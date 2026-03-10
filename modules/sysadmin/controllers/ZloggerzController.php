<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\Zloggerz;
use app\models\MUser;
use app\models\MPegawai;

class ZloggerzController extends DeltaBaseController
{	
	
	public $defaultAction = 'index';

	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-user'){
			$param['table']= \app\models\Zloggerz::tableName();
			$param['pk']= \app\models\ZLoggerz::primaryKey()[0];
			$param['column'] = ['id','log_time','prefix','message'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index');
	}

}