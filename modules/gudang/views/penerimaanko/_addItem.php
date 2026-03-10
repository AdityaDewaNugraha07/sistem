<tr>
    <td style="vertical-align: middle; text-align: center; padding: 3px;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle; padding: 3px;">
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]t', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.2rem;','onblur'=>'setMeterKubikItem(this);','disabled'=>$disabled]); ?>
		</span>
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeDropDownList($modDetail, '[ii]t_satuan',\app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'padding: 3px; font-size:1.2rem;','onchange'=>'setMeterKubikItem(this);','disabled'=>$disabled]); ?>
		</span>
    </td>
    <td style="vertical-align: middle; padding: 3px;">
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]l', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.2rem;','onblur'=>'setMeterKubikItem(this);','disabled'=>$disabled]); ?>
		</span>
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeDropDownList($modDetail, '[ii]l_satuan',\app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'padding: 3px; font-size:1.2rem;','onchange'=>'setMeterKubikItem(this);','disabled'=>$disabled]); ?>
		</span>
    </td>
	<td style="vertical-align: middle; padding: 3px;">
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]p', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.2rem;','onblur'=>'setMeterKubikItem(this);','disabled'=>$disabled]); ?>
		</span>
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeDropDownList($modDetail, '[ii]p_satuan',\app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'padding: 3px; font-size:1.2rem;','onchange'=>'setMeterKubikItem(this);','disabled'=>$disabled]); ?>
		</span>
    </td>
    <td style="vertical-align: middle; padding: 3px;">
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.2rem;','onblur'=>'setMeterKubikItem(this);','disabled'=>$disabled]); ?>
		</span>
		<span class="input-group-btn" style="width: 50%">
			<?= \yii\helpers\Html::activeDropDownList($modDetail, '[ii]qty_satuan',\app\models\MDefaultValue::getOptionList('produk-satuan-kecil'),['class'=>'form-control','style'=>'padding: 3px; font-size:1.2rem;','disabled'=>$disabled]); ?>
		</span>
    </td>
	<td style="vertical-align: middle; padding: 3px;">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]kapasitas_kubikasi_display', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.2rem;','readonly'=>'readonly']); ?>
		<?= \yii\helpers\Html::activeHiddenInput($modDetail, '[ii]kapasitas_kubikasi') ?>
    </td>
	<td style="vertical-align: middle; padding: 3px;">
        <?= \yii\helpers\Html::activeTextarea($modDetail, '[ii]keterangan', ['class'=>'form-control','style'=>'width:100%; height: 45px; font-size:1.1rem; padding:3px;','placeholder'=>'Keterangan','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;  padding: 3px;">
		<?php if(!$disabled){
			echo '<a class="btn btn-xs red" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>';
		} ?>
    </td>
</tr>
<script>
</script>