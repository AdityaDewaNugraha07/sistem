<?php
//echo"<pre>$id</pre>";

/** @var integer $id */

use app\components\DeltaFormatter;
use app\components\DeltaGlobalClass;
use app\models\MBrgProduk;
use app\models\MCustomer;
use app\models\MPenerbitBl;
use app\models\TInvoice;
use app\models\TInvoiceDetail;
use app\models\TOpExport;
use app\models\TPackinglist;

/**
 * @param array $data
 * @return string
 */
function renderDetail(array $data)
{
    return '
        <tr>
            <td style="text-align: center;">' . $data['no'] . '</td>
            <td style="">' . $data['nama'] . '</td>
            <td style="vertical-align:middle;">' . $data['dimensi'] . '</td>
            <td style="vertical-align:middle;text-align: right;">' . $data['qty_besar'] . '</td>
            <td style="vertical-align:middle;text-align: right;">' . $data['qty_kecil'] . '</td>
            <td style="vertical-align:middle;text-align: right;">' . $data['kubikasi_display'] . '</td>
            <td style="vertical-align:middle;text-align: right;">' . $data['harga_jual'] . '</td>
            <td style="vertical-align:middle;text-align: right;">' . $data['subtotal'] . '</td>
        </tr>
    ';
}

/**
 * @param array $data
 * @return string
 */
function renderFooter(array $data)
{
    return '
        <tr>
            <th colspan="3" style="text-align: right;">Total &nbsp;</th>
            <td style="text-align: right;">' . $data['totalbdl'] . '</td>
            <td style="text-align: right;">' . $data['totalpcs'] . '</td>
            <td style="text-align: right;">' . $data['totalm3'] . '</td>
            <td></td>
            <td style="text-align: right;">' . $data['total'] . '</td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: right;">Potongan &nbsp;</th>
            <td colspan="4" style="text-align: right;">' . $data['keterangan_potongan'] . '&nbsp;</td>
            <td style="text-align: right;">' . $data['total_potongan'] . '</td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: right;">Biaya Tambahan &nbsp;</th>
            <td colspan="4" style="text-align: right;">' . $data['keterangan_biaya_tambahan'] . '&nbsp;</td>
            <td style="text-align: right;">' . $data['total_biaya_tambahan'] . '</td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: right;">Grand Total &nbsp;</th>
            <td colspan="4" style="text-align: right;"></td>
            <td style="text-align: right;">' . $data['grandtotal'] . '</td>
        </tr>
    ';
}

/**
 * @param array $params
 * @return string|void
 */
function getDimentions(array $params)
{
    $tinggi = '';
    $lebar = '';
    $panjang = '';
    try {
        $vw_dimensi = "
            SELECT
                m_brg_produk.produk_t,
                m_brg_produk.produk_t_satuan,
                m_brg_produk.produk_l,
                m_brg_produk.produk_l_satuan,
                m_brg_produk.produk_p,
                m_brg_produk.produk_p_satuan 
            FROM
                t_invoice_detail
                INNER JOIN m_brg_produk ON t_invoice_detail.produk_id = m_brg_produk.produk_id 
            WHERE
                t_invoice_detail.harga_jual = {$params['harga_jual']} 
                AND t_invoice_detail.invoice_id = {$params['invoice_id']} 
                AND t_invoice_detail.keterangan = '{$params['keterangan']}'
                AND m_brg_produk.grade = '{$params['grade']}'
        ";

        $sql_t = "SELECT t.produk_t, t.produk_t_satuan FROM ($vw_dimensi) t GROUP BY t.produk_t, t.produk_t_satuan";
        $sql_l = "SELECT l.produk_l, l.produk_l_satuan FROM ($vw_dimensi) l GROUP BY l.produk_l, l.produk_l_satuan";
        $sql_p = "SELECT p.produk_p, p.produk_p_satuan FROM ($vw_dimensi) p GROUP BY p.produk_p, p.produk_p_satuan";

        $result_tinggi = Yii::$app->db->createCommand($sql_t)->queryAll();
        $result_lebar = Yii::$app->db->createCommand($sql_l)->queryAll();
        $result_panjang = Yii::$app->db->createCommand($sql_p)->queryAll();

        if (count($result_tinggi) > 0) {
            foreach ($result_tinggi as $key => $t) {
                if ($key !== count($result_tinggi) - 1) {
                    $tinggi .= $t['produk_t'] . '/';
                } else {
                    $tinggi .= $t['produk_t'] . ' ' . $t['produk_t_satuan'];
                }
            }
        }

        if (count($result_lebar) > 0) {
            foreach ($result_lebar as $key => $l) {
                if ($key !== count($result_lebar) - 1) {
                    $lebar .= $l['produk_l'] . '/';
                } else {
                    $lebar .= $l['produk_l'] . ' ' . $l['produk_l_satuan'];
                }
            }
        }

        if (count($result_panjang) > 0) {
            if (count($result_panjang) === 1) {
                $panjang = $result_panjang[0]['produk_p'] . ' ' . $result_panjang[0]['produk_p_satuan'];
            } else {
                asort($result_panjang);
                $last = end($result_panjang);
                $panjang = $result_panjang[0]['produk_p'] . ' ' . $result_panjang[0]['produk_p_satuan'] . ' ~ ' . $last['produk_p'] . ' ' . $last['produk_p_satuan'];
            }
        }

        if($tinggi !== '' && $lebar !== '' && $panjang !== '') {
            return $tinggi . ' X ' . $lebar . ' X ' . $panjang;
        }
    } catch (\yii\db\Exception $exception) {
        return $exception->getMessage();
    }
}

