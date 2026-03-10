<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ExtensionteleponController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\MExtensionTelepon::tableName();
			$param['pk']= \app\models\MExtensionTelepon::primaryKey()[0];
			$param['column'] = ['extension_telepon_id','ext_kode','bagian','nama','active'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		
		return $this->render('index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MExtensionTelepon();
			$model->active = true;
			if( Yii::$app->request->post('MExtensionTelepon')){
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
	
	public function actionSetValue(){
        if(\Yii::$app->request->isAjax){
			$pegawai_id = Yii::$app->request->post('pegawai_id');
			$data = [];
			if(!empty($pegawai_id)){
				$mod = \app\models\MPegawai::findOne($pegawai_id);
				$data['nama'] = $mod->pegawai_nama;
				$data['dept'] = $mod->departement->departement_nama;
			}
            return $this->asJson($data);
        }
    }

    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MExtensionTelepon::findOne($id);
			return $this->renderAjax('info',['model'=>$model]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MExtensionTelepon::findOne($id);
			if( Yii::$app->request->post('MExtensionTelepon')){
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
                        $data['message'] = Yii::t('app', 'Data Berhasil Diupdate');
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
			$model = \app\models\MExtensionTelepon::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
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
	
	public function actionList(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-extension'){
				$param['table']= \app\models\MExtensionTelepon::tableName();
				$param['pk']= \app\models\MExtensionTelepon::primaryKey()[0];
				$param['column'] = ['extension_telepon_id','ext_kode','bagian','nama'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
		}
		return $this->renderAjax('list');
	}
}
