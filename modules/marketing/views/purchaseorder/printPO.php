<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<style>
table{
	font-size: 1.2rem;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
.t {border-top: solid 1px;}
.b {border-bottom: solid 1px;}
.l {border-left: solid 1px;}
.r {border-right: solid 1px;}
</style>
<table style="width: 20cm; margin: 10px;">
    <tr>
        <td rowspan="4" style="width: 90px;" class="text-center t b l">
            <!-- <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default text-center" style="width: 80px; margin: 20px;"> -->
        </td>
        <td rowspan="4" colspan="10" class="t b " style="padding-left: 185px;">
            <span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span>
		</td>
        <td style="width: 110px; padding-left: 30px; font-weight: bold; padding-top: 30px;" class="t"></td>
        <td style="width: 110px;  padding-top: 30px;" class="t r"></td>
    </tr>
    <tr>
        <td style="padding-left: 30px; font-weight: bold;"></td>
        <td class="r"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td class="r">&nbsp;</td>
    </tr>
    <tr>
        <td class="b">&nbsp;</td>
        <td class="b r">&nbsp;</td>
    </tr>
    <tr>
        <td style="vertical-align: top; padding-top: 10px; padding-left: 10px;" class="l">Kepada</td>
        <td colspan="5" style="width: 240px; vertical-align: top; padding-top: 10px;"></td>
        <td colspan="5" style="vertical-align: top; padding-top: 10px; padding-left: 47px;">Pembeli</td>
        <td colspan="2" style="vertical-align: top; padding-top: 10px;" class="r">:</td>
    </tr>
    <tr>
        <td colspan="7" style="vertical-align: top; padding-left: 10px;" class="l">Yth : Bp. <?= $model->sales->sales_nm; ?></td>
        <td colspan="5" style="vertical-align: top; padding-left: 10px;"><?= (!empty($model->cust->cust_pr_nama))?$model->cust->cust_pr_nama:$model->cust->cust_an_nama ?></td>
        <td style="vertical-align: top;" class="r"></td>
    </tr>
    <tr>
        <td colspan="7" style="vertical-align: top; padding-left: 10px;" class="l">
            JL Raya semarang - Purwodadi Km. 16 No. 349 Rt. 002 Rw. 004 
            Kembangarum Mranggen Kab. Demak - Jawa Tengah
        </td>
        <td colspan="5" style="vertical-align: top; padding-left: 10px;"><?= (!empty($model->cust->cust_pr_alamat)?$model->cust->cust_pr_alamat:$model->cust->cust_an_alamat); ?></td>
        <td style="vertical-align: top;" class="r"></td>
    </tr>
    <tr>
        <td colspan="7" style="vertical-align: top; padding-left: 10px;" class="l"></td>
        <td colspan="4" style="vertical-align: top; padding-left: 10px; padding-top: 10px;">Alamat Bongkar</td>
        <td colspan="2" style="vertical-align: top; padding-top: 10px;" class="r">:</td>
    </tr>
    <tr>
        <td colspan="7" style="vertical-align: top; padding-left: 10px;" class="l"></td>
        <td colspan="5" style="vertical-align: top; padding-left: 10px; "><?= $model->alamat_bongkar; ?></td>
        <td style="vertical-align: top;" class="r"></td>
    </tr>
    <tr>
        <td colspan="7" style="vertical-align: top; padding-left: 10px;" class="l"></td>
        <td colspan="4" style="vertical-align: top; padding-left: 10px; padding-top: 10px;">Rencana Pengiriman</td>
        <td colspan="2" style="vertical-align: top; padding-top: 10px;" class="r">: <?= app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal_kirim); ?></td>
    </tr>
    <tr>
        <td colspan="7" style="vertical-align: top; padding-left: 10px;" class="l"></td>
        <td colspan="4" style="vertical-align: top; padding-left: 10px;">Sistem Pembayaran</td>
        <td colspan="2" style="vertical-align: top;" class="r">: 
            <?= (!empty($model->keterangan_bayar))?$model->cara_bayar.'<br>&nbsp; '.$model->keterangan_bayar:$model->cara_bayar; ?>
        </td>
    </tr>
    <?php 
    if($model->sistem_bayar == 'Tempo'){
    ?>
    <tr>
        <td colspan="7" style="vertical-align: top; padding-left: 10px;" class="l"></td>
        <td colspan="4" style="vertical-align: top; padding-left: 10px;">Maks Tgl Bayar</td>
        <td colspan="2" style="vertical-align: top;" class="r">: <?= app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal_bayarmax); ?></td>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="7" style="vertical-align: top; padding-left: 10px;" class="l"></td>
        <td colspan="4" style="vertical-align: top; padding-left: 10px;">Syarat Penyerahan</td>
        <td colspan="2" style="vertical-align: top; padding-bottom: 10px;" class="r">: <?= $model->syarat_jual; ?></td>
    </tr>
    <tr>
        <td colspan="13" class="b l r" style="width: 200px; padding-left: 10px; padding-bottom: 20px;">
            Dengan hormat,<br>
            Guna persediaan bahan baku perusahaan kami, sudilah kiranya bapak bersedia memenuhi order pembelian log bagi kami sbb:
        </td>
    </tr>
    <tr style="height: 30px; line-height: 30px;">
        <td colspan="4" class="text-center t b l r" style="font-weight: bold; width: 200px;">Jenis</td>
        <td colspan="2" class="text-center t b l r" style="font-weight: bold; width: 120px;">Volume</td>
        <td colspan="2" class="text-center t b l r" style="font-weight: bold; width: 20px;">Diameter</td>
        <td colspan="3" class="text-center t b l r" style="font-weight: bold; width: 30px;">%</td>
        <td colspan="2" class="text-center t b l r" style="font-weight: bold; width: 50px;">Harga</td>
    </tr>
    <?php 
    foreach($modDetail as $i => $detail){
    ?>
    <tr>
        <td colspan="4" class="t b l r" style="width: 200px; padding-left: 10px;"><?= $detail->produk_alias; ?></td>
        <td colspan="2" class="text-center t b l r" style="width: 120px;"><?= $detail->kubikasi; ?> m<sup>3</sup></td>
        <td colspan="2" class="text-center t b l r" style="width: 20px;">
            <?php
            if (strpos($detail->diameter_alias, '-') !== false) {
                $diameter = explode('-', $detail->diameter_alias);
                $range_akhir = $diameter[1] == 200?' UP':'-' .$diameter[1];
                echo $diameter[0] . $range_akhir;
            } else {
                echo $detail->diameter_alias;
            }
            ?>
        </td>
        <td colspan="3" class="text-center t b l r" style="width: 30px;"><?= $detail->komposisi; ?>%</td>
        <td colspan="2" class="text-right t b l r" style="width: 50px; padding-right: 10px;">Rp<?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga); ?></td>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="13" class="l r" style="width: 200px; padding-left: 10px; padding-top: 5px; padding-bottom: 10px;">
            <?php if(!empty($model->keterangan)){ ?>
                <b>Keterangan :</b><br> <?= $model->keterangan ?>
            <?php } else { 
                echo "&nbsp;<br>&nbsp;";
            } ?>
        </td>
    </tr>
    <tr>
        <td colspan="13" class="l r" style="padding-left: 10px; padding-top: 15px; padding-bottom: 20px;">
            Atas ketersediaan Bapak untuk memenuhi pesanan tersebut, kami ucapkan terima kasih.
        </td>
    </tr>
    <tr>
        <td colspan="6" class="text-center l" style="padding-left: 10px;">
            <?= $model->kota_cust?$model->kota_cust:'Semarang'; ?>, <?= app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal_po); ?><br>
            Hormat kami
        </td>
        <td class="text-center"></td>
        <td colspan="6" class="text-center r"></td>
    </tr>
    
    <tr>
        <td colspan="6" class="text-center b l" style="padding-left: 10px; padding-bottom: 30px;">
            Pembeli,
            <br><br><br><br>
            <?php
                echo "<span style='font-size:0.9rem'>
                        <span style='margin-right: 50px;'>(</span>
                        <span style='margin-left: 50px;'>)</span>
                      </span><br>";
                echo "<span style='font-size:0.9rem'>". $model->cust->cust_pr_nama ."</span><br>";
            ?>
        </td>
        <td class="text-center b"></td>
        <td colspan="6" class="text-center b r" style="padding-left: 50px; padding-bottom: 30px;">
            Penjual,

            <br><br><br><br>
            <?php
                echo "<span style='font-size:0.9rem'>( ".$model->sales->sales_nm." )</span><br>";
                echo "<span style='font-size:0.9rem;'>PT. Cipta Wijaya Mandiri</span>";
            ?>
        </td>
    </tr>

    <tr>
		<td colspan="13"style="vertical-align: bottom; font-size: 0.9rem;">
            <?php
			// echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			// echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
		</td>
    </tr>

    
</table>
