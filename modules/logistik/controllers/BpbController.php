<?php

namespace app\modules\logistik\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class BpbController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TBpb();
        $modDetail = new \app\models\TBpbDetail();
        $model->bpb_kode = 'Auto Generate';
        $model->bpb_tgl_keluar = date('d/m/Y');
        $model->bpb_dikeluarkan = Yii::$app->user->identity->pegawai_id;
        
		
        if(isset($_GET['bpb_id'])){
            $model = \app\models\TBpb::findOne($_GET['bpb_id']);
            $model->bpb_tgl_keluar = \app\components\DeltaFormatter::formatDateTimeForUser2($model->bpb_tgl_keluar);
            $modDetail = \app\models\TBpbDetail::find()->where(['bpb_id'=>$model->bpb_id])->all();
        }
        
        if( Yii::$app->request->post('TBpb')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_bpb
                $success_2 = true; // t_bpb_detail
                $success_3 = false; // t_spb
                $success_4 = true; // t_spb_detail
                $success_5 = true; // h_persediaan_bhp
                $model->load(\Yii::$app->request->post());
                $model->bpb_kode = \app\components\DeltaGenerator::kodeBpb();
                $model->bpb_status = 'BELUM DITERIMA';
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        
                        if( (isset($_POST['TBpbDetail'])) && (count($_POST['TBpbDetail'])>0) ){
                            $qty_spb_terpenuhi = true;
                            $item_spb_terpenuhi = true;
							$modSpb = \app\models\TSpb::findOne($model->spb_id);
							$modSpbDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$model->spb_id])->andWhere('spbd_jml_terpenuhi < spbd_jml')->all();
							if( count($modSpbDetail) <= count($_POST['TBpbDetail']) ){
								$item_spb_terpenuhi = true;
							}else{
								$item_spb_terpenuhi = false;
							}
                            foreach($_POST['TBpbDetail'] as $i => $detail){
                                $modDetail = new \app\models\TBpbDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->bpb_id = $model->bpb_id;
                                if($modDetail->validate()){
                                    if($modDetail->save()){
                                        $success_2 &= $success_2;
                                        
                                        // Start Proses Update Stock
                                        $modDetail->qty_in = 0;
                                        $modDetail->qty_out = $modDetail->bpbd_jml;
                                        $success_5 &= \app\models\HPersediaanBhp::updateStokPersediaan($modDetail,$model->bpb_kode,$modDetail->bpbd_id,$model->bpb_tgl_keluar);
                                        // End Proses Update Stock
                                        
                                        if(!empty($model->spb_id)){
                                            $modSpbDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$model->spb_id,'bhp_id'=>$modDetail->bhp_id])->andWhere("spbd_ket ILIKE '%".$modDetail->bpbd_ket."%'")->one();
											if(empty($modSpbDetail)){
												$modSpbDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$model->spb_id,'bhp_id'=>$modDetail->bhp_id])->one();	
											}
											$totalqtykeluar = $modDetail->bpbd_jml + $modSpbDetail->spbd_jml_terpenuhi;
                                            $modSpbDetail->spbd_jml_terpenuhi = $modSpbDetail->spbd_jml_terpenuhi + $modDetail->bpbd_jml;
                                            if($modSpbDetail->spbd_jml <= ($totalqtykeluar)){
                                                $qty_spb_terpenuhi &= true;
                                            }else{
                                                $qty_spb_terpenuhi &= false;
                                            }
                                            if($modSpbDetail->save()){
                                                $success_4 &= $success_4;
                                            }else{
                                                $success_4 = FALSE;
                                            }
                                        }else{
                                            $success_4 = false;
                                        }
                                    }else{
                                        $success_2 &= $success_2;
                                    }
                                }else{
                                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                                }
                            }
                            if(!empty($model->spb_id)){
                                if($qty_spb_terpenuhi && $item_spb_terpenuhi){
                                    $modSpb->spb_status = 'TERPENUHI';
                                }else{
                                    $modSpb->spb_status = 'SEDANG DIPROSES';
                                }
                                if($modSpb->save()){
                                    $success_3 = TRUE;
                                }else{
                                    $success_3 = FALSE;
                                }
                            }else{
                                $success_3 = TRUE;
                            }
                        }else{
                            $success_2 = false;
                            Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                        }
                    }
                }
                
