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
                    <li>
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/user/index"); ?>"> <?= Yii::t('app', 'User Account'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/usergroup/index"); ?>"> <?= Yii::t('app', 'User Group'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/useraccess/index"); ?>"> <?= Yii::t('app', 'User Access'); ?> </a>
                    </li>
                    <li class="active">
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
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'User/Pegawai Aktif'); ?></span>
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
                                            <th><?= Yii::t('app', 'ID User') ?></th>
                                            <th><?= Yii::t('app', 'Username') ?></th>
                                            <th><?= Yii::t('app', 'Status') ?></th>
                                            <th><?= Yii::t('app', 'ID Pegawai') ?></th>
                                            <th><?= Yii::t('app', 'Pegawai Nama') ?></th>
                                            <th><?= Yii::t('app', 'Status') ?></th>
                                            <th><?= Yii::t('app', 'Jenis Kelamin') ?></th>
                                            <th><?= Yii::t('app', 'ID Departement') ?></th>
                                            <th><?= Yii::t('app', 'Nama Departement') ?></th>
                                            <th><?= Yii::t('app', 'ID Jabatan') ?></th>
                                            <th><?= Yii::t('app', 'Nama Jabatan') ?></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/useraktif/index') ?>',data:{dt: 'table-user'} },
        order: [[ 7, "desc" ], [ 9, "desc" ]],
        columnDefs: [
            {targets: 0,
                render: function ( data, type, full, meta ) {
                    if (full[2] == 1){
                        ret = '<span style="color:#0f7d13">'+full[0]+'</span>'
                    } else {
                        ret = '<span style="color:#b30000">'+full[0]+'</span>'
                    }
                    return ret;
                }    
            },
            {targets: 1,
                render: function ( data, type, full, meta ) {
                    if (full[2] == 1){
                        ret = '<span style="color:#0f7d13">'+full[1]+'</span>'
                    } else {
                        ret = '<span style="color:#b30000">'+full[1]+'</span>'
                    }
                    return ret;
                }    
            },
            {targets: 2, visible: false,
                render: function ( data, type, full, meta ) {
                    if (full[2] == 1){
                        ret = '<span style="color:#0f7d13">'+full[2]+'</span>'
                    } else {
                        ret = '<span style="color:#b30000">'+full[2]+'</span>'
                    }
                    return ret;
                }
            },
            {targets: 3,
                render: function ( data, type, full, meta ) {
                    if (full[5] == 1){
                        ret = '<span style="color:#0f7d13">'+full[3]+'</span>'
                    } else {
                        ret = '<span style="color:#b30000">'+full[3]+'</span>'
                    }
                    return ret;
                }
            },
            {targets: 4,
                render: function ( data, type, full, meta ) {
                    if (full[5] == 1){
                        ret = '<span style="color:#0f7d13">'+full[4]+'</span>'
                    } else {
                        ret = '<span style="color:#b30000">'+full[4]+'</span>'
                    }
                    return ret;
                }
            },
            {targets: 5, visible: false,
                render: function ( data, type, full, meta ) {
                    if (full[5] == 1){
                        ret = '<span style="color:#0f7d13">'+full[5]+'</span>'
                    } else {
                        ret = '<span style="color:#b30000">'+full[6]+'</span>'
                    }
                    return ret;
                }
            },
            {targets: 6},
            {targets: 7, visible: false},
            {targets: 8},
            {targets: 9, visible: false},
            {targets: 10},
        ],        
    });
}
</script>