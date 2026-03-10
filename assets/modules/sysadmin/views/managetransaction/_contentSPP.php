<table id="table-spp" class="table table-striped table-bordered table-hover" style="width: 100%;">
	<thead style="background-color: #B2C4D3">
		<th style="text-align: center; width: 40px;">No.</th>
		<th style="text-align: center;">sppd_id</th>
		<th style="text-align: center;">spp_id</th>
		<th style="text-align: center;">Qty</th>
		<th style="text-align: center;">Ket</th>
	</thead>
	<tbody>
		<?php 
		$total=0;
		foreach($model as $i => $spp){ 
		$total += $spp->sppd_qty;
		?>
		<tr>
			<td class="td-kecil text-align-center">
				<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
				<span class="no_urut"><?= $i+1; ?></span>
			</td>
			<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
				<?= $spp->sppd_id; ?>
			</td>
			<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
				<?= $spp->spp_id; ?>
			</td>
			<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
				<?= $spp->sppd_qty; ?>
			</td>
			<td class="td-kecil text-align-left" style="vertical-align: top ! important; font-size: 1.1rem;">
				<?= $spp->sppd_ket; ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="3">total</td>
			<td class="text-align-center"><?= $total ?></td>
		</tr>
	</tbody>
</table>