<h5 style="font-weight: bold;"><?= Yii::t('app', 'Surat Permintaan Pembelian (SPP)'); ?></h5>
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-advance table-hover">
		<thead>
			<tr >
				<th style="text-align: center;"> # </th>
				<th style="text-align: center;"> Tanggal </th>
				<th style="text-align: center;"> Qty </th>
				<th style="text-align: center;"> Dept </th>
				<th style="text-align: center;"> Kode SPP </th>
			</tr>
		</thead>
		<?php
			foreach($models as $i => $detail){
				$modBhp = app\models\MBrgBhp::findOne($detail['bhp_id']);
				$modDept = app\models\MDepartement::findOne($detail['departement_id']);
		?>
		<tr>
			<td class="td-kecil text-align-center"><?= $i+1; ?></td>
			<td class="td-kecil text-align-center"><?= app\components\DeltaFormatter::formatDateTimeForUser($detail['spp_tanggal']) ?></td>
			<td class="td-kecil " style="text-align: right;"> <?= app\components\DeltaFormatter::formatNumberForUserFloat($detail['sppd_qty']).' '.$modBhp->bhp_satuan; ?> </td>
			<td class="td-kecil " style="text-align: center;"> <?= $modDept->departement_nama; ?> </td>
			<td class="td-kecil">
				<a href="javascript:;" style="text-align: center; color: #5594CA" onclick="infoSPP(<?= $detail['spp_id'] ?>,<?= $detail['bhp_id'] ?>)"><?= $detail['spp_kode'] ?></a>
			</td>
		</tr>
		<?php } ?>
	</table>
</div>