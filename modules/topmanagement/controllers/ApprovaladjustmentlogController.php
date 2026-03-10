<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovaladjustmentlogController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',
								'reff_no', 
								'tanggal_berkas',
                                't_loglist.loglist_kode',
                                't_spk_shipping.kode',
								'assigned_nama',
								'approved_by_nama', 
								'level', 
								$param['table'].'.status'];
								//$param['table'].'.created_at', 
								//$param['table'].'.created_by'];
			$param['join'] = "left join t_adjustment_log on t_adjustment_log.kode = view_approval.reff_no ".
                                " left join t_loglist on t_loglist.loglist_kode = t_adjustment_log.reff_no_loglist ".
                                " left join t_spk_shipping on t_spk_shipping.kode = t_adjustment_log.reff_no_spk ";
			$param['where'] = "(reff_no ILIKE '%ADL%' and view_approval.status = 'Not Confirmed')";
			$param['order'] = "tanggal_berkas desc, reff_no desc";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
                if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){
                    $param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
                }
			}
			$param['order'] = "tanggal_berkas DESC, reff_no desc, level DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Not Confirmed']);
	}
	
	public function actionIndexConfirmed(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',
								'reff_no', 
								'tanggal_berkas',
                                't_loglist.loglist_kode',
                                't_spk_shipping.kode',
								'assigned_nama',
								'approved_by_nama', 
								'level', 
								$param['table'].'.status'];
								//$param['table'].'.created_at', 
								//$param['table'].'.created_by'];
            $param['join'] = "left join t_adjustment_log on t_adjustment_log.kode = view_approval.reff_no ".
                                " left join t_loglist on t_loglist.loglist_kode = t_adjustment_log.reff_no_loglist ".
                                " left join t_spk_shipping on t_spk_shipping.kode = t_adjustment_log.reff_no_spk ";
			$param['where'] = "(reff_no ILIKE '%ADL%' and view_approval.status != 'Not Confirmed')";
			$param['order'] = "kode desc, tanggal_berkas desc, reff_no desc, level asc";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
                if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){
                    $param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
                }
			}
			$param['order'] = "tanggal_berkas DESC, reff_no desc, level DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Confirmed']);
	}

	public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			return $this->renderAjax('info',['model'=>$model]);
		}
	}

	public function actionImage($id){
		if(\Yii::$app->request->isAjax){
			$attch = \app\models\TAttachment::findOne($id);
			return $this->renderAjax('image',['attch'=>$attch]);
		}
	}

    public function actionShowDetails(){
        if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$data['html'] = '';
			$model = \app\models\TApproval::findOne($approval_id);
			$modReff = \app\models\TAdjustmentLog::findOne(['kode'=>$model->reff_no]);
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
                    $success_1 = false;
					if(!empty($model)){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
							}
						}
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); showdetails("'.$id.'");';
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
    
	public function actionApproveReason($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modelReff = \app\models\TAdjustmentLog::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TAdjustmentLog')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['TAdjustmentLog']['approve_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;

						$model->tanggal_approve = date('Y-m-d');
												
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"APPROVED",
											'by'=> $model->assigned_to,
											'at'=>date('Y-m-d H:i:s'),
											'reason'=>$_POST['TAdjustmentLog']['approve_reason']
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

                                if ($model->assigned_to == \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG) {
                                    $and_status_approval = ", status_approval = 'PENDING' ";
                                } else if ($model->assigned_to == \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO) {
                                    $and_status_approval = ", status_approval = 'APPROVED' ";
                                } else {
                                    $and_status_approval = "";
                                }

                                $sqlUpdate =  " UPDATE t_adjustment_log ".
                                                " SET approve_reason = '".$modelReff->approve_reason ."'".
                                                "   ".$and_status_approval." ".
                                                "   WHERE kode = '".$model->reff_no."' ".
                                                "   ";
                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }
                    
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
					} else {
						$transaction->rollback();
						$data['status'] = false;
						//(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (!isset($data['message']) ? $data['message'] = $sqlUpdate : '');
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
	
	public function actionRejectConfirm($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$berkas_nama = \app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no);
			$pesan = "Yakin akan menolak ".$berkas_nama." ini?";
            if( Yii::$app->request->post('updaterecord')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					if(!empty($model)){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
							}
						}
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); showdetails("'.$id.'");';
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
			return $this->renderAjax('@views/apps/partial/_globalConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'RejectConfirm']);
		}
	}
	
    public function actionRejectReason($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modelReff = \app\models\TAdjustmentLog::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TAdjustmentLog')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['TAdjustmentLog']['reject_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;

						$model->tanggal_approve = date('Y-m-d');
						
						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"REJECTED",
											'by'=> $model->assigned_to,
											'at'=>date('Y-m-d H:i:s'),
											'reason'=>$_POST['TAdjustmentLog']['reject_reason']
											];
								if(!empty($modelReff->reject_reason)){
									$reason = \yii\helpers\Json::decode($modelReff->reject_reason);
									$reject_reason = [];
									foreach($reason as $i => $reas){
										$reject_reason[] = $reas;
									}
									array_push($reject_reason, $arrPost);
								}else{
									$reject_reason[0] = $arrPost;
								}
								$modelReff->reject_reason = \yii\helpers\Json::encode($reject_reason);

                                if ($model->assigned_to == \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG) {
                                    $and_status_approval = ", status_approval = 'PENDING' ";
                                } else if ($model->assigned_to == \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO) {
                                    $and_status_approval = ", status_approval = 'REJECTED' ";
                                } else {
                                    $and_status_approval = "";
                                }

                                $sqlUpdate =  " UPDATE t_adjustment_log ".
                                                "   SET reject_reason = '".$modelReff->reject_reason."'".
                                                "   ".$and_status_approval."".
                                                "   WHERE kode = '".$model->reff_no."' ".
                                                "   ";
                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }

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
			return $this->renderAjax('rejectReason',['modelReff'=>$modelReff,'id'=>$id]);
		}
	}
	
	public function actionConfirm(){
		if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$modApprove = \app\models\TApproval::findOne($approval_id);
			$modHO = \app\models\TAdjustmentLog::findOne(['kode'=>$modApprove->reff_no]);
			$checkApprovals = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '".$modHO->kode."' AND level < ".$modApprove->level)->queryAll();
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
    
    
}
