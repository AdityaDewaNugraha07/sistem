<?php

namespace app\modules\gudang\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class HistoryscanController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\TProdukKeluar::tableName();
			$param['pk']= \app\models\TProdukKeluar::primaryKey()[0];
			$param['column'] = [$param['table'].'.produk_keluar_id',
								't_produk_keluar.nomor_produksi',
								't_produk_keluar.qty_kecil',
								't_produk_keluar.satuan_kecil',
								't_produk_keluar.kubikasi',
								't_spm_ko.kode',
								'm_customer.cust_an_nama',
								't_produk_keluar.created_at'];
            
			$param['join']= ['JOIN t_spm_ko ON t_produk_keluar.reff_no = t_spm_ko.kode
							  JOIN m_customer ON m_customer.cust_id = t_spm_ko.cust_id'];
			$param['where'] = "t_produk_keluar.keterangan = 'Scan Result'";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index');
	}
}
