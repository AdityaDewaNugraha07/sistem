<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_user".
 *
 * @property integer $user_id
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 * @property boolean $active
 * @property boolean $login_status
 * @property integer $user_group_id
 * @property string $last_login_time
 * @property integer $user_profile_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $pegawai_id
 *
 * @property MPegawai $pegawai
 * @property MUserGroup $userGroup
 * @property MUserProfile $userProfile
 */
class MUser extends \app\models\DeltaBaseActiveRecord implements \yii\web\IdentityInterface
{
    public $newpassword;
    public $renewpassword;    
    const SCENARIO_CHANGE_PASS = 'changepassword';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_user';
    }
    
    public function behaviors(){
		return [\app\components\DeltaGeneralBehavior::className()];
	}
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CHANGE_PASS] = ['password', 'newpassword','renewpassword']; // menentukan attributes mana saja yg digunakan saat validate
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'user_group_id', 'user_profile_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'pegawai_id'], 'required'],
            [['active', 'login_status'], 'boolean'],
            [['user_group_id', 'user_profile_id', 'created_by', 'updated_by', 'pegawai_id'], 'integer'],
            [['last_login_time', 'created_at', 'updated_at'], 'safe'],
            [['username'], 'string', 'max' => 100],
            [['password', 'authKey', 'accessToken'], 'string', 'max' => 200],
            [['newpassword','renewpassword'], 'string', 'min' => 5],
            [['username'], 'unique'],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['user_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => MUserGroup::className(), 'targetAttribute' => ['user_group_id' => 'user_group_id']],
            [['user_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => MUserProfile::className(), 'targetAttribute' => ['user_profile_id' => 'user_profile_id']],
            [['password','newpassword','renewpassword'], 'required', 'on' => self::SCENARIO_CHANGE_PASS],
            [['newpassword'], 'changePassword', 'on' => self::SCENARIO_CHANGE_PASS],
            ['renewpassword','compare','compareAttribute'=>'newpassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'authKey' => Yii::t('app', 'Auth Key'),
            'accessToken' => Yii::t('app', 'Access Token'),
            'active' => Yii::t('app', 'Active'),
            'login_status' => Yii::t('app', 'Login Status'),
            'user_group_id' => Yii::t('app', 'User Group'),
            'last_login_time' => Yii::t('app', 'Last Login Time'),
            'user_profile_id' => Yii::t('app', 'User Profile'),
            'created_at' => Yii::t('app', 'Create Time'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
            'newpassword' => Yii::t('app', 'New Password'),
            'renewpassword' => Yii::t('app', 'Re-New Password'),
            'pegawai_id' => Yii::t('app', 'Nama Pegawai'),
        ];
    }
    
    public function getPegawai()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroup()
    {
        return $this->hasOne(MUserGroup::className(), ['user_group_id' => 'user_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(MUserProfile::className(), ['user_profile_id' => 'user_profile_id']);
    }
    
    public static function findIdentity($id){
		return static::findOne($id);
    }
    
    public static function findIdentityByAccessToken($token, $type = null){
        return self::findOne(['accessToken'=>$token]);
    }

	public function getId(){
		return $this->user_id;
	}

	public function getAuthKey(){
		return $this->authKey;//Here I return a value of my authKey column
	}

	public function validateAuthKey($authKey){
		return $this->authKey === $authKey;
	}
    
	public static function findByUsername($username){
		return self::findOne(['username'=>$username]);
	}
	public static function findActiveByUsername($username){
		return self::findOne(['username'=>$username,'active'=>true]);
	}
    
    public function validatePassword($password_post){
        return Yii::$app->getSecurity()->validatePassword($password_post, $this->password);
    }
    
    public function hashPassword($password) {
        return Yii::$app->getSecurity()->generatePasswordHash($password);
    }
    
    public function matchNewPassword(){
        if ($this->newpassword === $this->renewpassword) {
            return true;
        }else{
            $this->addError('renewpassword', 'Renew-password is Incorrect.');
            return false;
        }
    }
    
    public function changePassword(){
        if (!$this->hasErrors()) {
            $modelUserIdentity = self::findIdentity(\Yii::$app->user->id);
            if($modelUserIdentity->validatePassword($this->password)){
                if($this->matchNewPassword()){
                    if($modelUserIdentity->validatePassword($this->newpassword)){
                        $this->addError('newpassword', Yii::t('app', 'New Password must different with current password'));
                    }
                }
            }else{
                $this->addError('password', Yii::t('app', 'Incorrect password'));
            }
        }
    }
}
