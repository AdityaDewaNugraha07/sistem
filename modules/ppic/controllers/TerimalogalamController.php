<?php

namespace app\modules\ppic\controllers;

use Yii;
use yii\db\Exception;
use yii\helpers\Json;
use app\components\SSP;
use app\components\Params;
use app\models\TTerimaLogalam;
use app\components\DeltaFormatter;
use app\components\DeltaGenerator;
use app\models\TTerimaLogalamDetail;
use app\models\TTerimaLogalamPabrik;
use app\controllers\DeltaBaseController;
use app\models\MCustomer;
use app\models\TPengajuanPembelianlog;
use yii\helpers\Url;

class TerimalogalamController extends DeltaBaseController
{
    public $defaultAction = 'index';
    public function actionIndex()
    {
        $model = new TTerimaLogalam();
        $modDetail = new TTerimaLogalamDetail();
        $model->kode = 'Auto Generate';
        $model->area_pembelian = "Luar Jawa";
        $model->peruntukan = "Industri";
        $model->lokasi_tujuan = "PT. CIPTA WIJAYA MANDIRI";
        $model->alamat_tujuan = "Jl. Raya Semarang - Purwodadi Km 16.5 No. 349 Mranggen Demak Jawa Tengah 59567";
        $model->tanggal = DeltaFormatter::formatDateTimeForUser2(date("Y-m-d"));

        // EDIT 
        if (isset($_GET['terima_logalam_id'])) {
            $model = TTerimaLogalam::findOne($_GET['terima_logalam_id']);
            $model->tanggal = DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modDetail = TTerimaLogalamDetail::find()->where(['terima_logalam_id' => $model->terima_logalam_id])->orderBy(['terima_logalam_detail_id' => SORT_ASC])->all();
        }
        // eo EDIT

        // INPUT TERIMA LOG ALAM atau TERIMA INPUT LOG ALAM DETAIL
        if (Yii::$app->request->isPost) {
            $master         = Yii::$app->request->post('TTerimaLogalam');
            $detail         = Yii::$app->request->post('TTerimaLogalamDetail');
            $transaction    = Yii::$app->db->beginTransaction();
            try {
                // MASTER
                if ($master['terima_logalam_id'] !== "") { // jika ada ID maka edit
                    $model = TTerimaLogalam::findOne($master['terima_logalam_id']);
                }
                $model->attributes = $master;
                $model->kode = DeltaGenerator::kodeTerimaLogAlam();
                $model->spk_shipping_id = isset($master['spk_shipping_id']) ? $master['spk_shipping_id'] : null;
                $model->pengajuan_pembelianlog_id = isset($master['pengajuan_pembelianlog_id']) ? $master['pengajuan_pembelianlog_id'] : null;
                $model->tanggal = date_format(date_create_from_format('d/m/Y', $master['tanggal']), 'Y-m-d');
                $model->cetak  = 1;
                // $customer = isset($master['lokasi_tujuan']) ? $master['lokasi_tujuan'] : null;
                // if($customer){
                //     if (strpos($customer, '-') !== false) {
                //         $customer = explode("-", $customer);
                //         $customer = trim($customer[0]);
                //         $model->lokasi_tujuan = $customer;
                //     }
                // }
                
                if (!$model->validate() || !$model->save()) {
                    throw new Exception(array_values($model->firstErrors)[0]);
                }
                
                // var_dump($model->attributes);die;
                // DETAIL

                TTerimaLogalamDetail::deleteAll(['terima_logalam_id' => $model->terima_logalam_id]);
                usort($detail, function($a, $b) {return $a['next_nomor'] > $b['next_nomor'];});
                foreach ($detail as $key => $dtl) {
                    switch($dtl['kode_potong']) {
                        case '01': $kode_potong = 'A';break;
                        case '02': $kode_potong = 'B';break;
                        case '03': $kode_potong = 'C';break;
                        default: $kode_potong = '';   
                    }

                    $modDetail = new TTerimaLogalamDetail();
                    $modDetail->attributes = $dtl;
                    $modDetail->terima_logalam_id = $model->terima_logalam_id;

                    // TAMBAH FSC - jawa - pengajuan_pembelianlog_id jg diisi
                    if($model->area_pembelian == 'Jawa'){
                        $modDetail->pengajuan_pembelianlog_id = $model->pengajuan_pembelianlog_id;
                    }
                    //eo FSC

                    // barcode
                    $sql =  "   SELECT RIGHT( no_barcode, 3 )                       " .
                        "   FROM t_terima_logalam_detail                            " .
                        "   WHERE terima_logalam_id = $model->terima_logalam_id     " .
                        "   GROUP BY terima_logalam_detail_id                       " .
                        "   ORDER BY terima_logalam_detail_id DESC                  " .
                        "   LIMIT 1                                                 ";
                    $last_barcode = Yii::$app->db->createCommand($sql)->queryScalar();
                    $seq = $last_barcode ? (int)$last_barcode : 1;
                    $modDetail->no_barcode = substr($model->kode, 3) . sprintf("%03d", $seq + 1);
                    // endbarcode
                    if($model->peruntukan === 'Industri') {
                        $modDetail->no_lap = DeltaGenerator::kodeLapTerimaLogalam($_POST['kode_partai'], $kode_potong, $dtl['no_btg'], $modDetail->no_grade, $modDetail->no_produksi, $modDetail->terima_logalam_id);
                        // var_dump($modDetail->no_lap);
                    }
                    $modDetail->kode_potong = $kode_potong;
                    // var_dump($modDetail->attributes);die;
                    if (!$modDetail->validate() || !$modDetail->save()) {
                        throw new Exception(array_values($modDetail->firstErrors)[0]);
                    }
                }
                // print_r($detail); exit;

                // die;
                $transaction->commit();
                return Json::encode(['status' => true, 'message' => Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE, 'data' => $model->attributes]);
            } catch (Exception $ex) {
                $transaction->rollback();
                return Json::encode(['status' => false, 'message' => $ex->getMessage()]);
            }
        }
        // eo INPUT
        return $this->render('index', ['model' => $model, 'modDetail' => $modDetail]);
    }

