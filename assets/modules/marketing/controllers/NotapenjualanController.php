<?php

namespace app\modules\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class NotapenjualanController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TNotaPenjualan();
        $model->kode = 'Auto Generate';
//        $model->tanggal = date('d/m/Y');
		$model->total_harga = 0; $model->total_bayar = 0; $model->total_potongan = 0; $model->total_ppn = 0;
		$modSpm = new \app\models\TSpmKo();
		$modSp = new \app\models\TSuratPengantar();
		$modTempo = new \app\models\TTempobayarKo();
        if(isset($_GET['nota_penjualan_id'])){
            $model = \app\models\TNotaPenjualan::findOne($_GET['nota_penjualan_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$modSpm = \app\models\TSpmKo::findOne($model->spm_ko_id);
			$model->kode_spm = $modSpm->kode;
			$model->cust_an_nama = $model->cust->cust_an_nama;
			$model->cust_pr_nama = $model->cust->cust_pr_nama;
			$model->cust_an_alamat = $model->cust->cust_an_alamat;
			$model->cust_is_pkp = $model->cust->cust_is_pkp;
			$model->total_harga = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_harga);
			$model->total_ppn = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_ppn);
			$model->total_potongan = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_potongan);
			$model->total_bayar = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_bayar);
			$modSp = \app\models\TSuratPengantar::findOne(['nota_penjualan_id'=>$model->nota_penjualan_id]);
			$modTempoBayar = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);
			if(!empty($modTempoBayar)){
				$modTempo->attributes = $modTempoBayar->attributes;
				$modTempo->maks_plafon = \app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->maks_plafon);
				$modTempo->sisa_piutang = \app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_piutang);
				$modTempo->sisa_plafon = \app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_plafon);
			}
        }
		
        if( Yii::$app->request->post('TNotaPenjualan')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_nota_penjualan
                $success_2 = true; // t_nota_penjualan_detail
                $success_3 = true; // t_surat_pengantar
                $success_4 = true; // t_surat_pengantar_detail
                $success_5 = false; // t_approval
                $model->load(\Yii::$app->request->post());
				$modSp = New \app\models\TSuratPengantar();
				$modSp->attributes = $model->attributes;
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeNotaPenjualan($_POST['TNotaPenjualan']['jenis_produk']);
					$modSp->kode = \app\components\DeltaGenerator::kodeSuratPengantar($_POST['TNotaPenjualan']['jenis_produk']);
					$model->status = "UNPAID";
				}
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						$modSp->nota_penjualan_id = $model->nota_penjualan_id;
						if($modSp->validate()){
							if($modSp->save()){
								$success_3 = true;
							}
						}
						if((isset($_GET['edit'])) && (isset($_GET['nota_penjualan_id']))){
							$modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id'=>$_GET['nota_penjualan_id']])->all();
							if(count($modDetail)>0){
								\app\models\TNotaPenjualanDetail::deleteAll(['nota_penjualan_id'=>$_GET['nota_penjualan_id']]);
							}
							$modDetailSp = \app\models\TSuratPengantarDetail::find()->where(['surat_pengantar_id'=>$modSp->surat_pengantar_id])->all();
							if(count($modDetailSp)>0){
								\app\models\TSuratPengantarDetail::deleteAll(['surat_pengantar_id'=>$modSp->surat_pengantar_id]);
							}
						}
						foreach($_POST['TNotaPenjualanDetail'] as $i => $detail){
							$modDetail = new \app\models\TNotaPenjualanDetail();
							$modDetail->attributes = $detail;
							$modDetail->nota_penjualan_id = $model->nota_penjualan_id;
							$modDetail->satuan_kecil = !empty($modDetail->satuan_kecil)?$modDetail->satuan_kecil:"Pcs";
							if($modDetail->validate()){
								if($modDetail->save()){
									$success_2 &= true;
									$modDetailSp = new \app\models\TSuratPengantarDetail();
									$modDetailSp->attributes = $modDetail->attributes;
									$modDetailSp->surat_pengantar_id = $modSp->surat_pengantar_id;
									if($modDetailSp->validate()){
										if($modDetailSp->save()){
											$success_4 &= true;
										}else{
											$success_4 = false;
										}
									}else{
										$success_4 = false;
									}
								}else{
									$success_2 = false;
								}
							}else{
								$success_2 = false;
							}
						}
						if($model->total_potongan > 0){
							$modApproval = new \app\models\TApproval();
							$modApproval->assigned_to = \app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
							$modApproval->reff_no = $model->kode;
							$modApproval->tanggal_berkas = $model->tanggal;
							$modApproval->level = 1;
							$modApproval->status = "Not Confirmed";
							$success_5 = $modApproval->createApproval();
							if($model->total_potongan > 100000 ){ // Jika lebih besar dari Rp 100.000
								$modApproval = new \app\models\TApproval();
								$modApproval->assigned_to = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
								$modApproval->reff_no = $model->kode;
								$modApproval->tanggal_berkas = $model->tanggal;
								$modApproval->level = 2;
								$modApproval->status = "Not Confirmed";
								$success_5 = $modApproval->createApproval();
							}
						}else{
							$success_5 = true;
						}
                    }
                }
				
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>3";
//				print_r($success_3);
//				echo "<pre>4";
//				print_r($success_4);
//				echo "<pre>5";
//				print_r($success_5);
//				exit;
				
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'nota_penjualan_id'=>$model->nota_penjualan_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modSpm'=>$modSpm,'modSp'=>$modSp,'modTempo'=>$modTempo]);
	}
	
	public function actionSetSPM(){
		if(\Yii::$app->request->isAjax){
			$spm_ko_id = \Yii::$app->request->post('spm_ko_id');
			$data = []; $data['tempo'] = [];
			if(!empty($spm_ko_id)){
				$model = \app\models\TSpmKo::findOne($spm_ko_id);
				$modCust = \app\models\MCustomer::findOne($model->cust_id);
				$modOp = \app\models\TOpKo::findOne($model->op_ko_id);
				$modTempo = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);
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
					$data['dokumen_penjualan'] = ($modOp->jenis_produk=="Limbah")?"": \app\components\DeltaGenerator::dokumenPenjualan($modOp->jenis_produk);
				}
				if(!empty($modTempo)){
					$data['tempo'] = $modTempo->attributes;
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionFindSPM(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT t_spm_ko.* FROM t_spm_ko 
					LEFT JOIN t_nota_penjualan ON t_nota_penjualan.spm_ko_id = t_spm_ko.spm_ko_id
					WHERE t_spm_ko.kode ilike '%{$term}%' AND t_spm_ko.cancel_transaksi_id IS NULL AND t_spm_ko.status = '".\app\models\TSpmKo::REALISASI."' AND nota_penjualan_id IS NULL
					ORDER BY t_spm_ko.created_at";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'spm_ko_id', 'kode');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['spm_ko_id'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	
	public function actionOpenSPM(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-spm'){
				$param['table']= \app\models\TSpmKo::tableName();
				$param['pk']= $param['table'].".".\app\models\TSpmKo::primaryKey()[0];
				$param['column'] = [$param['table'].'.spm_ko_id',
									$param['table'].'.kode',
									't_op_ko.jenis_produk',
									$param['table'].'.tanggal',
									'm_customer.cust_an_nama',
									$param['table'].'.tanggal_kirim',
									$param['table'].'.kendaraan_nopol',
									$param['table'].'.kendaraan_supir',
									$param['table'].'.alamat_bongkar'
									];
				$param['join']= ['JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  JOIN t_op_ko ON t_op_ko.op_ko_id = t_spm_ko.op_ko_id
								  LEFT JOIN t_nota_penjualan ON t_nota_penjualan.spm_ko_id = t_spm_ko.spm_ko_id'];
				$param['where']=$param['table'].".cancel_transaksi_id IS NULL AND ".$param['table'].".status ='".\app\models\TSpmKo::REALISASI."' AND nota_penjualan_id IS NULL AND t_spm_ko.jenis_penjualan = 'lokal'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('spm');
        }
	}
	
	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $spm_ko_id = Yii::$app->request->post('spm_ko_id');
            $jns_produk = Yii::$app->request->post('jns_produk');
			$model = new \app\models\TNotaPenjualan();
			$modSpm = \app\models\TSpmKo::findOne($spm_ko_id);
            $modOpKo = \app\models\TOpKo::findOne($modSpm->op_ko_id);
			$modCust = \app\models\MCustomer::findOne($modSpm->cust_id);
			$modDetail = [];
            $data = [];
            if(!empty($spm_ko_id)){
                $modSPMDetail = \app\models\TSpmKoDetail::find()->where(['spm_ko_id'=>$spm_ko_id])->all();
            }
            
            $data['html'] = '';
            
            if( $modOpKo->jenis_produk == "JasaGesek" ){
                $modOpKoDetails = \app\models\TOpKoDetail::find()->where(['op_ko_id'=>$modOpKo->op_ko_id])->all();
                if(count($modOpKoDetails)>0){
                    foreach($modOpKoDetails as $i => $opdetali){
                        $modDetail = new \app\models\TNotaPenjualanDetail();
                        $modDetail->attributes = $opdetali->attributes;
                        $modDetail->qty_besar = $opdetali->qty_besar;
                        $modDetail->satuan_besar = $opdetali->satuan_besar;
                        $modDetail->qty_kecil = $opdetali->qty_kecil;
                        $modDetail->satuan_kecil = $opdetali->satuan_kecil;
                        $modDetail->kubikasi = number_format($opdetali->kubikasi,4);
                        $subtotal = $modDetail->harga_jual * number_format($modDetail->kubikasi,4);
                        $modDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal );
                        $modDetail->ppn = 0;
                        $modDetail->harga_jual = \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual);
                        $data['html'] .= $this->renderPartial('_itemSPM',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i,'modOpKo'=>$modOpKo]);
                    }
                }
            }else{
                if(count($modSPMDetail)>0){
                    foreach($modSPMDetail as $i => $spmdetail){
                        $modDetail = new \app\models\TNotaPenjualanDetail();
                        $modDetail->attributes = $spmdetail->attributes;
                        $modDetail->qty_besar = $spmdetail->qty_besar_realisasi;
                        $modDetail->satuan_besar = $spmdetail->satuan_besar_realisasi;
                        $modDetail->qty_kecil = $spmdetail->qty_kecil_realisasi;
                        $modDetail->satuan_kecil = $spmdetail->satuan_kecil_realisasi;
                        $modDetail->kubikasi = number_format($spmdetail->kubikasi_realisasi,4);
                        if($modOpKo->jenis_produk == "Plywood" || $modOpKo->jenis_produk == "Lamineboard" || $modOpKo->jenis_produk == "Platform" || $modOpKo->jenis_produk == "Limbah"){
                            $subtotal = $modDetail->harga_jual * $modDetail->qty_kecil;
                        }else{
                            $subtotal = $modDetail->harga_jual * number_format($modDetail->kubikasi,4);
                        }
                        $modDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal );
    //					if($modCust->cust_is_pkp==TRUE){
    //						$modDetail->ppn = \app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal * 0.1 );
    //					}else{
    //						$modDetail->ppn = 0;
    //					}
                        $modDetail->ppn = 0;
                        $modDetail->harga_jual = \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual);

                        $data['html'] .= $this->renderPartial('_itemSPM',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i,'modOpKo'=>$modOpKo]);

                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	function actionGetItemsById(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $edit = Yii::$app->request->post('edit');
			$model = \app\models\TNotaPenjualan::findOne($id);
			$modDetail = [];
            $data = [];
            if(!empty($id)){
                $modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id'=>$id])->all();
            }
			$data['model'] = $model->attributes;
            $data['html'] = '';
            if(count($modDetail)>0){
                foreach($modDetail as $i => $detail){
					if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform" || $model->jenis_produk == "Limbah"){
						$subtotal = $detail->harga_jual * $detail->qty_kecil;
					}else{
						$subtotal = $detail->harga_jual * number_format($detail->kubikasi,4);
					}
					$detail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal );
					if($model->cust_is_pkp==TRUE){
						$detail->ppn = \app\components\DeltaFormatter::formatNumberForUserFloat( $detail->ppn );
					}else{
						$detail->ppn = 0;
					}
					if($edit){
						
					}
                    $data['html'] .= $this->renderPartial('_itemAfterSave',['model'=>$model,'modDetail'=>$detail,'i'=>$i,'edit'=>$edit]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TNotaPenjualan::tableName();
				$param['pk']= $param['table'].".". \app\models\TNotaPenjualan::primaryKey()[0];
				$param['column'] = [$param['table'].'.nota_penjualan_id',
									$param['table'].'.kode AS kode_nota',
									$param['table'].'.jenis_produk',
									$param['table'].'.tanggal',
									'm_customer.cust_an_nama',
									't_spm_ko.kode',
									$param['table'].'.kendaraan_nopol',
									$param['table'].'.kendaraan_supir',
									$param['table'].'.alamat_bongkar',
									$param['table'].'.total_bayar',
									$param['table'].'.cancel_transaksi_id',
									$param['table'].'.status'
									];
				$param['join']= ['JOIN t_spm_ko ON t_spm_ko.spm_ko_id = '.$param['table'].'.spm_ko_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionCheckApproval(){
		if(\Yii::$app->request->isAjax){
			$nota_penjualan_id = Yii::$app->request->post('nota_penjualan_id');
			$data['status'] = true;
			if(!empty($nota_penjualan_id)){
				$modNota = \app\models\TNotaPenjualan::findOne($nota_penjualan_id);
				$approval = \app\models\TApproval::find()->where(['reff_no'=>$modNota->kode])->all();
				if(count($approval)>0){
					foreach($approval as $i => $appr){
						if($appr->status == "APPROVED"){
							$data['status'] &= true;
						}else{
							$data['status'] = false;
						}
					}
				}
			}
            return $this->asJson($data);
        }
	}
	
	public function actionPrintNota(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TNotaPenjualan::findOne($_GET['id']);
		$modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'NOTA PENJUALAN');
		if($caraprint == 'PRINT'){
			return $this->render('printNota',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printNota',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printNota',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}
	public function actionPrintSP(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TSuratPengantar::findOne($_GET['id']);
		$modDetail = \app\models\TSuratPengantarDetail::find()->where(['surat_pengantar_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'SURAT PENGANTAR');
		if($caraprint == 'PRINT'){
			return $this->render('printSP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printSP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printSP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}
	
	public function actionInfoNota($kode){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TNotaPenjualan::findOne(["kode"=>$kode]);
			$modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id'=>$model->nota_penjualan_id])->all();
			$paramprint['judul'] = Yii::t('app', 'NOTA PENJUALAN');
			return $this->renderAjax('infoNota',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint]);
		}
    }
	
}
