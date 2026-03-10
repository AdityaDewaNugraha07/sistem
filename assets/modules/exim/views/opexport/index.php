<?php
/* @var $this yii\web\View */
$this->title = 'Order Penjualan (OP) Export';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\MagnificPopupAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
.add-more:hover {
    background: #58ACFA;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<span class="pull-right">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'OP Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>
										<?php
										if(!isset($_GET['op_export_id'])){
											echo "Order Penjualan Export Baru";
										}else{
											echo "Data Order Penjualan Export";
										}
										?>
									</h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
									<div class="col-md-6">
										<?php if(!isset($_GET['op_export_id'])){ ?>
											<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:600'])->label("Order No."); ?>
										<?php }else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Order No.'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?php 
										if(!isset($_GET['op_export_id']) || isset($_GET['edit'])){
											echo $form->field($model, 'nomor_kontrak')->textInput(['style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Nomor Kontrak'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'nomor_kontrak', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->nomor_kontrak ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'tanggal',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Kontrak"); ?>
										<?= $form->field($model, 'jenis_produk')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-produk'),[]); ?>
										<?= $form->field($model, 'port_of_loading')->textInput(); ?>
										<?= $form->field($model, 'hs_code')->textInput(['placeholder'=>'ex. 4409.22.00']); ?>
										<?= $form->field($model, 'origin')->textInput(); ?>
									</div>
									<div class="col-md-6">
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Applicant'); ?></label>
											<div class="col-md-8">
												<?= \yii\bootstrap\Html::activeDropDownList($model, 'cust_id', \app\models\MCustomer::getOptionListExport(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setBuyer(this)','style'=>'width:100%;']); ?>
											</div>
										</div>
										<?= $form->field($modBuyer, 'cust_an_alamat')->textarea(['disabled'=>'disabled'])->label("Applicant Address"); ?>
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Notify Party'); ?></label>
											<div class="col-md-8">
												<?= \yii\bootstrap\Html::activeDropDownList($model, 'notify_party', \app\models\MCustomer::getOptionListExport(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setBuyer(this)','style'=>'width:100%;']); ?>
											</div>
										</div>
										<?= $form->field($modBuyer, 'cust_an_alamat2')->textarea(['disabled'=>'disabled'])->label("Notify Party Address"); ?>
										<?= $form->field($model, 'payment_method')->dropDownList(\app\models\MDefaultValue::getOptionList('payment-method-export'),[]); ?>
										<?= $form->field($model, 'term_of_price')->textInput(['placeholder'=>'ex. CFR KAOHSIUNG']); ?>
									</div>
								</div>
								<br><hr>
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: -15px; margin-bottom: -10px;">
                                        <h5><?= Yii::t('app', 'Goods Description'); ?> :</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-bordered" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th style="width: 40px;"><?= Yii::t('app', 'No.'); ?></th>
														<th style=""><?= Yii::t('app', 'Good Description'); ?></th>
														<th style="width: 150px;"><?= Yii::t('app', 'Size'); ?></th>
														<th style="width: 80px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
														<th style="width: 80px;"><?= Yii::t('app', 'Price / M<sup>3</sup>'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Total Price'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Lot Code'); ?></th>
														<th style="width: 100px; line-height: 1; font-size: 1.1rem; padding: 2px;"><?= Yii::t('app', 'Shipment Time<br>Estimate'); ?></th>
														<th style="width: 40px;"></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="3" class="text-align-right" style="padding: 3px; vertical-align: middle;">
															<table style="width: 100%" border="0" id="table-qty">
																<tbody>
																	<tr>
																		<td>
																			<span class="pull-left">
																				<a class="btn btn-xs btn-default" onclick="addItem();"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Descriptions'); ?></a>
																			</span>
																		</td>
																		<td>
																			<b>Qty : </b> 
																			<?= yii\bootstrap\Html::textInput("detail_qty[ii][detail_vehicle_qty]",'1',['style'=>'width:25px; text-align:right; height:24px;']) ?> X 
																			<?= yii\bootstrap\Html::dropDownList("detail_qty[ii][detail_vehicle_type]",'', ['container'=>'Container','truck'=>'Truck'],['options'=>['container'=>['selected'=>true]]]) ?>  
																			<?= yii\bootstrap\Html::dropDownList("detail_qty[ii][detail_vehicle_size]",'', ['20'=>'20 Feet','40'=>'40 Feet'],['options'=>['20'=>['selected'=>true]]]) ?>
																				<a class="btn btn-xs btn-default" onclick="addQty();"><i class="fa fa-plus"></i></a>
																		</td>
																		<td>
																			
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
														<td id="place-total_m3" style="padding: 3px;">
															<?= \yii\helpers\Html::textInput("total_m3",'0',['class'=>'form-control float','style'=>'padding:3px;','disabled'=>'disabled']); ?>
														</td>
														<td id="" style="padding: 3px;"></td>
														<td id="place-total_price" style="padding: 3px;">
															<?= \yii\helpers\Html::textInput("total_price",'0',['class'=>'form-control float','style'=>'padding:3px;','disabled'=>'disabled']); ?>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?= Yii::t('app', 'Attachment'); ?></h5>
										<div id="place-attch">
											<div class="col-md-2">
												<?php
												echo $form->field($modAttachment, 'file',[
													'template'=>'
														<div class="col-md-12">
															<div class="fileinput fileinput-new" data-provides="fileinput">
																<div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
																	<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
																<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
																<div>
																	<span class="btn btn-xs blue-hoki btn-outline btn-file">
																		<span class="fileinput-new"> Select File </span>
																		<span class="fileinput-exists"> Change </span>
																		{input} 
																	</span> 
																	<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																	{error}
																</div>
															</div>
														</div>'
												])->fileInput();
												?>
											</div>
											<div class="col-md-2 hidden">
												<?php
												echo $form->field($modAttachment, 'file1',[
													'template'=>'
														<div class="col-md-12">
															<div class="fileinput fileinput-new" data-provides="fileinput">
																<div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
																	<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
																<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
																<div>
																	<span class="btn btn-xs blue-hoki btn-outline btn-file">
																		<span class="fileinput-new"> Select File </span>
																		<span class="fileinput-exists"> Change </span>
																		{input} 
																	</span> 
																	<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																	{error}
																</div>
															</div>
														</div>'
												])->fileInput();
												?>
											</div>
											<div class="col-md-2 hidden">
												<?php
												echo $form->field($modAttachment, 'file2',[
													'template'=>'
														<div class="col-md-12">
															<div class="fileinput fileinput-new" data-provides="fileinput">
																<div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
																	<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
																<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
																<div>
																	<span class="btn btn-xs blue-hoki btn-outline btn-file">
																		<span class="fileinput-new"> Select File </span>
																		<span class="fileinput-exists"> Change </span>
																		{input} 
																	</span> 
																	<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																	{error}
																</div>
															</div>
														</div>'
												])->fileInput();
												?>
											</div>
											<div class="col-md-2 hidden">
												<?php
												echo $form->field($modAttachment, 'file3',[
													'template'=>'
														<div class="col-md-12">
															<div class="fileinput fileinput-new" data-provides="fileinput">
																<div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
																	<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
																<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
																<div>
																	<span class="btn btn-xs blue-hoki btn-outline btn-file">
																		<span class="fileinput-new"> Select File </span>
																		<span class="fileinput-exists"> Change </span>
																		{input} 
																	</span> 
																	<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																	{error}
																</div>
															</div>
														</div>'
												])->fileInput();
												?>
											</div>
											<div class="col-md-2 hidden">
												<?php
												echo $form->field($modAttachment, 'file4',[
													'template'=>'
														<div class="col-md-12">
															<div class="fileinput fileinput-new" data-provides="fileinput">
																<div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
																	<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
																<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
																<div>
																	<span class="btn btn-xs blue-hoki btn-outline btn-file">
																		<span class="fileinput-new"> Select File </span>
																		<span class="fileinput-exists"> Change </span>
																		{input} 
																	</span> 
																	<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																	{error}
																</div>
															</div>
														</div>'
												])->fileInput();
												?>
											</div>
											<div class="col-md-2 hidden">
												<?php
												echo $form->field($modAttachment, 'file5',[
													'template'=>'
														<div class="col-md-12">
															<div class="fileinput fileinput-new" data-provides="fileinput">
																<div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
																	<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
																<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
																<div>
																	<span class="btn btn-xs blue-hoki btn-outline btn-file">
																		<span class="fileinput-new"> Select File </span>
																		<span class="fileinput-exists"> Change </span>
																		{input} 
																	</span> 
																	<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																	{error}
																</div>
															</div>
														</div>'
												])->fileInput();
												?>
											</div>
										</div>
										<div class="col-md-2">
											<div class="thumbnail add-more" style="width: 150px; height: 115px; cursor: pointer;" onclick="addAttch();">
												<img src="<?= Yii::$app->view->theme->baseUrl ?>/cis/img/add-more.png" alt="" /> 
											</div>
										</div>
                                    </div>
									<div class="col-md-12">
										
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
$pagemode = "";
if(isset($_GET['op_export_id'])){
    $pagemode = "afterSave(".$_GET['op_export_id']."); getItems(".$_GET['op_export_id'].");";
}else{
	$pagemode = "addItem();";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	$('select[name*=\"[cust_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Buyer',
	});
	$('select[name*=\"[notify_party]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Buyer',
	});
	$('#".\yii\helpers\Html::getInputId($model, "shipment_time")."').datepicker({
		rtl: App.isRTL(),
		orientation: 'left',
		autoclose: !0,
		format: 'mm/yyyy',
		viewMode: 'months', 
		minViewMode: 'months',
		clearBtn:true,
		todayHighlight:true
	});
", yii\web\View::POS_READY); ?>
<script>
function masterBuyer(){
	var url = '<?= \yii\helpers\Url::toRoute(['/exim/opexport/masterBuyer']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pick(cust_id,par){
	$("#modal-master").find('button.fa-close').trigger('click');
	$("#<?php echo yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val(cust_id).trigger('change');
//	$("#<?php // echo yii\bootstrap\Html::getInputId($model, "cust_id") ?>").empty().append('<option value="'+cust_id+'">'+par+'</option>').val(cust_id).trigger('change');
}
function setBuyer(ele){
	var cust_id = $(ele).val();
	$(ele).parents('.form-group').next().find("textarea[name*=cust_an_alamat]").val('');
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/exim/opexport/setBuyer']); ?>',
        type   : 'POST',
        data   : {cust_id:cust_id},
        success: function (data) {
			if(data.cust_id){
				$("#modal-master").find('button.fa-close').trigger('click');
				$(ele).parents('.form-group').next().find("textarea[name*=cust_an_alamat]").val(data.cust_an_alamat);
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function addItem(){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/exim/opexport/addItem']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail > tbody').fadeIn(200,function(){
					$(this).find(".tooltips").tooltip({ delay: 50 });
					reordertable('#table-detail');
					reordertableqty('#table-qty');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function addQty(){
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/exim/opexport/addQty']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data) {
            if(data.html){
                $('#table-qty > tbody').append(data.html);
				reordertableqty('#table-qty');
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function removeQty(ele){
    $(ele).closest('tr').fadeOut(200,function(){
        $(this).remove();
        reordertableqty('#table-qty');
    });
}

function reordertableqty(){
	var row = 0;
    $('#table-qty > tbody > tr').each(function(){
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
}

function total(){
	var total_m3 = 0; var total_price = 0;
	$('#table-detail > tbody tr').each(function(){
		var m3 = unformatNumber( $(this).find('input[name*="[detail_volume]"]').val() );
		var price = unformatNumber( $(this).find('input[name*="[detail_price]"]').val() );
		$(this).find('input[name*="[detail_subtotal]"]').val( formatNumberForUser(m3 * price) );
		total_m3 += m3;
		total_price += (m3 * price);
	});
	$('input[name*="total_m3"]').val( formatNumberForUser(total_m3) );
	$('input[name*="total_price"]').val( formatNumberForUser(total_price) );
}

function save(){
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($model, "nomor_kontrak") ?>").removeClass("error-tb-detail");
    if(formrequiredvalidate($form)){
        if(validatingDetail()){
            submitform($form);
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
	if($("#<?= \yii\bootstrap\Html::getInputId($model, "nomor_kontrak") ?>").val().indexOf("XXX") != -1){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "nomor_kontrak") ?>").addClass("error-tb-detail");
		has_error = has_error + 1;
	}
	$('#table-detail > tbody > tr').each(function(){
		if( !$(this).find('textarea[name*="[detail_description]"]').val() ){
			$(this).find('textarea[name*="[detail_description]"]').addClass("error-tb-detail");
			has_error = has_error + 1;
		}else{
			$(this).find('textarea[name*="[detail_description]"]').removeClass("error-tb-detail");
		}
		if( !$(this).find('textarea[name*="[detail_size]"]').val() ){
			$(this).find('textarea[name*="[detail_size]"]').addClass("error-tb-detail");
			has_error = has_error + 1;
		}else{
			$(this).find('textarea[name*="[detail_size]"]').removeClass("error-tb-detail");
		}
	});
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
	<?php if( (isset($_GET['op_export_id'])) && !isset($_GET['edit'])){ ?>
        $('form').find('input').each(function(){ $(this).prop("disabled", true); });
        $('form').find('input').each(function(){ $(this).removeAttr("placeholder"); });
        $('form').find('select').each(function(){ $(this).prop("disabled", true); });
        $('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
        $('#<?= yii\bootstrap\Html::getInputId($model, 'pegawai_mutasi') ?>').attr('disabled','');
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'departure_estimated_date') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'arrival_estimated_date') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'shipment_time') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('#btn-save').attr('disabled','');
        $('#btn-print').removeAttr('disabled');
        $('.add-more').remove();
	<?php }else{ ?>
        $("#<?= yii\bootstrap\Html::getInputId($model, "tanggal") ?>").prop("disabled", false);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "nomor_kontrak") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "port_of_loading") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "vessel") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "mother_vessel") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "departure_estimated_date") ?>").prop("disabled", false);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'departure_estimated_date') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "final_destination") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "arrival_estimated_date") ?>").prop("disabled", false);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'arrival_estimated_date') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "shipment_time") ?>").prop("disabled", false);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'shipment_time') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "static_product_code") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "goods_description") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "payment_method") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "term_of_price") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "origin") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "svlk_no") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "vlegal_no") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "harvesting_area") ?>").prop("disabled", false);
		$("#<?= yii\bootstrap\Html::getInputId($model, "hs_code") ?>").prop("disabled", false);
		$('#btn-save').removeAttr('disabled');
		$('#btn-print').attr('disabled');
		<?php if($allowedit==true){ ?>
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").prop("disabled", false);
			$("#<?= yii\bootstrap\Html::getInputId($model, "notify_party") ?>").prop("disabled", false);
		<?php } ?>
	<?php } ?>
}

function getItems(op_export_id){
	<?php if(isset($_GET['edit'])){ ?>
		var tipe = 'input';
	<?php }else{ ?>
		var tipe = 'view';
	<?php } ?>
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/opexport/getItems']); ?>',
		type   : 'POST',
		data   : {op_export_id:op_export_id, tipe:tipe},
		success: function (data) {
			if(data.html){
				$('#table-detail > tbody').html(data.html);
				if(data.qty){
					$("#table-qty > tbody").html(data.qty);
				}
			}
            if(data.attch){
				$(data.attch).each(function(i,val){
					var asd = (i==0)?"":i;
					var src = "<?= Yii::$app->urlManager->baseUrl ?>/uploads/exm/op/"+val.file_name; 
                    $(".field-tattachment-file"+asd).parents(".col-md-2").removeClass("hidden");
                    if(edit){
                        $(".field-tattachment-file"+asd).find(".btn-file").addClass("hidden");
                        $(".field-tattachment-file"+asd).find(".fileinput.fileinput-new").append("<a class='btn btn-xs btn-outline red-flamingo' onclick='deleteAttch("+val.attachment_id+",\""+asd+"\");'><i class='fa fa-trash-o'></i> Hapus</a>");
                    }
                    if (val.file_type.match(/image.*/)) {
                        $(".field-tattachment-file"+asd).find("img").attr("src",src);
                        $(".field-tattachment-file"+asd).find("img").wrap("<a class='attch-pict-"+asd+"' href='"+src+"'></a>");
                        $(".attch-pict-"+asd).magnificPopup({
                            type: 'image'
                        });
                    }else{
                        $(".field-tattachment-file"+asd).find("img").parent().attr("style","vertical-align: middle; line-height: 0.5;");
                        $(".field-tattachment-file"+asd).find("img").parent().html("<b>File "+val.file_ext+"</b><br><br><br><a style='font-size:1rem; line-height:1;' href='"+src+"'>"+val.file_name+"</a>");
                    }
				});
			}
			<?php if((isset($_GET['op_export_id'])) && (!isset($_GET['edit']))){ ?>
				$('#table-detail').find("textarea").prop("disabled", true);
				$('#table-detail').find("select").prop("disabled", true);
				$('#table-detail').find("input").prop("disabled", true);
				$("#place-addProduk").css("display", "none");
				$('#table-detail').find(".red-flamingo").css("display", "none");
			<?php }else{ ?>
				$('#table-detail').find("textarea").prop("disabled", false);
				$('#table-detail').find("select").prop("disabled", false);
				$('#table-detail').find("input").not("input[name*='[detail_subtotal]'], input[name*='total_m3'], input[name*='total_price']").prop("disabled", false);
				$("#place-addProduk").css("display", "");
				$('#table-detail').find(".red-flamingo").css("display", "");
			<?php } ?>
			reordertable('#table-detail')
			total();
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/exim/opexport/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function printOP(id){
	window.open("<?= yii\helpers\Url::toRoute('/exim/opexport/printOP') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
function cancelTransaksi(op_export_id){
	openModal('<?php echo \yii\helpers\Url::toRoute(['/exim/opexport/cancelTransaksi']) ?>?id='+op_export_id,'modal-transaksi');
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/exim/opexport/index','op_export_id'=>'']); ?>'+id+'&edit=1');
}
function addAttch(){
	$("#place-attch .col-md-2.hidden:first").removeClass('hidden');
}
function deleteAttch(attachment_id,fileno){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/deleteAttch']) ?>?id='+attachment_id+'&fileno='+fileno,'modal-delete-record');
}
function setNormalPickAttch(fileno){
	if(!fileno){
		fileno = "";
	}
	$(".field-tattachment-file"+fileno).find(".btn-file").removeClass("hidden");
	$(".field-tattachment-file"+fileno).find(".fileinput.fileinput-new > a.red-flamingo").remove();
	$(".field-tattachment-file"+fileno).find("img").attr("src","<?= Yii::$app->view->theme->baseUrl; ?>/cis/img/no-image.png");
}
</script>