<?php
/* @var $this yii\web\View */
$this->title = 'Master Jasa';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Jasa'); ?></h1>
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
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Produk Jasa'); ?></span>
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
                                            <th style="width: 100px;"><?= Yii::t('app', 'Jenis') ?></th>
                                            <th style="width: 120px;"><?= Yii::t('app', 'Kode') ?></th>
                                            <th style="width: 300px;"><?= Yii::t('app', 'Nama') ?></th>
                                            <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Satuan<br>Harga') ?></th>
                                            <th><?= Yii::t('app', 'Keterangan') ?></th>
                                            <th style="width: 100px;"><?= Yii::t('app', 'Status') ?></th>
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
<?php $this->registerJs(" dtMaster();", yii\web\View::POS_READY); ?>
		
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/marketing/jasa/index') ?>',data:{dt: 'table-master'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class:"text-align-left" },
            {	targets: 2, class:"text-align-center" },
            {	targets: 3,
                render: function ( data, type, full, meta ) {
                    var ret = data;
                    return ret;
                }
            },
            {	targets: 4, class:"text-align-center" },
            {	targets: 5, class:"text-align-center" },
            {	targets: 6, class:"text-align-center",
                orderable: false,
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
            {	targets: 7, 
                orderable: false,
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
        ],
        "autoWidth":false,
    });
}

function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/marketing/jasa/create') ?>','modal-master-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/jasa/info','id'=>'']) ?>'+id,'modal-master-info');
}
</script>