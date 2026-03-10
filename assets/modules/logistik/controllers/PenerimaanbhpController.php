<?php

namespace app\modules\logistik\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PenerimaanbhpController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TTerimaBhp();
        $model->terimabhp_kode = 'Auto Generate';
        $model->pegawaipenerima = Yii::$app->user->identity->pegawai_id;
        $model->tglterima = date('d/m/Y');
		$modDetail = [];
		
		if(isset($_GET['terima_bhp_id'])){
            $model = \app\models\TTerimaBhp::findOne($_GET['terima_bhp_id']);
            $model->tglterima = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tglterima);
            $modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$model->terima_bhp_id])->all();
        }
		
		if( Yii::$app->request->post('TTerimaBhp')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $success_2 = true;
                $success_3 = true; // insert stock persediaan h_persediaan_bhp
                $success_4 = true; // update harga m_brg_bhp
                $success_5 = true; // jurnal
				$success_6 = false; // update reff table (t_spo / t_spp)
				$success_7 = true; // update map_spp_detail_reff
				$success_8 = true; // update t_spp
                $model->load(\Yii::$app->request->post());
                $model->terimabhp_kode = \app\components\DeltaGenerator::kodeTerimaBhp();
                $model->terimabhp_status = '-';
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						
						// update reff table (t_spo / t_spp)
						if(!empty($model->spo_id)){
							$modSpo = \app\models\TSpo::findOne($model->spo_id);
							$modSpo->terima_bhp_id = $model->terima_bhp_id;
							if($modSpo->validate()){
								$success_6 = $modSpo->save();
							}
							$reff_no = $modSpo->spo_kode;
						}
						if(!empty($model->spl_id)){
							$modSpl = \app\models\TSpl::findOne($model->spl_id);
							$modSpl->terima_bhp_id = $model->terima_bhp_id;
							if($modSpl->validate()){
								$success_6 = $modSpl->save();
							}
							$reff_no = $modSpl->spl_kode;
						}
						// end update reff table (t_spo / t_spp)
						
                        if( (isset($_POST['TTerimaBhpDetail'])) && (count($_POST['TTerimaBhpDetail'])>0) ){
                            foreach($_POST['TTerimaBhpDetail'] as $i => $detail){
                                $modDetail = new \app\models\TTerimaBhpDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->terima_bhp_id = $model->terima_bhp_id;
								if(empty($modDetail->suplier_id)){
									$modDetail->suplier_id = $model->suplier_id;
								}
                                if($modDetail->validate()){
                                    if($modDetail->save()){
                                        $success_2 &= true;
										
										// Start Proses Update Stock
                                        $modDetail->qty_in = $modDetail->terimabhpd_qty;
                                        $modDetail->qty_out = 0;
                                        $success_3 &= \app\models\HPersediaanBhp::updateStokPersediaan($modDetail,$model->terimabhp_kode,$modDetail->terima_bhpd_id,$model->tglterima);
                                        // End Proses Update Stock

										// Start Proses Update Harga BHP
										if(!empty($model->spo_id)){
											if($model->spo->spo_is_pkp){
												if($model->spo->spo_is_ppn){
													$harga_pokok = $modDetail->terimabhpd_harga;
													$nominal_ppn = (($modDetail->terimabhpd_harga * 1.1) - $modDetail->terimabhpd_harga);
													$harga = $harga_pokok + $nominal_ppn;
													$include_ppn = TRUE;
												}else{
													$harga_pokok = $modDetail->terimabhpd_harga;
													$nominal_ppn = $modDetail->terimabhpd_harga * 0.1;
													$harga = $modDetail->terimabhpd_harga;
													$include_ppn = FALSE;
												}
											}else{
												$harga_pokok = $modDetail->terimabhpd_harga;
												$nominal_ppn = 0;
												$harga = $harga_pokok;
												$include_ppn = FALSE;
											}
										}else{
											$harga_pokok = $modDetail->terimabhpd_harga;
											$nominal_ppn = 0;
											$harga = $harga_pokok;
											$include_ppn = FALSE;
										}
                                        $success_4 &= \app\models\MBrgBhp::updateHargaBhp($modDetail->bhp_id,$harga_pokok,$nominal_ppn,$harga,$include_ppn);
                                        // End Proses Update Harga BHP 

										// update map_spp_detail_reff
										if(!empty($model->spo_id)){ $reff_detail_id = $detail['spod_id']; }
										if(!empty($model->spl_id)){ $reff_detail_id = $detail['spld_id']; }
										$modMap = \app\models\MapSppDetailReff::find()->where("reff_no = '".$reff_no."' AND reff_detail_id = ".$reff_detail_id)->one();
										if(!empty($modMap)){
											if($modMap->updateAll(['terima_bhpd_id'=>$modDetail->terima_bhpd_id],"reff_no = '".$reff_no."' AND reff_detail_id = ".$reff_detail_id)){
												$success_7 &= true;
											}else{
												$success_7 &= false;
											}
										}
										// end update map_spp_detail_reff

                                    }else{
                                        $success_2 &= false;
                                    }
                                }else{
									$success_2 &= false;
								}
                            }
                        }else{
                            $success_2 = false;
                            Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                        }
                    }
                }else{
					$success_1 = false;
				}
				
				
				/* ================ START PROSES JURNAL AKUNTANSI  ========= */
