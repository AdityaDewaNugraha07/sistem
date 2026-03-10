<?php
/* @var $this yii\web\View */
$this->title = 'Keberangkatan Tongkang';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Keberangkatan Tongkang'); ?></h1>
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
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Keberangkatan'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Keberangkatan'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= yii\helpers\Html::activeHiddenInput($model, "keberangkatan_tongkang_id") ?>
										<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Kode Keberangkatan"); ?>
										<?= $form->field($model, 'nama')->textInput()->label("Nama Tongkang"); ?>
										<?= $form->field($model, 'eta',[
												'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
															 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
															 {error}</div>'])->textInput(['readonly'=>'readonly'])->label("Estimasi Kedatangan"); ?>				
										<?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'total_loglist')->textInput(['disabled'=>true])->label("Jumlah Loglist"); ?>
										<?= $form->field($model, 'total_batang')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'total_m3')->textInput(['disabled'=>true])->label("Total Volume"); ?>
                                    </div>
                                </div>
                                <div class="row ">
									<br><hr>
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Data Loglist'); ?></h4>
                                    </div>
                                </div>
								<div class="row" style="">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
													<tr>
														<th style="width: 60px; font-size: 1.1rem;"><?= Yii::t('app', 'No'); ?></th>
														<th style="width: 240px; font-size: 1.1rem;"><?= Yii::t('app', 'Kode Loglist'); ?></th>
                                                        <th style="width: 160px; font-size: 1.1rem;"><?= Yii::t('app', 'Lokasi Muat'); ?></th>
                                                        <th style="width: 100px; font-size: 1.1rem;"><?= Yii::t('app', 'Tanggal Muat'); ?></th>
                                                        <th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Qty Pcs'); ?></th>
                                                        <th style="width: 100px; font-size: 1.1rem;"><?= Yii::t('app', 'Volume'); ?></th>
                                                        <th style="font-size: 1.1rem;"><?= Yii::t('app', 'Keterangan'); ?></th>
														<th style="width: 40px;"></th>
													</tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
												<tfoot>
													<tr>
														<td colspan="6">
															<?php if(isset($_GET['keberangkatan_tongkang_id'])){ ?>
																<a class="btn btn-xs grey btn-outline" id="btn-add-item" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Item'); ?></a>
															<?php }else{ ?>
																<a class="btn btn-xs blue-hoki btn-outline" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Item'); ?></a>
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
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save()']); ?>
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
if(isset($_GET['keberangkatan_tongkang_id'])){
    $pagemode = "afterSave();";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode;
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Keberangkatan Tongkang'))."');
", yii\web\View::POS_READY); ?>
<script>
function addItem(){
	var alreadyitem = [];
	$("#table-detail > tbody > tr").each(function(i){
		alreadyitem[i] = $(this).find("select[name*='[loglist_id]']").val();
	});
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/keberangkatantongkang/addItem']); ?>',
        type   : 'POST',
        data   : {alreadyitem:alreadyitem},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail tbody').fadeIn(300,function(){
                    $(this).find('select[name*="[loglist_id]"]').select2({
                        allowClear: !0,
                        placeholder: 'Kode Loglist',
					});
					$(this).find('.select2-selection').css('font-size','1.2rem');
					$(this).find('.select2-selection').css('padding-left','5px');
					$(this).find(".tooltips").tooltip({ delay: 50 });
                    reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function setItem(ele){
	var loglist_id = $(ele).parents('tr').find('select[name*="[loglist_id]"]').val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/keberangkatantongkang/setItem']); ?>',
        type   : 'POST',
        data   : {loglist_id:loglist_id},
        success: function (data) {
            if(data){
				$(ele).parents("tr").find('input[name*="[lokasi_muat]"]').val(data.lokasi_muat);
				$(ele).parents("tr").find('input[name*="[qty_batang]"]').val(formatNumberForUser(data.qty_batang));
				$(ele).parents("tr").find('input[name*="[qty_m3]"]').val(formatNumberForUser(data.qty_m3));
				setTotal();
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function setTotal(){
	var total_batang = 0; var total_m3 = 0; 
	$("#table-detail > tbody > tr").each(function(i){
		total_batang += unformatNumber( $(this).find('input[name*="[qty_batang]"]').val() );
		total_m3 += unformatNumber( $(this).find('input[name*="[qty_m3]"]').val() );
		if((i+1)==$("#table-detail > tbody > tr").length){
			$("#<?= yii\helpers\Html::getInputId($model, "total_loglist") ?>").val( $("#table-detail > tbody > tr").length );
			$("#<?= yii\helpers\Html::getInputId($model, "total_batang") ?>").val( total_batang );
			$("#<?= yii\helpers\Html::getInputId($model, "total_m3") ?>").val( total_m3 );
		}
	});
}
function save(){
    var $form = $('#form-transaksi');
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
function validatingDetail(ele){
    var has_error = 0;
	$('#table-detail tbody tr').each(function(){
		var field1 = $(this).find('select[name*="[loglist_id]"]');
		var field2 = $(this).find('input[name*="[lokasi_muat]"]');
		var field3 = $(this).find('input[name*="[tanggal_muat]"]');
		var field4 = $(this).find('input[name*="[qty_batang]"]');
		var field5 = $(this).find('input[name*="[qty_m3]"]');
		if(!field1.val()){
			$(this).find('select[name*="[loglist_id]"]').parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).find('select[name*="[loglist_id]"]').parents('td').removeClass('error-tb-detail');
		}
		if(!field2.val()){
			$(this).find('input[name*="[lokasi_muat]"]').parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).find('input[name*="[lokasi_muat]"]').parents('td').removeClass('error-tb-detail');
		}
		if(!field3.val()){
			$(this).find('input[name*="[tanggal_muat]"]').parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).find('input[name*="[tanggal_muat]"]').parents('td').removeClass('error-tb-detail');
		}
		if(!field4.val()){
			$(this).find('input[name*="[qty_batang]"]').parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).find('input[name*="[qty_batang]"]').parents('td').removeClass('error-tb-detail');
		}
		if(!field5.val()){
			$(this).find('input[name*="[qty_m3]"]').parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).find('input[name*="[qty_m3]"]').parents('td').removeClass('error-tb-detail');
		}
	});
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
function openLoglist(ele){
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/keberangkatantongkang/openLoglist']); ?>?tr_seq='+tr_seq;
	$(".modals-place-3-min").load(url, function() {
		$("#modal-loglist .modal-dialog").css('width','90%');
		$("#modal-loglist").modal('show');
		$("#modal-loglist").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pickLoglist(loglist_id,tr_seq){
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/keberangkatantongkang/pickLoglist']); ?>',
        type   : 'POST',
        data   : {loglist_id:loglist_id},
        success: function (data) {
			if(data){
				var already = [];
				$('#table-detail > tbody > tr').each(function(){
					var loglist_id = $(this).find('select[name*="[loglist_id]"]');
					if( loglist_id.val() ){
						already.push(loglist_id.val());
					}
				});
				if( $.inArray(  data.loglist_id.toString(), already ) != -1 ){ // Jika ada yang sama
					cisAlert("Item ini sudah dipilih di list");
					return false;
				}else{
					$("#modal-loglist").find('button.fa-close').trigger('click');
					$("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[loglist_id]']").val(data.loglist_id).trigger('change');;
				}
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function afterSave(id){
    getItems();
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tkeberangkatantongkang-eta').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if(isset($_GET['edit'])){ ?>
		$('#tkeberangkatantongkang-eta').siblings('.input-group-btn').find('button').prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "nama") ?>").prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "keterangan") ?>").prop('disabled', false);
		$('#btn-save').removeAttr('disabled');
	<?php } ?>
}
function getItems(){
	var keberangkatan_tongkang_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'keberangkatan_tongkang_id') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/keberangkatantongkang/getItems']); ?>',
		type   : 'POST',
		data   : {keberangkatan_tongkang_id:keberangkatan_tongkang_id,edit:"<?= (isset($_GET['edit'])?$_GET['edit']:""); ?>"},
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
function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/keberangkatantongkang/DaftarAfterSave']) ?>','modal-aftersave','90%');
}
</script>