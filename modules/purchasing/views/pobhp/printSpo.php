<?php

use app\components\DeltaFormatter;

app\assets\DatatableAsset::register($this);
$modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
$blankspace = 2;

?>
<style>
    table {
        font-size: 0.8rem;
    }

    table#table-detail {
        font-size: 0.8rem;
    }

    table#table-detail tr td {
        vertical-align: top;
    }
</style>
<table style="width: 20cm; margin: 10px;" border="1">
    <tr>
        <td colspan="3" style="padding: 5px;border-left:none; border-right: none; border-top: none; border-bottom: solid 1px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 3cm; text-align: left; vertical-align: middle; padding: 0px; height: 1cm;">
                        <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;">
                    </td>
                    <td style="width: 8cm; text-align: left; vertical-align: top; padding: 5px; line-height: 1.1;">
                        <span style="font-size: 0.9rem; font-weight: 600"><?= $modCompany->name; ?></span><br>
                        <span style="font-size: 0.8rem;"><?= $modCompany->alamat; ?></span><br>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style=" border: 0px;">
            <table style="width: 100%;" border="0px">
                <tr style="">
                    <td style="width: 5cm; text-align: left; vertical-align: middle;border-right: solid 1px transparent;"></td>
                    <td style="text-align: center; vertical-align: top; padding: 5px; line-height: 1;">
                        <span style="font-size: 1.2rem; font-weight: 600"><?= $paramprint['judul']; ?></span>
                    </td>
                    <td style="width: 5cm; vertical-align: top;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border-style:none;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 95%; padding-right: 5px; vertical-align: top;">
                        <table style="width: 100%; margin: 5px; border: none;">
                            <tr style="height: 2cm;">
                                <td style="text-align: left; vertical-align: top; font-size: 0.9rem;">
                                    Kepada Yth,
                                    <b><?= !empty($model->suplier_id) ? $model->suplier->suplier_nm : ""; ?></b><br>
                                    <?php
                                    if (!empty($model->suplier->fax)) {
                                        echo "<span style='font-size:0.9rem; font-weight:bold; margin-bottom:30px;'>(FAX: " . $model->suplier->fax . ")</span><br style='margin-bottom: 15px;' >";
                                    }
                                    if (!empty($model->tanggal_kirim)) {
                                        echo 'Bersama ini kami mohon dikirim barang-barang sbb pada tanggal ' . app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal_kirim) . ' :';
                                    } else {
                                        echo 'Dengan Hormat,<br>Bersama ini kami mohon diberikan barang-barang sbb:';
                                    }
                                    ?>
                                </td>
                                <td style="text-align: right; vertical-align: top; font-size: 0.9rem; width: 200px;">
                                    Tanggal, <?= DeltaFormatter::formatDateTimeForUser($model->spo_tanggal); ?>
                                    <br>
                                    <?php
                                    if ($model->spo_is_ppn) {
                                        echo "<b><i>Include Ppn</i></b>";
                                    } else {
                                        echo "<b><i>Exclude Ppn</i></b>";
                                    }
                                    ?>
                                    <br>
                                    <u><span style="font-weight: bold;">
                                            <?= $model->spo_kode ?>
                                        </span></u>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="vertical-align: top; font-size: 1rem;">
                                    <table style="width: 100%;" border="1" cellspacing="0">
                                        <tr>
                                            <td style="font-weight: bold; width: 1cm; text-align: center; padding: 3px;">
                                                No.
                                            </td>
                                            <td style="font-weight: bold;  text-align: left;  width: 5.5cm;">Nama
                                                Barang
                                            </td>
                                            <td style="font-weight: bold;  width: 2cm; text-align: center;">Qty</td>
                                            <td style="font-weight: bold;  text-align: center;  width: 2.5cm;">Harga
                                                <span style="font-size: 0.9rem;">(<?= $model->defaultValue->name_en; ?>)</span>
                                            </td>
                                            <td style="font-weight: bold;  text-align: center;  width: 2.5cm;">Total
                                                <span style="font-size: 0.9rem;">(<?= $model->defaultValue->name_en; ?>)</span>
                                            </td>
                                            <td style="font-weight: bold;  text-align: left;">Keterangan</td>
                                        </tr>
                                        <?php
                                        $max = count($modDetail);
                                        $total_duid = "";
                                        for ($i = 0; $i < $max; $i++) {

                                            if ($max >= ($i + 1)) {

                                                //                                                $ppn = $modDetail[$i]->spod_harga * \app\components\Params::DEFAULT_PPN;
                                                //                                                if ($model->spo_is_ppn == TRUE) {
                                                //                                                    $harga = $modDetail[$i]->spod_harga + $ppn;
                                                //                                                } else {
                                                //                                                    $harga = $modDetail[$i]->spod_harga;
                                                //                                                }
                                        ?>
                                                <tr style="font-size: 0.9rem;">
                                                    <td style="text-align: center;"><?= $i + 1; ?></td>
                                                    <td style="padding: 2px;"><?= $modDetail[$i]->bhp->Bhp_nm; ?></td>
                                                    <td style="text-align: center;"><?= $modDetail[$i]->spod_qty . " (" . $modDetail[$i]->bhp->bhp_satuan . ")"; ?></td>
                                                    <td style="text-align: right; padding-right: 5px;"><?= DeltaFormatter::formatNumberForUser($modDetail[$i]->spod_harga); ?></td>
                                                    <td style="text-align: right; padding-right: 5px;"><?= DeltaFormatter::formatNumberForUser($modDetail[$i]->spod_qty * $modDetail[$i]->spod_harga); ?></td>
                                                    <td style="padding: 2px;"><?php echo $modDetail[$i]->spod_keterangan; ?></td>
                                                </tr>
                                            <?php
                                            } else {
                                            ?>
                                                <tr style="font-size: 1rem;">
                                                    <td style="text-align: center;">&nbsp;</td>
                                                    <td style="text-align: center;">&nbsp;</td>
                                                    <td style="text-align: center;">&nbsp;</td>
                                                    <td style="text-align: center;">&nbsp;</td>
                                                    <td style="text-align: center;">&nbsp;</td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        <?php
                                            $total_duid += $modDetail[$i]->spod_qty * $modDetail[$i]->spod_harga;
                                        }
                                        $pph = $model->spo_pph_nominal;
                                        if ($model->spo_is_pkp) {
                                            $ppn = $model->spo_ppn_nominal;
                                            $grand_total = $total_duid + $pph + $ppn;
                                        } else {
                                            $ppn = 0;
                                            $grand_total = $total_duid + $pph;
                                        }

                                        ?>

                                        <tr>
                                            <td style="font-weight: bold;  padding-right: 5px; text-align: right;" colspan="4" class="text-right">Total
                                            </td>
                                            <td style="font-weight: bold;  padding-right: 5px; text-align: right;" class="text-right"><?= DeltaFormatter::formatNumberForUser($total_duid) ?></td>
                                            <td style="font-weight: bold; ">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;  padding-right: 5px; text-align: right;" colspan="4" class="text-right">PPh
                                            </td>
                                            <td style="font-weight: bold;  padding-right: 5px; text-align: right;" class="text-right"><?= DeltaFormatter::formatNumberForAllUser($pph) ?></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;  padding-right: 5px; text-align: right;" colspan="4" class="text-right">PPN
                                            </td>
                                            <td style="font-weight: bold;  padding-right: 5px; text-align: right;" class="text-right"><?= DeltaFormatter::formatNumberForUser($ppn) ?></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;  padding-right: 5px; text-align: right;" colspan="4" class="text-right">Grand Total
                                            </td>
                                            <td style="font-weight: bold;  padding-right: 5px; text-align: right;" class="text-right"><?= DeltaFormatter::formatNumberForUser($grand_total) ?></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>
                    </td>

                </tr>
            </table>
        </td>
    </tr>


    <tr style="border-style:none;">
        <td colspan="3" style="padding: 5px;border:none;">
            <table style="width: 100%;">
                <tr style="height: 1cm;  ">
                    <td style="vertical-align: bottom; width: 1cm; border-bottom: solid 1px transparent; text-align: left;"></td>
                    <td style="vertical-align: bottom; width: 4cm; text-align: center;">Hormat Kami,</td>
                    <td style="vertical-align: bottom;border-bottom: solid 1px transparent;"></td>
                    <td style="vertical-align: bottom; width: 4cm; text-align: center;"></td>
                    <td style="vertical-align: bottom; width: 1cm; border-bottom: solid 1px transparent; text-align: left;"></td>
                </tr>
                <tr>
                    <td style="height: 2cm; "></td>
                    <td style="vertical-align: bottom; line-height: 1;  text-align: center;">
                        ( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )
                    </td>
                    <td style=""></td>
                    <td style="vertical-align: bottom; line-height: 1;  text-align: center;">

                    </td>
                    <td style=""></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="border-style:none;">
        <td style="border:none;height: <?= (0.5 * $blankspace) ?>cm">&nbsp;</td>
    </tr>


    <tr style="border-style:none;">
        <td style="vertical-align: bottom; height: 1.5cm;border: none;" colspan="3">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 7cm;vertical-align: bottom; font-size: 0.7rem; padding:3px;">
                        <?php
                        echo Yii::t('app', 'Printed By : ') . Yii::$app->user->getIdentity()->userProfile->fullname . "&nbsp;";
                        echo Yii::t('app', 'at : ') . date('d/m/Y H:i:s');
                        ?>
                    </td>
                    <td style="width: 6cm;text-align: center; padding:3px;">
                        <?php // echo '<img src="' . \Yii::$app->view->theme->baseUrl . '/cis/img/new-sertifikat.png" alt="" class="logo-default" style="width: 6cm;">'; 
                        ?>
                        <img src="<?= \Yii::$app->view->theme->baseUrl . '/cis/img/sertifikat_tanpa_CARB.png' ?>" alt="sertifikat" class="logo-default" style="width: 6cm;">
                        <!-- <img src="<?= \Yii::$app->view->theme->baseUrl . '/cis/img/sertifikatplusFSC.jpg' ?>" alt="sertifikat" class="logo-default" style="width: 6cm;"> -->
                    </td>
                    <td style="width: 7cm;"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table style="width: 20cm; margin: 10px;" border:0;>
    <tr>
        <td style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;text-align:right;">
            <span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-PCH-03-0</span>
        </td>
    </tr>
</table>