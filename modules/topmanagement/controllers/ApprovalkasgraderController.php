<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovalkasgraderController extends DeltaBaseController
{
	public function actionUangdinas(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id','reff_no','kode',['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'], 'assigned_nama', 'approved_by_nama', $param['table'].'.status', $param['table'].'.created_at', $param['table'].'.created_at'];
			$param['where'] = "reff_no ILIKE '%PDG%' and view_approval.status = 'Not Confirmed' ";
			$param['join'] = "left join t_ajuandinas_grader on view_approval.reff_no = t_ajuandinas_grader.kode";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		$p = '';
		return $this->render('uangDinas',['p' => 'uang_dinas', 'status' => 'Not Confirmed']);
	}

	public function actionUangdinasConfirmed(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id','reff_no','kode',['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'], 'assigned_nama', 'approved_by_nama', $param['table'].'.status', $param['table'].'.created_at', $param['table'].'.created_at'];
			$param['where'] = "reff_no ILIKE '%PDG%' and view_approval.status != 'Not Confirmed' ";
			$param['join'] = "left join t_ajuandinas_grader on view_approval.reff_no = t_ajuandinas_grader.kode";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('uangDinas',['p' => 'uang_dinas', 'status' => 'Confirmed']);
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
				case "PDG":
					$data['html'] = $this->renderPartial('showAjuanDinas',['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail]);
					break;
				case "PMG":
					$data['html'] = $this->renderPartial('showAjuanMakan',['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail]);
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
					if(!empty($model)){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_APPROVED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
							}
						}
					}
					if ($success_1) {
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
			return $this->renderAjax('@views/apps/partial/_globalConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'ApproveConfirm']);
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
					if(!empty($model)){
						$model->approved_by = Yii::$app->user->identity->pegawai_id;
						$model->tanggal_approve = date('Y-m-d');
						$model->status = \app\models\TApproval::STATUS_REJECTED;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
							}
						}
					}
					if ($success_1) {
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
			return $this->renderAjax('@views/apps/partial/_globalConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'RejectConfirm']);
		}
	}
	
	public function loadBerkas($berkas_kode){
		$berkas_initial = substr($berkas_kode,0,3);
		switch ($berkas_initial){
		case "PDG":
			$model = \app\models\TAjuandinasGrader::findOne(['kode'=>$berkas_kode]);
			$modDetail = \app\models\TRealisasidinasGrader::find()->where(['dkg_id'=>$model->dkg_id])->orderBy(['realisasidinas_grader_id'=>SORT_DESC])->all();
			return ['model'=>$model,'modDetail'=>$modDetail];
			break;
		case "PMG":
			$model = \app\models\TAjuanmakanGrader::findOne(['kode'=>$berkas_kode]);
			$modDetail = \app\models\TRealisasimakanGrader::find()->where(['dkg_id'=>$model->dkg_id])->orderBy(['realisasimakan_grader_id'=>SORT_DESC])->all();
			return ['model'=>$model,'modDetail'=>$modDetail];
			break;
		}
	}
	
	public function actionUangmakan(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id','reff_no','kode',['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'], 'assigned_nama', 'approved_by_nama', $param['table'].'.status', $param['table'].'.created_at', $param['table'].'.created_at'];
			$param['where'] = "reff_no ILIKE '%PMG%' and view_approval.status = 'Not Confirmed' ";
			$param['join'] = "left join t_ajuandinas_grader on view_approval.reff_no = t_ajuandinas_grader.kode";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('uangMakan',['p' => 'uang_makan', 'status' => 'Not Confirmed']);
	}

	public function actionUangmakanConfirmed(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id','reff_no','kode',['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'], 'assigned_nama', 'approved_by_nama', $param['table'].'.status', $param['table'].'.created_at', $param['table'].'.created_at'];
			$param['where'] = "reff_no ILIKE '%PMG%' and view_approval.status != 'Not Confirmed' ";
			$param['join'] = "left join t_ajuandinas_grader on view_approval.reff_no = t_ajuandinas_grader.kode";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('uangMakan',['p' => 'uang_makan', 'status' => 'Confirmed']);
	}	
	
}
