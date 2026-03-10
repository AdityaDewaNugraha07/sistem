<?php
app\assets\Select2Asset::register($this);
?>
<div class="modal fade draggable-modal" id="modal-master-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Data PIC ISO'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-master-edit',
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
                        <?= $form->field($model, 'kategori_dokumen[]')->dropDownList([],['multiple'=>true,'class'=>'form-control select2']); ?> <!-- \app\models\MDokumen::getOptionListJenis() -->
                        <?= $form->field($model, 'active',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                        ->checkbox([],false)->label(Yii::t('app', 'Active')); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-master-edit\').modal(\'hide\'); $(\'#table-master\').dataTable().fnClearTable();")'])?>
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
        dropdownParent: $('#modal-master-edit'),
	});
	$('select[name*=\"[pegawai_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Nama Pegawai',
        width: '100%',
        dropdownParent: $('#modal-master-edit'),
	});
    $('select[name*=\"[kategori_dokumen]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Kategori Dokumen',
        width: '100%',
        dropdownParent: $('#modal-master-edit'),
	});
    setDDKategori();
", yii\web\View::POS_READY); ?>
<script>
    function setDDKategori(){
        var id = <?= $model->pic_iso_id; ?>;
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qms/pic/setDDKategori']); ?>',
            type   : 'POST',
            data   : { id:id},
            success: function (data){
                if(data.html){
                    $("#<?= \yii\helpers\Html::getInputId($model, "kategori_dokumen") ?>").html(data.html);
                    
                    if(data.kategori){
                        var kategori = data.kategori.join(',');
                        var kategori_split = kategori.split(',');
                        $("#<?= \yii\helpers\Html::getInputId($model, "kategori_dokumen") ?>").val( kategori_split ).change();
                    }
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function setPegawai(){
        var departement_id = $("#<?= \yii\helpers\Html::getInputId($model, 'departement_id') ?>").val();
        var selected = <?= $model->pegawai_id; ?>;
        
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qms/pic/setPegawai']); ?>',
            type   : 'POST',
            data   : {departement_id:departement_id,selected:selected},
            success: function (data) {
                if(data){
                    $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").html(data.dropdown);
                    $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").val(selected).trigger('change');
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
</script>