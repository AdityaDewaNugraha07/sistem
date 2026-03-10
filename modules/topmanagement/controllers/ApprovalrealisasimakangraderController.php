<?php
namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovalrealisasimakangraderController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',
								'reff_no', 
								'tanggal_berkas', 
								't_realisasimakan_grader.saldo_awal',
								't_realisasimakan_grader.total_realisasi',
								'm_graderlog.graderlog_nm',
								'assigned_nama',
								'approved_by_nama', 
								'level', 
								$param['table'].'.status', 
								$param['table'].'.created_at', 
								$param['table'].'.created_at'];
			$param['where'] = "(reff_no ILIKE '%RMG%' and view_approval.status = 'Not Confirmed')";
			$param['join'] = "left join t_realisasimakan_grader on t_realisasimakan_grader.kode = view_approval.reff_no ".
                                "   left join m_graderlog on m_graderlog.graderlog_id = t_realisasimakan_grader.graderlog_id ".
                                "   ";
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
								't_realisasimakan_grader.saldo_awal',
								't_realisasimakan_grader.total_realisasi',
								'm_graderlog.graderlog_nm',
								'assigned_nama',
								'approved_by_nama', 
								'level', 
								$param['table'].'.status', 
								$param['table'].'.created_at', 
								$param['table'].'.created_at'];
			$param['where'] = "(reff_no ILIKE '%RMG%' and view_approval.status != 'Not Confirmed')";
			$param['join'] = "left join t_realisasimakan_grader on t_realisasimakan_grader.kode = view_approval.reff_no ".
                                "   left join m_graderlog on m_graderlog.graderlog_id = t_realisasimakan_grader.graderlog_id ".
                                "   ";
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
            $modRMG = \app\models\TRealisasimakanGrader::find()->where(['kode'=>$model->reff_no])->one();
			return $this->renderAjax('info',['model'=>$model, 'modRMG'=>$modRMG]);
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
            $modRMG = \app\models\TRealisasimakanGrader::find()->where(['kode'=>$model->reff_no])->one();
			$data['html'] = $this->renderPartial('show',['model'=>$model,'modRMG'=>$modRMG]);
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
			$modRMG = \app\models\TRealisasimakanGrader::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TRealisasimakanGrader')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
                    $success_2 = false;
                    $success_3 = false;
                    $success_4 = false;
					if(!empty($model) && !empty($_POST['TRealisasimakanGrader']['approve_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;

						// ambil user_id untuk update t_realisasimakan_grader
						$user_id = Yii::$app->user->identity->user_id;
                        $pegawai_id = Yii::$app->db->createCommand("select pegawai_id from m_user where user_id = ".$user_id."")->queryScalar();
						$model->tanggal_approve = date('Y-m-d');
						
						// tanggal jam menit detik hari ini untuk update t_realisasimakan_grader
						$updated_at = date('Y-m-d H:i:s');
						
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"APPROVED",
											'by'=> $model->assigned_to,
											'at'=>date('Y-m-d H:i:s'),
											'reason'=>$_POST['TRealisasimakanGrader']['approve_reason']
											];
								if(!empty($modRMG->approve_reason)){
									$reason = \yii\helpers\Json::decode($modRMG->approve_reason);
									$approve_reason = [];
									foreach($reason as $i => $reas){
										$approve_reason[] = $reas;
									}
									array_push($approve_reason, $arrPost);
								}else{
									$approve_reason[0] = $arrPost;
								}
								$modRMG->approve_reason = \yii\helpers\Json::encode($approve_reason);

                                //t_realisasimakan_grader
                                $sqlUpdate =  "update t_realisasimakan_grader
                                                set approve_reason = '$modRMG->approve_reason'
                                                , approval_status = 'APPROVED'
                                                where kode = '".$model->reff_no."' ";
                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();

                                $sqlTApp = "update t_approval 
                                            set status = 'APPROVED', approved_by = ".$pegawai_id."
                                            , tanggal_approve = '".date('Y-m-d')."', updated_at = '".date('Y-m-d H:i:s')."'
                                            , updated_by = ".$user_id."
                                            where reff_no = '".$model->reff_no."' ".
                                            "   ";
                                $success_3 = Yii::$app->db->createCommand($sqlTApp)->execute();

                                // PINDAHAN DARI DINAS GRADER - BIAYA2 - REALISASI UANG MAKAN DINAS GRADER
                                // Start Proses Update Saldo
                                $modRMG->reff_no = $modRMG->kode;
                                $modRMG->nominal_in = 0;
                                $modRMG->nominal_out = $modRMG->total_realisasi;
                                $success_4 = \app\models\HKasMakangrader::updateSaldoKas($modRMG);
                                // End Proses Update Saldo
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }

					if ($success_1 && $success_2 && $success_3 && $success_4) {
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
			return $this->renderAjax('approveReason',['id'=>$id, 'model'=>$model, 'modRMG'=>$modRMG]);
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
			$modRMG = \app\models\TRealisasimakanGrader::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TRealisasimakanGrader')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
                    $success_3 = false;
					if(!empty($model) && !empty($_POST['TRealisasimakanGrader']['reject_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;

						// ambil user_id untuk update t_realisasimakan_grader
						$user_id = Yii::$app->user->identity->user_id;
                        $pegawai_id = Yii::$app->db->createCommand("select pegawai_id from m_user where user_id = ".$user_id."")->queryScalar();
						$model->tanggal_approve = date('Y-m-d');
						
						// tanggal jam menit detik hari ini untuk update t_realisasimakan_grader
						$updated_at = date('Y-m-d H:i:s');
						
						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"REJECTED",
											'by'=> $model->assigned_to,
											'at'=>date('Y-m-d H:i:s'),
											'reason'=>$_POST['TRealisasimakanGrader']['reject_reason']
											];
								if(!empty($modRMG->reject_reason)){
									$reason = \yii\helpers\Json::decode($modRMG->reject_reason);
									$reject_reason = [];
									foreach($reason as $i => $reas){
										$reject_reason[] = $reas;
									}
									array_push($reject_reason, $arrPost);
								}else{
									$reject_reason[0] = $arrPost;
								}
								$modRMG->reject_reason = \yii\helpers\Json::encode($reject_reason);

                                $sqlTRealisasimakanGrader =  " update t_realisasimakan_grader
                                                set reject_reason = '$modRMG->reject_reason',  
                                                approval_status = 'REJECTED'
                                                where kode = '".$model->reff_no."' ";
                                $success_2 = Yii::$app->db->createCommand($sqlTRealisasimakanGrader)->execute();

                                $sqlTApp = "update t_approval 
                                            set status = 'REJECTED', approved_by = ".$pegawai_id."
                                            , tanggal_approve = '".date('Y-m-d')."', updated_at = '".date('Y-m-d H:i:s')."'
                                            , updated_by = ".$user_id."
                                            where reff_no = '".$model->reff_no."' ".
                                            " ";
                                $success_3 = Yii::$app->db->createCommand($sqlTApp)->execute();
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }

					if ($success_1 && $success_2 && $success_3) {
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
			return $this->renderAjax('rejectReason',['modRMG'=>$modRMG,'id'=>$id]);
		}
	}
	
	public function actionConfirm(){
		if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$modApprove = \app\models\TApproval::findOne($approval_id);
			$modRMG = \app\models\TRealisasimakanGrader::findOne(['kode'=>$modApprove->reff_no]);
			$checkApprovals = Yii::$app->db->createCommand("select * from t_approval WHERE reff_no = '".$modRMG->kode."' AND level < ".$modApprove->level)->queryAll();
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
