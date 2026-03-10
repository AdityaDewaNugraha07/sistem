<?php
/* @var $this yii\web\View */
$this->title = 'User Management';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'User Management'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/user/index"); ?>"> <?= Yii::t('app', 'User Account'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/usergroup/index"); ?>"> <?= Yii::t('app', 'User Group'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/useraccess/index"); ?>"> <?= Yii::t('app', 'User Access'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/useraktif/index"); ?>"> <?= Yii::t('app', 'User Aktif'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'User Account Master'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-user">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Username') ?></th>
                                            <th><?= Yii::t('app', 'Fullname') ?></th>
                                            <th><?= Yii::t('app', 'User Group') ?></th>
                                            <th><?= Yii::t('app', 'Created Time') ?></th>
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
<?php $this->registerJs(" dtUser();", yii\web\View::POS_READY); ?>

<script>
function dtUser(){
    var dt_table =  $('#table-user').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/user/index') ?>',data:{dt: 'table-user'} },
        order: [
            [4, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
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
	openModal('<?= \yii\helpers\Url::toRoute('/sysadmin/user/create') ?>','modal-user-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/user/info','id'=>'']) ?>'+id,'modal-user-info');
}
</script>