<?php

namespace app\modules\exim\controllers;

use app\models\MBrgProduk;
use app\models\MCustomer;
use app\models\TInvoice;
use app\models\TInvoiceDetail;
use app\models\TPackinglist;
use Yii;
use app\controllers\DeltaBaseController;
use yii\db\Exception;
use yii\web\Response;

class InvoiceController extends DeltaBaseController
{
    public $defaultAction = 'index';

    public function actionIndex()
    {
        $model = new TInvoice();
        $model->mata_uang = "USD";
        $model->disetujui = \app\components\Params::DEFAULT_PEGAWAI_ID_EKO_NOWO;
        $model->status_inv = "FINAL";
        $model->tanggal = date("d/m/Y");
        if (isset($_GET['invoice_id'])) {
            $model = TInvoice::findOne($_GET['invoice_id']);
            $modCust = MCustomer::findOne($model->cust_id);
            $modPackinglist = TPackinglist::findOne($model->packinglist_id);
            $modPackinglistCont = Yii::$app->db
                ->createCommand("SELECT container_no, container_kode, seal_no 
													 FROM t_packinglist_container 
													 WHERE packinglist_id = " . $model->packinglist_id . " 
													 GROUP BY container_no, container_kode, seal_no")->queryAll();
            $modOpEx = \app\models\TOpExport::findOne($model->op_export_id);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->shiper = $modPackinglist->shipper;
            $model->shipto = strtoupper($modCust->cust_an_nama) . "\n" . strtoupper($modCust->cust_an_alamat);
            $model->port_of_loading = $modPackinglist->port_of_loading;
            $model->etd = \app\components\DeltaFormatter::formatDateTimeEn($modPackinglist->etd);
            $model->final_destination = $modPackinglist->final_destination;
            $model->eta = \app\components\DeltaFormatter::formatDateTimeEn($modPackinglist->eta);
            $model->harvesting_area = $modPackinglist->harvesting_area;
            $model->static_product_code = $modPackinglist->static_product_code;
            $model->nomor_kontrak = $modOpEx->nomor_kontrak;
            $model->hs_code = $modPackinglist->hs_code;
            $model->svlk_no = $modPackinglist->svlk_no;
            $model->vlegal_no = $modPackinglist->vlegal_no;
            $model->vessel = $modPackinglist->vessel;
            if (count($modPackinglistCont) > 0) {
                foreach ($modPackinglistCont as $i => $listCont) {
                    $model->container_kode_seal_no .= $listCont['container_kode'] . " / " . $listCont['seal_no'] . "\n";
                }
            }
            $model->payment_method = $modOpEx->payment_method;

            //$model->term_of_price = $modOpEx->term_of_price;
            if (isset($model->term_of_price)) {
                $model->term_of_price = $model->term_of_price;
            } else {
                $model->term_of_price = $modOpEx->term_of_price;
            }

            $model->mata_uang = "USD";
            $model->goods_description = $modPackinglist->goods_description;
            $model->notes = !empty($model->notes) ? str_replace("<br>", "\n", $model->notes) : "";
            $model->fob = !empty($model->fob) ? \app\components\DeltaFormatter::formatNumberForUserFloat($model->fob) : "";
            $model->freight = !empty($model->freight) ? \app\components\DeltaFormatter::formatNumberForUserFloat($model->freight) : "";
            $model->peb_tanggal = !empty($model->peb_tanggal) ? \app\components\DeltaFormatter::formatDateTimeForUser2($model->peb_tanggal) : "";
            $model->bl_tanggal = !empty($model->bl_tanggal) ? \app\components\DeltaFormatter::formatDateTimeForUser2($model->bl_tanggal) : "";
            $model->payment_date_estimate = !empty($model->payment_date_estimate) ? \app\components\DeltaFormatter::formatDateTimeForUser2($model->payment_date_estimate) : "";
            $model->total_bayar > 0 ? $model->total_bayar = \app\components\DeltaFormatter::formatNumberForAllUser($model->total_bayar) : $model->total_bayar = 0;
            $model->total_potongan > 0 ? $model->total_potongan = $model->total_potongan : $model->total_potongan = 0;
            $model->total_biaya_tambahan > 0 ? $model->total_biaya_tambahan = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_biaya_tambahan, 2) : $model->total_biaya_tambahan = 0;
        }
        if (Yii::$app->request->post('TInvoice')) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_invoice
                $success_2 = true; // t_invoice_detail
                $success_3 = false; // t_packinglist update
                $model->load(Yii::$app->request->post());
                if (!isset($_GET['edit'])) {
                    $model->status = "UNPAID";
                }

                //$model->total_bayar = $_POST['TInvoice']['total_bayar'];
                $model->total_potongan = $_POST['TInvoice']['total_potongan'];
                $model->total_biaya_tambahan = $_POST['TInvoice']['total_biaya_tambahan'];
                $xxx = $_POST['TInvoice']['total_bayar'] + $_POST['TInvoice']['total_potongan'] - $_POST['TInvoice']['total_biaya_tambahan'];
                $model->total_bayar = $xxx;
                $model->notes = isset($_POST['TInvoice']['notes']) ? str_replace("\n", "<br>", $_POST['TInvoice']['notes']) : "";
                $model->fob = ($_POST['TInvoice']['fob'] != 0) ? $_POST['TInvoice']['fob'] : null;
                $model->freight = ($_POST['TInvoice']['freight'] != 0) ? $_POST['TInvoice']['freight'] : null;
                if ($model->validate()) {
                    if ($model->save()) {
                        $success_1 = true;
                        if ((isset($_GET['edit'])) && (isset($_GET['invoice_id']))) {
                            $modDetail = TInvoiceDetail::find()->where(['invoice_id' => $_GET['invoice_id']])->all();
                            if (count($modDetail) > 0) {
                                TInvoiceDetail::deleteAll(['invoice_id' => $_GET['invoice_id']]);
                            }
                        }

                        foreach ($_POST['TInvoiceDetail'] as $i => $detail) {
                            $modDetail = new TInvoiceDetail();
                            $modDetail->attributes = $detail;
                            $modDetail->invoice_id = $model->invoice_id;
//							$modDetail->kubikasi = $detail['kubikasi_display'];
                            if ($modDetail->validate()) {
                                if ($modDetail->save()) {
                                    $success_2 &= true;
                                } else {
                                    $success_2 = false;
                                }
                            } else {
                                $success_2 = false;
                                $errmsg = $modDetail->errors;
                            }
                        }

                        $modPackinglist = TPackinglist::findOne($model->packinglist_id);
                        $modPackinglist->shipper = $_POST['TInvoice']['shiper'];
                        if ($modPackinglist->save(false)) {
                            $success_3 = true;
                        } else {
                            $success_3 = false;
                            $errmsg = $modPackinglist->errors;
                        }
                    }
                }

                if ($success_1 && $success_2 && $success_3) {
                    /*
                    echo "<pre>total_potongan = ".$_POST['TInvoice']['total_potongan'];
                    echo "<pre>total_bayar = ".$_POST['TInvoice']['total_bayar'];
                    echo "<pre>success_1 = ".$success_1;
                    echo "<pre>success_2 = ".$success_2;
                    echo "<pre>success_3 = ".$success_3;
                    $total_harga = $_POST['TInvoice']['total_bayar'] + $_POST['TInvoice']['total_potongan'];
                    $invoice_id = $_POST['TInvoice']['invoice_id'];
                    $total_potongan = $_POST['TInvoice']['total_potongan'];
                    $total_bayar = $_POST['TInvoice']['total_bayar'];
                    $total_harga = $_POST['TInvoice']['total_bayar'] + $_POST['TInvoice']['total_potongan'];
                    $invoice_id_ = "select invoice_id from t_invoice where total_harga = '$total_harga', total_bayar = '$total_bayar' ";
                    $sql = "update t_invoice set total_harga = '$total_harga', total_bayar = '$total_bayar' where invoice_id = '$invoice_id' ";
                    echo "<pre>".$sql;
                    $queryOne = Yii::$app->db->createCommand($sql)->queryOne();
                    $invoice_id = $_POST['TInvoice']['invoice_id'];
                    echo "<pre>invoice_id = ".$invoice_id;
                    echo "<pre>sukses";
                    exit();
                    */
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index', 'success' => 1, 'invoice_id' => $model->invoice_id]);
                    //$transaction->rollback();
                    //$errmsg = "1".$success_1."\n2".$success_2."\n3".$success_3;
                    //$errmsg = $_POST['TInvoice']['total_bayar'] + $_POST['TInvoice']['total_potongan'] - $_POST['TInvoice']['total_biaya_tambahan']." ".$model->total_bayar;
                    //Yii::$app->session->setFlash('error', !empty($errmsg)? $errmsg : Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                } else {
                    /*
                    echo "<pre>total_potongan = ".$_POST['TInvoice']['total_potongan'];
                    echo "<pre>total_bayar = ".$_POST['TInvoice']['total_bayar'];
                    echo "<pre>success_1 = ".$success_1;
                    echo "<pre>success_2 = ".$success_2;
                    echo "<pre>success_3 = ".$success_3;
                    echo "<pre>gagal";
                    exit();
                    */
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg) ? (implode(",", array_values($errmsg)[0])) : Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        return $this->render('index', ['model' => $model]);
    }

