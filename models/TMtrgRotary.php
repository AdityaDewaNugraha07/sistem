<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "t_mtrg_rotary".
 *
 * @property integer $mtrg_rotary_id
 * @property string $tanggal
 * @property string $kode
 * @property string $shift
 * @property string $status_approval
 * @property integer $disiapkan
 * @property integer $diperiksa
 * @property integer $disetujui
 * @property integer $diketahui
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $reason_approval
 * @property string $reason_reject
 * @property integer $mtrg_setup_id
 * @property string $jenis_kayu
 * @property integer $jam_jalan
 *
 * @property MPegawai $disiapkan0
 * @property MPegawai $diperiksa0
 * @property MPegawai $disetujui0
 * @property MPegawai $diketahui0
 */
class TMtrgRotary extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_mtrg_rotary';
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
            [['tanggal', 'kode', 'shift', 'status_approval', 'created_at', 'created_by', 'updated_at', 'updated_by', 'mtrg_setup_id', 'jenis_kayu', 'jam_jalan'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['disiapkan', 'diperiksa', 'disetujui', 'diketahui', 'created_by', 'updated_by', 'mtrg_setup_id'], 'integer'],
            [['reason_approval', 'reason_reject', 'jenis_kayu'], 'string'],
            [['kode'], 'string', 'max' => 20],
            [['shift'], 'string', 'max' => 10],
            [['status_approval'], 'string', 'max' => 30],
            [['disiapkan'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disiapkan' => 'pegawai_id']],
            [['diperiksa'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['diperiksa' => 'pegawai_id']],
            [['disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disetujui' => 'pegawai_id']],
            [['diketahui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['diketahui' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'mtrg_rotary_id' => Yii::t('app', 'Mtrg Rotary'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'kode' => Yii::t('app', 'Kode'),
                'shift' => Yii::t('app', 'Shift'),
                'status_approval' => Yii::t('app', 'Status Approval'),
                'disiapkan' => Yii::t('app', 'Disiapkan'),
                'diperiksa' => Yii::t('app', 'Diperiksa Karu'),
                'disetujui' => Yii::t('app', 'Disetujui Kanit'),
                'diketahui' => Yii::t('app', 'Diketahui Kashift'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'reason_approval' => Yii::t('app', 'Reason Approval'),
                'reason_reject' => Yii::t('app', 'Reason Reject'),
                'jam_jalan' => Yii::t('app', 'Jam Jalan'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getDisiapkan0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disiapkan']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDiperiksa0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'diperiksa']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDisetujui0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disetujui']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDiketahui0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'diketahui']);
    }
}
