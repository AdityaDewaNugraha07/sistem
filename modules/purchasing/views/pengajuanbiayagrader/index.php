<?php
/* @var $this yii\web\View */
$this->title = 'Pengajuan Biaya Grader';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Pengajuan Biaya Grader'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-pengajuanbiaya-greder',
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
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarPengajuanBiayaGrader()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Pengajuan Biaya'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Biaya Grader'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'biaya_grader_kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']); ?>
										<?= $form->field($model, 'biaya_grader_tgl',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?php if(isset($_GET['biaya_grader_id'])){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label">Status</label>
												<div class="col-md-7">
													<span class="label label-sm label-<?= ($model->status=='PAID')?"success":"warning" ?>"><?= $model->status ?></span>
												</div>
											</div>
										<?php } ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'biaya_grader_ket')->textarea(); ?>
                                        <?= $form->field($model, 'biaya_grader_jml')->textInput(['disabled'=>true,'class'=>'form-control money-format']); ?>
                                    </div>
                                </div>
                                <br><br><hr>
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Biaya Grader'); ?></h4>
                                    </div>
                                    <div class="col-md-7">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;" >No.</th>
                                                        <th ><?= Yii::t('app', 'Detail Pengajuan Biaya'); ?></th>
                                                        <th style="width: 30px;"><?= Yii::t('app', 'Cancel'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
												<tfoot>
<!--													<tr>
														<td style="text-align: right">&nbsp; <i>Total</i></td>
														<td style="padding-left: 8px; padding-right: 8px;">
															<?= yii\bootstrap\Html::textInput('total',0,['class'=>'form-control','style'=>'width:100%; font-style: bold;','readonly'=>'readonly','id'=>'total']); ?>
														</td>
														<td></td>
													</tr>-->
													<tr>
														<td colspan="6">
															<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah'); ?></a>
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
if(isset($_GET['biaya_grader_id'])){
    $pagemode = "afterSave()";
}else{
    $pagemode = "addItem()";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode;
", yii\web\View::POS_READY); ?>
<script>
function addItem(){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuanbiayagrader/addItem']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                    setDropdownGrader($(this));
                    $(this).find('select[name*="[graderlog_id]"]').select2({
                        allowClear: !0,
                        placeholder: 'Pilih Nama Grader',
                        width: null
                    });
                    reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setDropdownGrader(obj){
    var selected_items = [];
    $('#table-detail tbody tr').each(function(){
        var graderlog_id = $(this).find('select[name*="[graderlog_id]"]').val();
        if(graderlog_id){
            selected_items.push(graderlog_id);
        }
    });
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuanbiayagrader/setDropdownGrader']); ?>',
		type   : 'POST',
		data   : {selected_items:selected_items},
		success: function (data) {
			$(obj).find('select[name*="[graderlog_id]"]').html(data.html);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setMasterGrader(obj){
    var graderlog_id = $(obj).val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuanbiayagrader/setMasterGrader']); ?>',
        type   : 'POST',
        data   : {graderlog_id:graderlog_id},
        success: function (data) {
            if(data){
                $(obj).parents('tr').find('input[name*="[grader_norek]"]').val(data.graderlog_norek_bank);
                $(obj).parents('tr').find('input[name*="[grader_bank]"]').val(data.graderlog_bank);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setWilayahDinas(obj){
    var wilayah_dinas_id = $(obj).val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuanbiayagrader/setWilayahDinas']); ?>',
        type   : 'POST',
        data   : {wilayah_dinas_id:wilayah_dinas_id},
        success: function (data) {
            if(data){
                $(obj).parents('tr').find('input[name*="[biaya_grader_detail_jml]"]').val(formatInteger(data.wilayah_dinas_plafon));
				setTotalBiaya();
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setTotalBiaya(){
	var totalbiaya = 0;
	$('#table-detail tbody tr').each(function(index){
		totalbiaya += unformatNumber($(this).find('input[name*="[biaya_grader_detail_jml]"]').val());
	});
	totalbiaya = formatInteger(totalbiaya);
	setTimeout(function(){
		$('#<?= yii\bootstrap\Html::getInputId($model, 'biaya_grader_jml') ?>').val(totalbiaya);
	},200);
}

function save(){
    var $form = $('#form-pengajuanbiaya-greder');
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
        var field1 = $(this).find('select[name*="[graderlog_id]"]');
        var field2 = $(this).find('select[name*="[wilayah_dinas_id]"]');
        var field3 = $(this).find('input[name*="[grader_norek]"]');
        var field4 = $(this).find('input[name*="[grader_bank]"]');
        var field5 = $(this).find('input[name*="[biaya_grader_detail_jml]"]');
        if(!field1.val()){
            $(this).find('select[name*="[graderlog_id]"]').parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('select[name*="[graderlog_id]"]').parents('.form-group').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            $(this).find('select[name*="[wilayah_dinas_id]"]').parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('select[name*="[wilayah_dinas_id]"]').parents('.form-group').removeClass('error-tb-detail');
        }
        if(!field3.val()){
            $(this).find('input[name*="[grader_norek]"]').parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[grader_norek]"]').parents('.form-group').removeClass('error-tb-detail');
        }
        if(!field4.val()){
            $(this).find('input[name*="[grader_bank]"]').parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[grader_bank]"]').parents('.form-group').removeClass('error-tb-detail');
        }
        if(!field5.val()){
            $(this).find('input[name*="[biaya_grader_detail_jml]"]').parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[biaya_grader_detail_jml]"]').parents('.form-group').removeClass('error-tb-detail');
        }
    });
    
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    GetItemsByPengajuanBiayaGrader(id);
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tbiayagrader-biaya_grader_tgl').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
}

function GetItemsByPengajuanBiayaGrader(){
    $('#table-detail').addClass('animation-loading');
    var biaya_grader_id = '<?= (isset($_GET['biaya_grader_id'])?$_GET['biaya_grader_id']:'') ?>';
    var html = "";
    if(biaya_grader_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuanbiayagrader/GetItemsByPengajuanBiayaGrader']); ?>',
            type   : 'POST',
            data   : {biaya_grader_id:biaya_grader_id},
            success: function (data) {
                if(data){
                    html = data.html;
                    $('#table-detail tbody').html(html);
                    $('#table-detail').removeClass('animation-loading');
                    reordertable('#table-detail');
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

function daftarPengajuanBiayaGrader(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuanbiayagrader/daftarPengajuanBiayaGrader']) ?>','modal-daftar-pengajuanbiaya','75%');
}


</script>