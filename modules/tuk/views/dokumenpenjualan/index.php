<?php
/* @var $this yii\web\View */
$this->title = 'Dokumen Penjualan';
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
        'labelOptions'=>['class'=>'col-md-5 control-label'],
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
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Dokumen Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
								<span class="caption-subject bold"><h4>
								<?php
								if(isset($_GET['dokumen_penjualan_id'])){
									echo "Dokumen Penjualan";
								}else{
									echo "Dokumen Penjualan Baru";
								}
								?>
								</h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?php if(isset($_GET['dokumen_penjualan_id'])){ ?>
											<?= yii\bootstrap\Html::activeHiddenInput($model, "spm_ko_id"); ?>
											<?= $form->field($model, 'kode_spm')->textInput()->label("Kode Nota"); ?>
										<?php }else{ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Nota'); ?></label>
												<div class="col-md-7">
													<span class="input-group-btn" style="width: 100%">
														<?= \yii\bootstrap\Html::activeDropDownList($model, 'spm_ko_id', app\models\TNotaPenjualan::getOptionListNotaDokumen(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setNota()','style'=>'width:100%;']); ?>
													</span>
													<span class="input-group-btn">
														<a class="btn btn-icon-only btn-default tooltips" onclick="openNota();" data-original-title="Daftar Nota Realisasi" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'cust_id'); ?>
										<?= $form->field($model, 'jenis_produk')->textInput(['disabled'=>'disabled'])->label("Jenis Produk"); ?>
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Customer'); ?></label>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 100%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'cust_an_nama', ['class'=>'form-control','disabled'=>'disabled']); ?>
												</span>
												<span class="input-group-btn">
													<div class="mt-checkbox-list" style="padding: 0px;">
														<label class="mt-checkbox mt-checkbox-outline">
															<input name="TDokumenPenjualan[cust_is_pkp]" value="0" type="hidden">
															<input id="TDokumenPenjualan-cust_is_pkp" name="TDokumenPenjualan[cust_is_pkp]" value="1" type="checkbox" disabled="disabled"> 
															<span class="help-block"></span>
															<div style="padding-top: 3px; margin-left: 10px;">PKP</div>
														</label> 
													</div>
												</span>
											</div>
										</div>
										
										<?= $form->field($model, 'cust_pr_nama')->textInput(['disabled'=>'disabled'])->label("Nama Perusahaan") ?>
										<?= $form->field($model, 'cust_alamat')->textarea(['disabled'=>'disabled'])->label("Alamat Perusahaan") ?>
										<?= $form->field($model, 'kendaraan_nopol')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'kendaraan_supir')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'alamat_bongkar')->textarea(['disabled'=>'disabled']); ?>
										
									</div>
									<div class="col-md-5">
										<?= $form->field($model, 'jenis_dokumen')->textInput(['disabled'=>'disabled'])->label("Jenis Dokumen"); ?>
										<?php 
										if(!isset($_GET['dokumen_penjualan_id']) || isset($_GET['edit'])){
											echo $form->field($model, 'nomor_dokumen')->textInput(['style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Nomor Dokumen'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'nomor_dokumen', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->nomor_dokumen ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'skshhko_no')->textInput(); ?>
										<?= $form->field($model, 'tanggal',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'masaberlaku_awal',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime" style="width:150px;">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setQtyHari()']); ?>
										<?= $form->field($model, 'masaberlaku_akhir',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setQtyHari()']); ?>
										<div class="form-group">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Lama Hari Berlaku'); ?></label>
											<div class="col-md-7" style="padding-bottom: 5px;">
												<div class="input-group">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'masaberlaku_hari', ['class'=>'form-control','disabled'=>'disabled']) ?>
													<span class="input-group-addon" style="padding-left: 5px; padding-right: 5px;">Hari</span>
												</div>
											</div>
										</div>
										<?php if(!isset($_GET['dokumen_penjualan_id']) || isset($_GET['edit'])){ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Petugas TUK'); ?></label>
												<div class="col-md-7">
													<?= \yii\bootstrap\Html::activeDropDownList($model, 'petugas_legalkayu_id', [],['class'=>'form-control select2','prompt'=>'','onchange'=>'setPetugas()']); ?>
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
                                        <h5 style="font-weight: 600" id="place-detaildokumen"><?= Yii::t('app', 'Detail Produk'); ?></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th rowspan="2" style="width: 250px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Produk'); ?></th>
														<th rowspan="2" style="width: 220px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Dimensi'); ?></th>
														<th colspan="2" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty'); ?></th>
														<th rowspan="2" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Keterangan'); ?></th>
													</tr>
													<tr>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 130px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 130px; padding: 5px;"><?= Yii::t('app', 'Volume M<sup>3</sup>'); ?></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
												<tfoot>
													<tr style="background-color: #F1F4F7;">
														<td colspan="3" class="text-align-right"><b>
															Total &nbsp;
														</b></td>
														<td style="font-size: 1.3rem; line-height: 0.9; padding: 5px; text-align: right;" id="place-totalkecil">
															
														</td>
														<td style="font-size: 1.3rem; line-height: 0.9; padding: 5px; text-align: right;" id="place-totalkubikasi">
															
														</td>
														<td></td>
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
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printDokumen('.(isset($_GET['dokumen_penjualan_id'])?$_GET['dokumen_penjualan_id']:'').');','disabled'=>true]); ?>
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
if(isset($_GET['dokumen_penjualan_id'])){
    $pagemode = "afterSave(".$_GET['dokumen_penjualan_id'].");";
}else{
	$pagemode = "setQtyHari();";
}
?>
<?php $this->registerJs(" 
    $pagemode;
	formconfig();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Dokumen Penjualan KO'))."');
	$('select[name*=\"[spm_ko_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Tarik Kode Nota',
//		ajax: {
//			url: '".\yii\helpers\Url::toRoute('/tuk/dokumenpenjualan/findNota')."',
//			dataType: 'json',
//			delay: 250,
//			processResults: function (data) {
//				return {
//					results: data
//				};
//			},
//			cache: true
//		}
	});
	$('select[name*=\"[petugas_legalkayu_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Petugas',
	});
", yii\web\View::POS_READY); ?>
<script>
function setNota(){
	var spm_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spm_ko_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/tuk/dokumenpenjualan/setNota']); ?>',
        type   : 'POST',
        data   : {spm_ko_id:spm_ko_id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_pr_nama") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_alamat") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "noreg") ?>").val('');
			$("#TDokumenPenjualan-cust_is_pkp").prop("checked",false);
			$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_dokumen") ?>").val("");
			$("#<?= yii\bootstrap\Html::getInputId($model, "nomor_dokumen") ?>").val("");
			$('#table-detail tbody').html("");
			if(data.spm_ko_id){
				$("#modal-master").find('button.fa-close').trigger('click');
				$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val(data.jenis_produk);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val(data.cust.cust_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val(data.cust.cust_an_nama);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_pr_nama") ?>").val(data.cust.cust_pr_nama);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_alamat") ?>").val(data.cust_alamat);
				$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val(data.alamat_bongkar);
				$("#<?= yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>").val(data.kendaraan_nopol);
				$("#<?= yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>").val(data.kendaraan_supir);
				$("#<?= yii\bootstrap\Html::getInputId($model, "nomor_dokumen") ?>").val(data.dokumen_penjualan);
				if(data.cust.cust_is_pkp){
					$("#TDokumenPenjualan-cust_is_pkp").prop("checked",true);
				}else{
					$("#TDokumenPenjualan-cust_is_pkp").prop("checked",false);
				}
				if(data.jenis_produk == "Plywood" || data.jenis_produk == "Moulding" || data.jenis_produk == "Lamineboard" || data.jenis_produk == "Platform" ||  data.jenis_produk == "JasaMoulding" ||  data.jenis_produk == "FingerJointLamineBoard" ||  data.jenis_produk == "FingerJointStick" ||  data.jenis_produk == "Flooring"){
					$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_dokumen") ?>").val("Nota Perusahaan");
				}else if(data.jenis_produk == "Sawntimber" || data.jenis_produk == "Veneer" || data.jenis_produk == "JasaKD" || data.jenis_produk == "JasaGesek"){
					$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_dokumen") ?>").val("DKO");
				}else if(data.jenis_produk == "Log"){
					$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_dokumen") ?>").val("DKB");
				}
				getItems(data.nota_penjualan_id);
			}
			setDropdownPetugas();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function openNota(){
	var url = '<?= \yii\helpers\Url::toRoute(['/tuk/dokumenpenjualan/OpenSPMTernota']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pick(spm_ko_id,kode){
	$("#modal-master").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "spm_ko_id") ?>").empty().append('<option value="'+spm_ko_id+'">'+kode+'</option>').val(spm_ko_id).trigger('change');
}

