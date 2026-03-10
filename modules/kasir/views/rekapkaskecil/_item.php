<tr style="">
	<td class=""  style="font-size:1.2rem; text-align: center; padding: 5px;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<span class="no_urut"><?= $i+1; ?></span>
	</td>
	<td class="text-align-center"  style="font-size:1.2rem; padding: 5px;">
		<?= app\components\DeltaFormatter::formatDateTimeForUser($detail['tanggal']); ?>
	</td>
	<td class="text-align-center"  style="font-size:1.2rem; padding: 5px;">
		<b><?= $detail['kode']; ?></b>
	</td>
	<td class=""  style="font-size:1.2rem; padding: 5px;">
		<?= !empty($detail['deskripsi'])?$detail['deskripsi']:""; ?>
	</td>
	<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
		<?= !empty($detail['nominal'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['nominal']):0; ?>
	</td>
	<td class="text-align-center"  style="font-size:1.2rem; padding: 5px;">
		<?php
		if($detail['closing'] == TRUE){
			echo '<span class="label label-success" style="font-size: 1.2rem">CLOSED</span>';
		}else{
			echo '<span class="label label-info" style="font-size: 1.2rem">OPEN</span>';
		}
		?>
	</td>
</tr>