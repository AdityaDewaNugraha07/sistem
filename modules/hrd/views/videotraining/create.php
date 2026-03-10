<?php
/* @var $this yii\web\View */
/** @var TVideoTraining $model */

use app\models\TVideoTraining;
use yii\bootstrap\BootstrapAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
app\assets\Select2Asset::register($this);

$this->title = 'Video Training';
$today = date('d/m/Y');
$jam = date('H:i:s');
app\assets\DatatableAsset::register($this);
app\assets\FileUploadAsset::register($this);
$user_id = Yii::$app->user->id;
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->

<?php
$form = ActiveForm::begin([
    'id' => 'form-video-training',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-9">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-3 control-label'],
    ],
    'validateOnSubmit' => true,
])
?>

<div class="row" id="modal-limbah-create">
    <div class="col-md-12">
        <?= Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert');?>
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs pull-right">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/hrd/videotraining/index"); ?>"> <?= Yii::t('app', $this->title); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/hrd/videotraining/create"); ?>"> <?= Yii::t('app', 'Tambah '.$this->title); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12 portlet light">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="row">
                            <div class="col-md-9">
                                <?= $form->field($model, 'tgl_awal',[
                                    'template'=>'{label}    <div class="col-md-7">
                                                        <div class="input-group input-large date date-picker bs-datetime">
                                                            {input} 
                                                            <span class="input-group-addon">
													            <button class="btn default" type="button">
													                <i class="fa fa-calendar"></i>
													            </button>
													        </span>
													    </div> 
													        {error}
												    </div>'
                                ])->textInput(['readonly'=>'readonly', 'placeholder' => date('d-m-Y')]); ?>
                                <?= $form->field($model, 'tgl_akhir',[
                                    'template'=>'{label}    <div class="col-md-7">
                                                        <div class="input-group input-large date date-picker bs-datetime">
                                                            {input} 
                                                            <span class="input-group-addon">
													            <button class="btn default" type="button">
													                <i class="fa fa-calendar"></i>
													            </button>
													        </span>
													    </div> 
													        {error}
												    </div>'
                                ])->textInput(['readonly'=>'readonly', 'placeholder' => date('d-m-Y', strtotime("+7 day", time()))]); ?>
                                <?= $form->field($model, 'judul')->textInput(['placeholder' => 'Judul training']) ?>
                                <?= $form->field($model, 'deskripsi')->textarea(['class' => 'summernote']) ?>
                                <div class="form-group field-TVideoTraining-video">
                                    <label class="col-md-3 control-label" for="TVideoTraining-video">Video</label>
                                    <div class="col-md-9 input-video-container">
                                        <div class="input-group margin-bottom-5">
                                            <input type="text" class="form-control" name="video[]" id="TVideoTraining-video" placeholder="Link Video di Google Drive" />
                                            <span class="input-group-addon btn"
                                                  style="background-color: white"
                                                  data-toggle="popover"
                                            ><i class="fa fa-info-circle" style="color: #6f87d2;"></i>
                                            </span>
                                            <span class="input-group-addon btn add-input-video" style="background-color: white"><i class="fa fa-plus" style="color: rgb(113, 113, 113);"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group field-TVideoTraining-evaluasi_peserta">
                                    <label class="col-md-3 control-label" for="TVideoTraining-evaluasi_peserta">Link Evaluasi Peserta</label>
                                    <div class="col-md-9 input-evaluasi-peserta-container">
                                        <div class="input-group margin-bottom-5">
                                            <input type="text" class="form-control" name="evaluasi_peserta[]" id="TVideoTraining-evaluasi_peserta" placeholder="Link untuk evaluasi peserta training"/>
                                            <span class="input-group-addon btn add-input-evaluasi-peserta" style="background-color: white"><i class="fa fa-plus" style="color: rgb(113, 113, 113);"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group field-TVideoTraining-evaluasi_atasan">
                                    <label class="col-md-3 control-label" for="TVideoTraining-evaluasi_atasan">Link Evaluasi Atasan</label>
                                    <div class="col-md-9 input-evaluasi-atasan-container">
                                        <div class="input-group margin-bottom-5">
                                            <input type="text" class="form-control" name="evaluasi_atasan[]" id="TVideoTraining-evaluasi_atasan" placeholder="Link untuk evaluasi dari atasan"/>
                                            <span class="input-group-addon btn add-input-evaluasi-atasan" style="background-color: white"><i class="fa fa-plus" style="color: rgb(113, 113, 113);"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group margin-top-10">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9">
                                        <?= Html::submitButton( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn','id'=>'save']);?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
ActiveForm::end() 
?>

<style>
    .img-popover {
        width: -webkit-fill-available;
        width: -moz-available;
        width: fill-available;
        position: relative;
    }
    .popover{
        max-width: 70%;
    }
</style>
<?php $this->registerJs(" 
formconfig();
summernote();
popover();
addInputVideo();
addInputEvaluasiPeserta();
addInputEvaluasiAtasan();
setMenuActive('" . Json::encode(app\models\MMenu::getMenuByCurrentURL('Video Training')) . "');
", yii\web\View::POS_READY);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css', ['depends' => [BootstrapAsset::className()]]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js', ['depends' => [JqueryAsset::className()]]);
?>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script>
    function summernote() {
        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: 'Deskripsi video...'
            });
        });
    }

    function addInputVideo() {
        $('.add-input-video').on('click', function() {
            $('.input-video-container').append('<input type="text" class="form-control margin-bottom-5" name="video[]" placeholder="Tambah Link"/>')
        })
    }

    function addInputEvaluasiPeserta() {
        $('.add-input-evaluasi-peserta').on('click', function() {
            $('.input-evaluasi-peserta-container').append('<input type="text" class="form-control margin-bottom-5" name="evaluasi_peserta[]" placeholder="Tambah Link" />')
        })
    }

    function addInputEvaluasiAtasan() {
        $('.add-input-evaluasi-atasan').on('click', function() {
            $('.input-evaluasi-atasan-container').append('<input type="text" class="form-control margin-bottom-5" name="evaluasi_atasan[]" id="basic-url" placeholder="Tambah Link"/>')
        })
    }

    function popover() {
        $(document).ready(function () {
            $('[data-toggle="popover"]').popover({
                html: true,
                placement: 'bottom',
                title: 'Petunjuk',
                container: '.input-video-container',
                content: '<h3>1. Pilih "Dapatkan Link"</h3>' +
                    '<img src="<?= Url::base() . '/img/1.png' ?>" alt="" class="img-popover">' +
                    '<h3>2. Copy Link</h3>' +
                    '<img src="<?= Url::base() . '/img/2.png' ?>" alt="" class="img-popover">',
            });
        });
    }

</script>