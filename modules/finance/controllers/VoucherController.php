<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class VoucherController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TVoucherPengeluaran();
        $model->kode = 'Auto Generate';
        $model->tanggal_bayar = date('d/m/Y');
		$model->totalkredit = 0;
		$model->status_bayar = "UNPAID";
		$model->total_dpp = 0;
		$model->total_dp = 0;
		$model->total_sisa = 0;
		$model->total_ppn = 0;
		$model->total_pph = 0;
		$model->total_pbbkb = 0;
		$model->biaya_tambahan = 0;
		$model->total_potongan = 0;
		$model->total_pembayaran = 0;
		$modDetails = [];
		$modDetailPO = [];
		
        if(isset($_GET['voucher_pengeluaran_id'])){
            $model = \app\models\TVoucherPengeluaran::findOne($_GET['voucher_pengeluaran_id']);
            $model->tanggal_bayar = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_bayar);
			if($model->mata_uang == "IDR"){
				$model->totalkredit = \app\components\DeltaFormatter::formatNumberForUser($model->total_nominal);
			} else {
				$model->totalkredit = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_nominal, 2);
			}
            // $model->totalkredit = \app\components\DeltaFormatter::formatNumberForUser($model->total_nominal);
            $model->totaldebit = \app\components\DeltaFormatter::formatNumberForUser($model->total_nominal);
            $model->suplier_nm = !empty($model->suplier_id)?$model->suplier->suplier_nm:' - ';
