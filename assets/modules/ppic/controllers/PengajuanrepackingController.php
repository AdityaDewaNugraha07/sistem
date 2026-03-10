<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PengajuanrepackingController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TPengajuanRepacking();
        $model->kode = 'Auto Generate';
		$model->dibuat_oleh = Yii::$app->user->identity->pegawai->pegawai_nama;
		$model->prepared_by = \app\components\Params::DEFAULT_PEGAWAI_ID_NADLIM;
		$model->approved_by = \app\components\Params::DEFAULT_PEGAWAI_ID_ILHAM;
        
        if(isset($_GET['pengajuan_repacking_id'])){
            $model = \app\models\TPengajuanRepacking::findOne($_GET['pengajuan_repacking_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->dibuat_oleh = \app\models\MPegawai::findOne( \app\models\MUser::findOne($model->created_by)->pegawai_id )->pegawai_nama;
        }
		
        if( Yii::$app->request->post('TPengajuanRepacking')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pengajuan_repacking
                $success_2 = true; // t_pengajuan_repacking_detail
                $success_3 = false; // t_approval
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodePengajuanRepacking();
				}
                $model->status = 'SEDANG DIAJUKAN';
                if($model->validate()){
                    if($model->save()){
						$success_1 = true;
						
						if(isset($_GET['edit'])){ // jika proses edit
							$modDetail = \app\models\TPengajuanRepackingDetail::findAll(['pengajuan_repacking_id'=>$model->pengajuan_repacking_id]);
                            if(count($modDetail)>0){
                                $success_2 = (\app\models\TPengajuanRepackingDetail::deleteAll("pengajuan_repacking_id = ".$model->pengajuan_repacking_id))?true:false;
                            }
						}
						
						foreach($_POST['TPengajuanRepackingDetail'] as $i => $detail){
							$modDetail = new \app\models\TPengajuanRepackingDetail();
							$modDetail->attributes = $detail;
							$modDetail->pengajuan_repacking_id = $model->pengajuan_repacking_id;
							if($modDetail->validate()){
								if($modDetail->save()){
									$success_2 &= true;
									
								}else{
									$success_2 = false;
								}
							}else{
								$success_2 = false;
							}
						}
                        
                        // START Create Approval
						$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
						if(count($modelApproval)>0){ // exist
							if(!isset($_GET['revisi'])){
								if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
									$success_3 = $this->saveApproval($model);
								}
							}else{
								$success_3 = $this->saveApproval($model);
							}
						}else{ // not exist
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
                    return $this->redirect(['index','success'=>1,'pengajuan_repacking_id'=>$model->pengajuan_repacking_id]);
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
		$modelApproval->assigned_to = $model->prepared_by;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success = $modelApproval->createApproval();
		if($model->approved_by){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approved_by;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 2;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		return $success;
	}
    
    public function actionAvailableStockPalet(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-produk'){
				$param['table']= \app\models\HPersediaanProduk::tableName();
				$param['pk']= \app\models\HPersediaanProduk::primaryKey()[0];
				$param['column'] = ['h_persediaan_produk.produk_id','m_brg_produk.produk_group','h_persediaan_produk.nomor_produksi','m_brg_produk.produk_nama',
                                    'm_brg_produk.produk_dimensi','m_gudang.gudang_nm','sum(in_qty_kecil-out_qty_kecil) AS qty_kecil',
                                    'sum(in_qty_m3-out_qty_m3) AS kubikasi'];
				$param['group'] = " GROUP BY h_persediaan_produk.produk_id, m_brg_produk.produk_group, h_persediaan_produk.nomor_produksi, m_brg_produk.produk_nama, 
                                    m_brg_produk.produk_dimensi, m_gudang.gudang_nm";
				$param['join']= ['JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
                                  JOIN m_gudang ON m_gudang.gudang_id = h_persediaan_produk.gudang_id'];
				$param['having'] = "HAVING SUM(in_qty_palet-out_qty_palet) > 0";
				$param['where'] = "h_persediaan_produk.keterangan NOT ILIKE '%MUTASI DARI GUDANG%'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('availableStockPalet',[]);
		}
	}
	
	public function actionPick(){
		if(\Yii::$app->request->isAjax){
			$produk_id = \Yii::$app->request->post('produk_id');
			$data['html'] = ''; $data['produk_id'] = $produk_id;
            $model = new \app\models\TPengajuanRepackingDetail();
			if(!empty($produk_id)){
				$modProduk = \app\models\MBrgProduk::findOne($produk_id);
                $modStock = \app\models\HPersediaanProduk::getCurrentStockPerProduk($produk_id);
                $model->produk_id = $modProduk['produk_id'];
                $model->produk_nama = $modProduk->produk_nama;
                $model->produk_dimensi = $modProduk->produk_dimensi;
                $model->qty_besar = 0;
                $model->qty_stock = $modStock['palet'];
                $data['html'] .= $this->renderPartial('item',['model'=>$model,'modStock'=>$modStock,'modProduk'=>$modProduk]);
			}
			return $this->asJson($data);
		}
	}
    
    function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $pengajuan_repacking_id = Yii::$app->request->post('id');
			$edit = Yii::$app->request->post('edit');
            if(!empty($pengajuan_repacking_id)){
                $modDetails = \app\models\TPengajuanRepackingDetail::find()->where(['pengajuan_repacking_id'=>$pengajuan_repacking_id])->all();
            }else{
                $modDetails = [];
            }
            $data['html'] = '';
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
                    $modProduk = \app\models\MBrgProduk::findOne($detail->produk_id);
                    $detail->jenis_produk = $modProduk->produk_group;
                    $detail->produk_nama = $modProduk->produk_nama;
                    $detail->produk_dimensi = $modProduk->produk_dimensi;
					if(!empty($edit)){
                        $modStock = \app\models\HPersediaanProduk::getCurrentStockPerProduk($detail->produk_id);
						$data['html'] .= $this->renderPartial('item',['model'=>$detail,'edit'=>$edit,'modStock'=>$modStock]);
					}else{
						$data['html'] .= $this->renderPartial('item',['model'=>$detail,'edit'=>$edit]);
					}
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPengajuanRepacking::tableName();
				$param['pk']= $param['table'].".".\app\models\TPengajuanRepacking::primaryKey()[0];
				$param['column'] = [$param['table'].'.pengajuan_repacking_id',
									$param['table'].'.kode',
									$param['table'].'.tanggal',
									$param['table'].'.keperluan',
									$param['table'].'.status',
									'peg1.pegawai_nama AS pegawai1',
									'apprv1.status AS approval1',
									'peg2.pegawai_nama AS pegawai2',
									'apprv2.status AS approval2',
									];
				$param['join']= ['JOIN t_approval AS apprv1 ON apprv1.reff_no = '.$param['table'].'.kode AND apprv1.assigned_to = '.$param['table'].'.prepared_by 
								  JOIN t_approval AS apprv2 ON apprv2.reff_no = '.$param['table'].'.kode AND apprv2.assigned_to = '.$param['table'].'.approved_by 
                                  JOIN m_pegawai AS peg1 ON peg1.pegawai_id = '.$param['table'].'.prepared_by 
								  JOIN m_pegawai AS peg2 ON peg2.pegawai_id = '.$param['table'].'.approved_by
                                '];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
    
    public function actionPrintRepacking(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TPengajuanRepacking::findOne($_GET['id']);
		$modDetail = \app\models\TPengajuanRepackingDetail::find()->where(['pengajuan_repacking_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'PERMINTAAN BARANG JADI');
		if($caraprint == 'PRINT'){
			return $this->render('printRepacking',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printRepacking',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printRepacking',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}
    
    function actionStatusQuery(){
        $param['table']= \app\models\TPengajuanRepackingDetail::tableName();
        $param['pk']= $param['table'].".".\app\models\TPengajuanRepackingDetail::primaryKey()[0];
        $param['column'] = ['t_pengajuan_repacking_detail.pengajuan_repacking_detail_id', // 0
                            't_pengajuan_repacking.kode', // 1
                            't_pengajuan_repacking.tanggal', // 2
                            't_pengajuan_repacking.keperluan', // 3
                            "(SELECT status FROM t_approval WHERE t_approval.reff_no = t_pengajuan_repacking.kode AND assigned_to = t_pengajuan_repacking.approved_by) AS by_approved_status", // 4
                            "(SELECT CONCAT('<b>',produk_nama,'</b><br>',produk_dimensi ) FROM m_brg_produk WHERE m_brg_produk.produk_id = t_pengajuan_repacking_detail.produk_id) AS produk", // 5
                            "t_pengajuan_repacking_detail.qty_besar AS pcs", // 6
                            "(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( 
                                SELECT t_mutasi_keluar.mutasi_keluar_id, t_mutasi_keluar.nomor_produksi FROM t_mutasi_keluar 
                                JOIN t_terima_ko ON t_terima_ko.nomor_produksi = t_mutasi_keluar.nomor_produksi
                                WHERE t_mutasi_keluar.pengajuan_repacking_id = t_pengajuan_repacking.pengajuan_repacking_id 
                                    AND t_terima_ko.produk_id = t_pengajuan_repacking_detail.produk_id) t) 
                            AS mutasi_keluar", // 7
                            "(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( 
                                SELECT t_terima_mutasi.terima_mutasi_id, t_terima_mutasi.nomor_produksi FROM t_terima_mutasi 
                                JOIN t_terima_ko ON t_terima_ko.nomor_produksi = t_terima_mutasi.nomor_produksi
                                WHERE t_terima_mutasi.reff_no2 = t_pengajuan_repacking.kode 
                                    AND t_terima_ko.produk_id = t_pengajuan_repacking_detail.produk_id) t) 
                            AS terima_mutasi", // 8
                            "(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT t_hasil_repacking.hasil_repacking_id ,t_hasil_repacking.nomor_produksi FROM t_hasil_repacking JOIN map_terimamutasi_hasilrepacking ON map_terimamutasi_hasilrepacking.hasil_repacking_id = t_hasil_repacking.hasil_repacking_id WHERE map_terimamutasi_hasilrepacking.pengajuan_repacking_id = t_pengajuan_repacking.pengajuan_repacking_id GROUP BY 1,2 ) t) AS nomor_produksi",
                            "t_pengajuan_repacking_detail.produk_id", // 9
                            "(SELECT array_to_json(array_agg(row_to_json(t))) FROM (SELECT t_terima_ko.nomor_produksi 
                            FROM map_terimamutasi_hasilrepacking 
                            JOIN t_terima_ko ON map_terimamutasi_hasilrepacking.nomor_produksi_baru = t_terima_ko.nomor_produksi
                            WHERE map_terimamutasi_hasilrepacking.pengajuan_repacking_id = t_pengajuan_repacking.pengajuan_repacking_id) t) AS terima_gudang_kembali",  // 10
                            ];
        $param['join']= ['JOIN t_pengajuan_repacking ON t_pengajuan_repacking.pengajuan_repacking_id = t_pengajuan_repacking_detail.pengajuan_repacking_id'];
        $param['order']= ['t_pengajuan_repacking.pengajuan_repacking_id DESC'];
        return $param;
    }
    public function actionStatus(){
		$model = new \app\models\TPengajuanRepackingDetail();
        if(\Yii::$app->request->get('dt')=='table-informasi'){
			$param = $this->actionStatusQuery();
            return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('status',['model'=>$model]);
	}
    public function actionStatusPrint(){
		$this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TPengajuanRepackingDetail();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes = !empty($_GET['TPengajuanRepackingDetail'])?$_GET['TPengajuanRepackingDetail']:null;
		$paramprint['judul'] = Yii::t('app', 'Status Permintaan Repacking');
		if((!empty($model->tgl_awal)) && (!empty($model->tgl_akhir))){
			$paramprint['judul2'] = "Periode Tanggal ". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)." sd ".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir);
		}
		if($caraprint == 'PRINT'){
			return $this->renderPartial('printStatus',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->renderPartial('printStatus',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->renderPartial('printStatus',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
    
    
    public function actionKirimgudang(){
        $model = new \app\models\THasilRepacking();
        $modRandom = new \app\models\THasilRepackingRandom();
        $modProduk = new \app\models\MBrgProduk();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->keterangan = "-";
        $model->jenis_palet = "Biasa";
        $modProduksi = new \app\models\TProduksi();
		$modProduksi->tanggal_produksi = "";
        
        if(isset($_GET['hasil_repacking_id'])){
            $model = \app\models\THasilRepacking::findOne($_GET['hasil_repacking_id']);
            $modRandom = \app\models\THasilRepackingRandom::find()->where(['hasil_repacking_id'=>$model->hasil_repacking_id])->all();
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->nomor_urut_produksi = substr($model->nomor_produksi, -6);
			$model->qty_m3_display = number_format($model->qty_m3,4);
			$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
			$modProduksi->tanggal_produksi = \app\components\DeltaFormatter::formatDateTimeForUser2($modProduksi->tanggal_produksi);
        }
        
        if( Yii::$app->request->post('THasilRepacking')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_hasil_repacking
                $success_2 = true; // t_hasil_repacking_random
				$success_3 = false; // t_produksi
				$success_4 = true; // map_terimamutasi_hasilrepacking
                $model->load(\Yii::$app->request->post());
                $modProduksi->load(\Yii::$app->request->post());
				$model->kode = \app\components\DeltaGenerator::kodeHasilRepacking();
				$model->nomor_produksi = $modProduksi->nomor_produksi;
				$model->tanggal_produksi = $modProduksi->tanggal_produksi;
				$model->p = isset($_POST['MBrgProduk']['produk_p'])?$_POST['MBrgProduk']['produk_p']:"";
				$model->l = isset($_POST['MBrgProduk']['produk_l'])?$_POST['MBrgProduk']['produk_l']:"";
				$model->t = isset($_POST['MBrgProduk']['produk_t'])?$_POST['MBrgProduk']['produk_t']:"";
				$model->p_satuan = isset($_POST['MBrgProduk']['produk_p_satuan'])?$_POST['MBrgProduk']['produk_p_satuan']:"";
				$model->l_satuan = isset($_POST['MBrgProduk']['produk_l_satuan'])?$_POST['MBrgProduk']['produk_l_satuan']:"";
				$model->t_satuan = isset($_POST['MBrgProduk']['produk_t_satuan'])?$_POST['MBrgProduk']['produk_t_satuan']:"";
				$model->qty_kecil = isset($_POST['THasilRepacking']['qty_kecil'])?$_POST['THasilRepacking']['qty_kecil']:"";
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
						if(isset($_POST['TTerimaKoKd'])){
							foreach($_POST['TTerimaKoKd'] as $i => $detail){
								$modRandom = new \app\models\THasilRepackingRandom();
								$modRandom->attributes = $detail;
								$modRandom->hasil_repacking_id = $model->hasil_repacking_id;
								if($modRandom->validate()){
									$success_2 &= $modRandom->save();
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
							$success_3 = $modProduksi->save();
						}else{
                            $errmsg = \yii\widgets\ActiveForm::validate($modProduksi);
                            echo "<pre>";
                            print_r($errmsg);
                            exit;
                        }
						// end t_produksi insert 
                        
                        // start map_terimamutasi_hasilrepacking insert 
                        if(isset($_POST['MapTerimamutasiHasilrepacking'])){
                            foreach($_POST['MapTerimamutasiHasilrepacking'] as $iv => $map){
                                $modMap = new \app\models\MapTerimamutasiHasilrepacking();
                                $modMap->attributes = $map;
                                $modMap->hasil_repacking_id = $model->hasil_repacking_id;
                                $modMap->nomor_produksi_baru = $modProduksi->nomor_produksi;
                                if($modMap->validate()){
                                    $success_4 &= $modMap->save();
                                }
                                
                            }
                        }
                        // end map_terimamutasi_hasilrepacking insert 
                    }
                }
                
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>3";
//				print_r($success_3);
//				echo "<pre>3";
//				print_r($success_4);
//				exit;
                
                if ($success_1 && $success_2 && $success_3 && $success_4) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['kirimgudang','success'=>1,'hasil_repacking_id'=>$model->hasil_repacking_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        
        return $this->render('kirimgudang',['model'=>$model,'modRandom'=>$modRandom,'modProduksi'=>$modProduksi,'modProduk'=>$modProduk]);
	}
    public function actionGetPengajuanRepacking(){
		if(\Yii::$app->request->isAjax){
            $data = [];
			$pengajuan_repacking_id = \Yii::$app->request->post('pengajuan_repacking_id');
            if($pengajuan_repacking_id){
                $modPengajuan = \app\models\TPengajuanRepacking::findOne($pengajuan_repacking_id);
                $data = $modPengajuan->attributes;
                $data['dibuat_permintaan'] = \app\models\MPegawai::findOne( \app\models\MUser::findOne($modPengajuan->created_by)->pegawai_id )->pegawai_nama;
            }
			return $this->asJson($data);
        }
    }
    function actionGetItemsRandomByPk(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data = []; $data['html'] = '';
			$modDetails = \app\models\THasilRepackingRandom::find()->where(['hasil_repacking_id'=>$id])->all();
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
					$detail->kapasitas_kubikasi_display = ($detail->kapasitas_kubikasi!=0)? number_format($detail->kapasitas_kubikasi,4) :0;
					$data['html'] .= $this->renderPartial('@app/modules/gudang/views/penerimaanko/_addItem',['modDetail'=>$detail,'disabled'=>true]);
                }
            }
            return $this->asJson($data);
        }
    }
    public function actionDaftarAfterSavePengiriman(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\THasilRepacking::tableName();
				$param['pk']= $param['table'].".".\app\models\THasilRepacking::primaryKey()[0];
				$param['column'] = [$param['table'].'.hasil_repacking_id',
									$param['table'].'.kode',
									$param['table'].'.nomor_produksi',
									'm_brg_produk.produk_kode',
									'm_brg_produk.produk_nama',
									$param['table'].'.tanggal',
									$param['table'].'.tanggal_produksi',
									$param['table'].'.qty_kecil',
									$param['table'].'.qty_kecil_satuan',
									$param['table'].'.qty_m3',
                                    't_terima_ko.tbko_id'];
				$param['join']= ['JOIN m_brg_produk ON m_brg_produk.produk_id = '.$param['table'].'.produk_id
                                  LEFT JOIN t_terima_ko ON t_terima_ko.nomor_produksi = t_hasil_repacking.nomor_produksi'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSavePengiriman');
        }
    }
    public function actionInfoKembaligudang($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\THasilRepacking::findOne($id);
            $map = \app\models\MapTerimamutasiHasilrepacking::findOne(['hasil_repacking_id'=>$model->hasil_repacking_id]);
            $modPermintaan = \app\models\TPengajuanRepacking::findOne($map->pengajuan_repacking_id);
            $model->kode_permintaan = $modPermintaan->kode;
            $model->dibuat_permintaan = \app\models\MPegawai::findOne( \app\models\MUser::findOne($modPermintaan->created_by)->pegawai_id )->pegawai_nama;
            $model->keperluan_permintaan = $modPermintaan->keperluan;
            $model->keterangan_permintaan = $modPermintaan->keterangan;
			$modRandom = \app\models\THasilRepackingRandom::find()->where(['hasil_repacking_id'=>$id])->all();
			$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
			$modProduk = \app\models\MBrgProduk::findOne($model->produk_id);
			return $this->renderAjax('infoKembaligudang',['model'=>$model,'modRandom'=>$modRandom,'modProduksi'=>$modProduksi,'modProduk'=>$modProduk]);
		}
    }
    
    public function actionPaletditerima(){
        if(\Yii::$app->request->isAjax){
            if(\Yii::$app->request->get('dt')=='table-palet'){
                $param['table']= \app\models\TTerimaMutasi::tableName();
                $param['pk']= \app\models\TTerimaMutasi::primaryKey()[0];
                $param['column'] = ['terima_mutasi_id','produk_group',"CONCAT('<b>',reff_no2,'</b><br>',t_pengajuan_repacking.tanggal) AS permintaan","t_terima_mutasi.nomor_produksi",
                                    "CONCAT('<b>',t_terima_mutasi.nomor_produksi,'</b><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ',m_brg_produk.produk_nama) AS kbj","m_brg_produk.produk_dimensi",
                                    "t_terima_ko.qty_kecil","t_terima_ko.qty_m3","t_mutasi_keluar.tanggal AS tanggal_mutasi","t_terima_mutasi.tanggal AS tanggal_mutasi"];
                $param['join'] = ["JOIN t_produksi ON t_produksi.nomor_produksi = t_terima_mutasi.nomor_produksi 
                                   JOIN m_brg_produk on m_brg_produk.produk_id = t_produksi.produk_id
                                   JOIN t_pengajuan_repacking on t_pengajuan_repacking.kode = t_terima_mutasi.reff_no2
                                   JOIN t_mutasi_keluar on t_mutasi_keluar.nomor_produksi = t_terima_mutasi.nomor_produksi
                                   JOIN t_terima_ko on t_terima_ko.nomor_produksi = t_terima_mutasi.nomor_produksi"];
//                $param['where'] = "";
                return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
            }
            return $this->renderAjax('paletditerima');
        }
	}
    
    public function actionPickpalet(){
		if(\Yii::$app->request->isAjax){
			$nomor_produksi = \Yii::$app->request->post('nomor_produksi');
			$data['html'] = ''; $data['nomor_produksi'] = $nomor_produksi;
            $model = new \app\models\MapTerimamutasiHasilrepacking();
			if(!empty($nomor_produksi)){
                $modTerimaMutasi = \app\models\TTerimaMutasi::findOne(['nomor_produksi'=>$nomor_produksi]);
                $modMutasiKeluar = \app\models\TMutasiKeluar::findOne(['nomor_produksi'=>$nomor_produksi]);
                $modPengajuan = \app\models\TPengajuanRepacking::findOne(['kode'=>$modTerimaMutasi->reff_no2]);
                $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$nomor_produksi]);
                $modProduk = \app\models\MBrgProduk::findOne($modProduksi->produk_id);
                $modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$nomor_produksi]);
                $model->pengajuan_repacking_id = $modPengajuan->pengajuan_repacking_id;
                $model->mutasi_keluar_id = $modMutasiKeluar->mutasi_keluar_id;
                $model->terima_mutasi_id = $modTerimaMutasi->terima_mutasi_id;
                $model->nomor_produksi_lama = $nomor_produksi;
                $model->qty_kecil = $modTerima->qty_kecil;
                $model->qty_m3 = $modTerima->qty_m3;
                $data['html'] .= $this->renderPartial('itemPalet',['model'=>$model,'modTerimaMutasi'=>$modTerimaMutasi,'modMutasiKeluar'=>$modMutasiKeluar,
                                                                   'modPengajuan'=>$modPengajuan,'modProduk'=>$modProduk]);
			}
			return $this->asJson($data);
		}
	}
    
    public function actionGetPaletAsal(){
        if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data = []; $data['html'] = '';
			$modDetails = \app\models\MapTerimamutasiHasilrepacking::find()->where(['hasil_repacking_id'=>$id])->all();
            if(count($modDetails)>0){
                foreach($modDetails as $i => $detail){
                    $modTerimaMutasi = \app\models\TTerimaMutasi::findOne(['nomor_produksi'=>$detail->nomor_produksi_lama]);
                    $modMutasiKeluar = \app\models\TMutasiKeluar::findOne(['nomor_produksi'=>$detail->nomor_produksi_lama]);
                    $modPengajuan = \app\models\TPengajuanRepacking::findOne(['kode'=>$modTerimaMutasi->reff_no2]);
                    $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$detail->nomor_produksi_lama]);
                    $modProduk = \app\models\MBrgProduk::findOne($modProduksi->produk_id);
                    $modTerima = \app\models\TTerimaKo::findOne(['nomor_produksi'=>$detail->nomor_produksi_lama]);
                    $detail->qty_kecil = $modTerima->qty_kecil;
                    $detail->qty_m3 = $modTerima->qty_m3;
					$data['html'] .= $this->renderPartial('itemPalet',['model'=>$detail,'modTerimaMutasi'=>$modTerimaMutasi,'modMutasiKeluar'=>$modMutasiKeluar,
                                                                        'modPengajuan'=>$modPengajuan,'modProduk'=>$modProduk]);
                }
            }
            return $this->asJson($data);
        }
	}
    
    public function actionPrintKartuBarang(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\THasilRepacking::findOne($_GET['id']);
		$modProduksi = \app\models\TProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'PRODUCT DETAILS');
		$barcodecontent = $model->nomor_produksi;
		if($caraprint == 'PRINT'){
			return $this->render('@app/modules/gudang/views/penerimaanko/printKartuBarang',['model'=>$model,'paramprint'=>$paramprint,'barcodecontent'=>$barcodecontent,'modProduksi'=>$modProduksi]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('@app/modules/gudang/views/penerimaanko/printKartuBarang',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('@app/modules/gudang/views/penerimaanko/printKartuBarang',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
}
