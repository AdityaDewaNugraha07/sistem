<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_cancel_transaksi".
 *
 * @property integer $cancel_transaksi_id
 * @property integer $cancel_by
 * @property string $cancel_at
 * @property string $cancel_reason
 * @property string $reff_no
 * @property integer $approved_by
 * @property string $approved_at
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $reff_detail_id
 * @property integer $bhp_id
 * @property double $cancel_jml
 *
 * @property HBonsementara[] $hBonsementaras
 * @property TAdjustmentstock[] $tAdjustmentstocks
 * @property TBpbDetail[] $tBpbDetails
 * @property MPegawai $cancelBy
 * @property MPegawai $approvedBy
 * @property TDpBhp[] $tDpBhps
 * @property TKasBesar[] $tKasBesars
 * @property TKasBesarSetor[] $tKasBesarSetors
 * @property TKasBon[] $tKasBons
 * @property TKasKecil[] $tKasKecils
 * @property TMutasiGudanglogistik[] $tMutasiGudanglogistiks
 * @property TPengeluaranKaskecil[] $tPengeluaranKaskecils
 * @property TSpl[] $tSpls
 * @property TSpo[] $tSpos
 * @property TSpp[] $tSpps
 * @property TTerimaBhp[] $tTerimaBhps
 * @property TUangtunai[] $tUangtunais
 * @property TVoucherPengeluaran[] $tVoucherPengeluarans
 */ 
class TCancelTransaksi extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	const STATUS_ABORTED = 'ABORTED';
    public static function tableName()
    {
        return 't_cancel_transaksi';
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
            [['cancel_by', 'cancel_at', 'cancel_reason', 'reff_no', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['cancel_by', 'approved_by', 'created_by', 'updated_by', 'reff_detail_id', 'bhp_id'], 'integer'],
            [['cancel_at', 'approved_at', 'created_at', 'updated_at'], 'safe'],
            [['cancel_reason'], 'string'],
            [['cancel_jml'], 'number'],
            [['reff_no', 'status'], 'string', 'max' => 50],
            [['cancel_by'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['cancel_by' => 'pegawai_id']],
            [['approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approved_by' => 'pegawai_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'cancel_by' => Yii::t('app', 'Cancel By'),
			'cancel_at' => Yii::t('app', 'Cancel At'),
			'cancel_reason' => Yii::t('app', 'Cancel Reason'),
			'reff_no' => Yii::t('app', 'Reff No'),
			'approved_by' => Yii::t('app', 'Approved By'),
			'approved_at' => Yii::t('app', 'Approved At'),
			'status' => Yii::t('app', 'Status'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'reff_detail_id' => Yii::t('app', 'Reff Detail'),
			'bhp_id' => Yii::t('app', 'Bhp'),
			'cancel_jml' => Yii::t('app', 'Cancel Jml'),
        ];
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getHBonsementaras()
    {
        return $this->hasMany(HBonsementara::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTAdjustmentstocks()
    {
        return $this->hasMany(TAdjustmentstock::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTBpbDetails()
    {
        return $this->hasMany(TBpbDetail::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelBy()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'cancel_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approved_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTDpBhps()
    {
        return $this->hasMany(TDpBhp::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTKasBesars()
    {
        return $this->hasMany(TKasBesar::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTKasBesarSetors()
    {
        return $this->hasMany(TKasBesarSetor::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTKasBons()
    {
        return $this->hasMany(TKasBon::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTKasKecils()
    {
        return $this->hasMany(TKasKecil::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTMutasiGudanglogistiks()
    {
        return $this->hasMany(TMutasiGudanglogistik::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTPengeluaranKaskecils()
    {
        return $this->hasMany(TPengeluaranKaskecil::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpls()
    {
        return $this->hasMany(TSpl::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpos()
    {
        return $this->hasMany(TSpo::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpps()
    {
        return $this->hasMany(TSpp::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaBhps()
    {
        return $this->hasMany(TTerimaBhp::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTUangtunais()
    {
        return $this->hasMany(TUangtunai::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTVoucherPengeluarans()
    {
        return $this->hasMany(TVoucherPengeluaran::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    } 
}
