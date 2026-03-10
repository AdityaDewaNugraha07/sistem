<?php
/* @var $this yii\web\View */
$this->title = 'Verifikasi Order Penjualan';
app\assets\DatatableAsset::register($this);

$status == 'Not Confirmed' ? $status1 = 'active' : $status1 = '';
$status == 'Confirmed' ? $status2 = 'active' : $status2 = '';

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
                    <li class="<?php echo $status1;?>">
						<a href="<?= yii\helpers\Url::toRoute("/topmanagement/approvalop/index"); ?>"> <?= Yii::t('app', 'Not Confirmed'); ?> </a>
                    </li>
					<li class="<?php echo $status2;?>">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalop/vopConfirmed") ?>"> <?= Yii::t('app', 'Confirmed'); ?> </a>
					</li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<?= Yii::t('app', 'Order Penjualan Over TOP atau Over Plafon'); ?>
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
                                            <?php
                                            /*['approval_id',                                                       // 0
                                            'reff_no',                                                              // 1
                                            'kode',                                                                 // 2
                                            'm_customer.cust_an_nama',                                              // 3
                                            ['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'],    // 4
                                            'assigned_nama',                                                        // 5
                                            'approved_by_nama',                                                     // 6
                                            $param['table'].'.status',                                              // 7
                                            $param['table'].'.created_at',                                          // 8
                                            $param['table'].'.created_at'];                                         // 9
                                            */
                                            ?>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Reff No.') ?></th>
                                            <th><?= Yii::t('app', 'Customer') ?></th>                                            
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
<?php $this->registerJs(" dtMaster(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Verifikasi Order Penjualan'))."');", yii\web\View::POS_READY); ?>
<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvalop/'.$this->context->action->id); ?>
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
            {   targets: 2,
                render: function ( data, type, full, meta ) {
                    return full[3];
                }                
            },
            {   targets: 3,
                render: function ( data, type, full, meta ) {
                    return '<center>'+full[4]+'</center>';
                }                
            },
            {   targets: 4,
                render: function ( data, type, full, meta ) {
                    return full[5];
                }                
            },
            {   targets: 5,
                render: function ( data, type, full, meta ) {
                    return full[6];
                }                
            },
            {	targets: 6,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(full[7]=='APPROVED'){
                        ret = '<center><span class="label label-success">'+full[7]+'</span></center>';
                    }else if(full[7]=='REJECTED'){
                        ret = '<center><span class="label label-danger">'+full[7]+'</span></center>';
                    }else if(full[7]=='Not Confirmed'){
                        ret = '<center><span class="label label-default">'+full[7]+'</span></center>';
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
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalop/info','id'=>'']) ?>'+id,'modal-master-info','90%'," $('#table-master').dataTable().fnClearTable(); ");
}
</script>
