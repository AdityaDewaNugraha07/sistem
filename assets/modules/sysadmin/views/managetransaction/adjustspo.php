<?php
/* @var $this yii\web\View */
$this->title = 'Manage Transaction';
app\assets\DatatableAsset::register($this);
app\assets\InputMaskAsset::register($this);
\app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Manage Transaction'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
#table-produk tbody tr td{
	font-size: 1.3rem;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/sysadmin/managetransaction/stockproduk"); ?>"> <?= Yii::t('app', 'Stock Produk'); ?> </a>
                    </li>
                    <li class="active">
						<a href="<?= yii\helpers\Url::toRoute("/sysadmin/managetransaction/adjustspo"); ?>"> <?= Yii::t('app', 'Adjust SPO'); ?> </a>
                    </li>
                </ul>
                <div class="portlet-body">
					<?php $form = \yii\bootstrap\ActiveForm::begin([
						'id' => 'form-search',
						'fieldConfig' => [
							'template' => '{label}<div class="col-md-8">{input} {error}</div>',
							'labelOptions'=>['class'=>'col-md-3 control-label'],
						],
						'enableClientValidation'=>false
					]); ?>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<?= $form->field($modSPODetail, 'bhp_id')->dropDownList([],['prompt'=>'','onchange'=>'setValue()'])->label(Yii::t('app', 'BHP')); ?>
							</div>
							<div class="col-md-6">
								<?= $form->field($modSPODetail, 'suplier_id')->dropDownList([],['prompt'=>'','onchange'=>'setValue()'])->label(Yii::t('app', 'Suplier')); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label"><?= Yii::t('app', 'bhp_id'); ?></label>
									<div class="col-md-5" style="padding-bottom: 5px;">
										<span class="input-group-btn" style="width: 90%">
											<?= \yii\bootstrap\Html::activeTextInput($modSPODetail, 'bhp_id_display', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
										</span>
										<span class="input-group-btn" style="width: 10%">
											<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyah(this);">
												<i class="icon-paper-clip"></i>
											</a>
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label"><?= Yii::t('app', 'suplier_id'); ?></label>
									<div class="col-md-5" style="padding-bottom: 5px;">
										<span class="input-group-btn" style="width: 90%">
											<?= \yii\bootstrap\Html::activeTextInput($modSPODetail, 'suplier_id_display', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
										</span>
										<span class="input-group-btn" style="width: 10%">
											<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyah(this);">
												<i class="icon-paper-clip"></i>
											</a>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
					<?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
					<?php \yii\bootstrap\ActiveForm::end(); ?>
				</div>
				<div class="portlet-body">
                    spod_keterangan ILIKE '%INJECT%'
					<div class="row">
						<div class="col-md-6" style="padding-left: 2px; padding-right: 2px;">
							<div class="portlet light custom-bordered" style="padding-left: 5px; padding-right: 5px;">
								<div class="portlet-title">
									<div class="caption"><center><?= Yii::t('app', 'SPP'); ?></center></div>
									<div class="pull-right">
									</div>
								</div>
								<div class="portlet-body" id="place-spp">
									
								</div>
							</div>
						</div>
						<div class="col-md-6" style="padding-left: 2px; padding-right: 2px;">
							<div class="portlet light custom-bordered" style="padding-left: 5px; padding-right: 5px;">
								<div class="portlet-title">
									<div class="caption"><center><?= Yii::t('app', 'SPO'); ?></center></div>
									<div class="pull-right">
									</div>
								</div>
								<div class="portlet-body" id="place-spo">
									
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs("
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Administrator'))."');
	$('#".\yii\bootstrap\Html::getInputId($modSPODetail, 'bhp_id')."').select2({
		allowClear: !0,
		placeholder: 'Ketik BHP',
		width: '250px',
		ajax: {
			url: '". \yii\helpers\Url::toRoute('/logistik/spb/FindBhpActive') ."',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		}
	});
	$('#".\yii\bootstrap\Html::getInputId($modSPODetail, 'suplier_id')."').select2({
		allowClear: !0,
		placeholder: 'Ketik Suplier',
		width: '250px',
		ajax: {
			url: '". \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/FindSupplier') ."',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		}
	});
	setValue();
", yii\web\View::POS_READY); ?>
<script>
function setValue(){
	var bhp_id = $("#<?= yii\helpers\Html::getInputId($modSPODetail, "bhp_id") ?>").val();
	var suplier_id = $("#<?= yii\helpers\Html::getInputId($modSPODetail, "suplier_id") ?>").val();
	$("#<?= yii\helpers\Html::getInputId($modSPODetail, "bhp_id_display") ?>").val(bhp_id);
	$("#<?= yii\helpers\Html::getInputId($modSPODetail, "suplier_id_display") ?>").val(suplier_id);
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/sysadmin/managetransaction/adjustspo']); ?>',
		type   : 'POST',
		data   : {bhp_id:bhp_id,suplier_id:suplier_id},
		success: function (data) {
			if(data.spp){
				$("#place-spp").html(data.spp);
			}
			if(data.spo){
				$("#place-spo").html(data.spo);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function copyah(ele){
	var text = $(ele).parents(".form-group").find("input[name*='[bhp_id_display]']").val();
	copyToClipboard(text);
}

</script>