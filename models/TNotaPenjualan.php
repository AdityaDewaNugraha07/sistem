<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_nota_penjualan".
 *
 * @property integer $nota_penjualan_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $op_ko_id
 * @property string $jenis_produk
 * @property string $syarat_jual
 * @property string $sistem_bayar
 * @property string $cara_bayar
 * @property string $cara_bayar_reff
 * @property integer $spm_ko_id
 * @property string $kendaraan_nopol
 * @property string $kendaraan_supir
 * @property string $alamat_bongkar
 * @property integer $cust_id
 * @property boolean $cust_is_pkp
 * @property string $mata_uang
 * @property double $total_harga
 * @property double $total_ppn
 * @property double $total_pph
 * @property double $total_potongan
 * @property double $total_bayar
 * @property string $status
 * @property string $keterangan_potongan
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property integer $control_by
 * @property string $status_approval
 * @property string $approve_reason
 * @property string $reject_reason
 *
 * @property MCustomer $cust
 * @property TCancelTransaksi $cancelTransaksi
 * @property TOpKo $opKo
 * @property TSpmKo $spmKo
 * @property TNotaPenjualanDetail[] $tNotaPenjualanDetails
 * @property TSuratPengantar[] $tSuratPengantars
 * @property mixed|null $cust_alamat
 */
class TNotaPenjualan extends DeltaBaseActiveRecord
{
    public $kode_spm, $cust_an_nama, $cust_pr_nama, $cust_an_alamat, $tgl_awal, $tgl_akhir;

