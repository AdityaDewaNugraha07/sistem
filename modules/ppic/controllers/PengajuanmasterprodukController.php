<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\components\SSP;
use app\models\TPengajuanMasterproduk;
use app\models\TPengajuanMasterprodukDetail;
use yii\helpers\Json;
use app\components\Params;
use app\models\TPengajuanDrpDetail;
use yii\db\Exception;

class PengajuanmasterprodukController extends DeltaBaseController
{
    
	public $defaultAction = 'index';

    public function actionIndex(){
        $model = new \app\models\TPengajuanMasterproduk();
        $modDetail = new \app\models\TPengajuanMasterprodukDetail();
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->status_pengajuan = 'Normal';
        $model->prepared_by = \app\components\Params::DEFAULT_PEGAWAI_ID_NAFIS; // kadep ppic
        $model->reviewed_by = \app\components\Params::DEFAULT_PEGAWAI_ID_ILHAM; // kadiv operasional
        $model->approved_by = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG; // direktur
        if(isset($_GET['pengajuan_masterproduk_id'])){
            $model = TPengajuanMasterproduk::findOne($_GET['pengajuan_masterproduk_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
        }
            
        if( Yii::$app->request->post('TPengajuanMasterproduk')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                // $success_0 = false; // cek sudah punya status approval
                $success_1 = false; // t_pengajuan_masterproduk
                $success_2 = true;  // t_pengajuan_masterproduk_detail
                $success_3 = false; // t_approval
                $model->load(\Yii::$app->request->post());
                if(!isset($_GET['edit'])){
                    $model->kode = \app\components\DeltaGenerator::kodePengajuanMasterProduk();
                    $model->approval_status = 'Not Confirmed';
                }
                    
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;

                        if(!empty($_POST['TPengajuanMasterprodukDetail'])){
                            if(isset($_GET['edit'])){
                                \app\models\TPengajuanMasterprodukDetail::deleteAll("pengajuan_masterproduk_id = ".$model->pengajuan_masterproduk_id);
                            }
                            
                            foreach($_POST['TPengajuanMasterprodukDetail'] as $i => $detail){
                                $modDetail = new \app\models\TPengajuanMasterprodukDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->pengajuan_masterproduk_id = $model->pengajuan_masterproduk_id;
                                $modDetail->glue = ($detail['glue'] == "null" || $detail['glue'] == null || $detail['glue'] == "")?null:$detail['glue'];
                                $modDetail->grade = ($detail['grade'] == "null" || $detail['grade'] == null || $detail['grade'] == "")?null:$detail['grade'];
                                $modDetail->jenis_kayu = ($detail['jenis_kayu'] == "null" || $detail['jenis_kayu'] == null || $detail['jenis_kayu'] == "")?null:$detail['jenis_kayu'];
                                $modDetail->profil_kayu = ($detail['profil_kayu'] == "null" || $detail['profil_kayu'] == null || $detail['profil_kayu'] == "")?null:$detail['profil_kayu'];
                                $modDetail->kondisi_kayu = ($detail['kondisi_kayu'] == "null" || $detail['kondisi_kayu'] == null || $detail['kondisi_kayu'] == "")?null:$detail['kondisi_kayu'];
                                $modDetail->diameter_range = ($detail['diameter_range'] == "null" || $detail['diameter_range'] == null || $detail['diameter_range'] == "")?null:$detail['diameter_range'];
                                $modDetail->warna_kayu = ($detail['warna_kayu'] == "null" || $detail['warna_kayu'] == null || $detail['warna_kayu'] == "")?null:$detail['warna_kayu'];
                                // $file1 = \yii\web\UploadedFile::getInstance($modDetail, '['.$i.']produk_gbr');
                                $file1 = $_FILES['TPengajuanMasterprodukDetail']['name'][$i]['produk_gbr'];
                                if($modDetail->validate()){
                                    if(!empty($file1)){ 
                                        $randomstring = Yii::$app->getSecurity()->generateRandomString(4); 
                                        $dir_path = Yii::$app->basePath.'/web/uploads/gud/req_produk';
                                        if(!is_dir($dir_path)){ 
                                            if(!is_dir(Yii::$app->basePath.'/web/uploads/gud')){
                                                mkdir(Yii::$app->basePath.'/web/uploads/gud');
                                            }
                                            mkdir($dir_path); 
                                        } 
                                        $tmp = $_FILES['TPengajuanMasterprodukDetail']['tmp_name'][$i]['produk_gbr'];
                                        $file_path = $dir_path.'/'.date('Ymd_His').'-'.$modDetail->produk_group.'-'.$randomstring.'.' . $file1;
                                        // $file1->saveAs($file_path,false);
                                        if(move_uploaded_file($tmp, $file_path)){
                                            $modDetail->produk_gbr = date('Ymd_His').'-'.$modDetail->produk_group.'-'.$randomstring.'.' .$file1;
                                        }
                                    } else {
                                        if(isset($_GET['edit'])){
                                            $modDetail->produk_gbr = ($detail['produk_gbr_lama'] == 'null' || $detail['produk_gbr_lama'] == 'undefined')?null:$detail['produk_gbr_lama'];
                                        }
                                    }
                                    
                                    if($modDetail->save()){
                                        $success_2 &= true;
                                    }else{
                                        $success_2 = false;
                                    }
                                }else{
                                    // $kode = $detail['produk_kode'];
                                    // $exist = Yii::$app->db->createCommand("
                                    //                 select * from t_pengajuan_masterproduk_detail 
                                    //                 join t_pengajuan_masterproduk on t_pengajuan_masterproduk.pengajuan_masterproduk_id = t_pengajuan_masterproduk_detail.pengajuan_masterproduk_id
                                    //                 where produk_kode = '".$kode."' and (approval_status = 'REJECTED' OR cancel_transaksi_id is not null)")->queryAll();
                                    // if(count($exist) > 0){
                                    //     $success_2 = true;
                                    // }
                                    $success_2 = false;
                                }
                            }
                                
                            // START Create Approval
                            $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
                            if(count($modelApproval)>0){ // exist
                                if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
                                    $success_3 = $this->saveApproval($model);
                                }else{
                                    $success_3 = $this->saveApproval($model);
                                }
                            }else{ // not exist
                                $success_3 = $this->saveApproval($model);
                            }
                            // END Create Approval
                        }
                    }
                }

                    // echo '1';
                    // print_r($success_1);
                    // echo '2';
                    // print_r($success_2);
                    // echo '3';
                    // print_r($success_3);
                    // print_r($modDetail);
                    // exit;
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'pengajuan_masterproduk_id'=>$model->pengajuan_masterproduk_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        return $this->render('index',['model'=>$model, 'modDetail'=>$modDetail]);
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

		if($model->reviewed_by){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->reviewed_by;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 2;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}

        if ($model->approved_by) {
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->approved_by;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 3;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
        }
		return $success;
	}

