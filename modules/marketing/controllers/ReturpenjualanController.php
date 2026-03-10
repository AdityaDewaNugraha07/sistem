<?php

namespace app\modules\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ReturpenjualanController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TReturProduk();
        $model->kode = "Auto Generate";
		$modTempo = new \app\models\TTempobayarKo();
        if(isset($_GET['retur_produk_id'])){
            $model = \app\models\TReturProduk::findOne($_GET['retur_produk_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
        }
		
        if( Yii::$app->request->post('TReturProduk')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_retur_produk
                $success_2 = true; // t_retur_produk_detail
                $model->load(\Yii::$app->request->post());
				$modSp = New \app\models\TSuratPengantar();
				$modSp->attributes = $model->attributes;
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeReturProduk();
				}
                $model->total_retur = \app\components\DeltaFormatter::formatNumberForDb2($model->total_retur);
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if((isset($_GET['edit'])) && (isset($_GET['retur_produk_id']))){
							$modDetail = \app\models\TReturProdukDetail::find()->where(['retur_produk_id'=>$_GET['retur_produk_id']])->all();
							if(count($modDetail)>0){
								\app\models\TReturProdukDetail::deleteAll(['retur_produk_id'=>$_GET['retur_produk_id']]);
							}
						}
						foreach($_POST['TReturProdukDetail'] as $i => $detail){
							$modDetail = new \app\models\TReturProdukDetail();
							$modDetail->attributes = $detail;
							$modDetail->retur_produk_id = $model->retur_produk_id;
							if($modDetail->validate()){
								if($modDetail->save()){
									$success_2 &= true;
								}else{
									$success_2 = false;
								}
							}else{
								$success_2 = false;
							}
						}
                    }
                }
				
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				exit;
				
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'retur_produk_id'=>$model->retur_produk_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modTempo'=>$modTempo]);
	}
	
	public function actionSetNota(){
		if(\Yii::$app->request->isAjax){
			$nota_penjualan_id = \Yii::$app->request->post('nota_penjualan_id');
			$data = []; $data['tempo'] = [];
			if(!empty($nota_penjualan_id)){
				$model = \app\models\TNotaPenjualan::findOne($nota_penjualan_id);
				$modCust = \app\models\MCustomer::findOne($model->cust_id);
				$modOp = \app\models\TOpKo::findOne($model->op_ko_id);
				$modTempo = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$modOp->op_ko_id]);
				if(!empty($model)){
					$data = $model->attributes;
				}
				if(!empty($modCust)){
					$data['cust'] = $modCust->attributes;
					$data['cust']['cust_pr_nama'] = (!empty($modCust->cust_pr_nama)?$modCust->cust_pr_nama:"-");
				}
				if(!empty($modOp)){
					$data['jenis_produk'] = $modOp->jenis_produk;
					$data['op'] = $modOp->attributes;
					$data['dokumen_penjualan'] = \app\components\DeltaGenerator::dokumenPenjualan($modOp->jenis_produk);
				}
				if(!empty($modTempo)){
					$data['tempo'] = $modTempo->attributes;
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionOpenNota(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-nota'){
				$param['table']= \app\models\TNotaPenjualan::tableName();
				$param['pk']= $param['table'].".".\app\models\TNotaPenjualan::primaryKey()[0];
				$param['column'] = [$param['table'].'.nota_penjualan_id',
									$param['table'].'.kode',
									$param['table'].'.jenis_produk',
									$param['table'].'.tanggal',
									'm_customer.cust_an_nama',
									'syarat_jual',
									'sistem_bayar',
									'cara_bayar',
									'total_harga',
									];
				$param['join']= ['JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id'];
//				$param['where']="cancel_transaksi_id IS NULL AND nota_penjualan_id NOT IN( SELECT nota_penjualan_id FROM t_retur_produk WHERE cancel_transaksi_id IS NULL )";
                $param['where']="cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('nota');
        }
	}
	
	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $nota_penjualan_id = Yii::$app->request->post('nota_penjualan_id');
			$modNota = \app\models\TNotaPenjualan::findOne($nota_penjualan_id);
			$modCust = \app\models\MCustomer::findOne($modNota->cust_id);
			$modDetail = [];
            $data = [];
            if(!empty($nota_penjualan_id)){
                $modNotaDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id'=>$nota_penjualan_id])->all();
            }
            $data['html'] = '';
            if(count($modNotaDetail)>0){
                foreach($modNotaDetail as $i => $notadetail){
					$modProduk = \app\models\MBrgProduk::findOne($notadetail->produk_id);
					$modDetail = new \app\models\TReturProdukDetail();
					$modDetail->attributes = $notadetail->attributes;
					$modDetail->qty_besar = $notadetail->qty_besar;
					$modDetail->satuan_besar = $notadetail->satuan_besar;
					$modDetail->qty_kecil = \app\components\DeltaFormatter::formatNumberForUserFloat($notadetail->qty_kecil);
					$modDetail->satuan_kecil = $notadetail->satuan_kecil;
					$modDetail->kubikasi = $notadetail->kubikasi;
					$modDetail->kubikasi_display = number_format($notadetail->kubikasi,4);
					$modDetail->harga_jual = \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual);
					$modDetail->harga_retur = \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual);
                    $data['html'] .= $this->renderPartial('_itemNota',['modDetail'=>$modDetail,'i'=>$i,'modProduk'=>$modProduk]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	function actionGetItemsById(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $edit = Yii::$app->request->post('edit');
			$model = \app\models\TReturProduk::findOne($id);
			$modDetail = [];
            $data = [];
            if(!empty($id)){
                $modDetail = \app\models\TReturProdukDetail::find()->where(['retur_produk_id'=>$id])->all();
            }
			$data['model'] = $model->attributes;
            $data['html'] = '';
            if(count($modDetail)>0){
                foreach($modDetail as $i => $detail){
					$modProduk = \app\models\MBrgProduk::findOne($detail->produk_id);
					if($detail->produk->produk_group == "Plywood" || $detail->produk->produk_group == "Lamineboard" || $detail->produk->produk_group == "Platform"){
						$subtotal = $detail->harga_retur * $detail->qty_kecil;
					}else{
						$subtotal = $detail->harga_retur * $detail->kubikasi;
					}
//					$detail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal );
					$detail->subtotal = \app\components\DeltaFormatter::formatNumberForUser(round($subtotal)); // perubahan per tanggal 19/05/2022
					if($edit=="true"){
						$detail->harga_retur = \app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga_retur);
						$data['html'] .= $this->renderPartial('_itemNota',['modDetail'=>$detail,'i'=>$i,'modProduk'=>$modProduk]);
					}else{
						$data['html'] .= $this->renderPartial('_itemAfterSave',['model'=>$model,'modDetail'=>$detail,'i'=>$i,'edit'=>$edit]);
					}
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionPrintNota(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TReturProduk::findOne($_GET['id']);
		$modDetail = \app\models\TReturProdukDetail::find()->where(['retur_produk_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'RETUR PENJUALAN');
		if($caraprint == 'PRINT'){
			return $this->render('printRetur',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printRetur',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printRetur',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TReturProduk::tableName();
				$param['pk']= $param['table'].".". \app\models\TReturProduk::primaryKey()[0];
				$param['column'] = [$param['table'].'.retur_produk_id',
									$param['table'].'.kode AS kode_retur',
									$param['table'].'.tanggal',
									'm_customer.cust_an_nama',
									't_nota_penjualan.kode',
									$param['table'].'.alasan_retur',
									$param['table'].'.status'];
				$param['join']= ['JOIN t_nota_penjualan ON t_nota_penjualan.nota_penjualan_id = '.$param['table'].'.nota_penjualan_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionInfoRetur($kode){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TReturProduk::findOne(["kode"=>$kode]);
			$modDetail = \app\models\TReturProdukDetail::find()->where(['retur_produk_id'=>$model->retur_produk_id])->all();
			$paramprint['judul'] = Yii::t('app', 'RETUR PENJUALAN');
			return $this->renderAjax('infoRetur',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint]);
		}
    }
}
