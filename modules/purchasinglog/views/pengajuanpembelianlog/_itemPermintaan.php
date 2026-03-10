<?php
$approver3 = app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->approver_3]);
?>
<tr style="">
	<td class="" style="vertical-align: middle; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_id',[]); ?>
		<span class="no_urut"></span>
	</td>
	<td class="" style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]kode',[]); ?>
        <?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]total_m3',[]); ?>
		<?= $model->kode ?>
	</td>
	<td class="" style="vertical-align: middle; text-align: center;">
		<?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?>
	</td>
	<td class="" style="vertical-align: middle; text-align: center;">
		<?= $model->tujuan ?>
	</td>
	<td class="" style="vertical-align: middle; text-align: center; font-size: 1.2rem;">
		<?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_dibutuhkan_awal)." sd ".app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_dibutuhkan_akhir); ?>
	</td>
    <td class="td-kecil2" style="vertical-align: middle; text-align: center;">
        <?= "<b>".$model->dibuatOleh->pegawai_nama."</b><br>".$model->dibuatOleh->departement->departement_nama; ?>
	</td>
    <td class="td-kecil2" style="vertical-align: middle; text-align: center;">
		<?= "<b>".$approver3->approvedBy->pegawai_nama."</b><br>".$approver3->status." at ".\app\components\DeltaFormatter::formatDateTimeForUser2($approver3->updated_at); ?>
	</td>
    <td class="" style="vertical-align: middle; text-align: right;">
		<?= number_format($model->total_m3)." M<sup>3</sup>"; ?>
	</td>
    <td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <a class="btn btn-xs grey-gallery btn-outline" onclick="detailPermintaan(<?= $model->pmr_id ?>)" style="font-size: 1rem; line-height: 1">Detail<br>Permintaan</a>
		<a class="btn btn-xs red" onclick="cancelItem(this,'totalPermintaan()')"><i class="fa fa-remove"></i></a>
	</td>
</tr>