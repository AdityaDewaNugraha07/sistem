<?php
/* @var $this yii\web\View */

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
	font-size: 1.1rem;
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
		<td colspan="3" style="padding: 5px; border-bottom: solid 1px transparent;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 4cm; height: 1cm; border-bottom: solid 1px transparent; border: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span>
					</td>
					<td style="width: 3cm; height: 1cm; vertical-align: top; padding: 10px;">
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
				<tr style="border-bottom: solid 1px #000; border-top: solid 1px #000;">
					<th style="padding: 10px; border: solid 1px #000;"><?= Yii::t('app', 'Kode') ?></th>
					<th style="padding: 10px; border: solid 1px #000;"><?= Yii::t('app', 'Nama') ?></th>
					<th style="padding: 10px; border: solid 1px #000;"><?= Yii::t('app', 'Alamat'); ?></th>
					<th style="padding: 10px; border: solid 1px #000;"><?= Yii::t('app', 'Status'); ?></th>
				</tr>
				
				<?php 
				foreach ($model as $key) {
					$kode = $key['kode'];
					$nama = $key['nama'];
					$alamat = $key['alamat'];
					$active = $key['active'];
					$active == 1 ? $status = 'Active' : $status = 'Non-Active'
				?>
				<tr>
					<td style="padding: 3px; border: solid 1px #000;"><?= $kode; ?></td>
					<td style="padding: 3px; border: solid 1px #000;"><?= $nama; ?></td>
					<td style="padding: 3px; border: solid 1px #000;"><?= $alamat; ?></td>
					<td style="padding: 3px; border: solid 1px #000;"><?= $status; ?></td>
				</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
	<?php /* <tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; border-top: solid 1px #000; height: 20px; vertical-align: top;">
			<?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-12-0</span>
		</td>
	</tr>
	<tr style="border: solid 1px transparent; border-top: solid 1px #000;">
		<td colspan="3" style="border: solid 1px transparent;">
			<table style="width: 100%; font-size: 1.1rem; text-align: center;">
				<tr style="height: 0.4cm;  ">
					<td rowspan="3" style="vertical-align: middle;">&nbsp;</td>
					<td style="vertical-align: middle; width: 4cm; ">Disetujui Oleh</td>
					<td style="vertical-align: middle; width: 4cm; ">Dibuat Oleh</td>
				</tr>
				<tr>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;"></td>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;"></td>
				</tr>
				<tr>
					<td style="height: 20px; vertical-align: middle; line-height: 1">
						<?php
							echo "<span style='font-size:0.9rem'><b><u> ".app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_ASENG)->pegawai_nama." </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>General Manager</span>";
						?>
					</td>
					<td style="height: 20px; vertical-align: middle; line-height: 1">
						<?php
							echo "<span style='font-size:0.9rem'><b><u> ".app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO)->pegawai_nama." </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>Kadiv Marketing</span>";
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr> */?>
</table>