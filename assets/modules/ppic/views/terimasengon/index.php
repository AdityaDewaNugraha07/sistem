<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan Log Sengon';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\RepeaterAsset::register($this);
app\assets\InputMaskAsset::register($this);
\app\assets\FileUploadAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Penerimaan Log Sengon'); ?></h1>
<!-- END PAGE TITLE -->
<!-- END PAGE HEADER -->
<!-- BEGIN EXAMPLE TABLE PORTLET -->
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/terimasengon/index") ?>"> <?= Yii::t('app', 'Penerimaan Sengon'); ?> </a>
					</li>
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/terimasengon/rekap") ?>"> <?= Yii::t('app', 'Rekap Penerimaan'); ?> </a>
					</li>
                    <li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/terimasengon/afkir") ?>"> <?= Yii::t('app', 'Afkir'); ?> </a>
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
                                            <?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
                                        </div>
                                        <div class="col-md-5">
                                            <?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionList("LS"),['prompt'=>'All'])->label(Yii::t('app', 'Suplier')); ?>
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
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-laporan">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="line-height: 1; width: 120px;"><?= Yii::t('app', 'Kode Input'); ?></th>
                                    <th><?= Yii::t('app', 'Suplier'); ?></th>
                                    <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Tanggal<br>Terima') ?></th>
                                    <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Nopol<br>Kendaraan') ?></th>
                                    <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'No<br>Urut') ?></th>
                                    <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Kode<br>Terima') ?></th>
                                    <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Kedatangan<br>Ke') ?></th>
                                    <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Kode<br>Jenis') ?></th>
                                    <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'D') ?></th>
                                    <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'P') ?></th>
                                    <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Pcs') ?></th>
                                    <th style="line-height: 1; width: 60px;"><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                    <th style=""></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
    $('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
	formconfig();
    dtLaporan();
	changePertanggalLabel();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/ppic/terimasengon/index') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, visible: false,},
            { 	targets: 1, class: "td-kecil text-align-center"},
            { 	targets: 2, class: "td-kecil text-align-left"},
			{ 	targets: 3, class: "td-kecil text-align-center", 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return date;
                }
            },
            { 	targets: 4, class: "td-kecil text-align-center"},
            { 	targets: 5, class: "td-kecil text-align-center"},
            { 	targets: 6, class: "td-kecil text-align-center"},
            { 	targets: 7, class: "td-kecil text-align-center"},
            { 	targets: 8, class: "td-kecil text-align-center"},
            { 	targets: 9, class: "td-kecil text-align-center"},
            { 	targets: 10, class: "td-kecil text-align-center"},
            { 	targets: 11, class: "td-kecil text-align-center"},
            { 	targets: 12, class: "td-kecil text-align-right"},
            { 	targets: 13, visible: false,},
        ],
		"fnDrawCallback": function( oSettings ) {
            <?php if( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER) || (Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_PPIC) ){ ?>
			formattingDatatableThis(oSettings.sTableId);
            <?php } ?>
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		order: [
            [13, 'desc'],
            [5, 'asc'], 
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}
function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/ppic/terimasengon/print') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function formattingDatatableThis(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-icon-only btn-default tooltips' onclick='importFromExcel()' data-original-title='Import from Excel'><i class='icon-arrow-down'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='deleteByKodeInput()' data-original-title='Hapus by kode input'><i class='icon-trash'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PRINT\")' data-original-title='Print Out'><i class='fa fa-print'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
    $(".tooltips").tooltip({ delay: 50 });
}

function importFromExcel(){
	openModal('<?= \yii\helpers\Url::toRoute('/ppic/terimasengon/importexcel') ?>','modal-importexcel');
}

function cariPosengon(){
    var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/cariPoSengon','pick'=>'1']); ?>';
	$(".modals-place-2").load(url, function() {
		$("#modal-posengon .modal-dialog").css('width','90%');
		$("#modal-posengon").modal('show');
		$("#modal-posengon").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function pick(kode,suplier_id){
	$("#modal-posengon").find('button.fa-close').trigger('click');
    $("#modal-importexcel").find("input[name*='[posengon_kode]']").val(kode);
    $("#modal-importexcel").find("select[name*='[suplier_id]']").val(suplier_id);
    $("#place-berkas-reff").html('<a id="btn-reff-1" class="btn btn-outline btn-xs purple" onclick="detailPoByKode(\''+kode+'\')"><i class="icon-tag"></i> Lihat PO</a>');
}
function detailPoByKode(kode){
    var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/detailPoByKode','kode'=>'']); ?>'+kode;
	$(".modals-place-2").load(url, function() {
		$("#modal-detailpo .modal-dialog").css('width','22cm');
		$("#modal-detailpo").modal('show');
		$("#modal-detailpo").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function deleteByKodeInput(){
	openModal('<?= \yii\helpers\Url::toRoute('/ppic/terimasengon/deleteByKodeInput') ?>','modal-deletebykode');
}
</script>