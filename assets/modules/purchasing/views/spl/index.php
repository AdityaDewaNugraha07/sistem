<?php
/* @var $this yii\web\View */
$this->title = 'Transaksi SPL';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Surat Pembelian Langsung (SPL)'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-spl',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/index"); ?>"> <?= Yii::t('app', 'PO Baru'); ?> </a>
                    </li>
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/podibuat"); ?>"> <?= Yii::t('app', 'PO Dibuat'); ?> </a>
                    </li>
					<li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/spl/index"); ?>"> <?= Yii::t('app', 'SPL Baru'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/index"); ?>"> <?= Yii::t('app', 'SPP Masuk'); ?> </a>
                    </li>
					<li class="">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/sppmasuk"); ?>"> <?= Yii::t('app', 'SPP Masuk Detail'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/dpbhp/index"); ?>"> <?= Yii::t('app', 'Downpayment'); ?> </a>
                    </li>
                </ul>
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarSpl()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Cari SPL'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Surat Pembelian Langsung'); ?></h4></span>
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
										if(!isset($_GET['spl_id'])){
											echo $form->field($model, 'spl_kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'spl_kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->spl_kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'spl_tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?php if(isset($_GET['spl_id'])){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<?php if($model->cancel_transaksi_id != null){ ?>
													<span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
													<?php }else{ ?>
														<a href="javascript:void(0);" onclick="cancelSpl(<?= $model->spl_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan SPL'); ?></a>
													<?php } ?>
												</div>
											</div>
										<?php } ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'spl_disetujui')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control select2','prompt'=>'']); ?>
                                    </div>
                                </div>
                                <br><br><hr>
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Surat Pembelian Langsung'); ?></h4>
                                    </div>
                                    <div class="col-md-7">
										<?php if(!isset($_GET['spl_id'])){ ?>
										<!--<a class="btn btn-sm blue-hoki pull-right" data-url="<?php // echo \yii\helpers\Url::toRoute('/purchasing/spl/pickPanel') ?>" onclick="openPickPanel(this)"><i class="fa fa-arrow-circle-left"></i><?= Yii::t('app', ' Add From SPP'); ?></a>-->
										<?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
                                                        <th style="vertical-align: middle; text-align: center; width: 300px;" ><?= Yii::t('app', 'Nama Item'); ?></th>
                                                        <th style="text-align: center;  vertical-align: middle; width: 50px;"><?= Yii::t('app', 'Qty'); ?></th>
                                                        <th style="width: 40px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Sat'); ?></th>
                                                        <th style="width: 90px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Estimasi<br>Harga Satuan'); ?></th>
                                                        <th style="width: 100px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Sub Total'); ?></th>
                                                        <th style="vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
                                                        <th style="vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Supplier'); ?></th>
														<?php if(isset($_GET['spl_id'])){ ?>
                                                        <th style="vertical-align: middle; text-align: center;"><?= Yii::t('app', 'TBP'); ?></th>
														<?php } ?>
                                                        <th style="width: 30px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Cancel'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
												<tfoot>
													<tr>
														<td colspan="5" style="text-align: right">&nbsp; <i>Total</i></td>
														<td style="padding-left: 8px; padding-right: 8px;">
															<?= yii\bootstrap\Html::textInput('total',0,['class'=>'form-control','style'=>'width:100%; font-style: bold; padding:3px; text-align:center;','readonly'=>'readonly','id'=>'total']); ?>
														</td>
														<td colspan="2"></td>
													</tr>
													<tr>
														<td colspan="6">
															<!--<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>-->
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
								<?php 
									if(isset($_GET['spl_id'])){
										$disabled = FALSE;
									}else{
										$disabled = TRUE;
									}
								?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>(($disabled==FALSE)? 'printout('.(isset($_GET['spl_id'])?$_GET['spl_id']:'').')' :''),'disabled'=>$disabled]); ?>
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
if(isset($_GET['spl_id'])){
    $pagemode = "afterSave()";
}else{
    $pagemode = "setDetail();";
}
?>
<?php $this->registerJs(" 
formconfig();
$pagemode;
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Bahan Pembantu'))."');
", yii\web\View::POS_READY); ?>
<script>
function setDetail(){
	$('#table-detail').addClass('animation-loading');
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/spl/setDetail']); ?>',
		type   : 'POST',
		data   : {},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail tbody').html(data.html);
				reordertable('#table-detail');
				setSubtotal();
			}
			$('#table-detail').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function reordertable(obj_table){
    var row = 0;
    $(obj_table).find("tbody > tr").each(function(){
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
        row++;
    });
    formconfig();
}

