<tr style="">
    <td style="vertical-align: middle; text-align: center; padding: 2px;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]ajuandinas_grader_id',['style'=>'width:50px;']); ?>
		<?= $model->kode ?>
    </td>
    <td style="vertical-align: middle; text-align: center; padding: 2px;" class="td-kecil">
		<?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?>
    </td>
    <td style="vertical-align: middle; text-align: center; padding: 2px;" class="td-kecil">
		<?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_dibutuhkan) ?>
    </td>
    <td style="vertical-align: middle; text-align: right; padding: 2px;" class="td-kecil">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_ajuan); ?>
    </td>
	<td class="text-align-center" style="padding: 2px;">
		<?php echo \app\models\TApproval::findOne(['reff_no'=>$model->kode])->StatusLite; ?>
	</td>
	<td class="text-align-center" style="padding: 2px;">
		<?php
		if(!empty($model->voucher_pengeluaran_id)){
			echo $model->voucherPengeluaran->Status_bayarLite;
		}else{
			echo "-";
		}
		?>
	</td>
	<td class="text-align-center" style="padding: 2px;">
		<a class="btn btn-xs btn-outline blue-hoki" id="btn-info" onclick="detailAjuanDinas(<?= $model->ajuandinas_grader_id ?>);"><i class="fa fa-info-circle"></i></a>
		<?php if(empty($model->voucher_pengeluaran_id)){ ?>
			<a class="btn btn-xs red" id="btn-delete" onclick="deleteAjuanDinas(<?= $model->ajuandinas_grader_id ?>);" style="margin-left: -7px"><i class="fa fa-trash-o"></i></a>
		<?php } ?>
		
	</td>
</tr>