<?php
/* @var $this yii\web\View */
$this->title = 'Rekap Output Bandsaw';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Rekap Output Bandsaw '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Kode SPK'); ?></th>
							<th><?= Yii::t('app', 'Jenis Kayu'); ?></th>
							<th><?= Yii::t('app', 'Nomor<br>Bandsaw'); ?></th>
							<th><?= Yii::t('app', 'Size'); ?></th>
							<th><?= Yii::t('app', 'Panjang'); ?></th>
							<th><?= Yii::t('app', 'Qty'); ?></th>
                            <th><?= Yii::t('app', 'Volume (m<sup>3</sup>)'); ?></th>
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
			url: '<?= \yii\helpers\Url::toRoute('/ppic/laporan/rekapBandsaw') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{	targets: 0, class: 'text-align-center td-kecil'}, 
			{	targets: 1, class: 'text-align-center td-kecil'},
            {	targets: 2, class: 'text-align-center td-kecil'},
            // { 	targets: 3, class: "td-kecil text-align-center"},
			{	targets: 3, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return data + 'x' + full[4];
                }
			},
            {	targets: 4, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[5];
                }
			},
            {	targets: 5, class: 'text-align-center td-kecil',
				render: function ( data, type, full, meta ) {
					return full[6];
                }
			},
            {	targets: 6, class: 'text-align-right td-kecil',
				render: function ( data, type, full, meta ) {
					var kubikasi = full[3] * full[4] * full[5] * full[6] / 1000000; //cm3 convert to m3
                    return formatNumberFixed4(kubikasi);
                }
			}
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
			
		},
		order:[0, 4, 5, 6],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
		"autoWidth" : false,
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
	window.open("<?= yii\helpers\Url::toRoute('/ppic/laporan/rekapBandsawPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>