<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\TPengajuanPembelianlog;
use app\models\MSuplier;

class SpmlogController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TSpkShipping();
        $modPengajuanPembelianlog = new \app\models\TPengajuanPembelianlog();
        $model->tanggal = date("d/m/Y");
        $model->etd = date("d/m/Y");
        $model->eta_logpond = date("d/m/Y");
        $model->eta = date("d/m/Y");
		$model->asuransi = true;
		$model->estimasi_total_batang = 0;
        $model->estimasi_total_m3 = 0;
		$model->by_kanit = \app\components\Params::DEFAULT_PEGAWAI_ID_SEKAR;
		$model->by_kadiv = \app\components\Params::DEFAULT_PEGAWAI_ID_ASENG;

		if(isset($_GET['spk_shipping_id'])){
			$model = \app\models\TSpkShipping::findOne($_GET['spk_shipping_id']);
			$model->kode = $model->kode;
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->nama_tongkang = $model->nama_tongkang;
            $model->etd = \app\components\DeltaFormatter::formatDateTimeForUser2($model->etd);
            $model->eta_logpond = \app\components\DeltaFormatter::formatDateTimeForUser2($model->eta_logpond);
            $model->eta = \app\components\DeltaFormatter::formatDateTimeForUser2($model->eta);
            $model->lokasi_muat = $model->lokasi_muat;
            $model->estimasi_total_batang = $model->estimasi_total_batang;
            $model->estimasi_total_m3 = $model->estimasi_total_m3;
            $model->asuransi == true ? $model->asuransi = 1 : $model->asuransi = 0;
            $model->pic_shipping = $model->pic_shipping;
			$model->by_kanit = \app\models\MPegawai::findOne($model->by_kanit)->pegawai_id;
			$model->by_kadiv = \app\models\MPegawai::findOne($model->by_kadiv)->pegawai_id;
            $model->approve_reason = $model->approve_reason;
            $model->reject_reason = $model->reject_reason;
            $model->status = $model->status;
            $model->keterangan = $model->keterangan;
            
            $modPengajuanPembelianlog = \app\models\TPengajuanPembelianlog::findAll(array('spk_shipping_id'=>$_GET['spk_shipping_id']));
		}

        if( Yii::$app->request->post('TSpkShipping')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_spk_shipping
                $success_2 = false; // t_pengajuan_pembelianlog
                $success_3 = false; // t_approval
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeSpmLog();
				}

                // berikan status Not Confirmed otomatis saat input data di t_spk_shipping
                $model->status = 'Not Confirmed';
                
                // t_spk_shipping : simpan data
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                    } else {
                        $success_1 = false;
                    }
                }

                // t_spk_shipping : ambil spk_shipping_id
                $sql_spk_shipping_id = "select spk_shipping_id from t_spk_shipping where kode = '".$model->kode."' ";
                $spk_shipping_id = Yii::$app->db->createCommand($sql_spk_shipping_id)->queryScalar();

                // t_pengajuan_pembelianlog : cek apa ada spk_shipping_id
                $sql_jumlah_t_pengajuan_pembelianlog = "select count(spk_shipping_id) from t_pengajuan_pembelianlog where spk_shipping_id = ".$spk_shipping_id."";
                $jumlah_t_pengajuan_pembelianlog = Yii::$app->db->createCommand($sql_jumlah_t_pengajuan_pembelianlog)->queryScalar();
                
                // t_pengajuan_pembelianlog : kosongkan dulu yang sudah pernah diisi
                if ($jumlah_t_pengajuan_pembelianlog > 0) {
                    $sql_t_pengajuan_pembelianlog = "update t_pengajuan_pembelianlog set spk_shipping_id = null where spk_shipping_id = ".$spk_shipping_id."";
                    $success_3 = Yii::$app->db->createCommand($sql_t_pengajuan_pembelianlog)->execute();
                } else {
                    $success_3 = true;
                }

                // t_pengajuan_pembelianlog : update kolom spk_shipping_id sesuai dengan jumlah spk_shipping_id yang diambil
                foreach (Yii::$app->request->post('TPengajuanPembelianlog') as $post) {
                    $pengajuan_pembelianlog_id = $post['pengajuan_pembelianlog_id'];
                    $sql_update = "update t_pengajuan_pembelianlog set spk_shipping_id = ".$spk_shipping_id." ".
                                        "   where pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id." ";
                    $success_2 = Yii::$app->db->createCommand($sql_update)->execute();
                }

                // t_approval : simpan data
                // START Create Approval
                $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
                if (count($modelApproval) > 0){ // edit mode
                    if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
                        $success_4 = $this->saveApproval($model);
                    }
                }else{ // insert mode
                    $success_4 = $this->saveApproval($model);
                }
                // END Create Approval

                if ($success_1 && $success_2 && $success_3 && $success_4) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'spk_shipping_id'=>$model->spk_shipping_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?(implode(",", array_values($errmsg)[0])):Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model,'modPengajuanPembelianlog'=>$modPengajuanPembelianlog]);
	}

    public function actionOpenKeputusanPembelianLog(){
		if(\Yii::$app->request->isAjax){
            $pickKeputusanPembelianLog = \Yii::$app->request->get('pickKeputusanPembelianLog');
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPengajuanPembelianlog::tableName();
				$param['pk']= $param['table'].".". \app\models\TPengajuanPembelianlog::primaryKey()[0];
				$param['column'] = [$param['table'].'.pengajuan_pembelianlog_id',
									"CONCAT(kode,'-',revisi) AS kode",
									$param['table'].'.tanggal',
									$param['table'].'.nomor_kontrak',
									$param['table'].'.volume_kontrak',
									'm_suplier.suplier_nm',
									$param['table'].'.asal_kayu',
									$param['table'].'.total_volume',
									$param['table'].'.cancel_transaksi_id',
                                    $param['table'].'.suplier_id',
                                    $param['table'].'.status',
									];
				$param['join']= ['JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id '];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL ".
                                    "  and status = 'APPROVED' ". 
                                    "   and ".$param['table'].".spk_shipping_id IS NULL ". 
                                    "   and ".$param['table'].".pengajuan_pembelianlog_id NOT IN ".
                                    "       (select pengajuan_pembelianlog_id from t_pmr where pengajuan_pembelianlog_id = t_pengajuan_pembelianlog.pengajuan_pembelianlog_id) ".
                                    "   ";                
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('_openKeputusanPembelianLog',['pickKeputusanPembelianLog'=>$pickKeputusanPembelianLog]);
        }
    }

    public function actionPickKeputusanPembelianLog(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data['html'] = ""; $data['pengajuan_pembelianlog_id']="";
            if(!empty($id)){
                $model = TPengajuanPembelianLog::findOne($id);
                $modSuplier = MSuplier::findOne($model->suplier_id);
                $sql_jumlahBatang = "select sum(qty_batang) from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$id."";
                $jumlah_batang = Yii::$app->db->createCommand($sql_jumlahBatang)->queryScalar();
                $sql_jumlahVolume = "select sum(qty_m3) from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$id."";
                $jumlah_volume = Yii::$app->db->createCommand($sql_jumlahVolume)->queryScalar();
                $data['html'] .= $this->renderPartial('_itemPermintaan',['model'=>$model,'modSuplier'=>$modSuplier,'jumlah_batang'=>$jumlah_batang,'jumlah_volume'=>$jumlah_volume]);
                $data['lokasi_muat'] = $model->lokasi_muat;
                $data['pengajuan_pembelianlog_id'] = $model->pengajuan_pembelianlog_id;
            }
            return $this->asJson($data);
        }
    }

	public function saveApproval($model){
		$success = true;
		/*if($model->by_kanit){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->by_kanit;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 1;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}*/
		if($model->by_kadiv){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->by_kadiv;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 2;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		return $success;
	}

    public function actionDaftarSpmLog(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-daftarSpmLog'){
				$param['table']= \app\models\TSpkShipping::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TSpkShipping::primaryKey()[0];
				$param['column'] = ['t_spk_shipping.spk_shipping_id',
									't_spk_shipping.kode',
									't_spk_shipping.tanggal',
									't_spk_shipping.etd',
									't_spk_shipping.eta_logpond',
									't_spk_shipping.eta',
									't_spk_shipping.nama_tongkang',
									't_spk_shipping.lokasi_muat',
									't_spk_shipping.estimasi_total_batang',
									't_spk_shipping.estimasi_total_m3',
                                    'm_pegawai.pegawai_nama',
                                    't_spk_shipping.status',
                                    't_spk_shipping.status_jenis',
									];
                $param['join']= ['
                JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.pic_shipping 
                '];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('_daftarSpmLog');
        }
    }

	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $spk_shipping_id = Yii::$app->request->post('spk_shipping_id');
            $data = [];
            $data['html'] = '';
            if(!empty($spk_shipping_id)){
                $modPengajuanPembelianlog = \app\models\TPengajuanPembelianlog::find()->where(['spk_shipping_id'=>$spk_shipping_id])->orderBy(['pengajuan_pembelianlog_id'=>SORT_ASC])->all();
                if(count($modPengajuanPembelianlog)>0){
                    foreach($modPengajuanPembelianlog as $i => $modelPengajuanPembelianlog){
						$modelPengajuanPembelianlog->kode = $modelPengajuanPembelianlog->kode;
                        $modSuplier = \app\models\MSuplier::findOne($modelPengajuanPembelianlog->suplier_id);
                        $sql_jumlahBatang = "select sum(qty_batang) from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$modelPengajuanPembelianlog->pengajuan_pembelianlog_id."";
                        $jumlah_batang = Yii::$app->db->createCommand($sql_jumlahBatang)->queryScalar();
                        $sql_jumlahVolume = "select sum(qty_m3) from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$modelPengajuanPembelianlog->pengajuan_pembelianlog_id."";
                        $jumlah_volume = Yii::$app->db->createCommand($sql_jumlahVolume)->queryScalar();
                        $data['html'] .= $this->renderPartial('_item',['modelPengajuanPembelianlog'=>$modelPengajuanPembelianlog,'modSuplier'=>$modSuplier,'jumlah_batang'=>$jumlah_batang,'jumlah_volume'=>$jumlah_volume,'alreadyitem'=>[]]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionOpenDetailKeputusanPembelianlog($id){
		if(\Yii::$app->request->isAjax){
            $model = TPengajuanPembelianlog::findOne($id);
			return $this->renderAjax('_info',['model'=>$model]);
		}
	}

    public function actionOpenDetailTracking2($id) {
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('_infoTracking',['id'=>$id]);
		}
    }

}
