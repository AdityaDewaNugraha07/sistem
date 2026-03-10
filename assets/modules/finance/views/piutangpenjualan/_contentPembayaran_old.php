<style>
#table-bayar thead tr th{
	font-size: 1.2rem !important;
}
#table-bayar tbody tr td{
	font-size: 1.2rem;
}
#table-bayar tfoot tr td{
	font-size: 1.2rem;
}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<span class="col-md-9">
				<h4>Nota / Invoice <b><?= $modNota->kode; ?></b> :</h4>
			</span>
			<span class="col-md-3">
				<h5 class="pull-right">Curr : <?= $modNota->mata_uang ?></h5>
			</span>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<span class="col-md-6" style="font-size: 1.2rem;">
				<b>Jumlah Tagihan : <span class="font-red-flamingo"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modNota->total_bayar); ?></span></b>
			</span>
			<span class="col-md-6" style="font-size: 1.2rem;">
				<b class="pull-right">Sisa Piutang : <span class="font-yellow"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($sisapiutang); ?></span></b>
			</span>
		</div>
	</div>
</div>
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover" id="table-bayar">
		<thead>
			<tr style="background-color: #F1F4F7; ">
				<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Kode Penerimaan'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Tanggal Bayar'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Jumlah'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Hapus'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$sql = "SELECT * FROM t_piutang_penjualan WHERE bill_reff = '".$modNota->kode."' AND cancel_transaksi_id IS NULL ORDER BY created_at DESC";
			$modPiutang = Yii::$app->db->createCommand($sql)->queryAll();
			$matauang = "";
			if(count($modPiutang)>0){
				foreach($modPiutang as $i => $piutang){ ?>
					<tr>
						<td style="text-align: center;">
							<?php echo $i+1; ?>
							<?= yii\bootstrap\Html::hiddenInput('t_piutang_penjualan-total_bayar', $piutang['total_bayar']) ?>
						</td>
						<td><?= $piutang['payment_reff']; ?></td>
						<td style="text-align: center;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($piutang['tanggal_bayar']) ?></td>
						<td>
							<span class="pull-left">
								<?= $matauang = \app\models\MDefaultValue::getOneByValue('mata-uang', $piutang['mata_uang'], "name_en"); ?>
							</span>
							<span class="pull-right">
								<?= app\components\DeltaFormatter::formatNumberForUserFloat($piutang['total_bayar']) ?>
							</span>
						</td>
						<td style="text-align: center;">
							<a class="btn btn-xs red-flamingo" onclick="openModal('<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/deletePiutang','id'=>$piutang['piutang_penjualan_id']]) ?>','modal-delete-record')"><i class="fa fa-trash-o"></i></a>
						</td>
					</tr>
			<?php
				}
			}else{
				?>
					<tr><td colspan="4" style="text-align: center;"><i>Tidak ditemukan data pembayaran</i></td></tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr style="background-color: #F1F4F7">
				<td colspan="2" style="font-weight: 600;  vertical-align: middle;">
					<a class="btn btn-xs blue-steel btn-outline" onclick="newBayar('<?= $modNota->kode ?>')"><i class="fa fa-plus"></i> Pembayaran Baru</a>
				</td>
				<td style="text-align: right; vertical-align: middle;"><b>Jumlah Terbayar &nbsp;</b></td>
				<td style="text-align: right; vertical-align: middle; width: 150px; font-weight: 600">
					<span class="pull-left"><?= $matauang; ?></span>
					<span class="pull-right" id="place-jumlahterbayar"></span>
				</td>
				<td></td>
			</tr>
		</tfoot>
	</table>
</div>