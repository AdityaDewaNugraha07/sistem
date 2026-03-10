<?php
/* @var $this yii\web\View */
$this->title = 'Available Stock';
app\assets\DatatableAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Available Stock'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
#table-master tbody tr td{
	font-size: 1.3rem;
}
</style>
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
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Available Stock Produk'); ?></span>
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
                                            <th><?= Yii::t('app', 'Jenis Produk') ?></th>
                                            <th><?= Yii::t('app', 'Kode Produk') ?></th>
                                            <th><?= Yii::t('app', 'Nama Produk') ?></th>
                                            <th style="line-height: 1"><?= Yii::t('app', 'Total<br>Palet') ?></th>
                                            <th style="line-height: 1"><?= Yii::t('app', 'Total<br>Qty') ?></th>
											<th></th>
                                            <th style="line-height: 1"><?= Yii::t('app', 'Total<br>M<sup>3</sup>') ?></th>
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
", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/gudang/availablestockproduk/index') ?>',data:{dt: 'table-master'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, width: '100px', },
            {	targets: 2, width: '220px', },
            {	targets: 3, width: '320px',},
            {	targets: 4, class:'text-align-right', searchable: false },
            {	targets: 5, class:'text-align-right', searchable: false,
				render: function ( data, type, full, meta ) {
					return data+" ("+full[6]+")";
                }
			},
			{	targets: 6, visible: false, searchable: false},
			{	targets: 7, class:'text-align-right', searchable: false},
            {	targets: 8, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"produklist('+full[0]+',\''+full[6]+'\')\"><i class="fa fa-info-circle"></i> Detail</a></center>';
                }
            },
        ],
		"autoWidth":false,
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
		},
    });
}

function printout(caraPrint){
	var param = $('input[type="search"][aria-controls="table-master"]').val();
	window.open("<?= yii\helpers\Url::toRoute('/gudang/availablestockproduk/stockPrint') ?>?caraprint="+caraPrint+"&param="+param,"",'location=_new, width=1200px, scrollbars=yes');
}

function produklist(produk_id,satuan_kecil){
	openModal('<?= \yii\helpers\Url::toRoute(['/gudang/availablestockproduk/produklist','produk_id'=>'']); ?>'+produk_id+'&satuan_kecil='+satuan_kecil,'modal-produklist','90%');
}
</script>