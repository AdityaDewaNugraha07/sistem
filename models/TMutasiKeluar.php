<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_mutasi_keluar".
 *
 * @property integer $mutasi_keluar_id
 * @property string $kode
 * @property string $tanggal
 * @property string $nomor_produksi
 * @property integer $gudang_asal
 * @property string $cara_keluar
 * @property integer $pegawai_mutasi
 * @property string $status
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MGudang $gudangAsal
 * @property MPegawai $pegawaiMutasi
 * @property TCancelTransaksi $cancelTransaksi
 * @property TProduksi $nomorProduksi
 */
class TMutasiKeluar extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $persediaan_produk_id,$tanggal_produksi,$produk_nama,$produk_jenis,$produk_dimensi,$gudang_asal_display,$kode_permintaan,$keperluan_permintaan,$keterangan_permintaan;
    public $total_palet,$total_pcs,$total_m3,$qty_kecil,$qty_m3,$permintaan_total_palet,$permintaan_total_pcs,$permintaan_total_m3,$kbj_permintaan,$produk_id;
    public static function tableName()
    {
        return 't_mutasi_keluar';
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
            [['kode', 'tanggal', 'cara_keluar', 'pegawai_mutasi', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'], //, 'nomor_produksi', 'gudang_asal'
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['gudang_asal', 'pegawai_mutasi', 'created_by', 'updated_by', 'cancel_transaksi_id', 'pengajuan_repacking_id'], 'integer'],
            [['keterangan'], 'string'],
            [['kode', 'nomor_produksi', 'cara_keluar', 'status'], 'string', 'max' => 50],
            [['gudang_asal'], 'exist', 'skipOnError' => true, 'targetClass' => MGudang::className(), 'targetAttribute' => ['gudang_asal' => 'gudang_id']],
            [['pegawai_mutasi'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawai_mutasi' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            // [['nomor_produksi'], 'exist', 'skipOnError' => true, 'targetClass' => TProduksi::className(), 'targetAttribute' => ['nomor_produksi' => 'nomor_produksi']],
            [['pengajuan_repacking_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPengajuanRepacking::className(), 'targetAttribute' => ['pengajuan_repacking_id' => 'pengajuan_repacking_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'mutasi_keluar_id' => Yii::t('app', 'Mutasi Keluar'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'nomor_produksi' => Yii::t('app', 'Nomor Produksi'),
                'gudang_asal' => Yii::t('app', 'Gudang Asal'),
                'cara_keluar' => Yii::t('app', 'Cara Keluar'),
                'pegawai_mutasi' => Yii::t('app', 'Pegawai Mutasi'),
                'status' => Yii::t('app', 'Status'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGudangAsal()
    {
        return $this->hasOne(MGudang::className(), ['gudang_id' => 'gudang_asal']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiMutasi()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_mutasi']);
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
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanRepacking()
    {
        return $this->hasOne(TProduksi::className(), ['pengajuan_repacking_id' => 'pengajuan_repacking_id']);
    }
}
