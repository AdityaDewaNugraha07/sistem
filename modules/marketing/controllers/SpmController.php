<?php

namespace app\modules\marketing\controllers;

use app\components\Params;
use app\components\SSP;
use app\models\HPersediaanProduk;
use app\models\MCustomer;
use app\models\TApproval;
use app\models\TProdukKeluar;
use app\models\TProduksi;
use app\models\TSpmKo;
use app\models\TSpmKoDetail;
use app\models\TLogKeluar;
use app\models\ViewLogKeluar;
use app\models\TSpmLog;
use Yii;
use app\controllers\DeltaBaseController;
use app\models\HPersediaanLog;
use app\models\MBrgLog;
use app\models\MKayu;
use app\models\TOpKo;
use app\models\TOpKoDetail;
use app\models\TPoKoDetail;
use Codeception\Command\Console;
use Codeception\Exception\ModuleException;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Response;

class SpmController extends DeltaBaseController
{
    
	public $defaultAction = 'index';

	public function actionIndex(){
        $model = new TSpmKo();
        $model->kode = 'Auto Generate';
		// $model->tanggal = date('d/m/Y');
        $model->dibuat = Yii::$app->user->identity->pegawai_id;
        $model->dibuat_display = Yii::$app->user->identity->pegawai->pegawai_nama;
        $model->disetujui = Params::DEFAULT_PEGAWAI_ID_FITRIYANAH;
		// $modOP = new \app\models\TOpKo();
        
        if(isset($_GET['spm_ko_id'])){
            $model = TSpmKo::findOne($_GET['spm_ko_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_rencanamuat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_rencanamuat);
            $model->tanggal_kirim = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
            //$model->waktu_mulaimuat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->waktu_mulaimuat);
            //$model->waktu_selesaimuat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->waktu_selesaimuat);
            $model->waktu_mulaimuat = \app\components\DeltaFormatter::formatDateTimeDBTPHPLV($model->waktu_mulaimuat);
            $model->waktu_selesaimuat = \app\components\DeltaFormatter::formatDateTimeDBTPHPLV($model->waktu_selesaimuat);
			// $modOP = \app\models\TOpKo::findOne($model->op_ko_id);
			$model->jenis_produk = $model->opKo->jenis_produk;
			$model->kode_op = $model->opKo->kode;
			$model->cust_an_nama = $model->cust->cust_an_nama;
			$model->cust_pr_nama = !empty($model->cust->cust_pr_nama)?$model->cust->cust_pr_nama:"-";
			$model->cust_an_alamat = $model->cust->cust_an_alamat;
			$model->dibuat_display = $model->dibuat0->pegawai_nama;
			$model->terima_logalam_id = $model->opKo->terima_logalam_id;
        }
		
        if( Yii::$app->request->post('TSpmKo')){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_spm_ko
                $success_2 = true; // t_spm_ko_detail
                $success_3 = true; // t_produk_keluar & t_log_keluar
                $success_4 = true; // h_persediaan_produk & h_persediaan_log
				$success_5 = true; // t_spm_log
                $model->load(Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					// exec ini jika proses edit
					$model->kode = \app\components\DeltaGenerator::kodeSpm($_POST['TSpmKo']['jenis_produk']);
				}
				if(!empty($model->waktu_mulaimuat)){
					$model->waktu_mulaimuat = explode(" - ", $model->waktu_mulaimuat);
					$model->waktu_mulaimuat = $model->waktu_mulaimuat[0]." ".$model->waktu_mulaimuat[1].":00";
				}
				if(!empty($model->waktu_selesaimuat)){
					$model->waktu_selesaimuat = explode(" - ", $model->waktu_selesaimuat);
					$model->waktu_selesaimuat = $model->waktu_selesaimuat[0]." ".$model->waktu_selesaimuat[1].":00";
				}
				if(isset($_GET['edit'])){
					if($model->status !== 'REALISASI'){
						$model->status = null;
					}
				}
				
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if((isset($_GET['edit'])) && (isset($_GET['spm_ko_id']))){
							// exec ini jika proses edit
							$modDetail = TSpmKoDetail::find()->where(['spm_ko_id'=>$_GET['spm_ko_id']])->all();
							if(count($modDetail)>0){
								TSpmKoDetail::deleteAll(['spm_ko_id'=>$_GET['spm_ko_id']]);
							}
						}
						foreach($_POST['TSpmKoDetail'] as $i => $detail){
							$modDetail = new TSpmKoDetail();
							$modDetail->attributes = $detail;
							$modDetail->spm_ko_id = $model->spm_ko_id;
							$modDetail->harga_jual_realisasi = $modDetail->harga_jual;
							$modDetail->satuan_besar_realisasi = $modDetail->satuan_besar;
                            if($model->opKo->jenis_produk == "JasaKD" || $model->opKo->jenis_produk == "JasaGesek" || $model->opKo->jenis_produk == "JasaMoulding"){
                                $modDetail->keterangan = $detail['nomor_palet_exist'];
                            }
							// $modDetail->save(); print_r($modDetail->errors); exit;
							if($modDetail->validate()){
								if($modDetail->save()){
									$success_2 &= true;
									
									// SPM Realisasi nye
									if(isset($_POST['TProdukKeluar'])){
										foreach($_POST['TProdukKeluar'] as $i => $detail){
											if($detail['produk_id']==$modDetail->produk_id){
												$modProdukKeluar = TProdukKeluar::findOne(['nomor_produksi'=>$detail['nomor_produksi']]);
												if(empty($modProdukKeluar)){
													$modProdukKeluar = new TProdukKeluar();
													$modProdukKeluar->attributes = $detail;
													$modProdukKeluar->kode = \app\components\DeltaGenerator::kodeProdukKeluar($model->jenis_produk);
													$modProdukKeluar->tanggal = date("Y-m-d");
													$modProdukKeluar->cara_keluar = TProdukKeluar::CARA_KELUAR_PENJUALAN;
													$modProdukKeluar->reff_no = $model->kode;
													$modProdukKeluar->reff_detail_id = $modDetail->spm_kod_id;
													$modProdukKeluar->petugas_mengeluarkan = $model->dikeluarkan;
													$modProdukKeluar->keterangan = !empty($modProdukKeluar->keterangan)?$modProdukKeluar->keterangan:'-';
													$modProdukKeluar->gudang_id = $detail['gudang_id'];
												}else{
													$modProdukKeluar->reff_detail_id = $modDetail->spm_kod_id;
													$modProdukKeluar->gudang_id = $detail['gudang_id'];
												}
												if($modProdukKeluar->validate()){
													if($modProdukKeluar->save()){
														$success_3 &= true;
														
														if(HPersediaanProduk::getCurrentStockPerPalet($detail['nomor_produksi'])['palet']>0){
															// Start Proses Update Stock (OUT)
															$modPersediaan = new HPersediaanProduk();
															$modPersediaan->attributes = $modProdukKeluar->attributes;
															$modPersediaan->tgl_transaksi = $modProdukKeluar->tanggal;
															$modPersediaan->gudang_id = $modProdukKeluar->gudang_id;
															$modPersediaan->reff_no = $model->kode;
															$modPersediaan->in_qty_palet = 0;
															$modPersediaan->in_qty_kecil = 0;
															$modPersediaan->in_qty_kecil_satuan = $modProdukKeluar->satuan_kecil;
															$modPersediaan->in_qty_m3 = 0;
															$modPersediaan->out_qty_palet = $modProdukKeluar->qty_besar;
															$modPersediaan->out_qty_kecil = $modProdukKeluar->qty_kecil;
															$modPersediaan->out_qty_kecil_satuan = $modProdukKeluar->satuan_kecil;
															$modPersediaan->out_qty_m3 = $modProdukKeluar->kubikasi;
															$modPersediaan->keterangan = $modProdukKeluar->cara_keluar;
															$success_4 &= HPersediaanProduk::updateStokPersediaan($modPersediaan);
															// End Proses Update Stock (OUT)
														}else{
															$success_4 = false;
															$errmsg = $detail['nomor_produksi']." Out of Stock";
														}
														
													}else{
														$success_3 = false;
													}
												}else{
													$success_3 = false;
												}
											}
										}
									}

									
								}else{
									$success_2 = false;
								}
							}else{
								$success_2 = false;
							}
						}
						
						// SPM Realisasi Log
						$modLogKeluar = TLogKeluar::find()->where(['reff_no'=>$model->kode])->all();
						if(empty($modLogKeluar)){
							if(isset($_POST['TLogKeluar'])){
								foreach($_POST['TLogKeluar'] as $i => $logkeluar){
									$modKayu = MBrgLog::findOne(['log_id'=>$modDetail->produk_id]);
										$modLogKeluar = TLogKeluar::findOne(['no_barcode'=>$logkeluar['no_barcode']]);
										if(empty($modLogKeluar)){
											$modLogKeluar = new TLogKeluar();
											$modLogKeluar->attributes = $logkeluar;
											$modLogKeluar->kode = \app\components\DeltaGenerator::kodeLogKeluar($model->jenis_produk);
											$modLogKeluar->tanggal = date("Y-m-d");
											$modLogKeluar->cara_keluar = TLogKeluar::CARA_KELUAR_PENJUALAN;
											$modLogKeluar->reff_no = $model->kode;
											$modLogKeluar->pic_log_keluar = Yii::$app->user->identity->pegawai_id;
											$modLogKeluar->keterangan = !empty($modLogKeluar->keterangan)?$modLogKeluar->keterangan:'';
										} else {
											$success_3 = false;
											$errmsg = $logkeluar['no_barcode']." sudah pernah keluar!";
										}
										if($modLogKeluar->validate()){
											if($modLogKeluar->save()){
												$success_3 &= true;
												
												if(isset($_POST['HPersediaanLog'])){
													$persediaan_in = Yii::$app->db->createCommand("SELECT * FROM h_persediaan_log WHERE no_barcode = '{$logkeluar['no_barcode']}' AND status = 'IN'")->queryAll();
													$qty_in = count($persediaan_in);
													$persediaan_out = Yii::$app->db->createCommand("SELECT * FROM h_persediaan_log WHERE no_barcode = '{$logkeluar['no_barcode']}' AND status <> 'IN'")->queryAll();
													$qty_out = count($persediaan_out);
													$stock = $qty_in - $qty_out;
													// if(HPersediaanLog::getStockLog($detail['no_barcode'])['stock'] > 0){
														$persediaanLog = HPersediaanLog::findOne(['no_barcode'=>$logkeluar['no_barcode']]);
															if($stock > 0){
																// Start Proses Update Stock (OUT)
																$modPersediaan = new HPersediaanLog();
																$modPersediaan->attributes 		= $modLogKeluar->attributes;
																$modPersediaan->no_barcode		= $persediaanLog->no_barcode;
																$modPersediaan->tgl_transaksi 	= date("Y-m-d");
																$modPersediaan->reff_no 		= $model->kode;
																$modPersediaan->status 			= 'OUT';
																$modPersediaan->keterangan 		= 'MUTASI LOG DARI GUDANG LOG ALAM MENUJU PENJUALAN';
																$modPersediaan->kayu_id 		= $persediaanLog->kayu_id;
																$modPersediaan->lokasi			= 'PENJUALAN LOG ALAM';
																$modPersediaan->fisik_diameter	= $persediaanLog->fisik_diameter;
																$modPersediaan->fisik_panjang	= $persediaanLog->fisik_panjang;
																$modPersediaan->fisik_reduksi	= $persediaanLog->fisik_reduksi;
																$modPersediaan->fisik_volume	= $persediaanLog->fisik_volume;
																$modPersediaan->no_produksi		= $persediaanLog->no_produksi;
																$modPersediaan->no_grade		= $persediaanLog->no_grade;
																$modPersediaan->no_btg			= $persediaanLog->no_btg;
																$modPersediaan->no_lap			= $persediaanLog->no_lap;
																$modPersediaan->diameter_ujung1	= $persediaanLog->diameter_ujung1;
																$modPersediaan->diameter_ujung2	= $persediaanLog->diameter_ujung2;
																$modPersediaan->diameter_pangkal1	= $persediaanLog->diameter_pangkal1;
																$modPersediaan->diameter_pangkal2	= $persediaanLog->diameter_pangkal2;
																$modPersediaan->cacat_panjang	= $persediaanLog->cacat_panjang;
																$modPersediaan->cacat_gb		= $persediaanLog->cacat_gb;
																$modPersediaan->cacat_gr		= $persediaanLog->cacat_gr;
																$modPersediaan->pot				= $persediaanLog->pot;
																$modPersediaan->fsc				= ($persediaanLog->fsc == 1)?true:false;
																$success_4 &= HPersediaanLog::updateStokPersediaan($modPersediaan);
																// End Proses Update Stock (OUT)
															}else{
																$success_4 = false;
																$errmsg = $logkeluar['no_barcode']." Out of Stock";
															}
												}
											}else{
												$success_3 = false;
											}
										}else{
											$success_3 = false;
										}
								}
							}
	
							// proses realisasi masuk ke t_spm_log
							if(isset($_POST['TSpmLog'])){
								foreach($_POST['TSpmLog'] as $i => $spm_log){
										$modSpmLog = new TSpmLog();
										$modSpmLog->attributes 	= $spm_log;
										$modSpmLog->reff_no 	= $model->kode;
										// $modSpmLog->panjang		= $modPersediaan->fisik_panjang;
										// $modSpmLog->kode_potong	= $modPersediaan->pot;
										// $modSpmLog->kayu_id		= $modPersediaan->kayu_id;
										// $modSpmLog->no_barcode	= $modPersediaan->no_barcode;
										// $modSpmLog->no_lap		= $modPersediaan->no_lap;
										// $modSpmLog->no_grade	= $modPersediaan->no_grade;
										// $modSpmLog->no_btg		= $modPersediaan->no_btg;
										// $modSpmLog->no_produksi	= $modPersediaan->no_produksi;
										// $modSpmLog->save(); $modSpmLog->errors;exit;
										if($modSpmLog->validate()){
											if ($modSpmLog->save()){
												$success_5 &= true;
											} else {
												$success_5 = false;
											}
										} else {
											$success_5 = false;
										}
									// }
								}
							}
						}
                    }
                }
				
				// echo "<pre>1 ";
				// print_r($success_1);
				// echo "<pre>2 ";
				// print_r($success_2);
				// echo "<pre>3 ";
				// print_r($success_3);
				// echo "<pre>4 ";
				// print_r($success_4);
				// echo "<pre>5 ";
				// print_r($success_5);
				// exit;
				// print_r($modDetail);exit;
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'spm_ko_id'=>$model->spm_ko_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionSetOP(){
		if(Yii::$app->request->isAjax){
			$op_ko_id = Yii::$app->request->post('op_ko_id');
			$data = [];
			if(!empty($op_ko_id)){
				$model = \app\models\TOpKo::findOne($op_ko_id);
				$modCust = \app\models\MCustomer::findOne($model->cust_id);
				if(!empty($model)){
					$data = $model->attributes;
					$data['tanggal_kirim'] = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
				}
				if(!empty($modCust)){
					$data['cust'] = $modCust->attributes;
					$data['cust']['cust_pr_nama'] = (!empty($modCust->cust_pr_nama)?$modCust->cust_pr_nama:"-");
					
				}
			}
			return $this->asJson($data);
		}
	}

    /**
     * @return string|void
     */
	
	public function actionOpenOP(){
		if(Yii::$app->request->isAjax){
			if(Yii::$app->request->get('dt') === 'table-op'){
				$param['table']= \app\models\TOpKo::tableName();
				$param['pk']= $param['table'].".".\app\models\TOpKo::primaryKey()[0];
				$param['column'] = [$param['table'].'.op_ko_id',																						//0
									$param['table'].'.kode',																							//1
									$param['table'].'.jenis_produk',																					//2
									$param['table'].'.tanggal',																							//3
									'm_sales.sales_nm',																									//4
									$param['table'].'.sistem_bayar',																					//5
									$param['table'].'.tanggal_kirim',																					//6
									'm_customer.cust_an_nama',																							//7
									'm_customer.cust_pr_nama',																							//8
//									'(select t_approval.status from t_approval where t_approval.reff_no = t_op_ko.kode and level = 1 ) as status_satu',	//9
//									'(select t_approval.status from t_approval where t_approval.reff_no = t_op_ko.kode and level = 2 ) as status_dua',	//10
//									'(select t_approval.status from t_approval where t_approval.reff_no = t_op_ko.kode and level = 3 ) as status_tiga',	//11
                                    $param['table'].'.status',																							//12
                                    $param['table'].'.status_approval'
									];
				$param['join']= ['JOIN m_sales ON m_sales.sales_id = '.$param['table'].'.sales_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  LEFT JOIN t_spm_ko ON t_spm_ko.op_ko_id = t_op_ko.op_ko_id'];
				$param['where']= $param['table'].".cancel_transaksi_id IS NULL 
										AND (CASE 
                                            WHEN t_op_ko.jenis_produk = 'JasaKD' THEN t_op_ko.jenis_produk = 'JasaKD'
                                            WHEN t_op_ko.jenis_produk = 'JasaGesek' THEN t_op_ko.jenis_produk = 'JasaGesek'
                                            WHEN t_op_ko.jenis_produk = 'JasaMoulding' THEN t_op_ko.jenis_produk = 'JasaMoulding'
                                        ELSE
                                            t_spm_ko.op_ko_id IS NULL 
									END)";
				$param['group']=['GROUP BY 
				    t_op_ko.op_ko_id, 
				    t_op_ko.kode, 
				    t_op_ko.jenis_produk, 
				    t_op_ko.tanggal, 
				    m_sales.sales_nm, 
				    t_op_ko.sistem_bayar, 
				    t_op_ko.tanggal_kirim, 
				    m_customer.cust_an_nama, 
				    m_customer.cust_pr_nama,
				    t_op_ko.status,
				    t_op_ko.status_approval'
                ];

				return Json::encode(SSP::complex( $param ));
			}
			return $this->renderAjax('orderpenjualan');
        }
	}
	
	function actionGetItems(){
		if(Yii::$app->request->isAjax){
            $op_ko_id = Yii::$app->request->post('op_ko_id');
			$model = new TSpmKo();
			$modDetail = [];
            $modOpKo = [];
            $data = [];
            if(!empty($op_ko_id)){
                $modOpKo = \app\models\TOpKo::findOne($op_ko_id);
                $model->jenis_produk = $modOpKo->jenis_produk;
                $modOPDetail = \app\models\TOpKoDetail::find()->where(['op_ko_id'=>$op_ko_id])->all();
            }
            $data['html'] = '';
            if(count($modOPDetail)>0){
                foreach($modOPDetail as $i => $opdetail){
					$modDetail = new TSpmKoDetail();
					$modDetail->attributes = $opdetail->attributes;
					if($model->jenis_produk == "Log"){
						$modLog = \app\models\MBrgLog::findOne(['log_id'=>$opdetail->produk_id]);
					}
                    if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                        $modDetail->qty_besar = 0; $modDetail->qty_kecil = 0; $modDetail->kubikasi = 0;
                        $data['html'] .= $this->renderPartial('_itemOPJasaKD',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i,'modOpDetail'=>$opdetail]); //
                    }else{
						if($model->jenis_produk == "Log"){
							$data['html'] .= $this->renderPartial('_itemOP',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i,'modOpDetail'=>$opdetail, 'modLog'=>$modLog]);
						} else {
							$data['html'] .= $this->renderPartial('_itemOP',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i,'modOpDetail'=>$opdetail]);
						}
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	function actionGetItemsById(){
		if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $realisasi = Yii::$app->request->post('realisasi');
            $edit = Yii::$app->request->post('edit');
			$model = TSpmKo::findOne($id);
            $model->jenis_produk = $model->opKo->jenis_produk;
			$modDetail = [];
            $data = [];
            if(!empty($id)){
                $modDetail = TSpmKoDetail::find()->where(['spm_ko_id'=>$id])->all();
            }
            $data['html'] = '';
            if(count($modDetail)>0){
                foreach($modDetail as $i => $detail){
					$modOpDetail = \app\models\TOpKoDetail::findOne(['op_ko_id'=>$model->op_ko_id,'produk_id'=>$detail->produk_id]);
					if($model->jenis_produk == "Log"){
						$modLog = \app\models\MBrgLog::findOne(['log_id'=>$modOpDetail->produk_id]);
					}
                    if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                        $itemHtml = "_itemOPJasaKD";
                        $detail->nomor_palet_exist = $detail->keterangan;
                    }else{
                        $itemHtml = "_itemOP";
                    }
					if($realisasi){
						if($model->jenis_produk == "Log"){
							$detail->kubikasi = number_format($detail->kubikasi ,2);
						} else {
							$detail->kubikasi = number_format($detail->kubikasi ,4);
						}
						if($model->status!= TSpmKo::REALISASI){
							$detail->qty_besar_realisasi = 0;
							$detail->satuan_besar_realisasi = $detail->satuan_besar;
							$detail->qty_kecil_realisasi = 0;
							$detail->satuan_kecil_realisasi = $detail->satuan_kecil;
							$detail->kubikasi_realisasi = 0;
						}
//						if($modOpDetail->is_random=='1'){
//							$modRandom = \app\models\TOpKoRandom::find()->where("op_ko_detail_id = ".$modOpDetail->op_ko_detail_id)->orderBy("nomor_produksi ASC, op_ko_random_id ASC")->all();
//							if(count($modRandom)>0){
//								$data['html'] .= $this->renderPartial('_itemOP',['model'=>$model,'modDetail'=>$detail,'i'=>$i,'realisasi'=>$realisasi,'modOpDetail'=>$modOpDetail]);
//								if($edit){
//									foreach($modRandom as $ii => $random){
//										$data['html'] .= $this->renderPartial('_itemOpRandom',['model'=>$model,'modDetail'=>$detail,'i'=>$i,'ii'=>$ii,'realisasi'=>$realisasi,'random'=>$random,'edit'=>$edit,'modRandom'=>$modRandom]);
//									}
//								}
//							}
//						}else{
							if($model->jenis_produk == "Log"){
								$data['html'] .= $this->renderPartial($itemHtml,['model'=>$model,'modDetail'=>$detail,'i'=>$i,'realisasi'=>$realisasi,'modOpDetail'=>$modOpDetail, 'modLog'=>$modLog]);
							} else {
								$data['html'] .= $this->renderPartial($itemHtml,['model'=>$model,'modDetail'=>$detail,'i'=>$i,'realisasi'=>$realisasi,'modOpDetail'=>$modOpDetail]);
							}
//						}
					}else{
						if($model->jenis_produk == "Log"){
							$data['html'] .= $this->renderPartial($itemHtml,['model'=>$model,'modDetail'=>$detail,'i'=>$i,'realisasi'=>$realisasi,'modOpDetail'=>$modOpDetail, 'modLog'=>$modLog]);
						} else {
							$data['html'] .= $this->renderPartial($itemHtml,['model'=>$model,'modDetail'=>$detail,'i'=>$i,'realisasi'=>$realisasi,'modOpDetail'=>$modOpDetail]);
						}
					}
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
		if(Yii::$app->request->isAjax){
			if(Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= TSpmKo::tableName();
				$param['pk']= $param['table'].".". TSpmKo::primaryKey()[0];
				$param['column'] = [$param['table'].'.spm_ko_id',
									$param['table'].'.kode',
									't_op_ko.jenis_produk',
									$param['table'].'.tanggal',
									'm_customer.cust_an_nama',
									$param['table'].'.tanggal_kirim',
									$param['table'].'.kendaraan_nopol',
									$param['table'].'.kendaraan_supir',
									$param['table'].'.alamat_bongkar',
									$param['table'].'.cancel_transaksi_id',
									$param['table'].'.status',
									'nota_penjualan_id'
									];
				$param['join']= ['JOIN t_op_ko ON t_op_ko.op_ko_id = '.$param['table'].'.op_ko_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  LEFT JOIN t_nota_penjualan ON t_nota_penjualan.spm_ko_id = t_spm_ko.spm_ko_id
								'];
				$param['where'] = "t_op_ko.cancel_transaksi_id IS NULL";
				return Json::encode(SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionPrintSPM(){
		$this->layout = '@views/layouts/metronic/print';
		$model = TSpmKo::findOne($_GET['id']);
		$modDetail = TSpmKoDetail::find()->where(['spm_ko_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'SURAT PERINTAH MUAT');
		if($caraprint == 'PRINT'){
            $sql_update_status_cetak = "update t_spm_ko set status_cetak = 1 where spm_ko_id = '".$_GET['id']."' ";
            Yii::$app->db->createCommand($sql_update_status_cetak)->execute();
			return $this->render('printSPM',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printSPM',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printSPM',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}
	
	public function actionFindSPMAll(){
		if(Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT * FROM t_spm_ko 
					WHERE kode ilike '%{$term}%' AND cancel_transaksi_id IS NULL
					ORDER BY created_at";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'spm_ko_id', 'kode');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['spm_ko_id'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	
	public function actionGetCurrentProdukList(){
        if(Yii::$app->request->isAjax){
			$kode_spm = Yii::$app->request->post('kode_spm');
			$status = Yii::$app->request->post('status');
            $modProdukKeluar = TProdukKeluar::find()->where(['reff_no'=>$kode_spm])->all();
			$modSpm = TSpmKo::findOne(['kode'=>$kode_spm]);
			$data = [];
			$data['item'] = "";
			$data['model'] = "";
			if(count($modProdukKeluar)>0){
				foreach($modProdukKeluar as $i => $keluar){
					$data['model'][] = $keluar->attributes;
					if($status== TSpmKo::REALISASI){
						$data['item'] .= $this->renderPartial('_itemProdukListRealisasi',['model'=>$keluar]);
					}else{
						$modOpDetail = \app\models\TOpKoDetail::find()->where("produk_id = ".$keluar->produk_id)->one();
						if(!empty($modOpDetail)){
							$modRandom = \app\models\TOpKoRandom::find()->where("op_ko_detail_id = ".$modOpDetail->op_ko_detail_id)->all();
							if(count($modRandom)>0){
								$rand = [];
								foreach($modRandom as $ii => $random){
									$rand[] = $random;
								}
								$keluar->random = Json::encode($rand);
							}
						}
						$data['item'] .= $this->renderPartial('_itemProdukList',['model'=>$keluar]);
					}
				}
			}
            return $this->asJson($data);
        }
    }
	
	public function actionAddProdukList(){
        if(Yii::$app->request->isAjax){
            $model = new TProdukKeluar();
            $data['item'] = $this->renderPartial('_itemProdukList',['model'=>$model]);
            return $this->asJson($data);
        }
    }
	
	public function actionFindStockActive(){
        if(Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$jns_produk = Yii::$app->request->get('type');
			$notinpost = json_decode( Yii::$app->request->get('notin') );
			$data = []; $notin = "";
			if(!empty($notinpost)){
				$notin = "AND h_persediaan_produk.nomor_produksi NOT IN(";
				foreach($notinpost as $i => $not){
					$notin .= "'$not'";
					if( ($i+1)!=(count($notinpost)) ){
						$notin .= ",";
					}
				}
				$notin .= ")";
			}
			if(!empty($term)){
				$query = "
					SELECT h_persediaan_produk.produk_id, nomor_produksi, gudang_id, 
						SUM(in_qty_palet-out_qty_palet) AS qty_palet, 
						SUM(in_qty_kecil-out_qty_kecil) AS qty_kecil, 
						in_qty_kecil_satuan AS satuan_kecil,
						SUM(in_qty_m3-out_qty_m3) AS kubikasi  
					FROM h_persediaan_produk 
					JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
					WHERE ".(!empty($term)?"nomor_produksi ILIKE '%".$term."%'":'')." AND h_persediaan_produk.active IS TRUE AND produk_group = '".$jns_produk."' ".$notin." 
					GROUP BY h_persediaan_produk.produk_id, nomor_produksi, gudang_id, in_qty_kecil_satuan
					HAVING SUM(in_qty_palet-out_qty_palet) > 0
				";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['nomor_produksi'], 'text'=>$val['nomor_produksi']];
					}
				}
			}
            return $this->asJson($data);
        }
    }
	function actionSetItemProductList(){
		if(Yii::$app->request->isAjax){
            $nomor_produksi = Yii::$app->request->post('nomor_produksi');
            $op_ko_id = Yii::$app->request->post('op_ko_id');
            if(!empty($nomor_produksi)){
                $data['produksi'] = TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
                $data['produk'] = \app\models\MBrgProduk::findOne($data['produksi']['produk_id']);
                $data['persediaan'] = HPersediaanProduk::getDataByNomorProduksi($nomor_produksi);
				$data['random'] = '';
				$data['kubikasi_hasilhitung'] = 0;
				if(!empty($op_ko_id)){
					$modRandom = Yii::$app->db->createCommand("
								SELECT t_op_ko_random.* FROM t_op_ko_random
								JOIN t_op_ko_detail ON t_op_ko_detail.op_ko_detail_id = t_op_ko_random.op_ko_detail_id
								WHERE op_ko_id = {$op_ko_id} AND t_op_ko_detail.produk_id = {$data['produksi']->produk_id}
								")->queryAll();
					if(count($modRandom)>0){
						$rand = [];
						foreach($modRandom as $ii => $random){
							$rand[] = $random;
						}
						$data['random'] = Json::encode($rand);
					}
				}
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }

	function actionGetItemsScanned(){
		if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data = [];
			$data['html'] = '';
			$data['status'] = "";
            if(!empty($id)){
                $modSPM = TSpmKo::findOne($id);
				$data['status'] = $modSPM->status;
				$models = TProdukKeluar::find()->where(["reff_no"=>$modSPM->kode])->orderBy("produk_keluar_id DESC")->all();
				if(count($models)>0){
					foreach($models as $i => $model){
						$data['html'] .= $this->renderPartial('_itemScannedSpm',['model'=>$model]);
					}
				}
            }
            return $this->asJson($data);
        }
    }
	
	public function actionScanSpm(){
		$model = new TProdukKeluar();
        if (isset($_GET['spm_ko_id'])) {
            $model->spm_ko_id = $_GET['spm_ko_id'];
        }
        return $this->render('scanspm',['model'=>$model]);
	}
	
    public function actionReview(){
        if(Yii::$app->request->isAjax){
            $spm_ko_id = $_GET['spm_ko_id'];
            $nomor_produksi = $_GET['nomor_produksi'];
            $modSpm = TSpmKo::findOne($spm_ko_id);
            $modProduksi = TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modBrgProduk = \app\models\MBrgProduk::findOne(['produk_id'=>$modProduksi->produk_id]);
            $modHasilProduksi = \app\models\THasilProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modPersediaan = HPersediaanProduk::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modProdukKeluar = TProdukKeluar::findOne(['nomor_produksi'=>$nomor_produksi]);
            return $this->renderAjax('_review',['spm_ko_id'=>$spm_ko_id, 
                                                'nomor_produksi'=>$nomor_produksi,
                                                'modSpm'=>$modSpm,
                                                'modProduksi'=>$modProduksi,
                                                'modBrgProduk'=>$modBrgProduk,
                                                'modHasilProduksi'=>$modHasilProduksi,
                                                'modPersediaan'=>$modPersediaan,
                                                'modProdukKeluar'=>$modProdukKeluar,
                                                ]);
        }
    }

    public function actionShowDetail(){
        $data['status']         = false;
        if(Yii::$app->request->isAjax){
			$spm_ko_id          = Yii::$app->request->post('spm_ko_id');
			$nomor_produksi     = Yii::$app->request->post('nomor_produksi');
			$modSpm             = TSpmKo::findOne($spm_ko_id);
			$modProduksi        = TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
			$modPersediaan      = HPersediaanProduk::getDataByNomorProduksi($nomor_produksi);
			$modProdukKeluar    = TProdukKeluar::find()
                                                ->where(['nomor_produksi' => $nomor_produksi])
                                                ->andWhere(['or', ['cancel_transaksi_id' => null], ['produk_kembali_id' => null]])
                                                ->one();
			if($modProdukKeluar === null){
                $modSPMdetail = TSpmKoDetail::find()->where(['spm_ko_id' => $modSpm->spm_ko_id,'produk_id' => $modProduksi->produk_id])->all();
				if(count($modSPMdetail)>0){
					$spmsama = true;
				}else{
					$spmsama = false;
				}
				if($spmsama){
					if(!empty($modPersediaan)){
						if($modSpm !== null){
                            $data['status'] = true;
                            $data['msg']    = "Data ok";
						}else{
							$data['status'] = false;
							$data['msg']    = "Data SPM tidak ditemukan!";
						}
					}else{
						$data['status'] = false;
						$data['msg']    = "Tidak tersedia di stock!";
					}
				}else{
					$data['status'] = false;
					$data['msg']    = "Produk tidak sesuai dengan SPM";
				}
			}else{
				if($modProdukKeluar['produk_kembali_id'] !== null){
					$modSPMdetail = TSpmKoDetail::find()->where(['spm_ko_id' => $modSpm->spm_ko_id,'produk_id' => $modProduksi->produk_id])->all();
					if(count($modSPMdetail)>0){
						$spmsama = true;
					}else{
						$spmsama = false;
					}
					if($spmsama){
						if(!empty($modPersediaan)){
							if($modSpm !== null){
								$data['status'] = true;
								$data['msg']    = "Data ok";
							}else{
								$data['status'] = false;
								$data['msg']    = "Data SPM tidak ditemukan!";
							}
						}else{
							$data['status'] = false;
							$data['msg']    = "Tidak tersedia di stock!";
						}
					}else{
						$data['status'] = false;
						$data['msg']    = "Produk tidak sesuai dengan SPM";
					}
				}else{
					$data['status'] = false;
					$data['msg']    = "Produk sudah pernah keluar!";
				}
			}
		} else {
            $data['msg'] = "xxx";
        }
        return $this->asJson($data);
	}

	public function actionSaveNomorProduksi(){
		if(Yii::$app->request->isAjax){
			$data['status'] = false;
			$data['msg'] = "";
			$nomor_produksi = Yii::$app->request->post('nomor_produksi');
			$spm_ko_id = Yii::$app->request->post('spm_ko_id');
			$modSpm = TSpmKo::findOne($spm_ko_id);
			$modProduksi = TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
			$modPersediaan = HPersediaanProduk::getDataByNomorProduksi($nomor_produksi);
			$modProdukKeluar = TProdukKeluar::findOne(['nomor_produksi'=>$nomor_produksi, 'reff_no' => $modSpm->kode]);
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_produk_keluar

                if(empty($modProdukKeluar)){
                    $spmsama = true;
                    $modSPMdetail = TSpmKoDetail::find()->where(['spm_ko_id'=>$modSpm->spm_ko_id,'produk_id'=>$modProduksi->produk_id])->all();
                    if(count($modSPMdetail)>0){
                        $spmsama = true;
                    }else{
                        $spmsama = false;
                    }
                    if($spmsama){
                        if(!empty($modPersediaan)){
                            if(!empty($modSpm)){
                                $modProdukKeluar = new TProdukKeluar();
                                $modProdukKeluar->attributes = $modProduksi->attributes;
                                $modProdukKeluar->attributes = $modPersediaan;
                                $modProdukKeluar->kode = \app\components\DeltaGenerator::kodeProdukKeluar($modProduksi->produk->produk_group);
                                $modProdukKeluar->tanggal = date("Y-m-d");
                                $modProdukKeluar->cara_keluar = TProdukKeluar::CARA_KELUAR_PENJUALAN;
                                $modProdukKeluar->reff_no = $modSpm->kode;
                                $modProdukKeluar->petugas_mengeluarkan = Yii::$app->user->identity->pegawai_id;
                                $modProdukKeluar->keterangan = 'Scan Result';
                                $modProdukKeluar->gudang_id = $modPersediaan['gudang_id'];
                                $modProdukKeluar->qty_besar = $modPersediaan['qty_palet'];
                                $modProdukKeluar->satuan_besar = $modProduksi->produk->produk_satuan_besar;
                                if($modProdukKeluar->validate()){
                                    if($modProdukKeluar->save()){
                                        $success_1 = true;
                                        $data['status'] = true;
                                    }
                                }
                            }else{
                                $data['status'] = false;
                                $data['msg'] = "Data SPM tidak ditemukan!";
                            }
                        }else{
                            $data['status'] = false;
                            $data['msg'] = "Tidak tersedia di stock!";
                        }
                    }else{
                        $data['status'] = false;
                        $data['msg'] = "Produk tidak sesuai dengan SPM";
                    }
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Produk sudah pernah keluar!";
                }

                if ($success_1) {
                    $transaction->commit();
                    $data['status'] = true;
                    $data['message'] = Yii::t('app', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                    Yii::$app->session->setFlash('success', 'Data berhasil disimpan!');
                } else {
                    $transaction->rollback();
                    $data['status'] = false;
                    (!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                    (isset($data['message_validate']) ? $data['message'] = null : '');
                    Yii::$app->session->setFlash('error', 'Data gagal disimpan!');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $data['message'] = $ex;
            }
			return $this->asJson($data);
		}
	}

    /**
     * @param $id
     * @return string|void|Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionDeleteNomorProduksi($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TProdukKeluar::findOne($id);
            $modSpm = TSpmKo::findOne(['kode' => $model->reff_no]);
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
                                $data['callback']   = "getItemsScanned(" . $modSpm->spm_ko_id . ");";
                                $data['message']    = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                                $transaction->commit();
                                return $this->asJson($data);
                            }
                            $panggilan  = $approver->assignedTo->pegawai_jk === 'Perempuan' ? 'Ibu ' : 'Bapak ';
                            $nama       = ucwords(strtolower($approver->assignedTo->pegawai_nama));
                            throw new Exception("<i class='icon-close'></i> Data Gagal dihapus karena $panggilan <strong> $nama </strong> belum melakukan approve");
                        }
                    }else if($model->delete()) {
                        $data['status']     = true;
                        $data['callback']   = "getItemsScanned(" . $modSpm->spm_ko_id . ");";
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
                'actionname'    => 'deleteNomorProduksi'
            ]);
        }
    }
	
	public function actionSetQty(){
		if(Yii::$app->request->isAjax){
			$produk_id = Yii::$app->request->post('produk_id');
			$data = [];
			if(!empty($produk_id)){
				$modProduk = \app\models\MBrgProduk::findOne($produk_id);
				$data['qty_kecil'] = $modProduk->produk_qty_satuan_kecil;
				$data['kubikasi'] = $modProduk->kapasitas_kubikasi;
			}
            return $this->asJson($data);
        }
	}
	
	public function actionAddProduk(){
        if(Yii::$app->request->isAjax){
            $modDetail = new TSpmKoDetail();
            $data['item'] = $this->renderPartial('_addItem',['modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }
	
	public function actionCheckApproval(){
		if(Yii::$app->request->isAjax){
			$op_ko_id = Yii::$app->request->post('op_ko_id');
			$data['status'] = true;
			if(!empty($op_ko_id)){
				$modOP = \app\models\TOpKo::findOne($op_ko_id);
				$approval = \app\models\TApproval::find()->where(['reff_no'=>$modOP->kode])->all();
				if(count($approval)>0){
					foreach($approval as $i => $appr){
						if($appr->status == "APPROVED"){
							$data['status'] &= true;
						}else{
							$data['status'] = false;
						}
					}
				}
			}
            return $this->asJson($data);
        }
	}
	
	public function actionListRandom($produk_id,$op_ko_id){
		if(Yii::$app->request->isAjax){
			$modOp = \app\models\TOpKo::findOne($op_ko_id);
			$modOpDetail = \app\models\TOpKoDetail::findOne(['op_ko_id'=>$op_ko_id,'produk_id'=>$produk_id]);
			$sql = "SELECT * FROM t_op_ko_random WHERE op_ko_detail_id = {$modOpDetail->op_ko_detail_id} ";
			$models = Yii::$app->db->createCommand($sql)->queryAll();
			$modProduk = \app\models\MBrgProduk::findOne($produk_id);
			return $this->renderAjax('random',['models'=>$models,'modProduk'=>$modProduk]);
		}
	}
	
	public function actionInfoPalet(){
		if(Yii::$app->request->isAjax){
			$nomor_produksi = Yii::$app->request->get('nomor_produksi');
			$modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$nomor_produksi]);
			$modTerimaRandom = []; $modProduksi = [];
			$keperluan = '';
			if(!empty($modTerima)){
				$modTerimaRandom = \app\models\TTerimaKoKd::find()->where("tbko_id = ".$modTerima->tbko_id)->all();
				$modProduksi = TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
			} else {
				$keperluan = 'Penanganan Barang Retur';
				$modTerima = \app\models\TReturProdukDetail::findOne(['nomor_produksi'=>$nomor_produksi]);
				if(empty($modTerima)){
					$modHasil = \app\models\THasilRepacking::findOne(['nomor_produksi'=>$nomor_produksi]);
					$modTerima->qty_kecil = $modHasil->qty_kecil;
					$modTerima->kubikasi = $modHasil->qty_m3;
				}
			}
			return $this->renderAjax('infoPalet',['modTerima'=>$modTerima,'modTerimaRandom'=>$modTerimaRandom,'modProduksi'=>$modProduksi, 'keperluan'=>$keperluan]);
        }
    }
    
    public function actionEditKecil($id){
		if(Yii::$app->request->isAjax){
			$model = TSpmKo::findOne($id);
			if( Yii::$app->request->post('TSpmKo')){
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_spm
                    $success_2 = true; // t_nota_penjualan
                    $success_3 = true; // t_surat_pengantar
                    $success_4 = true; // t_dokumen_penjualan
                    $model->load(Yii::$app->request->post());
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                            $modNota = \app\models\TNotaPenjualan::findOne(['spm_ko_id'=>$model->spm_ko_id]);
                            if(!empty($modNota)){
                                $modNota->kendaraan_nopol = $model->kendaraan_nopol;
                                $modNota->kendaraan_supir = $model->kendaraan_supir;
                                if($modNota->validate()){
                                    $success_2 = $modNota->save();
                                }
                            }
                            $modSp = \app\models\TSuratPengantar::findOne(['spm_ko_id'=>$model->spm_ko_id]);
                            if(!empty($modSp)){
                                $modSp->kendaraan_nopol = $model->kendaraan_nopol;
                                $modSp->kendaraan_supir = $model->kendaraan_supir;
                                if($modSp->validate()){
                                    $success_3 = $modSp->save();
                                }
                            }
                            $modDok = \app\models\TDokumenPenjualan::findOne(['spm_ko_id'=>$model->spm_ko_id]);
                            if(!empty($modDok)){
                                $modDok->kendaraan_nopol = $model->kendaraan_nopol;
                                $modDok->kendaraan_supir = $model->kendaraan_supir;
                                if($modDok->validate()){
                                    $success_4 = $modDok->save();
                                }
                            }
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
//                    echo "<pre>";
//                    print_r($success_1);
//                    echo "<pre>";
//                    print_r($success_2);
//                    echo "<pre>";
//                    print_r($success_3);
//                    echo "<pre>";
//                    print_r($success_4);
//                    exit;
                    if ($success_1 && $success_2 && $success_3 && $success_4) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data Berhasil Diupdate');
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
			return $this->renderAjax('editKecil',['model'=>$model]);
		}
	}
    
    public function actionListPaletTerima($produk_id,$op_ko_id,$spm_ko_id,$tr_seq=null,$nomor_palet_exist=null,$lihat=null){
		if(Yii::$app->request->isAjax){
			$modOp = \app\models\TOpKo::findOne($op_ko_id);
			$modOpDetail = \app\models\TOpKoDetail::findOne(['op_ko_id'=>$op_ko_id,'produk_id'=>$produk_id]);
			$models = \app\models\TTerimaJasa::find()->select("tanggal,nomor_palet")->where("op_ko_id = {$op_ko_id} ")->groupBy("tanggal,nomor_palet")->orderBy("tanggal ASC")->all();
			$modProduk = \app\models\MProdukJasa::findOne($produk_id);
            $nomor_palet_exist = str_replace("'", "", explode(",", $nomor_palet_exist));
			return $this->renderAjax('paletterima',['models'=>$models,'modProduk'=>$modProduk,'nomor_palet_exist'=>$nomor_palet_exist,'modOp'=>$modOp,'tr_seq'=>$tr_seq,'lihat'=>$lihat,'spm_ko_id'=>$spm_ko_id]);
		}
	}
    
    public function actionGetPaletisi(){
		if(Yii::$app->request->isAjax){
			$op_ko_id = Yii::$app->request->post("op_ko_id");
			$nomor_palet = Yii::$app->request->post("nomor_palet");
			$data['html'] = "";
			$data['tot_qty'] = 0;
			$data['tot_kubikasi'] = 0;
			if(!empty($nomor_palet)){
				$models = Yii::$app->db->createCommand("SELECT * FROM t_terima_jasa WHERE op_ko_id = {$op_ko_id} AND nomor_palet IN({$nomor_palet})")->queryAll();
				if(count($models)>0){
					foreach($models as $i => $model){
						$data['html'] .= "<tr>";
						$data['html'] .= "<td><center>".($i+1)."</center></td>";
						$data['html'] .= "<td style='text-align:center;'>".$model['nomor_palet']."</td>";
						$data['html'] .= "<td style='text-align:center;'>".$model['t'].$model['t_satuan']." x ".$model['l'].$model['l_satuan']." x ".$model['p'].$model['p_satuan']."</td>";
						$data['html'] .= "<td style='text-align:center;'>".$model['qty_kecil']."</td>";
						$data['html'] .= "<td style='text-align:right;'>".$model['kubikasi']."</td>";
						$data['html'] .= "</tr>";
						$data['tot_qty'] += $model['qty_kecil'];
						$data['tot_kubikasi'] += $model['kubikasi'];
					}
				}
                
                $modTerimaJasa = Yii::$app->db->createCommand("SELECT * FROM t_terima_jasa WHERE op_ko_id = {$op_ko_id} AND nomor_palet NOT IN({$nomor_palet})")->queryAll();
			}
			return $this->asJson($data);
		}
	}

###########################################################################################
	public function actionScanSpmLog(){
		$model = new TLogKeluar();
		$modSpmLog = new TSpmLog();
		$modPersediaan = new HPersediaanLog();
        if (isset($_GET['spm_ko_id'])) {
            $model->spm_ko_id = $_GET['spm_ko_id'];
        }
        return $this->render('scanspmlog',['model'=>$model, 'modSpmLog'=>$modSpmLog, 'modPersediaan'=>$modPersediaan]);
	}

	public function actionAddLogList(){
        if(Yii::$app->request->isAjax){
            $model = new TLogKeluar();
			$modSpmLog = new TSpmLog();
			$modPersediaan = new HPersediaanLog();
            $data['item'] = $this->renderPartial('_itemLogList',['model'=>$model, 'modSpmLog'=>$modSpmLog, 'modPersediaan'=>$modPersediaan]); //, 'modDetail'=>$modDetail
            return $this->asJson($data);
        }
    }

	public function actionGetCurrentLogList(){
        if(Yii::$app->request->isAjax){
			$kode_spm = Yii::$app->request->post('kode_spm');
			$status = Yii::$app->request->post('status');
			$modSpm = TSpmKo::findOne(['kode'=>$kode_spm]);
			$modSpmLog = TSpmLog::find()->where(['reff_no'=>$kode_spm])->all();
			$modLogKeluar = TLogKeluar::find()->where(['reff_no'=>$kode_spm])->all();
			$data = [];
			$data['item'] = "";
			$data['model'] = "";
			if(count($modLogKeluar)>0){
				foreach($modLogKeluar as $i => $keluar){
					$data['model'][] = $keluar->attributes;
					$modPersediaan = HPersediaanLog::findOne(['no_barcode'=>$keluar->no_barcode, 'reff_no'=>$kode_spm]);
					$modKayu = MKayu::findOne($modPersediaan['kayu_id']);
					if($status== TSpmKo::REALISASI){
						$modSpmLog = new TSpmLog();
						$modPersediaan = new HPersediaanLog();
						$modSpmKoDetail = TSpmKoDetail::findOne(['spm_ko_id' => $modSpm->spm_ko_id]);
						// $modBrgLog = MBrgLog::findOne(['log_id'=>$modSpmKoDetail['produk_id']]);
						$modPersediaan = HPersediaanLog::findOne(['no_barcode'=>$keluar->no_barcode, 'reff_no'=>$kode_spm]);
						$modSpmLog = TSpmLog::findOne(['reff_no'=>$keluar->reff_no, 'no_barcode'=>$keluar->no_barcode]);
						$modKayu = MKayu::findOne($modSpmLog->kayu_id);
						$data['item'] .= $this->renderPartial('_itemLogListRealisasi',['model'=>$keluar, 'modSpmLog'=>$modSpmLog, 'modSpmKoDetail'=>$modSpmKoDetail, 'modKayu'=>$modKayu, 'modPersediaan'=>$modPersediaan]);
					}else{
						$modSpmLog = new TSpmLog();
						$modPersediaan = new HPersediaanLog();
						$data['item'] .= $this->renderPartial('_itemLogList',['model'=>$keluar, 'i'=>$i, 'modPersediaan'=>$modPersediaan,'modSpmLog'=>$modSpmLog, 'modKayu'=>$modKayu]);
					}
				}
			}
            return $this->asJson($data);
        }
    }

	function actionSetItemLogList(){
		if(Yii::$app->request->isAjax){
            $no_barcode = Yii::$app->request->post('no_barcode');
            $op_ko_id = Yii::$app->request->post('op_ko_id');

            if(!empty($no_barcode)){
                $data['log'] = HPersediaanLog::findOne(['no_barcode'=>$no_barcode, 'status'=>'OUT']);
				if($data['log']['fsc']){
					$fsc = 1;
				} else {
					$fsc = 0;
				}
				$data['spm'] = \app\models\TSpmKo::findOne(['op_ko_id'=>$op_ko_id]);
				$produks = Yii::$app->db->createCommand(
								"SELECT * FROM m_brg_log WHERE kayu_id = {$data['log']['kayu_id']} AND {$data['log']['fisik_diameter']}
								BETWEEN range_awal AND range_akhir AND fsc = '{$fsc}'"
							)->queryOne();
				$data['produk'] = \app\models\MBrgLog::findOne($produks['log_id']);
				$data['kayu'] = \app\models\MKayu::findOne($data['log']['kayu_id']);
                $data['persediaan'] = HPersediaanLog::getDataByNoBarcode($no_barcode);
				$data['spmlog'] = TSpmLog::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$data['spm']['kode']]);
				$data['detail'] = '';
				$data['kubikasi_hasilhitung'] = 0;
				if(!empty($op_ko_id)){
					$modDetail = Yii::$app->db->createCommand("
								SELECT t_op_ko.* FROM t_op_ko
								JOIN t_op_ko_detail ON t_op_ko_detail.op_ko_id = t_op_ko.op_ko_id
								WHERE t_op_ko.op_ko_id = {$op_ko_id} AND t_op_ko_detail.produk_id = {$data['log']->kayu_id}
								")->queryAll();
					if(count($modDetail)>0){
						$det = [];
						foreach($modDetail as $ii => $det){
							$rand[] = $det;
						}
						$data['detail'] = Json::encode($det); 
					}
				}
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }

	public function actionFindStockLogActive(){
        if(Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$notinpost = json_decode( Yii::$app->request->get('notin') );
			$array_data = Yii::$app->request->get('data_log_nama');
			$data = []; $notin = "";
			$log_nama_fsc = []; $log_nama_non_fsc = [];

			foreach ($array_data as $item) {
				$trimmed_item = trim($item);
				$data[] = "'" . $trimmed_item . "'";

				// Pisahkan log yang mengandung FSC100% dan yang tidak
				if (strpos($trimmed_item, 'FSC100%') !== false) {
					$log_nama_fsc[] = "'" . $trimmed_item . "'";  // Menambahkan log yang mengandung FSC100%
				} else {
					$log_nama_non_fsc[] = "'" . $trimmed_item . "'";  // Menambahkan log yang tidak mengandung FSC100%
				}
			}
			// $log_nama = implode(', ', $data);
			$log_nama_fsc = implode(', ', $log_nama_fsc);  // Log dengan FSC100%
			$log_nama_non_fsc = implode(', ', $log_nama_non_fsc);  // Log tanpa FSC100%

			if(!empty($log_nama_fsc) && !empty($log_nama_non_fsc)){
				$where_log = "(
								log_nama IN ({$log_nama_fsc}) and h_persediaan_log.fsc = '1'
								or
								log_nama IN ({$log_nama_non_fsc}) and h_persediaan_log.fsc = '0'
								)";
			} else if (!empty($log_nama_fsc)){
				$where_log = "(
								log_nama IN ({$log_nama_fsc}) and h_persediaan_log.fsc = '1'
								)";
			} else if (!empty($log_nama_non_fsc)){
				$where_log = "(
								log_nama IN ({$log_nama_non_fsc}) and h_persediaan_log.fsc = '0'
								)";
			}
			
			if(!empty($notinpost)){
				$notin = "AND h_persediaan_log.no_barcode NOT IN(";
				foreach($notinpost as $i => $not){
					$notin .= "'$not'";
					if( ($i+1)!=(count($notinpost)) ){
						$notin .= ",";
					}
				}
				$notin .= ")";
			}
			if(!empty($term)){
				// $query = "
				// 	SELECT h_persediaan_log.kayu_id, no_barcode, 
				// 		SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) as stock
				// 	FROM h_persediaan_log 
				// 	JOIN m_brg_log ON m_brg_log.kayu_id = h_persediaan_log.kayu_id
				// 	WHERE no_grade <> '-' AND ".(!empty($term)?"no_barcode ILIKE '%".$term."%'":'')." AND h_persediaan_log.active IS TRUE 
				// 	GROUP BY h_persediaan_log.kayu_id, no_barcode
				// 	HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0
				// ";
				$query = "
					SELECT h_persediaan_log.kayu_id, h_persediaan_log.no_barcode, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) as stock
					FROM h_persediaan_log
					JOIN m_brg_log on m_brg_log.kayu_id = h_persediaan_log.kayu_id
					JOIN m_kayu on m_kayu.kayu_id = m_brg_log.kayu_id
					JOIN (SELECT no_barcode, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS total_stock FROM h_persediaan_log
							GROUP BY no_barcode HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0) s ON h_persediaan_log.no_barcode = s.no_barcode
					WHERE  no_grade <> '-' and m_brg_log.active = true 
							AND fisik_diameter BETWEEN range_awal AND range_akhir
							AND {$where_log} AND ".(!empty($term)?"h_persediaan_log.no_barcode ILIKE '%".$term."%'":'')." $notin
					GROUP BY h_persediaan_log.kayu_id, h_persediaan_log.no_barcode,m_kayu.group_kayu
					HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0
					ORDER BY m_kayu.group_kayu ASC
				";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['no_barcode'], 'text'=>$val['no_barcode']];
					}
				}
			}
            return $this->asJson($data);
        }
    }

	public function actionShowDetailLog(){
        $data['status']         = false;
        if(Yii::$app->request->isAjax){
			$spm_ko_id          = Yii::$app->request->post('spm_ko_id');
			$no_barcode     	= Yii::$app->request->post('no_barcode');
			$modSpm             = TSpmKo::findOne($spm_ko_id);
			$modPersediaan      = HPersediaanLog::getDataScanned($no_barcode);
			$modLogKeluar    	= TLogKeluar::find()
                                                ->where(['no_barcode' => $no_barcode])
                                                ->andWhere(['or', ['cancel_transaksi_id' => null]])
                                                ->one();
			$modKeluar = TLogKeluar::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$modSpm->kode]);
			$persediaan = HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
			$maxLength = 12; // Misalnya, kita hanya mengizinkan 12 karakter
			if ($this->isValidBarcode($_POST['datas']) && strlen($no_barcode) === $maxLength){
				if($modKeluar === null){
					if((!empty($persediaan)) ){
						$modBrgLog = Yii::$app->db->createCommand("
										SELECT * FROM m_brg_log 
										JOIN h_persediaan_log ON h_persediaan_log.kayu_id = m_brg_log.kayu_id 
										WHERE no_barcode = '{$no_barcode}'
										AND h_persediaan_log.kayu_id = {$persediaan['kayu_id']} 
										AND fisik_diameter BETWEEN range_awal AND range_akhir")->queryOne();
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
						$modPersediaan = \app\models\HPersediaanLog::findOne(['no_barcode'=>$model->no_barcode]);
						$modSpmKoDetail = \app\models\TSpmKoDetail::findOne(['spm_ko_id'=>$id]);
						$modBrgLog = \app\models\MBrgLog::findOne(['log_id'=>$modSpmKoDetail->produk_id]);
						$modKayu = \app\models\MKayu::findOne(['kayu_id'=>$modPersediaan->kayu_id]);
						$data['html'] .= $this->renderPartial('_itemScannedLogSpm',['model'=>$model, 'modBrgLog'=>$modBrgLog, 'modPersediaan'=>$modPersediaan, 'modKayu'=>$modKayu]);
					}
				}
            }
            return $this->asJson($data);
        }
    }

	public function actionReviewLog(){
        if(Yii::$app->request->isAjax){
            $spm_ko_id 		= $_GET['spm_ko_id'];
            $no_barcode 	= $_GET['no_barcode'];
            $modSpm 		= TSpmKo::findOne($spm_ko_id);
			$modPersediaan 	= HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
            $modLogKeluar 	= TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
			$modBrgLog 		= Yii::$app->db->createCommand(
									"SELECT * FROM h_persediaan_log 
									JOIN m_brg_log ON h_persediaan_log.kayu_id = m_brg_log.kayu_id 
									WHERE h_persediaan_log.kayu_id = {$modPersediaan->kayu_id} AND fisik_diameter BETWEEN range_awal 
									AND range_akhir AND no_barcode = '{$no_barcode}'"
								)->queryAll();
			$modKayu		= \app\models\MKayu::findOne(['kayu_id'=>$modPersediaan->kayu_id]);
            return $this->renderAjax('_reviewLog',[	'spm_ko_id'		=>$spm_ko_id, 
                                                	'no_barcode'	=>$no_barcode,
                                                	'modSpm'		=>$modSpm,
                                                	'modLogKeluar'	=>$modLogKeluar,
                                                	'modPersediaan'	=>$modPersediaan,
                                                	'modBrgLog'		=>$modBrgLog,
													'modKayu' 		=> $modKayu
                                                	]);
        }
    }

	public function actionSaveNoBarcode(){
		if(Yii::$app->request->isAjax){
			$data['status'] = false;
			$data['msg'] 	= "";
			$no_barcode 	= Yii::$app->request->post('no_barcode');
			$spm_ko_id 		= Yii::$app->request->post('spm_ko_id');
			$modSpm 		= TSpmKo::findOne($spm_ko_id);
			$modPersediaan  = HPersediaanLog::getDataScanned($no_barcode);
			$modLogKeluar   = TLogKeluar::find()
                                            ->where(['no_barcode' => $no_barcode])
                                            ->andWhere(['or', ['cancel_transaksi_id' => null]])
                                            ->one();
			$modKeluar 		= TLogKeluar::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$modSpm->kode]);
			$persediaan = HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
				$transaction = Yii::$app->db->beginTransaction();
				try {
					$success_1 = false; // t_produk_keluar

					if($modKeluar === null){
						if((!empty($persediaan)) ){
							$modBrgLog = Yii::$app->db->createCommand("
											SELECT * FROM m_brg_log 
											JOIN h_persediaan_log ON h_persediaan_log.kayu_id = m_brg_log.kayu_id 
											WHERE no_barcode = '{$no_barcode}'
											AND h_persediaan_log.kayu_id = {$persediaan['kayu_id']} 
											AND fisik_diameter BETWEEN range_awal AND range_akhir")->queryOne();
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
											// $modLogKeluar->attributes = $modProduksi->attributes;
											$modLogKeluar->attributes = $modPersediaan;
											// foreach($modLog as $i => $log){
											$modLogKeluar->kode = \app\components\DeltaGenerator::kodeLogKeluar($modBrgLog['log_kelompok']);
											// }
											$modLogKeluar->tanggal = date("Y-m-d");
											$modLogKeluar->cara_keluar = TLogKeluar::CARA_KELUAR_PENJUALAN;
											$modLogKeluar->reff_no = $modSpm->kode;
											$modLogKeluar->pic_log_keluar = Yii::$app->user->identity->pegawai_id;
											if($modLogKeluar->validate()){
												if($modLogKeluar->save()){
													$success_1 = true;
													$data['status'] = true;
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

					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['message'] = Yii::t('app', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
						Yii::$app->session->setFlash('success', 'Data berhasil disimpan!');
					} else {
						$transaction->rollback();
						$data['msg'] = "Data gagal tersimpan!";
						$data['status'] = false;
						$data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE);
					}
				} catch (Exception $ex) {
					$transaction->rollback();
					$data['message'] = $ex;
					$data['msg'] = "GAGAL!!!";
				}
			return $this->asJson($data);
		}
	}

	public function actionDeleteNoBarcode($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TLogKeluar::findOne($id);
            $modSpm = TSpmKo::findOne(['kode' => $model->reff_no]);
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
                    }else if($model->delete()) {
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

	public function actionSetOPScan(){
		if(Yii::$app->request->isAjax){
			$spm_ko_id = Yii::$app->request->post('spm_ko_id');
			$data = [];
			if(!empty($spm_ko_id)){
				$model = \app\models\TSpmKo::findOne($spm_ko_id);
				$modCust = \app\models\MCustomer::findOne($model->cust_id);
				if(!empty($model)){
					$data = $model->attributes;
					$data['tanggal_kirim'] = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
				}
				if(!empty($modCust)){
					$data['cust'] = $modCust->attributes;
					$data['cust']['cust_pr_nama'] = (!empty($modCust->cust_pr_nama)?$modCust->cust_pr_nama:"-");					
				}
			}
			return $this->asJson($data);
		}
	}

	function actionGetItemsScan(){
		if(Yii::$app->request->isAjax){
            $spm_ko_id = Yii::$app->request->post('spm_ko_id');
			$model = new TSpmKo();
			$modDetail = [];
            $modOpKo = [];
            $data = [];
            if(!empty($spm_ko_id)){
				$model = \app\models\TSpmKo::findOne($spm_ko_id);
                $modOpKo = \app\models\TOpKo::findOne($model->op_ko_id);
                $model->jenis_produk = $modOpKo->jenis_produk;
                $modOPDetail = \app\models\TOpKoDetail::find()->where(['op_ko_id'=>$modOpKo->op_ko_id])->all();
            }
            $data['html'] = '';
            if(count($modOPDetail)>0){
                foreach($modOPDetail as $i => $opdetail){
					$modDetail = new TSpmKoDetail();
					$modDetail->attributes = $opdetail->attributes;
					$modLog = \app\models\MBrgLog::findOne(['log_id'=>$opdetail->produk_id]);
                    $data['html'] .= $this->renderPartial('_itemOP',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i,'modOpDetail'=>$opdetail, 'modLog'=>$modLog]);
                }
            }
            return $this->asJson($data);
        }
    }

	public function actionInfoLog($no_barcode){
		if(Yii::$app->request->isAjax){
			$modLogKeluar = \app\models\TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
			$modPersediaan = \app\models\HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
			$modKayu = \app\models\MKayu::findOne($modPersediaan->kayu_id);
			$modBrgLog = Yii::$app->db->createCommand("
											SELECT * FROM m_brg_log WHERE kayu_id = {$modPersediaan->kayu_id} 
											AND {$modPersediaan->fisik_diameter} BETWEEN range_awal AND range_akhir
										")->queryOne();
			return $this->renderAjax('infoLog',['modLogKeluar'=>$modLogKeluar, 'no_barcode'=>$no_barcode, 'modPersediaan'=>$modPersediaan, 'modKayu'=>$modKayu, 'modBrgLog'=>$modBrgLog]);
        }
    }

	public function actionLogListOnModal($tr_seq=null,$jns_produk=null,$data_log_nama=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
				$array_data = explode(',', $data_log_nama);
				$data = [];
				$log_nama_fsc = [];
				$log_nama_non_fsc = [];

				foreach ($array_data as $item) {
					$trimmed_item = trim($item);
					$data[] = "'" . $trimmed_item . "'";

					// Pisahkan log yang mengandung FSC100% dan yang tidak
					if (strpos($trimmed_item, 'FSC100%') !== false) {
						$log_nama_fsc[] = "'" . $trimmed_item . "'";  // Menambahkan log yang mengandung FSC100%
					} else {
						$log_nama_non_fsc[] = "'" . $trimmed_item . "'";  // Menambahkan log yang tidak mengandung FSC100%
					}
				}
				// $log_nama = implode(', ', $data);
				$log_nama_fsc = implode(', ', $log_nama_fsc);  // Log dengan FSC100%
				$log_nama_non_fsc = implode(', ', $log_nama_non_fsc);  // Log tanpa FSC100%

				if(!empty($log_nama_fsc) && !empty($log_nama_non_fsc)){
					$where_log = "(
									log_nama IN ({$log_nama_fsc}) and h_persediaan_log.fsc = '1'
									or
									log_nama IN ({$log_nama_non_fsc}) and h_persediaan_log.fsc = '0'
								 )";
				} else if (!empty($log_nama_fsc)){
					$where_log = "(
									log_nama IN ({$log_nama_fsc}) and h_persediaan_log.fsc = '1'
								 )";
				} else if (!empty($log_nama_non_fsc)){
					$where_log = "(
									log_nama IN ({$log_nama_non_fsc}) and h_persediaan_log.fsc = '0'
								  )";
				}
				
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
										AND {$where_log} ";
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
		// Memisahkan data berdasarkan newline
		$lines = explode("\n", $data);
		
		// Memeriksa apakah ada karakter non-ASCII
		foreach ($lines as $line) {
			if (preg_match('/[^\x20-\x7E]/', $line)) {
				return false; 
			}
		}

		return true;
	}

	public function actionSetItemLogPelabuhan(){
		if(Yii::$app->request->isAjax){
			$terima_logalam_id = Yii::$app->request->post('terima_logalam_id');
			if (!empty($terima_logalam_id)) {
				$details = \app\models\TTerimaLogalamDetail::find()->where(['terima_logalam_id' => $terima_logalam_id])->all();
					if (!empty($details)) {
						$data = [];
						foreach ($details as $i =>$detail) {
							$kayu = \app\models\MKayu::findOne($detail->kayu_id);
							if($detail->fsc){
								$fsc = 'true';
							} else {
								$fsc = 'false';
							}
							$produks = Yii::$app->db->createCommand(
								"SELECT * FROM m_brg_log WHERE kayu_id = {$detail->kayu_id} AND {$detail->diameter_rata}
								BETWEEN range_awal AND range_akhir AND fsc = $fsc"
							)->queryOne();

							$data[] = [
								'no_urut' => $i+1,
								'kayu_nama' => $kayu->group_kayu . '<br>' . $kayu->kayu_nama,
								'no_barcode' => $detail->no_barcode,
								'no_lap' => $detail->no_lap,
								'no_grade' => $detail->no_grade,
								'no_btg' => $detail->no_btg,
								'panjang' => $detail->panjang,
								'diameter_ujung1' => $detail->diameter_ujung1,
								'diameter_ujung2' => $detail->diameter_ujung2,
								'diameter_pangkal1' => $detail->diameter_pangkal1,
								'diameter_pangkal2' => $detail->diameter_pangkal2,
								'diameter_rata' => $detail->diameter_rata,
								'cacat_panjang' => $detail->cacat_panjang,
								'cacat_gb' => $detail->cacat_gb,
								'cacat_gr' => $detail->cacat_gr,
								'volume' => $detail->volume,
								'produk_id'=> $produks['log_id'],
								'kode_potong'=>$detail->kode_potong,
								'kayu_id'=>$detail->kayu_id,
								'no_produksi'=>$detail->no_produksi
							];
						}
					}
					return $this->asJson($data);
			}
    	}
	}

	public function actionValidatingKubikasi(){
		if(\Yii::$app->request->isAjax){
			$produks = Yii::$app->request->post('produks');
			$edit = Yii::$app->request->post('edit');
			$spm_id = Yii::$app->request->post('spm_id');
			$data = [];
			$modSpm = TSpmKo::findOne($spm_id);
			$modOp = TOpKo::findOne($modSpm->op_ko_id);
			$id = $modOp->po_ko_id;
			$op_id = $modSpm->op_ko_id;

			// cari po_ko_detail dari produk yg dipilih
			$data['post'] = [];
			foreach($produks as $i => $prod){
				$produk_id = $prod['produk_id'];
				$kubikasi = $prod['kubikasi'];
				$modSpm = TSpmKo::findOne($spm_id);
				$modOp = TOpKo::findOne($modSpm->op_ko_id);
				$modPoDet = TPoKoDetail::findOne(['po_ko_id'=>$id, 'produk_id'=>$produk_id]);
				if(empty($modPoDet)){
					$modPoDet = Yii::$app->db->createCommand("
									SELECT * FROM t_po_ko_detail WHERE po_ko_id = $id and $produk_id = ANY (string_to_array(produk_id_alias, ',')::int[])
									")->queryOne();
					$poDetail = $modPoDet['po_ko_detail_id'];
				} else {
					$poDetail = $modPoDet->po_ko_detail_id;
				}

				if (isset($data['post'][$poDetail])) {
					$data['post'][$poDetail]['kubikasi'] += $kubikasi;
				} else {
					$data['post'][$poDetail] = [
						'po_ko_detail_id' => $poDetail,
						'kubikasi' => $kubikasi,
					];
				}
			}

			// kumpulkan data yg sudah ada di OP
			$data['spm'] = [];
			$modSpm = Yii::$app->db->createCommand("SELECT * FROM t_spm_ko
													JOIN t_op_ko ON t_op_ko.op_ko_id = t_spm_ko.op_ko_id
													JOIN t_po_ko ON t_po_ko.po_ko_id = t_op_ko.po_ko_id
													WHERE t_po_ko.po_ko_id = $id AND t_spm_ko.status ='REALISASI'
													")->queryAll();
			if(count($modSpm) > 0){
				foreach($modSpm as $i => $op){
					$modSpmDet = TOpKoDetail::findAll(['op_ko_id'=>$op['op_ko_id']]);
					foreach($modSpmDet as $ii => $opdet){
						$produk_id_spm = $opdet['produk_id'];
						$kubikasi_spm = $opdet['kubikasi'];
						$modPoDet_spm = TPoKoDetail::findOne(['po_ko_id'=>$id, 'produk_id'=>$produk_id_spm]);
						if(empty($modPoDet_spm)){
							$modPoDet_spm = Yii::$app->db->createCommand("
											SELECT * FROM t_po_ko_detail WHERE po_ko_id = $id and $produk_id_spm = ANY (string_to_array(produk_id_alias, ',')::int[])
											")->queryOne();
							$poDetail_spm = $modPoDet_spm['po_ko_detail_id'];
						} else {
							$poDetail_spm = $modPoDet_spm->po_ko_detail_id;
						}

						if (isset($data['spm'][$poDetail_spm])) {
							$data['spm'][$poDetail_spm]['kubikasi'] += $kubikasi_spm;
						} else {
							$data['spm'][$poDetail_spm] = [
								'po_ko_detail_id' => $poDetail_spm,
								'kubikasi' => $kubikasi_spm,
							];
						}
					}
				}
			}

			// maksimal yg digunakan patokan
			$data['maks'] = [];
			$model = TPoKoDetail::findAll(['po_ko_id'=>$id]);
			foreach($model as $m => $mod){
				$po_ko_detail_id = $mod['po_ko_detail_id'];
				$data['maks'][$po_ko_detail_id] = ['po_ko_detail_id'=>$po_ko_detail_id, 'kubikasi'=>$mod['kubikasi']];
			}

			return $this->asJson($data);
		}
	}
}