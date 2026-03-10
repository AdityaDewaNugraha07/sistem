<?php
/* @var $this yii\web\View */
$this->title = 'Transaksi Downpayment';
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
    'id' => 'form-dp',
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
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/index"); ?>"> <?= Yii::t('app', 'PO Baru'); ?> </a>
                    </li>
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/podibuat"); ?>"> <?= Yii::t('app', 'PO Dibuat'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/spl/index"); ?>"> <?= Yii::t('app', 'SPL Baru'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/index"); ?>"> <?= Yii::t('app', 'SPP Masuk'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/sppmasuk"); ?>"> <?= Yii::t('app', 'SPP Masuk Detail'); ?> </a>
                    </li>
                    <li class="">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/sppberes"); ?>"> <?= Yii::t('app', 'SPP Complete'); ?> </a>
                    </li>
					<li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/dpbhp/index"); ?>"> <?= Yii::t('app', 'Downpayment'); ?> </a>
                    </li>
                </ul>
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-md-12">
						<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarDp()"><i class="fa fa-list"></i> <?= Yii::t('app', 'DP yang telah dibuat'); ?></a>
					</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Downpayment Baru'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?php 
										if(!isset($_GET['dp_bhp_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
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
										<?= $form->field($model, 'tanggal',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'cara_bayar')->dropDownList(\app\models\MDefaultValue::getOptionList('cara-bayar-dp'),['style'=>'padding:6px;','onchange'=>'setStatusBayar()']); ?>
										<?= $form->field($model, 'status')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionListBHP(),['class'=>'form-control select2','prompt'=>'']); ?>
										<?php // echo $form->field($model, 'nominal')->textInput(['class'=>'form-control money-format']); ?>
										<?= $form->field($model, 'nominal',['template'=>'{label}<div class="col-md-7">
													<span class="input-group-btn" style="width: 70%">{input}</span> 
													<span class="input-group-btn" style="width: 30%">'.\yii\bootstrap\Html::activeDropDownList($model, 'mata_uang', \app\models\MDefaultValue::getOptionListLabelValue('mata-uang'),['class'=>'form-control']).'</span> {error}</div>'])
													->textInput(['class'=>'form-control float','onblur'=>'setDimensi(); setMeterKubik();']); ?>
										<?= $form->field($model, 'keterangan')->textarea(); ?>
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
if(isset($_GET['dp_bhp_id'])){
    $pagemode = "afterSave()";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Bahan Pembantu'))."');
    $pagemode;
", yii\web\View::POS_READY); ?>
<script>
function save(){
    var $form = $('#form-dp');
    if(formrequiredvalidate($form)){
        submitform($form);
    }
    return false;
}

function setStatusBayar(){
	var cara_bayar = $('#<?= \yii\bootstrap\Html::getInputId($model, 'cara_bayar') ?>').val();
	if(cara_bayar == 'Cash'){
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'status') ?>').val("PAID");
	}else if(cara_bayar == 'Transfer'){
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'status') ?>').val("UNPAID");
	}
}

function afterSave(id){
	var total = 0;
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tdpbhp-tanggal').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
}

function daftarDp(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/dpbhp/daftarDpBhp']) ?>','modal-daftar-dp','75%');
}

</script>