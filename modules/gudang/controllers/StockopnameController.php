<?php

namespace app\modules\gudang\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class StockopnameController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TStockopnameAgenda();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->penanggungjawab_display = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_RUSDIANTO)->pegawai_nama;
        $model->by_kadivacc_display = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_EKO_NOWO)->pegawai_nama;
        $model->by_kanitgud_display = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_ROCHANDRA)->pegawai_nama;
        $model->by_kadivmkt_display = \app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO)->pegawai_nama;
        $model->penanggungjawab = \app\components\Params::DEFAULT_PEGAWAI_ID_RUSDIANTO;
        $model->by_kadivacc = \app\components\Params::DEFAULT_PEGAWAI_ID_EKO_NOWO;
        $model->by_kanitgud = \app\components\Params::DEFAULT_PEGAWAI_ID_ROCHANDRA;
        $model->by_kadivmkt = \app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
        if(isset($_GET['stockopname_agenda_id'])){
            $model = \app\models\TStockopnameAgenda::findOne($_GET['stockopname_agenda_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->penanggungjawab_display = \app\models\MPegawai::findOne($model->penanggungjawab)->pegawai_nama;
            $model->by_kadivacc_display = \app\models\MPegawai::findOne($model->by_kadivacc)->pegawai_nama;
            $model->by_kanitgud_display = \app\models\MPegawai::findOne($model->by_kanitgud)->pegawai_nama;
            $model->by_kadivmkt_display = \app\models\MPegawai::findOne($model->by_kadivmkt)->pegawai_nama;
        }
        if( Yii::$app->request->post('TStockopnameAgenda')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_stockopname_agenda
                $success_2 = true; // t_stockopname_peserta
                $success_3 = false; // t_approval
                $model->load(\Yii::$app->request->post());
                if( (!isset($_GET['edit'])) && (!isset($_GET['stockopname_agenda_id'])) ){
                    $model->kode = \app\components\DeltaGenerator::kodeAgendaStockopname();
                    $model->status = "NOT ACTIVE";
                }
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        if( (isset($_POST['TStockopnamePeserta'])) && (count($_POST['TStockopnamePeserta'])>0) ){
                            if( (isset($_GET['edit'])) && (isset($_GET['stockopname_agenda_id'])) ){
                                // exec ini jika proses edit
                                $modDetail = \app\models\TStockopnamePeserta::find()->where(['stockopname_agenda_id'=>$_GET['stockopname_agenda_id']])->all();
                                if(count($modDetail)>0){
                                    \app\models\TStockopnamePeserta::deleteAll(['stockopname_agenda_id'=>$_GET['stockopname_agenda_id']]);
                                }
								// exec ini jika proses edit
                            }
                            foreach($_POST['TStockopnamePeserta'] as $i => $detail){
                                $modDetail = new \app\models\TStockopnamePeserta();
                                $modDetail->attributes = $detail;
                                $modDetail->stockopname_agenda_id = $model->stockopname_agenda_id;;
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
						$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
						if(count($modelApproval)>0){ // edit mode (sementara editable tanpa approve ulang dulu)
//							if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
//								$success_3 = $this->saveApproval($model);
//							}
                            $success_3 = true;
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
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'stockopname_agenda_id'=>$model->stockopname_agenda_id]);
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
    public function saveApproval($model){
		$success = false;
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->by_kadivacc;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success = $modelApproval->createApproval();
		if($model->by_kanitgud){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->by_kanitgud;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 2;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		if($model->by_kadivmkt){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->by_kadivmkt;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 3;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		return $success;
	}
    
    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new \app\models\TStockopnamePeserta();
            $data['item'] = $this->renderPartial('item',['modDetail'=>$modDetail]);
            return $this->asJson($data);
        }
    }
    public function actionFindPegawai(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
            $notin = json_decode( Yii::$app->request->get('notin') );
			$data = [];
			$active = "";
            if(!empty($notin)){
				$notin = 'AND m_pegawai.pegawai_id NOT IN('.implode(", ", $notin).')';
			}else{
				$notin = "";
			}
			if(!empty($term)){
				$query = "
					SELECT *,departement_nama FROM m_pegawai
                    JOIN m_departement ON m_departement.departement_id = m_pegawai.departement_id
					WHERE ".(!empty($term)?"pegawai_nama ILIKE '%".$term."%'":'')." AND m_pegawai.active IS TRUE {$notin}
					ORDER BY m_pegawai.pegawai_nama ASC;
				";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['pegawai_id'], 'text'=>$val['pegawai_nama']." - ".$val['departement_nama']];
					}
				}
			}
            return $this->asJson($data);
        }
    }
    function actionSetItem(){
		if(\Yii::$app->request->isAjax){
            $pegawai_id = Yii::$app->request->post('pegawai_id');
            if(!empty($pegawai_id)){
                $modPegawai = \app\models\MPegawai::findOne($pegawai_id);
                $data = $modPegawai->attributes;
                $data['jabatan_nama'] = !empty($modPegawai->jabatan_id)?$modPegawai->jabatan->jabatan_nama:"";
                $data['departement_nama'] = !empty($modPegawai->departement_id)?$modPegawai->departement->departement_nama:"";
                
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }
    function actionGetItemByPk(){
		if(\Yii::$app->request->isAjax){
            $stockopname_agenda_id= Yii::$app->request->post('stockopname_agenda_id');
            $edit = Yii::$app->request->post('edit');
            $data = []; $data['html'] = "";
            if(!empty($stockopname_agenda_id)){
                $model = \app\models\TStockopnameAgenda::findOne($stockopname_agenda_id);
                $modDetails = \app\models\TStockopnamePeserta::find()->where(['stockopname_agenda_id'=>$stockopname_agenda_id])->all();
                if(count($modDetails)>0){
                    foreach($modDetails as $i => $modDetail){
                        $modDetail->pegawai_nama = $modDetail->pegawai->pegawai_nama;
                        $modDetail->jabatan_nama = !empty($modDetail->pegawai->jabatan_id)?$modDetail->pegawai->jabatan->jabatan_nama:"";
                        $modDetail->departement_nama = !empty($modDetail->pegawai->departement_id)?$modDetail->pegawai->departement->departement_nama:"";
                        $data['html'] .= $this->renderPartial('item',['modDetail'=>$modDetail,'edit'=>$edit]);
                        $value = $modDetail->attributes;
						$value['pegawai_nama'] = $modDetail->pegawai->pegawai_nama;
						$value['departement_nama'] = (!empty($modDetail->pegawai->departement_id)?$modDetail->pegawai->departement->departement_nama:"");
                        $data['value'][$i] = $value;
                    }
                }
            }
            return $this->asJson($data);
        }
    }
    public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			$departement_id = Yii::$app->request->get('dept_id');
			if(\Yii::$app->request->get('dt')=='table-aftersave'){
				$param['table']= \app\models\TStockopnameAgenda::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TStockopnameAgenda::primaryKey()[0];
				$param['column'] = [ $param['table'].'.stockopname_agenda_id','kode','tanggal', 'status',
                                     'penanggungjawab.pegawai_nama AS penanggungjawab',
                                     'by_kadivacc.pegawai_nama AS by_kadivacc', 
                                     'by_kanitgud.pegawai_nama AS by_kanitgud',
                                     'by_kadivmkt.pegawai_nama AS by_kadivmkt',
                                     '(SELECT status FROM t_approval WHERE reff_no = t_stockopname_agenda.kode AND assigned_to = t_stockopname_agenda.by_kadivacc) AS by_kadivacc_status',
                                     '(SELECT status FROM t_approval WHERE reff_no = t_stockopname_agenda.kode AND assigned_to = t_stockopname_agenda.by_kanitgud) AS by_kanitgud_status',
                                     '(SELECT status FROM t_approval WHERE reff_no = t_stockopname_agenda.kode AND assigned_to = t_stockopname_agenda.by_kadivmkt) AS by_kadivmkt_status',];
				$param['join'] = "JOIN m_pegawai AS penanggungjawab ON penanggungjawab.pegawai_id = t_stockopname_agenda.penanggungjawab
								  JOIN m_pegawai AS by_kadivacc ON by_kadivacc.pegawai_id = t_stockopname_agenda.by_kadivacc
								  JOIN m_pegawai AS by_kanitgud ON by_kanitgud.pegawai_id = t_stockopname_agenda.by_kanitgud
								  JOIN m_pegawai AS by_kadivmkt ON by_kadivmkt.pegawai_id = t_stockopname_agenda.by_kadivmkt";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
    
    public function actionScan(){
        $model = new \app\models\TStockopname();
        $model->nama_peserta = Yii::$app->user->identity->pegawai->pegawai_nama;
		return $this->render('scan',['model'=>$model]);
	}
    public function actionCheckAgendaAktif(){
        if(\Yii::$app->request->isAjax){
            $data = [];
            $modAgenda = \app\models\TStockopnameAgenda::find()->where("status NOT IN('DONE','REJECTED')")->all();
            if(!empty($modAgenda)){
                $modAgenda2 = \app\models\TStockopnameAgenda::find()->where("status = 'ACTIVE'")->one();
                if(!empty($modAgenda2)){
                    $thisPegawaiId = Yii::$app->user->identity->pegawai_id;
                    $modPeserta = \app\models\TStockopnamePeserta::find()->where("stockopname_agenda_id = ".$modAgenda2->stockopname_agenda_id." AND pegawai_id=".$thisPegawaiId)->one();
                    if((!empty($modPeserta)) || (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER)){
                        $data['status'] = true;
                        $data['agenda'] = $modAgenda2->attributes;
                        $data['agenda']['nama_peserta'] = Yii::$app->user->identity->pegawai->pegawai_nama;
                        $data['judulhasil'] = "Hasil Scan Palet Verifikasi Data <br>Kode Agenda : <b>".$modAgenda2->kode."</b>,<br>Oleh : <b>".$data['agenda']['nama_peserta']."</b>";
                    }else{
                        $data['status'] = false;
                        $data['msg'] = "Anda tidak terdaftar sebagai peserta pada agenda ".$modAgenda2->kode;
                    }
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Agenda Verifikasi Data belum di setujui";
                }
            }else{
                $data['status'] = false;
                $data['msg'] = "Agenda Verifikasi Data ini sudah close";
            }
            return $this->asJson($data);
        }
    }
    function actionGetItemsScanned(){
		if(\Yii::$app->request->isAjax){
            if(\Yii::$app->request->get('dt')=='table-master'){
                $kode_agenda = \Yii::$app->request->get('kode_agenda');
                $gudang_id = \Yii::$app->request->get('gudang_id');
                if((empty($kode_agenda))||(empty($gudang_id))){
                    $kode_agenda = -1;
                }
                if(Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER){
                    $peserta = "AND t_stockopname.pegawai_id = ".Yii::$app->user->identity->pegawai_id;
                }else{
                    $peserta = "";
                }
                $param['table']= \app\models\TStockopname::tableName();
                $param['pk']= "tbko_id";
                $param['column'] = ['stockopname_id','t_stockopname.nomor_produksi',
                                    "(CASE WHEN m_brg_produk.produk_id IS NOT NULL THEN CONCAT('<b>',m_brg_produk.produk_kode,'</b><br>',m_brg_produk.produk_dimensi) ELSE null END) AS produk",
                                    'm_gudang.gudang_nm',
                                    "CONCAT('<b>',t_stockopname_agenda.kode,'</b><br>',TO_CHAR(t_stockopname_agenda.tanggal,'DD/MM/YYYY')) AS permintaan",
                                    '(CASE WHEN t_terima_ko.nomor_produksi IS NOT NULL THEN t_terima_ko.qty_kecil WHEN t_hasil_produksi.nomor_produksi IS NOT NULL THEN t_hasil_produksi.qty_kecil ELSE 0 END) AS qty_kecil', 
                                    '(CASE WHEN t_terima_ko.nomor_produksi IS NOT NULL THEN t_terima_ko.qty_m3 WHEN t_hasil_produksi.nomor_produksi IS NOT NULL THEN t_hasil_produksi.qty_m3 ELSE 0 END) AS qty_m3', 
                                    't_stockopname.created_at','username','t_stockopname.status'];
                $param['join'] = "LEFT JOIN m_brg_produk ON m_brg_produk.produk_id = t_stockopname.produk_id
                                  LEFT JOIN t_terima_ko ON t_terima_ko.nomor_produksi = t_stockopname.nomor_produksi
                                  LEFT JOIN t_hasil_produksi ON t_hasil_produksi.nomor_produksi = t_stockopname.nomor_produksi
                                  JOIN m_gudang ON m_gudang.gudang_id = t_stockopname.gudang_id
                                  JOIN t_stockopname_agenda ON t_stockopname_agenda.stockopname_agenda_id = t_stockopname.stockopname_agenda_id
                                  JOIN m_user ON m_user.user_id = t_stockopname.created_by
                                  ";
                $param['group'] = "GROUP BY 1,2,3,4,5,6,7,8,9,10";
                $param['where'] = "t_stockopname_agenda.stockopname_agenda_id = $kode_agenda AND t_stockopname.status != 'FNSY' $peserta";
                $param['order'] = "stockopname_id DESC";
                return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
            }
        }
    }
    public function actionSaveNomorProduksi(){
		if(\Yii::$app->request->isAjax){
			$data['status'] = false;
			$data['msg'] = "";
			$nomor_produksi = \Yii::$app->request->post('prod_number');
			$stockopname_agenda_id = \Yii::$app->request->post('stockopname_agenda_id');
			$gudang_id = \Yii::$app->request->post('gudang_id');
            $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modSo = \app\models\TStockopname::findOne(['nomor_produksi'=>$nomor_produksi,'stockopname_agenda_id'=>$stockopname_agenda_id]);
            $validasiBarang = false;
            if(!empty($nomor_produksi)){
                if( (strpos($nomor_produksi, "VNR") !== false) ||
                    (strpos($nomor_produksi, "STM") !== false) ||
                    (strpos($nomor_produksi, "PWD") !== false) ||
                    (strpos($nomor_produksi, "MLD") !== false) ||
                    (strpos($nomor_produksi, "PFM") !== false) ||
                    (strpos($nomor_produksi, "LBD") !== false) ){
                    $validasiBarang = true;
                }
            }
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_stockopname
                if(!empty($modProduksi)){
                    if(empty($modSo)){
                        $model = new \app\models\TStockopname();
                        $model->stockopname_agenda_id = $stockopname_agenda_id;
                        $model->waktu_scan = date("Y-m-d H:i:s");
                        $model->nomor_produksi = $nomor_produksi;
                        $model->gudang_id = $gudang_id;
                        $model->produk_id = !empty($modProduksi)?$modProduksi->produk_id:null;
                        $model->produksi_id = !empty($modProduksi)?$modProduksi->produksi_id:null;
                        $model->pegawai_id = \Yii::$app->user->identity->pegawai_id;
                        $model->status = $this->getStatusPaletSO($nomor_produksi);
                        if($model->validate()){
                            if($model->save()){
                                $success_1 = true;
                            }
                        }
                    }else{
                        $data['status'] = false;
                        $data['msg'] = "Barang sudah pernah discan!<br>oleh : ".\app\models\MUser::findIdentity($modSo->created_by)->username.", pada : ".date("d/m/Y H:i", strtotime($modSo->created_at));
                    }
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Produk tidak dikenali.";
                }
                
//                echo "<pre>";
//                print_r($success_1);
//                exit;
                
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
	}
    public function getStatusPaletSO($nomor_produksi){
        $status = "FYSN";
        $modPersediaan = \app\models\HPersediaanProduk::getCurrentStockPerPalet($nomor_produksi);
        if(!empty($modPersediaan)){
            if($modPersediaan['palet']>0){
                $status = "FYSY";
            }
        }
        return $status;
    }
    public function actionInfoProdukSo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TStockopname::findOne($id);
            $modProduksi = \app\models\TProduksi::findOne(["nomor_produksi"=>$model->nomor_produksi]);
            $modTerima = \app\models\HPersediaanProduk::find()->where("nomor_produksi = '".$model->nomor_produksi."' AND in_qty_palet != 0 AND keterangan NOT ILIKE '%MUTASI DARI GUDANG%'")->orderBy("created_at DESC")->one();
            $modKeluar = \app\models\HPersediaanProduk::find()->where("nomor_produksi = '".$model->nomor_produksi."' AND out_qty_palet != 0 AND keterangan NOT ILIKE '%MUTASI DARI GUDANG%'")->orderBy("created_at DESC")->one();
			return $this->renderAjax('infoProdukSo',['model'=>$model,'modProduksi'=>$modProduksi,'modTerima'=>$modTerima,'modKeluar'=>$modKeluar]);
		}
	}
    public function actionDeleteSo($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\TStockopname::findOne($id);
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
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid,'actionname'=>'deleteSo']);
		}
	}
    
    public function actionHasil(){
        $model = new \app\models\TStockopnameHasil();
        if(\Yii::$app->request->isAjax && Yii::$app->request->get('confirm') && Yii::$app->request->get('id')){
            $stockopname_agenda_id = Yii::$app->request->get('id');
            $jenis_produk = Yii::$app->request->get('jenis_produk'); $jenis_produk = explode(",", $jenis_produk);
            $model->by_gmopr = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
            $model->by_gmopr_display = \app\models\MPegawai::findOne($model->by_gmopr)->pegawai_nama;
            $model->by_dirut = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
            $model->by_dirut_display = \app\models\MPegawai::findOne($model->by_dirut)->pegawai_nama;
            $modAgenda = \app\models\TStockopnameAgenda::findOne($stockopname_agenda_id);
            $modPeserta = \app\models\TStockopnamePeserta::find()->where("stockopname_agenda_id = ".$modAgenda->stockopname_agenda_id)->all();
			$pesan = "Konfirmasi Selesai Verifikasi Data Gudang Barang Jadi";
            $form_params = []; parse_str(\Yii::$app->request->get('data'),$form_params);
            if( isset($form_params['TStockopnameHasil']) ){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_stockopname_hasil
                    $success_5 = true; // t_stockopname_hasil_detail
                    $success_2 = false; // t_stockopname_agenda -- update
                    $success_3 = true; // t_approval
                    $success_4 = false; // t_stockopname -- INSERT FNSY
                    $model->attributes = $form_params['TStockopnameHasil'];
                    $model->attributes = $form_params['TStockopnameHasil']['total'];
                    $model->stockopname_agenda_id = $stockopname_agenda_id;
                    $model->jenis_produk = \yii\helpers\Json::encode($jenis_produk);
                    $model->kode = \app\components\DeltaGenerator::kodeHasilStockopname();
                    $model->tanggal = date("d/m/Y");
                    $model->by_prepared = Yii::$app->user->identity->pegawai_id;
                    if($form_params['TStockopnameHasil']['lanjut_adjustment'] == '0'){
                        $model->by_gmopr = null;
                        $model->by_dirut = null;
                    }
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                            // START hasil_detail
                            foreach($form_params['TStockopnameHasilDetail'] as $i => $detail){
                                if($i != 'total'){
                                    $modDetail = new \app\models\TStockopnameHasilDetail();
                                    $modDetail->attributes = $detail;
                                    $modDetail->stockopname_hasil_id = $model->stockopname_hasil_id;
                                    $modDetail->jenis_produk = $i;
                                    if($modDetail->validate()){
                                        if($modDetail->save()){
                                            $success_5 &= true;
                                        }else{
                                            $success_5 = false;
                                        }
                                    }else{
                                        $success_5 = false;
                                    }
                                }
                            }
                            // END hasil_detail
                            
                            $modAgenda->status = "DONE";
                            if($modAgenda->validate()){
                                if($modAgenda->save()){
                                    $success_2 = true;
                                    // START Create Approval
                                    if(!empty($model->by_gmopr)){
                                        $modelApproval = new \app\models\TApproval();
                                        $modelApproval->assigned_to = $model->by_gmopr;
                                        $modelApproval->reff_no = $model->kode;
                                        $modelApproval->tanggal_berkas = $model->tanggal;
                                        $modelApproval->level = 1;
                                        $modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
                                        $success_3 = $modelApproval->createApproval();
                                        if(!empty($model->by_dirut)){
                                            $modelApproval = new \app\models\TApproval();
                                            $modelApproval->assigned_to = $model->by_dirut;
                                            $modelApproval->reff_no = $model->kode;
                                            $modelApproval->tanggal_berkas = $model->tanggal;
                                            $modelApproval->level = 2;
                                            $modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
                                            $success_3 = $modelApproval->createApproval();
                                        }
                                    }
                                    // END Create Approval
                                    // START Insert FNSY
                                    $timestamp = date("Y-m-d H:i:s");
                                    $quejp = $this->actionGetParamJenisProduk(\yii\helpers\Json::decode($model->jenis_produk))['query'];
                                    $sqlInsert = "  INSERT INTO public.t_stockopname(
                                                        stockopname_agenda_id, waktu_scan, nomor_produksi, 
                                                        produk_id, produksi_id, pegawai_id, status, keterangan, 
                                                        created_at, created_by, updated_at, updated_by)
                                                    (
                                                    SELECT {$model->stockopname_agenda_id}, '{$timestamp}'::timestamp, h_persediaan_produk.nomor_produksi,
                                                        t_produksi.produk_id,t_produksi.produksi_id, ".Yii::$app->user->identity->pegawai_id.", 'FNSY', 'Hasil Closing', 
                                                        '{$timestamp}'::timestamp, ".Yii::$app->user->identity->pegawai_id.", '{$timestamp}'::timestamp, ".Yii::$app->user->identity->pegawai_id."
                                                    FROM h_persediaan_produk 
                                                    JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
                                                    JOIN t_produksi ON t_produksi.nomor_produksi = h_persediaan_produk.nomor_produksi
                                                    WHERE h_persediaan_produk.keterangan NOT ILIKE '%MUTASI DARI GUDANG%' 
                                                        AND tgl_transaksi <= '{$model->tanggal}' AND ( {$quejp} )
                                                        AND h_persediaan_produk.nomor_produksi NOT IN (SELECT nomor_produksi FROM t_stockopname WHERE stockopname_agenda_id = {$model->stockopname_agenda_id})
                                                    GROUP BY 1,2,3,4,5,6 HAVING SUM(in_qty_palet-out_qty_palet) > 0 
                                                    )";
                                    $sqlUpdate =  " UPDATE t_stockopname
                                                    SET gudang_id=subquery.gudang_id
                                                    FROM (SELECT nomor_produksi, gudang_id
                                                        FROM h_persediaan_produk 
                                                        WHERE h_persediaan_produk.keterangan NOT ILIKE '%MUTASI DARI GUDANG%' AND tgl_transaksi <= '{$model->tanggal}'
                                                        GROUP BY nomor_produksi, gudang_id ) AS subquery
                                                    WHERE t_stockopname.nomor_produksi=subquery.nomor_produksi AND t_stockopname.stockopname_agenda_id = {$model->stockopname_agenda_id} AND t_stockopname.status = 'FNSY';";
                                    if(Yii::$app->db->createCommand($sqlInsert)->execute()){
                                        $success_4 = Yii::$app->db->createCommand($sqlUpdate)->execute();
                                    }
                                    // END Insert FNSY
                                }else{
                                    $success_2 = false;
                                }
                            }else{
                                $success_2 = false;
                            }
                        }else{
                            $success_1 = false;
                        }
                    }else{
                        $success_1 = false;
                    }

