<?php

namespace app\modules\purchasing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class SplController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TSpl();
        $model->spl_kode = 'Auto Generate';
        $model->spl_tanggal = date('d/m/Y');
		$modDetail = [];
		
		if(isset($_GET['spl_id'])){
            $model = \app\models\TSpl::findOne($_GET['spl_id']);
            $model->spl_tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->spl_tanggal);
            $modDetail = \app\models\TSplDetail::find()->where(['spl_id'=>$model->spl_id])->all();
        }
		
		if( Yii::$app->request->post('TSpl')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $success_2 = true;
				$success_3 = true;  // insert map_spp_detail_reff
                $model->load(\Yii::$app->request->post());
                $model->spl_kode = \app\components\DeltaGenerator::kodeSpl();
                $model->spl_status = 'BELUM DISETUJUI';
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        if( (isset($_POST['TSplDetail'])) && (count($_POST['TSplDetail'])>0) ){
                            foreach($_POST['TSplDetail'] as $i => $detail){
                                $modDetail = new \app\models\TSplDetail(); 
                                $modDetail->attributes = $detail; 
                                $modDetail->spl_id = $model->spl_id; 
                                if($modDetail->validate()){
                                    if($modDetail->save()){
                                        $success_2 &= true;
                                    }else{
                                        $success_2 &= false;
                                    }
                                }else{
									$success_2 = false;
								}
								
								// Start Insert Mapping Table
								$modMap = new \app\models\MapSppDetailReff();
								$modMap->sppd_id = $detail['sppd_id'];
								$modMap->reff_no = $model->spl_kode;
								$modMap->reff_detail_id = $modDetail->spld_id;
								if($modMap->validate()){
									$success_3 &= $modMap->save();
								}else{
									$success_3 = false;
								}
								// End Insert Mapping Table
								
                            }
                        }else{
                            $success_2 = false;
                            Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                        }
                    }
                }
