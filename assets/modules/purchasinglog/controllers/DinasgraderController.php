<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class DinasgraderController extends DeltaBaseController
{
	public $defaultAction = 'index';
	
	public function actionIndex(){
		$model = new \app\models\TDkg();
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->post('getItems')){
				$data = []; $data['html'] = ''; $saldodinas=0;$saldomakan=0;
                $wheretype = "AND m_graderlog.type  = ''";
                if(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER){
                    $wheretype = "";
                }elseif( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_SENGON)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_SENGON) ){
                    $wheretype = "AND m_graderlog.type  = 'GLS'";
                }elseif( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_ALAM)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_ALAM) ){
                    $wheretype = "AND m_graderlog.type  = 'GLA'";
                }
				$model = \app\models\TDkg::find()->join("JOIN", "m_graderlog", "m_graderlog.graderlog_id = t_dkg.graderlog_id")
                            ->where("status = '".\app\models\TDkg::AKTIF_DINAS."' {$wheretype}")->orderBy(['created_at'=>SORT_DESC])->all();
				if(count($model)>0){
					foreach($model as $i => $mod){
						$mod->graderlog_nm = $mod->graderlog->graderlog_nm;
						$none = "";
						$modAjuanDinas = \app\models\TAjuandinasGrader::find()->where("dkg_id = '{$mod->dkg_id}'")->all();
						if(count($modAjuanDinas)>0){
							foreach($modAjuanDinas as $i => $ajuan){
								$modApproval = \app\models\TApproval::findOne(['reff_no'=>$ajuan->kode]);
								if($modApproval->status == \app\models\TApproval::STATUS_APPROVED){
									$none = "none;";
								}
							}
						}
						$data['html'] .= $this->renderPartial('_item',['model'=>$mod,'i'=>$i,'saldodinas'=>$saldodinas,'saldomakan'=>$saldomakan,'none'=>$none]);
					}
				}else{
					$data['html'] = "<tr><td colspan='9'><center><i>Belum ada data Dinas Grader</i></center></td></tr>";
				}
				return $this->asJson($data);
			}
        }
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionCreateDkg(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TDkg();
			$model->kode = "Auto Generate";
			$model->tanggal = date('d/m/Y');
			$model->tipe = "ORIENTASI";
			$modDkgs = \app\models\TDkg::find()->where("status = '".\app\models\TDkg::AKTIF_DINAS."'")->all();
			$grader_aktif = [];
			if(count($modDkgs)>0){
				foreach($modDkgs as $i => $dkg){
					$grader_aktif[] = $dkg->graderlog_id;
				}
			}
			if( isset($_POST['TDkg']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = FALSE; // t_dkg
					$model->load(\Yii::$app->request->post());
					$model->kode = \app\components\DeltaGenerator::kodeDKG();
					$model->status = \app\models\TDkg::AKTIF_DINAS;
					if($model->validate()){
						if($model->save()){
							$success_1 = TRUE;
						}
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
						$data['message'] = Yii::t('app', "Data Berhasil di Simpan");
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
			return $this->renderAjax('createDinas',['model'=>$model,'actionname'=>'CreateDkg','grader_aktif'=>$grader_aktif]);
		}
	}
	
	public function actionEditDkg($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TDkg::findOne($id);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->graderlog_nm = $model->graderlog->graderlog_nm;
			$model->wilayah_dinas_nama = $model->wilayahDinas->wilayah_dinas_nama;
			if( isset($_POST['TDkg']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = FALSE; // t_dkg
					$model->load(\Yii::$app->request->post());
					if($model->validate()){
						if($model->save()){
							$success_1 = TRUE;
						}
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '';
						$data['message'] = Yii::t('app', "Data Berhasil di Simpan");
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
			return $this->renderAjax('editDinas',['model'=>$model,'actionname'=>'EditDinas']);
		}
	}
	
	public function actionDeleteItem($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TDkg::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = 'getItems()';
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>'deleteItem']);
		}
	}
	
	public function actionValidationSelesaiDinas($dkg_id){
		if(\Yii::$app->request->isAjax){
			$modAjuanDinas = \app\models\TAjuandinasGrader::find()->where(['dkg_id'=>$dkg_id])->all();
			$modAjuanMakan = \app\models\TAjuanmakanGrader::find()->where(['dkg_id'=>$dkg_id])->all();
			$status_dinas = true;
			$status_makan = true;
			if(count($modAjuanDinas)>0){
				foreach($modAjuanDinas as $dinas){
					$modApproveDinas = \app\models\TApproval::findOne(['reff_no'=>$dinas->kode]);
					if($modApproveDinas->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
						$status_dinas &= false;
					}
				}
			}
			if(count($modAjuanMakan)>0){
				foreach($modAjuanMakan as $makan){
					$modAjuanMakan = \app\models\TApproval::findOne(['reff_no'=>$makan->kode]);
					if($modAjuanMakan->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
						$status_makan &= false;
					}
				}
			}
			if($status_dinas && $status_makan){
				$data['status'] = true;
			}else{
				$data['status'] = false;
				$data['msg'] = "Tidak bisa menyelesaikan dinas grader ini karena masih ada pengajuan yang belum terkonfirmasi";
			}
			return $this->asJson($data);
		}
	}
	public function actionChangeStatus($dkg_id){
		if(\Yii::$app->request->isAjax){
			$modDkg = \app\models\TDkg::findOne($dkg_id);
			$pesan = "Anda akan me Non-Aktif kan Kerja Dinas Grader '<b>".$modDkg->graderlog->graderlog_nm."</b>' ?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_dkg
					$modDkg->status = \app\models\TDkg::NON_AKTIF_DINAS;
					$modDkg->saldo_akhir_dinas = \app\models\HKasDinasgrader::getSaldoKas($modDkg->graderlog_id);
					$modDkg->saldo_akhir_makan = \app\models\HKasMakangrader::getSaldoKas($modDkg->graderlog_id);
					$modDkg->selesai_dinas_at = date('Y-m-d H:i:s');
					if($modDkg->validate()){
						if($modDkg->save()){
							$success_1 = true;
						}
					}
//					echo "<pre>";
//					print_r($success_1);
//					exit;
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( ".fa-close" ).click(); setClosingBtn();';
						$data['message'] = Yii::t('app', "Data Berhasil Di Non-Aktifkan");
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
			return $this->renderAjax('_changeStatus',['id'=>$dkg_id,'pesan'=>$pesan,'modDkg'=>$modDkg,'actionname'=>'ChangeStatus']);
		}
	}
	
	public function actionHistory(){
		$model = new \app\models\TMutasiGudanglogistik();
		$model->tgl_awal = date('d/m/Y', strtotime('first day of this month'));
		$model->tgl_akhir = date('d/m/Y');
        $wheretype = "AND m_graderlog.type  = ''";
        if(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER){
            $wheretype = "";
        }elseif( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_SENGON)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_SENGON) ){
            $wheretype = "AND m_graderlog.type  = 'GLS'";
        }elseif( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_ALAM)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_ALAM) ){
            $wheretype = "AND m_graderlog.type  = 'GLA'";
        }
        $model = \app\models\TDkg::find()->join("JOIN", "m_graderlog", "m_graderlog.graderlog_id = t_dkg.graderlog_id")
                    ->where("status = '".\app\models\TDkg::AKTIF_DINAS."' {$wheretype}")->orderBy(['created_at'=>SORT_DESC])->all();
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-detail'){
				$param['table']= \app\models\TDkg::tableName();
				$param['pk']= \app\models\TDkg::primaryKey()[0];
				$param['column'] = ['dkg_id',$param['table'].'.kode','tipe','graderlog_nm','wilayah_dinas_nama','saldo_akhir_dinas','saldo_akhir_makan','status','selesai_dinas_at'];
				$param['where']= "status = '".\app\models\TDkg::NON_AKTIF_DINAS."' {$wheretype}";
				$param['order']= $param['table'].".created_at DESC";
				$param['join']= ['JOIN m_graderlog ON m_graderlog.graderlog_id = '.$param['table'].'.graderlog_id',
								 'JOIN m_wilayah_dinas ON m_wilayah_dinas.wilayah_dinas_id = '.$param['table'].'.wilayah_dinas_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('history');
        }
		return $this->render('history',['model'=>$model]);
	}
	
	public function actionDetailBiaya($dkg_id){
		if(\Yii::$app->request->isAjax){
			$modDkg = \app\models\TDkg::findOne($dkg_id);
			return $this->renderAjax('_summary',['id'=>$dkg_id,'modDkg'=>$modDkg,'actionname'=>'ChangeStatus']);
		}
	}
}
