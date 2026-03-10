<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PegawaiController extends DeltaBaseController
{
	
    public $defaultAction = 'index';
    
	public function actionIndex(){
        $cari = Yii::$app->request->get('cari');
        $departement_id = Yii::$app->request->get('departement_id');
        $status = Yii::$app->request->get('status');

        if(\Yii::$app->request->get('dt')=='table-master'){

                $param['table']= \app\models\MPegawai::tableName();
                $param['pk']= \app\models\MPegawai::primaryKey()[0];
                $param['column'] = ['pegawai_id',                   //0
                                    'pegawai_nama',                 //1
                                    'pegawai_jk',                   //2
                                    'departement_nama',             //3
                                    'jabatan_nama',                 //4
                                    $param['table'].'.active',      //5
                                    'm_jabatan.jabatan_id',         //6
                                    'pegawai_nik'];                 //7
                $param['join']= ['LEFT JOIN m_jabatan ON m_jabatan.jabatan_id = '.$param['table'].'.jabatan_id',
                                    'LEFT JOIN m_departement ON m_departement.departement_id = '.$param['table'].'.departement_id'];
                
                $where = ' 0 = 0';
                
                if ($cari != '') {
                    $where0 = "and m_pegawai.pegawai_nama ilike '%".$cari."%'";
                } else {
                    $where0= 'and 1 = 1';
                }

                if ($departement_id > 0) {
                    $where1 = 'and m_departement.departement_id = '.$departement_id;
                } else {
                    $where1 = 'and 2 = 2';
                }

                if ($status != 'all') {
                    $where2 = 'and m_pegawai.active = '.$status.'';
                } else {
                    $where2 = 'and 3 = 3';
                }

                $where = $where." ".$where0." ".$where1." ".$where2;

                $param['where'] = [$where];
                $param['order'] = ['m_jabatan.jabatan_id asc', 'm_pegawai.active desc', 'm_pegawai.pegawai_id asc'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
        }

		return $this->render('index',['cari'=>$cari, 'departement_id'=>$departement_id, 'status'=>$status]);
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MPegawai();
			$model->active = true;
			if( Yii::$app->request->post('MPegawai')){
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
			$model = \app\models\MPegawai::findOne($id);
			return $this->renderAjax('info',['model'=>$model]);
		}
	}
	
	public function actionEdit($id){
            if(\Yii::$app->request->isAjax){
                $model = \app\models\MPegawai::findOne($id);
                if( Yii::$app->request->post('MPegawai')){
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $success_1 = false;
                        $success_2 = false;
                        $model->load(\Yii::$app->request->post());
                        if($model->validate()){
                            if($model->save()){
                                $success_1 = true;
                                // START update active m_user    
                                $modUser = \app\models\MUser::findOne(['pegawai_id'=>$model->pegawai_id]);
                                if(!empty($modUser)){
                                    $modUser->active = $model->active;
                                    if($modUser->validate()){
                                        if($modUser->save()){
                                            $success_2 = true;
                                        }else{
                                            $success_2 = false;
                                        }
                                    }else{
                                        $success_2 = false;
                                    }
                                }else{
                                    $success_2 = true;
                                }

                            }
                        }else{
                            $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                        }
                        if ($success_1 && $success_2) {
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
			$model = \app\models\MPegawai::findOne($id);
                if( Yii::$app->request->post('deleteRecord')){
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $success_1 = false;
                        if($model->delete()){
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Gagal dihapus');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', 'Data Berhasil Dihapus');
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
	
	public function actionPrintout(){
		$this->layout = '@views/layouts/metronic/print';
		$caraprint = Yii::$app->request->get('caraprint');
		$cari = Yii::$app->request->get('cari');
		$departement_id = Yii::$app->request->get('departement_id');
		$status = Yii::$app->request->get('status');

        $where = ' 0 = 0';
                
        if ($cari != '') {
            $where0 = "and m_pegawai.pegawai_nama ilike '%".$cari."%'";
        } else {
            $where0= 'and 1 = 1';
        }

        if ($departement_id > 0) {
            $where1 = 'and m_departement.departement_id = '.$departement_id;
        } else {
            $where1 = 'and 2 = 2';
        }

        if ($status != 'all') {
            $where2 = 'and m_pegawai.active = '.$status.'';
        } else {
            $where2 = 'and 3 = 3';
        }

        $where = $where." ".$where0." ".$where1." ".$where2;

        $model = \app\models\MPegawai::find()->select("m_pegawai.pegawai_nama, m_pegawai.pegawai_jk, m_departement.departement_nama, m_jabatan.jabatan_nama, m_pegawai.active, m_jabatan.jabatan_id")
                                            ->join("LEFT JOIN", "m_departement", "m_departement.departement_id = m_pegawai.departement_id")
                                            ->join("LEFT JOIN", "m_jabatan", "m_jabatan.jabatan_id = m_pegawai.jabatan_id")
                                            ->where($where)
                                            ->orderBy("m_jabatan.jabatan_id asc, m_pegawai.pegawai_id asc")
                                            ->all();
		$paramprint['judul'] = Yii::t('app', 'Laporan Data Pegawai');
		if($caraprint == 'PRINT'){
			return $this->render('print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->renderPartial('print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->renderPartial('print',['model'=>$model,'paramprint'=>$paramprint, 'cari'=>$cari, 'departement_id'=>$departement_id, 'status'=>$status]);
		}
	}
}
