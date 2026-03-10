<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-caratransaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-6">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="modal fade" id="modal-caratransaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-caratransaksi" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Masukkan Nomor ').$cara; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<?php echo $form->field($model, 'reff_cara_transaksi')->textInput(['style'=>'font-weight:bold'])->label("Reff. Number"); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Ok'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'pick();']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php $this->registerJs(" 
	
", yii\web\View::POS_READY); ?>
<script>
function pick(){
	var reff_no = $('#modal-caratransaksi').find('input[name*="[reff_cara_transaksi]"]').val();
	if(reff_no){
		$('#<?= $idtarget ?>').val(reff_no);
		$("#close-btn-caratransaksi" ).click();
		$('#<?= $idtarget ?>').parents('tr').find('select[name*="[cara_transaksi]"]').val('<?= $cara ?>');
		$('#<?= $idtarget ?>').parents('tr').find("#place-caratransaksilabel").removeAttr("style");
		$('#<?= $idtarget ?>').parents('tr').find("#place-caratransaksilabel").html("<b>"+reff_no+"</b>");
	}else{
		cisAlert("Reff. Number harus Diisi");
		return false;
	}
}
</script>