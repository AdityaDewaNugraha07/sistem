<?php
/* @var $this yii\web\View */
$this->title = 'Packinglist';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\JexcelAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
	'validateOnChange' => false,
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
table#table-op_export tr td{
	padding-top: 5px; vertical-align: top;
}
table#table-op_export tr td.col-md-8{
	padding-top: 5px; font-weight:700; vertical-align: top; font-size: 1.1rem;
}
table.table-contrainer thead tr th{
	padding : 1px !important;
}
.table-contrainer, 
.table-contrainer > tbody > tr > td, 
.table-contrainer > tbody > tr > th, 
.table-contrainer > tfoot > tr > td, 
.table-contrainer > tfoot > tr > th, 
.table-contrainer > thead > tr > td, 
.table-contrainer > thead > tr > th {
    border: 1px solid #A0A5A9;
	line-height: 0.9 !important;
	font-size: 1.2rem;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <?= Yii::t('app', 'Packinglist Overview'); ?>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Packing List'); ?></label>
											<div class="col-md-8">
												<span class="input-group-btn" style="width: 100%">
													<?= \yii\bootstrap\Html::activeDropDownList($model, 'packinglist_id', app\models\TPackinglist::getOptionListPackinglistExim(),['class'=>'form-control','prompt'=>'','onchange'=>'setOpEx()','disabled'=>true]); ?>
												</span>
												<span class="input-group-btn" style="width: 25%">
													<a class="btn btn-icon-only btn-default tooltips" onclick="masterProforma();" data-original-title="Cari Packinglist PROFORMA" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-file-o"></i></a>
													<a class="btn btn-icon-only btn-default tooltips" onclick="masterFinal();" data-original-title="Cari Packinglist FINAL" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-file-text-o"></i></a>
													<!--<a class="btn btn-icon-only btn-default tooltips" onclick="HistoryRevisi();" data-original-title="Cari History Revisi" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-clock-o"></i></a>-->
												</span>
											</div>
										</div>
										<?= \yii\bootstrap\Html::activeHiddenInput($model, "cust_id") ?>
										<?= \yii\bootstrap\Html::activeHiddenInput($model, "jenis_produk") ?>
										<div id="place-packinglistoverview"></div>
									</div>
									<div class="col-md-6">
										<?php 
										echo $form->field($model, 'nomor')->textInput(['style'=>'font-weight:bold']); ?>
										<?= $form->field($model, 'tanggal_packinglistexim',[
																'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'disetujui_finance')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control select2','prompt'=>'','data-placeholder'=>'Ketik Nama Pegawai']); ?>
										<?= $form->field($model, 'status')->dropDownList(\app\models\MDefaultValue::getOptionList('status-packinglist'),['class'=>'form-control']); ?>
										<?= $form->field($model, 'total_container')->textInput(['class'=>'form-control float','style'=>'width:50%','disabled'=>'disabled']); ?>
										<?= $form->field($model, 'total_bundles')->textInput(['class'=>'form-control float','style'=>'width:50%','disabled'=>'disabled']); ?>
										<?= $form->field($model, 'total_pcs')->textInput(['class'=>'form-control float','style'=>'width:50%','disabled'=>'disabled']); ?>
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Total Volume'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px; width: 40%;">
												<span class="input-group-btn" style="width: 100%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'total_volume', ['class'=>'form-control float','style'=>'width:100%','disabled'=>'disabled']) ?>
												</span>
												<span class="input-group-addon" style="padding-left: 5px; padding-right: 5px;">M<sup>3</sup> &nbsp;&nbsp;</span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Total Gross Weight'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px; width: 40%;">
												<span class="input-group-btn" style="width: 100%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'total_gross_weight', ['class'=>'form-control float','style'=>'width:100%','disabled'=>'disabled']) ?>
												</span>
												<span class="input-group-addon" style="padding-left: 5px; padding-right: 5px;">Kg(s)</span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Total Nett Weight'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px; width: 40%;">
												<span class="input-group-btn" style="width: 100%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'total_nett_weight', ['class'=>'form-control float','style'=>'width:100%','disabled'=>'disabled']) ?>
												</span>
												<span class="input-group-addon" style="padding-left: 5px; padding-right: 5px;">Kg(s)</span>
											</div>
										</div>
										<?= $form->field($model, 'bundle_partition',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>'])
													->checkbox(['disabled'=>true],false)->label(Yii::t('app', 'Random Partition')); ?>
										<?php echo $form->field($model, 'notes', ['options' => ['style' => 'margin-top:10px;']])->textarea()->label("Note"); ?>
									</div>
								</div>
                            </div>
							
                        </div>
						<div class="portlet light bordered" style="margin-top: -15px;">
                            <div class="portlet-title">
                                <div class="caption">
                                    <?= Yii::t('app', 'Container Details'); ?>
                                </div>
                                <div class="pull-right" style="margin-left: 10px; line-height: 0.8; width: 160px;">
                                    <span style="font-size: 0.9rem;"><i id="place-alias-approver" ></i> </span><br>
                                    <span style="font-size: 0.8rem;" id="place-alias-status"></span><br>
                                    <span style="font-size: 0.8rem;" id="place-alias-reason"></span>
                                </div>
                                <span class="pull-right">
                                    <a class="btn btn-sm blue-dark btn-outline" id="btn-setalias" onclick="setAlias()"></a>
                                </span>
                            </div>
                            <div class="portlet-body" id="place-container">
								<span style="font-size: 1.2rem; font-style: italic;" id="place-notfound">Container Not Found</span>
							</div>
						</div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print Packinglist'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>"printout('PRINT')"]); ?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print Summary Packinglist'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>"printoutlampiran('PRINT')"]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print SI'),['id'=>'btn-print','class'=>'btn blue-steel btn-outline ciptana-spin-btn','onclick'=>"printoutsi('PRINT')"]); ?>
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
$pagemode = "";
if(isset($_GET['packinglist_id'])){
    $pagemode = "$('#". yii\bootstrap\Html::getInputId($model, "packinglist_id") ."').val('".$_GET['packinglist_id']."').trigger('change');";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
//	$('select[name*=\"[packinglist_id]\"]').select2({
//		allowClear: !0,
//		placeholder: 'Pilih Proforma',
//	});
	$('#".yii\bootstrap\Html::getInputId($model, "status")." option[value=\"PROFORMA\"]').css('background-color','#FBE88C');
	$('#".yii\bootstrap\Html::getInputId($model, "status")." option[value=\"FINAL\"]').css('background-color','#95EBA3');
	$('select[name*=\"[packinglist_id]\"]').on('select2:open', function(e){ 
		$.ajax({
			url    : '".\yii\helpers\Url::toRoute(['/exim/packinglist/checkSPMExport'])."',
			type   : 'POST',
			data   : {},
			success: function (data) {
				if(data){
					$('#select2-tpackinglist-packinglist_id-results').find('li').each(function(){
						var li = $(this);
						var asd = $(this).attr('id');
						var fr_el = asd.split('-')[5];
						$(li).attr('style','font-size:1.1rem;');
						$(data).each(function(){
							var fr_db = $(this)[0].packinglist_id;
							if(fr_el == fr_db){
								$(li).attr('style','color:blue');
							}
						});
					});
				}
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
	});
", yii\web\View::POS_READY); ?>
<script>
function masterProforma(){
	var url = '<?= \yii\helpers\Url::toRoute(['/exim/packinglist/masterProforma']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function masterFinal(){
	var url = '<?= \yii\helpers\Url::toRoute(['/exim/packinglist/masterFinal']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pick(packinglist_id,par='asdasd'){
	$("#modal-master").find('button.fa-close').trigger('click');
	$('#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>').val(packinglist_id);
	setOpEx();
}
function setOpEx(){
	var packinglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>').val();
	$("#place-packinglistoverview").html("");
	$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val("");
	$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val("");
	$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_packinglistexim") ?>").val("");
	$("#<?= yii\bootstrap\Html::getInputId($model, "disetujui_finance") ?>").val("<?= \app\components\Params::DEFAULT_PEGAWAI_ID_EKO_NOWO ?>").trigger('change');
	$("#<?= yii\bootstrap\Html::getInputId($model, "status") ?>").val("PROFORMA");
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_container") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_bundles") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_pcs") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_volume") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_gross_weight") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_nett_weight") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "bundle_partition") ?>").prop('checked', false);
	$("#<?= yii\bootstrap\Html::getInputId($model, "notes") ?>").val("");
	$('#place-container').html('<span style="font-size: 1.2rem; font-style: italic;" id="place-notfound">Container Not Found</span>');
	$("#btn-addcontainer").attr("disabled","disabled").removeAttr("onclick");
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/exim/packinglist/setOpEx']); ?>',
        type   : 'POST',
        data   : {packinglist_id:packinglist_id},
        success: function (data) {
			$("#modal-master").find('button.fa-close').trigger('click');
			$("#<?= yii\bootstrap\Html::getInputId($model, "nomor") ?>").val(data.nomor);
			if(data.packinglist_html){
				$("#place-packinglistoverview").html(data.packinglist_html);
				$('select[name*=\"[cust_id]\"]').select2({
					allowClear: !0,
					placeholder: 'Pilih Applicant',
				});
				$('select[name*=\"[notify_party]\"]').select2({
					allowClear: !0,
					placeholder: 'Pilih Notify',
				});
			}
			if(data.packinglist){
				$("#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>").val(data.packinglist.packinglist_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val(data.packinglist.cust_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val(data.packinglist.jenis_produk);
				if(data.packinglist.nomor){
					$("#<?= yii\bootstrap\Html::getInputId($model, "nomor") ?>").val(data.packinglist.nomor);
				}
				$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_packinglistexim") ?>").val(data.packinglist.tanggal_packinglistexim);
				$("#<?= yii\bootstrap\Html::getInputId($model, "disetujui_finance") ?>").val(data.packinglist.disetujui_finance).trigger('change');
				$("#<?= yii\bootstrap\Html::getInputId($model, "status") ?>").val(data.packinglist.status);
				$("#<?= yii\bootstrap\Html::getInputId($model, "total_container") ?>").val(data.packinglist.total_container);
				$("#<?= yii\bootstrap\Html::getInputId($model, "total_bundles") ?>").val(data.packinglist.total_bundles);
				$("#<?= yii\bootstrap\Html::getInputId($model, "total_pcs") ?>").val(data.packinglist.total_pcs);
				$("#<?= yii\bootstrap\Html::getInputId($model, "total_volume") ?>").val(data.packinglist.total_volume);
				$("#<?= yii\bootstrap\Html::getInputId($model, "total_gross_weight") ?>").val(data.packinglist.total_gross_weight);
				$("#<?= yii\bootstrap\Html::getInputId($model, "total_nett_weight") ?>").val(data.packinglist.total_nett_weight);
				if(data.packinglist.bundle_partition == 1){
					$("#<?= yii\bootstrap\Html::getInputId($model, "bundle_partition") ?>").prop('checked', true);
				}else{
					$("#<?= yii\bootstrap\Html::getInputId($model, "bundle_partition") ?>").prop('checked', false);
				}
				$("#<?= yii\bootstrap\Html::getInputId($model, "notes") ?>").val(data.packinglist.notes);
				setContainer(data.packinglist.packinglist_id,function(){
					existSpm(data.spm);
				}); 
			}else{
				$("#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>").val("");
			}
            if(data.alias){
                $('#place-alias-approver').html(data.alias.approver);
                $('#place-alias-status').html(data.alias.status);
                $('#place-alias-reason').html(data.alias.reason);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setBuyer(ele){
	var cust_id = $(ele).val();
	$(ele).parents('tr').next().find("textarea").val("");
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/exim/opexport/setBuyer']); ?>',
        type   : 'POST',
        data   : {cust_id:cust_id},
        success: function (data) {
			if(data.cust_id){
				$("#modal-master").find('button.fa-close').trigger('click');
				$(ele).parents('tr').next().find("textarea").val(data.cust_an_alamat);
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setContainer(packinglist_id,callback=null){
	var jenis_produk = $("#<?= yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
	if($("#<?= \yii\helpers\Html::getInputId($model, "bundle_partition") ?>").is(":checked")){
		var bundle_partition = true;
	}else{
		var bundle_partition = false;
	}
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/packinglist/setContainer']); ?>',
		type   : 'POST',
		data   : {packinglist_id:packinglist_id},
		success: function (data) {
			if(data.htmlcontainer){
				$("#place-container").html(data.htmlcontainer);
				reordercontainer();
			}
			if(data.disabled_detail){
				$(".table-contrainer > tbody").each(function(){
					$(this).find("input, select").prop('disabled',true);
				});
			}
			if(jenis_produk == "Plywood" || jenis_produk == "Lamineboard" || jenis_produk == "Platform"){
				$("#btn-setalias").html("Set Alias");
			}else if(jenis_produk == "Moulding" || jenis_produk == "FingerJointLamineBoard" || jenis_produk == "FingerJointStick" || jenis_produk == "Flooring"){
				$("#btn-setalias").html("Set Alias");
			}else{
				$("#btn-setalias").html("");
			}
			if( callback ){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function existSpm(spm){
	if(spm){
		$("#<?= yii\bootstrap\Html::getInputId($model, "nomor") ?>").prop("disabled",true);
		$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_packinglistexim") ?>").prop("disabled",true);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_packinglistexim') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'disetujui_finance') ?>').prop("disabled",true);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'status') ?>').prop("disabled",true);
		$(".table-contrainer").each(function(){
			$(this).find("input[name*='[container_kode]']").prop("disabled",true);
			$(this).find("input[name*='[seal_no]']").prop("disabled",true);
			$(this).find("input[name*='[gross_weight]']").prop("disabled",true);
			$(this).find("input[name*='[nett_weight]']").prop("disabled",true);
		});
	}else{
		$("#<?= yii\bootstrap\Html::getInputId($model, "nomor") ?>").prop("disabled",false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_packinglistexim") ?>").prop("disabled",false);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_packinglistexim') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'disetujui_finance') ?>').prop("disabled",false);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'status') ?>').prop("disabled",false);
		$(".table-contrainer").each(function(){
			$(this).find("input[name*='[container_kode]']").prop("disabled",false);
			$(this).find("input[name*='[seal_no]']").prop("disabled",false);
			$(this).find("input[name*='[gross_weight]']").prop("disabled",false);
			$(this).find("input[name*='[nett_weight]']").prop("disabled",false);
		});
	}
}

function setValueRandomAfterSave(packinglist_id){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/packinglist/setValueRandomAfterSave']); ?>',
		type   : 'POST',
		data   : {packinglist_id:packinglist_id},
		success: function (data) {
			if(data){
				$(data).each(function(){
					var db = this;
					$(".table-contrainer").each(function(){
						var elm = this; var container_no = $(elm).find("input[name='container_no']").val();
						if(container_no == db['container_no']){
							$(elm).find("input[name='container_kode']").val(db['container_kode']);
							$(elm).find("input[name='seal_no']").val(db['seal_no']);
							$(elm).find("input[name='gross_weight']").val( formatNumberForUser(db['gross_weight']) );
							$(elm).find("input[name='nett_weight']").val( formatNumberForUser(db['nett_weight']) );
							$(elm).find("tbody > tr").each(function(){
								var bundles_no = $(this).find('input[name*="[bundles_no]"]').val();
								var partition_kode = $(this).find('input[name*="[partition_kode]"]').val();
								if(bundles_no == db['bundles_no'] && partition_kode == db['partition_kode']){
									$(this).find('input[name*="[packinglist_container_id]"]').val( db['packinglist_container_id'] );
									$(this).find('select[name*="[grade]"]').val( db['grade'] );
									$(this).find('select[name*="[jenis_kayu]"]').val( db['jenis_kayu'] );
									$(this).find('select[name*="[glue]"]').val( db['glue'] );
									$(this).find('select[name*="[profil_kayu]"]').val( db['profil_kayu'] );
									$(this).find('select[name*="[kondisi_kayu]"]').val( db['kondisi_kayu'] );
									$(this).find('input[class*="qtyperuk"]').each(function(){
										var ukran_name = $(this).attr('id').split("_")[2];
										var ukran_value = $(this).attr('id').split("_")[3];
										if(ukran_value == db[ukran_name]){
											$(this).val( db['pcs'] );
										}
									});
									if($(this).find('.qtyperuk').length == 0){
										$(this).find('input[name*="[pcs]"]').val( db['pcs'] );
										$(this).find('input[name*="[volume]"]').val( db['volume'] );
									}
								}
							});
						}
					});
				});
				$(".table-contrainer").each(function(){
					totalRandom( $(this).find('input[name="container_no"]')[0] );
				});
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function addContainer(){
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($modOpEx, "jenis_produk") ?>').val();
	var total_contrainer = $('#<?= yii\bootstrap\Html::getInputId($model, "total_container") ?>').val();
	var op_export_id = $('#<?= yii\bootstrap\Html::getInputId($modOpEx, "op_export_id") ?>').val();
	var gross_weight = $(".table-contrainer:last").find('input[name*="gross_weight"]').val();
	var nett_weight = $(".table-contrainer:last").find('input[name*="nett_weight"]').val();
	if($("#<?= \yii\helpers\Html::getInputId($model, "bundle_partition") ?>").is(":checked")){
		var bundle_partition = true;
	}else{
		var bundle_partition = false;
	}
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/packinglist/addContainer']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk,op_export_id:op_export_id,gross_weight:gross_weight,nett_weight:nett_weight,bundle_partition:bundle_partition},
		success: function (data) {
			if(bundle_partition===true){
//				loadExcel();
				openModal('<?= \yii\helpers\Url::toRoute('/exim/packinglist/SetRandomTemplate') ?>?jenis_produk='+jenis_produk,'modal-setrandomtemplate','35%');
			}else{
				if(data.html){
					$('#place-notfound').remove();
					$('#place-container').append(data.html);
					$(".tooltips").tooltip({ delay: 50 });
					reordercontainer();
						total();
				}
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function reordercontainer(){
    var row = 0;
    $('#place-container').find('input[name="container_no"]').each(function(){
        $(this).val(row+1);
		$(this).closest('table').attr('id','table-container-'+row);
		if($("#<?= \yii\helpers\Html::getInputId($model, "bundle_partition") ?>").is(":checked")){
			$('#'+$(this).closest('table').attr('id')+' > tbody > tr').each(function(){
				$(this).find('input[name*="[container_no]"]').val((row+1));
			});
		}
        row++;
    });
    formconfig();
}
function hapuscontainer(ele){
	$(ele).closest('.row').remove();
	reordercontainer();
	var table_id = $(ele).parents("table").attr("id");
	reordertablebundle(table_id);
}
function addBundle(ele){
	var container_no = $(ele).closest("table").find('input[name="container_no"]').val();
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($modOpEx, "jenis_produk") ?>").val();
	var table_id = $(ele).closest("table").attr('id');
	var grade = $("#"+table_id+" > tbody > tr:last").find('select[name*="[grade]"]').val();
	var jenis_kayu = $("#"+table_id+" > tbody > tr:last").find('select[name*="[jenis_kayu]"]').val();
	var glue = $("#"+table_id+" > tbody > tr:last").find('select[name*="[glue]"]').val();
	var profil_kayu = $("#"+table_id+" > tbody > tr:last").find('select[name*="[profil_kayu]"]').val();
	var kondisi_kayu = $("#"+table_id+" > tbody > tr:last").find('select[name*="[kondisi_kayu]"]').val();
	var thick = unformatNumber( $("#"+table_id+" > tbody > tr:last").find('input[name*="[thick]"]').val() );
	var thick_unit = $("#"+table_id+" > tbody > tr:last").find('select[name*="[thick_unit]"]').val();
	var width = unformatNumber( $("#"+table_id+" > tbody > tr:last").find('input[name*="[width]"]').val() );
	var width_unit = $("#"+table_id+" > tbody > tr:last").find('select[name*="[width_unit]"]').val();
	var length = unformatNumber( $("#"+table_id+" > tbody > tr:last").find('input[name*="[length]"]').val() );
	var length_unit = $("#"+table_id+" > tbody > tr:last").find('select[name*="[length_unit]"]').val();
	var pcs = unformatNumber( $("#"+table_id+" > tbody > tr:last").find('input[name*="[pcs]"]').val() );
	var volume = unformatNumber( $("#"+table_id+" > tbody > tr:last").find('input[name*="[volume]"]').val() );
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/packinglist/addBundle']); ?>',
		type   : 'POST',
		data   : {container_no:container_no,jenis_produk:jenis_produk,table_id:table_id,
				  grade:grade,jenis_kayu:jenis_kayu,glue:glue,profil_kayu:profil_kayu,kondisi_kayu:kondisi_kayu,
				  thick:thick,thick_unit:thick_unit,width:width,width_unit:width_unit,length:length,length_unit:length_unit,pcs:pcs,volume:volume},
		success: function (data) {
			if(data.html){
				$(ele).closest("table").find('#place-emptytr').remove();
				$(ele).closest("table").find('tbody').append(data.html);
				reordertablebundle(table_id);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function reordertablebundle(table_id){
    var row = 0;
    var bundles_no = 0;
	if($("#<?= \yii\helpers\Html::getInputId($model, "bundle_partition") ?>").is(":checked")){
		var bundle_partition = true;
	}else{
		var bundle_partition = false;
	}
    $(".table-contrainer > tbody > tr").not("#uncount-tr").each(function(){
        $(this).find("#no_urut").val(row+1);
        $(this).find("span.no_urut").text(row+1);
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 3){
				$(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]);
				$(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]");
            }
			if(old_name_arr.length == 4){
				$(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]+"_"+old_name_arr[3]);
				$(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]["+old_name_arr[3]+"]");
			}
        });
        row++;
    });
	if(bundle_partition !== true){
		$("#"+table_id+" > tbody > tr").not("#uncount-tr").each(function(){
			$(this).find("input[name*='[bundles_no]']").val(bundles_no+1);
			bundles_no++;
		});
	}
	if( $("#"+table_id+" > tbody > tr").not("#uncount-tr").length <= 0 ){
		$("#"+table_id+" > tbody").html('<tr id="place-emptytr" class="uncount-tr"><td colspan="8" style="text-align: center; font-size: 1.2rem;">Data Tidak Ditemukan</td></tr>')
	}
    formconfig();
	setContainerDetails();
	total();
}
function hapusbundle(ele,table_id){
	$(ele).closest('tr').remove();
	reordertablebundle(table_id);
}
function setMeterKubik(ele){
    var p = unformatNumber( $(ele).parents("tr").find('input[name*="[length]"]').val() );
    var l = unformatNumber( $(ele).parents("tr").find('input[name*="[width]"]').val() );
    var t = unformatNumber( $(ele).parents("tr").find('input[name*="[thick]"]').val() );
    var sat_p = $(ele).parents("tr").find('select[name*="[length_unit]"], input[name*="[length_satuan]"]').val();
    var sat_l = $(ele).parents("tr").find('select[name*="[width_unit]"], input[name*="[width_satuan]"]').val();
    var sat_t = $(ele).parents("tr").find('select[name*="[thick_unit]"], input[name*="[thick_satuan]"]').val();
    var qty = unformatNumber( $(ele).parents("tr").find('input[name*="[pcs]"]').val() );
    var sat_p_m = 0;
    var sat_l_m = 0;
    var sat_t_m = 0;
    var result = 0;
    
    if(sat_p == 'mm'){
        sat_p_m = p * 0.001;
    }else if(sat_p == 'cm'){
        sat_p_m = p * 0.01;
    }else if(sat_p == 'inch'){
        sat_p_m = p * 0.0254;
    }else if(sat_p == 'm'){
        sat_p_m = p;
    }else if(sat_p == 'feet'){
        sat_p_m = p * 0.3048;
    }
    if(sat_l == 'mm'){
        sat_l_m = l * 0.001;
    }else if(sat_l == 'cm'){
        sat_l_m = l * 0.01;
    }else if(sat_l == 'inch'){
        sat_l_m = l * 0.0254;
    }else if(sat_l == 'm'){
        sat_l_m = l;
    }else if(sat_l == 'feet'){
        sat_l_m = l * 0.3048;
    }
    if(sat_t == 'mm'){
        sat_t_m = t * 0.001;
    }else if(sat_t == 'cm'){
        sat_t_m = t * 0.01;
    }else if(sat_t == 'inch'){
        sat_t_m = t * 0.0254;
    }else if(sat_t == 'm'){
        sat_t_m = t;
    }else if(sat_t == 'feet'){
        sat_t_m = t * 0.3048;
    }
    result = sat_p_m * sat_l_m * sat_t_m * qty;
    result = (Math.round( result * 10000 ) / 10000 ).toString();
    $(ele).parents("tr").find('input[name*="[volume]"]').val( formatNumberForUser(result) );
	total();
}

function setContainerDetails(){
	if( $(".table-contrainer").length > 0 ){
		$(".table-contrainer").each(function(){
			var container_no = $(this).find("input[name='container_no']").val();
			var container_kode = $(this).find("input[name='container_kode']").val();
			var seal_no = $(this).find("input[name='seal_no']").val();
			var gross_weight = $(this).find("input[name='gross_weight']").val();
			var nett_weight = $(this).find("input[name='nett_weight']").val();
			$(this).find("tbody input[name*='[container_no]']").val(container_no);
			$(this).find("tbody input[name*='[container_kode]']").val(container_kode);
			$(this).find("tbody input[name*='[seal_no]']").val(seal_no);
			$(this).find("tbody input[name*='[gross_weight]']").val(gross_weight);
			$(this).find("tbody input[name*='[nett_weight]']").val(nett_weight);
		});
	}
}

function setBundlePartition(){
	$("#place-container").addClass("animation-loading");
	$('#place-container').html('<span style="font-size: 1.2rem; font-style: italic;" id="place-notfound">Container Not Found</span>');
	if($("#<?= \yii\helpers\Html::getInputId($model, "bundle_partition") ?>").is(":checked")){
		$("#place-container").removeClass("animation-loading");
	}else{
		$("#place-container").removeClass("animation-loading");
	}
}

function loadExcel(){
	var data = [
		['Mazda', 2001, 2000],
		['Pegeout', 2010, 5000],
		['Honda Fit', 2009, 3000],
		['Honda CRV', 2010, 6000],
	];
	$('.table-contrainer:last').find('#place-randomsize').jexcel({
		data:data,
		colHeaders: ['Bundle No.', 'Part', 'Grade', 'Wood Type', 'Glue', 'Thick', 'Width', 'Length', 'Total'],
//		colWidths: [ 300, 80, 100 ]
	});
}

function total(){
	setTimeout(function(){
		var total_pcs = 0;
		var total_vol = 0;
		var total_gross_weight = 0;
		var total_nett_weight = 0;
		var bundles = 0;
		$('table.table-contrainer').each(function(){
			var tot_pcs = 0;
			var tot_vol = 0;
			$(this).find("tbody > tr").each(function(){
				tot_pcs += unformatNumber( $(this).find('input[name*="[pcs]"]').val() );
				tot_vol += unformatNumber( $(this).find('input[name*="[volume]"]').val() );
			});
			total_pcs += tot_pcs;
			total_vol += tot_vol;
			$(this).find('input[name*="tot_pcs"]').val(tot_pcs);
			$(this).find('input[name*="tot_vol"]').val(formatNumberFixed4(tot_vol));
			total_gross_weight += unformatNumber( $(this).find('input[name*="gross_weight"]').val() );
			total_nett_weight += unformatNumber( $(this).find('input[name*="nett_weight"]').val() );
		});
		if( $('table.table-contrainer').find('input[name*="[bundles_no]"]:last') ){
			$('table.table-contrainer').find('input[name*="[bundles_no]"]:last').each(function(){
				bundles += unformatNumber( $(this).val() );
			});
		}
		$("#<?= yii\bootstrap\Html::getInputId($model, "total_container") ?>").val(formatNumberForUser($('table.table-contrainer').length));
		$("#<?= yii\bootstrap\Html::getInputId($model, "total_bundles") ?>").val(formatNumberForUser( bundles ));
		$("#<?= yii\bootstrap\Html::getInputId($model, "total_pcs") ?>").val(formatNumberForUser(total_pcs));
		$("#<?= yii\bootstrap\Html::getInputId($model, "total_volume") ?>").val(formatNumberFixed4(total_vol));
		$("#<?= yii\bootstrap\Html::getInputId($model, "total_gross_weight") ?>").val(formatNumberForUser(total_gross_weight));
		$("#<?= yii\bootstrap\Html::getInputId($model, "total_nett_weight") ?>").val(formatNumberForUser(total_nett_weight));
	},500);
}

function totalWeight(){
	var tot_gross = 0;
	var tot_nett = 0;
	$('table.table-contrainer').each(function(){
		var gross = unformatNumber( $(this).find('input[name*="[gross_weight]"]').val() );
		var nett = unformatNumber( $(this).find('input[name*="[nett_weight]"]').val() );
		tot_gross += gross;
		tot_nett += nett;
	});
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_gross_weight") ?>").val(formatNumberForUser(tot_gross));
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_nett_weight") ?>").val(formatNumberForUser(tot_nett));
}

function save(){
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($modOpEx, "op_export_id") ?>").parents(".form-group").removeClass("error-tb-detail");
	var tot_gross = 0;
	var tot_nett = 0;
	$('table.table-contrainer').each(function(){
		var gross = unformatNumber( $(this).find('input[name*="[gross_weight]"]').val() );
		var nett = unformatNumber( $(this).find('input[name*="[nett_weight]"]').val() );
		tot_gross += gross;
		tot_nett += nett;
	});
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_gross_weight") ?>").val(formatNumberForUser(tot_gross));
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_nett_weight") ?>").val(formatNumberForUser(tot_nett));
    if(formrequiredvalidate($form)){
		var jumlah_container = $('.table-contrainer').length;
        if(jumlah_container <= 0){
			cisAlert('Isi detail terlebih dahulu');
			return false;
        }
		var jumlah_container_kosong = $('.table-contrainer tbody tr.uncount-tr').length;
		if(jumlah_container_kosong > 0){
			cisAlert('Ada container yang masih kosong!');
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
	
	if($("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val() == "FINAL"){
		if($("#<?= \yii\bootstrap\Html::getInputId($model, "nomor") ?>").val().indexOf("XXXXX") != -1){
			$("#<?= \yii\bootstrap\Html::getInputId($model, "nomor") ?>").addClass("error-tb-detail");
			has_error = has_error + 1;
		}
		var nomor = $("#<?= \yii\bootstrap\Html::getInputId($model, "nomor") ?>").val();
		var splitnomor = nomor.split('/');
		if(splitnomor[0].length != 5){
			$("#<?= \yii\bootstrap\Html::getInputId($model, "nomor") ?>").addClass("error-tb-detail");
			has_error = has_error + 1;
		}
	}
	
	$('.table-contrainer tbody tr').each(function(){
		
	});
	
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('input').each(function(){ $(this).removeAttr("placeholder"); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#<?= yii\bootstrap\Html::getInputId($modOpEx, 'pegawai_mutasi') ?>').attr('disabled','');
	$('#<?= yii\bootstrap\Html::getInputId($modOpEx, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($modOpEx, 'departure_estimated_date') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($modOpEx, 'arrival_estimated_date') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if(isset($_GET['edit'])){ ?>
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "tanggal") ?>").prop("disabled", false);
		$('#<?= yii\bootstrap\Html::getInputId($modOpEx, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "jenis_produk") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "port_of_loading") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "vessel") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "departure_estimated_date") ?>").prop("disabled", false);
		$('#<?= yii\bootstrap\Html::getInputId($modOpEx, 'departure_estimated_date') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "final_destination") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "arrival_estimated_date") ?>").prop("disabled", false);
		$('#<?= yii\bootstrap\Html::getInputId($modOpEx, 'arrival_estimated_date') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "static_product_code") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "goods_description") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "payment_method") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "term_of_price") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "origin") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "svlk_no") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($modOpEx, "vlegal_no") ?>").prop("disabled", false);
		$('#btn-save').removeAttr('disabled');
		$('#btn-print').attr('disabled');
	<?php } ?>
}

function printout(caraprint){
	var id = $('#<?= \yii\bootstrap\Html::getInputId($model, "packinglist_id"); ?>').val();
	window.open("<?= yii\helpers\Url::toRoute('/exim/packinglist/print') ?>?id="+id+"&caraprint="+caraprint,"",'location=_new, width=1200px, scrollbars=yes');
}
function printoutlampiran(caraprint){
	var id = $('#<?= \yii\bootstrap\Html::getInputId($model, "packinglist_id"); ?>').val();
	window.open("<?= yii\helpers\Url::toRoute('/exim/packinglist/printlampiran') ?>?id="+id+"&caraprint="+caraprint,"",'location=_new, width=1200px, scrollbars=yes');
}
function printoutsi(caraprint){
	var id = $('#<?= \yii\bootstrap\Html::getInputId($model, "packinglist_id"); ?>').val();
	openModal('<?= \yii\helpers\Url::toRoute('/exim/packinglist/konfirmsi') ?>?packinglist_id='+id,'modal-konfirmsi','70%');
//	window.open("<?= yii\helpers\Url::toRoute('/exim/packinglist/printsi') ?>?id="+id+"&caraprint="+caraprint,"",'location=_new, width=1200px, scrollbars=yes');
}

function setAlias(){
	var packinglist_id = $("#<?= yii\helpers\Html::getInputId($model, "packinglist_id") ?>").val();
	openModal('<?= \yii\helpers\Url::toRoute('/exim/packinglist/setAlias') ?>?packinglist_id='+packinglist_id,'modal-setalias','60%');
}
</script>