function getItems(nota_penjualan_id){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/tuk/dokumenpenjualan/getItems']); ?>',
		type   : 'POST',
		data   : {nota_penjualan_id:nota_penjualan_id},
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

function total(){
	$("#place-totalkecil").html("");
	$("#place-totalkubikasi").html("");
	var total_kecil = 0;
	var total_kubikasi = 0;
	var satuan_kecil = "";
	$("#table-detail tbody tr").each(function(){
		total_kecil += unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
		total_kubikasi += unformatNumber( $(this).find('input[name*="[kubikasi]"]').val() );
		satuan_kecil = $(this).find('input[name*="[satuan_kecil]"]').val();
	});
	$("#place-totalkecil").html(formatNumberForUser(total_kecil)+" <i>("+satuan_kecil+")</i>");
	$("#place-totalkubikasi").html( formatNumberForUser(total_kubikasi) );
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

function setDropdownPetugas(callback=null){
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'petugas_legalkayu_id') ?>').addClass('animation-loading');
    var jenis_produk = $('#<?= \yii\bootstrap\Html::getInputId($model, 'jenis_produk') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/tuk/dokumenpenjualan/setDropdownPetugas']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'petugas_legalkayu_id') ?>").html("");
			if(data){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'petugas_legalkayu_id') ?>").html(data.html);
			}
            $('#<?= \yii\bootstrap\Html::getInputId($model, 'petugas_legalkayu_id') ?>').removeClass('animation-loading');
			(callback)?callback():"";
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
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
	if("<?= $model->cust_is_pkp ?>" == "1"){
		$("#TDokumenPenjualan-cust_is_pkp").prop("checked",true);
	}else{
		$("#TDokumenPenjualan-cust_is_pkp").prop("checked",false);
	}
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