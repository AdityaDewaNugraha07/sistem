<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle; line-height:13px;">
		<span class="pull-left">
			<?= $detail->bhp->Bhp_nm; ?>
			<?php 
			if(!empty($mod)){
				echo "<br>";
				echo "<span style='font-size:1.2rem; margin-top:-5px; cursor: pointer;' class='font-blue-steel' onclick='sppDetail(".$mod['spp_id'].",".$detail->bhp_id.")'>".$mod['spp_kode']."</span>";
			}
			?>
		</span>
		<span class="pull-right">
			<a onclick="penawaranTerpilih('<?= $detail->spod_id ?>','SPO')" id="tbl-penawaran" style="font-size: 1.1rem;"><i class="fa fa-info-circle"></i> Lihat<br>Penawaran</a>
		</span>
    </td>
	<td style="vertical-align: middle;">
        <center><?php 
			$stock = \app\models\HPersediaanBhp::getCurrentStock($detail->bhp_id);
			$stock = (!empty($stock)?$stock:0);
			echo app\components\DeltaFormatter::formatNumberForUserFloat($stock);
		?></center>
    </td>
    <td style="vertical-align: middle;">
        <center><?= $detail->qty_kebutuhan; ?></center>
    </td>
    <td style="vertical-align: middle;">
        <center><?= $detail->spod_qty; ?></center>
		<?php echo yii\bootstrap\Html::activeHiddenInput($detail, '[ii]spod_qty',['disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?= $detail->bhp->bhp_satuan; ?>
    </td>
    <td style="vertical-align: middle; text-align: right;">
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->spod_harga); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($detail, '[ii]spod_harga',['disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle; text-align: right;">
        <?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->spod_harga * $detail->spod_qty); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($detail, '[ii]subtotal',['value'=>($detail->spod_harga * $detail->spod_qty)] ); ?>
    </td>
    <td style="vertical-align: middle; padding: 5px; font-size: 1.1rem;">
		<?= !empty($detail->spod_keterangan)?$detail->spod_keterangan:"<center> - </center>"; ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
         - 
    </td>
</tr>