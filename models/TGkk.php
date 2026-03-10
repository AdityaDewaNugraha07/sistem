<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_gkk".
 *
 * @property integer $gkk_id
 * @property string $kode
 * @property string $tanggal
 * @property string $deskripsi
 * @property double $totalnominal
 * @property string $status
 * @property integer $voucher_pengeluaran_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property string $tbp_reff
 * @property string $penerima
 *
 * @property TCancelTransaksi $cancelTransaksi
 * @property TVoucherPengeluaran $voucherPengeluaran
 * @property TKasBon[] $tKasBons
 */ 
class TGkk extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $totalgkk;
    public static function tableName()
    {
        return 't_gkk';
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
            [['kode', 'tanggal', 'deskripsi', 'totalnominal', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['deskripsi', 'tbp_reff'], 'string'],
            [['totalnominal'], 'number'],
            [['voucher_pengeluaran_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['kode'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 30],
            [['penerima'], 'string', 'max' => 200],
            [['kode'], 'unique'],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['voucher_pengeluaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => TVoucherPengeluaran::className(), 'targetAttribute' => ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'gkk_id' => Yii::t('app', 'Gkk'),
			'kode' => Yii::t('app', 'Kode'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'deskripsi' => Yii::t('app', 'Deskripsi'),
			'totalnominal' => Yii::t('app', 'Totalnominal'),
			'status' => Yii::t('app', 'Status'),
			'voucher_pengeluaran_id' => Yii::t('app', 'Voucher Pengeluaran'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'tbp_reff' => Yii::t('app', 'Tbp Reff'),
			'penerima' => Yii::t('app', 'Penerima'),
        ];
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
    public function getVoucherPengeluaran()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getTKasBons()
    {
        return $this->hasMany(TKasBon::className(), ['gkk_id' => 'gkk_id']);
    } 
}
