<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "t_mtrg_rotary_detail".
 *
 * @property integer $mtrg_rotary_detail_id
 * @property integer $mtrg_rotary_id
 * @property integer $suplier_id
 * @property string $unit
 * @property double $diameter
 * @property double $panjang
 * @property double $pcs
 * @property double $volume
 *
 * @property MSuplier $suplier
 */
class TMtrgRotaryDetail extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_mtrg_rotary_detail';
    }
    
    public function behaviors(){
		return [DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mtrg_rotary_id', 'suplier_id', 'unit', 'diameter', 'panjang', 'pcs', 'volume'], 'required'],
            [['mtrg_rotary_id', 'suplier_id'], 'integer'],
            [['diameter', 'panjang', 'pcs', 'volume'], 'number'],
            [['unit'], 'string', 'max' => 30],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'mtrg_rotary_detail_id' => Yii::t('app', 'Mtrg Rotary Detail'),
                'mtrg_rotary_id' => Yii::t('app', 'Mtrg Rotary'),
                'suplier_id' => Yii::t('app', 'Suplier'),
                'unit' => Yii::t('app', 'Unit'),
                'diameter' => Yii::t('app', 'Diameter'),
                'panjang' => Yii::t('app', 'Panjang'),
                'pcs' => Yii::t('app', 'Batang'),
                'volume' => Yii::t('app', 'Volume'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSuplier()
    {
        return $this->hasOne(MSuplier::className(), ['suplier_id' => 'suplier_id']);
    }
}
