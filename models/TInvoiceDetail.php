<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "t_invoice_detail".
 *
 * @property integer $invoice_detail_id
 * @property integer $invoice_id
 * @property integer $produk_id
 * @property double $qty_besar
 * @property string $satuan_besar
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property double $harga_hpp
 * @property double $harga_jual
 * @property double $ppn
 * @property double $pph
 * @property double $potongan
 * @property string $keterangan
 * @property integer $kubikasi_display
 * @property MBrgProduk $produk
 * @property TInvoice $invoice
 */
class TInvoiceDetail extends DeltaBaseActiveRecord
{
    public $subtotal, $subtotal_display, $grade, $kubikasi_grouping;

    public static function tableName()
    {
        return 't_invoice_detail';
    }

    public function behaviors()
    {
        return [DeltaGeneralBehavior::className()];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'produk_id', 'qty_besar', 'satuan_besar', 'qty_kecil', 'satuan_kecil', 'kubikasi', 'harga_hpp', 'harga_jual'], 'required'],
            [['invoice_id', 'produk_id'], 'integer'],
            [['qty_besar', 'qty_kecil', 'kubikasi', 'kubikasi_display', 'harga_hpp', 'harga_jual', 'ppn', 'pph', 'potongan'], 'number'],
            [['keterangan'], 'string'],
            [['satuan_besar', 'satuan_kecil'], 'string', 'max' => 50],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => TInvoice::className(), 'targetAttribute' => ['invoice_id' => 'invoice_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_detail_id' => Yii::t('app', 'Invoice Detail'),
            'invoice_id' => Yii::t('app', 'Invoice'),
            'produk_id' => Yii::t('app', 'Produk'),
            'qty_besar' => Yii::t('app', 'Qty Besar'),
            'satuan_besar' => Yii::t('app', 'Satuan Besar'),
            'qty_kecil' => Yii::t('app', 'Qty Kecil'),
            'satuan_kecil' => Yii::t('app', 'Satuan Kecil'),
            'kubikasi' => Yii::t('app', 'Kubikasi'),
            'kubikasi_display' => Yii::t('app', 'Kubikasi'),
            'harga_hpp' => Yii::t('app', 'Harga Hpp'),
            'harga_jual' => Yii::t('app', 'Harga Jual'),
            'ppn' => Yii::t('app', 'Ppn'),
            'pph' => Yii::t('app', 'Pph'),
            'potongan' => Yii::t('app', 'Potongan'),
            'keterangan' => Yii::t('app', 'Keterangan'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProduk()
    {
        return $this->hasOne(MBrgProduk::className(), ['produk_id' => 'produk_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(TInvoice::className(), ['invoice_id' => 'invoice_id']);
    }
}
