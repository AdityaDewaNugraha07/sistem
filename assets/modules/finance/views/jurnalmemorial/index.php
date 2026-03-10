<?php
/* @var $this yii\web\View */
$this->title = 'Jurnal memorial';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Jurnal memorial'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Jurnal'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']); ?>
										<?= $form->field($model, 'reff_no')->textInput(['style'=>'font-weight:bold'])->label('Reff Number'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'status_posting',[])->inline()->radioList(['UNPOSTED'=>'UNPOSTED','POSTED'=>'POSTED'],false); ?>
                                    </div>
                                </div>
                                <br><br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Detail Rekening'); ?></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"><br>
                                        <table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30px;">No.</th>
                                                    <th style="width: 250px;"><?= Yii::t('app', 'Rekening'); ?></th>
                                                    <th style="width: 150px;"><?php echo Yii::t('app', 'Memo'); ?></th>
                                                    <th style="width: 100px;"><?= Yii::t('app', 'Debt (Rp)'); ?></th>
                                                    <th style="width: 100px;"><?= Yii::t('app', 'Credit (Rp)'); ?></th>
                                                    <th style="width: 50px;"><?= Yii::t('app', 'Cancel'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
												if(count($modJurnals)){
													$totaldebt = 0;
													$totalcredit = 0;
													foreach($modJurnals as $i => $jurnal){
												?>
														<tr>
															<td style="padding-top: 10px; vertical-align:middle; text-align:center;">
																<?= $i+1; ?>
															</td>
															<td style="text-align: left;">
																<?= $jurnal->acct_no.' - '.$jurnal->acct->acct_nm; ?>
															</td>
															<td style="text-align: left;">
																<?= $jurnal->memo; ?>
															</td>
															<td style="text-align: right;">
																<?= app\components\DeltaFormatter::formatNumberForUser($jurnal->debet); ?>
															</td>
															<td style="text-align: right;">
																<?= app\components\DeltaFormatter::formatNumberForUser($jurnal->kredit); ?>
															</td>
															<td style="padding-top: 10px; text-align: center;">
																-
															</td>
														</tr>
												<?php	
													$totaldebt += $jurnal->debet;
													$totalcredit += $jurnal->kredit;
													}
												$model->totaldebet = \app\components\DeltaFormatter::formatNumberForUser($totaldebt);
												$model->totalkredit = \app\components\DeltaFormatter::formatNumberForUser($totalcredit);
												}
												?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;">
														<u>Total</u> &nbsp;
													</td>
													<td style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'totaldebet', ['class'=>'form-control money-format','disabled'=>'disabled']); ?>
													</td>
													<td style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'totalkredit', ['class'=>'form-control money-format','disabled'=>'disabled']); ?>
													</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">
                                                        <a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['kode'])){
    $pagemode = "afterSaveThis()";
}else {
    $pagemode = "addItem()";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function addItem(){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/jurnalmemorial/addItem']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail tbody').fadeIn(500,function(){
                    $(this).find('select[name*="[acct_id]"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik No. Rek',
                        width: null
                    });
					setTotal();
                    reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setTotal(){
	var debt = 0;
	var credit = 0;
	$("#table-detail > tbody > tr").each(function (){
		debt += unformatNumber($(this).find('input[name*="[debet]"]').val());
		credit += unformatNumber($(this).find('input[name*="[kredit]"]').val());
	});
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'totaldebet') ?>").val(formatInteger(debt));
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'totalkredit') ?>").val(formatInteger(credit));
}

function hapusItem(ele){
	$(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
		setTotal();
        reordertable('#table-detail');
    });
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
        }
        if(validatingDetail()){
			if(validNominal()){
	            submitform($form);
			}
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
    $('#table-detail > tbody > tr').each(function(){
        var field1 = $(this).find('select[name*="[acct_id]"]');
        var field2 = $(this).find('input[name*="[debet]"]');
        var field3 = $(this).find('input[name*="[kredit]"]');
        if(!field1.val()){
            $(this).find('select[name*="[acct_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('select[name*="[acct_id]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            $(this).find('input[name*="[debet]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[debet]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field3.val()){
            $(this).find('input[name*="[kredit]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[kredit]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function validNominal(){
	var totaldebet = unformatNumber($("#<?= \yii\bootstrap\Html::getInputId($model, 'totaldebet') ?>").val());
	var totalkredit = unformatNumber($("#<?= \yii\bootstrap\Html::getInputId($model, 'totalkredit') ?>").val());
	var debt = 0;
	var credit = 0;
	if((totaldebet <= 0)&&(totalkredit <= 0)){
		cisAlert("<?= Yii::t('app', 'Nominal tidak sesuai'); ?>");
		return false;
	}
	if(totaldebet != totalkredit){
		cisAlert("<?= Yii::t('app', 'Debt dan Credit tidak Match'); ?>");
		return false;
	}
	return true;
}

function afterSaveThis(){
	$('#btn-add-item').attr('style','display:none');
	$('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
}

</script>