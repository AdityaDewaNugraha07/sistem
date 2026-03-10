<?php

use app\models\TTerimaLogalamDetail;

$kode = Yii::$app->db->createCommand("select kode from t_log_keluar where log_keluar_id = ".$log_keluar_id."")->queryScalar();
$modLogKeluar = \app\models\TLogKeluar::findall(['kode'=>$kode]);
$i = 1;
foreach ($modLogKeluar as $kolomLogKeluar) {
    $no_barcode = $kolomLogKeluar->no_barcode;
    $modTerimaLogalamDetail = \app\models\ViewTerimaLogalamPabrik::findOne(["no_barcode"=>$no_barcode]);
    $no_lap = $modTerimaLogalamDetail->no_lap;
    $no_grade = $modTerimaLogalamDetail->no_grade;
    $no_btg = $modTerimaLogalamDetail->no_btg;
    $nama_kayu = Yii::$app->db->createCommand("select kayu_nama from m_kayu where kayu_id = ".$modTerimaLogalamDetail->kayu_id."")->queryScalar();
    $panjang = $modTerimaLogalamDetail->panjang;
    $kode_potong = $modTerimaLogalamDetail->kode_potong;
    $diameter_ujung1 = $modTerimaLogalamDetail->diameter_ujung1;
    $diameter_ujung2 = $modTerimaLogalamDetail->diameter_ujung2;
    $diameter_pangkal1 = $modTerimaLogalamDetail->diameter_pangkal1;
    $diameter_pangkal2 = $modTerimaLogalamDetail->diameter_pangkal2;
    $cacat_panjang = $modTerimaLogalamDetail->cacat_panjang;
    $cacat_gb = $modTerimaLogalamDetail->cacat_gb;
    $cacat_gr = $modTerimaLogalamDetail->cacat_gr;
    $volume = $modTerimaLogalamDetail->volume;
    $modsdetail = TTerimaLogalamDetail::findOne($modTerimaLogalamDetail->terima_logalam_detail_id);
    $fsc = $modsdetail->fsc?'FSC 100%':'Non FSC';
    ?>
    <tr>
        <td style="line-height: 30px;text-align: center;"><?php echo $i;?></td>
        <td style="text-align: center;"><?php echo $no_barcode;?></td>
        <td style="text-align: center;"><?php echo $no_lap;?></td>
        <td style="text-align: center;"><?php echo $no_grade;?></td>
        <td style="text-align: center;"><?php echo $no_btg;?></td>
        <td style="text-align: left; padding-left: 3px;"><?php echo $nama_kayu;?></td>
        <td style="text-align: right; padding-left: 3px;"><?php echo $panjang;?></td>
        <td style="text-align: center;"><?php echo $kode_potong;?></td>
        <td style="text-align: right; padding-right: 3px;"><?php echo $diameter_ujung1;?></td>
        <td style="text-align: right; padding-right: 3px;"><?php echo $diameter_ujung2;?></td>
        <td style="text-align: right; padding-right: 3px;"><?php echo $diameter_pangkal1;?></td>
        <td style="text-align: right; padding-right: 3px;"><?php echo $diameter_pangkal2;?></td>
        <td style="text-align: right; padding-right: 3px;"><?php echo $cacat_panjang;?></td>
        <td style="text-align: right; padding-right: 3px;"><?php echo $cacat_gb;?></td>
        <td style="text-align: right; padding-right: 3px;"><?php echo $cacat_gr;?></td>
        <td style="text-align: right; padding-right: 3px;"><?php echo $volume;?></td>
        <td style="text-align: right; padding-right: 3px;"><?php echo $fsc;?></td>
    </tr>
    <?php
    $i++;
}
?>
<?php $this->registerJs(" 
", yii\web\View::POS_READY); ?>
