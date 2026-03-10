<?php
/* @var $this yii\web\View */
$this->title = 'Purchase Order Log Sengon';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\RepeaterAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Purchase Order Log Sengon'); ?></h1>
<!-- END PAGE TITLE -->
<!-- END PAGE HEADER -->
<!-- BEGIN EXAMPLE TABLE PORTLET -->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<?php
if(isset($_GET['posengon_id'])){
	$display['none'] = 'none';
	$display['disabled'] = TRUE;
}else{
	$display['none'] = '';
	$display['disabled'] = FALSE;
}
?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar PO Sengon'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Purchase Order'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']); ?>
										<?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionList('LS'),['class'=>'form-control select2','prompt'=>'','onchange'=>'setSupplier()']); ?>
										<?= $form->field($model, 'kepada')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'alamat')->textArea(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'hp')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'disetujui_supplier')->textInput(); ?>
										<?= $form->field($model, 'disetujui_direktur')->dropDownList(\app\models\MPegawai::getOptionList(),['class'=>'form-control select2','prompt'=>'']); ?>
										<?= $form->field($model, 'nama_barang')->textInput(); ?>
										<?= $form->field($model, 'panjang')->textInput(); ?>
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Kuantitas'); ?></label>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 30%">
													<?php echo \yii\helpers\Html::activeTextInput($model, 'kuantiti',['class'=>'form-control money-format']) ?>
												</span>
												<span class="input-group-addon textarea-addon " style="text-align: left; width: 70%; background-color: #fff; border: 0;"> m<sup>3</sup> </span>
												<span class="help-block"></span>
											</div>
										</div>
                                    </div>
                                    <div class="col-md-6">
										<div class="form-group" style="margin-top: 10px; margin-bottom: 10px;">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Diameter / Harga'); ?></label>
											<div class="col-md-8">
												<div class="repeater">
													<div data-repeater-list="<?= \yii\helpers\StringHelper::basename(get_class($model));  ?>">
														<?php
														if( (count($model->diameter_harga)>0) && is_array($model->diameter_harga) ){
															foreach($model->diameter_harga as $i => $item){ ?>
															<div data-repeater-item style="display: block; width: 85%">
																<span class="input-group-btn" style="width: 40%">
																	<?php echo \yii\bootstrap\Html::activeDropDownList($model, 'diameter_harga', \app\models\MDefaultValue::getOptionList('diameter-range-sengon'),['class'=>'form-control','value'=>$i]); ?>
																</span>
																<span class="input-group-btn" style="width: 40%">
																	<?php echo yii\bootstrap\Html::activeTextInput($model, 'diameter_harga[]',['class'=>'form-control money-format','placeholder'=>'Harga','disabled'=>$display['disabled'],'value'=> app\components\DeltaFormatter::formatNumberForUser($item)]) ?>
																</span>
																<span class="input-group-btn" style="width: 10%; display: <?= $display['none'] ?>;" id="remove-btn">
																	<a href="javascript:;" data-repeater-delete class="btn btn-danger"><i class="fa fa-minus"></i></a>
																</span>
															</div>
															<?php } ?>
														<?php } ?>
													</div>
													<a href="javascript:;" data-repeater-create class="btn btn-xs btn-info mt-repeater-add" style="margin-top: 5px; margin-bottom: 10px; display: <?= $display['none'] ?>">
														<i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?>
													</a>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Periode Pengiriman'); ?></label>
											<div class="col-md-7">
												<span class="input-group-btn">
													<?= $form->field($model, 'tgl_awal',[
																'template'=>'<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-btn">
																			 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																			 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
													
												</span>
												<span class="input-group-addon textarea-addon" style="text-align: left; width: 100%; background-color: #fff; border: 0;"> sd </span>
													<?= $form->field($model, 'tgl_akhir',[
																'template'=>'<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-btn">
																			 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																			 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
											<span class="help-block"></span>
											</div>
										</div>
										<?= $form->field($model, 'cara_bayar')->textarea(); ?>
										<?= $form->field($model, 'rekening_bank')->textarea(); ?>
                                    </div>
                                </div>
								<br><br><hr>
								<div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Spesifikasi Log Sengon'); ?></h4>
                                    </div>
                                    <div class="col-md-7">
										
                                    </div>
                                </div>
								<div class="row">
									<div class="col-md-12">
										<div class="repeater">
											<div data-repeater-list="<?= \yii\helpers\StringHelper::basename(get_class($model));  ?>">
												<?php
												if( (count($model->spesifikasi_log)>0) && is_array($model->spesifikasi_log) ){
													foreach($model->spesifikasi_log as $i => $item){ ?>
													<div data-repeater-item style="display: block; width: 100%; margin-bottom: 5px;">
														<span class="input-group-btn" style="width: 40%; vertical-align: top;">
															<?php echo yii\bootstrap\Html::activeTextInput($model, 'spesifikasi_log[]',['class'=>'form-control','value'=>(!is_numeric($i)?$i:"")]); ?>
														</span>
														<span class="input-group-addon textarea-addon" style="vertical-align: top; text-align: left; background-color: #fff; border: 0;"> : </span>
														<span class="input-group-btn" style="width: 55%">
															<?php echo yii\bootstrap\Html::activeTextarea($model, 'spesifikasi_log[]',['class'=>'form-control','rows'=>2,'value'=> $item]); ?>
														</span>
														<span class="input-group-btn" style="width: 5%; vertical-align: top; display: <?= $display['none'] ?>" id="remove-btn">
															<a href="javascript:;" data-repeater-delete class="btn btn-danger"><i class="fa fa-close"></i></a>
														</span>
													</div>
													<?php } ?>
												<?php } ?>
											</div>
											<a href="javascript:;" data-repeater-create class="btn btn-xs btn-info mt-repeater-add" style="margin-top: 5px; margin-bottom: 10px; display : <?= $display['none']; ?>">
												<i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Spesifikasi'); ?>
											</a>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'submitform()']); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','disabled'=>true,'onclick'=>'printout()']); ?>
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
if(isset($_GET['posengon_id'])){
    $pagemode = "afterSave()";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
	$('.date-picker').datepicker({ clearBtn:false });
	formconfig();
    $pagemode;
	$(this).find('select[name*=\"[suplier_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Supplier',
		width: null
	});
	$(this).find('select[name*=\"[disetujui_direktur]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Pegawai',
		width: null
	});
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
<script>
function setSupplier(){
	var suplier_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/posengon/setSupplier']); ?>',
		type   : 'POST',
		data   : {suplier_id:suplier_id},
		success: function (data) {
			if(data.model){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'kepada') ?>').val(data.model.suplier_nm);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'alamat') ?>').val(data.model.suplier_almt);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'hp') ?>').val(data.model.suplier_phone);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'disetujui_supplier') ?>').val(data.model.suplier_nm);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'rekening_bank') ?>').val('Bank '+data.model.suplier_bank+' '+data.model.suplier_norekening+' an. '+data.model.suplier_an_rekening);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function afterSave(id){
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tposengon-tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#tposengon-tgl_awal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#tposengon-tgl_akhir').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/posengon/daftarAfterSave']) ?>','modal-aftersave','75%');
}

function printout(){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/posengon/printout') ?>?posengon_id=<?= (isset($_GET['posengon_id'])?$_GET['posengon_id']:"") ?>","",'location=_new, width=1200px, scrollbars=1');
}
</script>