//			$modDetails = \app\models\TVoucherPengeluarandetail::find()->where(['voucher_pengeluaran_id'=>$_GET['voucher_pengeluaran_id']])->all();
			$modDetails = \app\models\TVoucherPengeluarandetail::find()->where(['voucher_pengeluaran_id'=>$_GET['voucher_pengeluaran_id']])->orderBy(['voucher_detail_id'=>SORT_ASC])->all();
			$modDetailPO = \app\models\TSpo::find()->where(['voucher_pengeluaran_id'=>$_GET['voucher_pengeluaran_id']])->all();
			$modPpk = \app\models\TPpk::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
			$modGkk = \app\models\TGkk::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
			$modAjuanDinas = \app\models\TAjuandinasGrader::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
			$modAjuanMakan = \app\models\TAjuanmakanGrader::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
			$modLogBayarDp = \app\models\TLogBayarDp::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
			$modLogBayarMuat = \app\models\TLogBayarMuat::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
			$modOpenVoucher = \app\models\TOpenVoucher::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
			if(!empty($modPpk)){
				$model->ppk_id = $modPpk->ppk_id;
				$model->ppk_kode = $modPpk->kode.' - '.\app\components\DeltaFormatter::formatDateTimeForUser2($modPpk->tanggal);
			}
			if(!empty($modGkk)){
				$model->gkk_id = $modGkk->gkk_id;
				$model->gkk_kode = $modGkk->kode.' - '.\app\components\DeltaFormatter::formatDateTimeForUser2($modGkk->tanggal);
			}
			if(!empty($modAjuanDinas)){
				$model->ajuandinas_grader_id = $modAjuanDinas->ajuandinas_grader_id;
				$model->pdg_kode = $modAjuanDinas->kode.' - '.$modAjuanDinas->graderlog->graderlog_nm.' - '.\app\components\DeltaFormatter::formatDateTimeForUser2($modAjuanDinas->tanggal);
			}
			if(!empty($modAjuanMakan)){
				$model->ajuanmakan_grader_id = $modAjuanMakan->ajuanmakan_grader_id;
				$model->pmg_kode = $modAjuanMakan->kode.' - '.$modAjuanMakan->graderlog->graderlog_nm.' - '.\app\components\DeltaFormatter::formatDateTimeForUser2($modAjuanMakan->tanggal);
			}
			if(!empty($modLogBayarDp)){
				$model->log_bayar_dp_id = $modLogBayarDp->log_bayar_dp_id;
				$model->pdl_kode = $modLogBayarDp->kode.' - '.\app\components\DeltaFormatter::formatDateTimeForUser2($modLogBayarDp->tanggal);
			}
			if(!empty($modLogBayarMuat)){
				$model->log_bayar_muat_id = $modLogBayarMuat->log_bayar_muat_id;
				$model->mlg_kode = $modLogBayarMuat->kode.' - '.\app\components\DeltaFormatter::formatDateTimeForUser2($modLogBayarMuat->tanggal);
			}
			if(!empty($modOpenVoucher)){
				$model->open_voucher_id = $modOpenVoucher->open_voucher_id;
				$model->ovk_kode = $modOpenVoucher->kode.' - '.$modOpenVoucher->departement->departement_nama;
			}
        }
        if( Yii::$app->request->post('TVoucherPengeluaran')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $success_2 = true;
				$success_3 = true; // t_spo
				$success_4 = true; // t_terima_bhp
				$success_5 = true; // t_dp_bhp
				$success_6 = true; // t_ppk
				$success_7 = true; // t_gkk
				$success_8 = true; // t_ajuandinas_grader
				$success_9 = true; // t_ajuanmakan_grader
				$success_10 = true; // t_log_bayar_dp
				$success_11 = true; // t_log_bayar_muat
				$success_12 = true; // t_open_voucher
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeVoucherPengeluaran();
					$model->tanggal = date('d/m/Y');
				}
				$model->total_nominal = $_POST['TVoucherPengeluaran']['totaldebit'];
				$model->active = true;
				$arrPost = ['nama_bank'=> isset($_POST['TVoucherPengeluaran']['nama_bank'])?$_POST['TVoucherPengeluaran']['nama_bank']:'',
							'rekening'=> isset($_POST['TVoucherPengeluaran']['rekening'])?$_POST['TVoucherPengeluaran']['rekening']:'',
							'an_bank'=> isset($_POST['TVoucherPengeluaran']['an_bank'])?$_POST['TVoucherPengeluaran']['an_bank']:''
							];
				$penerima[0] = $arrPost;
				$model->penerima_pembayaran = \yii\helpers\Json::encode($penerima);
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if(isset($_POST['TVoucherPengeluarandetail'])){
							if(count($_POST['TVoucherPengeluarandetail'])>0){
								if( (isset($_GET['edit'])) && (isset($_GET['voucher_pengeluaran_id']))){
									// exec ini jika proses edit
									$modDetail = \app\models\TVoucherPengeluarandetail::find()->where(['voucher_pengeluaran_id'=>$_GET['voucher_pengeluaran_id']])->all();
									if(count($modDetail)>0){
										\app\models\TVoucherPengeluarandetail::deleteAll(['voucher_pengeluaran_id'=>$_GET['voucher_pengeluaran_id']]);
									}
									$Drp = \app\models\TVoucherPengeluaran::findOne($_GET['voucher_pengeluaran_id']);
									if($Drp->status_drp !== null){
										$sql = "update t_voucher_pengeluaran set status_drp = NULL where voucher_pengeluaran_id = ".$_GET['voucher_pengeluaran_id'];
										Yii::$app->db->createCommand($sql)->execute();
									}
									// exec ini jika proses edit
								}
								foreach($_POST['TVoucherPengeluarandetail'] as $i => $detail){
									$modDetail = new \app\models\TVoucherPengeluarandetail();
									$modDetail->attributes = $detail;
									$modDetail->voucher_pengeluaran_id = $model->voucher_pengeluaran_id;
									if($modDetail->validate()){
										if($modDetail->save()){
											$success_2 &= true;
										}else{
											$success_2 = false;
										}
									}else{
										$success_2 = false;
									}
								}
							}
						}
						if(isset($_POST['TTerimaBhp'])){
							if(count($_POST['TTerimaBhp'])>0){
								foreach($_POST['TTerimaBhp'] as $ii => $terimadetail){
									$modTerima = \app\models\TTerimaBhp::findOne($terimadetail['terima_bhp_id']);
									$modTerima->voucher_pengeluaran_id = $model->voucher_pengeluaran_id;
									if($modTerima->save()){
										$success_4 &= true;
									}else{
										$success_4 = false;
									}
								}
							}
						}
						if(isset($_POST['TSpo'])){
							if(count($_POST['TSpo'])>0){
								foreach($_POST['TSpo'] as $ii => $podetail){
									$modSPO = \app\models\TSpo::findOne($podetail['spo_id']);
									$modSPO->voucher_pengeluaran_id = $model->voucher_pengeluaran_id;
									if($modSPO->save()){
										$success_3 &= true;
									}else{
										$success_3 = false;
									}
								}
							}
						}
						if(isset($_POST['TDpBhp'])){
							if(count($_POST['TDpBhp'])>0){
								foreach($_POST['TDpBhp'] as $ii => $dpdetail){
									$modDp = \app\models\TDpBhp::findOne($dpdetail['dp_bhp_id']);
									if($model->tipe == "Pembelian BHP"){
										$modDp->pemakaian_voucher = $model->voucher_pengeluaran_id;
									}else if($model->tipe == "Pembayaran DP BHP"){
										$modDp->pembayaran_voucher = $model->voucher_pengeluaran_id;
									}
									if($modDp->save()){
										$success_5 &= true;
									}else{
										$success_5 = false;
									}
								}
							}
						}
						if( ($_POST['TVoucherPengeluaran']['tipe'] == "Top-up Kas Kecil") && (isset($_POST['TVoucherPengeluaran']['ppk_id'])) ){
							if(!empty($_POST['TVoucherPengeluaran']['ppk_id'])){
								$modPpk = \app\models\TPpk::findOne($_POST['TVoucherPengeluaran']['ppk_id']);
								$modPpk->voucher_pengeluaran_id = $model->voucher_pengeluaran_id;
								if( $modPpk->validate() ){
									$success_6 = $modPpk->save();
								}else{
									$success_6 = false;
								}
							}else{
								$success_6 = false;
							}
						}
						if( ( $_POST['TVoucherPengeluaran']['tipe'] == "Ganti Kas Besar" ) || ( $_POST['TVoucherPengeluaran']['tipe'] == "Ganti Kas Kecil" ) ){
							if(!empty($_POST['TVoucherPengeluaran']['gkk_id'])){
								$modGkk = \app\models\TGkk::findOne($_POST['TVoucherPengeluaran']['gkk_id']);
								$modGkk->voucher_pengeluaran_id = $model->voucher_pengeluaran_id;
								if( $modGkk->validate() ){
									$success_7 = $modGkk->save();
								}else{
									$success_7 = false;
								}
							}else{
								$success_7 = false;
							}
						}
						if( $_POST['TVoucherPengeluaran']['tipe'] == "Uang Dinas Grader" ){
							if(!empty($_POST['TVoucherPengeluaran']['ajuandinas_grader_id'])){
								$modAjuanGrader = \app\models\TAjuandinasGrader::findOne($_POST['TVoucherPengeluaran']['ajuandinas_grader_id']);
								$modAjuanGrader->voucher_pengeluaran_id = $model->voucher_pengeluaran_id;
								if( $modAjuanGrader->validate() ){
									$success_8 = $modAjuanGrader->save();
								}else{
									$success_8 = false;
								}
							}else{
								$success_8 = false;
							}
						}
						if( $_POST['TVoucherPengeluaran']['tipe'] == "Uang Makan Grader" ){
							if(!empty($_POST['TVoucherPengeluaran']['ajuanmakan_grader_id'])){
								$modAjuanMakan = \app\models\TAjuanmakanGrader::findOne($_POST['TVoucherPengeluaran']['ajuanmakan_grader_id']);
								$modAjuanMakan->voucher_pengeluaran_id = $model->voucher_pengeluaran_id;
								if( $modAjuanMakan->validate() ){
									$success_9 = $modAjuanMakan->save();
								}else{
									$success_9 = false;
								}
							}else{
								$success_9 = false;
							}
						}
						if( $_POST['TVoucherPengeluaran']['tipe'] == "Pembayaran DP Log" ){
							if(!empty($_POST['TVoucherPengeluaran']['log_bayar_dp_id'])){
								$modLogBayarDp = \app\models\TLogBayarDp::findOne($_POST['TVoucherPengeluaran']['log_bayar_dp_id']);
								$modLogBayarDp->voucher_pengeluaran_id = $model->voucher_pengeluaran_id;
								if( $modLogBayarDp->validate() ){
									$success_10 = $modLogBayarDp->save();
								}else{
									$success_10 = false;
								}
							}else{
								$success_10 = false;
							}
						}
						if( $_POST['TVoucherPengeluaran']['tipe'] == "Pelunasan Log" ){
							if(!empty($_POST['TVoucherPengeluaran']['log_bayar_muat_id'])){
								$modLogBayarMuat = \app\models\TLogBayarMuat::findOne($_POST['TVoucherPengeluaran']['log_bayar_muat_id']);
								$modLogBayarMuat->voucher_pengeluaran_id = $model->voucher_pengeluaran_id;
								if( $modLogBayarMuat->validate() ){
									$success_11 = $modLogBayarMuat->save();
								}else{
									$success_11 = false;
								}
							}else{
								$success_11 = false;
							}
						}
						if( $_POST['TVoucherPengeluaran']['tipe'] == "Open Voucher" ){
							if(!empty($_POST['TVoucherPengeluaran']['open_voucher_id'])){
								$modOpenVoucher = \app\models\TOpenVoucher::findOne($_POST['TVoucherPengeluaran']['open_voucher_id']);
								$modOpenVoucher->voucher_pengeluaran_id = $model->voucher_pengeluaran_id;
								$modOpenVoucher->status_bayar = $model->status_bayar;
								if( $modOpenVoucher->validate() ){
									$success_12 = $modOpenVoucher->save();
								}else{
									$success_12 = false;
								}
							}else{
								$success_12 = false;
							}
						}
                    }
                }
				
				// echo "<pre>1";
				// print_r($success_1);
				// echo "<pre>2";
				// print_r($success_2);
				// echo "<pre>3";
				// print_r($success_3);
				// echo "<pre>4";
				// print_r($success_4);
				// echo "<pre>5";
				// print_r($success_5);
				// echo "<pre>6";
				// print_r($success_6);
				// echo "<pre>7";
				// print_r($success_7);
				// echo "<pre>8";
				// print_r($success_8);
				// echo "<pre>9";
				// print_r($success_9);
				// echo "<pre>10";
				// print_r($success_10);
				// echo "<pre>11";
				// print_r($success_11);
				// echo "<pre>12";
				// print_r($success_12);
				// exit;
				// print_r($model->biaya_tambahan); exit;
				
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5 && $success_6 && $success_7 && $success_8 && $success_9 && $success_10 && $success_11 && $success_12) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Voucher Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modDetails'=>$modDetails,'modDetailPO'=>$modDetailPO]);
	
	}
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new \app\models\TVoucherPengeluarandetail();
            $data['item'] = $this->renderPartial('_itemDetail',['modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }
	
	public function actionEditItem(){
        if(\Yii::$app->request->isAjax){
			$voucher_pengeluaran_id = \Yii::$app->request->post('voucher_pengeluaran_id');
            $modDetails = \app\models\TVoucherPengeluarandetail::find()->where(['voucher_pengeluaran_id'=>$voucher_pengeluaran_id])->orderBy('voucher_detail_id')->all();
			$data['item'] = '';
			if(count($modDetails)>0){
				foreach($modDetails as $i => $detail){
					$detail->jumlah = \app\components\DeltaFormatter::formatNumberForUserFloat($detail->jumlah, 2);
					$data['item'] .= $this->renderPartial('_itemDetail',['modDetail'=>$detail,'edit'=>true]);
				}
			}
            return $this->asJson($data);
        }
    }
	
	public function actionSetTipe(){
		if(\Yii::$app->request->isAjax){
			$tipe = \Yii::$app->request->post('tipe');
			if(empty($tipe)){ return false; }
			
			$html = '<option value=""></option>';
			switch ($tipe){
			case "Pembelian BHP":
				$sql = "SELECT * FROM t_spo JOIN m_suplier ON m_suplier.suplier_id = t_spo.suplier_id WHERE spo_status_bayar = 'PENDING' ORDER BY spo_tanggal DESC";
				$mod = \Yii::$app->db->createCommand($sql)->queryAll();
				if(!empty($mod)){
					foreach($mod as $ii => $val){
						$output = $val['spo_kode']." - ".$val['suplier_nm'];
						$html .= \yii\bootstrap\Html::tag('option',$output,['value'=>$val['spo_kode']]);
					}
				}
				break;
			case "Pembelian Kayu Alam":
				$mod = \app\models\TLogBayarDp::find()->where(['status'=>'UNPAID'])->all();
				if(!empty($mod)){
					foreach($mod as $ii => $val){
						$output = $val->kode." - ".$val->logKontrak->suplier->suplier_nm_company;
						$html .= \yii\bootstrap\Html::tag('option',$output,['value'=>$val->kode]);
					}
				}
				break;
			case "Pembelian Sengon":
				$mod = \app\models\TTagihanSengon::find()->where(['status'=>'UNPAID'])->all();
				if(!empty($mod)){
					foreach($mod as $ii => $val){
						$unique = $val->kode."-".$val->no_urut;
						$output = $unique." - ".$val->nopol." - ".$val->posengon->suplier->suplier_nm;
						$html .= \yii\bootstrap\Html::tag('option',$output,['value'=>$unique]);
					}
				}
				break;
			}
			$data['html']= $html;
            return $this->asJson($data);
        }
	}
	
	public function actionSetBerkas(){
		if(\Yii::$app->request->isAjax){
			$no_berkas = \Yii::$app->request->post('no_berkas');
			$tipe = \Yii::$app->request->post('tipe');
			if(empty($no_berkas)){ return false; }
			$kode_berkas = substr($no_berkas, 0,3);
			$total = 0;
			switch ($tipe){
			case "Pembelian BHP":
				$mod = \app\models\TSpo::findOne(['spo_kode'=>$no_berkas]);
				$total = isset($mod->spo_total)?$mod->spo_total:0;
				break;
			case "Pembelian Kayu Alam":
				$mod = \app\models\TLogBayarDp::findOne(['kode'=>$no_berkas]);
				$total = isset($mod->total_dp)?$mod->total_dp:0;
				break;
			case "Pembelian Sengon":
				$kode_awal = substr($no_berkas, 0,12);
				$kode_akhir = substr($no_berkas, -2,2);
				$mod = \app\models\TTagihanSengon::findOne(['kode'=>$kode_awal,'no_urut'=>$kode_akhir]);
				$total = isset($mod->total_bayar)?$mod->total_bayar:0;
				break;
			}
			$data['totaldebit']= $total;
            return $this->asJson($data);
        }
	}
	
	public function actionSetDropdownSupplier(){
        if(\Yii::$app->request->isAjax){
			$produk_group = Yii::$app->request->post('produk_group');
			$type = \Yii::$app->request->post('type');
            $mod = [];
            $data['html'] = '';
            $data['type'] = '';
			$html = '<option value=""></option>';
			switch ($type){
			case "Pembelian BHP":
				$sql = "SELECT t_pengajuan_tagihan.suplier_id, m_suplier.suplier_nm FROM t_pengajuan_tagihan
						JOIN m_suplier ON m_suplier.suplier_id = t_pengajuan_tagihan.suplier_id
						JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_pengajuan_tagihan.terima_bhp_id
						WHERE m_suplier.active IS TRUE AND m_suplier.type = 'BHP'
							AND t_pengajuan_tagihan.cancel_transaksi_id IS NULL 
							AND t_pengajuan_tagihan.spo_id IS NOT NULL 
							AND t_pengajuan_tagihan.status = 'DITERIMA'
							AND t_terima_bhp.voucher_pengeluaran_id IS NULL 
							AND t_terima_bhp.cancel_transaksi_id IS NULL 
							AND lunas IS false
						GROUP BY 1,2
						ORDER BY m_suplier.suplier_nm ASC 
						";
				$mod = Yii::$app->db->createCommand($sql)->queryAll();
				$arraymap = \yii\helpers\ArrayHelper::map($mod, 'suplier_id', 'suplier_nm');
				foreach($mod as $i => $sup){
					$asd = \app\models\MSuplier::findOne($sup['suplier_id']);
					$html .= \yii\bootstrap\Html::tag('option',$asd->suplier_nm.", ".$asd->suplier_almt,['value'=>$asd->suplier_id]);
				}
				break;
			case "Pembayaran DP BHP":
				$sql = "SELECT t_dp_bhp.suplier_id FROM t_dp_bhp
						JOIN m_suplier ON m_suplier.suplier_id = t_dp_bhp.suplier_id
						WHERE m_suplier.active IS TRUE AND pembayaran_voucher IS NULL AND status = 'UNPAID'
						GROUP BY t_dp_bhp.suplier_id ";
				$mod = Yii::$app->db->createCommand($sql)->queryAll();
				$arraymap = \yii\helpers\ArrayHelper::map($mod, 'suplier_id', 'suplier_nm');
				foreach($mod as $i => $sup){
					$asd = \app\models\MSuplier::findOne($sup['suplier_id']);
					$html .= \yii\bootstrap\Html::tag('option',$asd->suplier_nm.", ".$asd->suplier_almt,['value'=>$asd->suplier_id]);
				}
				break;
			case "Top-up Kas Kecil":
				$sql = "SELECT * FROM t_ppk where voucher_pengeluaran_id IS NULL AND tipe = 'Kas Kecil' AND cancel_transaksi_id is null";
				$mod = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($mod as $i => $top){
					$html .= \yii\bootstrap\Html::tag('option',$top['kode']." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($top['tanggal'])." - ".\app\components\DeltaFormatter::formatNumberForUserFloat($top['nominal']),['value'=>$top['ppk_id']]);
				}
				break;
			case "Ganti Kas Besar":
				$sql = "SELECT * FROM t_bkk WHERE cancel_transaksi_id IS NULL AND voucher_pengeluaran_id IS NULL AND tipe = 'Kas Besar'";
				$mod = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($mod as $i => $kasbes){
					$html .= \yii\bootstrap\Html::tag('option',$kasbes['kode']." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($kasbes['tanggal'])." - ".\app\components\DeltaFormatter::formatNumberForUserFloat($kasbes['totalnominal']),['value'=>$kasbes['bkk_id']]);
				}
				break;
			case "Ganti Kas Kecil":
				$sql = "SELECT * FROM t_gkk WHERE cancel_transaksi_id IS NULL AND voucher_pengeluaran_id IS NULL";
				$mod = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($mod as $i => $gkk){
					$html .= \yii\bootstrap\Html::tag('option',$gkk['kode']." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($gkk['tanggal'])." - ".\app\components\DeltaFormatter::formatNumberForUserFloat($gkk['totalnominal']),['value'=>$gkk['gkk_id']]);
				}
				break;
			case "Uang Dinas Grader":
				$sql = "SELECT * FROM t_ajuandinas_grader 
						JOIN m_graderlog ON m_graderlog.graderlog_id = t_ajuandinas_grader.graderlog_id 
						JOIN t_approval ON t_approval.reff_no = t_ajuandinas_grader.kode 
						WHERE cancel_transaksi_id IS NULL 
							AND voucher_pengeluaran_id IS NULL 
							AND t_approval.status = '".\app\models\TApproval::STATUS_APPROVED."'";
				$mod = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($mod as $i => $pdg){
					$html .= \yii\bootstrap\Html::tag('option',$pdg['kode']." - ".$pdg['graderlog_nm']." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($pdg['tanggal']),['value'=>$pdg['ajuandinas_grader_id']]);
				}
				break;
			case "Uang Makan Grader":
				$sql = "SELECT * FROM t_ajuanmakan_grader 
						JOIN m_graderlog ON m_graderlog.graderlog_id = t_ajuanmakan_grader.graderlog_id 
						JOIN t_approval ON t_approval.reff_no = t_ajuanmakan_grader.kode 
						WHERE cancel_transaksi_id IS NULL 
							AND voucher_pengeluaran_id IS NULL 
							AND t_approval.status = '".\app\models\TApproval::STATUS_APPROVED."'";
				$mod = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($mod as $i => $pmg){
					$html .= \yii\bootstrap\Html::tag('option',$pmg['kode']." - ".$pmg['graderlog_nm']." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($pmg['tanggal']),['value'=>$pmg['ajuanmakan_grader_id']]);
				}
				break;
			case "Pembayaran DP Log":
				$sql = "SELECT * FROM t_log_bayar_dp 
                        JOIN t_approval ON t_approval.reff_no = t_log_bayar_dp.kode
                        WHERE voucher_pengeluaran_id IS NULL AND t_approval.level = 2 AND t_approval.status = '".\app\models\TApproval::STATUS_APPROVED."'";
				$mod = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($mod as $i => $pdl){
					$html .= \yii\bootstrap\Html::tag('option',$pdl['kode']." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($pdl['tanggal']),['value'=>$pdl['log_bayar_dp_id']]);
				}
				break;
			case "Pelunasan Log":
				$sql = "SELECT * FROM t_log_bayar_muat 
						WHERE voucher_pengeluaran_id IS NULL";
				$mod = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($mod as $i => $pdl){
					$html .= \yii\bootstrap\Html::tag('option',$pdl['kode']." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($pdl['tanggal']),['value'=>$pdl['log_bayar_muat_id']]);
				}
				break;
			case "Pembelian Sengon":
				$type = "LS";
				break;
			case "Open Voucher":
				$sql = "SELECT *,departement_nama FROM t_open_voucher 
                        JOIN m_departement ON m_departement.departement_id = t_open_voucher.departement_id
						WHERE cancel_transaksi_id IS NULL AND voucher_pengeluaran_id IS NULL AND cara_bayar = 'Transfer Bank' AND status_approve = 'APPROVED'";
				$mod = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($mod as $i => $hokya){
					$html .= \yii\bootstrap\Html::tag('option',$hokya['kode']." - ".$hokya['tipe']." - ".$hokya['departement_nama'],['value'=>$hokya['open_voucher_id']]);
				}
				break;
			}
			
			$data['html'] = $html;
			$data['type'] = $type;
			return $this->asJson($data);
		}
    }
	
	public function actionSetDetailReff(){
		if(\Yii::$app->request->isAjax){
			$supplier_id = \Yii::$app->request->post('supplier_id');
			$ppk_id = \Yii::$app->request->post('ppk_id');
			$gkk_id = \Yii::$app->request->post('gkk_id');
			$ajuandinas_grader_id = \Yii::$app->request->post('ajuandinas_grader_id');
			$ajuanmakan_grader_id = \Yii::$app->request->post('ajuanmakan_grader_id');
			$log_bayar_dp_id = \Yii::$app->request->post('log_bayar_dp_id');
			$log_bayar_muat_id = \Yii::$app->request->post('log_bayar_muat_id');
			$type = \Yii::$app->request->post('type');
			$voucher_pengeluaran_id = \Yii::$app->request->post('voucher_pengeluaran_id');
			$open_voucher_id = \Yii::$app->request->post('open_voucher_id');
			$data['htmlspo'] = "";
			$data['htmlterima'] = "";
			$data['voucher'] = "";
			switch ($type){
				case "Pembelian BHP":
					if(!empty($supplier_id)){
						if(!empty($voucher_pengeluaran_id)){
							$modSPO = \app\models\TSpo::find()->where(['voucher_pengeluaran_id'=>$voucher_pengeluaran_id])->orderBy(['spo_tanggal'=>SORT_DESC])->all();
							$modTerima = \app\models\TTerimaBhp::find()
										->select("t_terima_bhp.*, b.tanggal, b.keterangan")
										->join("LEFT JOIN", "t_pengajuan_tagihan AS b", "b.terima_bhp_id = t_terima_bhp.terima_bhp_id")
										->where(['voucher_pengeluaran_id'=>$voucher_pengeluaran_id])
										->orderBy(['tglterima'=>SORT_DESC])->all();
							$modDps = \app\models\TDpBhp::find()->where(['pemakaian_voucher'=>$voucher_pengeluaran_id])->orderBy(['created_at'=>SORT_DESC])->all();
							$data['voucher'] = \app\models\TVoucherPengeluaran::findOne($voucher_pengeluaran_id)->attributes;
						}else{
							$modSPO = \app\models\TSpo::find()->where("voucher_pengeluaran_id IS NULL AND suplier_id = ".$supplier_id." AND approve_status = 'APPROVED' AND cancel_transaksi_id IS NULL")->orderBy(['spo_tanggal'=>SORT_DESC])->all();
							$modTerima = \app\models\TTerimaBhp::find()
										->select("t_terima_bhp.*, b.tanggal, b.keterangan")
										->join("JOIN", "t_pengajuan_tagihan AS b", "b.terima_bhp_id = t_terima_bhp.terima_bhp_id")
										->where("voucher_pengeluaran_id IS NULL AND t_terima_bhp.suplier_id = ".$supplier_id." AND t_terima_bhp.cancel_transaksi_id IS NULL AND t_terima_bhp.spo_id IS NOT NULL")
										->andWhere("b.cancel_transaksi_id IS NULL AND b.spo_id IS NOT NULL AND b.status = 'DITERIMA'")
										->andWhere("totalbayar > 0")
										->andWhere('lunas is false')
										//->andWhere("terima_bhp_id NOT IN( SELECT terima_bhp_id FROM t_retur_bhp JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhpd_id = t_retur_bhp.terima_bhpd_id WHERE suplier_id = ".$supplier_id." GROUP BY terima_bhp_id )")
										->orderBy(['tglterima'=>SORT_DESC])->all();
							$modDps = \app\models\TDpBhp::find()->where("suplier_id = ".$supplier_id." AND status = 'PAID' AND pemakaian_voucher IS NULL")->orderBy(['created_at'=>SORT_DESC])->all();
						}
						$modSuplier = \app\models\MSuplier::findOne($supplier_id);
						$data['nama_bank'] = $modSuplier->suplier_bank;
						$data['rekening'] = $modSuplier->suplier_norekening;
						$data['an_bank'] = $modSuplier->suplier_an_rekening;
						if(count($modTerima)>0){
							$gkk_id = 'bukan gkk';
							$data['htmlterima'] = $this->renderPartial('_detailTerima',['modDetail'=>$modTerima,'voucher_pengeluaran_id'=>$voucher_pengeluaran_id, 'supplier_id'=>$supplier_id, 'gkk'=>$gkk_id]);
						}
						if(count($modDps)>0){
							$data['htmldp'] = $this->renderPartial('_detailDp',['modDps'=>$modDps,'voucher_pengeluaran_id'=>$voucher_pengeluaran_id,'pakaidp'=>true, 'supplier_id'=>$supplier_id]);
						}
						if(count($modSPO)>0){
							$data['htmlspo'] = $this->renderPartial('_detailPO',['modDetail'=>$modSPO,'voucher_pengeluaran_id'=>$voucher_pengeluaran_id]);
						}
					}
					break;
				case "Pembayaran DP BHP":
					if(!empty($supplier_id)){
						if(!empty($voucher_pengeluaran_id)){
							$modDps = \app\models\TDpBhp::find()->where(['pembayaran_voucher'=>$voucher_pengeluaran_id])->orderBy(['created_at'=>SORT_DESC])->all();
							$data['voucher'] = \app\models\TVoucherPengeluaran::findOne($voucher_pengeluaran_id)->attributes;
						}else{
							$modDps = \app\models\TDpBhp::find()->where(['suplier_id'=>$supplier_id,'cara_bayar'=>'Transfer'])->andWhere("pembayaran_voucher IS NULL AND status = 'UNPAID'")->orderBy(['created_at'=>SORT_DESC])->all();
						}
						$modSuplier = \app\models\MSuplier::findOne($supplier_id);
						$data['nama_bank'] = $modSuplier->suplier_bank;
						$data['rekening'] = $modSuplier->suplier_norekening;
						$data['an_bank'] = $modSuplier->suplier_an_rekening;
						$data['htmldp'] = $this->renderPartial('_detailDp',['modDps'=>$modDps,'voucher_pengeluaran_id'=>$voucher_pengeluaran_id, 'supplier_id'=>$supplier_id]);
					}
					break;
				case "Top-up Kas Kecil":
					if(!empty($ppk_id)){
						$modPpk = \app\models\TPpk::findOne($ppk_id);
						if(!empty($modPpk)){
							$sql = "SELECT tanggal, SUM(nominal) AS nominal FROM t_kas_kecil
									WHERE t_kas_kecil.tanggal <= '".$modPpk->tanggal."' 
									GROUP BY tanggal ORDER BY tanggal DESC LIMIT 5";
							$mods = \Yii::$app->db->createCommand($sql)->queryAll();
							$data['modPpk'] = $modPpk->attributes;
							$data['htmlppk'] = $this->renderPartial('_detailPpk',['modPengeluaranKasKecil'=>$mods,'voucher_pengeluaran_id'=>$voucher_pengeluaran_id]);
						}
					}
					break;
				case "Ganti Kas Besar":
					if(!empty($bkk_id)){
						$modBkk = \app\models\TBkk::findOne($bkk_id);
						if(!empty($modBkk)){
							$modDetail = \yii\helpers\Json::decode($modBkk->deskripsi);
							$total = 0;
							if(count($modDetail)>0){
								foreach($modDetail as $i => $det){
									$total += $det['detail_nominal'];
								}
							}
							$data['modBkk'] = $modBkk->attributes;
							$data['htmlbkk'] = $this->renderPartial('_detailBkk',['modBkk'=>$modBkk,'modDetail'=>$modDetail]);
						}
					}
					break;
				case "Ganti Kas Kecil":
					if(!empty($gkk_id)){
						$modGkk = \app\models\TGkk::findOne($gkk_id);
						if(!empty($modGkk)){
							$modDetail = \yii\helpers\Json::decode($modGkk->deskripsi);
							$total = 0;
							$modTBPs = [];
							if(count($modDetail)>0){
								foreach($modDetail as $i => $det){
									$total += $det['detail_nominal'];
								}
							}
							$data['modGkk'] = $modGkk->attributes;
							
							if(!empty($modGkk->tbp_reff)){
								foreach(explode(",", $modGkk->tbp_reff) as $i => $tbp){
									$modTBP = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>$tbp]);
									$modTBPs[] = $modTBP;
									$supplier_id = $modTBP->suplier_id;
								}
								if(count($modTBPs)>0){
									$data['htmlterima'] = $this->renderPartial('_detailTerima',['modDetail'=>$modTBPs,'voucher_pengeluaran_id'=>$voucher_pengeluaran_id,'supplier_id'=>$supplier_id, 'gkk'=>$gkk_id]);
								}
							}
							$data['htmlgkk'] = $this->renderPartial('_detailGkk',['modGkk'=>$modGkk,'modDetail'=>$modDetail,'modTBPs'=>$modTBPs]);
						}
					}
					break;
				case "Uang Dinas Grader":
					if(!empty($ajuandinas_grader_id)){
						$modAjuanDinas = \app\models\TAjuandinasGrader::findOne($ajuandinas_grader_id);
						if(!empty($modAjuanDinas)){
							$modDetail = \app\models\TRealisasidinasGrader::find()->where(['dkg_id'=>$modAjuanDinas->dkg_id])->orderBy(['realisasidinas_grader_id'=>SORT_DESC])->all();
							$data['modAjuanDinas'] = $modAjuanDinas->attributes;
							$data['htmlpdg'] = $this->renderPartial('_detailPdg',['modAjuanDinas'=>$modAjuanDinas,'modDetail'=>$modDetail]);
							$modGrader = \app\models\MGraderlog::findOne($modAjuanDinas->graderlog_id);
							$data['nama_bank'] = $modAjuanDinas->grader_bank;
							$data['rekening'] = $modAjuanDinas->grader_norek;
							$data['an_bank'] = $modGrader->graderlog_nm;
						}
					}
					break;
				case "Uang Makan Grader":
					if(!empty($ajuanmakan_grader_id)){
						$modAjuanMakan = \app\models\TAjuanmakanGrader::findOne($ajuanmakan_grader_id);
						if(!empty($modAjuanMakan)){
							$modDetail = \app\models\TRealisasimakanGrader::find()->where(['dkg_id'=>$modAjuanMakan->dkg_id])->orderBy(['realisasimakan_grader_id'=>SORT_DESC])->all();
							$data['modAjuanMakan'] = $modAjuanMakan->attributes;
							$data['htmlpmg'] = $this->renderPartial('_detailPmg',['modAjuanMakan'=>$modAjuanMakan,'modDetail'=>$modDetail]);
							$modGrader = \app\models\MGraderlog::findOne($modAjuanMakan->graderlog_id);
							$data['nama_bank'] = $modAjuanMakan->grader_bank;
							$data['rekening'] = $modAjuanMakan->grader_norek;
							$data['an_bank'] = $modGrader->graderlog_nm;
						}
					}
					break;
				case "Pembayaran DP Log":
					if(!empty($log_bayar_dp_id)){
						$modLogBayarDp = \app\models\TLogBayarDp::findOne($log_bayar_dp_id);
						if(!empty($modLogBayarDp)){
							$modKontrak = \app\models\TLogKontrak::findOne($modLogBayarDp->log_kontrak_id);
							$data['modLogBayarDp'] = $modLogBayarDp->attributes;
							$data['modKontrak'] = $modKontrak->attributes;
							$data['htmlpdl'] = $this->renderPartial('_detailPdl',['modLogBayarDp'=>$modLogBayarDp,'modKontrak'=>$modKontrak]);
						}
					}
					break;
				case "Pelunasan Log":
					if(!empty($log_bayar_muat_id)){
						$modLogBayarMuat = \app\models\TLogBayarMuat::findOne($log_bayar_muat_id);
						if(!empty($modLogBayarMuat)){
							$modKontrak = \app\models\TLogKontrak::findOne($modLogBayarMuat->log_kontrak_id);
							$modPengajuanPembelian = \app\models\TPengajuanPembelianlog::findOne($modLogBayarMuat->pengajuan_pembelianlog_id);
							$modLoglist = \app\models\TLoglist::findOne($modLogBayarMuat->loglist_id);
							$data['modLogBayarMuat'] = $modLogBayarMuat->attributes;
							$data['modKontrak'] = $modKontrak->attributes;
							$data['modPengajuanPembelian'] = $modPengajuanPembelian->attributes;
							$data['modLoglist'] = $modLoglist->attributes;
							$data['htmlmlg'] = $this->renderPartial('_detailMlg',['modLogBayarMuat'=>$modLogBayarMuat]);
						}
					}
					break;
				case "Open Voucher":
					if(!empty($open_voucher_id)){
						$modOpenVoucher = \app\models\TOpenVoucher::findOne($open_voucher_id);
                        $data['modOpenVoucher'] = $modOpenVoucher->attributes;
						if(!empty($modOpenVoucher)){
							$modOpenVoucherDetail = \app\models\TOpenVoucherDetail::find()->where("open_voucher_id = ".$open_voucher_id)->all();
							$data['htmlovk'] = $this->renderPartial('_detailOvk',['model'=>$modOpenVoucher,'modOpenVoucherDetail'=>$modOpenVoucherDetail, 'voucher_pengeluaran_id'=>$voucher_pengeluaran_id]);
							if($modOpenVoucher->penerima_reff_table == 'm_penerima_voucher'){
								$modPenerima = \app\models\MPenerimaVoucher::findOne($modOpenVoucher->penerima_voucher_id);
								$data['nama_bank'] = $modPenerima->rekening_bank;
								$data['rekening'] = $modPenerima->rekening_no;
								$data['an_bank'] = $modPenerima->rekening_an;
							} else if ($modOpenVoucher->penerima_reff_table == 'm_suplier'){
								$modPenerima = \app\models\MSuplier::findOne($modOpenVoucher->penerima_reff_id);
								$data['nama_bank'] = $modPenerima->suplier_bank;
								$data['rekening'] = $modPenerima->suplier_norekening;
								$data['an_bank'] = $modPenerima->suplier_an_rekening;
							}
						}
                        if(!empty($voucher_pengeluaran_id)){
							$data['voucher'] = \app\models\TVoucherPengeluaran::findOne($voucher_pengeluaran_id)->attributes;
                        } else {
							
						}
					}
					break;
			}
			return $this->asJson($data);
        }
	}
	
	public function actionGetDetailTerima(){
		if(\Yii::$app->request->isAjax){
            $terima_bhp_id = Yii::$app->request->post('terima_bhp_id');
			$data['html'] = "";
			$html = "";
			$model = \app\models\TTerimaBhp::findOne($terima_bhp_id);
			$modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$terima_bhp_id])->all();
			$modPengajuan = \app\models\TPengajuanTagihan::findOne(['terima_bhp_id'=>$terima_bhp_id]);
			$kelengkapanberkas = !empty($modPengajuan)? \yii\helpers\Json::decode($modPengajuan->kelengkapan_berkas) : "";
			$kelengkapanberkas_html = "";
			if(!empty($kelengkapanberkas)){
				if(isset($kelengkapanberkas['is_notaasli'])){
					if($kelengkapanberkas['is_notaasli']=="1"){
						$kelengkapanberkas_html .= '<i class="fa fa-check font-green-haze"></i> Nota Asli'.( !empty($modPengajuan->nomor_nota)?" -> <b>No. : ".$modPengajuan->nomor_nota." </b>":"" );
					}else{
						$kelengkapanberkas_html .= '<i class="fa fa-remove font-red-flamingo"></i> Nota Asli';
					}
                    $kelengkapanberkas_html .= ( !empty($modPengajuan->tanggal_nota)?" -> <b>Tgl : ".\app\components\DeltaFormatter::formatDateTimeForUser2($modPengajuan->tanggal_nota)."</b>":"" )."<br>";
				}
				if(isset($kelengkapanberkas['is_kuitansi'])){
					if($kelengkapanberkas['is_kuitansi']=="1"){
						$kelengkapanberkas_html .= '<i class="fa fa-check font-green-haze"></i> Kuitansi'.( !empty($modPengajuan->nomor_kuitansi)?" -> <b>No. : ".$modPengajuan->nomor_kuitansi:"" )."<br>";
					}else{
						$kelengkapanberkas_html .= '<i class="fa fa-remove font-red-flamingo"></i> Kuitansi<br>';
					}
				}
				if(isset($kelengkapanberkas['is_fakturpajak'])){
					if($kelengkapanberkas['is_fakturpajak']=="1"){
						$kelengkapanberkas_html .= '<i class="fa fa-check font-green-haze"></i> Faktur'.( !empty($modPengajuan->no_fakturpajak)?" -> <b>No. : ".$modPengajuan->no_fakturpajak:"" )."<br>";
					}else{
						$kelengkapanberkas_html .= '<i class="fa fa-remove font-red-flamingo"></i> Faktur<br>';
					}
				}
				if(isset($kelengkapanberkas['is_suratjalan'])){
					if($kelengkapanberkas['is_suratjalan']=="1"){
						$kelengkapanberkas_html .= '<i class="fa fa-check font-green-haze"></i> Surat Jalan<br>';
					}else{
						$kelengkapanberkas_html .= '<i class="fa fa-remove font-red-flamingo"></i> Surat Jalan<br>';
					}
				}
				if(!empty($kelengkapanberkas['keterangan_berkas'])){
					$kelengkapanberkas_html .= $kelengkapanberkas['keterangan_berkas'];
				}
			}
			$ppn_item = 0;
			$total = 0;
			$pph_item = 0;
            $rowspan = 3;
			$total_pbbkb = !empty($model->total_pbbkb)?$model->total_pbbkb:0;
            $total_biayatambahan = !empty($model->total_biayatambahan)?$model->total_biayatambahan:0;
            $total_potongan = !empty($model->potonganharga) ? $model->potonganharga : 0;
            $rowspan = !empty($model->total_pbbkb)?((int)$rowspan+1):$rowspan;
            $rowspan = !empty($model->total_biayatambahan)?((int)$rowspan+1):$rowspan;
			$html .= "<table style='width:100%;' class='table table-striped table-bordered table-detail-terimabhp'>
						<tr style=''>
							<th style='font-size: 1.2rem;'>Item</th>
							<th style='font-size: 1.2rem;'>Qty</th>
							<th style='font-size: 1.2rem;'>Harga</th>
							<th style='font-size: 1.2rem;'>Subtotal</th>
						<tr>";
            $hargaRetur = 0;
            $totalsemuappn = 0;
            $totalreturppn = 0;
            $totalppn = 0;
            $totalsemuapph = 0;
            $totalreturpph = 0;
            $totalpph = 0;

            // hitung ppn dulu cuy
            // cek ppn di t_terima_bhp
            $total_ppn = $model->ppn_nominal;
            
            foreach($modDetail as $i => $detail){
                $modRetur = \app\models\TReturBhp::findOne(['terima_bhpd_id'=>$detail['terima_bhpd_id']]);

                // ppn
                if ($total_ppn > 0 || $total_ppn != "" || $total_ppn != null) {
                    $totalsemuappn = $total_ppn;
                } else {
                    // total semuah pph
                    $totalsemuappn += $detail->ppn_peritem;
                }
                
                if(!empty($modRetur)){
                    // hitung ppn yang diretur
                    $modPpnRetur = \app\models\TReturBhp::findOne(['terima_bhpd_id'=>$detail['terima_bhpd_id']]);
                    $totalreturppn += $modPpnRetur->ppn_nominal;
                }

                // pph
                if ($detail->pph_peritem != 0){
                    // total semuah pph
                    $totalsemuapph += $detail->pph_peritem;
                }
                
                if(!empty($modRetur)){
                    // hitung pph yang diretur
                    $modPphRetur = \app\models\TTerimaBhpDetail::findOne(['terima_bhpd_id'=>$detail['terima_bhpd_id']]);
                    $totalreturpph += $modPphRetur->pph_peritem;
                }
                
                // kolom ppn
                // kurangi semuah ppn dengan ppn yang diretur
                $totalppn = $totalsemuappn - $totalreturppn;

                // kolom pph
                // kurangi semuah pph dengan pph yang diretur
                $totalpph += $totalsemuapph - $totalreturpph;
                    
                $total += ($detail->terimabhpd_qty * $detail->terimabhpd_harga);
                $html .= "<tr style=';'>
                            <td style='font-size: 1.1rem;'>".$detail->bhp->Bhp_nm."</td>
                            <td style='font-size: 1.1rem;'>".$detail->terimabhpd_qty." ".$detail->bhp->bhp_satuan."</td>
                            <td style='font-size: 1.1rem; text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUser($detail->terimabhpd_harga)."</td>
                            <td style='font-size: 1.1rem; text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUser($detail->terimabhpd_qty * $detail->terimabhpd_harga)."</td>
                            <tr>";
                
                if(!empty($modRetur)){
                    $total_kembali = $modRetur->total_kembali;
                    $hargaRetur += $total_kembali;
                    $html .= "<tr style=''>
                                <td style='font-size: 1.1rem;' class='font-red-flamingo'><b>Retur</b> ".$detail->bhp->Bhp_nm."</td>
                                <td style='font-size: 1.1rem;' class='font-red-flamingo'>".$modRetur->qty." ".$detail->bhp->bhp_satuan."</td>
                                <td style='font-size: 1.1rem; text-align:right;' class='font-red-flamingo'>".\app\components\DeltaFormatter::formatNumberForUser($modRetur->harga)."</td>
                                <td style='font-size: 1.1rem; text-align:right;' class='font-red-flamingo'>(".\app\components\DeltaFormatter::formatNumberForUser($modRetur->total_kembali).")</td>
                                <tr>";
                }
            }
			if(!empty($model->ppn_nominal)){
				if($model->ppn_nominal!=0){
					$ppn_item = $model->ppn_nominal;
				}
			}
			$html .= "<tr style=''>
                            <td style='font-size: 1.2rem; text-align:left;' colspan='2' rowspan='{$rowspan}'>
							<u>Kelengkapan Berkas Ajuan</u> :<br>
							<span class='font-blue-steel'>
								{$kelengkapanberkas_html}
							</span>
							</td>
							<td style='font-size: 1.2rem; text-align:right;'><b>PPn &nbsp; </b></td>
							<td style='font-size: 1.2rem; text-align:right;' ><b>".
								\app\components\DeltaFormatter::formatNumberForUser($ppn_item)
							."</b></td>
					</tr>";
			$html .= "<tr style=''>
							<td style='font-size: 1.2rem; text-align:right;'><b>PPh &nbsp; </b></td>
							<td style='font-size: 1.2rem; text-align:right;' ><b>".
								\app\components\DeltaFormatter::formatNumberForUser($totalpph)
							."</b></td>
                        </tr>";
            if($total_pbbkb>0){
                $html .= "<tr style=''>
							<td style='font-size: 1.2rem; text-align:right;'><b>Pbbkb &nbsp; </b></td>
							<td style='font-size: 1.2rem; text-align:right;' ><b>".
								\app\components\DeltaFormatter::formatNumberForUser($total_pbbkb)
							."</b></td>
						</tr>";
            }
            if($total_biayatambahan>0){
                $html .= "<tr style=''>
							<td style='font-size: 1rem; text-align:right; padding: 2px;'><b>Biaya Tambahan &nbsp; </b>".( (!empty($model->label_biayatambahan))?"<br>".$model->label_biayatambahan:"" )."</td>
							<td style='font-size: 1.2rem; text-align:right;' ><b>".
								\app\components\DeltaFormatter::formatNumberForUser($total_biayatambahan)
							."</b></td>
						</tr>";
            }
            if($total_potongan>0){
                $html .= "<tr style=''>
							<td style='font-size: 1rem; text-align:right; padding: 2px;'><b>Potongan Harga &nbsp; </b>".( (!empty($model->label_potonganharga))?"<br>".$model->label_potonganharga:"" )."</td>
							<td style='font-size: 1.2rem; text-align:right;' ><b>".
                    \app\components\DeltaFormatter::formatNumberForUser($total_potongan)
                    ."</b></td>
						</tr>";
            }
            $html .= "<tr style=''>
							<td style='font-size: 1.2rem; text-align:right;'><b>Grand Total &nbsp; </b></td>
							<td style='font-size: 1.2rem; text-align:right;' ><b>".\app\components\DeltaFormatter::formatNumberForUser($total + $ppn_item - $hargaRetur - $totalpph + $total_pbbkb + $total_biayatambahan - $total_potongan)."</b></td>
						</tr>";
			$html .= "</table>";
			$data['html'] = $html;
			return $this->asJson($data);
		}
	}
	public function actionGetDetailPO(){
		if(\Yii::$app->request->isAjax){
            $spo_id = Yii::$app->request->post('spo_id');
			$data['html'] = "";
			$html = "";
			$model = \app\models\TSpo::findOne($spo_id);
			$modDetail = \app\models\TSpoDetail::find()->where(['spo_id'=>$spo_id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->all();
			$html .= "<table style='width:100%;' class='table table-striped table-bordered '>
						<tr>
							<th style='font-size: 1.2rem;'>Item</th>
							<th style='font-size: 1.2rem;'>Qty</th>
							<th style='font-size: 1.2rem;'>Harga</th>
							<th style='font-size: 1.2rem;'>Subtotal</th>
						<tr>";
			foreach($modDetail as $i => $detail){
				$html .= "<tr>
							<td style='font-size: 1.1rem;'>".$detail->bhp->bhp_nm."</td>
							<td style='font-size: 1.1rem;'>".$detail->spod_qty."</td>
							<td style='font-size: 1.1rem; text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUser($detail->spod_harga)."</td>
							<td style='font-size: 1.1rem; text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUser($detail->spod_qty * $detail->spod_harga)."</td>
						  <tr>";
			}
			$html .= "<tr>
							<td style='font-size: 1.2rem; text-align:right;' colspan='3'><b>Total PPn &nbsp; </b></td>
							<td style='font-size: 1.2rem; text-align:right;' ><b>".\app\components\DeltaFormatter::formatNumberForUser($model->spo_ppn_nominal)."</b></td>
						<tr>";
			$html .= "<tr>
							<td style='font-size: 1.2rem; text-align:right;' colspan='3'><b>Grand Total &nbsp; </b></td>
							<td style='font-size: 1.2rem; text-align:right;' ><b>".\app\components\DeltaFormatter::formatNumberForUser($model->spo_total)."</b></td>
						<tr>";
			$html .= "</table>";
			$data['html'] = $html;
			return $this->asJson($data);
		}
	}
    
	public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TVoucherPengeluaran::tableName();
				$param['pk']= $param['table'].".".\app\models\TVoucherPengeluaran::primaryKey()[0];
				$param['column'] = [$param['table'].'.voucher_pengeluaran_id', //0
									$param['table'].'.tipe', //1
									$param['table'].'.kode',//2
								   ['col_name'=>$param['table'].'.tanggal_bayar','formatter'=>'formatDateForUser2'], //3
									'm_suplier.suplier_nm',//4
								   ['col_name'=>'total_nominal'],//5
									$param['table'].'.status_bayar',//6
									$param['table'].'.cancel_transaksi_id', //7
									't_gkk.gkk_id', //8
									't_gkk.kode as gkk_kode', //9
									't_ppk.ppk_id', // 10
									't_ppk.kode as ppk_kode', // 11
									't_ajuandinas_grader.ajuandinas_grader_id', //12
									't_ajuandinas_grader.kode as pdg_kode', // 13
									't_ajuanmakan_grader.ajuanmakan_grader_id', //14
									't_ajuanmakan_grader.kode as pmg_kode', // 15
									't_log_bayar_dp.log_bayar_dp_id', //16
									't_log_bayar_dp.kode as kode_dp', //17
									't_log_bayar_muat.log_bayar_muat_id', //18
									't_log_bayar_muat.kode as kode_pelunasan', //19
									'm_penerima_voucher.nama_penerima as nama_penerima', //20
									'm_penerima_voucher.nama_perusahaan as nama_perusahaan', //21
                                    't_open_voucher.tipe AS tipe_openvoucher',//22
                                    't_open_voucher.keterangan', // 23
                                    'm_suplierOV.suplier_nm AS suplier_ov',// 24
                                    $param['table'].'.mata_uang AS mata_uang',// 25
									$param['table'].'.status_drp', //26
									"(SELECT t_pengajuan_drp.status_approve
										FROM t_pengajuan_drp_detail 
										JOIN t_pengajuan_drp ON t_pengajuan_drp.pengajuan_drp_id = t_pengajuan_drp_detail.pengajuan_drp_id
										WHERE t_pengajuan_drp_detail.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
										AND status_approve <> 'REJECTED' ORDER BY pengajuan_drp_detail_id DESC
										LIMIT 1) AS status_approve", //27
									't_asuransi.kepada', //28
									"(SELECT t_pengajuan_drp_detail.status_pengajuan
										FROM t_pengajuan_drp_detail 
										JOIN t_pengajuan_drp ON t_pengajuan_drp.pengajuan_drp_id = t_pengajuan_drp_detail.pengajuan_drp_id
										WHERE t_pengajuan_drp_detail.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
										AND status_approve <> 'REJECTED' ORDER BY pengajuan_drp_detail_id DESC
										LIMIT 1) AS status_pengajuan", //29
									'a.graderlog_nm as grader_makan',
 	                                'c.graderlog_nm as grader_dinas',
									'm_suplier.suplier_nm_company',
									'm_suplierOV.suplier_nm_company AS company_ov'
                                    ]; 
				$param['join']= ['LEFT JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id AND '.$param['table'].'.suplier_id is not null
								  LEFT JOIN t_gkk ON t_gkk.voucher_pengeluaran_id = '.$param['table'].'.voucher_pengeluaran_id
								  LEFT JOIN t_ppk ON t_ppk.voucher_pengeluaran_id = '.$param['table'].'.voucher_pengeluaran_id
								  LEFT JOIN t_ajuandinas_grader ON t_ajuandinas_grader.voucher_pengeluaran_id = '.$param['table'].'.voucher_pengeluaran_id
								  LEFT JOIN t_ajuanmakan_grader ON t_ajuanmakan_grader.voucher_pengeluaran_id = '.$param['table'].'.voucher_pengeluaran_id
								  LEFT JOIN t_log_bayar_dp ON t_log_bayar_dp.voucher_pengeluaran_id = '.$param['table'].'.voucher_pengeluaran_id
								  LEFT JOIN t_log_bayar_muat ON t_log_bayar_muat.voucher_pengeluaran_id = '.$param['table'].'.voucher_pengeluaran_id
								  LEFT JOIN t_open_voucher ON t_open_voucher.voucher_pengeluaran_id = '.$param['table'].'.voucher_pengeluaran_id
								  LEFT JOIN m_penerima_voucher ON m_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id
								  LEFT JOIN m_suplier AS m_suplierOV ON m_suplierOV.suplier_id = t_open_voucher.penerima_reff_id AND t_open_voucher.penerima_reff_id is not null
								  LEFT JOIN t_asuransi ON t_asuransi.kode = t_open_voucher.reff_no
								  LEFT JOIN m_graderlog AS a ON a.graderlog_id = t_ajuanmakan_grader.graderlog_id
                                  LEFT JOIN m_graderlog AS c ON c.graderlog_id = t_ajuandinas_grader.graderlog_id
                                '];
								// LEFT JOIN t_pengajuan_drp_detail ON t_pengajuan_drp_detail.voucher_pengeluaran_id = '.$param['table'].'.voucher_pengeluaran_id
								// LEFT JOIN t_pengajuan_drp ON t_pengajuan_drp.pengajuan_drp_id = t_pengajuan_drp_detail.pengajuan_drp_id
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionChangeStatus(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->get('id');
			$model = new \app\models\TVoucherPengeluaran(['scenario'=> \app\models\TVoucherPengeluaran::SCENARIO_STATUS_PAID]);
			$modVoucher = \app\models\TVoucherPengeluaran::findOne($id);
			$model->tanggal_bayar = \app\components\DeltaFormatter::formatDateTimeForUser2($modVoucher->tanggal_bayar);
			$model->urutan_kode = '000';
			$model->kode = \app\components\DeltaGenerator::kodeBuktiBankKeluar($modVoucher->akun_debit,$modVoucher->tanggal_bayar);
			$pesan = "Yakin akan merubah status bayar menjadi 'PAID'?";
            if( isset($_POST['TVoucherPengeluaran']) ){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_voucher_pengeluaran
                    $success_2 = true; // t_dp_bhp (update)
                    $success_3 = true; // h_kas_dinasgrader & h_kas_makangrader
                    $success_4 = true; // t_log_bayar_dp
                    $success_5 = true; // update t_pengajuan_tagihan (melalui kas kecil)
                    $success_6 = true; // update status bayar di t_open_voucher
					$model = \app\models\TVoucherPengeluaran::findOne($id);
					if(!empty($model)){
						if($model->status_bayar == 'UNPAID'){
							$model->status_bayar = "PAID";
							$model->tanggal_bayar = $_POST['TVoucherPengeluaran']['tanggal_bayar'];
							$model->kode = $_POST['TVoucherPengeluaran']['kode'];
							$model->totaldebit = $model->total_nominal;
							if($model->validate()){
								if($model->save()){
									$success_1 = true;
									// start update t_dp_bhp
									$modDps = \app\models\TDpBhp::find()->where(['pembayaran_voucher'=>$model->voucher_pengeluaran_id])->all();
									foreach($modDps as $i => $dp){
										$dp->status = $model->status_bayar;
										if($dp->validate()){
											$success_2 &= $dp->save();
										}else{
											$success_2 = false;
										}
									}
									// end update t_dp_bhp
									
									// Start Proses Update Saldo Kas Grader
									if($model->tipe == 'Uang Dinas Grader'){
										$modAjuanDinas = \app\models\TAjuandinasGrader::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
										if(!empty($modAjuanDinas)){
											$modAjuanDinas->reff_no = $modAjuanDinas->kode;
											$modAjuanDinas->nominal_in = $modAjuanDinas->total_ajuan;
											$modAjuanDinas->nominal_out = 0;
											$success_3 = \app\models\HKasDinasgrader::updateSaldoKas($modAjuanDinas);
										}
									}
									if($model->tipe == 'Uang Makan Grader'){
										$modAjuanMakan = \app\models\TAjuanmakanGrader::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
										if(!empty($modAjuanMakan)){
											$modAjuanMakan->reff_no = $modAjuanMakan->kode;
											$modAjuanMakan->nominal_in = $modAjuanMakan->total_ajuan;
											$modAjuanMakan->nominal_out = 0;
											$success_3 = \app\models\HKasMakangrader::updateSaldoKas($modAjuanMakan);
										}
									}
									// End Proses Update Saldo Kas Grader
									// 
									// Start Proses Update t_log_bayar_dp
									if($model->tipe == 'Pembayaran DP Log'){
										$modBayarDpLog = \app\models\TLogBayarDp::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
										if(!empty($modBayarDpLog)){
											$modBayarDpLog->status = $model->status_bayar;
											if($modBayarDpLog->validate()){
												$success_4 &= $modBayarDpLog->save();
											}else{
												$success_4 = false;
											}
										}
									}
									// End Proses Update t_log_bayar_dp
									
									// Start Proses Update t_pengajuan_tagihan
									if($model->tipe == 'Ganti Kas Kecil'){
										$modGkk = \app\models\TGkk::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
										if(!empty($modGkk->tbp_reff)){
											$tbps = explode(",", $modGkk->tbp_reff);
											foreach($tbps as $i => $tbp){
												$modTbp = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>$tbp]);
												$modPengajuanTagihan = \app\models\TPengajuanTagihan::findOne(['terima_bhp_id'=>$modTbp->terima_bhp_id]);
												$modPengajuanTagihan->status = "DIREALISASI";
												if($modPengajuanTagihan->validate()){
													$success_5 &= $modPengajuanTagihan->save();
												}else{
													$success_5 = false;
												}
											}
										}
									}
									// End Proses Update t_pengajuan_tagihan
                                    
									// Start Proses Update t_open_voucher
									if($model->tipe == 'Open Voucher'){
										$modOpenVoucher = \app\models\TOpenVoucher::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
										if(!empty($modOpenVoucher)){
											$modOpenVoucher->status_bayar = $model->status_bayar;
											if($modOpenVoucher->validate()){
												$success_6 &= $modOpenVoucher->save();
                                                
                                                // start insert saldo suplier
                                                if($modOpenVoucher->tipe = "DP LOG SENGON" || $modOpenVoucher->tipe = "PELUNASAN LOG SENGON"){
                                                    $modSaldoSuplier = new \app\models\HSaldoSuplier();
                                                    $modSaldoSuplier->tipe = "LS";
                                                    $modSaldoSuplier->tanggal = $model->tanggal_bayar;
                                                    $modSaldoSuplier->suplier_id = $modOpenVoucher->penerima_reff_id;
                                                    $modSaldoSuplier->reff_no = $model->kode;
                                                    $modSaldoSuplier->deskripsi = "PEMBAYARAN OPEN VOUCHER ".$modOpenVoucher->kode;
                                                    $modSaldoSuplier->nominal_in = $model->total_nominal;
                                                    $modSaldoSuplier->nominal_out = 0;
                                                    $modSaldoSuplier->active = TRUE;
                                                    if($modSaldoSuplier->validate()){
                                                        $success_6 &= $modSaldoSuplier->save();
                                                    }
                                                }
                                                // end insert saldo suplier
                                                
											}else{
												$success_6 = false;
											}
										}
									}
									// End Proses Update t_open_voucher
								}
							}else{
                                $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                            }
						}
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
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Diupdate');
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
			return $this->renderAjax('_setPaid',['model'=>$model,'id'=>$id,'pesan'=>$pesan,'actionname'=>'changeStatus','tableid'=>'table-aftersave']);
		}
	}
	
	public function actionDetailBbk(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$kode = (isset($_GET['kode']))?Yii::$app->request->get('kode'):'';
			$model = \app\models\TVoucherPengeluaran::findOne($_GET['id']);
			$modDetail = \app\models\TVoucherPengeluarandetail::find()->where(['voucher_pengeluaran_id'=>$_GET['id']])->orderBy(['voucher_detail_id'=>SORT_ASC])->all();
			$modDrp = \app\models\TPengajuanDrp::findOne(['kode'=>$kode]);
			$paramprint['judul'] = Yii::t('app', 'BUKTI BANK KELUAR');
			return $this->renderAjax('detailBbk',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint, 'modDrp'=>$modDrp]);
        }
	}
	
	public function actionPrintBbk(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TVoucherPengeluaran::findOne($_GET['id']);
		$modDetail = \app\models\TVoucherPengeluarandetail::find()->where(['voucher_pengeluaran_id'=>$_GET['id']])->orderBy(['voucher_detail_id'=>SORT_ASC])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'BUKTI BANK KELUAR');
		if($caraprint == 'PRINT'){
			return $this->render('printBbk',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/finance/voucher/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/finance/voucher/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
	
	public function actionCancelVoucher($id){
		if(\Yii::$app->request->isAjax){
			$modVoucher = \app\models\TVoucherPengeluaran::findOne($id);
			$modVoucherDetails = \app\models\TVoucherPengeluarandetail::find()->where(['voucher_pengeluaran_id'=>$id])->all();
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_voucher_pengeluaran
                    $success_3 = true; // t_spo / t_spl
                    $success_4 = true; // t_terima_bhp
                    $success_5 = true; // t_dp_bhp
                    $success_6 = true; // t_ppk
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $modVoucher->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
                            if($modVoucher->updateAttributes(['cancel_transaksi_id'=>$modCancel->cancel_transaksi_id])){
								$success_2 = TRUE;
							}else{
								$success_2 = FALSE;
							}
							
							$modSPO = \app\models\TSpo::find()->where(['voucher_pengeluaran_id'=>$modVoucher->voucher_pengeluaran_id])->all();
							if(count($modSPO)){
								foreach($modSPO as $i => $spo){
									$spo->voucher_pengeluaran_id = NULL;
									if($spo->save()){
										$success_3 &= true;
									}else{
										$success_3 = false;
									}
								}
							}
							$modPenerimaan = \app\models\TTerimaBhp::find()->where(['voucher_pengeluaran_id'=>$modVoucher->voucher_pengeluaran_id])->all();
							if(count($modPenerimaan)){
								foreach($modPenerimaan as $i => $terima){
									$terima->voucher_pengeluaran_id = NULL;
									if($terima->save()){
										$success_4 &= true;
									}else{
										$success_4 = false;
									}
								}
							}
							$modDp = \app\models\TDpBhp::find()->where(['pemakaian_voucher'=>$modVoucher->voucher_pengeluaran_id])->all();
							if(count($modDp)){
								foreach($modDp as $i => $dp){
									$dp->pemakaian_voucher = NULL;
									if($dp->save()){
										$success_5 &= true;
									}else{
										$success_5 = false;
									}
								}
							}
							$modPpk = \app\models\TPpk::find()->where(['voucher_pengeluaran_id'=>$modVoucher->voucher_pengeluaran_id])->all();
							if(count($modPpk)){
								foreach($modPpk as $i => $ppk){
									$ppk->voucher_pengeluaran_id = NULL;
									if($ppk->save()){
										$success_6 &= true;
									}else{
										$success_6 = false;
									}
								}
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel); 
                    }
					
//					echo "<pre>1";
//					print_r($success_1);
//					echo "<pre>2";
//					print_r($success_2);
//					echo "<pre>3";
//					print_r($success_3);
//					echo "<pre>4";
//					print_r($success_4);
//					echo "<pre>5";
//					print_r($success_5);
//					echo "<pre>5";
//					print_r($success_6);
//					exit;
					
                    if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5 && $success_6) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Voucher Berhasil di Batalkan');
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
			
			return $this->renderAjax('cancelVoucher',['modVoucher'=>$modVoucher,'modCancel'=>$modCancel]);
		}
	}
    
    public function actionSetAutoItems(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
            $terima_bhp_ids = \Yii::$app->request->post('terima_bhp_ids');
            $data['items'] = "";
            if(!empty($terima_bhp_ids)){
				// ada kemungkinan id kosong, jadi ambil yg ada isinya/angkanya saja
				$filter_ids = array_filter($terima_bhp_ids, function($id) {
					return !empty($id) && is_numeric($id); 
				});
				$terima_bhp_id = implode(",", $filter_ids);
                // $terima_bhp_id = implode(",", $terima_bhp_ids);
                $modTbps = \app\models\TTerimaBhp::find()
                            ->select("t_terima_bhp.*, b.tanggal_nota, b.keterangan, b.no_fakturpajak")
                            ->join("JOIN", "t_pengajuan_tagihan AS b", "b.terima_bhp_id = t_terima_bhp.terima_bhp_id")
                            ->where("voucher_pengeluaran_id IS NULL AND t_terima_bhp.terima_bhp_id IN (".$terima_bhp_id.") AND t_terima_bhp.cancel_transaksi_id IS NULL AND t_terima_bhp.spo_id IS NOT NULL")
                            ->andWhere("b.cancel_transaksi_id IS NULL AND b.spo_id IS NOT NULL AND b.status = 'DITERIMA'")
                            ->andWhere("totalbayar > 0")
                            ->orderBy(['tglterima'=>SORT_ASC])->all();
                if(count($modTbps)>0){
                    foreach($modTbps as $i => $tbp){
                        $sql = "SELECT * FROM t_terima_bhp_detail WHERE terima_bhp_id = ".$tbp->terima_bhp_id;
                        $mods = \Yii::$app->db->createCommand($sql)->queryAll();
                        if(count($mods)>0){
                            foreach($mods as $ii => $mo){
                                $modBhp = \app\models\MBrgBhp::findOne($mo['bhp_id']);
                                $modDetail = new \app\models\TVoucherPengeluarandetail();
                                $inv = "";
                                if($ii==0){
                                    $inv = "INV ".( !empty($tbp->nofaktur)?$tbp->nofaktur: date("Ymd") )." (". substr(\app\components\DeltaFormatter::formatDateTimeForUser($tbp->tanggal_nota), 0,-5).") ";
                                }
                                $modDetail->keterangan = $inv.$modBhp->Bhp_nm." (".$mo['terimabhpd_qty']." ".$modBhp->bhp_satuan.")";
                                $modDetail->jumlah = number_format( ($mo['terimabhpd_harga'] - $mo['terimabhpd_diskon'])*$mo['terimabhpd_qty'] );
                                $data['items'] .= $this->renderPartial('_itemDetail',['modDetail'=>$modDetail]);
                            }
                            if($tbp->ppn_nominal != 0){
                                $modDetail = new \app\models\TVoucherPengeluarandetail();
                                $modDetail->keterangan = "PPN / FP ".$tbp->no_fakturpajak;
                                $modDetail->jumlah = number_format($tbp->ppn_nominal);
                                $data['items'] .= $this->renderPartial('_itemDetail',['modDetail'=>$modDetail]);
                            }
                        }
                    }
                }
            }
            return $this->asJson($data);
        }
	}
    
    public function actionCariOpenVoucher(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-open-voucher'){
				$param['table']= \app\models\TOpenVoucher::tableName();
				$param['pk']= $param['table'].".".\app\models\TOpenVoucher::primaryKey()[0];
				$param['column'] = ['t_open_voucher.open_voucher_id',
                                    't_open_voucher.kode',
                                    't_open_voucher.tanggal',
                                    't_open_voucher.tipe',
                                    'm_departement.departement_nama',
                                    't_open_voucher.reff_no',
                                    "(CASE 
                                        WHEN t_open_voucher.tipe='REGULER' THEN (SELECT CONCAT('<b>',nama_penerima,'</b><br>',nama_perusahaan) FROM m_penerima_voucher WHERE m_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id )
                                        WHEN t_open_voucher.tipe='PEMBAYARAN LOG ALAM' THEN (SELECT CONCAT('<b>',suplier_nm_company,'</b><br>',suplier_nm) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
                                        WHEN t_open_voucher.tipe='DEPOSIT SUPPLIER LOG' THEN (SELECT CONCAT('<b>',suplier_nm,'</b><br>',suplier_nm_company) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
                                        WHEN t_open_voucher.tipe='DP LOG SENGON' THEN (SELECT CONCAT('<b>',suplier_nm,'</b><br>',suplier_almt) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
                                        WHEN t_open_voucher.tipe='PELUNASAN LOG SENGON' THEN (SELECT CONCAT('<b>',suplier_nm,'</b><br>',suplier_almt) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
										WHEN t_open_voucher.tipe='PEMBAYARAN ASURANSI LOG SHIPPING' THEN (SELECT kepada FROM t_asuransi WHERE t_asuransi.kode = t_open_voucher.reff_no )
                                      ELSE '' END) AS penerima",
                                    't_open_voucher.total_pembayaran',
                                    'pegawai.pegawai_nama AS prepared_by', // 8
                                    't_open_voucher.keterangan', //9
                                    't_open_voucher.status_bayar', // 10 
                                    't_open_voucher.status_approve', //11
                                    't_open_voucher.voucher_pengeluaran_id', //12
                                    't_voucher_pengeluaran.kode AS kode_voucher_pengeluaran', //13
                                    't_voucher_pengeluaran.total_nominal AS nominal_pembayaran', //14
									"SUBSTRING((SELECT deskripsi FROM t_open_voucher_detail WHERE open_voucher_id=t_open_voucher.open_voucher_id ORDER BY open_voucher_detail_id LIMIT 1), 1, 50) AS deskripsi", // 15
									't_open_voucher.mata_uang' //16
									];
				$param['join']= ['JOIN m_departement ON m_departement.departement_id = t_open_voucher.departement_id 
								  JOIN m_pegawai AS pegawai ON pegawai.pegawai_id = t_open_voucher.prepared_by
                                  JOIN m_pegawai AS pegawai1 ON pegawai1.pegawai_id = t_open_voucher.approver_1
                                  LEFT JOIN m_pegawai AS pegawai2 ON pegawai2.pegawai_id = t_open_voucher.approver_2
                                  LEFT JOIN t_voucher_pengeluaran AS t_voucher_pengeluaran ON t_voucher_pengeluaran.voucher_pengeluaran_id = t_open_voucher.voucher_pengeluaran_id
                                '];
                $param['where'] = "t_open_voucher.cancel_transaksi_id IS NULL AND t_open_voucher.cara_bayar = 'Transfer Bank' ";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('openVoucher');
        }
    }
    
    public function actionSetOpenVoucher(){
        if(\Yii::$app->request->isAjax){
            $open_voucher_id = \Yii::$app->request->post('open_voucher_id');
            $model = \app\models\TOpenVoucher::findOne($open_voucher_id);
            $data = $model->attributes;
            return $this->asJson($data);
        }
    }

	public function actionSetAutoOv(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
            $open_voucher_id = \Yii::$app->request->post('open_voucher_id');
			// $mata_uang = \Yii::$app->request->post('mata_uang');
            $data['items'] = "";
            if(!empty($open_voucher_id)){
				$modOv = \app\models\TOpenVoucher::findOne($open_voucher_id);
				$modOvDetail = \app\models\TOpenVoucherDetail::find()->where(['open_voucher_id'=>$open_voucher_id])->orderBy('open_voucher_detail_id')->all();
				$total_nominal = 0;
				$keterangan = "";
				if(count($modOvDetail) > 0){
					$modDetail = new \app\models\TVoucherPengeluarandetail();
					foreach ($modOvDetail as $i => $ovd){
						$keterangan .= $ovd['deskripsi'] ."\n";
						$total_nominal += $ovd['nominal'];
					}
					$modDetail->keterangan = $keterangan;
					// $modDetail->jumlah = number_format($total_nominal);
					if($modOv->mata_uang == "IDR"){
						$modDetail->jumlah = number_format($total_nominal);
					} else {
						$modDetail->jumlah = \app\components\DeltaFormatter::formatNumberForUserFloat($total_nominal, 2);
					}
					$data['items'] .= $this->renderPartial('_itemDetail',['modDetail'=>$modDetail]);
					if($modOv->total_ppn > 0){
						$modDetail->keterangan = 'PPN';
						$modDetail->jumlah = number_format($modOv->total_ppn);
						$data['items'] .= $this->renderPartial('_itemDetail',['modDetail'=>$modDetail]);
					}
					if($modOv->total_pph > 0){
						$modDetail->keterangan = 'PPh';
						$modDetail->jumlah = number_format(-$modOv->total_pph);
						$data['items'] .= $this->renderPartial('_itemDetail',['modDetail'=>$modDetail]);
					}
					$data['ov'] = $modOv;
				}
            }
            return $this->asJson($data);
        }
	}
	public function actionDetailApprover(){
		$this->layout = '@views/layouts/metronic/print';
		if (\Yii::$app->request->isAjax) {
			$id = Yii::$app->request->get('id');			
			// Validasi jika parameter tidak ditemukan
			if (empty($id)) {
				return json_encode(['error' => 'ID tidak ditemukan.']);
			}	
			// Ambil data berdasarkan ID
			$model = \app\models\TApproval::findOne(['reff_no' => $id]);	
			$modReff = \app\models\TOpenVoucher::findOne(['kode'=>$model->reff_no]);
			$modReff->departement_nama = \app\models\MDepartement::findOne( $modReff->departement_id )->departement_nama;
            $modDetail = \app\models\TOpenVoucherDetail::find()->where(['open_voucher_id'=>$modReff->open_voucher_id])->all();
			// Validasi jika model tidak ditemukan
			if ($model === null) {
				return json_encode(['error' => 'Data tidak ditemukan untuk ID: ' . $id]);
			}	
			$paramprint['judul'] = Yii::t('app', 'APPROVAL');
			return $this->renderAjax('detailApprover', ['model' => $model,'paramprint'=>$paramprint, 'modReff'=>$modReff,'modDetail'=>$modDetail]);
		}
	}
    
}
