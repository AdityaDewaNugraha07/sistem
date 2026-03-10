<?php 
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<div class="modal fade" id="modal-realisasi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi-relisasidinas',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-6">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal2" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title text-align-center"><?= Yii::t('app', "Realisasi Kas Grader"); ?> </h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-6">
						<?= yii\bootstrap\Html::activeHiddenInput($model, 'graderlog_id'); ?>
						<?= yii\bootstrap\Html::activeHiddenInput($model, 'dkg_id'); ?>
						<?= $form->field($model, 'kode')->textInput(['readonly'=>true])->label(Yii::t('app', 'Kode Realisasi')); ?>
						<?= $form->field($model, 'periode_awal',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime" style="width:195px !important">{input} <span class="input-group-addon">
								<button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly'])->label('Periode Awal'); ?>
						<?= $form->field($model, 'periode_akhir',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime" style="width:195px !important">{input} <span class="input-group-addon">
								<button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly'])->label('Periode Awal'); ?>
						<?= $form->field($model, 'graderlog_nm')->textInput(['readonly'=>true])->label("Nama Grader"); ?>
					</div>
					<div class="col-md-6">
						<?= $form->field($model, 'saldo_awal')->textInput(['readonly'=>true,'class'=>'form-control float'])->label("Saldo Awal"); ?>
						<?= $form->field($model, 'total_realisasi')->textInput(['style'=>'font-weight:800','class'=>'form-control float'])->label("Total Realisasi"); ?>
						<?= $form->field($model, 'saldo_akhir')->textInput(['readonly'=>true,'class'=>'form-control float'])->label("Saldo Akhir"); ?>
						<?= $form->field($model, 'keterangan')->textarea(); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#close-btn-modal2\').removeAttr(\'disabled\'); $(\'#close-btn-modal2\').trigger(\'click\'); getItemsRealisasi('.$model->dkg_id.'); getSaldo();")'
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
$('#".yii\bootstrap\Html::getInputId($model, 'kanit_grader')."').select2({
	allowClear: !0,
	placeholder: 'Ketik Nama',
	width: null
});
$('#".yii\bootstrap\Html::getInputId($model, 'approved_by')."').select2({
	allowClear: !0,
	placeholder: 'Ketik Nama',
	width: null
});
", yii\web\View::POS_READY); ?>
<script>

</script>