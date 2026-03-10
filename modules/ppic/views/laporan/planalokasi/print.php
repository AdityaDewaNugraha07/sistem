<?php
/* @var $this yii\web\View */
$this->title = 'Print ' . $paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan', ['paramprint' => $paramprint]);
if ($_GET['caraprint'] == "EXCEL") {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . '-'. date('d/m/Y') .'.xls"');
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
                                            <th class='td-kecil'><?= Yii::t('app', 'Jenis Alokasi'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Jml Batang'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Jml Kubikasi'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $query = $model->searchLaporan()->all();
                                        if(!empty($query)>0){
                                            foreach($query as $i => $data){ ?>
                                                <tr>
                                                    <td class='td-kecil'><?= $data->jenis_alokasi ?></td>
                                                    <td class='td-kecil'><?= $data->kayu_nama ?></td>
                                                    <td class='td-kecil text-align-right'><?= $data->pcs ?></td>
                                                    <td class='td-kecil text-align-right'><?= app\components\DeltaFormatter::formatNumberForUserFloat($data->kubikasi, 2) ?></td>
                                                </tr>
                                        <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="4" class="td-kecil text-align-center">Data tidak ditemukan</td>
                                            </tr>
                                        <?php } ?>
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