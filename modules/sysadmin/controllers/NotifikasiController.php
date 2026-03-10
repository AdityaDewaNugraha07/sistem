<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class NotifikasiController extends DeltaBaseController
{
    public function actionShowTApproval(){
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('showTApproval');
        }
    }
    
    public function actionShowOpexportProforma($id){
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('showOpexportProforma',['id'=>$id]);
        }
    }
}