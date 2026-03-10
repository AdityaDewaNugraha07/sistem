<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Penjualan Lokal Detail';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Penjualan Detail Produk Lokal '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th ><?= Yii::t('app', 'Kode Nota') ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Nota') ?></th>
							<th ><?= Yii::t('app', 'Sales'); ?></th>
							<th ><?= Yii::t('app', 'Customer'); ?></th>
							<th ><?= Yii::t('app', 'Alamat Customer'); ?></th>
							<th style="line-height: 1;"><?= Yii::t('app', 'Jenis<br>Produk'); ?></th>
							<th ><?= Yii::t('app', 'Produk'); ?></th>
							<th ><?= Yii::t('app', 'Dimensi'); ?></th>
							<th ><?= Yii::t('app', 'Pcs'); ?></th>
							<th ><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
							<th  style="line-height: 1;"><?= Yii::t('app', 'Harga<br>Satuan'); ?></th>
							<th  style="line-height: 1;"><?php echo Yii::t('app', 'Total<br>Harga'); ?></th>
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
			url: '<?= \yii\helpers\Url::toRoute('/marketing/laporan/penjualanLokalDetail') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{	targets: 0, visible: false },
			{	targets: 1, class: 'text-align-center td-kecil' },
			{ 	targets: 2, class: ' td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, class: 'text-align-center  td-kecil' },
			{	targets: 4, class: 'text-align-left  td-kecil' },
			{	targets: 5, class: 'text-align-left  td-kecil' },
			{	targets: 6, class: 'text-align-center  td-kecil' },
			{	targets: 7, class: 'text-align-left  td-kecil',
                render: function ( data, type, full, meta ) {
                    if( full[6] == "Limbah" ){
                        return full[20]+" - ("+full[19]+") "+full[16];
                    }else{
                        return data;
                    }
                }
            },
			{	targets: 8, class: 'text-align-center  td-kecil',
                render: function ( data, type, full, meta ) {
                    if( full[6] == "Limbah" ){
                        return "-";
                    }else{
                        return data;
                    }
                }
            },
			{	targets: 9, class: 'text-align-right td-kecil' },
			{ 	targets: 10, class: 'text-align-right td-kecil',
                render: function ( data, type, full, meta ) {
                    var jnsproduk = full[6];
                    if( jnsproduk == "Limbah" ){
                        if(full[17] == "Rit"){
                            return full[18];
                        }else{
                            return "-";
                        }
                    }else{
                        return formatNumberFixed4(data);
                    }
                }
            },
			{ 	targets: 11, class: 'text-align-right td-kecil',
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                    }
            },
			{ 	targets: 12, class: 'text-align-right td-kecil',
                render: function ( data, type, full, meta ) {
					var jnsproduk = full[6];
					var qty_kecil = full[9];
					var kubikasi = full[10];
					var harga = full[11];
					var subtotal = 0;
					if(jnsproduk == "Plywood" || jnsproduk == "Lamineboard" || jnsproduk == "Platform" || jnsproduk == "Limbah"){
						subtotal = qty_kecil * harga;
					}else{
						subtotal = kubikasi * harga;
					}
					return formatNumberForUser(subtotal);
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
	window.open("<?= yii\helpers\Url::toRoute('/marketing/laporan/PenjualanLokalDetailPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>