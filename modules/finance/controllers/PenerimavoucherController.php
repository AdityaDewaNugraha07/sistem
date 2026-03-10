<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PenerimavoucherController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\MPenerimaVoucher::tableName();
			$param['pk']= \app\models\MPenerimaVoucher::primaryKey()[0];
			$param['column'] = ['penerima_voucher_id','kode','nama_penerima','penerima_alamat','phone','keterangan',$param['table'].'.active','nama_perusahaan'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		
		return $this->render('index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MPenerimaVoucher();
			$model->active = true;
			$model->kode = "Auto Generate";
			if( Yii::$app->request->post('MPenerimaVoucher')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
                    $model->kode = \app\components\DeltaGenerator::masterPenerimaVoucher();
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
			$model = \app\models\MPenerimaVoucher::findOne($id);
			return $this->renderAjax('info',['model'=>$model]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MPenerimaVoucher::findOne($id);
			if( Yii::$app->request->post('MPenerimaVoucher')){
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
                        $data['message'] = Yii::t('app', 'Data Master Berhasil Diupdate');
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
			$model = \app\models\MPenerimaVoucher::findOne($id);
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
                            $data['message'] = Yii::t('app', 'Data Master Gagal dihapus');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Master Berhasil Dihapus');
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
    
	public function actionCustomerPrint(){
        $this->layout = '@views/layouts/metronic/print';

        $search = Yii::$app->request->get('search');
        if ($search != "") {
            $andWhere = " and (kode ilike '%".$search."%' or nama_penerima ilike '%".$search."%' or penerima_alamat ilike '%".$search."%' or phone ilike '%".$search."%' or keterangan ilike '%".$search."%' )";
        } else {
            $andWhere = '';
        }
        
        $sql = "select * from m_penerima_voucher where 1=1 ".$andWhere." ";
        $model = Yii::$app->db->createCommand($sql)->queryAll();
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = "Laporan Penerima Voucher";
		if ($caraprint == 'PRINT') {
			return $this->render('print',['model'=>$model,'paramprint'=>$paramprint, 'search'=>$search]);
		} else if($caraprint == 'PDF') {
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('print',['model'=>$model,'paramprint'=>$paramprint, 'search'=>$search]);
		}
	}	
}
