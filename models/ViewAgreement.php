<?php

namespace app\models;

use Yii;

/**
* This is the model class for table "view_agreement".
* @property integer $agreement_id
* @property integer $assigned_to
* @property string $assigned_nama
* @property integer $approved_by
* @property string $approved_nama
* @property string $reff_no
* @property string $tanggal_berkas
* @property string $tanggal_approve
* @property integer $level_approve
* @property string $status
* @property boolean $active
* @property string $created_at
* @property integer $created_by
* @property string $created_nama
* @property string $updated_at
* @property integer $updated_by
* @property string $updated_nama
* @property string $keterangan
* @property integer $atasnama
* @property string $pemohon_nama
* @property string $tglberkas
*/
class ViewAgreement extends yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const STATUS_NOT_CONFIRMATED = 'Not Confirmed';
	const STATUS_APPROVED = 'APPROVED';
	const STATUS_REJECTED = 'REJECTED';
    public $reject_reason, $tgl_awal, $tgl_akhir;
    public static function tableName()
    {
        return 'view_agreement';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return[
                [['agreement_id','assigned_to','approved_by','level_approve','created_by','updated_by','atasnama'],'integer'],
                [['tanggal_berkas','tanggal_approve','created_at','updated_at'],'safe'],
    [['active'],'boolean'],
    [['keterangan','tglberkas'],'string'],
    [['assigned_nama','approved_nama','created_nama','updated_nama','pemohon_nama'],'string','max'=>100],
    [['reff_no','status'],'string','max'=>50],
    ];
    }

    /**
         *@inheritdoc
         */
    public function attributeLabels()
    {
        return[
                'agreement_id'=>'Agreement ID',
                'assigned_to'=>'Assigned To',
                'assigned_nama'=>'Assigned Nama',
                'approved_by'=>'Approved By',
                'approved_nama'=>'Approved Nama',
                'reff_no'=>'Reff No',
                'tanggal_berkas'=>'Tanggal Berkas',
                'tanggal_approve'=>'Tanggal Approve',
                'level_approve'=>'Level Approve',
                'status'=>'Status',
                'active'=>'Active',
                'created_at'=>'Created At',
                'created_by'=>'Created By',
                'created_nama'=>'Created Nama',
                'updated_at'=>'Updated At',
                'updated_by'=>'Updated By',
                'updated_nama'=>'Updated Nama',
                'keterangan'=>'Keterangan',
                'atasnama'=>'Atasnama',
                'pemohon_nama'=>'Pemohon Nama',
                'tglberkas'=>'Tglberkas',
                ];
    }

    public function getStatus(){
		if($this->status == self::STATUS_APPROVED){
			return '<span class="label label-success label-sm">'.self::STATUS_APPROVED.'</span>';
		}else if($this->status == self::STATUS_REJECTED){
			return '<span class="label label-danger label-sm">'.self::STATUS_REJECTED.'</span>';
		}else if($this->status == self::STATUS_NOT_CONFIRMATED){
			return '<span class="label label-default label-sm">'.self::STATUS_NOT_CONFIRMATED.'</span>';
		}
	}
    
}
