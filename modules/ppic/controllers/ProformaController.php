<?php

namespace app\modules\ppic\controllers;

use app\components\DeltaFormatter;
use app\components\DeltaGenerator;
use app\components\Params;
use app\components\SSP;
use app\models\MCustomer;
use app\models\TApproval;
use app\models\TOpExport;
use app\models\TPackinglist;
use app\models\TPackinglistContainer;
use Yii;
use app\controllers\DeltaBaseController;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Response;

class ProformaController extends DeltaBaseController
{

//    public $defaultAction = 'index';

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionIndex()
    {
        $model = new TPackinglist();
        $model->kode = "Auto Generate";
        $model->status = "PROFORMA";
        $model->revisi_ke = 0;
        $model->total_container = 0;
        $model->total_bundles = 0;
        $model->total_pcs = 0;
        $model->total_volume = 0;
        $model->total_gross_weight = 0;
        $model->total_nett_weight = 0;
        $model->disetujui = Params::DEFAULT_PEGAWAI_ID_NAFIS;
        $model->mengetahui = Params::DEFAULT_PEGAWAI_ID_ILHAM;
        $model->tanggal = date("d/m/Y");
        $modContainer = new TPackinglistContainer();
        $modOpEx = new TOpExport();
        $modBuyer = new MCustomer();

        if (isset($_GET['packinglist_id'])) {
            $model = TPackinglist::findOne($_GET['packinglist_id']);
            $model->tanggal = DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modOpEx = TOpExport::findOne($model->op_export_id);
            $modOpEx->kode .= " - " . $modOpEx->nomor_kontrak;
        }

        if (isset($_GET['revisi'])) {
            $model = TPackinglist::findOne($_GET['packinglist_id']);
            $model->tanggal = DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            ++$model->revisi_ke;
            $modOpEx = TOpExport::findOne($model->op_export_id);
        }

        if (Yii::$app->request->post('TPackinglist')) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_packinglist
                $success_2 = true; // t_packinglist_container
                $success_3 = true; // t_approval
                $model->load(Yii::$app->request->post());

                if (!empty($_POST['TPackinglist']['packinglist_id'])) { // kondisi EDIT
                    if (isset($_GET['revisi'])) {
                        $model = new TPackinglist();
                        $modelOld = TPackinglist::find()->where("kode ILIKE '%" . (substr($_POST['TPackinglist']['kode'], 0, 12)) . "%' AND revisi_ke = " . ($_POST['TPackinglist']['revisi_ke'] - 1))->one();
                        $model->attributes = $modelOld->attributes;
                        $model->load(Yii::$app->request->post());
                        $kode = explode("-", $model->kode)[0];
                        $model->kode = $kode . "-" . $model->revisi_ke;
                    } else {
                        $model = TPackinglist::findOne($_POST['TPackinglist']['packinglist_id']);
                        $model->load(Yii::$app->request->post());
                    }
                } else {
                    $model->kode = DeltaGenerator::kodeProformaPackinglist($model->revisi_ke);
                }
                $model->jenis_produk = $_POST['TOpExport']['jenis_produk'];
                $model->op_export_id = $_POST['TOpExport']['op_export_id'];
                $model->cust_id = $_POST['TOpExport']['cust_id'];

                $modOpEx = TOpExport::findOne($model->op_export_id);
                if ($modOpEx !== null) {
                    $model->shipper = !empty($model->shipper) ? $model->shipper : $modOpEx->shipper;
                    $model->notify_party = !empty($model->notify_party) ? $model->notify_party : $modOpEx->notify_party;
                    $model->port_of_loading = !empty($model->port_of_loading) ? $model->port_of_loading : $modOpEx->port_of_loading;
                    $model->vessel = !empty($model->vessel) ? $model->vessel : $modOpEx->vessel;
                    $model->mother_vessel = !empty($model->mother_vessel) ? $model->mother_vessel : $modOpEx->mother_vessel;
                    $model->etd = !empty($model->etd) ? $model->etd : $modOpEx->departure_estimated_date;
                    $model->final_destination = !empty($model->final_destination) ? $model->final_destination : $modOpEx->final_destination;
                    $model->eta = !empty($model->eta) ? $model->eta : $modOpEx->arrival_estimated_date;
                    $model->hs_code = !empty($model->hs_code) ? $model->hs_code : $modOpEx->hs_code;
                    $model->origin = !empty($model->origin) ? $model->origin : $modOpEx->origin;
                    $model->svlk_no = !empty($model->svlk_no) ? $model->svlk_no : $modOpEx->svlk_no;
                    $model->vlegal_no = !empty($model->vlegal_no) ? $model->vlegal_no : $modOpEx->vlegal_no;
                    $model->static_product_code = !empty($model->static_product_code) ? $model->static_product_code : $modOpEx->static_product_code;
                    $model->goods_description = !empty($model->goods_description) ? $model->goods_description : $modOpEx->static_product_code;
                    $model->harvesting_area = !empty($model->harvesting_area) ? $model->harvesting_area : $modOpEx->harvesting_area;
                }

                if ($model->validate() && $model->save()) {
                    $success_1 = true;
//                    $isirandom = false;

                    if (isset($_POST['TPackinglistContainer']) && count($_POST['TPackinglistContainer']) > 0) {
                        // kondisi EDIT
                        if (!empty($_POST['TPackinglist']['packinglist_id']) && !isset($_GET['revisi'])) {
                            $success_2 = TPackinglistContainer::deleteAll("packinglist_id = " . $_POST['TPackinglist']['packinglist_id']);
                        }

//                        ga kepakai
//                        foreach ($_POST['TPackinglistContainer'] as $asd) {
//                            foreach ($asd as $qwe) {
//                                if (is_array($qwe)) {
//                                    $isirandom = true;
//                                }
//                            }
//                        }
                        foreach ($_POST['TPackinglistContainer'] as $xx => $qweqwe) {
                            if (isset($qweqwe['thick_satuan'])) {
                                $_POST['TPackinglistContainer'][$xx]['thick_unit'] = $qweqwe['thick_satuan'];
                            }
                            if (isset($qweqwe['width_satuan'])) {
                                $_POST['TPackinglistContainer'][$xx]['width_unit'] = $qweqwe['width_satuan'];
                            }
                            if (isset($qweqwe['length_satuan'])) {
                                $_POST['TPackinglistContainer'][$xx]['length_unit'] = $qweqwe['length_satuan'];
                            }
                        }
                        $postDetail = $_POST['TPackinglistContainer'];
                        foreach ($postDetail as $container) {
                            $modContainer = new TPackinglistContainer();
                            $modContainer->attributes = $container;
                            $modContainer->packinglist_id = $model->packinglist_id;
                            if ($modContainer->validate() && $modContainer->save()) {
                                $success_2 = true;
                            } else {
                                $success_2 = false;
                            }
                            if (empty($modContainer->grade) && empty($modContainer->jenis_kayu) && empty($modContainer->glue) && empty($modContainer->profil_kayu) && empty($modContainer->kondisi_kayu)) {
                                $errmsg = "Data detail tidak lengkap";
                                $success_2 = false;
                            }
                        }
                    }

                    // START Create Approval
                    $modelApproval = TApproval::find()->where(['reff_no' => $model->kode])->all();
                    // exist
                    if ((count($modelApproval) > 0) && !isset($_GET['revisi'])) {
                        if (TApproval::deleteAll(['reff_no' => $model->kode])) {
                            $model->status_approval = TApproval::STATUS_NOT_CONFIRMATED;
                            $model->save();
                            $success_3 = $this->saveApproval($model);
                        }
                    } else { // not exist
                        $success_3 = $this->saveApproval($model);
                    }

                    // update reason approval
                    $reason = TApproval::find()->where(['reff_no' => $model->kode])->orderBy(['level' => SORT_ASC])->all();
                    foreach ($reason as $res) {
                        $build[] = [
                            'pegawai_id' => $res->assigned_to,
                            'tanggal'   => null,
                            'reason'    => '',
                            'status'    => $res->status,
                            'level'     => $res->level
                        ];
                    }
                    $model->reason_approval = Json::encode($build);
                    $model->save();
                    // END Create Approval

                }
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				echo "<pre>2";
//				print_r($success_3);
//				exit;
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index', 'success' => 1, 'packinglist_id' => $model->packinglist_id]);
                }

