<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Pembelian Bahan Baku';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Rekap Pembelian Bahan Baku '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'BBK') ?></th>
							<th><?= Yii::t('app', 'Tanggal') ?></th>
							<th><?= Yii::t('app', 'No. Cek/BG'); ?></th>
							<th><?= Yii::t('app', 'Penerima') ?></th>
							<th><?= Yii::t('app', 'Bank<br>Penerima') ?></th>
							<th><?= Yii::t('app', 'No. Rek<br>Penerima') ?></th>
							<th><?= Yii::t('app', 'Kode<br>Perkiraan') ?></th>
							<th><?= Yii::t('app', 'Keterangan') ?></th>
							<th><?= Yii::t('app', 'Nominal') ?></th>
							<th><?= Yii::t('app', 'Bank') ?></th>
							<th><?= Yii::t('app', 'Status<br>Pembayaran') ?></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
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
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/finance/laporan/pbb') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, class:"td-kecil",
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 1, class : "td-kecil"},
			{ 	targets: 2, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 3, class:"td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var ret = "";
					if(data=="Cek" || data=="Bilyet Giro"){
						ret = full[12];
					}else{
						ret = data;
					}
					return ret;
                }
            },
			{ 	targets: 4, class:"td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var ret = "";
					if(data){
						ret = data;
					}else{
						if(full[11]=="Top-up Kas Kecil"){ // tipe
							ret = "Kas Kecil CWM";
						}
						if(full[11]=="Uang Dinas Grader"){ // tipe
							ret = full[13];
						}
						if(full[11]=="Uang Makan Grader"){ // tipe
							ret = full[14];
						}
						if(full[11]=="Ganti Kas Kecil"){ // tipe
							ret = full[15];
						}
						if(full[11]=="Open Voucher"){ // tipe
                            if(full[17]=="DP LOG SENGON"||full[17]=="PELUNASAN LOG SENGON"){
                                ret='<b>'+full[17]+'</b><br>'+full[18];
                            }else{
                                ret='<b>'+full[17]+'</b><br>'+full[19];
                            }
							
						}
                    }
					return ret;
                }
            },
			{ 	targets: 5, class:"td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					if(full[11] == "Open Voucher" || full[11] == "Uang Dinas Grader" || full[11] == "Uang Makan Grader"){
						penerima = JSON.parse(full[20]);
						if(penerima){
							bank = penerima[0].nama_bank;
						} else {
							bank = '';
						}
					}else{
						bank = full[5];
					}
					return bank;
                }
            },
			{ 	targets: 6, class:"td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					if(full[11] == "Open Voucher" || full[11] == "Uang Dinas Grader" || full[11] == "Uang Makan Grader"){
						penerima = JSON.parse(full[20]);
						if(penerima){
						 	norek = penerima[0].rekening;
						} else {
							norek = '';
						}
					}else{
						norek = full[6];
					}
					return norek;
                }
            },
			{ 	targets: 7, class:"td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var ret = "";
					return ret;
                }
            },
			{ 	targets: 8, class : "td-kecil"},
			{ 	targets: 9, class : "td-kecil text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{ 	targets: 10, class : "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var ret = data.substr(-3, 3);
					return ret;
                }
            },
			{ 	targets: 11, class : "td-kecil text-align-center",
				render: function ( data, type, full, meta ) {
					if(full[11] == "Pembelian BHP" || full[11] == "Pembayaran DP BHP"){
						status_byr = full[27];
						tgl_byr = full[28];
					} else {
						status_byr = full[21];
						tgl_byr = full[22];
					}
					if(status_byr == "PAID"){
						a = 'at ';
					} else {
						a = 'plan ';
					}
					var date = new Date(tgl_byr);
					date = date.toString('dd/MM/yyyy');
					tanggal = '<br>' + a + date;
                    return status_byr + '<span class="td-kecil2">' + tanggal + '</span>';
                }
			},
			{ 	targets: 12,visible:false},
			{ 	targets: 13,visible:false},
			{ 	targets: 14,visible:false},
			{ 	targets: 15,visible:false},
			{ 	targets: 16,visible:false},
			{ 	targets: 17,visible:false},
			{ 	targets: 18,visible:false},
			{ 	targets: 19,visible:false}
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
	window.open("<?= yii\helpers\Url::toRoute('/finance/laporan/pbbPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>