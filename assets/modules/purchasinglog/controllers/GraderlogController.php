<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class GraderlogController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\MGraderlog::tableName();
			$param['pk']= \app\models\MGraderlog::primaryKey()[0];
			$param['column'] = ['graderlog_id','graderlog_nm','graderlog_kode','graderlog_phone','graderlog_almt','graderlog_email','type',$param['table'].'.active'];
            if( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_SENGON)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_SENGON) ){
                $param['where'] = "m_graderlog.type = 'GLS'";
            }elseif( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_ALAM)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_ALAM) ){
                $param['where'] = "m_graderlog.type = 'GLA'";
            }
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		
		return $this->render('index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MGraderlog();
			$model->active = true;
            if( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_SENGON)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_SENGON) ){
                $model->type = "GLS";
            }elseif( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_ALAM)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_ALAM) ){
                $model->type = "GLA";
            }
			if( Yii::$app->request->post('MGraderlog')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
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
			return $this->renderAjax('create',['model'=>$model]);
		}
	}

    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MGraderlog::findOne($id);
			return $this->renderAjax('info',['model'=>$model]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MGraderlog::findOne($id);
			if( Yii::$app->request->post('MGraderlog')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data Grader Berhasil Diupdate');
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
			return $this->renderAjax('edit',['model'=>$model]);
		}
	}
	
	public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MGraderlog::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                        if($model->delete()){
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data Grader Gagal dihapus');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Grader Berhasil Dihapus');
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid]);
		}
	}
	
}
