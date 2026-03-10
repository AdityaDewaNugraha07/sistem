<style>
table tr td{
	vertical-align: top;
}
</style>
<br><div class="col-md-12">
	<h4><b><?php echo Yii::t('app', 'Pengajuan Uang Makan Grader'); ?></b></h4>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
			<table style="width: 100%;">
				<tr>
					<td style="width: 20%;">Kode Pengajuan</td>
					<td style="width: 30%"><b>: &nbsp; <?= $modAjuanMakan->kode ?></b></td>
					<td style="width: 25%">Wilayah Dinas</td>
					<td style="width: 25%"><b>: &nbsp; <?= $modAjuanMakan->wilayahDinas->wilayah_dinas_nama; ?></b></td>
				</tr>
				<tr>
					<td>Periode Awal</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($modAjuanMakan->periode_awal) ?></b></td>
					<td>Kode Dinas</td>
					<td><b>: &nbsp; <?= $modAjuanMakan->dkg->kode ?></b></td>
				</tr>
				<tr>
					<td>Periode Akhir</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($modAjuanMakan->periode_akhir) ?></b></td>
					<td>Uang Makan /hari</td>
					<td><b>: &nbsp; <?= \app\components\DeltaFormatter::formatNumberForUserFloat($modAjuanMakan->wilayah_dinas_makan) ?></b></td>
				</tr>
				<tr>
					<td>Nama Grader</td>
					<td><b>: &nbsp; <?= $modAjuanMakan->graderlog->graderlog_nm ?></b></td>
					<td>Uang Pulsa /bln</td>
					<td><b>: &nbsp; <?= \app\components\DeltaFormatter::formatNumberForUserFloat($modAjuanMakan->wilayah_dinas_pulsa) ?></b></td>
				</tr>
				<tr>
					<td>Wilayah Dinas</td>
					<td><b>: &nbsp; <?= $modAjuanMakan->wilayahDinas->wilayah_dinas_nama ?></b></td>
					<td>Qty Hari</td>
					<td><b>: &nbsp; <?= \app\components\DeltaFormatter::formatNumberForUserFloat($modAjuanMakan->qty_hari) ?> Hari</b></td>
				</tr>
				<tr>
					<td>Tipe Dinas</td>
					<td><b>: &nbsp; <?= $modAjuanMakan->dkg->tipe ?></b></td>
					<td>Saldo Sebelumnya</td>
					<td><b>: &nbsp; <span class="font-yellow"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modAjuanMakan->saldo_sebelumnya) ?></span></b></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>Total Ajuan</td>
					<td><b>: &nbsp; <span class="font-red-soft"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modAjuanMakan->total_ajuan) ?></span></b></td>
				</tr>
			</table>
			<?= \yii\bootstrap\Html::hiddenInput('totalpmg', \app\components\DeltaFormatter::formatNumberForUserFloat($modAjuanMakan->total_ajuan), ['class'=>'form-control float']); ?>
		</div>
	</div>
	<a class="btn blue btn-sm btn-outline" onclick="ajuanMakan(<?= $modAjuanMakan->ajuanmakan_grader_id ?>)" style="margin-top: 8px;">Lihat Form Ajuan</a>
	<br><br>
	<h5><u>Realisasi Uang Makan</u></h5>
	<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%;" id="table-pengeluaran-kaskecil" >
		<thead>
			<tr>
				<th style="width: 20px;"><?php echo Yii::t('app', 'No.'); ?></th>
				<th><?php echo Yii::t('app', 'Kode Realisasi'); ?></th>
				<th><?php echo Yii::t('app', 'Periode'); ?></th>
				<th><?php echo Yii::t('app', 'Saldo Awal'); ?></th>
				<th><?php echo Yii::t('app', 'Total Reliasasi'); ?></th>
				<th><?php echo Yii::t('app', 'Saldo Akhir'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$total = 0;
			if(count($modDetail)){
				foreach($modDetail as $i => $detail){
				?>
					<tr style="">
						<td class=""  style="font-size:1.2rem; text-align: center; padding: 5px;">
							<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
							<span class="no_urut"><?= $i+1; ?></span>
						</td>
						<td class=""  style="font-size:1.2rem; padding: 5px;">
							<?= $detail['kode'] ?>
						</td>
						<td class="text-align-center"  style="font-size:1.2rem; padding: 5px;">
							<?= \app\components\DeltaFormatter::formatDateTimeForUser2($detail['periode_awal']).' - '.\app\components\DeltaFormatter::formatDateTimeForUser2($detail['periode_akhir']) ?>
						</td>
						<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
							<?= !empty($detail['saldo_awal'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['total_realisasi']):0; ?>
						</td>
						<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
							<?= !empty($detail['total_realisasi'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['total_realisasi']):0; ?>
						</td>
						<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
							<?= !empty($detail['saldo_akhir'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['total_realisasi']):0; ?>
						</td>
						<td class="text-align-center" style="font-size:1.2rem; padding: 5px;">
							<a class="btn btn-xs btn-outline blue-hoki" onclick="detailRealisasiMakan(<?= $detail['realisasimakan_grader_id'] ?>)"><i class="fa fa-info-circle"></i></a>
						</td>
					</tr>
			<?php	}
			}
			?>
		</tbody>
		<tfoot>
			
		</tfoot>
	</table>
</div>
<script>
setTotalPembayaran();
function detailRealisasiMakan(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailRealisasiMakan','id'=>'']) ?>'+id;
	var modal_id = 'modal-realisasimakan';
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