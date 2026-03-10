<?php

namespace app\modules\purchasing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PengajuantagihancashController extends DeltaBaseController
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
						}
					}else{
						$success_1 = false;
						$data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
					}
				}
//                echo "<pre>";
//				print_r($success_1);
//				exit;
                if ($success_1 && $success_2) {
					$transaction->commit();
					$data['status'] = true;
					$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
					$data['model'] = $mod->attributes;
					$data['html_berkas'] = \Yii::$app->runAction("/purchasing/pengajuantagihan/setKelengkapanBerkas",['model'=>$mod,'tipe'=>'view']);
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
            $nomor_nota = Yii::$app->request->post('nomor_nota');
            $data = []; $models = [];
            $data['html'] = ''; $disabled = false;
			if($mode=="input"){
				if(!empty($tgl)){
					$models = \app\models\TPengajuanTagihan::find()->where("tanggal = '{$tgl}' AND spl_id IS NOT NULL")->orderBy("pengajuan_tagihan_id ASC")->all();
				}
			}else{
				$models = \app\models\TPengajuanTagihan::find()->andWhere("t_pengajuan_tagihan.spl_id IS NOT NULL")
						->join("JOIN", "t_terima_bhp", "t_terima_bhp.terima_bhp_id = t_pengajuan_tagihan.terima_bhp_id")
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
				if(!empty($nomor_nota)){
					$models->andWhere("t_pengajuan_tagihan.nomor_nota ILIKE '%{$nomor_nota}%'");
				}
				$models = $models->all();
			}
            if(count($models)>0){
				foreach($models as $i => $model){
					$modTerima = \app\models\TTerimaBhp::findOne($model->terima_bhp_id);
					$modTerima->nofaktur = !empty($modTerima->nofaktur)?$modTerima->nofaktur:"-";
					$model->tanggal_nota = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_nota);
					$model->nomor_nota = !empty($model->nomor_nota)?$model->nomor_nota:$modTerima->nofaktur;
					$data['html'] .= $this->renderPartial('_item',['model'=>$model,'modTerima'=>$modTerima,'i'=>$i,'disabled'=>$disabled,'view'=>true]);
				}
			}
            return $this->asJson($data);
        }
    }
	
	public function actionPickPanelTBP(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-dt'){
				$param['table']= \app\models\TTerimaBhp::tableName();
				$param['pk']= \app\models\TTerimaBhp::primaryKey()[0];
				$param['column'] = [$param['table'].'.terima_bhp_id','terimabhp_kode',['col_name'=>'tglterima','formatter'=>'formatDateForUser2'],'m_suplier.suplier_nm','nofaktur','totalbayar'];
				$param['join']= ['JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhp_id = t_terima_bhp.terima_bhp_id 
                                                  JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id'];
//                                $param['join']= ['JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id'];                                
                                $param['where'] = $param['table'].".cancel_transaksi_id IS NULL AND spl_id IS NOT NULL 
                                                  AND not exists (select terima_bhpd_id from t_retur_bhp where t_retur_bhp.terima_bhpd_id=t_terima_bhp_detail.terima_bhpd_id) 
                                                  AND t_terima_bhp.terima_bhp_id NOT IN ( SELECT terima_bhp_id FROM t_pengajuan_tagihan 
                                                                                          WHERE cancel_transaksi_id IS NULL AND status != 'DITOLAK') 
                                                                                                  AND EXTRACT(year FROM tglterima) >= '2019'";
				
//				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL AND spl_id IS NOT NULL 
//                                                  AND ".$param['table'].".terima_bhp_id NOT IN ( SELECT terima_bhp_id FROM t_pengajuan_tagihan 
//                                                                                              WHERE cancel_transaksi_id IS NULL AND status != 'DITOLAK') 
//                                                                                              AND EXTRACT(year FROM tglterima) >= '2019'";
				$param['group'] = "GROUP BY ".$param['table'].".terima_bhp_id,terimabhp_kode,tglterima,m_suplier.suplier_nm,nofaktur,totalbayar";
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
					$model = new \app\models\TPengajuanTagihan();
					$model->attributes = $modTerima->attributes;
					$model->nomor_nota = !empty($modTerima->nofaktur)?$modTerima->nofaktur:"-";
					$model->nominal = $modTerima->totalbayar;
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
	
}
