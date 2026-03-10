<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\TPengajuanDrpDetail;

class ApprovaldrpController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',						//0
								'kode', 							//1
								'tanggal', 							//2
								// 'kategori', 						//3
								't_pengajuan_drp.keterangan',		//4
								'assigned_nama', 					//5
								'approved_by_nama', 				//6
								'level', 							//7
								$param['table'].'.status']; 		//8
			$param['where'] = "(view_approval.reff_no ILIKE '%DRP%' and view_approval.status = 'Not Confirmed')";
			$param['join'] 	= "JOIN t_pengajuan_drp on t_pengajuan_drp.kode = view_approval.reff_no";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "tanggal DESC, level DESC";
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
								'tanggal', 							//2
								// 'kategori', 						//3
								't_pengajuan_drp.keterangan',		//4
								'assigned_nama', 					//5
								'approved_by_nama', 				//6
								'level', 							//7
								$param['table'].'.status']; 		//8
			$param['where'] = "(view_approval.reff_no ILIKE '%DRP%' and view_approval.status != 'Not Confirmed')";
			$param['join'] = "JOIN t_pengajuan_drp on t_pengajuan_drp.kode = view_approval.reff_no";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "tanggal DESC, level DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Confirmed']);
	}

	public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modReff = \app\models\TPengajuanDrp::findOne(['kode'=>$model->reff_no]);
			return $this->renderAjax('info',['model'=>$model,'modReff'=>$modReff]);
		}
	}

	public function actionApproveConfirm($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modelReff = \app\models\TPengajuanDrp::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TPengajuanDrp')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					$success_3 = false;
					if(!empty($model) && !empty($_POST['TPengajuanDrp']['reason_approval'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;

						$model->tanggal_approve = date('Y-m-d');
						
						// tanggal jam menit detik hari ini untuk update 
						$updated_at = date('Y-m-d H:i:s');
						
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"APPROVED",
											'by'=> $model->assigned_to,
											'at'=>date('Y-m-d H:i:s'),
											'reason'=>$_POST['TPengajuanDrp']['reason_approval']
											];
								if(!empty($modelReff->reason_approval)){
									$reason = \yii\helpers\Json::decode($modelReff->reason_approval);
									$reason_approval = [];
									foreach($reason as $i => $reas){
										$reason_approval[] = $reas;
									}
									array_push($reason_approval, $arrPost);
								}else{
									$reason_approval[0] = $arrPost;
								}
								$modelReff->reason_approval = \yii\helpers\Json::encode($reason_approval);

								$sql_json = "select reason_approval from t_pengajuan_drp where kode = '".$model->reff_no."' ";
								$jsons = Yii::$app->db->createCommand($sql_json)->queryScalar();

								if (empty($jsons)) {
									$sqlUpdate =  " UPDATE t_pengajuan_drp
													SET reason_approval = '$modelReff->reason_approval'
													WHERE kode = '".$model->reff_no."' ";
									$success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();

								} else if (!empty($jsons)) {
									$json = json_decode($jsons);
									$pegawai_id = Yii::$app->user->identity->pegawai_id;

									foreach($json as $key) {
										if ($key->by == $pegawai_id) {
											$value = 'ada';
										} else {
											$value = 'kosong';
										}
									}

									if ($value == 'kosong') {
										$sqlUpdate =  " UPDATE t_pengajuan_drp
														SET reason_approval = '$modelReff->reason_approval'
														WHERE kode = '".$model->reff_no."' ";
										$success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();
									} else {
										$success_2 = true ;
									}
								}
								 else {
									$success_2 = true;
								}
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }

					$sql_ = "select count(*) from t_approval where approval_id = '".$id."' and approved_by is not NULL ";
					$query_ = Yii::$app->db->createCommand($sql_)->queryScalar();
					$result = $query_;
					$result > 0 ? $success_3 = true : $success_3 = false;
					
					if ($success_1 && $success_2 && $success_3) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';

						//$transaction->rollback();
						//(!isset($data['message']) ? $data['message'] = $success_2 : $success_2);
					
					} else {
						$transaction->rollback();
						$data['status'] = false;
						(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');

						//$transaction->rollback();
						//(!isset($data['message']) ? $data['message'] = "1 = ".$success_1."<br> 2 = ".$success_2."<br> sqlb = ".$sql_."<br> result = ".$success_3 : 'y2');
						//(isset($data['message_validate']) ? $data['message'] = $sql : 'yyy2');
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

	public function actionRejectReason($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modelReff = \app\models\TPengajuanDrp::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TPengajuanDrp')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['TPengajuanDrp']['reason_rejected'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()) {
							if($model->save()) {
								$success_1 = true;
								$arrPost = ['status'=>"REJECTED",
													'by'=> $model->assigned_to,
													'at'=>date('Y-m-d H:i:s'),
													'reason'=>$_POST['TPengajuanDrp']['reason_rejected']
													];
								if(!empty($modelReff->reason_rejected)){
									$reason = \yii\helpers\Json::decode($modelReff->reason_rejected);
									$reason_rejected = [];
									foreach($reason as $i => $reas){
										$reason_rejected[] = $reas;
									}
									array_push($reason_rejected, $arrPost);
								}else{
									$reason_rejected[0] = $arrPost;
								}
								$modelReff->reason_rejected = \yii\helpers\Json::encode($reason_rejected);

								$sqlUpdate =  " UPDATE t_pengajuan_drp
												SET reason_rejected = '$modelReff->reason_rejected'
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
									$sql_update_3 = "update t_approval set status = 'REJECTED' ".
													"	, approved_by = {$model->approved_by} ".
													"	, tanggal_approve = '".$model->tanggal_approve."' ".
													"	where reff_no = '".$model->reff_no."' ".
													"	and level = 3 ".
													"	";
									Yii::$app->db->createCommand($sql_update_3)->execute();
								} else {
									$success_3 = 1;
								}

								if($model->level == 2){
									$sql_update_3 = "update t_approval set status = 'REJECTED' ".
													"	, approved_by = {$model->approved_by} ".
													"	, tanggal_approve = '".$model->tanggal_approve."' ".
													"	where reff_no = '".$model->reff_no."' ".
													"	and level = 3 ".
													"	";
									Yii::$app->db->createCommand($sql_update_3)->execute();
								} else {
									$success_3 = 1;
								}

								//ubah status_approve t_pengajuan_drp jadi reject
								if($model->level == 1 || $model->level == 2 || $model->level == 3){
									$modDrp = \app\models\TPengajuanDrp::findOne(['kode'=>$model->reff_no]);
                                    $modDrp->status_approve = "REJECTED";
									$modDrpDetail = \app\models\TPengajuanDrpDetail::find()->where(['pengajuan_drp_id'=>$modDrp->pengajuan_drp_id])->all();
                                    if($modDrp->validate()){
                                        if($modDrp->save()){
                                            $success_4 = true;
											foreach($modDrpDetail as $a => $detail){
												$sql = "update t_voucher_pengeluaran set status_drp = NULL where voucher_pengeluaran_id = ".$detail['voucher_pengeluaran_id'];
												Yii::$app->db->createCommand($sql)->execute();
											}
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
			$modDrp = \app\models\TPengajuanDrp::findOne(['kode'=>$modApprove->reff_no]);
			$modDrpDetail = \app\models\TPengajuanDrpDetail::findOne(['pengajuan_drp_id'=>$modDrp->pengajuan_drp_id]);
			$checkApprovals = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '".$modDrp->kode."' AND level < ".$modApprove->level)->queryAll();
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

			// hitung ulang cek approval buat status_approval di table t_pengajuan_drp, bikin dibawah jangan ganggu yang atas xD
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

			$lvl = Yii::$app->db->createCommand("select max(level) as lvl from t_approval where reff_no = '{$modDrp->kode}'")->queryOne();
			if($xxx < $lvl['lvl']){
				$sql_status_approval = "update t_pengajuan_drp set status_approve = 'Not Confirmed' where kode = '".$modApprove->reff_no."' ";
			} else {
				// $sql_status_approval = "update t_pengajuan_drp set status_approve = 'APPROVED' where kode = '".$modApprove->reff_no."' ";
				if ($xxx == $lvl['lvl'] - 1) {
					$sql_status_approval = "UPDATE t_pengajuan_drp SET status_approve = 'Not Confirmed' WHERE kode = '".$modApprove->reff_no."' ";
				} else {
					$sql_status_approval = "UPDATE t_pengajuan_drp SET status_approve = 'APPROVED' WHERE kode = '".$modApprove->reff_no."' ";
				}
			}
			Yii::$app->db->createCommand($sql_status_approval)->execute(); 
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
			$details = json_decode(Yii::$app->request->get('details'), true);
			$model = \app\models\TApproval::findOne($id);
			$modelReff = \app\models\TPengajuanDrp::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TPengajuanDrp')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['TPengajuanDrp']['reason_approval'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"APPROVED",
											'by'=> $model->assigned_to,
											'at'=>date('Y-m-d H:i:s'),
											'reason'=>$_POST['TPengajuanDrp']['reason_approval']
											];
								if(!empty($modelReff->reason_approval)){
									$reason = \yii\helpers\Json::decode($modelReff->reason_approval);
									$reason_approval = [];
									foreach($reason as $i => $reas){
										$reason_approval[] = $reas;
									}
									array_push($reason_approval, $arrPost);
								}else{
									$reason_approval[0] = $arrPost;
								}
								$modelReff->reason_approval = \yii\helpers\Json::encode($reason_approval);
								if($modelReff->validate()){
									if($modelReff->save()){
										$success_2 = true;
									}
								}

								//jika tidak approve semua, tapi level 3 approved maka status_approve-nya approved
								if($model->level == 3){
									$modDrp = \app\models\TPengajuanDrp::findOne(['kode'=>$model->reff_no]);
                                    $modDrp->status_approve = "APPROVED";
									$modDrpDetail = \app\models\TPengajuanDrpDetail::find()->where(['pengajuan_drp_id'=>$modDrp->pengajuan_drp_id])->all();
                                    if($modDrp->validate()){
                                        if($modDrp->save()){
                                            $success_4 = true;
											foreach($modDrpDetail as $a => $detail){
												$sql = "update t_voucher_pengeluaran set status_drp = 'drp' where voucher_pengeluaran_id = ".$detail['voucher_pengeluaran_id'];
												Yii::$app->db->createCommand($sql)->execute();
											}
										}
									}
								}

								//save checkbox ke status_pengajuan
								if(!empty($details)){
									foreach ($details as $d => $detail) {
										$modelDetail = \app\models\TPengajuanDrpDetail::findOne($detail['detailId']);
										if ($modelDetail) {
											$modelDetail->status_pengajuan = $detail['status'];
											$modelDetail->save();
										}
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
	
	public function actionSavePengajuan(){
		$drp_detail_id = Yii::$app->request->post('drp_detail_id');
		$status = Yii::$app->request->post('status');
		
		$model = TPengajuanDrpDetail::findOne($drp_detail_id);
		if ($model) {
			$model->status_pengajuan = $status;
			if ($model->save()) {
				return $this->asJson(['success' => true]);
			} else {
				return $this->asJson(['success' => false, 'error' => 'Gagal menyimpan data.']);
			}
		}
		return $this->asJson(['success' => false, 'error' => 'Data tidak ditemukan.']);
	}
}
