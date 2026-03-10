<?php

namespace app\modules\qc\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class DefectswmController extends DeltaBaseController
{
    public $defaultAction = 'index';
	public function actionIndex()
	{
		$model = new \app\models\TDefectSwm();
		$model->kode = 'Auto Generate';
        $model->tanggal = date("d/m/Y");

        if(isset($_GET['defect_swm_id'])){
            $model = \app\models\TDefectSwm::findOne($_GET['defect_swm_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
        }

        if( Yii::$app->request->post('TDefectSwm')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; //t_defect_swm
                $success_2 = false; //t_defect_swm_detail

                $model->load(\Yii::$app->request->post());
                if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeDefectswm();
				}
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;

                        if(isset($_GET['edit'])){
                            \app\models\TDefectSwmDetail::deleteAll("defect_swm_id = ".$model->defect_swm_id);
                        }
                        foreach($_POST['TDefectSwmDetail'] as $i => $detail){
                            $modDetail = new \app\models\TDefectSwmDetail();
                            $modDetail->attributes = $detail;
                            $modDetail->defect_swm_id = $model->defect_swm_id;
                            if($modDetail->validate()){
                                if($modDetail->save()){
                                    $success_2 = true;
                                }
                            }
                        }
                    }
                }

                if($success_1 && $success_2){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'defect_swm_id'=>$model->defect_swm_id]);
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

    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TDefectSwm();
            $modDetail = new \app\models\TDefectSwmDetail();
            $data['item'] = $this->renderPartial('_item',['model'=>$model,'modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }

    function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $defect_swm_id = Yii::$app->request->post('defect_swm_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
            $model = \app\models\TDefectSwm::findOne($defect_swm_id);
            $data['model'] = $model;
            $modDetails = \app\models\TDefectSwmDetail::find()->where(['defect_swm_id' => $defect_swm_id])->all();
            $data['html'] = '';
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
					$data['html'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$detail,'i'=>$i,'edit'=>$edit]);
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TDefectSwm::tableName();
				$param['pk']= $param['table'].".". \app\models\TDefectSwm::primaryKey()[0];
				$param['column'] = [$param['table'].'.defect_swm_id',					//0
									$param['table'].'.kode',							//1
									$param['table'].'.tanggal',	    					//2
                                    't_spk_sawmill.kode as kode_spk',                   //3
                                    'kayu_nama',                                        //4
                                    $param['table'].'.line_sawmill',                     //5
                                    'nomor_bandsaw'                                     //6
									];
				$param['join'] = [' JOIN t_spk_sawmill ON t_spk_sawmill.spk_sawmill_id = '.$param['table'].'.spk_sawmill_id
                                    JOIN m_kayu ON m_kayu.kayu_id = '.$param['table'].'.kayu_id'];
                $param['where'] = ['t_defect_swm.cancel_transaksi_id is null'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

    public function actionPrintDefect()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = \app\models\TDefectSwm::findOne($_GET['id']);
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'Defect Sawmill');
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

    public function actionCancelDefect($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TDefectSwm::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_defect_swm
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
			
			return $this->renderAjax('cancelDefect',['model'=>$model,'modCancel'=>$modCancel]);
		}
	}
}
?>