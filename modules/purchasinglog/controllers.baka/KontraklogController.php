<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class KontraklogController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TLogKontrak();
		$modCompany = \app\models\CCompanyProfile::findOne(\app\components\Params::DEFAULT_COMPANY_PROFILE);
		$model->kode = "Auto Generate";
		$model->kode_cardpad = "Auto Generate";
                $model->nomor = 'No.xxx/xxx-CWM/X/'. date('Y');
                $model->tanggal = date('d/m/Y');
                $model->tanggal_po = date('d/m/Y');
		$model->pihak2_pegawai = \app\components\Params::DEFAULT_PEGAWAI_ID_DIREKTUR_UTAMA;
		$model->pihak2_pegawai2 = \app\components\Params::DEFAULT_PEGAWAI_ID_DIREKTUR_MANUFAKTUR;
		$model->pihak2_perusahaan = $modCompany->name;
		$model->pihak2_alamat = $modCompany->alamat;
		
		if(isset($_GET['log_kontrak_id'])){
                    $model = \app\models\TLogKontrak::findOne($_GET['log_kontrak_id']);
                    $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
                    $model->tanggal_po = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_po);
                    $model->nama_iuphhk = $model->hasilOrientasi->nama_iuphhk;
                    $model->nama_ipk = $model->hasilOrientasi->nama_ipk;
                }
		
            if( Yii::$app->request->post('TLogKontrak') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $success_2 = true; // t_pengajuan_pembelianlog
                $model->load(\Yii::$app->request->post());
                if(!isset($_GET['edit'])){
                    $model->kode = \app\components\DeltaGenerator::kodePOLogAlam();
                    $model->kode_cardpad = \app\components\DeltaGenerator::kodePOCardpad();
                }
				$model->file1 = \yii\web\UploadedFile::getInstance($model, 'uploadfile');
                if($model->validate()){
                    $model->kode_cardpad = "";
                    if($model->save()){
                        $success_1 = true;
                        $modKeputusan = \app\models\TPengajuanPembelianlog::findOne(['log_kontrak_id'=>$model->log_kontrak_id]);
                        if(!empty($modKeputusan)){
                            $modKeputusan->nomor_kontrak = $model->nomor;
                            if($modKeputusan->validate()){
                                if($modKeputusan->save()){
                                    $success_2 = true;
                                }
                            }
                        }
                    }
                }
//                echo "<pre>1";
//                print_r($success_1);
//                echo "<pre>2";
//                print_r($success_2);
//                exit;
                if ($success_1 && $success_2) {
                    if(!empty($model->file1)){
						$randomstring_file = Yii::$app->getSecurity()->generateRandomString(4);
						$dir_path = Yii::$app->basePath.'/web/uploads/pur/kontraklog';
						$file_path = $dir_path.'/'.date('Ymd_His').'-file-'.$randomstring_file.'.'  . $model->file1->extension;
						$model->file1->saveAs($file_path,false);
						$model->uploadfile = date('Ymd_His').'-file-'.$randomstring_file.'.' .$model->file1->extension;
					}
					if($model->update() !== false){
						$transaction->commit();
						Yii::$app->session->setFlash('success', Yii::t('app', 'Data Kontrak Berhasil disimpan'));
						return $this->redirect(['index','success'=>1,'log_kontrak_id'=>$model->log_kontrak_id]);
					}
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		
		return $this->render('index',['model'=>$model,'modCompany'=>$modCompany]);
	}
	
	public function actionPickPanel(){
        if(\Yii::$app->request->isAjax){
			$model = new \app\models\TLogKontrak();
			$modItems = \app\models\MSuplier::find()->where(['type'=>'LA','active'=>TRUE])->all();
			return $this->renderAjax('pickPanel',['model'=>$model,'modItems'=>$modItems]);
        }
    }
	
	public function actionSetPickedItem(){
		if(\Yii::$app->request->isAjax){
			$suplier_id = Yii::$app->request->post('suplier_id');
			$data = '';
			$modSuplier = \app\models\MSuplier::findOne($suplier_id);
			if(!empty($modSuplier)){
				$data = $modSuplier->attributes;
			}
			return $this->asJson($data);
        }
	}
	
	public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TLogKontrak::tableName();
				$param['pk']= "t_log_kontrak.log_kontrak_id";
				$param['column'] = ['t_log_kontrak.log_kontrak_id','t_log_kontrak.kode','t_log_kontrak.nomor','t_log_kontrak.tanggal','t_log_kontrak.pihak1_nama','t_log_kontrak.pihak1_perusahaan','t_log_kontrak.kode_cardpad',
                                    '(SELECT status FROM t_approval WHERE t_approval.reff_no = t_pengajuan_pembelianlog.kode AND t_approval.assigned_to = t_pengajuan_pembelianlog.by_owner) AS approval_owner'];
                $param['join']  =  ['LEFT JOIN t_pengajuan_pembelianlog ON t_pengajuan_pembelianlog.log_kontrak_id = t_log_kontrak.log_kontrak_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
    
    public function actionPrintConfirm($id){
		if(\Yii::$app->request->isAjax){
            $model = new \app\models\TLogKontrak();
			if( Yii::$app->request->post('TInvoice')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = true;
					$post = Yii::$app->request->post('TInvoice');
					if( count($post)>0 ){
						$model->data_peb = \yii\helpers\Json::encode($post);
						if($model->validate()){
							if($model->save()){
								$success_1 &= true;
							}
						}else{
							$success_1 = false;
						}
					}
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                        $data['callback'] = "$('#modal-konfirmsi').modal('hide'); window.open('".\yii\helpers\Url::toRoute('/exim/invoice/printsipeb')."?id={$model->invoice_id}&caraprint=PRINT','','location=_new, width=1200px, scrollbars=yes')";
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
			return $this->renderAjax('printConfirm',['model'=>$model]);
        }
    }
    
    public function actionPrint($id){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TPengajuanPembelianlog::findOne($id);
		$modPO = \app\models\TLogKontrak::findOne($model->log_kontrak_id);
        if(Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER){
            \app\models\TPrintout::createPrintout(['reff_no'=>$model->kode,'reff_no2'=>$modPO->kode,'parameter1'=>$model->nomor_kontrak]);
        }
        $caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'PURCHASE ORDER LOG ALAM');
		if($caraprint == 'PRINT'){
			return $this->render('printPo',['model'=>$model,'paramprint'=>$paramprint,'modPO'=>$modPO]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printPo',['model'=>$model,'paramprint'=>$paramprint,'modPO'=>$modPO]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printPo',['model'=>$model,'paramprint'=>$paramprint,'modPO'=>$modPO]);
		}
	}
	
	public function actionSetOrientasi($hasil_orientasi_id){
		if(\Yii::$app->request->isAjax){
			$data = [];
			if(!empty($hasil_orientasi_id)){
				$model = \app\models\THasilOrientasi::findOne($hasil_orientasi_id);
				if(!empty($model)){
					$data = $model->attributes;
				}
			}
			return $this->asJson($data);
		}
	}
    
    function actionGetAttch(){
		if(\Yii::$app->request->isAjax){
            $log_kontrak_id = Yii::$app->request->post('log_kontrak_id');
            $tipe = Yii::$app->request->post('tipe');
            $data = []; $data['html'] = ''; $data['qty'] = ''; $data['attch'] = '';
            if(!empty($log_kontrak_id)){
                $model = \app\models\TLogKontrak::findOne($log_kontrak_id);
            }
            $data['attch'] = $model->uploadfile;
            return $this->asJson($data);
        }
    }
	
}
