<?php
/* @var $this yii\web\View */
$this->title = 'Open Voucher';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Open Voucher'); ?>  <small> Form Pengajuan Pembayaran Kepada Finance </small></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.table-advance tr td:first-child {
    border-left-width: 1px !important;
}
.table-detail-terimabhp {
    border-left: 1px solid transparent !important;
}
.table-detail-terimabhp > tbody > tr > td{
    background-color: #e2f1ff; border: 1px solid #303030;
}
.table-detail-terimabhp > tbody > tr > th{
    background-color: #e2f1ff; border: 1px solid #303030;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Voucher Yang Telah Dibuat'); ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Voucher'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?php 
										if(!isset($_GET['open_voucher_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
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
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, "open_voucher_id") ?>
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, "departement_id") ?>
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, "prepared_by") ?>
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, "penerima_reff_table") ?>
                                        <?php echo $form->field($model, 'departement_nama')->textInput(['class'=>'form-control','disabled'=>true])->label("Dept"); ?>
                                        <?php echo $form->field($model, 'tanggal')->textInput(['class'=>'form-control','disabled'=>true]); ?>
                                        <?php echo $form->field($model, 'tipe')->dropDownList([],['prompt'=>'','class'=>'form-control','onchange'=>'setApprover(); setPenerima();']); ?>
                                        <div id="place-penerima-reff" class="form-group" style="display: none;">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Penerima Pembayaran'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 5px;">
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'penerima_voucher_id', [], ['prompt'=>'','class'=>'form-control','style'=>'width:100%']) ?>
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'penerima_reff_id', [], ['prompt'=>'','class'=>'form-control','style'=>'width:100%']) ?>
                                            </div>
                                        </div>
                                        <div id="place-reff-no"></div>
                                        <div id="place-berkas-reff"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->field($model, 'cara_bayar')->dropDownList(app\models\MDefaultValue::getOptionListCustom("cara-bayar-voucher-penerimaan","'Cek','Bilyet Giro'","ASC"),['class'=>'form-control']); ?>
                                        <?php echo $form->field($model, 'approver_1')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['prompt'=>'','class'=>'form-control']); ?>
                                        <?php echo $form->field($model, 'approver_2')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['prompt'=>'','class'=>'form-control']); ?>
                                        <?php echo $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                </div><br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Detail Uraian Voucher'); ?></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; padding: 5px;">No.</th>
														<th style="padding: 5px;">Deskripsi</th>
                                                        <th style="width: 100px; padding: 5px;">Nominal</th>
														<th style="width: 100px; padding: 5px;">PPn</th>
														<th style="width: 100px; padding: 5px;">Pph</th>
														<th style="width: 120px; padding: 5px;">Subtotal</th>
														<th style="width: 50px;"><?= Yii::t('app', 'Cancel'); ?></th>
													</tr>
												</thead>
												<tbody>

												</tbody>
												<tfoot>
													<tr>
														<td colspan="3">
															<a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
														</td>
														<td colspan="2" style="vertical-align: middle; text-align: right;">
															Total DPP
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_dpp',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
													<tr>
														<td colspan="5" style="vertical-align: middle; text-align: right;">
															Total PPn
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_ppn',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
                                                    <tr>
														<td colspan="5" style="vertical-align: middle; text-align: right;">
															Total Pph
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_pph',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
                                                    <tr>
														<td colspan="5" style="vertical-align: middle; text-align: right;">
															Potongan
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_potongan',0,['class'=>'form-control float','style'=>'font-size:1.2rem; padding:5px;','onblur'=>'total();']); ?>
														</td>
													</tr>
                                                    <tr>
														<td colspan="5" style="vertical-align: middle; text-align: right;">
															Biaya Tambahan
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('biaya_tambahan',0,['class'=>'form-control float','style'=>'font-size:1.2rem; padding:5px;','onblur'=>'total();']); ?>
														</td>
													</tr>
                                                    <tr>
                                                        <td colspan="5" style="vertical-align: middle; text-align: right;" class="font-red-flamingo">
															TOTAL PEMBAYARAN
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_pembayaran',0,['class'=>'form-control float font-red-flamingo','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
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
if(isset($_GET['open_voucher_id'])){
    $pagemode = "setTimeout(function(){ afterSaveThis(); },1000)";
}else {
    $pagemode = "addItem(); ";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
    SetDDTipeOVByDept();
    $(this).find('select[name*=\"[approver_1]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
		width: null
	});
    $(this).find('select[name*=\"[approver_2]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
		width: null
	});
