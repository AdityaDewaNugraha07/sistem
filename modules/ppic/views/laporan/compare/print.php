<?php
/* @var $this yii\web\View */
$this->title = 'Print ' . $paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan', ['paramprint' => $paramprint]);
if ($_GET['caraprint'] == "EXCEL") {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . ' - Per_' . (!empty($model->per_tanggal) ? $model->per_tanggal : date('d/m/Y')) . '.xls"');
    header('Cache-Control: max-age=0');
    $header = "";
}
?>

<style>
    table { border-collapse: collapse;width: 100%;}
    table, th, td { border: 1px solid #333;}
    th { text-align: center !important;}
</style>
<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $header; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <i>
                            <?php if($_GET['caraprint'] === 'EXCEL') : ?>
                            <h2>Cetak Compare Loglist dengan Penerimaan Log</h2>
                            <?php else: ?>
                            <h5 class="pull-right font-red-flamingo">Cetak Compare Loglist dengan Penerimaan Log</h5>
                            <?php endif ?>
                        </i>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet">
                            <div class="portlet-body">
                                <table>
                                    <thead>
                                        <tr>
                                            <th rowspan="3">No.</th>
                                            <th colspan="10">LOGLIST</th>
                                            <th rowspan="3" style="width: 10px;"></th>
                                            <th colspan="15" class='Jpenerimaan'>PENERIMAAN</th>
                                        </tr>
                                        <tr>
                                            <th class="td-kecil" colspan="3">Nomor</th>
                                            <th class="td-kecil" rowspan="2">Jenis Kayu</th>
                                            <th class="td-kecil" rowspan="2">Panjang</th>
                                            <th class="td-kecil" colspan="3">Cacat</th>
                                            <th class="td-kecil" rowspan="2">Diameter</th>
                                            <th class="td-kecil" rowspan="2">Volume</th>
                                            <th class="td-kecil" colspan="5">Nomor</th>
                                            <th class="td-kecil" rowspan="2">Jenis Kayu</th>
                                            <th class="td-kecil" rowspan="2">Panjang</th>
                                            <th class="td-kecil" colspan="4">Diameter</th>
                                            <th class="td-kecil" colspan="3">Cacat</th>
                                            <th class="td-kecil" rowspan="2">Volume</th>
                                        </tr>
                                        <tr>
                                            <th class="td-kecil">Grade</th>
                                            <th class="td-kecil">Batang</th>
                                            <th class="td-kecil">Produksi</th>
                                            <th class="td-kecil">Panjang</th>
                                            <th class="td-kecil">GB</th>
                                            <th class="td-kecil">GR</th>
                                            <th class="td-kecil">Grade</th>
                                            <th class="td-kecil">Batang</th>
                                            <th class="td-kecil">Produksi</th>
                                            <th class="td-kecil">Lapangan</th>
                                            <th class="td-kecil">QRCode</th>
                                            <th class="td-kecil">U1</th>
                                            <th class="td-kecil">U2</th>
                                            <th class="td-kecil">P1</th>
                                            <th class="td-kecil">P2</th>
                                            <th class="td-kecil">Panjang</th>
                                            <th class="td-kecil">GB</th>
                                            <th class="td-kecil">GR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= $html ?>
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