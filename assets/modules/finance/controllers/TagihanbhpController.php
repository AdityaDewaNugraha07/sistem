<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class TagihanbhpController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
		$model = new \app\models\TPengajuanTagihan();
		$model->tgl_awal = date('d/m/Y', strtotime('-1 days'));
		$model->tgl_akhir = date('d/m/Y');
		$model->suplier_id = date('d/m/Y');
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\TPengajuanTagihan();
			if((\Yii::$app->request->post('form_params')) !== null){
				$data = []; $data['html'] = ''; $disabled = false; $where = [];
				$form_params = []; parse_str(\Yii::$app->request->post('form_params'),$form_params);
				$model->attributes = $form_params['TPengajuanTagihan'];
				$model->tgl_awal = $form_params['TPengajuanTagihan']['tgl_awal'];
				$model->tgl_akhir = $form_params['TPengajuanTagihan']['tgl_akhir'];
				$model->suplier_id = $form_params['TPengajuanTagihan']['suplier_id'];
				
				$query = \app\models\TPengajuanTagihan::find();
				$query->orderBy("pengajuan_tagihan_id ASC");
				$query->andWhere("spo_id IS NOT NULL");
				if(!empty($model->tgl_awal) && !empty($model->tgl_akhir)){
					$query->andWhere("t_pengajuan_tagihan.tanggal BETWEEN '".$model->tgl_awal."' AND '".$model->tgl_akhir."' ");
				}
				if($model->suplier_id){
					$query->andWhere("t_pengajuan_tagihan.suplier_id = ".$model->suplier_id);
				}
				
				foreach($query->all() as $i => $model){
					$modTerima = \app\models\TTerimaBhp::findOne($model->terima_bhp_id);
					$data['html'] .= $this->renderPartial('_item',['model'=>$model,'modTerima'=>$modTerima,'i'=>$i,'disabled'=>$disabled]);
				}
				
				return $this->asJson($data);
			}
        }
    }
	
	public function actionUpdateStatus(){
        if(\Yii::$app->request->isAjax){
			$pengajuan_tagihan_id = \Yii::$app->request->post('pengajuan_tagihan_id');
			$status = \Yii::$app->request->post('status');
			$alasantolak = \Yii::$app->request->post('alasantolak');
			$transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pengajuan_tagihan
				$model = \app\models\TPengajuanTagihan::findOne($pengajuan_tagihan_id);
				$model->status = $status; $berkas=[];
				if(!empty($model->kelengkapan_berkas)){
					$kelengkapan_berkas = \yii\helpers\Json::decode($model->kelengkapan_berkas);
					foreach($kelengkapan_berkas as $key => $kelengkapan){
						if($kelengkapan=="1"){
							$berkas[] = substr($key, 3,20);
						}
					}
					$berkas = implode(", ", $berkas);
				}
				if($status=="DITERIMA"){
					$model->keterangan = !empty($model->keterangan)?$model->keterangan."<br>":"";
					$model->keterangan .= "Diterima Berkas (<b>".(!empty($berkas)?$berkas:"")."</b>) Pada ".date('d/m/Y H:i:s')." Oleh ".Yii::$app->user->identity->pegawai->pegawai_nama;
				}else if($status=="DITOLAK"){
					$model->keterangan = !empty($model->keterangan)?$model->keterangan."<br>":"";
					$model->keterangan .= "Ditolak Berkas (<b>".(!empty($berkas)?$berkas:"")."</b>) Pada ".date('d/m/Y H:i:s')." Oleh ".Yii::$app->user->identity->pegawai->pegawai_nama.", Karena ".$alasantolak;
				}else if($status=="DIAJUKAN"){
					$model->keterangan = !empty($model->keterangan)?$model->keterangan."<br>":"";
					$model->keterangan .= "Dibatalkan Penerimaan Berkas (<b>".(!empty($berkas)?$berkas:"")."</b>) Pada ".date('d/m/Y H:i:s')." Oleh ".Yii::$app->user->identity->pegawai->pegawai_nama;
				}
				if($model->validate()){
					if($model->save()){
						$success_1 = true;
					}
				}else{
					$success_1 = false;
					$data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
				}
                if ($success_1) {
					$transaction->commit();
					$data['status'] = true;
					$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
					$data['model'] = $model->attributes;
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
	
	public function actionAlasanTolak($pengajuan_tagihan_id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TPengajuanTagihan::findOne($pengajuan_tagihan_id);
			return $this->renderAjax('alasanTolak',['model'=>$model]);
        }
    }
	
	public function actionUpdateBerkas($pengajuan_tagihan_id,$cash=null){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TPengajuanTagihan::findOne($pengajuan_tagihan_id);
			$berkas = \yii\helpers\Json::decode($model->kelengkapan_berkas);
			$model->is_notaasli = (isset($berkas['is_notaasli']) ? ($berkas['is_notaasli']=="1")?true:false :null );
			$model->is_kuitansi = (isset($berkas['is_kuitansi'])? ($berkas['is_kuitansi']=="1")?true:false :null );
			$model->is_fakturpajak = (isset($berkas['is_fakturpajak'])? ($berkas['is_fakturpajak']=="1")?true:false :null );
			$model->is_suratjalan = (isset($berkas['is_suratjalan'])? ($berkas['is_suratjalan']=="1")?true:false :null );
			$model->keterangan_berkas = (isset($berkas['keterangan_berkas'])? $berkas['keterangan_berkas'] :"" );
			$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
			if( isset($form_params['TPengajuanTagihan']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false; // t_pengajuan_tagihan
					$success_2 = false; // update t_terima_bhp
					$success_3 = true; // update t_voucher_pengeluaran_detail
					$post = $form_params['TPengajuanTagihan'];
					if(count($post)>0){
						$mod = \app\models\TPengajuanTagihan::findOne($pengajuan_tagihan_id);
						$asd = []; $bef = !empty($mod->kelengkapan_berkas)?\yii\helpers\Json::decode($mod->kelengkapan_berkas):""; $berkas_update="";
						isset($post['is_notaasli'])?$asd['is_notaasli']=$post['is_notaasli']:"";
						isset($post['is_kuitansi'])?$asd['is_kuitansi']=$post['is_kuitansi']:"";
						isset($post['is_fakturpajak'])?$asd['is_fakturpajak']=$post['is_fakturpajak']:"";
						isset($post['is_suratjalan'])?$asd['is_suratjalan']=$post['is_suratjalan']:"";
						isset($post['keterangan_berkas'])?$asd['keterangan_berkas']=$post['keterangan_berkas']:"";
						isset($post['no_fakturpajak'])?$asd['no_fakturpajak']=$post['no_fakturpajak']:"";
						$mod->kelengkapan_berkas = \yii\helpers\Json::encode($asd);
						$mod->no_fakturpajak = isset($post['no_fakturpajak'])?$asd['no_fakturpajak']:"";
						if(!empty($bef)){
							foreach($bef as $ii => $be){
								if(($asd[$ii] != $be) && ($asd[$ii] =="1")){
									$berkas_update .= substr($ii, 3,20);
								}
							}
							if(!empty($berkas_update)){
								$mod->keterangan = !empty($mod->keterangan)?$mod->keterangan."<br>":"";
								$mod->keterangan .= "Diajukan Susulan Berkas (<b>".(!empty($berkas_update)?$berkas_update:"")."</b>) Pada ".date('d/m/Y H:i:s')." Oleh ".Yii::$app->user->identity->pegawai->pegawai_nama;
                                if(!$cash){
                                    $mod->status = "DIAJUKAN";
                                }
							}
						}
                        
//                        echo "<pre>";
//                        print_r($mod->attributes);
//                        echo "<pre>";
//                        print_r($cash);
//                        exit;
                        
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
						$data['callback'] = "getItems();";
						$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
						$data['model'] = $mod->attributes;
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
				$data['html_berkas'] = \Yii::$app->runAction("purchasing/pengajuantagihan/setKelengkapanBerkas",['model'=>$mod,'tipe'=>'view']);
				return $this->asJson($data);
			}
			
			return $this->renderAjax('updateBerkas',['model'=>$model,'berkas'=>$berkas,'cash'=>$cash]);
        }
    }
	
	
}
