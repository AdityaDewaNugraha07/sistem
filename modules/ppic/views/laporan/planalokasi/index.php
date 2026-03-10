<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Plan Alokasi Stok Log';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Plan Alokasi Stok Log'); ?></span>
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
							<th style="width:150px;"><?= Yii::t('app', 'Jenis Alokasi') ?></th>
                            <th style="width:150px;"><?= Yii::t('app', 'Jenis Kayu') ?></th>
							<th style="width:150px;"><?= Yii::t('app', 'Jml Batang') ?></th>
							<th style="width:300px;"><?= Yii::t('app', 'Jml Kubikasi'); ?></th>
							<th style="width:100px;"></th>
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
			url: '<?= \yii\helpers\Url::toRoute('/ppic/laporan/planalokasi') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{	targets: 0, class: 'td-kecil'},
			{	targets: 1, class: 'td-kecil'},
            {	targets: 2, class: 'text-align-center td-kecil'},
			{	
				targets: 3, 
				class: 'text-align-right td-kecil' ,
				render: function ( data, type, full, meta ) {
					return formatNumberFixed2(data);
                }
			},
			{
				targets: 4, 
				class: 'text-align-center td-kecil' ,
				render: function ( data, type, full, meta ) {
					return '<a class="btn btn-xs btn-default" onclick="lihatdetail(\'' + full[0] + '\', \'' + full[4] + '\')"><i class="fa fa-search"></i> Lihat Detail </a>';
                }
			}
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
	window.open("<?= yii\helpers\Url::toRoute('/ppic/laporan/planalokasiPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function lihatdetail(jenis_alokasi, kayu){
	openModal('<?= \yii\helpers\Url::toRoute(['/ppic/planalokasi/lihatdetail','jenis_alokasi'=>'']) ?>'+jenis_alokasi+'&kayu_id='+kayu,'modal-lihatdetail', '90%');
}
</script>