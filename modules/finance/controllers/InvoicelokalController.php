<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\TInvoiceLokal;
use app\models\TNotaPenjualan;
use app\models\TOpKo;
use app\models\TPoKo;

class InvoicelokalController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TInvoiceLokal();
        $modDetail = new \app\models\TInvoiceInvoiceDetail();
        $model->jenis_produk = 'Log';
        $model->kode = '';
        $model->kode1 = '000';
        $model->kode2 = 'CWM';
        $model->kode3 = 'LG';
        $model->kode4 = \app\components\DeltaFunctions::Romawi(date('m'));
        $model->kode5 = date("Y");
        $model->tanggal = date('d/m/Y');
        $model->include_ppn = true;
		$model->penerbit = \app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
        $model->ceklis_pph = true;
        $model->total_potongan = 0;
		
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
                $model->total_ppn = isset($_POST['TInvoiceInvoiceDetail']['ppn'])?$_POST['TInvoiceInvoiceDetail']['ppn']:0;
                $model->total_pph = isset($_POST['TInvoiceInvoiceDetail']['pph'])?$_POST['TInvoiceInvoiceDetail']['pph']:0;
                $model->total_potongan = isset($_POST['TInvoiceLokal']['total_potongan'])?$_POST['TInvoiceLokal']['total_potongan']:0;
                $model->total_bayar = isset($_POST['total_bayar'])?$_POST['total_bayar']:0;
                $model->op_ko_id = isset($_POST['TInvoiceLokal']['op_ko_id'])?$_POST['TInvoiceLokal']['op_ko_id']:null;
                $nota_penjualan= isset($_POST['TInvoiceLokal']['nota_penjualan']) ? $_POST['TInvoiceLokal']['nota_penjualan'] : [];
                $model->nota_penjualan = \yii\helpers\Json::encode($nota_penjualan);
                $model->label_potongan = isset($_POST['TInvoiceLokal']['label_potongan'])?$_POST['TInvoiceLokal']['label_potongan']:null;
                // karna cust_id = po_ko_id - cust_id, jadi harus dipisah dulu
                // $cust_id = $_POST['TInvoiceLokal']['cust_id']; 
                // $part_cust = explode('-', $cust_id);
                // $model->cust_id = $part_cust[1];

                if($model->validate()){
                    if($model->save()){
						$success_1 = true;
						
						if(isset($_GET['edit'])){ // jika proses edit
                            $success_2 = (\app\models\TInvoiceInvoiceDetail::deleteAll("invoice_lokal_id = ".$model->invoice_lokal_id))?true:false;
                            
                            $modPo = TPoKo::findAll(['invoice_lokal_id'=>$model->invoice_lokal_id]);
                            foreach($modPo as $m => $po){
                                $modelPo = TPoKo::findOne($po['po_ko_id']);
                                $modelPo->invoice_lokal_id = null;
                                $modelPo->save();
                                // print_r($modelPo->save()); exit;
                            }
						}
						
                        $deskripsi_nota = [];
                        $deskripsi_invoice = [];
                        $modDetail = new \app\models\TInvoiceInvoiceDetail();
						foreach($_POST['TInvoiceInvoiceDetail'] as $i => $detail){
							$modDetail->attributes = $detail;
							$modDetail->invoice_lokal_id = $model->invoice_lokal_id;
                            $arrNota = ['produk_id'     => isset($_POST['TInvoiceInvoiceDetail'][$i]['produk_id'])?$_POST['TInvoiceInvoiceDetail'][$i]['produk_id']:'',
                                        'qty_kecil'     => isset($_POST['TInvoiceInvoiceDetail'][$i]['qty_kecil'])?$_POST['TInvoiceInvoiceDetail'][$i]['qty_kecil']:'',
                                        'kubikasi'      => isset($_POST['TInvoiceInvoiceDetail'][$i]['kubikasi'])?$_POST['TInvoiceInvoiceDetail'][$i]['kubikasi']:'',
                                        'harga_invoice' => isset($_POST['TInvoiceInvoiceDetail'][$i]['harga_invoice'])?$_POST['TInvoiceInvoiceDetail'][$i]['harga_invoice']:'',
                                        'harga_nota'    => isset($_POST['TInvoiceInvoiceDetail'][$i]['harga_nota'])?$_POST['TInvoiceInvoiceDetail'][$i]['harga_nota']:'',
                                        'subtotal'      => isset($_POST['TInvoiceInvoiceDetail'][$i]['subtotal'])?$_POST['TInvoiceInvoiceDetail'][$i]['subtotal']:''
                                        ];
                            if (!empty($arrNota['produk_id'])) {
                                $deskripsi_nota[] = $arrNota;
                            }

                            $arrInvoice = [ 'uraian'        => isset($_POST['TInvoiceInvoiceDetail'][$i]['uraian'])?$_POST['TInvoiceInvoiceDetail'][$i]['uraian']:'',
                                            'kubikasi_inv'  => isset($_POST['TInvoiceInvoiceDetail'][$i]['kubikasi_inv'])?$_POST['TInvoiceInvoiceDetail'][$i]['kubikasi_inv']:'',
                                            'harga_inv'     => isset($_POST['TInvoiceInvoiceDetail'][$i]['harga_inv'])?$_POST['TInvoiceInvoiceDetail'][$i]['harga_inv']:'',
                                            'total_inv'     => isset($_POST['TInvoiceInvoiceDetail'][$i]['total_inv'])?$_POST['TInvoiceInvoiceDetail'][$i]['total_inv']:''
                                          ];
                            if (!empty($arrInvoice['uraian'])) {
                                $deskripsi_invoice[] = $arrInvoice;
                            }

                            $modDetail->deskripsi_nota = \yii\helpers\Json::encode($deskripsi_nota);
                            $modDetail->deskripsi_invoice = \yii\helpers\Json::encode($deskripsi_invoice);
                            $modDetail->ppn = isset($_POST['TInvoiceInvoiceDetail']['ppn'])?$_POST['TInvoiceInvoiceDetail']['ppn']:0;
                            $modDetail->pph = isset($_POST['TInvoiceInvoiceDetail']['pph'])?$_POST['TInvoiceInvoiceDetail']['pph']:0;
                            $modDetail->potongan = $model->total_potongan;
                            
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

                        // save invoice_lokal_id ke t_po_ko
                        // $po_ko_id = $_POST['TInvoiceLokal']['po_ko_id'];
                        // $modPo = TPoKo::findOne($po_ko_id);
                        // $modPo->invoice_lokal_id = $model->invoice_lokal_id;
                        // if($modPo->validate()){
                        //     $modPo->save();
                        // }
                        // save invoice_lokal_id di t_po_ko
                        if($_POST['TInvoiceLokal']['po_ko_id'] == ''){
                            if(count($nota_penjualan) > 0){
                                foreach($nota_penjualan as $n => $nota){
                                    $modNota = TNotaPenjualan::findOne($nota);
                                    $modOp = TOpKo::findOne($modNota->op_ko_id);
                                    $modPo = TPoKo::findOne($modOp->po_ko_id);
                                    $modPo->invoice_lokal_id = $model->invoice_lokal_id;
                                    $modPo->save();
                                }
                            }
                        }
                        // print_r($nota_penjualan);
                        // exit;
                    }
                }
                
				// echo "<pre>1";
				// print_r($success_1);
				// echo "<pre>2";
				// print_r($success_2);
                // print_r($_POST['TInvoiceInvoiceDetail']['total_potongan']);
				// exit;
                
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
		return $this->render('index',['model'=>$model, 'modDetail'=>$modDetail]);
	}
    
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $op_ko_id = \Yii::$app->request->post('op_ko_id');
            $jenis_produk = Yii::$app->request->post('jenis_produk');
            $modOp = \app\models\TOpKo::findOne($op_ko_id);
            $model = new \app\models\TInvoiceLokal();
            $modDetail = new \app\models\TInvoiceInvoiceDetail();
            $data['item'] = $this->renderPartial('_itemInvoice',['model'=>$model, 'modDetail'=>$modDetail,'modOp'=>$modOp, 'jenis_produk'=>$jenis_produk]);
            return $this->asJson($data);
        }
    }
    
    public function actionSetCustomer(){
		if(\Yii::$app->request->isAjax){
            $jenis_produk = \Yii::$app->request->post('jenis_produk');
            $id = \Yii::$app->request->post('id');
			$cust = \Yii::$app->request->post('cust_id'); // po_ko_id - cust_id (log)
            if($jenis_produk == 'Log'){
                $cust_id = $cust;
                $po_ko_id = '';
                // kode before
                // $part_cust = explode('-', $cust);
                // $cust_id = $part_cust[1];
                // $po_ko_id = $part_cust[0];
                // eo kode before
            } else {
                $cust_id = $cust;
                $po_ko_id = '';
            }
            
			$data = [];

			if(!empty($cust_id)){
                $model = \app\models\MCustomer::findOne($cust_id);
				if(!empty($model)){
					$data = $model->attributes;
				}
			}
            if(!empty($po_ko_id)){
                $data['po_id'] = $po_ko_id;
            }
            if($id !== '0'){
                $modInv = TInvoiceLokal::findOne($id);
                $data['cust_no_npwp'] = $modInv->cust_no_npwp;
            }
			return $this->asJson($data);
		}
	}
    
    public function actionSetOpKo(){
		if(\Yii::$app->request->isAjax){
			$op_ko_id = \Yii::$app->request->post('op_ko_id');
            $jenis_produk = \Yii::$app->request->post('jenis_produk');
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
                        $model = new \app\models\TInvoiceLokal();
                        $modDetail = new \app\models\TInvoiceInvoiceDetail();
                        $modDetail->harga_nota = $notadetail->harga_jual;
                        $modDetail->produk_id = $notadetail->produk_id;
                        $modDetail->qty_kecil = $notadetail->qty_kecil;
                        $modDetail->kubikasi = $notadetail->kubikasi;
                        $data['detail'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$i,'notadetail'=>$notadetail, 'jenis_produk'=>$jenis_produk, 'nota_penjualan_id'=>$modNota->nota_penjualan_id]);
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
            // $jenis_produk = Yii::$app->request->post('jenis_produk');
            $data = [];
			$data['html'] = '';
            $data['html2'] = '';
            if(!empty($invoice_lokal_id)){
                $model = \app\models\TInvoiceLokal::findOne($invoice_lokal_id);
                $data['cust_id'] = $model->cust_id;
                $nota_id = json_decode($model->nota_penjualan);
                $data['nota_penjualan'] = $nota_id;
                $modDetails = \app\models\TInvoiceInvoiceDetail::find()->where(['invoice_lokal_id'=>$invoice_lokal_id])->all();
                    if(count($modDetails)>0){
                        foreach($modDetails as $i => $detail){
                            $modDetail = new \app\models\TInvoiceInvoiceDetail();
                            $deskripsi_nota = json_decode($detail['deskripsi_nota'], true);
                            foreach($deskripsi_nota as $a => $dn){
                                if($model->jenis_produk == "Log"){
                                    // $data['html'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$a, 'jenis_produk'=>$model->jenis_produk, 'nota_penjualan_id'=>$nota_id,'notadetail'=>$dn]);
                                } else {
                                    $modNota = \app\models\TNotaPenjualan::findOne(['op_ko_id'=>$model->op_ko_id]);
                                    $data['html'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$a, 'jenis_produk'=>$model->jenis_produk, 'nota_penjualan_id'=>$modNota->nota_penjualan_id,'notadetail'=>$dn]);
                                }
                            }
                            $deskripsi_invoice = json_decode($detail['deskripsi_invoice'], true);
                            foreach($deskripsi_invoice as $b => $di){
                                $modDetail->uraian = $di['uraian'];
                                $modDetail->kubikasi_inv = $di['kubikasi_inv'];
                                $modDetail->harga_inv = $di['harga_inv'];
                                $modDetail->total_inv = $di['total_inv'];
                                $data['html2'] .= $this->renderPartial('_itemInvoice',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$b, 'jenis_produk'=>$model->jenis_produk]);
                            }
                        }
                    }
                
            }
            return $this->asJson($data);
        }
    }
    /**function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $invoice_lokal_id = Yii::$app->request->post('invoice_lokal_id');
			$edit = Yii::$app->request->post('edit');
            // $jenis_produk = Yii::$app->request->post('jenis_produk');
            $data = [];
			$data['html'] = '';
            $data['html2'] = '';
            if(!empty($invoice_lokal_id)){
                $model = \app\models\TInvoiceLokal::findOne($invoice_lokal_id);
                $data['cust_id'] = $model->cust_id;
                $nota_id = json_decode($model->nota_penjualan);
                $data['nota_penjualan'] = $nota_id;
                $modDetails = \app\models\TInvoiceInvoiceDetail::find()->where(['invoice_lokal_id'=>$invoice_lokal_id])->all();
                    if(count($modDetails)>0){
                        foreach($modDetails as $i => $detail){
                            $modDetail = new \app\models\TInvoiceInvoiceDetail();
                            $deskripsi_nota = json_decode($detail['deskripsi_nota'], true);
                            foreach($deskripsi_nota as $a => $dn){
                                if($model->jenis_produk !== "Log"){
                                //     $data['html'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$a, 'jenis_produk'=>$model->jenis_produk, 'nota_penjualan_id'=>$nota_id,'notadetail'=>$dn]);
                                // } else {
                                    $modNota = \app\models\TNotaPenjualan::findOne(['op_ko_id'=>$model->op_ko_id]);
                                    $data['html'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$a, 'jenis_produk'=>$model->jenis_produk, 'nota_penjualan_id'=>$modNota->nota_penjualan_id,'notadetail'=>$dn]);
                                }
                            }
                            if($model->jenis_produk == "Log"){
                                $data['html'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$a, 'jenis_produk'=>$model->jenis_produk, 'nota_penjualan_id'=>$nota_id,'notadetail'=>$dn]);
                            }
                            $deskripsi_invoice = json_decode($detail['deskripsi_invoice'], true);
                            foreach($deskripsi_invoice as $b => $di){
                                $modDetail->uraian = $di['uraian'];
                                $modDetail->kubikasi_inv = $di['kubikasi_inv'];
                                $modDetail->harga_inv = $di['harga_inv'];
                                $modDetail->total_inv = $di['total_inv'];
                                $data['html2'] .= $this->renderPartial('_itemInvoice',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$b, 'jenis_produk'=>$model->jenis_produk]);
                            }
                        }
                    }
                
            }
            return $this->asJson($data);
        }
    }*/
    
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
    
    public function actionPrintInvoice1(){
		$this->layout = '@views/layouts/metronic/print';
		if(!empty($_GET['id'])){
			$model = \app\models\TInvoiceLokal::findOne($_GET['id']);
			$modDetails = \app\models\TInvoiceInvoiceDetail::find()->where(["invoice_lokal_id"=>$_GET['id']])->all();
            $modOp = \app\models\TOpKo::findOne($model->op_ko_id);
			$caraprint = Yii::$app->request->get('caraprint');
			$paramprint['judul'] = Yii::t('app', 'INVOICE');
			$paramprint['judul2'] = $model->kode;
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

    public function actionSetDropdownCustomer(){
        $jenis_produk = Yii::$app->request->post('jenis_produk');
        $edit = Yii::$app->request->post('edit');
        $id = Yii::$app->request->post('id');
        $data['html'] = '';
        $html = '<option value=""></option>';

        switch ($jenis_produk){
            case "Log" :
                if(!empty($id)){
                    $sql = "SELECT t_po_ko.po_ko_id, t_nota_penjualan.cust_id FROM t_nota_penjualan 
                        LEFT JOIN t_op_ko ON t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id
                        LEFT JOIN t_po_ko ON t_po_ko.po_ko_id = t_op_ko.po_ko_id
                        WHERE t_nota_penjualan.jenis_produk = 'Log' AND status_po = false 
                        AND (t_po_ko.invoice_lokal_id IS NULL OR t_po_ko.invoice_lokal_id = $id)
                        GROUP BY 1, 2";
                } else {
                    // $sql = "SELECT t_po_ko.po_ko_id, t_nota_penjualan.cust_id FROM t_nota_penjualan 
                    //     LEFT JOIN t_op_ko ON t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id
                    //     LEFT JOIN t_po_ko ON t_po_ko.po_ko_id = t_op_ko.po_ko_id
                    //     WHERE t_nota_penjualan.jenis_produk = 'Log' AND status_po = false 
                    //     AND t_po_ko.invoice_lokal_id IS NULL
                    //     GROUP BY 1, 2";
                    $sql = "SELECT t_nota_penjualan.cust_id FROM t_nota_penjualan 
                            LEFT JOIN t_op_ko ON t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id
                            LEFT JOIN t_po_ko ON t_po_ko.po_ko_id = t_op_ko.po_ko_id
                            WHERE t_nota_penjualan.jenis_produk = 'Log' AND status_po = false
                            AND NOT EXISTS (
                                SELECT 1
                                FROM t_invoice_lokal inv,
                                    jsonb_array_elements_text(inv.nota_penjualan::jsonb) AS nota
                                WHERE nota::int = t_nota_penjualan.nota_penjualan_id
                            )
                            GROUP BY t_nota_penjualan.cust_id";
                }
                break;
            case "JasaGesek" :
                // $sql = "SELECT cust_id FROM t_nota_penjualan WHERE jenis_produk = 'JasaGesek' GROUP BY 1";
                $sql = "SELECT
                            m_customer.cust_id,
                            m_customer.cust_an_nama 
                        FROM
                            t_nota_penjualan
                            JOIN m_customer ON m_customer.cust_id = t_nota_penjualan.cust_id 
                        WHERE
                            jenis_produk IN ( 'JasaKD', 'JasaGesek', 'JasaMoulding' ) 
                        GROUP BY 1,2";
                        // $model = \app\models\MCustomer::getOptionListInvoiceLokal();
                        // foreach($model as $i => $mod){
                        //     $html .= \yii\bootstrap\Html::tag('option',$mod);
                        // }
                break;
            default :
                $sql = '';
        }

        if($sql !== ''){
            $model = Yii::$app->db->createCommand($sql)->queryAll();
            if(count($model) > 0){
                foreach($model as $i => $mod){
                    $asd = \app\models\MCustomer::findOne($mod['cust_id']);
                    if ($jenis_produk == 'Log'){
                        // $po = TPoKo::findOne($mod['po_ko_id']);
                        // $html .= \yii\bootstrap\Html::tag('option',$po->kode . ' - ' .$asd->cust_an_nama,['value'=>$po->po_ko_id . '-' . $asd->cust_id]);
                        $html .= \yii\bootstrap\Html::tag('option',$asd->cust_an_nama,['value'=>$asd->cust_id]);
                    } else {
                        $html .= \yii\bootstrap\Html::tag('option',$asd->cust_an_nama,['value'=>$asd->cust_id]);
                    }
                }
            }
        }
        
        if(!empty($id) && empty($edit)){
            $model = \app\models\TInvoiceLokal::findOne($id);
            $modPo = TPoKo::findOne(['invoice_lokal_id'=>$id]);
            // $data['cust_id'] = $modPo->po_ko_id . '-' . $model->cust_id;
            $data['cust_id'] = $model->cust_id;
            $data['po_ko_id'] = ''; //$modPo->po_ko_id
        } 
        $data['html'] = $html;
        return $this->asJson($data);
    }

    public function actionSetDDNotaOp(){
        if(\Yii::$app->request->isAjax){
			$cust_id = Yii::$app->request->post('cust_id');
            $jenis_produk = Yii::$app->request->post('jenis_produk');
            $id = Yii::$app->request->post('id');
            $po_ko_id = Yii::$app->request->post('po_ko_id');
            $data['html'] = '';
			$html = '<option></option>';
            
            if(!empty($cust_id) && !empty($jenis_produk)){
                if($jenis_produk == "Log" ){
                    $drop_nota = []; 
                    $query = "SELECT t_nota_penjualan.* FROM t_nota_penjualan 
                                  JOIN t_op_ko ON t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id
                                  JOIN t_po_ko ON t_po_ko.po_ko_id = t_op_ko.po_ko_id
                                  WHERE t_po_ko.close_po != 'true' and t_op_ko.cust_id = {$cust_id}
                                ";
                    if(!empty($id)){
                        $query .= " AND (
                                    NOT EXISTS (
                                        SELECT 1
                                        FROM t_invoice_lokal inv,
                                                jsonb_array_elements_text(inv.nota_penjualan::jsonb) AS nota
                                        WHERE nota::int = t_nota_penjualan.nota_penjualan_id )
                                    OR 
                                    EXISTS (
                                        SELECT 1
                                        FROM t_invoice_lokal inv,
                                            jsonb_array_elements_text(inv.nota_penjualan::jsonb) AS nota
                                        WHERE nota::int = t_nota_penjualan.nota_penjualan_id
                                            AND inv.invoice_lokal_id = {$id}
                                    )
                                )";
                        $model = Yii::$app->db->createCommand($query)->queryAll();
                    } else {
                        $query .= " AND NOT EXISTS (
                                        SELECT 1
                                        FROM t_invoice_lokal inv, jsonb_array_elements_text(inv.nota_penjualan::jsonb) AS nota
                                        WHERE nota::int = t_nota_penjualan.nota_penjualan_id
                                    )";
                        $model = Yii::$app->db->createCommand($query)->queryAll();
                        $nota_id = $model[0]['nota_penjualan_id']; // pake id nota yg pertama aja
                        $modNota = TNotaPenjualan::findOne($nota_id); 
                        $data['op_ko'] = TOpKo::findOne($modNota->op_ko_id);
                    }
                    // if(!empty($id)){
                    //     $model = Yii::$app->db->createCommand("
                    //                     SELECT * FROM t_nota_penjualan WHERE cust_id = {$cust_id} AND jenis_produk = 'Log' AND 
                    //                     (nota_penjualan_id IN (SELECT (jsonb_array_elements_text(nota_penjualan::jsonb))::integer 
                    //                     FROM t_invoice_lokal WHERE op_ko_id is null AND cust_id = {$cust_id}) OR 
                    //                     nota_penjualan_id NOT IN (SELECT (jsonb_array_elements_text(nota_penjualan::jsonb))::integer 
                    //                     FROM t_invoice_lokal WHERE op_ko_id is null AND cust_id = {$cust_id}))
                    //                 ")->queryAll();
                    // } else {
                    //     $model = Yii::$app->db->createCommand("
                    //                     SELECT t_nota_penjualan.* FROM t_nota_penjualan 
                    //                     JOIN t_op_ko ON t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id
                    //                     JOIN t_po_ko ON t_po_ko.po_ko_id = t_op_ko.po_ko_id
                    //                     WHERE t_po_ko.po_ko_id = {$po_ko_id}
                    //                 ")->queryAll();
                    //     $nota_id = $model[0]['nota_penjualan_id']; // pake id nota yg pertama aja
                    //     $modNota = TNotaPenjualan::findOne($nota_id); 
                    //     $data['op_ko'] = TOpKo::findOne($modNota->op_ko_id);
                    // }
                    if(count($model) > 0){
                        foreach($model as $i => $tag){
                            $drop_nota[$tag['nota_penjualan_id']] = $tag['kode']." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($tag['tanggal']);
                        }
                    }
                    foreach($drop_nota as $i => $val){
                        $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                    }
                    $data['nota_penjualan'] = array_keys($drop_nota); // agar semua nota langsung ditarik
                } else {
                    $drop_op = []; 
                    // $model = \app\models\TOpKo::find()->select("op_ko_id, kode, tanggal")->where("cust_id = ".$cust_id." AND jenis_produk ILIKE '%{$jenis_produk}%'")->groupBy("op_ko_id, kode, tanggal")->orderBy("op_ko_id DESC")->all();
                    // $model = \app\models\TOpKo::find()->select("op_ko_id, kode, tanggal")->where("cust_id = ".$cust_id." AND jenis_produk ILIKE '%Jasa%'")->groupBy("op_ko_id, kode, tanggal")->orderBy("op_ko_id DESC")->all();
                    if(!empty($id)){
                        $model = Yii::$app->db->createCommand("
                                            select * from t_op_ko where cust_id = {$cust_id} AND jenis_produk ILIKE '%{$jenis_produk}%'
                                            and op_ko_id not in (select op_ko_id from t_invoice_lokal where op_ko_id is not null AND cust_id = {$cust_id})
                                            or op_ko_id in (select op_ko_id from t_invoice_lokal where invoice_lokal_id = {$id})
                                            order by op_ko_id DESC
                                        ")->queryAll();
                    } else {
                        $model = Yii::$app->db->createCommand("
                                            select * from t_op_ko where cust_id = {$cust_id} AND jenis_produk ILIKE '%{$jenis_produk}%'
                                            and op_ko_id not in (select op_ko_id from t_invoice_lokal where op_ko_id is not null AND cust_id = {$cust_id})
                                            order by op_ko_id DESC
                                        ")->queryAll();
                    }
                    if(!empty($model)){
                        foreach($model as $i => $tag){
                            $drop_op[$tag['op_ko_id']] = $tag['kode']." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($tag['tanggal']);
                        }
                    }
                    foreach($drop_op as $i => $val){
                        $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                    }
                }
            }
            if(!empty($id)){
                $modInv = \app\models\TInvoiceLokal::findOne($id);
                $nota_id = json_decode($modInv->nota_penjualan);
                $data['nota_penjualan'] = $nota_id;
                $data['op_ko_id'] = $modInv->op_ko_id;
            }
			$data['html'] = $html;
			return $this->asJson($data);
		}
    }

    public function actionSetNota(){
		if(\Yii::$app->request->isAjax){
			$nota_penjualan_id = \Yii::$app->request->post('nota_penjualan_id');
            $jenis_produk = \Yii::$app->request->post('jenis_produk');
			$data = []; 
			if(!empty($nota_penjualan_id)){
                $nota_id = implode(', ', $nota_penjualan_id);
                foreach($nota_penjualan_id as $a => $nota){
                    $modelNota = \app\models\TNotaPenjualan::findOne($nota);
                    if(!empty($modelNota)){
                        $data = $modelNota->attributes;
                    }
                    $data['detail'] = "";
                    if($jenis_produk == "Log"){
                        $modNotaDetail = Yii::$app->db->createCommand(
                            "SELECT t_nota_penjualan_detail.produk_id,t_nota_penjualan.kode, t_nota_penjualan.tanggal, t_spm_ko.tanggal_kirim as tgl_spm, t_spm_ko.kendaraan_nopol, t_spm_ko.kendaraan_supir,
                            log_kelompok, kayu_nama, range_awal, range_akhir, count(*) as qty_kecil, SUM(kubikasi) as kubikasi, harga_jual
                            FROM t_nota_penjualan 
                            JOIN t_nota_penjualan_detail on t_nota_penjualan_detail.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id
                            LEFT JOIN t_spm_ko ON t_spm_ko.spm_ko_id = t_nota_penjualan.spm_ko_id
                            JOIN m_brg_log ON m_brg_log.log_id = t_nota_penjualan_detail.produk_id
                            JOIN m_kayu ON m_kayu.kayu_id = m_brg_log.kayu_id
                            WHERE t_nota_penjualan.nota_penjualan_id in ($nota_id) and jenis_produk = 'Log' 
                            GROUP BY t_nota_penjualan.nota_penjualan_id, t_nota_penjualan.kode, t_nota_penjualan.tanggal, t_spm_ko.tanggal_kirim, t_spm_ko.kendaraan_nopol, 
                                    t_spm_ko.kendaraan_supir, m_brg_log.log_kelompok, kayu_nama,range_awal,range_akhir,harga_jual,t_nota_penjualan_detail.produk_id
                            ORDER BY t_nota_penjualan.nota_penjualan_id"
                        )->queryAll();
                    } else {
                        $modNotaDetail = \app\models\TNotaPenjualanDetail::find()->where("nota_penjualan_id = ".$modelNota->nota_penjualan_id)->all();
                    }
                    
                    if(count($modNotaDetail)>0){
                        foreach($modNotaDetail as $i => $notadetail){
                            $model = new \app\models\TInvoiceLokal();
                            $modDetail = new \app\models\TInvoiceInvoiceDetail();
                            $modDetail->harga_nota = $notadetail['harga_jual'];
                            $modDetail->produk_id = $notadetail['produk_id'];
                            $modDetail->qty_kecil = $notadetail['qty_kecil'];
                            $modDetail->kubikasi = $notadetail['kubikasi'];
                            if($jenis_produk !== 'Log'){
                                $data['detail'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$i,'notadetail'=>$notadetail, 'nota_penjualan_id'=>$nota_penjualan_id, 'jenis_produk'=>$jenis_produk]);
                            }
                        }
                    }
                }
                if($jenis_produk == 'Log'){
                    $data['detail'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$i,'notadetail'=>$modNotaDetail, 'nota_penjualan_id'=>$nota_penjualan_id, 'jenis_produk'=>$jenis_produk]);
                }
			}
			return $this->asJson($data);
		}
	}

    /**public function actionSetNota(){
		if(\Yii::$app->request->isAjax){
			$nota_penjualan_id = \Yii::$app->request->post('nota_penjualan_id');
            $jenis_produk = \Yii::$app->request->post('jenis_produk');
			$data = []; 
			if(!empty($nota_penjualan_id)){
                $nota_id = implode(', ', $nota_penjualan_id);
                foreach($nota_penjualan_id as $a => $nota){
                    $modelNota = \app\models\TNotaPenjualan::findOne($nota);
                    if(!empty($modelNota)){
                        $data = $modelNota->attributes;
                    }
                    $data['detail'] = "";
                    if($jenis_produk == "Log"){
                        $modNotaDetail = Yii::$app->db->createCommand(
                            "   SELECT t_nota_penjualan_detail.produk_id, 
                                SUM(qty_kecil) as qty_kecil, SUM(kubikasi) as kubikasi, t_nota_penjualan_detail.harga_jual
                                FROM t_nota_penjualan 
                                JOIN t_nota_penjualan_detail on t_nota_penjualan_detail.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id
                                WHERE t_nota_penjualan.nota_penjualan_id in ({$nota_id}) and jenis_produk = 'Log'
                                GROUP BY t_nota_penjualan_detail.produk_id, t_nota_penjualan_detail.harga_jual
                            "
                        )->queryAll();
                    } else {
                        $modNotaDetail = \app\models\TNotaPenjualanDetail::find()->where("nota_penjualan_id = ".$modelNota->nota_penjualan_id)->all();
                    }
                    
                    if(count($modNotaDetail)>0){
                        foreach($modNotaDetail as $i => $notadetail){
                            $model = new \app\models\TInvoiceLokal();
                            $modDetail = new \app\models\TInvoiceInvoiceDetail();
                            $modDetail->harga_nota = $notadetail['harga_jual'];
                            $modDetail->produk_id = $notadetail['produk_id'];
                            $modDetail->qty_kecil = $notadetail['qty_kecil'];
                            $modDetail->kubikasi = $notadetail['kubikasi'];
                            $data['detail'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$modDetail,'i'=>$i,'notadetail'=>$notadetail, 'nota_penjualan_id'=>$nota_penjualan_id, 'jenis_produk'=>$jenis_produk]);
                        }
                    }
                }
			}
			return $this->asJson($data);
		}
	}*/

    public function actionPrintInvoice2(){
		$this->layout = '@views/layouts/metronic/print';
		if(!empty($_GET['id'])){
			$model = \app\models\TInvoiceLokal::findOne($_GET['id']);
			$modDetails = \app\models\TInvoiceInvoiceDetail::find()->where(["invoice_lokal_id"=>$_GET['id']])->all();
            $modOp = \app\models\TOpKo::findOne($model->op_ko_id);
			$caraprint = Yii::$app->request->get('caraprint');
			$paramprint['judul'] = Yii::t('app', 'INVOICE 2');
            $viewPrint = "printInvoice2";
			
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
