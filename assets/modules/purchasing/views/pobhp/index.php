<?php
/* @var $this yii\web\View */
$this->title = 'Transaksi PO';

app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Purchase Order (PO) Bahan Pembantu'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-spo',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="active">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/index"); ?>"> <?= Yii::t('app', 'PO Baru'); ?> </a>
                    </li>
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/podibuat"); ?>"> <?= Yii::t('app', 'PO Dibuat'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/spl/index"); ?>"> <?= Yii::t('app', 'SPL Baru'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/index"); ?>"> <?= Yii::t('app', 'SPP Masuk'); ?> </a>
                    </li>
					<li class="">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/sppmasuk"); ?>"> <?= Yii::t('app', 'SPP Masuk Detail'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/dpbhp/index"); ?>"> <?= Yii::t('app', 'Downpayment'); ?> </a>
                    </li>
                </ul>
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-md-12">
						<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarSpo()"><i class="fa fa-list"></i> <?= Yii::t('app', 'PO Yang Telah Dibuat'); ?></a>
					</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Purchase Order'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?php 
										if(!isset($_GET['spo_id'])){
											echo $form->field($model, 'spo_kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'spo_kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->spo_kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        
                                        <?= $form->field($model, 'spo_tanggal')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']); ?>
										
										<?= $form->field($model, 'spo_is_pkp',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
													->checkbox(['onchange'=>'setPKP(); setppnlayout();'],false)->label(Yii::t('app', 'PKP')); ?>
										
										<?= $form->field($model, 'spo_is_ppn',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
													->checkbox(['onchange'=>'hitungppn()'],false)->label(Yii::t('app', 'Include PPn')); ?>
										
										<?php if( (isset($_GET['spo_id'])) && ($model->cancel_transaksi_id == NULL) ){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<a href="javascript:void(0);" onclick="cancelPo(<?= $model->spo_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan PO'); ?></a>
												</div>
											</div>
										<?php } ?>
										<?php if($model->cancel_transaksi_id != null){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
													<?php
													$modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
													echo "<br><span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena ".$modCancel->cancel_reason."</span>";
													?>
												</div>
											</div>
										<?php } ?>
                                    </div>
                                    <div class="col-md-6">
										<?php
										if(!isset($_GET['spp_id'])){
											$onchange = 'setDetail()';
										}else{
											$onchange = '';
										}
										?>
										<?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionListBHP(),['class'=>'form-control select2','prompt'=>'','onchange'=>$onchange]); ?>
										<div class="form-group" style="margin-top: -10px;">
											<div class="col-md-4">
											</div>
											<div class="col-md-7 font-blue-steel" style="margin-top: -5px; margin-bottom: 5px; font-size: 1.2rem; cursor: pointer;" id="place-supplierfax"></div>
										</div>
										<?php if(isset($_GET['spo_id'])){ ?>
											<?php echo $form->field($model, 'spo_disetujui')->dropDownList(\app\models\MPegawai::getOptionList(),['class'=>'form-control select2','prompt'=>'']); ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Status Approval'); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<?php
													if(count($model)>0){
														if($model->approve_status == \app\models\TApproval::STATUS_APPROVED){
															echo '<span class="label label-sm label-success"> '.$model->approve_status .' </span>';
														}else if($model->approve_status == \app\models\TApproval::STATUS_REJECTED){
															echo '<span class="label label-sm label-danger"> '.$model->approve_status  .' </span>';
														}else{
															echo '<span class="label label-sm label-default"> '.\app\models\TApproval::STATUS_NOT_CONFIRMATED.' </span>';
														}
													}else{
														echo '<span class="label label-sm label-default"> '.\app\models\TApproval::STATUS_NOT_CONFIRMATED.' </span>';
													}
													?>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'tanggal_kirim',[
														'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime" style="width:195px !important">{input} <span class="input-group-addon">
															<button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div> 
															{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= \yii\helpers\Html::activeHiddenInput($model, "penawaran") ?>
										<?= \yii\helpers\Html::hiddenInput("allowpenawaran","",['id'=>'allowpenawaran']) ?>
                                    </div>
                                </div>
                                <br><br><hr>
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-6">
                                        <h4><?= Yii::t('app', 'Detail Purchase Order'); ?></h4>
                                    </div>
                                    <div class="col-md-6">
										<div class="form-group">
											<div class="form-group">
												<div class="col-md-5 pull-right">
													<?php echo \yii\bootstrap\Html::activeDropDownList($model, 'mata_uang', \app\models\MDefaultValue::getOptionList('mata-uang'),['class'=>'form-control','style'=>'font-size: 1.3rem; padding: 3px; height: 27px;','onchange'=>'setMataUang()']) ?>
												</div>
												<label class="col-md-3 pull-right" style="margin-right: -50px; font-size: 1.3rem; padding-top: 5px;">Mata Uang : </label>
											</div>
										</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
                                                        <th rowspan="2" style="vertical-align: middle; text-align: center; width: 250px;" ><?= Yii::t('app', 'Nama Item'); ?></th>
                                                        <th rowspan="2" style="text-align: center;  vertical-align: middle; width: 70px;"><?= Yii::t('app', 'Available<br>Stock'); ?></th>
                                                        <th colspan="2" style="text-align: center;  vertical-align: middle;"><?= Yii::t('app', 'Qty'); ?></th>
                                                        <th rowspan="2" style="width: 60px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Sat'); ?></th>
                                                        <th rowspan="2" style="width: 120px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Harga'); ?> <span class="place-mata-uang">(<?= !empty($model->defaultValue->name_en)?$model->defaultValue->name_en:"Rp."; ?>)</span></th>
                                                        <th rowspan="2" style="width: 120px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Sub Total'); ?> <span class="place-mata-uang">(<?= !empty($model->defaultValue->name_en)?$model->defaultValue->name_en:"Rp."; ?>)</span></th>
                                                        <th rowspan="2" style="width: 160px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
                                                        <th rowspan="2" style="vertical-align: middle; text-align: center; width: 30px;"><?= Yii::t('app', ''); ?></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50px; font-size: 1.2rem;"><?= Yii::t('app', 'Demand'); ?></th>
                                                        <th style="width: 50px; font-size: 1.2rem;"><?= Yii::t('app', 'Purchase'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
												<tfoot>
													<tr>
														<td colspan="7" style="text-align: right; font-weight: 500; ">&nbsp; Total</td>
														<td style="padding-left: 8px; padding-right: 8px;">
															<?= yii\bootstrap\Html::textInput('total',0,['class'=>'form-control money-format','style'=>'width:100%; padding:3px;','disabled'=>'disabled','id'=>'total']); ?>
														</td>
													</tr>
													<tr>
														<td colspan="7" style="text-align: right; font-weight: 500; ">&nbsp; PPh</td>
														<td style="padding-left: 8px; padding-right: 8px;">
															<?= \yii\helpers\Html::activeTextInput($model, 'spo_pph_nominal', ['class'=>'form-control float', 'onkeyup'=>'hitungpph()','style'=>'padding:3px;']); ?>
														</td>
													</tr>
													<tr>
														<td colspan="7" style="text-align: right; font-weight: 500; vertical-align: middle;">
															<?= Yii::t('app', 'PPn'); ?>
														</td>
														<td style="padding-left: 8px; padding-right: 8px; padding: 3px 8px; text-align: right;">
															<?php echo yii\bootstrap\Html::activeTextInput($model,'spo_ppn_nominal',['class'=>'form-control money-format','style'=>'width:100%; padding:3px;','disabled'=>'disabled']); ?>
														</td>
														<td colspan="2"></td>
													</tr>
													<tr>
														<td colspan="7" style="text-align: right; vertical-align: middle;">&nbsp;<span> <?= Yii::t('app', 'Total Bayar'); ?> </span></td>
														<td style="padding-left: 8px; padding-right: 8px; padding: 3px 8px;">
															<?= yii\bootstrap\Html::activeTextInput($model,'spo_total',['class'=>'form-control money-format','style'=>'width:100%; font-style: bold; padding:3px;','disabled'=>'disabled']); ?>
														</td>
														<td colspan="2"></td>
													</tr>
												</tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
								<?php 
									if( (isset($_GET['spo_id'])) && ($model->approve_status == \app\models\TApproval::STATUS_APPROVED) ){
										$disabled = FALSE;
									}else{
										$disabled = TRUE;
									}
								?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>(($disabled==FALSE)? 'printout('.(isset($_GET['spo_id'])?$_GET['spo_id']:'').')' :''),'disabled'=>$disabled]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>

<?php
if(isset($_GET['spo_id'])){
    $pagemode = "afterSave();";
}else if(Yii::$app->session->hasFlash('success')){
    $pagemode = "daftarSpo();";
}else{
    $pagemode = "";
}
if(isset($_GET['spp_id'])){
    $detailbyspp = "setDetailBySppId(".$_GET['spp_id'].")";
}else{
    $detailbyspp = "";
}
?>

<?php $this->registerJs(" 
    $('#".yii\bootstrap\Html::getInputId($model, 'departement_id')."').change(function(){
        getItems();
    });
	formconfig();
    $pagemode;
	$detailbyspp;
", yii\web\View::POS_READY);
?>

<script>
function setDetail(ele){
	var suplier_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
	$(ele).siblings('.select2').addClass('animation-loading');
	$('#<?= \yii\helpers\Html::getInputId($model, "penawaran") ?>').val("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/setDetail']); ?>',
		type   : 'POST',
		data   : {suplier_id:suplier_id},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail tbody').html(data.html);
				hitungppn();
				reordertable('#table-detail');
			}else{
				hitungppn();
			}
			if(data.supplier){
				var fax = (data.supplier.fax)? "<b>"+data.supplier.fax+"</b>" :"<i>Tidak Ditemukan No Fax</i>";
				$('#place-supplierfax').html("Fax : "+fax);
				$('#place-supplierfax').attr("onclick","infoSupplier("+data.supplier.suplier_id+")");
			}
			if(data.penawaran){
				$('#<?= \yii\helpers\Html::getInputId($model, "penawaran") ?>').val(data.penawaran);
			}
			$(ele).siblings('.select2').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setDetailBySppId(spp_id){
	$('#table-detail > tbody').addClass('animation-loading');
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/setDetailBySppId']); ?>',
		type   : 'POST',
		data   : {spp_id:spp_id},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail tbody').html(data.html);
				hitungppn();
				reordertable('#table-detail');
			}else{
				hitungppn();
			}
			$('#table-detail > tbody').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
	
function setPKP(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'spo_is_pkp') ?>').is(':checked')){
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_is_ppn') ?>').prop('checked', false);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_is_ppn') ?>').parents('.form-group').attr('style','display:');
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').parents('tr').attr('style','display:');
		$('.btn.addfromspp').show();
	}else{
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_is_ppn') ?>').prop('checked', false);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_is_ppn') ?>').parents('.form-group').attr('style','display:none');
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').val(0);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').parents('tr').attr('style','display:none');
		$('.btn.addfromspp').show();
	}
	$('#table-detail tbody > tr').each(function(){
		var hargabantu = unformatNumber($(this).find('input[name*="[spod_harga_bantu]"]').val());
		$(this).find('input[name*="[spod_harga]"]').val(formatInteger(hargabantu));
	});
	hitungppn();
}

function reordertable(obj_table){
    var row = 0;
    $(obj_table).find("tbody > tr").each(function(){
        $(this).find("#no_urut").val(row+1);
        $(this).find("span.no_urut").text(row+1);
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 3){
                $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]);
                $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]");
            }
        });
        row++;
    });
    formconfig();
}

