<?php 
$column = "";
$colspan_header = 8;
$colspan_footer = 3;
if($jenis_produk == "Plywood" || $jenis_produk == "Lamineboard" || $jenis_produk == "Platform"){
	$column = '<td style="padding: 2px;">'.yii\bootstrap\Html::activeDropDownList($model, "[ii]jenis_kayu", \app\models\MJenisKayu::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']).'</td>'.
			  '<td style="padding: 2px;">'.yii\bootstrap\Html::activeDropDownList($model, "[ii]glue", \app\models\MGlue::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']).'</td>';
	$colspan_header = 10;
	$colspan_footer = 5;
}else if($jenis_produk == "Sawntimber"){
	$column = '<td style="padding: 2px;">'.yii\bootstrap\Html::activeDropDownList($model, "[ii]kondisi_kayu", \app\models\MKondisiKayu::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']).'</td>';
	$colspan_header = 9;
	$colspan_footer = 4;
}else if($jenis_produk == "Moulding"){
	$column = '<td style="padding: 2px;">'.yii\bootstrap\Html::activeDropDownList($model, "[ii]jenis_kayu", \app\models\MJenisKayu::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']).'</td>'.
			  '<td style="padding: 2px;">'.yii\bootstrap\Html::activeDropDownList($model, "[ii]profil_kayu", \app\models\MProfilKayu::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']).'</td>';
	$colspan_header = 10;
	$colspan_footer = 5;
}?>
<tr>
	<td style="padding: 2px;">
		<?= \yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:50px;']); ?>
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]bundles_no',['class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center; height: 26px; padding:2px; font-size:1.2rem;']) ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($model, '[ii]packinglist_container_id') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($model, '[ii]container_no') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($model, '[ii]container_kode') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($model, '[ii]seal_no') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($model, '[ii]gross_weight',['class'=>'float']) ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($model, '[ii]nett_weight',['class'=>'float']) ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($model, '[ii]partition_kode') ?>
	</td>
	<td style="padding: 2px;">
		<?= yii\bootstrap\Html::activeDropDownList($model, "[ii]grade", \app\models\MGrade::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']) ?>
	</td>
	<?= $column ?>
	<td style="padding: 2px;">
		<span class="input-group-btn" style="width: 45%">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]thick',['class'=>'form-control float','style'=>'text-align:right; height: 26px; padding:2px; font-size:1.2rem;','onblur'=>'setMeterKubik(this)']) ?>
		</span>
		<span class="input-group-btn" style="width: 55%">
			<?= yii\bootstrap\Html::activeDropDownList($model, "[ii]thick_unit", \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'height: 26px; padding:2px; font-size:1.2rem;','onchange'=>'setMeterKubik(this)']) ?>
		</span>
	</td>
	<td style="padding: 2px;">
		<span class="input-group-btn" style="width: 45%">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]width',['class'=>'form-control float','style'=>'text-align:right; height: 26px; padding:2px; font-size:1.2rem;','onblur'=>'setMeterKubik(this)']) ?>
		</span>
		<span class="input-group-btn" style="width: 55%">
			<?= yii\bootstrap\Html::activeDropDownList($model, "[ii]width_unit", \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'height: 26px; padding:2px; font-size:1.2rem;','onchange'=>'setMeterKubik(this)']) ?>
		</span>
	</td>
	<td style="padding: 2px;">
		<span class="input-group-btn" style="width: 45%">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]length',['class'=>'form-control float','style'=>'text-align:right; height: 26px; padding:2px; font-size:1.2rem;','onblur'=>'setMeterKubik(this)']) ?>
		</span>
		<span class="input-group-btn" style="width: 55%">
			<?= yii\bootstrap\Html::activeDropDownList($model, "[ii]length_unit", \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'height: 26px; padding:2px; font-size:1.2rem;','onchange'=>'setMeterKubik(this)']) ?>
		</span>
	</td>
	<td style="padding: 2px;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]pcs',['class'=>'form-control float','style'=>'text-align:right; height: 26px; padding:2px; font-size:1.2rem;','onblur'=>'setMeterKubik(this)']) ?>
	</td>
	<td style="padding: 2px;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]volume',['class'=>'form-control float','disabled'=>'disabled','style'=>'text-align:right; height: 26px; padding:2px; font-size:1.2rem;']) ?>
	</td>
	<td style="text-align: right;">
		<a class="btn btn-xs red-flamingo" onclick="hapusbundle(this,'<?= $table_id ?>')"><i class="fa fa-close"></i></a>
	</td>
</tr>