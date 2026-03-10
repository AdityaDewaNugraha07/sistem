<?php
/* @var $this yii\web\View */
$this->title = 'Master Harga';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Harga'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/hargaproduk/index"); ?>"> <?= Yii::t('app', 'Produk'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/hargalimbah/index"); ?>"> <?= Yii::t('app', 'Limbah'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Harga Produk'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-harga">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Jenis Produk') ?></th>
                                            <th><?= Yii::t('app', 'Kode') ?></th>
                                            <th><?= Yii::t('app', 'Nama') ?></th>
                                            <th><?= Yii::t('app', 'Harga End User') ?></th>
                                            <th><?= Yii::t('app', 'Tanggal Penetapan') ?></th>
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
<?php $this->registerJs(" dtHarga();", yii\web\View::POS_READY); ?>
<script>
function dtHarga(){
    var dt_table =  $('#table-harga').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/hargaproduk/index') ?>',data:{dt: 'table-harga'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, width: '15%' },
            {	targets: 6,
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
	openModal('<?= \yii\helpers\Url::toRoute('/sysadmin/hargaproduk/create') ?>','modal-harga-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/hargaproduk/info','id'=>'']) ?>'+id,'modal-harga-info');
}
</script>