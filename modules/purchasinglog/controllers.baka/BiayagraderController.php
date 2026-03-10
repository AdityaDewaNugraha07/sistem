<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class BiayagraderController extends DeltaBaseController
{
	
	public function actionBiayaBiaya($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TDkg::findOne($id);
			if(\Yii::$app->request->post('getItemsAjuan')){
				$data = []; $data['html'] = '';
				$modAjuans = \app\models\TAjuandinasGrader::find()->where(['dkg_id'=>$id])->orderBy(['created_at'=>SORT_DESC])->all();
				if(count($modAjuans)>0){
					foreach($modAjuans as $i => $ajuan){
						$data['html'] .= $this->renderPartial('_itemAjuan',['model'=>$ajuan,'i'=>$i]);
					}
				}else{
					$data['html'] = "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Pengajuan</i></td></tr>";
				}
				return $this->asJson($data);
			}
			if(\Yii::$app->request->post('getItemsRealisasi')){
				$data = []; $data['html'] = '';
				$modRealisasi = \app\models\TRealisasidinasGrader::find()->where(['dkg_id'=>$id])->orderBy(['created_at'=>SORT_DESC])->all();
				if(count($modRealisasi)>0){
					foreach($modRealisasi as $i => $realisasi){
						$data['html'] .= $this->renderPartial('_itemRealisasi',['model'=>$realisasi,'i'=>$i]);
					}
				}else{
					$data['html'] = "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Realisasi</i></td></tr>";
				}
				return $this->asJson($data);
			}
			if(\Yii::$app->request->post('getItemsAjuanMakan')){
				$data = []; $data['html'] = '';
				$modAjuans = \app\models\TAjuanmakanGrader::find()->where(['dkg_id'=>$id])->orderBy(['created_at'=>SORT_DESC])->all();
				if(count($modAjuans)>0){
					foreach($modAjuans as $i => $ajuan){
						$data['html'] .= $this->renderPartial('_itemAjuanMakan',['model'=>$ajuan,'i'=>$i]);
					}
				}else{
					$data['html'] = "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Pengajuan</i></td></tr>";
				}
				return $this->asJson($data);
			}
			if(\Yii::$app->request->post('getItemsRealisasiMakan')){
				$data = []; $data['html'] = '';
				$modRealisasi = \app\models\TRealisasimakanGrader::find()->where(['dkg_id'=>$id])->orderBy(['created_at'=>SORT_DESC])->all();
				if(count($modRealisasi)>0){
					foreach($modRealisasi as $i => $realisasi){
						$data['html'] .= $this->renderPartial('_itemRealisasiMakan',['model'=>$realisasi,'i'=>$i]);
					}
				}else{
					$data['html'] = "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Realisasi</i></td></tr>";
				}
				return $this->asJson($data);
			}
			return $this->renderAjax('biayaBiaya',['model'=>$model,'actionname'=>'BiayaBiaya']);
		}
	}
	
	public function actionCreateAjuanDinas($id){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TAjuandinasGrader();
			$modDkg = \app\models\TDkg::findOne($id);
			$sisa_saldo = \app\models\HKasDinasgrader::getSaldoKas($modDkg->graderlog_id);
			$model->kode = "Auto Generate";
			$model->tanggal = date('d/m/Y');
			$model->dkg_id = $modDkg->dkg_id;
			$model->graderlog_id = $modDkg->graderlog_id;
			$model->graderlog_nm = $modDkg->graderlog->graderlog_nm;
            if( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_SENGON)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_SENGON) ){
                $model->kanit_grader = \app\components\Params::DEFAULT_PEGAWAI_ID_SONI_DWI;
                $model->approved_by = \app\components\Params::DEFAULT_PEGAWAI_ID_PAK_WID;
            }else{
                $model->kanit_grader = \app\components\Params::DEFAULT_PEGAWAI_ID_KANIT_GRADER;
                //$model->approved_by = \app\components\Params::DEFAULT_PEGAWAI_ID_TATANG;
                $model->approved_by = \app\components\Params::DEFAULT_PEGAWAI_ID_DIREKTUR_MANUFAKTUR;
            }
			$model->grader_norek = $modDkg->graderlog->graderlog_norek_bank;
			$model->grader_bank = $modDkg->graderlog->graderlog_bank;
			$model->wilayah_dinas_id = $modDkg->wilayah_dinas_id;
			$model->wilayah_dinas_nama = $modDkg->wilayahDinas->wilayah_dinas_nama;
			$model->wilayah_dinas_plafon = \app\components\DeltaFormatter::formatNumberForUserFloat($modDkg->wilayahDinas->wilayah_dinas_plafon);
			$model->saldo_sebelumnya = \app\components\DeltaFormatter::formatNumberForUserFloat($sisa_saldo);
			$model->total_ajuan = \app\components\DeltaFormatter::formatNumberForUserFloat($modDkg->wilayahDinas->wilayah_dinas_plafon - $sisa_saldo);
			if( isset($_POST['TAjuandinasGrader']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = FALSE; // t_ajuandinas_grader
					$success_2 = FALSE; // t_approval
					$model->load(\Yii::$app->request->post());
					$model->kode = \app\components\DeltaGenerator::kodePDG();
					if($model->validate()){
						if($model->save()){
							$success_1 = TRUE;
							// START Create Approval
							$modelApproval = new \app\models\TApproval();
							$modelApproval->assigned_to = $model->approved_by;
							$modelApproval->reff_no = $model->kode;
							$modelApproval->tanggal_berkas = $model->tanggal;
							$modelApproval->level = 1;
							$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
							$success_2 = $modelApproval->createApproval();
							// END Create Approval
						}
					}
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
						$data['message'] = Yii::t('app', "Data Berhasil di Simpan");
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
			return $this->renderAjax('createAjuanDinas',['model'=>$model,'actionname'=>'AjukanDinas']);
		}
	}

	public function actionDetailAjuanDinas($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TAjuandinasGrader::findOne($id);
			return $this->renderAjax('detailAjuanDinas',['model'=>$model]);
		}
	}

	public function actionPrintAjuanDinas(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TAjuandinasGrader::findOne($_GET['id']);
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', '');
		if($caraprint == 'PRINT'){
			return $this->render('printAjuanDinas',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}

	public function actionDeleteAjuanDinas($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TAjuandinasGrader::findOne($id);
			$modApproval = \app\models\TApproval::findOne(['reff_no'=>$model->kode]);
			$dkg_id = $model->dkg_id;
			$pesan = Yii::t('app', 'Yakin akan menghapus pengajuan <b>'.$model->kode.'</b>??');
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = true;
					if(!empty($modApproval)){
						$success_2 = $modApproval->delete();
					}
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = 'getItemsAjuan('.$dkg_id.')';
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['pesan'=>$pesan,'id'=>$id,'actionname'=>'deleteAjuanDinas']);
		}
	}
	
	public function actionCreateRealisasiDinas($id){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TRealisasidinasGrader();
			$modDkg = \app\models\TDkg::findOne($id);
			$saldo_awal = \app\models\HKasDinasgrader::getSaldoKas($modDkg->graderlog_id);
			$saldo_akhir = 0;
			$model->kode = "Auto Generate";
			$model->tanggal = date('d/m/Y');
			$model->dkg_id = $modDkg->dkg_id;
			$model->graderlog_id = $modDkg->graderlog_id;
			$model->graderlog_nm = $modDkg->graderlog->graderlog_nm;
			$model->saldo_awal = \app\components\DeltaFormatter::formatNumberForUserFloat($saldo_awal);
			$model->total_realisasi = 0;
			$model->saldo_akhir = \app\components\DeltaFormatter::formatNumberForUserFloat($saldo_akhir);
			if( isset($_POST['TRealisasidinasGrader']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = FALSE; // t_realisasidinas_grader
					$success_2 = FALSE; // h_kas_dinasgrader
                    $success_3 = FALSE; // t_approval
                    $model->approval_status = 'Not Confirmed';
					$model->load(\Yii::$app->request->post());
					$model->kode = \app\components\DeltaGenerator::kodeRDG();
                    
                    // approval by Staff Finance : Iswari
                    $model->approved1_by = 57;

					if($model->validate()){
						if($model->save()){
							$success_1 = TRUE;
                            // SKIP DULU, PINDAH KE APPROVAL REALISASI KAS DINAS GRADER
							// Start Proses Update Saldo
							//$model->reff_no = $model->kode;
							//$model->nominal_in = 0;
							//$model->nominal_out = $model->total_realisasi;
							//$success_2 = \app\models\HKasDinasgrader::updateSaldoKas($model);
							// End Proses Update Saldo
                            $success_2 = true;

							// START Create Approval
                            // approval by Staff Finance : Iswari
                            $pegawai_id = 57;
                            $user_id = 103;
							$modelApproval = new \app\models\TApproval();
							$modelApproval->assigned_to = $pegawai_id;
							$modelApproval->reff_no = $model->kode;
							$modelApproval->tanggal_berkas = $model->tanggal;
							$modelApproval->level = 1;
							$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
							$success_3 = $modelApproval->createApproval();
							// END Create Approval
						}
					}

					if ($success_1 && $success_2 && $success_3) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
						$data['message'] = Yii::t('app', "Data Berhasil di Simpan");
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
			return $this->renderAjax('createRealisasiDinas',['model'=>$model,'actionname'=>'AjukanDinas']);
		}
	}

	public function actionDetailRealisasiDinas($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TRealisasidinasGrader::findOne($id);
			return $this->renderAjax('detailRealisasiDinas',['model'=>$model]);
		}
	}

	public function actionDeleteRealisasiDinas($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TRealisasidinasGrader::findOne($id);
			$modKas = \app\models\HKasDinasgrader::findOne(['reff_no'=>$model->kode]);
            $modApp = \app\models\TApproval::findOne(['reff_no'=>$model->kode]);
			$dkg_id = $model->dkg_id;
			$pesan = Yii::t('app', 'Yakin akan menghapus realisasi <b>'.$model->kode.'</b>??');
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_0 = false;
                    $success_1 = false;
                    $success_2 = false;
                    $success_3 = false;
                    if ($model->approval_status == "Not Confirmed") {
                        $success_0 = true;
                    } else {
                        $success_0 = false;
                        $data['message'] = Yii::t('app', 'Status approval sudah approve/reject.<br>Refresh ulang dulu halaman ini.');
                    }
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Tabel Realiasi Gagal dihapus');
					}
                    if ($modKas['reff_no'] == $model->kode) {
                        if($modKas->delete()){
                            $success_2 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data Tabel History Gagal dihapus');
                        }
                    } else {
                        $success_2 = true;
                    }
					if($modApp->delete()){
						$success_3 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Tabel Approval Gagal dihapus');
					}

					if ($success_0 && $success_1 && $success_2 && $success_3) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = 'getItemsRealisasi('.$dkg_id.'); getSaldo();';
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
					} else {
						$transaction->rollback();
						$data['status'] = false;
						(isset($data['message']) ? $data['message'] = $data['message'] : Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
						(isset($data['message_validate']) ? $data['message'] = null : '');
					}
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['pesan'=>$pesan,'id'=>$id,'actionname'=>'deleteRealisasiDinas']);
		}
	}
	
	public function actionCreateAjuanMakan($id){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TAjuanmakanGrader();
			$modDkg = \app\models\TDkg::findOne($id);
			$sisa_saldo = \app\models\HKasMakangrader::getSaldoKas($modDkg->graderlog_id);
			$model->kode = "Auto Generate";
			$model->tanggal = date('d/m/Y');
			$model->dkg_id = $modDkg->dkg_id;
			$model->graderlog_id = $modDkg->graderlog_id;
			$model->graderlog_nm = $modDkg->graderlog->graderlog_nm;
            if( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_SENGON)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_SENGON) ){
                $model->kanit_grader = \app\components\Params::DEFAULT_PEGAWAI_ID_SONI_DWI;
                $model->approved_by = \app\components\Params::DEFAULT_PEGAWAI_ID_PAK_WID;
            }else{
                $model->kanit_grader = \app\components\Params::DEFAULT_PEGAWAI_ID_KANIT_GRADER;
                //$model->approved_by = \app\components\Params::DEFAULT_PEGAWAI_ID_TATANG;
                $model->approved_by = \app\components\Params::DEFAULT_PEGAWAI_ID_DIREKTUR_MANUFAKTUR;
            }
			$model->grader_norek = $modDkg->graderlog->graderlog_norek_bank;
			$model->grader_bank = $modDkg->graderlog->graderlog_bank;
			$model->wilayah_dinas_id = $modDkg->wilayah_dinas_id;
			$model->wilayah_dinas_nama = $modDkg->wilayahDinas->wilayah_dinas_nama;
			$model->wilayah_dinas_makan = \app\components\DeltaFormatter::formatNumberForUserFloat($modDkg->wilayahDinas->wilayah_dinas_makan);
			$model->wilayah_dinas_pulsa = \app\components\DeltaFormatter::formatNumberForUserFloat($modDkg->wilayahDinas->wilayah_dinas_pulsa);
			$model->qty_hari = 0;
			$model->saldo_sebelumnya = \app\components\DeltaFormatter::formatNumberForUserFloat($sisa_saldo);
			$model->total_ajuan = 0;
			if( isset($_POST['TAjuanmakanGrader']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = FALSE; // t_ajuanmakan_grader
					$success_2 = FALSE; // t_approval
					$model->load(\Yii::$app->request->post());
					$model->kode = \app\components\DeltaGenerator::kodePMG();
					if($model->validate()){
						if($model->save()){
							$success_1 = TRUE;
							// START Create Approval
							$modelApproval = new \app\models\TApproval();
							$modelApproval->assigned_to = $model->approved_by;
							$modelApproval->reff_no = $model->kode;
							$modelApproval->tanggal_berkas = $model->tanggal;
							$modelApproval->level = 1;
							$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
							$success_2 = $modelApproval->createApproval();
							// END Create Approval
						}
					}
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
						$data['message'] = Yii::t('app', "Data Berhasil di Simpan");
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
			return $this->renderAjax('createAjuanMakan',['model'=>$model]);
		}
	}

	public function actionDetailAjuanMakan($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TAjuanmakanGrader::findOne($id);
			return $this->renderAjax('detailAjuanMakan',['model'=>$model]);
		}
	}

	public function actionPrintAjuanMakan(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TAjuanmakanGrader::findOne($_GET['id']);
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', '');
		if($caraprint == 'PRINT'){
			return $this->render('printAjuanMakan',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}

	public function actionDeleteAjuanMakan($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TAjuanmakanGrader::findOne($id);
			$modApproval = \app\models\TApproval::findOne(['reff_no'=>$model->kode]);
			$dkg_id = $model->dkg_id;
			$pesan = Yii::t('app', 'Yakin akan menghapus pengajuan <b>'.$model->kode.'</b>??');
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = true;
					if(!empty($modApproval)){
						$success_2 = $modApproval->delete();
					}
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = 'getItemsAjuanMakan('.$dkg_id.')';
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['pesan'=>$pesan,'id'=>$id,'actionname'=>'deleteAjuanMakan']);
		}
	}

	public function actionCreateRealisasiMakan($id){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TRealisasimakanGrader();
			$modDkg = \app\models\TDkg::findOne($id);
			$saldo_awal = \app\models\HKasMakangrader::getSaldoKas($modDkg->graderlog_id);
			$saldo_akhir = 0;
			$model->kode = "Auto Generate";
			$model->tanggal = date('d/m/Y');
			$model->dkg_id = $modDkg->dkg_id;
			$model->graderlog_id = $modDkg->graderlog_id;
			$model->graderlog_nm = $modDkg->graderlog->graderlog_nm;
			$model->saldo_awal = \app\components\DeltaFormatter::formatNumberForUserFloat($saldo_awal);
			$model->total_realisasi = 0;
			$model->qty_hari = 0;
			$model->saldo_akhir = \app\components\DeltaFormatter::formatNumberForUserFloat($saldo_akhir);
			$model->tempat_tujuan = !empty($modDkg->tujuan)?$modDkg->tujuan:"-";
			$model->wilayah_dinas_id = $modDkg->wilayah_dinas_id;
			$model->wilayah_dinas_nama = $modDkg->wilayahDinas->wilayah_dinas_nama;
			$model->wilayah_dinas_makan = \app\components\DeltaFormatter::formatNumberForUserFloat($modDkg->wilayahDinas->wilayah_dinas_makan);
			if($model->graderlog_id != \app\components\Params::DEFAULT_GRADERLOG_ID_GRADER_JOKO_MULYONO){
				$model->wilayah_dinas_pulsa = \app\components\DeltaFormatter::formatNumberForUserFloat($modDkg->wilayahDinas->wilayah_dinas_pulsa);
			}else{
				$model->wilayah_dinas_pulsa = 0;
			}
			if( isset($_POST['TRealisasimakanGrader']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = FALSE; // t_realisasidinas_grader
					$success_2 = FALSE; // h_kas_dinasgrader
					$success_3 = FALSE; // t_approval
                    $model->approval_status = 'Not Confirmed';
					$model->load(\Yii::$app->request->post());
					$model->kode = \app\components\DeltaGenerator::kodeRMG();
                    
                    // approval by Staff Finance : Iswari
                    $model->approved1_by = 57;

					if($model->validate()){
						if($model->save()){
							$success_1 = TRUE;
							// SKIP DULU, PINDAH KE APPROVAL REALISASI UANG MAKAN DINAS GRADER
                            // Start Proses Update Saldo
							//$model->reff_no = $model->kode;
							//$model->nominal_in = 0;
							//$model->nominal_out = $model->total_realisasi;
							//$success_2 = \app\models\HKasMakangrader::updateSaldoKas($model);
							// End Proses Update Saldo
                            $success_2 = true;

                            // START Create Approval
                            // approval by Staff Finance : Iswari
                            $pegawai_id = 57;
                            $user_id = 103;
							$modelApproval = new \app\models\TApproval();
							$modelApproval->assigned_to = $pegawai_id;
							$modelApproval->reff_no = $model->kode;
							$modelApproval->tanggal_berkas = $model->tanggal;
							$modelApproval->level = 1;
							$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
							$success_3 = $modelApproval->createApproval();
							// END Create Approval
						}
					}

					if ($success_1 && $success_2 && $success_3) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
						$data['message'] = Yii::t('app', "Data Berhasil di Simpan");
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
			return $this->renderAjax('createRealisasiMakan',['model'=>$model,'actionname'=>'CreateRealisasiMakan']);
		}
	}

	public function actionDetailRealisasiMakan($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TRealisasimakanGrader::findOne($id);
			return $this->renderAjax('detailRealisasiMakan',['model'=>$model]);
		}
	}

	public function actionDeleteRealisasiMakan($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TRealisasimakanGrader::findOne($id);
			$modKas = \app\models\HKasMakangrader::findOne(['reff_no'=>$model->kode]);
            $modApp = \app\models\TApproval::findOne(['reff_no'=>$model->kode]);
			$dkg_id = $model->dkg_id;
			$pesan = Yii::t('app', 'Yakin akan menghapus realisasi <b>'.$model->kode.'</b>??');
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_0 = false;
                    $success_1 = false;
                    $success_2 = false;
                    $success_3 = false;
                    if ($model->approval_status == "Not Confirmed") {
                        $success_0 = true;
                    } else {
                        $success_0 = false;
                        $data['message'] = Yii::t('app', 'Status approval sudah approve/reject.<br>Refresh ulang dulu halaman ini.');
                    }
                    if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
                    if ($modKas['reff_no'] == $model->kode) {
                        if($modKas->delete()){
                            $success_2 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data Tabel History Gagal dihapus');
                        }
                    } else {
                        $success_2 = true;
                    }
                    if($modApp->delete()){
						$success_3 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Tabel Approval Gagal dihapus');
					} 
					if ($success_0 && $success_1 && $success_2 && $success_3) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = 'getItemsRealisasiMakan('.$dkg_id.'); getSaldo();';
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
					} else {
						$transaction->rollback();
						$data['status'] = false;
						(isset($data['message']) ? $data['message'] = $data['message'] : Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
						(isset($data['message_validate']) ? $data['message'] = null : '');
					}
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['pesan'=>$pesan,'id'=>$id,'actionname'=>'deleteRealisasiMakan']);
		}
	}
	
	function actionGetSaldo(){
		if(\Yii::$app->request->isAjax){
			$graderlog_id = Yii::$app->request->post('graderlog_id');
			$data['dinas'] = \app\components\DeltaFormatter::formatUang( \app\models\HKasDinasgrader::getSaldoKas($graderlog_id) );
			$data['makan'] = \app\components\DeltaFormatter::formatUang(\app\models\HKasMakangrader::getSaldoKas($graderlog_id) );
			return $this->asJson($data);
		}
	}
	
}
