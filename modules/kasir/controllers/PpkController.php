<?php

namespace app\modules\kasir\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PpkController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TPpk();
        $model->tipe = "Kas Kecil";
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->tanggal_diperlukan = date('d/m/Y');
        $model->nominal = 0;
        
		if(isset($_GET['kas_bon_id'])){
			$modKasBon = \app\models\TKasBon::findOne($_GET['kas_bon_id']);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($modKasBon->tanggal);
			$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($modKasBon->nominal);
			$model->kas_bon_id = $modKasBon->kas_bon_id;
			$model->tipe = $modKasBon->tipe;
			if($modKasBon->tipe=='KK'){
				$model->tipe = 'Kas Kecil';
			}else if($modKasBon->tipe=='KB'){
				$model->tipe = 'Kas Besar';
			}
		}
		if(isset($_GET['tgl'])){
			$model->tanggal = date('d/m/Y', strtotime($_GET['tgl']));
		}
		if(isset($_GET['nominal'])){
			$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($_GET['nominal']);
		}
		
		if(isset($_GET['ppk_id'])){
            $model = \app\models\TPpk::findOne($_GET['ppk_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_diperlukan = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_diperlukan);
            $model->nominal = \app\components\DeltaFormatter::formatNumberForUser($model->nominal);
        }
		
		if( Yii::$app->request->post('TPpk')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $model->load(\Yii::$app->request->post());
                $model->kode = \app\components\DeltaGenerator::kodePpk();
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                    }
                }
//				echo "<pre>";
//				print_r($success_1);
//				exit;
                if ($success_1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data PPK Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'ppk_id'=>$model->ppk_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionDaftarPpk(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-ppk'){
				$param['table']= \app\models\TPpk::tableName();
				$param['pk']= \app\models\TPpk::primaryKey()[0];
				//$param['column'] = ['ppk_id',$param['table'].'.tipe',$param['table'].'.kode',['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],$param['table'].'.nominal',['col_name'=>'tanggal_diperlukan','formatter'=>'formatDateForUser2'],'t_voucher_pengeluaran.status_bayar','is_terimatopup'];
                $param['column'] = ['ppk_id',
                                    $param['table'].'.tipe',
                                    $param['table'].'.kode',
                                    ['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],
                                    $param['table'].'.nominal',
                                    ['col_name'=>'tanggal_diperlukan','formatter'=>'formatDateForUser2'],
                                    't_voucher_pengeluaran.status_bayar','is_terimatopup',
                                    $param['table'].'.cancel_transaksi_id',
                                    "(select split_part(pegawai_nama, ' ', 1) as pegawai_nama from t_cancel_transaksi a left join m_pegawai b on b.pegawai_id = a.cancel_by where a.reff_no = t_ppk.kode ) as xxx",
                                    '(select cancel_reason from t_cancel_transaksi where reff_no = t_ppk.kode ) as yyy',
                                    ['col_name'=>'(select cancel_at from t_cancel_transaksi where reff_no = t_ppk.kode ) as zzz','formatter'=>'formatDateForUser2']
                                    ];
				$param['join']= ['LEFT JOIN t_voucher_pengeluaran ON t_voucher_pengeluaran.voucher_pengeluaran_id = '.$param['table'].'.voucher_pengeluaran_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarPpk');
        }
    }
	
	public function actionDetailppk($id){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TPpk::findOne($id);
			if(!empty($model)){
				$caraprint = Yii::$app->request->get('caraprint');
				$paramprint['judul'] = Yii::t('app', 'Permintaan Penambahan Kas');
				return $this->renderAjax('detailppk',['model'=>$model,'paramprint'=>$paramprint]);
			}else{
				return false;
			}
        }
	}
	
	public function actionPrintout(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TPpk::findOne($_GET['id']);
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'PERMINTAAN PENAMBAHAN KAS');
		if($caraprint == 'PRINT'){
			return $this->renderPartial('printout',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
	
	public function actionTerimaTopup(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->get('id');
			$model = \app\models\TPpk::findOne($id);
			$modVoucher = \app\models\TVoucherPengeluaran::findOne($model->voucher_pengeluaran_id);
			$modKasKecil = new \app\models\TKasKecil();
			$modKasKecil->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($modVoucher->tanggal_bayar);
			$pesan = "Anda akan melakukan penerimaan Uang dengan nominal <b>Rp. ".\app\components\DeltaFormatter::formatNumberForUser($modVoucher->total_nominal)."</b> ?";
            if( isset($_POST['TPpk']) ){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_kas_kecil
                    $success_2 = false; // t_ppk
					
					if($model->tipe == 'Kas Kecil'){
						if(!empty($modVoucher)){
							$modKasKecil->kode = "-";
							$modKasKecil->tanggal = \app\components\DeltaFormatter::formatDateTimeForDb($_POST['TKasKecil']['tanggal'])." 00:00:01";
							$modKasKecil->penerima = Yii::$app->user->identity->pegawai->pegawai_nama;
							$modKasKecil->deskripsi = "TOPUP SALDO";
							$modKasKecil->closing = FALSE;
							$modKasKecil->tipe = "IN";
							$modKasKecil->jenis = "TOPUP";
							$modKasKecil->nominal = $modVoucher->total_nominal;
							$modKasKecil->seq = \app\components\DeltaGenerator::sequenceKasKecil($modKasKecil->tanggal);
							if($modKasKecil->validate()){
								if($modKasKecil->save()){
									$success_1 = TRUE;
								}else{
									$success_1 = FALSE;
								}
							}else{
								$success_1 = FALSE;
							}
						}
					}else{
						$success_1 = true;
					}
					
					if(!empty($model)){
						$model->is_terimatopup = true;
						if($model->validate()){
							if($model->save()){
								$success_2 = TRUE;
							}else{
								$success_2 = FALSE;
							}
						}else{
							$success_2 = FALSE;
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
						$data['message'] = Yii::t('app', 'Data Berhasil diterima');
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
			return $this->renderAjax('_terimaTopup',['model'=>$model,'modVoucher'=>$modVoucher,'modKasKecil'=>$modKasKecil,'id'=>$id,'pesan'=>$pesan,'actionname'=>'terimaTopup','tableid'=>'table-aftersave']);
		}
	}

    public function actionConfirmBatal($id){
        if(\Yii::$app->request->isAjax){
			return $this->renderAjax('_confirmBatal',['id'=>$id]);
        }
    }

    public function actionBatalYes() {
        $ppk_id = Yii::$app->request->post('id');
        $cancel_reason = Yii::$app->request->post('cancel_reason');
		if(\Yii::$app->request->isAjax){
            // kode adjustmentLog
            $model = \app\models\TPpk::findOne(["ppk_id"=>$ppk_id]);
            $reff_no = $model->kode;

            // cek ulang, sudah ada di voucher_pengeluaran_id atau belum di table t_ppk yang bersangkutan
            $sql_cek = "select voucher_pengeluaran_id from t_ppk where ppk_id = '".$ppk_id."' ";
            $cek = Yii::$app->db->createCommand($sql_cek)->queryScalar();

            $data = [];
            $data['msg'] = "";
            $data['html'] = "";
            if ($cek > 0) {
                $msg = "Data gagal dibatalkan";
            } else {
                // input t_cancel_transaksi
                $username = $_SESSION['sess_username'];
                $m_pegawai = \app\models\MUser::findByUsername($username);
                $pegawai_id = $m_pegawai->pegawai_id;
                $now = date('Y-m-d H:i:s');
                $sql_insert = "insert into t_cancel_transaksi ". 
                                "   (cancel_by, cancel_at, cancel_reason, reff_no, status, created_at, created_by, updated_at, updated_by) ".
                                "   values ". 
                                "   ($pegawai_id, '".$now."', '".$cancel_reason."', '".$reff_no."', 'ABORTED', '".$now."', $pegawai_id, '".$now."', $pegawai_id) ";
                $success_1 = Yii::$app->db->createCommand($sql_insert)->execute();

                // ambil cancel_transaksi_id
                $t_cancel_transaksi = \app\models\TCancelTransaksi::findOne(['reff_no'=>$reff_no]);
                $cancel_transaksi_id = $t_cancel_transaksi->cancel_transaksi_id;

                // update status t_ppk batal
                $sql_update_ppk = "update t_ppk set cancel_transaksi_id = ".$cancel_transaksi_id." where ppk_id = ".$ppk_id." ";
                $success_2 = Yii::$app->db->createCommand($sql_update_ppk)->execute();
                if ($success_1 && $success_2) {
                    $msg = "Data berhasil dibatalkan";
                } else {
                    $msg = "Data gagal dibatalkan";
                }
            }

            $data['msg'] = $msg;
		}
        return $this->asJson($data);
        //return $this->redirect(['index','success'=>1,'ppk_id'=>$model->ppk_id]);
    }
    
}
