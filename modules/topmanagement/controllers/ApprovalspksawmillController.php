<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovalspksawmillController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',						//0
								'kode', 							//1
								'tanggal_berkas', 					//2
								'tanggal_mulai',					//3
								'tanggal_selesai',		            //4
								'assigned_nama',      				//5
								'approved_by_nama',   				//6
								'level', 							//7
								$param['table'].'.status',          //8
                                ]; 		
			$param['where'] = "(view_approval.reff_no ILIKE '%SSW%' and view_approval.status = 'Not Confirmed')";
			$param['join']  = " JOIN t_spk_sawmill on t_spk_sawmill.kode = view_approval.reff_no";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "kode DESC, tanggal_berkas DESC, level DESC";
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
								'tanggal_mulai',					//3
								'tanggal_selesai',		            //4
								'assigned_nama',      				//5
								'approved_by_nama',   				//6
								'level', 							//7
								$param['table'].'.status',          //8
                                ]; 		
			$param['where'] = "(view_approval.reff_no ILIKE '%SSW%' and view_approval.status != 'Not Confirmed')";
			$param['join']  = " JOIN t_spk_sawmill on t_spk_sawmill.kode = view_approval.reff_no";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "kode DESC, tanggal_berkas DESC, level DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Confirmed']);
	}

	public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modReff = \app\models\TSpkSawmill::findOne(['kode'=>$model->reff_no]);
			return $this->renderAjax('info',['model'=>$model,'modReff'=>$modReff]);
		}
	}

	public function actionRejectReason($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modelReff = \app\models\TSpkSawmill::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TSpkSawmill')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['TSpkSawmill']['reject_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()) {
							if($model->save()) {
								$success_1 = true;
								$arrPost = ['status'=>"REJECTED",
													'by'=> $model->assigned_to,
													'at'=>date('Y-m-d H:i:s'),
													'reason'=>$_POST['TSpkSawmill']['reject_reason']
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

								$sqlUpdate =  " UPDATE t_spk_sawmill
												SET reject_reason = '$modelReff->reject_reason'
												WHERE kode = '".$model->reff_no."' ";
								$success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();

								// jika yang reject level 1, update status approval level 2 sekalian jadi reject
								if ($model->level == 1) {
									$sql_update_2 = "update t_approval set status = 'REJECTED' ".
													"	, approved_by = {$model->approved_by} ".
													"	, tanggal_approve = '".$model->tanggal_approve."' ".
													"	where reff_no = '".$model->reff_no."' ".
													"	and level = 2 ".
													"	";
									Yii::$app->db->createCommand($sql_update_2)->execute();
								} else {
									$success_2 = 1;
								}

								//ubah approval_status t_pengajuan_drp jadi reject
								if($model->level == 1 || $model->level == 2){
									$modSPK = \app\models\TSpkSawmill::findOne(['kode'=>$model->reff_no]);
                                    $modSPK->approval_status = "REJECTED";
                                    if($modSPK->validate()){
                                        if($modSPK->save()){
                                            $success_4 = true;
                                        }
                                    }
								}

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
			$model = \app\models\TSpkSawmill::findOne(['kode'=>$modApprove->reff_no]);
			$checkApprovals = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '".$model->kode."' AND level < ".$modApprove->level)->queryAll();
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

			// hitung ulang cek approval buat approval_status di table t_spk_sawmill, bikin dibawah jangan ganggu yang atas xD
            $xxx = 1;
			if (count($checkApprovals) > 0){
				foreach($checkApprovals as $i => $check){
                    if ($check['status'] == "APPROVED") {
                        $xxx += 1;
                    } else {
                        $xxx += 0;
                    }
				}
            }

			$lvl = Yii::$app->db->createCommand("select max(level) as lvl from t_approval where reff_no = '{$model->kode}'")->queryOne();
			if($xxx < $lvl['lvl']){
				$sql_approval_status = "update t_spk_sawmill set approval_status = 'Not Confirmed' where kode = '".$modApprove->reff_no."' ";
			} else {
				if ($xxx == $lvl['lvl'] - 1) {
					$sql_approval_status = "UPDATE t_spk_sawmill SET approval_status = 'Not Confirmed' WHERE kode = '".$modApprove->reff_no."' ";
				} else {
					$sql_approval_status = "UPDATE t_spk_sawmill SET approval_status = 'APPROVED' WHERE kode = '".$modApprove->reff_no."' ";
				}
			}
			Yii::$app->db->createCommand($sql_approval_status)->execute(); 
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
			$modelReff = \app\models\TSpkSawmill::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TSpkSawmill')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['TSpkSawmill']['approve_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"APPROVED",
											'by'=> $model->assigned_to,
											'at'=>date('Y-m-d H:i:s'),
											'reason'=>$_POST['TSpkSawmill']['approve_reason']
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
					// print_r($details); exit;
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
