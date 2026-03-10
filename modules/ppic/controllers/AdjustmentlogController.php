<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class AdjustmentlogController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TAdjustmentLog();
        $model->kode = "Auto Generate";
        $model->tanggal = date("d/m/Y");
        $model->status_approval = "Not Confirmed";
		$model->uraian = "";
        $model->by_approver1 = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
		$model->by_approver2 = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
		$modAttachment = new \app\models\TAttachment();

        if(isset($_GET['adjustment_log_id'])){
			$model = \app\models\TAdjustmentLog::findOne($_GET['adjustment_log_id']);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            
            $reff_no_loglist = $model->reff_no_loglist;
            $sql_reff_no_loglist = "select loglist_id from t_loglist where loglist_kode = '".$reff_no_loglist."'";
            $model->reff_no_loglist = Yii::$app->db->createCommand($sql_reff_no_loglist)->queryScalar();
            $reff_no_spk = $model->reff_no_spk;
            $sql_reff_no_spk = "select spk_shipping_id from t_spk_shipping where kode = '".$reff_no_spk."'";
            $model->reff_no_spk = Yii::$app->db->createCommand($sql_reff_no_spk)->queryScalar();

            //$model->by_approver1 = \app\models\MPegawai::findOne($model->by_approver1)->pegawai_nama;
			//$model->by_approver2 = \app\models\MPegawai::findOne($model->by_approver2)->pegawai_nama;
		}

        if( Yii::$app->request->post('TAdjustmentLog')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_adjustmentlog
                $success_2 = true; // t_attachment
                $success_3 = true; // t_approvel
                
                $model->load(\Yii::$app->request->post());
				
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeAdjustmentPenerimaanLog();
				}
                //echo "<pre>";
                //print_r($_POST);
                if($model->validate()){
                    $sql_reff_no_loglist = "select loglist_kode from t_loglist where loglist_id = ".$_POST['TAdjustmentLog']['reff_no_loglist']."";
                    $model->reff_no_loglist = Yii::$app->db->createCommand($sql_reff_no_loglist)->queryScalar();
                    $sql_reff_no_spk = "select kode from t_spk_shipping where spk_shipping_id = ".$_POST['TAdjustmentLog']['reff_no_spk']."";
                    $model->reff_no_spk = Yii::$app->db->createCommand($sql_reff_no_spk)->queryScalar();
                    //echo "<br>ref_no_loglist ".$model->reff_no_loglist;
                    //echo "<br>ref_no_spk ".$model->reff_no_spk;

                    if($model->save()){
                        $success_1 = true;						
						$dir_path = Yii::$app->basePath.'/web/uploads/ppic/adjustmentLog';
						if(isset($_FILES['TAttachment'])){
							$files = [];
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file1');
							foreach($files as $i => $file){
								if(!empty($file)){
									$modAttachment = new \app\models\TAttachment();
									$modAttachment->reff_no = $model->kode;
									$modAttachment->file_type = $file->type;
									$modAttachment->file_ext = $file->extension;
									$modAttachment->file_size = $file->size;
									$modAttachment->dir_path = $dir_path;
									$modAttachment->seq = ($i+1);
									$randomstring_attch = Yii::$app->getSecurity()->generateRandomString(4);
									if(!is_dir($dir_path)){ mkdir($dir_path); }
									$file_path = date('Ymd_His').'-attch-'.$randomstring_attch.'.'  . $file->extension;
									$file->saveAs($dir_path.'/'.$file_path);
									$modAttachment->file_name = $file_path;
									if($modAttachment->validate()){
										if($modAttachment->save()){
											$success_2 &= true;
										}else{
											$success_2 = false;
										}
									}else{
										$success_2 = false;
										$errmsg = $modAttachment->errors;
									}
								}
							}
						}
						
						// START Create Approval
						$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
						if(count($modelApproval)>0){ // edit mode
							if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
								$success_3 = $this->saveApproval($model, $modelApproval);
							}
							$success_3 = true;
						}else{ // insert mode
							$success_3 = $this->saveApproval($model, $modelApproval);
						}
						// END Create Approval
                    }
                }
                //echo "<br>success_1 = ".$success_1."<br>success_2 = ".$success_2."<br>success_3 = ".$success_3;
				//exit();
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'adjustment_log_id'=>$model->adjustment_log_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?(implode(",", array_values($errmsg)[0])):Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modAttachment'=>$modAttachment]);
	}
	
	public function saveApproval($model, $modelApproval){
		$success = true;

        $approver1 = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;
        $approver2 = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $approver1;
		//$kode = \app\components\DeltaGenerator::kodeAdjustmentPenerimaanLog();
        $modelApproval->reff_no = $model->kode;
        $tanggal = $model->tanggal;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();
		
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = $approver2;
		$modelApproval->reff_no = $model->kode;
		$modelApproval->tanggal_berkas = $model->tanggal;
		$modelApproval->level = 2;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();
		
		return $success;
	}

	public function actionDaftarAdjustmentLog(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-daftarAdjustmentLog'){
				$param['table']= \app\models\TAdjustmentLog::tableName();
				$param['pk']= $param['table'].".". \app\models\TAdjustmentLog::primaryKey()[0];
				$param['column'] = [$param['table'].'.adjustment_log_id',
									't_adjustment_log.kode as adjustmentlog_kode',
									$param['table'].'.tanggal',
                                    't_pengajuan_pembelianlog.kode as pembelianlog_kode',
                                    $param['table'].'.reff_no_loglist',
                                    $param['table'].'.reff_no_spk',
                                    $param['table'].'.jml_batang_loglist',
                                    $param['table'].'.jml_m3_loglist',
                                    $param['table'].'.jml_batang_terima',
                                    $param['table'].'.jml_m3_terima',
                                    $param['table'].'.uraian',
                                    $param['table'].'.status_approval',
									];
                $param['join'] = ['JOIN t_pengajuan_pembelianlog ON t_pengajuan_pembelianlog.pengajuan_pembelianlog_id = '.$param['table'].'.pengajuan_pembelianlog_id
                                    JOIN t_loglist ON t_loglist.loglist_kode = '.$param['table'].'.reff_no_loglist
                                    JOIN t_spk_shipping ON t_spk_shipping.kode = '.$param['table'].'.reff_no_spk'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('_daftarAdjustmentLog');
        }
    }
	
    function actionGetItemsById(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $edit = Yii::$app->request->post('edit');
			$model = \app\models\TAdjustmentLog::findOne($id);
            $data = [];
            if(!empty($id)){
                $attachments = \app\models\TAttachment::find()->where(['reff_no'=>$model->kode])->orderBy("seq ASC")->all();
            }
			$data['model'] = $model->attributes;

            if(count($attachments)>0){
                foreach($attachments as $i => $attachment){
                    $data['attch'][] = $attachment->attributes;
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionDeleteAttch($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
            $fileno = Yii::$app->request->get('fileno');
			$model = \app\models\TAttachment::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                        if( unlink($model->dir_path.'/'.$model->file_name) && $model->delete()){
                            $success_1 = true;
                        }else{
                            $data['message'] = Yii::t('app', 'Data gagal dihapus');
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                            $data['callback'] = "setNormalPickAttch({$fileno});";
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
			return $this->renderAjax('_deleteAttch',['id'=>$id,'tableid'=>$tableid,"actionname"=>"DeleteAttch",'fileno'=>$fileno]);
		}
	}

    public function actionCariCari() {
		if(\Yii::$app->request->isAjax){
			$pengajuan_pembelianlog_id = \Yii::$app->request->post('pengajuan_pembelianlog_id');
			$data = [];

            // t_loglist
            $data['loglist'] = "";
			if(!empty($pengajuan_pembelianlog_id)){
                $data['loglist'] .= $this->renderPartial('_loglist',['pengajuan_pembelianlog_id'=>$pengajuan_pembelianlog_id]);
            } else {
                $data['loglist'] .= "Data Pengajuan Pembelian tidak ditemukanx";
            }

            $sql_jml_batang_loglist = "select count(*) from t_loglist_detail a ". 
                                    "   left join t_loglist b on b.loglist_id = a.loglist_id ". 
                                    "   where b.pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."".
                                    "   ";
            $jml_batang_loglist = Yii::$app->db->createCommand($sql_jml_batang_loglist)->queryScalar();
            
            $sql_jml_m3_loglist = "select sum(volume_value) from t_loglist_detail a ". 
                                    "   left join t_loglist b on b.loglist_id = a.loglist_id ". 
                                    "   where b.pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."".
                                    "   ";
            $jml_m3_loglist = Yii::$app->db->createCommand($sql_jml_m3_loglist)->queryScalar();
            
            
            $data['jml_batang_loglist'] = $jml_batang_loglist;
            $data['jml_m3_loglist'] = $jml_m3_loglist;

            // t_loglist - area_pembelian
            $sql_area_pembelian = "select area_pembelian from t_loglist where pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."";
            $area_pembelian = Yii::$app->db->createCommand($sql_area_pembelian)->queryScalar();
            $data['area_pembelian'] = $area_pembelian;

            if ($area_pembelian == "Jawa") {
                $jml_batang_terima = 0;
                $jml_m3_terima = 0;
            } else {
                // ambil spk_shipping_id
                $sql_spk_shipping_id = "select spk_shipping_id from t_pengajuan_pembelianlog where pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."";
                $spk_shipping_id = Yii::$app->db->createCommand($sql_spk_shipping_id)->queryScalar();
                
                if ($spk_shipping_id > 0) {
                    // ambil jml_batang_terima dari t_terima_logalam
                    $sql_jml_batang_terima = "select count(*) from t_terima_logalam_detail a ". 
                                                "   join t_terima_logalam b on b.terima_logalam_id = a.terima_logalam_id ". 
                                                "   where b.spk_shipping_id = ".$spk_shipping_id."".
                                                "   and a.pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."".
                                                "   ";
                    $jml_batang_terima = Yii::$app->db->createCommand($sql_jml_batang_terima)->queryScalar();

                    // ambil jml_m3_terima dari t_terima_logalam
                    $sql_jml_m3_terima = "select sum(a.volume) from t_terima_logalam_detail a ". 
                                                "   join t_terima_logalam b on b.terima_logalam_id = a.terima_logalam_id ". 
                                                "   and a.pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."".
                                                "   where b.spk_shipping_id = ".$spk_shipping_id."";
                    $jml_m3_terima = Yii::$app->db->createCommand($sql_jml_m3_terima)->queryScalar();

                    $data['jml_batang_terima'] = $jml_batang_terima;
                    $data['jml_m3_terima'] = $jml_m3_terima;
        
                } else {
                    $data['jml_batang_terima'] = 0;
                    $data['jml_m3_terima'] = 0;
                }
            }


            // t_spk_shipping
            $modTPengajuanPembelianLog = \app\models\TPengajuanPembelianlog::findOne(["pengajuan_pembelianlog_id"=>$pengajuan_pembelianlog_id]);
            $spk_shipping_id = $modTPengajuanPembelianLog->spk_shipping_id;

            $data['spk_shipping'] = "";
			if(!empty($spk_shipping_id)){
                $data['spk_shipping'] .= $this->renderPartial('_spk_shipping',['spk_shipping_id'=>$spk_shipping_id]);
            } else {
                $data['spk_shipping'] .= "Data SPK Shipping tidak ditemukan";
            }
		}
        return $this->asJson($data);
    }

    // fungsi menampilkan modal image
    public function actionImage($id){
        if(\Yii::$app->request->isAjax){
            $modAttachment = \app\models\TAttachment::findOne($id);
            return $this->renderAjax('_image',['modAttachment'=>$modAttachment]);
        }
    }

    public function actionConfirmBatal($id){
        if(\Yii::$app->request->isAjax){
			return $this->renderAjax('_confirmBatal',['id'=>$id]);
        }
    }

    public function actionConfirmHapus($id){
        if(\Yii::$app->request->isAjax){
			return $this->renderAjax('_confirmHapus',['id'=>$id]);
        }
    }

    public function actionBatalYes() {
        $adjustment_log_id = Yii::$app->request->post('id');
        $cancel_reason = Yii::$app->request->post('cancel_reason');
		if(\Yii::$app->request->isAjax){
            // kode adjustmentLog
            $model = \app\models\TAdjustmentLog::findOne(["adjustment_log_id"=>$adjustment_log_id]);
            $reff_no = $model->kode;

            // cek ulang, sudah diapprove atau belum
            $sql_approval = "select count(*) from t_approval where reff_no = '".$reff_no."' and (status = 'APPROVED' or status = 'REJECTED') ";
            $jumlah_approval = Yii::$app->db->createCommand($sql_approval)->queryScalar();

            if ($jumlah_approval > 1) {
                $msg = "Data gagal dibatalkan";
            } else {
                // batalkan t_approval
                $sql_update_approval = "update t_approval set status = 'ABORTED' where reff_no = '".$reff_no."' ";
                Yii::$app->db->createCommand($sql_update_approval)->execute();
                
                // input t_cancel_transaksi
                $username = $_SESSION['sess_username'];
                $m_pegawai = \app\models\MUser::findByUsername($username);
                $pegawai_id = $m_pegawai->pegawai_id;
                $now = date('Y-m-d H:i:s');
                $sql_insert = "insert into t_cancel_transaksi ". 
                                "   (cancel_by, cancel_at, cancel_reason, reff_no, status, created_at, created_by, updated_at, updated_by) ".
                                "   values ". 
                                "   ($pegawai_id, '".$now."', '".$cancel_reason."', '".$reff_no."', 'ABORTED', '".$now."', $pegawai_id, '".$now."', $pegawai_id) ";
                Yii::$app->db->createCommand($sql_insert)->execute();

                // update status t_adjustment_log batal
                $sql_update_ajudstment_log = "update t_adjustment_log set status_approval = 'ABORTED' where adjustment_log_id = ".$adjustment_log_id." ";
                Yii::$app->db->createCommand($sql_update_ajudstment_log)->execute();

                $msg = "Data berhasil dibatalkan";
            }

            // reload ajax tampilkan data setelah dibatalkan ke tabel semula
            $sql = "select a.adjustment_log_id, a.kode as kodex, a.tanggal, b.kode as kodey, a.reff_no_loglist, a.reff_no_spk, a.jml_batang_loglist, a.jml_m3_loglist, a.jml_batang_terima, a.jml_m3_terima, a.uraian, a.status_approval ".
                        "   from t_adjustment_log a ".
                        "   join t_pengajuan_pembelianlog b on b.pengajuan_pembelianlog_id = a.pengajuan_pembelianlog_id ".
                        "   join t_loglist c on c.loglist_kode = a.reff_no_loglist ".
                        "   join t_spk_shipping d on d.kode = a.reff_no_spk ".
                        "   ";
            $modAdjustmentLogs = Yii::$app->db->createCommand($sql)->queryAll();
            $data = [];
            $data['status'] = false;
            $data['msg'] = $msg;
            $data['html'] = "";
            foreach ($modAdjustmentLogs as $modAdjustmentLog) {
                $data['html'] .= "<tr class='odd' role='row'>";
                $data['html'] .= "<td class='text-center td-kecil' style='height: 22px;'>".$modAdjustmentLog['kodex']."</td>";
                $data['html'] .= "<td class='text-center td-kecil'>".$modAdjustmentLog['tanggal']."</td>";
                $data['html'] .= "<td class='text-center td-kecil'>".$modAdjustmentLog['kodey']."</td>";
                $data['html'] .= "<td class='text-center td-kecil'>".$modAdjustmentLog['reff_no_loglist']."</td>";
                $data['html'] .= "<td class='text-center td-kecil'>".$modAdjustmentLog['reff_no_spk']."</td>";
                $data['html'] .= "<td class='td-kecil'>".$modAdjustmentLog['jml_batang_loglist']."</td>";
                $data['html'] .= "<td class='td-kecil'>".$modAdjustmentLog['jml_m3_loglist']."</td>";
                $data['html'] .= "<td class='td-kecil'>".$modAdjustmentLog['jml_batang_terima']."</td>";
                $data['html'] .= "<td class='td-kecil'>".$modAdjustmentLog['jml_m3_terima']."</td>";
                $data['html'] .= "<td class='td-kecil'>".$modAdjustmentLog['uraian']."</td>";
                
                if ($modAdjustmentLog['status_approval'] == "Not Confirmed") {
                    $data['html'] .= "<td>".
                                        "   <a class='btn btn-xs btn-default tooltips' style='margin-right: 0px;' data-original-title='VIEW' onclick='confirmView(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-eye' aria-hidden='true'></i></a> ".
                                        "   <a class='btn btn-xs btn-outline btn-warning tooltips' style='margin-right: 0px;' data-original-title='EDIT' onclick='confirmEdit(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-edit'></i></a> ". 
                                        "   <a class='btn btn-xs btn-outline btn-danger tooltips' style='margin-right: 0px;' data-original-title='BATAL' onclick='confirmBatal(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-minus-circle' aria-hidden='true'></i></a>".
                                        "   </td>";
                } else {
                    $data['html'] .= "<td>". 
                                        "   <a class='btn btn-xs btn-default tooltips' style='margin-right: 0px;' data-original-title='VIEW' onclick='confirmView(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-eye' aria-hidden='true'></i></a> ".
                                        "   <span class='td-kecil text-danger'><b>".$modAdjustmentLog['status_approval']."</b></span></td> ".
                                        "   ";
                }
                
                $data['html'] .= "<td></td>";
                $data['html'] .= "</tr>";
            }  
		}
        return $this->asJson($data);
    }

    public function actionHapusYes() {
        $adjustment_log_id = Yii::$app->request->post('id');
		if(\Yii::$app->request->isAjax){
            // kode adjustmentLog
            $model = \app\models\TAdjustmentLog::findOne(["adjustment_log_id"=>$adjustment_log_id]);
            $reff_no = $model->kode;

            // cek ulang, sudah diapprove atau belum
            $sql_approval = "select count(*) from t_approval where reff_no = '".$reff_no."' and status = 'APPROVED' or status = 'REJECTED' ";
            $jumlah_approval = Yii::$app->db->createCommand($sql_approval)->queryScalar();
            if ($jumlah_approval > 0) {
                $delete1 = 0; $delete2 = 0; $delete3 = 0; 
            } else {
                // cek ulang, jika ada data, hapus
                //$reff_no = Yii::$app->db->createCommand("select kode from t_adjustment_log where adjustment_log_id = ".$adjustment_log_id."")->queryScalar();
                $data1 = Yii::$app->db->createCommand("select count(*) from t_adjustment_log where adjustment_log_id = ".$adjustment_log_id."")->queryScalar();
                if ($data1 > 0) {
                    $delete1 = Yii::$app->db->createCommand()->delete('t_adjustment_log', ['adjustment_log_id' => $adjustment_log_id])->execute();
                } else {
                    $delete1 = 1;
                }
                $data2 = Yii::$app->db->createCommand("select count(*) from t_approval where reff_no = '".$reff_no."'")->queryScalar();
                if ($data2 > 0) {
                    $delete2 = Yii::$app->db->createCommand()->delete('t_approval', ['reff_no' => $reff_no])->execute();
                } else {
                    $delete2 = 1;
                }
                $data3 = Yii::$app->db->createCommand("select count(*) from t_attachment where reff_no = '".$reff_no."'")->queryScalar();
                if ($data3 > 0) {
                    $modAttachments = \app\models\TAttachment::findAll(["reff_no"=>$reff_no]);
                    foreach ($modAttachments as $modAttachment) {
                        $delete3 = unlink($modAttachment->dir_path.'/'.$modAttachment->file_name) && $modAttachment->delete();
                    }
                } else {
                    $delete3 = 1;
                }
            }

            if ($delete1 && $delete2 && $delete3) {
                // reload ajax tampilkan data setelah dihapus ke tabel semula
                $sql = "select a.adjustment_log_id, a.kode as kodex, a.tanggal, b.kode as kodey, a.reff_no_loglist, a.reff_no_spk, a.jml_batang_loglist, a.jml_m3_loglist, a.jml_batang_terima, a.jml_m3_terima, a.uraian, a.status_approval ".
                        "   from t_adjustment_log a ".
                        "   join t_pengajuan_pembelianlog b on b.pengajuan_pembelianlog_id = a.pengajuan_pembelianlog_id ".
                        "   join t_loglist c on c.loglist_kode = a.reff_no_loglist ".
                        "   join t_spk_shipping d on d.kode = a.reff_no_spk ".
                        "   ";
                $modAdjustmentLogs = Yii::$app->db->createCommand($sql)->queryAll();
                $data = [];
                $data['status'] = false;
                $data['msg'] = "Data berhasil dihapus";
                $data['html'] = "";
                foreach ($modAdjustmentLogs as $modAdjustmentLog) {
                    $data['html'] .= "<tr class='odd' role='row'>";
                    $data['html'] .= "<td class='text-center td-kecil' style='height: 22px;'>".$modAdjustmentLog['kodex']."</td>";
                    $data['html'] .= "<td class='text-center td-kecil'>".$modAdjustmentLog['tanggal']."</td>";
                    $data['html'] .= "<td class='text-center td-kecil'>".$modAdjustmentLog['kodey']."</td>";
                    $data['html'] .= "<td class='text-center td-kecil'>".$modAdjustmentLog['reff_no_loglist']."</td>";
                    $data['html'] .= "<td class='text-center td-kecil'>".$modAdjustmentLog['reff_no_spk']."</td>";
                    $data['html'] .= "<td class='td-kecil'>".$modAdjustmentLog['jml_batang_loglist']."</td>";
                    $data['html'] .= "<td class='td-kecil'>".$modAdjustmentLog['jml_m3_loglist']."</td>";
                    $data['html'] .= "<td class='td-kecil'>".$modAdjustmentLog['jml_batang_terima']."</td>";
                    $data['html'] .= "<td class='td-kecil'>".$modAdjustmentLog['jml_m3_terima']."</td>";
                    $data['html'] .= "<td class='td-kecil'>".$modAdjustmentLog['uraian']."</td>";
                    if ($modAdjustmentLog['status_approval'] == "PENDING") {
                        $data['html'] .= "<td class='text-center td-kecil'>
                                            <a class='btn btn-xs btn-default tooltips' style='margin-right: 0px;' data-original-title='VIEW' onclick='confirmView(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-eye' aria-hidden='true'></i></a>
                                            <a class='btn btn-xs btn-outline btn-warning tooltips' style='margin-right: 0px;' data-original-title='EDIT' onclick='confirmEdit(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-edit'></i></a>
                                            <a class='btn btn-xs btn-outline btn-danger tooltips' style='margin-right: 0px;' data-original-title='BATAL' onclick='confirmBatal(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-minus-circle'></i></a>
                                            </td>";
                    } else if ($modAdjustmentLog['status_approval'] == "BATAL") {
                        $data['html'] .= "<td class='text-center td-kecil'>
                                            <a class='btn btn-xs btn-default tooltips' style='margin-right: 0px;' data-original-title='VIEW' onclick='confirmView(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-eye' aria-hidden='true'></i></a>
                                            <a class='btn btn-xs btn-outline btn-warning tooltips' style='margin-right: 0px;' data-original-title='EDIT' onclick='confirmEdit(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-edit'></i></a>
                                            </td>";
                    } else {
                        $data['html'] .= "<td class='text-center td-kecil'>
                                            <a class='btn btn-xs btn-default tooltips' style='margin-right: 0px;' data-original-title='VIEW' onclick='confirmView(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-eye' aria-hidden='true'></i></a>
                                            <a class='btn btn-xs btn-outline btn-warning tooltips' style='margin-right: 0px;' data-original-title='EDIT' onclick='confirmEdit(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-edit'></i></a>
                                            <a class='btn btn-xs btn-outline btn-danger tooltips' style='margin-right: 0px;' data-original-title='HAPUS' onclick='confirmHapus(".$modAdjustmentLog['adjustment_log_id'].")'><i class='fa fa-trash-o'></i></a>
                                            </td>";
                    }
                    $data['html'] .= "</tr>";
                }
            } else {
                $data['msg'] = "Data gagal dihapus";
            }
		}
        return $this->asJson($data);
    }
}
