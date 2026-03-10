<?php
use yii\helpers\Url;
?>
<div class="modal fade" id="modal-madul" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <?php
                $form = \yii\bootstrap\ActiveForm::begin([
                    'id' => 'form-reject',
                    'fieldConfig' => [
                        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                        'labelOptions'=>['class'=>'col-md-3 control-label'],
                    ],
                ]);
            ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title">Alasan Reject</h4>
            </div>
            <div class="modal-body">
                <?php echo $form->field($modKirimGudangDetail, 'kirim_gudang_detail_id')->hiddenInput(['value'=>$modKirimGudangDetail->kirim_gudang_detail_id])->label(false); ?> 
                <?php echo $form->field($modKirimGudangDetail, 'reject_reason')->textarea(['placeholder'=>'Ketik alasan'])->label(Yii::t('app', 'Alasan')); ?> 
            </div>
            <div class="modal-footer">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Ok'),['id'=>'bangke', 'class'=>'btn red btn-outline ciptana-spin-btn',
                    'onclick'=>'submits();'
                    //'onclick'=>'submitformajax(this,"$(\'#modal-madul\').modal(\'hide\'); $(\'#modal-review\').modal(\'hide\');")'
                    //'onclick'=>'submitformajax(this,"location.reload();")'
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
", yii\web\View::POS_READY); ?>

<script>
function submits(){
	var kirim_gudang_detail_id = $("#<?= yii\bootstrap\Html::getInputId($modKirimGudangDetail, "kirim_gudang_detail_id") ?>").val();
	var reject_reason = $("#<?= yii\bootstrap\Html::getInputId($modKirimGudangDetail, "reject_reason") ?>").val();
	if (kirim_gudang_detail_id != '' && reject_reason != '') {
		$.ajax({
			url    : '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/simpanlahSusahAmat']); ?>',
			type   : 'POST',
			data   : {kirim_gudang_detail_id:kirim_gudang_detail_id, reject_reason:reject_reason},
			success: function (data) {
                $("#modal-madul").hide();
                $("#modal-review").hide();
                window.location.href = "<?php echo Url::base();?>/gudang/penerimaanko/scanterima";
			},
		});
	}
}
</script>