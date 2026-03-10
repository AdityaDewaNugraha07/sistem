<?php

namespace app\modules\purchasing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class TrackingController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $modSPO = new \app\models\TSpo();
        $modSPL = new \app\models\TSpl();
		return $this->render('index',['modSPO'=>$modSPO,'modSPL'=>$modSPL]);
	}
	
	public function actionFindSPO(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			if(!empty($term)){
				$query = "
					SELECT * FROM t_spo JOIN m_suplier ON m_suplier.suplier_id = t_spo.suplier_id 
					WHERE t_spo.cancel_transaksi_id IS NULL 
						".(!empty($term)?"AND spo_kode ILIKE '%".$term."%'":'')." 
					ORDER BY t_spo.created_at DESC;
				";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'spo_id', 'spo_kode');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['spo_id'], 'text'=>$val['spo_kode'].' - '.$val['suplier_nm']];
					}
				}
			}
            return $this->asJson($data);
        }
    }
	public function actionFindSPL(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			if(!empty($term)){
				$query = "
					SELECT * FROM t_spl 
					WHERE t_spl.cancel_transaksi_id IS NULL 
					".(!empty($term)?"AND spl_kode ILIKE '%".$term."%'":'')." 
					ORDER BY t_spl.created_at DESC;;
				";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'spl_id', 'spl_kode');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['spl_id'], 'text'=>$val['spl_kode'].' - '.\app\components\DeltaFormatter::formatDateTimeForUser($val['spl_tanggal'])];
					}
				}
			}
            return $this->asJson($data);
        }
    }
	
	public function actionGetSPO(){
		if(\Yii::$app->request->isAjax){
			$spo_id = Yii::$app->request->post('spo_id');
			$data = [];
			$data['html'] = '';
			$data['itemsBhp'] = '';
			if(!empty($spo_id)){
				$modSPO = \app\models\TSpo::findOne($spo_id);
				$modSPODetail = \app\models\TSpoDetail::find()->where(['spo_id'=>$spo_id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->orderBy(['spod_id'=>SORT_ASC])->all();
				$modApproval = \app\models\TApproval::find()->where(['reff_no'=>$modSPO->spo_kode])->one();
				$data['html'] .= $this->renderPartial('_contentSPO',['modSPO'=>$modSPO,'modSPODetail'=>$modSPODetail,'modApproval'=>$modApproval]);
				foreach($modSPODetail as $key => $detail){
					$data['itemsBhp'] .= $detail->bhp_id.((count($modSPODetail)==($key+1))?"":",");
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionGetSPL(){
		if(\Yii::$app->request->isAjax){
			$spl_id = Yii::$app->request->post('spl_id');
			$data = [];
			$data['html'] = '';
			$data['itemsBhp'] = '';
			if(!empty($spl_id)){
				$modSPL = \app\models\TSpl::findOne($spl_id);
				$modSPLDetail = \app\models\TSplDetail::find()->where(['spl_id'=>$spl_id])->orderBy(['spld_id'=>SORT_ASC])->all();
				$modApproval = \app\models\TApproval::find()->where(['reff_no'=>$modSPL->spl_kode])->one();
				$data['html'] .= $this->renderPartial('_contentSPL',['modSPL'=>$modSPL,'modSPLDetail'=>$modSPLDetail,'modApproval'=>$modApproval]);
				foreach($modSPLDetail as $key => $detail){
					$data['itemsBhp'] .= $detail->bhp_id.((count($modSPLDetail)==($key+1))?"":",");
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionGetSPP(){
		if(\Yii::$app->request->isAjax){
			$spo_id = Yii::$app->request->post('spo_id');
			$spl_id = Yii::$app->request->post('spl_id');
			$data = [];
			$data['html'] = '';
			$data['queryResult'] = '';
			if( (!empty($spo_id)) || (!empty($spl_id)) ){
				if($spo_id){
					$modSPO = \app\models\TSpo::findOne($spo_id);
					$kode = $modSPO->spo_kode;
				}else if($spl_id){
					$modSPL = \app\models\TSpl::findOne($spl_id);
					$kode = $modSPL->spl_kode;
				}
				$sql = "SELECT t_spp.spp_id FROM map_spp_detail_reff
						JOIN t_spp_detail ON t_spp_detail.sppd_id = map_spp_detail_reff.sppd_id
						JOIN t_spp ON t_spp.spp_id = t_spp_detail.spp_id
						WHERE map_spp_detail_reff.reff_no = '".$kode."' 
							AND cancel_transaksi_id IS NULL 
						GROUP BY t_spp.spp_id
						ORDER BY t_spp.spp_id DESC";
				$models = Yii::$app->db->createCommand($sql)->queryAll();
				if(count($models)>0){
					$data['html'] .= $this->renderPartial('_contentSPP',['models'=>$models]);
					$data['queryResult'] = $models;
				}
			}
			return $this->asJson($data);
		}
	}
	
	
	public function actionGetSPB(){
		if(\Yii::$app->request->isAjax){
			$spo_id = Yii::$app->request->post('spo_id');
			$spl_id = Yii::$app->request->post('spl_id');
			$data = [];
			$data['html'] = '';
			if( (!empty($spo_id)) || (!empty($spl_id)) ){
				$spps = $this->actionGetSPP()->data['queryResult'];
				if($spo_id){
					$bhps = $this->actionGetSPO()->data['itemsBhp'];
				}else if($spl_id){
					$bhps = $this->actionGetSPL()->data['itemsBhp'];
				}
				if((count($spps)>0) && (!empty($spps))){
					foreach($spps as $i => $spp){
						$sql = "SELECT t_spb_detail.spb_id FROM t_spp_detail 
								JOIN map_spb_detail_spp_detail ON t_spp_detail.sppd_id = map_spb_detail_spp_detail.sppd_id 
								JOIN t_spb_detail ON t_spb_detail.spbd_id = map_spb_detail_spp_detail.spbd_id 
								WHERE spp_id = ".$spp['spp_id']." AND t_spb_detail.bhp_id IN(".$bhps.")
								GROUP BY t_spb_detail.spb_id 
								ORDER BY spb_id DESC";
						$models = Yii::$app->db->createCommand($sql)->queryAll();
						if(count($models)>0){
							$data['html'] .= $this->renderPartial('_contentSPB',['models'=>$models]);
							$data['queryResult'] = $models;
						}
					}
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionGetTBP(){
		if(\Yii::$app->request->isAjax){
			$spo_id = Yii::$app->request->post('spo_id');
			$spl_id = Yii::$app->request->post('spl_id');
			$data = [];
			$data['html'] = '';
			if( (!empty($spo_id)) || (!empty($spl_id)) ){
				if($spo_id){
					$models = \app\models\TTerimaBhp::find()->where(['spo_id'=>$spo_id])->andWhere('cancel_transaksi_id IS NULL')->orderBy(['terima_bhp_id'=>SORT_ASC])->all();
				}else if($spl_id){
					$models = \app\models\TTerimaBhp::find()->where(['spl_id'=>$spl_id])->andWhere('cancel_transaksi_id IS NULL')->orderBy(['terima_bhp_id'=>SORT_ASC])->all();
				}
				if(count($models)>0){
					$data['html'] .= $this->renderPartial('_contentTBP',['models'=>$models]);
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionGetBPB(){
		if(\Yii::$app->request->isAjax){
			$spo_id = Yii::$app->request->post('spo_id');
			$spl_id = Yii::$app->request->post('spl_id');
			$data = [];
			$data['html'] = '';
			if( (!empty($spo_id)) || (!empty($spl_id)) ){
				$spbs = isset($this->actionGetSPB()->data['queryResult'])?$this->actionGetSPB()->data['queryResult']:'';
				if((count($spbs)>0) && (!empty($spbs))){
					foreach($spbs as $i => $spb){
						$models = \app\models\TBpb::find()->where(['spb_id'=>$spb['spb_id']])->orderBy(['bpb_id'=>SORT_ASC])->all();
						if(count($models)>0){
							$data['html'] .= $this->renderPartial('_contentBPB',['models'=>$models]);
						}
					}
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionChecklistTracking(){
		if(\Yii::$app->request->isAjax){
			$checked = Yii::$app->request->post('checked');
			$reff_no = Yii::$app->request->post('reff_no'); 
			$bhp_id = Yii::$app->request->post('bhp_id'); 
			$modChecked = new \app\models\MapTrackingpembelianChecklist();
			$searchChecked = \app\models\MapTrackingpembelianChecklist::findOne(['reff_no'=>$reff_no,'bhp_id'=>$bhp_id]);
			if($checked == "1"){
				$checked = TRUE;
			}else{
				$checked = FALSE;
			}
			if(!empty($searchChecked)){
				$data = \app\models\MapTrackingpembelianChecklist::updateAll(['checked'=>$checked],"reff_no = '".$reff_no."' AND bhp_id = ".$bhp_id);
			}else{
				$modChecked->reff_no = $reff_no;
				$modChecked->bhp_id = $bhp_id;
				$modChecked->checked = $checked;
				$data = $modChecked->save();
			}
			return $this->asJson($data);
		}
	}
	
	public function actionInfoSpp($id,$spo_id=null,$spl_id=null,$bhp_id=null){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpp::findOne($id);
			$modDetail = \app\models\TSppDetail::find()->where(['spp_id'=>$id])->all();
			return $this->renderAjax('infoSpp',['model'=>$model,'modDetail'=>$modDetail,'spo_id'=>$spo_id,'spl_id'=>$spl_id,'bhp_id'=>$bhp_id]);
		}
    }
	
	public function actionInfoSpb($id,$spo_id=null,$spl_id=null,$bhp_id=null){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpb::findOne($id);
			$modDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$id])->all();
			return $this->renderAjax('infoSpb',['model'=>$model,'modDetail'=>$modDetail,'spo_id'=>$spo_id,'spl_id'=>$spl_id,'bhp_id'=>$bhp_id]);
		}
    }
	
	public function actionInfoTbp($id,$spo_id=null,$spl_id=null,$bhp_id=null){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TTerimaBhp::findOne($id);
			$modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$id])->all();
			return $this->renderAjax('infoTbp',['model'=>$model,'modDetail'=>$modDetail,'spo_id'=>$spo_id,'spl_id'=>$spl_id,'bhp_id'=>$bhp_id]);
		}
    }
	public function actionInfoTbpBhp($id,$spo_id=null,$spl_id=null,$bhp_id=null){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TTerimaBhp::findOne($id);
			$modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$id,'bhp_id' =>$bhp_id])->all();
			return $this->renderAjax('infoTbp',['model'=>$model,'modDetail'=>$modDetail,'spo_id'=>$spo_id,'spl_id'=>$spl_id,'bhp_id'=>$bhp_id]);
		}
    }
	public function actionInfoBpb($id,$spo_id=null,$spl_id=null,$bhp_id=null){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TBpb::findOne($id);
			$modDetail = \app\models\TBpbDetail::find()->where(['bpb_id'=>$id])->all();
			return $this->renderAjax('infoBpb',['model'=>$model,'modDetail'=>$modDetail,'spo_id'=>$spo_id,'spl_id'=>$spl_id,'bhp_id'=>$bhp_id]);
		}
    }
	
	public function actionInfoSpl($id,$spo_id=null,$spl_id=null,$bhp_id=null){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpl::findOne($id);
			$modDetail = \app\models\TSplDetail::find()->where(['spl_id'=>$id])->all();
			return $this->renderAjax('infoSpl',['model'=>$model,'modDetail'=>$modDetail,'spo_id'=>$spo_id,'spl_id'=>$spl_id,'bhp_id'=>$bhp_id]);
		}
    }
	
	public function actionInfoSpo($id, $spo_id=null,$spl_id=null,$bhp_id=null){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpo::findOne($id);
			$modDetail = \app\models\TSpoDetail::find()->where(['spo_id'=>$id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->all();
			//$sql_modelTTerimaBHP = "select status_approval from t_terima_bhp where terima_bhp_id = '".$terima_bhp_id."' ";
			//$status_approval = Yii::$app->db->createCommand($sql_modelTTerimaBHP)->queryScalar();
			return $this->renderAjax('infoSpo',['model'=>$model,'modDetail'=>$modDetail,'spo_id'=>$spo_id,'spl_id'=>$spl_id,'bhp_id'=>$bhp_id]);
		}
    }
	
//	public function actionInfoVoucherPengeluaran($id){
//        if(\Yii::$app->request->isAjax){
//			$model = \app\models\TVoucherPengeluaran::findOne($id);
//			$modDetail = \app\models\TVoucherPengeluarandetail::find()->where(['voucher_pengeluaran_id'=>$id])->all();
//			$paramprint['judul'] = Yii::t('app', 'BUKTI BANK KELUAR');
//			return $this->renderAjax('infoBbk',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint]);
//		}
//    }
	
	public function actionInfoReturBHP($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TReturBhp::findOne($id);
			$paramprint['judul'] = Yii::t('app', 'Detail Retur Pembelian BHP');
			return $this->renderAjax('infoReturBHP',['model'=>$model]);
		}
    }
	
	public function actionInfoAllSpoByItem($bhp_id){
		$model = new \app\models\TSpoDetail();
		if(\Yii::$app->request->get('dt')=='table-laporan3'){
			$model->bhp_id = \Yii::$app->request->get('bhp_id');
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchAllSpoByItemDt() ));
		}
		
		return $this->renderAjax('infoAllSpoByItem',['bhp_id'=>$bhp_id]);
    }
    
	public function actionInfoAllSplByItem($bhp_id){
		$model = new \app\models\TSplDetail();
		if(\Yii::$app->request->get('dt')=='table-laporan4'){
			$model->bhp_id = \Yii::$app->request->get('bhp_id');
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchAllSplByItemDt() ));
		}
		
		return $this->renderAjax('infoAllSplByItem',['bhp_id'=>$bhp_id]);
    }
	
	public function actionGetRekapByTanggal($kode,$tanggal=null){
		if(\Yii::$app->request->isAjax){
            if(!empty($tanggal)){
                $thn = date("Y", strtotime($tanggal));
                $model = \app\models\HSaldoKaskecil::find()->where("reff_no = '{$kode}' AND EXTRACT(year FROM tanggal) = '{$thn}' ")->one();
            }else{
                $model = \app\models\HSaldoKaskecil::findOne(['reff_no'=>$kode]);
            }
            
            $tgl = date('Y-m-d', strtotime($model->tanggal));
			if($tgl){
				$awal = $tgl." 00:00:00";
				$akhir = $tgl." 23:59:59";
				$models = \app\models\HSaldoKaskecil::find()->where("tanggal BETWEEN '".$awal."' AND '".$akhir."' ")->orderBy('reff_no ASC')->all();
				if(count($models)>0){
					return $this->renderAjax('@app/modules/kasir/views/rekapkaskecil/rekapbytgl',['models'=>$models,'highlight_kode'=>$kode]);
				}
			}
		}
	}
	
	public function actionPrintSpp(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TSpp::findOne($_GET['id']);
		$modDetail = \app\models\TSppDetail::find()->where(['spp_id'=>$model->spp_id])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'SURAT PERMINTAAN PEMBELIAN');
		if($caraprint == 'PRINT'){
			return $this->renderPartial('printSpp',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint]);
		}
	}
	
	public function actionPrintTbp(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TTerimaBhp::findOne($_GET['id']);
		$modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$_GET['id']])->all();
		$matauang = "Rp";
		if(!empty($model->spo_id)){
			$modSPO = \app\models\TSpo::findOne($model->spo_id);
			if(!empty($modSPO)){
				$matauang = $modSPO->defaultValue->name_en;
			}
		}
		
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'LAPORAN PENERIMAAN BARANG');
		if($caraprint == 'PRINT'){
			return $this->render('printTbp',['model'=>$model,'modDetail'=>$modDetail,'matauang'=>$matauang,'paramprint'=>$paramprint]);
		}
	}
	
	public function actionPrintTbpRincian(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TTerimaBhp::findOne($_GET['id']);
		$modDetail = \app\models\TTerimaBhpDetail::find()->where(['terima_bhp_id'=>$_GET['id']])->all();
		if(!empty($model->spo_id)){
			$modSPO = \app\models\TSpo::findOne($model->spo_id);
			if(!empty($modSPO)){
				$matauang = $modSPO->defaultValue->name_en;
			}
		}
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'RINCIAN PENERIMAAN BARANG');
		if($caraprint == 'PRINT'){
			return $this->render('printTbpRincian',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint]);
		}
	}
	
}
