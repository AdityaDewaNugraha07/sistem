<?php
if(!empty($modJurnal->voucher_pengeluaran_id)){
	$disabled = true;
}else{
	$disabled = false;
}
?>
<tr>
    <td style="padding-top: 10px; vertical-align:middle; text-align:center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px; ']); ?>
        <span class="no_urut"></span>
		<?= \yii\bootstrap\Html::activeHiddenInput($modJurnal, '[ii]jurnal_id') ?>
    </td>
	<td style="text-align: left;">
		<?= yii\bootstrap\Html::activeDropDownList($modJurnal, '[ii]acct_id', \app\models\MAcctRekening::getOptionList(),['class'=>'form-control select2','prompt'=>'','disabled'=>$disabled]) ?>
	</td>
	<td style="text-align: left;">
		<?= \yii\helpers\Html::activeTextarea($modJurnal, '[ii]memo', ['class'=>'form-control','disabled'=>$disabled,'style'=>'height:50px;']); ?>
	</td>
	<td style="text-align: left;">
		<?= \yii\helpers\Html::activeTextInput($modJurnal, '[ii]debet', ['class'=>'form-control money-format','disabled'=>$disabled,'onblur'=>'setTotal();','placeholder'=>'Rp.']); ?>
	</td>
	<td style="text-align: left;">
		<?= \yii\helpers\Html::activeTextInput($modJurnal, '[ii]kredit', ['class'=>'form-control money-format','disabled'=>$disabled,'onblur'=>'setTotal();','placeholder'=>'Rp.']); ?>
	</td>
    <td style="padding-top: 10px; text-align: center;">
		<a class="btn btn-xs red tooltips hapusitembutton" data-original-title="Delete" onclick="hapusItem(this);" style="margin-right: 0px;"><i class="fa fa-remove"></i></a>
    </td>
</tr>