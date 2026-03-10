<?php
/* @var $this yii\web\View */
$this->title = 'Invoice Lokal';
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
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<span class="pull-right">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Riwayat Invoice'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>
										<?php
										if(!isset($_GET['invoice_lokal_id'])){
											echo "Invoice Baru";
										}else{
											echo "Data Invoice";
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
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nomor Invoice'); ?></label>
                                            <div class="col-md-7" style="padding-bottom: 5px;">
                                                <span class="input-group-btn" style="width: 20%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode1', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode2', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;','disabled'=>true]) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode3', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;','disabled'=>true]) ?>
                                                    <?php // echo \yii\bootstrap\Html::activeDropDownList($model, 'kode3', ["JASA"=>"JASA"], ['class'=>'form-control','style'=>'width:100%;padding:2px;']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode4', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode5', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;']) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?= $form->field($model, 'tanggal',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Invoice"); ?>
                                        <div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Customer'); ?></label>
											<div class="col-md-7">
												<?=  \yii\bootstrap\Html::activeDropDownList($model, 'cust_id', \app\models\MCustomer::getOptionListInvoiceLokal(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setCustomer()','style'=>'width:100%;']); ?>
											</div>
										</div>
										<?= $form->field($model, 'cust_an_alamat')->textarea(['disabled'=>true])->label("Alamat Customer"); ?>
										<?= $form->field($model, 'cust_no_npwp')->textInput()->label("NPWP Customer"); ?>
										<?= $form->field($model, 'no_faktur_pajak')->textInput()->label("No. Faktur Pajak"); ?>
										
									</div>
									<div class="col-md-6">
										
                                        <?= $form->field($model, 'op_ko_id')->dropDownList( [],['class'=>'form-control','prompt'=>'','onchange'=>'setOpKo()','style'=>'width:100%;'] ); ?>
                                        <?= $form->field($model, 'jenis_produk')->textInput(['disabled'=>true])->label("Jenis Produk"); ?>
                                        <?= $form->field($model, 'penerbit')->dropDownList( app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control','prompt'=>'','onchange'=>'','style'=>'width:100%;'] ); ?>
                                        <?= $form->field($model, 'cara_bayar')->dropDownList( ["Transfer Bank"=>"Transfer Bank"],['class'=>'form-control','style'=>'width:100%;'] ); ?>
                                        <?= $form->field($model, 'mata_uang')->dropDownList( app\models\MDefaultValue::getOptionList("mata-uang"),['class'=>'form-control','style'=>'width:100%;'] ); ?>
                                        <?= $form->field($model, 'include_ppn',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>'])
                        ->checkbox(['onchange'=>'total()'],false)->label(Yii::t('app', 'Include PPN')); ?>
									</div>
								</div><br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?= Yii::t('app', 'Detail Order'); ?></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
                                                        <th rowspan="2" style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.3rem;">No.</th>
														<th rowspan="2" style="width: 200px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Nota'); ?></th>
														<th rowspan="2" style="line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Deskripsi'); ?></th>
														<th colspan="2" style="line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Pengiriman'); ?></th>
														<th colspan="2" style="line-height: 0.9;  padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Qty'); ?></th>
														<th rowspan="2" style="width: 120px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Harga'); ?></th>
														<th rowspan="2" style="width: 120px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Total'); ?></th>
														<th rowspan="2"h style="width: 40px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"></th>
													</tr>
                                                    <tr>
                                                        <th style="width: 80px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">Tanggal</th>
                                                        <th style="width: 170px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">Nopol / Supir</th>
                                                        <th style="width: 60px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">Pcs</th>
                                                        <th style="width: 100px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">M<sup>3</sup></th>
                                                    </tr>
												</thead>
												<tbody>

												</tbody>
												<tfoot>
                                                    <tr>
														<td colspan="4">
															<!--<a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>-->
														</td>
														<td style="vertical-align: middle; text-align: right;">
															Total &nbsp; 
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_pcs',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_m3',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
                                                        <td style="vertical-align: middle; text-align: right;">
															DPP &nbsp; 
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_harga',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
                                                    <tr>
														<td colspan="7"></td>
														<td style="vertical-align: middle; text-align: right;">
															PPN &nbsp; 
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_ppn',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
                                                    <tr>
														<td colspan="7"></td>
														<td style="vertical-align: middle; text-align: right;">
															PPH &nbsp; 
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_pph',0,['class'=>'form-control float','disabled'=>false,'style'=>'font-size:1.2rem; padding:5px;','onblur'=>'total()']); ?>
														</td>
													</tr>
													<tr>
                                                        <td colspan="7">
                                                            
                                                        </td>
														<td style="vertical-align: middle; text-align: right;">
															Grand Total &nbsp; 
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_bayar',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
													
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div><br><br>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printInvoice('.(isset($_GET['invoice_lokal_id'])?$_GET['invoice_lokal_id']:'').');','disabled'=>true]); ?>
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
if(isset($_GET['invoice_lokal_id'])){
    $pagemode = "afterSave(".$_GET['invoice_lokal_id'].");";
}else{
	$pagemode = "resetTableDetail();";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
    $('select[name*=\"[cust_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Customer',
	});
    $('#". yii\bootstrap\Html::getInputId($model, 'cust_no_npwp') ."').inputmask({'mask': '99.999.999.9-999.999'});
    $('#". yii\bootstrap\Html::getInputId($model, 'no_faktur_pajak') ."').inputmask({'mask': '999.999-99.99999999'});
", yii\web\View::POS_READY); ?>
<script>
function setCustomer(){
	var cust_id = $('#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/setCustomer']); ?>',
        type   : 'POST',
        data   : {cust_id:cust_id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_alamat") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_no_npwp") ?>").val('');
			resetTableDetail();
			if(data.cust_id){
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_alamat") ?>").val(data.cust_an_alamat);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_no_npwp") ?>").val(data.cust_no_npwp);
			}
			if(data.dropdown_op){
				$("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").html(data.dropdown_op);
                $('select[name*=\"[op_ko_id]\"]').select2({
                    allowClear: !0,
                    placeholder: 'Ketik Kode OP',
                });
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function resetTableDetail(){
	$('#table-detail tbody').html('');
}

function addItem(){
	var op_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>').val();
    if(op_ko_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/addItem']); ?>',
            type   : 'POST',
            data   : {op_ko_id:op_ko_id},
            success: function (data) {
                if(data.item){
                    $(data.item).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                        $(this).find(".tooltips").tooltip({ delay: 50 });
                        reordertable('#table-detail');
                    });
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }else{
        cisAlert("OP harus diisi dulu");
    }
    
}

function setOpKo(){
    var op_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>').val();
    $("#<?= \yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val("");
    $("#table-detail > tbody").html("");
    resetTableDetail();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/setOpKo']); ?>',
        type   : 'POST',
        data   : {op_ko_id:op_ko_id},
        success: function (data) {
            if(data){
                $("#<?= \yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val(data.jenis_produk);
            }
            if(data.detail){
                $("#table-detail > tbody").html(data.detail);
                reordertable('#table-detail');
            }
            total();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function total(){
    var pcs = 0; var m3 = 0; var total = 0; var dpp = 0; var ppn = 0; var bayar = 0; var harga_nota =0; var harga_inv=0; var subtotal=0; var total_m3=0;
    var pph = unformatNumber( $("input[name*='total_pph']").val() );
    
    $("#table-detail > tbody > tr").each(function(i,v){
        m3 = unformatNumber( $(this).find("input[name*='[kubikasi]']").val() );
        harga_nota = unformatNumber( $(this).find("input[name*='[harga_nota]']").val() );
        if($("#<?= \yii\helpers\Html::getInputId($model, "include_ppn") ?>").is(":checked")){
            harga_inv = Math.ceil( harga_nota / 1.1 );
        }else{
            harga_inv = Math.round( harga_nota );
        }
        subtotal = Math.round( harga_inv * m3 );
        
        $(this).find("input[name*='[harga_invoice]']").val( formatNumberForUser(harga_inv) );
        $(this).find("input[name*='[subtotal]']").val( formatNumberForUser(subtotal) );
        
        pcs += unformatNumber( $(this).find("input[name*='[qty_kecil]']").val() );
        dpp += subtotal;
        total_m3 += unformatNumber( $(this).find("input[name*='[kubikasi]']").val() );
    });
    
    setTimeout(function(){
        ppn = Math.round( dpp * 0.1 );
        bayar = dpp + ppn - pph;
        $("input[name*='total_pcs']").val( pcs );
        $("input[name*='total_m3']").val( (Math.round( total_m3 * 10000 ) / 10000 ).toString() );
        $("input[name*='total_harga']").val( formatNumberForUser(dpp) );
        $("input[name*='total_ppn']").val( formatNumberForUser(ppn) );
        $("input[name*='total_bayar']").val( formatNumberForUser(bayar) );
    },500);
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
    var field2 = $("#<?= yii\bootstrap\Html::getInputId($model, "kode1") ?>");
    var field3 = $("#<?= yii\bootstrap\Html::getInputId($model, "kode4") ?>");
    var field4 = $("#<?= yii\bootstrap\Html::getInputId($model, "kode5") ?>");
    if( (!field2.val()) || (!field3.val()) || (!field4.val()) ){
        $(field2).parents('.form-group').addClass('error-tb-detail');
		has_error = has_error + 1;
    }else{
        if( field2.val() == "000" ){
            has_error = has_error + 1;
            $(field2).parents('.form-group').addClass('error-tb-detail');
        }else{
            $(field2).parents('.form-group').removeClass('error-tb-detail');
        }
    }
    
    $('#table-detail tbody > tr').each(function(){
        var field1 = $(this).find('input[name*="[nota_penjualan_id]"]');
        if(!field1.val()){
            $(this).find('input[name*="[nota_penjualan_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[nota_penjualan_id]"]').parents('td').removeClass('error-tb-detail');
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

function afterSave(id){
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    setCustomer();
    setTimeout(function(){
        $("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").empty().append('<option value="<?= $model->op_ko_id ?>"><?= (isset($model->op_ko_id)?$model->opKo->kode." - ".app\components\DeltaFormatter::formatDateTimeForUser2($model->opKo->tanggal):"") ?></option>').val('<?= (isset($model->op_ko_id)?$model->op_ko_id:"") ?>').trigger('change');
        <?php if(isset($_GET['edit'])){ ?>
            $("#<?= yii\helpers\Html::getInputId($model, "kode1") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "kode4") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "kode5") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "tanggal") ?>").prop("disabled",false);
            $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
            $("#<?= yii\helpers\Html::getInputId($model, "cust_id") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "cust_no_npwp") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "no_faktur_pajak") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "op_ko_id") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "penerbit") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "cara_bayar") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "mata_uang") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "include_ppn") ?>").prop("disabled",false);

            $('#btn-save').prop('disabled',false);
            $('#btn-print').prop('disabled',true);
            getItems(id,1);
        <?php }else{ ?>
            getItems(id);
            $('#btn-add-item').hide();
        <?php } ?>
    },500);
}

function getItems(invoice_lokal_id,edit=null){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/getItems']); ?>',
		type   : 'POST',
		data   : {invoice_lokal_id:invoice_lokal_id,edit:edit},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				reordertable('#table-detail');
                $("#table-detail > tbody > tr").find("input[name*='[nota_penjualan_id]']").each(function(){
                    if(edit){
                        $(this).prop("disabled",false);
                    }else{
                        $(this).prop("disabled",true);
                        $(this).parents("tr").find("a.btn.btn-xs.red").removeAttr('onclick').removeClass('red').addClass('grey');
                    }
                });
			}
			setTimeout(function(){
				total();
			},500);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function printInvoice(id){
	window.open("<?= yii\helpers\Url::toRoute('/finance/invoicelokal/printInvoice') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
</script>