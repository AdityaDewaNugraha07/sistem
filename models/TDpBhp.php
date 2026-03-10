<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_dp_bhp".
 *
 * @property integer $dp_bhp_id
 * @property string $kode
 * @property string $cara_bayar
 * @property string $tanggal
 * @property double $nominal
 * @property string $keterangan
 * @property integer $suplier_id
 * @property integer $pembayaran_voucher
 * @property integer $pemakaian_voucher
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 * @property boolean $is_faktur
 * @property integer $cancel_transaksi_id
 * @property string $mata_uang
 *
 * @property MSuplier $suplier
 * @property TCancelTransaksi $cancelTransaksi
 * @property TVoucherPengeluaran $pembayaranVoucher
 * @property TVoucherPengeluaran $pemakaianVoucher
 */ 
class TDpBhp extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_dp_bhp';
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
            [['kode', 'tanggal', 'created_at', 'created_by', 'updated_at', 'updated_by','nominal'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['keterangan'], 'string'],
            [['suplier_id', 'pembayaran_voucher', 'pemakaian_voucher', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['is_faktur'], 'boolean'],
            [['kode', 'cara_bayar', 'status'], 'string', 'max' => 50],
            [['mata_uang'], 'string', 'max' => 20],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['pembayaran_voucher'], 'exist', 'skipOnError' => true, 'targetClass' => TVoucherPengeluaran::className(), 'targetAttribute' => ['pembayaran_voucher' => 'voucher_pengeluaran_id']],
            [['pemakaian_voucher'], 'exist', 'skipOnError' => true, 'targetClass' => TVoucherPengeluaran::className(), 'targetAttribute' => ['pemakaian_voucher' => 'voucher_pengeluaran_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'dp_bhp_id' => Yii::t('app', 'Dp Bhp'),
			'kode' => Yii::t('app', 'Kode'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'nominal' => Yii::t('app', 'Nominal'),
			'keterangan' => Yii::t('app', 'Keterangan'),
			'suplier_id' => Yii::t('app', 'Suplier'),
			'pembayaran_voucher' => Yii::t('app', 'Pembayaran Voucher'),
			'pemakaian_voucher' => Yii::t('app', 'Pemakaian Voucher'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'status' => Yii::t('app', 'Status Bayar'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'mata_uang' => Yii::t('app', 'Mata Uang'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuplier()
    {
        return $this->hasOne(MSuplier::className(), ['suplier_id' => 'suplier_id']);
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
    public function getPembayaranVoucher()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'pembayaran_voucher']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPemakaianVoucher()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'pemakaian_voucher']);
    }
	
	public function getDefaultValue()
    {
        return $this->hasOne(MDefaultValue::className(), ['value' => 'mata_uang']);
    } 
}
