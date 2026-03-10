<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovaldiskonController extends DeltaBaseController
{
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id','reff_no',['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'],'jenis_produk', 'cust_an_nama', 'assigned_nama', 'approved_by_nama', $param['table'].'.status', $param['table'].'.created_at'];
			$param['join'] = "LEFT JOIN t_nota_penjualan on view_approval.reff_no = t_nota_penjualan.kode
							  LEFT JOIN m_customer ON m_customer.cust_id = t_nota_penjualan.cust_id";
			$param['where'] = "view_approval.status = 'Not Confirmed' and (reff_no ILIKE '%PNP%') OR (reff_no ILIKE '%VNP%') OR (reff_no ILIKE '%SNP%') OR (reff_no ILIKE '%MNP%') OR (reff_no ILIKE '%LNP%') OR (reff_no ILIKE '%BNP%') OR (reff_no ILIKE '%FNP%') OR (reff_no ILIKE '%HNP%') OR (reff_no ILIKE '%KNP%') OR (reff_no ILIKE '%GNP%') OR (reff_no ILIKE '%DNP%')";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Not Confirmed']);
	}

	public function actionIndexConfirmed(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\ViewApproval::tableName();
			$param['pk']= "approval_id";
			$param['column'] = ['approval_id','reff_no',['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'],'jenis_produk', 'cust_an_nama', 'assigned_nama', 'approved_by_nama', $param['table'].'.status', $param['table'].'.created_at'];
			$param['join'] = "LEFT JOIN t_nota_penjualan on view_approval.reff_no = t_nota_penjualan.kode
							  LEFT JOIN m_customer ON m_customer.cust_id = t_nota_penjualan.cust_id";
			$param['where'] = "view_approval.status != 'Not Confirmed' and (reff_no ILIKE '%PNP%') OR (reff_no ILIKE '%VNP%') OR (reff_no ILIKE '%SNP%') OR (reff_no ILIKE '%MNP%') OR (reff_no ILIKE '%LNP%') OR (reff_no ILIKE '%BNP%') OR (reff_no ILIKE '%FNP%') OR (reff_no ILIKE '%HNP%') OR (reff_no ILIKE '%KNP%') OR (reff_no ILIKE '%GNP%') OR (reff_no ILIKE '%DNP%')";
			if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
				$param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
			}
			$param['order'] = "created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index',['status' => 'Confirmed']);
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
			$modReff = \app\models\TNotaPenjualan::findOne(['kode'=>$model->reff_no]);
			$modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id'=>$modReff->nota_penjualan_id])->orderBy(['nota_penjualan_detail_id'=>SORT_DESC])->all();
			$data['html'] = $this->renderPartial('showNota',['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail]);
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
	
	public function actionCheckApproval(){
		if(\Yii::$app->request->isAjax){
			$approval_id = Yii::$app->request->post('approval_id');
			$modApprove = \app\models\TApproval::findOne($approval_id);
			$modApproveLevel1 = \app\models\TApproval::findOne(['reff_no'=>$modApprove->reff_no,'assigned_to'=> \app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO]);
			if($modApprove->assigned_to == $modApproveLevel1->assigned_to){
				$data = TRUE;
			}else{
				if($modApproveLevel1->status == \app\models\TApproval::STATUS_APPROVED){
					$data = TRUE;
				}else{
					$data = FALSE;
				}
			}
			return $this->asJson($data);
		}
	}
	public function actionNotAllowedApprove(){
		if(\Yii::$app->request->isAjax){
			$modPegawai = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO);
			$judul = "Notice";
			$pesan = "Approval belum bisa dilakukan, karena ".$modPegawai->pegawai_nama." belum melakukan konfirmasi!";
			return $this->renderAjax('@views/apps/partial/_globalInfo',['judul'=>$judul,'pesan'=>$pesan,'actionname'=>'']);
		}
	}
	
}