    public function actionSetParent()
    {
        if (Yii::$app->request->isAjax) {
            $packinglist_id = Yii::$app->request->post('packinglist_id');
            $data = [];
            if (!empty($packinglist_id)) {
                $modPackinglist = TPackinglist::findOne($packinglist_id);
                $modPackinglistCont = Yii::$app->db
                    ->createCommand("SELECT container_no, container_kode, seal_no 
													 FROM t_packinglist_container 
													 WHERE packinglist_id = " . $packinglist_id . " 
													 GROUP BY container_no, container_kode, seal_no")->queryAll();
                $modCust = MCustomer::findOne($modPackinglist->cust_id);
                $modOpEx = \app\models\TOpExport::findOne($modPackinglist->op_export_id);
                $modComPro = \app\models\CCompanyProfile::findOne(\app\components\Params::DEFAULT_COMPANY_PROFILE);
                $data['op_export_id'] = $modPackinglist->op_export_id;
                $data['packinglist_id'] = $modPackinglist->packinglist_id;
                $data['cust_id'] = $modCust->cust_id;
                $data['nomor'] = $modPackinglist->nomor;
                $data['jenis_produk'] = $modPackinglist->jenis_produk;
                $data['shiper'] = strtoupper($modComPro->name) . "\n" . strtoupper(substr($modComPro->alamat, 0, strpos($modComPro->alamat, "Jawa")) . "\nJawa Tengah, Indonesia");
                $data['shipto'] = strtoupper($modPackinglist->cust->cust_an_nama) . "\n" . strtoupper($modPackinglist->cust->cust_an_alamat);
                $data['applicant'] = strtoupper($modPackinglist->cust->cust_an_nama) . "\n" . strtoupper($modPackinglist->cust->cust_an_alamat);
                if (!empty($modPackinglist->notify_party)) {
                    $data['notify_party'] = strtoupper($modPackinglist->notifyParty->cust_an_nama) . "\n" . strtoupper($modPackinglist->notifyParty->cust_an_alamat);
                }
                $data['port_of_loading'] = $modPackinglist->port_of_loading;
                $data['etd'] = \app\components\DeltaFormatter::formatDateTimeForUser2($modPackinglist->etd);
                $data['final_destination'] = $modPackinglist->final_destination;
                $data['eta'] = \app\components\DeltaFormatter::formatDateTimeForUser2($modPackinglist->eta);
                $data['harvesting_area'] = $modPackinglist->harvesting_area;
                $data['static_product_code'] = $modPackinglist->static_product_code;
                $data['nomor_kontrak'] = $modOpEx->nomor_kontrak;
                $data['hs_code'] = $modPackinglist->hs_code;
                $data['svlk_no'] = $modPackinglist->svlk_no;
                $data['vlegal_no'] = $modPackinglist->vlegal_no;
                $data['vessel'] = $modPackinglist->vessel;
                $data['container_kode_seal_no'] = "";
                if (count($modPackinglistCont) > 0) {
                    foreach ($modPackinglistCont as $i => $listCont) {
                        $data['container_kode_seal_no'] .= $listCont['container_kode'] . " / " . $listCont['seal_no'] . "\n";
                    }
                }
                $data['payment_method'] = $modOpEx->payment_method;
                $data['term_of_price'] = $modOpEx->term_of_price;
                $data['mata_uang'] = "USD";
                $data['goods_description'] = $modPackinglist->goods_description;
                $data['notes'] = $modPackinglist->notes;
            }
            return $this->asJson($data);
        }
    }

    public function actionOpenPackinglist()
    {
        if (Yii::$app->request->isAjax) {
            $status_inv = Yii::$app->request->get('status_inv');
            if (Yii::$app->request->get('dt') == 'table-spm') {
                $param['table'] = TPackinglist::tableName();
                $param['pk'] = $param['table'] . "." . TPackinglist::primaryKey()[0];
                $param['column'] = ['t_packinglist.packinglist_id',
                    't_packinglist.jenis_produk',
                    'cust_an_nama',
                    't_op_export.kode AS kode_opex',
                    'nomor_kontrak',
                    't_packinglist.nomor',
                    't_packinglist.kode'];
                $param['join'] = ['JOIN t_op_export ON t_op_export.op_export_id = t_packinglist.op_export_id
								  JOIN m_customer ON m_customer.cust_id = t_packinglist.cust_id
								  LEFT JOIN t_invoice ON t_invoice.packinglist_id = t_packinglist.packinglist_id '];
                $param['where'] = $param['table'] . ".cancel_transaksi_id IS NULL " . (($status_inv == "FINAL") ? "AND t_packinglist.status = 'FINAL'" : "") . " AND invoice_id IS NULL";
                $param['group'] = "GROUP BY t_packinglist.packinglist_id, 
									t_packinglist.jenis_produk, 
									cust_an_nama, 
									t_op_export.kode, 
									nomor_kontrak, 
									t_packinglist.nomor,
                                                                        t_packinglist.kode";
                $param['order'] = "t_packinglist.packinglist_id DESC";
                return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
            }
            return $this->renderAjax('packinglist', ['status_inv' => $status_inv]);
        }
    }

    public static function getMasterProduk($jenis_produk, $container, $packinglist_id, $bundle_partition)
    {
        $condition = [];
        $produkorder = "";
        $garing = "";
        if ($jenis_produk == "Plywood" || $jenis_produk == "Lamineboard" || $jenis_produk == "Platform") {
            $produkorder = $jenis_produk;
            $garing = "/";
        }
        $condition['produk_group'] = $jenis_produk;
        if (!empty($container['jenis_kayu'])) {
            $condition['jenis_kayu'] = $container['jenis_kayu'];
            $produkorder .= $garing . $container['jenis_kayu'];
        }
        if (!empty($container['grade'])) {
            $condition['grade'] = $container['grade'];
            $produkorder .= "/" . $container['grade'];
        }
        if (!empty($container['glue'])) {
            $condition['glue'] = $container['glue'];
            $produkorder .= "/" . $container['glue'];
        }
        if (!empty($container['profil_kayu'])) {
            $condition['profil_kayu'] = $container['profil_kayu'];
            $produkorder .= "/" . $container['profil_kayu'];
        }
        if (!empty($container['kondisi_kayu'])) {
            $condition['kondisi_kayu'] = $container['kondisi_kayu'];
            $produkorder .= "/" . $container['kondisi_kayu'];
        }
        if (!empty($container['thick'])) {
            $condition['produk_t'] = $container['thick'];
            $produkorder .= "/" . $container['thick'];
        }
        if (!empty($container['thick_unit'])) {
            $condition['produk_t_satuan'] = $container['thick_unit'];
        }
        if (!empty($container['width'])) {
            $condition['produk_l'] = $container['width'];
            $produkorder .= $container['width'];
        }
        if (!empty($container['width_unit'])) {
            $condition['produk_l_satuan'] = $container['width_unit'];
        }
        if (!empty($container['length'])) {
            $condition['produk_p'] = $container['length'];
            $produkorder .= $container['length'];
        }
        if (!empty($container['length_unit'])) {
            $condition['produk_p_satuan'] = $container['length_unit'];
        }
        if ($bundle_partition == true) {
            $modUkuranThick = Yii::$app->db->createCommand("SELECT thick, thick_unit
                                                                            FROM t_packinglist_container
                                                                            WHERE packinglist_id = {$packinglist_id}
                                                                            GROUP by 1,2
                                                                            ORDER by 1,2 ASC")->queryAll();
            $modUkuranWidth = Yii::$app->db->createCommand("SELECT width, width_unit
                                                                            FROM t_packinglist_container
                                                                            WHERE packinglist_id = {$packinglist_id}
                                                                            GROUP by 1,2
                                                                            ORDER by 1,2 ASC")->queryAll();
            $modUkuranLength = Yii::$app->db->createCommand("SELECT length, length_unit
                                                                            FROM t_packinglist_container
                                                                            WHERE packinglist_id = {$packinglist_id}
                                                                            GROUP by 1,2
                                                                            ORDER by 1,2 ASC")->queryAll();
            if (count($modUkuranThick) > 1) {
                $produkorder .= "/0";
                $condition['produk_t'] = "0";
            } else {
                $produkorder .= "/" . $modUkuranThick[0]['thick'];
                $condition['produk_t'] = $modUkuranThick[0]['thick'];
            }
            if (count($modUkuranWidth) > 1) {
                $produkorder .= "0";
                $condition['produk_l'] = "0";
            } else {
                $produkorder .= $modUkuranWidth[0]['width'];
                $condition['produk_l'] = $modUkuranWidth[0]['width'];
            }
            if (count($modUkuranLength) > 1) {
                $produkorder .= "0";
                $condition['produk_p'] = "0";
            } else {
                $produkorder .= $modUkuranLength[0]['length'];
                $condition['produk_p'] = $modUkuranLength[0]['length'];
            }
            $condition['produk_t_satuan'] = $modUkuranThick[0]['thick_unit'];
            $condition['produk_l_satuan'] = $modUkuranWidth[0]['width_unit'];
            $condition['produk_p_satuan'] = $modUkuranLength[0]['length_unit'];
        }
        $produkorder = str_replace(" ", "", $produkorder);
        return $condition;
    }

    /**
     * @return void|Response
     */
    public function actionGetItems()
    {
        if (Yii::$app->request->isAjax) {
            $packinglist_id = Yii::$app->request->post('packinglist_id');
            $data = [];
            if (!empty($packinglist_id)) {
                try {
                    $model = new TInvoice();
                    $modPackinglist = TPackinglist::findOne($packinglist_id);
                    $data['html'] = '';
                    if ($modPackinglist->bundle_partition) {
                        $sql = "
                            SELECT 
                                packinglist_id, 
                                grade, 
                                jenis_kayu, 
                                glue, 
                                profil_kayu, 
                                kondisi_kayu, 
                                max(bundles_no) AS bundles, 
                                SUM(pcs) AS pcs, 
                                SUM(volume) AS volume, 
                                SUM( ROUND(volume::numeric,4) ) AS volume_display
							FROM t_packinglist_container
							WHERE 
							    packinglist_id = $packinglist_id 
							GROUP by 1,2,3,4,5,6
							ORDER by 1,2
                        ";
                    } else {
                        $sql = "
                            SELECT 
                                packinglist_id, 
                                grade, 
                                jenis_kayu, 
                                glue, 
                                profil_kayu, 
                                kondisi_kayu, 
                                thick, 
                                thick_unit, 
                                length, 
                                length_unit, 
                                width, 
                                width_unit, 
                                count(packinglist_id) AS bundles, 
                                SUM(pcs) AS pcs, 
                                SUM(volume) AS volume, 
                                SUM( ROUND(volume::numeric,4) ) AS volume_display
							FROM t_packinglist_container
							WHERE 
							    packinglist_id = $packinglist_id
							GROUP by 1,2,3,4,5,6,7,8,9,10,11,12
							ORDER by 1,2
                        ";
                    }
                    $modContainer = Yii::$app->db->createCommand($sql)->queryAll();
                    if (count($modContainer) > 0) {
                        foreach ($modContainer as $i => $container) {
                            $condition = self::getMasterProduk($modPackinglist->opExport->jenis_produk, $container, $packinglist_id, $modPackinglist->bundle_partition);
                            if ($modPackinglist->bundle_partition === TRUE) {
                                $sql = "
                                    SELECT 
                                        container_no, 
                                        bundles_no 
                                    FROM t_packinglist_container 
                                    WHERE 
                                        packinglist_id = " . $packinglist_id . " 
                                        " . (!empty($condition['grade']) ? "AND grade='{$condition['grade']}'" : "") . "
                                        " . (!empty($condition['jenis_kayu']) ? "AND jenis_kayu='{$condition['jenis_kayu']}'" : "") . "
                                        " . (!empty($condition['glue']) ? "AND glue='{$condition['glue']}'" : "") . "
                                        " . (!empty($condition['profil_kayu']) ? "AND profil_kayu='{$condition['profil_kayu']}'" : "") . "
                                        " . (!empty($condition['kondisi_kayu']) ? "AND kondisi_kayu='{$condition['kondisi_kayu']}'" : "") . "
                                        " . (!empty($condition['warna_kayu']) ? "AND warna_kayu='{$condition['warna_kayu']}'" : "") . "
                                    GROUP BY container_no, bundles_no
                                    ORDER BY bundles_no
                                ";
                                $searchbund = Yii::$app->db->createCommand($sql)->queryAll();
                                $bundles = count($searchbund);
                            } else {
                                $bundles = $container['bundles'];
                            }
                            $modProduk = MBrgProduk::findOne($condition);
                            $modDetail = new TInvoiceDetail();
                            if ($modProduk !== null) {
                                $modDetail->produk_id = $modProduk->produk_id;
                            } else {
                                // create new Master Produk
                                $modProduk = MBrgProduk::createNewByPackinglist($condition);
                                $modDetail->produk_id = $modProduk['produk_id'];
                                // end create
                            }
                            $modDetail->qty_besar = $bundles;
                            $modDetail->satuan_besar = "Palet";
                            $modDetail->qty_kecil = $container['pcs'];
                            $modDetail->satuan_kecil = "Pcs";
                            $modDetail->kubikasi = $container['volume'];
                            $modDetail->kubikasi_display = $container['volume_display'];
                            $modDetail->harga_hpp = 0;
                            $modDetail->harga_jual = 0;
                            $data['html'] .= $this->renderPartial('_item', ['model' => $model, 'modDetail' => $modDetail, 'modPackinglist' => $modPackinglist, 'i' => $i]);
                        }
                    }
                }catch (Exception $exception) {
                    $data['html'] = $exception->getMessage();
                    return $this->asJson($data);
                }
            }

            return $this->asJson($data);
        }
    }

    /**
     * @return void|Response
     * @throws Exception
     */
    public function actionGetItemsById()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $edit = Yii::$app->request->post('edit');
            $model = TInvoice::findOne($id);
            $modPackinglist = TPackinglist::findOne($model->packinglist_id);
            $diff_size_diff_price = Yii::$app->request->post('diff_size_diff_price');
            $grouping_qty = Yii::$app->request->post('grouping_qty');
            $modDetail = [];
            $data = [];
            if (!empty($id)) {
                $modDetail = TInvoiceDetail::find()->where(['invoice_id' => $id])->all();
                if ($modPackinglist->bundle_partition === FALSE && empty($edit) && $diff_size_diff_price === 'false') {
                    $modDetail = TInvoiceDetail::find()
                        ->select(["keterangan", "grade", "harga_jual", "SUM(qty_besar) AS qty_besar", "SUM(qty_kecil) AS qty_kecil", "SUM( ROUND(kubikasi_display::numeric,4) ) AS kubikasi_grouping"])
                        ->join("JOIN", "m_brg_produk", "t_invoice_detail.produk_id = m_brg_produk.produk_id")
                        ->where("invoice_id = " . $id)
                        ->groupBy("keterangan, grade, harga_jual")
                        ->all();
//                    var_dump($modDetail->createCommand()->getRawSql());die;
                }
                if ($modPackinglist->bundle_partition === FALSE && empty($edit) && $grouping_qty === 'true') {
                    $sql = 'SELECT "packinglist_id", "grade", "jenis_kayu", "glue", "profil_kayu", "kondisi_kayu", "width", "width_unit", "length", "length_unit", "thick", "thick_unit",  
									count(packinglist_id) AS bundles, pcs as pcsgroup, SUM(pcs) AS pcs, SUM(volume) AS volume, SUM( ROUND(volume::numeric,4) ) AS volume_display 
							FROM t_packinglist_container
							WHERE packinglist_id = ' . $model->packinglist_id . ' 
							GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,14
							ORDER BY 1,2';
                    $modContainer = Yii::$app->db->createCommand($sql)->queryAll();
                    $modDetail = [];
                    foreach ($modContainer as $i => $container) {
                        $condition = self::getMasterProduk($modPackinglist->jenis_produk, $container, $model->packinglist_id, $modPackinglist->bundle_partition);
                        $modProduk = MBrgProduk::findOne($condition);
                        $modDetail[$i] = TInvoiceDetail::findOne(['invoice_id' => $id, 'produk_id' => $modProduk->produk_id]);
                        $modDetail[$i]->qty_besar = $container['bundles'];
                        $modDetail[$i]->qty_kecil = $container['pcs'];
                        $modDetail[$i]->kubikasi = $container['volume'];
                        $modDetail[$i]->kubikasi_display = $container['volume_display'];
                        $modDetail[$i]->grade = $container['grade'];
                        $modDetail[$i]->kubikasi_grouping = $container['volume_display'];
                    }
                }
            }
            $data['model'] = $model->attributes;
            $data['html'] = '';
            if (count($modDetail) > 0) {
                foreach ($modDetail as $i => $detail) {
                    if (
                        ($modPackinglist->bundle_partition === FALSE && empty($edit) && $diff_size_diff_price === 'false') ||
                        ($modPackinglist->bundle_partition === FALSE && empty($edit) && $grouping_qty === 'true')
                    ){
                        $data['html'] .= $this->renderPartial('_itemGrouping', ['model' => $model, 'modDetail' => $detail, 'i' => $i, 'edit' => $edit, 'modPackinglist' => $modPackinglist]);
                    } else {
                        $data['html'] .= $this->renderPartial('_item', ['model' => $model, 'modDetail' => $detail, 'i' => $i, 'edit' => $edit, 'modPackinglist' => $modPackinglist]);
                    }
                }
            }
//            echo "<pre>";
//            print_r($data);
//            exit;
            return $this->asJson($data);
        }
    }

    public function actionDaftarAfterSave()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->get('dt') == 'modal-aftersave') {
                $param['table'] = TInvoice::tableName();
                $param['pk'] = $param['table'] . "." . TInvoice::primaryKey()[0];
                $param['column'] = [$param['table'] . '.invoice_id',
                    $param['table'] . '.nomor AS kode_invoice', // 1
                    't_packinglist.kode AS kode_pl', // 2
                    $param['table'] . '.jenis_produk', // 3
                    $param['table'] . '.tanggal', // 4
                    'm_customer.cust_an_nama', // 5
                    'nomor_kontrak', // 6
                    $param['table'] . '.status', // 7
                    $param['table'] . '.status_inv', // 8
                    $param['table'] . '.piutang_active', // 9
                    $param['table'] . '.bl_no', // 10
                    $param['table'] . '.bl_tanggal', // 11
                    $param['table'] . '.cancel_transaksi_id'
                ];
                $param['join'] = ['JOIN m_customer ON m_customer.cust_id = ' . $param['table'] . '.cust_id
								  JOIN t_op_export ON t_op_export.op_export_id = ' . $param['table'] . '.op_export_id
								  JOIN t_packinglist ON t_packinglist.packinglist_id = t_invoice.packinglist_id'];
                return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
            }
            return $this->renderAjax('daftarAfterSave');
        }
    }

    public function actionSetDropdownPackinglist()
    {
        if (Yii::$app->request->isAjax) {
            $status_inv = Yii::$app->request->post('status_inv');
            $data['html'] = [];
            $arraymap = TPackinglist::getOptionListInvoiceBaru($status_inv);
            $html = \yii\bootstrap\Html::tag('option');
            foreach ($arraymap as $i => $val) {
                $html .= \yii\bootstrap\Html::tag('option', $val, ['value' => $i,]);
            }
            $data['html'] = $html;
            return $this->asJson($data);
        }
    }

    public function actionPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        if (!empty($_GET['id'])) {
            $model = TInvoice::findOne($_GET['id']);
            $modOpEx = \app\models\TOpExport::findOne($model->op_export_id);
            $modPackinglist = TPackinglist::findOne($model->packinglist_id);
            $modContainer = \app\models\TPackinglistContainer::find()->where(['packinglist_id' => $model->packinglist_id])->all();
            $caraprint = Yii::$app->request->get('caraprint');
            if ($model->status_inv == "FINAL") {
                $paramprint['judul'] = Yii::t('app', 'COMMERCIAL INVOICE');
            } else {
                $paramprint['judul'] = Yii::t('app', 'PROFORMA INVOICE');
            }
            $paramprint['judul2'] = $model->jenis_produk;
            if ($modPackinglist->bundle_partition === FALSE && $model->diff_size_diff_price == FALSE) {
                $modDetails = TInvoiceDetail::find()
                    ->select(["keterangan", "grade", "harga_jual", "SUM(qty_besar) AS qty_besar", "SUM(qty_kecil) AS qty_kecil", "SUM( ROUND(kubikasi::numeric,4) ) AS kubikasi_grouping", "SUM( ROUND(kubikasi_display::numeric,4) ) AS kubikasi_display"])
                    ->join("JOIN", "m_brg_produk", "t_invoice_detail.produk_id = m_brg_produk.produk_id")
                    ->where("invoice_id = " . $_GET['id'])
                    ->groupBy("keterangan, grade, harga_jual")
                    ->all();
                $viewPrint = "printInvoiceGrouping";
            } else if ($modPackinglist->bundle_partition === FALSE && $model->grouping_qty == TRUE) {
                $sql = 'SELECT "packinglist_id", "grade", "jenis_kayu", "glue", "profil_kayu", "kondisi_kayu", "width", "width_unit", "length", "length_unit", "thick", "thick_unit",  
								count(packinglist_id) AS bundles, pcs as pcsgroup, SUM(pcs) AS pcs, SUM(volume) AS volume, SUM( ROUND(volume::numeric,4) ) AS volume_display 
						FROM t_packinglist_container
						WHERE packinglist_id = ' . $model->packinglist_id . ' 
						GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,14
						ORDER BY 1,2';
                $modContainer = Yii::$app->db->createCommand($sql)->queryAll();
                $modDetails = [];
                foreach ($modContainer as $i => $container) {
                    $condition = self::getMasterProduk($modPackinglist->jenis_produk, $container, $model->packinglist_id, $modPackinglist->bundle_partition);
                    $modProduk = MBrgProduk::findOne($condition);
                    $modDetails[$i] = TInvoiceDetail::findOne(['invoice_id' => $_GET['id'], 'produk_id' => $modProduk->produk_id]);
                    $modDetails[$i]->qty_besar = $container['bundles'];
                    $modDetails[$i]->qty_kecil = $container['pcs'];
                    $modDetails[$i]->kubikasi = $container['volume'];
                    $modDetails[$i]->kubikasi_display = $container['volume_display'];
                    $modDetails[$i]->grade = $container['grade'];
                    $modDetails[$i]->kubikasi_grouping = $container['volume_display'];
                }
                $viewPrint = "printInvoiceGrouping";
            } else {
                $modDetails = TInvoiceDetail::find()->where(['invoice_id' => $_GET['id']])->orderBy("invoice_detail_id ASC")->all();
                $viewPrint = "printInvoice";
            }
            if ($caraprint == 'PRINT') {
                return $this->render($viewPrint, ['model' => $model, 'modDetails' => $modDetails, 'modOpEx' => $modOpEx, 'modPackinglist' => $modPackinglist, 'modContainer' => $modContainer, 'paramprint' => $paramprint]);
            } else if ($caraprint == 'PDF') {
                $pdf = Yii::$app->pdf;
                $pdf->options = ['title' => $paramprint['judul']];
                $pdf->filename = $paramprint['judul'] . '.pdf';
                $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
                $pdf->content = $this->render($viewPrint, ['model' => $model, 'modDetails' => $modDetails, 'modOpEx' => $modOpEx, 'modPackinglist' => $modPackinglist, 'modContainer' => $modContainer, 'paramprint' => $paramprint]);
                return $pdf->render();
            } else if ($caraprint == 'EXCEL') {
                return $this->render($viewPrint, ['model' => $model, 'modDetails' => $modDetails, 'modOpEx' => $modOpEx, 'modPackinglist' => $modPackinglist, 'modContainer' => $modContainer, 'paramprint' => $paramprint]);
            } else if ($caraprint == 'MODAL') {
                $this->layout = '@views/layouts/metronic/main';
                return $this->renderAjax('infoInvoice', ['model' => $model, 'modDetails' => $modDetails, 'modOpEx' => $modOpEx, 'modPackinglist' => $modPackinglist, 'modContainer' => $modContainer, 'paramprint' => $paramprint, 'viewPrint' => $viewPrint]);
            }
        }
    }

