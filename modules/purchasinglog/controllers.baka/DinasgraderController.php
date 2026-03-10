<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class DinasgraderController extends DeltaBaseController
{
	public $defaultAction = 'index';
	
	public function actionIndex(){
		$model = new \app\models\TDkg();
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->post('getItems')){
				$data = []; $data['html'] = ''; $saldodinas=0;$saldomakan=0;
                $wheretype = "AND m_graderlog.type  = ''";
                if(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER){
                    $wheretype = "";
                }elseif( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_SENGON)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_SENGON) ){
                    $wheretype = "AND m_graderlog.type  = 'GLS'";
                }elseif( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_ALAM)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_ALAM) ){
                    $wheretype = "AND m_graderlog.type  = 'GLA'";
                }
				$model = \app\models\TDkg::find()->join("JOIN", "m_graderlog", "m_graderlog.graderlog_id = t_dkg.graderlog_id")
                            ->where("status = '".\app\models\TDkg::AKTIF_DINAS."' {$wheretype}")->orderBy(['created_at'=>SORT_DESC])->all();
                            
				if(count($model)>0){
					foreach($model as $i => $mod){
						$mod->graderlog_nm = $mod->graderlog->graderlog_nm;
						$none = "";
						$modAjuanDinas = \app\models\TAjuandinasGrader::find()->where("dkg_id = '{$mod->dkg_id}'")->all();
						if(count($modAjuanDinas)>0){
							foreach($modAjuanDinas as $i => $ajuan){
								$modApproval = \app\models\TApproval::findOne(['reff_no'=>$ajuan->kode]);
								if($modApproval->status == \app\models\TApproval::STATUS_APPROVED){
									$none = "none;";
								}
							}
						}
						$data['html'] .= $this->renderPartial('_item',['model'=>$mod,'i'=>$i,'saldodinas'=>$saldodinas,'saldomakan'=>$saldomakan,'none'=>$none]);
					}
				}else{
					$data['html'] = "<tr><td colspan='9'><center><i>Belum ada data Dinas Grader</i></center></td></tr>";
				}
				return $this->asJson($data);
			}
        }
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionCreateDkg(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TDkg();
            $modPmr = new \app\models\TDkg();
            $modMap = new \app\models\MapDkgPmrPengajuanPembelianlog();
            $modPengajuanPembelianlog = new \app\models\TPengajuanPembelianlog();
			$model->kode = "Auto Generate";
			$model->tanggal = date('d/m/Y');
			$model->tipe = "ORIENTASI";
			$modDkgs = \app\models\TDkg::find()->where("status = '".\app\models\TDkg::AKTIF_DINAS."'")->all();
			$grader_aktif = [];
			if(count($modDkgs)>0){
				foreach($modDkgs as $i => $dkg){
					$grader_aktif[] = $dkg->graderlog_id;
				}
			}
			if( isset($_POST['TDkg']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false; // t_dkg
                    $success_2 = false; // map_dkg_pmr_pengajuan_pembelianlog
                    $success_3 = false; // t_approval

                    $model->load(\Yii::$app->request->post());
                    $model->jenis_log = $_POST['TDkg']['jenis_log'];
					$model->kode = \app\components\DeltaGenerator::kodeDKG();
					$model->status = \app\models\TDkg::AKTIF_DINAS;

                    // jika log alam maka approvernya :
                    // level 1 kadiv purchasing log (Heryanto Suwardi - 22)
                    // level 2 kadiv hrd ga (Andrian Argasasmita - 124)
                    if ($model->jenis_log == "LA") {
                        $model->approved1_by = 22;
                        $model->approved2_by = 124;
                    }
                    // jika log sengon atau jabon maka approvernya :
                    // level 1 kadep purchasing (Widyo Sinudarsono - 56)
                    // level 2 kadiv hrd ga (Andrian Argasasmita - 124)
                    else if ($model->jenis_log == "LS" || $model->jenis_log == "LJ") {
                        $model->approved1_by = 56;
                        $model->approved2_by = 124;
                    } else {
                        $success_1 = false;
                    }
                    
                    if($model->validate()){
						if($model->save()){
                            $success_1 = true;
						}
					}

                    if ($success_1) {
                        $modMap->dkg_id = $model->dkg_id;
                        if ($model->jenis_log == "LA") {
                            if (isset($_POST['pmr_id'])) {
                                for ($i=0; $i < count($_POST['pmr_id']); $i++) {
                                    $pmr_id = $_POST['pmr_id'][$i];
                                    if ($model->dkg_id != "" && $model->dkg_id != NULL && $model->dkg_id > 0 
                                            && $pmr_id != "" && $pmr_id != NULL && $pmr_id > 0) {
                                        $sql_insert = "insert into map_dkg_pmr_pengajuan_pembelianlog ".
                                                    "   (dkg_id, pmr_id) ".
                                                    "   values ".
                                                    "   ($model->dkg_id, $pmr_id) ". 
                                                    "   ";
                                        Yii::$app->db->createCommand($sql_insert)->execute();
                                    }
                                }
                                $success_2 = true;
                            }
                            
                            if (isset($_POST['pengajuan_pembelianlog_id'])) {
                                $modMap->pengajuan_pembelianlog_id = $_POST['pengajuan_pembelianlog_id'];
                                if($modMap->validate()){
                                    if ($modMap->save()) {
                                        $success_2 = true;
                                    }
                                }
                            }
                        } else if ($model->jenis_log == "LS" || $model->jenis_log == "LJ") {
                            $success_2 = true;
                        } else {
                            $success_2 = false;
                        }
                        // START Create Approval
						$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
						if(count($modelApproval)>0){ // edit mode
							if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
								$success_3 = $this->saveApproval($model);
							}
							$success_3 = true;
						}else{ // insert mode
							$success_3 = $this->saveApproval($model);
						}
						// END Create Approval
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

			return $this->renderAjax('createDinas',['model'=>$model,'modPmr'=>$modPmr,'modPengajuanPembelianlog'=>$modPengajuanPembelianlog,'actionname'=>'CreateDkg','grader_aktif'=>$grader_aktif]);
		}
	}

    public function saveApproval($model){
        // jika log alam maka approvernya :
        // level 1 kadiv purchasing log (Heryanto Suwardi - 22)
        // level 2 kadiv hrd ga (Andrian Argasasmita - 124)
        if ($model->jenis_log == "LA") {
            $success = true;

            $modelApproval = new \app\models\TApproval();
            $modelApproval->assigned_to = 22;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 1;
            $modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
            $success &= $modelApproval->createApproval();
            
            $modelApproval = new \app\models\TApproval();
            $modelApproval->assigned_to = 124;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 2;
            $modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
            $success &= $modelApproval->createApproval();
            
            return $success;
        } 
        // jika log sengon atau jabon maka approvernya :
        // level 1 kadep purchasing (Widyo Sinudarsono - 56)
        // level 2 kadiv hrd ga (Andrian Argasasmita - 124)
        else if ($model->jenis_log == "LS" || $model->jenis_log == "LJ") {
            $success = true;

            $modelApproval = new \app\models\TApproval();
            $modelApproval->assigned_to = 56;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 1;
            $modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
            $success &= $modelApproval->createApproval();
            
            $modelApproval = new \app\models\TApproval();
            $modelApproval->assigned_to = 124;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 2;
            $modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
            $success &= $modelApproval->createApproval();
            
            return $success;
        } else {
		    return true;
        }
	}

    public function actionOpenPMR(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPmr::tableName();
				$param['pk']= $param['table'].".". \app\models\TPmr::primaryKey()[0];
				$param['column'] = [$param['table'].'.pmr_id',
									$param['table'].'.kode',
									$param['table'].'.tanggal',
									$param['table'].'.jenis_log',
									$param['table'].'.tujuan',
									"CONCAT( TO_CHAR(tanggal_dibutuhkan_awal :: DATE, 'dd/mm/yyyy'),' sd ',TO_CHAR(tanggal_dibutuhkan_akhir :: DATE, 'dd/mm/yyyy')) as dibutuhkan",
									'(SELECT SUM(qty_m3) FROM t_pmr_detail WHERE t_pmr_detail.pmr_id = t_pmr.pmr_id) AS total_m3',
									'm_pegawai.pegawai_nama AS dibuat_oleh',
									'pegawai1.pegawai_nama AS approver_1',
									'pegawai2.pegawai_nama AS approver_2',
									'pegawai3.pegawai_nama AS approver_3',
                                    '(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_1) AS approver_1_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_2) AS approver_2_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_3) AS approver_3_status',
                                    '(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT t_pengajuan_pembelianlog.kode FROM t_pengajuan_pembelianlog JOIN map_permintaan_keputusan_logalam ON map_permintaan_keputusan_logalam.pengajuan_pembelianlog_id = t_pengajuan_pembelianlog.pengajuan_pembelianlog_id WHERE map_permintaan_keputusan_logalam.pmr_id = t_pmr.pmr_id GROUP BY 1) t) AS kode_pengajuan_keputusan',
									$param['table'].'.status',
                                    'pegawai4.pegawai_nama AS approver_4',
                                    '(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_4) AS approver_4_status',
                                    ];
				$param['join']= ['
								JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.dibuat_oleh 
								JOIN m_pegawai AS pegawai1 ON pegawai1.pegawai_id = '.$param['table'].'.approver_1 
								JOIN m_pegawai AS pegawai2 ON pegawai2.pegawai_id = '.$param['table'].'.approver_2 
								JOIN m_pegawai AS pegawai3 ON pegawai3.pegawai_id = '.$param['table'].'.approver_3 
                                JOIN m_pegawai AS pegawai4 ON pegawai4.pegawai_id = '.$param['table'].'.approver_4
								'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL AND t_pmr.jenis_log = 'LA'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('_openPMR',[]);
        }
    }

    public function actionOpenPengajuanPembelianlog(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPengajuanPembelianlog::tableName();
				$param['pk']= $param['table'].".". \app\models\TPengajuanPembelianlog::primaryKey()[0];
				$param['column'] = [$param['table'].'.pengajuan_pembelianlog_id',
									"CONCAT(kode,'-',revisi) AS kode",
									$param['table'].'.tanggal',
									$param['table'].'.nomor_kontrak',
									$param['table'].'.volume_kontrak',
									'm_suplier.suplier_nm',
									$param['table'].'.asal_kayu',
									$param['table'].'.total_volume',
									$param['table'].'.cancel_transaksi_id',
                                    $param['table'].'.suplier_id',
                                    $param['table'].'.status',
									];
				$param['join']= ['
								JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id 
								'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL and status = 'APPROVED' and ".$param['table'].".spk_shipping_id IS NULL ";                
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
            return $this->renderAjax('_openPengajuanPembelianlog',[]);
        }
    }

    public function actionPickPengajuanPembelianlog(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data['html'] = ""; $data['pengajuan_pembelianlog_id']="";
            if(!empty($id)){
                $model = \app\models\TPengajuanPembelianLog::findOne($id);
                $modSuplier = \app\models\MSuplier::findOne($model->suplier_id);
                $sql_jumlahBatang = "select sum(qty_batang) from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$id."";
                $jumlah_batang = Yii::$app->db->createCommand($sql_jumlahBatang)->queryScalar();
                $sql_jumlahVolume = "select sum(qty_m3) from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$id."";
                $jumlah_volume = Yii::$app->db->createCommand($sql_jumlahVolume)->queryScalar();
                $data['html'] .= $this->renderPartial('_itemPermintaan',['model'=>$model,'modSuplier'=>$modSuplier,'jumlah_batang'=>$jumlah_batang,'jumlah_volume'=>$jumlah_volume]);
                $data['lokasi_muat'] = $model->lokasi_muat;
                $data['pengajuan_pembelianlog_id'] = $model->pengajuan_pembelianlog_id;
            }
            return $this->asJson($data);
        }
    }

	public function actionEditDkg($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TDkg::findOne($id);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->graderlog_nm = $model->graderlog->graderlog_nm;
			$model->wilayah_dinas_nama = $model->wilayahDinas->wilayah_dinas_nama;
            $modMap = new \app\models\MapDkgPmrPengajuanPembelianlog();
            $modPmr = new \app\models\TPmr();
            $modPengajuanPembelianlog = new \app\models\TPengajuanPembelianlog();
			$modDkgs = \app\models\TDkg::find()->where("status = '".\app\models\TDkg::AKTIF_DINAS."'")->all();

			$grader_aktif = [];
			if(count($modDkgs)>0){
				foreach($modDkgs as $i => $dkg){
					$grader_aktif[] = $dkg->graderlog_id;
				}
			}
            
            if( isset($_POST['TDkg']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = FALSE; // t_dkg
					$model->load(\Yii::$app->request->post());
                    $model->jenis_log = 'LA';
					$model->kode = \app\components\DeltaGenerator::kodeDKG();
					$model->status = \app\models\TDkg::AKTIF_DINAS;
					if($model->validate()){
						if($model->save()){
                            $success_1 = true;
						}
					}

                    $success_2 = false;
                    if ($success_1) {
                        $modMap->dkg_id = $model->dkg_id;

                        if (isset($_POST['pmr_id'])) {
                            // hapus data lama dulu
                            $delete = \app\models\MapDkgPmrPengajuanPembelianlog::deleteAll(['and',[ 'dkg_id'=>$model->dkg_id]]);
                            $modMap->pengajuan_pembelianlog_id = $_POST['pmr_id'];
                            if ($delete) {
                                for ($i=0; $i < count($_POST['pmr_id']); $i++) {
                                    $pmr_id = $_POST['pmr_id'][$i];
                                    if ($model->dkg_id != "" && $model->dkg_id != NULL && $model->dkg_id > 0 
                                            && $pmr_id != "" && $pmr_id != NULL && $pmr_id > 0) {
                                        $sql_insert = "insert into map_dkg_pmr_pengajuan_pembelianlog ".
                                                    "   (dkg_id, pmr_id) ".
                                                    "   values ".
                                                    "   ($model->dkg_id, $pmr_id) ". 
                                                    "   ";
                                        Yii::$app->db->createCommand($sql_insert)->execute();
                                    }
                                }
                                $success_2 = true;
                            } else {
                                //$success_2 = false;
                            }
                        }
                        
                        if (isset($_POST['pengajuan_pembelianlog_id'])) {
                            $delete = \app\models\MapDkgPmrPengajuanPembelianlog::deleteAll(['and',[ 'dkg_id'=>$model->dkg_id]]);
                            $modMap->pengajuan_pembelianlog_id = $_POST['pengajuan_pembelianlog_id'];
                            if ($delete) {
                                if($modMap->validate()){
                                    if ($modMap->save()) {
                                        $success_2 = true;
                                    }
                                }
                            } else {
                                $success_2 = false;
                            }
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
			return $this->renderAjax('editDinas',['model'=>$model, 'modMap'=>$modMap, 'modPmr'=>$modPmr,'modPengajuanPembelianlog'=>$modPengajuanPembelianlog,'actionname'=>'EditDinas','grader_aktif'=>$grader_aktif]);
		}
	}

    public function actionGetGrader() {
        if(\Yii::$app->request->isAjax){
            $jenis_log = Yii::$app->request->post('jenis_log');
            $data['html'] = '';
            if ($jenis_log == "LA") {
                $xxx = "GLA";
            } else if ($jenis_log == "LS") {
                $xxx = "GLS";
            } else {
                $xxx = "GLJ";
            }

            $html = "";
            $html .= '<div class="form-group field-G"'.$xxx.'"">
                        <label class="col-md-5 control-label" for="G'.$xxx.'">Grader</label>
                        <div class="col-md-6">
                            <select id="'.$xxx.'" class="form-control select2" name="TDkg[graderlog_id]" aria-invalid="false">';
            
            $graders = \app\models\MGraderlog::find()->where(['type'=>$xxx, 'active'=>true])->orderBy(['graderlog_nm'=>'ASC'])->all();
            foreach ($graders as $kolom) {
                $graderlog_id = $kolom->graderlog_id;
                $graderlog_nm = $kolom->graderlog_nm;
                $html .= "<option value='".$graderlog_id."'>".$graderlog_nm."</option>";
            }
            $html .= '       </select> 
                            <span class="help-block"></span>
                        </div>
                    </div>';
            $data['html'] = $html;
            return $this->asJson($data);
        }
    }

	public function actionDeleteItem($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TDkg::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = 'getItems()';
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>'deleteItem']);
		}
	}
	
	public function actionValidationSelesaiDinas($dkg_id){
		if(\Yii::$app->request->isAjax){
			$modAjuanDinas = \app\models\TAjuandinasGrader::find()->where(['dkg_id'=>$dkg_id])->all();
			$modAjuanMakan = \app\models\TAjuanmakanGrader::find()->where(['dkg_id'=>$dkg_id])->all();
			$status_dinas = true;
			$status_makan = true;
			if(count($modAjuanDinas)>0){
				foreach($modAjuanDinas as $dinas){
					$modApproveDinas = \app\models\TApproval::findOne(['reff_no'=>$dinas->kode]);
					if($modApproveDinas->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
						$status_dinas &= false;
					}
				}
			}
			if(count($modAjuanMakan)>0){
				foreach($modAjuanMakan as $makan){
					$modAjuanMakan = \app\models\TApproval::findOne(['reff_no'=>$makan->kode]);
					if($modAjuanMakan->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
						$status_makan &= false;
					}
				}
			}
			if($status_dinas && $status_makan){
				$data['status'] = true;
			}else{
				$data['status'] = false;
				$data['msg'] = "Tidak bisa menyelesaikan dinas grader ini karena masih ada pengajuan yang belum terkonfirmasi";
			}
			return $this->asJson($data);
		}
	}

	public function actionChangeStatus($dkg_id){
		if(\Yii::$app->request->isAjax){
			$modDkg = \app\models\TDkg::findOne($dkg_id);
			$pesan = "Anda akan me Non-Aktif kan Kerja Dinas Grader '<b>".$modDkg->graderlog->graderlog_nm."</b>' ?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_dkg
					$modDkg->status = \app\models\TDkg::NON_AKTIF_DINAS;
					$modDkg->saldo_akhir_dinas = \app\models\HKasDinasgrader::getSaldoKas($modDkg->graderlog_id);
					$modDkg->saldo_akhir_makan = \app\models\HKasMakangrader::getSaldoKas($modDkg->graderlog_id);
					$modDkg->selesai_dinas_at = date('Y-m-d H:i:s');
					if($modDkg->validate()){
						if($modDkg->save()){
							$success_1 = true;
						}
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( ".fa-close" ).click(); setClosingBtn();';
						$data['message'] = Yii::t('app', "Data Berhasil Di Non-Aktifkan");
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
			return $this->renderAjax('_changeStatus',['id'=>$dkg_id,'pesan'=>$pesan,'modDkg'=>$modDkg,'actionname'=>'ChangeStatus']);
		}
	}
	
	public function actionHistory(){
		$model = new \app\models\TMutasiGudanglogistik();
		$model->tgl_awal = date('d/m/Y', strtotime('first day of this month'));
		$model->tgl_akhir = date('d/m/Y');
        $wheretype = "AND m_graderlog.type  = ''";
        if(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER){
            $wheretype = "";
        }elseif( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_SENGON)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_SENGON) ){
            $wheretype = "AND m_graderlog.type  = 'GLS'";
        }elseif( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_ALAM)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_ALAM) ){
            $wheretype = "AND m_graderlog.type  = 'GLA'";
        }
        $model = \app\models\TDkg::find()->join("JOIN", "m_graderlog", "m_graderlog.graderlog_id = t_dkg.graderlog_id")
                    ->where("status = '".\app\models\TDkg::AKTIF_DINAS."' {$wheretype}")->orderBy(['created_at'=>SORT_DESC])->all();
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-detail'){
				$param['table']= \app\models\TDkg::tableName();
				$param['pk']= \app\models\TDkg::primaryKey()[0];
				$param['column'] = ['dkg_id',$param['table'].'.kode','tipe','graderlog_nm','wilayah_dinas_nama','saldo_akhir_dinas','saldo_akhir_makan','status','selesai_dinas_at'];
				$param['where']= "status = '".\app\models\TDkg::NON_AKTIF_DINAS."' {$wheretype}";
				$param['order']= $param['table'].".created_at DESC";
				$param['join']= ['JOIN m_graderlog ON m_graderlog.graderlog_id = '.$param['table'].'.graderlog_id',
								 'JOIN m_wilayah_dinas ON m_wilayah_dinas.wilayah_dinas_id = '.$param['table'].'.wilayah_dinas_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('history');
        }
		return $this->render('history',['model'=>$model]);
	}
	
	public function actionDetailBiaya($dkg_id){
		if(\Yii::$app->request->isAjax){
			$modDkg = \app\models\TDkg::findOne($dkg_id);
            $modMap = \app\models\MapDkgPmrPengajuanPembelianlog::find()->where(['dkg_id'=>$dkg_id])->all();
			return $this->renderAjax('_summary',['id'=>$dkg_id,'modDkg'=>$modDkg,'modMap'=>$modMap,'actionname'=>'ChangeStatus']);
		}
	}
}
