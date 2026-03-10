<?php
/* @var $this yii\web\View */
$this->title = 'Stock Log Sengon';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\RepeaterAsset::register($this);
app\assets\InputMaskAsset::register($this);
\app\assets\FileUploadAsset::register($this);
?>
<style>
.table#table-laporan > thead > tr > th {
    padding: 3px;
    font-size: 1.2rem;
}
.table#table-laporan > tbody > tr > td {
    padding: 2px;
    font-size: 1.2rem;
}
</style>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Stock Log Sengon'); ?></h1>
<!-- END PAGE TITLE -->
<!-- END PAGE HEADER -->
<!-- BEGIN EXAMPLE TABLE PORTLET -->
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/stocksengon/index") ?>"> <?= Yii::t('app', 'Available Stock'); ?> </a>
					</li>
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/mutasikeluarsengon/index") ?>"> <?= Yii::t('app', 'Mutasi Sengon'); ?> </a>
					</li>
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/stocksengon/riwayat") ?>"> <?= Yii::t('app', 'Riwayat Mutasi'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered form-search">
                            <div class="portlet-title">
                                <div class="tools panel-cari">
                                    <button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
                                    <span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
                                </div>
                            </div>
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
                                            <?php // echo $this->render('@views/apps/form/periodeTanggalByJenis', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
                                            <?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
                                        </div>
                                        <div class="col-md-5">
                                            <?php // echo $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionList("LS"),['prompt'=>'All'])->label(Yii::t('app', 'Suplier')); ?>
                                        </div>
                                    </div>
                                    <?php echo $this->render('@views/apps/form/tombolSearch') ?>
                                </div>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12"></div>
                    <div class="col-md-6 col-sm-12" style="text-align: right;">
                        <a class="btn btn-icon-only btn-default tooltips" onclick="create()" data-original-title="Tambah Mutasi Baru"><i class="icon-plus"></i></a>
                        <a class="btn btn-icon-only btn-default tooltips" onclick="printout('PRINT')" data-original-title="Print"><i class="fa fa-print"></i></a>
                        <a class="btn btn-icon-only btn-default tooltips" onclick="printout('EXCEL')" data-original-title="Excel"><i class="fa fa-table"></i></a>
                        <a class="btn btn-icon-only btn-default tooltips" onclick="printout('PDF')" data-original-title="PDF"><i class="fa fa-files-o"></i></a>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12" id="place-table-laporan"></div>
                </div>
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
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Persediaan Log Sengon'))."');
", yii\web\View::POS_READY); ?>
<script>
function getRekap(){
    $("#place-table-laporan").html("");
    $("#place-table-laporan").addClass("animation-loading");
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/mutasikeluarsengon/index']); ?>',
        type   : 'GET',
        data   : {laporan_params : $("#form-search-laporan").serialize()},
        success: function (data){
            if(data.html){
                $("#place-table-laporan").html(data.html);
                $("#place-table-laporan").removeClass("animation-loading");
                $("#table-laporan > tbody > tr").each(function(){
                    $(this).find(".tooltips").tooltip({ delay: 50 });
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function printout(caraPrint){
    var tgl_awal = $("#tmutasisengon-tgl_awal").val();
    var tgl_akhir = $("#tmutasisengon-tgl_akhir").val();
	window.open("<?= yii\helpers\Url::toRoute('/ppic/mutasikeluarsengon/print') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint+"&tgl_awal="+tgl_awal+"&tgl_akhir="+tgl_akhir,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/ppic/mutasikeluarsengon/create') ?>','modal-master-create');
}
</script>