<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PengajuandplogController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TLogBayarDp();
		$model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
		$modDetail = [];
		
		if(isset($_GET['log_kontrak_id'])){
			$modKontrak = \app\models\TLogKontrak::findOne( $_GET['log_kontrak_id'] );
            $model = \app\models\TLogBayarDp::find()->where( ['log_kontrak_id'=> $_GET['log_kontrak_id']] )->orderBy(['created_at'=>SORT_DESC])->one();
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->total_dp = \app\components\DeltaFormatter::formatNumberForUser($model->total_dp);
        }
		
		if( Yii::$app->request->post('TLogBayarDp') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_log_bayar_dp
                $success_2 = true; // t_approval
                $model->load(\Yii::$app->request->post());
                if(!isset($_GET['edit'])){
                    $model->kode = \app\components\DeltaGenerator::kodePengajuanDpLog();
                }
                $model->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        // START Create Approval
						$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
						if(count($modelApproval)>0){ // edit mode
							if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
								$success_2 = $this->saveApproval($model);
							}
						}else{ // insert mode
							$success_2 = $this->saveApproval($model);
						}
						// END Create Approvalc
                    }
                }
//                echo "<pre>";
//                print_r($success_1);
//                echo "<pre>";
//                print_r($success_2);
//                exit;
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Pengajuan DP Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'log_kontrak_id'=>$model->log_kontrak_id]);
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
    
    public function saveApproval($model){
		$success = false;
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_WIBOWO;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success = $modelApproval->createApproval();
		
        $modelApproval = new \app\models\TApproval();
        $modelApproval->assigned_to = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
        $modelApproval->reff_no = $model->kode;
        $modelApproval->tanggal_berkas = $model->tanggal;
        $modelApproval->level = 2;
        $modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
        $success &= $modelApproval->createApproval();
		
		return $success;
	}
	
	public function actionShowDetail(){
		if(\Yii::$app->request->isAjax){
            $log_kontrak_id = Yii::$app->request->post('log_kontrak_id');
            $data = [];
            $data['html'] = '';
            $data['htmldp'] = '';
            if(!empty($log_kontrak_id)){
                $model = \app\models\TLogKontrak::findOne($log_kontrak_id);
				$modDp = \app\models\TLogBayarDp::find()->where(['log_kontrak_id'=>$log_kontrak_id])->all();
                if(!empty($model)){
                    $data['html'] .= $this->renderPartial('_showItem',['model'=>$model]);
                    $data['model'] = $model->attributes;
                }
                if(count($modDp)>0){
                    $data['htmldp'] .= $this->renderPartial('_showItemDp',['model'=>$model,'modDp'=>$modDp]);
                }
            }
            return $this->asJson($data);
        }
	}
	
	public function actionGetItemsByPk(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data = [];
            $data['html'] = '';
            if(!empty($id)){
                $model = \app\models\TLogKontrak::findOne($id);
                if(!empty($model)){
                    $data['html'] .= $this->renderPartial('_showItem',['model'=>$model]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TLogKontrak::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TLogKontrak::primaryKey()[0];
				$param['column'] = [$param['table'].'.log_kontrak_id','t_log_kontrak.kode','nomor',['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],'pihak1_nama','pihak1_perusahaan','sum(total_dp) as total_dp'];
				$param['join'] = ['JOIN t_log_bayar_dp ON t_log_bayar_dp.log_kontrak_id = '.$param['table'].'.log_kontrak_id',];
				$param['group'] = 'GROUP BY '.$param['table'].'.log_kontrak_id, nomor, '.$param['table'].'.tanggal, pihak1_nama ,pihak1_perusahaan';
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionInfoKontrak(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TLogKontrak::findOne($_GET['id']);
			return $this->renderAjax('infoKontrak',['model'=>$model]);
        }
	}
}
