<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "m_menu".
 *
 * @property integer $menu_id
 * @property integer $module_id
 * @property integer $menu_group_id
 * @property string $name
 * @property string $othername
 * @property string $url
 * @property string $icon
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $sequence
 *
 * @property MMenuGroup $menuGroup
 * @property MModule $module
 */
class MMenu extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_menu';
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
            [['module_id', 'menu_group_id', 'name', 'othername', 'url', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['module_id', 'menu_group_id', 'created_by', 'updated_by', 'sequence'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'othername', 'icon'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 200],
//            [['url'], 'unique'],
            [['menu_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => MMenuGroup::className(), 'targetAttribute' => ['menu_group_id' => 'menu_group_id']],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => MModule::className(), 'targetAttribute' => ['module_id' => 'module_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => Yii::t('app', 'Menu'),
            'module_id' => Yii::t('app', 'Module'),
            'menu_group_id' => Yii::t('app', 'Menu Group'),
            'name' => Yii::t('app', 'Name'),
            'othername' => Yii::t('app', 'Other Name'),
            'url' => Yii::t('app', 'URL'),
            'icon' => Yii::t('app', 'Icon'),
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
    public function getMenuGroup()
    {
        return $this->hasOne(MMenuGroup::className(), ['menu_group_id' => 'menu_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(MModule::className(), ['module_id' => 'module_id']);
    }
    
    public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('sequence ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'menu_id', 'name');
    }

    public static function getMenuByCurrentURL($menu_name)
    {
       $query = "SELECT * FROM m_menu WHERE name = '". $menu_name."' AND active IS TRUE";
       return Yii::$app->db->createCommand($query)->queryOne();
    }
}
