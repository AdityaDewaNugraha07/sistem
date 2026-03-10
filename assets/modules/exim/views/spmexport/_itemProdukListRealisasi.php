<?php
$model->gudang_id = \app\models\HPersediaanProduk::getDataByNomorProduksi($model->nomor_produksi)['gudang_id'];
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_keluar_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]nomor_produksi"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]gudang_id"); ?>
		<?= $model->nomor_produksi ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= $model->produk->produk_kode ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= $model->produk->produk_nama ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_besar) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_kecil) ?> 
		<i>(<?= $model->produk->produk_satuan_kecil ?>)</i>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= number_format($model->kubikasi,4); ?>
    </td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil">
        -
    </td>
</tr>
<script>

</script>
