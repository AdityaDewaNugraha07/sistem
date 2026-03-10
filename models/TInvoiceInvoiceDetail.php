<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_invoice_invoice_detail".
 *
 * @property integer $invoice_invoice_detail_id
 * @property integer $invoice_lokal_id
 * @property string $deskripsi_nota
 * @property string $deskripsi_invoice
 * @property double $ppn
 * @property double $pph
 * @property double $potongan
 *
 * @property TInvoiceLokal $invoiceLokal
 */
class TInvoiceInvoiceDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $produk_id, $qty_kecil, $kubikasi, $harga_nota, $harga_invoice, $subtotal;
    public $uraian, $kubikasi_inv, $harga_inv, $total_inv;
    public $ppn_berikat;
    public static function tableName()
    {
        return 't_invoice_invoice_detail';
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
            [['invoice_lokal_id'], 'required'],
            [['invoice_lokal_id'], 'integer'],
            [['deskripsi_nota', 'deskripsi_invoice'], 'string'],
            [['ppn', 'pph', 'potongan'], 'number'],
            [['invoice_lokal_id'], 'exist', 'skipOnError' => true, 'targetClass' => TInvoiceLokal::className(), 'targetAttribute' => ['invoice_lokal_id' => 'invoice_lokal_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'invoice_invoice_detail_id' => 'Invoice Invoice Detail',
                'invoice_lokal_id' => 'Invoice Lokal',
                'deskripsi_nota' => 'Deskripsi Nota',
                'deskripsi_invoice' => 'Deskripsi Invoice',
                'ppn' => 'Ppn',
                'pph' => 'Pph',
                'potongan' => 'Potongan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceLokal()
    {
        return $this->hasOne(TInvoiceLokal::className(), ['invoice_lokal_id' => 'invoice_lokal_id']);
    }
} 