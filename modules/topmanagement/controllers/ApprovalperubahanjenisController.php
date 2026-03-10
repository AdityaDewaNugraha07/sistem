<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovalperubahanjenisController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',
								"t_log_rubahjenis.kode",
								'tanggal_berkas',
								't_log_rubahjenis.peruntukan',
								't_log_rubahjenis.keterangan',
								'assigned_nama', 
								'approved_by_nama',
								'level',
								$param['table'].'.status'];
			$param['where'] = "(reff_no ILIKE '%RJK%' and view_approval.status = 'Not Confirmed')";
			$param['join'] = "JOIN t_log_rubahjenis ON view_approval.reff_no = t_log_rubahjenis.kode
							  ";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
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
								"t_log_rubahjenis.kode",
								'tanggal_berkas',
								't_log_rubahjenis.peruntukan',
								't_log_rubahjenis.keterangan',
								'assigned_nama', 
								'approved_by_nama',
								'level',
								$param['table'].'.status'];
			$param['where'] = "(reff_no ILIKE '%RJK%' and view_approval.status != 'Not Confirmed')";
			$param['join'] = "JOIN t_log_rubahjenis ON view_approval.reff_no = t_log_rubahjenis.kode
							  ";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
            $param['order'] = "reff_no desc, level desc ";
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
			$modReff = \app\models\TLogRubahjenis::findOne(['kode'=>$model->reff_no]);
			$data['html'] = $this->renderPartial('show',['model'=>$model,'modReff'=>$modReff]);
			return $this->asJson($data);
        }
    }

    public function actionLihatHistory(){
        if(\Yii::$app->request->isAjax){
            $no_barcode = Yii::$app->request->get('no_barcode');
			$modPersediaan = \app\models\HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);

            $query = "  SELECT t.tanggal,
                                detail ->> 'no_barcode' AS no_barcode,
                                detail ->> 'no_lap' AS no_lap,
                                detail ->> 'kayu_id_old' AS kayu_id_old,
                                detail ->> 'kayu_id_new' AS kayu_id_new
                        FROM t_log_rubahjenis t, jsonb_array_elements(t.datadetail::jsonb) AS detail
                        WHERE detail ->> 'no_barcode' = '$no_barcode' and status_approve = 'APPROVED'
                        ORDER BY t.tanggal ASC";
            $model = Yii::$app->db->createCommand($query)->queryAll();

            return $this->renderAjax('lihatHistory',['model'=>$model, 'no_barcode'=>$no_barcode, 'no_lap'=>$modPersediaan->no_lap]);
        }
    }

    public function actionConfirm(){
		if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$modApprove = \app\models\TApproval::findOne($approval_id);
			$modReff = \app\models\TLogRubahjenis::findOne(['kode'=>$modApprove->reff_no]);
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

    public function actionApproveReason($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
            $modLogRubahjenis = \app\models\TLogRubahjenis::find()->where(['kode'=>$model->reff_no])->one();
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

                    if (!empty($modLogRubahjenis)){
                        $arrPost = ['status'=>"APPROVED",
                                    'by'=> $model->assigned_to,
                                    'at'=>date('Y-m-d H:i:s'),
                                    'reason'=>$_POST['TApproval']['keterangan']
                                    ];
                        if(!empty($modLogRubahjenis->reason_approval)){
                            $reason = \yii\helpers\Json::decode($modLogRubahjenis->reason_approval);
                            $reason_approval = [];
                            foreach($reason as $i => $reas){
                                $reason_approval[] = $reas;
                            }
                            array_push($reason_approval, $arrPost);
                        }else{
                            $reason_approval[0] = $arrPost;
                        }
                        $modLogRubahjenis->reason_approval = \yii\helpers\Json::encode($reason_approval);

                        // cek max level approval
                        $sql_max_level = "select max(level) from t_approval where reff_no = '".$model->reff_no."'";
                        $max_level_approval = Yii::$app->db->createCommand($sql_max_level)->queryScalar();
                        if ($model->level == $max_level_approval) {
                            $modLogRubahjenis->status_approve = "APPROVED";
                        } else {
                            $modLogRubahjenis->status_approve = "Not Confirmed";
                        }
                        if ($modLogRubahjenis->validate()) {
							if($modLogRubahjenis->save()) {
								$success_2 = true;

								// jika approved maka ubah kayu_id di persediaan
								$details = \yii\helpers\Json::decode($modLogRubahjenis->datadetail, true);
								foreach ($details as $detail) {
									$modPersediaan = \app\models\HPersediaanLog::findOne(['no_barcode' => $detail['no_barcode']]);
									if ($modPersediaan !== null) {
										$modPersediaan->kayu_id = $detail['kayu_id_new'];
										$modPersediaan->save();
									}
								}
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

    public function actionRejectReason($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
            $modPengajuanRepacking = \app\models\TLogRubahjenis::find()->where(['kode'=>$model->reff_no])->one();
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
                        
                        if(!empty($modPengajuanRepacking->reason_rejected)){
                            $reason = \yii\helpers\Json::decode($modPengajuanRepacking->reason_rejected);
                            $reason_rejected = [];
                            foreach($reason as $i => $reas){
                                $reason_rejected[] = $reas;
                            }
                            array_push($reason_rejected, $arrPost);
                        }else{
                            $reason_rejected[0] = $arrPost;
                        }
                        $modPengajuanRepacking->reason_rejected = \yii\helpers\Json::encode($reason_rejected);

                        //$modPengajuanRepacking->approved2_by = Yii::$app->user->identity->pegawai_id;
                        $modPengajuanRepacking->status_approve = "REJECTED";
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