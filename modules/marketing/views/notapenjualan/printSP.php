<?php
/* @var $this yii\web\View */
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
<?php if ($model->notaPenjualan->jenis_produk == "Log"){ ?>
    <table style="width: 20cm; margin: 10px;" border="1">
        <tr>
            <td colspan="3" style="min-height: 4cm; max-height: 4cm; padding:8px; border-bottom: solid 1px transparent;">
                <table style="width: 100%;">
                    <tr>
                        <td colspan="2" style="text-align: left; vertical-align: middle; padding: 0px; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
                            <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt=""
                                class="logo-default" style="width: 60px;">
                        </td>
                        <td colspan="8"style="text-align: center; vertical-align: middle; padding: 9px;">
                            <span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
                            <?= 'Kayu Bulat' ?>
                        </td>
                        <td colspan="3" style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; padding-left: 25px; width: 50%;">
                            <table style="line-height: 12px;">
                                <tr style="">
                                    <td><b>Kode OP</b></td>
                                    <td>: &nbsp; <?= $model->spmKo->opKo->kode; ?></td>
                                </tr>
                                <tr style="">
                                    <td style="width:2cm;"><b>Kode SPM</b></td>
                                    <td>: &nbsp; <?= $model->spmKo->kode; ?></td>
                                </tr>
                                <tr style="">
                                    <td><b>Kode Nota</b></td>
                                    <td>: &nbsp; <?= $model->notaPenjualan->kode; ?></td>
                                </tr>
                                <tr style="">
                                    <td style="width:2cm;"><b>Kode SP</b></td>
                                    <td>: &nbsp; <b><?= $kode; ?></b></td>
                                </tr>
                                <tr style="">
                                    <td><b>Tanggal</b></td>
                                    <td>:
                                        &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?> </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="13" style="border-bottom: solid 1px; line-height: 0px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="13" style="line-height : 15px; vertical-align: top; font-size: 1rem;"><b>
                                <i>Kepada Yth,</i> <br>
                                <?php
                                // ada alamat perusahaan yg tidak di isi oleh user
                                if ($model->cust->cust_pr_nama <> '') {
                                    $Customer_nama = $model->cust->cust_pr_nama;
                                    $Customer_alamat = $model->cust->cust_pr_alamat;
                                } else {
                                    $Customer_nama = $model->cust->cust_an_nama;
                                //  $Customer_alamat = $model->cust->cust_an_alamat;
                                }
                                //  echo $Customer_nama." <br>";
                                //  echo $Customer_alamat;
                                ?>
                                &nbsp; &nbsp; <?= $Customer_nama; ?><br>
                                <?= $model->cust_alamat ?: $model->cust->cust_pr_alamat ?: $model->cust->cust_an_alamat ?>
                            </b><br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="13"
                            style="line-height: 17px; vertical-align: top; text-align: justify; font-size: 12px;">
                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Kami kirimkan barang-barang berikut dibawah ini dengan
                            kondisi baik, menggunakan kendaraan Truk Nopol <b><?= $model->kendaraan_nopol; ?></b>
                            dan Supir bernama <b><?= $model->kendaraan_supir ?></b>, menuju alamat bongkar di
                            <b><?= $model->alamat_bongkar ?></b>.
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <!-- ISI KONTEN -->
                    <tr style="background-color: #F1F4F7; height: 0.5cm; border-top: 1px solid black;">
                        <td rowspan="2"
                            style="width: 30%; padding: 7px 5px; border: solid 1px; vertical-align: middle;"><b>
                                <center>Produk<br>(Range Diameter)</center>
                            </b></td>
                        <td rowspan="2"
                            style="width: 2cm; padding: 7px 5px; border: solid 1px; vertical-align: middle;"><b>
                                <center>QR. Code<br>Lapangan<br>Grade<br>Batang</center>
                            </b></td>
                        <td rowspan="2"
                            style="width: 1cm; padding: 7px 5px; border: solid 1px; vertical-align: middle;"><b>
                                <center>P<br>(m)</center>
                            </b></td>
                        <td colspan="5"
                            style="width: 3cm; padding: 7px 5px; border: solid 1px; vertical-align: middle; border-bottom: solid 1px #000;"><b>
                                <center>Diameter</center>
                            </b></td>
                        <td colspan="3"
                            style="width: 2cm; padding: 7px 5px; border: solid 1px; vertical-align: middle; border-bottom: solid 1px #000;"><b>
                                <center>Unsur Cacat (cm)</center>
                            </b></td>
                        <td rowspan="2" style="width: 5%; vertical-align: middle; border: solid 1px;"><b>
                                <center>Volume<br>(M<sup>3</sup>)</center>
                            </b></td>
                        <td rowspan="2" style="width: 1.5cm; vertical-align: middle; border: solid 1px;"><b>
                                <center>Keterangan</center>
                            </b></td>
                    </tr>
                    <tr style="background-color: #F1F4F7; border-bottom: solid 1px #000;">
                        <td style="border-right: solid 1px #000;"><b>
                                <center>Ujung 1</center>
                            </b></td>
                        <td style="border-right: solid 1px #000; "><b>
                                <center>Ujung 2</center>
                            </b></td>
                        <td style="border-right: solid 1px #000; "><b>
                                <center>Pangkal 1</center>
                            </b></td>
                        <td style="border-right: solid 1px #000; "><b>
                                <center>Pangkal 2</center>
                            </b></td>
                        <td style="border-right: solid 1px #000; "><b>
                                <center>Rata</center>
                            </b></td>
                        <td style="border-right: solid 1px #000; "><b>
                                <center>Panjang</center>
                            </b></td>
                        <td style="border-right: solid 1px #000; "><b>
                                <center>Gubal</center>
                            </b></td>
                        <td style="border-right: solid 1px #000; width: 5%;"><b>
                                <center>Growong</center>
                            </b></td>
                    </tr>
                    <?php 
                    $max = 6;
                    if (count($modDetail) > $max) {
                        $max = count($modDetail);
                    }
                    $total_kecil = 0;
                    $total_kubik = 0;

                    for ($i = 0; $i < $max; $i++) {
                        if (count($modDetail) >= ($i + 1)) {
                            $total_kecil += $modDetail[$i]->qty_kecil;
                            $total_kubik += $modDetail[$i]->kubikasi;

                            $modSpmKo = \app\models\TSpmKo::findOne($model->spm_ko_id);
                            $modBrgLog = \app\models\MBrgLog::findOne($modDetail[$i]->produk_id);
                            if ($modDetail[$i]->suratPengantar->jenis_produk == "Log"){
                                // $modSpmLog = \app\models\TSpmLog::findOne(['reff_no'=>$modSpmKo->kode, 'kayu_id'=>$modBrgLog->kayu_id, 'volume'=>$modDetail[$i]->kubikasi]);
                                $modSpmLog = \app\models\TSpmLog::findOne($modDetail[$i]->spm_log_id);
                                $modKayu = \app\models\MKayu::findOne($modBrgLog->kayu_id);
                                $range_akhir = $modBrgLog['range_akhir'] >= 200?' UP': ' - '.$modBrgLog['range_akhir'];
                                $logNama = $modKayu['group_kayu'] .'-'.$modKayu['kayu_nama'].'<br>('. $modBrgLog['range_awal'] . $range_akhir.')';
                                // jika log fsc100 tampilkan nama ilmiah
                                if($modBrgLog && stripos($modBrgLog->log_nama, 'FSC100') !== false){
                                    $logNama = $modKayu['group_kayu'] .'-'.$modKayu['kayu_nama'].'<br><b>FSC</b>100%<br>('. $modBrgLog['range_awal'] .'-' . $modBrgLog['range_akhir'] .')';
                                    $logNama .= "<br><em>" . htmlspecialchars($modKayu->nama_ilmiah) . "</em>";
                                }

                                // cek alias, alias true pakai nama & diameter alias
                                $modAlias = Yii::$app->db->createCommand("
                                                                SELECT t_po_ko_detail.po_ko_id, t_po_ko_detail.alias, t_po_ko_detail.po_ko_detail_id
                                                                FROM t_surat_pengantar_detail
                                                                JOIN t_surat_pengantar ON t_surat_pengantar.surat_pengantar_id = t_surat_pengantar_detail.surat_pengantar_id
                                                                JOIN t_nota_penjualan ON t_nota_penjualan.nota_penjualan_id = t_surat_pengantar.nota_penjualan_id
                                                                JOIN t_nota_penjualan_detail ON t_nota_penjualan_detail.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id
                                                                JOIN t_op_ko ON t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id
                                                                JOIN t_po_ko_detail ON t_po_ko_detail.po_ko_id = t_op_ko.po_ko_id 
                                                                LEFT JOIN m_brg_log ON m_brg_log.log_id = t_nota_penjualan_detail.produk_id  
                                                                WHERE t_surat_pengantar_detail.surat_pengantar_detail_id={$modDetail[$i]->surat_pengantar_detail_id} AND
                                                                {$modDetail[$i]->produk_id} = ANY (string_to_array(t_po_ko_detail.produk_id_alias, ',')::int[])
                                                                GROUP BY t_po_ko_detail.po_ko_id, t_po_ko_detail.alias, t_po_ko_detail.po_ko_detail_id")->queryOne();
                                if(!empty($modAlias['alias']) && $modAlias['alias']){
                                    $modPO = app\models\TPoKoDetail::findOne($modAlias['po_ko_detail_id']);
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
                        <tr>
                            <td style="padding: 2px 5px; border-left: 1px solid black; border-right: solid 1px black; vertical-align: top;">
                                <?php echo $logNama ?>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000; vertical-align: top;">
                                <span style="float: center"><?php echo '<b>'.$modSpmLog['no_barcode'].'</b><br>'.$modSpmLog['no_lap'].'<br>'.$modSpmLog['no_grade'].'<br>'.$modSpmLog['no_btg'] ?></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000;  text-align: center; vertical-align: top;">
                                <span style="float: center"><?php echo $modSpmLog['panjang']  ?></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000;  text-align: center; vertical-align: top;">
                                <span style="float: center"><?php echo $modSpmLog['diameter_ujung1']  ?></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000;  text-align: center; vertical-align: top;">
                                <span style="float: center"><center><?php echo $modSpmLog['diameter_ujung2']  ?></center></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000;  text-align: center; vertical-align: top;">
                                <span style="float: center"><center><?php echo $modSpmLog['diameter_pangkal1']  ?></center></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000;  text-align: center; vertical-align: top;">
                                <span style="float: center"><center><?php echo $modSpmLog['diameter_pangkal2']  ?></center></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000;  text-align: center; vertical-align: top;">
                                <span style="float: center"><?php echo $modSpmLog['diameter_rata']  ?></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000;  text-align: center; vertical-align: top;">
                                <span style="float: center"><?php echo $modSpmLog['cacat_panjang']  ?></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000;  text-align: center; vertical-align: top;">
                                <span style="float: center"><?php echo $modSpmLog['cacat_gb']  ?></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000; text-align: center; vertical-align: top;">
                                <span style="float: center"><?php echo $modSpmLog['cacat_gr']  ?></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000; text-align: right; vertical-align: top;">
                                <span style="float: right"><?php echo number_format($modDetail[$i]->kubikasi, 2)  ?></span>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px #000; vertical-align: top;">
                                <span style="float: left"><?php echo $modDetail[$i]->keterangan  ?></span>
                            </td>
                        </tr>
                    <?php }
                    } ?>
                    <tr>
                        <td style="padding: 2px 5px; border-left: 1px solid black; border-right: solid 1px black;">
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
                        <td style="padding: 2px 5px; border-left: 1px solid black; border-right: solid 1px black;">
                            &nbsp;
                        </td>
                    </tr>
                    <tr style="border-top: solid 1px #000; border-bottom: solid 1px transparent; background-color: #F1F4F7;">
                        <td class="text-align-right" style="padding: 5px; border: 1px solid black;"><b>Total</b>
                            &nbsp;
                        </td>
                        <td style="border: solid 1px black;"></td>
                        <td style="border: solid 1px black;"></td>
                        <td style="border: solid 1px black;"></td>
                        <td style="border: solid 1px black;"></td>
                        <td style="border: solid 1px black;"></td>
                        <td style="border: solid 1px black;"></td>
                        <td style="border: solid 1px black;"></td>
                        <td style="border: solid 1px black;"></td>
                        <td style="border: solid 1px black;"></td>
                        <td style="border: solid 1px black;"></td>
                        <td style="border: solid 1px black; text-align: right; padding: 2px 5px;"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubik); ?></b></td>
                        <td style="border: solid 1px black;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="page-break-inside : avoid">
            <div>
                <td colspan="3" style="padding: 0px;">
                    <table style="width: 100%; border: solid 1px;" id="table-detail">

                    </table>
                </td>
            </div>
        </tr>
        <tr style="border-bottom: solid 1px transparent;">
            <td colspan="2" style=" border-top: solid 1px transparent;">
                <table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;"
                    border="1">
                    <tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
                        <td style="width: 10cm; text-align: left; border-bottom: solid 1px transparent;"></td>
                        <td colspan="2" style="vertical-align: middle; width: 6cm; background-color: #F1F4F7;">Diterima
                            Oleh
                        </td>
                        <td style="vertical-align: middle; width: 4cm; background-color: #F1F4F7;">Dibuat Oleh</td>
                    </tr>
                    <tr>
                        <td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;"></td>
                        <td style="vertical-align: bottom; font-size: 0.8rem; width: 3cm;">Customer</td>
                        <td style="vertical-align: bottom; font-size: 0.8rem; width: 3cm;">Supir</td>
                        <td style="border-right: solid 1px transparent;"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; font-size: 0.9rem; text-align: left; border-top: solid 1px transparent;">
                            <?php
                            echo Yii::t('app', 'Printed By : ') . Yii::$app->user->getIdentity()->userProfile->fullname . "&nbsp;";
                            echo Yii::t('app', 'at : ') . date('d/m/Y H:i:s');
                            ?>
                        </td>
                        <td style="background-color: #F1F4F7;"></td>
                        <td style="background-color: #F1F4F7;"></td>
                        <td style="background-color: #F1F4F7; height: 20px; vertical-align: middle;  border-right: solid 1px transparent;  ">
                            <?php
                            if (!empty($model->created_by)) {
                                echo "<span style='font-size:0.9rem'>" . \app\models\MPegawai::findOne(app\components\Params::DEFAULT_PEGAWAI_ID_RYA)->pegawai_nama . "</span>";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="border-right: 1px solid transparent;">
            <td colspan="2" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top; ">
                <span class="pull-left lampiran-rangkap" style="font-size: 0.8rem;">
                    Lembar Putih : Untuk Customer &nbsp;&nbsp; - &nbsp;&nbsp;
                    Lembar Merah : Untuk Customer &nbsp;&nbsp; - &nbsp;&nbsp;
                    Lembar Kuning (1) : Untuk Acctounting (LPH) &nbsp;&nbsp; - &nbsp;&nbsp;
                    Lembar Kuning (2) : Untuk Acctounting (Faktur)
                </span>
                <span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-09-0</span>
            </td>
        </tr>
    </table>
<?php } else { ?>
    <table style="width: 20cm; margin: 10px;" border="1">
        <tr>
            <td colspan="3" style="min-height: 4cm; max-height: 4cm; padding: 8px; border-bottom: solid 1px transparent;">
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: left; vertical-align: middle; padding: 0px; width: 5.5cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
                            <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt=""
                                class="logo-default" style="width: 60px;">
                        </td>
                        <td colspan="4" style="text-align: center; vertical-align: middle; padding: 10px; ">
                            <span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
                            <?= $model->jenis_produk ?>
                        </td>
                        <td style="width: 5.5cm; vertical-align: top; padding: 10px;">
                            <table style="line-height: 12px;">
                                <tr style="">
                                    <td><b>Kode OP</b></td>
                                    <td>: &nbsp; <?= $model->spmKo->opKo->kode; ?></td>
                                </tr>
                                <tr style="">
                                    <td><b>Kode SPM</b></td>
                                    <td>: &nbsp; <?= $model->spmKo->kode; ?></td>
                                </tr>
                                <tr style="">
                                    <td style="width:2cm;"><b>Kode Nota</b></td>
                                    <td>: &nbsp; <?= $model->notaPenjualan->kode; ?></td>
                                </tr>
                                <tr style="">
                                    <td style="width:2cm;"><b>Kode SP</b></td>
                                    <td>: &nbsp; <b><?= $kode; ?></b></td>
                                </tr>
                                <tr style="">
                                    <td><b>Tanggal</b></td>
                                    <td>:
                                        &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?> </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="border-bottom: solid 1px; line-height: 0px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" style="line-height : 15px; vertical-align: top; font-size: 1rem;"><b>
                                <i>Kepada Yth,</i> <br>
                                <?php
                                //                                                ada alamat perusahaan yg tidak di isi oleh user
                                if ($model->cust->cust_pr_nama <> '') {
                                    $Customer_nama = $model->cust->cust_pr_nama;
                                    $Customer_alamat = $model->cust->cust_pr_alamat;
                                } else {
                                    $Customer_nama = $model->cust->cust_an_nama;
    //                                $Customer_alamat = $model->cust->cust_an_alamat;
                                }
                                //                                                    echo $Customer_nama." <br>";
                                //                                                    echo $Customer_alamat;
                                ?>
                                &nbsp; &nbsp; <?= $Customer_nama; ?><br>
                                <?= $model->cust_alamat ?: $model->cust->cust_pr_alamat ?: $model->cust->cust_an_alamat ?>
                            </b><br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6"
                            style="line-height: 17px; vertical-align: top; text-align: justify; font-size: 12px;">
                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Kami kirimkan barang-barang berikut dibawah ini dengan
                            kondisi baik, menggunakan kendaraan Truk Nopol <b><?= $model->kendaraan_nopol; ?></b>
                            dan Supir bernama <b><?= $model->kendaraan_supir ?></b>, menuju alamat bongkar di
                            <b><?= $model->alamat_bongkar ?></b>.
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <?php // KONTEN CUK ;?>
                    <tr style="background-color: #F1F4F7; height: 0.5cm; border-top: 1px solid black;">
                        <td colspan="2" rowspan="2"
                            style="width: 9cm; padding: 7px 5px; border: solid 1px; vertical-align: middle;"><b>
                                <center>Produk</center>
                            </b></td>
                        <td colspan="3" style="width: 6cm; border-right: solid 1px #000; border-bottom: solid 1px #000;"><b>
                                <center>Qty</center>
                            </b></td>
                        <td rowspan="2" style="width: 5cm; vertical-align: middle; border: solid 1px;"><b>
                                <center>Keterangan</center>
                            </b></td>
                    </tr>
                    <tr style="background-color: #F1F4F7; border-bottom: solid 1px #000;">
                        <?php if ($model->notaPenjualan->jenis_produk == "Limbah") { ?>
                            <td style="width: 1cm; border-right: solid 1px #000;"><b>
                                    <center></center>
                                </b></td>
                            <td style="width: 2.0cm; border-right: solid 1px #000; "><b>
                                    <center>Satuan<br>Beli</center>
                                </b></td>
                            <td style="width: 2.0cm; border-right: solid 1px #000; "><b>
                                    <center>Satuan<br>Angkut</center>
                                </b></td>
                        <?php } else if ($model->notaPenjualan->jenis_produk == "JasaGesek") { ?>
                            <td style="width: 1cm; border-right: solid 1px #000;"><b>
                                    <center>Batang</center>
                                </b></td>
                            <td style="width: 2.5cm; border-right: solid 1px #000;"><b>
                                    <center>-</center>
                                </b></td>
                            <td style="width: 1.5cm; border-right: solid 1px #000;"><b>
                                    <center>M<sup>3</sup></center>
                                </b></td>
                        <?php } else if ($model->notaPenjualan->jenis_produk == "Log") { ?>
                            <td style="width: 1cm; border-right: solid 1px #000;"><b>
                                    <center></center>
                                </b></td>
                            <td style="width: 2.0cm; border-right: solid 1px #000; "><b>
                                    <center>Satuan<br>Beli</center>
                                </b></td>
                            <td style="width: 1cm; border-right: solid 1px #000; "><b>
                                    <center></center>
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
                    </tr>
                    <?php
                    $max = 6;
                    if (count($modDetail) > $max) {
                        $max = count($modDetail);
                    }
                    $total_besar = 0;
                    $total_kecil = 0;
                    $total_kubik = 0;
                    ?>
                    <?php for ($i = 0; $i < $max; $i++) {
                        if (count($modDetail) >= ($i + 1)) {
                            $total_besar += $modDetail[$i]->qty_besar;
                            $total_kecil += $modDetail[$i]->qty_kecil;
                            $total_kubik += $modDetail[$i]->kubikasi;

                            $modRandom = Yii::$app->db->createCommand("
                                        SELECT t_op_ko_random.* FROM t_op_ko_random
                                        JOIN t_op_ko_detail ON t_op_ko_detail.op_ko_detail_id = t_op_ko_random.op_ko_detail_id
                                        JOIN t_nota_penjualan ON t_nota_penjualan.op_ko_id = t_op_ko_detail.op_ko_id
                                        JOIN t_surat_pengantar ON t_surat_pengantar.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id
                                        WHERE surat_pengantar_id = '{$model->surat_pengantar_id}' AND t_op_ko_detail.produk_id = {$modDetail[$i]->produk_id}
                                        ")->queryAll(); ?>

                            <?php if (count($modRandom) > 0) { ?>
                                <tr>
                                    <td colspan="2"
                                        style="line-height: 14px; padding: 2px 5px; border-left: 1px solid black; border-right: 1px solid black;">
                                        <?= $modDetail[$i]->produk->NamaProduk . " <i><b>Random Size: </b></i>"; ?>
                                    </td>
                                    <td style="padding: 2px 5px; border-right: solid 1px #000; text-align: center;">
                                        <?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_besar) ?>
                                    </td>
                                    <td style="padding: 2px 5px; border-right: solid 1px #000;"></td>
                                    <td style="padding: 2px 5px; border-right: solid 1px #000;"></td>
                                    <td style="padding: 2px 5px; border-right: solid 1px #000; "><?= $modDetail[$i]->keterangan ?></td>
                                </tr>
                                <?php foreach ($modRandom as $ii => $random) {
                                    $modDetail[$i]->attributes = $random; ?>
                                    <?php
                                    $dotted = "";
                                    if (($ii != 0)) {
                                        if ($modRandom[($ii - 1)]['nomor_produksi'] != $random['nomor_produksi']) {
                                            $res = $random['nomor_produksi'] . " : ";
                                            $dotted = "border-top: 2px dotted #999";
                                        } else {
                                            $res = "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
                                        }
                                    } else {
                                        $res = $random['nomor_produksi'] . " : ";
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="2"
                                            style="line-height: 15px; font-size: 11px; padding: 2px 5px; border-left: 1px solid black; border-right: 1px solid black; <?= $dotted ?>">
                                            <?php echo "&nbsp; <b>" . $res . "</b> - <span style='font-size:1.1rem;'>" . $random['t'] . " " . $random['t_satuan'] . " X " . $random['l'] . " " . $random['l_satuan'] . " X " . $random['p'] . " " . $random['p_satuan'] . "<span><br>"; ?>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>">
                                            <span style="float: right"></span>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>">
                                            <span style="float: right;">
                                                <?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_kecil) ?> 
                                                <i>(<?= $modDetail[$i]->satuan_kecil ?>)</i>
                                            </span>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>">
                                            <span style="float: right">
                                                <?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->kubikasi) ?>
                                            </span>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; ">

                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else {
                                if ($model->notaPenjualan->jenis_produk == "JasaKD" || $model->notaPenjualan->jenis_produk == "JasaMoulding") {
                                    $modJasa = \app\models\MProdukJasa::findOne($modDetail[$i]->produk_id);
                                    $modSpmDetail = app\models\TSpmKoDetail::find()->where(["spm_ko_id" => $model->notaPenjualan->spm_ko_id, "produk_id" => $modDetail[$i]->produk_id])->one();
                                    $modPaletKD = Yii::$app->db->createCommand("
                                                    SELECT * FROM t_terima_jasa
                                                    WHERE op_ko_id = '{$model->notaPenjualan->op_ko_id}' AND produk_jasa_id = {$modDetail[$i]->produk_id} AND nomor_palet IN(" . $modSpmDetail->keterangan . ")
                                                ")->queryAll();
                                    ?>
                                    <tr>
                                        <td colspan="2"
                                            style="line-height: 14px; font-size: 11px; padding: 2px 5px; border-left: solid 1px; border-right: 1px solid black;">
                                            <?php echo "<b>" . $modJasa->kode . '</b> - ' . $modJasa->nama; ?>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; text-align: center;">
                                            <?= \app\components\DeltaFormatter::formatNumberForUserFloat($modSpmDetail->qty_besar_realisasi) ?>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000;"></td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000;"></td>
                                        <td style="padding: 2px 5px; border-right: solid 1px;"></td>
                                    </tr>
                                    <?php
                                    foreach ($modPaletKD as $iii => $paletKD) {
                                        $dotted = "";
                                        if (($iii != 0)) {
                                            if ($modPaletKD[($iii - 1)]['nomor_palet'] != $paletKD['nomor_palet']) {
                                                $res = $paletKD['nomor_palet'] . " : <br> &nbsp; &nbsp;";
                                                $dotted = "border-top: 2px dotted #999";
                                            } else {
                                                $res = "&nbsp;";
                                            }
                                        } else {
                                            $res = $paletKD['nomor_palet'] . " : <br> &nbsp; &nbsp;";
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="2"
                                                style="line-height: 14px; font-size: 11px;  border-left: solid 1px; border-right: 1px solid black; <?= $dotted ?>">
                                                <?php echo "&nbsp; <b>" . $res . "</b> - <span style='font-size:1.1rem;'>" . $paletKD['t'] . " " . $paletKD['t_satuan'] . " X " . $paletKD['l'] . " " . $paletKD['l_satuan'] . " X " . $paletKD['p'] . " " . $paletKD['p_satuan'] . "<span>"; ?>
                                            </td>
                                            <td style="padding: 2px 5px; border-right: solid 1px #000; vertical-align: bottom; <?= $dotted ?>">
                                                <span style="float: right"></span>
                                            </td>
                                            <td style="padding: 2px 5px; border-right: solid 1px #000; vertical-align: bottom; <?= $dotted ?>">
                                            <span style="float: right;">
                                                <?= \app\components\DeltaFormatter::formatNumberForUserFloat($paletKD['qty_kecil']) ?> 
                                                <i>(<?= $paletKD['satuan_kecil'] ?>)</i>
                                            </span>
                                            </td>
                                            <td style="padding: 2px 5px; border-right: solid 1px #000; vertical-align: bottom; <?= $dotted ?>">
                                            <span style="float: right">
                                                <?= number_format($paletKD['kubikasi'], 4) ?>
                                            </span>
                                            </td>
                                            <td style="padding: 2px 5px; border-right: solid 1px; "></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="2"
                                            style="padding: 2px 5px; border-left: 1px solid black; border-right: solid 1px black;">
                                            <?php
                                            if ($model->notaPenjualan->opKo->jenis_produk == "Limbah") {
                                                echo $modDetail[$i]->limbah->limbah_kode . " - (" . $modDetail[$i]->limbah->limbah_produk_jenis . ") " . $modDetail[$i]->limbah->limbah_nama;
                                            } else if ($model->notaPenjualan->opKo->jenis_produk == "JasaKD" || $model->notaPenjualan->opKo->jenis_produk == "JasaGesek" || $model->notaPenjualan->opKo->jenis_produk == "JasaMoulding") {
                                                echo "<b>" . $modDetail[$i]->produkJasa->kode . "</b> - " . $modDetail[$i]->produkJasa->nama;
                                            } else if ($model->notaPenjualan->opKo->jenis_produk == "Log") {
                                                echo $modDetail[$i]->log->log_kode . " - (" . $modDetail[$i]->log->log_kelompok . ") " . $modDetail[$i]->log->log_nama;
                                            } else if ($model->notaPenjualan->opKo->jenis_produk == "Veneer") {
                                                // jika vener dengan grade fsc100 maka tampilkan nama ilimiah sesuai dengan jenis log
                                                $modProduk = \app\models\MBrgProduk::findOne(['produk_id' =>$modDetail[$i]->produk_id]);
                                                if ($modProduk && stripos($modProduk->grade, 'FSC 100') !== false){
                                                    $modJeniskayu = \app\models\MJenisKayu::findOne(['jenis_produk' => $model->notaPenjualan->opKo->jenis_produk, 'nama' => $modProduk->jenis_kayu]);
                                                    // Menebalkan teks FSC100 di nama produk
                                                    $produkNama = str_ireplace('FSC100', '<strong>FSC100</strong>', htmlspecialchars($modDetail[$i]->produk->produk_nama));
                                                    echo $produkNama ;
                                                    echo "<br><em>" . htmlspecialchars($modJeniskayu['othername']) . "</em>";
                                                    $nomorSertifikatFsc = app\components\Params::NOMOR_SERTIFIKAT_FSC;
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
                                            <span style="float: right"><?= ($model->notaPenjualan->opKo->jenis_produk == "Limbah" || $model->notaPenjualan->opKo->jenis_produk == "Log") ? "" : \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_besar); ?></span>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000;">
                                        <span style="float: right;">
                                            <?= ($model->jenis_produk == "JasaGesek") ? "-" : app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_kecil) . "<i> (" . (!empty($modDetail[$i]->satuan_kecil) ? $modDetail[$i]->satuan_kecil : "Pcs") . ")</i>"; ?>
                                        </span>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000;">
                                        <span style="float: right">
                                            <?= ($model->notaPenjualan->opKo->jenis_produk == "Limbah" || $model->notaPenjualan->opKo->jenis_produk == "Log") ? (($modDetail[$i]->satuan_kecil == "Rit") ? $modDetail[$i]->satuan_besar : "") : number_format($modDetail[$i]->kubikasi, 4); ?>
                                        </span>
                                        </td>
                                        <td style="padding: 2px 5px;border-left: 1px solid black; border-right: solid 1px black;">
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="2"
                                    style="padding: 2px 5px; border-left: 1px solid black; border-right: solid 1px black;">
                                    &nbsp;
                                </td>
                                <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                                <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                                <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
                                <td style="padding: 2px 5px; border-left: 1px solid black; border-right: solid 1px black;">
                                    &nbsp;
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    <tr style="border-top: solid 1px #000; border-bottom: solid 1px transparent; background-color: #F1F4F7;">
                        <td colspan="2" class="text-align-right" style="padding: 5px; border: 1px solid black;"><b>Total</b>
                            &nbsp;
                        </td>
                        <td class="text-align-center" style="padding: 5px; border: solid 1px #000;"><b>
                                <?php echo ($model->notaPenjualan->opKo->jenis_produk == "Limbah" || $model->notaPenjualan->opKo->jenis_produk == "Log") ? "" : \app\components\DeltaFormatter::formatNumberForUserFloat($total_besar) ?>
                            </b></td>
                        <td class="text-align-right" style="padding: 5px; border: solid 1px #000;"><b>
                                <?= ($model->jenis_produk == "JasaGesek") ? "-" : app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil) . "<i> (" . (!empty($modDetail[0]->satuan_kecil) ? $modDetail[0]->satuan_kecil : "Pcs") . ")</i>"; ?>
                            </b></td>
                        <td class="text-align-right" style="padding: 5px; border: solid 1px #000;"><b>
                                <?php echo ($model->notaPenjualan->opKo->jenis_produk == "Limbah" || $model->notaPenjualan->opKo->jenis_produk == "Log") ? "" : \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubik); ?>
                            </b></td>
                        <td style="border: solid 1px black;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="page-break-inside : avoid">
            <div>
                <td colspan="3" style="padding: 0px;">
                    <table style="width: 100%; border: solid 1px;" id="table-detail">

                    </table>
                </td>
            </div>
        </tr>
        <tr style="border-bottom: solid 1px transparent;">
            <td colspan="2" style=" border-top: solid 1px transparent;">
                <table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;"
                    border="1">
                    <tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
                        <td style="width: 10cm; text-align: left; border-bottom: solid 1px transparent;"></td>
                        <td colspan="2" style="vertical-align: middle; width: 6cm; background-color: #F1F4F7;">Diterima
                            Oleh
                        </td>
                        <td style="vertical-align: middle; width: 4cm; background-color: #F1F4F7;">Dibuat Oleh</td>
                    </tr>
                    <tr>
                        <td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;"></td>
                        <td style="vertical-align: bottom; font-size: 0.8rem; width: 3cm;">Customer</td>
                        <td style="vertical-align: bottom; font-size: 0.8rem; width: 3cm;">Supir</td>
                        <td style="border-right: solid 1px transparent;"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; font-size: 0.9rem; text-align: left; border-top: solid 1px transparent;">
                            <?php
                            echo Yii::t('app', 'Printed By : ') . Yii::$app->user->getIdentity()->userProfile->fullname . "&nbsp;";
                            echo Yii::t('app', 'at : ') . date('d/m/Y H:i:s');
                            ?>
                        </td>
                        <td style="background-color: #F1F4F7;"></td>
                        <td style="background-color: #F1F4F7;"></td>
                        <td style="background-color: #F1F4F7; height: 20px; vertical-align: middle;  border-right: solid 1px transparent;  ">
                            <?php
                            if (!empty($model->created_by)) {
                                echo "<span style='font-size:0.9rem'>" . \app\models\MPegawai::findOne(app\components\Params::DEFAULT_PEGAWAI_ID_RYA)->pegawai_nama . "</span>";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="border-right: 1px solid transparent;">
            <td colspan="2" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top; ">
                <span class="pull-left lampiran-rangkap" style="font-size: 0.8rem;">
                    Lembar Putih : Untuk Customer &nbsp;&nbsp; - &nbsp;&nbsp;
                    Lembar Merah : Untuk Customer &nbsp;&nbsp; - &nbsp;&nbsp;
                    Lembar Kuning (1) : Untuk Acctounting (LPH) &nbsp;&nbsp; - &nbsp;&nbsp;
                    Lembar Kuning (2) : Untuk Acctounting (Faktur)
                </span>
                <span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-09-0</span>
            </td>
        </tr>
    </table>
<?php } ?>