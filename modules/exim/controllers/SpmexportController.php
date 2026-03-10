<?php

namespace app\modules\exim\controllers;

use app\components\DeltaGenerator;
use app\components\Params;
use app\models\TApproval;
use app\models\TPengajuanManipulasi;
use app\models\TSpmKo;
use app\models\TSpmKoDetail;
use Yii;
use app\controllers\DeltaBaseController;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Response;

class SpmexportController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TSpmKo();
        $model->kode = 'Auto Generate';
        $model->dibuat = Yii::$app->user->identity->pegawai_id;
        $model->dibuat_display = Yii::$app->user->identity->pegawai->pegawai_nama;
        $model->disetujui = \app\components\Params::DEFAULT_PEGAWAI_ID_BUDI_ADHI;
        
        if(isset($_GET['spm_ko_id'])){
            $model = \app\models\TSpmKo::findOne($_GET['spm_ko_id']);
			$modPackinglist = \app\models\TPackinglist::findOne($model->packinglist_id);
			$modPackinglistContainer = \app\models\TPackinglistContainer::findOne(['packinglist_id'=>$model->packinglist_id,'container_no'=>$model->container_no]);
			$modOpExport = \app\models\TOpExport::findOne($modPackinglist->op_export_id);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_rencanamuat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_rencanamuat);
            $model->tanggal_kirim = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
            $model->waktu_selesaimuat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->waktu_selesaimuat);
			$model->jenis_produk = $modOpExport->jenis_produk;
			$model->packinglist_id = $modPackinglist->packinglist_id."-".$model->container_no;
			$model->nomor = $modPackinglist->nomor." - Container ".$model->container_no;
			$model->shipment_to = $modOpExport->shipment_to;
			$model->port_of_loading = $modOpExport->port_of_loading;
			$model->final_destination = $modPackinglist->final_destination;
			$model->container_kode = $modPackinglistContainer->container_kode;
			$model->seal_no = $modPackinglistContainer->seal_no;
        }
		
        if( Yii::$app->request->post('TSpmKo')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_spm_ko
                $success_2 = true; // t_spm_ko_detail
                $success_3 = true; // t_produk_keluar
                $success_4 = true; // h_persediaan_produk
                $success_5 = true; // t_packinglist_container update
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeSpm($_POST['TSpmKo']['jenis_produk']);
				}
				$model->op_ko_id = 999999;
				$model->alamat_bongkar = $_POST['TSpmKo']['final_destination'];
				$post = explode("-", $model->packinglist_id);
				$model->packinglist_id = $post[0];
				$model->container_no = $post[1];
				$model->jenis_penjualan = "export";
				if(!empty($model->waktu_selesaimuat)){
					if (strpos($model->waktu_selesaimuat, '-') !== false) {
						$model->waktu_selesaimuat = explode(" - ", $model->waktu_selesaimuat);
						$model->waktu_selesaimuat = $model->waktu_selesaimuat[0]." ".$model->waktu_selesaimuat[1].":00";
					}
				}
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if((isset($_GET['edit'])) && (isset($_GET['spm_ko_id']))){
							// exec ini jika proses edit
							$modDetail = \app\models\TSpmKoDetail::find()->where(['spm_ko_id'=>$_GET['spm_ko_id']])->all();
							if(count($modDetail)>0){
								\app\models\TSpmKoDetail::deleteAll(['spm_ko_id'=>$_GET['spm_ko_id']]);
							}
						}
						foreach($_POST['TSpmKoDetail'] as $i => $detail){
							$modDetail = new \app\models\TSpmKoDetail();
							$modDetail->attributes = $detail;
							$modDetail->spm_ko_id = $model->spm_ko_id;
							$modDetail->qty_kecil = \app\components\DeltaFormatter::formatNumberForDb2($modDetail->qty_kecil);
							$modDetail->harga_jual_realisasi = $modDetail->harga_jual;
							if($modDetail->validate()){
								if($modDetail->save()){
									$success_2 &= true;
									
									// SPM Realisasi nye
									if(isset($_POST['TProdukKeluar'])){
										foreach($_POST['TProdukKeluar'] as $i => $detail){
											if($detail['produk_id']==$modDetail->produk_id){
												$modProdukKeluar = \app\models\TProdukKeluar::findOne(['nomor_produksi'=>$detail['nomor_produksi']]);
												$modProdukKeluar->reff_detail_id = $modDetail->spm_kod_id;
												$modProdukKeluar->gudang_id = $detail['gudang_id'];
												if($modProdukKeluar->validate()){
													if($modProdukKeluar->save()){
														$success_3 &= true;
														
														if(\app\models\HPersediaanProduk::getCurrentStockPerPalet($detail['nomor_produksi'])['palet']>0){
															// Start Proses Update Stock (OUT)
															$modPersediaan = new \app\models\HPersediaanProduk();
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
															$success_4 &= \app\models\HPersediaanProduk::updateStokPersediaan($modPersediaan);
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
								$errmsg = $modDetail->errors;
							}
						}
						
						// update Packinglist Container
						$modContainers = \app\models\TPackinglistContainer::find()->where("packinglist_id = {$model->packinglist_id} AND container_no = {$model->container_no}")->all();
						if(count($modContainers)>0){
							foreach($modContainers as $i => $container){
								$container->attributes = $detail;
								$container->container_kode = $_POST['TSpmKo']['container_kode'];
								$container->seal_no = $_POST['TSpmKo']['seal_no'];
								if($container->validate()){
									if($container->save()){
										$success_5 &= true;
									}
								}else{
									$success_5 = false;
								}
							}
						}
						// end update
                    }
                }
				
//				echo "<pre>1";
//
//
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
				
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'spm_ko_id'=>$model->spm_ko_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?( is_array($errmsg)?implode(",", array_values($errmsg)[0]):$errmsg ):Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionOpenPackinglist(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-op'){
				$param['table']= \app\models\TPackinglistContainer::tableName();
				$param['pk']= $param['table'].".".\app\models\TPackinglistContainer::primaryKey()[0];
				$param['column'] = ['t_packinglist.packinglist_id', 
									't_packinglist.jenis_produk', 
									'cust_an_nama', 
									't_op_export.kode' ,
									'nomor_kontrak', 
									'nomor', 
									't_packinglist.tanggal', 
									$param['table'].'.container_no', 
									'MAX(bundles_no) AS bundles_total', 
									'SUM(pcs) AS pcs_total',
									'SUM(volume) AS volume_total'];
				$param['join']= ['JOIN t_packinglist ON t_packinglist.packinglist_id = t_packinglist_container.packinglist_id 
								  JOIN t_op_export ON t_op_export.op_export_id = t_packinglist.op_export_id
								  JOIN m_customer ON m_customer.cust_id = t_packinglist.cust_id
								  LEFT JOIN t_spm_ko ON t_spm_ko.packinglist_id = t_packinglist.packinglist_id '];
				$param['where']= "t_packinglist.cancel_transaksi_id IS NULL AND t_packinglist.status = 'FINAL'";
				$param['group'] = "GROUP BY t_packinglist.packinglist_id, 
									t_packinglist.jenis_produk, 
									cust_an_nama, 
									t_op_export.kode, 
									nomor_kontrak, 
									nomor, 
									t_packinglist.tanggal, 
									".$param['table'].".container_no";
				$param['order'] = "t_packinglist.packinglist_id DESC, ".$param['table'].".container_no DESC";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('packinglist');
        }
	}
	
	public function actionSetOP(){
		if(\Yii::$app->request->isAjax){
			$post = \Yii::$app->request->post('packinglist_id');
			$data = [];
			if(!empty($post)){
				$post = explode("-", $post);
				$packinglist_id = $post[0]; $cont_no = $post[1]; 
				if(!empty($packinglist_id) && !empty($cont_no)){
					$modContainer = \app\models\TPackinglistContainer::find()->where(['packinglist_id'=>$packinglist_id,'container_no'=>$cont_no])->all();
					$modPackinglist = \app\models\TPackinglist::findOne($packinglist_id);
					$modOpEx = \app\models\TOpExport::findOne($modPackinglist->op_export_id);
					if(!empty($modPackinglist)){
						$data['packinglist'] = $modPackinglist->attributes;
						$data['packinglist']['buyer'] = $modPackinglist->cust->cust_an_nama."\n".$modPackinglist->cust->cust_an_alamat;
						if(!empty($modPackinglist->notify_party)){
							$data['packinglist']['applicant'] = $data['packinglist']['buyer'];
							$data['packinglist']['notify_party'] = $modPackinglist->notifyParty->cust_an_nama."\n".$modPackinglist->notifyParty->cust_an_alamat;
						}
						$modCustomer = \app\models\MCustomer::findOne($modPackinglist->cust_id);
						if(!empty($modCustomer)){
							$data['cust'] = $modCustomer->attributes;
						}
					}
				}
			}
			return $this->asJson($data);
		}
	}
	
	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $post = \Yii::$app->request->post('packinglist_id');
			$modDetail = []; $data = []; $data['html'] = '';
			if(!empty($post)){
				$post = explode("-", $post);
				$packinglist_id = $post[0]; $cont_no = $post[1];
				$model = new \app\models\TSpmKo();
				$modPackinglist = \app\models\TPackinglist::findOne($packinglist_id);
				if(!empty($packinglist_id) && !empty($cont_no)){
					if($modPackinglist->bundle_partition == true){
						$sql = "SELECT packinglist_id, container_no, grade, jenis_kayu, glue, profil_kayu, kondisi_kayu, max(bundles_no) AS bundles, SUM(pcs) AS pcs, SUM(volume) AS volume, SUM( ROUND(volume::numeric,4) ) AS volume_display
								FROM t_packinglist_container
								WHERE packinglist_id = {$packinglist_id} AND container_no = {$cont_no}
								GROUP by 1,2,3,4,5,6,7
								ORDER by 1,2 ASC";
					}else{
						$sql = "SELECT packinglist_id, container_no, grade, jenis_kayu, glue, profil_kayu, kondisi_kayu, thick, thick_unit, length, length_unit, width, width_unit, count(packinglist_id) AS bundles, SUM(pcs) AS pcs, SUM(volume) AS volume, SUM( ROUND(volume::numeric,4) ) AS volume_display
								FROM t_packinglist_container
								WHERE packinglist_id = {$packinglist_id} AND container_no = {$cont_no}
								GROUP by 1,2,3,4,5,6,7,8,9,10,11,12,13
								ORDER by 1,2 ASC";
					}
					$modContainer = Yii::$app->db->createCommand($sql)->queryAll();
					if(count($modContainer)>0){
						foreach($modContainer as $i => $container){
							$condition = []; $produkorder =""; $garing="";
							if($modPackinglist->opExport->jenis_produk == "Plywood" || $modPackinglist->opExport->jenis_produk == "Lamineboard" || $modPackinglist->opExport->jenis_produk == "Platform"){
								$produkorder = $modPackinglist->opExport->jenis_produk;
								$garing = "/";
							}
							$condition['produk_group'] = $modPackinglist->opExport->jenis_produk;
							if(!empty($container['jenis_kayu'])){
								$condition['jenis_kayu'] = $container['jenis_kayu'];
								$produkorder .= $garing.$container['jenis_kayu'];
							}
							if(!empty($container['grade'])){
								$condition['grade'] = $container['grade'];
								$produkorder .= "/".$container['grade'];
							}
							if(!empty($container['glue'])){
								$condition['glue'] = $container['glue'];
								$produkorder .= "/".$container['glue'];
							}
							if(!empty($container['profil_kayu'])){
								$condition['profil_kayu'] = $container['profil_kayu'];
								$produkorder .= "/".$container['profil_kayu'];
							}
							if(!empty($container['kondisi_kayu'])){
								$condition['kondisi_kayu'] = $container['kondisi_kayu'];
								$produkorder .= "/".$container['kondisi_kayu'];
							}
							if(!empty($container['thick'])){
								$condition['produk_t'] = $container['thick'];
								$produkorder .= "/".$container['thick'];
							}
							if(!empty($container['thick_unit'])){
								$condition['produk_t_satuan'] = $container['thick_unit'];
							}
							if(!empty($container['width'])){
								$condition['produk_l'] = $container['width'];
								$produkorder .= $container['width'];
							}
							if(!empty($container['width_unit'])){
								$condition['produk_l_satuan'] = $container['width_unit'];
							}
							if(!empty($container['length'])){
								$condition['produk_p'] = $container['length'];
								$produkorder .= $container['length'];
							}
							if(!empty($container['length_unit'])){
								$condition['produk_p_satuan'] = $container['length_unit'];
							}
							if($modPackinglist->bundle_partition == true){
								$modUkuranThick = Yii::$app->db->createCommand("SELECT thick, thick_unit
																				FROM t_packinglist_container
																				WHERE packinglist_id = {$packinglist_id} AND container_no = {$cont_no}
																				GROUP by 1,2
																				ORDER by 1,2 ASC")->queryAll();
								$modUkuranWidth = Yii::$app->db->createCommand("SELECT width, width_unit
																				FROM t_packinglist_container
																				WHERE packinglist_id = {$packinglist_id} AND container_no = {$cont_no}
																				GROUP by 1,2
																				ORDER by 1,2 ASC")->queryAll();
								$modUkuranLength = Yii::$app->db->createCommand("SELECT length, length_unit
																				FROM t_packinglist_container
																				WHERE packinglist_id = {$packinglist_id} AND container_no = {$cont_no}
																				GROUP by 1,2
																				ORDER by 1,2 ASC")->queryAll();
								if(count($modUkuranThick)>1){
									$produkorder .= "/0";
									$condition['produk_t'] = "0";
								}else{
									$produkorder .= "/".$modUkuranThick[0]['thick'];
									$condition['produk_t'] = $modUkuranThick[0]['thick'];
								}
								if(count($modUkuranWidth)>1){
									$produkorder .= "0";
									$condition['produk_l'] = "0";
								}else{
									$produkorder .= $modUkuranWidth[0]['width'];
									$condition['produk_l'] = $modUkuranWidth[0]['width'];
								}
								if(count($modUkuranLength)>1){
									$produkorder .= "0";
									$condition['produk_p'] = "0";
								}else{
									$produkorder .= $modUkuranLength[0]['length'];
									$condition['produk_p'] = $modUkuranLength[0]['length'];
								}
								$condition['produk_t_satuan'] = $modUkuranThick[0]['thick_unit'];
								$condition['produk_l_satuan'] = $modUkuranWidth[0]['width_unit'];
								$condition['produk_p_satuan'] = $modUkuranLength[0]['length_unit'];
								
							}
							$sqlbund = "SELECT bundles_no FROM t_packinglist_container 
										WHERE packinglist_id = ".$packinglist_id." 
												AND container_no = ".$cont_no." 
												".(!empty($condition['grade'])?"AND grade='{$condition['grade']}'":"")."
												".(!empty($condition['jenis_kayu'])?"AND jenis_kayu='{$condition['jenis_kayu']}'":"")."
												".(!empty($condition['glue'])?"AND glue='{$condition['glue']}'":"")."
												".(!empty($condition['profil_kayu'])?"AND profil_kayu='{$condition['profil_kayu']}'":"")."
												".(!empty($condition['kondisi_kayu'])?"AND kondisi_kayu='{$condition['kondisi_kayu']}'":"")."
												".(!empty($condition['produk_t'])?"AND thick ='{$condition['produk_t']}'":"")."
												".(!empty($condition['produk_t_satuan'])?"AND thick_unit='{$condition['produk_t_satuan']}'":"")."
												".(!empty($condition['produk_l'])?"AND width='{$condition['produk_l']}'":"")."
												".(!empty($condition['produk_l_satuan'])?"AND width_unit='{$condition['produk_l_satuan']}'":"")."
												".(!empty($condition['produk_p'])?"AND length='{$condition['produk_p']}'":"")."
												".(!empty($condition['produk_p_satuan'])?"AND length_unit='{$condition['produk_p_satuan']}'":"")."
										GROUP BY bundles_no
										ORDER BY bundles_no";
							$searchbund = \Yii::$app->db->createCommand($sqlbund)->queryAll();
							$produkorder = str_replace(" ", "", $produkorder);
							$modDetail = new \app\models\TSpmKoDetail();
							$modProduk = \app\models\MBrgProduk::findOne($condition);
							if(!empty($modProduk)){
								$modDetail->produk_id = $modProduk->produk_id;
							}
							$modDetail->qty_besar = count($searchbund);
							$modDetail->satuan_besar = "Palet";
							$modDetail->qty_kecil = $container['pcs'];
							$modDetail->satuan_kecil = "Pcs";
							$modDetail->kubikasi = $container['volume_display'];
							$modDetail->kubikasi_display = $container['volume_display'];
							$modDetail->harga_hpp = 0;
							$modDetail->harga_jual = 0;
							$modDetail->harga_jual_realisasi = 0;
							$data['html'] .= $this->renderPartial('_itemOP',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i,'is_random'=>$modPackinglist->bundle_partition,'produkorder'=>$produkorder]);
						}
					}
				}
			}
			
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TSpmKo::tableName();
				$param['pk']= $param['table'].".".\app\models\TSpmKo::primaryKey()[0];
				$param['column'] = [
                    $param['table'].'.spm_ko_id',
                    $param['table'].'.kode',
                    't_packinglist.jenis_produk',
                    't_op_export.nomor_kontrak',
                    't_packinglist.nomor',
                    $param['table'].'.tanggal',
                    'm_customer.cust_an_nama',
                    $param['table'].'.tanggal_kirim',
                    $param['table'].'.kendaraan_nopol',
                    $param['table'].'.kendaraan_supir',
                    $param['table'].'.alamat_bongkar',
                    $param['table'].'.cancel_transaksi_id',
                    $param['table'].'.status',
                    $param['table'].'.container_no',
                    't_pengajuan_manipulasi.status AS status_manipulasi'
                ];
				$param['join']= ['
				    JOIN t_packinglist ON t_packinglist.packinglist_id = '.$param['table'].'.packinglist_id
                    JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
					JOIN t_op_export ON t_op_export.op_export_id = t_packinglist.op_export_id
				    LEFT JOIN t_pengajuan_manipulasi ON t_pengajuan_manipulasi.reff_no = t_spm_ko.kode AND t_pengajuan_manipulasi.status = \'PROCESS\'
				'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL AND jenis_penjualan = 'export'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionListRandom($produk_id,$post){
		$post = explode("-", $post);
		$packinglist_id = $post[0]; $cont_no = $post[1];
		if(\Yii::$app->request->isAjax){
			$sql = "SELECT * FROM t_packinglist_container 
					WHERE packinglist_id = {$packinglist_id} AND container_no = {$cont_no}
					ORDER BY 1";
			$models = Yii::$app->db->createCommand($sql)->queryAll();
			$modProduk = \app\models\MBrgProduk::findOne($produk_id);
			return $this->renderAjax('random',['models'=>$models,'modProduk'=>$modProduk]);
		}
	}
	
	function actionGetItemsById(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $realisasi = Yii::$app->request->post('realisasi');
            $edit = Yii::$app->request->post('edit');
            $post = Yii::$app->request->post('post');
			$model = \app\models\TSpmKo::findOne($id);
			$modPackinglist = \app\models\TPackinglist::findOne($model->packinglist_id);
			$post = explode("-", $post);
			$cont_no = $post[1];
			$modDetail = [];
            $data = [];
            if(!empty($id)){
                $modDetail = \app\models\TSpmKoDetail::find()->where(['spm_ko_id'=>$id])->all();
            }
            $data['html'] = '';
            if(count($modDetail)>0){
                foreach($modDetail as $i => $detail){
					$detail->kubikasi_display = $detail->kubikasi;
					if($realisasi){
						if($model->status!= \app\models\TSpmKo::REALISASI){
							$detail->qty_besar_realisasi = 0;
							$detail->satuan_besar_realisasi = $detail->satuan_besar;
							$detail->qty_kecil_realisasi = 0;
							$detail->satuan_kecil_realisasi = $detail->satuan_kecil;
							$detail->kubikasi_realisasi = 0;
							$modProdukkeluar = \app\models\TProdukKeluar::find()->where(['reff_no'=>$model->kode,'produk_id'=>$detail->produk_id])->all();
							if(count($modProdukkeluar)){
								$detail->satuan_besar_realisasi = $modProdukkeluar[0]->satuan_besar;
								$detail->satuan_kecil_realisasi = $modProdukkeluar[0]->satuan_kecil;
								$detail->kubikasi_realisasi += $detail->kubikasi; // Biar gak pusing samakan kayak sebelum realisasi
								foreach($modProdukkeluar as $ii => $keluar){
									$detail->qty_besar_realisasi += $keluar->qty_besar;
									$detail->qty_kecil_realisasi += $keluar->qty_kecil;
//									$detail->kubikasi_realisasi += $keluar->kubikasi;
								}
							}
						}
                        $detail->qty_kecil = number_format($detail->qty_kecil);
                        $data['html'] .= $this->renderPartial('_itemOP',['model'=>$model,'modDetail'=>$detail,'i'=>$i,'realisasi'=>$realisasi,'is_random'=>$modPackinglist->bundle_partition]);
					}else{
                        $detail->qty_kecil = number_format($detail->qty_kecil);
						$data['html'] .= $this->renderPartial('_itemOP',['model'=>$model,'modDetail'=>$detail,'i'=>$i,'realisasi'=>$realisasi,'is_random'=>$modPackinglist->bundle_partition]);
					}
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionGetCurrentProdukList(){
        if(\Yii::$app->request->isAjax){
			$kode_spm = Yii::$app->request->post('kode_spm');
			$status = Yii::$app->request->post('status');
			$post = explode("-", Yii::$app->request->post('post'));
			$packinglist_id = $post[0]; $cont_no = $post[1];
            $modProdukKeluar = \app\models\TProdukKeluar::find()->where(['reff_no'=>$kode_spm])->all();
			$modSpm = \app\models\TSpmKo::findOne(['kode'=>$kode_spm]);
			$data = [];
			$data['item'] = "";
			$data['model'] = "";
			if(count($modProdukKeluar)>0){
				foreach($modProdukKeluar as $i => $keluar){
					$data['model'][] = $keluar->attributes;
					if($status== \app\models\TSpmKo::REALISASI){
						$data['item'] .= $this->renderPartial('_itemProdukListRealisasi',['model'=>$keluar]);
					}else{
						$modPackinglist = \app\models\TPackinglist::findOne($packinglist_id);
						$data['item'] .= $this->renderPartial('_itemProdukList',['model'=>$keluar]);
					}
				}
			}
            return $this->asJson($data);
        }
    }
	
	public function actionPrintSPM(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TSpmKo::findOne($_GET['id']);
		$modDetail = \app\models\TSpmKoDetail::find()->where(['spm_ko_id'=>$_GET['id']])->all();
		$modPackinglist = \app\models\TPackinglist::findOne(['packinglist_id'=>$model->packinglist_id]);
		$modOpexport = \app\models\TOpExport::findOne(['op_export_id'=>$modPackinglist->op_export_id]);
		$modContainer = \app\models\TPackinglistContainer::find()->where(['packinglist_id'=>$model->packinglist_id,'container_no'=>$model->container_no])->all();
		$model->container_kode  = !empty($modContainer[0]->container_kode)?$modContainer[0]->container_kode:"";
		$model->seal_no  = !empty($modContainer[0]->seal_no)?$modContainer[0]->seal_no:"";
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'SURAT PERINTAH MUAT');
		if($caraprint == 'PRINT'){
			return $this->render('printSPM',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail,'modPackinglist'=>$modPackinglist,'modContainer'=>$modContainer,'modOpexport'=>$modOpexport]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printSPM',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail,'modPackinglist'=>$modPackinglist,'modContainer'=>$modContainer,'modOpexport'=>$modOpexport]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printSPM',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail,'modPackinglist'=>$modPackinglist,'modContainer'=>$modContainer,'modOpexport'=>$modOpexport]);
		}
	}

    /**
     * @param $id
     * @return string|void|Response
     * @throws Exception
     */
    public function actionHapusSPMExport($id)
    {
        if(Yii::$app->request->isAjax) {
            $model  = new TPengajuanManipulasi();
            $modSpm = TSpmKo::findOne(['spm_ko_id' => $id]);

            if(Yii::$app->request->isPost) {
                $transaction = Yii::$app->db->beginTransaction();
                $data = [];
                $success1 = false;
                $success2 = false;
                $success3 = false;
                try {
                    $model->kode        = DeltaGenerator::kodeAjuanManipulasiData();
                    $model->tipe        = 'PERMINTAAN HAPUS SPM EXPORT';
                    $model->tanggal     = date('Y-m-d');
                    $model->reff_no     = $modSpm->kode;
                    $model->datadetail1 = Json::encode($modSpm);
                    $model->datadetail2 = Json::encode(TSpmKoDetail::findAll(['spm_ko_id' => $id]));
                    $model->priority    = 'NORMAL';
                    $model->status      = 'PROCESS';
                    $model->reason      = Yii::$app->request->post('TPengajuanManipulasi')['reason'];
                    $model->created_by  = Yii::$app->user->identity->pegawai->pegawai_id;
                    $model->updated_by  = Yii::$app->user->identity->pegawai->pegawai_id;
                    $model->approver1   = Params::DEFAULT_PEGAWAI_ID_ROCHANDRA;
                    $model->approver2   = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $approvers[0] = ['pegawai_id' => $model->approver1, 'level' => 1];
                    $approvers[1] = ['pegawai_id' => $model->approver2, 'level' => 2];
                    if($modSpm->status === TSpmKo::REALISASI) {
                        $model->approver3   = Params::DEFAULT_PEGAWAI_ID_ASENG;
                        $model->approver4   = Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                        $approvers[3] = ['pegawai_id' => $model->approver3, 'level' => 3];
                        $approvers[4] = ['pegawai_id' => $model->approver4, 'level' => 4];
                    }

                    if($model->validate()) {
                        if($model->save()) {
                            $success1 = true;
                        }
                    }else {
                        $data['status'] = false;
                        $data['message'] = $model->getFirstError('reason');
                        return $this->asJson($data);
                    }

                    $build = [];
                    foreach ($approvers as $approval) {
                        $modApprove                 = new TApproval();
                        $modApprove->assigned_to    = $approval['pegawai_id'];
                        $modApprove->reff_no        = $modSpm->kode;
                        $modApprove->tanggal_berkas = date('Y-m-d');
                        $modApprove->level          = $approval['level'];
                        $modApprove->status         = TApproval::STATUS_NOT_CONFIRMATED;
                        $modApprove->active         = true;
                        $modApprove->parameter1     = $model->kode;
                        $build[] = [
                            'pegawai_id' => $approval['pegawai_id'],
                            'tanggal' => null,
                            'status' => TApproval::STATUS_NOT_CONFIRMATED,
                            'level' => $approval['level'],
                            'reason' => ''
                        ];
                        if($modApprove->validate()) {
                            if($modApprove->save()) {
                                $success2 = true;
                            }
                        }else if($transaction !== null) {
                            $transaction->rollBack();
                            $data['status'] = false;
                            $data['message'] = 'Ada masalah saat membuat approval';
                            return $this->asJson($data);
                        }
                    }

                    $model->reason_approval = Json::encode($build);
                    if($model->save()) {
                        $success3 = true;
                    }

                    if($success1 && $success2 && $success3 && $transaction !== null) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = 'Transaksi Berhasil. Menunggu Approval...';
                        $data['callback'] = 'setTimeout(() => window.location.reload(), 500)';
                        return $this->asJson($data);
                    }
                }catch (Exception $exception) {
                    $transaction->rollBack();
                    $data['status'] = false;
                    $data['message'] = $exception->getMessage();
                    return $this->asJson($data);
                }
            }
            return $this->renderAjax('_reasondihapus', compact('model', 'modSpm'));
        }

    }

}
