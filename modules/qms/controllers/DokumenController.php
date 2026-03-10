<?php

namespace app\modules\qms\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\TDokumenRevisi;
use app\components\SSP;
use yii\helpers\Json;

class DokumenController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\MDokumen::tableName();
			$param['pk']= \app\models\MDokumen::primaryKey()[0];
			$param['column'] = ['dokumen_id',
                                'nomor_dokumen',
                                'jenis_dokumen',
                                'kategori_dokumen',
                                'nama_dokumen',
                                'active'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		
		return $this->render('index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MDokumen();
            $modDokRevisi = new \app\models\TDokumenRevisi();
            $model->kode1 = 'CWM';
            $model->kode4 = '00';
			$model->active = true;
            $modDokRevisi->tanggal_berlaku = date('d/m/Y');
			if( Yii::$app->request->post('MDokumen')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // m_dokumen
                    $success_2 = false; //t_dokumen_revisi
                    $model->load(\Yii::$app->request->post());
                    $kode1 = isset($_POST['MDokumen']['kode1'])?$_POST['MDokumen']['kode1']:"";
                    $kode2 = isset($_POST['MDokumen']['kode2'])?$_POST['MDokumen']['kode2']:"";
                    $kode3 = isset($_POST['MDokumen']['kode3'])?$_POST['MDokumen']['kode3']:"";
                    $kode4 = isset($_POST['MDokumen']['kode4'])?$_POST['MDokumen']['kode4']:"";
                    $model->nomor_dokumen = $kode1 . '-' . $kode2 . '-' . $kode3 . '-' . $kode4;
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;

                            // simpan di t_dokumen_revisi
                            $modDokRevisi = new TDokumenRevisi();
                            $modDokRevisi->dokumen_id = $model->dokumen_id;
                            $modDokRevisi->nama_dokumen = $model->nama_dokumen;
                            $modDokRevisi->revisi_ke = 0;
                            if($modDokRevisi->validate()){
                                if($modDokRevisi->save()){
                                    $success_2 = true;
                                }
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
			return $this->renderAjax('create',['model'=>$model, 'modDokRevisi'=>$modDokRevisi]);
		}
	}

    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MDokumen::findOne($id);
            $modDokRevisi = \app\models\TDokumenRevisi::findOne(['dokumen_id'=>$model->dokumen_id, 'revisi_ke'=>0]);
			return $this->renderAjax('info',['model'=>$model, 'modDokRevisi'=>$modDokRevisi]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MDokumen::findOne($id);
            $kode = explode("-", $model->nomor_dokumen);
            $model->kode1 = $kode[0];
            $model->kode2 = $kode[1];
            $model->kode3 = $kode[2];
            $model->kode4 = $kode[3];
            $modDokRevisi = \app\models\TDokumenRevisi::findOne(['dokumen_id'=>$model->dokumen_id, 'revisi_ke'=>0]);
            $modDokRevisi->tanggal_berlaku = \app\components\DeltaFormatter::formatDateTimeForUser2($modDokRevisi->tanggal_berlaku);
			if( Yii::$app->request->post('MDokumen')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
                    $model->load(\Yii::$app->request->post());
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;

                            $modDokRevisi->load(Yii::$app->request->post());
                            if($modDokRevisi->validate()){
                                if($modDokRevisi->save()){
                                    $success_2 = true;
                                }
                            }
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1 & $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data Dokumen Berhasil Diupdate');
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
			return $this->renderAjax('edit',['model'=>$model, 'modDokRevisi'=>$modDokRevisi]);
		}
	}
	
	/**public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MDokumen::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                        if($model->delete()){
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data Dokumen Gagal dihapus');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Dokumen Berhasil Dihapus');
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
    }*/
    
    public function actionDokumenPrint(){
        $this->layout = '@views/layouts/metronic/print';
        
        $search = Yii::$app->request->get('search');
        if ($search != "") {
            $andWhere = "(nomor_dokumen ilike '%".$search."%' or jenis_dokumen ilike '%".$search."%' or kategori_dokumen ilike '%".$search."%'  or nama_dokumen ilike '%".$search."%')";
        } else {
            $andWhere = '';
        }

        $query = "SELECT * FROM m_dokumen $andWhere ORDER BY dokumen_id DESC";
        $model = Yii::$app->db->createCommand($query)->queryAll();
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = "Laporan Master Dokumen";
		if ($caraprint == 'PRINT') {
			return $this->render('print',['model'=>$model,'paramprint'=>$paramprint]);
		} else if($caraprint == 'PDF') {
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('print',['model'=>$model,'paramprint'=>$paramprint, 'search'=>$search]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('print',['model'=>$model,'paramprint'=>$paramprint, 'search'=>$search]);
		}
    }	

    public function actionPick(){
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->get('dt')=='table-dokumen'){
                $param['table'] = \app\models\MDokumen::tableName();
                $param['pk']= $param['table'].".". \app\models\MDokumen::primaryKey()[0];
                $param['column'] = [$param['table'].'.dokumen_id','nomor_dokumen','nama_dokumen','jenis_dokumen','kategori_dokumen'];
                $param['where'] = "active IS TRUE";
                return Json::encode(SSP::complex( $param ));
            }
            return $this->renderAjax('pick');
        }
    }

    public function actionMasterOnModal(){
		if(Yii::$app->request->isAjax){
			if(Yii::$app->request->get('dt')=='table-dokumen'){
				$param['table'] = \app\models\MDokumen::tableName();
				$param['pk']= $param['table'].".". \app\models\MDokumen::primaryKey()[0];
				$param['column'] = [$param['table'].'.dokumen_id','nomor_dokumen','nama_dokumen','jenis_dokumen','kategori_dokumen'];
				$param['where'] = "active IS TRUE";
				return Json::encode(SSP::complex( $param ));
			}
			return $this->renderAjax('masterOnTable');
		}
	}
}
