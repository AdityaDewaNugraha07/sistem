<?php 
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<div class="modal fade" id="modal-realisasimakan" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi-relisasimakan',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-6">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal2" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title text-align-center"><?= Yii::t('app', "Realisasi Makan Grader"); ?> </h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-6"><?php echo "wilayah dinas nama = ".$model->wilayah_dinas_nama;?>
						<?= yii\bootstrap\Html::activeHiddenInput($model, 'graderlog_id'); ?>
						<?= yii\bootstrap\Html::activeHiddenInput($model, 'dkg_id'); ?>
						<?= yii\bootstrap\Html::activeHiddenInput($model, 'wilayah_dinas_id'); ?>
						<?= $form->field($model, 'kode')->textInput(['readonly'=>true])->label(Yii::t('app', 'Kode Realisasi')); ?>
						<?= $form->field($model, 'periode_awal',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime" style="width:195px !important">{input} <span class="input-group-addon">
								<button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setAll();'])->label('Periode Awal'); ?>
						<?= $form->field($model, 'periode_akhir',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime" style="width:195px !important">{input} <span class="input-group-addon">
								<button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setAll();'])->label('Periode Akhir'); ?>
						<?= $form->field($model, 'graderlog_nm')->textInput(['readonly'=>true])->label("Nama Grader"); ?>
						<?= $form->field($model, 'wilayah_dinas_nama')->textInput(['readonly'=>true])->label("Wilayah Dinas"); ?>
						<?= $form->field($model, 'tempat_tujuan')->textInput(['readonly'=>true])->label("Tempat Tujuan"); ?>
						<?= $form->field($model, 'keterangan')->textarea(); ?>
					</div>
					<div class="col-md-6">
						<?= $form->field($model, 'wilayah_dinas_makan')->textInput(['readonly'=>true,'class'=>'form-control float'])->label("Uang Makan /hari"); ?>
						<?= $form->field($model, 'wilayah_dinas_pulsa')->textInput(['class'=>'form-control float','onblur'=>'setAll();'])->label("Uang Pulsa /bln"); ?>
						<?php
						// REQUEST RAHMAN & KRISNA 
						// uang makan grader sengon fixed 25 hari, uang makan grader selain sengon menyesuaikan jumlah hari kerja
						if ($model->wilayah_dinas_nama == 'Jawa (Sengon)') {
						?>
						<?= $form->field($model, 'qty_hari')->textInput(['readonly'=>true,'class'=>'form-control float','value'=>'25']); ?>
						<?php
						} else {
						?>
						<?= $form->field($model, 'qty_hari')->textInput(['class'=>'form-control float']); ?>
						<?php
						}
						// EOR
						?>
						<?= $form->field($model, 'saldo_awal')->textInput(['readonly'=>true,'class'=>'form-control float'])->label("Saldo Awal"); ?>
						<?= $form->field($model, 'total_realisasi')->textInput(['style'=>'font-weight:800','class'=>'form-control float','readonly'=>true])->label("Total Realisasi"); ?>
						<?= $form->field($model, 'saldo_akhir')->textInput(['readonly'=>true,'class'=>'form-control float'])->label("Saldo Akhir"); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#close-btn-modal2\').removeAttr(\'disabled\'); $(\'#close-btn-modal2\').trigger(\'click\'); getItemsRealisasiMakan('.$model->dkg_id.'); getSaldo();")'
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
function setAll(){
	var start = $('#<?= \yii\bootstrap\Html::getInputId($model, 'periode_awal') ?>').val();
	var end = $('#<?= \yii\bootstrap\Html::getInputId($model, 'periode_akhir') ?>').val();
	var wilayah_dinas_makan = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'wilayah_dinas_makan') ?>').val());
	var wilayah_dinas_pulsa = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'wilayah_dinas_pulsa') ?>').val());
	var saldo_awal = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'saldo_awal') ?>').val());
	<?php
	// REQUEST RAHMAN & KRISNA 
	// uang makan grader sengon fixed 25 hari, uang makan grader selain sengon menyesuaikan jumlah hari kerja
	if ($model->wilayah_dinas_nama == '(Jawa (Sengon)') {
	?>
		var qty_hari = 25;
    <?php
	} else {
	?>
		var qty_hari = 0;
		if(start && end){
			qty_hari = dateDaysPeriode(start,end) + 1;
		}
	    if( $("#<?= yii\helpers\Html::getInputId($model, "wilayah_dinas_nama") ?>").val() == "Jawa (Sengon)" ){
	        if(qty_hari > 30 ){ // untuk grader sengon dibuat maksimal 30 hari uang makannya
	            qty_hari = 30;
	        }
	    }
	<?php
	}
	// EOR
    ?>
	var total = (qty_hari * wilayah_dinas_makan) + wilayah_dinas_pulsa;
	var saldo_akhir = saldo_awal - total;
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'qty_hari') ?>').val(qty_hari);
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'total_realisasi') ?>').val( formatNumberForUser(total) );
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'saldo_akhir') ?>').val( formatNumberForUser(saldo_akhir) );
}
</script>