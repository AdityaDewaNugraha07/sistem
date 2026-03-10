<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class OpenvoucherController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TOpenVoucher();
        $model->departement_id = !empty(Yii::$app->user->identity->pegawai->departement_id)?Yii::$app->user->identity->pegawai->departement_id:'';
        $model->departement_nama = !empty(Yii::$app->user->identity->pegawai->departement->departement_nama)?Yii::$app->user->identity->pegawai->departement->departement_nama:'';
        $model->tanggal = date("d/m/Y");
        $model->kode = 'Auto Generate';
		$model->mata_uang = "IDR";
		$model->cara_bayar = "Transfer Bank";
		$model->status_bayar = "WAITING";
		$model->status_approve = "Not Confirmed";
        $model->prepared_by = Yii::$app->user->identity->pegawai_id;
        $model->prepared_by_display = Yii::$app->user->identity->pegawai->pegawai_nama;
		$model->total_dpp = 0;
		$model->total_dp = 0;
		$model->total_sisa = 0;
		$model->total_ppn = 0;
		$model->total_pph = 0;
		$model->biaya_tambahan = 0;
		$model->total_potongan = 0;
		$model->total_pembayaran = 0;
		$modDetails = [];
        
        if(isset($_GET['kode'])){
            $model = \app\models\TOpenVoucher::findOne(['kode'=>$_GET['kode']]);
            return $this->redirect(['index','open_voucher_id'=>$model->open_voucher_id]);
        }
        if(isset($_GET['open_voucher_id'])){
            $model = \app\models\TOpenVoucher::findOne($_GET['open_voucher_id']);
            $modDetails = \app\models\TOpenVoucherDetail::find()->where(['open_voucher_id'=>$_GET['open_voucher_id']])->all();
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->departement_nama = \app\models\MDepartement::findOne($model->departement_id)->departement_nama;
        }
        if( Yii::$app->request->post('TOpenVoucher')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_open_voucher
                $success_2 = true; // t_open_vouhcer_detail
                $success_3 = true; // t_approval
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeOpenVoucher();
					$model->tanggal = date('d/m/Y');
				}
                $model->total_dpp = isset($_POST['total_dpp'])?$_POST['total_dpp']:"";
                $model->total_ppn = isset($_POST['total_ppn'])?$_POST['total_ppn']:"";
                $model->total_pph = isset($_POST['total_pph'])?$_POST['total_pph']:"";
                $model->total_potongan = isset($_POST['total_potongan'])?$_POST['total_potongan']:"";
                $model->biaya_tambahan = isset($_POST['biaya_tambahan'])?$_POST['biaya_tambahan']:"";
                $model->total_pembayaran = isset($_POST['total_pembayaran'])?$_POST['total_pembayaran']:"";
                $model->reff_no2 = isset($_POST['TOpenVoucher']['reff_no2'])? (is_array($_POST['TOpenVoucher']['reff_no2'])? implode(",", $_POST['TOpenVoucher']['reff_no2']) :$_POST['TOpenVoucher']['reff_no2']) :"";
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        
                        if(!empty($_POST['TOpenVoucherDetail'])){
                            if(isset($_GET['edit'])){
                                \app\models\TOpenVoucherDetail::deleteAll("open_voucher_id = ".$model->open_voucher_id);
                            }
                            foreach($_POST['TOpenVoucherDetail'] as $i => $detail){
                                $modDetail = new \app\models\TOpenVoucherDetail();
                                $modDetail->open_voucher_id = $model->open_voucher_id;
                                $modDetail->attributes = $detail;
                                if($model->tipe=="PELUNASAN LOG SENGON"){
                                    $asd = explode(",", $model->reff_no2);
                                    if(!empty($asd)){
                                        foreach($asd as $iii => $asdasd){
                                            if (strpos($modDetail->deskripsi, $asdasd) !== false) {
                                                $modDetail->reff_no = $asdasd;
                                            }
                                        }
                                    }
                                }
                                if($modDetail->validate()){
                                    if($modDetail->save()){
                                        $success_2 &= true;
                                    }else{
                                        $success_2 = false;
                                    }
                                }else{
                                    $success_2 = false;
                                }
                            }
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
				
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>3";
//				print_r($success_3);
//				exit;
				
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'open_voucher_id'=>$model->open_voucher_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modDetails'=>$modDetails]);
	}
    
    public function saveApproval($model){
		$success = false;
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->approver_1;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success = $modelApproval->createApproval();
		if($model->approver_2){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approver_2;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 2;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		return $success;
	}
    
    public function actionSetDDTipeOVByDept(){
        if(\Yii::$app->request->isAjax){
			$dept_id = Yii::$app->request->post('dept_id');
            $data['html'] = '';
			$html = '<option value=""></option>';
            $html .= \yii\bootstrap\Html::tag('option',"REGULER",['value'=>"REGULER"]);
            if((Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_PURCH_LOG) || (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER)){
                $html .= \yii\bootstrap\Html::tag('option',"PEMBAYARAN LOG ALAM",['value'=>"PEMBAYARAN LOG ALAM"]);
//                $html .= \yii\bootstrap\Html::tag('option',"DEPOSIT SUPPLIER LOG",['value'=>"DEPOSIT SUPPLIER LOG"]);
            }
            if((Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_PCH) || (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER)){
                $html .= \yii\bootstrap\Html::tag('option',"DP LOG SENGON",['value'=>"DP LOG SENGON"]);
                $html .= \yii\bootstrap\Html::tag('option',"PELUNASAN LOG SENGON",['value'=>"PELUNASAN LOG SENGON"]);
            }
            
			$data['html'] = $html;
			return $this->asJson($data);
		}
    }
    
    public function actionSetDDPenerima(){
        if(\Yii::$app->request->isAjax){
			$tipe = Yii::$app->request->post('tipe');
            $open_voucher_id = Yii::$app->request->post('open_voucher_id');
            if(!empty($open_voucher_id)){
                $model = \app\models\TOpenVoucher::findOne($open_voucher_id);
            }else{
                $model = new \app\models\TOpenVoucher();
            }
            $data['html'] = '';
			$html = '<option value=""></option>';
            if($tipe == "REGULER"){
                foreach(\app\models\MPenerimaVoucher::getOptionList() as $i => $val){
                    $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                }
            }else if($tipe == "PEMBAYARAN LOG ALAM"){
                foreach(\app\models\MSuplier::getOptionList("LA") as $i => $val){
                    $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                }
            }else if($tipe == "DEPOSIT SUPPLIER LOG"){
                foreach(\app\models\MSuplier::getOptionList("LA") as $i => $val){
                    $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                }
            }else if($tipe == "DP LOG SENGON" || $tipe == "PELUNASAN LOG SENGON" ){
                foreach(\app\models\MSuplier::getOptionList("LS") as $i => $val){
                    $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                }
            }
			$data['html'] = $html;
            $data['html_penerima_reff'] = $this->renderPartial('_reff_no',['model'=>$model,'tipe'=>$tipe]);
            $data['html_berkas_reff'] = $this->renderPartial('_reff_berkas',['model'=>$model,'tipe'=>$tipe]);
			return $this->asJson($data);
		}
    }
    
    public function actionSetApprover(){
        if(\Yii::$app->request->isAjax){
			$tipe = Yii::$app->request->post('tipe');
            $data = [];
            if($tipe == "PEMBAYARAN LOG ALAM"){
                $data['approver_1'] = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_WIBOWO;
                $data['approver_1_display'] = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_WIBOWO)->pegawai_nama;
                $data['approver_2'] = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                $data['approver_2_display'] = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
            }else if($tipe == "DEPOSIT SUPPLIER LOG"){
                $data['approver_1'] = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_WIBOWO;
                $data['approver_1_display'] = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_WIBOWO)->pegawai_nama;
                $data['approver_2'] = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                $data['approver_2_display'] = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
            }else if($tipe == "DP LOG SENGON" || $tipe == "PELUNASAN LOG SENGON" ){
                $data['approver_1'] = \app\components\Params::DEFAULT_PEGAWAI_ID_PAK_WID;
                $data['approver_1_display'] = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_PAK_WID)->pegawai_nama;
//                $data['approver_2'] = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
//                $data['approver_2_display'] = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
            }
			return $this->asJson($data);
		}
    }
    
    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new \app\models\TOpenVoucherDetail();
            $modDetail->nominal = 0;
            $modDetail->ppn = 0;
			$modDetail->pph = 0;
			$modDetail->subtotal = 0;
            $data['html'] = $this->renderPartial('_item',['modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }
    
    public function actionSetHeaderAfterSave(){
        if(\Yii::$app->request->isAjax){
			$open_voucher_id = Yii::$app->request->post('open_voucher_id');
            $model = \app\models\TOpenVoucher::findOne($open_voucher_id);
            if($model->tipe == "PELUNASAN LOG SENGON"){
                $reff_no2 = explode(",", $model->reff_no2);
                if(!empty($reff_no2)){
                    $modTagihan = \app\models\TTagihanSengon::findOne(['kode'=>$reff_no2[0]]);
                }
                $data['modTagihan']['terima_sengon_id'] = (!empty($modTagihan)?$modTagihan->terima_sengon_id:"");
            }
            $data['html'] = $this->renderPartial('_reff_no',['model'=>$model,'tipe'=>$model->tipe]);
            $data['model'] = !empty($model->attributes)?$model->attributes:"";
            
			return $this->asJson($data);
		}
    }
    
    function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $open_voucher_id = Yii::$app->request->post('open_voucher_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
			$model = \app\models\TOpenVoucher::findOne($open_voucher_id);
			$modDetails = \app\models\TOpenVoucherDetail::find()->where(['open_voucher_id'=>$open_voucher_id])->all();
            $data['html'] = '';
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
                    $detail->nominal = number_format($detail->nominal);
                    $detail->ppn = number_format($detail->ppn);
                    $detail->pph = number_format($detail->pph);
                    $detail->subtotal = number_format($detail->subtotal);
					$data['html'] .= $this->renderPartial('_item',['model'=>$model,'modDetail'=>$detail,'i'=>$i,'edit'=>$edit]);
                }
            }
            return $this->asJson($data);
        }
    }
    
    function actionGetItemsByTagihanSengon(){
		if(\Yii::$app->request->isAjax){
            $tagihan_sengon_id = Yii::$app->request->post('tagihan_sengon_id');
			$model = \app\models\TTagihanSengon::findOne($tagihan_sengon_id);
            $diameter_harga = \yii\helpers\Json::decode($model->diameter_harga);
            $data['html'] = '';
            if(count($diameter_harga)>0){
                foreach($diameter_harga as $i => $dia){
                    $modDetail = new \app\models\TOpenVoucherDetail();
                    $modDetail->deskripsi = "Log Sengon ".$dia['panjang']." Cm (".$dia['wilayah'].") ".$dia['diameter_awal']."-".$dia['diameter_awal']." Cm ".$dia['pcs']."Btg (".$dia['m3']." m3)";
                    $modDetail->nominal = number_format($dia['subtotal']);
                    $modDetail->ppn = 0;
                    $modDetail->pph = number_format($dia['pph']);
                    $modDetail->subtotal = number_format($dia['bayar']);
					$data['html'] .= $this->renderPartial('_item',['model'=>$model,'modDetail'=>$modDetail,'i'=>$i]);
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave-this'){
				$param['table']= \app\models\TOpenVoucher::tableName();
				$param['pk']= $param['table'].".".\app\models\TOpenVoucher::primaryKey()[0];
				$param['column'] = [$param['table'].'.open_voucher_id',
                                    $param['table'].'.kode',
                                    $param['table'].'.tanggal',
                                    $param['table'].'.tipe',
                                    'm_departement.departement_nama',
                                    $param['table'].'.reff_no',
                                    "(CASE 
                                        WHEN t_open_voucher.tipe='REGULER' THEN (SELECT CONCAT('<b>',nama_penerima,'</b><br>',nama_perusahaan) FROM m_penerima_voucher WHERE m_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id )
                                        WHEN t_open_voucher.tipe='PEMBAYARAN LOG ALAM' THEN (SELECT CONCAT('<b>',suplier_nm,'</b><br>',suplier_nm_company) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
                                        WHEN t_open_voucher.tipe='DEPOSIT SUPPLIER LOG' THEN (SELECT CONCAT('<b>',suplier_nm,'</b><br>',suplier_nm_company) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
                                        WHEN t_open_voucher.tipe='DP LOG SENGON' THEN (SELECT CONCAT('<b>',suplier_nm,'</b><br>',suplier_almt) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
                                        WHEN t_open_voucher.tipe='PELUNASAN LOG SENGON' THEN (SELECT CONCAT('<b>',suplier_nm,'</b><br>',suplier_almt) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
                                      ELSE '' END) AS penerima",
                                    $param['table'].'.cara_bayar',
                                    $param['table'].'.total_pembayaran',
                                    $param['table'].'.status_approve',
                                    $param['table'].'.status_bayar',
                                    'm_pegawai.pegawai_nama',
                                    't_open_voucher.voucher_pengeluaran_id',
                                    't_voucher_pengeluaran.kode AS kode_voucher_pengeluaran',
                                    't_voucher_pengeluaran.total_nominal AS nominal_pembayaran',
                                    $param['table'].'.keterangan AS keterangan',
                                    '( SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT * FROM t_tagihan_sengon WHERE kode IN( SELECT reff_no FROM t_open_voucher_detail WHERE t_open_voucher_detail.open_voucher_id = t_open_voucher.open_voucher_id GROUP BY 1 ) ) t) AS keterangan_sengon',
									];
				$param['join']= ['JOIN m_departement ON m_departement.departement_id = '.$param['table'].'.departement_id 
								  JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.prepared_by
                                  LEFT JOIN t_voucher_pengeluaran AS t_voucher_pengeluaran ON t_voucher_pengeluaran.voucher_pengeluaran_id = t_open_voucher.voucher_pengeluaran_id
                                '];
                if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
                    $param['where'] = $param['table'].".departement_id = ".Yii::$app->user->identity->pegawai->departement_id;
                }
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
    
    public function actionSetDDReff2(){
        if(\Yii::$app->request->isAjax){
			$tipe = Yii::$app->request->post('tipe');
			$kode = Yii::$app->request->post('kode');
			$open_voucher_id = Yii::$app->request->post('open_voucher_id');
            $data['html'] = '';
			$html = '<option value=""></option>';
            if(!empty($tipe) && !empty($kode)){
                if($tipe == "PELUNASAN LOG SENGON" ){
                    $modPo = \app\models\TPosengon::findOne(['kode'=>$kode]);
                    $drop_reff2 = []; 
                    if(!empty($open_voucher_id)){
                        $modOpen = \app\models\TOpenVoucher::findOne($open_voucher_id);
                        $tfss = !empty($modOpen->reff_no2)? explode(",", $modOpen->reff_no2):"";
                        $qwe = "";
                        if(!empty($tfss)){
                            foreach($tfss as $i => $tfs){
                                $qwe .= "'".$tfs."'";
                                if(($i+1)<count($tfss)){
                                    $qwe .= ',';
                                }
                            }
                        }
                        $tagihan = \app\models\TTagihanSengon::find()->where("kode IN(".$qwe.")")->orderBy('created_at ASC')->all();
                    }else{
                        $tagihan = \app\models\TTagihanSengon::find()->where("posengon_id = {$modPo->posengon_id} AND kode NOT IN ( SELECT COALESCE(t_open_voucher_detail.reff_no, '') FROM t_open_voucher JOIN t_open_voucher_detail ON t_open_voucher_detail.open_voucher_id = t_open_voucher.open_voucher_id WHERE t_open_voucher.reff_no = '{$modPo->kode}' )")->orderBy('created_at ASC')->all();
                    }
                    if(!empty($tagihan)){
                        foreach($tagihan as $i => $tag){
                            $drop_reff2[$tag->kode] = $tag->kode." - ".$tag->reff_no." - Rp.". number_format($tag->total_bayar);
                        }
                    }
                    foreach($drop_reff2 as $i => $val){
                        $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                    }
                }
            }
			$data['html'] = $html;
			return $this->asJson($data);
		}
    }
	public function actionSetReff2(){
        if(\Yii::$app->request->isAjax){
			$tipe = Yii::$app->request->post('tipe');
			$reff_no2 = Yii::$app->request->post('reff_no2');
            $data['html'] = '';
			
            if(!empty($tipe) && !empty($reff_no2)){
                if($tipe == "PELUNASAN LOG SENGON" ){
                    if(!empty($reff_no2)){
                        foreach($reff_no2 as $i => $reff2){
                            $modDetail = new \app\models\TOpenVoucherDetail();
                            $modDetail->deskripsi = "";
                            $modTagihan = \app\models\TTagihanSengon::findOne(['kode'=>$reff2]);
                            if(!empty($modTagihan->terima_sengon_id)){
                                $modTerima = \app\models\TTerimaSengon::findOne($modTagihan->terima_sengon_id);
                                $modDetail->deskripsi .= $modTerima->kode." - Tgl Terima ".date("d/m/y", strtotime($modTerima->tanggal))."\n";
                            }
                            $modDetail->deskripsi .= $modTagihan->kode." - Tgl Tagihan ". date("d/m/y", strtotime($modTagihan->tanggal))." - ".$modTagihan->reff_no."\n";
                            $diameter_harga = \yii\helpers\Json::decode($modTagihan->diameter_harga);
                            $totalharga = 0; $pph=0; $bayar=0;
                            if(count($diameter_harga)>0){
                                foreach($diameter_harga as $i => $dia){
                                    $modDetail->deskripsi .= "   - ".$dia['panjang']."cm(".$dia['wilayah'].") ".$dia['diameter_awal']."-".$dia['diameter_akhir']." = ".$dia['pcs']." pcs ".$dia['m3']." m3 x Rp.".
                                                                \app\components\DeltaFormatter::formatNumberForUser($dia['harga'])."\n";
                                    $totalharga += $dia['subtotal'];
                                    $pph += $dia['pph'];
                                    $bayar += $dia['bayar'];
                                }
                            }
                            $modDetail->nominal = number_format($totalharga);
                            $modDetail->pph = $pph;
                            $data['html'] .= $this->renderPartial('_item',['modDetail'=>$modDetail,'i'=>$i]);
                        }
                    }
                }
            }
			return $this->asJson($data);
		}
    }
}
