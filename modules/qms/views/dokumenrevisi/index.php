<?php
/* @var $this yii\web\View */

use app\components\Params;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Dokumen Revisi';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Dokumen Revisi'); ?></h1>
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
<style>
.table-advance tr td:first-child {
    border-left-width: 1px !important;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <a class="btn blue btn-sm btn-outline pull-right" style="margin-left: 5px;" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Dokumen Revisi Yang Telah Dibuat'); ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Dokumen'); ?></h4></span>
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
                                            <div class="col-md-4 control-label"><label>Nomor Dokumen</label></div>
                                            <div class="col-md-7">
                                                <span class="input-group-btn" style="width: 90%">
                                                    <?= \yii\bootstrap\Html::activeDropDownList($modRevisi, 'dokumen_id', \app\models\MDokumen::getOptionList(),['class'=>'form-control select2','id'=>'dokumen','prompt'=>'','onchange'=>'setDokumen()','style'=>'width:100%;']); ?>
												</span>
												<span class="input-group-btn" style="width: 10%">
													<a class="btn btn-icon-only btn-default tooltips" onclick="pickDokumen();"  id="btn-icon-cust" data-original-title="Lihat Master Dokumen" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
												</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12" style="padding: 0px;" id="place-dokumen-asal">
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-bottom: 5px;">
                                                        <div class="col-md-4 control-label"><label>Nama Dokumen Asal</label></div>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="margin-bottom: 5px;">
                                                        <div class="col-md-4 control-label"><label>Jenis Dokumen</label></div>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-bottom: 5px;">
                                                        <div class="col-md-4 control-label"><label>Kategori Dokumen</label></div>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="" disabled>
                                                        </div>
                                                    </div>
                                                </div>
											</div>
                                            <div class="col-md-12" style="padding: 0px; display: none;" id="place-riwayat-revisi">
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 10px;">
                                                        <div class="col-md-4"></div>
                                                        <div class="col-md-7">
                                                            <a class="btn btn-default tooltips" onclick="riwayatRevisi();" data-original-title="Lihat Riwayat Revisi" style="margin-left: 3px; border-radius: 4px; font-size: 1.2rem;"><i class="fa fa-clock-o"></i> Riwayat Revisi</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
											<div class="col-md-8">
												<h4><?= Yii::t('app', 'Data Dokumen Revisi'); ?></h4>
											</div>
                                            <div class="col-md-12" style="padding: 0px;" id="place-dokumen-revisi">
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-bottom: 5px;">
                                                        <div class="col-md-4 control-label"><label>Nama Dokumen Revisi</label></div>
                                                        <div class="col-md-7">
                                                            <?php echo \yii\bootstrap\Html::activeTextInput($modRevisi, 'nama_dokumen', ['class'=>'form-control','style'=>'width:100%']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="margin-bottom: 5px;">
                                                        <div class="col-md-4 control-label"><label>Revisi Ke</label></div>
                                                        <div class="col-md-7">
                                                            <?= \yii\bootstrap\Html::activeTextInput($modRevisi, 'revisi_ke', ['class'=>'form-control','style'=>'width:100%', 'disabled'=>true]) ?>
                                                        </div>
                                                    </div>
                                                    <?php echo $form->field($modRevisi, 'tanggal_berlaku',[
                                                        'template'=>'{label}<div class="col-md-4"><div class="input-group date date-picker">{input} <span class="input-group-btn">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-bottom: 5px;">
                                                        <div class="col-md-4 control-label"><label>Catatan Revisi</label></div>
                                                        <div class="col-md-7">
                                                            <?= \yii\bootstrap\Html::activeTextarea($modRevisi, 'catatan_revisi', ['class'=>'form-control','style'=>'width:100%']) ?>
                                                        </div>
                                                    </div>
                                                </div>
											</div>
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
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php 
$page = '';
if(isset($_GET['dokumen_revisi_id'])){
    $page = 'aftersave('.$_GET['dokumen_revisi_id'].');';
}
?>
<?php $this->registerJs(" 
	formconfig();
    $page
    $('#dokumen').select2({
		allowClear: !0,
		placeholder: 'Masukkan Nomor Dokumen',
	});
", yii\web\View::POS_READY); ?>
<script>
function setDokumen(){ 
    var id = $('#dokumen').val();
    $('#place-dokumen-asal').html("");
    $('#place-dokumen-revisi').html("");
    
    var edit = "<?= isset($_GET['edit'])?$_GET['edit']:''; ?>";
    var dok_id = "<?= isset($_GET['dokumen_revisi_id'])?$_GET['dokumen_revisi_id']:''; ?>";

    if(id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qms/dokumenrevisi/setDokumen']); ?>',
            type   : 'POST',
            data   : {id:id, edit: edit,dok_id: dok_id},
            success: function (data) {
                if(data){
                    $('#place-dokumen-asal').html(data.html);
                    $('#place-dokumen-revisi').html(data.html2);
                    $('.date-picker').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    }); 
                    
                    if(dok_id && !edit){
                        $('form').find('input').each(function(){ $(this).prop("disabled", true); });
                        $('form').find('select').each(function(){ $(this).prop("disabled", true); });
                        $('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
                        $('.date-picker').find('.input-group-btn').find('button').prop('disabled', true);
                    }
                    if(!dok_id){
                        setRevisi(id);
                    }
                    setRiwayat();
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}

function save(){
    var form = '#form-transaksi';
    if(validateform()){
        submitform(form);
    } 
}

function validateform(){
    var has_error = 0;
    var field1 = $("#<?= \yii\helpers\Html::getInputId($modRevisi, 'nama_dokumen') ?>").val();
    var field2 = $("#<?= \yii\helpers\Html::getInputId($modRevisi, 'revisi_ke') ?>").val();

    if(!field1){
        $("#<?= \yii\helpers\Html::getInputId($modRevisi, 'nama_dokumen') ?>").addClass('error-tb-detail');
		has_error = has_error + 1;
    } else {
        $("#<?= \yii\helpers\Html::getInputId($modRevisi, 'nama_dokumen') ?>").removeClass('error-tb-detail');
    }

    if(!field2){
        $("#<?= \yii\helpers\Html::getInputId($modRevisi, 'revisi_ke') ?>").addClass('error-tb-detail');
		has_error = has_error + 1;
    } else {
        if(!/^\d+$/.test(field2)){
            $("#<?= \yii\helpers\Html::getInputId($modRevisi, 'revisi_ke') ?>").addClass('error-tb-detail');
            cisAlert('Revisi hanya dapat diisi dengan angka!');
            has_error = has_error + 1;
        } else {
            $("#<?= \yii\helpers\Html::getInputId($modRevisi, 'revisi_ke') ?>").removeClass('error-tb-detail');
        }
    }

    if(has_error === 0){
        return true;
    }
    return false;
}

function aftersave(id){
    var edit = "<?= isset($_GET['edit'])?$_GET['edit']:''; ?>";

    $('#btn-search').attr('disabled','');
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    if(edit){
        $('#btn-save').removeAttr('disabled');
        $('#btn-print').attr('disabled','');
    }

    $('#dokumen').val(<?= $modRevisi->dokumen_id; ?>);
    setDokumen();
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/qms/dokumenrevisi/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function pickDokumen(){
	var url = '<?= \yii\helpers\Url::toRoute(['/qms/dokumen/pick']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function pick(id, nomor){
	$("#modal-master").find('button.fa-close').trigger('click');
    $('#dokumen').empty().append('<option value="'+id+'">'+nomor+'</option>').val(id).trigger('change');
}

function setRevisi(id){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/qms/dokumenrevisi/setRevisi']); ?>',
        type   : 'POST',
        data   : {id:id},
        success: function (data) {
            var revisi = data + 1;
            $("#<?= \yii\helpers\Html::getInputId($modRevisi, 'revisi_ke') ?>").val(revisi);
            setRiwayat();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
} 

function setRiwayat(){
    $('#place-riwayat-revisi').css('display', 'none');
    var revisi_ke = $("#<?= \yii\helpers\Html::getInputId($modRevisi, 'revisi_ke') ?>").val();
    if(revisi_ke > 0){
        $('#place-riwayat-revisi').css('display', '');
    }
}

function riwayatRevisi(){
    var dokumen_id = $('#dokumen').val();
    openModal('<?= \yii\helpers\Url::toRoute(['/qms/dokumenrevisi/riwayatRevisi', 'dokumen_id'=>'']) ?>'+dokumen_id,'modal-riwayat-revisi','90%');
}
</script>