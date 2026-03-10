<?php
/* @var $this yii\web\View */

// use app\models\MDefaultValue;

$this->title = 'Realisasi Pemakaian Budget Bahan Pembantu';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
// app\assets\FileUploadAsset::register($this);
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
					<span class="pull-right" style="margin-left: 10px; margin-right: 10px;">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Realisasi Pemakaian'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered" style="border: solid 1px;">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>
										<?php
										if(!isset($_GET['pemakaian_bhpsub_id'])){
											echo "Realisasi Pemakaian Budget";
										}else{
											echo "Data Realisasi Pemakaian ";
											echo $model->kode ;
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
										<?php
										if(!isset($_GET['pemakaian_bhpsub_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
											
										}else{ ?>
											<div class="form-group">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%', 'readonly'=>true]) ?>
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
										<?= $form->field($model, 'departement_id')->dropDownList(\app\models\MDepartement::getOptionList2(),['class'=>'form-control select2','prompt'=>''])->label('Departement');; ?>
                                        
                                        
									</div>
									                                      
                                </div>
                                <hr>
                                <?php // DETAIL ORDER ;?>
                                <div class="row" id="detail-order" style="margin-top: -20px; margin-bottom: -20px;">
                                    <div class="col-md-12">
                                        <h5 style="font-weight: bold;"><?= Yii::t('app', 'Detail Realisasi Pemakaian'); ?></h5>
                                    </div>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.3rem;">No.</th>
														<th style="line-height: 0.9; padding: 5px; font-size: 1.1rem;"><?= Yii::t('app', 'Nama Item'); ?></th>														
                                                        <th style="line-height: 0.9;  padding: 5px; font-size: 1.1rem;"><?= Yii::t('app', 'Satuan'); ?></th>
														<th style="line-height: 0.9;  padding: 5px; font-size: 1.1rem;"><?= Yii::t('app', 'Plan'); ?></th>
														<th style="line-height: 0.9;  padding: 5px; font-size: 1.1rem;"><?= Yii::t('app', 'Target'); ?></th>
														<th style="line-height: 0.9;  padding: 5px; font-size: 1.1rem;"><?= Yii::t('app', 'Stock'); ?></th>
                                                        <th style="line-height: 0.9;  padding: 5px; font-size: 1.1rem;"><?= Yii::t('app', 'Qty'); ?></th>
                                                        <th style="line-height: 0.9;  padding: 5px; font-size: 1.1rem;"><?= Yii::t('app', 'Peruntukan<br>Departemen'); ?></th>                                                        
                                                        <th style="line-height: 0.9;  padding: 5px; font-size: 1.1rem;"><?= Yii::t('app', 'Peruntukan<br>Asset'); ?></th>
                                                        <th style="line-height: 0.9;  padding: 5px; font-size: 1.1rem;"><?= Yii::t('app', 'Refferensi<br>Kode Transaksi'); ?></th>
                                                        <th style="line-height: 0.9;  padding: 5px; font-size: 1.1rem;"><?= Yii::t('app', 'Keterangan'); ?></th>                                              
                                                        <th style="line-height: 0.9;  padding: 5px; font-size: 1.1rem;"></th> 
													</tr>
												</thead>
												<tbody>
													
												</tbody>
												<tfoot>
													<tr>
														<td colspan="6">
															<a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
														</td>
														<td style="vertical-align: middle; text-align: right;"></td>
														<td style="vertical-align: middle; text-align: right;"></td>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
                                
                                <hr>
		                        <div class="form-actions pull-right col-md-12 row">
		                            <div class="col-md-12 right">
										<div class="col-md-6"></div>
										<div class="col-md-6 pull-right pull-right">
		                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'save();']); ?>
										
										<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'printOP('.(isset($_GET['pemakaian_bhpsub_id'])?$_GET['pemakaian_bhpsub_id']:'').');','disabled'=>true]); ?>
		                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'resetForm();']); ?>
		                            	</div>
		                            </div>
		                        </div>
		                        <br>
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
if(isset($_GET['pemakaian_bhpsub_id'])){
    $pagemode = "afterSave(".$_GET['pemakaian_bhpsub_id'].");";
}else{
	$pagemode = "resetTableDetail(); "; //setVerify();
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	
", yii\web\View::POS_READY); ?>

<script>

function resetTableDetail(){
	$('#table-detail tbody').html('');
	addItem();
}


function masterProduk(ele){
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/itemInStock','disableAction'=>'']); ?>1&tr_seq='+tr_seq;
    $(".modals-place-3-min").load(url, function() {
		$("#modal-master-produk .modal-dialog").css('width','75%');
		$("#modal-master-produk").modal('show');
		$("#modal-master-produk").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}


function pickItem(bhp_id, tr_seq, data, reff_detail_id){
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/pickItem']); ?>',
        type   : 'POST',
        data   : {reff_detail_id:reff_detail_id,bhp_id:bhp_id},
        success: function (data) {
			if(data){
				var already = [];
				$('#table-detail > tbody > tr').each(function(){
					var reff_detail_id = $(this).find('input[name*="[terima_bhp_sub_id]"]');
					if( reff_detail_id.val() ){
						already.push(reff_detail_id.val());
					}
				});
				if( $.inArray(data.reff_detail_id.toString(), already ) != -1 ){ // Jika ada yang sama
					cisAlert("Item ini sudah dipilih di list");
					return false;
				}else{
					$("#modal-master-produk").find('button.fa-close').trigger('click');
					$("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("input[name*='[terima_bhp_sub_id]']").empty().val(data.reff_detail_id).trigger('change');
					$("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[bhp_id]']").empty().append('<option value="'+data.bhp_id+'">'+data.bhp_nm+'</option>').val(data.bhp_id).trigger('change');
					$("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("input[name*='[jumlah]']").empty().val(data.jumlah).trigger('change');
					console.log(data);
					// console.log(bhp_id);
					// console.log(reff_detail_id);
				}
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function addItem(){
	var terima_bhp_sub_id = $('#<?= yii\bootstrap\Html::getInputId($model, "terima_bhp_sub_id") ?>').val();
	var notin = [];
	
	$('#table-detail > tbody > tr').each(function(){
		var terima_bhp_sub_id = $(this).find('input[name*="[terima_bhp_sub_id]"]');
		if( terima_bhp_sub_id.val() ){
			notin.push(terima_bhp_sub_id.val());			
		}
	});
	// console.log(notin);
	if(notin){
		notin = JSON.stringify(notin);
	}    
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/addItem']); ?>',
        type   : 'POST',
        data   : {terima_bhp_sub_id:terima_bhp_sub_id},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                        $(this).find('select[name*="[bhp_id]"]').select2({
                            allowClear: !0,
							placeholder: 'Ketik Item',
                            width: '100%',
                            ajax: {
								url: '<?= \yii\helpers\Url::toRoute('/logistik/realisasibhp/findProdukActive') ?>',
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    var query = {
                                      term: params.term,
                                      type: bhp_id,
                                      notin: notin
                                    }
                                    return query;
                                },
                                processResults: function (data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: true
                            }
                        });
						$(this).find('.select2-selection').css('font-size','1.2rem');
						$(this).find('.select2-selection').css('padding-left','5px');
						$(this).find(".tooltips").tooltip({ delay: 50 });
						reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setItem(ele){
	// var bhp_id = $(ele).val();
	var terima_bhp_sub_id = $(ele).val();
	// console.log(" IIDIID "+terima_bhp_sub_id);
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/setItem']); ?>',
        type   : 'POST',
        data   : {terima_bhp_sub_id:terima_bhp_sub_id},
        success: function (data) {
			var row = $(ele).closest('tr');
			// console.log("Row:", row);
            // console.log("Server response:", data);
			$(ele).parents('tr').find('input[name*="[bhp_satuan]"]').val('');			
			$(ele).parents('tr').find('input[name*="[harga_peritem]"]').val('0');
			$(ele).parents('tr').find('input[name*="[qty"]').val('0');
            if(data.bhp){
				// $(ele).parents('tr').find('input[name*="[satuan]"]').val(data.bhp.bhp_satuan);
				// $(ele).parents('tr').find('input[name*="[harga_peritem]"]').val(data.bhp.bhp_harga);
				row.find('input[name*="[bhp_satuan]"]').val(data.bhp.bhp_satuan);
                row.find('input[name*="[harga_peritem]"]').val(data.bhp.harga_peritem);
				row.find('input[name*="[target_plan]"]').val(data.bhp.target_plan);
				row.find('input[name*="[target_peruntukan]"]').val(data.bhp.target_peruntukan);	
				// row.find('select[name*="[bhp_id]"]').val(data.bhp.bhp_nm);				
                console.log(data.bhp.bhp_nm);
            }			
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setInventaris(ele,dept_peruntukan=null){
	if(!dept_peruntukan){
		dept_peruntukan = $(ele).val();
	}
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/setInventaris']); ?>',
        type   : 'POST',
        data   : {dept_peruntukan:dept_peruntukan},
        success: function (data) {
            let inventarisDropdown = $(ele).closest('tr').find('select[name*="[asset_peruntukan]"]').select2({
							allowClear: !0,
							placeholder: '',
							dropdownAutoWidth : true,
						});
            inventarisDropdown.empty(); // Clear existing options
            inventarisDropdown.append('<option value=""></option>'); // Add prompt option

            if (data && data.length > 0) {
                $.each(data, function(index, item) {
                    inventarisDropdown.append(new Option(item.kode+" "+item.inventaris_nama, item.inventaris_id));
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function save() {
    
    var $form = $('#form-transaksi');
	var has_error = 0;
	
	<?php
	if (isset($_GET['edit'])) {
		$halaman = 'edit';
	} else if (isset($_GET['afterSave'])) {
		$halaman = 'aftersave';
	} else {
		$halaman = 'index';
	}
	?>
	
	if (validatingDetail()) {
		setTimeout(function(){
			if (has_error < 1) {
				submitform($form);
			}
			// console.log("1.5 detik");
		}, 1500);
	}
    return false;
}

function validatingDetail(){
    var has_error = 0;
    $('#table-detail tbody > tr').each(function(){
        var field1 = $(this).find('select[name*="[bhp_id]"]');
        var field2 = $(this).find('input[name*="[terima_bhp_sub_id]"]');
        var field3 = $(this).find('input[name*="[harga_peritem]"]');
        var field4 = $(this).find('input[name*="[qty]"]');
        var field5 = $(this).find('select[name*="[dept_peruntukan]"]');
		var qtyInput = $(this).find('input[name*="[qty]"]');
		var stockInput = $(this).find('input[name*="[jumlah]"]');
		var qtyValue = parseFloat(qtyInput.val());
		var stockValue = parseFloat(stockInput.val());
		var currenStockValue = stockValue - qtyValue;
		// console.log(stockValue);
		// console.log(qtyValue);
		// console.log(currenStockValue);

        if(!field1.val()){
            $(this).find('select[name*="[bhp_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('select[name*="[bhp_id]"]').parents('td').removeClass('error-tb-detail');
        }
		if(!field2.val()){
            $(this).find('input[name*="[terima_bhp_sub_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[terima_bhp_sub_id]"]').parents('td').removeClass('error-tb-detail');
        }
		if(!field3.val()){
            $(this).find('input[name*="[harga_peritem]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[harga_peritem]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field4.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[qty]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( $(this).find('input[name*="[qty]"]').val() == 0 ){
                has_error = has_error + 1;
                $(this).find('input[name*="[qty]"]').parents('td').addClass('error-tb-detail');
            }else{
				if (currenStockValue < 0 ){
					has_error = has_error + 1;
					$(this).find('input[name*="[qty]"]').parents('td').addClass('error-tb-detail');
				}else{
                	$(this).find('input[name*="[qty]"]').parents('td').removeClass('error-tb-detail');
				}
            }
        }
        if(!field5.val()){
            $(this).find('select[name*="[dept_peruntukan]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('select[name*="[dept_peruntukan]"]').parents('td').removeClass('error-tb-detail');
        }
    });
	// console.log(has_error);
	<?php if(isset($_GET['edit'])){ ?>
		has_error = 0;
	<?php } ?>
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
	<?php if(!isset($_GET['edit'])) { ?>
		getItems(id);
		$('#btn-add-item').hide();
		$('form').find('input').each(function(){ $(this).prop("disabled", true); });
	<?php } else { ?>
		getItems(id,1);
		// setVal();
	<?php } ?>
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'departement_id') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');

	<?php if(isset($_GET['edit'])){ ?>
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "departement_id") ?>').prop("disabled", false);
		
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
	<?php } ?>

}

function getItems(pemakaian_bhpsub_id,edit=null){
    
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/getItems']); ?>',
		type   : 'POST',
		data   : {pemakaian_bhpsub_id:pemakaian_bhpsub_id,edit:edit},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				if(edit){ // edit load item process
					var notin = [];
					$('#table-detail > tbody > tr').each(function(){
						var terima_bhp_sub_id = $(this).find('input[name*="[terima_bhp_sub_id]"]');
						if( terima_bhp_sub_id.val() ){
							notin.push(terima_bhp_sub_id.val());
						}
					});
					if(notin){
						notin = JSON.stringify(notin);
					}
					$('#table-detail tbody tr').each(function(){
						$(this).find('select[name*="[bhp_id]"]').select2({
							allowClear: !0,
							placeholder: 'Ketik kode item',
							width: '100%',
							ajax: {
								url: '<?= \yii\helpers\Url::toRoute('/logistik/realisasi/findProdukActive') ?>',
								dataType: 'json',
								delay: 250,
								data: function (params) {
									var query = {
									  term: params.term,
									  type: bhp_id,
									  notin: notin
									}
									return query;
								},
								processResults: function (data) {
									return {
										results: data
									};
								},
								cache: true
							}
						});
						$(this).find('.select2-selection').css('font-size','1.2rem');
						$(this).find('.select2-selection').css('padding-left','5px');
						$(this).find(".tooltips").tooltip({ delay: 50 });
						// $(this).find("input[name*='[harga_peritem]']").removeAttr("disabled");
						
					});
					reordertable('#table-detail');
				}
			}
			setTimeout(function(){
				// total();
			},500);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}


function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function cancelTransaksi(pemakaian_bhpsub_id){
	openModal('<?php echo \yii\helpers\Url::toRoute(['/logistik/realisasibhp/cancelTransaksi']) ?>?id='+pemakaian_bhpsub_id,'modal-transaksi');
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/index','pemakaian_bhpsub_id'=>'']); ?>'+id+'&edit=1');
}
function abortItem(id,pemakaian_bhpsub_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/abortItem','id'=>'']); ?>'+id+"&pemakaian_bhpsub_id="+pemakaian_bhpsub_id,'modal-transaksi');
} 
</script>