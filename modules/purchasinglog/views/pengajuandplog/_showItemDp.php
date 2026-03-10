<div class="table-scrollable">
	<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
		<thead>
			<tr>
				<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
				<th style="" ><?= Yii::t('app', 'Kode Pengajuan DP'); ?></th>
				<th style=""><?= Yii::t('app', 'Tanggal'); ?></th>
				<th style="vertical-align: middle; "><?= Yii::t('app', 'Keterangan'); ?></th>
				<th style="width: 150px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Nominal DP'); ?></th>
				<th style="vertical-align: middle; "><?= Yii::t('app', 'Status'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $total=0; foreach($modDp as $i => $dp){ ?>
			<tr>
				<td><?= $i+1; ?></td>
				<td><?= $dp->kode ?></td>
				<td><?= app\components\DeltaFormatter::formatDateTimeForUser($dp->tanggal); ?></td>
				<td><?= ($dp->keterangan)?$dp->keterangan:"<center>-</center>"; ?></td>
				<td style="text-align: right;"><?= app\components\DeltaFormatter::formatUang($dp->total_dp); ?></td>
				<?php if(!empty($dp->voucher_pengeluaran_id)){ ?>
					<?php if($dp->voucherPengeluaran->status_bayar == "PAID"){ ?>
							<td><center><?= "<span class='label label-sm label-success'>PAID</span>"; ?></center></td>
					<?php }else{ ?>
							<td><center><?= "<span class='label label-sm label-warning'>UNPAID</span>"; ?></center></td>
					<?php } ?>
				<?php }else{ ?>
					<td><center>-</center></td>
				<?php } ?>
				<?php
				$total += $dp->total_dp;
				?>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" style="text-align: right">&nbsp; <i>Total</i></td>
				<td style="text-align: right;">
					<?= app\components\DeltaFormatter::formatUang($total); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>