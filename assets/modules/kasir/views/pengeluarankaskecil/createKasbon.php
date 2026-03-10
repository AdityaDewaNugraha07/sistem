<?php app\assets\DatepickerAsset::register($this); ?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-6">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Kasbon Baru'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<?= yii\bootstrap\Html::activeHiddenInput($model, 'kas_bon_id'); ?>
						<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']); ?>
						<?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
								<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
						<?= $form->field($model, 'penerima')->textInput()->label(Yii::t('app', 'Penerima')); ?>
                    </div>
					<div class="col-md-6">
						<?= $form->field($model, 'nominal')->textInput(['class'=>'form-control float'])->label(Yii::t('app', 'Kredit')); ?>
						<?= $form->field($model, 'deskripsi')->textarea()->label(Yii::t('app', 'Deskripsi')); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'saveKasbon()'
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
", yii\web\View::POS_READY); ?>
<script>
function saveKasbon(){
	$('#form-transaksi').find('input[name*="[nominal]"]').val( unformatNumber($('#form-transaksi').find('input[name*="[nominal]"]').val()) );
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/sementara']); ?>',
		type   : 'POST',
		data   : { formData: $('#form-transaksi').find('input, textarea').serialize() },
		success: function (data) {
			if(data.status){
				$('#modal-transaksi').modal('hide');
				$('#table-laporan').dataTable().fnClearTable();
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
    return false;
}
</script>