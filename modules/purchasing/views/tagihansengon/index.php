<?php
/* @var $this yii\web\View */
$this->title = 'Tagihan Log Sengon';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Tagihan Log Sengon'); ?></h1>
<!-- END PAGE TITLE -->
<!-- END PAGE HEADER -->
<!-- BEGIN EXAMPLE TABLE PORTLET -->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<?php
if(isset($_GET['tagihan_sengon_id'])){
	$display['none'] = 'none';
	$display['disabled'] = TRUE;
}else{
	$display['none'] = '';
	$display['disabled'] = FALSE;
}
?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Tagihan Sengon'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']); ?>
										<?= $form->field($model, 'tanggal_tagihan',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'getDetailItem()']); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'posengon_id')->dropDownList(\app\models\TPosengon::getOptionList(),['class'=>'form-control select2','prompt'=>'','onchange'=>'getDetailItem()']); ?>
										<?= $form->field($model, 'suplier_nm')->textInput(['disabled'=>'disabled'])->label('Supplier'); ?>
                                    </div>
                                </div>
								<br><br><hr>
								<div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Tagihan Sengon '); ?></h4>
                                    </div>
                                    <div class="col-md-7">
										
                                    </div>
                                </div>
								<div class="row">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover table-detail-mepet" style="width: 100%; overflow-x:auto;" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px;" rowspan="2">No.</th>
														<th style="width: 150px;"><?= Yii::t('app', 'Tgl.Datang / Menyetujui'); ?></th>
														<th style="width: 140px;"><?= Yii::t('app', 'Nopol / NPWP'); ?></th>
														<th style="width: 80px;"><?= Yii::t('app', 'Diameter'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Volume'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Harga'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Subtotal Harga'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'PPH 0.25%'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Subtotal Bayar'); ?></th>
														<th style="width: 75px;"></th>
													</tr>
												</thead>
												<tbody>

												</tbody>
												<tfoot>
													<tr>
														<td colspan="4" style="font-weight: bold; font-size:1.6rem; text-align: right; padding: 5px;">
															<u>Grand Total</u> &nbsp;
														</td>
														<td style="font-weight: bold; font-size:1.6rem; text-align: right; padding: 5px;">
															<div id="grandtotal-volume"></div>
														</td>
														<td style="font-weight: bold; font-size:1.6rem; text-align: right; padding: 5px;">
															<div id="grandtotal-harga"></div>
														</td>
														<td style="font-weight: bold; font-size:1.6rem; text-align: right; padding: 5px;">
															<div id="grandtotal-subtotalharga"></div>
														</td>
														<td style="font-weight: bold; font-size:1.6rem; text-align: right; padding: 5px;">
															<div id="grandtotal-pph"></div>
														</td>
														<td style="font-weight: bold; font-size:1.6rem; text-align: right; padding: 5px;">
															<div id="grandtotal-subtotalbayar"></div>
														</td>
													</tr>
													<tr>
														<td colspan="10">
															<!--<a class="btn btn-sm blue-hoki" id="btn-add-item" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>-->
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
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save()']); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['tagihan_sengon_id'])){
    $pagemode = "";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
	$(\".date-picker\").datepicker({
        rtl: App.isRTL(),
        orientation: \"left\",
        autoclose: !0,
        clearBtn:true,
        format: 'M-yyyy',
		startView: 'year', 
		minViewMode: 'months'
    });
	formconfig();
    $pagemode;
	$(this).find('select[name*=\"[posengon_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik nomor PO',
		width: null
	});
	getDetailItem();
", yii\web\View::POS_READY); ?>
<script>
function getDetailItem(){ 
	var posengon_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'posengon_id') ?>').val();
	var tanggal_tagihan = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_tagihan') ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/tagihansengon/getDetailItem']); ?>',
        type   : 'POST',
        data   : {posengon_id:posengon_id,tanggal_tagihan:tanggal_tagihan},
        success: function (data) {
			$(".datepicker").datepicker("disable");
			if(data.kode){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'kode') ?>').val(data.kode);
			}else{
				$('#<?= yii\bootstrap\Html::getInputId($model, 'kode') ?>').val("Auto Generate");
			}
			if(data.suplier_nm){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_nm') ?>').val(data.suplier_nm);
			} else {
				$('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_nm') ?>').val("");
			}
			$('#table-detail > tbody').html('');
			if(data.detail){
				$('#table-detail > tbody').append(data.detail);
				reordertablethis('#table-detail');
				$(".tooltips").tooltip({ delay: 50 });
				$('input[name*=\"[npwp]\"]').inputmask({'mask': '99.999.999.9-999.999'});
			}
			setTimeout(function(){
				$('#table-detail > tbody > tr').find('input[name*="[harga]"]').each(function(){
					setNominal($(this));
				});
				setTotal();
			},300);
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
	setAllButton();
}

function reordertablethis(obj_table){
    var row = 0;
    $(obj_table+' > tbody > tr').each(function(){
        $(this).find("#no_urut").val(row+1);
        $(this).find("span.no_urut").text(row+1);
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 3){
                $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]);
                $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]");
            }
        });
		$(this).find('table tr').each(function(){
			$(this).find('input,select,textarea').each(function(){ //element <input>
				var old_name = $(this).attr("name").replace(/]/g,"");
				var old_name_arr = old_name.split("[");
				if(old_name_arr.length == 4){
					$(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]+"_"+old_name_arr[3]);
					$(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]["+old_name_arr[3]+"]");
				}
			});
		});
		row++;
    });
    formconfig();
}

