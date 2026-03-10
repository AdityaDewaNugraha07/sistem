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
	vertical-align: top;
	border-bottom: solid 1px transparent;
}
#table-detail{
	border-left: solid 1px transparent; 
	border-right: solid 1px transparent;
}
#table-detail thead tr th{
	padding: 5px;
	text-align: center;
	font-size: 1.3rem;
}
#table-detail tbody tr td{
	vertical-align: top;
	padding: 3px;
	font-size: 1.1rem;
}
</style>
<table style="width: 20cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3">
			<table style="width: 100%; " border="0">
				<tr>
					<td colspan="3" style="padding: 5px;">
						<table style="width: 100%; " border="0">
							<tr style="">
								<td style="text-align: left; vertical-align: middle; padding: 0px; width: 2cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
									<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 70px;"> 	
								</td>
								<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
									<span style="font-size: 2rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
								</td>
								<td style="width: 5cm; height: 1cm; vertical-align: top; padding: 10px;">
									<table style="width: 100%;">
										<tr>
											<td style="width:2cm;"><b>No.</b></td>
											<td>: &nbsp; <?= (isset($modDetail[0]['kode'])?$modDetail[0]['kode']:""); ?> </td>
										</tr>
										<tr>
											<td><b>Tanggal</b></td>
											<td>: &nbsp; <?= (isset($modDetail[0]['tanggal'])?app\components\DeltaFormatter::formatDateTimeForUser2(substr($modDetail[0]['tanggal'], 0,10)):"") ?> </td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table style="width: 100%;" border="1" id="table-detail">
				<thead>
					<tr style="background-color: #F1F4F7; border-right: 1px solid transparent; border-left: 1px solid transparent;">
						<th rowspan="2" style="width: 0.5cm; "><center>No.</center></th>
						<th rowspan="2" style="width: 3cm; "><center>Nama Customer</center></th>
						<th rowspan="2" style="width: 1.5cm;  line-height:13px"><center>No. Bukti</center></th>
						<th colspan="2" style=""><center>Bank</center></th>
						<th rowspan="2" style="width: 2cm; "><center>No. BG/Cek</center></th>
						<th rowspan="2" style="width: 2.5cm;  line-height:13px"><center>Tgl Jatuh<br>Tempo</center></th>
						<th rowspan="2" style="width: 2.5cm; "><center>Nominal</center></th>
						<th rowspan="2" style="max-width: 5cm; "><center>Keterangan</center></th>
					</tr>
					<tr style="background-color: #F1F4F7; border-right: 1px solid transparent; border-left: 1px solid transparent;">
						<th style="width: 1.5cm; "><center>Nama</center></th>
						<th style="width: 2.5cm; "><center>No. Acct</center></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$max = 15;
					if(count($modDetail) > $max){
						$max = count($modDetail);
					}
					for($i=0;$i<$max;$i++){
					if( count($modDetail) >= ($i+1) ){
					?>
					<tr style="border-left: 1px solid transparent; border-right: 1px solid transparent; font-size: 1.1rem;">
						<td style="vertical-align: top;">
							<center><?= $i+1; ?></center>
						</td>
						<td style="vertical-align: top;">
							<?= $modDetail[$i]->nama_customer ?>
						</td>
						<td style="vertical-align: top;">
							<center><?= $modDetail[$i]->no_bukti; ?></center>
						</td>
						<td style="vertical-align: top;">
							<center><?= $modDetail[$i]->cust_bank; ?></center>
						</td>
						<td style="vertical-align: top;">
							<center><?= $modDetail[$i]->cust_acct; ?></center>
						</td>
						<td style="vertical-align: top;">
							<center><?= $modDetail[$i]->reff_number; ?></center>
						</td>
						<td style="vertical-align: top;">
							<center><?= app\components\DeltaFormatter::formatDateTimeForUser2($modDetail[$i]->tanggal_jatuhtempo); ?></center>
						</td>
						<td style="text-align: right; vertical-align: top;">
							<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->nominal); ?> &nbsp; 
						</td>
						<td style="vertical-align: top; max-width: 5cm; ">
							<center><?= $modDetail[$i]->keterangan; ?></center>
						</td>
					</tr>
				<?php }else{ ?>
					<tr>
						<td style="padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px;">&nbsp;</td>
					</tr>
				<?php } ?>
				<?php } ?>
				</tbody>
			</table>
			<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
				<tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
					<td rowspan="3" style="width: 14cm; vertical-align: bottom; text-align: left; font-size: 0.9rem;">
						<?php
						echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
						echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
						?>
					</td>
					<td style="vertical-align: middle; width: 130px; background-color: #F1F4F7;">Diperiksa Oleh</td>
					<td style="vertical-align: middle; width: 130px; background-color: #F1F4F7;">Disetujui Oleh</td>
					<td style="vertical-align: middle; width: 130px; background-color: #F1F4F7;">Dibuat Oleh</td>
				</tr>
				<tr>
					<td style="height: 55px; vertical-align: bottom; font-size: 0.8rem; text-align: center;"><i>Staff Bank</i></td>
					<td style="height: 55px; vertical-align: bottom; font-size: 0.8rem; text-align: center;"><i>Kadep Finance</i></td>
					<td style="height: 55px; vertical-align: bottom; font-size: 0.8rem; text-align: center; border-right: solid 1px transparent;"><i>Kasir</i></td>
				</tr>
				<tr style="background-color: #F1F4F7;">
					<td style="height: 20px; vertical-align: middle;"></td>
					<td style="height: 20px; vertical-align: middle;"></td>
					<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;  "></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-FIN-03-0</span>
		</td>
	</tr>
</table>