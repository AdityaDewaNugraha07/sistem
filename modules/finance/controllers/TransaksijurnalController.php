<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class TransaksijurnalController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
		$model = new \app\models\TAcctJurnal();
		$model->tgl_awal = date('d/m/Y',strtotime('2017-12-1'));
		$model->tgl_akhir = date('d/m/Y');
		if(\Yii::$app->request->get('dt')=='table-laporan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params); 
				$model->attributes = $form_params['TAcctJurnal'];
				$model->tgl_awal = $form_params['TAcctJurnal']['tgl_awal'];
				$model->tgl_akhir = $form_params['TAcctJurnal']['tgl_akhir'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchLaporanDt() ));
		}
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionPrintout(){
		$this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TAcctJurnal();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->tgl_awal = !empty($_GET['TAcctJurnal']['tgl_awal'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TAcctJurnal']['tgl_awal']):"";
		$model->tgl_akhir = !empty($_GET['TAcctJurnal']['tgl_akhir'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TAcctJurnal']['tgl_akhir']):"";
		$paramprint['judul'] = Yii::t('app', 'Laporan Transaksi Jurnal');
		return $this->renderPartial('print',['model'=>$model,'paramprint'=>$paramprint]);
	}
}