function setSubtotal(ele){

	var sppd_qty =  $(ele).parents('tr').find('input[name*="[sppd_qty]"]').val();
	if(parseInt($(ele).val()) > parseInt(sppd_qty)){
		$(ele).val(sppd_qty);
	}
	setTimeout(function(){
		var total = 0;
		var totalppn = $('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').val();
		var totalpph = $('#<?= yii\bootstrap\Html::getInputId($model, 'spo_pph_nominal') ?>').val();
		var totaldisplay = 0;
		var hargabantu = 0;

		$('#table-detail tbody tr').each(function(index){
			var qty = unformatNumber($(this).find('input[name*="[spod_qty]"]').val());
			var harga = unformatNumber($(this).find('input[name*="[spod_harga]"]').val());
			totaldisplay = qty * harga;
			if(totaldisplay.toString().indexOf('.') != -1){
				totaldisplay = formatFloat(totaldisplay);
			}else{
				totaldisplay = formatInteger(totaldisplay);
			}
			$(this).find('input[name*="[subtotal_display]"]').val(totaldisplay);
			$(this).find('input[name*="[subtotal]"]').val(qty * harga);
			total += qty * harga;
			hargabantu += unformatNumber($(this).find('input[name*="[spod_harga_bantu]"]').val()) * unformatNumber($(this).find('input[name*="[spod_qty]"]').val());
		});
		$('#total').val(formatInteger(Math.ceil(total)));
		
		
		if ($('#<?= yii\bootstrap\Html::getInputId($model, 'spo_is_ppn') ?>').is(':checked')){
			var grandtotal = formatInteger(unformatNumber(hargabantu) + unformatNumber(totalpph));
		}else{
			var grandtotal = formatInteger(unformatNumber(total) + unformatNumber(totalpph) + unformatNumber(totalppn));
		}

		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_total') ?>').val(grandtotal);
	},300);
}

