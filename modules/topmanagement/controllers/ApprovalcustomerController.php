<?php

namespace app\modules\topmanagement\controllers;

use app\components\DeltaGlobalClass;
use app\components\Params;
use app\components\SSP;
use app\models\HCustData;
use app\models\HCustomer;
use app\models\HCustTop;
use app\models\MCustomer;
use app\models\MCustTop;
use app\models\TApproval;
use app\models\ViewApproval;
use Yii;
use app\controllers\DeltaBaseController;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Response;

class ApprovalcustomerController extends DeltaBaseController
{
    /**
     * @version 2022-02-18
     * @note duplikat param order dan merapikan kode aja
     * @return string
     */
	public function actionIndex()
    {
        if(Yii::$app->request->get('dt') == 'table-master'){
			$param['table']     = ViewApproval::tableName();
			$param['pk']        = "approval_id";
			$param['column']    = [
                'approval_id',					    // 0
                'reff_no',							// 1
                'm_customer.cust_an_nama', 			// 2
                'tanggal_berkas', 					// 3
                'assigned_nama',					// 4
                'approved_by_nama', 				// 5
                'level', 							// 6
                $param['table'].'.status', 			// 7
                $param['table'].'.created_at', 		// 8
			];
			$param['where']     = "(reff_no ILIKE '%CUS%' and view_approval.status = 'Not Confirmed')";
			$param['join']      = "left join m_customer on m_customer.kode_customer = view_approval.reff_no";
//			$param['order'] = "tanggal_berkas desc, reff_no desc";
			if( Yii::$app->user->identity->user_group_id != Params::USER_GROUP_ID_SUPER_USER ){
                if(( Yii::$app->user->identity->user_group_id != Params::USER_GROUP_ID_OWNER )){
                    $param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
                }
			}
			$param['order'] = "tanggal_berkas DESC, reff_no desc, level DESC";
			return Json::encode(SSP::complex( $param ));
		}
		return $this->render('index', ['status' => 'Not Confirmed']);
	}

    public function actionIndexConfirmed()
    {
        if (Yii::$app->request->get('dt') == 'table-master') {
            $param['table'] = ViewApproval::tableName();
            $param['pk'] = "approval_id";
            $param['column'] = [
                'approval_id',                        // 0
                'reff_no',                            // 1
                'h_cust_data.cust_an_nama',            // 2
                'tanggal_berkas',                     // 3
                'assigned_nama',                      // 4
                'approved_by_nama',                   // 5
                'level',                              // 6
                $param['table'] . '.status',          // 7
                $param['table'] . '.created_at',      // 8
            ];
            $param['where'] = "(reff_no ILIKE '%CUS%' and view_approval.status != 'Not Confirmed')";
            $param['join'] = "left join h_cust_data on h_cust_data.kode_customer = view_approval.reff_no";
//            $param['order'] = "tanggal_berkas desc, reff_no desc";
            if (Yii::$app->user->identity->user_group_id != Params::USER_GROUP_ID_SUPER_USER) {
                if ((Yii::$app->user->identity->user_group_id != Params::USER_GROUP_ID_OWNER)) {
                    $param['where'] .= "AND assigned_to = " . Yii::$app->user->identity->pegawai_id . " ";
                }
            }
            $param['order'] = "tanggal_berkas DESC, reff_no desc, level DESC";
            return Json::encode(SSP::complex($param));
        }
        return $this->render('index', ['status' => 'Confirmed']);
    }

    /**
     * @param $id
     * @return string|void
     */
	public function actionInfo($id)
    {
		if(Yii::$app->request->isAjax){
			$model = TApproval::findOne($id);
			return $this->renderAjax('info', compact('model'));
		}
	}

    public function actionShowDetails(){
        if(Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$data['html'] = '';
			$model = TApproval::findOne($approval_id);
			$modReff = \app\models\HHargaProduk::findOne(['kode'=>$model->reff_no]);
			$data['html'] = $this->renderPartial('show',['model'=>$model,'modReff'=>$modReff]);
			return $this->asJson($data);
        }
    }

    /**
     * @note cuma dirapikan
     * @param $reff_no
     * @param $tipe
     * @return string|void
     */

    // fungsi menampilkan modal image
    public function actionImage($reff_no, $tipe)
    {
        if(Yii::$app->request->isAjax){
            $model = MCustomer::findOne(['kode_customer' => $reff_no]);
            if(!isset($model)) {
                $model = HCustData::findOne(['kode_customer' => $reff_no]);
            }
            return $this->renderAjax('image', ['model' => $model, 'tipe' => $tipe]);
        }
    }
    
