<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
isset($_GET['jp']) ? $jp = $_GET['jp'] : $jp = 'Platform';
isset($_GET['tp']) ? $tp = $_GET['tp'] : $tp = '2020-01-01';

if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>

<style>
table{
	font-size: 1.1rem;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>

<table style="width: 790px; margin: 10px; height: 10cm;" border="0">
	<tr>
		<td colspan="3" style="padding: 5px; border-bottom: solid 1px transparent;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 4cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
						<?= $paramprint['judul2'] ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">
						<table style="width: 100%; border: solid 1px;" id="table-detail">
							<tr style="border-bottom: solid 1px #000; border-top: solid 1px #000;">
								<th style="width: 50px; text-align: center;  padding: 10px; border-right: solid 1px #000;">No.</th>
								<th style="width: 260px; text-align: center;  padding: 10px; border-right: solid 1px #000;">Produk Nama</th>
								<th style="width: 200px; text-align: center;  padding: 10px; border-right: solid 1px #000;">Kode</th>
								<th style="width: 200px; text-align: center;  padding: 10px; border-right: solid 1px #000;">Dimensi</th>
								<th style="width: 80px; text-align: center;  padding: 10px; border-right: solid 1px #000;">Harga</th>
							</tr>
							<?php
							$i = 1;
							$total = 0;
							foreach ($model as $key) {
								$produk_nama = $key['produk_nama'];
								$produk_kode = $key['produk_kode'];
								$produk_dimensi = $key['produk_dimensi'];
								$harga_enduser = $key['harga_enduser'];
							?>
							<tr>
								<td style="padding: 2px 5px; border-right: solid 1px #000; line-height: 20px; text-align: center;"><?php echo $i;?></td>
								<td style="padding: 2px 5px; border-right: solid 1px #000; line-height: 20px;"><?php echo $produk_nama;?></td>
								<td style="padding: 2px 5px; border-right: solid 1px #000; line-height: 20px;"><?php echo $produk_kode;?></td>
								<td style="padding: 2px 5px; border-right: solid 1px #000; line-height: 20px;"><?php echo $produk_dimensi;?></td>
								<td style="padding: 2px 5px; border-right: solid 1px #000; line-height: 20px; text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($harga_enduser);?></td>
							</tr>
							<?php
								$i++;
								$total = $total + $harga_enduser;
							}
							?>
							<tr>
								<td style="padding: 2px 5px; border-right: solid 1px #000; line-height: 20px; text-align: center;"><b>Total</b></td>
								<td style="padding: 2px 5px; border-right: solid 1px #000; line-height: 20px; text-align: right;" colspan="4"><b><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total);?></b></td>
							</tr>
						</table>						
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<br>
<br>
<br>
<br>