    public function actionDeleteInvoice($id)
    {
        if (Yii::$app->request->isAjax) {
            $tableid = Yii::$app->request->get('tableid');
            $model = TInvoice::findOne($id);
            $packinglist_id = $model->packinglist_id;
            $modPackinglist = TPackinglist::findOne($packinglist_id);
            if (Yii::$app->request->post('deleteRecord')) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
                    $success_3 = false;

                    if (TInvoiceDetail::deleteAll("invoice_id = " . $id)) {
                        $success_2 = true;
                    } else {
                        $success_2 = false;
                    }

                    if ($model->delete()) {
                        $success_1 = true;
                    } else {
                        $data['message'] = Yii::t('app', 'Data Gagal dihapus');
                    }

                    if ($success_1 && $success_2 && $modPackinglist->status == "FINAL") {
                        // update status t_packinglist dari FINAL jadi PROFORMA
                        $sql_update = "update t_packinglist set status = 'PROFORMA' where packinglist_id = '" . $packinglist_id . "' ";
                        $success_3 = Yii::$app->db->createCommand($sql_update)->execute($sql_update);
                    } else {
                        // lolos kan saja karena statusnya proforma
                        $success_3 = true;
                    }

                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm', ['id' => $id, 'tableid' => $tableid, 'actionname' => 'deleteInvoice']);
        }
    }

