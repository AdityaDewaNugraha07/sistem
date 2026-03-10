<?php
/* @var $this yii\web\View */
$this->title = 'Approval Center';
app\assets\DatatableAsset::register($this);
$status == "Not Confirmed" ? $active1 = 'active' : $active1 = '';
$status == "Confirmed" ? $active2 = 'active' : $active2 = '';
$status == "Aborted" ? $active3 = 'active' : $active3 = '';
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
					<li class="<?php echo $active1;?>">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalcenter/spo") ?>"> <?= Yii::t('app', 'Not Confirmed'); ?> </a>
					</li>
					<li class="<?php echo $active2;?>">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalcenter/spoConfirmed") ?>"> <?= Yii::t('app', 'Confirmed'); ?> </a>
					</li>
                                        <li class="<?php echo $active3;?>">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalcenter/spoAborted") ?>"> <?= Yii::t('app', 'Aborted'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <?php
                                    $judul = "PO Bahan Pembantu";
                                    echo $judul.Yii::t('app', ' Agreement List'); ?>
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
                                            <th><?= Yii::t('app', 'Tanggal Berkas') ?></th>
                                            <th><?= Yii::t('app', 'Assign To') ?></th>
                                            <th><?= Yii::t('app', 'Confirm By') ?></th>
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
<?php $this->registerJs(" dtMaster(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Approval PO Bahan Pembantu'))."');", yii\web\View::POS_READY); ?>
<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvalcenter/'.$this->context->action->id); ?>
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
			{	targets: 2, visible: false, },
            {	targets: 5,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
					if(!full[7]){
						if(data=='APPROVED'){
							ret = '<span class="label label-success">'+data+'</span>';
						}else if(data=='REJECTED'){
							ret = '<span class="label label-danger">'+data+'</span>';
						}else if(data=='Not Confirmed'){
							ret = '<span class="label label-default">'+data+'</span>';
						}
					}else{
						ret = '<span class="label label-danger"><?= app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>';
					}
                    return ret;
                }
            },
            {	targets: 6, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
			{	targets: 7, visible: false, },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        "bStateSave": true,
    });
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalcenter/info','id'=>'']) ?>'+id,'modal-master-info',null," $('#table-master').dataTable().fnClearTable(); ");
}

function penawaranTerpilih(spod_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/penawaranTerpilih','id'=>'']) ?>'+spod_id+'&by=SPO';
	var modal_id = 'modal-penawaran';	
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id+" .modal-dialog").css('width',"80%");
		$("#"+modal_id).on('hidden.bs.modal', function () {
			$("#"+modal_id).hide();
			$("#"+modal_id).remove();
		});
		spinbtn();
		draggableModal();
	});
}

function infoPenawaran(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/infoPenawaran','id'=>'']) ?>'+id+'&disableAction=1&disableDelete=1&disableEdit=1';
	var modal_id = 'modal-info-penawaran';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function () {
			$("#"+modal_id).hide();
			$("#"+modal_id).remove();
		});
		spinbtn();
		draggableModal();
	});
}
</script>