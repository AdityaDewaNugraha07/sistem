<?php

namespace app\modules\exim\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PackinglistController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){ 
        $model = new \app\models\TPackinglist(['scenario'=> \app\models\TPackinglist::SCENARIO_PACKINGLIST_EXIM]);
		$model->disetujui = \app\components\Params::DEFAULT_PEGAWAI_ID_EKO_NOWO;
		$model->total_container = 0;
		$model->total_bundles = 0;
		$model->total_pcs = 0;
		$model->total_volume = 0;
		$model->total_gross_weight = 0;
		$model->total_nett_weight = 0;
        $modContainer = new \app\models\TPackinglistContainer();
        $modOpEx = new \app\models\TOpExport();
        $modBuyer = new \app\models\MCustomer();
        if(isset($_GET['packinglist_id'])){
            $model = \app\models\TPackinglist::findOne($_GET['packinglist_id']);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modOpEx = \app\models\TOpExport::findOne($model->op_export_id);
        }
		
        if( Yii::$app->request->post('TPackinglist')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_packinglist
                $success_2 = true; // t_packinglist_container
                $success_3 = true; // t_invoices
				$model = \app\models\TPackinglist::findOne($_POST['TPackinglist']['packinglist_id']);
                $model->load(\Yii::$app->request->post());
                if($model->validate()){
                    if($model->save()){
						$success_1 = true;
						if(count($_POST['TPackinglistContainer'])>0){
							foreach($_POST['TPackinglistContainer'] as $i => $container){
								$modContainers = \app\models\TPackinglistContainer::find()->where(['packinglist_id'=>$model->packinglist_id,'container_no'=>$container['container_no']])->all();
								if(count($modContainers)>0){
									foreach($modContainers as $i => $modCont){
										$modCont->attributes = $container;
										if($modCont->validate()){
											if($modCont->save()){
												$success_2 &= true;
											}else{
												$success_2 = false;
											}
										}else{
											$success_2 = false;
										}
									}
								}else{
									$success_2 = false; $errmsg = "Data Container Not Found!";  
								}
							}
						}else{
							$success_2 = false;
						}
						
						$modInvoice = \app\models\TInvoice::findOne(['packinglist_id'=>$model->packinglist_id]);
						if(!empty($modInvoice)){
							$modInvoice->nomor = $model->nomor;
							if($modInvoice->validate()){
								if($modInvoice->save()){
									$success_3 = true;
								}else{
									$success_3 = false;
								}
							}else{
								$success_3 = false;
							}
						}
						
                    }else{
						$success_1 = false;
					}
                }else{
					$success_1 = false;
				}
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>3";
//				print_r($success_3);
//				exit;
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'packinglist_id'=>$model->packinglist_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modBuyer'=>$modBuyer,'modContainer'=>$modContainer,'modOpEx'=>$modOpEx]);
	}
	
