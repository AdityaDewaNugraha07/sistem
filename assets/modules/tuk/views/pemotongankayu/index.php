<?php
/* @var $this yii\web\View */
$this->title = 'Pemotongan Kayu Bulat';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Pemotongan Kayu Bulat (Dokumen)'); ?></h1>
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
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Pemotongan Kayu'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Pemotongan Kayu Bulat Dokumen'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= yii\helpers\Html::activeHiddenInput($model, "pemotongan_kayu_id") ?>
										<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Kode Pemotongan"); ?>
										<?= $form->field($model, 'nomor')->textInput(['placeholder'=>'ex. 7/DKB/CWM/19AI/III/2019'])->label("Nomor Berita Acara"); ?>
										<?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Pemotongan"); ?>				
										
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'petugas')->dropDownList(\app\models\MPetugasLegalkayu::getOptionList('Kayu Bulat'),['prompt'=>''])->label("Penerbit"); ?>
										<?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                </div>
                                <div class="row ">
									<br><hr>
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Data DKB'); ?></h4>
                                    </div>
                                </div>
								<div class="row" style="margin-left: -30px; margin-right: -30px;">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" style="width: 30px;"><?= Yii::t('app', 'No'); ?></th>
                                                        <th colspan="5"><?= Yii::t('app', 'Asal Kayu Semula'); ?></th>
                                                        <th colspan="4"><?= Yii::t('app', 'Dipotong Menjadi'); ?></th>
														<th rowspan="2" style="width: 40px;"><?= Yii::t('app', ''); ?></th>
                                                    </tr>
													<tr>
														<th style="width: 250px; font-size: 1.1rem;"><?= Yii::t('app', 'No. Barcode'); ?></th>
														<th style="font-size: 1.1rem;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'P <sup>m</sup>'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', '&#8709; <sup>cm</sup>'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'V <sup>m3</sup>'); ?></th>
                                                        <th style="width: 30px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Jumlah<br>Potong'); ?></th>
                                                        <th style="width: 265px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'No. Barcode Baru'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'P <sup>m</sup>'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'V <sup>m3</sup>'); ?></th>
													</tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
												<tfoot>
													<tr>
														<td colspan="6">
															<?php if(isset($_GET['pemotongan_kayu_id'])){ ?>
																<a class="btn btn-xs grey btn-outline" id="btn-add-item" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Item'); ?></a>
															<?php }else{ ?>
																<a class="btn btn-xs blue-hoki btn-outline" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Item'); ?></a>
															<?php } ?>
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
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print BAP'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printBAP('.(isset($_GET['pemotongan_kayu_id'])?$_GET['pemotongan_kayu_id']:'').');','disabled'=>true]); ?>
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
if(isset($_GET['pemotongan_kayu_id'])){
    $pagemode = "afterSave();";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode;
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Pemotongan Kayu Bulat'))."');
", yii\web\View::POS_READY); ?>
<script>
function addItem(){
	openModal('<?= \yii\helpers\Url::toRoute(['/tuk/pemotongankayu/stockLog'])?>','modal-stock-log','90%');
}
function pick(no_barcode){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/tuk/pemotongankayu/addItem']); ?>',
        type   : 'POST',
        data   : {no_barcode:no_barcode},
        success: function (data){
            if(data.html){
				var allow = true;
				$('#table-detail > tbody > tr').each(function(){
					var barcode = $(this).find('input[name*="[no_barcode]"]').val();
					if(barcode == data.no_barcode){
						allow = false;
					}
				});
				if(allow){
					$("#modal-stock-log").find('button.fa-close').trigger('click');
					$(data.html).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
						reordertable('#table-detail');
					});
				}else{
					cisAlert(data.no_barcode+" is already picked, please pick other."); return false;
				}
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function addPotongan(ele){
	var lastinput = $(ele).parents('td').siblings('td:eq(6)').find('input:last').clone();
	var lastinput2 = $(ele).parents('td').siblings('td:eq(7)').find('input:last').clone();
	var lastinput3 = $(ele).parents('td').siblings('td:eq(8)').find('input:last').clone();
	var value = $(lastinput).val().split(".");
	var barcode = $(ele).parents('tr').find("input[name*='[no_barcode]']").val();
	var seq = parseInt(value[(value.length-1)])+1;
	var new_value = (seq < 10)?"0"+seq:seq;
	$(lastinput).val(barcode+"."+new_value);
	$(ele).parents('td').siblings('td:eq(6)').append(lastinput); var row = 0;
	$(ele).parents('td').siblings('td:eq(6)').find('input').each(function(){
		var old_name = $(this).attr("name").replace(/]/g,"");
		var old_name_arr = old_name.split("[");
		$(this).attr("id",old_name_arr[0]+"_"+old_name_arr[1]+"_"+row+"_"+old_name_arr[3]);
		$(this).attr("name",old_name_arr[0]+"["+old_name_arr[1]+"]["+row+"]["+old_name_arr[3]+"]");
		row++;
	});
	$(ele).parents('td').siblings('td:eq(7)').append(lastinput2); var row = 0;
	$(ele).parents('td').siblings('td:eq(7)').find('input').each(function(){
		var old_name = $(this).attr("name").replace(/]/g,"");
		var old_name_arr = old_name.split("[");
		$(this).attr("id",old_name_arr[0]+"_"+old_name_arr[1]+"_"+row+"_"+old_name_arr[3]);
		$(this).attr("name",old_name_arr[0]+"["+old_name_arr[1]+"]["+row+"]["+old_name_arr[3]+"]");
		row++;
	});
	$(ele).parents('td').siblings('td:eq(8)').append(lastinput3); var row = 0;
	$(ele).parents('td').siblings('td:eq(8)').find('input').each(function(){
		var old_name = $(this).attr("name").replace(/]/g,"");
		var old_name_arr = old_name.split("[");
		$(this).attr("id",old_name_arr[0]+"_"+old_name_arr[1]+"_"+row+"_"+old_name_arr[3]);
		$(this).attr("name",old_name_arr[0]+"["+old_name_arr[1]+"]["+row+"]["+old_name_arr[3]+"]");
		row++;
	});
	formconfig();
	setJmlPotongan(ele);
}
function removePotongan(ele){
	if($(ele).parents('td').siblings('td:eq(6)').find('input').length > 2){
		$(ele).parents('td').siblings('td:eq(6)').find('input:last').remove();
	}
	if($(ele).parents('td').siblings('td:eq(7)').find('input').length > 2){
		$(ele).parents('td').siblings('td:eq(7)').find('input:last').remove();
	}
	if($(ele).parents('td').siblings('td:eq(8)').find('input').length > 2){
		$(ele).parents('td').siblings('td:eq(8)').find('input:last').remove();
	}
	setJmlPotongan(ele);
}
function setJmlPotongan(ele){
	var jml = $(ele).parents('td').siblings('td:eq(6)').find('input').length;
	$(ele).parents('td').find("input[name*='[jumlah_potong]']").val(jml);
}
function setVolBaru(ele){
	var pjg = unformatNumber( $(ele).parents('tr').find('input[name*="[panjang]"]').val() );
	var vol = unformatNumber( $(ele).parents('tr').find('input[name*="[volume]"]').val() );
	var pjg_baru = unformatNumber( $(ele).val() );
	var vol_baru = (pjg_baru * vol) / pjg;
	var old_name = $(ele).attr("name").replace(/]/g,"");
	var old_name_arr = old_name.split("[");
	var i = old_name_arr[2];
	$(ele).parents('tr').find('input[name*="['+i+'][volume_baru]"]').val( formatNumberForUser2Digit(vol_baru) );
}

