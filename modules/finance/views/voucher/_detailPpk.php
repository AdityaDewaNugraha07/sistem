<div class="col-md-12">
	<h4><?= Yii::t('app', 'Detail 5 Pengeluaran Kas Kecil Terakhir'); ?></h4>
</div>
<div class="col-md-12">
	<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%;" id="table-pengeluaran-kaskecil" >
		<thead>
			<tr>
				<th style="width: 20px;"><?php echo Yii::t('app', 'No.'); ?></th>
				<th style=""><?php echo Yii::t('app', 'Deskripsi'); ?></th>
				<th style="width: 95px;"><?= Yii::t('app', 'Kredit'); ?></th>
				<th style="width: 95px;"><?= Yii::t('app', 'Rincian'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$total = 0;
			if(count($modPengeluaranKasKecil)){
				foreach($modPengeluaranKasKecil as $i => $detail){
					$desc = "Pengeluaran Kas Kecil Tanggal ".app\components\DeltaFormatter::formatDateTimeForUser($detail['tanggal']);
					$total += $detail['nominal'];
				?>
					<tr style="">
						<td class=""  style="font-size:1.2rem; text-align: center; padding: 5px;">
							<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
							<span class="no_urut"><?= $i+1; ?></span>
						</td>
						<td class=""  style="font-size:1.2rem; padding: 5px;">
							<?= $desc ?>
						</td>
						<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
							<?= !empty($detail['nominal'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['nominal']):0; ?>
						</td>
						<td class="text-align-center"  style="font-size:1.2rem; padding: 5px;">
							<a class="btn btn-sm btn-default" style="font-size:1rem; padding: 3px;" onclick="lihatRincian('<?= $detail['tanggal'] ?>')"><i class="fa fa-search"></i> Lihat Laporan</a>
						</td>
					</tr>
			<?php	}
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;">
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;">
					
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.2rem; text-align: right; padding: 5px;">
					<?= \yii\bootstrap\Html::hiddenInput('totalppk', 0, ['class'=>'form-control money-format','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<script>
setTotalDp();
function cancel(ele){
	$(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
		setTotalDp();
		setTotalPembayaran();
        reordertable('#table-detail-dp');
    });
}
function setTotalDp(){
	var total = 0;
	$('#table-detail-dp > tbody > tr').each(function(){
		total += unformatNumber($(this).find('input[name*="[nominal]"]').val());
	});
	$('input[name="totaldp"]').val(formatInteger(total));
}

function lihatRincian(tgl){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/rekapkaskecil/getRekapByTanggal','tgl'=>'']) ?>'+tgl,'modal-rekap');
}
</script>