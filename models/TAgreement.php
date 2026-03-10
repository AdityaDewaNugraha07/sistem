<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_agreement".
 *
 * @property integer $agreement_id
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
 * @property string $keterangan
 * @property integer $atasnama
 *
 * @property MPegawai $assignedTo
 * @property MPegawai $approvedBy
 * @property MPegawai $atasnama0
 */
class TAgreement extends DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    const STATUS_NOT_CONFIRMATED = 'Not Confirmed';
	const STATUS_APPROVED = 'APPROVED';
	const STATUS_REJECTED = 'REJECTED';
    public static function tableName()
    {
        return 't_agreement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assigned_to', 'reff_no', 'tanggal_berkas', 'level', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['assigned_to', 'approved_by', 'level', 'created_by', 'updated_by', 'atasnama'], 'integer'],
            [['tanggal_berkas', 'tanggal_approve', 'created_at', 'updated_at'], 'safe'],
            [['active'], 'boolean'],
            [['keterangan'], 'string'],
            [['reff_no', 'status'], 'string', 'max' => 50],
            [['assigned_to'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['assigned_to' => 'pegawai_id']],
            [['approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approved_by' => 'pegawai_id']],
            [['atasnama'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['atasnama' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'agreement_id' => 'Agreement ID',
            'assigned_to' => 'Assigned To',
            'approved_by' => 'Approved By',
            'reff_no' => 'Reff No',
            'tanggal_berkas' => 'Tanggal Berkas',
            'tanggal_approve' => 'Tanggal Approve',
            'level' => 'Level',
            'status' => 'Status',
            'active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'keterangan' => 'Keterangan',
            'atasnama' => 'Atasnama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedTo()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'assigned_to']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approved_by']);
    }
    public function getAtasnama0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'atasnama']);
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
