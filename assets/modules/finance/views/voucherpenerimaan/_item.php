<?php
if(!empty($model->voucher_penerimaan_id)){
	$btninfo = '';
	$btnedit = '';
	$btnsave = 'none';
	$btncancel = 'none';
	$btndelete = '';
	$btntbp = false;
	$disabled = true;
//	$date1 = new DateTime(date("y-m-d"));
//	$date2 = new DateTime($model->tanggal);
//	$diff = $date2->diff($date1)->format("%a"); 
//	$days = intval($diff);
//	if($days > 2){
//		$btnedit = 'none';
//	}
}else{
	$btninfo = 'none';
	$btnedit = 'none';
	$btnsave = '';
	$btncancel = '';
	$btndelete = 'none';
	$btntbp = true;
	$disabled = false;
}

$onclickinfopiutang = "";
$trcolor = "";
if(!empty($model->voucher_penerimaan_id)){
	$onclickinfopiutang = "infoPiutang('".$model->voucher_penerimaan_id."');";
	$sql = "SELECT t_piutang_penjualan.bayar AS nominalterpakai, t_voucher_penerimaan.total_nominal AS nominalterima FROM t_piutang_penjualan
			JOIN t_voucher_penerimaan ON t_piutang_penjualan.payment_reff = t_voucher_penerimaan.kode
			WHERE t_voucher_penerimaan.voucher_penerimaan_id = ".$model->voucher_penerimaan_id;
	$modPiutang = Yii::$app->db->createCommand($sql)->queryAll();
	if(!empty($modPiutang)){
		$nominalterima = $model->total_nominal;
		$nominalterpakai = 0;
		foreach($modPiutang as $iii => $piutang){
			$nominalterpakai += $piutang['nominalterpakai'];
		}
		$sisa = $nominalterima-$nominalterpakai;
		if($sisa > 0){
			$trcolor = "#FFF2DA";
		}else{
			$trcolor = "#E8FFDA";
		}
	}
}

$notakuilabel = "";
if(!empty($model->nota_penjualan_id)){
	$nota = "<a onclick='infoNota(\"".$model->notaPenjualan->kode."\")'>".$model->notaPenjualan->kode."</a>";
}else{
	$nota = "";
}
if(!empty($model->voucher_penerimaan_id)){
	$modKuitansi = \app\models\TKuitansi::findOne(['reff_penerimaan'=>$model->kode]);
	if(!empty($modKuitansi)){
		$kuitansi = "<br><a onclick='infoKuitansi({$modKuitansi->kuitansi_id})' >".$modKuitansi->nomor."</a>";
	}else{
		$kuitansi = "<br><a onclick='createKuitansi(this);' class='tooltips btn btn-outline btn-xs blue' data-original-title='Buat Kuitansi'><i class='fa fa-plus' style='font-size: 1rem;'></i> Kuitansi</a>";
	}
}else{
	$kuitansi = "";
}

