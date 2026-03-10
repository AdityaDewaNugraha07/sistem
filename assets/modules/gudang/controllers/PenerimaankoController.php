<?php

namespace app\modules\gudang\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PenerimaankoController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TTerimaKo();
        $modDetail = new \app\models\TTerimaKoKd();
        $modProduk = new \app\models\MBrgProduk();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->keterangan = "-";
        $model->jenis_penerimaan = "Biasa";
        $model->petugas_penerima = \Yii::$app->user->identity->pegawai->pegawai_id;
        $model->petugas_penerima_nama = \Yii::$app->user->identity->pegawai->pegawai_nama;
		$modProduksi = new \app\models\TProduksi();
		$modProduksi->tanggal_produksi = "";
        
        if(isset($_GET['tbko_id'])){
            $model = \app\models\TTerimaKo::findOne($_GET['tbko_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modDetail = \app\models\TTerimaKoKd::find()->where(['tbko_id'=>$model->tbko_id])->all();
			$model->jenis_penerimaan = (!empty($modDetail)?"Khusus":"Biasa");
			$model->nomor_urut_produksi = substr($model->nomor_produksi, -6);
			$model->qty_m3_display = ($model->qty_m3!=0)? number_format($model->qty_m3,4) :0;
			$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
			$modProduksi->tanggal_produksi = \app\components\DeltaFormatter::formatDateTimeForUser2($modProduksi->tanggal_produksi);
//			$modProduksi->plymill_shift = ['A','B'];
//			echo "<pre>";
//			print_r($modProduksi->attributes);
//			exit;
        }
		
        if( Yii::$app->request->post('TTerimaKo')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_terima_ko
                $success_2 = true; // t_terima_ko_kd
				$success_3 = true; // h_persediaan_produk
				$success_4 = false; // t_produksi
                $model->load(\Yii::$app->request->post());
                $modProduksi->load(\Yii::$app->request->post());
				$model->kode = \app\components\DeltaGenerator::kodePenerimaanKayuOlahan();
				$model->barcode = $model->kode;
				$model->nomor_produksi = $modProduksi->nomor_produksi;
				$model->tanggal_produksi = $modProduksi->tanggal_produksi;
				$model->p = isset($_POST['MBrgProduk']['produk_p'])?$_POST['MBrgProduk']['produk_p']:"";
				$model->l = isset($_POST['MBrgProduk']['produk_l'])?$_POST['MBrgProduk']['produk_l']:"";
				$model->t = isset($_POST['MBrgProduk']['produk_t'])?$_POST['MBrgProduk']['produk_t']:"";
				$model->p_satuan = isset($_POST['MBrgProduk']['produk_p_satuan'])?$_POST['MBrgProduk']['produk_p_satuan']:"";
				$model->l_satuan = isset($_POST['MBrgProduk']['produk_l_satuan'])?$_POST['MBrgProduk']['produk_l_satuan']:"";
				$model->t_satuan = isset($_POST['MBrgProduk']['produk_t_satuan'])?$_POST['MBrgProduk']['produk_t_satuan']:"";
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if(isset($_POST['TTerimaKoKd'])){
							foreach($_POST['TTerimaKoKd'] as $i => $detail){
								$modDetail = new \app\models\TTerimaKoKd();
								$modDetail->attributes = $detail;
								$modDetail->tbko_id = $model->tbko_id;
								if($modDetail->validate()){
									$success_2 &= $modDetail->save();
								}else{
									$success_2 = false;
									Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
								}
							}
						}
						
						// start t_produksi insert 
						if($model->produk->produk_group == "Plywood" || $model->produk->produk_group == "Veneer" || $model->produk->produk_group == "Platform" || $model->produk->produk_group == "Lamineboard"){
							$plymill_shift = "";
							foreach($modProduksi->plymill_shift as $i => $plymill){
								$plymill_shift .= $plymill;
							}
							$modProduksi->plymill_shift = $plymill_shift;
							$modProduksi->sawmill_line = "-";
						}else if($model->produk->produk_group == "Sawntimber"){
							$modProduksi->plymill_shift = "-";
						}else{
							$modProduksi->plymill_shift = "-";
							$modProduksi->sawmill_line = "-";
						}
						$modProduksi->produk_id = $model->produk_id;
						$modProduksi->keterangan = $model->keterangan;
						if($modProduksi->validate()){
							$success_4 = $modProduksi->save();
						}
						// end t_produksi insert 
						
						// Start Proses Update Stock 
						$modPersediaan = new \app\models\HPersediaanProduk();
						$modPersediaan->attributes = $model->attributes;
						$modPersediaan->nomor_produksi = $model->nomor_produksi;
						$modPersediaan->tgl_transaksi = $model->tanggal;
						$modPersediaan->reff_no = $model->kode;
						$modPersediaan->in_qty_palet = $model->qty_palet;
						$modPersediaan->in_qty_kecil = $model->qty_kecil;
						$modPersediaan->in_qty_kecil_satuan = $model->qty_kecil_satuan;
						$modPersediaan->in_qty_m3 = $model->qty_m3;
						$modPersediaan->out_qty_palet = 0;
						$modPersediaan->out_qty_kecil = 0;
						$modPersediaan->out_qty_kecil_satuan = $model->qty_kecil_satuan;
						$modPersediaan->out_qty_m3 = 0;
						$modPersediaan->keterangan = "PENERIMAAN PERSEDIAAN DARI PRODUKSI";
						$success_3 = \app\models\HPersediaanProduk::updateStokPersediaan($modPersediaan);
						// End Proses Update Stock
                    }
                }
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>3";
//				print_r($success_3);
//				echo "<pre>4";
//				print_r($success_4);
//				exit;
                if ($success_1 && $success_2 && $success_3 && $success_4) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'tbko_id'=>$model->tbko_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        // Akses Riwayat Penerimaan untuk PPIC
        $cur_user = \Yii::$app->user->identity->user_group_id;
        $user_ppic_staff = \app\components\Params::USER_GROUP_ID_PPIC_STAFF;
        $user_ppic_kadep = \app\components\Params::USER_GROUP_ID_PPIC_KADEP;
        if( (($cur_user == $user_ppic_staff) || ($cur_user == $user_ppic_kadep)) && (!isset($_GET['tbko_id'])) ){
            return $this->redirect(['/gudang/penerimaanko/riwayatpenerimaan']);
        }
        // End
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail,'modProduksi'=>$modProduksi,'modProduk'=>$modProduk]);
	}
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new \app\models\TTerimaKoKd();
			$produk_id = \Yii::$app->request->post('produk_id');
			$p_satuan = "cm";
			$l_satuan = "cm";
			$t_satuan = "cm";
			if(!empty($produk_id)){
				$modProduk = \app\models\MBrgProduk::findOne($produk_id);
				$p = $modProduk->produk_p;
				$l = $modProduk->produk_l;
				$t = $modProduk->produk_t;
				$p_satuan = $modProduk->produk_p_satuan;
				$l_satuan = $modProduk->produk_l_satuan;
				$t_satuan = $modProduk->produk_t_satuan;
			}
			$modDetail->p = (\Yii::$app->request->post('p'))?\Yii::$app->request->post('p'):$p;
			$modDetail->l = (\Yii::$app->request->post('l'))?\Yii::$app->request->post('l'):$l;
			$modDetail->t = (\Yii::$app->request->post('t'))?\Yii::$app->request->post('t'):$t;
			$modDetail->p_satuan = (\Yii::$app->request->post('p_satuan'))?\Yii::$app->request->post('p_satuan'):$p_satuan;
			$modDetail->l_satuan = (\Yii::$app->request->post('l_satuan'))?\Yii::$app->request->post('l_satuan'):$l_satuan;
			$modDetail->t_satuan = (\Yii::$app->request->post('t_satuan'))?\Yii::$app->request->post('t_satuan'):$t_satuan;
			$modDetail->qty = (\Yii::$app->request->post('qty'))?\Yii::$app->request->post('qty'):1;
			$modDetail->kapasitas_kubikasi = (\Yii::$app->request->post('kapasitas_kubikasi'))?\Yii::$app->request->post('kapasitas_kubikasi'):0;
			$modDetail->keterangan = (\Yii::$app->request->post('keterangan'))?\Yii::$app->request->post('keterangan'):"-";
            $data['item'] = $this->renderPartial('_addItem',['modDetail'=>$modDetail,'disabled'=>false]);
            return $this->asJson($data);
        }
    }
	
	function actionGetProduk(){
		if(\Yii::$app->request->isAjax){
            $produk_id = Yii::$app->request->post('produk_id');
            $persediaan_produk_id = Yii::$app->request->post('persediaan_produk_id');
            $data = [];
			if(!empty($persediaan_produk_id)){
				$modPersediaan = \app\models\HPersediaanProduk::findOne($persediaan_produk_id);
				$data['persediaan'] = $modPersediaan->attributes;
				$produk_id = $modPersediaan->produk_id;
				$modPenerimaan = \app\models\TTerimaKo::findOne(['kode'=>$modPersediaan->reff_no]);
				$data['tanggal_produksi'] = $modPenerimaan->tanggal_produksi;
				$data['nomor_produksi'] = $modPenerimaan->nomor_produksi;
				if(!empty($modPersediaan)){
					$modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$modPersediaan->nomor_produksi]);
					$data['terima'] = $modTerima->attributes;
					$data['gudang_asal_display'] = $modTerima->gudang->gudang_nm;
				}
			}
			if(!empty($produk_id)){
				$model = \app\models\MBrgProduk::findOne($produk_id);
				$model->kapasitas_kubikasi = !empty($model->kapasitas_kubikasi)?$model->kapasitas_kubikasi:0;
				$data['model'] = $model->attributes;
			}
            return $this->asJson($data);
        }
    }
	
	function actionGetItemsByPk(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data = []; $data['html'] = '';
			$modDetails = \app\models\TTerimaKoKd::find()->where(['tbko_id'=>$id])->all();
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
					$detail->kapasitas_kubikasi_display = ($detail->kapasitas_kubikasi!=0)? number_format($detail->kapasitas_kubikasi,4) :0;
					$data['html'] .= $this->renderPartial('_addItem',['modDetail'=>$detail,'disabled'=>true]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TTerimaKo::tableName();
				$param['pk']= $param['table'].".".\app\models\TTerimaKo::primaryKey()[0];
				$param['column'] = [$param['table'].'.tbko_id',
									$param['table'].'.kode',
									$param['table'].'.nomor_produksi',
									'm_brg_produk.produk_kode',
									'm_brg_produk.produk_nama',
									$param['table'].'.tanggal',
									$param['table'].'.tanggal_produksi',
									'm_gudang.gudang_nm',
									$param['table'].'.qty_kecil',
									$param['table'].'.qty_kecil_satuan',
									$param['table'].'.qty_m3'];
				$param['join']= ['JOIN m_gudang ON m_gudang.gudang_id = '.$param['table'].'.gudang_id 
								  JOIN m_brg_produk ON m_brg_produk.produk_id = '.$param['table'].'.produk_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
    
    public function actionCounterPrint(){
        if(\Yii::$app->request->isAjax){
            $model = \app\models\TTerimaKo::findOne(\Yii::$app->request->post('id'));
            $data = [];
            if(!empty($model)){
                $jmlprintout = \app\models\TPrintout::getJumlahPrintByReffNo($model->nomor_produksi);
                if(($jmlprintout >= 1) && (Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER)){
//                if($jmlprintout >= 1){
                    $adamanipulasi = \app\models\TPengajuanManipulasi::findOne(['reff_no'=>$model->kode]);
                    if(!empty($adamanipulasi)){
                        $adaapprove1 = \app\models\TApproval::find()->where(['reff_no'=>$adamanipulasi->kode,'assigned_to'=>$adamanipulasi->approver1])->orderBy("approval_id DESC")->one();
                        $adaapprove2 = \app\models\TApproval::find()->where(['reff_no'=>$adamanipulasi->kode,'assigned_to'=>$adamanipulasi->approver2])->orderBy("approval_id DESC")->one();
                        if($adaapprove1 && $adaapprove2){
                            if($adaapprove1->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED || $adaapprove2->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
                                $data['status'] = 'info';
                            }else if($adaapprove1->status == \app\models\TApproval::STATUS_REJECTED || $adaapprove2->status == \app\models\TApproval::STATUS_REJECTED){
                                $data['status'] = 'ajukan';
                            }
                        }
                    }
                }else{
                    \app\models\TPrintout::createPrintout(['reff_no'=>$model->kode,'reff_no2'=>$model->nomor_produksi]);
                    $data['status'] = 'print';
                }
                return $this->asJson($data);
            }
        }
    }
	
	public function actionPrintKartuBarang(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TTerimaKo::findOne($_GET['id']);
		$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'PRODUCT DETAILS');
		$barcodecontent = $model->nomor_produksi;
		if($caraprint == 'PRINT'){
			return $this->render('printKartuBarang',['model'=>$model,'paramprint'=>$paramprint,'barcodecontent'=>$barcodecontent,'modProduksi'=>$modProduksi]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printKartuBarang',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printKartuBarang',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
    
    public function actionPengajuancetakulang(){
		if(\Yii::$app->request->isAjax){
            $modTerima = \app\models\TTerimaKo::findOne($_GET['id']);
            $statuspengajuan = $_GET['statuspengajuan'];
            $model = new \app\models\TPengajuanManipulasi();
            $model->approver1 = \app\components\Params::DEFAULT_PEGAWAI_ID_NADLIM;
            $model->approver1_display = \app\models\MPegawai::findOne($model->approver1)->pegawai_nama;
            $model->approver2 = \app\components\Params::DEFAULT_PEGAWAI_ID_ILHAM;
            $model->approver2_display = \app\models\MPegawai::findOne($model->approver2)->pegawai_nama;
            $model->tipe = "CETAK ULANG LABEL PRODUK";
            $model->reff_no = $modTerima->kode;
            $model->reff_no2 = $modTerima->nomor_produksi;
            $model->priority = "NORMAL";
            $model->parameter1 = strval($modTerima->tbko_id);
			if( Yii::$app->request->post('TPengajuanManipulasi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_pengajuan_manipulasi
                    $success_2 = false; // t_approval
                    $model->load(\Yii::$app->request->post());
                    $model->kode = \app\components\DeltaGenerator::kodeAjuanManipulasiData();
                    $model->tanggal = date("Y-m-d");
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                            // START Create Approval
                            $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
                            if(count($modelApproval)>0){ // edit mode
                                if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
                                    $success_2 = $this->saveApproval($model);
                                }
                            }else{ // insert mode
                                $success_2 = $this->saveApproval($model);
                            }
                            // END Create Approval
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
//                    echo "<pre>";
//                    print_r($success_1);
//                    echo "<pre>";
//                    print_r($success_2);
//                    exit;
                    if ($success_1 && $success_2) {
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
			return $this->renderAjax('pengajuancetakulang',['model'=>$model,'statuspengajuan'=>$statuspengajuan,'modTerima'=>$modTerima]);
		}
    }
    
    public function saveApproval($model){
		$success = false;
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $model->approver1;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success = $modelApproval->createApproval();
        if($model->approver2){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approver2;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 2;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		return $success;
	}
	
	function actionGenerateNomorProduksi(){
		if(\Yii::$app->request->isAjax){
            $tgl = Yii::$app->request->post('tgl');
            $prod = Yii::$app->request->post('prod');
            $no_urut = Yii::$app->request->post('no_urut');
            $plymill_shift = Yii::$app->request->post('plymill_shift');
            $sawmill_line = Yii::$app->request->post('sawmill_line');
			$res_tgl = "ddmmyy";
			$res_prod = "XXX";
			$res_shln = "";
			$no_urut = !empty($no_urut)?$no_urut:"XXXXXX";
            if(!empty($tgl)){
				$res_tgl = date('dmy', strtotime(\app\components\DeltaFormatter::formatDateTimeForDb($tgl)));
			}
            if(!empty($prod)){
				$modProduk = \app\models\MBrgProduk::findOne($prod);
				$res_prod = \app\models\MDefaultValue::getOneValueByAttributes("jenis-produk",$modProduk->produk_group,'name_en');
				if($modProduk->produk_group == "Plywood" || $modProduk->produk_group == "Veneer" || $modProduk->produk_group == "Platform" || $modProduk->produk_group == "Lamineboard"){
					$plymill_res = "";
					if(!empty($plymill_shift)){
						foreach($plymill_shift as $i => $plymill){
							$plymill_res .= $plymill;
						}
					}
					$res_shln = $plymill_res;
				}else if($modProduk->produk_group == "Sawntimber"){
					$res_shln = $sawmill_line;
				}
			}
			$res = $res_tgl.$res_prod.$res_shln.$no_urut;
            return $this->asJson($res);
        }
    }
	
	public function actionScanBarcode(){
		
	}
	
	public function actionAvailableProduk(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-info'){
				$param['table']= \app\models\HPersediaanProduk::tableName();
				$param['pk']= $param['table'].".".\app\models\HPersediaanProduk::primaryKey()[0];
				$param['column'] = ['h_persediaan_produk.persediaan_produk_id',
									'h_persediaan_produk.produk_id',
									'm_brg_produk.produk_nama',
									't_terima_ko.nomor_produksi',
									't_terima_ko.tanggal_produksi',
									'SUM(in_qty_palet-out_qty_palet) AS palet',
									'SUM(in_qty_kecil-out_qty_kecil) AS qty_kecil',
									'in_qty_kecil_satuan',
									'SUM(in_qty_m3-out_qty_m3) as kubikasi',
									'm_gudang.gudang_kode'];
				$param['join']= ['JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id 
								  JOIN t_terima_ko ON t_terima_ko.produk_id = h_persediaan_produk.produk_id
								  JOIN m_gudang ON m_gudang.gudang_id = h_persediaan_produk.gudang_id'];
				$param['group'] = 'GROUP BY h_persediaan_produk.persediaan_produk_id,
											h_persediaan_produk.produk_id,
											m_brg_produk.produk_nama, 
											t_terima_ko.nomor_produksi,
											t_terima_ko.tanggal_produksi,
											in_qty_kecil_satuan,
											m_gudang.gudang_kode';
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('availableProduk');
        }
	}
	public function actionFindAvailableProduk(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT  h_persediaan_produk.persediaan_produk_id, 
						h_persediaan_produk.produk_id,
						m_brg_produk.produk_nama, 
						t_produksi.nomor_produksi, 
						t_produksi.tanggal_produksi, 
						SUM(in_qty_palet-out_qty_palet) AS palet, 
						SUM(in_qty_kecil-out_qty_kecil) AS qty_kecil, 
						in_qty_kecil_satuan, SUM(in_qty_m3-out_qty_m3) as kubikasi, 
						m_gudang.gudang_kode 
					FROM h_persediaan_produk 
					JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
					JOIN t_produksi ON t_produksi.nomor_produksi = h_persediaan_produk.nomor_produksi
					JOIN m_gudang ON m_gudang.gudang_id = h_persediaan_produk.gudang_id
					WHERE t_produksi.nomor_produksi ilike '%{$term}%'
					GROUP BY h_persediaan_produk.persediaan_produk_id, 
						h_persediaan_produk.produk_id,
						m_brg_produk.produk_nama, 
						t_produksi.nomor_produksi,
						t_produksi.tanggal_produksi, 
						in_qty_kecil_satuan, 
						m_gudang.gudang_kode;";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'persediaan_produk_id', 'nomor_produksi');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['persediaan_produk_id'], 'text'=>$val['nomor_produksi']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
    
    public function actionTerimarepacking(){
        $model = new \app\models\TTerimaKo();
        $modDetail = new \app\models\TTerimaKoKd();
        $modProduk = new \app\models\MBrgProduk();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->keterangan = "-";
        $model->jenis_penerimaan = "Biasa";
        $model->petugas_penerima = \Yii::$app->user->identity->pegawai->pegawai_id;
        $model->petugas_penerima_nama = \Yii::$app->user->identity->pegawai->pegawai_nama;
		$modProduksi = new \app\models\TProduksi();
		$modProduksi->tanggal_produksi = "";
        
        if(isset($_GET['tbko_id'])){
            $model = \app\models\TTerimaKo::findOne($_GET['tbko_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modDetail = \app\models\TTerimaKoKd::find()->where(['tbko_id'=>$model->tbko_id])->all();
			$model->jenis_penerimaan = (!empty($modDetail)?"Khusus":"Biasa");
			$model->nomor_urut_produksi = substr($model->nomor_produksi, -6);
			$model->qty_m3_display = ($model->qty_m3!=0)? number_format($model->qty_m3,4) :0;
			$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
			$modProduksi->tanggal_produksi = \app\components\DeltaFormatter::formatDateTimeForUser2($modProduksi->tanggal_produksi);
        }
		
        if( Yii::$app->request->post('TTerimaKo')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_terima_ko
                $success_2 = true; // t_terima_ko_kd
				$success_3 = true; // h_persediaan_produk
                $model->load(\Yii::$app->request->post());
                $modProduksi->load(\Yii::$app->request->post());
				$model->kode = \app\components\DeltaGenerator::kodePenerimaanKayuOlahan();
				$model->barcode = $model->kode;
				$model->p = isset($_POST['MBrgProduk']['produk_p'])?$_POST['MBrgProduk']['produk_p']:"";
				$model->l = isset($_POST['MBrgProduk']['produk_l'])?$_POST['MBrgProduk']['produk_l']:"";
				$model->t = isset($_POST['MBrgProduk']['produk_t'])?$_POST['MBrgProduk']['produk_t']:"";
				$model->p_satuan = isset($_POST['MBrgProduk']['produk_p_satuan'])?$_POST['MBrgProduk']['produk_p_satuan']:"";
				$model->l_satuan = isset($_POST['MBrgProduk']['produk_l_satuan'])?$_POST['MBrgProduk']['produk_l_satuan']:"";
				$model->t_satuan = isset($_POST['MBrgProduk']['produk_t_satuan'])?$_POST['MBrgProduk']['produk_t_satuan']:"";
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if(isset($_POST['TTerimaKoKd'])){
							foreach($_POST['TTerimaKoKd'] as $i => $detail){
								$modDetail = new \app\models\TTerimaKoKd();
								$modDetail->attributes = $detail;
								$modDetail->tbko_id = $model->tbko_id;
								if($modDetail->validate()){
									$success_2 &= $modDetail->save();
								}else{
									$success_2 = false;
									Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
								}
							}
						}
						
						// Start Proses Update Stock 
						$modPersediaan = new \app\models\HPersediaanProduk();
						$modPersediaan->attributes = $model->attributes;
						$modPersediaan->nomor_produksi = $model->nomor_produksi;
						$modPersediaan->tgl_transaksi = $model->tanggal;
						$modPersediaan->reff_no = $model->kode;
						$modPersediaan->in_qty_palet = $model->qty_palet;
						$modPersediaan->in_qty_kecil = $model->qty_kecil;
						$modPersediaan->in_qty_kecil_satuan = $model->qty_kecil_satuan;
						$modPersediaan->in_qty_m3 = $model->qty_m3;
						$modPersediaan->out_qty_palet = 0;
						$modPersediaan->out_qty_kecil = 0;
						$modPersediaan->out_qty_kecil_satuan = $model->qty_kecil_satuan;
						$modPersediaan->out_qty_m3 = 0;
						$modPersediaan->keterangan = "PENERIMAAN PERSEDIAAN DARI HASIL REPACKING";
						$success_3 = \app\models\HPersediaanProduk::updateStokPersediaan($modPersediaan);
						// End Proses Update Stock
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
                    return $this->redirect(['terimarepacking','success'=>1,'tbko_id'=>$model->tbko_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('terimarepacking',['model'=>$model,'modDetail'=>$modDetail,'modProduksi'=>$modProduksi,'modProduk'=>$modProduk]);
	}
    public function actionOpenHasilrepacking(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-hasilrepacking'){
				$param['table']= \app\models\THasilRepacking::tableName();
				$param['pk']= $param['table'].".".\app\models\THasilRepacking::primaryKey()[0];
				$param['column'] = [$param['table'].'.hasil_repacking_id',
									$param['table'].'.tanggal',
									$param['table'].'.nomor_produksi',
									'm_brg_produk.produk_kode',
									'm_brg_produk.produk_nama',
									$param['table'].'.tanggal_produksi',
									$param['table'].'.qty_kecil',
									$param['table'].'.qty_kecil_satuan',
									$param['table'].'.qty_m3',
                                    't_terima_ko.tbko_id'];
				$param['join']= ['JOIN m_brg_produk ON m_brg_produk.produk_id = '.$param['table'].'.produk_id
                                  LEFT JOIN t_terima_ko ON t_terima_ko.nomor_produksi = '.$param['table'].'.nomor_produksi'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('hasilrepacking');
        }
	}
    public function actionSetPalet(){
        if(\Yii::$app->request->isAjax){
            $kbj = Yii::$app->request->post('kbj');
            $data = [];
			if(!empty($kbj)){
                $modHasilrepacking = \app\models\THasilRepacking::findOne(['nomor_produksi'=>$kbj]);
                $modHasilrepacking->tanggal_produksi = \app\components\DeltaFormatter::formatDateTimeForUser2($modHasilrepacking->tanggal_produksi);
				$data['qty_m3_display'] = number_format($modHasilrepacking->qty_m3,4);;
				$data['hasil_repacking'] = $modHasilrepacking->attributes;
				$data['tanggal_produksi'] = $modHasilrepacking->tanggal_produksi;
				$data['nomor_produksi'] = $modHasilrepacking->nomor_produksi;
                $model = \app\models\MBrgProduk::findOne($modHasilrepacking->produk_id);
				$model->kapasitas_kubikasi = !empty($model->kapasitas_kubikasi)?$model->kapasitas_kubikasi:0;
				$data['model'] = $model->attributes;
                $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$kbj]);
                $data['produksi'] = $modProduksi->attributes;
			}
            return $this->asJson($data);
        }
    }
    function actionGetItemsRandom(){
		if(\Yii::$app->request->isAjax){
            $hasil_repacking_id = Yii::$app->request->post('hasil_repacking_id');
            $data = []; $data['html'] = '';
            $modHasilRepacking = \app\models\THasilRepacking::findOne($hasil_repacking_id);
            $modHasilRandom = \app\models\THasilRepackingRandom::find()->where(['hasil_repacking_id'=>$hasil_repacking_id])->all();
            if(count($modHasilRandom)>0){
                foreach($modHasilRandom as $i => $random){
                    $detail = new \app\models\TTerimaKoKd();
                    $detail->attributes = $random->attributes;
					$detail->kapasitas_kubikasi_display = ($detail->kapasitas_kubikasi!=0)? number_format($detail->kapasitas_kubikasi,4) :0;
					$data['html'] .= $this->renderPartial('_addItem',['modDetail'=>$detail,'disabled'=>true]);
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionRiwayatpenerimaan(){
        $model = new \app\models\TTerimaKo();
//		$model->tgl_awal = date('d/m/Y', strtotime('-3 days'));
//		$model->tgl_akhir = date('d/m/Y');
        if(\Yii::$app->request->get('dt')=='table-laporan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params);
				$model->attributes = $form_params['TTerimaKo'];
				$model->tgl_awal = $form_params['TTerimaKo']['tgl_awal'];
				$model->tgl_akhir = $form_params['TTerimaKo']['tgl_akhir'];
				$model->produk_id = $form_params['TTerimaKo']['produk_id'];
				$model->gudang_id = $form_params['TTerimaKo']['gudang_id'];
				$model->nomor_produksi = $form_params['TTerimaKo']['nomor_produksi'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchLaporanRiwayatTerimaDt() ));
		}
		return $this->render('riwayatPenerimaan',['model'=>$model]);
	}
    public function actionRiwayatpenerimaanPrint(){
		$this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TTerimaKo();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes = $_GET['TTerimaKo'];
		$model->tgl_awal = !empty($_GET['TTerimaKo']['tgl_awal'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TTerimaKo']['tgl_awal']):"";
		$model->tgl_akhir = !empty($_GET['TTerimaKo']['tgl_akhir'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TTerimaKo']['tgl_akhir']):"";
		$model->produk_id = !empty($_GET['TTerimaKo']['produk_id'])?$_GET['TTerimaKo']['produk_id']:"";
		$model->produk_dimensi = !empty($_GET['TTerimaKo']['produk_dimensi'])?$_GET['TTerimaKo']['produk_dimensi']:"";
		$model->nomor_produksi = !empty($_GET['TTerimaKo']['nomor_produksi'])?$_GET['TTerimaKo']['nomor_produksi']:"";
		$paramprint['judul'] = Yii::t('app', 'Laporan Rekapitulasi Penjualan Export');
		if($model->tgl_awal == $model->tgl_akhir){
			$paramprint['judul2'] = "Tanggal <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u>";
		}else{
			$paramprint['judul2'] = "Periode Tanggal <u>". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u> sd <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir)."</u>";
		}
		if($caraprint == 'PRINT'){
			return $this->render('riwayatPenerimaanPrint',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('riwayatPenerimaanPrint',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('riwayatPenerimaanPrint',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
    
    public function actionScanterima(){
        $model = new \app\models\TTerimaKo();
        $model->tanggal_scan_terima = date("d/m/Y");
		return $this->render('scanterima',['model'=>$model]);
	}
    function actionGetItemsScanned(){
		if(\Yii::$app->request->isAjax){
            if(\Yii::$app->request->get('dt')=='table-master'){
                $param['table']= \app\models\TTerimaKo::tableName();
                $param['pk']= "tbko_id";
                $param['column'] = ['tbko_id','t_terima_ko.nomor_produksi',
                                    "CONCAT('<b>',m_brg_produk.produk_kode,'</b><br>',m_brg_produk.produk_dimensi) AS produk",
                                    'm_gudang.gudang_nm',
                                    "CONCAT('<b>',t_kirim_gudang.kode,'</b><br>',t_kirim_gudang.tanggal) AS permintaan",
                                    't_terima_ko.qty_kecil', 't_terima_ko.qty_m3', 't_terima_ko.created_at', 'username'];
                $param['join'] = "JOIN m_brg_produk ON m_brg_produk.produk_id = t_terima_ko.produk_id
                                  JOIN t_kirim_gudang_detail ON t_kirim_gudang_detail.nomor_produksi = t_terima_ko.nomor_produksi
                                  JOIN t_kirim_gudang ON t_kirim_gudang.kirim_gudang_id = t_kirim_gudang_detail.kirim_gudang_id
                                  JOIN m_gudang ON m_gudang.gudang_id = t_terima_ko.gudang_id
                                  JOIN m_user ON m_user.user_id = t_terima_ko.created_by";
                $param['order'] = "created_at DESC";
                return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
            }
        }
    }
    function actionGetItemsScannedRepacking(){
		if(\Yii::$app->request->isAjax){
            if(\Yii::$app->request->get('dt')=='table-master'){
                $param['table']= \app\models\TTerimaKo::tableName();
                $param['pk']= "tbko_id";
                $param['column'] = ['tbko_id','t_terima_ko.nomor_produksi',
                                    "CONCAT('<b>',m_brg_produk.produk_kode,'</b><br>',m_brg_produk.produk_dimensi) AS produk",
                                    'm_gudang.gudang_nm',
                                    "CONCAT('<b>',t_hasil_repacking.kode,'</b><br>',t_hasil_repacking.tanggal) AS permintaan",
                                    't_terima_ko.qty_kecil', 't_terima_ko.qty_m3', 't_terima_ko.created_at', 'username'];
                $param['join'] = "JOIN m_brg_produk ON m_brg_produk.produk_id = t_terima_ko.produk_id
                                  JOIN t_hasil_repacking ON t_hasil_repacking.nomor_produksi= t_terima_ko.nomor_produksi
                                  JOIN m_gudang ON m_gudang.gudang_id = t_terima_ko.gudang_id
                                  JOIN m_user ON m_user.user_id = t_terima_ko.created_by";
                $param['order'] = "created_at DESC";
                return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
            }
        }
    }
    public function actionSaveNomorProduksi(){
		if(\Yii::$app->request->isAjax){
			$data['status'] = false;
			$data['msg'] = "";
			$nomor_produksi = \Yii::$app->request->post('prod_number');
			$gudang_id = \Yii::$app->request->post('gudang_id');
            $modHasilProduksi = \app\models\THasilProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modKirimDetail = \app\models\TKirimGudangDetail::findOne(['nomor_produksi'=>$nomor_produksi,'terkirim'=>true]);
            $modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$nomor_produksi]);
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_terima_ko
                $success_2 = true; // t_terima_ko_kd
				$success_3 = false; // h_persediaan_produk
                
                if(!empty($modHasilProduksi)){
                    if(!empty($modKirimDetail)){
                        if(empty($modTerima)){
                            $model = new \app\models\TTerimaKo();
                            $model->attributes = $modHasilProduksi->attributes;
                            $model->kode = \app\components\DeltaGenerator::kodePenerimaanKayuOlahan();
                            $model->barcode = $model->kode;
                            $model->tanggal = date("d/m/Y");
                            $model->gudang_id = $gudang_id;
                            $model->keterangan = "Hasil Scan";
                            $model->petugas_penerima = \Yii::$app->user->identity->pegawai_id;
                            if($model->validate()){
                                if($model->save()){
                                    $success_1 = true;
                                    // Jika ada random
                                    $modHasilProduksiRandom = \app\models\THasilProduksiRandom::find()->where(['hasil_produksi_id'=>$modHasilProduksi->hasil_produksi_id])->all();
                                    if(!empty($modHasilProduksiRandom)){
                                        foreach($modHasilProduksiRandom as $i => $random){
                                            $modRandom = new \app\models\TTerimaKoKd();
                                            $modRandom->attributes = $random->attributes;
                                            $modRandom->tbko_id = $model->tbko_id;
                                            if($modRandom->validate()){
                                                if($modRandom->save()){
                                                    $success_2 &= true;
                                                }else{
                                                    $success_2 = false;
                                                }
                                            }else{
                                                $success_2 = false;
                                            }
                                        }
                                    }
                                    // end
                                    // Start Proses Update Stock 
                                    $modPersediaan = new \app\models\HPersediaanProduk();
                                    $modPersediaan->attributes = $model->attributes;
                                    $modPersediaan->nomor_produksi = $model->nomor_produksi;
                                    $modPersediaan->tgl_transaksi = $model->tanggal;
                                    $modPersediaan->reff_no = $model->kode;
                                    $modPersediaan->in_qty_palet = $model->qty_palet;
                                    $modPersediaan->in_qty_kecil = $model->qty_kecil;
                                    $modPersediaan->in_qty_kecil_satuan = $model->qty_kecil_satuan;
                                    $modPersediaan->in_qty_m3 = $model->qty_m3;
                                    $modPersediaan->out_qty_palet = 0;
                                    $modPersediaan->out_qty_kecil = 0;
                                    $modPersediaan->out_qty_kecil_satuan = $model->qty_kecil_satuan;
                                    $modPersediaan->out_qty_m3 = 0;
                                    $modPersediaan->keterangan = "PENERIMAAN PERSEDIAAN DARI PRODUKSI";
                                    $success_3 = \app\models\HPersediaanProduk::updateStokPersediaan($modPersediaan);
                                    // End Proses Update Stock
                                }
                            }
                        }else{
                            $data['status'] = false;
                            $data['msg'] = "Barang sudah pernah diterima!";
                        }
                    }else{
                        $data['status'] = false;
                        $data['msg'] = "Tidak ada pengiriman dengan nomor tersebut!";
                    }
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Produk tidak ditemukan!";
                }
                
                
//                echo "<pre>";
//                print_r($success_1);
//                exit;
//                print_r($success_2);
//                exit;
//                print_r($success_3);
//                exit;
                
                if ($success_1 && $success_2 && $success_3 ) {
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
    
    public function actionSaveNomorProduksiRepacking(){
		if(\Yii::$app->request->isAjax){
			$data['status'] = false;
			$data['msg'] = "";
			$nomor_produksi = \Yii::$app->request->post('prod_number');
			$gudang_id = \Yii::$app->request->post('gudang_id');
            $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modHasilRepacking = \app\models\THasilRepacking::findOne(['nomor_produksi'=>$nomor_produksi]);
            $modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$nomor_produksi]);
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_terima_ko
                $success_2 = true; // t_terima_ko_kd
				$success_3 = false; // h_persediaan_produk
                if(!empty($modProduksi)){
                    if(!empty($modHasilRepacking)){
                        if(empty($modTerima)){
                            $model = new \app\models\TTerimaKo();
                            $model->attributes = $modHasilRepacking->attributes;
                            $model->kode = \app\components\DeltaGenerator::kodePenerimaanKayuOlahan();
                            $model->barcode = $model->kode;
                            $model->tanggal = date("d/m/Y");
                            $model->gudang_id = $gudang_id;
                            $model->keterangan = "Hasil Scan";
                            $model->petugas_penerima = \Yii::$app->user->identity->pegawai_id;
                            if($model->validate()){
                                if($model->save()){
                                    $success_1 = true;
                                    // Jika ada random
                                    $modHasilRepackingRandom = \app\models\THasilRepackingRandom::find()->where(['hasil_repacking_id'=>$modHasilRepacking->hasil_repacking_id])->all();
                                    if(!empty($modHasilRepackingRandom)){
                                        foreach($modHasilRepackingRandom as $i => $random){
                                            $modRandom = new \app\models\TTerimaKoKd();
                                            $modRandom->attributes = $random->attributes;
                                            $modRandom->tbko_id = $model->tbko_id;
                                            if($modRandom->validate()){
                                                if($modRandom->save()){
                                                    $success_2 &= true;
                                                }else{
                                                    $success_2 = false;
                                                }
                                            }else{
                                                $success_2 = false;
                                            }
                                        }
                                    }
                                    // end
                                    // Start Proses Update Stock 
                                    $modPersediaan = new \app\models\HPersediaanProduk();
                                    $modPersediaan->attributes = $model->attributes;
                                    $modPersediaan->nomor_produksi = $model->nomor_produksi;
                                    $modPersediaan->tgl_transaksi = $model->tanggal;
                                    $modPersediaan->reff_no = $model->kode;
                                    $modPersediaan->in_qty_palet = $model->qty_palet;
                                    $modPersediaan->in_qty_kecil = $model->qty_kecil;
                                    $modPersediaan->in_qty_kecil_satuan = $model->qty_kecil_satuan;
                                    $modPersediaan->in_qty_m3 = $model->qty_m3;
                                    $modPersediaan->out_qty_palet = 0;
                                    $modPersediaan->out_qty_kecil = 0;
                                    $modPersediaan->out_qty_kecil_satuan = $model->qty_kecil_satuan;
                                    $modPersediaan->out_qty_m3 = 0;
                                    $modPersediaan->keterangan = "PENERIMAAN PERSEDIAAN DARI HASIL REPACKING";
                                    $success_3 = \app\models\HPersediaanProduk::updateStokPersediaan($modPersediaan);
                                    // End Proses Update Stock
                                }
                            }
                        }else{
                            $data['status'] = false;
                            $data['msg'] = "Barang sudah pernah diterima!";
                        }
                    }else{
                        $data['status'] = false;
                        $data['msg'] = "Tidak ada pengiriman dengan nomor tersebut!";
                    }
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Produk tidak ditemukan!";
                }
                
                
//                echo "<pre>";
//                print_r($success_1);
//                echo "<pre>";
//                print_r($success_2);
//                echo "<pre>";
//                print_r($success_3);
//                exit;
                
                if ($success_1 && $success_2 && $success_3 ) {
                    $transaction->commit();
                    $data['status'] = true;
                    $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                } else {
                    $transaction->rollback();
                    $data['status'] = false;
                    (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                    (isset($data['message_validate']) ? $data['message'] = null : '');
                    $data['msg'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE);
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                $data['message'] = $ex;
            }
			return $this->asJson($data);
		}
	}
}