function afterSave(id){
    getItems();
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tpemotongankayu-tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if(isset($_GET['edit'])){ ?>
		$('#tpemotongankayu-tanggal').siblings('.input-group-btn').find('button').prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "nomor") ?>").prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "petugas") ?>").prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "keterangan") ?>").prop('disabled', false);
		$('#btn-save').removeAttr('disabled');
		$('#btn-print').attr('disabled','');
	<?php } ?>
}

function getItems(){
	var pemotongan_kayu_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'pemotongan_kayu_id') ?>').val();
	var edit = "<?= isset($_GET['edit'])?$_GET['edit']:"" ?>";
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/tuk/pemotongankayu/getItems']); ?>',
		type   : 'POST',
		data   : {pemotongan_kayu_id:pemotongan_kayu_id,edit:edit},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function save(ele){
    var $form = $('#form-transaksi');
    if(validatingDetail(ele)){
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

function validatingDetail(ele){
    var has_error = 0;
	
    if(has_error === 0){
        return true;
    }
    return false;
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
    });
}
function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/tuk/pemotongankayu/DaftarAfterSave']) ?>','modal-aftersave','80%');
}
function edit(ele){
	$(ele).parents('tr').find('input, select').removeAttr('disabled');
	$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
	$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
}
function printBAP(id){
	window.open("<?= yii\helpers\Url::toRoute('/tuk/pemotongankayu/printBAP') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
</script>