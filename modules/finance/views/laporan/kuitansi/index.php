<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Kuitansi Penerimaan';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Rekap Kuitansi Penerimaan'); ?><span id="periode-label" class="font-blue-soft"></span></span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="reload"> </a>
					<a href="javascript:;" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan" style="table-layout: fixed;">
					<thead>
						<tr>
							<th style="width: 50px;"><?= Yii::t('app', 'No.'); ?></th>
							<th style="width: 100px;"><?= Yii::t('app', 'Nomor') ?></th>
							<th style="width: 80px;"><?= Yii::t('app', 'Tanggal') ?></th>
							<th style="width: 100px;"><?= Yii::t('app', 'Cara Bayar') ?></th>
							<th style="width: 120px;"><?= Yii::t('app', 'Reff Penerimaan'); ?></th>
							<th style="width: 180px;"><?= Yii::t('app', 'Terima Dari') ?></th>
							<th style="width: 100px;"><?= Yii::t('app', 'Untuk Pembayaran') ?></th>
							<th style="width: 100px;"><?= Yii::t('app', 'Nominal') ?></th>
							<th><?= Yii::t('app', 'Keterangan') ?></th>
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
			url: '<?= \yii\helpers\Url::toRoute('/finance/laporan/kuitansi') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, class:"td-kecil text-align-center",
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 1, class:"td-kecil text-align-center",
				render: function ( data, type, full, meta ) {
					return "<a onclick='infoKuitansi(\""+full[0]+"\")'>"+data+"</a>";;
                }
			},
			{ 	targets: 2, class:"td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 3, class:"td-kecil text-align-center" },
			{ 	targets: 4, class:"td-kecil text-align-center",
				render: function( data, type, full, meta ){
					var ret = "";
					if(full[3]=="Tunai"){
						ret = "<a onclick='infoKasbesar(\""+data+"\")'>"+data+"</a>";
					}else{
						ret = "<a onclick='infoPiutang(\""+data+"\")'>"+data+"</a>";
					}
					return ret;
				}
			},
			{ 	targets: 5, class:"td-kecil text-align-left" },
			{ 	targets: 6, class:"td-kecil text-align-left" },
			{ 	targets: 7, class:"td-kecil text-align-right",
				render: function( data, type, full, meta ){
					return formatNumberForUser(data);
				}
			},
			{ 	targets: 8, class:"td-kecil text-align-left" },
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
		"autoWidth":false,
    });
}

function infoKuitansi(kuitansi_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/infoKuitansi']) ?>?kuitansi_id='+kuitansi_id,'modal-info-kuitansi');
}
function infoPiutang(kode){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/infoPiutang']) ?>?kode='+kode,'modal-info');
}
function infoKasbesar(par){
	var thn = par.split("/");
	var bln = thn[0].substring(2, 4); 
	var tgl = thn[0].substring(4, 6); 
	var tgl = thn[1]+'-'+bln+'-'+tgl;
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/saldokasbesar/getLaporanByTanggal']) ?>?tgl='+tgl,'modal-rekap');
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/finance/laporan/kuitansiPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>