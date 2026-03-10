<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]suplier_id',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: top; text-align: left;" class="td-kecil">
		<b><?= !empty($model->suplier_nm)?$model->suplier_nm:"" ?></b> <?= (!empty($model->suplier_nm_company)?"<br>".$model->suplier_nm_company:"") ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil">
		<?= $model->suplier_almt ?>
    </td>
    <td style="vertical-align: top; text-align: right; " class="td-kecil">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat( $saldo ); ?> &nbsp;
    </td>
    <td style="vertical-align: top; text-align: center; font-size: 1.1rem !important;" class="td-kecil">
		<?= !empty($last_transaksi)?\app\components\DeltaFormatter::formatDateTimeForUser2($last_transaksi):"-"; ?>
    </td>
	<td class="text-align-left">
        <a style="margin-right: 0px;" class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="infoSuplier(<?= $model->suplier_id ?>)" data-original-title="Lihat Info Suplier"><i class="fa fa-info-circle"></i></a>
        <a onclick='riwayatSaldo(<?= $model->suplier_id ?>)' class="btn btn-outline blue-soft btn-xs tooltips" style='font-size: 1.1rem;' data-original-title="Lihat Riwayat transaksi yang pernah dilakukan"><i class="icon-wallet"></i> Riwayat Saldo </a>
	</td>
</tr>