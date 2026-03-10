<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class JurnalmemorialController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TAcctJurnal();
        
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->status_posting = "UNPOSTED";
        $model->reff_no = "Memorial";
		$modJurnals = [];
        if(isset($_GET['kode'])){
            $modJurnals = \app\models\TAcctJurnal::find()->where(['kode'=>$_GET['kode']])->all();
			$model->attributes = $modJurnals[0]->attributes;
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
        }
        
        if( Yii::$app->request->post('TAcctJurnal')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = true;
				$kodejurnal = \app\components\DeltaGenerator::kodeJurnalAcct();
				if(isset($_POST['TAcctJurnal'])){
					$model = new \app\models\TAcctJurnal();
					foreach( $_POST['TAcctJurnal'] as $i => $jurnal ){
						if(is_array($jurnal)){
							$model = new \app\models\TAcctJurnal();
							$model->attributes = $_POST['TAcctJurnal'];
							$model->attributes = $jurnal;
							$model->kode = $kodejurnal;
							$success_1 &= $model->autoInsertJurnal();
						}
					}
				}
                if ($success_1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Jurnal Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'kode'=>$model->kode]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modJurnals'=>$modJurnals]);
	}
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modJurnal = new \app\models\TAcctJurnal();
			$modJurnal->debet = 0;
			$modJurnal->kredit = 0;
            $data['item'] = $this->renderPartial('_itemDetail',['modJurnal'=>$modJurnal]);
            return $this->asJson($data);
        }
    }
	
	
}
