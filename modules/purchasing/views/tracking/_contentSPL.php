<style>
table.table.tracking tr td, table.table tr th{
	padding: 3px;
	font-size: 1.1rem;
}
</style>
<table class="table tracking" style="width: 100%; ">
	<tr>
		<td style="width: 20%"><?= Yii::t('app', 'Kode'); ?></td>
		<td style="width: 30%"><strong><?= $modSPL->spl_kode ?></strong></td>
		<td><?= Yii::t('app', 'Tanggal'); ?></td>
		<td><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modSPL->spl_tanggal); ?></strong></td>
	</tr>
</table>
<div class="table-scrollable">
	<table class="table tracking table-striped table-bordered table-hover">
		<thead>
			<tr style="background-color: #F1F4F7; ">
				<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Items'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Qty'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Harga Realisasi'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Supplier'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($modSPLDetail as $i => $detail){ ?>
			<tr>
				<td style="text-align: center;"><?= $i+1; ?></td>
				<td><?= $detail->bhp->Bhp_nm; ?></td>
				<td style="text-align: center;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->spld_qty) ?></td>
				<td style="text-align: right;"><?= !empty($detail->spld_harga_realisasi)?app\components\DeltaFormatter::formatNumberForUserFloat($detail->spld_harga_realisasi):0; ?></td>
				<td style="text-align: center;"><?= !empty($detail->suplier_id)? $detail->suplier->suplier_nm : "<center>-</center>"; ?></td>
				<td><?= $detail->spld_keterangan ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>