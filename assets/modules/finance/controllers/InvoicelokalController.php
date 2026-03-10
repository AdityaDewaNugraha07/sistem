<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class InvoicelokalController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TInvoiceLokal();
        $model->kode = '';
        $model->kode1 = '000';
        $model->kode2 = 'CWM';
        $model->kode3 = 'JASA';
        $model->kode4 = \app\components\DeltaFunctions::Romawi(12);
        $model->kode5 = date("Y");
        $model->tanggal = date('d/m/Y');
        $model->include_ppn = true;
		$model->penerbit = \app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
		
        if(isset($_GET['invoice_lokal_id'])){
            $model = \app\models\TInvoiceLokal::findOne($_GET['invoice_lokal_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $kode = explode("/", str_replace("-", "/", $model->kode));
            $model->kode1 = $kode[0]; $model->kode2 = $kode[1]; $model->kode3 = $kode[2]; $model->kode4 = $kode[3]; $model->kode5 = $kode[4];
            $model->cust_an_alamat = $model->cust->cust_an_alamat;
        }
		
        if( Yii::$app->request->post('TInvoiceLokal')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_invoice_lokal
                $success_2 = true; // t_invoice_lokal_detail
                $model->load(\Yii::$app->request->post());
                $kode1 = isset($_POST['TInvoiceLokal']['kode1'])?$_POST['TInvoiceLokal']['kode1']:"";
                $kode2 = isset($_POST['TInvoiceLokal']['kode2'])?$_POST['TInvoiceLokal']['kode2']:"";
                $kode3 = isset($_POST['TInvoiceLokal']['kode3'])?$_POST['TInvoiceLokal']['kode3']:"";
                $kode4 = isset($_POST['TInvoiceLokal']['kode4'])?$_POST['TInvoiceLokal']['kode4']:"";
                $kode5 = isset($_POST['TInvoiceLokal']['kode5'])?$_POST['TInvoiceLokal']['kode5']:"";
                $model->kode = $kode1."/".$kode2."-".$kode3."/".$kode4."/".$kode5;
                $model->cust_no_npwp = str_replace(["-","_","."], "", $model->cust_no_npwp);
                $model->no_faktur_pajak = str_replace(["-","_","."], "", $model->no_faktur_pajak);
                $model->total_harga = isset($_POST['total_harga'])?$_POST['total_harga']:0;
                $model->total_ppn = isset($_POST['total_ppn'])?$_POST['total_ppn']:0;
                $model->total_pph = isset($_POST['total_pph'])?$_POST['total_pph']:0;
                $model->total_potongan = isset($_POST['total_potongan'])?$_POST['total_potongan']:0;
                $model->total_bayar = isset($_POST['total_bayar'])?$_POST['total_bayar']:0;
                if($model->validate()){
                    if($model->save()){
						$success_1 = true;
						
						if(isset($_GET['edit'])){ // jika proses edit
                            $success_2 = (\app\models\TInvoiceLokalDetail::deleteAll("invoice_lokal_id = ".$model->invoice_lokal_id))?true:false;
						}
						
						foreach($_POST['TInvoiceLokalDetail'] as $i => $detail){
							$modDetail = new \app\models\TInvoiceLokalDetail();
							$modDetail->attributes = $detail;
							$modDetail->invoice_lokal_id = $model->invoice_lokal_id;
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
                    return $this->redirect(['index','success'=>1,'invoice_lokal_id'=>$model->invoice_lokal_id]);
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
    
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $op_ko_id = \Yii::$app->request->post('op_ko_id');
            $modOp = \app\models\TOpKo::findOne($op_ko_id);
            $modDetail = new \app\models\TInvoiceLokalDetail();
            $jenis_produk = Yii::$app->request->post('jenis_produk');
            $data['item'] = $this->renderPartial('_item',['modDetail'=>$modDetail,'modOp'=>$modOp]);
            return $this->asJson($data);
        }
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
                
                $modOp = \app\models\TOpKo::find()->select("op_ko_id, kode, tanggal")->where("cust_id = ".$cust_id." AND jenis_produk ILIKE '%Jasa%'")->groupBy("op_ko_id, kode, tanggal")->orderBy("op_ko_id DESC")->all();
                $data['dropdown_op'] = '<option value=""></option>';
                if(count($modOp)>0){
                    foreach($modOp as $i => $op){
                        $data['dropdown_op'] .= \yii\bootstrap\Html::tag('option',$op->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($op->tanggal),['value'=>$op->op_ko_id]);
                    }
                }
			}
			return $this->asJson($data);
		}
	}
    
    public function actionSetOpKo(){
		if(\Yii::$app->request->isAjax){
			$op_ko_id = \Yii::$app->request->post('op_ko_id');
			$data = []; 
			if(!empty($op_ko_id)){
                $model = \app\models\TOpKo::findOne($op_ko_id);
				if(!empty($model)){
					$data = $model->attributes;
				}
                $data['detail'] = "";
                $modOp = \app\models\TOpKo::findOne($op_ko_id);
                $modNota = \app\models\TNotaPenjualan::findOne(['op_ko_id'=>$op_ko_id]);
                $modNotaDetail = \app\models\TNotaPenjualanDetail::find()->where("nota_penjualan_id = ".$modNota->nota_penjualan_id)->all();
                if(count($modNotaDetail)>0){
                    foreach($modNotaDetail as $i => $notadetail){
                        $modDetail = new \app\models\TInvoiceLokalDetail();
                        $modDetail->spm_ko_id = $modNota->spm_ko_id;
                        $modDetail->nota_penjualan_id = $notadetail->nota_penjualan_id;
                        $modDetail->nota_penjualan_detail_id = $notadetail->nota_penjualan_detail_id;
                        $modDetail->harga_nota = $notadetail->harga_jual;
                        $modDetail->satuan_kecil = "Pcs";
                        $modDetail->produk_id = $notadetail->produk_id;
                        $modDetail->qty_besar = 0;
                        $modDetail->satuan_besar = "Palet";
                        $modDetail->qty_kecil = $notadetail->qty_kecil;
                        $modDetail->kubikasi = $notadetail->kubikasi;
                        $data['detail'] .= $this->renderPartial('_item',['modDetail'=>$modDetail,'i'=>$i,'notadetail'=>$notadetail]);
                    }
                }
			}
            
			return $this->asJson($data);
		}
	}
    function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $invoice_lokal_id = Yii::$app->request->post('invoice_lokal_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
			$data['html'] = '';
            if(!empty($invoice_lokal_id)){
                $model = \app\models\TInvoiceLokal::findOne($invoice_lokal_id);
                $modDetails = \app\models\TInvoiceLokalDetail::find()->where(['invoice_lokal_id'=>$invoice_lokal_id])->all();
                if(count($modDetails)>0){
                    foreach($modDetails as $i => $detail){
                        $notadetail = \app\models\TNotaPenjualanDetail::findOne($detail->nota_penjualan_detail_id);
                        if(!empty($edit)){
                            $data['html'] .= $this->renderPartial('_item',['modDetail'=>$detail,'i'=>$i,'notadetail'=>$notadetail]);
                        }else{
                            $data['html'] .= $this->renderPartial('_item',['modDetail'=>$detail,'i'=>$i,'notadetail'=>$notadetail]);
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
				$param['table']= \app\models\TInvoiceLokal::tableName();
				$param['pk']= $param['table'].".".\app\models\TInvoiceLokal::primaryKey()[0];
				$param['column'] = [$param['table'].'.invoice_lokal_id',
                                    $param['table'].'.kode',
									$param['table'].'.jenis_produk',
									$param['table'].'.tanggal',
									'm_customer.cust_an_nama',
									'm_customer.cust_an_alamat',
									$param['table'].'.cust_no_npwp',
									$param['table'].'.no_faktur_pajak',
									$param['table'].'.cara_bayar',
									$param['table'].'.mata_uang',
									$param['table'].'.include_ppn',
									$param['table'].'.total_bayar',
									];
				$param['join']= ['JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id'];
				$param['where'] = "t_invoice_lokal.cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
    
    public function actionPrintInvoice(){
		$this->layout = '@views/layouts/metronic/print';
		if(!empty($_GET['id'])){
			$model = \app\models\TInvoiceLokal::findOne($_GET['id']);
			$modDetails = \app\models\TInvoiceLokalDetail::find()->where(["invoice_lokal_id"=>$_GET['id']])->all();
            $modOp = \app\models\TOpKo::findOne($model->op_ko_id);
			$caraprint = Yii::$app->request->get('caraprint');
			$paramprint['judul'] = Yii::t('app', 'INVOICE');
			$paramprint['judul2'] = $model->jenis_produk;
            $viewPrint = "printInvoice";
			
			if($caraprint == 'PRINT'){
				return $this->render($viewPrint,['model'=>$model,'modDetails'=>$modDetails,'modOp'=>$modOp,'paramprint'=>$paramprint]);
			}else if($caraprint == 'PDF'){
				$pdf = Yii::$app->pdf;
				$pdf->options = ['title' => $paramprint['judul']];
				$pdf->filename = $paramprint['judul'].'.pdf';
				$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
				$pdf->content = $this->render($viewPrint,['model'=>$model,'modDetails'=>$modDetails,'modOp'=>$modOp,'paramprint'=>$paramprint]);
				return $pdf->render();
			}else if($caraprint == 'EXCEL'){
				return $this->render($viewPrint,['model'=>$model,'modDetails'=>$modDetails,'modOp'=>$modOp,'paramprint'=>$paramprint]);
			}else if($caraprint == 'MODAL'){
                $this->layout = '@views/layouts/metronic/main';
				return $this->renderAjax('infoInvoice',['model'=>$model,'modDetails'=>$modDetails,'modOp'=>$modOp,'paramprint'=>$paramprint,'viewPrint'=>$viewPrint]);
			}
		}
	}
	
}