    public function actionCreate(){
        if(Yii::$app->request->isAjax){
            $data = [];
            $modDetail = new \app\models\TPengajuanMasterprodukDetail();
            $modDetail->produk_satuan_besar = \app\components\Params::DEFAULT_PRODUK_SATUAN_BESAR;
			$modDetail->produk_satuan_kecil = \app\components\Params::DEFAULT_PRODUK_SATUAN_KECIL;
			$modDetail->produk_qty_satuan_kecil = 0;
			$modDetail->produk_p = 0;
			$modDetail->produk_l = 0;
			$modDetail->produk_t = 0;
			$modDetail->produk_p_satuan = \app\components\Params::DEFAULT_PRODUK_SATUAN_DIMENSI;
			$modDetail->produk_l_satuan = \app\components\Params::DEFAULT_PRODUK_SATUAN_DIMENSI;
			$modDetail->produk_t_satuan = \app\components\Params::DEFAULT_PRODUK_SATUAN_DIMENSI;
            $modDetail->pengajuan_masterproduk_id = 0;

            if( Yii::$app->request->post('TPengajuanMasterprodukDetail')){
                $transaction = \Yii::$app->db->beginTransaction();
                $post = Yii::$app->request->post('TPengajuanMasterprodukDetail');
                try {
                    $success_1 = false;  // t_pengajuan_masterproduk_detail
                    
                    $modDetail->load(Yii::$app->request->post());
                    if($modDetail->validate()){
                        if($modDetail->save()){
                            $success_1 = true;
                        }          
                    } 
                    // else {
                    //     $kode = $post['produk_kode'];
                    //     $exist = Yii::$app->db->createCommand("
                    //                     select * from t_pengajuan_masterproduk_detail 
                    //                     join t_pengajuan_masterproduk on t_pengajuan_masterproduk.pengajuan_masterproduk_id = t_pengajuan_masterproduk_detail.pengajuan_masterproduk_id
                    //                     where produk_kode = '".$kode."' and (approval_status = 'REJECTED' OR cancel_transaksi_id is not null)")->queryAll();
                    //     if(count($exist) > 0){
                    //         $success_1 = true;
                    //     }
                    // }

                    // print_r($modDetail->produk_p); exit;
                    if ($success_1) {
                        // $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Pengajuan Berhasil Ditambahkan');
                        $data['data'] = [
                            'pengajuan_masterproduk_detail_id' => $modDetail->pengajuan_masterproduk_detail_id,
                            'produk_group' => $modDetail->produk_group,
                            'produk_kode' => $modDetail->produk_kode,
                            'produk_nama' => $modDetail->produk_nama,
                            'produk_dimensi' => $modDetail->produk_dimensi,
                            'jenis_kayu' => $modDetail->jenis_kayu,
                            'grade' => $modDetail->grade,
                            'warna_kayu' => $modDetail->warna_kayu,
                            'glue' => $modDetail->glue, 
                            'profil_kayu' => $modDetail->profil_kayu, 
                            'kondisi_kayu' => $modDetail->kondisi_kayu, 
                            // 'produk_gbr' => $modDetail->produk_gbr,
                            'produk_p' => $modDetail->produk_p,
                            'produk_l' => $modDetail->produk_l,
                            'produk_t' => $modDetail->produk_t,
                            'produk_p_satuan' => $modDetail->produk_p_satuan,
                            'produk_l_satuan' => $modDetail->produk_l_satuan,
                            'produk_t_satuan' => $modDetail->produk_t_satuan,
                            'produk_satuan_besar' => $modDetail->produk_satuan_besar,
                            'produk_satuan_kecil' => $modDetail->produk_satuan_kecil,
                            'produk_qty_satuan_kecil' => $modDetail->produk_qty_satuan_kecil,
                            'kapasitas_kubikasi' => $modDetail->kapasitas_kubikasi,
                            'diameter_range' => $modDetail->diameter_range,
                        ];
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
            return $this->renderAjax('create',['modDetail'=>$modDetail]);
        }
	}
    
    public function actionEdit($datas, $tr_id){
		if(Yii::$app->request->isAjax){
            $datas = json_decode($datas, true);
            $modDetail = new \app\models\TPengajuanMasterprodukDetail();
            $modDetail->pengajuan_masterproduk_detail_id = $datas['pengajuan_masterproduk_detail_id'];
            $modDetail->produk_satuan_besar = $datas['produk_satuan_besar'];
			$modDetail->produk_satuan_kecil = $datas['produk_satuan_kecil'];
			$modDetail->produk_qty_satuan_kecil = $datas['produk_qty_satuan_kecil'];
			$modDetail->produk_p = $datas['produk_p'];
			$modDetail->produk_l = $datas['produk_l'];
			$modDetail->produk_t = $datas['produk_t'];
			$modDetail->produk_p_satuan = $datas['produk_p_satuan'];
			$modDetail->produk_l_satuan = $datas['produk_l_satuan'];
			$modDetail->produk_t_satuan = $datas['produk_t_satuan'];
            $modDetail->pengajuan_masterproduk_id = 0;
            $modDetail->produk_kode = $datas['produk_kode'];
            $modDetail->produk_group = $datas['produk_group'];
            $modDetail->produk_nama = $datas['produk_nama'];
            $modDetail->produk_dimensi = $datas['produk_dimensi'];
            // $modDetail->produk_gbr = $datas['produk_gbr'];
            $modDetail->kapasitas_kubikasi = $datas['kapasitas_kubikasi'];
            $modDetail->jenis_kayu = $datas['jenis_kayu'];
            $modDetail->grade = $datas['grade']?$datas['grade']:'';
            $modDetail->glue = $datas['glue']?$datas['glue']:'';
            $modDetail->profil_kayu = $datas['profil_kayu'];
            $modDetail->kondisi_kayu = $datas['kondisi_kayu'];
            $modDetail->diameter_range = $datas['diameter_range'];
            $modDetail->warna_kayu = $datas['warna_kayu'];

            if( Yii::$app->request->post('TPengajuanMasterprodukDetail')){
                $post = Yii::$app->request->post('TPengajuanMasterprodukDetail');
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_0 = false;

                    $modDetail->load(Yii::$app->request->post());

                    $detail_id = $datas['pengajuan_masterproduk_detail_id'];
                    if($detail_id !== null){
                        $details = TPengajuanMasterprodukDetail::findOne($detail_id);
                        if($details !== null){
                            // if( $post['produk_p_satuan'] !== $details->produk_p_satuan || 
                            //     $post['produk_l_satuan'] !== $details->produk_l_satuan ||
                            //     $post['produk_t_satuan'] !== $details->produk_t_satuan ){
                            //     $success_0 = true;
                            // } else {
                            //     $success_0 = true;
                            // }
                            $success_0 = true;
                        } else {
                            if($modDetail->validate()){
                                if($modDetail->save()){
                                    $success_0 = true;
                                }
                            }else{
                                $data['message_validate']=\yii\widgets\ActiveForm::validate($modDetail); 
                            }
                        }
                    }

                    // print_r($datas['pengajuan_masterproduk_detail_id']); exit;

                    if ($success_0) {
                        $data['status'] = true;
                        $data['data'] = [
                            'pengajuan_masterproduk_detail_id' => $modDetail->pengajuan_masterproduk_detail_id,
                            'produk_group' => $modDetail->produk_group,
                            'produk_kode' => $modDetail->produk_kode,
                            'produk_nama' => $modDetail->produk_nama,
                            'produk_dimensi' => $modDetail->produk_dimensi,
                            'jenis_kayu' => $modDetail->jenis_kayu,
                            'grade' => $modDetail->grade,
                            'warna_kayu' => $modDetail->warna_kayu,
                            'glue' => $modDetail->glue, 
                            'profil_kayu' => $modDetail->profil_kayu, 
                            'kondisi_kayu' => $modDetail->kondisi_kayu, 
                            // 'produk_gbr' => $modDetail->produk_gbr,
                            'produk_p' => $modDetail->produk_p,
                            'produk_l' => $modDetail->produk_l,
                            'produk_t' => $modDetail->produk_t,
                            'produk_p_satuan' => $modDetail->produk_p_satuan,
                            'produk_l_satuan' => $modDetail->produk_l_satuan,
                            'produk_t_satuan' => $modDetail->produk_t_satuan,
                            'produk_satuan_besar' => $modDetail->produk_satuan_besar,
                            'produk_satuan_kecil' => $modDetail->produk_satuan_kecil,
                            'produk_qty_satuan_kecil' => $modDetail->produk_qty_satuan_kecil,
                            'kapasitas_kubikasi' => $modDetail->kapasitas_kubikasi,
                            'diameter_range' => $modDetail->diameter_range,
                        ];
                    } else {
                        $transaction->rollback();
                        $data['status'] = 'salah';
                    }
                    // print_r($data['data']);exit;
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('edit',['modDetail'=>$modDetail, 'tr_id'=>$tr_id, 'datas'=>$datas]);
		}
	}

    function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
            if(!empty($id)){
                $model = TPengajuanMasterproduk::findOne($id);
                $modDetails = TPengajuanMasterprodukDetail::find()->where(['pengajuan_masterproduk_id'=>$model->pengajuan_masterproduk_id])->all();
            }else{
                $model = [];
                $modDetails = [];
            }

            $produkData = [];
            if(count($modDetails) > 0){
                foreach($modDetails as $i => $detail){
                    if ( (preg_match('/^(.*)_rejected\d*$/', $detail->produk_kode, $kode)) || (preg_match('/^(.*)_aborted\d*$/', $detail->produk_kode, $kode))) {
                        $final_kode = $kode[1];
                    } else {
                        $final_kode =$detail->produk_kode;
                    }
                    if ( (preg_match('/^(.*)_rejected\d*$/', $detail->produk_nama, $nama)) || (preg_match('/^(.*)_aborted\d*$/', $detail->produk_nama, $nama))) {
                        $final_nama = $nama[1];
                    } else {
                        $final_nama =$detail->produk_nama;
                    }
                    $produkData[] = [
                        'pengajuan_masterproduk_detail_id' =>$detail->pengajuan_masterproduk_detail_id,
                        'produk_group' => $detail->produk_group,
                        'produk_kode' => $final_kode,
                        'produk_nama' => $final_nama,
                        'produk_dimensi' => $detail->produk_dimensi,
                        'jenis_kayu' => $detail->jenis_kayu,
                        'grade' => $detail->grade,
                        'warna_kayu' => $detail->warna_kayu,
                        'glue' => $detail->glue, 
                        'profil_kayu' => $detail->profil_kayu, 
                        'kondisi_kayu' => $detail->kondisi_kayu, 
                        'produk_gbr' => $detail->produk_gbr,
                        'produk_p' => $detail->produk_p,
                        'produk_l' => $detail->produk_l,
                        'produk_t' => $detail->produk_t,
                        'produk_p_satuan' => $detail->produk_p_satuan,
                        'produk_l_satuan' => $detail->produk_l_satuan,
                        'produk_t_satuan' => $detail->produk_t_satuan,
                        'produk_satuan_besar' => $detail->produk_satuan_besar,
                        'produk_satuan_kecil' => $detail->produk_satuan_kecil,
                        'produk_qty_satuan_kecil' => $detail->produk_qty_satuan_kecil,
                        'kapasitas_kubikasi' => $detail->kapasitas_kubikasi,
                        'diameter_range' => $detail->diameter_range,
                    ];
                }
            }
            $data = $produkData;
            
            return $this->asJson($data);
        }
    }

    public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
            if(Yii::$app->request->get('dt') === 'modal-aftersave'){
                $param['table']= \app\models\TPengajuanMasterproduk::tableName();
                $param['pk']= \app\models\TPengajuanMasterproduk::primaryKey()[0];
                $param['column'] =  [   $param['table'].'.pengajuan_masterproduk_id',
                                        $param['table'].'.kode',
                                        $param['table'].'.tanggal',
                                        $param['table'].'.keperluan',
                                        $param['table'].'.status_pengajuan',
                                        $param['table'].'.keterangan',
                                        $param['table'].'.approval_status',
                                        $param['table'].'.cancel_transaksi_id'
                                    ];
                return Json::encode(SSP::complex( $param ));
            }
            
            return $this->renderAjax('daftarAfterSave');
        }
	}

