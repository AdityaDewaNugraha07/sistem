<?php 
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-6">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-5 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', "Edit Dinas Kerja Grader"); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<?= $form->field($model, 'kode')->textInput(['style'=>'width:200px;','readonly'=>true])->label(Yii::t('app', 'Kode Dinas')); ?>
						<?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
								<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
						<div class="form-group">
							<?= \yii\bootstrap\Html::activeLabel($model, 'tipe', ['class'=>'col-md-5 control-label']); ?>
							<div class="col-md-6">
								<?php echo yii\helpers\Html::activeRadioList($model, 'tipe', \app\models\MDefaultValue::getOptionList('tipe-dinas-grader'),['separator' => ' &nbsp; &nbsp;', 'tabindex' => 3]); ?>
							</div>
						</div>
						<?= $form->field($model, 'graderlog_nm')->textInput(['readonly'=>true])->label(Yii::t('app', 'Grader')); ?>
                    </div>
					<div class="col-md-6">
						<?= $form->field($model, 'wilayah_dinas_nama')->textInput(['readonly'=>true])->label(Yii::t('app', 'Wilayah Dinas')); ?>
						<?= $form->field($model, 'tujuan')->textInput()->label(Yii::t('app', 'Tujuan PT')); ?>
						<?= $form->field($model, 'keterangan')->textarea(); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#close-btn-modal\').removeAttr(\'disabled\'); $(\'#close-btn-modal\').trigger(\'click\'); getItems();")'
                    ]);
				?>
            </div>
			<?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
formconfig();
$('#".yii\bootstrap\Html::getInputId($model, 'graderlog_id')."').select2({
	allowClear: !0,
	placeholder: 'Pilih Nama Grader',
	width: null
});
$('#".yii\bootstrap\Html::getInputId($model, 'wilayah_dinas_id')."').select2({
	allowClear: !0,
	placeholder: 'Pilih Wilayah Dinas',
	width: null
});
$.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script>
</script>