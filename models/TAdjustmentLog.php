<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_adjustment_log".
 *
 * @property integer $adjustment_log_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $pengajuan_pembelianlog_id
 * @property string $reff_no_loglist
 * @property string $reff_no_spk
 * @property string $uraian
 * @property double $jml_batang_loglist
 * @property double $jml_m3_loglist
 * @property double $jml_batang_terima
 * @property double $jml_m3_terima
 * @property string $status_approval
 * @property string $approve_reason
 * @property string $reject_reason
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TCancelTransaksi $cancelTransaksi
 */
class TAdjustmentLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_adjustment_log';
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
            [['kode', 'tanggal', 'pengajuan_pembelianlog_id', 'reff_no_loglist', 'reff_no_spk', 'jml_batang_loglist', 'jml_m3_loglist', 'jml_batang_terima', 'jml_m3_terima'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['pengajuan_pembelianlog_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['uraian', 'approve_reason', 'reject_reason'], 'string'],
            [['jml_batang_loglist', 'jml_m3_loglist', 'jml_batang_terima', 'jml_m3_terima'], 'number'],
            [['kode', 'reff_no_loglist', 'reff_no_spk', 'status_approval'], 'string', 'max' => 50],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'adjustment_log_id' => 'Adjustment Log',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'pengajuan_pembelianlog_id' => 'Pengajuan Pembelianlog',
                'reff_no_loglist' => 'Reff No Loglist',
                'reff_no_spk' => 'Reff No Spk',
                'uraian' => 'Uraian',
                'jml_batang_loglist' => 'Jml Pcs Loglist',
                'jml_m3_loglist' => 'Jml M3 Loglist',
                'jml_batang_terima' => 'Jml Pcs Terima',
                'jml_m3_terima' => 'Jml M3 Terima',
                'status_approval' => 'Status Approval',
                'approve_reason' => 'Approve Reason',
                'reject_reason' => 'Reject Reason',
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
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }
}
