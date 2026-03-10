<style>
table tr td{
	vertical-align: top;
}
</style>
<br><div class="col-md-12">
	<h4><b><?php echo Yii::t('app', 'Pengajuan Uang Dinas Grader'); ?></b></h4>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
			<table style="width: 100%;">
				<tr>
					<td style="width: 20%;">Kode Pengajuan</td>
					<td style="width: 25%"><b>: &nbsp; <?= $modAjuanDinas->kode ?></b></td>
					<td style="width: 20%">Kode Dinas</td>
					<td style="width: 35%"><b>: &nbsp; <?= $modAjuanDinas->dkg->kode ?></b></td>
				</tr>
				<tr>
					<td>Tanggal Ajuan</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($modAjuanDinas->tanggal) ?></b></td>
					<td>Tipe Dinas</td>
					<td><b>: &nbsp; <?= $modAjuanDinas->dkg->tipe ?></b></td>
				</tr>
				<tr>
					<td>Tanggal Butuh</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($modAjuanDinas->tanggal_dibutuhkan) ?></b></td>
					<td>Wilayah Dinas</td>
					<td><b class="red-soft">: &nbsp; <?= $modAjuanDinas->wilayahDinas->wilayah_dinas_nama; ?></b></td>
					
				</tr>
				<tr>
					<td>Saldo Akhir</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatNumberForUserFloat($modAjuanDinas->saldo_sebelumnya) ?></b></td>
					<td>Nama Grader</td>
					<td><b>: &nbsp; <?= $modAjuanDinas->graderlog->graderlog_nm ?></b></td>
				</tr>
				<tr>
					<td>Maksimal Plafon</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatNumberForUserFloat($modAjuanDinas->wilayah_dinas_plafon) ?></b></td>
					<td>No.Rek</td>
					<td><b>: &nbsp; <?= $modAjuanDinas->grader_bank." - ".$modAjuanDinas->grader_norek ?></b></td>
				</tr>
				<tr>
					<td>Total Pengajuan</td>
					<td><b>: &nbsp; <span class="font-red-soft"><?= app\components\DeltaFormatter::formatNumberForUserFloat($modAjuanDinas->total_ajuan) ?></span></b></td>
				</tr>
			</table>
			<?= \yii\bootstrap\Html::hiddenInput('totalpdg', \app\components\DeltaFormatter::formatNumberForUserFloat($modAjuanDinas->total_ajuan), ['class'=>'form-control float']); ?>
		</div>
	</div>
	<a class="btn blue btn-sm btn-outline" onclick="ajuanDinas(<?= $modAjuanDinas->ajuandinas_grader_id ?>)" style="margin-top: 8px;">Lihat Form Ajuan</a>
	<br><br>
	<h5><u>Detail Pemakaian</u></h5>
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
							<a class="btn btn-xs btn-outline blue-hoki" onclick="detailRealisasiDinas(<?= $detail['realisasidinas_grader_id'] ?>)"><i class="fa fa-info-circle"></i></a>
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
function detailRealisasiDinas(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailRealisasiDinas','id'=>'']) ?>'+id;
	var modal_id = 'modal-realisasidinas';
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