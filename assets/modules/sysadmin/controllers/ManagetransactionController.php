<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ManagetransactionController extends DeltaBaseController
{
	public function actionStockproduk(){
		$model = new \app\models\HPersediaanProduk();
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
            $id = Yii::$app->request->get('id');
			$model = \app\models\HPersediaanProduk::findOne(['nomor_produksi'=>$id]);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // h_persediaan_produk
                    $success_2 = false; // t_produksi
                    $success_3 = false; // t_terima_ko
                    $success_4 = false; // t_terima_ko_kd
					$validatingtransaction = true;
					
					// start validating transaction
					if(!empty(\app\models\TProdukKeluar::findOne(['nomor_produksi'=>$id]))){
						$validatingtransaction = false;
						$data['message'] = Yii::t('app', 'Data tidak bisa dihapus karena sudah dilakukan SCAN SPM / Mutasi Keluar');
					}
					if(!empty(\app\models\TMutasiGudang::findOne(['nomor_produksi'=>$id]))){
						$validatingtransaction = false;
						$data['message'] = Yii::t('app', 'Data tidak bisa dihapus karena sudah dikakukan mutasi antar gudang');
					}
					if(!empty(\app\models\TMutasiKeluar::findOne(['nomor_produksi'=>$id]))){
						$validatingtransaction = false;
						$data['message'] = Yii::t('app', 'Data tidak bisa dihapus karena sudah dikakukan Mutasi Keluar');
					}
					if(count((\app\models\HPersediaanProduk::findAll(['nomor_produksi'=>$id])))>1){
						$validatingtransaction = false;
						$data['message'] = Yii::t('app', 'Data tidak bisa dihapus karena data ini pernah dilakukan transaksi');
					}
					// end validating transaction
					
                    if($model->delete() && $validatingtransaction){
                        $success_1 = true;
						$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$id]);
						if(!empty($modProduksi)){
							if($modProduksi->delete()){
								$success_2 = true;
								$modTerimaKo = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$id]);
								if(!empty($modTerimaKo)){
									$modTerimaKoKd = \app\models\TTerimaKoKd::find()->where(['tbko_id'=>$modTerimaKo->tbko_id])->all();
									if(count($modTerimaKoKd)>0){
										$success_4 = \app\models\TTerimaKoKd::deleteAll('tbko_id = '.$modTerimaKo->tbko_id);
									}else{
										$success_4 = true;
									}
									if($modTerimaKo->delete()){
										$success_3 = true;
									}else{
										$data['message'] = Yii::t('app', 'Data '.$modTerimaKo->tableName().' Gagal dihapus');
									}
								}else{
									$success_3 = true;
								}
							}else{
								$data['message'] = Yii::t('app', 'Data '.$modProduksi->tableName().' Gagal dihapus');
							}
						}else{
							$success_2 = true;
						}
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
                    if ($success_1 && $success_2 && $success_3 && $success_4) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Berhasil Dihapus');
                        $data['callback'] = '$( ".fa-close" ).click();';
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid,'actionname'=>'stockproduk']);
		}
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionMutasikeluar(){
		$model = new \app\models\HPersediaanProduk();
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
            $id = Yii::$app->request->get('id');
			$model = \app\models\TMutasiKeluar::findOne($id);
			$no_prod = $model->nomor_produksi;
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // h_persediaan_produk (OUT)
                    $success_2 = false; // t_mutasi_keluar
					$validatingtransaction = true;
					
					// start validating transaction
					
					// end validating transaction
					
                    if($model->delete() && $validatingtransaction){
                        $success_1 = true;
						$modPersediaan = \app\models\HPersediaanProduk::find()->where("nomor_produksi = '{$no_prod}' AND out_qty_palet != 0")->all();
						if(count($modPersediaan)>0){
							if(\app\models\HPersediaanProduk::deleteAll("nomor_produksi = '{$no_prod}' AND out_qty_palet != 0")){
								$success_2 = true;
							}else{
								$data['message'] = Yii::t('app', 'Data '.$modPersediaan->tableName().' Gagal dihapus');
							}
						}else{
							$success_2 = true;
						}
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
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Berhasil Dihapus');
                        $data['callback'] = '$( ".fa-close:last" ).click();';
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid,'actionname'=>'mutasikeluar']);
		}
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionAdjustspo(){
		$modSPODetail = new \app\models\TSpoDetail();
		if(\Yii::$app->request->isAjax){
			$data['spp'] = '<i style="font-size: 1.2rem;">'. Yii::t('app', 'Data Tidak Ditemukan') .'</i>'; 
			$data['spo'] = '<i style="font-size: 1.2rem;">'. Yii::t('app', 'Data Tidak Ditemukan') .'</i>'; 
			if( Yii::$app->request->post('bhp_id') || Yii::$app->request->post('suplier_id')){
				$bhp_id = Yii::$app->request->post('bhp_id');
				$suplier_id = Yii::$app->request->post('suplier_id');
				$querySPP = \app\models\TSppDetail::find()->join("JOIN", "t_spp", "t_spp.spp_id = t_spp_detail.spp_id");
				if(!empty($bhp_id)){
					$querySPP->andWhere("bhp_id = ".$bhp_id);
				}
				if(!empty($suplier_id)){
					$querySPP->andWhere("suplier_id = ".$suplier_id);
				}
				$modSPP = $querySPP->orderBy("t_spp_detail.sppd_id ASC")->all();
				if(count($modSPP)>0){
					$data['spp'] = $this->renderPartial('_contentSPP',['model'=>$modSPP]);
				}
				
				$querySPO = \app\models\TSpoDetail::find()->join("JOIN", "t_spo", "t_spo.spo_id = t_spo_detail.spo_id");
				if(!empty($bhp_id)){
					$querySPO->andWhere("bhp_id = ".$bhp_id);
				}
				if(!empty($suplier_id)){
					$querySPO->andWhere("suplier_id = ".$suplier_id);
				}
				$modSPO = $querySPO->orderBy("spod_id ASC")->all();
				if(count($modSPO)>0){
					$data['spo'] = $this->renderPartial('_contentSPO',['model'=>$modSPO]);
				}
			}
			return $this->asJson($data);
		}
		return $this->render('adjustspo',['modSPODetail'=>$modSPODetail]);
	}
    
    public function actionDeleteTerimaRepacking(){
		$model = new \app\models\HPersediaanProduk();
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
            $id = Yii::$app->request->get('id');
			$model = \app\models\HPersediaanProduk::findOne(['nomor_produksi'=>$id]);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // h_persediaan_produk
                    $success_2 = false; // t_terima_ko
                    $success_3 = false; // t_terima_ko_kd
					$validatingtransaction = true;
					
					// start validating transaction
					if(!empty(\app\models\TProdukKeluar::findOne(['nomor_produksi'=>$id]))){
						$validatingtransaction = false;
						$data['message'] = Yii::t('app', 'Data tidak bisa dihapus karena sudah dilakukan SCAN SPM / Mutasi Keluar');
					}
					if(!empty(\app\models\TMutasiGudang::findOne(['nomor_produksi'=>$id]))){
						$validatingtransaction = false;
						$data['message'] = Yii::t('app', 'Data tidak bisa dihapus karena sudah dikakukan mutasi antar gudang');
					}
					if(!empty(\app\models\TMutasiKeluar::findOne(['nomor_produksi'=>$id]))){
						$validatingtransaction = false;
						$data['message'] = Yii::t('app', 'Data tidak bisa dihapus karena sudah dikakukan Mutasi Keluar');
					}
					if(count((\app\models\HPersediaanProduk::findAll(['nomor_produksi'=>$id])))>1){
						$validatingtransaction = false;
						$data['message'] = Yii::t('app', 'Data tidak bisa dihapus karena data ini pernah dilakukan transaksi');
					}
					// end validating transaction
					
                    if($model->delete() && $validatingtransaction){
                        $success_1 = true;
                    }
//					echo "<pre>1";
//					print_r($success_1);
//					echo "<pre>2";
//					print_r($success_2);
//					echo "<pre>3";
//					print_r($success_3);
//					exit;
                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Berhasil Dihapus');
                        $data['callback'] = '$( ".fa-close" ).click();';
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid,'actionname'=>'stockproduk']);
		}
		return $this->render('index',['model'=>$model]);
	}
    
    public function actionDeleteHasilProduksi(){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
            $id = Yii::$app->request->get('id');
			$model = \app\models\THasilProduksi::findOne(['hasil_produksi_id'=>$id]);
			$modRandom = \app\models\THasilProduksiRandom::find()->where(['hasil_produksi_id'=>$id])->all();
			$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_hasil_produksi
                    $success_2 = true; // t_hasil_produksi_random
                    $success_3 = false; // t_produksi
					$validatingtransaction = true;
					
					// start validating transaction
					if(!empty(\app\models\TTerimaKo::findOne(['nomor_produksi'=>$model->nomor_produksi]))){
						$validatingtransaction = false;
						$data['message'] = Yii::t('app', 'Data tidak bisa dihapus karena sudah dilakukan SCAN Terima Gudang');
					}
					if(empty($modProduksi)){
						$validatingtransaction = false;
						$data['message'] = Yii::t('app', 'Data tidak bisa dihapus karena data t_produksi tidak ditemukan');
					}
					// end validating transaction
					
                    if($modProduksi->delete() && $validatingtransaction){
                        $success_3 = true;
                        if(!empty($modRandom)){
                            \app\models\THasilProduksiRandom::deleteAll("hasil_produksi_id = ".$id);
                            $success_2 = true;
                        }
                        if($model->delete()){
                            $success_1 = true;
                        }
                    }
//					echo "<pre>1";
//					print_r($success_1);
//					echo "<pre>2";
//					print_r($success_2);
//					echo "<pre>3";
//					print_r($success_3);
//					exit;
                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Berhasil Dihapus');
                        $data['callback'] = '$( ".fa-close" ).click();';
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid,'actionname'=>'deleteHasilProduksi']);
		}
		return $this->render('index',['model'=>$model]);
	}
}
