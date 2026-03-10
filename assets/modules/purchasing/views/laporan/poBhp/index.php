<?php
/* @var $this yii\web\View */
$this->title = 'Laporan PO Bahan Pembantu';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar Purchase Order Bahan Pembantu '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Kode PO') ?></th>
							<th><?= Yii::t('app', 'Tanggal Order') ?></th>
							<th></th>
							<th><?= Yii::t('app', 'Nama Item') ?></th>
							<th><?= Yii::t('app', 'Qty') ?></th>
							<th><?= Yii::t('app', 'Satuan') ?></th>
							<th><?= Yii::t('app', 'Harga') ?></th>
							<th><?= Yii::t('app', 'Suplier') ?></th>
							<th><?= Yii::t('app', 'Keterangan') ?></th>
							<th></th>
							<th><?= Yii::t('app', 'Kode TBP') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Tanggal<br>Rencana Kirim') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Tanggal<br>Penerimaan') ?></th>
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
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/laporan/poBhp') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',  class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 1, class:'td-kecil', },
			{ 	targets: 2, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, class:'td-kecil', visible: false },
			{ 	targets: 4, class:'td-kecil', },
			{ 	targets: 5, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 6, class:'td-kecil', },
			{ 	targets: 7, class:"text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(Math.round(data));
                }
            },
			{ 	targets: 8, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 9, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<span style="font-size:1.1rem">'+data+'</span>';
                }
            },
			{	targets: 10, visible: false },
			{ 	targets: 11, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					if(data){
						return "<a onclick='infoTBP("+full[9]+","+full[3]+")'>"+data+"</span>";
					}else{
						return "<center>-</center>"
					}
                }
            },
			{ 	targets: 12, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					if(data){
						var date = new Date(data);
						date = date.toString('dd/MM/yyyy');
						return '<center>'+date+'</center>';
					}else{
						return "<center>-</center>"
					}
                }
            },
			{ 	targets: 13, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					if(data){
						var date = new Date(data);
						date = date.toString('dd/MM/yyyy');
						return '<center>'+date+'</center>';
					}else{
						return "<center>-</center>"
					}
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
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/laporan/poBhpPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function infoTBP(terima_bhp_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id+'&bhp_id='+bhp_id,'modal-info-tbp','75%');
}
</script>