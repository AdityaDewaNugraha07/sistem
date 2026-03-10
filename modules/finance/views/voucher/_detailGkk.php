<div class="col-md-12">
	<h4><?php echo Yii::t('app', 'Penggantian Uang Kasir'); ?></h4>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
			<table style="width: 100%;">
				<tr>
					<td style="width: 20%">Kode GKK</td>
					<td style="width: 30%"><b>: &nbsp; <?= $modGkk->kode ?></b></td>
					<td style="width: 20%">Penerima</td>
					<td style="width: 30%"><b>: &nbsp; <?= $modGkk->penerima ?></b></td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td><b>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($modGkk->tanggal) ?></b></td>
				</tr>
			</table>
		</div>
	</div>
	<h5>Detail Gkk</h5>
	<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%;" id="table-pengeluaran-kaskecil" >
		<thead>
			<tr>
				<th style="width: 20px;"><?php echo Yii::t('app', 'No.'); ?></th>
				<th><?php echo Yii::t('app', 'Deskripsi'); ?></th>
				<th style="width: 95px;"><?= Yii::t('app', 'Nominal'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$total = 0;
			if(count($modDetail)){
				foreach($modDetail as $i => $detail){
					$total += $detail['detail_nominal'];
				?>
					<tr style="">
						<td class=""  style="font-size:1.2rem; text-align: center; padding: 5px;">
							<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
							<span class="no_urut"><?= $i+1; ?></span>
						</td>
						<td class=""  style="font-size:1.2rem; padding: 5px;">
							<?= $detail['detail_deskripsi'] ?>
						</td>
						<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
							<?= !empty($detail['detail_nominal'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['detail_nominal']):0; ?>
						</td>
					</tr>
			<?php	}
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: left; padding: 8px;" colspan="2">
					<a class="btn blue btn-sm btn-outline" onclick="detailGkk(<?= $modGkk->gkk_id ?>)">Lihat GKK</a>
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.2rem; text-align: right; padding: 5px;">
					<?= \yii\bootstrap\Html::textInput('totalgkk', \app\components\DeltaFormatter::formatNumberForUserFloat($total), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<script>
setTotalPembayaran();
function detailGkk(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/detailGkk']) ?>?id='+id,'modal-gkk','21cm');
}
</script>