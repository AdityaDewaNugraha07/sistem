<?php

namespace app\modules\purchasing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PengajuanbiayagraderController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TBiayaGrader();
        $model->biaya_grader_kode = 'Auto Generate';
        $model->biaya_grader_tgl = date('d/m/Y');
        $model->biaya_grader_jml = 0;
		$modDetail = [];
		
		if(isset($_GET['biaya_grader_id'])){
            $model = \app\models\TBiayaGrader::findOne($_GET['biaya_grader_id']);
            $model->biaya_grader_tgl = \app\components\DeltaFormatter::formatDateTimeForUser2($model->biaya_grader_tgl);
			$model->biaya_grader_jml = (!empty($model->biaya_grader_jml)?\app\components\DeltaFormatter::formatNumberForUser($model->biaya_grader_jml):" - ");
            $modDetail = \app\models\TBiayaGraderDetail::find()->where(['biaya_grader_id'=>$model->biaya_grader_id])->all();
        }
		
		if( Yii::$app->request->post('TBiayaGrader') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $success_2 = true;
                $model->load(\Yii::$app->request->post());
                $model->biaya_grader_kode = \app\components\DeltaGenerator::kodePengajuanBiayaGreder();
                $model->status = 'UNPAID';
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        if( (isset($_POST['TBiayaGraderDetail'])) && (count($_POST['TBiayaGraderDetail'])>0) ){
                            foreach($_POST['TBiayaGraderDetail'] as $i => $detail){
                                $modDetail = new \app\models\TBiayaGraderDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->biaya_grader_id = $model->biaya_grader_id;
                                if($modDetail->validate()){
                                    if($modDetail->save()){
                                        $success_2 &= true;
                                    }else{
                                        $success_2 &= false;
                                    }
                                }
                            }
                        }else{
                            $success_2 = false;
                            Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                        }
                    }
                }
				
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Pengajuan Biaya Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'biaya_grader_id'=>$model->biaya_grader_id]);
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
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new \app\models\TBiayaGraderDetail();
			$modDetail->tipe_dinas = 'GRADING';
			$modDetail->periode_awal = date('d/m/Y',strtotime("first day of this month"));
			$modDetail->periode_akhir = date('d/m/Y',strtotime("last day of this month"));
            $data['html'] = $this->renderPartial('_item',['modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }
	
	function actionSetDropdownGrader(){
		if(\Yii::$app->request->isAjax){
			$selected_items = Yii::$app->request->post('selected_items');
            if(!empty($selected_items)){
                $selected_items = implode(', ', $selected_items);
            }
			$query = "
                SELECT * FROM m_graderlog
                WHERE m_graderlog.active IS TRUE
                    ".(($selected_items!='')?'AND graderlog_id NOT IN ('.$selected_items.')':'')." 
                ORDER BY m_graderlog.graderlog_nm ASC
            ";
            $mod = Yii::$app->db->createCommand($query)->queryAll();
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'graderlog_id', 'graderlog_nm');
			$html = \yii\bootstrap\Html::tag('option');
			foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
			}
			$data['html'] = $html;
			return $this->asJson($data);
		}
	}
	
	function actionSetMasterGrader(){
		if(\Yii::$app->request->isAjax){
            $graderlog_id = Yii::$app->request->post('graderlog_id');
            if(!empty($graderlog_id)){
                $data = \app\models\MGraderlog::findOne($graderlog_id);
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }
	
	function actionSetWilayahDinas(){
		if(\Yii::$app->request->isAjax){
            $wilayah_dinas_id = Yii::$app->request->post('wilayah_dinas_id');
            if(!empty($wilayah_dinas_id)){
                $data = \app\models\MWilayahDinas::findOne($wilayah_dinas_id);
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }
	
	public function actionGetItemsByPengajuanBiayaGrader(){
		if(\Yii::$app->request->isAjax){
            $biaya_grader_id = Yii::$app->request->post('biaya_grader_id');
            $data = [];
            $data['html'] = '';
            if(!empty($biaya_grader_id)){
                $modPengjauan = \app\models\TBiayaGrader::findOne($biaya_grader_id);
                $modPengajuanDetail = \app\models\TBiayaGraderDetail::find()->where(['biaya_grader_id'=>$biaya_grader_id])->all();
                if(count($modPengajuanDetail)>0){
                    foreach($modPengajuanDetail as $i => $detail){
                        $data['html'] .= $this->renderPartial('_itemAfterSave',['modDetail'=>$detail,'i'=>$i]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarPengajuanBiayaGrader(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-pengajuanbiaya'){
				$param['table']= \app\models\TBiayaGrader::tableName();
				$param['pk']= \app\models\TBiayaGrader::primaryKey()[0];
				$param['column'] = ['biaya_grader_id','biaya_grader_kode',['col_name'=>'biaya_grader_tgl','formatter'=>'formatDateForUser2'],['col_name'=>'biaya_grader_jml','formatter'=>'formatUang'],'biaya_grader_ket','status'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarPengajuanBiayaGrader');
        }
    }
}
