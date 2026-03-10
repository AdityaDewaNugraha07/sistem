<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Defect Sawmill';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Defect Sawmill '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
                            <th><?= Yii::t('app', 'Kode<br>Defect') ?></th>
							<th><?= Yii::t('app', 'Kode SPK'); ?></th>
							<th><?= Yii::t('app', 'Tanggal'); ?></th>
							<th><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                            <th><?= Yii::t('app', 'Line<br>Sawmill') ?></th>
							<th><?= Yii::t('app', 'Nomor<br>Bandsaw'); ?></th>
							<th><?= Yii::t('app', 'Size'); ?></th>
							<th><?= Yii::t('app', 'Panjang'); ?></th>
                            <th><?= Yii::t('app', 'Kategori<br>Defect'); ?></th>
							<th><?= Yii::t('app', 'Qty'); ?></th>
							<th><?= Yii::t('app', 'Keterangan'); ?></th>
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
    changePertanggalLabel();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/qc/laporan/defectswm') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{	targets: 0, visible:false},
			{	targets: 1, class: 'text-align-center td-kecil'},
            {	targets: 2, class: 'text-align-center td-kecil'},
            { 	targets: 3, class: "td-kecil text-align-center", 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return date;
                }
            },
			{	targets: 4, class: 'text-align-center td-kecil'},
            {	targets: 5, class: 'text-align-center td-kecil'},
            {	targets: 6, class: 'text-align-center td-kecil'},
            {	targets: 7, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return data + 'x' + full[8];
                }
			},
			{	targets: 8, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[9];
                }
			},
			{	targets: 9, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[10];
                }
			},
            {	targets: 10, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[11];
                }
			},
			{	targets: 11, class: 'td-kecil',
				render: function ( data, type, full, meta ) {
					return full[12]?full[12]:'<center>-</center>';
                }
			},
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
            changePertanggalLabel();
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

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/qc/laporan/defectswmPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>