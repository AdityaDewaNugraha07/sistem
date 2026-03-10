<?php

namespace app\models;

use Yii;
use app\components\SSP;

/**
 * This is the model class for table "t_log_keluar".
 *
 * @property integer $log_keluar_id
 * @property string $kode
 * @property string $tanggal
 * @property string $no_barcode
 * @property string $cara_keluar
 * @property string $reff_no
 * @property string $keterangan
 * @property integer $pic_log_keluar
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MPegawai $picLogKeluar
 * @property TCancelTransaksi $cancelTransaksi
 */
class TLogKeluar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const CARA_KELUAR_PENJUALAN = "Trading";
    public $tgl_awal, $tgl_akhir, $spm_ko_id, $fsc;
    public $kayu_id, $satuan_besar, $log_kode, $log_nama, $qty_besar, $qty_kecil, $satuan_kecil, $kubikasi;
    public $produk_id;
    public static function tableName()
    {
        return 't_log_keluar';
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
            [['kode', 'tanggal', 'cara_keluar', 'reff_no', 'pic_log_keluar', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['keterangan'], 'string'],
            [['pic_log_keluar', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['kode', 'no_barcode', 'cara_keluar', 'reff_no'], 'string', 'max' => 50],
            [['no_barcode'], 'unique'],
            [['pic_log_keluar'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pic_log_keluar' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'log_keluar_id' => 'Log Keluar',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'no_barcode' => 'No Barcode',
                'cara_keluar' => 'Jenis Peruntukan',
                'reff_no' => 'Nomor Nota',
                'keterangan' => 'Keterangan',
                'pic_log_keluar' => 'Pic Log Keluar',
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
    public function getPicLogKeluar()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pic_log_keluar']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    public function searchLaporan() {
		$query = self::find();
		$query->select('t_log_keluar.log_keluar_id, 
                        t_log_keluar.tanggal, 
                        t_log_keluar.kode, 
                        t_log_keluar.reff_no, 
                        t_log_keluar.cara_keluar, 
                        m_pegawai.pegawai_nama,
                        m_kayu.kayu_nama, 
                        t_log_keluar.no_barcode, 
                        h_persediaan_log.no_grade, 
                        h_persediaan_log.no_lap, 
                        h_persediaan_log.no_btg, 
                        h_persediaan_log.pot, 
                        h_persediaan_log.fisik_panjang, 
                        h_persediaan_log.diameter_ujung1, 
                        h_persediaan_log.diameter_ujung2, 
                        h_persediaan_log.diameter_pangkal1, 
                        h_persediaan_log.diameter_pangkal2, 
                        h_persediaan_log.fisik_diameter, 
                        h_persediaan_log.cacat_panjang, 
                        h_persediaan_log.cacat_gb, 
                        h_persediaan_log.cacat_gr, 
                        h_persediaan_log.fisik_volume
                    ');
        $query->innerJoin('h_persediaan_log', "h_persediaan_log.no_barcode = t_log_keluar.no_barcode AND h_persediaan_log.status = 'IN'");
        $query->innerJoin('m_kayu', 'm_kayu.kayu_id = h_persediaan_log.kayu_id');
        $query->innerJoin('m_pegawai', 'm_pegawai.pegawai_id = t_log_keluar.pic_log_keluar');
		$query->andWhere([self::tableName().'.cancel_transaksi_id'=>NULL]);
		$query->orderBy( !empty($_GET['sort']['col'])? SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):"" );
		return $query;
	}
	
	public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= self::primaryKey()[0];
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}		
		if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
		$param['where'] = [];
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
        if (!empty($this->cara_keluar)){
			array_push($param['where'],"cara_keluar = '".$this->cara_keluar."'");
		}
		return $param;
	}

    public function searchLaporanPersediaan() {
		$query = HPersediaanLog::find();
		$query->select("
            h_persediaan_log.persediaan_log_id,
            h_persediaan_log.tgl_transaksi,
            h_persediaan_log.reff_no,
            CASE WHEN \"h_persediaan_log\".\"lokasi\" ILIKE'%PRODUKSI%' THEN 'Industri' ELSE'Trading' END AS cara_keluar,
            m_pegawai.pegawai_nama,
            m_kayu.kayu_nama,
            h_persediaan_log.no_barcode,
            h_persediaan_log.no_grade,
            h_persediaan_log.no_lap,
            h_persediaan_log.no_btg,
            h_persediaan_log.pot,
            h_persediaan_log.fisik_panjang,
            h_persediaan_log.diameter_ujung1,
            h_persediaan_log.diameter_ujung2,
            h_persediaan_log.diameter_pangkal1,
            h_persediaan_log.diameter_pangkal2,
            h_persediaan_log.fisik_diameter,
            h_persediaan_log.cacat_panjang,
            h_persediaan_log.cacat_gb,
            h_persediaan_log.cacat_gr,
            h_persediaan_log.fisik_volume,
            h_persediaan_log.fsc
        ");
        $query->innerJoin('m_kayu', 'm_kayu.kayu_id = h_persediaan_log.kayu_id');
        $query->innerJoin('m_user', 'm_user.user_id = h_persediaan_log.created_by');
        $query->innerJoin('m_pegawai', 'm_pegawai.pegawai_id = m_user.pegawai_id');
        $query->where("h_persediaan_log.status = 'OUT' AND h_persediaan_log.lokasi ILIKE '%LOG ALAM%' AND h_persediaan_log.no_barcode <> '-'");
		$query->orderBy( !empty($_GET['sort']['col'])? SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):"" );
		return $query;
	}

    public function searchLaporanPersediaanDt() {
		$searchLaporan = $this->searchLaporanPersediaan();
		$param['table']= HPersediaanLog::tableName();
		$param['pk']= HPersediaanLog::primaryKey()[0];
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}		
		if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
        if(!empty($searchLaporan->where)) {
            $param['where'][] = $searchLaporan->where;
        }
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
            $param['where'][] = "h_persediaan_log.tgl_transaksi BETWEEN '{$this->tgl_awal}' AND '{$this->tgl_akhir}' ";
		}
        if (!empty($this->cara_keluar)){
            if($this->cara_keluar === 'Industri') {
                $param['where'][] = "h_persediaan_log.lokasi ILIKE '%PRODUKSI%'";
            }else {
                $param['where'][] = "h_persediaan_log.lokasi ILIKE '%PENJUALAN%'";
            }
		}
        if( (!empty($this->fsc))){
            $param['where'][] = "h_persediaan_log.fsc = {$this->fsc} ";
		}
		return $param;
	}

    public function searchLogKeluarBlmPotong(){
        $query = self::find();
        $query->select("
            t_log_keluar.log_keluar_id, 
            t_log_keluar.tanggal, 
            t_log_keluar.reff_no, 
            t_log_keluar.cara_keluar, 
            m_pegawai.pegawai_nama, 
            m_kayu.kayu_nama,
            t_log_keluar.no_barcode, 
            h_persediaan_log.no_grade, 
            h_persediaan_log.no_lap, 
            h_persediaan_log.no_btg, 
            h_persediaan_log.pot, 
            h_persediaan_log.fisik_panjang, 
            h_persediaan_log.diameter_ujung1, 
            h_persediaan_log.diameter_ujung2, 
            h_persediaan_log.diameter_pangkal1,
            h_persediaan_log.diameter_pangkal2, 
            h_persediaan_log.fisik_diameter, 
            h_persediaan_log.cacat_panjang, 
            h_persediaan_log.cacat_gb,
            h_persediaan_log.cacat_gr, 
            h_persediaan_log.fisik_volume, 
            h_persediaan_log.fsc
        ");
        $query->innerJoin('h_persediaan_log', 't_log_keluar.no_barcode = h_persediaan_log.no_barcode');
        $query->innerJoin('m_kayu', 'm_kayu.kayu_id = h_persediaan_log.kayu_id');
        $query->innerJoin('m_pegawai', 'm_pegawai.pegawai_id = t_log_keluar.pic_log_keluar');
        $query->where(" t_log_keluar.cara_keluar = 'Industri' AND 
                        NOT EXISTS (SELECT t_log_keluar.no_barcode FROM t_pemotongan_log_detail_potong 
                            WHERE t_log_keluar.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama) 
                        AND NOT EXISTS (SELECT t_log_keluar.no_barcode FROM view_persediaan_logalam 
                            WHERE t_log_keluar.no_barcode = view_persediaan_logalam.no_barcode)");
        $query->groupBy("   t_log_keluar.log_keluar_id, t_log_keluar.no_barcode,m_kayu.kayu_nama, h_persediaan_log.no_grade, h_persediaan_log.no_btg,
                            h_persediaan_log.no_lap,h_persediaan_log.fisik_diameter, h_persediaan_log.fisik_panjang,h_persediaan_log.fisik_volume, m_pegawai.pegawai_nama,
                            h_persediaan_log.pot,h_persediaan_log.diameter_ujung1, h_persediaan_log.diameter_ujung2, h_persediaan_log.diameter_pangkal1,
                            h_persediaan_log.diameter_pangkal2,h_persediaan_log.cacat_panjang, h_persediaan_log.cacat_gb, h_persediaan_log.cacat_gr, h_persediaan_log.fsc
                        ");
        $query->orderBy( !empty($_GET['sort']['col'])? SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):
                "t_log_keluar.log_keluar_id DESC" );
        return $query;
    }

    public function searchLogKeluarBlmPotongDt(){
        $searchLaporan = $this->searchLogKeluarBlmPotong();
		$param['table']= TLogKeluar::tableName();
		$param['pk']= TLogKeluar::primaryKey()[0];
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}		
		if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
        if(!empty($searchLaporan->where)) {
            $param['where'][] = $searchLaporan->where;
        }
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
            $param['where'][] = "t_log_keluar.tanggal BETWEEN '{$this->tgl_awal}' AND '{$this->tgl_akhir}' ";
		}
        if( (!empty($this->fsc))){
            $param['where'][] = "h_persediaan_log.fsc = {$this->fsc} ";
		}
		return $param;
    }

}
