<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_approval".
 *
 * @property integer $approval_id
 * @property integer $assigned_to
 * @property integer $approved_by
 * @property string $reff_no
 * @property string $tanggal_berkas
 * @property string $tanggal_approve
 * @property integer $level
 * @property string $status
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MPegawai $assignedTo
 * @property MPegawai $approvedBy
 * @property mixed|string|null $parameter1
 */
class TApproval extends DeltaBaseActiveRecord
{
    const STATUS_NOT_CONFIRMATED = 'Not Confirmed';
	const STATUS_APPROVED = 'APPROVED';
	const STATUS_REJECTED = 'REJECTED';
    public $reject_reason, $tgl_awal, $tgl_akhir;
    public static function tableName()
    {
        return 't_approval';
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
            [['assigned_to', 'reff_no', 'tanggal_berkas', 'level', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['assigned_to', 'approved_by', 'level', 'created_by', 'updated_by'], 'integer'],
            [['tanggal_berkas', 'tanggal_approve', 'created_at', 'updated_at'], 'safe'],
            [['active'], 'boolean'],
            [['reff_no', 'status'], 'string', 'max' => 50],
            [['assigned_to'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['assigned_to' => 'pegawai_id']],
            [['approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approved_by' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'approval_id' => Yii::t('app', 'Approval'),
                'assigned_to' => Yii::t('app', 'Assigned To'),
                'approved_by' => Yii::t('app', 'Confirm By'),
                'reff_no' => Yii::t('app', 'Reff No'),
                'tanggal_berkas' => Yii::t('app', 'Tanggal Berkas'),
                'tanggal_approve' => Yii::t('app', 'Confirm At'),
                'level' => Yii::t('app', 'Level'),
                'status' => Yii::t('app', 'Status'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedTo()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'assigned_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approved_by']);
    }
	
	public function createApproval(){
		if($this->validate()){
			if($this->save()){
				return true;
			} else {
				return false;
			}
		}else{
			return false;
		}
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
	public function getStatusLite(){
		if($this->status == self::STATUS_APPROVED){
			return '<span class="label label-success label-sm" style="font-size: 9px;">'.self::STATUS_APPROVED.'</span>';
		}else if($this->status == self::STATUS_REJECTED){
			return '<span class="label label-danger label-sm" style="font-size: 9px;">'.self::STATUS_REJECTED.'</span>';
		}else if($this->status == self::STATUS_NOT_CONFIRMATED){
			return '<span class="label label-default label-sm" style="font-size: 9px;">'.self::STATUS_NOT_CONFIRMATED.'</span>';
		}
	}
	
}
