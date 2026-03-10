<tr style="background-color: #F1F4F7">
	<td class="td-detail" style="text-align:center; font-size: 1.2rem; width: 50px; position:absolute; left:335px;">
	</td>
	<?php if(isset($modHead)){ ?>
	<?php foreach($modHead as $i => $head){
		
		?>
		<td class="td-detail" style="text-align:right; font-size: 1.1rem; padding-right: 10px;">
			<b><?= $head['palet'] ?></b>
		</td>	
		<td class="td-detail" style="text-align:right; font-size: 1.1rem; border-right: solid 1px #999;">
			<b><?= app\components\DeltaFormatter::formatNumberForUserFloat($head['pcs']) ?></b>
		</td>
	<?php } ?>
	<?php } ?>
</tr>