<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Pengajuan Tagihan Bahan Pembantu';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar Tagihan '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th style="line-height: 1" class="td-kecil"><?= Yii::t('app', 'Tanggal<br>Pengajuan'); ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Kode<br>Terima') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Kode Reff') ?></th>
							<th></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Supplier') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Tanggal<br>Nota') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Nota') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Kuitansi') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Berkas Ajuan') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Nominal') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Status') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', '14 Hari') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', '> 14 Hari') ?></th>
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
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/laporan/pengajuanTagihan') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, class:"td-kecil",
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 1,  class:"td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 2, class:"td-kecil" },
			{ 	targets: 3, class:"td-kecil", 
				render: function(data, type, full, meta){
					if(full[3]){
						return full[3];
					}
					if(full[4]){
						return full[4];
					}
				}
			},
			{ 	targets: 4, visible:false },
			{ 	targets: 5, class:"td-kecil" },
			{ 	targets: 6,  class:"td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 7, class:"td-kecil" },
			{ 	targets: 8, class:"td-kecil" },
			{ 	targets: 9, class:"td-kecil2 text-align-left", 
				render: function(data, type, full, meta){
					var ret = '';
					if(data){
						var berkas = $.parseJSON(data);
						if(berkas.is_notaasli){
							if(berkas.is_notaasli == "1"){
								ret += '<i class="fa fa-check font-green-haze"></i> Nota Asli<br>';
							}else{
								ret += '<i class="fa fa-remove font-red-flamingo"></i> Nota Asli<br>';
							}
						}
						if(berkas.is_kuitansi){
							if(berkas.is_kuitansi == "1"){
								ret += '<i class="fa fa-check font-green-haze"></i> Kuitansi<br>';
							}else{
								ret += '<i class="fa fa-remove font-red-flamingo"></i> Kuitansi<br>';
							}
						}
						if(berkas.is_fakturpajak){
							if(berkas.is_fakturpajak == "1"){
								ret += '<i class="fa fa-check font-green-haze"></i> Faktur Pajak<br>';
							}else{
								ret += '<i class="fa fa-remove font-red-flamingo"></i> Faktur Pajak<br>';
							}
						}
						if(berkas.is_suratjalan){
							if(berkas.is_suratjalan == "1"){
								ret += '<i class="fa fa-check font-green-haze"></i> Surat Jalan<br>';
							}else{
								ret += '<i class="fa fa-remove font-red-flamingo"></i> Surat Jalan<br>';
							}
						}
					}
					return ret;
				}
			},
			{ 	targets: 10, class:"td-kecil text-align-right", 
				render: function(data, type, full, meta){
					if(data){
						return formatNumberForUser(data);
					}
				}
			},
			{ 	targets: 11, class:"td-kecil" },
			{ 	targets: 12, class:"td-kecil text-align-center", 
				render: function(data, type, full, meta){
					var d1 = full[1];
					var d2 = full[6];
					var date1 = new Date(d1);
					var date2 = new Date(d2);
					var date1_ms = date1.getTime();
					var date2_ms = date2.getTime();
					var diff = date1_ms-date2_ms;
					var days = diff/1000/60/60/24;
					if(days <= 14){
						var ret = "1";
					}else{
						var ret = "0";
					}
					return ret;
				}
			},
			{ 	targets: 13, class:"td-kecil text-align-center", 
				render: function(data, type, full, meta){
					var d1 = full[1];
					var d2 = full[6];
					var date1 = new Date(d1);
					var date2 = new Date(d2);
					var date1_ms = date1.getTime();
					var date2_ms = date2.getTime();
					var diff = date1_ms-date2_ms;
					var days = diff/1000/60/60/24;
					if(days > 14){
						var ret = "1";
					}else{
						var ret = "0";
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
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/laporan/pengajuanTagihanPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>