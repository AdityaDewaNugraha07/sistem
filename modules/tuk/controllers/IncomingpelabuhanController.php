<?php

namespace app\modules\tuk\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class IncomingpelabuhanController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TIncomingPelabuhan();
		$model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
		$modDetail = [];
		
		if(isset($_GET['incoming_pelabuhan_id'])){
            $model = \app\models\TIncomingPelabuhan::findOne($_GET['incoming_pelabuhan_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->kode_keberangkatan = $model->keberangkatanTongkang->kode;
        }
		
		if( Yii::$app->request->post('TIncomingPelabuhan') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeIncomingPelabuhan();
				}
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                    }
                }
                if ($success_1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'incoming_pelabuhan_id'=>$model->incoming_pelabuhan_id]);
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
	
	public function actionSetParent(){
		if(\Yii::$app->request->isAjax){
            $keberangkatan_tongkang_id = Yii::$app->request->post('keberangkatan_tongkang_id');
            $incoming_pelabuhan_id = Yii::$app->request->post('incoming_pelabuhan_id');
            $data = [];
            if(!empty($keberangkatan_tongkang_id)){
                $model = \app\models\TKeberangkatanTongkang::findOne($keberangkatan_tongkang_id);
				if(!empty($model)){
					$model->total_batang = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_batang);
					$model->total_m3 = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_m3);
					$data['keberangkatan'] = $model->attributes;
				}
				if(!empty($incoming_pelabuhan_id)){
					$modIncomingPelabuhan = \app\models\TIncomingPelabuhan::findOne($incoming_pelabuhan_id);
				}
				
            }
            return $this->asJson($data);
        }
	}
	
	public function actionOpenKeberangkatan(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-keberangkatan'){
				$param['table']= \app\models\TKeberangkatanTongkang::tableName();
				$param['pk']= $param['table'].".".\app\models\TKeberangkatanTongkang::primaryKey()[0];
				$param['column'] = [$param['table'].'.keberangkatan_tongkang_id',
									$param['table'].'.kode',
									$param['table'].'.nama',
									$param['table'].'.eta',
									$param['table'].'.total_loglist',
									$param['table'].'.total_batang',
									$param['table'].'.total_m3'
									];
				$param['where']=$param['table'].".cancel_transaksi_id IS NULL AND keberangkatan_tongkang_id NOT IN ( SELECT keberangkatan_tongkang_id FROM t_incoming_pelabuhan WHERE cancel_transaksi_id IS NULL )";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('openKeberangkatan');
        }
	}
	
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $keberangkatan_tongkang_id = Yii::$app->request->post('keberangkatan_tongkang_id');
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($keberangkatan_tongkang_id)){
				$modKeberangkatan = \app\models\TKeberangkatanTongkang::findOne($keberangkatan_tongkang_id);
                $modKeberangkatanDetail = \app\models\TKeberangkatanTongkangDetail::find()->where(['keberangkatan_tongkang_id'=>$keberangkatan_tongkang_id])->orderBy(['keberangkatan_tongkang_id'=>SORT_ASC])->all();
                if(count($modKeberangkatanDetail)>0){
                    foreach($modKeberangkatanDetail as $i => $model){
						$model->tanggal_muat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_muat);
						$data['html'] .= $this->renderPartial('_item',['modDetail'=>$model,'alreadyitem'=>[]]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TIncomingPelabuhan::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TIncomingPelabuhan::primaryKey()[0];
				$param['column'] = ['t_incoming_pelabuhan.incoming_pelabuhan_id',
									't_incoming_pelabuhan.kode',
									't_incoming_pelabuhan.tanggal',
									't_keberangkatan_tongkang.kode AS kode_keberangkatan',
									't_keberangkatan_tongkang.nama',
									't_incoming_pelabuhan.keterangan',
									];
				$param['join'] = ['JOIN t_keberangkatan_tongkang ON t_keberangkatan_tongkang.keberangkatan_tongkang_id = t_incoming_pelabuhan.keberangkatan_tongkang_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
}
