<?php
/* @var $this yii\web\View */
/** @var array $paramprint */

use app\models\THasilRepacking;

$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
//$header = "Laporna";
if($_GET['caraprint'] === "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
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
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 10px;"><?= Yii::t('app', 'Kode') ?></th>
                                            <th style="font-size: 10px;"><?= Yii::t('app', 'Tanggal')?></th>
                                            <th style="font-size: 10px;"><?= Yii::t('app', 'Jenis Produk') ?></th>
                                            <th style="font-size: 10px;"><?= Yii::t('app', 'KBJ') ?> / <?= Yii::t('app','Nama Produk')?></th>
                                            <th style="font-size: 10px;"><?= Yii::t('app', 'Qty') ?></th>
                                            <th style="font-size: 10px;"><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                            <th style="font-size: 10px;"><?= Yii::t('app', 'Diserahkan{br}Oleh', ['br' => ' ']) ?></th>
                                            <th style="font-size: 10px;"><?= Yii::t('app', 'Penerimaan{br}Gudang', ['br' => ' ']) ?></th>
                                            <th style="font-size: 10px;"><?= Yii::t('app', 'Lokasi{br}Gudang', ['br' => ' ']) ?></th>
                                            <th style="font-size: 10px;"><?= Yii::t('app', 'Diterima{br}Oleh', ['br' => ' ']) ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php /** @var THasilRepacking $model */
                                        foreach($model as $data): ?>
                                            <tr>
                                                <td style="font-size: 10px; vertical-align: middle;">
                                                    <strong><?= $data['kode'] ?></strong>
                                                </td>
                                                <td style="font-size: 10px; vertical-align: middle;">
                                                    <?= $data['tanggal'] ?>
                                                </td>
                                                <td style="font-size: 10px; vertical-align: middle;"><?= $data['produk_group'] ?></td>
                                                <td style="font-size: 10px; vertical-align: middle;">
                                                    <?= $data['produk_nama'] ?>
                                                </td>
                                                <td style="font-size: 10px; vertical-align: middle;"><?= $data['qty_kecil'] ?></td>
                                                <td style="font-size: 10px; vertical-align: middle;"><?= $data['qty_m3'] ?></td> 
                                                <td style="font-size: 10px; vertical-align: middle;"><?= $data['diserahkan'] ?></td> 
                                                <td style="font-size: 10px; vertical-align: middle;"><strong><?= $data['nomor_produksi_kirim'] ?></strong></td>
                                                <td style="font-size: 10px; vertical-align: middle;"><?= $data['gudang_nm'] ?></td>
                                                <td style="font-size: 10px; vertical-align: middle;"><?= $data['petugas_terima'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>