//    					echo "<pre>";
//    					print_r($success_1);
//    					echo "<pre>";
//    					print_r($success_2);
//    					echo "<pre>";
//    					print_r($success_3);
//    					echo "<pre>";
//    					print_r($success_4);
//    					echo "<pre>";
//    					print_r($success_5);
//    					exit;

                    if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['callback'] = '$( "#close-btn-modal" ).click(); location.reload(true);';
                        $data['message'] = Yii::t('app', "Agenda Berhasil di Close");
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
			return $this->renderAjax('_confirmHasil',['pesan'=>$pesan,'jenis_produk'=>$jenis_produk,'model'=>$model,'modAgenda'=>$modAgenda,'modPeserta'=>$modPeserta]);
		}
		return $this->render('hasil',['model'=>$model]);
	}
    public function actionGetParamJenisProduk($jenis_produk){
        $ret = []; $jns = ""; $lainnya = "";
        if(!is_array($jenis_produk)){
            $jenis_produk = explode(",", $jenis_produk);
        }
        if(count($jenis_produk)>0){
            foreach($jenis_produk as $uwuk => $zxczxc){
                if($zxczxc == "Lainnya"){
                    $lainnya = "(m_brg_produk.produk_id IS NULL)";
                    unset($jenis_produk[array_search("Lainnya",$jenis_produk)]);
                }
            }
            $ijns = 0;
            foreach($jenis_produk as $i => $jnsp){
                $ijns = $ijns+1;
                $jns .= "'".$jnsp."'";
                if(count($jenis_produk)!=$ijns){
                    $jns .= ",";
                }
            }
        }
        if(!empty($jns)){
            $jns = "m_brg_produk.produk_group IN (".$jns.") ".(!empty($lainnya)?" OR ":"");
        }
        $ret['query'] = $jns." ".$lainnya;
        $ret['jenis_produk'] = $jenis_produk;
        return $ret;
    }

    public function actionGetDataSummary($stockopname_agenda_id,$queryJenisProduk,$stockopname_hasil_id=null){
        $ret['fisik_yes_system_yes_palet'] = 0;
        $ret['fisik_yes_system_no_palet'] = 0;
        $ret['fisik_no_system_yes_palet'] = 0;

        $ret['fisik_yes_system_yes_m3'] = 0;
        $ret['fisik_yes_system_no_m3'] = 0;
        $ret['fisik_no_system_yes_m3'] = 0;

        $today = date('Y-m-d');
        
        $modFysy = Yii::$app->db->createCommand(" 
                    SELECT t_stockopname.nomor_produksi
                        FROM t_stockopname 
                    LEFT JOIN m_brg_produk ON m_brg_produk.produk_id = t_stockopname.produk_id
                    WHERE status = 'FYSY' AND stockopname_agenda_id = {$stockopname_agenda_id} AND ( $queryJenisProduk )
                    GROUP BY 1")->queryAll();
        
        $modFysn = Yii::$app->db->createCommand(" 
                    SELECT t_stockopname.nomor_produksi
                        FROM t_stockopname 
                    LEFT JOIN m_brg_produk ON m_brg_produk.produk_id = t_stockopname.produk_id
                    WHERE status = 'FYSN' AND stockopname_agenda_id = {$stockopname_agenda_id} AND ( $queryJenisProduk )
                    GROUP BY 1")->queryAll();
        
        $modFnsy = Yii::$app->db->createCommand(" 
                    SELECT h_persediaan_produk.nomor_produksi, ROUND(SUM(in_qty_m3-out_qty_m3)::numeric,4) AS kubikasi
                    FROM h_persediaan_produk
                    JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
                    WHERE tgl_transaksi <= '{$today}' AND ( $queryJenisProduk )
                        AND nomor_produksi NOT IN (
                            SELECT t_stockopname.nomor_produksi
                                FROM t_stockopname 
                            LEFT JOIN m_brg_produk ON m_brg_produk.produk_id = t_stockopname.produk_id
                            WHERE status = 'FYSY' AND stockopname_agenda_id = {$stockopname_agenda_id} AND ( $queryJenisProduk )
                            GROUP BY 1
                        )
                    GROUP BY 1
                    HAVING SUM(in_qty_palet-out_qty_palet) > 0 ")->queryAll();
        
        $modAvai = Yii::$app->db->createCommand(" 
                    SELECT h_persediaan_produk.nomor_produksi, ROUND(SUM(in_qty_m3-out_qty_m3)::numeric,4) AS kubikasi
                    FROM h_persediaan_produk
                    JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
                    WHERE tgl_transaksi <= '{$today}' AND ( $queryJenisProduk )
                    GROUP BY 1
                    HAVING SUM(in_qty_palet-out_qty_palet) > 0 ")->queryAll();
        
        $ret['fisik_yes_system_yes_palet'] = count($modFysy);
        $ret['fisik_yes_system_no_palet'] = count($modFysn);
        $ret['fisik_no_system_yes_palet'] = count($modFnsy);
        
        $ret['total_fisik_palet'] = $ret['fisik_yes_system_yes_palet'] + $ret['fisik_yes_system_no_palet'];
        $ret['total_fisik_m3'] = $ret['fisik_yes_system_yes_m3'] + $ret['fisik_yes_system_no_m3'];
        $ret['total_fisik_rp'] = 0;
        $ret['total_system_palet'] = count($modAvai);
        $ret['total_system_m3'] = count($modAvai);
        $ret['total_system_rp'] = 0;
        return $ret;
    }

    public function actionSetHasil(){
        if(\Yii::$app->request->isAjax){
            $data = [];
            $stockopname_agenda_id = \Yii::$app->request->post('stockopname_agenda_id');
            $jenis_produk = \Yii::$app->request->post('jenis_produk');
            $confim = \Yii::$app->request->post('confirm');
            $summaryonly = \Yii::$app->request->post('summaryonly');
            if( (!empty($stockopname_agenda_id)) && (!empty($jenis_produk))){
                $modAgenda = \app\models\TStockopnameAgenda::findOne($stockopname_agenda_id);
                if($modAgenda->status == "DONE"){
                    $model = \app\models\TStockopnameHasil::findOne(['stockopname_agenda_id'=>$modAgenda->stockopname_agenda_id]);
                }else{
                    $model = new \app\models\TStockopnameHasil();
                }
                if(!empty($modAgenda)){
                    if(!empty($confim)){
                        $data['hasil'] = $this->renderPartial('_hasilSummaryConfirm',['model'=>$model,'jenis_produk'=>$jenis_produk,'modAgenda'=>$modAgenda]);
                    }else{
                        $data['hasil'] = $this->renderPartial('_hasilSummary',['model'=>$model,'jenis_produk'=>$jenis_produk,'modAgenda'=>$modAgenda,'summaryonly'=>$summaryonly]);
                    }
                    
                }
            }
            return $this->asJson($data);
        }
	}
    function actionGetHasilDetailQuery($stockopname_agenda_id,$queryJenisProduk,$status){
        $param['table']= \app\models\TStockopname::tableName();
        $param['pk']= "stockopname_id";
        $param['column'] = ['stockopname_id','t_stockopname.nomor_produksi',
                            "(CASE WHEN m_brg_produk.produk_id IS NOT NULL THEN CONCAT('<b>',m_brg_produk.produk_nama,'</b><br>',m_brg_produk.produk_dimensi) ELSE null END) AS produk",
                            'm_gudang.gudang_nm',
                            "CONCAT('<b>',t_stockopname_agenda.kode,'</b><br>',TO_CHAR(t_stockopname_agenda.tanggal,'DD/MM/YYYY')) AS permintaan",
                            '0 AS qty_kecil', 
                            '0 AS qty_m3', 
                            't_stockopname.created_at','username','t_stockopname.status'];
        $param['join'] = "LEFT JOIN m_brg_produk ON m_brg_produk.produk_id = t_stockopname.produk_id
                          JOIN m_gudang ON m_gudang.gudang_id = t_stockopname.gudang_id
                          JOIN t_stockopname_agenda ON t_stockopname_agenda.stockopname_agenda_id = t_stockopname.stockopname_agenda_id
                          JOIN m_user ON m_user.user_id = t_stockopname.created_by
                          ";
        $param['group'] = "GROUP BY 1,2,3,4,5,6,7,8,9,10";
        $param['where'] = "t_stockopname_agenda.stockopname_agenda_id = ".$stockopname_agenda_id." AND ( ".$queryJenisProduk." ) AND t_stockopname.status = '{$status}'";
        $param['order'] = "stockopname_id DESC";
        return $param;
    }
    function actionGetHasilDetailQuery2($stockopname_agenda_id,$queryJenisProduk){
        $tgl = date("Y-m-d");
        $param['table']= \app\models\HPersediaanProduk::tableName();
        $param['pk']= "persediaan_produk_id";
        $param['column'] = ['nomor_produksi','nomor_produksi',
                            "(CASE WHEN m_brg_produk.produk_id IS NOT NULL THEN CONCAT('<b>',m_brg_produk.produk_nama,'</b><br>',m_brg_produk.produk_dimensi) ELSE null END) AS produk",
                            'm_gudang.gudang_nm', 
                            "'-' AS permintaan", 
                            "0 AS qty_kecil", 
                            "0 AS qty_m3", 
                            "'-' AS created_at", 
                            "'-' AS username", 
                            "'FNSY' AS status"
                            ];
        $param['join'] = "LEFT JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
                          JOIN m_gudang ON m_gudang.gudang_id = h_persediaan_produk.gudang_id
                          ";
        $param['where'] = "tgl_transaksi <= '{$tgl}' AND ( $queryJenisProduk ) 
                            AND nomor_produksi NOT IN (
                                SELECT t_stockopname.nomor_produksi
                                    FROM t_stockopname 
                                LEFT JOIN m_brg_produk ON m_brg_produk.produk_id = t_stockopname.produk_id
                                WHERE status = 'FYSY' AND stockopname_agenda_id = {$stockopname_agenda_id} AND ( $queryJenisProduk )
                                GROUP BY 1
                            ) 
                            ";
        $param['group'] = "GROUP BY 1,2,3,4,5,6,7,8,9,10";
        $param['having'] = "HAVING SUM(in_qty_palet-out_qty_palet) > 0";
        return $param;
    }
    function actionGetHasilDetail(){
		if(\Yii::$app->request->isAjax){
            if(\Yii::$app->request->get('dt')=='table-master'){
                $stockopname_agenda_id = \Yii::$app->request->get('stockopname_agenda_id');
                $jenis_produk = \Yii::$app->request->get('jenis_produk');
                $status = \Yii::$app->request->get('status');
                $paramJenisProduk = $this->actionGetParamJenisProduk($jenis_produk);
                if($status=="FNSY"){ 
                    $param = $this->actionGetHasilDetailQuery2($stockopname_agenda_id, $paramJenisProduk['query']);
                }else{
                    $param = $this->actionGetHasilDetailQuery($stockopname_agenda_id, $paramJenisProduk['query'], $status);
                }
                return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
            }
        }
    }
    public function actionPrintHasil(){
		$this->layout = '@views/layouts/metronic/print';
		$stockopname_agenda_id = Yii::$app->request->get('stockopname_agenda_id');
		$caraprint = Yii::$app->request->get('caraprint');
		$filterstatus = Yii::$app->request->get('filterstatus');
        $paramJenisProduk = $this->actionGetParamJenisProduk($_GET['jenis_produk']);
		$model = \app\models\TStockopnameAgenda::findOne($stockopname_agenda_id);
		$paramprint['judul'] = Yii::t('app', 'Hasil Verifikasi Data Agenda '.$model->kode.' pada tanggal '.\app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal));
		$paramprint['judul2'] = (!empty($paramJenisProduk['jenis_produk']) ? implode(", ", $paramJenisProduk['jenis_produk']):"");
		if($caraprint == 'PRINT'){
			return $this->render('printHasil',['model'=>$model,'paramprint'=>$paramprint,'jenis_produk'=>$_GET['jenis_produk'],'paramJenisProduk'=>$paramJenisProduk,'filterstatus'=>$filterstatus]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printHasil',['model'=>$model,'paramprint'=>$paramprint,'jenis_produk'=>$_GET['jenis_produk'],'paramJenisProduk'=>$paramJenisProduk,'filterstatus'=>$filterstatus]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printHasil',['model'=>$model,'paramprint'=>$paramprint,'jenis_produk'=>$_GET['jenis_produk'],'paramJenisProduk'=>$paramJenisProduk,'filterstatus'=>$filterstatus]);
		}
	}
}
