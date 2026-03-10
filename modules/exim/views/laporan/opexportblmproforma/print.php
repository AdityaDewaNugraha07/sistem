<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporanBootstrap',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>

<?php

$tglawal = \app\components\DeltaFormatter::formatDateTimeForUser2($model[0]['tanggal']);
$tglakhir = \app\components\DeltaFormatter::formatDateTimeForUser2(end($model)['tanggal']);

?>

<table style="border-collapse: collapse;" border="1">
    <thead>
            <tr>
                <th colspan="14" style="font-size: 28px; border: none;"><?= strtoupper($paramprint['judul']) ?></th>
            </tr>
            <tr>
                <th colspan="14">Periode <?= $tglawal ?> s/d <?= $tglakhir ?></th>
            </tr>
            <th rowspan="2"><?= Yii::t('app', 'No'); ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Nomor Order'); ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Nomor Kontrak'); ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Jenis Produk'); ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Tanggal'); ?></th>
            <th colspan="2"><?= Yii::t('app', 'Applicant'); ?></th>
            <th colspan="2"><?= Yii::t('app', 'Notify Party'); ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Payment<br>Method'); ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Term of Price'); ?></th>
            <th rowspan="2"><?= Yii::t('app', 'HS Code'); ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Nomor SVLK'); ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Keterangan'); ?></th>
            <tr>
                <th><?= Yii::t('app', 'Nama') ?></th>
                <th><?= Yii::t('app', 'Alamat') ?></th>
                <th><?= Yii::t('app', 'Nama') ?></th>
                <th><?= Yii::t('app', 'Alamat') ?></th>
            </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach($model as $row): ?>
            <tr>
                <td style="text-align: center;"><?= $no ?></td>
                <td style="text-align: center;"><?= $row['kode'] ?></td>
                <td style="text-align: center;"><?= $row['nomor_kontrak'] ?></td>
                <td style="text-align: center;"><?= $row['jenis_produk'] ?></td>
                <td style="text-align: center;"><?= $row['tanggal'] ?></td>
                <td><?= $row['cust_an_nama'] ?></td>
                <td><?= $row['cust_an_alamat'] ?></td>
                <td><?= $row['notify_nama'] ?></td>
                <td><?= $row['notify_alamat'] ?></td>
                <td style="text-align: center;"><?= $row['payment_method'] ?></td>
                <td style="text-align: center;"><?= $row['term_of_price'] ?></td>
                <td style="text-align: center;"><?= $row['hs_code'] ?></td>
                <td style="text-align: center;"><?= $row['svlk_no'] ?></td>
                <td style="text-align: center;"><?= $row['cancel_reason'] ?></td>
            </tr>
        <?php $no++; endforeach; ?>
    </tbody>
</table>