    public function actionDaftarPenerimaanLogAlam()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->get('dt') == 'modal-daftarPenerimaanLogAlam') {
                $param['table']     = TTerimaLogalam::tableName();
                $param['pk']        = TTerimaLogalam::primaryKey()[0];
                $param['column']    = ['kode', 'tanggal', 'area_pembelian', 'no_truk', 'no_dokumen', 'peruntukan', 'lokasi_tujuan', 'pegawai_nama', 'terima_logalam_id', 'cetak'];
                $param['join']      = ['JOIN m_pegawai ON m_pegawai.pegawai_id = ' . $param['table'] . '.pic_ukur'];
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('_daftarPenerimaanLogAlam');
        }
    }

    public function actionFindPengajuanPembelianLog()
    {
        if (\Yii::$app->request->isAjax) {
            $term = Yii::$app->request->get('term');
            $data = [];
            $active = "";
            if (!empty($term)) {
                $sql = " select * from t_pengajuan_pembelianlog " .
                    "   where 1=1 " .
                    "   and kode ilike '%{$term}%' " .
                    "   and status = 'APPROVED' " .
                    "   and spk_shipping_id > 0 " .
                    "   order by kode ASC " .
                    "   ";
            } else {
                $sql = " select * from t_pengajuan_pembelianlog " .
                    "   where 1=1 " .
                    "   and status = 'APPROVED' " .
                    "   and spk_shipping_id > 0 " .
                    "   order by kode ASC " .
                    "   ";
            }
            $mod = Yii::$app->db->createCommand($sql)->queryAll();
            $ret = [];
            if (count($mod) > 0) {
                $arraymap = \yii\helpers\ArrayHelper::map($mod, 'pengajuan_pembelianlog_id', 'kode');
                foreach ($mod as $i => $val) {
                    $data[] = ['id' => $val['pengajuan_pembelianlog_id'], 'text' => $val['kode']];
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionDaftarKeputusanPembelianLog()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->get('dt') == 'modal-daftarKeputusanPembelianLog') {
                $param['table'] = \app\models\TPengajuanPembelianlog::tableName();
                $param['pk'] = \app\models\TPengajuanPembelianlog::primaryKey()[0];
                $param['column'] = ['pengajuan_pembelianlog_id', 'kode', 'tanggal', 'nomor_kontrak', 'm_suplier.suplier_nm', 'asal_kayu', 'volume_kontrak', 'total_volume'];
                $param['join'] = ['JOIN m_suplier ON m_suplier.suplier_id = ' . $param['table'] . '.suplier_id'];
                // $param['where'] = ['pengajuan_pembelianlog_id NOT IN (SELECT pengajuan_pembelianlog_id FROM t_loglist WHERE cancel_transaksi_id IS NULL)'];
                return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
            }
            return $this->renderAjax('_daftarKeputusanPembelianLog');
        }
    }

    public function actionDaftarSpmLog()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->get('dt') == 'modal-daftarSpmLog') {
                $param['table'] = \app\models\TSpkShipping::tableName();
                $param['pk'] = $param['table'] . '.' . \app\models\TSpkShipping::primaryKey()[0];
                $param['column'] = [
                    't_spk_shipping.spk_shipping_id',
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
                $param['join'] = ['JOIN m_pegawai ON m_pegawai.pegawai_id = ' . $param['table'] . '.pic_shipping '];
                $param['where'] = ["status_jenis > 0 and status = 'APPROVED'"];
                return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
            }
            return $this->renderAjax('_daftarSpmLog');
        }
    }

    public function actionFindSpmLog()
    {
        if (\Yii::$app->request->isAjax) {
            $term = Yii::$app->request->get('term');
            $data = [];
            $active = "";
            if (!empty($term)) {
                $sql = " select * from t_spk_shipping " .
                    "   where kode ilike '%{$term}%' " .
                    "   and status = 'APPROVED' " .
                    "   and status_jenis > 0 " .
                    "   order by kode ASC " .
                    "   ";
            } else {
                $sql = " select * from t_spk_shipping " .
                    "   where status = 'APPROVED' " .
                    "   and status_jenis > 0 " .
                    "   order by kode ASC " .
                    "   ";
            }
            $mod = Yii::$app->db->createCommand($sql)->queryAll();
            $ret = [];
            if (count($mod) > 0) {
                $arraymap = \yii\helpers\ArrayHelper::map($mod, 'spk_shipping_id', 'kode');
                foreach ($mod as $i => $val) {
                    $data[] = ['id' => $val['spk_shipping_id'], 'text' => $val['kode']];
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionUpdateModelTTerimaLogalam()
    {
        if (\Yii::$app->request->isAjax) {
            $terima_logalam_id = \Yii::$app->request->post("loglist_id");
            $modeluk = \Yii::$app->request->post("modeluk");
            $modelarea = \Yii::$app->request->post("modelarea");
            $data = false;
            if (!empty($terima_logalam_id)) {
                $model = \app\models\TTerimaLogalam::findOne($terima_logalam_id);
                if (!empty($model)) {
                    $model->area_pembelian = $modelarea;
                    $data = $model->save();
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionLihatLampiran()
    {
        if (\Yii::$app->request->isAjax) {
            $terima_logalam_id = Yii::$app->request->post('terima_logalam_id');
            $model = \app\models\TTerimaLogalam::findOne(['terima_logalam_id' => $terima_logalam_id]);
            $area_pembelian = $model->area_pembelian;
            $view = Yii::$app->request->post('view');
            $edit = Yii::$app->request->post('edit');
            $data = [];
            $data['html'] = '';
            $disabled = false;
            if (!empty($terima_logalam_id)) {
                $modDetail = \app\models\TTerimaLogalamDetail::find()->where(['terima_logalam_id' => $terima_logalam_id])->orderBy(['created_at' => SORT_ASC])->all();
                if (count($modDetail) > 0) {
                    $i = 1;
                    foreach ($modDetail as $x => $detail) {
                        $sql_cek_kode = "select kode from t_terima_logalam_pabrik where kode = '" . $detail->no_barcode . "'";
                        if (!empty($detail)) {
                            $kode = Yii::$app->db->createCommand($sql_cek_kode)->queryScalar();
                            (!empty($kode)) ? $bedes = 1 : $bedes = 0;
                        } else {
                            $bedes = 0;
                        }
                        $jumlah_baris = $detail->no_lap;
                        $kayu_id = $detail->kayu_id;
                        $data['html'] .= $this->renderPartial('_item', ['model' => $model, 'modDetail' => $detail, 'area_pembelian' => $area_pembelian, 'jumlah_baris' => $jumlah_baris, 'kayu_id' => $kayu_id, 'bedes' => $bedes, 'view' => $view, 'edit' => $edit]);
                        $i++;
                    }
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionLihatRekap()
    {
        if (\Yii::$app->request->isAjax) {
            $terima_logalam_id = Yii::$app->request->post('terima_logalam_id');
            $lampiran = Yii::$app->request->post('lampiran');
            $edit = 0;
            $model = \app\models\TTerimaLogalam::findOne(['terima_logalam_id' => $terima_logalam_id]);
            $data = [];
            $data['html'] = '';
            if (!empty($terima_logalam_id)) {
                $sql_jenis_kayu = "select distinct(a.kayu_id), b.group_kayu, b.kayu_nama " .
                    "   from t_terima_logalam_detail a " .
                    "   join m_kayu b on b.kayu_id = a.kayu_id " .
                    "   where terima_logalam_id = " . $terima_logalam_id . " " .
                    "   and lampiran = " . $lampiran . "" .
                    "   ";
                $query_jenis_kayu = Yii::$app->db->createCommand($sql_jenis_kayu)->queryAll();
                $i = 1;
                $tot_batang_2529 = 0;
                $tot_volume_2529 = 0;
                $tot_batang_3039 = 0;
                $tot_volume_3039 = 0;
                $tot_batang_4049 = 0;
                $tot_volume_4049 = 0;
                $tot_batang_5059 = 0;
                $tot_volume_5059 = 0;
                $tot_batang_6069 = 0;
                $tot_volume_6069 = 0;
                $tot_batang_70up = 0;
                $tot_volume_70up = 0;
                foreach ($query_jenis_kayu as $kolom) {
                    $sql_batang_2529 = "select count(no_barcode) from t_terima_logalam_detail " .
                        "   where terima_logalam_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '25-29' and lampiran = " . $lampiran . " " .
                        "   ";
                    $batang_2529 = Yii::$app->db->createCommand($sql_batang_2529)->queryScalar();
                    $batang_2529 == 0 ? $batang_2529 = '-' : $batang_2529 = $batang_2529;
                    $sql_volume_2529 = "select sum(volume_value) from t_terima_logalam_detail " .
                        "   where terima_logalam_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '25-29' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $volume_2529 = Yii::$app->db->createCommand($sql_volume_2529)->queryScalar();
                    $volume_2529 == 0 ? $volume_2529 = '-' : $volume_2529 = $volume_2529;
                    //=============================================================================
                    $sql_batang_3039 = "select count(no_barcode) from t_terima_logalam_detail " .
                        "   where terima_logalam_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '30-39' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $batang_3039 = Yii::$app->db->createCommand($sql_batang_3039)->queryScalar();
                    $batang_3039 == 0 ? $batang_3039 = '-' : $batang_3039 = $batang_3039;
                    $sql_volume_3039 = "select sum(volume_value) from t_terima_logalam_detail " .
                        "   where terima_logalam_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '30-39' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $volume_3039 = Yii::$app->db->createCommand($sql_volume_3039)->queryScalar();
                    $volume_3039 == 0 ? $volume_3039 = '-' : $volume_3039 = $volume_3039;
                    //=============================================================================
                    $sql_batang_4049 = "select count(no_barcode) from t_terima_logalam_detail " .
                        "   where terima_logalam_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '40-49' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $batang_4049 = Yii::$app->db->createCommand($sql_batang_4049)->queryScalar();
                    $batang_4049 == 0 ? $batang_4049 = '-' : $batang_4049 = $batang_4049;
                    $sql_volume_4049 = "select sum(volume_value) from t_terima_logalam_detail " .
                        "   where terima_logalam_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '40-49' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $volume_4049 = Yii::$app->db->createCommand($sql_volume_4049)->queryScalar();
                    $volume_4049 == 0 ? $volume_4049 = '-' : $volume_4049 = $volume_4049;
                    //=============================================================================
                    $sql_batang_5059 = "select count(no_barcode) from t_terima_logalam_detail " .
                        "   where terima_logalam_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '50-59' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $batang_5059 = Yii::$app->db->createCommand($sql_batang_5059)->queryScalar();
                    $batang_5059 == 0 ? $batang_5059 = '-' : $batang_5059 = $batang_5059;
                    $sql_volume_5059 = "select sum(volume_value) from t_terima_logalam_detail " .
                        "   where loglist_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '50-59' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $volume_5059 = Yii::$app->db->createCommand($sql_volume_5059)->queryScalar();
                    $volume_5059 == 0 ? $volume_5059 = '-' : $volume_5059 = $volume_5059;
                    //=============================================================================
                    $sql_batang_6069 = "select count(no_barcode) from t_terima_logalam_detail " .
                        "   where loglist_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '60-69' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $batang_6069 = Yii::$app->db->createCommand($sql_batang_6069)->queryScalar();
                    $batang_6069 == 0 ? $batang_6069 = '-' : $batang_6069 = $batang_6069;
                    $sql_volume_6069 = "select sum(volume_value) from t_terima_logalam_detail " .
                        "   where loglist_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '60-69' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $volume_6069 = Yii::$app->db->createCommand($sql_volume_6069)->queryScalar();
                    $volume_6069 == 0 ? $volume_6069 = '-' : $volume_6069 = $volume_6069;
                    //=============================================================================
                    $sql_batang_70up = "select count(no_barcode) from t_terima_logalam_detail " .
                        "   where loglist_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '70-up' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $batang_70up = Yii::$app->db->createCommand($sql_batang_70up)->queryScalar();
                    $batang_70up == 0 ? $batang_70up = '-' : $batang_70up = $batang_70up;
                    $sql_volume_70up = "select sum(volume_value) from t_terima_logalam_detail " .
                        "   where loglist_id = " . $terima_logalam_id . " and kayu_id = " . $kolom['kayu_id'] . " and volume_range = '70-up' and lampiran = " . $lampiran . "  " .
                        "   ";
                    $volume_70up = Yii::$app->db->createCommand($sql_volume_70up)->queryScalar();
                    $volume_70up == 0 ? $volume_70up = '-' : $volume_70up = $volume_70up;
                    //=============================================================================

                    $data['html'] .= $this->renderPartial('_rekap', [
                        'sql_volume_6069' => $sql_volume_6069, 'i' => $i, 'model' => $model, 'kolom' => $kolom,
                        'batang_2529' => $batang_2529, 'volume_2529' => $volume_2529,
                        'batang_3039' => $batang_3039, 'volume_3039' => $volume_3039,
                        'batang_4049' => $batang_4049, 'volume_4049' => $volume_4049,
                        'batang_5059' => $batang_5059, 'volume_5059' => $volume_5059,
                        'batang_6069' => $batang_6069, 'volume_6069' => $volume_6069,
                        'batang_70up' => $batang_70up, 'volume_70up' => $volume_70up
                    ]);
                    $i++;
                    $tot_batang_2529 += $batang_2529;
                    $tot_volume_2529 += $volume_2529;
                    $tot_batang_3039 += $batang_3039;
                    $tot_volume_3039 += $volume_3039;
                    $tot_batang_4049 += $batang_4049;
                    $tot_volume_4049 += $volume_4049;
                    $tot_batang_5059 += $batang_5059;
                    $tot_volume_5059 += $volume_5059;
                    $tot_batang_6069 += $batang_6069;
                    $tot_volume_6069 += $volume_6069;
                    $tot_batang_70up += $batang_70up;
                    $tot_volume_70up += $volume_70up;
                }
                $data['html'] .= "<tr>";
                $data['html'] .= "<td colspan='2' class='td=leco; text-right'><b>TOTAL</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_batang_2529, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_volume_2529, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_batang_3039, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_volume_3039, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_batang_4049, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_volume_4049, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_batang_5059, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_volume_5059, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_batang_6069, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_volume_6069, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_batang_70up, 2) . "</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>" . \app\components\DeltaFormatter::formatNumberForUser($tot_volume_70up, 2) . "</b></td>";
                $data['html'] .= "</tr>";
            }
            return $this->asJson($data);
        }
    }

    public function actionAddItem()
    {
        $data['html'] = "";
        if (Yii::$app->request->isAjax) {
            $data['html'] .= $this->renderPartial('_item', Yii::$app->request->post());
            return $this->asJson($data);
        }
    }

    public function actionPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $caraprint = Yii::$app->request->get('caraprint');
        $modDetail = \app\models\TTerimaLogalamDetail::findOne($_GET['id']);
        $id_terima = Yii::$app->db->createCommand("select terima_logalam_id from t_terima_logalam where terima_logalam_id = " . $modDetail->terima_logalam_id . "")->queryScalar();
        $kode_terima = Yii::$app->db->createCommand("select kode from t_terima_logalam where terima_logalam_id = " . $modDetail->terima_logalam_id . "")->queryScalar();
        $tipe_kayu = Yii::$app->db->createCommand("select kayu_nama from m_kayu where kayu_id = " . $modDetail->kayu_id . "")->queryScalar();
        $paramprint['judul'] = Yii::t('app', 'Print QR Code');
        $qrCodeContent = "ID : " . $modDetail->terima_logalam_detail_id .
            "\u000ANo : " . $modDetail->no_barcode .
            //"\u000ANo. Lap : ".$modDetail->no_lap.
            //"\u000ANo. Grade : ".$modDetail->no_grade.
            //"\u000ANo. Btg : ".$modDetail->no_btg.
            //"\u000APanjang : ".$modDetail->panjang." m".
            //"\u000AKode Potong : ".$modDetail->kode_potong.
            //"\u000AUjung 1 : ".$modDetail->diameter_ujung1.
            //"\u000AUjung 2 : ".$modDetail->diameter_ujung2.
            //"\u000APangkal 1 : ".$modDetail->diameter_pangkal1.
            //"\u000APangkal 2 : ".$modDetail->diameter_pangkal2.
            //"\u000ADiameter : ".$modDetail->diameter_rata." cm".
            //"\u000AVolume : ".$modDetail->volume." m2".
            //"\u000AJenis Kayu : ".$tipe_kayu.
            "";
        // update kolom t_terima_logalam.cetak = 1 supaya tidak bisa diedit lagi
        $sql_cetak = "update t_terima_logalam set cetak = 1";
        $query_cetak = Yii::$app->db->createCommand($sql_cetak)->execute();

        if ($query_cetak) {
            if ($caraprint == 'PRINT') {
                return $this->render('print', ['paramprint' => $paramprint, 'modDetail' => $modDetail, 'qrCodeContent' => $qrCodeContent]);
            } else if ($caraprint == 'PDF') {
                $pdf = Yii::$app->pdf;
                $pdf->options = ['title' => $paramprint['judul']];
                $pdf->filename = $paramprint['judul'] . '.pdf';
                $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
                $pdf->content = $this->render('printNota', ['paramprint' => $paramprint, 'modDetail' => $modDetail]);
                return $pdf->render();
            } else if ($caraprint == 'EXCEL') {
                return $this->render('printNota', ['paramprint' => $paramprint, 'modDetail' => $modDetail]);
            }
        }
    }

    public function actionDeleteItem()
    {
        $id = Yii::$app->request->get('id');
        $url_asal = "terimalogalam/hapusYes";
        return $this->renderAjax('@views/apps/partial/_confirmHapus', ['url_asal' => $url_asal, 'id' => $id, 'url_tujuan' => "index"]);
    }

    public function actionHapusYes()
    {
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $master = TTerimaLogalam::findOne($id);
                $detail = TTerimaLogalamDetail::findAll(['terima_logalam_id' => $master->terima_logalam_id]);
                foreach($detail as $dtl) {
                    if(TTerimaLogalamPabrik::findOne(['terima_logalam_detail_id' => $dtl->terima_logalam_detail_id])) {
                        $data['status'] = false;
                        throw new Exception("Log Sudah Diterima");
                    }else {
                        $dtl->delete();
                    }
                }
                if($detail > 0) {
                    $success = $master->delete();
                }
                if (!$success) {
                    $data['status'] = false;
                    throw new Exception("Data Gagal di hapus");
                }

                $transaction->commit();
                $data['status'] = false;
                Yii::$app->session->setFlash('success', Yii::t('app', 'Data berhasil dihapus'));
            } catch (Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex->getMessage());
            }
        }
        return $this->asJson($data);
    }

    public function actionDeleteItemDetail()
    {
        $id                 = Yii::$app->request->get('id');
        $modDetail          = TTerimaLogalamDetail::findOne(['terima_logalam_detail_id' => $id]);
        $terima_logalam_id  = $modDetail->terima_logalam_id;
        $url_asal           = "terimalogalam/hapusDetailYes";
        return $this->renderAjax('@views/apps/partial/_confirmHapus', ['url_asal' => $url_asal, 'id' => $id, 'url_tujuan' => 'index?terima_logalam_id='. $terima_logalam_id .'&edit=1']);
    }

    public function actionHapusDetailYes()
    {
        $id = Yii::$app->request->post('id');
        if (\Yii::$app->request->isAjax) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                TTerimaLogalamDetail::findOne($id)->delete();
                $transaction->commit();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Data berhasil dihapus'));
            } catch (Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex->getMessage());
            }
        }
        return $this->asJson(['status' => true, 'message' => Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE]);
    }

    public function actionCekTerimaLogalamPabrik()
    {
        return TTerimaLogalamPabrik::find()
            ->where(['terima_logalam_detail_id' => Yii::$app->request->post('terima_logalam_detail_id')])
            ->count();
    }

    public function actionPrintall()
    {
        $this->layout = '@views/layouts/metronic/print';
        $modDetail = TTerimaLogalamDetail::find()->where(['terima_logalam_id' => $_GET['terima_logalam_id']])->orderBy(['terima_logalam_detail_id' => SORT_ASC])->all();
        // $qrCodeContent = "ID : " . $modDetail->terima_logalam_detail_id .
        // $sql_cetak = "update t_terima_logalam set cetak = 1";
        // $query_cetak = Yii::$app->db->createCommand($sql_cetak)->execute();
        return $this->render('printAll', compact('modDetail'));
    }

    public function actionEditdetail($id) 
    {
        $id = Yii::$app->request->get('id');
        $model  = TTerimaLogalamDetail::findOne($id);
        $terima = TTerimaLogalam::findOne($model->terima_logalam_id);
        if(Yii::$app->request->isPost) {
            $model->attributes = $_POST['TTerimaLogalamDetail'];
            if($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Updated');
                return $this->redirect(Url::toRoute('/ppic/terimalogalam/index?view=1&terima_logalam_id='. $terima->terima_logalam_id));    
            } 
        }
        return $this->renderPartial('_editDetail', compact('model', 'terima'));
    }

    public function actionDaftarcustomer()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->get('dt') == 'modal-daftar-customer') {
                $param['table'] = MCustomer::tableName();
                $param['pk'] = $param['table'] . '.' . MCustomer::primaryKey()[0];
                $param['column'] = [
                    "m_customer.cust_id", 
                    "m_customer.cust_kode", 
                    "m_customer.cust_an_nama", 
                    "m_customer.cust_pr_nama", 
                    "m_customer.cust_pr_alamat", 
                    "m_customer.cust_an_alamat", 
                    "m_customer.status_approval", 
                    "m_customer.active"
                ];
                $param['where'] = ["status_approval = 'APPROVED' AND active = true"];
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('_daftarCustomer');
        }
    }

    public function actionSetcustaddress()
    {
        if(Yii::$app->request->isAjax) {
            $customer = $_POST['customer'];
            $customer = explode("-", $customer);
            $customer = trim($customer[0]);
            $cust = MCustomer::find()->where(['cust_an_nama' => $customer])->orWhere(['cust_pr_nama' => $customer])->one();
            if($cust->cust_pr_alamat) {
                return $cust->cust_pr_alamat; //$customer->cust_pr_alamat;
            }

            return $cust->cust_an_alamat;
        }
    }

    // TAMBAH FSC
    public function actionSetcheckboxfsc()
    {
        if(Yii::$app->request->isAjax) {
            $data = [];
            $id = Yii::$app->request->post('id');
            $model = TPengajuanPembelianlog::findOne($id); 
            if($model->status_fsc == 'FSC 100%'){
                $data['fsc'] = true;
                $data['value'] = 1;
            } else {
                $data['fsc'] = false;
                $data['value'] = 0;
            }

            return $this->asJson($data);
        }
    }
    // eo FSC

    public function actionDaftarcustomerPO()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->get('dt') == 'modal-daftar-customer') {
                $param['table'] = MCustomer::tableName();
                $param['pk'] = $param['table'] . '.' . MCustomer::primaryKey()[0];
                $param['column'] = [
                    "m_customer.cust_id", 
                    "m_customer.cust_kode", 
                    "m_customer.cust_an_nama", 
                    "m_customer.cust_pr_nama", 
                    "m_customer.cust_pr_alamat", 
                    "m_customer.cust_an_alamat", 
                    "m_customer.status_approval", 
                    "m_customer.active",
                    "t_po_ko.kode",
                    "t_po_ko.tanggal"
                ];
                $param['join'] = ["LEFT JOIN t_po_ko ON t_po_ko.cust_id = m_customer.cust_id"];
                $param['where'] = ["m_customer.status_approval = 'APPROVED' AND active = true AND t_po_ko.status_approval = 'APPROVED' AND t_po_ko.status_po = true"];
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('_daftarCustomerPO');
        }
    }

    public function actionInfoPO(){
		$kode = Yii::$app->request->get('kode');
		$model = \app\models\TPoKo::findOne(['kode'=>$kode]);
		$modDetail = \app\models\TPoKoDetail::findAll(['po_ko_id'=>$model->po_ko_id]);
		return $this->renderAjax('infoPO',['model'=>$model,'modDetail'=>$modDetail]);
	}

    public function actionInfoSpmLog(){
        $id = Yii::$app->request->get('id');
        $model = \app\models\TSpkShipping::findOne($id);
        return $this->renderAjax('infoSpmLog',['model'=>$model]);
    }

    public function actionInfoKeputusan(){
        $id = Yii::$app->request->get('id');
        $model = \app\models\TPengajuanPembelianlog::findOne($id);
        $modSup = \app\models\MSuplier::findOne($model->suplier_id);
        return $this->renderAjax('infoKeputusan',['model'=>$model, 'modSup'=>$modSup]);
    }
}