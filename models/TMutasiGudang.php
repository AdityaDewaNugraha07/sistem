<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_mutasi_gudang".
 *
 * @property integer $mutasi_gudang_id
 * @property string $kode
 * @property string $tanggal
 * @property string $nomor_produksi
 * @property integer $gudang_asal
 * @property integer $gudang_tujuan
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
 * @property MGudang $gudangTujuan
 * @property MPegawai $pegawaiMutasi
 * @property TCancelTransaksi $cancelTransaksi
 * @property TProduksi $nomorProduksi
 */ 
class TMutasiGudang extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $persediaan_produk_id, $produk_id, $produk_kode, $tanggal_produksi, $produk_nama,$produk_jenis,$produk_dimensi;
	public $gudang_asal_display,$gudang_tujuan_display;
    public static function tableName()
    {
        return 't_mutasi_gudang';
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
            [['kode', 'tanggal', 'nomor_produksi', 'gudang_asal', 'gudang_tujuan', 'pegawai_mutasi', 'created_at', 'created_by', 'updated_at', 'updated_by','gudang_asal_display'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['gudang_asal', 'gudang_tujuan', 'pegawai_mutasi', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['keterangan'], 'string'],
            [['kode', 'nomor_produksi', 'status'], 'string', 'max' => 50],
            [['gudang_asal'], 'exist', 'skipOnError' => true, 'targetClass' => MGudang::className(), 'targetAttribute' => ['gudang_asal' => 'gudang_id']],
            [['gudang_tujuan'], 'exist', 'skipOnError' => true, 'targetClass' => MGudang::className(), 'targetAttribute' => ['gudang_tujuan' => 'gudang_id']],
            [['pegawai_mutasi'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawai_mutasi' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'mutasi_gudang_id' => Yii::t('app', 'Mutasi Gudang'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'tbko_id' => Yii::t('app', 'Tbko'),
                'gudang_asal' => Yii::t('app', 'Gudang Asal'),
                'gudang_tujuan' => Yii::t('app', 'Gudang Tujuan'),
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
    public function getGudangTujuan()
    {
        return $this->hasOne(MGudang::className(), ['gudang_id' => 'gudang_tujuan']);
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
}
