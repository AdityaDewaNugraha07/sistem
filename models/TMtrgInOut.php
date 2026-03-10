<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "t_mtrg_in_out".
 *
 * @property integer $mtrg_in_out_id
 * @property string $tanggal_kupas
 * @property string $tanggal_produksi
 * @property string $kode
 * @property string $shift
 * @property string $status_in_out
 * @property string $kategori_proses
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
 * @property string $jenis_kayu
 *
 * @property MPegawai $disiapkan0
 * @property MPegawai $diperiksa0
 * @property MPegawai $disetujui0
 * @property MPegawai $diketahui0
 * @property TMtrgInOutDetail[] $tMtrgInOutDetails
 */
class TMtrgInOut extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_mtrg_in_out';
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
            [['tanggal_kupas', 'tanggal_produksi', 'kode', 'shift', 'status_in_out', 'kategori_proses', 'status_approval', 'created_at', 'created_by', 'updated_at', 'updated_by', 'jenis_kayu'], 'required'],
            [['tanggal_kupas', 'tanggal_produksi', 'created_at', 'updated_at'], 'safe'],
            [['disiapkan', 'diperiksa', 'disetujui', 'diketahui', 'created_by', 'updated_by'], 'integer'],
            [['reason_approval', 'jenis_kayu'], 'string'],
            [['kode', 'status_in_out'], 'string', 'max' => 20],
            [['shift'], 'string', 'max' => 10],
            [['kategori_proses', 'status_approval'], 'string', 'max' => 30],
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
                'mtrg_in_out_id' => Yii::t('app', 'Mtrg In Out'),
                'tanggal_kupas' => Yii::t('app', 'Tanggal Kupas'),
                'tanggal_produksi' => Yii::t('app', 'Tanggal Produksi'),
                'kode' => Yii::t('app', 'Kode'),
                'shift' => Yii::t('app', 'Shift'),
                'status_in_out' => Yii::t('app', 'Status In Out'),
                'kategori_proses' => Yii::t('app', 'Kategori Proses'),
                'status_approval' => Yii::t('app', 'Status Approval'),
                'disiapkan' => Yii::t('app', 'Disiapkan'),
                'diperiksa' => Yii::t('app', 'Diperiksa Karu'),
                'disetujui' => Yii::t('app', 'Disetujui Kanit'),
                'diketahui' => Yii::t('app', 'Diketahui Kashift'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'reason_approval' => Yii::t('app', 'Reason Approve'),
                'jenis_kayu' => Yii::t('app', 'Jenis Kayu'),
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

    /**
     * @return ActiveQuery
     */
    public function getTMtrgInOutDetails()
    {
        return $this->hasMany(TMtrgInOutDetail::className(), ['mtrg_in_out_id' => 'mtrg_in_out_id']);
    }
}
