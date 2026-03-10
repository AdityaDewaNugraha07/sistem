<?php
/* @var $this yii\web\View */
$this->title = 'Incoming Pelabuhan';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Incoming Pelabuhan'); ?></h1>
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
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Incoming Pelabuhan'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Incoming Pelabuhan'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= yii\helpers\Html::activeHiddenInput($model, "incoming_pelabuhan_id") ?>
										<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Kode Incoming"); ?>
										<?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Incoming"); ?>				
										<?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                    <div class="col-md-6">
										<?php if(isset($_GET['incoming_pelabuhan_id']) && !isset($_GET['edit'])){ ?>
											<?= yii\bootstrap\Html::activeHiddenInput($model, "keberangkatan_tongkang_id"); ?>
											<?= $form->field($model, 'kode_keberangkatan')->textInput()->label("Kode Keberangkatan"); ?>
										<?php }else{ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Keberangkatan'); ?></label>
												<div class="col-md-7">
													<span class="input-group-btn" style="width: 100%">
														<?= \yii\bootstrap\Html::activeDropDownList($model, 'keberangkatan_tongkang_id', \app\models\TKeberangkatanTongkang::getOptionListIncoming(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setParent()','style'=>'width:100%;']); ?>
													</span>
													<span class="input-group-btn">
														<a class="btn btn-icon-only btn-default tooltips" onclick="openKeberangkatan();" data-original-title="Daftar Keberangkatan" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'tongkang')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'total_loglist')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'total_batang')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'total_m3')->textInput(['disabled'=>true]); ?>
                                    </div>
                                </div>
                                <div class="row ">
									<br><hr>
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Data Detail Loglist Dan DKB'); ?></h4>
                                    </div>
                                </div>
								<div class="row" style="">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
													<tr>
														<th style="width: 40px; font-size: 1.1rem;"><?= Yii::t('app', 'No'); ?></th>
														<th style="width: 240px; font-size: 1.1rem;"><?= Yii::t('app', 'Kode Loglist'); ?></th>
                                                        <th style="width: 120px; font-size: 1.1rem;"><?= Yii::t('app', 'Lokasi Muat'); ?></th>
                                                        <th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Tanggal Muat'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Qty<br>Batang'); ?></th>
                                                        <th style="width: 60px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Volume'); ?></th>
                                                        <th style="font-size: 1.1rem;"><?= Yii::t('app', 'Incoming DKB'); ?></th>
													</tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
												<tfoot>
													
												</tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'submitform()']); ?>
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
if(isset($_GET['incoming_pelabuhan_id'])){
    $pagemode = "afterSave();";
	if(isset($_GET['edit'])){
//		<option value=\"".$model->keberangkatan_tongkang_id."\">". $model->kode_keberangkatan ."</option>
		$pagemode .= "$('#". yii\bootstrap\Html::getInputId($model, "keberangkatan_tongkang_id")."').empty('').append('<option value=\"".$model->keberangkatan_tongkang_id."\">". $model->kode_keberangkatan ."</option>').val(".$model->keberangkatan_tongkang_id.").trigger('change')";
	}
}else{
	$pagemode = "";
}
//echo "<pre>";
//print_r($pagemode);
//exit;
?>
<?php $this->registerJs(" 
    $pagemode;
	formconfig();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Incoming Pelabuhan'))."');
	$(this).find('select[name*=\"[keberangkatan_tongkang_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Keberangkatan',
		width: null
	});
", yii\web\View::POS_READY); ?>
<script>
function setParent(){
	var incoming_pelabuhan_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'incoming_pelabuhan_id') ?>').val();
	var keberangkatan_tongkang_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'keberangkatan_tongkang_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/tuk/incomingpelabuhan/setParent']); ?>',
		type   : 'POST',
		data   : {keberangkatan_tongkang_id:keberangkatan_tongkang_id,incoming_pelabuhan_id:incoming_pelabuhan_id},
		success: function (data) {
			$('#<?= yii\bootstrap\Html::getInputId($model, 'tongkang') ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, 'total_loglist') ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, 'total_batang') ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, 'total_m3') ?>').val("");
			if(data.keberangkatan){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'tongkang') ?>').val(data.keberangkatan.nama);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'total_loglist') ?>').val(data.keberangkatan.total_loglist);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'total_batang') ?>').val(data.keberangkatan.total_batang);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'total_m3') ?>').val(data.keberangkatan.total_m3);
			}
			getItems();
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function openKeberangkatan(){
	var url = '<?= \yii\helpers\Url::toRoute(['/tuk/incomingpelabuhan/openKeberangkatan']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','80%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pick(keberangkatan_tongkang_id,kode){
	$("#modal-master").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "keberangkatan_tongkang_id") ?>").empty().append('<option value="'+keberangkatan_tongkang_id+'">'+kode+'</option>').val(keberangkatan_tongkang_id).trigger('change');
}

function afterSave(id){
    setParent();
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tincomingpelabuhan-tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if(isset($_GET['edit'])){ ?>
		$('#tincomingpelabuhan-tanggal').siblings('.input-group-btn').find('button').prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "keterangan") ?>").prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "keberangkatan_tongkang_id") ?>").prop('disabled', false);
		$('#btn-save').removeAttr('disabled');
	<?php } ?>
}

function getItems(){
	var keberangkatan_tongkang_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'keberangkatan_tongkang_id') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/tuk/incomingpelabuhan/getItems']); ?>',
		type   : 'POST',
		data   : {keberangkatan_tongkang_id:keberangkatan_tongkang_id},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			setTotal();
			reordertable('#table-detail');
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
			url    : '<?php echo \yii\helpers\Url::toRoute(['/tuk/incomingpelabuhan/savedkb']); ?>',
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
					$(ele).parents('tr').removeClass('animation-loading');
				}else{
					cisAlert(data.message);
				}
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

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/tuk/incomingpelabuhan/DaftarAfterSave']) ?>','modal-aftersave','90%');
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
    openModal('<?= \yii\helpers\Url::toRoute(['/tuk/incomingpelabuhan/deleteItem','id'=>''])?>'+incoming_dkb_id,'modal-delete-record');
}
function deleteAll(){
	var incoming_pelabuhan_id = $("#<?= \yii\helpers\Html::getInputId($model, 'incoming_pelabuhan_id') ?>").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/tuk/incomingpelabuhan/deleteAll','id'=>''])?>'+incoming_pelabuhan_id,'modal-delete-record');
}
</script>