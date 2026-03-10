<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_op_ko_detail_jasa".
 *
 * @property integer $op_ko_detail_jasa_id
 * @property integer $op_ko_id
 * @property integer $produk_id
 * @property double $qty_besar
 * @property string $satuan_besar
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property double $harga_hpp
 * @property double $harga_jual
 * @property string $keterangan
 *
 * @property MBrgProduk $produk
 * @property TOpKo $opKo
 */
class TOpKoDetailJasa extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $produk_nama,$qty_kecil_perpalet,$kubikasi_perpalet,$subtotal,$nomor_produksi_random;
    public static function tableName()
    {
        return 't_op_ko_detail_jasa';
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
            [['op_ko_id', 'produk_id', 'qty_besar', 'satuan_besar', 'qty_kecil', 'satuan_kecil', 'kubikasi', 'harga_hpp', 'harga_jual'], 'required'],
            [['op_ko_id', 'produk_id'], 'integer'],
            [['qty_besar', 'qty_kecil', 'kubikasi', 'harga_hpp', 'harga_jual'], 'number'],
            [['keterangan'], 'string'],
            [['satuan_besar', 'satuan_kecil'], 'string', 'max' => 50],
//            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['op_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpKo::className(), 'targetAttribute' => ['op_ko_id' => 'op_ko_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'op_ko_detail_jasa_id' => Yii::t('app', 'Op Ko Detail Jasa'),
                'op_ko_id' => Yii::t('app', 'Op Ko'),
                'produk_id' => Yii::t('app', 'Produk'),
                'qty_besar' => Yii::t('app', 'Qty Besar'),
                'satuan_besar' => Yii::t('app', 'Satuan Besar'),
                'qty_kecil' => Yii::t('app', 'Qty Kecil'),
                'satuan_kecil' => Yii::t('app', 'Satuan Kecil'),
                'kubikasi' => Yii::t('app', 'Kubikasi'),
                'harga_hpp' => Yii::t('app', 'Harga Hpp'),
                'harga_jual' => Yii::t('app', 'Harga Jual'),
                'keterangan' => Yii::t('app', 'Keterangan'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduk()
    {
        return $this->hasOne(MBrgProduk::className(), ['produk_id' => 'produk_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLimbah()
    {
        return $this->hasOne(MBrgLimbah::className(), ['limbah_id' => 'produk_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdukJasa()
    {
        return $this->hasOne(MProdukJasa::className(), ['produk_jasa_id' => 'produk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpKo()
    {
        return $this->hasOne(TOpKo::className(), ['op_ko_id' => 'op_ko_id']);
    }
}
