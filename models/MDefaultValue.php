<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_default_value".
 *
 * @property integer $default_value_id
 * @property string $type
 * @property string $name
 * @property string $name_en
 * @property string $value
 * @property integer $sequence_number
 * @property boolean $active
 */
class MDefaultValue extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $size_p, $size_l;
    public static function tableName()
    {
        return 'm_default_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name', 'name_en', 'value'], 'required'],
            [['sequence_number'], 'integer'],
            [['active'], 'boolean'],
            [['type'], 'string', 'max' => 100],
            [['name', 'name_en', 'value'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'default_value_id' => Yii::t('app', 'Default Value ID'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'name_en' => Yii::t('app', 'Name En'),
            'value' => Yii::t('app', 'Value'),
            'sequence_number' => Yii::t('app', 'Sequence Number'),
            'active' => Yii::t('app', 'Active'),
        ];
    }       
    public static function getOptionList($type)
    {
        $res = self::find()->where(['active'=>true,'type'=>$type])->orderBy('sequence_number ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'value', 'name');
    }
    public static function getOptionListProduk($type)
    {
        $res = self::find()->where(['active'=>true,'type'=>$type])->andWhere(['not in','name', ['Log','Limbah','JasaKD','JasaGesek','JasaMoulding']])->orderBy('sequence_number ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'value', 'name');
    }
    public static function getOptionListLabelValue($type)
    {
        $res = self::find()->where(['active'=>true,'type'=>$type])->orderBy('sequence_number ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'value', 'value');
    }
    public static function getOptionListBuatPL($type)
    {
        $res = self::find()->where(['active'=>true,'type'=>$type])->andWhere(['not in','name', ['Log','Limbah','JasaKD','JasaGesek','JasaMoulding']])->orderBy('sequence_number ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'value', 'name');
    }    
    public static function getOptionListWithEN($type)
    {
        $res = self::find()->where(['active'=>true,'type'=>$type])->orderBy('sequence_number ASC')->all();
        foreach($res as $i => $val){
            $return[$val->value."|".$val->name_en] = $val->name;
        }
        return $return;
    }
	public static function getOptionListCustom($type,$notin,$ordering)
    {
        $res = self::find()->where(['active'=>true,'type'=>$type])->andWhere("name NOT IN(".$notin.")")->orderBy('sequence_number '.$ordering)->all();
        return \yii\helpers\ArrayHelper::map($res, 'value', 'name');
    }
	public static function getOneValueByAttributes($type,$name,$return_attributes)
    {
        return self::findOne(['type'=>$type,'name'=>$name])->$return_attributes;
    }
	public static function getOneByValue($type,$value,$return_attributes)
    {
        return self::findOne(['type'=>$type,'value'=>$value])->$return_attributes;
    }
}
