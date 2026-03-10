<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\TLogKontrak;

class TerimaloglistController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TLoglist();
		$model->loglist_kode = 'LGL';
        $model->tanggal = date('d/m/Y');
        $model->model_ukuran_loglist = 0;
		$model->area_pembelian = 'Luar Jawa';
		$modDetail = [];
		$modDkg = [];
		
		if(isset($_GET['loglist_id'])){
            $model = \app\models\TLoglist::findOne($_GET['loglist_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$model->loglist_id])->all();
			$modDkg = \app\models\TDkg::find()->where(['loglist_id'=>$model->loglist_id])->all();
			$model->kode_po = $model->logKontrak->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($model->logKontrak->tanggal_po);
			$model->nomor_kontrak = $model->logKontrak->nomor;
            if($model->model_ukuran_loglist == "2 Diameter"){
                $model->model_ukuran_loglist = 0;
            }else if($model->model_ukuran_loglist == "4 Diameter"){
                $model->model_ukuran_loglist = 1;
            }
        }
		
		if( Yii::$app->request->post('TLoglist') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $success_2 = true;
                $success_3 = true;
                $model->load(\Yii::$app->request->post());
				$dkg = [];
				foreach($_POST['TLoglist'] as $postloglist){
					if(is_array($postloglist)){
						$dkg[] = $postloglist['grader_id'];
					}
				}
				if(!empty($dkg)){
					$model->grader_id = "-";
				}
				$model->area_pembelian = $_POST['TLoglist']['area_pembelian'];
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
//                        if( (isset($_POST['TLoglistDetail'])) && (count($_POST['TLoglistDetail'])>0) ){
//                            foreach($_POST['TLoglistDetail'] as $i => $detail){
//                                $modDetail = new \app\models\TLoglistDetail();
//                                $modDetail->attributes = $detail;
//                                $modDetail->loglist_id = $model->loglist_id;
//                                if($modDetail->validate()){
//                                    if($modDetail->save()){
//                                        $success_2 &= true;
//                                    }else{
//                                        $success_2 &= false;
//                                    }
//                                }
//                            }
//                        }else{
//                            $success_2 = false;
//                            Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
//                        }
						if(!empty($dkg)){
                            if(isset($_GET['edit'])){
                                
                            }
							foreach($dkg as $dkg_id){
								$modDkg = \app\models\TDkg::findOne($dkg_id);
								$modDkg->loglist_id = $model->loglist_id;
								if($modDkg->validate()){
                                    if($modDkg->save()){
                                        $success_2 &= true;
                                    }else{
                                        $success_2 &= false;
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
//				exit;
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Loglist Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'loglist_id'=>$model->loglist_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail,'modDkg'=>$modDkg]);
	}
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
			$loglist_id = Yii::$app->request->post('loglist_id');
			$add = Yii::$app->request->post('loglist_id');
			$ukuran = Yii::$app->request->post('ukuran'); $data['html'] = "";
            $modDetail = new \app\models\TLoglistDetail();
			$last_tr = []; parse_str(\Yii::$app->request->post('last_tr'),$last_tr);
			if(!empty($last_tr)){
                if(!empty($last_tr['diameter_ujung'])){
                    foreach($last_tr['TLoglistDetail'] as $qwe){
                        $last_tr = $qwe;
                    }
                    $modDetail->attributes = $last_tr;
                    $modDetail->nomor_grd = "";
                    $modDetail->nomor_produksi = "";
                    $modDetail->nomor_batang = "";
                    $modDetail->diameter_rata = ($last_tr['diameter_ujung']+$last_tr['diameter_pangkal'])/2;
                }
			}
			$modDetail->loglist_id = $loglist_id;
            if($ukuran=="2 Diameter"){
                $data['html'] .= $this->renderPartial('_item',['modDetail'=>$modDetail,'last_tr'=>$last_tr,'edit'=>'0']);
            }else{
                $data['html'] .= $this->renderPartial('_item4D',['modDetail'=>$modDetail,'last_tr'=>$last_tr,'edit'=>'0']);
            }
//            $data['html'] = $this->renderPartial('_item',['modDetail'=>$modDetail,'last_tr'=>$last_tr,'edit'=>'0']);
            return $this->asJson($data);
        }
    }
	
	public function actionSaveitem(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
			$form_params = []; parse_str(\Yii::$app->request->post('formData'),$form_params);
			if( isset($form_params['TLoglistDetail']) ){
				$transaction = \Yii::$app->db->beginTransaction();
				try {
					$success_1 = false; // t_loglist_detail
					$post = $form_params['TLoglistDetail'];
					if(count($post)>0){
						foreach($post as $peng){ $post = $peng; }
						$mod = new \app\models\TLoglistDetail();
						if(!empty($post['loglist_detail_id'])){
							$mod = \app\models\TLoglistDetail::findOne($post['loglist_detail_id']);
						}
						$mod->attributes = $post;
						$mod->diameter_ujung = (isset($post['diameter_ujung'])?$post['diameter_ujung']: ($post['diameter_ujung1']+$post['diameter_ujung2'])/2 );
						$mod->diameter_pangkal = (isset($post['diameter_pangkal'])?$post['diameter_pangkal']: ($post['diameter_pangkal1']+$post['diameter_pangkal2'])/2 );
						if($mod->validate()){
							if($mod->save()){
								$success_1 = true;
							}
						}else{
							$success_1 = false;
							$data['message_validate']=\yii\widgets\ActiveForm::validate($mod); 
						}
					}

	                // echo "<pre>";
					// print_r($post);
					// exit;

					if ($success_1) {
						$transaction->commit();
						$data = $mod->attributes;
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
			
        }
    }
	
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $edit = Yii::$app->request->post('edit');
            $ukuran = Yii::$app->request->post('ukuran');
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($loglist_id)){
                $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id])->orderBy(['created_at'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    foreach($modDetail as $i => $detail){
                        if($ukuran=="2 Diameter"){
                            $data['html'] .= $this->renderPartial('_item',['modDetail'=>$detail,'edit'=>$edit]);
                        }else{
                            $data['html'] .= $this->renderPartial('_item4D',['modDetail'=>$detail,'edit'=>$edit]);
                        }
                    }
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDeleteItem($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TLoglistDetail::findOne($id);
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
	
	function actionSetDropdownGrader(){
		if(\Yii::$app->request->isAjax){
			$selected_items = Yii::$app->request->post('selected_items');
            if(!empty($selected_items)){
                $selected_items = implode(', ', $selected_items);
            }
			$query = "
                SELECT * FROM m_graderlog
                WHERE m_graderlog.active IS TRUE
                    ".(($selected_items!='')?'AND graderlog_id NOT IN ('.$selected_items.')':'')." 
                ORDER BY m_graderlog.graderlog_nm ASC
            ";
            $mod = Yii::$app->db->createCommand($query)->queryAll();
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'graderlog_id', 'graderlog_nm');
			$html = \yii\bootstrap\Html::tag('option');
			foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
			}
			$data['html'] = $html;
			return $this->asJson($data);
		}
	}
	
	public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TLoglist::tableName();
				$param['pk']= \app\models\TLoglist::primaryKey()[0];
				$param['column'] = ['loglist_id','loglist_kode','t_pengajuan_pembelianlog.kode as kode_keputusan','t_log_kontrak.kode' ,'t_log_kontrak.nomor',['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],'tongkang',$param['table'].'.lokasi_muat'];
				$param['join'] = ['JOIN t_log_kontrak ON t_log_kontrak.log_kontrak_id = '.$param['table'].'.log_kontrak_id
								   JOIN t_pengajuan_pembelianlog ON t_pengajuan_pembelianlog.pengajuan_pembelianlog_id = '.$param['table'].'.pengajuan_pembelianlog_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionRepeaterSetDropdown(){
        if(\Yii::$app->request->isAjax){
			$list = Yii::$app->request->post('list');
            $html = '';
            $data['habis'] = false;
            if(!empty($list)){
                $params = "";
                foreach($list as $i => $val){
                    $params .= "'".$val."'";
                    if(count($list)>$i+1){
                        $params .= ",";
                    }
                }
                $mod = [];
				$mod = \app\models\TDkg::find()
						->andWhere("status = '".\app\models\TDkg::AKTIF_DINAS."'")
						->andWhere('dkg_id NOT IN ('.$params.')')
						->orderBy(['created_at'=>SORT_DESC])->all();
				$arraymap = [];
				if(count($mod)){
					foreach($mod as $i => $dkg){
						$arraymap[$dkg->dkg_id] = $dkg->graderlog->graderlog_nm;
					}
				}
                foreach($arraymap as $i => $val){
                    $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                }
            }
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	
	public function actionSetKeputusan($pengajuan_pembelianlog_id){
		if(\Yii::$app->request->isAjax){
			$data = [];
			if(!empty($pengajuan_pembelianlog_id)){
				$model = \app\models\TPengajuanPembelianlog::findOne($pengajuan_pembelianlog_id);
				$modKontrak = \app\models\TLogKontrak::findOne($model->log_kontrak_id);
				$modKontrak->tanggal_po = \app\components\DeltaFormatter::formatDateTimeForUser2($modKontrak->tanggal_po);
				if(!empty($model)){
					$data['model'] = $model->attributes;
				}
				if(!empty($modKontrak)){
					$data['modKontrak'] = $modKontrak->attributes;
				}
			}
			return $this->asJson($data);
		}
	}
    
	public function actionUpdateModelLoglist(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = \Yii::$app->request->post("loglist_id");
            $modeluk = \Yii::$app->request->post("modeluk");
			$data = false;
			if(!empty($loglist_id)){
                $model = \app\models\TLoglist::findOne($loglist_id);
                if(!empty($model)){
                    $model->model_ukuran_loglist = $modeluk;
                    $data = $model->save();
                }
			}
			return $this->asJson($data);
		}
	}
}
