<?php
/* @var $this yii\web\View */
$this->title = 'Hasil Produksi';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
#table-laporan thead th{
	font-size: 1.2rem;
}
</style>
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<div class="portlet-body">
                <ul class="nav nav-tabs">
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/hasilproduksi/index") ?>"> <?= Yii::t('app', 'Input Hasil Produksi'); ?> </a>
					</li>
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/hasilproduksi/LaporanHasilProduksi") ?>"> <?= Yii::t('app', 'Data Hasil Produksi'); ?> </a>
					</li>
				</ul>
                <?= $this->render('_searchHasilProduksi', ['model' => $model]) ?>
				<table class="table table-striped table-bordered table-hover" id="table-laporan">
					<thead>
						<tr>
							<th>No.</th>
							<th style="line-height: 1"><?= Yii::t('app', 'Tanggal') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Kode') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Kode<br>Barang Jadi') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Tanggal<br>Produksi') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Jenis Palet') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Nama Produk') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Dimensi') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Qty') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'm<sup>3</sup>') ?></th>
							<th style="line-height: 1; width: 60px;"><?= Yii::t('app', '') ?></th>
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
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Hasil Produksi'))."');
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/ppic/hasilproduksi/LaporanHasilProduksi') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, class : 'td-kecil', orderable: false,
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
			{ 	targets: 4, class: 'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 5, class: 'td-kecil' },
			{	targets: 6, class: 'td-kecil' },
			{	targets: 7, class: 'text-align-center td-kecil' },
			{	targets: 8, class: 'text-align-center td-kecil' },
			{	targets: 9, class: 'text-align-right td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+formatNumberForUser4Digit(data)+'</center>';
                }
            },
			{	targets: 10, class: 'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>\n\
                                <a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetailHasilProduksi('+full[0]+')" ><i class="fa fa-eye"></i></a>\n\
                                <a style="margin-left: -8px;" class="btn btn-xs btn-outline red-flamingo tooltips <?= (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER)?"":"hidden"; ?>" data-original-title="Hapus" onclick="openModal(\'<?php echo \yii\helpers\Url::toRoute(['/sysadmin/managetransaction/deleteHasilProduksi','id'=>'']) ?>'+full[0]+'&tableid=table-master\',\'modal-delete-record\')"><i class="fa fa-trash-o"></i></a>\n\
                            </center>';
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
        autoWidth:false,
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/tuk/laporan/dokumenDetailPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function lihatDetailHasilProduksi(id){
    window.open('<?= \yii\helpers\Url::toRoute(['/ppic/hasilproduksi/index','hasil_produksi_id'=>'']); ?>'+id, '_blank');
}
</script>