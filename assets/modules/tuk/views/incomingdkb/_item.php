<?php
if(!empty($modDetail->incoming_dkb_id)){
	$btnsave = 'none';
	$btncancel = 'none';
	$btnedit = '';
	$btndelete = '';
	$disabled = true;
}else{
	$btnsave = '';
	$btncancel = '';
	$btnedit = 'none';
	$btndelete = 'none';
	$disabled = false;
}

$tertransaksikan = false;
if(!empty($modDetail->incoming_dkb_id)){
	$checkOut = \app\models\HPersediaanDkb::checkPersediaanOut($modDetail->no_barcode);
	if($checkOut){
		$tertransaksikan = true;
		$btnedit = 'none';
		$btndelete = 'none';
	}
}
?>
<tr style="<?= ($tertransaksikan)?'background-color: #FFFBC3':''; ?>">
    <td style="text-align: center; padding: 2px; vertical-align: middle;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]incoming_dkb_id") ?>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]loglist_id") ?>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]kode_partai") ?>
    </td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]no_grade',['class'=>'form-control','style'=>'padding: 2px; text-align:left; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]no_barcode',['class'=>'form-control','style'=>'padding: 0px; text-align:left; font-size:12px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]no_btg',['class'=>'form-control','style'=>'padding: 2px; text-align:left; font-size:12px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]kayu_id',\app\models\MKayu::getOptionListPlusGroup(),['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:10px; height:25px;','disabled'=>$disabled]); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]panjang',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]volume',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]kondisi',['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]pot',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]asal_kayu',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:12px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 9px;">
		<?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]lokasi_bongkar',["PELABUHAN"=>"PELABUHAN","PABRIK"=>"PABRIK"],['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:10px; height:25px;','disabled'=>$disabled]); ?>
	</td>
    <td style="vertical-align: middle; text-align: center;">
        <span id="place-editbtn" style="display: <?= $btnedit ?>">
			<a class="btn btn-xs dark btn-outline" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="edit(this);"><i class="fa fa-edit"></i></a>
		</span>
		<span id="place-savebtn" style="display: <?= $btnsave ?>">
			<a class="btn btn-xs hijau" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="saveItem(this);"><i class="fa fa-check"></i></a>
		</span>
		<span id="place-cancelbtn" style="display: <?= $btncancel ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
		</span>
		<span id="place-deletebtn" style="display: <?= $btndelete ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="deleteItem(this);"><i class="fa fa-trash-o"></i></a>
		</span>
    </td>
</tr>
<?php $this->registerJs(" 
	
	
", yii\web\View::POS_READY); ?>