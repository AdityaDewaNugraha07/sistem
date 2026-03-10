<?php

namespace app\modules\tuk\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PemotongankayuController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TPemotonganKayu();
		$model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
		$modDetail = [];
		
		if(isset($_GET['pemotongan_kayu_id'])){
            $model = \app\models\TPemotonganKayu::findOne($_GET['pemotongan_kayu_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
        }
		
		if( Yii::$app->request->post('TPemotonganKayu') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pemotongan_kayu
                $success_2 = true; // t_pemotongan_kayu_detail
                $success_3 = true; // h_persediaan_dkb
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodePemotonganKayu();
				}
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if(isset($_POST['TPemotonganKayuDetail'])){
							if(!isset($_GET['edit'])){
								foreach($_POST['TPemotonganKayuDetail'] as $i => $detail){
									$modDetail = new \app\models\TPemotonganKayuDetail();
									$modDetail->attributes = $detail;
									$modDetail->pemotongan_kayu_id = $model->pemotongan_kayu_id;
									$hasil_pemotongan = [];
									foreach($detail as $arr){
										if(is_array($arr)){
											$hasil_pemotongan[] = $arr;
										}
									}
									if(count($hasil_pemotongan)>0){
										$hasil_pemotongan = \yii\helpers\Json::encode($hasil_pemotongan);
									}
									$modDetail->hasil_pemotongan = $hasil_pemotongan;
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

								// UPDATE STOCK LOG
								foreach($_POST['TPemotonganKayuDetail'] as $i => $detail){
									$modStock = \app\models\HPersediaanDkb::getCurrentStockPerBatang($detail['no_barcode']);
									$modPersediaan = new \app\models\HPersediaanDkb();
									$modPersediaan->attributes = $modStock->attributes;
									$modPersediaan->status = "OUT";
									$modPersediaan->reff_no = $model->kode;
									$success_3 &= \app\models\HPersediaanDkb::updateStokPersediaan($modPersediaan);
									foreach($detail as $arr){
										if(is_array($arr)){
											$modStock = \app\models\HPersediaanDkb::getCurrentStockPerBatang($detail['no_barcode']);
											$modPersediaan = new \app\models\HPersediaanDkb();
											$modPersediaan->attributes = $modStock->attributes;
											$modPersediaan->reff_no = $model->kode;
											$modPersediaan->no_barcode = $arr['no_barcode_baru'];
											$modPersediaan->dok_panjang = $arr['panjang_baru'];
											$modPersediaan->dok_volume = $arr['volume_baru'];
											$modPersediaan->status = "IN";
											$success_3 &= \app\models\HPersediaanDkb::updateStokPersediaan($modPersediaan);
										}
									}
								}
								// END UPDATE
							}
						}
                    }
                }
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>3";
//				print_r($success_3);
//				exit;
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'pemotongan_kayu_id'=>$model->pemotongan_kayu_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail]);
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
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('stockLog');
        }
	}
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
			$no_barcode = Yii::$app->request->post('no_barcode');
            $modPersediaan = \app\models\HPersediaanDkb::getCurrentStockPerBatang($no_barcode);
			$modDetail = new \app\models\TPemotonganKayuDetail();
			$modDetail->attributes = $modPersediaan->attributes;
			$modDetail->panjang = $modPersediaan->dok_panjang;
			$modDetail->reduksi = $modPersediaan->dok_reduksi;
			$modDetail->volume = $modPersediaan->dok_volume;
			$modDetail->jumlah_potong = 2;
            $data['html'] = $this->renderPartial('_item',['modDetail'=>$modDetail]);
            $data['no_barcode'] = $modPersediaan->no_barcode;
            return $this->asJson($data);
        }
    }
	
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $pemotongan_kayu_id = Yii::$app->request->post('pemotongan_kayu_id');
            $edit = Yii::$app->request->post('edit');
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($pemotongan_kayu_id)){
                $modDetail = \app\models\TPemotonganKayuDetail::find()->where(['pemotongan_kayu_id'=>$pemotongan_kayu_id])->orderBy(['pemotongan_kayu_detail_id'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    foreach($modDetail as $i => $model){
						$data['html'] .= $this->renderPartial('_item',['modDetail'=>$model,'edit'=>$edit]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPemotonganKayu::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TPemotonganKayu::primaryKey()[0];
				$param['column'] = ['t_pemotongan_kayu.pemotongan_kayu_id',
									't_pemotongan_kayu.kode',
									't_pemotongan_kayu.nomor',
									't_pemotongan_kayu.tanggal',
									'm_pegawai.pegawai_nama',
									't_pemotongan_kayu.keterangan',
									];
				$param['join'] = ['JOIN m_petugas_legalkayu ON m_petugas_legalkayu.petugas_legalkayu_id = t_pemotongan_kayu.petugas
								   JOIN m_pegawai ON m_pegawai.pegawai_id = m_petugas_legalkayu.pegawai_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionPrintBAP(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TPemotonganKayu::findOne($_GET['id']);
		$modDetail = \app\models\TPemotonganKayuDetail::find()->where(['pemotongan_kayu_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'BERITA ACARA PEMOTONGAN KAYU BULAT');
		if($caraprint == 'PRINT'){
			return $this->render('printBAP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printBAP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printBAP',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}
}
