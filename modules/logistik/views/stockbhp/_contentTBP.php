<h5 style="font-weight: bold;"><?= Yii::t('app', 'Terima Barang Persediaan (TBP)'); ?></h5>
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-advance table-hover">
		<thead>
			<tr >
				<th style="text-align: center;"> # </th>
				<th style="text-align: center;"> Tanggal </th>
				<th style="text-align: center;"> Suplier </th>
				<th style="text-align: center;"> Kode TBP </th>
				<th style="text-align: center;"> Qty </th>
				<th style="text-align: center;"> Harga </th>
				<th style="text-align: center;"> PPn </th>
				<th style="text-align: center;"> PPh </th>
			</tr>
		</thead>
		<?php
			foreach($models as $i => $detail){
				$modBhp = app\models\MBrgBhp::findOne($detail['bhp_id']);
				$modSupplier = app\models\MSuplier::findOne($detail['suplier_id']);
				$ppn = 0;
				$pph = !empty($detail['pph_peritem'])?$detail['pph_peritem']:0;
				if($detail['ppn_nominal']!=0){
					$ppn_status = "Exclude";
				}else{
					$ppn_status = "Include";
				}
				if(!empty($detail['spo_id'])){
					$modSpo = app\models\TSpo::findOne($detail['spo_id']);
					if($modSpo->spo_is_pkp == TRUE){
						$ppn = (($detail['terimabhpd_harga']*$detail['terimabhpd_qty']) * 0.1);
					}else{
						$ppn_status = "Non-PKP";
					}
				}
				if(!empty($detail['spl_id'])){
					$ppn = !empty($detail['ppn_peritem'])?$detail['ppn_peritem']:0;
				}
		?>
		<tr>
			<td class="td-kecil text-align-center"><?= $i+1; ?></td>
			<td class="td-kecil text-align-center"><?= app\components\DeltaFormatter::formatDateTimeForUser($detail['tglterima']) ?></td>
			<td class="td-kecil" style="text-align: left;"> <?= !empty($modSupplier)?$modSupplier->suplier_nm:' - '; ?> </td>
			<td class="td-kecil">
				<a href="javascript:;" style="text-align: center; color: #5594CA" onclick="infoTBP(<?= $detail['terima_bhp_id'] ?>,<?= $detail['bhp_id'] ?>)"><?= $detail['terimabhp_kode'] ?></a>
			</td>
			<td class="td-kecil " style="text-align: right;"> <?= app\components\DeltaFormatter::formatNumberForUserFloat($detail['terimabhpd_qty']); ?> <?= !empty($detail['spbd_satuan'])?$detail['spbd_satuan']:$modBhp->bhp_satuan; ?> </td>
			<td class="td-kecil " style="text-align: right;"> <?= app\components\DeltaFormatter::formatNumberForUserFloat($detail['terimabhpd_harga']); ?> </td>
			<td class="td-kecil " style="text-align: right;"> <?= $ppn_status; ?> </td>
			<td class="td-kecil " style="text-align: right;"> <?= app\components\DeltaFormatter::formatNumberForUserFloat( $pph ); ?> </td>
		</tr>
		<?php } ?>
	</table>
</div>