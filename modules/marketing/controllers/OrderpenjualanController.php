<?php

namespace app\modules\marketing\controllers;

use app\components\DeltaFormatter;
use app\components\Params;
use app\models\TAttachment;
use app\models\TOpKo;
use app\models\TOpKoDetail;
use app\models\TOpKoDetailJasa;
use app\models\TTempobayarKo;
use Yii;
use app\controllers\DeltaBaseController;
use app\models\MBrgLog;
use app\models\MCustomer;
use app\models\MSales;
use app\models\TPoKo;
use app\models\TPoKoDetail;
use app\models\TTerimaLogalam;
use yii\db\Exception;
use yii\web\Response;

class OrderpenjualanController extends DeltaBaseController
{

	public $defaultAction = 'index';

    /**
     * @return string|Response
     * @throws Exception
     * @throws \yii\base\Exception
     * @version 2022-03-01
     * @note cuma dirapikan
     */
    public function actionIndex(){
        $model                      = new TOpKo();
        $model->kode                = 'Auto Generate';
		// $model->tanggal          = date('d/m/Y');
        $model->syarat_jual         = "Loco";
        $model->cara_bayar          = "Transfer Bank";
		$model->disetujui           = Params::DEFAULT_PEGAWAI_ID_FITRIYANAH;
		$modTempo                   = new TTempobayarKo();
		$modTempo->top_hari         = 0;
		$modTempo->maks_top_hari    = 0;
		$modTempo->op_aktif         = 0;
		$modAttachment              = new TAttachment();
        $model->jenis_produk        = 'Limbah';
        $model->tanggal             = date('d/m/Y');
        $model->tanggal_kirim       = date('d/m/Y');
        $model->provinsi_bongkar    = 'JAWA TENGAH';
		$cust_id = Yii::$app->request->get('cust_id');
		
		/*$sql_kode = "select kode from m_harga_produk a ".
					"	left join m_brg_produk b on b.produk_id = a.produk_id ".
					"	where a.harga_tanggal_penetapan <= current_date ".
					"	and b.produk_group = '".$model->jenis_produk."' ".
					"	and a.status_approval = 'APPROVED' ".
					"	order by a.harga_tanggal_penetapan desc, kode desc ".
					"	limit 1 ".
					"	";
        $kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();
        */
        $sql_kode = "select kode from m_harga_produk a ".
                    "	left join m_brg_produk b on b.produk_id = a.produk_id ".
                    "	where a.harga_tanggal_penetapan <= current_date ".
                    "	and b.produk_group = '".$model->jenis_produk."' ".
                    "	and a.status_approval = 'APPROVED' ".
                    "	order by a.harga_tanggal_penetapan desc ".
                    "	limit 1 ".
                    "	";

        $kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();

        if(isset($_GET['op_ko_id'])){
            $model                  = TOpKo::findOne($_GET['op_ko_id']);
            $model->tanggal         = DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->tanggal_kirim   = DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
			$model->tanggal_po      = DeltaFormatter::formatDateTimeForUser2($model->tanggal_po);
            $model->customer        = $model->cust->cust_an_nama. (!empty($model->cust->cust_pr_nama)?" - ".$model->cust->cust_pr_nama:"");
            $modTempoBayar          = TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);
            $modDetail              = TOpKoDetail::findAll($_GET['op_ko_id']);
            $modDetailJasa          = TOpKoDetailJasa::findAll($_GET['op_ko_id']);
			// $modTerimaLogalam		= TTerimaLogalam::findOne($model->terima_logalam_id);
			// $model->kode_logalam	= $modTerimaLogalam['kode'];
			if(!empty($model->po_ko_id)){
				$modPoKo = TPoKo::findOne($model->po_ko_id);
				$model->kode_po_ko      = $modPoKo->kode;
			} else {
				$model->kode_po_ko      = '';
			}
			

            if ($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding") {
                $cekDetail  =  Yii::$app->db->createCommand("select * from t_op_ko_detail where op_ko_id = ".$_GET['op_ko_id']."")->queryAll();
                $beda       = 0;
                foreach ($cekDetail as $kolom) {
                    $produk_id  = $kolom['produk_id'];
                    $qty_besar  = $kolom['qty_besar'];
                    $qty_kecil  = $kolom['qty_kecil'];
                    $qtyBesar   = Yii::$app->db->createCommand("select qty_besar from t_op_ko_detail_jasa where op_ko_id = ".$_GET['op_ko_id']." and produk_id = ".$produk_id."")->queryScalar();
                    $qtyKecil   = Yii::$app->db->createCommand("select qty_kecil from t_op_ko_detail_jasa where op_ko_id = ".$_GET['op_ko_id']." and produk_id = ".$produk_id."")->queryScalar();
                    $qty_besar == $qtyBesar && $qty_kecil == $qtyKecil ? $beda = 0 : $beda = 1;
                    $beda += $beda;
                }
            } else {
                $beda = 0;
            }

			$file   = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>1, 'active'=>'true']);
			$file1  = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>2, 'active'=>'true']);
			$file2  = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>3, 'active'=>'true']);
			$file3  = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>4, 'active'=>'true']);
			$file4  = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>5, 'active'=>'true']);
			$file5  = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>6, 'active'=>'true']);

			if(!empty($modTempoBayar)){
				$modTempo->attributes = $modTempoBayar->attributes;
				$modTempo->maks_plafon = DeltaFormatter::formatNumberForUserFloat($modTempo->maks_plafon);
				$modTempo->sisa_piutang = DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_piutang);
				$modTempo->sisa_plafon = DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_plafon);
            }
        } else {
			$file = '';
			$file1 = '';
			$file2 = '';
			$file3 = '';
			$file4 = '';
            $file5 = '';
            $modDetail = '';
            $modDetailJasa = '';
            $beda = '';
		}

        if( Yii::$app->request->post('TOpKo')){

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;     // t_op_ko
                $success_2 = true;      // t_op_ko_detail
                $success_3 = false;     // t_tempobayar_ko
                $success_4 = false;     // t_approval
                $success_5 = true;      // t_spm_detail
                $success_6 = true;      // t_op_ko_random
                $success_7 = true;      // t_terima_jasa
                $success_8 = false;     // t_attachment
                $success_9 = false;     //t t_op_ko_detail_jasa

                $model->load(\Yii::$app->request->post());

                isset($model->status) ? $status = $model->status : $status = '';
                $status = str_replace("Status : ", "", $status);
                $model->status = $status;

				// jika log maka tidak perlu approval
				if($_POST['TOpKo']['jenis_produk'] == 'Log'){
					$model->status_approval = "APPROVED";
				} else {
					if (!empty($model->status)) {
						$model->status_approval = "Not Confirmed";
					} else {
						$model->status_approval = "APPROVED";
					}
				}
				

				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::orderPenjualan($model->jenis_produk);
				}

				$model->gambar_po = \yii\web\UploadedFile::getInstance($model, 'gambar_po');

                if($model->validate()){
					$model->po = $_POST['TOpKo']['po'];
					$po_ko = $_POST['TOpKo']['po_ko_id'];
					if(!empty($po_ko)){
						$modPo = TPoKo::findOne($po_ko);
						$model->tanggal_po = $modPo->tanggal_po;
					} else {
						$model->tanggal_po = $_POST['TOpKo']['tanggal_po'];
					}

                    if($model->save()){
						$success_1 = true;

						if(isset($_GET['edit'])){ // jika proses edit
                            if( count( \app\models\TTerimaJasa::find()->where("op_ko_id = ".$model->op_ko_id)->all() ) > 0 ){
                                $success_7 = (\app\models\TTerimaJasa::deleteAll("op_ko_id = ".$model->op_ko_id))? true:false;
                            } else {
                                $success_7 = true;
                            }
                            foreach(TOpKoDetail::findAll(['op_ko_id'=>$model->op_ko_id]) as $i => $detail){
                                if($detail->is_random==1){
                                    $modRandom = \app\models\TOpKoRandom::findAll(['op_ko_detail_id'=>$detail->op_ko_detail_id]);
                                    if(count($modRandom)){
                                        $success_6 = (\app\models\TOpKoRandom::deleteAll("op_ko_detail_id = {$detail->op_ko_detail_id}"))?true:false;
                                    }
                                }
                            }
                            $success_2 = (TOpKoDetail::deleteAll("op_ko_id = ".$model->op_ko_id))?true:false;
                            $success_3 = (TTempobayarKo::deleteAll("op_ko_id = ".$model->op_ko_id))?true:false;
                            $success_4 = (\app\models\TApproval::deleteAll("reff_no = '".$model->kode."'"))?true:false;
							$modSpms = \app\models\TSpmKo::find()->where(['op_ko_id'=>$model->op_ko_id])->all();

							if((count($modSpms)>0) && $model->jenis_produk != "JasaKD" && $model->jenis_produk != "JasaGesek" && $model->jenis_produk != "JasaMoulding" ){
								foreach($modSpms as $i => $spm){
									$modSPMDetailOld = \app\models\TSpmKoDetail::find()->where("spm_ko_id = ".$spm->spm_ko_id)->all();
									if(count($modSPMDetailOld)>0){
										$success_5 = (\app\models\TSpmKoDetail::deleteAll("spm_ko_id = ".$spm->spm_ko_id))?true:false;
										foreach($_POST['TOpKoDetail'] as $ii => $detail){
											$modSpmDetail = new \app\models\TSpmKoDetail();
											$modSpmDetail->attributes = $detail;
											$modSpmDetail->spm_ko_id = $spm->spm_ko_id;

											foreach($modSPMDetailOld as $iii => $spmdetailold){
												if($spmdetailold->produk_id == $detail['produk_id']){
													$modSpmDetail->qty_besar_realisasi = $spmdetailold->qty_besar_realisasi;
													$modSpmDetail->satuan_besar_realisasi = $spmdetailold->satuan_besar_realisasi;
													$modSpmDetail->qty_kecil_realisasi = $spmdetailold->qty_kecil_realisasi;
													$modSpmDetail->satuan_kecil_realisasi = $spmdetailold->satuan_kecil_realisasi;
													$modSpmDetail->kubikasi_realisasi = $spmdetailold->kubikasi_realisasi;
													$modSpmDetail->harga_jual_realisasi = $spmdetailold->harga_jual_realisasi;
												}
											}
											if($modSpmDetail->validate()){
												if($modSpmDetail->save()){
													$success_5 &= true;
												}else{
													$success_5 = false;
												}
											}else{
												$success_5 = false;
											}
										}
									}
								}
							}
						}

						foreach($_POST['TOpKoDetail'] as $i => $detail){
							$modDetail = new TOpKoDetail();
							$modDetail->attributes = $detail;
							$modDetail->op_ko_id = $model->op_ko_id;
							$modDetail->is_random = isset($detail['is_random'])?$detail['is_random']:false;
							$modDetail->subtotal = DeltaFormatter::formatNumberForDb2($_POST['TOpKoDetail'][$i]['subtotal']);
							$modDetail->harga_jual = DeltaFormatter::formatNumberForDb2($_POST['TOpKoDetail'][$i]['harga_jual']);

                            if($modDetail->validate()){
								if($modDetail->save()){
                                    // insert t_op_ko_detail_jasa
                                    // cek jenis produk dulu
                                    if ($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding") {
                                        // cek t_op_ko_detail_jasa
                                        $sql_jumlah_t_op_ko_detail_jasa = "select count(*) from t_op_ko_detail_jasa where op_ko_id = ".$modDetail->op_ko_id." and produk_id = ".$modDetail->produk_id."";
                                        $jumlah_t_op_ko_detail_jasa = Yii::$app->db->createCommand($sql_jumlah_t_op_ko_detail_jasa)->queryScalar();
                                        if ($jumlah_t_op_ko_detail_jasa < 1) {
                                            $modDetailJasa = new TOpKoDetailJasa();
                                            $modDetailJasa->attributes = $detail;
                                            $modDetailJasa->op_ko_id = $model->op_ko_id;
                                            $modDetailJasa->is_random = isset($detail['is_random'])?$detail['is_random']:false;
                                            $success_9 = true;
                                            $modDetailJasa->save();
                                        } else {
                                            $success_9 = true;
                                        }
                                    } else {
                                        $success_9 = true;
                                    }

									$success_2 &= true;
									if(!empty($detail['nomor_produksi_random'])){
										$sql = "SELECT *,t_terima_ko_kd.p AS panjang, t_terima_ko_kd.l AS lebar, t_terima_ko_kd.t AS tinggi FROM t_terima_ko_kd 
												JOIN t_terima_ko ON t_terima_ko.tbko_id = t_terima_ko_kd.tbko_id 
												WHERE t_terima_ko.nomor_produksi IN({$detail['nomor_produksi_random']}) 
												ORDER BY tbko_kd_id ASC";
										$masterRandom = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($masterRandom)>0){
											foreach($masterRandom as $ii => $modRand){
												$modOpRandom = new \app\models\TOpKoRandom();
												$modOpRandom->attributes = $modRand;
												$modOpRandom->p = $modRand['panjang'];
												$modOpRandom->l = $modRand['lebar'];
												$modOpRandom->t = $modRand['tinggi'];
												$modOpRandom->op_ko_detail_id = $modDetail->op_ko_detail_id;
												$modOpRandom->qty_kecil = $modRand['qty'];
												$modOpRandom->satuan_kecil = $modRand['qty_satuan'];
												$modOpRandom->kubikasi = $modRand['kapasitas_kubikasi'];
												if($modOpRandom->validate()){
													if($modOpRandom->save()){
														$success_6 &= true;
													}else{
														$success_6 = false;
													}
												}else{
													$success_6 = false;
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
						// print_r($modDetail);exit;

                        if(isset($_GET['edit'])){ // jika proses edit
                            // start penerimaan jasa
                            if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaMoulding" || $model->jenis_produk == "JasaGesek"){
                                if(isset($_POST['TTerimaJasa'])){
                                    foreach($_POST['TTerimaJasa'] as $i => $terima){
                                        $modTerimaJasa = new \app\models\TTerimaJasa();
                                        $modTerimaJasa->attributes = $terima;
                                        $modTerimaJasa->jenis = $model->jenis_produk;
                                        $modTerimaJasa->op_ko_id = $model->op_ko_id;
                                        $modTerimaJasa->satuan_kecil = "Pcs";
                                        $modTerimaJasa->op_ko_detail_id = $modDetail->op_ko_detail_id;
                                        if($modTerimaJasa->validate()){
                                            if($modTerimaJasa->save()){
                                                $success_7 &= true;
                                            }else{
                                                $success_7 = false;
                                            }
                                        }else{
                                            $success_7 = false;
                                        }
                                    }
                                }
                            }
                            // end terima jasa
                        }

						if($model->sistem_bayar == "Tempo"){
							$modTempo = new TTempobayarKo();
							$modTempo->attributes = $_POST['TTempobayarKo'];
							$modTempo->op_ko_id = $model->op_ko_id;
							$modTempo->kode = \app\components\DeltaGenerator::tempoBayarKayuolahan();
							$modTempo->jenis_produk = $model->jenis_produk;
							if($modTempo->validate()){
								if($modTempo->save()){
									$success_3 = true;
								}else{
									$success_3 = false;
								}
							}else{
								$success_3 = false;
							}
						}else{
							$success_3 = true;
						}

						// jika log maka tidak perlu approval
						if($model->jenis_produk == 'Log'){
							$success_4 = true;
						} else {
							if(!empty($model->status)){
								$this->saveApproval($model);
								$success_4 = true;
							} else {
								$success_4 = true;
							}
						}

						if($model->jenis_produk !== "Log"){
							$sizes = 0;
							foreach ($_FILES['TAttachment']['size'] as $size) {
								$sizes = $sizes + $size;
							}

							// jika jenis produk adalah semua jasa dan gambar po tidak kosong
							if(($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding") && !empty($model->tanggal_po))  {
								$dir_path = Yii::$app->basePath.'/web/uploads/mkt/po';

								if (isset($_FILES['TAttachment']) == 1 && $sizes > 0) {
									$files = [];
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file1');
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file2');
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file3');
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file4');
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file5');
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file6');

									foreach($files as $i => $file){
										if(!empty($file)) {
											$modAttachment = new TAttachment();
											$modAttachment->reff_no = $model->kode;
											$modAttachment->file_type = $file->type;
											$modAttachment->file_ext = $file->extension;
											$modAttachment->file_size = $file->size;
											$modAttachment->dir_path = $dir_path;
											$modAttachment->seq = ($i+1);
											$randomstring_attch = Yii::$app->getSecurity()->generateRandomString(4);

											if(!is_dir($dir_path)) {
												mkdir($dir_path);
											}

											$file_path = date('Ymd_His').'-attch-'.$randomstring_attch.'.'  . $file->extension;
											$file->saveAs($dir_path.'/'.$file_path);
											$modAttachment->file_name = $file_path;

											$sql_cek = "select count(*) from t_attachment where reff_no = '".$model->kode."' and seq = ".$modAttachment->seq." ";
											$query_cek = Yii::$app->db->createCommand($sql_cek)->queryScalar();

											if ($query_cek > 0) {
												// cek file_name
												$sql_attachment_id = "select attachment_id from t_attachment where reff_no = '".$model->kode."' and seq = ".$modAttachment->seq." ";
												$attachment_id = Yii::$app->db->createCommand($sql_attachment_id)->queryScalar();

												$sql_file_name = "select file_name from t_attachment where attachment_id = ".$attachment_id."";
												$file_name = Yii::$app->db->createCommand($sql_file_name)->queryScalar();

												// hapus file
												$dir_path = Yii::$app->basePath.'\web\uploads\mkt\po';
												unlink($dir_path.'\\'.$file_name);

												// hapus database
												$sql_delete = "delete from t_attachment where attachment_id = ".$attachment_id."";
												$query_delete = Yii::$app->db->createCommand($sql_delete)->execute();

												//$sql_na = "update t_attachment set active = 'false' where reff_no = '".$model->kode."' and seq = ".$modAttachment->seq." ";
												//$query_na = Yii::$app->db->createCommand($sql_na)->execute();
											}

											$modAttachment->validate();
											$modAttachment->save();
											$success_8 = true;
										}
									}
								} else {
									$sql_jumlah_t_attachment = "select count(*) from t_attachment where active = 'true' and reff_no = '".$model->kode."' ";
									$jumlah_t_attachment = Yii::$app->db->createCommand($sql_jumlah_t_attachment)->queryScalar();
									if ($jumlah_t_attachment > 0) {
										$success_8 = true;
										$success_8 = true;
									} else {
										$success_8 = false;
									}
								}

							} else {
								// jika bukan jasa
								$success_8 = true;
								$dir_path = Yii::$app->basePath.'/web/uploads/mkt/po';
								if(isset($_FILES['TAttachment'])){
									$files = [];
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file1');
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file2');
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file3');
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file4');
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file5');
									$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file6');

									foreach($files as $i => $file){
										if(!empty($file)){
											$modAttachment = new TAttachment();
											$modAttachment->reff_no = $model->kode;
											$modAttachment->file_type = $file->type;
											$modAttachment->file_ext = $file->extension;
											$modAttachment->file_size = $file->size;
											$modAttachment->dir_path = $dir_path;
											$modAttachment->seq = ($i+1);
											$randomstring_attch = Yii::$app->getSecurity()->generateRandomString(4);
											if(!is_dir($dir_path)){ 
												mkdir(Yii::$app->basePath.'/web/uploads/mkt');
												mkdir($dir_path); 
											}
											$file_path = date('Ymd_His').'-attch-'.$randomstring_attch.'.'  . $file->extension;
											$file->saveAs($dir_path.'/'.$file_path);
											$modAttachment->file_name = $file_path;

											$sql_cek = "select count(*) from t_attachment where reff_no = '".$model->kode."' and seq = ".$modAttachment->seq." ";
											$query_cek = Yii::$app->db->createCommand($sql_cek)->queryScalar();

											if ($query_cek > 0) {
												$sql_na = "update t_attachment set active = 'false' where reff_no = '".$model->kode."' and seq = ".$modAttachment->seq." ";
												$query_na = Yii::$app->db->createCommand($sql_na)->execute();
											}

											$modAttachment->validate();
											$modAttachment->save();
										}
									}
								}
							}
						} else {
							if(!isset($_GET['edit'])){
								if(isset($_POST['TAttachment'])){
									$dir_path = Yii::$app->basePath.'/web/uploads/mkt/po';
									foreach($_POST['TAttachment'] as $i => $attachment){
										if(!empty($attachment)){
											$modelAttach = TAttachment::findOne($attachment);
											if(!empty($modelAttach)){
												$modAttachment = new TAttachment();
												$modAttachment->reff_no = $model->kode;
												$modAttachment->file_type = $modelAttach->file_type;
												$modAttachment->file_ext = $modelAttach->file_ext;
												$modAttachment->file_size = $modelAttach->file_size;
												$modAttachment->dir_path = $dir_path;
												$modAttachment->seq = ($i+1);
												$modAttachment->active = true;
												$randomstring_attch = Yii::$app->getSecurity()->generateRandomString(4);
												if(!is_dir($dir_path)){ 
													mkdir(Yii::$app->basePath.'/web/uploads/mkt');
													mkdir($dir_path); 
												}
												$file_path = date('Ymd_His').'-attch-'.$randomstring_attch.'.'  . $modelAttach->file_ext;
												$folder_purchaseorder = Yii::$app->basePath . '/web/uploads/mkt/purchaseorder/' . $modelAttach->file_name;
												$folder_op = $dir_path . '/' . $file_path;
												
												if (copy($folder_purchaseorder, $folder_op)) {
													$modAttachment->file_name = $file_path;
	
													$sql_cek = "select count(*) from t_attachment where reff_no = '" . $model->kode . "' and seq = " . $modAttachment->seq;
													$query_cek = Yii::$app->db->createCommand($sql_cek)->queryScalar();
	
													$modAttachment->validate();
													$success_8 = $modAttachment->save();
												}
											}
										}
									}
								}
							} else {
								$success_8 = true;
							}
							
						}
						
                    }
                }
				// print_r($model->status_approval);exit;
				
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5 && $success_6 && $success_7 && $success_8 && $success_9) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
					return $this->redirect(['index','success'=>1,'op_ko_id'=>$model->op_ko_id]);
                    /*$transaction->rollback();
                    $errmsg = $model->status_approval;
					//$errmsg = "1".$success_1."\n2".$success_2."\n3".$success_3."\n4".$success_4."\n5".$success_5."\n6".$success_6."\n7".$success_7."\n8".$success_8;
					Yii::$app->session->setFlash('error', !empty($errmsg)? $errmsg : Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));*/
				} else {
                    $transaction->rollback();
					Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
					$errmsg = "1".$success_1."\n2".$success_2."\n3".$success_3."\n4".$success_4."\n5".$success_5."\n6".$success_6."\n7".$success_7."\n8".$success_8."\n9".$success_9;
					Yii::$app->session->setFlash('error', !empty($errmsg)? $errmsg : Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }

		return $this->render('index',['beda'=>$beda, 'model'=>$model,'modDetail'=>$modDetail,'modDetailJasa'=>$modDetailJasa,'modTempo'=>$modTempo,'modAttachment'=>$modAttachment,'file'=>$file,'file1'=>$file1,'file2'=>$file2,'file3'=>$file3,'file4'=>$file4,'file5'=>$file5, 'cust_id'=>$cust_id]); //
	
	}

    public function saveApproval($model){
		$success = true;

		if (trim($model->status) == "Low Price (2)") {

			//approval 2 : kadiv marketing (iwan sulistyo 19) dan fat (supriyadi)
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO; // iwan sulistyo
			$modelApproval->reff_no = trim($model->kode);
			$modelApproval->tanggal_berkas = date("Y-m-d");
			$modelApproval->level = 1;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();

			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = params::DEFAULT_PEGAWAI_ID_SUPRIYADI_INTERNALCONTROL; // supriyadi
			$modelApproval->reff_no = trim($model->kode);
			$modelApproval->tanggal_berkas = date("Y-m-d");
			$modelApproval->level = 3;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();

		} else {

			//approval 3 : dir (heryanto suwardi 22) dan kadiv marketing (iwan sulistyo 19) dan fat (supriyadi)
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;; // iwan sulistyo
			$modelApproval->reff_no = trim($model->kode);
			$modelApproval->tanggal_berkas = date("Y-m-d");
			$modelApproval->level = 1;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();

            $modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = params::DEFAULT_PEGAWAI_ID_ASENG; // heryanto suwardi
			$modelApproval->reff_no = trim($model->kode);
			$modelApproval->tanggal_berkas = date("Y-m-d");
			$modelApproval->level = 2;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();

			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = params::DEFAULT_PEGAWAI_ID_SUPRIYADI_INTERNALCONTROL; // supriyadi_internal control
			$modelApproval->reff_no = trim($model->kode);
			$modelApproval->tanggal_berkas = date("Y-m-d");
			$modelApproval->level = 3;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
        return $success;
    }

	public function actionSetCustomer(){
		if(\Yii::$app->request->isAjax){
			$cust_id = \Yii::$app->request->post('cust_id');
			$data = [];
			if(!empty($cust_id)){
				$model = \app\models\MCustomer::findOne($cust_id);
				if(!empty($model)){
					$data = $model->attributes;
				}
			}

			return $this->asJson($data);
		}
    }

	public function actionPickProduk(){ 
		if(\Yii::$app->request->isAjax){
			$produk_id = \Yii::$app->request->post('produk_id');
			$jns_produk = \Yii::$app->request->post('jns_produk');
			$data = [];
			if(!empty($produk_id)){
                if($jns_produk == "Limbah"){
                    $model = \app\models\MBrgLimbah::findOne($produk_id);
                    $data = (!empty($model))? $model->attributes:null;
                    $data['produk_id'] = $model->limbah_id;
                }else if($jns_produk == "JasaKD" || $jns_produk == "JasaGesek" || $jns_produk == "JasaMoulding"){
                    $model = \app\models\MProdukJasa::findOne($produk_id);
                    $data = (!empty($model))? $model->attributes:null;
                    $data['produk_id'] = $model->produk_jasa_id;
                } else if($jns_produk == "Log"){
					$model = \app\models\MBrgLog::findOne($produk_id);
                    $data = (!empty($model))? $model->attributes:null;
                    $data['produk_id'] = $model->log_id;
				}else{
                    $model = \app\models\MBrgProduk::findOne($produk_id);
                    $data = (!empty($model))? $model->attributes:null;
                }
			}
			return $this->asJson($data);
		}
    }

	public function actionSetVal(){
		if(\Yii::$app->request->isAjax){
			$cust_id = \Yii::$app->request->post('cust_id');
			$jns_produk = \Yii::$app->request->post('jns_produk');
			$op_ko_id = \Yii::$app->request->post('op_ko_id');
			$po_ko_id = \Yii::$app->request->post('po_ko_id');
			$data = [];
			$data['sisa_piutang']= 0;
			$data['op_aktif']= 0;
			$data['sisa_plafon']= 0;
			$data['maks_plafon']= 0;
			$data['top_hari']= 0;
			$data['maks_top_hari']= 0;
			if(!empty($cust_id)){
				$model = \app\models\MCustomer::findOne($cust_id);
				if(!empty($model)){
					$data['cust'] = $model->attributes;
					$modTop = \app\models\MCustTop::findOne(['cust_id'=>$cust_id,'custtop_jns'=>$jns_produk]);
					if(!empty($modTop)){
						$data['top_hari']= $modTop->custtop_top;
						$data['maks_top_hari']= $modTop->custtop_top;
					}
					if(!empty($po_ko_id)){
						$modpo = TPoKo::findOne($po_ko_id);
						$data['top_hari']= $modpo->top_hari;
					}
					$data['sisa_piutang']= \app\models\MCustomer::getSisaPiutang($cust_id);
					$data['op_aktif']= \app\models\MCustomer::getOPAktif($cust_id);
					$data['sisa_plafon']= \app\models\MCustomer::getSisaPlafon($cust_id) - $data['op_aktif'];
					$data['maks_plafon']= $model->cust_max_plafond;
					if(!empty($op_ko_id)){
						$modTempo = TTempobayarKo::findOne(['op_ko_id'=>$op_ko_id]);
						if(!empty($modTempo)){
							$data['sisa_piutang']= $modTempo->sisa_piutang;
							$data['op_aktif']= $modTempo->op_aktif;
							$data['sisa_plafon']= $modTempo->sisa_plafon;
							$data['maks_plafon']= $modTempo->maks_plafon;
							$data['top_hari']= $modTempo->top_hari;
							$data['maks_top_hari']= $modTempo->maks_top_hari;
						}
					}
				}
			}
			$data['sisa_plafon'] = ($data['sisa_plafon']>0)?$data['sisa_plafon']:0;
			return $this->asJson($data);
		}
	}

	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new TOpKoDetail();
            $modProduk = new \app\models\MBrgProduk();
            $jns_produk = Yii::$app->request->post('jns_produk');
			$po_ko_id = Yii::$app->request->post('po_ko_id');
            $modDetail->harga_jual = 0;
            $modDetail->subtotal = 0;
            $data['item'] = $this->renderPartial('_addItem',['modDetail'=>$modDetail,'modProduk'=>$modProduk,'jns_produk'=>$jns_produk, 'po_ko_id'=>$po_ko_id]);
            return $this->asJson($data);
        }
    }

    public function actionAddItemTerima(){
        if(\Yii::$app->request->isAjax){
            $op_ko_id = Yii::$app->request->post('op_ko_id');
            $model = TOpKo::findOne($op_ko_id);
            $modDetail = new \app\models\TTerimaJasa();
            $data['item'] = $this->renderPartial('_addItemTerima',['modOp'=>$model,'modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }

	public function actionFindProdukActive(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$jns_produk = Yii::$app->request->get('type');
			$po_ko_id = Yii::$app->request->get('po_ko_id');

			$sql_kode = "select kode from m_harga_produk a ".
							"	left join m_brg_produk b on b.produk_id = a.produk_id ".
							"	where a.harga_tanggal_penetapan <= current_date ".
							"	and b.produk_group = '".$jns_produk."' ".
							"	and a.status_approval = 'APPROVED' ".
							"	order by a.harga_tanggal_penetapan desc, kode desc ".
							"	limit 1 ".
							"	";
			$kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();

			$notin = json_decode( Yii::$app->request->get('notin') );
			$data = [];
			if(!empty($notin)){
				$notin = 'AND '.( ($jns_produk == "Limbah")?"m_brg_limbah.limbah_id":"m_brg_produk.produk_id" ).' NOT IN('.implode(", ", $notin).')';
			}else{
				$notin = "";
			}
			if(!empty($term)){
                if($jns_produk == "Limbah"){
                    // $query = "
                    //     SELECT m_brg_limbah.limbah_id AS produk_id, m_brg_limbah.limbah_kode AS produk_kode, CONCAT('(',m_brg_limbah.limbah_produk_jenis,') ',m_brg_limbah.limbah_nama) AS produk_nama FROM m_brg_limbah
                    //     WHERE (m_brg_limbah.limbah_kode ILIKE '%".$term."%' OR m_brg_limbah.limbah_nama ILIKE '%".$term."%') AND m_brg_limbah.active IS TRUE
                    //     ORDER BY m_brg_limbah.limbah_id ASC
                    //     ;
                    // ";
                    $tanggal    = \app\models\MHargaLimbah::getTanggalBerlaku();
                    $query      = "
                        SELECT
                            m_brg_limbah.limbah_id AS produk_id,
                            CONCAT ( m_brg_limbah.limbah_kelompok, ' (', m_brg_limbah.limbah_produk_jenis, ')' ) AS produk_nama,
                            m_brg_limbah.limbah_kode AS produk_kode,
                            m_brg_limbah.limbah_nama,
                            m_brg_limbah.limbah_satuan_muat 
                        FROM
                            m_brg_limbah
                            JOIN m_harga_limbah ON m_harga_limbah.limbah_id = m_brg_limbah.limbah_id 
                        WHERE
                            m_brg_limbah.active IS TRUE 
                            AND m_harga_limbah.harga_tanggal_penetapan = '$tanggal' 
                            AND m_harga_limbah.status_approval = 'APPROVED' 
                            AND m_harga_limbah.harga_enduser > 0 
                            AND ( m_brg_limbah.limbah_kode ILIKE'%$term%' OR m_brg_limbah.limbah_nama ILIKE'%$term%' ) 
                        ORDER BY
                            m_brg_limbah.limbah_id ASC
                    ";
                }else if($jns_produk == "JasaKD" || $jns_produk == "JasaGesek" || $jns_produk == "JasaMoulding"){
                    $query = "
                        SELECT m_produk_jasa.produk_jasa_id AS produk_id, m_produk_jasa.kode AS produk_kode, CONCAT('(',m_produk_jasa.jenis,') ',m_produk_jasa.nama) AS produk_nama FROM m_produk_jasa
                        WHERE (m_produk_jasa.kode ILIKE '%".$term."%' OR m_produk_jasa.nama ILIKE '%".$term."%') AND m_produk_jasa.active IS TRUE
                        ORDER BY m_produk_jasa.produk_jasa_id ASC 
                        ;
                    ";
                } else if ($jns_produk == "Log"){
					$query = "
							SELECT t_po_ko_detail.produk_id, m_brg_log.log_nama as produk_nama, m_brg_log.log_kode AS produk_kode, m_brg_log.log_nama, m_brg_log.log_satuan_jual
							FROM t_po_ko_detail
							JOIN m_brg_log on m_brg_log.log_id = t_po_ko_detail.produk_id
							JOIN m_kayu on m_kayu.kayu_id = m_brg_log.kayu_id
							LEFT JOIN h_persediaan_log on h_persediaan_log.kayu_id = m_brg_log.kayu_id 
									AND h_persediaan_log.fisik_diameter BETWEEN m_brg_log.range_awal AND m_brg_log.range_akhir
							WHERE  t_po_ko_detail.po_ko_id = $po_ko_id and no_grade <> '-' AND ( m_brg_log.log_kode ILIKE'%$term%' OR m_brg_log.log_nama ILIKE'%$term%' )
							GROUP BY t_po_ko_detail.produk_id, m_brg_log.log_kelompok, m_kayu.kayu_nama, m_brg_log.log_kode, m_brg_log.log_nama, 
									m_brg_log.range_awal, m_brg_log.range_akhir, m_brg_log.log_satuan_jual
							HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0 
							ORDER BY m_brg_log.log_nama ASC";
					/*$tanggal    = \app\models\MHargaLog::getTanggalBerlaku();
                    $query      = "
                        SELECT
                            m_brg_log.log_id AS produk_id,
                            CONCAT ( m_brg_log.log_kelompok, ' (', m_kayu.kayu_nama, ')' ) AS produk_nama,
                            m_brg_log.log_kode AS produk_kode,
                            m_brg_log.log_nama,
                            m_brg_log.log_satuan_jual 
                        FROM
                            m_brg_log
                            JOIN m_harga_log ON m_harga_log.log_id = m_brg_log.log_id 
							JOIN m_kayu ON m_kayu.kayu_id = m_brg_log.kayu_id
                        WHERE
                            m_brg_log.active IS TRUE 
                            AND m_harga_log.harga_tanggal_penetapan = '$tanggal' 
                            AND m_harga_log.status_approval = 'APPROVED' 
                            AND m_harga_log.harga_enduser > 0 
                            AND ( m_brg_log.log_kode ILIKE'%$term%' OR m_brg_log.log_nama ILIKE'%$term%' ) 
                        ORDER BY
                            m_brg_log.log_id ASC
                    ";*/
				}else{
                    /*$query = "
                        SELECT m_brg_produk.produk_id, m_brg_produk.produk_kode, m_brg_produk.produk_nama, (select harga_enduser from m_harga_produk where produk_id = m_brg_produk.produk_id and status_harga is true) as harga_enduser
                        FROM h_persediaan_produk
                        JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
                        WHERE ".(!empty($term)?"(produk_kode ILIKE '%".$term."%' OR produk_nama ILIKE '%".$term."%')":'')." AND m_brg_produk.active IS TRUE AND produk_group = '".$jns_produk."' ".$notin."
                        GROUP BY m_brg_produk.produk_id, m_brg_produk.produk_kode, m_brg_produk.produk_nama
                        HAVING SUM(in_qty_palet-out_qty_palet) > 0
                        ORDER BY m_brg_produk.produk_id ASC
                        ;
                    ";*/
                    $query = "
                        SELECT m_brg_produk.produk_id, m_brg_produk.produk_kode, m_brg_produk.produk_nama, (select harga_enduser from m_harga_produk where produk_id = m_brg_produk.produk_id and kode = '".$kode."' order by harga_tanggal_penetapan desc ) as harga_enduser
                        FROM h_persediaan_produk
                        JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
                        WHERE ".(!empty($term)?"(produk_kode ILIKE '%".$term."%' OR produk_nama ILIKE '%".$term."%')":'')." AND m_brg_produk.active IS TRUE AND produk_group = '".$jns_produk."' ".$notin." 
                        GROUP BY m_brg_produk.produk_id, m_brg_produk.produk_kode, m_brg_produk.produk_nama
                        HAVING SUM(in_qty_palet-out_qty_palet) > 0
                        ORDER BY m_brg_produk.produk_id ASC 
                        ;
                    ";
                }
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['produk_id'], 'text'=>$val['produk_kode'] . ' - ' . $val['produk_nama']];
					}
				}
			}
            return $this->asJson($data);
        }
    }

	function actionSetItem(){
		if(\Yii::$app->request->isAjax){
            $produk_id = Yii::$app->request->post('produk_id');
			$jns_produk = Yii::$app->request->post('jns_produk');

			$sql_kode = "select kode from m_harga_produk a ".
							"	left join m_brg_produk b on b.produk_id = a.produk_id ".
							"	where a.harga_tanggal_penetapan <= current_date ".
							"	and b.produk_group = '".$jns_produk."' ".
							"	and a.status_approval = 'APPROVED' ".
							"	order by a.harga_tanggal_penetapan desc, kode desc ".
							"	limit 1 ".
							"	";
			$kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();

			$data['random'] = NULL;
            if(!empty($produk_id)){
                if ($jns_produk=="Limbah") {
                    $data['produk'] = \app\models\MBrgLimbah::findOne($produk_id);
                    $data['harga_enduser'] = \app\models\MHargaLimbah::getHargaCurrentEndUser($produk_id);
                } else if ($jns_produk == "JasaKD" || $jns_produk == "JasaGesek" || $jns_produk == "JasaMoulding"){
					$data['produk'] = \app\models\MProdukJasa::findOne($produk_id);
                } else if($jns_produk=="Log"){
					$data['produk'] = \app\models\MBrgLog::findOne($produk_id);
					$kayu_id = $data['produk']['kayu_id'];
					$log_id = $data['produk']['log_id'];
					$po_ko_id = Yii::$app->request->post('po_ko_id');
					// TAMBAH FSC
					if(strpos($data['produk']->log_nama, 'FSC100%')){
						$fsc = 1;
					} else {
						$fsc= 0;
					}
					// EO FSC
					$modPODet = TPoKoDetail::findOne(['po_ko_id'=>$po_ko_id, 'produk_id'=>$produk_id]);
					if(!$modPODet){
						$modPODet = Yii::$app->db->createCommand("
										SELECT * FROM t_po_ko_detail WHERE po_ko_id = {$po_ko_id} and {$produk_id}::text = ANY (string_to_array(produk_id_alias, ','))
										")->queryOne();
						$data['harga_enduser'] = $modPODet['harga'];
						$data['maks_kubikasi'] = $modPODet['kubikasi'];
					} else {
						$data['harga_enduser'] = $modPODet->harga;
						$data['maks_kubikasi'] = $modPODet->kubikasi;
					}
					$data['availablestock'] = \app\models\HPersediaanLog::getCurrentStockPerLog($po_ko_id, $log_id, $fsc); // TAMBAH FSC - availablestock berdasarkan produk & fsc/nonfsc yg dipilih
				} else {
                    $data['random'] = $this->getRandom($produk_id);
                    $data['produk'] = \app\models\MBrgProduk::findOne($produk_id);
					//$data['harga'] = \app\models\MHargaProduk::findOne(['produk_id'=>$produk_id, 'status_harga' => 'true', 'status_approval'=>'APPROVED']);
					$data['harga'] = \app\models\MHargaProduk::findOne(['produk_id'=>$produk_id, 'status_approval'=>'APPROVED']);
                    $data['availablestock'] = \app\models\HPersediaanProduk::getCurrentStockPerProduk($produk_id);
                    $data['harga_enduser'] = \app\models\MHargaProduk::getHargaCurrentEndUser($produk_id,'harga_enduser',$kode);

					/*$sql = "select m_brg_produk.produk_id as produk_id, m_brg_produk.produk_group as produk_group, m_brg_produk.produk_kode as produk_kode, m_brg_produk.produk_nama as produk_nama, m_brg_produk.produk_dimensi as produk_dimensi
									, (select SUM(in_qty_kecil-out_qty_kecil) from h_persediaan_produk where produk_id = m_brg_produk.produk_id) as stock_palet
									, (select SUM(in_qty_m3-out_qty_m3) from h_persediaan_produk where produk_id = m_brg_produk.produk_id) as stock_kubik
									, (select harga_enduser from m_harga_produk where produk_id = m_brg_produk.produk_id and status_harga is true) as harga_enduser
					from h_persediaan_produk
					join m_brg_produk on m_brg_produk.produk_id = h_persediaan_produk.produk_id
					where m_brg_produk.produk_id = '".$produk_id."'
					and exists (select produk_id from m_harga_produk where h_persediaan_produk.produk_id = m_harga_produk.produk_id and status_harga is true)
					group by m_brg_produk.produk_id, produk_group, produk_kode, produk_nama, produk_dimensi
					having sum(in_qty_palet-out_qty_palet) > 0
					";
					$data['sql'] = Yii::$app->db->createCommand($sql)->queryAll();*/
                }
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }

	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $op_ko_id = Yii::$app->request->post('op_ko_id');
			$edit = Yii::$app->request->post('edit');
			$po_ko_id = Yii::$app->request->post('po_ko_id');
            $data = [];
			$data['random'] = NULL;
            if(!empty($op_ko_id)){
                $model = TOpKo::findOne($op_ko_id);
                $tanggal_batas = $model->tanggal;
                $modDetails = TOpKoDetail::find()->where(['op_ko_id'=>$op_ko_id])->all();
            }else{
                $model = [];
                $modDetails = [];
            }
            $data['html'] = '';
            $data['kode'] = '';
            if(count($modDetails)>0){
				$v = 0;
				if($model->jenis_produk == "Log"){
					$sql_kode = "select kode from m_harga_log a ".
                            "	left join m_brg_log b on b.log_id = a.log_id ".
                            "	where a.harga_tanggal_penetapan <= current_date ".
                            "	and a.status_approval = 'APPROVED' ".
                            "	order by a.harga_tanggal_penetapan desc ".
                            "	limit 1 ".
                            "	";
				} else if($model->jenis_produk == "Limbah"){
					$sql_kode = "select kode from m_harga_limbah a ".
                            "	left join m_brg_limbah b on b.limbah_id = a.limbah_id ".
                            "	where a.harga_tanggal_penetapan <= current_date ".
                            "	and a.status_approval = 'APPROVED' ".
                            "	order by a.harga_tanggal_penetapan desc ".
                            "	limit 1 ".
                            "	";
				} else {
					$sql_kode = "select kode from m_harga_produk a ".
                            "	left join m_brg_produk b on b.produk_id = a.produk_id ".
                            "	where a.harga_tanggal_penetapan <= current_date ".
                            "	and b.produk_group = '".$model->jenis_produk."' ".
                            "	and a.status_approval = 'APPROVED' ".
                            "	order by a.harga_tanggal_penetapan desc ".
                            "	limit 1 ".
                            "	";
				}
                $kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();
                $data['kode'] = $kode;
                foreach($modDetails as $i => $detail){
                    if(!empty($edit)){
                        $modProduk = \app\models\MBrgProduk::findOne($detail->produk_id);

						if($model->jenis_produk == "Log"){
							// $sql_m_harga_produk = "select harga_enduser from m_harga_log ".
                            //                         "	where log_id = ".$detail->produk_id."".
                            //                         "	and harga_tanggal_penetapan <= '".$tanggal_batas."' ".
                            //                         "	and status_approval = 'APPROVED' ".
                            //                         // "	and kode = '".$kode."' ".
                            //                         "	order by harga_tanggal_penetapan desc ".
                            //                         "	limit 1".
                            //                         "	";
							$sql_m_harga_produk = "select harga from t_po_ko_detail where po_ko_id = {$model->po_ko_id} and produk_id = {$detail->produk_id}";
						} else if($model->jenis_produk == "Limbah"){
							$sql_m_harga_produk = "select harga_enduser from m_harga_limbah ".
                                                    "	where limbah_id = ".$detail->produk_id."".
                                                    "	and harga_tanggal_penetapan <= '".$tanggal_batas."' ".
                                                    "	and status_approval = 'APPROVED' ".
                                                    // "	and kode = '".$kode."' ".
                                                    "	order by harga_tanggal_penetapan desc ".
                                                    "	limit 1".
                                                    "	";
						} else {
							$sql_m_harga_produk = "select harga_enduser from m_harga_produk ".
                                                    "	where produk_id = ".$detail->produk_id."".
                                                    "	and harga_tanggal_penetapan <= '".$tanggal_batas."' ".
                                                    "	and status_approval = 'APPROVED' ".
                                                    "	and kode = '".$kode."' ".
                                                    "	order by harga_tanggal_penetapan desc ".
                                                    "	limit 1".
                                                    "	";
						}
                        $harga_enduser = Yii::$app->db->createCommand($sql_m_harga_produk)->queryScalar();

                        $detail->harga_jual = DeltaFormatter::formatNumberForUserFloat($detail->harga_jual);
                        $detail->qty_kecil_perpalet = ($detail->opKo->jenis_produk == "Limbah" || $detail->opKo->jenis_produk == "JasaKD" || $detail->opKo->jenis_produk == "JasaGesek" || $detail->opKo->jenis_produk == "JasaMoulding" || $detail->opKo->jenis_produk == "Log")?"": $detail->produk->produk_qty_satuan_kecil;
                        $detail->kubikasi_perpalet = ($detail->opKo->jenis_produk == "Limbah" || $detail->opKo->jenis_produk == "JasaKD" || $detail->opKo->jenis_produk == "JasaGesek" || $detail->opKo->jenis_produk == "JasaMoulding" || $detail->opKo->jenis_produk == "Log")?0: $detail->produk->kapasitas_kubikasi;
                        $modRandom = Yii::$app->db->createCommand("SELECT nomor_produksi FROM t_op_ko_random WHERE op_ko_detail_id = {$detail->op_ko_detail_id} GROUP BY nomor_produksi")->queryAll();
                        if(!empty($modRandom)){
                            foreach($modRandom as $ii => $rand){
                                $detail->nomor_produksi_random[] = "'".$rand['nomor_produksi']."'";
                            }
                            $detail->nomor_produksi_random = implode(",", $detail->nomor_produksi_random);
                            $detail->is_random = "1";
                        }else{
                            $detail->is_random = "0";
                        }
                        //$data['html'] = $kode;
                        $data['random'] = $this->getRandom($detail->produk_id);
                        $data['html'] .= $this->renderPartial('_addItem',['modDetail'=>$detail,'i'=>$i,'edit'=>$edit,'modProduk'=>$modProduk, 'jns_produk'=>$model->jenis_produk, 'harga_enduser'=>$harga_enduser, 'v'=>$v, 'po_ko_id'=>$po_ko_id]);
                    }else{
                        $modRandom = \app\models\TOpKoRandom::find()->where("op_ko_detail_id = ".$detail->op_ko_detail_id)->all();
                        if(count($modRandom)>0){
                            foreach($modRandom as $ii => $random){
                                $no = $i+$ii;
                                $data['html'] .= $this->renderPartial('_addItemAfterSaveRandom',['modDetail'=>$detail,'i'=>$no,'random'=>$random, 'v'=>$v]);
                            }
                        }else{
							$data['random'] = $this->getRandom($detail->produk_id);
                            $data['html'] .= $this->renderPartial('_addItemAfterSave',['modDetail'=>$detail,'i'=>$i,'modRandom'=>$modRandom, 'v'=>$v]);
                        }

                    }
					$v++;
                }
            }
            return $this->asJson($data);
        }
    }

    function actionGetItemTerima(){
		if(\Yii::$app->request->isAjax){
            $op_ko_id = Yii::$app->request->post('op_ko_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
            $model = TOpKo::findOne($op_ko_id);
            $modDetails = \app\models\TTerimaJasa::find()->where("op_ko_id = ".$model->op_ko_id)->orderBy("terima_jasa_id ASC")->all();
            $data['html'] = '';
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
                    $detail['tanggal'] = DeltaFormatter::formatDateTimeForUser2($detail['tanggal']);
					$data['html'] .= $this->renderPartial('_addItemTerima',['modOp'=>$model,'modDetail'=>$detail,'edit'=>$edit]);
                }
            }
            return $this->asJson($data);
        }
    }

	public function getRandom($produk_id){
		$return = NULL;
		$return['total_palet'] = 0;
		$return['total_qty'] = 0;
		$return['total_kubikasi'] = 0;
		$sqlrandom = "	SELECT nomor_produksi, sum(t_terima_ko_kd.qty) AS qty_kecil, sum(kapasitas_kubikasi) AS kubikasi ".
                        "   FROM t_terima_ko_kd ".
						"   LEFT JOIN t_terima_ko ON t_terima_ko.tbko_id = t_terima_ko_kd.tbko_id ".
						"   WHERE t_terima_ko.produk_id = ".$produk_id." ".
                        // 2020-03-05 penyesuaian get random pada produk CSTMBKR/A/02/2.815.560
                        "   AND t_terima_ko_kd.tbko_id != 92617 " .
                        " GROUP BY nomor_produksi";
        $random = Yii::$app->db->createCommand($sqlrandom)->queryAll();
		if(count($random)>0){
			$return['total_palet'] = count($random);
			foreach($random as $i => $rand){
				$return['total_qty'] += $rand['qty_kecil'];
				$return['total_kubikasi'] += $rand['kubikasi'];
			}
		}
		return $return;
	}

	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= TOpKo::tableName();
				$param['pk']= $param['table'].".". TOpKo::primaryKey()[0];
				$param['column'] = [$param['table'].'.op_ko_id',																			//0
									$param['table'].'.kode',																				//1
									$param['table'].'.jenis_produk',																		//2
									$param['table'].'.tanggal',																				//3
									'm_sales.sales_nm',																						//4
									$param['table'].'.sistem_bayar',																		//5
									$param['table'].'.tanggal_kirim',																		//6
									'm_customer.cust_an_nama',																				//7
									'm_customer.cust_pr_nama',																				//8
									$param['table'].'.cancel_transaksi_id',																	//9
									'MAX(t_spm_ko.spm_ko_id) AS spm_ko_id',																	//10
									$param['table'].'.status as xxx',																		//11
									'(select nota_penjualan_id
										from t_nota_penjualan 
										where t_nota_penjualan.op_ko_id = t_op_ko.op_ko_id
										limit 1 ) as yyy',																					//12
									'(select status from t_approval where reff_no = t_op_ko.kode and level = 1) as status_level_1',			//13
									'(select status from t_approval where reff_no = t_op_ko.kode and level = 2) as status_level_2',			//14
									'(select status from t_approval where reff_no = t_op_ko.kode and level = 3) as status_level_3',			//15
									];
				$param['join']= ['JOIN m_sales ON m_sales.sales_id = '.$param['table'].'.sales_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  LEFT JOIN t_spm_ko ON t_spm_ko.op_ko_id = '.$param['table'].'.op_ko_id
								'];
				$param['group'] = "GROUP BY ".$param['table'].".op_ko_id,
											".$param['table'].".kode, 
											".$param['table'].".jenis_produk,
											".$param['table'].".tanggal,
											m_sales.sales_nm,
											".$param['table'].".sistem_bayar,
											".$param['table'].".tanggal_kirim,
											m_customer.cust_an_nama,
											m_customer.cust_pr_nama,
											".$param['table'].".cancel_transaksi_id,
											xxx,
											yyy
											";
				$param['where'] = "t_op_ko.cancel_transaksi_id IS NULL and t_op_ko.status != '' ";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

    public function actionDaftarAfterSaveX() {
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersavex'){
				$param['table']= TOpKo::tableName();
				$param['pk']= $param['table'].".". TOpKo::primaryKey()[0];
				$param['column'] = [$param['table'].'.op_ko_id',
									$param['table'].'.kode',
									$param['table'].'.jenis_produk',
									$param['table'].'.tanggal',
									'm_sales.sales_nm',
									$param['table'].'.sistem_bayar',
									$param['table'].'.tanggal_kirim',
									'm_customer.cust_an_nama',
									'm_customer.cust_pr_nama',
									$param['table'].'.cancel_transaksi_id',
									'MAX(t_spm_ko.spm_ko_id) AS spm_ko_id',
									$param['table'].'.status',
									// 't_approval.status AS statusapprove', 
									$param['table'].'.status_approval',
									'nota_penjualan_id',
									];
				$param['join']= ['JOIN m_sales ON m_sales.sales_id = '.$param['table'].'.sales_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  LEFT JOIN t_spm_ko ON t_spm_ko.op_ko_id = '.$param['table'].'.op_ko_id
								  LEFT JOIN t_approval ON t_approval.reff_no = t_op_ko.kode
								  LEFT JOIN t_nota_penjualan ON t_nota_penjualan.spm_ko_id = t_spm_ko.spm_ko_id
									'];
				$param['group'] = "GROUP BY ".$param['table'].".op_ko_id,
											".$param['table'].".kode, 
											".$param['table'].".jenis_produk,
											".$param['table'].".tanggal,
											m_sales.sales_nm,
											".$param['table'].".sistem_bayar,
											".$param['table'].".tanggal_kirim,
											m_customer.cust_an_nama,
											m_customer.cust_pr_nama,
											".$param['table'].".cancel_transaksi_id,
											".$param['table'].".status,
											
											nota_penjualan_id"; //t_approval.status,
				$param['where'] = "t_op_ko.cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSaveX');
        }
    }

	public function actionPrintOP(){
		$this->layout = '@views/layouts/metronic/print';
		$model = TOpKo::findOne($_GET['id']);
		$modDetail = TOpKoDetail::find()->where(['op_ko_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'ORDER PENJUALAN');
        if ($model->jenis_produk == "Limbah"){
            // echo "Limbah Tidak ada print!"; exit;
        }
		if($caraprint == 'PRINT'){
			return $this->render('printOP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printOP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printOP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}

	public function actionFindOP(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT t_op_ko.op_ko_id, t_op_ko.kode AS kode FROM t_op_ko 
                    LEFT JOIN t_spm_ko ON t_spm_ko.op_ko_id = t_op_ko.op_ko_id
					WHERE t_op_ko.kode ilike '%{$term}%' AND t_op_ko.cancel_transaksi_id IS NULL 
						AND t_op_ko.kode NOT IN( SELECT reff_no FROM t_approval WHERE status != 'APPROVED' )
                        AND t_spm_ko.op_ko_id IS NULL
					ORDER BY t_op_ko.created_at";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'op_ko_id', 'kode');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['op_ko_id'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}

	public function actionCancelTransaksi($id){
		if(\Yii::$app->request->isAjax){
			$model = TOpKo::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_op_ko
                    $success_3 = true; // t_tempobayar_ko
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $model->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
							$model->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
                            if($model->validate()){
								$success_2 = $model->save();

								$modTempo = TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);
								if(!empty($modTempo)){
									$modTempo->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
									if($modTempo->validate()){
										$success_3 = $modTempo->save();
									}
								}
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel);
                    }

                    $sql_t_approval = "delete from t_approval where reff_no = '".$model->kode."' ";
                    $success_4 = Yii::$app->db->createCommand($sql_t_approval)->execute();

                    if ($success_1 && $success_2 && $success_3 && $success_4) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Transaksi Berhasil di Batalkan');
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

			return $this->renderAjax('cancelTransaksi',['model'=>$model,'modCancel'=>$modCancel]);
		}
	}

	public function actionProdukInStock($disableAction=null,$tr_seq=null,$jenis_produk=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
				$param['table']= \app\models\HPersediaanProduk::tableName();
				$param['pk']= \app\models\HPersediaanProduk::primaryKey()[0];
				$param['column'] = ["m_brg_produk.produk_id" 																												//0
										,"m_brg_produk.produk_group" 																										//1
										,"m_brg_produk.produk_kode" 																										//2
										,"m_brg_produk.produk_nama" 																										//3
										,"m_brg_produk.produk_dimensi" 																										//4
										, "(select SUM(in_qty_kecil-out_qty_kecil) from h_persediaan_produk where produk_id = m_brg_produk.produk_id) as stock_pcs" 		//5
										, "(select SUM(in_qty_m3-out_qty_m3) from h_persediaan_produk where produk_id = m_brg_produk.produk_id) as stock_kubik" 			//6
										, "(select SUM(in_qty_palet-out_qty_palet) from h_persediaan_produk where produk_id = m_brg_produk.produk_id) as stock_palet" 		//7
										, "(select harga_enduser from m_harga_produk 
											where produk_id = m_brg_produk.produk_id  
											and harga_tanggal_penetapan <= current_date 
											and status_approval='APPROVED' 
											order by harga_tanggal_penetapan desc, kode desc limit 1) as harga_enduser" 													//8
										, "(select kode from m_harga_produk 
											where produk_id = m_brg_produk.produk_id  
											and harga_tanggal_penetapan <= current_date 
											and status_approval='APPROVED' 
											order by harga_tanggal_penetapan desc, kode desc limit 1) as kode" 																//9
									];
				$param['join']= ["JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id"];
                if(!empty($jenis_produk)){
                    $param['where'] = "produk_group = '$jenis_produk' ".
                    					"	AND EXISTS (select produk_id from m_harga_produk where h_persediaan_produk.produk_id = m_harga_produk.produk_id and harga_tanggal_penetapan <= current_date and status_approval = 'APPROVED') ";
                }
				$param['group'] = "GROUP BY m_brg_produk.produk_id, produk_group, produk_kode, produk_nama, produk_dimensi";
				$param['having'] = "HAVING SUM(in_qty_palet-out_qty_palet) > 0 ";
                $param['exists'] = " ";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}

            /*$sql = "select m_brg_produk.produk_id,m_brg_produk.produk_group,m_brg_produk.produk_kode,m_brg_produk.produk_nama,m_brg_produk.produk_dimensi
						(select SUM(in_qty_kecil-out_qty_kecil) from h_persediaan_produk where produk_id = m_brg_produk.produk_id) as stock_pcs ,
						(select SUM(in_qty_m3-out_qty_m3) from h_persediaan_produk where produk_id = m_brg_produk.produk_id) as stock_kubik ,
						(select SUM(in_qty_palet-out_qty_palet) from h_persediaan_produk where produk_id = m_brg_produk.produk_id) as stock_palet ,
						(select harga_enduser from m_harga_produk where produk_id = m_brg_produk.produk_id and status_harga is true) as harga_enduser
					from h_persediaan_produk
					join m_brg_produk on m_brg_produk.produk_id = h_persediaan_produk.produk_id
					where produk_group = '$jenis_produk'
					and exists (select produk_id from m_harga_produk where h_persediaan_produk.produk_id = m_harga_produk.produk_id and harga_tanggal_penetapan <=current_date and status_approval='APPROVED')
					GROUP BY m_brg_produk.produk_id, produk_group, produk_kode, produk_nama, produk_dimensi
					having sum(in_qty_palet-out_qty_palet) > 0
					";*/

			return $this->renderAjax('produkInStock',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq,'jenis_produk'=>$jenis_produk]);
		}
	}

	// untuk proses repacking
	public function actionProdukInStock2($disableAction=null,$tr_seq=null,$jenis_produk=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
				$param['table']= \app\models\HPersediaanProduk::tableName();
				$param['pk']= \app\models\HPersediaanProduk::primaryKey()[0];
				$param['column'] = ["m_brg_produk.produk_id" 																												//0
										,"m_brg_produk.produk_group" 																										//1
										,"m_brg_produk.produk_kode" 																										//2
										,"m_brg_produk.produk_nama" 																										//3
										,"m_brg_produk.produk_dimensi"
									];
				$param['join']= ["JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id"];
				if(!empty($jenis_produk)){
					$param['where'] = "produk_group = '$jenis_produk' ";
				}
				$param['group'] = "GROUP BY 1,2,3,4,5";
				$param['having'] = "HAVING SUM(in_qty_palet-out_qty_palet) > 0 ";
								$param['exists'] = " ";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('produkInStock',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq,'jenis_produk'=>$jenis_produk]);
		}
	}
        // untuk perubahan order penjualan biar lebih ringan
        public function actionProdukInStock3($disableAction=null,$tr_seq=null,$jenis_produk=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){

                            $sql = "select 
                                    harga_tanggal_penetapan 
                                    from m_harga_produk as a 
                                    join m_brg_produk as b on b.produk_id=a.produk_id
                                    where produk_group = '".$jenis_produk."' and harga_tanggal_penetapan <= current_date and status_approval = 'APPROVED' 
                                    order by harga_tanggal_penetapan desc limit 1";
                            $modTglpenetapanHarga = Yii::$app->db->createCommand($sql)->queryOne();

				$param['table']= \app\models\ViewStockProduk::tableName();
				// $param['pk']= \app\models\ViewStockProduk::primaryKey()[0];
				$param['pk']= "view_stock_produk.produk_id";
				$param['column'] = ["m_brg_produk.produk_id" 																												//0
										,"m_brg_produk.produk_group" 																										//1
										,"m_brg_produk.produk_kode" 																										//2
										,"m_brg_produk.produk_nama" 																										//3
										,"m_brg_produk.produk_dimensi" 																									//4
																										//9
									];
                                $param['join']= ["JOIN m_brg_produk ON m_brg_produk.produk_id = view_stock_produk.produk_id"];
                                if(!empty($jenis_produk)){
                                    $param['where'] = " produk_group = '$jenis_produk' ".
                                                    " AND EXISTS (select m_brg_produk.produk_id from m_harga_produk where view_stock_produk.produk_id = m_harga_produk.produk_id and harga_tanggal_penetapan ='".$modTglpenetapanHarga['harga_tanggal_penetapan']."' and status_approval = 'APPROVED') ";
                                }
				// $param['group'] = "GROUP BY m_brg_produk.produk_id, produk_group, produk_kode, produk_nama, produk_dimensi";
				// $param['having'] = "HAVING SUM(in_qty_palet-out_qty_palet) > 0 ";
                                $param['exists'] = " ";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}

			return $this->renderAjax('produkInStock',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq,'jenis_produk'=>$jenis_produk]);
		}
	}
	public function actionOpenlimbah($disableAction=null,$tr_seq=null,$jenis_produk=null){

        if(\Yii::$app->request->isAjax){
            $tanggal        = \app\models\MHargaLimbah::getTanggalBerlaku();
            if(empty($tanggal)) {
                $tanggal = \app\models\MHargaLimbah::find()
                ->andWhere(['<=', 'harga_tanggal_penetapan', date('Y-m-d')])
                ->orderBy(['harga_tanggal_penetapan' => SORT_DESC])
                ->max('harga_tanggal_penetapan');
            }

			if(\Yii::$app->request->get('dt')=='table-produk'){
				$param['table'] = \app\models\MBrgLimbah::tableName();
				$param['pk']    = $param['table'] . '.' .\app\models\MBrgLimbah::primaryKey()[0];
				$param['column']= [
                    'm_brg_limbah.limbah_id',
                    "CONCAT(m_brg_limbah.limbah_kelompok,' (',m_brg_limbah.limbah_produk_jenis,')') AS limbah_group",
                    'm_brg_limbah.limbah_kode',
                    'm_brg_limbah.limbah_nama',
                    'm_brg_limbah.limbah_satuan_muat'
                ];
                $param['join']  = ['JOIN m_harga_limbah ON m_harga_limbah.limbah_id = m_brg_limbah.limbah_id'];
				$param['where'] = "m_brg_limbah.active IS TRUE ";
                $param['where'] .= "AND m_harga_limbah.harga_tanggal_penetapan = '$tanggal' ";
                $param['where'] .= "AND m_harga_limbah.status_approval = 'APPROVED' ";
                $param['where'] .= "AND m_harga_limbah.harga_enduser > 0 ";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('openlimbah', [
                'disableAction' => $disableAction,
                'tr_seq' => $tr_seq,
                'jenis_produk' => $jenis_produk,
                'tanggal_penetapan' => $tanggal
            ]);
		}
	}

	public function actionOpenjasa($disableAction=null,$tr_seq=null,$jenis_produk=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
                if($jenis_produk=="JasaKD"){
                    $jenis = "JASA KD";
                }else if($jenis_produk=="JasaGesek"){
                    $jenis = "JASA GESEK";
                }else if($jenis_produk=="JasaMoulding"){
                    $jenis = "JASA MOULDING";
                }
				$param['table']= \app\models\MProdukJasa::tableName();
				$param['pk']= \app\models\MProdukJasa::primaryKey()[0];
				$param['column'] = ['produk_jasa_id',"jenis",'kode','nama','satuan','keterangan'];
				$param['where'] = "active IS TRUE AND jenis = '{$jenis}'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('openjasa',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq,'jenis_produk'=>$jenis_produk]);
		}
	}

	public function actionListRandom($tr_seq=null,$produk_id=null,$nomor_produksi_random=null){
		if(\Yii::$app->request->isAjax){
			$sql = "SELECT t_terima_ko.nomor_produksi, sum(t_terima_ko_kd.qty) AS qty_kecil, sum(kapasitas_kubikasi) AS kubikasi from t_terima_ko_kd 
					LEFT JOIN t_terima_ko ON t_terima_ko.tbko_id = t_terima_ko_kd.tbko_id 
					LEFT JOIN h_persediaan_produk ON h_persediaan_produk.nomor_produksi = t_terima_ko.nomor_produksi 
					WHERE t_terima_ko.produk_id = $produk_id GROUP BY t_terima_ko.nomor_produksi 
					HAVING SUM(in_qty_kecil-out_qty_kecil)>0 ORDER BY t_terima_ko.nomor_produksi ASC";
			$models = Yii::$app->db->createCommand($sql)->queryAll();
			$modProduk = \app\models\MBrgProduk::findOne($produk_id);
			$nomor_produksi_random = str_replace("'", "", explode(",", $nomor_produksi_random));
			return $this->renderAjax('random',['models'=>$models,'tr_seq'=>$tr_seq,'produk_id'=>$produk_id,'modProduk'=>$modProduk,'nomor_produksi_random'=>$nomor_produksi_random]);
		}
	}

	public function actionGetRandom(){
		if(\Yii::$app->request->isAjax){
			$nomor_produksi = Yii::$app->request->post("nomor_produksi");
			$data['html'] = "";
			$data['tot_qty'] = 0;
			$data['tot_kubikasi'] = 0;
			if(!empty($nomor_produksi)){
				$sql = "SELECT t_terima_ko_kd.*, t_terima_ko.nomor_produksi FROM t_terima_ko_kd JOIN t_terima_ko ON t_terima_ko.tbko_id = t_terima_ko_kd.tbko_id WHERE t_terima_ko.nomor_produksi IN($nomor_produksi) ORDER BY tbko_kd_id ASC";
				$models = Yii::$app->db->createCommand($sql)->queryAll();

				if(count($models)>0){
					foreach($models as $i => $model){
						$data['html'] .= "<tr>";
						$data['html'] .= "<td><center>".($i+1)."</center></td>";
						$data['html'] .= "<td>".$model['nomor_produksi']."</td>";
						$data['html'] .= "<td>".$model['t'].$model['t_satuan']." x ".$model['l'].$model['l_satuan']." x ".$model['p'].$model['p_satuan']."</td>";
						$data['html'] .= "<td style='text-align:center;'>".$model['qty']."</td>";
						$data['html'] .= "<td style='text-align:right;'>".$model['kapasitas_kubikasi']."</td>";
						$data['html'] .= "</tr>";
						$data['tot_qty'] += $model['qty'];
						$data['tot_kubikasi'] += $model['kapasitas_kubikasi'];
					}
				}
			}
			return $this->asJson($data);
		}
	}

	public function actionImage($id){
		if(\Yii::$app->request->isAjax){
			$attch = TAttachment::findOne($id);
			return $this->renderAjax('image',['attch'=>$attch]);
		}
	}

	public function actionHapusfile(){
		$attachment_id = $_POST['attachment_id'];

		$TAttachment = TAttachment::findOne($attachment_id);
		$file_name = $TAttachment->file_name;
		$kode = $TAttachment->reff_no;

		//$sql_file_name = "select file_name from t_attachment where attachment_id = '.$attachment_id.' ";
		//$file_name = Yii::$app->db->createCommand($sql_file_name)->queryScalar();

		//$sql_op_ko_kode = "select reff_no from t_attachment where attachment_id = ".$attachment_id." ";
		//$kode = Yii::$app->db->createCommand($sql_op_ko_kode)->queryScalar();

		$sql_cek_attachment = "select count(*) from t_attachment where reff_no = '".$kode."' and active = 'true' ";
		$jumlah_attachment = Yii::$app->db->createCommand($sql_cek_attachment)->queryScalar();

		$sql_t_op_ko = "select op_ko_id from t_op_ko where kode = '".$kode."' ";
		$op_ko_id = Yii::$app->db->createCommand($sql_t_op_ko)->queryScalar();

		$sql_jenis_produk = "select jenis_produk from t_op_ko where kode = '".$kode."' ";
		$jenis_produk = Yii::$app->db->createCommand($sql_jenis_produk)->queryScalar();

		// kalau jenis produk adalah jasa dan attachment tinggal 1 ya jangan dihapus dul
		if ($jumlah_attachment > 1 && ($jenis_produk == 'JasaKD' || $jenis_produk == 'JasaGesek' || $jenis_produk == 'JasaMoulding')) {
			// hapus file
			$dir_path = Yii::$app->basePath.'\web\uploads\mkt\po';
			unlink($dir_path.'\\'.$file_name);

			// hapus database
			$sql_delete = "delete from t_attachment where attachment_id = ".$attachment_id."";
			$query_delete = Yii::$app->db->createCommand($sql_delete)->execute();

			//$sql_update = "update t_attachment set active = 'false' where attachment_id = ".$attachment_id." ";
			//$query_update = Yii::$app->db->createCommand($sql_update)->execute();
		}

		$model = new TOpKo();
		$modTempo = new TTempobayarKo();
		$modAttachment = new TAttachment();

		$modTempoBayar = TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);

		$file = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>1, 'active'=>'true']);
		$file1 = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>2, 'active'=>'true']);
		$file2 = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>3, 'active'=>'true']);
		$file3 = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>4, 'active'=>'true']);
		$file4 = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>5, 'active'=>'true']);
		$file5 = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>6, 'active'=>'true']);

		if(!empty($modTempoBayar)){
			$modTempo->attributes = $modTempoBayar->attributes;
			$modTempo->maks_plafon = DeltaFormatter::formatNumberForUserFloat($modTempo->maks_plafon);
			$modTempo->sisa_piutang = DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_piutang);
			$modTempo->sisa_plafon = DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_plafon);
		}

		//return $this->redirect(array('/marketing/orderpenjualan/index','op_ko_id'=>$op_ko_id, 'success'=>1, 'jumlah_attachment'=>$jumlah_attachment));
		//http://localhost/cis/web/marketing/orderpenjualan/index?op_ko_id=4332&edit=1
		return $this->redirect(array('/marketing/orderpenjualan/index','op_ko_id'=>$op_ko_id, 'edit'=>1, 'jumlah_attachment'=>$jumlah_attachment));
	}

	public function actionOpenlog($disableAction=null,$tr_seq=null,$jenis_produk=null, $po_ko_id){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
				$query ="WITH split_produk AS (
							SELECT  *, unnest(string_to_array(produk_id_alias, ','))::int AS exploded_produk_id
							FROM t_po_ko_detail
							WHERE po_ko_id = {$po_ko_id} )
							
						SELECT 
							COALESCE(t.produk_id, t.exploded_produk_id) AS produk_ids,
							CONCAT(m_brg_log.log_kelompok, ' - ', m_kayu.kayu_nama) AS jenis_kayu,
							m_brg_log.log_kode,
							m_brg_log.log_nama,
							CONCAT(m_brg_log.range_awal, 'cm - ', m_brg_log.range_akhir, 'cm') AS range_diameter,
							SUM(fisik_pcs) AS stock_pcs,
							SUM(fisik_volume) AS stock_kubik,
							t.harga,
							m_brg_log.fsc
						FROM split_produk t
						JOIN m_brg_log ON m_brg_log.log_id = COALESCE(t.produk_id, t.exploded_produk_id)
						JOIN m_kayu ON m_kayu.kayu_id = m_brg_log.kayu_id
						JOIN view_stock_logalam ON view_stock_logalam.kayu_id = m_brg_log.kayu_id
							AND view_stock_logalam.fisik_diameter BETWEEN m_brg_log.range_awal AND m_brg_log.range_akhir
						GROUP BY produk_ids, m_brg_log.log_kelompok, m_kayu.kayu_nama, m_brg_log.log_kode, m_brg_log.log_nama, 
							m_brg_log.range_awal, m_brg_log.range_akhir, t.harga, m_brg_log.fsc
						-- HAVING SUM(CASE WHEN h_persediaan_log.status = 'IN' THEN 1 ELSE -1 END) > 0
						ORDER BY m_brg_log.log_nama ASC;
						";
				$model = \Yii::$app->db->createCommand($query)->queryAll();
				$response = [
					"draw" => intval(Yii::$app->request->get('draw')), // penting!
					"recordsTotal" => count($model),
					"recordsFiltered" => count($model),
					"data" => [],
				];

				foreach ($model as $row) {
					$response['data'][] = [
						$row['produk_ids'],        // 0 
						$row['jenis_kayu'],        // 1
						$row['log_kode'],          // 2
						$row['log_nama'],          // 3
						$row['range_diameter'],    // 4
						$row['fsc'],               // 5 
						$row['harga'],             // 6
						$row['stock_pcs'],         // 7
						$row['stock_kubik'],       // 8
					];
				}

				return \yii\helpers\Json::encode($response);
			}

			return $this->renderAjax('openlog',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq,'jenis_produk'=>$jenis_produk, 'po_ko_id'=>$po_ko_id]);
		}
	}

	/**public function actionOpenlog($disableAction=null,$tr_seq=null,$jenis_produk=null, $po_ko_id){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
				$term = Yii::$app->request->get('term');
				$param['table']= \app\models\TPoKoDetail::tableName();
				$param['pk']= \app\models\TPoKoDetail::primaryKey()[0];
				$param['column']= [
									"t_po_ko_detail.produk_id", 
									"CONCAT(m_brg_log.log_kelompok, ' - ', m_kayu.kayu_nama) as jenis_kayu", 
									"m_brg_log.log_kode", 
									"m_brg_log.log_nama",
									"CONCAT(m_brg_log.range_awal, 'cm - ', m_brg_log.range_akhir, 'cm') as range_diameter",
									"COALESCE(SUM(CASE WHEN h_persediaan_log.status = 'IN' THEN 1 ELSE -1 END), 0) AS stock_pcs",
									"SUM(fisik_volume) as stock_kubik", 
									"t_po_ko_detail.harga",
									"m_brg_log.fsc"
					            ];
				$param['join']  = ['JOIN m_brg_log on m_brg_log.log_id = t_po_ko_detail.produk_id
									JOIN m_kayu on m_kayu.kayu_id = m_brg_log.kayu_id
									LEFT JOIN h_persediaan_log on h_persediaan_log.kayu_id = m_brg_log.kayu_id 
										AND h_persediaan_log.fisik_diameter BETWEEN m_brg_log.range_awal AND m_brg_log.range_akhir'];
                $param['where'] = " t_po_ko_detail.po_ko_id = {$po_ko_id} and no_grade <> '-' ";
				$param['group'] = "GROUP BY t_po_ko_detail.produk_id, m_brg_log.log_kelompok, m_kayu.kayu_nama, m_brg_log.log_kode, m_brg_log.log_nama, 
									m_brg_log.range_awal, m_brg_log.range_akhir, t_po_ko_detail.harga, m_brg_log.fsc";
				$param['having'] = "HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0 ";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
				// return \yii\helpers\Json::encode($model);
			}

			return $this->renderAjax('openlog',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq,'jenis_produk'=>$jenis_produk, 'po_ko_id'=>$po_ko_id]);
		}
	}*/

	public function actionSetItemLogPelabuhan(){
		if(Yii::$app->request->isAjax){
			$terima_logalam_id = Yii::$app->request->post('terima_logalam_id');
			$po_ko_id = Yii::$app->request->post('po_ko_id');
			if (!empty($terima_logalam_id)) {
				// if ($terimalogalam) {
					$details = \app\models\TTerimaLogalamDetail::find()->where(['terima_logalam_id' => $terima_logalam_id])->all();
					if (!empty($details)) {
						$data = [];
						$allValid = true;
						foreach ($details as $i =>$detail) {
							// $det = \app\models\TTerimaLogalamDetail::findOne($detail->terima_logalam_detail_id);
							if($detail->fsc){
								$fsc = 'true';
							} else {
								$fsc = 'false';
							}
							$kayu = \app\models\MKayu::findOne($detail->kayu_id);
							$produks = Yii::$app->db->createCommand(
								"SELECT * FROM m_brg_log WHERE kayu_id = {$detail->kayu_id} AND {$detail->diameter_rata}
								BETWEEN range_awal AND range_akhir AND fsc = {$fsc}"
							)->queryOne();
							// $harga_jual = \app\models\MHargaLog::getHargaCurrentEndUser($produks['log_id']);
							// $modPoDetail = TPoKoDetail::findOne(['po_ko_id'=>$po_ko_id, 'produk_id'=>$produks['log_id']]);
							$modPoDetail = Yii::$app->db->createCommand("SELECT * FROM t_po_ko_detail WHERE po_ko_id = $po_ko_id AND 
																	(
																		(t_po_ko_detail.produk_id IS NULL AND {$produks['log_id']} = ANY(string_to_array(t_po_ko_detail.produk_id_alias, ',')::int[])) OR
																		(t_po_ko_detail.produk_id IS NOT NULL AND produk_id = {$produks['log_id']})
																	)")->queryOne();
							if(!empty($modPoDetail)){
								$harga_jual = $modPoDetail['harga'];//$modPoDetail->harga;
								$item = [
									// 'produk'=>$produks,
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
									'log_kode'=>$produks['log_kode'] . ' - ' . $produks['log_nama'],
									'harga_jual'=>$harga_jual 
								];
							}
							if (!empty($item['produk_id']) && !empty($item['log_kode'])) {
								// Validasi bahwa item tidak kosong
								if (!array_filter($item, function($value) {
									return $value === '' || $value === null;
								})) {
									$data[] = $item;
								} else {
									$allValid = false;
								}
							} else {
								$allValid = false;
							}
						}
					}

					// print_r($modPoDetail); exit;

					if ($allValid && !empty($data)) {
						return $this->asJson([
							'status' => 'success',
							'data' => $data
						]);
					} else {
						return $this->asJson([
							'status' => 'failed',
							'data' => []
						]);
					}
					// return $this->asJson($data);
				// }
			}
		}
	}

	public function actionGetLogPelabuhanOptions($cust_id)
	{
		$options = TOpKo::getOptionListLogPelabuhan($cust_id);
		return \yii\helpers\Json::encode($options);
	}

	public function actionSetTarikPO(){
		if(\Yii::$app->request->isAjax){
			$po_ko_id = Yii::$app->request->post('po_ko_id');
			$data = [];
			if(!empty($po_ko_id)){
				$model = TPoKo::findOne($po_ko_id);
				if(!empty($model)){
					$data = $model->attributes;
					$modCust = MCustomer::findOne($model->cust_id);
					$data['customer'] = $modCust->attributes;
					$modSales = MSales::findOne($model->sales_id);
					$data['sales'] = $modSales->attributes;
				}
			}

			return $this->asJson($data);
		}
	}

	public function actionSetPreviewPO(){
		if(\Yii::$app->request->isAjax){
			$kode = Yii::$app->request->post('kode');
			$data = [];
			if(!empty($kode)){
				$model = TAttachment::find()->where(['reff_no'=>$kode])->all();
				foreach ($model as $attachment) {
					$attachmentData = $attachment->attributes;
                    $attachmentData['file_url'] = Yii::$app->urlManager->baseUrl . '/uploads/mkt/purchaseorder/' . $attachment->file_name;
                    $data[] = $attachmentData;
				}
			}
			return $this->asJson($data);
		}
	}

	public function actionShowFile($id){
		if(\Yii::$app->request->isAjax){
			$dataindex = Yii::$app->request->get('dataindex');
			$attch = \app\models\TAttachment::findOne($id);
			$ext = $attch->file_ext;
			
			return $this->renderAjax('showFile',['attch'=>$attch, 'ext'=>$ext, 'dataindex'=>$dataindex]);
		}
	}

	public function actionValidatingKubikasi(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->post('id');
			$produks = Yii::$app->request->post('produks');
			$edit = Yii::$app->request->post('edit');
			$op_id = Yii::$app->request->post('op_id');
			$data = [];

			// cari po_ko_detail dari produk yg dipilih
			$data['post'] = [];
			if($produks){
				foreach($produks as $i => $prod){
					$produk_id = $prod['produk_id'];
					$kubikasi = $prod['kubikasi'];
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
			}

			// kumpulkan data yg sudah ada di OP
			$data['op'] = [];
			if($edit){
				$modOp = TOpKo::find()->where(['po_ko_id'=>$id])->andWhere(['<>',  'op_ko_id', $op_id])->all();
			} else {
				$modOp = TOpKo::findAll(['po_ko_id'=>$id]);
			}
			if(count($modOp) > 0){
				foreach($modOp as $i => $op){
					$modOpDet = TOpKoDetail::findAll(['op_ko_id'=>$op['op_ko_id']]);
					foreach($modOpDet as $ii => $opdet){
						$produk_id_op = $opdet['produk_id'];
						$kubikasi_op = $opdet['kubikasi'];
						$modPoDet_op = TPoKoDetail::findOne(['po_ko_id'=>$id, 'produk_id'=>$produk_id_op]);
						if(empty($modPoDet_op)){
							$modPoDet_op = Yii::$app->db->createCommand("
											SELECT * FROM t_po_ko_detail WHERE po_ko_id = $id and $produk_id_op = ANY (string_to_array(produk_id_alias, ',')::int[])
											")->queryOne();
							$poDetail_op = $modPoDet_op['po_ko_detail_id'];
						} else {
							$poDetail_op = $modPoDet_op->po_ko_detail_id;
						}

						if (isset($data['op'][$poDetail_op])) {
							$data['op'][$poDetail_op]['kubikasi'] += $kubikasi_op;
						} else {
							$data['op'][$poDetail_op] = [
								'po_ko_detail_id' => $poDetail_op,
								'kubikasi' => $kubikasi_op,
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