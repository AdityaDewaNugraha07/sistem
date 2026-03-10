<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_keluar_pelabuhan".
 *
 * @property integer $keluar_pelabuhan_id
 * @property string $kode
 * @property string $nomor_nota_angkut
 * @property string $nomor_dkb
 * @property string $nomor_surat_pengantar
 * @property string $nomor_skshhkb
 * @property string $tanggal
 * @property string $cara_keluar
 * @property string $jenis_kendaraan
 * @property string $kendaraan_nopol
 * @property string $kendaraan_supir
 * @property string $alamat_bongkar
 * @property integer $cust_id
 * @property string $masaberlaku_awal
 * @property string $masaberlaku_akhir
 * @property integer $petugas_legalkayu_id
 * @property string $noreg
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MCustomer $cust
 * @property MPetugasLegalkayu $petugasLegalkayu
 * @property TKeluarPelabuhanDetail $tKeluarPelabuhanDetail
 */
class TKeluarPelabuhan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $masaberlaku_hari;
    public static function tableName()
    {
        return 't_keluar_pelabuhan';
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
            [['kode', 'nomor_nota_angkut', 'nomor_dkb', 'tanggal', 'cara_keluar', 'jenis_kendaraan', 'kendaraan_nopol', 'alamat_bongkar', 'petugas_legalkayu_id', 'noreg', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'masaberlaku_awal', 'masaberlaku_akhir', 'created_at', 'updated_at'], 'safe'],
            [['alamat_bongkar'], 'string'],
            [['cust_id', 'petugas_legalkayu_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['kode', 'nomor_nota_angkut', 'nomor_dkb', 'nomor_surat_pengantar', 'nomor_skshhkb', 'cara_keluar', 'jenis_kendaraan', 'kendaraan_supir', 'status'], 'string', 'max' => 50],
            [['kendaraan_nopol'], 'string', 'max' => 20],
            [['noreg'], 'string', 'max' => 100],
            [['nomor_dkb'], 'unique'],
            [['nomor_nota_angkut'], 'unique'],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['petugas_legalkayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPetugasLegalkayu::className(), 'targetAttribute' => ['petugas_legalkayu_id' => 'petugas_legalkayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'keluar_pelabuhan_id' => 'Keluar Pelabuhan',
                'kode' => 'Kode',
                'nomor_nota_angkut' => 'Nomor Nota Angkut',
                'nomor_dkb' => 'Nomor Dkb',
                'nomor_surat_pengantar' => 'Nomor Surat Pengantar',
                'nomor_skshhkb' => 'Nomor Skshhkb',
                'tanggal' => 'Tanggal',
                'cara_keluar' => 'Cara Keluar',
                'jenis_kendaraan' => 'Jenis Kendaraan',
                'kendaraan_nopol' => 'Kendaraan Nopol',
                'kendaraan_supir' => 'Kendaraan Supir',
                'alamat_bongkar' => 'Alamat Bongkar',
                'cust_id' => 'Cust',
                'masaberlaku_awal' => 'Masaberlaku Awal',
                'masaberlaku_akhir' => 'Masaberlaku Akhir',
                'petugas_legalkayu_id' => 'Petugas Legalkayu',
                'noreg' => 'Noreg',
                'status' => 'Status',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'cust_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPetugasLegalkayu()
    {
        return $this->hasOne(MPetugasLegalkayu::className(), ['petugas_legalkayu_id' => 'petugas_legalkayu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTKeluarPelabuhanDetail()
    {
        return $this->hasOne(TKeluarPelabuhanDetail::className(), ['keluar_pelabuhan_id' => 'keluar_pelabuhan_id']);
    }
}
