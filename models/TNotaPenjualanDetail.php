<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_nota_penjualan_detail".
 *
 * @property integer $nota_penjualan_detail_id
 * @property integer $nota_penjualan_id
 * @property integer $produk_id
 * @property double $qty_besar
 * @property string $satuan_besar
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property double $harga_hpp
 * @property double $harga_jual
 * @property double $ppn
 * @property double $pph
 * @property double $potongan
 * @property string $keterangan
 * @property integer $spm_log_id
 *
 * @property MBrgProduk $produk
 * @property TNotaPenjualan $notaPenjualan
 * @property mixed|null $limbah
 */
class TNotaPenjualanDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $subtotal,$tgl_awal,$tgl_akhir,$jenis_produk,$cust_an_nama,$cust_id,$kode,$tanggal,$sales_kode,$produk_nama,$produk_dimensi,$kode_op,$kode_spm;
	public $kode_sp,$nomor_dokumen,$sales_id,$harga_jual_lama,$harga_jual_baru,$alamat_bongkar,$cust_an_alamat;
    public $limbah_nama,$limbah_produk_jenis,$limbah_satuan_jual,$limbah_satuan_muat,$limbah_kode;
	public $produk_group,$jenis_kayu,$grade,$glue,$profil_kayu,$kondisi_kayu;
	public $nama;
	public $log_nama, $range_awal, $range_akhir, $alias, $produk_alias;
	public static function tableName()
    {
        return 't_nota_penjualan_detail';
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
            [['nota_penjualan_id', 'produk_id', 'qty_besar', 'satuan_besar', 'qty_kecil', 'satuan_kecil', 'kubikasi', 'harga_hpp', 'harga_jual'], 'required'],
            [['nota_penjualan_id', 'produk_id', 'spm_log_id'], 'integer'],
            [['qty_besar', 'qty_kecil', 'kubikasi', 'harga_hpp', 'harga_jual', 'ppn', 'pph', 'potongan'], 'number'],
            [['keterangan'], 'string'],
            [['satuan_besar', 'satuan_kecil'], 'string', 'max' => 50],
//            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['nota_penjualan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TNotaPenjualan::className(), 'targetAttribute' => ['nota_penjualan_id' => 'nota_penjualan_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'nota_penjualan_detail_id' => Yii::t('app', 'Nota Penjualan Detail'),
                'nota_penjualan_id' => Yii::t('app', 'Nota Penjualan'),
                'produk_id' => Yii::t('app', 'Produk'),
                'qty_besar' => Yii::t('app', 'Qty Besar'),
                'satuan_besar' => Yii::t('app', 'Satuan Besar'),
                'qty_kecil' => Yii::t('app', 'Qty Kecil'),
                'satuan_kecil' => Yii::t('app', 'Satuan Kecil'),
                'kubikasi' => Yii::t('app', 'Kubikasi'),
                'harga_hpp' => Yii::t('app', 'Harga Hpp'),
                'harga_jual' => Yii::t('app', 'Harga Jual'),
                'ppn' => Yii::t('app', 'Ppn'),
                'pph' => Yii::t('app', 'Pph'),
                'potongan' => Yii::t('app', 'Potongan'),
                'keterangan' => Yii::t('app', 'Keterangan'),
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
    public function getLimbah()
    {
        return $this->hasOne(MBrgLimbah::className(), ['limbah_id' => 'produk_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdukJasa()
    {
        return $this->hasOne(MProdukJasa::className(), ['produk_jasa_id' => 'produk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotaPenjualan()
    {
        return $this->hasOne(TNotaPenjualan::className(), ['nota_penjualan_id' => 'nota_penjualan_id']);
    }
	
	public function searchLaporan() {
		$query = self::find();
		// 2020-07-03 produk_dimensi dibuang, nggak bisa diprint / diexcel
		if ($this->jenis_produk == "JasaKD") {
			$query->select("nota_penjualan_detail_id, t_nota_penjualan.kode, t_nota_penjualan.tanggal, sales_kode, cust_an_nama, m_customer.cust_an_alamat, 
							t_nota_penjualan.jenis_produk, nama, nama, qty_kecil, kubikasi, harga_jual, t_op_ko.kode AS kode_op, 
							t_spm_ko.kode AS kode_spm, t_surat_pengantar.kode AS kode_sp, t_dokumen_penjualan.nomor_dokumen, m_brg_limbah.limbah_nama, 
							m_brg_limbah.limbah_satuan_jual, m_brg_limbah.limbah_satuan_muat, m_brg_limbah.limbah_produk_jenis, m_brg_limbah.limbah_kode,
							m_produk_jasa.kode as xxx, nama, nama,
							CASE WHEN t_nota_penjualan.jenis_produk = 'Log' OR t_nota_penjualan.jenis_produk = 'Lamineboard' OR t_nota_penjualan.jenis_produk = 'Platform' OR
										t_nota_penjualan.jenis_produk = 'Limbah' OR t_nota_penjualan.jenis_produk = 'FingerJointLamineBoard' OR 
										t_nota_penjualan.jenis_produk = 'FingerJointStick' OR t_nota_penjualan.jenis_produk = 'Flooring'
								THEN ROUND(SUM(qty_kecil * harga_jual))
								ELSE ROUND(SUM(kubikasi * harga_jual))
							END AS subtotal");
			$query->join('JOIN', 't_nota_penjualan','t_nota_penjualan_detail.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id');
			$query->join('JOIN', 't_op_ko','t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id');
			$query->join('JOIN', 't_spm_ko','t_spm_ko.spm_ko_id = t_nota_penjualan.spm_ko_id');
			$query->join('JOIN', 't_surat_pengantar','t_surat_pengantar.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id');
			$query->join('LEFT JOIN', 't_dokumen_penjualan','t_dokumen_penjualan.spm_ko_id = t_spm_ko.spm_ko_id');
			$query->join('JOIN', 'm_sales','m_sales.sales_id = t_op_ko.sales_id');
			$query->join('JOIN', 'm_customer','m_customer.cust_id = t_nota_penjualan.cust_id');
			$query->join('JOIN', 'm_produk_jasa','m_produk_jasa.produk_jasa_id = t_nota_penjualan_detail.produk_id');
			$query->join('LEFT JOIN', 'm_brg_limbah','m_brg_limbah.limbah_id = t_nota_penjualan_detail.produk_id');
			$query->groupBy('nota_penjualan_detail_id, t_nota_penjualan.kode, t_nota_penjualan.tanggal, sales_kode, cust_an_nama, 
							m_customer.cust_an_alamat, t_nota_penjualan.jenis_produk, nama, t_op_ko.kode, t_spm_ko.kode, 
							t_surat_pengantar.kode, t_dokumen_penjualan.nomor_dokumen, m_brg_limbah.limbah_nama, 
							m_brg_limbah.limbah_satuan_jual, m_brg_limbah.limbah_satuan_muat, m_brg_limbah.limbah_produk_jenis, m_brg_limbah.limbah_kode, 
							m_produk_jasa.kode');
			$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
				self::tableName().'.nota_penjualan_detail_id ASC' );
			if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
				$query->andWhere("t_nota_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
			}
			if(!empty($this->jenis_produk)){
				$query->andWhere("t_nota_penjualan.jenis_produk  = '".$this->jenis_produk."'");
			}
			if(!empty($this->cust_id)){
				$query->andWhere("m_customer.cust_id  = ".$this->cust_id);
			}
			if(!empty($this->kode)){
				$query->andWhere("t_nota_penjualan.kode  = '".$this->kode."'");
			}
			if(!empty($this->sales_id)){
				$query->andWhere("m_sales.sales_id  = '".$this->sales_id."'");
			}
		} else {
			$query->select("nota_penjualan_detail_id, t_nota_penjualan.kode, t_nota_penjualan.tanggal, sales_kode, cust_an_nama, m_customer.cust_an_alamat, 
							t_nota_penjualan.jenis_produk, produk_nama, produk_dimensi, t_nota_penjualan_detail.qty_kecil, t_nota_penjualan_detail.kubikasi, harga_jual, 
							t_op_ko.kode AS kode_op, t_spm_ko.kode AS kode_spm, t_surat_pengantar.kode AS kode_sp, t_dokumen_penjualan.nomor_dokumen, m_brg_limbah.limbah_nama, 
							m_brg_limbah.limbah_satuan_jual, m_brg_limbah.limbah_satuan_muat, m_brg_limbah.limbah_produk_jenis, m_brg_limbah.limbah_kode,
							m_brg_log.log_nama, m_brg_log.range_awal, m_brg_log.range_akhir,
							CASE WHEN t_nota_penjualan.jenis_produk = 'Log' OR t_nota_penjualan.jenis_produk = 'Lamineboard' OR t_nota_penjualan.jenis_produk = 'Platform' OR
										t_nota_penjualan.jenis_produk = 'Limbah' OR t_nota_penjualan.jenis_produk = 'FingerJointLamineBoard' OR 
										t_nota_penjualan.jenis_produk = 'FingerJointStick' OR t_nota_penjualan.jenis_produk = 'Flooring'
								THEN ROUND(SUM(t_nota_penjualan_detail.qty_kecil * harga_jual))
								ELSE ROUND(SUM(t_nota_penjualan_detail.kubikasi * harga_jual))
							END AS subtotal, t_po_ko_detail.alias, produk_alias");
			$query->join('JOIN', 't_nota_penjualan','t_nota_penjualan_detail.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id');
			$query->join('JOIN', 't_op_ko','t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id');
			$query->join('JOIN', 't_spm_ko','t_spm_ko.spm_ko_id = t_nota_penjualan.spm_ko_id');
			$query->join('JOIN', 't_surat_pengantar','t_surat_pengantar.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id');
			$query->join('LEFT JOIN', 't_dokumen_penjualan','t_dokumen_penjualan.spm_ko_id = t_spm_ko.spm_ko_id');
			$query->join('JOIN', 'm_sales','m_sales.sales_id = t_op_ko.sales_id');
			$query->join('JOIN', 'm_customer','m_customer.cust_id = t_nota_penjualan.cust_id');
			$query->join('LEFT JOIN', 'm_brg_produk','m_brg_produk.produk_id = t_nota_penjualan_detail.produk_id');
			$query->join('LEFT JOIN', 'm_brg_limbah','m_brg_limbah.limbah_id = t_nota_penjualan_detail.produk_id');
			$query->join('LEFT JOIN', 'm_brg_log','m_brg_log.log_id = t_nota_penjualan_detail.produk_id');
			$query->join('LEFT JOIN', 't_po_ko_detail', "t_po_ko_detail.po_ko_id = t_op_ko.po_ko_id
								AND (
										(t_po_ko_detail.produk_id IS NULL AND t_nota_penjualan_detail.produk_id = ANY(string_to_array(t_po_ko_detail.produk_id_alias, ',')::int[])) OR
										(t_po_ko_detail.produk_id IS NOT NULL AND t_nota_penjualan_detail.produk_id = t_po_ko_detail.produk_id)
									)");
			$query->groupBy('nota_penjualan_detail_id, t_nota_penjualan.kode, t_nota_penjualan.tanggal, sales_kode, cust_an_nama, m_customer.cust_an_alamat,
							t_nota_penjualan.jenis_produk, produk_nama, produk_dimensi,
							t_op_ko.kode, t_spm_ko.kode, t_surat_pengantar.kode, t_dokumen_penjualan.nomor_dokumen, m_brg_limbah.limbah_nama, 
							m_brg_limbah.limbah_satuan_jual, m_brg_limbah.limbah_satuan_muat, m_brg_limbah.limbah_produk_jenis, m_brg_limbah.limbah_kode,
							m_brg_log.log_nama, m_brg_log.range_awal, m_brg_log.range_akhir, t_po_ko_detail.alias,produk_alias');
			$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
				self::tableName().'.nota_penjualan_detail_id ASC' );
			if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
				$query->andWhere("t_nota_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
			}
			if(!empty($this->jenis_produk)){
				$query->andWhere("t_nota_penjualan.jenis_produk  = '".$this->jenis_produk."'");
			}
			if(!empty($this->cust_id)){
				$query->andWhere("m_customer.cust_id  = ".$this->cust_id);
			}
			if(!empty($this->kode)){
				$query->andWhere("t_nota_penjualan.kode  = '".$this->kode."'");
			}
			if(!empty($this->sales_id)){
				$query->andWhere("m_sales.sales_id  = '".$this->sales_id."'");
			}	
			
			if(!empty($this->jenis_kayu)){
				if (is_array($this->jenis_kayu)) {
					if (isset($this->jenis_kayu)) {
						$subq=null;
						$cn=1;
						$subq.='(';
						foreach ($this->jenis_kayu as $id_jenis_kayu) {
							$subq.="m_brg_produk.jenis_kayu = '".$id_jenis_kayu."' ";
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
					$query->andWhere("m_brg_produk.jenis_kayu = '".$this->jenis_kayu."'");
				}            
			}
			if(!empty($this->grade)){
				if (is_array($this->grade)) {
					if (isset($this->grade)) {
						$subq=null;
						$cn=1;
						$subq.='(';
						foreach ($this->grade as $id_grade) {
							$subq.="m_brg_produk.grade = '".$id_grade."' ";
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
					$query->andWhere("m_brg_produk.grade = '".$this->grade."'");
				}            
			}
			if(!empty($this->glue)){
				if (is_array($this->glue)) {
					if (isset($this->glue)) {
						$subq=null;
						$cn=1;
						$subq.='(';
						foreach ($this->glue as $id_glue) {
							$subq.="m_brg_produk.glue = '".$id_glue."' ";
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
					$query->andWhere("m_brg_produk.glue = '".$this->glue."'");
				}            
			}
			if(!empty($this->profil_kayu)){
				if (is_array($this->profil_kayu)) {
					if (isset($this->profil_kayu)) {
						$subq=null;
						$cn=1;
						$subq.='(';
						foreach ($this->profil_kayu as $id_profil_kayu) {
							$subq.="m_brg_produk.profil_kayu = '".$id_profil_kayu."' ";
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
					$query->andWhere("m_brg_produk.profil_kayu = '".$this->profil_kayu."'");
				}            
			}
			if(!empty($this->kondisi_kayu)){
				if (is_array($this->kondisi_kayu)) {
					if (isset($this->kondisi_kayu)) {
						$subq=null;
						$cn=1;
						$subq.='(';
						foreach ($this->kondisi_kayu as $id_kondisi_kayu) {
							$subq.="m_brg_produk.kondisi_kayu = '".$id_kondisi_kayu."' ";
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
					$query->andWhere("m_brg_produk.kondisi_kayu = '".$this->kondisi_kayu."'");
				}
			}
			if(!empty($this->produk_dimensi)){
				if (is_array($this->produk_dimensi)) {
					if (isset($this->produk_dimensi)) {
						$subq=null;
						$cn=1;
						$subq.='(';
						foreach ($this->produk_dimensi as $id_produk_dimensi) {
							if(substr($id_produk_dimensi,-1) == "'"){$fkon = "'";}else{ $fkon = "";}
							$subq.="m_brg_produk.produk_dimensi = '".$id_produk_dimensi."' $fkon";
							if ($cn < count($this->produk_dimensi)) {
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
					$query->andWhere("m_brg_produk.produk_dimensi = '".$this->produk_dimensi."'");
				}
			}
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
			array_push($param['where'],"t_nota_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->jenis_produk)){
			array_push($param['where'],"t_nota_penjualan.jenis_produk ILIKE '%".$this->jenis_produk."%'");
		}
		if(!empty($this->cust_id)){
			array_push($param['where'],"m_customer.cust_id = ".$this->cust_id);
		}
		if(!empty($this->kode)){
			array_push($param['where'],"t_nota_penjualan.kode = '".$this->kode."'");
		}
		if(!empty($this->sales_id)){
			array_push($param['where'],"m_sales.sales_id = '".$this->sales_id."'");
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
		if(!empty($this->produk_dimensi)){
            if (is_array($this->produk_dimensi)) {
                if (isset($this->produk_dimensi)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->produk_dimensi as $id_produk_dimensi) {
						if(substr($id_produk_dimensi,-1) == "'"){$fkon = "'";}else{ $fkon = "";}
                        $subq.="produk_dimensi = '".$id_produk_dimensi."' $fkon";
                        if ($cn < count($this->produk_dimensi)) {
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
                array_push($param['where'],"produk_dimensi = '".$this->produk_dimensi."'");
            }     
        }
		if(!empty($this->per_tanggal)){
			array_push($param['where'],"tgl_transaksi <= '".$this->per_tanggal."'");
		}
		return $param;
	}

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getLog()
    {
        return $this->hasOne(MBrgLog::className(), ['log_id' => 'produk_id']);
    }
}
