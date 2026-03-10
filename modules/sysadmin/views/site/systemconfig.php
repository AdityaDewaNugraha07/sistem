<?php
/* @var $this yii\web\View */
$this->title = 'Site Configuration';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-site',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/index"); ?>"> <?= Yii::t('app', 'General'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/systemconfig"); ?>"> <?= Yii::t('app', 'System Config'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/pengumuman/index"); ?>"> <?= Yii::t('app', 'Pengumuman'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/extensiontelepon/index"); ?>"> <?= Yii::t('app', 'Extension Telepon'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/servermonitor"); ?>"> <?= Yii::t('app', 'Server Monitor'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/servermonitors"); ?>"> <?= Yii::t('app', 'Backup Monitor'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/cdb"); ?>"> <?= Yii::t('app', 'Compare Database'); ?> </a>
                    </li>   
                </ul>
                <div class="row">
                    <div class="col-md-6">
						<div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Display & Notification'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-6">Lock Screen</label>
													<div class="col-md-6" style="margin-top: 5px;">
														<input type="checkbox" checked class="make-switch" id="lockscreen" data-size="mini">
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-6">Notification</label>
													<div class="col-md-6" style="margin-top: 5px;">
														<?= yii\helpers\Html::activeCheckbox($model, 'notifikasi',['label'=>null,'class'=>'make-switch','data-size'=>'mini','onchange'=>'setNotification()']); ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-6">
						<div class="portlet light bordered">
							<div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cloud-download"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'System Updates'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
							<div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12 text-align-center">
										<button class="btn blue-steel" id="systemupdate" onclick="sysupdate();" type="button">Update!</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="portlet light bordered">
							<div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-layers"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Database Backup'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
							<div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12 text-align-center">
										<button class="btn blue" id="btn-backupdb" onclick="backupdb();" type="button">Backup DB!</button>
									</div>
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
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Site'))."');
", yii\web\View::POS_READY); ?>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<script>
function setNotification(){
	$("#<?= yii\bootstrap\Html::getInputId($model, "notifikasi") ?>").parents('.bootstrap-switch').addClass('animation-loading');
    var notif = $("#<?= yii\bootstrap\Html::getInputId($model, "notifikasi") ?>").is(':checked');
	if(notif){ notif = "ON"; }else{ notif = "OFF"; }
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/sysadmin/site/systemconfig']); ?>',
		type   : 'POST',
		data   : {update:true,notif:notif},
		success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "notifikasi") ?>").parents('.bootstrap-switch').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function sysupdate(){
	$('#systemupdate').addClass('animation-loading');
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/sysadmin/site/systemupdate']); ?>',
		type   : 'POST',
		data   : {},
		success: function (data) {
			if(data){
				cisAlert(data);
			}
			$('#systemupdate').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function backupdb(){
	$('#btn-backupdb').addClass('animation-loading');
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/sysadmin/site/backupdb']); ?>',
		type   : 'POST',
		data   : {},
		success: function (data) {
			if(data){
				cisAlert(data);
			}
			$('#btn-backupdb').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>