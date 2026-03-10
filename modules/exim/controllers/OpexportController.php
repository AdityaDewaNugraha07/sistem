<?php

namespace app\modules\exim\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class OpexportController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){ 
        $model = new \app\models\TOpExport();
		$model->kode = "Auto Generate";
        $model->nomor_kontrak = 'XXX/CWM-SC/'.\app\components\DeltaFunctions::Romawi(date('m')).'/'.date('Y').'/-R1';
        $model->tanggal = date('d/m/Y');
        $model->jenis_produk = "Plywood";
        $model->port_of_loading = "SEMARANG PORT, INDONESIA";
        $model->static_product_code = ($model->jenis_produk=="Moulding")?"INDONESIA WOOD MOULDING PRODUCT":"INDONESIAN PLYWOOD PRODUCT";
        $model->harvesting_area = "KABUPATEN MUARA PAHU, EAST KALIMANTAN";
		$model->origin = "INDONESIA";
		$model->svlk_no = "VLK00011";
        $modBuyer = new \app\models\MCustomer();
        $modAttachment = new \app\models\TAttachment();
		$allowedit = true;
        if(isset($_GET['op_export_id'])){
            $model = \app\models\TOpExport::findOne($_GET['op_export_id']);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->departure_estimated_date = \app\components\DeltaFormatter::formatDateTimeForUser2($model->departure_estimated_date);
			$model->arrival_estimated_date = \app\components\DeltaFormatter::formatDateTimeForUser2($model->arrival_estimated_date);
            $modBuyer = \app\models\MCustomer::findOne($model->cust_id);
			if(!empty($model->notify_party)){
				$modBuyer->cust_an_nama2 = $model->notifyParty->cust_an_nama;
				$modBuyer->cust_an_alamat2 = $model->notifyParty->cust_an_alamat;
			}
			if(isset($_GET['edit'])){
				$modPackinglist = \app\models\TPackinglist::findOne(['op_export_id'=>$_GET['op_export_id']]);
				if(!empty($modPackinglist)){
					$modSpm = \app\models\TSpmKo::findOne(['packinglist_id'=>$modPackinglist->packinglist_id]);
					if(!empty($modSpm)){
						$allowedit = false;
					}
				}
			}
        }
        if( Yii::$app->request->post('TOpExport')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_op_export
                $success_2 = true; // t_invoice
                $success_3 = true; // t_packinglist
                $success_4 = true; // t_attachment
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::orderPenjualanExport($model->jenis_produk);
				}
				$model->shipper = strtoupper(\app\models\CCompanyProfile::findOne(\app\components\Params::DEFAULT_COMPANY_PROFILE)->Name());
				$model->shipment_to = strtoupper(\app\models\MCustomer::getShipmentTo( (!empty($model->notify_party)?$model->notify_party:$model->cust_id) ));
				$detail_order = ""; $goods_description = "";
				foreach($_POST['TOpExport'] as $i => $postop){ 
					if(is_array($postop)){ 
						$goods_description[] = $postop['detail_description'];
						$detail_order[] = $postop;
					}
				}
				$model->goods_description = implode("; ", $goods_description);
				$model->detail_order = \yii\helpers\Json::encode($detail_order);
				$model->detail_qty = \yii\helpers\Json::encode($_POST['detail_qty']);
                if($model->validate()){
                    if($model->save()){
						$success_1 = true;
						$modInvoice = \app\models\TInvoice::findOne(['op_export_id'=>$model->op_export_id]);
						if(!empty($modInvoice)){
							$modInvoice->payment_method = $model->payment_method;
							$modInvoice->term_of_price = $model->term_of_price;
							if($modInvoice->validate()){
								if($modInvoice->save()){
									$success_2 = true;
								}else{
									$success_2 = false;
								}
							}else{
								$success_2 = false;
							}
						}
						$modPackinglist = \app\models\TPackinglist::findOne(['op_export_id'=>$model->op_export_id]);
						if(!empty($modPackinglist)){
							$modPackinglist->cust_id = $model->cust_id;
							if($modPackinglist->validate()){
								if($modPackinglist->save()){
									$success_3 = true;
								}else{
									$success_3 = false;
								}
							}else{
								$success_3 = false;
							}
						}
                        
                        // save attchment
                        $dir_path = Yii::$app->basePath.'/web/uploads/exm/op';
						if(isset($_FILES['TAttachment'])){
							$files = [];
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file1');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file2');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file3');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file4');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file5');
							foreach($files as $i => $file){
								if(!empty($file)){
									$modAttachment = new \app\models\TAttachment();
									$modAttachment->reff_no = $model->kode;
									$modAttachment->file_type = $file->type;
									$modAttachment->file_ext = $file->extension;
									$modAttachment->file_size = $file->size;
									$modAttachment->dir_path = $dir_path;
									$modAttachment->seq = ($i+1);
									$randomstring_attch = Yii::$app->getSecurity()->generateRandomString(4);
									if(!is_dir($dir_path)){ mkdir($dir_path); }
									$file_path = date('Ymd_His').'-attch-'.$randomstring_attch.'.'  . $file->extension;
									$file->saveAs($dir_path.'/'.$file_path);
									$modAttachment->file_name = $file_path;
									if($modAttachment->validate()){
										if($modAttachment->save()){
											$success_4 &= true;
										}else{
											$success_4 = false;
										}
									}else{
										$success_4 = false;
										$errmsg = $modAttachment->errors;
									}
								}
							}
						}
                        // end
                    }
                }
