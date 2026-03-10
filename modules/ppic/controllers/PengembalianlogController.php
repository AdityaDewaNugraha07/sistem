<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use kartik\mpdf\Pdf;
use app\models\HPersediaanLog;
use app\models\MKayu;
use app\models\TLogKeluar;
use app\models\TPengembalianLogDetail;

class PengembalianlogController extends DeltaBaseController
{
    public $defaultAction = 'index';
    public function actionIndex()
    {
        $model = new \app\models\TPengembalianLog();
        $modDetail = new \app\models\TPengembalianLogDetail();
        $model->kode = 'Auto Generate';
        $model->tanggal = date("d/m/Y");
        $modLog = [];

        if (isset($_GET['pengembalian_log_id'])) {
            $model = \app\models\TPengembalianLog::findOne($_GET['pengembalian_log_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modDetails = TPengembalianLogDetail::find()->where(['pengembalian_log_id'=>$_GET['pengembalian_log_id']])->all();
            if($modDetails){
                foreach($modDetails as $a => $modDetail){
                    $modLogKeluar = TLogKeluar::findOne(['no_barcode'=>$modDetail->no_barcode]);
                    $modLog = HPersediaanLog::findOne(['no_barcode'=>$modDetail->no_barcode, 'reff_no'=>$modLogKeluar->reff_no]);
                }
            }
        }

        if (Yii::$app->request->post('TPengembalianLog')) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pengembalian_log
                $success_2 = false; // t_pengembalian_log_detail
                $success_3 = false; // t_approval

                $model->load(\Yii::$app->request->post());

                if(!isset($_GET['edit'])){
                    $model->kode = \app\components\DeltaGenerator::kodeKembaliLog();
                    // $model->status_approve = 'Not Confirmed';
                }
                
                if ($model->validate()) {
                    if ($model->save()) {
                        $success_1 = true;

                        if(isset($_GET['edit'])){ // jika proses edit
                            $success_2 = (\app\models\TPengembalianLogDetail::deleteAll(['pengembalian_log_id'=>$model->pengembalian_log_id, 'status_penerimaan'=>false]))?true:false;
                            $existingBarcodes = \app\models\TPengembalianLogDetail::find()
                                                    ->select('no_barcode')
                                                    ->where(['pengembalian_log_id' => $model->pengembalian_log_id, 'status_penerimaan' => true])
                                                    ->column();
                        }

                        if (isset($_POST['TPengembalianLogDetail'])) {
                            foreach ($_POST['TPengembalianLogDetail'] as $i => $detail) {
                                if (isset($_GET['edit']) && in_array($detail['no_barcode'], $existingBarcodes)) {
                                    continue; // Skip barcode yang sudah diterima
                                }

                                $modDetail = new TPengembalianLogDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->pengembalian_log_id = $model->pengembalian_log_id;
                                $modDetail->status_penerimaan = false; // belum diterima
                                
                                if($modDetail->validate()){
                                    if($modDetail->save()){
                                        $success_2 = true;
                                    }
                                }
                            }
                        }

                        // membuat approval pengembalian
                        $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
                        // eo approval
                    }
                }
                               
                // echo "<pre>1";
                // print_r($success_1);
                // echo "<pre>2";
                // print_r($success_2);
                // print_r($_POST['TPengembalianLogDetail']);
                // exit;
                               
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index', 'success' => 1, 'pengembalian_log_id' => $model->pengembalian_log_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg) ? $errmsg : Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        return $this->render('index', ['model' => $model, 'modDetail'=>$modDetail, 'modLog'=>$modLog]);
    }

    public function saveApproval($model){
		
	}

    public function actionInputManual()
    {
        if (\Yii::$app->request->isAjax) {
            $edit   = Yii::$app->request->get('edit');
            $id     = Yii::$app->request->get('id');
            return $this->renderAjax('_inputManual', ['edit'=>$edit, 'id'=>$id]);
        }
    }

    public function actionInputManuals()
    {
        if (Yii::$app->request->isAjax) {
            $req = $_POST;
            if($req['clause'] === 'no_lap' || $req['clause'] === 'no_barcode') {
                $modDetail = Yii::$app->db->createCommand(" SELECT * FROM h_persediaan_log 
                                                            JOIN t_log_keluar ON t_log_keluar.no_barcode = h_persediaan_log.no_barcode 
                                                                AND t_log_keluar.reff_no = h_persediaan_log.reff_no
															WHERE h_persediaan_log.".trim($req['clause'])." = '".trim($req['keyword'])."'
                                                            AND t_log_keluar.tanggal >= '2025-04-01'::date
                                                            ")->queryOne(); // pembatasan tanggal
                if (count($modDetail) > 0) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modDetail['persediaan_log_id']}\nNo : {$modDetail['no_barcode']}",
						'no_barcode'=> $modDetail['no_barcode']
                    ]);
                }
            }else {
                $modDetails = Yii::$app->db->createCommand("SELECT * FROM h_persediaan_log 
                                                            JOIN t_log_keluar ON t_log_keluar.no_barcode = h_persediaan_log.no_barcode 
                                                                AND t_log_keluar.reff_no = h_persediaan_log.reff_no
															WHERE h_persediaan_log.".trim($req['clause'])." = '".trim($req['keyword'])."'
                                                            AND t_log_keluar.tanggal >= '2025-04-01'::date
                                                            ")->queryAll(); // pembatasan tanggal
                if(count($modDetails) === 1) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modDetails[0]['persediaan_log_id']}\nNo : {$modDetails[0]['no_barcode']}",
                        'no_barcode'=> $modDetails[0]['no_barcode']
                    ]);
                }else {
                    return $this->asJson([
                        'status' => true,
                        'datas' => $modDetails
                    ]);
                }
            }
        }
        return $this->asJson(['status' => false, 'message' => 'Data tidak ditemukan']);
    }

    public function actionShowDetail(){
        $data['status']         = false;
        if(Yii::$app->request->isAjax){
			$no_barcode     	= Yii::$app->request->post('no_barcode');
            $edit     	        = Yii::$app->request->post('edit');
            $id     	        = Yii::$app->request->post('id');
            $data['no_barcode'] = $no_barcode;
            $modKeluar = TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
            $query = " SELECT * FROM h_persediaan_log 
                        JOIN t_log_keluar ON t_log_keluar.no_barcode = h_persediaan_log.no_barcode AND t_log_keluar.reff_no = h_persediaan_log.reff_no
                        WHERE t_log_keluar.tanggal >= '2025-04-01'::date 
                        AND NOT EXISTS (SELECT t_log_keluar.no_barcode FROM t_pemotongan_log_detail_potong WHERE t_log_keluar.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama) 
                        AND t_log_keluar.no_barcode = '".$no_barcode."'";  // pembatasan waktu 
            if(!$edit){
                $query .= " AND NOT EXISTS (SELECT t_log_keluar.no_barcode FROM t_pengembalian_log_detail WHERE t_log_keluar.no_barcode = t_pengembalian_log_detail.no_barcode)";
            } else {
                $query .= " AND (NOT EXISTS (SELECT t_log_keluar.no_barcode FROM t_pengembalian_log_detail WHERE t_log_keluar.no_barcode = t_pengembalian_log_detail.no_barcode)
	                        OR EXISTS (SELECT no_barcode FROM t_pengembalian_log_detail WHERE pengembalian_log_id = $id))";
            }
            $modPersediaan = Yii::$app->db->createCommand($query)->queryAll();
            $modKembali = TPengembalianLogDetail::findOne(['no_barcode'=>$no_barcode]);
            $modKembaliA = TPengembalianLogDetail::findOne(['no_barcode'=>$no_barcode, 'status_penerimaan'=>true]); //sudah diterima
            if (substr($_POST['datas'], 0, 5) == "ID : ") {
                if($modKeluar !== null){
                    if(!$modKembali){
                        if((!empty($modPersediaan)) ){
                            $data['status'] = true;
                            $data['msg']    = "Data ok";
                        } else {
                            $data['status'] = false;
                            $data['msg']    = "Log tidak ditemukan di data log keluar!";
                        }
                    } else {
                        if($modKembaliA){
                            $data['status'] = false;
                            $data['msg']    = "Log sudah diterima kembali sebagai stock!";
                        } else {
                            $data['status'] = false;
                            $data['msg']    = "Log sudah pernah discan pengembalian!";
                        }
                    }
                } else {
                    $data['status'] = false;
                    $data['msg']    = "Data tidak ada di data log keluar";
                }
            } else {
                $data['msg'] = "Invalid QR Code Format -> " . $_POST['datas'];
            }
		} else {
            $data['msg'] = "xxx";
        }
        return $this->asJson($data);
	}

    public function actionReview()
    {
        if (\Yii::$app->request->isAjax) {
            $no_barcode = $_GET['no_barcode'];
            $model = \app\models\TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
            $modLog = HPersediaanLog::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$model->reff_no]);
            $modKayu = MKayu::findOne($modLog->kayu_id);
            return $this->renderAjax('_review', ['modLog' => $modLog, 'modKayu'=>$modKayu]);
        }
    }

    public function actionAddLog(){
        if(Yii::$app->request->isAjax){
            $no_barcode = Yii::$app->request->post('no_barcode');
            $kayu_id = Yii::$app->request->post('kayu_id');
            $alasan = Yii::$app->request->post('alasan');
            $data = [];
            if($no_barcode && $kayu_id && $alasan){
                $modDetail = new TPengembalianLogDetail();
                $data['html'] = $this->renderPartial('_item',['modDetail'=>$modDetail,'no_barcode'=>$no_barcode, 'kayu_id'=>$kayu_id, 'alasan'=>$alasan, 'edit'=>'', 'id'=>'']);
            }
            return $this->asJson($data);
        }
    }

    public function actionDaftarScanned(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPengembalianLog::tableName();
				$param['pk']= $param['table'].".". \app\models\TPengembalianLog::primaryKey()[0];
				$param['column'] = [$param['table'].'.pengembalian_log_id',
                                    $param['table'].'.kode',
                                    $param['table'].'.tanggal',
                                    $param['table'].'.keterangan',
                                    $param['table'].'.status_approve',
									];
                $param['where'] = "cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarScanned');
        }
    }

    public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $edit = Yii::$app->request->post('edit');
            $data = [];
            $data['html'] = '';

            if(!empty($id)){
                $modDetail = TPengembalianLogDetail::find()->where(['pengembalian_log_id'=>$id])->orderBy(['pengembalian_log_detail_id'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    foreach($modDetail as $i => $detail){
                        $no_barcode = $detail['no_barcode'];
                        $kayu_id = $detail['kayu_id'];
                        $alasan = $detail['alasan_pengembalian'];
                        $data['html'] .= $this->renderPartial('_item',['modDetail'=>$detail, 'edit'=>$edit,'no_barcode'=>$no_barcode, 'kayu_id'=>$kayu_id, 'alasan'=>$alasan, 'id'=>$id]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
}
