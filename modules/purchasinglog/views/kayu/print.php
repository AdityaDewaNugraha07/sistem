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
					<th style="padding: 10px; border: solid 1px #000;"><?= Yii::t('app', 'Kelompok Kayu') ?></th>
					<th style="padding: 10px; border: solid 1px #000;"><?= Yii::t('app', 'Nama Kayu') ?></th>
					<th style="padding: 10px; border: solid 1px #000;"><?= Yii::t('app', 'Nama Lain'); ?></th>
					<th style="padding: 10px; border: solid 1px #000;"><?= Yii::t('app', 'Nama Ilmiah'); ?></th>
					<th style="padding: 10px; border: solid 1px #000;"><?= Yii::t('app', 'Status'); ?></th>
				</tr>
				
				<?php 
				foreach ($model as $key) {
					$group_kayu = $key['group_kayu'];
					$kayu_nama = $key['kayu_nama'];
					$kayu_othername = $key['kayu_othername'];
					$nama_ilmiah = $key['nama_ilmiah'];
					$active = $key['active'];
					$active == 1 ? $status = 'Active' : $status = 'Non-Active'
				?>
				<tr>
					<td style="padding: 3px; border: solid 1px #000;"><?= $group_kayu; ?></td>
					<td style="padding: 3px; border: solid 1px #000;"><?= $kayu_nama; ?></td>
					<td style="padding: 3px; border: solid 1px #000;"><?= $kayu_othername; ?></td>
					<td style="padding: 3px; border: solid 1px #000;"><?= $nama_ilmiah; ?></td>
					<td style="padding: 3px; border: solid 1px #000;"><?= $status; ?></td>
				</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
</table>