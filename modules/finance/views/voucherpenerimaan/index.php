<?php
/* @var $this yii\web\View */
$this->title = 'Voucher Penerimaan';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Voucher Penerimaan'); ?></h1>
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
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Voucher Penerimaan'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
                                    <div class="col-md-5">
										<?= $form->field($model, 'tanggal',[
											'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
											<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
											{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'getItems()'])->label("Tanggal Voucher"); ?>
									</div>
									<div class="col-md-5 pull-right">
										<label class="col-md-7 control-label"><?= Yii::t('app', 'Kurs Tengah'); ?></label>
										<span class="input-group-btn" style="width: 100%">
											<?= \yii\bootstrap\Html::activeTextInput($modKurs, 'usd', ['class'=>'form-control float','disabled'=>'disabled']); ?>
										</span>
										<span class="input-group-btn">
											<a id="place-editkurs" class="btn btn-icon-only btn-default tooltips" onclick="editKurs();" data-original-title='Edit Kurs' style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-edit"></i></a>
											<a id="place-savekurs" class="btn btn-icon-only btn-default tooltips" onclick="saveKurs();" data-original-title='Save Kurs' style="margin-left: 3px; border-radius: 4px; display: none;"><i class="fa fa-check"></i></a>
										</span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
														<th style="width: 80px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Kode<br>Voucher / BBM'); ?></th>
														<th style="width: 90px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Jenis<br>Voucher'); ?></th>
														<th style="width: 60px; font-size: 1.1rem;  line-height: 1"><?= Yii::t('app', 'Mata<br>Uang'); ?></th>
														<th style="width: 120px; font-size: 1.1rem;  line-height: 1"><?= Yii::t('app', 'Akun<br>Kredit'); ?></th>
														<th style="width: 150px; font-size: 1.1rem;  line-height: 1"><?= Yii::t('app', 'Sender'); ?></th>
														<th style="font-size: 1.2rem;"><?= Yii::t('app', 'Deskripsi'); ?></th>
														<th style="width: 100px; font-size: 1.1rem;  line-height: 1"><?= Yii::t('app', 'Nominal'); ?></th>
														<th style="width: 80px; font-size: 1.1rem; text-align: center;"><?= Yii::t('app', 'Nota /<br>Kuitansi'); ?></th>
														<th style="width: 80px; font-size: 1.1rem; text-align: center;"><?= Yii::t('app', 'Action'); ?></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td colspan="9" class="text-align-center" style="font-size: 1.2rem"><i>Data Tidak Ditemukan</i></td>
													</tr>
												</tbody>
												<tfoot>
													<tr id="place-total_idr" style="display: none;">
														<td colspan="7" class="text-align-right">Total IDR &nbsp;</td>
														<td class="td-kecil text-align-right"><?php echo yii\bootstrap\Html::textInput('total_idr',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'width: 100px; padding:3px; font-weight:600; font-size:1.2rem']) ?></td>
													</tr>
													<tr id="place-total_usd" style="display: none;">
														<td colspan="7" class="text-align-right">Total USD &nbsp;</td>
														<td class="td-kecil text-align-right"><?php echo yii\bootstrap\Html::textInput('total_usd',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'width: 100px; padding:3px; font-weight:600; font-size:1.2rem']) ?></td>
													</tr>
													<tr>
														<td colspan="9">
															<div class="col-md-2" id="btn-additem-place"></div>
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
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-save','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printout();']); ?>
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
if(isset($_GET['voucher_penerimaan_id'])){
    $pagemode = "afterSave(".$_GET['voucher_penerimaan_id'].")";
}else {
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	getItems();
	setBtn();
", yii\web\View::POS_READY); ?>
<script>
function getItems(){
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/getItems']); ?>',
		type   : 'POST',
		data   : {tgl:tgl},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}else{
				$('#table-detail > tbody').html("<tr><td style='font-size:1.1rem;' colspan='10'><center><i>Tidak ditemukan data penerimaan</i></center></td></tr>");
			}
			setKurs();
			setTotal();
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setKurs(){
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	$("#place-editkurs").attr('style',"display:");
	$("#place-savekurs").attr('style',"display:none");
	$('#<?= yii\bootstrap\Html::getInputId($modKurs, 'usd') ?>').attr("disabled","disabled");
	$('#<?= yii\bootstrap\Html::getInputId($modKurs, 'usd') ?>').addClass("animation-loading");
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/setKurs']); ?>',
		type   : 'POST',
		data   : {tgl:tgl},
		success: function (data) {
			$('#<?= yii\bootstrap\Html::getInputId($modKurs, 'usd') ?>').val('0');
			if(data.kurs){
				$('#<?= yii\bootstrap\Html::getInputId($modKurs, 'usd') ?>').val( formatNumberForUser(data.kurs) );
			}
			$('#<?= yii\bootstrap\Html::getInputId($modKurs, 'usd') ?>').removeClass("animation-loading");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function editKurs(){
	$("#place-editkurs").attr('style',"display:none");
	$("#place-savekurs").attr('style',"display:");
	$('#<?= yii\bootstrap\Html::getInputId($modKurs, 'usd') ?>').removeAttr("disabled");
}
function saveKurs(){
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	var usd = unformatNumber( $('#<?= yii\bootstrap\Html::getInputId($modKurs, 'usd') ?>').val() );
	$('#<?= yii\bootstrap\Html::getInputId($modKurs, 'usd') ?>').addClass("animation-loading");
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/saveKurs']); ?>',
		type   : 'POST',
		data   : {tgl:tgl,usd:usd},
		success: function (data) {
			setKurs();
			$('#<?= yii\bootstrap\Html::getInputId($modKurs, 'usd') ?>').removeClass("animation-loading");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function addItem(){
	// Check Closing
	var usd = unformatNumber( $('#<?= yii\bootstrap\Html::getInputId($modKurs, 'usd') ?>').val() );
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/addItem']); ?>',
		type   : 'POST',
		data   : {tgl:tgl,usd:usd},
		success: function (data) {
			if(data.html){
				if($('#table-detail > tbody #no_urut').length == 0){
					$('#table-detail > tbody').html("");
				}
				$('#table-detail > tbody').append(data.html);
			}
			reordertable('#table-detail');
			setBtn();
			setTotal();
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setBtn(){ 
	var btnadd = '<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Penerimaan'); ?></a>';
	$('#btn-additem-place').html(btnadd);
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
		setBtn();
		setTotal();
    });
}

function setTotal(){
	var total_idr = 0;
	var total_usd = 0;
	$("#place-total_idr").attr("style","display:none");
	$("#place-total_usd").attr("style","display:none");
	$('#table-detail > tbody > tr').each(function (){
		if($(this).find("select[name*='[mata_uang]']").val() == "IDR"){
			total_idr += unformatNumber( $(this).find('input[name*="[total_nominal]"]').val() );
		}else{
			total_usd += unformatNumber( $(this).find('input[name*="[total_nominal]"]').val() );
		}
	});
	if(total_idr > 0){
		$('input[name="total_idr"]').val( formatNumberForUser( total_idr ) );
		$("#place-total_idr").attr("style","display:");
	}
	if(total_usd > 0){
		$('input[name="total_usd"]').val( formatNumberForUser( total_usd ) );
		$("#place-total_usd").attr("style","display:");
	}
}


function save(ele){
	$(ele).parents('tr').addClass("animation-loading");
    var $form = $('#form-transaksi');
	if(formrequiredvalidate($form)){
        if(validatingDetail(ele)){
            $(ele).parents('tr').find('input[name*="[total_nominal]"]').val( unformatNumber($(ele).parents('tr').find('input[name*="[total_nominal]"]').val()) );
			$.ajax({
				url    : '<?php echo \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/index']); ?>',
				type   : 'POST',
				data   : { formData: $(ele).parents('tr').find('input, textarea, select').serialize() },
				success: function (data) {
					$(ele).parents('tr').find('input[name*="[total_nominal]"]').val( formatNumberForUser($(ele).parents('tr').find('input[name*="[total_nominal]"]').val()) );
					if(data.status){
						getItems();
					}
					reordertable('#table-detail');
					$(ele).parents('tr').removeClass("animation-loading");
				},
				error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
			});
        }else{
			$(ele).parents('tr').removeClass("animation-loading");
		}
    }
    return false;
}
function validatingDetail(ele){
    var has_error = 0;
	var field1 = $(ele).parents("tr").find("input[name*='[total_nominal]']");
	var field2 = $(ele).parents("tr").find("textarea[name*='[deskripsi]']");
	var field3 = $(ele).parents("tr").find("select[name*='[akun_kredit]']");
	var field4 = $(ele).parents("tr").find("input[name*='[sender]']");
	$(field1).removeClass("error-tb-detail");
	$(field2).removeClass("error-tb-detail");
	$(field3).removeClass("error-tb-detail");
	$(field4).removeClass("error-tb-detail");
	
	if(!field1.val()){
		$(field1).addClass("error-tb-detail");
		has_error = has_error + 1;
	}else{
		if(unformatNumber($(field1).val())==0){
			$(field1).addClass("error-tb-detail");
			has_error = has_error + 1;
		}
	}
	if(!field2.val()){
		$(field2).addClass("error-tb-detail");
		has_error = has_error + 1;
	}
	if(!field3.val()){
		$(field3).addClass("error-tb-detail");
		has_error = has_error + 1;
	}
	if(!field4.val()){
		$(field4).addClass("error-tb-detail");
		has_error = has_error + 1;
	}
    if(has_error === 0){
        return true;
    }
    return false;
}

function edit(ele){
	$(ele).parents('tr').find('input, textarea, select').removeAttr('disabled');
	$(ele).parents('tr').find('#place-nota').attr('style','display:none');
	$(ele).parents('tr').find('#place-editnotakui').attr('style','display:');
	$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
	$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
}
function deleteItem(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/deleteItem','id'=>''])?>'+id,'modal-delete-record');
}

function detailBbm(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/detailBbm']) ?>?id='+id,'modal-bbm','21cm');
}

