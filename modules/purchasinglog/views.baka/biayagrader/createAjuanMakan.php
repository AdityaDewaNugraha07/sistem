<?php 
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<div class="modal fade" id="modal-ajuanmakan" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi-ajuanmakan',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-6">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal2" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title text-align-center"><?= Yii::t('app', "Form Pengajuan Uang Makan Grader Pembelian Log"); ?> </h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-6">
						<?= yii\bootstrap\Html::activeHiddenInput($model, 'graderlog_id'); ?>
						<?= yii\bootstrap\Html::activeHiddenInput($model, 'dkg_id'); ?>
						<?= $form->field($model, 'kode')->textInput(['readonly'=>true])->label(Yii::t('app', 'Kode Dinas')); ?>
						<?= $form->field($model, 'periode_awal',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime" style="width:195px !important">{input} <span class="input-group-addon">
								<button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setQtyHari(); setTotalAjuan();'])->label('Periode Awal'); ?>
						<?= $form->field($model, 'periode_akhir',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime" style="width:195px !important">{input} <span class="input-group-addon">
								<button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setQtyHari(); setTotalAjuan();'])->label('Periode Akhir'); ?>
						<?= $form->field($model, 'graderlog_nm')->textInput(['readonly'=>true])->label("Nama Grader"); ?>
						<?= $form->field($model, 'grader_norek')->textInput(['readonly'=>true])->label("No. Rek"); ?>
						<?= $form->field($model, 'grader_bank')->textInput(['readonly'=>true])->label("Bank"); ?>
						<?= $form->field($model, 'kanit_grader')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control select2','prompt'=>''])->label('Kadep Grader'); ?>
						<?= $form->field($model, 'approved_by')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control select2','prompt'=>'']); ?>
					</div>
					<div class="col-md-6">
						<?= $form->field($model, 'wilayah_dinas_nama')->textInput(['readonly'=>true])->label("Wilayah Dinas"); ?>
						<?= $form->field($model, 'wilayah_dinas_makan')->textInput(['readonly'=>true,'class'=>'form-control float'])->label("Uang Makan /hari"); ?>
						<?= $form->field($model, 'wilayah_dinas_pulsa')->textInput(['class'=>'form-control float','onblur'=>'setTotalAjuan();'])->label("Uang Pulsa /bln"); ?>
						<?= $form->field($model, 'qty_hari')->textInput(['readonly'=>true,'class'=>'form-control float']); ?>
						<?= $form->field($model, 'saldo_sebelumnya')->textInput(['readonly'=>true,'class'=>'form-control float'])->label("Sisa Saldo"); ?>
						<?= $form->field($model, 'total_ajuan')->textInput(['style'=>'font-weight:800','class'=>'form-control float','readonly'=>true])->label("Total Ajuan"); ?>
						<?= $form->field($model, 'tanggal_dibutuhkan',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime" style="width:195px !important">{input} <span class="input-group-addon">
								<button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly'])->label('Tanggal Dibutuhkan'); ?>
						<?= $form->field($model, 'keterangan')->textarea(); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Ajukan Uang Makan'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#close-btn-modal2\').removeAttr(\'disabled\'); $(\'#close-btn-modal2\').trigger(\'click\'); getItemsAjuanMakan('.$model->dkg_id.');  getSaldo();")'
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
$.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script>
function setQtyHari(){
	var start = $('#<?= \yii\bootstrap\Html::getInputId($model, 'periode_awal') ?>').val();
	var end = $('#<?= \yii\bootstrap\Html::getInputId($model, 'periode_akhir') ?>').val();
	var qty_hari = 0;
	if(start && end){
		qty_hari = dateDaysPeriode(start,end) + 1;
	}
    if( $("#<?= yii\helpers\Html::getInputId($model, "wilayah_dinas_nama") ?>").val() == "Jawa (Sengon)" ){
        if(qty_hari > 25 ){ // untuk grader sengon dibuat maksimal 30 hari uang makannya di ubah per tgl 05/02/2020 menjadi max 25 hari
            qty_hari = 25;
        }
    }
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'qty_hari') ?>').val(qty_hari);
}
function setTotalAjuan(){
	var qty_hari = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'qty_hari') ?>').val());
	var wilayah_dinas_makan = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'wilayah_dinas_makan') ?>').val());
	var wilayah_dinas_pulsa = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'wilayah_dinas_pulsa') ?>').val());
	var saldo_sebelumnya = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'saldo_sebelumnya') ?>').val());
	var total = ((qty_hari * wilayah_dinas_makan) + wilayah_dinas_pulsa)-saldo_sebelumnya;
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'total_ajuan') ?>').val( formatNumberForUser(total) );
}
</script>