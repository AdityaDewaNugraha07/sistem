<?php
/* @var $this yii\web\View */
$this->title = 'Kas Besar';
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
    'id' => 'form-setorbank',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/kasbesar/index"); ?>"> <?= Yii::t('app', 'Penerimaan Kas Besar'); ?> </a>
                    </li>
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/kasbesar/kasbon"); ?>"> <?= Yii::t('app', 'Bon Kas Besar'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/saldokasbesar/index"); ?>"> <?= Yii::t('app', 'Laporan Kas Besar'); ?> </a>
                    </li>
					<li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/setorbank/index"); ?>"> <?= Yii::t('app', 'Setor Bank'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/rekapkasbesar/index"); ?>"> <?= Yii::t('app', 'Rekap Kas Besar'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/terimanontunai/index"); ?>"> <?= Yii::t('app', 'Penerimaan Non-Tunai'); ?> </a>
                    </li>
                </ul>
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-md-12">
						<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarDp()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Histori Setor Tunai'); ?></a>
					</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Setor Tunai Kas Besar'); ?></h4></span>
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
										if(!isset($_GET['kas_besar_setor_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
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
										<?= $form->field($model, 'reff_no_bank')->textInput(['class'=>'form-control']); ?>
										<?= $form->field($model, 'reff_no_dokangkut')->textInput(['class'=>'form-control']); ?>
										<?= $form->field($model, 'tanggal',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'nominal')->textInput(['class'=>'form-control money-format']); ?>
										<?= $form->field($model, 'deskripsi')->textarea(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
								<?php 
									if(isset($_GET['kas_besar_setor_id'])){
										$disabled = FALSE;
									}else{
										$disabled = TRUE;
									}
								?>
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
if(isset($_GET['kas_besar_setor_id'])){
    $pagemode = "afterSave()";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Kas Besar'))."');
	formconfig();
    $pagemode;
", yii\web\View::POS_READY); ?>
<script>
function save(){
    var $form = $('#form-setorbank');
    if(formrequiredvalidate($form)){
        submitform($form);
    }
    return false;
}
function afterSave(id){
	var total = 0;
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
}

function daftarDp(){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/setorbank/daftarAftersave']) ?>','modal-daftar-setortunai','75%');
}

</script>