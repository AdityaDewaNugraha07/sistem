<?php

namespace app\modules\purchasing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PenerimaansppController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-list'){
			$param['table']= \app\models\TSpp::tableName();
			$param['pk']= \app\models\TSpp::primaryKey()[0];
			$param['column'] = ['spp_id','spp_kode','spp_nomor',['col_name'=>'spp_tanggal','formatter'=>'formatDateForUser2'],'departement_nama'];
            $param['join']= ['JOIN m_departement ON m_departement.departement_id = '.$param['table'].'.departement_id'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
        
		return $this->render('index');
	}
	
	public function actionSppmasuk(){
		$model = new \app\models\TSppDetail();
//		$model->tgl_awal = date('d/m/Y',strtotime("-1 day"));
		$model->tgl_awal = date('d/m/Y');
		$model->tgl_akhir = date('d/m/Y');
		$model->status = ['TO-DO','PARTIALLY'];
		return $this->render('sppmasuk',['model'=>$model]);
	}
	public function actionSppmasuk_developed(){
		$model = new \app\models\TSppDetail();
		$model->tgl_awal = date('d/m/Y',strtotime("-1 day"));
		$model->tgl_akhir = date('d/m/Y');
        if(\Yii::$app->request->post('dt')=='table-laporan'){
			if((\Yii::$app->request->post('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->post('laporan_params'),$form_params); 
				$model->attributes = $form_params['TSppDetail'];
				$model->tgl_awal = $form_params['TSppDetail']['tgl_awal'];
				$model->tgl_akhir = $form_params['TSppDetail']['tgl_akhir'];
				$model->spp_kode = $form_params['TSppDetail']['spp_kode'];
				$model->bhp_nm = $form_params['TSppDetail']['bhp_nm'];
				$model->suplier_id = $form_params['TSppDetail']['suplier_id'];
				
				$filterSppKode = (!empty($form_params['TSppDetail']['spp_kode'])?" AND spp_kode ILIKE '%".$form_params['TSppDetail']['spp_kode']."%' ":"");
				$filterBhpNm = (!empty($form_params['TSppDetail']['bhp_nm'])?" AND bhp_nm ILIKE '%".$form_params['TSppDetail']['bhp_nm']."%' ":"");
				$filterSuplierId = (!empty($form_params['TSppDetail']['suplier_id'])?" AND t_spp_detail.suplier_id = ".$form_params['TSppDetail']['suplier_id']." ":"");
				
				$param['table']= \app\models\TSppDetail::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TSppDetail::primaryKey()[0];
				$param['column'] = ['sppd_id','t_spp.spp_kode','t_spp.spp_tanggal','bhp_kode'];
				$param['join'] = ["JOIN t_spp ON t_spp.spp_id =  t_spp_detail.spp_id 
								   JOIN m_departement ON m_departement.departement_id = t_spp.departement_id
								   JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_spp_detail.bhp_id"];
				$param['where'] = "t_spp.spp_tanggal BETWEEN '".$form_params['TSppDetail']['tgl_awal']."' AND '".$form_params['TSppDetail']['tgl_akhir']."'
									".$filterSppKode." ".$filterBhpNm." ".$filterSuplierId;
				$param['order'] = "t_spp.created_at DESC, t_spp_detail.sppd_id ASC";
				
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('sppmasuk',['model'=>$model]);
	}
	
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
			$data['html'] = "<tr><td colspan='10' style='text-align: center;'>". Yii::t('app', 'No Data Available'). "</td></tr>";
			$form_params = [];
			parse_str($_POST['formdata'],$form_params);
			$filterSppKode = (!empty($form_params['TSppDetail']['spp_kode'])?" AND spp_kode ILIKE '%".$form_params['TSppDetail']['spp_kode']."%' ":"");
			$filterBhpNm = (!empty($form_params['TSppDetail']['bhp_nm'])?" AND bhp_nm ILIKE '%".$form_params['TSppDetail']['bhp_nm']."%' ":"");
			$filterSuplierId = (!empty($form_params['TSppDetail']['suplier_id'])?" AND t_spp_detail.suplier_id = ".$form_params['TSppDetail']['suplier_id']." ":"");
			$filterKeterangan = (!empty($form_params['TSppDetail']['sppd_ket'])?" AND t_spp_detail.sppd_ket ILIKE '%".$form_params['TSppDetail']['sppd_ket']."%' ":"");
			$filterStatus = "";
			if(!empty($form_params['TSppDetail']['status'])){
				foreach($form_params['TSppDetail']['status'] as $i => $status){
					if($status=="TO-DO"){
						$filterStatus[] = "(COALESCE(terimabhpd_qty, 0)) = 0 ";
					}else if($status=="PARTIALLY"){
						$filterStatus[] = "((COALESCE(terimabhpd_qty, 0)) < sppd_qty AND (COALESCE(terimabhpd_qty, 0)) != 0) ";
					}else if($status=="COMPLETE"){
						$filterStatus[] = "(COALESCE(terimabhpd_qty, 0)) >= sppd_qty ";
					}
				}
				$filterStatus = implode(" OR ", $filterStatus);
				$filterStatus = "AND (".$filterStatus.")";
			}
			$sql = "SELECT * FROM t_spp_detail
					JOIN t_spp ON t_spp.spp_id =  t_spp_detail.spp_id
					JOIN m_departement ON m_departement.departement_id = t_spp.departement_id
					JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_spp_detail.bhp_id
					WHERE t_spp.spp_tanggal BETWEEN '".$form_params['TSppDetail']['tgl_awal']."' AND '".$form_params['TSppDetail']['tgl_akhir']."' 
						".$filterSppKode." ".$filterBhpNm." ".$filterSuplierId." ".$filterKeterangan." 
					ORDER BY t_spp.created_at DESC, t_spp_detail.sppd_id ASC
					";
//			$select = "t_spp_detail.sppd_id, t_spp_detail.bhp_id, t_spp_detail.spp_id, t_spp.spp_kode, t_spp.spp_tanggal, 
//					   m_departement.departement_nama, m_brg_bhp.bhp_kode, t_spp_detail.sppd_qty, m_brg_bhp.bhp_satuan, 
//					   t_spp_detail.suplier_id, t_spp_detail.sppd_ket, map_spp_detail_reff.reff_no ";
//			$sql = "SELECT {$select} FROM t_spp_detail
//					JOIN t_spp ON t_spp.spp_id =  t_spp_detail.spp_id
//					JOIN m_departement ON m_departement.departement_id = t_spp.departement_id
//					JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_spp_detail.bhp_id
//					LEFT JOIN map_spp_detail_reff ON map_spp_detail_reff.sppd_id = t_spp_detail.sppd_id
//					LEFT JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhpd_id = map_spp_detail_reff.terima_bhpd_id
//					LEFT JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_terima_bhp_detail.terima_bhp_id
//					WHERE t_spp.spp_tanggal BETWEEN '".$form_params['TSppDetail']['tgl_awal']."' AND '".$form_params['TSppDetail']['tgl_akhir']."' 
//						".$filterSppKode." ".$filterBhpNm." ".$filterSuplierId." ".$filterStatus." 
//					ORDER BY t_spp.created_at DESC, t_spp_detail.sppd_id ASC
//					";
			$mods = \Yii::$app->db->createCommand($sql)->queryAll();
			if(count($mods)>0){
				$data['html'] = ""; $data['items'] = [];
				foreach($mods as $i => $detail){
//					$data['html'] .= $this->renderPartial('_item',['mods'=>$mods,'detail'=>$detail,'i'=>$i]);
					
					if(!empty($detail['bhp_id'])){
						$data['items'][$i] = $detail;
						$modSppDetail = \app\models\TSppDetail::findOne($detail['sppd_id']);
						$data['items'][$i]['spp_tanggal'] = \app\components\DeltaFormatter::formatDateTimeForUser2($data['items'][$i]['spp_tanggal']);
						$data['items'][$i]['bhp_nm2'] = $this->getBhp_nm($detail['bhp_nm']);
						$data['items'][$i]['qty_terbeli'] = $modSppDetail->QtyTerbeli['qty'];
						$data['items'][$i]['status_spp_detail'] = $modSppDetail->StatusSppDetail;
						$data['items'][$i]['html_suplier'] = "";
						
						$value_arr = [];
						if(empty($modSppDetail->spp->cancel_traksaksi_id)){
							if(!empty($detail['suplier_id'])){
								$modSupplier = \app\models\MSuplier::findOne($detail['suplier_id']);
								$value_arr[$modSupplier->suplier_id] = $modSupplier->suplier_nm." ".$modSupplier->suplier_almt;
							}
							if(strpos($data['items'][$i]['status_spp_detail'], 'COMPLETE')){
								$data['items'][$i]['html_suplier'] = \yii\bootstrap\Html::activeDropDownList($modSppDetail, 'suplier_id', $value_arr,['class'=>'form-control select2','style'=>'padding:3px; font-size:1.1rem;','prompt'=>'','disabled'=>TRUE]);
							}else{
								$data['items'][$i]['html_suplier'] = \yii\bootstrap\Html::activeDropDownList($modSppDetail, 'suplier_id', $value_arr,['class'=>'form-control select2','style'=>'padding:3px; font-size:1.1rem;','prompt'=>'','onchange'=>'setSupplier(this,'.$detail['sppd_id'].')']);
							}
						}
						$sql = "SELECT * FROM map_spp_detail_reff WHERE sppd_id = ".$detail['sppd_id'];
						$mod = Yii::$app->db->createCommand($sql)->queryAll();
						$data['items'][$i]['reff_beli'] = "";
						if(count($mod)>0){
							foreach($mod as $res){
								$ex = substr($res['reff_no'], 0,3);
								if($ex == "SPO"){
									$modSPO = \app\models\TSpo::findOne(['spo_kode'=>$res['reff_no']]);
									$data['items'][$i]['reff_beli'] .= "<a onclick='infoSPO(".$modSPO->spo_id.",".$detail['bhp_id'].")'>".$res['reff_no']."</a><br>";
								}else{
									$modSPL = \app\models\TSpl::findOne(['spl_kode'=>$res['reff_no']]);
									$data['items'][$i]['reff_beli'] .= "<a onclick='infoSPL(".$modSPL->spl_id.",".$detail['bhp_id'].")'>".$res['reff_no']."</a><br>";
								}
							}
						}
						$data['items'][$i]['reff_terima'] = $modSppDetail->QtyTerbeli['info_terima'];
						$data['items'][$i]['html_penawaran'] = $this->actionSetButtonPenawaran($detail['sppd_id']);
					}
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function getBhp_nm($bhp_nm){
		$array = explode("/", $bhp_nm);
		if(count($array)==2){
			$bhp_nm = $array[1];
		}
		if(count($array)==3){
			$bhp_nm = $array[1].'/'.$array[2];
		}
		if(count($array)==4){
			$bhp_nm = $array[1].'/'.$array[2].'/'.$array[3];
		}
		if(count($array)==5){
			$bhp_nm = $array[1].'/'.$array[2].'/'.$array[3].'/'.$array[4];
		}
		if(count($array)==6){
			$bhp_nm = $array[1].'/'.$array[2].'/'.$array[3].'/'.$array[4].'/'.$array[5];
		}
		return $bhp_nm;
	}
	
	public function actionFindSupplier(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			if(!empty($term)){
				$query = "
					SELECT * FROM m_suplier
					WHERE m_suplier.active IS TRUE
						".(!empty($term)?"AND suplier_nm ILIKE '%".$term."%'":'')." 
					ORDER BY m_suplier.suplier_nm ASC
					;
				";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'suplier_id', 'suplier_nm');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['suplier_id'], 'text'=>$val['suplier_nm']." ".$val['suplier_almt']];
					}
				}
			}
            return $this->asJson($data);
        }
    }
    
    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpp::findOne($id);
            $modDetail = \app\models\TSppDetail::find()->where(['spp_id'=>$id])->orderBy(['sppd_id'=>SORT_ASC])->all();
			return $this->renderAjax('info',['model'=>$model,'modDetail'=>$modDetail]);
		}
	}
	
    public function actionSpbTerkait($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpp::findOne($id);
            $modDetail = \app\models\TSppDetail::find()->where(['spp_id'=>$model->spp_id])->orderBy(['sppd_id'=>SORT_ASC])->all();
			$spbs = [];
			foreach($modDetail as $i => $detail){
				$sql = "SELECT t_spb.spb_id,t_spb.spb_kode FROM map_spb_detail_spp_detail 
						JOIN t_spb_detail ON t_spb_detail.spbd_id = map_spb_detail_spp_detail.spbd_id 
						JOIN t_spb ON t_spb.spb_id = t_spb_detail.spb_id
						WHERE sppd_id = ".$detail['sppd_id']." 
						GROUP BY t_spb.spb_id,t_spb.spb_kode";
				$spbs = \Yii::$app->db->createCommand($sql)->queryAll();
			}
			return $this->renderAjax('spbTerkait',['model'=>$model,'modDetail'=>$modDetail,'spbs'=>$spbs]);
		}
	}
    
	public function actionSetSupplier(){
		if(\Yii::$app->request->isAjax){
            $suplier_id = Yii::$app->request->post('suplier_id');
            $sppd_id = Yii::$app->request->post('sppd_id');
            
			$transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $success_2 = true;
                $modSppDetail = \app\models\TSppDetail::findOne($sppd_id);
				$modSppDetail->suplier_id = $suplier_id;
                if($modSppDetail->validate()){
                    if($modSppDetail->save()){
                        $success_1 = true;
                        $modSpp = \app\models\TSpp::findOne(['spp_id'=>$modSppDetail->spp_id]);
						$modSpp->spp_status = "INPROGRESS";
						if($modSpp->validate()){
							if($modSpp->save()){
								$success_2 = true;
							}
						}
                    }
                }
                if ($success_1 && $success_2) {
                    $transaction->commit();
                } else {
                    $transaction->rollback();
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
            }
			
            return $this->asJson($success_1 && $success_2);
        }
    }
	
	public function actionRiwayatPenerimaan(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-laporan'){
				$param['table']= \app\models\TTerimaBhpDetail::tableName();
				$param['pk']= \app\models\TTerimaBhpDetail::primaryKey()[0];
				$param['column'] = ['terima_bhpd_id',
									'terimabhp_kode',
									'tglterima',
									'suplier_nm',
									'bhp_kode',
									'bhp_nm',
									'terimabhpd_qty',
									'bhp_satuan',
									'terimabhpd_harga',
									'(terimabhpd_qty*terimabhpd_harga)*0.1 AS ppn',
									't_terima_bhp.spo_id',
									'(terimabhpd_qty*terimabhpd_harga)+((terimabhpd_qty*terimabhpd_harga)*0.1) as total',
									'terimabhpd_keterangan',
									'ppn_nominal',
									'pph_peritem',
									'm_default_value.name_en as mata_uang'];
				$param['join']= ['JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_terima_bhp_detail.terima_bhp_id',
								 'JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_terima_bhp_detail.bhp_id',
								 'LEFT JOIN t_spo ON t_spo.spo_id = t_terima_bhp.spo_id',
								 'LEFT JOIN m_default_value ON m_default_value.value = t_spo.mata_uang',
								 'LEFT JOIN m_suplier ON m_suplier.suplier_id = t_terima_bhp_detail.suplier_id',];
				$param['where']= "t_terima_bhp.cancel_transaksi_id IS NULL ";
				return \yii\helpers\Json::encode( \app\components\SSP::complex( $param ) );
			}
			return $this->renderAjax('riwayatPenerimaan');
        }
    }
	
	public function actionClosespp($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TSppDetail::findOne($id);
			if(empty($this->status_closed)){
				if(!empty($model->spp->cancel_transaksi_id)){
					$currentstatus = \app\models\TCancelTransaksi::STATUS_ABORTED;
				}else{
					if($model->QtyTerbeli['qty'] == 0){
						$currentstatus = "TO-DO";
					}else{
						if($model->QtyTerbeli['qty'] < $model->sppd_qty){
							$currentstatus = "PARTIALLY";
						}else{
							$currentstatus = "COMPLETE";
						}
					}
				}
			}else{
				$currentstatus = 'CLOSED';
			}
			if( Yii::$app->request->post('TSppDetail')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
					if($_POST['TSppDetail']['status']=="0"){
						$model->status_closed = NULL;
					}
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
//                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
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
			return $this->renderAjax('closeSPP',['model'=>$model,'currentstatus'=>$currentstatus]);
		}
    }

	public function actionPenawaran($bhp_id,$sppd_id=null){
		$modBhp = \app\models\MBrgBhp::findOne($bhp_id);
		$current_data = "";
		if(!empty($sppd_id)){
			$mapPenawaran = \app\models\MapPenawaranBhp::find()->where("sppd_id = ".$sppd_id)->all();
			if(count($mapPenawaran)>0){
				foreach($mapPenawaran as $i => $tawar){
					$current_data[] = $tawar['penawaran_bhp_id'];
				}
				$current_data = \yii\helpers\Json::encode($current_data);
			}
		}
		if(\Yii::$app->request->post('dt')=='table-penawaran'){
			$param['table']= \app\models\TPenawaranBhp::tableName();
			$param['pk']= \app\models\TPenawaranBhp::primaryKey()[0];
			$param['join'] = ['JOIN m_suplier ON m_suplier.suplier_id = t_penawaran_bhp.suplier_id'];
			$param['column'] = ['penawaran_bhp_id','kode','tanggal','suplier_nm','qty','satuan_kecil','harga_satuan','keterangan','attachment'];
			$param['order'] = "t_penawaran_bhp.created_at DESC";
			$param['where'] = "bhp_id = ".$bhp_id;
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->renderAjax('penawaran',['modBhp'=>$modBhp,'current_data'=>$current_data]);
	}
	
	public function actionSetButtonPenawaran($sppd_id){
		$html_penawaran = ""; $supp = "";
		$modSppDetail = \app\models\TSppDetail::findOne($sppd_id);
		$sqltawar = "SELECT * FROM map_penawaran_bhp 
					 JOIN t_penawaran_bhp ON t_penawaran_bhp.penawaran_bhp_id = map_penawaran_bhp.penawaran_bhp_id 
					 JOIN m_suplier ON m_suplier.suplier_id = t_penawaran_bhp.suplier_id 
					 WHERE map_penawaran_bhp.sppd_id = ".$sppd_id;
		$modsupp = Yii::$app->db->createCommand($sqltawar)->queryAll();
		$sudahdiproses = TRUE;
		if(count($modsupp)>0){
			foreach($modsupp as $itwr => $restwr){
				$supp .= "<b>".($itwr+1).".</b> <span class='font-red-flamingo'>".substr($restwr['suplier_nm'],0,15)."</span>"."<br>";
				if((!empty($restwr['spod_id'])) || (!empty($restwr['spld_id']))){
                    if(!empty($restwr['spod_id'])){
                        $modSpo = Yii::$app->db->createCommand("SELECT * FROM t_spo JOIN t_spo_detail ON t_spo_detail.spo_id = t_spo.spo_id WHERE spod_id = ".$restwr['spod_id']." AND cancel_transaksi_id IS NULL")->queryOne();
                        if(!empty($modSpo)){
                            $sudahdiproses &= TRUE;
                        }else{
                            $sudahdiproses = FALSE;
                        }
                    }
                    if(!empty($restwr['spld_id'])){
                        $modSpl = Yii::$app->db->createCommand("SELECT * FROM t_spl JOIN t_spl_detail ON t_spl_detail.spl_id = t_spl.spl_id WHERE spld_id = ".$restwr['spld_id']." AND cancel_transaksi_id IS NULL")->queryOne();
                        if(!empty($modSpl)){
                            $sudahdiproses &= TRUE;
                        }else{
                            $sudahdiproses = FALSE;
                        }
                    }
				}else{
					$sudahdiproses = FALSE;
				}
			}
			if($sudahdiproses){
				$html_penawaran .= '<a onclick="penawaranTerpilih('.$sppd_id.')" class="btn btn-default" 
														style="font-size:0.9rem; padding:2px; line-height:1; text-align:left; height: 35px; width: 100%;">'
															.$supp.
													'</a>';
			}else{
				$html_penawaran .= '<a onclick="penawaran('.$modSppDetail->bhp_id.','.$sppd_id.(!empty($res['spod_id'])?",".$res['spod_id']:null).(!empty($res['spld_id'])?",".$res['spld_id']:null).')" class="btn btn-default" 
														style="font-size:0.9rem; padding:2px; line-height:1; text-align:left; height: 35px; width: 100%;">'
															.$supp.
													'</a>';
			}
		}else{
			$asd = Yii::$app->db->createCommand("SELECT * FROM map_spp_detail_reff WHERE sppd_id = ".$sppd_id)->queryOne();
			$qwe = Yii::$app->db->createCommand("SELECT * FROM t_spl_detail WHERE sppd_id = ".$sppd_id)->queryOne();
			if((!empty($asd))&&(empty($qwe))){
				$html_penawaran .= '-';
			}else{
				$html_penawaran .= '<a onclick="penawaran('.$modSppDetail->bhp_id.','.$sppd_id.')"><i class="icon-plus"></i></a>';
			}
		}
		return $html_penawaran;
	}
	
	public function actionUpdatePenawaran(){
		$sppd_id = Yii::$app->request->post('sppd_id');
		$data_checked = Yii::$app->request->post('data_checked');
		if(!empty($data_checked)){
			$data_checked = \yii\helpers\Json::decode($data_checked);
			$transaction = \Yii::$app->db->beginTransaction();
			try {
				$success_1 = true;
				$delPenawaraanOld = \app\models\MapPenawaranBhp::find()->where(['sppd_id'=>$sppd_id])->all();
				if(count($delPenawaraanOld)>0){
					$old_spod_id = \yii\helpers\ArrayHelper::map($delPenawaraanOld,'penawaran_bhp_id','spod_id');
					$old_spld_id = \yii\helpers\ArrayHelper::map($delPenawaraanOld,'penawaran_bhp_id','spld_id');
					\app\models\MapPenawaranBhp::deleteAll("sppd_id = ".$sppd_id);
				}
				if(count($data_checked)>0){
					foreach($data_checked as $penawaran_bhp_id){
						$modPenawaran = \app\models\TPenawaranBhp::findOne($penawaran_bhp_id);
						$mapPenawaranNew = new \app\models\MapPenawaranBhp();
						$mapPenawaranNew->penawaran_bhp_id = $penawaran_bhp_id;
						$mapPenawaranNew->sppd_id = $sppd_id;
						if(!empty($old_spod_id)){
							$mapPenawaranNew->spod_id = !empty($old_spod_id[$penawaran_bhp_id])?$old_spod_id[$penawaran_bhp_id]:null;
						}
						if(!empty($old_spld_id)){
							$mapPenawaranNew->spld_id = !empty($old_spld_id[$penawaran_bhp_id])?$old_spld_id[$penawaran_bhp_id]:null;
						}
						$mapPenawaranNew->qty = $modPenawaran->qty;
						$mapPenawaranNew->harga = $modPenawaran->harga_satuan;
						if($mapPenawaranNew->validate()){
							if($mapPenawaranNew->save()){
								$success_1 &= true;
							}else{
								$success_1 = false;
							}
						}
					}
				}
//				echo "<pre>";
//				print_r($success_1);
//				exit;
				if ($success_1) {
					$transaction->commit();
					$data['status'] = true;
				} else {
					$transaction->rollback();
					$data['status'] = false;
					(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
					(isset($data['message_validate']) ? $data['message'] = null : '');
				}
			} catch (\yii\db\Exception $ex) {
				$transaction->rollback();
			}
		}
		$data['html_suplier'] = "";
		$data['html_penawaran'] = $this->actionSetButtonPenawaran($sppd_id);
		return $this->asJson($data);
	}
	
	public function actionCreatePenawaran($id){
		$modBhp = \app\models\MBrgBhp::findOne($id);
		$model = new \app\models\TPenawaranBhp();
		$model->kode = "Auto Generate";
		$model->tanggal = date("d/m/Y");
		$model->bhp_id = $modBhp->bhp_id;
		$model->bhp_nm = $modBhp->bhp_nm;
		$model->qty = 1;
		$model->satuan_kecil = $modBhp->bhp_satuan;
		$model->harga_satuan = 0;
		if( Yii::$app->request->post('TPenawaranBhp')){
			$transaction = \Yii::$app->db->beginTransaction();
			try {
				$success_1 = false;
				$model->load(\Yii::$app->request->post());
				$model->kode = \app\components\DeltaGenerator::kodeTHS();
				$model->file1 = \yii\web\UploadedFile::getInstance($model, 'attachment');
				if($model->validate()){ 
					if(!empty($model->file1)){ 
						$randomstring = Yii::$app->getSecurity()->generateRandomString(4); 
						$dir_path = Yii::$app->basePath.'/web/uploads/pur/penawaran';
						if(!is_dir($dir_path)){ 
							mkdir(Yii::$app->basePath.'/web/uploads/pur'); 
							mkdir($dir_path); 
						} 
						$file_path = $dir_path.'/'.$randomstring.$model->kode.".".$model->file1->extension;
						$model->file1->saveAs($file_path,false);
						$model->attachment = $randomstring.$model->kode.".".$model->file1->extension;
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
		return $this->renderAjax('createPenawaran',['model'=>$model,'modBhp'=>$modBhp]);
	}
	
	public function actionInfoPenawaran($id,$disableEdit=null,$disableDelete=null){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TPenawaranBhp::findOne($id);
			return $this->renderAjax('infoPenawaran',['model'=>$model,'disableEdit'=>$disableEdit,'disableDelete'=>$disableDelete]);
		}
	}
	
	public function actionDeletePenawaran($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\TPenawaranBhp::findOne($id);
            $file_old = $model->attachment;
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					\app\models\MapPenawaranBhp::deleteAll("penawaran_bhp_id = ".$id);
					if($model->delete()){
						if($file_old != null){
							if (file_exists(Yii::$app->basePath.'/web/uploads/pur/penawaran/'.$file_old)) {
								unlink(Yii::$app->basePath.'/web/uploads/pur/penawaran/'.$file_old);
							}
						}
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['callback'] = "$('#modal-info-penawaran').modal('hide'); updateCurrentData();";
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid,'actionname'=>"deletePenawaran"]);
		}
	}
	
	public function actionEditPenawaran($id){
		$model = \app\models\TPenawaranBhp::findOne($id);
		$model->bhp_nm = $model->bhp->bhp_nm;
		if( Yii::$app->request->post('TPenawaranBhp')){
			$transaction = \Yii::$app->db->beginTransaction();
			try {
				$success_1 = false;
				$model->load(\Yii::$app->request->post());
				$model->kode = \app\components\DeltaGenerator::kodeTHS();
				$model->file1 = \yii\web\UploadedFile::getInstance($model, 'attachment');
				if($model->validate()){ 
					if(!empty($model->file1)){ 
						$randomstring = Yii::$app->getSecurity()->generateRandomString(4); 
						$dir_path = Yii::$app->basePath.'/web/uploads/pur/penawaran';
						if(!is_dir($dir_path)){ 
							mkdir(Yii::$app->basePath.'/web/uploads/pur'); 
							mkdir($dir_path); 
						} 
						$file_path = $dir_path.'/'.$randomstring.$model->kode.".".$model->file1->extension;
						$model->file1->saveAs($file_path,false);
						$model->attachment = $randomstring.$model->kode.".".$model->file1->extension;
						if($file_old != null){
							if (file_exists(Yii::$app->basePath.'/web/uploads/pur/penawaran/'.$file_old)) {
								unlink(Yii::$app->basePath.'/web/uploads/pur/penawaran/'.$file_old);
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
		return $this->renderAjax('editPenawaran',['model'=>$model]);
	}
}
