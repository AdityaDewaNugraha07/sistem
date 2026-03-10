<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengajuan_masterproduk".
 *
 * @property integer $pengajuan_masterproduk_id
 * @property string $kode
 * @property string $tanggal
 * @property string $keperluan
 * @property string $status_pengajuan
 * @property string $keterangan
 * @property integer $prepared_by
 * @property integer $reviewed_by
 * @property integer $approved_by
 * @property integer $cancel_transaksi_id
 * @property string $approve_reason
 * @property string $reject_reason
 * @property string $approval_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class TPengajuanMasterproduk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_pengajuan_masterproduk';
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
            [['kode', 'tanggal', 'keperluan', 'status_pengajuan', 'prepared_by', 'reviewed_by', 'approved_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['keterangan', 'approve_reason', 'reject_reason'], 'string'],
            [['prepared_by', 'reviewed_by', 'approved_by', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['kode', 'keperluan', 'status_pengajuan'], 'string', 'max' => 50],
            [['approval_status'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengajuan_masterproduk_id' => 'Pengajuan Masterproduk',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'keperluan' => 'Keperluan',
                'status_pengajuan' => 'Status Pengajuan',
                'keterangan' => 'Keterangan',
                'prepared_by' => 'Prepared By',
                'reviewed_by' => 'Reviewed By',
                'approved_by' => 'Approved By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'approve_reason' => 'Approve Reason',
                'reject_reason' => 'Reject Reason',
                'approval_status' => 'Approval Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }
    public function getPreparedby()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'prepared_by']);
    }

    public function getReviewedby()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'reviewed_by']);
    }

    public function getApprovedby()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approved_by']);
    }
} 