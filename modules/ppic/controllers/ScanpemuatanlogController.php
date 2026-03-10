<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\components\Params;
use yii\db\Exception;
use yii\helpers\Json;
use app\components\SSP;
use app\models\TSpmKo;
use app\models\HPersediaanLog;
use app\models\TLogKeluar;
use app\models\TSpmKoDetail;
use app\models\TSpmLog;
use app\models\TApproval;
use app\controllers\DeltaBaseController;

class ScanpemuatanlogController extends DeltaBaseController
{
    public $defaultAction = 'index';

    public function actionIndex(){
		$model = new TLogKeluar();
		$modSpmLog = new TSpmLog();
		$modPersediaan = new HPersediaanLog();
        if (isset($_GET['spm_ko_id'])) {
            $model->spm_ko_id = $_GET['spm_ko_id'];
        }
        return $this->render('index',['model'=>$model, 'modSpmLog'=>$modSpmLog, 'modPersediaan'=>$modPersediaan]);
	}

    public function actionShowDetailLog(){
        $data['status']         = false;
        if(Yii::$app->request->isAjax){
			$spm_ko_id          = Yii::$app->request->post('spm_ko_id');
			$no_barcode     	= Yii::$app->request->post('no_barcode');
            $data['no_barcode'] = $no_barcode;
			$modSpm             = TSpmKo::findOne($spm_ko_id);
			$modLogKeluar    	= TLogKeluar::find()
                                                ->where(['no_barcode' => $no_barcode])
                                                ->andWhere(['or', ['cancel_transaksi_id' => null]])
                                                ->one();
			$modKeluar = TLogKeluar::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$modSpm->kode]);
			$persediaan = HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
			$sedia_fsc = $persediaan['fsc']?'true':'false';
			$modPersediaan = HPersediaanLog::getDataScanned($no_barcode, $sedia_fsc);
			$fsc = $persediaan['fsc']?'true':'false';
			if (substr($_POST['datas'], 0, 5) == "ID : ") {
			$maxLength = 12; // Misalnya, kita hanya mengizinkan 12 karakter
			// if ($this->isValidBarcode($_POST['datas']) && strlen($no_barcode) === $maxLength && $this->isNoBarcodeValid($no_barcode)){
			// if ($this->isNoBarcodeValid($no_barcode)){
				if($modKeluar === null){
					if((!empty($persediaan)) ){
						$modBrgLog = Yii::$app->db->createCommand("
										SELECT * FROM m_brg_log 
										JOIN h_persediaan_log ON h_persediaan_log.kayu_id = m_brg_log.kayu_id 
										WHERE no_barcode = '{$no_barcode}'
										AND h_persediaan_log.kayu_id = {$persediaan['kayu_id']} 
										AND fisik_diameter BETWEEN range_awal AND range_akhir AND m_brg_log.fsc = '$fsc'")->queryOne();
						$modSPMdetail = TSpmKoDetail::find()->where(['spm_ko_id' => $modSpm->spm_ko_id, 'produk_id' => $modBrgLog['log_id']])->all();
						if(count($modSPMdetail)>0){
							$spmsama = true;
						}else{
							$spmsama = false;
						}
						if($spmsama){
							if((!empty($modPersediaan))){
								if($modLogKeluar === null){
									if($modSpm !== null){
										$data['status'] = true;
										$data['msg']    = "Data ok";
									}else{
										$data['status'] = false;
										$data['msg']    = "Data SPM tidak ditemukan!";
									}
								}else{
									$data['status'] = false;
									$data['msg']    = "Produk sudah discan di SPM lain";
								}
							} else {
								$data['status'] = false;
								$data['msg']    = "Tidak tersedia di stock!!";
							}
						}else{
							$data['status'] = false;
							$data['msg']    = "Produk tidak sesuai dengan SPM";
						}
					}else{
						$data['status'] = false;
						$data['msg']    = "Tidak tersedia di stock!";
					}
				} else {
					$data['status'] = false;
					$data['msg']    = "Produk sudah discan";
				}
			} else {
				$data['msg'] = "Invalid QR Code Format -> " . $_POST['datas'];
			}
		} else {
            $data['msg'] = "xxx";
        }
        return $this->asJson($data);
	}

    function actionGetItemsLogScanned(){
		if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data = [];
			$data['html'] = '';
			$data['status'] = "";
            if(!empty($id)){
                $modSPM = TSpmKo::findOne($id);
				$data['status'] = $modSPM->status;
				$models = TLogKeluar::find()->where(["reff_no"=>$modSPM->kode])->orderBy("log_keluar_id DESC")->all();
				if(count($models)>0){
					foreach($models as $i => $model){
						$persediaan = \app\models\HPersediaanLog::find()->where(['no_barcode'=>$model->no_barcode])->all();
						if(count($persediaan) == 1){
							$modPersediaan = \app\models\HPersediaanLog::findOne(['no_barcode'=>$model->no_barcode]);
						} else {
							$modPersediaan = \app\models\HPersediaanLog::findOne(['no_barcode'=>$model->no_barcode, 'reff_no'=>$modSPM->kode]);
						}
						$modSpmKoDetail = \app\models\TSpmKoDetail::findOne(['spm_ko_id'=>$id]);
						$modBrgLog = \app\models\MBrgLog::findOne(['log_id'=>$modSpmKoDetail->produk_id]);
						$modKayu = \app\models\MKayu::findOne(['kayu_id'=>$modPersediaan->kayu_id]);
						$modSpmLog = \app\models\TSpmLog::findOne(['reff_no'=>$modSPM->kode, 'no_barcode'=>$model->no_barcode]);
						$data['html'] .= $this->renderPartial('_itemScannedLogSpm',['model'=>$model, 'modBrgLog'=>$modBrgLog, 'modPersediaan'=>$modPersediaan, 'modSpmLog'=>$modSpmLog, 'modKayu'=>$modKayu]); 
					}
				}
            }
            return $this->asJson($data);
        }
    }

	public function actionReviewLog(){
        if(Yii::$app->request->isAjax){
			// $model = new TLogKeluar();
			$modSpmLog = new TSpmLog();
			// $modPersediaan = new HPersediaanLog();
            $spm_ko_id 		= $_GET['spm_ko_id'];
            $no_barcode 	= $_GET['no_barcode'];
            $modSpm 		= TSpmKo::findOne($spm_ko_id);
			$modPersediaan 	= HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
			$fsc = $modPersediaan->fsc?'true':'false';
            $modLogKeluar 	= TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
			$modBrgLog 		= Yii::$app->db->createCommand(
									"SELECT * FROM h_persediaan_log 
									JOIN m_brg_log ON h_persediaan_log.kayu_id = m_brg_log.kayu_id 
									WHERE h_persediaan_log.kayu_id = {$modPersediaan->kayu_id} AND fisik_diameter BETWEEN range_awal 
									AND range_akhir AND no_barcode = '{$no_barcode}' AND m_brg_log.fsc = '$fsc'"
								)->queryAll();
			$modKayu		= \app\models\MKayu::findOne(['kayu_id'=>$modPersediaan->kayu_id]);
            return $this->renderAjax('_reviewLog',[	'spm_ko_id'		=>$spm_ko_id, 
                                                	'no_barcode'	=>$no_barcode,
                                                	'modSpm'		=>$modSpm,
                                                	'modLogKeluar'	=>$modLogKeluar,
                                                	'modPersediaan'	=>$modPersediaan,
                                                	'modBrgLog'		=>$modBrgLog,
													'modKayu' 		=> $modKayu,
													// 'model'			=> $model,
													'modSpmLog'		=> $modSpmLog
                                                	]);
        }
    }

    public function actionSaveNoBarcode(){
		if(Yii::$app->request->isAjax){
			$modSpmLog = new TSpmLog();
			$data['status'] = false;
			$data['msg'] 	= "";
			$no_barcode 	= Yii::$app->request->post('no_barcode');
			$spm_ko_id 		= Yii::$app->request->post('spm_ko_id');
			$modSpm 		= TSpmKo::findOne($spm_ko_id);
			$modLogKeluar   = TLogKeluar::find()
                                            ->where(['no_barcode' => $no_barcode])
                                            ->andWhere(['or', ['cancel_transaksi_id' => null]])
                                            ->one();
			$modKeluar 		= TLogKeluar::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$modSpm->kode]);
			$persediaan 	= HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
			$sedia_fsc = $persediaan->fsc?'true':'false';
			$modPersediaan  = HPersediaanLog::getDataScanned($no_barcode, $sedia_fsc);
			$fsc = $modPersediaan['fsc']?'true':'false';
			$modBrgLog = Yii::$app->db->createCommand("
											SELECT * FROM m_brg_log 
											JOIN h_persediaan_log ON h_persediaan_log.kayu_id = m_brg_log.kayu_id 
											WHERE no_barcode = '{$no_barcode}'
											AND h_persediaan_log.kayu_id = {$persediaan['kayu_id']} 
											AND fisik_diameter BETWEEN range_awal AND range_akhir AND m_brg_log.fsc = '$fsc'")->queryOne();
			// if($modKeluar === null){
			// if( Yii::$app->request->post('TSpmKo')){
				$transaction = Yii::$app->db->beginTransaction();
				try {
					$success_1 = false; // t_log_keluar
					$success_2 = false; // h_persediaan_log
					$success_3 = false; // t_spm_log

					if($modKeluar === null){
						if((!empty($persediaan)) ){
							$modBrgLog = Yii::$app->db->createCommand("
											SELECT * FROM m_brg_log 
											JOIN h_persediaan_log ON h_persediaan_log.kayu_id = m_brg_log.kayu_id 
											WHERE no_barcode = '{$no_barcode}'
											AND h_persediaan_log.kayu_id = {$persediaan['kayu_id']} 
											AND fisik_diameter BETWEEN range_awal AND range_akhir AND m_brg_log.fsc = '$fsc'")->queryOne();
							$modSPMdetail = TSpmKoDetail::find()->where(['spm_ko_id' => $modSpm->spm_ko_id, 'produk_id' => $modBrgLog['log_id']])->all();
							if(count($modSPMdetail)>0){
								$spmsama = true;
							}else{
								$spmsama = false;
							}
							if($spmsama){
								if((!empty($modPersediaan))){
									if($modLogKeluar === null){
										if($modSpm !== null){
											$modLogKeluar = new TLogKeluar();
											// $modLogKeluar->attributes = $_POST['TLogKeluar'];
											$modLogKeluar->kode = \app\components\DeltaGenerator::kodeLogKeluar($modBrgLog['log_kelompok']);
											$modLogKeluar->tanggal = date("Y-m-d");
											$modLogKeluar->no_barcode = $no_barcode;
											$modLogKeluar->cara_keluar = TLogKeluar::CARA_KELUAR_PENJUALAN;
											$modLogKeluar->reff_no = $modSpm->kode;
											$modLogKeluar->pic_log_keluar = Yii::$app->user->identity->pegawai_id;
											if($modLogKeluar->validate()){
												if($modLogKeluar->save()){
													$success_1 = true;
													// $data['status'] = true;

													$modPersediaanLog = new HPersediaanLog();
													$modPersediaanLog->attributes 		= $modLogKeluar->attributes;
													$modPersediaanLog->no_barcode		= $no_barcode;
													$modPersediaanLog->tgl_transaksi 	= date("Y-m-d");
													$modPersediaanLog->reff_no 			= $modSpm->kode;
													$modPersediaanLog->status 			= 'OUT';
													$modPersediaanLog->keterangan 		= 'MUTASI LOG DARI GUDANG LOG ALAM MENUJU PENJUALAN';
													$modPersediaanLog->kayu_id 			= $persediaan['kayu_id'];
													$modPersediaanLog->lokasi			= 'PENJUALAN LOG ALAM';
													$modPersediaanLog->fisik_diameter	= $_POST['diameter_rata']; //$persediaan['fisik_diameter'];
													$modPersediaanLog->fisik_panjang	= $_POST['panjang']; //$persediaan['fisik_panjang'];
													$modPersediaanLog->fisik_reduksi	= $persediaan['fisik_reduksi'];
													$modPersediaanLog->fisik_volume		= $_POST['volume']; //$persediaan['fisik_volume'];
													$modPersediaanLog->no_produksi		= $persediaan['no_produksi'];
													$modPersediaanLog->no_grade			= $persediaan['no_grade'];
													$modPersediaanLog->no_btg			= $persediaan['no_btg'];
													$modPersediaanLog->no_lap			= $persediaan['no_lap'];
													$modPersediaanLog->diameter_ujung1	= $_POST['diameter_ujung1']; //$persediaan['diameter_ujung1'];
													$modPersediaanLog->diameter_ujung2	= $_POST['diameter_ujung2']; //$persediaan['diameter_ujung2'];
													$modPersediaanLog->diameter_pangkal1	= $_POST['diameter_pangkal1']; //$persediaan['diameter_pangkal1'];
													$modPersediaanLog->diameter_pangkal2	= $_POST['diameter_pangkal2'];; //$persediaan['diameter_pangkal2'];
													$modPersediaanLog->cacat_panjang	= $_POST['cacat_panjang']; //$persediaan['cacat_panjang'];
													$modPersediaanLog->cacat_gb		= $_POST['cacat_gb']; //$persediaan['cacat_gb'];
													$modPersediaanLog->cacat_gr		= $_POST['cacat_gr']; //$persediaan['cacat_gr'];
													$modPersediaanLog->pot				= $persediaan['pot'];
													$modPersediaanLog->fsc				= ($persediaan['fsc'] == 1)?true:false;
													if($modPersediaanLog->validate()){
														if($modPersediaanLog->save()){
															$success_2 = true;
														}
													} 

													$modSpmLog = new TSpmLog();
													$modSpmLog->reff_no 	= $modSpm->kode;
													$modSpmLog->no_barcode	= $no_barcode;
													$modSpmLog->no_lap 		= $modPersediaanLog->no_lap;
													$modSpmLog->no_grade 	= $modPersediaanLog->no_grade;
													$modSpmLog->no_btg 		= $modPersediaanLog->no_btg;
													$modSpmLog->no_produksi = $modPersediaanLog->no_produksi;
													$modSpmLog->kode_potong	= $modPersediaanLog->pot;
													$modSpmLog->kayu_id 	= $modPersediaanLog->kayu_id;
													$modSpmLog->panjang		= $_POST['panjang'];
													$modSpmLog->diameter_ujung1 	= $_POST['diameter_ujung1'];
													$modSpmLog->diameter_ujung2 	= $_POST['diameter_ujung2'];
													$modSpmLog->diameter_pangkal1 	= $_POST['diameter_pangkal1'];
													$modSpmLog->diameter_pangkal2 	= $_POST['diameter_pangkal2'];
													$modSpmLog->diameter_rata 	= $_POST['diameter_rata'];
													$modSpmLog->cacat_panjang 	= $_POST['cacat_panjang'];
													$modSpmLog->cacat_gb 		= $_POST['cacat_gb'];
													$modSpmLog->cacat_gr 		= $_POST['cacat_gr'];
													$modSpmLog->volume 		= $_POST['volume'];
													if($modSpmLog->validate()){
														if ($modSpmLog->save()){
															$success_3 = true;
														}
													}
												}
											}
										}else{
											$data['status'] = false;
											$data['msg']    = "Data SPM tidak ditemukan!";
										}
									}else{
										$data['status'] = false;
										$data['msg']    = "Produk sudah discan di SPM lain";
									}
								} else {
									$data['status'] = false;
									$data['msg']    = "Tidak tersedia di stock!";
								}
							}else{
								$data['status'] = false;
								$data['msg']    = "Produk tidak sesuai dengan SPM";
							}
						}else{
							$data['status'] = false;
							$data['msg']    = "Tidak tersedia di stock!";
						}
					} else {
						$data['status'] = false;
						$data['msg']    = "Produk sudah pernah keluar!";
					}

					// echo '<pre>1';
					// print_r($success_1);
					// echo '<pre>2';
					// print_r($success_2);
					// echo '<pre>3';
					// print_r($success_3);
					// // print_r($_POST['panjang']);
					// exit;
					if ($success_1 && $success_2 && $success_3) { //
						$transaction->commit();
						$data['msg'] = "Data OK!";
						$data['status'] = true;
						$data['message'] = Yii::t('app', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
						Yii::$app->session->setFlash('success', 'Data berhasil disimpan!');
					} else {
						$transaction->rollback();
						$data['msg'] = "Data gagal tersimpan!";
						$data['status'] = false;
						$data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE);
						// (!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						// (isset($data['message_validate']) ? $data['message'] = null : '');
						// Yii::$app->session->setFlash('error', 'Data gagal disimpan!');
					}
				} catch (Exception $ex) {
					$transaction->rollback();
					$data['message'] = $ex;
				}
			// }
			return $this->asJson($data);
		}
	}

	public function actionDeleteNoBarcode($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TLogKeluar::findOne($id);
            $modSpm = TSpmKo::findOne(['kode' => $model->reff_no]);
			$modPersediaan = HPersediaanLog::findOne(['no_barcode'=>$model->no_barcode, 'reff_no'=>$model->reff_no]);
			$modSpmLog = \app\models\TSpmLog::findOne(['reff_no'=>$model->reff_no, 'no_barcode'=>$model->no_barcode]);
            if (Yii::$app->request->post('deleteRecord')) {
                $data['status']     = false;
                $data['message']    = Params::DEFAULT_FAILED_TRANSACTION_MESSAGE;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $approver = TApproval::find()
                        ->join('INNER JOIN', 't_pengajuan_manipulasi', 't_pengajuan_manipulasi.reff_no = t_approval.reff_no AND t_approval.parameter1 = t_pengajuan_manipulasi.kode')
                        ->where([
                            't_approval.reff_no'            => $modSpm->kode,
                            't_approval.level'              => 1,
                            't_pengajuan_manipulasi.status' => 'PROCESS'
                        ])->one();
                    if($approver !== null) {
                        if($modSpm->cust->cust_tipe_penjualan === 'export' && $modSpm->status !== TSpmKo::REALISASI) {
                            if(($approver->status === TApproval::STATUS_APPROVED) && $model->delete()) {
                                $data['status']     = true;
                                $data['callback']   = "getItemsLogScanned(" . $modSpm->spm_ko_id . ");";
                                $data['message']    = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                                $transaction->commit();
                                return $this->asJson($data);
                            }
                            $panggilan  = $approver->assignedTo->pegawai_jk === 'Perempuan' ? 'Ibu ' : 'Bapak ';
                            $nama       = ucwords(strtolower($approver->assignedTo->pegawai_nama));
                            throw new Exception("<i class='icon-close'></i> Data Gagal dihapus karena $panggilan <strong> $nama </strong> belum melakukan approve");
                        }
                    }else if($model->delete() && $modPersediaan->delete() && $modSpmLog->delete()) { //&& $modPersediaan->delete() && $modSpmLog->delete()
                        $data['status']     = true;
                        $data['callback']   = "getItemsLogScanned(" . $modSpm->spm_ko_id . ");";
                        $data['message']    = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                        $transaction->commit();
                        return $this->asJson($data);
                    }
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $data['status']     = false;
                    $data['message']    = $ex->getMessage();
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm', [
                'id'            => $id,
                'actionname'    => 'deleteNoBarcode'
            ]);
        }
    }

	// public function actionSetOPScan(){
	// 	if(Yii::$app->request->isAjax){
	// 		$spm_ko_id = Yii::$app->request->post('spm_ko_id');
	// 		$data = [];
	// 		if(!empty($spm_ko_id)){
	// 			$model = \app\models\TSpmKo::findOne($spm_ko_id);
	// 			// $modCust = \app\models\MCustomer::findOne($model->cust_id);
	// 			if(!empty($model)){
	// 				$data = $model->attributes;
	// 				$data['tanggal_kirim'] = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
	// 			}
	// 			if(!empty($modCust)){
	// 				$data['cust'] = $modCust->attributes;
	// 				$data['cust']['cust_pr_nama'] = (!empty($modCust->cust_pr_nama)?$modCust->cust_pr_nama:"-");
					
	// 			}
	// 		}
	// 		return $this->asJson($data);
	// 	}
	// }

	// function actionGetItemsScan(){
	// 	if(Yii::$app->request->isAjax){
    //         $spm_ko_id = Yii::$app->request->post('spm_ko_id');
	// 		$model = new TSpmKo();
	// 		$modDetail = [];
    //         $modOpKo = [];
    //         $data = [];
    //         if(!empty($spm_ko_id)){
	// 			$model = \app\models\TSpmKo::findOne($spm_ko_id);
    //             $modOpKo = \app\models\TOpKo::findOne($model->op_ko_id);
    //             $model->jenis_produk = $modOpKo->jenis_produk;
    //             $modOPDetail = \app\models\TOpKoDetail::find()->where(['op_ko_id'=>$modOpKo->op_ko_id])->all();
    //         }
    //         $data['html'] = '';
    //         if(count($modOPDetail)>0){
    //             foreach($modOPDetail as $i => $opdetail){
	// 				$modDetail = new TSpmKoDetail();
	// 				$modDetail->attributes = $opdetail->attributes;
	// 				$modLog = \app\models\MBrgLog::findOne(['log_id'=>$opdetail->produk_id]);
    //                 $data['html'] .= $this->renderPartial('_itemOP',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i,'modOpDetail'=>$opdetail, 'modLog'=>$modLog]);
    //             }
    //         }
    //         return $this->asJson($data);
    //     }
    // }

	public function actionInfoLog($no_barcode){
		if(Yii::$app->request->isAjax){
			$modLogKeluar = \app\models\TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
			$modPersediaan = \app\models\HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
			$fsc = $modPersediaan->fsc?'true':'false';
			$modKayu = \app\models\MKayu::findOne($modPersediaan->kayu_id);
			$modBrgLog = Yii::$app->db->createCommand("
											SELECT * FROM m_brg_log WHERE kayu_id = {$modPersediaan->kayu_id} 
											AND {$modPersediaan->fisik_diameter} BETWEEN range_awal AND range_akhir AND m_brg_log.fsc = '$fsc'
										")->queryOne();
			return $this->renderAjax('infoLog',['modLogKeluar'=>$modLogKeluar, 'no_barcode'=>$no_barcode, 'modPersediaan'=>$modPersediaan, 'modKayu'=>$modKayu, 'modBrgLog'=>$modBrgLog]);
        }
    }

	public function actionLogListOnModal($tr_seq=null,$jns_produk=null,$data_log_nama=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
				// $notinpost = json_decode( Yii::$app->request->get('notin') );
				// $notin = "";
				// if(!empty($notinpost)){
				// 	$notin = " h_persediaan_log.kayu_id NOT IN(";
				// 	foreach($notinpost as $i => $not){
				// 		$notin .= "'$not'";
				// 		if( ($i+1)!=(count($notinpost)) ){
				// 			$notin .= ",";
				// 		}
				// 	}
				// 	$notin .= ")";
				// }
				$array_data = explode(',', $data_log_nama);
				$data = [];
				foreach ($array_data as $item) {
					$data[] = "'" . trim($item) . "'";;
				}
				$log_nama = implode(', ', $data);
				$param['table']= HPersediaanLog::tableName();
				$param['pk']= HPersediaanLog::primaryKey()[0];
				$param['column'] 	= [ $param['table'].'.kayu_id', //0
										$param['table'].'.no_barcode', //1
										'm_kayu.group_kayu', //2
										'm_kayu.kayu_nama', //3
										'm_brg_log.log_nama', //4
										'm_brg_log.range_awal', //5
										'm_brg_log.range_akhir', //6
										'h_persediaan_log.no_barcode', //7
										'h_persediaan_log.no_lap', //8
										'h_persediaan_log.no_grade', //9
										'h_persediaan_log.no_btg', //10
										'h_persediaan_log.fisik_diameter', //11
										'h_persediaan_log.fisik_panjang', //12
										'h_persediaan_log.fisik_volume', //13
										'h_persediaan_log.diameter_ujung1', //14
										'h_persediaan_log.diameter_ujung2', //15
										'h_persediaan_log.diameter_pangkal1', //16
										'h_persediaan_log.diameter_pangkal2', //17
										'h_persediaan_log.cacat_panjang', //18
										'h_persediaan_log.cacat_gb', //19
										'h_persediaan_log.cacat_gr', //20
										'm_brg_log.log_kode' //21
									  ];
				$param['join'] 		= ["JOIN m_brg_log on m_brg_log.kayu_id = h_persediaan_log.kayu_id
										JOIN m_kayu on m_kayu.kayu_id = m_brg_log.kayu_id
										JOIN (SELECT no_barcode, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS total_stock FROM h_persediaan_log
											GROUP BY no_barcode HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0) s ON h_persediaan_log.no_barcode = s.no_barcode"];
				$param['where'] 	= " no_grade <> '-' and m_brg_log.active = true 
										AND fisik_diameter BETWEEN range_awal AND range_akhir
										AND log_nama IN ({$log_nama})";
				$param['group'] 	= ' GROUP BY '.$param['table'].'.kayu_id'.', m_kayu.group_kayu, m_kayu.kayu_nama, m_brg_log.log_nama, m_brg_log.range_awal, 
										m_brg_log.range_akhir, h_persediaan_log.no_barcode, h_persediaan_log.no_lap, h_persediaan_log.no_grade, 
										h_persediaan_log.no_btg, h_persediaan_log.fisik_diameter, h_persediaan_log.fisik_panjang, h_persediaan_log.fisik_volume, 
										h_persediaan_log.diameter_ujung1, h_persediaan_log.diameter_ujung2, h_persediaan_log.diameter_pangkal1, m_brg_log.log_kode,
										h_persediaan_log.diameter_pangkal2, h_persediaan_log.cacat_panjang,h_persediaan_log.cacat_gb,h_persediaan_log.cacat_gr';
				$param['having'] 	= " HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0";
				return Json::encode(SSP::complex( $param ));
			}
			return $this->renderAjax('loglistOnModal',['tr_seq'=>$tr_seq,'jns_produk'=>$jns_produk, 'data_log_nama'=>$data_log_nama]);
		}
	}

	public function isValidBarcode($data)
	{
		$lines = explode("\n", $data);
		
		foreach ($lines as $line) {
			if (preg_match('/[^\x20-\x7E]/', $line)) { // kalo ada karakter aneh
				return false; 
			}
		}

		return true;
	}

    function isNoBarcodeValid($no_barcode) { 
        // kalo no_barcode terdapat hurufnya
        // return !preg_match('/[A-Za-z]/', $no_barcode);
		return preg_match('/^[0-9]{12}/', $no_barcode);
		// return !preg_match('/[A-Za-z\u0000-\u0008\u000e-\u001f]/', $no_barcode);
    }

	public function actionInputManual()
    {
        if (\Yii::$app->request->isAjax) {
			$spm_ko_id = $_GET['spm_ko_id'];
            return $this->renderAjax('_inputManual', ['spm_ko_id'=>$spm_ko_id]);
        }
    }

	public function actionInputManuals()
    {
        if (Yii::$app->request->isAjax) {
            $req = $_POST;
            if($req['clause'] === 'no_lap' || $req['clause'] === 'no_barcode') {
                $modDetail = Yii::$app->db->createCommand("SELECT * FROM h_persediaan_log 
															WHERE ".trim($req['clause'])." = '".trim($req['keyword'])."'
															GROUP BY h_persediaan_log.persediaan_log_id
															HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0")->queryOne();
				// $modDetail = HPersediaanLog::findOne([trim($req['clause']) => trim($req['keyword'])]);
                if (count($modDetail) > 0) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modDetail['persediaan_log_id']}\nNo : {$modDetail['no_barcode']}",
						'no_barcode'=> $modDetail['no_barcode']
                    ]);
                }
            }else {
                $modDetails = Yii::$app->db->createCommand("
										SELECT persediaan_log_id, h_persediaan_log.no_barcode, no_grade, no_btg, no_produksi, no_lap FROM h_persediaan_log 
										INNER JOIN (SELECT no_barcode, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS total_stock FROM h_persediaan_log
											GROUP BY no_barcode HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0) s ON h_persediaan_log.no_barcode = s.no_barcode
										WHERE ".trim($req['clause']) ." = '". trim($req['keyword'])."'
										group by h_persediaan_log.persediaan_log_id, s.no_barcode, s.total_stock
										HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0")->queryAll();
                if(count($modDetails) === 1) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modDetails[0]['persediaan_log_id']}\nNo : {$modDetails[0]['no_barcode']}",
						'no_barcode'=> $modDetails[0]['no_barcode']
                    ]);
                }else {
                    return $this->asJson([
                        'status' => true,
                        'datas' => $modDetails
                    ]);
                }
            }
        }
        return $this->asJson(['status' => false, 'message' => 'Data tidak ditemukan']);
    }

	// public function actionShowDetail()
    // {
    //     if (\Yii::$app->request->isAjax) {
    //         $data = [];
    //         $data['status'] = false;
    //         $data['msg'] = "";
    //         // return $this->asJson(['msg' => 'PARAMS: ' . substr($_POST['datas'], 0, 5), 'status' => false]);
    //         if (substr($_POST['datas'], 0, 5) == "ID : ") {
    //             $data = explode("\n", $_POST['datas']);
    //             $baris_id = $data[0];
    //             $baris_kode = $data[1];
    //             $persediaan_log = explode(" : ", $baris_id);
    //             $persediaan_log_id = $persediaan_log[1];

    //             $sql_persediaan_log_id = "select persediaan_log_id from h_persediaan_log where persediaan_log_id = " . $persediaan_log_id . "";
    //             $persediaan_log_id = Yii::$app->db->createCommand($sql_persediaan_log_id)->queryScalar();

	// 			//log keluar
	// 			$kode = explode(" : ", $baris_kode);
	// 			$no_barcode = $kode[1];
    //             $sql_log_keluar = "select * from t_log_keluar where no_barcode = '" . $no_barcode . "'";
    //             $log_keluar = Yii::$app->db->createCommand($sql_log_keluar)->queryAll();

    //             if (count($log_keluar) == 0) {
    //                 $sql_countPersediaan = "select count(*) from h_persediaan_log " .
    //                     "   where persediaan_log_id = " . $persediaan_log_id .
    //                     "   and no_barcode = '" . $no_barcode . "' ";
    //                 $countDetail = Yii::$app->db->createCommand($sql_countPersediaan)->queryScalar();
    //                 $data['sql_countPersediaan'] = $sql_countPersediaan;
    //                 $data['countDetail'] = $countDetail;
    //                 if ($countDetail > 0) {
    //                     $modPersediaan = \app\models\HPersediaanLog::findOne(['persediaan_log_id' => $persediaan_log_id, 'no_barcode' => $no_barcode]);
    //                     $kayu_id = $modPersediaan->kayu_id;
    //                     $no_barcode = $modPersediaan->no_barcode;
    //                     $modKayu = \app\models\MKayu::find()->where(['kayu_id' => $kayu_id])->one();
    //                     $model = \app\models\TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
    //                     $modLogKeluar = new \app\models\TLogKeluar();
    //                     $data['persediaan_log_id'] = $persediaan_log_id;
    //                     $data['no_barcode'] = $no_barcode;
    //                     $sql_cek = "select count(*) from t_log_keluar where no_barcode = '" . $no_barcode . "' ";
    //                     $jumlah_log_keluar = Yii::$app->db->createCommand($sql_cek)->queryScalar();
    //                     if ($jumlah_log_keluar > 0) {
    //                         $data['msg'] = "Data sudah ada";
    //                     } else {
    //                         $data['msg'] = "Data ok";
    //                     }
    //                 } else {
    //                     $data['msg'] = "Data tidak ditemukan";
    //                 }
    //             } else {
    //                 $data['persediaan_log_id'] = $persediaan_log_id;
    //                 $data['msg'] = "Data log alam untuk dijual";
    //             }
    //         } else {
    //             $data['msg'] = "Invalid QR Code Format -> " . $_POST['datas'];
    //         }
    //     }
    //     return $this->asJson($data);
    // }

	// public function actionView()
    // {
    //     // jika log alam untuk dijual tidak masuk ke h_persediaan
    //     // jika log alam untuk pabrik masuk ke h_persediaan
    //     if (\Yii::$app->request->isAjax) {
    //         $persediaan_log_id = $_GET['persediaan_log_id'];
    //         $modDetail = \app\models\HPersediaanLog::findOne($persediaan_log_id);
    //         $no_barcode = $modDetail->no_barcode;
    //         $kayu_id = $modDetail->kayu_id;
    //         $modKayu = \app\models\MKayu::findOne(['kayu_id' => $kayu_id]);
    //         $modLogKeluar = \app\models\TLogKeluar::findOne(['no_barcode' => $no_barcode]);

    //         // if (empty($modLogKeluar)) {
    //         //     $title = "<font style='color: #2ebd30;'>LOG SUDAH DITERIMA</font>";
    //         // } else {
    //         //     $title = "<font style='color: #f00;'>LOG UNTUK DIJUAL</font>";
    //         // }
    //         return $this->renderAjax('_view', ['modDetail' => $modDetail, 'modKayu' => $modKayu,'modLogKeluar' => $modLogKeluar]);
    //     }
    // }
}
