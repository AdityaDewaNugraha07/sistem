<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>

<style>
table{
	font-size: 1.2rem;
}
.sizetd{
    padding-left: 15px; 
    padding-right: 10px;
    font-size: 1.5rem;
    font-weight: bold;
    border-right: solid 1px transparent;
}
</style>
<table style="width: 10.5cm; margin: 10px; height: 6cm;" border="1">
	<tr style="height: 1cm; border: solid 1px #000;">
		<td colspan="5" style="text-align: center;">
			<b style="font-size: 2rem;"><?= $paramprint['judul'] ?></b>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="height: 10px; line-height: 1px; border-right: solid 1px transparent; border-top: solid 1px #000;"> </td>
	</tr>
	<tr>
        <td class="sizetd">
            <?php 
            $modProd = \app\models\MBrgProduk::findOne($modelDet->produk_id);
            echo '<span style="font-size: 2rem;">'.$modelDet->nomor_produksi.'</span>'; 
            echo '<br>';
            echo \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            echo '<br>';
            echo $modProd->produk_nama;
            echo '<br>';
            echo $modelDet->qty_kecil . ' ' . $modelDet->satuan_kecil;
            echo '<br>';
            echo $modelDet->kubikasi . ' m<sup>3</sup>';
            ?>
        </td>
        <td>
            <div class="place-qrcode" style="text-align: center; padding-right: 15px;"></div>
        </td>
    </tr>
</table>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery.qrcode.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    jQuery('.place-qrcode').qrcode({fill: '#666',width: 120,height: 120, text: '". $modelDet->nomor_produksi ."' });
", yii\web\View::POS_READY); ?>
<script>
</script>