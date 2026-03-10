<div class="modal fade" id="modal-master-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Kode Partai'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-master-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, "kode_partai")->textInput(['value'=>$kode_partai]); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'saveKodePartai(this)'])?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
function saveKodePartai(ele){
	var kode_partai = $("#modal-master-edit").find("input[name*='[kode_partai]']").val();
	if(kode_partai){
		$("#modal-master-edit").find("input[name*='[kode_partai]']").removeClass("error-tb-detail");
		submitformajax(ele,"$('#modal-master-edit').modal('hide'); getItems();");
	}else{
		$("#modal-master-edit").find("input[name*='[kode_partai]']").addClass("error-tb-detail");
	}
}
</script>