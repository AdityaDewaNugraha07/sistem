<?php

namespace app\modules\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class LimbahController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-limbah'){
			$param['table']= \app\models\MBrgLimbah::tableName();
			$param['pk']= \app\models\MBrgLimbah::primaryKey()[0];
			$param['column'] = ['limbah_id','limbah_kode','limbah_nama','limbah_satuan_jual','limbah_satuan_muat',$param['table'].'.active'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('@app/modules/marketing/views/limbah/index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MBrgLimbah();
			$model->active = true;
			$model->limbah_satuan_jual = 'Rit';
			if( Yii::$app->request->post('MBrgLimbah')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
                    $model->file1 = \yii\web\UploadedFile::getInstance($model, 'limbah_gambar');
                    if($model->validate()){
                        if(!empty($model->file1)){
                            $randomstring = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/gud/limbah';
                            if(!is_dir($dir_path)){
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-'.$model->limbah_produk_jenis.'-'.$randomstring.'.' . $model->file1->extension;
                            $model->file1->saveAs($file_path,false);
                            $model->limbah_gambar = date('Ymd_His').'-'.$model->limbah_produk_jenis.'-'.$randomstring.'.' .$model->file1->extension;
                        }
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
			return $this->renderAjax('@app/modules/marketing/views/limbah/create',['model'=>$model]);
		}
	}

    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MBrgLimbah::findOne($id);
			return $this->renderAjax('info',['model'=>$model]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MBrgLimbah::findOne($id);
			if( Yii::$app->request->post('MBrgLimbah')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $file_old = $model->limbah_gambar;
                    $model->load(\Yii::$app->request->post());
                    $model->file1 = \yii\web\UploadedFile::getInstance($model, 'limbah_gambar');
                    $model->limbah_gambar = !empty($model->limbah_gambar)?$model->limbah_gambar:$file_old;
                    if($model->validate()){
                        if(!empty($model->file1)){
                            $randomstring = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/gud/limbah';
                            if(!is_dir($dir_path)){
                                mkdir(Yii::$app->basePath.'/web/uploads/gud');
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-'.$model->limbah_produk_jenis.'-'.$randomstring.'.' . $model->file1->extension;
                            $model->file1->saveAs($file_path,false);
                            $model->limbah_gambar = date('Ymd_His').'-'.$model->limbah_produk_jenis.'-'.$randomstring.'.' .$model->file1->extension;
                            if($file_old != null){
                                if (file_exists(Yii::$app->basePath.'/web/uploads/gud/limbah/'.$file_old)) {
                                    unlink(Yii::$app->basePath.'/web/uploads/gud/limbah/'.$file_old);
                                }
                            }
                        }
                        if($model->save()){
                            $success_1 = true;
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data Produk Berhasil Diupdate');
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
			return $this->renderAjax('@app/modules/marketing/views/limbah/edit',['model'=>$model]);
		}
	}
	
	public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MBrgLimbah::findOne($id);
            $file_old = $model->limbah_gambar;
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
//                    if(count($modMenu)>0){
//                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_DELETE_RESTRICT_MESSAGE);
//                    }else{
                        if($model->delete()){
                            if($file_old != null){
                                if (file_exists(Yii::$app->basePath.'/web/uploads/gud/limbah/'.$file_old)) {
                                    unlink(Yii::$app->basePath.'/web/uploads/gud/limbah/'.$file_old);
                                }
                            }
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data Limbah Gagal dihapus');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Limbah Berhasil Dihapus');
                        } else {
                            $transaction->rollback();
                            $data['status'] = false;
                            (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                            (isset($data['message_validate']) ? $data['message'] = null : '');
                        }
//                    }
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
