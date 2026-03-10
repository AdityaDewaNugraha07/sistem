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
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/pricelist/history"); ?>"> <?= Yii::t('app', 'Price List History'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/ongkostruk/index"); ?>"> <?= Yii::t('app', 'Ongkos Truck'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-scrollable">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-list-alt"></i>
                                        <span class="caption-subject hijau bold"><?= Yii::t('app', 'Price List History'); ?></span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="reload"> </a>
                                        <a href="javascript:;" class="fullscreen"> </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover" id="table-history">
                                        <thead>
                                            <tr>
                                                <th><?= Yii::t('app', 'Tanggal') ?></th>
                                                <th><?= Yii::t('app', 'Kode') ?></th>
                                                <th><?= Yii::t('app', 'Jenis Produk') ?></th>
                                                <th><?= Yii::t('app', 'Status Approval') ?></th>
                                                <th><?= Yii::t('app', '') ?></th>
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
</div>
<?php $this->registerJs(" 
    dtHistory();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Harga / Tarif Produk'))."');
	", yii\web\View::POS_READY); ?>
<script>
function dtHistory(){
    var dt_table =  $('#table-history').dataTable({
        ajax: { 
            url: '<?= \yii\helpers\Url::toRoute('/marketing/pricelist/history') ?>',
            data:{
                dt: 'table-history'
            } 
        },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
			{ 	targets: 0, class: "text-align-center", 
                render: function ( data, type, full, meta ) {
                    var tanggal_db = full[0];
                    var tanggal = tanggal_db.split("-");
					return tanggal[2]+'-'+tanggal[1]+'-'+tanggal[0];
                }
            },
            {	targets: 1,  class: "text-align-center", 
                render: function ( data, type, full, meta ) {
                    return full[1];
                }
            },
            {	targets: 2,  class: "text-align-center", 
                render: function ( data, type, full, meta ) {
                    return full[2];
                }
            },
            {	targets: 3,  class: "text-align-center", 
                render: function ( data, type, full, meta ) {
                    return full[3];
                }
            },
            {   targets: 4, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info(\''+full[1]+'\',\''+full[0]+'\')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },            
        ],
        dom: 'Bfrtip',
        buttons: []
    });
    
}

function info(kode, harga_tanggal_penetapan){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/historyInfo','kode'=>'']) ?>'+kode+'&harga_tanggal_penetapan='+harga_tanggal_penetapan,'modal-history-info','90%');
}
</script>