<?php

namespace app\modules\purchasing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class DpbhpController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TDpBhp();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
		$model->nominal = 0;
		$model->cara_bayar = "Cash";
		$model->status = "PAID";
		
		if(isset($_GET['dp_bhp_id'])){
            $model = \app\models\TDpBhp::findOne($_GET['dp_bhp_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->nominal = \app\components\DeltaFormatter::formatNumberForUser($model->nominal);
        }
		
        if( Yii::$app->request->post('TDpBhp')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $model->load(\Yii::$app->request->post());
                $model->kode = \app\components\DeltaGenerator::kodeDpBhp();
                $model->cara_bayar = $_POST['TDpBhp']['cara_bayar'];
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                    }
                }
//				echo "<pre>";
//				print_r($success_1);
//				exit;
                if ($success_1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data DP Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'dp_bhp_id'=>$model->dp_bhp_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionDaftarDpBhp(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-dp'){
				$param['table']= \app\models\TDpBhp::tableName();
				$param['pk']= \app\models\TDpBhp::primaryKey()[0];
				$param['column'] = ['dp_bhp_id',$param['table'].'.kode',['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],'suplier_nm',$param['table'].'.cara_bayar','nominal','status','status_bayar','tanggal_bayar','m_default_value.name_en as mata_uang'];
				$param['join']= ['JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id
								  LEFT JOIN t_voucher_pengeluaran ON t_voucher_pengeluaran.voucher_pengeluaran_id = '.$param['table'].'.pembayaran_voucher
								  JOIN m_default_value ON m_default_value.value = t_dp_bhp.mata_uang
								'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarDp');
        }
    }
	
}
