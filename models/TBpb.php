<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_bpb".
 *
 * @property integer $bpb_id
 * @property integer $spb_id
 * @property integer $departement_id
 * @property string $bpb_kode
 * @property string $bpb_nomor
 * @property string $bpb_tgl_keluar
 * @property string $bpb_tgl_diterima
 * @property integer $bpb_dikeluarkan
 * @property integer $bpb_diterima
 * @property string $bpb_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MDepartement $departement
 * @property MPegawai $bpbDikeluarkan
 * @property MPegawai $bpbDiterima
 * @property TSpb $spb
 */
class TBpb extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_bpb';
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
            [['spb_id', 'departement_id', 'bpb_kode', 'bpb_tgl_keluar', 'bpb_dikeluarkan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['spb_id', 'departement_id', 'bpb_dikeluarkan', 'bpb_diterima', 'created_by', 'updated_by'], 'integer'],
            [['bpb_tgl_keluar', 'bpb_tgl_diterima', 'created_at', 'updated_at'], 'safe'],
            [['bpb_kode', 'bpb_nomor'], 'string', 'max' => 50],
            [['bpb_status'], 'string', 'max' => 30],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
            [['bpb_dikeluarkan'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['bpb_dikeluarkan' => 'pegawai_id']],
            [['bpb_diterima'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['bpb_diterima' => 'pegawai_id']],
            [['spb_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpb::className(), 'targetAttribute' => ['spb_id' => 'spb_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'bpb_id' => Yii::t('app', 'SPB'),
                'spb_id' => Yii::t('app', 'SPB'),
                'departement_id' => Yii::t('app', 'Departement'),
                'bpb_kode' => Yii::t('app', 'Kode BPB'),
                'bpb_nomor' => Yii::t('app', 'No. BPB'),
                'bpb_tgl_keluar' => Yii::t('app', 'Tanggal Keluar'),
                'bpb_tgl_diterima' => Yii::t('app', 'Tanggal Diterima'),
                'bpb_dikeluarkan' => Yii::t('app', 'Dikeluarkan Oleh'),
                'bpb_diterima' => Yii::t('app', 'Diterima Oleh'),
                'bpb_status' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartement()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'departement_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBpbDikeluarkan()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'bpb_dikeluarkan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBpbDiterima()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'bpb_diterima']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpb()
    {
        return $this->hasOne(TSpb::className(), ['spb_id' => 'spb_id']);
    }

    public function getTBpbDetails()
    {
        return $this->hasMany(TBpbDetail::class, ['bpb_id' => 'bpb_id']);
    }
}
