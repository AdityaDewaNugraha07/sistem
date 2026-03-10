<?php
/* @var $this yii\web\View */
$this->title = 'Approval Asuransi';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);

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
						<a href="<?= yii\helpers\Url::toRoute("/topmanagement/approvalasuransi/index"); ?>"> <?= Yii::t('app', 'Not Confirmed'); ?> </a>
                    </li>
					<li class="<?php echo $status2;?>">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalasuransi/indexConfirmed") ?>"> <?= Yii::t('app', 'Confirmed'); ?> </a>
					</li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                <?= Yii::t('app', 'Daftar Pengajuan Asuransi'); ?>
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
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Berkas') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Kepada') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Lampiran') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Nama Kapal') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Freight') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Rate') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Approver') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Level') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Status') ?></th>
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
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Approve Asuransi'))."');
", yii\web\View::POS_READY);
?>
<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvalasuransi/'.$this->context->action->id); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= $url ?>',data:{dt: 'table-master'} },
		order: [],
        columnDefs: [
            { 	targets: 0, 
                orderable: false,
                class: "td-kecil text-align-left",
                    render: function ( data, type, full, meta ) {
                            return '<center>'+(meta.row+1)+'</center>';
                }
            },
            { 	targets: 1, class: "td-kecil text-align-center",
                    render: function ( data, type, full, meta ) {
                            var date = new Date(data);
                            date = date.toString('dd/MM/yyyy');
                            return '<center>'+date+'</center>';
                }
            },
            { 	targets: 2, 
                class: "td-kecil text-align-left",
                    render: function ( data, type, full, meta ) {
                            return nl2br(data);
                }
            },
            { 	targets: 3, 
                class: "td-kecil text-align-left",
                    render: function ( data, type, full, meta ) {
                            return nl2br(data);
                }
            },
            {	targets: 4, class: "td-kecil text-align-left",
                    render: function ( data, type, full, meta ) {
                            return data;
                }
            },
            {	targets: 5, class: "td-kecil text-align-right",
                    render: function (data, type, full, meta) {
                            return formatNumberForUser(data);
                }
            },
            { 	targets: 6, 
                class: "td-kecil text-align-left",
                    render: function ( data, type, full, meta ) {
                            return data+'%';
                }
            },            
            {	targets: 7, class: "td-kecil text-align-left"},
            {	targets: 8, class: "td-kecil text-align-center"},
            {	targets: 9, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data=='APPROVED'){
                        ret = '<span class="td-kecil label label-success">'+data+'</span>';
                    }else if(data=='REJECTED'){
                        ret = '<span class="td-kecil label label-danger">'+data+'</span>';
                    }else if(data=='Not Confirmed'){
                        ret = '<span class="td-kecil label label-default">'+data+'</span>';
                    } else {
                        ret = '<span class="td-kecil label label-danger">'+data+'</span>';
                    }
                    return ret;
                }
            },
            {	targets: 10, class: "td-kecil text-align-center",
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

function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalasuransi/info','id'=>'']) ?>'+id,'modal-master-info','90%'," $('#table-master').dataTable().fnClearTable(); ");
}

</script>