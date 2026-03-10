<?php

namespace app\modules\kasir\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class SetorbankController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TKasBesarSetor();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->nominal = 0;
        $model->deskripsi = "Setor tunai BCA (Penerimaan KB Tgl.  sd  ) / Maryanto(Advantage)";
        
		if(isset($_GET['kas_besar_setor_id'])){
			$model = \app\models\TKasBesarSetor::findOne($_GET['kas_besar_setor_id']);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal);
		}
		
		if( Yii::$app->request->post('TKasBesarSetor')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_kas_besar_setor
                $success_2 = false; // t_kas_besar
				
				$model->load(\Yii::$app->request->post());
                $model->kode = \app\components\DeltaGenerator::kodeKasBesarSetor();
				
				$modKasBesar = new \app\models\TKasBesar();
				$modKasBesar->attributes = $model->attributes;
				$modKasBesar->kode = "-";
				$modKasBesar->tipe = "OUT";
				$modKasBesar->penerima = Yii::$app->user->identity->pegawai->pegawai_nama;
				$modKasBesar->closing = FALSE;
				$modKasBesar->reff = $model->kode;
				$modKasBesar->no_tandaterima = $model->reff_no_dokangkut;
				if($modKasBesar->validate()){
					$success_2 = $modKasBesar->save();
					$model->kas_besar_id = $modKasBesar->kas_besar_id;
					if($model->validate()){
						$success_1 = $model->save();
					}else{
						$success_1 = false;
					}
				}else{
					$success_2 = false;
				}
				
//				echo "<pre>";
//				print_r($success_1);
//				echo "<pre>";
//				print_r($success_2);
//				exit;
				
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Setor Tunai Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'kas_besar_setor_id'=>$model->kas_besar_setor_id]);
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
	
	public function actionDaftarAftersave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-setortunai'){
				$param['table']= \app\models\TKasBesarSetor::tableName();
				$param['pk']= \app\models\TKasBesarSetor::primaryKey()[0];
				$param['column'] = ['kas_besar_setor_id',$param['table'].'.kode',$param['table'].'.reff_no_bank',$param['table'].'.reff_no_dokangkut',['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],$param['table'].'.nominal'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarsetortunai');
        }
    }
	
	public function actionDetailsetor($tgl){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasBesarSetor::findOne(['tanggal'=>$_GET['tgl']]);
			if(!empty($model)){
				$caraprint = Yii::$app->request->get('caraprint');
				$paramprint['judul'] = Yii::t('app', 'Setor Tunai Kas Besar');
				return $this->renderAjax('detailsetortunai',['model'=>$model,'paramprint'=>$paramprint]);
			}else{
				return false;
			}
        }
	}
	
}
