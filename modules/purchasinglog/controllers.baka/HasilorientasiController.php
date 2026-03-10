<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class HasilorientasiController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\THasilOrientasi();
        $model->kode = "Auto Generate";
        $model->tanggal = date("d/m/Y");
        $model->rkt_pertahun = 0;
        $model->kondisi_logpond = "Pasang Surut";
        $model->kondisi_alat_berat = "Bagus";
        $model->perjanjian_scaling = "Ukur Ulang";
		$model->kualitas_kayu = "Bagus";
		$model->kondisi_perusahaan = "Bagus";
		$model->rekomendasi_grader = "Beli";
		$model->selisih_ukur = "Bagus";
		$model->jumlah_sampling_log = "";
		$model->tahun_target_rkt1 = date("Y")-1;
		$model->tahun_target_rkt2 = date("Y")-2;
		$model->perlakuan_log_tidak_standard = "Tinggal";
		$model->by_kanit = \app\components\Params::DEFAULT_PEGAWAI_ID_SEKAR;
		$model->by_kadiv = \app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
		$model->by_gmopr = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
        //$model->by_gmpurch = \app\components\Params::DEFAULT_PEGAWAI_ID_TATANG;
        $model->by_gmpurch = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
		$model->by_dirut = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
		$model->by_kanit_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_SEKAR)->pegawai_nama;
        //$model->by_gmpurch_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_TATANG)->pegawai_nama;
        $model->by_gmpurch_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_ASENG)->pegawai_nama;
		$model->by_kadiv_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO)->pegawai_nama;
		$model->by_gmopr_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_ASENG)->pegawai_nama;
		$model->by_dirut_name = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
		$modAttachment = new \app\models\TAttachment();
		if(isset($_GET['hasil_orientasi_id'])){
			$model = \app\models\THasilOrientasi::findOne($_GET['hasil_orientasi_id']);
			$model->rkt_pertahun = \app\components\DeltaFormatter::formatNumberForUserFloat($model->rkt_pertahun);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			if(!empty($model->sistem_pemuatan)){
				$sistem_pemuatan = \yii\helpers\Json::decode($model->sistem_pemuatan);
				$model->sp_langsung = $sistem_pemuatan['sp_langsung'];
				$model->sp_langsung_feet = $sistem_pemuatan['sp_langsung_feet'];
				$model->sp_estafet = $sistem_pemuatan['sp_estafet'];
				$model->sp_estafet_kendaraan = $sistem_pemuatan['sp_estafet_kendaraan'];
				$model->sp_estafet_feet = $sistem_pemuatan['sp_estafet_feet'];
				$model->sp_estafet_induk_feet = $sistem_pemuatan['sp_estafet_induk_feet'];
			}
			if(!empty($model->lama_pemuatan)){
				$lama_pemuatan = \yii\helpers\Json::decode($model->lama_pemuatan);
				$model->lp_langsung = $lama_pemuatan['lp_langsung'];
				$model->lp_langsung_hari = $lama_pemuatan['lp_langsung_hari'];
				$model->lp_estafet = $lama_pemuatan['lp_estafet'];
				$model->lp_estafet_m3 = $lama_pemuatan['lp_estafet_m3'];
				$model->lp_estafet_hari = $lama_pemuatan['lp_estafet_hari'];
			}
			if(!empty($model->jenis_alat_berat)){
				$jenis_alat_berat = \yii\helpers\Json::decode($model->jenis_alat_berat);
				$model->jab_traktor = $jenis_alat_berat['jab_traktor'];
				$model->jab_logging = $jenis_alat_berat['jab_logging'];
				$model->jab_loader = $jenis_alat_berat['jab_loader'];
				$model->jab_lainnya = $jenis_alat_berat['jab_lainnya'];
			}
			if(!empty($model->lokasi_produksi)){
				$lokasi_produksi = \yii\helpers\Json::decode($model->lokasi_produksi);
				$model->lpr_blok2tpn = $lokasi_produksi['lpr_blok2tpn'];
				$model->lpr_blok2tpn_kondisi = $lokasi_produksi['lpr_blok2tpn_kondisi'];
				$model->lpr_tpn2tpk = $lokasi_produksi['lpr_tpn2tpk'];
				$model->lpr_tpn2tpk_kondisi = $lokasi_produksi['lpr_tpn2tpk_kondisi'];
			}
			if(!empty($model->rendemen_produksi)){
				$rendemen_produksi = \yii\helpers\Json::decode($model->rendemen_produksi);
                if($rendemen_produksi != "-"){
                    $model->rp_sawnmill = $rendemen_produksi['rp_sawnmill'];
                    $model->rp_plymill = $rendemen_produksi['rp_plymill'];
                    $model->rp_face = $rendemen_produksi['rp_face'];
                    $model->rp_back = $rendemen_produksi['rp_back'];
                    $model->rp_core = $rendemen_produksi['rp_core'];
                }
			}
			if(!empty($model->target_rkt_sebelumnya)){
				$target_rkt_sebelumnya = \yii\helpers\Json::decode($model->target_rkt_sebelumnya);
                if($target_rkt_sebelumnya != "-"){
                    $model->tahun_target_rkt1 = $target_rkt_sebelumnya['tahun_target_rkt1'];
                    $model->target_rkt1 = \app\components\DeltaFormatter::formatNumberForUserFloat($target_rkt_sebelumnya['target_rkt1']);
                    $model->realisasi_rkt1 = \app\components\DeltaFormatter::formatNumberForUserFloat($target_rkt_sebelumnya['realisasi_rkt1']);
                    $model->tahun_target_rkt2 = $target_rkt_sebelumnya['tahun_target_rkt2'];
                    $model->target_rkt2 = \app\components\DeltaFormatter::formatNumberForUserFloat($target_rkt_sebelumnya['target_rkt2']);
                    $model->realisasi_rkt2 = \app\components\DeltaFormatter::formatNumberForUserFloat($target_rkt_sebelumnya['realisasi_rkt2']);
                }
			}
			
			$model->target_rkt = \app\components\DeltaFormatter::formatNumberForUserFloat($model->target_rkt);

			$model->by_kanit_name = \app\models\MPegawai::findOne($model->by_kanit)->pegawai_nama;
			$model->by_kadiv_name = \app\models\MPegawai::findOne($model->by_kadiv)->pegawai_nama;
			$model->by_gmopr_name = \app\models\MPegawai::findOne($model->by_gmopr)->pegawai_nama;
			$model->by_gmpurch_name = \app\models\MPegawai::findOne($model->by_gmpurch)->pegawai_nama;
			$model->by_dirut_name = \app\models\MPegawai::findOne($model->by_dirut)->pegawai_nama;
		}
        if( Yii::$app->request->post('THasilOrientasi')){
            $transaction = \Yii::$app->db->beginTransaction();
            	/* echo "<br>target_rkt = ".$_POST['THasilOrientasi']['target_rkt'];
            	echo "<br>tahun_target_rkt1 = ".$_POST['THasilOrientasi']['tahun_target_rkt1'];
            	echo "<br>target_rkt1 = ".$_POST['THasilOrientasi']['target_rkt1'];
            	echo "<br>tahun_target_rkt2 = ".$_POST['THasilOrientasi']['tahun_target_rkt2'];
            	echo "<br>target_rkt2 = ".$_POST['THasilOrientasi']['target_rkt2'];
            	echo "<br>jumlah_sampling_log = ".$_POST['THasilOrientasi']['jumlah_sampling_log'];
            	echo "<br>perlakuan_log_tidak_standard = ".$_POST['THasilOrientasi']['perlakuan_log_tidak_standard'];
            	echo "<br>perlakuan_log_tidak_standard_lain = ".$_POST['THasilOrientasi']['perlakuan_log_tidak_standard_lain']; */
            try {
                $success_1 = false; // t_hasil_orientasi
                $success_2 = true;  // t_hasil_orientasi_kuantitas
                $success_3 = true;  // t_hasil_orientasi_kualitas
                $success_4 = true;  // t_attachment
                $success_5 = false; // t_approval
                $success_6 = false; // t_dkg

                $model->load(\Yii::$app->request->post());
				
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeHasilOrientasi();
				}
				
				$sistem_pemuatan['sp_langsung'] = ($_POST['THasilOrientasi']['sp_langsung']=="on")?"1":"0";
				$sistem_pemuatan['sp_langsung_feet'] = $_POST['THasilOrientasi']['sp_langsung_feet'];
				$sistem_pemuatan['sp_estafet'] = ($_POST['THasilOrientasi']['sp_estafet']=="on")?"1":"0";
				$sistem_pemuatan['sp_estafet_kendaraan'] = $_POST['THasilOrientasi']['sp_estafet_kendaraan'];
				$sistem_pemuatan['sp_estafet_feet'] = $_POST['THasilOrientasi']['sp_estafet_feet'];
				$sistem_pemuatan['sp_estafet_induk_feet'] = $_POST['THasilOrientasi']['sp_estafet_induk_feet'];
				$model->sistem_pemuatan = \yii\helpers\Json::encode($sistem_pemuatan);
				
				$lama_pemuatan['lp_langsung'] = ($_POST['THasilOrientasi']['lp_langsung']=="on")?"1":"0";
				$lama_pemuatan['lp_langsung_hari'] = $_POST['THasilOrientasi']['lp_langsung_hari'];
				$lama_pemuatan['lp_estafet'] = ($_POST['THasilOrientasi']['lp_estafet']=="on")?"1":"0";
				$lama_pemuatan['lp_estafet_m3'] = $_POST['THasilOrientasi']['lp_estafet_m3'];
				$lama_pemuatan['lp_estafet_hari'] = $_POST['THasilOrientasi']['lp_estafet_hari'];
				$model->lama_pemuatan = \yii\helpers\Json::encode($lama_pemuatan);
				
				$jenis_alat_berat['jab_traktor'] = $_POST['THasilOrientasi']['jab_traktor'];
				$jenis_alat_berat['jab_logging'] = $_POST['THasilOrientasi']['jab_logging'];
				$jenis_alat_berat['jab_loader'] = $_POST['THasilOrientasi']['jab_loader'];
				$jenis_alat_berat['jab_lainnya'] = $_POST['THasilOrientasi']['jab_lainnya'];
				$model->jenis_alat_berat = \yii\helpers\Json::encode($jenis_alat_berat);
				
				$lokasi_produksi['lpr_blok2tpn'] = $_POST['THasilOrientasi']['lpr_blok2tpn'];
				$lokasi_produksi['lpr_blok2tpn_kondisi'] = $_POST['THasilOrientasi']['lpr_blok2tpn_kondisi'];
				$lokasi_produksi['lpr_tpn2tpk'] = $_POST['THasilOrientasi']['lpr_tpn2tpk'];
				$lokasi_produksi['lpr_tpn2tpk_kondisi'] = $_POST['THasilOrientasi']['lpr_tpn2tpk_kondisi'];
				$model->lokasi_produksi = \yii\helpers\Json::encode($lokasi_produksi);
                
                $target_rkt_sebelumnya['tahun_target_rkt1'] = $_POST['THasilOrientasi']['tahun_target_rkt1'];
                $target_rkt_sebelumnya['target_rkt1'] = $_POST['THasilOrientasi']['target_rkt1'];
                $target_rkt_sebelumnya['realisasi_rkt1'] = $_POST['THasilOrientasi']['realisasi_rkt1'];
                $target_rkt_sebelumnya['tahun_target_rkt2'] = $_POST['THasilOrientasi']['tahun_target_rkt2'];
                $target_rkt_sebelumnya['target_rkt2'] = $_POST['THasilOrientasi']['target_rkt2'];
                $target_rkt_sebelumnya['realisasi_rkt2'] = $_POST['THasilOrientasi']['realisasi_rkt2'];
                $model->target_rkt_sebelumnya = \yii\helpers\Json::encode($target_rkt_sebelumnya);

                if(!empty($rendemen_produksi)){
                    if($rendemen_produksi != "-"){
                        $rendemen_produksi['rp_sawnmill'] = $_POST['THasilOrientasi']['rp_sawnmill'];
                        $rendemen_produksi['rp_plymill'] = $_POST['THasilOrientasi']['rp_plymill'];
                        $rendemen_produksi['rp_face'] = $_POST['THasilOrientasi']['rp_face'];
                        $rendemen_produksi['rp_back'] = $_POST['THasilOrientasi']['rp_back'];
                        $rendemen_produksi['rp_core'] = $_POST['THasilOrientasi']['rp_core'];
                        $model->rendemen_produksi = \yii\helpers\Json::encode($rendemen_produksi);
                    }
                }else{
                    $model->rendemen_produksi = \yii\helpers\Json::encode("-");
                }
				$grader_terlibat = [];
                //echo "<pre>";
				foreach($_POST['THasilOrientasi'] as $i => $hasilorientasi){
					if(is_array($hasilorientasi)){
						$grader_terlibat[] = $hasilorientasi;
					}
				}
				if(!empty($grader_terlibat)){
					$model->grader_terlibat = \yii\helpers\Json::encode($grader_terlibat);
				}
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if((isset($_GET['edit'])) && (isset($_GET['hasil_orientasi_id']))){
							$modHOKuantitas = \app\models\THasilOrientasiKuantitas::find()->where(['hasil_orientasi_id'=>$_GET['hasil_orientasi_id']])->all();
							if(count($modHOKuantitas)>0){
								\app\models\THasilOrientasiKuantitas::deleteAll(['hasil_orientasi_id'=>$_GET['hasil_orientasi_id']]);
							}
							$modHOKualitas = \app\models\THasilOrientasiKualitas::find()->where(['hasil_orientasi_id'=>$_GET['hasil_orientasi_id']])->all();
							if(count($modHOKualitas)>0){
								\app\models\THasilOrientasiKualitas::deleteAll(['hasil_orientasi_id'=>$_GET['hasil_orientasi_id']]);
							}
						}
						foreach($_POST['THasilOrientasiKuantitas'] as $i => $kuantitas){
							if(is_numeric($i)){
								foreach($kuantitas as $ii => $kuantiti){
									if((is_array($kuantiti))&&($ii!='total')){
										$modHOKuantitas = new \app\models\THasilOrientasiKuantitas();
										$modHOKuantitas->hasil_orientasi_id = $model->hasil_orientasi_id;
										$modHOKuantitas->kayu_id = $kuantitas['kayu_id'];
										$modHOKuantitas->diameter_cm = $ii;
										$modHOKuantitas->qty_batang = $kuantiti['qty_batang'];
										$modHOKuantitas->qty_m3 = $kuantiti['qty_m3'];
										$modHOKuantitas->keterangan = $kuantitas['keterangan'];
										if($modHOKuantitas->validate()){
											if($modHOKuantitas->save()){
												$success_2 &= true;
											}else{
												$success_2 = false;
											}
										}else{
											$success_2 = false;
											$errmsg = $modHOKuantitas->errors;
										}
									}
								}
							}
						}
						foreach($_POST['THasilOrientasiKualitas'] as $i => $kualitas){
							if(is_numeric($i)){
								$modHOKualitas = new \app\models\THasilOrientasiKualitas();
								$modHOKualitas->attributes = $kualitas;
								$modHOKualitas->hasil_orientasi_id = $model->hasil_orientasi_id;
								$modHOKualitas->bekas_pilih = ($modHOKualitas->bekas_pilih=="on")?"1":"0";
								//$usia_tebang['ut_qty']= $kualitas['ut_qty'];
								//$usia_tebang['ut_satuan']= $kualitas['ut_satuan'];
								$usia_tebang['ut_13']= $kualitas['ut_13'];
								$usia_tebang['ut_45']= $kualitas['ut_45'];
								$usia_tebang['ut_68']= $kualitas['ut_68'];
								$usia_tebang['ut_99']= $kualitas['ut_99'];
								$modHOKualitas->usia_tebang = \yii\helpers\Json::encode($usia_tebang);
								//$kondisi_global['kg_sehat']= $kualitas['kg_sehat'];
								//$kondisi_global['kg_rusak']= $kualitas['kg_rusak'];
								$kondisi_global['kg_gubal']= $kualitas['kg_gubal'];
								$modHOKualitas->kondisi_global = \yii\helpers\Json::encode($kondisi_global);
								$kondisi_total['kt_gr']= $kualitas['kt_gr'];
								$kondisi_total['kt_pecah']= $kualitas['kt_pecah'];
								$modHOKualitas->kondisi_total = \yii\helpers\Json::encode($kondisi_total);
								if($modHOKualitas->validate()){
									if($modHOKualitas->save()){
										$success_3 &= true;
									}else{
										$success_3 = false;
									}
								}else{
									$success_3 = false;
									$errmsg = $modHOKualitas->errors;
								}
							}
						}
						
						$dir_path = Yii::$app->basePath.'/web/uploads/pur/hasilorientasi';
						if(isset($_FILES['TAttachment'])){
							$files = [];
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file1');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file2');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file3');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file4');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file5');
							foreach($files as $i => $file){
								if(!empty($file)){
									$modAttachment = new \app\models\TAttachment();
									$modAttachment->reff_no = $model->kode;
									$modAttachment->file_type = $file->type;
									$modAttachment->file_ext = $file->extension;
									$modAttachment->file_size = $file->size;
									$modAttachment->dir_path = $dir_path;
									$modAttachment->seq = ($i+1);
									$randomstring_attch = Yii::$app->getSecurity()->generateRandomString(4);
									if(!is_dir($dir_path)){ mkdir($dir_path); }
									$file_path = date('Ymd_His').'-attch-'.$randomstring_attch.'.'  . $file->extension;
									$file->saveAs($dir_path.'/'.$file_path);
									$modAttachment->file_name = $file_path;
									if($modAttachment->validate()){
										if($modAttachment->save()){
											$success_4 &= true;
										}else{
											$success_4 = false;
										}
									}else{
										$success_4 = false;
										$errmsg = $modAttachment->errors;
									}
								}
							}
						}
						
						// START Create Approval
						$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
						if(count($modelApproval)>0){ // edit mode
							if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
								$success_5 = $this->saveApproval($model);
							}
							$success_5 = true;
						}else{ // insert mode
							$success_5 = $this->saveApproval($model);
						}
						// END Create Approval

                        // insert value hasil_orientasi_id ke tabel t_dkg
                        foreach($_POST['THasilOrientasi'] as $i => $hasilorientasi){
                            if(is_numeric($i)){
                                $dkg_id = $hasilorientasi['gt_dkg_id'];
                                $modDkg = \app\models\TDkg::findOne($dkg_id);
                                $modDkg->hasil_orientasi_id = $model->hasil_orientasi_id;
                                if($modDkg->validate()){
                                    if ($modDkg->update() !== false ) {
                                        $success_6 = true;
                                    } else {
                                        $success_6 = false;
                                    }
                                } else {
									$success_6 = false;
									$errmsg = $modDkg->errors;
                                }
                            }
                        }
                    }
                }
				
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>3";
//				print_r($success_3);
//				echo "<pre>4";
//				print_r($success_4);
//				echo "<pre>5";
//				print_r($success_5);
//				exit;
                
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5 && $success_6) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'hasil_orientasi_id'=>$model->hasil_orientasi_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?(implode(",", array_values($errmsg)[0])):Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modAttachment'=>$modAttachment]);
	}
	
	public function saveApproval($model){
		$success = true;
//		$modelApproval = new \app\models\TApproval();
//		$modelApproval->assigned_to = $model->by_kanit;
//		$modelApproval->reff_no = $model->kode;
//		$modelApproval->tanggal_berkas = $model->tanggal;
//		$modelApproval->level = 1;
//		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
//		$success &= $modelApproval->createApproval();
		
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->by_gmpurch;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 2;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();
		
//		$modelApproval = new \app\models\TApproval();
//		$modelApproval->assigned_to = $model->by_kadiv;
//		$modelApproval->reff_no = $model->kode;
//		$modelApproval->tanggal_berkas = $model->tanggal;
//		$modelApproval->level = 3;
//		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
//		$success &= $modelApproval->createApproval();
//		
//		$modelApproval = new \app\models\TApproval();
//		$modelApproval->assigned_to = $model->by_gmopr;
//		$modelApproval->reff_no = $model->kode;
//		$modelApproval->tanggal_berkas = $model->tanggal;
//		$modelApproval->level = 3;
//		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
//		$success &= $modelApproval->createApproval();
		
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->by_dirut;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 4;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();
		
		return $success;
	}
	
	public function actionAddNewKuantitas(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\THasilOrientasiKuantitas();
			$last_tr = []; parse_str(\Yii::$app->request->post('last_tr'),$last_tr);
			if(!empty($last_tr)){
				foreach($last_tr['THasilOrientasiKuantitas'] as $qwe){
					$last_tr = $qwe;
				}
			}
            $data['html'] = $this->renderPartial('_itemKuantitas',['model'=>$model,'last_tr'=>$last_tr]);
            return $this->asJson($data);
        }
    }

	public function actionAddNewKualitas(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\THasilOrientasiKualitas();
			$last_tr = []; parse_str(\Yii::$app->request->post('last_tr'),$last_tr);
			if(!empty($last_tr)){
				foreach($last_tr['THasilOrientasiKualitas'] as $qwe){
					$last_tr = $qwe;
				}
			}
            $data['html'] = $this->renderPartial('_itemKualitas',['model'=>$model,'last_tr'=>$last_tr]);
            return $this->asJson($data);
        }
    }
    
	public function actionAddGrader(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\THasilOrientasi();
			$last_tr = []; 
            parse_str(\Yii::$app->request->post('last_tr'),$last_tr);
			if(!empty($last_tr)){
				foreach($last_tr['THasilOrientasiKualitas'] as $qwe){
					$last_tr = $qwe;
				}
			}
            $data['html'] = $this->renderPartial('_itemGrader',['model'=>$model,'last_tr'=>$last_tr]);
            return $this->asJson($data);
        }
    }
	
	public function actionMasterDkg($tr_seq=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-master'){
				$param['table'] = \app\models\TDkg::tableName();
				$param['pk']= $param['table'].".".\app\models\TDkg::primaryKey()[0];
				$param['column'] = [$param['table'].'.dkg_id','kode','tipe','tanggal','graderlog_nm','wilayah_dinas_nama'];
				$param['join'] = "JOIN m_graderlog ON m_graderlog.graderlog_id = t_dkg.graderlog_id
								  JOIN m_wilayah_dinas ON m_wilayah_dinas.wilayah_dinas_id = t_dkg.wilayah_dinas_id";
				$param['where'] = "status = '". \app\models\TDkg::AKTIF_DINAS."'  ";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('masterDkg',['tr_seq'=>$tr_seq]);
		}
	}
	
	public function actionSetGrader(){
		if(\Yii::$app->request->isAjax){
			$data = [];
			$dkg_id = \Yii::$app->request->post('dkg_id');
			if(!empty($dkg_id)){
				$modDkg = \app\models\TDkg::find()->where(['dkg_id'=>$dkg_id,'approval_status'=>'APPROVED'])->one();
				if(!empty($modDkg)){
					$data = $modDkg->attributes;
					$data['graderlog_nm'] = $modDkg->graderlog->graderlog_nm;
					$data['wilayah_dinas_nama'] = $modDkg->wilayahDinas->wilayah_dinas_nama;
				}
			}
			return $this->asJson($data);
		}
	}
	
	function actionGetItemsById(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $edit = Yii::$app->request->post('edit');
			$model = \app\models\THasilOrientasi::findOne($id);
			$modHOKuantitas = [];
            $data = [];
            if(!empty($id)){
                $modHOKuantitas = \app\models\THasilOrientasiKuantitas::find()
								->select("hasil_orientasi_id, kayu_id, keterangan")
								->groupBy("hasil_orientasi_id, kayu_id, keterangan")
								->where(['hasil_orientasi_id'=>$id])->all();
                $graders = \yii\helpers\Json::decode($model->grader_terlibat);
                $attachments = \app\models\TAttachment::find()->where(['reff_no'=>$model->kode])->orderBy("seq ASC")->all();
            }
			$data['model'] = $model->attributes;
            $data['html_kuantitas'] = ''; $data['html_kualitas'] = ''; $data['html_grader']="";
            if(count($modHOKuantitas)>0){
                foreach($modHOKuantitas as $i => $kuantitas){
                    $data['html_kuantitas'] .= $this->renderPartial('_itemKuantitas',['model'=>$kuantitas,'i'=>$i,'edit'=>$edit]);
                    $modHOKualitas = \app\models\THasilOrientasiKualitas::find()->where(['hasil_orientasi_id'=>$id,'kayu_id'=>$kuantitas->kayu_id])->one();
                    $data['html_kualitas'] .= $this->renderPartial('_itemKualitas',['model'=>$modHOKualitas,'i'=>$i,'edit'=>$edit]);
                }
            }
            if(count($graders)>0){
                foreach($graders as $i => $grader){
					$model->graderlog_id = $grader['graderlog_id'];
					$model->gt_dkg_id = $grader['gt_dkg_id'];
					$model->gt_tipe_dinas = $grader['gt_tipe_dinas'];
					$model->gt_nama_grader = $grader['gt_nama_grader'];
					$model->gt_wilayah_dinas = $grader['gt_wilayah_dinas'];
                    if(!empty($edit) || !empty($model->hasil_orientasi_id)){
                        $modDkg = \app\models\TDkg::findOne($model->gt_dkg_id);
                        if(!empty($modDkg)){
                            $model->gt_dkg_id = $modDkg->dkg_id;
                            $model->gt_dkg_kode = $modDkg->kode;
                        }
                    }
                    $data['html_grader'] .= $this->renderPartial('_itemGrader',['model'=>$model,'i'=>$i,'edit'=>$edit]);
                }
            }
            if(count($attachments)>0){
                foreach($attachments as $i => $attachment){
                    $data['attch'][] = $attachment->attributes;
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\THasilOrientasi::tableName();
				$param['pk']= $param['table'].".". \app\models\THasilOrientasi::primaryKey()[0];
				$param['column'] = [$param['table'].'.hasil_orientasi_id',
									$param['table'].'.kode',
									$param['table'].'.tanggal',
									$param['table'].'.nama_iuphhk',
                                                                        $param['table'].'.nama_ipk',
									$param['table'].'.lokasi_muat',
									$param['table'].'.rkt_pertahun',
									'pegawai4.pegawai_nama AS by_gmpurch', // 6
									'pegawai5.pegawai_nama AS by_dirut', // 7
									'(SELECT status FROM t_approval WHERE reff_no = t_hasil_orientasi.kode AND  assigned_to = t_hasil_orientasi.by_gmpurch group by 1) AS by_gmpurch_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_hasil_orientasi.kode AND  assigned_to = t_hasil_orientasi.by_dirut group by 1) AS by_dirut_status',
									$param['table'].'.cancel_transaksi_id',
									];
				$param['join']= ['
								JOIN m_pegawai AS pegawai4 ON pegawai4.pegawai_id = '.$param['table'].'.by_gmpurch 
								JOIN m_pegawai AS pegawai5 ON pegawai5.pegawai_id = '.$param['table'].'.by_dirut 
								'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionDeleteAttch($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
            $fileno = Yii::$app->request->get('fileno');
			$model = \app\models\TAttachment::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                        if( unlink($model->dir_path.'/'.$model->file_name) && $model->delete()){
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data Gagal dihapus');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                            $data['callback'] = "setNormalPickAttch({$fileno});";
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
			return $this->renderAjax('_deleteAttch',['id'=>$id,'tableid'=>$tableid,"actionname"=>"DeleteAttch",'fileno'=>$fileno]);
		}
	}
	
}
