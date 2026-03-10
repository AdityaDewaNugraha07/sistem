<?php

namespace app\modules\qc\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class LosstimeswmController extends DeltaBaseController
{
    public $defaultAction = 'index';
	public function actionIndex()
	{
		$model = new \app\models\TLosstimeSwm();
		$model->kode = 'Auto Generate';
        $model->tanggal = date("d/m/Y");

        if(isset($_GET['losstime_swm_id'])){
            $model = \app\models\TLosstimeSwm::findOne($_GET['losstime_swm_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
        }
		
        if( Yii::$app->request->post('TLosstimeSwm')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; //t_losstime_swm
                $success_2 = false; //t_losstime_swm_detail

                $model->load(\Yii::$app->request->post());
                if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeLosstimeswm();
				}
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;

                        if(isset($_GET['edit'])){
                            \app\models\TLosstimeSwmDetail::deleteAll("losstime_swm_id = ".$model->losstime_swm_id);
                        }
                        foreach($_POST['TLosstimeSwmDetail'] as $i => $detail){
                            $modDetail = new \app\models\TLosstimeSwmDetail();
                            $modDetail->attributes = $detail;
                            $modDetail->losstime_swm_id = $model->losstime_swm_id;
                            $losstime_start = explode(" - ", $_POST['TLosstimeSwmDetail'][$i]['losstime_start']);
                            $losstime_start = $losstime_start[0]." ".$losstime_start[1].":00";
                            $modDetail->losstime_start = $losstime_start;
                            $losstime_end = explode(" - ", $_POST['TLosstimeSwmDetail'][$i]['losstime_end']);
                            $losstime_end = $losstime_end[0]." ".$losstime_end[1].":00";
                            $modDetail->losstime_end = $losstime_end;
                            if($modDetail->validate()){
                                if($modDetail->save()){
                                    $success_2 = true;
                                }
                            }
                        }
                    }
                }
                // print_r($modDetail);
                // exit;
                if($success_1 && $success_2){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'losstime_swm_id'=>$model->losstime_swm_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (yii\db\Exception $ex){
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index', ['model' => $model]);
	}

    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TLosstimeSwm();
            $modDetail = new \app\models\TLosstimeSwmDetail();
            $data['item'] = $this->renderPartial('_item',['model'=>$model,'modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }

    public function actionSetSPK(){
        $spk_sawmill_id = Yii::$app->request->post('spk_sawmill_id');
        $data = [];
        if($spk_sawmill_id){
            $modSpk = \app\models\TSpkSawmill::findOne($spk_sawmill_id);
            if($modSpk){
                $data = $modSpk;
            }
            return $this->asJson($data);
        }
    }

    function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $losstime_swm_id = Yii::$app->request->post('losstime_swm_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
            $model = \app\models\TLosstimeSwm::findOne($losstime_swm_id);
            $data['model'] = $model;
            $modDetails = \app\models\TLosstimeSwmDetail::find()->where(['losstime_swm_id' => $losstime_swm_id])->all();
            $data['html'] = '';
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
                    $detail->losstime_start = \app\components\DeltaFormatter::formatDateTimeDBTPHPLV($detail->losstime_start);
                    $detail->losstime_end = \app\components\DeltaFormatter::formatDateTimeDBTPHPLV($detail->losstime_end);
					$data['html'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$detail,'i'=>$i,'edit'=>$edit]);
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TLosstimeSwm::tableName();
				$param['pk']= $param['table'].".". \app\models\TLosstimeSwm::primaryKey()[0];
				$param['column'] = [$param['table'].'.losstime_swm_id',					//0
									$param['table'].'.kode',							//1
									$param['table'].'.tanggal',	    					//2
                                    't_spk_sawmill.kode as kode_spk',                   //3
                                    $param['table'].'.line_sawmill',                    //4
									];
				$param['join'] = [' JOIN t_spk_sawmill ON t_spk_sawmill.spk_sawmill_id = '.$param['table'].'.spk_sawmill_id'];
                $param['where'] = ['t_losstime_swm.cancel_transaksi_id is null'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

    public function actionPrintLosstime()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = \app\models\TLosstimeSwm::findOne($_GET['id']);
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'Losstime Sawmill');
        if ($caraprint == 'PRINT') {
            return $this->render('print', ['model' => $model, 'paramprint' => $paramprint]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('print', ['model' => $model, 'paramprint' => $paramprint]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('print', ['model' => $model, 'paramprint' => $paramprint]);
        }
    }

    public function actionCancelLosstime($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TLosstimeSwm::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_losstime_swm
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $model->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
                            if($model->updateAttributes(['cancel_transaksi_id'=>$modCancel->cancel_transaksi_id])){
								$success_2 = TRUE;
							}else{
								$success_2 = FALSE;
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel); 
                    }
					
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Defect Sawmill Berhasil di Batalkan');
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			
			return $this->renderAjax('cancelLosstime',['model'=>$model,'modCancel'=>$modCancel]);
		}
	}
}