<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pmr".
 *
 * @property integer $pmr_id
 * @property string $kode
 * @property string $tanggal
 * @property string $jenis_log
 * @property string $tujuan
 * @property string $tanggal_dibutuhkan_awal
 * @property string $tanggal_dibutuhkan_akhir
 * @property integer $dibuat_oleh
 * @property integer $approver_1
 * @property integer $approver_2
 * @property integer $approver_3
 * @property string $status
 * @property string $keterangan
 * @property integer $pengajuan_pembelianlog_id
 * @property integer $posengon_rencana_id
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $approver_4
 * @property integer $approver_5
 *
 * @property MPegawai $dibuatOleh
 * @property MPegawai $approver1
 * @property MPegawai $approver2
 * @property MPegawai $approver3
 * @property MPegawai $approver4
 * @property MPegawai $approver5
 * @property TPmrDetail[] $tPmrDetails
 */
class TPmr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $total_m3;
    public static function tableName()
    {
        return 't_pmr';
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
            [['kode', 'tanggal', 'jenis_log', 'tujuan', 'tanggal_dibutuhkan_awal','tanggal_dibutuhkan_akhir', 'dibuat_oleh', 'approver_1', 'approver_2', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'tanggal_dibutuhkan_awal','tanggal_dibutuhkan_akhir', 'created_at', 'updated_at'], 'safe'],
            [['dibuat_oleh', 'approver_1', 'approver_2', 'approver_3', 'approver_4', 'approver_5', 'pengajuan_pembelianlog_id', 'posengon_rencana_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['keterangan'], 'string'],
            [['kode', 'jenis_log', 'tujuan', 'status'], 'string', 'max' => 25],
            [['dibuat_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['dibuat_oleh' => 'pegawai_id']],
            [['approver_1'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approver_1' => 'pegawai_id']],
            [['approver_2'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approver_2' => 'pegawai_id']],
            [['approver_3'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approver_3' => 'pegawai_id']],
            [['approver_4'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approver_4' => 'pegawai_id']],
            [['approver_5'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approver_5' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pmr_id' => 'Pmr',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'jenis_log' => 'Jenis Log',
                'tujuan' => 'Peruntukan',
                'tanggal_dibutuhkan_awal' => 'Tanggal Dibutuhkan Awal',
                'tanggal_dibutuhkan_akhir' => 'Tanggal Dibutuhkan Akhir',
                'dibuat_oleh' => 'Dibuat Oleh',
                'approver_1' => 'Approver 1',
                'approver_2' => 'Approver 2',
                'approver_3' => 'Approver 3',
                'approver_4' => 'Approver 4',
                'approver_5' => 'Approver 5',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
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
    public function getApprover1()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approver_1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover2()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approver_2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover3()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approver_3']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover4()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approver_4']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover5()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approver_5']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTPmrDetails()
    {
        return $this->hasMany(TPmrDetail::className(), ['pmr_id' => 'pmr_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanPembelianlog()
    {
        return $this->hasMany(TPengajuanPembelianlog::className(), ['pengajuan_pembelianlog_id' => 'pengajuan_pembelianlog_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosengonRencana()
    {
        return $this->hasMany(TPosengonRencana::className(), ['posengon_rencana_id' => 'posengon_rencana_id']);
    }
}
