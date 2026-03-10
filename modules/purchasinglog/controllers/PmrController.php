<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PmrController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TPmr;
        $model->kode = "Auto Generate";
        $model->jenis_log = "";
        $model->tujuan = "LA";
        $model->tanggal = date('d/m/Y');
        if(isset($_GET['pmr_id'])){
            $model = \app\models\TPmr::findOne($_GET['pmr_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_dibutuhkan_awal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_dibutuhkan_awal);
            $model->tanggal_dibutuhkan_akhir = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_dibutuhkan_akhir);
        }
        
        if( Yii::$app->request->post('TPmr')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pmr
                $success_2 = true; // t_pmr_detail
                $success_3 = true; // t_approval
                $model->load(\Yii::$app->request->post());
                if(!isset($_GET['edit'])){
                    $model->kode = \app\components\DeltaGenerator::purchaseMaterialRequest();
                }
                
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        if( (isset($_POST['TPmrDetail'])) && (count($_POST['TPmrDetail'])>0) ){
                            if( (isset($_GET['edit'])) && (isset($_GET['pmr_id']))){
                                // exec ini jika proses edit
                                $modDetail = \app\models\TPmrDetail::find()->where(['pmr_id'=>$_GET['pmr_id']])->all();
                                if(count($modDetail)>0){
                                    \app\models\TPmrDetail::deleteAll(['pmr_id'=>$_GET['pmr_id']]);
                                }
								// exec ini jika proses edit
                            }
                            foreach($_POST['TPmrDetail'] as $i => $details){
                                if(is_numeric($i)){
                                    foreach($details as $ii => $detail){
                                        if((is_array($detail))&&($ii!='total')){
                                            $modDetail = new \app\models\TPmrDetail();
                                            $modDetail->pmr_id = $model->pmr_id;
                                            $modDetail->kayu_id = $details['kayu_id'];
                                            $modDetail->panjang = ($model->jenis_log=="LS" || $model->jenis_log=="LJ")?$ii:"0";
                                            $modDetail->diameter_range = ($model->jenis_log=="LA")?$ii:"-";
                                            $modDetail->qty_m3 = $detail['qty_m3'];
                                            $modDetail->keterangan = $details['keterangan'];
                                            if($modDetail->validate()){
                                                if($modDetail->save()){
                                                    $success_2 &= true;
                                                }else{
                                                    $success_2 = false;
                                                }
                                            }else{
                                                $success_2 = false;
                                                $errmsg = $modDetail->errors;
                                            }
                                        }
                                    }
                                }
                            }
                        }else{
                            $success_2 = false;
                            Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                        }
						
						// START Create Approval
						$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
						if(count($modelApproval)>0){ // edit mode
							if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
								$success_3 = $this->saveApproval($model);
							}
						}else{ // insert mode
							$success_3 = $this->saveApproval($model);
						}
						// END Create Approval
                    }
                }
                // echo "<pre>1";
                // print_r($success_1);
                // echo "<pre>2";
                // print_r($success_2);
                // echo "<pre>3";
                // print_r($success_3);
                // exit;
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Berhasil Diajukan'));
                    return $this->redirect(['index','success'=>1,'pmr_id'=>$model->pmr_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model]);
	}
	
	public function saveApproval($model){
		$success = true;
		if($model->approver_1){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approver_1;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 1;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		if($model->approver_2){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approver_2;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 2;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		if($model->approver_3){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approver_3;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 3;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		if($model->approver_4){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approver_4;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 4;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		if($model->approver_5){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approver_5;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 5;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		return $success;
	}
    
    public function actionSetHeader(){
        if(\Yii::$app->request->isAjax){
            $pmr_id = Yii::$app->request->post('pmr_id');
            $edit = Yii::$app->request->post('edit');
            $jenis_log = Yii::$app->request->post('jenis_log');
            $tujuan = Yii::$app->request->post('tujuan');
            $model = new \app\models\TPmr();
            
            $data['dibuat_oleh'] = Yii::$app->user->identity->pegawai_id;
            $data['dibuat_oleh_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['dibuat_oleh'])->pegawai_nama,['value'=>$data['dibuat_oleh']]);
            
            if(!empty($pmr_id)){
                $modPmr = \app\models\TPmr::findOne($pmr_id);
                $data['dibuat_oleh'] = $modPmr->dibuat_oleh;
                $data['dibuat_oleh_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($modPmr->dibuat_oleh)->pegawai_nama,['value'=>$modPmr->dibuat_oleh]);
            }
            
            $data['approver_1'] = null;
            $data['approver_1_html'] = \yii\bootstrap\Html::tag('option');
            $data['approver_1_label'] = "Approver 1";
            $data['approver_2'] = null;
            $data['approver_2_html'] = \yii\bootstrap\Html::tag('option');
            $data['approver_2_label'] = "Approver 2";
            $data['approver_3'] = null;
            $data['approver_3_html'] = \yii\bootstrap\Html::tag('option');
            $data['approver_3_label'] = "Approver 3";
            $data['approver_4'] = null;
            $data['approver_4_html'] = \yii\bootstrap\Html::tag('option');
            $data['approver_4_label'] = "Approver 4";
            $data['approver_5'] = null;
            $data['approver_5_html'] = \yii\bootstrap\Html::tag('option');
            $data['approver_5_label'] = "Approver 5";
            
            if($jenis_log=="LA"){
                if($tujuan=="INDUSTRI"){
                    $data['approver_1'] = \app\components\Params::DEFAULT_PEGAWAI_ID_ILHAM;
                    $data['approver_1_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_1'])->pegawai_nama,['value'=>$data['approver_1']]);
                    $data['approver_1_label'] = "Approver 1<br><span style='font-size:1.1rem;'><b>Kadiv Opr</b></span>";
                    $data['approver_2'] = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
                    $data['approver_2_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_2'])->pegawai_nama,['value'=>$data['approver_2']]);
                    $data['approver_2_label'] = "Approver 2<br><span style='font-size:1.1rem;'><b>GM Opr</b></span>";
                    $data['approver_3'] = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                    $data['approver_3_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_3'])->pegawai_nama,['value'=>$data['approver_3']]);
                    $data['approver_3_label'] = "Approver 3<br><span style='font-size:1.1rem;'><b>Dirut</b></span>";
                    $data['approver_4'] = \app\components\Params::DEFAULT_PEGAWAI_ID_JENNY_CHANDRA;
                    $data['approver_4_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_4'])->pegawai_nama,['value'=>$data['approver_4']]);
                    $data['approver_4_label'] = "Approver 4<br><span style='font-size:1.1rem;'><b>Owner</b></span>";
                }else if($tujuan=="TRADING"){
                    $data['approver_1'] = \app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $data['approver_1_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_1'])->pegawai_nama,['value'=>$data['approver_1']]);
                    $data['approver_1_label'] = "Approver 1<br><span style='font-size:1.1rem;'><b>Kadiv Mkt</b></span>";
                    $data['approver_2'] = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
                    $data['approver_2_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_2'])->pegawai_nama,['value'=>$data['approver_2']]);
                    $data['approver_2_label'] = "Approver 1<br><span style='font-size:1.1rem;'><b>Kadiv Purchasing Log</b></span>";
                    $data['approver_3'] = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                    $data['approver_3_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_3'])->pegawai_nama,['value'=>$data['approver_3']]);
                    $data['approver_3_label'] = "Approver 2<br><span style='font-size:1.1rem;'><b>Dirut</b></span>";
                    $data['approver_4'] = \app\components\Params::DEFAULT_PEGAWAI_ID_JENNY_CHANDRA;
                    $data['approver_4_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_4'])->pegawai_nama,['value'=>$data['approver_4']]);
                    $data['approver_4_label'] = "Approver 3<br><span style='font-size:1.1rem;'><b>Owner</b></span>";
                }
            }else if($jenis_log=="LS"){
                if($tujuan=="INDUSTRI"){
                    $data['approver_1'] = \app\components\Params::DEFAULT_PEGAWAI_ID_ILHAM;
                    $data['approver_1_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_1'])->pegawai_nama,['value'=>$data['approver_1']]);
                    $data['approver_1_label'] = "Approver 1<br><span style='font-size:1.1rem;'><b>Kadiv Opr</b></span>";
                    $data['approver_2'] = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
                    $data['approver_2_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_2'])->pegawai_nama,['value'=>$data['approver_2']]);
                    $data['approver_2_label'] = "Approver 2<br><span style='font-size:1.1rem;'><b>GM Opr</b></span>";
                }
            }else if($jenis_log=="LJ"){
                if($tujuan=="INDUSTRI"){
                    $data['approver_1'] = \app\components\Params::DEFAULT_PEGAWAI_ID_ILHAM;
                    $data['approver_1_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_1'])->pegawai_nama,['value'=>$data['approver_1']]);
                    $data['approver_1_label'] = "Approver 1<br><span style='font-size:1.1rem;'><b>Kadiv Opr</b></span>";
                    $data['approver_2'] = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
                    $data['approver_2_html'] = \yii\bootstrap\Html::tag('option', \app\models\MPegawai::findOne($data['approver_2'])->pegawai_nama,['value'=>$data['approver_2']]);
                    $data['approver_2_label'] = "Approver 2<br><span style='font-size:1.1rem;'><b>GM Opr</b></span>";
                }
            }
            
            return $this->asJson($data);
        }
    }
    
    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TPmrDetail();
            $jenis_log = Yii::$app->request->post('jenis_log');
            $data['html'] = "";
            if($jenis_log=="LA"){
                $data['html'] .= $this->renderPartial('_item',['model'=>$model,'jenis_log'=>$jenis_log]);
            }else if($jenis_log=="LS"){
                $model->kayu_id = 29; // RC - Sengon
                $data['html'] .= $this->renderPartial('_item',['model'=>$model,'jenis_log'=>$jenis_log]);
            }else if($jenis_log=="LJ"){
                $model->kayu_id = 24; // RC - Jabon
                $data['html'] .= $this->renderPartial('_item',['model'=>$model,'jenis_log'=>$jenis_log]);
            }
            return $this->asJson($data);
        }
    }
    
    function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $pmr_id = Yii::$app->request->post('pmr_id');
            $edit = Yii::$app->request->post('edit');
            $jenis_log = Yii::$app->request->post('jenis_log');
			$modDetail = []; $data = [];
            if(!empty($pmr_id)){
                $modDetail = \app\models\TPmrDetail::find()
								->select("max(pmr_detail_id) as xxx, pmr_id, kayu_id, keterangan")
                                ->where(['pmr_id'=>$pmr_id])
                                ->groupBy("pmr_id, kayu_id, keterangan")
                                ->orderBy("xxx asc")
                                ->all();
            }
            $data['html'] = '';
            if(count($modDetail)>0){
                $x = 1;
                foreach($modDetail as $i => $detail){
                    $data['html'] .= $this->renderPartial('_item',['model'=>$detail,'i'=>$i,'x'=>$x,'edit'=>$edit, 'jenis_log'=>$jenis_log]);
                    $x++;
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
            $pick = \Yii::$app->request->get('pick');
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPmr::tableName();
				$param['pk']= $param['table'].".". \app\models\TPmr::primaryKey()[0];
				$param['column'] = [$param['table'].'.pmr_id',
									$param['table'].'.kode',
									$param['table'].'.tanggal',
									$param['table'].'.jenis_log',
									$param['table'].'.tujuan',
									"CONCAT( TO_CHAR(tanggal_dibutuhkan_awal :: DATE, 'dd/mm/yyyy'),' sd ',TO_CHAR(tanggal_dibutuhkan_akhir :: DATE, 'dd/mm/yyyy')) as dibutuhkan",
									'(SELECT SUM(qty_m3) FROM t_pmr_detail WHERE t_pmr_detail.pmr_id = t_pmr.pmr_id) AS total_m3',
									'm_pegawai.pegawai_nama AS dibuat_oleh',
									'pegawai1.pegawai_nama AS approver_1',
									'pegawai2.pegawai_nama AS approver_2',
									'pegawai3.pegawai_nama AS approver_3',
									'pegawai4.pegawai_nama AS approver_4',
                                    '(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_1) AS approver_1_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_2) AS approver_2_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_3) AS approver_3_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_4) AS approver_4_status',
                                                                        $param['table'].'.status'
									];
				$param['join']= ['
								JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.dibuat_oleh 
								JOIN m_pegawai AS pegawai1 ON pegawai1.pegawai_id = '.$param['table'].'.approver_1 
								JOIN m_pegawai AS pegawai2 ON pegawai2.pegawai_id = '.$param['table'].'.approver_2 
								LEFT JOIN m_pegawai AS pegawai3 ON pegawai3.pegawai_id = '.$param['table'].'.approver_3 
								LEFT JOIN m_pegawai AS pegawai4 ON pegawai4.pegawai_id = '.$param['table'].'.approver_4
								'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL ";
                if(\Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER){
                    $param['where'] .= "AND m_pegawai.departement_id = ".\Yii::$app->user->identity->pegawai->departement_id;
                }
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave',['pick'=>$pick]);
        }
    }
    
    public function actionDetailPermintaan(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TPmr::findOne($_GET['id']);
			$modDetail = \app\models\TPmrDetail::find()->where(['pmr_id'=>$_GET['id']])->all();
			return $this->renderAjax('detailPermintaan',['model'=>$model,'modDetail'=>$modDetail]);
        }
	}
    
    public function actionSetDetail(){
        if(\Yii::$app->request->isAjax){
            $jenis_log = Yii::$app->request->post('jenis_log');
            $success = Yii::$app->request->post('success');
            $pmr_id = Yii::$app->request->post('pmr_id');
            $data['html'] = "";
            $data['html'] .= $this->renderPartial('_table',['jenis_log'=>$jenis_log, 'success'=>$success, 'pmr_id'=>$pmr_id]);
            return $this->asJson($data);
        }
    }

    public function actionGetKayu() {
		if(\Yii::$app->request->isAjax){
            $data = [];
            $data['html'] = '';
            $group_kayu = Yii::$app->request->post('group_kayu');
            $baris = Yii::$app->request->post('baris');
            $model = \app\models\MKayu::find()->select("kayu_id, kayu_nama")->where(['group_kayu' => $group_kayu])->orderBy('kayu_nama ASC')->all();
            return $this->renderAjax('_namaKayu',['group_kayu'=>$group_kayu, 'baris'=>$baris, 'model'=>$model]);
        }        
    }
    
}
