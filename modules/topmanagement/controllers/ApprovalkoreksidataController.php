<?php

namespace app\modules\topmanagement\controllers;

use app\components\DeltaGenerator;
use Yii;
use app\controllers\DeltaBaseController;

class ApprovalkoreksidataController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id',						//0
								'kode', 							//1
								'tanggal_berkas', 					//2 
								't_pengajuan_manipulasi.tipe', 		//3
								'priority', 						//4
								'reason', 							//5
								'm_user.username AS dibuat_oleh', 	//6
								'assigned_nama', 					//7
								'approved_by_nama', 				//8
								'level', 							//9
								$param['table'].'.status']; 		//10
			$param['where'] = "(view_approval.reff_no ILIKE '%AMD%') AND t_pengajuan_manipulasi.tipe != 'CETAK ULANG LABEL PRODUK' and view_approval.status = 'Not Confirmed'";
			$param['join'] = "JOIN t_pengajuan_manipulasi on t_pengajuan_manipulasi.kode = view_approval.reff_no
                              JOIN m_user ON m_user.user_id = t_pengajuan_manipulasi.created_by";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "t_pengajuan_manipulasi.created_at DESC, level DESC";
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
								't_pengajuan_manipulasi.tipe', 		//3
								'priority', 						//4
								'reason', 							//5
								'm_user.username AS dibuat_oleh', 	//6
								'assigned_nama', 					//7
								'approved_by_nama', 				//8
								'level', 							//9
								$param['table'].'.status']; 		//10
			$param['where'] = "(view_approval.reff_no ILIKE '%AMD%') AND t_pengajuan_manipulasi.tipe != 'CETAK ULANG LABEL PRODUK' AND view_approval.status != 'Not Confirmed'";
			$param['join'] = "JOIN t_pengajuan_manipulasi on t_pengajuan_manipulasi.kode = view_approval.reff_no
                              JOIN m_user ON m_user.user_id = t_pengajuan_manipulasi.created_by";;
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "t_pengajuan_manipulasi.created_at DESC, level DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Confirmed']);
	}
	
	public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$modReff = \app\models\TPengajuanManipulasi::findOne(['kode'=>$model->reff_no]);
                        $modDetail = \yii\helpers\Json::decode($modReff->datadetail1);
			return $this->renderAjax('info',['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail]);
		}
	}
	
	public function actionApproveConfirm($id){
        if(\Yii::$app->request->isAjax){
                $model = \app\models\TApproval::findOne($id);
                $modReff = \app\models\TPengajuanManipulasi::findOne(['kode'=>$model->reff_no]);
                $berkas_nama = \app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no);
                $pesan = "Yakin akan menyetujui ".$berkas_nama." ini?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_approval 
                    $success_2 = true; // t_nota_penjualan
                    $success_3 = true; // t_surat_pengantar
                    $success_4 = true; // t_spm_ko
                    $success_5 = true; // t_dokumen_penjualan
                    $success_6 = true; // t_op_ko
                    $success_7 = true; // t_piutang_penjualan  
                    
                    if(!empty($model)){
                        $model->approved_by = Yii::$app->user->identity->pegawai_id;
                        $model->tanggal_approve = date('Y-m-d');
                        $model->status = \app\models\TApproval::STATUS_APPROVED;
                        if($model->validate()){
                            if($model->save()){
                                $success_1 = true;
                                
                                $maxlevel = Yii::$app->db->createCommand("SELECT MAX(level) AS maxlevel FROM t_approval WHERE reff_no = '".$modReff->kode."'")->queryOne()['maxlevel'];
                                if($maxlevel == $model->level){
                                    if( $modReff->tipe == "KOREKSI HARGA JUAL" ){
                                        
                                        // START t_nota_penjualan
                                        $new_data_nota = \yii\helpers\Json::decode($modReff->datadetail1)['new']['t_nota_penjualan'];
                                        $modNota = \app\models\TNotaPenjualan::findOne(['kode'=>$modReff->reff_no]);
                                        $modNota->attributes = $new_data_nota;
                                        $success_2 = $modNota->validate() && $modNota->save();
                                        // END t_nota_penjualan
                                        // START t_nota_penjualan_detail
                                        $new_data_nota_detail = \yii\helpers\Json::decode($modReff->datadetail1)['new']['t_nota_penjualan_detail'];
                                        if(!empty($new_data_nota_detail)){
                                            foreach($new_data_nota_detail as $i => $new){
                                                $modNotaDetail = \app\models\TNotaPenjualanDetail::findOne($new['nota_penjualan_detail_id']);
                                                $modNotaDetail->attributes = $new;
                                                $success_3 &= $modNotaDetail->validate() && $modNotaDetail->save();
                                            }
                                        }
                                        // END t_nota_penjualan_detail
                                        // START t_op_ko_detail
                                        $new_data_op_detail = \yii\helpers\Json::decode($modReff->datadetail1)['new']['t_op_ko_detail'];
                                        if(!empty($new_data_op_detail)){
                                            foreach($new_data_op_detail as $i => $new){
                                                $modOpDetail = \app\models\TOpKoDetail::findOne($new['op_ko_detail_id']);
                                                $modOpDetail->attributes = $new;
                                                $success_4 &= $modOpDetail->validate() && $modOpDetail->save();
                                            }
                                        }
                                        // END t_op_ko_detail
                                        
                                    }else if( $modReff->tipe == "KOREKSI NOPOL MOBIL" ){
                                        
                                        $new_data = \yii\helpers\Json::decode($modReff->datadetail1)['new'];
                                        $new_data_supir = \yii\helpers\Json::decode($modReff->datadetail1)['supir_new'];
                                        // START t_nota_penjualan
                                        $modNota = \app\models\TNotaPenjualan::findOne(['kode'=>$modReff->reff_no]);
                                        $modNota->kendaraan_nopol = $new_data;
                                        $modNota->kendaraan_supir = $new_data_supir;
                                        $success_2 = $modNota->validate() && $modNota->save();
                                        // END t_nota_penjualan
                                        // START t_surat_pengantar
                                        $modNSP = \app\models\TSuratPengantar::findOne(['nota_penjualan_id'=>$modNota->nota_penjualan_id]);
                                        $modNSP->kendaraan_nopol = $new_data;
                                        $modNSP->kendaraan_supir = $new_data_supir;
                                        $success_3 = $modNSP->validate() && $modNSP->save();
                                        // END t_surat_pengantar
                                        // START t_spm_ko
                                        $modSpm = \app\models\TSpmKo::findOne($modNota->spm_ko_id);
                                        $modSpm->kendaraan_nopol = $new_data;
                                        $modSpm->kendaraan_supir = $new_data_supir;
                                        $success_4 = $modSpm->validate() && $modSpm->save();
                                        // END t_spm_ko
                                        // START t_dokumen_penjualan
                                        if($modNota->jenis_produk == 'Limbah'){
                                            
                                        }else{
                                            $modDok = \app\models\TDokumenPenjualan::findOne(['spm_ko_id'=>$modNota->spm_ko_id]);
                                            if(!empty($modDok)){
                                                $modDok->kendaraan_nopol = $new_data;
                                                $modDok->kendaraan_supir = $new_data_supir;
                                                $success_5 = $modDok->validate() && $modDok->save();
                                            }
                                        }
                                        // END t_dokumen_penjualan
                                        
                                    }else if( $modReff->tipe == "KOREKSI ALAMAT BONGKAR" ){
                                        
                                        $new_data = \yii\helpers\Json::decode($modReff->datadetail1)['new'];
                                        // START t_nota_penjualan
                                        $modNota = \app\models\TNotaPenjualan::findOne(['kode'=>$modReff->reff_no]);
                                        $modNota->alamat_bongkar = $new_data;
                                        $success_2 = $modNota->validate() && $modNota->save();
                                        // END t_nota_penjualan
                                        // START t_surat_pengantar
                                        $modNSP = \app\models\TSuratPengantar::findOne(['nota_penjualan_id'=>$modNota->nota_penjualan_id]);
                                        $modNSP->alamat_bongkar = $new_data;
                                        $success_3 = $modNSP->validate() && $modNSP->save();
                                        // END t_surat_pengantar
                                        // START t_spm_ko
                                        $modSpm = \app\models\TSpmKo::findOne($modNota->spm_ko_id);
                                        $modSpm->alamat_bongkar = $new_data;
                                        $success_4 = $modSpm->validate() && $modSpm->save();
                                        // END t_spm_ko
                                        // START t_dokumen_penjualan
                                        if($modNota->jenis_produk == 'Limbah'){
                                            
                                        }else{
                                            $modDok = \app\models\TDokumenPenjualan::findOne(['spm_ko_id'=>$modNota->spm_ko_id]);
                                            if(!empty($modDok)){
                                                $modDok->alamat_bongkar = $new_data;
                                                $success_5 = $modDok->validate() && $modDok->save();
                                            }
                                        }
                                        // END t_dokumen_penjualan
                                        // START t_op_ko
                                        $modOp = \app\models\TOpKo::findOne(['op_ko_id'=>$modNota->op_ko_id]);
                                        $modOp->alamat_bongkar = $new_data;
                                        $success_6 = $modOp->validate() && $modOp->save();
                                        // END t_op_ko
                                        
                                    }else if( $modReff->tipe == "POTONGAN PIUTANG" ){
                                        
                                        $new_data = \yii\helpers\Json::decode($modReff->datadetail1)['new'];                                        
                                        // START t_piutang_penjualan
                                        $modPiutang = new \app\models\TPiutangPenjualan();
                                        $modPiutang->attributes = $new_data['t_piutang_penjualan'];
                                        $modPiutang->kode = DeltaGenerator::kodePPD();
                                        $success_7 = $modPiutang->validate() && $modPiutang->save();
                                        // END t_piutang_penjualan
                                        // START t_nota_penjualan
                                        $modNota = \app\models\TNotaPenjualan::findOne(['kode'=>$modReff->reff_no]);
                                        $modNota->status = $new_data['t_nota_penjualan']['status'];
//                                        $modNota->total_potongan = $new_data['t_nota_penjualan']['total_potongan'];
//                                        $modNota->keterangan_potongan = $new_data['t_nota_penjualan']['keterangan_potongan'];                                        
                                        $success_2 = $modNota->validate() && $modNota->save();
                                        // END t_nota_penjualan
                                    }
                                }
                            }
                        }
                    }
//                    echo "<pre>1";
//                    print_r($success_1);
//                    echo "<pre>2";
//                    print_r($success_2);
//                    echo "<pre>3";
//                    print_r($success_3);
//                    echo "<pre>4";
//                    print_r($success_4);
//                    exit;

                    if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5 && $success_6 && $success_7) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['callback'] = '$( "#close-btn-globalconfirm" ).click(); $("#modal-master-info").find(".fa.fa-close").click();';
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
	
	public function actionRejectReason($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			if( Yii::$app->request->post('TApproval')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false;
					if(!empty($model) && !empty($_POST['TApproval']['reject_reason'])){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_REJECTED;
                        $arrPost = ['status'=>"REJECTED",
                                            'by'=> $model->assigned_to,
                                            'at'=>date('Y-m-d H:i:s'),
                                            'reason'=>$_POST['TApproval']['reject_reason']
                                            ];
                        if(!empty($model->keterangan)){
                            $reason = \yii\helpers\Json::decode($model->keterangan);
                            $reject_reason = [];
                            foreach($reason as $i => $reas){
                                $reject_reason[] = $reas;
                            }
                            array_push($reject_reason, $arrPost);
                        }else{
                            $reject_reason[0] = $arrPost;
                        }
                        $model->keterangan = \yii\helpers\Json::encode($reject_reason);
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
							}
						}
					}else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }
//                    echo "<pre>";
//                    print_r($success_1);
//                    exit;
					if ($success_1) {
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
	
	public function actionConfirm(){
		if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$modApprove = \app\models\TApproval::findOne($approval_id);
			$modAselole = \app\models\TPengajuanManipulasi::findOne(['kode'=>$modApprove->reff_no]);
			$checkApprovals = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '".$modAselole->kode."' AND level < ".$modApprove->level)->queryAll();
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
						$model->tanggal_approve = date('Y-m-d');
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
