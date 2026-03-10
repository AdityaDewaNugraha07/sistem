<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Laporan DRP Detail';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan DRP Detail '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Kode DRP'); ?></th>
							<th><?= Yii::t('app', 'BBK'); ?></th>
							<th><?= Yii::t('app', 'Tanggal Rencana<br>Pembayaran') ?></th>
							<th><?= Yii::t('app', 'Kategori'); ?></th>
							<th><?= Yii::t('app', 'Tipe Voucher'); ?></th>
							<th><?= Yii::t('app', 'Penerima<br>Pembayaran'); ?></th>
							<th><?= Yii::t('app', 'Jumlah Bayar') ?></th>
							<th><?= Yii::t('app', 'Bank Tujuan'); ?></th>
							<th><?= Yii::t('app', 'No. Rek'); ?></th>
							<th><?= Yii::t('app', 'Rek. a/n'); ?></th>
							<th><?= Yii::t('app', 'No. Cek'); ?></th>
							<th><?= Yii::t('app', 'Keterangan'); ?></th>
							<th><?= Yii::t('app', 'Status<br>Approval'); ?></th>
							<th><?= Yii::t('app', 'Status<br>Pembayaran'); ?></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
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
					<tfoot>
						<tr>
                            <th colspan="7" style="text-align:right">Total Per Page:</th>
                            <th colspan="1" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="7" style="text-align:right;" class="td-kecil">Total All Page:</th>
                            <th colspan="1" style="text-align:right;" class="td-kecil"></th>
                        </tr>
					</tfoot>
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
			url: '<?= \yii\helpers\Url::toRoute('/finance/laporan/drpDetail') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, class: 'td-kecil',
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 1, class: 'td-kecil',
				render: function ( data, type, full, meta ) {
					return data;
                }
			},
			{	targets: 2, class: 'td-kecil text-align-center',
				render: function ( data, type, full, meta ) {
					var bbk="<a onclick=\"infoBbk('"+full[30]+"', '"+full[1]+"')\">"+data+"</a>";
					return bbk;
                }
			},
			{	targets: 3, class: 'td-kecil text-align-center',
				render: function ( data, type, full, meta ) {
					var date = new Date(full[3]);
					date = date.toString('dd/MM/yyyy');
					return date;
                }
			},
			{ 	targets: 4, class: 'td-kecil text-align-center',
                render: function ( data, type, full, meta ) {
					return full[4];
                }
            },
			{	targets: 5, class: 'text-align-center td-kecil', width: "100px",
				render: function ( data, type, full, meta ) {
					if(full[5] == "Open Voucher"){
						ret = full[5] + '<br><b>' + full[31] +'</b>';
					} else {
						ret = full[5];
					}
					return ret;
                }
			},
			{	targets: 6, class: 'text-align-center td-kecil', width: "100px",
				render: function ( data, type, full, meta ) {
					if(full[34]){
						suplier = full[34];
					}else if(full[6]){
						suplier = full[6];
					} else if(full[9] !== null){
						suplier= "<a onclick='gkk("+full[8]+")'>"+full[9]+"</a>";
					}else if(full[11] !== null){
						suplier= "<a onclick='ppk("+ full[10] +")'>"+ full[11] +"</a>";
					}else if(full[13] !== null){
						suplier="<a onclick='ajuanDinas("+ full[12] +")'>"+ full[13] +"</a>";
					}else if(full[15] !== null){
						suplier="<a onclick='ajuanMakan("+ full[14] +")'>"+ full[15] +"</a>";
					}else if(full[17] !== null){
						suplier= "<a onclick='infoAjuanDp("+ full[16] +")'>"+ full[17] +"</a>";
					}else if(full[19] !== null){
						suplier= "<a onclick='infoPelunasan("+ full[18] +")'>"+ full[19] +"</a>";
					}else {
						suplier = full[7];
					}
					return suplier;
                }
			},
			{ 	targets: 7, class : "text-align-right td-kecil", width: "100px",
                render: function ( data, type, full, meta ) {
					number = unformatNumber(full[20]);
					return formatNumberForUser(number);
                }
            },
			{ 	targets: 8, class : "text-align-center td-kecil",
				render: function ( data, type, full, meta ) {
					if(full[5] == "Open Voucher" || full[5] == "Uang Dinas Grader" || full[5] == "Uang Makan Grader"){
						penerima = JSON.parse(full[22]);
						if(penerima){
							bank = penerima[0].nama_bank;
						} else {
							bank = '';
						}
					}else{
						bank = full[25];
					}
					return bank;
                }
            },
			{ 	targets: 9, class: "text-align-left td-kecil",
				render: function ( data, type, full, meta ) {
					if(full[5] == "Open Voucher" || full[5] == "Uang Dinas Grader" || full[5] == "Uang Makan Grader"){
						penerima = JSON.parse(full[23]);
						if(penerima){
						 	norek = penerima[0].rekening;
						} else {
							norek = '';
						}
					}else{
						norek = full[26];
					}
					return norek;
				}
            },
			{ 	targets: 10, class: "td-kecil",
				render: function ( data, type, full, meta ) {
					if(full[5] == "Open Voucher" || full[5] == "Uang Dinas Grader" || full[5] == "Uang Makan Grader"){
						penerima = JSON.parse(full[24]);
						if(penerima){
							rek_an = penerima[0].an_bank;
						} else {
							rek_an = '';
						}
					}else{
						rek_an = full[27];
					}
					return rek_an;
				}
			},
			{ 	targets: 11, class: "td-kecil", 
				render: function ( data, type, full, meta ) {
					if(full[36] == 'Cek'){
						ret = full[37];
					} else {
						ret = '';
					}
					return ret;
				}
			},
			{ 	targets: 12, class: "td-kecil",
				render: function ( data, type, full, meta ) {
					return full[29];
				}
			},
			{ 	targets: 13, class: "td-kecil text-align-center", width: "50px",
				render: function ( data, type, full, meta ) {
					status = full[21];
					if(full[21] == 'APPROVED'){
						if(full[35] == 'Ditunda'){
							status = "<span style='font-size: 1rem; color: red;'>*ajukan ulang ditanggal berikutnya</span>";
						}
					}
					return status;
				}
			},
			{ 	targets: 14, class: "td-kecil text-align-center",
				render: function ( data, type, full, meta ) {
				var statusColor = full[38] === "PAID" ? "green" : "orange";
					if(full[38] == "PAID"){
						a = 'at ';
					} else {
						a = 'plan ';
					}
					var date = new Date(full[39]);
					date = date.toString('dd/MM/yyyy');
					tanggal = '<br>' + a + date;
                    return '<span style="color:' + statusColor + '; font-weight: bold;">' + full[38] + '</span><span class="td-kecil2">' + tanggal + '</span>';
				}
			},
			{ 	targets: 15,visible:false},
			{ 	targets: 16,visible:false},
			{ 	targets: 17,visible:false},
			{ 	targets: 18,visible:false},
			{ 	targets: 19,visible:false},
			{ 	targets: 20,visible:false},
			{ 	targets: 21,visible:false},
			{ 	targets: 22,visible:false},
			{ 	targets: 23,visible:false},
			{ 	targets: 24,visible:false},
			{ 	targets: 25,visible:false},
			{ 	targets: 26,visible:false},
			{ 	targets: 27,visible:false},
			{ 	targets: 28,visible:false},
			{ 	targets: 29,visible:false},
			{ 	targets: 30,visible:false},
			{ 	targets: 31,visible:false},
        ],
		"fnDrawCallback": function( oSettings ) {
			<?php //if(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER){ ?>
				//formattingDatatableReport(oSettings.sTableId);
			<?php //} else { ?>
				formattingDatatableMasterThis(oSettings.sTableId);
			<?php //} ?>
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		footerCallback: function(row, data, start, end, display) {
			var api = this.api();
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                         i : 0;
            };

            // Total over all pages
            total = api
                .column( 20 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 20, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $('tr:eq(0) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(pageTotal.toFixed(0).toLocaleString())}`);
            $.ajax({
                url: "<?= yii\helpers\Url::toRoute('/finance/laporan/drpDetailTotal') ?>?"+$('#form-search-laporan').serialize(),
                success: res => {
                    $('tr:eq(1) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(JSON.parse(res).total.toFixed(0).toLocaleString())}`)  
                }                                                                                                                                                                                                                                                                                                                                                                                                             
            })
		},
		order:[20, 'desc'],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/finance/laporan/drpDetailPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function gkk(id){
    var url = '<?= Url::toRoute(['/kasir/pengeluarankaskecil/detailGkk']); ?>?id='+id;
    $(".modals-place-2").load(url, function() {
        $("#modal-gkk").modal('show');
        $("#modal-gkk").on('hidden.bs.modal', function () { });
        $("#modal-gkk .modal-dialog").css('width',"21cm");
        spinbtn();
        draggableModal();
    });
}

function ppk(id){
    var url = '<?= Url::toRoute(['/kasir/ppk/detailppk']); ?>?id='+id;
    $(".modals-place-2").load(url, function() {
        $("#modal-ppk").modal('show');
        $("#modal-ppk").on('hidden.bs.modal', function () { });
        $("#modal-ppk .modal-dialog").css('width',"21cm");
        spinbtn();
        draggableModal();
    });
}

function ajuanDinas(id){
    var url = '<?= Url::toRoute(['/purchasinglog/biayagrader/detailAjuanDinas']); ?>?id='+id;
    $(".modals-place-2").load(url, function() {
        $("#modal-ajuandinas").modal('show');
        $("#modal-ajuandinas").on('hidden.bs.modal', function () { });
        $("#modal-ajuandinas .modal-dialog").css('width',"21cm");
        spinbtn();
        draggableModal();
    });
}
    
function ajuanMakan(id){
    var url = '<?= Url::toRoute(['/purchasinglog/biayagrader/detailAjuanMakan']); ?>?id='+id;
    $(".modals-place-2").load(url, function() {
        $("#modal-ajuanmakan").modal('show');
        $("#modal-ajuanmakan").on('hidden.bs.modal', function () { });
        $("#modal-ajuanmakan .modal-dialog").css('width',"21cm");
        spinbtn();
        draggableModal();
    });
}

function infoBbk(id, kode){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucher/detailBbk']) ?>?id='+id+'&kode='+kode,'modal-bbk','50%');
}

function formattingDatatableMasterThis(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printoutRelease(\"PRINT\")' data-original-title='Print Release'><i class='fa fa-download'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PRINT\")' data-original-title='Print Out'><i class='fa fa-print'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PDF\")' data-original-title='Export to PDF'><i class='fa fa-files-o'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
    $(".tooltips").tooltip({ delay: 50 });
}

function printoutRelease(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/finance/laporan/drpDetailPrintRelease') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>

