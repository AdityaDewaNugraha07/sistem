<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "t_produk_kembali".
 *
 * @property integer $produk_kembali_id
 * @property string $kode
 * @property string $tanggal
 * @property string $nomor_produksi
 * @property string $tanggal_produksi
 * @property string $cara_kembali
 * @property string $reff_no
 * @property integer $reff_detail_id
 * @property integer $produk_id
 * @property integer $qty_besar
 * @property string $satuan_besar
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property string $keterangan
 * @property integer $petugas_penerima
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property integer $gudang_id
 * @property MGudang $gudang
 *
 * @property MBrgProduk $produk
 * @property MPegawai $petugasMengeluarkan
 * @property TCancelTransaksi $cancelTransaksi
 */ 
class TProdukKembali extends DeltaBaseActiveRecord
{
    const CARA_KEMBALI_BATAL_SPM = 'BATAL SPM';
	public $produk_kode,$produk_nama,$spm_ko_id;
	public $random,$kubikasi_hasilhitung,$produk_p,$produk_l,$produk_t,$produk_p_satuan,$produk_l_satuan,$produk_t_satuan;
    public static function tableName()
    {
        return 't_produk_kembali';
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
            [['kode', 'tanggal', 'nomor_produksi', 'tanggal_produksi', 'cara_kembali', 'reff_no', 'produk_id', 'satuan_besar', 'satuan_kecil', 'keterangan', 'petugas_penerima', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'tanggal_produksi', 'created_at', 'updated_at'], 'safe'],
            [['reff_detail_id', 'produk_id', 'qty_besar', 'petugas_penerima', 'created_by', 'updated_by', 'cancel_transaksi_id', 'gudang_id'], 'integer'],
            [['qty_kecil', 'kubikasi'], 'number'],
            [['kode', 'nomor_produksi', 'cara_kembali', 'reff_no', 'satuan_besar', 'satuan_kecil'], 'string', 'max' => 50],
            [['keterangan'], 'string', 'max' => 30],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['petugas_penerima'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['petugas_penerima' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'produk_kembali_id' => Yii::t('app', 'Produk Keluar'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'nomor_produksi' => Yii::t('app', 'Nomor Produksi'),
                'tanggal_produksi' => Yii::t('app', 'Tanggal Produksi'),
                'cara_kembali' => Yii::t('app', 'Cara Keluar'),
                'reff_no' => Yii::t('app', 'Reff No'),
                'reff_detail_id' => Yii::t('app', 'Reff Detail'),
                'produk_id' => Yii::t('app', 'Produk'),
                'qty_besar' => Yii::t('app', 'Qty Besar'),
                'satuan_besar' => Yii::t('app', 'Satuan Besar'),
                'qty_kecil' => Yii::t('app', 'Qty Kecil'),
                'satuan_kecil' => Yii::t('app', 'Satuan Kecil'),
                'kubikasi' => Yii::t('app', 'Kubikasi'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'petugas_penerima' => Yii::t('app', 'Petugas Mengeluarkan'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
                'gudang_id' => Yii::t('app', 'Gudang'),
        ];
    } 

    /**
     * @return ActiveQuery
     */
    public function getProduk()
    {
        return $this->hasOne(MBrgProduk::className(), ['produk_id' => 'produk_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPetugasMenerima()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'petugas_penerima']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGudang()
    {
        return $this->hasOne(MGudang::className(), ['gudang_id' => 'gudang_id']);
    }

    /**
     * @return array
     */
    public function searchLaporanDt($spm_ko_id)
    {
        $param['table'] = 't_produk_kembali';
        $param['pk']    = 't_produk_kembali.produk_kembali_id';
        $param['column']= [
            't_produk_kembali.produk_kembali_id',
            't_produk_kembali.nomor_produksi',
            "concat('<strong>', m_brg_produk.produk_kode, '</strong></br>', m_brg_produk.produk_dimensi) AS produk_kode",
            'm_gudang.gudang_nm',
            't_produk_kembali.reff_no',
            't_produk_kembali.qty_kecil',
            't_produk_kembali.kubikasi',
            't_produk_kembali.created_at',
            'm_pegawai.pegawai_nama',
            't_pengajuan_manipulasi.status'
        ];
        $param['join'] = [
            'INNER JOIN m_brg_produk ON m_brg_produk.produk_id = t_produk_kembali.produk_id',
            'INNER JOIN m_gudang ON t_produk_kembali.gudang_id = m_gudang.gudang_id',
            'INNER JOIN m_user ON t_produk_kembali.created_by = m_user.user_id',
            'INNER JOIN m_pegawai ON m_user.pegawai_id = m_pegawai.pegawai_id',
            'INNER JOIN t_pengajuan_manipulasi ON t_pengajuan_manipulasi.kode = t_produk_kembali.reff_no'
        ];
        if($spm_ko_id) {
            $spm = TSpmKo::findOne(['spm_ko_id' => $spm_ko_id]);
            $manipulasi = TPengajuanManipulasi::findOne(['reff_no' => $spm->kode, 'status' => 'PROCESS']);
            $param['where'] = [
                "t_produk_kembali.reff_no = '$manipulasi->kode'"
            ];
        }
        return $param;
    }
}
