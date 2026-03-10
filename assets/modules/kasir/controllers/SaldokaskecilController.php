<?php

namespace app\modules\kasir\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class SaldokaskecilController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TKasKecil();
		$model->tgl_awal = date('d/m/Y',strtotime("-10 day"));
		$model->tgl_akhir = date('d/m/Y');
		
		if(\Yii::$app->request->isAjax){
			if(isset($_POST['search'])){
				$data['html'] = "<tr><td colspan='6' style='text-align: center;'>". Yii::t('app', 'No Data Available'). "</td></tr>";
				$data['saldoakhir'] = 0;
				$form_params = [];
				parse_str($_POST['formdata'],$form_params);
				$tgl_awal = $form_params['TKasKecil']['tgl_awal']." 00:00:00";
				$tgl_akhir = $form_params['TKasKecil']['tgl_akhir']." 23:59:59";
				$sql = "SELECT t_closing_kasir.tanggal::date, SUM(h_saldo_kaskecil.debit) AS debit, SUM(h_saldo_kaskecil.kredit) AS kredit FROM t_closing_kasir
						LEFT JOIN h_saldo_kaskecil ON h_saldo_kaskecil.tanggal::date = t_closing_kasir.tanggal::date
						WHERE t_closing_kasir.tanggal BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."' AND t_closing_kasir.tipe = 'KK'
						GROUP BY t_closing_kasir.tanggal::date ORDER BY t_closing_kasir.tanggal::date ASC
						";
				$mods = \Yii::$app->db->createCommand($sql)->queryAll();
				if(count($mods)>0){
					$data['html'] = "";
					foreach($mods as $i => $detail){
						$data['html'] .= $this->renderPartial('_item',['detail'=>$detail,'i'=>$i,'totalrows'=>count($mods)]);
					}
					$data['saldoakhir'] = \app\models\HSaldoKaskecil::getSaldoAkhir();
				}
				return $this->asJson($data);
			}
		}
		
		return $this->render('index',['model'=>$model]);
	}
	
}
