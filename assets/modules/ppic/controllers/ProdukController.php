<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ProdukController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-produk'){
			$param['table']= \app\models\MBrgProduk::tableName();
			$param['pk']= \app\models\MBrgProduk::primaryKey()[0];
			$param['column'] = ['produk_id','produk_group','produk_kode','produk_nama','produk_dimensi',$param['table'].'.active','created_at'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		
		return $this->render('index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MBrgProduk();
			$model->active = true;
			$model->produk_satuan_besar = \app\components\Params::DEFAULT_PRODUK_SATUAN_BESAR;
			$model->produk_satuan_kecil = \app\components\Params::DEFAULT_PRODUK_SATUAN_KECIL;
            $model->produk_qty_satuan_kecil = 0;
			$model->produk_p = 0;
			$model->produk_l = 0;
			$model->produk_t = 0;
			$model->produk_p_satuan = \app\components\Params::DEFAULT_PRODUK_SATUAN_DIMENSI;
			$model->produk_l_satuan = \app\components\Params::DEFAULT_PRODUK_SATUAN_DIMENSI;
			$model->produk_t_satuan = \app\components\Params::DEFAULT_PRODUK_SATUAN_DIMENSI;
			
			if( Yii::$app->request->post('MBrgProduk')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
                    $model->file1 = \yii\web\UploadedFile::getInstance($model, 'produk_gbr');
//					if($model->produk_group == "Plywood" || $model->produk_dimensi == "Veneer"){
//						$plymill_shift = "";
//						foreach($model->plymill_shift as $i => $plymill){
//							$plymill_shift .= $plymill;
//						}
//						$model->plymill_shift = $plymill_shift;
//						$model->sawmill_line = "-";
//					}else if($model->produk_group == "Sawntimber"){
//						$model->plymill_shift = "-";
//					}else{
//						$model->plymill_shift = "-";
//						$model->sawmill_line = "-";
//					}
                    if($model->validate()){ 
                        if(!empty($model->file1)){ 
                            $randomstring = Yii::$app->getSecurity()->generateRandomString(4); 
                            $dir_path = Yii::$app->basePath.'/web/uploads/gud/produk';
                            if(!is_dir($dir_path)){ 
                                mkdir(Yii::$app->basePath.'/web/uploads/gud'); 
                                mkdir($dir_path); 
                            } 
                            $file_path = $dir_path.'/'.date('Ymd_His').'-'.$model->produk_group.'-'.$randomstring.'.' . $model->file1->extension;
                            $model->file1->saveAs($file_path,false);
                            $model->produk_gbr = date('Ymd_His').'-'.$model->produk_group.'-'.$randomstring.'.' .$model->file1->extension;
                        }
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

    public function actionInfo($id,$disableAction=null){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MBrgProduk::findOne($id);
			return $this->renderAjax('info',['model'=>$model,'disableAction'=>$disableAction]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MBrgProduk::findOne($id);
			if( Yii::$app->request->post('MBrgProduk')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $file_old = $model->produk_gbr;
                    $model->load(\Yii::$app->request->post());
                    $model->file1 = \yii\web\UploadedFile::getInstance($model, 'produk_gbr');
                    $model->produk_gbr = !empty($model->produk_gbr)?$model->produk_gbr:$file_old;
                    if($model->validate()){
                        if(!empty($model->file1)){
                            $randomstring = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/gud/produk';
                            if(!is_dir($dir_path)){
                                mkdir(Yii::$app->basePath.'/web/uploads/gud');
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-'.$model->produk_group.'-'.$randomstring.'.' . $model->file1->extension;
                            $model->file1->saveAs($file_path,false);
                            $model->produk_gbr = date('Ymd_His').'-'.$model->produk_group.'-'.$randomstring.'.' .$model->file1->extension;
                            if($file_old != null){
                                if (file_exists(Yii::$app->basePath.'/web/uploads/gud/produk/'.$file_old)) {
                                    unlink(Yii::$app->basePath.'/web/uploads/gud/produk/'.$file_old);
                                }
                            }
                        }
                        if($model->save()){
                            $success_1 = true;
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Data Produk Berhasil Diupdate');
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
	
	public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MBrgProduk::findOne($id);
            $file_old = $model->produk_gbr;
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
//                    if(count($modMenu)>0){
//                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_DELETE_RESTRICT_MESSAGE);
//                    }else{
                        if($model->delete()){
                            if($file_old != null){
                                if (file_exists(Yii::$app->basePath.'/web/uploads/gud/produk/'.$file_old)) {
                                    unlink(Yii::$app->basePath.'/web/uploads/gud/produk/'.$file_old);
                                }
                            }
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data Produk Gagal dihapus');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Produk Berhasil Dihapus');
                        } else {
                            $transaction->rollback();
                            $data['status'] = false;
                            (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                            (isset($data['message_validate']) ? $data['message'] = null : '');
                        }
//                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid]);
		}
	}
    
    public function actionSetDropdownJenisKayu(){
        if(\Yii::$app->request->isAjax){
			$jenis_produk = Yii::$app->request->post('jenis_produk');
            $mod = \app\models\MJenisKayu::find()->where(['active'=>true,'jenis_produk'=>$jenis_produk])->all();
			$html = '';
			if(!empty($mod)){
				$arraymap = \yii\helpers\ArrayHelper::map($mod, 'nama', 'nama');
				foreach($arraymap as $i => $val){
					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
				}
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
    public function actionSetDropdownGrade(){
        if(\Yii::$app->request->isAjax){
			$jenis_produk = Yii::$app->request->post('jenis_produk');
            $mod = \app\models\MGrade::find()->where(['active'=>true,'jenis_produk'=>$jenis_produk])->all();
			$html = '';
			if(!empty($mod)){
				$arraymap = \yii\helpers\ArrayHelper::map($mod, 'nama', 'nama');
				foreach($arraymap as $i => $val){
					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
				}
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	public function actionSetDropdownGlue(){
        if(\Yii::$app->request->isAjax){
			$jenis_produk = Yii::$app->request->post('jenis_produk');
            $mod = \app\models\MGlue::find()->where(['active'=>true,'jenis_produk'=>$jenis_produk])->all();
			$html = '';
			if(!empty($mod)){
				$arraymap = \yii\helpers\ArrayHelper::map($mod, 'nama', 'nama');
				foreach($arraymap as $i => $val){
					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
				}
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	public function actionSetDropdownProfilKayu(){
        if(\Yii::$app->request->isAjax){
			$jenis_produk = Yii::$app->request->post('jenis_produk');
            $mod = \app\models\MProfilKayu::find()->where(['active'=>true,'jenis_produk'=>$jenis_produk])->all();
			$html = '';
			if(!empty($mod)){
				$arraymap = \yii\helpers\ArrayHelper::map($mod, 'nama', 'nama');
				foreach($arraymap as $i => $val){
					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
				}
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	public function actionSetDropdownKondisiKayu(){
        if(\Yii::$app->request->isAjax){
			$jenis_produk = Yii::$app->request->post('jenis_produk');
            $mod = \app\models\MKondisiKayu::find()->where(['active'=>true,'jenis_produk'=>$jenis_produk])->all();
			$html = '';
			if(!empty($mod)){
				$arraymap = \yii\helpers\ArrayHelper::map($mod, 'nama', 'nama');
				foreach($arraymap as $i => $val){
					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
				}
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	public function actionSetKodeNamaProduk(){
        if(\Yii::$app->request->isAjax){
			$jenis_produk = Yii::$app->request->post('jenis_produk');
			$jenis_kayu = Yii::$app->request->post('jenis_kayu');
			$grade = Yii::$app->request->post('grade');
			$glue = Yii::$app->request->post('glue');
			$profil_kayu = Yii::$app->request->post('profil_kayu');
			$kondisi_kayu = Yii::$app->request->post('kondisi_kayu');
			$t = Yii::$app->request->post('t');
			$l = Yii::$app->request->post('l');
			$p = Yii::$app->request->post('p');
			$data['produk_kode'] = "";
			$data['produk_nama'] = "";
			
			$kode_jenis_produk = \app\models\MDefaultValue::find()->where(['active'=>true,'type'=>"jenis-produk",'value'=>$jenis_produk])->one();
			if(!empty($kode_jenis_produk)){
				$kode_jenis_produk = $kode_jenis_produk->name_en;
			}
			$kode_jenis_kayu = \app\models\MJenisKayu::find()->where(['active'=>true,'nama'=>$jenis_kayu])->one();
			if(!empty($kode_jenis_kayu)){
				$kode_jenis_kayu = $kode_jenis_kayu->kode;
			}
			$kode_grade = \app\models\MGrade::find()->where(['active'=>true,'nama'=>$grade])->one();
			if(!empty($kode_grade)){
				$kode_grade = $kode_grade->kode;
			}
			$kode_glue = \app\models\MGlue::find()->where(['active'=>true,'nama'=>$glue])->one();
			if(!empty($kode_glue)){
				$kode_glue = $kode_glue->kode;
			}
			$kode_profil_kayu = \app\models\MProfilKayu::find()->where(['active'=>true,'nama'=>$profil_kayu])->one();
			if(!empty($kode_profil_kayu)){
				$kode_profil_kayu = $kode_profil_kayu->kode;
			}
			$kode_kondisi_kayu = \app\models\MKondisiKayu::find()->where(['active'=>true,'nama'=>$kondisi_kayu])->one();
			if(!empty($kode_kondisi_kayu)){
				$kode_kondisi_kayu = $kode_kondisi_kayu->kode;
			}
			
			switch ($jenis_produk){
				case "Plywood": // CPWDFM/A2/01/11.512202440
					$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_glue."/".$t.$l.$p;
					$data['produk_nama'] = str_replace(' ', '',$jenis_produk."/".$jenis_kayu."/".$grade."/".$glue."/".$t.$l.$p);
				break;
				case "Sawntimber": // CSTMBKR/A/01/3015516
					$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_kondisi_kayu."/".$t.$l.$p;
					$data['produk_nama'] = str_replace(' ', '',$jenis_kayu."/".$grade."/".$kondisi_kayu."/".$t.$l.$p);
				break;
				case "Moulding": // CMLDBKR/A/01/2514516
					$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_profil_kayu."/".$t.$l.$p;
					$data['produk_nama'] = str_replace(' ', '',$jenis_kayu."/".$grade."/".$profil_kayu."/".$t.$l.$p);
				break;
				case "Veneer": // CVNR/A/0,512202440
					$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$t.$l.$p;
					$data['produk_nama'] = str_replace(' ', '',$grade."/".$t.$l.$p);
				break;
				case "Lamineboard": //CLBDMM/A2/01/11512202440
					$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_glue."/".$t.$l.$p;
					$data['produk_nama'] = str_replace(' ', '',$jenis_produk."/".$jenis_kayu."/".$grade."/".$glue."/".$t.$l.$p);
				break;
				case "Platform": //CPMFMM/A2/01/11512202440
					$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_glue."/".$t.$l.$p;
					$data['produk_nama'] = str_replace(' ', '',$jenis_produk."/".$jenis_kayu."/".$grade."/".$glue."/".$t.$l.$p);
				break;
			}
			return $this->asJson($data);
		}
    }
	
	public function actionMasterOnModal($disableAction=null,$tr_seq=null){
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('masterOnTable',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq]);
		}
	}
	public function actionFindProduk(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT * FROM m_brg_produk 
					WHERE produk_kode ilike '%{$term}%' AND active IS TRUE
					ORDER BY produk_kode";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'cust_id', 'cust_an_nama');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['produk_id'], 'text'=>$val['produk_kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
    
    public function actionUpdateStatus($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MBrgProduk::findOne($id);
			$transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                if($model->active==true){
                    $stat = false;
                }else{
                    $stat = true;
                }
                $model->active = $stat;
                if($model->validate()){
                    $success_1 = $model->save();
                }else{
                    $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                }
                if ($success_1) {
                    $transaction->commit();
                    $data['status'] = true;
                    $data['message'] = Yii::t('app', 'Data Produk Berhasil Diupdate');
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
	}
	
}
