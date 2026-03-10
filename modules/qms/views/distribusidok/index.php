<?php
/* @var $this yii\web\View */

use app\components\Params;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Distribusi Dokumen';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Distribusi Dokumen'); ?></h1>
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
                        <a class="btn blue btn-sm btn-outline pull-right" style="margin-left: 5px;" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Distribusi Dokumen Yang Telah Dibuat'); ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Distribusi Dokumen'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Nomor Dokumen'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 5px;">
                                                <span class="input-group-btn" style="width: 90%">
                                                    <?= \yii\bootstrap\Html::activeDropDownList($model, 'dokumen_revisi_id', \app\models\TDokumenRevisi::getOptionList(isset($_GET['dokumen'])?$_GET['dokumen']:''),['class'=>'form-control select2','prompt'=>'','onchange'=>'setPic(); setNama();','style'=>'width:100%;']); ?>
												</span>
												<span class="input-group-btn" style="width: 10%">
													<a class="btn btn-icon-only btn-default tooltips" onclick="pickDokumen();"  id="btn-icon-pick" data-original-title="Lihat Master Dokumen" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
												</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?= $form->field($model, 'nama_dokumen')->textInput(['disabled'=>'disabled']); ?>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Penerima Dokumen'); ?></label>
                                            <div class="col-md-8">
                                                <span id="place-pic"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                        if(isset($_GET['dokumen_distribusi_id'])){
                                            $datetime = new DateTime($model->tanggal_dikirim);
                                            $tanggal = $datetime->format('d/m/Y');
                                            $model->tanggal_dikirim = $tanggal;
                                        }
                                        // echo $form->field($model, 'tanggal_dikirim')->textInput(['disabled'=>'disabled']); 
                                        echo $form->field($model, 'tanggal_dikirim',[
                                                'template'=>'{label}<div class="col-md-4"><div class="input-group date date-picker">{input} <span class="input-group-btn">
                                                        <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                        {error}</div>'])->textInput(['readonly'=>'readonly']);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php //echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printDok('.(isset($_GET['dokumen_revisi_id'])?$_GET['dokumen_revisi_id']:'').');','disabled'=>true]); ?>
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
if(isset($_GET['dokumen'])){
    $page = 'aftersave('.$_GET['dokumen'].');';
}
?>
<?php $this->registerJs(" 
	formconfig();
    $page
    $('select[name*=\"[dokumen_revisi_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Masukkan Nomor Dokumen',
	});
    $('select[name*=\"[pic_iso_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Masukkan Nama Pegawai',
	});
    setPic();
", yii\web\View::POS_READY); ?>
<script>
    function setPic(){
        var dokumen_revisi_id = $("#<?= \yii\helpers\Html::getInputId($model, 'dokumen_revisi_id') ?>").val();
        var edit = "<?= isset($_GET['edit'])?$_GET['edit']:''; ?>";
        var id = "<?= isset($_GET['dokumen'])?$_GET['dokumen']:''; ?>";
        var tgl_kirim = "<?= isset($_GET['tgl_kirim'])?$_GET['tgl_kirim']:''; ?>";

        if(dokumen_revisi_id){
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/qms/distribusidok/setPic']); ?>',
                type   : 'POST',
                data   : {dokumen_revisi_id:dokumen_revisi_id, id:id},
                success: function (data) {
                    if(data){
                        $('#place-pic').html(data.html);
                    }

                    if(id){
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'dokumen_revisi_id') ?>").prop('disabled', true);
                        $("input[type='checkbox']").prop('disabled', true);
                        $('#btn-icon-pick').css('pointer-events', 'none');
                        if(edit){
                            $("input[type='checkbox']").removeAttr('disabled');
                        }
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
        var field1 = $("#<?= \yii\helpers\Html::getInputId($model, 'dokumen_revisi_id') ?>").val();

        if(!field1){
            $("#<?= \yii\helpers\Html::getInputId($model, 'dokumen_revisi_id') ?>").addClass('error-tb-detail');
            has_error = has_error + 1;
        } else {
            $("#<?= \yii\helpers\Html::getInputId($model, 'dokumen_revisi_id') ?>").removeClass('error-tb-detail');
        }

        var checkboxGroup = $("input[name^='TDokumenDistribusi[pic_iso_id]']");
        if(checkboxGroup.length > 0){
            var checkboxChecked = checkboxGroup.filter(':checked').length;
            if(checkboxChecked === 0){
                cisAlert("Anda belum memilih penerima dokumen, mohon cek kembali!");
                has_error += 1;
                $("#checkbox-group").addClass("error-tb-detail");
            } else {
                $("#checkbox-group").removeClass("error-tb-detail");
            }
        } else {
            cisAlert("PIC penerima dokumen belum ada, mohon cek kembali!");
            has_error += 1;
        }

        if(has_error === 0){
            return true;
        }
        return false;
    }

    function aftersave(id){
        <?php if(!isset($_GET['edit'])){ ?>
            $('#btn-save').attr('disabled','');
            $("#<?= \yii\helpers\Html::getInputId($model, 'tanggal_dikirim') ?>").prop('disabled', true);
            $('.date-picker').find('.input-group-btn').find('button').prop('disabled', true);
        <?php } ?>
    }
    
    function daftarAfterSave(){
        openModal('<?= \yii\helpers\Url::toRoute(['/qms/distribusidok/daftarAfterSave']) ?>','modal-aftersave','90%');
    }

    function pickDokumen(){
        var url = '<?= \yii\helpers\Url::toRoute(['/qms/distribusidok/pick']); ?>';
        $(".modals-place-3-min").load(url, function() {
            $("#modal-master .modal-dialog").css('width','90%');
            $("#modal-master").modal('show');
            $("#modal-master").on('hidden.bs.modal', function () {});
            spinbtn();
            draggableModal();
        });
    }

    function pick(id, nomor, nama, rev){
        $("#modal-master").find('button.fa-close').trigger('click');
        $("#<?= \yii\helpers\Html::getInputId($model, 'dokumen_revisi_id') ?>").empty().append('<option value="'+id+'">'+nomor + ' - REVISI ' + rev +'</option>').val(id).trigger('change');
        $("#<?= \yii\helpers\Html::getInputId($model, 'nama_dokumen') ?>").val(nama);
    }

    function setNama(){
        var dokumen_revisi_id = $("#<?= \yii\helpers\Html::getInputId($model, 'dokumen_revisi_id') ?>").val();
        $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/qms/distribusidok/setNama']); ?>',
                type   : 'POST',
                data   : {dokumen_revisi_id:dokumen_revisi_id},
                success: function (data) {
                    if(data){
                        $("#<?= \yii\helpers\Html::getInputId($model, 'nama_dokumen') ?>").val(data.nama);
                    }
                    console.log(data);
                },
                error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
            });
    }
</script>