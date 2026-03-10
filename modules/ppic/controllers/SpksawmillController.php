<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class SpksawmillController extends DeltaBaseController
{
    public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TSpkSawmill();
        $modDetail = new \app\models\TSpkSawmillDetail();
        $model->kode = 'Auto Generate';
        $model->tanggal_mulai = date("d/m/Y");
        $model->tanggal_selesai = date("d/m/Y");
        $model->peruntukan = 'Lokal';
        $model->refisi_ke = 0;
        $modelApproval = [];

        if(isset($_GET['spk_sawmill_id'])){
            $model = \app\models\TSpkSawmill::findOne($_GET['spk_sawmill_id']);
            $model->tanggal_mulai = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_mulai);
            $model->tanggal_selesai = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_selesai);
            $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
        }

        if( Yii::$app->request->post('TSpkSawmill')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;     // t_spk_sawmill
                $success_2 = false;     // t_spk_sawmill_detail
                $success_3 = false;     // t_approval

                $model->load(\Yii::$app->request->post());

                if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeSpkSawmill();
				}
                $model->approval_status = 'Not Confirmed';
                $model->status_spk = true;
                $model->prepared_by = Yii::$app->user->identity->pegawai_id;
                $model->approved_by1 = \app\components\Params::DEFAULT_PEGAWAI_ID_KADEP_PPIC;
                $model->approved_by2 = \app\components\Params::DEFAULT_PEGAWAI_ID_KADEP_SAWMILL;
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;

                        if(isset($_GET['edit'])){
                            \app\models\TSpkSawmillDetail::deleteAll("spk_sawmill_id = ".$model->spk_sawmill_id);
                        }
                        // print_r($_POST['TSpkSawmillDetail']);exit;
                        foreach($_POST['TSpkSawmillDetail'] as $i => $detail){
                            $size = $_POST['TSpkSawmillDetail'][$i]['size'];
                            $arr_size = preg_split('/x/i', $size);
                            $produk_t = $arr_size[0];
                            $produk_l = $arr_size[1];
                            $panjang = $_POST['TSpkSawmillDetail'][$i]['panjang'];
                            $arr_panjang = $panjang;
                            foreach($arr_panjang as $p => $pjg){
                                $modDetail = new \app\models\TSpkSawmillDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->spk_sawmill_id = $model->spk_sawmill_id;
                                $modDetail->kayu_id = $model->kayu_id;
                                $modDetail->produk_sawmill = $model->produk_sawmill;
                                $modDetail->produk_t = $produk_t;
                                $modDetail->produk_l = $produk_l;
                                $modDetail->produk_p = $pjg;
                                if($modDetail->validate()){
                                    if($modDetail->save()){
                                        $success_2 = true;
                                    }
                                }
                            }
                        }

                        // START approval
                        $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
                        if(count($modelApproval)>0){ // edit mode
							if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
								$success_3 = $this->saveApproval($model);
							}
						}else{ // insert mode
							$success_3 = $this->saveApproval($model);
						}
                        // EO approval
                    }
                }

                // print_r('1');
                // print_r($success_1);
                // print_r('2');
                // print_r($success_2);
                // print_r('3');
                // print_r($success_3);
                // print_r($modDetail);
                // exit;
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'spk_sawmill_id'=>$model->spk_sawmill_id]);
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

    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TSpkSawmill();
            $modDetail = new \app\models\TSpkSawmillDetail();
            $modDetail->kategori_ukuran = 'Utama';
            $data['item'] = $this->renderPartial('_item',['model'=>$model,'modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }

    public function saveApproval($model){
		$success = false;
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->approved_by1;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = date("d/m/Y");
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success = $modelApproval->createApproval();
		if($model->approved_by2){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approved_by2;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = date("d/m/Y");
			$modelApproval->level = 2;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		return $success;
	}

    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TSpkSawmill::tableName();
				$param['pk']= $param['table'].".". \app\models\TSpkSawmill::primaryKey()[0];
				$param['column'] = [$param['table'].'.spk_sawmill_id',					//0
									$param['table'].'.kode',							//1
									$param['table'].'.refisi_ke',						//2
									$param['table'].'.tanggal_mulai',					//3
                                    $param['table'].'.tanggal_selesai',					//4
                                    $param['table'].'.pemenuhan_po',					//5
                                    $param['table'].'.peruntukan',					    //6
                                    $param['table'].'.line_sawmill',					//7
                                    $param['table'].'.status_spk',					    //8
                                    $param['table'].'.approval_status',					//9
                                    'm_pegawai.pegawai_nama',					        //10
                                    $param['table'].'.cancel_transaksi_id',				//11
									];
				$param['join']= ['JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.prepared_by'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

    function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $spk_sawmill_id = Yii::$app->request->post('spk_sawmill_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
            $model = \app\models\TSpkSawmill::findOne($spk_sawmill_id);
            $data['model'] = $model;
            $modDetails = \app\models\TSpkSawmillDetail::find()
                                    ->select(['produk_t', 'produk_l', 'kategori_ukuran'])
                                    ->where(['spk_sawmill_id' => $spk_sawmill_id])
                                    ->groupBy(['produk_t', 'produk_l', 'kategori_ukuran'])
                                    ->all();
            $data['html'] = '';
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
					$data['html'] .= $this->renderPartial('_item',['model'=>$model, 'modDetail'=>$detail,'i'=>$i,'edit'=>$edit]);
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionCancelSPK($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpkSawmill::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_spk_sawmill
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
                        $data['message'] = Yii::t('app', 'SPK Sawmill Berhasil di Batalkan');
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
			
			return $this->renderAjax('cancelSPK',['model'=>$model,'modCancel'=>$modCancel]);
		}
	}

    public function actionSetStatus($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpkSawmill::findOne($id);
            if(!$model->status_spk){
                $close = json_decode($model->status_spk_close, true);
                $model->status_spk_close = $close['reason'];
            }
			if( Yii::$app->request->post('TSpkSawmill')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
					if($_POST['TSpkSawmill']['status_spk'] == 1){
						$model->status_spk = true;
                        $model->status_spk_close = null;
					} else {
						$model->status_spk = false;
                        $status_spk_close = $_POST['TSpkSawmill']['status_spk_close'];
                        $arrPost = ['by'=> Yii::$app->user->identity->pegawai_id,
                                    'at'=>date('Y-m-d H:i:s'),
                                    'reason'=>$status_spk_close
                                    ];
                        $model->status_spk_close = json_encode($arrPost);
					}
					
					if($model->validate()){
						if($model->save()){
							$success_1 = true;
						}
					}
				
					// print_r($success_1); exit;
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                    }
                } catch (yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('setStatus',['model'=>$model]);
		}
    }

    public function actionPrintSPK()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = \app\models\TSpkSawmill::findOne($_GET['id']);
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'SPK Sawmill');
        if ($caraprint == 'PRINT') {
            return $this->render('printSPK', ['model' => $model, 'paramprint' => $paramprint]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('printSPK', ['model' => $model, 'paramprint' => $paramprint]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('printSPK', ['model' => $model, 'paramprint' => $paramprint]);
        }
    }

    public function actionSetRevisi(){
        $id = Yii::$app->request->post('id');
        $data = [];

        $revisi = \app\models\TSpkSawmill::findOne($id);
        $data = $revisi->refisi_ke;
        return $this->asJson($data);
    }

    public function actionAddListSize(){
        $model = new \app\models\MDefaultValue();
        return $this->renderAjax('createListSize', ['model'=>$model]);
    }

    public function actionSaveDefaultSize(){
        $model = new \app\models\MDefaultValue();
        $data = [];

        if( Yii::$app->request->post('MDefaultValue')){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; //m_default_value

                $model->type = 'size-sawmill';
                $size_p = isset($_POST['MDefaultValue']['size_p'])?trim($_POST['MDefaultValue']['size_p']):null;
                $size_l = isset($_POST['MDefaultValue']['size_l'])?trim($_POST['MDefaultValue']['size_l']):null;
                $size = $size_p.'x'.$size_l;
                //cek dulu sdh ada di db blm
                $modDefault = \app\models\MDefaultValue::findOne(['value'=>$size]);
                if(empty($modDefault)){
                    $model->name= $size;
                    $model->name_en = $size;
                    $model->value = $size;
                    $modSeq = Yii::$app->db->createCommand("select max(sequence_number) as seq from m_default_value where type = 'size-sawmill'")->queryOne();
                    $model->sequence_number = $modSeq['seq'] + 1;
                    $model->active = true;
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;

                             $transaction->commit();
                            $data = [
                                'status' => true,
                                'id'     => $model->default_value_id,
                                'name'   => $model->name_en,
                                'msg'    => 'Data ok'
                            ];
                        } else {
                            $transaction->rollback();
                            $data = [
                                'status' => false,
                                'msg'    => 'Gagal menyimpan data'
                            ];
                        }
                    }
                } else {
                    $transaction->rollback();
                    $data = [
                        'status' => false,
                        'msg'    => 'Sudah ada di list'
                    ];
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                $data['status']     = false;
                $data['message']    = $ex;
            }
        }
        return $this->asJson($data);
    }
}