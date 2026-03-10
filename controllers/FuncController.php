<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

class FuncController extends DeltaBaseController
{
    public function actionSetDropdownProduk(){
        if(\Yii::$app->request->isAjax){
            $mod = [];
            $mod = \app\models\MBrgProduk::getOptionList();
			$html = '<option value=""></option>';
			foreach($mod as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	
    public function actionSetMataUang(){
        if(\Yii::$app->request->isAjax){
			$val = \Yii::$app->request->post("selected");
            $mod = \app\models\MDefaultValue::findOne(['type'=>'mata-uang','value'=>$val]);
			return $this->asJson($mod);
		}
    }
}
