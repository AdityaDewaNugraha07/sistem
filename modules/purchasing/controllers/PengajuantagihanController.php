<?php

namespace app\modules\purchasing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PengajuantagihanController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TPengajuanTagihan();
        $model->tanggal = date('d/m/Y');
        $model->tgl_awal = date('d/m/Y');
        $model->tgl_akhir = date('d/m/Y');
		
		$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
		$tgl = \Yii::$app->request->post('tgl');
        if( isset($form_params['TPengajuanTagihan']) ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pengajuan_tagihan
                $success_2 = false; // update t_terima_bhp
                $success_3 = true; // update t_voucher_pengeluaran_detail
				$post = $form_params['TPengajuanTagihan'];
				if(count($post)>0){
					foreach($post as $peng){ $post = $peng; }
					if(!empty($post['pengajuan_tagihan_id'])){
						$mod = \app\models\TPengajuanTagihan::findOne($post['pengajuan_tagihan_id']);
					}else{
						$mod = new \app\models\TPengajuanTagihan();
					}
					$mod->attributes = $post;
					if(empty($post['pengajuan_tagihan_id'])){
						$mod->tanggal = $tgl;
					}
					$mod->status = "DIAJUKAN";
					$asd = [];
					isset($post['is_notaasli'])?$asd['is_notaasli']=$post['is_notaasli']:"";
					isset($post['is_kuitansi'])?$asd['is_kuitansi']=$post['is_kuitansi']:"";
					isset($post['is_fakturpajak'])?$asd['is_fakturpajak']=$post['is_fakturpajak']:"";
					isset($post['is_suratjalan'])?$asd['is_suratjalan']=$post['is_suratjalan']:"";
					isset($post['keterangan_berkas'])?$asd['keterangan_berkas']=$post['keterangan_berkas']:"";
					$mod->kelengkapan_berkas = \yii\helpers\Json::encode($asd);
					if($mod->validate()){
						if($mod->save()){
							$success_1 = true;
                            
                            // update t_terima_bhp
                            $modTBP = \app\models\TTerimaBhp::findOne($mod->terima_bhp_id);
                            $modTBP->no_fakturpajak = $mod->no_fakturpajak;
                            if($modTBP->validate()){
                                if($modTBP->save()){
                                    $success_2 = true;
                                }else{
                                    $success_2 = false;
                                }
                            }else{
                                $success_2 = false;
                            }
                            
                            // update t_voucher_pengeluaran_detail
                            if(!empty($modTBP->voucher_pengeluaran_id)){
                                $modVoucherDetail = \app\models\TVoucherPengeluarandetail::find()->where("voucher_pengeluaran_id = ".$modTBP->voucher_pengeluaran_id." AND keterangan ilike '%PPN%'")->one();
                                if(!empty($modVoucherDetail)){
                                    $modVoucherDetail->keterangan = "PPN / FP ".$mod->no_fakturpajak;
                                    if($modVoucherDetail->validate()){
                                        if($modVoucherDetail->save()){
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
//				exit;
                if ($success_1 && $success_2 && $success_3) {
					$transaction->commit();
					$data['status'] = true;
					$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
					$data['model'] = $mod->attributes;
					$data['html_berkas'] = $this->actionSetKelengkapanBerkas($mod,'view');
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
            $mode = Yii::$app->request->post('mode');
            $tgl_awal = Yii::$app->request->post('tgl_awal');
            $tgl_akhir = Yii::$app->request->post('tgl_akhir');
            $suplier_id = Yii::$app->request->post('suplier');
            $kode_tbp = Yii::$app->request->post('kode_tbp');
            $kode_spo = Yii::$app->request->post('kode_spo');
            $nomor_nota = Yii::$app->request->post('nomor_nota');
            $nomor_kuitansi = Yii::$app->request->post('nomor_kuitansi');
            $no_fakturpajak = Yii::$app->request->post('no_fakturpajak');
			$data = [];
			$models = [];
            $data['html'] = '';
			if($mode=="input"){
				if(!empty($tgl)){
					$models = \app\models\TPengajuanTagihan::find()->where("tanggal = '{$tgl}' AND spo_id IS NOT NULL")->orderBy("pengajuan_tagihan_id ASC")->all();
				}
			}else{
				$models = \app\models\TPengajuanTagihan::find()->andWhere("t_pengajuan_tagihan.spo_id IS NOT NULL")
						->join("JOIN", "t_terima_bhp", "t_terima_bhp.terima_bhp_id = t_pengajuan_tagihan.terima_bhp_id")
						->join("LEFT JOIN", "t_spo", "t_spo.spo_id = t_pengajuan_tagihan.spo_id")
						->orderBy("pengajuan_tagihan_id ASC");
				if((!empty($tgl_awal)) && (!empty($tgl_akhir))){
					$models->andWhere("t_pengajuan_tagihan.tanggal BETWEEN '{$tgl_awal}' AND '{$tgl_akhir}'");
				}
				if(!empty($suplier_id)){
					$models->andWhere("t_pengajuan_tagihan.suplier_id = {$suplier_id}");
				}
				if(!empty($kode_tbp)){
					$models->andWhere("t_terima_bhp.terimabhp_kode ILIKE '%{$kode_tbp}%'");
				}
				if(!empty($kode_spo)){
					$models->andWhere("t_spo.spo_kode ILIKE '%{$kode_spo}%'");
				}
				if(!empty($nomor_nota)){
					$models->andWhere("t_pengajuan_tagihan.nomor_nota ILIKE '%{$nomor_nota}%'");
				}
				if(!empty($nomor_kuitansi)){
					$models->andWhere("t_pengajuan_tagihan.nomor_kuitansi ILIKE '%{$nomor_kuitansi}%'");
				}
                if(!empty($no_fakturpajak)) {
                    $models->andWhere("t_pengajuan_tagihan.no_fakturpajak ILIKE '%$no_fakturpajak%'");
                }
				$models = $models->all();
			}
			if(count($models)>0){
				foreach($models as $i => $model){
					$modTerima = \app\models\TTerimaBhp::findOne($model->terima_bhp_id);
					$modTerima->nofaktur = !empty($modTerima->nofaktur)?$modTerima->nofaktur:"-";
					$model->tanggal_nota = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_nota);
					$model->nomor_nota = !empty($model->nomor_nota)?$model->nomor_nota:$modTerima->nofaktur;
					$data['html'] .= $this->renderPartial('_item',['model'=>$model,'modTerima'=>$modTerima,'i'=>$i,'view'=>true]);
				}
			}
            return $this->asJson($data);
        }
    }
	
	public function actionPickPanelTBP(){
        if(\Yii::$app->request->isAjax){
//			if(\Yii::$app->request->get('dt')=='table-dt'){
//				$param['table']= \app\models\TTerimaBhp::tableName();
//				$param['pk']= \app\models\TTerimaBhp::primaryKey()[0];
//				$param['column'] = ['terima_bhp_id','terimabhp_kode',['col_name'=>'tglterima','formatter'=>'formatDateForUser2'],'suplier_nm','nofaktur','ppn_nominal','totalbayar'];
//				$param['join']= ['JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id'];
//				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL AND spo_id IS NOT NULL 
//                                    AND terima_bhp_id NOT IN ( SELECT terima_bhp_id FROM t_pengajuan_tagihan WHERE cancel_transaksi_id IS NULL AND status != 'DITOLAK') 
//                                    AND (terima_bhp_id NOT IN ( SELECT t_terima_bhp_detail.terima_bhp_id FROM t_retur_bhp JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhpd_id = t_retur_bhp.terima_bhpd_id ))
//                                    AND EXTRACT(year FROM tglterima) >= '2019' 
//                                    ";
//				$param['order'] = $param['table'].".created_at DESC";
//				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
//			}
//			return $this->renderAjax('pickPanelTBP',[]);
			if(\Yii::$app->request->get('dt')=='table-dt'){
				$param['table']= \app\models\TTerimaBhp::tableName();
				$param['pk']= \app\models\TTerimaBhp::primaryKey()[0];
				$param['column'] = ['t_terima_bhp.terima_bhp_id'									//0
									,'terimabhp_kode'												//1
									,['col_name'=>'tglterima', 'formatter'=>'formatDateForUser2']	//2
									,'suplier_nm'													//3
									,'nofaktur'														//4
									,'t_terima_bhp.ppn_nominal'										//5
									,'totalbayar'													//6
									,'t_terima_bhp.totalretur'										//7
									/*,'( SELECT sum(ppn_nominal) 
										FROM t_retur_bhp 
										JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhp_id = t_terima_bhp.terima_bhp_id
										WHERE t_terima_bhp_detail.terima_bhpd_id = t_retur_bhp.terima_bhpd_id 
										AND (t_terima_bhp.terima_bhp_id = t_terima_bhp.terima_bhp_id)) as ppn_retur'*/

									];
				$param['join']= ['JOIN m_suplier ON m_suplier.suplier_id = t_terima_bhp.suplier_id '.
									' JOIN t_terima_bhp_detail on t_terima_bhp_detail.terima_bhp_id = t_terima_bhp.terima_bhp_id '
									//' FULL OUTER JOIN t_retur_bhp on t_retur_bhp.terima_bhpd_id = t_terima_bhp_detail.terima_bhpd_id 
								];
				$param['where'] = "1=1
                                    AND ".$param['table'].".cancel_transaksi_id IS NULL 
									AND spo_id IS NOT NULL 
									AND t_terima_bhp.terima_bhp_id NOT IN 
										( SELECT t_pengajuan_tagihan.terima_bhp_id 
											FROM t_pengajuan_tagihan 
											WHERE cancel_transaksi_id IS NULL 
											AND status != 'DITOLAK') 
									AND (exists 
										( SELECT t_terima_bhp_detail.terima_bhp_id 
											FROM t_retur_bhp 
											JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhpd_id = t_retur_bhp.terima_bhpd_id 
											WHERE t_terima_bhp_detail.terima_bhpd_id = t_retur_bhp.terima_bhpd_id 
											AND (t_terima_bhp_detail.terimabhpd_qty-t_retur_bhp.qty)>0))
                                    AND EXTRACT(year FROM tglterima) >= '2019' 
									";
				//$param['group'] = "GROUP BY t_terima_bhp.terima_bhp_id, m_suplier.suplier_nm, t_retur_bhp.total_kembali ";
				$param['group'] = "GROUP BY t_terima_bhp.terima_bhp_id, m_suplier.suplier_nm ";
				$param['order'] = $param['table'].".created_at DESC";
				return \yii\helpers\Json::encode(\app\components\SSP2::complex( $param ));
			}
			return $this->renderAjax('pickPanelTBP',[]);
        }
    }
	
	public function actionPickTBP(){
        if(\Yii::$app->request->isAjax){
			$picked = \Yii::$app->request->post('picked');
			$parsed = explode(',', $picked);
			$clean = []; $data = []; $data['html'] = '';
			foreach($parsed as $parse){
				if(!empty($parse)){
					$clean[] = str_replace('-', '', $parse);
				}
			}
			if(!empty($clean)){
				foreach($clean as $i => $id){
					$modTerima = \app\models\TTerimaBhp::findOne($id);
					$modTerimaDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$modTerima->terima_bhp_id])->one();
					$modReturBhp = \app\models\TReturBhp::find()->where(['terima_bhpd_id'=>$modTerimaDetail->terima_bhpd_id])->one();
					$count_modReturBhp = count($modReturBhp);
					if ($modTerima->totalretur > 0) {
						$nilai_retur = $modTerima->totalretur;
					} else {
						$nilai_retur = 0;
					}
					$model = new \app\models\TPengajuanTagihan();
					$model->attributes = $modTerima->attributes;
					$model->nomor_nota = !empty($modTerima->nofaktur)?$modTerima->nofaktur:"-";
					$model->nominal = $modTerima->totalbayar - $nilai_retur;
					$data['html'] .= $this->renderPartial('_item',['model'=>$model,'modTerima'=>$modTerima,'input'=>true]);
				}
			}
			return $this->asJson($data);
        }
    }
	
	public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TPengajuanTagihan::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
//					echo "<pre>";
//					print_r($success_1);
//					exit;
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = '$("#table-detail > tbody > tr").find("input[name*=\'[pengajuan_tagihan_id]\'][value=\''.$id.'\']").parents("tr").remove(); setTotal();';
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id]);
		}
	}
	
	public function actionInputKeterangan($value){
        if(\Yii::$app->request->isAjax){
			$model = new \app\models\TPengajuanTagihan();
			return $this->renderAjax('inputKeterangan',['model'=>$model,'value'=>$value]);
		}
    }
	
	public function actionInfoKeterangan($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TPengajuanTagihan::findOne($id);
			$model->keterangan_berkas = \yii\helpers\Json::decode($model->kelengkapan_berkas)['keterangan_berkas'];
			return $this->renderAjax('infoKeterangan',['model'=>$model]);
		}
    }
	
	public function actionSetKelengkapanBerkas($model,$tipe){
		$qwe = \yii\helpers\Json::decode($model->kelengkapan_berkas);
		$model->is_notaasli = isset($qwe['is_notaasli'])?$qwe['is_notaasli']:null;
		$model->is_kuitansi = isset($qwe['is_kuitansi'])?$qwe['is_kuitansi']:null;
		$model->is_fakturpajak = isset($qwe['is_fakturpajak'])?$qwe['is_fakturpajak']:null;
		$model->is_suratjalan = isset($qwe['is_suratjalan'])?$qwe['is_suratjalan']:null;
		$model->keterangan_berkas = isset($qwe['keterangan_berkas'])?$qwe['keterangan_berkas']:null;
		$ret = "";
		if($tipe=="input"){
			$ret .= \yii\helpers\Html::activeCheckbox($model, "[ii]is_notaasli",['labelOptions' => [ 'style' => 'margin-bottom: 0px; font-size: 1.1rem' ],
																				'label' => 'Nota Asli',
																				'style'=>'transform: scale(0.8); margin:0px;']);
			$ret .= \yii\helpers\Html::activeCheckbox($model, "[ii]is_kuitansi",['labelOptions' => [ 'style' => 'margin-bottom: 0px; font-size: 1.1rem' ],
																				'label' => 'Kuitansi',
																				'style'=>'transform: scale(0.8); margin:0px;']);
			$ret .= \yii\helpers\Html::activeCheckbox($model, "[ii]is_fakturpajak",['labelOptions' => [ 'style' => 'margin-bottom: 0px; font-size: 1.1rem' ],
																				'label' => 'Faktur Pajak',
																				'style'=>'transform: scale(0.8); margin:0px;']);
			$ret .= \yii\helpers\Html::activeCheckbox($model, "[ii]is_suratjalan",['labelOptions' => [ 'style' => 'margin-bottom: 0px; font-size: 1.1rem' ],
																				'label' => 'Surat Jalan',
																				'style'=>'transform: scale(0.8); margin:0px;']);
			$ret .= \yii\helpers\Html::activeHiddenInput($model, "[ii]keterangan_berkas");
			$ret .= '<br><a onclick="inputKeterangan(this)"><i class="fa fa-edit"></i> Keterangan</a>';
		}else{
			if($model->is_notaasli == '1' || $model->is_notaasli===true){
				$ret .= '<i class="fa fa-check font-green-haze"></i> Nota Asli<br>';
			}else if($model->is_notaasli == '0' || $model->is_notaasli===false){
				$ret .= '<i class="fa fa-remove font-red-flamingo"></i> Nota Asli<br>';
			}
			if($model->is_kuitansi == '1' || $model->is_kuitansi===true){
				$ret .= '<i class="fa fa-check font-green-haze"></i> Kuitansi<br>';
			}else if($model->is_kuitansi == '0' || $model->is_kuitansi===false){
				$ret .= '<i class="fa fa-remove font-red-flamingo"></i> Kuitansi<br>';
			}
			if($model->is_fakturpajak == '1' || $model->is_fakturpajak===true){
				$ret .= '<i class="fa fa-check font-green-haze"></i> Faktur Pajak<br>';
			}else if($model->is_fakturpajak == '0' || $model->is_fakturpajak===false){
				$ret .= '<i class="fa fa-remove font-red-flamingo"></i> Faktur Pajak<br>';
			}
			if($model->is_suratjalan == '1' || $model->is_suratjalan===true){
				$ret .= '<i class="fa fa-check font-green-haze"></i> Surat Jalan<br>';
			}else if($model->is_suratjalan == '0' || $model->is_suratjalan===false){
				$ret .= '<i class="fa fa-remove font-red-flamingo"></i> Surat Jalan<br>';
			}
			
			$ret .= '<a onclick="infoKeterangan('. $model->pengajuan_tagihan_id .')"><i class="fa fa-info-circle"></i> Keterangan</a>';
		}
		return $ret;
	}
	
	public function actionEdit($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TPengajuanTagihan::findOne($id);
			$data = $this->actionSetKelengkapanBerkas($model,'input');
			return $this->asJson($data);
		}
    }
	
}
