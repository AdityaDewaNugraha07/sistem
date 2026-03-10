<style>
#table-nota thead tr th{
	font-size: 1.2rem;
}
#table-nota tbody tr td{
	font-size: 1.2rem;
}
#table-nota tfoot tr td{
	font-size: 1.2rem;
}
</style>
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover" id="table-nota">
		<thead>
			<tr style="background-color: #F1F4F7; ">
				<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Kode Nota'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
				<th style="text-align: center; width: 110px; line-height: 0.8;"><?= Yii::t('app', 'Nominal<br>Bill'); ?></th>
				<th style="text-align: center; width: 110px; line-height: 0.8;"><?= Yii::t('app', 'Terbayar'); ?></th>
				<th style="text-align: center; width: 110px; line-height: 0.8;"><?= Yii::t('app', 'Sisa<br>Tagihan'); ?></th>
				<!--<th style="text-align: center;"><?php // echo Yii::t('app', 'TOP'); ?></th>-->
				<th style="text-align: center;"><?= Yii::t('app', 'Status'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$matauang = ""; $jmltagihan=0; $jmlterbayar = 0; $jmlsisatagihan=0;
			foreach($models as $i => $model){
				$modTempo = app\models\TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);
				$sql = "SELECT * FROM t_piutang_penjualan WHERE bill_reff = '".$model->kode."' AND cancel_transaksi_id IS NULL";
				$modPiutangs = Yii::$app->db->createCommand($sql)->queryAll();
				$terbayar = 0;
				if(count($modPiutangs)>0){
					foreach($modPiutangs as $ii => $piutang){
						$terbayar += $piutang['bayar'];
					}
				}
				$jmltagihan += $model->total_bayar;
				$jmlterbayar += $terbayar;
				$jmlsisatagihan += $jmltagihan-$jmlterbayar;
				$matauang = \app\models\MDefaultValue::getOneByValue('mata-uang', $model->mata_uang, "name_en");;
			?>
			<tr style="cursor: pointer;" onclick="selectRow(this); setHighlight(this);">
				<td style="text-align: center; font-size: 1rem;"><?= $i+1; ?></td>
				<td style="font-size: 1rem;">
					<a onclick="infoNota('<?= $model->kode ?>')"><?= $model->kode ?></a>
					<?= \yii\bootstrap\Html::activeHiddenInput($model, "nota_penjualan_id") ?>
					<?= \yii\bootstrap\Html::activeHiddenInput($model, "kode") ?>
				</td>
				<td style="text-align: center; font-size: 1rem;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></td>
				<td>
					<span class="pull-left" style="font-size: 1rem;"><?= $matauang; ?></span>
					<span class="pull-right" style="font-size: 1rem;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_bayar) ?></span>
				</td>
				<td>
					<span class="pull-left" style="font-size: 1rem;"><?= $matauang; ?></span>
					<span class="pull-right" style="font-size: 1rem;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($terbayar) ?></span>
				</td>
				<td>
					<span class="pull-left" style="font-size: 1rem;"><?= $matauang; ?></span>
					<span class="pull-right" style="font-size: 1rem;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_bayar-$terbayar) ?></span>
				</td>
				<!--<td style="text-align: right;"><?php // echo !empty($modTempo->top_hari)?$modTempo->top_hari." <i>Hari</i>":"<center>-</center>" ?> </td>-->
				<td style="text-align: center;">
					<?php
					if($model->status == "PAID"){
						echo '<span class="label label-success" style="font-size: 10px;">'.$model->status.'<span>';
					}else if($model->status == "PARTIALLY"){
						echo '<span class="label label-warning" style="font-size: 10px;">'.$model->status.'<span>';
					}else if($model->status == "UNPAID"){
						echo '<span class="label label-default" style="font-size: 10px;">'.$model->status.'<span>';
					}
					?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr style="background-color: #F1F4F7">
				<td colspan="3" style="text-align: right; vertical-align: middle;"><b>Total &nbsp;</b></td>
				<td style="text-align: right; vertical-align: middle; font-weight: 600">
					<span class="pull-left"><?= $matauang; ?></span>
					<span class="pull-right" style="font-size: 1rem;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($jmltagihan) ?></span>
				</td>
				<td style="text-align: right; vertical-align: middle; font-weight: 600">
					<span class="pull-left"><?= $matauang; ?></span>
					<span class="pull-right" style="font-size: 1rem;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($jmlterbayar) ?></span>
				</td>
				<td style="text-align: right; vertical-align: middle; font-weight: 600">
					<span class="pull-left"><?= $matauang; ?></span>
					<span class="pull-right" style="font-size: 1rem;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($jmlsisatagihan) ?></span>
				</td>
				<td></td>
			</tr>
		</tfoot>
	</table>
</div>