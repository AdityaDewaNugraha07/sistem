<tr style="">
    <td style="vertical-align: middle; text-align: center; padding: 2px;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]realisasimakan_grader_id',['style'=>'width:50px;']); ?>
		<?= $model->kode ?>
    </td>
    <td style="vertical-align: middle; text-align: center; padding: 2px;" class="td-kecil">
		<?= \app\components\DeltaFormatter::formatDateTimeForUser($model->periode_awal)." sd ".
			\app\components\DeltaFormatter::formatDateTimeForUser($model->periode_akhir) ?>
    </td>
    <td style="vertical-align: middle; text-align: right;  padding: 2px;" class="td-kecil">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_realisasi); ?>
    </td>
	<td class="text-align-center" style="padding: 2px;">
		<a class="btn btn-xs btn-outline blue-hoki" id="btn-info" onclick="detailRealisasiMakan(<?= $model->realisasimakan_grader_id ?>);"><i class="fa fa-info-circle"></i></a>
		<a class="btn btn-xs red" id="btn-delete" onclick="deleteRealisasiMakan(<?= $model->realisasimakan_grader_id ?>);" style="margin-left: -7px"><i class="fa fa-trash-o"></i></a>
	</td>
</tr>