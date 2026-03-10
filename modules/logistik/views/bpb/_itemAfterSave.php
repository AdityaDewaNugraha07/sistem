<tr style="<?= (!empty($detail->cancel_transaksi_id)?"background-color:#ffcbc3":"") ?>">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1 ?></span>
    </td>
    <td style="vertical-align: middle;">
        <?= $detail->bhp_nama ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?= $detail->qty_kebutuhan ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?= $detail->jml_terpenuhi ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?= $detail->bpbd_jml ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?= $detail->current_stock ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <span class="satuan"><?= $detail->satuan; ?></span>
    </td>
    <td style="vertical-align: middle; padding:5px; font-size:1.1rem;">
        <?= !empty($detail->bpbd_ket)?$detail->bpbd_ket:' - ' ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
		<?php if(!empty($detail->cancel_transaksi_id)){ ?>
			<span class="label label-sm label-danger"><?= app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
		<?php }else{ ?>
			<?php if(Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER){ ?>
				<a class="btn btn-xs btn-outline red-flamingo" onclick="abortItem(<?= $detail->bpbd_id ?>,<?= $modBpb->bpb_id ?>);" style="font-size: 1rem"><i class="fa fa-remove"></i> Abort</a>
			<?php } ?>
		<?php } ?>
    </td>
</tr>