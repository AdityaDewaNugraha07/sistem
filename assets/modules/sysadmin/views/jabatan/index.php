<?php
/* @var $this yii\web\View */
$this->title = 'Pegawai';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Pegawai'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/pegawai/index"); ?>"> <?= Yii::t('app', 'Pegawai'); ?> </a>
                    </li>
					<li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/jabatan/index"); ?>"> <?= Yii::t('app', 'Jabatan'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/departement/index"); ?>"> <?= Yii::t('app', 'Departement'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Jabatan'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<input type="hidden" id="showing_id" value="">
                                <table class="table table-striped table-bordered table-hover" id="table-master">
                                    <thead>
                                        <tr>
                                            <th><?= Yii::t('app', 'ID Jabatan') ?></th>
                                            <th><?= Yii::t('app', 'Nama Jabatan') ?></th>
                                            <th><?= Yii::t('app', 'Nama Lain') ?></th>
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
<?php $this->registerJs(" dtMaster(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Pegawai'))."');", yii\web\View::POS_READY); ?>

<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/jabatan/index') ?>',data:{dt: 'table-master'} },
        order: [
            [0, 'DESC']
        ],
        columnDefs: [
            {	targets: 3,
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
            {	targets: 4, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableMaster(oSettings.sTableId);
			var cols = dt_table.fnSettings().aoColumns,
				rows = dt_table.fnGetData();
			var result = $.map(rows, function(row) {
				var object = {};
				for (var i=row.length-1; i>=0; i--)
					object = row[0];
				return object;
			});
			$('#showing_id').val(result);
		},
    });
}

function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/sysadmin/jabatan/create') ?>','modal-master-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/jabatan/info','id'=>'']) ?>'+id,'modal-master-info');
}
function printout(caraprint){
	window.open("<?= yii\helpers\Url::toRoute('/sysadmin/jabatan/printout') ?>?id="+$('#showing_id').val()+"&caraprint="+caraprint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>