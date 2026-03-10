<?php
/* @var $this yii\web\View */
$this->title = 'Manage Transaction';
app\assets\DatatableAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Manage Transaction'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
#table-produk tbody tr td{
	font-size: 1.3rem;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="<?= yii\helpers\Url::toRoute("/sysadmin/managetransaction/stockproduk"); ?>"> <?= Yii::t('app', 'Stock Produk'); ?> </a>
                    </li>
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/sysadmin/managetransaction/adjustspo"); ?>"> <?= Yii::t('app', 'Adjust SPO'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Stock Produk Gudang'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-produk">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Kode Barang Jadi') ?></th>
                                            <th><?= Yii::t('app', 'Nama Produk') ?></th>
                                            <th><?= Yii::t('app', 'Tanggal Produksi') ?></th>
                                            <th><?= Yii::t('app', 'Shift/Line') ?></th>
                                            <th><?= Yii::t('app', 'Lokasi Gudang') ?></th>
                                            <th><?= Yii::t('app', 'Qty') ?></th>
                                            <th><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
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
<?php $this->registerJs("
	dtMaster();
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Administrator'))."');
", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-produk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/gudang/availablestockproduk/produklist') ?>',data:{dt: 'table-produk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1,
				render: function ( data, type, full, meta ) {
                    return full[3];
                }
			},
            {	targets: 3,
				render: function ( data, type, full, meta ) {
                    var date = new Date(full[4]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
			},
            {	targets: 4,
				render: function ( data, type, full, meta ) {
					return '<center>'+full[5]+'</center>';
                }
			},
            {	targets: 5,
				render: function ( data, type, full, meta ) {
					return '<center>'+full[7]+'</center>';
                }
			},
            {	targets: 6,
				class:"text-align-right",
				render: function ( data, type, full, meta ) {
					return full[8]+' ('+full[9]+')';
                }
			},
            {	targets: 7,
				class:"text-align-right",
				render: function ( data, type, full, meta ) {
					return full[10];
                }
			},
            {	targets: 8,
				searchable:false,
				class:"text-align-right",
				render: function ( data, type, full, meta ) {
					return '<a class="btn btn-xs red" onclick="openModal(\'<?php echo \yii\helpers\Url::toRoute(['/sysadmin/managetransaction/stockproduk','id'=>'']) ?>'+full[3]+'&tableid=table-produk\',\'modal-delete-record\')">delete</a>';
                }
			},
        ],
		"autoWidth":false,
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
		},
    });
}
</script>