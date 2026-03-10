<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\TSpb;
use yii\helpers\Json;

class ApprovalcenterController extends DeltaBaseController
{
	
	public $defaultAction = 'spb';
	public function actionSpb(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id','reff_no','spb_nomor','tanggal_berkas', 'assigned_nama', 'approved_by_nama', 
                                '(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT m_brg_bhp.bhp_nm, t_spb_detail.spbd_jml, m_brg_bhp.bhp_satuan FROM t_spb_detail 
								 JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_spb_detail.bhp_id 
								 WHERE t_spb_detail.spb_id = t_spb.spb_id ) t) AS detail',
                                $param['table'].'.status', $param['table'].'.created_at', $param['table'].'.created_at'];
			$param['where'] = "reff_no ILIKE '%SPB%' AND ".$param['table'].".status = 'Not Confirmed'";
			$param['join'] = "LEFT JOIN t_spb ON view_approval.reff_no = t_spb.spb_kode";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('indexSPB');
	}
    public function actionSpbConfirmed(){
        $model = new \app\models\ViewApproval();
        $model->tgl_awal = date('d/m/Y', strtotime('-30 days'));
		$model->tgl_akhir = date('d/m/Y');
        if(\Yii::$app->request->get('dt')=='table-laporan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params);
				$model->attributes = $form_params['ViewApproval'];
				$model->tgl_awal = $form_params['ViewApproval']['tgl_awal'];
				$model->tgl_akhir = $form_params['ViewApproval']['tgl_akhir'];
				$model->contain = $form_params['ViewApproval']['contain'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchSPBConfirmedDt() ));
		}
		return $this->render('indexSPBconfirmed',['model' => $model]);
	}
	
	public function actionSpo(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id','reff_no',['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'], 'assigned_nama', 'approved_by_nama', $param['table'].'.status', $param['table'].'.created_at', 'cancel_transaksi_id'];
			$param['where'] = "reff_no ILIKE '%SPO%' and status = 'Not Confirmed' and t_spo.cancel_transaksi_id is null ";
			$param['join'] = ['JOIN t_spo ON t_spo.spo_kode = '.$param['table'].'.reff_no'];
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				// $param['where'] = "assigned_to = ".Yii::$app->user->identity->pegawai_id;
			}
			$param['order'] = "created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('indexSPO',['status' => 'Not Confirmed']);
	}

	public function actionSpoConfirmed(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id','reff_no',['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'], 'assigned_nama', 'approved_by_nama', $param['table'].'.status', $param['table'].'.created_at', 'cancel_transaksi_id'];
			$param['where'] = "reff_no ILIKE '%SPO%' and status != 'Not Confirmed' and t_spo.cancel_transaksi_id is null ";
			$param['join'] = ['JOIN t_spo ON t_spo.spo_kode = '.$param['table'].'.reff_no'];
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				// $param['where'] = "assigned_to = ".Yii::$app->user->identity->pegawai_id;
			}
			$param['order'] = "created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('indexSPO',['status' => 'Confirmed']);
	}
	
        public function actionSpoAborted(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id','reff_no',['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'], 'assigned_nama', 'approved_by_nama', $param['table'].'.status', $param['table'].'.created_at', 'cancel_transaksi_id'];
			$param['where'] = "reff_no ILIKE '%SPO%' and status in ('Not Confirmed','ABORTED') and t_spo.cancel_transaksi_id is not null ";
			$param['join'] = ['JOIN t_spo ON t_spo.spo_kode = '.$param['table'].'.reff_no'];
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				// $param['where'] = "assigned_to = ".Yii::$app->user->identity->pegawai_id;
			}
			$param['order'] = "created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('indexSPO',['status' => 'Aborted']);
	}
        
	public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			return $this->renderAjax('info',['model'=>$model]);
		}
	}

    public function actionShowDetails(){
        if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$data['html'] = '';
			$model = \app\models\TApproval::findOne($approval_id);
			$modReff = $this->loadBerkas($model->reff_no)['model'];
			$modDetail = $this->loadBerkas($model->reff_no)['modDetail'];
			switch (substr($model->reff_no,0,3)){
				case "SPB":
					$data['html'] = $this->renderPartial('showDetailsSpb',['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail]);
					break;
				case "SPO":
					$modSpo = \app\models\TSpo::findOne(['spo_kode'=>$model->reff_no]);
					$nominallevels = \app\models\MApprovalNominallevel::find()->where(['active'=>true])->all();
					$currentAccesibleActor = \app\models\MApprovalNominallevel::findOne(['pegawai_id'=>Yii::$app->user->identity->pegawai_id]);
					$accessible_level = false;
					if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
						if(!empty($currentAccesibleActor)){
							$totalspo = $modReff->spo_total;
							if($currentAccesibleActor->pegawai_id == $model->assigned_to){
								$accessible_level = true;
							}
//							foreach($nominallevels as $i => $level){
//								if( ($totalspo < $level->nominal) && ($currentAccesibleActor->pegawai_id == $level->pegawai_id) ){
//									$accessible_level = true;
//								}
//							}
						}
					}else{
						$accessible_level = true;
					}
					$data['html'] = $this->renderPartial('showDetailsSpo',['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail,'accessible_level'=>$accessible_level,'modSpo'=>$modSpo]);
					break;
			}
			return $this->asJson($data);
        }
    }
	
	public function actionApproveConfirm($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$berkas_nama = \app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no);
			$pesan = "Yakin akan menyetujui ".$berkas_nama." ini?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
					if(!empty($model)){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								// check approval people
								$modAppr = \app\models\TApproval::find()->where(['reff_no'=>$model->reff_no])->all();
								$approve_all = TRUE;
								if(count($modAppr)>0){
									foreach($modAppr as $i => $appr){
										if($appr->status == \app\models\TApproval::STATUS_APPROVED){
											$approve_all &= TRUE;
										}else{
											$approve_all = FALSE;
										}
									}
								}

								// tambah coding untuk menerima alasan approval
								$modReff = $this->loadBerkas($model->reff_no)['model'];
								$reasons = Json::decode($modReff->reason_approval);
						 		$reasons[] = [
									'assigned_to' => $model->assigned_to,
									'level'	=> $model->level,
									'status' => $model->status,
									'tanggal_approve' => date('Y-m-d H:i:s'),
									'reason' => $_POST['alasan']
								];
								$modReff->reason_approval = Json::encode($reasons);
								if (!$modReff->validate() || !$modReff->save()) {
									$data['status'] = false;
									$data['message'] = 'Gagal menyimpan alasan';
								}
								// end alasan

								// end check approval people
								if($approve_all){
									$modreff = $this->loadBerkas($model->reff_no)['model'];
									$modreff->approve_date = date('Y-m-d');
									$modreff->approve_status = \app\models\TApproval::STATUS_APPROVED;
									if( $modreff->validate() && $modreff->save() ){
										$success_2 = true;
									}else{
										$success_2 = false;
									}
								}else{
									$success_2 = true;
								}
							}
						}
					}
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					exit;
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); showdetails("'.$id.'");';
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
			return $this->renderAjax('@views/apps/partial/_globalPrompt',['id'=>$id,'pesan'=>$pesan,'actionname'=>'ApproveConfirm']);
		}
	}
	
	public function actionRejectConfirm($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TApproval::findOne($id);
			$berkas_nama = \app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no);
			$pesan = "Yakin akan menolak ".$berkas_nama." ini?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
					if(!empty($model)){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
								$modreff = $this->loadBerkas($model->reff_no)['model'];
								$modreff->approve_date = date('Y-m-d');
								$modreff->approve_status = \app\models\TApproval::STATUS_REJECTED;
								if( $modreff->validate() && $modreff->save() ){
									$success_2 = true;
								}
							}
						}
					}

					// tambah coding untuk menerima alasan approval
					$modReff = $this->loadBerkas($model->reff_no)['model'];
					$reasons = Json::decode($modReff->reason_approval);
					$reasons[] = [
							'assigned_to' => $model->assigned_to,
							'level'	=> $model->level,
							'status' => $model->status,
							'tanggal_approve' => date('Y-m-d'),
							'reason' => $_POST['alasan']
						];
					$modReff->reason_approval = Json::encode($reasons);
					if (!$modReff->validate() || !$modReff->save()) {
						$data['status'] = false;
						$data['message'] = 'Gagal menyimpan alasan';
					}
					// end alasan

					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); showdetails("'.$id.'");';
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
			return $this->renderAjax('@views/apps/partial/_globalPrompt',['id'=>$id,'pesan'=>$pesan,'actionname'=>'RejectConfirm']);
		}
	}
	
	public function loadBerkas($berkas_kode){
		$berkas_initial = substr($berkas_kode,0,3);
		switch ($berkas_initial){
		case "SPB":
			$model = \app\models\TSpb::findOne(['spb_kode'=>$berkas_kode]);
			$modDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$model->spb_id])->all();
			return ['model'=>$model,'modDetail'=>$modDetail];
			break;
		case "SPO":
			$model = \app\models\TSpo::findOne(['spo_kode'=>$berkas_kode]);
			$modDetail = \app\models\TSpoDetail::find()->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->where(['spo_id'=>$model->spo_id])->all();
			return ['model'=>$model,'modDetail'=>$modDetail];
			break;
		}
	}
	
	public function actionConfirmSPB(){
		if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$modApprove = \app\models\TApproval::findOne($approval_id);
			$modSPB = \app\models\TSpb::findOne(['spb_kode'=>$modApprove->reff_no]);
			$modApproveMenyetujui = \app\models\TApproval::findOne(['reff_no'=>$modSPB->spb_kode,'assigned_to'=>$modSPB->spb_disetujui]);
//			$modApproveMengetahui = \app\models\TApproval::findOne(['reff_no'=>$modSPB->spb_kode,'assigned_to'=>$modSPB->spb_mengetahui]);
			
			if($modApprove->assigned_to == $modApproveMenyetujui->assigned_to){
				$data = TRUE;
			}else{
				if($modApproveMenyetujui->status != \app\models\TApproval::STATUS_NOT_CONFIRMATED){
					$data = TRUE;
				}else{
					$data = FALSE;
				}
			}
			return $this->asJson($data);
		}
	}
	public function actionNotAllowedSPB(){
		if(\Yii::$app->request->isAjax){
			$judul = "SPB Confirm";
			$pesan = "SPB ini belum dapat di konfirmasi, karena orang yang menyetujui belum melakukan konfirmasi.";
			return $this->renderAjax('@views/apps/partial/_globalInfo',['judul'=>$judul,'pesan'=>$pesan,'actionname'=>'']);
		}
	}
	
}
