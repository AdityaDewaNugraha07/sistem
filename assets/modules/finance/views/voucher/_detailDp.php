<div class="col-md-12">
	<h4><?= Yii::t('app', 'Detail Downpayment'); ?></h4>
</div>
<div class="col-md-12">
	<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%;" id="table-detail-dp" >
		<thead>
			<tr>
				<th style="width: 20px;"><?php echo Yii::t('app', 'No.'); ?></th>
				<th style="width: 110px;"><?php echo Yii::t('app', 'Kode'); ?></th>
				<th style="width: 95px;"><?= Yii::t('app', 'Tanggal'); ?></th>
				<th style="width: 95px;"><?= Yii::t('app', 'Payment'); ?></th>
				<th style="width: 90px;"><?= Yii::t('app', 'Keterangan'); ?></th>
				<th style="width: 90px;"><?= Yii::t('app', 'Nominal'); ?></th>
				<th style="width: 80px;"><?= Yii::t('app', ''); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$total = 0;
			if(count($modDps)){
				foreach($modDps as $i => $detail){
					$total += $detail->nominal;
					$matauang = $detail->defaultValue->name_en;
				?>
					<tr>
						<td style="text-align: center;"><?= $i+1; ?></td>
						<td style="text-align: center; font-size: 1.2rem; padding: 5px;">
							<?= $detail->kode; ?>
							<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px; ']); ?>
							<?= \yii\bootstrap\Html::hiddenInput('TDpBhp[ii][dp_bhp_id]',$detail->dp_bhp_id,[]); ?>
						</td>
						<td style="text-align: center; font-size: 1.2rem; padding: 5px;">
							<?= app\components\DeltaFormatter::formatDateTimeForUser2($detail->tanggal); ?>
						</td>
						<td style="text-align: center; font-size: 1.2rem; padding: 5px; vertical-align: middle;">
							<?= $detail->cara_bayar ?>
						</td>
						<td style="text-align: center; font-size: 1.1rem; padding: 5px;">
							<?= (!empty($detail->keterangan)?$detail->keterangan:'<center>-</center>'); ?>
						</td>
						<td style="font-size: 1.2rem; padding: 5px; text-align: right;">
							<span class="pull-left"><?= $matauang ?></span>
							<?= \yii\bootstrap\Html::hiddenInput('TDpBhp[ii][nominal]',$detail->nominal,[]); ?>
							<?= \app\components\DeltaFormatter::formatNumberForUser($detail->nominal); ?>
						</td>
						<td style="padding: 5px; text-align: center;">
							<?php if(empty($voucher_pengeluaran_id)){ ?>
								<a class="btn btn-xs red btn-outline" href="javascript:void(0)" onclick="cancel(this)"><i class="fa fa-remove"></i></a>
							<?php } ?>
						</td>
					</tr>
			<?php	}
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;">
					<?php
//					if(isset($pakaidp)){
//						echo "Faktur DP : &nbsp;";
//						echo \yii\bootstrap\Html::checkbox('TDpBhp[is_faktur]',false,['onchange'=>'setDpFaktur();','template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',]);
//						echo " &nbsp;";
//					}
					?>
					
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;">
					<u>Total</u> &nbsp;
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.2rem; text-align: right; padding: 5px;">
					<?= \yii\bootstrap\Html::textInput('totaldp', \app\components\DeltaFormatter::formatNumberForUser($total), ['class'=>'form-control money-format','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
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
</script>