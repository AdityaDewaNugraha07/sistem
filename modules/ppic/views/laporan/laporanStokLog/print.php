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
    $header = $paramprint['judul']." ". $paramprint['judul2'];
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
                                            <th rowspan="2"><?= Yii::t('app', 'No.'); ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Kayu') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'No. QRcode') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'No. Grade') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'No. Lap') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'No. Batang') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Pcs') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Panjang<br>(m)') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Kode Potong') ?></th>
                                            <th colspan="5"><?= Yii::t('app', 'Diameter (cm)') ?></th>
                                            <th colspan="3"><?= Yii::t('app', 'Cacat (cm)') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Volume<br>(m<sup>3</sup>)') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Status FSC') ?></th> <!-- TAMBAH FSC -->
                                        </tr>
                                        <tr>
                                            <th>Ujung 1</th>
                                            <th>Ujung 2</th>
                                            <th>Pangkal 1</th>
                                            <th>Pangkal 2</th>
                                            <th>Rata-rata</th>
                                            <th>Panjang</th>
                                            <th>Gubal</th>
                                            <th>Growong</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($model as $key => $data): ?>
                                            <tr>
                                                <td><?= $key + 1 ?></td>
                                                <td><?= $data['kayu_nama'] ?></td>
                                                <td><?= $data['no_barcode'] ?></td>
                                                <td><?= $data['no_grade'] ?></td>
                                                <td><?= $data['no_lap'] ?></td>
                                                <td><?= $data['no_btg'] ?></td>
                                                <td><?= $data['fisik_pcs'] ?></td>
                                                <td><?= $data['fisik_panjang'] ?></td>
                                                <td><?= $data['pot'] ?></td>
                                                <td><?= $data['diameter_ujung1'] ?></td>
                                                <td><?= $data['diameter_ujung2'] ?></td>
                                                <td><?= $data['diameter_pangkal1'] ?></td>
                                                <td><?= $data['diameter_pangkal2'] ?></td>
                                                <td><?= ($data['diameter_ujung1'] + $data['diameter_ujung2'] + $data['diameter_pangkal1'] + $data['diameter_pangkal2']) / 4 ?></td>
                                                <td><?= $data['cacat_panjang'] ?></td>
                                                <td><?= $data['cacat_gb'] ?></td>
                                                <td><?= $data['cacat_gr'] ?></td>
                                                <td><?= $data['fisik_volume'] ?></td>
                                                <td><center><?= $data['fsc']?'FSC 100%':'Non FSC' ?></center></td> <!-- TAMBAH FSC -->
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                    <!-- <tfoot>
                                        <tr>
                                            <th colspan="6" style="text-align: right;">Total Per Page</th>
                                            <th id="pcs_page"></th>
                                            <th colspan="10"></th>
                                            <th id="m3_page" class="text-right"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="6" class="text-right">Total All Page</th>
                                            <th id="total_pcs" class="text-center"></th>
                                            <th colspan="10"></th>
                                            <th id="total_m3" class="text-right"></th>
                                        </tr>
                                    </tfoot> -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>