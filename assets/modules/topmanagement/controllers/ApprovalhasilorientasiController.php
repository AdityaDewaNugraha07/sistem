<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovalhasilorientasiController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',
								'reff_no', 
								'tanggal_berkas', 
								't_hasil_orientasi.nama_iuphhk',
                                                                't_hasil_orientasi.nama_ipk',
								't_hasil_orientasi.lokasi_muat',
								't_hasil_orientasi.rkt_pertahun',
								'assigned_nama',
								'approved_by_nama', 
								'level', 
								$param['table'].'.status', 
								$param['table'].'.created_at', 
								$param['table'].'.created_at'];
			$param['where'] = "(reff_no ILIKE '%HOL%')";
			$param['join'] = "left join t_hasil_orientasi on view_approval.reff_no = t_hasil_orientasi.kode";
			//$param['order'] = "tanggal_berkas desc, reff_no desc";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
                if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){
                    $param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
                }
			}
			$param['order'] = "created_at DESC, reff_no desc, level DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index');
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
			$modReff = \app\models\THasilOrientasi::findOne(['kode'=>$model->reff_no]);
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
			$modelReff = \app\models\THasilOrientasi::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('THasilOrientasi')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['THasilOrientasi']['reject_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"REJECTED",
                                                    'by'=> $model->assigned_to,
                                                    'at'=>date('Y-m-d H:i:s'),
                                                    'reason'=>$_POST['THasilOrientasi']['reject_reason']
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
								
								/*
								if($modelReff->validate()){
									if($modelReff->save()){
										$success_2 = true;
									}
								}
								*/

                                $sqlUpdate =  " UPDATE t_hasil_orientasi
                                                SET reject_reason = '$modelReff->reject_reason'
                                                WHERE kode = '".$model->reff_no."' ";
                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();

							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }
					// echo "<pre>";
					// print_r($success_1);
					// echo "<pre>";
					// print_r($success_2);
					// exit;
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
			$modHO = \app\models\THasilOrientasi::findOne(['kode'=>$modApprove->reff_no]);
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

						// ambil user_id untuk update t_hasil_orientasi
						$user_id = Yii::$app->user->identity->user_id;

						$model->tanggal_approve = date('Y-m-d');
						
						// tanggal jam menit detik hari ini untuk update t_hasil_orientasi
						$updated_at = date('Y-m-d H:i:s');
						
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
								
								/*
								if($modelReff->validate()){
									if($modelReff->save()){
										$success_2 = true;
									}
								}*/

                                /*
                                $sqlUpdate =  " UPDATE t_hasil_orientasi
                                                SET approve_reason = '$modelReff->approve_reason'
                                                , updated_at = '$updated_at'
                                                , updated_by = $user_id
                                                WHERE kode = '".$model->reff_no."' ";
                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();
                                */

                                $sqlUpdate =  " UPDATE t_hasil_orientasi
                                                SET approve_reason = '$modelReff->approve_reason'
                                                WHERE kode = '".$model->reff_no."' ";
                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }
					// echo "<pre>";
					// print_r($success_1);
					// echo "<pre>";
					// print_r($success_2);
					// exit;
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
