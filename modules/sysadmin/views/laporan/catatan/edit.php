<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Edit Catatan';
$today = date('d/m/Y');
app\assets\DatatableAsset::register($this);
app\assets\FileUploadAsset::register($this);

?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->

<?php
$form = ActiveForm::begin([
    'id' => 'form-catatan',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-5 control-label'],
    ],
    'validateOnSubmit' => true,
])
?>

<div class="row" id="modal-limbah-create">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs pull-right">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/laporan/catatan"); ?>"> <?= Yii::t('app', 'Catatan'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/laporan/catatanCreate"); ?>"> <?= Yii::t('app', 'Tambah Catatan'); ?> </a>
                    </li>
                    <li class="">
                        <a href="javascript:;" class="reload"> <i class="fa fa-refresh"></i> </a>
                    </li>
                    <li class="">
                        <a href="javascript:;" class="fullscreen"> <i class="fa fa-expand"></i>  </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12 portlet light bordered">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <?= $form->field($model, 'user_id')->label(false)->hiddenInput(['value' => $model->user_id]); ?>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label for="tanggal" class="col-sm-2 col-form-label text-right">Tanggal</label>
                                <div>
                                    <?= $form->field($model, 'tanggal',[
                                                                'template'=>'<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                {error}</div>'])->textInput(['readonly'=>'readonly', 'value' => $model->tanggal]) ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-2 col-form-label text-right">Judul</label>
                                <div>
                                    <?= $form->field($model, 'judul')->label(false)->textinput(['placeholder' => 'judul', '$p']); ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-2 col-form-label text-right">Keterangan</label>
                                <div>
                                    <?= $form->field($model, 'keterangan')->label(false)->textarea(['rows' => '23', 'placeholder' => 'keterangan', 'value' => $model->keterangan]); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label for="Gambar" class="col-sm-2 col-form-label text-right">Gambar</label>
                                <div>
                                    <?php
                                    if ($model->catatan_gambar != '') {
                                        $src = Yii::getAlias('@web')."/uploads/catatan/".$model->catatan_gambar;
                                    } else {
                                        $src = Yii::$app->view->theme->baseUrl."/cis/img/no-image.png";
                                    }
                                    echo $form->field($model, 'catatan_gambar',[
                                        'template'=>'
                                            <div class="col-md-8">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                        <img src="'.$src.'" class="img-responsive pic-bordered" alt="" width="120">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                    <div>
                                                        <span class="btn blue-hoki btn-outline btn-file">
                                                            <span class="fileinput-new"> Select image </span>
                                                            <span class="fileinput-exists"> Change </span>
                                                            {input} 
                                                        </span> 
                                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                        {error}
                                                    </div>
                                                </div>
                                            </div>'
                                    ])->fileInput();
                                    ?>
                                </div>
                        </div>
                        <br>
                        <div class="col-sm-12">
                            <div class="form-gropu row">
                                <label for="Submit" class="col-sm-2 col-form-label text-right"></label>
                                <div class="col-sm-10" style="margin-left: -10px;">
                                    <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn','id'=>'save'
                                        ]);
                                    ?>                                    
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

<?php $this->registerJs(" 
    formconfig();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Catatan'))."');
    ", yii\web\View::POS_READY); 
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>
$("#save").click(function(){
    var tanggal = $("#tanggal").val();
    var keterangan = $("#keterangan").val();
    if (tanggal != '' || keterangan != '') {
        $("#form-catatan").submit();
    }
});
</script>