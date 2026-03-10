<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovaldplogController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',
								'view_approval.reff_no AS reff_appr', //1
								'tanggal_berkas', //2
                                't_log_kontrak.nomor',
                                "CONCAT('<b>',t_log_kontrak.pihak1_nama,'</b>','<br>',t_log_kontrak.pihak1_perusahaan) AS suplier",
                                "t_log_kontrak.kuantitas",
								't_log_bayar_dp.keterangan', //7
								'assigned_nama', //8
								'approved_by_nama', //9
								'level', //10
								$param['table'].'.status', //11
								$param['table'].'.created_at', //12
								$param['table'].'.created_at']; //13
			$param['where'] = "(view_approval.reff_no ILIKE '%PDL%' and view_approval.status = 'Not Confirmed')";
			$param['join'] = "JOIN t_log_bayar_dp on view_approval.reff_no = t_log_bayar_dp.kode
                              JOIN t_log_kontrak on t_log_bayar_dp.log_kontrak_id = t_log_kontrak.log_kontrak_id";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "tanggal_berkas DESC, level DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Not Confirmed']);
	}
	public function actionIndexConfirmed(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',
								'view_approval.reff_no AS reff_appr', //1
								'tanggal_berkas', //2
                                't_log_kontrak.nomor',
                                "CONCAT('<b>',t_log_kontrak.pihak1_nama,'</b>','<br>',t_log_kontrak.pihak1_perusahaan) AS suplier",
                                "t_log_kontrak.kuantitas",
								't_log_bayar_dp.keterangan', //7
								'assigned_nama', //8
								'approved_by_nama', //9
								'level', //10
								$param['table'].'.status', //11
								$param['table'].'.created_at', //12
								$param['table'].'.created_at']; //13
			$param['where'] = "(view_approval.reff_no ILIKE '%PDL%' and view_approval.status != 'Not Confirmed')";
			$param['join'] = "JOIN t_log_bayar_dp on view_approval.reff_no = t_log_bayar_dp.kode
                              JOIN t_log_kontrak on t_log_bayar_dp.log_kontrak_id = t_log_kontrak.log_kontrak_id";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "tanggal_berkas DESC, level DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Confirmed']);
	}
	
	public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modReff = \app\models\TLogBayarDp::findOne(['kode'=>$model->reff_no]);
			$modPO = \app\models\TLogKontrak::findOne($modReff->log_kontrak_id);
			return $this->renderAjax('info',['model'=>$model,'modReff'=>$modReff,'modPO'=>$modPO]);
		}
	}

    public function actionShowDetails(){
        if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$data['html'] = '';
			$model = \app\models\TApproval::findOne($approval_id);
			$modReff = \app\models\TStockopnameHasil::findOne(['kode'=>$model->reff_no]);
			$data['html'] = $this->renderPartial('show',['model'=>$model,'modReff'=>$modReff]);
			return $this->asJson($data);
        }
    }
	
	public function actionApproveConfirm($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$berkas_nama = \app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no);
			$pesan = "Yakin akan menyetujui ".$berkas_nama." ini?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_approval
                    $success_2 = true; // update t_stockopname_agenda
					if(!empty($model)){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
                                // update status hasil = "ACTIVE"
                                if($model->level == 2){
                                    $modHasil = \app\models\TLogBayarDp::findOne(['kode'=>$model->reff_no]);
                                    $modHasil->status = "APPROVED";
                                    if($modHasil->validate()){
                                        if($modHasil->save()){
                                            $success_2 = true;
                                        }else{
                                            $success_2 = false;
                                        }
                                    }else{
                                        $success_2 = false;
                                    }
                                }
                                // end
							}
						}
					}
