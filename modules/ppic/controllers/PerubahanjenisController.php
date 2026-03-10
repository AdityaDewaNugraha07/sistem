<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PerubahanjenisController extends DeltaBaseController
{
    public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TLogRubahjenis();
        $model->kode = 'Auto Generate';
        $model->tanggal = date("d/m/Y");
        $model->peruntukan = 'Industri';
        $modelApproval = [];

        if(isset($_GET['log_rubahjenis_id'])){
            $model = \app\models\TLogRubahjenis::findOne($_GET['log_rubahjenis_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $details = \yii\helpers\Json::decode($model->datadetail, true);
            $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
        }

        if( Yii::$app->request->post('TLogRubahjenis')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; //t_log_rubahjenis
                $success_2 = false; //t_approval

                $model->load(\Yii::$app->request->post());
                if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeRubahJenis();
				}
                $model->status_approve = 'Not Confirmed';
                $model->approver1 = \app\components\Params::DEFAULT_PEGAWAI_ID_KADEP_PPIC;
                if($_POST['TLogRubahjenis']['peruntukan'] == 'Industri'){
                    $model->approver2 = \app\components\Params::DEFAULT_PEGAWAI_ID_KADIV_OPERASIONAL;
                } else {
                    $model->approver2 = \app\components\Params::DEFAULT_PEGAWAI_ID_KADIV_MKT;
                }

                $datadetail = [];
                foreach($_POST['TLogRubahjenis'] as $i => $post){
                    if(is_array($post)){
                        unset($post['kayu_nama']);
                        unset($post['barcode_lap']);
                        $datadetail[] = $post;
                    }
                }
                $model->datadetail = \yii\helpers\Json::encode($datadetail);
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                    }
                }

                // START approval
                $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
                if(count($modelApproval)>0){ // edit mode
					if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
						$success_2 = $this->saveApproval($model);
					}
				}else{ // insert mode
					$success_2 = $this->saveApproval($model);
				}
                // EO approval

                // print_r($success_1); print_r($success_2); 
                // exit;
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'log_rubahjenis_id'=>$model->log_rubahjenis_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }

        return $this->render('index',['model'=>$model, 'modelApproval'=>$modelApproval]);
    }

    public function actionAddItem()
    {
        if (\Yii::$app->request->isAjax) {
            $model = new \app\models\TLogRubahjenis();
            $peruntukan = Yii::$app->request->get('peruntukan');
            return $this->renderAjax('_addItem', ['model'=>$model, 'peruntukan'=>$peruntukan]);
        }
    }

    public function saveApproval($model){
		$success = false;
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->approver1;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = date("d/m/Y");
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success = $modelApproval->createApproval();
		if($model->approver2){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approver2;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = date("d/m/Y");
			$modelApproval->level = 2;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		return $success;
	}

    public function actionAddItems()
    {
        if (Yii::$app->request->isAjax) {
            $req = $_POST;
            $modDetail = \app\models\TTerimaLogalamDetail::findOne([trim($req['clause']) => trim($req['keyword'])]);
            $post_peruntukan = trim($req['peruntukan']);
            if ($modDetail) {
                return $this->asJson([
                    'status' => true,
                    'datas' => "ID : $modDetail->terima_logalam_detail_id\nNo : $modDetail->no_barcode\nPeruntukan : $post_peruntukan",
                ]);
            }
        }
        return $this->asJson(['status' => false, 'message' => 'Data tidak ditemukan']);
    }

    public function actionShowDetail()
    {
        if (\Yii::$app->request->isAjax) {
            $data = [];
            $data['status'] = false;
            $data['msg'] = "";

            if (substr($_POST['datas'], 0, 5) == "ID : ") {
                $data = explode("\n", $_POST['datas']);
                $baris_id = $data[0];
                $baris_kode = $data[1];
                $baris_peruntukan = $data[2];
                $terima_logalam_detail = explode(" : ", $baris_id);
                $terima_logalam_detail_id = $terima_logalam_detail[1];
                $p_peruntukan = explode(" : ", $baris_peruntukan);
                $post_peruntukan = $p_peruntukan[1];

                $sql_terima_logalam_id = "select terima_logalam_id from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "";
                $terima_logalam_id = Yii::$app->db->createCommand($sql_terima_logalam_id)->queryScalar();

                $sql_peruntukan = "select peruntukan from t_terima_logalam where terima_logalam_id = " . $terima_logalam_id . "";
                $peruntukan = Yii::$app->db->createCommand($sql_peruntukan)->queryScalar();
                $data['peruntukan'] = $peruntukan;

                $kode = explode(" : ", $baris_kode);
                $no_barcode = $kode[1];
                $query = "  SELECT h_persediaan_log.no_barcode, h_persediaan_log.no_lap
                            FROM h_persediaan_log
                            WHERE h_persediaan_log.no_grade <> '-' AND no_barcode = '$no_barcode'
                            GROUP BY h_persediaan_log.no_barcode, h_persediaan_log.no_lap
                            HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0 
                         ";
                $stock = Yii::$app->db->createCommand($query)->queryScalar();
                if($stock){
                    $data['status'] = true;
					$data['msg']    = "Data ok";
                } else {
                    $data['msg'] = "Log tidak tersedia di stock";
                }
            } else {
                $data['msg'] = "Invalid QR Code Format -> " . $_POST['datas'];
            }
        }
        return $this->asJson($data);
    }

    public function actionReview()
    {
        if (\Yii::$app->request->isAjax) {
            $no_barcode = $_GET['no_barcode'];
            $modPersediaan = \app\models\HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
            $modKayu = \app\models\MKayu::findOne($modPersediaan->kayu_id);
            return $this->renderAjax('_review', ['model' => $modPersediaan, 'modKayu'=>$modKayu]);
        }
    }

    public function actionSaveItem(){
        if(\Yii::$app->request->isAjax){
            $no_barcode = Yii::$app->request->post('no_barcode');
            $kayu_id = Yii::$app->request->post('kayu_id');
            $model = new \app\models\TLogRubahjenis();
            $data['item'] = $this->renderPartial('_item',['model'=>$model, 'no_barcode'=>$no_barcode, 'kayu_id'=>$kayu_id]);
            $data['no_barcode'] = $no_barcode;
            return $this->asJson($data);
        }
    }

    function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
            $model = \app\models\TLogRubahjenis::findOne($id);
            $datadetails = \yii\helpers\Json::decode($model->datadetail, true);
            $data['html'] = '';
            foreach($datadetails as $detail){
                $model->kayu_id_new = $detail['kayu_id_new'];
				$data['html'] .= $this->renderPartial('_item',['model'=>$model, 'no_barcode'=>$detail['no_barcode'], 'kayu_id'=>$detail['kayu_id_old'],'edit'=>$edit]);
            }
            return $this->asJson($data);
        }
    }

    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TLogRubahjenis::tableName();
				$param['pk']= $param['table'].".". \app\models\TLogRubahjenis::primaryKey()[0];
				$param['column'] = [$param['table'].'.log_rubahjenis_id',				//0
									$param['table'].'.kode',							//1
									$param['table'].'.tanggal', 						//2
									$param['table'].'.peruntukan',  					//3
                                    $param['table'].'.status_approve',  				//4
                                    $param['table'].'.keterangan',	        			//5
									];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

    public function actionCancelTransaksi($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TLogRubahjenis::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_log_rubahjenis
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $model->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
                            if($model->updateAttributes(['cancel_transaksi_id'=>$modCancel->cancel_transaksi_id, 'status_approve'=>$modCancel->status])){
								$success_2 = TRUE;
                                $modApproval = \app\models\TApproval::findAll(['reff_no'=>$model->kode]);
                                foreach($modApproval as $ap => $approval){
                                    $approval->updateAttributes(['status'=>$modCancel->status]);
                                }
							}else{
								$success_2 = FALSE;
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel); 
                    }
					
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Pengajuan Berhasil di Batalkan');
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
			
			return $this->renderAjax('cancelTransaksi',['model'=>$model,'modCancel'=>$modCancel]);
		}
	}

    public function actionPrintPengajuan()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = \app\models\TLogRubahjenis::findOne($_GET['id']);
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'Pengajuan Perubahan Jenis Kayu');
        if ($caraprint == 'PRINT') {
            return $this->render('print', ['model' => $model, 'paramprint' => $paramprint]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('print', ['model' => $model, 'paramprint' => $paramprint]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('print', ['model' => $model, 'paramprint' => $paramprint]);
        }
    }
}
?>