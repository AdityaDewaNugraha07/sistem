<?php
/* @var $this yii\web\View */
$this->title = 'Monitoring Permintaan Pembelian';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar Permintaan Pembelian Bahan Pembantu '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Kode SPP') ?></th>
							<th><?= Yii::t('app', 'Tanggal<br> Permintaan') ?></th>
							<th><?= Yii::t('app', 'Nama Item') ?></th>
							<th><?= Yii::t('app', 'Qty<br> Dipesan') ?></th>
							<th><?= Yii::t('app', 'Qty<br> Terpenuhi') ?></th>
							<th><?= Yii::t('app', 'Satuan') ?></th>
							<th><?= Yii::t('app', 'Dept Pemesan') ?></th>
							<th><?= Yii::t('app', 'SPB') ?></th>
							<th><?= Yii::t('app', 'Penawaran<br>Terpilih') ?></th>
							<th><?= Yii::t('app', 'Reff Pembelian') ?></th>
							<th><?= Yii::t('app', 'Reff Terima') ?></th>							
							<th><?= Yii::t('app', 'Status') ?></th>
							
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
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/laporan/MonitoringSpp') ?>',
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
			{ 	targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 4, 
                render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 5, 
                render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 6, 
                render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 7, 
                render: function ( data, type, full, meta ) {					
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 8, 
                render: function ( data, type, full, meta ) {
					var data = '';
					if(full[13] == null){
						data = '';
					}else{
						const textreff = full[13];
						const objfull8 = JSON.parse(textreff);		
						objfull8.forEach((element) => {
							const spbkode = element["spbkode"];
							const spbdid = element["spbdid"];	
							data += '<a title="'+spbdid+','+spbkode+'" onclick="infoSpb('+spbdid+','+full[0]+')">'+spbkode+'</a><br>';					
						});		
					}
					return '<center>'+data+' </center>';
                }
            },
			{ 	targets: 9, 
                render: function ( data, type, full, meta ) {
					var data = '';
					if(full[15] == null){
						data = '';
					}else{
						data = '<a title="'+full[15]+'" onclick="infoPenawaranBhp('+full[0]+')">'+full[15]+'</a>';		
					}
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 10, width: '120px',
                render: function ( data, type, full, meta ) {
					var data1 = '';
					if(full[8] == null){
						data1 = '';
					}else{
						const textreff = full[8];
						const objfull8 = JSON.parse(textreff);		
						objfull8.forEach((element) => {
							const reffno = element["reffno"];
							const id = element["reffdetailid"];	
							data1 += '<a title="'+id+','+reffno+'" onclick="info('+id+','+full[0]+')">'+reffno+'</a><br>';					
						});		
					}
					return '<center>'+data1+' </center>';
                }
            },
			{ 	targets: 11, width: '120px',
                render: function ( data, type, full, meta ) {
					var dataTerima = '';
					if(full[12] == null){
						dataTerima = '';
					}else{
						const texttbp = full[12];
						const objfull12 = JSON.parse(texttbp);		
						objfull12.forEach((element) => {
							const terimabhpkode = element["terimabhp_kode"];
							const terimabhpdid = element["terima_bhpd_id"];
							// console.log(terimabhpkode+" id "+terimabhpdid);		
							dataTerima += '<a title="'+terimabhpdid+','+terimabhpkode+'" onclick="infoPenerimaan('+terimabhpdid+','+full[0]+')">'+terimabhpkode+'</a><br>';	
							// console.log(dataTerima);					
						});		
					}
					return '<center>'+dataTerima+' </center>';
                }
            },
			{ 	targets: 12, 
                render: function ( data, type, full, meta ) {
					var data = '';
					if(full[9] == null){
						if(full[4] > full[5]){
							if(full[5] > 0 && full[5] < full[4]){
								data = 'PARTIAL';
							}else{
								data = '-';
							}
						}else if(full[4] == full[5] || full[4] < full[5]){
							data = 'COMPLETE';
						}
					}else{
						data = 'CLOSED';		
					}
					return '<center>'+data+'</center>';
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
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/laporan/monitoringSppPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
function info(id,sppdid){
	console.log(sppdid+" ini reff_detail_id: "+id); //,'id'=>''
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/laporan/info']) ?>?id='+id+"&sppdid="+sppdid,'modal-master-info','90%'," $('#table-master').dataTable().fnClearTable(); ");
}
function infoPenerimaan(id,sppdid){
	console.log(sppdid+" ini terima_bhpd_id: "+id);
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/laporan/infoPenerimaan']) ?>?id='+id+"&sppdid="+sppdid,'modal-master-info','90%'," $('#table-master').dataTable().fnClearTable(); ");
}
function infoSpb(id,sppdid){
	console.log(id+" sppd_id : "+sppdid);
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/laporan/infoSpb']) ?>?id='+id+"&sppdid="+sppdid,'modal-master-info','90%'," $('#table-master').dataTable().fnClearTable(); ");
}
function infoPenawaranBhp(id,by="SPP"){
	console.log(" sppd_id : "+id);
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/penawaranTerpilih','id'=>'']) ?>'+id+'&by='+by,'modal-penawaran','80%');
}
function infoPenawaran(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/infoPenawaran','id'=>'']) ?>'+id+'&disableDelete=1&disableEdit=1';
	var modal_id = 'modal-info-penawaran';	
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function () {
			$("#"+modal_id).hide();
			$("#"+modal_id).remove();
		});
		spinbtn();
		draggableModal();
	});
}
function infoSPO(spo_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpo']) ?>?id='+spo_id+"&bhp_id="+bhp_id,'modal-info-spo','75%','');
}
</script>