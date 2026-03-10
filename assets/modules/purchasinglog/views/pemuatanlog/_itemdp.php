<tr>
	<td><?= $modDp->kode ?></td>
	<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($modDp->tanggal) ?></td>
	<td style="text-align: center;"><?= "<span class=\"label label-sm label-".(($modDp->status=='PAID')?'success':'warning')."\">".$modDp->status."</span>"; ?></td>
	<?php if($modDp->status == 'PAID'){ ?>
		<td style="text-align: right;"><?= app\components\DeltaFormatter::formatUang($modDp->total_dp) ?></td>
	<?php }else{ ?>
		<td style="text-align: right;"><del><?=  app\components\DeltaFormatter::formatUang($modDp->total_dp) ?></del>	</td>
	<?php } ?>
</tr>