//				echo "<pre>";
//				print_r($success_1);
//				echo "<pre>";
//				print_r($success_2);
//				echo "<pre>";
//				print_r($success_3);
//				echo "<pre>";
//				print_r($success_4);
//				exit;
                if ($success_1 && $success_2 && $success_3 && $success_4) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'op_export_id'=>$model->op_export_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modBuyer'=>$modBuyer,'allowedit'=>$allowedit,'modAttachment'=>$modAttachment]);
	}
	
	public function actionFindBuyer(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT * FROM m_customer 
					WHERE cust_an_nama ilike '%{$term}%' AND active IS TRUE AND cust_tipe_penjualan = 'export'
					ORDER BY cust_an_nama";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'cust_id', 'cust_an_nama');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['cust_id'], 'text'=>$val['cust_an_nama']." ".(!empty($val['cust_pr_nama'])?"- ".$val['cust_pr_nama']:"")];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	public function actionMasterBuyer(){
		if(\Yii::$app->request->isAjax){
			$url = \yii\helpers\Url::toRoute("/exim/opexport/masterBuyer");
			if(\Yii::$app->request->get('dt')=='table-customer'){
				$param['table'] = \app\models\MCustomer::tableName();
				$param['pk']= $param['table'].".".\app\models\MCustomer::primaryKey()[0];
				$param['column'] = [$param['table'].'.cust_id','cust_kode','cust_an_nama','cust_pr_nama','cust_max_plafond',
									'COALESCE(SUM(t_nota_penjualan.total_bayar)-COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id),0),0) AS piutang'];
				$param['join'] = ['LEFT JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = '.$param['table'].'.cust_id'];
				$param['group'] = "GROUP BY ".$param['table'].".cust_id, cust_kode, cust_an_nama, cust_pr_nama, cust_max_plafond";
				$param['where'] = "active IS TRUE AND cust_tipe_penjualan = 'export'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('@app/modules/marketing/views/customer/masterOnTable',['url'=>$url]);
		}
	}
	public function actionSetBuyer(){
		if(\Yii::$app->request->isAjax){
			$cust_id = \Yii::$app->request->post('cust_id');
			$data = [];
			if(!empty($cust_id)){
				$model = \app\models\MCustomer::findOne($cust_id);
				if(!empty($model)){
					$data = $model->attributes;
				}
			}
			
			return $this->asJson($data);
		}
	}
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TOpExport();
			$model->detail_volume = 0;
			$model->detail_price = 0;
			$model->detail_subtotal = 0;
            $data['item'] = $this->renderPartial('_addItem',['model'=>$model]);
            return $this->asJson($data);
        }
    }
	
	public function actionAddQty(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TOpExport();
			$data['html'] = "";
			$data['html'] = "<tr>
								<td></td>
								<td>
									<b>Qty : </b> 
									".\yii\bootstrap\Html::textInput("detail_qty[ii][detail_vehicle_qty]",'1',['style'=>'width:25px; text-align:right; height:24px;','value'=>'1'])." X 
									".\yii\bootstrap\Html::dropDownList("detail_qty[ii][detail_vehicle_type]",'', ['container'=>'Container','truck'=>'Truck'],['options'=>['container'=>['selected'=>true]]])."
									".\yii\bootstrap\Html::dropDownList("detail_qty[ii][detail_vehicle_size]",'', ['20'=>'20 Feet','40'=>'40 Feet'],['options'=>['20'=>['selected'=>true]]])."
									<a class='btn btn-xs btn-default' onclick='removeQty(this);'><i class='fa fa-minus'></i></a>
								</td>
								<td></td>
							 </tr>";
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TOpExport::tableName();
				$param['pk']= $param['table'].".".\app\models\TOpExport::primaryKey()[0];
				$param['column'] = [$param['table'].'.op_export_id',
									$param['table'].'.kode',
									$param['table'].'.nomor_kontrak',
									$param['table'].'.tanggal',
									'applicant.cust_an_nama',
									'applicant.cust_an_alamat',
									'notify.cust_an_nama AS notify_nama',
									'notify.cust_an_alamat AS notify_alamat',
									$param['table'].'.jenis_produk',
									$param['table'].'.payment_method',
									$param['table'].'.cancel_transaksi_id'];
				$param['join']= ['LEFT JOIN m_customer AS applicant ON applicant.cust_id = '.$param['table'].'.cust_id
								  LEFT JOIN m_customer AS notify ON notify.cust_id = '.$param['table'].'.notify_party'];
				// $param['where']= "cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $op_export_id = Yii::$app->request->post('op_export_id');
            $editable = Yii::$app->request->post('editable');
            $tipe = Yii::$app->request->post('tipe');
            $data = []; $data['html'] = ''; $data['qty'] = ''; $data['attch'] = [];
            if(!empty($op_export_id)){
                $model = \app\models\TOpExport::findOne($op_export_id);
                $modDetailOrders = \yii\helpers\Json::decode($model->detail_order);
                $modDetailQty = \yii\helpers\Json::decode($model->detail_qty);
                $modAttch = \app\models\TAttachment::find()->where("reff_no = '{$model->kode}'")->all();
				if(count($modDetailOrders)>0){
					foreach($modDetailOrders as $i => $detailorder){
						$model->detail_description = $detailorder['detail_description'];
						$model->detail_size = $detailorder['detail_size'];
						$model->detail_volume = \app\components\DeltaFormatter::formatNumberForUserFloat($detailorder['detail_volume']);
						$model->detail_price = \app\components\DeltaFormatter::formatNumberForUserFloat($detailorder['detail_price']);
						$model->detail_subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat($detailorder['detail_subtotal']);
						$model->detail_lot_code = isset($detailorder['detail_lot_code'])?$detailorder['detail_lot_code']:"";
						$model->shipment_time = isset($detailorder['shipment_time'])?$detailorder['shipment_time']:"";
						$data['html'] .= $this->renderPartial('_addItem',['model'=>$model]);
					}
				}
				if(count($modDetailQty)>0){
					foreach($modDetailQty as $ii => $qty){
						if($tipe=="input"){
							$data['qty'] .= '<tr>
												<td>
													'.(($ii!=0)?'':'<span class="pull-left">
																	<a class="btn btn-xs btn-default" onclick="addItem();"><i class="fa fa-plus"></i> '. Yii::t('app', 'Add Descriptions').'</a>
																  </span>' ).'

												</td>
												<td>
													<b>Qty : </b> 
													'. yii\bootstrap\Html::textInput("detail_qty[".$ii."][detail_vehicle_qty]",$qty['detail_vehicle_qty'],['style'=>'width:25px; text-align:right; height:24px;']).' X 
													'. yii\bootstrap\Html::dropDownList("detail_qty[".$ii."][detail_vehicle_type]",$qty['detail_vehicle_type'], ['container'=>'Container','truck'=>'Truck']).' 
													'. yii\bootstrap\Html::dropDownList("detail_qty[".$ii."][detail_vehicle_size]",$qty['detail_vehicle_size'], ['20'=>'20 Feet','40'=>'40 Feet']).'
														'.(($ii!=0)?'<a class="btn btn-xs btn-default" onclick="removeQty(this);"><i class="fa fa-minus"></i></a>':'<a class="btn btn-xs btn-default" onclick="addQty();"><i class="fa fa-plus"></i></a>' ).'

												</td>
												<td></td>
											</tr>';
						}else{
							$data['qty'] .= '<tr>
												<td></td>
												<td>
													<b>Qty : </b> 
													'. yii\bootstrap\Html::textInput("detail_qty[".$ii."][detail_vehicle_qty]",$qty['detail_vehicle_qty'],['style'=>'width:25px; text-align:right; height:24px;']).' X 
													'. yii\bootstrap\Html::dropDownList("detail_qty[".$ii."][detail_vehicle_type]",$qty['detail_vehicle_type'], ['container'=>'Container','truck'=>'Truck']).' 
													'. yii\bootstrap\Html::dropDownList("detail_qty[".$ii."][detail_vehicle_size]",$qty['detail_vehicle_size'], ['20'=>'20 Feet','40'=>'40 Feet']).'
												</td>
												<td></td>
											</tr>';
						}
						
					}
				}
                if(count($modAttch)>0){
                    foreach($modAttch as $ii => $attch){
                        $data['attch'][] = $attch->attributes;
                    }
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionCancelOpExport($id)
    {
        if(\Yii::$app->request->isAjax){
			$modOpExport    = \app\models\TOpExport::findOne($id);
			$modCancel      = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1              = false; // t_cancel_transaksi
                    $success_2              = false; // t_op_export
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by   = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at   = date('d/m/Y H:i:s');
					$modCancel->reff_no     = $modOpExport->kode;
					$modCancel->status      = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        $success_1          = $modCancel->save() ? true : false;
                        $modOpExport->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
                        $success_2          = $modOpExport->validate() && $modOpExport->save() ? true : false;
                    }else{
                        $data['message_validate'] = \yii\widgets\ActiveForm::validate($modCancel); 
					}
					
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'OP Export Berhasil di Batalkan');
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
			
			return $this->renderAjax('_cancelOpExport',['modOpExport'=>$modOpExport,'modCancel'=>$modCancel]);
		}
    }
}
