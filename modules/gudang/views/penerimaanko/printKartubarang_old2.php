<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
table{
	font-size: 1.0rem;
}
</style>
<table style="width: 9cm; margin: 10px; height: auto;" border="1">
	<tr style="height: 1.2cm; border: solid 1px #000;">
		<td style="border-right: solid 1px transparent; text-align: center;"><b><center>
			<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 45px;"> 	
		</center></b></td>
		<td colspan="3" style="text-align: center;">
			<b style="font-size: 1.6rem; margin-left: -50px;"><?= $paramprint['judul'] ?></b>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="height: 10px; line-height: 1px; border-right: solid 1px transparent;"> </td>
		<td colspan="2" rowspan="10" style="vertical-align: top; padding: 10px;">
			<div class="place-qrcode" style="text-align: center;"></div>
		</td>
	</tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Name</td>
		<td style="vertical-align: top; padding: 3px;">: &nbsp; <?= $model->produk->produk_nama; ?> (<?= $model->produk->grade ?>)</td>
	<tr>
	<tr>
		<td colspan="2" style="height: 10px; line-height: 1px; border-right: solid 1px transparent;"> </td>
	</tr>
	<tr>
		<td style="width: 2cm; vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;"><b>Item No.</td>
		<td style="width: 4.5cm; vertical-align: top; padding: 3px; ">: &nbsp; <?= $model->nomor_produksi; ?></td>
	<tr>
	<tr>
		<td colspan="2" style="height: 10px; line-height: 1px; border-right: solid 1px transparent;"> </td>
	</tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Prod Date</td>
		<td style="vertical-align: top; padding: 3px; border-left: solid 1px transparent;">: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_produksi); ?></td>
	<tr>
	<tr>
		<td colspan="2" style="height: 10px; line-height: 1px; border-right: solid 1px transparent;" > </td>
	</tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Dimension</td>
		<td colspan="3" style="vertical-align: top; padding: 3px;">
			<table style="width: 100%;">
				<tr>
					<td style="width: 5%">: </td>
					<td><i>Length</i></td>
					<td> = &nbsp; <?= $model->produk->produk_p ?> <?= $model->produk->produk_p_satuan ?></td>
					<td style="width: 1.3cm;"><i>Pallet</i> &nbsp; &nbsp; </td>
					<td> = &nbsp; <?= $model->qty_palet ?></td>
				</tr>
				<tr>
					<td> </td>
					<td><i>Width </td>
					<td> = &nbsp; <?= $model->produk->produk_l ?> <?= $model->produk->produk_l_satuan ?></td>
					<td><i>Qty</i> &nbsp; &nbsp; </td>
					<td> = &nbsp; <?= $model->qty_kecil ?> <?= $model->qty_kecil_satuan ?></td>
				</tr>
				<tr>
					<td> </td>
					<td><i>Thickness </td>
					<td> = &nbsp; <?= $model->produk->produk_t ?> <?= $model->produk->produk_t_satuan ?></td>
					<td><i>Volume</i> &nbsp; &nbsp; </td>
					<td> = &nbsp; <?= app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_m3) ?> m<sup>3</sup></td>
				</tr>
			</table>
		</td>
<!--		<td colspan="2" style="vertical-align: top; padding: 3px; border-left: solid 1px transparent;">
			<table style="width: 100%">
				<tr>
					
				</tr>
				<tr>
					
				</tr>
				<tr>
					
				</tr>
			</table>
		</td>-->
	</tr>
</table>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery.qrcode.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    jQuery('.place-qrcode').qrcode({fill: '#666',width: 80,height: 80, text: '".$barcodecontent."' });
//    jQuery('.place-qrcode').qrcode({width: 80,height: 80, text: [\"".$model->nomor_produksi."\"]});
", yii\web\View::POS_READY); ?>
<script>
</script>