<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Faktur Pajak Belum Diterima';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);

?>
<?php $hide = ''; if(Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_LOGISTIC){ $hide = 'none'; } ?>
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', $this->title); ?>
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
                            <th><?= Yii::t('app', 'No.'); ?></th>
							<th style="width: 80px;"><?= Yii::t('app', 'Tanggal') ?></th>
							<th style="width: 100px;"><?= Yii::t('app', 'TBHP Kode') ?></th>
							<th style="width: 100px;"><?= Yii::t('app', 'SPL Kode') ?></th>
							<th style="width: 100px;"><?= Yii::t('app', 'SPO Kode') ?></th>
							<th><?= Yii::t('app', 'Supplier') ?></th>
							<th style="width: 100px;"><?= Yii::t('app', 'No. Faktur') ?></th>
							<th style="width: 100px;"><?= Yii::t('app', 'Total Bayar') ?></th>
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
    $('#".yii\bootstrap\Html::getInputId($model, 'suplier_id')."').select2({ 
		allowClear: !0, 
		placeholder: 'Pilih Suplier', 
		width: null, 
	});
", yii\web\View::POS_READY); ?>

<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/laporan/terimaBhpFilterNoFaktur') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},

        columnDefs: [
			{ 	targets: 0, // No
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 1, class:"td-kecil text-center",
				orderable: true,
				render: function ( data, type, full, meta ) {
					var date = full[1];
					var tglterima = date.split('-');
					tanggal = tglterima[2];
					bulan = tglterima[1];
					tahun = tglterima[0];
					return tanggal+'/'+bulan+'/'+tahun;
                } 
			},
			{ 	targets: 2, class:"td-kecil",
				orderable: true,
				render: function ( data, type, full, meta ) {
					return full[2];
                }
			},
			{ 	targets: 3, class:"td-kecil",
				orderable: true,
				render: function ( data, type, full, meta ) {
					return full[3];
                }
			},
			{ 	targets: 4, class:"td-kecil",
				orderable: true,
				render: function ( data, type, full, meta ) {
					return full[4];
                }
			},
			{ 	targets: 5, class:"td-kecil",
				orderable: true,
				render: function ( data, type, full, meta ) {
					return full[5];
                }
			},
			{ 	targets: 6, class:"td-kecil text-center",
				orderable: true,
				render: function ( data, type, full, meta ) {
					return full[6];
                }
			},
			{ 	targets: 7, class:"td-kecil text-right",
				orderable: true,
				render: function ( data, type, full, meta ) {
                    return full[7].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                } 
			},
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
	$('td:eq(2)').addClass('semoko');
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/laporan/terimaBhpFilterNoFakturPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=800px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>