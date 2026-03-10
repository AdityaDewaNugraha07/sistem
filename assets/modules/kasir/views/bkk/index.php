<?php
/* @var $this yii\web\View */
$this->title = 'Bukti Kas Keluar';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-bkk',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-3 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-md-12">
						<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarBkk()"><i class="fa fa-list"></i> <?= Yii::t('app', 'BKK yang telah dibuat'); ?></a>
					</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'BKK Baru'); ?></h4></span>
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
										if(!isset($_GET['bkk_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-3 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'tanggal',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'tipe')->dropDownList(['Kas Kecil'=>'Kas Kecil','Kas Besar'=>'Kas Besar'],['style'=>'padding:6px;']); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'kas_bon_id',['style'=>'width:50px;']); ?>
										<div class="form-group" id="tbp" style=" <?= (empty($model->tbp_reff))?"display: none;":""; ?> ">
											<label class="col-md-3 control-label"><?= Yii::t('app', 'TBP Terkait'); ?></label>
											<div class="col-md-7" style="padding-bottom: 5px;" id="place-tbp">
												<?php
												$tbplabel = "";
												if(!empty($model->tbp_reff)){
													foreach(explode(",", $model->tbp_reff) as $i => $tbp){
														$modTBP = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>$tbp]);
														$tbplabel .= "<a onclick='infoTBP(".$modTBP->terima_bhp_id.")'>".$tbp."</a><br>";
													}
													echo $tbplabel;
												}
												?>
											</div>
											<?= yii\bootstrap\Html::activeHiddenInput($model, 'tbp_reff'); ?>
										</div>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'totalnominal')->textInput(['class'=>'form-control float','disabled'=>'disabled']); ?>
										<?= $form->field($model, 'diterima_oleh')->dropDownList(\app\models\MPegawai::getOptionList( null ),['class'=>'form-control select2','prompt'=>'']); ?>
										<?= $form->field($model, 'dibuat_oleh')->dropDownList(\app\models\MPegawai::getOptionList( null ),['class'=>'form-control select2','prompt'=>'']); ?>
										<?= $form->field($model, 'ganti_uangkas',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
													->checkbox([],false)->label(Yii::t('app', 'Penggantian Uang Kasir')); ?>
                                    </div>
                                </div>
								<br><br><hr>
								<div class="row">
									<div class="col-md-5">
										<h4><?= Yii::t('app', 'Rincian BKK'); ?></h4>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
														<th><?= Yii::t('app', 'Deskripsi'); ?></th>
														<th style="width: 100px; "><?= Yii::t('app', 'Nominal'); ?></th>
														<th style="width: 100px; text-align: center;"><?= Yii::t('app', 'Kode Kasbon'); ?></th>
														<th style="width: 70px; text-align: center;"><?= Yii::t('app', ''); ?></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="2" class="text-align-right">Total &nbsp;</td>
														<td class="td-kecil text-align-right td-kecil"><?php echo \yii\bootstrap\Html::activeTextInput($model, 'totalnominal', ['class'=>'form-control float','disabled'=>'disabled','style'=>'width: 100px; padding:3px;']) ?></td>
													</tr>
													<tr>
														<td colspan="2" style="text-align: right;">
															<?php if(!isset($_GET['bkk_id'])){ ?>
																<div class="col-md-2" id="btn-additem-place">
																	<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Item Baru'); ?></a>
																	<a class="btn btn-sm yellow-gold btn-outline" id="btn-add-bon" onclick="pickPanelKasbon();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Kasbon'); ?></a>
																	<a class="btn btn-sm blue-steel btn-outline" id="btn-add-tbp" onclick="pickPanelTBP();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add TBP'); ?></a>
																</div>
															<?php } ?>
														</td>
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
									if(isset($_GET['bkk_id'])){
										$disabled = FALSE;
									}else{
										$disabled = TRUE;
									}
								?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>(($disabled==FALSE)? 'printout('.(isset($_GET['bkk_id'])?$_GET['bkk_id']:'').')' :''),'disabled'=>$disabled]); ?>
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
if(isset($_GET['bkk_id'])){
    $pagemode = "afterSave()";
}else if(isset($_GET['kas_bon_id'])){
    $pagemode = "setDetailFromKasbon()";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
	$('.select2').select2({
		allowClear: !0,
		placeholder: 'Ketik nama pegawai',
		width: null,
	});
    $pagemode;
", yii\web\View::POS_READY); ?>
<script>
function save(ele){
    var $form = $('#form-bkk');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail()){
            submitform($form);
        }
    }
	
    return false;
}

