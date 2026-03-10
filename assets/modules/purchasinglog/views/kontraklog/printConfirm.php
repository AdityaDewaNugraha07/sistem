<?php app\assets\RepeaterAsset::register($this); ?>
<style>
.note-editable p{
	margin: 0px;
}
</style>
<div class="modal fade" id="modal-konfirmsi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				
                <h4 class="modal-title"><?= Yii::t('app', 'Konfirmasi Cetak PO Log Alam'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-konfirmsi',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-10">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-2 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        Pilih Keputusan Pembelian Atas Dasar ini :<br>
                        <?php
                        $form->field($model, "",[]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Lanjutkan Print <i class="fa fa-arrow-right"></i>'),['class'=>'btn blue btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this);'
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
	$('.repeater').repeater({
        show: function () {
            $(this).slideDown();
            $('div[data-repeater-item][style=\"display: none;\"]').remove();
        },
        hide: function (e) {
            $(this).slideUp(e);
        },
    });
    
", yii\web\View::POS_READY); ?>
<script type="text/javascript">
</script>