<?php
/* @var $this yii\web\View */
$this->title = 'Menu & Module Management';
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
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/menu/index"); ?>"> <?= Yii::t('app', 'Menu'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/menugroup/index"); ?>"> <?= Yii::t('app', 'Menu Group'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/modulemaster/index"); ?>"> <?= Yii::t('app', 'Module Master'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Menu Master'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-menu">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Name') ?></th>
                                            <th><?= Yii::t('app', 'Menu Group') ?></th>
                                            <th><?= Yii::t('app', 'Module') ?></th>
                                            <th><?= Yii::t('app', 'Url') ?></th>
                                            <th><?= Yii::t('app', 'Sequence') ?></th>
                                            <th><?= Yii::t('app', 'Create Time') ?></th>
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
<?php $this->registerJs(" dtMenu();", yii\web\View::POS_READY); ?>

<script>
function dtMenu(){
    var dt_table =  $('#table-menu').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/menu/index') ?>',data:{dt: 'table-menu'} },
        order: [
            [6, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1,
                width: '25%',
                render: function ( data, type, full, meta ) {
                    return full[1];
                }
            },
            {	targets: 5,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center>'+full[5]+'</center>';
                }
            },
            {	targets: 7,
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
            {	targets: 8, 
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
	openModal('<?= \yii\helpers\Url::toRoute('/sysadmin/menu/create') ?>','modal-menu-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/menu/info','id'=>'']) ?>'+id,'modal-menu-info');
}
</script>