//                echo "<pre>1";
//                print_r($success_1);
//                echo "<pre>2";
//                print_r($success_2);
//                echo "<pre>3";
//                print_r($success_3);
//                echo "<pre>4";
//                print_r($success_4);
//                echo "<pre>5";
//                print_r($success_5);
//                exit;
                
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data BPB Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'bpb_id'=>$model->bpb_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail]);
	}
    
    public function actionSetDropdownSpb(){
		if(\Yii::$app->request->isAjax){
			$dept_id = Yii::$app->request->post('dept_id');
            $data['html'] = [];
            if(!empty($dept_id)){
                $mod = \app\models\TSpb::find()->where(['departement_id'=>$dept_id])
						->andWhere("t_spb.spb_status = 'BELUM DIPROSES' OR t_spb.spb_status = 'SEDANG DIPROSES'")
						->andWhere("t_spb.approve_status = '". \app\models\TApproval::STATUS_APPROVED."'")
						->orderBy('spb_tanggal DESC, spb_kode DESC')->all();
                if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'spb_id', 'spb_kode');
					$html = \yii\bootstrap\Html::tag('option');
					foreach($arraymap as $i => $val){
						$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,]);
					}
                    $data['html'] = $html;
                }
            }
			return $this->asJson($data);
		}
	}
    
    public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $spb_id = Yii::$app->request->post('spb_id');
            $data = [];
            $data['html'] = '';
            if(!empty($spb_id)){
                $modDetailSpb = \app\models\TSpbDetail::find()->where(['spb_id'=>$spb_id])->andWhere("(spbd_jml > spbd_jml_terpenuhi)")->all();
                if(count($modDetailSpb)>0){
                    foreach($modDetailSpb as $i => $detailspb){
                        $modDetail = new \app\models\TBpbDetail();
                        $modDetail->bhp_nama = $detailspb->bhp->bhp_nm;
                        $modDetail->qty_kebutuhan = $detailspb->spbd_jml;
						if(($detailspb->spbd_jml-$detailspb->spbd_jml_terpenuhi) > $detailspb->bhp->current_stock){
							$modDetail->bpbd_jml = $detailspb->bhp->current_stock;
						}else{
							$modDetail->bpbd_jml = $detailspb->spbd_jml-$detailspb->spbd_jml_terpenuhi;
						}
						$modDetail->bpbd_jml = 0;
                        $modDetail->current_stock = $detailspb->bhp->current_stock;
                        $modDetail->satuan = $detailspb->bhp->bhp_satuan;
                        $modDetail->bhp_id = $detailspb->bhp_id;
                        $modDetail->bpbd_ket = $detailspb->spbd_ket;
                        $data['html'] .= $this->renderPartial('_item',['modDetail'=>$modDetail,'detailspb'=>$detailspb]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionGetItemsByBpb(){
		if(\Yii::$app->request->isAjax){
            $bpb_id = Yii::$app->request->post('bpb_id');
            $data = [];
            $data['html'] = '';
            if(!empty($bpb_id)){
                $modBpb = \app\models\TBpb::findOne($bpb_id);
                $modDetailBpb = \app\models\TBpbDetail::find()->where(['bpb_id'=>$bpb_id])->all();
                if(count($modDetailBpb)>0){
                    foreach($modDetailBpb as $i => $detail){
                        $detail->bhp_nama = $detail->bhp->bhp_nm;
                        $detail->qty_kebutuhan = 0;
                        $detail->current_stock = $detail->bhp->current_stock;
                        $detail->satuan = $detail->bhp->bhp_satuan;
                        if(!empty($modBpb->spb_id)){
							$cek = \Yii::$app->db->createCommand("SELECT * FROM t_bpb_detail WHERE bpb_id = ".$modBpb->bpb_id." AND bhp_id = ".$detail->bhp_id)->queryAll();
							if(count($cek)>1){
								$ket = $detail->bpbd_ket;
							}else{
								$ket = null;
							}
                            $modDetailSpb = $this->getSpbItemDetail($modBpb->spb_id, $detail->bhp_id,$ket);
                            $detail->qty_kebutuhan = $modDetailSpb->spbd_jml;
							$detail->jml_terpenuhi = $modDetailSpb->spbd_jml_terpenuhi;
                        }
                        $data['html'] .= $this->renderPartial('_itemAfterSave',['detail'=>$detail,'i'=>$i,'modBpb'=>$modBpb]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function getSpbItemDetail($spb_id,$bhp_id,$ket) {
		if(!empty($ket)){
			$modSpb = \app\models\TSpbDetail::find()->where(['spb_id'=>$spb_id,'bhp_id'=>$bhp_id])->andWhere("spbd_ket ILIKE '%".$ket."%'")->one();
		}else{
			$modSpb = \app\models\TSpbDetail::find()->where(['spb_id'=>$spb_id,'bhp_id'=>$bhp_id])->one();
		}
		if(empty($modSpb)){
			$modSpb = \app\models\TSpbDetail::find()->where(['spb_id'=>$spb_id,'bhp_id'=>$bhp_id])->one();
		}
        if(count($modSpb)>0){
            return $modSpb;
        }else{
            return null;
        }
    }
    
    public function actionInfoSpb($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpb::findOne($id);
            $modDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$id])->all();
			return $this->renderAjax('infoSpb',['model'=>$model,'modDetail'=>$modDetail]);
		}
    }
    
    public function actionDaftarBpb(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-bpb'){
				$param['table']= \app\models\TBpb::tableName();
				$param['pk']= \app\models\TBpb::primaryKey()[0];
				$param['column'] = ['bpb_id','bpb_kode','spb_kode',['col_name'=>'bpb_tgl_keluar','formatter'=>'formatDateForUser2'],'departement_nama',['col_name'=>'bpb_tgl_diterima','formatter'=>'formatDateForUser2'],'bpb_status'];
				$param['join']= ['JOIN t_spb ON t_spb.spb_id = '.$param['table'].'.spb_id',
								 'JOIN m_departement ON m_departement.departement_id = '.$param['table'].'.departement_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarBpb');
        }
    }
    
	
    public function actionPrintBPB(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TBpb::findOne($_GET['id']);
		$modDetail = \app\models\TBpbDetail::find()->where(['bpb_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'BUKTI PENGELUARAN BARANG');
                if($caraprint == 'PRINT'){                        
			return $this->render('printBPB',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printBPB',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printBPB',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
                
                
        }
        
	public function actionAbortItem($id,$bpb_id){
		if(\Yii::$app->request->isAjax){
			$modBPBDetail = \app\models\TBpbDetail::find()->where(['bpbd_id'=>$id])->one();
			$modBPB = \app\models\TBpb::findOne($modBPBDetail->bpb_id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_bpb_detail 
                    $success_3 = false; // h_persediaan_bhp
                    $success_4 = false; // t_spb 
                    $success_5 = false; // t_spb_detail   
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $modBPB->bpb_kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
					$modCancel->reff_detail_id = $modBPBDetail->bpbd_id;
					$modCancel->bhp_id = $modBPBDetail->bhp_id;
					$modCancel->cancel_jml = $modBPBDetail->bpbd_jml;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
							$modBPBDetail->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
                            if($modBPBDetail->validate()){
								$success_2 = $modBPBDetail->save();
							}
							
							// Start Proses Update Stock
							$modBPBDetail->qty_out = 0;
							$modBPBDetail->qty_in = $modBPBDetail->bpbd_jml;
							$modBPBDetail->keterangan = "Pembatalan Item BPB";
							$success_3 = \app\models\HPersediaanBhp::updateStokPersediaan($modBPBDetail,$modBPB->bpb_kode,$modBPBDetail->bpbd_id,date('Y-m-d'));
							// End Proses Update Stock
							
							$modSpb = \app\models\TSpb::findOne($modBPB->spb_id);
							$modSpb->spb_status = 'SEDANG DIPROSES';
							if($modSpb->validate()){
								$success_4 = $modSpb->save();
							}
							
							$modSpbDetail = \app\models\TSpbDetail::findOne(['spb_id'=>$modBPB->spb_id,'bhp_id'=>$modBPBDetail->bhp_id]);
							$modSpbDetail->spbd_jml_terpenuhi = 0;
							if($modSpbDetail->validate()){
								$success_5 = $modSpbDetail->save();
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel); 
                    }
					
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					echo "<pre>";
//					print_r($success_3);
//					echo "<pre>";
//					print_r($success_4);
//					echo "<pre>";
//					print_r($success_5);
//					exit;
					
                    if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Berhasil di Batalkan');
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
			
			return $this->renderAjax('_abortItem',['modBPBDetail'=>$modBPBDetail,'modBPB'=>$modBPB,'modCancel'=>$modCancel]);
		}
	}
}
