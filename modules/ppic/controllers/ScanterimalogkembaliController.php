<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\models\MKayu;
use yii\helpers\Json;
use app\components\SSP;
use app\controllers\DeltaBaseController;
use app\models\HPersediaanLog;
use app\models\TPengembalianLog;
use app\models\TPengembalianLogDetail;

class ScanterimalogkembaliController extends DeltaBaseController
{
    public $defaultAction = 'index';
    public function actionIndex()
    {
        if (isset($_POST['catatan']) && isset($_POST['pengembalian_log_detail_id']) ) {
            $data = "";
            $catatan = Yii::$app->request->post('catatan');
            $pengembalian_log_detail_id = Yii::$app->request->post('pengembalian_log_detail_id');
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pengembalian_log_detail
                $success_2 = false; // h_persediaan_log

                // update t_pengembalian_log_detail
                $modDetail = TPengembalianLogDetail::findOne($pengembalian_log_detail_id);
                $modDetail->tanggal_penerimaan = date("Y-m-d H:i:s");
                $modDetail->penerima = Yii::$app->user->identity->pegawai_id;
                $modDetail->catatan_penerimaan = ($catatan)?$catatan:null;
                $modDetail->status_penerimaan = true;
                if($modDetail->validate()){
                    if($modDetail->save()){
                        $success_1 = true;

                        $model = TPengembalianLog::findOne($modDetail->pengembalian_log_id);
                        $modLogKeluar = \app\models\TLogKeluar::findOne(['no_barcode'=>$modDetail->no_barcode]);
                        $modLog = \app\models\HPersediaanLog::findOne(['no_barcode'=>$modDetail->no_barcode, 'reff_no'=>$modLogKeluar->reff_no]);

                        $modH = new HPersediaanLog();
                        $modH->attributes = $modLog->attributes;
                        $modH->tgl_transaksi = $modDetail->tanggal_penerimaan;
                        $modH->status = 'IN';
                        $modH->reff_no = $model->kode;
                        $modH->lokasi = 'GUDANG LOG ALAM';
                        $modH->keterangan = 'SCAN PENERIMAAN LOG DARI PENGEMBALIAN LOG';
                        $modH->active = true;
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

            $modelA = TPengembalianLogDetail::findOne(['no_barcode'=>$no_barcode]); // ada di pengembalian
            $modelB = TPengembalianLogDetail::findOne(['no_barcode'=>$no_barcode, 'status_penerimaan'=>false]); // belum diterima

            if (substr($_POST['datas'], 0, 5) == "ID : ") {
                if($modelA){
                    if($modelB){
                        $data['status'] = true;
                        $data['msg']    = "Data ok";
                        $data['pengembalian_log_detail_id'] = $modelB->pengembalian_log_detail_id;
                    } else {
                        $data['status'] = false;
                        $data['msg']    = "Data sudah ada";
                        $data['pengembalian_log_detail_id'] = $modelA->pengembalian_log_detail_id;
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
            $pengembalian_log_detail_id = $_GET['id'];
            $modLogKeluar = \app\models\TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
            $modLog = \app\models\HPersediaanLog::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$modLogKeluar->reff_no]);
            $modKayu = MKayu::findOne($modLog->kayu_id);
            return $this->renderAjax('_review', ['modLog' => $modLog, 'modKayu'=>$modKayu, 'pengembalian_log_detail_id'=>$pengembalian_log_detail_id]);
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
                $modDetail = Yii::$app->db->createCommand(" SELECT * FROM t_pengembalian_log_detail
                                                            JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pengembalian_log_detail.no_barcode
                                                            JOIN t_log_keluar ON t_log_keluar.no_barcode = h_persediaan_log.no_barcode 
                                                                AND t_log_keluar.reff_no = h_persediaan_log.reff_no
                                                            WHERE h_persediaan_log.".trim($req['clause'])." = '".trim($req['keyword'])."' 
                                                         ")->queryOne();
                if (count($modDetail) > 0) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modDetail['persediaan_log_id']}\nNo : {$modDetail['no_barcode']}",
						'no_barcode'=> $modDetail['no_barcode'],
                        'id'=>$modDetail['pengembalian_log_detail_id']
                    ]);
                }
            }else {
                $modDetails = Yii::$app->db->createCommand("SELECT * FROM t_pengembalian_log_detail
                                                            JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pengembalian_log_detail.no_barcode
                                                            JOIN t_log_keluar ON t_log_keluar.no_barcode = h_persediaan_log.no_barcode 
                                                                AND t_log_keluar.reff_no = h_persediaan_log.reff_no
                                                            WHERE h_persediaan_log.".trim($req['clause'])." = '".trim($req['keyword'])."' 
                                                            ")->queryAll();
                if(count($modDetails) === 1) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modDetails[0]['persediaan_log_id']}\nNo : {$modDetails[0]['no_barcode']}",
                        'no_barcode'=> $modDetails[0]['no_barcode'],
                        'id'=>$modDetails[0]['pengembalian_log_detail_id']
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
                $param['table'] = TPengembalianLog::tableName();
                $param['pk'] = 't_pengembalian_log.pengembalian_log_id';
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
                                    't_pengembalian_log.pengembalian_log_id' //11
                                    ];
                $param['join'] = [ 'JOIN t_pengembalian_log_detail ON t_pengembalian_log_detail.pengembalian_log_id = t_pengembalian_log.pengembalian_log_id
                                    JOIN h_persediaan_log ON h_persediaan_log.reff_no = t_pengembalian_log.kode
                                    JOIN m_kayu ON m_kayu.kayu_id = h_persediaan_log.kayu_id
                                    JOIN m_pegawai ON m_pegawai.pegawai_id = t_pengembalian_log_detail.penerima'
                                 ];
                $param['where'] = ["t_pengembalian_log_detail.status_penerimaan IS TRUE"];
                $param['group'] = ["GROUP BY h_persediaan_log.persediaan_log_id, m_kayu.kayu_nama, m_pegawai.pegawai_nama, t_pengembalian_log.pengembalian_log_id"];
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
            $model = TPengembalianLog::findOne(['kode'=>$modH->reff_no]);
            $modDetail = TPengembalianLogDetail::findOne(['pengembalian_log_id'=>$model->pengembalian_log_id, 'no_barcode'=>$modH->no_barcode]);
            
            return $this->renderAjax('_lihatDetail', ['modH' => $modH, 'model'=>$model, 'modDetail'=>$modDetail]);
        }
    }

    public function actionPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $caraprint = Yii::$app->request->get('caraprint');
        $pengembalian_log_id = $_GET['id'];
        $no_barcode = $_GET['no_barcode'];
        $paramprint['judul'] = Yii::t('app', 'Print QR Code');
        $qrCodeContent = "ID : " . $pengembalian_log_id .
            "\u000ANo : " . $no_barcode .
            "";
        $model = TPengembalianLog::findOne($pengembalian_log_id);
        $modDetail = HPersediaanLog::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$model->kode]);

        if ($caraprint == 'PRINT') {
            return $this->render('print', ['paramprint' => $paramprint, 'modDetail' => $modDetail, 'qrCodeContent' => $qrCodeContent]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] .'-'. $no_barcode . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('print', ['paramprint' => $paramprint, 'modDetail' => $modDetail]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('print', ['paramprint' => $paramprint, 'modDetail' => $modDetail]);
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
            $model = TPengembalianLog::findOne(['kode'=>$modH->reff_no]);
            $success_1 = Yii::$app->db->createCommand()->update('t_pengembalian_log_detail', 
                                ['status_penerimaan'=>false, 'tanggal_penerimaan'=>null, 'penerima'=>null, 'catatan_penerimaan'=>null], 
                                ['pengembalian_log_id'=>$model->pengembalian_log_id, 'no_barcode'=>$modH->no_barcode])->execute();
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

    public function actionView()
    {
        if (\Yii::$app->request->isAjax) {
            $no_barcode = $_GET['no_barcode'];
            $pengembalian_log_detail_id = $_GET['id'];
            $modDetail = \app\models\TPengembalianLogDetail::findOne($pengembalian_log_detail_id);
            $model = TPengembalianLog::findOne($modDetail->pengembalian_log_id);
            $modH = HPersediaanLog::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$model->kode]);
            $modKayu = \app\models\MKayu::findOne(['kayu_id' => $modH->kayu_id]);
            $title = "<font style='color: #2ebd30;'>PENGEMBALIAN LOG SUDAH DITERIMA</font>";

            return $this->renderAjax('_view', ['modDetail' => $modDetail, 'modKayu' => $modKayu, 'title' => $title, 'modH'=>$modH]);
        }
    }
}
