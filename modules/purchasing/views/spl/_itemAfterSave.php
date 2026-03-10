<?php
$sql = "SELECT * FROM map_spp_detail_reff 
		JOIN t_spp_detail ON t_spp_detail.sppd_id = map_spp_detail_reff.sppd_id
		JOIN t_spp ON t_spp.spp_id = t_spp_detail.spp_id
		JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhpd_id = map_spp_detail_reff.terima_bhpd_id
		JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_terima_bhp_detail.terima_bhp_id
		WHERE reff_no = '{$modSpl->spl_kode}' AND reff_detail_id = {$detail->spld_id} ";
$mod = Yii::$app->db->createCommand($sql)->queryOne();
?>
<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle; line-height:12px;">
        <?= $detail->bhp->Bhp_nm; ?>
		<?php
		if(!empty($mod)){
			echo "<br><span style='font-size:1.1rem'><a onclick='infoSPP({$mod['spp_id']},{$detail->bhp_id})'>".$mod['spp_kode']."</a></span>";
		}
		?>
    </td>
    <td style="vertical-align: middle;">
        <center><?= $detail->spld_qty; ?></center>
		<?= \yii\bootstrap\Html::activeHiddenInput($detail, '[ii]spld_qty'); ?>
    </td>
    <td style="vertical-align: middle;">
		<span class="satuan">
			<?= !empty($detail->bhp_id)?$detail->bhp->bhp_satuan:""; ?>
		</span>
    </td>
    <td style="vertical-align: middle; text-align: right;">
		<?= \app\components\DeltaFormatter::formatNumberForUser($detail->spld_harga_estimasi); ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($detail, '[ii]spld_harga_estimasi'); ?>
    </td>
    <td style="vertical-align: middle; text-align: right;">
        <?= \app\components\DeltaFormatter::formatNumberForUser($detail->spld_harga_estimasi * $detail->spld_qty); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($detail, '[ii]subtotal',['readonly'=>'readonly','value'=>($detail->spld_harga_estimasi * $detail->spld_qty)] ); ?>
    </td>
    <td style="vertical-align: middle; font-size: 1.1rem; line-height:10px;">
		<?= !empty($detail->spld_keterangan)?$detail->spld_keterangan:"<center> - </center>"; ?>
    </td>
    <td style="vertical-align: middle;">
		<?= !empty($detail->suplier_id)?$detail->suplier->suplier_nm:"<center> - </center>"; ?>
    </td>
    <td style="vertical-align: middle; font-size: 1.1rem">
		<?php
		if(!empty($mod)){
			echo "<a onclick='infoTBP({$mod['terima_bhp_id']},{$detail->bhp_id})'>".$mod['terimabhp_kode']."</a>";
		}else{
			echo "<center>-</center>";
		}
		?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
		-
    </td>
</tr>