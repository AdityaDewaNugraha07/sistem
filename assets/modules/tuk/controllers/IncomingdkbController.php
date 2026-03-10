<?php

namespace app\modules\tuk\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class IncomingdkbController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TIncomingDkb();
		$modDetail = [];
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail]);
	}
	
	public function actionSetParent(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $data = [];
            if(!empty($loglist_id)){
                $model = \app\models\TLoglist::findOne($loglist_id);
                $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id])->all();
				if(!empty($model)){
					$data['pihak1_perusahaan'] = $model->logKontrak->pihak1_perusahaan;
					$data['nomor_kontrak'] = $model->logKontrak->nomor;
					$data['lokasi_muat'] = $model->lokasi_muat;
					$data['total_batang'] = count($modDetail);
					$data['total_m3'] = 0;
					foreach($modDetail as  $i => $detail){
						$data['total_m3'] += $detail->volume_value;
					}
				}
            }
            return $this->asJson($data);
        }
	}
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new \app\models\TIncomingDkb();
			$incoming_dkb_id = Yii::$app->request->post('incoming_dkb_id');
			$loglist_id = Yii::$app->request->post('loglist_id');
			$kode_partai = Yii::$app->request->post('kode_partai');
			$last_tr = []; parse_str(\Yii::$app->request->post('last_tr'),$last_tr);
			$modDetail->incoming_dkb_id = $incoming_dkb_id;
			$modDetail->loglist_id = $loglist_id;
			$modDetail->kode_partai = $kode_partai;
			if(!empty($last_tr)){
				foreach($last_tr['TIncomingDkb'] as $qwe){
					$last_tr = $qwe;
				}
				$modDetail->attributes = $last_tr;
				$modDetail->no_grade = "";
				$modDetail->no_btg = "";
				$modDetail->no_barcode = "";
			}
            $data['html'] = $this->renderPartial('_item',['modDetail'=>$modDetail,'last_tr'=>$last_tr]);
            return $this->asJson($data);
        }
    }
	
	public function actionSavedkb(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
			$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
			if( isset($form_params['TIncomingDkb']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false; // t_incoming_dkb
					$success_2 = false; // h_persediaan_dkb
					$no_barcode_old = '';
					$post = $form_params['TIncomingDkb'];
					if(count($post)>0){
						foreach($post as $peng){ $post = $peng; }
						$mod = new \app\models\TIncomingDkb();
						if(!empty($post['incoming_dkb_id'])){
							$mod = \app\models\TIncomingDkb::findOne($post['incoming_dkb_id']);
							$no_barcode_old = $mod->no_barcode;
						}
						$mod->attributes = $post;
						if($mod->validate()){
							if($mod->save()){
								$success_1 = true;
								if(!\app\models\HPersediaanDkb::checkPersediaanOut($no_barcode_old)){
									$modPersediaan = \app\models\HPersediaanDkb::findOne(['no_barcode'=>$no_barcode_old]);
									if(empty($modPersediaan)){
										$modPersediaan = new \app\models\HPersediaanDkb();
									}
									$modPersediaan->attributes = $mod->attributes;
									$modPersediaan->status = "IN";
									$modPersediaan->reff_no = $mod->loglist->loglist_kode;
									$modPersediaan->lokasi = $post['lokasi_bongkar'];
									$modPersediaan->dok_diameter = $mod->diameter;
									$modPersediaan->dok_panjang = $mod->panjang;
									$modPersediaan->dok_reduksi = $mod->kondisi;
									$modPersediaan->dok_volume = $mod->volume;
									if($modPersediaan->validate()){
										if($modPersediaan->save()){
											$success_2 = true;
										}else{
											$success_2 = false;
										}
									}else{
										$success_2 = false;
									}
								}else{
									$success_2 = false;
									$data['message'] = "Tidak bisa simpan karena kayu ini sudah pernah ditransaksikan!";
								}
							}
						}else{
							$success_1 = false;
                            if(!empty(\yii\widgets\ActiveForm::validate($mod))){
                                $data['message'] = "";
                                foreach(\yii\widgets\ActiveForm::validate($mod) as $i => $error ){
                                    $data['message'] .= implode(", ", $error);
                                }
                            }
						}
					}

//	                echo "<pre>";
//					print_r($success_1);
//	                echo "<pre>";
//					print_r($success_2);
//					exit;

					if ($success_1 && $success_2) {
						$transaction->commit();
						$data = $mod->attributes;
						$data['status'] = true;
						$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
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
			
        }
    }
	
	
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $data = [];
            $data['html'] = ''; $data['kode_partai'] = "";
			$disabled = false;
            if(!empty($loglist_id)){
				$modLoglist = \app\models\TLoglist::findOne($loglist_id);
                $modDkb = \app\models\TIncomingDkb::find()->where(['loglist_id'=>$loglist_id])->orderBy(['created_at'=>SORT_ASC])->all();
                if(count($modDkb)>0){
					$data['kode_partai'] = $modDkb[0]->kode_partai;
					foreach($modDkb as $i => $model){
						$modPersediaan = \app\models\HPersediaanDkb::findOne(['reff_no'=>$modLoglist->loglist_kode,'no_barcode'=>$model->no_barcode]);
						$model->lokasi_bongkar = $modPersediaan->lokasi;
						$data['html'] .= $this->renderPartial('_item',['modDetail'=>$model]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDeleteItem($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TIncomingDkb::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
					if(!\app\models\HPersediaanDkb::checkPersediaanOut($model->no_barcode)){
						$modPersediaan = \app\models\HPersediaanDkb::findOne(['no_barcode'=>$model->no_barcode]);
						if($modPersediaan->delete()){
							$success_2 = true;
							if($model->delete()){
								$success_1 = true;
							}else{
								$data['message'] = Yii::t('app', 'Data Gagal dihapus');
							}
						}else{
							$data['message'] = Yii::t('app', 'Data Gagal dihapus');
						}
					}else{
						$data['message'] = Yii::t('app', 'Tidak bisa hapus karena kayu ini sudah pernah ditransaksikan!');
					}
					
					if ($success_1 && $success_2) {
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
	
	public function actionDeleteAll($id){
		if(\Yii::$app->request->isAjax){
			$pesan = "Apakah anda yakin akan menghapus semua?";
			$modLoglist = \app\models\TLoglist::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					$success_2 = false;
					
					$barcode = "";
					$sql = "SELECT h_persediaan_log.no_barcode FROM t_incoming_dkb
							JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_incoming_dkb.no_barcode
							WHERE t_incoming_dkb.loglist_id = {$id} AND h_persediaan_log.status != 'IN'";
					$barcodearr= Yii::$app->db->createCommand($sql)->queryAll();
					if(count($barcodearr)> 0){
						$asd = '';
						foreach($barcodearr as $i => $bar){
							$asd .= "'".$bar['no_barcode']."'";
							if(($i+1) != count($barcodearr)){
								$asd .= ",";
							}
						}
						$barcode .= " AND no_barcode NOT IN (".$asd.")";
						
					}
					$qwe = \app\models\HPersediaanDkb::find()->where("reff_no = '".$modLoglist->loglist_kode."' ".$barcode)->all();
					if(count($qwe)>0){
						if(\app\models\HPersediaanDkb::deleteAll("reff_no = '".$modLoglist->loglist_kode."' ".$barcode)){
							$success_2 = true;
						}
					}else{
						$success_2 = true;
					}
					
					$zxc = \app\models\TIncomingDkb::find()->where("loglist_id = ".$id." ".$barcode)->all();
					if(count($zxc)>0){
						if(\app\models\TIncomingDkb::deleteAll("loglist_id = ".$id." ".$barcode)){
							$success_1 = true;
						}
					}else{
						$success_1 = true;
					}
					
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					exit;
					
					if ($success_1 && $success_2) {
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'deleteAll']);
		}
	}
	
	public function actionEditPartai($loglist_id,$kode_partai){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TIncomingDkb();
            if( Yii::$app->request->post('TIncomingDkb')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = true;
					$TIncomingDkb = \app\models\TIncomingDkb::find()->where(['loglist_id'=>$loglist_id])->all();
					if(count($TIncomingDkb)>0){
						foreach($TIncomingDkb as $i => $dkb){
							$dkb->kode_partai = $_POST['TIncomingDkb']['kode_partai'];
							$dkb->lokasi_bongkar = "XXXXX";
							if($dkb->validate()){
								if($dkb->save()){
									$success_1 &= true;
								}else{
									$success_1 = false;
								}
							}else{
								$success_1 = false;
							}
						}
					}
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data Berhasil Diupdate');
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
			return $this->renderAjax('editKodePartai',['model'=>$model,'loglist_id'=>$loglist_id,'kode_partai'=>$kode_partai]);
		}
	}
    
    public function actionImportexcel($loglist_id,$kode_partai){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TAttachment();
			if(isset($_FILES['TAttachment'])){
                $transaction = \Yii::$app->db->beginTransaction();
                $model->load(\Yii::$app->request->post());
                $modLoglist = \app\models\TLoglist::findOne($loglist_id);
                $file = \yii\web\UploadedFile::getInstance($model, 'file');
                $dir_path = Yii::$app->basePath.'/web/uploads/fileimport';
                try {
                    $success_1 = false; // t_attachment
                    $success_2 = true; // t_incoming_dkb
                    $success_3 = true; // t_persediaan_dkb
                    if(!empty($file)){
                        if($file->extension == 'xlsx' || $file->extension == 'xls'){
                            $model = new \app\models\TAttachment();
                            $model->reff_no = $modLoglist->loglist_kode;
                            $model->file_type = $file->type;
                            $model->file_ext = $file->extension;
                            $model->file_size = $file->size;
                            $model->dir_path = $dir_path;
                            $model->seq = 1;
                            $randomstring_attch = Yii::$app->getSecurity()->generateRandomString(4);
                            $file_path = date('Ymd_His').'-importdkb-'.$randomstring_attch.'.'  . $file->extension;
                            $model->file_name = $file_path;
                            
                            if($model->validate()){
                                if($model->save()){
                                    $success_1 = true;
                                    
                                    $file->saveAs($dir_path.'/'.$file_path);
                                    // start excel reader
                                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader( ucfirst($file->extension) );
                                    $reader->setReadDataOnly(true);
                                    $spreadsheet = $reader->load($dir_path.'/'.$file_path);
                                    $worksheet = $spreadsheet->getActiveSheet();
                                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
                                    // end excel reader

                                    // https://phpspreadsheet.readthedocs.io/en/latest/topics/accessing-cells/#looping-through-cells
                                    for ($row = 1; $row <= $highestRow; ++$row) {
                                        if($row != 1){ // menghindari baca header
                                            if($success_2 == true){
                                                $nama_kayu = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                                                $modKayu = \app\models\MKayu::find()->where("kayu_nama = '{$nama_kayu}'")->one();
                                                if(!empty($modKayu)){
                                                    $dkb = new \app\models\TIncomingDkb();
                                                    $dkb->loglist_id = $loglist_id;
                                                    $dkb->kode_partai = $kode_partai;
                                                    $dkb->no_grade = strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()); // kolom no 2
                                                    $dkb->no_barcode = $worksheet->getCellByColumnAndRow(4, $row)->getValue(); // kolom no 4
                                                    $dkb->no_btg = $worksheet->getCellByColumnAndRow(5, $row)->getValue(); // kolom no 5
                                                    $dkb->kayu_id = $modKayu->kayu_id;
                                                    $dkb->panjang = number_format( $worksheet->getCellByColumnAndRow(8, $row)->getValue(), 2); // kolom no 8
                                                    $dkb->diameter = $worksheet->getCellByColumnAndRow(9, $row)->getValue(); // kolom no 9
                                                    $dkb->kondisi = $worksheet->getCellByColumnAndRow(10, $row)->getValue(); // kolom no 10
                                                    $dkb->volume = number_format( $worksheet->getCellByColumnAndRow(11, $row)->getValue(), 2); // kolom no 11
                                                    $dkb->pot = $worksheet->getCellByColumnAndRow(13, $row)->getValue(); // kolom no 13
                                                    $dkb->asal_kayu = $worksheet->getCellByColumnAndRow(14, $row)->getValue(); // kolom no 14
                                                    $dkb->lokasi_bongkar = "-";
                                                    if($dkb->validate()){
                                                        if($dkb->save()){
                                                            $success_2 &= true;
                                                            
                                                            // start insert h_persediaan_dkb
                                                            if(!\app\models\HPersediaanDkb::checkPersediaanOut($dkb->no_barcode)){
                                                                $modPersediaan = \app\models\HPersediaanDkb::findOne(['no_barcode'=>$dkb->no_barcode]);
                                                                if(empty($modPersediaan)){
                                                                    $modPersediaan = new \app\models\HPersediaanDkb();
                                                                }
                                                                $modPersediaan->attributes = $dkb->attributes;
                                                                $modPersediaan->status = "IN";
                                                                $modPersediaan->reff_no = $modLoglist->loglist_kode;
                                                                $modPersediaan->lokasi = $_POST['TAttachment']['lokasi_bongkar'];
                                                                $modPersediaan->dok_diameter = $dkb->diameter;
                                                                $modPersediaan->dok_panjang = $dkb->panjang;
                                                                $modPersediaan->dok_reduksi = $dkb->kondisi;
                                                                $modPersediaan->dok_volume = $dkb->volume;
                                                                if($modPersediaan->validate()){
                                                                    if($modPersediaan->save()){
                                                                        $success_3 = true;
                                                                    }else{
                                                                        $success_3 = false;
                                                                    }
                                                                }else{
                                                                    $success_3 = false;
                                                                }
                                                            }else{
                                                                $success_3 = false;
                                                                $data['message'] = "'{$dkb->no_barcode}' sudah pernah ditransaksikan<br>Transaction Aborted!";
                                                            }
                                                            // end insert h_persediaan_dkb
                                                            
                                                        }else{
                                                            $success_2 = false;
                                                        }
                                                    }else{
                                                        $success_2 = false;
                                                    }
                                                }else{
                                                    $success_2 = false;
                                                    $data['message'] = 'Jenis kayu "'.$nama_kayu.'" tidak ditemukan di master<br>Transaction Aborted!';
                                                }
                                            }
                                        }
                                    }
                                }
                            }else{
                                $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                            }
                        }else{
                            $data['status'] = false;
                            $data['message'] = "file not supported";
                        }
                    }
                    
//                    echo "<pre>";
//                    print_r($success_1);
//                    echo "<pre>";
//                    print_r($success_2);
//                    echo "<pre>";
//                    print_r($success_3);
//                    exit;
                    
                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                        unlink($dir_path.'/'.$file_path);
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('importexcel',['model'=>$model]);
		}
	}
}
