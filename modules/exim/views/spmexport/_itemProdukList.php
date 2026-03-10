<?php
$model->produk_kode = $model->produk->produk_kode;
$model->produk_nama = $model->produk->produk_nama;
$model->gudang_id = \app\models\HPersediaanProduk::getDataByNomorProduksi($model->nomor_produksi)['gudang_id'];
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
	
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]satuan_besar"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]tanggal_produksi"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]gudang_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_p"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_l"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_t"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_p_satuan"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_l_satuan"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_t_satuan"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]kubikasi_hasilhitung"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]nomor_produksi"); ?>
		<?= $model->nomor_produksi ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= $model->produk_kode ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= $model->produk_nama ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_besar) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_kecil)." ".$model->satuan_kecil ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= number_format($model->kubikasi,4); ?>
    </td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= $model->petugasMengeluarkan->pegawai_nama; ?>
    </td>
</tr>
<script>

</script>
