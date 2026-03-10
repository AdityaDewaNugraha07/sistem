<?php

namespace app\modules\logistik\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class SppController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TSpp();
        $modDetail = new \app\models\TSppDetail();
        $model->spp_kode = 'Auto Generate';
        $model->spp_tanggal = date('d/m/Y');		
        $model->spp_tanggal_dibutuhkan = date('d/m/Y',strtotime("+1 day"));
		$model->spp_disetujui = \app\components\Params::DEFAULT_PEGAWAI_ID_BU_ERA;
		
		if(isset($_GET['spp_id'])){
            $model = \app\models\TSpp::findOne($_GET['spp_id']);
            $model->spp_tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->spp_tanggal);
            $model->spp_tanggal_dibutuhkan = \app\components\DeltaFormatter::formatDateTimeForUser2($model->spp_tanggal_dibutuhkan);
            $modDetail = \app\models\TSppDetail::find()->where(['spp_id'=>$model->spp_id])->all();
        }
		
        if( Yii::$app->request->post('TSpp')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // insert t_spp
                $success_2 = true; // insert t_spp_detail
                $success_3 = true; // insert map_spb_detail_spp_detail
                $success_4 = true; // update t_spb
                $success_5 = true; // insert t_spp_spo_spl_tbp                
				$arrDetailSpb = [];
                $model->load(\Yii::$app->request->post());
                $model->spp_kode = \app\components\DeltaGenerator::kodeSpp();
                $model->spp_status = 'TO-DO';
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        if( (isset($_POST['TSppDetail'])) && (count($_POST['TSppDetail'])>0) ){
                            foreach($_POST['TSppDetail'] as $i => $detail){
                                $modDetail = new \app\models\TSppDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->spp_id = $model->spp_id;
                                if($modDetail->validate()){
                                    if($modDetail->save()){
                                        $success_2 &= true;
                                    }else{
                                        $success_2 &= false;
                                    }
                                    // Start Insert Mapping Table
//                                    $sql = "SELECT bhp_id, SUM(sppd_qty), string_agg(sppd_ket, ', ') AS keterangan FROM t_spp_detail
//                                                    JOIN t_spp ON t_spp.spp_id = t_spp_detail.spp_id
//                                                    WHERE t_spp.departement_id = ".$model->departement_id."
//                                                            AND bhp_id = ".$detail['bhp_id']." AND sppd_id NOT IN (select sppd_id from map_spb_detail_spp_detail)
//                                                    GROUP BY bhp_id ";

//                                  perubahan tanggal 2022-06-14
                                    $sql = "SELECT bhp_id, sum(spbd_jml - spbd_jml_terpenuhi), string_agg(spbd_ket, ', ') AS keterangan FROM t_spb_detail 
                                            JOIN t_spb ON t_spb.spb_id = t_spb_detail.spb_id 
                                            WHERE departement_id = ".$model->departement_id." 
                                              AND t_spb_detail.spb_id = ".$detail['bhp_id']."
                                              AND t_spb_detail.spbd_jml_terpenuhi <> t_spb_detail.spbd_jml
                                              AND spbd_id NOT IN (select spbd_id from map_spb_detail_spp_detail)
                                            GROUP BY bhp_id
                                            ORDER BY bhp_id";
                                    $mods = \Yii::$app->db->createCommand($sql)->queryOne();
                                    $sql2 = "SELECT * FROM t_spb_detail JOIN t_spb ON t_spb.spb_id = t_spb_detail.spb_id 
                                                     WHERE departement_id = ".$model->departement_id." AND t_spb_detail.spb_id = ".$_GET['loadjs']['spb_id']." AND bhp_id = ".$detail['bhp_id'];
                                    $mods2 = \Yii::$app->db->createCommand($sql2)->queryAll();
                                    $qty_ordering = 0;
                                    foreach($mods2 as $j => $abc){
                                            $qty_ordering = $qty_ordering+$abc['spbd_jml'];
                                            $qty_current = $mods['sum'] - $detail['sppd_qty'];
//                                            if($qty_ordering > $qty_current){
                                                    $modMapping = new \app\models\MapSpbDetailSppDetail();
                                                    $modMapping->spbd_id = $abc['spbd_id'];
                                                    $modMapping->sppd_id = $modDetail->sppd_id;                                                    
                                                    if($modMapping->save()){                                                            
                                                            $success_3 &= true;    
                                                    }else{
                                                            $success_3 &= false;
                                                    }
                                                    $modTmpspp = new \app\models\TmpSppSpoSplTbp();
                                                    $modTmpspp->sppd_id = $modDetail->sppd_id;
                                                    $sq1 = "select json_agg(json_build_object('spbkode', spb_kode, 'spbdid', spbd_id)) as spb
                                                            from t_spb_detail 
                                                            join t_spb on t_spb.spb_id=t_spb_detail.spb_id 
                                                            where spbd_id in($modMapping->spbd_id)";
                                                    $modsSPbDetail = \Yii::$app->db->createCommand($sq1)->queryOne();
                                                    $modTmpspp->spbd_id = $modsSPbDetail['spb'];
                                                    if($modTmpspp->save()){
                                                        $success_5 &= true;
                                                    }else{
                                                        $success_5 &= false;
                                                    }
//                                            }
                                    }
                                    // End Insert Mapping Table
                                }
                            }
                        }else{
                            $success_2 = false;
                            Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                        }
                    }
                }
				
				// Start Update status SPB
				$spb_id = $_GET['loadjs']['spb_id'];
				$modSpb = \app\models\TSpb::findOne($spb_id);
				if(!empty($modSpb)){
					$modSpb->spb_status = "SEDANG DIPROSES";
					if($modSpb->save()){
						$success_4 &= true;
					}else{
						$success_4 &= false;
					}
				}
				// END Update status SPB
				
				// echo "<pre>";
				// print_r($success_1);
				// echo "<pre>";
				// print_r($success_2);
				// echo "<pre>";
				// print_r($success_3);
				// echo "<pre>";
				// print_r($success_4);
                // echo "<pre>";
				// print_r($success_5);
				// exit;
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data SPP Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'spp_id'=>$model->spp_id]);
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
	
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $departement_id = Yii::$app->request->post('departement_id');
            $spb_id = Yii::$app->request->post('spb_id');
			$data = [];
            $data['html'] = '';
			$data['spb'] = \app\models\TSpb::findOne($spb_id)->attributes;
			if(!empty($spb_id)){
				$spbExist = " AND spb_id = ".$spb_id." ";
			}else{
				$spbExist = "";
			}
            if(!empty($departement_id)){
//				$sql = "SELECT bhp_id, sum(spbd_jml), string_agg(spbd_ket, ', ') AS keterangan FROM t_spb_detail
//						JOIN t_spb ON t_spb.spb_id = t_spb_detail.spb_id
//						WHERE departement_id = ".$departement_id."
//						  AND t_spb_detail.spb_id = ".$spb_id."
//						  AND spbd_id NOT IN (select spbd_id from map_spb_detail_spp_detail)
//						GROUP BY bhp_id
//						ORDER BY bhp_id";
//              perubahan per tanggal 2022-06-14
                $sql = "SELECT bhp_id, sum(spbd_jml - spbd_jml_terpenuhi), string_agg(spbd_ket, ', ') AS keterangan FROM t_spb_detail 
                        JOIN t_spb ON t_spb.spb_id = t_spb_detail.spb_id 
                        WHERE departement_id = ".$departement_id." 
                          AND t_spb_detail.spb_id = ".$spb_id." 
                          AND t_spb_detail.spbd_jml_terpenuhi <> t_spb_detail.spbd_jml
                          AND spbd_id NOT IN (select spbd_id from map_spb_detail_spp_detail)
                        GROUP BY bhp_id
                        ORDER BY bhp_id";
				$model = Yii::$app->db->createCommand($sql)->queryAll();
                if(count($model)>0){
                    foreach($model as $i => $detailspb){
						$moreDetailSpb = \app\models\TSpbDetail::findOne(['spb_id'=>$spb_id,'bhp_id'=>$detailspb['bhp_id']]);
						$modBhp = \app\models\MBrgBhp::findOne($detailspb['bhp_id']);
						$modDetail = new \app\models\TSppDetail();
						$modDetail->attributes = $moreDetailSpb->attributes;
						$modDetail->bhp_nama = $modBhp->bhp_nm;
						$modDetail->qty_kebutuhan = $detailspb['sum'];
						$modDetail->qty_terpenuhi = $moreDetailSpb->spbd_jml_terpenuhi;
						$modDetail->sppd_qty = $modDetail->qty_kebutuhan - $modDetail->qty_terpenuhi;
						$modDetail->current_stock = $moreDetailSpb->bhp->current_stock;
						$modDetail->satuan = $modBhp->bhp_satuan;
						$modDetail->sppd_ket = $detailspb['keterangan'];
						$data['html'] .= $this->renderPartial('_item',['modDetail'=>$modDetail,'detailspb'=>$detailspb]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionGetItemsBySpp(){
		if(\Yii::$app->request->isAjax){
            $spp_id = Yii::$app->request->post('spp_id');
            $data = [];
            $data['html'] = '';
            if(!empty($spp_id)){
                $modSpp = \app\models\TSpp::findOne($spp_id);
                $modDetailSpp = \app\models\TSppDetail::find()->where(['spp_id'=>$spp_id])->all();
				
                if(count($modDetailSpp)>0){
                    foreach($modDetailSpp as $i => $detail){
						$modVSpbDetail = \app\models\VDetailSpb::getRekapSpbBelumTerlayani($modSpp->departement_id,$detail->bhp_id);
						$detail->attributes = $detail->attributes;
                        $detail->bhp_nama = $detail->bhp->bhp_nm;
						if(count($modVSpbDetail)>0){
							$detail->qty_kebutuhan = $modVSpbDetail->pesan;
							$detail->qty_terpenuhi = $modVSpbDetail->terpenuhi;
						}else{
							$detail->qty_kebutuhan = 0;
							$detail->qty_terpenuhi = 0;
							$modMap = \app\models\MapSpbDetailSppDetail::find()->where(['sppd_id'=>$detail->sppd_id])->all();
							if(count($modMap)>0){
								foreach($modMap as $i => $map){
									$modSPBDet = \app\models\TSpbDetail::findOne($map->spbd_id);
									if(!empty($modSPBDet)){
										$detail->qty_kebutuhan += $modSPBDet->spbd_jml;
										$detail->qty_terpenuhi += $modSPBDet->spbd_jml_terpenuhi;
									}
								}
							}
						}
                        $detail->current_stock = $detail->bhp->current_stock;
                        $detail->satuan = $detail->bhp->bhp_satuan;
                        $data['html'] .= $this->renderPartial('_itemAfterSave',['detail'=>$detail,'i'=>$i]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarSpp(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-spp'){
				$param['table']= \app\models\TSpp::tableName();
				$param['pk']= \app\models\TSpp::primaryKey()[0];
				$param['column'] = ['spp_id','spp_kode',['col_name'=>'spp_tanggal','formatter'=>'formatDateForUser2'],'departement_nama','spp_status'];
				$param['join']= ['JOIN m_departement ON m_departement.departement_id = '.$param['table'].'.departement_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarSpp');
        }
    }
    
    
}