    public function actionApproveConfirm($id){
		if(Yii::$app->request->isAjax){
			$model = TApproval::findOne($id);
			$berkas_nama = DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no);
			$pesan = "Yakin akan menyetujui ".$berkas_nama." ini?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					if(!empty($model)){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = TApproval::STATUS_APPROVED;
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
						(!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');
					}
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('@views/apps/partial/_globalConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'ApproveConfirm']);
		}
	}
	
	public function actionRejectConfirm($id){
		if(Yii::$app->request->isAjax){
			$model = TApproval::findOne($id);
			$berkas_nama = DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no);
			$pesan = "Yakin akan menolak ".$berkas_nama." ini?";
            if( Yii::$app->request->post('updaterecord')){
				$transaction = Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					if(!empty($model)){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = TApproval::STATUS_REJECTED;
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
						(!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');
					}
				} catch (Exception $ex) {
					$transaction->rollback();
					$data['message'] = $ex;
				}
				return $this->asJson($data);
			}
			return $this->renderAjax('@views/apps/partial/_globalConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'RejectConfirm']);
		}
	}

	public function actionRejectReason($id){
		if(Yii::$app->request->isAjax){
			$model      = TApproval::findOne($id);
			$modelReff  = MCustomer::findOne(['kode_customer'=>$model->reff_no]);
			$modelReffx = HCustomer::findOne(['kode_customer'=>$model->reff_no]);
			if( Yii::$app->request->post('MCustomer')){
				$transaction = Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['MCustomer']['reject_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						
						// ambil user_id untuk update m_customer
						$user_id = Yii::$app->user->identity->user_id;
						$model->tanggal_approve = date('Y-m-d');

						// tanggal jam menit detik hari ini untuk update m_customer
						$updated_at = date('Y-m-d H:i:s');

						$model->status = TApproval::STATUS_REJECTED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"REJECTED",
                                                    'by'=> $model->assigned_to,
                                                    'at'=>date('Y-m-d H:i:s'),
                                                    'reason'=>$_POST['MCustomer']['reject_reason'],
                                                    'kode_customer'=>trim($model->reff_no)
													];

								if(!empty($modelReff->reject_reason)){
									//select * from json_array_elements_text('["foo", "bar"]')
									$sql = "select json_array_length(reject_reason::JSON) from m_customer where trim(kode_customer) = '".$model->reff_no."' ";
									$jumlah_json = Yii::$app->db->createCommand($sql)->queryScalar();

									if ($jumlah_json < 2) {
										$reason = Json::decode($modelReff->reject_reason);
										$reject_reason = [];
										
										foreach($reason as $i => $reas){
											$reject_reason[] = $reas;
										}
										array_push($reject_reason, $arrPost);
										$xxx = "insert";
									} else {
										$sql_empty_json = "update m_customer set reject_reason = NULL where trim(kode_customer) = '".$model->reff_no."' ";
										$query = Yii::$app->db->createCommand($sql_empty_json)->execute();
										$reject_reason[0] = $arrPost;
										$xxx = "empty - insert";
									}
								}else{
									$reject_reason[0] = $arrPost;
								}
								$modelReff->reject_reason = Json::encode($reject_reason);

								if(!empty($modelReffx->reject_reason)){
									$reasonx = Json::decode($modelReffx->reject_reason);
									$approve_reason_hcustomer = [];
									foreach($reasonx as $i => $reas){
										$approve_reason_hcustomer[] = $reas;
									}
									array_push($approve_reason_hcustomer, $arrPost);
								}else{
									$approve_reason_hcustomer[0] = $arrPost;
								}
								$modelReffx->reject_reason = Json::encode($approve_reason_hcustomer);

								//approval 2 : dir (heryanto suwardi 22) dan kadiv finance (nowo eko yulianto 58)
                                $approver2 = TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>22]);
                                $final_status = $approver2->status;
                                $cust_max_plafond = HCustomer::find()->where(['kode_customer'=>$model->reff_no])->all();
                                if (count($cust_max_plafond) > 0) {
	                                $hcustomer = HCustomer::findOne(['kode_customer'=>$model->reff_no]);
	                                $cust_max_plafond = $hcustomer->cust_max_plafond;
	                            } else {
	                            	$cust_max_plafond = $modelReff->cust_max_plafond;
	                            }

	                            /*$m_cust_top = \app\models\MCustTop::find()->where(['cust_id'=>$modelReff->cust_id])->all();
	                            $h_cust_top = \app\models\HCustTop::find()->where(['kode_customer'=>$model->reff_no])->all();
                                if (count($h_cust_top) > 0 && $model->level == 2 && $final_status == 'REJECTED') {
	                                // delete m_cust_top yang lama 
	                                $m_customer = \app\models\MCustomer::find()->select('cust_id')->where(['kode_customer' => $approver2->reff_no])->one();
	                                $cust_id = $m_customer->cust_id;

	                                $modCustTopCurr = \app\models\MCustTop::find()->where(['cust_id'=>$cust_id])->all();
	                                $total_modCustTopCurr = count($modCustTopCurr);

	                                if(count($total_modCustTopCurr) > 0){
	                                    \app\models\MCustTop::deleteAll(['cust_id'=>$cust_id]);
	                                }

	                                // ambil data di h_cust_top
	                                $h_cust_top = \app\models\HCustTop::find()->where(['kode_customer'=>$model->reff_no])->all();
	                                $h_cust_top_count = count($h_cust_top);

	                                // insert m_cust_top yang baru
	                                foreach ($h_cust_top as $item) {
	                                	$m_cust_top = new \app\models\MCustTop();
	                                	$m_cust_top['cust_id'] = $item->cust_id;
	                                	$m_cust_top['custtop_jns'] = $item->custtop_jns;
	                                	$m_cust_top['custtop_top'] = $item->custtop_top;
	                                	$m_cust_top['active'] = true;
	                                	$m_cust_top['updated_at'] = $item->created_at;
	                                	$m_cust_top['updated_by'] = $item->created_by;
	                                	$m_cust_top->save();
	                                }

	                                // cek jangan sampai m_cust_top kosong cuy
	                                count($m_cust_top) > 0 ? $success_2 = true : $success_2 = false;	                                
	                            }*/

                                // cek jangan sampai m_cust_top kosong cuy
                                //count($m_cust_top) > 0 ? $success_2 = true : $success_2 = false;	                                

	                            if ($model->level == 2 && $final_status == 'REJECTED') {
	                                $sqlUpdate =  " UPDATE m_customer
	                                                SET reject_reason = '".$modelReff->reject_reason."'
	                                                , status_approval = '".$final_status."'
	                                                , cust_max_plafond = '".$cust_max_plafond."'
	                                                WHERE kode_customer = '".$model->reff_no."' ";
	                                $success_3 = Yii::$app->db->createCommand($sqlUpdate)->execute();

	                                $sqlUpdatex =  " UPDATE h_customer
	                                                SET reject_reason = '".$modelReffx->reject_reason."'
	                                                , status_approval = '".$final_status."'
	                                                , cust_max_plafond = '".$cust_max_plafond."'
	                                                WHERE kode_customer = '".$model->reff_no."' ";
	                                $success_4 = Yii::$app->db->createCommand($sqlUpdatex)->execute();

	                            } else {

	                                $sqlUpdate =  " UPDATE m_customer
	                                                SET reject_reason = '".$modelReff->reject_reason."'
	                                                WHERE kode_customer = '".$model->reff_no."' ";
	                                $success_3 = Yii::$app->db->createCommand($sqlUpdate)->execute();

	                                $sqlUpdatex =  " UPDATE h_customer
	                                                SET reject_reason = '".$modelReffx->reject_reason."'
	                                                WHERE kode_customer = '".$model->reff_no."' ";
	                                $success_4 = Yii::$app->db->createCommand($sqlUpdatex)->execute();
	                            }

                                $sqlUpdate =  " UPDATE m_customer
                                                SET reject_reason = '".$modelReff->reject_reason."'
                                                , status_approval = '".$final_status."'
                                                WHERE kode_customer = '".$model->reff_no."' ";
                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();

							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }

