<?php

namespace app\modules\gudang\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class MutasigudangController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TMutasiGudang();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->pegawai_mutasi = \Yii::$app->user->identity->pegawai_id;
		$modProduk = new \app\models\MBrgProduk();
        
        if(isset($_GET['mutasi_gudang_id'])){
            $model = \app\models\TMutasiGudang::findOne($_GET['mutasi_gudang_id']);
			$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
			$modProduk = \app\models\MBrgProduk::findOne($modProduksi->produk_id);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_produksi = \app\components\DeltaFormatter::formatDateTimeForUser2($modProduksi->tanggal_produksi);
            $model->produk_nama = $modProduk->produk_nama;
            $model->produk_jenis = $modProduk->produk_group;
            $model->produk_dimensi = $modProduk->produk_dimensi;
            $model->gudang_asal_display = $model->gudangAsal->gudang_nm;
            $model->gudang_tujuan_display = $model->gudangTujuan->gudang_nm;
        }
		
        if( Yii::$app->request->post('TMutasiGudang')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_mutasi_gudang
                $success_2 = true; // h_persediaan_produk (OUT)
                $success_3 = true; // h_persediaan_produk (IN)
				$success_4 = true; // t_terima_ko (UPDATE gudang_id)
                $model->load(\Yii::$app->request->post());
				$model->kode = \app\components\DeltaGenerator::mutasiKayuOlahan();
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						
						// Start Proses Update Stock (OUT)
						$modPersediaan = new \app\models\HPersediaanProduk();
						$modPersediaan->attributes = $model->attributes;
						$modPersediaan->produk_id = $_POST['MBrgProduk']['produk_id'];
						$modPersediaan->tgl_transaksi = $model->tanggal;
						$modPersediaan->reff_no = $model->kode;
						$modPersediaan->gudang_id = $model->gudang_asal;
						$modPersediaan->in_qty_palet = 0;
						$modPersediaan->in_qty_kecil = 0;
						$modPersediaan->in_qty_kecil_satuan = $_POST['MBrgProduk']['produk_satuan_kecil'];
						$modPersediaan->in_qty_m3 = 0;
						$modPersediaan->out_qty_palet = $_POST['MBrgProduk']['produk_qty_satuan_besar'];
						$modPersediaan->out_qty_kecil = $_POST['MBrgProduk']['produk_qty_satuan_kecil'];
						$modPersediaan->out_qty_kecil_satuan = $_POST['MBrgProduk']['produk_satuan_kecil'];
						$modPersediaan->out_qty_m3 = $_POST['MBrgProduk']['kapasitas_kubikasi'];
						$modPersediaan->keterangan = "MUTASI DARI GUDANG ".$model->gudangAsal->gudang_kode." KE GUDANG ".$model->gudangTujuan->gudang_kode;
						$success_2 = \app\models\HPersediaanProduk::updateStokPersediaan($modPersediaan);
						// End Proses Update Stock (OUT)
						// Start Proses Update Stock (IN)
						$modPersediaan = new \app\models\HPersediaanProduk();
						$modPersediaan->attributes = $model->attributes;
						$modPersediaan->produk_id = $_POST['MBrgProduk']['produk_id'];
						$modPersediaan->tgl_transaksi = $model->tanggal;
						$modPersediaan->reff_no = $model->kode;
						$modPersediaan->gudang_id = $model->gudang_tujuan;
						$modPersediaan->in_qty_palet = $_POST['MBrgProduk']['produk_qty_satuan_besar'];
						$modPersediaan->in_qty_kecil = $_POST['MBrgProduk']['produk_qty_satuan_kecil'];
						$modPersediaan->in_qty_kecil_satuan = $_POST['MBrgProduk']['produk_satuan_kecil'];
						$modPersediaan->in_qty_m3 = $_POST['MBrgProduk']['kapasitas_kubikasi'];
						$modPersediaan->out_qty_palet = 0;
						$modPersediaan->out_qty_kecil = 0;
						$modPersediaan->out_qty_kecil_satuan = $_POST['MBrgProduk']['produk_satuan_kecil'];
						$modPersediaan->out_qty_m3 = 0;
						$modPersediaan->keterangan = "MUTASI DARI GUDANG ".$model->gudangAsal->gudang_kode." KE GUDANG ".$model->gudangTujuan->gudang_kode;
						$success_3 = \app\models\HPersediaanProduk::updateStokPersediaan($modPersediaan);
						// End Proses Update Stock (IN)
						
						$modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$model->nomor_produksi]);
						$modTerima->gudang_id = $model->gudang_tujuan;
						if($modTerima->validate()){
							if($modTerima->save()){
								$success_4 = true;
							}else{
								$success_4 = false;
							}
						}else{
							$success_4 = false;
						}
                    }
                }
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>2";
//				print_r($success_3);
//				echo "<pre>2";
//				print_r($success_4);
//				exit;
                if ($success_1 && $success_2 && $success_3 && $success_4) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'mutasi_gudang_id'=>$model->mutasi_gudang_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modProduk'=>$modProduk]);
	}
	
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TMutasiGudang::tableName();
				$param['pk']= $param['table'].".".\app\models\TMutasiGudang::primaryKey()[0];
				$param['column'] = [$param['table'].'.mutasi_gudang_id',
									$param['table'].'.kode',
									$param['table'].'.tanggal',
									$param['table'].'.nomor_produksi',
									'm_brg_produk.produk_nama',
									'm_gudang.gudang_nm AS gudang_tujuan',
									'm_brg_produk.produk_qty_satuan_kecil',
									'm_brg_produk.produk_satuan_kecil',
									'm_brg_produk.kapasitas_kubikasi'];
				$param['join']= ['JOIN m_gudang ON m_gudang.gudang_id = '.$param['table'].'.gudang_tujuan 
								  JOIN t_produksi ON t_produksi.nomor_produksi = '.$param['table'].'.nomor_produksi
								  JOIN m_brg_produk ON m_brg_produk.produk_id = t_produksi.produk_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionPrintKartuBarang(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TTerimaKo::findOne($_GET['id']);
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'KARTU BARANG');
		if($caraprint == 'PRINT'){
			return $this->render('printKartuBarang',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printKartuBarang',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printKartuBarang',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
	
	public function actionPick(){
		if(\Yii::$app->request->isAjax){
			$prod_number = \Yii::$app->request->post('prod_number');
			$modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$prod_number]);
			$data = [];
			$cek = \app\models\HPersediaanProduk::getCurrentStockPerPalet($prod_number);
			if($cek['palet'] > 0){
				if(!empty($modTerima)){
					$modPersediaan = \app\models\HPersediaanProduk::findOne(['reff_no'=>$modTerima->kode,'produk_id'=>$modTerima->produk_id]);
					if(!empty($modPersediaan)){
						$data = $modPersediaan->attributes;
					}
				}
			}else{
				$data['msg'] = "Palet tidak tersedia di Stock";
			}
			return $this->asJson($data);
		}
	}
	
	public function actionScanMutasi(){
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('scanMutasi');
		}
	}
	
	public function actionSetDropdownGudangtujuan(){
        if(\Yii::$app->request->isAjax){
			$gudang_asal = Yii::$app->request->post('gudang_asal');
            $mod = [];
			if(!empty($gudang_asal)){
				$mod = \app\models\MGudang::find()->where("active IS TRUE AND gudang_id != ".$gudang_asal)->orderBy('gudang_nm ASC')->all();
			}
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'gudang_id', 'gudang_nm');
			$html = '';
			foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	
	public function actionTransaksiCepat(){
		$model = new \app\models\TMutasiGudang();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
		return $this->render('transaksiCepat',['model'=>$model]);
	}
    
}
