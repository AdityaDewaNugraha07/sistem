<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_persediaan_produk".
 *
 * @property integer $persediaan_produk_id
 * @property integer $produk_id
 * @property string $nomor_produksi
 * @property string $tgl_transaksi
 * @property integer $gudang_id
 * @property string $reff_no
 * @property integer $reff_detail_id
 * @property integer $in_qty_palet
 * @property double $in_qty_kecil
 * @property string $in_qty_kecil_satuan
 * @property double $in_qty_m3
 * @property integer $out_qty_palet
 * @property double $out_qty_kecil
 * @property string $out_qty_kecil_satuan
 * @property double $out_qty_m3
 * @property string $keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MBrgProduk $produk
 * @property MGudang $gudang
 * @property TProduksi $nomorProduksi
 * @property array $jenis_kayu
 * @property array $grade
 * @property array $glue
 * @property array $profil_kayu
 * @property array $kondisi_kayu
 */
class HPersediaanProduk extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $produk_nama,$produk_group,$jenis_kayu,$grade,$glue,$profil_kayu,$kondisi_kayu;
	public $tgl_awal,$tgl_akhir,$penerima,$cara_keluar,$per_tanggal,$pengajuan_repacking_id;
    public static function tableName()
    {
        return 'h_persediaan_produk';
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
            [['produk_id', 'nomor_produksi', 'tgl_transaksi', 'gudang_id', 'reff_no', 'in_qty_kecil_satuan', 'out_qty_kecil_satuan', 'keterangan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['produk_id', 'gudang_id', 'reff_detail_id', 'in_qty_palet', 'out_qty_palet', 'created_by', 'updated_by'], 'integer'],
            [['tgl_transaksi', 'created_at', 'updated_at', 'produk_nama', 'produk_group', 'jenis_kayu', 'grade', 'glue', 'profil_kayu', 'kondisi_kayu', 'per_tanggal'], 'safe'],
            [['in_qty_kecil', 'in_qty_m3', 'out_qty_kecil', 'out_qty_m3'], 'number'],
            [['keterangan'], 'string'],
            [['active'], 'boolean'],
            [['nomor_produksi', 'reff_no', 'in_qty_kecil_satuan', 'out_qty_kecil_satuan'], 'string', 'max' => 50],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['gudang_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGudang::className(), 'targetAttribute' => ['gudang_id' => 'gudang_id']],
            [['nomor_produksi'], 'exist', 'skipOnError' => true, 'targetClass' => TProduksi::className(), 'targetAttribute' => ['nomor_produksi' => 'nomor_produksi']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'persediaan_produk_id' => Yii::t('app', 'Persediaan Produk'),
                'produk_id' => Yii::t('app', 'Produk'),
				'nomor_produksi' => Yii::t('app', 'Nomor Produksi'),
                'tgl_transaksi' => Yii::t('app', 'Tgl Transaksi'),
                'gudang_id' => Yii::t('app', 'Gudang'),
                'reff_no' => Yii::t('app', 'Reff No'),
                'reff_detail_id' => Yii::t('app', 'Reff Detail'),
                'in_qty_palet' => Yii::t('app', 'In Qty Palet'),
                'in_qty_kecil' => Yii::t('app', 'In Qty Kecil'),
                'in_qty_kecil_satuan' => Yii::t('app', 'In Qty Kecil Satuan'),
                'in_qty_m3' => Yii::t('app', 'In Qty M3'),
                'out_qty_palet' => Yii::t('app', 'Out Qty Palet'),
                'out_qty_kecil' => Yii::t('app', 'Out Qty Kecil'),
                'out_qty_kecil_satuan' => Yii::t('app', 'Out Qty Kecil Satuan'),
                'out_qty_m3' => Yii::t('app', 'Out Qty M3'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
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
    public function getGudang()
    {
        return $this->hasOne(MGudang::className(), ['gudang_id' => 'gudang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNomorProduksi()
    {
        return $this->hasOne(TProduksi::className(), ['nomor_produksi' => 'nomor_produksi']);
    } 
	
	public static function updateStokPersediaan($model){
        if($model->validate()){
            if($model->save()){
                return true;
            }else{
                return false;
            }
		}
    }
	
	public static function getCurrentStockPerProduk($produk_id){
		$sql = "SELECT produk_id, 
					SUM(in_qty_palet-out_qty_palet) AS palet, 
					SUM(in_qty_kecil-out_qty_kecil) AS qty_kecil, 
					in_qty_kecil_satuan, 
					SUM(in_qty_m3-out_qty_m3) as kubikasi 
				FROM h_persediaan_produk 
				WHERE produk_id = $produk_id
				GROUP BY produk_id, in_qty_kecil_satuan";
		$mod = \Yii::$app->db->createCommand($sql)->queryOne();
		return $mod;
	}
	
	public static function getCurrentStockPerPalet($nomor_produksi){
		$sql = "SELECT produk_id, nomor_produksi, gudang_id, SUM(in_qty_palet-out_qty_palet) AS palet, SUM(in_qty_kecil-out_qty_kecil) AS qty_kecil, in_qty_kecil_satuan, SUM(in_qty_m3-out_qty_m3) as kubikasi 
				FROM h_persediaan_produk 
				WHERE nomor_produksi = '{$nomor_produksi}' and keterangan NOT ILIKE '%MUTASI DARI GUDANG%'
				GROUP BY produk_id, nomor_produksi, gudang_id, in_qty_kecil_satuan";
		$mod = \Yii::$app->db->createCommand($sql)->queryOne();
		return $mod;
	}
	
	public static function getCurrentStock(){
		$sql = "SELECT produk_id, SUM(in_qty_palet-out_qty_palet) AS palet, SUM(in_qty_kecil-out_qty_kecil) AS qty_kecil, in_qty_kecil_satuan, SUM(in_qty_m3-out_qty_m3) as kubikasi, gudang_id FROM h_persediaan_produk 
				GROUP BY produk_id, in_qty_kecil_satuan, gudang_id";
		$mod = \Yii::$app->db->createCommand($sql)->queryAll();
		echo "<pre>";
		print_r($mod);
		exit;
	}
	
	public static function getDataByNomorProduksi($nomor_produksi){
		$sql = "SELECT produk_id, nomor_produksi, gudang_id, 
					SUM(in_qty_palet-out_qty_palet) AS qty_palet, 
					SUM(in_qty_kecil-out_qty_kecil) AS qty_kecil, 
					in_qty_kecil_satuan AS satuan_kecil,
					SUM(in_qty_m3-out_qty_m3) AS kubikasi  
				FROM h_persediaan_produk 
				WHERE nomor_produksi = '$nomor_produksi'
				GROUP BY produk_id, nomor_produksi, gudang_id, in_qty_kecil_satuan
				HAVING SUM(in_qty_palet-out_qty_palet) > 0";
		$mod = \Yii::$app->db->createCommand($sql)->queryOne();
		return $mod;
	}	
	
	public function searchLaporanPalet() {
		$query = self::find();

		$query->select("h_persediaan_produk.produk_id, 
							m_brg_produk.produk_group, 
							m_brg_produk.produk_kode, 
							m_brg_produk.produk_nama, 
							m_brg_produk.produk_dimensi, 
							h_persediaan_produk.nomor_produksi, 
							m_gudang.gudang_nm, 
							sum(in_qty_kecil-out_qty_kecil) AS qty_kecil, 
							in_qty_kecil_satuan, 
							sum(in_qty_m3-out_qty_m3) AS kubikasi,
							t_terima_ko.tbko_id,
                            (select count(*) from t_terima_ko_kd where t_terima_ko_kd.tbko_id = t_terima_ko.tbko_id) as tot,
                            ((current_date - t_terima_ko.tanggal) * 1) as hari,
                            t_terima_ko.tanggal,
                            ((current_date - t_terima_ko.tanggal_produksi) * 1) as harii,
                            t_terima_ko.tanggal_produksi,
                            sum(in_qty_palet-out_qty_palet) AS palet, 
						");
		$query->join('JOIN', "m_brg_produk","m_brg_produk.produk_id = h_persediaan_produk.produk_id");
		$query->join('JOIN', "m_gudang","m_gudang.gudang_id = h_persediaan_produk.gudang_id");
		$query->join('JOIN', "t_terima_ko", "t_terima_ko.nomor_produksi = h_persediaan_produk.nomor_produksi");
		$query->groupBy("h_persediaan_produk.produk_id, m_brg_produk.produk_group, m_brg_produk.produk_kode, m_brg_produk.produk_nama, 
							m_brg_produk.produk_dimensi, h_persediaan_produk.nomor_produksi, m_gudang.gudang_nm, in_qty_kecil_satuan, 
							t_terima_ko.tbko_id ");
		$query->having("SUM(in_qty_palet-out_qty_palet) > 0");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.produk_id ASC' );
		if(!empty($this->gudang_id)){
			$query->andWhere("h_persediaan_produk.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->nomor_produksi)){
			$query->andWhere("h_persediaan_produk.nomor_produksi ILIKE '%".$this->nomor_produksi."%'");
		}
		if(!empty($this->produk_group)){
			$query->andWhere("produk_group = '".$this->produk_group."'");
		}
		if(!empty($this->produk_nama)){
			$query->andWhere("produk_nama ILIKE '%".$this->produk_nama."%'");
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
                }
            }else{
                $query->andWhere("kondisi_kayu = '".$this->kondisi_kayu."'");
            }            
        }
		if(!empty($this->per_tanggal)){
			$query->andWhere("tgl_transaksi <= '".$this->per_tanggal."'");
		}
		return $query;
	}
	
	public function searchLaporanPaletDt() {
		$searchLaporan = $this->searchLaporanPalet();
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
		if(!empty($searchLaporan->having)){
			$param['having'] = "HAVING ".$searchLaporan->having;
		}
		
		$param['where'] = [];
		if(!empty($this->gudang_id)){
			array_push($param['where'],"h_persediaan_produk.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->nomor_produksi)){
			array_push($param['where'],"h_persediaan_produk.nomor_produksi ILIKE '%".$this->nomor_produksi."%'");
		}
		if(!empty($this->produk_group)){
			array_push($param['where'],"produk_group = '".$this->produk_group."'");
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
                        $subq.="jenis_kayu = '".$id_jenis_kayu."' ";
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
			array_push($param['where'],"tgl_transaksi <= '".$this->per_tanggal."'");
		}
		return $param;
	}
	
	public function searchLaporanPalets() {
		$query = self::find();

		$query->select("h_persediaan_produk.produk_id, 
							m_brg_produk.produk_group, 
							m_brg_produk.produk_kode, 
							m_brg_produk.produk_nama, 
							m_brg_produk.produk_dimensi, 
							h_persediaan_produk.nomor_produksi, 
							m_gudang.gudang_nm, 
							sum(in_qty_kecil-out_qty_kecil) AS qty_kecil, 
							in_qty_kecil_satuan, 
							sum(in_qty_m3-out_qty_m3) AS kubikasi,
							t_terima_ko.tbko_id,
                            (select count(*) from t_terima_ko_kd where t_terima_ko_kd.tbko_id = t_terima_ko.tbko_id) as tot,
                            ((current_date - t_terima_ko.tanggal) * 1) as hari,
                            t_terima_ko.tanggal,
                            ((current_date - t_terima_ko.tanggal_produksi) * 1) as harii,
                            t_terima_ko.tanggal_produksi,
						");
		$query->join('JOIN', "m_brg_produk","m_brg_produk.produk_id = h_persediaan_produk.produk_id");
		$query->join('JOIN', "m_gudang","m_gudang.gudang_id = h_persediaan_produk.gudang_id");
		$query->join('JOIN', "t_terima_ko", "t_terima_ko.nomor_produksi = h_persediaan_produk.nomor_produksi");
		//$query->join('JOIN', "t_terima_ko_kd", "t_terima_ko_kd.tbko_id = t_terima_ko.tbko_id");
		$query->groupBy("h_persediaan_produk.produk_id, m_brg_produk.produk_group, m_brg_produk.produk_kode, m_brg_produk.produk_nama, 
							m_brg_produk.produk_dimensi, h_persediaan_produk.nomor_produksi, m_gudang.gudang_nm, in_qty_kecil_satuan, 
							t_terima_ko.tbko_id ");
		$query->having("SUM(in_qty_palet-out_qty_palet) > 0");
		// $query->andWhere("h_persediaan_produk.keterangan NOT ILIKE '%MUTASI DARI GUDANG%'");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.produk_id ASC' );
		if(!empty($this->gudang_id)){
			$query->andWhere("h_persediaan_produk.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->nomor_produksi)){
			$query->andWhere("h_persediaan_produk.nomor_produksi ILIKE '%".$this->nomor_produksi."%'");
		}
		if(!empty($this->produk_group)){
			$query->andWhere("produk_group = '".$this->produk_group."'");
		}
		if(!empty($this->produk_nama)){
			$query->andWhere("produk_nama ILIKE '%".$this->produk_nama."%'");
		}
		/*if(!empty($this->jenis_kayu)){
			$query->andWhere("jenis_kayu = '".$this->jenis_kayu."'");
		}
		if(!empty($this->grade)){
			$query->andWhere("grade = '".$this->grade."'");
		}
		if(!empty($this->glue)){
			$query->andWhere("glue = '".$this->glue."'");
		}
		if(!empty($this->profil_kayu)){
			$query->andWhere("profil_kayu = '".$this->profil_kayu."'");
		}
		if(!empty($this->kondisi_kayu)){
			$query->andWhere("kondisi_kayu = '".$this->kondisi_kayu."'");
		}*/
		
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
			$query->andWhere("tgl_transaksi <= '".$this->per_tanggal."'");
		}
		return $query;
	}
	
	public function searchLaporanPaletDts() {
		$searchLaporan = $this->searchLaporanPalets();
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
		if(!empty($searchLaporan->having)){
			$param['having'] = "HAVING ".$searchLaporan->having;
		}
		
		$param['where'] = [];
		if(!empty($this->gudang_id)){
			array_push($param['where'],"h_persediaan_produk.gudang_id = ".$this->gudang_id);
		}
		if(!empty($this->nomor_produksi)){
			array_push($param['where'],"h_persediaan_produk.nomor_produksi ILIKE '%".$this->nomor_produksi."%'");
		}
		if(!empty($this->produk_group)){
			array_push($param['where'],"produk_group = '".$this->produk_group."'");
		}
		if(!empty($this->produk_nama)){
			array_push($param['where'],"produk_nama ILIKE '%".$this->produk_nama."%'");
		}
		if(!empty($this->jenis_kayu)){
			array_push($param['where'],"jenis_kayu = '".$this->jenis_kayu."'");
		}
		if(!empty($this->grade)){
			array_push($param['where'],"grade = '".$this->grade."'");
		}
		if(!empty($this->glue)){
			array_push($param['where'],"glue = '".$this->glue."'");
		}
		if(!empty($this->profil_kayu)){
			array_push($param['where'],"profil_kayu = '".$this->profil_kayu."'");
		}
		if(!empty($this->kondisi_kayu)){
			array_push($param['where'],"kondisi_kayu = '".$this->kondisi_kayu."'");
		}
		if(!empty($this->per_tanggal)){
			array_push($param['where'],"tgl_transaksi <= '".$this->per_tanggal."'");
		}
		return $param;
	}

	public function searchLaporan() {
		$query = self::find();
		$query->select(self::tableName().'.produk_id, 
            m_brg_produk.produk_group, 
            m_brg_produk.produk_kode, 
            m_brg_produk.produk_nama, 
            m_brg_produk.produk_dimensi, 
            sum(in_qty_palet-out_qty_palet) AS palet, 
            sum(in_qty_kecil-out_qty_kecil) AS qty_kecil, 
            in_qty_kecil_satuan, 
            sum(in_qty_m3-out_qty_m3) AS kubikasi, 
            m_brg_produk.jenis_kayu,
            m_brg_produk.grade,
            m_brg_produk.glue,
            m_brg_produk.profil_kayu,
            m_brg_produk.kondisi_kayu');
		$query->join('JOIN', "m_brg_produk","m_brg_produk.produk_id = h_persediaan_produk.produk_id");
//		$query->join('JOIN', "m_gudang","m_gudang.gudang_id = h_persediaan_produk.gudang_id");
		$query->groupBy(self::tableName().".produk_id,
		    m_brg_produk.produk_group,
		    m_brg_produk.produk_kode,
		    m_brg_produk.produk_nama,
		    produk_dimensi,
		    in_qty_kecil_satuan,
		    m_brg_produk.jenis_kayu,
		    m_brg_produk.grade,
            m_brg_produk.glue,
            m_brg_produk.profil_kayu,
            m_brg_produk.kondisi_kayu");
		$query->having("SUM(in_qty_palet-out_qty_palet) > 0");
        $query->orderBy( !empty($_GET['sort']['col']) ? 
            \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']) : 
			self::tableName().'.produk_id ASC' );
		if(!empty($this->gudang_id)){
			$query->andWhere("h_persediaan_produk.gudang_id = ".$this->gudang_id);
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
			$query->andWhere("tgl_transaksi <= '".$this->per_tanggal."'");
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
		if(!empty($searchLaporan->having)){
			$param['having'] = "HAVING ".$searchLaporan->having;
		}
		
		$param['where'] = [];
		if(!empty($this->gudang_id)){
			array_push($param['where'],"h_persediaan_produk.gudang_id = ".$this->gudang_id);
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
			array_push($param['where'],"tgl_transaksi <= '".$this->per_tanggal."'");
		}
		return $param;
	}
    
    public function searchLaporanProdukKeluar() {
		$query = self::find();
		$query->select("h_persediaan_produk.produk_id,
						h_persediaan_produk.nomor_produksi, 
						produk_nama, 
						tgl_transaksi, 
						h_persediaan_produk.reff_no, 
						SUM(out_qty_kecil) AS pcs, 
						SUM(out_qty_m3) AS m3, 
						h_persediaan_produk.keterangan, 
						m_customer.cust_an_nama AS penerima,
                        m_brg_produk.produk_t,
                        m_brg_produk.produk_t_satuan,
                        m_brg_produk.produk_l,
                        m_brg_produk.produk_l_satuan,
                        m_brg_produk.produk_p,
                        m_brg_produk.produk_p_satuan,
                        m_brg_produk.jenis_kayu,
                        m_brg_produk.grade,
                        m_brg_produk.glue,
                        m_gudang.gudang_nm,
                        ");
		$query->join('LEFT JOIN', "t_produk_keluar","t_produk_keluar.nomor_produksi = h_persediaan_produk.nomor_produksi");
        $query->join('JOIN', "m_gudang","m_gudang.gudang_id = h_persediaan_produk.gudang_id");   
		$query->join('JOIN', "m_brg_produk","m_brg_produk.produk_id = h_persediaan_produk.produk_id");
		$query->join('LEFT JOIN', "t_spm_ko","t_spm_ko.kode = h_persediaan_produk.reff_no");
		$query->join('LEFT JOIN', "m_customer","m_customer.cust_id = t_spm_ko.cust_id");             
		$query->groupBy("h_persediaan_produk.produk_id, 
						h_persediaan_produk.nomor_produksi, 
						produk_nama, 
						tgl_transaksi, 
						h_persediaan_produk.reff_no, 
						h_persediaan_produk.keterangan, 
						m_customer.cust_an_nama,
                        m_brg_produk.produk_t,
                        m_brg_produk.produk_t_satuan,
                        m_brg_produk.produk_l,
                        m_brg_produk.produk_l_satuan,
                        m_brg_produk.produk_p,
                        m_brg_produk.produk_p_satuan,
                        m_brg_produk.jenis_kayu,
                        m_brg_produk.grade,
                        m_brg_produk.glue,
                        m_gudang.gudang_nm,
                        ");
		$query->andWhere("out_qty_palet != 0 AND h_persediaan_produk.keterangan NOT ILIKE '%MUTASI DARI GUDANG%'");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.tgl_transaksi DESC' );
		
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tgl_transaksi BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->nomor_produksi)){
			$query->andWhere("h_persediaan_produk.nomor_produksi ILIKE '%".$this->nomor_produksi."%'");
		}
		if(!empty($this->reff_no)){
			$query->andWhere("h_persediaan_produk.reff_no ILIKE '%".$this->reff_no."%'");
		}
		if(!empty($this->penerima)){
			$query->andWhere("penerima ILIKE '%".$this->penerima."%'");
		}
		if(!empty($this->produk_nama)){
			$query->andWhere("produk_nama ILIKE '%".$this->produk_nama."%'");
		}
		if(!empty($this->cara_keluar)){
			if($this->cara_keluar=="SPM Lokal"){
				$query->andWhere("h_persediaan_produk.keterangan ILIKE 'PENJUALAN'");
			}else if($this->cara_keluar=="Export"){
				$query->andWhere("h_persediaan_produk.keterangan ILIKE 'MUTASI KELUAR UNTUK Export'");
			}else if($this->cara_keluar=="Mutasi Ke Produksi"){
				$query->andWhere("h_persediaan_produk.keterangan ILIKE 'MUTASI KELUAR UNTUK Kembali Produksi'");
			}else if($this->cara_keluar=="Mutasi Kebutuhan Internal"){
				$query->andWhere("h_persediaan_produk.keterangan ILIKE 'MUTASI KELUAR UNTUK Kebutuhan Internal'");
			}else{
				$query->andWhere("(h_persediaan_produk.keterangan NOT ILIKE 'PENJUALAN' AND
								  h_persediaan_produk.keterangan NOT ILIKE 'MUTASI KELUAR UNTUK Export' AND
								  h_persediaan_produk.keterangan NOT ILIKE 'MUTASI KELUAR UNTUK Kembali Produksi' AND
								  h_persediaan_produk.keterangan NOT ILIKE 'MUTASI KELUAR UNTUK Kebutuhan Internal')");
			}
		}
		if(!empty($this->produk_group)){
			$query->andWhere("produk_group ILIKE '%".$this->produk_group."%'");
		}
        if(!empty($this->gudang_id)){
			$query->andWhere("h_persediaan_produk.gudang_id ='".$this->gudang_id."'");
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
			$query->andWhere("tgl_transaksi <= '".$this->per_tanggal."'");
		}
		return $query;
	}
	
	public function searchLaporanProdukKeluarDt() {
		$searchLaporan = $this->searchLaporanProdukKeluar();
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
		if(!empty($searchLaporan->having)){
			$param['having'] = "HAVING ".$searchLaporan->having;
		}
		
		$param['where'] = [];
		array_push($param['where'],"out_qty_palet != 0 AND h_persediaan_produk.keterangan NOT ILIKE '%MUTASI DARI GUDANG%'");
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tgl_transaksi BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->nomor_produksi)){
			array_push($param['where'],"h_persediaan_produk.nomor_produksi ILIKE '%".$this->nomor_produksi."%'");
		}
		if(!empty($this->reff_no)){
			array_push($param['where'],"h_persediaan_produk.reff_no ILIKE '%".$this->reff_no."%'");
		}
		if(!empty($this->penerima)){
			array_push($param['where'],"penerima ILIKE '%".$this->penerima."%'");
		}
		if(!empty($this->cara_keluar)){
			if($this->cara_keluar=="SPM Lokal"){
				array_push($param['where'],"h_persediaan_produk.keterangan ILIKE 'PENJUALAN'");
			}else if($this->cara_keluar=="Export"){
				array_push($param['where'],"h_persediaan_produk.keterangan ILIKE 'MUTASI KELUAR UNTUK Export'");
			}else if($this->cara_keluar=="Mutasi Ke Produksi"){
				array_push($param['where'],"h_persediaan_produk.keterangan ILIKE 'MUTASI KELUAR UNTUK Kembali Produksi'");
			}else if($this->cara_keluar=="Mutasi Kebutuhan Internal"){
				array_push($param['where'],"h_persediaan_produk.keterangan ILIKE 'MUTASI KELUAR UNTUK Kebutuhan Internal'");
			}else{
				array_push($param['where'],"(h_persediaan_produk.keterangan NOT ILIKE 'PENJUALAN' AND
											h_persediaan_produk.keterangan NOT ILIKE 'MUTASI KELUAR UNTUK Export' AND
											h_persediaan_produk.keterangan NOT ILIKE 'MUTASI KELUAR UNTUK Kembali Produksi' AND
											h_persediaan_produk.keterangan NOT ILIKE 'MUTASI KELUAR UNTUK Kebutuhan Internal')");
			}
		}
		if(!empty($this->produk_group)){
			array_push($param['where'],"produk_group ILIKE '%".$this->produk_group."%'");
		}
        if(!empty($this->produk_nama)){
			array_push($param['where'],"produk_nama ILIKE '%".$this->produk_nama."%'");
		}
        if(!empty($this->gudang_id)){
			array_push($param['where'],"h_persediaan_produk.gudang_id = '".$this->gudang_id."'");
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
			array_push($param['where'],"tgl_transaksi <= '".$this->per_tanggal."'");
		}        
		return $param;
	}
}
