<?php

namespace app\modules\logistik\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class StockbhpController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex($bhp_id=null,$tgl_awal=null,$tgl_akhir=null){
		$model = new \app\models\HPersediaanBhp();
		$model->tgl_awal = (empty($tgl_awal))? $model->tgl_awal = date('d/m/Y',strtotime("-30 day")) : $tgl_awal;
		$model->tgl_akhir = (empty($tgl_akhir))? $model->tgl_akhir = date('d/m/Y') : $tgl_akhir;
		if(!empty($bhp_id)){
			$model->bhp_id = $bhp_id;
			$modPersediaan = \app\models\HPersediaanBhp::find()->where(['bhp_id'=>$bhp_id])->orderBy(['created_at'=>SORT_DESC])->all();
		}
		return $this->render('index',['model'=>$model]);
	}
	public function actionGetStockActivity(){
		if(\Yii::$app->request->isAjax){
			$bhp_id = Yii::$app->request->post('bhp_id');
			$tgl_awal = Yii::$app->request->post('tgl_awal');
			$tgl_akhir = Yii::$app->request->post('tgl_akhir');
			$data = [];
			$data['html'] = '';
			$data['bhp_nm'] = '';
			if(!empty($bhp_id)){
				$modBhp = \app\models\MBrgBhp::findOne($bhp_id); 
				$modPersediaan = \app\models\HPersediaanBhp::find()->where(['bhp_id'=>$bhp_id])
						->andWhere("tgl_transaksi BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."' ")
						->orderBy(['created_at'=>SORT_ASC])->all();
				$saldoawal = \app\models\HPersediaanBhp::find()->select("sum(qty_in)-sum(qty_out) as total_qty")->where(['bhp_id'=>$bhp_id])
						->andWhere("tgl_transaksi < '{$tgl_awal}'")->one()->total_qty;
				$data['html'] .= $this->renderPartial('_stockActivity',['modPersediaan'=>$modPersediaan,'bhp_id'=>$bhp_id,'modBhp'=>$modBhp,'saldoawal'=>$saldoawal]);
				$data['modBhp'] = $modBhp->attributes;
			}
			return $this->asJson($data);
		}
	}
	public function actionGetAdjStock(){
		if(\Yii::$app->request->isAjax){
			$bhp_id = Yii::$app->request->post('bhp_id');
			$data = [];
			$data['html'] = '';
			if(!empty($bhp_id)){
				$modBhp = \app\models\MBrgBhp::findOne($bhp_id);
				$model = new \app\models\TAdjustmentstock();
				$model->kode = 'Auto Generate';
				$model->tanggal = date('d/m/Y');
				$model->bhp_nm = $modBhp->bhp_nm;
				$model->qty_in = 0;
				$model->qty_out = 0;
				$data['html'] .= $this->renderPartial('_adjStock',['model'=>$model]);
			}
			return $this->asJson($data);
		}
	}
	
	public function actionGetSPB(){
		if(\Yii::$app->request->isAjax){
			$bhp_id = Yii::$app->request->post('bhp_id');
			$tgl_awal = Yii::$app->request->post('tgl_awal');
			$tgl_akhir = Yii::$app->request->post('tgl_akhir');
			$data = [];
			$data['html'] = '';
			if(!empty($bhp_id)){
				$sql = "SELECT * FROM t_spb_detail
						JOIN t_spb ON t_spb.spb_id = t_spb_detail.spb_id
						WHERE spb_status != 'DITOLAK' AND approve_status != '".\app\models\TApproval::STATUS_REJECTED."' AND bhp_id = ".$bhp_id." 
							AND spb_tanggal BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."' 
						ORDER BY created_at DESC";
				$models = Yii::$app->db->createCommand($sql)->queryAll();
				if(count($models)>0){
					$data['html'] .= $this->renderPartial('_contentSPB',['models'=>$models]);
					$data['queryResult'] = $models;
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionGetSPP(){
		if(\Yii::$app->request->isAjax){
			$bhp_id = Yii::$app->request->post('bhp_id');
			$tgl_awal = Yii::$app->request->post('tgl_awal');
			$tgl_akhir = Yii::$app->request->post('tgl_akhir');
			$data = [];
			$data['html'] = '';
			if(!empty($bhp_id)){
				$sql = "SELECT * FROM t_spp_detail
						JOIN t_spp ON t_spp_detail.spp_id = t_spp.spp_id
						WHERE cancel_transaksi_id IS NULL AND bhp_id = ".$bhp_id."
							AND spp_tanggal BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."' 
						ORDER BY created_at DESC";
				$models = Yii::$app->db->createCommand($sql)->queryAll();
				if(count($models)>0){
					$data['html'] .= $this->renderPartial('_contentSPP',['models'=>$models]);
					$data['queryResult'] = $models;
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionGetTBP(){
		if(\Yii::$app->request->isAjax){
			$bhp_id = Yii::$app->request->post('bhp_id');
			$tgl_awal = Yii::$app->request->post('tgl_awal');
			$tgl_akhir = Yii::$app->request->post('tgl_akhir');
			$data = [];
			$data['html'] = '';
			if(!empty($bhp_id)){
				$sql = "SELECT * FROM t_terima_bhp_detail
						JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_terima_bhp_detail.terima_bhp_id
						WHERE t_terima_bhp.cancel_transaksi_id IS NULL AND bhp_id = ".$bhp_id."
							AND tglterima BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."' 
						ORDER BY created_at DESC";
				$models = Yii::$app->db->createCommand($sql)->queryAll();
				if(count($models)>0){
					$data['html'] .= $this->renderPartial('_contentTBP',['models'=>$models]);
					$data['queryResult'] = $models;
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionGetSPO(){
		if(\Yii::$app->request->isAjax){
			$bhp_id = Yii::$app->request->post('bhp_id');
			$tgl_awal = Yii::$app->request->post('tgl_awal');
			$tgl_akhir = Yii::$app->request->post('tgl_akhir');
			$data = [];
			$data['html'] = '';
			if(!empty($bhp_id)){
				$sql = "SELECT * FROM t_spo_detail
						JOIN t_spo ON t_spo.spo_id = t_spo_detail.spo_id
						WHERE cancel_transaksi_id IS NULL AND bhp_id = ".$bhp_id."
							AND spo_tanggal BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."' 
						ORDER BY created_at DESC";
				$models = Yii::$app->db->createCommand($sql)->queryAll();
				if(count($models)>0){
					$data['html'] .= $this->renderPartial('_contentSPO',['models'=>$models]);
					$data['queryResult'] = $models;
				}
			}
			return $this->asJson($data);
		}
	}
	public function actionGetSPL(){
		if(\Yii::$app->request->isAjax){
			$bhp_id = Yii::$app->request->post('bhp_id');
			$tgl_awal = Yii::$app->request->post('tgl_awal');
			$tgl_akhir = Yii::$app->request->post('tgl_akhir');
			$data = [];
			$data['html'] = '';
			if(!empty($bhp_id)){
				$sql = "SELECT * FROM t_spl_detail
						JOIN t_spl ON t_spl.spl_id = t_spl_detail.spl_id
						WHERE cancel_transaksi_id IS NULL AND bhp_id = ".$bhp_id."
							AND spl_tanggal BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."' 
						ORDER BY created_at DESC";
				$models = Yii::$app->db->createCommand($sql)->queryAll();
				if(count($models)>0){
					$data['html'] .= $this->renderPartial('_contentSPL',['models'=>$models]);
					$data['queryResult'] = $models;
				}
			}
			return $this->asJson($data);
		}
	}
	
}
