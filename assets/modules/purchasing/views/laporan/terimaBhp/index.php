<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Penerimaan Bahan Pembantu';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar Penerimaan Bahan Pembantu '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Kode Terima') ?></th>
							<th><?= Yii::t('app', 'Tanggal Terima') ?></th>
							<th><?= Yii::t('app', 'Supplier') ?></th>
							<th><?= Yii::t('app', 'Kode Item') ?></th>
							<th><?= Yii::t('app', 'Nama Item') ?></th>
							<th><?php echo Yii::t('app', 'Satuan') ?></th>
							<th><?php echo Yii::t('app', 'Qty') ?></th>
							<th><?php echo Yii::t('app', 'Harga Satuan') ?></th>
							<th><?php echo Yii::t('app', 'Ppn') ?></th>
							<th><?php echo Yii::t('app', 'Pph') ?></th>
							<th><?php echo Yii::t('app', 'PBBKB') ?></th>
							<th><?php echo Yii::t('app', 'Total') ?></th>
							<th><?php echo Yii::t('app', 'Keterangan') ?></th>
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
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/laporan/terimaBhp') ?>',
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
			{ 	targets: 1, class:"td-kecil" }, // Kode
			{ 	targets: 2, // Tanggal
				class:"td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 3, class:"td-kecil" }, // Supplier
			{ 	targets: 4, class:"td-kecil" }, // Kode 
			{ 	targets: 5, //
				class:"td-kecil",
                render: function ( data, type, full, meta ) {
					var parse1 = $.trim(data.split('/')[1]);
					var parse2 = '';
					var parse3 = '';
					if($.trim(data.split('/')[2])){
						parse2 = '/'+$.trim(data.split('/')[2]);
					}
					if($.trim(data.split('/')[3])){
						parse3 = '/'+$.trim(data.split('/')[3]);
					}
					var ret = parse1+parse2+parse3;
					return ret;
                }
            },
			{ 	targets: 6, // Qty
				class:"td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 7, // Satuan
				class:"td-kecil text-align-right",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 8, // Harga
				className: 'td-kecil dt-body-right',
                render: function ( data, type, full, meta ) {
					<?php 
					if (empty($hide)) {
					?>
						if(full[15]) {
							var mu = full[15];
						} else {
							var mu = "Rp";
						}
						//return "<span class='pull-left'>"+mu+"</span>"+formatInteger(data);
						return formatInteger(data);
					<?php 
					} else {
					?>
					return '';
					<?php 
					}
					?>
                }
            },
			{ 	targets: 9, // PPn
				className: 'td-kecil dt-body-right',
                render: function ( data, type, full, meta ) {
					var ret = "<i style='font-size:1.1rem'>Non-PKP</i>";
					if( ((full[10]!=null) && (full[13]!=0)) || (full[13]!=0) ){
                        ret = formatInteger(data);
					}
					
					<?php 
					if(empty($hide)) {
					?>
						return ret;
					<?php 
					} else {
					?>
						return '';
					<?php
					}
					?>
                }
            },
			{ 	targets: 10, // PPh (spo_id)
				className: 'td-kecil dt-body-right',
                render: function ( data, type, full, meta ) {
					if(full[14]){
						var ret = formatNumberForUser(full[14]);
					}else{
						var ret = 0;
					}
					<?php if(empty($hide)){ ?>
						return ret;
					<?php }else{ ?>
						return '';
					<?php } ?>
					
                }
            },
            { targets: 11, // PBBKB
				className: 'td-kecil dt-body-right',
                render: function ( data, type, full, meta ) {
					var total = full[16];

					if (total > 0) {
						return formatInteger(total);
					} else {
						return formatInteger(total);
					}

                }
            },
			{ 	targets: 12, // Total Bayar
				className: 'td-kecil dt-body-right',
                render: function ( data, type, full, meta ) {

					// mata uang
					if (full[15]) {
						var mu = full[15];
					} else {
						var mu = "Rp";
					}

					// total 
					var total = full[7] * full[8];

					// ppn
					if (full[13] > 0) {
						ppn = total * 0.1;
					} else {
						ppn = 0;
					}

					// pbbkb
					if (full[16] > 0) {
						total_pbbkb = full[16];
					} else {
						total_pbbkb = 0;
					}

					<?php 
					if(empty($hide)){ ?>
						var grand_total = total + total_pbbkb;
						return "<span class='pull-left'>"+mu+"</span>"+formatInteger(parseInt(total) + parseInt(ppn) + parseInt(total_pbbkb));
					<?php 
					} else {
					?>
						return '';
					<?php 
					}
					?>
                }
            },
			{ 	targets: 13, // Keterangan
				class: 'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<span style="padding:3px;font-size:1.1rem;">'+full[12]+'</span>';
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
	$('td:eq(2)').addClass('semok');
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/laporan/terimaBhpPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>