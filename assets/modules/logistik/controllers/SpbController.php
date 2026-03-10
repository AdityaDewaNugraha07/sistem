<?php

namespace app\modules\logistik\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class SpbController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TSpb(['scenario'=> \app\models\TSpb::SCENARIO_SPB_BARU]);
        $modDetail = new \app\models\TSpbDetail();
        $model->departement_id = Yii::$app->user->identity->pegawai->departement_id;
        $model->spb_tipe = 'Biasa';
        $model->spb_kode = 'Auto Generate';
        $model->spb_jenis = 'Barang';
        $model->spb_tanggal = date('d/m/Y');
        $model->spb_diminta = Yii::$app->user->identity->pegawai_id;
        $spb_exist = \app\models\TSpb::find()->where(['departement_id'=>Yii::$app->user->identity->pegawai->departement_id])->all();
        
        if(isset($_GET['spb_id'])){
            $model = \app\models\TSpb::findOne($_GET['spb_id']);
            $model->spb_tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->spb_tanggal);
            $modDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$model->spb_id])->all();
        }
        
        if( Yii::$app->request->post('TSpb')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_spb
                $success_2 = true; // t_spb_detail
				$success_3 = false; // t_approval
                $model->load(\Yii::$app->request->post());
				$model->spb_status = 'BELUM DIPROSES';
				$model->approve_status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
                if(!isset($_GET['edit'])){
                    // exec ini jika proses save
                    $model->spb_kode = \app\components\DeltaGenerator::kodeSpb();
					// exec ini jika proses save
                }
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        if( (isset($_POST['TSpbDetail'])) && (count($_POST['TSpbDetail'])>0) ){
                            if( (isset($_GET['edit'])) && (isset($_GET['spb_id']))){
                                // exec ini jika proses edit
                                $modDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$_GET['spb_id']])->all();
                                if(count($modDetail)>0){
                                    \app\models\TSpbDetail::deleteAll(['spb_id'=>$_GET['spb_id']]);
                                }
								// exec ini jika proses edit
                            }
                            foreach($_POST['TSpbDetail'] as $i => $detail){
                                $modDetail = new \app\models\TSpbDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->spb_id = $model->spb_id;
                                if($modDetail->validate()){
                                    if($modDetail->save()){
                                        $success_2 &= $success_2;
                                    }else{
                                        $success_2 = false;
                                    }
                                }else{
									$success_2 = false;
                                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                                }
                            }
                        }else{
                            $success_2 = false;
                            Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                        }
						
						// START Create Approval
						$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->spb_kode])->all();
						if(count($modelApproval)>0){ // edit mode
							if(\app\models\TApproval::deleteAll(['reff_no'=>$model->spb_kode])){
								$success_3 = $this->saveApproval($model);
							}
						}else{ // insert mode
							$success_3 = $this->saveApproval($model);
						}
						// END Create Approval
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
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data SPB Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'spb_id'=>$model->spb_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail,'spb_exist'=>$spb_exist]);
	}
	
	public function saveApproval($model){
		$success = false;
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->spb_disetujui;
		$modelApproval->reff_no = $model->spb_kode;
		$modelApproval->tanggal_berkas = $model->spb_tanggal;
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success = $modelApproval->createApproval();
		if($model->spb_mengetahui){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->spb_mengetahui;
			$modelApproval->reff_no = $model->spb_kode;
			$modelApproval->tanggal_berkas = $model->spb_tanggal;
			$modelApproval->level = 1;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		return $success;
	}
    
    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new \app\models\TSpbDetail();
            $data['item'] = $this->renderPartial('_addItem',['modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }
	
    public function actionFindBhpActive(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(\Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_LOGISTIC){
				$active = "AND m_brg_bhp.active IS TRUE";
			}
			if(!empty($term)){
				$query = "
					SELECT * FROM m_brg_bhp
					WHERE ".(!empty($term)?"bhp_nm ILIKE '%".$term."%'":'')." AND m_brg_bhp.active IS TRUE 
					ORDER BY m_brg_bhp.bhp_id ASC
					;
				";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'bhp_id', 'bhp_nm');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['bhp_id'], 'text'=>$val['bhp_nm']];
					}
				}
			}
            return $this->asJson($data);
        }
    }
    public function actionFindBhp(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(\Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_LOGISTIC){
				$active = "AND m_brg_bhp.active IS TRUE";
			}
			if(!empty($term)){
				$query = "
					SELECT * FROM m_brg_bhp
					WHERE ".(!empty($term)?"bhp_nm ILIKE '%".$term."%'":'')." 
							{$active}
					ORDER BY m_brg_bhp.bhp_id ASC
					;
				";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'bhp_id', 'bhp_nm');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['bhp_id'], 'text'=>$val['bhp_nm']];
					}
				}
			}
            return $this->asJson($data);
        }
    }
    
    function actionSetDropdownBhp(){
		if(\Yii::$app->request->isAjax){
			$selected_items = Yii::$app->request->post('selected_items');
            if(!empty($selected_items)){
                $selected_items = implode(', ', $selected_items);
            }
			$query = "
                SELECT * FROM m_brg_bhp
                WHERE m_brg_bhp.active IS TRUE
                    ".(($selected_items!='')?'AND bhp_id NOT IN ('.$selected_items.')':'')." 
                ORDER BY m_brg_bhp.bhp_id ASC
				;
            ";
            $mod = Yii::$app->db->createCommand($query)->queryAll();
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'bhp_id', 'bhp_nm');
			$html = \yii\bootstrap\Html::tag('option');
			foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
			}
			$data['html'] = $html;
			return $this->asJson($data);
		}
	}
	
    function actionSetItem(){
		if(\Yii::$app->request->isAjax){
            $bhp_id = Yii::$app->request->post('bhp_id');
            if(!empty($bhp_id)){
                $data = \app\models\MBrgBhp::findOne($bhp_id);
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }
    
    function actionGetAllItem(){
		if(\Yii::$app->request->isAjax){
            $spb_id = Yii::$app->request->post('spb_id');
            $editable = Yii::$app->request->post('editable');
            $data = [];
            if(!empty($spb_id)){
                $modDetails = \app\models\TSpbDetail::find()->where(['spb_id'=>$spb_id])->all();
            }else{
                $modDetails = [];
            }
            $data['html'] = '';
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
                    if($editable == 'true'){
                        $modDetail = \app\models\TSpbDetail::findOne($detail->spbd_id);
                        $modDetail->spbd_tgl_dipakai = !empty($modDetail->spbd_tgl_dipakai)?\app\components\DeltaFormatter::formatDateTimeForUser2($modDetail->spbd_tgl_dipakai):'';
                        $modDetail->spbd_satuan = $modDetail->bhp->bhp_satuan;
                        $data['html'] .= $this->renderPartial('_addItem',['modDetail'=>$modDetail,'editable'=>$editable,'detail'=>$detail]);
						$value = $modDetail->attributes;
						$value['bhp_nama'] = $modDetail->bhp->bhp_nm;
                        $data['value'][$i] = $value;
                    }else{
                        $data['html'] .= $this->renderPartial('_addItemAfterSave',['detail'=>$detail,'i'=>$i,'editable'=>$editable,'detail'=>$detail]);
                    }
                    
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionDaftarSpb_old(){
        if(\Yii::$app->request->isAjax){
            $departement_id = Yii::$app->request->get('dept_id');
            $modDetail = \app\models\TSpb::find()->where(['departement_id'=>$departement_id])->orderBy(['created_at'=>SORT_DESC])->all();
			$data = [];
			if( Yii::$app->request->get('refresh')){
				$bpb_id = Yii::$app->request->get('bpb_id');
				$modBpb = \app\models\TBpb::findOne($bpb_id);
				$modBpbs = \app\models\TBpb::find()->where(['spb_id'=>$modBpb->spb_id])->orderBy(['created_at'=>SORT_DESC])->all();
				$data = "";
				foreach($modBpbs as $i => $bpb){
					if($bpb->bpb_status == "BELUM DITERIMA"){
						$data .= "<a style='font-size:0.8em;' class='font-red-intense' data-bpb='$bpb->bpb_id' onclick='infoBpb(".$bpb->bpb_id.")'>".$bpb->bpb_kode." - ".$bpb->bpb_status."</a><br>";
					}else if($bpb->bpb_status == "SUDAH DITERIMA"){
						$data .= "<a style='font-size:0.8em;' class='font-green-meadow' data-bpb='$bpb->bpb_id' onclick='infoBpb(".$bpb->bpb_id.")'>".$bpb->bpb_kode." - ".$bpb->bpb_status."</a><br>";
					}
				}
				return $this->asJson($data);
			}
            return $this->renderAjax('daftarSpb',['modDetail'=>$modDetail]);
        }
    }
    public function actionDaftarSpb(){
        if(\Yii::$app->request->isAjax){
			$departement_id = Yii::$app->request->get('dept_id');
			if(\Yii::$app->request->get('dt')=='table-aftersave'){
				$param['table']= \app\models\TSpb::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TSpb::primaryKey()[0];
				$param['column'] = [ $param['table'].'.spb_id','spb_kode','spb_tanggal', 'pegawaidiminta.pegawai_nama AS diminta', 
									 'pegawaidisetujui.pegawai_nama AS disetujui', 
                                     "(SELECT status FROM t_approval WHERE t_approval.reff_no = t_spb.spb_kode AND t_approval.assigned_to = t_spb.spb_disetujui LIMIT 1 ) AS status_setuju", 
                                     "(SELECT updated_at FROM t_approval WHERE t_approval.reff_no = t_spb.spb_kode AND t_approval.assigned_to = t_spb.spb_disetujui LIMIT 1 ) AS update_setuju",
									 'pegawaimengetahui.pegawai_nama AS mengetahui', 
                                     "(SELECT status FROM t_approval WHERE t_approval.reff_no = t_spb.spb_kode AND t_approval.assigned_to = t_spb.spb_mengetahui LIMIT 1 ) AS status_mengetahui", 
                                     "(SELECT updated_at FROM t_approval WHERE t_approval.reff_no = t_spb.spb_kode AND t_approval.assigned_to = t_spb.spb_mengetahui LIMIT 1 ) AS update_mengetahui", 
									 'spb_status','approve_status',
									 '(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT bpb_id,bpb_kode,bpb_status FROM t_bpb WHERE spb_id=t_spb.spb_id GROUP BY 1,2,3 ) t) AS status_spb', ];
				$param['where'] = "t_spb.departement_id = ".$departement_id;
				$param['join'] = "JOIN m_pegawai AS pegawaidiminta ON pegawaidiminta.pegawai_id = t_spb.spb_diminta
								  JOIN m_pegawai AS pegawaidisetujui ON pegawaidisetujui.pegawai_id = t_spb.spb_disetujui
								  JOIN m_pegawai AS pegawaimengetahui ON pegawaimengetahui.pegawai_id = t_spb.spb_mengetahui";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarSpb',['departement_id'=>$departement_id]);
        }
    }
    
    public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpb::findOne($id);
            $modDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$id])->all();
            $pesan = "Apakah anda yakin akan membatalkan pengajuan SPB <b>".$model->spb_kode."</b> ?";
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if(count($modDetail)>0){
                        if(\app\models\TSpbDetail::deleteAll(['spb_id'=>$id])){
                            $success_2 = true;
                            if($model->delete()){
                                $success_1 = true;
                            }
                        }else{
                            $success_2 = false;
                        }
                    }else{
                        $success_2 = true;
                        if($model->delete()){
                            $success_1 = true;
                        }
                    }
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data pengajuan SPB berhasil Dihapus');
                        $data['callback'] = "resetForm()";
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'pesan'=>$pesan]);
		}
	}
	
	function actionInfoBpb($bpb_id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TBpb::findOne($bpb_id);
			$model->bpb_tgl_diterima = (!empty($model->bpb_tgl_diterima)?\app\components\DeltaFormatter::formatDateTimeForUser2($model->bpb_tgl_diterima):" - ");
			$model->bpb_diterima = (!empty($model->bpb_diterima)?$model->bpbDiterima->pegawai_nama:" - ");
			$modDetail = \app\models\TBpbDetail::find()->where(['bpb_id'=>$bpb_id])->all();
			$data = [];
			if( Yii::$app->request->get('refresh')){
				$data = $model;
				return $this->asJson($data);
			}
			return $this->renderAjax('infoBpb',['model'=>$model,'modDetail'=>$modDetail]);
		}
	}
	
	function actionTerimaBarang($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TBpb::findOne($id);
			$pesan = Yii::t('app', 'Apakan anda akan melakukan penerimaan barang ini?');
			$data = [];
			if( Yii::$app->request->post('deleteRecord')){
				$model->bpb_tgl_diterima = date('Y-m-d');
				$model->bpb_diterima = Yii::$app->user->identity->pegawai_id;
				$model->bpb_status = "SUDAH DITERIMA";
				if($model->validate()){
					if($model->save()){
						$data['status'] = true;
						$data['message'] = "Data Berhasil di simpan";
						$data['callback'] = "$('#modal-terima-bpb').modal('hide')";
					}else{
						$data['status'] = false;
						$data['message'] = "Gagal :(";
					}
				}else{
					$data['status'] = false;
				}
				return $this->asJson($data);
			}
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',[
				'id'=>$id,
				'pesan'=>$pesan,
				'actionname'=>'terimaBarang']);
		}
	}
	
	function actionEditSelectItem(){
		if(\Yii::$app->request->isAjax){
			$bhp_id = Yii::$app->request->post('bhp_id');
			$modBhp = \app\models\MBrgBhp::findOne($bhp_id);
			$data['dropdown'] = \yii\bootstrap\Html::dropDownList('TSpbDetail[ii][bhp_id]',null,[],['class'=>'form-control select2','onchange'=>'setItem(this)','prompt'=>'','style'=>'width:90%']);
			$data['modBhp'] = $modBhp->attributes;
			return $this->asJson($data);
		}
	}
	
	public function actionPrintout(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TSpb::findOne($_GET['id']);
		$modelModDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'Surat Permintaan Barang');
		if($caraprint == 'PRINT'){
			return $this->renderPartial('printout',['model'=>$model,'paramprint'=>$paramprint,'modelModDetail'=>$modelModDetail]);
		}
	}
	
	public function actionItemdipesan(){
        if(\Yii::$app->request->get('dt')=='table-item'){
			$param['table']= \app\models\TSpbDetail::tableName();
			$param['pk']= \app\models\TSpbDetail::primaryKey()[0];
			$param['column'] = ['spbd_id','spb_tanggal','spb_kode','m_brg_bhp.bhp_nm',
								'spbd_jml','spbd_jml','a.pegawai_nama AS diminta',
								'b.pegawai_nama AS disetujui','c.pegawai_nama AS diketahui','spb_status',
								'approve_status','spbd_ket'];
			$param['join']= ['JOIN t_spb ON t_spb.spb_id = '.$param['table'].'.spb_id
							  JOIN m_brg_bhp ON m_brg_bhp.bhp_id = '.$param['table'].'.bhp_id
							  JOIN m_pegawai AS a ON a.pegawai_id = t_spb.spb_diminta
							  JOIN m_pegawai AS b ON b.pegawai_id = t_spb.spb_disetujui
							  JOIN m_pegawai AS c ON c.pegawai_id = t_spb.spb_mengetahui'];
			$param['where']= "t_spb.departement_id = ".\Yii::$app->user->identity->pegawai->departement_id;
			$param['order']= "t_spb.created_at DESC";
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('itemdipesan');
	}
	public function actionItemdipesanPrint(){
		$this->layout = '@views/layouts/metronic/print';
		$sql = "SELECT spbd_id, spb_tanggal, spb_kode, m_brg_bhp.bhp_nm, spbd_jml, a.pegawai_nama AS diminta, b.pegawai_nama AS disetujui,
						c.pegawai_nama AS diketahui, spb_status, approve_status, spbd_ket
				FROM t_spb_detail 
				JOIN t_spb ON t_spb.spb_id = t_spb_detail.spb_id
				JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_spb_detail.bhp_id
				JOIN m_pegawai AS a ON a.pegawai_id = t_spb.spb_diminta
				JOIN m_pegawai AS b ON b.pegawai_id = t_spb.spb_disetujui
				JOIN m_pegawai AS c ON c.pegawai_id = t_spb.spb_mengetahui
				WHERE t_spb.departement_id = ".\Yii::$app->user->identity->pegawai->departement_id."
				ORDER BY t_spb.created_at DESC
				";
		$models = \Yii::$app->db->createCommand($sql)->queryAll();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'Semua Item yang pernah di pesan oleh bagian : IT');
		if($caraprint == 'PRINT'){
			return $this->renderPartial('printitemdipesan',['models'=>$models,'paramprint'=>$paramprint]);
		}else if($caraprint == 'EXCEL'){
			return $this->renderPartial('printitemdipesan',['models'=>$models,'paramprint'=>$paramprint]);
		}
	}
	
	function actionCheckBPB(){
		if(\Yii::$app->request->isAjax){
			$pegawai_id = Yii::$app->request->post('pegawai_id');
			$modBpb = \Yii::$app->db->createCommand("SELECT * FROM t_bpb WHERE spb_id IN( SELECT spb_id FROM t_spb WHERE spb_diminta = {$pegawai_id} ) AND bpb_diterima IS NULL")->queryAll();
			$data['msg']="";
			if(count($modBpb)>0){
				$data['msg']="Maaf anda tidak dapat membuat SPB baru, sebelum semua BPB anda Sudah Diterima!";
			}
			return $this->asJson($data);
		}
	}
}
