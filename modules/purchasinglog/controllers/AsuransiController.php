<?php

namespace app\modules\purchasinglog\controllers;

use app\components\Params;
use Yii;
use app\controllers\DeltaBaseController;

class AsuransiController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TAsuransi();
        $model->kepada = "PT. ACA";
        $model->lampiran = "1 (satu) Bandel";
        $modAsuransiDetail = new \app\models\TAsuransiDetail();
        $model->tanggal = date("Y-m-d");
        $model->tanggal_muat = date("d/m/Y");
        $model->tanggal_berangkat = date("d/m/Y");
        $model->freight = 0;
        $model->rate = 0;
        $model->by_gmpurch = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
        $model->by_dirut = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
        $disabled = false;
        $statusEdit = false;
        $model->discount = 0;

        if(isset($_GET['asuransi_id'])){
            $model = \app\models\TAsuransi::findOne($_GET['asuransi_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_muat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_muat);
            $model->tanggal_berangkat = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_berangkat);
            $disabled = true;
            $statusEdit = true;
            if (isset($_GET['edit']) && $_GET['edit'] == 1) {
                $model = \app\models\TAsuransi::findOne($_GET['asuransi_id']);
                $modAsuransiDetail = \app\models\TAsuransiDetail::findAll(['asuransi_id'=>$model->asuransi_id]);
                $disabled = false;
                $statusEdit = true;
            } else {
                $model = \app\models\TAsuransi::findOne($_GET['asuransi_id']);
                $modAsuransiDetail = \app\models\TAsuransiDetail::findAll(['asuransi_id'=>$model->asuransi_id]);
                $disabled = true;
                $statusEdit = false;
            }
        }

        if( Yii::$app->request->post('TAsuransi')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_0 = false; // cek
                $success_1 = false; // t_asuransi
                $success_2 = true;  // t_asuransi_detail
                $success_3 = true;  // t_approval
                $model->load(\Yii::$app->request->post());
                $model->status_approval = 'Not Confirmed';
                
                $model->lumpsump = isset($_POST['lumpsump']) && $_POST['lumpsump'] == 'on' ? true : false;

                if(!isset($_GET['edit'])){
                        $model->kode = \app\components\DeltaGenerator::kodeAsuransi();
                }

                $cek = \app\models\TAsuransi::findOne(['kode'=>$model->kode]);
                if (!empty($cek)) {
                    $status_approval = $cek->status_approval;
                    if ($status_approval == "Not Confirmed") {
                        $success_0 = true;
                    } else {
                        $success_0 = false;
                    }
                } else {
                    $success_0 = true;
                }

                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        if((isset($_GET['edit'])) && (isset($_GET['asuransi_id']))){
                                $modAsuransiDetail = \app\models\TAsuransiDetail::find()->where(['asuransi_id'=>$_GET['asuransi_id']])->all();
                                if(count($modAsuransiDetail)>0){
                                        \app\models\TAsuransiDetail::deleteAll(['asuransi_id'=>$_GET['asuransi_id']]);
                                }
                        }
                        $sql_delete = "delete from t_asuransi_detail where asuransi_id = ".$model->asuransi_id;
                        Yii::$app->db->createCommand($sql_delete)->execute();
                        $total = 0;
                        $total_kubikasi = 0;
                        foreach($_POST['TAsuransiDetail'] as $i => $details){ 
                            $Details = "";
                            $Scount = count($details['jenis']);
                            $No = 1;

                            if (is_array($details['jenis']) || is_object($details['jenis'])) {
                                foreach ($details['jenis'] as $u => $Nok) {
                                    if ($Scount == $No) {
                                        $Details .= $Nok."";
                                    } else {
                                        $Details .= $Nok.", ";
                                    }
                                    $No++;
                                }
                            }
                            $tipess = array($Details, "DR", "PSDH");
                            if($statusEdit == true){
                                if (is_array($details['jenis']) || is_object($details['jenis'])) {
                                        $total += $details['harga'] * $details['kubikasi'];                                        
                                        $total_kubikasi += ($details['tipe'] <> 'DR' && $details['tipe'] <> 'PSDH') ? $details['kubikasi'] : 0;   
                                        if ($details['jenis'] != "" && !empty($details['jenis']) ) {
                                            $modAsuransiDetail = new \app\models\TAsuransiDetail();
                                            $modAsuransiDetail->asuransi_id = $model->asuransi_id;
                                            $modAsuransiDetail->jenis = $Details;
                                            $modAsuransiDetail->tipe = $details['tipe'];
                                            $modAsuransiDetail->kubikasi = $details['kubikasi'] * 1;
                                            $modAsuransiDetail->harga = $details['harga'] * 1;
                                            $modAsuransiDetail->total = $details['harga'] * $details['kubikasi'];                              
                                        }    
                                        if($modAsuransiDetail->validate()){
                                            if($modAsuransiDetail->save()){
                                                $success_2 &= true;
                                            }else{
                                                $success_2 = false;
                                            }
                                        } else {
                                            $success_2 = false;
                                            $errmsg = $modAsuransiDetail->errors;
                                        }    
                                }
                            }elseif($statusEdit == false){
                                if (is_array($details['jenis']) || is_object($details['jenis'])) {
                                    foreach ($tipess as $tipes => $tipe) {
                                        $total += $details['harga'] * $details['kubikasi'];
                                        $total_kubikasi += ($tipe == $Details) ? $details['kubikasi'] : 0; 
                                        // $total_kubikasi += $details['kubikasi'];                                       
                                        
                                        if ($details['jenis'] != "" && !empty($details['jenis']) ) {
                                            $modAsuransiDetail = new \app\models\TAsuransiDetail();
                                            $modAsuransiDetail->asuransi_id = $model->asuransi_id;
                                            $modAsuransiDetail->jenis = $Details;
                                            $modAsuransiDetail->tipe = $tipe;
                                            $modAsuransiDetail->kubikasi = $details['kubikasi'] * 1;
                                            if ($tipe == $Details)  {
                                                $modAsuransiDetail->harga = $details['harga'] * 1;
                                                $modAsuransiDetail->total = $details['harga'] * $details['kubikasi'];
                                            } else if ($tipe == "DR") {                                            
                                                if($statusEdit == true){
                                                    $modAsuransiDetail->harga = $details['harga'] * 1;
                                                    $modAsuransiDetail->total = $details['harga'] * $details['kubikasi'];
                                                }else{
                                                    $modAsuransiDetail->harga = $details['harga_dr'];
                                                    $modAsuransiDetail->total = $details['harga_dr'] * $details['kubikasi'];
                                                }
                                            } else if ($tipe == "PSDH") {
                                                if($statusEdit == true){
                                                    $modAsuransiDetail->harga = $details['harga'] * 1;
                                                    $modAsuransiDetail->total = $details['harga'] * $details['kubikasi'];
                                                }else{
                                                    $modAsuransiDetail->harga = $details['harga_psdh'];
                                                    $modAsuransiDetail->total = $details['harga_psdh'] * $details['kubikasi'];
                                                }
                                            }                                  
                                        }
    
                                        if($modAsuransiDetail->validate()){
                                            if($modAsuransiDetail->save()){
                                                $success_2 &= true;
                                            }else{
                                                $success_2 = false;
                                            }
                                        } else {
                                            $success_2 = false;
                                            $errmsg = $modAsuransiDetail->errors;
                                        }
                                    }    
                                }
                            }                            
                        }                      

                        $total = $total;
                        $total_kubikasi = $total_kubikasi;
                        $freight_kubikasi = $model->freight * $total_kubikasi;
                        $jumlah = $total + ($model->freight * $total_kubikasi);
                        $ppn = (!empty($_POST['TAsuransi']['ppn'])) ? $_POST['TAsuransi']['ppn'] : 0;
                        $grandtotal = $jumlah + $ppn;
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

                $model->total               = $total;
                $model->freight_kubikasi    = $freight_kubikasi;
                $model->jumlah              = $jumlah;
                $model->ppn                 = $ppn;
                $model->grandtotal          = $grandtotal;
                $model->pembulatan          = 0;
                if ($success_0 && $success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['view','success'=>1,'asuransi_id'=>$model->asuransi_id]);
                } else {
                    $transaction->rollback();
                    $errmsg = "";
                    if ($success_0 == false) {
                        $errmsg .= "Data sudah diapprove/direject";
                    } else if ($success_1 == false) {
                        $errmsg .= "Gagal menyimpan asuransi";
                    } else if ($success_2 == false) {
                        $errmsg .= "Gagal menyimpan rincian asuransi";
                    } else if ($success_3 == false) {
                        $errmsg .= "Gagal menyimpan approval";
                    } else {
                        $errmsg .= "Gagal proses";
                    }
                    Yii::$app->session->setFlash('error', $errmsg);
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        return $this->render('index',['model'=>$model, 'modAsuransiDetail'=>$modAsuransiDetail, 'disabled'=>$disabled]);
	}
	
	public function saveApproval($model){
            $success = true;
            if($model->by_gmpurch){	
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->by_gmpurch;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();
            }
            if($model->by_dirut){
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->by_dirut;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 2;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();		
            }
            return $success;
	}
	
    public function actionView() {
        $asuransi_id = $_GET['asuransi_id'];
        $model = \app\models\TAsuransi::findOne($asuransi_id);
        // $modAsuransiDetail = \app\models\TAsuransiDetail::findAll(['asuransi_id' => $asuransi_id]);
        $modAsuransiDetail = \app\models\TAsuransiDetail::find()->where(['asuransi_id'=>$asuransi_id])->orderBy(['asuransi_detail_id'=>SORT_ASC])->all();
        return $this->render('view',['model'=>$model, 'modAsuransiDetail'=>$modAsuransiDetail, 'disabled'=>true]);
    }

    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TAsuransi::tableName();
                $gmpurch = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
                $dirut = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
				$param['pk']= $param['table'].".". \app\models\TAsuransi::primaryKey()[0];
				$param['column'] = [$param['table'].'.asuransi_id',
                                                    $param['table'].'.kode',
                                                    $param['table'].'.tanggal',
                                                    $param['table'].'.kepada',
                                                    $param['table'].'.lampiran',
                                                    $param['table'].'.tanggal_muat',
                                                    $param['table'].'.tanggal_berangkat',
                                                    $param['table'].'.dop',
                                                    $param['table'].'.rute',
                                                    $param['table'].'.nama_kapal',
                                                    $param['table'].'.freight',
                                                    $param['table'].'.rate',
                                                    'gmpurch.status as by_gmpurch_status', 
                                                    'dirut.status as by_dirut_status',
                                                    $param['table'].'.discount',
                                                    // '(SELECT status FROM t_approval WHERE reff_no = t_asuransi.kode AND  assigned_to = t_asuransi.by_gmpurch group by 1) AS by_gmpurch_status',
                                                    // '(SELECT status FROM t_approval WHERE reff_no = t_asuransi.kode AND  assigned_to = t_asuransi.by_dirut group by 1) AS by_dirut_status',
                                                    ];
                $param['join'] = [" JOIN t_approval AS gmpurch ON gmpurch.reff_no = t_asuransi.kode AND gmpurch.assigned_to = $gmpurch 
                                    JOIN t_approval AS dirut ON dirut.reff_no = t_asuransi.kode AND dirut.assigned_to = $dirut"];
                                //  [" LEFT JOIN LATERAL (SELECT reason->>'status' AS status FROM jsonb_array_elements(approve_reason::jsonb) AS reason
                                //         WHERE (reason->>'by')::int = $gmpurch) AS status_gmpurch ON true
                                //     LEFT JOIN LATERAL (SELECT reason->>'status' AS status FROM jsonb_array_elements(approve_reason::jsonb) AS reason
                                //         WHERE (reason->>'by')::int = $dirut) AS status_dirut ON true"];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
    public function actionPrint(){
		$this->layout = '@views/layouts/metronic/print';
		if(!empty($_GET['id'])){
			$modAsuransi = \app\models\TAsuransi::findOne($_GET['id']);
			$caraprint = Yii::$app->request->get('caraprint');
			$paramprint['judul2'] = "Pengajuan Asuransi";

			if ($caraprint == 'PRINT') {
				return $this->render('print',['modAsuransi'=>$modAsuransi,'paramprint'=>$paramprint]);
			} else if($caraprint == 'PDF') {
				$pdf = Yii::$app->pdf;
				$pdf->options = ['title' => $paramprint['judul']];
				$pdf->filename = $paramprint['judul'].'.pdf';
				$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
				$pdf->content = $this->render('print',['modAsuransi'=>$modAsuransi,'paramprint'=>$paramprint]);
				return $pdf->render();
			} else {
				return $this->render('print',['modAsuransi'=>$modAsuransi,'paramprint'=>$paramprint]);
			}
		}
	}
    
    public function actionDeletePengajuan($id){
        if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
            $model = \app\models\TAsuransi::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; //t_asuransi
                    $success_2 = false; //t_asuransi_detail
                    $success_3 = false; //t_approval
                    
                    if(\app\models\TApproval::deleteAll("reff_no = '".$model->kode."'")){
                            $success_3 = true;
                    }else{
                            $success_3 = false;
                    }
                            
                    if(\app\models\TAsuransiDetail::deleteAll("asuransi_id = ".$id)){
                            $success_2 = true;
                    }else{
                            $success_2 = false;
                    }
                            
                    if($model->delete()){
                        $success_1 = true;
                    }else{
                        $data['message'] = Yii::t('app', 'Data Gagal dihapus');
                    }
                    
                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
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
                return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid,'actionname'=>'deletePengajuan']);
        }
    }

    public function actionTambahBaris(){
        if(\Yii::$app->request->isAjax){
            $form = \Yii::$app->request->post('form');
            $data[] = "";

            $asuransi_id = 0;

            $model = new \app\models\TAsuransi();
            $modDetail = new \app\models\TAsuransiDetail();
            $last_tr = [];

            parse_str(\Yii::$app->request->post('last_tr'),$last_tr);
            if(!empty($last_tr)){
                foreach($last_tr['TAsuransiDetail'] as $qwe){
                    $last_tr = $qwe;
                }
            }

            //$data['html'] = $this->renderPartial('_tambahBaris',['form'=>$form, 'asuransi_id'=>$asuransi_id, 'model'=>$model, 'modDetail'=>$modDetail, 'last_tr'=>$last_tr, 'disabled'=>false]);
            $data['html'] = "".
                "<tr>
                    <td>
                        <input type=\"hidden\" id=\"no_urut\" name=\"no_urut\">
                        ".\yii\helpers\Html::activeDropDownList($modDetail, '[ii]jenis', \app\models\MKayu::getOptionListN(),['class'=>'form-control select2','multiple'=>'multiple','style'=>'width:100%;'])."
                    </td>
                    <td><input type=\"text\" id=\"tasuransidetail-ii-harga\" class=\"form-control float text-right\" name=\"TAsuransiDetail[ii][harga]\" style=\"width:100%; padding: 2px; height:25px;\" onblur=\"itungTotalDul();\"></td>
                    <td><input type=\"text\" id=\"TAsuransiDetail_6_kubikasi\" class=\"form-control float text-right\" name=\"TAsuransiDetail[6][kubikasi]\" style=\"width:100%; padding: 2px; height:25px;\" onblur=\"itungTotalDul();\"></td>
                    <td><input type=\"text\" id=\"TAsuransiDetail_6_total\" class=\"form-control float text-right\" name=\"TAsuransiDetail[6][total]\" style=\"width:100%; padding: 2px; height:25px;\" onblur=\"itungTotalDul();\" readonly></td>
                    <td><a class=\"btn btn-xs red\" onclick=\"cancelItemThis(this);\"><i class=\"fa fa-remove\"></i></a></td>
                </tr>";

            return $this->asJson($data);
            
        }
    }

    function actionDetailAsuransi(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$modAsuransi = \app\models\TAsuransi::findOne(['kode'=>$_GET['kode']]);
			$paramprint['judul'] = Yii::t('app', 'PENGAJUAN ASURANSI');
			return $this->renderAjax('detailAsuransi',['modAsuransi'=>$modAsuransi,'paramprint'=>$paramprint]);
        }
	}

}
