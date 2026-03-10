<?php
if(!empty($modDetail->loglist_detail_id)){
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
<tr>
    <td class="td-kecil text-center"><?php echo $i;?></td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
        <?php /*<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?> */?>
        <!--<span class="no_urut"></span>-->
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]loglist_detail_id") ?>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]loglist_id") ?>
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]nomor_grd',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
    </td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]nomor_produksi',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]nomor_batang',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]kayu_id',\app\models\MKayu::getOptionListPlusGroup(),['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>$disabled]); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]panjang',['class'=>'form-control float','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','onblur'=>'hitungRata(this); hitungVolume(this)','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter_ujung1',['class'=>'form-control float','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','onblur'=>'hitungRata(this); hitungVolume(this)','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter_ujung2',['class'=>'form-control float','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','onblur'=>'hitungRata(this); hitungVolume(this)','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter_pangkal1',['class'=>'form-control float','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','onblur'=>'hitungRata(this); hitungVolume(this)','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter_pangkal2',['class'=>'form-control float','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','onblur'=>'hitungRata(this); hitungVolume(this)','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter_rata',['class'=>'form-control float','disabled'=>TRUE,'style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]cacat_panjang',['class'=>'form-control float','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','onblur'=>'hitungVolume(this)','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]cacat_gb',['class'=>'form-control float','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','onblur'=>'hitungVolume(this)','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]cacat_gr',['class'=>'form-control float','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','onblur'=>'hitungVolume(this)','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]volume_range', \app\models\MDefaultValue::getOptionList('volume-range-log'),['class'=>'form-control bs-select','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>$disabled]); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]volume_value',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 18px; vertical-align: middle;">
		<?php $modDetail->is_freshcut = TRUE; ?>
		<center><?= yii\helpers\Html::activeCheckbox($modDetail, '[ii]is_freshcut',['class'=>'','label'=>'','disabled'=>$disabled]); ?></center>
	</td>
    <td style="vertical-align: middle; text-align: center;">
        <?php
        //(empty($_POST['lampiran']) || $_POST['lampiran'] == '' || $_POST['lampiran'] < 1) ? $lampiran = 1 : $lampiran = $_POST['lampiran'];
        ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, '[ii]lampiran',['value'=>$lampiran,'class'=>'form-control float','disabled'=>TRUE,'style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>$disabled]); ?>
        <?php if(isset($edit)&&($edit=="0")){ ?>
		<span id="place-editbtn" style="display: <?= $btnedit ?>">
			<a class="btn btn-xs dark btn-outline" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="edit(this);"><i class="fa fa-edit"></i></a>
		</span>
		<?php /*<span id="place-savebtn" style="display: <?= $btnsave ?>">
			<a class="btn btn-xs hijau" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="saveItem(this);"><i class="fa fa-check"></i></a>
		</span>*/?>
		<span id="place-cancelbtn" style="display: <?= $btncancel ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
		</span>
		<?php /*<span id="place-deletebtn" style="display: <?= $btndelete ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="deleteItem(this);"><i class="fa fa-trash-o"></i></a>
		</span>*/?>
        <?php } ?>
    </td>
</tr>
<?php $this->registerJs(" 
	
	
", yii\web\View::POS_READY); ?>