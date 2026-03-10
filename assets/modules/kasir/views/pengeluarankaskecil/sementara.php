<?php
/* @var $this yii\web\View */
$this->title = 'Kas Kecil';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this)
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-pengeluaran-kas',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-3 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.table td, .table th {
    font-size: 13px;
}
</style>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/sementara"); ?>"> <?= Yii::t('app', 'Bon Kas Kecil'); ?> </a>
                    </li>
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/index"); ?>"> <?= Yii::t('app', 'Pengeluaran Kas Kecil'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/saldokaskecil/index"); ?>"> <?= Yii::t('app', 'Laporan Kas Kecil'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/rekapkaskecil/index"); ?>"> <?= Yii::t('app', 'Rekap Kas Kecil'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/terimaretur"); ?>"> <?= Yii::t('app', 'Terima Uang Retur'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Rekap Pengeluaran Kas Kecil Sementara (Bon Sementara)'); ?></h4></span>
                                </div>
                                <div class="tools">
									<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px; height: 28px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Bon Baru'); ?></a>
									<a class="btn btn-sm btn-outline blue-dark" id="btn-closing" onclick="historyBon();" style="margin-top: 10px; height: 28px;"><i class="icon-speedometer"></i> <?= Yii::t('app', 'Bon Terealisasi'); ?></a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-laporan">
												<thead>
													<tr>
														<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
														<th style="width: 110px; text-align: center;"><?= Yii::t('app', 'Kode'); ?></th>
														<th style="width: 150px; text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
														<th style="width: 150px; text-align: center;"><?= Yii::t('app', 'Penerima'); ?></th>
														<th><?= Yii::t('app', 'Deskripsi'); ?></th>
														<th style="width: 100px; "><?= Yii::t('app', 'Kredit'); ?></th>
														<th style="width: 70px; text-align: center;"><?= Yii::t('app', ''); ?></th>
														<th style="width: 70px; text-align: center;"><?= Yii::t('app', ''); ?></th>
														<th></th>
														<th></th>
														<th></th>
														<th></th>
														<th></th>
														<th></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
												<tfoot>
													<tr>
														<th colspan="5" style="text-align:right; height: 30px; vertical-align: bottom; font-size: 1.4rem">Total Bon Sementara &nbsp; </th>
														<th style="vertical-align: bottom; font-size: 1.4rem"></th>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['kas_kecil_id'])){
    $pagemode = "";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	dtTable();
	$(\"#".yii\bootstrap\Html::getInputId($model, 'tanggal')."\").datepicker({
        rtl: App.isRTL(),
        orientation: \"left\",
        autoclose: !0,
        format: \"dd/mm/yyyy\",
        clearBtn:false,
        todayHighlight:true
    });
    $pagemode;
	checkKasbonKasbesar();
", yii\web\View::POS_READY); ?>
<script>
function dtTable(){
    var dt_table =  $('#table-laporan').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/kasir/pengeluarankaskecil/sementara') ?>',data:{dt: 'table-laporan'} },
        order: [
            [0, 'desc']
        ],
		"pageLength": 700,
        columnDefs: [
            { 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets:1, class:'text-align-center', },
			{ 	targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 5, 
				class:'text-align-right',
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{ 	targets: 6, 
				class:'text-align-center',
                render: function ( data, type, full, meta ) {
					var ret = "";
					if(full[0]){
						if(full[8]){
							ret += '<a onclick="detailGkk('+full[8]+')" >'+full[9]+'</a>';
							if(full[10]){
								ret += '<br>';
								if(full[13]){
									ret += '<strike>';
								}
								ret += '<a onclick="detailBbk('+full[10]+')" >'+full[11]+'</a>';
								if(full[12] == "PAID"){
									ret += '<br>';
									ret += '<a class="btn btn-sm green-seagreen btn-outline" style="font-size:1rem; padding: 3px;" onclick="terimauangganti('+full[0]+')"><i class="fa fa-download"></i> Terima Uang</a>';
								}
							}
						}else{
							ret += '<a class="btn btn-sm blue btn-outline" target="BLANK" style="font-size:1rem; padding: 3px;" onclick="createGkk('+full[0]+')"><i class="fa fa-share"></i> Buat GKK </a>'
						}
						return ret;
					}
                }
            },
			{ 	targets: 7, 
				class:'text-align-center',
                render: function ( data, type, full, meta ) {
					var ret = "";
					if(!full[8] || full[13]){
						ret += '<a class="btn btn-xs dark btn-outline" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="edit('+full[0]+');"><i class="fa fa-edit"></i></a>&nbsp;';
						ret += '<a class="btn btn-xs red" id="close-btn-this" onclick="deleteItem('+full[0]+');"><i class="fa fa-trash-o"></i></a>';
					}
					return ret;
                }
            },
			{	targets: 8, visible: false }, // gkk_id
			{	targets: 9, visible: false }, // gkk_kode
			{	targets: 10, visible: false }, // t_gkk.voucher_pengeluaran_id
			{	targets: 11, visible: false }, // t_voucher_pengeluaran.voucher_kode
			{	targets: 12, visible: false }, // t_voucher_pengeluaran.status_bayar
			{	targets: 13, visible: false }, // t_voucher_pengeluaran.cancel_transaksi_id
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            // Total over all pages
            var total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
             // Update footer
            $( api.column( 5 ).footer() ).html(
                formatNumberForUser(total)
            );
        }
    });
}

function addItem(){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/createKasbon'])?>','modal-transaksi');
}
function edit(kas_bon_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/editKasbon','kas_bon_id'=>''])?>'+kas_bon_id,'modal-transaksi');
}

function deleteItem(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/deleteItemSementara','id'=>''])?>'+id,'modal-delete-record')
}

function setTotal(){
	var total = 0;
	$('#table-detail > tbody > tr').each(function (){
		total += unformatNumber( $(this).find('input[name*="[nominal]"]').val() );
	});
	$('input[name="total"]').val( formatNumberForUser( total ) );
}

function afterSave(){
	$('input[name*="total"]').attr('disabled','disabled');
	$('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
}

function historyBon(){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/historyPengeluaranSementara']) ?>','modal-history','85%');
}

function detailBkk(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/bkk/detailBkk']) ?>?id='+id,'modal-bkk','21cm');
}
function detailBbk(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucher/detailBbk']) ?>?id='+id,'modal-bbk','21cm');
}
function detailGkk(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/detailGkk']) ?>?id='+id,'modal-gkk','21cm');
}
function terimauangganti(id){ 
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/terimauangganti','id'=>''])?>'+id,'modal-global-confirm')
}
function printBKK(id){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/bkk/printout') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
function printGKK(id){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/pengeluarankaskecil/detailGkk') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function checkKasbonKasbesar(){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/checkKasbonKasbesar']); ?>';
	$(".modals-place-confirm").load(url, function() {
		$("#modal-global-confirm").modal('show');
		$("#modal-global-confirm").on('hidden.bs.modal', function () {
			location.reload();
		});
		spinbtn();
		draggableModal();
	});
}

function createGkk(kas_bon_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/createGkk','kas_bon_id'=>'']); ?>'+kas_bon_id;
	$(".modals-place").load(url, function() {
		$("#modal-transaksi").modal('show');
		$("#modal-transaksi").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
</script>