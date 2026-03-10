<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_hasil_orientasi_kuantitas".
 *
 * @property integer $hasil_orientasi_kuantitas_id
 * @property integer $hasil_orientasi_id
 * @property integer $kayu_id
 * @property string $diameter_cm
 * @property integer $qty_batang
 * @property double $qty_m3
 * @property string $keterangan
 */
class THasilOrientasiKuantitas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_hasil_orientasi_kuantitas';
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
            [['hasil_orientasi_id', 'kayu_id', 'diameter_cm', 'qty_batang', 'qty_m3'], 'required'],
            [['hasil_orientasi_id', 'kayu_id', 'qty_batang'], 'integer'],
            [['qty_m3'], 'number'],
            [['keterangan'], 'string'],
            [['diameter_cm'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'hasil_orientasi_kuantitas_id' => 'Hasil Orientasi Kuantitas',
                'hasil_orientasi_id' => 'Hasil Orientasi',
                'kayu_id' => 'Kayu',
                'diameter_cm' => 'Diameter Cm',
                'qty_batang' => 'Qty Batang',
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
}
