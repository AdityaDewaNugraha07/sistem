<style>
table.table.tracking tr td, table.table tr th{
	padding: 3px;
	font-size: 1.1rem;
}
</style>
<table class="table tracking" style="width: 100%; ">
	<tr>
		<td style="width: 20%"><?= Yii::t('app', 'Kode'); ?></td>
		<td style="width: 30%"><strong><?= $modSPO->spo_kode ?></strong></td>
		<td style="width: 20%"><?= Yii::t('app', 'Status Approve'); ?></td>
		<td style="width: 30%"><strong><?= isset($modApproval->Status)?$modApproval->Status:' - '; ?></strong></td>
	</tr>
	<tr>
		<td><?= Yii::t('app', 'Tanggal'); ?></td>
		<td><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modSPO->spo_tanggal); ?></strong></td>
		<td><?= Yii::t('app', 'Assigned To'); ?></td>
		<td><strong><?= isset($modApproval->assigned_to)?$modApproval->assignedTo->pegawai_nama:' - '; ?></strong></td>
	</tr>
	<tr>
		<td><?= Yii::t('app', 'Supplier'); ?></td>
		<td><strong><?= $modSPO->suplier->suplier_nm ?></strong></td>
		<td><?= Yii::t('app', 'Approved By'); ?></td>
		<td><strong><?= isset($modApproval->approved_by)?$modApproval->approvedBy->pegawai_nama:' - '; ?></strong></td>

	</tr>
	<tr>
		<td><?= Yii::t('app', 'Status Pembayaran'); ?></td>
		<td><strong><?= $modSPO->spo_status_bayar; ?></strong></td>
		<td><?= Yii::t('app', 'Approved At'); ?></td>
		<td><strong><?= isset($modApproval->created_at)?app\components\DeltaFormatter::formatDateTimeForUser2($modApproval->created_at):' - '; ?></strong></td>
	</tr>
</table>
<div class="table-scrollable">
	<table class="table tracking table-striped table-bordered table-hover">
		<thead>
			<tr style="background-color: #F1F4F7; ">
				<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Items'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Qty'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Harga'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($modSPODetail as $i => $detail){ ?>
			<tr>
				<td style="text-align: center;"><?= $i+1; ?></td>
				<td><?= $detail->bhp->bhp_nm; ?></td>
				<td style="text-align: center;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->spod_qty) ?></td>
				<td style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->spod_harga) ?></td>
				<td><?= $detail->spod_keterangan ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>