<?php
/* @var $this yii\web\View */
$this->title = 'Retur Penjualan';
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
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Retur Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
								<span class="caption-subject bold"><h4>
								<?= Yii::t('app', 'Retur Penjualan'); ?>
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
										<?php if(isset($_GET['retur_produk_id'])){ ?>
											<?= yii\bootstrap\Html::activeHiddenInput($model, "nota_penjualan_id"); ?>
											<?= $form->field($model, 'kode_nota')->textInput()->label("Kode Nota"); ?>
										<?php }else{ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Nota'); ?></label>
												<div class="col-md-7">
													<span class="input-group-btn" style="width: 100%">
														<?= \yii\bootstrap\Html::activeDropDownList($model, 'nota_penjualan_id', \app\models\TNotaPenjualan::getOptionListRetur(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setNota()','style'=>'width:100%;','data-placeholder'=>'Pilih Nota Penjualan']); ?>
													</span>
													<span class="input-group-btn">
														<a class="btn btn-icon-only btn-default tooltips" onclick="openNota();" data-original-title="Daftar Nota" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
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
															<input name="TNotaPenjualan[cust_is_pkp]" value="0" type="hidden">
															<input id="tnotapenjualan-cust_is_pkp" name="TNotaPenjualan[cust_is_pkp]" value="1" type="checkbox" onchange="subTotal();" disabled="disabled"> 
															<span class="help-block"></span>
															<div style="padding-top: 3px; margin-left: 10px;">PKP</div>
														</label> 
													</div>
												</span>
											</div>
										</div>
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
										if(!isset($_GET['retur_produk_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Kode Retur");
										}else{ ?>
											<div class="form-group">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Retur'); ?></label>
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
																	{error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Retur"); ?>
										<?= $form->field($model, 'alasan_retur')->textarea(); ?>
										<?= $form->field($model, 'kendaraan_nopol')->textInput(); ?>
										<?= $form->field($model, 'kendaraan_supir')->textInput(); ?>
									</div>
								</div><br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?= Yii::t('app', 'Detail Retur'); ?></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th rowspan="2" style="width: 160px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode'); ?></th>
														<th rowspan="2" style="width: 250px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Nama Produk'); ?></th>
														<th colspan="2" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty'); ?></th>
														<th rowspan="2" style="width: 100px; line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Harga Jual'); ?></th>
														<th rowspan="2" style="width: 100px; line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Harga Retur'); ?></th>
														<th rowspan="2" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Subtotal'); ?></th>
														<th rowspan="2" style="width: 20px; line-height: 0.9;"></th>
													</tr>
													<tr>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil (Pcs)'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
													</tr>
												</thead>
												<tbody>

												</tbody>
												<tfoot>
													<tr>
														<td colspan="7" class="text-align-right"><b>
															Total Retur &nbsp;
														</b></td>
														<td style="font-size: 1.2rem; line-height: 0.9; padding: 5px;">
															<?= yii\bootstrap\Html::activeTextInput($model, "total_retur",['class'=>'form-control float','disabled'=>'disabled','style'=>'font-weight:600; padding:2px;']); ?>
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
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print Nota Retur'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printNota('.(isset($_GET['retur_produk_id'])?$_GET['retur_produk_id']:'').');','disabled'=>true]); ?>
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
if(isset($_GET['retur_produk_id'])){
    $pagemode = "afterSave(".$_GET['retur_produk_id']."); setNota();";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function setNota(){
	var nota_penjualan_id = $('#<?= yii\bootstrap\Html::getInputId($model, "nota_penjualan_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/returpenjualan/setNota']); ?>',
        type   : 'POST',
        data   : {nota_penjualan_id:nota_penjualan_id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "syarat_jual") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($modTempo, "top_hari") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cara_bayar_reff") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_is_pkp") ?>").prop("checked",false);
			$('#table-detail tbody').html("");
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_retur") ?>").val( 0 );
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_ppn") ?>").val( 0 );
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val( 0 );
			$("#<?= yii\bootstrap\Html::getInputId($model, "total_bayar") ?>").val( 0 );
			if(data.nota_penjualan_id){
				$("#modal-master").find('button.fa-close').trigger('click');
				$("#<?= yii\bootstrap\Html::getInputId($model, "kode_nota") ?>").val(data.kode);
				$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val(data.jenis_produk);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val(data.cust.cust_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val(data.cust.cust_an_nama);
				$("#<?= yii\bootstrap\Html::getInputId($model, "syarat_jual") ?>").val(data.op.syarat_jual);
				$("#<?= yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>").val(data.op.sistem_bayar);
				$("#<?= yii\bootstrap\Html::getInputId($modTempo, "top_hari") ?>").val( (data.tempo)?data.tempo.top_hari:"" );
				$("#<?= yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>").val(data.op.cara_bayar);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cara_bayar_reff") ?>").val(data.op.cara_bayar_reff);
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
				<?php if(isset($_GET['retur_produk_id'])){ ?>
					<?php if(isset($_GET['edit'])){ ?>
//						getItems( $("#<?php // echo yii\helpers\Html::getInputId($model, "nota_penjualan_id") ?>").val() );
						getItemsById( "<?= $_GET['retur_produk_id'] ?>" , true );
					<?php }else{ ?>
						getItemsById("<?= $_GET['retur_produk_id'] ?>");
					<?php } ?>
				<?php }else{ ?>
					getItems(nota_penjualan_id);
				<?php } ?>
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function openNota(){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/returpenjualan/openNota']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pick(nota_penjualan_id,kode){
	$("#modal-master").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "nota_penjualan_id") ?>").empty().append('<option value="'+nota_penjualan_id+'">'+kode+'</option>').val(nota_penjualan_id).trigger('change');
}

function getItems(nota_penjualan_id){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/returpenjualan/getItems']); ?>',
		type   : 'POST',
		data   : {nota_penjualan_id:nota_penjualan_id},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				formconfig();
			}
			setTimeout(function(){
				subTotal();
			},500)
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setMeterKubik(ele){
    var p = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_p]"]').val() );
    var l = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_l]"]').val() );
    var t = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_t]"]').val() );
    var sat_p = $(ele).parents('tr').find('input[name*="[produk_p_satuan]"]').val();
    var sat_l = $(ele).parents('tr').find('input[name*="[produk_l_satuan]"]').val();
    var sat_t = $(ele).parents('tr').find('input[name*="[produk_t_satuan]"]').val();
    var qty = unformatNumber( $(ele).parents('tr').find('input[name*="[qty_kecil]"]').val() );
    var sat_p_m = 0;
    var sat_l_m = 0;
    var sat_t_m = 0;
    var result = 0; var result_display = 0;
    if(sat_p == 'mm'){
        sat_p_m = p * 0.001;
    }else if(sat_p == 'cm'){
        sat_p_m = p * 0.01;
    }else if(sat_p == 'inch'){
        sat_p_m = p * 0.0254;
    }else if(sat_p == 'm'){
        sat_p_m = p;
    }else if(sat_p == 'feet'){
        sat_p_m = p * 0.3048;
    }
    if(sat_l == 'mm'){
        sat_l_m = l * 0.001;
    }else if(sat_l == 'cm'){
        sat_l_m = l * 0.01;
    }else if(sat_l == 'inch'){
        sat_l_m = l * 0.0254;
    }else if(sat_l == 'm'){
        sat_l_m = l;
    }else if(sat_l == 'feet'){
        sat_l_m = l * 0.3048;
    }
    if(sat_t == 'mm'){
        sat_t_m = t * 0.001;
    }else if(sat_t == 'cm'){
        sat_t_m = t * 0.01;
    }else if(sat_t == 'inch'){
        sat_t_m = t * 0.0254;
    }else if(sat_t == 'm'){
        sat_t_m = t;
    }else if(sat_t == 'feet'){
        sat_t_m = t * 0.3048;
    }
    result = sat_p_m * sat_l_m * sat_t_m * qty;
    result_display = (Math.round( result * 10000 ) / 10000 ).toString();
    setTimeout(function() {
		if( $(ele).parents('tr').find("input[name*='[is_random]']").val() != 1 ){
			$(ele).parents('tr').find('input[name*="[kubikasi]"]').val( result );
			$(ele).parents('tr').find('input[name*="[kubikasi_display]"]').val( formatNumberFixed4(result_display) );
			subTotal();
		}
    }, 300);
}