//				if(!empty($model->spo_id)){
//					$modTerimaDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$model->terima_bhp_id])->all();
//					$total['subtotal_atk'] = 0; $total['subtotal_rt'] = 0; $total['subtotal_cetakan'] = 0; $total['subtotal_bp'] = 0;
//					$total['ppn_atk'] = 0; $total['ppn_rt'] = 0; $total['ppn_cetakan'] = 0; $total['ppn_bp'] = 0;
//					if(!empty($model->spo_id)){
//						$is_pkp = (($model->spo->spo_is_pkp)?"PPN":"NON-PPN");
//						$reff_no = $model->spo->spo_kode;
//					}else{
//						$is_pkp = "NON-PPN";
//						$reff_no = $model->spl->spl_kode;
//					}
//					$modTerimaDetailGroup = array();
//					foreach($modTerimaDetail as $ii => $detailterima){
//						$id = $detailterima->bhp->bhp_group;
//						if (isset($modTerimaDetailGroup[$id])) {
//							$modTerimaDetailGroup[$id][] = $detailterima->attributes;
//						}else{
//							$modTerimaDetailGroup[$id] = array($detailterima->attributes);
//						}
//					}
//					foreach($modTerimaDetailGroup as $iv => $detailed){
//						$subtotal = 0; $ppn = 0;
//						foreach($detailed as $totalkelbrg){
//							$subtotal += ($totalkelbrg['terimabhpd_qty'] * $totalkelbrg['terimabhpd_harga']);
//							$ppn += ($totalkelbrg['terimabhpd_qty'] * $totalkelbrg['terimabhpd_harga']) * 0.1;
//						}
//						$modAcctSetup = \app\models\MAcctSetup::findByAcctKeyDefaultValue($iv,$is_pkp);
//						if(!empty($modAcctSetup)){
//							$id_transaksi = $modAcctSetup->acct_setup_id;
//							if(!empty($modAcctSetup)>0){
//								$modAuto = \app\models\MAcctAuto::find()->where(['acct_setup_id'=>$id_transaksi])->all();
//								if(count($modAuto)>0){
//									foreach($modAuto as $v => $rek){
//										$modJurnal = new \app\models\TAcctJurnal();
//										$modJurnal->reff_no = $reff_no;
//										$modJurnal->memo = $modAcctSetup->acct_setup_nm;
//										$modJurnal->acct_id = $rek->acct_id;
//										switch ($modJurnal->acct_id){
//											case \app\components\Params::ACCT_REKENING_DEBET_ATK:
//												$modJurnal->debet = $subtotal;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_DEBET_RT:
//												$modJurnal->debet = $subtotal;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_DEBET_CETAKAN:
//												$modJurnal->debet = $subtotal;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_DEBET_BP_BOP:
//												$modJurnal->debet = $subtotal;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_DEBET_PPN_BELUM_FAKTUR:
//												$modJurnal->debet = $ppn;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_DEBET_PPN_MASUKAN:
//												$modJurnal->debet = $ppn;
//												$modJurnal->kredit = 0;
//												break;
//											case \app\components\Params::ACCT_REKENING_KREDIT_PEMBAYARAN_BP:
//												$modJurnal->debet = 0;
//												$modJurnal->kredit = $subtotal + $ppn;
//												break;
//										}
//										$success_5 &= $modJurnal->autoInsertJurnal();
//									}
//								}
//							}
//						}else{
//							$success_5 &= FALSE;
//							$transaction->rollback();
//							Yii::$app->session->setFlash('error', Yii::t('app', 'Setup Jurnal '.$iv.' Tidak ditemukan'));
//						}
//					}	
//				}
					
