<?php

namespace app\modules\kasir\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PengeluarankaskecilController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TKasKecil();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
		$model->nominal = 0;
		
		if(isset($_GET['kas_kecil_id'])){
            $model = \app\models\TKasKecil::findOne($_GET['kas_kecil_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->nominal = \app\components\DeltaFormatter::formatNumberForUser($model->nominal);
        }
		
		$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
        if( isset($form_params['TKasKecil']) ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_kas_kecil
                $success_2 = false; // t_kas_bon
                $success_3 = false; // t_terima_bhp
                $success_4 = true; // t_pengajuan_tagihan
				$post = $form_params['TKasKecil'];
				if(count($post)>0){
					foreach($post as $peng){ $post = $peng; }
					$mod = new \app\models\TKasKecil();
					$mod->attributes = $post;
					$mod->kode = "-";
					$mod->closing = false;
					$mod->tipe = "OUT";
					$mod->jenis = "KELUAR";
					$mod->seq = \app\components\DeltaGenerator::sequenceKasKecil($mod->tanggal);
					if(!empty($post['kas_kecil_id'])){
						$mod = \app\models\TKasKecil::findOne($post['kas_kecil_id']);
						$mod->attributes = $post;
						if($mod->tipe == "IN"){
							$mod->nominal = $post['debit'];
						}
					}
					if($mod->validate()){
						if($mod->save()){
							$success_1 = true;
							if(!empty($post['kas_bon_id'])){
								$modSementara = \app\models\TKasBon::findOne($post['kas_bon_id']);
								$modSementara->kas_kecil_id = $mod->kas_kecil_id;
								if($modSementara->validate()){
									$success_2 = $modSementara->save();
								}else{
									$success_2 = false;
								}
							}else{
								$success_2 = true;
							}

							if(!empty($post['tbp_reff'])){
								$tbps = explode(",", $post['tbp_reff']);
								foreach($tbps as $i => $tbp){
									$modTerima = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>$tbp]);
									$modTerima->kas_kecil_id = $mod->kas_kecil_id;
									if($modTerima->validate()){
										$success_3 = $modTerima->save();
									}else{
										$success_3 = false;
									}
									
									$modPengajuanTagihan = \app\models\TPengajuanTagihan::findOne(['terima_bhp_id'=>$modTerima->terima_bhp_id]);
									$modPengajuanTagihan->status = "DIREALISASI";
									$modPengajuanTagihan->keterangan = "Direalisasi Pada ".date('d/m/Y H:i:s')." Oleh ".Yii::$app->user->identity->pegawai->pegawai_nama;
									if($modPengajuanTagihan->validate()){
										$success_4 = $modPengajuanTagihan->save();
									}else{
										$success_4 = false;
									}
								}
							}else{
								$modTerima = \app\models\TTerimaBhp::findOne(['kas_kecil_id'=>$mod->kas_kecil_id]);
								if(!empty($modTerima)){
									$success_3 = \app\models\TTerimaBhp::updateAll(['kas_kecil_id'=>null], 'kas_kecil_id = '.$mod->kas_kecil_id);
								}else{
									$success_3 = true;
								}
							}
						}
					}else{
						$success_1 = false;
						$data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
					}
				}
				
//                echo "<pre>";
//				print_r($success_1);
//                echo "<pre>";
//				print_r($success_2);
//                echo "<pre>";
//				print_r($success_3);
//                echo "<pre>";
//				print_r($success_4);
//				exit;
				
                if ($success_1 && $success_2 && $success_3 && $success_4) {
					$transaction->commit();
					$data['status'] = true;
					$data['kode'] = $mod->kode;
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
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $tgl = Yii::$app->request->post('tgl');
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($tgl)){
                //$modKasKecil = \app\models\TKasKecil::find()->where(['tanggal'=>$tgl])->orderBy(['bkk_id'=>SORT_ASC, 'seq'=>SORT_ASC])->all();
                $modKasKecil = \app\models\TKasKecil::find()->where(['tanggal'=>$tgl])->orderBy(['seq'=>SORT_ASC])->all();
                if(count($modKasKecil)>0){
                    foreach($modKasKecil as $i => $model){
						$modKasbon = \app\models\TKasBon::findOne(['kas_kecil_id'=>$model->kas_kecil_id]);
						if(!empty($modKasbon)){
							$model->kas_bon_id = $modKasbon->kas_bon_id;
						}
						$model->debit = 0;
						if($model->tipe == "IN"){
							$model->debit = \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal);
							$model->nominal = 0;
						}
						$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal);
						$data['html'] .= $this->renderPartial('_item',['model'=>$model,'i'=>$i,'disabled'=>$disabled,'modKasbon'=>$modKasbon]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionAddItem(){
		if(\Yii::$app->request->isAjax){
			$data = [];
            $data['html'] = '';
			$tgl = Yii::$app->request->post('tgl');
			$model = new \app\models\TKasKecil();
			$model->kode = "New Generate";
			$model->debit = 0;
			$model->nominal = 0;
			$model->tanggal = $tgl;
			$model->penerima = "";
			$data['html'] = $this->renderPartial('_item',['model'=>$model]);
			return $this->asJson($data);
		}
	}
	
	
	public function actionClosingConfirm($id){
		if(\Yii::$app->request->isAjax){
			$pesan = "Yakin akan melakukan <b>Closing Kasir</b> tanggal '<b>".\app\components\DeltaFormatter::formatDateTimeForUser($id)."</b>' ?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = true; // t_kas_kecil
                    $success_2 = true; // h_saldo_kaskecil
                    $success_3 = true; // t_uangtunai
                    $success_4 = true; // h_bonsementara
					$success_5 = false; // t_closing_kasir
					$debit = 0; $kredit = 0;
					$modelsIn = \app\models\TKasKecil::find()->where(['tanggal'=>$id,'tipe'=>"IN"])->orderBy(['seq'=>SORT_ASC])->all();
					$modelsOut = \app\models\TKasKecil::find()->where(['tanggal'=>$id,'tipe'=>"OUT"])->orderBy(['seq'=>SORT_ASC])->all();
					$uangTunai = \app\models\TUangtunai::find()->where(['tanggal'=>$id,'tipe'=>'KK'])->orderBy(['nominal'=>SORT_ASC])->all();
					$keluarSementara = \app\models\TKasBon::find()->where("kas_kecil_id IS NULL AND tipe = 'KK' AND (status_bon != 'PAID' OR status_bon IS NULL) ")->orderBy(['kas_bon_id'=>SORT_ASC])->all();
					if(count($modelsIn)>0){
						foreach($modelsIn as $i => $in){
							$in->kode = \app\components\DeltaGenerator::kodePengeluaranKasKecil($in->tanggal);
							$in->closing = TRUE;
							if($in->validate()){
								if($in->save()){
									$success_1 &= TRUE;
									$success_2 &= $this->saveSaldo($in);
								}else{
									$success_1 = FALSE;
								}
							}else{
								$success_1 = FALSE;
							}
							$debit += $in->nominal;
						}
					}
					if(count($modelsOut)>0){
						foreach($modelsOut as $i => $out){
							$out->kode = \app\components\DeltaGenerator::kodePengeluaranKasKecil($out->tanggal);
							$out->closing = TRUE;
							if($out->validate()){
								if($out->save()){
									$success_1 &= TRUE;
									$success_2 &= $this->saveSaldo($out);
								}else{
									$success_1 = FALSE;
								}
							}else{
								$success_1 = FALSE;
							}
							$kredit += $out->nominal;
						}
					}
					if(count($uangTunai)>0){
						foreach($uangTunai as $ii => $tunai){
							$tunai->closing = true;
							if($tunai->validate()){
								if($tunai->save()){
									$success_3 &= TRUE;
								}else{
									$success_3 = FALSE;
								}
							}else{
								$success_3 = FALSE;
							}
						}
					}
					if(count($keluarSementara)>0){
						foreach($keluarSementara as $iii => $sementara){
							$modBon = new \app\models\HBonsementara();
							$modBon->attributes = $sementara->attributes;
							$modBon->tanggal = $id;
							$modBon->tipe = "KK";
							$modBon->tanggal_kasbon = $sementara->tanggal;
							if($modBon->validate()){
								if($modBon->save()){
									$success_4 &= TRUE;
								}else{
									$success_4 = FALSE;
								}
							}else{
								$success_4 = FALSE;
							}
						}
					}
					
					$modClosing = New \app\models\TClosingKasir();
					$modClosing->kode = "-";
					$modClosing->tipe = "KK";
					$modClosing->tanggal = $id;
					$modClosing->debit = $debit;
					$modClosing->kredit = $kredit;
					if($modClosing->validate()){
						if($modClosing->save()){
							$success_5 = TRUE;
						}else{
							$success_5 = FALSE;
						}
					}else{
						$success_5 = FALSE;
					}
					
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					echo "<pre>";
//					print_r($success_3);
//					echo "<pre>";
//					print_r($success_4);
//					echo "<pre>";
//					print_r($success_5);
//					exit;
					
					if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( "#close-btn-modal" ).click(); setClosingBtn();';
						$data['message'] = Yii::t('app', "Data Berhasil di Closing");
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
			return $this->renderAjax('_closing',['id'=>$id,'pesan'=>$pesan]);
		}
	}
	
	public function saveSaldo($model){
		// Start insert saldo kas
		$modSaldo = new \app\models\HSaldoKaskecil();
		$modSaldo->attributes = $model->attributes;
		$modSaldo->reff_no = $model->kode;
		$modSaldo->tanggal = $model->tanggal." 01:00:00";
		$modSaldo->saldo = $modSaldo->SaldoAkhir - $modSaldo->kredit;
		if($model->tipe == "IN"){
			$modSaldo->kredit = 0;
			$modSaldo->debit = $model->nominal;
			$modSaldo->status = "TOPUP";
		}else if($model->tipe == "OUT"){
			$modSaldo->debit = 0;
			$modSaldo->kredit = $model->nominal;
			$modSaldo->status = "KELUAR";
		}
		if($modSaldo->validate()){
			if($modSaldo->save()){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		// End insert saldo kas
	}
	
	public function actionSetClosingBtn(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->post('tgl');
			$data['status'] = 0;
			$kas = \app\models\TKasKecil::find()->where(['tanggal'=>$tgl])->one();
			$tunai = \app\models\TUangtunai::find()->where(['tanggal'=>$tgl,'tipe'=>'KK'])->one();
			
			if(count($kas)>0){
				$data['status'] = ($kas->closing == true)?1:0;
			}
			if(count($tunai)>0){
				$data['status'] = ($tunai->closing == true)?1:0;
			}
			$data['today'] = ( \app\components\DeltaFormatter::formatDateTimeForDb($tgl) == date('Y-m-d') )?1:0;
			return $this->asJson($data);
		}
	}
	public function actionCheckClosing(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->post('tgl');
			$data = 0;
			$kas = \app\models\TKasKecil::find()->where("(tanggal < '".$tgl."' AND closing IS FALSE) OR (tanggal < '".$tgl."' AND closing IS NULL)")->one();
			if(count($kas)>0){
				$data = ($kas->closing == false)?1:0;
			}
			return $this->asJson($data);
		}
	}
	
	public function actionSementara(){
        $model = new \app\models\TKasBon();
        $model->kode = 'Auto Generate';
		$model->nominal = 0;
		
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-laporan'){
				$modPenegeluaranKasSementara = \app\models\TKasBon::kasbonGantung();
				$param['table']= \app\models\TKasBon::tableName();
				$param['pk']= \app\models\TKasBon::primaryKey()[0];
				$param['column'] = ['kas_bon_id',
									$param['table'].'.kode',
									$param['table'].'.tanggal',
									$param['table'].'.penerima',
									$param['table'].'.deskripsi',
									'nominal',
									'kas_bon_id',
									'kas_bon_id',
									$param['table'].'.gkk_id',
									't_gkk.kode AS gkk_kode',
									't_gkk.voucher_pengeluaran_id',
									't_voucher_pengeluaran.kode AS voucher_kode',
									't_voucher_pengeluaran.status_bayar',
									't_voucher_pengeluaran.cancel_transaksi_id'];
				$param['join']= ['LEFT JOIN t_gkk ON t_gkk.gkk_id = '.$param['table'].'.gkk_id
								  LEFT JOIN t_voucher_pengeluaran ON t_voucher_pengeluaran.voucher_pengeluaran_id = t_gkk.voucher_pengeluaran_id'];
				$param['where'] = "kas_kecil_id IS NULL AND ".$param['table'].".tipe = 'KK' AND (status_bon != 'PAID' OR status_bon IS NULL)";
				$param['order'] = $param['table'].".tanggal DESC, ".$param['table'].".kas_bon_id DESC";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
        }
		
		if(isset($_GET['kas_kecil_id'])){
            $model = \app\models\TKasBon::findOne($_GET['kas_kecil_id']);
            $model->nominal = \app\components\DeltaFormatter::formatNumberForUser($model->nominal);
        }
		$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
        if( isset($form_params['TKasBon']) ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = true;
				$post = $form_params['TKasBon'];
				if(count($post)>0){
					$mod = new \app\models\TKasBon();
					$mod->attributes = $post;
					$mod->kode = \app\components\DeltaGenerator::kodeKasBon();
					if(!empty($post['kas_bon_id'])){
						$mod = \app\models\TKasBon::findOne($post['kas_bon_id']);
						$mod->attributes = $post;
					}
					if($mod->validate()){
						if($mod->save()){
							$success_1 &= true;
						}
					}else{
						$success_1 = false;
						$data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
					}
				}
                if ($success_1) {
					$transaction->commit();
					$data['status'] = true;
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
		return $this->render('sementara',['model'=>$model]);
	}
	
	public function actionCreateKasbon(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TKasBon();
			$model->kode = "New Generate";
			$model->tanggal = date('d/m/Y');
			$model->nominal = 0;
			return $this->renderAjax('createKasbon',['model'=>$model]);
		}
	}
	public function actionEditKasbon(){
		if(\Yii::$app->request->isAjax){
			$kas_bon_id = Yii::$app->request->get("kas_bon_id");
			$model = \app\models\TKasBon::findOne($kas_bon_id);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal);
			return $this->renderAjax('createKasbon',['model'=>$model]);
		}
	}
	
	public function actionDeleteItem($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasKecil::findOne($id);
			$modSementara = \app\models\TKasBon::findOne(['kas_kecil_id'=>$id]);
			$modTerima = \app\models\TTerimaBhp::findOne(['kas_kecil_id'=>$id]);
			if(!empty($modTerima)){
				$modPengajuanTagihan = \app\models\TPengajuanTagihan::findOne(['terima_bhp_id'=>$modTerima->terima_bhp_id]);
			}
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
                    $success_3 = false;
                    $success_4 = true;
                    $success_5 = true;
					
					if(!empty($modSementara)){
						$modSementara->kas_kecil_id = null;
						if($modSementara->validate()){
							$success_2 = $modSementara->save();
						}
					}else{
						$success_2 = true;
					}
					
					if(!empty($modTerima)){
						
						$modTBPs = \app\models\TTerimaBhp::find()->where("kas_kecil_id = ".$id)->all();
						if(count($modTBPs)>0){
							foreach($modTBPs as $itbp => $modTBP){
								$success_5 &= \app\models\TPengajuanTagihan::updateAll(['status'=>"DIAJUKAN",'keterangan'=>null], 'terima_bhp_id = '.$modTBP['terima_bhp_id']);
							}
						}else{
							$success_5 = true;
						}
						
						$success_3 = \app\models\TTerimaBhp::updateAll(['kas_kecil_id'=>null], 'kas_kecil_id = '.$id);
					}else{
						$success_3 = true;
					}
					
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					
					$mod = \app\models\TKasKecil::find()->where(['tanggal'=>$model->tanggal])->all();
					foreach($mod as $i => $kaskecil){
						$kaskecil->seq = $i+1;
						$success_4 &= $kaskecil->save();
					}
					
					
					
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					echo "<pre>";
//					print_r($success_3);
//					echo "<pre>";
//					print_r($success_4);
//					echo "<pre>";
//					print_r($success_5);
//					exit;
					
					if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5) {
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
	
	public function actionDeleteItemSementara($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasBon::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					$success_2 = true;
					$modBonSementara = \app\models\HBonsementara::find()->where(['kas_bon_id'=>$id])->all();
					if(count($modBonSementara)>0){
						$success_2 = \app\models\HBonsementara::updateAll(['kas_bon_id'=>null], "kas_bon_id = {$id}");
					}
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					exit;
					
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = "$('#table-laporan').dataTable().fnClearTable();";
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>'deleteItemSementara']);
		}
	}
	
	public function actionHistoryPengeluaranSementara(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-dt'){
				$param['table']= \app\models\TKasBon::tableName();
				$param['pk']= \app\models\TKasBon::primaryKey()[0];
				$param['column'] = ['kas_bon_id',$param['table'].'.kode',['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],
									['col_name'=>'t_kas_kecil.tanggal AS tanggal_real','formatter'=>'formatDateForUser2'],$param['table'].'.penerima',
									$param['table'].'.deskripsi',$param['table'].'.nominal'
									];
				$param['join']= ['LEFT JOIN t_kas_kecil ON t_kas_kecil.kas_kecil_id = '.$param['table'].'.kas_kecil_id'];
				$param['where'] = $param['table'].".kas_kecil_id IS NOT NULL OR status_bon = 'PAID' AND ".$param['table'].".tipe = 'KK'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('historyPengeluaranSementara');
        }
    }
	
	public function actionPickPanelPengeluaranSementara(){
        if(\Yii::$app->request->isAjax){
			$rowid = \Yii::$app->request->get('rowid');
			if(\Yii::$app->request->get('dt')=='table-dt'){
				$param['table']= \app\models\TKasBon::tableName();
				$param['pk']= \app\models\TKasBon::primaryKey()[0];
				$param['column'] = ['kas_bon_id','kode',['col_name'=>'tanggal','formatter'=>'formatDateForUser2'],'penerima','deskripsi','nominal'];
				$param['where'] = "kas_kecil_id IS NULL AND tipe = 'KK' AND (status_bon != 'PAID' OR status_bon IS NULL)";
				$param['order'] = "created_at DESC";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('pickPanelPengeluaranSementara',['rowid'=>$rowid]);
        }
    }
	
	public function actionPickPengeluaranSementara(){
        if(\Yii::$app->request->isAjax){
			$picked = \Yii::$app->request->post('picked');
			$tgl = \Yii::$app->request->post('tgl');
			$parsed = explode(',', $picked);
			$clean = []; $data = []; $data['html'] = ''; $data['kasbon'] = '';
			foreach($parsed as $parse){
				if(!empty($parse)){
					$clean[] = str_replace('-', '', $parse);
				}
			}
			if(!empty($clean)){
				foreach($clean as $id){
					$modKasbon = \app\models\TKasBon::findOne($id);
					$model = new \app\models\TKasKecil();
					$model->kode = "New Generate";
					$model->kas_bon_id = $modKasbon->kas_bon_id;
					$model->penerima = $modKasbon->penerima;
					$model->deskripsi = $modKasbon->deskripsi;
					$model->tanggal = $tgl;
					$model->debit = 0;
					$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($modKasbon->nominal);
					$data['kasbon'] = $modKasbon->attributes;
					$data['html'] .= $this->renderPartial('_item',['model'=>$model,'modKasbon'=>$modKasbon]);
				}
			}
			
			return $this->asJson($data);
        }
    }
	
	public function actionPickPanelTBP(){
        if(\Yii::$app->request->isAjax){
			$eleid = \Yii::$app->request->get('eleid');
			if(\Yii::$app->request->get('dt')=='table-dt'){
				$param['table']= \app\models\TTerimaBhp::tableName();
				$param['pk']= \app\models\TTerimaBhp::primaryKey()[0];
				$param['column'] = ['terima_bhp_id','terimabhp_kode',['col_name'=>'tglterima','formatter'=>'formatDateForUser2'],'suplier_nm','nofaktur','totalbayar'];
				$param['join']= ['JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id'];
//				$param['where'] = "kas_kecil_id IS NULL AND ".$param['table'].".cancel_transaksi_id IS NULL AND spl_id IS NOT NULL";
				$param['where'] = "kas_kecil_id IS NULL AND ".$param['table'].".cancel_transaksi_id IS NULL AND spl_id IS NOT NULL AND terima_bhp_id IN (SELECT terima_bhp_id FROM t_pengajuan_tagihan WHERE spl_id IS NOT NULL AND status = 'DIAJUKAN')";
				$param['order'] = $param['table'].".created_at DESC";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('pickPanelTBP',['eleid'=>$eleid]);
        }
    }
	
	public function actionPickTBP(){
        if(\Yii::$app->request->isAjax){
			$picked = \Yii::$app->request->post('picked');
			$parsed = explode(',', $picked);
			$clean = []; $data = []; $data['deskripsi'] = ''; $kodeterima=[]; $data['total']=0; $data['kodelabelterima'] = "";
			foreach($parsed as $parse){
				if(!empty($parse)){
					$clean[] = str_replace('-', '', $parse);
				}
			}
			if(!empty($clean)){
				foreach($clean as $i => $id){
					$modTerima = \app\models\TTerimaBhp::findOne($id);
					$kodeterima[] = $modTerima->terimabhp_kode;
					$data['total'] += $modTerima->totalbayar;
					if(!empty($modTerima->suplier_id)){
						if($i!=0){
							$data['deskripsi'] .= "-";
						}
						$data['deskripsi'] .= $modTerima->suplier->suplier_nm."-".$modTerima->terimabhp_kode;
					}
					$modTerimaDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$id])->all();
					if(count($modTerimaDetail)>0){
						$data['deskripsi'] .= "-";
						foreach($modTerimaDetail as $ii => $detail){
							$data['deskripsi'] .= $detail->bhp->Bhp_nm."(".$detail->terimabhpd_qty.$detail->bhp->bhp_satuan.")";
							if(count($modTerimaDetail)!= ($ii+1) ){
								$data['deskripsi'] .= ", ";
							}
						}
					}
					$data['kodelabelterima'] .= "<a onclick='infoTBP(".$modTerima->terima_bhp_id.")'>".$modTerima->terimabhp_kode."</a><br>";
				}
			}
			$data['kodeterima'] = implode(',', $kodeterima);
			return $this->asJson($data);
        }
    }
	
	public function actionUrutkanKode(){
        if(\Yii::$app->request->isAjax){
			$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
			if( (isset($form_params['TKasKecil'])) ){
				if( count($form_params['TKasKecil'])>0 ){
					$transaction = \Yii::$app->db->beginTransaction();
					try {
						$success_1 = true;
						$success_2 = true;
						$success_3 = false;
						$success_4 = true;
						$post = $form_params['TKasKecil'];

						if(count($form_params['TKasKecil'])){
							// hilangkan dulu data
							foreach($form_params['TKasKecil'] as $i => $post){
								$modSementara = \app\models\TKasBon::findOne(['kas_kecil_id'=>$post['kas_kecil_id']]);
								if(!empty($modSementara)){
									$modSementara->kas_kecil_id = null;
									if($modSementara->validate()){
										$success_4 = $modSementara->save();
									}
								}else{
									$success_4 = true;
								}
							}
							$modHapus = \app\models\TKasKecil::find()->where("tanggal = '".$form_params['TKasKecil'][0]['tanggal']."'")->all();
							if(count($modHapus)>0){
								$success_3 = \app\models\TKasKecil::deleteAll("tanggal = '".$form_params['TKasKecil'][0]['tanggal']."' ");
							}else{
								$success_3 = true;
							}
							// end hilangkan dulu data
							foreach($form_params['TKasKecil'] as $i => $post){
								$mod = new \app\models\TKasKecil();
								$mod->attributes = $post;
								$mod->kode = \app\components\DeltaGenerator::kodePengeluaranKasKecil($mod->tanggal);
								$mod->closing = false;
								if($mod->validate()){
									if($mod->save()){
										$success_1 = true;
										if(!empty($post['kas_bon_id'])){
											$modSementara = \app\models\TKasBon::findOne($post['kas_bon_id']);
											$modSementara->kas_kecil_id = $mod->kas_kecil_id;
											if($modSementara->validate()){
												$success_2 = $modSementara->save();
											}else{
												$success_2 = false;
											}
										}else{
											$success_2 = true;
										}
									}
								}else{
									$success_1 = false;
									$data['message_validate']=\yii\widgets\ActiveForm::validate($mod); 
								}
							}
						}

	//	                echo "<pre>";
	//					print_r($success_1);
	//	                echo "<pre>";
	//					print_r($success_2);
	//	                echo "<pre>";
	//					print_r($success_3);
	//	                echo "<pre>";
	//					print_r($success_4);
	//					exit;

						if ($success_1 && $success_2 && $success_3 && $success_4) {
							$transaction->commit();
							$data['status'] = true;
							$data['kode'] = $mod->kode;
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
			}
        }
    }
	
	public function actionUangtunai(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TUangtunai();
			$tgl = \Yii::$app->request->get('id');
			$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
			if( (isset($form_params['TUangtunai'])) ){
				if( count($form_params['TUangtunai'])>0 ){
					$transaction = \Yii::$app->db->beginTransaction();
					try {
						$success_1 = true;
						foreach($form_params['TUangtunai'] as $i => $post){
							if(!empty($post['uangtunai_id'])){
								$mod = \app\models\TUangtunai::findOne($post['uangtunai_id']);
							}else{
								$mod = new \app\models\TUangtunai();
							}
							$mod->attributes = $post;
							$mod->closing = false;
							if($mod->validate()){
								if($mod->save()){
									$success_1 &= true;
								}
							}else{
								$success_1 = false;
								$data['message_validate']=\yii\widgets\ActiveForm::validate($mod); 
							}
						}
						if ($success_1) {
							$transaction->commit();
							$data['status'] = true;
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
			}
			return $this->renderAjax('uangtunai',['model'=>$model,'tgl'=>$tgl]);
		}
	}
	
	public function actionGetUangTunai(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->post('tgl');
            $data = [];
            $data['html'] = '';
            $data['total'] = 0;
            if(!empty($tgl)){
                $modUangTunai = \app\models\TUangtunai::find()->where(['tanggal'=>$tgl,'tipe'=>'KK'])->orderBy(['nominal'=>SORT_ASC])->all();
                if(count($modUangTunai)>0){
                    foreach($modUangTunai as $i => $model){
						$data['html'] .= $this->renderPartial('_itemuangtunai',['model'=>$model,'i'=>$i,'tgl'=>$tgl]);
						$data['total'] += $model->subtotal;
                    }
                }
            }
            return $this->asJson($data);
		}
	}
	
	public function actionCheckClosingUangTunai(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->post('tgl');
			$data['closing'] = 0;
			$data['exist'] = 0;
			$data['msg'] = "";
			$kas = \app\models\TUangtunai::find()->where("tanggal = '".$tgl."' AND tipe = 'KK' ")->one();
			$bkk = \app\models\TKasKecil::find()->where("bkk_id IS NULL AND tanggal = '".$tgl."' AND tipe = 'OUT'")->all();
			if(!empty($kas)){
				$data['closing'] = ($kas->closing == true)?1:0;
				$data['exist'] = 1;
			}else{
				$data['closing'] = 0;
				$data['exist'] = 0;
				$data['msg'] = "Tidak bisa closing, Rincian Uang Tunai belum di input.";
			}
			if(count($bkk)>0){
				$data['closing'] = 0;
				$data['exist'] = 0;
				$data['msg'] = "Tidak bisa closing, masih ada BKK yang belum di generate.";
			}
			return $this->asJson($data);
		}
	}
	
	public function actionCheckKasbonKasbesar(){
		if(\Yii::$app->request->isAjax){
			$pesan = "";
			$id = "";
			$modKasBons = \app\models\TKasBon::find()->where("tipe = 'KB' AND status = 'KASBON KASBESAR KE KASKECIL' AND (terimakasbon_kk IS NULL OR terimakasbon_kk = FALSE)")->all();
			if(count($modKasBons)>0){
				$totalkasbon = 0;
				foreach($modKasBons as $i => $kasbon){
					$totalkasbon += $kasbon->nominal;
				}
				$totalkasbon = \app\components\DeltaFormatter::formatUang($totalkasbon);
				$pesan = "Anda memiliki Kasbon dari<br>Kas Besar total sebesar : <b>".$totalkasbon."</b> <br> Apakah anda akan menerima kasbon ini?";
				if( Yii::$app->request->post('updaterecord')){
					$transaction = \Yii::$app->db->beginTransaction();
					try {
						$success_1 = true; // t_kas_bon
						foreach($modKasBons as $ii => $bon){
							$bon->terimakasbon_kk = TRUE;
							if($bon->validate()){
								$success_1 &= $bon->save();
							}else{
								$success_1 = FALSE;
							}
						}
	//					echo "<pre>";
	//					print_r($success_1);
	//					exit;
						if ($success_1 ) {
							$transaction->commit();
							$data['status'] = true;
							$data['callback'] = '$( "#close-btn-globalconfirm" ).click();';
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
				return $this->renderAjax('@views/apps/partial/_globalConfirmUndragable',['id'=>$id,'pesan'=>$pesan,'actionname'=>'CheckKasbonKasbesar']);
			}else{
				return false;
			}
		}
	}
	
	public function actionTerimauangganti($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasBon::findOne($id);
			$models = \app\models\TKasBon::find()->where("gkk_id = ".$model->gkk_id)->all();
			$totalnominal = 0;
			foreach($models as $i => $kasbon){
				$totalnominal += $kasbon->nominal;
			}
			$pesan = "Anda akan menerima pembayaran kas bon <br>dari <b>Finance</b> dengan Kode BBK : '<b>".$model->gkk->voucherPengeluaran->kode."</b>' <br>total sebesar <b>".\app\components\DeltaFormatter::formatNumberForUser($totalnominal)."</b> ?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = true;
					foreach($models as $i => $bon){
						$bon->status_bon = "PAID";
						if($bon->validate()){
							if($bon->save()){
								$success_1 &= true;
							}
						}
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); $("#table-laporan").dataTable().fnClearTable();';
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
			return $this->renderAjax('@views/apps/partial/_globalConfirmUndragable',['id'=>$id,'pesan'=>$pesan,'actionname'=>'terimauangganti']);
		}
	}
	
	public function actionInfoKasbonkk($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasBon::findOne($id);
			return $this->renderAjax('infoKasbonkk',['model'=>$model]);
		}
    }
	
	public function actionTerimaretur(){
        $model = new \app\models\TReturBhp();
        $modKasKecil = new \app\models\TKasKecil();
        $modKasKecil->tanggal = date('d/m/Y');
		
		if(isset($_GET['kas_kecil_id'])){
			$model = \app\models\TReturBhp::findOne(['kas_kecil_id'=>$_GET['kas_kecil_id']]);
			$modTerimaBhp = \app\models\TTerimaBhpDetail::findOne(['terima_bhpd_id'=>$model->terima_bhpd_id]);
			$model->bhp_nm = $modTerimaBhp->bhp->bhp_nm;
			$model->harga_terima = \app\components\DeltaFormatter::formatNumberForUserFloat($modTerimaBhp->terimabhpd_harga);
			$model->potongan = \app\components\DeltaFormatter::formatNumberForUserFloat($model->potongan);
			$model->harga = \app\components\DeltaFormatter::formatNumberForUserFloat($model->harga);
			$model->total_kembali = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_kembali);
			$modKasKecil = \app\models\TKasKecil::findOne($_GET['kas_kecil_id']);
			$modKasKecil->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($modKasKecil->nominal);
		}
		
		if( Yii::$app->request->post('TReturBhp')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_retur_bhp
                $success_2 = false; // t_kas_kecil
				
				$modKasKecil->load(\Yii::$app->request->post());
				$modKasKecil->kode = "-";
				$modKasKecil->tipe = "IN";
				$modKasKecil->closing = FALSE;
				$modKasKecil->jenis = "TOPUP";
				$modKasKecil->seq = \app\components\DeltaGenerator::sequenceKasKecil($modKasKecil->tanggal);
				if($modKasKecil->validate()){
					$success_2 = $modKasKecil->save();
					$model = \app\models\TReturBhp::findOne($_POST['TReturBhp']['retur_bhp_id']);
					$model->kas_kecil_id = $modKasKecil->kas_kecil_id;
					if($model->validate()){
						$success_1 = $model->save();
					}else{
						$success_1 = false;
					}
				}else{
					$success_2 = false;
				}
				
//				echo "<pre>";
//				print_r($success_1);
//				echo "<pre>";
//				print_r($success_2);
//				exit;
				
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Uang Retur Berhasil Diterima'));
                    return $this->redirect(['terimaretur','success'=>1,'kas_kecil_id'=>$modKasKecil->kas_kecil_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		
		return $this->render('terimaretur',['model'=>$model,'modKasKecil'=>$modKasKecil]);
	}
	
	public function actionGetDataRetur(){
		if(\Yii::$app->request->isAjax){
			$retur_bhp_id = Yii::$app->request->post('retur_bhp_id');
			$data = [];
			if(!empty($retur_bhp_id)){
				$model = \app\models\TReturBhp::findOne($retur_bhp_id);
				$modTerimaDetail = \app\models\TTerimaBhpDetail::findOne($model->terima_bhpd_id);
				$modKasKecil = new \app\models\TKasKecil();
				if(!empty($model)){
					$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
					$model->harga = \app\components\DeltaFormatter::formatNumberForUserFloat($model->harga);
					$model->potongan = \app\components\DeltaFormatter::formatNumberForUserFloat($model->potongan);
					$model->total_kembali = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_kembali);
					$data = $model->attributes;
					$data['harga_terima'] = \app\components\DeltaFormatter::formatNumberForUserFloat($modTerimaDetail->terimabhpd_harga);
					$data['bhp_nm'] = $modTerimaDetail->bhp->bhp_nm;
					$data['tanggalkas'] = date('d/m/Y');
					$data['penerima'] = Yii::$app->user->identity->pegawai->pegawai_nama;
					$data['tbp_reff'] = $modTerimaDetail->terimaBhp->terimabhp_kode;
					$data['deskripsikas'] = "Penambahan Uang Retur-".$modTerimaDetail->terimaBhp->terimabhp_kode."-".$modTerimaDetail->bhp->bhp_nm."(".$modTerimaDetail->terimabhpd_qty.$modTerimaDetail->bhp->bhp_satuan.")";
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionReturDiterima(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-returditerima'){
				$param['table']= \app\models\TReturBhp::tableName();
				$param['pk']= \app\models\TReturBhp::primaryKey()[0];
				$param['column'] = ['retur_bhp_id',$param['table'].'.kode', 't_terima_bhp.terima_bhp_id','t_terima_bhp.terimabhp_kode',$param['table'].'.tanggal','m_brg_bhp.bhp_nm','harga','potongan','qty','m_brg_bhp.bhp_satuan','total_kembali','t_retur_bhp.kas_kecil_id','t_terima_bhp_detail.bhp_id'];
				$param['join']= ['JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhpd_id = '.$param['table'].'.terima_bhpd_id',
								 'JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_terima_bhp_detail.terima_bhp_id',
								 'JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_terima_bhp_detail.bhp_id'];
				$param['where'] = $param['table'].".kas_kecil_id IS NOT NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarterimaretur');
        }
    }
	
	public function actionReorderkaskecil(){
		if(\Yii::$app->request->isAjax){
			$pesan = Yii::t('app', 'Apakah anda akan mengubah urutan ini??');
			$id = $_GET['table-detail'];
			if( Yii::$app->request->post('updaterecord')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$params = $_GET['table-detail'];
					$success_1 = TRUE;
					foreach($params as $i => $kas_kecil_id){
						$mod = \app\models\TKasKecil::findOne($kas_kecil_id);
						$mod->seq = $i+1;
						$success_1 &= $mod->save();
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); getItems();';
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
			return $this->renderAjax('_orderingConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'Reorderkaskecil']);
		}
	}
	
	function actionRekapPraClosing(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->get('tgl');
			$info = Yii::$app->request->get('info');
			if($info=='laporan'){
				$models = \app\models\TKasKecil::find()->where("tanggal = '".$tgl."'")->orderBy(['seq'=>SORT_ASC])->all();
				if(!empty($models)){
					return $this->renderAjax('_kaskecilPraclosing',['models'=>$models]);
				}
			}
			if($info=='uangtunai'){
				$model = new \app\models\TUangtunai();
				if(!empty($model)){
					return $this->renderAjax('uangtunai',['model'=>$model,'tgl'=>$tgl,'info'=>true]);
				}
			}
			if($info=='kasbon'){
				$models = \app\models\TKasBon::kasbonGantung();
				if(!empty($models)){
					return $this->renderAjax('kasbon',['models'=>$models,'tgl'=>$tgl]);
				}
			}
			if($info=='kasbonkasbesar'){
				$models = \app\models\TKasBon::kasbonGantungKBKeKK($tgl);
				if(!empty($models)){
					return $this->renderAjax('kasbonkasbesar',['models'=>$models,'tgl'=>$tgl]);
				}
			}
			return false;
		}
	}
	
	public function actionCreateBkk($id){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->get('id');
			$pesan = "Anda akan mencetak BKK dari pengeluaran ini sekaligus mengenerate kode BBK baru. Data tidak akan bisa dihapus atau diedit jika kode BKK telah digenerate. Yakinkah anda melakukan ini?";
			$modKasKecil = \app\models\TKasKecil::findOne($id);
			if( Yii::$app->request->post('updaterecord')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = FALSE; // t_bkk
					$success_2 = FALSE; // t_kas_kecil
					$modBkk = new \app\models\TBkk();
					$modBkk->tipe = "Kas Kecil";
					$modBkk->kode = \app\components\DeltaGenerator::kodeBKK();
					$modBkk->tanggal = $modKasKecil->tanggal;
					$arr = [['kasbon_id'=>'','detail_deskripsi'=>$modKasKecil->deskripsi,'detail_nominal'=>$modKasKecil->nominal]];
					$modBkk->deskripsi = \yii\helpers\Json::encode($arr);
					$modBkk->totalnominal = $modKasKecil->nominal;
					$modBkk->diterima_oleh = $modKasKecil->penerima;
					$modBkk->dibuat_oleh = \Yii::$app->user->identity->pegawai_id;
					$modBkk->ganti_uangkas = FALSE;
					$modBkk->tbp_reff = $modKasKecil->tbp_reff;
					if($modBkk->validate()){
						if($modBkk->save()){
							$success_1 = TRUE;
							$modKasKecil->bkk_id = $modBkk->bkk_id;
							if($modKasKecil->save()){
								$success_2 = TRUE;
							}else{
								$success_2 = FALSE;
							}
						}
					}
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); getItems(); printBKK('.$modBkk->bkk_id.');';
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
			return $this->renderAjax('@views/apps/partial/_globalConfirmUndragable',['id'=>$id,'pesan'=>$pesan,'actionname'=>'CreateBkk']);
		}
	}
	
	public function actionCreateMultipleBkk($id){
		if(\Yii::$app->request->isAjax){
			if(!empty($id)){
				$modKasKecil = \app\models\TKasKecil::find()->where("kas_kecil_id IN ($id)")->all(); $total = 0;
				foreach($modKasKecil as $i => $kaskecil){
					$total += $kaskecil->nominal;
				}
				$pesan = "<center>Anda akan menggabungkan ".count($modKasKecil)." pengeluaran menjadi satu BKK.<br>total nominal <b>Rp. ".\app\components\DeltaFormatter::formatNumberForUserFloat($total)."</b>. Data tidak akan bisa dihapus atau diedit jika kode BKK telah digenerate.<br> Yakinkah anda melakukan ini?<center>";
				if( Yii::$app->request->post('updaterecord')){
					$transaction = \Yii::$app->db->beginTransaction();
					try {
						$success_1 = FALSE; // t_bkk
						$success_2 = TRUE; // t_kas_kecil
						$tbp_reff = []; $deskripsi=""; $nominal=0;
						foreach($modKasKecil as $i => $kaskecil){
							if(!empty($kaskecil->tbp_reff)){
								foreach(explode(",", $kaskecil->tbp_reff) as $reff){
									$tbp_reff[] = $reff;
								}
							}
							$arr[] = ['kasbon_id'=>'','detail_deskripsi'=>$kaskecil->deskripsi,'detail_nominal'=>$kaskecil->nominal];
						}
						$modBkk = new \app\models\TBkk();
						$modBkk->tipe = "Kas Kecil";
						$modBkk->kode = \app\components\DeltaGenerator::kodeBKK();
						$modBkk->tanggal = $modKasKecil[0]->tanggal;
						$modBkk->deskripsi = \yii\helpers\Json::encode($arr);
						$modBkk->totalnominal = $total;
						$modBkk->diterima_oleh = "-";
						$modBkk->dibuat_oleh = \Yii::$app->user->identity->pegawai_id;
						$modBkk->ganti_uangkas = FALSE;
						$modBkk->tbp_reff = implode(",", $tbp_reff);
						if($modBkk->validate()){
							if($modBkk->save()){
								$success_1 = TRUE;
								foreach($modKasKecil as $i => $kaskecil){
									$kaskecil->bkk_id = $modBkk->bkk_id;
									if($kaskecil->save()){
										$success_2 = TRUE;
									}else{
										$success_2 = FALSE;
									}
								}
							}
						}
						
//						echo "<pre>";
//						print_r($success_1);
//						echo "<pre>";
//						print_r($success_2);
//						exit;
						
						if ($success_1 && $success_2) {
							$transaction->commit();
							$data['status'] = true;
							$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); getItems(); printBKK('.$modBkk->bkk_id.');';
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
				return $this->renderAjax('@views/apps/partial/_globalConfirmUndragable',['id'=>$id,'pesan'=>$pesan,'actionname'=>'CreateMultipleBkk']);
			}else{
				return false;
			}
		}
	}
	
	public function actionCreateGkk($kas_bon_id){
		if(\Yii::$app->request->isAjax){
			$modKasBon = \app\models\TKasBon::findOne($kas_bon_id);
			$pesan = "Pengajuan Penggantian Uang Ke Finance. Ganti Kas Kecil (GKK) Baru";
			$model = new \app\models\TGkk();
			$model->kode = "Auto Generate";
			$model->tanggal = date('d/m/Y');
			$model->totalnominal = \app\components\DeltaFormatter::formatNumberForUserFloat($modKasBon->nominal);
			$model->penerima = $modKasBon->penerima;
			$model->deskripsi = $modKasBon->deskripsi;
			if( Yii::$app->request->post('TGkk')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = FALSE; // t_gkk
					$success_2 = FALSE; // t_kas_bon
					$model->load(\Yii::$app->request->post());
					$model->kode = \app\components\DeltaGenerator::kodeGKK();
					$arr = [['kasbon_id'=>$kas_bon_id,'detail_deskripsi'=>$_POST['TGkk']['deskripsi'],'detail_nominal'=>$_POST['TGkk']['totalnominal']]];
					$model->deskripsi = \yii\helpers\Json::encode($arr);
					if($model->validate()){
						if($model->save()){
							$success_1 = TRUE;
							$modKasBon->gkk_id = $model->gkk_id;
							if($modKasBon->save()){
								$success_2 = TRUE;
							}else{
								$success_2 = FALSE;
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
						$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); $("#table-laporan").dataTable().fnClearTable();';
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
			return $this->renderAjax('createGkk',['model'=>$model,'modKasBon'=>$modKasBon,'pesan'=>$pesan,'actionname'=>'CreateGkk']);
		}
	}
	
	public function actionDetailGkk(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TGkk::findOne($_GET['id']);
		$modDetail = \yii\helpers\Json::decode($model->deskripsi);
		$total = 0;
		if(count($modDetail)>0){
			foreach($modDetail as $i => $det){
				$total += $det['detail_nominal'];
			}
		}
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'GANTI KAS KECIL');
		if($caraprint == 'PRINT'){
			return $this->render('printgkk',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint,'total'=>$total]);
		}else{
			return $this->renderAjax('detailgkk',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint,'total'=>$total]);
		}
	}
	
	public function actionEditDeskripsi($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasKecil::findOne($id);
			$modSaldo = \app\models\HSaldoKaskecil::findOne(['reff_no'=>$model->kode,'tanggal'=>$model->tanggal." 01:00:00"]);
			$modBkk = \app\models\TBkk::findOne($model->bkk_id);
			$desk_old = $model->deskripsi;
			if( Yii::$app->request->post('TKasKecil')){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = FALSE; // t_kas_kecil
					$success_2 = TRUE; // h_saldo_kaskecil
					$success_3 = TRUE; // t_bkk
					$model->load(\Yii::$app->request->post());
					if($model->validate()){
						if($model->save()){
							$success_1 = TRUE;
							if(!empty($modSaldo)){
								$modSaldo->deskripsi = $model->deskripsi;
								if($modSaldo->validate()){
									if($modSaldo->save()){
										$success_2 = true;
									}else{
										$success_2 = false;
									}
								}else{
									$success_2 = false;
								}
							}
							if(!empty($modBkk)){
								$deskbkks = \yii\helpers\Json::decode($modBkk->deskripsi);
								if(count($deskbkks)>0){
									foreach($deskbkks as $idsbkk => $deskbkk){
										if($deskbkk['detail_deskripsi'] == $desk_old){
											$deskbkks[$idsbkk]['detail_deskripsi'] = $model->deskripsi;
										}
									}
								}
								$modBkk->deskripsi = \yii\helpers\Json::encode($deskbkks);
								if($modBkk->validate()){
									if($modBkk->save()){
										$success_3 = true;
									}else{
										$success_3 = false;
									}
								}else{
									$success_3 = false;
								}
							}
						}
					}
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					echo "<pre>";
//					print_r($success_3);
//					exit;
					if ($success_1 && $success_2 && $success_3) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = 'location.reload();';
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
			return $this->renderAjax('editDeskripsi',['model'=>$model]);
		}
	}
}