                $transaction->rollback();
                Yii::$app->session->setFlash('error', !empty($errmsg) ? $errmsg : Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
            } catch (Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        return $this->render('index', ['model' => $model, 'modBuyer' => $modBuyer, 'modContainer' => $modContainer, 'modOpEx' => $modOpEx]);
    }

    public function saveApproval($model)
    {
        $success = false;

        if ($model->diperiksa) {
            $modelApproval = new TApproval();
            $modelApproval->assigned_to = $model->diperiksa;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 1;
            $modelApproval->status = TApproval::STATUS_NOT_CONFIRMATED;
            $success &= $modelApproval->createApproval();
        }

        if($model->disetujui) {
            $modelApproval = new TApproval();
            $modelApproval->assigned_to = $model->disetujui;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 2;
            $modelApproval->status = TApproval::STATUS_NOT_CONFIRMATED;
            $success = $modelApproval->createApproval();
        }

        if ($model->mengetahui) {
            $modelApproval = new TApproval();
            $modelApproval->assigned_to = $model->mengetahui;
            $modelApproval->reff_no = $model->kode;
            $modelApproval->tanggal_berkas = $model->tanggal;
            $modelApproval->level = 3;
            $modelApproval->status = TApproval::STATUS_NOT_CONFIRMATED;
            $success &= $modelApproval->createApproval();
        }

        return $success;
    }

