<?php
/** @var array $data */

use app\components\DeltaFormatter;
use app\models\MMtrgSetup;

$this->title = MMtrgSetup::KATEGORI_ROTARY;
?>
<header>
    <div>
        <h2 style="text-align: left; margin-left: 20px">MONITORING <?= MMtrgSetup::KATEGORI_ROTARY?></h2>
    </div>
    <div style="margin-top: -30px;">
        <h2 class="datetime" style="margin-right: 20px; text-align: center"></h2>
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
    <tr>
        <th rowspan="<?= count($data) + 3?>">SENGON</th>
        <th colspan="6" style="text-align: left">INPUT</th>
    </tr>
    <?php
        $jam_jalan = 0;
        $daily_input_actual = 0;
        $daily_input_plan = 0;
        $daily_input_achieve = 0;
        $daily_input_perjam_actual = 0;
        $daily_input_perjam_plan = 0;
        $monthly_input_actual = 0;
        $monthly_input_plan = 0;
        $monthly_input_achieve = 0;
        foreach ($data as $row): ?>
        <?php if ($row->jenis_proses === MMtrgSetup::INPUT && $row->jenis_kayu === 'Sengon' && $row->kategori_proses === MMtrgSetup::KATEGORI_ROTARY): ?>
            <?php
                $satuan = DeltaFormatter::satuanMonitoring($row->satuan_harian);
                if($row->grade === 'Input') {
                    $monthly_input_plan = MMtrgSetup::getSummary($row->grade, $row->jenis_proses, $row->jenis_kayu)->sum('plan_harian');
                    $monthly_input_actual = MMtrgSetup::getSummary($row->grade, $row->jenis_proses, $row->jenis_kayu)->sum('jumlah_aktual');
                    $monthly_input_achieve = $monthly_input_actual / $monthly_input_plan * 100;
                    $daily_input_achieve = $row->jumlah_aktual / $row->plan_harian * 100;
                    $daily_input_actual = $row->jumlah_aktual;
                    $daily_input_plan = $row->plan_harian;
                }
            ?>
            <tr>
                <td style="text-indent: 2rem; text-align: left"><?= $row->grade ?></td>
                <td><?= MMtrgSetup::getSummary($row->grade, $row->jenis_proses, $row->jenis_kayu)->sum('plan_harian'), $satuan ?></td>
                <td><?= round(MMtrgSetup::getSummary($row->grade, $row->jenis_proses, $row->jenis_kayu)->sum('jumlah_aktual'), 3), $satuan ?></td>
                <td><?= is_float($monthly_input_actual / $monthly_input_plan * 100) ? round($monthly_input_actual / $monthly_input_plan * 100, 1) : $monthly_input_actual / $monthly_input_plan * 100, '%'?></td>
                <td><?= $row->plan_harian, $satuan ?></td>
                <td><?= round($row->jumlah_aktual, 3),  $satuan ?></td>
                <td class="<?= $row->jumlah_aktual / $row->plan_harian * 100 < 100 ? 'warning' : 'achieved'?>">
                    <?= is_float($row->jumlah_aktual / $row->plan_harian * 100)
                        ? round($row->jumlah_aktual / $row->plan_harian * 100, 1)
                        : $row->jumlah_aktual / $row->plan_harian * 100, '%'
                    ?>
                </td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
    <tr>
        <th colspan="7" style="text-align: left">OUTPUT</th>
    </tr>
    <?php
        $monthly_rendemen_plan = 0;
        $monthly_rendemen_actual = 0;
        $monthly_rendemen_achieve = 0;
        $daily_rendemen_achieve = 0;
        $daily_rendemen_output_actual   = 0;
        $daily_rendemen_output_plan   = 0;
        $count_grade_output = 0;
        foreach ($data as $row): ?>
        <?php if ($row->jenis_proses === MMtrgSetup::OUTPUT && $row->jenis_kayu === 'Sengon' && $row->kategori_proses === MMtrgSetup::KATEGORI_ROTARY): ?>
        <?php
        $satuan = DeltaFormatter::satuanMonitoring($row->satuan_harian);

        $monthly_output_plan = MMtrgSetup::getSummary($row->grade, $row->jenis_proses, $row->jenis_kayu)->sum('plan_harian');
        $monthly_output_plan = $monthly_output_plan / $monthly_input_plan * 100;
        $monthly_output_actual = MMtrgSetup::getSummary($row->grade, $row->jenis_proses, $row->jenis_kayu)->sum('jumlah_aktual');
        $monthly_output_actual = $monthly_output_actual / $monthly_input_plan * 100;
        $monthly_output_achieve = $monthly_output_actual / $monthly_output_plan * 100;

        $daily_output_grade = $row->jumlah_aktual / $daily_input_plan * 100;
        $daily_output_plan = $row->plan_harian / $daily_input_plan * 100;
        $daily_output_achieve   = $daily_output_grade / $daily_output_plan * 100;

        $monthly_rendemen_plan += $monthly_output_plan;
        $monthly_rendemen_actual += $monthly_output_actual;
        $monthly_rendemen_achieve += $monthly_output_achieve;

        $daily_rendemen_achieve += $daily_output_achieve;
        $daily_rendemen_output_actual += $daily_output_grade;
        $daily_rendemen_output_plan += $daily_output_plan;
        ++$count_grade_output;
        ?>
            <tr>
                <td style="text-indent: 2rem; text-align: left"><?= $row->grade ?></td>
                <td><?= is_float($monthly_output_plan) ? round($monthly_output_plan, 1) : $monthly_output_plan, '%' ?></td>
                <td><?= is_float($monthly_output_actual) ? round($monthly_output_actual, 1) : $monthly_output_actual, '%' ?></td>
                <td><?= is_float($monthly_output_achieve) ? round($monthly_output_achieve, 1) : $monthly_output_achieve, '%'?></td>
                <td><?= is_float($daily_output_plan) ? round($daily_output_plan, 1) : $daily_output_plan, '%' ?></td>
                <td><?= is_float($daily_output_grade) ? round($daily_output_grade, 1) : $daily_output_grade, '%' ?></td>
                <td class="<?= $daily_output_grade < $daily_output_plan ? 'warning' : 'achieved'?>">
                    <?= is_float($daily_output_achieve)
                        ? round($daily_output_achieve, 1)
                        : $daily_output_achieve, '%'?>
                </td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
    <?php
    $monthly_rendemen_achieve_percent = $monthly_rendemen_achieve !== 0 && $count_grade_output !== 0 ? $monthly_rendemen_achieve / $count_grade_output : 0;
    $monthly_rendemen_achieve_percent = is_float($monthly_rendemen_achieve_percent) ? round($monthly_rendemen_achieve_percent, 1) : $monthly_rendemen_achieve_percent;
    $daily_rendemen_achieve_percent = $daily_rendemen_achieve !== 0 && $count_grade_output !== 0 ? $daily_rendemen_achieve / $count_grade_output : 0;
    $daily_rendemen_achieve_percent = is_float($daily_rendemen_achieve_percent) ? round($daily_rendemen_achieve_percent, 1) : $daily_rendemen_achieve_percent;
    ?>
    <tr>
        <th style="text-align: left">RENDEMEN</th>
        <th><?= is_float($monthly_rendemen_plan) ? round($monthly_rendemen_plan, 1) : $monthly_rendemen_plan, '%'?></th>
        <th><?= is_float($monthly_rendemen_actual) ? round($monthly_rendemen_actual, 1) : $monthly_rendemen_actual, '%'?></th>
        <th><?= $monthly_rendemen_achieve_percent, '%' ?></th>
        <th><?= is_float($daily_rendemen_output_plan) ? round($daily_rendemen_output_plan, 1) : $daily_rendemen_output_plan, '%'?></th>
        <th><?= is_float($daily_rendemen_output_actual) ? round($daily_rendemen_output_actual, 1) : $daily_rendemen_output_actual, '%'?></th>
        <th class="<?= $daily_rendemen_achieve_percent < 100 ? 'warning' : 'achieved'?>"><?= $daily_rendemen_achieve_percent, '%' ?></th>
    </tr>
    </tbody>
</table>