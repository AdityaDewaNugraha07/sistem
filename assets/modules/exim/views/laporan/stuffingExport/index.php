<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Stuffing Export';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
#table-laporan thead th{
	font-size: 1.2rem;
}
</style>
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model]) ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Tabel Stuffing Export '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Kode SPM') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'No Inv / PL') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>SPM') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Kirim') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Customer') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Alamat') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Final Destination') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Supir') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Nopol') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'No. Cont') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Cont<br>Size') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Size') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Total<br>Palet') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Total<br>Qty') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Total<br>m<sup>3<sup>') ?></th>
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
	$('select[name*=\"[cust_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Filter By Customer',
	});
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/exim/laporan/stuffingExport') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{	targets: 0, visible: false },
			{	targets: 1, class: 'text-align-center td-kecil3',
				render: function ( data, type, full, meta ) {
					return data;
                }
			},
			{	targets: 2, class: 'text-align-center td-kecil3', },
			{	targets: 3, class: 'text-align-center td-kecil3',
				render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
			},
			{	targets: 4, class: 'text-align-center td-kecil3',
				render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
			},
			{	targets: 5, class: 'text-align-left td-kecil3', },
			{	targets: 6, class: 'text-align-left td-kecil3', },
			{	targets: 7, class: 'text-align-left td-kecil3', },
			{	targets: 8, class: 'text-align-left td-kecil3', },
			{	targets: 9, class: 'text-align-left td-kecil3', },
			{	targets: 10, class: 'text-align-center td-kecil3', },
			{	targets: 11, class: 'text-align-center td-kecil3', },
			{	targets: 12, class: 'text-align-center td-kecil3', },
			{	targets: 13, class: 'text-align-left td-kecil3',
				render: function ( data, type, full, meta ) {
					data = $.parseJSON(data);
					var ret = ""; var thick=""; var width=""; var length="";
					if(data){
						$(data).each(function(key,val){
							if(val.min_thick != val.max_thick){
								thick += val.min_thick+"/"+val.max_thick+val.thick_unit+" x ";
							}else{
								thick += val.min_thick+val.thick_unit+" x ";
							}
							if(val.min_width != val.max_width){
								width += val.min_width+"/"+val.max_width+val.width_unit+" x ";
							}else{
								width += val.min_width+val.width_unit+" x ";
							}
							if(val.min_length != val.max_length){
								length += val.min_length+"-"+val.max_length+val.length_unit;
							}else{
								length += val.min_length+val.length_unit;
							}
						});
					}
					ret = thick+width+length;
					return ret;
                }
			},
			{	targets: 14, class: 'text-align-center td-kecil3', },
			{	targets: 15, class: 'text-align-right td-kecil3', },
			{	targets: 16, class: 'text-align-right td-kecil3', },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		order: [
            [2, 'desc']
        ],
		'autoWidth':false,
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/exim/laporan/stuffingExportPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>