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
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]random"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_p"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_l"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_t"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_p_satuan"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_l_satuan"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_t_satuan"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]kubikasi_hasilhitung"); ?>
		<span class="input-group-btn" style="width: 100%">
			<?php echo yii\helpers\Html::activeDropDownList($model, '[ii]nomor_produksi',[],['class'=>'form-control select2','onchange'=>'setItemProductList(this)','prompt'=>'','style'=>'width:90%;']); ?>
		</span>
		<span class="input-group-btn" style="width: 20%">
			<a class="btn btn-icon-only btn-default tooltips" onclick="stockAvailable(this);" data-original-title="Stock Available" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
		</span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]produk_kode', ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]produk_nama', ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]qty_besar', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeTextInput($model, '[ii]qty_kecil', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
		</span>
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeTextInput($model, '[ii]satuan_kecil', ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
		</span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]kubikasi', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
    </td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <a class="btn btn-xs red" onclick="cancelItemProdukList(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>
<script>

</script>
