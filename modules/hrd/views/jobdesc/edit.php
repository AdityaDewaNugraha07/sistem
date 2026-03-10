<?php
app\assets\FileUploadAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<div class="modal fade" id="modal-jobdesc-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Jobdesc Pegawai'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-jobdesc',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'pegawai_id')->dropDownList(\app\models\MPegawai::getOptionListWithDeptName(),['class'=>'form-control select2','prompt'=>''])->label('Nama Pegawai'); ?>
                    </div>
                    <div class="col-md-8">
                        <?php echo $form->field($model, 'file',[
                                'template'=>'
                                    <div class="col-md-12">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div>
                                                <span class="btn blue-hoki btn-outline btn-file">
                                                    <span class="fileinput-new"> Select File</span>
                                                    <span class="fileinput-exists"> Change </span>
                                                    {input} 
                                                </span> 
                                                <span class="btn blue-hoki btn-outline btn-file" id="label_nama_file"></span>
                                                <a href="javascript:;" id="remove_file" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                {error}
                                            </div>
                                        </div>
                                    </div>'
                            ])->fileInput();
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                <?php 
                // echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                //     'onclick'=>'submitformajax(this,"$(\'#modal-jobdesc-edit\').modal(\'hide\'); $(\'#table-jobdesc\').dataTable().fnClearTable();")'
                //     ]);
                ?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->registerJs(" 
    formconfig();
    $('select[name*=\"[pegawai_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
		width: '100%',
	});
", yii\web\View::POS_READY); 
?>

<script>
$('#<?= \yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>').prop("disabled", true);
$("#label_nama_file").show();
$('#label_nama_file').text('<?= $model->nama_file; ?>');
$('#remove_file').show();
$('.btn.blue-hoki.btn-outline.btn-file .fileinput-new').hide();
$('.btn.blue-hoki.btn-outline.btn-file .fileinput-exists').show();

$('#tjobdesc-file').change(function() {
    var i = $(this).prev('label').clone();
    var file = $('#tjobdesc-file')[0].files[0].name;
    $("#label_nama_file").show();
    $('#label_nama_file').text(file);
    $('#remove_file').show();
    $('.btn.blue-hoki.btn-outline.btn-file .fileinput-new').hide();
    $('.btn.blue-hoki.btn-outline.btn-file .fileinput-exists').show();  
});

$('#remove_file').click(function() {
    $('#label_nama_file').text('');
    $("#label_nama_file").hide();
    $('#remove_file').hide();
    $('.btn.blue-hoki.btn-outline.btn-file .fileinput-new').show();
    $('.btn.blue-hoki.btn-outline.btn-file .fileinput-exists').hide();
});

function save(){
    var $form = $('#form-jobdesc');
    if(formrequiredvalidate($form)){
        if($('#tjobdesc-file').val()){
            submitformajax($form,"$(\'#modal-jobdesc-edit\').modal(\'hide\'); $(\'#table-jobdesc\').dataTable().fnClearTable();");
        } else {
            cisAlert("Pilih file terlebih dahulu!");
        }
    }
}
</script>