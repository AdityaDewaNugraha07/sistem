<?php
if(!empty($modDetail->voucher_pengeluaran_id) && !isset($edit)){
	$disabled = true;
}else{
	$disabled = false;
}
?>
<tr>
    <td style="padding-top: 10px; vertical-align:middle; text-align:center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px; ']); ?>
        <span class="no_urut"></span>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]voucher_detail_id') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]acct_id') ?>
    </td>
	<td style="text-align: left;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextarea($modDetail, '[ii]keterangan', ['class'=>'form-control','disabled'=>$disabled,'rows'=>1,'style'=>'padding:3px; font-size:1.2rem;']); ?>
	</td>
	<td style="text-align: left;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]jumlah', ['class'=>'form-control float','style'=>'padding:3px; font-size:1.2rem;','disabled'=>$disabled,'onblur'=>'setTotal();']); ?>
	</td>
    <td style="padding-top: 10px; text-align: center;" class="td-kecil">
		<a class="btn btn-xs red tooltips hapusitembutton" data-original-title="Delete" onclick="hapusItem(this);" style="margin-right: 0px;"><i class="fa fa-remove"></i></a>
    </td>
</tr>