    public function actionInfo($id){ //,$disableAction=null
		if(Yii::$app->request->isAjax){
			$modDetail = TPengajuanMasterprodukDetail::findOne($id);
            $model = TPengajuanMasterproduk::findOne($modDetail->pengajuan_masterproduk_id);
			return $this->renderAjax('info',['modDetail'=>$modDetail,'model'=>$model]); //,'disableAction'=>$disableAction
		}
	}

    public function actionCancelTransaksi($id){
		if(\Yii::$app->request->isAjax){
            $model = TPengajuanMasterproduk::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_pengajuan_masterproduk
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $model->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
							$model->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
                            if($model->validate()){
								$success_2 = $model->save();

                                // ubah produk_kode dan produk_nama agar unique
                                $modPengajuanDet = \app\models\TPengajuanMasterprodukDetail::find()->where(['pengajuan_masterproduk_id'=>$model->pengajuan_masterproduk_id])->all();
                                foreach($modPengajuanDet as $i => $detail){
                                    // produk_kode
                                    $kode = $detail['produk_kode'].'_aborted';
                                    $counter = 0;
                                    while (\app\models\TPengajuanMasterprodukDetail::find()->where(['produk_kode' => $kode . $counter])->exists()) {
                                        $counter++;
                                    }
                                    $kode .= $counter;
                                    $sql_kode = "update t_pengajuan_masterproduk_detail set produk_kode = '".$kode."' where pengajuan_masterproduk_detail_id = ".$detail['pengajuan_masterproduk_detail_id'];
                                    Yii::$app->db->createCommand($sql_kode)->execute();
                                    // produk_nama
                                    $nama = $detail['produk_nama'].'_aborted';
                                    $counter = 0;
                                    while (\app\models\TPengajuanMasterprodukDetail::find()->where(['produk_nama' => $nama . $counter])->exists()) {
                                        $counter++;
                                    }
                                    $nama .= $counter;
                                    $sql_nama = "update t_pengajuan_masterproduk_detail set produk_nama = '".$nama."' where pengajuan_masterproduk_detail_id = ".$detail['pengajuan_masterproduk_detail_id'];
                                    Yii::$app->db->createCommand($sql_nama)->execute();
                                }
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel);
                    }

                    $sql_t_approval = "delete from t_approval where reff_no = '".$model->kode."' ";
                    $success_3 = Yii::$app->db->createCommand($sql_t_approval)->execute();

                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Transaksi Berhasil di Batalkan');
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}

