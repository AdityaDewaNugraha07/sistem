<?php
/* @var $this yii\web\View */
$this->title = 'Approval Harga Limbah';
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
						<a href="<?= yii\helpers\Url::toRoute("/topmanagement/approvalhargalimbah/index"); ?>"> <?= Yii::t('app', 'Not Confirmed'); ?> </a>
                    </li>
					<li class="<?php echo $status2;?>">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalhargalimbah/indexConfirmed") ?>"> <?= Yii::t('app', 'Confirmed'); ?> </a>
					</li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<?= Yii::t('app', 'Daftar Pengajuan Update Data Harga Limbah'); ?>
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
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Kode') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Berkas') ?></th>
                                            <?php /* <th style="line-height: 1;"><?= Yii::t('app', 'Nama Customer') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Max Plafond') ?></th> */ ?>
                                            <th><?= Yii::t('app', 'Assign To') ?></th>
                                            <th><?= Yii::t('app', 'Approved By') ?></th>
                                            <th><?= Yii::t('app', 'Status') ?></th>
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

<?php 
$this->registerJs(" dtMaster(); 
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Approval Harga Limbah'))."');
", yii\web\View::POS_READY); 
?>

<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvalhargalimbah/'.$this->context->action->id); ?>

<script>
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= $url ?>',data:{dt: 'table-master'} },
		order: [],
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 1, class: "td-kecil text-align-center"},
            {   targets: 2, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
                    date = date.toString('dd/MM/yyyy');
                    return '<center>'+date+'</center>';
                }
            },
            {   targets: 3, class: "td-kecil text-align-left",
                render: function ( data, type, full, meta ) {
                    return '<span style="padding-left: 10px;">'+full[3]+'</span>';
                }
            },
            {   targets: 4, class: "td-kecil text-align-left",
                render: function ( data, type, full, meta ) {
                    return full[4];
                }
            },
            {   targets: 5, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(full[6]=='APPROVED'){
                        ret = '<span class="label label-success" style="font-size:1.1rem;">'+full[6]+'</span>';
                    }else if(full[6]=='REJECTED'){
                        ret = '<span class="label label-danger" style="font-size:1.1rem;">'+full[6]+'</span>';
                    }else if(full[6]=='Not Confirmed'){
                        ret = '<span class="label label-default" style="font-size:1.1rem;">'+full[6]+'</span>';
                    }
                    return ret;
                }
            },
            {	targets: 6, 
                orderable: false,
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
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalhargalimbah/info','id'=>'']) ?>'+id,'modal-master-info','80%'," $('#table-master').dataTable().fnClearTable(); ");
}
</script>