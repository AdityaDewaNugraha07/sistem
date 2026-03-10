<?php

use yii\helpers\Url;
use app\models\MMenu;
use yii\helpers\Json;
use yii\bootstrap\Html;
use app\assets\Select2Asset;
use app\assets\DatatableAsset;
use app\assets\InputMaskAsset;
use app\assets\DatepickerAsset;

DatatableAsset::register($this);
DatepickerAsset::register($this);
InputMaskAsset::register($this);
Select2Asset::register($this);

$this->title = 'Laporan Retur Penjualan Detail';
?>
<h1 class="page-title"> <?= $this->title; ?></h1>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model]) ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject hijau bold"><?= $this->title ?> <span id="periode-label" class="font-blue-soft"></span></span>
				</div>
				<div class="tools">
					<a href="javascript:void(0);" class="reload"> </a>
					<a href="javascript:void(0);" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan">
					<thead>
						<tr>
							<th>No</th>
							<th>Kode Retur</th>
							<th style="line-height: 1;">Tanggal<br>Retur</th>
							<th>Nomor Nota</th>
							<th>Sales</th>
							<th>Customer</th>
							<th>Alamat Customer</th>
							<th style="line-height: 1;">Jenis<br>Produk</th>
							<th>Produk</th>
							<th>Dimensi</th>
							<th>Pcs</th>
							<th>M<sup>3</sup></th>
							<th style="line-height: 1;">Harga<br>Jual</th>
							<th style="line-height: 1;">Harga<br>Retur</th>
							<th style="line-height: 1;">Sub<br>Total</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $this->registerJs("init()"); ?>
<script>
	function init() {
		setMenuActive('<?= Json::encode(MMenu::getMenuByCurrentURL("Retur Penjualan Detail")) ?>');

		$('#form-search-laporan').submit(function() {
			dtLaporan();
			return false;
		});

		formconfig();
		dtLaporan();
		changePertanggalLabel();

		$('select[name*="[cust_id]"]').select2({
			allowClear: !0,
			placeholder: 'Filter By Customer'
		})

	}

	function dtLaporan() {
		$('#table-laporan').dataTable({
			pageLength: 100,
			ajax: {
				url: '<?= Url::toRoute('/marketing/laporan/returPenjualanDetail') ?>',
				data: {
					dt: 'table-laporan',
					laporan_params: $("#form-search-laporan").serialize(),
				}
			},
			columnDefs: [{
					targets: 2,
					render: data => formatDateForUser(data)
				},
				{
					targets: [5, 6, 8],
					class: 'text-left td-kecil'
				},
				{
					targets: 11,
					class: 'text-right td-kecil'
				},
				{
					targets: [12, 13],
					class: 'text-right td-kecil',
					render: data => formatNumberForUser(data)
				},
				{ 	targets: 14, class: 'text-right td-kecil',
					render: function ( data, type, full, meta ) {
						var jnsproduk = full[7];
						var qty_kecil = full[10];
						var kubikasi = full[11];
						var harga = full[13];
						var subtotal = 0;
						if(jnsproduk == "Plywood" || jnsproduk == "Lamineboard" || jnsproduk == "Platform" || jnsproduk == "Limbah"){
							subtotal = qty_kecil * harga;
						}else{
							subtotal = kubikasi * harga;
						}
						return formatNumberForUser(Math.round(subtotal))
					}
				},
				{
					targets: '_all',
					class: 'text-center td-kecil'
				}
			],
			rowCallback: function(row, data, index) {
				var api = this.api();
				var startIndex = api.context[0]._iDisplayStart;
				$('td:eq(0)', row).html(index + 1 + startIndex);
			},
			drawCallback: function(oSettings) {
				formattingDatatableReport(oSettings.sTableId);
				changePertanggalLabel();
				if (oSettings.aLastSort[0]) {
					$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
					$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
				}
			},
			bDestroy: true,
		});
	}

	function printout(caraPrint) {
		const url = "<?= Url::toRoute('/marketing/laporan/returPenjualanDetailPrint') ?>?" + $('#form-search-laporan').serialize() + "&caraprint=" + caraPrint;
		// const options = 'location=_new, width=1200px, scrollbars=yes';
		window.open(url, "_blank");
	}

	function changePertanggalLabel() {
		if ($('#<?= Html::getInputId($model, 'tgl_awal'); ?>').val()) {
			$('#periode-label').html("Periode " + $('#<?= Html::getInputId($model, 'tgl_awal'); ?>').val() + " sd " + $('#<?= Html::getInputId($model, 'tgl_akhir'); ?>').val());
		} else {
			$('#periode-label').html("");
		}
	}
</script>