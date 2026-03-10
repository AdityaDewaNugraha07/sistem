<?php

namespace app\modules\ppic\controllers;

use Yii;
use yii\helpers\Json;
use app\components\SSP;
use app\controllers\DeltaBaseController;
use app\models\HPersediaanLog;
use app\models\TPemotonganLogDetailPotong;
use app\models\MKayu;
use app\models\TPemotonganLog;
use app\models\TPemotonganLogDetail;

class ScanterimaloghasilpotongController extends DeltaBaseController
{
    public $defaultAction = 'index';
    public function actionIndex()
    {
        if (isset($_POST['catatan']) && isset($_POST['pemotongan_log_detail_potong_id']) ) {
            $data = "";
            $catatan = Yii::$app->request->post('catatan');
            $pemotongan_log_detail_potong_id = Yii::$app->request->post('pemotongan_log_detail_potong_id');
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pemotongan_log_detail_potong
                $success_2 = false; // h_persediaan_log

                // update t_pemotongan_log_detail_potong
                $modDetail = TPemotonganLogDetailPotong::findOne($pemotongan_log_detail_potong_id);
                $modDetail->tanggal_penerimaan = date("Y-m-d H:i:s");
                $modDetail->penerima = Yii::$app->user->identity->pegawai_id;
                $modDetail->catatan_penerimaan = ($catatan)?$catatan:null;
                $modDetail->status_penerimaan = true;
                if($modDetail->validate()){
                    if($modDetail->save()){
                        $success_1 = true;

                        $modelDet = TPemotonganLogDetail::findOne($modDetail->pemotongan_log_detail_id);
                        $model = TPemotonganLog::findOne($modelDet->pemotongan_log_id);
                        $modLog = Yii::$app->db->createCommand("
                                SELECT * FROM t_pemotongan_log_detail_potong
                                JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama
                                JOIN t_log_keluar ON t_log_keluar.no_barcode = h_persediaan_log.no_barcode  AND t_log_keluar.reff_no = h_persediaan_log.reff_no
                                WHERE pemotongan_log_detail_potong_id = {$pemotongan_log_detail_potong_id}")->queryOne();

                        $modH = new HPersediaanLog();
                        $modH->tgl_transaksi = $modDetail->tanggal_penerimaan;
                        $modH->kayu_id = $modelDet->kayu_id;
                        $modH->no_barcode = $modDetail->no_barcode_baru;
                        $modH->no_grade = $modLog['no_grade'];
                        $modH->no_btg = $modLog['no_btg'];
                        $modH->no_lap = $modDetail->no_lap_baru;
                        $modH->status = 'IN';
                        $modH->reff_no = $model->kode;
                        $modH->lokasi = 'GUDANG LOG ALAM';
                        $modH->keterangan = 'SCAN PENERIMAAN LOG DARI PEMOTONGAN LOG';
                        $modH->fisik_diameter = number_format(($modDetail->diameter_ujung1_baru + $modDetail->diameter_ujung2_baru + $modDetail->diameter_pangkal1_baru + $modDetail->diameter_pangkal2_baru) / 4);
                        $modH->fisik_panjang = $modDetail->panjang_baru;
                        $modH->fisik_reduksi = $modLog['fisik_reduksi'];
                        $modH->fisik_volume = $modDetail->volume_baru;
                        $modH->pot = $modDetail->kode_pemotongan;
                        $modH->fisik_pcs = $modLog['fisik_pcs'];
                        $modH->diameter_ujung1 = $modDetail->diameter_ujung1_baru;
                        $modH->diameter_ujung2 = $modDetail->diameter_ujung2_baru;
                        $modH->diameter_pangkal1 = $modDetail->diameter_pangkal1_baru;
                        $modH->diameter_pangkal2 = $modDetail->diameter_pangkal2_baru;
                        $modH->cacat_panjang = $modDetail->cacat_pjg_baru;
                        $modH->cacat_gb = $modDetail->cacat_gb_baru;
                        $modH->cacat_gr = $modDetail->cacat_gr_baru;
                        $modH->active = true;
                        $modH->no_produksi = $modLog['no_produksi'];
                        $modH->fsc = $modLog['fsc'];
                        $modH->created_by = $modDetail->penerima;
                        $modH->created_at = date("Y-m-d H:i:s");
                        $modH->updated_by = $modDetail->penerima;
                        $modH->updated_at = date("Y-m-d H:i:s");
                        if($modH->validate()){
                            if($modH->save()){
                                $success_2 = true;
                            }
                        }
                    }
                }

                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data berhasil disimpan'));
                    $data['msg'] = "Data berhasil disimpan";
                } else {
                    $transaction->rollback();
                    if ($success_1) {
                        $error_msg = "Gagal insert t_pengembalian_log_detail";
                    } else {
                        $error_msg = "Gagal insert h_persediaan_log";
                    }
                    if (empty($error_msg)) {
                        $error_msg = \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE;
                    } else {
                        $error_msg = $error_msg;
                    }
                    $data['msg'] = $error_msg;
                    Yii::$app->session->setFlash('error', !empty($errmsg) ? $errmsg : Yii::t('app', $error_msg));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
            return $this->asJson($data);
        }
        return $this->render('index');
    }

    public function actionShowDetail(){
        $data['status']         = false;
        if(Yii::$app->request->isAjax){
			$no_barcode     	= Yii::$app->request->post('no_barcode');

            $modelA = TPemotonganLogDetailPotong::findOne(['no_barcode_baru'=>$no_barcode]); // ada di data pemotongan
            $modelB = TPemotonganLogDetailPotong::findOne(['no_barcode_baru'=>$no_barcode, 'status_penerimaan'=>false]); // belum diterima

            if (substr($_POST['datas'], 0, 5) == "ID : ") {
                if($modelA){
                    if($modelB){
                        if($modelB->alokasi == 'Gudang'){
                            $data['status'] = true;
                            $data['msg']    = "Data ok";
                            $data['pemotongan_log_detail_potong_id'] = $modelB->pemotongan_log_detail_potong_id;
                        } else {
                            $data['status'] = false;
                            $data['msg']    = "Log bukan beralokasi di gudang!!";
                            $data['pemotongan_log_detail_potong_id'] = $modelB->pemotongan_log_detail_potong_id;
                        }
                    } else {
                        $data['status'] = false;
                        $data['msg']    = "Data sudah ada";
                        $data['pemotongan_log_detail_potong_id'] = $modelA->pemotongan_log_detail_potong_id;
                    }
                } else {
                    $data['status'] = false;
                    $data['msg']    = "Data tidak ditemukan!!";
                }
            } else {
                $data['msg'] = "Invalid QR Code Format -> " . $_POST['datas'];
            }
		} else {
            $data['msg'] = "xxx";
        }
        return $this->asJson($data);
	}

    public function actionReview()
    {
        if (\Yii::$app->request->isAjax) {
            $no_barcode = $_GET['no_barcode'];
            $pemotongan_log_detail_potong_id = $_GET['id'];
            $modLog = TPemotonganLogDetailPotong::findOne($pemotongan_log_detail_potong_id);
            return $this->renderAjax('_review', ['modLog' => $modLog, 'pemotongan_log_detail_potong_id'=>$pemotongan_log_detail_potong_id]);
        }
    }

    public function actionView()
    {
        if (\Yii::$app->request->isAjax) {
            $no_barcode = $_GET['no_barcode'];
            $pemotongan_log_detail_potong_id = $_GET['id'];
            $modDetail = \app\models\TPemotonganLogDetailPotong::findOne($pemotongan_log_detail_potong_id);
            $modH = Yii::$app->db->createCommand("
                        SELECT * FROM t_pemotongan_log_detail_potong
                        JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama
                        JOIN t_log_keluar ON t_log_keluar.no_barcode = h_persediaan_log.no_barcode  AND t_log_keluar.reff_no = h_persediaan_log.reff_no
                        WHERE pemotongan_log_detail_potong_id = {$pemotongan_log_detail_potong_id}")->queryOne();
            $modKayu = \app\models\MKayu::findOne(['kayu_id' => $modH['kayu_id']]);
            $title = "<font style='color: #2ebd30;'>PEMOTONGAN LOG ALOKASI GUDANG SUDAH DITERIMA</font>";

            return $this->renderAjax('_view', ['modDetail' => $modDetail, 'modKayu' => $modKayu, 'title' => $title, 'modH'=>$modH]);
        }
    }

    public function actionInputManual()
    {
        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('_inputManual');
        }
    }

    public function actionInputManuals()
    {
        if (Yii::$app->request->isAjax) {
            $req = $_POST;
            if($req['clause'] === 'no_lap' || $req['clause'] === 'no_barcode') {
                $clause = trim($req['clause']).'_baru';
                $modDetail = Yii::$app->db->createCommand(" SELECT * FROM t_pemotongan_log_detail_potong
                                                            WHERE ".$clause." = '".trim($req['keyword'])."' 
                                                         ")->queryOne();
                if (count($modDetail) > 0) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modDetail['pemotongan_log_detail_potong_id']}\nNo : {$modDetail['no_barcode_baru']}",
						'no_barcode'=> $modDetail['no_barcode_baru'],
                        'id'=>$modDetail['pemotongan_log_detail_potong_id'],
                    ]);
                }
            }else {
                $modDetails = Yii::$app->db->createCommand("SELECT * FROM t_pemotongan_log_detail_potong
                                                            JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama
                                                            JOIN t_log_keluar ON t_log_keluar.no_barcode = h_persediaan_log.no_barcode  AND t_log_keluar.reff_no = h_persediaan_log.reff_no
                                                            WHERE h_persediaan_log.".trim($req['clause'])." = '".trim($req['keyword'])."' 
                                                            ")->queryAll();
                if(count($modDetails) === 1) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modDetails[0]['pemotongan_log_detail_potong_id']}\nNo : {$modDetails[0]['no_barcode']}",
                        'no_barcode'=> $modDetails[0]['no_barcode'],
                        'id'=>$modDetails[0]['pemotongan_log_detail_potong_id'],
                    ]);
                }else {
                    return $this->asJson([
                        'status' => true,
                        'datas' => $modDetails
                    ]);
                }
            }
        }
        return $this->asJson(['status' => false, 'message' => 'Data tidak ditemukan']);
    }

    public function actionDaftarScanned()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->get('dt') == 'modal-scanned') {
                $param['table'] = TPemotonganLogDetailPotong::tableName();
                $param['pk'] = TPemotonganLogDetailPotong::primaryKey()[0];
                $param['column'] = ['h_persediaan_log.persediaan_log_id', 
                                    'h_persediaan_log.tgl_transaksi', //1
                                    'h_persediaan_log.no_barcode', //2
                                    'm_kayu.kayu_nama', //3
                                    'm_pegawai.pegawai_nama', //4
                                    'h_persediaan_log.no_grade', //5
                                    'h_persediaan_log.no_btg', //6
                                    'h_persediaan_log.no_lap', //7
                                    'h_persediaan_log.fisik_volume', //8
                                    'h_persediaan_log.fsc', //9
                                    'h_persediaan_log.lokasi', //10
                                    't_pemotongan_log_detail_potong.pemotongan_log_detail_potong_id' //11
                                    ];
                $param['join'] = [ 'JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_baru
                                    JOIN m_kayu ON m_kayu.kayu_id = h_persediaan_log.kayu_id
                                    JOIN m_pegawai ON m_pegawai.pegawai_id = t_pemotongan_log_detail_potong.penerima'
                                 ];
                $param['where'] = ["t_pemotongan_log_detail_potong.status_penerimaan IS TRUE"];
                // $param['group'] = ["GROUP BY h_persediaan_log.persediaan_log_id, m_kayu.kayu_nama, m_pegawai.pegawai_nama, t_pemotongan_log_detail_potong.pemotongan_log_detail_potong_id"];
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('_daftarScanned');
        }
    }

    public function actionLihatDetail()
    {
        if (\Yii::$app->request->isAjax) {
            $persediaan_log_id = $_GET['id'];
            $modH = HPersediaanLog::findOne($persediaan_log_id);
            $model = TPemotonganLog::findOne(['kode'=>$modH->reff_no]);
            $modDetailPot = TPemotonganLogDetailPotong::findOne(['no_barcode_baru'=>$modH->no_barcode]);
            $modDetail = TPemotonganLogDetail::findOne(['no_barcode'=>$modDetailPot->no_barcode_lama]);
            
            return $this->renderAjax('_lihatDetail', ['modH' => $modH, 'model'=>$model, 'modDetail'=>$modDetail, 'modDetailPot'=>$modDetailPot]);
        }
    }

    public function actionConfirmHapusDetail($id){
        if(\Yii::$app->request->isAjax){
			return $this->renderAjax('_confirmHapusDetail',['id'=>$id]);
        }
    }

    public function actionHapusDetailYes() {
        $persediaan_log_id = Yii::$app->request->post('id');
		if(\Yii::$app->request->isAjax){
            // hapus
            $modH = HPersediaanLog::findOne($persediaan_log_id);
            $success_1 = Yii::$app->db->createCommand()->update('t_pemotongan_log_detail_potong', 
                                ['status_penerimaan'=>false, 'tanggal_penerimaan'=>null, 'penerima'=>null, 'catatan_penerimaan'=>null], 
                                ['no_barcode_baru'=>$modH->no_barcode])->execute();
            $success_2 = Yii::$app->db->createCommand()->delete('h_persediaan_log', ['persediaan_log_id' => $persediaan_log_id])->execute();
            
            if ($success_1 && $success_2) {
                $data['status'] = true;
                $data['msg'] = 'Data berhasil dihapus';
            } else {
                $data['status'] = false;
                $data['msg'] = "Data gagal dihapus";
            }
		}
        return $this->asJson($data);
    } 
}
