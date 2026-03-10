<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Dokumen Penjualan Detail';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Dokumen Penjualan Detail'); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th style="line-height: 1"><?= Yii::t('app', 'Tanggal') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Dokumen') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Customer') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Produk') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'T') ?></th><th></th>
							<th style="line-height: 1"><?= Yii::t('app', 'L') ?></th><th></th>
							<th style="line-height: 1"><?= Yii::t('app', 'P') ?></th><th></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Total<br>Palet') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Total<br>Pcs') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Total<br>M<sup>3</sup>') ?></th>
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
			url: '<?= \yii\helpers\Url::toRoute('/tuk/laporan/dokumenDetail') ?>',
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
			{ 	targets: 1, class: 'td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 2, class: 'text-align-center td-kecil' },
			{	targets: 3, class: 'text-align-center td-kecil' },
			{	targets: 4, class: 'td-kecil' },
			{	targets: 5, class: 'td-kecil' },
			{	targets: 6, class: 'td-kecil text-align-center',
				render: function ( data, type, full, meta ) {
					if(data!=0){
						return data+" "+full[7];
					}else{
						return full[16]+" "+full[7];
					}
                }
			},
			{	targets: 7, visible : false },
			{	targets: 8, class: 'td-kecil text-align-center',
				render: function ( data, type, full, meta ) {
					if(data!=0){
						return data+" "+full[9];
					}else{
						return full[17]+" "+full[9];
					}
                }
			},
			{	targets: 9, visible : false },
			{ 	targets: 10, class : 'td-kecil text-align-right', 
				render: function ( data, type, full, meta ) {
					if(data!=0){
						return data+" "+full[11];
					}else{
						return full[18]+" "+full[11];
					}
                }
			},
			{	targets: 11, visible : false },
			{ 	targets: 12, class : 'td-kecil text-align-center', orderable: false,
				render: function ( data, type, full, meta ) {
					if( full[6]==0 || full[8]==0 || full[10]==0 ){
						var ret = "-";
					}else{
						var ret = formatNumberForUser(data);
					}
					return ret;
                }
			},
			{ 	targets: 13, class : 'td-kecil text-align-right', orderable: false,
                render: function ( data, type, full, meta ) {
					var ret = 0;
					if( full[6]==0 || full[8]==0 || full[10]==0 ){
						if( unformatNumber(full[19]) >0){
							ret = formatNumberForUser(full[19]);
						}
					}else{
						if( unformatNumber(data) >0){
							ret = formatNumberForUser(data);
						}
					}
					return ret;
                }
            },
			{ 	targets: 14, class : 'td-kecil text-align-right', orderable: false,
                render: function ( data, type, full, meta ) {
					var ret = 0;
					if( full[6]==0 || full[8]==0 || full[10]==0 ){
						if( unformatNumber(full[20]) >0){
							ret = formatNumberForUser(full[20]);
						}
					}else{
						if( unformatNumber(data) >0){
							ret = formatNumberForUser(data);
						}
					}
					return ret;
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
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/tuk/laporan/dokumenDetailPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>