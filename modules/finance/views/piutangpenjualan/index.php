<?php
/* @var $this yii\web\View */
$this->title = 'Piutang Penjualan';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Piutang Penjualan'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
					<li class="active">
                        <a href="<?= \yii\helpers\Url::toRoute("/finance/piutangpenjualan/index") ?>"> <?= Yii::t('app', 'Piutang Customer'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= \yii\helpers\Url::toRoute("/finance/piutangcustomer/index") ?>"> <?= Yii::t('app', 'Laporan Piutang'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= \yii\helpers\Url::toRoute("/finance/kartupiutang/index") ?>"> <?= Yii::t('app', 'Kartu Piutang'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Piutang Customer'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6" id="place-dropdown">
										
                                    </div>
                                    <div class="col-md-6">
										<input type="checkbox" id="is_export" onchange="setCustomer();"> &nbsp; Export
                                    </div>
                                </div><br>
								<div class="row">
                                    <div class="col-md-7" style="padding-left: 0px; padding-right: 5px;">
										<div class="portlet light custom-bordered" style="padding-left: 3px; padding-right: 3px;">
											<div class="portlet-title">
												<center><h4><?= Yii::t('app', 'Nota / Invoice'); ?></h4></center>
											</div>
											<div class="portlet-body" id="showNota" style="padding-top: 0px;">
												<center><i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i></center>
											</div>
										</div>
									</div>
                                    <div class="col-md-5" style="padding-left: 5px; padding-right: 0px;">
										<div class="portlet light custom-bordered" style="padding-left: 3px; padding-right: 3px;">
											<div class="portlet-title">
												<center><h4><?= Yii::t('app', 'Data Pembayaran'); ?></h4></center>
											</div>
											<div class="portlet-body" id="showPayment" style="padding-top: 0px;">
												<center><i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i></center>
											</div>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
	formconfig();
	setCustomer();
", yii\web\View::POS_READY); ?>
<script>
function setCustomer(){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/setCustomerPiutang']); ?>',
		type   : 'POST',
		data   : {isexport:$("#is_export").is(":checked")},
		success: function (data) {
			if(data.html){
				$("#place-dropdown").html(data.html);
				$('select[name*=\"[cust_id]\"]').select2({
					allowClear: !0,
					placeholder: data.placeholder,
				});
				getAll();
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getAll(){
	$('#showNota').html('<i style="font-size: 1.2rem;">Data Tidak Ditemukan</i>');
	$('#showPayment').html('<i style="font-size: 1.2rem;">Data Tidak Ditemukan</i>');
	var cust_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'cust_id') ?>').val();
	var is_export = $("#is_export").is(":checked");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/getAll']); ?>',
		type   : 'POST',
		data   : {cust_id:cust_id,is_export:is_export},
		success: function (data) {
			if(data.htmlnota){
				$('#showNota').html(data.htmlnota);
			}
			if(data.htmlbayar){
				$('#showPayment').html(data.htmlbayar);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function openCustomerPiutang(type){
	if(type == 'export'){
		var url = "<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/openCustomerPiutangExport2']); ?>";
	}else if(type == 'lokal'){
		var url = "<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/openCustomerPiutang2']); ?>";
	}
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function pick(cust_id,cust_an_nama){
	$("#modal-master").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").empty().append('<option value="'+cust_id+'">'+cust_an_nama+'</option>').val(cust_id).trigger('change');
}

function selectRow(ele,bill_reff,is_export){
	$("#table-nota tbody tr").each(function(){
		$(this).removeClass("selectedtr");
	});
    if(is_export){
        if(!bill_reff){
            $(ele).find("input[name*='[nomor]']").parents('tr').addClass("selectedtr");
        }else{
            $("#table-nota tbody tr input[name*='[nomor]'][value='"+bill_reff+"']").parents('tr').addClass("selectedtr");
        }
    }else{
        if(!bill_reff){
            $(ele).find("input[name*='[kode]']").parents('tr').addClass("selectedtr");
        }else{
            $("#table-nota tbody tr input[name*='[kode]'][value='"+bill_reff+"']").parents('tr').addClass("selectedtr");
        }
    }
}

function setPembayaran(ele,bill_reff=null){
	if(!bill_reff){
		bill_reff = $(ele).find("input[name*='[kode]']").val();
	}
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/setPembayaran']); ?>',
		type   : 'POST',
		data   : {bill_reff:bill_reff},
		success: function (data) {
			if(data.html){
				$('#showPayment').html(data.html);
			}
			setTimeout(function(){
				totalTerbayar();
			},500);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setHighlight(ele,is_export){
    if(is_export){
        var bill_reff = $(ele).find("input[name*='[nomor]']").val();
    }else{
        var bill_reff = $(ele).find("input[name*='[kode]']").val();
    }
	$("#table-bayar").find(".selectedtr").removeClass("selectedtr");
	$("#table-bayar > tbody > tr").each(function(){
		if( $(this).find('td:nth-child(2)').text() == bill_reff ){
			$(this).addClass("selectedtr");
		}
	});
}

function totalTerbayar(){
	var total = 0;
	$('#table-bayar > tbody > tr').each(function(){
		total += unformatNumber( $(this).find('input[name*="total_bayar"]').val() );
	});
	$('#place-jumlahterbayar').html( formatNumberForUser(total) );
}

function newBayar(cust_id){
	openModal('<?= \yii\helpers\Url::toRoute('/finance/piutangpenjualan/newBayar') ?>?cust_id='+cust_id,'modal-transaksi',null,
		'getAll();');
}

function infoNota(kode){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/infoNota']) ?>?kode='+kode,'modal-info-nota','21.5cm');
}

function infoVoucher(par){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/detailBbm']) ?>?id='+par,'modal-bbm','21.5cm');
}
function infoKasbesar(par){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/saldokasbesar/getLaporanByTanggal']) ?>?tgl='+par,'modal-rekap');
}
function infoGirocek(par){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/terimanontunai/detailnontunai']) ?>?tgl='+par,'modal-bkk','21.5cm');
}
function infoCatatan(piutang_penjualan_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/showCatatan']) ?>?piutang_penjualan_id='+piutang_penjualan_id,'modal-catatan');
}
function infoCatatan2(piutang_penjualan_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/showCatatan2']) ?>?piutang_penjualan_id='+piutang_penjualan_id,'modal-catatan-potongan');
}
function printoutPotongan(id) {
        var caraPrint = "PRINT";
        window.open("<?= yii\helpers\Url::toRoute(['/sysadmin/datacorection/printDK', 'id' => '']) ?>" + id + "&caraprint=" + caraPrint, "", 'location=_new, width=1200px, scrollbars=yes');
    }
function infoReturPenjualan(kode){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/returpenjualan/infoRetur']) ?>?kode='+kode,'modal-info-retur');
}
function infoInvoice(id){
    openModal("<?= \yii\helpers\Url::toRoute(['/exim/invoice/print']) ?>?id="+id+"&caraprint=MODAL",'modal-print','21.5cm');
}
</script>