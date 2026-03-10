<?php app\assets\DatepickerAsset::register($this); ?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-cancel',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-6">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-3 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', $pesan); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<?= $form->field($model, 'kode')->textInput(['style'=>'width:200px;','readonly'=>true,'value'=>$model->kode.$model->urutan_kode])->label(Yii::t('app', 'Kode BKK')); ?>
						<?= $form->field($model, 'tanggal_bayar',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
								<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setKode(this)']); ?>
						<?= $form->field($model, 'urutan_kode')->textInput(['style'=>'width:100px;','onkeyup'=>'setKode(this)'])->label(Yii::t('app', 'Urutan Kode')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#close-btn-modal\').removeAttr(\'disabled\'); $(\'#close-btn-modal\').trigger(\'click\'); $(\'#table-aftersave\').dataTable().fnClearTable();  ")'
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
", yii\web\View::POS_READY); ?>
<script>
function setKode(ele){
	var kode = '<?= $model->kode ?>';
	var tanggalBayar = $(ele).parents('.row').find('input[name*="[tanggal_bayar]"]').val();
	var kode1 = kode.substr(0, 6);
	var kode2 = "K";
	var kode3 = tanggalBayar.substr(3, 2);
	var urutan = $('#<?= yii\bootstrap\Html::getInputId($model, 'urutan_kode') ?>').val();
	$(ele).parents('.row').find('input[name*="[kode]"]').val(kode1+kode2+kode3+urutan);
}
</script>