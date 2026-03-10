<?php
$approve_status = "";
if ($model->approval_status == "Not Confirmed") {
    $status_delete = true;
    $approve_status = '<span class="label label-default label-sm" style="font-size: 9px;">Not Confirmed</span>';
    //$bg_color = "";
} else {
    $status_delete = false;
    if ($model->approval_status == "APPROVED") {
        //$bg_color = "background: rgba(210,250,210,0.5)";
        $approve_status = '<span class="label label-success label-sm" style="font-size: 9px;">APPROVED</span>';
    } else if ($model->approval_status == "REJECTED") {
        //$bg_color = "background: rgba(250,210,210,0.5)";
        $approve_status = '<span class="label label-danger label-sm" style="font-size: 9px;">REJECTED</span>';
    } else {
        $approve_status = '';
    }
}
?>
<tr>
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
    <td style="vertical-align: middle; text-align: right;  padding: 2px;" class="td-kecil">
		<?php echo $approve_status;?>
    </td>
	<td class="text-align-center" style="padding: 2px;">
		<a class="btn btn-xs btn-outline blue-hoki" id="btn-info" onclick="detailRealisasiMakan(<?= $model->realisasimakan_grader_id ?>);"><i class="fa fa-info-circle"></i></a>
        <?php
        if ($status_delete) {
        ?>
		    <a class="btn btn-xs red" id="btn-delete" onclick="deleteRealisasiMakan(<?= $model->realisasimakan_grader_id ?>);" style="margin-left: -7px"><i class="fa fa-trash-o"></i></a>
        <?php
        }
        ?>
	</td>
</tr>