$notakuilabel .= $nota.$kuitansi;
?>
<tr style="background-color: <?= $trcolor; ?>">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]voucher_penerimaan_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]kode',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]kode_bbm',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]tanggal',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
	<td style="vertical-align: middle; padding: 3px; text-align: center; font-weight: 600;" class="td-kecil font-green-seagreen">
		<span id="kode_display"><?= (!empty($model->voucher_penerimaan_id))?"<span class='font-grey-gallery'>".$model->kode."</span>":"Auto<br>Generate" ?></span>
		<span id="kode_bbm_display"><?= (!empty($model->voucher_penerimaan_id))?"<a onclick=\"detailBbm('".$model->kode."')\" class='font-blue'>".$model->kode_bbm."</a>":"" ?></span>
	</td>
	<td style="vertical-align: middle; padding: 3px;" class="td-kecil">
		<?php echo yii\bootstrap\Html::activeDropDownList($model, '[ii]tipe', \app\models\MDefaultValue::getOptionList('tipe-voucher-penerimaan'),['class'=>'form-control','disabled'=>$disabled,'style'=>'font-size: 1.1rem; padding:3px; height: 30px;']); ?>
	</td>
	<td style="vertical-align: middle; padding: 3px;" class="td-kecil">
		<?php echo yii\bootstrap\Html::activeDropDownList($model, '[ii]mata_uang', \app\models\MDefaultValue::getOptionListLabelValue('mata-uang'),['class'=>'form-control','disabled'=>$disabled,'style'=>'font-size: 1.1rem; padding:3px; height: 30px;']); ?>
	</td>
	<td style="vertical-align: middle; padding: 3px;" class="td-kecil">
		<?php echo yii\bootstrap\Html::activeDropDownList($model, '[ii]akun_kredit', \app\models\MAcctRekening::getOptionListBank(),['class'=>'form-control','disabled'=>$disabled,'prompt'=>'','style'=>'font-size: 1.1rem; padding:3px; height: 30px;']); ?>
	</td>
	<td style="vertical-align: middle; padding: 3px;" class="td-kecil">
		<?php echo yii\bootstrap\Html::activeTextInput($model, '[ii]sender', ['class'=>'form-control','disabled'=>$disabled,'style'=>'font-size: 1.1rem; padding:3px; height: 30px;']); ?>
	</td>
	<td style="vertical-align: middle; padding: 3px;" class="td-kecil">
		<?php echo yii\bootstrap\Html::activeTextarea($model, '[ii]deskripsi', ['class'=>'form-control','disabled'=>$disabled,'style'=>'font-size: 1.1rem; padding:3px; height: 30px;']); ?>
	</td>
	<td style="vertical-align: middle; padding: 3px;" class="td-kecil">
		<?php $model->total_nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_nominal); ?>
		<?php echo \yii\helpers\Html::activeTextInput($model, '[ii]total_nominal', ['class'=>'form-control float','disabled'=>$disabled,'style'=>'padding:3px; font-size: 1.2rem;  height: 30px;','onblur'=>'setTotal()']); ?>
	</td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil" id='td-action'>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]nota_penjualan_id'); ?>
        <span id="place-nota" style="font-size: 1.1rem; display: <?= $btnedit ?>"> 
			<?= $notakuilabel ?> 
		</span>
		<span id="place-notabtn" style="display: <?= $btnsave ?>">
			<a class="btn btn-xs blue-steel" id="btn-addnota" onclick="pickPanelNota(this)" style="margin-top: 5px; font-size: 1rem;"><i class="fa fa-plus"></i> Nota</a>
		</span>
		<span id="place-editnotakui" style="display: none;">
			<a class="btn btn-xs blue-steel" id="btn-addnota" onclick="pickPanelNota(this)" style="margin-top: 5px; font-size: 1rem;"><i class="fa fa-edit"></i> Nota</a>
			<a onclick='editKuitansi(this);' class='tooltips btn btn-outline btn-xs blue' data-original-title='Edit Kuitansi'><i class='fa fa-edit'></i> Kuitansi</a>
		</span>
	</td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil" id='td-action'>
		<span id="place-savebtn" style="display: <?= $btnsave ?>">
			<a class="btn btn-xs hijau" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="save(this);"><i class="fa fa-check"></i></a>
		</span>
		<span id="place-cancelbtn" style="display: <?= $btncancel ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
		</span>
		<span id="place-editbtn" style="display: <?= $btninfo ?>">
			<a class="btn btn-xs blue btn-outline" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="<?= $onclickinfopiutang; ?>"><i class="fa fa-info-circle"></i></a>
		</span>
		<span id="place-editbtn" style="display: <?= $btnedit ?>">
			<a class="btn btn-xs dark btn-outline" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="edit(this);"><i class="fa fa-edit"></i></a>
		</span>
		<span id="place-deletebtn" style="display: <?= $btndelete ?>">
			<a class="btn btn-xs red-flamingo btn-outline" id="close-btn-this" onclick="deleteItem(<?= $model->voucher_penerimaan_id ?>);"><i class="fa fa-trash-o"></i></a>
		</span>
    </td>
</tr>