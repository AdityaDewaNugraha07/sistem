<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Rekapitulasi Penjualan Export';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Tabel Rekap Penjualan '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th colspan="3" style="line-height: 1;"><?= Yii::t('app', 'Invoice') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Volume<br>M<sup>3</sup>') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Buyer') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Address') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Produk') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Size') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Packinglist No.') ?></th>
							<th colspan="4" style="line-height: 1;"><?= Yii::t('app', 'Container') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Stuffing') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Payment<br>Method') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Term Of<br>Price') ?></th>
							<th colspan="2" style="line-height: 1;"><?= Yii::t('app', 'PEB') ?></th>
							<th colspan="3" style="line-height: 1;"><?= Yii::t('app', 'B/L') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'FOB') ?></th>
							<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Final<br>Destination') ?></th>
						</tr>
						<tr>
							<th style="line-height: 1;"><?= Yii::t('app', 'Kode') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'USD') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Nomor') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Qty') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Size') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Nomor') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Nomor') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Penerbit') ?></th>
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
			url: '<?= \yii\helpers\Url::toRoute('/exim/laporan/rekapPenjualan') ?>',
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
			{	targets: 2, class: 'text-align-center td-kecil3',
				render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
			},
			{	targets: 3, class: 'text-align-right td-kecil3',
				render: function ( data, type, full, meta ) {
                    // START KEBIJAKAN Perubahan Pembulatan dari Round Up ke Round Per tgl 14 Sept 2019
                    var tgl = new Date(full[2]); 
                    var tgl_kebijakan = new Date("2019-09-13"); 
                    if( tgl > tgl_kebijakan ){
                        return formatNumberForUser2Digit(data);
                    }else{
                        return formatNumberRoundUp2Digit(data);
                    }
                    // END KEBIJAKAN
					
					
                }
			},
			{	targets: 4, class: 'text-align-right td-kecil3',
				render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
                }
			},
			{	targets: 5, class: 'text-align-left td-kecil3',},
			{	targets: 6, class: 'text-align-left td-kecil3',},
			{	targets: 7, class: 'text-align-left td-kecil3',},
			{	targets: 8, class: 'text-align-left td-kecil3',
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
			{	targets: 9, class: 'text-align-left td-kecil3',},
			{	targets: 10, class: 'text-align-left td-kecil3',
				render: function ( data, type, full, meta ) {
					data = $.parseJSON(data);
					var ret = "";
					if(data){
						$(data).each(function(key,val){
							ret += val.container_kode+"<br>";
						});
					}
					return ret;
                }
			},
			{	targets: 11, class: 'text-align-left td-kecil3',
				render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
			},
			{	targets: 12, class: 'text-align-center td-kecil3',},
			{	targets: 13, class: 'text-align-center td-kecil3',
				render: function ( data, type, full, meta ) {
					data = $.parseJSON(data);
					var ret = "";
					if(data){
						$(data).each(function(key,val){
							ret += val.container_size+" Feet<br>";
						});
					}
					return ret;
                }
			},
			{	targets: 14, class: 'text-align-center td-kecil3',
				render: function ( data, type, full, meta ) {
					var ret = "";
					if(data){
						data = $.parseJSON(data);
						data = data[0].tanggal;
						var date = new Date(data);
						date = date.toString('dd/MM/yyyy');
						return '<center>'+date+'</center>';
					}
					return ret;
                }
			},
			{	targets: 15, class: 'text-align-center td-kecil3',},
			{	targets: 16, class: 'text-align-center td-kecil3',},
			{	targets: 17, class: 'text-align-center td-kecil3',},
			{	targets: 18, class: 'text-align-center td-kecil3',
				render: function ( data, type, full, meta ) {
					var ret = "";
					if(data){
						var date = new Date(data);
						date = date.toString('dd/MM/yyyy');
						ret = '<center>'+date+'</center>';
					}
					return ret;
                }
			},
			{	targets: 19, class: 'text-align-center td-kecil3',},
			{	targets: 20, class: 'text-align-center td-kecil3',
				render: function ( data, type, full, meta ) {
					var ret = "";
					if(data){
						var date = new Date(data);
						date = date.toString('dd/MM/yyyy');
						ret = '<center>'+date+'</center>';
					}
					return ret;
                }
			},
			{	targets: 21, class: 'text-align-left td-kecil3',
				render: function ( data, type, full, meta ) {
					return data;
                }
			},
			{	targets: 22, class: 'text-align-right td-kecil3',
				render: function ( data, type, full, meta ) {
					var ret = 0;
					if(data){
						ret = formatNumberForUser(data);
					}
					return ret;
                }
			},
			{	targets: 23, class: 'text-align-left td-kecil3',
				render: function ( data, type, full, meta ) {
					return data;
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
		order: [
            [1, 'desc']
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/exim/laporan/rekapPenjualanPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>