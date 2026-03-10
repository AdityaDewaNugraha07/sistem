<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_log_rubahjenis".
 *
 * @property integer $log_rubahjenis_id
 * @property string $kode
 * @property string $tanggal
 * @property string $peruntukan
 * @property integer $approver1
 * @property integer $approver2
 * @property string $datadetail
 * @property string $status_approve
 * @property string $reason_approval
 * @property string $reason_rejected
 * @property string $keterangan
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class TLogRubahjenis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $no_barcode, $kayu_id_old, $kayu_id_new;
    public $tgl_awal, $tgl_akhir, $label_no, $keyword;
    public static function tableName()
    {
        return 't_log_rubahjenis';
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
            [['kode', 'tanggal', 'peruntukan', 'approver1', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['approver1', 'approver2', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['datadetail', 'reason_approval', 'reason_rejected', 'keterangan'], 'string'],
            [['kode'], 'string', 'max' => 50],
            [['peruntukan'], 'string', 'max' => 20],
            [['status_approve'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'log_rubahjenis_id' => 'Log Rubahjenis',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'peruntukan' => 'Peruntukan',
                'approver1' => 'Approver1',
                'approver2' => 'Approver2',
                'datadetail' => 'Datadetail',
                'status_approve' => 'Status Approve',
                'reason_approval' => 'Reason Approval',
                'reason_rejected' => 'Reason Rejected',
                'keterangan' => 'Keterangan',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }
} 