function hitungpph() {
	var total = unformatNumber($('#total').val());
	var ppn = unformatNumber($('#tspo-spo_ppn_nominal').val());
	var pph = unformatNumber($('#tspo-spo_pph_nominal').val());
	var total_semuah = ribuan(total + ppn + pph);
	var total = $('#tspo-spo_total').val(total_semuah);
	$('#tspo-spo_total').val(total_semuah);

}

function ribuan(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function hitungppn(){
	var hargappn = 0;
	var total = 0;
	var ppn = 0;
	var harga_display = 0;
	$('#table-detail tbody tr').each(function(index){
		var qty = unformatNumber($(this).find('input[name*="[spod_qty]"]').val());
		var harga = unformatNumber($(this).find('input[name*="[spod_harga]"]').val());
		if($('#<?= yii\bootstrap\Html::getInputId($model, 'spo_is_ppn') ?>').is(':checked')){
			harga = harga / 1.1;
			if(harga.toString().indexOf('.') != -1){
				harga_display = formatFloat(harga);
			}else{
				harga_display = formatInteger(harga);
			}
			ppn += unformatNumber(qty * harga) * 0.1;
			$(this).find('input[name*="[harga_display]"]').val(harga_display);
			$(this).find('input[name*="[harga_display]"]').attr('disabled', 'disabled');
			$(this).find('input[name*="[spod_harga]"]').val(harga);
			$(this).find('input[name*="[spod_harga]"]').attr('disabled', 'disabled');
			$(this).find('input[name*="[spod_qty]"]').attr('disabled', 'disabled');
			$('.btn.addfromspp').hide();
		}else{
			var hargabantu = $(this).find('input[name*="[spod_harga_bantu]"]').val();
			harga = unformatNumber(hargabantu);
			if(harga.toString().indexOf('.') != -1){
				harga_display = formatFloat(harga);
			}else{
				harga_display = formatInteger(harga);
			}
			ppn += unformatNumber(qty * harga) * 0.1;
			$(this).find('input[name*="[harga_display]"]').val(harga_display);
			$(this).find('input[name*="[harga_display]"]').removeAttr('disabled');
			$(this).find('input[name*="[spod_harga]"]').val(harga);
			$(this).find('input[name*="[spod_harga]"]').removeAttr('disabled');
			$(this).find('input[name*="[spod_qty]"]').removeAttr('disabled');
			$('.btn.addfromspp').show();
		}
	});
	setSubtotal();
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'spo_is_pkp') ?>').is(':checked')){
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').val(formatInteger(Math.floor(ppn)));	
	}else{
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').val(0);	
	}
}

