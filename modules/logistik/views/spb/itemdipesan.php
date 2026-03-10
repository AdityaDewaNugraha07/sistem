<?php
/* @var $this yii\web\View */
$this->title = 'Item Dipesan';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Item Dipesan'); ?></h1>
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
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Semua Item yang pernah di pesan oleh bagian : ').Yii::$app->user->identity->pegawai->departement->departement_nama; ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<table class="table table-striped table-bordered table-hover" id="table-item">
									<thead>
										<tr>
											<th>No.</th>
											<th><?= Yii::t('app', 'Tanggal SPB') ?></th>
											<th><?= Yii::t('app', 'Kode SPB') ?></th>
											<th><?= Yii::t('app', 'Item') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Qty<br>Pesan') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Qty<br>Terpenuhi') ?></th>
											<th><?= Yii::t('app', 'Diminta') ?></th>
											<th><?= Yii::t('app', 'Menyetujui') ?></th>
											<th><?= Yii::t('app', 'Mengetahui') ?></th>
											<th><?= Yii::t('app', 'Status SPB') ?></th>
											<th><?= Yii::t('app', 'Status Approve') ?></th>
											<th><?= Yii::t('app', 'Keterangan') ?></th>
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
<?php $this->registerJs(" dtmaster(); $('.fullscreen').trigger('click');", yii\web\View::POS_READY); ?>
<script>
function dtmaster(){
    var dt_table =  $('#table-item').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/logistik/spb/Itemdipesan') ?>',data:{dt: 'table-item'} },
        order: [
            [0, 'desc']
        ],
//		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
		"pageLength": 100,
        columnDefs: [
            { 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
            {	targets: 1, class:"td-kecil",
				render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
			},
            {	targets: 2, class: "text-align-center td-kecil"},
            {	targets: 3, class: "td-kecil"},
            {	targets: 4, class: "text-align-center td-kecil"},
            {	targets: 5, visible:false},
            {	targets: 6, class: "td-kecil"},
            {	targets: 7, class: "td-kecil"},
            {	targets: 8, class: "td-kecil"},
            {	targets: 9, class: "text-align-center td-kecil",
				render: function ( data, type, full, meta ) {
					var ret = '';
					if(data == "TERPENUHI"){
						ret = '<span class="label label-success" style="font-size: 11px; padding: 2px 3px;"> '+data+' </span>';
					}
					if(data == "DITOLAK"){
						ret = '<span class="label label-danger" style="font-size: 11px; padding: 2px 3px;"> '+data+' </span>';
					}
					if(data == "SEDANG DIPROSES"){
						ret = '<span class="label label-warning" style="font-size: 11px; padding: 2px 3px;"> '+data+' </span>';
					}
					if(data == "BELUM DIPROSES"){
						ret = '<span class="label label-info" style="font-size: 11px; padding: 2px 3px;"> '+data+' </span>';
					}
					return ret;
                }
			},
			{	targets: 10, class: "text-align-center td-kecil",
				render: function ( data, type, full, meta ) {
					var ret = '';
					if(data == "APPROVED"){
						ret = '<span class="label label-success" style="font-size: 11px; padding: 2px 3px;"> '+data+' </span>';
					}
					if(data == "REJECTED"){
						ret = '<span class="label label-danger" style="font-size: 11px; padding: 2px 3px;"> '+data+' </span>';
					}
					if(data == "Not Confirmed"){
						ret = '<span class="label label-default" style="font-size: 11px; padding: 2px 3px;"> '+data+' </span>';
					}
					return ret;
                }
			},
			{	targets: 11, class: "td-kecil"},
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableThis(oSettings.sTableId);
		},
    });
}

function formattingDatatableThis(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printitemdipesan(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
    $(".tooltips").tooltip({ delay: 50 });
}

function printitemdipesan(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/logistik/spb/ItemdipesanPrint') ?>?caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>