<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_po_ko".
 *
 * @property integer $po_ko_id
 * @property string $jenis_produk
 * @property string $kode
 * @property string $tanggal
 * @property integer $cust_id
 * @property string $nomor_po
 * @property string $tanggal_po
 * @property integer $sales_id
 * @property string $syarat_jual
 * @property string $sistem_bayar
 * @property string $cara_bayar
 * @property string $tanggal_kirim
 * @property string $alamat_bongkar
 * @property string $provinsi_bongkar
 * @property string $keterangan
 * @property integer $cancel_transaksi_id
 * @property boolean $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status_approval
 * @property string $approve_reason
 * @property string $reject_reason
 * @property string $tanggal_bayarmax
 * @property string $keterangan_bayar
 * @property double $top_hari
 * @property boolean $status_po
 * @property string $close_po
 * @property integer $invoice_lokal_id
 * @property string $kota_cust
 * @property string $data_piutang
 *
 * @property MCustomer $cust
 * @property MSales $sales
 * @property TPoKoDetail[] $tPoKoDetails
 */
class TPoKo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $cust_alamat, $customer, $maks_plafon, $sisa_piutang, $op_aktif, $sisa_plafon;
    public static function tableName()
    {
        return 't_po_ko';
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
            [['jenis_produk', 'kode', 'cust_id', 'tanggal_po', 'sales_id', 'syarat_jual', 'sistem_bayar', 'cara_bayar', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'tanggal_po', 'tanggal_kirim', 'created_at', 'updated_at', 'tanggal_bayarmax'], 'safe'],
            [['cust_id', 'sales_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['alamat_bongkar', 'keterangan', 'approve_reason', 'reject_reason', 'keterangan_bayar', 'close_po', 'data_piutang'], 'string'],
            [['status', 'status_po'], 'boolean'],
            [['top_hari'], 'number'],
            [['jenis_produk', 'kode', 'nomor_po', 'syarat_jual', 'cara_bayar', 'provinsi_bongkar'], 'string', 'max' => 50],
            [['sistem_bayar'], 'string', 'max' => 150],
            [['status_approval'], 'string', 'max' => 20],
            [['kota_cust'], 'string', 'max' => 100],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['sales_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSales::className(), 'targetAttribute' => ['sales_id' => 'sales_id']],
            [['invoice_lokal_id'], 'exist', 'skipOnError' => true, 'targetClass' => TInvoiceLokal::className(), 'targetAttribute' => ['invoice_lokal_id' => 'invoice_lokal_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'po_ko_id' => 'Po Ko',
                'jenis_produk' => 'Jenis Produk',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'cust_id' => 'Cust',
                'nomor_po' => 'Nomor PO',
                'tanggal_po' => 'Tanggal PO',
                'sales_id' => 'Sales',
                'syarat_jual' => 'Syarat Jual',
                'sistem_bayar' => 'Sistem Bayar',
                'cara_bayar' => 'Cara Bayar',
                'tanggal_kirim' => 'Tanggal Kirim',
                'alamat_bongkar' => 'Alamat Bongkar',
                'provinsi_bongkar' => 'Provinsi Bongkar',
                'keterangan' => 'Keterangan',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'status' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'status_approval' => 'Status Approval',
                'approve_reason' => 'Approve Reason',
                'reject_reason' => 'Reject Reason',
                'tanggal_bayarmax' => 'Tanggal Bayar Max',
                'keterangan_bayar' => 'Keterangan Bayar',
                'top_hari' => 'Top Hari',
                'status_po' => 'Status PO',
                'close_po' => 'Close PO',
                'kota_cust' => 'Kota Customer',
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
    public function getSales()
    {
        return $this->hasOne(MSales::className(), ['sales_id' => 'sales_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTPoKoDetails()
    {
        return $this->hasMany(TPoKoDetail::className(), ['po_ko_id' => 'po_ko_id']);
    }

    public static function getOptionList()
    {
        $res = self::find()
                ->select(" po_ko_id, kode, MAX(t_attachment.attachment_id) AS attachment_id")
                ->join("LEFT JOIN", "t_attachment", "t_attachment.reff_no = t_po_ko.kode")
                ->where("cancel_transaksi_id IS NULL and t_po_ko.status_approval = 'APPROVED' and attachment_id is not null")
                ->groupBy("t_po_ko.po_ko_id")
                ->orderBy('kode ASC')->all();
		$ret = [];
		foreach($res as $po){
			$ret[$po['po_ko_id']] = $po['kode'];
		}
        return $ret;
    }
} 