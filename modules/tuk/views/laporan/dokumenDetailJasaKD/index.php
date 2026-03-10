<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Dokumen Penjualan Detail Jasa KD';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\MetronicAsset::register($this);
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Dokumen Penjualan Detail Jasa KD'); ?> <span id="periode-label" class="font-blue-soft"></span></span>
				</div>
				<div class="tools">
					<a onclick="printout('PDF')" style="display: inline-block; top: -2px; left: -1px; position: relative;"><i class="icon-doc"></i>PDF</a>
					<a onclick="printout('EXCEL')" style="display: inline-block; top: -2px; left: -1px; position: relative;"><i class="icon-doc"></i>Excel</a>
					<a onclick="printout('PRINT')" style="display: inline-block; top: -2px; left: -1px; position: relative; font-size: 15px;"><i class="fa fa-print"></i>Print</a>
					<a href="javascript:;" class="reload"> </a>
					<a href="javascript:;" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan">
					<thead>
						<tr>
							<th style="line-height: 1"><?= Yii::t('app', 'No') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Tanggal') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Dokumen') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Customer') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Produk') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'T') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'L') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'P') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Total<br>Pcs') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Total<br>M<sup>3</sup>') ?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php $this->registerJs("
	search();
	formconfig(); 
	changePertanggalLabel();
", yii\web\View::POS_READY); ?>
<script>
function search(){
	$("#table-laporan").addClass("animation-loading");
	var tgl_awal = $('#tdokumenpenjualandetail-tgl_awal').val();
	var tgl_akhir = $('#tdokumenpenjualandetail-tgl_akhir').val();
	var jenis_produk = $('#tdokumenpenjualandetail-jenis_produk').val();
	var cust_id = $('#tdokumenpenjualandetail-cust_id').val();
	var dokumen_penjualan_id = $('#tdokumenpenjualandetail-dokumen_penjualan_id').val();
	
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/tuk/laporan/dokumenDetailJasaKD']); ?>',
		type   : 'POST',
		data   : {tgl_awal:tgl_awal, tgl_akhir:tgl_akhir, jenis_produk:jenis_produk, cust_id:cust_id, dokumen_penjualan_id:dokumen_penjualan_id},
		success: function (data, total) {
			$("#table-laporan > tbody").html("");
			$("#table-laporan > foot").html("");
			if(data.html){
				$("#table-laporan > tbody").html(data.html);
			}
			$("#table-laporan").removeClass("animation-loading");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
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
	var tgl_awal = $('#tdokumenpenjualandetail-tgl_awal').val();
	var tgl_akhir = $('#tdokumenpenjualandetail-tgl_akhir').val();
	var jenis_produk = $('#tdokumenpenjualandetail-jenis_produk').val();
	var cust_id = $('#tdokumenpenjualandetail-cust_id').val();
	var dokumen_penjualan_id = $('#tdokumenpenjualandetail-dokumen_penjualan_id').val();
	window.open("<?= yii\helpers\Url::toRoute('/tuk/laporan/dokumenDetailJasaKDPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint+"&tgl_awal="+tgl_awal+"&tgl_akhir="+tgl_akhir+"&jenis_produk="+jenis_produk+"&cust_id="+cust_id+"&dokumen_penjualan_id="+dokumen_penjualan_id,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>