function setppnlayout(){
	$('#table-detail tbody tr').each(function(index){
		$(this).find('input[name*="[spod_harga]"]').removeAttr('disabled');
		$(this).find('input[name*="[spod_qty]"]').removeAttr('disabled');
	});
	
}

function duplicateHarga(ele){
	var harga = $(ele).val();
	$(ele).parents('tr').find('input[name*="[spod_harga_bantu]"]').val(harga);
	$(ele).parents('tr').find('input[name*="[spod_harga]"]').val(harga);
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(300,function(){
        $(this).remove();
		var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
            $('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').val(0);
            $('#<?= yii\bootstrap\Html::getInputId($model, 'spo_pph_nominal') ?>').val(0);            
            $('#<?= yii\bootstrap\Html::getInputId($model, 'spo_total') ?>').val(0);
        }
		setSubtotal();
		hitungppn();
		hitungpph();
        reordertable('#table-detail');
    });
}

function save(){
    var $form = $('#form-spo');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail()){
			if(checkPenawaran()){
				submitform($form);
			}
        }
    }
    return false;
}

function checkPenawaran(){
	var xcv = $("#allowpenawaran").val();
	if(xcv=="1"){
		return true;
	}else{
		openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/warningPenawaran']) ?>','modal-warning-penawaran');
		return false;
	}
}

