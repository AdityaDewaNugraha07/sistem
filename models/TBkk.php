<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_bkk".
 *
 * @property integer $bkk_id
 * @property string $tipe
 * @property string $kode
 * @property string $tanggal
 * @property string $deskripsi
 * @property double $totalnominal
 * @property integer $diterima_oleh
 * @property integer $dibuat_oleh
 * @property string $status
 * @property integer $voucher_pengeluaran_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property boolean $ganti_uangkas
 * @property string $tbp_reff
 *
 * @property MPegawai $diterimaOleh
 * @property MPegawai $dibuatOleh
 * @property TVoucherPengeluaran $voucherPengeluaran
 * @property TKasBon[] $tKasBons
 */ 
class TBkk extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $detail_deskripsi,$detail_nominal,$kas_bon_id,$kode_kasbon,$kode_tbp;
    public static function tableName()
    {
        return 't_bkk';
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
            [['tipe', 'kode', 'tanggal', 'deskripsi', 'totalnominal', 'diterima_oleh', 'dibuat_oleh', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['deskripsi', 'tbp_reff'], 'string'],
            [['totalnominal'], 'number'],
            [['dibuat_oleh', 'voucher_pengeluaran_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['ganti_uangkas'], 'boolean'],
            [['tipe'], 'string', 'max' => 20],
            [['kode'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 30],
            [['kode'], 'unique'],
            [['dibuat_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['dibuat_oleh' => 'pegawai_id']],
            [['voucher_pengeluaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => TVoucherPengeluaran::className(), 'targetAttribute' => ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']],
        ];  
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'bkk_id' => Yii::t('app', 'Bkk'),
			'tipe' => Yii::t('app', 'Tipe'),
			'kode' => Yii::t('app', 'Kode'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'deskripsi' => Yii::t('app', 'Deskripsi'),
			'totalnominal' => Yii::t('app', 'Total Nominal'),
			'diterima_oleh' => Yii::t('app', 'Diterima Oleh'),
			'dibuat_oleh' => Yii::t('app', 'Dibuat Oleh'),
			'status' => Yii::t('app', 'Status'),
			'voucher_pengeluaran_id' => Yii::t('app', 'Voucher Pengeluaran'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'ganti_uangkas' => Yii::t('app', 'Ganti Uangkas'),
			'tbp_reff' => Yii::t('app', 'Tbp Reff'),
        ];
    }
	

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDibuatOleh()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'dibuat_oleh']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherPengeluaran()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTKasBons()
    {
        return $this->hasMany(TKasBon::className(), ['bkk_id' => 'bkk_id']);
    } 
}
