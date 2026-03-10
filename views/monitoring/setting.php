<?php
/**
 * @var array $data
 * @var integer $hari_kerja
 */

use app\components\DeltaFormatter;
use app\models\MMtrgSetup;

$this->title = MMtrgSetup::KATEGORI_SETTING;

$uid = str_replace(" ", "-", strtolower($this->title));
$this->registerCss("
:root {
    --".$uid."-foreground: yellow;
    --".$uid."-background: #00005d;
    --".$uid."-achieved: #AFFF00;
    --".$uid."-font-size: 32px;
}

table, th, td {
    border: 1px solid var(--".$uid."-foreground);
    border-collapse: collapse;
    padding: 1px;
    text-align: center;
}

table td table tr td:first-child {
    width: 60%;
}

table td table tr td:last-child {
    width: 40%;
}
");
?>
<header>
    <div>
        <h3 style="text-align: left; margin-left: 20px">
            MONITORING <?= MMtrgSetup::KATEGORI_SETTING ?>
            <br>
            <span style="font-size: 70%">Total Waktu Kerja Bulan Ini : </span><span
                style="font-size: 90%;"><?= $hari_kerja ?> Hari</span>
        </h3>
    </div>
    <div style="display: flex;">
        <div>
            <div style="border-right: 2px solid yellow; margin-top: 40px; padding-right: 15px;">
                <p>Shift : <strong><?= MMtrgSetup::getActiveShift(MMtrgSetup::KATEGORI_SETTING) ?></strong></p>
                <p style="margin-top: -25px;">Note :
                    <?= MMtrgSetup::getNotConfirmed(MMtrgSetup::KATEGORI_SETTING) ? '<span class="notice" style="margin-top: -5px;">' . MMtrgSetup::getNotConfirmed(MMtrgSetup::KATEGORI_SETTING) . ' data perlu tindakan</span>' : '-' ?>
                </p>
            </div>
        </div>
        <h3 class="datetime" style="margin-right: 20px; text-align: center; margin-left: 10px;"></h3>
    </div>
</header>
<table style="width: 100%">
    <thead>
    <tr>
        <th rowspan="2">JENIS KAYU</th>
        <th rowspan="2">GRADE</th>
        <th colspan="3">AKUMULASI</th>
        <th colspan="3">DAILY</th>
    </tr>
    <tr>
        <th>Plan</th>
        <th>Actual</th>
        <th>Achieve</th>
        <th>Plan</th>
        <th>Actual</th>
        <th>Achieve</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $row) : ?>
    <tr>
        <td><strong><?= $row['jenis_kayu']?></strong></td>
        <td style="text-align: left; <?= !in_array($row['grade'] , ['INPUT', 'OUTPUT', 'RENDEMEN']) ? 'text-indent: 2em' : ''?>"><?= $row['grade']?></td>
        <td><?= DeltaFormatter::renderDataMonitoring($row['plan_bulanan'], $row['satuan_harian'], $row['grade'])?></td>
        <td><?= DeltaFormatter::renderDataMonitoring($row['jumlah_bulanan'], $row['satuan_harian'], $row['grade'])?></td>
        <td class="<?= $row['monthly_achieve'] < 100 ? 'warning' : 'achieved'?>"><?= DeltaFormatter::renderDataMonitoring($row['monthly_achieve'], "%", $row['grade'])?></td>
        <td><?= DeltaFormatter::renderDataMonitoring($row['plan_harian'], $row['satuan_harian'], $row['grade'])?></td>
        <td><?= DeltaFormatter::renderDataMonitoring($row['jumlah_aktual'], $row['satuan_harian'], $row['grade'])?></td>
        <td class="<?= $row['daily_achieve'] < 100 ? 'warning' : 'achieved'?>"><?= DeltaFormatter::renderDataMonitoring($row['daily_achieve'], "%", $row['grade'])?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>