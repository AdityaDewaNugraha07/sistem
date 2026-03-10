<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_mutasi".
 *
 * @property integer $terima_mutasi_id
 * @property string $kode
 * @property string $reff_no
 * @property string $reff_no2
 * @property string $tanggal
 * @property string $nomor_produksi
 * @property integer $pegawai_terima
 * @property string $status
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MPegawai $pegawaiTerima
 * @property TCancelTransaksi $cancelTransaksi
 * @property TProduksi $nomorProduksi
 */
class TTerimaMutasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_terima_mutasi';
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
            [['kode', 'reff_no', 'tanggal', 'nomor_produksi', 'pegawai_terima', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['pegawai_terima', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['keterangan'], 'string'],
            [['kode', 'reff_no', 'reff_no2', 'nomor_produksi', 'status'], 'string', 'max' => 50],
            [['pegawai_terima'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawai_terima' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            // [['nomor_produksi'], 'exist', 'skipOnError' => true, 'targetClass' => TProduksi::className(), 'targetAttribute' => ['nomor_produksi' => 'nomor_produksi']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'terima_mutasi_id' => 'Terima Mutasi',
                'kode' => 'Kode',
                'reff_no' => 'Reff No',
                'reff_no2' => 'Reff No2',
                'tanggal' => 'Tanggal',
                'nomor_produksi' => 'Nomor Produksi',
                'pegawai_terima' => 'Pegawai Terima',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiTerima()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_terima']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNomorProduksi()
    {
        return $this->hasOne(TProduksi::className(), ['nomor_produksi' => 'nomor_produksi']);
    }
}
