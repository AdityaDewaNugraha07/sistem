<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class HargalimbahController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-harga'){
			$param['table']= \app\models\MHargaLimbah::tableName();
			$param['pk']= \app\models\MHargaLimbah::primaryKey()[0];
			$param['column'] = ['harga_id','limbah_kelompok','limbah_kode','limbah_nama',['col_name'=>'harga_enduser','formatter'=>'formatUang'],['col_name'=>'harga_tanggal_penetapan','formatter'=>'formatDateForUser2'],$param['table'].'.active'];
            $param['join']= ['JOIN m_brg_limbah ON m_brg_limbah.limbah_id = '.$param['table'].'.limbah_id'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		
		return $this->render('index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MHargaLimbah();
			$model->active = true;
			$model->harga_tanggal_penetapan = date('d/m/Y');
			if( Yii::$app->request->post('MHargaLimbah')){
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
			$model = \app\models\MHargaLimbah::findOne($id);
			$modLimbah = \app\models\MBrgLimbah::findOne($model->limbah_id);
			return $this->renderAjax('info',['model'=>$model,'modLimbah'=>$modLimbah]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MHargaLimbah::findOne($id);
			$model->limbah_kelompok = $model->limbah->limbah_kelompok;
            $model->harga_tanggal_penetapan = !empty($model->harga_tanggal_penetapan)?\app\components\DeltaFormatter::formatDateTimeForUser2($model->harga_tanggal_penetapan):'';
			if( Yii::$app->request->post('MHargaLimbah')){
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
                        $data['message'] = Yii::t('app', 'Data Harga Berhasil Diupdate');
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
			$model = \app\models\MHargaLimbah::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
//                    if(count($modMenu)>0){
//                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_DELETE_RESTRICT_MESSAGE);
//                    }else{
                        if($model->delete()){
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data Harga Gagal dihapus');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Harga Berhasil Dihapus');
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
    
    public function actionSetDropdownLimbah(){
        if(\Yii::$app->request->isAjax){
			$limbah_kelompok = Yii::$app->request->post('limbah_kelompok');
            $mod = [];
            
            $mod = \app\models\MBrgLimbah::find()->where(['active'=>true,'limbah_kelompok'=>$limbah_kelompok])->all();
            
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'limbah_id', 'limbah_nama');
			$html = '';
			foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	
}
