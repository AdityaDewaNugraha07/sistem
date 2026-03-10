<tr style="">
	<td class="td-kecil"  style="font-size:1.2rem; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<span class="no_urut"><?= $no; ?></span>
	</td>
	<td class="td-kecil"  style="font-size:1.2rem;">
		<b><?= $detail['spo_kode']; ?></b>
	</td>
	<td class="td-kecil"  style="font-size:1.2rem;">
		<?= !empty($detail['spo_tanggal'])?app\components\DeltaFormatter::formatDateTimeForUser2($detail['spo_tanggal']):""; ?>
	</td>
	<td class="td-kecil"  style="font-size:1.2rem;">
		<?= !empty($detail['bhp_kode'])?'<b>'.$detail['bhp_kode'].'</b><br>':""; ?>
		<?php
			$modBhp = app\models\MBrgBhp::findOne($detail['bhp_id']);
			echo $modBhp->Bhp_nm;
		?>
	</td>
	<td class="td-kecil" style="text-align: center; font-size: 1.1rem">
		<?= (!empty($detail['spod_qty'])?$detail['spod_qty']:"0")."<br>".(!empty($detail['bhp_satuan'])?$detail['bhp_satuan']:""); ?>
	</td>
	<?php
	
	?>
	<td class="td-kecil" style="text-align: center; font-size: 1.1rem">
		<?php echo $par['qty_terima']."<br>".(!empty($detail['bhp_satuan'])?$detail['bhp_satuan']:"") ?>
	</td> 
	<td class="td-kecil" style="padding:3px;">
		<?php echo $detail['suplier_nm']; ?>
	</td>
	<td class="td-kecil" style="text-align: center;">
		<?= $par['html_status']; ?>
	</td>
	<td class="td-kecil" style="font-size: 0.8rem;">
		<?= !empty($detail['spod_keterangan'])?$detail['spod_keterangan']:"<center>-</center>"; ?>
	</td>
	<td class="td-kecil" style="font-size: 0.9rem; text-align: center; vertical-align: middle;">
		<a href="javascript:void(0);" onclick="spbTerkait('<?= $detail['spo_id']; ?>')">Lihat</a>
	</td>
	<td class="td-kecil" style="font-size: 1rem !important; text-align: center; vertical-align: middle;">
		<?php echo $par['kode_terima']; ?>
	</td>
</tr>