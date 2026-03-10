<?php
/* @var $this yii\web\View */
$this->title = 'Approval Pengajuan Master Produk';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);

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
						<a href="<?= yii\helpers\Url::toRoute("/topmanagement/approvalpengajuanmasterproduk/index"); ?>"> <?= Yii::t('app', 'Not Confirmed'); ?> </a>
                    </li>
					<li class="<?php echo $status2;?>">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalpengajuanmasterproduk/confirmed") ?>"> <?= Yii::t('app', 'Confirmed'); ?> </a>
					</li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<?= Yii::t('app', 'Pengajuan Master Barang Produk Baru'); ?>
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
                                            <th>No</th>
                                            <th><?= Yii::t('app', 'Reff No.') ?></th>
                                            <th><?= Yii::t('app', 'Tanggal') ?></th>
                                            <th><?= Yii::t('app', 'Keperluan') ?></th>  
                                            <th><?= Yii::t('app', 'Status Pengajuan') ?></th>                                            
                                            <th><?= Yii::t('app', 'Assign To') ?></th>
                                            <th><?= Yii::t('app', 'Approved By') ?></th>
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
<?php $this->registerJs(" dtMaster(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Approval Pengajuan Master Produk'))."');", yii\web\View::POS_READY); ?>
<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvalpengajuanmasterproduk/'.$this->context->action->id); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= $url ?>',data:{dt: 'table-master'} },
//		order: [
//            [1, 'desc'],
//        ],
        columnDefs: [
			{ 	targets: 0, class:'td-kecil',
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 1, class:'td-kecil',
                render: function ( data, type, full, meta ) {
                    return data;
                }
            },
            {   targets: 2,class:'td-kecil',
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }                
            },
            {   targets: 3,class:'td-kecil'},
            {   targets: 4,class:'td-kecil text-align-center'},
            {   targets: 5,class:'td-kecil'},
            {   targets: 6,class:'td-kecil text-align-center'},
            {	targets: 7,class:'td-kecil text-align-center',
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
            {	targets: 8, class:'td-kecil',
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        "bStateSave": true,
    });
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalpengajuanmasterproduk/info','id'=>'']) ?>'+id,'modal-master-info','90%'," $('#table-master').dataTable().fnClearTable(); ");
}
</script>
