<?php
if(($model->status=="")||($model->status=="DIAJUKAN")){
	if(!empty($model->pengajuan_tagihan_id)){
		$btnedit = '';
		$btndelete = '';
		$btnsave = 'none';
		$btncancel = 'none';
	}else{
		$btnedit = 'none';
		$btndelete = 'none';
		$btnsave = '';
		$btncancel = '';
	}
}else{
	$btnedit = 'none';
	$btndelete = 'none';
	$btnsave = 'none';
	$btncancel = 'none';
}
$nominal = $model->nominal;
$model->nominal = !empty($model->status=="DITOLAK")?0:$model->nominal;
?>
<tr>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pengajuan_tagihan_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]suplier_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]terima_bhp_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]spo_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]spl_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]nomor_nota',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]nominal',['style'=>'width:50px;']); ?>
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<a onclick="infoTBP(<?= $modTerima->terima_bhp_id ?>);"><?= $modTerima->terimabhp_kode ?></a>
	</td>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<a onclick="infoSPL(<?= $modTerima->spl_id ?>);"><?= !empty($modTerima->spl_id)?$modTerima->spl->spl_kode:"-" ?></a>
	</td>
	<td class="td-kecil text-align-left" style="vertical-align: top ! important;">
		<?= $modTerima->suplier->suplier_nm ?> 
	</td>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<div class="input-group date date-picker">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]tanggal_nota',['class'=>'form-control','style'=>'width:100%; padding:2px; font-size:1.1rem; height:25px;','readonly'=>'readonly','placeholder'=>'Pilih Tanggal']); ?>
            <span class="input-group-btn">
                <button class="btn default" type="button" style="height: 25px; width: 20px; padding: 0px; margin-left: 0px; margin-right: 0px; border-width: 0px;">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
	</td>
    <td class="td-kecil text-align-left" style="vertical-align: top;">
		<div class="place-input-mode" style="display: <?= isset($input)?:"none" ?>">
			<?= Yii::$app->runAction("purchasing/pengajuantagihan/setKelengkapanBerkas",['model'=>$model,'tipe'=>'input']); ?>
		</div>
		<div class="place-view-mode" style="font-size: 1.1rem; display: <?= isset($view)?:"none" ?>">
			<?= Yii::$app->runAction("purchasing/pengajuantagihan/setKelengkapanBerkas",['model'=>$model,'tipe'=>'view']); ?>
		</div>
	</td>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<?= $model->nomor_nota ?><br>
	</td>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]no_fakturpajak', ['class'=>'form-control','style'=>'font-size:1.1rem; padding:3px; height:25px;','placeholder'=>'Ketik No. Faktur Pajak']) ?>
	</td>
	<td class="td-kecil text-align-right" style="vertical-align: top ! important;">
		<?php
		if($model->status=="DITOLAK"){
			echo "<strike>".\app\components\DeltaFormatter::formatNumberForUserFloat($nominal)."</strike>";
		}else{
			echo \app\components\DeltaFormatter::formatNumberForUserFloat($nominal);
		}
		?>
	</td>
	<td class="td-kecil text-align-center" id="place-status" style="vertical-align: top ! important;">
		<?php
		if(!empty($model->status)){
			if($model->status == "DIAJUKAN"){
				echo "<label style='font-size:1rem;' class='label label-warning tooltips' title='Menunggu Realisasi Kasir'>".$model->status."</label>";
			}else if($model->status == "DIREALISASI"){
				echo "<label style='font-size:1rem; cursor:pointer' class='label label-success tooltips' title='".$model->keterangan."' onclick='updateBerkas(".$model->pengajuan_tagihan_id.")'>DIREALISASI</label>";
			}else if($model->status == "DITOLAK"){
				echo "<label style='font-size:1rem;' class='label label-danger tooltips' title='".$model->keterangan."'>DITOLAK</label>";
			}
		}else{
			echo "-";
		}
		?>
        <?php
		if(!empty($modTerima->voucher_pengeluaran_id)){
			$voucher = app\models\TVoucherPengeluaran::findOne($modTerima->voucher_pengeluaran_id);
			if(!empty($voucher)){
				echo "<br><a onclick='infoVoucher(\"{$modTerima->voucher_pengeluaran_id}\")'>".$voucher->kode."</a>";
			}
		}
		?>
	</td>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<span id="place-savebtn" style="display: <?= $btnsave ?>">
			<a class="btn btn-xs hijau" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="save(this);"><i class="fa fa-check"></i></a>
		</span>
		<span id="place-cancelbtn" style="display: <?= $btncancel ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItem(this,'setTotal();');"><i class="fa fa-remove"></i></a>
		</span>
		<span id="place-editbtn" style="display: <?= $btnedit ?>">
			<a class="btn btn-xs dark btn-outline" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="edit(this);"><i class="fa fa-edit"></i></a>
		</span>
		<span id="place-deletebtn" style="display: <?= $btndelete ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="deleteItem(this);"><i class="fa fa-trash-o"></i></a>
		</span>
	</td>
</tr>