					if ($success_1 && $success_2 && $success_3 && $success_4) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
						(!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');
						//$transaction->rollback();
						//$data['message'] = "<br>sukses 1 ".$success_1."<br>sukses 2 ".$success_2."<br>success_3 ".$success_3;
					} else {
						$transaction->rollback();
						$data['status'] = false;
						//(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						//(isset($data['message_validate']) ? $data['message'] = null : '');
						$data['message'] = "<br>sukses 1 = ".$success_1."<br>sukses 2 = ".$success_2."<br>sukses 3 = ".$success_3."<br>cust_max_plafond ".$cust_max_plafond."<br>m_cust_top ".count($m_cust_top)."<br>h_cust_top : ".count($h_cust_top);
					}
				} catch (Exception $ex) {
					$transaction->rollback();
					$data['message'] = $ex;
				}
				return $this->asJson($data);
			}
			return $this->renderAjax('rejectReason',['modelReff'=>$modelReff,'id'=>$id]);
		}
	}

    /**
     * @version 2022-02-19
     * @note cuma dirapikan
     * @return void|Response
     * @throws Exception
     */
	public function actionConfirm(){
		if(Yii::$app->request->isAjax){
			$approval_id    = Yii::$app->request->post('approval_id');
			$modApprove     = TApproval::findOne($approval_id);
			$modCUS         = MCustomer::findOne(['kode_customer'=>$modApprove->reff_no]);
			$checkApprovals = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '".$modCUS->kode_customer."' AND level < ".$modApprove->level." AND status != 'APPROVED' ")->queryAll();
			$data           = true;
			if(count($checkApprovals) > 0){
				foreach($checkApprovals as $check){
					if($check['status'] != TApproval::STATUS_NOT_CONFIRMATED){
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
		if(Yii::$app->request->isAjax){
			$judul = "Agreement Confirm!";
			$pesan = "Anda belum bisa mengkonfirmasi ini, sebelum approver dibawah level anda mengkonfirmasi approval nya.";
			return $this->renderAjax('@views/apps/partial/_globalInfo',['judul'=>$judul,'pesan'=>$pesan,'actionname'=>'']);
		}
	}

    /**
     * @version 2022-02-19
     * @note cuma dirapikan
     * @param $id
     * @return string|void|Response
     * @throws Exception
     */

    public function actionApproveReason($id)
    {
        if (Yii::$app->request->isAjax) {
            $model      = TApproval::findOne($id);
            $modelMCustomer  = MCustomer::findOne(['kode_customer' => $model->reff_no]);
            $modelHCustomer = HCustomer::findOne(['kode_customer' => $model->reff_no]);
            if (Yii::$app->request->post('MCustomer')) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_tapproval          = false;
                    $success_mcusttop           = false;
                    $success_update_mcustomer   = false;
                    $success_update_hcustomer   = false;
                    $h_cust_top                 = 0;
                    if (!empty($model) && !empty($_POST['MCustomer']['approve_reason'])) {
                        $model->approved_by     = Yii::$app->user->identity->pegawai_id;
                        $model->tanggal_approve = date('Y-m-d');
                        $model->status          = TApproval::STATUS_APPROVED;
                        if ($model->validate() && $model->save()) {
                            $success_tapproval = true;
                            $arrPost = [
                                'status'        => "APPROVED",
                                'by'            => $model->assigned_to,
                                'at'            => date('Y-m-d H:i:s'),
                                'reason'        => $_POST['MCustomer']['approve_reason'],
                                'kode_customer' => $model->reff_no
                            ];

                            if (!empty($modelMCustomer->approve_reason)) {
                                $sql = "select json_array_length(approve_reason::JSON) from m_customer where trim(kode_customer) = '" . $model->reff_no . "' ";
                                $jumlah_json = Yii::$app->db->createCommand($sql)->queryScalar();

                                if ($jumlah_json < 2) {
                                    $reason = Json::decode($modelMCustomer->approve_reason);
                                    $approve_reason = [];

                                    foreach ($reason as $i => $reas) {
                                        $approve_reason[] = $reas;
                                    }
                                    $approve_reason[] = $arrPost;
                                } else {
                                    $sql_empty_json = "update m_customer set approve_reason = NULL where trim(kode_customer) = '" . $model->reff_no . "' ";
                                    Yii::$app->db->createCommand($sql_empty_json)->execute();
                                    $approve_reason[0] = $arrPost;
                                }
                            } else {
                                $approve_reason[0] = $arrPost;
                            }
                            $modelMCustomer->approve_reason = Json::encode($approve_reason);

                            if(!empty($modelHCustomer)) {
                                if (!empty($modelHCustomer->approve_reason)) {
                                    $reason_hcustomer           = Json::decode($modelHCustomer->approve_reason);
                                    $approve_reason_hcustomer   = [];
                                    foreach ($reason_hcustomer as $reas) {
                                        $approve_reason_hcustomer[] = $reas;
                                    }
                                    $approve_reason_hcustomer[] = $arrPost;
                                } else {
                                    $approve_reason_hcustomer[0] = $arrPost;
                                }
                                $modelHCustomer->approve_reason = Json::encode($approve_reason_hcustomer);
                            }

                            //approval 2 : dir (heryanto suwardi 22) dan kadiv finance (nowo eko yulianto 58)
                            $approver2          = TApproval::findOne(['reff_no' => $model->reff_no, 'assigned_to' => 22]);
                            $final_status       = $approver2->status;
                            $h_cust_top         = HCustTop::find()->where(['kode_customer' => $model->reff_no])->all();

//                            jika insert pertama kali kode ini tidak di eksekusi
//                            di tandai dengan mengecek di hcusttop, jika kosong maka ini adalah aproval pertama kali
//                            berikutnya ketika ada perubahan pada TOP maka akan di insert dulu ke hcusttop
                            if (count($h_cust_top) > 0 && $model->level == 2 && $final_status == 'APPROVED') {
                                MCustTop::deleteAll(['cust_id' => $modelMCustomer->cust_id]);

                                // insert m_cust_top yang baru
                                foreach ($h_cust_top as $item) {
                                    $m_cust_top = new MCustTop();
                                    $m_cust_top['cust_id']      = $item->cust_id;
                                    $m_cust_top['custtop_jns']  = $item->custtop_jns;
                                    $m_cust_top['custtop_top']  = $item->custtop_top;
                                    $m_cust_top['active']       = true;
                                    $m_cust_top['created_at']   = $item->created_at;
                                    $m_cust_top['created_by']   = Yii::$app->user->id;
                                    $m_cust_top['updated_at']   = date('Y-m-d H:i:s');
                                    $m_cust_top['updated_by']   = Yii::$app->user->id;
                                    $m_cust_top->save();
                                }

                                // cek jangan sampai m_cust_top kosong cuy
                                $m_cust_top = MCustTop::find()->where(['cust_id' => $modelMCustomer->cust_id])->all();
                                $success_mcusttop = count($m_cust_top) > 0;
                            }

                            if (!empty($modelHCustomer)) {
                                $cust_max_plafond   = $modelHCustomer->cust_max_plafond;
                            } else {
                                $cust_max_plafond   = $modelMCustomer->cust_max_plafond;
                            }

                            if ($model->level == 2 && $final_status == 'APPROVED') {
                                $sqlUpdate  = "UPDATE m_customer
                                                SET approve_reason = '$modelMCustomer->approve_reason'
                                                , status_approval = '$final_status'
                                                , cust_max_plafond = '$cust_max_plafond'
                                                WHERE kode_customer = '$model->reff_no' ";
                                $success_update_mcustomer  = Yii::$app->db->createCommand($sqlUpdate)->execute();

                                if(!empty($modelHCustomer)) {
                                    $sqlUpdatex = "UPDATE h_customer
                                                    SET approve_reason = '$modelHCustomer->approve_reason'
                                                    , status_approval = '$final_status'
                                                    , cust_max_plafond = '$cust_max_plafond'
                                                    WHERE kode_customer = '$model->reff_no' ";
                                    $success_update_hcustomer = Yii::$app->db->createCommand($sqlUpdatex)->execute();
                                }
                            } else {
                                $sqlUpdate  = "UPDATE m_customer
                                                SET approve_reason = '$modelMCustomer->approve_reason'
                                                WHERE kode_customer = '$model->reff_no' ";
                                $success_update_mcustomer  = Yii::$app->db->createCommand($sqlUpdate)->execute();

                                if(!empty($modelHCustomer)) {
                                    $sqlUpdatex = "UPDATE h_customer
                                                    SET approve_reason = '$modelHCustomer->approve_reason'
                                                    WHERE kode_customer = '$model->reff_no' ";
                                    $success_update_hcustomer = Yii::$app->db->createCommand($sqlUpdatex)->execute();

                                }
                            }


                        }
                    } else {
                        $data['message'] = "Maaf, alasan tidak boleh kosong";
                    }

                    if ($model->level == 2 && count($h_cust_top) > 0) {
                        $kondisi = $success_tapproval && $success_mcusttop && $success_update_mcustomer;
                    } else {
                        $kondisi = $success_tapproval && $success_update_mcustomer;
                    }

                    if ($kondisi) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['callback'] = '';
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        $data['message'] = "<br>sukses tapproval = " . $success_tapproval .
                            "<br>sukses mcusttop = " . $success_mcusttop .
                            "<br>sukses update mcustomer = " . $success_update_mcustomer .
                            "<br>sukses 4 = " . $success_update_hcustomer;
                    }
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('approveReason', compact('modelMCustomer', 'id'));
        }
    }
	
}
