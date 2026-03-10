<?php
/* @var $this yii\web\View */
$this->title = 'Nota Penjualan';
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
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Nota Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
								<span class="caption-subject bold"><h4>
								<?php
								if(isset($_GET['nota_penjualan_id'])){
									echo "Data Nota Penjualan";
								}else{
									echo "Nota Penjualan Baru";
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
										<?php if(isset($_GET['nota_penjualan_id'])){ ?>
											<?= yii\bootstrap\Html::activeHiddenInput($model, "spm_ko_id"); ?>
											<?= $form->field($model, 'kode_spm')->textInput()->label("Kode SPM"); ?>
										<?php }else{ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode SPM'); ?></label>
												<div class="col-md-7">
													<span class="input-group-btn" style="width: 100%">
														<?= \yii\bootstrap\Html::activeDropDownList($model, 'spm_ko_id', \app\models\TSpmKo::getOptionListNotaBaru(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setSPM()','style'=>'width:100%;']); ?>
													</span>
													<span class="input-group-btn">
														<a class="btn btn-icon-only btn-default tooltips" onclick="openSPM();" data-original-title="Daftar SPM Realisasi" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'op_ko_id'); ?>
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
															<input name="TNotaPenjualan[cust_is_pkp]" value="0" type="hidden">
															<input id="tnotapenjualan-cust_is_pkp" name="TNotaPenjualan[cust_is_pkp]" value="1" type="checkbox" onchange="subTotal();" disabled="disabled"> 
															<span class="help-block"></span>
															<div style="padding-top: 3px; margin-left: 10px;">PKP</div>
														</label> 
													</div>
												</span>
											</div>
										</div>
										
										<?= $form->field($model, 'cust_pr_nama')->textInput(['disabled'=>'disabled'])->label("Nama Perusahaan") ?>
										<?php echo $form->field($model, 'syarat_jual')->textInput(['disabled'=>'disabled']) ?>
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Sistem Bayar'); ?></label>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 68%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'sistem_bayar', ['class'=>'form-control','disabled'=>'disabled']); ?>
												</span>
												<span class="input-group-btn" style="width: 2%">&nbsp;</span>
												<span class="input-group-btn place-sistembayar" style="width: 30%; display: <?= ($model->sistem_bayar=="Tempo"?"":"none"); ?> ">
													<?= \yii\bootstrap\Html::activeTextInput($modTempo, 'top_hari', ['class'=>'form-control','disabled'=>'disabled']); ?>
												</span>
												<span class="input-group-addon place-sistembayar" style="padding-left: 5px; padding-right: 5px; display: <?= ($model->sistem_bayar=="Tempo"?"":"none"); ?>">Hari </span>
											</div>
										</div>
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Cara Bayar'); ?></label>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 48%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'cara_bayar', ['class'=>'form-control','disabled'=>'disabled']); ?>
												</span>
												<span class="input-group-btn" style="width: 2%">&nbsp;</span>
												<span class="input-group-btn" style="width: 50%">
													<?= \yii\bootstrap\Html::activeTextInput($model, 'cara_bayar_reff', ['class'=>'form-control','disabled'=>'disabled']); ?>
												</span>
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<?php 
										if(!isset($_GET['nota_penjualan_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Nota'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'tanggal',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'kendaraan_nopol')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'kendaraan_supir')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'alamat_bongkar')->textarea(['disabled'=>'disabled']); ?>
									</div>
								</div><br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?= Yii::t('app', 'Detail SPM'); ?></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th rowspan="2" style="width: 200px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode'); ?></th>
														<th rowspan="2" style="width: 250px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Nama Produk'); ?></th>
														<th colspan="3" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty'); ?></th>
														<th rowspan="2" style="width: 120px; line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Harga Satuan'); ?></th>
														<th rowspan="2" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Subtotal'); ?></th>
													</tr>
													<tr>
														<th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Palet'); ?></th>
														<th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 110px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
														<th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 90px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                                                        
														<th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px; display: none;"><?= Yii::t('app', '-'); ?></th>
														<th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 110px; padding: 5px; display: none;"><?= Yii::t('app', 'Satuan<br>Beli'); ?></th>
														<th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 90px; padding: 5px; display: none;"><?= Yii::t('app', 'Satuan<br>Angkut'); ?></th>
                                                        
                                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 50px; display: none; display: none;"><?= Yii::t('app', 'Batang'); ?></th>
                                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 130px; display: none; display: none;"><?= Yii::t('app', '-'); ?></th>
                                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 70px; display: none; display: none;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
													</tr>
												</thead>
												<tbody>

												</tbody>
												<tfoot>
													<tr>
														<td colspan="7" class="text-align-right"><b>
															Total Harga &nbsp;
														</b></td>
														<td style="font-size: 1.2rem; line-height: 0.9; padding: 5px;">
															<?= yii\bootstrap\Html::activeTextInput($model, "total_harga",['class'=>'form-control float','disabled'=>'disabled','style'=>'font-weight:600; padding:2px;']); ?>
														</td>
													</tr>
<!--													<tr>
														<td colspan="7" class="text-align-right"><b>
															Ppn 10% &nbsp;
														</b></td>
														<td style="font-size: 1.2rem; line-height: 0.9; padding: 5px;">
															<?php // echo yii\bootstrap\Html::activeTextInput($model, "total_ppn",['class'=>'form-control float','disabled'=>'disabled','style'=>'font-weight:600; padding:2px;']); ?>
														</td>
													</tr>-->
													<tr>
														<td colspan="2" ></td>
														<td colspan="4" class="text-align-right">
															<?php echo \yii\bootstrap\Html::activeTextInput($model, 'keterangan_potongan',['style'=>'font-weight:400; display:'.(!empty($model->keterangan_potongan)?'':'none').';','class'=>'form-control','placeholder'=>'Isikan Keterangan Potongan Harga']); ?>
														</td>
														<td class="text-align-right"><b>
															Potongan &nbsp;
														</b></td>
														<td style="font-size: 1.2rem; line-height: 0.9; padding: 5px;">
															<?= yii\bootstrap\Html::activeTextInput($model, "total_potongan",['class'=>'form-control float','style'=>'font-weight:600; padding:2px;','onblur'=>'total();']); ?>
														</td>
													</tr>
													<tr>
														<td colspan="7" class="text-align-right font-red-flamingo"><b>
															Total Bayar &nbsp;
														</b></td>
														<td style="font-size: 1.2rem; line-height: 0.9; padding: 5px;">
															<?= yii\bootstrap\Html::activeTextInput($model, "total_bayar",['class'=>'form-control float font-red-flamingo','disabled'=>'disabled','style'=>'font-weight:600; padding:2px;']); ?>
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
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print Nota'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printNota('.(isset($_GET['nota_penjualan_id'])?$_GET['nota_penjualan_id']:'').');','disabled'=>true]); ?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print SP'),['id'=>'btn-print2','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printSP('.(!empty($modSp->surat_pengantar_id)?$modSp->surat_pengantar_id:'').');','disabled'=>true]); ?>
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
if(isset($_GET['nota_penjualan_id'])){
    $pagemode = "afterSave(".$_GET['nota_penjualan_id'].");";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	$('select[name*=\"[spm_ko_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Tarik Kode SPM',
//		ajax: {
//			url: '".\yii\helpers\Url::toRoute('/marketing/notapenjualan/findSPM')."',
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
", yii\web\View::POS_READY); ?>
<script>
function setSPM(){
	var spm_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spm_ko_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/setSPM']); ?>',
        type   : 'POST',
        data   : {spm_ko_id:spm_ko_id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_pr_nama") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "syarat_jual") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($modTempo, "top_hari") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cara_bayar_reff") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_is_pkp") ?>").prop("checked",false);
			$('#table-detail tbody').html("");
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_harga") ?>").val( 0 );
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_ppn") ?>").val( 0 );
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val( 0 );
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_bayar") ?>").val( 0 );
			if(data.spm_ko_id){
				$("#modal-master").find('button.fa-close').trigger('click');
				$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val(data.jenis_produk);
				$("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").val(data.op_ko_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val(data.cust.cust_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val(data.cust.cust_an_nama);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_pr_nama") ?>").val(data.cust.cust_pr_nama);
				$("#<?= yii\bootstrap\Html::getInputId($model, "syarat_jual") ?>").val(data.op.syarat_jual);
				$("#<?= yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>").val(data.op.sistem_bayar);
				$("#<?= yii\bootstrap\Html::getInputId($modTempo, "top_hari") ?>").val( (data.tempo)?data.tempo.top_hari:"" );
				$("#<?= yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>").val(data.op.cara_bayar);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cara_bayar_reff") ?>").val(data.op.cara_bayar_reff);
				$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val(data.alamat_bongkar);
				$("#<?= yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>").val(data.kendaraan_nopol);
				$("#<?= yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>").val(data.kendaraan_supir);
				if(data.cust.cust_is_pkp){
					$("#<?= yii\bootstrap\Html::getInputId($model, "cust_is_pkp") ?>").prop("checked",true);
				}else{
					$("#<?= yii\bootstrap\Html::getInputId($model, "cust_is_pkp") ?>").prop("checked",false);
				}
				if(data.op.sistem_bayar == "Tempo"){
					$(".place-sistembayar").css('display','');
				}else{
					$(".place-sistembayar").css('display','none');
				}
				getItems(spm_ko_id);
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function openSPM(){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/openSPM']); ?>';
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

function getItems(spm_ko_id){
    var jns_produk = $("#<?= yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
    if(jns_produk == "Limbah"){
        $(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","");
        $(".place-satuan-gesek").css("display","none");
    }else if(jns_produk == "JasaGesek"){
        $(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","none");
        $(".place-satuan-gesek").css("display","");
    }else{
        $(".place-satuan-produk").css("display","");
        $(".place-satuan-limbah").css("display","none");
        $(".place-satuan-gesek").css("display","none");
    }
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/getItems']); ?>',
		type   : 'POST',
		data   : {spm_ko_id:spm_ko_id,jns_produk:jns_produk},
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

function subTotal(){
	var jnsproduk = $("#<?= \yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
	$("#table-detail tbody tr").each(function(){
		var qty_kecil = unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
		var harga = unformatNumber( $(this).find('input[name*="[harga_jual]"]').val() );
		var kubikasi = unformatNumber( $(this).find('input[name*="[kubikasi]"]').val() );
		var subtotal = 0;
		if(jnsproduk == "Plywood" || jnsproduk == "Lamineboard" || jnsproduk == "Platform"){
			subtotal = qty_kecil * harga;
		}else{
			subtotal = kubikasi * harga;
		}
		var ppn = 0;
		if( $('#<?= yii\bootstrap\Html::getInputId($model, "cust_is_pkp") ?>').prop('checked') ){
			ppn = subtotal * 0.1;
		}
		$(this).find('input[name*="[ppn]"]').val( ppn );
		$(this).find('input[name*="[subtotal]"]').val( formatNumberForUser(subtotal) );
	});
	total();
}

function total(){
	var total_harga = 0;
	var total_ppn = 0;
	var total_potongan = unformatNumber( $("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val() );
	$("#table-detail tbody tr").each(function(){
		total_harga += unformatNumber( $(this).find('input[name*="[subtotal]"]').val() );
		total_ppn += unformatNumber( $(this).find('input[name*="[ppn]"]').val() );
	});
	var total_bayar = (total_harga + total_ppn) - total_potongan;
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_harga") ?>").val( formatNumberForUser(total_harga) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_ppn") ?>").val( formatNumberForUser(total_ppn) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val( formatNumberForUser(total_potongan) );
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_bayar") ?>").val( formatNumberForUser(total_bayar) );
}

function save(){
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").parents("td").removeClass("has-error");
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
            return false;
        }
		if(validatingDetail()){
			submitform($form);
        }
    }
    return false;
}

function validatingDetail($form){
	var has_error = 0;
	var potongan = unformatNumber( $("#<?= \yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val() );
	if(potongan > 0){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").slideDown();
		if(!$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").val()){
			$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").parents("td").addClass("has-error");
			has_error = has_error + 1;
		}
	}else{
		$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").slideUp();
		$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_potongan") ?>").val("");
	}
	if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
	getItemsById(id);
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_kirim') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    $('#btn-print2').removeAttr('disabled');
	$('#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>')
	<?php if(isset($_GET['edit'])){ ?>
		$('#<?= \yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_kirim") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal_kirim') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "waktu_selesaimuat") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>').prop("disabled", false);
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
		$('#btn-print2').prop('disabled',true);
	<?php } ?>
}

function getItemsById(id,edit=null){
    var jns_produk = $("#<?= yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
    if(jns_produk == "Limbah"){
        $(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","");
    }else{
        $(".place-satuan-produk").css("display","");
        $(".place-satuan-limbah").css("display","none");
    }
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/getItemsById']); ?>',
		type   : 'POST',
		data   : {id:id,edit:edit},
		success: function (data) {
			if(data.html){
				if(data.model.cust_is_pkp){
					$("#<?= yii\bootstrap\Html::getInputId($model, "cust_is_pkp") ?>").prop("checked",true);
				}else{
					$("#<?= yii\bootstrap\Html::getInputId($model, "cust_is_pkp") ?>").prop("checked",false);
				}
				$('#table-detail tbody').html(data.html);
				formconfig();
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function printNota(id){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/checkApproval']); ?>',
		type   : 'POST',
		data   : {nota_penjualan_id:id},
		success: function (data) {
			if(data.status){
				window.open("<?= yii\helpers\Url::toRoute('/marketing/notapenjualan/printNota') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
			}else{
				cisAlert("Diperlukan Authority khusus untuk jumlah potongan yang diinputkan");
				return false;
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function printSP(id){
	var nota_penjualan_id = '<?= $model->nota_penjualan_id ?>';
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/checkApproval']); ?>',
		type   : 'POST',
		data   : {nota_penjualan_id:nota_penjualan_id},
		success: function (data) {
			if(data.status){
				window.open("<?= yii\helpers\Url::toRoute('/marketing/notapenjualan/printSP') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
			}else{
				cisAlert("Diperlukan Authority khusus untuk jumlah potongan yang diinputkan");
				return false;
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/index','nota_penjualan_id'=>'']); ?>'+id+'&edit=1');
}

function printout(id){
	var caraPrint = "PRINT";
	window.open("<?= yii\helpers\Url::toRoute(['/marketing/notapenjualan/printNota','id'=>'']) ?>"+id+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>