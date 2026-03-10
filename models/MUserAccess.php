<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_user_access".
 *
 * @property integer $user_access_id
 * @property integer $user_group_id
 * @property integer $menu_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MUserAccess extends \app\models\DeltaBaseActiveRecord
{
    public $menu_group_id,$module_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_user_access';
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
            [['user_group_id', 'menu_id', 'created_at', 'created_by', 'updated_at', 'updated_by','menu_group_id','module_id'], 'required'],
            [['user_group_id', 'menu_id', 'created_by', 'updated_by','menu_group_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_access_id' => Yii::t('app', 'User Access ID'),
            'user_group_id' => Yii::t('app', 'User Group ID'),
            'menu_id' => Yii::t('app', 'Menu'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
            'menu_group_id' => Yii::t('app', 'Menu Group'),
            'module_id' => Yii::t('app', 'Module'),
        ];
    }
    
    public static function getFromUserGroupAndMenu($user_group_id,$menu_id)
    {
        $res = self::find()->where(['user_group_id'=>$user_group_id,'menu_id'=>$menu_id])->one();
        return $res->user_access_id;
        
    }
}
