<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ScanterimamutasiController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TTerimaMutasi();
		return $this->render('index',['model'=>$model]);
	}
    
    function actionGetItemsScanned(){
		if(\Yii::$app->request->isAjax){
            if(\Yii::$app->request->get('dt')=='table-master'){
                $param['table']= \app\models\TTerimaMutasi::tableName();
                $param['pk']= "terima_mutasi_id";
                $param['column'] = ['terima_mutasi_id',
                                    't_terima_mutasi.nomor_produksi',
                                    "CASE WHEN t_mutasi_keluar.cara_keluar = 'Penanganan Barang Retur' THEN CONCAT('<b>',b.produk_kode,'</b><br>',b.produk_dimensi) ELSE CONCAT('<b>',a.produk_kode,'</b><br>',a.produk_dimensi) END AS produk", 
                                    "CONCAT('<b>',t_pengajuan_repacking.kode,'</b><br>',t_pengajuan_repacking.tanggal,'/',t_pengajuan_repacking.keperluan) AS permintaan",
                                    "CASE WHEN t_mutasi_keluar.cara_keluar = 'Penanganan Barang Retur' THEN t_retur_produk_detail.qty_kecil ELSE t_terima_ko.qty_kecil END AS qty_kecil", 
                                    "CASE WHEN t_mutasi_keluar.cara_keluar = 'Penanganan Barang Retur' THEN t_retur_produk_detail.kubikasi ELSE t_terima_ko.qty_m3 END AS qty_m3", 
                                    't_terima_mutasi.created_at', 
                                    't_terima_mutasi.created_by',
                                    't_mutasi_keluar.cara_keluar'];
                $param['join'] = ["LEFT JOIN t_terima_ko ON t_terima_ko.nomor_produksi = t_terima_mutasi.nomor_produksi
                                  LEFT JOIN m_brg_produk a ON a.produk_id = t_terima_ko.produk_id
                                  JOIN t_mutasi_keluar ON t_mutasi_keluar.nomor_produksi = t_terima_mutasi.nomor_produksi
                                  JOIN t_pengajuan_repacking ON t_pengajuan_repacking.pengajuan_repacking_id = t_mutasi_keluar.pengajuan_repacking_id
                                  LEFT JOIN t_retur_produk_detail ON t_retur_produk_detail.nomor_produksi = t_terima_mutasi.nomor_produksi
                                  LEFT JOIN m_brg_produk b ON b.produk_id = t_retur_produk_detail.produk_id"];
                $param['where'] = " NOT EXISTS (SELECT terima_mutasi_id FROM map_terimamutasi_hasilrepacking WHERE map_terimamutasi_hasilrepacking.terima_mutasi_id = 
				                    t_terima_mutasi.terima_mutasi_id)";
                $param['order'] = "created_at DESC";
                return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
            }
        }
    }
    
    public function actionSaveNomorProduksi(){
		if(\Yii::$app->request->isAjax){
			$data['status'] = false;
			$data['msg'] = "";
			$nomor_produksi = \Yii::$app->request->post('prod_number');
            $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modTerima = \app\models\TTerimaMutasi::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modMutasi = \app\models\TMutasiKeluar::findOne(['nomor_produksi'=>$nomor_produksi]);
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_terima_mutasi
                if(empty($modTerima)){
                    if(!empty($modMutasi)){
                        $modRepacking = \app\models\TPengajuanRepacking::findOne($modMutasi->pengajuan_repacking_id);
                        if(!empty($modRepacking)){
                            $modTerima = new \app\models\TTerimaMutasi();
                            $modTerima->kode = \app\components\DeltaGenerator::terimaMutasiProduk();
                            $modTerima->reff_no = $modMutasi->kode;
                            $modTerima->reff_no2 = $modRepacking->kode;
                            $modTerima->nomor_produksi = $nomor_produksi;
                            $modTerima->tanggal = date('Y-m-d');
                            $modTerima->pegawai_terima = \Yii::$app->user->identity->pegawai_id;
                            $modTerima->keterangan = 'Penerimaan Mutasi Dari Gudang Oleh PPIC';
                            if($modTerima->validate()){
                                if($modTerima->save()){
                                    $success_1 = true;
                                }
                            }
                        }else{
                            $data['status'] = false;
                            $data['msg'] = "Data Permintaan tidak ditemukan!";
                        }
                    }else{
                        $data['status'] = false;
                        $data['msg'] = "Tidak ditemukan data Mutasi Keluar!";
                    }
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Produk sudah pernah diterima!";
                }
                
            //    echo "<pre>";
            //    print_r($success_1);
            //    exit;
                
                if ($success_1 ) {
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
			$model = \app\models\TTerimaMutasi::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_terima_mutasi
                    if(!empty($model)){
                        if($model->delete()){
                            $success_1 = true;
                        }
                    }

    //                echo "<pre>";
    //                print_r($success_1);
    //                exit;

                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['callback'] = "$('#table-master').dataTable().fnClearTable();";
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

    public function actionShowDetail(){
		if(\Yii::$app->request->isAjax){
			$data['status'] = false;
			$data['msg'] = "";
			$nomor_produksi = \Yii::$app->request->post('prod_number');
            $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modTerima = \app\models\TTerimaMutasi::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modMutasi = \app\models\TMutasiKeluar::findOne(['nomor_produksi'=>$nomor_produksi]);

            if(empty($modTerima)){
                if(!empty($modMutasi)){
                    $modRepacking = \app\models\TPengajuanRepacking::findOne($modMutasi->pengajuan_repacking_id);
                    if(!empty($modRepacking)){
                        $data['msg'] = 'Data ok';
                    }else{
                        $data['status'] = false;
                        $data['msg'] = "Data Permintaan tidak ditemukan!";
                    }
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Tidak ditemukan data Mutasi Keluar!";
                }
            }else{
                $data['status'] = false;
                $data['msg'] = "Produk sudah pernah diterima!";
            }
		} else {
            $data['status'] = false;
            $data['msg'] = "xxx";
        }
        return $this->asJson($data);
	}

    public function actionReview(){
        if(\Yii::$app->request->isAjax){
            $nomor_produksi = \Yii::$app->request->get('nomor_produksi');
            // $modretur = \app\models\TReturProdukDetail::findOne(['nomor_produksi'=>$nomor_produksi]);
            // $moddetail = \app\models\TPengajuanRepackingDetail::findOne(['retur_produk_detail_id'=>$modretur->retur_produk_detail_id]);
            // $pengajuan_repacking_id = $moddetail->pengajuan_repacking_id;
            // $modRepacking = \app\models\TPengajuanRepacking::findOne($pengajuan_repacking_id);
            $modMutasi = \app\models\TMutasiKeluar::findOne(['nomor_produksi'=>$nomor_produksi]);
            $pengajuan_repacking_id = $modMutasi->pengajuan_repacking_id;
			$modRepacking = \app\models\TPengajuanRepacking::findOne($pengajuan_repacking_id);
            if($modRepacking->keperluan == 'Penanganan Barang Retur'){
                $modProduksi = \app\models\TReturProdukDetail::findOne(['nomor_produksi'=>$nomor_produksi]);
                $modPersediaan = \app\models\TPengajuanRepackingDetail::findOne(['retur_produk_detail_id'=>$modProduksi->retur_produk_detail_id]);
            } else {
                $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
                $modPersediaan = \app\models\HPersediaanProduk::findOne(['nomor_produksi'=>$nomor_produksi]);
            }
            $modHasilProduksi = \app\models\THasilProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modBrgProduk = \app\models\MBrgProduk::findOne(['produk_id'=>$modProduksi->produk_id]);
            $modProdukKeluar = \app\models\TProdukKeluar::findOne(['nomor_produksi'=>$nomor_produksi]);
            $jml_ajuan = $this->getTotalpaletpermintaan($pengajuan_repacking_id);
            $jml_termutasi = count(\app\models\TMutasiKeluar::find()->where("pengajuan_repacking_id = ".$pengajuan_repacking_id)->all());
            return $this->renderAjax('_review',['pengajuan_repacking_id'=>$pengajuan_repacking_id, 
                                                'nomor_produksi'=>$nomor_produksi, 
                                                'modRepacking'=>$modRepacking,
                                                'modProduksi'=>$modProduksi,
                                                'modHasilProduksi'=>$modHasilProduksi,
                                                'modBrgProduk'=>$modBrgProduk,
                                                'modPersediaan'=>$modPersediaan,
                                                'modMutasi'=>$modMutasi,
                                                'modProdukKeluar'=>$modProdukKeluar,
                                                'jml_ajuan'=>$jml_ajuan,
                                                'jml_termutasi'=>$jml_termutasi,
                                                ]);
        }
    }
}
