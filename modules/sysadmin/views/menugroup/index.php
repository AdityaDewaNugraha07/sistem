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
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/menu/index"); ?>" > <?= Yii::t('app', 'Menu'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/menugroup/index"); ?>" > <?= Yii::t('app', 'Menu Group'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/modulemaster/index"); ?>" > <?= Yii::t('app', 'Module Master'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Menu Group'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-menu-group">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Name') ?></th>
                                            <th><?= Yii::t('app', 'Other Name') ?></th>
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
<?php $this->registerJs(" dtMenuGroup(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Menu'))."');", yii\web\View::POS_READY); ?>
<script>
function dtMenuGroup(){
    var dt_table =  $('#table-menu-group').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/menugroup/index') ?>',data:{dt: 'table-menu-group'} },
        order: [
            [4, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 3,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    return '<center>'+full[3]+'</center>';
                }
            },
            {	targets: 5,
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
            {	targets: 6, 
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
	openModal('<?= \yii\helpers\Url::toRoute('/sysadmin/menugroup/create') ?>','modal-menugroup-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/menugroup/info','id'=>'']) ?>'+id,'modal-menugroup-info');
}
</script>