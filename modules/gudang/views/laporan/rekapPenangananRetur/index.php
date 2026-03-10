<?php
/* @var $this yii\web\View */
$this->title = 'Rekap Penanganan Barang Retur';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Rekap Penanganan Barang Retur '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th rowspan="2"><?= Yii::t('app', 'No.'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Kode<br>Tanggal'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Status<br>Approval'); ?></th>
                            <th colspan="2"><?= Yii::t('app', 'Jml Permintaan Barang'); ?></th>
                            <th colspan="2"><?= Yii::t('app', 'Jml Dimutasi Dari Gudang'); ?></th>
                            <th colspan="2"><?= Yii::t('app', 'Jml Diterima Oleh PPIC'); ?></th>
                            <th colspan="2"><?= Yii::t('app', 'Jml Dikirim Ke Gudang'); ?></th>
                            <th colspan="2"><?= Yii::t('app', 'Jml Diterima Oleh Gudang'); ?></th>
						</tr>
                        <tr>
                            <th>Palet</th>
                            <th>Vol (m<sup>3</sup>)</th>
                            <th>Palet</th>
                            <th>Vol (m<sup>3</sup>)</th>
                            <th>Palet</th>
                            <th>Vol (m<sup>3</sup>)</th>
                            <th>Palet</th>
                            <th>Vol (m<sup>3</sup>)</th>
                            <th>Palet</th>
                            <th>Vol (m<sup>3</sup>)</th>
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
    setFilterByProdukGroup();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/gudang/laporan/rekapPenangananRetur') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, orderable: false, width: '5%', class: 'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 1, class: 'td-kecil', 
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return full[0] + '<br>' + date;
                }
            },
            { 	targets: 2, class: 'td-kecil text-align-center'},
            { 	targets: 3, class: 'td-kecil text-align-center'},
            { 	targets: 4, class: 'td-kecil text-align-right'},
            { 	targets: 5, class: 'td-kecil text-align-center'},
            { 	targets: 6, class: 'td-kecil text-align-right'},
            { 	targets: 7, class: 'td-kecil text-align-center'},
            { 	targets: 8, class: 'td-kecil text-align-right'},
            { 	targets: 9, class: 'td-kecil text-align-center'},
            { 	targets: 10, class: 'td-kecil text-align-right',
                render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
                }
            },
            { 	targets: 11, class: 'td-kecil text-align-center'},
            { 	targets: 12, class: 'td-kecil text-align-right',
                render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
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
	window.open("<?= yii\helpers\Url::toRoute('/gudang/laporan/rekapPenangananReturPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>