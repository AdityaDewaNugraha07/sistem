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
				
                <h4 class="modal-title"><?= Yii::t('app', 'Konfirmasi Cetak Shipping Instruction'); ?></h4>
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
                    <div class="col-md-12">
						<?php echo $form->field($model, 'si_shipper', ['options' => []])->textarea()->label("SHIPPER"); ?>
						<?php echo $form->field($model, 'si_consignee', ['options' => []])->textarea()->label("CONSIGNEE"); ?>
						<?php echo $form->field($model, 'si_notify', ['options' => []])->textarea()->label("NOTIFY PARTY"); ?>
						<div class="field-tpackinglist-si_gd_label" style="margin-top: 10px;">
                            <label class="col-md-2 control-label"><?= Yii::t('app', 'GOODS DESCRIPTION'); ?></label>
                            <div class="col-md-10">
								<?= \yii\helpers\Html::activeTextInput($model, "si_gd_product",["class"=>"form-control"]); ?>
								<?= \yii\helpers\Html::activeTextarea($model, "si_gd_sizegrade",["class"=>"form-control"]); ?>
								<?= \yii\helpers\Html::activeTextInput($model, "si_gd_total",["class"=>"form-control"]); ?>
                                <div class="repeater">
                                    <div data-repeater-list="<?= \yii\helpers\StringHelper::basename(get_class($model));  ?>">
										<?php foreach($model->si_gdrepeater as $gdlabel => $gdvalue){ ?>
                                        <div data-repeater-item style="display: block;">
											<span class="input-group-btn" style="width: 30%">
												<?php echo \yii\bootstrap\Html::activeTextInput($model, 'si_gdrepeater[label]', ['class'=>'form-control','value'=>$gdlabel]) ?>
											</span>
											<span class="input-group-btn" style="width: 65%">
												<?php echo \yii\bootstrap\Html::activeTextInput($model, 'si_gdrepeater[value]', ['class'=>'form-control','value'=>$gdvalue]) ?>
											</span>
											<span class="input-group-btn" style="width: 5%" id="remove-btn">
												<a href="javascript:;" data-repeater-delete class="btn btn-danger"><i class="fa fa-close"></i></a>
											</span>
										</div>
										<?php } ?>
                                    </div>
                                    <a href="javascript:;" data-repeater-create class="btn btn-xs btn-info mt-repeater-add" style="margin-top: 5px;">
                                        <i class="fa fa-plus"></i> <?= Yii::t('app', 'add more'); ?>
                                    </a>
                                </div>
								<?= \yii\helpers\Html::activeTextInput($model, "si_gd_ket",["class"=>"form-control","style"=>"margin-top:10px; margin-bottom:8px;"]); ?>
                            </div>
                        </div>
						<?php echo $form->field($model, 'si_instruction', ['options' => [""]])->textarea()->label("SPECIAL INSTRUCTION"); ?>
						<br><br>
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