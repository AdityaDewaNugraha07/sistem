<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
table{
	font-size: 1.2rem;
}
#place-dimensi{
	font-size: 1.2rem;
}
</style>
<table style="width: 13.5cm; margin: 10px; height: 9cm;" border="1">
	<tr style="height: 1.5cm; border: solid 1px #000;">
		<!--<td style="border-right: solid 1px transparent; text-align: center;"><b><center>-->
			<!--<img src="<?php // echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 60px;">--> 	
		<!--</center></b></td>-->
		<td colspan="5" style="text-align: center;">
			<b style="font-size: 2.8rem;"><?= $paramprint['judul'] ?></b>
		</td>
	</tr>
	<tr style="">
		<td colspan="2" style="height: 10px; line-height: 1px; border-right: solid 1px transparent; border-top: solid 1px #000;"> </td>
	</tr>
	<tr>
		<td style="width: 2.5cm; height: 30px; vertical-align: middle; padding: 3px 0px 3px 10px; border-right: solid 1px transparent;" ><b>Product Type</td>
		<td style="width: 3.5cm; vertical-align: middle; padding: 3px;">: &nbsp; <?= $model->produk->produk_group; ?></td>
		<td style="width: 2.5cm; height: 30px; vertical-align: middle; padding: 3px 0px 3px 10px; border-right: solid 1px transparent;" ><b> Grade</td>
		<td style="width: 2.5cm; vertical-align: middle; padding: 3px;">: &nbsp; <?= $model->produk->grade; ?></td>
	</tr>
	<?php if($model->produk->produk_group == "Plywood" || $model->produk->produk_group == "Platform" || $model->produk->produk_group == "Lamineboard"){ ?>
		<tr style="">
			<td colspan="2" style="height: 10px; line-height: 1px; border-right: solid 1px transparent; border-top: solid 1px #000;"> </td>
		</tr>
		<tr>
			<td style="height: 30px; vertical-align: middle; padding: 3px 0px 3px 10px; border-right: solid 1px transparent;" ><b>Wood</td>
			<td style="vertical-align: middle; padding: 3px;">: &nbsp; <?= $model->produk->jenis_kayu; ?></td>
			<td style="vertical-align: middle; padding: 3px 0px 3px 10px; border-right: solid 1px transparent;" ><b>Glue</td>
			<td style="vertical-align: middle; padding: 3px;">: &nbsp; <?= $model->produk->glue; ?></td>
		</tr>
	<?php }else if($model->produk->produk_group == "Sawntimber"){ ?>
		<tr style="">
			<td colspan="2" style="height: 10px; line-height: 1px; border-right: solid 1px transparent; border-top: solid 1px #000;"> </td>
		</tr>
		<tr>
			<td style="height: 30px; vertical-align: middle; padding: 3px 0px 3px 10px; border-right: solid 1px transparent;" ><b>Wood</td>
			<td style="vertical-align: middle; padding: 3px;">: &nbsp; <?= $model->produk->jenis_kayu; ?></td>
			<td style="vertical-align: middle; padding: 3px 0px 3px 10px; border-right: solid 1px transparent;" ><b>Condition</td>
			<td style="vertical-align: middle; padding: 3px;">: &nbsp; <?= $model->produk->kondisi_kayu; ?></td>
		</tr>
	<?php }else if($model->produk->produk_group == "Moulding"){ ?>
		<tr style="">
			<td colspan="2" style="height: 10px; line-height: 1px; border-right: solid 1px transparent; border-top: solid 1px #000;"> </td>
		</tr>
		<tr>
			<td style="height: 30px; vertical-align: middle; padding: 3px 0px 3px 10px; border-right: solid 1px transparent;" ><b>Wood</td>
			<td style="vertical-align: middle; padding: 3px;">: &nbsp; <?= $model->produk->jenis_kayu; ?></td>
			<td style="vertical-align: middle; padding: 3px 0px 3px 10px; border-right: solid 1px transparent;" ><b>Profile</td>
			<td style="vertical-align: middle; padding: 3px;">: &nbsp; <?= $model->produk->profil_kayu; ?></td>
		</tr>
	<?php }else if($model->produk->produk_group == "Veneer"){ ?>
		
	<?php } ?>
	<tr>
		<td colspan="3" style="width: 9cm; height: 10px; line-height: 1px; border-right: solid 1px transparent; border-top: solid 1px #000;"> </td>
		<td colspan="2" rowspan="5" style="width: 4.5cm; vertical-align: top; padding: 20px 10px 10px; border-bottom: solid 1px transparent; text-align: center;">
			<div class="place-qrcode" style="text-align: center;"></div>
			<span style="font-size: 1rem;"><?= $modProduksi->nomor_urut_produksi ?></span>
		</td>
	</tr>
	<tr>
		<td style="height: 30px; width: 2cm; vertical-align: middle; padding: 3px 0px 3px 10px; border-right: solid 1px transparent;"><b>Item No.</td>
		<td colspan="2" style="vertical-align: middle; padding: 3px; ">: &nbsp; <?= $model->nomor_produksi; ?></td>
	</tr>
	<tr>
		<td colspan="3" style="height: 10px; line-height: 1px; border-right: solid 1px transparent; border-top: solid 1px #000;" > </td>
	</tr>
	<tr style="border-bottom: solid 1px transparent;">
		<td style="vertical-align: top; padding: 5px 0px 3px 10px; border-right: solid 1px transparent; height: 10px;" ><b>Dimension</td>
		<td colspan="2" style="vertical-align: top; padding: 5px 3px 3px;">:</td>
	</tr>
	<tr>
		<td colspan="3" style="vertical-align: top; padding: 0px 3px 3px;">
			<table style="width: 100%; height: 100%;" id="place-dimensi">
				<tr>
					<td style="width: 0.8cm"></td>
					<td style="width: 1.8cm; font-weight: 600">Length</td>
					<td> :&nbsp; <?= ($model->produk->produk_p > 0)? $model->produk->produk_p." ".$model->produk->produk_p_satuan:"<i style='font-size:1rem;'>Random</i>"; ?></td>
					<td style="width: 1.3cm; font-weight: 600">Pallet</td>
					<td style="width: 2.4cm;"> :&nbsp; <?= $model->qty_palet ?></td>
				</tr>
				<tr>
					<td> </td>
					<td style="font-weight: 600">Width </td>
					<td> :&nbsp; <?= ($model->produk->produk_l > 0)? $model->produk->produk_l." ".$model->produk->produk_l_satuan:"<i style='font-size:1rem;'>Random</i>"; ?></td>
					<td style="font-weight: 600">Qty</td>
					<td> :&nbsp; <?= $model->qty_kecil ?> <?= $model->qty_kecil_satuan ?></td>
				</tr>
				<tr>
					<td> </td>
					<td style="font-weight: 600">Thickness </td>
					<td> :&nbsp; <?= ($model->produk->produk_t > 0)? $model->produk->produk_t." ".$model->produk->produk_t_satuan:"<i style='font-size:1rem;'>Random</i>"; ?></td>
					<td style="font-weight: 600">Volume</td>
					<td> :&nbsp; <?= number_format((float)app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_m3), 4, '.', '') ?> m<sup>3</sup></td>
				</tr>
                <tr style="font-size: 0.3rem"><td colspan="5">&nbsp;</td></tr>
                <tr style="border-top: solid 1px #000; height: 1.5cm">
                    <td colspan="5">
                        <table style="width: 100%; height: 100%;">
                            <tr>
                                <td style="width: 33%; border-right: solid 1px #000; font-size: 0.8rem; vertical-align: bottom; text-align: center;">Check </td>
                                <td style="width: 33%; border-right: solid 1px #000; font-size: 0.8rem; vertical-align: bottom; text-align: center;">Verify </td>
                                <td style="width: 33%; text-align: center; vertical-align: middle; font-size: 3rem"><b>
                                    <?php
                                    $modHasilProduksi = app\models\THasilProduksi::findOne(['nomor_produksi'=>$model->nomor_produksi]);
                                    $modHasilRepacking = app\models\THasilRepacking::findOne(['nomor_produksi'=>$model->nomor_produksi]);
                                    if(!empty($modHasilProduksi)){
                                        echo "P";
                                    }else if(!empty($modHasilRepacking)){
                                        echo "R";
                                    }
                                    ?>
                                </b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="height: 10px; line-height: 1px; border-right: solid 1px transparent; border-top: solid 1px #000;" > </td>
	</tr>
</table>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery.qrcode.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    jQuery('.place-qrcode').qrcode({fill: '#666',width: 140,height: 140, text: '".$barcodecontent."' });
", yii\web\View::POS_READY); ?>
<script>
</script>