$model = TInvoice::findOne($id);
$modPackinglist = TPackinglist::findOne(['packinglist_id' => $model->packinglist_id]);
$modCustomer = MCustomer::findOne(['cust_id' => $model->cust_id]);
$modOpEx = TOpExport::findOne(['op_export_id' => $modPackinglist->op_export_id]);
$modPenerbitbl = null;
if (!empty($model->penerbit_bl_id)) {
    $modPenerbitbl = MPenerbitBl::findOne(['penerbit_bl_id' => $model->penerbit_bl_id]);
}

//foreach($modDetail as $i => $detail){
//    echo"<pre>test </pre>";
//}
?>

<style>
    .form-group {
        margin-bottom: 0 !important;
    }
</style>
<div class="modal fade" id="modal-master-info" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                        aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Commercial Invoice') . '<b>' . DeltaGlobalClass::getBerkasNamaByBerkasKode($model->kode) . '</b>' ?></h4>
            </div>

            <div class="modal-body">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Status Invoice') ?></label>
                            <div class="col-md-7"><?= $model->status_inv ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Invoice No.') ?></label>
                            <div class="col-md-7"><?= $model->nomor ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal') ?></label>
                            <div class="col-md-7"><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nomor Kontrak') ?></label>
                            <div class="col-md-7"><?= $modOpEx->nomor_kontrak ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Shiper') ?></label>
                            <div class="col-md-7">  <?= $modPackinglist->shipper ?> </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Shipment To') ?></label>
                            <div class="col-md-7"><?= $modCustomer->cust_an_nama . "<br>" . $modCustomer->cust_an_alamat ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Port Of Loading') ?></label>
                            <div class="col-md-7"><?= $modPackinglist->port_of_loading ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'ETD') ?></label>
                            <div class="col-md-7" style="line-height: 0.8; margin-bottom: 10px;">
                                <?= app\components\DeltaFormatter::formatDateTimeForUser2($modPackinglist->etd) ?>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Final Destination') ?></label>
                            <div class="col-md-7"><?= $modPackinglist->final_destination ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'ETA') ?></label>
                            <div class="col-md-7" style="line-height: 0.8; margin-bottom: 10px;">
                                <?= app\components\DeltaFormatter::formatDateTimeForUser2($modPackinglist->eta) ?>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Static Product Code') ?></label>
                            <div class="col-md-7"
                                 style="line-height: 0.8; margin-bottom: 10px;"><?= $modPackinglist->static_product_code ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Hs Code') ?></label>
                            <div class="col-md-7"
                                 style="line-height: 0.8; margin-bottom: 10px;"><?= $modPackinglist->hs_code ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Svlk No') ?></label>
                            <div class="col-md-7"
                                 style="line-height: 0.8; margin-bottom: 10px;"><?= $modPackinglist->svlk_no ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Vlegal No') ?></label>
                            <div class="col-md-7"
                                 style="line-height: 0.8; margin-bottom: 10px;"><?= $modPackinglist->vlegal_no ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Vessel') ?></label>
                            <div class="col-md-7"><?= $modPackinglist->vessel ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Payment Method') ?></label>
                            <div class="col-md-7"><?= $model->payment_method . " " . $model->payment_method_reff ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Term of Price') ?></label>
                            <div class="col-md-7"><?= $model->term_of_price ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Goods Description') ?></label>
                            <div class="col-md-7"><?= $modPackinglist->goods_description ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'FOB') ?></label>
                            <div class="col-md-7"><?= $model->fob ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'PEB No. / Date') ?></label>
                            <div class="col-md-7"><?= $model->peb_no . " Date " . app\components\DeltaFormatter::formatDateTimeForUser2($model->peb_tanggal) ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'B/L No. / Date') ?></label>
                            <div class="col-md-7">
                                <?php
                                if (!empty($model->bl_no)) {
                                    echo $model->bl_no . " Date " . app\components\DeltaFormatter::formatDateTimeForUser2($model->bl_tanggal);
                                }
                                ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Penerbit B/L') ?></label>
                            <div class="col-md-7">
                                <?php
                                if (!empty($model->penerbit_bl_id)) {
                                    echo $modPenerbitbl->nama;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Shipping Instruction Marks') ?></label>
                            <div class="col-md-7"><?= $model->marks ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet box blue-hoki bordered">
                            <div class="portlet-title">
                                <div class="tools" style="float: left;">
                                    <a href="javascript:void(0)" class="collapse" data-original-title="" title=""> </a>
                                    &nbsp;
                                </div>
                                <div class="caption"> <?= Yii::t('app', 'Show Detail') ?> </div>
                            </div>
                            <div class="portlet-body" style="background-color: #d9e2f0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover"
                                                   id="table-detail">
                                                <thead>
                                                <tr>
                                                    <th style="width: 30px;">No.</th>
                                                    <th style="text-align: center;"><?= Yii::t('app', 'Produk') ?></th>

                                                    <th style=""><?= Yii::t('app', 'Dimensi') ?></th>
                                                    <th style=""><?= Yii::t('app', 'Bdl') ?></th>
                                                    <th style=""><?= Yii::t('app', 'Pcs') ?></th>
                                                    <th style=""><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                                    <th style=""><?= Yii::t('app', 'Price ($) / M<sup>3</sup>') ?></th>
                                                    <th style=""><?= Yii::t('app', 'Subtotal') ?></th>
                                                </tr>
                                                </thead>
                                                <?php
                                                $totalbdl = 0;
                                                $totalpcs = 0;
                                                $totalm3 = 0;
                                                $total = 0;
                                                $grandtotal = 0;
                                                $dataFooter = [];
                                                $htmlDetail = '';
                                                $htmlFooter = '';
                                                $modDetail = null;

                                                if ($model->diff_size_diff_price === false && $model->grouping_qty === false) {
                                                    $modDetail = TInvoiceDetail::find()
                                                        ->select([
                                                            "keterangan",
                                                            "grade",
                                                            "harga_jual",
                                                            "SUM(qty_besar) AS qty_besar",
                                                            "SUM(qty_kecil) AS qty_kecil",
                                                            "SUM( ROUND(kubikasi_display::numeric,4) ) AS kubikasi_grouping"
                                                        ])
                                                        ->join("JOIN", "m_brg_produk", "t_invoice_detail.produk_id = m_brg_produk.produk_id")
                                                        ->where("invoice_id = " . $model->invoice_id)
                                                        ->groupBy("keterangan, grade, harga_jual")
                                                        ->all();
                                                }else if($model->grouping_qty === true) {
                                                    try {
                                                        $sql = "
                                                            SELECT
                                                                packinglist_id,
                                                                grade,
                                                                jenis_kayu,
                                                                glue,
                                                                profil_kayu,
                                                                kondisi_kayu,
                                                                width,
                                                                width_unit,
                                                                length,
                                                                length_unit,
                                                                thick,
                                                                thick_unit,
                                                                COUNT ( packinglist_id ) AS bundles,
                                                                pcs AS pcsgroup,
                                                                SUM ( pcs ) AS pcs,
                                                                SUM ( volume ) AS volume,
                                                                SUM ( ROUND( volume :: NUMERIC, 4 ) ) AS volume_display 
                                                            FROM
                                                                t_packinglist_container 
                                                            WHERE
                                                                packinglist_id = $model->packinglist_id
                                                            GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,14
                                                            ORDER BY 1,2";
                                                        $modContainer = Yii::$app->db->createCommand($sql)->queryAll();
                                                        $modDetail = [];
                                                        foreach ($modContainer as $i => $container) {
                                                            $condition = MBrgProduk::getMasterProduk($modPackinglist->jenis_produk, $container, $model->packinglist_id, $modPackinglist->bundle_partition);
                                                            $modProduk = MBrgProduk::findOne($condition);
                                                            $modDetail[$i] = TInvoiceDetail::findOne(['invoice_id' => $id, 'produk_id' => $modProduk->produk_id]);
                                                            $modDetail[$i]->qty_besar = $container['bundles'];
                                                            $modDetail[$i]->qty_kecil = $container['pcs'];
                                                            $modDetail[$i]->kubikasi = $container['volume'];
                                                            $modDetail[$i]->kubikasi_display = $container['volume_display'];
                                                            $modDetail[$i]->grade = $container['grade'];
                                                            $modDetail[$i]->kubikasi_grouping = $container['volume_display'];
                                                        }
                                                    }catch (\yii\db\Exception $exception) {
                                                        echo $exception->getMessage();
                                                    }
                                                }else {
                                                    $modDetail = TInvoiceDetail::find()->where(['invoice_id' => $id])->all();
                                                }

                                                if (count($modDetail) > 0) {
                                                    foreach ($modDetail as $i => $detail) {
                                                        if ($model->diff_size_diff_price === false && $model->grouping_qty === false) {
                                                            $nama = $detail->keterangan;
                                                            $dimentions = getDimentions([
                                                                'harga_jual' => $detail->harga_jual,
                                                                'invoice_id' => $model->invoice_id,
                                                                'keterangan' => $detail->keterangan,
                                                                'grade'      => $detail->grade
                                                            ]);
                                                            $kubikasi = $detail->kubikasi_grouping;
                                                        }else {
                                                            $nama = $detail->produk->produk_nama . "<br>" . $detail->keterangan;
                                                            $dimentions = $detail->produk->produk_dimensi;
                                                            $kubikasi = $detail->kubikasi_display;
                                                        }

                                                        $subtotal = round($detail->harga_jual * $kubikasi, 2);
                                                        $total += $subtotal;
                                                        $totalbdl += $detail->qty_besar;
                                                        $totalpcs += $detail->qty_kecil;
                                                        $totalm3 += $kubikasi;

                                                        $dataDetail = [
                                                            'no' => $i + 1,
                                                            'nama' => $nama,
                                                            'dimensi' => $dimentions,
                                                            'qty_besar' => DeltaFormatter::formatNumberForUserFloat($detail->qty_besar),
                                                            'qty_kecil' => DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil),
                                                            'kubikasi_display' => DeltaFormatter::formatNumberForUserFloat($kubikasi),
                                                            'harga_jual' => DeltaFormatter::formatNumberForUserFloat($detail->harga_jual),
                                                            'subtotal' => DeltaFormatter::formatNumberForUserFloat($subtotal)
                                                        ];

                                                        $htmlDetail .= renderDetail($dataDetail);
                                                    }
                                                }

                                                $total_potongan = $model->total_potongan ? number_format($model->total_potongan, 2) : 0;
                                                $total_biaya_tambahan = $model->total_biaya_tambahan ? number_format($model->total_biaya_tambahan, 2) : 0;
                                                $grandtotal = $total + $model->total_biaya_tambahan - $model->total_potongan;

                                                $dataFooter = [
                                                    'totalbdl' => DeltaFormatter::formatNumberForUserFloat($totalbdl),
                                                    'totalpcs' => DeltaFormatter::formatNumberForUserFloat($totalpcs),
                                                    'totalm3' => DeltaFormatter::formatNumberForUserFloat($totalm3),
                                                    'total' => DeltaFormatter::formatNumberForUserFloat($total),
                                                    'keterangan_potongan' => $model->keterangan_potongan,
                                                    'total_potongan' => $total_potongan,
                                                    'keterangan_biaya_tambahan' => $model->keterangan_biaya_tambahan,
                                                    'total_biaya_tambahan' => $total_biaya_tambahan,
                                                    'grandtotal' => DeltaFormatter::formatNumberForUserFloat($grandtotal)
                                                ];
                                                $htmlFooter = renderFooter($dataFooter);
                                                ?>

                                                <tbody><?= $htmlDetail ?></tbody>
                                                <tfoot><?= $htmlFooter ?></tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>