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
			$open_voucher_id = Yii::$app->request->post('open_voucher_id');
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
					if($open_voucher_id){
						$model->open_voucher_id = $open_voucher_id;
					}
				}else if($status=="DITOLAK"){
					$model->keterangan = !empty($model->keterangan)?$model->keterangan."<br>":"";
					$model->keterangan .= "Ditolak Berkas (<b>".(!empty($berkas)?$berkas:"")."</b>) Pada ".date('d/m/Y H:i:s')." Oleh ".Yii::$app->user->identity->pegawai->pegawai_nama.", Karena ".$alasantolak;
				}else if($status=="DIAJUKAN"){
					$model->keterangan = !empty($model->keterangan)?$model->keterangan."<br>":"";
					if($model->lunas && $model->open_voucher_id){
						$modOv = \app\models\TOpenVoucher::findOne($model->open_voucher_id);
						$model->keterangan .= "Dibatalkan Penerimaan Berkas (<b>".(!empty($berkas)?$berkas:"")."</b>) dengan Open Voucher ". $modOv->kode ." Pada ".date('d/m/Y H:i:s')." Oleh ".Yii::$app->user->identity->pegawai->pegawai_nama;
					} else {
						$model->keterangan .= "Dibatalkan Penerimaan Berkas (<b>".(!empty($berkas)?$berkas:"")."</b>) Pada ".date('d/m/Y H:i:s')." Oleh ".Yii::$app->user->identity->pegawai->pegawai_nama;
					}
					// $model->keterangan .= "Dibatalkan Penerimaan Berkas (<b>".(!empty($berkas)?$berkas:"")."</b>) Pada ".date('d/m/Y H:i:s')." Oleh ".Yii::$app->user->identity->pegawai->pegawai_nama;
					$model->open_voucher_id = null;
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

	public function actionOpenVoucher(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-open-voucher'){
				$param['table']= \app\models\TOpenVoucher::tableName();
				$param['pk']= $param['table'].".".\app\models\TOpenVoucher::primaryKey()[0];
				$param['column'] = ['t_open_voucher.open_voucher_id',
                                    't_open_voucher.kode',
                                    't_open_voucher.tanggal',
                                    't_open_voucher.tipe',
                                    "(CASE 
                                        WHEN t_open_voucher.tipe='REGULER' THEN (SELECT CONCAT('<b>',nama_penerima,'</b><br>',nama_perusahaan) FROM m_penerima_voucher WHERE m_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id )
                                        WHEN t_open_voucher.tipe='PEMBAYARAN LOG ALAM' THEN (SELECT CONCAT('<b>',suplier_nm_company,'</b><br>',suplier_nm) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
                                        WHEN t_open_voucher.tipe='DEPOSIT SUPPLIER LOG' THEN (SELECT CONCAT('<b>',suplier_nm,'</b><br>',suplier_nm_company) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
                                        WHEN t_open_voucher.tipe='DP LOG SENGON' THEN (SELECT CONCAT('<b>',suplier_nm,'</b><br>',suplier_almt) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
                                        WHEN t_open_voucher.tipe='PELUNASAN LOG SENGON' THEN (SELECT CONCAT('<b>',suplier_nm,'</b><br>',suplier_almt) FROM m_suplier WHERE m_suplier.suplier_id = t_open_voucher.penerima_reff_id )
										WHEN t_open_voucher.tipe='PEMBAYARAN ASURANSI LOG SHIPPING' THEN (SELECT kepada FROM t_asuransi WHERE t_asuransi.kode = t_open_voucher.reff_no )
                                      ELSE '' END) AS penerima",
                                    't_open_voucher.total_pembayaran',
                                    'pegawai.pegawai_nama AS prepared_by',
                                    't_open_voucher.keterangan',
                                    't_open_voucher.status_bayar',
                                    't_open_voucher.status_approve',
                                    't_open_voucher.voucher_pengeluaran_id', 
                                    't_voucher_pengeluaran.kode AS kode_voucher_pengeluaran',
                                    't_voucher_pengeluaran.total_nominal AS nominal_pembayaran',
									"SUBSTRING((SELECT deskripsi FROM t_open_voucher_detail WHERE open_voucher_id=t_open_voucher.open_voucher_id ORDER BY open_voucher_detail_id LIMIT 1), 1, 50) AS deskripsi", 
									't_open_voucher.mata_uang' 
									];
				$param['join']= ['JOIN m_pegawai AS pegawai ON pegawai.pegawai_id = t_open_voucher.prepared_by
                                  JOIN m_pegawai AS pegawai1 ON pegawai1.pegawai_id = t_open_voucher.approver_1
                                  LEFT JOIN m_pegawai AS pegawai2 ON pegawai2.pegawai_id = t_open_voucher.approver_2
                                  LEFT JOIN t_voucher_pengeluaran AS t_voucher_pengeluaran ON t_voucher_pengeluaran.voucher_pengeluaran_id = t_open_voucher.voucher_pengeluaran_id
                                ']; //t_open_voucher.departement_id = 113 AND 
                $param['where'] = " t_open_voucher.cancel_transaksi_id IS NULL AND t_open_voucher.status_bayar = 'PAID' 
									AND t_open_voucher.cara_bayar='Transfer Bank' AND t_open_voucher.tipe = 'REGULER'
									AND not exists (select open_voucher_id from t_pengajuan_tagihan where t_pengajuan_tagihan.open_voucher_id = t_open_voucher.open_voucher_id)";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('openVoucher');
		}
	}

	public function actionInfoOpenVoucher(){
        if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->get('open_voucher_id');
			$model = \app\models\TOpenVoucher::findOne($id);
			return $this->renderAjax('infoOpenVoucher',['model'=>$model]);
		}
    }
	
	
}
