<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use app\components\SSP;
use phpDocumentor\Reflection\Type;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "t_hasil_repacking".
 *
 * @property integer $hasil_repacking_id
 * @property string $kode
 * @property string $tanggal
 * @property string $nomor_produksi
 * @property string $tanggal_produksi
 * @property integer $produk_id
 * @property double $p
 * @property double $l
 * @property double $t
 * @property string $p_satuan
 * @property string $l_satuan
 * @property string $t_satuan
 * @property string $jenis_palet
 * @property integer $qty_palet
 * @property double $qty_kecil
 * @property string $qty_kecil_satuan
 * @property double $qty_m3
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MBrgProduk $produk
 * @property TPengajuanRepacking $pengajuanRepacking
 */
class THasilRepacking extends ActiveRecord
{
    public $produk_nama,$produk_jenis,$produk_dimensi,$qty_besar_satuan,$kode_permintaan,$dibuat_permintaan,$keperluan_permintaan,$keterangan_permintaan;
	public $total_qty,$total_m3,$total_qty_satuan,$nomor_urut_produksi,$produk_kode,$qty_m3_display;
    public $tgl_awal,$tgl_akhir,$jenis_produk;
    public $jenis_kayu, $grade, $glue, $profil_kayu, $kondisi_kayu;
    public $hasil_dari_retur;

