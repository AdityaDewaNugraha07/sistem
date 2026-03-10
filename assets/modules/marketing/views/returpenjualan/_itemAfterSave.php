<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?= $modDetail->produk->produk_kode ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?= $modDetail->produk->produk_nama ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= number_format($modDetail->kubikasi,4); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual) ?>
    </td>
	<td class="text-align-right td-kecil">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_retur) ?>
	</td>
	<td class="text-align-right td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]subtotal",['class'=>'float']); ?>
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->subtotal) ?>
	</td>
</tr>
<script>

</script>
