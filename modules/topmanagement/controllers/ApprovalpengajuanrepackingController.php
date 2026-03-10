<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovalpengajuanrepackingController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',
								"t_pengajuan_repacking.kode",
								'tanggal_berkas',
								't_pengajuan_repacking.keperluan',
								't_pengajuan_repacking.keterangan',
                                '( SELECT SUM(qty_besar) FROM t_pengajuan_repacking_detail WHERE t_pengajuan_repacking_detail.pengajuan_repacking_id = t_pengajuan_repacking.pengajuan_repacking_id ) AS total_palet',
								'assigned_nama', 
								'approved_by_nama',
								'level',
								$param['table'].'.status'];
			$param['where'] = "(reff_no ILIKE '%ARP%' and view_approval.status = 'Not Confirmed')";
			$param['join'] = "JOIN t_pengajuan_repacking ON view_approval.reff_no = t_pengajuan_repacking.kode
							  ";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			/*$param['order'] = "CASE view_approval.status 
                                WHEN 'Not Confirmed' THEN 1
                                END, view_approval.created_at DESC";*/
            $param['order'] = "reff_no desc, level desc ";
            return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));

		}
		return $this->render('index',['status' => 'Not Confirmed']);
	}

	public function actionIndexConfirmed(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',
								"t_pengajuan_repacking.kode",
								'tanggal_berkas',
								't_pengajuan_repacking.keperluan',
								't_pengajuan_repacking.keterangan',
                                '( SELECT SUM(qty_besar) FROM t_pengajuan_repacking_detail WHERE t_pengajuan_repacking_detail.pengajuan_repacking_id = t_pengajuan_repacking.pengajuan_repacking_id ) AS total_palet',
								'assigned_nama', 
								'approved_by_nama',
								'level',
								$param['table'].'.status'];
			$param['where'] = "(reff_no ILIKE '%ARP%' and view_approval.status != 'Not Confirmed')";
			$param['join'] = "JOIN t_pengajuan_repacking ON view_approval.reff_no = t_pengajuan_repacking.kode
							  ";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = " CASE view_approval.status 
                                WHEN 'Not Confirmed' THEN 1
                                END, view_approval.created_at DESC";
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

    public function actionShowDetails(){
        if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$data['html'] = '';
			$model = \app\models\TApproval::findOne($approval_id);
			$modReff = \app\models\TPengajuanRepacking::findOne(['kode'=>$model->reff_no]);
			$modDetail = \app\models\TPengajuanRepackingDetail::find()->where(['pengajuan_repacking_id'=>$modReff->pengajuan_repacking_id])->all();
			$data['html'] = $this->renderPartial('show',['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail]);
			return $this->asJson($data);
        }
    }
    
    public function actionConfirm(){
		if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$modApprove = \app\models\TApproval::findOne($approval_id);
			$modReff = \app\models\TPengajuanRepacking::findOne(['kode'=>$modApprove->reff_no]);
			$checkApprovals = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '".$modReff->kode."' AND level < ".$modApprove->level)->queryAll();
			$data['status'] = true; $data['alasanapprove'] = true;
			if(count($checkApprovals)>0){
				foreach($checkApprovals as $i => $check){
					if($check['status'] == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
						$data['status'] &= false;
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
            $modPengajuanRepacking = \app\models\TPengajuanRepacking::find()->where(['kode'=>$model->reff_no])->one();
			if( Yii::$app->request->post('TApproval')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
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

                    if (!empty($modPengajuanRepacking)){
                        $arrPost = ['status'=>"APPROVED",
                                    'by'=> $model->assigned_to,
                                    'at'=>date('Y-m-d H:i:s'),
                                    'reason'=>$_POST['TApproval']['keterangan']
                                    ];
                        if(!empty($modPengajuanRepacking->approve_reason)){
                            $reason = \yii\helpers\Json::decode($modPengajuanRepacking->approve_reason);
                            $approve_reason = [];
                            foreach($reason as $i => $reas){
                                $approve_reason[] = $reas;
                            }
                            array_push($approve_reason, $arrPost);
                        }else{
                            $approve_reason[0] = $arrPost;
                        }
                        $modPengajuanRepacking->approve_reason = \yii\helpers\Json::encode($approve_reason);

                        // cek max level approval
                        $sql_max_level = "select max(level) from t_approval where reff_no = '".$model->reff_no."'";
                        $max_level_approval = Yii::$app->db->createCommand($sql_max_level)->queryScalar();
                        if ($model->level == $max_level_approval) {
                            $modPengajuanRepacking->approval_status = "APPROVED";
                        } else {
                            $modPengajuanRepacking->approval_status = "Not Confirmed";
                        }
                        if ($modPengajuanRepacking->validate()) {
							if($modPengajuanRepacking->save()) {
								$success_2 = true;
							}
						}
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
			return $this->renderAjax('approveReason',['model'=>$model,'id'=>$id]);
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
            $modPengajuanRepacking = \app\models\TPengajuanRepacking::find()->where(['kode'=>$model->reff_no])->one();
			if( Yii::$app->request->post('TApproval')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model)){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_REJECTED;
                        $sql_max_level = "select max(level) from t_approval where reff_no = '".$model->reff_no."'";
                        $max_level = Yii::$app->db->createCommand($sql_max_level)->queryScalar();
                        for ($i=$model->level; $i<=$max_level; $i++) {
                            $modelY = \app\models\TApproval::find()->where(['reff_no'=>$model->reff_no,'level'=>$i])->one();
                            $modelY->approved_by = Yii::$app->user->identity->pegawai_id;
                            $modelY->tanggal_approve = date('Y-m-d');
                            $modelY->status = 'REJECTED';
                            $modelY->updated_at = date('Y-m-d H:i:s');
                            $modelY->updated_by = Yii::$app->user->identity->user_id;
                            $modelY->save();
                        }

                        if($model->validate()){
							if($model->save()){
								if ($modelY->save()) {
                                    $success_1 = true;
                                }
							}
						}
                        
					}

                    if (!empty($modPengajuanRepacking)){
                        $arrPost = ['status'=>"REJECTED",
                                    'by'=> $model->assigned_to,
                                    'at'=>date('Y-m-d H:i:s'),
                                    'reason'=>$_POST['TApproval']['keterangan']
                                    ];
                        
                        if(!empty($modPengajuanRepacking->reject_reason)){
                            $reason = \yii\helpers\Json::decode($modPengajuanRepacking->reject_reason);
                            $reject_reason = [];
                            foreach($reason as $i => $reas){
                                $reject_reason[] = $reas;
                            }
                            array_push($reject_reason, $arrPost);
                        }else{
                            $reject_reason[0] = $arrPost;
                        }
                        $modPengajuanRepacking->reject_reason = \yii\helpers\Json::encode($reject_reason);

                        //$modPengajuanRepacking->approved2_by = Yii::$app->user->identity->pegawai_id;
                        $modPengajuanRepacking->approval_status = "REJECTED";
                        if ($modPengajuanRepacking->validate()) {
							if($modPengajuanRepacking->save()) {
								$success_2 = true;
							}
						}
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
			return $this->renderAjax('rejectReason',['model'=>$model,'id'=>$id]);
		}
	}
	
}
