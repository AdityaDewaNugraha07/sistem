<?php
/* @var $this yii\web\View */

/** @var array $paramprint */

use app\models\MSuplier;

$this->title = 'Print ' . $paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan', ['paramprint' => $paramprint]);
if ($_GET['caraprint'] === "EXCEL") {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . ' - ' . date("d/m/Y") . '.xls"');
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
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th style="width: 250px;"><?= Yii::t('app', 'Nama') ?></th>
                                        <th style="width: 250px;"><?= Yii::t('app', 'Perusahaan') ?></th>
                                        <th><?= Yii::t('app', 'Alamat') ?></th>
                                        <th><?= Yii::t('app', 'Keterangan') ?></th>
                                        <th><?= Yii::t('app', 'Status') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    /** @var MSuplier $model */
                                    $sql = $model->searchLaporan()->createCommand()->rawSql;
                                    try {
                                        $contents = Yii::$app->db->createCommand($sql)->queryAll();
                                        if (count($contents) > 0) {
                                            foreach ($contents as $i => $data) {
                                                if ($data['suplier_phone'] !== '') {
                                                    $telephone = ", Phone($data[suplier_phone])";
                                                } else {
                                                    $telephone = "";
                                                }
                                                ?>
                                                <tr>
                                                    <td style="text-align: center;"><?= $i + 1 ?></td>
                                                    <td class=""><?= $data['suplier_nm'] ?></td>
                                                    <td class=""><?= $data['suplier_nm_company'] ?></td>
                                                    <td class=""><?= $data['suplier_almt'] . "" . $telephone ?></td>
                                                    <td style="font-size: 1.1rem;"><?= $data['suplier_ket'] ?></td>
                                                    <td class="">
                                                        <?php
                                                        if ($data['active']) {
                                                            echo "Aktif";
                                                        } else {
                                                            echo "Non-Aktif";
                                                        }
                                                        ?>
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