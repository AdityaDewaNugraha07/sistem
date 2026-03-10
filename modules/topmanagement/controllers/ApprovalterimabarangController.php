<?php
namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovalterimabarangController extends DeltaBaseController
{
	public function actionIndex(){
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
								$param['table'].'.created_at',
								't_terima_bhp.tanggal_jam_checker'
								];
			$param['join'] = "left join t_terima_bhp on t_terima_bhp.terimabhp_kode = view_approval.reff_no 
								";
			$param['group'] = "group by approval_id, reff_no, tanggal_berkas, assigned_nama, approved_by_nama, level, view_approval.status, view_approval.created_at, view_approval.created_at, t_terima_bhp.tanggal_jam_checker ";
			$param['having'] = "having reff_no ILIKE '%TBP%' ";
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
								$param['table'].'.created_at',
								't_terima_bhp.tanggal_jam_checker'
								];
			$param['join'] = "left join t_terima_bhp on t_terima_bhp.terimabhp_kode = view_approval.reff_no 
								";
			$param['group'] = "group by approval_id, reff_no, tanggal_berkas, assigned_nama, approved_by_nama, level, view_approval.status, view_approval.created_at, view_approval.created_at, t_terima_bhp.tanggal_jam_checker ";
			$param['having'] = "having reff_no ILIKE '%TBP%' ";
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
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$model_t_terima_bhp = \app\models\TTerimaBhp::find()->where(["terimabhp_kode"=>$model->reff_no])->one();
				$tanggal_jam_checker = $model_t_terima_bhp->tanggal_jam_checker;
				$created_by = $model_t_terima_bhp->created_by;
				$sql = "select b.pegawai_nama from m_user a join m_pegawai b on b.pegawai_id = a.pegawai_id where a.user_id = ".$created_by;
				$created_by = Yii::$app->db->createCommand($sql)->queryScalar();
				$created_at = $model_t_terima_bhp->created_at;
			return $this->renderAjax('info',['model'=>$model, 'model_t_terima_bhp'=>$model_t_terima_bhp,'tanggal_jam_checker'=>$tanggal_jam_checker,'created_by'=>$created_by,'created_at'=>$created_at]);
		}
	}

    public function actionShowDetails(){
        if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$data['html'] = '';
			$data['html'] = $this->renderPartial('show', ['model'=>$model]);
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
			$modTTerimaBhp = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>$model->reff_no]);

			if( Yii::$app->request->post('TTerimaBhp')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					if(!empty($model) && !empty($_POST['TTerimaBhp']['reject_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						
						// ambil user_id untuk update t_terima_bhp
						$user_id = Yii::$app->user->identity->user_id;
						$model->tanggal_approve = date('Y-m-d');

						// tanggal jam menit detik hari ini untuk update t_terima_bhp
						$updated_at = date('Y-m-d H:i:s');

						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"REJECTED",
                                                    'by'=> $model->assigned_to,
                                                    'at'=>date('Y-m-d H:i:s'),
                                                    'reason'=>$_POST['TTerimaBhp']['reject_reason'],
                                                    'terimabhp_kode'=>trim($model->reff_no)
													];

								if(!empty($modTTerimaBhp->reject_reason)){
									$sql = "select json_array_length(reject_reason::JSON) from t_terima_bhp where trim(terimabhp_kode) = '".$model->reff_no."' ";
									$jumlah_json = Yii::$app->db->createCommand($sql)->queryScalar();

									if ($jumlah_json < 2) {
										$reason = \yii\helpers\Json::decode($modTTerimaBhp->reject_reason);
										$reject_reason = [];
										
										foreach($reason as $i => $reas){
											$reject_reason[] = $reas;
										}
										array_push($reject_reason, $arrPost);
									} else {
										$sql_empty_json = "update t_terima_bhp set reject_reason = NULL where trim(terimabhp_kode) = '".$model->reff_no."' ";
										$query = Yii::$app->db->createCommand($sql_empty_json)->execute();
										$reject_reason[0] = $arrPost;
									}
								}else{
									$reject_reason[0] = $arrPost;
								}
								$modTTerimaBhp->reject_reason = \yii\helpers\Json::encode($reject_reason);

								// approver satu level
								//approval 1 : kadiv hrd ga (andrian argasasmita 124)
								//approval 2 : kadiv akt (nowo eko yulianto 58)

								$approver1 = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>124]);
								$approver2 = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>58]);
								$approver1_status = $approver1->status;
								$approver2_status = $approver2->status;


								if ($approver1_status == 'REJECTED' || $approver2_status == 'REJECTED') {
									$insert_update = 1;
								} else {
									$insert_update = 0;
								}

	                            if ($insert_update == 1) {
	                                $sqlUpdate =  " UPDATE t_terima_bhp
	                                                SET reject_reason = '".$modTTerimaBhp->reject_reason."'
	                                                , status_approval = 'REJECTED'
	                                                WHERE terimabhp_kode = '".$model->reff_no."' ";
	                                $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();
	                            } else {
									$success_2 = true;
								}
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }

					if ($success_1 && $success_2) {
						/*$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
						(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');*/
						$transaction->rollback();
						$data['message'] = "<br>approver 1 124 ".$approver1_status."<br>approver 2 58 ".$approver2_status."<br>insert_update ".$insert_update;
					} else {
						$transaction->rollback();
						$data['status'] = false;
						(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');
						//$data['message'] = "<br>sukses 1 = ".$success_1."<br>sukses 2 = ".$success_2;
					}
				} catch (\yii\db\Exception $ex) {
					$transaction->rollback();
					$data['message'] = $ex;
				}
				return $this->asJson($data);
			}
			return $this->renderAjax('rejectReason',['modTTerimaBhp'=>$modTTerimaBhp,'id'=>$id]);
		}
	}
	
	public function actionConfirm(){
		if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$modApprove = \app\models\TApproval::findOne($approval_id);
			$modTTerimaBhp = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>$modApprove->reff_no]);
			//$checkApprovals = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '".$modTTerimaBhp->terimabhp_kode."' AND level = 1 AND status != 'APPROVED' ")->queryAll();
			$data = true;
			/*if(count($checkApprovals) > 0){
				foreach($checkApprovals as $i => $check){
					if($check['status'] != \app\models\TApproval::STATUS_NOT_CONFIRMATED){
						$data &= true;
					}else{
                        $data &= false;
                    }
				}
			}*/
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
			$modTTerimaBhp = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>$model->reff_no]);

			if( Yii::$app->request->post('TTerimaBhp')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					$success_2 = false;
					$success_3 = false;
					$success_4 = false;
					$success_7 = false;

					if(!empty($model) && !empty($_POST['TTerimaBhp']['approve_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;

						// ambil user_id untuk update t_terima_bhp
						$user_id = Yii::$app->user->identity->user_id;
						$model->tanggal_approve = date('Y-m-d');
						
						// tanggal jam menit detik hari ini untuk update t_terima_bhp
						$updated_at = date('Y-m-d H:i:s');
						
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$arrPost = ['status'=>"APPROVED",
											'by'=> $model->assigned_to,
											'at'=>date('Y-m-d H:i:s'),
											'reason'=>$_POST['TTerimaBhp']['approve_reason'],
											'terimabhp_kode'=>$model->reff_no
											];
								
								if(!empty($modTTerimaBhp->approve_reason)){
									$sql = "select json_array_length(approve_reason::JSON) from t_terima_bhp where trim(terimabhp_kode) = '".$model->reff_no."' ";
									$jumlah_json = Yii::$app->db->createCommand($sql)->queryScalar();

									if ($jumlah_json < 2) {
										$reason = \yii\helpers\Json::decode($modTTerimaBhp->approve_reason);
										$approve_reason = [];
										
										foreach($reason as $i => $reas){
											$approve_reason[] = $reas;
										}
										array_push($approve_reason, $arrPost);
									} else {
										$sql_empty_json = "update t_terima_bhp set approve_reason = NULL where trim(terimabhp_kode) = '".$model->reff_no."' ";
										$query = Yii::$app->db->createCommand($sql_empty_json)->execute();
										$approve_reason[0] = $arrPost;
									}
								}else{
									$approve_reason[0] = $arrPost;
								}
								$modTTerimaBhp->approve_reason = \yii\helpers\Json::encode($approve_reason);

								// approval tidak berjenjang
								//approval 1 : kadiv hrd ga (andrian argasasmita 124)
								//approval 2 : kadiv akt (nowo eko yulianto 58)
								$approver1 = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>124]);
								$approver2 = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>58]);
								$approver1_status = $approver1->status;
								$approver2_status = $approver2->status;

								if ($approver1_status == 'APPROVED' && $approver2_status == 'APPROVED') {
									$insert_update = 1;
								} else {
									$insert_update = 0;
								}
                                
	                            if ($insert_update > 0) {
	                                $sqlUpdate =  " UPDATE t_terima_bhp
	                                                SET approve_reason = '".$modTTerimaBhp->approve_reason."'
	                                                , status_approval = 'APPROVED'
	                                                WHERE terimabhp_kode = '".$model->reff_no."' ";
									$success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();

									/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
									// insert & update
									
									//$success_3 = true;  // insert h_persediaan_bhp 
									//$success_4 = true;  // update m_brg_bhp
									//$success_7 = true;  // update map_spp_detail_reff */
									// set success_3, success_4, dan success_7 setelah diapprove

									// insert h_persediaan_bhp
									// bhp_id, waktu_transaksi, qty_in, qty_out, keterangan, active, created_at, created_by, updated_at, updated_by, reff_no, reff_detail_id, tgl_transaksi
									$modTTerimaBhpDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$modTTerimaBhp->terima_bhp_id])->all();
									
									$sekarang = date('Y-m-d H:i:s');
									$user_id = Yii::$app->user->identity->id;
									// $success_3 = true;  // insert stock persediaan h_persediaan_bhp 

									foreach ($modTTerimaBhpDetail as $i => $detail) {
										// cek dulu udah ada datanya belum
										$terimabhpd_keterangan = pg_escape_string ($detail->terimabhpd_keterangan);
										$sql_cek_h_persediaan_bhp = "select count(*) from h_persediaan_bhp ".
																		"	where 1=1 ".
																		"	and bhp_id = '".$detail->bhp_id."' ".
																		"	and qty_in = '".$detail->terimabhpd_qty."' ".
																		//"	and keterangan = '".$terimabhpd_keterangan."' ".
																		"	and active = 'true' ".
																		"	and reff_no = '".$model->reff_no."' ".
																		"	and reff_detail_id = '".$detail->terima_bhpd_id."' ".
																		"	and tgl_transaksi =  '".$modTTerimaBhp->tglterima."' ".
																		"	";

										$numrows = Yii::$app->db->createCommand($sql_cek_h_persediaan_bhp)->queryScalar();

										if ($numrows > 0) {
											$success_3 = true;
										} else {
											$sql_h_persediaan_bhp = "insert into h_persediaan_bhp (bhp_id, waktu_transaksi, qty_in, keterangan, active ". 
																		"		, created_at, created_by, updated_at, updated_by, reff_no, reff_detail_id, tgl_transaksi) ".
																		"	values ". 
																		"	('".$detail->bhp_id."','".$modTTerimaBhp->tglterima."','".$detail->terimabhpd_qty."','".$detail->terimabhpd_keterangan."','true' ". 
																		"		, '".$modTTerimaBhp->created_at."','".$modTTerimaBhp->created_by."', '".$sekarang."', '".$user_id."', '".$model->reff_no."', '".$detail->terima_bhpd_id."', '".$modTTerimaBhp->tglterima."') ".
																		"	";
											$success_3 = Yii::$app->db->createCommand($sql_h_persediaan_bhp)->execute();
										}
										
										/*$detail->qty_in = $detail->terimabhpd_qty;
										$detail->qty_out = 0;
										$success_3 &= \app\models\HPersediaanBhp::updateStokPersediaan($detail, $modTTerimaBhp->terimabhp_kode, $detail->terima_bhpd_id, $modTTerimaBhp->tglterima);*/
										
										//$success_4 = true;  // update harga m_brg_bhp
										// bhp_id, bhp_harga, bhp_ppn, bhp_harga_pokok, bhp_include_ppn
										if(!empty($modTTerimaBhp->spo_id)){
											if($modTTerimaBhp->spo->spo_is_pkp){
												if($modTTerimaBhp->spo->spo_is_ppn){
													$harga_pokok = $detail->terimabhpd_harga;
													$nominal_ppn = (($detail->terimabhpd_harga * 1.1) - $detail->terimabhpd_harga);
													$harga = $harga_pokok + $nominal_ppn;
													$include_ppn = TRUE;
												}else{
													$harga_pokok = $detail->terimabhpd_harga;
													$nominal_ppn = $detail->terimabhpd_harga * 0.1;
													$harga = $detail->terimabhpd_harga;
													$include_ppn = FALSE;
												}
											}else{
												$harga_pokok = $detail->terimabhpd_harga;
												$nominal_ppn = 0;
												$harga = $harga_pokok;
												$include_ppn = FALSE;
											}
										}else{
											$harga_pokok = $detail->terimabhpd_harga;
											$nominal_ppn = 0;
											$harga = $harga_pokok;
											$include_ppn = FALSE;
										}
										$success_4 = \app\models\MBrgBhp::updateHargaBhp($detail->bhp_id,$harga_pokok,$nominal_ppn,$harga,$include_ppn); 

										//$success_7 = true;  // update map_spp_detail_reff */
										if (!empty($modTTerimaBhp->spo_id)) { 
											$sql_reff_no = "select spo_kode from t_spo where spo_id = ".$modTTerimaBhp->spo_id." ";
											$reff_no = Yii::$app->db->createCommand($sql_reff_no)->queryScalar();

											$sql_reff_detail_id = "select spod_id from t_spo_detail where spo_id = ".$modTTerimaBhp->spo_id." and bhp_id = ".$detail->bhp_id." ";
											$reff_detail_id = Yii::$app->db->createCommand($sql_reff_detail_id)->queryScalar();

											$modMap = \app\models\MapSppDetailReff::find()->where("reff_no = '".$reff_no."' AND reff_detail_id = ".$reff_detail_id)->one();
											if(!empty($modMap)){
												if($modMap->updateAll(['terima_bhpd_id'=>$detail->terima_bhpd_id],"reff_no = '".$reff_no."' AND reff_detail_id = ".$reff_detail_id)){
													$success_7 = true;
												}else{
													$success_7 = false;
												}
											}
	
										}
										
										//$success_7 = true;  // update map_spp_detail_reff */
										if (!empty($modTTerimaBhp->spl_id)) {
											$sql_reff_no = "select spl_kode from t_spl where spl_id = ".$modTTerimaBhp->spl_id." ";
											$reff_no = Yii::$app->db->createCommand($sql_reff_no)->queryScalar();

											$sql_reff_detail_id = "select spld_id from t_spl_detail where spl_id = ".$modTTerimaBhp->spl_id." and bhp_id = ".$detail->bhp_id." ";
											$reff_detail_id = Yii::$app->db->createCommand($sql_reff_detail_id)->queryScalar();

											$modMap = \app\models\MapSppDetailReff::find()->where("reff_no = '".$reff_no."' AND reff_detail_id = ".$reff_detail_id)->one();
											if(!empty($modMap)){
												if($modMap->updateAll(['terima_bhpd_id'=>$detail->terima_bhpd_id],"reff_no = '".$reff_no."' AND reff_detail_id = ".$reff_detail_id)){
													$success_7 = true;
												}else{
													$success_7 = false;
												}
											}
										}
									}
									/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
									
	                            } else {
									if ($approver1->status == 'Rejected' || $approver2->status == 'Rejected') {
										$sqlUpdate =  " UPDATE t_terima_bhp
														SET approve_reason = '".$modTTerimaBhp->approve_reason."'
														, status_approval = 'REJECTED'
														WHERE terimabhp_kode = '".$model->reff_no."' ";
										$success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();
									} else {
										$sqlUpdate =  " UPDATE t_terima_bhp
														SET approve_reason = '".$modTTerimaBhp->approve_reason."'
														, status_approval = 'Not Confirmed'
														WHERE terimabhp_kode = '".$model->reff_no."' ";
										$success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();										
									}
									$success_3 = true;
									$success_4 = true;
									$success_7 = true;
	                            }
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }

					if ($success_1 && $success_2 && $success_3 && $success_4 && $success_7) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
						(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');
                        
                        /*$transaction->rollback();
						$data['message'] = "<br>sukses_1 = ".$success_1."<br>sukses_2 = ".$success_2."<br>sukses_3 ".$success_3."<br>sukses_4 ".$success_4."<br>sukses_7 ".$success_7.
												"<br>approver1 = ".$approver1->status."<br>approver2 = ".$approver2->status.
												"<br>insert_update = ".$insert_update.
												" ";*/
					} else {
						$transaction->rollback();
						$data['status'] = false;
						(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');
						$data['message'] = "<br>sukses_1 = ".$success_1."<br>sukses_2 = ".$success_2."<br>sukses_3 ".$success_3."<br>sukses_4 ".$success_4."<br>sukses_7 ".$success_7."<br>approver1 = ".$approver1->status."<br>approver2 = ".$approver2->status."<br>yyy";
					}
				} catch (\yii\db\Exception $ex) {
					$transaction->rollback();
					$data['message'] = "<br>sukses_1 = ".$success_1."<br>sukses_2 = ".$success_2."<br>sukses_3 ".$success_3."<br>sukses_4 ".$success_4."<br>sukses_7 ".$success_7.
											"<br>approver1 = ".$approver1->status."<br>approver2 = ".$approver2->status.
                                            "<br>insert_update = ".$insert_update.
                                            "<br>ex ".$ex.
											" ";
					//$data['message'] = '';
				}
				return $this->asJson($data);
			}
			return $this->renderAjax('approveReason',['modTTerimaBhp'=>$modTTerimaBhp,'id'=>$id]);
		}
	}


}
