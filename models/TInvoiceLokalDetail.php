<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_invoice_lokal_detail".
 *
 * @property integer $invoice_lokal_detail_id
 * @property integer $invoice_lokal_id
 * @property integer $spm_ko_id
 * @property integer $nota_penjualan_id
 * @property integer $nota_penjualan_detail_id
 * @property integer $produk_id
 * @property double $qty_besar
 * @property string $satuan_besar
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property double $harga_nota
 * @property double $harga_invoice
 * @property double $ppn
 * @property double $pph
 * @property double $potongan
 * @property string $keterangan
 *
 * @property TInvoiceLokal $invoiceLokal
 * @property TNotaPenjualan $notaPenjualan
 * @property TNotaPenjualanDetail $notaPenjualanDetail
 * @property TSpmKo $spmKo
 */
class TInvoiceLokalDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $subtotal;
    public static function tableName()
    {
        return 't_invoice_lokal_detail';
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
            [['invoice_lokal_id', 'spm_ko_id', 'nota_penjualan_id', 'nota_penjualan_detail_id', 'produk_id', 'qty_besar', 'satuan_besar', 'qty_kecil', 'satuan_kecil', 'kubikasi', 'harga_nota', 'harga_invoice'], 'required'],
            [['invoice_lokal_id', 'spm_ko_id', 'nota_penjualan_id', 'nota_penjualan_detail_id', 'produk_id'], 'integer'],
            [['qty_besar', 'qty_kecil', 'kubikasi', 'harga_nota', 'harga_invoice', 'ppn', 'pph', 'potongan'], 'number'],
            [['keterangan'], 'string'],
            [['satuan_besar', 'satuan_kecil'], 'string', 'max' => 50],
            [['invoice_lokal_id'], 'exist', 'skipOnError' => true, 'targetClass' => TInvoiceLokal::className(), 'targetAttribute' => ['invoice_lokal_id' => 'invoice_lokal_id']],
            [['nota_penjualan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TNotaPenjualan::className(), 'targetAttribute' => ['nota_penjualan_id' => 'nota_penjualan_id']],
            [['nota_penjualan_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => TNotaPenjualanDetail::className(), 'targetAttribute' => ['nota_penjualan_detail_id' => 'nota_penjualan_detail_id']],
            [['spm_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpmKo::className(), 'targetAttribute' => ['spm_ko_id' => 'spm_ko_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'invoice_lokal_detail_id' => 'Invoice Lokal Detail',
                'invoice_lokal_id' => 'Invoice Lokal',
                'spm_ko_id' => 'Spm Ko',
                'nota_penjualan_id' => 'Nota Penjualan',
                'nota_penjualan_detail_id' => 'Nota Penjualan Detail',
                'produk_id' => 'Produk',
                'qty_besar' => 'Qty Besar',
                'satuan_besar' => 'Satuan Besar',
                'qty_kecil' => 'Qty Kecil',
                'satuan_kecil' => 'Satuan Kecil',
                'kubikasi' => 'Kubikasi',
                'harga_nota' => 'Harga Nota',
                'harga_invoice' => 'Harga Invoice',
                'ppn' => 'Ppn',
                'pph' => 'Pph',
                'potongan' => 'Potongan',
                'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceLokal()
    {
        return $this->hasOne(TInvoiceLokal::className(), ['invoice_lokal_id' => 'invoice_lokal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotaPenjualan()
    {
        return $this->hasOne(TNotaPenjualan::className(), ['nota_penjualan_id' => 'nota_penjualan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotaPenjualanDetail()
    {
        return $this->hasOne(TNotaPenjualanDetail::className(), ['nota_penjualan_detail_id' => 'nota_penjualan_detail_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpmKo()
    {
        return $this->hasOne(TSpmKo::className(), ['spm_ko_id' => 'spm_ko_id']);
    }
}
