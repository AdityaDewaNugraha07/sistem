<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?php
			echo $modDetail->produk->produk_kode." - (".$random['t'].$random['t_satuan']."x".$random['l'].$random['l_satuan']."x".$random['p'].$random['p_satuan'].")<br>";
			$modDetail->qty_kecil = $random['qty_kecil'];
			$modDetail->kubikasi = $random['kubikasi'];
		?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]op_ko_detail_id") ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id") ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar") ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil") ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi") ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_jual") ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
		-
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($random['qty_kecil']) ?> (<?= $random['satuan_kecil'] ?>)
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($random['kubikasi']) ?>
    </td>
	<td></td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual) ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?php
		if($modDetail->opKo->jenis_produk == "Plywood" || $modDetail->opKo->jenis_produk == "Lamineboard" || $modDetail->opKo->jenis_produk == "Platform"){
			$subtotal = $random['qty_kecil'] * $modDetail->harga_jual;
		}else{
			$subtotal = $random['kubikasi'] * $modDetail->harga_jual;
		}
		?>
		<?= app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal ) ?>
    </td>
	<td></td>
</tr>
<script>

</script>