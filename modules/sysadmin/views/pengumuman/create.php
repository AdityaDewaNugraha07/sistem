<?php app\assets\SummernoteAsset::register($this); ?>
<style>
.panel-body p{
	margin: 10px 0;
}
</style>
<div class="modal fade" id="modal-master-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Pengumuman Baru'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-master-create',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-3 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'tipe')->dropDownList(\app\models\MDefaultValue::getOptionList('bootstrap-class'),['prompt'=>'']); ?>
                        <?= $form->field($model, 'judul')->textInput(); ?>
                        <?php // echo $form->field($model, 'deskripsi')->textarea(); ?>
						<?= $form->field($model, 'seq')->textInput()->label('Urutan'); ?>
						<?= $form->field($model, 'judul_pulsate',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                        ->checkbox([],false)->label(Yii::t('app', 'Pulsate')); ?>
						<?= yii\bootstrap\Html::activeHiddenInput($model, 'deskripsi') ?>
                    </div>
					<div class="col-md-8" style="margin-left: -15px;">
						<div name="deskripsi_editor" id="deskripsi_editor"> </div>
					</div>
                </div><br>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-master-create\').modal(\'hide\'); $(\'#table-master\').dataTable().fnClearTable();")'
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
    $(\"#deskripsi_editor\").summernote({
		height: 300,
		placeholder: 'Ketik Deskripsi Disini',
		dialogsInBody: true,
		dialogsFade: true,
		callbacks: {
			onBlur: function() {
				var des = $('#deskripsi_editor').summernote('code');
				$('#".yii\bootstrap\Html::getInputId($model, 'deskripsi')."').val(des);
			}
		}
	});
", yii\web\View::POS_READY); ?>