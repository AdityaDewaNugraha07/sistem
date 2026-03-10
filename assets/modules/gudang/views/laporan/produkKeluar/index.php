<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Produk Keluar';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
\app\assets\InputMaskAsset::register($this);
app\assets\StickytableheaderAsset::register($this);
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
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan">
					<thead>
						<tr>
							<th><?= Yii::t('app', 'No.'); ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Kode<br>Barang Jadi') ?></th>
							<th><?= Yii::t('app', 'Produk') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Tanggal Keluar') ?></th>
							<th><?= Yii::t('app', 'Reff No.') ?></th>
							<th><?= Yii::t('app', 'Pcs') ?></th>
							<th><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
							<th><?= Yii::t('app', 'Keterangan') ?></th>
							<th></th>
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
	$('table').stickytableheader();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/gudang/laporan/produkKeluar') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 1, class:"td-kecil" },
			{ 	targets: 2, class:"td-kecil" },
			{ 	targets: 3, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 4, class:"td-kecil" },
			{ 	targets: 5, class: "td-kecil text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{ 	targets: 6, class: "td-kecil text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{ 	targets: 7, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					var ret = data;
					if(data=="PENJUALAN"){
						ret= data+" Ke <b>"+full[8]+"</b>";
					}
					return ret;	
                }
            },
			{ 	targets: 8, visible: false },
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
	window.open("<?= yii\helpers\Url::toRoute('/gudang/laporan/produkKeluarPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>