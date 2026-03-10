<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Pembayaran TBP / LPB';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Rekap Penerimaan Bahan Pembantu '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Tanggal'); ?></th>
							<th><?= Yii::t('app', 'Kode') ?></th>
							<th><?= Yii::t('app', 'Reff Kode') ?></th>
							<th></th>
							<th><?= Yii::t('app', 'Nama Items') ?></th>
							<th><?= Yii::t('app', 'Qty') ?></th>
							<th><?= Yii::t('app', 'Satuan') ?></th>
							<th><?= Yii::t('app', 'Harga') ?></th>
							<th><?= Yii::t('app', 'Supplier') ?></th>
							<th><?= Yii::t('app', 'Invoice') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Payment<br>Status') ?></th>
							<th style="line-height: 1"><?= Yii::t('app', 'Payment<br>Reff') ?></th>
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
	$(this).find('select[name*=\"[suplier_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Supplier',
		width: null
	});
	$(this).find('select[name*=\"[bhp_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Bhp',
		width: null,
		ajax: {
			url: '". \yii\helpers\Url::toRoute('/logistik/spb/findBhpActive') ."',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		},
	});
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/finance/laporan/PembayaranTbp') ?>',
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
			{ 	targets: 1, class:'td-kecil', 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	
				targets: 2, 
				class: 'text-align-center td-kecil' ,
				render: function ( data, type, full, meta ) {
					var ret="<a onclick=\"infoTBP('"+full[0]+"')\">"+full[2]+"</a>";
					return ret;
                }
			},
			{ 	targets: 3, class:'td-kecil', 
                render: function ( data, type, full, meta ) {
					var ret = '-';
					if(full[3]){
						ret="<a onclick=\"infoSPL('"+full[11]+"')\">"+full[3]+"</a>";
					}else{
						ret="<a onclick=\"infoSPO('"+full[12]+"')\">"+full[4]+"</a>";
					}
					return ret;
                }
            },
			{	targets: 4, class:'td-kecil', visible: false },
			
			{	targets: 5, class: 'text-align-left td-kecil',
                render: function ( data, type, full, meta ) { 
					var ret = full[17];
					return ret;
                }
            },
			{	targets: 6, class: 'text-align-right td-kecil', 
				render: function ( data, type, full, meta ) { 
					var ret = formatNumberForUser(full[18]);
					return ret;
                }
			},
			{	targets: 7, class: 'text-align-left td-kecil', 
				render: function ( data, type, full, meta ) { 
					var ret = full[19];
					return ret;
                }
			},
			{	targets: 8, class: 'text-align-right td-kecil', 
				render: function ( data, type, full, meta ) { 
					var ret = formatNumberForUser(full[20]);
					return ret;
                }
			},
			
			{	targets: 9, class:'td-kecil' ,
                render: function ( data, type, full, meta ) {
					var ret = "-";
					if(full[5]){
						ret=full[5];
					}
					return ret;
                }
            },
			{	targets: 10, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					var ret = "-";
					if(full[6]){
						ret=full[6];
					}
					return ret;
                }
            },
			{	targets: 11, class: 'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					var ret = "-";
					if(full[10]){
						var ret = '<span class="label label-sm label-danger" style="font-size: 9px; padding: 2px 3px;"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>';
					}else{
						if(full[7]){
							ret=full[7];
						}else{
							if(full[8]){
								ret='PAID';
							}
						}
						if(ret=='UNPAID'){
							ret = "<b class='font-yellow'>UNPAID</b>";
						}else if(ret=='PAID'){
							ret = "<b class='font-green'>PAID</b>";
						}
                        if(full[21]){
                            ret = "<b class='font-red-flamingo'>RETUR</b>";
                        }
					}
					return ret;
                }
            },
			{	targets: 12, class: 'text-align-center td-kecil',
                render: function ( data, type, full, meta ) { 
					var ret = "-";
					if(full[10]){
						var ret = '<span class="label label-sm label-danger" style="font-size: 9px; padding: 2px 3px;"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>';
					}else{
						if(full[9]){
							ret="<a onclick=\"infoVoucher('"+full[13]+"')\">"+full[9]+"</a>";
						}
						if(full[8]){
							ret="<a onclick=\"infoKasKecil('"+full[8]+"','"+full[1]+"')\">"+full[8]+"</a>";
						}
					}
					return ret;
                }
            },
			{	targets: 13, visible: false },
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
	window.open("<?= yii\helpers\Url::toRoute('/finance/laporan/pembayaranTbpPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function infoTBP(terima_bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id,'modal-info-tbp','75%');
}
function infoTBP(terima_bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id,'modal-info-tbp','75%');
}
function infoSPO(id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpo']) ?>?id='+id,'modal-info-spo','75%');
}
function infoSPL(id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpl']) ?>?id='+id,'modal-info-spl','75%');
}
function infoVoucher(id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucher/detailBbk']) ?>?id='+id,'modal-bbk','21cm');
}
function infoKasKecil(kode,tgl){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/getRekapByTanggal','kode'=>'']) ?>'+kode+'&tanggal='+tgl,'modal-rekap');
}
</script>