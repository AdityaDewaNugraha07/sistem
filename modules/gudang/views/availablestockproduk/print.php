<?php
/* @var $this yii\web\View */
/* @var HPersediaanProduk $model */
/* @var array $paramprint */

use app\models\HPersediaanProduk;

$this->title = 'Print ' . $paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan', ['paramprint' => $paramprint]);
if ($_GET['caraprint'] === "EXCEL") {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . ' - Per_' . (!empty($model->per_tanggal) ? $model->per_tanggal : date('d/m/Y')) . '.xls"');
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
                        <?php echo $header; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <i><h5 class="pull-right font-red-flamingo"></h5></i>
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
                                        <th><?= Yii::t('app', 'No.') ?></th>
                                        <th><?= Yii::t('app', 'Jenis Produk') ?></th>
                                        <?php if(in_array($model->produk_group, ['Plywood', 'Lamineboard', 'Platform', 'Sawntimber', 'Moulding', ''], true)): ?>
                                        <th><?= Yii::t('app', 'Jenis Kayu') ?></th>
                                        <?php endif ?>
                                        <?php if(in_array($model->produk_group, ['Plywood', 'Lamineboard', 'Platform', 'Sawntimber', 'Moulding', 'Veneer', ''], true)): ?>
                                        <th><?= Yii::t('app', 'Grade') ?></th>
                                        <?php endif ?>
                                        <?php if(in_array($model->produk_group, ['Plywood', 'Lamineboard', 'Platform', ''], true)): ?>
                                        <th><?= Yii::t('app', 'Glue') ?></th>
                                        <?php endif ?>
                                        <?php if(in_array($model->produk_group, ['Sawntimber', ''], true)): ?>
                                        <th><?= Yii::t('app', 'Kondisi Kayu') ?></th>
                                        <?php endif ?>
                                        <?php if(in_array($model->produk_group, ['Moulding', ''], true)): ?>
                                        <th><?= Yii::t('app', 'Profil Kayu') ?></th>
                                        <?php endif ?>
                                        <th><?= Yii::t('app', 'Kode') ?></th>
                                        <th><?= Yii::t('app', 'Nama Produk') ?></th>
                                        <th><?= Yii::t('app', 'Dimensi') ?></th>
                                        <th><?= Yii::t('app', 'Total Palet') ?></th>
                                        <th><?= Yii::t('app', 'Total Qty') ?></th>
                                        <th><?= Yii::t('app', 'Total M<sup>3</sup>') ?></th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = $model->searchLaporan()->createCommand()->rawSql;
                                    try {
                                        $contents = Yii::$app->db->createCommand($sql)->queryAll();
                                        if (count($contents) > 0) {
                                            foreach ($contents as $i => $data) { ?>
                                                <tr>
                                                    <td style="text-align: center;"><?= $i + 1 ?></td>
                                                    <td><?= $data['produk_group'] ?></td>
                                                    <?php if(in_array($model->produk_group, ['Plywood', 'Lamineboard', 'Platform', 'Sawntimber', 'Moulding', ''], true)): ?>
                                                    <td><?= $data['jenis_kayu'] ?></td>
                                                    <?php endif ?>
                                                    <?php if(in_array($model->produk_group, ['Plywood', 'Lamineboard', 'Platform', 'Sawntimber', 'Moulding', 'Veneer', ''], true)): ?>
                                                    <td style="text-align: center"><?= $data['grade'] ?></td>
                                                    <?php endif ?>
                                                    <?php if(in_array($model->produk_group, ['Plywood', 'Lamineboard', 'Platform', ''], true)): ?>
                                                    <td><?= $data['glue'] ?></td>
                                                    <?php endif ?>
                                                    <?php if(in_array($model->produk_group, ['Sawntimber', ''], true)): ?>
                                                    <td><?= $data['kondisi_kayu'] ?></td>
                                                    <?php endif ?>
                                                    <?php if(in_array($model->produk_group, ['Moulding', ''], true)): ?>
                                                    <td><?= $data['profil_kayu'] ?></td>
                                                    <?php endif ?>
                                                    <td><?= $data['produk_kode'] ?></td>
                                                    <td><?= $data['produk_nama'] ?></td>
                                                    <td><?= $data['produk_dimensi'] ?></td>
                                                    <td class="text-right"><?= $data['palet'] ?></td>
                                                    <td class="text-right"><?= $data['qty_kecil'] ?></td>
                                                    <td style="text-align: right">
                                                        <?php echo number_format($data['kubikasi'], 4); ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else {
                                            "<tr colspan='5'>" . Yii::t('app', 'Data tidak ditemukan') . "</tr>";
                                        }
                                    } catch (\yii\db\Exception $e) {
                                        echo $e->getMessage();
                                    }
                                    ?>
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