<?php

namespace app\modules\sysadmin\controllers;

use app\components\DeltaFormatter;
use app\components\DeltaGenerator;
use app\components\Params;
use app\components\SSP;
use app\models\MPegawai;
use app\models\MUser;
use app\models\TApproval;
use app\models\TNotaPenjualan;
use app\models\TNotaPenjualanDetail;
use app\models\TOpKo;
use app\models\TOpKoDetail;
use app\models\TPengajuanManipulasi;
use app\models\TPiutangAlert;
use app\models\TPiutangAlertDetail;
use app\models\TTempobayarKo;
use Yii;
use app\controllers\DeltaBaseController;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Response;

class DatacorrectionController extends DeltaBaseController
{
    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionIndex()
    {
        $model = new TPengajuanManipulasi();
        $model->kode = "AUTO GENERATE";
        $model->tanggal = date("d/m/Y");
        $model->departement_id = Yii::$app->user->identity->pegawai->departement_id;

        if (isset($_GET['pengajuan_manipulasi_id'])) {
            $model = TPengajuanManipulasi::findOne($_GET['pengajuan_manipulasi_id']);
            $model->tanggal = DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modPegawai = MPegawai::findOne(MUser::findOne($model->created_by)->pegawai_id);
            $model->departement_id = $modPegawai->departement_id;
        }

        if (Yii::$app->request->post('TPengajuanManipulasi')) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pengajuan_manipulasi
                $success_2 = false; // t_approval

                $model->load(Yii::$app->request->post());
                if (!isset($_GET['edit'])) {
                    $model->kode = DeltaGenerator::kodeAjuanManipulasiData();
                    $model->tanggal = date('d/m/Y');
                }

                $modNota = TNotaPenjualan::findOne(['kode' => $model->reff_no]);
                if ($model->tipe === "KOREKSI HARGA JUAL") {
                    $modOp = TOpKo::findOne($modNota->op_ko_id);
                    $datadetail_old['t_nota_penjualan'] = $modNota->attributes;
                    $datadetail_new['t_nota_penjualan'] = $modNota->attributes;
                    $datadetail_new['t_nota_penjualan']['total_harga'] = $_POST['TNotaPenjualan']['total_harga'];
                    $datadetail_new['t_nota_penjualan']['total_bayar'] = $_POST['TNotaPenjualan']['total_harga'];
                    foreach ($_POST['TNotaPenjualanDetail'] as $ii => $posDet) {
                        $modNotaDet = TNotaPenjualanDetail::findOne($posDet['nota_penjualan_detail_id']);
                        $opdet = TOpKoDetail::findOne(['op_ko_id' => $modOp->op_ko_id, 'produk_id' => $modNotaDet->produk_id]);
                        $datadetail_old['t_nota_penjualan_detail'][$ii] = $modNotaDet->attributes;
                        $datadetail_old['t_op_ko_detail'][$ii] = $opdet->attributes;
                        $datadetail_new['t_nota_penjualan_detail'][$ii] = $modNotaDet->attributes;
                        $datadetail_new['t_nota_penjualan_detail'][$ii]['harga_jual'] = $posDet['harga_jual_baru']; // update harga jual
                        $datadetail_new['t_op_ko_detail'][$ii] = $opdet->attributes;
                        $datadetail_new['t_op_ko_detail'][$ii]['harga_jual'] = $posDet['harga_jual_baru']; // update harga jual   
                    }
                    $datadetail1['old'] = $datadetail_old;
                    $datadetail1['new'] = $datadetail_new;
                    $model->datadetail1 = Json::encode($datadetail1);
                    $model->approver1 = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $model->approver2 = Params::DEFAULT_PEGAWAI_ID_EKO_NOWO;
                    $model->approver3 = Params::DEFAULT_PEGAWAI_ID_ASENG;
                    $model->approver4 = Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                } else if ($model->tipe === "KOREKSI NOPOL MOBIL") {
                    $datadetail1['supir_old'] = isset($_POST['TPengajuanManipulasi'][0]['supir_old']['kendaraan_supir_lama']) ? $_POST['TPengajuanManipulasi'][0]['supir_old']['kendaraan_supir_lama'] : null;
                    $datadetail1['supir_new'] = isset($_POST['TPengajuanManipulasi'][0]['supir_new']['kendaraan_supir_baru']) ? $_POST['TPengajuanManipulasi'][0]['supir_new']['kendaraan_supir_baru'] : null;
                    $datadetail1['old'] = isset($_POST['TPengajuanManipulasi'][0]['old']['nopol_lama']) ? $_POST['TPengajuanManipulasi'][0]['old']['nopol_lama'] : null;
                    $datadetail1['new'] = isset($_POST['TPengajuanManipulasi'][0]['new']['nopol_baru']) ? $_POST['TPengajuanManipulasi'][0]['new']['nopol_baru'] : null;
                    $model->datadetail1 = Json::encode($datadetail1);
                    $model->approver1 = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                } else if ($model->tipe === "KOREKSI ALAMAT BONGKAR") {
                    $datadetail1['old'] = isset($_POST['TPengajuanManipulasi'][0]['old']['alamat_bongkar_lama']) ? $_POST['TPengajuanManipulasi'][0]['old']['alamat_bongkar_lama'] : null;
                    $datadetail1['new'] = isset($_POST['TPengajuanManipulasi'][0]['new']['alamat_bongkar_baru']) ? $_POST['TPengajuanManipulasi'][0]['new']['alamat_bongkar_baru'] : null;
                    $model->datadetail1 = Json::encode($datadetail1);
                    $model->approver1 = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                } else if ($model->tipe === "POTONGAN PIUTANG") {
                    $modTempo = TTempobayarKo::findOne($modNota->op_ko_id);
                    $modPiutang = new \app\models\TPiutangPenjualan();
                    $modPiutang->load(Yii::$app->request->post());
                    $modPiutang->kode = null;
                    $modPiutang->tipe = 'lokal';
                    $modPiutang->tanggal = date("Y-m-d");
                    $modPiutang->cust_id = $modNota->cust_id;
                    $modPiutang->tanggal_bill = $modNota->tanggal;
                    $modPiutang->cara_bayar = "Potongan";
                    $modPiutang->payment_reff = "Potongan";
                    $modPiutang->mata_uang = "IDR";
                    $modPiutang->status_bayar = "PAID";
                    $modPiutang->tanggal_bayar = $modPiutang->tanggal;
                    $modPiutang->sisa = $modPiutang->tagihan - $modPiutang->bayar;
                    $modPiutang->custtop_top = $modTempo !== null ? $modTempo : 0;
                    $modPiutang->keterangan = "Pengajuan Koreksi Data";
                    $total_tagihan = $modNota->total_bayar;
                    $sisa_piutang = $modPiutang->sisa;
                    $status = '';
                    if ($total_tagihan === $sisa_piutang) {
                        $status = "UNPAID";
                    } else if ($sisa_piutang > 0) {
                        $status = "PARTIALLY";
                    } else if ($sisa_piutang === 0) {
                        $status = "PAID";
                    } else if ($sisa_piutang < 0) {
                        $status = "PAID";
                    }
                    $modNota->status = $status;
//                    $modNota->total_potongan = $modPiutang->bayar;
                    $datadetail1['new']['t_piutang_penjualan'] = $modPiutang->attributes;
                    $datadetail1['new']['t_nota_penjualan'] = $modNota->attributes;
                    $model->datadetail1 = Json::encode($datadetail1);
                    $model->approver1 = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $model->approver2 = Params::DEFAULT_PEGAWAI_ID_EKO_NOWO;
                    $model->approver3 = Params::DEFAULT_PEGAWAI_ID_ASENG;
                    $model->approver4 = Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                } else if ($model->tipe === "KOREKSI PIUTANG LOG & JASA") {
                    if (!empty($_POST['TPiutangAlertDetail'])) {
                        foreach ($_POST['TPiutangAlertDetail'] as $i => $detail) {
                            $modAlertDetail = TPiutangAlertDetail::findOne($detail['piutang_alert_detail_id']);
                            $modAlert = TPiutangAlert::findOne($modAlertDetail->piutang_alert_id);
                            $datadetail_old['t_piutang_alert_detail'][$i] = $modAlertDetail->attributes;
                            $datadetail_old['t_piutang_alert'] = $modAlert->attributes;

                            $datadetail_new['t_piutang_alert_detail'][$i] = $modAlertDetail->attributes;
                            $datadetail_new['t_piutang_alert_detail'][$i]['sisa_bayar'] = $detail['sisa_bayar'];
                            $datadetail_new['t_piutang_alert_detail'][$i]['potongan'] = $detail['potongan'];
                            $datadetail_new['t_piutang_alert_detail'][$i]['sisa_bayar_baru'] = $detail['sisa_bayar_baru'];

                            $datadetail_new['t_piutang_alert'] = $modAlert->attributes;
                            $datadetail_new['t_piutang_alert']['potongan'] = $_POST['TPiutangAlert']['potongan'];
                            $datadetail_new['t_piutang_alert']['sisa_bayar_baru'] = $_POST['TPiutangAlert']['sisa_bayar_baru'];
                        }
                    }
                    $datadetail1['old'] = $datadetail_old;
                    $datadetail1['new'] = $datadetail_new;
                    $model->datadetail1 = Json::encode($datadetail1);
                    $model->approver1 = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $model->approver2 = Params::DEFAULT_PEGAWAI_ID_EKO_NOWO;
                    $model->approver3 = Params::DEFAULT_PEGAWAI_ID_ASENG;
                    $model->approver4 = Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                }

                if ($model->validate() && $model->save()) {
                    $success_1 = true;

                    // START Create Approval
                    $modelApproval = TApproval::find()->where(['reff_no' => $model->kode])->all();
                    if (count($modelApproval) > 0) { // edit mode
                        if (TApproval::deleteAll(['reff_no' => $model->kode])) {
                            $success_2 = $this->saveApproval($model);
                        }
                    } else { // insert mode
                        $success_2 = $this->saveApproval($model);
                    }
                    // END Create Approval
                }
                if ($success_1 && $success_2) {
                    if($transaction !== null) {
                        $transaction->commit();
                    }
                    Yii::$app->session->setFlash('success', Yii::t('app', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE));
                    return $this->redirect(['index', 'success' => 1, 'pengajuan_manipulasi_id' => $model->pengajuan_manipulasi_id]);
                }

                $transaction->rollback();
                Yii::$app->session->setFlash('error',  Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
            } catch (Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex->getMessage());
            }
        }

        return $this->render('index', ['model' => $model]);
    }

    public function saveApproval($model)
    {
        $success = true;
        if ($model->approver1) {
            $modelApproval = new TApproval();
            $modelApproval->assigned_to = $model->approver1;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 1;
            $modelApproval->parameter1 = 'Data Correction';
            $modelApproval->status = TApproval::STATUS_NOT_CONFIRMATED;
            $success &= $modelApproval->createApproval();
        }
        if ($model->approver2) {
            $modelApproval = new TApproval();
            $modelApproval->assigned_to = $model->approver2;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 2;
            $modelApproval->parameter1 = 'Data Correction';
            $modelApproval->status = TApproval::STATUS_NOT_CONFIRMATED;
            $success &= $modelApproval->createApproval();
        }
        if ($model->approver3) {
            $modelApproval = new TApproval();
            $modelApproval->assigned_to = $model->approver3;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 3;
            $modelApproval->parameter1 = 'Data Correction';
            $modelApproval->status = TApproval::STATUS_NOT_CONFIRMATED;
            $success &= $modelApproval->createApproval();
        }
        if ($model->approver4) {
            $modelApproval = new TApproval();
            $modelApproval->assigned_to = $model->approver4;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 4;
            $modelApproval->parameter1 = 'Data Correction';
            $modelApproval->status = TApproval::STATUS_NOT_CONFIRMATED;
            $success &= $modelApproval->createApproval();
        }
        return $success;
    }

    /**
     * @return string|void
     */
    public function actionPrintDK()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = TPengajuanManipulasi::findOne($_GET['id']);
        $modPegawai = MPegawai::findOne(MUser::findOne($model->created_by)->pegawai_id);
        $model->departement_id = $modPegawai->departement_id;
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'ORDER PENJUALAN');
        if ($caraprint === 'PRINT') {
            return $this->render('_printDK', ['model' => $model, 'paramprint' => $paramprint]);
        }

        if ($caraprint === 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('_printDK', ['model' => $model, 'paramprint' => $paramprint]);
            return $pdf->render();
        }

        if ($caraprint === 'EXCEL') {
            return $this->render('_printDK', ['model' => $model, 'paramprint' => $paramprint]);
        }
    }

    public function actionGetReff()
    {
        if (Yii::$app->request->isAjax) {
            $tipe = Yii::$app->request->post('tipe');
            $pengajuan_manipulasi_id = Yii::$app->request->post('pengajuan_manipulasi_id');
            if (!empty($pengajuan_manipulasi_id)) {
                $model = TPengajuanManipulasi::findOne($pengajuan_manipulasi_id);
            } else {
                $model = new TPengajuanManipulasi();
            }
            $data['html'] = '';
            $data['html_reff'] = $this->renderPartial('_reff_no', ['model' => $model, 'tipe' => $tipe]);
            return $this->asJson($data);
        }
    }

    public function actionOpennota()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->get('dt') == 'modal-open-nota') {
                $param['table'] = TNotaPenjualan::tableName();
                $param['pk'] = $param['table'] . "." . TNotaPenjualan::primaryKey()[0];
                $param['column'] = [$param['table'] . '.nota_penjualan_id',
                    $param['table'] . '.kode AS kode_nota',
                    $param['table'] . '.jenis_produk',
                    $param['table'] . '.tanggal',
                    'm_customer.cust_an_nama',
                    't_spm_ko.kode',
                    $param['table'] . '.kendaraan_nopol',
                    $param['table'] . '.kendaraan_supir',
                    $param['table'] . '.alamat_bongkar',
                    $param['table'] . '.total_bayar',
                    $param['table'] . '.cancel_transaksi_id',
                    $param['table'] . '.status',
                ];
                $param['join'] = ['JOIN t_spm_ko ON t_spm_ko.spm_ko_id = ' . $param['table'] . '.spm_ko_id 
                                    JOIN m_customer ON m_customer.cust_id = ' . $param['table'] . '.cust_id'];
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('openNota');
        }
    }

    public function actionOpenlogjasa()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->get('dt') == 'modal-open-logjasa') {
                $param['table'] = TPiutangAlert::tableName();
                $param['pk'] = $param['table'] . "." . TPiutangAlert::primaryKey()[0];
                $param['column'] = [$param['table'] . '.piutang_alert_id',
                    $param['table'] . '.piutang_nomor_nota',
                    $param['table'] . '.piutang_jenis',
                    $param['table'] . '.tgl_nota',
                    'm_customer.cust_an_nama',
                    'm_customer.cust_an_alamat',
                    $param['table'] . '.tempo_bayar',
                    $param['table'] . '.tagihan_jml',
                    $param['table'] . '.created_at',
                    'm_pegawai.pegawai_nama',
                ];
                $param['join'] = ['JOIN m_customer ON m_customer.cust_id = ' . $param['table'] . '.customer_id
                                  JOIN m_pegawai ON m_pegawai.pegawai_id = ' . $param['table'] . '.created_by'];
                $param['where'] = "piutang_jenis != 1";
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('openLogjasa');
        }
    }

    function actionPickNota()
    {
        if (Yii::$app->request->isAjax) {
            $tipe = Yii::$app->request->post('tipe');
            $pengajuan_manipulasi_id = Yii::$app->request->post('pengajuan_manipulasi_id');
            $kode = Yii::$app->request->post('kode');
            $model = new TPengajuanManipulasi();
            if (!empty($pengajuan_manipulasi_id)) {
                $model = TPengajuanManipulasi::findOne($pengajuan_manipulasi_id);
            }
            $data['html'] = "";
            $data['pmr_id'] = "";
            $modReff = "";
            if (!empty($tipe)) {
                if (empty($model->tanggal)) {
                    $tanggal_manipulasi = date('Y-m-d');
                } else {
                    $tanggal_manipulasi = $model->tanggal;
                }
                $tanggal_berlaku = '2021-10-01';
                if ($tipe == "KOREKSI HARGA JUAL" || $tipe == "KOREKSI NOPOL MOBIL" || $tipe == "KOREKSI ALAMAT BONGKAR" || $tipe == "POTONGAN PIUTANG") {
                    if (!empty($kode)) {
                        $modNota = TNotaPenjualan::findOne(['kode' => $kode]);
                        $modCust = \app\models\MCustomer::findOne(['cust_id' => $modNota->cust_id]);
                        $data['cust'] = $modCust->attributes;
                    }
                }
                if ($tipe == "KOREKSI HARGA JUAL") {
                    $data['approver1'] = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $data['approver2'] = Params::DEFAULT_PEGAWAI_ID_EKO_NOWO;
                    if ($tanggal_manipulasi < $tanggal_berlaku) {
                        $data['approver3'] = Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                    } else {
                        $data['approver3'] = Params::DEFAULT_PEGAWAI_ID_ASENG;
                    }
                    $data['approver4'] = Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;

                    $data['approver1_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO)->pegawai_nama;
                    $data['approver2_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_EKO_NOWO)->pegawai_nama;
                    if ($tanggal_manipulasi < $tanggal_berlaku) {
                        $data['approver3_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
                    } else {
                        $data['approver3_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_ASENG)->pegawai_nama;
                    }
                    $data['approver4_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
                }
                if ($tipe == "KOREKSI NOPOL MOBIL") {
                    if (!empty($model->datadetail1)) {
                        $datadetail1 = Json::decode($model->datadetail1);
                    }
                    $data['approver1'] = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $data['approver1_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO)->pegawai_nama;
                    $model->supir_old = $modNota->kendaraan_supir;
                    $model->supir_new = !empty($datadetail1) ? $datadetail1['supir_new'] : $modNota->kendaraan_supir;
                    $model->nopol_lama = $modNota->kendaraan_nopol;
                    $model->nopol_baru = !empty($datadetail1) ? $datadetail1['new'] : $modNota->kendaraan_nopol;
                }
                if ($tipe == "KOREKSI ALAMAT BONGKAR") {
                    if (!empty($model->datadetail1)) {
                        $datadetail1 = Json::decode($model->datadetail1);
                    }
                    $data['approver1'] = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $data['approver1_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO)->pegawai_nama;
                    $model->alamat_bongkar_lama = $modNota->alamat_bongkar;
                    $model->alamat_bongkar_baru = !empty($datadetail1) ? $datadetail1['new'] : $modNota->alamat_bongkar;
                }
                if ($tipe == "POTONGAN PIUTANG") {
                    if (!empty($model->datadetail1)) {
                        $datadetail1 = Json::decode($model->datadetail1);
                    }
                    $data['approver1'] = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $data['approver2'] = Params::DEFAULT_PEGAWAI_ID_EKO_NOWO;
                    if ($tanggal_manipulasi < $tanggal_berlaku) {
                        $data['approver3'] = Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                    } else {
                        $data['approver3'] = Params::DEFAULT_PEGAWAI_ID_ASENG;
                    }
                    $data['approver4'] = Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                    $data['approver1_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO)->pegawai_nama;
                    $data['approver2_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_EKO_NOWO)->pegawai_nama;
                    if ($tanggal_manipulasi < $tanggal_berlaku) {
                        $data['approver3_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
                    } else {
                        $data['approver3_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_ASENG)->pegawai_nama;
                    }
                    $data['approver4_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
                    $modReff = new \app\models\TPiutangPenjualan();
                    if (!empty($model->datadetail1)) {
                        $datadetail1 = Json::decode($model->datadetail1);
                    }
                    $modReff->bill_reff = $modNota->kode;
                    $modReff->nominal_bill = number_format($modNota->total_bayar);
                    $sql = "SELECT * FROM t_piutang_penjualan WHERE bill_reff = '" . $modNota->kode . "' AND cancel_transaksi_id IS NULL -- AND cara_bayar != 'Potongan'"; // perubahan tgl 11/07/2023
                    $modPiutangs = Yii::$app->db->createCommand($sql)->queryAll();
                    $terbayar = 0;
                    if (count($modPiutangs) > 0) {
                        foreach ($modPiutangs as $ii => $piutang) {
                            $terbayar += $piutang['bayar'];
                        }
                    }
                    $modReff->nominal_terbayar = number_format($terbayar);
                    $modReff->tagihan = $modNota->total_bayar - $terbayar !== null ? number_format($modNota->total_bayar - $terbayar) : 0;
                    $modReff->bayar = !empty($datadetail1) ? number_format($datadetail1['new']['t_piutang_penjualan']['bayar']) : 0;
                    $modReff->sisa = !empty($datadetail1) ? number_format($datadetail1['new']['t_piutang_penjualan']['sisa']) : number_format(($modNota->total_bayar - $terbayar) - $modReff->bayar);
                }
                if ($tipe == "KOREKSI PIUTANG LOG & JASA") {
                    $data['approver1'] = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $data['approver2'] = Params::DEFAULT_PEGAWAI_ID_EKO_NOWO;
                    if ($tanggal_manipulasi < $tanggal_berlaku) {
                        $data['approver3'] = Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                    } else {
                        $data['approver3'] = Params::DEFAULT_PEGAWAI_ID_ASENG;
                    }
                    $data['approver4'] = Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                    $data['approver1_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO)->pegawai_nama;
                    $data['approver2_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_EKO_NOWO)->pegawai_nama;
                    if ($tanggal_manipulasi < $tanggal_berlaku) {
                        $data['approver3_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
                    } else {
                        $data['approver3_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_ASENG)->pegawai_nama;
                    }
                    $data['approver4_display'] = MPegawai::findOne(Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->pegawai_nama;
                    $modReff = TPiutangAlert::findOne(['piutang_nomor_nota' => $kode]);
                }
            }
            $data['html_berkas_reff'] = $this->renderPartial('_reff_berkas', ['tipe' => $tipe, 'model' => (isset($modNota) ? $modNota : null)]);
            $data['html_koreksi'] = $this->renderPartial('_koreksi', ['tipe' => $tipe, 'model' => (isset($modNota) ? $modNota : null), 'modAjuan' => $model, 'modReff' => $modReff]);
            return $this->asJson($data);
        }
    }

    function actionGetItemNotaPenjualan()
    {
        if (Yii::$app->request->isAjax) {

            $tipe = Yii::$app->request->post('tipe');
            $id = Yii::$app->request->post('id');
            $model = TPengajuanManipulasi::findOne($id);
            $modNota = TNotaPenjualan::findOne(['kode' => $model->reff_no]);
            $data['html'] = $this->renderPartial('_koreksi', ['tipe' => $tipe, 'model' => $modNota, 'modelAjuan' => $model]);
            $modCust = \app\models\MCustomer::findOne(['cust_id' => $modNota->cust_id]);
            $data['cust'] = $modCust->attributes;
            return $this->asJson($data);
        }
    }

    /**
     * @return string|void
     */
//    public function actionDaftarAfterSave()
//    {
//        if (Yii::$app->request->isAjax) {
//            $pick = Yii::$app->request->get('pick');
//            if (Yii::$app->request->get('dt') === 'modal-aftersave') {
//                $param['table'] = TPengajuanManipulasi::tableName();
//                $param['pk'] = $param['table'] . "." . TPengajuanManipulasi::primaryKey()[0];
//                $param['column'] = [$param['table'] . '.pengajuan_manipulasi_id',
//                    $param['table'] . '.kode',
//                    $param['table'] . '.tanggal',
//                    $param['table'] . '.tipe',
//                    $param['table'] . '.reff_no',
//                    $param['table'] . '.priority',
//                    $param['table'] . '.reason',
//                    'peg1.pegawai_nama AS approver_1',
//                    '(SELECT status FROM t_approval WHERE reff_no = t_pengajuan_manipulasi.kode AND assigned_to = t_pengajuan_manipulasi.approver1) AS approver_1_status',
//                    'peg2.pegawai_nama AS approver_2',
//                    '(SELECT status FROM t_approval WHERE reff_no = t_pengajuan_manipulasi.kode AND assigned_to = t_pengajuan_manipulasi.approver2) AS approver_2_status',
//                    'peg3.pegawai_nama AS approver_3',
//                    '(SELECT status FROM t_approval WHERE reff_no = t_pengajuan_manipulasi.kode AND assigned_to = t_pengajuan_manipulasi.approver3) AS approver_3_status',
//                    'peg4.pegawai_nama AS approver_4',
//                    '(SELECT status FROM t_approval WHERE reff_no = t_pengajuan_manipulasi.kode AND assigned_to = t_pengajuan_manipulasi.approver4) AS approver_4_status',
//                ];
//                $param['join'] = ['LEFT JOIN m_pegawai AS peg1 ON peg1.pegawai_id = ' . $param['table'] . '.approver1
//                                  LEFT JOIN m_pegawai AS peg2 ON peg2.pegawai_id = ' . $param['table'] . '.approver2
//                                  LEFT JOIN m_pegawai AS peg3 ON peg3.pegawai_id = ' . $param['table'] . '.approver3
//                                  LEFT JOIN m_pegawai AS peg4 ON peg4.pegawai_id = ' . $param['table'] . '.approver4
//								  '];
//                $param['where'] = "cancel_transaksi_id IS NULL AND tipe NOT IN('CETAK ULANG LABEL PRODUK') ";
//                if (Yii::$app->user->identity->user_group_id !== Params::USER_GROUP_ID_SUPER_USER) {
//                    $param['where'] .= " "; //AND
//                }
//                return Json::encode(SSP::complex($param));
//            }
//            return $this->renderAjax('daftarAfterSave', ['pick' => $pick]);
//        }
//    }

    /**
     * @return string|void
     */
    public function actionDaftarAfterSave()
    {
        if (Yii::$app->request->isAjax) {
            $pick = Yii::$app->request->get('pick');
            if (Yii::$app->request->get('dt') === 'modal-aftersave') {
                $param['table'] = TPengajuanManipulasi::tableName();
                $param['pk'] = $param['table'] . "." . TPengajuanManipulasi::primaryKey()[0];
                $param['column'] = [$param['table'] . '.pengajuan_manipulasi_id',
                    $param['table'] . '.kode',
                    $param['table'] . '.tanggal',
                    $param['table'] . '.tipe',
                    $param['table'] . '.reff_no',
                    $param['table'] . '.priority',
                    $param['table'] . '.reason',
                    'approver1.assigned_nama as approver_1',
                    'approver1.status as approver_1_status',
                    'approver2.assigned_nama as approver_2',
                    'approver2.status as approver_2_status',
                    'approver3.assigned_nama as approver_3',
                    'approver3.status as approver_3_status',
                    'approver4.assigned_nama as approver_4',
                    'approver4.status as approver_4_status'
                ];
                $param['join'] = ['
                    LEFT JOIN view_approval approver1 ON t_pengajuan_manipulasi.kode = approver1.reff_no AND t_pengajuan_manipulasi.approver1 = approver1.assigned_to 
	                LEFT JOIN view_approval approver2 ON t_pengajuan_manipulasi.kode = approver2.reff_no AND t_pengajuan_manipulasi.approver2 = approver2.assigned_to
	                LEFT JOIN view_approval approver3 ON t_pengajuan_manipulasi.kode = approver3.reff_no AND t_pengajuan_manipulasi.approver3 = approver3.assigned_to
	                LEFT JOIN view_approval approver4 ON t_pengajuan_manipulasi.kode = approver4.reff_no AND t_pengajuan_manipulasi.approver4 = approver4.assigned_to
				'];
                $param['where'] = "cancel_transaksi_id IS NULL AND tipe NOT IN('CETAK ULANG LABEL PRODUK', 'PERMINTAAN HAPUS SPM EXPORT') ";
                if (Yii::$app->user->identity->user_group_id !== Params::USER_GROUP_ID_SUPER_USER) {
                    $param['where'] .= " "; //AND
                }
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('daftarAfterSave', ['pick' => $pick]);
        }
    }
}
