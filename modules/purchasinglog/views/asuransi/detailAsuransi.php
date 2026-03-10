<?php
app\assets\DatatableAsset::register($this);
// $kode = $model->kode;
$tablewidth = "20";
$modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
$blankspace = 2;
?>
<style>
    table {
        font-size: 1.1rem;
    }

    table#table-detail {
        font-size: 1.1rem;
    }

    table#table-detail tr td {
        vertical-align: top;
    }
    #detail th, #detail td {
      border: 1px solid #a8a3a3;
      padding: 2px;
    }
</style>
<div class="modal fade" id="modal-detailpo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title">DETAIL ASURANSI</h4>
            </div>
            <div class="modal-body">
                <table style="width: <?= $tablewidth ?>cm; margin:10px;" border="0">
                    <tr>
                        <td style="padding-left:40px;height:2cm;">
                            <table style="width: 100%; " border="0">
                                <tr style="">
                                    <td style="width: 1.5cm; text-align: left; vertical-align: middle; padding: 0px; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
                                        <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 100px;">
                                    </td>
                                    <td style="width: 10cm; text-align: left; vertical-align: top; padding: 5px; line-height: 1.1;">
                                        <span style="font-size: 1.3rem; font-weight: 600"><?= $modCompany->name; ?></span><br>
                                        <span style="font-size: 1rem;"><?= $modCompany->alamat; ?></span><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="vertical-align: top; padding-left:40px;height:5cm;">
                            <br>
                            <table style="width: 18cm; vertical-align: top; padding: 0px 0px;">
                                <tr>
                                    <td colspan="3">Demak, <?php echo date('d M Y'); ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 100px; vertical-align: top;">Kepada Yth </td>
                                    <td style="width: 20px; vertical-align: top;"> : </td>
                                    <td> <?php echo nl2br($modAsuransi->kepada); ?></td>
                                </tr>
                                <tr>
                                    <td>Lampiran </td>
                                    <td> : </td>
                                    <td> <?php echo $modAsuransi->lampiran; ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding-left:40px; height:14cm;">
                            <table style="width: 18cm; vertical-align: top;">
                                <tr>
                                    <td>Nama Tertanggung</td>
                                    <td class="text-center" style="width: 20px;"> : </td>
                                    <td><?= $modCompany->name; ?></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top;">Alamat</td>
                                    <td class="text-center" style="width: 20px; vertical-align: top;"> : </td>
                                    <td><?= $modCompany->alamat; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;">Tanggal Muat</td>
                                    <td class="text-center" style="width: 20px;"> : </td>
                                    <td> <?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modAsuransi->tanggal_muat); ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Berangkat</td>
                                    <td class="text-center"> : </td>
                                    <td> <?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modAsuransi->tanggal_berangkat); ?></td>
                                </tr>
                                <tr>
                                    <td>Deskripsi Obyek Pertanggungan</td>
                                    <td class="text-center"> : </td>
                                    <td> <?php echo $modAsuransi->dop; ?></td>
                                </tr>
                                <tr>
                                    <td>Total Sum Insured</td>
                                    <td class="text-center"> : </td>
                                    <td> Rp. <?php echo \app\components\DeltaFormatter::formatNumberForAllUser($modAsuransi->pembulatan); ?>,-</td>
                                </tr>
                                <tr>
                                    <td>Terbilang</td>
                                    <td class="text-center"> : </td>
                                    <td> #<?= strtoupper(\app\components\DeltaFormatter::formatNumberTerbilang(\app\components\DeltaFormatter::formatNumberForUserFloat($modAsuransi->pembulatan, 2))) ?>#</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top;">Rute</td>
                                    <td style="vertical-align:top;" class="text-center"> : </td>
                                    <td style="vertical-align:top;"> <?php echo $modAsuransi->rute; ?></td>
                                </tr>
                                <tr>
                                    <td>Nama Kapal</td>
                                    <td class="text-center"> : </td>
                                    <td> <?php echo $modAsuransi->nama_kapal; ?></td>
                                </tr>
                                <tr>
                                    <td>Rate</td>
                                    <td class="text-center"> : </td>
                                    <td> <?php echo $modAsuransi->rate; ?>%</td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="text-center"> : </td>
                                    <td> <?php echo $modAsuransi->discount; ?>%</td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:3.5cm;">
                            <table style="width: <?= $tablewidth ?>cm;">
                                <tr>
                                    <td style="width: 12cm">&nbsp;</td>
                                    <td style="width: 9cm; line-height: 15px;" class="text-left">
                                        Hormat kami,
                                        <br><?= $modCompany->name; ?>
                                        <br><br><br><br><br>
                                        <span style="vertical-align: top;">
                                            <u>William Novendy</u>
                                            <br>
                                            <span style="font-size:0.9rem;vertical-align:top;">
                                                Shipping
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:bottom;height:3.5cm;">
                            <table style="width: <?= $tablewidth ?>cm">
                                <tr>
                                    <td style="width: 7cm;vertical-align: bottom; font-size: 0.9rem; padding:3px;padding-left:40px;">
                                        <?php
                                        echo Yii::t('app', 'Printed By : ') . Yii::$app->user->getIdentity()->userProfile->fullname . "&nbsp;";
                                        echo '<br>' . Yii::t('app', 'Date : ') . date('d/m/Y H:i:s') . ' WIB';
                                        ?>
                                    </td>
                                    <td style="width: 10cm;text-align: right; padding:3px;">
                                        <?php //echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/new-sertifikat.png" alt="" class="logo-default" style="width: 8cm;">'; 
                                        ?>
                                        <img src="<?= \Yii::$app->view->theme->baseUrl . '/cis/img/sertifikat_tanpa_CARB.png' ?>" alt="sertifikat" class="logo-default" style="width: 8cm;">
                                        <!-- <img src="<?= \Yii::$app->view->theme->baseUrl . '/cis/img/sertifikatplusFSC.jpg' ?>" alt="sertifikat" class="logo-default" style="width: 8cm;"> -->
                                    </td>
                                    <td style="width:7cm"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br><br>
                <table style="width: <?= $tablewidth ?>cm; margin:10px;" border="0">
                    <tr>
                        <td style="vertical-align:top;padding-left:40px;height:23cm">
                            <table id="detail" style="width:18cm;">
                                <tr>
                                    <th colspan="5" class="text-center">ESTIMASI ASURANSI <?= strtoupper($modAsuransi->nama_kapal) ?></th>
                                </tr>
                                <tr>
                                    <th style="width: 50px; text-align: center;">No.</th>
                                    <th style="text-align: left;">Jenis Kayu</th>
                                    <th style="width: 100px; text-align: center;">Harga</th>
                                    <th style="width: 100px; text-align: center;">Kubikasi</th>
                                    <th style="width: 100px; text-align: center;">Sub Total</th>
                                </tr>

                                <tbody>
                                    <?php
                                    $modAsuransiDetail = \app\models\TAsuransiDetail::findAll(['asuransi_id' => $modAsuransi->asuransi_id]);
                                    $x = '';
                                    $i = 0;
                                    $kubikasis = 0;
                                    foreach ($modAsuransiDetail as $f => $v) {
                                    ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php
                                                if ($x != $v->jenis) {
                                                    $i = $i + 1;
                                                    echo $i;
                                                    $kubikasis += $v->kubikasi;
                                                    $PaddingRight = "";
                                                } else {
                                                    $PaddingRight = "style='color:red;padding-right:40px;'";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $v->tipe; ?>
                                            </td>
                                            <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($v->harga, 2); ?></td>
                                            <td class="text-right" <?= $PaddingRight ?>><?php echo \app\components\DeltaFormatter::formatNumberForUser($v->kubikasi, 2); ?></td>
                                            <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($v->total, 2); ?></td>
                                        </tr>
                                    <?php
                                        $x = $v->jenis;
                                    }
                                    ?>
                                </tbody>


                                <tr>
                                    <th colspan="3" class="text-right">Total</th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($kubikasis, 2); ?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->total, 2); ?></th>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td class="text-center"></td>
                                    <td><?php echo $modAsuransi->nama_kapal; ?></td>
                                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser(($modAsuransi->freight * 1), 2); ?></td>
                                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($kubikasis, 2); ?></td>
                                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser(($modAsuransi->freight * $kubikasis), 2); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center"></td>
                                    <td colspan="3" class="text-right">Total</td>
                                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->jumlah, 2); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center"></td>
                                    <td colspan="3" class="text-right">Ppn</td>
                                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->ppn, 2); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center"></td>
                                    <th colspan="3" class="text-right">Grand Total</th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->grandtotal, 2); ?></th>
                                </tr>
                                <tr>
                                    <td class="text-center"></td>
                                    <th colspan="3" class="text-right">Dibulatkan</th>
                                    <th class="text-right" style="color:darkgreen;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->pembulatan, 2); ?></th>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:bottom;height:3.5cm;">
                            <table style="width: <?= $tablewidth ?>cm">
                                <tr>
                                    <td style="width: 7cm;vertical-align: bottom; font-size: 0.9rem; padding:3px;padding-left:40px;">
                                        <?php
                                        echo Yii::t('app', 'Printed By : ') . Yii::$app->user->getIdentity()->userProfile->fullname . "&nbsp;";
                                        echo '<br>' . Yii::t('app', 'Date : ') . date('d/m/Y H:i:s') . ' WIB';
                                        ?>
                                    </td>
                                    <td style="width: 6cm;text-align: right; padding:3px;">
                                        <?php // echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/new-sertifikat.png" alt="" class="logo-default" style="width: 8cm;">'; 
                                        ?>
                                    </td>
                                    <td style="width:7cm"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <span style="page-break-after: always;">&nbsp;</span>
            </div>
            <div class="modal-footer text-align-center">
                <a class="btn btn-outline blue btn-md" onclick=""><i class="fa fa-print"></i> Print</a> <!-- printPo(<?php //echo $model->posengon_id ?>) -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) 
?>