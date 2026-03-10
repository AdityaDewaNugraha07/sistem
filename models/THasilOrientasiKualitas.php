<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_hasil_orientasi_kualitas".
 *
 * @property integer $hasil_orientasi_kualitas_id
 * @property integer $hasil_orientasi_id
 * @property integer $kayu_id
 * @property integer $qty_batang
 * @property double $qty_m3
 * @property boolean $bekas_pilih
 * @property string $usia_tebang
 * @property string $kondisi_global
 * @property string $kondisi_total
 * @property string $keterangan
 */
class THasilOrientasiKualitas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $ut_13, $ut_45, $ut_68, $ut_99, $kg_sehat, $kg_rusak, $kt_gr, $kt_pecah;
    public static function tableName()
    {
        return 't_hasil_orientasi_kualitas';
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
            [['hasil_orientasi_id', 'kayu_id', 'qty_batang', 'qty_m3', 'usia_tebang', 'kondisi_global', 'kondisi_total'], 'required'],
            [['hasil_orientasi_id', 'kayu_id', 'qty_batang'], 'integer'],
            [['qty_m3'], 'number'],
            [['bekas_pilih'], 'boolean'],
            [['usia_tebang', 'kondisi_global', 'kondisi_total', 'keterangan'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'hasil_orientasi_kualitas_id' => 'Hasil Orientasi Kualitas',
                'hasil_orientasi_id' => 'Hasil Orientasi',
                'kayu_id' => 'Kayu',
                'qty_batang' => 'Qty Batang',
                'qty_m3' => 'Qty M3',
                'bekas_pilih' => 'Bekas Pilih',
                'usia_tebang' => 'Usia Tebang',
                'kondisi_global' => 'Kondisi Global',
                'kondisi_total' => 'Kondisi Total',
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