function setSubtotal(ele){
	var total = 0;
	$('#table-detail tbody tr').each(function(index){
		var qty = unformatNumber($(this).find('input[name*="[spld_qty]"]').val());
		var harga = unformatNumber($(this).find('input[name*="[spld_harga_estimasi]"]').val());
		var subtotal = $(this).find('input[name*="[subtotal]"]').val( formatInteger(qty*harga) );
		total += unformatNumber($(this).find('input[name*="[subtotal]"]').val());
	});
	total = formatInteger(total);
	setTimeout(function(){
		$('#total').val(total);
	},500);
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
		setSubtotal();
        reordertable('#table-detail');
    });
}

function save(){
    var $form = $('#form-spl');
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
    $('#table-detail tbody > tr').each(function(){
        var field1 = $(this).find('input[name*="[bhp_id]"]');
        var field2 = $(this).find('input[name*="[spld_qty]"]');
        if(!field1.val()){
            $(this).find('input[name*="[bhp_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[bhp_id]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[spld_qty]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( $(this).find('input[name*="[spld_qty]"]').val() == 0 ){
                has_error = has_error + 1;
                $(this).find('input[name*="[spld_qty]"]').parents('td').addClass('error-tb-detail');
            }else{
                $(this).find('input[name*="[spld_qty]"]').parents('td').removeClass('error-tb-detail');
            }
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    getItemsBySpl(id);
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tspl-spl_tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
	setTimeout(function(){
		setSubtotal();
	},1000);
}

function getItemsBySpl(){
    $('#table-detail').addClass('animation-loading');
    var spl_id = '<?= (isset($_GET['spl_id'])?$_GET['spl_id']:'') ?>';
    var html = "";
    if(spl_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/spl/GetItemsBySpl']); ?>',
            type   : 'POST',
            data   : {spl_id:spl_id},
            success: function (data) {
                if(data){
                    html = data.html;
                    $('#table-detail tbody').html(html);
                    $('#table-detail').removeClass('animation-loading');
                    reordertable('#table-detail');
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }else{
        html = "<tr><td colspan='7'><center><i>Data tidak ditemukan</i></center></td></tr>"
        $('#table-detail tbody').html(html);
        $('#table-detail').removeClass('animation-loading');
    }
}

function daftarSpl(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/spl/daftarSpl']) ?>','modal-daftar-spl','75%');
}

function addItem(){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/spl/addItem']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                    setDropdownBhp($(this));
                    $(this).find('select[name*="[bhp_id]"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik nama item',
                        width: null
                    });
                    reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setDropdownBhp(obj){
    var selected_items = [];
    $('#table-detail tbody tr').each(function(){
        var bhp_id = $(this).find('select[name*="[bhp_id]"]').val();
        if(bhp_id){
            selected_items.push(bhp_id);
        }
    });
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/spl/setDropdownBhp']); ?>',
		type   : 'POST',
		data   : {selected_items:selected_items},
		success: function (data) {
			$(obj).find('select[name*="[bhp_id]"]').html(data.html);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setItem(obj,bhp_id,qty=null,harga=null){
	var from_panel = false;
	if(obj){
		var bhp_id = $(obj).val();
	}else{
		obj = $('#table-detail tbody tr:last').find('#no_urut');
		from_panel = true;
	}
	
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/spl/setItem']); ?>',
        type   : 'POST',
        data   : {bhp_id:bhp_id},
        success: function (data) {
            if(data){
				if(from_panel){
					$(obj).parents('tr').find('select[name*="[bhp_id]"]').val(bhp_id).trigger("change"); 
				}else{
					$(obj).parents('tr').find('input[name*="[spld_qty]"]').val(0);
					$(obj).parents('tr').find('input[name*="[spld_harga_estimasi]"]').val(0);
					$(obj).parents('tr').find('input[name*="[subtotal]"]').val(0);
				}
                $(obj).parents('tr').find('span.satuan').text(data.bhp_satuan);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function printout(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/spl/printSpl') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function cancelSpl(spl_id){
	openModal('<?php echo \yii\helpers\Url::toRoute(['/purchasing/spl/cancelSpl']) ?>?id='+spl_id,'modal-transaksi');
}

function infoTBP(terima_bhp_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id+'&bhp_id='+bhp_id,'modal-info-tbp','75%');
}

function infoSPP(spp_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpp']) ?>?id='+spp_id+'&bhp_id='+bhp_id,'modal-info-spp','60%');
}
</script>