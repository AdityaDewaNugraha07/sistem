<?php
/* @var $this yii\web\View */
$this->title = 'Master Log';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Log'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/log/index"); ?>"> <?= Yii::t('app', 'Log'); ?> </a>
                    </li>
                    <!-- <li class="">
                        <a href="<?php //echo yii\helpers\Url::toRoute("/marketing/pricelistlog/index"); ?>"> <?php //echo Yii::t('app', 'Price List'); ?> </a>
                    </li> -->
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Barang Log'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-log">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="width: 120px;"><?= Yii::t('app', 'Kode') ?></th>
                                            <th><?= Yii::t('app', 'Nama') ?></th>
                                            <th><?= Yii::t('app', 'Diameter') ?></th>
                                            <th><?= Yii::t('app', 'Kuantitas') ?></th>
                                            <th><?= Yii::t('app', 'Status FSC') ?></th>
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
<?php $this->registerJs(" dtLog();", yii\web\View::POS_READY); ?>
		
<script>
function dtLog(){
    var dt_table =  $('#table-log').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/marketing/log/index') ?>',data:{dt: 'table-log'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 3,
                render: function ( data, type, full, meta ) {
                    if(full[5] != null || full[6] != null){
                        var range_awal = full[5];
                        var range_akhir = full[6];
                    } else {
                        var range_awal = '';
                        var range_akhir = '';
                    }
                    return '<center>'+range_awal + ' - ' + range_akhir +'</center>';
                }
            },
            {	targets: 4,
                render: function ( data, type, full, meta ) {
                    return '<center>'+full[3]+'</center>';
                }
            },
            {	targets: 5, class: 'text-align-center',
                render: function ( data, type, full, meta ) {
                    if(full[7]){
                        $ret = 'FSC 100%';
                    } else {
                        $ret = 'Non FSC';
                    }
                    return $ret;
                }
             },
            {	targets: 6,
                orderable: false,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(full[4]){
                        ret = 'Active';
                    }else{
                        ret = '<span style="color:#B40404">Non-Active</span>';
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
        ],
    });
}

function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/marketing/log/create') ?>','modal-log-create', '90%');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/log/info','id'=>'']) ?>'+id,'modal-log-info');
}
</script>