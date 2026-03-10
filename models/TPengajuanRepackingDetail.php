<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengajuan_repacking_detail".
 *
 * @property integer $pengajuan_repacking_detail_id
 * @property integer $pengajuan_repacking_id
 * @property integer $produk_id
 * @property double $qty_besar
 * @property string $keterangan
 * @property double $qty_kecil
 * @property integer $retur_produk_detail_id
 * @property double $kubikasi
 *
 * @property MBrgProduk $produk
 * @property TPengajuanRepacking $pengajuanRepacking
 */
class TPengajuanRepackingDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $jenis_produk,$produk_nama,$produk_dimensi,$lokasi_gudang,$qty_stock;
    public $produk_group, $tgl_awal, $tgl_akhir, $jenis_kayu, $grade, $glue, $profil_kayu, $kondisi_kayu, $gudang_id;
    public static function tableName()
    {
        return 't_pengajuan_repacking_detail';
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
            [['pengajuan_repacking_id', 'produk_id'], 'required'],
            [['pengajuan_repacking_id', 'produk_id', 'retur_produk_detail_id'], 'integer'],
            [['qty_besar', 'qty_kecil', 'kubikasi'], 'number'],
            [['keterangan'], 'string'],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['pengajuan_repacking_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPengajuanRepacking::className(), 'targetAttribute' => ['pengajuan_repacking_id' => 'pengajuan_repacking_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengajuan_repacking_detail_id' => 'Pengajuan Repacking Detail',
                'pengajuan_repacking_id' => 'Pengajuan Repacking',
                'produk_id' => 'Produk',
                'qty_besar' => 'Qty Kecil',
                'keterangan' => 'Keterangan',
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
    public function getPengajuanRepacking()
    {
        return $this->hasOne(TPengajuanRepacking::className(), ['pengajuan_repacking_id' => 'pengajuan_repacking_id']);
    }

    public function searchLaporan(){
        $query = self::find();
        $query->select("
            t_pengajuan_repacking_detail.produk_id, 
            t_pengajuan_repacking.kode,
            t_pengajuan_repacking.tanggal,
            m_brg_produk.produk_group, 
            m_brg_produk.produk_nama, 
	        m_brg_produk.produk_dimensi, 
            count(*) as palet, 
            sum(t_retur_produk_detail.qty_kecil) as qty_kecil, 
	        sum(t_retur_produk_detail.kubikasi) as kubikasi
        ");
        $query->innerJoin("t_pengajuan_repacking", "t_pengajuan_repacking.pengajuan_repacking_id = t_pengajuan_repacking_detail.pengajuan_repacking_id");
        $query->innerJoin("t_retur_produk_detail", "t_retur_produk_detail.retur_produk_detail_id = t_pengajuan_repacking_detail.retur_produk_detail_id");
        $query->innerJoin("m_brg_produk", "t_retur_produk_detail.produk_id = m_brg_produk.produk_id");
        $query->groupBy("t_pengajuan_repacking_detail.produk_id,t_pengajuan_repacking.kode, t_pengajuan_repacking.tanggal,m_brg_produk.produk_group, m_brg_produk.produk_nama, m_brg_produk.produk_dimensi");
        $query->orderBy( 
            !empty($_GET['sort']['col'])
            ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir'])
            : self::tableName().'.produk_id ASC' 
        );
        $query->andWhere("keperluan = 'Penanganan Barang Retur' AND
                            exists (SELECT nomor_produksi FROM t_mutasi_keluar a WHERE a.nomor_produksi = t_retur_produk_detail.nomor_produksi) AND
                            not exists (select nomor_produksi from t_terima_mutasi 
                            where t_terima_mutasi.nomor_produksi = t_retur_produk_detail.nomor_produksi)");
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
		}
		return $query;
    }

    public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
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
		
		$param['where'] = ["keperluan = 'Penanganan Barang Retur' AND
                            exists (SELECT nomor_produksi FROM t_mutasi_keluar a WHERE a.nomor_produksi = t_retur_produk_detail.nomor_produksi) AND
                            not exists (select nomor_produksi from t_terima_mutasi 
                            where t_terima_mutasi.nomor_produksi = t_retur_produk_detail.nomor_produksi)"];
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
		}
		return $param;
	}

    /**public function searchLaporanA(){
        $query = self::find();
        $query->select("t_pengajuan_repacking_detail.produk_id,
            t_pengajuan_repacking.kode, 
            t_pengajuan_repacking.tanggal, 
            m_brg_produk.produk_group, 
            m_brg_produk.produk_nama, 
	        m_brg_produk.produk_dimensi, 
            count(*) as palet, 
            sum(t_retur_produk_detail.qty_kecil) as qty_kecil, 
	        sum(t_retur_produk_detail.kubikasi) as kubikasi
        ");
        $query->innerJoin("t_pengajuan_repacking", "t_pengajuan_repacking.pengajuan_repacking_id = t_pengajuan_repacking_detail.pengajuan_repacking_id");
        $query->innerJoin("t_retur_produk_detail", "t_retur_produk_detail.retur_produk_detail_id = t_pengajuan_repacking_detail.retur_produk_detail_id");
        $query->innerJoin("m_brg_produk", "t_retur_produk_detail.produk_id = m_brg_produk.produk_id");
        $query->groupBy("t_pengajuan_repacking_detail.produk_id, t_pengajuan_repacking.kode, t_pengajuan_repacking.tanggal,m_brg_produk.produk_group, m_brg_produk.produk_nama, m_brg_produk.produk_dimensi");
        $query->orderBy( 
            !empty($_GET['sort']['col'])
            ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir'])
            : 't_pengajuan_repacking.kode ASC' 
        );
        $query->andWhere("keperluan = 'Penanganan Barang Retur' AND t_pengajuan_repacking.status = 'SEDANG DIAJUKAN' 
                            AND t_pengajuan_repacking.approval_status = 'Not Confirmed'");
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
		}
		return $query;
    }

    public function searchLaporanADt() {
		$searchLaporan = $this->searchLaporanA();
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
		
		$param['where'] = ["keperluan = 'Penanganan Barang Retur' AND t_pengajuan_repacking.status = 'SEDANG DIAJUKAN'
                            AND t_pengajuan_repacking.approval_status = 'Not Confirmed'"];
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
		}
		return $param;
	}*/
}
