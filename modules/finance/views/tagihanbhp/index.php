<?php
/* @var $this yii\web\View */
$this->title = 'Tagihan Pembayaran Bahan Pembantu';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
//app\assets\ConfirmationAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<style>
.tDnD_whileDrag td {
    background-color: #93979d;
    -webkit-box-shadow: 11px 5px 12px 2px #333, 0 1px 0 #ccc inset, 0 -1px 0 #ccc inset;
}

.custom-checkbox {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  width: 16px;
  height: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #f5f5f5;
  position: relative;
}

.custom-checkbox:checked {
  background-color: #4CAF50;
  border-color: #4CAF50;
}

.custom-checkbox:checked::after {
  content: '✔';
  color: white;
  font-size: 10px;
  position: absolute;
  top: 0px;
  left: 3px;
}
</style>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Penerimaan Nota Dari Supplier Bahan Pembantu'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
									<div class="col-md-12">
										<!-- BEGIN EXAMPLE TABLE PORTLET-->
										<div class="portlet light bordered form-search">
											<div class="portlet-title">
												<div class="tools panel-cari">
													<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
													<span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
												</div>
											</div>
											<div class="portlet-body">
												<?php $form = \yii\bootstrap\ActiveForm::begin([
													'id' => 'form-search-laporan',
													'fieldConfig' => [
														'template' => '{label}<div class="col-md-8">{input} {error}</div>',
														'labelOptions'=>['class'=>'col-md-3 control-label'],
													],
													'enableClientValidation'=>false
												]); ?>
												<div class="modal-body">
													<div class="row">
														<div class="col-md-6">
															<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode Pengajuan','model' => $model,'form'=>$form]) ?>
														</div>
														<div class="col-md-5">
															<?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionList(),['prompt'=>'All'])->label(Yii::t('app', 'Supplier')); ?>
														</div>
													</div>
													<div class="row" style="margin-top: -45px; margin-right: -30px;">
														<div class="col-md-1 pull-right" style="position: relative;">
															<?php echo \yii\helpers\Html::button( Yii::t('app', 'Search'),[
																'class'=>'btn hijau btn-outline ciptana-spin-btn pull-right',
																'type'=>'button',
																'name'=>'search-laporan',
																'onclick'=>'getItems()',
																]);
															?>
														</div>
													</div>
												</div>
												<?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
												<?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
												<?php \yii\bootstrap\ActiveForm::end(); ?>
											</div>
										</div>
										<!-- END EXAMPLE TABLE PORTLET-->
									</div>
								</div>
								<div class="row" style="margin-left: -30px; margin-right: -30px;">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail" style="width: 100%;">
												<thead>
													<tr class="nodrag">
														<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
														<th style="width: 80px; line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Pengajuan'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Kode Terima'); ?></th>
														<th style=""><?= Yii::t('app', 'Suplier'); ?></th>
														<th style="width: 80px; line-height: 1; "><?= Yii::t('app', 'Tanggal<br>Nota'); ?></th>
														<th style="width: 110px; line-height: 1; font-size: 1.1rem;"><?= Yii::t('app', 'Kelengkapan<br>Berkas'); ?></th>
														<th style="width: 100px; line-height: 1; font-size: 1.1rem;"><?= Yii::t('app', 'No.Nota /<br>No.Faktur'); ?></th>
														<th style="width: 100px; line-height: 1;"><?= Yii::t('app', 'Nomor<br>Kuitansi'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Nominal'); ?></th>
														<th style="width: 110px;"><?= Yii::t('app', 'Status'); ?></th>
														<th style="width: 90px;"><?= Yii::t('app', 'Lunas'); ?></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
												<tfoot>
													<tr class="nodrag">
														<td colspan="9" class="text-align-right">Total &nbsp;</td>
														<td class="td-kecil text-align-right" id="total_nominal"></td>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php $this->registerJs(" 
	formconfig(); 
	$(this).find('select[name*=\"[suplier_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Supplier',
		width: null
	});
	getItems();
", yii\web\View::POS_READY); ?>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/dnd/jquery.tablednd.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js",['depends'=>[yii\web\YiiAsset::className(), app\assets\MetronicAsset::className()]]) ?>
<script>
function getItems(){
	var form_params = $("#form-search-laporan").serialize();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/finance/tagihanbhp/getItems']); ?>',
		type   : 'POST',
		data   : {form_params:form_params},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}else{
				$('#table-detail > tbody').html("<tr><td colspan='11' style='font-size:1.2rem;'><center>Data tidak ditemukan</center></td></tr>");
			}
			reordertable('#table-detail');
			setTotal();
			$('#table-detail > tbody > tr').each(function(){
				setPopover(this);
			});
			$('#table-detail > tbody').find('input, textarea, button').attr('disabled','disabled');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setTotal(){
	var total = 0;
	$('#table-detail > tbody > tr').each(function (){
		total += unformatNumber( $(this).find('input[name*="[nominal]"]').val() );
	});
	$('#total_nominal').html( formatNumberForUser( total ) );
}

