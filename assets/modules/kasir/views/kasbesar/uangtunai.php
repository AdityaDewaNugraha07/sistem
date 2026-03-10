<?php \app\assets\InputMaskAsset::register($this); ?>
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-uangtunai',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-3 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="modal fade" id="modal-uangtunai" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Rincian Uang Tunai Kas Besar Tanggal ').\app\components\DeltaFormatter::formatDateTimeForUser2($tgl); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered" id="table-uangtunai">
								<thead>
									<tr>
										<th class="text-align-center" style="width: 125px;"><?= Yii::t('app', 'Nominal'); ?></th>
										<th class="text-align-center" style="width: 75px;"><?= Yii::t('app', 'Qty'); ?></th>
										<th class="text-align-center" style="width: 150px;"><?= Yii::t('app', 'Subtotal'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$mods = app\models\MDefaultValue::find()->where(['type'=>'uangtunai-kaskecil'])->all();
									if(count($mods)>0){
										foreach($mods as $i => $mod){
											$model->nominal = \app\components\DeltaFormatter::formatNumberForUserFloat($mod->value);
											$model->qty = 0;
											$model->subtotal = 0;
										?>
										<tr>
											<td style="padding:3px;">
												<?= yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']uangtunai_id') ?>
												<?= yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']tanggal',['value'=>$tgl]) ?>
												<?= yii\bootstrap\Html::activeTextInput($model, '['.$i.']nominal',['class'=>'form-control text-align-right money-format','style'=>'width:120px;','readonly'=>'readonly']) ?>
											</td>
											<td style="padding:3px;"><?= yii\bootstrap\Html::activeTextInput($model, '['.$i.']qty',['class'=>'form-control text-align-right float','style'=>'width:75px;','onblur'=>'setSubtotal(this)']) ?></td>
											<td style="padding:3px;"><?= yii\bootstrap\Html::activeTextInput($model, '['.$i.']subtotal',['class'=>'form-control text-align-right money-format','style'=>'width:150px;','readonly'=>'readonly']) ?></td>
										</tr>
										<?php } ?>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2" style="font-weight: bold; text-align: right">Total &nbsp; </td>
										<td style="padding: 3px;"><?= yii\bootstrap\Html::textInput('totaluangtunai',0,['class'=>'form-control','style'=>'text-align: right; text-weight:bold;','disabled'=>'disabled']) ?></td>
									</tr>
								</tfoot>
							</table>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'saveUangTunai();']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php $this->registerJs(" 
	formconfig();
	getUangTunai();
	checkClosing();
	info();
", yii\web\View::POS_READY); ?>
<script>
function getUangTunai(){
	$('#table-uangtunai > tbody').addClass('animation-loading');
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/getUangTunai']); ?>',
		type   : 'POST',
		data   : { tgl: '<?= $tgl ?>' },
		success: function (data) {
			if(data.html){
				$('#table-uangtunai > tbody').html(data.html);
			}
			$('#table-uangtunai > tbody > tr').each(function(){
				$(this).find('input[name*="[nominal]"]').val( formatNumberForUser($(this).find('input[name*="[nominal]"]').val()) );
				$(this).find('input[name*="[qty]"]').val( formatNumberForUser($(this).find('input[name*="[qty]"]').val()) );
				$(this).find('input[name*="[subtotal]"]').val( formatNumberForUser($(this).find('input[name*="[subtotal]"]').val()) );
				$(this).removeClass('animation-loading');
			});
			total();
			$('#table-uangtunai > tbody').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
	
function setSubtotal(ele){
	var nominal = unformatNumber( $(ele).parents('tr').find('input[name*="[nominal]"]').val() );
	var qty = unformatNumber( $(ele).parents('tr').find('input[name*="[qty]"]').val() );
	var subtotal = nominal * qty;
	$(ele).parents('tr').find('input[name*="[subtotal]"]').val( formatNumberForUser(subtotal) );
	total();
}

function total(){
	//total
	var total = 0;
	$('#table-uangtunai > tbody > tr').each(function(){
		total += unformatNumber( $(this).find('input[name*="[subtotal]"]').val() );
	});
	$('input[name="totaluangtunai"]').val( formatNumberForUser(total) );
}

function saveUangTunai(){
    var $form = $('#form-uangtunai');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-uangtunai tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail terlebih dahulu');
            return false;
        }
		$('#table-uangtunai > tbody > tr').each(function(){
			$(this).find('input[name*="[nominal]"]').val( unformatNumber($(this).find('input[name*="[nominal]"]').val()) );
			$(this).find('input[name*="[qty]"]').val( unformatNumber($(this).find('input[name*="[qty]"]').val()) );
			$(this).find('input[name*="[subtotal]"]').val( unformatNumber($(this).find('input[name*="[subtotal]"]').val()) );
			$(this).addClass('animation-loading');
		});
		$.ajax({
			url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/uangtunai']); ?>',
			type   : 'POST',
			data   : { formData: $form.serialize() },
			success: function (data) {
				getUangTunai();
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
    }
	
    return false;
}

function checkClosing(){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/CheckClosingUangTunai']); ?>',
		type   : 'POST',
		data   : { tgl: '<?= $tgl ?>' },
		success: function (data) {
			if(data.closing == 1){
				$('#table-uangtunai tbody').find('input').attr('disabled','disabled');
				$('#modal-uangtunai').find('#btn-save').attr('disabled','disabled');
			}else{
				$('#table-uangtunai tbody').find('input').removeAttr('disabled');
				$('#modal-uangtunai').find('#btn-save').removeAttr('disabled');
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function info(){
	<?php if(isset($info)){ ?>
		setTimeout(function(){
			$('#table-uangtunai tbody').find('input').attr('disabled','disabled');
			$('#modal-uangtunai').find('#btn-save').attr('disabled','disabled');
		},1000);
	<?php } ?>
}
</script>