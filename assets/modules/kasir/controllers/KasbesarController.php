<?php

namespace app\modules\kasir\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class KasbesarController extends DeltaBaseController
{
	public $defaultAction = 'index';
	
	public function actionIndex(){
        $model = new \app\models\TKasBesar();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
		$model->nominal = 0;
		
		if(isset($_GET['kas_besar_id'])){
            $model = \app\models\TKasBesar::findOne($_GET['kas_besar_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->nominal = \app\components\DeltaFormatter::formatNumberForUser($model->nominal);
        }
		
		$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
        if( isset($form_params['TKasBesar']) ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_kas_besar
				$post = $form_params['TKasBesar'];
				if(count($post)>0){
					foreach($post as $peng){ $post = $peng; }
					$mod = new \app\models\TKasBesar();
					$mod->attributes = $post;
					$mod->kode = "-";
					$mod->closing = false;
					$mod->tipe = "IN";
					if(!empty($post['kas_besar_id'])){
						$mod = \app\models\TKasBesar::findOne($post['kas_besar_id']);
						$mod->attributes = $post;
						if($mod->tipe == "OUT"){
							$mod->nominal = $post['kredit'];
						}
					}
					if($mod->validate()){
						if($mod->save()){
							$success_1 = true;
						}
					}else{
						$success_1 = false;
						$data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
					}
				}
				
//                echo "<pre>";
//				print_r($success_1);
//				exit;
				
                if ($success_1) {
					$transaction->commit();
					$data['status'] = true;
					$data['kode'] = $mod->kode;
					$data['kodenota'] = (!empty($mod->nota_penjualan_id)?$mod->notaPenjualan->kode:"");
					$data['kas_besar_id'] = $mod->kas_besar_id;
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
                $modKasBesar = \app\models\TKasBesar::find()->where(['tanggal'=>$tgl])->orderBy(['kas_besar_id'=>SORT_ASC])->all();
                if(count($modKasBesar)>0){
                    foreach($modKasBesar as $i => $model){
						$model->kredit = 0;
						if($model->tipe == "OUT"){
							$model->kredit = \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal);
							$model->nominal = 0;
						}
						$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal);
						$data['html'] .= $this->renderPartial('_item',['model'=>$model,'i'=>$i,'disabled'=>$disabled]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionSetClosingBtn(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->post('tgl');
			$data['status'] = 0;
			$kas = \app\models\TKasBesar::find()->where(['tanggal'=>$tgl])->one();
			$tunai = \app\models\TUangtunai::find()->where(['tanggal'=>$tgl,'tipe'=>'KB'])->one();
			
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
	
	public function actionGetUangTunai(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->post('tgl');
            $data = [];
            $data['html'] = '';
            $data['total'] = 0;
            if(!empty($tgl)){
                $modUangTunai = \app\models\TUangtunai::find()->where(['tanggal'=>$tgl,'tipe'=>'KB'])->orderBy(['nominal'=>SORT_ASC])->all();
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
	
	public function actionCheckClosing(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->post('tgl');
			$data = 0;
			$kas = \app\models\TKasBesar::find()->where("(tanggal < '".$tgl."' AND closing IS FALSE) OR (tanggal < '".$tgl."' AND closing IS NULL)")->one();
			if(count($kas)>0){
				$data = ($kas->closing == false)?1:0;
			}
			return $this->asJson($data);
		}
	}
	
	public function actionAddItem(){
		if(\Yii::$app->request->isAjax){
			$data = [];
            $data['html'] = '';
			$tgl = Yii::$app->request->post('tgl');
			$model = new \app\models\TKasBesar();
			$model->kode = "New Generate";
			$model->kredit = 0;
			$model->nominal = 0;
			$model->tanggal = $tgl;
			$model->penerima = "-";
			$model->jenis_penerimaan = "Penjualan";
			$model->cara_transaksi = "Tunai";
			$model->reff_cara_transaksi = "-";
			$model->tipe = "IN";
			$data['html'] = $this->renderPartial('_item',['model'=>$model]);
			return $this->asJson($data);
		}
	}
	
	public function actionDeleteItem($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasBesar::findOne($id);
			$modKuitansi = \app\models\TKuitansi::findOne(['cara_bayar'=>'Tunai','reff_penerimaan'=>$model->kas_besar_id]);
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
	
	public function actionCheckClosingUangTunai(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->post('tgl');
			$data['closing'] = 0;
			$data['exist'] = 0;
			$kas = \app\models\TUangtunai::find()->where("tanggal = '".$tgl."' AND tipe = 'KB'")->one();
			if(!empty($kas)){
				$data['closing'] = ($kas->closing == true)?1:0;
				$data['exist'] = 1;
			}else{
				$data['closing'] = 0;
				$data['exist'] = 0;
			}
			return $this->asJson($data);
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
							$mod->tipe = "KB";
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
	
	public function actionClosingConfirm($id){
		if(\Yii::$app->request->isAjax){
			$pesan = "Yakin akan melakukan <b>Closing Kasir</b> tanggal '<b>".\app\components\DeltaFormatter::formatDateTimeForUser($id)."</b>' ?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = true; // t_kas_besar
                    $success_2 = true; // h_saldo_kasbesar
                    $success_3 = true; // t_uang_tunai
					$success_4 = true; // h_bonsementara
					$success_5 = false; // t_closing_kasir
					$success_6 = true; // t_kas_besar_nontunai
					$debit = 0; $kredit = 0;
					$modelsIn = \app\models\TKasBesar::find()->where(['tanggal'=>$id,'tipe'=>"IN"])->orderBy(['kas_besar_id'=>SORT_ASC])->all();
					$modelsOut = \app\models\TKasBesar::find()->where(['tanggal'=>$id,'tipe'=>"OUT"])->orderBy(['kas_besar_id'=>SORT_ASC])->all();
					$uangTunai = \app\models\TUangtunai::find()->where(['tanggal'=>$id,'tipe'=>'KB'])->orderBy(['nominal'=>SORT_ASC])->all();
					$keluarSementara = \app\models\TKasBon::find()->where("kas_kecil_id IS NULL AND t_kas_bon.tipe = 'KB' AND ( status_bon != 'PAID' OR status_bon IS NULL)")->orderBy(['kas_bon_id'=>SORT_ASC])->all();
					$kasbesarNontunai = \app\models\TKasBesarNontunai::find()->where(['tanggal'=>$id])->all();
					if(count($modelsIn)>0){
						foreach($modelsIn as $i => $in){
							$in->kode = \app\components\DeltaGenerator::kodeKasBesar($in->tanggal);
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
							$out->kode = \app\components\DeltaGenerator::kodeKasBesar($out->tanggal);
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
							$modBon->tipe = "KB";
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
					
					if(count($kasbesarNontunai)>0){
						foreach($kasbesarNontunai as $iv => $nontunai){
							$nontunai->closing = TRUE;
							if($nontunai->validate()){
								if($nontunai->save()){
									$success_6 = TRUE;
								}else{
									$success_6 = FALSE;
								}
							}else{
								$success_6 = FALSE;
							}
						}
					}
					
					$modClosing = New \app\models\TClosingKasir();
					$modClosing->kode = "-";
					$modClosing->tipe = "KB";
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
//					echo "<pre>";
//					print_r($success_6);
//					exit;
					
					if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5 && $success_6) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$( ".fa-close" ).click(); setClosingBtn();';
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
			return $this->renderAjax('_closing',['id'=>$id,'pesan'=>$pesan,'actionname'=>'ClosingConfirm']);
		}
	}
	
	public function saveSaldo($model){
		// Start insert saldo kas
		$modSaldo = new \app\models\HSaldoKasbesar();
		$modSaldo->attributes = $model->attributes;
		$modSaldo->kode = $model->kode;
		$modSaldo->tanggal = $model->tanggal." 01:00:00";
		$modSaldo->saldo = 0;
		if($model->tipe == "IN"){
			$modSaldo->kredit = 0;
			$modSaldo->debit = $model->nominal;
			$modSaldo->status = "MASUK";
		}else if($model->tipe == "OUT"){
			$modSaldo->debit = 0;
			$modSaldo->kredit = $model->nominal;
			$modSaldo->status = "SETOR";
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
	
	public function actionKasbon(){
        $model = new \app\models\TKasBon();
        $model->kode = 'Auto Generate';
		$model->nominal = 0;
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
					foreach($post as $peng){ $post = $peng; }
					$mod = new \app\models\TKasBon();
					$mod->attributes = $post;
					$mod->kode = \app\components\DeltaGenerator::kodeKasBon();
					$mod->tipe = "KB";
					
					if(!empty($post['kas_bon_id'])){
						$mod = \app\models\TKasBon::findOne($post['kas_bon_id']);
						$mod->attributes = $post;
					}
					if($post['is_kasbonkaskecil'] == 1){
						$mod->status = "KASBON KASBESAR KE KASKECIL";
					}else{
						$mod->status = NULL;
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
				
//                echo "<pre>";
//				print_r($success_1);
//				exit;
				
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
		return $this->render('kasbon',['model'=>$model]);
	}
	
	public function actionGetKasbon(){
		if(\Yii::$app->request->isAjax){
            $data = [];
            $data['html'] = '';
			$disabled = false;
			$modBon = \app\models\TKasBon::kasbonGantungKB();
			if(count($modBon)>0){
				foreach($modBon as $i => $model){
					$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal);
					$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
					$model->is_kasbonkaskecil = ($model->status == "KASBON KASBESAR KE KASKECIL")?TRUE:FALSE;
					$data['html'] .= $this->renderPartial('_itemKasbon',['model'=>$model,'i'=>$i,'disabled'=>$disabled]);
				}
			}
            return $this->asJson($data);
        }
    }
	
	public function actionAddKasbon(){
		if(\Yii::$app->request->isAjax){
			$data = [];
            $data['html'] = '';
			$model = new \app\models\TKasBon();
			$model->kode = "New Generate";
			$model->nominal = 0;
			$model->tanggal = date('d/m/Y');
			$data['html'] = $this->renderPartial('_itemKasbon',['model'=>$model]);
			return $this->asJson($data);
		}
	}
	
	public function actionDeleteKasbon($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasBon::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
//					$success_2 = true;
					$modBonSementara = \app\models\HBonsementara::find()->where(['kas_bon_id'=>$id])->all();
					if(count($modBonSementara)>0){
						$data['message'] = "bon ini tidak bisa dihapus karna sudah pernah di closing";
//						$success_2 = \app\models\HBonsementara::deleteAll("kas_bon_id = ".$id);
					}else{
						if($model->delete()){
							$success_1 = true;
						}else{
							$data['message'] = Yii::t('app', 'Data Gagal dihapus');
						}
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>'deleteKasbon']);
		}
	}
	
	public function actionBonTerbayar(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-dt'){
				$param['table']= \app\models\TKasBon::tableName();
				$param['pk']= \app\models\TKasBon::primaryKey()[0];
				$param['column'] = ['t_kas_bon.kas_bon_id','t_kas_bon.kode',['col_name'=>'t_kas_bon.tanggal','formatter'=>'formatDateForUser2'],['col_name'=>'t_kas_bon.updated_at','formatter'=>'formatDateForUser2'],'t_kas_bon.penerima','t_kas_bon.deskripsi','status','t_kas_bon.nominal'];
//				$param['join']= ['JOIN t_kas_bon ON t_ppk.kas_bon_id = t_kas_bon.kas_bon_id'];
				$param['where'] = "kas_kecil_id IS NULL AND t_kas_bon.tipe = 'KB' AND status_bon = 'PAID'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('bonterbayar');
        }
    }
	
	public function actionCaratransaksi(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TKasBesar();
			$cara = \Yii::$app->request->get('cara');
			$idtarget = \Yii::$app->request->get('idtarget');
			if($cara == 'Bilyet'){
				$cara = 'Bilyet Giro';
			}
			return $this->renderAjax('caratransaksi',['model'=>$model,'cara'=>$cara,'idtarget'=>$idtarget]);
		}
	}
	
	public function actionTerimauangkaskecil($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasBon::findOne($id);
			$pesan = "Anda akan menerima pembayaran kas bon dari <br>kas kecil sebesar <b>".\app\components\DeltaFormatter::formatNumberForUser($model->nominal)."</b> ?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					if(!empty($model)){
						$model->status_bon = "PAID";
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
							}
						}
					}
//					echo "<pre>";
//					print_r($success_1);
//					exit;
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
			return $this->renderAjax('@views/apps/partial/_globalConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'Terimauangkaskecil']);
		}
	}
	
	public function actionTerimauangganti($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasBon::findOne($id);
			$pesan = "Anda akan menerima pembayaran kas bon <br>dari <b>Finance</b> dengan Kode BBK : '<b>".$model->bkk->voucherPengeluaran->kode."</b>' <br>sebesar <b>".\app\components\DeltaFormatter::formatNumberForUser($model->nominal)."</b> ?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					if(!empty($model)){
						$model->status_bon = "PAID";
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
							}
						}
					}
//					echo "<pre>";
//					print_r($success_1);
//					exit;
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
			return $this->renderAjax('@views/apps/partial/_globalConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'Terimauangkaskecil']);
		}
	}
	
	function actionRekappraclosing(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->get('tgl');
			$info = Yii::$app->request->get('info');
			if($info=='penjualan'){
				$models = \app\models\TKasBesar::find()->where("tanggal = '".$tgl."'")->orderBy(['tipe'=>SORT_ASC,'kas_besar_id'=>SORT_ASC])->all();
				if(!empty($models)){
					return $this->renderAjax('penjualanpraclosing',['models'=>$models]);
				}
			}
			if($info=='kasbon'){
				$models = \app\models\TKasBon::kasbonGantungKB();
				if(!empty($models)){
					return $this->renderAjax('kasbonpraclosing',['models'=>$models,'tgl'=>$tgl]);
				}
			}
			if($info=='uangtunai'){
				$model = new \app\models\TUangtunai();
				if(!empty($model)){
					return $this->renderAjax('uangtunai',['model'=>$model,'tgl'=>$tgl,'info'=>true]);
				}
			}
			return false;
		}
	}
	
	public function actionPickPanelNota(){
        if(\Yii::$app->request->isAjax){
			$eleid = \Yii::$app->request->get('eleid');
			if(\Yii::$app->request->get('dt')=='table-dt'){
				$param['table']= \app\models\TNotaPenjualan::tableName();
				$param['pk']= \app\models\TNotaPenjualan::primaryKey()[0];
				$param['column'] = ['nota_penjualan_id','kode',['col_name'=>'tanggal','formatter'=>'formatDateForUser2'],'cust_an_nama','cara_bayar','total_bayar','status'];
				$param['join']= ['JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL";
				$param['order'] = $param['table'].".created_at DESC";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('pickPanelNota',['eleid'=>$eleid]);
        }
    }
	
	public function actionPickNota(){
        if(\Yii::$app->request->isAjax){
			$kode = \Yii::$app->request->post('kode');
			$nota_penjualan_id = \Yii::$app->request->post('nota_penjualan_id');
			$data = null;
			$modNota = \app\models\TNotaPenjualan::findOne($nota_penjualan_id);
			if(!empty($modNota)){
				$data['kode'] = $modNota->kode;
				$data['deskripsi'] = "Nota ".$modNota->kode."/".$modNota->cust->cust_an_nama;
				$data['deskripsivoucher'] = $modNota->kode;
				$data['nominal'] = $modNota->total_bayar;
				$data['sender'] = $modNota->cust->cust_an_nama;
			}
			return $this->asJson($data);
        }
    }
	
	public function actionCreateKuitansi($reff_id,$cara_bayar,$edit=null){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TKuitansi();
			if($cara_bayar == "Tunai"){
				$modPenerimaan = \app\models\TKasBesar::findOne($reff_id);
				$model->cara_bayar = "Tunai";
				$model->reff_penerimaan = $modPenerimaan->kas_besar_id;
				$reff_penerimaan = $modPenerimaan->kas_besar_id;
			}else if($cara_bayar == "Transfer"){
				$modPenerimaan = \app\models\TVoucherPenerimaan::findOne($reff_id);
				$model->cara_bayar = "Transfer Bank";
				$model->terima_dari = $modPenerimaan->sender;
				$model->reff_penerimaan = $modPenerimaan->kode;
				$reff_penerimaan = $modPenerimaan->kode;
			}
			$modNota = \app\models\TNotaPenjualan::findOne($modPenerimaan->nota_penjualan_id);
			if(!empty($modNota)){
				$model->cust_id = $modNota->cust_id;
				$model->reff_tagihan = $modNota->kode;
				$model->terima_dari = $modNota->cust->cust_an_nama;
				$model->untuk_pembayaran = $modNota->kode;
				$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($modNota->total_bayar);
			}
			$model->tanggal = date('d/m/Y');
			$model->nomor = \app\components\DeltaGenerator::kodeKuitansi($cara_bayar,$model->tanggal);
			$model->petugas = \Yii::$app->user->identity->pegawai_id;
			$model->petugas_nama = \Yii::$app->user->identity->pegawai->pegawai_nama;
			if(!empty($edit)){
				$model = \app\models\TKuitansi::findOne(['reff_penerimaan'=>$reff_penerimaan]);
				$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
				$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal);
				$model->petugas_nama = $model->petugas0->pegawai_nama;
			}
			
			if( Yii::$app->request->post('TKuitansi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
					if(empty($model->reff_tagihan)){
						$model->reff_tagihan = "-";
					}
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
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
			return $this->renderAjax('createKuitansi',['model'=>$model]);
		}
	}
	
	public function actionGenerateNomorKuitansi(){
		if(\Yii::$app->request->isAjax){
			$tgl = Yii::$app->request->post('tgl');
			$cara_bayar = Yii::$app->request->post('cara_bayar');
			$data = \app\components\DeltaGenerator::kodeKuitansi($cara_bayar,$tgl);
			return $this->asJson($data);
		}
	}
	
	public function actionInfoKuitansi($kuitansi_id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TKuitansi::findOne($kuitansi_id);
			return $this->renderAjax('infoKuitansi',['model'=>$model]);
		}
    }
	
	public function actionPrintKuitansi(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TKuitansi::findOne($_GET['id']);
		$caraprint = Yii::$app->request->get('caraprint');
		if($caraprint == 'PRINT'){
			return $this->render('printKuitansi',['model'=>$model]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printKuitansi',['model'=>$model]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printKuitansi',['model'=>$model]);
		}
	}
}
