<?php
/* @var $this yii\web\View */
$this->title = 'Purchase Order Log Sengon/Log Jabon';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\RepeaterAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Purchase Order Log Sengon/Log Jabon'); ?></h1>
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
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject"><h4><?= Yii::t('app', 'Rencana Purchase Order Log Sengon/Log Jabon'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'kode')->dropDownList( \app\models\TPosengonRencana::getOptionList() ,['style'=>'font-weight:bold','onchange'=>'setValue(); getItems();'])->label("Kode Rencana"); ?>
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, "pmr_id") ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Permintaan'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 5px;">
                                                <span class="input-group-btn" style="width: 95%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode_permintaan', ['class'=>'form-control','style'=>'width:100%','disabled'=>true,'placeholder'=>'Cari Permintaan Pembelian Log Sengon/Log Jabon']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 5%">
                                                    <button class="btn btn-icon-only btn-default tooltips" data-original-title="Cari Permintaan" onclick="openPermintaan();" type="button">
                                                        <i class="icon-magnifier"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <?= $form->field($model, 'tanggal')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Tanggal Buat"); ?>
                                    </div>
                                    <div class="col-md-6">
										<div class="form-group">
											<label class="col-md-4 control-label">Periode Pengiriman</label>
                                            <div class="col-md-7" style="margin-bottom: 7px; ">
												<span class="input-group-btn" style="width: 47.5%">
													<?= $form->field($model, 'tanggal_pengiriman_awal',[
																'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
																			 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																			 </div>'])->textInput(['readonly'=>'readonly','onchange'=>'setLabelPeriode()']); ?>
												</span>
												<span class="input-group-addon textarea-addon" style="width: 5%; background-color: #fff; border: 0;"> sd </span>
												<span class="input-group-btn" style="width: 47.5%">
													<?= $form->field($model, 'tanggal_pengiriman_akhir',[
																'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
																			 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																			 </div>'])->textInput(['readonly'=>'readonly','onchange'=>'setLabelPeriode()']); ?>
												</span>
											</div>
										</div>
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, "menyetujui") ?>
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, "status_approval") ?>
                                        <?= $form->field($model, 'menyetujui_display')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Approval Assigne to<br><b style='font-size:1rem;'>Kadept Purchasing BP</b>"); ?>
                                        <div class="form-group" >
											<label class="col-md-4 control-label">Approval Status</label>
                                            <div class="col-md-7" style="margin-top: 5px;" id="place-approvalstatus">-</div>
										</div>
                                    </div>
                                </div>
                                <br>
                                <div class="row pull-right">
                                    <div class="col-md-12 pull-right">
                                        <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save()']); ?>
                                        <?php // echo \yii\helpers\Html::button( Yii::t('app', 'Print Rencana'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','disabled'=>true,'onclick'=>'printout()']); ?>
                                        <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                                    </div>
                                </div>
                                <br><br><hr>    
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Tabel Perencanaan Pembelian (Breakdown By Supplier) Pada Periode : '); ?><span id="place-periode"></span></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                            <thead>
                                                <?php $ukuranganrange = \app\models\MDefaultValue::getOptionList('log-sengon-panjang'); ?>
                                                <tr>
                                                    <th style="width: 30px;" rowspan="2" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                                    <th style="width: 300px;" rowspan="2"><?= Yii::t('app', 'Supplier'); ?></th>
                                                    <th colspan="<?= count($ukuranganrange)+1 ?>"><?= Yii::t('app', 'Qty M<sup>3</sup> By Panjang Log'); ?></th>
                                                    <th style="line-height: 1" rowspan="2"><?= Yii::t('app', 'Harga By Diameter Range'); ?></th>
                                                    <th style="width: 100px; line-height: 1" rowspan="2"><?= Yii::t('app', 'Print PO'); ?></th>
                                                    <th rowspan="2" style="width: 75px;"><a style="display: none;" id="btn-modify" onclick="editPOConfirm();" class="btn blue-steel btn-outline btn-xs tooltips" data-original-title="Edit Data Setelah Confirm"><i class="fa fa-edit"></i> Modify</a></th>
                                                </tr>
                                                <tr>
                                                    <?php foreach($ukuranganrange as $i => $range){ ?>
                                                    <th style="width: 60px;"><?= $range ?> cm</th>
                                                    <?php } ?>
                                                    <th style="width: 80px;">Subtotal M<sup>3</sup></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="empty-tr"><td colspan="<?= count($ukuranganrange)+5 ?>" style="font-size: 1.1rem; text-align: center;"><i>Data Not Found</i></td></tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" style="text-align: right;">Total M<sup>3</sup>&nbsp; </td>
                                                    <?php foreach($ukuranganrange as $i => $range){ ?>
                                                    <td><?= yii\helpers\Html::textInput("TPosengonRencana[".$range."][total_m3]",0,["class"=>'form-control float col-m3-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                    <?php } ?>
                                                    <td><?= yii\helpers\Html::textInput("TPosengonRencana[total][total_m3]",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align: right;">Total Percent&nbsp; </td>
                                                    <?php foreach($ukuranganrange as $i => $range){ ?>
                                                    <td><?= yii\helpers\Html::textInput("TPosengonRencana[".$range."][total_persen]",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                    <?php } ?>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <a class="btn btn-xs blue" id="btn-add-item" onclick="addItem()" style="display: none;"><i class="fa fa-plus"></i> Add Item</a>
                                    </div>
                                </div><br>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                
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
if(isset($_GET['posengon_rencana_id'])){
    $pagemode = "afterSave(); setValue();";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
	$('.date-picker').datepicker({ clearBtn:false });
	formconfig();
    $pagemode;
	$(this).find('select[name*=\"[disetujui_direktur]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Pegawai',
		width: null
	});
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('PO Sengon'))."');
", yii\web\View::POS_READY); ?>
<script>
function setLabelPeriode(){
    var awal = $("#<?= \yii\helpers\Html::getInputId($model, 'tanggal_pengiriman_awal') ?>").val();
    var akhir = $("#<?= \yii\helpers\Html::getInputId($model, 'tanggal_pengiriman_akhir') ?>").val();
    if(awal && akhir){
        $('#place-periode').html( awal+" sd "+akhir );
    }else{
        $('#place-periode').html(" - ");
    }
}
function openPermintaan(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/openpermintaanlog']) ?>','modal-permintaanlog','90%');
}
function pick(kode){
	$("#modal-permintaanlog").find('button.fa-close').trigger('click');
    $("#<?= \yii\helpers\Html::getInputId($model, "kode_permintaan") ?>").val("");
    $("#<?= \yii\helpers\Html::getInputId($model, "pmr_id") ?>").val("");
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/pick']); ?>',
        type   : 'POST',
        data   : {kode:kode},
        success: function (data){
            if(data){
                $("#<?= \yii\helpers\Html::getInputId($model, "kode_permintaan") ?>").val(data.kode+" ("+formatDateForUser2(data.tanggal_dibutuhkan_awal)+" - "+formatDateForUser2(data.tanggal_dibutuhkan_akhir)+")");
                $("#<?= \yii\helpers\Html::getInputId($model, "pmr_id") ?>").val(data.pmr_id);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function getItems(){
	var id = $("#<?= \yii\helpers\Html::getInputId($model, "kode") ?>").val();
    
    $('#table-detail > tbody').html('<tr id="empty-tr"><td colspan="<?= count($ukuranganrange)+5 ?>" style="font-size: 1.1rem; text-align: center;"><i>Data Not Found</i></td></tr>');
    reordertable("#table-detail");
    total();
    
    if(id != "AUTO GENERATE"){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/getItems']); ?>',
            type   : 'POST',
            data   : {id:id},
            success: function (data){
                if(data.html){
                    $('#table-detail > tbody').html(data.html);
                    $('#table-detail > tbody').find("input,select,textarea").prop("disabled",true);
                    $('#table-detail > tbody').find('.input-group-btn > button').prop('disabled', true);
                    $('#table-detail > tbody > tr').each(function(){
                        $(this).find(".tooltips").tooltip({ delay: 50 });
                    });
                    formconfig();
                    reordertable("#table-detail");
                    total();
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}
function addItem(){
    $("#empty-tr").remove();
    var allowadd = true;
    $('#table-detail > tbody > tr').each(function(){
        if(! $(this).find('input[name*="[posengon_id]"]').val() ){
            savePO($(this).find('#btn-save-po'),function(){ setTimeout(function(){ addItem(); },600) });
            allowadd &= false;
            return false;
        }else{
            allowadd &= true;
        }
    });
    if(allowadd){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/addItem']); ?>',
            type   : 'POST',
            data   : {},
            success: function (data){
                if(data.html){
                    $(data.html).hide().appendTo('#table-detail > tbody').fadeIn(100,function(){
                        $(this).find(".tooltips").tooltip({ delay: 50 });
                        formconfig(); reordertable("#table-detail"); 
                    });
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}
function savePO(ele,callback){
    var id = $("#<?= \yii\helpers\Html::getInputId($model, "kode") ?>").val();
    var data = $(ele).parents("tr").find("input,select,textarea");
    $(ele).parents("tr").find("select[name*='[suplier_id]']").removeClass("error-tb-detail");
    var has_error = 0;
    
    if(!$(ele).parents("tr").find("select[name*='[suplier_id]']").val()){
		$(ele).parents("tr").find("select[name*='[suplier_id]']").addClass("error-tb-detail");
        cisAlert("Supplier tidak boleh kosong");
		has_error = has_error + 1;
	}
    
    $("#table-detail > tbody > tr select[disabled]").each(function(){
        if( $(this).val() == $(ele).parents("tr").find("select[name*='[suplier_id]']").val() ){
            $(ele).parents("tr").find("select[name*='[suplier_id]']").addClass("error-tb-detail");
            cisAlert("Supplier ini sudah ada dilist");
            has_error = has_error + 1;
        }
    });
    
    $("#table-detail > tbody > tr").each(function(){
        if( $(this).val() == $(ele).parents("tr").find("select[name*='[suplier_id]']").val() ){
            $(ele).parents("tr").find("select[name*='[suplier_id]']").addClass("error-tb-detail");
            cisAlert("Supplier ini sudah ada dilist");
            has_error = has_error + 1;
        }
    });
    
    if(has_error === 0){
        unformatNumberAll();
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/savePO']); ?>',
            type   : 'POST',
            data   : {data:data.serialize(),posengon_rencana_id:id},
            success: function (data) {
                if(data){
                    cisAlert(data.message);
                    if(data.status){
                        getItems();
                    }
                    reordertable("#table-detail");
                    if(callback){ callback(); }
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}
function deletePO(id){
	$(".modals-place-confirm").load('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/deletePO']) ?>?id='+id, function() {
		$("#modal-delete-record").modal('show');
		$("#modal-delete-record").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
function editPOConfirm(ele){
    var no_urut = $(ele).parents('tr').find('input[name*="no_urut"]').val();
    var status_approval = $("#<?= yii\helpers\Html::getInputId($model, "status_approval") ?>").val();
    if(status_approval=="APPROVED"){
        cisConfirm('Memodifikasi data yang sudah di approve, memerlukan approval ulang. Tetap lanjutkan?<br /><br /><a class="btn btn-xs hijau" onclick="editPOUpdateNotconfirm('+no_urut+')">Lanjutkan</a> &nbsp; <a class="btn btn-xs grey">Jangan</a>');
    }else{
        editPO(no_urut);
    }
}

function editPOUpdateNotconfirm(no_urut){
    var posengon_rencana_id = $("#<?= yii\helpers\Html::getInputId($model, "kode") ?>").val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/editPOUpdateNotconfirm']); ?>',
        type   : 'POST',
        data   : {posengon_rencana_id:posengon_rencana_id},
        success: function (data){
            if(data){
                setValue();
                getItems();
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function editPO(ele){
    var no_urut = $(ele).parents('tr').find('input[name*="no_urut"]').val();
    $("#table-detail > tbody > tr").find("input[name='no_urut'][value='"+no_urut+"']").parents('tr').find("input:not([name*='total']),select,textarea").prop("disabled",false);
	$("#table-detail > tbody > tr").find("input[name='no_urut'][value='"+no_urut+"']").parents('tr').find('.input-group-btn > button').prop('disabled', false);
	$("#table-detail > tbody > tr").find("input[name='no_urut'][value='"+no_urut+"']").parents('tr').find(".show-mode").attr("style","display:none;");
	$("#table-detail > tbody > tr").find("input[name='no_urut'][value='"+no_urut+"']").parents('tr').find(".input-mode").attr("style","display:none");
	$("#table-detail > tbody > tr").find("input[name='no_urut'][value='"+no_urut+"']").parents('tr').find(".edit-mode").attr("style","display:");
	$("#table-detail > tbody > tr").find("input[name='no_urut'][value='"+no_urut+"']").parents('tr').find(".hidden").removeClass("hidden");
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        if(validatingDetail()){
            submitform($form);
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
    var field1 = $('input[name*="[kode_permintaan]"]');
    if(!field1.val()){
        $('input[name*="[kode_permintaan]"]').addClass('error-tb-detail');
        has_error = has_error + 1;
    }else{
        $('input[name*="[kode_permintaan]"]').removeClass('error-tb-detail');
    }
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_awal") ?>").siblings('.input-group-addon').find('button').prop('disabled', true);
    $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_akhir") ?>").siblings('.input-group-addon').find('button').prop('disabled', true);
    $("#<?= yii\helpers\Html::getInputId($model, "kode_permintaan") ?>").parents(".input-group-btn").siblings('.input-group-btn').find('button').prop('disabled', true);
    $("#<?= yii\helpers\Html::getInputId($model, "kode") ?>").prop('disabled', false);
    $('#btn-add-item').show();
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    getItems();
}

function setValue(){
	var posengon_rencana_id = $("#<?= yii\helpers\Html::getInputId($model, "kode") ?>").val();
	$("#<?= yii\helpers\Html::getInputId($model, "pmr_id") ?>").val("");
	$("#<?= yii\helpers\Html::getInputId($model, "kode_permintaan") ?>").val("");
	$("#<?= yii\helpers\Html::getInputId($model, "tanggal") ?>").val( formatDateForUser(createDate()) );
    $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_awal") ?>").val("");
    $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_akhir") ?>").val("");
    $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_awal") ?>").prop('disabled', false);
    $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_akhir") ?>").prop('disabled', false);
    $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_awal") ?>").siblings('.input-group-addon').find('button').prop('disabled', false);
    $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_akhir") ?>").siblings('.input-group-addon').find('button').prop('disabled', false);
    $("#<?= yii\helpers\Html::getInputId($model, "kode_permintaan") ?>").parents(".input-group-btn").siblings('.input-group-btn').find('button').prop('disabled', false);
    $("#place-approvalstatus").html('-');
    $('#btn-add-item').hide();
    $('#btn-save').prop('disabled', false);
    $('#btn-print').prop('disabled', true);
    $('#btn-modify').hide();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/setValue']); ?>',
        type   : 'POST',
        data   : {posengon_rencana_id:posengon_rencana_id},
        success: function (data){
            if(data){
                $("#<?= yii\helpers\Html::getInputId($model, "pmr_id") ?>").val( data.modPmr.pmr_id );
                pick(data.modPmr.kode);
                $("#<?= yii\helpers\Html::getInputId($model, "tanggal") ?>").val( formatDateForUser(data.model.tanggal) );
                $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_awal") ?>").val( formatDateForUser(data.model.tanggal_pengiriman_awal) );
                $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_akhir") ?>").val( formatDateForUser(data.model.tanggal_pengiriman_akhir) );
                $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_awal") ?>").prop('disabled', true);
                $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_akhir") ?>").prop('disabled', true);
                $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_awal") ?>").siblings('.input-group-addon').find('button').prop('disabled', true);
                $("#<?= yii\helpers\Html::getInputId($model, "tanggal_pengiriman_akhir") ?>").siblings('.input-group-addon').find('button').prop('disabled', true);
                $("#<?= yii\helpers\Html::getInputId($model, "kode_permintaan") ?>").parents(".input-group-btn").siblings('.input-group-btn').find('button').prop('disabled', true);
                setLabelPeriode();
                if(data.labelapproval){
                    $("#<?= yii\helpers\Html::getInputId($model, "status_approval") ?>").val(data.modApproval.status);
                    $("#place-approvalstatus").html(data.labelapproval);
                    if(data.modApproval.status == 'Not Confirmed'){
                        $('#btn-add-item').show();
                    }
                    if(data.modApproval.status == 'APPROVED'){
                        $('#btn-modify').show();
                    }
                }
                
                $('#btn-save').prop('disabled', true);
                $('#btn-print').prop('disabled', false);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function total(){
	var jml_subtotal_m3 = 0;
	// subtotal Horizontal
	$("#table-detail > tbody > tr").each(function(){
		var tr = $(this); var subtotal_m3 = 0;
		$(tr).find(".col-m3").each(function(){
			subtotal_m3 += unformatNumber( $(this).val() );
		}).promise().done( function(){ 
			$(tr).find("input[name*='[total][qty_m3]']").val(formatNumberForUser(subtotal_m3));
		});
	});
	
	// subtotal Vertical
	var sub_ver = [];
	$("#table-detail > tfoot > tr:first").each(function(){
		$(this).find(".col-m3-foot").each(function(){
			var key = $(this).attr("name").replace(/]/g,"");
			key = key.split("["); key = key[1];
			sub_ver.push(key);
		});
	});
	$(sub_ver).each(function(key,val){
        var sub_m3 = 0;
		$("#table-detail > tbody > tr").each(function(){
			sub_m3 += unformatNumber( $(this).find("input[name*='["+val+"][qty_m3]']").val() );
		});
		$("#table-detail > tfoot").find("input[name*='["+val+"][total_m3]']").val( formatNumberForUser(sub_m3) );
	});
	
	// total
	setTimeout(function(){ 
		$("#table-detail > tbody > tr").each(function(){ 
			jml_subtotal_m3 += unformatNumber( $(this).find("input[name*='[total][qty_m3]']").val() );
		}).promise().done( function(){ 
			$("#table-detail").find("input[name*='[total][total_m3]']").val(formatNumberForUser(jml_subtotal_m3));
		});
        
        // subtotal Vertical Persen
        $(sub_ver).each(function(key,val){
            var sub_m3_persen = 0;
            var sub_m3 = unformatNumber( $("#table-detail > tfoot").find("input[name*='["+val+"][total_m3]']").val() );
            sub_m3_persen = (sub_m3 / jml_subtotal_m3) * 100;
//             console.log(jml_subtotal_m3);
            $("#table-detail > tfoot").find("input[name*='["+val+"][total_persen]']").val( formatInteger(sub_m3_persen) +" %" );
         });
	},400);
}

function printout(){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/posengon/printout') ?>?posengon_rencana_id=<?= (isset($_GET['posengon_rencana_id'])?$_GET['posengon_rencana_id']:"") ?>","",'location=_new, width=1200px, scrollbars=1');
}
function setHarga(ele){
    var no_urut = $(ele).parents('tr').find('input[name*="no_urut"]').val();
    var source = $(ele).parents('tr').find('input[name*="[diameter_harga]"]').val();
    var par= [];
    if(source){
        par = reformatJson(source);
    }
    par = JSON.stringify(par);
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/setHarga','no_urut'=>'']) ?>'+no_urut+'&source='+par,'modal-sethargasengon','90%');
}

function reformatJson(source){
    var par= [];
    source = $.parseJSON(source);
    $(source).each(function(i,vals){
        par[i] = vals;
        $(vals).each(function(k,val){
            par[i][k].panjang = unformatNumber(val.panjang);
            par[i][k].wilayah = val.wilayah;
            par[i][k].diameter_awal = unformatNumber(val.diameter_awal);
            par[i][k].diameter_akhir = unformatNumber(val.diameter_akhir);
            par[i][k].harga = unformatNumber(val.harga);
        });
    });
    return par;
}

function detailPo(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/detailPo','id'=>'']) ?>'+id,'modal-detailpo','22cm');
}
function printPo(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/posengon/printPo') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
</script>