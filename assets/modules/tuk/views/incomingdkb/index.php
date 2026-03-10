<?php
/* @var $this yii\web\View */
$this->title = 'Incoming DKB';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Incoming DKB'); ?></h1>
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
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Incoming DKB'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= yii\helpers\Html::activeHiddenInput($model, "incoming_dkb_id") ?>
										<?php if(isset($_GET['incoming_dkb_id']) && !isset($_GET['edit'])){ ?>
											<?= yii\bootstrap\Html::activeHiddenInput($model, "loglist_id"); ?>
											<?= $form->field($model, 'kode_keberangkatan')->textInput()->label("Kode Loglist"); ?>
										<?php }else{ ?>
											<?= $form->field($model, 'loglist_id')->dropDownList(\app\models\TLoglist::getOptionListIncomingDKB(),['prompt'=>'','onchange'=>'setParent(); getItems();']) ?>
										<?php } ?>
										<?= $form->field($model, 'pihak1_perusahaan')->textInput(['disabled'=>true])->label("Suplier"); ?>
										<?= $form->field($model, "nomor_kontrak")->textInput(['disabled'=>true]); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, "lokasi_muat")->textInput(['disabled'=>true]); ?>
										<div class="form-group">
											<label class="col-md-4 control-label">Total Batang</label>
											<div class="col-md-2">
												<?= \yii\helpers\Html::activeTextInput($model, "total_batang",['disabled'=>true,'class'=>'form-control']) ?>
												<span class="help-block"></span>
											</div>
											<label class="col-md-2 control-label">Total M<sup>3</sup></label>
											<div class="col-md-3">
												<?= \yii\helpers\Html::activeTextInput($model, "total_m3",['disabled'=>true,'class'=>'form-control']) ?>
												<span class="help-block"></span>
											</div>
										</div>
                                    </div>
                                </div>
                                <div class="row ">
									<br><br><br><hr>
                                    <div class="col-md-4">
                                        <h4><?= Yii::t('app', 'Data Detail DKB'); ?></h4>
                                    </div>
                                    <div class="col-md-4 pull-right">
                                        <?= $form->field($model, 'kode_partai',['template'=>'<div class="form-group">{label}<div class="col-md-7">
																								<span class="input-group-btn" style="width: 80%">{input}</span>
																								<span class="input-group-btn" style="width: 20%">
																									<a class="btn hijau tooltips" data-original-title="Set Kode Partai" style="display:none;" id="btn-save-kodepartai" onclick="setButtonKelola();"><i class="fa fa-check"></i></a>
																									<a class="btn blue-hoki tooltips" data-original-title="Edit Kode Partai" style="display:none;" id="btn-edit-kodepartai" onclick="editPartai();"><i class="fa fa-edit"></i></a>
																								</span></div></div>'])
													->textInput(['style'=>'font-weight:600'])->label("Kode Partai"); ?>
                                    </div>
                                </div>
								<div class="row" style="margin-left: -40px; margin-right: -40px;">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2"><?= Yii::t('app', 'No'); ?></th>
                                                        <th colspan="3"><?= Yii::t('app', 'Nomor'); ?></th>
                                                        <th rowspan="2"><?= Yii::t('app', 'Kayu'); ?></th>
                                                        <th rowspan="2" style="width: 50px;"><?= Yii::t('app', 'P <sup>m</sup>'); ?></th>
                                                        <th rowspan="2" style="width: 50px;"><?= Yii::t('app', 'D <sup>cm</sup>'); ?></th>
                                                        <th rowspan="2" style="width: 60px;"><?= Yii::t('app', 'V <sup>m3</sup>'); ?></th>
                                                        <th rowspan="2" style="width: 90px;"><?= Yii::t('app', 'Reduksi'); ?></th>
                                                        <th rowspan="2" style="width: 40px;"><?= Yii::t('app', 'Pot'); ?></th>
                                                        <th rowspan="2" style="width: 90px;"><?= Yii::t('app', 'Asal Kayu'); ?></th>
                                                        <th rowspan="2" style="width: 90px; line-height: 1"><?= Yii::t('app', 'Lokasi<br>Bongkar'); ?></th>
                                                        <th rowspan="2" style="width: 60px;"><?= Yii::t('app', ''); ?></th>
                                                    </tr>
													<tr>
														<th style="width: 60px; font-size: 1.1rem;"><?= Yii::t('app', 'Grade'); ?></th>
														<th style="width: 220px; font-size: 1.1rem;"><?= Yii::t('app', 'Barcode'); ?></th>
                                                        <th style="width: 100px; font-size: 1.1rem;"><?= Yii::t('app', 'Batang'); ?></th>
													</tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
												<tfoot>
													<tr>
														<td colspan="6">
															<div id="place-kelolaitem-aktif" style="display: none;">
																<a class="btn btn-xs blue-hoki btn-outline" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Item'); ?></a>
																<a class="btn btn-xs green-seagreen btn-outline" id="btn-import" onclick="importItem();" style="margin-top: 10px;"><i class="fa fa-download"></i> <?= Yii::t('app', 'Import xls'); ?></a>
																<a class="btn btn-xs red-flamingo btn-outline" id="btn-delete-all" onclick="deleteAll();" style="margin-top: 10px;"><i class="fa fa-trash-o"></i> <?= Yii::t('app', 'Delete All'); ?></a>
															</div>
															<div id="place-kelolaitem-nonaktif" style="display: none;">
																<a class="btn btn-xs grey btn-outline" id="btn-add-item" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Item'); ?></a>
																<a class="btn btn-xs grey btn-outline" id="btn-import" style="margin-top: 10px;"><i class="fa fa-download"></i> <?= Yii::t('app', 'Import xls'); ?></a>
																<a class="btn btn-xs grey btn-outline" id="btn-delete-all" style="margin-top: 10px;"><i class="fa fa-trash-o"></i> <?= Yii::t('app', 'Delete All'); ?></a>
															</div>
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
<?php $this->registerJs(" 
	formconfig();
	$(this).find('select[name*=\"[loglist_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Loglist',
		width: null
	});
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Incoming DKB'))."');
", yii\web\View::POS_READY); ?>
<script>
function importItem(loglist_id,kode_partai){
    var loglist_id = $("#<?= yii\helpers\Html::getInputId($model, "loglist_id") ?>").val();
    var kode_partai = $("#<?= yii\helpers\Html::getInputId($model, "kode_partai") ?>").val();
	openModal('<?= \yii\helpers\Url::toRoute('/tuk/incomingdkb/importexcel') ?>?loglist_id='+loglist_id+'&kode_partai='+kode_partai,'modal-importexcel','60%');
}

function setParent(){
	var loglist_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'loglist_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/tuk/incomingdkb/setParent']); ?>',
		type   : 'POST',
		data   : {loglist_id:loglist_id},
		success: function (data) {
			$('#<?= yii\bootstrap\Html::getInputId($model, 'pihak1_perusahaan') ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, 'nomor_kontrak') ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, 'lokasi_muat') ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, 'total_batang') ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, 'total_m3') ?>').val("");
			if(data){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'pihak1_perusahaan') ?>').val(data.pihak1_perusahaan);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'nomor_kontrak') ?>').val(data.nomor_kontrak);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'lokasi_muat') ?>').val(data.lokasi_muat);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'total_batang') ?>').val(data.total_batang);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'total_m3') ?>').val(data.total_m3);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getItems(){
	var kode_partai = $('#<?= yii\bootstrap\Html::getInputId($model, 'kode_partai') ?>').val();
	var loglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'loglist_id') ?>').val();
    $('#table-detail').addClass('animation-loading');
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/tuk/incomingdkb/getItems']); ?>',
		type   : 'POST',
		data   : {loglist_id:loglist_id,kode_partai:kode_partai},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			$("#<?= yii\helpers\Html::getInputId($model, "kode_partai") ?>").val(data.kode_partai);

			setButtonKelola();
			setTotal();
			reordertable('#table-detail');
            $('#table-detail').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setButtonKelola(){
	var loglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'loglist_id') ?>').val();
	var kode_partai = $("#<?= yii\helpers\Html::getInputId($model, "kode_partai") ?>").val();
	if( loglist_id ){
		if( kode_partai ){
			$("#btn-save-kodepartai").attr("style","display:none;");
			$("#btn-edit-kodepartai").attr("style","display:;");
			$("#<?= yii\helpers\Html::getInputId($model, "kode_partai") ?>").prop("disabled",true);
			$("#place-kelolaitem-aktif").attr("style","display:;");
			$("#place-kelolaitem-nonaktif").attr("style","display:none;");
		}else{
			$("#btn-save-kodepartai").attr("style","display:;");
			$("#btn-edit-kodepartai").attr("style","display:none;");
			$("#<?= yii\helpers\Html::getInputId($model, "kode_partai") ?>").prop("disabled",false);
			$("#place-kelolaitem-aktif").attr("style","display:none;");
			$("#place-kelolaitem-nonaktif").attr("style","display:;");
		}
	}
}

