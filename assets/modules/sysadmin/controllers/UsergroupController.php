<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class UsergroupController extends DeltaBaseController
{
	public $defaultAction = 'index';
	
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-user-group'){
			$param['table']= \app\models\MUserGroup::tableName();
			$param['pk']= \app\models\MUserGroup::primaryKey()[0];
			$param['column'] = ['user_group_id','name','othername',['col_name'=>$param['table'].'.created_at','formatter'=>'formatDateTimeForUser'],$param['table'].'.active'];
            $param['where'] = ['user_group_id != '.\app\components\Params::USER_GROUP_ID_SUPER_USER];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		
		return $this->render('index');
    }
    
    public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MUserGroup();
			$model->active = true;
			if( Yii::$app->request->post('MUserGroup')){
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
			$model = \app\models\MUserGroup::findOne($id);
			return $this->renderAjax('info',['model'=>$model]);
		}
	}
    
    public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MUserGroup::findOne($id);
			if( Yii::$app->request->post('MUserGroup')){
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
			return $this->renderAjax('edit',['model'=>$model]);
		}
	}
    
    public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MUserGroup::findOne($id);
            $modUser = \app\models\MUser::find()->where(['user_group_id'=>$id])->all();
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if(count($modUser)>0){
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_DELETE_RESTRICT_MESSAGE);
                    }else{
                        if($model->delete()){
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data User Group Gagal dihapus :(');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data User Group Berhasil Dihapus');
                        } else {
                            $transaction->rollback();
                            $data['status'] = false;
                            (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                            (isset($data['message_validate']) ? $data['message'] = null : '');
                        }
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
