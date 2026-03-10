<?php
/* @var $this yii\web\View */
$this->title = 'Print ' . $paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan', ['paramprint' => $paramprint]);
if ($_GET['caraprint'] == "EXCEL") {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . ' - Rev_' . time() . '.xls"');
    header('Cache-Control: max-age=0');
    $header = $paramprint['judul']." ".$paramprint['judul2'];
}
?>
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
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-rekap">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Tanggal</th>
                                            <!-- <th rowspan="2">Kode</th> -->
                                            <th rowspan="2">Reff No</th>
                                            <th rowspan="2">Peruntukan</th>
                                            <th rowspan="2">PIC</th>
                                            <th rowspan="2">Jenis Kayu</th>
                                            <th colspan="4">Nomor</th>
                                            <th rowspan="2" style="width: 40px;">Kode<br>Potong</th>
                                            <th rowspan="2">Panjang (m)</th>
                                            <th colspan="5">Diameter (cm)</th>
                                            <th colspan="3">Cacat (cm)</th>
                                            <th rowspan="2">Volume (m<sup>3</sup>)</th>
                                            <th rowspan="2">Status FSC</th>
                                        </tr>
                                        <tr>
                                            <th>QRcode</th>
                                            <th>Grade</th>
                                            <th>Lapangan</th>
                                            <th>Batang</th>
                                            <th>Ujung 1</th>
                                            <th>Ujung 2</th>
                                            <th>Pangkal 1</th>
                                            <th>Pangkal 2</th>
                                            <th>Rata Rata</th>
                                            <th>Panjang</th>
                                            <th>Gubal</th>
                                            <th>Growong</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($model as $key => $row): ?>
                                            <tr>
                                                <td><?= $key + 1 ?></td>
                                                <td><?= $row['tgl_transaksi'] ?></td>
                                                <!-- <td><?php // $row['kode'] ?></td> -->
                                                <td><?= $row['reff_no'] ?></td>
                                                <td><?= $row['cara_keluar'] ?></td>
                                                <td><?= $row['pegawai_nama'] ?></td>
                                                <td><?= $row['kayu_nama'] ?></td>
                                                <td><?= $row['no_barcode'] ?></td>
                                                <td><?= $row['no_grade'] ?></td>
                                                <td><?= $row['no_lap'] ?></td>
                                                <td><?= $row['no_btg'] ?></td>
                                                <td><?= $row['pot'] ?></td>
                                                <td><?= $row['fisik_panjang'] ?></td>
                                                <td><?= $row['diameter_ujung1'] ?></td>
                                                <td><?= $row['diameter_ujung2'] ?></td>
                                                <td><?= $row['diameter_pangkal1'] ?></td>
                                                <td><?= $row['diameter_pangkal2'] ?></td>
                                                <td><?= $row['fisik_diameter'] ?></td>
                                                <td><?= $row['cacat_panjang'] ?></td>
                                                <td><?= $row['cacat_gb'] ?></td>
                                                <td><?= $row['cacat_gr'] ?></td>
                                                <td><?= $row['fisik_volume'] ?></td>
                                                <td><?= $row['fsc']?'FSC 100%':'Non FSC' ?></td>
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