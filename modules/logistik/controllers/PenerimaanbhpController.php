<?php

namespace app\modules\logistik\controllers;

use app\components\Params;
use Yii;
use app\controllers\DeltaBaseController;

class PenerimaanbhpController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TTerimaBhp();
        $model->terimabhp_kode = 'Auto Generate';
        $model->pegawaipenerima = Yii::$app->user->identity->pegawai_id;
        $model->tglterima = date('d/m/Y');
		$modDetail = [];

		if(isset($_GET['terima_bhp_id'])){
			$model = \app\models\TTerimaBhp::findOne($_GET['terima_bhp_id']);
			$terimabhp_kode = $model->terimabhp_kode;
            $model->tglterima = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tglterima);
			$modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$model->terima_bhp_id])->all();
			$sql_status_approval = "select status from t_approval where reff_no = '".$terimabhp_kode."' ";
			$status_approval = Yii::$app->db->createCommand($sql_status_approval)->queryScalar();
        }
		
		if( Yii::$app->request->post('TTerimaBhp')){
//            echo "<pre>";
//            print_r(Yii::$app->request->post('TTerimaBhp'));die;
			$transaction = \Yii::$app->db->beginTransaction();

			$tanggal_jam_checker = $_POST['TTerimaBhp']['tanggal_jam_checker'].":00";
			
			$model->tanggal_jam_checker = \app\components\DeltaFormatter::formatDateTimesForDb($tanggal_jam_checker);

			try {
                $success_1 = false; // insert t_terima_bhp
                $success_2 = true;  // insert t_terima_bhp_detail
                $success_3 = true;  // insert stock persediaan h_persediaan_bhp
                $success_4 = true;  // update harga m_brg_bhp
                $success_5 = true;  // jurnal								--> skip
                $success_6 = false; // update reff table (t_spo / t_spp)
                $success_7 = true;  // update map_spp_detail_reff
                $success_8 = true;  // update t_spp							--> skip
                $success_9 = false; // update status_approval t_terima_bhp
				$success_10 = true;  // update tmp_spp_spo_spl_tbp
                $model->load(\Yii::$app->request->post());
                $model->terimabhp_kode = \app\components\DeltaGenerator::kodeTerimaBhp();
                $model->terimabhp_status = '-';	
                $model->tanggal_jam_checker = \app\components\DeltaFormatter::formatDateTimesForDb($tanggal_jam_checker);

                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						
						// update reff table (t_spo / t_spp)
						if(!empty($model->spo_id)){
							$modSpo = \app\models\TSpo::findOne($model->spo_id);
							$modSpo->terima_bhp_id = $model->terima_bhp_id;
							if($modSpo->validate()){
								$success_6 = $modSpo->save();
							}
							$reff_no = $modSpo->spo_kode;
							$reff_select = "spo_id";
							$reff_selectId = $model->spo_id;
						}
						if(!empty($model->spl_id)){
							$modSpl = \app\models\TSpl::findOne($model->spl_id);
							$modSpl->terima_bhp_id = $model->terima_bhp_id;
							if($modSpl->validate()){
								$success_6 = $modSpl->save();
							}
							$reff_no = $modSpl->spl_kode;
							$reff_select = "spl_id";
							$reff_selectId = $model->spl_id;
						}
						// end update reff table (t_spo / t_spp)
						
                        if( (isset($_POST['TTerimaBhpDetail'])) && (count($_POST['TTerimaBhpDetail'])>0) ){
                            foreach($_POST['TTerimaBhpDetail'] as $i => $detail){
                                $modDetail = new \app\models\TTerimaBhpDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->terima_bhp_id = $model->terima_bhp_id;
								if(empty($modDetail->suplier_id)){
									$modDetail->suplier_id = $model->suplier_id;
								}
                                if($modDetail->validate()){
                                    if($modDetail->save()){
                                        $success_2 &= true;
										
										/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
										// CEK HITUNGAN TANGGAL CHECKER DAN TANGGAL INPUT HARI INI
										// hari ini
										$today = date('Y-m-d H:i:s');
										$hari_ini = explode(' ', $today);
										$tanggal = $hari_ini[0];
										$jam = $hari_ini[1];

										// hari terima barang
										$tanggal_jam_checkers = explode(' ',$model->tanggal_jam_checker);										
										$tanggal_checker = $tanggal_jam_checkers[0];
										$jam_checker = $tanggal_jam_checkers[1];
										$hari_checker = date('w	', strtotime($tanggal_checker));
										$j_checker = date('H', strtotime($jam_checker));
										$m_checker = date('i', strtotime($jam_checker));

										$start = new \DateTime(date($tanggal_checker));
										$end = new \DateTime(date($tanggal));
										$days = $start->diff($end, true)->days;
										$jumlah_minggu = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

										$user_id = Yii::$app->user->identity->id;

										$date = strtotime($today);
										$jam_sekarang =  date('H', $date);
                                        $menit_sekarang =  date('i', $date);
                                        
										// hitung selisih tanggal checker dan tanggal input hari ini
										$tanggal_awal = strtotime($tanggal_checker);
                                        $tanggal_akhir = strtotime($tanggal);
                                        
                                        // hitung hari libur
                                        $sql_hari = "select count(tanggal) as jumlah_hari ". 
                                                        "   from view_hari_libur_99 ". 
                                                        "   where tanggal between '".$tanggal_checker."' ".
                                                        "   and '".$tanggal."' ". 
                                                        "   ";
                                        $hari_libur = Yii::$app->db->createCommand($sql_hari)->queryScalar();

                                        // hitung jumlah hari, hari libur jangan dihitung
										$beda_waktu = abs($tanggal_akhir - $tanggal_awal);
										$jumlah_hari = $beda_waktu/86400;  // 1 hari = 86400 detik
										$jumlah_hari = intval($jumlah_hari) - $hari_libur;

										// jika barang masuk hari sabtu diatas jam 13, hari minggu nggak usah dihitung cuy
										if ($hari_checker == 6 &&  $j_checker >= 13 && $m_checker > 0 ) {
											$hitung_hari =  $jumlah_hari - $jumlah_minggu;
										} else if ($hari_checker == 0) {
											$hari_minggu = 1;
											$hitung_hari = $jumlah_hari - $jumlah_minggu + $hari_minggu;
										} else {
											$hitung_hari =  $jumlah_hari - $jumlah_minggu;
										}
										
										/*echo "<hr>tanggal_checker : ".$tanggal_checker;
										echo "<br><br>jam_checker : ".$j_checker.":".$m_checker;					
										echo "<hr>tangal_input : ".$today;
										echo "<br><br>jam_input : ".$jam_sekarang.":".$menit_sekarang;
										echo "<hr>jumlah_hari : ".$jumlah_hari;
										echo "<br><br>jumlah_minggu : ".$jumlah_minggu;
										echo "<br><br>hitung_hari : ".$hitung_hari;
										echo "<hr>";*/
										

										/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
										// BUAT APPROVAL JIKA BARANG DITERIMA KEMAREN TAPI INPUTNYA KE CIS DIATAS JAM 10
										// Menghitung hari
										if ($hitung_hari >= 2) {
											$minta_approve = 1;
											$pos = "1";
										} else if ($hitung_hari >= 1 && ($jam_sekarang >= 10 && $menit_sekarang > 01)) {
											$minta_approve = 1;
											$pos = "2";
										} else {
											$minta_approve = 0;
											$pos = "4";
										}

										/*echo "<br><br>hitung_hari = ".$hitung_hari;
										echo "<br><br>jam_sekarang = ".$jam_sekarang;
										echo "<br><br>menit_sekarang = ".$menit_sekarang;
										
										echo "<br><br>minta approve = ".$minta_approve;
										echo "<br><br>pos = ".$pos;*/
										
										if ($minta_approve > 0) {

											// approver 1 dan 2 satu level ya
											// jabatan 		: nama					: pegawai_id	: user_id
											// kadiv hrd ga : andrian argasasmita 	: 124 			: 154
											// kadiv acc	: nowo eko yulianto 	: 58			: 104
											$approver = array(124,58);
											foreach ($approver as $key) {
												//cek sek
												$sql_cek = "select count(*) from t_approval ".
																"	where assigned_to = '".$key."' ".
																"	and reff_no = '".$model->terimabhp_kode."' ".
																"	and tanggal_berkas = '".$tanggal."' ".
																"	and level = 1 ".
																"	and active = 'true' ".
																"	";
												$numrows = Yii::$app->db->createCommand($sql_cek)->queryScalar();

												if ($numrows < 1) {
													$sql = "insert into t_approval ".
																"	(assigned_to, reff_no, tanggal_berkas, level, status, active, created_at, created_by, updated_at, updated_by) ".
																"	values ".
																"	($key, '".$model->terimabhp_kode."', '".$tanggal."', 1, 'Not Confirmed', 'true', '".$today."',$user_id, '".$today."',$user_id) ".
																"	";
													$query = Yii::$app->db->createCommand($sql)->execute();
												}
											}

											$success_3 = TRUE;
											$success_4 = TRUE;
											$success_5 = TRUE;
											$success_6 = TRUE;
											$success_7 = TRUE;
											$success_8 = TRUE;

											// update status_approval t_terima_bhp jadi ALLOWED
											$sql_update = "update t_terima_bhp set status_approval = 'Not Confirmed' where terima_bhp_id = '".$model->terima_bhp_id."' ";
											$success_9 = Yii::$app->db->createCommand($sql_update)->execute();

											//echo "<br><b>minta approve</b>";
	
										} else {

											/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
											// LOLOSKAN SAJA KALAU BARANG DITERIMA DIHARI ITU JUGA
											// Start Proses Update Stock
											$modDetail->qty_in = $modDetail->terimabhpd_qty;
											$modDetail->qty_out = 0;
											$success_3 &= \app\models\HPersediaanBhp::updateStokPersediaan($modDetail,$model->terimabhp_kode,$modDetail->terima_bhpd_id,$model->tglterima);
											// End Proses Update Stock

											//echo "<br><br>bablas";

											// Start Proses Update Harga BHP
											if(!empty($model->spo_id)){
												if($model->spo->spo_is_pkp){
													if($model->spo->spo_is_ppn){
														$harga_pokok = $modDetail->terimabhpd_harga;
														$nominal_ppn = (($modDetail->terimabhpd_harga * 1.1) - $modDetail->terimabhpd_harga);
														$harga = $harga_pokok + $nominal_ppn;
														$include_ppn = TRUE;
													}else{
														$harga_pokok = $modDetail->terimabhpd_harga;
														// jika tanggal spo sebelum 2 januari 2025 maka ppn 11%
														if($model->spo->spo_tanggal < '2025-01-02'){
															$nominal_ppn = $modDetail->terimabhpd_harga * 0.11;
														} else {
															$nominal_ppn = $modDetail->terimabhpd_harga * Params::DEFAULT_PPN;
														}
														$harga = $modDetail->terimabhpd_harga;
														$include_ppn = FALSE;
													}
												}else{
													$harga_pokok = $modDetail->terimabhpd_harga;
													$nominal_ppn = 0;
													$harga = $harga_pokok;
													$include_ppn = FALSE;
												}
											}else{
												$harga_pokok = $modDetail->terimabhpd_harga;
												$nominal_ppn = 0;
												$harga = $harga_pokok;
												$include_ppn = FALSE;
											}
											$success_4 &= \app\models\MBrgBhp::updateHargaBhp($modDetail->bhp_id,$harga_pokok,$nominal_ppn,$harga,$include_ppn);
											// End Proses Update Harga BHP 

											// update map_spp_detail_reff
											if(!empty($model->spo_id)){ $reff_detail_id = $detail['spod_id']; }
											if(!empty($model->spl_id)){ $reff_detail_id = $detail['spld_id']; }
											$modMap = \app\models\MapSppDetailReff::find()->where("reff_no = '".$reff_no."' AND reff_detail_id = ".$reff_detail_id)->one();
											if(!empty($modMap)){
												if($modMap->updateAll(['terima_bhpd_id'=>$modDetail->terima_bhpd_id],"reff_no = '".$reff_no."' AND reff_detail_id = ".$reff_detail_id)){
													$success_7 &= true;
												}else{
													$success_7 &= false;
												}
											}
											// update tmp_spp_spo_spl_tbp
											$mapTmpReffno = \app\models\TmpSppSpoSplTbp::findOne(['sppd_id'=>$modMap['sppd_id']]);
											$sq1 = "SELECT 
														json_agg(json_build_object('terimabhp_kode', terimabhp_kode, 'terima_bhpd_id', terima_bhpd_id)) as tbp,                                                    
														string_agg(terima_bhpd_id::text, ',') as tbpdid
													FROM t_terima_bhp_detail 
													JOIN t_terima_bhp on t_terima_bhp.terima_bhp_id = t_terima_bhp_detail.terima_bhp_id
													WHERE $reff_select in($reff_selectId) AND bhp_id = $modDetail->bhp_id AND t_terima_bhp.cancel_transaksi_id is null";
											$modsReffno = \Yii::$app->db->createCommand($sq1)->queryOne(); 
											$mapTmpReffno->terima_bhpd_id = $modsReffno['tbp'];	
											// echo"<prev>";print_r($mapTmpReffno->terima_bhpd_id);echo"</prev>";
											$sq2 = "select sum(terimabhpd_qty) as jmlterima from t_terima_bhp_detail where terima_bhpd_id in( $modsReffno[tbpdid] )";
											$modsReffnoSum = \Yii::$app->db->createCommand($sq2)->queryOne();
											$mapTmpReffno->terimabhpd_qty = $modsReffnoSum['jmlterima'];
											// echo"<prev>";print_r($mapTmpReffno->terimabhpd_qty);echo"</prev>";
											if ($mapTmpReffno->validate()) {
												// print_r($mapTmpReffno->errors);
												$success_10 &= $mapTmpReffno->save();	
											}else{
												$success_10 &= false;
											}

											$success_8 = true;

											// update status_approval t_terima_bhp jadi ALLOWED
											$sql_update = "update t_terima_bhp set status_approval = 'ALLOWED' where terima_bhp_id = '".$model->terima_bhp_id."' ";
											$success_9 = Yii::$app->db->createCommand($sql_update)->execute();

											// end update map_spp_detail_reff
											// EO LOLOSKAN SAJA KALAU BARANG DITERIMA DIHARI ITU JUGA
											/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */

										}

                                    }else{
                                        $success_2 &= false;
                                    }
                                }else{
									$success_2 &= false;
								}
                            }
                        }else{
                            $success_2 = false;
                            Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                        }
                    }
                }else{
					$success_1 = false;
				}

				//echo "<hr>sukses 1 = ".$success_1."<br>sukses 2 = ".$success_2."<br>sukses 3 = ".$success_3."<br>sukses 4 = ".$success_4."<br>sukses 5 = ".$success_5."<br>sukses 6 = ".$success_6."<br>sukses 7 = ".$success_7."<br>sukses 8 = ".$success_8;

				/* ================ START PROSES JURNAL AKUNTANSI  ========= */
