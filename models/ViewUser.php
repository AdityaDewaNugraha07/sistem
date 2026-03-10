<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_user".
 *
 * @property integer $user_id
 * @property string $username
 * @property string $password
 * @property string $authenkey
 * @property string $passwordtoken
 * @property boolean $active
 * @property boolean $login_status
 * @property integer $user_group_id
 * @property string $name
 * @property string $othername
 * @property string $last_login_time
 * @property integer $user_profile_id
 * @property integer $pegawai_id
 * @property string $pegawai_nama
 * @property integer $pegawai_nik
 * @property integer $departement_id
 */
class ViewUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_user';
    }
    
    public function behaviors(){
		return [\app\components\DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_group_id', 'user_profile_id', 'pegawai_id', 'pegawai_nik', 'departement_id'], 'integer'],
            [['active', 'login_status'], 'boolean'],
            [['last_login_time'], 'safe'],
            [['username', 'name', 'othername', 'pegawai_nama'], 'string', 'max' => 100],
            [['password', 'authenkey', 'passwordtoken'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'user_id' => 'User',
                'username' => 'Username',
                'password' => 'Password',
                'authenkey' => 'Authenkey',
                'passwordtoken' => 'Passwordtoken',
                'active' => 'Status',
                'login_status' => 'Login Status',
                'user_group_id' => 'User Group',
                'name' => 'Name',
                'othername' => 'Othername',
                'last_login_time' => 'Last Login Time',
                'user_profile_id' => 'User Profile',
                'pegawai_id' => 'Pegawai',
                'pegawai_nama' => 'Pegawai Nama',
                'pegawai_nik' => 'Pegawai Nik',
                'departement_id' => 'Departement',
        ];
    }
    public function getPegawai()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }
    public function getDepartement()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'departement_id']);
    }
}
