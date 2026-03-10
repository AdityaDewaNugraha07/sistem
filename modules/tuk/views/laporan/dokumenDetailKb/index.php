<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Dokumen Penjualan Detail Kayu Bulat';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Dokumen Penjualan Detail Kayu Bulat'); ?> <span id="periode-label" class="font-blue-soft"></span></span>
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
							<th rowspan="2" style="width: 5px;">No.</th>
							<th rowspan="2" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Tanggal') ?></th>
							<th rowspan="2" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Nomor Dokumen') ?></th>
							<th rowspan="2" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Customer') ?></th>
							<th rowspan="2" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Jenis Kayu') ?></th>
							<th rowspan="2" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'No Lap<br>No Grade<br>No Batang') ?></th>
							<th rowspan="2" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Range Diameter<br>(cm)') ?></th>
							<th rowspan="2" style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Panjang<br>(m)') ?></th>
							<th colspan="5" style="line-height: 1;"><?= Yii::t('app', 'Ukuran Diameter (cm)') ?></th>
							<th colspan="3" style="line-height: 1;"><?= Yii::t('app', 'Unsur Cacat (cm)') ?></th>
							<th rowspan="2" style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Total<br>M<sup>3</sup>') ?></th>
						</tr>
						<tr>
							<th style="width: 30px;"><?= Yii::t('app', 'Ujung 1'); ?></th>
							<th style="width: 30px;"><?= Yii::t('app', 'Ujung 2'); ?></th>
							<th style="width: 30px;"><?= Yii::t('app', 'Pangkal 1'); ?></th>
							<th style="width: 30px;"><?= Yii::t('app', 'Pangkal 2'); ?></th>
							<th style="width: 30px;"><?= Yii::t('app', 'Rata'); ?></th>
							<th style="width: 30px;"><?= Yii::t('app', 'Panjang'); ?></th>
							<th style="width: 30px;"><?= Yii::t('app', 'Gb'); ?></th>
							<th style="width: 30px;"><?= Yii::t('app', 'Gr'); ?></th>
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
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Dokumen Detail Kb'))."');
	dtLaporan();
	changePertanggalLabel();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/tuk/laporan/dokumenDetailKb') ?>',
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
			{	targets: 4, class: 'td-kecil',
				render: function ( data, type, full, meta ) {
					return data + ' - ' + full[5];
				}
			 },
			 {	targets: 5, class: 'td-kecil',
				render: function ( data, type, full, meta ) {
					return full[18] + '<br>' + full[19] + '<br>' + full[20];
				}
			 },
			{	targets: 6, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return data + ' - ' + full[7];
				}
			},
			{	targets: 7, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[8];
				}
			},
			{	targets: 8, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[9];
				}
			},
			{	targets: 9, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[10];
				}
			},
			{	targets: 10, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[11];
				}
			},
			{	targets: 11, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[12];
				}
			},
			{	targets: 12, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[13];
				}
			},
			{	targets: 13, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[14];
				}
			},
			{	targets: 14, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[15];
				}
			},
			{	targets: 15, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[16];
				}
			},
			{	targets: 16, class: 'text-align-right td-kecil',
				render: function ( data, type, full, meta ) {
					return full[17];
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
	window.open("<?= yii\helpers\Url::toRoute('/tuk/laporan/dokumenDetailKbPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>