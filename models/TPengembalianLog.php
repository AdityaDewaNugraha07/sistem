<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengembalian_log".
 *
 * @property integer $pengembalian_log_id
 * @property string $kode
 * @property string $tanggal
 * @property string $keterangan
 * @property integer $cancel_transaksi_id
 * @property string $status_approve
 * @property string $reason_approval
 * @property string $reason_rejected
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class TPengembalianLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_pengembalian_log';
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
            [['kode', 'tanggal', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['keterangan', 'reason_approval', 'reason_rejected'], 'string'],
            [['cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['kode', 'status_approve'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengembalian_log_id' => 'Pengembalian Log',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'keterangan' => 'Keterangan',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'status_approve' => 'Status Approve',
                'reason_approval' => 'Reason Approval',
                'reason_rejected' => 'Reason Rejected',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }
} 