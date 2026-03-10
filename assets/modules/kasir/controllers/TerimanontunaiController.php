<?php

namespace app\modules\kasir\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class TerimanontunaiController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TKasBesarNontunai();
        $model->tanggal = date('d/m/Y');
		$model->kode = "Auto Generate";
        
		$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
        if( isset($form_params['TKasBesarNontunai']) ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_kas_besar
				$post = $form_params['TKasBesarNontunai'];
				if(count($post)>0){
					foreach($post as $peng){ $post = $peng; }
					$mod = new \app\models\TKasBesarNontunai();
					$mod->attributes = $post;
					$mod->kode = \app\components\DeltaGenerator::kodeKasBesarNontunai($model->tanggal);
					$mod->closing = false;
					$mod->seq = \app\components\DeltaGenerator::sequenceKasBesarNontunai($mod->tanggal);
					if(!empty($post['kas_besar_nontunai_id'])){
						$mod = \app\models\TKasBesarNontunai::findOne($post['kas_besar_nontunai_id']);
						$mod->attributes = $post;
					}
					if($mod->validate()){
						if($mod->save()){
							$success_1 = true;
						}
					}else{
						$success_1 = false;
						$data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
					}
				}
                if ($success_1) {
					$transaction->commit();
					$data['status'] = true;
					$data['kode'] = $mod->kode;
					$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
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
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($tgl)){
                $modNontunai = \app\models\TKasBesarNontunai::find()->where(['tanggal'=>$tgl])->orderBy(['seq'=>SORT_ASC])->all();
                if(count($modNontunai)>0){
                    foreach($modNontunai as $i => $model){
						$model->tanggal_jatuhtempo = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_jatuhtempo);
						$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal);
						$data['html'] .= $this->renderPartial('_item',['model'=>$model,'i'=>$i,'disabled'=>$disabled]);
						$data['kode'] = $model->kode;
                    }
					$kas = \app\models\TKasBesar::find()->where(['tanggal'=>$tgl])->one();
					if(count($kas)>0){
						$data['statusclosing'] = ($kas->closing == true)?1:0;
					}
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionAddItem(){
		if(\Yii::$app->request->isAjax){
			$data = [];
            $data['html'] = '';
			$tgl = Yii::$app->request->post('tgl');
			$model = new \app\models\TKasBesarNontunai();
			$model->tanggal = $tgl;
			$model->nominal = 0;
			$data['html'] = $this->renderPartial('_item',['model'=>$model]);
			return $this->asJson($data);
		}
	}
	
	public function actionDeleteItem($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasBesarNontunai::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = 'getItems()';
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>'deleteItem']);
		}
	}
	
	public function actionDetailnontunai($tgl){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TKasBesarNontunai::find()->where(['tanggal'=>$_GET['tgl']])->orderBy(['seq'=>SORT_ASC])->all();
			if(count($model)>0){
				$caraprint = Yii::$app->request->get('caraprint');
				$paramprint['judul'] = Yii::t('app', 'Laporan Harian Penerimaan GIRO / CEK');
				return $this->renderAjax('detailnontunai',['modDetail'=>$model,'paramprint'=>$paramprint]);
			}else{
				return false;
			}
        }
	}
	public function actionPrintnontunai($tgl){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TKasBesarNontunai::find()->where(['tanggal'=>$_GET['tgl']])->orderBy(['seq'=>SORT_ASC])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'Laporan Harian Penerimaan GIRO / CEK');
		if($caraprint == 'PRINT'){
			return $this->render('printnontunai',['modDetail'=>$model,'paramprint'=>$paramprint]);
		}
	}
	
}
