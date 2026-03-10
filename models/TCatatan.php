<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_catatan".
 *
 * @property integer $catatan_id
 * @property integer $user_id 
 * @property string $tanggal
 * @property string $jam 
 * @property string $keterangan
 * @property string $catatan_gambar
 * @property string $judul
 */
class TCatatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    // tambahan variable untuk upload file
    public $file;

    public static function tableName()
    {
        return 't_catatan';
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
            [['tanggal'], 'safe'],
            [['keterangan', 'jam'], 'string'],
            [['user_id'], 'integer'],
            [['catatan_gambar', 'judul'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'catatan_id' => 'Catatan ID',
                'tanggal' => 'Tanggal',
                'jam' => 'Jam',
                'keterangan' => 'Keterangan',
                'catatan_gambar' => 'File',
                'judul' => 'Judul',
                'user_id' => 'User',
        ];
    }

    // tambahkan data relasi yang berhubungan dengan tabel utama di halaman ini (TCatatan)
    // TCatatan.user_id -> TUser.user_id -> TUser.pegawai_id -> TPegawai.pegawai_id
    // semua relasi seharusnya dituliskan di file models
    // untuk pemanggilan di controller tinggal ditulis misalnya : $pegawai_nama = $model->m_user->m_pegawai->pegawai_nama;
    /**
     * @return \yii\db\ActiveQuery
     */
    // relasikan TCatatan dengan TUser terlebih dulu
    public function getUser()
    {
        return $this->hasOne(MUser::className(), ['user_id' => 'user_id']);
    }

    // relasikan lagi TUser dengan TPegawai
    public function getPegawai()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }

}