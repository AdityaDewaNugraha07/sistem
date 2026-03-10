<?php

namespace app\modules\gudang\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class MutasikeluarController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TMutasiKeluar();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->pegawai_mutasi = \Yii::$app->user->identity->pegawai_id;
        $model->cara_keluar = "Lokal";
        $modProduk = new \app\models\MBrgProduk();
        $modPersediaan = new \app\models\HPersediaanProduk();
        
        if(isset($_GET['mutasi_keluar_id'])){
            $model = \app\models\TMutasiKeluar::findOne($_GET['mutasi_keluar_id']);
            //$modPersediaan = \app\models\HPersediaanProduk::find()->where("nomor_produksi = '$model->nomor_produksi' AND out_qty_kecil > 0 ")->one();
            $modPersediaan = \app\models\HPersediaanProduk::find()->where("nomor_produksi = '$model->nomor_produksi' AND out_qty_kecil > 0")->orderBy("persediaan_produk_id desc")->one();
			$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
			$modProduk = \app\models\MBrgProduk::findOne($modProduksi->produk_id);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_produksi = \app\components\DeltaFormatter::formatDateTimeForUser2($modProduksi->tanggal_produksi);
            $model->produk_nama = $modProduk->produk_nama;
            $model->produk_jenis = $modProduk->produk_group;
            $model->produk_dimensi = $modProduk->produk_dimensi;
            $model->gudang_asal_display = $model->gudangAsal->gudang_nm;
			$modProduk->produk_qty_satuan_kecil = $modPersediaan->out_qty_kecil;
			$modProduk->produk_satuan_kecil = $modPersediaan->out_qty_kecil_satuan;
			$modProduk->kapasitas_kubikasi = $modPersediaan->out_qty_m3;
        }
		
        if( Yii::$app->request->post('TMutasiKeluar')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_mutasi_keluar
                $success_2 = true; // h_persediaan_produk (OUT)
                $model->load(\Yii::$app->request->post());
				$model->kode = \app\components\DeltaGenerator::mutasiKayuOlahan();
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						
						if(\app\models\HPersediaanProduk::getCurrentStockPerPalet($_POST['TMutasiKeluar']['nomor_produksi'])['palet']>0){
							// Start Proses Update Stock (OUT)
							$modPersediaan = new \app\models\HPersediaanProduk();
							$modPersediaan->attributes = $model->attributes;
							$modPersediaan->produk_id = $_POST['MBrgProduk']['produk_id'];
							$modPersediaan->tgl_transaksi = $model->tanggal;
							$modPersediaan->reff_no = $model->kode;
                            //$modPersediaan->gudang_id = $model->gudang_asal;
                                $gudang_asal = $_POST['TMutasiKeluar']['gudang_asal_display'];
                                $sql_gudang_id = "select gudang_id from m_gudang where gudang_kode = '".$gudang_asal."'";
                                $gudang_id = Yii::$app->db->createCommand($sql_gudang_id)->queryScalar();
                                $modPersediaan->gudang_id = $gudang_id;
                                $model->gudang_asal = $gudang_asal;
							$modPersediaan->in_qty_palet = 0;
							$modPersediaan->in_qty_kecil = 0;
							$modPersediaan->in_qty_kecil_satuan = $_POST['MBrgProduk']['produk_satuan_kecil'];
							$modPersediaan->in_qty_m3 = 0;
							$modPersediaan->out_qty_palet = $_POST['MBrgProduk']['produk_qty_satuan_besar'];
							$modPersediaan->out_qty_kecil = $_POST['MBrgProduk']['produk_qty_satuan_kecil'];
							$modPersediaan->out_qty_kecil_satuan = $_POST['MBrgProduk']['produk_satuan_kecil'];
							$modPersediaan->out_qty_m3 = $_POST['MBrgProduk']['kapasitas_kubikasi'];
							$modPersediaan->keterangan = "MUTASI KELUAR UNTUK ".$model->cara_keluar;
							$success_2 = \app\models\HPersediaanProduk::updateStokPersediaan($modPersediaan);
							// End Proses Update Stock (OUT)
						}else{
							$success_2 = false;
							$errmsg = "Out of Stock";
						}
                    }
                }
				
				/*echo "<pre>";
                echo "<pre>gudang asal = ".$model->gudang_asal;
                echo "<pre>post = ".$_POST['TMutasiKeluar']['gudang_asal_display'];
				exit;*/
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'mutasi_keluar_id'=>$model->mutasi_keluar_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modProduk'=>$modProduk,'modPersediaan'=>$modPersediaan]);
	}
	
	public function actionFindProdukActive(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			if(!empty($term)){
				$query = "
					SELECT nomor_produksi, SUM(in_qty_palet), SUM(out_qty_palet), SUM(in_qty_kecil), SUM(out_qty_kecil) FROM h_persediaan_produk
					where nomor_produksi = '{$term}' GROUP BY nomor_produksi HAVING SUM(in_qty_palet)-SUM(out_qty_palet) > 0;";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['produk_id'], 'text'=>$val['produk_kode']];
					}
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
//				$param['join']= ['JOIN (SELECT MAX(persediaan_produk_id) AS persediaan_produk_id, gudang_id 
//                                        FROM h_persediaan_produk
//                                        WHERE nomor_produksi = '.$param['table'].'.nomor_produksi)
//                                  LEFT JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id'];
                $param['where'] = "h_persediaan_produk.out_qty_kecil > 0 AND h_persediaan_produk.keterangan NOT ILIKE '%MUTASI DARI GUDANG%'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionScanMutasi(){
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('scanMutasi');
		}
	}
	
	public function actionTransaksiCepat(){
		$model = new \app\models\TMutasiKeluar();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
		return $this->render('transaksiCepat',['model'=>$model]);
	}
    
}
