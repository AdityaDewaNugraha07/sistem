<?php
app\assets\DatepickerAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);

?>
<div class="modal fade" id="modal-deletebykode" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Konfirmasi Hapus Penerimaan'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-importexcel',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-3 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 fontsize-1-2 text-align-center">
                        Pilih Penerimaan berdasarkan kode input yang akan anda hapus!
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'terima_sengon_id')->dropDownList( app\models\TTerimaSengon::getOptionList() ,['prompt'=>'', 'class'=>'form-control select2', 'style'=>'width:100%'] )->label("Kode Input"); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Delete'),['class'=>'btn red-flamingo btn-outline ciptana-spin-btn',
                    'onclick'=>'confirmThis()'
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
    $('select[name*=\"[terima_sengon_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Kode Penerimaan',
	});
    $.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script type="text/javascript">

function confirmThis(){
    $("#<?= \yii\helpers\Html::getInputId($model, "terima_sengon_id") ?>").parents(".col-md-8").removeClass("error-tb-detail");
    if( $("#<?= \yii\helpers\Html::getInputId($model, "terima_sengon_id") ?>").val() == "" ){
        $("#<?= \yii\helpers\Html::getInputId($model, "terima_sengon_id") ?>").parents(".col-md-8").addClass("error-tb-detail");
        return false;
    }
    cisConfirm(null,"Apakah Anda Yakin akan menghapus penerimaan ini?","deleteByKodeInput()","red-flamingo");
}
function deleteByKodeInput(){
    var terima_sengon_id = $("#<?= \yii\helpers\Html::getInputId($model, "terima_sengon_id") ?>").val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/terimasengon/DeleteByKodeInput']); ?>',
		type   : 'POST',
		data   : {terima_sengon_id:terima_sengon_id},
		success: function (data) {
			if(data){
                $('#modal-deletebykode').modal('hide');
				$('#table-laporan').dataTable().fnClearTable(); 
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>