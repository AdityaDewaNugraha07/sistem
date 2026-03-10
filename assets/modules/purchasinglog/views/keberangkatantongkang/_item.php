<?php
if(!empty($modDetail->keberangkatan_tongkang_detail_id) && empty($edit)){
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
?>
<tr style="">
    <td style="text-align: center; padding: 2px; vertical-align: middle;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]keberangkatan_tongkang_detail_id") ?>
    </td>
	<td style="text-align: left; padding: 2px; font-size: 10px;">
		<span class="input-group-btn" style="width: 100%">
			<?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]loglist_id', app\models\TLoglist::getOptionListNotIn($alreadyitem),['class'=>'form-control select2','onchange'=>'setItem(this)','prompt'=>'','style'=>'width:100%;','disabled'=>$disabled]); ?>
		</span>
		<span class="input-group-btn" style="width: 10%">
			<?php if(!empty($modDetail->keberangkatan_tongkang_detail_id)){ ?>
				<a class="btn btn-icon-only btn-default tooltips" style="margin-left: 3px; border-radius: 4px;" disabled><i class="fa fa-list"></i></a>
			<?php }else{ ?>
				<a class="btn btn-icon-only btn-default tooltips" onclick="openLoglist(this);" data-original-title="Open Daftar Loglist" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
			<?php } ?>
		</span>
	</td>
	<td style="text-align: left; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]lokasi_muat',['class'=>'form-control','style'=>'padding: 2px; font-size:13px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<div class="input-group date date-picker">
            <?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]tanggal_muat',['class'=>'form-control','style'=>'width:140px','readonly'=>'readonly','placeholder'=>'Pilih Tanggal','disabled'=>$disabled]); ?>
            <span class="input-group-btn">
                <button class="btn default" type="button" style="margin-left: -40px;" <?= ($disabled==true)?"disabled":""; ?>>
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_batang',['class'=>'form-control float','onblur'=>'setTotal()','style'=>'padding: 2px; font-size:13px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_m3',['class'=>'form-control float','onblur'=>'setTotal()','style'=>'padding: 2px; font-size:13px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]keterangan',['class'=>'form-control','style'=>'padding: 2px; font-size:13px;','disabled'=>$disabled]); ?></center>
	</td>
    <td style="vertical-align: middle; text-align: center;">
		<span id="place-cancelbtn" style="display: <?= $btncancel ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
		</span>
    </td>
</tr>
<?php $this->registerJs(" 
	
	
", yii\web\View::POS_READY); ?>