<?php

namespace app\assets\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class OrderpenjualanController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TOpKo();
        $model->kode = 'Auto Generate';
//        $model->tanggal = date('d/m/Y');
        $model->syarat_jual = "Loco";
        $model->cara_bayar = "Transfer Bank";
		$model->disetujui = \app\components\Params::DEFAULT_PEGAWAI_ID_FITRIYANAH;
		$modTempo = new \app\models\TTempobayarKo();
		$modTempo->top_hari = 0;
		$modTempo->maks_top_hari = 0;
		$modTempo->op_aktif = 0;
        
        if(isset($_GET['op_ko_id'])){
            $model = \app\models\TOpKo::findOne($_GET['op_ko_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_kirim = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
            $model->customer = $model->cust->cust_an_nama. (!empty($model->cust->cust_pr_nama)?" - ".$model->cust->cust_pr_nama:"");
			$modTempoBayar = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);
			if(!empty($modTempoBayar)){
				$modTempo->attributes = $modTempoBayar->attributes;
				$modTempo->maks_plafon = \app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->maks_plafon);
				$modTempo->sisa_piutang = \app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_piutang);
				$modTempo->sisa_plafon = \app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_plafon);
			}
        }
		
        if( Yii::$app->request->post('TOpKo')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_op_ko
                $success_2 = true; // t_op_ko_detail
                $success_3 = false; // t_tempobayar_ko
                $success_4 = false; // t_approval
                $success_5 = true; // t_spm_detail
                $success_6 = true; // t_op_ko_random
                $success_7 = true; // t_terima_jasa
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::orderPenjualan($model->jenis_produk);
				}
                if($model->validate()){
                    if($model->save()){
						$success_1 = true;
						
						if(isset($_GET['edit'])){ // jika proses edit
                            if( count( \app\models\TTerimaJasa::find()->where("op_ko_id = ".$model->op_ko_id)->all() )>0 ){
                                $success_7 = (\app\models\TTerimaJasa::deleteAll("op_ko_id = ".$model->op_ko_id))?true:false;
                            }
                            foreach(\app\models\TOpKoDetail::findAll(['op_ko_id'=>$model->op_ko_id]) as $i => $detail){
                                if($detail->is_random==1){
                                    $modRandom = \app\models\TOpKoRandom::findAll(['op_ko_detail_id'=>$detail->op_ko_detail_id]);
                                    if(count($modRandom)){
                                        $success_6 = (\app\models\TOpKoRandom::deleteAll("op_ko_detail_id = {$detail->op_ko_detail_id}"))?true:false;
                                    }
                                }
                            }
                            $success_2 = (\app\models\TOpKoDetail::deleteAll("op_ko_id = ".$model->op_ko_id))?true:false;
                            $success_3 = (\app\models\TTempobayarKo::deleteAll("op_ko_id = ".$model->op_ko_id))?true:false;
                            $success_4 = (\app\models\TApproval::deleteAll("reff_no = '".$model->kode."'"))?true:false;
							$modSpms = \app\models\TSpmKo::find()->where(['op_ko_id'=>$model->op_ko_id])->all();
							if((count($modSpms)>0) && $model->jenis_produk != "JasaKD" && $model->jenis_produk != "JasaGesek" && $model->jenis_produk != "JasaMoulding" ){
								foreach($modSpms as $i => $spm){
									$modSPMDetailOld = \app\models\TSpmKoDetail::find()->where("spm_ko_id = ".$spm->spm_ko_id)->all();
									if(count($modSPMDetailOld)>0){
										$success_5 = (\app\models\TSpmKoDetail::deleteAll("spm_ko_id = ".$spm->spm_ko_id))?true:false;
										foreach($_POST['TOpKoDetail'] as $ii => $detail){
											$modSpmDetail = new \app\models\TSpmKoDetail();
											$modSpmDetail->attributes = $detail;
											$modSpmDetail->spm_ko_id = $spm->spm_ko_id;
											
											foreach($modSPMDetailOld as $iii => $spmdetailold){
												if($spmdetailold->produk_id == $detail['produk_id']){
													$modSpmDetail->qty_besar_realisasi = $spmdetailold->qty_besar_realisasi;
													$modSpmDetail->satuan_besar_realisasi = $spmdetailold->satuan_besar_realisasi;
													$modSpmDetail->qty_kecil_realisasi = $spmdetailold->qty_kecil_realisasi;
													$modSpmDetail->satuan_kecil_realisasi = $spmdetailold->satuan_kecil_realisasi;
													$modSpmDetail->kubikasi_realisasi = $spmdetailold->kubikasi_realisasi;
													$modSpmDetail->harga_jual_realisasi = $spmdetailold->harga_jual_realisasi;
												}
											}
											if($modSpmDetail->validate()){
												if($modSpmDetail->save()){
													$success_5 &= true;
												}else{
													$success_5 = false;
												}
											}else{
												$success_5 = false;
											}
										}
									}
								}
							}
						}
						
						foreach($_POST['TOpKoDetail'] as $i => $detail){
							$modDetail = new \app\models\TOpKoDetail();
							$modDetail->attributes = $detail;
							$modDetail->op_ko_id = $model->op_ko_id;
							$modDetail->is_random = isset($detail['is_random'])?$detail['is_random']:false;
							if($modDetail->validate()){
								if($modDetail->save()){
									$success_2 &= true;
									if(!empty($detail['nomor_produksi_random'])){
										$sql = "SELECT *,t_terima_ko_kd.p AS panjang, t_terima_ko_kd.l AS lebar, t_terima_ko_kd.t AS tinggi FROM t_terima_ko_kd 
												JOIN t_terima_ko ON t_terima_ko.tbko_id = t_terima_ko_kd.tbko_id 
												WHERE t_terima_ko.nomor_produksi IN({$detail['nomor_produksi_random']}) 
												ORDER BY tbko_kd_id ASC";
										$masterRandom = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($masterRandom)>0){
											foreach($masterRandom as $ii => $modRand){
												$modOpRandom = new \app\models\TOpKoRandom();
												$modOpRandom->attributes = $modRand;
												$modOpRandom->p = $modRand['panjang'];
												$modOpRandom->l = $modRand['lebar'];
												$modOpRandom->t = $modRand['tinggi'];
												$modOpRandom->op_ko_detail_id = $modDetail->op_ko_detail_id;
												$modOpRandom->qty_kecil = $modRand['qty'];
												$modOpRandom->satuan_kecil = $modRand['qty_satuan'];
												$modOpRandom->kubikasi = $modRand['kapasitas_kubikasi'];
												if($modOpRandom->validate()){
													if($modOpRandom->save()){
														$success_6 &= true;
													}else{
														$success_6 = false;
													}
												}else{
													$success_6 = false;
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
                        
                        if(isset($_GET['edit'])){ // jika proses edit
                            // start penerimaan jasa
                            if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaMoulding" || $model->jenis_produk == "JasaGesek"){
                                if(isset($_POST['TTerimaJasa'])){
                                    foreach($_POST['TTerimaJasa'] as $i => $terima){
                                        $modTerimaJasa = new \app\models\TTerimaJasa();
                                        $modTerimaJasa->attributes = $terima;
                                        $modTerimaJasa->jenis = $model->jenis_produk;
                                        $modTerimaJasa->op_ko_id = $model->op_ko_id;
                                        $modTerimaJasa->satuan_kecil = "Pcs";
                                        $modTerimaJasa->op_ko_detail_id = $modDetail->op_ko_detail_id;
                                        if($modTerimaJasa->validate()){
                                            if($modTerimaJasa->save()){
                                                $success_7 &= true;
                                            }else{
                                                $success_7 = false;
                                            }
                                        }else{
                                            $success_7 = false;
                                        }
                                    }
                                }
                            }
                            // end terima jasa
                        }
						
						if($model->sistem_bayar == "Tempo"){
							$modTempo = new \app\models\TTempobayarKo();
							$modTempo->attributes = $_POST['TTempobayarKo'];
							$modTempo->op_ko_id = $model->op_ko_id;
							$modTempo->kode = \app\components\DeltaGenerator::tempoBayarKayuolahan();
							$modTempo->jenis_produk = $model->jenis_produk;
							if($modTempo->validate()){
								if($modTempo->save()){
									$success_3 = true;
								}else{
									$success_3 = false;
								}
							}else{
								$success_3 = false;
							}
						}else{
							$success_3 = true;
						}
						
						if(!empty($model->status)){
							$modApproval = new \app\models\TApproval();
							$modApproval->assigned_to = \app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
							$modApproval->reff_no = $model->kode;
							$modApproval->tanggal_berkas = $model->tanggal;
							$modApproval->level = 1;
							$modApproval->status = "Not Confirmed";
							if($modApproval->validate()){
								if($modApproval->save()){
									$success_4 = true;
								}else{
									$success_4 = false;
								}
							}else{
								$success_4 = false;
							}
						}else{
							$success_4 = true;
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
//				echo "<pre>5";
//				print_r($success_5);
//				echo "<pre>6";
//				print_r($success_6);
//				echo "<pre>7";
//				print_r($success_7);
//				exit;
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5 && $success_6 && $success_7) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'op_ko_id'=>$model->op_ko_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modTempo'=>$modTempo]);
	}
	
	public function actionSetCustomer(){
		if(\Yii::$app->request->isAjax){
			$cust_id = \Yii::$app->request->post('cust_id');
			$data = [];
			if(!empty($cust_id)){
				$model = \app\models\MCustomer::findOne($cust_id);
				if(!empty($model)){
					$data = $model->attributes;
				}
			}
			
			return $this->asJson($data);
		}
	}
	public function actionPickProduk(){
		if(\Yii::$app->request->isAjax){
			$produk_id = \Yii::$app->request->post('produk_id');
			$jns_produk = \Yii::$app->request->post('jns_produk');
			$data = [];
			if(!empty($produk_id)){
                if($jns_produk == "Limbah"){
                    $model = \app\models\MBrgLimbah::findOne($produk_id);
                    $data = (!empty($model))? $model->attributes:null;
                    $data['produk_id'] = $model->limbah_id;
                }else if($jns_produk == "JasaKD" || $jns_produk == "JasaGesek" || $jns_produk == "JasaMoulding"){
                    $model = \app\models\MProdukJasa::findOne($produk_id);
                    $data = (!empty($model))? $model->attributes:null;
                    $data['produk_id'] = $model->produk_jasa_id;
                }else{
                    $model = \app\models\MBrgProduk::findOne($produk_id);
                    $data = (!empty($model))? $model->attributes:null;
                }
			}
			return $this->asJson($data);
		}
	}
	public function actionSetVal(){
		if(\Yii::$app->request->isAjax){
			$cust_id = \Yii::$app->request->post('cust_id');
			$jns_produk = \Yii::$app->request->post('jns_produk');
			$op_ko_id = \Yii::$app->request->post('op_ko_id');
			$data = [];
			$data['sisa_piutang']= 0;
			$data['op_aktif']= 0;
			$data['sisa_plafon']= 0;
			$data['maks_plafon']= 0;
			$data['top_hari']= 0;
			$data['maks_top_hari']= 0;
			if(!empty($cust_id)){
				$model = \app\models\MCustomer::findOne($cust_id);
				if(!empty($model)){
					$data['cust'] = $model->attributes;
					$modTop = \app\models\MCustTop::findOne(['cust_id'=>$cust_id,'custtop_jns'=>$jns_produk]);
					if(!empty($modTop)){
						$data['top_hari']= $modTop->custtop_top;
						$data['maks_top_hari']= $modTop->custtop_top;
					}
					$data['sisa_piutang']= \app\models\MCustomer::getSisaPiutang($cust_id);
					$data['op_aktif']= \app\models\MCustomer::getOPAktif($cust_id);
					$data['sisa_plafon']= \app\models\MCustomer::getSisaPlafon($cust_id) - $data['op_aktif'];
					$data['maks_plafon']= $model->cust_max_plafond;
					if(!empty($op_ko_id)){
						$modTempo = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$op_ko_id]);
						if(!empty($modTempo)){
							$data['sisa_piutang']= $modTempo->sisa_piutang;
							$data['op_aktif']= $modTempo->op_aktif;
							$data['sisa_plafon']= $modTempo->sisa_plafon;
							$data['maks_plafon']= $modTempo->maks_plafon;
							$data['top_hari']= $modTempo->top_hari;
							$data['maks_top_hari']= $modTempo->maks_top_hari;
						}
					}
				}
			}
			$data['sisa_plafon'] = ($data['sisa_plafon']>0)?$data['sisa_plafon']:0;
			return $this->asJson($data);
		}
	}
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new \app\models\TOpKoDetail();
            $modProduk = new \app\models\MBrgProduk();
            $jns_produk = Yii::$app->request->post('jns_produk');
            $modDetail->harga_jual = 0;
            $modDetail->subtotal = 0;
            $data['item'] = $this->renderPartial('_addItem',['modDetail'=>$modDetail,'modProduk'=>$modProduk,'jns_produk'=>$jns_produk]);
            return $this->asJson($data);
        }
    }
    public function actionAddItemTerima(){
        if(\Yii::$app->request->isAjax){
            $op_ko_id = Yii::$app->request->post('op_ko_id');
            $model = \app\models\TOpKo::findOne($op_ko_id);
            $modDetail = new \app\models\TTerimaJasa();
            $data['item'] = $this->renderPartial('_addItemTerima',['modOp'=>$model,'modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }
	public function actionFindProdukActive(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$jns_produk = Yii::$app->request->get('type');
			$notin = json_decode( Yii::$app->request->get('notin') );
			$data = [];
			if(!empty($notin)){
				$notin = 'AND '.( ($jns_produk == "Limbah")?"m_brg_limbah.limbah_id":"m_brg_produk.produk_id" ).' NOT IN('.implode(", ", $notin).')';
			}else{
				$notin = "";
			}
			if(!empty($term)){
                if($jns_produk == "Limbah"){
                    $query = "
                        SELECT m_brg_limbah.limbah_id AS produk_id, m_brg_limbah.limbah_kode AS produk_kode, CONCAT('(',m_brg_limbah.limbah_produk_jenis,') ',m_brg_limbah.limbah_nama) AS produk_nama FROM m_brg_limbah
                        WHERE (m_brg_limbah.limbah_kode ILIKE '%".$term."%' OR m_brg_limbah.limbah_nama ILIKE '%".$term."%') AND m_brg_limbah.active IS TRUE
                        ORDER BY m_brg_limbah.limbah_id ASC 
                        ;
                    ";
                }else if($jns_produk == "JasaKD" || $jns_produk == "JasaGesek" || $jns_produk == "JasaMoulding"){
                    $query = "
                        SELECT m_produk_jasa.produk_jasa_id AS produk_id, m_produk_jasa.kode AS produk_kode, CONCAT('(',m_produk_jasa.jenis,') ',m_produk_jasa.nama) AS produk_nama FROM m_produk_jasa
                        WHERE (m_produk_jasa.kode ILIKE '%".$term."%' OR m_produk_jasa.nama ILIKE '%".$term."%') AND m_produk_jasa.active IS TRUE
                        ORDER BY m_produk_jasa.produk_jasa_id ASC 
                        ;
                    ";
                }else{
                    $query = "
                        SELECT m_brg_produk.produk_id, m_brg_produk.produk_kode, m_brg_produk.produk_nama FROM h_persediaan_produk
                        JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
                        WHERE ".(!empty($term)?"(produk_kode ILIKE '%".$term."%' OR produk_nama ILIKE '%".$term."%')":'')." AND m_brg_produk.active IS TRUE AND produk_group = '".$jns_produk."' ".$notin." 
                        GROUP BY m_brg_produk.produk_id, m_brg_produk.produk_kode, m_brg_produk.produk_nama
                        HAVING SUM(in_qty_palet-out_qty_palet) > 0
                        ORDER BY m_brg_produk.produk_id ASC 
                        ;
                    ";
                }
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['produk_id'], 'text'=>$val['produk_kode']." - ".$val['produk_nama']];
					}
				}
			}
            return $this->asJson($data);
        }
    }
	function actionSetItem(){
		if(\Yii::$app->request->isAjax){
            $produk_id = Yii::$app->request->post('produk_id');
            $jns_produk = Yii::$app->request->post('jns_produk');
			$data['random'] = NULL;
            if(!empty($produk_id)){
                if($jns_produk=="Limbah"){
                    $data['produk'] = \app\models\MBrgLimbah::findOne($produk_id);
                }else if($jns_produk=="JasaKD" || $jns_produk=="JasaGesek" || $jns_produk=="JasaMoulding"){
                    $data['produk'] = \app\models\MProdukJasa::findOne($produk_id);
                }else{
                    $data['random'] = $this->getRandom($produk_id);
                    $data['produk'] = \app\models\MBrgProduk::findOne($produk_id);
                    $data['availablestock'] = \app\models\HPersediaanProduk::getCurrentStockPerProduk($produk_id);
                    $data['hargahpp'] = \app\models\MHargaProduk::getHargaCurrentEndUser($produk_id,'harga_hpp');
                    $data['harga'] = \app\models\MHargaProduk::getHargaCurrentEndUser($produk_id,'harga_enduser');
                }
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }
	
	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $op_ko_id = Yii::$app->request->post('op_ko_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
			$data['random'] = NULL;
            if(!empty($op_ko_id)){
                $model = \app\models\TOpKo::findOne($op_ko_id);
                $modDetails = \app\models\TOpKoDetail::find()->where(['op_ko_id'=>$op_ko_id])->all();
            }else{
                $model = [];
                $modDetails = [];
            }
            $data['html'] = '';
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
					if(!empty($edit)){
						$modProduk = \app\models\MBrgProduk::findOne($detail->produk_id);
						$detail->harga_jual = \app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga_jual);
						$detail->qty_kecil_perpalet = ($detail->opKo->jenis_produk == "Limbah" || $detail->opKo->jenis_produk == "JasaKD" || $detail->opKo->jenis_produk == "JasaGesek" || $detail->opKo->jenis_produk == "JasaMoulding")?"": $detail->produk->produk_qty_satuan_kecil;
						$detail->kubikasi_perpalet = ($detail->opKo->jenis_produk == "Limbah" || $detail->opKo->jenis_produk == "JasaKD" || $detail->opKo->jenis_produk == "JasaGesek" || $detail->opKo->jenis_produk == "JasaMoulding")?0: $detail->produk->kapasitas_kubikasi;
						$modRandom = Yii::$app->db->createCommand("SELECT nomor_produksi FROM t_op_ko_random WHERE op_ko_detail_id = {$detail->op_ko_detail_id} GROUP BY nomor_produksi")->queryAll();
						if(!empty($modRandom)){
							foreach($modRandom as $ii => $rand){
								$detail->nomor_produksi_random[] = "'".$rand['nomor_produksi']."'";
							}
							$detail->nomor_produksi_random = implode(",", $detail->nomor_produksi_random);
							$detail->is_random = "1";
						}else{
							$detail->is_random = "0";
						}
						$data['random'] = $this->getRandom($detail->produk_id);
						$data['html'] .= $this->renderPartial('_addItem',['modDetail'=>$detail,'i'=>$i,'edit'=>$edit,'modProduk'=>$modProduk,'jns_produk'=>$model->jenis_produk]);
					}else{
						$modRandom = \app\models\TOpKoRandom::find()->where("op_ko_detail_id = ".$detail->op_ko_detail_id)->all();
						if(count($modRandom)>0){
							foreach($modRandom as $ii => $random){
								$no = $i+$ii;
								$data['html'] .= $this->renderPartial('_addItemAfterSaveRandom',['modDetail'=>$detail,'i'=>$no,'random'=>$random]);
							}
						}else{
							$data['html'] .= $this->renderPartial('_addItemAfterSave',['modDetail'=>$detail,'i'=>$i,'modRandom'=>$modRandom]);
						}
						
					}
                }
            }
            return $this->asJson($data);
        }
    }
    
    function actionGetItemTerima(){
		if(\Yii::$app->request->isAjax){
            $op_ko_id = Yii::$app->request->post('op_ko_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
            $model = \app\models\TOpKo::findOne($op_ko_id);
            $modDetails = \app\models\TTerimaJasa::find()->where("op_ko_id = ".$model->op_ko_id)->orderBy("terima_jasa_id ASC")->all();
            $data['html'] = '';
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
                    $detail['tanggal'] = \app\components\DeltaFormatter::formatDateTimeForUser2($detail['tanggal']);
					$data['html'] .= $this->renderPartial('_addItemTerima',['modOp'=>$model,'modDetail'=>$detail,'edit'=>$edit]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function getRandom($produk_id){
		$return = NULL;
		$return['total_palet'] = 0;
		$return['total_qty'] = 0;
		$return['total_kubikasi'] = 0;
		$sqlrandom = "	SELECT nomor_produksi, sum(t_terima_ko_kd.qty) AS qty_kecil, sum(kapasitas_kubikasi) AS kubikasi from t_terima_ko_kd 
						LEFT JOIN t_terima_ko ON t_terima_ko.tbko_id = t_terima_ko_kd.tbko_id 
						WHERE t_terima_ko.produk_id = ".$produk_id." GROUP BY nomor_produksi";
		$random = Yii::$app->db->createCommand($sqlrandom)->queryAll();
		if(count($random)>0){
			$return['total_palet'] = count($random);
			foreach($random as $i => $rand){
				$return['total_qty'] += $rand['qty_kecil'];
				$return['total_kubikasi'] += $rand['kubikasi'];
			}
		}
		return $return;
	}
	
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
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
									$param['table'].'.cancel_transaksi_id',
									'MAX(t_spm_ko.spm_ko_id) AS spm_ko_id',
									$param['table'].'.status',
									't_approval.status AS statusapprove',
									'nota_penjualan_id'
									];
				$param['join']= ['JOIN m_sales ON m_sales.sales_id = '.$param['table'].'.sales_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  LEFT JOIN t_spm_ko ON t_spm_ko.op_ko_id = '.$param['table'].'.op_ko_id
								  LEFT JOIN t_approval ON t_approval.reff_no = t_op_ko.kode
								  LEFT JOIN t_nota_penjualan ON t_nota_penjualan.spm_ko_id = t_spm_ko.spm_ko_id
									'];
				$param['group'] = "GROUP BY ".$param['table'].".op_ko_id,
											".$param['table'].".kode, 
											".$param['table'].".jenis_produk,
											".$param['table'].".tanggal,
											m_sales.sales_nm,
											".$param['table'].".sistem_bayar,
											".$param['table'].".tanggal_kirim,
											m_customer.cust_an_nama,
											m_customer.cust_pr_nama,
											".$param['table'].".cancel_transaksi_id,
											".$param['table'].".status,
											t_approval.status,
											nota_penjualan_id";
				$param['where'] = "t_op_ko.cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionPrintOP(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TOpKo::findOne($_GET['id']);
		$modDetail = \app\models\TOpKoDetail::find()->where(['op_ko_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'ORDER PENJUALAN');
        if($model->jenis_produk == "Limbah"){
//            echo "Limbah Tidak ada print!"; exit;
        }
		if($caraprint == 'PRINT'){
			return $this->render('printOP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printOP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printOP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}
	
	public function actionFindOP(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT t_op_ko.op_ko_id, t_op_ko.kode AS kode FROM t_op_ko 
                    LEFT JOIN t_spm_ko ON t_spm_ko.op_ko_id = t_op_ko.op_ko_id
					WHERE t_op_ko.kode ilike '%{$term}%' AND t_op_ko.cancel_transaksi_id IS NULL 
						AND t_op_ko.kode NOT IN( SELECT reff_no FROM t_approval WHERE status != 'APPROVED' )
                        AND t_spm_ko.op_ko_id IS NULL
					ORDER BY t_op_ko.created_at";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'op_ko_id', 'kode');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['op_ko_id'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	
	public function actionCancelTransaksi($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TOpKo::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_op_ko
                    $success_3 = true; // t_tempobayar_ko
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $model->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
							$model->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
                            if($model->validate()){
								$success_2 = $model->save();
								
								$modTempo = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);
								if(!empty($modTempo)){
									$modTempo->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
									if($modTempo->validate()){
										$success_3 = $modTempo->save();
									}
								}
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel); 
                    }
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					echo "<pre>";
//					print_r($success_3);
//					exit;
                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Transaksi Berhasil di Batalkan');
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
			
			return $this->renderAjax('cancelTransaksi',['model'=>$model,'modCancel'=>$modCancel]);
		}
	}
	
	public function actionProdukInStock($disableAction=null,$tr_seq=null,$jenis_produk=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
				$param['table']= \app\models\HPersediaanProduk::tableName();
				$param['pk']= \app\models\HPersediaanProduk::primaryKey()[0];
				$param['column'] = ['m_brg_produk.produk_id','produk_group','produk_kode','produk_nama','produk_dimensi'];
				$param['group'] = "GROUP BY m_brg_produk.produk_id, produk_group, produk_kode, produk_nama, produk_dimensi";
				$param['join']= ['JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id'];
				$param['having'] = "HAVING SUM(in_qty_palet-out_qty_palet) > 0";
                if(!empty($jenis_produk)){
                    $param['where'] = "produk_group = '$jenis_produk'";
                }
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('produkInStock',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq,'jenis_produk'=>$jenis_produk]);
		}
	}
    
	public function actionOpenlimbah($disableAction=null,$tr_seq=null,$jenis_produk=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
				$param['table']= \app\models\MBrgLimbah::tableName();
				$param['pk']= \app\models\MBrgLimbah::primaryKey()[0];
				$param['column'] = ['m_brg_limbah.limbah_id',"CONCAT(limbah_kelompok,' (',limbah_produk_jenis,')') AS limbah_group",'limbah_kode','limbah_nama','limbah_satuan_muat'];
				$param['where'] = "active IS TRUE";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('openlimbah',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq,'jenis_produk'=>$jenis_produk]);
		}
	}
    
	public function actionOpenjasa($disableAction=null,$tr_seq=null,$jenis_produk=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
                if($jenis_produk=="JasaKD"){
                    $jenis = "JASA KD";
                }else if($jenis_produk=="JasaGesek"){
                    $jenis = "JASA GESEK";
                }else if($jenis_produk=="JasaMoulding"){
                    $jenis = "JASA MOULDING";
                }
				$param['table']= \app\models\MProdukJasa::tableName();
				$param['pk']= \app\models\MProdukJasa::primaryKey()[0];
				$param['column'] = ['produk_jasa_id',"jenis",'kode','nama','satuan','keterangan'];
				$param['where'] = "active IS TRUE AND jenis = '{$jenis}'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('openjasa',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq,'jenis_produk'=>$jenis_produk]);
		}
	}
	
	public function actionListRandom($tr_seq=null,$produk_id=null,$nomor_produksi_random=null){
		if(\Yii::$app->request->isAjax){
			$sql = "SELECT t_terima_ko.nomor_produksi, sum(t_terima_ko_kd.qty) AS qty_kecil, sum(kapasitas_kubikasi) AS kubikasi from t_terima_ko_kd 
					LEFT JOIN t_terima_ko ON t_terima_ko.tbko_id = t_terima_ko_kd.tbko_id 
					LEFT JOIN h_persediaan_produk ON h_persediaan_produk.nomor_produksi = t_terima_ko.nomor_produksi 
					WHERE t_terima_ko.produk_id = $produk_id GROUP BY t_terima_ko.nomor_produksi 
					HAVING SUM(in_qty_kecil-out_qty_kecil)>0 ORDER BY t_terima_ko.nomor_produksi ASC";
			$models = Yii::$app->db->createCommand($sql)->queryAll();
			$modProduk = \app\models\MBrgProduk::findOne($produk_id);
			$nomor_produksi_random = str_replace("'", "", explode(",", $nomor_produksi_random));
			return $this->renderAjax('random',['models'=>$models,'tr_seq'=>$tr_seq,'produk_id'=>$produk_id,'modProduk'=>$modProduk,'nomor_produksi_random'=>$nomor_produksi_random]);
		}
	}
	
	public function actionGetRandom(){
		if(\Yii::$app->request->isAjax){
			$nomor_produksi = Yii::$app->request->post("nomor_produksi");
			$data['html'] = "";
			$data['tot_qty'] = 0;
			$data['tot_kubikasi'] = 0;
			if(!empty($nomor_produksi)){
				$sql = "SELECT t_terima_ko_kd.*, t_terima_ko.nomor_produksi FROM t_terima_ko_kd JOIN t_terima_ko ON t_terima_ko.tbko_id = t_terima_ko_kd.tbko_id WHERE t_terima_ko.nomor_produksi IN($nomor_produksi) ORDER BY tbko_kd_id ASC";
				$models = Yii::$app->db->createCommand($sql)->queryAll();
				
				if(count($models)>0){
					foreach($models as $i => $model){
						$data['html'] .= "<tr>";
						$data['html'] .= "<td><center>".($i+1)."</center></td>";
						$data['html'] .= "<td>".$model['nomor_produksi']."</td>";
						$data['html'] .= "<td>".$model['t'].$model['t_satuan']." x ".$model['l'].$model['l_satuan']." x ".$model['p'].$model['p_satuan']."</td>";
						$data['html'] .= "<td style='text-align:center;'>".$model['qty']."</td>";
						$data['html'] .= "<td style='text-align:right;'>".$model['kapasitas_kubikasi']."</td>";
						$data['html'] .= "</tr>";
						$data['tot_qty'] += $model['qty'];
						$data['tot_kubikasi'] += $model['kapasitas_kubikasi'];
					}
				}
			}
			return $this->asJson($data);
		}
	}
	
}
