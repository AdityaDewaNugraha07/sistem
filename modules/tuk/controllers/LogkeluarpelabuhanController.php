<?php

namespace app\modules\tuk\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class LogkeluarpelabuhanController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TKeluarPelabuhan();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
		$model->masaberlaku_awal = $model->tanggal;
		$model->masaberlaku_akhir = date('d/m/Y');
		$model->cara_keluar = "INDUSTRI";
		
		if(isset($_GET['keluar_pelabuhan_id'])){
            $model = \app\models\TKeluarPelabuhan::findOne($_GET['keluar_pelabuhan_id']);
            $model->kode_spm = $model->spmKo->kode;
            $model->cust_an_nama = $model->cust->cust_an_nama;
            $model->cust_pr_nama = $model->cust->cust_pr_nama;
            $model->cust_an_alamat = $model->cust->cust_an_alamat;
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$diff=date_diff( date_create($model->masaberlaku_awal) ,date_create($model->masaberlaku_akhir) );
			$model->masaberlaku_hari = ($diff->days+1);
			$modCust = \app\models\MCustomer::findOne($model->cust_id);
			$model->cust_is_pkp = ($modCust->cust_is_pkp)?1:0;
			$model->petugas_legalkayu = $model->petugasLegalkayu->pegawai->pegawai_nama;
        }
		
        if( Yii::$app->request->post('TKeluarPelabuhan')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_keluar_pelabuhan
                $success_2 = true; // t_kelauar_pelabuhan_detail
                $model->load(\Yii::$app->request->post());
                    $cekformat = explode("/", $model->nomor_dkb);
				if(count( (explode("/", $model->nomor_dkb)) == 5)){
					if(strlen($cekformat[0]) != 3){
						$errmsg = "Format Nomor Dokumen harus 3 digit pada slice pertama contoh XXX/XXX/CWM/X/YYYY";
					}
				}
                
                echo "<pre>";
                print_r($model->attributes);
                exit;
                
                if($model->validate()){ 
                    if($model->save()){ 
                        $success_1 = ( isset($errmsg)? false : true );
						if((isset($_GET['edit'])) && (isset($_GET['keluar_pelabuhan_id']))){
							$modDetail = \app\models\TKeluarPelabuhanDetail::find()->where(['keluar_pelabuhan_id'=>$_GET['keluar_pelabuhan_id']])->all();
							if(count($modDetail)>0){
								\app\models\TKeluarPelabuhanDetail::deleteAll(['keluar_pelabuhan_id'=>$_GET['keluar_pelabuhan_id']]);
							}
						}
						foreach($_POST['TKeluarPelabuhanDetail'] as $i => $detail){
							$modDetail = new \app\models\TKeluarPelabuhanDetail();
							$modDetail->attributes = $detail;
							$modDetail->keluar_pelabuhan_id = $model->keluar_pelabuhan_id;
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
				
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				exit;
				
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'keluar_pelabuhan_id'=>$model->keluar_pelabuhan_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionStockLog(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\HPersediaanDkb::tableName();
				$param['pk']= $param['table'].".".\app\models\HPersediaanDkb::primaryKey()[0];
				$param['column'] = ["CONCAT(m_kayu.group_kayu,' - ',m_kayu.kayu_nama) AS kayu",
									$param['table'].'.no_barcode',
									$param['table'].'.no_grade',
									$param['table'].'.no_btg',
									$param['table'].'.no_lap',
									$param['table'].'.lokasi',
									'SUM(dok_diameter) AS diameter',
									'SUM(dok_panjang) AS panjang',
									'dok_reduksi AS reduksi',
									'SUM(dok_volume) AS volume'];
				$param['join'] = "JOIN m_kayu ON m_kayu.kayu_id = h_persediaan_dkb.kayu_id";
				$param['group'] = "GROUP BY kayu,no_barcode,no_grade,no_btg,no_lap,lokasi,dok_reduksi";
				$param['having'] = "HAVING MOD(COUNT(no_barcode),2) != 0";
				$param['where'] = "lokasi = 'PELABUHAN'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('@app/modules/tuk/views/pemotongankayu/stockLog',['action'=> "/".\Yii::$app->controller->module->id."/".\Yii::$app->controller->id."/".\Yii::$app->controller->action->id ]);
        }
	}
    
    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
			$no_barcode = Yii::$app->request->post('no_barcode');
            $modPersediaan = \app\models\HPersediaanDkb::getCurrentStockPerBatang($no_barcode);
            $modIncomingDkb = \app\models\TIncomingDkb::findOne(['no_barcode'=>$no_barcode]);
			$modDetail = new \app\models\TKeluarPelabuhanDetail();
			$modDetail->attributes = $modPersediaan->attributes;
			$modDetail->panjang = $modPersediaan->dok_panjang;
			$modDetail->diameter = $modPersediaan->dok_diameter;
			$modDetail->volume = $modPersediaan->dok_volume;
            $modDetail->kondisi = $modPersediaan->dok_reduksi;
            $modDetail->asal_kayu = $modIncomingDkb->asal_kayu;
            $modDetail->keterangan = $modIncomingDkb->keterangan;
            $data['html'] = $this->renderPartial('_item',['modDetail'=>$modDetail]);
            $data['no_barcode'] = $modDetail->no_barcode;
            return $this->asJson($data);
        }
    }
}
