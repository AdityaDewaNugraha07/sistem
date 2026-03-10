<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Dokumen Penjualan Kayu Bulat';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Dokumen Penjualan Kayu Bulat '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th>No.</th>
							<th style="line-height: 1"><?= Yii::t('app', 'Jenis<br>Dokumen') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Tanggal') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Dokumen') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Kode<br>Nota') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Customer') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Nopol') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Nama<br>Supir') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Alamat<br>Bongkar') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Petugas<br>TUK') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Noreg<br>Petugas') ?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php $this->registerJs("
    formconfig();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Lap. Dok. Penjualan KB'))."');
    $('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
	dtLaporan();
	changePertanggalLabel();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/tuk/laporan/dokumenPenjualanKb') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, class : 'td-kecil', orderable: false, width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 1, class: 'text-align-center td-kecil' },
			{ 	targets: 2, class: 'td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, class: 'text-align-center td-kecil' },
			{	targets: 4, class: 'text-align-center td-kecil' },
			{	targets: 5, class: 'td-kecil' },
			{	targets: 6, class: 'td-kecil' },
			{	targets: 7, class: 'td-kecil' },
			{	targets: 8, class: 'td-kecil' },
			{	targets: 9, class: 'text-align-center td-kecil' },
			{	targets: 10, class: 'td-kecil' },
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
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/tuk/laporan/dokumenPenjualanKbPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>