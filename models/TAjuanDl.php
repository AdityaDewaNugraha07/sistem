<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_ajuan_dl".
 *
 * @property integer $ajuan_dl_id
 * @property string $kode
 * @property string $nama
 * @property string $jabatan
 * @property integer $departement_id
 * @property integer $perintah_dl
 * @property string $tujuan
 * @property string $tgl_dl_mulai
 * @property string $jam_dl_mulai
 * @property string $tgl_dl_selesai
 * @property string $jam_dl_selesai
 * @property string $keterangan
 * @property integer $disetujui
 * @property integer $diketahui
 * @property integer $diperiksa
 * @property integer $disiapkan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property boolean $hapus
 * @property string $hapus_at
 * @property integer $hapus_by
 * @property string $wilayah_dl
 * @property integer $status_dl
 * @property integer $disetujui2
 * @property integer $nama_id
 *
 * @property MDepartement $departement
 * @property MPegawai $perintahDl
 * @property MPegawai $disetujui0
 * @property MPegawai $disetujui20
 * @property MPegawai $diketahui0
 * @property MPegawai $diperiksa0
 * @property MPegawai $disiapkan0
 * @property MPegawai $nama0
 * @property TAjuanDlDetail[] $tAjuanDlDetails
 * @property TAjuanDlRealisasi[] $tAjuanDlRealisasis
 */
class TAjuanDl extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_ajuan_dl';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'jabatan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['departement_id', 'perintah_dl', 'disetujui', 'diketahui', 'diperiksa', 'disiapkan', 'created_by', 'updated_by', 'hapus_by', 'status_dl', 'disetujui2', 'nama_id'], 'integer'],
            [['tujuan', 'keterangan', 'wilayah_dl'], 'string'],
            [['tgl_dl_mulai', 'jam_dl_mulai', 'tgl_dl_selesai', 'jam_dl_selesai', 'created_at', 'updated_at', 'hapus_at'], 'safe'],
            [['hapus'], 'boolean'],
            [['kode', 'nama'], 'string', 'max' => 50],
            [['jabatan'], 'string', 'max' => 150],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
            [['perintah_dl'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['perintah_dl' => 'pegawai_id']],
            [['disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disetujui' => 'pegawai_id']],
            [['disetujui2'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disetujui2' => 'pegawai_id']],
            [['diketahui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['diketahui' => 'pegawai_id']],
            [['diperiksa'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['diperiksa' => 'pegawai_id']],
            [['disiapkan'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disiapkan' => 'pegawai_id']],
            [['nama_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['nama_id' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ajuan_dl_id' => 'Ajuan Dl ID',
            'kode' => 'Kode',
            'nama' => 'Nama',
            'jabatan' => 'Jabatan',
            'departement_id' => 'Departement ID',
            'perintah_dl' => 'Perintah Dl',
            'tujuan' => 'Tujuan',
            'tgl_dl_mulai' => 'Tgl Dl Mulai',
            'jam_dl_mulai' => 'Jam Dl Mulai',
            'tgl_dl_selesai' => 'Tgl Dl Selesai',
            'jam_dl_selesai' => 'Jam Dl Selesai',
            'keterangan' => 'Keterangan',
            'disetujui' => 'Disetujui',
            'diketahui' => 'Diketahui',
            'diperiksa' => 'Diperiksa',
            'disiapkan' => 'Disiapkan',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'hapus' => 'Hapus',
            'hapus_at' => 'Hapus At',
            'hapus_by' => 'Hapus By',
            'wilayah_dl' => 'Wilayah Dl',
            'status_dl' => 'Status Dl',
            'disetujui2' => 'Disetujui2',
            'nama_id' => 'Nama ID',
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
    public function getPerintahDl()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'perintah_dl']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisetujui0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disetujui']);
    }
    public function getDisetujui20()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disetujui2']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiketahui0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'diketahui']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiperiksa0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'diperiksa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisiapkan0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disiapkan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNama0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'nama_id']);
    }
    public function getTAjuanDlDetails()
    {
        return $this->hasMany(TAjuanDlDetail::className(), ['ajuan_dl_id' => 'ajuan_dl_id']);
    }
    public function getTAjuanDlRealisasis()
    {
        return $this->hasMany(TAjuanDlRealisasi::className(), ['ajuan_dl_id' => 'ajuan_dl_id']);
    }
}
