<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]dkg_id',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<b><?= $model->kode ?></b>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?= $model->tipe ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= $model->graderlog_nm ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
		<?= $model->wilayahDinas->wilayah_dinas_nama ?>
    </td>
    <td style="vertical-align: middle; text-align: right; " class="td-kecil">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat( $model->saldo_akhir_dinas ); ?>
    </td>
    <td style="vertical-align: middle; text-align: right; " class="td-kecil">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat( $model->saldo_akhir_makan ); ?>
    </td>
    <td style="vertical-align: middle; text-align: center; " class="td-kecil">
		<?php echo $model->status ?>
    </td>
    <td style="vertical-align: middle; text-align: center; " class="td-kecil">
		<?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($model->selesai_dinas_at) ?>
    </td>
	<td class="text-align-center">
		<a onclick='detailBiaya(<?= $model->dkg_id ?>)' class="btn btn-outline grey-gallery btn-xs" style='font-size: 1.1rem;'> Detail Dinas</a>
	</td>
</tr>