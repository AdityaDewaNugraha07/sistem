<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class UserController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
	public function actionAccountInfo(){
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->user->id;
            $model = \app\models\MUser::findOne($id);
            $modUserProfile = \app\models\MUserProfile::findOne($model->user_profile_id);
            return $this->renderAjax('partial/_accountInfo',['model'=>$model,'modUserProfile'=>$modUserProfile]);
        }
    }
	
    public function actionAccountEdit($id){
        if(Yii::$app->request->isAjax){
            $model = \app\models\MUser::findOne($id);
            $modUserProfile = \app\models\MUserProfile::findOne($model->user_profile_id);
            $modAvatars = \app\models\MDefaultValue::find()->where(['active'=>TRUE,'type'=>'userprofile_avatar'])->all();
            if( Yii::$app->request->post('MUser')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
                    $modUserProfile->load(\Yii::$app->request->post());
                    if($modUserProfile->validate()){
                        if($modUserProfile->save()){
                            $success_1 = true;
                            $model->load(\Yii::$app->request->post());
                            $model->user_profile_id = $modUserProfile->user_profile_id;
                            if($model->validate()){
                                if($model->save()){
                                    $success_2 = true;
                                }
                            }else{
                                $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                            }
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data Profile Berhasil Diupdate');
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
            return $this->renderAjax('partial/_accountEdit',['model'=>$model,'modUserProfile'=>$modUserProfile,'modAvatars'=>$modAvatars]);
        }
    }
	
	public function actionChangePassword(){
        $data['status']=false;
        $model = new \app\models\MUser(['scenario'=> \app\models\MUser::SCENARIO_CHANGE_PASS]);
        
        if(Yii::$app->request->isAjax && Yii::$app->request->post(\yii\helpers\StringHelper::basename($model->className()))){
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                $data['status'] = \app\models\MUser::updateAll(['password'=> $model->hashPassword($model->newpassword),'accessToken'=>md5($model->newpassword)],['user_id'=>  \Yii::$app->user->id]);
                $data['message'] = Yii::t('app', 'Password berhasil diubah');
            }else{
                $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
            }
            return $this->asJson($data);
        }
        
        return $this->renderAjax('partial/_changePassword',['model'=>$model]);
    }
	
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-user'){
			$param['table']= \app\models\MUser::tableName();
			$param['pk']= \app\models\MUser::primaryKey()[0];
			$param['column'] = ['user_id','username','fullname','name',['col_name'=>$param['table'].'.created_at','formatter'=>'formatDateTimeForUser'],$param['table'].'.active',$param['table'].'.user_group_id'];
			$param['join']= ['JOIN m_user_profile ON m_user_profile.user_profile_id = '.$param['table'].'.user_profile_id',
							'JOIN m_user_group ON m_user_group.user_group_id = '.$param['table'].'.user_group_id'];
            $param['where'] = [$param['table'].'.user_group_id != '.\app\components\Params::USER_GROUP_ID_SUPER_USER];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		
		return $this->render('index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MUser();
            $model->password = "12345";
			$model->active = true;
            $modUserProfile = new \app\models\MUserProfile();
            $modUserProfile->avatar = 'ava-01.svg';
            $modAvatars = \app\models\MDefaultValue::find()->where(['active'=>TRUE,'type'=>'userprofile_avatar'])->all();
			if( Yii::$app->request->post('MUser')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
                    $modUserProfile->load(\Yii::$app->request->post());
                    $modUserProfile->language = \app\components\Params::DEFAULT_LANGUAGE;
                    if($modUserProfile->validate()){
                        if($modUserProfile->save()){
                            $success_1 = true;
                            $model->load(\Yii::$app->request->post());
                            $model->user_profile_id = $modUserProfile->user_profile_id;
							$model->accessToken = md5($model->password);
                            $model->password = \Yii::$app->security->generatePasswordHash($model->password);
                            if($model->validate()){
                                if($model->save()){
                                    $success_2 = true;
                                }
                            }else{
                                $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                            }
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1 && $success_2) {
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
			return $this->renderAjax('create',['model'=>$model,'modUserProfile'=>$modUserProfile,'modAvatars'=>$modAvatars]);
		}
	}

    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MUser::findOne($id);
			$modUserProfile = \app\models\MUserProfile::findOne($model->user_profile_id);
			return $this->renderAjax('info',['model'=>$model,'modUserProfile'=>$modUserProfile]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MUser::findOne($id);
            $modUserProfile = \app\models\MUserProfile::findOne($model->user_profile_id);
            $modAvatars = \app\models\MDefaultValue::find()->where(['active'=>TRUE,'type'=>'userprofile_avatar'])->all();
			if( Yii::$app->request->post('MUser')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
                    $modUserProfile->load(\Yii::$app->request->post());
                    if($modUserProfile->validate()){
                        if($modUserProfile->save()){
                            $success_1 = true;
                            $model->load(\Yii::$app->request->post());
                            $model->user_profile_id = $modUserProfile->user_profile_id;
                            if($model->validate()){
                                if($model->save()){
                                    $success_2 = true;
                                }
                            }else{
                                $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                            }
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data User Berhasil Diupdate');
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
			return $this->renderAjax('edit',['model'=>$model,'modUserProfile'=>$modUserProfile,'modAvatars'=>$modAvatars]);
		}
	}
	
	public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MUser::findOne($id);
			$modUserProfile = \app\models\MUserProfile::findOne($model->user_profile_id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
                    if($model->delete()){
                        $success_1 = true;
                    }else{
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data User Login Gagal dihapus');
                    }
                    if($modUserProfile->delete()){
                        $success_2 = true;
                    }else{
                        $data['message'] = Yii::t('app', 'Data User Profile Gagal dihapus');
                    }
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data User Berhasil Dihapus');
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
