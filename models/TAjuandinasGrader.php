<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_ajuandinas_grader".
 *
 * @property integer $ajuandinas_grader_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $graderlog_id
 * @property string $grader_norek
 * @property string $grader_bank
 * @property string $status
 * @property string $keterangan
 * @property double $saldo_sebelumnya
 * @property double $wilayah_dinas_plafon
 * @property double $total_ajuan
 * @property integer $dkg_id
 * @property integer $wilayah_dinas_id
 * @property integer $voucher_pengeluaran_id
 * @property integer $kanit_grader
 * @property integer $approved_by
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property string $tanggal_dibutuhkan
 *
 * @property MGraderlog $graderlog
 * @property MPegawai $kanitGrader
 * @property MPegawai $approvedBy
 * @property MWilayahDinas $wilayahDinas
 * @property TCancelTransaksi $cancelTransaksi
 * @property TDkg $dkg
 * @property TVoucherPengeluaran $voucherPengeluaran
 */ 
class TAjuandinasGrader extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $graderlog_nm,$wilayah_dinas_nama,$reff_no,$nominal_in,$nominal_out;
    public static function tableName()
    {
        return 't_ajuandinas_grader';
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
            [['kode', 'tanggal', 'graderlog_id', 'grader_norek', 'grader_bank', 'dkg_id', 'wilayah_dinas_id', 'kanit_grader', 'approved_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at', 'tanggal_dibutuhkan', 'total_ajuan'], 'safe'],
            [['graderlog_id', 'dkg_id', 'wilayah_dinas_id', 'voucher_pengeluaran_id', 'kanit_grader', 'approved_by', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['keterangan'], 'string'],
            [['saldo_sebelumnya', 'wilayah_dinas_plafon'], 'number'],
            [['kode', 'grader_norek', 'grader_bank', 'status'], 'string', 'max' => 50],
            [['graderlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGraderlog::className(), 'targetAttribute' => ['graderlog_id' => 'graderlog_id']],
            [['kanit_grader'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['kanit_grader' => 'pegawai_id']],
            [['approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approved_by' => 'pegawai_id']],
            [['wilayah_dinas_id'], 'exist', 'skipOnError' => true, 'targetClass' => MWilayahDinas::className(), 'targetAttribute' => ['wilayah_dinas_id' => 'wilayah_dinas_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['dkg_id'], 'exist', 'skipOnError' => true, 'targetClass' => TDkg::className(), 'targetAttribute' => ['dkg_id' => 'dkg_id']],
            [['voucher_pengeluaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => TVoucherPengeluaran::className(), 'targetAttribute' => ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'ajuandinas_grader_id' => Yii::t('app', 'Ajuandinas Grader'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'graderlog_id' => Yii::t('app', 'Graderlog'),
                'grader_norek' => Yii::t('app', 'Grader Norek'),
                'grader_bank' => Yii::t('app', 'Grader Bank'),
                'status' => Yii::t('app', 'Status'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'saldo_sebelumnya' => Yii::t('app', 'Saldo Sebelumnya'),
                'wilayah_dinas_plafon' => Yii::t('app', 'Wilayah Dinas Plafon'),
                'total_ajuan' => Yii::t('app', 'Total Ajuan'),
                'dkg_id' => Yii::t('app', 'Dkg'),
                'wilayah_dinas_id' => Yii::t('app', 'Wilayah Dinas'),
                'voucher_pengeluaran_id' => Yii::t('app', 'Voucher Pengeluaran'),
                'kanit_grader' => Yii::t('app', 'Kadep Grader'),
                'approved_by' => Yii::t('app', 'Approved By'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
				'tanggal_dibutuhkan' => Yii::t('app', 'Tanggal Dibutuhkan'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGraderlog()
    {
        return $this->hasOne(MGraderlog::className(), ['graderlog_id' => 'graderlog_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKanitGrader()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'kanit_grader']);
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
    public function getWilayahDinas()
    {
        return $this->hasOne(MWilayahDinas::className(), ['wilayah_dinas_id' => 'wilayah_dinas_id']);
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
    public function getDkg()
    {
        return $this->hasOne(TDkg::className(), ['dkg_id' => 'dkg_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherPengeluaran()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    }
}
