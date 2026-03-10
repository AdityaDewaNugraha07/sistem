<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Tagihan Suplier BHP';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Rekap Tagihan Suplier'); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Supplier'); ?></th>
							<th style="width:180px;"><?= Yii::t('app', 'Total Tagihan') ?></th>
							<th style="width:180px;"><?= Yii::t('app', 'Lunas') ?></th>
							<th style="width:180px;"><?= Yii::t('app', 'Hutang') ?></th>
						</tr>
					</thead>
				</table>
				<div class="place-total">
					<table class="table table-striped table-bordered table-hover" style="width: 100%;">
						<tr style="background-color: #D7E1EC">
							<td style="text-align: right"><b>Total Page &nbsp; </b></td>
							<td style="width:190px; font-weight: 600;" class='text-align-center'>
								<span class='pull-left'>Rp. </span><span class='pull-right' id='place-totaltagihan'></span>
							</td>
							<td style="width:190px; font-weight: 600;" class='text-align-center'>
								<span class='pull-left'>Rp. </span><span class='pull-right' id='place-totallunas'></span>
							</td>
							<td style="width:190px; font-weight: 600;" class='text-align-center'>
								<span class='pull-left'>Rp. </span><span class='pull-right' id='place-totalhutang'></span>
							</td>
						</tr>
						<tr style="background-color: #D7E1EC">
							<td style="text-align: right"><b>Total Semua &nbsp; </b></td>
							<td style="width:190px; font-weight: 600;" class='text-align-center'>
								<span class='pull-left'>Rp. </span><span class='pull-right' id='place-totalalltagihan'></span>
							</td>
							<td style="width:190px; font-weight: 600;" class='text-align-center'>
								<span class='pull-left'>Rp. </span><span class='pull-right' id='place-totalalllunas'></span>
							</td>
							<td style="width:190px; font-weight: 600;" class='text-align-center'>
								<span class='pull-left'>Rp. </span><span class='pull-right' id='place-totalallhutang'></span>
							</td>
						</tr>
					</table>
				</div>
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
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/finance/laporan/TagihanSuplier') ?>',
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
			{ 	targets: 1, 
				render: function(data, type, full, meta){
					return "<span class='tooltips' data-original-title='Klik untuk lihat detail tagihan'><a onclick='showTBP("+full[0]+")' style='color:#333;' onMouseOver='this.style.color=\"#4B77BE\"' onMouseOut='this.style.color=\"#333\"'>"+
								data 
							+"</a> &nbsp; </span>";
				}
			},
			{ 	targets: 2, class:"text-align-right", 
				render: function(data, type, full, meta){
					return "<span class='pull-left'>Rp. </span>\n\
							<span class='pull-right'>"+
								((data)? formatNumberForUser(Math.round(data)) :0)
							+" &nbsp; </span>";
				}
			},
			{ 	targets: 3, class:"text-align-right",
				render: function(data, type, full, meta){
					return "<span class='pull-left'>Rp. </span>\n\
							<span class='pull-right'>"+
								((data)? formatNumberForUser(Math.round(data)) :0)
							+" &nbsp; </span>";
				}
			},
			{ 	targets: 4, class:"text-align-right",
				render: function(data, type, full, meta){
					return "<span class='pull-left'>Rp. </span><span class='pull-right'>"+((data)? formatNumberForUser(Math.round(data)) :0)+" &nbsp; </span>";
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
			
			var api = this.api(), data;
			// Remove the formatting to get integer data for summation
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};
			// Total over all pages
			var totaltagihan = api.column( 2 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
			var totallunas = api.column( 3 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
			var totalhutang = api.column( 4 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
			$("#place-totaltagihan").html( formatNumberForUser(Math.round(totaltagihan)) );
			$("#place-totallunas").html( formatNumberForUser(Math.round(totallunas)) );
			$("#place-totalhutang").html( formatNumberForUser(Math.round(totalhutang)) );
			setTotalSemua();
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
		"autoWidth":false,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/finance/laporan/TagihanSuplierPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function setTotalSemua(){
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/laporan/TagihanSuplier']); ?>',
        type   : 'POST',
        data   : {getTotalSemua:true,laporan_params:$("#form-search-laporan").serialize()},
        success: function (data) {
			$("#place-totalalltagihan").html( formatNumberForUser(Math.round(data.totaltagihan)) );
			$("#place-totalalllunas").html( formatNumberForUser(Math.round(data.paid)) );
			$("#place-totalallhutang").html( formatNumberForUser(Math.round(data.hutang)) );
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function showTBP(suplier_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/laporan/tagihanSuplier']) ?>?show=tbp&suplier_id='+suplier_id,'modal-show-tbp','75%');
}
function infoTBP(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp','id'=>'']) ?>'+id;
	var modal_id = 'modal-info-tbp';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
	});
	return false;
}
function infoSPO(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpo','id'=>'']) ?>'+id;
	var modal_id = 'modal-info-spo';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
	});
	return false;
}
function infoBBK(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/finance/voucher/detailBbk','id'=>'']) ?>'+id;
	var modal_id = 'modal-bbk';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
	});
	return false;
}
</script>