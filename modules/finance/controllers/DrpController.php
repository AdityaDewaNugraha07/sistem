<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\MPegawai;
use app\models\TApproval;
use app\models\TPengajuanDrp;
use app\models\TPengajuanDrpDetail;

class DrpController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TPengajuanDrp();
		$modDrpDetail = new \app\models\TPengajuanDrpDetail(); 
        $model->kode = 'Auto Generate';
        // $model->tanggal = date('d/m/Y');
		$model->approver_1 = \app\components\params::DEFAULT_PEGAWAI_ID_YOSUA;
		$model->approver_2 = \app\components\params::DEFAULT_PEGAWAI_ID_EKO_NOWO;
		$model->approver_3 = \app\components\params::DEFAULT_PEGAWAI_ID_ASENG;

		if (isset($_GET['pengajuan_drp_id'])) {
            $model = \app\models\TPengajuanDrp::findOne($_GET['pengajuan_drp_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
        }

		if (Yii::$app->request->post('TPengajuanDrp')) {
			$transaction = \Yii::$app->db->beginTransaction();
			try {
                $success_1 = false; // t_pengajuan_drp
                $success_2 = true; // t_pengajuan_drp_detail
                $success_3 = true; // t_approval
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodePengajuanDrp();
					$model->status_approve = 'Not Confirmed';
				}
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;

                        if(!empty($_POST['TPengajuanDrpDetail'])){
                            if(isset($_GET['edit'])){
								// ubah status_drp database
								$modDetails = TPengajuanDrpDetail::find()->where(['pengajuan_drp_id'=>$model->pengajuan_drp_id])->all();
								if(count($modDetails) > 0){
									foreach($modDetails as $b => $modDetail){
										$sql = "update t_voucher_pengeluaran set status_drp = NULL where voucher_pengeluaran_id = ".$modDetail['voucher_pengeluaran_id'];
										Yii::$app->db->createCommand($sql)->execute();
									}
								}
                                \app\models\TPengajuanDrpDetail::deleteAll("pengajuan_drp_id = ".$model->pengajuan_drp_id);
                            }
                            foreach($_POST['TPengajuanDrpDetail'] as $i => $detail){
                                $modDrpDetail = new \app\models\TPengajuanDrpDetail();
                                $modDrpDetail->pengajuan_drp_id = $model->pengajuan_drp_id;
                                $modDrpDetail->attributes = $detail;
								// $modDrpDetail->kategori = $_POST['TPengajuanDrpDetail'][$i]['kategori'];
								$sql_status_drp = "update t_voucher_pengeluaran set status_drp = 'drp' where voucher_pengeluaran_id = ".$detail['voucher_pengeluaran_id'];
								Yii::$app->db->createCommand($sql_status_drp)->execute();
                                if($modDrpDetail->validate()){
                                    if($modDrpDetail->save()){
                                        $success_2 &= true;
                                    }else{
                                        $success_2 = false;
                                    }
                                }else{
                                    $success_2 = false;
                                }
                            }
                        }
                        // START Create Approval
						$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
						$approver_1 = $_POST['TPengajuanDrp']['approver_1'];
						$approver_2 = $_POST['TPengajuanDrp']['approver_2'];
						$approver_3 = $_POST['TPengajuanDrp']['approver_3'];
						if(count($modelApproval)>0){ // edit mode
							if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
								$success_3 = $this->saveApproval($model, $approver_1, $approver_2, $approver_3);
							}
						}else{ // insert mode
							$success_3 = $this->saveApproval($model, $approver_1, $approver_2, $approver_3);
						}
						// END Create Approval
                        
                    }
                }
				
				// echo "<pre>1";
				// print_r($success_1);
				// echo "<pre>2";
				// print_r($success_2);
				// echo "<pre>3";
				// print_r($success_3);
				// exit;
				// print_r($_POST); exit;
				
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'pengajuan_drp_id'=>$model->pengajuan_drp_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
		}

		return $this->render('index', ['model'=>$model, 'modDrpDetail'=>$modDrpDetail]); //, 'modDetails'=>$modDetails
	}

	public function actionSetDetail(){
        if (\Yii::$app->request->isAjax) {
			$tanggal = \app\components\DeltaFormatter::formatDateTimesForDb(Yii::$app->request->post('tanggal'));
			$edit = Yii::$app->request->post('edit');
			$id = Yii::$app->request->post('id');
            $data = [];
            $data['html'] = '';

            $sql = "SELECT t_voucher_pengeluaran.voucher_pengeluaran_id, 			-- 0
							t_voucher_pengeluaran.tipe, 							-- 1
							t_voucher_pengeluaran.kode, 							-- 2
							tanggal_bayar, 											-- 3
							m_suplier.suplier_nm, 									-- 4
							total_nominal, 											-- 5
							t_voucher_pengeluaran.status_bayar, 					-- 6
							t_voucher_pengeluaran.cancel_transaksi_id, 				-- 7
							t_gkk.gkk_id,											-- 8
							t_gkk.kode AS gkk_kode, 								-- 9
							t_ppk.ppk_id, 											-- 10
							t_ppk.kode AS ppk_kode, 								-- 11
							t_ajuandinas_grader.ajuandinas_grader_id, 				-- 12
							t_ajuandinas_grader.kode AS pdg_kode,					-- 13
							t_ajuanmakan_grader.ajuanmakan_grader_id, 				-- 14
							t_ajuanmakan_grader.kode AS pmg_kode, 					-- 15
							t_log_bayar_dp.log_bayar_dp_id, 						-- 16
							t_log_bayar_dp.kode AS kode_dp, 						-- 17
							t_log_bayar_muat.log_bayar_muat_id, 					-- 18
							t_log_bayar_muat.kode AS kode_pelunasan, 				-- 19
							m_penerima_voucher.nama_penerima AS nama_penerima, 		-- 20
							m_penerima_voucher.nama_perusahaan AS nama_perusahaan, 	-- 21
							t_open_voucher.tipe AS tipe_ov, 				-- 22
							t_open_voucher.keterangan, 								-- 23
							m_suplierOV.suplier_nm AS suplier_ov, 					-- 24
							t_voucher_pengeluaran.mata_uang AS mata_uang,			-- 25
							t_asuransi.kepada,										-- 26
							m_suplier.suplier_nm_company,							-- 27
							m_suplierOV.suplier_nm_company as company_ov			-- 28
					FROM t_voucher_pengeluaran
					LEFT JOIN m_suplier ON m_suplier.suplier_id = t_voucher_pengeluaran.suplier_id 
					LEFT JOIN t_gkk ON t_gkk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_ppk ON t_ppk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_ajuandinas_grader ON t_ajuandinas_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_ajuanmakan_grader ON t_ajuanmakan_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_log_bayar_dp ON t_log_bayar_dp.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_log_bayar_muat ON t_log_bayar_muat.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_open_voucher ON t_open_voucher.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN m_penerima_voucher ON m_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id
					LEFT JOIN m_suplier AS m_suplierOV ON m_suplierOV.suplier_id = t_open_voucher.penerima_reff_id
					LEFT JOIN t_asuransi ON t_asuransi.kode = t_open_voucher.reff_no
					WHERE t_voucher_pengeluaran.status_drp IS NULL AND t_voucher_pengeluaran.cancel_transaksi_id IS NULL 
					AND t_voucher_pengeluaran.status_bayar = 'UNPAID' AND tanggal_bayar = '$tanggal' "; //AND $tipe 
			if($edit == 1){
				$sql .= " OR t_voucher_pengeluaran.voucher_pengeluaran_id IN 
							(select voucher_pengeluaran_id from t_pengajuan_drp_detail where pengajuan_drp_id = $id AND tanggal_bayar = '$tanggal')
						  ORDER BY total_nominal DESC";
			} else {
				$sql .= "ORDER BY total_nominal DESC";
			}
            $mods = \Yii::$app->db->createCommand($sql)->queryAll();
            if (count($mods) > 0) {
                foreach ($mods as $i => $detail) {
                    $data['detail'] = $detail;
					$modDrpDetail = new \app\models\TPengajuanDrpDetail(); 
					$edit = null;
                    $data['html'] .= $this->renderPartial('_item', ['modDrpDetail'=>$modDrpDetail, 'detail' => $detail, 'mods'=>$mods, 'i'=>$i, 'edit'=>$edit]);
                }
            } else {
				$data['html'] .= "<tr><td colspan='7' style='text-align: center;'><i>Tidak ada data yang ditemukan</i></td></tr>";
			}
        }             
        return $this->asJson($data);
    }

	public function actionAfterSave(){
        if (\Yii::$app->request->isAjax) {
            $kategori = Yii::$app->request->post('kategori');
			$kode = Yii::$app->request->post('kode');
			$edit = Yii::$app->request->post('edit');
            $data = [];
            $data['html'] = '';

			$model = TPengajuanDrp::findOne(['kode'=>$kode]);
			$sql = "SELECT t_voucher_pengeluaran.kode, t_open_voucher.tipe as tipe_ov, m_suplier.suplier_nm, t_gkk.gkk_id,	t_gkk.kode AS gkk_kode,
					t_ppk.ppk_id, t_ppk.kode AS ppk_kode,t_ajuandinas_grader.ajuandinas_grader_id, t_ajuandinas_grader.kode AS pdg_kode,
					t_ajuanmakan_grader.ajuanmakan_grader_id, t_ajuanmakan_grader.kode AS pmg_kode, t_log_bayar_dp.log_bayar_dp_id, t_log_bayar_dp.kode AS kode_dp,
					t_log_bayar_muat.log_bayar_muat_id, t_log_bayar_muat.kode AS kode_pelunasan,m_penerima_voucher.nama_penerima AS nama_penerima, 
					m_penerima_voucher.nama_perusahaan AS nama_perusahaan, m_suplierOV.suplier_nm AS suplier_ov,
					t_voucher_pengeluaran.total_nominal, t_voucher_pengeluaran.voucher_pengeluaran_id, t_asuransi.kepada, t_pengajuan_drp_detail.pengajuan_drp_detail_id,
					t_pengajuan_drp_detail.reff_ket, t_pengajuan_drp_detail.keterangan, t_pengajuan_drp_detail.kategori, t_pengajuan_drp_detail.status_pengajuan,
					m_suplier.suplier_nm_company, m_suplierOV.suplier_nm_company as company_ov
					FROM t_pengajuan_drp 
					LEFT JOIN t_pengajuan_drp_detail on t_pengajuan_drp_detail.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id
					LEFT JOIN t_voucher_pengeluaran on t_voucher_pengeluaran.voucher_pengeluaran_id = t_pengajuan_drp_detail.voucher_pengeluaran_id
					LEFT JOIN t_open_voucher on t_open_voucher.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN m_suplier ON m_suplier.suplier_id = t_voucher_pengeluaran.suplier_id 
					LEFT JOIN t_gkk ON t_gkk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_ppk ON t_ppk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_ajuandinas_grader ON t_ajuandinas_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_ajuanmakan_grader ON t_ajuanmakan_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_log_bayar_dp ON t_log_bayar_dp.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN t_log_bayar_muat ON t_log_bayar_muat.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
					LEFT JOIN m_penerima_voucher ON m_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id
					LEFT JOIN m_suplier AS m_suplierOV ON m_suplierOV.suplier_id = t_open_voucher.penerima_reff_id
					LEFT JOIN t_asuransi ON t_asuransi.kode = t_open_voucher.reff_no 
					WHERE t_pengajuan_drp.pengajuan_drp_id = {$model->pengajuan_drp_id}
					ORDER BY t_voucher_pengeluaran.total_nominal DESC";
            $mods = \Yii::$app->db->createCommand($sql)->queryAll();
            if (count($mods) > 0) {
                foreach ($mods as $i => $detail) {
                    $data['detail'] = $detail;
					if($edit != null){
						$modDrpDetail = new \app\models\TPengajuanDrpDetail();
						$data['html'] .= $this->renderPartial('_item', ['detail' => $detail, 'mods'=>$mods, 'model'=>$model, 'i'=>$i, 'modDrpDetail'=>$modDrpDetail, 'edit'=>$edit]);
					} else {
						$modDrpDetail = \app\models\TPengajuanDrpDetail::findOne($detail['pengajuan_drp_detail_id']);
						$data['html'] .= $this->renderPartial('_itemAfterSave', ['detail' => $detail, 'mods'=>$mods, 'model'=>$model, 'i'=>$i, 'modDrpDetail'=>$modDrpDetail]);
					}
                }
            }
			$approver_1 = TApproval::find()->select(['assigned_to'])->where(['reff_no'=>$kode, 'level'=>1])->one();
			$data['approver_1'] = $approver_1?$approver_1->assigned_to:'';
			$approver_2 = TApproval::find()->select(['assigned_to'])->where(['reff_no'=>$kode, 'level'=>2])->one();
			$data['approver_2'] = $approver_2?$approver_2->assigned_to:'';
			$approver_3 = TApproval::find()->select(['assigned_to'])->where(['reff_no'=>$kode, 'level'=>3])->one();
			$data['approver_3'] = $approver_3?$approver_3->assigned_to:'';
			$data['tanggal'] = $model->tanggal;
        }             
        return $this->asJson($data);
    }

	public function saveApproval($model, $approver_1, $approver_2, $approver_3){
		$success = true;

		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $approver_1;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = date("Y-m-d");
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();

		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $approver_2;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = date("Y-m-d");
		$modelApproval->level = 2;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();

        $modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $approver_3;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = date("Y-m-d");
		$modelApproval->level = 3;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();

        return $success;
    }

	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPengajuanDrp::tableName();
				$param['pk']= $param['table'].".".\app\models\TPengajuanDrp::primaryKey()[0];
				$param['column'] = [$param['table'].'.pengajuan_drp_id', 			//0
									$param['table'].'.kode', 						//1
									$param['table'].'.tanggal', 					//2
									't_pengajuan_drp.keterangan',					//3
									'(SELECT SUM(vp2.total_nominal) FROM t_voucher_pengeluaran vp2
										JOIN t_pengajuan_drp_detail pdd2 ON pdd2.voucher_pengeluaran_id = vp2.voucher_pengeluaran_id
										WHERE pdd2.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id
									) AS total_jml',								//4
									$param['table'].'.cancel_transaksi_id',			//5
									$param['table'].'.status_approve',				//6
									"CASE 
										WHEN status_approve = 'APPROVED' 
										THEN
											CASE
												WHEN EXISTS (SELECT 1 
															FROM t_pengajuan_drp_detail pdd 
															WHERE pdd.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id 
															AND pdd.status_pengajuan = 'Ditunda') 
												THEN 'APPROVED PARTIAL' 
												ELSE 'APPROVED ALL' 
											END
										ELSE status_approve
									END AS approved",								//7
									"(SELECT SUM(vp2.total_nominal)
										FROM t_voucher_pengeluaran vp2
										JOIN t_pengajuan_drp_detail pdd2 ON pdd2.voucher_pengeluaran_id = vp2.voucher_pengeluaran_id
										WHERE pdd2.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id
										AND pdd2.status_pengajuan = 'Disetujui') AS total_jml_disetujui" //8
                                    ];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

	public function actionCancelDrp($id){
		if(\Yii::$app->request->isAjax){
			$modDrp = \app\models\TPengajuanDrp::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_pengajuan_drp
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $modDrp->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
							$modDetails = \app\models\TPengajuanDrpDetail::find()->where(['pengajuan_drp_id'=>$modDrp->pengajuan_drp_id])->all();
							foreach($modDetails as $a => $modDetail){
								$sql_status_drp = "update t_voucher_pengeluaran set status_drp = NULL where voucher_pengeluaran_id = ".$modDetail->voucher_pengeluaran_id;
								$sql_status_approve = "update t_pengajuan_drp set status_approve = 'ABORTED' where pengajuan_drp_id = ".$modDrp->pengajuan_drp_id;
								Yii::$app->db->createCommand($sql_status_drp)->execute();
								Yii::$app->db->createCommand($sql_status_approve)->execute();
								if($modDrp->reason_approval != null ){
									$sql_approve = "update t_pengajuan_drp set reason_approval = NULL where pengajuan_drp_id = ".$id;
									Yii::$app->db->createCommand($sql_approve)->execute();
								}
								if($modDrp->reason_rejected != null){
									$sql_reject = "update t_pengajuan_drp set reason_rejected = NULL where pengajuan_drp_id = ".$id;
									Yii::$app->db->createCommand($sql_reject)->execute();
								}
							}
							//delete approval yg ada
							$modApproval = \app\models\TApproval::find()->where(['reff_no'=>$modDrp->kode])->all();
							if(count($modApproval)>0){
								\app\models\TApproval::deleteAll(['reff_no'=>$modDrp->kode]);
							}
                            if($modDrp->updateAttributes(['cancel_transaksi_id'=>$modCancel->cancel_transaksi_id])){
								$success_2 = TRUE;
							}else{
								$success_2 = FALSE;
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel); 
                    }
					
					// echo "<pre>1";
					// print_r($success_1);
					// echo "<pre>2";
					// print_r($success_2);
					// exit;
					// print_r($modDrp); exit;
					
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Voucher Berhasil di Batalkan');
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
			
			return $this->renderAjax('cancelDrp',['modDrp'=>$modDrp,'modCancel'=>$modCancel]);
		}
	}

	public function actionPrint(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TPengajuanDrp::findOne($_GET['id']);
		$modDetail = \app\models\TPengajuanDrpDetail::find()->where(['pengajuan_drp_id'=>$_GET['id']])->orderBy(['pengajuan_drp_id'=>SORT_ASC])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'Daftar Rencana Pembayaran');
		if($caraprint == 'PRINT'){
			return $this->render('print',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/finance/drp/print',['model'=>$model,'paramprint'=>$paramprint, 'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/finance/drp/print',['model'=>$model,'paramprint'=>$paramprint, 'modDetail'=>$modDetail]);
		}
	}
	
}