", yii\web\View::POS_READY); ?>
<script>
function SetDDTipeOVByDept(){
    var dept_id = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    var open_voucher_id = $("#<?= yii\helpers\Html::getInputId($model, "open_voucher_id") ?>").val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/SetDDTipeOVByDept']); ?>',
		type   : 'POST',
		data   : {dept_id:dept_id,open_voucher_id:open_voucher_id},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'tipe') ?>").html(data.html);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setPenerima(){
    var open_voucher_id = $("#<?= yii\helpers\Html::getInputId($model, "open_voucher_id") ?>").val();
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    $("#place-penerima-reff").hide();
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").html("");
//    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2('destroy');
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").prop("disabled",false);
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").html("");
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").hide();
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").prop("disabled",false);
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("");
    $("#place-reff-no").html("");
    $("#place-berkas-reff").html("");
    if(tipe){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/SetDDPenerima']); ?>',
            type   : 'POST',
            data   : {tipe:tipe,open_voucher_id:open_voucher_id},
            success: function (data) {
                $("#place-penerima-reff").show();
                
                if(data.html_penerima_reff){
                    $("#place-reff-no").html(data.html_penerima_reff);
                    $("#place-reff-no").find(".tooltips").tooltip({ delay: 50 });
                }
                if(data.html_berkas_reff){
                    $("#place-berkas-reff").html(data.html_berkas_reff);
                }
                
                if(tipe == "REGULER"){
                    $("#<?= \yii\helpers\Html::getInputId($model, "penerima_voucher_id") ?>").html(data.html);
                    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").show();
                    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_penerima_voucher");
                }else if(tipe == "PEMBAYARAN LOG ALAM"){
                    $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").html(data.html);
                    $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").show();
                    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").prop("disabled",true);
                    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_suplier");
                }else if(tipe == "DEPOSIT SUPPLIER LOG"){
                    $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").html(data.html);
                    $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").show();
                    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_suplier");
                }else if(tipe == "DP LOG SENGON" || tipe == "PELUNASAN LOG SENGON"){
                    $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").html(data.html);
                    $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").show();
                    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").prop("disabled",true);
                    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_suplier");
                    $('#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>').select2({
                        placeholder: 'Ketik Kode Tagihan',
                        width: null
                    });
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}
function setApprover(){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/setApprover']); ?>',
		type   : 'POST',
		data   : {tipe:tipe},
		success: function (data) {
            $("#<?= yii\bootstrap\Html::getInputId($model, "approver_1") ?>").val(data.approver_1).trigger('change');
            $("#<?= yii\bootstrap\Html::getInputId($model, "approver_2") ?>").val(data.approver_2).trigger('change');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function openKeputusan(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/daftarAfterSave','pick'=>'1']) ?>','modal-aftersave','90%');
}
function openPOSengon(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/cariPoSengon','pick'=>'1']) ?>','modal-posengon','90%');
}
function openTagihanSengon(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/tagihansengon/daftarAfterSave','pick'=>'1']) ?>','modal-aftersave','90%');
}
function pick(kode,suplier_id){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    $('#table-detail > tbody').html( "" ); 
    addItem();
    total();
	$("#modal-aftersave").find('button.fa-close').trigger('click');
	$("#modal-posengon").find('button.fa-close').trigger('click');
	$('#<?= yii\bootstrap\Html::getInputId($model, "reff_no") ?>').val(kode);
	$('#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>').val(suplier_id);
    $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple');
    $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+suplier_id+'")');
//    if(terima_sengon_id){
//        $("#place-berkas-reff").find('#btn-reff-3').removeClass('grey').addClass('green-seagreen').attr('onclick','detailTerima('+terima_sengon_id+')');
//    }
    setDDReff2(tipe,kode);
}
function pickTagihanSengon(kode,kode_po,suplier_id,posengon_id,tagihan_sengon_id,terima_sengon_id){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
	$("#modal-aftersave").find('button.fa-close').trigger('click');
	$("#modal-posengon").find('button.fa-close').trigger('click');
	$('#<?= yii\bootstrap\Html::getInputId($model, "reff_no") ?>').val(kode_po);
	$('#<?= yii\bootstrap\Html::getInputId($model, "reff_no2") ?>').val(kode);
	$('#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>').val(suplier_id);
    $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple').attr('onclick','detailPo("'+posengon_id+'")');
    $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+suplier_id+'")');
    if(terima_sengon_id){
        $("#place-berkas-reff").find('#btn-reff-3').removeClass('grey').addClass('green-seagreen').attr('onclick','detailTerima('+terima_sengon_id+')');
    }
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/getItemsByTagihanSengon']); ?>',
        type   : 'POST',
        data   : { tagihan_sengon_id:tagihan_sengon_id },
        success: function (data){
            if(data.html){
                $('#table-detail > tbody').html( data.html );
                reordertable('#table-detail');
                total();
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function setDDReff2(tipe,kode){
    var open_voucher_id = "<?= isset($_GET['open_voucher_id'])?$_GET['open_voucher_id']:'' ?>";
    if(tipe == "PELUNASAN LOG SENGON"){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/setDDReff2']); ?>',
            type   : 'POST',
            data   : { tipe:tipe,kode:kode,open_voucher_id:open_voucher_id },
            success: function (data){
                if(data.html){
                    $("#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>").html(data.html);
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}
function setReff2(){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    var reff_no2 = $("#<?= yii\helpers\Html::getInputId($model, "reff_no2") ?>").val();
    $('#table-detail > tbody').html("");
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/setReff2']); ?>',
        type   : 'POST',
        data   : { tipe:tipe,reff_no2:reff_no2 },
        success: function (data){
            if(tipe == "PELUNASAN LOG SENGON"){
                if(data.html){
                    $('#table-detail > tbody').html(data.html);
                    reordertable('#table-detail');
                    total();
                }
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function addItem(){
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/addItem']); ?>',
        type   : 'POST',
        data   : {  },
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail > tbody').fadeIn(100,function(){
                    reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function total(){
    var total_dpp = 0; var total_ppn = 0; var total_pph = 0; var total = 0; 
    var potongan = unformatNumber( $("#table-detail").find('input[name="total_potongan"]').val() );
    var biaya_tambahan = unformatNumber( $("#table-detail").find('input[name="biaya_tambahan"]').val() );
    $("#table-detail > tbody > tr").each(function(i){
        var nominal = unformatNumber( $(this).find('input[name*="[nominal]"]').val() );
        var ppn = unformatNumber( $(this).find('input[name*="[ppn]"]').val() );
        var pph = unformatNumber( $(this).find('input[name*="[pph]"]').val() );
        var subtotal = (nominal + ppn) - pph;
        total_dpp += nominal;
        total_ppn += ppn;
        total_pph += pph;
        $(this).find('input[name*="[subtotal]"]').val( formatNumberForUser(subtotal) );
        if (i+1 === $("#table-detail > tbody > tr").length) { // fire after loop
            total = (total_dpp + total_ppn) - total_pph;
            total = total - potongan + biaya_tambahan;
            $("#table-detail").find('input[name="total_dpp"]').val( formatNumberForUser(total_dpp) );
            $("#table-detail").find('input[name="total_ppn"]').val( formatNumberForUser(total_ppn) );
            $("#table-detail").find('input[name="total_pph"]').val( formatNumberForUser(total_pph) );
            $("#table-detail").find('input[name="total_pembayaran"]').val( formatNumberForUser(total) );
        }
    });
    $("#table-detail").find('input[name="total_dpp"]').val( formatNumberForUser(total_dpp) );
    $("#table-detail").find('input[name="total_ppn"]').val( formatNumberForUser(total_ppn) );
    $("#table-detail").find('input[name="total_pph"]').val( formatNumberForUser(total_pph) );
    $("#table-detail").find('input[name="total_pembayaran"]').val( formatNumberForUser(total) );
}

function save(){
    var $form = $('#form-transaksi');
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

function validatingDetail(){
    var has_error = 0;
	var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    if(tipe == "PEMBAYARAN LOG ALAM"){
        if(!$("input[name*='[reff_no]']").val()){
            $("input[name*='[reff_no]']").parents(".form-group").addClass("error-tb-detail");
            has_error = has_error + 1;
        }else{
            $("input[name*='[reff_no]']").parents(".form-group").removeClass();
        }
    }else if(tipe == "DP LOG SENGON"){
        if(!$("input[name*='[reff_no]']").val()){
            $("input[name*='[reff_no]']").parents(".form-group").addClass("error-tb-detail");
            has_error = has_error + 1;
        }else{
            $("input[name*='[reff_no]']").parents(".form-group").removeClass();
        }
    }else if(tipe == "PELUNASAN LOG SENGON"){
        if(!$("input[name*='[reff_no]']").val()){
            $("input[name*='[reff_no]']").parents(".form-group").addClass("error-tb-detail");
            has_error = has_error + 1;
        }else{
            $("input[name*='[reff_no]']").parents(".form-group").removeClass();
        }
        if(!$("select[name*='[reff_no2]']").val()){
            $("select[name*='[reff_no2]']").parents(".form-group").addClass("error-tb-detail");
            has_error = has_error + 1;
        }else{
            $("select[name*='[reff_no2]']").parents(".form-group").removeClass();
        }
    }else{
        $("input[name*='[reff_no]']").parents(".form-group").removeClass();
    }
    $('#table-detail > tbody > tr').each(function(){
        var field1 = $(this).find('textarea[name*="[deskripsi]"]');
        if(!field1.val()){
            $(this).find('textarea[name*="[deskripsi]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('textarea[name*="[deskripsi]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    
	<?php if(isset($_GET['edit'])){ ?>
		has_error = 0;
	<?php } ?>
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSaveThis(id){
	<?php if(!isset($_GET['edit'])){ ?>
        setHeaderAfterSave();
		getItems(id);
		$('#btn-add-item').hide();
	<?php }else{ ?>
        setHeaderAfterSave(1);
		getItems(id,1);
	<?php } ?>
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if(isset($_GET['edit'])){ ?>
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'tipe') ?>").prop("disabled", false);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'cara_bayar') ?>").prop("disabled", false);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'approver_1') ?>").prop("disabled", false);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'approver_2') ?>").prop("disabled", false);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'keterangan') ?>").prop("disabled", false);
        $("input[name*='total_potongan']").prop("disabled", false);
        $("input[name*='biaya_tambahan']").prop("disabled", false);
	<?php } ?>
}

function setHeaderAfterSave(edit){
    var open_voucher_id = $("#<?= yii\helpers\Html::getInputId($model, "open_voucher_id") ?>").val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/setHeaderAfterSave']); ?>',
		type   : 'POST',
		data   : {open_voucher_id:open_voucher_id},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'tipe') ?>").val(data.model.tipe);
			$("input[name*='total_potongan']").val( formatNumberForUser(data.model.total_potongan) );
			$("input[name*='biaya_tambahan']").val( formatNumberForUser(data.model.biaya_tambahan) );
            setPenerima();
            $("#place-reff-no").html(data.html);
            $("#place-reff-no").find(".tooltips").tooltip({ delay: 50 });
            <?php if((isset($_GET['open_voucher_id']))&&(!isset($_GET['edit']))){ ?>
                setTimeout(function(){
                    $("#<?= \yii\bootstrap\Html::getInputId($model, 'reff_no') ?>").parents(".form-group").find(".input-group-btn:last").addClass("hidden");
                },600);
            <?php } ?>
            <?php if(isset($_GET['open_voucher_id'])){ ?>
                setTimeout(function(){
                    if(data.model.tipe == "REGULER"){
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_voucher_id') ?>").prop("disabled",true);
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_voucher_id') ?>").val(data.model.penerima_voucher_id);
                    }else if(data.model.tipe == "PEMBAYARAN LOG ALAM"){
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").prop("disabled",true);
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.model.penerima_reff_id);
                    }else if(data.model.tipe == "DEPOSIT SUPPLIER LOG"){
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").prop("disabled",true);
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.model.penerima_reff_id);
                    }else if(data.model.tipe == "DP LOG SENGON"){
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").prop("disabled",true);
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.model.penerima_reff_id);
                        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple');
                        $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+data.model.penerima_reff_id+'")');
                    }else if(data.model.tipe == "PELUNASAN LOG SENGON"){
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").prop("disabled",true);
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.model.penerima_reff_id);
                        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple');
                        $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+data.model.penerima_reff_id+'")');
                        if(data.modTagihan.terima_sengon_id){
                            $("#place-berkas-reff").find('#btn-reff-3').removeClass('grey').addClass('green-seagreen').attr('onclick','detailTerima('+data.modTagihan.terima_sengon_id+')');
                        }
                        if(data.model.reff_no2){
                            var reff_no2 = data.model.reff_no2.split(',');
                            setDDReff2(data.model.tipe,data.model.reff_no);
                            setTimeout(function(){
                                $("#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>").val( reff_no2 ).change();
                                <?php if( isset($_GET['open_voucher_id']) && !isset($_GET['edit']) ){ ?>
                                    setTimeout(function(){
                                        $("#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>").prop("disabled",true);
                                        $('form').find('input').each(function(){ $(this).prop("disabled", true); });
                                        $('form').find('textarea').each(function(){ $(this).prop("disabled", true); });
                                    },500);
                                <?php } ?>
                            },500);
                        }
                    }
                },600);
            <?php } ?>
            
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getItems(open_voucher_id,edit=null){
var open_voucher_id = $("#<?= yii\helpers\Html::getInputId($model, "open_voucher_id") ?>").val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/getItems']); ?>',
		type   : 'POST',
		data   : {open_voucher_id:open_voucher_id,edit:edit},
		success: function (data) {
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			setTimeout(function(){
                reordertable('#table-detail');
				total();
			},500);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/daftarAfterSave']) ?>','modal-aftersave-this','95%');
}
function infoVoucher(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/finance/voucher/detailBbk']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-bbk").modal('show');
		$("#modal-bbk").on('hidden.bs.modal', function () { });
		$("#modal-bbk .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}
function detailPoByKode(id){
    var reff_no = $("#<?= \yii\helpers\Html::getInputId($model, "reff_no") ?>").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/detailPoByKode','kode'=>'']) ?>'+reff_no,'modal-detailpo','22cm');
}
function detailPo(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/detailPo','id'=>'']) ?>'+id,'modal-detailpo','22cm');
}
function detailTerima(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/ppic/terimasengon/detailrekap','id'=>'']) ?>'+id,'modal-detailterima','90%');
}
function riwayatSaldoSuplierSengon(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/saldosuplierlog/riwayatSaldo','id'=>'']) ?>'+id,'modal-riwayatsaldo','80%');
}
</script>