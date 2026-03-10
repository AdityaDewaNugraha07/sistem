<?php
if(!empty($model->kas_kecil_id)){
	if(!empty($model->bkk_id)){
		$btnedit = 'none';
		$btndelete = 'none';
	}else{
		$btnedit = '';
		$btndelete = '';
	}
	$btnsave = 'none';
	$btncancel = 'none';
	$btntbp = false;
	$btneditdeskripsi = '';
}else{
	$btnedit = 'none';
	$btnsave = '';
	$btncancel = '';
	$btndelete = 'none';
	$btntbp = true;
	$btneditdeskripsi = 'none';
}
$tbplabel = "";
$kasbonlabel = "";
if(!empty($model->tbp_reff)){
	foreach(explode(",", $model->tbp_reff) as $i => $tbp){
		$modTBP = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>$tbp]);
		$tbplabel .= "<a onclick='infoTBP(".$modTBP->terima_bhp_id.")'>".$tbp."</a><br>";
	}
}
if(!empty($modKasbon)){
	$kasbonlabel = "<a onclick='infoKasbon(".$modKasbon->kas_bon_id.")'>".$modKasbon->kode."</a>";
}
?>
<tr style="" id="<?= $model->kas_kecil_id ?>">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]kas_kecil_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]tanggal',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]kas_bon_id',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextarea($model, '[ii]deskripsi', ['class'=>'form-control','style'=>'height:40px; font-size:1.1rem; padding:3px;']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextInput($model, '[ii]penerima', ['class'=>'form-control','style'=>'font-size:1.1rem; padding:3px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php echo \yii\helpers\Html::activeTextInput($model, '[ii]debit', ['class'=>'form-control float','onblur'=>'setTotal()','style'=>'padding:3px; font-size:1.1rem','disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php echo \yii\helpers\Html::activeTextInput($model, '[ii]nominal', ['class'=>'form-control float','onblur'=>'setTotal()','style'=>'padding:3px; font-size:1.1rem']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php
		if(!empty($model->kas_kecil_id)){
			if(!empty($model->bkk_id)){
				$bkk = "<a onclick='infoBKK({$model->bkk_id})' style='font-size: 1rem;'>".$model->bkk->kode."</a>";
			}else{
				$bkk = "<a onclick='createBkk({$model->kas_kecil_id});'><span class='font-red-flamingo'>PRINT BKK</span></a><br>
						<span id='place-checkboxbkkmultiple' style='display:none;'><input type='checkbox' id='checkboxbkkmultiple' name='checkboxbkkmultiple' onclick='checkThisMultiple();'></span>";
			}
			if($model->tipe == "IN"){
				$bkk = "-";
			}
		}else{
			$bkk = "-";
		}
		echo $bkk;
		?>
    </td>
	<td style="text-align: center; font-size: 1rem; vertical-align: middle !important;" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]tbp_reff'); ?>
		<span id="place-tbp" style="font-size: 1rem"><?= $tbplabel; ?></span>
		<span id="place-kasbon" style="font-size: 1rem"><?= $kasbonlabel; ?></span>
		<span id="place-tbpbtn">
			<?php if($btntbp==true){ ?>
				<a class="btn btn-xs blue-steel" id="btn-addtbp" onclick="pickPanelTBP(this)" style="margin-top: 5px; font-size: 1rem;"><i class="fa fa-plus"></i> TBP</a>
				<a class="btn btn-xs blue" id="btn-addbon" onclick="pickPanelPengeluaranSementara(this)" style="margin-top: 5px; margin-left: -7px; font-size: 1rem;"><i class="fa fa-plus"></i> Bon</a>
				<!--<a class="btn btn-xs yellow-gold" id="btn-refreshrow" onclick="resetTBP(this)" style="margin-top: 5px; margin-left: -7px;"><i class="fa fa-refresh"></i></a>-->
			<?php }else{ ?>
				<a class="btn btn-xs grey" id="btn-addtbp" style="font-size: 1rem;"><i class="fa fa-plus"></i> TBP</a>
				<a class="btn btn-xs grey" id="btn-addbon" style=" margin-left: -7px; font-size: 1rem;"><i class="fa fa-plus"></i> Bon</a>
				<!--<a class="btn btn-xs grey" id="btn-refreshrow" style=" margin-left: -7px;"><i class="fa fa-refresh"></i></a>-->
			<?php } ?>
		</span>
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
			<a class="btn btn-xs red" id="close-btn-this" onclick="deleteItem(<?= $model->kas_kecil_id ?>);"><i class="fa fa-trash-o"></i></a>
		</span>
		<span id="place-editdeskripsibtn" style="display: <?= $btneditdeskripsi ?>">
			<a class="btn btn-xs blue-steel btn-outline" id="close-btn-this" onclick="editDeskripsi(<?= $model->kas_kecil_id ?>);"><i class="fa fa-edit"></i></a>
		</span>
    </td>
</tr>