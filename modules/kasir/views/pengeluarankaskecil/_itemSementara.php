<?php
if(!empty($model->kas_bon_id)){
	$btnedit = '';
	$btnsave = 'none';
	$btncancel = 'none';
	$btndelete = '';
}else{
	$btnedit = 'none';
	$btnsave = '';
	$btncancel = '';
	$btndelete = 'none';
}
?>
<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]kas_bon_id',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?php $fontblue = ($model->kode!='New Generate')?'font-blue':''; ?>
		<?php echo \yii\helpers\Html::activeTextInput($model, '[ii]kode', ['class'=>'form-control text-align-center '.$fontblue,'disabled'=>'disabled','style'=>'font-weight:bold; font-size:1.1rem;']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<div class="input-group input-sm date date-picker bs-datetime" style="padding:3px;">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]tanggal',['class'=>'form-control','style'=>'width:70%; font-size:1.2rem; padding:3px;','readonly'=>'readonly','placeholder'=>'Tgl Kas Bon']); ?>
            <span class="input-group-addon">
                <button class="btn default" type="button" style="margin-left: -40px;">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextInput($model, '[ii]penerima', ['class'=>'form-control ','style'=>'font-size:1.2rem; padding:3px;']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextarea($model, '[ii]deskripsi', ['class'=>'form-control','style'=>'height:50px; font-size:1.2rem; padding:3px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php echo \yii\helpers\Html::activeTextInput($model, '[ii]nominal', ['class'=>'form-control float','onblur'=>'setTotal()','style'=>'padding:3px; font-size:1.2rem;']); ?>
    </td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php
		$ret = "";
		$mod = "";
		if(!empty($model->kas_bon_id)){
//			if(!empty($model->bkk_id)){
//				echo '<a onclick="detailBkk('.$model->bkk_id.')" >'.$model->bkk->kode.'</a>';
//				$btnedit = 'none';
//				$btndelete = 'none';
//				if(!empty($model->bkk->voucher_pengeluaran_id)){
//					$mod = \app\models\TVoucherPengeluaran::findOne($model->bkk->voucher_pengeluaran_id);
//					echo "<br>";
//					echo '<a onclick="detailBbk('.$mod->voucher_pengeluaran_id.')" >'.$mod->kode.'</a>';
//					if($mod->status_bayar == "PAID"){
//						echo "<br>";
//						echo '<a class="btn btn-sm green-seagreen btn-outline" style="font-size:1rem; padding: 3px;" onclick="terimauangganti('.$model->kas_bon_id.')"><i class="fa fa-download"></i> Terima Uang</a>';
//					}
//				}
//			}else{
//				$ret = '<a class="btn btn-sm blue btn-outline" target="BLANK" style="font-size:1rem; padding: 3px;" onclick="createGkk('.$model->kas_bon_id.')"><i class="fa fa-share"></i> Buat GKK </a>';
//			}
			if(!empty($model->gkk_id)){
				echo '<a onclick="detailGkk('.$model->gkk_id.')" >'.$model->gkk->kode.'</a>';
				$btnedit = 'none';
				$btndelete = 'none';
				if(!empty($model->gkk->voucher_pengeluaran_id)){
					$mod = \app\models\TVoucherPengeluaran::findOne($model->gkk->voucher_pengeluaran_id);
					echo "<br>";
					echo '<a onclick="detailBbk('.$mod->voucher_pengeluaran_id.')" >'.$mod->kode.'</a>';
					if($mod->status_bayar == "PAID"){
						echo "<br>";
						echo '<a class="btn btn-sm green-seagreen btn-outline" style="font-size:1rem; padding: 3px;" onclick="terimauangganti('.$model->kas_bon_id.')"><i class="fa fa-download"></i> Terima Uang</a>';
					}
				}
			}else{
				$ret = '<a class="btn btn-sm blue btn-outline" target="BLANK" style="font-size:1rem; padding: 3px;" onclick="createGkk('.$model->kas_bon_id.')"><i class="fa fa-share"></i> Buat GKK </a>';
			}
		}
		?>
        <?= $ret ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
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
			<a class="btn btn-xs red" id="close-btn-this" onclick="deleteItem(<?= $model->kas_bon_id ?>);"><i class="fa fa-trash-o"></i></a>
		</span>
    </td>
</tr>