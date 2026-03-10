<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_ajuan_dl_realisasi".
 *
 * @property integer $ajuan_dl_realisasi_id
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
 * @property integer $diperiksa
 * @property integer $disiapkan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property double $selisih_realisasi_plafon
 * @property integer $nama_ajuan
 *
 * @property MDepartement $departement
 * @property MPegawai $perintahDl
 * @property MPegawai $disetujui0
 * @property MPegawai $diperiksa0
 * @property MPegawai $disiapkan0
 * @property TAjuanDl $ajuanDl
 * @property MPegawai $nama_ajuan
 * @property TAjuanDlRealisasiDetail[] $tAjuanDlRealisasiDetails
 */
class TAjuanDlRealisasi extends DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_ajuan_dl_realisasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ajuan_dl_id', 'departement_id', 'perintah_dl', 'disetujui', 'diperiksa', 'disiapkan', 'created_by', 'updated_by', 'nama_ajuan'], 'integer'],
            [['kode', 'jabatan', 'created_at', 'created_by', 'updated_at', 'updated_by', 'nama_ajuan'], 'required'],
            [['tujuan', 'keterangan'], 'string'],
            [['tgl_dl_mulai', 'jam_dl_mulai', 'tgl_dl_selesai', 'jam_dl_selesai', 'created_at', 'updated_at'], 'safe'],
            [['selisih_realisasi_plafon'], 'number'],
            [['kode', 'nama'], 'string', 'max' => 50],
            [['jabatan'], 'string', 'max' => 150],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
            [['perintah_dl'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['perintah_dl' => 'pegawai_id']],
            [['disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disetujui' => 'pegawai_id']],
            [['diperiksa'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['diperiksa' => 'pegawai_id']],
            [['disiapkan'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disiapkan' => 'pegawai_id']],
            [['ajuan_dl_id'], 'exist', 'skipOnError' => true, 'targetClass' => TAjuanDl::className(), 'targetAttribute' => ['ajuan_dl_id' => 'ajuan_dl_id']],
            [['nama_ajuan'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['nama_ajuan' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ajuan_dl_realisasi_id' => 'Ajuan Dl Realisasi ID',
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
            'diperiksa' => 'Diperiksa',
            'disiapkan' => 'Disiapkan',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'selisih_realisasi_plafon' => 'Selisih Realisasi Plafon',
            'nama_ajuan' => 'Nama Ajuan',
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
    public function getNamaajuan0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'nama_ajuan']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAjuanDl()
    {
        return $this->hasOne(TAjuanDl::className(), ['ajuan_dl_id' => 'ajuan_dl_id']);
    }
    public function getTAjuanDlRealisasiDetails()
    {
        return $this->hasMany(TAjuanDlRealisasiDetail::className(), ['ajuan_dl_realisasi_id' => 'ajuan_dl_realisasi_id']);
    }
}
