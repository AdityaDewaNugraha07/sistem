<?php
if(!empty($model->kas_besar_id)){
	$btnedit = '';
	$btnsave = 'none';
	$btncancel = 'none';
	$btndelete = '';
	$btnnota = false;
}else{
	$btnedit = 'none';
	$btnsave = '';
	$btncancel = '';
	$btndelete = 'none';
	$btnnota = true;
}
//if(!empty($model->nota_penjualan_id)){
//	$notakuilabel = "<a onclick='infoNota(\"".$model->notaPenjualan->kode."\")'>".$model->notaPenjualan->kode."</a>";
//	if(!empty($model->kas_besar_id)){
//		$modKuitansi = \app\models\TKuitansi::findOne(['reff_penerimaan'=>$model->kas_besar_id]);
//		if(!empty($modKuitansi)){
//			$notakuilabel .= "<br><a onclick='infoKuitansi({$modKuitansi->kuitansi_id})' >".$modKuitansi->nomor."</a>";
//		}else{
//			$notakuilabel .= "<br><a onclick='createKuitansi(this);' class='tooltips btn btn-outline btn-xs blue' data-original-title='Buat Kuitansi'><i class='fa fa-plus' style='font-size: 1rem;'></i> Kuitansi</a>";
//		}
//		if($model->tipe == "OUT"){
//			$notakuilabel .= "-";
//		}
//	}else{
//		$notakuilabel .= "";
//	}
//}else{
//	$notakuilabel = "";
//}


$notakuilabel = "";
if(!empty($model->nota_penjualan_id)){
	$nota = "<a onclick='infoNota(\"".$model->notaPenjualan->kode."\")'>".$model->notaPenjualan->kode."</a>";
}else{
	$nota = "";
}
if(!empty($model->kas_besar_id)){
	$modKuitansi = \app\models\TKuitansi::findOne(['reff_penerimaan'=>$model->kas_besar_id]);
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
<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]kas_besar_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]tanggal',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]penerima',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
	<td style="vertical-align: middle; padding: 3px;" class="td-kecil">
		<?php
		if($model->tipe == "IN"){
			echo yii\bootstrap\Html::activeDropDownList($model, '[ii]jenis_penerimaan', \app\models\MDefaultValue::getOptionList('jenisterima-kasbesar'),['class'=>'form-control','style'=>'font-size: 1.1rem; padding:3px; height: 25px;']);
		}
		?>
	</td>
	<td style="vertical-align: middle; padding: 3px;" class="td-kecil">
		<?php
		if($model->tipe == "IN"){
			echo yii\bootstrap\Html::activeDropDownList($model, '[ii]cara_transaksi', \app\models\MDefaultValue::getOptionListCustom('cara-bayar',"'Klik-BCA','Cek','Bilyet Giro','Transfer Bank'",'DESC'),['class'=>'form-control','style'=>'font-size: 1.1rem; padding:3px; height: 25px;','onchange'=>'caratransaksi(this)','onfocus'=>'lastvalue(this)']);
			echo yii\bootstrap\Html::hiddenInput('last_value',$model->cara_transaksi);
			echo yii\bootstrap\Html::activeHiddenInput($model, '[ii]reff_cara_transaksi');
			echo yii\bootstrap\Html::activeHiddenInput($model, '[ii]kredit');
			
			if($model->cara_transaksi != 'Tunai'){
				echo '<span id="place-caratransaksilabel" style="font-size: 1.2rem;">'. $model->reff_cara_transaksi .'</span>';
			}else{
				echo '<span id="place-caratransaksilabel" style="display:none; font-size: 1.2rem;"></span>';
			}
		}
		?>
	</td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextInput($model, '[ii]no_tandaterima', ['class'=>'form-control','style'=>'padding:3px; font-size: 1.2rem; height: 25px;']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextarea($model, '[ii]deskripsi', ['class'=>'form-control','style'=>'height:40px; font-size:1.2rem; padding:3px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php echo \yii\helpers\Html::activeTextInput($model, '[ii]nominal', ['class'=>'form-control float','onblur'=>'setTotal()','style'=>'font-size:1.2rem; padding:3px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
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
			<a class="btn btn-xs red" id="close-btn-this" onclick="deleteItem(<?= $model->kas_besar_id ?>);"><i class="fa fa-trash-o"></i></a>
		</span>
    </td>
</tr>