//				echo "<pre>";
//				print_r($success_1);
//				echo "<pre>";
//				print_r($success_2);
//				echo "<pre>";
//				print_r($success_3);
//				exit;
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data SPL Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'spl_id'=>$model->spl_id]);
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
	
	public function actionPickPanel(){
        if(\Yii::$app->request->isAjax){
			$modGroup = \app\models\TSppDetail::find()
					->select("t_spp_detail.bhp_id,bhp_nm, SUM(sppd_qty) as sppd_qty")
					->join('JOIN','t_spp','t_spp.spp_id = t_spp_detail.spp_id')
					->join('JOIN','m_brg_bhp','m_brg_bhp.bhp_id = t_spp_detail.bhp_id')
					->groupBy("t_spp_detail.bhp_id,bhp_nm")
					->all();
			return $this->renderAjax('rekapSpp',['modGroup'=>$modGroup]);
        }
    }
	
	public function actionAddItem(){
		if(\Yii::$app->request->isAjax){
			$bhp_id = Yii::$app->request->post('bhp_id');
			$qty = Yii::$app->request->post('qty');
			$data['html'] = '';
			$modSplDetail = new \app\models\TSplDetail();
			if(!empty($bhp_id)){
				$modBhp = \app\models\MBrgBhp::findOne($bhp_id);
				if(count($modBhp)>0){
					$data['detail'] = $modBhp->attributes;
					$modSplDetail->bhp_id = $modBhp->bhp_id;
					$modSplDetail->spld_qty = $qty;
					$modSplDetail->spld_harga_estimasi = $modBhp->bhp_harga;
					$modSplDetail->subtotal = $modSplDetail->spld_qty * $modSplDetail->spld_harga_estimasi;
					$modSplDetail->spld_harga_estimasi = \app\components\DeltaFormatter::formatNumberForUser($modSplDetail->spld_harga_estimasi);
					$modSplDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUser($modSplDetail->subtotal);
					$data['html'] .= $this->renderPartial('_item',['modSplDetail'=>$modSplDetail]);
				}
			}else{
				$data['html'] .= $this->renderPartial('_item',['modSplDetail'=>$modSplDetail]);
			}
			return $this->asJson($data);
        }
	}
	
	function actionSetDropdownBhp(){
		if(\Yii::$app->request->isAjax){
			$selected_items = Yii::$app->request->post('selected_items');
            if(!empty($selected_items)){
                $selected_items = implode(', ', $selected_items);
            }
			$query = "
                SELECT * FROM m_brg_bhp
                WHERE m_brg_bhp.active IS TRUE
                    ".(($selected_items!='')?'AND bhp_id NOT IN ('.$selected_items.')':'')." 
                ORDER BY m_brg_bhp.bhp_id ASC
            ";
            $mod = Yii::$app->db->createCommand($query)->queryAll();
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'bhp_id', 'bhp_nm');
			$html = \yii\bootstrap\Html::tag('option');
			foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
			}
			$data['html'] = $html;
			return $this->asJson($data);
		}
	}
	
	function actionSetItem(){
		if(\Yii::$app->request->isAjax){
            $bhp_id = Yii::$app->request->post('bhp_id');
            if(!empty($bhp_id)){
                $data = \app\models\MBrgBhp::findOne($bhp_id);
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }
	
	public function actionGetItemsBySpl(){
		if(\Yii::$app->request->isAjax){
            $spl_id = Yii::$app->request->post('spl_id');
            $data = [];
            $data['html'] = '';
            if(!empty($spl_id)){
                $modSpl = \app\models\TSpl::findOne($spl_id);
                $modDetailSpl = \app\models\TSplDetail::find()->where(['spl_id'=>$spl_id])->all();
                if(count($modDetailSpl)>0){
                    foreach($modDetailSpl as $i => $detail){
                        $data['html'] .= $this->renderPartial('_itemAfterSave',['detail'=>$detail,'i'=>$i,'modSpl'=>$modSpl]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarSpl(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-spl'){
				$param['table']= \app\models\TSpl::tableName();
				$param['pk']= \app\models\TSpl::primaryKey()[0];
				$param['column'] = ['spl_id','spl_kode',['col_name'=>'spl_tanggal','formatter'=>'formatDateForUser2'],'pegawai_nama'];
				$param['join']= ['JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.spl_disetujui'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarSpl');
        }
    }
	
	public function actionSetDetail(){
		$data = [];
		$data['html'] = '';
		$modDetail = new \app\models\TSplDetail();
		$suplier_id = \app\components\Params::DEFAULT_SUPPLIER_ID_SPL;
		$mods = \app\models\TSppDetail::find()->select("t_spp_detail.*,t_spl_detail.spld_qty")
				->join('JOIN','t_spp','t_spp.spp_id = t_spp_detail.spp_id')
				->join('LEFT JOIN','t_spl_detail','t_spl_detail.sppd_id = t_spp_detail.sppd_id')
				->where("t_spp_detail.suplier_id =  ".$suplier_id." AND spp_status IN('TO-DO','INPROGRESS') AND status_closed IS NULL")
				->andWhere("(NOT EXISTS( SELECT * FROM t_spl_detail WHERE t_spl_detail.sppd_id = t_spp_detail.sppd_id )) OR ((sppd_qty > ( SELECT sum(spld_qty) FROM t_spl_detail WHERE t_spl_detail.sppd_id = t_spp_detail.sppd_id ) ))")
				->all();
//				->createCommand()->rawSql;
		if(count($mods)>0){
			foreach($mods as $i => $detail){
				$modDetail->attributes = $detail->attributes;
				$modDetail->bhp_nm = $detail->bhp->Bhp_nm;
				$modDetail->bhp_satuan = $detail->bhp->bhp_satuan;
				$modDetail->spld_qty = $detail->sppd_qty - $detail->spld_qty;
				$modDetail->spld_harga_estimasi = \app\components\DeltaFormatter::formatNumberForUser($detail->bhp->bhp_harga);
				$modDetail->spld_keterangan = $detail->sppd_ket;
				$modDetail->suplier_id = null;
				if($detail['spld_qty']){
					$modSppDetail = \app\models\TSppDetail::findOne($detail['sppd_id']);
					$qty_terbeli = $modSppDetail->QtyTerbeli['qty'];
					if($qty_terbeli < $detail->sppd_qty){
						$data['html'] .= $this->renderPartial('_item',['modSplDetail'=>$modDetail,'loadJs'=>true]);
					}
				}else{
					$data['html'] .= $this->renderPartial('_item',['modSplDetail'=>$modDetail,'loadJs'=>true]);
				}
			}
		}
		return $this->asJson($data);
	}
	
	public function actionPrintSpl(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TSpl::findOne($_GET['id']);
		$modDetail = \app\models\TSplDetail::find()->where(['spl_id'=>$model->spl_id])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'SURAT PESANAN LANGSUNG');
		if($caraprint == 'PRINT'){
			return $this->render('printSpl',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint]);
		}
	}
	
	public function getQtyItemTerbeli($sppd_id,$suplier_id){
		$sql = "select map_spp_detail_reff.sppd_id, sum(terimabhpd_qty) as total from t_spp_detail 
				join map_spp_detail_reff ON map_spp_detail_reff.sppd_id = t_spp_detail.sppd_id 
				join t_terima_bhp_detail on t_terima_bhp_detail.terima_bhpd_id = map_spp_detail_reff.terima_bhpd_id 
				where map_spp_detail_reff.terima_bhpd_id IS NOT NULL 
					AND suplier_id = ".$suplier_id." 
					AND map_spp_detail_reff.sppd_id = ".$sppd_id." 
				group by map_spp_detail_reff.sppd_id";
		$diterima = Yii::$app->db->createCommand($sql)->queryOne();
		return $diterima['total'];
	}
	
	public function actionCancelSpl($id){
		if(\Yii::$app->request->isAjax){
			$modSpl = \app\models\TSpl::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_spl
                    $success_3 = false; // map_spp_detail_reff
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $modSpl->spl_kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
							$modSpl->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
                            if($modSpl->validate()){
								$success_2 = $modSpl->save();
								// Start delete Mapping Table
								$success_3 = \app\models\MapSppDetailReff::deleteAll("reff_no = '".$modSpl->spl_kode."' ");
								// End delete Mapping Table
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
//					exit;
                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'SPL Berhasil di Batalkan');
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
			
			return $this->renderAjax('cancelSpl',['modSpl'=>$modSpl,'modCancel'=>$modCancel]);
		}
	}
}
