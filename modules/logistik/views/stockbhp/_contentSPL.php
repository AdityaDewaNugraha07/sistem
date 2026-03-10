<h5 style="font-weight: bold;"><?= Yii::t('app', 'Surat Pesanan Langsung (SPL)'); ?></h5>
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-advance table-hover">
		<thead>
			<tr >
				<th style="text-align: center;"> # </th>
				<th style="text-align: center;"> Tanggal </th>
				<th style="text-align: center;"> Suplier </th>
				<th style="text-align: center;"> Qty </th>
				<th style="text-align: center;"> Harga </th>
				<th style="text-align: center;"> Kode SPL </th>
			</tr>
		</thead>
		<?php
			foreach($models as $i => $detail){
				$modBhp = app\models\MBrgBhp::findOne($detail['bhp_id']);
				$modSuplier = app\models\MSuplier::findOne($detail['suplier_id']);
		?>
		<tr>
			<td class="td-kecil text-align-center"><?= $i+1; ?></td>
			<td class="td-kecil text-align-center"><?= app\components\DeltaFormatter::formatDateTimeForUser($detail['spl_tanggal']) ?></td>
			<td class="td-kecil " style="text-align: center;"> <?= !empty($modSuplier)?$modSuplier->suplier_nm:'<center>-</center>'; ?> </td>
			<td class="td-kecil " style="text-align: right;"> <?= app\components\DeltaFormatter::formatNumberForUserFloat($detail['spld_qty']); ?> <?= $modBhp->bhp_satuan ?></td>
			<td class="td-kecil " style="text-align: right;"> <?= !empty($detail['spld_harga_realisasi'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['spld_harga_realisasi']):'<center>-</center>'; ?></td>
			<td class="td-kecil">
				<a href="javascript:;" style="text-align: center; color: #5594CA" onclick="infoSPL(<?= $detail['spl_id'] ?>,<?= $detail['bhp_id'] ?>)"><?= $detail['spl_kode'] ?></a>
			</td>
		</tr>
		<?php } ?>
	</table>
</div>