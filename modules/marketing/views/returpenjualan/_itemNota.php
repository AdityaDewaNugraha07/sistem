<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modProduk, "[".$i."]produk_p"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modProduk, "[".$i."]produk_l"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modProduk, "[".$i."]produk_t"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modProduk, "[".$i."]produk_p_satuan"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modProduk, "[".$i."]produk_l_satuan"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modProduk, "[".$i."]produk_t_satuan"); ?>
		<?= $modDetail->produk->produk_kode ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?= $modDetail->produk->produk_nama ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?php
		echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]qty_kecil",['class'=>'form-control float','style'=>"padding:2px; font-size:1.2rem;",'onblur'=>'setMeterKubik(this);']);
		?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi",['class'=>'float']); ?>
		<?= \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]kubikasi_display",['class'=>'form-control float','style'=>"padding:2px; font-size:1.2rem;",'disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_jual",['class'=>'float']); ?>
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual) ?>
    </td>
	<td class="text-align-right td-kecil">
		<?php
		echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]harga_retur",['class'=>'form-control float','style'=>"padding:2px; font-size:1.2rem;",'onblur'=>'subTotal();']);
		?>
	</td>
	<td class="text-align-right td-kecil">
		<?php
		echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]subtotal",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;",'disabled'=>'disabled']);
		?>
	</td>
	<td class="text-align-right td-kecil">
		<a class="btn btn-xs red" onclick="cancelItem(this,'subTotal(this)');"><i class="fa fa-remove"></i></a>
	</td>
</tr>
<script>

</script>
