<?php
app\assets\Select2Asset::register($this);
?>
<div class="modal fade" id="modal-master-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan PIC ISO Baru'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-master-create',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<?= $form->field($model, 'departement_id')->dropDownList(\app\models\MDepartement::getOptionList(),['class'=>'form-control select2', 'prompt'=>'','onchange'=>'setPegawai()'])->label('Nama Departement'); ?>
                        <?= $form->field($model, 'pegawai_id')->dropDownList([],['class'=>'form-control select2', 'prompt'=>''])->label('Nama Pegawai'); ?>
                        <?= $form->field($model, 'kategori_dokumen[]')->dropDownList([],['multiple'=>true,'class'=>'form-control select2', 'prompt'=>'']); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this, "$(\'#modal-master-create\').modal(\'hide\'); $(\'#table-master\').dataTable().fnClearTable();");'
                    ]);
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
    setPegawai();
	$('select[name*=\"[departement_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Nama Departement',
        width: '100%',
        dropdownParent: $('#modal-master-create'),
	});
	$('select[name*=\"[pegawai_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Nama Pegawai',
        width: '100%',
        dropdownParent: $('#modal-master-create'),
	});
    $('select[name*=\"[kategori_dokumen]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Kategori Dokumen',
        width: '100%',
        dropdownParent: $('#modal-master-create'),
	});
    setDDKategori();
", yii\web\View::POS_READY); ?>
<script>
    function setDDKategori(){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qms/pic/setDDKategori']); ?>',
            type   : 'POST',
            data   : {},
            success: function (data){
                if(data.html){
                    $("#<?= \yii\helpers\Html::getInputId($model, "kategori_dokumen") ?>").html(data.html);
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function setPegawai(){
        var departement_id = $("#<?= \yii\helpers\Html::getInputId($model, 'departement_id') ?>").val();
        
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qms/pic/setPegawai']); ?>',
            type   : 'POST',
            data   : {departement_id:departement_id},
            success: function (data) {
                if(data){
                    $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").html(data.dropdown);
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
</script>