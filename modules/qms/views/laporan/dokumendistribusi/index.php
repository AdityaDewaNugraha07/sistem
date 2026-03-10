<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Distribusi Dokumen';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model]) ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Distribusi Dokumen'); ?> <span id="periode-label" class="font-blue-soft"></span></span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="reload"> </a>
					<a href="javascript:;" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan">
					<thead>
						<tr>
							<th></th>
							<th><?= Yii::t('app', 'No. Dokumen') ?></th>
							<th><?= Yii::t('app', 'Nama Dokumen'); ?></th>
							<th><?= Yii::t('app', 'Revisi'); ?></th>
							<th><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
                            <th><?= Yii::t('app', 'Pengirim'); ?></th>
							<th><?= Yii::t('app', 'Penerima Dokumen'); ?></th>
                            <th><?= Yii::t('app', 'Status Pengiriman'); ?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php $this->registerJs("
	$('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
	formconfig(); 
	dtLaporan();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: {  
			url: '<?= \yii\helpers\Url::toRoute('/qms/laporan/dokumendistribusi') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{	targets: 0, visible: false },
			{	targets: 1, class:'td-kecil' },
			{	targets: 2, class:'td-kecil' },
			{	targets: 3, class:'text-align-center td-kecil' },
			{	targets: 4, class:'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
			},
			{	targets: 5, class:'text-align-center td-kecil', },
			{	targets: 6, class:'text-align-center td-kecil' },
            {	targets: 7, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
                    var ret = '';
                    if(data == true){
						if(full[8]){
							var date = new Date(full[8]);
							date = date.toString('dd/MM/yyyy HH:mm:ss');
							var tgl_terima = '<br><span class="td-kecil3">at : ' + date + '</span>';
						} else {
							var tgl_terima = '';
						}
                        var ret = '<span style="color:green;">Sudah diterima</span>' + tgl_terima;
                    } else if(data == false){
                        var ret = '<span style="color:red;">Belum diterima</span>';
                    }
                    return ret;
                }
            },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
		"autoWidth" : false,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/qms/laporan/dokumendistribusiPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>