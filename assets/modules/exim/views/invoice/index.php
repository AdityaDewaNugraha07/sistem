<?php
/* @var $this yii\web\View */
$this->title = 'Commercial Invoice';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
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
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<span class="pull-right">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Invoice Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4> Commercial Invoice </h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?php if(isset($_GET['edit'])){
											$onchange_status_inv = "";
										}else{
											$onchange_status_inv = "setDropdownPackinglist();";
										} ?>
										<?= $form->field($model, 'status_inv')->inline(true)->radioList(['FINAL'=>'FINAL','PROFORMA'=>'PROFORMA'],['style'=>'margin-left:20px','onchange'=>$onchange_status_inv]); ?>
                                        <?= $form->field($model, 'piutang_active',['template' => '<label class="col-md-4 control-label">Close Invoice</label><div class="mt-checkbox-list col-md-7" style="margin-bottom:15px;"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])->checkbox([],false)->label(Yii::t('app', 'Close Invoice')); ?>
										<?php if(!isset($_GET['invoice_id'])){ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Pilih Packinglist'); ?></label>
												<div class="col-md-8">
													<span class="input-group-btn" style="width: 100%" id="place-option">
														<?= \yii\bootstrap\Html::activeDropDownList($model, 'packinglist_id', [],['class'=>'form-control select2','prompt'=>'','onchange'=>'setParent()','style'=>'width:100%;']); ?>
													</span>
													<span class="input-group-btn">
														<a class="btn btn-icon-only btn-default tooltips" onclick="openPackinglist();" data-original-title="Daftar Packinglist" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
													</span>
												</div>
											</div>
										<?php }else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Invoice No.'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'nomor', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->nomor ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'invoice_id'); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'op_export_id'); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'cust_id'); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'jenis_produk'); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'nomor'); ?>
										<?= $form->field($model, 'shiper')->textarea([]); ?>
										<div class="form-group" id="place-buyer">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Shipment To'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px;">
												<?= \yii\bootstrap\Html::activeTextarea($model, 'shipto', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
											</div>
										</div>
										<div class="form-group" id="place-applicant" style="display: none;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Applicant'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px;">
												<?= \yii\bootstrap\Html::activeTextarea($model, 'applicant', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
											</div>
										</div>
										<div class="form-group" id="place-notify_party" style="display: none;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Notify Party'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px;">
												<?= \yii\bootstrap\Html::activeTextarea($model, 'notify_party', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
											</div>
										</div>
										<?= $form->field($model, 'port_of_loading')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'etd')->textInput(['disabled'=>'disabled'])->label("ETD"); ?>
										<?= $form->field($model, 'final_destination')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'eta')->textInput(['disabled'=>'disabled'])->label("ETA"); ?>
										<?php // echo $form->field($model, 'harvesting_area')->textarea(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'static_product_code')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'hs_code')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'svlk_no')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'vlegal_no')->textInput(['disabled'=>'disabled']); ?>
									</div>
									<div class="col-md-6">
										<?= $form->field($model, 'tanggal',[
																	'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'nomor_kontrak')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'vessel')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'container_kode_seal_no')->textarea(['disabled'=>'disabled'])->label("Container / Seal"); ?>
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Payment Method'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px;">
												<span class="input-group-btn" style="width: 30%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'payment_method', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
												</span>
												<span class="input-group-btn" style="width: 70%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'payment_method_reff', ['class'=>'form-control','style'=>'width:100%','placeholder'=>'Reff. Number']) ?>
												</span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Term of Price'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px;">
												<span class="input-group-btn" style="width: 75%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'term_of_price', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
												</span>
												<span class="input-group-btn" style="width: 25%">
													<?= \yii\bootstrap\Html::activeDropDownList($model, 'mata_uang', \app\models\MDefaultValue::getOptionListLabelValue('mata-uang'),['class'=>'form-control','disabled'=>'disabled']) ?>
												</span>
											</div>
										</div>
										<?= $form->field($model, 'goods_description')->textarea(['disabled'=>'disabled'])->label("Goods Description"); ?>
										<?= $form->field($model, 'disetujui')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control select2','prompt'=>'','data-placeholder'=>'Ketik Nama Pegawai']); ?>
										<?= $form->field($model, 'notes')->textarea()->label("Note"); ?>
                                        <div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'FOB'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px;">
												<span class="input-group-btn" style="width: 60%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'fob', ['class'=>'form-control float','style'=>'width:100%','placeholder'=>'USD']) ?>
												</span>
												<span class="input-group-btn" style="width: 10%">
                                                    <?= $form->field($model, 'fob_preview',['template' => '<div class="mt-checkbox-list col-md-7" style="margin-left: 15px; margin-right: 20px;"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])->checkbox([],false)->label(Yii::t('app', 'Piutang Aktif')); ?>
												</span>
												<span class="input-group-addon" style="width: 10%; width: 10%;  padding: 0; border: 0; background-color:transparent;">
                                                    Priveiw Print
												</span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'PEB No. / Date'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px;">
												<span class="input-group-btn" style="width: 55%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'peb_no', ['class'=>'form-control','style'=>'width:100%','placeholder'=>'Nomor PEB']) ?>
												</span>
												<span class="input-group-btn" style="width: 5%"></span>
												<span class="input-group-btn" style="width: 35%">
													<?= $form->field($model, 'peb_tanggal',[
																	'template'=>'<div class="input-group date date-picker bs-datetime">{input} <span class="input-group-addon">
																				<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div>'])->textInput(['readonly'=>'readonly','placeholder'=>'Tanggal PEB']); ?>
												</span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'B/L No. / Date'); ?></label>
											<div class="col-md-8" style="padding-bottom: 5px;">
												<span class="input-group-btn" style="width: 55%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'bl_no', ['class'=>'form-control','style'=>'width:100%','placeholder'=>'Nomor B/L']) ?>
												</span>
												<span class="input-group-btn" style="width: 5%"></span>
												<span class="input-group-btn" style="width: 35%">
													<?= $form->field($model, 'bl_tanggal',[
																	'template'=>'<div class="input-group date date-picker bs-datetime">{input} <span class="input-group-addon">
																				<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div>'])->textInput(['readonly'=>'readonly','placeholder'=>'Tanggal B/L']); ?>
												</span>
											</div>
										</div>
										<?= $form->field($model, "penerbit_bl_id")->dropDownList(\app\models\MPenerbitBl::getOptionList(),["class"=>'form-control select2','prompt'=>'']) ?>
										<?= $form->field($model, 'marks')->textarea()->label("Shipping Instruction Marks"); ?>
                                        <?= $form->field($model, 'payment_date_estimate',[
																	'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly','placeholder'=>'Tanggal Rencana Bayar']); ?>
										<?php if((isset($_GET['invoice_id'])) && (!isset($_GET['edit']))){ 
												echo $form->field($model, 'diff_size_diff_price',['template' => '{label}<div class="mt-checkbox-list col-md-8"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>','options'=>['style'=>'line-height:0.9']])->checkbox(['onchange'=>'setDifferentPrice()'],false)->label(Yii::t('app', 'Un-Grouping')); 												
												echo $form->field($model, 'grouping_qty',['template' => '{label}<div class="mt-checkbox-list col-md-8"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>','options'=>['style'=>'line-height:0.9']])->checkbox(['onchange'=>'setGroupingQty()'],false)->label(Yii::t('app', 'Grouping Qty')); 												
										} ?>
									</div>
								</div><br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?= Yii::t('app', 'Detail Product'); ?></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th rowspan="2" style="font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Nama Produk'); ?></th>
														<th rowspan="2" style="width: 100px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Grade'); ?></th>
														<th rowspan="2" style="width: 200px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Dimensi'); ?></th>
														<th colspan="3" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty'); ?></th>
														<th rowspan="2" style="width: 100px; line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Price / m<sup>3</sup>'); ?></th>
														<th rowspan="2" style="width: 100px; line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Subtotal'); ?></th>
													</tr>
													<tr>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px;"><?= Yii::t('app', 'Palet'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 90px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
													</tr>
												</thead>
												
												<tbody>
													
												</tbody>
												
												<tfoot>
													<tr>
														<td></td>
														<td class="text-align-right" style="padding-top: 15px;">Potongan</td>
														<td colspan="6" class="text-align-right"><?= \yii\bootstrap\Html::activeTextInput($model, 'keterangan_potongan', ['class'=>'form-control text-align-left','style'=>'padding: 2px; padding-left: 5px; width:100%','placeholder'=>'Input Keterangan Potongan']) ?></td>
														<td>
															<?= \yii\bootstrap\Html::activeTextInput($model, 'total_potongan', ['class'=>'form-control text-align-right float','style'=>'padding: 2px; width:100%','placeholder'=>'']) ?>
														</td>
													</tr>
													<tr>
														<td colspan="4" class="text-align-right font-red-flamingo"><b>
															Total Bayar &nbsp;
														</b></td>
														<td style="font-size: 1.2rem; line-height: 0.9; padding: 2px;">
															<?= yii\bootstrap\Html::activeTextInput($model, "total_palet",['class'=>'form-control float font-red-flamingo','disabled'=>'disabled','style'=>'font-weight:600; padding:2px;']); ?>
														</td>
														<td style="font-size: 1.2rem; line-height: 0.9; padding: 2px;">
															<?= yii\bootstrap\Html::activeTextInput($model, "total_pcs",['class'=>'form-control float font-red-flamingo','disabled'=>'disabled','style'=>'font-weight:600; padding:2px;']); ?>
														</td>
														<td style="font-size: 1.2rem; line-height: 0.9; padding: 2px;">
															<?= yii\bootstrap\Html::activeTextInput($model, "total_kubikasi",['class'=>'form-control float font-red-flamingo','disabled'=>'disabled','style'=>'font-weight:600; padding:2px;']); ?>
														</td>
														<td></td>
														<?php
														/*
														if (!empty($_GET['invoice_id'])) {
															$sql = "select total_bayar from t_invoice where invoice_id = ".$model->invoice_id."";
															$xxx = Yii::$app->db->createCommand($sql)->queryOne();
															echo $sql;
														?>
														<td style="font-size: 1.2rem; line-height: 0.9; padding: 2px;">
															<input type="text" class="form-control" name="total_bayar" id="total_bayar" value="">
														</td>
														<?php
														} else {
														*/
														//echo $model->total_bayar;
														?>
														<td style="font-size: 1.2rem; line-height: 0.9; padding: 2px;">
															<?= yii\bootstrap\Html::activeTextInput($model, "total_bayar",['class'=>'form-control float font-red-flamingo','disabled'=>'disabled','style'=>'font-weight:600; padding:2px;']); ?>
														</td>
														<?php
														//}
														?>
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
								<div class="btn-group dropup">
                                    <a class="btn blue btn-outline ciptana-spin-btn" id="btn-print" disabled="" style="border-bottom-right-radius: 4px; border-top-right-radius: 4px;" data-toggle="dropdown" href="javascript:void(0);"> Print Invoice</a>
									<ul class="dropdown-menu dropdown-menu-default">
										<li>
											<a href="javascript:;" onclick="printout('PRINT')">
												<i class="fa fa-print"></i> Print Out
											</a>
										</li>
										<li>
											<a href="javascript:;" onclick="printout('EXCEL')">
												<i class="icon-doc"></i> Excel
											</a>
										</li>
									</ul>
								</div>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print PEB'),['id'=>'btn-print2','class'=>'btn blue-steel btn-outline ciptana-spin-btn','onclick'=>"printoutpeb('PRINT')",'disabled'=>true]); ?>
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
if(isset($_GET['invoice_id']) && !isset($_GET['edit'])){
    $pagemode = "afterSave(".$_GET['invoice_id'].");";
}else if( isset($_GET['invoice_id']) && isset($_GET['edit']) ){
	$pagemode = "afterSave(".$_GET['invoice_id'].",".$_GET['edit'].");";
}else{
	$pagemode = "setDropdownPackinglist();";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	total_potongan();
	$('select[name*=\"[packinglist_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Packinglist',
	});
	$('select[name*=\"[penerbit_bl_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Penerbit B/L',
	});
", yii\web\View::POS_READY); ?>
<script>
function setDifferentPrice(){
	var invoice_id = $("#<?= yii\bootstrap\Html::getInputId($model, "invoice_id") ?>").val();
	var diff_size_diff_price = $("#<?= \yii\helpers\Html::getInputId($model, "diff_size_diff_price") ?>").is(":checked");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/invoice/updateDiffSizeDiffPrice']); ?>',
		type   : 'POST',
		data   : {invoice_id:invoice_id,diff_size_diff_price:diff_size_diff_price},
		success: function (data) {
			if(data){
				afterSave(invoice_id);
				if(diff_size_diff_price){
					$('.field-tinvoice-grouping_qty').show();
				}else{
					$('.field-tinvoice-grouping_qty').hide();
				}
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setGroupingQty(){
	var invoice_id = $("#<?= yii\bootstrap\Html::getInputId($model, "invoice_id") ?>").val();
	var grouping_qty = $("#<?= \yii\helpers\Html::getInputId($model, "grouping_qty") ?>").is(":checked");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/invoice/updateGroupingQty']); ?>',
		type   : 'POST',
		data   : {invoice_id:invoice_id,grouping_qty:grouping_qty},
		success: function (data) {
			if(data){
				afterSave(invoice_id);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setParent(){
	var packinglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/exim/invoice/setParent']); ?>',
        type   : 'POST',
        data   : {packinglist_id:packinglist_id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "op_export_id") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val('');
			$('#table-detail tbody').html("");
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_harga") ?>").val( 0 );
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_ppn") ?>").val( 0 );
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_bayar") ?>").val( 0 );
			$("#place-buyer").find("textarea").html("");
			$("#place-applicant").find("textarea").html("");
			$("#place-notify_party").find("textarea").html("");
			$("#<?= yii\bootstrap\Html::getInputId($model, "container_kode_seal_no") ?>").val("");
			if(data){
				$("#modal-master").find('button.fa-close').trigger('click');
				$("#<?= yii\bootstrap\Html::getInputId($model, "op_export_id") ?>").val(data.op_export_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>").val(data.packinglist_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val(data.cust_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val(data.jenis_produk);
				$("#<?= yii\bootstrap\Html::getInputId($model, "nomor") ?>").val(data.nomor);
				$("#<?= yii\bootstrap\Html::getInputId($model, "shiper") ?>").val(data.shiper);
				$("#<?= yii\bootstrap\Html::getInputId($model, "shipto") ?>").val(data.shipto);
				$("#place-buyer").find("textarea").html(data.shipment_to);
				$("#place-applicant").find("textarea").html(data.applicant);
				$("#place-notify_party").find("textarea").html(data.notify_party);
				$("#<?= yii\bootstrap\Html::getInputId($model, "port_of_loading") ?>").val(data.port_of_loading);
				$("#<?= yii\bootstrap\Html::getInputId($model, "etd") ?>").val(data.etd);
				$("#<?= yii\bootstrap\Html::getInputId($model, "final_destination") ?>").val(data.final_destination);
				$("#<?= yii\bootstrap\Html::getInputId($model, "eta") ?>").val(data.eta);
				$("#<?= yii\bootstrap\Html::getInputId($model, "harvesting_area") ?>").val(data.harvesting_area);
				$("#<?= yii\bootstrap\Html::getInputId($model, "static_product_code") ?>").val(data.static_product_code);
				$("#<?= yii\bootstrap\Html::getInputId($model, "nomor_kontrak") ?>").val(data.nomor_kontrak);
				$("#<?= yii\bootstrap\Html::getInputId($model, "hs_code") ?>").val(data.hs_code);
				$("#<?= yii\bootstrap\Html::getInputId($model, "svlk_no") ?>").val(data.svlk_no);
				$("#<?= yii\bootstrap\Html::getInputId($model, "vlegal_no") ?>").val(data.vlegal_no);
				$("#<?= yii\bootstrap\Html::getInputId($model, "vessel") ?>").val(data.vessel);
				$("#<?= yii\bootstrap\Html::getInputId($model, "container_kode_seal_no") ?>").val(data.container_kode_seal_no);
				$("#<?= yii\bootstrap\Html::getInputId($model, "payment_method") ?>").val(data.payment_method);
				$("#<?= yii\bootstrap\Html::getInputId($model, "term_of_price") ?>").val(data.term_of_price);
				$("#<?= yii\bootstrap\Html::getInputId($model, "mata_uang") ?>").val(data.mata_uang);
				$("#<?= yii\bootstrap\Html::getInputId($model, "goods_description") ?>").val(data.goods_description);
				$("#<?= yii\bootstrap\Html::getInputId($model, "notes") ?>").val(data.notes);
				$("#<?= yii\bootstrap\Html::getInputId($model, "fob") ?>").val(data.fob);
				getItems(packinglist_id);
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function openPackinglist(){
	if( $("input:radio[name*='[status_inv]']:checked").val() == "FINAL" ){
		var status_inv = "FINAL";
	}else{
		var status_inv = "PROFORMA";
	}
	var url = '<?= \yii\helpers\Url::toRoute(['/exim/invoice/openPackinglist','status_inv'=>'']); ?>'+status_inv;
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pick(packinglist_id,kode){
	$("#modal-master").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>").empty().append('<option value="'+packinglist_id+'">'+kode+'</option>').val(packinglist_id).trigger('change');
}

function getItems(packinglist_id){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/invoice/getItems']); ?>',
		type   : 'POST',
		data   : {packinglist_id:packinglist_id},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				formconfig();
			}
			setTimeout(function(){
				total();
			},500)
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function subTotal(){
	var jnsproduk = $("#<?= \yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
    var tgl = $("#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>").val();
	$("#table-detail tbody tr").each(function(i,v){
        var tr = $(this);
		var qty_kecil = unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
		var harga = unformatNumber( $(this).find('input[name*="[harga_jual]"]').val() );
		var kubikasi_display = unformatNumber( $(this).find('input[name*="[kubikasi_display]"]').val() );
        
		// var subtotal = Math.ceil( kubikasi_display * harga  * 100 ) / 100; // Pembulatan Keatas Lurr
        
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/exim/invoice/setSubtotal']); ?>',
            type   : 'POST',
            data   : {tgl:tgl,kubikasi_display:kubikasi_display,harga:harga},
            success: function (data) {
                var subtotal = formatNumberFixed2(data);
                var ppn = 0;
                if( $('#<?= yii\bootstrap\Html::getInputId($model, "cust_is_pkp") ?>').prop('checked') ){
                    ppn = subtotal * 0.1;
                }
                $(tr).find('input[name*="[ppn]"]').val( ppn );
                $(tr).find('input[name*="[subtotal]"]').val( subtotal );
                $(tr).find('input[name*="[subtotal_display]"]').val( formatNumberForUser(formatNumberFixed2(subtotal)) );

                if( $("#table-detail tbody tr").length == (i+1) ){
        			total();
                }
        
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
	});
}

function total_potongan() {
	$("#tinvoice-total_potongan").change(function() {
	var total_palet = 0;
	var total_pcs = 0;
	var total_kubikasi = 0;
	var total_harga = 0;
	var total_ppn = 0;
	var total_potongan = $("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val();
	$("#table-detail tbody tr").each(function(){
		total_palet += unformatNumber( $(this).find('input[name*="[qty_besar]"]').val() );
		total_pcs += unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
		total_kubikasi += unformatNumber( $(this).find('input[name*="[kubikasi_display]"]').val() );
		total_harga += unformatNumber( $(this).find('input[name*="[subtotal]"]').val() );
		total_ppn += unformatNumber( $(this).find('input[name*="[ppn]"]').val() );
	});
	var total_bayar = (total_harga + total_ppn) - total_potongan;
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_palet") ?>").val( formatNumberForUser(total_palet) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_pcs") ?>").val( formatNumberForUser(total_pcs) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_kubikasi") ?>").val( formatNumberForUser(total_kubikasi) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_harga") ?>").val( formatNumberForUser(total_harga) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_ppn") ?>").val( formatNumberForUser(total_ppn) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val( formatNumberForUser(total_potongan) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_bayar") ?>").val( formatNumberForUser( formatNumberFixed2(total_bayar) ) );	
	});

}

function total(){
	var total_palet = 0;
	var total_pcs = 0;
	var total_kubikasi = 0;
	var total_harga = 0;
	var total_ppn = 0;
	var total_potongan = 0;
	//if ($("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val( formatNumberForUser(total_potongan)) > 0) {
	//	var total_potongan = unformatNumber($("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val(total_potongan));
	//} else {
	//var total_potongan = 0;
	//}
	$("#table-detail tbody tr").each(function(){
		total_palet += unformatNumber( $(this).find('input[name*="[qty_besar]"]').val() );
		total_pcs += unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
		total_kubikasi += unformatNumber( $(this).find('input[name*="[kubikasi_display]"]').val() );
		total_harga += unformatNumber( $(this).find('input[name*="[subtotal]"]').val() );
		total_ppn += unformatNumber( $(this).find('input[name*="[ppn]"]').val() );
	});
	var total_potongan = $('#tinvoice-total_potongan').val();
	var total_bayar = (total_harga + total_ppn) - total_potongan;
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_palet") ?>").val( formatNumberForUser(total_palet) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_pcs") ?>").val( formatNumberForUser(total_pcs) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_kubikasi") ?>").val( formatNumberForUser(total_kubikasi) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_harga") ?>").val( formatNumberForUser(total_harga) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_ppn") ?>").val( formatNumberForUser(total_ppn) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val( formatNumberForUser(total_potongan) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_bayar") ?>").val( formatNumberForUser( formatNumberFixed2(total_bayar) ) );
}


function save(){
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").parents("td").removeClass("has-error");
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
            return false;
        }
		
		if(validatingDetail()){
			submitform($form);
        } else {
        	alert('Data tidak valid');
        }
    } else {
    	alert('Form tidak valid');
    }
    return false;
}

function validatingDetail($form){
	var has_error = 0;
	var potongan = unformatNumber( $("#<?= \yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val() );
	
	$(this).find('input[name*="[harga_jual]"]').parents('td').removeClass('error-tb-detail');
	
	/*
	if(potongan > 0){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").slideDown();
		if(!$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").val()){
			$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").parents("td").addClass("has-error");
			has_error = has_error + 1;
		}
	}else{
		$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").slideUp();
		$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").val("");
	}
	*/

	$("#table-detail tbody tr").each(function(){
		var field1 = $(this).find('input[name*="[harga_jual]"]');
		if(!field1.val()){
			$(this).find('input[name*="[harga_jual]"]').parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			if(field1.val() == '0'){
				$(this).find('input[name*="[harga_jual]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[harga_jual]"]').parents('td').removeClass('error-tb-detail');
			}
		}
	});
    
    if($("input[name*='piutang_active']").is(':checked')){
        var field2 = $('input[name*="[peb_tanggal]"]');
        var field3 = $('input[name*="[payment_date_estimate]"]');
        if(!field2.val()){
			$('input[name*="[peb_tanggal]"]').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$('input[name*="[peb_tanggal]"]').removeClass('error-tb-detail');
		}
        if(!field3.val()){
			$('input[name*="[payment_date_estimate]"]').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$('input[name*="[payment_date_estimate]"]').removeClass('error-tb-detail');
		}
    }
	
	if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id,edit){
	getItemsById(id,edit,function(){
		$('form').find('input').each(function(){ $(this).prop("disabled", true); });
		$('form').find('select').each(function(){ $(this).prop("disabled", true); });
		$('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
		$("input[name*='[diff_size_diff_price]']").prop("disabled", false);
		$("input[name*='[grouping_qty]']").prop("disabled", false);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'peb_tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'bl_tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
		$('#<?= yii\bootstrap\Html::getInputId($model, 'payment_date_estimate') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
		$('#btn-save').attr('disabled','');
		$('#btn-print').removeAttr('disabled');
		$('#btn-print2').removeAttr('disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>')
		<?php if(isset($_GET['edit'])){ ?>
			$('input[name*="piutang_active"]').prop("disabled", false);
			$('input[name*="fob_preview"]').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>').prop("disabled", false);
			$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "payment_method_reff") ?>').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "notes") ?>').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "fob") ?>').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "shiper") ?>').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "peb_no") ?>').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "bl_no") ?>').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "peb_tanggal") ?>').prop("disabled", false);
			$('#<?= yii\bootstrap\Html::getInputId($model, 'peb_tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "bl_tanggal") ?>').prop("disabled", false);
			$('#<?= yii\bootstrap\Html::getInputId($model, 'bl_tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "penerbit_bl_id") ?>').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "marks") ?>').prop("disabled", false);
            $('#<?= \yii\bootstrap\Html::getInputId($model, "payment_date_estimate") ?>').prop("disabled", false);
			$('#<?= yii\bootstrap\Html::getInputId($model, 'payment_date_estimate') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
			$('input[name*="[status_inv]"]').prop("disabled", false);
			$("#table-detail > tbody > tr").find("input[name*='[harga_jual]']").prop("disabled", false);
			$("#table-detail > tbody > tr").find("input[name*='[keterangan]']").prop("disabled", true);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "total_potongan") ?>').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>').prop("disabled", false);
			$('#<?= \yii\bootstrap\Html::getInputId($model, "total_bayar") ?>').prop("disabled", true);
			$('#btn-save').prop('disabled',false);
			$('#btn-print').prop('disabled',true);
			$('#btn-print2').prop('disabled',true);
		<?php } ?>
		subTotal();
        setTimeout(function(){
            total();
            total_potongan();
            if(!edit){ updateTotalBayar(id); } // update karna grouping checklist
        },1000);
	});
}

function updateTotalBayar(id){
	var total = unformatNumber( $("#<?= \yii\helpers\Html::getInputId($model, "total_bayar") ?>").val() );
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/invoice/updateTotalBayar']); ?>',
		type   : 'POST',
		data   : {id:id,total:total},
		success: function (data) {
			
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getItemsById(id,edit=null,callback=null){
	var diff_size_diff_price = $("#<?= \yii\helpers\Html::getInputId($model, "diff_size_diff_price") ?>").is(":checked");
	var grouping_qty = $("#<?= \yii\helpers\Html::getInputId($model, "grouping_qty") ?>").is(":checked");
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/invoice/getItemsById']); ?>',
		type   : 'POST',
		data   : {id:id,edit:edit,diff_size_diff_price:diff_size_diff_price,grouping_qty:grouping_qty},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				formconfig();
				if( callback ){ callback(); }
				if(diff_size_diff_price){
					$('.field-tinvoice-grouping_qty').show();
				}else{
					$('.field-tinvoice-grouping_qty').hide();
				}
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setDropdownPackinglist(){
	$("#place-option").addClass("animation-loading");
	if( $("input:radio[name*='[status_inv]']:checked").val() == "FINAL" ){
		var status_inv = "FINAL";
	}else{
		var status_inv = "PROFORMA";
	}
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/invoice/setDropdownPackinglist']); ?>',
		type   : 'POST',
		data   : {status_inv:status_inv},
		success: function (data) {
			if(data.html){
				$('#<?= \yii\helpers\Html::getInputId($model, "packinglist_id") ?>').html(data.html);
				$("#place-option").removeClass("animation-loading");
				setParent();
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/exim/invoice/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/exim/invoice/index','invoice_id'=>'']); ?>'+id+'&edit=1');
}

function printout(caraprint){
	var id = $('#<?= \yii\bootstrap\Html::getInputId($model, "invoice_id"); ?>').val();
	window.open("<?= yii\helpers\Url::toRoute('/exim/invoice/print') ?>?id="+id+"&caraprint="+caraprint,"",'location=_new, width=1200px, scrollbars=yes');
}
function printoutpeb(caraprint){
	var id = $('#<?= \yii\bootstrap\Html::getInputId($model, "invoice_id"); ?>').val();
	openModal('<?= \yii\helpers\Url::toRoute('/exim/invoice/konfirmsipeb') ?>?invoice_id='+id,'modal-konfirmsi','90%');
}
</script>