<?php

namespace app\modules\gudang\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class MutasigudanglogistikController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TMutasiGudanglogistik();
        $modDetail = new \app\models\TMutasiGudanglogistikDetail();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->pegawai_mutasi = Yii::$app->user->identity->pegawai_id;
        
        if(isset($_GET['mutasi_gudanglogistik_id'])){
            $model = \app\models\TMutasiGudanglogistik::findOne($_GET['mutasi_gudanglogistik_id']);
            $model->spb_kode = $model->spb->spb_kode;
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modDetail = \app\models\TMutasiGudanglogistikDetail::find()->where(['mutasi_gudanglogistik_id'=>$model->mutasi_gudanglogistik_id])->all();
        }
        
        if( Yii::$app->request->post('TMutasiGudanglogistik')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $success_2 = true; 
				$success_3 = true; // maping table
				$success_4 = true; // h_persediaan_bhp
                $model->load(\Yii::$app->request->post());
				$model->kode = \app\components\DeltaGenerator::kodeMutasiGudangLogistik();
				$model->status = "-";
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						foreach($_POST['TMutasiGudanglogistikDetail'] as $i => $detail){
							$modDetail = new \app\models\TMutasiGudanglogistikDetail();
							$modDetail->attributes = $detail;
							$modDetail->mutasi_gudanglogistik_id = $model->mutasi_gudanglogistik_id;
							if($modDetail->validate()){
								if($modDetail->save()){
									$success_2 &= $success_2;
									// start insert mapping table
									$modMap = new \app\models\MapSpbDetailMutasiGudanglogistikDetail();
									$modMap->attributes = $_POST['MapSpbDetailMutasiGudanglogistikDetail'][$i];
									$modMap->mutasi_gudanglogistikd_id = $modDetail->mutasi_gudanglogistikd_id;
									$modMap->mutasi_gudanglogistikd_qty = $modDetail->qty;
									if($modMap->validate()){
										if($modMap->save()){
											$success_3 &= $success_3;
										}else{
											$success_3 = false;
										}
									}else{
										$success_3 = false;
									}
									// end insert mapping table 
									
									// Start Proses Update Stock 
										$modDetail->qty_in = $modDetail->qty;
										$modDetail->qty_out = 0;
										$success_4 &= \app\models\HPersediaanBhp::updateStokPersediaan($modDetail,$model->kode,$modDetail->mutasi_gudanglogistikd_id,$model->tanggal);
									// End Proses Update Stock									
								}else{
									$success_2 = false;
								}
							}else{
								$success_2 = false;
								Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
							}
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
//				exit;
                if ($success_1 && $success_2 && $success_3 && $success_4) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Mutasi Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'mutasi_gudanglogistik_id'=>$model->mutasi_gudanglogistik_id]);
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
	
	private function mainQuery($spb_id){
		$sql = "SELECT t_spb_detail.spbd_id AS spbd_id, sum(mutasi_gudanglogistikd_qty) AS  mutasi_qty FROM t_spb_detail 
				LEFT JOIN map_spb_detail_mutasi_gudanglogistik_detail ON map_spb_detail_mutasi_gudanglogistik_detail.spbd_id = t_spb_detail.spbd_id
				WHERE spb_id = ".$spb_id." 
				GROUP BY t_spb_detail.spbd_id
				ORDER BY t_spb_detail.spbd_id ASC";
		return $sql;
	}
			
	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $spb_id = Yii::$app->request->post('spb_id');
            $data = [];
            if(!empty($spb_id)){
				$model = \app\models\TSpb::findOne($spb_id);
				$modDetails = \Yii::$app->db->createCommand($this->mainQuery($spb_id))->queryAll();
            }else{
                $modDetails = [];
            }
            $data['html'] = '';
            if(count($modDetails)>0){
				$data['spb'] = $model->attributes;
                foreach($modDetails as $i => $detail){
					$spbdetail = \app\models\TSpbDetail::findOne($detail['spbd_id']);
					if( (empty($detail['mutasi_qty'])) || ($spbdetail->spbd_jml > $detail['mutasi_qty']) ){
						$modDetail = new \app\models\TMutasiGudanglogistikDetail();
						$modMap = new \app\models\MapSpbDetailMutasiGudanglogistikDetail();
						$modDetail->attributes = $spbdetail->attributes;
						$modDetail->bhp_nm = $spbdetail->bhp->bhp_nm;
						$modDetail->qty_spb = $spbdetail->spbd_jml;
						$modDetail->qty_termutasi = !empty($detail['mutasi_qty'])?$detail['mutasi_qty']:0;
						$modDetail->qty = $modDetail->qty_spb-$modDetail->qty_termutasi;
						$modDetail->satuan = $spbdetail->bhp->bhp_satuan;
						$modDetail->keterangan = $spbdetail->spbd_ket;
						$modMap->spbd_id = $spbdetail->spbd_id;
						$modMap->spbd_qty = $spbdetail->spbd_jml;
						$data['html'] .= $this->renderPartial('_item',['modDetail'=>$modDetail,'detail'=>$detail,'modMap'=>$modMap,'disabled'=>false]);
					}
                }
            }
            return $this->asJson($data);
        }
    }
	
	function actionGetItemsByPk(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data = [];
            if(!empty($id)){
				$model = \app\models\TMutasiGudanglogistik::findOne($id);
                $modDetails = \app\models\TMutasiGudanglogistikDetail::find()->select('*')
								->join('JOIN','map_spb_detail_mutasi_gudanglogistik_detail','map_spb_detail_mutasi_gudanglogistik_detail.mutasi_gudanglogistikd_id = t_mutasi_gudanglogistik_detail.mutasi_gudanglogistikd_id')
								->where(['mutasi_gudanglogistik_id'=>$id])->all();
            }else{
                $modDetails = [];
            }
            $data['html'] = '';
            if(count($modDetails)>0){
				$data['mutasi'] = $model->attributes;
                foreach($modDetails as $i => $detail){
					$modMap = new \app\models\MapSpbDetailMutasiGudanglogistikDetail();
					$sql = "SELECT SUM(mutasi_gudanglogistikd_qty) AS mutasi_gudanglogistikd_qty FROM map_spb_detail_mutasi_gudanglogistik_detail WHERE spbd_id = ".$detail->spbd_id;
					$mutasi_qty = Yii::$app->db->createCommand($sql)->queryOne()['mutasi_gudanglogistikd_qty'];
					$detail->bhp_nm = $detail->bhp->bhp_nm;
					$detail->qty_spb = $detail->spbd_qty;
					$detail->qty_termutasi = $mutasi_qty;
					$data['html'] .= $this->renderPartial('_item',['modDetail'=>$detail,'modMap'=>$modMap,'disabled'=>true]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarMutasi(){
        if(\Yii::$app->request->isAjax){
            $modMutasi = \app\models\TMutasiGudanglogistik::find()->where('cancel_transaksi_id IS NULL')->orderBy(['created_at'=>SORT_DESC])->all();
            return $this->renderAjax('daftarMutasi',['modMutasi'=>$modMutasi]);
        }
    }
    
}
