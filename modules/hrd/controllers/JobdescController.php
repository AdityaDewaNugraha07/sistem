<?php

namespace app\modules\hrd\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\MPegawai;
use app\models\TJobdesc;

class JobdescController extends DeltaBaseController
{	
	
	public $defaultAction = 'index';

	public function actionIndex() {
        if(\Yii::$app->request->get('dt')=='table-jobdesc'){
            $param['table'] = \app\models\MPegawai::tableName();
            $param['pk'] = $param['table'].".". \app\models\MPegawai::primaryKey()[0];
            $param['column'] = [    't_jobdesc.jobdesc_id',      // 0
                                    $param['table'].'.pegawai_nama',       // 1
                                    't_jobdesc.nama_file',  // 2
                                    'm_departement.departement_nama', //3
                                    $param['table'].'.pegawai_id'
                                ];
            $param['join'] = [ 'LEFT JOIN t_jobdesc ON m_pegawai.pegawai_id = t_jobdesc.pegawai_id
                                LEFT JOIN m_departement on m_departement.departement_id = m_pegawai.departement_id'];
            $param['where'] = ['m_pegawai.active = true'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index');
	}

	public function actionCreate(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TJobdesc();

            if($model->load(Yii::$app->request->post())) {
                echo "<pre>";
                print_r($_POST);
                
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    $success_1 = false;
                    $success_2 = false;
                    
                    $model->load(\Yii::$app->request->post());
                    echo "<br><br>model_load : ";
                    print_r($model->load(\Yii::$app->request->post()));
                    
                    $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
                    echo "<br><br>model_file : ";
                    print_r($model->file);

                    $model->nama_file = $model->file->name;
                    if ($model->validate()) {
                        if(!empty($model->file)){
                            $randomstring = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/jobdesc';
                            if(!is_dir($dir_path)){
                                mkdir($dir_path);
                            }

                            $nama_file = preg_replace("![^a-z0-9]+!i", "-",$model->file->baseName);
                            $file_path = $dir_path.'/'.date('Y-m-d_H-i-s').'-'.$nama_file.'-'.$randomstring.'.' . $model->file->extension;

                            $model->file->saveAs($file_path,false);
                            $model->nama_file = date('Y-m-d_H-i-s').'-'.$nama_file.'-'.$randomstring.'.' .$model->file->extension;
                        }

                        if ($model->save()) {
                            $success_1 = true;
                        }
                    } else {
                        $error = $model->getErrors();
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> '.$error);
                    }

                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollback();
                        return $this->redirect(['create']);
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }

                return $this->asJson($data);
            }
            return $this->renderAjax('create', ['model' => $model]);
        }
	}

    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TJobdesc::findOne($id);
            return $this->renderAjax('info',['model'=>$model]);
		}
	}

    public function actionImage($id, $gambar){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TJobdesc::findOne($id);
			return $this->renderAjax('image',['model'=>$model, 'gambar'=>$gambar]);
		}
	}	

    public function actionDelete($id){
        if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
            $model = \app\models\TJobdesc::findOne($id);
            $file_old = $model->nama_file;
            
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;

                    if($model->delete()){
                        if($file_old != null){
                            if (file_exists(Yii::$app->basePath.'/web/uploads/jobdesc/'.$file_old)) {
                                unlink(Yii::$app->basePath.'/web/uploads/jobdesc/'.$file_old);
                            }
                        }

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
                } 
                    catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid]);
        }
    }

    public function actionTambahJobdesc($id)
    {
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TJobdesc();
            $model->pegawai_id = $id;

            if($model->load(Yii::$app->request->post())) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    $success_1 = false;
                    $success_2 = false;
                    
                    $model->load(\Yii::$app->request->post());
                    $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
                    $model->nama_file = $model->file->name;
                    if ($model->validate()) {
                        if(!empty($model->file)){
                            $randomstring = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/jobdesc';
                            if(!is_dir($dir_path)){
                                mkdir($dir_path);
                            }

                            $nama_file = preg_replace("![^a-z0-9]+!i", "-",$model->file->baseName);
                            $file_path = $dir_path.'/'.date('Y-m-d_H-i-s').'-'.$nama_file.'-'.$randomstring.'.' . $model->file->extension;

                            $model->file->saveAs($file_path,false);
                            $model->nama_file = date('Y-m-d_H-i-s').'-'.$nama_file.'-'.$randomstring.'.' .$model->file->extension;
                        }

                        if ($model->save()) {
                            $success_1 = true;
                        }
                    } else {
                        $error = $model->getErrors();
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> '.$error);
                    }

                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Jobdesc Pegawai Berhasil Ditambahkan');
                    } else {
                        $transaction->rollback();
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }

                return $this->asJson($data);
            }
            return $this->renderAjax('create', ['model' => $model]);
        }
    }

    public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TJobdesc::findOne($id);
            $model->file = $model->nama_file;
            $file_lama = $model->file;

			if( Yii::$app->request->post('TJobdesc')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
                    $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
                    $model->nama_file = !empty($model->file)?$model->file->name:$file_lama;

                    if($model->validate()){
                        $dir_path = Yii::$app->basePath.'/web/uploads/jobdesc';
                        if (is_file("$dir_path/$file_lama")) {
                            unlink("$dir_path/$file_lama");
                        }

                        if(!empty($model->file)){
                            $randomstring = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/jobdesc';
                            if(!is_dir($dir_path)){
                                mkdir($dir_path);
                            }

                            $nama_file = preg_replace("![^a-z0-9]+!i", "-",$model->file->baseName);
                            $file_path = $dir_path.'/'.date('Y-m-d_H-i-s').'-'.$nama_file.'-'.$randomstring.'.' . $model->file->extension;

                            $model->file->saveAs($file_path,false);
                            $model->nama_file = date('Y-m-d_H-i-s').'-'.$nama_file.'-'.$randomstring.'.' .$model->file->extension;
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
                        $data['message'] = Yii::t('app', 'Jobdesc Pegawai Berhasil Diupdate');
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

}