<?php
/* @var $this yii\web\View */
$this->title = 'Master Harga / Tarif';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Harga / Tarif'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/pricelist/index"); ?>"> <?= Yii::t('app', 'Price List'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/ongkostruk/index"); ?>"> <?= Yii::t('app', 'Ongkos Truck'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Ongkos Truk'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-ongkostruk">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Tujuan') ?></th>
                                            <th><?= Yii::t('app', 'Tarif Supir Lama') ?></th>
                                            <th><?= Yii::t('app', 'Tarif Supir Baru') ?></th>
                                            <th><?= Yii::t('app', 'Satuan') ?></th>
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
<?php $this->registerJs(" dtOngkoStruk();
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Harga / Tarif Produk'))."');
		", yii\web\View::POS_READY); ?>
<script>
function dtOngkoStruk(){
    var dt_table =  $('#table-ongkostruk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/marketing/ongkostruk/index') ?>',data:{dt: 'table-ongkostruk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 2, 
                width: '120px',
                render: function ( data, type, full, meta ) {
                    return 'Rp. '+full[2];
                }
            },
            {	targets: 3, 
                width: '120px',
                render: function ( data, type, full, meta ) {
                    return 'Rp. '+full[3];
                }
            },
            {	targets: 4, width: '80px', },
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
	openModal('<?= \yii\helpers\Url::toRoute('/marketing/ongkostruk/create') ?>','modal-ongkostruk-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/ongkostruk/info','id'=>'']) ?>'+id,'modal-ongkostruk-info');
}
</script>