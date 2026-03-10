<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_jasa".
 *
 * @property integer $terima_jasa_id
 * @property string $tanggal
 * @property string $nopol
 * @property string $jenis
 * @property string $nomor_palet
 * @property integer $op_ko_id
 * @property integer $op_ko_detail_id
 * @property integer $produk_jasa_id
 * @property double $p
 * @property string $p_satuan
 * @property double $l
 * @property string $l_satuan
 * @property double $t
 * @property string $t_satuan
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property string $keterangan
 *
 * @property MProdukJasa $produkJasa
 * @property TOpKo $opKo
 * @property TOpKoDetail $opKoDetail
 */
class TTerimaJasa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_terima_jasa';
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
            [['tanggal', 'nopol', 'jenis', 'nomor_palet', 'op_ko_id', 'op_ko_detail_id', 'produk_jasa_id', 'p_satuan', 'l_satuan', 't_satuan', 'qty_kecil', 'satuan_kecil', 'kubikasi', 'qty_kecil_actual', 'kubikasi_actual'], 'required'],
            [['tanggal'], 'safe'],
            [['op_ko_id', 'op_ko_detail_id', 'produk_jasa_id'], 'integer'],
            [['p', 'l', 't', 'qty_kecil', 'kubikasi', 'kubikasi_actual', 'qty_kecil_actual'], 'number'],
            [['keterangan'], 'string'],
            [['nopol'], 'string', 'max' => 20],
            [['jenis', 'nomor_palet', 'p_satuan', 'l_satuan', 't_satuan', 'satuan_kecil'], 'string', 'max' => 50],
            [['produk_jasa_id'], 'exist', 'skipOnError' => true, 'targetClass' => MProdukJasa::className(), 'targetAttribute' => ['produk_jasa_id' => 'produk_jasa_id']],
            [['op_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpKo::className(), 'targetAttribute' => ['op_ko_id' => 'op_ko_id']],
            [['op_ko_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpKoDetail::className(), 'targetAttribute' => ['op_ko_detail_id' => 'op_ko_detail_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'terima_jasa_id' => 'Terima Jasa',
                'tanggal' => 'Tanggal',
                'nopol' => 'Nopol',
                'jenis' => 'Jenis',
                'nomor_palet' => 'Nomor Palet',
                'op_ko_id' => 'Op Ko',
                'op_ko_detail_id' => 'Op Ko Detail',
                'produk_jasa_id' => 'Produk Jasa',
                'p' => 'P',
                'p_satuan' => 'P Satuan',
                'l' => 'L',
                'l_satuan' => 'L Satuan',
                't' => 'T',
                't_satuan' => 'T Satuan',
                'qty_kecil' => 'Qty Kecil',
                'satuan_kecil' => 'Satuan Kecil',
                'kubikasi' => 'Kubikasi',
                'qty_kecil_actual' => 'Qty Aktual',
                'kubikasi_actual' => 'Volume Aktual',
                'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdukJasa()
    {
        return $this->hasOne(MProdukJasa::className(), ['produk_jasa_id' => 'produk_jasa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpKo()
    {
        return $this->hasOne(TOpKo::className(), ['op_ko_id' => 'op_ko_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpKoDetail()
    {
        return $this->hasOne(TOpKoDetail::className(), ['op_ko_detail_id' => 'op_ko_detail_id']);
    }
}
