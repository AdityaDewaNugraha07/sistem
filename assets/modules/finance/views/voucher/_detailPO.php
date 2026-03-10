<div class="col-md-12">
	<h4><?= Yii::t('app', 'Detail PO'); ?></h4>
</div>
<div class="col-md-12" >
	<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%;" id="table-detail-reff" >
		<thead>
			<tr>
				<th style="width: 110px;"><?php echo Yii::t('app', 'Kode PO'); ?></th>
				<th style="width: 95px;"><?= Yii::t('app', 'Tanggal'); ?></th>
				<th style="width: 90px;"><?= Yii::t('app', 'DPP'); ?></th>
				<th style="width: 90px;"><?= Yii::t('app', 'PPn'); ?></th>
				<th ><?= Yii::t('app', ''); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(count($modDetail)){
				$totaldpp = 0;
				$totalppn = 0;
				foreach($modDetail as $i => $detail){
				$totaldpp += $detail->spo_total;
				$totalppn += $detail->spo_ppn_nominal;
				?>
					<tr>
						<td style="text-align: center; font-size: 1.2rem; padding: 5px;">
							<?= $detail->spo_kode; ?>
							<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px; ']); ?>
							<?= \yii\bootstrap\Html::hiddenInput('TSpo[ii][spo_id]',$detail->spo_id,[]); ?>
							<!--<span class="no_urut"></span>-->
						</td>
						<td style="text-align: center; font-size: 1.2rem; padding: 5px;">
							<?= app\components\DeltaFormatter::formatDateTimeForUser2($detail->spo_tanggal); ?>
						</td>
						<td style="font-size: 1.2rem; padding: 5px; text-align: right;">
							<?= \yii\bootstrap\Html::hiddenInput('TSpo[ii][dpp]',$detail->spo_total,[]); ?>
							<?= \app\components\DeltaFormatter::formatNumberForUser($detail->spo_total); ?>
						</td>
						<td style="font-size: 1.2rem; padding: 5px; text-align: right;">
							<?= \yii\bootstrap\Html::hiddenInput('TSpo[ii][ppn]',$detail->spo_ppn_nominal,[]); ?>
							<?= \app\components\DeltaFormatter::formatNumberForUser($detail->spo_ppn_nominal); ?>
						</td>
						<td style="padding: 5px; text-align: center;">
							<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="detailPo(this,<?= $detail->spo_id; ?>,'<?= $detail->spo_kode; ?>')" title="Detail PO"><i class="fa fa-info-circle"></i></a>
							<?php if(empty($voucher_pengeluaran_id)){ ?>
								<!--<a class="btn btn-xs red btn-outline" href="javascript:void(0)" onclick="cancelPo(this)" title="Cancel PO"><i class="fa fa-remove"></i></a>-->
							<?php } ?>
						</td>
					</tr>
			<?php	}
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;">
					<u>Total</u> &nbsp;
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.2rem; text-align: right; padding: 5px;">
					<?= \yii\bootstrap\Html::textInput('totaldppreffpo', \app\components\DeltaFormatter::formatNumberForUser($totaldpp), ['class'=>'form-control money-format','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.2rem; text-align: right; padding: 5px;">
					<?= \yii\bootstrap\Html::textInput('totalppnreffpo', \app\components\DeltaFormatter::formatNumberForUser($totalppn), ['class'=>'form-control money-format','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<script>
setTotalPO();
function detailPo(ele,spo_id,spo_kode){
	if( $('#table-detail-reff > tbody > tr[id="detail-po-'+spo_id+'"]').length != 0 ){ // hide
		$(ele).removeClass('animation-loading');
		$(ele).find('i').attr('class','fa fa-info-circle');
		$('#table-detail-reff > tbody > tr[id="detail-po-'+spo_id+'"]').remove();
	}else{ // show
		$(ele).addClass('animation-loading');
		$(ele).find('i').attr('class','fa fa-sort-up');
		$('<tr id="detail-po-'+spo_id+'"></tr>').hide().insertAfter($(ele).closest('tr')).fadeIn(100,function(){
			var trPlace = $(this);
			$.ajax({
				url    : '<?= \yii\helpers\Url::toRoute(['/finance/voucher/getDetailPO']); ?>',
				type   : 'POST',
				data   : {spo_id:spo_id},
				success: function (data) {
					if(data.html){
						$('#table-detail-reff > tbody > tr[id="detail-po-'+spo_id+'"]')
							.html('<td colspan="5" style="width:100%; background-color:#e2f1ff"><center><span style="font-size:1.5rem; margin-top: -5px;">Detail PO <b>'+spo_kode+'</b></span></center>'+data.html+'</td>');
						$(ele).removeClass('animation-loading');
					}
				},
				error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
			});
			
		});
	}
}
function cancelPo(ele){
	$(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
		setTotalPO();
        reordertable('#table-detail-reff');
    });
}
function setTotalPO(){
	var totaldpp = 0;
	var totalppn = 0;
	$('#table-detail-reff > tbody > tr').each(function(){
		totaldpp += unformatNumber($(this).find('input[name*="[dpp]"]').val());
		totalppn += unformatNumber($(this).find('input[name*="[ppn]"]').val());
	});
	$('input[name="totaldppreffpo"]').val(formatInteger(totaldpp));
	$('input[name="totalppnreffpo"]').val(formatInteger(totalppn));
}
</script>