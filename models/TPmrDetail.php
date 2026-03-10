<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pmr_detail".
 *
 * @property integer $pmr_detail_id
 * @property integer $pmr_id
 * @property integer $kayu_id
 * @property double $panjang
 * @property string $diameter_range
 * @property double $qty_m3
 * @property string $keterangan
 *
 * @property MKayu $kayu
 * @property TPmr $pmr
 */
class TPmrDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $total_m3;
    public static function tableName()
    {
        return 't_pmr_detail';
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
            [['pmr_id', 'kayu_id', 'diameter_range', 'qty_m3'], 'required'],
            [['pmr_id', 'kayu_id'], 'integer'],
            [['qty_m3','panjang'], 'number'],
            [['keterangan'], 'string'],
            [['diameter_range'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
            [['pmr_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPmr::className(), 'targetAttribute' => ['pmr_id' => 'pmr_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pmr_detail_id' => 'Pmr Detail',
                'pmr_id' => 'Pmr',
                'kayu_id' => 'Kayu',
                'panjang' => 'Panjang',
                'diameter_range' => 'Diameter Range',
                'qty_m3' => 'Qty M3',
                'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKayu()
    {
        return $this->hasOne(MKayu::className(), ['kayu_id' => 'kayu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPmr()
    {
        return $this->hasOne(TPmr::className(), ['pmr_id' => 'pmr_id']);
    }
}
