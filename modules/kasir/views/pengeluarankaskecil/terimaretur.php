<?php
/* @var $this yii\web\View */
$this->title = 'Kas Kecil';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-terimaretur',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-3 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/sementara"); ?>"> <?= Yii::t('app', 'Bon Kas Kecil'); ?> </a>
                    </li>
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/index"); ?>"> <?= Yii::t('app', 'Pengeluaran Kas Kecil'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/saldokaskecil/index"); ?>"> <?= Yii::t('app', 'Laporan Kas Kecil'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/rekapkaskecil/index"); ?>"> <?= Yii::t('app', 'Rekap Kas Kecil'); ?> </a>
                    </li>
					<li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/terimaretur"); ?>"> <?= Yii::t('app', 'Terima Uang Retur'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Penerimaan Uang Dari Retur BHP'); ?></h4></span>
                                </div>
                                <div class="tools">
									<a class="btn btn-sm btn-outline blue" id="btn-closing" onclick="penerimaanRetur();" style="margin-top: 10px; height: 28px;"><i class="icon-speedometer"></i> <?= Yii::t('app', 'Retur Yang Sudah Diterima'); ?></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?php 
										if(!isset($_GET['kas_kecil_id'])){
											echo $form->field($model, 'retur_bhp_id')->dropDownList(\app\models\TReturBhp::getOptionListKasKecil(),['prompt'=>'','class'=>'form-control select2','style'=>'margin-top:10px; width: 180px','onchange'=>'setRetur()'])->label('Kode');
										}else{ ?>
											<div class="form-group">
												<label class="col-md-3 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										
										<?= $form->field($model, 'tanggal')->textInput(['disabled'=>'disabled'])->label("Tanggal"); ?>
										<?= $form->field($model, 'bhp_nm')->textInput(['disabled'=>'disabled'])->label("Nama Item"); ?>
										<?= $form->field($model, 'deskripsi')->textarea(['disabled'=>'disabled','style'=>'font-size:1.3rem'])->label("Keterangan"); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'harga_terima')->textInput(['class'=>'form-control float','disabled'=>'disabled'])->label("Harga Terima"); ?>
										<?= $form->field($model, 'potongan')->textInput(['class'=>'form-control float','disabled'=>'disabled'])->label("Potongan"); ?>
										<?= $form->field($model, 'harga')->textInput(['class'=>'form-control float','disabled'=>'disabled'])->label("Harga Retur"); ?>
										<?= $form->field($model, 'qty')->textInput(['class'=>'form-control float','disabled'=>'disabled'])->label("Qty Retur"); ?>
										<?= $form->field($model, 'total_kembali')->textInput(['class'=>'form-control float','disabled'=>'disabled'])->label("Total Kembali"); ?>
                                    </div>
                                </div>
								<hr>
								<div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Kas Masuk'); ?></h4></span>
                                </div>
								<div class="row">
									<div class="col-md-6">
										<?= yii\bootstrap\Html::activeHiddenInput($modKasKecil, 'kas_kecil_id',['style'=>'width:50px;']); ?>
										<?= $form->field($modKasKecil, 'tanggal',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($modKasKecil, 'penerima')->textInput(['disabled'=>'disabled'])->label("Penerima"); ?>
										<?= $form->field($modKasKecil, 'nominal')->textInput(['class'=>'form-control float','disabled'=>'disabled'])->label("Debit"); ?>
									</div>
									<div class="col-md-6">
										<?= $form->field($modKasKecil, 'tbp_reff')->textInput(['disabled'=>'disabled'])->label("Kode TBP"); ?>
										<?= $form->field($modKasKecil, 'deskripsi')->textarea(['style'=>'font-size:1.2rem'])->label("Deskripsi"); ?>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['kas_kecil_id'])){
    $pagemode = "afterSave()";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Kas Kecil'))."');
	formconfig();
    $pagemode;
	$('.select2').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Retur',
		width: null,
	});
", yii\web\View::POS_READY); ?>
<script>
function setRetur(){
	var retur_bhp_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'retur_bhp_id') ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/getDataRetur']); ?>',
        type   : 'POST',
        data   : {retur_bhp_id:retur_bhp_id},
        success: function (data){
			if(data){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val(data.tanggal);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'bhp_nm') ?>').val(data.bhp_nm);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'deskripsi') ?>').val(data.deskripsi);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'harga_terima') ?>').val(data.harga_terima);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'potongan') ?>').val(data.potongan);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'harga') ?>').val(data.harga);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'qty') ?>').val(data.qty);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'total_kembali') ?>').val(data.total_kembali);
				$('#<?= yii\bootstrap\Html::getInputId($modKasKecil, 'tanggal') ?>').val(data.tanggalkas);
				$('#<?= yii\bootstrap\Html::getInputId($modKasKecil, 'penerima') ?>').val(data.penerima);
				$('#<?= yii\bootstrap\Html::getInputId($modKasKecil, 'nominal') ?>').val(data.total_kembali);
				$('#<?= yii\bootstrap\Html::getInputId($modKasKecil, 'deskripsi') ?>').val(data.deskripsikas);
				$('#<?= yii\bootstrap\Html::getInputId($modKasKecil, 'tbp_reff') ?>').val(data.tbp_reff);
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function save(){
    var $form = $('#form-terimaretur');
    if(formrequiredvalidate($form)){
        submitform($form);
    }
    return false;
}

function penerimaanRetur(){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/returDiterima']) ?>','modal-returditerima','75%');
}

function afterSave(){
	$('input[name*="kode"]').attr('disabled','disabled');
	$('textarea[name*="deskripsi"]').attr('disabled','disabled');
	$('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
}

</script>