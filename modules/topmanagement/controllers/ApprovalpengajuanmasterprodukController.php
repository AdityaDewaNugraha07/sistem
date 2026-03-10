<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\TPengajuanMasterprodukDetail;

class ApprovalpengajuanmasterprodukController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table'] = \app\models\ViewApproval::tableName();
			$param['pk'] = "approval_id";
			$param['column'] = ['approval_id',															// 0
								'reff_no',																// 1
								['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'],	// 2
								't_pengajuan_masterproduk.keperluan',									// 3
                                't_pengajuan_masterproduk.status_pengajuan',    		                // 4
								'assigned_nama', 														// 5
								'approved_by_nama', 													// 6
								$param['table'].'.status', 												// 7
								$param['table'].'.created_at', 											// 8
								'level'];											                    // 9
			$param['where'] = "(substring(reff_no::TEXT,1,3) ='MPR') and view_approval.status = 'Not Confirmed'";
			$param['join'] = "join t_pengajuan_masterproduk on view_approval.reff_no = t_pengajuan_masterproduk.kode";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id."  ";
			}
			
			$param['order'] = "created_at DESC, level DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Not Confirmed']);
	}

	public function actionConfirmed(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table'] = \app\models\ViewApproval::tableName();
			$param['pk'] = "approval_id";
			$param['column'] = ['approval_id',															// 0
								'reff_no',																// 1
								['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'],	// 2
								't_pengajuan_masterproduk.keperluan',									// 3
                                't_pengajuan_masterproduk.status_pengajuan',    		                // 4
								'assigned_nama', 														// 5
								'approved_by_nama', 													// 6
								$param['table'].'.status', 												// 7
								$param['table'].'.created_at', 											// 8
								'level'];											                    // 9
			$param['where'] = "(substring(reff_no::TEXT,1,3) ='MPR') and view_approval.status != 'Not Confirmed'";
			$param['join'] = "join t_pengajuan_masterproduk on view_approval.reff_no = t_pengajuan_masterproduk.kode";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id."  ";
			}
			
			$param['order'] = "created_at DESC, level DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Confirmed']);
	}

	public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
            $modReff = \app\models\TPengajuanMasterproduk::findOne(['kode'=>$model->reff_no]);
            $modDetail = \app\models\TPengajuanMasterprodukDetail::find()->where(['pengajuan_masterproduk_id'=>$modReff->pengajuan_masterproduk_id])->all();
			return $this->renderAjax('info',['model'=>$model, 'modReff'=>$modReff, 'modDetail'=>$modDetail]);
		}
	}
        
	public function actionNotAllowed(){
		if(\Yii::$app->request->isAjax){
			$judul = "Agreement Confirm!";
			$pesan = "Anda belum bisa mengkonfirmasi ini, sebelum approver dibawah level anda mengkonfirmasi approval nya.";
			return $this->renderAjax('@views/apps/partial/_globalInfo',['judul'=>$judul,'pesan'=>$pesan,'actionname'=>'']);
		}
	}
    
	public function actionConfirm(){
		if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$modApprove = \app\models\TApproval::findOne($approval_id);
			$model = \app\models\TPengajuanMasterproduk::findOne(['kode'=>$modApprove->reff_no]);
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
            
            // hitung ulang cek approval buat status_approval di table t_pengajuan_masterproduk, bikin dibawah jangan ganggu yang atas xD
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
            if ($xxx < $lvl['lvl']) {
                $sql_status_approval = "update t_pengajuan_masterproduk set approval_status = 'Not Confirmed' where kode = '".$modApprove->reff_no."' ";
            } else {
				if ($xxx == $lvl['lvl'] - 1) {
					$sql_status_approval = "UPDATE t_pengajuan_masterproduk SET approval_status = 'Not Confirmed' WHERE kode = '".$modApprove->reff_no."' ";
				} else {
					$sql_status_approval = "UPDATE t_pengajuan_masterproduk SET approval_status = 'APPROVED' WHERE kode = '".$modApprove->reff_no."' ";

					//masukkan produk ke master
					$modDetail = TPengajuanMasterprodukDetail::findAll(['pengajuan_masterproduk_id'=>$model->pengajuan_masterproduk_id]);
					if(count($modDetail) > 0){
						foreach($modDetail as $ii => $detail){
							$produk_gbr 	= $detail->produk_gbr != null?"'$detail->produk_gbr'":"''";
							$jenis_kayu 	= $detail->jenis_kayu != null?"'$detail->jenis_kayu'":"''";
							$glue 			= $detail->glue != null?"'$detail->glue'":"''";
							$grade 			= $detail->grade != null?"'$detail->grade'":"''";
							$kondisi_kayu 	= $detail->kondisi_kayu != null?"'$detail->kondisi_kayu'":"''";
							$profil_kayu 	= $detail->profil_kayu != null?"'$detail->profil_kayu'":"''";
							$diameter_range = $detail->diameter_range != null?"'$detail->diameter_range'":"''";
							$warna_kayu 	= $detail->warna_kayu != null?"'$detail->warna_kayu'":"''";

							// copy gambardari req_produk ke folder produk
							$dir_path = Yii::$app->basePath.'/web/uploads/gud/produk';
							if(!is_dir($dir_path)){ 
								if(!is_dir(Yii::$app->basePath.'/web/uploads/gud')){
									mkdir(Yii::$app->basePath.'/web/uploads/gud');
								}
								mkdir($dir_path); 
							}
							if($detail->produk_gbr != null){
								$file_path = $detail->produk_gbr;
								$folder_new = Yii::$app->basePath . '/web/uploads/gud/req_produk/' . $detail->produk_gbr;
								$folder_old = $dir_path . '/' . $file_path;
												
								copy($folder_new, $folder_old);
							} 
							
							$insert_produk = "INSERT INTO m_brg_produk (produk_kode, produk_group, produk_nama, produk_dimensi, produk_p, produk_l, produk_t, produk_p_satuan, produk_l_satuan, produk_t_satuan,
											produk_satuan_besar, produk_satuan_kecil, produk_qty_satuan_kecil, produk_gbr, created_at, created_by, updated_at, updated_by, jenis_kayu, grade, glue, profil_kayu, 
											kondisi_kayu, diameter_range, warna_kayu, kapasitas_kubikasi) 
											VALUES ('$detail->produk_kode', '$detail->produk_group', '$detail->produk_nama', '$detail->produk_dimensi', $detail->produk_p, $detail->produk_l, $detail->produk_t, 
											'$detail->produk_p_satuan', '$detail->produk_l_satuan', '$detail->produk_t_satuan', '$detail->produk_satuan_besar', '$detail->produk_satuan_kecil',
											'$detail->produk_qty_satuan_kecil', $produk_gbr,  date_trunc('second', CURRENT_TIMESTAMP), $model->created_by,  date_trunc('second', CURRENT_TIMESTAMP), $model->updated_by, $jenis_kayu, $grade, $glue, 
											$profil_kayu, $kondisi_kayu, $diameter_range, $warna_kayu, $detail->kapasitas_kubikasi )";
							Yii::$app->db->createCommand($insert_produk)->execute();
						}
					}
					
				}
			}
            Yii::$app->db->createCommand($sql_status_approval)->execute();            
			return $this->asJson($data);
		}
	}

	public function actionApproveReason($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modelReff = \app\models\TPengajuanMasterproduk::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TPengajuanMasterproduk')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					$success_3 = false;
					if(!empty($model) && !empty($_POST['TPengajuanMasterproduk']['approve_reason'])){
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
											'reason'=>$_POST['TPengajuanMasterproduk']['approve_reason']
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

								$sql_json = "select approve_reason from t_pengajuan_masterproduk where kode = '".$model->reff_no."' ";
								$jsons = Yii::$app->db->createCommand($sql_json)->queryScalar();

								if (empty($jsons)) {
									$sqlUpdate =  " UPDATE t_pengajuan_masterproduk
													SET approve_reason = '$modelReff->approve_reason'
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
										$sqlUpdate =  " UPDATE t_pengajuan_masterproduk
														SET approve_reason = '$modelReff->approve_reason'
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
			$modelReff = \app\models\TPengajuanMasterproduk::findOne(['kode'=>$model->reff_no]);
			if( Yii::$app->request->post('TPengajuanMasterproduk')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['TPengajuanMasterproduk']['reject_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()) {
							if($model->save()) {
								$success_1 = true;
								$arrPost = ['status'=>"REJECTED",
													'by'=> $model->assigned_to,
													'at'=>date('Y-m-d H:i:s'),
													'reason'=>$_POST['TPengajuanMasterproduk']['reject_reason']
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

								$sqlUpdate =  " UPDATE t_pengajuan_masterproduk
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

								//ubah approval_status pengajuan jadi reject
								if($model->level == 1 || $model->level == 2 || $model->level == 3){
									$modPengajuan = \app\models\TPengajuanMasterproduk::findOne(['kode'=>$model->reff_no]);
                                    $modPengajuan->approval_status = "REJECTED";
									$modPengajuanDet = \app\models\TPengajuanMasterprodukDetail::find()->where(['pengajuan_masterproduk_id'=>$modPengajuan->pengajuan_masterproduk_id])->all();
                                    if($modPengajuan->validate()){
                                        if($modPengajuan->save()){
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
}