function editPartai(){
	var loglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'loglist_id') ?>').val();
	var kode_partai = $('#<?= yii\bootstrap\Html::getInputId($model, 'kode_partai') ?>').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/tuk/incomingdkb/editPartai','loglist_id'=>''])?>'+loglist_id+'&kode_partai='+kode_partai,'modal-master-edit');
}

function addItem(){
	var disabled =  $("#table-detail > tbody > tr:last").find('input:disabled, select:disabled').removeAttr('disabled');
	var last_tr =  $("#table-detail > tbody > tr:last").find("input,select").serialize();
	var loglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'loglist_id') ?>').val();
	var kode_partai = $("#<?= \yii\helpers\Html::getInputId($model, "kode_partai") ?>").val();
	disabled.attr('disabled','disabled');
	var incoming_dkb_id = $("#<?= yii\helpers\Html::getInputId($model, "incoming_dkb_id") ?>").val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/tuk/incomingdkb/addItem']); ?>',
        type   : 'POST',
        data   : {last_tr:last_tr,incoming_dkb_id:incoming_dkb_id,loglist_id:loglist_id,kode_partai:kode_partai},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                    reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function saveItem(ele){
    var $form = $('#form-pengeluaran-kas');
    if(validatingDetail(ele)){
		$(ele).parents('tr').find('input[name*="[panjang]"]').val( unformatNumber($(ele).parents('tr').find('input[name*="[panjang]"]').val()) );
		$(ele).parents('tr').find('input[name*="[diameter]"]').val( unformatNumber($(ele).parents('tr').find('input[name*="[diameter]"]').val()) );
		$(ele).parents('tr').addClass('animation-loading');
		$.ajax({
			url    : '<?php echo \yii\helpers\Url::toRoute(['/tuk/incomingdkb/savedkb']); ?>',
			type   : 'POST',
			data   : { formData: $(ele).parents('tr').find('input, textarea, select').serialize() },
			success: function (data) {
				$(ele).parents('tr').find('input[name*="[panjang]"]').val( formatNumberForUser($(ele).parents('tr').find('input[name*="[panjang]"]').val()) );
				$(ele).parents('tr').find('input[name*="[diameter]"]').val( formatNumberForUser($(ele).parents('tr').find('input[name*="[diameter]"]').val()) );
				if(data.status){
					$(ele).parents('tr').find('input[name*="[incoming_dkb_id]"]').val( data.incoming_dkb_id );
					$(ele).parents('tr').find('input[name*="[no_barcode]"]').addClass('font-blue-steel');
					$(ele).parents('tr').find('input[name*="[no_barcode]"]').val( data.no_barcode );
					$(ele).parents('tr').find('input, textarea, select').attr('disabled','disabled');
					$(ele).parents('tr').find('#place-editbtn').attr('style','display:');
					$(ele).parents('tr').find('#place-cancelbtn').attr('style','display:none');
					$(ele).parents('tr').find('#place-savebtn').attr('style','display:none');
					$(ele).parents('tr').find('#place-deletebtn').attr('style','display:');
				}else{
					cisAlert(data.message);
				}
                $(ele).parents('tr').removeClass('animation-loading');
				reordertable('#table-detail');
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
	}
    return false;
}

function validatingDetail(ele){
    var has_error = 0;
	var field1 = $(ele).parents('tr').find('input[name*="[no_grade]"]');
	var field2 = $(ele).parents('tr').find('input[name*="[no_barcode]"]');
	var field3 = $(ele).parents('tr').find('input[name*="[no_btg]"]');
	var field4 = $(ele).parents('tr').find('select[name*="[kayu_id]"]');
	var field5 = $(ele).parents('tr').find('input[name*="[panjang]"]');
	var field6 = $(ele).parents('tr').find('input[name*="[diameter]"]');
	var field7 = $(ele).parents('tr').find('input[name*="[kondisi]"]');
	var field8 = $(ele).parents('tr').find('input[name*="[pot]"]');
	if(!field1.val()){
		$(ele).parents('tr').find('input[name*="[no_grade]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[no_grade]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field2.val()){
		$(ele).parents('tr').find('input[name*="[no_barcode]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[no_barcode]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field3.val()){
		$(ele).parents('tr').find('input[name*="[no_btg]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[no_btg]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field4.val()){
		$(ele).parents('tr').find('select[name*="[kayu_id]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('select[name*="[kayu_id]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field5.val()){
		$(ele).parents('tr').find('input[name*="[panjang]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[panjang]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field6.val()){
		$(ele).parents('tr').find('input[name*="[diameter]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[diameter]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field7.val()){
		$(ele).parents('tr').find('input[name*="[kondisi]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[kondisi]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field8.val()){
		$(ele).parents('tr').find('input[name*="[pot]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[pot]"]').parents('td').removeClass('error-tb-detail');
	}
    if(has_error === 0){
        return true;
    }
    return false;
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
		setTotal();
    });
}

function setTotal(){
	var total_volume = unformatNumber( $("#<?= \yii\helpers\Html::getInputId($model, 'total_volume') ?>").val() );
	var total_harga = unformatNumber( $("#<?= \yii\helpers\Html::getInputId($model, 'total_harga') ?>").val() );
	var total_dp = unformatNumber( $("#<?= \yii\helpers\Html::getInputId($model, 'total_dp') ?>").val() );
	var total_bayar = unformatNumber( $("#<?= \yii\helpers\Html::getInputId($model, 'total_bayar') ?>").val() );
	total_bayar = total_harga - total_dp; 
	$("#<?= \yii\helpers\Html::getInputId($model, 'total_bayar') ?>").val( formatNumberForUser(total_bayar) );
}

function edit(ele){
	$(ele).parents('tr').find('input, select').removeAttr('disabled');
	$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
	$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
}
function deleteItem(ele){
	var incoming_dkb_id = $(ele).parents("tr").find("input[name*='[incoming_dkb_id]']").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/tuk/incomingdkb/deleteItem','id'=>''])?>'+incoming_dkb_id,'modal-delete-record');
}
function deleteAll(){
	var loglist_id = $("#<?= \yii\helpers\Html::getInputId($model, 'loglist_id') ?>").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/tuk/incomingdkb/deleteAll','id'=>''])?>'+loglist_id,'modal-delete-record');
}
</script>