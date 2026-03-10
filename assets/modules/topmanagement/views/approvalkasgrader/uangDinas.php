<?php
/* @var $this yii\web\View */
$this->title = 'Approval Center';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="active">
						<a href="<?= yii\helpers\Url::toRoute("/topmanagement/approvalkasgrader/uangdinas"); ?>"> <?= Yii::t('app', 'Uang Dinas'); ?> </a>
                    </li>
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/topmanagement/approvalkasgrader/uangmakan"); ?>"> <?= Yii::t('app', 'Uang Makan'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<?= Yii::t('app', 'Pengajuan Uang Dinas Grader Agreement List'); ?>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-master">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Reff No.') ?></th>
											<th></th>
                                            <th><?= Yii::t('app', 'Tanggal Berkas') ?></th>
                                            <th><?= Yii::t('app', 'Assign To') ?></th>
                                            <th><?= Yii::t('app', 'Approved By') ?></th>
                                            <th><?= Yii::t('app', 'Status') ?></th>
                                            <th style="width: 50px;"></th>
											<th></th>
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
<?php $this->registerJs(" dtMaster(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Approve Kas Grader'))."');", yii\web\View::POS_READY); ?>
<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvalkasgrader/'.$this->context->action->id); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= $url ?>',data:{dt: 'table-master'} },
//		order: [
//            [1, 'desc'],
//        ],
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 1, 
                render: function ( data, type, full, meta ) {
                    return data;
                }
            },
			{	targets: 2, visible: false, },
            {	targets: 6,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data=='APPROVED'){
                        ret = '<span class="label label-success">'+data+'</span>';
                    }else if(data=='REJECTED'){
                        ret = '<span class="label label-danger">'+data+'</span>';
                    }else if(data=='Not Confirmed'){
                        ret = '<span class="label label-default">'+data+'</span>';
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
			{	targets: 8, visible: false, },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        "bStateSave": true,
    });
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalkasgrader/info','id'=>'']) ?>'+id,'modal-master-info',null," $('#table-master').dataTable().fnClearTable(); ");
}
</script>