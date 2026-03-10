<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class KeberangkatantongkangController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TKeberangkatanTongkang();
		$model->kode = 'Auto Generate';
		$modDetail = [];
		
		if(isset($_GET['keberangkatan_tongkang_id'])){
            $model = \app\models\TKeberangkatanTongkang::findOne($_GET['keberangkatan_tongkang_id']);
            $model->eta = \app\components\DeltaFormatter::formatDateTimeForUser2($model->eta);
        }
		
		if( Yii::$app->request->post('TKeberangkatanTongkang') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_keberangkatan_tongkang
                $success_2 = true; // t_keberangkatan_tongkang_detail
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeKeberangkatanTongkang();
				}
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if(isset($_POST['TKeberangkatanTongkangDetail'])){
							if(isset($_GET['edit'])){
								$existDetail = \app\models\TKeberangkatanTongkangDetail::find()->where("keberangkatan_tongkang_id = ".$model->keberangkatan_tongkang_id)->all();
								if(count($existDetail)>0){
									\app\models\TKeberangkatanTongkangDetail::deleteAll("keberangkatan_tongkang_id = ".$model->keberangkatan_tongkang_id);
								}
							}
							foreach($_POST['TKeberangkatanTongkangDetail'] as $i => $detail){
								$modDetail = new \app\models\TKeberangkatanTongkangDetail();
								$modDetail->attributes = $detail;
								$modDetail->keberangkatan_tongkang_id = $model->keberangkatan_tongkang_id;
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
                }
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				exit;
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'keberangkatan_tongkang_id'=>$model->keberangkatan_tongkang_id]);
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
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new \app\models\TKeberangkatanTongkangDetail();
			$alreadyitems = Yii::$app->request->post('alreadyitem');
			$alreadyitem = "";
			if(!empty($alreadyitems)){
				$alreadyitem = implode(",", $alreadyitems);
			}
            $data['html'] = $this->renderPartial('_item',['modDetail'=>$modDetail,'alreadyitem'=>$alreadyitem]);
            return $this->asJson($data);
        }
    }
	
	function actionSetItem(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
			$data = [];
            if(!empty($loglist_id)){
                $model = \app\models\TLoglist::findOne($loglist_id);
				$modDetail = \app\models\TLoglistDetail::find()->where('loglist_id = '.$loglist_id)->all();
				$data['qty_m3'] = 0;
				foreach($modDetail as $i => $detail){
					$data['qty_m3'] += $detail['volume_value'];
				}
				$data['loglist_id'] = $loglist_id;
				$data['qty_m3'] = \app\components\DeltaFormatter::formatNumberForUserFloat($data['qty_m3']);
				$data['lokasi_muat'] = $model->lokasi_muat;
				$data['qty_batang'] = count($modDetail);
            }
            return $this->asJson($data);
        }
    }
	
	public function actionOpenLoglist($tr_seq=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
				$param['table']= \app\models\TLoglist::tableName();
				$param['pk']= \app\models\TLoglist::primaryKey()[0];
				$param['column'] = ['loglist_id','loglist_kode','kode_bajg','t_pengajuan_pembelianlog.kode','t_log_kontrak.kode','t_log_kontrak.nomor','t_loglist.tanggal','t_loglist.tongkang','t_loglist.lokasi_muat'];
				$param['join']= ['JOIN t_pengajuan_pembelianlog ON t_pengajuan_pembelianlog.pengajuan_pembelianlog_id = t_loglist.pengajuan_pembelianlog_id
								  JOIN t_log_kontrak ON t_log_kontrak.log_kontrak_id = t_pengajuan_pembelianlog.log_kontrak_id'];
//				$param['where'] = "produk_group = '$jenis_produk'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('openLoglist',['tr_seq'=>$tr_seq]);
		}
	}
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $keberangkatan_tongkang_id = Yii::$app->request->post('keberangkatan_tongkang_id');
            $edit = Yii::$app->request->post('edit');
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($keberangkatan_tongkang_id)){
                $modKeberangkatanDetail = \app\models\TKeberangkatanTongkangDetail::find()->where(['keberangkatan_tongkang_id'=>$keberangkatan_tongkang_id])->orderBy(['keberangkatan_tongkang_id'=>SORT_ASC])->all();
                if(count($modKeberangkatanDetail)>0){
                    foreach($modKeberangkatanDetail as $i => $model){
						$model->tanggal_muat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_muat);
						$data['html'] .= $this->renderPartial('_item',['modDetail'=>$model,'alreadyitem'=>[],'edit'=>$edit]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	public function actionPickLoglist(){
		if(\Yii::$app->request->isAjax){
			$loglist_id = \Yii::$app->request->post('loglist_id');
			$data = [];
			if(!empty($loglist_id)){
				$model = \app\models\TLoglist::findOne($loglist_id);
				if(!empty($model)){
					$data = $model->attributes;
				}
			}
			
			return $this->asJson($data);
		}
	}
	public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TKeberangkatanTongkang::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TKeberangkatanTongkang::primaryKey()[0];
				$param['column'] = ['t_keberangkatan_tongkang.keberangkatan_tongkang_id',
									't_keberangkatan_tongkang.kode',
									't_keberangkatan_tongkang.nama',
									't_keberangkatan_tongkang.eta',
									't_keberangkatan_tongkang.total_loglist',
									't_keberangkatan_tongkang.total_batang',
									't_keberangkatan_tongkang.total_m3',
									't_keberangkatan_tongkang.keterangan'
									];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
}
