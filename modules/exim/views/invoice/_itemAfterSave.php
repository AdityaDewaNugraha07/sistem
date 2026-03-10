<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_hpp",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]ppn",['class'=>'float']); ?>
		<?= $modDetail->produk->produk_kode ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?= $modDetail->produk->produk_nama ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil) ?> <i>(<?= $modDetail->satuan_kecil ?>)</i>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->kubikasi) ?>
    </td>
	<td class="text-align-right td-kecil">
		<?php
		echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]harga_jual",['class'=>'form-control float','style'=>"padding:2px;",'onblur'=>'subTotal();']);
		?>
	</td>
	<td class="text-align-right td-kecil">
		<?php
		echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]subtotal",['class'=>'form-control float','style'=>"padding:2px;",'disabled'=>'disabled']);
		?>
	</td>
</tr>
<script>

</script>
