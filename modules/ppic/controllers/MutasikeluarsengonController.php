<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class MutasikeluarsengonController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TMutasiSengon();
		$model->tgl_awal = date('d/m/Y', strtotime('-30 days'));
		$model->tgl_akhir = date('d/m/Y');
        if((\Yii::$app->request->get('laporan_params')) !== null){
            $form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params);
            $model->attributes = $form_params['TMutasiSengon'];
            $model->tgl_awal = $form_params['TMutasiSengon']['tgl_awal'];
            $model->tgl_akhir = $form_params['TMutasiSengon']['tgl_akhir'];
            $data['html'] = $this->renderPartial('rekapContent',['model'=>$model]);
            return $this->asJson($data);
        }
		return $this->render('index',['model'=>$model]);
	}
    
    public function actionCreate(){
		if(\Yii::$app->request->isAjax){
                $model = new \app\models\TMutasiSengon();
                $model->kode = "AUTO GENERATE";
                $model->tanggal = date("d/m/Y");
                if( Yii::$app->request->post('TMutasiSengon')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_mutasi_sengon
                    $success_2 = true; // h_persediaan_log
                    $model->load(\Yii::$app->request->post());  
                    $JenisLog = $_POST['TMutasiSengon']['jenis_log'];
                    if($JenisLog  == 'Log Sengon'){
                        $model->kode = \app\components\DeltaGenerator::kodeMutasiSengon();
                    }else if($JenisLog  == 'Log Jabon'){
                        $model->kode = \app\components\DeltaGenerator::kodeMutasiJabon();
                    }
//                    echo "<pre>kode1:";
//                    print_r($model->kode);
//                    echo "<pre>kode:";
//                    print_r($JenisLog);
//                    exit;
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                            
                            
                            if($model->pcs > 0){
                                // START PROSESS STOCK (OUT)
                                $modStock = new \app\models\HPersediaanLog();
                                $modStock->tgl_transaksi = $model->tanggal;
                                
                                //kondisikan kayu_id
                                if($model->jenis_log == 'Log Sengon'){
                                    $modStock->kayu_id = \app\components\Params::DEFAULT_KAYU_ID_SENGON;
                                }else if($model->jenis_log == 'Log Jabon'){
                                    $modStock->kayu_id = \app\components\Params::DEFAULT_KAYU_ID_JABON;
                                }
//                                echo "<pre>kayu id:";
//                                print_r($modStock->kayu_id);
//                                exit;
                                
                                $modStock->no_grade = "-";
                                $modStock->no_barcode = "-";
                                $modStock->no_btg = "-";
                                $modStock->no_lap = "-";
                                $modStock->status = "OUT";
                                $modStock->reff_no = $model->kode;
                                $modStock->lokasi = $model->dari;
                                $modStock->fisik_diameter = $model->diameter;
                                $modStock->fisik_panjang = $model->panjang;
                                $modStock->fisik_reduksi = "-";
                                $modStock->fisik_volume = $model->m3;
                                $modStock->fisik_pcs = $model->pcs;
                                $modStock->keterangan = "MUTASI LOG DARI ".$model->dari." MENUJU ".$model->ke;
                                $success_2 &= \app\models\HPersediaanLog::updateStokPersediaan($modStock);
                                // END PROSESS STOCK (OUT)
                                
                                // START PROSESS STOCK (IN)
                                $modStock = new \app\models\HPersediaanLog();
                                $modStock->tgl_transaksi = $model->tanggal;
                                
                                //kondisikan kayu_id
                                if($model->jenis_log == 'Log Sengon'){
                                    $modStock->kayu_id = \app\components\Params::DEFAULT_KAYU_ID_SENGON;
                                }else if($model->jenis_log == 'Log Jabon'){
                                    $modStock->kayu_id = \app\components\Params::DEFAULT_KAYU_ID_JABON;
                                }
                                $modStock->no_grade = "-";
                                $modStock->no_barcode = "-";
                                $modStock->no_btg = "-";
                                $modStock->no_lap = "-";
                                $modStock->status = "IN";
                                $modStock->reff_no = $model->kode;
                                $modStock->lokasi = $model->ke;
                                $modStock->fisik_diameter = $model->diameter;
                                $modStock->fisik_panjang = $model->panjang;
                                $modStock->fisik_reduksi = "-";
                                $modStock->fisik_volume = $model->m3;
                                $modStock->fisik_pcs = $model->pcs;
                                $modStock->keterangan = "MUTASI LOG DARI ".$model->dari." MENUJU ".$model->ke;
                                $success_2 &= \app\models\HPersediaanLog::updateStokPersediaan($modStock);
                                // END PROSESS STOCK (IN)
                            }
                            
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
//                    echo "<pre>1:";
//                    print_r($success_1);
//                    echo "<pre>2:";
//                    print_r($success_2);
//                    echo "<pre>3:";
//                    print_r($model->jenis_log);
//                    echo "<pre>4:";
//                    print_r($modStock->kayu_id);
//                    exit;
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
//                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                        $data['callback'] = "$('#modal-master-create').modal('hide'); getRekap();";
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
			return $this->renderAjax('create',['model'=>$model]);
		}
	}
    
    public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\TMutasiSengon::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = true;
                        $modStock = \app\models\HPersediaanLog::find()->where("reff_no = '".$model->kode."'")->all();
                        if(!empty($modStock)){
                            $success_2 = \app\models\HPersediaanLog::deleteAll("reff_no = '".$model->kode."'");
                        }
                        if($model->delete()){
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data Gagal dihapus');
                        }
                        if ($success_1 && $success_2) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                            $data['callback'] = "getRekap();";
                        } else {
                            $transaction->rollback();
                            $data['status'] = false;
                            (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                            (isset($data['message_validate']) ? $data['message'] = null : '');
                        }
//                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid]);
		}
	}

    public function actionPrint(){
        $this->layout = '@views/layouts/metronic/print';
        $tgl_awal = Yii::$app->request->get('tgl_awal');
        $tanggal_awal = \app\components\DeltaFormatter::formatDateTimeForUser($tgl_awal);
        
        $tgl_akhir = Yii::$app->request->get('tgl_akhir');
        $tanggal_akhir = \app\components\DeltaFormatter::formatDateTimeForUser($tgl_akhir);
        
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'MUTASI SENGON/JABON');
        $model = \app\models\TMutasiSengon::find()->where("tanggal between '".$tgl_awal."' and '".$tgl_akhir."' ")->all();

        // jika print
        if($caraprint == 'PRINT'){
            return $this->render('print',['model'=>$model,'paramprint'=>$paramprint,'tgl_awal'=>$tgl_awal,'tgl_akhir'=>$tgl_akhir,'tanggal_awal'=>$tanggal_awal,'tanggal_akhir'=>$tanggal_akhir]);
        } 
        
        // jika pdf
        else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'].' PERIODE '.$tanggal_awal.' - '.$tanggal_akhir.'.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('print',['model'=>$model,'paramprint'=>$paramprint,'tgl_awal'=>$tgl_awal,'tgl_akhir'=>$tgl_akhir,'tanggal_awal'=>$tanggal_awal,'tanggal_akhir'=>$tanggal_akhir]);
            return $pdf->render();
        }

        // jika excel
        else if($caraprint == 'EXCEL'){
            return $this->render('print',['model'=>$model,'paramprint'=>$paramprint,'tgl_awal'=>$tgl_awal,'tgl_akhir'=>$tgl_akhir,'tanggal_awal'=>$tanggal_awal,'tanggal_akhir'=>$tanggal_akhir]);
        }
    }
}