function validatingDetail(){
    var has_error = 0;
    $('#table-detail tbody > tr').each(function(){
        var field1 = $(this).find('textarea[name*="[detail_deskripsi]"]');
        var field2 = $(this).find('input[name*="[detail_nominal]"]');
        if(!field1.val()){
            $(this).find('textarea[name*="[detail_deskripsi]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('textarea[name*="[detail_deskripsi]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            $(this).find('input[name*="[detail_nominal]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[detail_nominal]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
	var total = 0;
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tbkk-tanggal').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
	getDetail();
}

function getDetail(){
	var bkk_id = '<?= isset($_GET['bkk_id'])?$_GET['bkk_id']:null; ?>';
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/bkk/getDetailBkk']); ?>',
		type   : 'POST',
		data   : {bkk_id:bkk_id},
		success: function (data) {
			if(data.html){
				$('#table-detail > tbody').append(data.html);
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function addItem(){
	// Check Closing
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/bkk/addItem']); ?>',
		type   : 'POST',
		data   : {tgl:tgl},
		success: function (data) {
			if(data.html){
				$('#table-detail > tbody').append(data.html);
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
	// End
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
		setTotal();
    });
}

function setTotal(){
	var totalnominal = 0;
	$('#table-detail > tbody > tr').each(function (){
		totalnominal += unformatNumber( $(this).find('input[name*="[detail_nominal]"]').val() );
	});
	$('input[name*="totalnominal"]').val( formatNumberForUser( totalnominal ) );
}

function setDetailFromKasbon(){
	var kas_bon_id = '<?= isset($_GET['kas_bon_id'])?$_GET['kas_bon_id']:null; ?>';
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/bkk/getDetailFromKasbon']); ?>',
		type   : 'POST',
		data   : {kas_bon_id:kas_bon_id},
		success: function (data) {
			if(data.html){
				$('#table-detail > tbody').append(data.html);
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarBkk(){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/bkk/daftarBkk']) ?>','modal-daftar-bkk','75%');
}

function printout(id){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/bkk/printout') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}


function pickPanelKasbon(){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/bkk/pickPanelKasbon']) ?>','modal-history','75%');
}
function pickingKasbon(){
	var picked = $('#select_data').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/kasir/bkk/PickKasbon']); ?>',
		type   : 'POST',
		data   : {picked:picked},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').append(data.html);
				$('#modal-history').modal('hide');
			}
			reordertable('#table-detail');
			setTotal();
			
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function pickPanelTBP(){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/pickPanelTBP']) ?>','modal-tbp','75%');
}
function pickingTBP(){
	var picked = $('#select_data').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/pickTBP']); ?>',
		type   : 'POST',
		data   : {picked:picked},
		success: function (data) {
			$('#tbp').removeAttr('style');
			$('#<?= yii\bootstrap\Html::getInputId($model, 'tbp_reff') ?>').val(data.kodeterima);
			$('#place-tbp').html(data.kodelabelterima);
			clearmodal();
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function infoKasbon(kas_bon_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/InfoKasbonkk','id'=>'']); ?>'+kas_bon_id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-kasbonkk").modal('show');
		$("#modal-info-kasbonkk").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
function infoTBP(terima_bhp_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp','id'=>'']); ?>'+terima_bhp_id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-tbp").modal('show');
		$("#modal-info-tbp").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
</script>