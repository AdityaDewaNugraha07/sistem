<?php

/**
 * @var array $data
 * @var integer $hari_kerja
 */

use app\components\DeltaFormatter;
use app\models\MMtrgSetup;
// echo "<pre>";
// print_r($data[5]);
// die;
$this->title = MMtrgSetup::KATEGORI_DRYING;
$uid = str_replace(" ", "-", strtolower($this->title));
$this->registerCss("
:root {
    --" . $uid . "-foreground: yellow;
    --" . $uid . "-background: #00005d;
    --" . $uid . "-achieved: #AFFF00;
    --" . $uid . "-font-size: 28px;
}

table, th, td {
    border: 1px solid var(--" . $uid . "-foreground);
    border-collapse: collapse;
    padding: 4px;
    text-align: center;
}

table td table tr td:first-child {
    width: 65%;
}

table td table tr td:last-child {
    width: 35%;
}
");
?>
<header>
    <div>
        <h3 style="text-align: left; margin-left: 20px">
            MONITORING <?= $this->title ?>
            <br>
            <span style="font-size: 70%">Total Waktu Kerja Bulan Ini : </span><span style="font-size: 90%;"><?= $hari_kerja ?> Hari</span>
        </h3>
    </div>
    <div style="display: flex;">
        <div>
            <div style="border-right: 2px solid yellow; margin-top: 40px; padding-right: 15px;">
                <p>Shift : <strong><?= MMtrgSetup::getActiveShift(MMtrgSetup::KATEGORI_DRYING) ?></strong></p>
                <p style="margin-top: -25px;">Note : <?= MMtrgSetup::getNotConfirmed(MMtrgSetup::KATEGORI_DRYING) ? '<span class="notice" style="margin-top: -5px;">' . MMtrgSetup::getNotConfirmed(MMtrgSetup::KATEGORI_DRYING) . ' data perlu tindakan</span>' : '-' ?> </p>
            </div>
        </div>
        <h3 class="datetime" style="margin-right: 20px; text-align: center; margin-left: 10px;"></h3>
    </div>
</header>
<table style="width: 100%">
    <thead>
        <tr>
            <th rowspan="3">JENIS KAYU</th>
            <th rowspan="3">GRADE</th>
            <th colspan="6">AKUMULASI</th>
            <th colspan="5">DAILY</th>
        </tr>
        <tr>
            <th rowspan="2">Rotary</th>
            <th colspan="2">Plan</th>
            <th colspan="2">Actual</th>
            <th rowspan="2">Achieve</th>
            <th colspan="2">Plan</th>
            <th colspan="2">Actual</th>
            <th rowspan="2">Achieve</th>
        </tr>
        <tr>
            <th>%</th>
            <th>M<sup>3</sup></th>
            <th>%</th>
            <th>M<sup>3</sup></th>
            <th>%</th>
            <th>M<sup>3</sup></th>
            <th>%</th>
            <th>M<sup>3</sup></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $pbm3 = 0;
        $jbm3 = 0;
        $phm3 = 0;
        $jhm3 = 0;
        $no = 1;
        foreach ($data as $row) : ?>
            <?php

            $is_rendemen = $row['grade'] === 'RENDEMEN';
            ?>
            <tr>
                <td>
                    <strong><?= $row['jenis_kayu'] ?></strong>
                </td>
                <td style="text-align: left; <?= !in_array($row['grade'], ['INPUT', 'OUTPUT', 'RENDEMEN']) ? 'text-indent: 2em;' : ' font-weight: bold;' ?>">
                    <?= $row['grade'] ?>
                </td>
                <td style="font-weight: <?= $is_rendemen ? 'bold' : '' ?>">
                    <?php
                        $summery = MMtrgSetup::getSummary($is_rendemen ? '*' : $row['grade'], MMtrgSetup::OUTPUT, ucwords(strtolower($row['jenis_kayu'])), MMtrgSetup::KATEGORI_ROTARY, MMtrgSetup::getActiveDate());
                        echo DeltaFormatter::renderDataMonitoring($summery->sum('jumlah_aktual'), 'm3', $row['grade'])
                    ?>
                </td>
                <td style="font-weight: <?= $is_rendemen ? 'bold' : '' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['plan_bulanan'], $row['satuan_harian'], $row['grade']) ?>
                </td>
                <td style="font-weight: <?= $is_rendemen ? 'bold' : '' ?>">
                    <?php
                    $plan_bln_m3 = !in_array($row['grade'], ['INPUT', 'OUTPUT']) && $row['jumlah_aktual'] > 0 ? $row['data']->jumlah_bulanan_m3 / $row['jumlah_aktual'] * 100 * $row['plan_bulanan'] / 100 : 0;
                    echo DeltaFormatter::renderDataMonitoring($is_rendemen ? $pbm3 : $plan_bln_m3, 'm3', $row['grade']);
                    $pbm3 += number_format(round($plan_bln_m3, 2), 2, ".", "");
                    ?>
                </td>
                <td style="font-weight: <?= $is_rendemen ? 'bold' : '' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['jumlah_bulanan'], $row['satuan_harian'], $row['grade']) ?>
                </td>
                <td style="font-weight: <?= $is_rendemen ? 'bold' : '' ?>">
                    <?php
                    $jml_bln_m3 = !in_array($row['grade'], ['INPUT', 'OUTPUT']) ? $row['data']->jumlah_bulanan_m3 : 0;
                    echo DeltaFormatter::renderDataMonitoring($is_rendemen ? $jbm3 : $jml_bln_m3, 'm3', $row['grade']);
                    $jbm3 += number_format(round($jml_bln_m3, 2), 2, ".", "");
                    ?>
                </td>
                <td style="font-weight: <?= $is_rendemen ? 'bold' : '' ?>" class="<?= $row['monthly_achieve'] <= 99 ? 'warning' : 'achieved' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['monthly_achieve'], "%", $row['grade']) ?>
                </td>
                <td style="font-weight: <?= $is_rendemen ? 'bold' : '' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['plan_harian'], $row['satuan_harian'], $row['grade']) ?>
                </td>
                <td style="font-weight: <?= $is_rendemen ? 'bold' : '' ?>">
                    <?php
                    $plan_hari_m3 = !in_array($row['grade'], ['INPUT', 'OUTPUT']) && $row['jumlah_aktual'] ? $row['data']->jumlah_aktual_m3 / $row['jumlah_aktual'] * 100 * $row['plan_harian'] / 100 : 0;
                    echo DeltaFormatter::renderDataMonitoring($is_rendemen ? $phm3 : $plan_hari_m3, 'm3', $row['grade']);
                    $phm3 += number_format(round($plan_hari_m3, 2), 2, ".", "");
                    ?>
                </td>
                <td style="font-weight: <?= $is_rendemen ? 'bold' : '' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['jumlah_aktual'], $row['satuan_harian'], $row['grade']) ?>
                </td>
                <td style="font-weight: <?= $is_rendemen ? 'bold' : '' ?>">
                    <?php
                    $jml_hari_m3 = !in_array($row['grade'], ['INPUT', 'OUTPUT']) ? $row['data']->jumlah_aktual_m3 : 0;
                    echo DeltaFormatter::renderDataMonitoring($is_rendemen ? $jhm3 : $jml_hari_m3, 'm3', $row['grade']);
                    $jhm3 += number_format(round($jml_hari_m3, 2), 2, ".", "");
                    ?>
                </td>
                <td class="<?= $row['daily_achieve'] <= 99 ? 'warning' : 'achieved' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['daily_achieve'], "%", $row['grade']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>