function setTotal(){
	var grandtotal_volume = 0;
	var grandtotal_subtotalharga = 0;
	var grandtotal_pph = 0;
	var grandtotal_subtotalbayar = 0;
	$('#table-detail > tbody > tr').each(function(){
		var totalharga = 0;
		var totalpph = 0;
		var totalbayar = 0;
		$(this).find('table tr').each(function(){
			var subtotal = 0;
			var range = $(this).find('input[name*="[range]"]').val();
			var volume = $(this).find('input[name*="[volume]"]').val();
			var harga = unformatNumber($(this).find('input[name*="[harga]"]').val());
			var subtotal = volume * harga;
			var pph = 0.0025 * subtotal;
			var bayar = subtotal - pph;
			if(volume){
				grandtotal_volume += parseFloat(volume);
			}
			if(subtotal){
				totalharga += parseInt(subtotal);
				grandtotal_subtotalharga += parseInt(subtotal);
			}
			if(pph){
				totalpph += parseInt(pph);
				grandtotal_pph += parseInt(pph);
			}
			if(bayar){
				totalbayar += parseInt(bayar);
				grandtotal_subtotalbayar += parseInt(bayar);
			}
		});	
		$(this).find('input[name*="[totalharga]"]').val(formatInteger(totalharga));
		$(this).find('input[name*="[totalpph]"]').val(formatInteger(totalpph));
		$(this).find('input[name*="[totalbayar]"]').val(formatInteger(totalbayar));
	});
	$('#grandtotal-volume').html("<u>"+ grandtotal_volume.toFixed(3) +' m<sup>3</sup>' +"</u>");
	$('#grandtotal-subtotalharga').html(formatInteger(grandtotal_subtotalharga));
	$('#grandtotal-pph').html(formatInteger(grandtotal_pph));
	$('#grandtotal-subtotalbayar').html(formatInteger(grandtotal_subtotalbayar));
}

function setNominal(ele_harga){
	var name = $(ele_harga).attr("name").replace(/]/g,"");
	var name = name.split("[");
	var tr = name[1];
	var range = name[2];
	var harga = unformatNumber($(ele_harga).val());
	var volume = unformatNumber($(ele_harga).parents('td').find('input[name*="['+tr+']['+range+'][volume]"]').val());
	var subtotal_harga = volume * harga;
	var pph = 0.0025 * subtotal_harga;
	var totalbayar = subtotal_harga - pph;
	$(ele_harga).parents('tr').find('input[name*="['+tr+']['+range+'][subtotal_harga]"]').val(formatInteger(subtotal_harga));
	$(ele_harga).parents('tr').find('input[name*="['+tr+']['+range+'][pph]"]').val(formatInteger(pph));
	$(ele_harga).parents('tr').find('input[name*="['+tr+']['+range+'][subtotal_bayar]"]').val(formatInteger(totalbayar));
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
    $('#table-detail > tbody > tr').each(function(){
        var field1 = $(this).find('input[name*="[tanggal_datang]"]');
        var field2 = $(this).find('input[name*="[nopol]"]');
        var field3 = $(this).find('select[name*="[disetujui]"]');
        var field4 = $(this).find('input[name*="[npwp]"]');
        if(!field1.val()){
            $(this).find('input[name*="[tanggal_datang]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[tanggal_datang]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            $(this).find('input[name*="[nopol]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[nopol]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field3.val()){
            $(this).find('select[name*="[disetujui]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('select[name*="[disetujui]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field4.val()){
            $(this).find('input[name*="[npwp]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[npwp]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function resetDelete(ele){
	var tagihan_sengon_id = $(ele).parents('tr').find('input[name*="[tagihan_sengon_id]"]').val();
	if(tagihan_sengon_id){
		openModal('"<?= \yii\helpers\Url::toRoute(['/purchasing/tagihansengon/delete','id'=>'']) ?>"'+tagihan_sengon_id,'modal-delete-record')
	}else{
		$(ele).parents('tr').find('#table-harga tr').each(function(){
			$(this).find('input[name*="[harga]"]').val(0);
			$(this).find('input[name*="[disetujui]"]').val(<?= \app\components\Params::DEFAULT_PEGAWAI_ID_PAK_WID ?>);
			$(this).find('input[name*="[npwp]"]').val('');
			setNominal($(this).find('input[name*="[harga]"]'));
		});
		setTotal();
	}
	reordertable('#table-detail');
}

function setAllButton(){
	var posengon_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'posengon_id') ?>').val();
	var tanggal_tagihan = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal_tagihan') ?>').val();
	if( posengon_id && tanggal_tagihan ){
		$('#btn-add-item').attr('onclick','getDetailItem()');
		$('#btn-add-item').removeAttr('disabled');
	}else{
		$('#btn-add-item').attr('disabled','disabled');  
		$('#btn-add-item').removeAttr('onclick');
	}
}

function hapusItem(ele){
	var tagihan_sengon_id = $(ele).parents('tr').find('input[name*="[tagihan_sengon_id]"]').val();
	$.ajax({
		url    : '<?= yii\helpers\Url::toRoute('/purchasing/tagihansengon/hapusItem') ?>',
		type   : 'POST',
		data   : {deleteRecord:true,tagihan_sengon_id:tagihan_sengon_id},
		success: function (data) {
			if(data.status){
				if(data.message){
                    cisAlert(data.message);
				}
				getDetailItem();
			}else{
				if(data.message){
                    if(data.message.errorInfo){
                        cisAlert(data.message.errorInfo[2]);
                    }else{
                        cisAlert(data.message);
                    }
				}
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

</script>