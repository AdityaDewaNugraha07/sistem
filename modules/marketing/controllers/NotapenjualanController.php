<?php

namespace app\modules\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\HPersediaanBhp;
use app\models\HPersediaanLog;
use app\models\TTerimaLogalamDetail;

class NotapenjualanController extends DeltaBaseController
{

    public $defaultAction = 'index';

    public function actionIndex()
    {
        $model = new \app\models\TNotaPenjualan();
        $model->kode = 'Auto Generate';
//        $model->tanggal = date('d/m/Y');
        $model->total_harga = 0;
        $model->total_bayar = 0;
        $model->total_potongan = 0;
        $model->total_ppn = 0;
        $modSpm = new \app\models\TSpmKo();
        $modSp = new \app\models\TSuratPengantar();
        $modTempo = new \app\models\TTempobayarKo();
        if (isset($_GET['nota_penjualan_id'])) {
            $model = \app\models\TNotaPenjualan::findOne($_GET['nota_penjualan_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modSpm = \app\models\TSpmKo::findOne($model->spm_ko_id);
            $model->kode_spm = $modSpm->kode;
            $model->cust_an_nama = $model->cust->cust_an_nama;
            $model->cust_pr_nama = $model->cust->cust_pr_nama;
            $model->cust_an_alamat = $model->cust->cust_an_alamat;
            $model->cust_is_pkp = $model->cust->cust_is_pkp;
            $model->total_harga = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_harga);
            $model->total_ppn = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_ppn);
            $model->total_potongan = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_potongan);
            $model->total_bayar = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_bayar);
            $modSp = \app\models\TSuratPengantar::findOne(['nota_penjualan_id' => $model->nota_penjualan_id]);
            $modTempoBayar = \app\models\TTempobayarKo::findOne(['op_ko_id' => $model->op_ko_id]);
            $modOpKo = \app\models\TOpKo::findOne($model->op_ko_id);
            if (!empty($modTempoBayar)) {
                $modTempo->attributes = $modTempoBayar->attributes;
                $modTempo->maks_plafon = \app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->maks_plafon);
                $modTempo->sisa_piutang = \app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_piutang);
                $modTempo->sisa_plafon = \app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_plafon);
            }
        }

        if (Yii::$app->request->post('TNotaPenjualan')) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_nota_penjualan
                $success_2 = true; // t_nota_penjualan_detail
                $success_3 = true; // t_surat_pengantar
                $success_4 = true; // t_surat_pengantar_detail
                $success_5 = false; // t_approval
                $model->load(\Yii::$app->request->post());
                $model->status_approval = 'Not Confirmed';
                $model->control_by = \app\components\Params::DEFAULT_PEGAWAI_ID_SUPRIYADI_INTERNALCONTROL;

                $modSp = new \app\models\TSuratPengantar();
                $modSp->attributes = $model->attributes;
                if (!isset($_GET['edit'])) {
                    $model->kode = \app\components\DeltaGenerator::kodeNotaPenjualan($_POST['TNotaPenjualan']['jenis_produk']);
                    $modSp->kode = \app\components\DeltaGenerator::kodeSuratPengantar($_POST['TNotaPenjualan']['jenis_produk']);
                    $model->status = "UNPAID";
                    $modSp->cust_alamat = $model->cust_alamat;
                }
                if ($model->validate()) {
                    if ($model->save()) {
                        $success_1 = true;
                        $modSp->nota_penjualan_id = $model->nota_penjualan_id;
                        if ($modSp->validate()) {
                            if ($modSp->save()) {
                                $success_3 = true;
                            }
                        }
                        if ((isset($_GET['edit'])) && (isset($_GET['nota_penjualan_id']))) {
                            $modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id' => $_GET['nota_penjualan_id']])->all();
                            if (count($modDetail) > 0) {
                                \app\models\TNotaPenjualanDetail::deleteAll(['nota_penjualan_id' => $_GET['nota_penjualan_id']]);
                            }
                            $modDetailSp = \app\models\TSuratPengantarDetail::find()->where(['surat_pengantar_id' => $modSp->surat_pengantar_id])->all();
                            if (count($modDetailSp) > 0) {
                                \app\models\TSuratPengantarDetail::deleteAll(['surat_pengantar_id' => $modSp->surat_pengantar_id]);
                            }
                        }
                        foreach ($_POST['TNotaPenjualanDetail'] as $i => $detail) {
                            $modDetail = new \app\models\TNotaPenjualanDetail();
                            $modDetail->attributes = $detail;
                            $modDetail->nota_penjualan_id = $model->nota_penjualan_id;
                            $modDetail->satuan_kecil = !empty($modDetail->satuan_kecil) ? $modDetail->satuan_kecil : "Pcs";
                            if ($modDetail->validate()) {
                                if ($modDetail->save()) {
                                    $success_2 &= true;
                                    $modDetailSp = new \app\models\TSuratPengantarDetail();
                                    $modDetailSp->attributes = $modDetail->attributes;
                                    $modDetailSp->surat_pengantar_id = $modSp->surat_pengantar_id;
                                    if ($modDetailSp->validate()) {
                                        if ($modDetailSp->save()) {
                                            $success_4 &= true;
                                        } else {
                                            $success_4 = false;
                                        }
                                    } else {
                                        $success_4 = false;
                                    }
                                } else {
                                    $success_2 = false;
                                }
                            } else {
                                $success_2 = false;
                            }
                        }
                        if ($model->total_potongan > 0) {
                            $modApproval = new \app\models\TApproval();
                            $modApproval->assigned_to = \app\components\Params::DEFAULT_PEGAWAI_ID_SUPRIYADI_INTERNALCONTROL;
                            $modApproval->reff_no = $model->kode;
                            $modApproval->tanggal_berkas = $model->tanggal;
                            $modApproval->level = 1;
                            $modApproval->status = "Not Confirmed";
                            $modApproval->parameter1 = "INV";
                            $success_5 = $modApproval->createApproval();

                            $modApproval = new \app\models\TApproval();
                            $modApproval->assigned_to = \app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                            $modApproval->reff_no = $model->kode;
                            $modApproval->tanggal_berkas = $model->tanggal;
                            $modApproval->level = 1;
                            $modApproval->status = "Not Confirmed";
                            $success_5 = $modApproval->createApproval();
                            if ($model->total_potongan > 100000) { // Jika lebih besar dari Rp 100.000
                                $modApproval = new \app\models\TApproval();
                                $modApproval->assigned_to = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO;
                                $modApproval->reff_no = $model->kode;
                                $modApproval->tanggal_berkas = $model->tanggal;
                                $modApproval->level = 2;
                                $modApproval->status = "Not Confirmed";
                                $success_5 = $modApproval->createApproval();
                            }
                        } else {
                            $modApproval = new \app\models\TApproval();
                            $modApproval->assigned_to = \app\components\Params::DEFAULT_PEGAWAI_ID_SUPRIYADI_INTERNALCONTROL;
                            $modApproval->reff_no = $model->kode;
                            $modApproval->tanggal_berkas = $model->tanggal;
                            $modApproval->level = 1;
                            $modApproval->status = "Not Confirmed";
                            $modApproval->parameter1 = "INV";
                            $success_5 = $modApproval->createApproval();
//                                                    $success_5 = true;
                        }
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
//				echo "<pre>5";
//				print_r($success_5);
//				exit;
                // print_r($_POST['TNotaPenjualan']); exit;
                if ($success_1 && $success_2 && $success_3 && $success_4 && $success_5) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index', 'success' => 1, 'nota_penjualan_id' => $model->nota_penjualan_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg) ? $errmsg : Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        if (isset($_GET['nota_penjualan_id'])) {
            return $this->render('index', ['model' => $model, 'modSpm' => $modSpm, 'modSp' => $modSp, 'modTempo' => $modTempo, 'modOpKo'=>$modOpKo]);
        } else {
            return $this->render('index', ['model' => $model, 'modSpm' => $modSpm, 'modSp' => $modSp, 'modTempo' => $modTempo]);
        }
    }

    public function actionSetSPM()
    {
        if (\Yii::$app->request->isAjax) {
            $spm_ko_id = \Yii::$app->request->post('spm_ko_id');
            $data = [];
            $data['tempo'] = [];
            if (!empty($spm_ko_id)) {
                $model = \app\models\TSpmKo::findOne($spm_ko_id);
                $modCust = \app\models\MCustomer::findOne($model->cust_id);
                $modOp = \app\models\TOpKo::findOne($model->op_ko_id);
                $modTempo = \app\models\TTempobayarKo::findOne(['op_ko_id' => $model->op_ko_id]);
                $modLog = \app\models\TSpmLog::findOne(['reff_no'=>$model->kode]);
                if (!empty($model)) {
                    $data = $model->attributes;
                }
                if (!empty($modCust)) {
                    $data['cust'] = $modCust->attributes;
                    $data['cust']['cust_pr_nama'] = (!empty($modCust->cust_pr_nama) ? $modCust->cust_pr_nama : "-");
                }
                if (!empty($modOp)) {
                    $data['jenis_produk'] = $modOp->jenis_produk;
                    $data['op'] = $modOp->attributes;
                    //$data['dokumen_penjualan'] = ($modOp->jenis_produk == "Limbah") ? "" : \app\components\DeltaGenerator::dokumenPenjualan($modOp->jenis_produk);
                }
                if (!empty($modTempo)) {
                    $data['tempo'] = $modTempo->attributes;
                }
                if(!empty($modLog)){
                    $data['log'] = $modLog->attributes;
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionFindSPM()
    {
        if (\Yii::$app->request->isAjax) {
            $term = Yii::$app->request->get('term');
            $data = [];
            $active = "";
            if (!empty($term)) {
                $query = "
					SELECT t_spm_ko.* FROM t_spm_ko 
					LEFT JOIN t_nota_penjualan ON t_nota_penjualan.spm_ko_id = t_spm_ko.spm_ko_id
					WHERE t_spm_ko.kode ilike '%{$term}%' AND t_spm_ko.cancel_transaksi_id IS NULL AND t_spm_ko.status = '" . \app\models\TSpmKo::REALISASI . "' AND nota_penjualan_id IS NULL
					ORDER BY t_spm_ko.created_at";
                $mod = Yii::$app->db->createCommand($query)->queryAll();
                $ret = [];
                if (count($mod) > 0) {
                    $arraymap = \yii\helpers\ArrayHelper::map($mod, 'spm_ko_id', 'kode');
                    foreach ($mod as $i => $val) {
                        $data[] = ['id' => $val['spm_ko_id'], 'text' => $val['kode']];
                    }
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionOpenSPM()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->get('dt') == 'table-spm') {
                $param['table'] = \app\models\TSpmKo::tableName();
                $param['pk'] = $param['table'] . "." . \app\models\TSpmKo::primaryKey()[0];
                $param['column'] = [$param['table'] . '.spm_ko_id',
                    $param['table'] . '.kode',
                    't_op_ko.jenis_produk',
                    $param['table'] . '.tanggal',
                    'm_customer.cust_an_nama',
                    $param['table'] . '.tanggal_kirim',
                    $param['table'] . '.kendaraan_nopol',
                    $param['table'] . '.kendaraan_supir',
                    $param['table'] . '.alamat_bongkar'
                ];
                $param['join'] = ['JOIN m_customer ON m_customer.cust_id = ' . $param['table'] . '.cust_id
								  JOIN t_op_ko ON t_op_ko.op_ko_id = t_spm_ko.op_ko_id
								  LEFT JOIN t_nota_penjualan ON t_nota_penjualan.spm_ko_id = t_spm_ko.spm_ko_id'];
                $param['where'] = $param['table'] . ".cancel_transaksi_id IS NULL AND " . $param['table'] . ".status ='" . \app\models\TSpmKo::REALISASI . "' AND nota_penjualan_id IS NULL AND t_spm_ko.jenis_penjualan = 'lokal'";
                return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
            }
            return $this->renderAjax('spm');
        }
    }

    function actionGetItems()
    {
        if (\Yii::$app->request->isAjax) {
            $spm_ko_id = Yii::$app->request->post('spm_ko_id');
            $jns_produk = Yii::$app->request->post('jns_produk');
            $model = new \app\models\TNotaPenjualan();
            $modSpm = \app\models\TSpmKo::findOne($spm_ko_id);
            $modOpKo = \app\models\TOpKo::findOne($modSpm->op_ko_id);
            $modCust = \app\models\MCustomer::findOne($modSpm->cust_id);
            $modDetail = [];
            $data = [];
            if (!empty($spm_ko_id)) {
                $modSPMDetail = \app\models\TSpmKoDetail::find()->where(['spm_ko_id' => $spm_ko_id])->all();
            }

            $data['html'] = '';

            if ($modOpKo->jenis_produk == "JasaGesek") {
                $modOpKoDetails = \app\models\TOpKoDetail::find()->where(['op_ko_id' => $modOpKo->op_ko_id])->all();
                if (count($modOpKoDetails) > 0) {
                    foreach ($modOpKoDetails as $i => $opdetali) {
                        $modDetail = new \app\models\TNotaPenjualanDetail();
                        $modDetail->attributes = $opdetali->attributes;
                        $modDetail->qty_besar = $opdetali->qty_besar;
                        $modDetail->satuan_besar = $opdetali->satuan_besar;
                        $modDetail->qty_kecil = $opdetali->qty_kecil;
                        $modDetail->satuan_kecil = $opdetali->satuan_kecil;
                        $modDetail->kubikasi = number_format($opdetali->kubikasi, 4);
                        $subtotal = $opdetali->harga_jual * number_format($modDetail->kubikasi, 4);
                        // 2021-02-23 hilangkan desimal
                        //$modDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal );
                        $modDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUser($subtotal, 0);
                        $modDetail->ppn = 0;
                        // 2021-02-23 hilangkan desimal
                        //$modDetail->harga_jual = \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual);
                        $modDetail->harga_jual = \app\components\DeltaFormatter::formatNumberForUser($modDetail->harga_jual, 0);
                        $data['html'] .= $this->renderPartial('_itemSPM', ['model' => $model, 'modDetail' => $modDetail, 'i' => $i, 'modOpKo' => $modOpKo, 'modSpm'=>$modSpm]);
                    }
                }
            } else {
                if (count($modSPMDetail) > 0) {
                    if ($modOpKo->jenis_produk == "Log") {
                        $modSpmLog = Yii::$app->db->createCommand("
                                        SELECT * FROM t_spm_log
                                        JOIN t_spm_ko on t_spm_ko.kode = t_spm_log.reff_no
                                        where reff_no = '".$modSpm->kode."' order by no_barcode, diameter_rata asc")->queryAll();
                        // $modSpmKod = \app\models\TSpmKoDetail::findOne(['spm_ko_id'=>$spm_ko_id]);
                        foreach($modSpmLog as $i => $spmlog){
                            $modDetail = new \app\models\TNotaPenjualanDetail();
                            // $modDetail->attributes = $spmlog->attributes;
                            $modDetail->qty_besar = number_format($spmlog['volume'], 2);
                            $modDetail->satuan_besar = 'M3';
                            $modDetail->qty_kecil = 1;
                            $modDetail->satuan_kecil = 'Pcs';
                            $modDetail->kubikasi = number_format($spmlog['volume'], 2);
                            $modDetail->ppn = 0;
                            $modKayu = \app\models\MKayu::findOne($spmlog['kayu_id']);
                            if($modOpKo->terima_logalam_id){
                                $modTerimaLog = TTerimaLogalamDetail::findOne(['no_barcode'=>$spmlog['no_barcode']]);
                                if($modTerimaLog->fsc){
                                    $fsc = 1;
                                } else {
                                    $fsc = 0;
                                }
                            } else {
                                $modLog = HPersediaanLog::findOne(['no_barcode'=>$spmlog['no_barcode'], 'status'=>'OUT']);
                                if($modLog->fsc){
                                    $fsc = 1;
                                } else {
                                    $fsc = 0;
                                }
                            }
                            $produk = Yii::$app->db->createCommand("
                                                    SELECT * FROM m_brg_log 
                                                    JOIN t_spm_ko_detail on t_spm_ko_detail.produk_id = m_brg_log.log_id
                                                    WHERE kayu_id = {$spmlog['kayu_id']} AND 
                                                    {$spmlog['diameter_rata']} BETWEEN range_awal and range_akhir AND fsc = '{$fsc}' and spm_ko_id = {$spmlog['spm_ko_id']}
                                                    ")->queryOne();
                            $modDetail->produk_id = $produk['log_id'];
                            $harga_jual = $produk['harga_jual'];
                            $modDetail->harga_jual = \app\components\DeltaFormatter::formatNumberForUser($harga_jual, 0); 
                            $subtotal = $harga_jual * $modDetail->kubikasi;
                            $modDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUser($subtotal, 0);
                            $modDetail->spm_log_id = $spmlog['spm_log_id'];
                            $data['html'] .= $this->renderPartial('_itemSPM', ['model' => $model, 'i'=>$i, 'modDetail' => $modDetail, 'modOpKo' => $modOpKo, 'modSpm'=>$modSpm, 'spmlog'=>$spmlog, 'modSPMDetail'=>$modSPMDetail, 'produk'=>$produk, 'modKayu'=>$modKayu]);
                        }
                    } else {
                        foreach ($modSPMDetail as $i => $spmdetail) {
                            $modDetail = new \app\models\TNotaPenjualanDetail();
                            $modDetail->attributes = $spmdetail->attributes;
                            $modDetail->qty_besar = $spmdetail->qty_besar_realisasi;
                            $modDetail->satuan_besar = $spmdetail->satuan_besar_realisasi;
                            $modDetail->qty_kecil = $spmdetail->qty_kecil_realisasi;
                            $modDetail->satuan_kecil = $spmdetail->satuan_kecil_realisasi;
                            $modDetail->kubikasi = number_format($spmdetail->kubikasi_realisasi, 4);
                            $harga_jual = $spmdetail->harga_jual;
                            if ($modOpKo->jenis_produk == "Plywood" || $modOpKo->jenis_produk == "Lamineboard" || $modOpKo->jenis_produk == "Platform" || $modOpKo->jenis_produk == "Limbah" || $modOpKo->jenis_produk == "FingerJointLamineBoard" || $modOpKo->jenis_produk == "FingerJointStick" || $modOpKo->jenis_produk == "Flooring") {
                                $subtotal = $harga_jual * $modDetail->qty_kecil;
                            } else {
                                $subtotal = $harga_jual * number_format($modDetail->kubikasi, 4);
                            }
                            // 2021-02-23 hilangkan desimal
                            //$modDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal );
                            $modDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUser($subtotal, 0);
                            //					if($modCust->cust_is_pkp==TRUE){
                            //						$modDetail->ppn = \app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal * 0.1 );
                            //					}else{
                            //						$modDetail->ppn = 0;
                            //					}
                            $modDetail->ppn = 0;
                            // 2021-02-23 hilangkan desimal
                            //$modDetail->harga_jual = \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual);
                            $modDetail->harga_jual = \app\components\DeltaFormatter::formatNumberForUser($modDetail->harga_jual, 0);

                            $data['html'] .= $this->renderPartial('_itemSPM', ['model' => $model, 'modDetail' => $modDetail, 'i' => $i, 'modOpKo' => $modOpKo, 'modSpm'=>$modSpm]);

                        }
                    }
                }
            }
            return $this->asJson($data);
        }
    }

    function actionGetItemsById()
    {
        if (\Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $edit = Yii::$app->request->post('edit');
            $model = \app\models\TNotaPenjualan::findOne($id);
            $modDetail = [];
            $data = [];
            if (!empty($id)) {
                $modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id' => $id])->all();
            }
            $data['model'] = $model->attributes;
            $data['html'] = '';
            if (count($modDetail) > 0) {
                foreach ($modDetail as $i => $detail) {
                    $modSpmKo = \app\models\TSpmKo::findOne($model->spm_ko_id);
                    $modBrgLog = \app\models\MBrgLog::findOne($detail->produk_id);
                    if ($model->jenis_produk == "Log"){
                        // $modSpmLog = \app\models\TSpmLog::findOne(['reff_no'=>$modSpmKo->kode, 'kayu_id'=>$modBrgLog->kayu_id, 'volume'=>$detail->kubikasi]);
                        $modSpmLog = \app\models\TSpmLog::findOne($detail->spm_log_id);
                        $modKayu = \app\models\MKayu::findOne($modBrgLog->kayu_id);
                    }
                    if ($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform" || $model->jenis_produk == "Limbah" || $model->jenis_produk == "FingerJointLamineBoard" || $model->jenis_produk == "FingerJointStick" || $model->jenis_produk == "Flooring") {
                        $subtotal = $detail->harga_jual * $detail->qty_kecil;
                    } else {
                        $subtotal = $detail->harga_jual * number_format($detail->kubikasi, 4);
                    }
                    //$detail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal );
                    $detail->subtotal = round($subtotal, 0);
                    if ($model->cust_is_pkp == TRUE) {
                        $detail->ppn = \app\components\DeltaFormatter::formatNumberForUserFloat($detail->ppn);
                    } else {
                        $detail->ppn = 0;
                    }
                    if ($edit) {

                    }
                    if ($model->jenis_produk == "Log"){
                        $data['html'] .= $this->renderPartial('_itemAfterSave', ['model' => $model, 'modDetail' => $detail, 'i' => $i, 'edit' => $edit, 'modSpmLog'=>$modSpmLog, 'modKayu'=>$modKayu]);
                    } else {
                        $data['html'] .= $this->renderPartial('_itemAfterSave', ['model' => $model, 'modDetail' => $detail, 'i' => $i, 'edit' => $edit]);
                    }
                    
                }
            }
            return $this->asJson($data);
        }
    }
    public function actionDaftarAfterSave()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->get('dt') == 'modal-aftersave') {
                $param['table'] = \app\models\TNotaPenjualan::tableName();
                $param['pk'] = $param['table'] . "." . \app\models\TNotaPenjualan::primaryKey()[0];
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
                    $param['table'] . '.status', 'm_customer.cust_pr_nama'
                ];
                $param['join'] = ['JOIN t_spm_ko ON t_spm_ko.spm_ko_id = ' . $param['table'] . '.spm_ko_id 
								  JOIN m_customer ON m_customer.cust_id = ' . $param['table'] . '.cust_id'];
                return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
            }
            return $this->renderAjax('daftarAfterSave');
        }
    }

    public function actionCheckApproval()
    {
        if (\Yii::$app->request->isAjax) {
            $nota_penjualan_id = Yii::$app->request->post('nota_penjualan_id');
            $data['status'] = true;
            if (!empty($nota_penjualan_id)) {
                $modNota = \app\models\TNotaPenjualan::findOne($nota_penjualan_id);
                $approval = \app\models\TApproval::find()->where(['reff_no' => $modNota->kode])->andWhere("parameter1 IS NULL")->all();
                if (count($approval) > 0) {
                    foreach ($approval as $i => $appr) {
                        if ($appr->status == "APPROVED") {
                            $data['status'] &= true;
                        } else {
                            $data['status'] = false;
                        }
                    }
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionPrintNota()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = \app\models\TNotaPenjualan::findOne($_GET['id']);
        $modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id' => $_GET['id']])->all();
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'NOTA PENJUALAN');
        if ($caraprint == 'PRINT') {
            return $this->render('printNota', ['model' => $model, 'paramprint' => $paramprint, 'modDetail' => $modDetail]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('printNota', ['model' => $model, 'paramprint' => $paramprint, 'modDetail' => $modDetail]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('printNota', ['model' => $model, 'paramprint' => $paramprint, 'modDetail' => $modDetail]);
        }
    }

    public function actionPrintSP()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = \app\models\TSuratPengantar::findOne($_GET['id']);
        $modDetail = \app\models\TSuratPengantarDetail::find()->where(['surat_pengantar_id' => $_GET['id']])->all();
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'SURAT PENGANTAR');
        if ($caraprint == 'PRINT') {
            return $this->render('printSP', ['model' => $model, 'paramprint' => $paramprint, 'modDetail' => $modDetail]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('printSP', ['model' => $model, 'paramprint' => $paramprint, 'modDetail' => $modDetail]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('printSP', ['model' => $model, 'paramprint' => $paramprint, 'modDetail' => $modDetail]);
        }
    }
    public function actionInfoNota($kode)
    {
        if (\Yii::$app->request->isAjax) {
            $model = \app\models\TNotaPenjualan::findOne(["kode" => $kode]);
            $modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id' => $model->nota_penjualan_id])->all();
            $paramprint['judul'] = Yii::t('app', 'NOTA PENJUALAN');
            return $this->renderAjax('infoNota', ['model' => $model, 'modDetail' => $modDetail, 'paramprint' => $paramprint]);
        }
    }
}
