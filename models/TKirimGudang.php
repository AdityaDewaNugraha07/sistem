<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_kirim_gudang".
 *
 * @property integer $kirim_gudang_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $diketahui
 * @property integer $diserahkan
 * @property double $total_palet
 * @property double $total_pcs
 * @property double $total_m3
 * @property string $status
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 */
class TKirimGudang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $tgl_awal, $tgl_akhir, $jenis_produk;

    public static function tableName()
    {
        return 't_kirim_gudang';
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
            [['tanggal', 'diketahui', 'diserahkan', 'total_palet', 'total_pcs', 'total_m3', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['diketahui', 'diserahkan', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['total_palet', 'total_pcs', 'total_m3'], 'number'],
            [['keterangan'], 'string'],
            [['kode', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'kirim_gudang_id' => 'Kirim Gudang',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'diketahui' => 'Diketahui',
                'diserahkan' => 'Diserahkan',
                'total_palet' => 'Total Palet',
                'total_pcs' => 'Total Pcs',
                'total_m3' => 'Total M3',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
        ];
    }
    
    public static function getOptionListScan()
    {
        $ret = [];
        $res = self::find()->where("cancel_transaksi_id IS NULL AND tanggal = '".date('Y-m-d')."'")->orderBy('kirim_gudang_id DESC')->all();
        if(count($res)>0){
            foreach($res as $i => $r){
                $ret[$r->kode] = $r->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($r->tanggal);
            }
        }
        return $ret;
    }

    public function searchLaporan()
    {
        $table = self::tableName();
        $query = self::find();
        $query = $query->select([
            "t_kirim_gudang_detail.kirim_gudang_detail_id",
            "{$table}.kode",
            "{$table}.tanggal",
            "t_kirim_gudang_detail.nomor_produksi AS nomor_produksi_kirim",
            "m_brg_produk.produk_nama",
            "t_hasil_produksi.qty_kecil",
            "round(t_hasil_produksi.qty_m3::numeric, 4) AS qty_m3",
            "m_pegawai2.pegawai_nama AS diserahkan",
            "t_terima_ko.nomor_produksi",
            "t_terima_ko.created_at",
            "m_gudang.gudang_nm",
            "m_pegawai.pegawai_nama AS petugas_terima",
            "m_brg_produk.produk_group"
        ]);
        $query = $query->join('JOIN', 't_kirim_gudang_detail', 't_kirim_gudang_detail.kirim_gudang_id = t_kirim_gudang.kirim_gudang_id');
        $query = $query->join('JOIN', 'm_brg_produk', 'm_brg_produk.produk_id = t_kirim_gudang_detail.produk_id');
        $query = $query->join('JOIN', 't_hasil_produksi', 't_hasil_produksi.nomor_produksi::text = t_kirim_gudang_detail.nomor_produksi::text');
        $query = $query->join('LEFT JOIN', 'm_pegawai m_pegawai2', 'm_pegawai2.pegawai_id = t_kirim_gudang.diserahkan');
        $query = $query->join('LEFT JOIN', 't_terima_ko', 't_terima_ko.nomor_produksi::text = t_kirim_gudang_detail.nomor_produksi::text');
        $query = $query->join('LEFT JOIN', 'm_gudang', 'm_gudang.gudang_id = t_terima_ko.gudang_id');
        $query = $query->join('LEFT JOIN', 'm_pegawai', 'm_pegawai.pegawai_id = t_terima_ko.petugas_penerima');
        $query = $query->where(['is', 't_kirim_gudang_detail.cancel_transaksi_id', new \yii\db\Expression('null')]);
        $query = $query->andWhere(['is', 't_terima_ko.petugas_penerima', new \yii\db\Expression('null')]);
        if(!empty($this->tgl_awal) && !empty($this->tgl_akhir)) {
            $query = $query->andWhere(['between', "{$table}.tanggal", $this->tgl_awal, $this->tgl_akhir]);
        }
        if(!empty($this->jenis_produk)) {
            $query = $query->andWhere(["m_brg_produk.produk_group" => $this->jenis_produk]);
        }
        $query = $query->orderBy( 
            !empty($_GET['sort']['col']) 
            ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir'])
            : $table . '.created_at ASC'
        );

        // var_dump($query);die;
        // echo "<script>console.log(JSON.parse('". json_encode($query) ."'))</script>";die;
        return $query;
    }
    
    public function searchLaporanDt()
    {
        $searchLaporan = $this->searchLaporan();
        $param['table'] = self::tableName();
        $param['pk'] = $this->tableName() . '.' . self::primaryKey()[0];
        
        if(!empty($searchLaporan->select)) {
            $param['column'] = $searchLaporan->select;
        }
        if(!empty($searchLaporan->groupBy)) {
            $param['group'] = ['GROUP BY ' . implode(", ", $searchLaporan->groupBy)];
        }
        if(!empty($searchLaporan->orderBy)) {
            foreach($searchLaporan->orderBy as $i_order => $order) {
                $param['order'][] = $i_order . " " . (($order == 3) ? "DESC":"ASC");
            }
        }
        // echo "<script>console.log(JSON.parse('". json_encode($param) ."'))</script>";die;
        if($searchLaporan->join) {
            foreach($searchLaporan->join as $join) {
                $param['join'][] = $join[0] . ' ' . $join[1] . " ON " . $join[2];
            }
        }
        $param['where'] = [];
        array_push($param['where'], "t_kirim_gudang_detail.cancel_transaksi_id IS NULL");
        array_push($param['where'], "t_terima_ko.petugas_penerima IS NULL");
        if(!empty($this->tgl_awal) || !empty($this->tgl_awal)) {
            array_push($param['where'], "{$param['table']}.tanggal BETWEEN '{$this->tgl_awal}' AND '{$this->tgl_akhir}'");
        }
        if(!empty($this->jenis_produk)) {
            array_push($param['where'], "m_brg_produk.produk_group = '{$this->jenis_produk}'");
        }

        return $param;
    }
}
