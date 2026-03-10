<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class VoucherpenerimaanController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TVoucherPenerimaan();
        $modKurs = new \app\models\HKursRupiah();
        $model->tanggal = date('d/m/Y');
		$model->kode_bbm = "-";
		$model->total_nominal = 0;
		
        if(isset($_GET['voucher_penerimaan_id'])){
            $model = \app\models\TVoucherPenerimaan::findOne($_GET['voucher_penerimaan_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->total_nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_nominal);
        }
        
		$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
		if( isset($form_params['TVoucherPenerimaan']) ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_voucher_penerimaan
				
                $post = $form_params['TVoucherPenerimaan'];
				if(count($post)>0){
					foreach($post as $peng){ $post = $peng; }
					$model = new \app\models\TVoucherPenerimaan();
					$model->attributes = $post;
					$model->kode = \app\components\DeltaGenerator::kodeVoucherPenerimaan();
					$model->kode_bbm = \app\components\DeltaGenerator::kodeBuktiBankMasuk($model->akun_kredit,$model->tanggal);
					if(!empty($post['voucher_penerimaan_id'])){
						$model = \app\models\TVoucherPenerimaan::findOne($post['voucher_penerimaan_id']);
						$model->attributes = $post;
					}
					if($model->validate()){
						if($model->save()){
							$success_1 = true;
						}
					}else{
						$success_1 = false;
						$data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
					}
				}
				
                if ($success_1) {
					$transaction->commit();
					$data['status'] = true;
					$data['kode_bbm'] = $model->kode_bbm;
					$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
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
		return $this->render('index',['model'=>$model,'modKurs'=>$modKurs]);
	}
	
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $tgl = Yii::$app->request->post('tgl');
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($tgl)){
                $models = \app\models\TVoucherPenerimaan::find()->where(['tanggal'=>$tgl])->orderBy(['voucher_penerimaan_id'=>SORT_ASC])->all();
                if(count($models)>0){
                    foreach($models as $i => $model){
						$data['html'] .= $this->renderPartial('_item',['model'=>$model,'i'=>$i,'disabled'=>$disabled]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionSetKurs(){
		if(\Yii::$app->request->isAjax){
            $tgl = Yii::$app->request->post('tgl');
            $tgl = \app\components\DeltaFormatter::formatDateTimeForDb($tgl);
            $data = [];
            $data['kurs'] = 0;
			$modKurs = \app\models\HKursRupiah::findOne(['tanggal'=>$tgl]);
			if(!empty($modKurs)){
				$data['kurs'] = $modKurs['usd'];
			}else{
				if($tgl == date('Y-m-d')){
					$kurs = \app\components\DeltaFunctions::kursNow();
					if($kurs['last_update'] == $tgl){
						$data['kurs'] = \app\components\DeltaFormatter::formatNumberForUserFloat($kurs['kurs_tengah']);
						$modKurs = new \app\models\HKursRupiah();
						$modKurs->tanggal = $tgl;
						$modKurs->usd = \app\components\DeltaFormatter::formatNumberForDb2($data['kurs']);
						$modKurs->save();
					}
				}
			}
            return $this->asJson($data);
        }
    }
	
	public function actionSaveKurs(){
		if(\Yii::$app->request->isAjax){
            $tgl = Yii::$app->request->post('tgl');
            $tgl = \app\components\DeltaFormatter::formatDateTimeForDb($tgl);
			$usd = Yii::$app->request->post('usd');
			$data = '';
			$modKurs = \app\models\HKursRupiah::findOne(['tanggal'=>$tgl]);
			if(!empty($modKurs)){
				$modKurs->usd = $usd;
				$data = $modKurs->save();
			}else{
				$modKurs = new \app\models\HKursRupiah();
				$modKurs->tanggal = $tgl;
				$modKurs->usd = $usd;
				$data = $modKurs->save();
			}
            return $this->asJson($data);
        }
    }
	
	public function actionAddItem(){
		if(\Yii::$app->request->isAjax){
			$data = [];
            $data['html'] = '';
			$tgl = Yii::$app->request->post('tgl');
			$usd = Yii::$app->request->post('usd');
			$model = new \app\models\TVoucherPenerimaan();
			$model->tanggal = $tgl;
			$data['html'] = $this->renderPartial('_item',['model'=>$model]);
			return $this->asJson($data);
		}
	}
	
	public function actionDeleteItem($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TVoucherPenerimaan::findOne($id);
			$modKuitansi = \app\models\TKuitansi::findOne(['cara_bayar'=>'Transfer Bank','reff_penerimaan'=>$model->kode]);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = true;
					if($model->delete()){
						$success_1 = true;
						if(!empty($modKuitansi)){
							if($modKuitansi->delete()){
								$success_2 = true;
							}else{
								$success_2 = false;
							}
						}
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					if ($success_1 && $success_2) {
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
	
	public function actionDetailBbm(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$kode = Yii::$app->request->get('id');
			$modTerima = \app\models\TVoucherPenerimaan::findOne(['kode'=>$kode]);
			$kode_bbm = $modTerima->kode_bbm;
            $thn = date("Y-m-d", strtotime($modTerima->tanggal));
			$model = \app\models\TVoucherPenerimaan::find()->where("kode_bbm = '{$kode_bbm}' AND TO_CHAR(tanggal::DATE,'yyyy-mm-dd')='{$thn}' ")->orderBy("kode ASC")->all();
			$modKurs = \app\models\HKursRupiah::findOne(['tanggal'=>$model[0]->tanggal]);
			$usd = !empty($modKurs)?$modKurs['usd']:0;
			$paramprint['judul'] = Yii::t('app', 'BUKTI BANK MASUK');
			if(count($model)>0){
				return $this->renderAjax('detailBbm',['model'=>$model,'paramprint'=>$paramprint,'usd'=>$usd]);
			}
        }
	}
	
	public function actionPrintBbm($id){
		$this->layout = '@views/layouts/metronic/print';
		$modTerima = \app\models\TVoucherPenerimaan::findOne(['kode'=>$id]);
        $kode_bbm = $modTerima->kode_bbm;
        $thn = date("Y-m-d", strtotime($modTerima->tanggal));
        $model = \app\models\TVoucherPenerimaan::find()->where("kode_bbm = '{$kode_bbm}' AND TO_CHAR(tanggal::DATE,'yyyy-mm-dd')='{$thn}' ")->orderBy("kode ASC")->all();
		$modKurs = \app\models\HKursRupiah::findOne(['tanggal'=>$model[0]->tanggal]);
		$usd = !empty($modKurs)?$modKurs['usd']:0;
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'BUKTI BANK MASUK');
		if($caraprint == 'PRINT'){
			return $this->render('printBbm',['model'=>$model,'paramprint'=>$paramprint,'usd'=>$usd]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printBbm',['model'=>$model,'paramprint'=>$paramprint,'usd'=>$usd]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printBbm',['model'=>$model,'paramprint'=>$paramprint,'usd'=>$usd]);
		}
	}
	
	public function actionPrintoutLaporan(){
		$this->layout = '@views/layouts/metronic/print';
		$caraprint = Yii::$app->request->get('caraprint');
		$tgl = Yii::$app->request->get('tgl');
		$model = \app\models\TVoucherPenerimaan::find()->where("tanggal = '".$tgl."' AND cancel_transaksi_id IS NULL")->orderBy("kode ASC")->all();
		$modKurs = \app\models\HKursRupiah::find()->where("tanggal = '{$tgl}'")->one();
		$usd = !empty($modKurs)?$modKurs['usd']:0;
		$paramprint['judul'] = Yii::t('app', 'Laporan Voucher Penerimaan');
		$paramprint['judul2'] = "Tanggal ". \app\components\DeltaFormatter::formatDateTimeForUser($tgl);
		if($caraprint == 'PRINT'){
			return $this->render('printLaporan',['model'=>$model,'paramprint'=>$paramprint,'usd'=>$usd]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printLaporan',['model'=>$model,'paramprint'=>$paramprint,'usd'=>$usd]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printLaporan',['model'=>$model,'paramprint'=>$paramprint,'usd'=>$usd]);
		}
	}
	
	public function actionInfoPiutang(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->get('id');
			$kode = Yii::$app->request->get('kode');
			if(!empty($kode)){
				$model = \app\models\TVoucherPenerimaan::findOne(['kode'=>$kode]);
				$sql = "SELECT * FROM t_piutang_penjualan
						JOIN t_voucher_penerimaan ON t_piutang_penjualan.payment_reff = t_voucher_penerimaan.kode
						WHERE t_voucher_penerimaan.kode = '".$kode."'";
			}else{
				$model = \app\models\TVoucherPenerimaan::findOne($id);
				$sql = "SELECT * FROM t_piutang_penjualan
						JOIN t_voucher_penerimaan ON t_piutang_penjualan.payment_reff = t_voucher_penerimaan.kode
						WHERE t_voucher_penerimaan.voucher_penerimaan_id = ".$id;
			}
			
			$modPiutang = Yii::$app->db->createCommand($sql)->queryAll();
			return $this->renderAjax('infoPiutang',['modPiutang'=>$modPiutang,'model'=>$model]);
        }
	}
	
}
