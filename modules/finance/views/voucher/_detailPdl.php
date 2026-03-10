<style>
table tr td{
	vertical-align: top;
}
</style>
<br><div class="col-md-12">
	<h4><b><?php echo Yii::t('app', 'Data Pengajuan Pembayaran DP Log Alam'); ?></b></h4>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
			<table style="width: 100%;">
				<tr>
					<td style="width: 20%;">Kode Ajuan</td>
					<td style="width: 25%"><b>: &nbsp; <?= $modLogBayarDp->kode ?></b></td>
					<td style="width: 20%">No. Kontrak</td>
					<td style="width: 35%"><b>: &nbsp; <?= $modKontrak->nomor ?></b></td>
				</tr>
				<tr>
					<td>Tanggal Ajuan</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($modLogBayarDp->tanggal) ?></b></td>
					<td>Tanggal Kontrak</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($modKontrak->tanggal); ?></b></td>
				</tr>
				<tr>
					<td>Status</td>
					<td><b>: &nbsp; <?= $modLogBayarDp->status ?></b></td>
					<td style="width: 20%; font-size: 1.2rem;">Kode - Tanggal PO</td>
					<td style="width: 35%; font-size: 1.2rem;"><b>: &nbsp; <?= $modKontrak->kode." - ".app\components\DeltaFormatter::formatDateTimeForUser2($modKontrak->tanggal_po) ?></b></td>
				</tr>
				<tr>
					<td>Keterangan</td>
					<td><b>: &nbsp; <?= $modLogBayarDp->keterangan ?></b></td>
					<td>Supplier</td>
					<td><b>: &nbsp; <?= $modKontrak->suplier->suplier_nm ?></b></td>
				</tr>
				<tr>
					<td>Total DP</td>
					<td class="font-red-soft"><b>: &nbsp; <?= app\components\DeltaFormatter::formatNumberForUserFloat($modLogBayarDp->total_dp) ?></b></td>
					<td>Harga /m<sup>3</sup></td>
					<td><b>: &nbsp; <?= $modKontrak->hargafob." (".$modKontrak->term_of_price.")"; ?></b></td>
				</tr>
			</table>
			<?= \yii\bootstrap\Html::hiddenInput('totalpdl', \app\components\DeltaFormatter::formatNumberForUserFloat($modLogBayarDp->total_dp), ['class'=>'form-control float']); ?>
		</div>
	</div>
	<a class="btn blue btn-sm btn-outline" onclick="infoKontrak(<?= $modLogBayarDp->log_kontrak_id ?>)" style="margin-top: 8px;">Lihat Detail Kontrak</a>
	<br>
</div>
<script>
setTotalPembayaran();
function infoKontrak(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuandplog/infoKontrak','id'=>'']) ?>'+id;
	var modal_id = 'modal-info';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
</script>