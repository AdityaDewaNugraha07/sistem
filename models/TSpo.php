<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spo".
 *
 * @property integer $spo_id
 * @property integer $suplier_id
 * @property string $spo_kode
 * @property string $spo_tanggal
 * @property integer $spo_disetujui
 * @property string $approve_date
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property boolean $spo_is_pkp
 * @property boolean $spo_is_ppn
 * @property double $spo_ppn_nominal
 * @property double $spo_pph_nominal
 * @property double $spo_total
 * @property string $spo_status_bayar
 * @property string $approve_status
 * @property integer $voucher_pengeluaran_id
 * @property integer $terima_bhp_id
 * @property integer $cancel_transaksi_id
 * @property string $mata_uang
 * @property string $tanggal_kirim
 * @property string $reason_approval
 *
 * @property MPegawai $spoDisetujui
 * @property MSuplier $suplier
 * @property TCancelTransaksi $cancelTransaksi
 * @property TSpoDetail[] $tSpoDetails
 * @property TTerimaBhp[] $tTerimaBhps
 */ 
class TSpo extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	const SCENARIO_SPO_BARU = 'scenarioSpoBaru';
	public $bhp_id,$penawaran,$berkas;
    public static function tableName()
    {
        return 't_spo';
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
            [['suplier_id', 'spo_kode', 'spo_tanggal', 'spo_disetujui', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['suplier_id', 'spo_disetujui', 'created_by', 'updated_by', 'voucher_pengeluaran_id', 'terima_bhp_id', 'cancel_transaksi_id'], 'integer'],
            [['spo_tanggal', 'approve_date', 'created_at', 'updated_at', 'tanggal_kirim'], 'safe'],
            [['spo_is_pkp', 'spo_is_ppn'], 'boolean'],
            [['spo_ppn_nominal', 'spo_pph_nominal', 'spo_total'], 'number'],
            [['spo_kode'], 'string', 'max' => 30],
            [['spo_status_bayar', 'mata_uang'], 'string', 'max' => 20],
            [['approve_status'], 'string', 'max' => 50],
            [['spo_disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['spo_disetujui' => 'pegawai_id']],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
			[['tanggal_kirim'], 'required', 'on' => self::SCENARIO_SPO_BARU],
        ]; 
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'spo_id' => Yii::t('app', 'Spo'),
			'suplier_id' => Yii::t('app', 'Supplier'),
			'spo_kode' => Yii::t('app', 'Kode'),
			'spo_tanggal' => Yii::t('app', 'Tanggal PO'),
			'spo_disetujui' => Yii::t('app', 'Approved By'),
			'approve_date' => Yii::t('app', 'Approve Date'),
			'spo_status' => Yii::t('app', 'Status'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'spo_is_pkp' => Yii::t('app', 'PKP'),
            'spo_is_ppn' => Yii::t('app', 'Include PPn'),
            'spo_ppn_nominal' => Yii::t('app', 'PPn'),
            'spo_pph_nominal' => Yii::t('app', 'PPh'),
			'spo_total' => Yii::t('app', 'Grand Total'),
			'spo_status_bayar' => Yii::t('app', 'Spo Status Bayar'),
			'approve_status' => Yii::t('app', 'Approve Status'),
			'voucher_pengeluaran_id' => Yii::t('app', 'Voucher Pengeluaran'),
			'terima_bhp_id' => Yii::t('app', 'Terima Bhp'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'mata_uang' => Yii::t('app', 'Mata Uang'),
			'tanggal_kirim' => Yii::t('app', 'Tanggal Kirim'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpoDisetujui()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'spo_disetujui']);
    }
    public function getSpoCreatedBy()
    {
        return $this->hasOne(MUser::className(), ['user_id' => 'created_by']);
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
    public function getTSpoDetails()
    {
        return $this->hasMany(TSpoDetail::className(), ['spo_id' => 'spo_id']);
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaBhps()
    {
        return $this->hasMany(TTerimaBhp::className(), ['spo_id' => 'spo_id']);
    } 
	
    public function getDefaultValue()
    {
        return $this->hasOne(MDefaultValue::className(), ['value' => 'mata_uang']);
    } 
	
	public static function getOptionListPenerimaan()
    {
        $res = self::find()->where("")->orderBy('spo_tanggal ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'spo_id', 'spo_kode');
    }
	
	public static function getOptionListPenerimaanAvailable()
    {
        $res = self::find()->where("terima_bhp_id IS NULL")->orderBy('spo_tanggal ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'spo_id', 'spo_kode');
    }
		}
