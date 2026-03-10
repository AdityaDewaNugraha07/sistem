<?php
/* @var $this yii\web\View */
$this->title = 'Log Keluar Pelabuhan';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<span class="pull-right">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Log Keluar'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4> <?php echo "Data Keluar Pelabuhan"; ?> </h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= yii\bootstrap\Html::activeHiddenInput($model, "keluar_pelabuhan_id"); ?>
										<?= $form->field($model, 'kode')->textInput(['disabled'=>true,'style'=>'font-weight:600'])->label("Kode Keluar"); ?>
										<?= $form->field($model, 'tanggal',[
												'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
												<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
												{error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Keluar"); ?>
										<?= $form->field($model, 'cara_keluar')->inline(true)->radioList(['INDUSTRI'=>'INDUSTRI','TRADING'=>'TRADING'],['style'=>'margin-left:20px','onchange'=>'setCaraKeluar();']); ?>
										<?php if(!isset($_GET['keluar_pelabuhan_id'])){ ?>
										<div class="form-group" style="margin-bottom: 5px; display: none;" id="place-customer">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Customer'); ?></label>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 95%">
													<?= \yii\bootstrap\Html::activeDropDownList($model, 'cust_id', \app\models\MCustomer::getOptionList(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setCustomer()','style'=>'width:100%;']); ?>
												</span>
												<span class="input-group-btn" style="width: 5%">
													<a class="btn btn-icon-only btn-default tooltips" onclick="masterCustomer();" data-original-title="Pick Master Customer" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
												</span>
											</div>
										</div>
										<?php }else{ ?>
											<?= \yii\bootstrap\Html::activeHiddenInput($model, "cust_id") ?>
											<?= $form->field($model, 'customer')->textInput(); ?>
										<?php } ?>
										<?= $form->field($model, 'alamat_bongkar')->textarea()->label("Tujuan Bongkar"); ?>
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'No. Nota Angkutan'); ?></label>
											<div class="col-md-7">
												<span class="input-group-addon" style="width: 20%">
													NA.CWM.
												</span>
												<span class="input-group-btn" style="width: 80%">
													<?= $form->field($model, 'nomor_nota_angkut',['template'=>'{input}'])->textInput(['style'=>'font-weight:600; margin-left: 10px; width: 100%','placeholder'=>'ex. 19AI.000134'])->label("No. DKB"); ?>
												</span>
											</div>
										</div>
										<?= $form->field($model, 'nomor_dkb')->textInput(['style'=>'font-weight:600','placeholder'=>'ex. 7/DKB/CWM/19AI/III/2019'])->label("No. DKB"); ?>
										<?= $form->field($model, 'nomor_surat_pengantar')->textInput(['style'=>'font-weight:600','placeholder'=>'ex. KB000046'])->label("No. Surat Pengantar"); ?>
										<?= $form->field($model, 'nomor_skshhkb')->textInput(['style'=>'font-weight:600','placeholder'=>'ex. KB.B.4704708'])->label("No. SKSHHKB"); ?>
									</div>
									<div class="col-md-5">
										<?= $form->field($model, 'jenis_kendaraan')->dropDownList(["TRAILER 40 FEET"=>"TRAILER 40 FEET","TRAILER 20 FEET"=>"TRAILER 20 FEET","LAINNYA"=>"LAINNYA"])->label("Jenis Kendaraan Pengangkut") ?>
										<?= $form->field($model, 'kendaraan_nopol')->textInput(['placeholder'=>'ex. H 5969 ABG'])->label("Nopol Kendaraan"); ?>
										<?= $form->field($model, 'kendaraan_supir')->textInput()->label("Nama Supir"); ?>
										<div class="form-group">
											<label class="col-md-4 control-label">Masa Berlaku Nota Angkut</label>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 50%">
													<?= $form->field($model, 'masaberlaku_awal',['template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
																							 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																							 {error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setQtyHari()']); ?>
												</span>
												<span class="input-group-addon textarea-addon" style="width: 10%; background-color: #fff; border: 0;"> sd </span>
												<span class="input-group-btn" style="width: 50%">
													<?= $form->field($model, 'masaberlaku_akhir',['template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
																							 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																							 {error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setQtyHari()']); ?>
												</span>
											<span class="help-block"></span>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Lama Hari Berlaku'); ?></label>
											<div class="col-md-7" style="padding-bottom: 5px;">
												<div class="input-group">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'masaberlaku_hari', ['class'=>'form-control','disabled'=>'disabled']) ?>
													<span class="input-group-addon" style="padding-left: 5px; padding-right: 5px;">Hari</span>
												</div>
											</div>
										</div>
										<?php if(!isset($_GET['keluar_pelabuhan_id']) || isset($_GET['edit'])){ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Petugas TUK'); ?></label>
												<div class="col-md-7">
													<?= \yii\bootstrap\Html::activeDropDownList($model, 'petugas_legalkayu_id', app\models\MPetugasLegalkayu::getOptionList("Kayu Bulat"),['class'=>'form-control select2','prompt'=>'','onchange'=>'setPetugas()']); ?>
												</div>
											</div>
										<?php }else{ ?>
											<?= $form->field($model, 'petugas_legalkayu')->textInput()->label("Petugas TUK"); ?>
										<?php } ?>
										<?= $form->field($model, 'noreg')->textInput(['disabled'=>'disabled'])->label('No. Reg Petugas'); ?>
									</div>
								</div><br><hr>
                                <div class="row">
                                    <div class="col-md-12">
										<h4><?= Yii::t('app', 'Detail Dokumen Kayu'); ?></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; font-size: 1.3rem; padding: 5px;">No.</th>
														<th style="width: 250px; font-size: 1.3rem; padding: 5px;"><?= Yii::t('app', 'No. Barcode'); ?></th>
														<th style="width: 300px; font-size: 1.3rem; padding: 5px;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
														<th style="width: 80px; font-size: 1.3rem; padding: 5px;"><?= Yii::t('app', 'Panjang'); ?></th>
														<th style="width: 80px; font-size: 1.3rem; padding: 5px;"><?= Yii::t('app', 'Diameter'); ?></th>
														<th style="width: 80px; font-size: 1.3rem; padding: 5px;"><?= Yii::t('app', 'Volume'); ?></th>
														<th style="width: 80px; font-size: 1.3rem; padding: 5px;"><?= Yii::t('app', 'Reduksi'); ?></th>
														<th style="font-size: 1.3rem; padding: 5px;"><?= Yii::t('app', 'Keterangan'); ?></th>
														<th style="width: 40px;"><?= Yii::t('app', ''); ?></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
												<tfoot>
													<tr style="background-color: #F1F4F7;">
														<td colspan="2" class="text-align-right"><b>
															Total &nbsp;
														</b></td>
														<td style="font-size: 1.3rem; padding: 5px; text-align: right;" id="place-totalkecil">
															
														</td>
														<td></td>
														<td></td>
														<td style="font-size: 1.3rem; padding: 5px; text-align: right;" id="place-totalkubikasi">
															
														</td>
														<td></td>
														<td></td>
													</tr>
													<tr>
														<td colspan="8">
															<?php if(isset($_GET['keluar_pelabuhan_id'])){ ?>
																<a class="btn btn-xs grey btn-outline" id="btn-add-item" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Item'); ?></a>
															<?php }else{ ?>
																<a class="btn btn-xs blue-hoki btn-outline" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Item'); ?></a>
															<?php } ?>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printDokumen('.(isset($_GET['keluar_pelabuhan_id'])?$_GET['keluar_pelabuhan_id']:'').');','disabled'=>true]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
$pagemode = "";
if(isset($_GET['keluar_pelabuhan_id'])){
    $pagemode = "afterSave(".$_GET['keluar_pelabuhan_id'].");";
}else{
	$pagemode = "setQtyHari(); setCaraKeluar();";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	$('select[name*=\"[cust_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Cari Customer',
		ajax: {
			url: '".\yii\helpers\Url::toRoute('/marketing/customer/findCustomer')."',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		}
	});
	$('select[name*=\"[petugas_legalkayu_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Petugas',
	});
", yii\web\View::POS_READY); ?>
<script>
function setCaraKeluar(){
	var cara_keluar = $("input[name*='cara_keluar']:checked").val();
	if(cara_keluar == "TRADING"){
		$("#place-customer").css("display","");
		$("#<?= \yii\helpers\Html::getInputId($model, "nomor_surat_pengantar") ?>").closest(".form-group").css("display","");
		$("#<?= \yii\helpers\Html::getInputId($model, "nomor_skshhkb") ?>").closest(".form-group").css("display","");
		$("#<?= \yii\helpers\Html::getInputId($model, "alamat_bongkar") ?>").val("");
	}else{
		$("#place-customer").css("display","none");
		$("#<?= \yii\helpers\Html::getInputId($model, "nomor_surat_pengantar") ?>").closest(".form-group").css("display","none");
		$("#<?= \yii\helpers\Html::getInputId($model, "nomor_skshhkb") ?>").closest(".form-group").css("display","none");
		$("#<?= \yii\helpers\Html::getInputId($model, "alamat_bongkar") ?>").val("Jl. Raya Semarang - Purwodadi \r\nKm. 16.5 No. 349 Kembangarum \r\nMranggen, Demak 59567 Jawa Tengah");
	}
}
function masterCustomer(){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/customer/masterOnModal']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','100%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pick(no_barcode){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/tuk/logkeluarpelabuhan/addItem']); ?>',
        type   : 'POST',
        data   : {no_barcode:no_barcode},
        success: function (data){
            if(data.html){
				var allow = true;
				$('#table-detail > tbody > tr').each(function(){
					var barcode = $(this).find('input[name*="[no_barcode]"]').val();
					if(barcode == data.no_barcode){
						allow = false;
					}
				});
				if(allow){
					$("#modal-stock-log").find('button.fa-close').trigger('click');
					$(data.html).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
						reordertable('#table-detail');
					});
				}else{
					cisAlert(data.no_barcode+" is already picked, please pick other."); return false;
				}
            }
            total();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setCustomer(){
	var cust_id = $('#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/orderpenjualan/setCustomer']); ?>',
        type   : 'POST',
        data   : {cust_id:cust_id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val('');
			if(data.cust_id){
				$("#modal-master").find('button.fa-close').trigger('click');
				$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val(data.cust_an_alamat);
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'sistem_bayar') ?>').val('Bayar Lunas');
}

function setQtyHari(){
	var start = $('#<?= \yii\bootstrap\Html::getInputId($model, 'masaberlaku_awal') ?>').val();
	var end = $('#<?= \yii\bootstrap\Html::getInputId($model, 'masaberlaku_akhir') ?>').val();
	var qty_hari = 0;
	if(start && end){
		qty_hari = dateDaysPeriode(start,end) + 1;
	}
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'masaberlaku_hari') ?>').val(qty_hari);
}

function setPetugas(){
	var petugas_legalkayu_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'petugas_legalkayu_id') ?>").val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/tuk/dokumenpenjualan/setPetugas']); ?>',
		type   : 'POST',
		data   : {petugas_legalkayu_id:petugas_legalkayu_id},
		success: function (data) {
			if(data){
				var noreg = data.noreg;
			}else{
				var noreg = "";
			}
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'noreg') ?>").val(noreg);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function addItem(){
	openModal('<?= \yii\helpers\Url::toRoute(['/tuk/logkeluarpelabuhan/stockLog'])?>','modal-stock-log','90%');
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
            return false;
        }
		submitform($form);
    }
    return false;
}

function total(){
	$("#place-totalkubikasi").html("");
	var total_kubikasi = 0;
	$("#table-detail tbody tr").each(function(){
		total_kubikasi += unformatNumber( $(this).find('input[name*="[volume]"]').val() );
	});
	$("#place-totalkubikasi").html( formatNumberForUser(total_kubikasi) );
}

function afterSave(id){
	getItemsById(id);
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'masaberlaku_awal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'masaberlaku_akhir') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    $('#btn-print2').removeAttr('disabled');
	$('#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>');
	<?php if(isset($_GET['edit'])){ ?>
		$('#<?= \yii\bootstrap\Html::getInputId($model, "nomor_dokumen") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "masaberlaku_awal") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'masaberlaku_awal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "masaberlaku_akhir") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'masaberlaku_akhir') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "petugas_legalkayu_id") ?>').prop("disabled", false);
		setDropdownPetugas(function(){
			$('#<?= \yii\bootstrap\Html::getInputId($model, "petugas_legalkayu_id") ?>').val("<?= $model->petugas_legalkayu_id ?>");
		});
		$('#<?= \yii\bootstrap\Html::getInputId($model, "skshhko_no") ?>').prop("disabled", false);
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
		$('#btn-print2').prop('disabled',true);
	<?php } ?>
}

function getItemsById(id){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/tuk/dokumenpenjualan/getItemsById']); ?>',
		type   : 'POST',
		data   : {id:id},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				formconfig();
			}
			setTimeout(function(){
				total();
			},500)
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/tuk/dokumenpenjualan/daftarAfterSave']) ?>','modal-aftersave','95%');
}

function printDokumen(id){
	window.open("<?= yii\helpers\Url::toRoute('/tuk/dokumenpenjualan/printDokumen') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
</script>