    public static function tableName()
    {
        return 't_nota_penjualan';
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
            [['kode', 'tanggal', 'op_ko_id', 'jenis_produk', 'syarat_jual', 'sistem_bayar', 'cara_bayar', 'spm_ko_id', 'kendaraan_nopol', 'kendaraan_supir', 'alamat_bongkar', 'cust_id', 'cust_is_pkp', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['op_ko_id', 'spm_ko_id', 'cust_id', 'cancel_transaksi_id', 'created_by', 'updated_by', 'control_by'], 'integer'],
//            [['op_ko_id', 'spm_ko_id', 'cust_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
//            [['alamat_bongkar', 'keterangan_potongan'], 'string'],
            [['alamat_bongkar', 'keterangan_potongan', 'status_approval', 'approve_reason', 'reject_reason', 'cust_alamat'], 'string'],
            [['cust_is_pkp'], 'boolean'],
            [['total_harga', 'total_ppn', 'total_pph', 'total_potongan', 'total_bayar'], 'number'],
            [['kode'], 'string', 'max' => 25],
            [['jenis_produk', 'syarat_jual', 'sistem_bayar', 'cara_bayar', 'cara_bayar_reff', 'kendaraan_supir', 'mata_uang', 'status'], 'string', 'max' => 50],
            [['kendaraan_nopol'], 'string', 'max' => 20],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['op_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpKo::className(), 'targetAttribute' => ['op_ko_id' => 'op_ko_id']],
            [['spm_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpmKo::className(), 'targetAttribute' => ['spm_ko_id' => 'spm_ko_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nota_penjualan_id' => Yii::t('app', 'Nota Penjualan'),
            'kode' => Yii::t('app', 'Kode Nota'),
            'tanggal' => Yii::t('app', 'Tanggal Nota'),
            'op_ko_id' => Yii::t('app', 'Op Ko'),
            'jenis_produk' => Yii::t('app', 'Jenis Produk'),
            'syarat_jual' => Yii::t('app', 'Syarat Jual'),
            'sistem_bayar' => Yii::t('app', 'Sistem Bayar'),
            'cara_bayar' => Yii::t('app', 'Cara Bayar'),
            'cara_bayar_reff' => Yii::t('app', 'Cara Bayar Reff'),
            'spm_ko_id' => Yii::t('app', 'Spm Ko'),
            'kendaraan_nopol' => Yii::t('app', 'Kendaraan Nopol'),
            'kendaraan_supir' => Yii::t('app', 'Kendaraan Supir'),
            'alamat_bongkar' => Yii::t('app', 'Alamat Bongkar'),
            'cust_id' => Yii::t('app', 'Cust'),
            'cust_is_pkp' => Yii::t('app', 'Cust Is Pkp'),
            'mata_uang' => Yii::t('app', 'Mata Uang'),
            'total_harga' => Yii::t('app', 'Total Harga'),
            'total_ppn' => Yii::t('app', 'Total Ppn'),
            'total_pph' => Yii::t('app', 'Total Pph'),
            'total_potongan' => Yii::t('app', 'Total Potongan'),
            'total_bayar' => Yii::t('app', 'Total Bayar'),
            'status' => Yii::t('app', 'Status'),
            'keterangan_potongan' => Yii::t('app', 'Keterangan Potongan'),
            'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
            'control_by' => Yii::t('app', 'Control By'),
            'status_approval' => Yii::t('app', 'Status Approval'),
            'approve_reason' => Yii::t('app', 'Approve Reason'),
            'reject_reason' => Yii::t('app', 'Reject Reason'),
            'cust_alamat'        => Yii::t('app', 'Customer Address')
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
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpKo()
    {
        return $this->hasOne(TOpKo::className(), ['op_ko_id' => 'op_ko_id']);
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
    public function getTNotaPenjualanDetails()
    {
        return $this->hasMany(TNotaPenjualanDetail::className(), ['nota_penjualan_id' => 'nota_penjualan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTPiutangPenjualans()
    {
        return $this->hasMany(TPiutangPenjualan::className(), ['nota_penjualan_id' => 'nota_penjualan_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSuratPengantars()
    {
        return $this->hasMany(TSuratPengantar::className(), ['nota_penjualan_id' => 'nota_penjualan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(MUser::className(), ['user_id' => 'created_by']);
    }

    public static function updateStatusPembayaran($kode, $status)
    {
        $model = self::findOne(['kode' => $kode]);
        $model->status = $status;
        if ($model->validate()) {
            if ($model->save()) {
                return true;
            } else {
                return false;
            }
        }
    }


    public function searchLaporan()
    {
        $query = self::find();
        $query->select(self::tableName() . '.nota_penjualan_id, kode, tanggal, cust_an_nama, SUM(qty_besar) AS palet, SUM(qty_kecil) AS pcs, SUM(kubikasi) AS kubikasi, total_bayar, jenis_produk');
        $query->join('JOIN', 't_nota_penjualan_detail', 't_nota_penjualan_detail.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id');
        $query->join('JOIN', 'm_customer', 'm_customer.cust_id = t_nota_penjualan.cust_id');
        $query->groupBy(self::tableName() . '.nota_penjualan_id, kode, tanggal, cust_an_nama, total_bayar');
        $query->orderBy(!empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) :
            self::tableName() . '.created_at ASC');
        if ((!empty($this->tgl_awal)) && (!empty($this->tgl_akhir))) {
            $query->andWhere("tanggal BETWEEN '" . $this->tgl_awal . "' AND '" . $this->tgl_akhir . "' ");
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
            array_push($param['where'], "tanggal BETWEEN '" . $this->tgl_awal . "' AND '" . $this->tgl_akhir . "' ");
        }
        if (!empty($this->jenis_produk)) {
            array_push($param['where'], "jenis_produk = '" . $this->jenis_produk . "'");
        }
        return $param;
    }

    public static function getOptionListNotaDokumen()
    {
        $res = self::find()->where("cancel_transaksi_id IS NULL AND t_nota_penjualan.spm_ko_id NOT IN( SELECT spm_ko_id FROM t_dokumen_penjualan )  AND t_nota_penjualan.jenis_produk NOT IN('Limbah', 'Log') AND op_ko_id != 999999")
            ->orderBy('created_at DESC')->all();
        $return = [];
        foreach ($res as $i => $val) {
            $return[$val['spm_ko_id']] = $val['kode'] . ' - ' . $val['jenis_produk'];
        }
        return $return;
    }

    public static function getOptionListPayment($cust_id)
    {
        $res = self::find()->where("cancel_transaksi_id IS NULL AND status != 'PAID' AND cust_id = " . $cust_id)->orderBy('created_at DESC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'kode', 'kode');
    }

    public static function getOptionListRetur()
    {
        $res = self::find()
            ->select("nota_penjualan_id, kode, cust_an_nama")
            ->join("JOIN", "m_customer", "m_customer.cust_id = t_nota_penjualan.cust_id")
//				->where("cancel_transaksi_id IS NULL AND nota_penjualan_id NOT IN( SELECT nota_penjualan_id FROM t_retur_produk WHERE cancel_transaksi_id IS NULL )")
            ->where("cancel_transaksi_id IS NULL")
            ->orderBy('t_nota_penjualan.created_at DESC')->all();

        $return = [];
        foreach ($res as $i => $val) {
            $return[$val['nota_penjualan_id']] = $val['kode'] . ' - ' . $val['cust_an_nama'];
        }
        return $return;
    }

    public static function getOptionListNotaDokumenKB()
    {
        $res = self::find()->where("cancel_transaksi_id IS NULL AND t_nota_penjualan.spm_ko_id NOT IN( SELECT spm_ko_id FROM t_dokumen_penjualan )  AND t_nota_penjualan.jenis_produk IN('Log') AND op_ko_id != 999999")
            ->orderBy('created_at DESC')->all();
        $return = [];
        foreach ($res as $i => $val) {
            $return[$val['spm_ko_id']] = $val['kode'] . ' - ' . $val['jenis_produk'];
        }
        return $return;
    }
}
