<?php

use app\components\DeltaFormatter;

$this->title = 'Print ' . $paramprint['judul'];
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan', ['paramprint' => $paramprint]);
if ($_GET['caraprint'] === "EXCEL") {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . ' - ' . date("d/m/Y") . '.xls"');
    header('Cache-Control: max-age=0');
    $header = "";
}

function menitToJam($value)
{
    $jam = floor($value / 60);
    $menit = $value % 60;
    return $menit === 0 ? $jam . ' jam' : $jam . ' jam ' . $menit . ' menit';
}

?>

<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($_GET['caraprint'] === 'PRINT') : ?>
                            <?= $header ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Shift</th>
                                            <th class="text-center">Jenis Kayu</th>
                                            <th class="text-center">Jam Jalan</th>
                                            <th class="text-center">Suplier</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Diameter</th>
                                            <th class="text-center">Panjang</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Volume</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($model as $row) : ?>
                                            <tr>
                                                <td class="text-center"><?= $no++; ?></td>
                                                <td class="text-center"><?= DeltaFormatter::formatDateTimeForUser2($row['tanggal']); ?></td>
                                                <td class="text-center"><?= $row['kode']; ?></td>
                                                <td class="text-center"><?= $row['shift']; ?></td>
                                                <td class="text-center"><?= $row['jenis_kayu']; ?></td>
                                                <td class="text-center"><?= menitToJam($row['jam_jalan']); ?></td>
                                                <td class="text-center"><?= $row['suplier_nm']; ?></td>
                                                <td class="text-center"><?= $row['unit']; ?></td>
                                                <td class="text-center"><?= $row['diameter']; ?></td>
                                                <td class="text-center"><?= $row['panjang']; ?></td>
                                                <td class="text-center"><?= $row['pcs']; ?></td>
                                                <td class="text-center"><?= $row['volume']; ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>