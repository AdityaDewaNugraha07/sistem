<?php

namespace app\modules\qms\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PicController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
			$param['table']= \app\models\MPicIso::tableName();
			$param['pk']= \app\models\MPicIso::primaryKey()[0];
			$param['column'] = ['pic_iso_id',
                                'departement_nama',
                                'pegawai_nama',
                                'kategori_dokumen',
                                'm_pic_iso.active'];
            $param['join'] = [' JOIN m_departement ON m_departement.departement_id = m_pic_iso.departement_id
                                JOIN m_pegawai ON m_pegawai.pegawai_id = m_pic_iso.pegawai_id'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		
		return $this->render('index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MPicIso();
			$model->active = true;
			if( Yii::$app->request->post('MPicIso')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; 
                    $model->load(\Yii::$app->request->post());
                    $kategori_dokumen = isset($_POST['MPicIso']['kategori_dokumen'])?$_POST['MPicIso']['kategori_dokumen']:[];
                    $model->kategori_dokumen = \yii\helpers\Json::encode($kategori_dokumen);
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }

                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
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
			return $this->renderAjax('create',['model'=>$model]);
		}
	}

    public function actionSetPegawai(){
        if(\Yii::$app->request->isAjax){
            $departement_id = Yii::$app->request->post('departement_id');
            $selected = Yii::$app->request->post('selected');
            $data = [];
            
            $model = \app\models\MPegawai::find()->where(['active'=>true])->orderBy('pegawai_nama ASC')->all();
            if($departement_id){
                $model = \app\models\MPegawai::find()->where(['departement_id'=>$departement_id, 'active'=>true])->orderBy('pegawai_nama ASC')->all();
            }

            $data['dropdown'] = '<option value=""></option>';
            if(count($model)>0){
                foreach($model as $i => $mod){
                    $options = ['value' => $mod->pegawai_id];
                    if ($mod->pegawai_id == $selected) {
                        $options['selected'] = true;
                    }
                    $data['dropdown'] .= \yii\bootstrap\Html::tag('option',$mod->pegawai_nama,$options);
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MPicIso::findOne($id);
            $kategori_dokumen = \yii\helpers\Json::decode($model->kategori_dokumen);
            $model->kategori_dokumen = implode(', ', $kategori_dokumen);
			return $this->renderAjax('info',['model'=>$model]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MPicIso::findOne($id);
			if( Yii::$app->request->post('MPicIso')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
                    $kategori_dokumen = isset($_POST['MPicIso']['kategori_dokumen'])?$_POST['MPicIso']['kategori_dokumen']:[];
                    $model->kategori_dokumen = \yii\helpers\Json::encode($kategori_dokumen);
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data PIC ISO Berhasil Diupdate');
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
			return $this->renderAjax('edit',['model'=>$model]);
		}
	}

    public function actionSetDDKategori(){
        $id = Yii::$app->request->post('id'); 
        if(\Yii::$app->request->isAjax){
            $data['html'] = '';
			$html = '<option></option>';
            
            $query = "SELECT kategori_dokumen FROM m_dokumen GROUP BY kategori_dokumen ORDER BY 1";
            $model = Yii::$app->db->createCommand($query)->queryAll();
            if(count($model) > 0){
                foreach($model as $i => $tag){
                    $drop[$tag['kategori_dokumen']] = $tag['kategori_dokumen'];
                }
            }
            foreach($drop as $i => $val){
                $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
            }
            if($id){
                $model = \app\models\MPicIso::findOne($id);
                if($model->kategori_dokumen){
                    $kategori = \yii\helpers\Json::decode($model->kategori_dokumen);
                    foreach($kategori as $i => $tag){
                        $drops[$tag] = $tag;
                    }
                    $data['kategori'] = array_keys($drops);
                }
            }
			$data['html'] = $html;
			return $this->asJson($data);
		}
    }
    
    public function actionPicPrint(){
        $this->layout = '@views/layouts/metronic/print';
        
        $search = Yii::$app->request->get('search');
        if ($search != "") {
            $andWhere = "WHERE (departement_nama ilike '%".$search."%' or pegawai_nama ilike '%".$search."%' or kategori_dokumen ilike '%".$search."%')";
        } else {
            $andWhere = '';
        }

        $query = " SELECT * FROM m_pic_iso
                  JOIN m_departement ON m_departement.departement_id = m_pic_iso.departement_id
                  JOIN m_pegawai ON m_pegawai.pegawai_id = m_pic_iso.pegawai_id
                  $andWhere ORDER BY pic_iso_id DESC";
        $model = Yii::$app->db->createCommand($query)->queryAll();
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = "Laporan Master PIC ISO";
		if ($caraprint == 'PRINT') {
			return $this->render('print',['model'=>$model,'paramprint'=>$paramprint]);
		} else if($caraprint == 'PDF') {
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('print',['model'=>$model,'paramprint'=>$paramprint, 'search'=>$search]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('print',['model'=>$model,'paramprint'=>$paramprint, 'search'=>$search]);
		}
    }	
}
