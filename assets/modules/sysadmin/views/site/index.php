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
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/index"); ?>"> <?= Yii::t('app', 'General'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/systemconfig"); ?>"> <?= Yii::t('app', 'System Config'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/pengumuman/index"); ?>"> <?= Yii::t('app', 'Pengumuman'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/servermonitor"); ?>"> <?= Yii::t('app', 'Server Monitor'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/extensiontelepon/index"); ?>"> <?= Yii::t('app', 'Extension Telepon'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
						<div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Profil Perusahaan'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= $form->field($model, 'name')->textInput(['class'=>'form-control']); ?>
										<?= $form->field($model, 'director')->dropDownList(app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control'])->label("Director Name"); ?>
										<?= $form->field($model, 'year_since')->textInput(['class'=>'form-control float'])->label("Since"); ?>
										<?= $form->field($model, 'alamat')->textarea()->label("Alamat"); ?>
										<?= $form->field($model, 'phone')->textarea()->label("Phone"); ?>
										<?= $form->field($model, 'email')->textInput()->label("Email"); ?>
									</div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'site_host')->textInput(['class'=>'form-control'])->label("Site Host"); ?>
										<?= $form->field($model, 'db_host')->textInput(['class'=>'form-control'])->label("Database Host"); ?>
										<?= $form->field($model, 'db_name')->textInput(['class'=>'form-control'])->label("Database Name"); ?>
										<?= $form->field($model, 'db_username')->textInput(['class'=>'form-control'])->label("Database Username"); ?>
										<?= $form->field($model, 'db_password')->textInput(['class'=>'form-control'])->label("Database Password"); ?>
										<?= $form->field($model, 'screenlock_timeout')->textInput(['class'=>'form-control float'])->label("Screenlock Timeout"); ?>
									</div>
								</div>
                            </div>
                        </div>
						<div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" ", yii\web\View::POS_READY); ?>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<script>
function save(){
    var $form = $('#form-site');
    if(formrequiredvalidate($form)){
        submitform($form);
    }
    return false;
}
</script>