//					echo "<pre>1";
//					print_r($success_1);
//					echo "<pre>2";
//					print_r($success_2);
//					echo "<pre>3";
//					print_r($success_3);
//					echo "<pre>4";
//					print_r($success_4);
//					echo "<pre>5";
//					print_r($success_5);
//					echo "<pre>6";
//					print_r($success_6);
//					echo "<pre>7";
//					print_r($success_7);
//					echo "<pre>8";
//					print_r($success_8);
//					exit;
				/* ================ END PROSES JURNAL AKUNTANSI ========= */
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5 && $success_6 && $success_7 && $success_8) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Penerimaan Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'terima_bhp_id'=>$model->terima_bhp_id]);
                } else {
                    $transaction->rollback();
					if(empty(Yii::$app->session->getFlash('error'))){
						Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
					}
					
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail]);
	}
	
	public function actionGetItemDariSPO(){
		if(\Yii::$app->request->isAjax){
            $spo_id = Yii::$app->request->post('spo_id');
			$terima_bhp_id = Yii::$app->request->post('terima_bhp_id');
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($spo_id)){
                $modSpo = \app\models\TSpo::findOne($spo_id);
                $modDetailSpo = \app\models\TSpoDetail::find()->where(['spo_id'=>$spo_id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->all();
				$model = new \app\models\TTerimaBhp();
				if(!empty($modSpo->terima_bhp_id)){
					$modTBP = \app\models\TTerimaBhp::findOne($modSpo->terima_bhp_id);
					$data['tbp'] = $modTBP->attributes;
				}
                if(count($modDetailSpo)>0){
                    foreach($modDetailSpo as $i => $spo){
						$modelDetail = new \app\models\TTerimaBhpDetail();
						if(!empty($terima_bhp_id)){
							$modelDetail = \app\models\TTerimaBhpDetail::findOne(['terima_bhp_id'=>$terima_bhp_id,'bhp_id'=>$spo->bhp_id]);
							if(count($modelDetail)>0){
								$disabled = true;
								$modelDetail->terimabhpd_harga = $modelDetail->terimabhpd_harga;
								$modelDetail->terimabhpd_diskon = 0;
								$modelDetail->subtotal = $modelDetail->terimabhpd_qty * $modelDetail->terimabhpd_harga;
								$modelDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUser($modelDetail->subtotal,2);
								$modelDetail->terimabhpd_harga = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->terimabhpd_harga);
								$modelDetail->terimabhpd_harga_display = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->terimabhpd_harga);
								$modelDetail->subtotal_display = \app\components\DeltaFormatter::formatNumberForUser($modelDetail->subtotal,2);
								$modelDetail->terimabhpd_keterangan = $modelDetail->terimabhpd_keterangan;
								$modelDetail->npwp = !empty($modelDetail->suplier_id)?$modelDetail->suplier->suplier_npwp:"";
								$data['html'] .= $this->renderPartial('_item',['modelDetail'=>$modelDetail,'i'=>$i,'disabled'=>$disabled,'qty_po'=>$spo->spod_qty]);
								$data['modSpo'] = $modSpo->attributes;
								$data['modSpo']['name_en'] = $modSpo->defaultValue->name_en;
							}
						}else{
							$modelDetail->attributes = $spo->attributes;
							$modelDetail->spod_id = $spo->spod_id;
//							$modelDetail->terimabhpd_qty = $spo->spod_qty;
							$modelDetail->terimabhpd_qty = 0;
							$modelDetail->terimabhpd_qty_old = $spo->spod_qty;
							$modelDetail->terimabhpd_harga = $spo->spod_harga;
							$modelDetail->terimabhpd_diskon = 0;
							$modelDetail->subtotal = $modelDetail->terimabhpd_qty * $modelDetail->terimabhpd_harga;
							$modelDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->subtotal,2);
							$modelDetail->terimabhpd_harga = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->terimabhpd_harga);
							$modelDetail->terimabhpd_harga_display = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->terimabhpd_harga);
							$modelDetail->subtotal_display = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->subtotal,2);
							$modelDetail->terimabhpd_keterangan = $spo->spod_keterangan;
							$data['html'] .= $this->renderPartial('_item',['modelDetail'=>$modelDetail,'i'=>$i,'disabled'=>$disabled,'qty_po'=>$spo->spod_qty]);
							$data['modSpo'] = $modSpo->attributes;
							$data['modSpo']['name_en'] = $modSpo->defaultValue->name_en;
						}
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionGetItemDariSPL(){
		if(\Yii::$app->request->isAjax){
            $spl_id = Yii::$app->request->post('spl_id');
            $data = [];
            $data['html'] = '';
            if(!empty($spl_id)){
                $modSpl = \app\models\TSpl::findOne($spl_id);
				$data['modSpl'] = $modSpl->attributes;
                $modDetailSpl = \app\models\TSplDetail::find()->where(['spl_id'=>$spl_id])->all();
				$model = new \app\models\TTerimaBhp();
				if(!empty($modSpl->terima_bhp_id)){
					$modTBP = \app\models\TTerimaBhp::findOne($modSpl->terima_bhp_id);
					$data['tbp'] = $modTBP->attributes;
				}
                if(count($modDetailSpl)>0){
                    foreach($modDetailSpl as $i => $spl){
						$modelDetail = new \app\models\TTerimaBhpDetail();
						$modelDetail->attributes = $spl->attributes;
						$modelDetail->spld_id = $spl->spld_id;
//						$modelDetail->terimabhpd_qty = $spl->spld_qty;
						$modelDetail->terimabhpd_qty_old = $spl->spld_qty;
						$modelDetail->terimabhpd_qty = 0;
						$modelDetail->harga_estimasi = $spl->spld_harga_estimasi;
						$modelDetail->terimabhpd_harga = $spl->spld_harga_estimasi;
						$modelDetail->terimabhpd_diskon = 0;
						$modelDetail->subtotal = $modelDetail->terimabhpd_qty * $modelDetail->terimabhpd_harga;
						$modelDetail->subtotal_display = \app\components\DeltaFormatter::formatNumberForUserFloat($modelDetail->subtotal);
						$modelDetail->terimabhpd_keterangan = $spl->spld_keterangan;
                        $data['html'] .= $this->renderPartial('_itemSPL',['modelDetail'=>$modelDetail,'i'=>$i,'qty_spl'=>$spl->spld_qty]);
						$data['modelDetail'][] = $modelDetail->attributes;
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionGetData(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->post('id');
			$data = [];
			if(!empty($id)){
				$data['model'] = \app\models\TTerimaBhp::findOne($id)->attributes;
                $modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$id])->orderBy(['terima_bhp_id'=>SORT_ASC])->all();
				foreach( $modDetail as $i => $detail){
					$data['modDetail'][] = $detail->attributes;
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionGetItemsByTerimaBhp(){
		if(\Yii::$app->request->isAjax){
            $terima_bhp_id = Yii::$app->request->post('terima_bhp_id');
            $data = [];
            $data['html'] = '';
			$qty_po = ""; $qty_spl="";
            if(!empty($terima_bhp_id)){
                $model = \app\models\TTerimaBhp::findOne($terima_bhp_id);
                $modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$terima_bhp_id])->orderBy(['terima_bhp_id'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    foreach($modDetail as $i => $detail){
						$detail->diskon_rp = ($detail->terimabhpd_diskon / 100) * ($detail->terimabhpd_harga * $detail->terimabhpd_qty);
						$detail->subtotal = ($detail->terimabhpd_harga * $detail->terimabhpd_qty) - $detail->diskon_rp;
						if(!empty($model->spl_id)){
							$detailSpl = \app\models\TSplDetail::find()->where(['spl_id'=>$model->spl_id,'bhp_id'=>$detail->bhp_id])->andWhere("spld_keterangan ILIKE '%{$detail->terimabhpd_keterangan}%' ")->one();
							if(empty($detailSpl)){
								$detailSpl = \app\models\TSplDetail::find()->where(['spl_id'=>$model->spl_id,'bhp_id'=>$detail->bhp_id])->one();
							}
							if(!empty($detailSpl)){
								$detail->harga_estimasi = !empty($detailSpl->spld_harga_estimasi)? \app\components\DeltaFormatter::formatNumberForUser($detailSpl->spld_harga_estimasi):0;
								$detail->spld_id = $detailSpl->spld_id;
								$qty_spl = $detailSpl->spld_qty;
							}
						}else if($model->spo_id){
							$detailSpo = \app\models\TSpoDetail::find()->where(['spo_id'=>$model->spo_id,'bhp_id'=>$detail->bhp_id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->one();
							$qty_po = $detailSpo->spod_qty;
						}
						$detail->terimabhpd_harga_display = \app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_harga);
						$detail->diskon_rp = \app\components\DeltaFormatter::formatNumberForUserFloat($detail->diskon_rp);
						$detail->subtotal_display = \app\components\DeltaFormatter::formatNumberForUser($detail->subtotal,2);
						$detail->subtotal = \app\components\DeltaFormatter::formatNumberForUser($detail->subtotal,2);
						$detail->terimabhpd_harga = \app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_harga);
						$detail->ppn_peritem = !empty($detail->ppn_peritem)?\app\components\DeltaFormatter::formatNumberForUser($detail->ppn_peritem,2):0;
						$detail->pph_peritem = !empty($detail->pph_peritem)?\app\components\DeltaFormatter::formatNumberForUser($detail->pph_peritem,2):0;
//                        $data['html'] .= $this->renderPartial( (!empty($model->spo_id)?'_itemAfterSave':'_itemAfterSaveSPL') ,['detail'=>$detail,'i'=>$i,'model'=>$model]);
						$detail->npwp = !empty($detail->suplier_id)?$detail->suplier->suplier_npwp:"";
                        $data['html'] .= $this->renderPartial( (!empty($model->spo_id)?'_itemAfterSave':'_itemSPL') ,['modelDetail'=>$detail,'i'=>$i,'model'=>$model,'qty_po'=>$qty_po,'qty_spl'=>$qty_spl]);
                        $data['detail'][] = $detail->attributes;
                    }
                }
				$data['terimabhp'] = $model->attributes;
				if(!empty($model->spo_id)){
					$modSPO = \app\models\TSpo::findOne($model->spo_id);
					$data['name_en'] = $modSPO->defaultValue->name_en;
				}
				if(!empty($model->spl_id)){
					$data['name_en'] = "Rp";
				}
            }
            return $this->asJson($data);
        }
    }
	
	public function actionInfoSpo($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpo::findOne($id);
            $modDetail = \app\models\TSpoDetail::find()->where(['spo_id'=>$id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->all();
			return $this->renderAjax('infoSpo',['model'=>$model,'modDetail'=>$modDetail]);
		}
    }
	public function actionInfoSpl($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpl::findOne($id);
            $modDetail = \app\models\TSplDetail::find()->where(['spl_id'=>$id])->all();
			return $this->renderAjax('infoSpl',['model'=>$model,'modDetail'=>$modDetail]);
		}
    }
	
	public function actionDaftarTerimaBhp(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-terimabhp'){
				$param['table']= \app\models\TTerimaBhp::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TTerimaBhp::primaryKey()[0];
				$param['column'] = [ $param['table'].'.terima_bhp_id','terimabhp_kode','spl_kode', 'spo_kode',['col_name'=>'tglterima','formatter'=>'formatDateForUser2'],'suplier_nm','nofaktur','no_fakturpajak',$param['table'].'.cancel_transaksi_id','status_bayar','tanggal_bayar','totalbayar'];
				$param['join']= ['JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.pegawaipenerima
								  LEFT JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id 
								  LEFT JOIN t_spl ON t_spl.spl_id = t_terima_bhp.spl_id
								  LEFT JOIN t_spo ON t_spo.spo_id = t_terima_bhp.spo_id
								  LEFT JOIN t_voucher_pengeluaran ON t_voucher_pengeluaran.voucher_pengeluaran_id = t_terima_bhp.voucher_pengeluaran_id
								'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarTerimaBhp');
        }
    }
	
	public function actionSetDropdownPO(){
		if(\Yii::$app->request->isAjax){
			$suplier_id = Yii::$app->request->post('suplier_id');
            $data['html'] = [];
			$data['spo'] = [];
            if(!empty($suplier_id)){
                $mod = \app\models\TSpo::find()
						->where("suplier_id = ".$suplier_id." ")
						->andWhere("approve_status = '".\app\models\TApproval::STATUS_APPROVED."'")
						->andWhere("cancel_transaksi_id IS NULL")
						->orderBy('created_at DESC')
						->all();
                if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'spo_id', 'spo_kode');
					$html = \yii\bootstrap\Html::tag('option');
					foreach($arraymap as $i => $val){
						$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,]);
					}
                    $data['html'] = $html;
					$sql = "SELECT * FROM t_spo 
							JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_spo.terima_bhp_id 
							WHERE (t_spo.suplier_id = {$suplier_id} ) AND (approve_status = 'APPROVED') AND (t_spo.cancel_transaksi_id IS NULL) 
							ORDER BY t_spo.created_at DESC";
					$mod2 = Yii::$app->db->createCommand($sql)->queryAll();
					foreach($mod2 as $i => $det){
						$data['spo'][] = $det;
					}
                }
            }else{
//				$arraymap = \app\models\TSpo::getOptionListPenerimaan();
//				$html = \yii\bootstrap\Html::tag('option');
//				foreach($arraymap as $i => $val){
//					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,]);
//				}
//				$data['html'] = $html;
			}
			return $this->asJson($data);
		}
	}
	public function actionSetDropdownSPL(){
		if(\Yii::$app->request->isAjax){
			$suplier_id = Yii::$app->request->post('suplier_id');
            $data['html'] = [];
            $data['spl'] = [];
            if(!empty($suplier_id)){
                $mod = \app\models\TSplDetail::find()->where("suplier_id = ".$suplier_id." ")
						->join("JOIN", "t_spl", "t_spl.spl_id = t_spl_detail.spl_id")
						->select("t_spl_detail.spl_id, spl_kode")
						->groupBy("t_spl_detail.spl_id, spl_kode")
						->orderBy('t_spl_detail.spl_id DESC')->all();
                if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'spl_id', 'spl_kode');
					$html = \yii\bootstrap\Html::tag('option');
					foreach($arraymap as $i => $val){
						$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,]);
					}
                    $data['html'] = $html;
                }
            }else{
				$arraymap = \app\models\TSpl::getOptionListPenerimaan();
				$html = \yii\bootstrap\Html::tag('option');
				foreach($arraymap as $i => $val){
					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,]);
				}
				$sql = "SELECT * FROM t_spl 
						JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_spl.terima_bhp_id 
						WHERE (t_spl.cancel_transaksi_id IS NULL) 
						ORDER BY t_spl.created_at DESC";
				$mod2 = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($mod2 as $i => $det){
					$data['spl'][] = $det;
				}
				$data['html'] = $html;
			}
			return $this->asJson($data);
		}
	}
	
	public function actionUpdateSPO(){
		if(\Yii::$app->request->isAjax){
			$terima_bhp_id = Yii::$app->request->post('terima_bhp_id');
			$suplier_id = Yii::$app->request->post('suplier_id');
			$data = [];
			$modTerima = \app\models\TTerimaBhp::findOne($terima_bhp_id);
			$modTerima->suplier_id = $suplier_id;
			if($modTerima->validate()){
				$data['status'] = $modTerima->save();
			}else{
				$data['status'] = false;
			}
			if($data['status']){
				$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
			}else{
				$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE);
			}
			return $this->asJson($data);
		}
	}
	
	public function actionUpdateHargaRealisasi(){
		if(\Yii::$app->request->isAjax){
			$terima_bhp_id = Yii::$app->request->post('terima_bhp_id');
			$totalbayar = Yii::$app->request->post('totalbayar');
			$suplier_id = Yii::$app->request->post('suplier_id');
			$nofaktur = Yii::$app->request->post('nofaktur');
			$no_fakturpajak = Yii::$app->request->post('no_fakturpajak');
			$ppn_nominal = Yii::$app->request->post('ppn_nominal');
			$pph_nominal = Yii::$app->request->post('pph_nominal');
			$total_pbbkb = Yii::$app->request->post('total_pbbkb');
			$total_biayatambahan = Yii::$app->request->post('total_biayatambahan');
			$label_biayatambahan = Yii::$app->request->post('label_biayatambahan');
			$form_params = [];
			parse_str($_POST['formdata'],$form_params);
			$data = [];
			if(count($form_params['TTerimaBhpDetail'])>0){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = true; // update t_terima_bhp_detail detail
					$success_2 = true; // update harga m_brg_bhp
					$success_3 = false; // update t_terima_bhp
					$success_4 = true; // update harga t_spl_detail
					$modTerima = \app\models\TTerimaBhp::findOne($terima_bhp_id);
					$modTerima->totalbayar = $totalbayar;
					$modTerima->suplier_id = $suplier_id;
					$modTerima->nofaktur = $nofaktur;
					$modTerima->no_fakturpajak = $no_fakturpajak;
					$modTerima->ppn_nominal = $ppn_nominal;
					$modTerima->total_pbbkb = $total_pbbkb;
					$modTerima->total_biayatambahan = $total_biayatambahan;
					$modTerima->label_biayatambahan = $label_biayatambahan;
					if($modTerima->validate()){
						$success_3 = $modTerima->save();
						foreach($form_params['TTerimaBhpDetail'] as $i => $detailpost){
							$modTerimaDetail = \app\models\TTerimaBhpDetail::findOne($detailpost['terima_bhpd_id']);
							$modTerimaDetail->attributes = $detailpost;
							$modTerimaDetail->terimabhpd_harga = (isset($detailpost['terimabhpd_harga'])?$detailpost['terimabhpd_harga']:'');
							$modTerimaDetail->ppn_peritem = (isset($detailpost['ppn_peritem'])?$detailpost['ppn_peritem']:'');
							$modTerimaDetail->suplier_id = ( !empty($suplier_id)? $suplier_id : (isset($detailpost['suplier_id'])?$detailpost['suplier_id']:'') );
							$modTerimaDetail->terimabhpd_keterangan = (isset($detailpost['terimabhpd_keterangan'])?$detailpost['terimabhpd_keterangan']:'');
							if($modTerimaDetail->validate()){
								$success_1 &= $modTerimaDetail->save();
								// update harga di master bhp
								$harga_pokok = $modTerimaDetail->terimabhpd_harga;
								$nominal_ppn = 0;
								$harga = $harga_pokok;
								$include_ppn = FALSE;
								$success_2 &= \app\models\MBrgBhp::updateHargaBhp($modTerimaDetail->bhp_id,$harga_pokok,$nominal_ppn,$harga,$include_ppn);
								// end update harga di master bhp
								if(isset($detailpost['spld_id'])){
									$modSplDetail = \app\models\TSplDetail::findOne($detailpost['spld_id']);
									$modSplDetail->spld_harga_realisasi = $modTerimaDetail->terimabhpd_harga;
									if($modSplDetail->validate()){
										$success_4 &= $modSplDetail->save();
									}else{
										$success_4 = false;
									}
								}
							}else{
								$success_1 = false;
							}
						}
					}else{
						$success_3 = false;
					}
//					echo "<pre>1";
//					print_r($success_1);
//					echo "<pre>2";
//					print_r($success_2);
//					echo "<pre>3";
//					print_r($success_3);
//					echo "<pre>4";
//					print_r($success_4);
//					exit;
					if($success_1 && $success_2 && $success_3 && $success_4){
						$transaction->commit();
						$data['status'] = true;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
					} else {
                        $transaction->rollback();
                        $data['status'] = false;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE);
                    }
				} catch (\yii\db\Exception $ex) {
					$transaction->rollback();
					Yii::$app->session->setFlash('error', $ex);
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionCancelTerima($id){
		if(\Yii::$app->request->isAjax){
			$modTerima = \app\models\TTerimaBhp::findOne($id);
			$modTerimaDetails = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$id])->all();
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_terima_bhp
                    $success_3 = true; // h_persediaan
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $modTerima->terimabhp_kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
							$modTerima->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
                            if($modTerima->validate()){
								$success_2 = $modTerima->save();
							}
							foreach($modTerimaDetails as $i => $detail){
								// Start Proses Update Stock
								$detail->qty_in = 0;
								$detail->qty_out = $detail->terimabhpd_qty;
								$detail->keterangan = 'Cancel Penerimaan';
								$success_3 &= \app\models\HPersediaanBhp::updateStokPersediaan($detail,$modTerima->terimabhp_kode,$detail->terima_bhpd_id,date('d/m/Y'));
								// End Proses Update Stock
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel); 
                    }
                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Penerimaan Berhasil di Batalkan');
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
			
			return $this->renderAjax('cancelTerima',['modTerima'=>$modTerima,'modCancel'=>$modCancel]);
		}
	}
	
	public function actionGetNpwp(){
		if(\Yii::$app->request->isAjax){
			$data = '';
			$suplier_id = Yii::$app->request->post('suplier_id');
			$subtotal = Yii::$app->request->post('subtotal');
			$data['pph'] = 0;
			$modSuplier = \app\models\MSuplier::findOne($suplier_id);
			if(!empty($modSuplier)){
				$data = $modSuplier->attributes;
				if(!empty($modSuplier->suplier_npwp)){ // pph 2%
					$data['total'] = $subtotal * 0.02;
				}else{ // pph 4%
					$data['total'] = $subtotal * 0.04;
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionReturBHP($terima_bhpd_id){
		if(\Yii::$app->request->isAjax){
			$modTerimaDetail = \app\models\TTerimaBhpDetail::findOne($terima_bhpd_id);
			$modTerima = \app\models\TTerimaBhp::findOne($modTerimaDetail->terima_bhp_id);
			$model = new \app\models\TReturBhp();
			$model->kode = "Auto Generate";
			$model->tanggal = date('d/m/Y');
			$model->terima_bhpd_id = $terima_bhpd_id;
			$model->terimabhp_kode = $modTerima->terimabhp_kode;
			$model->bhp_nm = $modTerimaDetail->bhp->bhp_nm;
			$model->qty = $modTerimaDetail->terimabhpd_qty;
			$model->harga = \app\components\DeltaFormatter::formatNumberForUserFloat($modTerimaDetail->terimabhpd_harga);
			$model->potongan = 0;
			$model->total_kembali = \app\components\DeltaFormatter::formatNumberForUserFloat($modTerimaDetail->terimabhpd_harga-$model->potongan);
			$modTerimaDetail->terimabhpd_harga = \app\components\DeltaFormatter::formatNumberForUserFloat($modTerimaDetail->terimabhpd_harga);
			if( Yii::$app->request->post('TReturBhp')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_retur_bhp
                    $success_2 = false; // h_persediaan_bhp
                    $success_3 = false; // t_terima_bhp_detail
                    $model->load(\Yii::$app->request->post());
					$model->kode = \app\components\DeltaGenerator::kodeReturBHP();
                    if($model->validate()){
                        if($model->save()){
							$success_1 = true;
							// Start Proses Update Stock
							$model->bhp_id = $model->terimaBhpd->bhp_id;
							$model->qty_in = 0;
							$model->qty_out = $model->qty;
							$model->keterangan = "Retur Item BHP - ".$model->deskripsi;
							$success_2 = \app\models\HPersediaanBhp::updateStokPersediaan($model,$model->kode,$model->retur_bhp_id,date('Y-m-d'));
							// End Proses Update Stock
							
							// start update penerimaan //
							$modTerimaDetail->load(\Yii::$app->request->post());
//							$modTerimaDetail->terimabhpd_qty = $modTerimaDetail->terimabhpd_qty - $model->qty;  // edited at 18/5/2019
							$modTerimaDetail->terimabhpd_keterangan = $modTerimaDetail->terimabhpd_keterangan." ~~Pernah Diretur Dengan Kode: ".$model->kode."~~";
							if($modTerimaDetail->validate()){
								$success_3 = $modTerimaDetail->save();
							}
							// end update penerimaan //
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
					
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					echo "<pre>";
//					print_r($success_3);
//					exit;
					
                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Berhasil di Retur');
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
			
			return $this->renderAjax('returBHP',['model'=>$model,'modTerimaDetail'=>$modTerimaDetail]);
		}
	}
}