function validatingDetail(){
    var has_error = 0;
    $('#table-detail tbody > tr').each(function(){
        var field1 = $(this).find('input[name*="[bhp_id]"]');
        var field2 = $(this).find('input[name*="[spod_qty]"]');
        if(!field1.val()){
            $(this).find('input[name*="[bhp_nama]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[bhp_nama]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[spod_qty]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( $(this).find('input[name*="[spod_qty]"]').val() == 0 ){
                has_error = has_error + 1;
                $(this).find('input[name*="[spod_qty]"]').parents('td').addClass('error-tb-detail');
            }else{
                $(this).find('input[name*="[spod_qty]"]').parents('td').removeClass('error-tb-detail');
            }
        }
    });
    
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    getItemsBySpo(id);
	var total = 0;
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tspo-spo_tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#tspo-tanggal_kirim').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'spo_is_pkp') ?>').is(':checked')){
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').parents('tr').attr('style','display:');
	}else{
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').parents('tr').attr('style','display:none');
	}
	setTimeout(function(){
		$('#table-detail tbody tr').each(function(){
			total += unformatNumber($(this).find('input[name*="[subtotal]"]').val());
		});
		$('#total').val(formatInteger(total));
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').val(formatInteger($('#<?= yii\bootstrap\Html::getInputId($model, 'spo_ppn_nominal') ?>').val()));
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_pph_nominal') ?>').val(formatInteger($('#<?= yii\bootstrap\Html::getInputId($model, 'spo_pph_nominal') ?>').val()));		
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_total') ?>').val(formatInteger($('#<?= yii\bootstrap\Html::getInputId($model, 'spo_total') ?>').val()));
	},1000);
}

function getItemsBySpo(){
    $('#table-detail').addClass('animation-loading');
    var spo_id = '<?= (isset($_GET['spo_id'])?$_GET['spo_id']:'') ?>';
    var html = "";
    if(spo_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/GetItemsBySpo']); ?>',
            type   : 'POST',
            data   : {spo_id:spo_id},
            success: function (data) {
                if(data){
                    html = data.html;
                    $('#table-detail tbody').html(html);
                    $('#table-detail').removeClass('animation-loading');
                    reordertable('#table-detail');
                }
				if(data.supplier){
					var fax = (data.supplier.fax)? "<b>"+data.supplier.fax+"</b>" :"<i>Tidak Ditemukan No Fax</i>";
					$('#place-supplierfax').html("Fax : "+fax);
					$('#place-supplierfax').attr("onclick","infoSupplier("+data.supplier.suplier_id+")");
				}
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }else{
        html = "<tr><td colspan='7'><center><i>Data tidak ditemukan</i></center></td></tr>"
        $('#table-detail tbody').html(html);
        $('#table-detail').removeClass('animation-loading');
    }
}

function daftarSpo(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/daftarSpo']) ?>','modal-daftar-spo','85%');
}

function printout(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/pobhp/printSpo') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=500px, scrollbars=yes');
}

function cancelPo(spo_id){
	openModal('<?php echo \yii\helpers\Url::toRoute(['/purchasing/pobhp/cancelSpo']) ?>?id='+spo_id,'modal-transaksi');
}

function sppDetail(spp_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpp']) ?>?id='+spp_id+'&bhp_id='+bhp_id,'modal-info-spp','75%');
}

function infoSupplier(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/logistik/supplier/info','id'=>'']) ?>'+id,'modal-supplier-info');
}

function setMataUang(){
	var selected = $("#<?= \yii\bootstrap\Html::getInputId($model, "mata_uang") ?>").val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/func/setMataUang']); ?>',
		type   : 'POST',
		data   : {selected:selected},
		success: function (data) {
			if(data){
				$('.place-mata-uang').html("("+data.name_en+")");
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function penawaranTerpilih(sppd_id,by="SPP"){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/penawaranTerpilih','id'=>'']) ?>'+sppd_id+'&by='+by,'modal-penawaran','80%');
}
function infoPenawaran(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/infoPenawaran','id'=>'']) ?>'+id+'&disableDelete=1&disableEdit=1';
	var modal_id = 'modal-info-penawaran';	
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function () {
			$("#"+modal_id).hide();
			$("#"+modal_id).remove();
		});
		spinbtn();
		draggableModal();
	});
}
</script>