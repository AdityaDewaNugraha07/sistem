<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use kartik\mpdf\Pdf;

class KirimgudangController extends DeltaBaseController
{
    public $defaultAction = 'index';
    public function actionIndex()
    {
        $model = new \app\models\TKirimGudang();
        $model->tanggal = date("d/m/Y");
        // $model->diketahui = \app\components\Params::DEFAULT_PEGAWAI_ID_TRI_HANDAYANTA;
        // $model->diserahkan = \app\components\Params::DEFAULT_PEGAWAI_ID_ROSA_OKTAVIA_ARDANI;
        $model->diserahkan  = Yii::$app->user->identity->pegawai->pegawai_id;
        if (isset($_GET['kirim_gudang_id'])) {
            $model = \app\models\TKirimGudang::findOne($_GET['kirim_gudang_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
        }

        if (Yii::$app->request->post('TKirimGudang')) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_kirim_gudang
                $success_2 = true; // t_kirim_gudang_detail  // update to terkirim
                $model->load(\Yii::$app->request->post());
                $model->kode = \app\components\DeltaGenerator::kodeKirimGudang();
                $model->total_m3 = number_format($model->total_m3, 4);
                if ($model->validate()) {
                    if ($model->save()) {
                        $success_1 = true;
                        if (isset($_POST['TKirimGudangDetail'])) {
                            foreach ($_POST['TKirimGudangDetail'] as $i => $detail) {
                                $modDetail = \app\models\TKirimGudangDetail::findOne($detail['kirim_gudang_detail_id']);
                                $modDetail->kirim_gudang_id = $model->kirim_gudang_id;
                                $modDetail->terkirim = true;
                                if ($modDetail->save()) {
                                    $success_2 &= true;
                                } else {
                                    $success_2 = false;
                                }
                            }
                        }
                    }
                }
                //                
                //				echo "<pre>1";
                //				print_r($success_1);
                //				echo "<pre>2";
                //				print_r($success_2);
                //				exit;
                //                
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index', 'success' => 1, 'kirim_gudang_id' => $model->kirim_gudang_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg) ? $errmsg : Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        return $this->render('index', ['model' => $model]);
    }

    function actionSetKirim()
    {
        if (\Yii::$app->request->isAjax) {
            $kode = Yii::$app->request->post('kode');
            $data = [];
            $model = new \app\models\TKirimGudang();
            $model->kode = "";
            $model->tanggal = date("d/m/Y");
            // $model->diketahui = \app\components\Params::DEFAULT_PEGAWAI_ID_TRI_HANDAYANTA;
            // $model->diserahkan = \app\components\Params::DEFAULT_PEGAWAI_ID_ROSA_OKTAVIA_ARDANI;
            $model->diserahkan  = Yii::$app->user->identity->pegawai->pegawai_id;
            if (!empty($kode)) {
                $model = \app\models\TKirimGudang::findOne(['kode' => $kode]);
                $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            }
            $data = $model->attributes;
            return $this->asJson($data);
        }
    }
    function actionGetItemsScanned()
    {
        if (\Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $data = [];
            $data['html'] = '';
            $data['status'] = "0";
            if (!empty($id)) {
                $modKirim = \app\models\TKirimGudang::findOne($id);
                if (!empty($modKirim)) {
                    $data['status'] = "1";
                    $modDetail = \app\models\TKirimGudangDetail::find()->where(["kirim_gudang_id" => $id, 'terkirim' => true])->orderBy("kirim_gudang_detail_id DESC")->all();
                } else {
                    $modDetail = \app\models\TKirimGudangDetail::find()->where(["kirim_gudang_id" => $id, 'terkirim' => false])->orderBy("kirim_gudang_detail_id DESC")->all();
                }
            } else {
                $modDetail = \app\models\TKirimGudangDetail::find()->where("terkirim IS FALSE")->orderBy("kirim_gudang_detail_id DESC")->all();
            }
            if (count($modDetail) > 0) {
                foreach ($modDetail as $i => $model) {
                    $modHasilProduksi = \app\models\THasilProduksi::findOne(['nomor_produksi' => $model->nomor_produksi]);
                    $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi' => $model->nomor_produksi]);
                    $model->qty_kecil = $modHasilProduksi->qty_kecil;
                    $model->qty_m3 = number_format($modHasilProduksi->qty_m3, 4);
                    $data['html'] .= $this->renderPartial('_item', ['model' => $model, 'modProduksi' => $modProduksi, 'modHasilProduksi' => $modHasilProduksi]);
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionSaveNomorProduksi()
    {
        if (\Yii::$app->request->isAjax) {
            $data['status'] = false;
            $data['msg'] = "";
            $prod_number = \Yii::$app->request->post('prod_number');
            $kirim_gudang_id = \Yii::$app->request->post('kirim_gudang_id');
            $modProduksi = \app\models\TProduksi::findOne(['nomor_produksi' => $prod_number]);
            $modHasilProduksi = \app\models\THasilProduksi::findOne(['nomor_produksi' => $prod_number]);
            $modKirimDetail = \app\models\TKirimGudangDetail::findOne(['nomor_produksi' => $prod_number]);
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_kirim_gudang_detail
                if (!empty($modProduksi)) {
                    if (!empty($modHasilProduksi)) {
                        if (empty($modKirimDetail)) {
                            $modKirimDetail = new \app\models\TKirimGudangDetail();
                            $modKirimDetail->attributes = $modProduksi->attributes;
                            $modKirimDetail->keterangan = 'Scan Result';
                            if ($modKirimDetail->validate()) {
                                if ($modKirimDetail->save()) {
                                    $success_1 = true;
                                }
                            }
                        } else {
                            $data['status'] = false;
                            $data['msg'] = "Produk sudah pernah dikirim ke gudang";
                        }
                    } else {
                        $data['status'] = false;
                        $data['msg'] = "Produk belum diinput Hasil Produksi!";
                    }
                } else {
                    $data['status'] = false;
                    $data['msg'] = "Produk tidak ditemukan!";
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

    public function actionDeleteNomorProduksi($id)
    {
        if (\Yii::$app->request->isAjax) {
            $model = \app\models\TKirimGudangDetail::findOne(['nomor_produksi' => $id]);
            if (Yii::$app->request->post('deleteRecord')) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {

                    $success_1 = false; // t_kirim_gudang_detail
                    $success_2 = false; // t_kirim_gudang // update total

                    if (!empty($model)) {
                        if ($model->delete()) {
                            $success_1 = true;
                        }
                    }
                    if (!empty($model->kirim_gudang_id)) {
                        $modHasilProds = \app\models\THasilProduksi::find()->where("nomor_produksi = " . $model->nomor_produksi)->all();
                        $modKirim = \app\models\TKirimGudang::findOne($model->kirim_gudang_id);
                        $modKirim->total_palet = count($modHasilProds);
                        foreach ($modHasilProds as $i => $prod) {
                            $modKirim->total_pcs += $prod['qty_pcs'];
                            $modKirim->total_m3 += $prod['qty_m3'];
                        }
                        if ($modKirim->validate()) {
                            if ($modKirim->save()) {
                                $success_2 = true;
                            } else {
                                $success_2 = false;
                            }
                        } else {
                            $success_2 = false;
                        }
                    } else {
                        $success_2 = true;
                    }

                    //                echo "<pre>";
                    //                print_r($success_1);
                    //                echo "<pre>";
                    //                print_r($success_2);
                    //                echo "<pre>";
                    //                print_r($success_3);
                    //                exit;

                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['callback'] = "getItemsScanned();";
                        //						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
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
            return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm', ['id' => $id, 'actionname' => 'deleteNomorProduksi']);
        }
    }

    public function actionStatusPengiriman()
    {
        $model = new \app\models\TKirimGudangDetail();
        if (\Yii::$app->request->get('dt') == 'table-informasi') {
            $param['table'] = \app\models\TKirimGudangDetail::tableName();
            $param['pk'] = $param['table'] . "." . \app\models\TKirimGudangDetail::primaryKey()[0];
            $param['column'] = [
                't_kirim_gudang_detail.kirim_gudang_detail_id', 't_kirim_gudang.kode',
                't_kirim_gudang.tanggal', 't_kirim_gudang_detail.nomor_produksi AS nomor_produksi_kirim', 'm_brg_produk.produk_nama',
                't_hasil_produksi.qty_kecil', 'ROUND(t_hasil_produksi.qty_m3::numeric,4) AS qty_m3', 'm_pegawai2.pegawai_nama AS diserahkan',
                't_terima_ko.nomor_produksi', 't_terima_ko.created_at', 'm_gudang.gudang_nm', 'm_pegawai.pegawai_nama AS petugas_terima', 'm_brg_produk.produk_group'
            ];
            $param['join'] = ['JOIN t_kirim_gudang ON t_kirim_gudang.kirim_gudang_id = t_kirim_gudang_detail.kirim_gudang_id
                               JOIN m_brg_produk ON m_brg_produk.produk_id = t_kirim_gudang_detail.produk_id
                               JOIN t_hasil_produksi ON t_hasil_produksi.nomor_produksi = t_kirim_gudang_detail.nomor_produksi
                               LEFT JOIN m_pegawai AS m_pegawai2 ON m_pegawai2.pegawai_id = t_kirim_gudang.diserahkan
                               LEFT JOIN t_terima_ko ON t_terima_ko.nomor_produksi = t_kirim_gudang_detail.nomor_produksi
                               LEFT JOIN m_gudang ON m_gudang.gudang_id = t_terima_ko.gudang_id
                               LEFT JOIN m_pegawai ON m_pegawai.pegawai_id = t_terima_ko.petugas_penerima
                               '];
            $param['order'] = ['t_kirim_gudang_detail.kirim_gudang_detail_id DESC'];
            return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
        }
        return $this->render('status', ['model' => $model]);
    }

    public function actionBlmditerimaGudang()
    {
        $model = new \app\models\TKirimGudang();
        $model->tgl_awal        = date('d/m/Y', strtotime('first day of this month'));
        $model->tgl_akhir       = date('d/m/Y');
        $dt = \Yii::$app->request->get('dt');
        $lap_params = \Yii::$app->request->get('laporan_params');
        if ($dt == 'table-informasi' && $lap_params !== NULL) {
            $form_params = [];
            parse_str($lap_params, $form_params);
            $model->attributes = $form_params['TKirimGudang'];
            $model->tgl_awal = $form_params['TKirimGudang']['tgl_awal'];
            $model->tgl_akhir = $form_params['TKirimGudang']['tgl_akhir'];
            $model->jenis_produk = $form_params['TKirimGudang']['jenis_produk'];

            return \yii\helpers\Json::encode(\app\components\SSP::complex($model->searchLaporanDt()));
        }

        return $this->render('blmditerimaGudang', ['model' => $model]);
    }

    public function actionBlmditerimaGudangPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $caraprint = Yii::$app->request->get('caraprint');
        $req    = Yii::$app->request->get('TKirimGudang');
        $model  = new \app\models\TKirimGudang();
        $model->attributes  = $req;
        $model->tgl_awal    = !empty($req['tgl_awal']) ? \app\components\DeltaFormatter::formatDateTimeForDb($req['tgl_awal']) : "";
        $model->tgl_akhir   = !empty($req['tgl_akhir']) ? \app\components\DeltaFormatter::formatDateTimeForDb($req['tgl_akhir']) : "";
        $model->jenis_produk = !empty($req['jenis_produk']) ? $req['jenis_produk'] : "";
        $query = $model->searchLaporan()->createCommand()->rawSql;
        $model = Yii::$app->db->createCommand($query)->queryAll();
        $paramprint['judul'] = Yii::t('app', 'Pengiriman Belum Diterima Gudang');

        if ($caraprint === 'PRINT' || $caraprint === 'EXCEL') {
            return $this->renderPartial('/kirimgudang/print', compact('model', 'paramprint'));
        } else {
            $user = Yii::$app->user->getIdentity()->userProfile->fullname;
            $time = date('d/m/Y H:i:s');
            $pdf = new Pdf([
                'mode' => PDF::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content' => $this->renderPartial('/kirimgudang/print', compact('model', 'paramprint')),
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'options' => ['title' => $paramprint['judul']],
                'methods' => [
                    'SetHeader' => ["{$paramprint['judul']} || Generate by {$user} At: {$time}"],
                    'SetFooter' => ['{PAGENO}'],
                ]
            ]);
            return $pdf->render();
        }
    }

    public function actionRekap()
    {
        $model = new \app\models\TKirimGudangDetail();
        if (\Yii::$app->request->get('dt') == 'table-informasi') {
            $param['table'] = \app\models\TKirimGudangDetail::tableName();
            $param['pk'] = $param['table'] . "." . \app\models\TKirimGudangDetail::primaryKey()[0];
            $param['column'] = [
                'm_brg_produk.produk_group', 'count(m_brg_produk.produk_group) as jumlah'
            ];
            $param['join'] = ['JOIN t_kirim_gudang ON t_kirim_gudang.kirim_gudang_id = t_kirim_gudang_detail.kirim_gudang_id
                                   JOIN m_brg_produk ON m_brg_produk.produk_id = t_kirim_gudang_detail.produk_id
                                   JOIN t_hasil_produksi ON t_hasil_produksi.nomor_produksi = t_kirim_gudang_detail.nomor_produksi
                                   LEFT JOIN t_terima_ko ON t_terima_ko.nomor_produksi = t_kirim_gudang_detail.nomor_produksi
                                   '];
            $param['where'] = $param['table'] . ".cancel_transaksi_id IS NULL  AND t_terima_ko.petugas_penerima IS NULL";
            $param['group'] = "GROUP BY 1 ";
            $param['order'] = ['1 DESC'];
            return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
        }
        return $this->render('rekap', ['model' => $model]);
    }

    public function actionGolekiJenengSingNgrejekSu()
    {
        if (\Yii::$app->request->isAjax) {
            $kirim_gudang_detail_id = $_POST['kirim_gudang_detail_id'];
            $modKirimGudangDetail = \app\models\TKirimGudangDetail::findOne(['kirim_gudang_detail_id' => $kirim_gudang_detail_id]);
            $data = [];
            if (!empty($modKirimGudangDetail)) {
                $modRejectReason = \yii\helpers\Json::decode($modKirimGudangDetail->reject_reason);
                $pelaku = "";
                foreach ($modRejectReason as $a => $b) {
                    $modPegawai = \app\models\MPegawai::findOne($b["by"]);
                    $pelaku = $modPegawai->pegawai_nama;
                }
            }
            $data = $pelaku;
            return $this->asJson($data);
        }
    }

    public function actionGolekiJenengSingApproveSu()
    {
        if (\Yii::$app->request->isAjax) {
            $kirim_gudang_detail_id = $_POST['kirim_gudang_detail_id'];
            $modKirimGudangDetail = \app\models\TKirimGudangDetail::findOne(['kirim_gudang_detail_id' => $kirim_gudang_detail_id]);
            $data = [];
            if (!empty($modKirimGudangDetail)) {
                $modAppproveReason = \yii\helpers\Json::decode($modKirimGudangDetail->approve_reason);
                $pelaku = "";
                foreach ($modAppproveReason as $a => $b) {
                    $modPegawai = \app\models\MPegawai::findOne($b["by"]);
                    $pelaku = $modPegawai->pegawai_nama;
                }
            }
            $data = $pelaku;
            return $this->asJson($data);
        }
    }
}