/*				if(!empty($model->spo_id)){
//					$modTerimaDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$model->terima_bhp_id])->all();
//					$total['subtotal_atk'] = 0; $total['subtotal_rt'] = 0; $total['subtotal_cetakan'] = 0; $total['subtotal_bp'] = 0;
//					$total['ppn_atk'] = 0; $total['ppn_rt'] = 0; $total['ppn_cetakan'] = 0; $total['ppn_bp'] = 0;
//					if(!empty($model->spo_id)){
//						$is_pkp = (($model->spo->spo_is_pkp)?"PPN":"NON-PPN");
//						$reff_no = $model->spo->spo_kode;
//					}else{
//						$is_pkp = "NON-PPN";
//						$reff_no = $model->spl->spl_kode;
//					}
//					$modTerimaDetailGroup = array();
//					foreach($modTerimaDetail as $ii => $detailterima){
//						$id = $detailterima->bhp->bhp_group;
//						if (isset($modTerimaDetailGroup[$id])) {
//							$modTerimaDetailGroup[$id][] = $detailterima->attributes;
//						}else{
//							$modTerimaDetailGroup[$id] = array($detailterima->attributes);
//						}
//					}
//					foreach($modTerimaDetailGroup as $iv => $detailed){
//						$subtotal = 0; $ppn = 0;
//						foreach($detailed as $totalkelbrg){
//							$subtotal += ($totalkelbrg['terimabhpd_qty'] * $totalkelbrg['terimabhpd_harga']);
//							$ppn += ($totalkelbrg['terimabhpd_qty'] * $totalkelbrg['terimabhpd_harga']) * 0.1;
//						}
//						$modAcctSetup = \app\models\MAcctSetup::findByAcctKeyDefaultValue($iv,$is_pkp);
//						if(!empty($modAcctSetup)){
//							$id_transaksi = $modAcctSetup->acct_setup_id;
//							if(!empty($modAcctSetup)>0){
//								$modAuto = \app\models\MAcctAuto::find()->where(['acct_setup_id'=>$id_transaksi])->all();
//								if(count($modAuto)>0){
//									foreach($modAuto as $v => $rek){
//										$modJurnal = new \app\models\TAcctJurnal();
//										$modJurnal->reff_no = $reff_no;
//										$modJurnal->memo = $modAcctSetup->acct_setup_nm;
//										$modJurnal->acct_id = $rek->acct_id;
//										switch ($modJurnal->acct_id){
//											case \app\components\Params::ACCT_REKENING_DEBET_ATK:
//												$modJurnal->debet = $subtotal;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_DEBET_RT:
//												$modJurnal->debet = $subtotal;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_DEBET_CETAKAN:
//												$modJurnal->debet = $subtotal;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_DEBET_BP_BOP:
//												$modJurnal->debet = $subtotal;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_DEBET_PPN_BELUM_FAKTUR:
//												$modJurnal->debet = $ppn;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_DEBET_PPN_MASUKAN:
//												$modJurnal->debet = $ppn;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_KREDIT_PEMBAYARAN_BP:
//												$modJurnal->debet = 0;
//												$modJurnal->kredit = $subtotal + $ppn;
//												break;
//										}
//										$success_5 &= $modJurnal->autoInsertJurnal();
//									}
//								}
//							}
//						}else{
//							$success_5 &= FALSE;
//							$transaction->rollback();
//							Yii::$app->session->setFlash('error', Yii::t('app', 'Setup Jurnal '.$iv.' Tidak ditemukan'));
//						}
//					}	
//				}
*/	
					// echo "<pre>1";
					// print_r($success_1);
					// echo "<pre>2";
					// print_r($success_2);
					// echo "<pre>3";
					// print_r($success_3);
					// echo "<pre>4";
					// print_r($success_4);
					// echo "<pre>5";
					// print_r($success_5);
					// echo "<pre>6";
					// print_r($success_6);
					// echo "<pre>7";
					// print_r($success_7);
					// echo "<pre>8";
					// print_r($success_8);
					// echo "<pre>9";
					// print_r($success_9);
					// echo "<pre>10";
					// print_r($success_10);					
					// exit;
					// && $success_10
				/* ================ END PROSES JURNAL AKUNTANSI ========= */

				// 
				if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5 && $success_6 && $success_7 && $success_8 && $success_9) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Penerimaan Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'terima_bhp_id'=>$model->terima_bhp_id]);
                    /*$transaction->rollback();
					if(empty(Yii::$app->session->getFlash('error'))){
						//Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
						$pesan = "sukses 1 = ".$success_1.". sukses 2 = ".$success_2.". sukses 3 = ".$success_3.". sukses 4 = ".$success_4.". sukses 5 = ".$success_5.". sukses 6 = ".$success_6.". sukses 7 = ".$success_7.". sukses 8 = ".$success_8;
						Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', $pesan));
					}*/
                } else {
                    $transaction->rollback();
					if(empty(Yii::$app->session->getFlash('error'))){
						//Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
						$pesan = "sukses 1 = ".$success_1.". sukses 2 = ".$success_2.". sukses 3 = ".$success_3.
									". sukses 4 = ".$success_4.". sukses 5 = ".$success_5.". sukses 6 = ".$success_6.
									". sukses 7 = ".$success_7.". sukses 8 = ".$success_8.". sukses 9 = ".$success_9.". sukses 10 = ".$success_10;
						Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', $pesan));
					}
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		isset($status_approval) ? $status_approval = $status_approval : $status_approval = '';
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail, 'status_approval'=>$status_approval]);
	}
	
	public function actionGetItemDariSPO(){
            if(\Yii::$app->request->isAjax){
                $spo_id = Yii::$app->request->post('spo_id');
                $terima_bhp_id = Yii::$app->request->post('terima_bhp_id');
                $data = [];
                $data['html'] = '';
                $disabled = false;
            if(!empty($spo_id)){
                $modSpo = \app\models\TSpo::findOne($spo_id);
                $modDetailSpo = \app\models\TSpoDetail::find()->where(['spo_id'=>$spo_id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->all();
				$model = new \app\models\TTerimaBhp();
				if(!empty($modSpo->terima_bhp_id)){
					$modTBP = \app\models\TTerimaBhp::findOne($modSpo->terima_bhp_id);
					$data['tbp'] = $modTBP->attributes;
				}

                if(count($modDetailSpo)>0){
                    foreach($modDetailSpo as $i => $spo){
						$modelDetail = new \app\models\TTerimaBhpDetail();
						if(!empty($terima_bhp_id)){

							$sql_status_approval = "select status_approval from t_terima_bhp where terima_bhp_id = '".$terima_bhp_id."' ";
							$status_approval = Yii::$app->db->createCommand($sql_status_approval)->queryScalar();
			
							$modelDetail = \app\models\TTerimaBhpDetail::findOne(['terima_bhp_id'=>$terima_bhp_id,'bhp_id'=>$spo->bhp_id]);
							if(count($modelDetail)>0){
								$disabled = true;
								$modelDetail->terimabhpd_harga = $modelDetail->terimabhpd_harga;
								$modelDetail->terimabhpd_diskon = 0;
								$modelDetail->subtotal = $modelDetail->terimabhpd_qty * $modelDetail->terimabhpd_harga;
								$modelDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUser($modelDetail->subtotal,2);
								$modelDetail->terimabhpd_harga = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->terimabhpd_harga);
								$modelDetail->terimabhpd_harga_display = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->terimabhpd_harga);
								$modelDetail->subtotal_display = \app\components\DeltaFormatter::formatNumberForUser($modelDetail->subtotal,2);
								$modelDetail->terimabhpd_keterangan = $modelDetail->terimabhpd_keterangan;
								$modelDetail->npwp = !empty($modelDetail->suplier_id)?$modelDetail->suplier->suplier_npwp:"";
								$data['html'] .= $this->renderPartial('_item',['modelDetail'=>$modelDetail,'i'=>$i,'disabled'=>$disabled,'qty_po'=>$spo->spod_qty]);
								//$data['html'] .= $this->renderPartial('_item',['modelDetail'=>$modelDetail,'i'=>$i,'disabled'=>$disabled,'qty_po'=>$spo->spod_qty,'status_approval'=>$status_approval]);
								$data['modSpo'] = $modSpo->attributes;
								$data['modSpo']['name_en'] = $modSpo->defaultValue->name_en;
							}
						}else{
							$modelDetail->attributes = $spo->attributes;
							$modelDetail->spod_id = $spo->spod_id;
//							$modelDetail->terimabhpd_qty = $spo->spod_qty;
							$modelDetail->terimabhpd_qty = 0;
							$modelDetail->terimabhpd_qty_old = $spo->spod_qty;
							$modelDetail->terimabhpd_harga = $spo->spod_harga;
							$modelDetail->terimabhpd_diskon = 0;
							$modelDetail->subtotal = $modelDetail->terimabhpd_qty * $modelDetail->terimabhpd_harga;
							$modelDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->subtotal,2);
							$modelDetail->terimabhpd_harga = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->terimabhpd_harga);
							$modelDetail->terimabhpd_harga_display = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->terimabhpd_harga);
							$modelDetail->subtotal_display = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->subtotal,2);
							$modelDetail->terimabhpd_keterangan = $spo->spod_keterangan;
							$modelDetail->pph_peritem = 0;
							$status_approval = '';
							$data['html'] .= $this->renderPartial('_item',['modelDetail'=>$modelDetail,'i'=>$i,'disabled'=>$disabled,'qty_po'=>$spo->spod_qty]);
							//$data['html'] .= $this->renderPartial('_item',['modelDetail'=>$modelDetail,'i'=>$i,'disabled'=>$disabled,'qty_po'=>$spo->spod_qty,'status_approval'=>$status_approval]);
							$data['modSpo'] = $modSpo->attributes;
							$data['modSpo']['name_en'] = $modSpo->defaultValue->name_en;
						}
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionGetItemDariSPL(){
		if(\Yii::$app->request->isAjax){
            $spl_id = Yii::$app->request->post('spl_id');
            $data = [];
            $data['html'] = '';
            if(!empty($spl_id)){
                $modSpl = \app\models\TSpl::findOne($spl_id);
				$data['modSpl'] = $modSpl->attributes;
                $modDetailSpl = \app\models\TSplDetail::find()->where(['spl_id'=>$spl_id])->all();
				$model = new \app\models\TTerimaBhp();
				if(!empty($modSpl->terima_bhp_id)){
					$modTBP = \app\models\TTerimaBhp::findOne($modSpl->terima_bhp_id);
					$data['tbp'] = $modTBP->attributes;
				}
                if(count($modDetailSpl)>0){
                    foreach($modDetailSpl as $i => $spl){
						$modelDetail = new \app\models\TTerimaBhpDetail();
						$modelDetail->attributes = $spl->attributes;
						$modelDetail->spld_id = $spl->spld_id;
//						$modelDetail->terimabhpd_qty = $spl->spld_qty;
						$modelDetail->terimabhpd_qty_old = $spl->spld_qty;
						$modelDetail->terimabhpd_qty = 0;
						$modelDetail->harga_estimasi = $spl->spld_harga_estimasi;
						$modelDetail->terimabhpd_harga = $spl->spld_harga_estimasi;
						$modelDetail->terimabhpd_diskon = 0;
						$modelDetail->subtotal = $modelDetail->terimabhpd_qty * $modelDetail->terimabhpd_harga;
						$modelDetail->subtotal_display = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->subtotal);
						$modelDetail->terimabhpd_keterangan = $spl->spld_keterangan;
                        $data['html'] .= $this->renderPartial('_itemSPL',['modelDetail'=>$modelDetail,'i'=>$i,'qty_spl'=>$spl->spld_qty]);
						$data['modelDetail'][] = $modelDetail->attributes;
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionGetData(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->post('id');
			$data = [];
			if(!empty($id)){
				$data['model'] = \app\models\TTerimaBhp::findOne($id)->attributes;
                $modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$id])->orderBy(['terima_bhp_id'=>SORT_ASC])->all();
				foreach( $modDetail as $i => $detail){
					$data['modDetail'][] = $detail->attributes;
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionGetItemsByTerimaBhp(){
		if(\Yii::$app->request->isAjax){
            $terima_bhp_id = Yii::$app->request->post('terima_bhp_id');
            $data = [];
            $data['html'] = '';
			$qty_po = ""; $qty_spl="";
            if(!empty($terima_bhp_id)){
                $model = \app\models\TTerimaBhp::findOne($terima_bhp_id);
                $modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$terima_bhp_id])->orderBy(['terima_bhp_id'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    foreach($modDetail as $i => $detail){
						$detail->diskon_rp = ($detail->terimabhpd_diskon / 100) * ($detail->terimabhpd_harga * $detail->terimabhpd_qty);
						$detail->subtotal = ($detail->terimabhpd_harga * $detail->terimabhpd_qty) - $detail->diskon_rp;
						if(!empty($model->spl_id)){
                                                        $terimabhpd_keterangan = pg_escape_string ($detail->terimabhpd_keterangan);
							$detailSpl = \app\models\TSplDetail::find()->where(['spl_id'=>$model->spl_id,'bhp_id'=>$detail->bhp_id])->andWhere("spld_keterangan ILIKE '%{$terimabhpd_keterangan}%' ")->one();
							if(empty($detailSpl)){
								$detailSpl = \app\models\TSplDetail::find()->where(['spl_id'=>$model->spl_id,'bhp_id'=>$detail->bhp_id])->one();
							}
							if(!empty($detailSpl)){
								$detail->harga_estimasi = !empty($detailSpl->spld_harga_estimasi)? \app\components\DeltaFormatter::formatNumberForUser($detailSpl->spld_harga_estimasi):0;
								$detail->spld_id = $detailSpl->spld_id;
								$qty_spl = $detailSpl->spld_qty;
							}
						}else if($model->spo_id){
							$detailSpo = \app\models\TSpoDetail::find()->where(['spo_id'=>$model->spo_id,'bhp_id'=>$detail->bhp_id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->one();
							$qty_po = $detailSpo->spod_qty;
						}
						$detail->terimabhpd_harga_display = \app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_harga);
						$detail->diskon_rp = \app\components\DeltaFormatter::formatNumberForUserFloat($detail->diskon_rp);
						$detail->subtotal_display = \app\components\DeltaFormatter::formatNumberForUser($detail->subtotal,2);
						$detail->subtotal = \app\components\DeltaFormatter::formatNumberForUser($detail->subtotal,2);
						$detail->terimabhpd_harga = \app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_harga);
						$detail->ppn_peritem = !empty($detail->ppn_peritem)?\app\components\DeltaFormatter::formatNumberForUser($detail->ppn_peritem,2):0;
						$detail->pph_peritem = !empty($detail->pph_peritem)?\app\components\DeltaFormatter::formatNumberForUser($detail->pph_peritem,2):0;
//                        $data['html'] .= $this->renderPartial( (!empty($model->spo_id)?'_itemAfterSave':'_itemAfterSaveSPL') ,['detail'=>$detail,'i'=>$i,'model'=>$model]);
						$detail->npwp = !empty($detail->suplier_id)?$detail->suplier->suplier_npwp:"";
                        $data['html'] .= $this->renderPartial( (!empty($model->spo_id)?'_itemAfterSave':'_itemSPL') ,['modelDetail'=>$detail,'i'=>$i,'model'=>$model,'qty_po'=>$qty_po,'qty_spl'=>$qty_spl]);
                        $data['detail'][] = $detail->attributes;
                    }
                }
				$data['terimabhp'] = $model->attributes;
				if(!empty($model->spo_id)){
					$modSPO = \app\models\TSpo::findOne($model->spo_id);
					$data['name_en'] = $modSPO->defaultValue->name_en;
				}
				if(!empty($model->spl_id)){
					$data['name_en'] = "Rp";
				}
            }
            return $this->asJson($data);
        }
    }
	
	public function actionInfoSpo($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpo::findOne($id);
            $modDetail = \app\models\TSpoDetail::find()->where(['spo_id'=>$id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->all();
			return $this->renderAjax('infoSpo',['model'=>$model,'modDetail'=>$modDetail]);
		}
    }
	public function actionInfoSpl($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpl::findOne($id);
            $modDetail = \app\models\TSplDetail::find()->where(['spl_id'=>$id])->all();
			return $this->renderAjax('infoSpl',['model'=>$model,'modDetail'=>$modDetail]);
		}
    }
	
	public function actionDaftarTerimaBhp(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-terimabhp'){
				$param['table']= \app\models\TTerimaBhp::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TTerimaBhp::primaryKey()[0];
				$param['column'] = [
                    $param['table'].'.terima_bhp_id',
                    'terimabhp_kode',
                    'spl_kode',
                    'spo_kode',
                    ['col_name'=>'tglterima','formatter'=>'formatDateForUser2'],
                    'suplier_nm',
                    'nofaktur',
                    'no_fakturpajak',
                    'no_suratjalan',
                    $param['table'].'.cancel_transaksi_id',
                    'status_bayar',
                    'tanggal_bayar',
                    'totalbayar',
                    'status_approval'
                ];
				$param['join']= ['JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.pegawaipenerima
								  LEFT JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id 
								  LEFT JOIN t_spl ON t_spl.spl_id = t_terima_bhp.spl_id
								  LEFT JOIN t_spo ON t_spo.spo_id = t_terima_bhp.spo_id
								  LEFT JOIN t_voucher_pengeluaran ON t_voucher_pengeluaran.voucher_pengeluaran_id = t_terima_bhp.voucher_pengeluaran_id
								'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarTerimaBhp');
        }
    }
	
	public function actionSetDropdownPO(){
		if(\Yii::$app->request->isAjax){
			$suplier_id = Yii::$app->request->post('suplier_id');
            $data['html'] = [];
			$data['spo'] = [];
            if(!empty($suplier_id)){
                $mod = \app\models\TSpo::find()
						->where("t_spo.suplier_id = ".$suplier_id." ")
						//->leftJoin('t_terima_bhp', 't_terima_bhp.terima_bhp_id = t_spo.terima_bhp_id')
						->andWhere("approve_status = '".\app\models\TApproval::STATUS_APPROVED."'")
						->andWhere("t_spo.cancel_transaksi_id IS NULL")
						//->andWhere("t_terima_bhp.status_approval = 'REJECTED'")
						->orderBy('created_at DESC')
						->all();
                if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'spo_id', 'spo_kode');
					$html = \yii\bootstrap\Html::tag('option');
					foreach($arraymap as $i => $val){
						$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,]);
					}
                    $data['html'] = $html;
					$sql = "SELECT * FROM t_spo 
							JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_spo.terima_bhp_id 
							WHERE (t_spo.suplier_id = {$suplier_id} ) 
							AND (approve_status = 'APPROVED') 
							AND (t_spo.cancel_transaksi_id IS NULL) 
							AND (t_terima_bhp.status_approval != 'REJECTED') 
							ORDER BY t_spo.created_at DESC";
					$mod2 = Yii::$app->db->createCommand($sql)->queryAll();
					foreach($mod2 as $i => $det){
						$data['spo'][] = $det;
					}
                }
            }else{
//				$arraymap = \app\models\TSpo::getOptionListPenerimaan();
//				$html = \yii\bootstrap\Html::tag('option');
//				foreach($arraymap as $i => $val){
//					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,]);
//				}
//				$data['html'] = $html;
			}
			return $this->asJson($data);
		}
	}
	public function actionSetDropdownSPL(){
		if(\Yii::$app->request->isAjax){
			$suplier_id = Yii::$app->request->post('suplier_id');
            $data['html'] = [];
            $data['spl'] = [];
            if(!empty($suplier_id)){
                $mod = \app\models\TSplDetail::find()->where("suplier_id = ".$suplier_id." ")
						->join("JOIN", "t_spl", "t_spl.spl_id = t_spl_detail.spl_id")
						->select("t_spl_detail.spl_id, spl_kode")
						->groupBy("t_spl_detail.spl_id, spl_kode")
						->orderBy('t_spl_detail.spl_id DESC')->all();
                if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'spl_id', 'spl_kode');
					$html = \yii\bootstrap\Html::tag('option');
					foreach($arraymap as $i => $val){
						$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,]);
					}
                    $data['html'] = $html;
                }
            }else{
				$arraymap = \app\models\TSpl::getOptionListPenerimaan();
				$html = \yii\bootstrap\Html::tag('option');
				foreach($arraymap as $i => $val){
					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,]);
				}
				$sql = "SELECT * FROM t_spl 
						JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_spl.terima_bhp_id 
						WHERE (t_spl.cancel_transaksi_id IS NULL) 
						ORDER BY t_spl.created_at DESC";
				$mod2 = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($mod2 as $i => $det){
					$data['spl'][] = $det;
				}
				$data['html'] = $html;
			}
			return $this->asJson($data);
		}
	}
	
	public function actionUpdateSPO(){
		if(\Yii::$app->request->isAjax){
			$terima_bhp_id = Yii::$app->request->post('terima_bhp_id');
			$suplier_id = Yii::$app->request->post('suplier_id');
			$data = [];
			$modTerima = \app\models\TTerimaBhp::findOne($terima_bhp_id);
			$modTerima->suplier_id = $suplier_id;
			if($modTerima->validate()){
				$data['status'] = $modTerima->save();
			}else{
				$data['status'] = false;
			}
			if($data['status']){
				$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
			}else{
				$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE);
			}
			return $this->asJson($data);
		}
	}
	
	public function actionUpdateHargaRealisasi(){
		if(\Yii::$app->request->isAjax){
			$terima_bhp_id = Yii::$app->request->post('terima_bhp_id');
			$totalbayar = Yii::$app->request->post('totalbayar');
			$suplier_id = Yii::$app->request->post('suplier_id');
			$nofaktur = Yii::$app->request->post('nofaktur');
			$no_fakturpajak = Yii::$app->request->post('no_fakturpajak');
			$no_suratjalan = Yii::$app->request->post('no_suratjalan');
			$ppn_nominal = Yii::$app->request->post('ppn_nominal');
			$pph_nominal = Yii::$app->request->post('pph_nominal');
			$total_pbbkb = Yii::$app->request->post('total_pbbkb');
			$total_biayatambahan = Yii::$app->request->post('total_biayatambahan');
			$label_biayatambahan = Yii::$app->request->post('label_biayatambahan');
            $potonganharga = Yii::$app->request->post('potonganharga');
            $label_potonganharga = Yii::$app->request->post('label_potonganharga');
			$form_params = [];
			parse_str($_POST['formdata'],$form_params);
			$data = [];
			if(count($form_params['TTerimaBhpDetail'])>0){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = true; // update t_terima_bhp_detail
					$success_2 = true; // update harga m_brg_bhp
					$success_3 = false; // update t_terima_bhp
					$success_4 = true; // update harga t_spl_detail
					$modTerima = \app\models\TTerimaBhp::findOne($terima_bhp_id);
					$modTerima->totalbayar = $totalbayar;
					$modTerima->suplier_id = $suplier_id;
					$modTerima->nofaktur = $nofaktur;
					$modTerima->no_fakturpajak = $no_fakturpajak;
					$modTerima->no_suratjalan = $no_suratjalan;
					$modTerima->ppn_nominal = $ppn_nominal;
					$modTerima->total_pbbkb = $total_pbbkb;
					$modTerima->total_biayatambahan = $total_biayatambahan;
					$modTerima->label_biayatambahan = $label_biayatambahan;
                    $modTerima->potonganharga = $potonganharga;
                    $modTerima->label_potonganharga = $label_potonganharga;
					if($modTerima->validate()){
						$success_3 = $modTerima->save();
						foreach($form_params['TTerimaBhpDetail'] as $i => $detailpost){
							$modTerimaDetail = \app\models\TTerimaBhpDetail::findOne($detailpost['terima_bhpd_id']);
							$modTerimaDetail->attributes = $detailpost;
							$modTerimaDetail->terimabhpd_harga = (isset($detailpost['terimabhpd_harga'])?$detailpost['terimabhpd_harga']:'');
							$modTerimaDetail->ppn_peritem = (isset($detailpost['ppn_peritem'])?$detailpost['ppn_peritem']:'');
							$modTerimaDetail->suplier_id = ( !empty($suplier_id)? $suplier_id : (isset($detailpost['suplier_id'])?$detailpost['suplier_id']:'') );
							$modTerimaDetail->terimabhpd_keterangan = (isset($detailpost['terimabhpd_keterangan'])?$detailpost['terimabhpd_keterangan']:'');
							if($modTerimaDetail->validate()){
								$success_1 &= $modTerimaDetail->save();
								// update harga di master bhp
								$harga_pokok = $modTerimaDetail->terimabhpd_harga;
								$nominal_ppn = 0;
								$harga = $harga_pokok;
								$include_ppn = FALSE;
								$success_2 &= \app\models\MBrgBhp::updateHargaBhp($modTerimaDetail->bhp_id,$harga_pokok,$nominal_ppn,$harga,$include_ppn);
								// end update harga di master bhp
								if(isset($detailpost['spld_id'])){
									$modSplDetail = \app\models\TSplDetail::findOne($detailpost['spld_id']);
									$modSplDetail->spld_harga_realisasi = $modTerimaDetail->terimabhpd_harga;
									if($modSplDetail->validate()){
										$success_4 &= $modSplDetail->save();
									}else{
										$success_4 = false;
									}
								}
							}else{
								$success_1 = false;
							}
						}
					}else{
						$success_3 = false;
					}
//					echo "<pre>1";
//					print_r($success_1);
//					echo "<pre>2";
//					print_r($success_2);
//					echo "<pre>3";
//					print_r($success_3);
//					echo "<pre>4";
//					print_r($success_4);
//					exit;
					if($success_1 && $success_2 && $success_3 && $success_4){
						$transaction->commit();
						$data['status'] = true;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
					} else {
                        $transaction->rollback();
                        $data['status'] = false;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE);
                    }
				} catch (\yii\db\Exception $ex) {
					$transaction->rollback();
					Yii::$app->session->setFlash('error', $ex);
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionCancelTerima($id){
		if(\Yii::$app->request->isAjax){
			$modTerima = \app\models\TTerimaBhp::findOne($id);
			$modTerimaDetails = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$id])->all();
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_terima_bhp
                    $success_3 = true; // h_persediaan
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $modTerima->terimabhp_kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
							$modTerima->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
                            if($modTerima->validate()){
								$success_2 = $modTerima->save();
							}
							foreach($modTerimaDetails as $i => $detail){
								// Start Proses Update Stock
								$detail->qty_in = 0;
								$detail->qty_out = $detail->terimabhpd_qty;
								$detail->keterangan = 'Cancel Penerimaan';
								$success_3 &= \app\models\HPersediaanBhp::updateStokPersediaan($detail,$modTerima->terimabhp_kode,$detail->terima_bhpd_id,date('d/m/Y'));
								// End Proses Update Stock
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel); 
					}
					
                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Penerimaan Berhasil di Batalkan');
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
						(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						//(!isset($data['message']) ? $data['message'] = "<br>1 ".$success_1."<br>2 ".$success_2."<br>3 ".$success_3."<br>id ".$id."<br>xxx ".$modTerima->cancel_transaksi_id : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			
			return $this->renderAjax('cancelTerima',['modTerima'=>$modTerima,'modCancel'=>$modCancel]);
		}
	}
	
	public function actionGetNpwp(){
		if(\Yii::$app->request->isAjax){
			$data = '';
			$suplier_id = Yii::$app->request->post('suplier_id');
			$subtotal = Yii::$app->request->post('subtotal');
			$data['pph'] = 0;
			$modSuplier = \app\models\MSuplier::findOne($suplier_id);
			if(!empty($modSuplier)){
				$data = $modSuplier->attributes;
				if(!empty($modSuplier->suplier_npwp)){ // pph 2%
					$data['total'] = $subtotal * 0.02;
				}else{ // pph 4%
					// $data['total'] = $subtotal * 0.04;
					// perubahan ini mengacu pada memo finacc tanggal 22/07/2024
					$data['total'] = $subtotal * 0.02;
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionReturBHP($terima_bhpd_id){
		if(\Yii::$app->request->isAjax){
			$modTerimaDetail = \app\models\TTerimaBhpDetail::findOne($terima_bhpd_id);
			$modTerima = \app\models\TTerimaBhp::findOne($modTerimaDetail->terima_bhp_id);
			$model = new \app\models\TReturBhp();
			$model->kode = "Auto Generate";
			$model->tanggal = date('d/m/Y');
			$model->terima_bhpd_id = $terima_bhpd_id;
			$model->terimabhp_kode = $modTerima->terimabhp_kode;
			$model->bhp_nm = $modTerimaDetail->bhp->bhp_nm;
			$model->qty = $modTerimaDetail->terimabhpd_qty;
			$model->harga = \app\components\DeltaFormatter::formatNumberForUserFloat($modTerimaDetail->terimabhpd_harga);
			$model->potongan = 0;
			if ($modTerima->ppn_nominal > 0) {
				if($modTerima->tglterima < '2025-01-02'){ // jika tanggal terima sebelum 2 Januari 2025
					$model->ppn_nominal = ($modTerimaDetail->terimabhpd_harga*0.11);
				} else {
					$model->ppn_nominal = ($modTerimaDetail->terimabhpd_harga*Params::DEFAULT_PPN);
				}
			} else {
				$model->ppn_nominal = 0;
			}
			$model->total_kembali = \app\components\DeltaFormatter::formatNumberForUserFloat($modTerimaDetail->terimabhpd_harga-$model->potongan+$model->ppn_nominal);
			$modTerimaDetail->terimabhpd_harga = \app\components\DeltaFormatter::formatNumberForUserFloat($modTerimaDetail->terimabhpd_harga);
			if( Yii::$app->request->post('TReturBhp')){
				$model->ppn_nominal = $model->ppn_nominal * $_POST['TReturBhp']['qty'];
				$transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_retur_bhp
                    $success_2 = false; // h_persediaan_bhp
                    $success_3 = false; // t_terima_bhp_detail
                    $success_4 = false; // t_terima_bhp
                    $model->load(\Yii::$app->request->post());
					$model->kode = \app\components\DeltaGenerator::kodeReturBHP();
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                            // Start Proses Update Stock
                            $model->bhp_id = $model->terimaBhpd->bhp_id;
                            $model->qty_in = 0;
                            $model->qty_out = $model->qty;
                            $model->keterangan = "Retur Item BHP - ".$model->deskripsi;
                            $success_2 = \app\models\HPersediaanBhp::updateStokPersediaan($model,$model->kode,$model->retur_bhp_id,date('Y-m-d'));
                            // End Proses Update Stock

                            // start update penerimaan //
                            $modTerimaDetail->load(\Yii::$app->request->post());
//							$modTerimaDetail->terimabhpd_qty = $modTerimaDetail->terimabhpd_qty - $model->qty;  // edited at 18/5/2019
                            $modTerimaDetail->terimabhpd_keterangan = $modTerimaDetail->terimabhpd_keterangan." ~~Pernah Diretur Dengan Kode: ".$model->kode."~~";
                            if($modTerimaDetail->validate()){
                                    $success_3 = $modTerimaDetail->save();
                            }
                            // end update penerimaan //

                            // ambil nilai totalretur yang lama dulu dul
                            $totalretur_lama = $modTerima->totalretur;

                            // update totalretur pada t_terima_bhp
                            $totalretur = $_POST['TReturBhp']['total_kembali'];
                            $totalretur_baru = $totalretur_lama + $totalretur;
                            $sqlRetur = "update t_terima_bhp set totalretur = ".$totalretur_baru." where terima_bhp_id = ".$modTerimaDetail->terima_bhp_id."";
                            $success_4 = Yii::$app->db->createCommand($sqlRetur)->execute();
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
				
                    if ($success_1 && $success_2 && $success_3 && $success_4) {
                        $transaction->commit();
                        $data['status'] = true;
						$data['message'] = Yii::t('app', 'Berhasil di Retur');											
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
			
			return $this->renderAjax('returBHP',['model'=>$model,'modTerimaDetail'=>$modTerimaDetail]);
		}
	}
    
    public function actionInfoApproval($id){
        if(\Yii::$app->request->isAjax){
            $model = \app\models\TSpo::findOne($id);
            return $this->renderAjax('infoApproval',['model'=>$model]);
        }
    }
}
