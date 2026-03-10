<div class="row number-stats margin-bottom-30">
	<div class="col-md-4">
		<div class="stat-right">
			<div class="stat-number" style="text-align: center">
				<div class="title" style="font-size: 1.7rem;"> <?= Yii::t('app', 'Current Stock'); ?> </div>
				<div class="number" id="currentstockplace" style="font-size: 2rem;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modBhp->current_stock) ?></div>
				<div class="number" id="currentstockplace" style="font-size: 2rem;"><?= $modBhp->bhp_satuan ?></div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="stat-left">
			<div class="stat-chart">
				<!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
				<div id="sparkline_bar"></div>
			</div>
			<div class="stat-number" style="text-align: center">
				<div class="title"> <?= Yii::t('app', 'Total Qty In'); ?> </div>
				<div class="number" id="totalqtyinplace" style="font-size: 1.8rem; margin-top: -10px;"></div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="stat-right">
			<div class="stat-chart">
				<!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
				<div id="sparkline_bar2"></div>
			</div>
			<div class="stat-number" style="text-align: center">
				<div class="title"> <?= Yii::t('app', 'Total Qty Out'); ?> </div>
				<div class="number" id="totalqtyoutplace" style="font-size: 1.8rem; margin-top: -10px;"></div>
			</div>
		</div>
	</div>
</div>
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-advance table-hover">
		<thead>
			<tr >
				<th style="text-align: center;"> # </th>
				<th style="text-align: center;"> Tanggal </th>
				<th style="text-align: center;"> Reff No </th>
				<th style="text-align: center;"> In </th>
				<th style="text-align: center;"> Out </th>
				<th style="text-align: center;"> Balance </th>
			</tr>
		</thead>
		<?php
		$total_in=0; $total_out=0; $saldoawal=0;
		$current = \app\models\HPersediaanBhp::getCurrentStock($bhp_id);
		$totalrows = count($modPersediaan);
		foreach($modPersediaan as $i => $detail){
			$iddetail = null; $reff_no = '-'; $in = 0; $out = 0; $balance = 0; 
			$modTerima = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>$detail->reff_no]);
			$modBpb = \app\models\TBpb::findOne(['bpb_kode'=>$detail->reff_no]);
			if(!empty($modTerima)){
				$iddetail = "infoTBP(".$modTerima->terima_bhp_id.",".$bhp_id.")";
				$reff_no = $modTerima->terimabhp_kode;
			}else if($modBpb){
				$iddetail = "infoBPB(".$modBpb->bpb_id.",".$bhp_id.")";
				$reff_no = $modBpb->bpb_kode;
			}
			$in = $detail->qty_in;
			$total_in += $in;
			$out = $detail->qty_out;
			$total_out += $out;
			
			$sql = "select sum(qty_in)-sum(qty_out) as total_qty from h_persediaan_bhp where bhp_id = ".$bhp_id." and tgl_transaksi < '".$tgl_awal."' ";
			$saldoawal = Yii::$app->db->createCommand($sql)->queryScalar();

			if($i!=0){
				$current = $current+($in-$out);
			}else{
				$current = $saldoawal+($in-$out);
			}
		?>
		<?php if($i == 0) { ?>
		<tr>
			<td class="td-kecil text-align-right" colspan="5" style="font-size: 10px;"><b>Saldo Awal </b> &nbsp; </td>
			<td class="td-kecil" style="text-align: right;"><b><?= app\components\DeltaFormatter::formatNumberForUserFloat($saldoawal); ?></b></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="td-kecil text-align-center"><?= $i+1; ?></td>
			<td class="td-kecil"><?= app\components\DeltaFormatter::formatDateTimeForUser($detail->tgl_transaksi) ?></td>
			<td class="td-kecil">
				<a href="javascript:;" style="text-align: left; color: #5594CA" onclick="<?= $iddetail ?>"><?= $detail->reff_no ?></a>
			</td>
			<td class="td-kecil " style="text-align: right; color: #74901a"> <?= app\components\DeltaFormatter::formatNumberForUserFloat($in); ?> </td>
			<td class="td-kecil " style="text-align: right; color: #F36A5B"> <?= app\components\DeltaFormatter::formatNumberForUserFloat($out); ?> </td>
			<td class="td-kecil bold theme-font" style="text-align: right;">
				<span ><?= app\components\DeltaFormatter::formatNumberForUserFloat($current); ?></span>
			</td>
		</tr>
		<?php } ?>
	</table>
</div>

<script>
$('#totalqtyinplace').html("<?= app\components\DeltaFormatter::formatNumberForUserFloat($total_in) ?>");
$('#totalqtyoutplace').html("<?= app\components\DeltaFormatter::formatNumberForUserFloat($total_out) ?>");
$('#totalqtysaldoawalplace').html("<?= app\components\DeltaFormatter::formatNumberForUserFloat($saldoawal) ?>");
</script>
