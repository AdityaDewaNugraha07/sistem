<?php
/* @var $this yii\web\View */
$this->title = 'Master Limbah';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Limbah'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/limbah/index"); ?>"> <?= Yii::t('app', 'Limbah'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/pricelistlimbah/index"); ?>"> <?= Yii::t('app', 'Price List'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Barang Limbah'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-limbah">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="width: 120px;"><?= Yii::t('app', 'Kode') ?></th>
                                            <th><?= Yii::t('app', 'Nama') ?></th>
                                            <th><?= Yii::t('app', 'Kuantitas') ?></th>
                                            <th></th>
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
<?php $this->registerJs(" dtLimbah();", yii\web\View::POS_READY); ?>
		
<script>
function dtLimbah(){
    var dt_table =  $('#table-limbah').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/marketing/limbah/index') ?>',data:{dt: 'table-limbah'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 3,
                render: function ( data, type, full, meta ) {
                    var ret = data+' / '+full[4];
                    return ret;
                }
            },
            {	targets: 4, visible: false },
            {	targets: 5,
                orderable: false,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data){
                        ret = 'Active';
                    }else{
                        ret = '<span style="color:#B40404">Non-Active</span>';
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
	openModal('<?= \yii\helpers\Url::toRoute('/marketing/limbah/create') ?>','modal-limbah-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/limbah/info','id'=>'']) ?>'+id,'modal-limbah-info');
}
</script>