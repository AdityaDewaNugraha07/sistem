<?php
/* @var $this yii\web\View */
$this->title = 'Zloggerz';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', $this->title); ?></span>
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
                                            <th><?= Yii::t('app', 'Log Time') ?></th>
                                            <th><?= Yii::t('app', 'Prefix') ?></th>
                                            <th><?= Yii::t('app', 'Message') ?></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/zloggerz/index') ?>',data:{dt: 'table-user'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, 
                render: function ( data, type, full, meta ) {
                    var d = new Date(full[1] * 1000);
                        tanggal = '' + d.getDate();
                        bulan = '' + (d.getMonth() + 1);
                        tahun = d.getFullYear();
                        jam = d.getHours();
                        menit = d.getMinutes();
                        detik = d.getSeconds();
                    return tanggal+'/'+bulan+'/'+tahun+' '+jam+':'+menit+':'+detik;
                }
            },
        ],        
    });
}
</script>