function subTotal(){
	var jnsproduk = $("#<?= \yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
	$("#table-detail tbody tr").each(function(){
		var qty_kecil = unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
		var harga = unformatNumber( $(this).find('input[name*="[harga_retur]"]').val() );
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
		$(this).find('input[name*="[subtotal]"]').val( formatNumberForUser(subtotal) );
	});
	total();
}

function total(){
	var total_retur = 0;
	var total_potongan = unformatNumber( $("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val() );
	$("#table-detail tbody tr").each(function(){
		total_retur += unformatNumber( $(this).find('input[name*="[subtotal]"]').val() );
	});
	$("#<?= yii\bootstrap\Html::getInputId($model, "total_retur") ?>").val( formatNumberForUser(total_retur) );
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
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    $('#btn-print2').removeAttr('disabled');
	$('#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>')
	<?php if(isset($_GET['edit'])){ ?>
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "alasan_retur") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>').prop("disabled", false);
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
		$('#btn-print2').prop('disabled',true);
	<?php } ?>
}

function getItemsById(id,edit=null){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/returpenjualan/getItemsById']); ?>',
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
				setTimeout(function(){
					if(edit){
						$('#table-detail tbody').each(function(){
							setMeterKubik($(this).find("input[name*='[qty_kecil]']"));
						});
					}else{
						total();
					}
				},500);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/marketing/returpenjualan/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function printNota(id){
	window.open("<?= yii\helpers\Url::toRoute('/marketing/returpenjualan/printNota') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/marketing/returpenjualan/index','nota_penjualan_id'=>'']); ?>'+id+'&edit=1');
}
</script>