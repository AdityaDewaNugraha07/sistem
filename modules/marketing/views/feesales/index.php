<?php
/* @var $this yii\web\View */
$this->title = 'Master Sales';
app\assets\DatatableAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Sales'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/sales/index"); ?>"> <?= Yii::t('app', 'Data Sales'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/feesales/index"); ?>"> <?= Yii::t('app', 'Fee Sales'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Fee Sales'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-feesales">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Level Sales') ?></th>
                                            <th><?= Yii::t('app', 'Jenis Produk') ?></th>
                                            <th><?= Yii::t('app', 'Tempo Pembayaran (Hari)') ?></th>
                                            <th><?= Yii::t('app', 'Fee (Rp) / Satuan') ?></th>
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
<?php $this->registerJs(" dtFeesales(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Sales'))."');", yii\web\View::POS_READY); ?>

<script>
function dtFeesales(){
    var dt_table =  $('#table-feesales').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/marketing/feesales/index') ?>',data:{dt: 'table-feesales'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 4,
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    ret = data+" / "+full[5];
                    return ret;
                }
            },
            {	targets: 5, visible: false },
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
	openModal('<?= \yii\helpers\Url::toRoute('/marketing/feesales/create') ?>','modal-feesales-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/feesales/info','id'=>'']) ?>'+id,'modal-feesales-info');
}
</script>