<?php

namespace app\models;

use Yii;
use app\components\SSP;

/**
 * This is the model class for table "t_retur_produk".
 *
 * @property integer $retur_produk_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $nota_penjualan_id
 * @property integer $cust_id
 * @property integer $petugas_penerima
 * @property string $waktu_terima
 * @property string $kendaraan_supir
 * @property string $kendaraan_nopol
 * @property integer $diperiksa_security
 * @property string $keterangan
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property double $total_retur
 *
 * @property MPegawai $petugasPenerima
 * @property TCancelTransaksi $cancelTransaksi
 * @property TNotaPenjualan $notaPenjualan
 */
class TReturProduk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $jenis_produk, $cust_an_nama, $cust_pr_nama, $syarat_jual, $sistem_bayar, $cara_bayar, $cara_bayar_reff, $alamat_bongkar;
    public $total_harga, $kode_nota, $tgl_awal, $tgl_akhir, $gudang_id;
    public static function tableName()
    {
        return 't_retur_produk';
    }

    public function behaviors()
    {
        return [\app\components\DeltaGeneralBehavior::className()];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'tanggal', 'nota_penjualan_id', 'cust_id', 'alasan_retur', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'waktu_terima', 'created_at', 'updated_at', 'total_retur'], 'safe'],
            [['nota_penjualan_id', 'cust_id', 'petugas_penerima', 'diperiksa_security', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['alasan_retur', 'keterangan'], 'string'],
            [['kode', 'status'], 'string', 'max' => 30],
            [['kendaraan_nopol'], 'string', 'max' => 20],
            [['kendaraan_supir'], 'string', 'max' => 50],
            [['petugas_penerima'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['petugas_penerima' => 'pegawai_id']],
            [['diperiksa_security'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['diperiksa_security' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['nota_penjualan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TNotaPenjualan::className(), 'targetAttribute' => ['nota_penjualan_id' => 'nota_penjualan_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'retur_produk_id' => 'Retur Produk',
            'kode' => 'Kode',
            'tanggal' => 'Tanggal',
            'nota_penjualan_id' => 'Nota Penjualan',
            'cust_id' => 'Cust',
            'alasan_retur' => 'Alasan Retur',
            'petugas_penerima' => 'Petugas Penerima',
            'waktu_terima' => 'Waktu Terima',
            'kendaraan_nopol' => 'Nopol Kendaraan',
            'kendaraan_supir' => 'Nama Supir',
            'diperiksa_security' => 'Diperiksa Security',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Create Time',
            'created_by' => 'Created By',
            'updated_at' => 'Last Update Time',
            'updated_by' => 'Last Updated By',
            'cancel_transaksi_id' => 'Cancel Transaksi',
            'total_retur' => 'Total Retur',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPetugasPenerima()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'petugas_penerima']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiperiksaSecurity()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'diperiksa_security']);
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
    public function getNotaPenjualan()
    {
        return $this->hasOne(TNotaPenjualan::className(), ['nota_penjualan_id' => 'nota_penjualan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'cust_id']);
    }

    public function searchLaporan()
    {
        $query = self::find();
        $query->select('
            t_retur_produk.retur_produk_id, 
            t_retur_produk.kode, 
            t_retur_produk.tanggal,
            t_nota_penjualan.kode as nomor_nota, 
            m_customer.cust_an_nama, 
            SUM(t_retur_produk_detail.qty_kecil) AS pcs, 
            SUM(t_retur_produk_detail.kubikasi) AS kubikasi, 
            total_retur
        ');
        $query->join('JOIN', 't_retur_produk_detail', 't_retur_produk_detail.retur_produk_id = t_retur_produk.retur_produk_id');
        $query->join('JOIN', 'm_customer', 'm_customer.cust_id = t_retur_produk.cust_id');
        $query->join('JOIN', 't_nota_penjualan', 't_nota_penjualan.nota_penjualan_id = t_retur_produk.nota_penjualan_id');
        $query->groupBy('
            t_retur_produk.retur_produk_id, 
            t_retur_produk.kode, 
            t_retur_produk.tanggal,
            t_nota_penjualan.kode, 
            m_customer.cust_an_nama
        ');
        $query->orderBy(
            !empty($_GET['sort']['col']) 
            ? SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) 
            : 't_retur_produk.created_at ASC'
        );
        if ((!empty($this->tgl_awal)) && (!empty($this->tgl_akhir))) {
            $query->andWhere("t_retur_produk.tanggal BETWEEN '" . $this->tgl_awal . "' AND '" . $this->tgl_akhir . "' ");
        }
        if (!empty($this->jenis_produk)) {
            $query->andWhere("jenis_produk = '" . $this->jenis_produk . "' ");
        }
        return $query;
    }

    public function searchLaporanDt()
    {
        $searchLaporan = $this->searchLaporan();
        $param['table'] = self::tableName();
        $param['pk'] = $param['table'] . '.' . self::primaryKey()[0];
        if (!empty($searchLaporan->groupBy)) {
            $param['column'] = ['GROUP BY ' . implode(", ", $searchLaporan->groupBy)];
        }
        if (!empty($searchLaporan->select)) {
            $param['column'] = $searchLaporan->select;
        }
        if (!empty($searchLaporan->groupBy)) {
            $param['group'] = ['GROUP BY ' . implode(", ", $searchLaporan->groupBy)];
        }
        if (!empty($searchLaporan->orderBy)) {
            foreach ($searchLaporan->orderBy as $i_order => $order) {
                $param['order'][] = $i_order . " " . (($order == 3) ? "DESC" : "ASC");
            }
        }
        if (!empty($searchLaporan->join)) {
            foreach ($searchLaporan->join as $join) {
                $param['join'][] = $join[0] . ' ' . $join[1] . " ON " . $join[2];
            }
        }
        $param['where'] = [];
        if ((!empty($this->tgl_awal)) || (!empty($this->tgl_akhir))) {
            array_push($param['where'], self::tableName() . ".tanggal BETWEEN '" . $this->tgl_awal . "' AND '" . $this->tgl_akhir . "' ");
        }
        if (!empty($this->jenis_produk)) {
            array_push($param['where'], "jenis_produk = '" . $this->jenis_produk . "'");
        }
        return $param;
    }
}
