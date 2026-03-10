<?php
/* @var $this yii\web\View */
$this->title = 'Permintaan Pembelian Log';
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Permintaan Pembelian Log'); ?></h1>
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
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Permintaan Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Permintaan'); ?></h4></span>
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
										if(!isset($_GET['pmr_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
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
                                                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker" data-date-end-date="-0d">{input} <span class="input-group-btn">
                                                             <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                             {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                        <?= $form->field($model, 'jenis_log')->dropDownList(["LA"=>"LOG ALAM","LS"=>"LOG SENGON"],['class'=>'form-control',"prompt"=>'','onchange'=>'setHeader(); setDetail();']); ?>
                                        <?= $form->field($model, 'tujuan')->dropDownList(["INDUSTRI"=>"INDUSTRI","TRADING"=>"TRADING"],['onchange'=>'setHeader()']); ?>
                                        <div class="form-group">
											<label class="col-md-4 control-label">Tanggal Dibutuhkan</label>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 50%">
													<?= $form->field($model, 'tanggal_dibutuhkan_awal',[
																'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
																			 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																			 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
												</span>
												<span class="input-group-addon textarea-addon" style="width: 10%; background-color: #fff; border: 0;"> sd </span>
												<span class="input-group-btn" style="width: 50%">
													<?= $form->field($model, 'tanggal_dibutuhkan_akhir',[
																'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
																			 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																			 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
												</span>
											</div>
										</div>
                                        <?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'dibuat_oleh')->dropDownList([],['class'=>'form-control','prompt'=>'']); ?>
                                        <?= $form->field($model, 'approver_1')->dropDownList([],['class'=>'form-control','prompt'=>'','disabled'=>true]); ?>
                                        <?= $form->field($model, 'approver_2')->dropDownList([],['class'=>'form-control','prompt'=>'','disabled'=>true]); ?>
                                        <?= $form->field($model, 'approver_3')->dropDownList([],['class'=>'form-control','prompt'=>'','disabled'=>true]); ?>
                                        <?= $form->field($model, 'approver_4')->dropDownList([],['class'=>'form-control','prompt'=>'','disabled'=>true]); ?>
                                        <?= $form->field($model, 'approver_5')->dropDownList([],['class'=>'form-control','prompt'=>'','disabled'=>true]); ?>
                                    </div>
                                </div>
                                <br><br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Detail Permintaan'); ?></h4>
                                    </div>
                                </div>
                                <div class="row" style="margin-left: -20px; margin-right: -20px;">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                        <div class="table-scrollable" id="place-table-detail"></div>
                                    </div>
                                </div><br>
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
if( isset($_GET['pmr_id']) && isset($_GET['edit'])){
    $pagemode = "afterSave(".$_GET['pmr_id'].");";
}else if(isset($_GET['pmr_id'])){
    $pagemode = "afterSave(".$_GET['pmr_id'].")";
}else {
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Permintaan Pembelian Log'))."');
    formconfig();
    setHeader();
    setDetail();
    $pagemode
", yii\web\View::POS_READY); ?>
<script>
function setHeader(){
    var jenis_log = $("#<?= \yii\helpers\Html::getInputId($model, "jenis_log") ?>").val();
    var pmr_id = "<?= isset($_GET['pmr_id'])? $_GET['pmr_id']:"" ?>";
    if(jenis_log == "LS"){
        $("#<?= \yii\helpers\Html::getInputId($model, "tujuan") ?>").val("INDUSTRI");
        $("#<?= \yii\helpers\Html::getInputId($model, "tujuan") ?>").attr("disabled",true);
    }else{
        $("#<?= \yii\helpers\Html::getInputId($model, "tujuan") ?>").attr("disabled",false);
    }
    var tujuan = $("#<?= \yii\helpers\Html::getInputId($model, "tujuan") ?>").val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pmr/setHeader']); ?>',
        type   : 'POST',
        data   : {jenis_log:jenis_log,tujuan:tujuan,pmr_id:pmr_id},
        success: function (data) {
            if(data){
                if(data.dibuat_oleh_html){
                    $("#<?= \yii\helpers\Html::getInputId($model, "dibuat_oleh") ?>").html(data.dibuat_oleh_html);
                }
                if(data.approver_1_html){
                    $("#<?= \yii\helpers\Html::getInputId($model, "approver_1") ?>").html(data.approver_1_html);
                    $("#<?= \yii\helpers\Html::getInputId($model, "approver_1") ?>").parents(".form-group").find("label").html(data.approver_1_label);
                }
                if(data.approver_2_html){
                    $("#<?= \yii\helpers\Html::getInputId($model, "approver_2") ?>").html(data.approver_2_html);
                    $("#<?= \yii\helpers\Html::getInputId($model, "approver_2") ?>").parents(".form-group").find("label").html(data.approver_2_label);
                }
                if(data.approver_3_html){
                    $("#<?= \yii\helpers\Html::getInputId($model, "approver_3") ?>").html(data.approver_3_html);
                    $("#<?= \yii\helpers\Html::getInputId($model, "approver_3") ?>").parents(".form-group").find("label").html(data.approver_3_label);
                }
                if(data.approver_4_html){
                    $("#<?= \yii\helpers\Html::getInputId($model, "approver_4") ?>").html(data.approver_4_html);
                    $("#<?= \yii\helpers\Html::getInputId($model, "approver_4") ?>").parents(".form-group").find("label").html(data.approver_4_label);
                }
                if(data.approver_5_html){
                    $("#<?= \yii\helpers\Html::getInputId($model, "approver_5") ?>").html(data.approver_5_html);
                    $("#<?= \yii\helpers\Html::getInputId($model, "approver_5") ?>").parents(".form-group").find("label").html(data.approver_5_label);
                }
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setDetail(){
    var jenis_log = $("#<?= \yii\helpers\Html::getInputId($model, "jenis_log") ?>").val();
    if(jenis_log == "LA"){
        
    }else if(jenis_log == "LS"){
        
    }
    $('#place-table-detail').html("");
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pmr/setDetail']); ?>',
        type   : 'POST',
        data   : {jenis_log:jenis_log},
        success: function (data){
            if(data.html){
                $('#place-table-detail').html(data.html);
                <?php if(!isset($_GET['pmr_id'])){ ?>
                    addItem();
                <?php } ?>
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function addItem(){
    var jenis_log = $("#<?= \yii\helpers\Html::getInputId($model, "jenis_log") ?>").val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pmr/addItem']); ?>',
        type   : 'POST',
        data   : {jenis_log:jenis_log},
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
		var sub_btg = 0; var sub_m3 = 0; var sub_harga = 0;
		$("#table-detail > tbody > tr").each(function(){
			sub_m3 += unformatNumber( $(this).find("input[name*='["+val+"][qty_m3]']").val() );
		});
		$("#table-detail > tfoot").find("input[name*='["+val+"][total_m3]']").val( sub_m3 );
	});
	
	// total
	setTimeout(function(){ 
		$("#table-detail > tbody > tr").each(function(){ 
			jml_subtotal_m3 += unformatNumber( $(this).find("input[name*='[total][qty_m3]']").val() );
		}).promise().done( function(){ 
			$("#table-detail").find("input[name*='[total][total_m3]']").val(formatNumberForUser(jml_subtotal_m3));
			var total_m3_industri = unformatNumber( $("#table-detail-industri").find("input[name*='[total][total_m3]']").val() );
			var total_m3_trading = unformatNumber( $("#table-detail-trading").find("input[name*='[total][total_m3]']").val() );
			var total_m3_pembelian = total_m3_industri + total_m3_trading;
			<?php if( isset($_GET['edit']) || !isset($_GET['pengajuan_pembelianlog_id']) ){ ?>
				$("#<?= yii\bootstrap\Html::getInputId($model, "total_volume") ?>").val( formatNumberForUser(total_m3_pembelian) );
			<?php } ?>
		}); 
	},400);
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail permintaan terlebih dahulu');
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
        var field1 = $(this).find('select[name*="[kayu_id]"]');
        if(!field1.val()){
            $(this).find('select[name*="[kayu_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('select[name*="[kayu_id]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("readonly","readonly"); });
    $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_dibutuhkan_awal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_dibutuhkan_akhir') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    <?php if(isset($_GET['edit'])){ ?>
        getItems(id,true);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').prop("disabled", false);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-btn').find('button').prop('disabled', false);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'jenis_log') ?>').prop("disabled", false);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tujuan') ?>').prop("disabled", false);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_dibutuhkan_awal') ?>').prop("disabled", false);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_dibutuhkan_awal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_dibutuhkan_akhir') ?>').prop("disabled", false);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_dibutuhkan_akhir') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'keterangan') ?>').prop("disabled", false);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'keterangan') ?>').prop("readonly", false);
        $('#btn-add-item').show();
        $('#btn-save').removeAttr('disabled');
        $('#btn-print').attr('disabled','');
    <?php }else{ ?>
        getItems(id);
    <?php } ?>
}

function getItems(pmr_id,edit=false){
    var jenis_log = $("#<?= \yii\helpers\Html::getInputId($model, "jenis_log") ?>").val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pmr/GetItems']); ?>',
		type   : 'POST',
		data   : {pmr_id:pmr_id,edit:edit,jenis_log:jenis_log},
		success: function (data) {
			if(data.html){
                setTimeout(function(){
                    $('#table-detail tbody').html(data.html);
                    reordertable('#table-detail');
                    total();
                },1000);
            }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pmr/daftarAfterSave']) ?>','modal-aftersave','90%');
}
</script>