    public function actionUpdateDiffSizeDiffPrice()
    {
        if (Yii::$app->request->isAjax) {
            $diff_size_diff_price = Yii::$app->request->post('diff_size_diff_price');
            $invoice_id = Yii::$app->request->post('invoice_id');
            $model = TInvoice::findOne($invoice_id);
            $data = false;
            if ($diff_size_diff_price == 'false') {
                $model->diff_size_diff_price = false;
                if ($model->validate()) {
                    $data = $model->save();
                }
            } else {
                $model->diff_size_diff_price = true;
                if ($model->validate()) {
                    $data = $model->save();
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionUpdateGroupingQty()
    {
        if (Yii::$app->request->isAjax) {
            $grouping_qty = Yii::$app->request->post('grouping_qty');
            $invoice_id = Yii::$app->request->post('invoice_id');
            $model = TInvoice::findOne($invoice_id);
            $data = false;
            if ($grouping_qty == 'false') {
                $model->grouping_qty = false;
                if ($model->validate()) {
                    $data = $model->save();
                }
            } else {
                $model->grouping_qty = true;
                if ($model->validate()) {
                    $data = $model->save();
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionUpdateTotalBayar()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $total = Yii::$app->request->post('total');
            $model = TInvoice::findOne($id);
            $model->total_harga = $total;
            $model->total_bayar = $total;
            if ($model->validate()) {
                $data = $model->save();
            }
            return $this->asJson($data);
        }
    }

    public function actionSetSubtotal()
    {
        if (Yii::$app->request->isAjax) {
            $kubikasi_display = Yii::$app->request->post('kubikasi_display');
            $tgl = Yii::$app->request->post('tgl');
            $harga = Yii::$app->request->post('harga');
            $subtotal = $kubikasi_display * $harga;
            if (strlen(substr(strrchr($subtotal, "."), 1)) > 2) {

                // START KEBIJAKAN Perubahan Pembulatan dari Round Up ke Round Per tgl 14 Sept 2019
                $tgl = date('Y-m-d', strtotime(\app\components\DeltaFormatter::formatDateTimeForDb($tgl)));
                $tgl_kebijakan = date('Y-m-d', strtotime("2019-09-13"));
                if ($tgl > $tgl_kebijakan) {
                    $subtotal = round($subtotal, 2);
                } else {
                    $subtotal = \app\components\DeltaFormatter::roundUp($subtotal, 2);
                }
                // END KEBIJAKAN
            }
            return $this->asJson($subtotal);
        }
    }

    public function actionKonfirmsipeb($invoice_id)
    {
        if (Yii::$app->request->isAjax) {
            $modCompany = \app\models\CCompanyProfile::findOne(\app\components\Params::DEFAULT_COMPANY_PROFILE);
            $model = TInvoice::findOne($invoice_id);
            $modPackinglist = TPackinglist::findOne($model->packinglist_id);

            $model->peb_kantorpabean_pemuatan = "060100   KPPBC Tanjung Emas";
            $model->peb_kantorpabean_ekspor = "060100   KPPBC Tanjung Emas";
            $model->peb_jenis_ekspor = "Ekspor Biasa";
            $model->peb_kategori_ekspor = "Umum";
            $model->peb_cara_perdagangan = "Lainnya";
            if ($model->payment_method == "TT") {
                $model->peb_cara_pembayaran = "Transfer";
            } elseif ($model->payment_method == "LC") {
                $model->peb_cara_pembayaran = "LC";
                $model->peb_carapembayaran_lcno = $model->payment_method_reff;
            }
            $model->peb_eksportir_identitas = "Npwp 15 Digit   01.830.994.8-511.000";
            $model->peb_eksportir_nama = "PT. CIPTA WIJAYA MANDIRI";
            $model->peb_eksportir_alamat = "JL. RAYA SEMARANG-PURWODADI KM. 16,5 NO.349 MRANGGEN DEMAK 59567";
            $model->peb_eksportir_status = "PMDN (non migas)";
            $model->peb_pengangkutan_cara_pengangkutan = "Laut";
            $model->peb_pelengkappabean_no_inv = $model->nomor;
            $model->peb_pelengkappabean_tgl_inv = date("d-m-Y", strtotime($model->tanggal));
            $model->peb_pelengkappabean_no_packing = $modPackinglist->nomor;
            $model->peb_pelengkappabean_tgl_packing = date("d-m-Y", strtotime($modPackinglist->tanggal));
            $model->peb_pelengkappabean_jenis_dok = "Sertifikat Legalitasi Kayu (Dok. V-Legal)";
            $model->peb_pelengkappabean_no = $modPackinglist->vlegal_no;
            $model->peb_pelengkappabean_kantor_beacukai = "-";
            $model->peb_transaksiekspor_bank_devisa = "014 - BCA";
            $model->peb_transaksiekspor_jenis_valuta = "USD   US DOllar";
            $model->peb_transaksiekspor_fob = $model->fob;
            $model->peb_transaksiekspor_freight = $model->freight;
            $model->peb_petikemas_jml = count(\app\models\TPackinglistContainer::find()->select("container_no")->where("packinglist_id = {$model->packinglist_id} and container_size ilike '%20%'")->groupBy("container_no")->all()) . " x 20feet;  " .
                count(\app\models\TPackinglistContainer::find()->select("container_no")->where("packinglist_id = {$model->packinglist_id} and container_size ilike '%40%'")->groupBy("container_no")->all()) . " x 40feet";
            $model->peb_petikemas_no = "";
            $modContKode = \app\models\TPackinglistContainer::find()->select("container_kode")->where("packinglist_id = {$model->packinglist_id}")->groupBy("container_kode")->all();
            foreach ($modContKode as $i => $cont) {
                $model->peb_petikemas_no .= $cont->container_kode . "; ";
            }
            $modContWeight = \app\models\TPackinglistContainer::find()->select("gross_weight, nett_weight")->where("packinglist_id = {$model->packinglist_id}")->groupBy("gross_weight, nett_weight")->all();
            $model->peb_barangekspor_bruto = 0;
            $model->peb_barangekspor_netto = 0;
            foreach ($modContWeight as $ii => $contii) {
                $model->peb_barangekspor_bruto += $contii->gross_weight;
                $model->peb_barangekspor_netto += $contii->nett_weight;
            }
            $model->peb_penerima_nama = (!empty($modPackinglist->notify_party) ? $modPackinglist->notifyParty->cust_an_nama : $modPackinglist->cust->cust_an_nama);
            $model->peb_penerima_alamat = (!empty($modPackinglist->notify_party) ? $modPackinglist->notifyParty->cust_an_alamat : $modPackinglist->cust->cust_an_alamat);
            $model->peb_pembeli_nama = $modPackinglist->cust->cust_an_nama;
            $model->peb_pembeli_alamat = $modPackinglist->cust->cust_an_alamat;
            $model->peb_pelabuhanmuat_muat_asal = "IDTES   Tanjung Emas";
            $model->peb_pelabuhanmuat_muat_ekspor = "IDTES   Tanjung Emas";
            $model->peb_pelabuhanmuat_bongkar = $modPackinglist->final_destination;
            $model->peb_pelabuhanmuat_tujuan = $modPackinglist->final_destination;
            $model->peb_tempatperiksa_lokasi = "Gudang Eksportir";
            $model->peb_tempatperiksa_kantor = "060100  KPPBC Tanjung Emas";
            $model->peb_kemasan_jenis_jml = "BE / Bundle, " . $modPackinglist->total_bundles;
            $model->detail_uraian = $modPackinglist->hs_code . "\n" . $modPackinglist->goods_description . "\n" . $modPackinglist->total_bundles . " BE/Bundle";
            $model->detail_qty = \app\components\DeltaFormatter::formatNumberForUserFloat($modPackinglist->total_volume) .
                " MTQ/Cubic Metre\n" . \app\components\DeltaFormatter::formatNumberForUserFloat($model->peb_barangekspor_netto) .
                " Kgm\n" . \app\components\DeltaFormatter::formatNumberForUserFloat($modPackinglist->total_volume) . " m3";
            $model->detail_asal = "- Indonesia\n- Kab. Demak";
            $model->detail_fob = \app\components\DeltaFormatter::formatNumberForUserFloat($model->fob);
            $model->detail_freight = \app\components\DeltaFormatter::formatNumberForUserFloat($model->freight);
            $model->peb_barangekspor_nilai_tukar = "0.0000";
            $model->peb_penerimaannegara_bea_keluar = "0.00";
            $model->peb_penerimaannegara_pajak = "0.00";

            if (!empty($model->data_peb)) {
                $data_peb = \yii\helpers\Json::decode($model->data_peb);
                $model->peb_kode_beacukai = $data_peb['peb_kode_beacukai'];
                $model->peb_no_pengajuan = $data_peb['peb_no_pengajuan'];
                $model->peb_kantorpabean_pemuatan = $data_peb['peb_kantorpabean_pemuatan'];
                $model->peb_kantorpabean_pemuatan = $data_peb['peb_kantorpabean_pemuatan'];
                $model->peb_kantorpabean_ekspor = $data_peb['peb_kantorpabean_ekspor'];
                $model->peb_jenis_ekspor = $data_peb['peb_jenis_ekspor'];
                $model->peb_kategori_ekspor = $data_peb['peb_kategori_ekspor'];
                $model->peb_cara_perdagangan = $data_peb['peb_cara_perdagangan'];
                $model->peb_cara_pembayaran = $data_peb['peb_cara_pembayaran'];
                if ($model->peb_cara_pembayaran == "LC") {
                    $model->peb_carapembayaran_lcno = $data_peb['peb_carapembayaran_lcno'];
                    $model->peb_carapembayaran_lctgl = $data_peb['peb_carapembayaran_lctgl'];
                }
                $model->peb_eksportir_identitas = $data_peb['peb_eksportir_identitas'];
                $model->peb_eksportir_nama = $data_peb['peb_eksportir_nama'];
                $model->peb_eksportir_alamat = $data_peb['peb_eksportir_alamat'];
                $model->peb_eksportir_niper = $data_peb['peb_eksportir_niper'];
                $model->peb_eksportir_status = $data_peb['peb_eksportir_status'];
                $model->peb_ppjk_npwp = $data_peb['peb_ppjk_npwp'];
                $model->peb_ppjk_nama = $data_peb['peb_ppjk_nama'];
                $model->peb_ppjk_alamat = $data_peb['peb_ppjk_alamat'];
                $model->peb_pengangkutan_cara_pengangkutan = $data_peb['peb_pengangkutan_cara_pengangkutan'];
                $model->peb_pengangkutan_nama_bendera = $data_peb['peb_pengangkutan_nama_bendera'];
                $model->peb_pengangkutan_no = $data_peb['peb_pengangkutan_no'];
                $model->peb_pengangkutan_tanggal_perkiraan = $data_peb['peb_pengangkutan_tanggal_perkiraan'];
                $model->peb_pelengkappabean_no_inv = $data_peb['peb_pelengkappabean_no_inv'];
                $model->peb_pelengkappabean_tgl_inv = $data_peb['peb_pelengkappabean_tgl_inv'];
                $model->peb_pelengkappabean_no_packing = $data_peb['peb_pelengkappabean_no_packing'];
                $model->peb_pelengkappabean_tgl_packing = $data_peb['peb_pelengkappabean_tgl_packing'];
                $model->peb_pelengkappabean_jenis_dok = $data_peb['peb_pelengkappabean_jenis_dok'];
                $model->peb_pelengkappabean_no = $data_peb['peb_pelengkappabean_no'];
                $model->peb_pelengkappabean_tgl = $data_peb['peb_pelengkappabean_tgl'];
                $model->peb_pelengkappabean_kantor_beacukai = $data_peb['peb_pelengkappabean_kantor_beacukai'];
                $model->peb_transaksiekspor_bank_devisa = $data_peb['peb_transaksiekspor_bank_devisa'];
                $model->peb_transaksiekspor_jenis_valuta = $data_peb['peb_transaksiekspor_jenis_valuta'];
                $model->peb_transaksiekspor_fob = $data_peb['peb_transaksiekspor_fob'];
                $model->peb_transaksiekspor_freight = $data_peb['peb_transaksiekspor_freight'];
                $model->peb_transaksiekspor_asuransi = $data_peb['peb_transaksiekspor_asuransi'];
                $model->peb_transaksiekspor_maklon = $data_peb['peb_transaksiekspor_maklon'];
                $model->peb_petikemas_jml = $data_peb['peb_petikemas_jml'];
                $model->peb_petikemas_no = $data_peb['peb_petikemas_no'];
                $model->peb_petikemas_ukuran = $data_peb['peb_petikemas_ukuran'];
                $model->peb_petikemas_status = $data_peb['peb_petikemas_status'];
                $model->peb_beacukai_no_daftar = $data_peb['peb_beacukai_no_daftar'];
                $model->peb_beacukai_tgl_daftar = $data_peb['peb_beacukai_tgl_daftar'];
                $model->peb_beacukai_no_bc = $data_peb['peb_beacukai_no_bc'];
                $model->peb_beacukai_tgl_bc = $data_peb['peb_beacukai_tgl_bc'];
                $model->peb_beacukai_pos = $data_peb['peb_beacukai_pos'];
                $model->peb_penerima_nama = $data_peb['peb_penerima_nama'];
                $model->peb_penerima_alamat = $data_peb['peb_penerima_alamat'];
                $model->peb_penerima_negara = $data_peb['peb_penerima_negara'];
                $model->peb_pembeli_nama = $data_peb['peb_pembeli_nama'];
                $model->peb_pembeli_alamat = $data_peb['peb_pembeli_alamat'];
                $model->peb_pembeli_negara = $data_peb['peb_pembeli_negara'];
                $model->peb_pelabuhanmuat_muat_asal = $data_peb['peb_pelabuhanmuat_muat_asal'];
                $model->peb_pelabuhanmuat_muat_ekspor = $data_peb['peb_pelabuhanmuat_muat_ekspor'];
                $model->peb_pelabuhanmuat_bongkar = $data_peb['peb_pelabuhanmuat_bongkar'];
                $model->peb_pelabuhanmuat_tujuan = $data_peb['peb_pelabuhanmuat_tujuan'];
                $model->peb_pelabuhanmuat_tujuan_ekspor = $data_peb['peb_pelabuhanmuat_tujuan_ekspor'];
                $model->peb_tempatperiksa_lokasi = $data_peb['peb_tempatperiksa_lokasi'];
                $model->peb_tempatperiksa_kantor = $data_peb['peb_tempatperiksa_kantor'];
                $model->peb_tempatperiksa_gudang = $data_peb['peb_tempatperiksa_gudang'];
                $model->peb_penyerahan_cara = $data_peb['peb_penyerahan_cara'];
                $model->peb_kemasan_jenis_jml = $data_peb['peb_kemasan_jenis_jml'];
                $model->peb_barangekspor_bruto = $data_peb['peb_barangekspor_bruto'];
                $model->peb_barangekspor_netto = $data_peb['peb_barangekspor_netto'];
                $model->detail_uraian = $data_peb[0]['detail_uraian'];
                $model->detail_he_barang = $data_peb[0]['detail_he_barang'];
                $model->detail_asal = $data_peb[0]['detail_asal'];
                $model->detail_fob = $data_peb[0]['detail_fob'];
                if (!isset($data_peb[0]['detail_freight'])) {
                    $model->detail_freight = 0; // Atur ke 0 jika tidak ada
                } else {
                    $model->detail_freight = $data_peb[0]['detail_freight']; // Ambil nilai jika ada
                }
                $model->peb_barangekspor_nilai_tukar = $data_peb['peb_barangekspor_nilai_tukar'];
                $model->peb_penerimaannegara_bea_keluar = $data_peb['peb_penerimaannegara_bea_keluar'];
                $model->peb_penerimaannegara_pajak = $data_peb['peb_penerimaannegara_pajak'];
            }

            if (Yii::$app->request->post('TInvoice')) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_1 = true;
                    $post = Yii::$app->request->post('TInvoice');
                    if (count($post) > 0) {
                        $model->data_peb = \yii\helpers\Json::encode($post);
                        if ($model->validate()) {
                            if ($model->save()) {
                                $success_1 &= true;
                            }
                        } else {
                            $success_1 = false;
                        }
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                        $data['callback'] = "$('#modal-konfirmsi').modal('hide'); window.open('" . \yii\helpers\Url::toRoute('/exim/invoice/printsipeb') . "?id={$model->invoice_id}&caraprint=PRINT','','location=_new, width=1200px, scrollbars=yes')";
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('konfirmsipeb', ['model' => $model, 'modPackinglist' => $modPackinglist, 'modCompany' => $modCompany]);
        }
    }

    public function actionPrintsipeb()
    {
        $this->layout = '@views/layouts/metronic/print';
        if (!empty($_GET['id'])) {
            $model = TInvoice::findOne($_GET['id']);
            $caraprint = Yii::$app->request->get('caraprint');
            $paramprint['judul'] = Yii::t('app', 'PEMBERITAHUAN EKSPOR BARANG');
            $paramprint['judul2'] = $model->jenis_produk;
            if ($caraprint == 'PRINT') {
                return $this->render('printPeb', ['model' => $model, 'paramprint' => $paramprint]);
            } else if ($caraprint == 'PDF') {
                $pdf = Yii::$app->pdf;
                $pdf->options = ['title' => $paramprint['judul']];
                $pdf->filename = $paramprint['judul'] . '.pdf';
                $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
                $pdf->content = $this->render('printPeb', ['model' => $model, 'paramprint' => $paramprint]);
                return $pdf->render();
            } else if ($caraprint == 'EXCEL') {
                return $this->render('printPeb', ['model' => $model, 'paramprint' => $paramprint]);
            }
        }
    }

    public function actionInputBL($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TInvoice::findOne($id);
            if (Yii::$app->request->post('TInvoice')) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
                    $post = Yii::$app->request->post('TInvoice');
                    if (count($post) > 0) {
                        $model->attributes = $post;
                        if ($model->validate()) {
                            if ($model->save()) {
                                $success_1 = true;

                                $modPackinglist = TPackinglist::findOne($model->packinglist_id);
                                $modPackinglist->etd = $model->bl_tanggal;
                                if ($modPackinglist->validate()) {
                                    if ($modPackinglist->save()) {
                                        $success_2 = true;
                                    } else {
                                        $success_2 = false;
                                    }
                                } else {
                                    $success_2 = false;
                                }
                            }
                        } else {
                            $success_1 = false;
                        }
                    }
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                        $data['callback'] = "$('#modal-input').modal('hide'); $('#table-aftersave').dataTable().fnClearTable();";
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('inputBL', ['model' => $model]);
        }
    }
}
