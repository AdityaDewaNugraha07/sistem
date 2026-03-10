<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_invoice_lokal".
 *
 * @property integer $invoice_lokal_id
 * @property string $kode
 * @property string $tanggal
 * @property string $jenis_produk
 * @property integer $op_ko_id
 * @property integer $cust_id
 * @property string $cust_no_npwp
 * @property string $no_faktur_pajak
 * @property string $cara_bayar
 * @property integer $penerbit
 * @property string $mata_uang
 * @property boolean $include_ppn
 * @property double $total_harga
 * @property double $total_ppn
 * @property double $total_pph
 * @property double $total_potongan
 * @property double $total_bayar
 * @property string $status
 * @property string $keterangan_potongan
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $nota_penjualan
 * @property integer $bank_id
 * @property boolean $kawasan_berikat
 * @property boolean $ceklis_pph
 *
 * @property TInvoiceInvoiceDetail[] $tInvoiceInvoiceDetails
 * @property MCustomer $cust
 * @property MPegawai $penerbit0
 * @property TCancelTransaksi $cancelTransaksi
 * @property TOpKo $opKo
 * @property TInvoiceLokalDetail[] $tInvoiceLokalDetails
 */
class TInvoiceLokal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kode1,$kode2,$kode3,$kode4,$kode5,$cust_an_alamat, $po_ko_id;
    public static function tableName()
    {
        return 't_invoice_lokal';
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
            [['kode', 'tanggal', 'jenis_produk', 'cust_id', 'cust_no_npwp', 'cara_bayar', 'bank_id','penerbit', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['op_ko_id', 'cust_id', 'penerbit', 'cancel_transaksi_id', 'created_by', 'updated_by', 'bank_id'], 'integer'],
            [['total_harga', 'total_ppn', 'total_pph', 'total_potongan', 'total_bayar'], 'number'],
            [['keterangan_potongan', 'nota_penjualan'], 'string'],
            [['kode'], 'string', 'max' => 25],
            [['jenis_produk', 'no_faktur_pajak', 'cara_bayar', 'mata_uang', 'status'], 'string', 'max' => 50],
            [['cust_no_npwp'], 'string', 'max' => 30],
            [['include_ppn', 'kawasan_berikat', 'ceklis_pph'], 'boolean'],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['penerbit'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['penerbit' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['op_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpKo::className(), 'targetAttribute' => ['op_ko_id' => 'op_ko_id']],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBank::className(), 'targetAttribute' => ['bank_id' => 'bank_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'invoice_lokal_id' => 'Invoice Lokal',
                'kode' => 'Nomor',
                'tanggal' => 'Tanggal',
                'jenis_produk' => 'Jenis Produk',
                'op_ko_id' => 'Kode OP',
                'cust_id' => 'Cust',
                'cust_no_npwp' => 'Cust No Npwp',
                'no_faktur_pajak' => 'No Faktur Pajak',
                'cara_bayar' => 'Cara Bayar',
                'penerbit' => 'Penerbit',
                'mata_uang' => 'Mata Uang',
                'total_harga' => 'Total Harga',
                'total_ppn' => 'Total Ppn',
                'total_pph' => 'Total Pph',
                'total_potongan' => 'Total Potongan',
                'total_bayar' => 'Total Bayar',
                'status' => 'Status',
                'keterangan_potongan' => 'Keterangan Potongan',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cust_an_alamat' => 'Alamat',
                'include_ppn' => 'Include PPN',
                'nota_penjualan' => 'Nota Penjualan',
                'bank_id' => 'Bank',
                'kawasan_berikat' => 'Kawasan Berikat',
                'ceklis_pph' => 'Include PPh'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'cust_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenerbit0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'penerbit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
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
    public function getTInvoiceLokalDetails()
    {
        return $this->hasMany(TInvoiceLokalDetail::className(), ['invoice_lokal_id' => 'invoice_lokal_id']);
    }

    public function getBank()
    {
        return $this->hasOne(MBank::className(), ['bank_id' => 'bank_id']);
    }
}
