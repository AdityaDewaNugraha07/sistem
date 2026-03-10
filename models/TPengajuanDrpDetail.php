<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengajuan_drp_detail".
 *
 * @property integer $pengajuan_drp_detail_id
 * @property integer $pengajuan_drp_id
 * @property integer $voucher_pengeluaran_id
 */
class TPengajuanDrpDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal,$tgl_akhir, $status_approve, $cara_bayar,$cara_bayar_reff, $status_bayar;
    public $total_nominal, $tanggal_bayar;
    public $kode, $tanggal, $tipe, $suplier_nm, $gkk_kode, $gkk_id, $ppk_kode, $ppk_id, $kode_drp, $kepada;
    public $pdg_kode, $ajuandinas_grader_id, $pmg_kode, $ajuanmakan_grader_id, $kode_dp, $log_bayar_dp_id;
    public $kode_pelunasan, $log_bayar_muat_id, $nama_penerima, $nama_perusahaan, $tipe_ov, $suplierov, $status;
    public $bank, $rek, $rek_an, $suplier_bank, $suplier_norekening, $suplier_an_rekening, $grader_makan, $grader_dinas;
    public $suplier_nm_company,$penerima_pembayaran;
    public static function tableName()
    {
        return 't_pengajuan_drp_detail';
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
            [['kategori'], 'required'],
            [['pengajuan_drp_id', 'voucher_pengeluaran_id'], 'integer'],
            [['reff_ket', 'keterangan'], 'string'],
            [['kategori'], 'string', 'max' => 20],
            [['status_pengajuan'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengajuan_drp_detail_id' => 'Pengajuan Drp Detail',
                'pengajuan_drp_id' => 'Pengajuan Drp',
                'voucher_pengeluaran_id' => 'Voucher Pengeluaran',
                'reff_ket' => 'Reff',
                'keterangan' => 'Keterangan',
                'kategori' => 'Kategori',
                'status_pengajuan' => '',
        ];
    }

    public function searchLaporan() {
		$query = self::find();
		$query->select( self::tableName().".pengajuan_drp_detail_id, 
                        t_pengajuan_drp.kode as kode_drp,
                        t_voucher_pengeluaran.kode, 
                        t_pengajuan_drp.tanggal,"
                        .self::tableName().".kategori, 
                        t_voucher_pengeluaran.tipe as tipe,
                        m_suplier.suplier_nm, 
                        (case when t_voucher_pengeluaran.tipe is not null then 
                                case when t_open_voucher.tipe = 'REGULER' then concat(m_penerima_voucher.nama_penerima)
                                    when t_open_voucher.tipe = 'PEMBAYARAN ASURANSI LOG SHIPPING' then t_asuransi.kepada
                                    else case when a.suplier_nm_company is not null then a.suplier_nm_company else a.suplier_nm end
                                    end
                            end) as suplierov,
                        t_gkk.gkk_id,	
                        t_gkk.kode AS gkk_kode,
                        t_ppk.ppk_id, 
                        t_ppk.kode AS ppk_kode,
                        t_ajuandinas_grader.ajuandinas_grader_id, 
                        t_ajuandinas_grader.kode AS pdg_kode,
                        t_ajuanmakan_grader.ajuanmakan_grader_id, 
                        t_ajuanmakan_grader.kode AS pmg_kode, 
                        t_log_bayar_dp.log_bayar_dp_id, 
                        t_log_bayar_dp.kode AS kode_dp,
                        t_log_bayar_muat.log_bayar_muat_id, 
                        t_log_bayar_muat.kode AS kode_pelunasan,
                        t_voucher_pengeluaran.total_nominal,
                        (case when t_pengajuan_drp.cancel_transaksi_id is null then t_pengajuan_drp.status_approve else 'ABORTED' end) as status,
                        t_voucher_pengeluaran.penerima_pembayaran,
                        t_voucher_pengeluaran.penerima_pembayaran,
                        t_voucher_pengeluaran.penerima_pembayaran,
                        m_suplier.suplier_bank,
                        m_suplier.suplier_norekening,
                        m_suplier.suplier_an_rekening,"
                        .self::tableName().".reff_ket,"
                        .self::tableName().".keterangan,
                        t_voucher_pengeluaran.voucher_pengeluaran_id,
                        t_open_voucher.tipe as tipe_ov,
                        g.graderlog_nm as grader_makan,
 	                    c.graderlog_nm as grader_dinas,
                        m_suplier.suplier_nm_company,
                        t_pengajuan_drp_detail.status_pengajuan,
                        t_voucher_pengeluaran.cara_bayar,
                        t_voucher_pengeluaran.cara_bayar_reff,
                        t_voucher_pengeluaran.status_bayar,
                        t_voucher_pengeluaran.tanggal_bayar
						");
        $query->join('LEFT JOIN', 't_pengajuan_drp', self::tableName().'.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id');
		$query->join('LEFT JOIN', 't_voucher_pengeluaran','t_voucher_pengeluaran.voucher_pengeluaran_id = t_pengajuan_drp_detail.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 'm_suplier','m_suplier.suplier_id = t_voucher_pengeluaran.suplier_id');
        $query->join('LEFT JOIN', 't_gkk','t_gkk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_ppk','t_ppk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_ajuandinas_grader','t_ajuandinas_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_ajuanmakan_grader','t_ajuanmakan_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_log_bayar_dp','t_log_bayar_dp.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_log_bayar_muat','t_log_bayar_muat.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_open_voucher','t_open_voucher.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 'm_penerima_voucher','m_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id');
        $query->join('LEFT JOIN', 'm_suplier AS a','a.suplier_id = t_open_voucher.penerima_reff_id');
        $query->join('LEFT JOIN', 't_asuransi','t_asuransi.kode = t_open_voucher.reff_no');
        $query->join('LEFT JOIN', 'm_graderlog AS g','g.graderlog_id = t_ajuanmakan_grader.graderlog_id');
        $query->join('LEFT JOIN', 'm_graderlog AS c','c.graderlog_id = t_ajuandinas_grader.graderlog_id');
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			't_pengajuan_drp.created_at DESC, t_pengajuan_drp.pengajuan_drp_id ASC' );
        // $query->where("(status_pengajuan IS NULL OR status_pengajuan = 'Disetujui')");
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_pengajuan_drp.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kategori)){
			$query->andWhere(self::tableName().".kategori ILIKE '%".$this->kategori."%'");
		}
        if(!empty($this->status_approve)){
			$query->andWhere("CASE WHEN t_pengajuan_drp.cancel_transaksi_id IS NULL THEN t_pengajuan_drp.status_approve ELSE 'ABORTED' END = '".$this->status_approve."'");
		}
        if(!empty($this->kode)){
            if (is_array($this->kode)) {
                if (isset($this->kode)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->kode as $k) {
                        $subq.="t_pengajuan_drp.kode = '".$k."' ";
                        if ($cn < count($this->kode)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("t_pengajuan_drp.kode = '".$this->kode."'");
            }            
        }
        if(!empty($this->status_bayar)){
			$query->andWhere("t_voucher_pengeluaran.status_bayar = '".$this->status_bayar."'");
		}
		return $query;
	}

    public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
		if(!empty($searchLaporan->groupBy)){
			$param['column'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}	
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}
		if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}
		if(!empty($searchLaporan->orderBy)){
			foreach($searchLaporan->orderBy as $i_order => $order){
				$param['order'][] = $i_order." ".(($order == 3)?"DESC":"ASC");
			}
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
		$param['where'] = []; //"(status_pengajuan IS NULL OR status_pengajuan = 'Disetujui')"
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"t_pengajuan_drp.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kategori)){
			array_push($param['where'],self::tableName().".kategori ILIKE '%".$this->kategori."%'");
		}
        if(!empty($this->status_approve)){
			array_push($param['where'],"CASE WHEN t_pengajuan_drp.cancel_transaksi_id IS NULL THEN t_pengajuan_drp.status_approve ELSE 'ABORTED' END = '".$this->status_approve."'");
		}
        if(!empty($this->kode)){
            if (is_array($this->kode)) {
                if (isset($this->kode)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->kode as $k) {
                        $subq.="t_pengajuan_drp.kode = '".$k."'";
                        if ($cn < count($this->kode)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"t_pengajuan_drp.kode = '".$this->kode."'");
            }     
        }
        if(!empty($this->status_bayar)){
			array_push($param['where'],"t_voucher_pengeluaran.status_bayar = '".$this->status_bayar."'");
		}
		return $param;
	}

    public function searchRelease() {
		$query = self::find();
		$query->select( self::tableName().".pengajuan_drp_detail_id, 
                        t_pengajuan_drp.kode as kode_drp,
                        t_voucher_pengeluaran.kode, 
                        t_pengajuan_drp.tanggal,"
                        .self::tableName().".kategori, 
                        t_voucher_pengeluaran.tipe as tipe,
                        m_suplier.suplier_nm, 
                        (case when t_voucher_pengeluaran.tipe is not null then 
                                case when t_open_voucher.tipe = 'REGULER' then concat(m_penerima_voucher.nama_penerima)
                                    when t_open_voucher.tipe = 'PEMBAYARAN ASURANSI LOG SHIPPING' then t_asuransi.kepada
                                    else case when a.suplier_nm_company is not null then a.suplier_nm_company else a.suplier_nm end
                                    end
                            end) as suplierov,
                        t_gkk.gkk_id,	
                        t_gkk.kode AS gkk_kode,
                        t_ppk.ppk_id, 
                        t_ppk.kode AS ppk_kode,
                        t_ajuandinas_grader.ajuandinas_grader_id, 
                        t_ajuandinas_grader.kode AS pdg_kode,
                        t_ajuanmakan_grader.ajuanmakan_grader_id, 
                        t_ajuanmakan_grader.kode AS pmg_kode, 
                        t_log_bayar_dp.log_bayar_dp_id, 
                        t_log_bayar_dp.kode AS kode_dp,
                        t_log_bayar_muat.log_bayar_muat_id, 
                        t_log_bayar_muat.kode AS kode_pelunasan,
                        t_voucher_pengeluaran.total_nominal,
                        (case when t_pengajuan_drp.cancel_transaksi_id is null then t_pengajuan_drp.status_approve else 'ABORTED' end) as status,
                        t_voucher_pengeluaran.penerima_pembayaran,
                        t_voucher_pengeluaran.penerima_pembayaran,
                        t_voucher_pengeluaran.penerima_pembayaran,
                        m_suplier.suplier_bank,
                        m_suplier.suplier_norekening,
                        m_suplier.suplier_an_rekening,"
                        .self::tableName().".reff_ket,"
                        .self::tableName().".keterangan,
                        t_voucher_pengeluaran.voucher_pengeluaran_id,
                        t_open_voucher.tipe as tipe_ov,
                        g.graderlog_nm as grader_makan,
 	                    c.graderlog_nm as grader_dinas,
                        m_suplier.suplier_nm_company,
                        t_pengajuan_drp_detail.status_pengajuan, 
                        t_voucher_pengeluaran.cara_bayar,
                        t_voucher_pengeluaran.cara_bayar_reff
						");
        $query->join('LEFT JOIN', 't_pengajuan_drp', self::tableName().'.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id');
		$query->join('LEFT JOIN', 't_voucher_pengeluaran','t_voucher_pengeluaran.voucher_pengeluaran_id = t_pengajuan_drp_detail.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 'm_suplier','m_suplier.suplier_id = t_voucher_pengeluaran.suplier_id');
        $query->join('LEFT JOIN', 't_gkk','t_gkk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_ppk','t_ppk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_ajuandinas_grader','t_ajuandinas_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_ajuanmakan_grader','t_ajuanmakan_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_log_bayar_dp','t_log_bayar_dp.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_log_bayar_muat','t_log_bayar_muat.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 't_open_voucher','t_open_voucher.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 'm_penerima_voucher','m_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id');
        $query->join('LEFT JOIN', 'm_suplier AS a','a.suplier_id = t_open_voucher.penerima_reff_id');
        $query->join('LEFT JOIN', 't_asuransi','t_asuransi.kode = t_open_voucher.reff_no');
        $query->join('LEFT JOIN', 'm_graderlog AS g','g.graderlog_id = t_ajuanmakan_grader.graderlog_id');
        $query->join('LEFT JOIN', 'm_graderlog AS c','c.graderlog_id = t_ajuandinas_grader.graderlog_id');
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			't_pengajuan_drp.created_at DESC, t_pengajuan_drp.pengajuan_drp_id ASC' );
        $query->where("(status_pengajuan <> 'Ditunda')");
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_pengajuan_drp.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kategori)){
			$query->andWhere(self::tableName().".kategori ILIKE '%".$this->kategori."%'");
		}
        if(!empty($this->status_approve)){
			$query->andWhere("CASE WHEN t_pengajuan_drp.cancel_transaksi_id IS NULL THEN t_pengajuan_drp.status_approve ELSE 'ABORTED' END = '".$this->status_approve."'");
		}
        if(!empty($this->kode)){
            if (is_array($this->kode)) {
                if (isset($this->kode)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->kode as $k) {
                        $subq.="t_pengajuan_drp.kode = '".$k."' ";
                        if ($cn < count($this->kode)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("t_pengajuan_drp.kode = '".$this->kode."'");
            }            
        }
        if(!empty($this->status_bayar)){
			$query->andWhere("t_voucher_pengeluaran.status_bayar = '".$this->status_bayar."'");
		}
		return $query;
	}
} 