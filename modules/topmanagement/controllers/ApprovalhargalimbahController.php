<?php
namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovalhargalimbahController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			/* SELECT approval_id, reff_no, tanggal_berkas, assigned_nama, approved_by_nama, level, view_approval.status, view_approval.created_at, view_approval.created_at, m_brg_produk.produk_group
				FROM view_approval
				left join m_harga_produk on m_harga_produk.kode = view_approval.reff_no 
				left join m_brg_produk on m_brg_produk.produk_id = m_harga_produk.produk_id 
				group by approval_id, reff_no, tanggal_berkas, assigned_nama, approved_by_nama, level, view_approval.status, view_approval.created_at, view_approval.created_at, m_brg_produk.produk_group
				HAVING (reff_no ILIKE '%PRP%') ORDER BY tanggal_berkas DESC, reff_no desc, level DESC 
				OFFSET 0 LIMIT 100 */

			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";

			$param['column'] = ['approval_id',
								'reff_no', 
								'tanggal_berkas', 
								'assigned_nama',
								'approved_by_nama', 
								'level', 
								$param['table'].'.status', 
								$param['table'].'.created_at', 
								$param['table'].'.created_at'
								];
			$param['join'] = "left join m_harga_limbah on m_harga_limbah.kode = view_approval.reff_no 
								left join m_brg_limbah on m_brg_limbah.limbah_id = m_harga_limbah.limbah_id 
								";
			$param['group'] = "group by approval_id, reff_no, tanggal_berkas, assigned_nama, approved_by_nama, level, view_approval.status, view_approval.created_at, view_approval.created_at";
			$param['having'] = "having reff_no ILIKE '%PRL%' ";
			$param['where'] = "view_approval.status = 'Not Confirmed' ";
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
								'assigned_nama',
								'approved_by_nama', 
								'level', 
								$param['table'].'.status', 
								$param['table'].'.created_at', 
								$param['table'].'.created_at'
								];
            $param['join'] = "left join m_harga_limbah on m_harga_limbah.kode = view_approval.reff_no 
            left join m_brg_limbah on m_brg_limbah.limbah_id = m_harga_limbah.limbah_id 
            ";
			$param['group'] = "group by approval_id, reff_no, tanggal_berkas, assigned_nama, approved_by_nama, level, view_approval.status, view_approval.created_at, view_approval.created_at";
			$param['having'] = "having reff_no ILIKE '%PRL%' ";
			$param['where'] = "view_approval.status != 'Not Confirmed' ";
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
        $model                  = \app\models\TApproval::findOne($id);
        $model_m_harga_limbah   = \app\models\MHargaLimbah::find()->where(["kode"=>$model->reff_no])->one();
        $harga_tanggal_penetapan= $model_m_harga_limbah->harga_tanggal_penetapan;
        $created_by             = $model_m_harga_limbah->created_by;
        $sql                    = "SELECT b.pegawai_nama FROM m_user a JOIN m_pegawai b ON b.pegawai_id = a.pegawai_id WHERE a.user_id = ".$created_by;
        $created_by             = Yii::$app->db->createCommand($sql)->queryScalar();
        $created_at             = $model_m_harga_limbah->created_at;
        // $model_m_brg_limbah     = \app\models\MBrgLimbah::find()->where(["limbah_id"=>$model_m_harga_limbah->limbah_id])->one();
        // $produk_group           = $model_m_brg_limbah->produk_group;
        return $this->renderAjax('info', [
            'model'=>$model, 
            // 'produk_group'=>$produk_group,
            'harga_tanggal_penetapan'=>$harga_tanggal_penetapan,
            'created_by'=>$created_by,
            'created_at'=>$created_at
        ]);
        // if(\Yii::$app->request->isAjax){
		// }
	}

    public function actionShowDetails(){
        if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$data['html'] = '';
			$model = \app\models\TApproval::findOne($approval_id);
			$modReff = \app\models\MHargaProduk::findOne(['kode'=>$model->reff_no]);
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
			$modelReff = \app\models\MHargaLimbah::findOne(['kode'=>$model->reff_no]);

			if( Yii::$app->request->post('MHargaLimbah')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['MHargaLimbah']['reject_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						
						// ambil user_id untuk update m_harga_produk
						$user_id = Yii::$app->user->identity->user_id;
						$model->tanggal_approve = date('Y-m-d');

						// tanggal jam menit detik hari ini untuk update m_harga_produk
						$updated_at = date('Y-m-d H:i:s');

						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"REJECTED",
                                            'by'=> $model->assigned_to,
                                            'at'=>date('Y-m-d H:i:s'),
                                            'reason'=>$_POST['MHargaLimbah']['reject_reason'],
                                            'kode'=>trim($model->reff_no)
                                            ];

								if(!empty($modelReff->reject_reason)){
									//select * from json_array_elements_text('["foo", "bar"]')
									$sql = "select json_array_length(reject_reason::JSON) from m_harga_limbah where trim(kode) = '".$model->reff_no."' ";
									$jumlah_json = Yii::$app->db->createCommand($sql)->queryScalar();

									if ($jumlah_json < 4) {
										$reason = \yii\helpers\Json::decode($modelReff->reject_reason);
										$reject_reason = [];
										
										foreach($reason as $i => $reas){
											$reject_reason[] = $reas;
										}
										array_push($reject_reason, $arrPost);
										$xxx = "insert";
									} else {
										$sql_empty_json = "update m_harga_limbah set reject_reason = NULL where trim(kode) = '".$model->reff_no."' ";
										$query = Yii::$app->db->createCommand($sql_empty_json)->execute();
										$reject_reason[0] = $arrPost;
										$xxx = "empty - insert";
									}
								}else{
									$reject_reason[0] = $arrPost;
								}
								$modelReff->reject_reason = \yii\helpers\Json::encode($reject_reason);

								// approval 1 : kadiv marketing (iwan s 19)
                                // approval 2 : dirut (heryanto suwardi 22)
								
                                $approver2 = \app\models\TApproval::findOne([
                                    'reff_no'=>$model->reff_no, 
                                    'assigned_to'=>\app\components\Params::DEFAULT_PEGAWAI_ID_ASENG
                                ]);

                                $final_status = $approver2->status;

	                            if ($model->level == 2 && $final_status == 'REJECTED') {
	                                $sqlUpdate =  " UPDATE m_harga_limbah
	                                                SET reject_reason = '".$modelReff->reject_reason."'
	                                                , status_approval = '".$final_status."'
	                                                WHERE kode = '".$model->reff_no."' ";
	                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();

	                            } else {

	                                $sqlUpdate =  " UPDATE m_harga_limbah
	                                                SET reject_reason = '".$modelReff->reject_reason."'
	                                                WHERE kode = '".$model->reff_no."' ";
	                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();
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
						(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');
						//$transaction->rollback();
						//$data['message'] = "<br>sukses 1 ".$success_1."<br>sukses 2 ".$success_2."<br>success_3 ".$success_3;
					} else {
						$transaction->rollback();
						$data['status'] = false;
						//(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						//(isset($data['message_validate']) ? $data['message'] = null : '');
						$data['message'] = "<br>sukses 1 = ".$success_1."<br>sukses 2 = ".$success_2; //."<br>sukses 3 = ".$success_3."<br>cust_max_plafond ".$cust_max_plafond."<br>m_cust_top ".count($m_cust_top)."<br>h_cust_top : ".count($h_cust_top);
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
			$modPRL = \app\models\MHargaLimbah::findOne(['kode'=>$modApprove->reff_no]);
			$checkApprovals = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '".$modPRL->kode."' AND level < ".$modApprove->level." AND status != 'APPROVED' ")->queryAll();
			$data = true;
			if(count($checkApprovals) > 0){
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
			$modelReff = \app\models\MHargaLimbah::findOne(['kode'=>$model->reff_no]);

			if( Yii::$app->request->post('MHargaLimbah')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['MHargaLimbah']['approve_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;

						// ambil user_id untuk update m_harga_produk
						$user_id = Yii::$app->user->identity->user_id;
						$model->tanggal_approve = date('Y-m-d');
						
						// tanggal jam menit detik hari ini untuk update m_harga_produk
						$updated_at = date('Y-m-d H:i:s');
						
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"APPROVED",
											'by'=> $model->assigned_to,
											'at'=>date('Y-m-d H:i:s'),
											'reason'=>$_POST['MHargaLimbah']['approve_reason'],
											'kode'=>$model->reff_no
											];
								
								if(!empty($modelReff->approve_reason)){
									$sql = "select json_array_length(approve_reason::JSON) from m_harga_limbah where trim(kode) = '".$model->reff_no."' ";
									$jumlah_json = Yii::$app->db->createCommand($sql)->queryScalar();

									if ($jumlah_json < 4) {
										$reason = \yii\helpers\Json::decode($modelReff->approve_reason);
										$approve_reason = [];
										
										foreach($reason as $i => $reas){
											$approve_reason[] = $reas;
										}
										array_push($approve_reason, $arrPost);
									} else {
										$sql_empty_json = "update m_harga_limbah set approve_reason = NULL where trim(kode) = '".$model->reff_no."' ";
										$query = Yii::$app->db->createCommand($sql_empty_json)->execute();
										$approve_reason[0] = $arrPost;
									}
								}else{
									$approve_reason[0] = $arrPost;
								}
								$modelReff->approve_reason = \yii\helpers\Json::encode($approve_reason);

							    // approval 1 : kadiv marketing (iwan s 19)
                                // approval 2 : dirut (heryanto suwardi 22)

								$approver2 = \app\models\TApproval::findOne([
                                    'reff_no'=>$model->reff_no, 
                                    'assigned_to'=> \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG
                                ]);
                                $final_status = $approver2->status;

	                            if ($model->level == 2 && $final_status == 'APPROVED') {
	                                $sqlUpdate =  " UPDATE m_harga_limbah
	                                                SET approve_reason = '".$modelReff->approve_reason."'
	                                                , status_approval = '".$final_status."'
	                                                WHERE kode = '".$model->reff_no."' ";
	                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();

	                            } else {
									
									$sqlUpdate =  " UPDATE m_harga_limbah
	                                                SET approve_reason = '".$modelReff->approve_reason."'
	                                                WHERE kode = '".$model->reff_no."' ";
                                    $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();
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
						(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');
						//$transaction->rollback();
						//$data['message'] = "<br>sukses 1 ".$success_1."<br>sukses 2 ".$success_2."<br>success_3 ".$success_3;
					} else {
						$transaction->rollback();
						$data['status'] = false;
						//(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						//(isset($data['message_validate']) ? $data['message'] = null : '');
						$data['message'] = "<br>sukses 1 = ".$success_1."<br>sukses 2 = ".$success_2;
					}
				} catch (\yii\db\Exception $ex) {
					$transaction->rollback();
					$data['message'] = $ex->getMessage();
				}
				return $this->asJson($data);
			}
			return $this->renderAjax('approveReason',['modelReff'=>$modelReff,'id'=>$id]);
		}
	}


}
