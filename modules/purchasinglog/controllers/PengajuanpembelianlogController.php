<?php

namespace app\modules\purchasinglog\controllers;

use app\models\MKayu;
use Yii;
use app\controllers\DeltaBaseController;

class PengajuanpembelianlogController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TPengajuanPembelianlog();
        $model->kode = "Auto Generate";
        $model->tanggal = date("d/m/Y");
		$model->revisi = "0";
		$model->asuransi = true;
		$model->total_volume = 0;
        $model->status_fsc = 'FSC 100%';
		$model->by_kanit = \app\components\Params::DEFAULT_PEGAWAI_ID_SEKAR;
		$model->by_kadiv = \app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
		$model->by_gmopr = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
        //$model->by_gmpurch = \app\components\Params::DEFAULT_PEGAWAI_ID_TATANG;
        $model->by_gmpurch = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
		$model->by_dirut = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
		$model->by_owner = \app\components\Params::DEFAULT_PEGAWAI_ID_JENNY_CHANDRA;
		$model->by_kanit_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_SEKAR)->pegawai_nama;
		//$model->by_gmpurch_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_TATANG)->pegawai_nama;
		$model->by_gmpurch_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_ASENG)->pegawai_nama;
		$model->by_kadiv_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO)->pegawai_nama;
		$model->by_gmopr_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_ASENG)->pegawai_nama;
		$model->by_dirut_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
		$model->by_owner_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_JENNY_CHANDRA)->pegawai_nama;
		if(isset($_GET['pengajuan_pembelianlog_id'])){
			$model = \app\models\TPengajuanPembelianlog::findOne($_GET['pengajuan_pembelianlog_id']);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->kode_po = $model->logKontrak->kode;
			$model->volume_kontrak = \app\components\DeltaFormatter::formatNumberForUserFloat($model->volume_kontrak);
			$model->waktu_penyerahan_awal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->waktu_penyerahan_awal);
			$model->waktu_penyerahan_akhir = \app\components\DeltaFormatter::formatDateTimeForUser2($model->waktu_penyerahan_akhir);
			$model->nominal_dp = \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal_dp);
			$model->tanggal_bayar_dp = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_bayar_dp);
			$model->total_volume = \app\components\DeltaFormatter::formatNumberForUser($model->total_volume,2);
			$model->by_kanit_name = \app\models\MPegawai::findOne($model->by_kanit)->pegawai_nama;
			$model->by_kadiv_name = \app\models\MPegawai::findOne($model->by_kadiv)->pegawai_nama;
			$model->by_gmopr_name = \app\models\MPegawai::findOne($model->by_gmopr)->pegawai_nama;
			$model->by_gmpurch_name = \app\models\MPegawai::findOne($model->by_gmpurch)->pegawai_nama;
			$model->by_dirut_name = \app\models\MPegawai::findOne($model->by_dirut)->pegawai_nama;
			$model->by_owner_name = \app\models\MPegawai::findOne($model->by_owner)->pegawai_nama;
		}
        if( Yii::$app->request->post('TPengajuanPembelianlog')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pengajuan_pembelianlog
                $success_2 = true; // t_pengajuan_pembelianlog_detail
                $success_3 = false; // t_approval
                $success_4 = true; // map_permintaan_keputusan_logalam
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodePengajuanPembelianLog();
				}else{
					$modelOld = \app\models\TPengajuanPembelianlog::findOne($_POST['TPengajuanPembelianlog']['pengajuan_pembelianlog_id']);
					$detailOld = \app\models\TPengajuanPembelianlogDetail::find()->where("pengajuan_pembelianlog_id = ".$_POST['TPengajuanPembelianlog']['pengajuan_pembelianlog_id'])->all();
				}
                $model->status_fsc = Yii::$app->request->post('TPengajuanPembelianlog')['status_fsc'];
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if((isset($_GET['edit'])) && (isset($_GET['pengajuan_pembelianlog_id']))){
							$modDetail = \app\models\TPengajuanPembelianlogDetail::find()->where(['pengajuan_pembelianlog_id'=>$_GET['pengajuan_pembelianlog_id']])->all();
							if(count($modDetail)>0){
								\app\models\TPengajuanPembelianlogDetail::deleteAll(['pengajuan_pembelianlog_id'=>$_GET['pengajuan_pembelianlog_id']]);
							}
						}
						foreach($_POST['TPengajuanPembelianlogDetail'] as $i => $details){
							if(is_numeric($i)){
								foreach($details as $ii => $detail){
									if((is_array($detail))&&($ii!='total')){
										$modDetail = new \app\models\TPengajuanPembelianlogDetail();
										$modDetail->pengajuan_pembelianlog_id = $model->pengajuan_pembelianlog_id;
										$modDetail->tipe = $details['tipe'];
										$modDetail->kayu_id = $details['kayu_id'];
										$modDetail->diameter_cm = $ii;
										$modDetail->qty_batang = $detail['qty_batang'];
										$modDetail->qty_m3 = $detail['qty_m3'];
										$modDetail->harga = $detail['harga'];
										if($modDetail->validate()){
											if($modDetail->save()){
												$success_2 &= true;
											}else{
												$success_2 = false;
											}
										}else{
											$success_2 = false;
											$errmsg = $modDetail->errors;
										}
									}
								}
							}
						}
						// START Create Approval
						$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
						if(!empty($_POST['TPengajuanPembelianlog']['pengajuan_pembelianlog_id'])){
							if($modelOld->revisi < $model->revisi){
								$revisi = true;
							}else{
								$revisi = false;
							}
						}else{
							$revisi = false; // new insert
						}
						if(count($modelApproval)>0){
							if($revisi == true){
								if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
									$success_3 = $this->saveApproval($model,$_POST['TPengajuanPembelianlogDetail']);
									
									//Create History Revisi
									if(!empty($model->history_revisi)){
										$history = \yii\helpers\Json::decode($model->history_revisi);
										$qwe = [];
										foreach($detailOld as $i => $detold){
											$qwe[] = $detold->attributes;
										}
										$asd = $modelOld->attributes;
										$asd['t_pengajuan_pembelianlog_detail'] = $qwe;
										array_push($history, $asd);
									}else{
										$qwe = [];
										foreach($detailOld as $i => $detold){
											$qwe[] = $detold->attributes;
										}
										$asd = $modelOld->attributes;
										$asd['t_pengajuan_pembelianlog_detail'] = $qwe;
										$history[0] = $asd;
									}
									$model->history_revisi = \yii\helpers\Json::encode($history);
									$model->updateAll(["history_revisi"=>\yii\helpers\Json::encode($history)], "pengajuan_pembelianlog_id = ".$model->pengajuan_pembelianlog_id);
									//END Create
								}
							}else{
								$success_3 = true;
							}
						}else{ 
							$success_3 = $this->saveApproval($model,$_POST['TPengajuanPembelianlogDetail']); // new insert
						}
						// END Create Approval
                        
                        // Start insert map_permintaan_keputusan_logalam
                        if((isset($_GET['edit'])) && (isset($_GET['pengajuan_pembelianlog_id']))){
                            $modMap = \app\models\MapPermintaanKeputusanLogalam::find()->where("pengajuan_pembelianlog_id = ".$model->pengajuan_pembelianlog_id)->all();
                            if(count($modMap)){
                                \app\models\MapPermintaanKeputusanLogalam::deleteAll("pengajuan_pembelianlog_id = ".$model->pengajuan_pembelianlog_id);
                            }
						}
                        foreach($_POST['TPmr'] as $ii => $detPmr){
                            $modMap = new \app\models\MapPermintaanKeputusanLogalam();
                            $modMap->pmr_id = $detPmr['pmr_id'];
                            $modMap->pengajuan_pembelianlog_id = $model->pengajuan_pembelianlog_id;
                            if($modMap->validate()){
                                if($modMap->save()){
                                    $success_4 &= true;
                                }else{
                                    $success_4 = false;
                                }
                            }else{
                                $success_4 = false;
                            }
                        }
                        // end insert map_permintaan_keputusan_logalam
                        
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
                    return $this->redirect(['index','success'=>1,'pengajuan_pembelianlog_id'=>$model->pengajuan_pembelianlog_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?(implode(",", array_values($errmsg)[0])):Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model]);
	}
	
	public function saveApproval($model){
		$success = true;
//		$modelApproval = new \app\models\TApproval();
//		$modelApproval->assigned_to = $model->by_kanit;
//		$modelApproval->reff_no = $model->kode;
//		$modelApproval->tanggal_berkas = $model->tanggal;
//		$modelApproval->level = 1;
//		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
//		$success &= $modelApproval->createApproval();
		
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->by_gmpurch;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 2;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();
		
		$is_industri = false; $is_trading = false;
		foreach($_POST['TPengajuanPembelianlogDetail'] as $i => $detail){
			if($detail['tipe'] == "INDUSTRI"){
				$is_industri = true;
			}
		}
		foreach($_POST['TPengajuanPembelianlogDetail'] as $i => $detail){
			if($detail['tipe'] == "TRADING"){
				$is_trading = true;
			}
		}
		if($is_industri == true){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->by_gmopr;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 3;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		if($is_trading == true){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->by_kadiv;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 3;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->by_dirut;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 4;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();
        
        $modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->by_owner;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 5;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();
		
		return $success;
	}
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TPengajuanPembelianlogDetail();
			$last_tr = []; parse_str(\Yii::$app->request->post('last_tr'),$last_tr);
			$tipe = \Yii::$app->request->post('tipe');
			$model->tipe = strtoupper( $tipe );
			if(!empty($last_tr)){
				foreach($last_tr['TPengajuanPembelianlogDetail'] as $qwe){
					$last_tr = $qwe;
				}
			}
            $data['html'] = $this->renderPartial('_item',['model'=>$model,'last_tr'=>$last_tr,'tipe'=>$tipe]);
            return $this->asJson($data);
        }
    }
	
	function actionGetItemsById(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $edit = Yii::$app->request->post('edit');
			$modDetailIndustri = []; $modDetailTrading = [];
            $data = [];
            if(!empty($id)){
                $modDetailIndustri = \app\models\TPengajuanPembelianlogDetail::find()
								->select("pengajuan_pembelianlog_id, kayu_id, tipe")
								->groupBy("pengajuan_pembelianlog_id, kayu_id, tipe")
								->where(['pengajuan_pembelianlog_id'=>$id,"tipe"=>"INDUSTRI"])->all();
                $modDetailTrading = \app\models\TPengajuanPembelianlogDetail::find()
								->select("pengajuan_pembelianlog_id, kayu_id, tipe")
								->groupBy("pengajuan_pembelianlog_id, kayu_id, tipe")
								->where(['pengajuan_pembelianlog_id'=>$id,"tipe"=>"TRADING"])->all();
                $modMap = \app\models\MapPermintaanKeputusanLogalam::find()->where(['pengajuan_pembelianlog_id'=>$id])->all();
            }
            $data['html_industri'] = ''; $data['html_trading'] = ''; $data['html_pmr'] = ''; 
            if(count($modMap)>0){
                foreach($modMap as $i => $map){
                    $modPmr = \app\models\TPmr::findOne($map->pmr_id);
                    $total_m3 = \app\models\TPmrDetail::find()->select("SUM(qty_m3) AS total_m3")->where("pmr_id = ".$modPmr->pmr_id)->one();
                    $modPmr->total_m3 = $total_m3->total_m3;
                    $data['html_pmr'] .= $this->renderPartial('_itemPermintaan',['model'=>$modPmr,'i'=>$i,'edit'=>$edit]);
                }
            }
            if(count($modDetailIndustri)>0){
                foreach($modDetailIndustri as $i => $industri){
                    $data['html_industri'] .= $this->renderPartial('_item',['model'=>$industri,'i'=>$i,'edit'=>$edit,'tipe'=> $industri->tipe]);
                }
            }
            if(count($modDetailTrading)>0){
                foreach($modDetailTrading as $i => $trading){
                    $data['html_trading'] .= $this->renderPartial('_item',['model'=>$trading,'i'=>$i,'edit'=>$edit,'tipe'=> $trading->tipe]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
            $pick = \Yii::$app->request->get('pick');
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPengajuanPembelianlog::tableName();
				$param['pk']= $param['table'].".". \app\models\TPengajuanPembelianlog::primaryKey()[0];
				$param['column'] = [$param['table'].'.pengajuan_pembelianlog_id',
									"CONCAT(kode,'-',revisi) AS kode",
									$param['table'].'.tanggal',
									$param['table'].'.nomor_kontrak',
									$param['table'].'.volume_kontrak',
									'm_suplier.suplier_nm',
									$param['table'].'.asal_kayu',
									$param['table'].'.total_volume',
									'pegawai4.pegawai_nama AS by_gmpurch',
									'pegawai2.pegawai_nama AS by_kadiv',
									'pegawai3.pegawai_nama AS by_gmopr',
									'pegawai5.pegawai_nama AS by_dirut',
									'pegawai6.pegawai_nama AS by_owner',
									'(SELECT status FROM t_approval WHERE reff_no = t_pengajuan_pembelianlog.kode AND assigned_to = t_pengajuan_pembelianlog.by_gmpurch and level=2 group by 1) AS by_gmpurch_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pengajuan_pembelianlog.kode AND assigned_to = t_pengajuan_pembelianlog.by_kadiv and level=3 group by 1) AS by_kadiv_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pengajuan_pembelianlog.kode AND assigned_to = t_pengajuan_pembelianlog.by_gmopr and level=3 group by 1) AS by_gmopr_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pengajuan_pembelianlog.kode AND assigned_to = t_pengajuan_pembelianlog.by_dirut and level=4 group by 1) AS by_dirut_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pengajuan_pembelianlog.kode AND assigned_to = t_pengajuan_pembelianlog.by_owner and level=5 group by 1) AS by_owner_status',
									$param['table'].'.cancel_transaksi_id',
                                    $param['table'].'.suplier_id',
									];
				$param['join']= ['
								JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id 
								JOIN m_pegawai AS pegawai4 ON pegawai4.pegawai_id = '.$param['table'].'.by_gmpurch 
								JOIN m_pegawai AS pegawai2 ON pegawai2.pegawai_id = '.$param['table'].'.by_kadiv 
								JOIN m_pegawai AS pegawai3 ON pegawai3.pegawai_id = '.$param['table'].'.by_gmopr 
								JOIN m_pegawai AS pegawai5 ON pegawai5.pegawai_id = '.$param['table'].'.by_dirut 
								JOIN m_pegawai AS pegawai6 ON pegawai6.pegawai_id = '.$param['table'].'.by_owner 
								'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave',['pick'=>$pick]);
        }
    }
	
	public function actionAddMonitoring($pengajuan_pembelianlog_id){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TMonitoringPembelianlog();
			$model->pengajuan_pembelianlog_id = $pengajuan_pembelianlog_id;
			if( !empty($_POST) ){
				$post = []; parse_str(\Yii::$app->request->post('data'),$post);
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false; // t_monitoring_pembelianlog
					$success_2 = true; // t_monitoring_pembelianlog_detail
					if(!empty($post['TMonitoringPembelianlog']['monitoring_pembelianlog_id'])){
						$model = \app\models\TMonitoringPembelianlog::findOne($post['TMonitoringPembelianlog']['monitoring_pembelianlog_id']);
					}else{
						$model->kode = \app\components\DeltaGenerator::kodeMonitoringPembelianLog();
					}
					$model->attributes = $post['TMonitoringPembelianlog'];
					if($model->validate()){
						if($model->save()){
							$success_1 = true;
							if(!empty($post['TMonitoringPembelianlog']['monitoring_pembelianlog_id'])){
								\app\models\TMonitoringPembelianlogDetail::deleteAll("monitoring_pembelianlog_id = ".$post['TMonitoringPembelianlog']['monitoring_pembelianlog_id']);
							}
							foreach($post['TMonitoringPembelianlogDetail'] as $i => $postdetail ){
								$modDetail = new \app\models\TMonitoringPembelianlogDetail();
								$modDetail->attributes = $postdetail;
								$modDetail->monitoring_pembelianlog_id = $model->monitoring_pembelianlog_id;
								$modDetail->btg = \app\components\DeltaFormatter::formatNumberForDb2($modDetail->btg);
								$modDetail->m3 = \app\components\DeltaFormatter::formatNumberForDb2($modDetail->m3);
								if($modDetail->validate()){
									if($modDetail->save()){
										$success_2 &= true;
									}
								}else{
									$success_2 &= false;
								}
							}
						}
					}else{
						$data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
					}
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					exit;
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
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
			}else{
				$data['html'] = $this->renderPartial('_itemMonitoring',['model'=>$model]);
				return $this->asJson($data);
			}
        }
    }
	
	function actionGetMonitoring($pengajuan_pembelianlog_id){
		if(\Yii::$app->request->isAjax){
            $data = [];
            if(!empty($pengajuan_pembelianlog_id)){
                $models = \app\models\TMonitoringPembelianlog::find()->where(['pengajuan_pembelianlog_id'=>$pengajuan_pembelianlog_id])->orderBy("created_at ASC")->all();
            }
            $data['html'] = '';
            if(count($models)>0){
                foreach($models as $i => $model){
					$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
                    $data['html'] .= $this->renderPartial('_itemMonitoring',['model'=>$model]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDeleteMonitoring($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\TMonitoringPembelianlog::findOne($id);
            $modDetail = \app\models\TMonitoringPembelianlogDetail::find()->where("monitoring_pembelianlog_id = '{$id}'")->all();
            $modAttch = \app\models\TAttachment::find()->where("reff_no = '{$model->kode}'")->all();
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = true;
                    $success_3 = true;
                    
                    if(!empty($modDetail)){
                        $success_2 = \app\models\TMonitoringPembelianlogDetail::deleteAll("monitoring_pembelianlog_id = '{$id}'");
                    }
                    if($success_2){
                        if($model->delete()){
                            $success_1 = true;
                            if(!empty($modAttch)){
                                $success_3 = \app\models\TAttachment::deleteAll("reff_no = '{$model->kode}'");
                            }
                        }else{
                            $data['message'] = Yii::t('app', 'Data Gagal dihapus');
                        }
                    }
//                    echo "<pre>1";
//                    print_r($success_1);
//                    echo "<pre>2>";
//                    print_r($success_2);
//                    echo "<pre>3>";
//                    print_r($success_3);
//                    exit;
					if ($success_1 && $success_2 && $success_3) {
						$transaction->commit();
						$data['status'] = true;
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
						$data['callback'] = "getMonitoring();";
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid,'actionname'=>'deleteMonitoring']);
		}
	}
	
	public function actionAddAttch($monitoring_pembelianlog_id){
		if(\Yii::$app->request->isAjax){
			$modelMonitoring = \app\models\TMonitoringPembelianlog::findOne($monitoring_pembelianlog_id);
			$model = new \app\models\TAttachment();
			if( Yii::$app->request->post('TAttachment')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = true;
					$files = []; $dir_path = Yii::$app->basePath.'/web/uploads/pur/monitoringlog';
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file1');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file2');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file3');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file4');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file5');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file6');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file7');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file8');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file9');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file10');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file11');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file12');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file13');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file14');
					$files[] = \yii\web\UploadedFile::getInstance($model, 'file15');
					
					$searchAttachment = \app\models\TAttachment::find()->where("reff_no = '{$modelMonitoring->kode}' ")->all();
					if(count($searchAttachment)>0){
						foreach($searchAttachment as $i => $attach){
							$keydb = ($i!=0)?$i:"";
							
							if(!empty($files[$i])){
								if (file_exists(Yii::$app->basePath.'/web/uploads/pur/monitoringlog/'.$attach->file_name)) {
									unlink(Yii::$app->basePath.'/web/uploads/pur/monitoringlog/'.$attach->file_name);
									\app\models\TAttachment::deleteAll(['reff_no'=>$modelMonitoring->kode,'file_name'=>$attach->file_name]);
								}
							}
						}
					}
					
					
					foreach($files as $i => $file){
						if(!empty($file)){
							$model = new \app\models\TAttachment();
							$model->reff_no = $modelMonitoring->kode;
							$model->file_type = $file->type;
							$model->file_ext = $file->extension;
							$model->file_size = $file->size;
							$model->dir_path = $dir_path;
							$model->seq = ($i+1);
							$randomstring_attch = Yii::$app->getSecurity()->generateRandomString(4);
							if(!is_dir($dir_path)){ mkdir($dir_path); }
							$file_path = date('Ymd_His').'-attch-'.$randomstring_attch.'.'  . $file->extension;
							$file->saveAs($dir_path.'/'.$file_path);
							$model->file_name = $file_path;
							if($model->validate()){
								if($model->save()){
									$success_1 &= true;
								}else{
									$success_1 = false;
								}
							}else{
								$success_1 = false;
								$errmsg = $model->errors;
							}
						}
					}
                    if ($success_1) {
                        $transaction->commit();
						$data['status'] = true;
						$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
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
			return $this->renderAjax('addAttch',['model'=>$model,'monitoring_pembelianlog_id'=>$monitoring_pembelianlog_id,'modelMonitoring'=>$modelMonitoring]);
        }
    }
	
	public function actionSetKontrak($log_kontrak_id){
		if(\Yii::$app->request->isAjax){
			$data = [];
			if(!empty($log_kontrak_id)){
				$model = \app\models\TLogKontrak::findOne($log_kontrak_id);
				if(!empty($model)){
					$data = $model->attributes;
				}
			}
			return $this->asJson($data);
		}
	}
    
    public function actionDeleteAttch($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\TAttachment::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if (file_exists(Yii::$app->basePath.'/web/uploads/pur/monitoringlog/'.$model->file_name)) {
                        unlink(Yii::$app->basePath.'/web/uploads/pur/monitoringlog/'.$model->file_name);
                    }
                    if($model->delete()){
                        $success_1 = true;
                    }else{
                        $data['message'] = Yii::t('app', 'Data Gagal dihapus');
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                        $data['callback'] = "getMonitoring();";
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>"DeleteAttch"]);
		}
	}
    
    public function actionOpenpermintaanlog(){
		if(\Yii::$app->request->isAjax){
            $pick = \Yii::$app->request->get('pick');
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPmr::tableName();
				$param['pk']= $param['table'].".". \app\models\TPmr::primaryKey()[0];
				$param['column'] = [$param['table'].'.pmr_id',
									$param['table'].'.kode',
									$param['table'].'.tanggal',
									$param['table'].'.jenis_log',
									$param['table'].'.tujuan',
									"CONCAT( TO_CHAR(tanggal_dibutuhkan_awal :: DATE, 'dd/mm/yyyy'),' sd ',TO_CHAR(tanggal_dibutuhkan_akhir :: DATE, 'dd/mm/yyyy')) as dibutuhkan",
									'(SELECT SUM(qty_m3) FROM t_pmr_detail WHERE t_pmr_detail.pmr_id = t_pmr.pmr_id) AS total_m3',
									'm_pegawai.pegawai_nama AS dibuat_oleh',
									'pegawai1.pegawai_nama AS approver_1',
									'pegawai2.pegawai_nama AS approver_2',
									'pegawai3.pegawai_nama AS approver_3',
                                                                        '(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_1) AS approver_1_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_2) AS approver_2_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_3) AS approver_3_status',
                                                                        '(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT t_pengajuan_pembelianlog.kode FROM t_pengajuan_pembelianlog JOIN map_permintaan_keputusan_logalam ON map_permintaan_keputusan_logalam.pengajuan_pembelianlog_id = t_pengajuan_pembelianlog.pengajuan_pembelianlog_id WHERE map_permintaan_keputusan_logalam.pmr_id = t_pmr.pmr_id GROUP BY 1) t) AS kode_pengajuan_keputusan',
									$param['table'].'.status',
                                                                        'pegawai4.pegawai_nama AS approver_4',
                                                                        '(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_4) AS approver_4_status'
                                    ];
				$param['join']= ['
								JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.dibuat_oleh 
								JOIN m_pegawai AS pegawai1 ON pegawai1.pegawai_id = '.$param['table'].'.approver_1 
								JOIN m_pegawai AS pegawai2 ON pegawai2.pegawai_id = '.$param['table'].'.approver_2 
								LEFT JOIN m_pegawai AS pegawai3 ON pegawai3.pegawai_id = '.$param['table'].'.approver_3 
                                                                LEFT JOIN m_pegawai AS pegawai4 ON pegawai4.pegawai_id = '.$param['table'].'.approver_4    
								'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL AND t_pmr.jenis_log = 'LA'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('openPermintaanLog',['pick'=>$pick]);
        }
    }
    
    function actionPick(){
		if(\Yii::$app->request->isAjax){
            $kode = Yii::$app->request->post('kode');
            $data['html'] = ""; $data['pmr_id']="";
            if(!empty($kode)){
                $model = \app\models\TPmr::findOne(['kode'=>$kode]);
                $total_m3 = \app\models\TPmrDetail::find()->select("SUM(qty_m3) AS total_m3")->where("pmr_id = ".$model->pmr_id)->one();
                $model->total_m3 = $total_m3->total_m3;
                $data['html'] .= $this->renderPartial('_itemPermintaan',['model'=>$model]);
                $data['pmr_id'] .= $model->pmr_id;
            }
            return $this->asJson($data);
        }
    }
    
    function actionUpdateStatusPmr(){
		if(\Yii::$app->request->isAjax){
            $pmr_id = Yii::$app->request->post('pmr_id');
            $status = Yii::$app->request->post('status');
            $success_1 = false;
            if(!empty($status)){
                $model = \app\models\TPmr::findOne($pmr_id);
                $model->status = "CLOSE";
                if($model->save()){
                    $success_1 = true;
                }
            }
            return $this->asJson($success_1);
        }
    }

	function actionDetailKeputusan(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TPengajuanPembelianlog::findOne(['kode'=>$_GET['kode']]);
			$modPO = \app\models\TLogKontrak::findOne($model->log_kontrak_id);
			$paramprint['judul'] = Yii::t('app', 'PURCHASE ORDER');
			return $this->renderAjax('detailKeputusan',['model'=>$model, 'modPO'=>$modPO,'paramprint'=>$paramprint]);
        }
	}
}
