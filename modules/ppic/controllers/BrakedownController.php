<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class BrakedownController extends DeltaBaseController
{
    public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TBrakedown();
        $model->kode = 'Auto Generate';
        $model->tanggal = date("d/m/Y");

		if(isset($_GET['brakedown_id'])){
            $model = \app\models\TBrakedown::findOne($_GET['brakedown_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$modSpk = \app\models\TSpkSawmill::findOne($model->spk_sawmill_id);
			$model->kode_spk = $modSpk->kode;
        }

		if( Yii::$app->request->post('TBrakedown')){
			$transaction = \Yii::$app->db->beginTransaction();
			try {
				$success_1 = false;     // t_brakedown
                $success_2 = false;     // t_brakedown_detail

				$model->load(\Yii::$app->request->post());

				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeBrakedown();
				}
				$model->prepared_by = Yii::$app->user->identity->pegawai_id;

				if($model->validate()){
                    if($model->save()){
                        $success_1 = true;

						if(isset($_GET['edit'])){
                            \app\models\TBrakedownDetail::deleteAll("brakedown_id = ".$model->brakedown_id);
                        }

						foreach($_POST['TBrakedownDetail'] as $i => $detail){
							$modDetail = new \app\models\TBrakedownDetail();
							$modDetail->attributes = $detail;
							$modDetail->brakedown_id = $model->brakedown_id;
							if($modDetail->validate()){
								if($modDetail->save()){
									$success_2 = true;
								}
							}
						}
					}
				}

				// print_r('1'); print_r($success_1);
				// print_r('2'); print_r($success_2);
				// print_r($model);
				// exit;
				if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'brakedown_id'=>$model->brakedown_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
			} catch (yii\db\Exception $ex){
				$transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
			}
		}
		return $this->render('index',['model'=>$model]);
	}

    public function actionFindSPK(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$edit = Yii::$app->request->get('edit');
			$id = Yii::$app->request->get('id');
			$data = [];
			$active = "";
			if(!empty($term)){
				$and_where = "AND NOT EXISTS (select spk_sawmill_id from t_brakedown where t_brakedown.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id)";
				// $and_where = "AND spk_sawmill_id not in (select spk_sawmill_id from t_brakedown)";
				// if(!empty($edit)) {
				// 	$and_where .= "OR spk_sawmill_id in (select spk_sawmill_id from t_brakedown where brakedown_id = $id)";
				// }
				$query = "
					SELECT spk_sawmill_id, kode FROM t_spk_sawmill
                    WHERE approval_status = 'APPROVED' and status_spk is true
					AND kode ilike '%{$term}%' AND cancel_transaksi_id IS NULL
					ORDER BY created_at";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					// $arraymap = \yii\helpers\ArrayHelper::map($mod, 'spk_sawmill_id', 'kode');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['spk_sawmill_id'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}

    public function actionOpenSPK(){
		if(Yii::$app->request->isAjax){
			$edit = Yii::$app->request->post('edit');
			$id = Yii::$app->request->post('id');
			$and_where = "AND NOT EXISTS (select spk_sawmill_id from t_brakedown where t_brakedown.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id
										  and cancel_transaksi_id is null)";
			// if($edit){
			// 	$and_where .= "OR spk_sawmill_id in (select spk_sawmill_id from t_brakedown where brakedown_id = $id)";
			// }
			if(Yii::$app->request->post('dt') === 'table-spk'){
				$param['table']= \app\models\TSpkSawmill::tableName();
				$param['pk']= $param['table'].".".\app\models\TSpkSawmill::primaryKey()[0];
				$param['column'] = ['spk_sawmill_id',
									'kode',		
									'refisi_ke',
									'tanggal_mulai',
                                    'tanggal_selesai',
                                    'pemenuhan_po',
                                    'peruntukan',
                                    'line_sawmill',
									];
				$param['where']= "cancel_transaksi_id IS NULL AND approval_status = 'APPROVED' AND status_spk is TRUE";

				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('spksawmill', ['id'=>$id, 'edit'=>$edit]);
        }
	}

    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TBrakedown();
            $modDetail = new \app\models\TBrakedownDetail();
            $data['item'] = $this->renderPartial('_item',['model'=>$model,'modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }

	public function actionFindNoLap(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->post('term');
			$kayu_id = Yii::$app->request->post('kayu_id');
			$notinpost = json_decode( Yii::$app->request->post('notin') );
			$data = []; $notin = "";
			if(!empty($notinpost)){
				$notin = "AND no_lap_baru NOT IN(";
				foreach($notinpost as $i => $not){
					$notin .= "'$not'";
					if( ($i+1)!=(count($notinpost)) ){
						$notin .= ",";
					}
				}
				$notin .= ")";
			}
			if(!empty($term) && !empty($kayu_id)){
				$query = "
					SELECT no_lap_baru FROM t_pemotongan_log_detail_potong
					JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama
					WHERE kayu_id = $kayu_id AND no_lap_baru ilike '%$term%' AND alokasi='Sawmill' 
					AND NOT EXISTS (select no_lap_baru from t_brakedown_detail where t_brakedown_detail.no_lap_baru = t_pemotongan_log_detail_potong.no_lap_baru)
					". $notin ."
					GROUP BY no_lap_baru;
				";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['no_lap_baru'], 'text'=>$val['no_lap_baru']];
					}
				}
			}
            return $this->asJson($data);
        }
    }

	public function actionSetItem(){
		if(Yii::$app->request->isAjax){
            $no_lap = Yii::$app->request->post('no_lap');
            if(!empty($no_lap)){
				$model = \app\models\TPemotonganLogDetailPotong::findOne(['no_lap_baru'=>$no_lap]);
				$data = $model;
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
	}

	public function actionModalNoLap(){
		if(\Yii::$app->request->isAjax){
			$tr_seq = Yii::$app->request->post('tr_seq');
			$kayu_id = Yii::$app->request->post('kayu_id');
			$id = Yii::$app->request->post('id');
			$edit = Yii::$app->request->post('edit');
			if(\Yii::$app->request->post('dt')=='table-produk'){
				$param['table']= \app\models\TPemotonganLogDetailPotong::tableName();
				$param['pk']= \app\models\TPemotonganLogDetailPotong::primaryKey()[0];
				$param['column'] = ["pemotongan_log_detail_potong_id", 
									"no_lap_baru", 
									"no_barcode_baru", 
									"grading_rule",
									"diameter_ujung1_baru",
									"diameter_ujung2_baru",
									"diameter_pangkal1_baru",
									"diameter_pangkal2_baru",
									"volume_baru"];
				$param['join']= ["JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama"];
				$param['where'] = "kayu_id = $kayu_id  AND alokasi = 'Sawmill' AND NOT EXISTS (select no_lap_baru from t_brakedown_detail 
								   where t_brakedown_detail.no_lap_baru = t_pemotongan_log_detail_potong.no_lap_baru)";
				// $param['where'] = "kayu_id = $kayu_id AND no_lap_baru not in (select no_lap_baru from t_brakedown_detail) AND alokasi = 'Sawmill'";
				$param['group'] = "GROUP BY pemotongan_log_detail_potong_id, no_lap_baru, no_barcode_baru, grading_rule, volume_baru";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('modalNoLap',['tr_seq'=>$tr_seq,'kayu_id'=>$kayu_id, 'id'=>$id, 'edit'=>$edit]);
		}
	}

	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $brakedown_id = Yii::$app->request->post('brakedown_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
            $model = \app\models\TBrakedown::findOne($brakedown_id);
            $modDetails = \app\models\TBrakedownDetail::find()->where(['brakedown_id' => $brakedown_id])->all();
            $data['html'] = '';
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
					$data['html'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$detail,'i'=>$i,'edit'=>$edit]);
                }
            }
            return $this->asJson($data);
        }
    }

	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TBrakedown::tableName();
				$param['pk']= $param['table'].".". \app\models\TBrakedown::primaryKey()[0];
				$param['column'] = [$param['table'].'.brakedown_id',					//0
									$param['table'].'.kode',							//1
									't_spk_sawmill.kode as kode_spk',					//2
									'm_kayu.kayu_nama',									//3
									$param['table'].'.tanggal',							//4
                                    $param['table'].'.line_sawmill',					//5
                                    'm_pegawai.pegawai_nama',					        //6
                                    $param['table'].'.cancel_transaksi_id',				//7
									];
				$param['join']= ['JOIN t_spk_sawmill ON t_spk_sawmill.spk_sawmill_id = '.$param['table'].'.spk_sawmill_id
								  JOIN m_kayu ON m_kayu.kayu_id = '.$param['table'].'.kayu_id
								  JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.prepared_by'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

	public function actionCancelBrakedown(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->get('id');
			$model = \app\models\TBrakedown::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_brakedown
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $model->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
                            if($model->updateAttributes(['cancel_transaksi_id'=>$modCancel->cancel_transaksi_id, 'approval_status'=>$modCancel->status])){
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
                        $data['message'] = Yii::t('app', 'Brakedown Berhasil di Batalkan');
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
			
			return $this->renderAjax('cancelBrakedown',['model'=>$model,'modCancel'=>$modCancel]);
		}
	}

	public function actionPrintBrakedown()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = \app\models\TBrakedown::findOne($_GET['id']);
		$modDetail = \app\models\TBrakedownDetail::findAll(['brakedown_id'=>$_GET['id']]);
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'Brakedown');
        if ($caraprint == 'PRINT') {
            return $this->render('print', ['model' => $model, 'paramprint' => $paramprint, 'modDetail'=>$modDetail]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf; 
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('print', ['model' => $model, 'paramprint' => $paramprint, 'modDetail'=>$modDetail]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('print', ['model' => $model, 'paramprint' => $paramprint, 'modDetail'=>$modDetail]);
        }
    }

	public function actionSetSPK(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->post('spk_sawmill_id');
			$data = [];
			$modSpk = \app\models\TSpkSawmill::findOne($id);
			if(!empty($modSpk)){
				$data = $modSpk;
				return $this->asJson($data);
			}
		}
	}
}