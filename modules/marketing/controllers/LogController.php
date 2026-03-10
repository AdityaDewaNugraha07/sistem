<?php

namespace app\modules\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\MKayu;

class LogController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-log'){
			$param['table']= \app\models\MBrgLog::tableName();
			$param['pk']= \app\models\MBrgLog::primaryKey()[0];
			$param['column'] = ['log_id','log_kode','log_nama','log_satuan_jual',$param['table'].'.active', 'range_awal', 'range_akhir', 'fsc'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('@app/modules/marketing/views/log/index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MBrgLog();
			$model->active = true;
			$model->log_satuan_jual = 'M3';
            $model->range_awal = 0;
            $model->range_akhir = 0;
			if( Yii::$app->request->post('MBrgLog')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
                    $model->file1 = \yii\web\UploadedFile::getInstance($model, 'log_gambar');
                    if($model->validate()){
                        if(!empty($model->file1)){
                            $randomstring = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/gud/log';
                            if(!is_dir($dir_path)){
                                mkdir(Yii::$app->basePath.'/web/uploads/gud');
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-'.$model->log_kelompok.'-'.$randomstring.'.' . $model->file1->extension;
                            $model->file1->saveAs($file_path,false);
                            $model->log_gambar = date('Ymd_His').'-'.$model->log_kelompok.'-'.$randomstring.'.' .$model->file1->extension;
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
			// return $this->renderAjax('@app/modules/marketing/views/log/create',['model'=>$model]);
            return $this->renderAjax('create',['model'=>$model]);
		}
	}

    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MBrgLog::findOne($id);
			return $this->renderAjax('info',['model'=>$model]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MBrgLog::findOne($id);
            if ($model->range_akhir == NULL){
                $model->range_awal = 0;
            }
            if($model->range_akhir == NULL){
                $model->range_akhir = 0;
            }
            $modKayu = MKayu::findOne($model->kayu_id);
			if( Yii::$app->request->post('MBrgLog')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $file_old = $model->log_gambar;
                    $model->load(\Yii::$app->request->post());
                    $model->file1 = \yii\web\UploadedFile::getInstance($model, 'log_gambar');
                    $model->log_gambar = !empty($model->log_gambar)?$model->log_gambar:$file_old;
                    if($model->validate()){
                        if(!empty($model->file1)){
                            $randomstring = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/gud/log';
                            if(!is_dir($dir_path)){
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-'.$model->log_kelompok.'-'.$randomstring.'.' . $model->file1->extension;
                            $model->file1->saveAs($file_path,false);
                            $model->log_gambar = date('Ymd_His').'-'.$model->log_kelompok.'-'.$randomstring.'.' .$model->file1->extension;
                            if($file_old != null){
                                if (file_exists(Yii::$app->basePath.'/web/uploads/gud/log/'.$file_old)) {
                                    unlink(Yii::$app->basePath.'/web/uploads/gud/log/'.$file_old);
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
			return $this->renderAjax('@app/modules/marketing/views/log/edit',['model'=>$model, 'modKayu'=>$modKayu]);
		}
	}
	
	public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MBrgLog::findOne($id);
            $file_old = $model->log_gambar;
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
//                    if(count($modMenu)>0){
//                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_DELETE_RESTRICT_MESSAGE);
//                    }else{
                        if($model->delete()){
                            if($file_old != null){
                                if (file_exists(Yii::$app->basePath.'/web/uploads/gud/log/'.$file_old)) {
                                    unlink(Yii::$app->basePath.'/web/uploads/gud/log/'.$file_old);
                                }
                            }
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data Log Gagal dihapus');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Log Berhasil Dihapus');
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

    public function actionSetDropdownKayu(){
        if(Yii::$app->request->isAjax){
			$group_kayu = Yii::$app->request->post('log_kelompok');
            $mod = \app\models\MKayu::find()->where(['active'=>true,'group_kayu'=>$group_kayu])->orderBy(['kayu_nama' => SORT_ASC])->all();
			$html = '';
			if(!empty($mod)){
				$arraymap = \yii\helpers\ArrayHelper::map($mod, 'kayu_id', 'kayu_nama');
				foreach($arraymap as $i => $val){
					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
				}
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }

    public function actionSetKodeLog(){
        if(Yii::$app->request->isAjax){
			$kayu_id = Yii::$app->request->post('kayu_id');
            $range_awal = Yii::$app->request->post('range_awal');
            $range_akhir = Yii::$app->request->post('range_akhir');
            $val_fsc = Yii::$app->request->post('val_fsc');
			$data['log_kode'] = "";
			
            if(!empty($kayu_id)){
				$model = \app\models\MKayu::findOne($kayu_id);
				if(!empty($model)){
                    // $kayu_othername = str_replace('/', '', $model->kayu_othername);
                    if (strpos($model->kayu_othername, '/') !== false) {
                        $parts = explode('/', $model->kayu_othername);
                        $kayu_othername = $parts[1]; 
                    } else {
                        $kayu_othername = $model->kayu_othername;
                    }

                    if($range_akhir >= 200){
                        $range = $range_awal.'UP';
                    } else {
                        $range = $range_awal . $range_akhir;
                    }

                    $fsc = '';
                    if($val_fsc == 1){
                        $fsc = 'FSC 100%';
                    }
                    $data['log_kode'] = str_replace(' ', '',"CLOG".$kayu_othername.$range.$fsc);
				}
			}
            return $this->asJson($data);
        }
    }

    public function actionSetNamaLog(){
        if(Yii::$app->request->isAjax){
            $log_kelompok = Yii::$app->request->post('log_kelompok');
			$kayu_id = Yii::$app->request->post('kayu_id');
            $range_awal = Yii::$app->request->post('range_awal');
            $range_akhir = Yii::$app->request->post('range_akhir');
            $val_fsc = Yii::$app->request->post('val_fsc');
			$data['log_nama'] = "";
			
            if(!empty($kayu_id)){
				$model = \app\models\MKayu::findOne($kayu_id);
				if(!empty($model)){
                    if($range_akhir >= 200){
                        $range = $range_awal.'UP';
                    } else {
                        $range = $range_awal . $range_akhir;
                    }

                    $fsc = '';
                    if($val_fsc == 1){
                        $fsc = 'FSC 100%';
                    }

                    $data['log_nama'] = str_replace(' ', '',$log_kelompok."/".$model->kayu_nama."/".$range.$fsc);
                    
                    // if($log_kelompok == 'Ebon'){
                    //     $data['log_nama'] = str_replace(' ', '',"Ebon/".$model->kayu_nama."/".$range.$fsc);
                    // }else if($log_kelompok == 'Indah'){
                    //     $data['log_nama'] = str_replace(' ', '',"Indah/".$model->kayu_nama."/".$range.$fsc);
                    // }else if($log_kelompok == 'Meranti'){
                    //     $data['log_nama'] = str_replace(' ', '',"Meranti/".$model->kayu_nama."/".$range.$fsc);
                    // }else if($log_kelompok == 'Meranti SM'){
                    //     $data['log_nama'] = str_replace(' ', '',"Meranti SM/".$model->kayu_nama."/".$range.$fsc);
                    // }else if($log_kelompok == 'Rimba Campur'){
                    //     $data['log_nama'] = str_replace(' ', '',"Rimba Campur/".$model->kayu_nama."/".$range.$fsc);
                    // }
				}
			}
            return $this->asJson($data);
        }
    }
}
