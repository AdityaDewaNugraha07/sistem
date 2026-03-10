<?php
/* @var $this yii\web\View */
$this->title = 'Master Supplier';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Supplier'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
							<?= $this->render('_search', ['model' => $model]) ?>
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Supplier'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<table class="table table-striped table-bordered table-hover" id="table-supplier">
									<thead>
										<tr>
											<th></th>
											<th style="width: 250px;"><?= Yii::t('app', 'Nama') ?></th>
											<th style="width: 250px;"><?= Yii::t('app', 'Perusahaan') ?></th>
											<th><?= Yii::t('app', 'Alamat') ?></th>
											<th><?= Yii::t('app', 'Tipe') ?></th>
											<th><?= Yii::t('app', 'Keterangan') ?></th>
											<th><?= Yii::t('app', 'Status') ?></th>
											<th style="width: 50px;"></th>
										</tr>
									</thead>
								</table>
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
if(\Yii::$app->controller->id == 'supplierlog'){
	$menu = "setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Supplier Log'))."');";
}else{
	$menu = "";
}
?>
<?php $this->registerJs("
	$('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
	formconfig(); 
	dtLaporan();
	changePertanggalLabel();
	$menu
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-supplier').dataTable({
        pageLength: 50,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('index') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 4,
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data == 'BHP'){
                        ret = 'Supplier BHP';
                    }else if(data == 'LA'){
                        ret = 'Supplier Log Alam';
                    }else{
						ret = 'Supplier Log Sengon';
					}
                    return ret;
                }
            },
			{	targets: 5, 
                orderable: false,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    return "<span style='font-size:1.1rem'>"+full[6]+"<span>";
                }
            },
            {	targets: 6,
                orderable: false,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(full[5]){
                        ret = 'Active'
                    }else{
                        ret = '<span style="color:#B40404">Non-Active</span>'
                    }
                    return ret;
                }
            },
            {	targets: 7, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableMaster(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
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
	window.open("<?= yii\helpers\Url::toRoute('/'.\Yii::$app->controller->module->id.'/'.\Yii::$app->controller->id.'/print') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/logistik/supplier/create') ?>','modal-supplier-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/logistik/supplier/info','id'=>'']) ?>'+id,'modal-supplier-info');
}
</script>