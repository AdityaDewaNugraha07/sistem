<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_dokumen_penjualan".
 *
 * @property integer $dokumen_penjualan_id
 * @property string $nomor_dokumen
 * @property string $tanggal
 * @property string $jenis_produk
 * @property string $jenis_dokumen
 * @property integer $spm_ko_id
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
 * @property string $skshhko_no
 *
 * @property MCustomer $cust
 * @property MPetugasLegalkayu $petugasLegalkayu
 * @property TCancelTransaksi $cancelTransaksi
 * @property TSpmKo $spmKo
 * @property TDokumenPenjualanDetail[] $tDokumenPenjualanDetails
 */
class TDokumenPenjualan extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kode_spm, $cust_pr_nama, $cust_an_nama, $cust_an_alamat, $cust_is_pkp, $masaberlaku_hari, $petugas_legalkayu;
    public $tgl_awal, $tgl_akhir, $pegawai_nama, $kode, $provinsi, $terima_logalam_id;

    public static function tableName()
    {
        return 't_dokumen_penjualan';
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
            [['nomor_dokumen', 'tanggal', 'jenis_produk', 'jenis_dokumen', 'spm_ko_id', 'kendaraan_nopol', 'kendaraan_supir', 'alamat_bongkar', 'cust_id', 'petugas_legalkayu_id', 'noreg', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'masaberlaku_awal', 'masaberlaku_akhir', 'created_at', 'updated_at'], 'safe'],
            [['spm_ko_id', 'cust_id', 'petugas_legalkayu_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['alamat_bongkar', 'cust_alamat'], 'string'],
            [['nomor_dokumen', 'jenis_produk', 'jenis_dokumen', 'kendaraan_supir', 'status', 'skshhko_no'], 'string', 'max' => 50],
            [['kendaraan_nopol'], 'string', 'max' => 20],
            [['noreg'], 'string', 'max' => 100],
            [['nomor_dokumen'], 'unique'],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['petugas_legalkayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPetugasLegalkayu::className(), 'targetAttribute' => ['petugas_legalkayu_id' => 'petugas_legalkayu_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['spm_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpmKo::className(), 'targetAttribute' => ['spm_ko_id' => 'spm_ko_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dokumen_penjualan_id' => Yii::t('app', 'Dokumen Penjualan'),
            'nomor_dokumen' => Yii::t('app', 'Nomor Dokumen'),
            'tanggal' => Yii::t('app', 'Tanggal Terbit'),
            'jenis_produk' => Yii::t('app', 'Jenis Produk'),
            'jenis_dokumen' => Yii::t('app', 'Jenis Dokumen'),
            'spm_ko_id' => Yii::t('app', 'Spm Ko'),
            'kendaraan_nopol' => Yii::t('app', 'Nopol Kendaraan'),
            'kendaraan_supir' => Yii::t('app', 'Nama Supir'),
            'alamat_bongkar' => Yii::t('app', 'Alamat Bongkar'),
            'cust_id' => Yii::t('app', 'Cust'),
            'masaberlaku_awal' => Yii::t('app', 'Masa Berlaku Awal'),
            'masaberlaku_akhir' => Yii::t('app', 'Masa Berlaku Akhir'),
            'masaberlaku_hari' => Yii::t('app', 'Lama Hari Berlaku'),
            'petugas_legalkayu_id' => Yii::t('app', 'Petugas TUK'),
            'noreg' => Yii::t('app', 'No Reg Petugas'),
            'status' => Yii::t('app', 'Status'),
            'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
            'skshhko_no' => Yii::t('app', 'No. SKSHHKO'),
            'cust_alamat' => Yii::t('app', 'Alamat Customer'),
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
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpmKo()
    {
        return $this->hasOne(TSpmKo::className(), ['spm_ko_id' => 'spm_ko_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTDokumenPenjualanDetails()
    {
        return $this->hasMany(TDokumenPenjualanDetail::className(), ['dokumen_penjualan_id' => 'dokumen_penjualan_id']);
    }

    public function searchLaporan()
    {
        $query = self::find();
        $query->select(self::tableName() . '.dokumen_penjualan_id, jenis_dokumen, ' . self::tableName() . '.tanggal, nomor_dokumen, t_nota_penjualan.kode, m_customer.cust_an_nama, ' . self::tableName() . '.kendaraan_nopol, ' . self::tableName() . '.kendaraan_supir, ' . self::tableName() . '.alamat_bongkar, m_pegawai.pegawai_nama, ' . self::tableName() . '.noreg');
        $query->join('JOIN', 't_nota_penjualan', 't_nota_penjualan.spm_ko_id = ' . self::tableName() . '.spm_ko_id');
        $query->join('JOIN', 'm_customer', 'm_customer.cust_id = ' . self::tableName() . '.cust_id');
        $query->join('JOIN', 'm_petugas_legalkayu', 'm_petugas_legalkayu.petugas_legalkayu_id = ' . self::tableName() . '.petugas_legalkayu_id');
        $query->join('JOIN', 'm_pegawai', 'm_pegawai.pegawai_id = m_petugas_legalkayu.pegawai_id');
        $query->groupBy(self::tableName() . '.dokumen_penjualan_id, jenis_dokumen, ' . self::tableName() . '.tanggal, nomor_dokumen, t_nota_penjualan.kode, m_customer.cust_an_nama, ' . self::tableName() . '.kendaraan_nopol, ' . self::tableName() . '.kendaraan_supir, ' . self::tableName() . '.alamat_bongkar, m_pegawai.pegawai_nama, ' . self::tableName() . '.noreg');
        $query->orderBy(!empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) :
            self::tableName() . '.created_at DESC');
        $query->where("jenis_dokumen <> 'DKB'");
        if ((!empty($this->tgl_awal)) && (!empty($this->tgl_akhir))) {
            $query->andWhere(self::tableName() . ".tanggal BETWEEN '" . $this->tgl_awal . "' AND '" . $this->tgl_akhir . "' ");
        }
        if (!empty($this->jenis_produk)) {
            $query->andWhere(self::tableName() . ".jenis_produk ILIKE '%" . $this->jenis_produk . "%'");
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
        $param['where'] = ["jenis_dokumen <> 'DKB'"];
        if ((!empty($this->tgl_awal)) || (!empty($this->tgl_akhir))) {
            array_push($param['where'], $param['table'] . ".tanggal BETWEEN '" . $this->tgl_awal . "' AND '" . $this->tgl_akhir . "' ");
        }
        if (!empty($this->jenis_produk)) {
            array_push($param['where'], self::tableName() . ".jenis_produk ILIKE '%" . $this->jenis_produk . "%' ");
        }
        return $param;
    }

    public static function getOptionList()
    {
        $res = self::find()->where("cancel_transaksi_id IS NULL")->orderBy('created_at DESC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'dokumen_penjualan_id', 'nomor_dokumen');
    }

    public static function getOptionListJasaKD()
    {
        $res = self::find()->where("cancel_transaksi_id IS NULL and jenis_produk='JasaKD'")->orderBy('nomor_dokumen DESC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'dokumen_penjualan_id', 'nomor_dokumen');
    }

    public static function getOptionListCustomer()
    {
        $res = MCustomer::find()->join("JOIN", "t_dokumen_penjualan", "m_customer.cust_id = t_dokumen_penjualan.cust_id")->where("active IS TRUE")->orderBy('t_dokumen_penjualan.created_at DESC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'cust_id', 'cust_an_nama');
    }


    public function searchLaporanPenerbitanKo()
    {
        $query = self::find();
        $query->select(["dokumen_penjualan_id", "(CASE WHEN jenis_dokumen='DKO' THEN skshhko_no ELSE nomor_dokumen END) AS no_seri", "t_nota_penjualan.tanggal AS tanggal_nota",
            // "(CASE WHEN t_dokumen_penjualan.jenis_produk = 'Veneer' OR t_dokumen_penjualan.jenis_produk = 'Plywood' THEN t_dokumen_penjualan.jenis_produk ELSE 'Gergajian' END) AS jenis_hh",
            "(CASE 
                WHEN t_dokumen_penjualan.jenis_produk = 'Sawntimber' OR t_dokumen_penjualan.jenis_produk = 'Moulding' THEN 'Gergajian' 
                WHEN t_dokumen_penjualan.jenis_produk = 'FingerJointSolid' THEN 'FJS'
                WHEN t_dokumen_penjualan.jenis_produk = 'FingerJointStick' THEN 'FJS'
                WHEN t_dokumen_penjualan.jenis_produk = 'FingerJointLamineBoard' THEN 'FJLB'
              ELSE t_dokumen_penjualan.jenis_produk END) AS jenis_hh",
            "(SELECT SUM(qty_kecil) FROM t_dokumen_penjualan_detail WHERE t_dokumen_penjualan_detail.dokumen_penjualan_id=t_dokumen_penjualan.dokumen_penjualan_id) AS kpg",
            "(SELECT ROUND(SUM(kubikasi)::numeric,4) FROM t_dokumen_penjualan_detail WHERE t_dokumen_penjualan_detail.dokumen_penjualan_id=t_dokumen_penjualan.dokumen_penjualan_id) AS m3",
            "m_customer.cust_an_nama", "t_dokumen_penjualan.alamat_bongkar", "t_op_ko.provinsi_bongkar", "t_spm_ko.kendaraan_nopol"]);
        $query->join('JOIN', 'm_customer', 'm_customer.cust_id = ' . self::tableName() . '.cust_id');
        $query->join('JOIN', 't_spm_ko', 't_spm_ko.spm_ko_id = ' . self::tableName() . '.spm_ko_id');
        $query->join('JOIN', 't_op_ko', 't_op_ko.op_ko_id = t_spm_ko.op_ko_id');
        $query->join('JOIN', 't_nota_penjualan', 't_nota_penjualan.spm_ko_id = ' . self::tableName() . '.spm_ko_id');
        $query->orderBy(!empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) :
            self::tableName() . '.created_at DESC');
        if ((!empty($this->tgl_awal)) && (!empty($this->tgl_akhir))) {
            $query->andWhere("t_nota_penjualan.tanggal BETWEEN '" . $this->tgl_awal . "' AND '" . $this->tgl_akhir . "' ");
        }
        if (!empty($this->jenis_produk)) {
            $query->andWhere(self::tableName() . ".jenis_produk = '" . $this->jenis_produk . "'");
        }
        if (!empty($this->cust_id)) {
            $query->andWhere(self::tableName() . ".cust_id = " . $this->cust_id);
        }
        if (!empty($this->provinsi)) {
            $query->andWhere("t_op_ko.provinsi_bongkar ILIKE '%" . $this->provinsi . "%'");
        }
        return $query;
    }

    public function searchLaporanPenerbitanKoDt()
    {
        $searchLaporan = $this->searchLaporanPenerbitanKo();
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
            array_push($param['where'], "t_nota_penjualan.tanggal BETWEEN '" . $this->tgl_awal . "' AND '" . $this->tgl_akhir . "' ");
        }
        if (!empty($this->jenis_produk)) {
            array_push($param['where'], self::tableName() . ".jenis_produk = '" . $this->jenis_produk . "' ");
        }
        if (!empty($this->cust_id)) {
            array_push($param['where'], self::tableName() . ".cust_id = " . $this->cust_id);
        }
        if (!empty($this->provinsi)) {
            array_push($param['where'], "t_op_ko.provinsi_bongkar ILIKE '%" . $this->provinsi . "%' ");
        }
        return $param;
    }

    public function searchLaporanPenjualanKb()
    {
        $query = self::find();
        $query->select(self::tableName() . '.dokumen_penjualan_id, jenis_dokumen, ' . self::tableName() . '.tanggal, nomor_dokumen, t_nota_penjualan.kode, m_customer.cust_an_nama, ' . self::tableName() . '.kendaraan_nopol, ' . self::tableName() . '.kendaraan_supir, ' . self::tableName() . '.alamat_bongkar, m_pegawai.pegawai_nama, ' . self::tableName() . '.noreg');
        $query->join('JOIN', 't_nota_penjualan', 't_nota_penjualan.spm_ko_id = ' . self::tableName() . '.spm_ko_id');
        $query->join('JOIN', 'm_customer', 'm_customer.cust_id = ' . self::tableName() . '.cust_id');
        $query->join('JOIN', 'm_petugas_legalkayu', 'm_petugas_legalkayu.petugas_legalkayu_id = ' . self::tableName() . '.petugas_legalkayu_id');
        $query->join('JOIN', 'm_pegawai', 'm_pegawai.pegawai_id = m_petugas_legalkayu.pegawai_id');
        $query->groupBy(self::tableName() . '.dokumen_penjualan_id, jenis_dokumen, ' . self::tableName() . '.tanggal, nomor_dokumen, t_nota_penjualan.kode, m_customer.cust_an_nama, ' . self::tableName() . '.kendaraan_nopol, ' . self::tableName() . '.kendaraan_supir, ' . self::tableName() . '.alamat_bongkar, m_pegawai.pegawai_nama, ' . self::tableName() . '.noreg');
        $query->orderBy(!empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) :
            self::tableName() . '.created_at DESC');
        $query->where("jenis_dokumen = 'DKB'");
        if ((!empty($this->tgl_awal)) && (!empty($this->tgl_akhir))) {
            $query->andWhere(self::tableName() . ".tanggal BETWEEN '" . $this->tgl_awal . "' AND '" . $this->tgl_akhir . "' ");
        }
        return $query;
    }

    public function searchLaporanPenjualanKbDt()
    {
        $searchLaporan = $this->searchLaporanPenjualanKb();
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
        $param['where'] = ["jenis_dokumen = 'DKB'"];
        if ((!empty($this->tgl_awal)) || (!empty($this->tgl_akhir))) {
            array_push($param['where'], $param['table'] . ".tanggal BETWEEN '" . $this->tgl_awal . "' AND '" . $this->tgl_akhir . "' ");
        }
        return $param;
    }
}
