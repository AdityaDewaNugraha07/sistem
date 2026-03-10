<?php
$sql = "SELECT jumlah
        FROM view_stock_itemsub
        WHERE reff_detail_id = $modDetail->terima_bhp_sub_id";
$modStockItemsub = Yii::$app->db->createCommand($sql)->queryOne();
?>
<tr>
    <td style="vertical-align: middle; text-align: center; "<?= (!empty($modDetail->cancel_transaksi_id) ? "background-color:#ffcbc3" : "") ?> class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil"><?= $modDetail->bhpId->bhp_nm; ?></td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center"><?= $modDetail->bhpId->bhp_satuan; ?></td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center"><?= $modDetail->terimaBhpSub->target_plan; ?></td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center"><?= $modDetail->terimaBhpSub->target_peruntukan; ?></td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center"><?= ($modStockItemsub) ? $modStockItemsub['jumlah'] : 0; ?></td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center"><?= $modDetail->qty; ?></td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center"><?= $modDetail->deptPeruntukan->departement_nama; ?></td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center"><?= ($modDetail->asset_peruntukan) ? $modDetail->asset->kode. " ".$modDetail->asset->inventaris_nama :''; ?></td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center"><?= $modDetail->reff_no; ?></td>
    <td style="vertical-align: middle;" class="td-kecil text-align-left"><?= $modDetail->keterangan; ?></td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
        <?php if(!empty($modDetail->cancel_transaksi_id)){ ?>
			<span class="label label-sm label-danger"><?= app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
		<?php }else{ ?>
			<?php //if(Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER){ ?>
				<a class="btn btn-xs btn-outline red-flamingo" onclick="abortItem(<?= $modDetail->pemakaian_bhpsub_detail_id ?>,<?= $modDetail->pemakaian_bhpsub_id ?>);" style="font-size: 1rem"><i class="fa fa-remove"></i> Abort</a>
			<?php //} ?>
		<?php } ?>
    </td>
</tr>