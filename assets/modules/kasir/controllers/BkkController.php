<?php

namespace app\modules\kasir\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class BkkController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TBkk();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->totalnominal = 0;
        $model->dibuat_oleh = \Yii::$app->user->identity->pegawai_id;
		$model->ganti_uangkas = TRUE;
        
		if(isset($_GET['bkk_id'])){
			$model = \app\models\TBkk::findOne($_GET['bkk_id']);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->totalnominal = \app\components\DeltaFormatter::formatNumberForUserFloat($model->totalnominal);
			$model->diterima_oleh = \app\models\MPegawai::findOne(['pegawai_nama'=>$model->diterima_oleh])->pegawai_id;
		}
		
		if(isset($_GET['kas_bon_id'])){
			$modKasBon = \app\models\TKasBon::findOne($_GET['kas_bon_id']);
			$model->kas_bon_id = \app\components\DeltaFormatter::formatNumberForUserFloat($modKasBon->kas_bon_id);
			$model->totalnominal = \app\components\DeltaFormatter::formatNumberForUserFloat($modKasBon->nominal);
			if($modKasBon->tipe == "KB"){
				$model->tipe = "Kas Besar";
			}else if($modKasBon->tipe == "KK"){
				$model->tipe = "Kas Kecil";
			}
		}
		
		if( Yii::$app->request->post('TBkk')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_bkk
                $success_2 = true; // t_kas_bon -- tunggal
                $success_3 = true; // t_kas_bon -- jamak
                $model->load(\Yii::$app->request->post());
				$model->kode = \app\components\DeltaGenerator::kodeBKK();
				$arr = [];
				foreach($_POST['TBkk'] as $i => $bkk){
					if(is_array($bkk)){
						$arr[] = $bkk;
					}
				}
				$model->deskripsi = \yii\helpers\Json::encode($arr);
				$model->diterima_oleh = \app\models\MPegawai::findOne($model->diterima_oleh)->pegawai_nama;
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if(!empty($_POST['TBkk']['kas_bon_id'])){
							$modKasBon = \app\models\TKasBon::findOne($_POST['TBkk']['kas_bon_id']);
							$modKasBon->bkk_id = $model->bkk_id;
							if($modKasBon->validate()){
								$success_2 = $modKasBon->save();
							}
						}
						
						foreach($_POST['TBkk'] as $i => $bkk){
							if(is_array($bkk)){
								if(!empty($bkk['kas_bon_id'])){
									$modKasBonJamak = \app\models\TKasBon::findOne($bkk['kas_bon_id']);
									$modKasBonJamak->bkk_id = $model->bkk_id;
									echo "<pre>";
									print_r($modKasBonJamak->attributes);
									if($modKasBonJamak->validate()){
										$success_2 = $modKasBonJamak->save();
									}
								}
							}
						}
						
                    }
                }
				
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>2";
//				print_r($success_3);
//				exit;
				
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data BKK Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'bkk_id'=>$model->bkk_id]);
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
	
	public function actionPrintout(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TBkk::findOne($_GET['id']);
		$modDetail = \yii\helpers\Json::decode($model->deskripsi);
		$total = 0;
		if(count($modDetail)>0){
			foreach($modDetail as $i => $det){
				$total += $det['detail_nominal'];
			}
		}
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'BUKTI KAS KELUAR');
		if($caraprint == 'PRINT'){
			return $this->render('printout',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint,'total'=>$total]);
		}
	}
	
	public function actionDetailBkk(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TBkk::findOne($_GET['id']);
			$modDetail = \yii\helpers\Json::decode($model->deskripsi);
			$total = 0;
			if(count($modDetail)>0){
				foreach($modDetail as $i => $det){
					$total += $det['detail_nominal'];
				}
			}
			$caraprint = Yii::$app->request->get('caraprint');
			$paramprint['judul'] = Yii::t('app', 'BUKTI KAS KELUAR');
			return $this->renderAjax('detailbkk',['model'=>$model,'modDetail'=>$modDetail,'paramprint'=>$paramprint,'total'=>$total]);
        }
	}
	
	public function actionDaftarBkk(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-bkk'){
				$param['table']= \app\models\TBkk::tableName();
				$param['pk']= \app\models\TBkk::primaryKey()[0];
				$param['column'] = ['bkk_id','tipe','kode',['col_name'=>'tanggal','formatter'=>'formatDateForUser2'],'diterima_oleh','totalnominal'];
				return \yii\helpers\Json::encode( \app\components\SSP::complex( $param ) );
			}
			return $this->renderAjax('daftarBkk');
        }
    }
	
	public function actionAddItem(){
		if(\Yii::$app->request->isAjax){
			$data = [];
            $data['html'] = '';
			$tgl = Yii::$app->request->post('tgl');
			$model = new \app\models\TBkk();
			$model->detail_deskripsi = "";
			$model->detail_nominal = 0;
			$data['html'] = $this->renderPartial('_item',['model'=>$model]);
			return $this->asJson($data);
		}
	}
	
	public function actionGetDetailBkk(){
        if(\Yii::$app->request->isAjax){
			$bkk_id = Yii::$app->request->post('bkk_id');
			$model = \app\models\TBkk::findOne($bkk_id);
			$detail = \yii\helpers\Json::decode($model->deskripsi);
			$data['html'] = '';
			if(count($detail)>0){
				foreach($detail as $i => $det){
					$data['html'] .= $this->renderPartial('_itemAfterSave',['model'=>$det,'i'=>$i]);
				}
			}
			$data['model'] = $model->attributes;
			return $this->asJson($data);
        }
    }
	
	public function actionGetDetailFromKasbon(){
        if(\Yii::$app->request->isAjax){
			$kas_bon_id = Yii::$app->request->post('kas_bon_id');
			$modKasBon = \app\models\TKasBon::findOne($kas_bon_id);
			$model = new \app\models\TBkk();
			$model->detail_deskripsi = $modKasBon->deskripsi;
			$model->detail_nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($modKasBon->nominal);
			$model->kas_bon_id = $modKasBon->kas_bon_id;
			$model->kode_kasbon = $modKasBon->kode;
			$data['html'] = $this->renderPartial('_item',['model'=>$model]);
			return $this->asJson($data);
        }
    }
	
	public function actionPickPanelKasbon(){
        if(\Yii::$app->request->isAjax){
			$rowid = \Yii::$app->request->get('rowid');
			if(\Yii::$app->request->get('dt')=='table-dt'){
				$param['table']= \app\models\TKasBon::tableName();
				$param['pk']= \app\models\TKasBon::primaryKey()[0];
				$param['column'] = ['kas_bon_id','kode',['col_name'=>'tanggal','formatter'=>'formatDateForUser2'],'penerima','deskripsi','nominal'];
				$param['where'] = "kas_kecil_id IS NULL AND tipe = 'KK'";
				$param['order'] = "created_at DESC";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('pickPanelKasbon',['rowid'=>$rowid]);
        }
    }
	
	public function actionPickKasbon(){
        if(\Yii::$app->request->isAjax){
			$picked = \Yii::$app->request->post('picked');
			$tgl = \Yii::$app->request->post('tgl');
			$parsed = explode(',', $picked);
			$clean = []; $data = []; $data['html'] = '';
			foreach($parsed as $parse){
				if(!empty($parse)){
					$clean[] = str_replace('-', '', $parse);
				}
			}
			if(!empty($clean)){
				foreach($clean as $id){
					$modKasbon = \app\models\TKasBon::findOne($id);
					$model = new \app\models\TBkk();
					$model->kas_bon_id = $modKasbon->kas_bon_id;
					$model->detail_deskripsi = $modKasbon->deskripsi;
					$model->detail_nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($modKasbon->nominal);
					$model->kode_kasbon = $modKasbon->kode;
					$data['html'] .= $this->renderPartial('_item',['model'=>$model,'disabled'=>TRUE]);
				}
			}
			
			return $this->asJson($data);
        }
    }
	
}
