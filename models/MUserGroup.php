<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_user_group".
 *
 * @property integer $user_group_id
 * @property string $name
 * @property string $othername
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MUser[] $mUsers
 */
class MUserGroup extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_user_group';
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
            [['name', 'othername', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['name', 'othername'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_group_id' => Yii::t('app', 'User Group ID'),
            'name' => Yii::t('app', 'Name'),
            'othername' => Yii::t('app', 'Other Name'),
            'active' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMUsers()
    {
        return $this->hasMany(MUser::className(), ['user_group_id' => 'user_group_id']);
    }
    
    public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->andWhere(['not in','user_group_id', \app\components\Params::USER_GROUP_ID_SUPER_USER])->orderBy('name ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'user_group_id', 'name');
        
    }
}
