<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_asuransi".
 *
 * @property integer $asuransi_id
 * @property string $tanggal
 * @property string $kepada
 * @property string $lampiran
 * @property string $tanggal_muat
 * @property string $tanggal_berangkat
 * @property string $dop
 * @property string $rute
 * @property string $nama_kapal
 * @property double $rate
 * @property string $kode
 * @property double $freight
 * @property integer $by_gmpurch
 * @property integer $by_dirut
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $approve_reason
 * @property string $reject_reason
 * @property string $status_approval
 * @property double $discount
 */
class TAsuransi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_asuransi';
    }
    
    public function behaviors(){
		return [\app\components\DeltaGeneralBehavior::class];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kepada', 'lampiran', 'tanggal_muat', 'tanggal_berangkat', 'dop', 'rute', 'nama_kapal', 'freight', 'lumpsump'], 'required'],
            [['tanggal', 'tanggal_muat', 'tanggal_berangkat', 'created_at', 'updated_at'], 'safe'],
            [['kepada', 'lampiran', 'rute', 'kode', 'approve_reason', 'reject_reason'], 'string'],
            [['rate', 'freight', 'total', 'freight_kubikasi', 'jumlah', 'ppn', 'grandtotal', 'pembulatan', 'discount'], 'number'],
            [['by_gmpurch', 'by_dirut', 'created_by', 'updated_by'], 'integer'],
            [['dop', 'nama_kapal'], 'string', 'max' => 100],
            [['status_approval'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'asuransi_id' => 'Asuransi',
                'tanggal' => 'Tanggal',
                'kepada' => 'Kepada',
                'lampiran' => 'Lampiran',
                'tanggal_muat' => 'Tanggal Muat',
                'tanggal_berangkat' => 'Tanggal Berangkat',
                'dop' => 'Dop',
                'rute' => 'Rute',
                'nama_kapal' => 'Nama Kapal',
                'rate' => 'Rate',
                'kode' => 'Kode',
                'freight' => 'Freight',
                'by_gmpurch' => 'By Gmpurch',
                'by_dirut' => 'By Dirut',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'approve_reason' => 'Approve Reason',
                'reject_reason' => 'Reject Reason',
                'status_approval' => 'Status Approval',
                'total' => 'Total',
                'freight_kubikasi' => 'Freight Kubikasi',
                'jumlah' => 'Jumlah',
                'ppn' => 'PPN',
                'grandtotal' => 'Grandtotal',
                'pembulatan' => 'Pembulatan',
                'lumpsump' => 'Lumpsump',
                'discount' => 'Discount',
        ];
    }
}
