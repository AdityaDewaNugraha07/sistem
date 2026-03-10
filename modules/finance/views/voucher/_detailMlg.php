<style>
table tr td{
	vertical-align: top;
}
</style>
<br><div class="col-md-12">
	<h4><b><?php echo Yii::t('app', 'Data Pengajuan Pelunasan Pembelian Log Alam'); ?></b></h4>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
			<table style="width: 100%;">
				<tr>
					<td style="width: 20%;">Kode Ajuan</td>
					<td style="width: 25%"><b>: &nbsp; <?= $modLogBayarMuat->kode ?></b></td>
					<td style="width: 20%">No. Kontrak</td>
					<td style="width: 35%"><b>: &nbsp; <?= $modLogBayarMuat->logKontrak->nomor ?></b></td>
				</tr>
				<tr>
					<td>Tanggal Ajuan</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($modLogBayarMuat->tanggal) ?></b></td>
					<td>Tanggal Kontrak</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($modLogBayarMuat->logKontrak->tanggal); ?></b></td>
				</tr>
				<tr>
					<td>Harga/m<sup>3</sup></td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatNumberForUserFloat($modLogBayarMuat->harga_m3) ?></b></td>
					<td style="width: 20%; font-size: 1.2rem;">Kode - Tanggal PO</td>
					<td style="width: 35%; font-size: 1.2rem;"><b>: &nbsp; <?= $modLogBayarMuat->logKontrak->kode." - ".app\components\DeltaFormatter::formatDateTimeForUser2($modLogBayarMuat->logKontrak->tanggal_po) ?></b></td>
				</tr>
				<tr>
					<td>Volume</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatNumberForUserFloat($modLogBayarMuat->total_volume) ?> m<sup>3</sup></b></td>
					<td>Supplier</td>
					<td><b>: &nbsp; <?= $modLogBayarMuat->logKontrak->suplier->suplier_nm ?></b></td>
				</tr>
				<tr>
					<td>Total Harga</td>
					<td class=""><b>: &nbsp; <?= app\components\DeltaFormatter::formatNumberForUserFloat($modLogBayarMuat->total_harga) ?></b></td>
					<td>Kode Keputusan</td>
					<td><b>: &nbsp; <?= $modLogBayarMuat->pengajuanPembelianlog->kode ?></b></td>
				</tr>
				<tr>
					<td>Pemakaian DP</td>
					<td class=""><b>: &nbsp; <?= app\components\DeltaFormatter::formatNumberForUserFloat($modLogBayarMuat->total_dp) ?></b></td>
					<td>Lokasi Muat</td>
					<td><b>: &nbsp; <?= $modLogBayarMuat->pengajuanPembelianlog->lokasi_muat ?></b></td>
				</tr>
				<tr>
					<td>Total Bayar</td>
					<td class="font-red-soft"><b>: &nbsp; <?= app\components\DeltaFormatter::formatNumberForUserFloat($modLogBayarMuat->total_bayar) ?></b></td>
					<td>Tongkang</td>
					<td><b>: &nbsp; <?= $modLogBayarMuat->loglist->tongkang ?></b></td>
				</tr>
			</table>
			<?= \yii\bootstrap\Html::hiddenInput('totalmlg', \app\components\DeltaFormatter::formatNumberForUserFloat($modLogBayarMuat->total_bayar), ['class'=>'form-control float']); ?>
		</div>
	</div>
<!--	<a class="btn blue btn-sm btn-outline" onclick="infoPelunasan(<?= $modLogBayarMuat->log_bayar_muat_id ?>)" style="margin-top: 8px;">Lihat Detail Kontrak</a>-->
	<br>
</div>
<script>
setTotalPembayaran();
function infoPelunasan(id){
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