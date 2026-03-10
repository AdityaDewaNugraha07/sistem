<?php

use app\components\DeltaFormatter;
use app\models\MDefaultValue;

$this->title = 'Print '.$paramprint['judul'];
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] === "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}

function getKeySize($value)
{
    foreach(MDefaultValue::getOptionList('size') as $key => $size) {
        if($key === $value) {
            return $size;
        }
    }
}
?>

<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php if($_GET['caraprint'] === 'PRINT'): ?>
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
                                            <th class="text-center">Tanggal Kupas</th>
                                            <th class="text-center">Tanggal Produksi</th>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Shift</th>
                                            <th class="text-center">Status I/O</th>
                                            <th class="text-center">Kategori Proses</th>
                                            <th class="text-center">Jenis Kayu</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Grade</th>
                                            <th class="text-center">Tebal</th>
                                            <th class="text-center">Size</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Volume</th>
                                            <th class="text-center">Patching</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($model as $row):?>
                                            <tr>
                                                <td class="text-center"><?= $no++;?></td>
                                                <td class="text-center"><?= DeltaFormatter::formatDateTimeForUser2($row['tanggal_kupas']);?></td>
                                                <td class="text-center"><?= DeltaFormatter::formatDateTimeForUser2($row['tanggal_produksi']);?></td>
                                                <td class="text-center"><?= $row['kode'];?></td>
                                                <td class="text-center"><?= $row['shift'];?></td>
                                                <td class="text-center"><?= $row['status_in_out'];?></td>
                                                <td class="text-center"><?= $row['kategori_proses'];?></td>
                                                <td class="text-center"><?= $row['jenis_kayu'];?></td>
                                                <td class="text-center"><?= $row['unit'];?></td>
                                                <td class="text-center"><?= $row['grade'];?></td>
                                                <td class="text-center"><?= $row['tebal'];?></td>
                                                <td class="text-center"><?= getKeySize($row['size']);?></td>
                                                <td class="text-center"><?= $row['pcs'];?></td>
                                                <td class="text-center"><?= $row['volume'];?></td>
                                                <td class="text-center"><?= $row['patching'];?></td>
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