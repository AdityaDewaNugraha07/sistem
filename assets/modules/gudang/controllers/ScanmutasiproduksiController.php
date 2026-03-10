<?php

namespace app\modules\gudang\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ScanmutasiproduksiController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TMutasiKeluar();
		return $this->render('index',['model'=>$model]);
	}
    
    function actionGetItemsScanned(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data = [];
			$data['html'] = '';
			$data['status'] = "";
            if(!empty($id)){
                $modRepacking = \app\models\TPengajuanRepacking::findOne($id);
				$data['status'] = $modRepacking->status;
				$models = \app\models\TMutasiKeluar::find()->where(["pengajuan_repacking_id"=>$id])->orderBy("mutasi_keluar_id DESC")->all();
				if(count($models)>0){
					foreach($models as $i => $model){
                        $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
                        $modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$model->nomor_produksi]);
                        $model->qty_kecil = $modTerima->qty_kecil;
                        $model->qty_m3 = number_format($modTerima->qty_m3,4);
						$data['html'] .= $this->renderPartial('_item',['model'=>$model,'modProduksi'=>$modProduksi]);
					}
				}
            }
            return $this->asJson($data);
        }
    }
    
    public function actionSaveNomorProduksi(){
		if(\Yii::$app->request->isAjax){
			$data['status'] = false;
			$data['msg'] = "";
			$prod_number = \Yii::$app->request->post('prod_number');
			$pengajuan_repacking_id = \Yii::$app->request->post('pengajuan_repacking_id');
			$modRepacking = \app\models\TPengajuanRepacking::findOne($pengajuan_repacking_id);
			$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$prod_number]);
			$modPersediaan = \app\models\HPersediaanProduk::getDataByNomorProduksi($prod_number);
			$modMutasi = \app\models\TMutasiKeluar::findOne(['nomor_produksi'=>$prod_number]);
            $modProdukKeluar = \app\models\TProdukKeluar::findOne(['nomor_produksi'=>$prod_number]);
            $jml_ajuan = $this->getTotalpaletpermintaan($pengajuan_repacking_id);
            $jml_termutasi = count(\app\models\TMutasiKeluar::find()->where("pengajuan_repacking_id = ".$pengajuan_repacking_id)->all());
            $modjml_ajuan_produk_ini = \app\models\TPengajuanRepackingDetail::find()->where("pengajuan_repacking_id = ".$pengajuan_repacking_id." AND produk_id = ".$modProduksi->produk_id)->one();
            $jml_ajuan_produk_ini = !empty($modjml_ajuan_produk_ini)?$modjml_ajuan_produk_ini->qty_besar:0;
            $jml_termutasi_produk_ini = count(\app\models\TMutasiKeluar::find()->join("JOIN", "t_produksi", "t_produksi.nomor_produksi = t_mutasi_keluar.nomor_produksi")->where("pengajuan_repacking_id = ".$pengajuan_repacking_id." AND produk_id = ".$modProduksi->produk_id)->all());
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_mutasi_keluar
                $success_2 = false; // h_persediaan_produk (OUT)
                $success_3 = false; // t_pengajuan_repacking (update status)
                
                if(!empty($modProduksi)){
                    if( (empty($modMutasi)) && (empty($modProdukKeluar)) ){
                        $checkRepackingdetail = \app\models\TPengajuanRepackingDetail::find()->where(['pengajuan_repacking_id'=>$pengajuan_repacking_id,'produk_id'=>$modProduksi->produk_id])->all();
                        if($jml_termutasi < $jml_ajuan){
                            $repackingsama = true;
                            if(count($checkRepackingdetail)>0){
                                $repackingsama = true;
                            }else{
                                $repackingsama = false;
                            }
                            if($repackingsama){
                                if($jml_termutasi_produk_ini < $jml_ajuan_produk_ini){
                                    if(!empty($modPersediaan)){
                                        if(!empty($modRepacking)){
                                            $modMutasi = new \app\models\TMutasiKeluar();
                                            $modMutasi->attributes = $modProduksi->attributes;
                                            $modMutasi->kode = \app\components\DeltaGenerator::mutasiKayuOlahan();
                                            $modMutasi->tanggal = date('Y-m-d');
                                            $modMutasi->pegawai_mutasi = \Yii::$app->user->identity->pegawai_id;
                                            $modMutasi->cara_keluar = "Kembali Produksi";
                                            $modMutasi->gudang_asal = $modPersediaan['gudang_id'];
                                            $modMutasi->keterangan = 'Scan Result';
                                            $modMutasi->pengajuan_repacking_id = $pengajuan_repacking_id;
                                            if($modMutasi->validate()){
                                                if($modMutasi->save()){
                                                    $success_1 = true;
                                                    // Start Proses Update Stock (OUT)
                                                    $modStock = new \app\models\HPersediaanProduk();
                                                    $modStock->attributes = $modMutasi->attributes;
                                                    $modStock->produk_id = $modProduksi->produk_id;
                                                    $modStock->tgl_transaksi = $modMutasi->tanggal;
                                                    $modStock->reff_no = $modMutasi->kode;
                                                    $modStock->gudang_id = $modMutasi->gudang_asal;
                                                    $modStock->in_qty_palet = 0;
                                                    $modStock->in_qty_kecil = 0;
                                                    $modStock->in_qty_kecil_satuan = 'Pcs';
                                                    $modStock->in_qty_m3 = 0;
                                                    $modStock->out_qty_palet = '1';
                                                    $modStock->out_qty_kecil = $modPersediaan['qty_kecil'];
                                                    $modStock->out_qty_kecil_satuan = 'Pcs';
                                                    $modStock->out_qty_m3 = $modPersediaan['kubikasi'];
                                                    $modStock->keterangan = "MUTASI KELUAR UNTUK ".$modMutasi->cara_keluar;
                                                    $success_2 = \app\models\HPersediaanProduk::updateStokPersediaan($modStock);
                                                    // End Proses Update Stock (OUT)

                                                    $jml_termutasi = count(\app\models\TMutasiKeluar::find()->where("pengajuan_repacking_id = ".$pengajuan_repacking_id)->all());
                                                    if( $jml_ajuan > $jml_termutasi ){
                                                        $status = "MUTASI INPROGRESS";
                                                    }else{
                                                        $status = "MUTASI COMPLETE";
                                                    }
                                                    $modRepacking->status = $status;
                                                    if($modRepacking->validate()){
                                                        if($modRepacking->save()){
                                                            $success_3 = true;
                                                        }else{
                                                            $success_3 = false;
                                                        }
                                                    }else{
                                                        $success_3 = false;
                                                    }
                                                }
                                            }
                                        }else{
                                            $data['status'] = false;
                                            $data['msg'] = "Data Permintaan tidak ditemukan!";
                                        }
                                    }else{
                                        $data['status'] = false;
                                        $data['msg'] = "Tidak tersedia di stock!";
                                    }
                                }else{
                                    $data['status'] = false;
                                    $data['msg'] = "Mutasi untuk produk ".$modProduksi->produk->produk_kode." sudah mencapai batas maksimal";
                                }
                            }else{
                                $data['status'] = false;
                                $data['msg'] = "Produk tidak sesuai dengan Permintaan";
                            }
                        }else{
                            $data['status'] = false;
                            $data['msg'] = "Item sudah mencapai ".$jml_termutasi." Palet!";
                        }
                    }else{
                        $data['status'] = false;
                        $data['msg'] = "Produk sudah pernah keluar / Scan SPM!";
                    }
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Produk tidak ditemukan!";
                }
                
                
