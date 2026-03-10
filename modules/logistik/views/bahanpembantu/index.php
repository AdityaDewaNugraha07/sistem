<?php
/* @var $this yii\web\View */
$this->title = 'Master Bahan Pembantu';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Bahan Pembantu'); ?></h1>
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
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Bahan Pembantu'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<table class="table table-striped table-bordered table-hover" id="table-bahanpembantu">
									<thead>
										<tr>
											<th>No.</th>
											<th style="width: 120px;"><?= Yii::t('app', 'Kode') ?></th>
											<th style="width: 180px;"><?= Yii::t('app', 'Kelompok') ?></th>
											<th><?= Yii::t('app', 'Nama') ?></th>
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
    var dt_table =  $('#table-bahanpembantu').dataTable({
        pageLength: 50,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/logistik/bahanpembantu/index') ?>',
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
            {	targets: 1, width: '15%' },
            {	targets: 4,
                orderable: false,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data){
                        ret = 'Active'
                    }else{
                        ret = '<span style="color:#B40404">Non-Active</span>'
                    }
                    return ret;
                }
            },
            {	targets: 5, 
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
		"dom": "<'row'<'col-md-6 col-sm-12'><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
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
	window.open("<?= yii\helpers\Url::toRoute('/logistik/bahanpembantu/print') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}


function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/logistik/bahanpembantu/create') ?>','modal-bahanpembantu-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/logistik/bahanpembantu/info','id'=>'']) ?>'+id,'modal-bahanpembantu-info');
}
</script>