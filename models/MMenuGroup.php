<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_menu_group".
 *
 * @property integer $menu_group_id
 * @property string $name
 * @property string $othername
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $sequence
 *
 * @property MMenu[] $mMenus
 */
class MMenuGroup extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_menu_group';
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
            [['created_by', 'updated_by', 'sequence'], 'integer'],
            [['name', 'othername'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_group_id' => Yii::t('app', 'Menu Group'),
            'name' => Yii::t('app', 'Name'),
            'othername' => Yii::t('app', 'Other Name'),
            'active' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
            'sequence' => Yii::t('app', 'Sequence'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMMenus()
    {
        return $this->hasMany(MMenu::className(), ['menu_group_id' => 'menu_group_id']);
    }
    
    public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('sequence ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'menu_group_id', 'name');
        
    }
}
