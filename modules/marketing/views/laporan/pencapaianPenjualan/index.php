<?php
/* @var $this yii\web\View */
$this->title = 'Target Pencapaian Penjualan';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\RepeaterAsset::register($this);
app\assets\InputMaskAsset::register($this);
\app\assets\FileUploadAsset::register($this);
?>
<style>
.table#table-laporan > thead > tr > th {
    padding: 1px;
    font-size: 1.2rem;
}
.table#table-laporan > tbody > tr > td {
    padding: 1px;
    font-size: 1.2rem;
}
</style>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Target Pencapaian Penjualan'); ?></h1>
<!-- END PAGE TITLE -->
<!-- END PAGE HEADER -->
<!-- BEGIN EXAMPLE TABLE PORTLET -->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light bordered form-search">
                        <div class="portlet-body">
                            <?php $form = \yii\bootstrap\ActiveForm::begin([
                                'id' => 'form-search-laporan',
                                'fieldConfig' => [
                                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                                    'labelOptions'=>['class'=>'col-md-3 control-label'],
                                ],
                                'enableClientValidation'=>false
                            ]); ?>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-8">
                                                <?= $form->field($model, 'periode')->dropDownList(\app\models\TTargetPenjualan::getOptionListPeriodeTahun(),['prompt'=>'-']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <?php echo \yii\helpers\Html::button( Yii::t('app', 'Search'),[
                                            'class'=>'btn hijau btn-outline ciptana-spin-btn pull-left',
                                            'type'=>'submit',
                                            'name'=>'search-laporan',
                                            ]);
                                        ?>
                                    </div>
                                </div>
                                <?php // echo $this->render('@views/apps/form/tombolSearch') ?>
                            </div>
                            <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
                            <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
                            <?php \yii\bootstrap\ActiveForm::end(); ?>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
<!--            <div class="row">
                <div class="col-md-6 col-sm-12 pull-right" style="margin-top: -15px;margin-bottom: 10px;">
                    <a class="btn btn-icon-only btn-default tooltips pull-right" onclick="printout('EXCEL')" data-original-title="Export to Excel" style="margin-left: 5px;"><i class="fa fa-table"></i></a>
                    <a class="btn btn-icon-only btn-default tooltips pull-right" onclick="printout('PRINT')" data-original-title="Print Out" ><i class="fa fa-print"></i></a>
                </div>
            </div>-->
            <div class="row">
                <div class="col-md-12" id="place-table-laporan"></div>
            </div>
        </div>
    </div>
</div>

<?php $this->registerJs(" 
    $('#form-search-laporan').submit(function(){
		getRekap();
		return false;
	});
	formconfig();
        getRekap();
	changePertanggalLabel();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Target Pencapaian Penjualan'))."');
", yii\web\View::POS_READY); ?>
<script>
function getRekap(){
    $("#place-table-laporan").html("");
    $("#place-table-laporan").addClass("animation-loading");
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/laporan/PencapaianPenjualan/']); ?>',
        type   : 'GET',
        data   : {laporan_params : $("#form-search-laporan").serialize()},
        success: function (data){
            if(data.html){
                $("#place-table-laporan").html(data.html);
                $("#place-table-laporan").removeClass("animation-loading");
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/marketing/laporan/pencapaianPenjualan/printRekap') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'periode');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'periode');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>