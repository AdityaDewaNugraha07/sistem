<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovalopenvoucherController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',						//0
								'kode', 							//1
								'tanggal_berkas', 					//2
								'tipe', 							//3
								'm_departement.departement_nama', 	//4
								't_open_voucher.reff_no', 			//5
								'total_pembayaran', 				//6
								't_open_voucher.keterangan',		//7
								'assigned_nama', 					//8
								'approved_by_nama', 				//9
								'level', 							//10
								$param['table'].'.status',	 		//11
								'mata_uang'];
			$param['where'] = "(view_approval.reff_no ILIKE '%OVK%' and view_approval.status = 'Not Confirmed')";
			$param['join'] = "JOIN t_open_voucher on t_open_voucher.kode = view_approval.reff_no
                              JOIN m_departement on m_departement.departement_id = t_open_voucher.departement_id";
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
			$param['column'] = ['approval_id',						//0
								'kode', 							//1
								'tanggal_berkas', 					//2
								'tipe', 							//3
								'm_departement.departement_nama', 	//4
								't_open_voucher.reff_no', 			//5
								'total_pembayaran', 				//6
								't_open_voucher.keterangan',		//7
								'assigned_nama', 					//8
								'approved_by_nama', 				//9
								'level', 							//10
								$param['table'].'.status',	 		//11
								'mata_uang'];
			$param['where'] = "(view_approval.reff_no ILIKE '%OVK%' and view_approval.status != 'Not Confirmed' AND view_approval.status != 'ABORTED')";
			$param['join'] = "JOIN t_open_voucher on t_open_voucher.kode = view_approval.reff_no
                              JOIN m_departement on m_departement.departement_id = t_open_voucher.departement_id";
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
			$modReff = \app\models\TOpenVoucher::findOne(['kode'=>$model->reff_no]);
			$modReff->departement_nama = \app\models\MDepartement::findOne( $modReff->departement_id )->departement_nama;
            $modDetail = \app\models\TOpenVoucherDetail::find()->where(['open_voucher_id'=>$modReff->open_voucher_id])->all();
			return $this->renderAjax('info',['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail]);
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

								//jika level bawah rejected, level atas rejected
								if ($model->level == 1) {
									$sql_update_2 = "update t_approval set status = 'REJECTED' ".
													"	, approved_by = {$model->approved_by} ".
													"	, tanggal_approve = '".$model->tanggal_approve."' ".
													"	where reff_no = '".$model->reff_no."' ".
													"	and level = 2 ".
													"	";
									Yii::$app->db->createCommand($sql_update_2)->execute();
									$ket_update = "update t_approval set keterangan = '".$model->keterangan."' ".
													"	, approved_by = {$model->approved_by} ".
													"	, tanggal_approve = '".$model->tanggal_approve."' ".
													"	where reff_no = '".$model->reff_no."' ".
													"	and level = 2 ".
													"	";
									Yii::$app->db->createCommand($ket_update)->execute();
								}
								if($model->level == 1 || $model->level == 2){
									$modHasil = \app\models\TOpenVoucher::findOne(['kode'=>$model->reff_no]);
                                    $modHasil->status_approve = "REJECTED";
                                    if($modHasil->validate()){
                                        if($modHasil->save()){
                                            $success_2 = true;
                                        } else {
											$success_2 = false;
										}
                                    } else {
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
			$modAselole = \app\models\TOpenVoucher::findOne(['kode'=>$modApprove->reff_no]);
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
			if( Yii::$app->request->post('TApproval')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					if(!empty($model) && !empty($_POST['TApproval']['approve_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_APPROVED;
                        $arrPost = ['status'=>"APPROVED",
                                            'by'=> $model->assigned_to,
                                            'at'=>date('Y-m-d H:i:s'),
                                            'reason'=>$_POST['TApproval']['approve_reason']
                                            ];
                        if(!empty($model->keterangan)){
                            $reason = \yii\helpers\Json::decode($model->keterangan);
                            $approve_reason = [];
                            foreach($reason as $i => $reas){
                                $approve_reason[] = $reas;
                            }
                            array_push($approve_reason, $arrPost);
                        }else{
                            $approve_reason[0] = $arrPost;
                        }
                        $model->keterangan = \yii\helpers\Json::encode($approve_reason);
						if($model->validate()){
							if($model->save()){
								$success_1 = true;

								$maxlevel = Yii::$app->db->createCommand("select max(level) as lvl from t_approval where reff_no = '".$model->reff_no."'")->queryOne();
								$modHasil = \app\models\TOpenVoucher::findOne(['kode'=>$model->reff_no]);
								if($model->level == $maxlevel['lvl']){
                                    $modHasil->status_approve = "APPROVED";
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
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }
                //    echo "<pre>";
                //    print_r($maxlevel['lvl']);
                //    exit;
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
			return $this->renderAjax('approveReason',['model'=>$model,'id'=>$id]);
		}
	}
}
