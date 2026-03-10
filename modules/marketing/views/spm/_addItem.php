<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil" colspan="2">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[ii]spm_kod_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[ii]satuan_besar"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[ii]harga_hpp"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[ii]harga_jual"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[ii]keterangan"); ?>
		<span class="input-group-btn" style="width: 100%">
			<?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]produk_id',[],['class'=>'form-control select2','onchange'=>'setItem(this)','prompt'=>'','style'=>'width:100%;']); ?>
		</span>
		<span class="input-group-btn" style="width: 10%">
			<a class="btn btn-icon-only btn-default tooltips" onclick="masterProduk(this);" data-original-title="Lihat Master Produk" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
		</span>
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-right">
		<?php
		echo yii\bootstrap\Html::activeTextInput($modDetail, "[ii]qty_besar",['class'=>'form-control float','style'=>'font-size:1.1rem; width:50px; padding:3px;','onblur'=>'setQty(this)']);
		echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[ii]satuan_besar");
		?>
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-right">
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_kecil', ['class'=>'form-control float','disabled'=>'disabled','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
		</span>
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]satuan_kecil', ['class'=>'form-control','disabled'=>'disabled','style'=>'width:100%;  font-size:1.2rem; padding:5px;']); ?>
		</span>
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-right">
		<?php
		echo \yii\bootstrap\Html::activeTextInput($modDetail, "[ii]kubikasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
		?>
    </td>
	<td class="text-align-right td-kecil" style="background-color: #B6D25D;">
		<?php
		echo \yii\helpers\Html::activeTextInput($modDetail, "[ii]qty_besar_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
		echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[ii]satuan_besar_realisasi");
		?>
	</td>
	<td class="text-align-right td-kecil" style="background-color: #B6D25D;">
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_kecil_realisasi', ['class'=>'form-control float','disabled'=>'disabled','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
		</span>
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]satuan_kecil_realisasi', ['class'=>'form-control','disabled'=>'disabled','style'=>'width:100%;  font-size:1.2rem; padding:5px;']); ?>
		</span>
	</td>
	<td class="text-align-right td-kecil" style="background-color: #B6D25D;">
		<?php
		echo \yii\bootstrap\Html::activeTextInput($modDetail, "[ii]kubikasi_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
		?>
	</td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center">
		<a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>
<script>

</script>
