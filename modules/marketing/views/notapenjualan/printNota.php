<?php
/* @var $this yii\web\View */

use app\models\TPoKoDetail;

$this->title = 'Print ' . $paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$kode = $model->kode;
if ($_GET['caraprint'] == "EXCEL") {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . ' - ' . date("d/m/Y") . '.xls"');
    header('Cache-Control: max-age=0');
    $header = "";
}
?>
<style>
    table {
        font-size: 1.2rem;
    }
    table#table-detail {
        font-size: 1.1rem;
    }
    table#table-detail tr td {
        vertical-align: top;
    }
</style>
<table style="width: 20cm; margin: 10px; height: 10cm;" border="1">
    <tr>
        <td colspan="3" style="padding: 5px;">
            <table style="width: 100%; " border="0">
                <tr style="">
                    <td style="text-align: left; vertical-align: middle; padding: 0px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
                        <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt=""
                             class="logo-default" style="width: 80px;">
                    </td>
                    <td style="text-align: center; vertical-align: middle; padding: 10px; line-height: 1.3;">
                        <span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
                        <?php 
                            if($model->jenis_produk == 'Log'){
                                echo "Kayu Bulat";
                            } else {
                                echo $model->jenis_produk;
                            }
                        ?>
                    </td>
                    <td style="width: 5.5cm; height: 1cm; vertical-align: top; padding: 10px;">
                        <table>
                            <tr style="line-height: 1.1">
                                <td><b>Kode OP</b></td>
                                <td>: &nbsp; <?= $model->spmKo->opKo->kode; ?></td>
                            </tr>
                            <tr style="line-height: 1.1">
                                <td><b>Kode SPM</b></td>
                                <td>: &nbsp; <?= $model->spmKo->kode; ?></td>
                            </tr>
                            <tr style="line-height: 1.1">
                                <td style="width:2cm;"><b>Kode Nota</b></td>
                                <td>: &nbsp; <b><?= $kode; ?></b></td>
                            </tr>
                            <?php
                            $modNota = app\models\TSuratPengantar::findOne(['nota_penjualan_id' => $model->nota_penjualan_id]);
                            ?>
                            <tr style="line-height: 1.1">
                                <td style="width:2cm;"><b>Kode SP</b></td>
                                <td>: &nbsp; <?= $modNota->kode; ?></td>
                            </tr>
                            <tr style="line-height: 1.1">
                                <td><b>Tanggal</b></td>
                                <td>:
                                    &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?> </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="padding: 8px; background-color: #F1F4F7;">
            <table style="width: 100%">
                <tr>
                    <td style="width: 60%; vertical-align: top; padding-left: 10px;">
                        <table>
                            <tr>
                                <td style="width: 3cm; vertical-align: top;"><b>Customer</b></td>
                                <td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
                                <td style="width: 6cm; vertical-align: top;">
                                    <?php
                                    //                                                                        ada alamat perusahaan yg tidak di isi oleh user
                                    if ($model->cust->cust_pr_nama <> '') {
                                        $Customer_nama = $model->cust->cust_pr_nama;
//                                                                                $Customer_alamat = $model->cust->cust_pr_alamat;
                                    } else {
                                        $Customer_nama = $model->cust->cust_an_nama;
//                                                                                $Customer_alamat = $model->cust->cust_an_alamat; 
                                    }
                                    echo $Customer_nama . " <br>";
                                    echo $model->cust_alamat ?: $model->cust->cust_pr_alamat ?: $model->cust->cust_an_alamat;
                                    //										echo $model->cust->cust_an_nama." <br>";
                                    //										echo $model->cust->cust_an_alamat;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;"><b>Alamat Bongkar</b></td>
                                <td style="vertical-align: top;"><b>:</b></td>
                                <td style="vertical-align: top;"><?= $model->alamat_bongkar ?></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 40%; vertical-align: top; padding-left: 10px;">
                        <table>
                            <tr>
                                <td style="width: 4.5cm; vertical-align: top;"><b>Nopol Kendaraaan</b></td>
                                <td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
                                <td style="width: 6cm; vertical-align: top;"><?= $model->kendaraan_nopol ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;"><b>Nama Supir</b></td>
                                <td style="vertical-align: top;"><b>:</b></td>
                                <td style="vertical-align: top;"><?= $model->kendaraan_supir ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="padding: 0px;">
            <table style="width: 100%" id="table-detail">
                <tr style="height: 0.5cm; border-bottom: solid 1px #000;">
                    <?php if($model->jenis_produk == 'Log'){ ?>
                        <td rowspan="2" style="width: 4cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b>
                                <center>Produk<br>(Range Diameter)</center>
                            </b></td>
                        <td rowspan="2" style="width: 2cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b>
                                <center>QR. Code<br>Lapangan<br>Grade<br>Batang</center>
                            </b></td>
                        <td rowspan="2" style="width: 1cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b>
                                <center>Panjang<br>(m)</center>
                            </b></td>
                        <td colspan="5" style="width: 4cm; border-right: solid 1px #000;"><b>
                                <center>Diameter (cm)</center>
                            </b></td>
                        <td colspan="3" style="width: 3cm; border-right: solid 1px #000;"><b>
                                <center>Unsur Cacat (cm)</center>
                            </b></td>
                        <td rowspan="2" style="width: 1cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b>
                                <center>M<sup>3</sup></center>
                            </b></td>
                        <td rowspan="2" style="width: 2.5cm; border-right: solid 1px #000; vertical-align: middle; text-align: right;"><b>
                                <center> Harga Satuan</center>
                            </b></td>
                        <td rowspan="2" style="width: 2.5cm; vertical-align: middle;"><b>
                                <center>Subtotal</center>
                            </b></td>
                    <?php } else { ?>
                        <td rowspan="2"
                            style="width: 7cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b>
                                <center>Produk</center>
                            </b></td>
                        <td colspan="3" style="width: 5cm; border-right: solid 1px #000;"><b>
                                <center>Qty Order</center>
                            </b></td>
                        <td rowspan="2"
                            style="width: 3cm; border-right: solid 1px #000; vertical-align: middle; text-align: right;"><b>
                                <center> Harga Satuan</center>
                            </b></td>
                        <td rowspan="2" style="width: 3cm; vertical-align: middle;"><b>
                                <center>Subtotal</center>
                            </b></td>
                    <?php } ?>
                </tr>
                <tr style="border-bottom: solid 1px #000;">
                    <?php if ($model->jenis_produk == "Log") { ?>
                        <td style="width: 1cm; border-right: solid 1px #000; vertical-align: middle"><b>
                                <center>Ujung<br>1</center>
                            </b></td>
                        <td style="width: 1cm; border-right: solid 1px #000; vertical-align: middle"><b>
                                <center>Ujung<br>2</center>
                            </b></td>
                        <td style="width: 1cm; border-right: solid 1px #000; vertical-align: middle"><b>
                                <center>Pangkal<br>1</center>
                            </b></td>
                        <td style="width: 1cm; border-right: solid 1px #000; vertical-align: middle"><b>
                                <center>Pangkal<br>2</center>
                            </b></td>
                        <td style="width: 1cm; border-right: solid 1px #000; vertical-align: middle"><b>
                                <center>Rata</center>
                            </b></td>
                        <td style="width: 1cm; border-right: solid 1px #000; vertical-align: middle"><b>
                                <center>P</center>
                            </b></td>
                        <td style="width: 1cm; border-right: solid 1px #000; vertical-align: middle"><b>
                                <center>GB</center>
                            </b></td>
                        <td style="width: 1cm; border-right: solid 1px #000; vertical-align: middle"><b>
                                <center>GR</center>
                            </b></td>
                    <?php } else { ?>
                        <?php if ($model->jenis_produk == "Limbah") { ?>
                            <td style="width: 1cm; border-right: solid 1px #000;"><b>
                                <center></center>
                                </b></td>
                            <td style="width: 2.0cm; border-right: solid 1px #000; line-height: 1"><b>
                                    <center>Satuan<br>Beli</center>
                                </b></td>
                            <td style="width: 2.0cm; border-right: solid 1px #000; line-height: 1"><b>
                                    <center>Satuan<br>Angkut</center>
                                </b></td>
                        <?php } else if ($model->jenis_produk == "JasaGesek") { ?>
                            <td style="width: 1cm; border-right: solid 1px #000;"><b>
                                    <center>Batang</center>
                                </b></td>
                            <td style="width: 2.5cm; border-right: solid 1px #000;"><b>
                                    <center>-</center>
                                </b></td>
                            <td style="width: 1.5cm; border-right: solid 1px #000;"><b>
                                    <center>M<sup>3</sup></center>
                                </b></td>
                        <?php } else { ?>
                            <td style="width: 1cm; border-right: solid 1px #000;"><b>
                                    <center>Palet</center>
                                </b></td>
                            <td style="width: 2.5cm; border-right: solid 1px #000;"><b>
                                    <center>Satuan Kecil</center>
                                </b></td>
                            <td style="width: 1.5cm; border-right: solid 1px #000;"><b>
                                    <center>M<sup>3</sup></center>
                                </b></td>
                        <?php } ?>
                    <?php } ?>
                </tr>
                <?php
                $max = 4;
                if (count($modDetail) > $max) {
                    $max = count($modDetail);
                }
                $total_besar = 0;
                $total_kecil = 0;
                $total_kubik = 0;
                $subtotal = 0;
                ?>
                <?php for ($i = 0; $i < $max; $i++) {
                    if (count($modDetail) >= ($i + 1)) {
                        $total_besar += $modDetail[$i]->qty_besar;
                        $total_kecil += $modDetail[$i]->qty_kecil;
                        $total_kubik += number_format($modDetail[$i]->kubikasi, 4);
                        if ($modDetail[$i]->notaPenjualan->jenis_produk == "Plywood" || $modDetail[$i]->notaPenjualan->jenis_produk == "Lamineboard" || $modDetail[$i]->notaPenjualan->jenis_produk == "Platform" || $modDetail[$i]->notaPenjualan->jenis_produk == "Limbah" || $modDetail[$i]->notaPenjualan->jenis_produk == "FingerJointLamineBoard" || $modDetail[$i]->notaPenjualan->jenis_produk == "FingerJointStick" || $modDetail[$i]->notaPenjualan->jenis_produk == "Flooring") {
                            $subtotal = $modDetail[$i]->harga_jual * $modDetail[$i]->qty_kecil;
                        } else {
                            $subtotal = $modDetail[$i]->harga_jual * number_format($modDetail[$i]->kubikasi, 4);
                        }
                        $modSpmKo = \app\models\TSpmKo::findOne($model->spm_ko_id);
                        $modBrgLog = \app\models\MBrgLog::findOne($modDetail[$i]->produk_id);
                        if ($modDetail[$i]->notaPenjualan->jenis_produk == "Log"){
                            $modSpmLog = \app\models\TSpmLog::findOne($modDetail[$i]->spm_log_id);
                            $modKayu = \app\models\MKayu::findOne($modBrgLog->kayu_id);
                            $range_akhir = $modBrgLog['range_akhir'] >= 200?' UP': ' - '.$modBrgLog['range_akhir'];
                            $logNama = $modKayu['group_kayu'] .'-'.$modKayu['kayu_nama'].'<br>('. $modBrgLog['range_awal'] .$range_akhir.')';
                            // jika log fsc100 tampilkan nama ilmiah
							if($modBrgLog && stripos($modBrgLog->log_nama, 'FSC100') !== false){
                                $logNama = $modKayu['group_kayu'] .'-'.$modKayu['kayu_nama'].'<br><b>FSC</b>100%<br>('. $modBrgLog['range_awal'] .'-' . $modBrgLog['range_akhir'] .')';
								$logNama .= "<br><em>" . htmlspecialchars($modKayu->nama_ilmiah) . "</em>";
							}
                            // cek alias, alias true pakai nama & diameter alias
                            $modAlias = Yii::$app->db->createCommand("
                                                            SELECT t_po_ko_detail.po_ko_id, t_po_ko_detail.alias, t_po_ko_detail.po_ko_detail_id
                                                            FROM t_nota_penjualan_detail
                                                            JOIN t_nota_penjualan ON t_nota_penjualan.nota_penjualan_id = t_nota_penjualan_detail.nota_penjualan_id
                                                            JOIN t_op_ko ON t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id
                                                            JOIN t_po_ko_detail ON t_po_ko_detail.po_ko_id = t_op_ko.po_ko_id 
                                                            WHERE t_nota_penjualan_detail.nota_penjualan_detail_id={$modDetail[$i]->nota_penjualan_detail_id} AND
                                                            {$modDetail[$i]->produk_id} = ANY (string_to_array(t_po_ko_detail.produk_id_alias, ',')::int[])
                                                            GROUP BY t_po_ko_detail.po_ko_id, t_po_ko_detail.alias, t_po_ko_detail.po_ko_detail_id")->queryOne();
                            if(!empty($modAlias['alias']) && $modAlias['alias']){
                                $modPO = TPoKoDetail::findOne($modAlias['po_ko_detail_id']);
                                $diameter = explode('-', $modPO->diameter_alias);
                                $range_akhir = $diameter[1];
                                if($range_akhir == 200){
                                    $range_diameter = $diameter[0] .' UP';
                                } else {
                                    $range_diameter = $modPO->diameter_alias;
                                }
                                $logNama = $modPO->produk_alias . '<br>(' . $range_diameter .')';
                            }
                        }
                        ?>
                        <?php if($model->opKo->jenis_produk == "Log"){ ?>
                            <tr>
                                <td style="padding: 2px 5px; border-right: 1px solid black;">
                                    <?php echo $logNama?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: left;">
                                    <?php echo '<b>'.$modSpmLog['no_barcode'].'</b><br>'.$modSpmLog['no_lap'].'<br>'.$modSpmLog['no_grade'].'<br>'.$modSpmLog['no_btg'];?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;">
                                    <?php echo $modSpmLog['panjang'];?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;">
                                    <?php echo $modSpmLog['diameter_ujung1'];?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;">
                                    <?php echo $modSpmLog['diameter_ujung2'];?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;">
                                    <?php echo $modSpmLog['diameter_pangkal1'];?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;">
                                    <?php echo $modSpmLog['diameter_pangkal2'];?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;">
                                    <?php echo $modSpmLog['diameter_rata'];?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;">
                                    <?php echo $modSpmLog['cacat_panjang'];?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;">
                                    <?php echo $modSpmLog['cacat_gb'];?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;">
                                    <?php echo $modSpmLog['cacat_gr'];?>
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;">
                                    <?php echo number_format($modDetail[$i]->kubikasi, 2);?>
                                </td>
                                <td style="padding: 2px 5px; border-right: solid 1px #000;">
                                    <span style="float: left">Rp.</span>
                                    <span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->harga_jual) ?></span>
                                </td>
                                <td style="padding: 2px 5px; ">
                                    <span style="float: left">Rp.</span>
                                    <span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat(round($subtotal, 0)) ?></span>
                                </td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td style="padding: 2px 5px; border-right: 1px solid black;">
                                    <?php
                                    $nomorSertifikatFsc = "";
                                    if ($model->opKo->jenis_produk == "Limbah") {
                                        echo $modDetail[$i]->limbah->limbah_kode . " - (" . $modDetail[$i]->limbah->limbah_produk_jenis . ") " . $modDetail[$i]->limbah->limbah_nama;
                                    } else if ($model->opKo->jenis_produk == "JasaKD" || $model->opKo->jenis_produk == "JasaGesek" || $model->opKo->jenis_produk == "JasaMoulding") {
                                        echo "<b>" . $modDetail[$i]->produkJasa->kode . "</b> - " . $modDetail[$i]->produkJasa->nama;
                                    } else if ($model->opKo->jenis_produk == "Log"){
                                        echo $modDetail[$i]->log->log_kode . " - (" . $modDetail[$i]->log->log_kelompok . ") " . $modDetail[$i]->log->log_nama;
                                    } else if ($model->opKo->jenis_produk == "Veneer"){
                                        // jika vener dengan grade fsc100 maka tampilkan nama ilimiah sesuai dengan jenis log
                                        $modProduk = \app\models\MBrgProduk::findOne(['produk_id' =>$modDetail[$i]->produk_id]);
                                        if ($modProduk && stripos($modProduk->grade, 'FSC 100') !== false){
                                            $modJeniskayu = \app\models\MJenisKayu::findOne(['jenis_produk' => $model->opKo->jenis_produk, 'nama' => $modProduk->jenis_kayu]);
                                            // Menebalkan teks FSC100 di nama produk
                                            $produkNama = str_ireplace('FSC100', '<strong>FSC100</strong>', htmlspecialchars($modDetail[$i]->produk->produk_nama));
                                            echo $produkNama ;
                                            echo "<br><em>" . htmlspecialchars($modJeniskayu['othername']) . "</em>";
                                            $nomorSertifikatFsc =  "Certificate Code : ".app\components\Params::NOMOR_SERTIFIKAT_FSC;
                                        }else{
                                            echo $modDetail[$i]->produk->produk_nama;
                                            $nomorSertifikatFsc = "";
                                        }
                                    } else {
                                        echo $modDetail[$i]->produk->produk_nama;
                                    }
                                    ?>
                                </td>
                                <td style="padding: 2px 5px; border-right: solid 1px #000;">
                                    <span style="float: right"><?= ($model->opKo->jenis_produk == "Limbah" || $model->opKo->jenis_produk == "Log") ? "" : \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_besar); ?></span>
                                </td>
                                <td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <span style="float: right;">
                                    <?= ($model->jenis_produk == "JasaGesek") ? "-" : app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_kecil) . " <i>(" . (!empty($modDetail[$i]->satuan_kecil) ? $modDetail[$i]->satuan_kecil : "Pcs") . ")</i>"; ?>
                                </span>
                                </td>
                                <td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <span style="float: right">
                                    <?php echo ($model->opKo->jenis_produk == "Limbah") ? (($modDetail[$i]->satuan_kecil == "Rit") ? $modDetail[$i]->satuan_besar : "") : number_format($modDetail[$i]->kubikasi, 4); ?>
                                </span>
                                </td>
                                <td style="padding: 2px 5px; border-right: solid 1px #000;">
                                    <span style="float: left">Rp.</span>
                                    <span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->harga_jual) ?></span>
                                </td>
                                <td style="padding: 2px 5px; ">
                                    <span style="float: left">Rp.</span>
                                    <span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat(round($subtotal, 0)) ?></span>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                        <?php if($model->opKo->jenis_produk == "Log"){ ?>
                            <td style="padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">
                                &nbsp;
                            </td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                        <?php }else { ?>
                            <td style="padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">
                                &nbsp;
                            </td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                            <td style="padding: 2px 5px;">&nbsp;</td>
                        <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } ?>
                <?php if ($model->total_ppn != 0 || $model->total_potongan != 0) { ?>
                    <tr style="border-top: solid 1px #000; background-color: #F1F4F7;">
                        <?php if ($model->opKo->jenis_produk == "Log"){ ?>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total
                                    Qty</b> &nbsp;
                            </td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
                                    <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil) ?> <i>(<?= $modDetail[0]->satuan_kecil ?>
                                        )</i>
                                </b></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><center>
                                    <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubik) ?>
                                </center></b></td>
                            <td style="width: 3cm; padding: 5px; border-right: solid 1px #000;" class="text-align-right"><b>Total
                                    Harga</b> &nbsp;
                            </td>
                            <td class="text-align-right" style="padding: 5px;"><b>
                                    <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_harga) ?>
                                </b></td>
                        <?php } else { ?>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total
                                    Qty</b> &nbsp;
                            </td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
                                    <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_besar) ?>
                                </b></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
                                    <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil) ?> <i>(<?= $modDetail[0]->satuan_kecil ?>
                                        )</i>
                                </b></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
                                    <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubik) ?>
                                </b></td>
                            <td style="width: 3cm; padding: 5px; border-right: solid 1px #000;" class="text-align-right"><b>Total
                                    Harga</b> &nbsp;
                            </td>
                            <td class="text-align-right" style="padding: 5px;"><b>
                                    <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_harga) ?>
                                </b></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                <?php if ($model->total_ppn != 0) { ?>
                    <tr style="border-top: solid 1px #000; background-color: #F1F4F7;">
                        <td colspan="4" class="text-align-right"
                            style="padding: 5px; border-right: solid 1px #000; background-color: #fff;"></td>
                        <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Ppn 10%</b>
                            &nbsp;
                        </td>
                        <td class="text-align-right" style="padding: 5px;"><b>
                                <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_ppn) ?>
                            </b></td>
                    </tr>
                <?php } ?>
                <?php if ($model->total_potongan != 0) { ?>
                    <tr style="border-top: solid 1px #000; background-color: #F1F4F7;">
                        <?php if ($model->jenis_produk == "Log"){ ?>
                            <td colspan="12" class="text-align-right"
                                style="padding: 5px; border-right: solid 1px #000; background-color: #fff; "></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Potongan</b>
                                &nbsp;
                            </td>
                        <?php } else { ?>
                            <td colspan="4" class="text-align-right"
                                style="padding: 5px; border-right: solid 1px #000; background-color: #fff; "></td>
                            <td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Potongan</b>
                                &nbsp;
                            </td>
                            <?php } ?>
                            <td class="text-align-right" style="padding: 5px;"><b>
                                    <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_potongan) ?>
                                </b></td>
                        
                    </tr>
                <?php } ?>
                <tr style="border-top: solid 1px #000; border-bottom: solid 1px transparent; background-color: #F1F4F7;">
                <?php if ($model->jenis_produk == "Log"){ 
                        $nomorSertifikatFsc = ''; // Default kosong
                        $modOp = \app\models\TOpKo::findOne(['op_ko_id' => $model->op_ko_id]);
                        if ($modOp !== null) {
                            // Ambil semua baris dengan po_ko_id yang sama
                            $listPo = \app\models\TPoKoDetail::find()->where(['po_ko_id' => $modOp->po_ko_id])->all();
                            // Cek apakah salah satunya punya fsc = true
                            foreach ($listPo as $po) {
                                if ($po->fsc == true) {
                                    $nomorSertifikatFsc = "Certificate Code : " . \app\components\Params::NOMOR_SERTIFIKAT_FSC;
                                    break; // Tidak perlu lanjut, sudah ketemu
                                }
                            }
                        }
                ?>
                    <td style="vertical-align: middle;"> <?= $nomorSertifikatFsc; ?></td>
                    <td colspan="12" class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total
                            Bayar</b> &nbsp;
                    </td>
                <?php } else { ?>
                    <td style="vertical-align: middle;"> <?= $nomorSertifikatFsc; ?></td>
                    <td colspan="4" class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total
                            Bayar</b> &nbsp;
                    </td>
                <?php } ?>
                    <td class="text-align-right" style="padding: 5px;"><b>
                            <?php //echo \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_bayar - $model->total_potongan) ?>
                            <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_bayar) ?>
                        </b></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="border-bottom: solid 1px transparent;">
        <td colspan="3" style=" border-top: solid 1px transparent;">
            <table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;"
                   border="1">
                <tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
                    <td style="width: 16cm; text-align: left; border-bottom: solid 1px transparent;">
                        <b>Terbilang :</b>
                    </td>
                    <td style="vertical-align: middle; width: 4cm; background-color: #F1F4F7;">Dibuat Oleh</td>
                </tr>
                <tr>
                    <td style="font-size:1.4rem; text-align: left; vertical-align: top;">
                        <b><i><?= app\components\DeltaFormatter::formatNumberTerbilang($model->total_bayar); ?></i></b>
                    </td>
                    <td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;"></td>
                </tr>
                <tr>
                    <td style="vertical-align: bottom; font-size: 0.9rem; text-align: left; border-top: solid 1px transparent;">
                        <?php
                        echo Yii::t('app', 'Printed By : ') . Yii::$app->user->getIdentity()->userProfile->fullname . "&nbsp;";
                        echo Yii::t('app', 'at : ') . date('d/m/Y H:i:s');
                        ?>
                    </td>
                    <td style="background-color: #F1F4F7; height: 20px; vertical-align: middle;  border-right: solid 1px transparent;  ">
                        <?php
                        if (!empty($model->created_by)) {
                            if (($model->tanggal) <= '2020-01-21') {
                                echo "<span style='font-size:0.9rem'>" . \app\models\MPegawai::findOne(app\components\Params::DEFAULT_PEGAWAI_ID_FITRIYANAH)->pegawai_nama . "</span>";
                            } elseif (($model->tanggal) <= '2020-04-01') {
                                echo "<span style='font-size:0.9rem'>" . \app\models\MPegawai::findOne(app\components\Params::DEFAULT_PEGAWAI_ID_LINGGA)->pegawai_nama . "</span>";
                            } else {
                                echo "<span style='font-size:0.9rem'>" . \app\models\MPegawai::findOne(app\components\Params::DEFAULT_PEGAWAI_ID_RYA)->pegawai_nama . "</span>";
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
			<span class="pull-left lampiran-rangkap" style="font-size: 0.8rem;">
				Lembar Putih : Untuk Customer &nbsp;&nbsp; - &nbsp;&nbsp;
				Lembar Kuning (1) : Untuk Accounting (LPH) &nbsp;&nbsp; - &nbsp;&nbsp;
				Lembar Kuning (2) : Untuk Accounting (Faktur)
			</span>
            <span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-10-0</span>
        </td>
    </tr>
</table>