//                    echo "<pre>";
//                    print_r($success_1);
//                    echo "<pre>";
//                    print_r($success_2);
//                    exit;
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); $("#modal-master-info").find(".fa.fa-close").click();';
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
			return $this->renderAjax('@views/apps/partial/_globalConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'ApproveConfirm']);
		}
	}
	
	public function actionRejectReason($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			if( Yii::$app->request->post('TApproval')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					if(!empty($model) && !empty($_POST['TApproval']['reject_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_REJECTED;
                        $arrPost = ['status'=>"REJECTED",
                                            'by'=> $model->assigned_to,
                                            'at'=>date('Y-m-d H:i:s'),
                                            'reason'=>$_POST['TApproval']['reject_reason']
                                            ];
                        if(!empty($model->keterangan)){
                            $reason = \yii\helpers\Json::decode($model->keterangan);
                            $reject_reason = [];
                            foreach($reason as $i => $reas){
                                $reject_reason[] = $reas;
                            }
                            array_push($reject_reason, $arrPost);
                        }else{
                            $reject_reason[0] = $arrPost;
                        }
                        $model->keterangan = \yii\helpers\Json::encode($reject_reason);
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
                                // update status agenda = "REJECTED"
                                if($model->level == 3){
                                    $modAgenda = \app\models\TStockopnameAgenda::findOne(['kode'=>$model->reff_no]);
                                    $modAgenda->status = "REJECTED";
                                    if($modAgenda->validate()){
                                        if($modAgenda->save()){
                                            $success_2 = true;
                                        }else{
                                            $success_2 = false;
                                        }
                                    }else{
                                        $success_2 = false;
                                    }
                                }
                                // end
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }
//                    echo "<pre>";
//                    print_r($success_1);
//                    exit;
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
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
			return $this->renderAjax('rejectReason',['model'=>$model,'id'=>$id]);
		}
	}
	
	public function actionConfirm(){
		if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$modApprove = \app\models\TApproval::findOne($approval_id);
			$modAselole = \app\models\TLogBayarDp::findOne(['kode'=>$modApprove->reff_no]);
			$checkApprovals = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '".$modAselole->kode."' AND level < ".$modApprove->level)->queryAll();
			$data = true;
			if(count($checkApprovals)>0){
				foreach($checkApprovals as $i => $check){
					if($check['status'] != \app\models\TApproval::STATUS_NOT_CONFIRMATED){
						$data &= true;
					}else{
                        $data &= false;
                    }
				}
			}
			return $this->asJson($data);
		}
	}
	public function actionNotAllowed(){
		if(\Yii::$app->request->isAjax){
			$judul = "Agreement Confirm!";
			$pesan = "Anda belum bisa mengkonfirmasi ini, sebelum approver dibawah level anda mengkonfirmasi approval nya.";
			return $this->renderAjax('@views/apps/partial/_globalInfo',['judul'=>$judul,'pesan'=>$pesan,'actionname'=>'']);
		}
	}
    
    public function actionApproveReason($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modelReff = \app\models\THasilOrientasi::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('THasilOrientasi')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['THasilOrientasi']['approve_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"APPROVED",
											'by'=> $model->assigned_to,
											'at'=>date('Y-m-d H:i:s'),
											'reason'=>$_POST['THasilOrientasi']['approve_reason']
											];
								if(!empty($modelReff->approve_reason)){
									$reason = \yii\helpers\Json::decode($modelReff->approve_reason);
									$approve_reason = [];
									foreach($reason as $i => $reas){
										$approve_reason[] = $reas;
									}
									array_push($approve_reason, $arrPost);
								}else{
									$approve_reason[0] = $arrPost;
								}
								$modelReff->approve_reason = \yii\helpers\Json::encode($approve_reason);
								if($modelReff->validate()){
									if($modelReff->save()){
										$success_2 = true;
									}
								}
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }
//                    echo "<pre>";
//                    print_r($success_1);
//                    echo "<pre>";
//                    print_r($success_2);
//                    exit;
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
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
			return $this->renderAjax('approveReason',['modelReff'=>$modelReff,'id'=>$id]);
		}
	}
	
}
