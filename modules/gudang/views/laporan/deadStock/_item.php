<tr>
	<td class="td-detail" style="text-align:center; font-size: 1.2rem; position:absolute; left:0px; width: 35px;"><?= $i+1 ?></td>
	<td class="td-detail" style="text-align:left; font-size: 1.2rem; position:absolute; left:35px; width: 300px;">
		<?= $model['produk_nama'] ?>
	</td>
	<td class="td-detail" style="text-align:center; font-size: 1.2rem; width: 50px; position:absolute; left:335px;">
		<?= $model['gudang_nm'] ?>
	</td>
	<td class="td-detail" style="text-align:right; font-size: 1.2rem; width: 60px; position:absolute; left:385px; padding-right: 15px;">
		<?= $model['palet'] ?>
		<input type="hidden" class="total_palet" value="<?= $model['palet'] ?>">
	</td>
	<td class="td-detail" style="text-align:right; font-size: 1.2rem; width: 85px; position:absolute; left:445px;">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($model['qty_kecil'])." <i>(".$model['in_qty_kecil_satuan'].")</i>"; ?>
		<input type="hidden" class="total_qty" value="<?= $model['qty_kecil'] ?>">
	</td>
	<td class="td-detail" style="text-align:right; font-size: 1.2rem; width: 85px; position:absolute; left:530px; border-right: 1px dotted #000">
		<?php // echo (strlen(substr(strrchr($model['kubikasi'], "."), 1)) > 4)? $model['kubikasi']*10000/10000: str_replace(".", ",", (string)$model['kubikasi']) ?>
		<?php echo (substr(strrchr($model['kubikasi'], "."), 1) > 4) ?$model['kubikasi']*10000/10000 : $model['kubikasi'] ?>
		<input type="hidden" class="total_kubikasi" value="<?= $model['kubikasi'] ?>">
	</td>
	<?= $td ?>
</tr>