    public static function tableName()
    {
        return 't_hasil_repacking';
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
            [['kode', 'tanggal', 'nomor_produksi', 'tanggal_produksi', 'produk_id', 'qty_kecil_satuan', 'keterangan', 'created_at', 'created_by', 'updated_at', 'updated_by', 'hasil_dari'], 'required'],
            [['produk_id', 'qty_palet', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['tanggal', 'tanggal_produksi', 'created_at', 'updated_at'], 'safe'],
            [['p', 'l', 't', 'qty_m3'], 'number'],
            [['kode', 'nomor_produksi', 'p_satuan', 'l_satuan', 't_satuan', 'jenis_palet', 'qty_kecil_satuan', 'hasil_dari'], 'string', 'max' => 50],
            [['keterangan'], 'string', 'max' => 30],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'hasil_repacking_id' => 'Hasil Repacking',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'nomor_produksi' => 'Nomor Produksi',
                'tanggal_produksi' => 'Tanggal Produksi',
                'produk_id' => 'Produk',
                'p' => 'P',
                'l' => 'L',
                't' => 'T',
                'p_satuan' => 'P Satuan',
                'l_satuan' => 'L Satuan',
                't_satuan' => 'T Satuan',
                'jenis_palet' => 'Jenis Palet',
                'qty_palet' => 'Qty Palet',
                'qty_kecil' => 'Qty Kecil',
                'qty_kecil_satuan' => 'Qty Kecil Satuan',
                'qty_m3' => 'Qty M3',
                'keterangan' => 'Keterangan',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'hasil_dari' => 'Hasil Dari',
                'jenis_kayu' => Yii::t('app', 'Jenis Kayu'),
                'jenis_produk'  => Yii::t('app', 'Jenis Produk'),
                'grade' => Yii::t('app', 'Grade'),
                'glue'  => Yii::t('app', 'Glue'),
                'profil_kayu'   => Yii::t('app', 'Profil Kayu'),
                'kondisi_kayu'  => Yii::t('app', 'Kondisi Kayu')
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
     * @param bool $tampil_semua
     * @return ActiveQuery
     */
    public function searchLaporan($tampil_semua = false)
    {
        $table = self::tableName();
        $query = self::find();
        $query = $query->select([
            "$table.hasil_repacking_id",
            "$table.kode",
            "$table.tanggal",
            "$table.nomor_produksi AS nomor_produksi_kirim",
            "m_brg_produk.produk_nama",
            "$table.qty_kecil",
            "round($table.qty_m3::numeric, 4) AS qty_m3",
            "m_pegawai2.pegawai_nama AS diserahkan",
            "t_terima_ko.nomor_produksi",
            "t_terima_ko.created_at",
            "m_gudang.gudang_nm",
            "m_pegawai.pegawai_nama AS petugas_terima",
            "m_brg_produk.produk_group"
        ]);
        $query = $query->join('JOIN', 'm_brg_produk', 'm_brg_produk.produk_id = t_hasil_repacking.produk_id');
        $query = $query->join('LEFT JOIN', 'view_user m_pegawai2', 'm_pegawai2.user_id = t_hasil_repacking.created_by');
        $query = $query->join('LEFT JOIN', 't_terima_ko', 't_terima_ko.nomor_produksi::text = t_hasil_repacking.nomor_produksi::text');
        $query = $query->join('LEFT JOIN', 'm_gudang', 'm_gudang.gudang_id = t_terima_ko.gudang_id');
        $query = $query->join('LEFT JOIN', 'm_pegawai', 'm_pegawai.pegawai_id = t_terima_ko.petugas_penerima');
        $query = $query->where(['is', 't_hasil_repacking.cancel_transaksi_id', new Expression('null')]);
        if($tampil_semua === false) {
            $query = $query->andWhere(['is', 't_terima_ko.petugas_penerima', new Expression('null')]);
        }
        if(!empty($this->tgl_awal) && !empty($this->tgl_akhir)) {
            $query = $query->andWhere(['between', "$table.tanggal", $this->tgl_awal, $this->tgl_akhir]);
        }
        if(!empty($this->jenis_produk)) {
            $query = $query->andWhere(["m_brg_produk.produk_group" => $this->jenis_produk]);
        }

        if(!empty($this->jenis_kayu)){
            if (is_array($this->jenis_kayu)) {
                $subq=null;
                $cn=1;
                $subq.='(';
                foreach ($this->jenis_kayu as $id_jenis_kayu) {
                    $subq.="jenis_kayu = '".$id_jenis_kayu."' ";
                    if ($cn < count($this->jenis_kayu)) {
                        $subq.=' OR ';
                    }
                    $cn++;
                }
                $subq.=')';
                if (!empty($subq)) {
                    $query->andWhere($subq);
                }
            }else{
                $query->andWhere("jenis_kayu = '".$this->jenis_kayu."'");
            }
        }

        if(!empty($this->grade)){
            if (is_array($this->grade)) {
                $subq=null;
                $cn=1;
                $subq.='(';
                foreach ($this->grade as $id_grade) {
                    $subq.="jenis_kayu = '".$id_grade."' ";
                    if ($cn < count($this->grade)) {
                        $subq.=' OR ';
                    }
                    $cn++;
                }
                $subq.=')';
                if (!empty($subq)) {
                    $query->andWhere($subq);
                }
            }else{
                $query->andWhere("grade = '".$this->grade."'");
            }
        }
        if(!empty($this->glue)){
            if (is_array($this->glue)) {
                $subq=null;
                $cn=1;
                $subq.='(';
                foreach ($this->glue as $id_glue) {
                    $subq.="jenis_kayu = '".$id_glue."' ";
                    if ($cn < count($this->glue)) {
                        $subq.=' OR ';
                    }
                    $cn++;
                }
                $subq.=')';
                if (!empty($subq)) {
                    $query->andWhere($subq);
                }
            }else{
                $query->andWhere("glue = '".$this->glue."'");
            }
        }

        if(!empty($this->profil_kayu)){
            if (is_array($this->profil_kayu)) {
                $subq=null;
                $cn=1;
                $subq.='(';
                foreach ($this->profil_kayu as $id_profil_kayu) {
                    $subq.="jenis_kayu = '".$id_profil_kayu."' ";
                    if ($cn < count($this->profil_kayu)) {
                        $subq.=' OR ';
                    }
                    $cn++;
                }
                $subq.=')';
                if (!empty($subq)) {
                    $query->andWhere($subq);
                }
            }else{
                $query->andWhere("profil_kayu = '".$this->profil_kayu."'");
            }
        }
        if(!empty($this->kondisi_kayu)){
            if (is_array($this->kondisi_kayu)) {
                $subq=null;
                $cn=1;
                $subq.='(';
                foreach ($this->kondisi_kayu as $id_kondisi_kayu) {
                    $subq.="jenis_kayu = '".$id_kondisi_kayu."' ";
                    if ($cn < count($this->kondisi_kayu)) {
                        $subq.=' OR ';
                    }
                    $cn++;
                }
                $subq.=')';
                if (!empty($subq)) {
                    $query->andWhere($subq);
                }
            }else{
                $query->andWhere("kondisi_kayu = '".$this->kondisi_kayu."'");
            }
        }
        if(!empty($this->hasil_dari)){
            if($this->hasil_dari == 'Penanganan Barang Retur'){
                if(!empty($this->hasil_dari_retur)){
                    $query->andWhere("hasil_dari = 'Penanganan Barang Retur-" . $this->hasil_dari_retur . "'");
                } else {
                    $query->andWhere("hasil_dari ilike '%Penanganan Barang Retur%'");
                }
            } else {
                $query->andWhere("hasil_dari = '$this->hasil_dari'");
            }
        }

        return $query->orderBy(
            !empty($_GET['sort']['col'])
            ? SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir'])
            : $table . '.created_at ASC'
        );
    }

    /**
     * @param bool $tampil_semua
     * @return array
     */
    public function searchLaporanDt($tampil_semua = false)
    {
        $searchLaporan = $this->searchLaporan($tampil_semua);
        $param['table'] = self::tableName();
        $param['pk'] = self::tableName() . '.' . self::primaryKey()[0];
        
        if(!empty($searchLaporan->select)) {
            $param['column'] = $searchLaporan->select;
        }
        if(!empty($searchLaporan->groupBy)) {
            $param['group'] = ['GROUP BY ' . implode(", ", $searchLaporan->groupBy)];
        }
        if(!empty($searchLaporan->orderBy)) {
            foreach($searchLaporan->orderBy as $i_order => $order) {
                $param['order'][] = $i_order . " " . (($order === 3) ? "DESC":"ASC");
            }
        }
        if($searchLaporan->join) {
            foreach($searchLaporan->join as $join) {
                $param['join'][] = $join[0] . ' ' . $join[1] . " ON " . $join[2];
            }
        }
        $param['where'] = [];
        $param['where'][] = "t_hasil_repacking.cancel_transaksi_id IS NULL";
        if($tampil_semua === false) {
            $param['where'][] = "t_terima_ko.petugas_penerima IS NULL";
        }
        if(!empty($this->tgl_awal)) {
            $param['where'][] = "{$param['table']}.tanggal BETWEEN '$this->tgl_awal' AND '$this->tgl_akhir'";
        }
        if(!empty($this->jenis_produk)) {
            $param['where'][] = "m_brg_produk.produk_group = '$this->jenis_produk'";
        }
        if(!empty($this->jenis_kayu)){
            if (is_array($this->jenis_kayu)) {
                $subq=null;
                $cn=1;
                $subq.='(';
                foreach ($this->jenis_kayu as $id_jenis_kayu) {
                    $subq.="jenis_kayu = '".$id_jenis_kayu."' ";
                    if ($cn < count($this->jenis_kayu)) {
                        $subq.=' OR ';
                    }
                    $cn++;
                }
                $subq.=')';
                if (!empty($subq)) {
                    $param['where'][] = $subq;
                }
            }else{
                $param['where'][] = "jenis_kayu = '" . $this->jenis_kayu . "'";
            }
        }
        if(!empty($this->grade)){
            if (is_array($this->grade)) {
                $subq=null;
                $cn=1;
                $subq.='(';
                foreach ($this->grade as $id_grade) {
                    $subq.="grade = '".$id_grade."' ";
                    if ($cn < count($this->grade)) {
                        $subq.=' OR ';
                    }
                    $cn++;
                }
                $subq.=')';
                if (!empty($subq)) {
                    $param['where'][] = $subq;
                }
            }else{
                $param['where'][] = "grade = '" . $this->grade . "'";
            }
        }
        if(!empty($this->glue)){
            if (is_array($this->glue)) {
                $subq=null;
                $cn=1;
                $subq.='(';
                foreach ($this->glue as $id_glue) {
                    $subq.="glue = '".$id_glue."' ";
                    if ($cn < count($this->glue)) {
                        $subq.=' OR ';
                    }
                    $cn++;
                }
                $subq.=')';
                if (!empty($subq)) {
                    $param['where'][] = $subq;
                }
            }else{
                $param['where'][] = "glue = '" . $this->glue . "'";
            }
        }

        if(!empty($this->profil_kayu)){
            if (is_array($this->profil_kayu)) {
                $subq=null;
                $cn=1;
                $subq.='(';
                foreach ($this->profil_kayu as $id_profil_kayu) {
                    $subq.="profil_kayu = '".$id_profil_kayu."' ";
                    if ($cn < count($this->profil_kayu)) {
                        $subq.=' OR ';
                    }
                    $cn++;
                }
                $subq.=')';
                if (!empty($subq)) {
                    $param['where'][] = $subq;
                }
            }else{
                $param['where'][] = "profil_kayu = '" . $this->profil_kayu . "'";
            }
        }
        if(!empty($this->kondisi_kayu)){
            if (is_array($this->kondisi_kayu)) {
                $subq=null;
                $cn=1;
                $subq.='(';
                foreach ($this->kondisi_kayu as $id_kondisi_kayu) {
                    $subq.="kondisi_kayu = '".$id_kondisi_kayu."' ";
                    if ($cn < count($this->kondisi_kayu)) {
                        $subq.=' OR ';
                    }
                    $cn++;
                }
                $subq.=')';
                if (!empty($subq)) {
                    $param['where'][] = $subq;
                }
            }else{
                $param['where'][] = "kondisi_kayu = '" . $this->kondisi_kayu . "'";
            }
        }
        if(!empty($this->hasil_dari)){
            if($this->hasil_dari == 'Penanganan Barang Retur'){
                if(!empty($this->hasil_dari_retur)){
                    $param['where'][] = "hasil_dari = 'Penanganan Barang Retur-" . $this->hasil_dari_retur . "'";
                } else {
                    $param['where'][] = "hasil_dari ilike '%Penanganan Barang Retur%'";
                }
            } else {
                $param['where'][] = "hasil_dari = '$this->hasil_dari'";
            }
        }

        return $param;
    }
}
