<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporanPpk',['paramprint'=>$paramprint,'model'=>$model]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<table style="width: 20cm; margin: 5px;" border="1" >
	<tr style="">
		<td colspan="2" style="vertical-align: top;">
			<?php echo $header; ?>
		</td>
	</tr>
	<tr style="border-top: solid 1px transparent;">
		<td style="width: 15cm; "></td>
		<td style="width: 5cm; vertical-align: top; padding: 3px; border-left: solid 1px transparent;">
			<table style="font-size: 1.3rem;">
				<tr>
					<td style="width: 40%;">No.</td>
					<td>:&nbsp; <?= $model->kode; ?></td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td>:&nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="border-top: solid 1px transparent;">
		<td colspan="2" style="vertical-align: top; padding: 10px">
			<table style="font-size: 1.3rem; width: 100%; margin-bottom: 20px">
				<tr>
					<td style="width: 18%; padding-left: 5px">Jumlah</td>
					<td style="width: 2%;">: </td>
					<td style="width: 80%; padding-left: 5px; border-bottom: solid 1px grey;"><?= app\components\DeltaFormatter::formatUang($model->nominal) ?></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Terbilang</td>
					<td style="width: 2%;">: </td>
					<td style="padding-left: 5px; border-bottom: solid 1px grey;"><b><i><?= app\components\DeltaFormatter::formatNumberTerbilang($model->nominal) ?></i></b></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Keperluan</td>
					<td style="width: 2%;">: </td>
					<td style="padding-left: 5px; border-bottom: solid 1px grey;"><?= $model->keperluan; ?></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Tanggal Diperlukan</td>
					<td style="width: 2%;">: </td>
					<td style="padding-left: 5px; border-bottom: solid 1px grey;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_diperlukan); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="vertical-align: bottom; border-top: solid 1px transparent;">
			<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000;" border="1">
				<tr style="height: 0.4cm;">
					<td style="width: 12cm; vertical-align: middle; border-left: solid 1px transparent; border-bottom: solid 1px transparent;"></td>
					<td style="width: 4cm; vertical-align: middle;">Disetujui Oleh</td>
					<td style="width: 4cm; vertical-align: middle; border-right: solid 1px transparent;">Dibuat Oleh</td>
				</tr>
				<tr>
					<td style="width: 12cm; vertical-align: middle; border-left: solid 1px transparent; border-bottom: solid 1px transparent;"></td>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;">Tgl :</td>
				</tr>
				<tr>
					<td style="width: 12cm; vertical-align: middle; border-left: solid 1px transparent; border-bottom: solid 1px transparent;"></td>
					<td style="height: 20px; vertical-align: middle; border-bottom: solid 1px transparent;"></td>
					<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent; border-bottom: solid 1px transparent;"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div style="font-size: 0.9rem;">
<?php
echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
?>
</div>