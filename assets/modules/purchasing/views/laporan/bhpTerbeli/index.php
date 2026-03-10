<?php
/* @var $this yii\web\View */
$this->title = 'Bahan Pembantu Terbeli';
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet light bordered">
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
										<div class="col-md-2"></div>
										<div class="col-md-7">
											<?php echo $form->field($model, 'bhp_nm')->textInput()->label(Yii::t('app', 'Cari Nama BHP')); ?>
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
                    <div class="col-md-6">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Pembelian item BHP menggunakan PO'); ?></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<table class="table table-striped table-bordered table-hover" id="table-laporan">
									<thead>
										<tr>
											<th><?= Yii::t('app', 'No.'); ?></th>
											<th><?= Yii::t('app', 'Nama BHP') ?></th>
											<th><?= Yii::t('app', 'Total SPO') ?></th>
											<th><?= Yii::t('app', 'Total Item') ?></th>
											<th></th>
										</tr>
									</thead>
								</table>
							</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Pembelian item BHP menggunakan SPL'); ?></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<table class="table table-striped table-bordered table-hover" id="table-laporan2">
									<thead>
										<tr>
											<th><?= Yii::t('app', 'No.'); ?></th>
											<th><?= Yii::t('app', 'Nama BHP') ?></th>
											<th><?= Yii::t('app', 'Total SPL') ?></th>
											<th><?= Yii::t('app', 'Total Item') ?></th>
											<th></th>
										</tr>
									</thead>
								</table>
							</div>
                        </div>
                    </div>
                </div>
				<div class="row">
					<div class="col-md-12 text-align-center">
						<?= yii\helpers\Html::button(Yii::t('app', 'Print'),['class'=>'btn blue-steel ciptana-spin-btn btn-outline','onclick'=>'printout("PRINT")']); ?>
						<?= yii\helpers\Html::button(Yii::t('app', 'Excel'),['class'=>'btn green-seagreen ciptana-spin-btn btn-outline','onclick'=>'printout("EXCEL")']); ?>
						<?= yii\helpers\Html::button(Yii::t('app', 'PDF'),['class'=>'btn red-flamingo ciptana-spin-btn btn-outline','onclick'=>'printout("PDF")']); ?>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
$('#form-search-laporan').submit(function(){
	dtLaporan();
	dtLaporan2();
	return false;
});
formconfig(); 
dtLaporan();
dtLaporan2();
changePertanggalLabel();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/laporan/bhpTerbeli') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 2, 
                class : 'text-align-center',
				render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{	targets: 3, 
                class : 'text-align-right',
				render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{	targets: 4, 
                width: '50px',
				orderable: false,
				searchable: false,
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline blue btn-outline" onclick="InfoAllSpoByItem('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		order: [
            [3, 'desc']
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function dtLaporan2(){
    var dt_table2 =  $('#table-laporan2').dataTable({
		pageLength: 20,
		ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/laporan/bhpTerbeli') ?>',
			data:{
				dt: 'table-laporan2',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 2, 
                class : 'text-align-center',
				render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{	targets: 3, 
                class : 'text-align-right',
				render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{	targets: 4, 
                width: '50px',
				orderable: false,
				searchable: false,
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline blue btn-outline" onclick="InfoAllSplByItem('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		order: [
            [3, 'desc']
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/laporan/bhpTerbeliPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function InfoAllSpoByItem(bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute('/purchasing/tracking/InfoAllSpoByItem') ?>?bhp_id='+bhp_id,'modal-all-spo');
}
function InfoAllSplByItem(bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute('/purchasing/tracking/InfoAllSplByItem') ?>?bhp_id='+bhp_id,'modal-all-spl');
}
</script>