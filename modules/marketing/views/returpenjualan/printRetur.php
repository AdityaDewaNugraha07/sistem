<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$kode = $model->kode;
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
</style>
<table style="width: 20cm; margin: 10px; height: 10cm;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: middle; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
						<?= $model->jenis_produk ?>
					</td>
					<td style="width: 5.5cm; height: 1cm; vertical-align: top; padding: 10px;">
						<table>
							<tr>
								<td><b>Kode Retur</b></td>
								<td>: &nbsp; <?= $model->kode; ?></td>
							</tr>
							<tr>
								<td><b>Tanggal</b></td>
								<td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></td>
							</tr>
							<tr>
								<td style="width:2.2cm;"><b>Nota Terkait</b></td>
								<td>: &nbsp; <?= $model->notaPenjualan->kode; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 8px; background-color: #F1F4F7;">
			<table style="width: 100%">
				<tr>
					<td style="width: 60%; vertical-align: top; padding-left: 10px;">	
						<table>
							<tr>
								<td style="width: 3cm; vertical-align: top;"><b>Customer</b></td>
								<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
								<td style="width: 6cm; vertical-align: top;">
									<?php
										echo $model->customer->cust_an_nama." <br>";
										echo $model->customer->cust_an_alamat;
									?>
								</td>
							</tr>
						</table>
					</td>
					<td style="width: 40%; vertical-align: top; padding-left: 10px;">
						<table>
							<tr>
								<td style="width: 4.5cm; vertical-align: top;"><b>Nopol Kendaraaan</b></td>
								<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
								<td style="width: 6cm; vertical-align: top;"><?= $model->kendaraan_nopol ?></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Nama Supir</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><?= $model->kendaraan_supir ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
				<tr style="height: 0.5cm; border-bottom: solid 1px #000;">
					<td rowspan="2" style="padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Produk</center></b></td>
					<td colspan="2" style="border-right: solid 1px #000;"><b><center>Qty Order</center></b></td>
					<td rowspan="2" style="width: 3cm; border-right: solid 1px #000; vertical-align: middle; text-align: right;"><b><center> Harga Retur</center></b></td>
					<td rowspan="2" style="width: 4cm; vertical-align: middle;"><b><center>Subtotal</center></b></td>
				</tr>
				<tr style="border-bottom: solid 1px #000;">
					<td style="width: 2.5cm; border-right: solid 1px #000;"><b><center>Satuan Kecil</center></b></td>
					<td style="width: 1.5cm; border-right: solid 1px #000;"><b><center>M<sup>3</sup></center></b></td>
				</tr>
				<?php
				$max = 4;
				if(count($modDetail) > $max){
					$max = count($modDetail);
				}
				$total_besar = 0;
				$total_kecil = 0;
				$total_kubik = 0;
				$subtotal=0; $total_rp=0;
				?>
				<?php for($i=0;$i<$max;$i++){
					if( count($modDetail) >= ($i+1) ){
						$total_besar += $modDetail[$i]->qty_besar;
						$total_kecil += $modDetail[$i]->qty_kecil;
						$total_kubik += $modDetail[$i]->kubikasi;
						if($model->notaPenjualan->jenis_produk == "Plywood" || $model->notaPenjualan->jenis_produk == "Lamineboard" || $model->notaPenjualan->jenis_produk == "Platform"){
							$subtotal = round($modDetail[$i]->harga_retur * $modDetail[$i]->qty_kecil,0);
						}else{
							$subtotal = round($modDetail[$i]->harga_retur * $modDetail[$i]->kubikasi,0);
						}
						$total_rp += $subtotal;
				?>
					<tr>
						<td style="padding: 2px 5px; border-right: 1px solid black;"><?= $modDetail[$i]->produk->produk_nama; ?></td>
						<td style="padding: 2px 5px; border-right: solid 1px #000;">
							<span style="float: right;">
								<?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_kecil) ?> 
								<i>(<?= $modDetail[$i]->satuan_kecil ?>)</i>
							</span>
						</td>
						<td style="padding: 2px 5px; border-right: solid 1px #000;">
							<span style="float: right">
								<?= number_format($modDetail[$i]->kubikasi,4) ?>
							</span> 
						</td>
						<td style="padding: 2px 5px; border-right: solid 1px #000;">
							<span style="float: left">Rp.</span> 
							<span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->harga_retur) ?></span> 
						</td>
						<td style="padding: 2px 5px; ">
							<span style="float: left">Rp.</span> 
							<span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat(round($subtotal)) ?></span>
						</td>
					</tr>
				<?php }else{ ?>
					<tr>
						<td style="padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px;">&nbsp;</td>
					</tr>
				<?php } ?>
				<?php } ?>
				<tr style="border-top: solid 1px #000; border-bottom: solid 1px transparent; background-color: #F1F4F7;" >
					<td colspan="4" class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total Bayar</b> &nbsp;</td>
					<td class="text-align-right" style="padding: 5px;"><b>
						<span style="float: left">Rp.</span> 
						<span style="float: right"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat(round($total_rp)) ?></span>
					</b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="border-bottom: solid 1px transparent;">
		<td colspan="3" style=" border-top: solid 1px transparent;">
			<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
				<tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
					<td style="width: 16cm; text-align: left; border-bottom: solid 1px transparent;">
						<b>Terbilang :</b>
					</td>
					<td style="vertical-align: middle; width: 4cm; background-color: #F1F4F7;">Dibuat Oleh</td>
				</tr>
				<tr>
					<td style="font-size:1.4rem; text-align: left; vertical-align: top;">
						<b><i><?= app\components\DeltaFormatter::formatNumberTerbilang(round($total_rp)); ?></i></b>
					</td>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;"></td>
				</tr>
				<tr>
					<td style="vertical-align: bottom; font-size: 0.9rem; text-align: left; border-top: solid 1px transparent;">
						<?php
						echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
						echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
						?>
					</td>
					<td style="background-color: #F1F4F7; height: 20px; vertical-align: middle;  border-right: solid 1px transparent;  ">
						<?php
						if(!empty($model->created_by)){
							echo "<span style='font-size:0.9rem'>".\app\models\MPegawai::findOne(app\components\Params::DEFAULT_PEGAWAI_ID_RYA)->pegawai_nama."</span>";
						}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>