    public function actionFindOP()
    {
        if (Yii::$app->request->isAjax) {
            $term = Yii::$app->request->get('term');
            $data = [];
            $active = "";
            if (!empty($term)) {
                $query = "
					SELECT * FROM t_op_export 
					JOIN m_customer ON m_customer.cust_id = t_op_export.cust_id
					WHERE nomor_kontrak ilike '%{$term}%' AND cancel_transaksi_id IS NULL
					ORDER BY t_op_export.created_at DESC";
                $mod = Yii::$app->db->createCommand($query)->queryAll();
                $ret = [];
                if (count($mod) > 0) {
                    foreach ($mod as $i => $val) {
                        $data[] = ['id' => $val['op_export_id'], 'text' => $val['nomor_kontrak']];
                    }
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionMasterOpex()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->get('dt') == 'table-master') {
                $param['table'] = TOpExport::tableName();
                $param['pk'] = $param['table'] . "." . TOpExport::primaryKey()[0];
                $param['column'] = [$param['table'] . '.op_export_id',
                    'nomor_kontrak',
                    't_op_export.kode',
                    't_op_export.tanggal',
                    'm_customer.cust_an_nama',
                    'm_customer.cust_an_alamat',
                    'cust2.cust_an_nama AS cust_nama2',
                    'cust2.cust_an_alamat AS cust_alamat2',
                    $param['table'] . '.goods_description',
                    'payment_method'];
                $param['join'] = ["LEFT JOIN m_customer ON m_customer.cust_id = t_op_export.cust_id 
								   LEFT JOIN m_customer AS cust2 ON cust2.cust_id = t_op_export.notify_party"];
                $param['where'] = "t_op_export.cancel_transaksi_id IS NULL";
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('masterOpex');
        }
    }

    public function actionSetOpEx()
    {
        if (Yii::$app->request->isAjax) {
            $op_export_id = Yii::$app->request->post('op_export_id');
            $packinglist_id = Yii::$app->request->post('packinglist_id');
            $modPackinglist = null;
            $data['attch'] = [];
            $data = [];
            if (!empty($op_export_id)) {
                $modEx = TOpExport::findOne($op_export_id);
                $modCust = MCustomer::findOne($modEx->cust_id);
                $modAttch = \app\models\TAttachment::find()->where("reff_no = '{$modEx->kode}'")->all();
                if (!empty($packinglist_id)) {
                    $modPackinglist = TPackinglist::findOne($packinglist_id);
                }
                if (!empty($modEx)) {
                    $modEx->tanggal = strtoupper(!empty($modEx->tanggal) ? DeltaFormatter::formatDateTimeEn($modEx->tanggal) : "-");
                    $modEx->shipment_time = strtoupper(!empty($modEx->tanggal) ? DeltaFormatter::formatMonthForUser($modEx->shipment_time) : "-");
                    $modEx->departure_estimated_date = strtoupper(!empty($modEx->departure_estimated_date) ? DeltaFormatter::formatDateTimeEn($modEx->departure_estimated_date) : "-");
                    $modEx->arrival_estimated_date = strtoupper(!empty($modEx->arrival_estimated_date) ? DeltaFormatter::formatDateTimeEn($modEx->arrival_estimated_date) : "-");
                    $modEx->goods_description = strtoupper($modEx->goods_description);
                    $modEx->term_of_price = strtoupper($modEx->term_of_price);
                    $modEx->payment_method = strtoupper(\app\models\MDefaultValue::getOneByValue("payment-method-export", $modEx->payment_method, "name"));
                    if (!empty($modEx->notify_party)) {
                        $modEx->notify_party = $modEx->notifyParty->cust_an_nama . "<br>" . $modEx->notifyParty->cust_an_alamat;
                    }
                    $data['opex'] = $modEx->attributes;
                    $data['htmlgoodsdescription'] = $this->renderPartial('_goodsdescription', ['modEx' => $modEx]);
                }
                if (!empty($modCust)) {
                    $data['cust'] = $modCust->attributes;
                }
                if (!empty($modPackinglist)) {
                    $modPackinglist->tanggal = DeltaFormatter::formatDateTimeForUser2($modPackinglist->tanggal);
                    $modPackinglist->total_pcs = DeltaFormatter::formatNumberForUserFloat($modPackinglist->total_pcs);
                    //$modPackinglist->total_volume = number_format($modPackinglist->total_volume,4);
                    $modPackinglist->total_volume = DeltaFormatter::formatNumberForUser($modPackinglist->total_volume);
                    $modPackinglist->total_gross_weight = DeltaFormatter::formatNumberForUserFloat($modPackinglist->total_gross_weight);
                    $modPackinglist->total_nett_weight = DeltaFormatter::formatNumberForUserFloat($modPackinglist->total_nett_weight);
                    $data['packinglist'] = $modPackinglist->attributes;
                    $modSpm = \app\models\TSpmKo::find()->where("packinglist_id = " . $modPackinglist->packinglist_id . " AND cancel_transaksi_id IS NULL")->one();
                    if (!empty($modSpm)) {
                        $data['spm'] = $modSpm->attributes;
                    }
                }
                if (count($modAttch) > 0) {
                    foreach ($modAttch as $ii => $attch) {
                        $data['html_attch'][] = $this->renderPartial('_attch', ['attch' => $attch, 'ii' => $ii]);
                        $data['attch'][] = $attch->attributes;
                    }
                }
            }

            return $this->asJson($data);
        }
    }

    public function actionSetContainer()
    {
        if (Yii::$app->request->isAjax) {
            $packinglist_id = Yii::$app->request->post("packinglist_id");
            $data = [];
            $modPackinglist = [];
            $modContainer = [];
            $data['htmlcontainer'] = "";
            if ($packinglist_id) {
                $modPackinglist = TPackinglist::findOne($packinglist_id);
                if (!empty($modPackinglist)) {
                    //$modSpms = \app\models\TSpmKo::find()->where(['packinglist_id'=>$modPackinglist->packinglist_id])->andWhere("cancel_transaksi_id IS NULL AND status = 'REALISASI'")->all();
                    $modSpms = \app\models\TInvoice::find()->where(['packinglist_id' => $modPackinglist->packinglist_id])->all();
                    $data['disabled_detail'] = ((count($modSpms) > 0) ? true : false);
                    $data['packinglist'] = $modPackinglist->attributes;
                    $jmlcontainer = Yii::$app->db->createCommand("SELECT container_no,seal_no,order_kode,container_kode,container_size,lot_code,gross_weight,nett_weight 
																	FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id}
																	GROUP BY container_no,seal_no,order_kode,container_kode,container_size,lot_code,gross_weight,nett_weight 
																	ORDER BY container_no ASC")->queryAll();
                    if (count($jmlcontainer) > 0) {
                        foreach ($jmlcontainer as $i => $container_no) {
                            $modContainer = new TPackinglistContainer();
                            $jenis_produk = $modPackinglist->jenis_produk;
                            if ($modPackinglist->bundle_partition == 1) { // random
                                $data['htmlcontainer'] .= $this->renderPartial('container_partition', ['model' => $modContainer, 'modPackinglist' => $modPackinglist, 'jenis_produk' => $jenis_produk,
                                    'gross_weight' => $container_no['gross_weight'],
                                    'nett_weight' => $container_no['nett_weight'],
                                    'container_no' => $container_no['container_no'],
                                    'container_kode' => $container_no['container_kode'],
                                    'seal_no' => $container_no['seal_no'],
                                    'container_size' => $container_no['container_size'],
                                    'lot_code' => $container_no['lot_code']]);
                            } else {
                                $data['htmlcontainer'] .= $this->renderPartial('container', ['model' => $modContainer, 'modPackinglist' => $modPackinglist, 'jenis_produk' => $modPackinglist->jenis_produk,
                                    'gross_weight' => $container_no['gross_weight'],
                                    'nett_weight' => $container_no['nett_weight'],
                                    'container_no' => $container_no['container_no'],
                                    'container_kode' => $container_no['container_kode'],
                                    'seal_no' => $container_no['seal_no'],
                                    'container_size' => $container_no['container_size'],
                                    'lot_code' => $container_no['lot_code']]);
                            }
                        }
                    }
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionAddContainer()
    {
        if (Yii::$app->request->isAjax) {
            $jenis_produk = Yii::$app->request->post('jenis_produk');
            $op_export_id = Yii::$app->request->post('op_export_id');
            $gross_weight = Yii::$app->request->post('gross_weight');
            $nett_weight = Yii::$app->request->post('nett_weight');
            $bundle_partition = Yii::$app->request->post('bundle_partition');
            $data = [];
            if (!empty($op_export_id)) {
                $modContainer = new TPackinglistContainer();
                if ($bundle_partition != "true") {
                    $data['html'] = $this->renderPartial('container', ['model' => $modContainer, 'jenis_produk' => $jenis_produk, 'gross_weight' => $gross_weight, 'nett_weight' => $nett_weight]);
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionAddBundle()
    {
        if (Yii::$app->request->isAjax) {
            $container_no = Yii::$app->request->post('container_no');
            $table_id = Yii::$app->request->post('table_id');
            $jenis_produk = Yii::$app->request->post('jenis_produk');
            $grade = Yii::$app->request->post('grade');
            $jenis_kayu = Yii::$app->request->post('jenis_kayu');
            $glue = Yii::$app->request->post('glue');
            $profil_kayu = Yii::$app->request->post('profil_kayu');
            $kondisi_kayu = Yii::$app->request->post('kondisi_kayu');
            $thick = Yii::$app->request->post('thick');
            $thick_unit = Yii::$app->request->post('thick_unit');
            $width = Yii::$app->request->post('width');
            $width_unit = Yii::$app->request->post('width_unit');
            $length = Yii::$app->request->post('length');
            $length_unit = Yii::$app->request->post('length_unit');
            $pcs = Yii::$app->request->post('pcs');
            $volume = Yii::$app->request->post('volume');
            $volume_display = Yii::$app->request->post('volume_display');
            $data = [];
            if (!empty($container_no)) {
                $modContainer = new TPackinglistContainer();
                $modContainer->grade = !empty($grade) ? $grade : "";
                $modContainer->jenis_kayu = !empty($jenis_kayu) ? $jenis_kayu : "";
                $modContainer->glue = !empty($glue) ? $glue : "";
                $modContainer->profil_kayu = !empty($profil_kayu) ? $profil_kayu : "";
                $modContainer->kondisi_kayu = !empty($kondisi_kayu) ? $kondisi_kayu : "";
                $modContainer->thick = !empty($thick) ? DeltaFormatter::formatNumberForUserFloat($thick) : "0";
                $modContainer->thick_unit = !empty($thick_unit) ? $thick_unit : "";
                $modContainer->width = !empty($width) ? DeltaFormatter::formatNumberForUserFloat($width) : "0";
                $modContainer->width_unit = !empty($width_unit) ? $width_unit : "";
                $modContainer->length = !empty($length) ? DeltaFormatter::formatNumberForUserFloat($length) : "0";
                $modContainer->length_unit = !empty($length_unit) ? $length_unit : "";
                $modContainer->pcs = !empty($pcs) ? DeltaFormatter::formatNumberForUserFloat($pcs) : "0";
                $modContainer->volume = !empty($volume) ? DeltaFormatter::formatNumberForUserFloat($volume) : "0";
                $modContainer->volume_display = !empty($volume_display) ? DeltaFormatter::formatNumberForUserFloat($volume_display) : "0";
                $data['html'] = $this->renderPartial('bundle', ['model' => $modContainer, 'table_id' => $table_id, 'jenis_produk' => $jenis_produk]);
            }
            return $this->asJson($data);
        }
    }

    public function actionSetRandomTemplate_old($jenis_produk)
    {
        if (Yii::$app->request->isAjax) {
            if ((Yii::$app->request->post('params')) !== null) {
                $form_params = [];
                parse_str(Yii::$app->request->post('params'), $form_params);
                $jenis_produk = Yii::$app->request->get('jenis_produk');
                $data = [];
                if (!empty($jenis_produk)) {
                    $modContainer = new TPackinglistContainer();
                    $data['html'] = $this->renderPartial('container_partition', ['model' => $modContainer, 'jenis_produk' => $jenis_produk, 'form_params' => $form_params]);
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('setrandomtemplate', ['jenis_produk' => $jenis_produk]);
        }
    }

    public function actionSetRandomTemplate($jenis_produk, $packinglist_id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('setrandomtemplate', ['jenis_produk' => $jenis_produk, 'packinglist_id' => $packinglist_id]);
        }
    }

    public function actionInputContainerRandom()
    {
        if (Yii::$app->request->isAjax) {
            $tipe = Yii::$app->request->get('tipe');
            $packinglist_id = Yii::$app->request->get('packinglist_id');
            $container_no = Yii::$app->request->get('container_no');
            $model = new TPackinglistContainer();
            $modPackinglist = TPackinglist::findOne($packinglist_id);
            $jenis_produk = $modPackinglist->jenis_produk;
            $form_params = $_GET;

            if (Yii::$app->request->post('TPackinglistContainer')) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_1 = true; // t_packinglist_container
                    $success_2 = true; // t_packinglist update

                    if (count($_POST['TPackinglistContainer']) > 0) {
                        if ($tipe == "edit") { // kondisi EDIT
                            if (!isset($_GET['revisi'])) {
                                $success_1 = TPackinglistContainer::deleteAll("packinglist_id = " . $packinglist_id . " AND container_no = " . $container_no);
                            }
                        }
                        $detailrandom = [];
                        foreach ($_POST['TPackinglistContainer'] as $ii => $contrandom) {
                            if (is_array($contrandom['thick'])) {
                                foreach ($contrandom['thick'] as $iii => $thick) {
//											if($thick > 0){
                                    $content = $contrandom;
                                    $content['thick'] = $iii;
                                    $content['pcs'] = $thick;
                                    $content['thick_unit'] = $content['thick_satuan'];
                                    $content['width_unit'] = $content['width_satuan'];
                                    $content['length_unit'] = $content['length_satuan'];
                                    $detailrandom[] = $content;
//											}
                                }
                            }
                            if (is_array($contrandom['width'])) {
                                foreach ($contrandom['width'] as $iii => $width) {
//											if($width > 0){
                                    $content = $contrandom;
                                    $content['width'] = $iii;
                                    $content['pcs'] = $width;
                                    $content['thick_unit'] = $content['thick_satuan'];
                                    $content['width_unit'] = $content['width_satuan'];
                                    $content['length_unit'] = $content['length_satuan'];
                                    $detailrandom[] = $content;
//											}
                                }
                            }
                            if (is_array($contrandom['length'])) {
                                foreach ($contrandom['length'] as $iii => $length) {
//											if($length > 0){
                                    $content = $contrandom;
                                    $content['length'] = $iii;
                                    $content['pcs'] = $length;
                                    $content['thick_unit'] = $content['thick_satuan'];
                                    $content['width_unit'] = $content['width_satuan'];
                                    $content['length_unit'] = $content['length_satuan'];
                                    $content['volume'] = $content['length_m3'][$iii];
                                    $detailrandom[] = $content;
//											}
                                }
                            }
                            if ((!is_array($contrandom['length'])) && (!is_array($contrandom['width'])) && (!is_array($contrandom['thick']))) {
                                $content = $contrandom;
                                $content['thick_unit'] = $content['thick_satuan'];
                                $content['width_unit'] = $content['width_satuan'];
                                $content['length_unit'] = $content['length_satuan'];
                                $detailrandom[] = $content;
                            }
                        }
                        $postDetail = $detailrandom;
                        foreach ($postDetail as $i => $container) {
                            $modContainer = new TPackinglistContainer();
                            $modContainer->attributes = $container;
                            $modContainer->packinglist_id = $packinglist_id;
                            if ($modContainer->validate()) {
                                if ($modContainer->save()) {
                                    $success_1 = true;
                                } else {
                                    $success_1 = false;
                                }
                            } else {
                                $success_1 = false;
                            }
                            if (empty($modContainer->grade) && empty($modContainer->jenis_kayu) && empty($modContainer->glue) && empty($modContainer->profil_kayu) && empty($modContainer->kondisi_kayu)) {
                                $errmsg = "Data detail tidak lengkap";
                                $success_1 = false;
                            }
                        }

                        if ($success_1 == TRUE) {
                            $success_2 = $this->updateTotalPackinglist($packinglist_id);
                        }
                    } else {
                        $success_1 = false;
                    }

//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					exit;

                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                        $data['callback'] = "location.reload();";
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

            return $this->renderAjax('inputContainerRandom', ['model' => $model,
                'modPackinglist' => $modPackinglist,
                'jenis_produk' => $jenis_produk,
                'packinglist_id' => $packinglist_id,
                'tipe' => $tipe,
                'form_params' => $form_params,
                'container_no' => $container_no]);
        }
    }

    public static function updateTotalPackinglist($packinglist_id)
    {
        $modPackinglist = TPackinglist::findOne($packinglist_id);
        if ($modPackinglist->bundle_partition == TRUE) {
            $query_vol = "SUM(ROUND(volume::numeric,4))";
        } else {
            $query_vol = "SUM(volume)";
        }
        $current_packs = Yii::$app->db->createCommand("SELECT container_no, gross_weight, nett_weight, MAX(bundles_no) AS bundle, SUM(pcs) AS pcs, {$query_vol} AS volume  FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} GROUP BY 1,2,3 ORDER BY 1 ")->queryAll();
        $total_bundles = 0;
        $total_pcs = 0;
        $total_volume = 0;
        $total_gross_weight = 0;
        $total_nett_weight = 0;
        if (count($current_packs) > 0) {
            foreach ($current_packs as $i => $pack) {
                $total_bundles += $pack['bundle'];
                $total_pcs += $pack['pcs'];
                $total_volume += $pack['volume'];
                $total_gross_weight += $pack['gross_weight'];
                $total_nett_weight += $pack['nett_weight'];
            }
            $modPackinglist->total_container = count($current_packs);
            $modPackinglist->total_bundles = $total_bundles;
            $modPackinglist->total_pcs = $total_pcs;
            $modPackinglist->total_volume = $total_volume;
            $modPackinglist->total_gross_weight = $total_gross_weight;
            $modPackinglist->total_nett_weight = $total_nett_weight;
            if ($modPackinglist->validate()) {
                if ($modPackinglist->save()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }


    public function actionSetValueRandomAfterSave()
    {
        if (Yii::$app->request->isAjax) {
            $packinglist_id = Yii::$app->request->post('packinglist_id');
            $data = [];
            $mod = Yii::$app->db->createCommand("SELECT * FROM t_packinglist_container WHERE packinglist_id = " . $packinglist_id)->queryAll();
            if (count($mod) > 0) {
                $data = $mod;
            }
            return $this->asJson($data);
        }
    }

    public function actionSetValueRandom()
    {
        if (Yii::$app->request->isAjax) {
            $packinglist_id = Yii::$app->request->post('packinglist_id');
            $container_no = Yii::$app->request->post('container_no');
            $data = [];
            $mod = Yii::$app->db->createCommand("SELECT * FROM t_packinglist_container WHERE packinglist_id = " . $packinglist_id . " AND container_no = " . $container_no)->queryAll();
            if (count($mod) > 0) {
                $data = $mod;
            }
            return $this->asJson($data);
        }
    }

    /**
     * @return string|void
     */
    public function actionDaftarAfterSave()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->get('dt') === 'modal-aftersave') {
                $param['table'] = TPackinglist::tableName();
                $param['pk'] = $param['table'] . "." . TPackinglist::primaryKey()[0];
                $param['column'] = [$param['table'] . '.packinglist_id',
                    $param['table'] . '.jenis_produk',  // 1
                    't_op_export.nomor_kontrak',  // 2
                    't_op_export.kode AS kode_op',  // 3
                    $param['table'] . '.kode',  // 4
                    $param['table'] . '.revisi_ke',  // 5
                    $param['table'] . '.tanggal',  // 6
                    'm_customer.cust_an_nama',  // 7
                    'm_customer.cust_an_alamat',  // 8
                    $param['table'] . '.total_container',  // 9
                    $param['table'] . '.total_bundles',  // 10
                    $param['table'] . '.total_volume',  // 11
                    $param['table'] . '.status AS packinglist_status',  // 12
                    $param['table'] . '.status_approval',  // 13
//                '(select status from t_approval where t_approval.reff_no=t_packinglist.kode and t_packinglist.mengetahui = t_approval.assigned_to order by approval_id desc limit 1) as status',
                    't_invoice.status_inv',  // 14
                    $param['table'] . '.cancel_transaksi_id']; // 15

                $param['join'] = ['JOIN m_customer ON m_customer.cust_id = ' . $param['table'] . '.cust_id
                                    JOIN t_op_export ON t_op_export.op_export_id = ' . $param['table'] . '.op_export_id
                                    --JOIN t_approval ON t_approval.reff_no = t_packinglist.kode AND t_packinglist.mengetahui = t_approval.assigned_to
                                    LEFT JOIN t_invoice ON t_invoice.packinglist_id = ' . $param['table'] . '.packinglist_id'];
                $param['where'] = $param['table'] . ".cancel_transaksi_id IS NULL";
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('daftarAfterSave');
        }
    }

    public function actionCheckSPMExport()
    {
        if (Yii::$app->request->isAjax) {
            $packinglist_id = Yii::$app->request->post('packinglist_id');
            $data = "";
            $sql = "SELECT packinglist_id FROM t_spm_ko WHERE jenis_penjualan = 'export' AND cancel_transaksi_id IS NULL AND packinglist_id=" . $packinglist_id . " ORDER BY spm_ko_id DESC";
            $mod = Yii::$app->db->createCommand($sql)->queryAll();
            if (count($mod) > 0) {
                $data = $mod;
            }
            return $this->asJson($data);
        }
    }

    public function actionPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = TPackinglist::findOne($_GET['id']);
        $modOpEx = TOpExport::findOne($model->op_export_id);
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'Proforma Packinglist');
        $paramprint['judul2'] = $model->jenis_produk;
        if ($caraprint == 'PRINT') {
            return $this->render('printProforma', ['model' => $model, 'modOpEx' => $modOpEx, 'paramprint' => $paramprint]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('printProforma', ['model' => $model, 'modOpEx' => $modOpEx, 'paramprint' => $paramprint]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('printProforma', ['model' => $model, 'modOpEx' => $modOpEx, 'paramprint' => $paramprint]);
        }
    }

    public function actionDeleteContainer($packinglist_id, $container_no)
    {
        if (Yii::$app->request->isAjax) {
            $packinglist_id = Yii::$app->request->get('packinglist_id');
            $container_no = Yii::$app->request->get('container_no');
            if (Yii::$app->request->post('deleteRecord')) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if (TPackinglistContainer::deleteAll(['packinglist_id' => $packinglist_id, 'container_no' => $container_no])) {
                        if ($this->updateTotalPackinglist($packinglist_id)) {
                            $success_1 = true;
                        }
                    } else {
                        $data['message'] = Yii::t('app', 'Data Gagal dihapus');
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                        $data['callback'] = "location.reload();";
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
            return $this->renderAjax('_deleteConfirm', ['packinglist_id' => $packinglist_id, 'container_no' => $container_no]);
        }
    }

    public function actionOpexblmProforma($id)
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->get('dt') == 'table-notif') {

                $jenisProduk = "";
                if ($id == '5') {
                    $jenisProduk = "and jenis_produk in('Moulding')";
                } elseif ($id == '98') {
                    $jenisProduk = "and jenis_produk in('Plywood','Lamineboard','Platform')";
                }
                $param['table'] = TOpExport::tableName();
                $param['pk'] = $param['table'] . "." . TOpExport::primaryKey()[0];
                $param['column'] = [$param['table'] . '.op_export_id',
                    'nomor_kontrak',
                    't_op_export.kode',
                    't_op_export.tanggal',
                    'm_customer.cust_an_nama',
                    'm_customer.cust_an_alamat',
                    'cust2.cust_an_nama AS cust_nama2',
                    'cust2.cust_an_alamat AS cust_alamat2',
                    $param['table'] . '.goods_description',
                    'payment_method'];
                $param['join'] = ["LEFT JOIN m_customer ON m_customer.cust_id = t_op_export.cust_id 
                                                               LEFT JOIN m_customer AS cust2 ON cust2.cust_id = t_op_export.notify_party"];
                $param['where'] = "t_op_export.cancel_transaksi_id IS NULL and not exists(
                                                    select op_export_id from t_packinglist where t_op_export.op_export_id=t_packinglist.op_export_id 
                                                ) $jenisProduk ";
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('showOpexportProforma', ['id' => $id]);
        }
    }
}
