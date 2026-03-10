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
<?php $this->registerJs(" dtBhp(); $('.fullscreen').trigger('click');", yii\web\View::POS_READY); ?>
<script>
function dtBhp(){
    var dt_table =  $('#table-bahanpembantu').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/logistik/bahanpembantu/list') ?>',data:{dt: 'table-bahanpembantu'} },
        order: [
            [0, 'desc']
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"pageLength": 1000,
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
        ],
    });
}

function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/logistik/bahanpembantu/create') ?>','modal-bahanpembantu-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/logistik/bahanpembantu/info','id'=>'']) ?>'+id,'modal-bahanpembantu-info');
}
</script>