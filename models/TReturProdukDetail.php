<?php

namespace app\models;

use Yii;
use app\components\SSP;

/**
 * This is the model class for table "t_retur_produk_detail".
 *
 * @property integer $retur_produk_detail_id
 * @property integer $retur_produk_id
 * @property integer $produk_id
 * @property double $qty_besar
 * @property string $satuan_besar
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property double $harga_jual
 * @property double $harga_retur
 * @property string $keterangan
 * @property string $nomor_produksi
 * @property integer $gudang_id
 *
 * @property MBrgProduk $produk
 * @property TReturProduk $returProduk
 */
class TReturProdukDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $subtotal, $kubikasi_display, $tgl_awal, $tgl_akhir, $jenis_produk, $cust_id, $sales_id;
    public $produk_group, $per_tanggal, $jenis_kayu, $grade, $glue, $profil_kayu, $kondisi_kayu, $produk_nama, $status;
    public static function tableName()
    {
        return 't_retur_produk_detail';
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
            [['retur_produk_id', 'produk_id', 'qty_besar', 'satuan_besar', 'qty_kecil', 'satuan_kecil', 'kubikasi', 'harga_jual', 'harga_retur'], 'required'],
            [['retur_produk_id', 'produk_id', 'gudang_id'], 'integer'],
            [['qty_besar', 'qty_kecil', 'kubikasi', 'harga_jual', 'harga_retur'], 'number'],
            [['keterangan'], 'string'],
            [['satuan_besar', 'satuan_kecil', 'nomor_produksi'], 'string', 'max' => 50],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['retur_produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => TReturProduk::className(), 'targetAttribute' => ['retur_produk_id' => 'retur_produk_id']],
            [['gudang_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGudang::className(), 'targetAttribute' => ['gudang_id' => 'gudang_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'retur_produk_detail_id' => 'Retur Produk Detail',
                'retur_produk_id' => 'Retur Produk',
                'produk_id' => 'Produk',
                'qty_besar' => 'Qty Besar',
                'satuan_besar' => 'Satuan Besar',
                'qty_kecil' => 'Qty Kecil',
                'satuan_kecil' => 'Satuan Kecil',
                'kubikasi' => 'Kubikasi',
                'harga_jual' => 'Harga Jual',
                'harga_retur' => 'Harga Retur',
                'keterangan' => 'Keterangan',
                'nomor_produksi' => 'No Produksi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduk()
    {
        return $this->hasOne(MBrgProduk::className(), ['produk_id' => 'produk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturProduk()
    {
        return $this->hasOne(TReturProduk::className(), ['retur_produk_id' => 'retur_produk_id']);
    }

    public function searchLaporan() {
		$query = self::find();
        $query->select("
            t_retur_produk_detail.retur_produk_detail_id,
            t_retur_produk.kode,
            t_retur_produk.tanggal,
            t_nota_penjualan.kode as nomor_nota,
            m_sales.sales_kode,
            m_customer.cust_an_nama,
            m_customer.cust_an_alamat,
            m_brg_produk.produk_group,
            m_brg_produk.produk_nama,
            m_brg_produk.produk_dimensi,
            t_retur_produk_detail.qty_kecil,
            t_retur_produk_detail.kubikasi,
            t_retur_produk_detail.harga_jual,
            t_retur_produk_detail.harga_retur
        ");
        $query->innerJoin("t_retur_produk", "t_retur_produk_detail.retur_produk_id = t_retur_produk.retur_produk_id");
        $query->innerJoin("t_nota_penjualan", "t_retur_produk.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id");
        $query->innerJoin("t_op_ko", "t_nota_penjualan.op_ko_id = t_op_ko.op_ko_id");
        $query->innerJoin("m_sales", "t_op_ko.sales_id = m_sales.sales_id");
        $query->innerJoin("m_customer", "t_nota_penjualan.cust_id = m_customer.cust_id AND t_op_ko.cust_id = m_customer.cust_id");
        $query->innerJoin("m_brg_produk", "t_retur_produk_detail.produk_id = m_brg_produk.produk_id");
        $query->orderBy( 
            !empty($_GET['sort']['col'])
            ? SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir'])
            : self::tableName().'.retur_produk_detail_id ASC' 
        );
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
            $query->andWhere("t_retur_produk.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
        }
        if(!empty($this->jenis_produk)){
            $query->andWhere("m_brg_produk.produk_group  = '".$this->jenis_produk."'");
        }
        if(!empty($this->cust_id)){
            $query->andWhere("m_customer.cust_id  = ".$this->cust_id);
        }
        if(!empty($this->sales_id)){
            $query->andWhere("m_sales.sales_id  = '".$this->sales_id."'");
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
		$param['where'] = [];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"t_retur_produk.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->jenis_produk)){
			array_push($param['where'],"m_brg_produk.produk_group ILIKE '%".$this->jenis_produk."%'");
		}
		if(!empty($this->cust_id)){
			array_push($param['where'],"m_customer.cust_id = ".$this->cust_id);
		}
		if(!empty($this->sales_id)){
			array_push($param['where'],"m_sales.sales_id = '".$this->sales_id."'");
		}
		return $param;
	}

    public function getGudang()
    {
        return $this->hasOne(MGudang::className(), ['gudang_id' => 'gudang_id']);
    }

    public function searchLaporanStock(){
        $query = self::find();
        $query->select("
            t_retur_produk_detail.produk_id, 
            m_brg_produk.produk_group, 
            m_brg_produk.produk_nama, 
	        m_brg_produk.produk_dimensi, 
            count(*) as palet, 
            sum(t_retur_produk_detail.qty_kecil) as qty_kecil, 
	        sum(t_retur_produk_detail.kubikasi) as kubikasi
        ");
        $query->innerJoin("t_retur_produk", "t_retur_produk.retur_produk_id = t_retur_produk_detail.retur_produk_id");
        $query->innerJoin("m_brg_produk", "t_retur_produk_detail.produk_id = m_brg_produk.produk_id");
        $query->groupBy("t_retur_produk_detail.produk_id,m_brg_produk.produk_group, m_brg_produk.produk_nama, m_brg_produk.produk_dimensi");
        $query->orderBy( 
            !empty($_GET['sort']['col'])
            ? SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir'])
            : self::tableName().'.produk_id ASC' 
        );
        $query->andWhere("nomor_produksi IS not null AND NOT EXISTS (SELECT nomor_produksi FROM t_mutasi_keluar mk 
                                WHERE mk.nomor_produksi = t_retur_produk_detail.nomor_produksi)");
        if(!empty($this->gudang_id)){
			$query->andWhere("t_retur_produk_detail.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->produk_nama)){
			$query->andWhere("produk_nama ILIKE '%".$this->produk_nama."%'");
		}
		if(!empty($this->produk_group)){
			$query->andWhere("produk_group ILIKE '%".$this->produk_group."%'");
		}
        if(!empty($this->jenis_kayu)){
            if (is_array($this->jenis_kayu)) {
                if (isset($this->jenis_kayu)) {
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
                }
            }else{
                $query->andWhere("jenis_kayu = '".$this->jenis_kayu."'");
            }            
        }
        if(!empty($this->grade)){
            if (is_array($this->grade)) {
                if (isset($this->grade)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("grade = '".$this->grade."'");
            }            
        }
        if(!empty($this->glue)){
            if (is_array($this->glue)) {
                if (isset($this->glue)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("glue = '".$this->glue."'");
            }            
        }
        if(!empty($this->profil_kayu)){
            if (is_array($this->profil_kayu)) {
                if (isset($this->profil_kayu)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("profil_kayu = '".$this->profil_kayu."'");
            }            
        }
        if(!empty($this->kondisi_kayu)){
            if (is_array($this->kondisi_kayu)) {
                if (isset($this->kondisi_kayu)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("kondisi_kayu = '".$this->kondisi_kayu."'");
            }            
        }
		if(!empty($this->per_tanggal)){
			$query->andWhere("waktu_terima <= '".$this->per_tanggal." 23:59:59'");
		}
		return $query;
    }

    public function searchLaporanStockDt() {
		$searchLaporan = $this->searchLaporanStock();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
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
		
		$param['where'] = ["nomor_produksi IS not null AND NOT EXISTS (SELECT nomor_produksi FROM t_mutasi_keluar mk 
                                        WHERE mk.nomor_produksi = t_retur_produk_detail.nomor_produksi)"];
		if(!empty($this->gudang_id)){
			array_push($param['where'],"t_retur_produk_detail.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->produk_group)){
			array_push($param['where'],"produk_group ILIKE '%".$this->produk_group."%'");
		}
        if(!empty($this->produk_nama)){
			array_push($param['where'],"produk_nama ILIKE '%".$this->produk_nama."%'");
		}
        if(!empty($this->jenis_kayu)){
            if (is_array($this->jenis_kayu)) {
                if (isset($this->jenis_kayu)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->jenis_kayu as $id_jenis_kayu) {
                        $subq.="jenis_kayu = '".$id_jenis_kayu."'";
                        if ($cn < count($this->jenis_kayu)) {
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
                array_push($param['where'],"jenis_kayu = '".$this->jenis_kayu."'");
            }     
        }
        if(!empty($this->grade)){
            if (is_array($this->grade)) {
                if (isset($this->grade)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"grade = '".$this->grade."'");
            }     
        }
        if(!empty($this->glue)){
            if (is_array($this->glue)) {
                if (isset($this->glue)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"glue = '".$this->glue."'");
            }     
        }
        if(!empty($this->profil_kayu)){
            if (is_array($this->profil_kayu)) {
                if (isset($this->profil_kayu)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"profil_kayu = '".$this->profil_kayu."'");
            }     
        }
        if(!empty($this->kondisi_kayu)){
            if (is_array($this->kondisi_kayu)) {
                if (isset($this->kondisi_kayu)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"kondisi_kayu = '".$this->kondisi_kayu."'");
            }     
        }
		if(!empty($this->per_tanggal)){
			array_push($param['where'],"waktu_terima <= '".$this->per_tanggal." 23:59:59'");
		}
		return $param;
	}

    public function searchLaporanReturblmdikirimgudang(){
        $query = self::find();
        $query->select("
            t_retur_produk_detail.produk_id, 
            t_pengajuan_repacking.kode, 
            t_pengajuan_repacking.tanggal,
            m_brg_produk.produk_group, 
            m_brg_produk.produk_nama, 
	        m_brg_produk.produk_dimensi, 
            count(*) as palet, 
            sum(t_retur_produk_detail.qty_kecil) as qty_kecil, 
	        sum(t_retur_produk_detail.kubikasi) as kubikasi
        ");
        $query->innerJoin("t_retur_produk", "t_retur_produk.retur_produk_id = t_retur_produk_detail.retur_produk_id");
        $query->innerJoin("m_brg_produk", "t_retur_produk_detail.produk_id = m_brg_produk.produk_id");
        $query->innerJoin("t_pengajuan_repacking_detail", "t_pengajuan_repacking_detail.retur_produk_detail_id = t_retur_produk_detail.retur_produk_detail_id");
        $query->innerJoin("t_pengajuan_repacking", "t_pengajuan_repacking.pengajuan_repacking_id = t_pengajuan_repacking_detail.pengajuan_repacking_id");
        $query->groupBy("t_retur_produk_detail.produk_id,t_pengajuan_repacking.kode, t_pengajuan_repacking.tanggal,m_brg_produk.produk_group, m_brg_produk.produk_nama, m_brg_produk.produk_dimensi");
        $query->orderBy( 
            !empty($_GET['sort']['col'])
            ? SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir'])
            : 't_pengajuan_repacking.kode ASC' 
        );
        $query->andWhere("  keperluan = 'Penanganan Barang Retur' AND t_pengajuan_repacking.approval_status = 'APPROVED' AND 
                            nomor_produksi IS not null AND NOT EXISTS (SELECT nomor_produksi FROM t_mutasi_keluar a 
                            WHERE a.nomor_produksi = t_retur_produk_detail.nomor_produksi)");
        if(!empty($this->gudang_id)){
			$query->andWhere("t_retur_produk_detail.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->produk_nama)){
			$query->andWhere("produk_nama ILIKE '%".$this->produk_nama."%'");
		}
		if(!empty($this->produk_group)){
			$query->andWhere("produk_group ILIKE '%".$this->produk_group."%'");
		}
        if(!empty($this->jenis_kayu)){
            if (is_array($this->jenis_kayu)) {
                if (isset($this->jenis_kayu)) {
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
                }
            }else{
                $query->andWhere("jenis_kayu = '".$this->jenis_kayu."'");
            }            
        }
        if(!empty($this->grade)){
            if (is_array($this->grade)) {
                if (isset($this->grade)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("grade = '".$this->grade."'");
            }            
        }
        if(!empty($this->glue)){
            if (is_array($this->glue)) {
                if (isset($this->glue)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("glue = '".$this->glue."'");
            }            
        }
        if(!empty($this->profil_kayu)){
            if (is_array($this->profil_kayu)) {
                if (isset($this->profil_kayu)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("profil_kayu = '".$this->profil_kayu."'");
            }            
        }
        if(!empty($this->kondisi_kayu)){
            if (is_array($this->kondisi_kayu)) {
                if (isset($this->kondisi_kayu)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("kondisi_kayu = '".$this->kondisi_kayu."'");
            }            
        }
		if(!empty($this->tgl_awal)){
            $query->andWhere("t_pengajuan_repacking.tanggal between '" . $this->tgl_awal . "' and '" . $this->tgl_akhir ."'");
			// $query->andWhere("waktu_terima <= '".$this->per_tanggal." 23:59:59'");
		}
		return $query;
    }

    public function searchLaporanReturblmdikirimgudangDt() {
		$searchLaporan = $this->searchLaporanReturblmdikirimgudang();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
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
		
		// $param['where'] = ["nomor_produksi IS not null AND t_retur_produk.status <> 'SUDAH DITERIMA'"];
        $param['where'] = ["keperluan = 'Penanganan Barang Retur' AND t_pengajuan_repacking.approval_status = 'APPROVED' AND 
                            nomor_produksi IS not null AND NOT EXISTS (SELECT nomor_produksi FROM t_mutasi_keluar a 
                            WHERE a.nomor_produksi = t_retur_produk_detail.nomor_produksi)"];
		if(!empty($this->gudang_id)){
			array_push($param['where'],"t_retur_produk_detail.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->produk_group)){
			array_push($param['where'],"produk_group ILIKE '%".$this->produk_group."%'");
		}
        if(!empty($this->produk_nama)){
			array_push($param['where'],"produk_nama ILIKE '%".$this->produk_nama."%'");
		}
        if(!empty($this->jenis_kayu)){
            if (is_array($this->jenis_kayu)) {
                if (isset($this->jenis_kayu)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->jenis_kayu as $id_jenis_kayu) {
                        $subq.="jenis_kayu = '".$id_jenis_kayu."'";
                        if ($cn < count($this->jenis_kayu)) {
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
                array_push($param['where'],"jenis_kayu = '".$this->jenis_kayu."'");
            }     
        }
        if(!empty($this->grade)){
            if (is_array($this->grade)) {
                if (isset($this->grade)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"grade = '".$this->grade."'");
            }     
        }
        if(!empty($this->glue)){
            if (is_array($this->glue)) {
                if (isset($this->glue)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"glue = '".$this->glue."'");
            }     
        }
        if(!empty($this->profil_kayu)){
            if (is_array($this->profil_kayu)) {
                if (isset($this->profil_kayu)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"profil_kayu = '".$this->profil_kayu."'");
            }     
        }
        if(!empty($this->kondisi_kayu)){
            if (is_array($this->kondisi_kayu)) {
                if (isset($this->kondisi_kayu)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"kondisi_kayu = '".$this->kondisi_kayu."'");
            }     
        }
		if(!empty($this->tgl_awal)){
            array_push($param['where'],"t_pengajuan_repacking.tanggal between '" . $this->tgl_awal . "' and '" . $this->tgl_akhir ."'");
			// array_push($param['where'],"waktu_terima <= '".$this->per_tanggal." 23:59:59'");
		}
		return $param;
	}

    public function searchLaporanStockPalet(){
        $query = self::find();
        $query->select("
            t_retur_produk_detail.produk_id, 
            m_brg_produk.produk_group, 
            m_brg_produk.produk_nama, 
	        m_brg_produk.produk_dimensi, 
            nomor_produksi, 
            sum(t_retur_produk_detail.qty_kecil) as qty_kecil, 
	        sum(t_retur_produk_detail.kubikasi) as kubikasi
        ");
        $query->innerJoin("t_retur_produk", "t_retur_produk.retur_produk_id = t_retur_produk_detail.retur_produk_id");
        $query->innerJoin("m_brg_produk", "t_retur_produk_detail.produk_id = m_brg_produk.produk_id");
        $query->groupBy("t_retur_produk_detail.produk_id,m_brg_produk.produk_group, m_brg_produk.produk_nama, m_brg_produk.produk_dimensi,nomor_produksi");
        $query->orderBy( 
            !empty($_GET['sort']['col'])
            ? SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir'])
            : self::tableName().'.produk_id ASC' 
        );
        $query->andWhere("nomor_produksi IS not null AND NOT EXISTS (SELECT nomor_produksi FROM t_mutasi_keluar mk 
                                WHERE mk.nomor_produksi = t_retur_produk_detail.nomor_produksi)");
        if(!empty($this->gudang_id)){
			$query->andWhere("t_retur_produk_detail.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->produk_nama)){
			$query->andWhere("produk_nama ILIKE '%".$this->produk_nama."%'");
		}
		if(!empty($this->produk_group)){
			$query->andWhere("produk_group ILIKE '%".$this->produk_group."%'");
		}
        if(!empty($this->jenis_kayu)){
            if (is_array($this->jenis_kayu)) {
                if (isset($this->jenis_kayu)) {
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
                }
            }else{
                $query->andWhere("jenis_kayu = '".$this->jenis_kayu."'");
            }            
        }
        if(!empty($this->grade)){
            if (is_array($this->grade)) {
                if (isset($this->grade)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("grade = '".$this->grade."'");
            }            
        }
        if(!empty($this->glue)){
            if (is_array($this->glue)) {
                if (isset($this->glue)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("glue = '".$this->glue."'");
            }            
        }
        if(!empty($this->profil_kayu)){
            if (is_array($this->profil_kayu)) {
                if (isset($this->profil_kayu)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("profil_kayu = '".$this->profil_kayu."'");
            }            
        }
        if(!empty($this->kondisi_kayu)){
            if (is_array($this->kondisi_kayu)) {
                if (isset($this->kondisi_kayu)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("kondisi_kayu = '".$this->kondisi_kayu."'");
            }            
        }
		if(!empty($this->per_tanggal)){
			$query->andWhere("waktu_terima <= '".$this->per_tanggal." 23:59:59'");
		}
		return $query;
    }

    public function searchLaporanStockPaletDt() {
		$searchLaporan = $this->searchLaporanStockPalet();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
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
		
		$param['where'] = ["nomor_produksi IS not null AND NOT EXISTS (SELECT nomor_produksi FROM t_mutasi_keluar mk 
                                    WHERE mk.nomor_produksi = t_retur_produk_detail.nomor_produksi)"];
		if(!empty($this->gudang_id)){
			array_push($param['where'],"t_retur_produk_detail.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->produk_group)){
			array_push($param['where'],"produk_group ILIKE '%".$this->produk_group."%'");
		}
        if(!empty($this->produk_nama)){
			array_push($param['where'],"produk_nama ILIKE '%".$this->produk_nama."%'");
		}
        if(!empty($this->jenis_kayu)){
            if (is_array($this->jenis_kayu)) {
                if (isset($this->jenis_kayu)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->jenis_kayu as $id_jenis_kayu) {
                        $subq.="jenis_kayu = '".$id_jenis_kayu."'";
                        if ($cn < count($this->jenis_kayu)) {
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
                array_push($param['where'],"jenis_kayu = '".$this->jenis_kayu."'");
            }     
        }
        if(!empty($this->grade)){
            if (is_array($this->grade)) {
                if (isset($this->grade)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"grade = '".$this->grade."'");
            }     
        }
        if(!empty($this->glue)){
            if (is_array($this->glue)) {
                if (isset($this->glue)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"glue = '".$this->glue."'");
            }     
        }
        if(!empty($this->profil_kayu)){
            if (is_array($this->profil_kayu)) {
                if (isset($this->profil_kayu)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"profil_kayu = '".$this->profil_kayu."'");
            }     
        }
        if(!empty($this->kondisi_kayu)){
            if (is_array($this->kondisi_kayu)) {
                if (isset($this->kondisi_kayu)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"kondisi_kayu = '".$this->kondisi_kayu."'");
            }     
        }
		if(!empty($this->per_tanggal)){
			array_push($param['where'],"waktu_terima <= '".$this->per_tanggal." 23:59:59'");
		}
		return $param;
	}

    public function searchLaporanPenerimaanRetur(){
        $query = self::find();
        $query->select("
            t_retur_produk.retur_produk_id, 
            t_retur_produk.kode, 
            t_retur_produk.tanggal, 
            m_customer.cust_an_nama, 
            produk_group,
            produk_nama, 
            produk_dimensi,
            qty_kecil, 
            kubikasi, 
            t_retur_produk.alasan_retur, 
            penerima.pegawai_nama AS petugas_penerima, 
            waktu_terima, 
            security.pegawai_nama AS diperiksa_security, 
            status,
            nomor_produksi
        ");
        $query->innerJoin("t_retur_produk", "t_retur_produk.retur_produk_id = t_retur_produk_detail.retur_produk_id");
        $query->innerJoin("m_brg_produk", "t_retur_produk_detail.produk_id = m_brg_produk.produk_id");
        $query->innerJoin("m_customer", "m_customer.cust_id = t_retur_produk.cust_id");
        $query->leftJoin("m_pegawai AS penerima", "penerima.pegawai_id = t_retur_produk.petugas_penerima");
        $query->leftJoin("m_pegawai AS security", "security.pegawai_id = t_retur_produk.diperiksa_security");
        $query->orderBy( 
            !empty($_GET['sort']['col'])
            ? SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir'])
            : self::tableName().'.produk_id ASC' 
        );
        $query->andWhere("cancel_transaksi_id IS NULL");
        if(!empty($this->gudang_id)){
			$query->andWhere("t_retur_produk_detail.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->produk_nama)){
			$query->andWhere("produk_nama ILIKE '%".$this->produk_nama."%'");
		}
		if(!empty($this->produk_group)){
			$query->andWhere("produk_group ILIKE '%".$this->produk_group."%'");
		}
        if(!empty($this->jenis_kayu)){
            if (is_array($this->jenis_kayu)) {
                if (isset($this->jenis_kayu)) {
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
                }
            }else{
                $query->andWhere("jenis_kayu = '".$this->jenis_kayu."'");
            }            
        }
        if(!empty($this->grade)){
            if (is_array($this->grade)) {
                if (isset($this->grade)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("grade = '".$this->grade."'");
            }            
        }
        if(!empty($this->glue)){
            if (is_array($this->glue)) {
                if (isset($this->glue)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("glue = '".$this->glue."'");
            }            
        }
        if(!empty($this->profil_kayu)){
            if (is_array($this->profil_kayu)) {
                if (isset($this->profil_kayu)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("profil_kayu = '".$this->profil_kayu."'");
            }            
        }
        if(!empty($this->kondisi_kayu)){
            if (is_array($this->kondisi_kayu)) {
                if (isset($this->kondisi_kayu)) {
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
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("kondisi_kayu = '".$this->kondisi_kayu."'");
            }            
        }
		if(!empty($this->tgl_awal)){
			$query->andWhere("t_retur_produk.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."'");
		}
        if(!empty($this->status)){
            if($this->status == 'BELUM DITERIMA'){
                $query->andWhere("status is null");
            } else {
                $query->andWhere("status = 'SUDAH DITERIMA'");
            }
		}
		return $query;
    }

    public function searchLaporanPenerimaanReturDt() {
		$searchLaporan = $this->searchLaporanPenerimaanRetur();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
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
		
		$param['where'] = ["cancel_transaksi_id IS NULL"];
		if(!empty($this->gudang_id)){
			array_push($param['where'],"t_retur_produk_detail.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->produk_group)){
			array_push($param['where'],"produk_group ILIKE '%".$this->produk_group."%'");
		}
        if(!empty($this->produk_nama)){
			array_push($param['where'],"produk_nama ILIKE '%".$this->produk_nama."%'");
		}
        if(!empty($this->jenis_kayu)){
            if (is_array($this->jenis_kayu)) {
                if (isset($this->jenis_kayu)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->jenis_kayu as $id_jenis_kayu) {
                        $subq.="jenis_kayu = '".$id_jenis_kayu."'";
                        if ($cn < count($this->jenis_kayu)) {
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
                array_push($param['where'],"jenis_kayu = '".$this->jenis_kayu."'");
            }     
        }
        if(!empty($this->grade)){
            if (is_array($this->grade)) {
                if (isset($this->grade)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"grade = '".$this->grade."'");
            }     
        }
        if(!empty($this->glue)){
            if (is_array($this->glue)) {
                if (isset($this->glue)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"glue = '".$this->glue."'");
            }     
        }
        if(!empty($this->profil_kayu)){
            if (is_array($this->profil_kayu)) {
                if (isset($this->profil_kayu)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"profil_kayu = '".$this->profil_kayu."'");
            }     
        }
        if(!empty($this->kondisi_kayu)){
            if (is_array($this->kondisi_kayu)) {
                if (isset($this->kondisi_kayu)) {
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
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"kondisi_kayu = '".$this->kondisi_kayu."'");
            }     
        }
		if(!empty($this->tgl_awal)){
			array_push($param['where'],"t_retur_produk.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."'");
		}
        if(!empty($this->status)){
            if($this->status == 'BELUM DITERIMA'){
                array_push($param['where'],"status is null");
            } else {
                array_push($param['where'],"status = 'SUDAH DITERIMA'");
            }
		}
		return $param;
	}
}
