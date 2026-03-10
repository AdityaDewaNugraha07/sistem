<?php
/* @var $this yii\web\View */
$this->title = 'Approval Alias Packinglist';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
\app\assets\InputMaskAsset::register($this);

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
						<a href="<?= yii\helpers\Url::toRoute("/topmanagement/approvalaliaspackinglist/index"); ?>"> <?= Yii::t('app', 'Not Confirmed'); ?> </a>
                    </li>
					<li class="<?php echo $status2;?>">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalaliaspackinglist/indexConfirmed") ?>"> <?= Yii::t('app', 'Confirmed'); ?> </a>
					</li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<?= Yii::t('app', 'Daftar Agreement Alias Packinglist'); ?>
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
                                            <th style="line-height: 1; width: 140px;"><?= Yii::t('app', 'Kode / No.<br>Packinglist') ?></th>
                                            <th style="line-height: 1; width: 200px;"></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Buyer') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Packinglist') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Final<br>Destination') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Status<br>Packinglist') ?></th>
                                            <th><?= Yii::t('app', 'Assign To') ?></th>
                                            <th><?= Yii::t('app', 'Approved By') ?></th>
                                            <th><?= Yii::t('app', 'Level') ?></th>
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
<?php $this->registerJs(" dtMaster(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Approve Alias Packinglist'))."');", yii\web\View::POS_READY); ?>
<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvalaliaspackinglist/'.$this->context->action->id); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= $url ?>',data:{dt: 'table-master'} },
		order: [],
        autoWidth: false,
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 1, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var ret = data;
                    if(full[2]){
                        ret = data+"<br><b>"+full[2]+"<br>";
                    }
                    return ret;
                }
            },
			{	targets: 2, class: "td-kecil text-align-center", visible:false},
			{	targets: 3, class: "td-kecil text-align-left"},
			{ 	targets: 4, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 5, class: "td-kecil text-align-left",
                render: function ( data, type, full, meta ) {
                    return data;
                }
            },
			{	targets: 6, class: "td-kecil text-align-center"},
			{	targets: 7, class: "td-kecil text-align-center"},
			{	targets: 8, class: "td-kecil text-align-center"},
			{	targets: 9, class: "td-kecil text-align-center"},
            {	targets: 10, class: "td-kecil text-align-center",
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
            {	targets: 11, 
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
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalaliaspackinglist/info','id'=>'']) ?>'+id,'modal-master-info','90%'," $('#table-master').dataTable().fnClearTable(); ");
}
</script>