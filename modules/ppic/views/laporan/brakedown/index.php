<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Input Brakedown';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Input Brakedown '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th rowspan="2"></th>
                            <th rowspan="2"><?= Yii::t('app', 'Kode<br>Brakedown') ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Jenis Kayu') ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Kode SPK'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Tanggal'); ?></th>
                            <th rowspan="2"><?= Yii::t('app', 'Line<br>Sawmill') ?></th>
                            <th rowspan="2"><?= Yii::t('app', 'No.<br>Barcode') ?></th>
							<th rowspan="2"><?= Yii::t('app', 'No. Lap') ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Grade'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Panjang'); ?></th>
							<th colspan="4"><?= Yii::t('app', 'Diameter'); ?></th>
							<th colspan="3"><?= Yii::t('app', 'Ukuran Cacar'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Volume (m<sup>3</sup>)'); ?></th>
						</tr>
						<tr>
							<th><?= Yii::t('app', 'Ujung1'); ?></th>
							<th><?= Yii::t('app', 'Ujung2'); ?></th>
							<th><?= Yii::t('app', 'Pangkal1'); ?></th>
							<th><?= Yii::t('app', 'Pangkal2'); ?></th>
							<th><?= Yii::t('app', 'Panjang'); ?></th>
							<th><?= Yii::t('app', 'Gb'); ?></th>
							<th><?= Yii::t('app', 'Gr'); ?></th>
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
			url: '<?= \yii\helpers\Url::toRoute('/ppic/laporan/brakedown') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{	targets: 0, visible:false},
			{	targets: 1, class: 'text-align-center td-kecil'},
            {	targets: 2, class: 'text-align-center td-kecil'},
			{ 	targets: 3, class: "td-kecil text-align-center"},
            { 	targets: 4, class: "td-kecil text-align-center", 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return date;
                }
            },
            {	targets: 5, class: 'text-align-center td-kecil'},
            {	targets: 6, class: 'text-align-center td-kecil'},
            {	targets: 7, class: 'text-align-center td-kecil'},
			{	targets: 8, class: 'text-align-center td-kecil'},
			{	targets: 9, class: 'text-align-right td-kecil'},
			{	targets: 10, class: 'text-align-right td-kecil'},
			{	targets: 11, class: 'text-align-right td-kecil'},
			{	targets: 12, class: 'text-align-right td-kecil'},
			{	targets: 13, class: 'text-align-right td-kecil'},
			{	targets: 14, class: 'text-align-right td-kecil'},
			{	targets: 15, class: 'text-align-right td-kecil'},
			{	targets: 16, class: 'text-align-right td-kecil'},
			{	targets: 17, class: 'text-align-right td-kecil'},
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
	window.open("<?= yii\helpers\Url::toRoute('/ppic/laporan/brakedownPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>