	public function actionFindOP(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT * FROM t_op_export 
					JOIN m_customer ON m_customer.cust_id = t_op_export.cust_id
					WHERE nomor_kontrak ilike '%{$term}%' AND cancel_transaksi_id IS NULL
					ORDER BY t_op_export.created_at DESC";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['op_export_id'], 'text'=>$val['nomor_kontrak']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	public function actionMasterOpex(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-master'){
				$param['table'] = \app\models\TOpExport::tableName();
				$param['pk']= $param['table'].".".\app\models\TOpExport::primaryKey()[0];
				$param['column'] = [$param['table'].'.op_export_id','nomor_kontrak','tanggal','shipment_to','goods_description','payment_method'];
				$param['where'] = "cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('masterOpex');
		}
	}
	public function actionMasterProforma(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPackinglist::tableName();
				$param['pk']= $param['table'].".".\app\models\TPackinglist::primaryKey()[0];
				$param['column'] = [$param['table'].'.packinglist_id',
									$param['table'].'.jenis_produk',
									't_op_export.nomor_kontrak',
									$param['table'].'.kode',
									$param['table'].'.revisi_ke',
									$param['table'].'.nomor',
									$param['table'].'.tanggal',
									'm_customer.cust_an_nama',
									'm_customer.cust_an_alamat',
									$param['table'].'.total_container',
									$param['table'].'.total_bundles',
									$param['table'].'.total_volume',
									$param['table'].'.status AS packinglist_status',
									't_approval.status',
									$param['table'].'.cancel_transaksi_id'];
				$param['join']= ['JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  JOIN t_op_export ON t_op_export.op_export_id = '.$param['table'].'.op_export_id
								  JOIN t_approval ON t_approval.reff_no = t_packinglist.kode AND t_approval.assigned_to = t_packinglist.mengetahui'];
				$param['where']= $param['table'].".cancel_transaksi_id IS NULL AND t_approval.status = '". \app\models\TApproval::STATUS_APPROVED."' AND t_packinglist.status = 'PROFORMA'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('masterProforma');
        }
	}
	public function actionMasterFinal(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPackinglist::tableName();
				$param['pk']= $param['table'].".".\app\models\TPackinglist::primaryKey()[0];
				$param['column'] = [$param['table'].'.packinglist_id',
									$param['table'].'.jenis_produk',
									't_op_export.nomor_kontrak',
									$param['table'].'.kode',
									$param['table'].'.revisi_ke',
									$param['table'].'.nomor',
									$param['table'].'.tanggal',
									'm_customer.cust_an_nama',
									'm_customer.cust_an_alamat',
									$param['table'].'.total_container',
									$param['table'].'.total_bundles',
									$param['table'].'.total_volume',
									$param['table'].'.status AS packinglist_status',
									't_approval.status',
									$param['table'].'.cancel_transaksi_id'];
				$param['join']= ['JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  JOIN t_op_export ON t_op_export.op_export_id = '.$param['table'].'.op_export_id
								  JOIN t_approval ON t_approval.reff_no = t_packinglist.kode AND t_packinglist.mengetahui = t_approval.assigned_to'];
				$param['where']= $param['table'].".cancel_transaksi_id IS NULL AND t_approval.status = '". \app\models\TApproval::STATUS_APPROVED."' AND t_packinglist.status = 'FINAL'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('masterFinal');
        }
	}
	public function actionSetOpEx(){
		if(\Yii::$app->request->isAjax){
			$packinglist_id = \Yii::$app->request->post('packinglist_id');
			$data = [];
			if(!empty($packinglist_id)){
				$modPackinglist = \app\models\TPackinglist::findOne($packinglist_id);
				$modOpEx = \app\models\TOpExport::findOne($modPackinglist->op_export_id);
				$modOpEx->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($modOpEx->tanggal);
				if(!empty($modPackinglist)){
					if(!empty($modPackinglist->nomor)){
						$data['nomor'] = $modPackinglist->nomor;
					}else{
						$data['nomor'] = \app\components\DeltaGenerator::nomorPackinglist(date("Y-m-d"),true);
					}
					$modPackinglist->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($modPackinglist->tanggal);
					$modPackinglist->tanggal_packinglistexim = \app\components\DeltaFormatter::formatDateTimeForUser2($modPackinglist->tanggal_packinglistexim);
					$modPackinglist->applicant_display = $modPackinglist->cust->cust_an_alamat;
					$modPackinglist->notify_display = !empty($modPackinglist->notify_party)?$modPackinglist->notifyParty->cust_an_alamat:"";
					$modPackinglist->etd = \app\components\DeltaFormatter::formatDateTimeForUser2($modPackinglist->etd);
					$modPackinglist->eta = \app\components\DeltaFormatter::formatDateTimeForUser2($modPackinglist->eta);
					
					$data['packinglist'] = $modPackinglist->attributes;
					$modSpm = \app\models\TSpmKo::find()->where("packinglist_id = ".$modPackinglist->packinglist_id." AND cancel_transaksi_id IS NULL")->one();
					if(!empty($modSpm)){
						$data['spm'] = $modSpm->attributes;
					}
                    $modAlias = \app\models\TAlias::find()->where(['reff_no'=>$modPackinglist->kode])->all();
                    if(count($modAlias)>0){
                        $modApproval = \app\models\TApproval::findOne(['reff_no'=>$modPackinglist->kode,'parameter1'=>'ALIAS PACKINGLIST']);
                        $status = ""; $reason = '';
                        if(!empty($modApproval)){
                            if($modApproval->status == \app\models\TApproval::STATUS_APPROVED){
                                $status = 'Approver : <span class="label label-success" style="font-size: 0.8rem; padding-top: 0px; padding-bottom: 0px;">'.$modApproval->status.' at '.$modApproval->tanggal_approve.'</span>';
                            }else if($modApproval->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
                                $status = 'Approver : <span class="label label-default" style="font-size: 0.8rem; padding-top: 0px; padding-bottom: 0px;">'.$modApproval->status.'</span>';
                            }else if($modApproval->status == \app\models\TApproval::STATUS_REJECTED){
                                $status = 'Approver : <span class="label label-danger" style="font-size: 0.8rem; padding-top: 0px; padding-bottom: 0px;">'.$modApproval->status.' at '.$modApproval->tanggal_approve.'</span>';
                                $reason = 'Reason : <i>'.\yii\helpers\Json::decode($modApproval->keterangan)['reason']."</i>";
                            }
                        }
                        $data['alias']['approver'] = !empty($modApproval->assignedTo->pegawai_nama)?$modApproval->assignedTo->pegawai_nama:"-";
                        $data['alias']['status'] = $status;
                        $data['alias']['reason'] = $reason;
                    }
				}
				
				$data['packinglist_html'] = $this->renderPartial('_packinglistoverview',['modPackinglist'=>$modPackinglist,'modOpEx'=>$modOpEx]);
				
			}
			
			return $this->asJson($data);
		}
	}
	
	public function actionSetContainer(){
		if(\Yii::$app->request->isAjax){
			$packinglist_id = \Yii::$app->request->post("packinglist_id");
			$data = []; $modOp=[]; $modPackinglist=[]; $modContainer=[]; $data['htmlcontainer']="" ;
			if($packinglist_id){
				$modPackinglist = \app\models\TPackinglist::findOne($packinglist_id);
				$modOp = \app\models\TOpExport::findOne($modPackinglist->op_export_id);
				if(!empty($modOp)){
					$data['op'] = $modOp->attributes;
					if(!empty($modPackinglist)){
						$modSpms = \app\models\TSpmKo::find()->where(['packinglist_id'=>$modPackinglist->packinglist_id])->andWhere("cancel_transaksi_id IS NULL")->all();
						$data['disabled_detail'] = ((count($modSpms)>0)?true:false);
						$data['packinglist'] = $modPackinglist->attributes;
						$jmlcontainer = \Yii::$app->db->createCommand("SELECT container_no,seal_no,order_kode,container_kode,gross_weight,nett_weight 
																		FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id}
																		GROUP BY container_no,seal_no,order_kode,container_kode,gross_weight,nett_weight 
																		ORDER BY container_no ASC")->queryAll();
						if(count($jmlcontainer)>0){
							foreach($jmlcontainer as $i => $container_no){
								$modContainer = \app\models\TPackinglistContainer::findOne(['packinglist_id'=>$modPackinglist->packinglist_id,'container_no'=>$container_no['container_no']]);
								$jenis_produk = $modOp->jenis_produk;
								if($modPackinglist->bundle_partition == 1){ // random
									$data['htmlcontainer'] .= $this->renderPartial('container_partition',['model'=>$modContainer,'modPackinglist'=>$modPackinglist,'jenis_produk'=>$jenis_produk,
																							'gross_weight'=>$container_no['gross_weight'],
																							'nett_weight'=>$container_no['nett_weight'],
																							'container_no'=>$container_no['container_no'],
																							'container_kode'=>$container_no['container_kode'],
																							'seal_no'=>$container_no['seal_no'],'i'=>$i]);
								}else{
									$data['htmlcontainer'] .= $this->renderPartial('container',['model'=>$modContainer,'modPackinglist'=>$modPackinglist,'jenis_produk'=>$modOp->jenis_produk,
																							'gross_weight'=>$container_no['gross_weight'],
																							'nett_weight'=>$container_no['nett_weight'],
																							'container_no'=>$container_no['container_no'],
																							'container_kode'=>$container_no['container_kode'],
																							'seal_no'=>$container_no['seal_no'],'i'=>$i]);
								}
							}
						}
					}
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionAddContainer(){
		if(\Yii::$app->request->isAjax){
			$jenis_produk = \Yii::$app->request->post('jenis_produk');
			$op_export_id = \Yii::$app->request->post('op_export_id');
			$gross_weight = \Yii::$app->request->post('gross_weight');
			$nett_weight = \Yii::$app->request->post('nett_weight');
			$bundle_partition = \Yii::$app->request->post('bundle_partition');
			$data = [];
			if(!empty($op_export_id)){
				$modContainer = new \app\models\TPackinglistContainer();
				if($bundle_partition != "true"){
					$data['html'] = $this->renderPartial('container',['model'=>$modContainer,'jenis_produk'=>$jenis_produk,'gross_weight'=>$gross_weight,'nett_weight'=>$nett_weight]);
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionAddBundle(){
		if(\Yii::$app->request->isAjax){
			$container_no = \Yii::$app->request->post('container_no');
			$table_id = \Yii::$app->request->post('table_id');
			$jenis_produk = \Yii::$app->request->post('jenis_produk');
			$grade = \Yii::$app->request->post('grade');
			$jenis_kayu = \Yii::$app->request->post('jenis_kayu');
			$glue = \Yii::$app->request->post('glue');
			$profil_kayu = \Yii::$app->request->post('profil_kayu');
			$kondisi_kayu = \Yii::$app->request->post('kondisi_kayu');
			$thick = \Yii::$app->request->post('thick');
			$thick_unit = \Yii::$app->request->post('thick_unit');
			$width = \Yii::$app->request->post('width');
			$width_unit = \Yii::$app->request->post('width_unit');
			$length = \Yii::$app->request->post('length');
			$length_unit = \Yii::$app->request->post('length_unit');
			$pcs = \Yii::$app->request->post('pcs');
			$volume = \Yii::$app->request->post('volume');
			$data = [];
			if(!empty($container_no)){
				$modContainer = new \app\models\TPackinglistContainer();
				$modContainer->grade = !empty($grade)?$grade:"";
				$modContainer->jenis_kayu = !empty($jenis_kayu)?$jenis_kayu:"";
				$modContainer->glue = !empty($glue)?$glue:"";
				$modContainer->profil_kayu = !empty($profil_kayu)?$profil_kayu:"";
				$modContainer->kondisi_kayu = !empty($kondisi_kayu)?$kondisi_kayu:"";
				$modContainer->thick = !empty($thick)?\app\components\DeltaFormatter::formatNumberForUserFloat($thick):"0";
				$modContainer->thick_unit = !empty($thick_unit)?$thick_unit:"";
				$modContainer->width = !empty($width)?\app\components\DeltaFormatter::formatNumberForUserFloat($width):"0";
				$modContainer->width_unit = !empty($width_unit)?$width_unit:"";
				$modContainer->length = !empty($length)?\app\components\DeltaFormatter::formatNumberForUserFloat($length):"0";
				$modContainer->length_unit = !empty($length_unit)?$length_unit:"";
				$modContainer->pcs = !empty($pcs)?\app\components\DeltaFormatter::formatNumberForUserFloat($pcs):"0";
				$modContainer->volume = !empty($volume)?\app\components\DeltaFormatter::formatNumberForUserFloat($volume):"0";
				$data['html'] = $this->renderPartial('bundle',['model'=>$modContainer,'table_id'=>$table_id,'jenis_produk'=>$jenis_produk]);
			}
			return $this->asJson($data);
		}
	}
	
	public function actionSetRandomTemplate($jenis_produk){
		if(\Yii::$app->request->isAjax){
			if((\Yii::$app->request->post('params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->post('params'),$form_params);
				$jenis_produk = \Yii::$app->request->get('jenis_produk');
				$data = [];
				if(!empty($jenis_produk)){
					$modContainer = new \app\models\TPackinglistContainer();
					$data['html'] = $this->renderPartial('container_partition',['model'=>$modContainer,'jenis_produk'=>$jenis_produk,'form_params'=>$form_params]);
				}
				return $this->asJson($data);
			}
			return $this->renderAjax('setrandomtemplate',['jenis_produk'=>$jenis_produk]);
        }
    }
	
	public function actionSetValueRandomAfterSave(){
		if(\Yii::$app->request->isAjax){
			$packinglist_id = \Yii::$app->request->post('packinglist_id');
			$data = [];
			$mod = \Yii::$app->db->createCommand("SELECT * FROM t_packinglist_container WHERE packinglist_id = ".$packinglist_id)->queryAll();
			if(count($mod)>0){
				$data = $mod;
			}
			return $this->asJson($data);
		}
	}
	
	public function actionCheckSPMExport(){
		if(\Yii::$app->request->isAjax){
			$data = [];
			$sql = "SELECT packinglist_id FROM t_spm_ko WHERE jenis_penjualan = 'export' AND cancel_transaksi_id IS NULL ORDER BY spm_ko_id DESC";
			$mod = Yii::$app->db->createCommand($sql)->queryAll();
			if(count($mod)>0){
				$data = $mod;
			}
			return $this->asJson($data);
		}
	}
	
	public function actionPrint(){
		$this->layout = '@views/layouts/metronic/print';
		if(!empty($_GET['id'])){
			$model = \app\models\TPackinglist::findOne($_GET['id']);
			$modOpEx = \app\models\TOpExport::findOne($model->op_export_id);
            $modApproval = \app\models\TApproval::findOne(['reff_no'=>$model->kode,'parameter1'=>'ALIAS PACKINGLIST']);
            if(!empty($modApproval)){
                if($modApproval->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
                    echo "<h3>Alias Belum di Konfirmasi oleh Approver</h3>"; exit;
                }
            }
			$caraprint = Yii::$app->request->get('caraprint');
			if($model->status == "PROFORMA"){
				$paramprint['judul'] = Yii::t('app', 'PROFORMA PACKING LIST');
			}else{
				$paramprint['judul'] = Yii::t('app', 'PACKING LIST');
			}
			$paramprint['judul2'] = $model->jenis_produk;
			if($caraprint == 'PRINT'){
				return $this->render('printPackinglist',['model'=>$model,'modOpEx'=>$modOpEx,'paramprint'=>$paramprint]);
			}else if($caraprint == 'PDF'){
				$pdf = Yii::$app->pdf;
				$pdf->options = ['title' => $paramprint['judul']];
				$pdf->filename = $paramprint['judul'].'.pdf';
				$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
				$pdf->content = $this->render('printPackinglist',['model'=>$model,'modOpEx'=>$modOpEx,'paramprint'=>$paramprint]);
				return $pdf->render();
			}else if($caraprint == 'EXCEL'){
				return $this->render('printPackinglist',['model'=>$model,'modOpEx'=>$modOpEx,'paramprint'=>$paramprint]);
			}
		}
	}
	public function actionPrintsi(){
		$this->layout = '@views/layouts/metronic/print';
		if(!empty($_GET['id'])){
			$model = \app\models\TPackinglist::findOne($_GET['id']);
			$modOpEx = \app\models\TOpExport::findOne($model->op_export_id);
			$modInvoice = \app\models\TInvoice::findOne(['packinglist_id'=>$model->packinglist_id]);
			$caraprint = Yii::$app->request->get('caraprint');
			$paramprint['judul'] = Yii::t('app', 'SHIPPING INSTRUCTION');
			$paramprint['judul2'] = $model->jenis_produk;
			if($caraprint == 'PRINT'){
				return $this->render('printShippingInstruction',['model'=>$model,'modOpEx'=>$modOpEx,'paramprint'=>$paramprint,'modInvoice'=>$modInvoice]);
			}else if($caraprint == 'PDF'){
				$pdf = Yii::$app->pdf;
				$pdf->options = ['title' => $paramprint['judul']];
				$pdf->filename = $paramprint['judul'].'.pdf';
				$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
				$pdf->content = $this->render('printShippingInstruction',['model'=>$model,'modOpEx'=>$modOpEx,'paramprint'=>$paramprint,'modInvoice'=>$modInvoice]);
				return $pdf->render();
			}else if($caraprint == 'EXCEL'){
				return $this->render('printShippingInstruction',['model'=>$model,'modOpEx'=>$modOpEx,'paramprint'=>$paramprint,'modInvoice'=>$modInvoice]);
			}
		}
	}
	
	public function actionSetAlias($packinglist_id){
		if(\Yii::$app->request->isAjax){
			$modPackinglist = \app\models\TPackinglist::findOne($packinglist_id);
			$model = new \app\models\TAlias();
			if( Yii::$app->request->post('TAlias')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = true; // t_alias
                    $success_2 = true; // t_approval
					$rawposts = Yii::$app->request->post('TAlias');
                    $posts = [];
                    foreach($rawposts['grade'] as $i => $grade){
                        array_push($posts, $grade);
                    }
                    foreach($rawposts['jenis_kayu'] as $i => $jenis_kayu){
                        array_push($posts, $jenis_kayu);
                    }
                    if(isset($rawposts['glue'])){
                        foreach($rawposts['glue'] as $i => $glue){
                            array_push($posts, $glue);
                        }
                    }
                    if(isset($rawposts['profil_kayu'])){
                        foreach($rawposts['profil_kayu'] as $i => $profil_kayu){
                            array_push($posts, $profil_kayu);
                        }
                    }
                    
					$models = \app\models\TAlias::find()->where("reff_no = '{$modPackinglist->kode}'")->all();
					if(count($models)>0){
						\app\models\TAlias::deleteAll("reff_no = '{$modPackinglist->kode}'");
					}
					if( count($posts)>0 ){
						foreach($posts as $i => $post){
							$model = new \app\models\TAlias();
							$model->attributes = $post;
							$model->value_alias = !empty($model->value_alias)?$model->value_alias:"";
							if($model->validate()){
								if($model->save()){
									$success_1 &= true;
								}
							}else{
								$success_1 = false;
							}
						}
					}
                    
                    // START Create Approval
                    $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$modPackinglist->kode,'parameter1'=>'ALIAS PACKINGLIST'])->all();
                    if(count($modelApproval)>0){ // edit mode
                        if(\app\models\TApproval::deleteAll(['reff_no'=>$modPackinglist->kode,'parameter1'=>'ALIAS PACKINGLIST'])){
                            $success_2 = $this->saveApproval($modPackinglist);
                        }
                    }else{ // insert mode
                        $success_2 = $this->saveApproval($modPackinglist);
                    }
                    // END Create Approval
					
//                    echo "<pre>";
//                    print_r($success_1);
//                    echo "<pre>";
//                    print_r($success_2);
//                    exit;
                    
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                        $data['callback'] = "$('#modal-setalias').modal('hide');";
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
			
			return $this->renderAjax('setAlias',['model'=>$model,
												'jenis_produk'=>$modPackinglist->jenis_produk,
												'modPackinglist'=>$modPackinglist]);
        }
    }
    
    public function saveApproval($model){
		$success = false;
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$modelApproval->parameter1 = "ALIAS PACKINGLIST";
		$success = $modelApproval->createApproval();
		return $success;
	}
	
	
	public function actionKonfirmsi($packinglist_id,$print=false){
		if(\Yii::$app->request->isAjax){
			$modCompany = \app\models\CCompanyProfile::findOne(\app\components\Params::DEFAULT_COMPANY_PROFILE);
			$model = \app\models\TPackinglist::findOne($packinglist_id);
			$modInvoice = \app\models\TInvoice::findOne(['packinglist_id'=>$model->packinglist_id]);
			$total_pcs = 0; $total_gross_weight = 0; $total_nett_weight=0;
			$sql = "SELECT container_no, container_kode, seal_no, gross_weight, nett_weight, container_size, SUM(pcs) AS pcs, SUM( ROUND( volume::numeric, 4)) AS volume
					FROM t_packinglist_container 
					WHERE t_packinglist_container.packinglist_id = '{$model->packinglist_id}' 
					GROUP BY 1,2,3,4,5,6 ORDER BY container_no ASC";
			$modDetails = Yii::$app->db->createCommand($sql)->queryAll();
			foreach($modDetails as $i => $detail){
				$total_pcs += $detail['pcs'];
				$total_gross_weight += $detail['gross_weight'];
				$total_nett_weight += $detail['nett_weight'];
			}
			if(!empty($model->data_si)){
				$data_si = \yii\helpers\Json::decode($model->data_si);
				$model->si_shipper = $data_si["si_shipper"];
				$model->si_consignee = $data_si["si_consignee"];
				$model->si_notify = $data_si["si_notify"];
				$model->si_gd_product = $data_si["si_gd_product"];
				$model->si_gd_sizegrade = $data_si["si_gd_sizegrade"];
				$model->si_gd_total = $data_si["si_gd_total"];
				$model->si_gdrepeater = $data_si["si_gdrepeater"];
				$model->si_gd_ket = $data_si["si_gd_ket"];
				$model->si_instruction = $data_si["si_instruction"];
			}else{
				$model->si_shipper = !empty($model->shipper)?strtoupper($modCompany->name)."\nJL. RAYA SEMARANG PURWODADI KM 16.5\nNO. 349 MRANGGEN, DEMAK. 59567\nJAWA TENGAH, INDONESIA":"";
				$model->si_consignee = "TO ORDER OF SHIPPER";
				$model->si_notify = !empty($model->notify_party)?strtoupper( $model->notifyParty->cust_an_nama )."\n".strtoupper( $model->notifyParty->cust_an_alamat ):strtoupper( $model->cust->cust_an_nama )."\n".strtoupper( $model->cust->cust_an_alamat );
				$model->si_gd_product = $model->goods_description;
				$model->si_gd_sizegrade = $this->getSizegrade($packinglist_id,$model->jenis_produk);
				if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
					$satuanbesar = "CRATES";
				}else{
					$satuanbesar = "BNDLS";
				}
				$model->si_gd_total = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_bundles)." {$satuanbesar} = ".
									  number_format($model->total_volume,4)." M3 = ".
									  \app\components\DeltaFormatter::formatNumberForUserFloat($total_pcs)." PIECES";
				$model->si_gdrepeater = ["SVLK NO."=>$model->svlk_no,
										"V-LEGAL NO."=>$model->vlegal_no,
										"HS CODE"=>$model->hs_code,
										"COUNTRY OF ORIGIN"=>$model->origin,
										"PRICE TERM"=>$modInvoice->term_of_price,
										"LC NUMBER"=>(($modInvoice->payment_method=="LC")?$modInvoice->payment_method_reff:""),
										"NETT WEIGHT"=> \app\components\DeltaFormatter::formatNumberForUserFloat($total_nett_weight)." KGS"];
				$model->si_gd_ket = "7 DAYS FREE TIME DETENTION & 14 DAYS FREE TIME DEMURRAGE AT DESTINATION";
				$model->si_instruction = "HEAVY DUTY CONTAINER\nCONTAINER MUST BE CLEAN, NO GREASE, NO LIVING INSECT, FREE BULK\nAND GOOD SHAPE (NO LEAK, NO RUST STAIN, NO DENTS, NO ANY DEFECT)";
			}
			if( Yii::$app->request->post('TPackinglist')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = true;
					$post = Yii::$app->request->post('TPackinglist');
					if( count($post)>0 ){
						foreach($post as $i => $po){
							if(!is_array($po)){
								$res[$i] = $po;
							}
						}
						$asd = [];
						foreach($post as $i => $po){
							if(is_array($po)){
								$asd[$po['label']] = $po['value'];
							}
						}
						$res['si_gdrepeater'] = $asd;
						$model->data_si = \yii\helpers\Json::encode($res);
						if($model->validate()){
							if($model->save()){
								$success_1 &= true;
							}
						}else{
							$success_1 = false;
						}
					}
					
//					echo "<pre>";
//					print_r($success_1);
//					exit;
                    
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                        $data['callback'] = "$('#modal-konfirmsi').modal('hide'); window.open('".\yii\helpers\Url::toRoute('/exim/packinglist/printsi')."?id={$packinglist_id}&caraprint=PRINT','','location=_new, width=1200px, scrollbars=yes')";
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
			return $this->renderAjax('konfirmsi',['model'=>$model,'modDetails'=>$modDetails,'total_pcs'=>$total_pcs,'total_gross_weight'=>$total_gross_weight,'total_nett_weight'=>$total_gross_weight]);
        }
    }
	
	public static function getSizeGrade($packinglist_id,$jenis_produk){
		$detailsizegrade = "";
		$tebal = ""; $lebar = ""; $panjang = "";
		$modtebal = Yii::$app->db->createCommand("SELECT thick, thick_unit FROM t_packinglist_container WHERE packinglist_id = {$packinglist_id} GROUP BY 1,2")->queryAll();
		if(count($modtebal)>0){
			if(count($modtebal)>1){
				foreach($modtebal as $i => $asdt){
					$tebal .= $asdt['thick'];
					if(count($modtebal)!=($i+1)){
						$tebal .= "/";
					}
				}
				$tebal .= " ".$modtebal[0]['thick_unit'];
			}else{
				$tebal = $modtebal[0]['thick']." ".$modtebal[0]['thick_unit'];
			}
		}
		$modlebar = Yii::$app->db->createCommand("SELECT width, width_unit FROM t_packinglist_container WHERE packinglist_id = {$packinglist_id} GROUP BY 1,2")->queryAll();
		if(count($modlebar)>0){
			if(count($modlebar)>1){
				foreach($modlebar as $i => $asdl){
					$lebar .= $asdl['width'];
					if(count($modlebar)!=($i+1)){
						$lebar .= "/";
					}
				}
				$lebar .= " ".$modlebar[0]['width_unit'];
			}else{
				$lebar = $modlebar[0]['width']." ".$modlebar[0]['width_unit'];
			}
		}
		$modpanjang = Yii::$app->db->createCommand("SELECT length, length_unit FROM t_packinglist_container WHERE packinglist_id = {$packinglist_id} GROUP BY 1,2")->queryAll();
		if(count($modpanjang)>0){
			if(count($modpanjang)>1){
				if(count($modpanjang)>2){
					$panjang = $modpanjang[0]['length']."~".$modpanjang[(count($modpanjang)-1)]['length']." ".$modpanjang[0]['length_unit'];
				}else{
					$panjang = $modpanjang[0]['length']."/".$modpanjang[1]['length']." ".$modpanjang[0]['length_unit'];
				}
			}else{
				$panjang = $modpanjang[0]['length']." ".$modpanjang[0]['length_unit'];
			}
		}
		$detailsizegrade = $tebal." X ".$lebar." X ".$panjang;
		$sqlgrade = "SELECT grade FROM t_packinglist_container WHERE packinglist_id = {$packinglist_id} GROUP BY 1";
		$detailgrades = Yii::$app->db->createCommand($sqlgrade)->queryAll();
		if(count($detailgrades)>0){
			$detailsizegrade = $detailsizegrade."\n";
			foreach($detailgrades as $i => $grade){
				$detailsizegrade .= (($jenis_produk == "Moulding")?"Grade ":"").$grade['grade'];
				if((($i+1) != count($detailgrades)) && (count($detailgrades) != 1)){
					$detailsizegrade .= " & ";
				}
			}
		}
		return $detailsizegrade;
	}
	
	public function actionLoadAlias(){
		if(\Yii::$app->request->isAjax){
			$packinglist_id = \Yii::$app->request->post('packinglist_id');
			$sorting = \Yii::$app->request->post('sorting');
			$data = []; $data['grade'] = ""; $data['jenis_kayu'] = ""; $data['glueprofil'] = "";  $model = new \app\models\TAlias();
			$modPackinglist = \app\models\TPackinglist::findOne($packinglist_id);
			if($modPackinglist->jenis_produk == "Moulding"){
				$data['glueprofil_label'] = "profil_kayu";
			}else{
				$data['glueprofil_label'] = "glue";
			}
			$sorting = (!empty($sorting)?"ORDER BY 1 $sorting":"");
			$modAliasGrade = \app\models\TAlias::find()->where("reff_no = '{$modPackinglist->kode}' AND alias_name = 'grade'")->all();
			if(count($modAliasGrade)>0 && empty($sorting)){
				$modGrade = \Yii::$app->db->createCommand("SELECT value_original AS grade  FROM t_alias WHERE reff_no = '{$modPackinglist->kode}' AND alias_name = 'grade' ORDER BY alias_id ASC")->queryAll();
			}else{
				$modGrade = \Yii::$app->db->createCommand("SELECT grade FROM t_packinglist_container WHERE packinglist_id = ".$packinglist_id." GROUP BY 1 $sorting")->queryAll();
			}
			$modAliasKayu = \app\models\TAlias::find()->where("reff_no = '{$modPackinglist->kode}' AND alias_name = 'jenis_kayu'")->all();
			if(count($modAliasKayu)>0 && empty($sorting)){
				$modKayu = \Yii::$app->db->createCommand("SELECT value_original AS jenis_kayu  FROM t_alias WHERE reff_no = '{$modPackinglist->kode}' AND alias_name = 'jenis_kayu' ORDER BY alias_id ASC")->queryAll();
			}else{
				$modKayu = \Yii::$app->db->createCommand("SELECT jenis_kayu FROM t_packinglist_container WHERE packinglist_id = ".$packinglist_id." GROUP BY 1 $sorting")->queryAll();
			}
			$modAlias = \app\models\TAlias::find()->where("reff_no = '{$modPackinglist->kode}' AND alias_name NOT IN('grade','jenis_kayu')")->all();
			if(count($modAlias)>0 && empty($sorting)){
				$modGlueProfil = \Yii::$app->db->createCommand("SELECT value_original AS {$data['glueprofil_label']}  FROM t_alias WHERE reff_no = '{$modPackinglist->kode}' AND alias_name NOT IN('grade','jenis_kayu') ORDER BY alias_id ASC")->queryAll();
			}else{
				$modGlueProfil = \Yii::$app->db->createCommand("SELECT {$data['glueprofil_label']} FROM t_packinglist_container WHERE packinglist_id = ".$packinglist_id." GROUP BY 1 $sorting")->queryAll();
			}
			if(count($modGrade)>0){
				foreach($modGrade as $i => $grade){
					$mod = \app\models\TAlias::findOne(["reff_no"=>$modPackinglist->kode,"alias_name"=>"grade","value_original"=>$grade['grade']]);
					if(!empty($mod)){
						$model->attributes = $mod->attributes;
						$model->alias_id = $mod->alias_id;
					}else{
						$model->reff_no = $modPackinglist->kode;
						$model->alias_name = 'grade';
						$model->value_original = $grade['grade'];
					}
					$data['grade'] .=	'<div class="form-group col-md-12" style="margin-bottom: 5px;">
                                                <div class="col-md-5">
                                                    '.\yii\helpers\Html::activeHiddenInput($model, "[grade][".($i)."]alias_id").
                                                      \yii\helpers\Html::activeHiddenInput($model, "[grade][".($i)."]reff_no").
                                                      \yii\helpers\Html::activeHiddenInput($model, "[grade][".($i)."]alias_name").'
                                                    '.\yii\helpers\Html::activeTextInput($model, "[grade][".($i)."]value_original",['class'=>'form-control','disabled'=>true]).'
                                                </div>
                                                <div class="col-md-1">==></div>
                                                <div class="col-md-6">
                                                    '.\yii\helpers\Html::activeTextInput($model, "[grade][".($i)."]value_alias",['class'=>'form-control','placeholder'=>'Input Alias '.$grade['grade']]).'
                                                </div>
                                            </div>';
				}
			}
			if(count($modKayu)>0){
				foreach($modKayu as $i => $kayu){
					$mod = \app\models\TAlias::findOne(["reff_no"=>$modPackinglist->kode,"alias_name"=>"jenis_kayu","value_original"=>$kayu['jenis_kayu']]);
					if(!empty($mod)){
						$model->attributes = $mod->attributes;
						$model->alias_id = $mod->alias_id;
					}else{
						$model->reff_no = $modPackinglist->kode;
						$model->alias_name = 'jenis_kayu';
						$model->value_original = $kayu['jenis_kayu'];
					}
					$data['jenis_kayu'] .=	'<div class="form-group col-md-12" style="margin-bottom: 5px;">
                                                <div class="col-md-5">
                                                    '.\yii\helpers\Html::activeHiddenInput($model, "[jenis_kayu][".($i)."]alias_id").
                                                      \yii\helpers\Html::activeHiddenInput($model, "[jenis_kayu][".($i)."]reff_no").
                                                      \yii\helpers\Html::activeHiddenInput($model, "[jenis_kayu][".($i)."]alias_name").'
                                                    '.\yii\helpers\Html::activeTextInput($model, "[jenis_kayu][".($i)."]value_original",['class'=>'form-control','disabled'=>true]).'
                                                </div>
                                                <div class="col-md-1">==></div>
                                                <div class="col-md-6">
                                                    '.\yii\helpers\Html::activeTextInput($model, "[jenis_kayu][".($i)."]value_alias",['class'=>'form-control','placeholder'=>'Input Alias '.$kayu['jenis_kayu']]).'
                                                </div>
                                            </div>';
				}
			}
			if(count($modGlueProfil)>0){
				foreach($modGlueProfil as $i => $glueprofil){
					$mod = \app\models\TAlias::findOne(["reff_no"=>$modPackinglist->kode,"alias_name"=>$data['glueprofil_label'],"value_original"=>$glueprofil[$data['glueprofil_label']]]);
					if(!empty($mod)){
						$model->attributes = $mod->attributes;
						$model->alias_id = $mod->alias_id;
					}else{
						$model->reff_no = $modPackinglist->kode;
						$model->alias_name = $data['glueprofil_label'];
						$model->value_original = $glueprofil[$data['glueprofil_label']];
					}
					$data['glueprofil'] .=	'<div class="form-group col-md-12" style="margin-bottom: 5px;">
                                                <div class="col-md-5">
                                                    '.\yii\helpers\Html::activeHiddenInput($model, "[".$data['glueprofil_label']."][".($i)."]alias_id").
                                                      \yii\helpers\Html::activeHiddenInput($model, "[".$data['glueprofil_label']."][".($i)."]reff_no").
                                                      \yii\helpers\Html::activeHiddenInput($model, "[".$data['glueprofil_label']."][".($i)."]alias_name").'
                                                    '.\yii\helpers\Html::activeTextInput($model, "[".$data['glueprofil_label']."][".($i)."]value_original",['class'=>'form-control','disabled'=>true]).'
                                                </div>
                                                <div class="col-md-1">==></div>
                                                <div class="col-md-6">
                                                    '.\yii\helpers\Html::activeTextInput($model, "[".$data['glueprofil_label']."][".($i)."]value_alias",['class'=>'form-control','placeholder'=>'Input Alias '.$glueprofil[$data['glueprofil_label']]]).'
                                                </div>
                                            </div>';
				}
			}
			
			return $this->asJson($data);
		}
	}
}
