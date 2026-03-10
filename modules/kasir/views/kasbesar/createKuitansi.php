<?php
app\assets\DatepickerAsset::register($this);
?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Buat Kuitansi Baru'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-transaksi-kuitansi',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<?= yii\helpers\Html::activeHiddenInput($model, "kuitansi_id") ?>
						<?= yii\helpers\Html::activeHiddenInput($model, "petugas") ?>
						<?= yii\helpers\Html::activeHiddenInput($model, "reff_penerimaan") ?>
						<?= yii\helpers\Html::activeHiddenInput($model, "cust_id") ?>
                        <?= $form->field($model, 'nomor')->textInput(['disabled'=>true]); ?>
						<?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setNomor()']); ?>
						<?= $form->field($model, 'terima_dari')->textInput()->label("Terima dari"); ?>
						<?= $form->field($model, 'untuk_pembayaran')->textarea()->label("Untuk Pembayaran"); ?>
                    </div>
					<div class="col-md-6">
						<?= $form->field($model, 'cara_bayar')->dropDownList(app\models\MDefaultValue::getOptionList('cara-bayar-voucher-penerimaan')); ?>
						<?= $form->field($model, 'nominal')->textInput(['class'=>'form-control float']); ?>
						<?= $form->field($model, 'petugas_nama')->textInput(['disabled'=>true]); ?>
						<?= $form->field($model, 'keterangan')->textarea(); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-transaksi\').modal(\'hide\'); getItems();")'
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
function setNomor(){
	var tgl = $('#<?= yii\helpers\Html::getInputId($model, 'tanggal') ?>').val();
	var cara_bayar = $('#<?= yii\helpers\Html::getInputId($model, 'cara_bayar') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/generateNomorKuitansi']); ?>',
		type   : 'POST',
		data   : {tgl:tgl,cara_bayar:cara_bayar},
		success: function (data) {
			if(data){
				$('#<?= yii\helpers\Html::getInputId($model, 'nomor') ?>').val(data);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>