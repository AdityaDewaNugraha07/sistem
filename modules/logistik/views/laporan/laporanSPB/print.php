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

$tglawal = \app\components\DeltaFormatter::formatDateTimeForUser2($model[0]['spb_tanggal']);
$tglakhir = \app\components\DeltaFormatter::formatDateTimeForUser2(end($model)['spb_tanggal']);

?>

<table style="border-collapse: collapse;">
    <thead>
            <tr>
                <th colspan="19" style="font-size: 28px; border: none;"><?= strtoupper($paramprint['judul']) ?></th>
            </tr>
            <tr>
                <th colspan="19">Periode <?= $tglawal ?> s/d <?= $tglakhir ?></th>
            </tr>
            <th rowspan="2" style="border: 1px solid black;"><?= Yii::t('app', 'No') ?></th>
            <th colspan="4" style="border: 1px solid black;">
                <?= Yii::t('app', 'SPB'); ?>
            </th>
            <th colspan="2" style="border: 1px solid black;">
                <?= Yii::t('app', 'Approval'); ?>
            </th>
            <th colspan="2" style="border: 1px solid black;">
                <?= Yii::t('app', 'SPP'); ?>
            </th>
            <th colspan="2" style="border: 1px solid black;">
                <?= Yii::t('app', 'SPL/SPO'); ?>
            </th>
            <th colspan="4" style="border: 1px solid black;">
                <?= Yii::t('app', 'TPB'); ?>
            </th>
            <th colspan="5" style="border: 1px solid black;">
                <?= Yii::t('app', 'BPB'); ?>
            </th>
            <tr>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Kode') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Tanggal') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Pegawai') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Departemen') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Approval 1') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Approval 2') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Kode') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Tanggal') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Kode') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Tanggal') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Kode') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Tanggal') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Checker') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Tanggal Checker') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Kode') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Dikeluarkan') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Tanggal Keluar') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Diterima') ?></th>
                <th style="border: 1px solid black;"><?= Yii::t('app', 'Tanggal Terima') ?></th>
            </tr>
        </tr> 
    </thead>
    <tbody>
        <?php use \app\components\DeltaFormatter; $no = 1; foreach($model as $data): ?>
            <tr>
                <td style="border: 1px solid black; text-align: center;"><?= $no ?></td>
                <td style="border: 1px solid black;"><?= $data['spb_kode'] ?></td>
                <td style="border: 1px solid black;"><?= DeltaFormatter::formatDateTimeForUser2($data['spb_tanggal']) ?></td>
                <td style="border: 1px solid black;"><?= $data['spb_pegawai_nama'] ?></td>
                <td style="border: 1px solid black;"><?= $data['departement_nama'] ?></td>
                <?php $approval = json_decode($data['spb_approval'], TRUE); ?>
                <td style="border: 1px solid black;">
                    <?= 
                        $approval[0]['detail']['approvedBy']['pegawai_nama'] 
                        ? $approval[0]['detail']['approvedBy']['pegawai_nama'] 
                        . ' (' . DeltaFormatter::formatDateTimeForUser2($approval[0]['master']['tanggal_approve']) . ')'
                        : '' 
                        ?>
                </td>
                <td style="border: 1px solid black;">
                    <?= 
                        $approval[1]['detail']['approvedBy']['pegawai_nama'] 
                        ? $approval[1]['detail']['approvedBy']['pegawai_nama'] 
                        . ' (' . DeltaFormatter::formatDateTimeForUser2($approval[1]['master']['tanggal_approve']) . ')'
                        : '' 
                        ?>
                </td>
                <td style="border: 1px solid black;"><?= $data['spp_kode'] ?></td>
                <td style="border: 1px solid black;"><?= DeltaFormatter::formatDateTimeForUser2($data['spp_tanggal']) ?></td>
                <td style="border: 1px solid black;"><?= $data['reff_no'] ?></td>
                <td style="border: 1px solid black;"><?= DeltaFormatter::formatDateTimeForUser2($data['reff_tanggal']) ?></td>
                <td style="border: 1px solid black;"><?= $data['bhp_kode'] ?></td>
                <td style="border: 1px solid black;"><?= DeltaFormatter::formatDateTimeForUser2($data['bhp_tanggal']) ?></td>
                <td style="border: 1px solid black;"><?= $data['bhp_pegawai_cek'] ?></td>
                <td style="border: 1px solid black;"><?= DeltaFormatter::formatDateTimeForUser2($data['bhp_tanggal_cek']) ?></td>
                <td style="border: 1px solid black;"><?= $data['bpb_kode'] ?></td>
                <td style="border: 1px solid black;"><?= $data['bpb_pegawai_keluar'] ?></td>
                <td style="border: 1px solid black;"><?= DeltaFormatter::formatDateTimeForUser2($data['bpb_tgl_keluar']) ?></td>
                <td style="border: 1px solid black;"><?= $data['bpb_pegawai_terima'] ?></td>
                <td style="border: 1px solid black;"><?= DeltaFormatter::formatDateTimeForUser2($data['bpb_tgl_terima']) ?></td>
            </tr>
        <?php $no++; endforeach; ?>
    </tbody>
</table>