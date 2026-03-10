<?php

/**
 * @var array $data
 * @var integer $hari_kerja
 */

use app\components\DeltaFormatter;
use app\models\MMtrgSetup;

$this->title = MMtrgSetup::KATEGORI_ROTARY;

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
    padding: 1px;
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
            MONITORING <?= MMtrgSetup::KATEGORI_ROTARY ?>
            <br>
            <span style="font-size: 70%">Total Waktu Kerja Bulan Ini : </span><span
                style="font-size: 90%;"><?= $hari_kerja ?> Hari</span>
        </h3>
    </div>
    <div style="display: flex;">
        <div>
            <div style="border-right: 2px solid yellow; margin-top: 40px; padding-right: 15px;">
                <p>Shift : <strong><?= MMtrgSetup::getActiveShift(MMtrgSetup::KATEGORI_ROTARY) ?></strong></p>
                <p style="margin-top: -25px;">Note :
                    <?= MMtrgSetup::getNotConfirmed(MMtrgSetup::KATEGORI_ROTARY) ? '<span class="notice" style="margin-top: -5px;">' . MMtrgSetup::getNotConfirmed(MMtrgSetup::KATEGORI_ROTARY) . ' data perlu tindakan</span>' : '-' ?>
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
            <th colspan="5">AKUMULASI</th>
            <th colspan="5">DAILY</th>
        </tr>
        <tr>
            <th colspan="2">Plan</th>
            <th colspan="2">Actual</th>
            <th>Achieve</th>
            <th colspan="2">Plan</th>
            <th colspan="2">Actual</th>
            <th>Achieve</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $planm3 = 0;
        $planhm3 = 0;
        foreach ($data as $row) : ?>
        <?php 
            if($row['grade'] === 'Input') {
                $planm3 = $row['plan_bulanan'];
                $planhm3 = $row['plan_harian'];
            }
            if($row['grade'] === 'RENDEMEN') {
                $bln_aktual = MMtrgSetup::getSummary('*', $row['jenis_proses'], ucwords(strtolower($row['jenis_kayu'])), $row['kategori_proses'], MMtrgSetup::getActiveDate())->sum("jumlah_aktual");
                $hari_aktual = MMtrgSetup::find()->where([
                    'kategori_proses' => $row['kategori_proses'],
                    'jenis_proses' => $row['jenis_proses'],
                    'tanggal' => MMtrgSetup::getActiveDate(),
                    'jenis_kayu' => ucwords(strtolower($row['jenis_kayu'])),
                ])->sum('jumlah_aktual');
            }else {
                $bln_aktual = MMtrgSetup::getSummary($row['grade'], $row['jenis_proses'], ucwords(strtolower($row['jenis_kayu'])), $row['kategori_proses'], MMtrgSetup::getActiveDate())->sum("jumlah_aktual");
                $hari_aktual = MMtrgSetup::find()->where([
                    'kategori_proses' => $row['kategori_proses'],
                    'jenis_proses' => $row['jenis_proses'],
                    'tanggal' => MMtrgSetup::getActiveDate(),
                    'jenis_kayu' => ucwords(strtolower($row['jenis_kayu'])),
                    'grade' => $row['grade']
                ])->sum('jumlah_aktual');
            }
        ?>
        <tr>
            <td><strong><?= $row['jenis_kayu'] ?></strong></td>
            <td style="text-align: left; <?= !in_array($row['grade'], ['INPUT', 'OUTPUT', 'RENDEMEN']) ? 'text-indent: 2em' : '' ?>">
                <?= $row['grade'] ?>
            </td>

            <?php if(in_array($row['grade'], ['INPUT', 'OUTPUT'])) : ?>
                <td colspan="10"></td>
            <?php else: ?>
                <td colspan="<?= $row['jenis_proses'] === MMtrgSetup::INPUT ? '2' : '' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['plan_bulanan'], $row['satuan_harian'], $row['grade']) ?>
                </td>
                <?php if($row['jenis_proses'] === MMtrgSetup::OUTPUT) : ?>
                <td>
                    <?= DeltaFormatter::renderDataMonitoring($planm3 * $row['plan_bulanan'] / 100, 'm3', $row['grade']) ?>
                </td>
                <?php endif ?>

                <td colspan="<?= $row['jenis_proses'] === MMtrgSetup::INPUT ? '2' : '' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['jumlah_bulanan'], $row['satuan_harian'], $row['grade']) ?>
                </td>

                <?php if($row['jenis_proses'] === MMtrgSetup::OUTPUT) : ?>
                <td>
                    <?=DeltaFormatter::renderDataMonitoring($bln_aktual, 'm3', $row['grade']);?>
                </td>
                <?php endif ?>

                <td class="<?= $row['monthly_achieve'] <= 99 ? 'warning' : 'achieved' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['monthly_achieve'], "%", $row['grade']) ?>
                </td>

                <td colspan="<?= $row['jenis_proses'] === MMtrgSetup::INPUT ? '2' : '' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['plan_harian'], $row['satuan_harian'], $row['grade']) ?>
                </td>
                
                <?php if($row['jenis_proses'] === MMtrgSetup::OUTPUT) : ?>
                <td>
                    <?= DeltaFormatter::renderDataMonitoring($planhm3 * $row['plan_harian'] / 100, 'm3', $row['grade']) ?>
                </td>
                <?php endif ?>

                <td colspan="<?= $row['jenis_proses'] === MMtrgSetup::INPUT ? '2' : '' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['jumlah_aktual'], $row['satuan_harian'], $row['grade']) ?>
                </td>
                
                <?php if($row['jenis_proses'] === MMtrgSetup::OUTPUT) : ?>
                <td>
                    <?= DeltaFormatter::renderDataMonitoring($hari_aktual, 'm3', $row['grade']) ?>
                </td>
                <?php endif ?>

                <td class="<?= $row['daily_achieve'] <= 99 ? 'warning' : 'achieved' ?>">
                    <?= DeltaFormatter::renderDataMonitoring($row['daily_achieve'], "%", $row['grade']) ?>
                </td>
            <?php endif ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>