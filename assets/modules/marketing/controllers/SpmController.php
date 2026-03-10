<?php

namespace app\modules\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class SpmController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TSpmKo();
        $model->kode = 'Auto Generate';
//        $model->tanggal = date('d/m/Y');
        $model->dibuat = Yii::$app->user->identity->pegawai_id;
        $model->dibuat_display = Yii::$app->user->identity->pegawai->pegawai_nama;
        $model->disetujui = \app\components\Params::DEFAULT_PEGAWAI_ID_FITRIYANAH;
		$modOP = new \app\models\TOpKo();
        
        if(isset($_GET['spm_ko_id'])){
            $model = \app\models\TSpmKo::findOne($_GET['spm_ko_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_rencanamuat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_rencanamuat);
            $model->tanggal_kirim = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
            $model->waktu_mulaimuat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->waktu_mulaimuat);
            $model->waktu_selesaimuat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->waktu_selesaimuat);
			$modOP = \app\models\TOpKo::findOne($model->op_ko_id);
			$model->jenis_produk = $model->opKo->jenis_produk;
			$model->kode_op = $model->opKo->kode;
			$model->cust_an_nama = $model->cust->cust_an_nama;
			$model->cust_pr_nama = !empty($model->cust->cust_pr_nama)?$model->cust->cust_pr_nama:"-";
			$model->cust_an_alamat = $model->cust->cust_an_alamat;
			$model->dibuat_display = $model->dibuat0->pegawai_nama;
        }
		
        if( Yii::$app->request->post('TSpmKo')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_spm_ko
                $success_2 = true; // t_spm_ko_detail
                $success_3 = true; // t_produk_keluar
                $success_4 = true; // h_persediaan_produk
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					// exec ini jika proses edit
					$model->kode = \app\components\DeltaGenerator::kodeSpm($_POST['TSpmKo']['jenis_produk']);
				}
				if(!empty($model->waktu_mulaimuat)){
					$model->waktu_mulaimuat = explode(" - ", $model->waktu_mulaimuat);
					$model->waktu_mulaimuat = $model->waktu_mulaimuat[0]." ".$model->waktu_mulaimuat[1].":00";
				}
				if(!empty($model->waktu_selesaimuat)){
					$model->waktu_selesaimuat = explode(" - ", $model->waktu_selesaimuat);
					$model->waktu_selesaimuat = $model->waktu_selesaimuat[0]." ".$model->waktu_selesaimuat[1].":00";
				}
				
				
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if((isset($_GET['edit'])) && (isset($_GET['spm_ko_id']))){
							// exec ini jika proses edit
							$modDetail = \app\models\TSpmKoDetail::find()->where(['spm_ko_id'=>$_GET['spm_ko_id']])->all();
							if(count($modDetail)>0){
								\app\models\TSpmKoDetail::deleteAll(['spm_ko_id'=>$_GET['spm_ko_id']]);
							}
						}
						foreach($_POST['TSpmKoDetail'] as $i => $detail){
							$modDetail = new \app\models\TSpmKoDetail();
							$modDetail->attributes = $detail;
							$modDetail->spm_ko_id = $model->spm_ko_id;
							$modDetail->harga_jual_realisasi = $modDetail->harga_jual;
							$modDetail->satuan_besar_realisasi = $modDetail->satuan_besar;
                            if($model->opKo->jenis_produk == "JasaKD" || $model->opKo->jenis_produk == "JasaGesek" || $model->opKo->jenis_produk == "JasaMoulding"){
                                $modDetail->keterangan = $detail['nomor_palet_exist'];
                            }
							if($modDetail->validate()){
								if($modDetail->save()){
									$success_2 &= true;
									
									// SPM Realisasi nye
									if(isset($_POST['TProdukKeluar'])){
										foreach($_POST['TProdukKeluar'] as $i => $detail){
											if($detail['produk_id']==$modDetail->produk_id){
												$modProdukKeluar = \app\models\TProdukKeluar::findOne(['nomor_produksi'=>$detail['nomor_produksi']]);
												if(empty($modProdukKeluar)){
													$modProdukKeluar = new \app\models\TProdukKeluar();
													$modProdukKeluar->attributes = $detail;
													$modProdukKeluar->kode = \app\components\DeltaGenerator::kodeProdukKeluar($model->jenis_produk);
													$modProdukKeluar->tanggal = date("Y-m-d");
													$modProdukKeluar->cara_keluar = \app\models\TProdukKeluar::CARA_KELUAR_PENJUALAN;
													$modProdukKeluar->reff_no = $model->kode;
													$modProdukKeluar->reff_detail_id = $modDetail->spm_kod_id;
													$modProdukKeluar->petugas_mengeluarkan = $model->dikeluarkan;
													$modProdukKeluar->keterangan = !empty($modProdukKeluar->keterangan)?$modProdukKeluar->keterangan:'-';
													$modProdukKeluar->gudang_id = $detail['gudang_id'];
												}else{
													$modProdukKeluar->reff_detail_id = $modDetail->spm_kod_id;
													$modProdukKeluar->gudang_id = $detail['gudang_id'];
												}
												if($modProdukKeluar->validate()){
													if($modProdukKeluar->save()){
														$success_3 &= true;
														
														if(\app\models\HPersediaanProduk::getCurrentStockPerPalet($detail['nomor_produksi'])['palet']>0){
															// Start Proses Update Stock (OUT)
															$modPersediaan = new \app\models\HPersediaanProduk();
															$modPersediaan->attributes = $modProdukKeluar->attributes;
															$modPersediaan->tgl_transaksi = $modProdukKeluar->tanggal;
															$modPersediaan->gudang_id = $modProdukKeluar->gudang_id;
															$modPersediaan->reff_no = $model->kode;
															$modPersediaan->in_qty_palet = 0;
															$modPersediaan->in_qty_kecil = 0;
															$modPersediaan->in_qty_kecil_satuan = $modProdukKeluar->satuan_kecil;
															$modPersediaan->in_qty_m3 = 0;
															$modPersediaan->out_qty_palet = $modProdukKeluar->qty_besar;
															$modPersediaan->out_qty_kecil = $modProdukKeluar->qty_kecil;
															$modPersediaan->out_qty_kecil_satuan = $modProdukKeluar->satuan_kecil;
															$modPersediaan->out_qty_m3 = $modProdukKeluar->kubikasi;
															$modPersediaan->keterangan = $modProdukKeluar->cara_keluar;
															$success_4 &= \app\models\HPersediaanProduk::updateStokPersediaan($modPersediaan);
															// End Proses Update Stock (OUT)
														}else{
															$success_4 = false;
															$errmsg = $detail['nomor_produksi']." Out of Stock";
														}
														
													}else{
														$success_3 = false;
													}
												}else{
													$success_3 = false;
												}
											}
										}
									}
									
								}else{
									$success_2 = false;
								}
							}else{
								$success_2 = false;
							}
						}
                    }
                }
				
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>3";
//				print_r($success_3);
//				echo "<pre>4";
//				print_r($success_4);
//				exit;
                if ($success_1 && $success_2 && $success_3 && $success_4) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'spm_ko_id'=>$model->spm_ko_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modOP'=>$modOP]);
	}
	
	public function actionSetOP(){
		if(\Yii::$app->request->isAjax){
			$op_ko_id = \Yii::$app->request->post('op_ko_id');
			$data = [];
			if(!empty($op_ko_id)){
				$model = \app\models\TOpKo::findOne($op_ko_id);
				$modCust = \app\models\MCustomer::findOne($model->cust_id);
				if(!empty($model)){
					$data = $model->attributes;
					$data['tanggal_kirim'] = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
				}
				if(!empty($modCust)){
					$data['cust'] = $modCust->attributes;
					$data['cust']['cust_pr_nama'] = (!empty($modCust->cust_pr_nama)?$modCust->cust_pr_nama:"-");
					
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionOpenOP(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-op'){
				$param['table']= \app\models\TOpKo::tableName();
				$param['pk']= $param['table'].".".\app\models\TOpKo::primaryKey()[0];
				$param['column'] = [$param['table'].'.op_ko_id',
									$param['table'].'.kode',
									$param['table'].'.jenis_produk',
									$param['table'].'.tanggal',
									'm_sales.sales_nm',
									$param['table'].'.sistem_bayar',
									$param['table'].'.tanggal_kirim',
									'm_customer.cust_an_nama',
									'm_customer.cust_pr_nama',
									't_approval.status'
									];
				$param['join']= ['JOIN m_sales ON m_sales.sales_id = '.$param['table'].'.sales_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  LEFT JOIN t_approval ON t_approval.reff_no = t_op_ko.kode
								  LEFT JOIN t_spm_ko ON t_spm_ko.op_ko_id = t_op_ko.op_ko_id'];
				$param['where']= $param['table'].".cancel_transaksi_id IS NULL"
                                 ." AND (CASE 
                                            WHEN t_op_ko.jenis_produk = 'JasaKD' THEN t_op_ko.jenis_produk = 'JasaKD'
                                            WHEN t_op_ko.jenis_produk = 'JasaGesek' THEN t_op_ko.jenis_produk = 'JasaGesek'
                                            WHEN t_op_ko.jenis_produk = 'JasaMoulding' THEN t_op_ko.jenis_produk = 'JasaMoulding'
                                        ELSE
                                            t_spm_ko.op_ko_id IS NULL 
                                    END)";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('orderpenjualan');
        }
	}
	
	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $op_ko_id = Yii::$app->request->post('op_ko_id');
			$model = new \app\models\TSpmKo();
			$modDetail = [];
            $modOpKo = [];
            $data = [];
            if(!empty($op_ko_id)){
                $modOpKo = \app\models\TOpKo::findOne($op_ko_id);
                $model->jenis_produk = $modOpKo->jenis_produk;
                $modOPDetail = \app\models\TOpKoDetail::find()->where(['op_ko_id'=>$op_ko_id])->all();
            }
            $data['html'] = '';
            if(count($modOPDetail)>0){
                foreach($modOPDetail as $i => $opdetail){
					$modDetail = new \app\models\TSpmKoDetail();
					$modDetail->attributes = $opdetail->attributes;
                    if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                        $modDetail->qty_besar = 0; $modDetail->qty_kecil = 0; $modDetail->kubikasi = 0;
                        $data['html'] .= $this->renderPartial('_itemOPJasaKD',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i,'modOpDetail'=>$opdetail]);
                    }else{
                        $data['html'] .= $this->renderPartial('_itemOP',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i,'modOpDetail'=>$opdetail]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	function actionGetItemsById(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $realisasi = Yii::$app->request->post('realisasi');
            $edit = Yii::$app->request->post('edit');
			$model = \app\models\TSpmKo::findOne($id);
            $model->jenis_produk = $model->opKo->jenis_produk;
			$modDetail = [];
            $data = [];
            if(!empty($id)){
                $modDetail = \app\models\TSpmKoDetail::find()->where(['spm_ko_id'=>$id])->all();
            }
            $data['html'] = '';
            if(count($modDetail)>0){
                foreach($modDetail as $i => $detail){
					$modOp = \app\models\TOpKo::findOne($model->op_ko_id);
					$modOpDetail = \app\models\TOpKoDetail::findOne(['op_ko_id'=>$model->op_ko_id,'produk_id'=>$detail->produk_id]);
                    if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                        $itemHtml = "_itemOPJasaKD";
                        $detail->nomor_palet_exist = $detail->keterangan;
                    }else{
                        $itemHtml = "_itemOP";
                    }
					if($realisasi){
						$detail->kubikasi = number_format($detail->kubikasi ,4);
						if($model->status!= \app\models\TSpmKo::REALISASI){
							$detail->qty_besar_realisasi = 0;
							$detail->satuan_besar_realisasi = $detail->satuan_besar;
							$detail->qty_kecil_realisasi = 0;
							$detail->satuan_kecil_realisasi = $detail->satuan_kecil;
							$detail->kubikasi_realisasi = 0;
						}
//						if($modOpDetail->is_random=='1'){
//							$modRandom = \app\models\TOpKoRandom::find()->where("op_ko_detail_id = ".$modOpDetail->op_ko_detail_id)->orderBy("nomor_produksi ASC, op_ko_random_id ASC")->all();
//							if(count($modRandom)>0){
//								$data['html'] .= $this->renderPartial('_itemOP',['model'=>$model,'modDetail'=>$detail,'i'=>$i,'realisasi'=>$realisasi,'modOpDetail'=>$modOpDetail]);
//								if($edit){
//									foreach($modRandom as $ii => $random){
//										$data['html'] .= $this->renderPartial('_itemOpRandom',['model'=>$model,'modDetail'=>$detail,'i'=>$i,'ii'=>$ii,'realisasi'=>$realisasi,'random'=>$random,'edit'=>$edit,'modRandom'=>$modRandom]);
//									}
//								}
//							}
//						}else{
							$data['html'] .= $this->renderPartial($itemHtml,['model'=>$model,'modDetail'=>$detail,'i'=>$i,'realisasi'=>$realisasi,'modOpDetail'=>$modOpDetail]);
//						}
					}else{
						$data['html'] .= $this->renderPartial($itemHtml,['model'=>$model,'modDetail'=>$detail,'i'=>$i,'realisasi'=>$realisasi,'modOpDetail'=>$modOpDetail]);
					}
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TSpmKo::tableName();
				$param['pk']= $param['table'].".".\app\models\TSpmKo::primaryKey()[0];
				$param['column'] = [$param['table'].'.spm_ko_id',
									$param['table'].'.kode',
									't_op_ko.jenis_produk',
									$param['table'].'.tanggal',
									'm_customer.cust_an_nama',
									$param['table'].'.tanggal_kirim',
									$param['table'].'.kendaraan_nopol',
									$param['table'].'.kendaraan_supir',
									$param['table'].'.alamat_bongkar',
									$param['table'].'.cancel_transaksi_id',
									$param['table'].'.status',
									'nota_penjualan_id'
									];
				$param['join']= ['JOIN t_op_ko ON t_op_ko.op_ko_id = '.$param['table'].'.op_ko_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  LEFT JOIN t_nota_penjualan ON t_nota_penjualan.spm_ko_id = t_spm_ko.spm_ko_id
								'];
				$param['where'] = "t_op_ko.cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionPrintSPM(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TSpmKo::findOne($_GET['id']);
		$modDetail = \app\models\TSpmKoDetail::find()->where(['spm_ko_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'SURAT PERINTAH MUAT');
		if($caraprint == 'PRINT'){
			return $this->render('printSPM',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printSPM',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printSPM',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}
	
	public function actionFindSPMAll(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT * FROM t_spm_ko 
					WHERE kode ilike '%{$term}%' AND cancel_transaksi_id IS NULL
					ORDER BY created_at";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'spm_ko_id', 'kode');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['spm_ko_id'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	
	public function actionGetCurrentProdukList(){
        if(\Yii::$app->request->isAjax){
			$kode_spm = Yii::$app->request->post('kode_spm');
			$status = Yii::$app->request->post('status');
            $modProdukKeluar = \app\models\TProdukKeluar::find()->where(['reff_no'=>$kode_spm])->all();
			$modSpm = \app\models\TSpmKo::findOne(['kode'=>$kode_spm]);
			$data = [];
			$data['item'] = "";
			$data['model'] = "";
			if(count($modProdukKeluar)>0){
				foreach($modProdukKeluar as $i => $keluar){
					$data['model'][] = $keluar->attributes;
					if($status== \app\models\TSpmKo::REALISASI){
						$data['item'] .= $this->renderPartial('_itemProdukListRealisasi',['model'=>$keluar]);
					}else{
						$modOpDetail = \app\models\TOpKoDetail::find()->where("produk_id = ".$keluar->produk_id)->one();
						if(!empty($modOpDetail)){
							$modRandom = \app\models\TOpKoRandom::find()->where("op_ko_detail_id = ".$modOpDetail->op_ko_detail_id)->all();
							if(count($modRandom)>0){
								$rand = [];
								foreach($modRandom as $ii => $random){
									$rand[] = $random;
								}
								$keluar->random = \yii\helpers\Json::encode($rand);
							}
						}
						$data['item'] .= $this->renderPartial('_itemProdukList',['model'=>$keluar]);
					}
				}
			}
            return $this->asJson($data);
        }
    }
	
	public function actionAddProdukList(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TProdukKeluar();
            $data['item'] = $this->renderPartial('_itemProdukList',['model'=>$model]);
            return $this->asJson($data);
        }
    }
	
	public function actionFindStockActive(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$jns_produk = Yii::$app->request->get('type');
			$notinpost = json_decode( Yii::$app->request->get('notin') );
			$data = []; $notin = "";
			if(!empty($notinpost)){
				$notin = "AND h_persediaan_produk.nomor_produksi NOT IN(";
				foreach($notinpost as $i => $not){
					$notin .= "'$not'";
					if( ($i+1)!=(count($notinpost)) ){
						$notin .= ",";
					}
				}
				$notin .= ")";
			}
			if(!empty($term)){
				$query = "
					SELECT h_persediaan_produk.produk_id, nomor_produksi, gudang_id, 
						SUM(in_qty_palet-out_qty_palet) AS qty_palet, 
						SUM(in_qty_kecil-out_qty_kecil) AS qty_kecil, 
						in_qty_kecil_satuan AS satuan_kecil,
						SUM(in_qty_m3-out_qty_m3) AS kubikasi  
					FROM h_persediaan_produk 
					JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
					WHERE ".(!empty($term)?"nomor_produksi ILIKE '%".$term."%'":'')." AND h_persediaan_produk.active IS TRUE AND produk_group = '".$jns_produk."' ".$notin." 
					GROUP BY h_persediaan_produk.produk_id, nomor_produksi, gudang_id, in_qty_kecil_satuan
					HAVING SUM(in_qty_palet-out_qty_palet) > 0
				";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['nomor_produksi'], 'text'=>$val['nomor_produksi']];
					}
				}
			}
            return $this->asJson($data);
        }
    }
	function actionSetItemProductList(){
		if(\Yii::$app->request->isAjax){
            $nomor_produksi = Yii::$app->request->post('nomor_produksi');
            $op_ko_id = Yii::$app->request->post('op_ko_id');
            if(!empty($nomor_produksi)){
                $data['produksi'] = \app\models\TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
                $data['produk'] = \app\models\MBrgProduk::findOne($data['produksi']['produk_id']);
                $data['persediaan'] = \app\models\HPersediaanProduk::getDataByNomorProduksi($nomor_produksi);
				$data['random'] = '';
				$data['kubikasi_hasilhitung'] = 0;
				if(!empty($op_ko_id)){
					$modRandom = Yii::$app->db->createCommand("
								SELECT t_op_ko_random.* FROM t_op_ko_random
								JOIN t_op_ko_detail ON t_op_ko_detail.op_ko_detail_id = t_op_ko_random.op_ko_detail_id
								WHERE op_ko_id = {$op_ko_id} AND t_op_ko_detail.produk_id = {$data['produksi']->produk_id}
								")->queryAll();
					if(count($modRandom)>0){
						$rand = [];
						foreach($modRandom as $ii => $random){
							$rand[] = $random;
						}
						$data['random'] = \yii\helpers\Json::encode($rand);
					}
				}
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }
	
	function actionGetItemsScanned(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data = [];
			$data['html'] = '';
			$data['status'] = "";
            if(!empty($id)){
                $modSPM = \app\models\TSpmKo::findOne($id);
				$data['status'] = $modSPM->status;
				$models = \app\models\TProdukKeluar::find()->where(["reff_no"=>$modSPM->kode])->orderBy("produk_keluar_id DESC")->all();
				if(count($models)>0){
					foreach($models as $i => $model){
						$data['html'] .= $this->renderPartial('_itemScannedSpm',['model'=>$model]);
					}
				}
            }
            return $this->asJson($data);
        }
    }
	
	public function actionScanSpm(){
		$model = new \app\models\TProdukKeluar();
		return $this->render('scanspm',['model'=>$model]);
	}
	
	public function actionSaveNomorProduksi(){
		if(\Yii::$app->request->isAjax){
			$data['status'] = false;
			$data['msg'] = "";
			$prod_number = \Yii::$app->request->post('prod_number');
			$spm_ko_id = \Yii::$app->request->post('spm_ko_id');
			$modSpm = \app\models\TSpmKo::findOne($spm_ko_id);
			$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$prod_number]);
			$modPersediaan = \app\models\HPersediaanProduk::getDataByNomorProduksi($prod_number);
			$modProdukKeluar = \app\models\TProdukKeluar::findOne(['nomor_produksi'=>$prod_number]);
			if(empty($modProdukKeluar)){
				$spmsama = true;
				$modSPMdetail = \app\models\TSpmKoDetail::find()->where(['spm_ko_id'=>$modSpm->spm_ko_id,'produk_id'=>$modProduksi->produk_id])->all();
				if(count($modSPMdetail)>0){
					$spmsama = true;
				}else{
					$spmsama = false;
				}
				if($spmsama){
					if(!empty($modPersediaan)){
						if(!empty($modSpm)){
							$modProdukKeluar = new \app\models\TProdukKeluar();
							$modProdukKeluar->attributes = $modProduksi->attributes;
							$modProdukKeluar->attributes = $modPersediaan;
							$modProdukKeluar->kode = \app\components\DeltaGenerator::kodeProdukKeluar($modProduksi->produk->produk_group);
							$modProdukKeluar->tanggal = date("Y-m-d");
							$modProdukKeluar->cara_keluar = \app\models\TProdukKeluar::CARA_KELUAR_PENJUALAN;
							$modProdukKeluar->reff_no = $modSpm->kode;
							$modProdukKeluar->petugas_mengeluarkan = Yii::$app->user->identity->pegawai_id;
							$modProdukKeluar->keterangan = 'Scan Result';
							$modProdukKeluar->gudang_id = $modPersediaan['gudang_id'];
							$modProdukKeluar->qty_besar = $modPersediaan['qty_palet'];
							$modProdukKeluar->satuan_besar = $modProduksi->produk->produk_satuan_besar;
							if($modProdukKeluar->validate()){
								if($modProdukKeluar->save()){
									$data['status'] = true;
								}
							}
						}else{
							$data['status'] = false;
							$data['msg'] = "Data SPM tidak ditemukan!";
						}
					}else{
						$data['status'] = false;
						$data['msg'] = "Tidak tersedia di stock!";
					}
				}else{
					$data['status'] = false;
					$data['msg'] = "Produk tidak sesuai dengan SPM";
				}
			}else{
				$data['status'] = false;
				$data['msg'] = "Produk sudah pernah keluar!";
			}
			return $this->asJson($data);
		}
	}
	
	public function actionDeleteNomorProduksi($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TProdukKeluar::findOne($id);
			$modSpm = \app\models\TSpmKo::findOne(['kode'=>$model->reff_no]);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if($model->delete()){
                        $success_1 = true;
                    }else{
                        $data['message'] = Yii::t('app', 'Data Gagal dihapus');
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['callback'] = "getItemsScanned(".$modSpm->spm_ko_id.");";
    //						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
            }
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>'deleteNomorProduksi']);
		}
	}
	
	public function actionSetQty(){
		if(\Yii::$app->request->isAjax){
			$produk_id = Yii::$app->request->post('produk_id');
			$data = [];
			if(!empty($produk_id)){
				$modProduk = \app\models\MBrgProduk::findOne($produk_id);
				$data['qty_kecil'] = $modProduk->produk_qty_satuan_kecil;
				$data['kubikasi'] = $modProduk->kapasitas_kubikasi;
			}
            return $this->asJson($data);
        }
	}
	
	public function actionAddProduk(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new \app\models\TSpmKoDetail();
            $data['item'] = $this->renderPartial('_addItem',['modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }
	
	public function actionCheckApproval(){
		if(\Yii::$app->request->isAjax){
			$op_ko_id = Yii::$app->request->post('op_ko_id');
			$data['status'] = true;
			if(!empty($op_ko_id)){
				$modOP = \app\models\TOpKo::findOne($op_ko_id);
				$approval = \app\models\TApproval::find()->where(['reff_no'=>$modOP->kode])->all();
				if(count($approval)>0){
					foreach($approval as $i => $appr){
						if($appr->status == "APPROVED"){
							$data['status'] &= true;
						}else{
							$data['status'] = false;
						}
					}
				}
			}
            return $this->asJson($data);
        }
	}
	
	public function actionListRandom($produk_id,$op_ko_id){
		if(\Yii::$app->request->isAjax){
			$modOp = \app\models\TOpKo::findOne($op_ko_id);
			$modOpDetail = \app\models\TOpKoDetail::findOne(['op_ko_id'=>$op_ko_id,'produk_id'=>$produk_id]);
			$sql = "SELECT * FROM t_op_ko_random WHERE op_ko_detail_id = {$modOpDetail->op_ko_detail_id} ";
			$models = Yii::$app->db->createCommand($sql)->queryAll();
			$modProduk = \app\models\MBrgProduk::findOne($produk_id);
			return $this->renderAjax('random',['models'=>$models,'modProduk'=>$modProduk]);
		}
	}
	
	public function actionInfoPalet(){
		if(\Yii::$app->request->isAjax){
			$nomor_produksi = Yii::$app->request->get('nomor_produksi');
			$modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$nomor_produksi]);
			$modTerimaRandom = []; $modProduksi = [];
			if(!empty($modTerima)){
				$modTerimaRandom = \app\models\TTerimaKoKd::find()->where("tbko_id = ".$modTerima->tbko_id)->all();
				$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
			}
			return $this->renderAjax('infoPalet',['modTerima'=>$modTerima,'modTerimaRandom'=>$modTerimaRandom,'modProduksi'=>$modProduksi]);
        }
    }
    
    public function actionEditKecil($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpmKo::findOne($id);
			if( Yii::$app->request->post('TSpmKo')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_spm
                    $success_2 = true; // t_nota_penjualan
                    $success_3 = true; // t_surat_pengantar
                    $success_4 = true; // t_dokumen_penjualan
                    $model->load(\Yii::$app->request->post());
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                            $modNota = \app\models\TNotaPenjualan::findOne(['spm_ko_id'=>$model->spm_ko_id]);
                            if(!empty($modNota)){
                                $modNota->kendaraan_nopol = $model->kendaraan_nopol;
                                $modNota->kendaraan_supir = $model->kendaraan_supir;
                                if($modNota->validate()){
                                    $success_2 = $modNota->save();
                                }
                            }
                            $modSp = \app\models\TSuratPengantar::findOne(['spm_ko_id'=>$model->spm_ko_id]);
                            if(!empty($modSp)){
                                $modSp->kendaraan_nopol = $model->kendaraan_nopol;
                                $modSp->kendaraan_supir = $model->kendaraan_supir;
                                if($modSp->validate()){
                                    $success_3 = $modSp->save();
                                }
                            }
                            $modDok = \app\models\TDokumenPenjualan::findOne(['spm_ko_id'=>$model->spm_ko_id]);
                            if(!empty($modDok)){
                                $modDok->kendaraan_nopol = $model->kendaraan_nopol;
                                $modDok->kendaraan_supir = $model->kendaraan_supir;
                                if($modDok->validate()){
                                    $success_4 = $modDok->save();
                                }
                            }
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
//                    echo "<pre>";
//                    print_r($success_1);
//                    echo "<pre>";
//                    print_r($success_2);
//                    echo "<pre>";
//                    print_r($success_3);
//                    echo "<pre>";
//                    print_r($success_4);
//                    exit;
                    if ($success_1 && $success_2 && $success_3 && $success_4) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data Berhasil Diupdate');
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('editKecil',['model'=>$model]);
		}
	}
    
    public function actionListPaletTerima($produk_id,$op_ko_id,$spm_ko_id,$tr_seq=null,$nomor_palet_exist=null,$lihat=null){
		if(\Yii::$app->request->isAjax){
			$modOp = \app\models\TOpKo::findOne($op_ko_id);
			$modOpDetail = \app\models\TOpKoDetail::findOne(['op_ko_id'=>$op_ko_id,'produk_id'=>$produk_id]);
			$models = \app\models\TTerimaJasa::find()->select("tanggal,nomor_palet")->where("op_ko_id = {$op_ko_id} ")->groupBy("tanggal,nomor_palet")->orderBy("tanggal ASC")->all();
			$modProduk = \app\models\MProdukJasa::findOne($produk_id);
            $nomor_palet_exist = str_replace("'", "", explode(",", $nomor_palet_exist));
			return $this->renderAjax('paletterima',['models'=>$models,'modProduk'=>$modProduk,'nomor_palet_exist'=>$nomor_palet_exist,'modOp'=>$modOp,'tr_seq'=>$tr_seq,'lihat'=>$lihat,'spm_ko_id'=>$spm_ko_id]);
		}
	}
    
    public function actionGetPaletisi(){
		if(\Yii::$app->request->isAjax){
			$op_ko_id = Yii::$app->request->post("op_ko_id");
			$nomor_palet = Yii::$app->request->post("nomor_palet");
			$data['html'] = "";
			$data['tot_qty'] = 0;
			$data['tot_kubikasi'] = 0;
			if(!empty($nomor_palet)){
				$models = Yii::$app->db->createCommand("SELECT * FROM t_terima_jasa WHERE op_ko_id = {$op_ko_id} AND nomor_palet IN({$nomor_palet})")->queryAll();
				if(count($models)>0){
					foreach($models as $i => $model){
						$data['html'] .= "<tr>";
						$data['html'] .= "<td><center>".($i+1)."</center></td>";
						$data['html'] .= "<td style='text-align:center;'>".$model['nomor_palet']."</td>";
						$data['html'] .= "<td style='text-align:center;'>".$model['t'].$model['t_satuan']." x ".$model['l'].$model['l_satuan']." x ".$model['p'].$model['p_satuan']."</td>";
						$data['html'] .= "<td style='text-align:center;'>".$model['qty_kecil']."</td>";
						$data['html'] .= "<td style='text-align:right;'>".$model['kubikasi']."</td>";
						$data['html'] .= "</tr>";
						$data['tot_qty'] += $model['qty_kecil'];
						$data['tot_kubikasi'] += $model['kubikasi'];
					}
				}
                
                $modTerimaJasa = Yii::$app->db->createCommand("SELECT * FROM t_terima_jasa WHERE op_ko_id = {$op_ko_id} AND nomor_palet NOT IN({$nomor_palet})")->queryAll();
			}
			return $this->asJson($data);
		}
	}
}