function printBbm(id){
	window.open("<?= yii\helpers\Url::toRoute('/finance/voucherpenerimaan/printBbm') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function printout(caraPrint,tgl){
	var caraPrint = "PRINT";
	var tgl = $("#<?= yii\bootstrap\Html::getInputId($model, "tanggal") ?>").val();
	window.open("<?= yii\helpers\Url::toRoute('/finance/voucherpenerimaan/PrintoutLaporan') ?>?tgl="+tgl+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function infoPiutang(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/infoPiutang']) ?>?id='+id,'modal-info');
}

function infoNota(kode){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/infoNota']); ?>?kode='+kode;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-nota .modal-dialog").css('width','21.5cm');
		$("#modal-info-nota").modal('show');
		$("#modal-info-nota").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pickPanelNota(ele){
	var eleid = $(ele).parents("td").find('input').attr('id');
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/pickPanelNota']) ?>?eleid='+eleid,'modal-nota','75%');
}
function pickingNota(nota_penjualan_id,kode){
	var eleid = $('#eleid').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/pickNota']); ?>',
		type   : 'POST',
		data   : {nota_penjualan_id:nota_penjualan_id,kode:kode},
		success: function (data) {
			if(data){
				$('#'+eleid).parents('tr').find('input[name*="[nota_penjualan_id]"]').val( nota_penjualan_id );
				$('#'+eleid).parents('tr').find('input[name*="[sender]"]').val(data.sender);
				$('#'+eleid).parents('tr').find('textarea[name*="[deskripsi]"]').val(data.deskripsivoucher);
				$('#'+eleid).parents('tr').find('input[name*="[total_nominal]"]').val( formatNumber(data.nominal) );
			}
			$("#modal-nota").find('button.fa-close').trigger('click');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function createKuitansi(ele){
	var voucher_penerimaan_id = $(ele).parents('tr').find('input[name*="[voucher_penerimaan_id]"]').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/createKuitansi']) ?>?reff_id='+voucher_penerimaan_id+'&cara_bayar=Transfer','modal-transaksi','75%');
}
function infoKuitansi(kuitansi_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/infoKuitansi']); ?>?kuitansi_id='+kuitansi_id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-kuitansi .modal-dialog").css('width','21.5cm');
		$("#modal-info-kuitansi").modal('show');
		$("#modal-info-kuitansi").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function editKuitansi(ele){
	var voucher_penerimaan_id = $(ele).parents('tr').find('input[name*="[voucher_penerimaan_id]"]').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/createKuitansi']) ?>?reff_id='+voucher_penerimaan_id+'&cara_bayar=Transfer&edit=1','modal-transaksi','75%');
}
</script>