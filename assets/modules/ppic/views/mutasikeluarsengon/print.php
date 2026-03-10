<?php
/* @var $this yii\web\View */
$title = 'Print '.$paramprint['judul']." PERIODE ".$tanggal_awal." - ".$tanggal_akhir;
$this->title = $title;
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
if($_GET['caraprint'] == "EXCEL"){
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' PERIODE '.$tanggal_awal.' - '.$tanggal_akhir.'.xls"');
    header('Cache-Control: max-age=0');
    $header = "";
}
?>

<style>
table{
    font-size: 1.2rem;
}
table#table-detail{
    font-size: 1rem;
}
table#table-detail tr td{
    vertical-align: top;
}
</style>


<table style="width: 20cm; margin: 10px;">
    <tr>
        <th style='text-align: center; height: 50px;'><?php echo $paramprint['judul'];?> PERIODE <?php echo $tanggal_awal." s/d. ".$tanggal_akhir;?></th>
    </tr>
    <tr>
        <td colspan="3" style="padding: 0px; border: solid 1px;">
            <table style="width: 100%" id="table-detail">
                <tr style="border-bottom: 1px solid black; background-color: #F1F4F7">
                    <th class="td-kecil2" rowspan="2" style="width: 1cm; text-align: center; border: 1px solid;">No.</th>
                    <th class="td-kecil2" rowspan="2" style="width: 3cm; text-align: center; border: 1px solid;">Jenis Mutasi</th>
                    <th class="td-kecil2" colspan="2" style="width: 3cm; text-align: center; border: 1px solid;">Mutasi</th>
                    <th class="td-kecil2" rowspan="2" style="width: 2cm; text-align: center; border: 1px solid;">Kode Mutasi</th>
                    <th class="td-kecil2" rowspan="2" style="width: 2cm; text-align: center; border: 1px solid;">Tanggal Mutasi</th>
                    <th class="td-kecil2" rowspan="2" style="width: 2cm; text-align: center; border: 1px solid;">Panjang<br>(cm)</th>
                    <th class="td-kecil2" rowspan="2" style="width: 2cm; text-align: center; border: 1px solid;">Diameter<br>(cm)</th>
                    <th class="td-kecil2" rowspan="2" style="width: 1cm; text-align: center; border: 1px solid;">Pcs<br>(btg)</th>
                    <th class="td-kecil2" rowspan="2" style="width: 1cm; text-align: center; border: 1px solid;">Volume<br>M<sup>3</sup></th>
                </tr>
                <tr style="border-bottom: 1px solid black; background-color: #F1F4F7">
                    <th class="td-kecil2" style="width: 3cm; text-align: center; border: 1px solid;">Dari</th>
                    <th class="td-kecil2" style="width: 3cm; text-align: center; border: 1px solid;">Ke</th>
                </tr>
                <?php
                $i = 1;
                foreach ($model as $value) {
                ?>
                <tr>
                    <td style="padding: 3px; text-align: center;"><?php echo $i; ?></td>                    
                    <td style="padding: 3px; "><?php echo $value->jenis_mutasi; ?></td>
                    <td style="padding: 3px; "><?php echo $value->dari; ?></td>
                    <td style="padding: 3px; "><?php echo $value->ke;?></td>
                    <td style="padding: 3px; text-align: center;"><?php echo $value->kode; ?></td>
                    <td style="padding: 3px; text-align: center;"><?= \app\components\DeltaFormatter::formatDateTimeForUser($value->tanggal); ?></td>
                    <td style="padding: 3px; text-align: right;"><?php echo $value->panjang; ?></td>
                    <td style="padding: 3px; text-align: right;"><?php echo $value->diameter; ?></td>
                    <td style="padding: 3px; text-align: right;"><?php echo $value->pcs; ?></td>
                    <td style="padding: 3px; text-align: right;"><?php echo $value->m3; ?></td>
                <?php
                    $i = $i + 1;
                }
                ?>
           </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; height: 20px; vertical-align: top;">
            <?php
            echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
            echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
            ?>
            <span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-PPC-29</span>
        </td>
    </tr>
</table>