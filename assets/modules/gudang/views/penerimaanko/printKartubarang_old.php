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
<table style="width: 10cm; margin: 10px; height: auto;" border="1">
	<tr style="height: 1.2cm; border: solid 1px #000;">
		<td style="width: 1.5cm; border-right: solid 1px transparent; text-align: center;"><b><center>
			<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 45px;"> 	
		</center></b></td>
		<td colspan="2" style=" text-align: center;">
			<?= app\models\CCompanyProfile::findOne(\app\components\Params::DEFAULT_COMPANY_PROFILE)->name ?><br>
			<b style="font-size: 1.6rem;"><?= $paramprint['judul'] ?></b>
		</td>
		<td style="width: 1.5cm; border-left: solid 1px transparent; text-align: center;"><b><center>
			<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 45px;"> 	
		</center></b></td>
	</tr>
	<tr>
		<td colspan="4" style="height: 5px; line-height: 1px;"> </td>
	</tr>
	<tr>
		<td style="width: 2.3cm; vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Storage Code</td>
		<td style="width: 2.5cm; vertical-align: top; padding: 3px; border-right: solid 1px transparent;">: <?= $model->kode; ?></td>
		<td style="width: 2cm; vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;"><b>Product No.</td>
		<td style="width: 3.2cm; vertical-align: top; padding: 3px;">: <?= $model->nomor_produksi; ?></td>
	<tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Storage Date</td>
		<td style="vertical-align: top; padding: 3px; border-right: solid 1px transparent;">: <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></td>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;"><b>Product Date</td>
		<td style="vertical-align: top; padding: 3px;">: <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_produksi); ?></td>
	<tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Warehouse</td>
		<td style="vertical-align: top; padding: 3px; border-right: solid 1px transparent;">: <?= $model->gudang->gudang_kode; ?></td>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;"><b>Receive By</td>
		<td style="vertical-align: top; padding: 3px;">: <?= $model->petugasPenerima->pegawai_nama; ?></td>
	<tr>
	<tr>
		<td colspan="4" style="height: 5px; line-height: 1px;"> </td>
	</tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Prod Name</td>
		<td colspan="3" style="vertical-align: top; padding: 3px;">: <?= $model->produk->produk_nama; ?></td>
	<tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Prod Type</td>
		<td style="vertical-align: top; padding: 3px; border-right: solid 1px transparent;">: <?= $model->produk->produk_group; ?></td>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;"><b>Prod Code</td>
		<td style="vertical-align: top; padding: 3px;">: <?= $model->produk->produk_kode; ?></td>
	<tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Wood Type</td>
		<td style="vertical-align: top; padding: 3px; border-right: solid 1px transparent;">: <?= !empty($model->produk->produk_jenis_kayu)?$model->produk->produk_jenis_kayu:"-"; ?></td>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;"><b>Grade</td>
		<td style="vertical-align: top; padding: 3px;">: <?= $model->produk->grade ?></td>
	<tr>
	<tr>
		<td colspan="4" style="height: 5px; line-height: 1px;"> </td>
	</tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Dimension</td>
		<td style="vertical-align: top; padding: 3px; border-right: solid 1px transparent;">
			<table style="width: 100%">
				<tr>
					<td>p </td>
					<td> = <?= $model->produk->produk_p ?> <?= $model->produk->produk_p_satuan ?></td>
				</tr>
				<tr>
					<td>l </td>
					<td> = <?= $model->produk->produk_l ?> <?= $model->produk->produk_l_satuan ?></td>
				</tr>
				<tr>
					<td>t </td>
					<td> = <?= $model->produk->produk_t ?> <?= $model->produk->produk_t_satuan ?></td>
				</tr>
			</table>
		</td>
		<td colspan="2" style="vertical-align: top; padding: 3px 0px 3px 5px;">
			<table style="">
				<tr>
					<td>Pallet &nbsp; &nbsp; </td>
					<td> : <?= $model->qty_palet ?></td>
				</tr>
				<tr>
					<td>Smallest Unit &nbsp; &nbsp; </td>
					<td> : <?= $model->qty_kecil ?> <?= $model->qty_kecil_satuan ?></td>
				</tr>
				<tr>
					<td>Volume &nbsp; &nbsp; </td>
					<td> : <?= app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_m3) ?> m<sup>3</sup></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="4" style="height: 5px; line-height: 1px;"> </td>
	</tr>
	<tr>
		<td colspan="2" style="vertical-align: top; padding: 3px 0px 3px 5px; height: 1.2cm" ><b>Notes : <?= $model->keterangan ?></td>
		<td colspan="2" style="vertical-align: top; padding: 5px; ">
			<div class="place-barcode" style="text-align: center;"></div>
		</td>
	<tr>
	<tr>
		<td colspan="4" style="height: 5px; line-height: 1px;"> </td>
	</tr>
	<tr>
		<td colspan="4">
			<table style="width: 100%; font-size: 1.1rem; text-align: center; border-top: solid 1px transparent; border-bottom: solid 1px #000;" border="1">
				<tr style="height: 0.4cm;">
					<td style="width: 4.8cm; vertical-align: middle; border-left: solid 1px transparent;">Diketahui Oleh</td>
					<td style="vertical-align: middle; border-right: solid 1px transparent;">Dibuat Oleh</td>
				</tr>
				<tr>
					<td style="height: 35px; vertical-align: bottom; padding-left: 5px; text-align: left; border-left: solid 1px transparent;"></td>
					<td style="height: 35px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;"></td>
				</tr>
				<tr>
					<td style="height: 20px; vertical-align: middle; border-left: solid 1px transparent;"></td>
					<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;"><?= Yii::$app->user->getIdentity()->userProfile->fullname ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="4" style="font-size: 0.9rem; border: solid 1px transparent; ">
			<?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
		</td>
	</tr>
	<tr>
		<td colspan="4" style="height: 10px; line-height: 1px; border-left: solid 1px transparent; border-right: solid 1px transparent;"> </td>
	</tr>
	<tr>
		<td colspan="4" style="height: 10px; line-height: 1px; border-left: solid 1px transparent; border-right: solid 1px transparent; border-top: dashed 1px #000;"> </td>
	</tr>
	<tr style="">
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent;" ><b>Product Date</td>
		<td style="vertical-align: top; padding: 3px; border-right: solid 1px transparent;">: <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_produksi); ?></td>
		<td colspan="2" rowspan="3" style="vertical-align: top; padding: 5px; ">
			<div class="place-barcode" style="text-align: center;"></div>
		</td>
	</tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent; border-top: solid 1px transparent;" ><b>Storage Date</td>
		<td style="vertical-align: top; padding: 3px; border-right: solid 1px transparent; border-top: solid 1px transparent;">: <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></td>
	</tr>
	<tr>
		<td style="vertical-align: top; padding: 3px 0px 3px 5px; border-right: solid 1px transparent; border-top: solid 1px transparent;" ><b>Warehouse</td>
		<td style="vertical-align: top; padding: 3px; border-right: solid 1px transparent; border-top: solid 1px transparent;">: <?= $model->gudang->gudang_kode; ?></td>
	</tr>
</table>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-barcode.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    generateBarcode('".$model->kode."');
", yii\web\View::POS_READY); ?>
<script>
function generateBarcode(value,type="code128"){
    // http://barcode-coder.com/
    var settings = {
        output:"css",
        bgColor: "#FFFFFF",
        color: "#000000",
        barWidth: "1",
        barHeight: "40",
        moduleSize: "5",
        posX: "10",
        posY: "20",
        addQuietZone: "1"
    };
    $(".place-barcode").html("").show().barcode(value,type, settings);
}
</script>