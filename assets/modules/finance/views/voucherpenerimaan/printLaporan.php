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
	padding: 2px;
	font-size: 1.1rem;
}
</style>
<table style="width: 20cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 10px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 70px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 2rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
						<?= (isset($model[0]['tanggal'])?"<i>Tanggal ". app\components\DeltaFormatter::formatDateTimeForUser(substr($model[0]['tanggal']."</i>", 0,10)):"") ?>
					</td>
					<td style="width: 5.5cm; height: 1cm; vertical-align: top; padding: 10px;"></td>
				</tr>
			</table>
			<table style="width: 100%;" border="1" id="table-detail">
				<thead>
					<tr style=" background-color: #F1F4F7;">
						<th style="width: 50px;">No.</th>
						<th style="line-height: 1;">Kode<br>Voucher</th>
						<th style="line-height: 1;">Kode<br>BBM</th>
						<th style="line-height: 1;">Jenis<br>Voucher</th>
						<th style="line-height: 1;">Akun<br>Kredit</th>
						<th style="line-height: 1;">Sender</th>
						<th style="line-height: 1;">Deskripsi</th>
						<th style="width: 90px; line-height: 1;">Nominal</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$totalkredit = 0;
					$contain_usd = false;
					$nominal = 0;
					if(count($model)>0){ 
						foreach($model as $i => $data){ 
						$contain_usd += ($data['mata_uang']=="USD")?TRUE:FALSE;
						if($data['mata_uang']=="USD"){
							$contain_usd += TRUE;
							$nominal = $data['total_nominal']*$usd;
						}else{
							$contain_usd += FALSE;
							$nominal = $data['total_nominal'];
						}
						?>
						<tr>
							<td style="text-align: center;"><?= $i+1; ?></td>
							<td class="text-align-center"><?= $data['kode'] ?></td>
							<td class="text-align-center"><?= $data['kode_bbm'] ?></td>
							<td class="text-align-center"><?= $data['tipe'] ?></td>
							<td class="text-align-center"><?= $data->akunKredit->acct_nm ?></td>
							<td><?= $data['sender']; ?></td>
							<td><?= $data['deskripsi']; ?></td>
							<td class="text-align-right"><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat($nominal):$nominal; ?></td>
						</tr>
					<?php
					$totalkredit += $nominal;
					}
					}else{
						echo "<tr ><td colspan='8' style='text-align:center'>".Yii::t('app', 'Data tidak ditemukan')."</td></tr>";
					}
					?>
					<tr>
						<td class="" colspan="7" style="font-size: 1.2rem;">
							<?php
							if($contain_usd == TRUE){
								echo '<i style="font-size: 1rem;">Kurs Tengah : Rp. '.\app\components\DeltaFormatter::formatNumberForUserFloat($usd).'</i>';
							}
							?>
							<span class="pull-right" style="font-weight: bold;">TOTAL</span>
						</td>
						<td class=" text-align-right" style="font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( $totalkredit ):$totalkredit; ?></td>
					</tr>
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
					<td style="vertical-align: middle; width: 122px; background-color: #F1F4F7;">Disetujui Oleh</td>
					<td style="vertical-align: middle; width: 123px; background-color: #F1F4F7;">Diperiksa Oleh</td>
				</tr>
				<tr>
					<td style="height: 55px; vertical-align: bottom; font-size: 0.8rem; text-align: center;"><i>Kadep Finance</i></td>
					<td style="height: 55px; vertical-align: bottom; font-size: 0.8rem; text-align: center; border-right: solid 1px transparent;"><i>Staff Finance</i></td>
				</tr>
				<tr style="background-color: #F1F4F7;">
					<td style="height: 20px; vertical-align: middle;">
						
					</td>
					<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;  ">
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
			<!--<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-FIN-07-0</span>-->
		</td>
	</tr>
</table>