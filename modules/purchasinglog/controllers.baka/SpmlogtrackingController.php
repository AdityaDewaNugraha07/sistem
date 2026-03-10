<?php
namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class SpmlogtrackingController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TSpkShippingTracking();
        $modelTSpkShipping = new \app\models\TSpkShipping();
        $modPengajuanPembelianlog = new \app\models\TPengajuanPembelianlog();
        $model->tanggal = date('d/m/Y H:i');
        $model->jenis = $model->jenis;
        $model->lokasi = $model->lokasi;
        $model->keterangan = $model->keterangan;

		if(isset($_GET['spk_shipping_tracking_id'])){
			$model = \app\models\TSpkShippingTracking::findOne($_GET['spk_shipping_tracking_id']);
            $model->tanggal = date("d/m/Y H:i", strtotime($model->tanggal));
            $model->jenis = $model->jenis;
            $model->lokasi = $model->lokasi;
            $model->keterangan = $model->keterangan;
            $spk_shipping_id = $model->spk_shipping_id;
            $modelTSpkShipping = \app\models\TSpkShipping::findOne($spk_shipping_id);
            $modPengajuanPembelianlog = \app\models\TPengajuanPembelianlog::findAll(['spk_shipping_id'=>$spk_shipping_id]);
		}

        if( Yii::$app->request->post('TSpkShippingTracking')){
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $success_1 = false; // t_spk_shipping_tracking
                $model->load(\Yii::$app->request->post());
                $model->keterangan = $_POST['TSpkShippingTracking']['keterangan'];
                // t_spk_shipping_tracking : simpan data
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                    } else {
                        $success_1 = false;
                    }
                }

                $success_2 = false; // t_spk_shipping
                $spk_shipping_id = $model->spk_shipping_id;
                $model->jenis == "Port Of Discharge" ? $status_jenis = 1 : $status_jenis = 0;
                $sql_update = "update t_spk_shipping set status_jenis = ".$status_jenis." where spk_shipping_id = ".$spk_shipping_id." ";
                $success_2 = Yii::$app->db->createCommand($sql_update)->execute();

                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'spk_shipping_tracking_id'=>$model->spk_shipping_tracking_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?(implode(",", array_values($errmsg)[0])):Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'tanggal'=>$model->tanggal,'jenis'=>$model->jenis,'lokasi'=>$model->lokasi,'keterangan'=>$model->keterangan,'modelTSpkShipping'=>$modelTSpkShipping,'modPengajuanPembelianlog'=>$modPengajuanPembelianlog]);
	}

	public function actionFindSpmLog(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$sql = " select * from t_spk_shipping ".
					        "   where kode ilike '%{$term}%' ".
                            "   and status = 'APPROVED' ".
                            "   and (status_jenis = 0 ".
                            "   or status_jenis is null) ".
					        "   order by kode ASC ".
                            "   ";
            } else {
				$sql = " select * from t_spk_shipping ".
					        "   where status = 'APPROVED' ".
                            "   and (status_jenis = 0 ".
                            "   or status_jenis is null) ".
					        "   order by kode ASC ".
                            "   ";
            }
            $mod = Yii::$app->db->createCommand($sql)->queryAll();
            $ret = [];
            if(count($mod)>0){
                $arraymap = \yii\helpers\ArrayHelper::map($mod, 'spk_shipping_id', 'kode');
                foreach($mod as $i => $val){
                    $data[] = ['id'=>$val['spk_shipping_id'], 'text'=>$val['kode']];
                }
            }
            return $this->asJson($data);
        }
	}

    public function actionDaftarSpmLog(){
		if(\Yii::$app->request->isAjax){
            $pickDaftarSpmLog = \Yii::$app->request->get('pickDaftarSpmLog');
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TSpkShipping::tableName();
				$param['pk']= $param['table'].".". \app\models\TSpkShipping::primaryKey()[0];
				$param['column'] = [$param['table'].'.kode',
                                        $param['table'].'.tanggal',
                                        $param['table'].'.nama_tongkang',
                                        $param['table'].'.lokasi_muat',
                                        $param['table'].'.etd',
                                        $param['table'].'.eta_logpond',
                                        $param['table'].'.eta',
                                        $param['table'].'.lokasi_muat',
                                        $param['table'].'.estimasi_total_batang',
                                        $param['table'].'.estimasi_total_m3',
                                        $param['table'].'.keterangan',
                                        $param['table'].'.status',
									];
				$param['where'] = "t_spk_shipping.status = 'APPROVED' ";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('_daftarSpmLog',['pickDaftarSpmLog'=>$pickDaftarSpmLog]);
        }
    }

    public function actionDaftarSpmLogTracking() {
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-daftarSpmLogTracking'){
				$param['table']= \app\models\TSpkShippingTracking::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TSpkShippingTracking::primaryKey()[0];

				$param['column'] = ['t_spk_shipping_tracking.spk_shipping_tracking_id',
									't_spk_shipping.kode',
									't_spk_shipping_tracking.tanggal',
									't_spk_shipping_tracking.jenis',
									't_spk_shipping_tracking.lokasi',
									't_spk_shipping_tracking.keterangan',
                                    't_spk_shipping.status_jenis',
                                    $param['table'].'.spk_shipping_id'
									];
                $param['join']= ['JOIN t_spk_shipping ON t_spk_shipping.spk_shipping_id = '.$param['table'].'.spk_shipping_id'];
                $param['where'] = "t_spk_shipping.status = 'APPROVED' ";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('_daftarSpmLogTracking');
        }
    }

}
