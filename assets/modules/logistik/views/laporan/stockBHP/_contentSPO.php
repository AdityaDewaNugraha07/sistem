<h5 style="font-weight: bold;"><?= Yii::t('app', 'Surat Purchase Order (SPO)'); ?></h5>
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-advance table-hover">
		<thead>
			<tr >
				<th style="text-align: center;"> # </th>
				<th style="text-align: center;"> Tanggal </th>
				<th style="text-align: center;"> Suplier </th>
				<th style="text-align: center;"> Qty </th>
				<th style="text-align: center;"> Harga </th>
				<th style="text-align: center;"> Kode SPB </th>
			</tr>
		</thead>
		<?php
			foreach($models as $i => $detail){
				$modBhp = app\models\MBrgBhp::findOne($detail['bhp_id']);
				$modSuplier = app\models\MSuplier::findOne($detail['suplier_id']);
		?>
		<tr>
			<td class="td-kecil text-align-center"><?= $i+1; ?></td>
			<td class="td-kecil text-align-center"><?= app\components\DeltaFormatter::formatDateTimeForUser($detail['spo_tanggal']) ?></td>
			<td class="td-kecil"> <?= $modSuplier->suplier_nm; ?> </td>
			<td class="td-kecil text-align-right"> <?= app\components\DeltaFormatter::formatNumberForUserFloat($detail['spod_qty']); ?> <?= $modBhp->bhp_satuan ?> </td>
			<td class="td-kecil text-align-right"> <?= !empty($detail['spod_qty'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['spod_qty']):"<center>-</center>"; ?> </td>
			<td class="td-kecil">
				<a href="javascript:;" style="text-align: center; color: #5594CA" onclick="infoSPO(<?= $detail['spo_id'] ?>,<?= $detail['bhp_id'] ?>)"><?= $detail['spo_kode'] ?></a>
			</td>
		</tr>
		<?php } ?>
	</table>
</div>