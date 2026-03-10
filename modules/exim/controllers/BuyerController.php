<?php

namespace app\modules\exim\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class BuyerController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
//	Export
	
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-buyer'){
			$param['table'] = \app\models\MCustomer::tableName();
			$param['pk']= \app\models\MCustomer::primaryKey()[0];
			$param['column'] = ['cust_id','cust_kode','cust_an_nama','cust_an_alamat',$param['table'].'.active'];
			$param['where'] = "cust_tipe_penjualan = 'export'";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MCustomer();
            $model->cust_kode = \app\components\DeltaGenerator::kodeBuyer();
			if( Yii::$app->request->post('MCustomer')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model = new \app\models\MCustomer();
                    $model->load(\Yii::$app->request->post());
					$model->cust_tipe_penjualan = 'export';
					$model->cust_tanggal_join = '2018-01-01';
					$model->cust_is_pkp = true;
					$model->cust_max_plafond = '0';
					$model->cust_no_npwp = "-";
					$model->cust_an_nik = "-";
					$model->cust_an_jk = "-";
					$model->cust_an_tgllahir = '2018-01-01';
					$model->cust_an_nohp = "-";
					$model->cust_an_agama = "-";
					$model->active = true;
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
			$model = \app\models\MCustomer::findOne($id);
            $modCustTop = \app\models\MCustTop::find()->where(['active'=>TRUE,'cust_id'=>$model->cust_id])->all();
			return $this->renderAjax('info',['model'=>$model,'modCustTop'=>$modCustTop]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MCustomer::findOne($id);
			if( Yii::$app->request->post('MCustomer')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
                    $model->cust_max_plafond = (string)$model->cust_max_plafond;
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
						$data['message'] = Yii::t('app', 'Data Buyer Berhasil Diupdate');
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
			$model = \app\models\MCustomer::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if($model->delete()){
                        $success_1 = true;
                    }else{
                        $data['message'] = Yii::t('app', 'Data Buyer Gagal dihapus');
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Buyer Berhasil Dihapus');
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
    
    public function actionBuyerPrint(){
        $this->layout = '@views/layouts/metronic/print';

        $search = Yii::$app->request->get('search');
        if ($search != "") {
            $andWhere = " and (cust_kode ilike '%".$search."%' or cust_kode ilike '%".$search."%' or cust_an_nama ilike '%".$search."%' or cust_pr_nama ilike '%".$search."%' or cust_pr_nama ilike '%".$search."%' or cust_an_alamat ilike '%".$search."%' )";
        } else {
            $andWhere = '';
        }
        
        $sql = "select * from m_customer where cust_tipe_penjualan='export' ".$andWhere." order by cust_id desc";
        $model = Yii::$app->db->createCommand($sql)->queryAll();
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = "Laporan Customer";
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