function updateStatus(ele,status,pengajuan_tagihan_id=null,alasantolak=null, open_voucher_id=null){
	$(ele).parents('tr').addClass('animation-loading');
	if(!pengajuan_tagihan_id){
		var pengajuan_tagihan_id = $(ele).parents("tr").find('input[name*="[pengajuan_tagihan_id]"]').val();
	}
    $.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/finance/tagihanbhp/UpdateStatus']); ?>',
		type   : 'POST',
		data   : { pengajuan_tagihan_id: pengajuan_tagihan_id, status:status, alasantolak:alasantolak, open_voucher_id:open_voucher_id},
		success: function (data) {
			$(ele).parents('tr').find('input[name*="[tanggal_nota]"]').val( $(ele).parents('tr').find('input[name*="[tanggal_nota]"]').val() );
			if(data.status){
				$(ele).parents('tr').removeClass('animation-loading');
				if(status=="DITOLAK"){
					$('#modal-transaksi').find('button.fa-close').trigger('click');
					ele = $('#table-detail').find("input[name*='[pengajuan_tagihan_id]'][value='"+pengajuan_tagihan_id+"']");
					$(ele).parents('tr').find("input[name*='[nominal]']").val("0");
					$(ele).parents('tr').find("#place-nominal").html( "<strike>"+$(ele).parents('tr').find("#place-nominal").text()+"</strike>" );
					setTotal();
					setStatus( ele, status, data.model.keterangan);
				}else{
					var status_old = $.trim($(ele).parents('tr').find("#place-status").text());
					if(status_old=="DITOLAK"){
						var nominal = unformatNumber( $.trim($(ele).parents('tr').find("#place-nominal").text()) );
						$(ele).parents('tr').find("input[name*='[nominal]']").val(nominal);
						$(ele).parents('tr').find("#place-nominal").html( $(ele).parents('tr').find("#place-nominal").text() );
						setTotal();
					}
					setStatus(ele,data.model.status, data.model.keterangan);
				}
			} 
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
    return false;
}

function setStatus(ele,value=null,ket){
	if(value){
		if(value=="DIAJUKAN"){
			value = '<a class="btn btn-xs btn-outline dark" title="Apakah akan menerima pengajuan ini?" data-toggle="konfirmasi" style="font-size:1.1rem;">BELUM DITERIMA</a>';
		}else if(value=="DITERIMA"){
			value = '<a class="btn btn-xs green-seagreen" title="'+ket+'<br><b>Batalkan Penerimaan?</b>" data-toggle="konfirmasibatal" style="font-size:1.1rem;">SUDAH DITERIMA</a>';
		}else if(value=="DITOLAK"){
			value = '<a class="btn btn-xs btn-outline red-flamingo" title="'+ket+'<br><b>Batalkan Penolakan?</b>" data-toggle="konfirmasibatal" style="font-size:1.1rem;">DITOLAK</a>';
		}
	}else{
		value = "-";
	}
	var newele = $(ele).parents("tr");
	$(ele).parents("tr").find("td#place-status").html(value);
	setPopover($(newele));
}

function setPopover(ele){
	$(ele).find("a[data-toggle='konfirmasi']").confirmation({
		placement: "left",
		title: "Terima Pengajuan Ini?",
		popout: 1,
		singleton: 1,
		btnOkClass: "btn btn-xs btn-outline green-seagreen",
		btnOkIcon: "icon-like",
		btnOkLabel: "Terima",
		btnCancelClass: "btn btn-xs btn-outline red-flamingo",
		btnCancelIcon: "icon-close",
		btnCancelLabel: "Tolak",
	});
	$(ele).find("a[data-toggle='konfirmasi']").on('confirmed.bs.confirmation', function() {
		if($(ele).find('input[name*="[lunas]"]').is(':checked')){
			openVoucher(this);
		} else {
			updateStatus(this,"DITERIMA");
		}
	});
	$(ele).find("a[data-toggle='konfirmasi']").on('canceled.bs.confirmation', function() {
		var pengajuan_tagihan_id = $(this).parents("tr").find("input[name*='[pengajuan_tagihan_id]']").val();
		openModal('<?= \yii\helpers\Url::toRoute(['/finance/tagihanbhp/alasanTolak','pengajuan_tagihan_id'=>''])?>'+pengajuan_tagihan_id,'modal-transaksi');
	});
	$(ele).find("a[data-toggle='konfirmasibatal']").confirmation({
		placement: "left",
		title: "Batalkan?",
		popout: 1,
		singleton: 1,
		btnOkClass: "btn btn-xs btn-outline red-flamingo",
		btnOkIcon: "icon-close",
		btnOkLabel: "Batalkan",
		btnCancelClass: "btn btn-xs btn-outline dark",
		btnCancelIcon: "",
		btnCancelLabel: "Close",
	});
	$(ele).find("a[data-toggle='konfirmasibatal']").on('confirmed.bs.confirmation', function() {
		updateStatus(this,"DIAJUKAN");
		if($(ele).find('input[name*="[lunas]"]').is(':checked')){
			$(ele).closest('tr').find('#place-open-voucher').html('');
			// console.log($(ele).closest('tr').find('#place-open-voucher').length);
		}
	});
//	$(ele).find("a[data-toggle='konfirmasibatal']").on('canceled.bs.confirmation', function() {
//		var pengajuan_tagihan_id = $(this).parents("tr").find("input[name*='[pengajuan_tagihan_id]']").val();
//		openModal('<?= \yii\helpers\Url::toRoute(['/finance/tagihanbhp/updateBerkas','pengajuan_tagihan_id'=>''])?>'+pengajuan_tagihan_id,'modal-transaksi');
//	});
}

function validatingDetail(ele){
    var has_error = 0;
	var field2 = $(ele).parents('tr').find('input[name*="[tanggal_nota]"]');
	if(!field2.val()){
		$(ele).parents('tr').find('input[name*="[tanggal_nota]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[tanggal_nota]"]').parents('td').removeClass('error-tb-detail');
	}
    if(has_error === 0){
        return true;
    }
    return false;
}

function infoTBP(terima_bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id,'modal-info-tbp','75%');
}
function infoKeterangan(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuantagihan/infoKeterangan']) ?>?id='+id,'modal-info-keterangan','50%');
}

var tr = null;
function openVoucher(ele){
	tr = ele;
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/tagihanbhp/openVoucher']) ?>','modal-openvoucher','70%');
}

function addOpenVoucher(id, kode){
	var pengajuan_tagihan_id = $(tr).find('input[name*="[pengajuan_tagihan_id]"]').val();
	var place_ov = "<a onclick='infoOpenVoucher("+id+")'>"+kode+"</a>";
	$(tr).closest('tr').find('#place-open-voucher').html(place_ov);
	$('#modal-openvoucher').modal('hide');
	updateStatus(tr, "DITERIMA", pengajuan_tagihan_id, null, id);
}

function infoOpenVoucher(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/tagihanbhp/infoOpenVoucher']) ?>?open_voucher_id='+id,'modal-info-ov','70%');
}
</script>