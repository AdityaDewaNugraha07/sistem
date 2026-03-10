<?php

//sementara diubah namespace nya ke asset biar gak dikasih warning sama editor
//namespace app\modules\marketing\controllers;
namespace app\assets\modules\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class CustomerController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-customer'){
			$param['table'] = \app\models\MCustomer::tableName();
			$param['pk']= \app\models\MCustomer::primaryKey()[0];
			$param['column'] = ['cust_id','cust_kode','cust_an_nama','cust_pr_nama','cust_an_alamat',$param['table'].'.active'];
			$param['where'] = "cust_tipe_penjualan = 'lokal'";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index');
	}
	
	public function actionCreate(){
		if(\Yii::$app->request->isAjax){
			$model = new \app\models\MCustomer();
            $modCustTop = new \app\models\MCustTop();
			$model->active = true;
			$model->cust_tipe_penjualan = \app\components\Params::DEFAULT_DESTINASI_PENJUALAN;
			$model->cust_tanggal_join = date('d/m/Y');
			$model->cust_an_tgllahir = date('d/m/Y');
			$model->cust_is_pkp = 0;
			if( Yii::$app->request->post('MCustomer')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = true;
                    $model = new \app\models\MCustomer();
                    $model->load(\Yii::$app->request->post());
                    $model->cust_tanggal_join = (!empty($model->cust_tanggal_join)?\app\components\DeltaFormatter::formatDateTimeForDb($model->cust_tanggal_join):'');
                    $model->file1 = \yii\web\UploadedFile::getInstance($model, 'cust_file_ktp');
                    $model->file2 = \yii\web\UploadedFile::getInstance($model, 'cust_file_npwp');
                    $model->file3 = \yii\web\UploadedFile::getInstance($model, 'cust_file_photo');
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                            if(isset($_POST['MCustTop'])){
                                foreach($_POST['MCustTop'] as $i => $custtop){
                                    $modCustTop = new \app\models\MCustTop();
                                    $modCustTop->attributes = $custtop;
                                    $modCustTop->cust_id = $model->cust_id;
                                    $modCustTop->active = TRUE;
                                    if($modCustTop->validate()){
                                        if($modCustTop->save()){
                                            $success_2 &= TRUE;
                                        }else{
                                            $success_2 &= FALSE;
                                        }
                                    }else{
                                        $data['message']= Yii::t('app', 'Data TOP Gagal di simpan');; 
                                    }
                                }
                            }
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1 && $success_2) {
                        if(!empty($model->file1)){
                            $randomstring_ktp = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/mkt/customer';
                            if(!is_dir($dir_path)){
                                mkdir(Yii::$app->basePath.'/web/uploads/mkt');
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-ktp-'.$randomstring_ktp.'.' . $model->file1->extension;
                            $model->file1->saveAs($file_path,false);
                            $model->cust_file_ktp = date('Ymd_His').'-ktp-'.$randomstring_ktp.'.' .$model->file1->extension;
                        }
                        if(!empty($model->file2)){
                            $randomstring_npwp = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/mkt/customer';
                            if(!is_dir($dir_path)){
                                mkdir(Yii::$app->basePath.'/web/uploads/mkt');
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-npwp-'.$randomstring_npwp.'.'  . $model->file2->extension;
                            $model->file2->saveAs($file_path,false);
                            $model->cust_file_npwp = date('Ymd_His').'-npwp-'.$randomstring_npwp.'.' .$model->file2->extension;
                        }
                        if(!empty($model->file3)){
                            $randomstring_photo = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/mkt/customer';
                            if(!is_dir($dir_path)){
                                mkdir(Yii::$app->basePath.'/web/uploads/mkt');
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-photo-'.$randomstring_photo.'.'  . $model->file3->extension;
                            $model->file3->saveAs($file_path,false);
                            $model->cust_file_photo = date('Ymd_His').'-photo-'.$randomstring_photo.'.' .$model->file3->extension;
                        }
                        if($model->update() !== false){
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                        }
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
			return $this->renderAjax('create',['model'=>$model,'modCustTop'=>$modCustTop]);
		}
	}

    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MCustomer::findOne($id);
            $modCustTop = \app\models\MCustTop::find()->where(['active'=>TRUE,'cust_id'=>$model->cust_id])->all();
			return $this->renderAjax('info',['model'=>$model,'modCustTop'=>$modCustTop]);
		}
	}
	
	public function actionEdit($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\MCustomer::findOne($id);
			$modCustTop = new \app\models\MCustTop();
			$modCustTops = \app\models\MCustTop::find()->where(['active'=>TRUE,'cust_id'=>$model->cust_id])->all();
			$model->cust_is_pkp = ($model->cust_is_pkp)?1:0;
			$model->cust_tanggal_join = (!empty($model->cust_tanggal_join)?\app\components\DeltaFormatter::formatDateTimeForUser2($model->cust_tanggal_join):'');
			$model->cust_max_plafond = (!empty($model->cust_max_plafond)?\app\components\DeltaFormatter::formatNumberForUser($model->cust_max_plafond):0);
			if( Yii::$app->request->post('MCustomer')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = true;
                    $cust_file_ktp_old = $model->cust_file_ktp;
                    $cust_file_npwp_old = $model->cust_file_npwp;
                    $cust_file_photo_old = $model->cust_file_photo;
                    $model->load(\Yii::$app->request->post());
                    $model->cust_tanggal_join = (!empty($model->cust_tanggal_join)?\app\components\DeltaFormatter::formatDateTimeForDb($model->cust_tanggal_join):'');
                    $model->file1 = \yii\web\UploadedFile::getInstance($model, 'cust_file_ktp');
                    $model->file2 = \yii\web\UploadedFile::getInstance($model, 'cust_file_npwp');
                    $model->file3 = \yii\web\UploadedFile::getInstance($model, 'cust_file_photo');
                    $model->cust_file_ktp = !empty($model->cust_file_ktp)?$model->cust_file_ktp:$cust_file_ktp_old;
                    $model->cust_file_npwp = !empty($model->cust_file_npwp)?$model->cust_file_npwp:$cust_file_npwp_old;
                    $model->cust_file_photo = !empty($model->cust_file_photo)?$model->cust_file_photo:$cust_file_photo_old;
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                            if(isset($_POST['MCustTop'])){
                                $modCustTopCurr = \app\models\MCustTop::find()->where(['cust_id'=>$model->cust_id])->all();
                                if(count($modCustTopCurr)>0){
                                    \app\models\MCustTop::deleteAll(['cust_id'=>$model->cust_id]);
                                }
                                foreach($_POST['MCustTop'] as $i => $custtop){
                                    $modCustTop = new \app\models\MCustTop();
                                    $modCustTop->attributes = $custtop;
                                    $modCustTop->cust_id = $model->cust_id;
                                    $modCustTop->active = TRUE;
                                    if($modCustTop->validate()){
                                        if($modCustTop->save()){
                                            $success_2 &= TRUE;
                                        }else{
                                            $success_2 &= FALSE;
                                        }
                                    }else{
                                        $success_2 &= FALSE;
                                        $data['message']= Yii::t('app', 'Data TOP Gagal di update');
                                    }
                                }
                            }
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1 && $success_2) {
                        if(!empty($model->file1)){
                            $randomstring_ktp = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/mkt/customer';
                            if(!is_dir($dir_path)){
                                mkdir(Yii::$app->basePath.'/web/uploads/mkt');
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-ktp-'.$randomstring_ktp.'.'  . $model->file1->extension;
                            $model->file1->saveAs($file_path,false);
                            $model->cust_file_ktp = date('Ymd_His').'-ktp-'.$randomstring_ktp.'.' .$model->file1->extension;
                            if($cust_file_ktp_old != null){
                                if (file_exists(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_ktp_old)) {
                                    unlink(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_ktp_old);
                                }
                            }
                        }
                        if(!empty($model->file2)){
                            $randomstring_npwp = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/mkt/customer';
                            if(!is_dir($dir_path)){
                                mkdir(Yii::$app->basePath.'/web/uploads/mkt');
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-npwp-'.$randomstring_npwp.'.'  . $model->file2->extension;
                            $model->file2->saveAs($file_path,false);
                            $model->cust_file_npwp = date('Ymd_His').'-npwp-'.$randomstring_npwp.'.' .$model->file2->extension;
                            if($cust_file_npwp_old != null){
                                if (file_exists(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_npwp_old)) {
                                    unlink(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_npwp_old);
                                }
                            }
                        }
                        if(!empty($model->file3)){
                            $randomstring_photo = Yii::$app->getSecurity()->generateRandomString(4);
                            $dir_path = Yii::$app->basePath.'/web/uploads/mkt/customer';
                            if(!is_dir($dir_path)){
                                mkdir(Yii::$app->basePath.'/web/uploads/mkt');
                                mkdir($dir_path);
                            }
                            $file_path = $dir_path.'/'.date('Ymd_His').'-photo-'.$randomstring_photo.'.'  . $model->file3->extension;
                            $model->file3->saveAs($file_path,false);
                            $model->cust_file_photo = date('Ymd_His').'-photo-'.$randomstring_photo.'.' .$model->file3->extension;
                            if($cust_file_photo_old != null){
                                if (file_exists(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_photo_old)) {
                                    unlink(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_photo_old);
                                }
                            }
                        }
                        if($model->update() !== false){
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', 'Data Customer Berhasil Diupdate');
                        }
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
			return $this->renderAjax('edit',['model'=>$model,'modCustTop'=>$modCustTop,'modCustTops'=>$modCustTops]);
		}
	}
	
	public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MCustomer::findOne($id);
			$modCustTop = \app\models\MCustTop::find()->where(['cust_id'=>$model->cust_id])->all();
            $cust_file_ktp_old = $model->cust_file_ktp;
            $cust_file_npwp_old = $model->cust_file_npwp;
            $cust_file_photo_old = $model->cust_file_photo;
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if(count($modCustTop)>0){
                        \app\models\MCustTop::deleteAll(['cust_id'=>$model->cust_id]);
                    }
                    if($model->delete()){
                        if($cust_file_ktp_old != null){
                            if (file_exists(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_ktp_old)) {
                                unlink(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_ktp_old);
                            }
                        }
                        if($cust_file_npwp_old != null){
                            if (file_exists(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_npwp_old)) {
                                unlink(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_npwp_old);
                            }
                        }
                        if($cust_file_photo_old != null){
                            if (file_exists(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_photo_old)) {
                                unlink(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_photo_old);
                            }
                        }
                        $success_1 = true;
                    }else{
                        $data['message'] = Yii::t('app', 'Data Customer Gagal dihapus');
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Customer Berhasil Dihapus');
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid]);
		}
	}
    
    public function actionSetDropdownJenisProdukTOP(){
        if(\Yii::$app->request->isAjax){
			$list_jenis = Yii::$app->request->post('list_jenis');
            $html = '';
            $data['habis'] = false;
            if(!empty($list_jenis)){
                $params = "";
                foreach($list_jenis as $i => $val){
                    $params .= "'".$val."'";
                    if(count($list_jenis)>$i+1){
                        $params .= ",";
                    }
                }
                $mod = [];
                $mod = \app\models\MDefaultValue::find()->where(['active'=>true,'type'=>'jenis-produk'])->andWhere('value NOT IN ('.$params.')')->all();
                $arraymap = \yii\helpers\ArrayHelper::map($mod, 'value', 'name');
                foreach($arraymap as $i => $val){
                    $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                }
            }
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	
	public function actionMasterOnModal(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-customer'){
				$param['table'] = \app\models\MCustomer::tableName();
				$param['pk']= $param['table'].".".\app\models\MCustomer::primaryKey()[0];
				$param['column'] = [$param['table'].'.cust_id','cust_kode','cust_an_nama','cust_pr_nama','cust_an_alamat','cust_max_plafond',
									'COALESCE(SUM(t_nota_penjualan.total_bayar)-COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id),0),0) AS piutang'];
				$param['join'] = ['LEFT JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = '.$param['table'].'.cust_id'];
				$param['group'] = "GROUP BY ".$param['table'].".cust_id, cust_kode, cust_an_nama, cust_pr_nama, cust_max_plafond";
				$param['where'] = "active IS TRUE";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('masterOnTable');
		}
	}
	public function actionFindCustomer(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT * FROM m_customer 
					WHERE cust_an_nama ilike '%{$term}%' AND active IS TRUE
					ORDER BY cust_an_nama";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'cust_id', 'cust_an_nama');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['cust_id'], 'text'=>$val['cust_an_nama']." ".(!empty($val['cust_pr_nama'])?"- ".$val['cust_pr_nama']:"")];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	
}
