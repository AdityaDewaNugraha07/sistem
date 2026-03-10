<?php

namespace app\modules\gudang\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class MutasiproduksiController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TMutasiKeluar();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->pegawai_mutasi = \Yii::$app->user->identity->pegawai_id;
        $model->cara_keluar = "Kembali Produksi";
        
        if(isset($_GET['kode_permintaan'])){
            $modPengajuan = \app\models\TPengajuanRepacking::findOne(['kode'=>$_GET['kode_permintaan']]);
            $model = \app\models\TMutasiKeluar::findOne(['pengajuan_repacking_id'=>$modPengajuan->pengajuan_repacking_id]);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->kode_permintaan = $_GET['kode_permintaan'];
        }
		
        if( Yii::$app->request->post('TMutasiKeluar')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = true; // t_mutasi_keluar
                $success_2 = true; // h_persediaan_produk (OUT)
                $success_3 = false; // t_pengajuan_repacking (update status)

                $modPengajuan = \app\models\TPengajuanRepacking::findOne(['kode'=>$_POST['TMutasiKeluar']['kode_permintaan']]);
                $modPengajuanDetail = \app\models\TPengajuanRepackingDetail::find()->where("pengajuan_repacking_id = ".$modPengajuan->pengajuan_repacking_id)->all();
                $modMutasi = \app\models\TMutasiKeluar::find()->where("pengajuan_repacking_id = ".$modPengajuan->pengajuan_repacking_id)->all();
                
                if(count($_POST['TMutasiKeluar'])>0){
                    $posts = [];
                    foreach ($_POST['TMutasiKeluar'] as $i => $post){
                        if(is_array($post)){
                            if(empty($post['mutasi_keluar_id'])){
                                $posts[] = $post;
                                $model = new \app\models\TMutasiKeluar();
                                $model->attributes = $post;
                                $model->kode = \app\components\DeltaGenerator::mutasiKayuOlahan();
                                $model->tanggal = $_POST['TMutasiKeluar']['tanggal'];
                                $model->pegawai_mutasi = $_POST['TMutasiKeluar']['pegawai_mutasi'];
                                $model->keterangan = $_POST['TMutasiKeluar']['keterangan'];
                                $model->pengajuan_repacking_id = $_POST['TMutasiKeluar']['pengajuan_repacking_id'];
                                if($model->validate()){
                                    if($model->save()){
                                        $success_1 = true;
                                        if(\app\models\HPersediaanProduk::getCurrentStockPerPalet($model->nomor_produksi)['palet']>0){
                                            // Start Proses Update Stock (OUT)
                                            $modPersediaan = new \app\models\HPersediaanProduk();
                                            $modPersediaan->attributes = $model->attributes;
                                            $modPersediaan->produk_id = $post['produk_id'];
                                            $modPersediaan->tgl_transaksi = $model->tanggal;
                                            $modPersediaan->reff_no = $model->kode;
                                            $modPersediaan->gudang_id = $model->gudang_asal;
                                            $modPersediaan->in_qty_palet = 0;
                                            $modPersediaan->in_qty_kecil = 0;
                                            $modPersediaan->in_qty_kecil_satuan = 'Pcs';
                                            $modPersediaan->in_qty_m3 = 0;
                                            $modPersediaan->out_qty_palet = '1';
                                            $modPersediaan->out_qty_kecil = $post['qty_kecil'];
                                            $modPersediaan->out_qty_kecil_satuan = 'Pcs';;
                                            $modPersediaan->out_qty_m3 = $post['qty_m3'];
                                            $modPersediaan->keterangan = "MUTASI KELUAR UNTUK ".$model->cara_keluar;
                                            $success_2 = \app\models\HPersediaanProduk::updateStokPersediaan($modPersediaan);
                                            // End Proses Update Stock (OUT)
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if(isset($_POST['TMutasiKeluar']['kode_permintaan'])){
                        $jml_ajuan = 0;
                        if(count($modPengajuanDetail)>0){
                            foreach($modPengajuanDetail as $iv => $ajuandet){
                                $jml_ajuan += $ajuandet->qty_besar;
                            }
                        }
                        $jml_post = count($posts);
                        $jml_mutasi = count($modMutasi);
                        if( $jml_ajuan > ($jml_post + $jml_mutasi) ){
                            $status = "MUTASI INPROGRESS";
                        }else{
                            $status = "MUTASI COMPLETE";
                        }
                        $modPengajuan->status = $status;
                        if($modPengajuan->validate()){
                            if($modPengajuan->save()){
                                $success_3 = true;
                            }else{
                                $success_3 = false;
                            }
                        }else{
                            $success_3 = false;
                        }
                    }
                }
                
//				
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>3";
//				print_r($success_3);
//				exit;
                
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'kode_permintaan'=>$_POST['TMutasiKeluar']['kode_permintaan']]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model]);
	}
    
    public function actionSetParent(){
		if(\Yii::$app->request->isAjax){
            $data = [];
			$kode_permintaan = \Yii::$app->request->post('kode_permintaan');
            if($kode_permintaan){
                $modPengajuan = \app\models\TPengajuanRepacking::findOne(['kode'=>$kode_permintaan]);
                $data = $modPengajuan->attributes;
            }
			return $this->asJson($data);
        }
    }
    
    public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $data = [];
			$kode_permintaan = \Yii::$app->request->post('kode_permintaan');
			$aftersave = \Yii::$app->request->post('aftersave');
			$edit = \Yii::$app->request->post('edit');
            $data['html'] = ''; $data['htmlmutasi']=''; $data['arrPermintaan'] = [];
            if($kode_permintaan){
                $modPengajuan = \app\models\TPengajuanRepacking::findOne(['kode'=>$kode_permintaan]);
                $modDetail = \app\models\TPengajuanRepackingDetail::find()->where("pengajuan_repacking_id = ".$modPengajuan->pengajuan_repacking_id)->orderBy('pengajuan_repacking_detail_id ASC')->all();
                if(count($modDetail)>0){
                    $data['total_palet'] = 0;
                    foreach($modDetail as $i => $detail){
                        $data['html'] .= $this->renderPartial('item',['detail'=>$detail]);
                        $data['total_palet'] += $detail->qty_besar;
                    }
                }
                $modMutasi = \app\models\TMutasiKeluar::find()->where(['pengajuan_repacking_id'=>$modPengajuan->pengajuan_repacking_id])->all();
                if(count($modMutasi)>0){
                    foreach($modMutasi as $ii => $mutasi){
                        $model = new \app\models\TMutasiKeluar();
                        $modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$mutasi->nomor_produksi]);
                        $modProduk = \app\models\MBrgProduk::findOne( $modTerima->produk_id );
                        $model->attributes = $mutasi->attributes;
                        $model->mutasi_keluar_id = $mutasi->mutasi_keluar_id;
                        $model->produk_id = $modProduk->produk_id;
                        $model->qty_kecil = $modTerima->qty_kecil;
                        $model->qty_m3 = number_format($modTerima->qty_m3,4);
                        $model->gudang_asal_display = $mutasi->gudangAsal->gudang_nm;
                        $data['htmlmutasi'] .= $this->renderPartial('itemMutasi',['model'=>$model,'edit'=>$edit,'aftersave'=>$aftersave,'modProduk'=>$modProduk]);
                    }
                }
            }
			return $this->asJson($data);
        }
    }
    
    public function actionAvailableProdukAtasPermintaan($pengajuan_repacking_id){
		if(\Yii::$app->request->isAjax){
            if(!empty($pengajuan_repacking_id)){
                $pengajuan = \app\models\TPengajuanRepackingDetail::find()->where("pengajuan_repacking_id = ".$pengajuan_repacking_id)->all();
                $pengajuan = \yii\helpers\ArrayHelper::map($pengajuan, 'produk_id','produk_id');
                $pengajuan = implode(",", $pengajuan);
                if(\Yii::$app->request->get('dt')=='table-produk'){
                    $param['table']= \app\models\HPersediaanProduk::tableName();
                    $param['pk']= \app\models\HPersediaanProduk::primaryKey()[0];
                    $param['column'] = [$param['table'].'.produk_id',
                                        'm_brg_produk.produk_kode',
                                        'm_brg_produk.produk_nama',
                                        $param['table'].'.nomor_produksi',
                                        't_produksi.tanggal_produksi',
                                        't_produksi.plymill_shift',
                                        't_produksi.sawmill_line',
                                        'sum(in_qty_kecil-out_qty_kecil) AS qty_kecil',
                                        'in_qty_kecil_satuan',
                                        'sum(in_qty_m3-out_qty_m3) AS kubikasi',
                                        ];
                    $param['group'] = 'GROUP BY '.$param['table'].'.produk_id, 
                                         m_brg_produk.produk_kode, 
                                         m_brg_produk.produk_nama, 
                                         in_qty_kecil_satuan,
                                         '.$param['table'].'.nomor_produksi,
                                         t_produksi.tanggal_produksi,
                                         t_produksi.plymill_shift,
                                         t_produksi.sawmill_line,
                                         in_qty_kecil_satuan
                                         ';
                    $param['join'] = ['JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
                                      JOIN t_produksi ON t_produksi.nomor_produksi = '.$param['table'].'.nomor_produksi'];
                    $param['where'] = $param['table'].'.produk_id IN('.$pengajuan.')';
                    $param['having'] = "HAVING SUM(in_qty_kecil-out_qty_kecil) > 0";
                    return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
                }
                return $this->renderAjax('@app/modules/gudang/views/availablestockproduk/currentstock',['url'=>\yii\helpers\Url::toRoute(['/gudang/mutasiproduksi/availableProdukAtasPermintaan','pengajuan_repacking_id'=>$pengajuan_repacking_id])]);
            }
        }
    }
    
    public function actionPick(){
		if(\Yii::$app->request->isAjax){
			$nomor_produksi = \Yii::$app->request->post('nomor_produksi');
			$data['html'] = ''; $data['nomor_produksi'] = $nomor_produksi;
            $model = new \app\models\TMutasiKeluar();
			if(!empty($nomor_produksi)){
                $modStock = \app\models\HPersediaanProduk::getCurrentStockPerPalet($nomor_produksi);
                if(!empty($modStock)){
                    $modProduk = \app\models\MBrgProduk::findOne($modStock['produk_id']);
                    $model->nomor_produksi = $modStock['nomor_produksi'];
                    $model->gudang_asal = $modStock['gudang_id'];
                    $model->gudang_asal_display = \app\models\MGudang::findOne($modStock['gudang_id'])->gudang_nm;
                    $model->cara_keluar = "Kembali Produksi";
                    $model->produk_id = $modStock['produk_id'];
                    $model->qty_kecil = $modStock['qty_kecil'];
                    $model->qty_m3 = number_format($modStock['kubikasi'],4);
                    $data['html'] .= $this->renderPartial('itemMutasi',['model'=>$model,'modStock'=>$modStock,'modProduk'=>$modProduk]);
                }
			}
			return $this->asJson($data);
		}
	}
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TMutasiKeluar::tableName();
				$param['pk']= $param['table'].".".\app\models\TMutasiKeluar::primaryKey()[0];
				$param['column'] = [$param['table'].'.mutasi_keluar_id',
									$param['table'].'.kode',
									$param['table'].'.tanggal',
									$param['table'].'.nomor_produksi',
									'm_brg_produk.produk_nama',
									'cara_keluar',
									'h_persediaan_produk.out_qty_kecil',
									'h_persediaan_produk.out_qty_kecil_satuan',
									'h_persediaan_produk.out_qty_m3'];
				$param['join']= ['JOIN h_persediaan_produk ON h_persediaan_produk.nomor_produksi = '.$param['table'].'.nomor_produksi
								  LEFT JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id'];
				$param['where'] = "h_persediaan_produk.out_qty_kecil > 0 AND h_persediaan_produk.keterangan NOT ILIKE '%MUTASI DARI GUDANG%'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
    
    public function actionStatuspermintaanbarangjadi(){
        $model = new \app\models\TPengajuanRepackingDetail();
		return $this->render('statuspermintaan',['model'=>$model]);
    }
}