//                echo "<pre>";
//                print_r($success_1);
//                echo "<pre>";
//                print_r($success_2);
//                echo "<pre>";
//                print_r($success_3);
//                exit;
                
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
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
    
    public function actionDeleteNomorProduksi($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TMutasiKeluar::findOne($id);
			$modStock = \app\models\HPersediaanProduk::findOne(['reff_no'=>$model->kode]);
			$modRepacking = \app\models\TPengajuanRepacking::findOne($model->pengajuan_repacking_id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {

                    $success_1 = false; // t_mutasi_keluar
                    $success_2 = false; // h_persediaan_produk (OUT)
                    $success_3 = false; // t_pengajuan_repacking (update status)

                    if(!empty($modStock)){
                        if($modStock->delete()){
                            $success_2 = true;
                        }
                    }
                    if(!empty($model)){
                        if($model->delete()){
                            $success_1 = true;
                        }
                    }
                    if(!empty($modRepacking)){
                        $jml_ajuan = $this->getTotalpaletpermintaan($model->pengajuan_repacking_id);
                        $jml_termutasi = count(\app\models\TMutasiKeluar::find()->where("pengajuan_repacking_id = ".$model->pengajuan_repacking_id)->all());
                        if($jml_termutasi != 0){
                            if( $jml_ajuan > $jml_termutasi ){
                                $status = "MUTASI INPROGRESS";
                            }else{
                                $status = "MUTASI COMPLETE";
                            }
                        }else{
                            $status = "SEDANG DIAJUKAN";
                        }
                        $modRepacking->status = $status;
                        if($modRepacking->validate()){
                            if($modRepacking->save()){
                                $success_3 = true;
                            }else{
                                $success_3 = false;
                            }
                        }else{
                            $success_3 = false;
                        }
                    }

    //                echo "<pre>";
    //                print_r($success_1);
    //                echo "<pre>";
    //                print_r($success_2);
    //                echo "<pre>";
    //                print_r($success_3);
    //                exit;

                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['callback'] = "getItemsScanned(".$model->pengajuan_repacking_id.");";
    //						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>'deleteNomorProduksi']);
		}
	}
    
    public function getTotalpaletpermintaan($pengajuan_repacking_id){
        $jml = 0;
        $modPengajuanDetail = \app\models\TPengajuanRepackingDetail::find()->where("pengajuan_repacking_id = ".$pengajuan_repacking_id)->all();
        if(count($modPengajuanDetail)>0){
            foreach($modPengajuanDetail as $iv => $ajuandet){
                $jml += $ajuandet->qty_besar;
            }
        }
        return $jml;
    }
}
