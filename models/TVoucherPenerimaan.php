<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_voucher_penerimaan".
 *
 * @property integer $voucher_penerimaan_id
 * @property string $kode
 * @property string $kode_bbm
 * @property string $tipe
 * @property string $tanggal
 * @property string $reff_no
 * @property integer $akun_kredit
 * @property string $mata_uang
 * @property string $cara_bayar
 * @property string $cara_bayar_reff
 * @property string $deskripsi
 * @property double $total_nominal
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $nota_penjualan_id
 * @property integer $invoice_id
 *
 * @property MAcctRekening $akunKredit
 * @property TCancelTransaksi $cancelTransaksi
 */
class TVoucherPenerimaan extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_voucher_penerimaan';
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
            [['kode', 'kode_bbm', 'tipe', 'tanggal', 'mata_uang', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at', 'total_nominal'], 'safe'],
            [['akun_kredit', 'cancel_transaksi_id', 'created_by', 'updated_by','nota_penjualan_id','invoice_id'], 'integer'],
            [['cara_bayar_reff','deskripsi'], 'string'],
            [['kode', 'kode_bbm', 'mata_uang', 'cara_bayar'], 'string', 'max' => 50],
            [['sender'], 'string', 'max' => 200],
            [['tipe', 'reff_no', 'status'], 'string', 'max' => 20],
            [['akun_kredit'], 'exist', 'skipOnError' => true, 'targetClass' => MAcctRekening::className(), 'targetAttribute' => ['akun_kredit' => 'acct_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['nota_penjualan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TNotaPenjualan::className(), 'targetAttribute' => ['nota_penjualan_id' => 'nota_penjualan_id']],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => TInvoice::className(), 'targetAttribute' => ['invoice_id' => 'invoice_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'voucher_penerimaan_id' => Yii::t('app', 'Voucher Penerimaan'),
                'kode' => Yii::t('app', 'Kode Voucher'),
                'kode_bbm' => Yii::t('app', 'Kode BBM'),
                'tipe' => Yii::t('app', 'Tipe'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'reff_no' => Yii::t('app', 'Reff No'),
                'akun_kredit' => Yii::t('app', 'Akun Kredit'),
                'mata_uang' => Yii::t('app', 'Mata Uang'),
                'cara_bayar' => Yii::t('app', 'Cara Bayar'),
                'cara_bayar_reff' => Yii::t('app', 'Cara Bayar Reff'),
                'sender' => Yii::t('app', 'Sender'),
                'deskripsi' => Yii::t('app', 'Deskripsi'),
                'total_nominal' => Yii::t('app', 'Total Nominal'),
                'status' => Yii::t('app', 'Status'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkunKredit()
    {
        return $this->hasOne(MAcctRekening::className(), ['acct_id' => 'akun_kredit']);
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
    public function getNotaPenjualan()
    {
        return $this->hasOne(TNotaPenjualan::className(), ['nota_penjualan_id' => 'nota_penjualan_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(TInvoice::className(), ['invoice_id' => 'invoice_id']);
    }
}
