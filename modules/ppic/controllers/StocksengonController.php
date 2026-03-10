<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class StocksengonController extends DeltaBaseController
{
    public $defaultAction = 'index';
    public function actionIndex(){
        $model = new \app\models\HPersediaanLog();
        $model->tgl_transaksi = date('d/m/Y');
        if((\Yii::$app->request->get('laporan_params')) !== null){
            $form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params);
            $model->tgl_transaksi = isset($form_params['HPersediaanLog']['tgl_transaksi'])?$form_params['HPersediaanLog']['tgl_transaksi']:"";
            if(!empty($model->tgl_transaksi)){
                $data['html'] = $this->renderPartial('rekapContent',['model'=>$model]);
            }
            return $this->asJson($data);
        }
        return $this->render('index',['model'=>$model]);
    }
    
    public function actionJabon(){
        $model = new \app\models\HPersediaanLog();
        $model->tgl_transaksi = date('d/m/Y');
        if((\Yii::$app->request->get('laporan_params')) !== null){
            $form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params);
            $model->tgl_transaksi = isset($form_params['HPersediaanLog']['tgl_transaksi'])?$form_params['HPersediaanLog']['tgl_transaksi']:"";
            if(!empty($model->tgl_transaksi)){
                $data['html'] = $this->renderPartial('rekapContentJabon',['model'=>$model]);
            }
            return $this->asJson($data);
        }
        return $this->render('jabon',['model'=>$model]);
    }
    
    public function actionRiwayat(){
        $model = new \app\models\HPersediaanLog();
        $model->tgl_awal = date('d/m/Y', strtotime('-30 days'));
        $model->tgl_akhir = date('d/m/Y');
        $data = "";
        if((\Yii::$app->request->get('laporan_params')) !== null){
            $form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params);
            $model->attributes = $form_params['HPersediaanLog'];
            $model->tgl_awal = $form_params['HPersediaanLog']['tgl_awal'];
            $model->tgl_akhir = $form_params['HPersediaanLog']['tgl_akhir'];
            $data['html'] = $this->renderPartial('rekapRiwayat',['model'=>$model]);
            return $this->asJson($data);
        }
        return $this->render('riwayat',['model'=>$model]);
    }
	
}
