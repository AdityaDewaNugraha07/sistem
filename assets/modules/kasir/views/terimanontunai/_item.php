<?php
if(!empty($model->kas_besar_nontunai_id)){
	$btnedit = '';
	$btnsave = 'none';
	$btncancel = 'none';
	$btndelete = '';
	$btntbp = false;
}else{
	$btnedit = 'none';
	$btnsave = '';
	$btncancel = '';
	$btndelete = 'none';
	$btntbp = true;
}
?>
<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]kas_besar_nontunai_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]tanggal',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]kode',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
	<td style="vertical-align: middle; padding: 3px;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextInput($model, '[ii]nama_customer', ['class'=>'form-control','style'=>'padding:3px; font-size: 1.2rem; height: 30px;']); ?>
	</td>
	<td style="vertical-align: middle; padding: 3px;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextInput($model, '[ii]no_bukti', ['class'=>'form-control','style'=>'padding:3px; font-size: 1.2rem; height: 30px;']); ?>
	</td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextInput($model, '[ii]cust_bank', ['class'=>'form-control','style'=>'padding:3px; font-size: 1.2rem; height: 30px;']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextInput($model, '[ii]cust_acct', ['class'=>'form-control','style'=>'padding:3px; font-size: 1.2rem; height: 30px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php echo \yii\helpers\Html::activeTextInput($model, '[ii]reff_number', ['class'=>'form-control','style'=>'padding:3px; font-size: 1.2rem; height: 30px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <div class="input-group date date-picker" data-date-start-date="-70d">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]tanggal_jatuhtempo',['class'=>'form-control','style'=>'width:100%; padding:3px; height: 30px; font-size: 1.2rem;','readonly'=>'readonly','placeholder'=>'dd/mm/yyyy']); ?>
            <span class="input-group-btn">
                <button class="btn default" type="button" style="margin-left: -28px; padding: 1px; height: 30px; margin-top: 0px; width: 25px; margin-right: 0px;">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php echo \yii\helpers\Html::activeTextInput($model, '[ii]nominal', ['class'=>'form-control float','style'=>'padding:3px; font-size: 1.2rem; height: 30px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php echo \yii\helpers\Html::activeTextarea($model, '[ii]keterangan', ['class'=>'form-control','style'=>'padding:3px; font-size: 1.1rem; height: 35px;']); ?>
    </td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil" id='td-action'>
		<span id="place-editbtn" style="display: <?= $btnedit ?>">
			<a class="btn btn-xs dark btn-outline" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="edit(this);"><i class="fa fa-edit"></i></a>
		</span>
		<span id="place-savebtn" style="display: <?= $btnsave ?>">
			<a class="btn btn-xs hijau" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="save(this);"><i class="fa fa-check"></i></a>
		</span>
		<span id="place-cancelbtn" style="display: <?= $btncancel ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
		</span>
		<span id="place-deletebtn" style="display: <?= $btndelete ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="deleteItem(<?= $model->kas_besar_nontunai_id ?>);"><i class="fa fa-trash-o"></i></a>
		</span>
    </td>
</tr>