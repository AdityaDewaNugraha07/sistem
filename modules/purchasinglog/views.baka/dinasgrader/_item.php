<?php
if ($model->approval_status == "APPROVED") {
    $bg = "background: rgba(210,250,210,0.5)";
} else if ($model->approval_status == "REJECTED") {
    $bg = "background: rgba(250,210,210,0.5)";
} else {
    $bg = "";
}
?>
<tr style="<?php echo $bg;?>">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]dkg_id',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<b><?= $model->kode ?></b>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php
        if ($model->jenis_log == "LA") {
            echo "Log Alam";
        } else if ($model->jenis_log == "LS") {
            echo "Log Sengon";
        } else {
            echo "Log Jabon";
        }
        ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?= $model->tipe ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= $model->graderlog_nm ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= $model->wilayahDinas->wilayah_dinas_nama ?>
    </td>
    <td style="vertical-align: middle; text-align: right; " class="td-kecil">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat( app\models\HKasDinasgrader::getSaldoKas($model->graderlog_id) ); ?>
    </td>
    <td style="vertical-align: middle; text-align: right; " class="td-kecil">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat( app\models\HKasMakangrader::getSaldoKas($model->graderlog_id) ); ?>
    </td>
    <td style="vertical-align: middle; text-align: center; " class="td-kecil">
		<?php echo yii\bootstrap\Html::activeDropDownList($model, 'status', [\app\models\TDkg::AKTIF_DINAS=>'Aktif Dinas', \app\models\TDkg::NON_AKTIF_DINAS=>'Non-Aktif Dinas'],['style'=>'width:110px;','onchange'=>'changeStatus('.$model->dkg_id.',this.value);']) ?>
    </td>
	<td class="text-align-center">
		<a onclick='detailBiaya(<?= $model->dkg_id ?>)' class="btn btn-outline grey-gallery btn-xs" style='font-size: 1.1rem;'> Detail</a>
		<?php
        if ($model->tanggal < '2021-07-01' || ($model->tanggal > '2021-07-01' && $model->approval_status  == "APPROVED")) {
        ?>
        <a onclick='biayaBiaya(<?= $model->dkg_id ?>)' class="btn btn-outline blue-hoki btn-xs" style='font-size: 1.1rem; margin-top: 5px;'>Biaya-Biaya</a>
        <?php
        }
        ?>
        <?php /* <a class="btn btn-xs btn-outline dark" id="btn-edit" onclick="editDKG(<?= $model->dkg_id ?>);" style="margin-left: -5px; display: <?= $none; ?>;"><i class="fa fa-edit"></i></a> */?>
        <?php /* <a class="btn btn-xs btn-outline dark" id="btn-edit" onclick="editDKG(<?= $model->dkg_id ?>);" style="margin-left: -5px; display: <?= $none; ?>;"><i class="fa fa-edit"></i></a>+<?php */?>
		<?php /* <a class="btn btn-xs red" id="btn-delete" onclick="deleteItem(<?= $model->dkg_id ?>);" style="margin-top: 5px; display: <?= $none; ?>;"><i class="fa fa-trash-o"></i></a> */?>
	</td>
</tr>