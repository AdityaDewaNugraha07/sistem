<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_ppk".
 *
 * @property integer $ppk_id
 * @property string $tipe
 * @property string $kode
 * @property string $tanggal
 * @property double $nominal
 * @property string $keperluan
 * @property string $tanggal_diperlukan
 * @property string $status
 * @property integer $voucher_pengeluaran_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property boolean $is_terimatopup
 * @property integer $kas_bon_id
 *
 * @property TKasBon $kasBon
 * @property TVoucherPengeluaran $voucherPengeluaran
 */ 
class TPpk extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_ppk';
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
            [['tipe', 'kode', 'tanggal', 'nominal', 'keperluan', 'tanggal_diperlukan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'tanggal_diperlukan', 'created_at', 'updated_at'], 'safe'],
            [['keperluan'], 'string'],
            [['voucher_pengeluaran_id', 'created_by', 'updated_by', 'cancel_transaksi_id', 'kas_bon_id'], 'integer'],
            [['is_terimatopup'], 'boolean'],
            [['tipe'], 'string', 'max' => 20],
            [['kode'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 30],
            [['kas_bon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TKasBon::className(), 'targetAttribute' => ['kas_bon_id' => 'kas_bon_id']],
            [['voucher_pengeluaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => TVoucherPengeluaran::className(), 'targetAttribute' => ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'ppk_id' => Yii::t('app', 'Ppk'),
                'tipe' => Yii::t('app', 'Tipe'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'nominal' => Yii::t('app', 'Nominal'),
                'keperluan' => Yii::t('app', 'Keperluan'),
                'tanggal_diperlukan' => Yii::t('app', 'Tanggal Diperlukan'),
                'status' => Yii::t('app', 'Status'),
                'voucher_pengeluaran_id' => Yii::t('app', 'Voucher Pengeluaran'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
				'is_terimatopup' => Yii::t('app', 'Is Terimatopup'),
				'kas_bon_id' => Yii::t('app', 'Kas Bon'),
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getKasBon()
    {
        return $this->hasOne(TKasBon::className(), ['kas_bon_id' => 'kas_bon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherPengeluaran()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    } 
}
