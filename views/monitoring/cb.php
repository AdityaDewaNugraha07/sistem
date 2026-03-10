<?php
/**
 * @var array $data
 * @var integer $hari_kerja
 */

use app\components\DeltaFormatter;
use app\models\MMtrgSetup;

$this->title = MMtrgSetup::KATEGORI_CORE_BUILDER;

$uid = str_replace(" ", "-", strtolower($this->title));
$this->registerCss("
:root {
    --".$uid."-foreground: yellow;
    --".$uid."-background: #00005d;
    --".$uid."-achieved: #AFFF00;
    --".$uid."-font-size: 24px;
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
// echo "<pre>";
// print_r($data);die;
?>
<header>
    <div>
        <h3 style="text-align: left; margin-left: 20px">
            MONITORING <?= MMtrgSetup::KATEGORI_CORE_BUILDER ?>
            <br>
            <span style="font-size: 70%">Total Waktu Kerja Bulan Ini : </span><span
                style="font-size: 90%;"><?= $hari_kerja ?> Hari</span>
        </h3>
    </div>
    <div style="display: flex;">
        <div>
            <div style="border-right: 2px solid yellow; margin-top: 40px; padding-right: 15px;">
                <p>Shift : <strong><?= MMtrgSetup::getActiveShift(MMtrgSetup::KATEGORI_CORE_BUILDER) ?></strong></p>
                <p style="margin-top: -25px;">Note :
                    <?= MMtrgSetup::getNotConfirmed(MMtrgSetup::KATEGORI_CORE_BUILDER) ? '<span class="notice" style="margin-top: -5px;">' . MMtrgSetup::getNotConfirmed(MMtrgSetup::KATEGORI_CORE_BUILDER) . ' data perlu tindakan</span>' : '-' ?>
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
        $daily_input_actual     = 0;
        $daily_input_plan       = 0;
        $monthly_input_plan     = 0;        
        $monthly_input_actual   = 0;  
        $monthly_achieve        = 0;      
        // print_r($data[3]);die;
        foreach ($data as $row) : ?>
        <?php
        if(str_word_count($row['grade']) > 1) {
            $ext    = explode(" ", $row['grade']);
            unset($ext[0]);
            $type   = implode(" ", $ext);
            $row['grade'] = $type;
        }

        if(in_array($row['grade'], ['Manual', 'PPC'])) {
            $daily_input_actual     = $row['jumlah_aktual'];
            $daily_input_plan       = $row['plan_harian'];
            $monthly_input_actual   = MMtrgSetup::getSummary('*', $row['jenis_proses'], ucwords(strtolower($row['jenis_kayu'])), $row['kategori_proses'])
                                    ->andWhere(['ILIKE', 'grade', $row['grade']])->sum("jumlah_aktual");
            $monthly_input_plan     = MMtrgSetup::getSummary('*', $row['jenis_proses'], ucwords(strtolower($row['jenis_kayu'])), $row['kategori_proses'])
                                    ->andWhere(['ILIKE', 'grade', $row['grade']])->sum("plan_harian");
        }

        // $monthly_achieve = $monthly_input_actual ? $row['jumlah_bulanan'] / ($row['plan_bulanan'] * $monthly_input_actual / 100) * 100 : 0;
        // echo "<pre>";
        // unset($row['data']);
        // print_r($row);
    
        ?>
        <tr <?= $row['grade'] === 'Sampah' ? 'style="opacity: .7"' : ''?>>
            <td><strong><?= $row['jenis_kayu']?></strong></td>
            <td style="text-align: left; <?= !in_array($row['grade'] , ['INPUT', 'OUTPUT', 'RENDEMEN']) ? 'text-indent: 2em' : ''?>"><?= $row['grade']?></td>
            <?php if(in_array($row['grade'], ['INPUT', 'OUTPUT'])) : ?>
                <td colspan="10"></td>
            <?php else: ?>


            <td colspan="<?= in_array($row['grade'], ['Manual', 'PPC']) ? '2' : ''?>">
                <?php 
                    $plan = $row['satuan_harian'] === 'm3' 
                            ? $row['plan_bulanan'] 
                            : $row['plan_bulanan'] / $hari_kerja;
                    echo DeltaFormatter::renderDataMonitoring($plan, $row['satuan_harian'], $row['grade'])
                ?>
            </td>
            <?php if(!in_array($row['grade'], ['Manual', 'PPC'])) :?>
            <td><?= DeltaFormatter::renderDataMonitoring(($row['plan_bulanan'] / $hari_kerja) * $monthly_input_plan / 100, 'm3', $row['grade']) ?></td>
            <?php endif; ?>


            <td colspan="<?= in_array($row['grade'], ['Manual', 'PPC']) ? '2' : ''?>">
                <?php
                if($monthly_input_actual != 0) {
                    if($row['jenis_proses'] === 'OUTPUT') {
                        $actual = $row['jumlah_bulanan']  / $monthly_input_actual * 100;
                    }else {
                        $actual = $monthly_input_actual;
                    }
                }else {
                    $actual = 0;
                }
                echo DeltaFormatter::renderDataMonitoring($actual, $row['satuan_harian'], $row['grade']);
                ?>
            </td>
            <?php if(!in_array($row['grade'], ['Manual', 'PPC'])) :?>
            <td><?= DeltaFormatter::renderDataMonitoring($row['jumlah_bulanan'], 'm3', $row['grade']) ?></td>
            <?php endif; ?>


            <?php 
                $monthly_achieve = $actual > 0 && $plan > 0 ? $actual / $plan * 100 : 0;
            ?>
            <td class="<?= $monthly_achieve < 100 ? 'warning' : 'achieved'?>"><?= DeltaFormatter::renderDataMonitoring($monthly_achieve, "%", $row['grade'])?></td>


            <td colspan="<?= in_array($row['grade'], ['Manual', 'PPC']) ? '2' : ''?>">
                <?php 
                    $plan = $row['plan_harian'];
                    echo DeltaFormatter::renderDataMonitoring($plan, $row['satuan_harian'], $row['grade'])
                ?>
            </td>
            <?php if(!in_array($row['grade'], ['Manual', 'PPC'])) :?>
            <td>
                <?= $daily_input_plan 
                    ? DeltaFormatter::renderDataMonitoring($row['plan_harian'] * $daily_input_plan / 100, 'm3', $row['grade']) 
                    : '0.00%'
                ?>
            </td>
            <?php endif; ?>
            <td colspan="<?= in_array($row['grade'], ['Manual', 'PPC']) ? '2' : ''?>">
                <?php 

                if($daily_input_actual != 0) {
                    if($row['satuan_harian'] === 'm3') {
                        $actual = $row['jumlah_aktual'];
                    }else {
                        $actual = $row['jumlah_aktual'] / $daily_input_actual * 100;
                    }
                }else {
                    $actual = 0;
                }
                    echo DeltaFormatter::renderDataMonitoring($actual, $row['satuan_harian'], $row['grade']);
                ?>
            </td>
            <?php if(!in_array($row['grade'], ['Manual', 'PPC'])) :?>
            <td>
                <?= DeltaFormatter::renderDataMonitoring($row['jumlah_aktual'], 'm3', $row['grade'])?>
            </td>
            <?php endif; ?>
            <?php 
                $daily_achieve = $actual > 0 && $plan > 0 ? $actual / $plan * 100 : 0;
            ?>
            <td class="<?= $daily_achieve < 100 ? 'warning' : 'achieved'?>"><?= DeltaFormatter::renderDataMonitoring($daily_achieve, "%", $row['grade'])?></td>
            <?php endif ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>