			return $this->renderAjax('cancelTransaksi',['model'=>$model,'modCancel'=>$modCancel]);
		}
	}

    public function actionCekKode() {
        $kode = Yii::$app->request->post('kode');
        $exists = \app\models\MBrgProduk::find()->where(['produk_kode' => $kode])->exists();
        return $this->asJson(['exists' => $exists]);
    }

    public function actionPrint(){
		$this->layout = '@views/layouts/metronic/print';
		$model = TPengajuanMasterproduk::findOne($_GET['id']);
		$modDetail = TPengajuanMasterprodukDetail::find()->where(['pengajuan_masterproduk_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'PENGAJUAN MASTER PRODUK ');
		if($caraprint == 'PRINT'){
			return $this->render('print',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('print',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('print',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}

    // public function actionHapusDetail(){
    //     $id = Yii::$app->request->post('id');
    //     $modDetail = TPengajuanMasterprodukDetail::findOne($id);
    //     if(!empty($modDetail)){
    //         $file = $modDetail->produk_gbr;
    //         if($file != null || $file != ''){
    //             $file_path = Yii::$app->basePath . '/web/uploads/gud/produk/' . $file;
    //             if(file_exists($file_path)){
    //                 if (unlink($file_path)) {
    //                     return $this->asJson(['status'=>'success']);
    //                 } else {
    //                     return $this->asJson(['status'=>'error']);
    //                 }
    //             }
    //         }
    //     }
    // }
}
