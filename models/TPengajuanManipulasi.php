<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "t_pengajuan_manipulasi".
 *
 * @property integer $pengajuan_manipulasi_id
 * @property string $kode
 * @property string $tipe
 * @property string $tanggal
 * @property integer $approver1
 * @property integer $approver2
 * @property string $reff_no
 * @property string $reff_no2
 * @property string $parameter1
 * @property string $parameter2
 * @property string $datadetail1
 * @property string $datadetail2
 * @property string $reason
 * @property string $priority
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property integer $approver3
 * @property integer $approver4
 * @property integer $approver5
 * @property string $reason_approval
 */
class TPengajuanManipulasi extends ActiveRecord
{
    public $approver1_display,$approver2_display,$departement_id,$cust_nm,$approver3_display,$approver4_display,$nopol_lama,$nopol_baru,$alamat_bongkar_lama,$alamat_bongkar_baru,$supir_old,$supir_new;
    public static function tableName()
    {
        return 't_pengajuan_manipulasi';
    }
    
    public function behaviors(){
		return [DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'tipe', 'tanggal', 'approver1', 'reff_no', 'reason', 'priority', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['approver1', 'approver2', 'approver3', 'approver4', 'approver5', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['parameter1', 'parameter2', 'datadetail1', 'datadetail2', 'reason', 'reason_approval'], 'string'],
            [['kode', 'tipe', 'reff_no', 'reff_no2', 'priority', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pengajuan_manipulasi_id' => 'Pengajuan Manipulasi',
            'kode' => 'Kode',
            'tipe' => 'Tipe',
            'tanggal' => 'Tanggal',
            'approver1' => 'Approver1',
            'approver2' => 'Approver2',
            'approver3' => 'Approver3',
            'approver4' => 'Approver4',
            'approver5' => 'Approver5',
            'reff_no' => 'Reff No',
            'reff_no2' => 'Reff No2',
            'parameter1' => 'Parameter1',
            'parameter2' => 'Parameter2',
            'datadetail1' => 'Datadetail1',
            'datadetail2' => 'Datadetail2',
            'reason' => 'Reason',
            'priority' => 'Priority',
            'status' => 'Status',
            'created_at' => 'Create Time',
            'created_by' => 'Created By',
            'updated_at' => 'Last Update Time',
            'updated_by' => 'Last Updated By',
            'cancel_transaksi_id' => 'Cancel Transaksi',
            'reason_aproval' => Yii::t('app', 'Keterangan Approve')
        ];
    }
}
