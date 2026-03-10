<?php
/* @var $this yii\web\View */
$this->title = 'Pemotongan Log';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Pemotongan Kayu Bulat (Stock)'); ?></h1>
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
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Pemotongan Log'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Pemotongan Kayu Bulat Stock'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= yii\helpers\Html::activeHiddenInput($model, "pemotongan_log_id") ?>
										<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Kode Pemotongan"); ?>
                                        <?= $form->field($model, 'peruntukan')->inline(true)->radioList(['Industri'=>'Industri','Trading'=>'Trading'],['style'=>'margin-left:20px', 'onchange'=>'setLabelNo();']); ?>
										<?= $form->field($model, 'nomor')->textInput()->label('
                                                <span id="label-nomor"></span>
                                            '); ?>
                                        <?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Pemotongan"); ?>				
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'petugas')->dropDownList(\app\models\MPegawai::getOptionListPPIC(),['class'=>'form-control select2','prompt'=>'','data-placeholder'=>'Ketik Nama Pegawai']); ?>
										<?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                </div>
                                <div class="row ">
									<br><hr>
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Data Log'); ?></h4>
                                    </div>
                                </div>
								<div class="row" style="margin-left: -30px; margin-right: -30px;">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="3" style="width: 30px;"><?= Yii::t('app', 'No'); ?></th>
                                                        <th colspan="6"><?= Yii::t('app', 'Asal Kayu Semula'); ?></th>
                                                        <th colspan="14"><?= Yii::t('app', 'Dipotong Menjadi'); ?></th>
														<th rowspan="3" style="width: 40px;"><?= Yii::t('app', ''); ?></th>
                                                    </tr>
													<tr>
														<th rowspan="2" style="width: 120px; font-size: 1.1rem;"><?= Yii::t('app', 'No. Barcode<br>No. Lap'); ?></th>
														<th rowspan="2" style="width: 220px; font-size: 1.1rem;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                                        <th rowspan="2" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'P <sup>m</sup>'); ?></th>
                                                        <th rowspan="2" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', '&#8709; <sup>cm</sup>'); ?></th>
                                                        <th rowspan="2" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'V <sup>m3</sup>'); ?></th>
                                                        <th rowspan="2" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Reduksi'); ?></th>
                                                        <th rowspan="2" style="width: 30px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Jumlah<br>Potong'); ?></th>
                                                        <th rowspan="2" style="width: 155px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'No. Barcode Baru'); ?></th>
                                                        <th rowspan="2" style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'P <sup>m</sup>'); ?></th>
                                                        <th colspan="4" style="font-size: 1.1rem; line-height: 1"><?= Yii::t('app', '&#8709; <sup>cm</sup>'); ?></th>
                                                        <th colspan="3" style="font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Cacat <sup>cm</sup>'); ?></th>
                                                        <th rowspan="2" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Reduksi'); ?></th>
                                                        <th rowspan="2" style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'V <sup>m3</sup>'); ?></th>
                                                        <th rowspan="2" style="width: 70px; font-size: 1.1rem;"><?= Yii::t('app', 'Alokasi'); ?></th>
                                                        <th rowspan="2" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Grading<br>Rule'); ?></th>
													</tr>
                                                    <tr>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Ujung 1'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Ujung 2'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Pangkal 1'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Pangkal 2'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'P'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Gb'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Gr'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
												<tfoot>
													<tr>
														<td colspan="6">
															<?php if(isset($_GET['pemotongan_log_id']) && (!isset($_GET['edit']))){ ?>
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
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printLog('.(isset($_GET['pemotongan_log_id'])?$_GET['pemotongan_log_id']:'').');','disabled'=>true]); ?>
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
if(isset($_GET['pemotongan_log_id'])){
    $pagemode = "afterSave();";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode;
	formconfig();
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Pemotongan Log'))."');
    setLabelNo();
", yii\web\View::POS_READY); ?>
<script>
function addItem(){
	openModal('<?= \yii\helpers\Url::toRoute(['/ppic/pemotonganlog/stockLog'])?>','modal-stock-log','90%');
}

function setLabelNo(){
    var peruntukan = $("input:radio[name*='[peruntukan]']:checked").val();
    if(peruntukan == 'Trading'){
        $("#label-nomor").html('Nomor SPM');
    } else {
        $("#label-nomor").html('Nomor SPK Pemotongan Log');
    }
}

function pick(no_barcode){ 
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/pemotonganlog/addItem']); ?>',
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
                        tabHorizontal();
					});
				}else{
					cisAlert(data.no_barcode+" is already picked, please pick other."); return false;
				}
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setGradingRule(ele){
    var alokasi = $(ele).parents('td').find("select[name*='[alokasi]']").val();
    var alokasi_id = $(ele).attr('id');
    var idx = getIndexFromId(alokasi_id);

    var grading_id = 'TPemotonganLogDetailPotong_' + idx.i + '_' + idx.ii + '_grading_rule';
    var alokasi_id = 'TPemotonganLogDetailPotong_' + idx.i + '_' + idx.ii + '_alokasi';

    if($('#' + alokasi_id).val() == "Plymill"){
        $('#' + grading_id).removeAttr('disabled');
        $('#' + grading_id).val('Q1');
    } else {
        $('#' + grading_id).attr('disabled','');
        $('#' + grading_id).val('');
    }
}

function getIndexFromId(id) {
    var parts = id.split('_');
    return {
        i: parts[1],
        ii: parts[2]
    };
}

function addPotongan(ele) {
    var lastinput = $(ele).parents('td').siblings('td:eq(7)').find('input:last').clone();
    var value = $(lastinput).val().split(".");
    var barcode = $(ele).parents('tr').find("input[name*='[no_barcode]']").val();
    var lastChar = value[1];  
    var new_value = '';
    
    if (lastChar.match(/[A-Za-z]/)) {
        new_value = String.fromCharCode(lastChar.charCodeAt(0) + 1);  // Increment huruf
    }
    var newInput = $(lastinput).val(barcode + "." + new_value);
    $(lastinput).val(barcode+"."+new_value);
	$(ele).parents('td').siblings('td:eq(7)').append(lastinput); var row = 0;
	$(ele).parents('td').siblings('td:eq(7)').find('input').each(function(){
		var old_name = $(this).attr("name").replace(/]/g,"");
		var old_name_arr = old_name.split("[");
		$(this).attr("id",old_name_arr[0]+"_"+old_name_arr[1]+"_"+row+"_"+old_name_arr[3]);
		$(this).attr("name",old_name_arr[0]+"["+old_name_arr[1]+"]["+row+"]["+old_name_arr[3]+"]");
		row++;
	});

    var cols = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17];
    cols.forEach(function(colIndex) {
        var lastinputClone = $(ele).parents('td').siblings('td:eq(' + colIndex + ')').find('input:last').clone();
        $(ele).parents('td').siblings('td:eq(' + colIndex + ')').append(lastinputClone);
        
        var row = 0;
        $(ele).parents('td').siblings('td:eq(' + colIndex + ')').find('input').each(function(){
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            $(this).attr("id", old_name_arr[0] + "_" + old_name_arr[1] + "_" + row + "_" + old_name_arr[3]);
            $(this).attr("name", old_name_arr[0] + "[" + old_name_arr[1] + "][" + row + "][" + old_name_arr[3] + "]");
            row++;
        });
    });

    var lastinput2 = $(ele).parents('td').siblings('td:eq(18)').find('select:last').clone();
    $(ele).parents('td').siblings('td:eq(18)').append(lastinput2); var row = 0;
	$(ele).parents('td').siblings('td:eq(18)').find('select').each(function(){
		var old_name = $(this).attr("name").replace(/]/g,"");
		var old_name_arr = old_name.split("[");
		$(this).attr("id",old_name_arr[0]+"_"+old_name_arr[1]+"_"+row+"_"+old_name_arr[3]);
		$(this).attr("name",old_name_arr[0]+"["+old_name_arr[1]+"]["+row+"]["+old_name_arr[3]+"]");
		row++;
	});

    var lastinput3 = $(ele).parents('td').siblings('td:eq(19)').find('select:last').clone();
    $(ele).parents('td').siblings('td:eq(19)').append(lastinput3); var row = 0;
    $(ele).parents('td').siblings('td:eq(19)').find('select').each(function(){
        var old_name = $(this).attr("name").replace(/]/g,"");
        var old_name_arr = old_name.split("[");
        $(this).attr("id",old_name_arr[0]+"_"+old_name_arr[1]+"_"+row+"_"+old_name_arr[3]);
        $(this).attr("name",old_name_arr[0]+"["+old_name_arr[1]+"]["+row+"]["+old_name_arr[3]+"]");

        var alokasiSelect = $(ele).parents('td').siblings('td:eq(18)').find('select').eq(row);
        var alokasiVal = alokasiSelect.val();
        if (alokasiVal !== 'Plymill') {
            $(this).attr("disabled", "disabled");
        } else {
            $(this).removeAttr("disabled");
        }

        row++;
    });

    formconfig();
    setJmlPotongan(ele);
    tabHorizontal();
}

function removePotongan(ele) {
    var cols = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];

    cols.forEach(function(colIndex) {
        var inputLength = $(ele).parents('td').siblings('td:eq(' + colIndex + ')').find('input').length;
        if (inputLength > 2) {
            $(ele).parents('td').siblings('td:eq(' + colIndex + ')').find('input:last').remove();
        }

        var selectLength = $(ele).parents('td').siblings('td:eq(' + colIndex + ')').find('select').length;
        if (selectLength > 2) {
            $(ele).parents('td').siblings('td:eq(' + colIndex + ')').find('select:last').remove();
        }
    });

    setJmlPotongan(ele);
    tabHorizontal();
}

function setJmlPotongan(ele){
	var jml = $(ele).parents('td').siblings('td:eq(7)').find('input').length;
	$(ele).parents('td').find("input[name*='[jumlah_potong]']").val(jml);
}

function setVolBaru(ele){
    var idx = $(ele).attr('id');
    var id = getIndexFromId(idx);

    // hitung rata2 dulu
    var ujung1 = unformatNumber($('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_diameter_ujung1_baru').val());
    var ujung2 = unformatNumber($('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_diameter_ujung2_baru').val());
    var pangkal1 = unformatNumber($('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_diameter_pangkal1_baru').val());
    var pangkal2 = unformatNumber($('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_diameter_pangkal2_baru').val());
    var ratarata = Math.round((ujung1+ujung2+pangkal1+pangkal2)/4);

    //hitung volume
    var panjang = $('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_panjang_baru').val();
	var cacat_panjang = $('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_cacat_pjg_baru').val();
	var cacat_gb = $('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_cacat_gb_baru').val();
	var cacat_gr = $('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_cacat_gr_baru').val();

    panjang == '' ? panjang = 0 : panjang = parseFloat(panjang);
	ratarata == '' ? ratarata = 0 : ratarata = parseFloat(ratarata);
    cacat_panjang == '' ? cacat_panjang = 0 : cacat_panjang = parseFloat(cacat_panjang);
    cacat_gb == '' ? cacat_gb = 0 : cacat_gb = parseFloat(cacat_gb);
    cacat_gr == '' ? cacat_gr = 0 : cacat_gr = parseFloat(cacat_gr);

    var pGrowong = (0.7854 * cacat_gr * cacat_gr * (panjang - (cacat_panjang / 100)) / 10000).toFixed(2);
    pGrowong == '' ? pGrowong = 0 : pGrowong = pGrowong;
    var zzz = (0.7854 * (panjang - (cacat_panjang / 100)) * ((ratarata - cacat_gb) * (ratarata - (cacat_gb)) * 1) / 10000) - (pGrowong);
    // var Vol = ((zzz * 100) / 100).toFixed(2);
    var Vol = zzz.toFixed(2);
    $('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_volume_baru').val(Vol);

	// var pjg = unformatNumber( $(ele).parents('tr').find('input[name*="[panjang]"]').val() );
	// var vol = unformatNumber( $(ele).parents('tr').find('input[name*="[volume]"]').val() );
	// var pjg_baru = unformatNumber( $(ele).val() );
	// var vol_baru = (pjg_baru * vol) / pjg;
	// var old_name = $(ele).attr("name").replace(/]/g,"");
	// var old_name_arr = old_name.split("[");
	// var i = old_name_arr[2];
	// $(ele).parents('tr').find('input[name*="['+i+'][volume_baru]"]').val( formatNumberForUser2Digit(vol_baru) );
}

function afterSave(id){
    getItems();
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tpemotonganlog-tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if(isset($_GET['edit'])){ ?>
		$('#tpemotonganlog-tanggal').siblings('.input-group-btn').find('button').prop('disabled', false);
        $("#<?= \yii\helpers\Html::getInputId($model, "tanggal") ?>").prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "nomor") ?>").prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "petugas") ?>").prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "keterangan") ?>").prop('disabled', false);
        $('input[name*="[peruntukan]"]').prop("disabled", false);
		$('#btn-save').removeAttr('disabled');
		$('#btn-print').attr('disabled','');
	<?php } ?>
}

function getItems(){
	var pemotongan_log_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'pemotongan_log_id') ?>').val();
	var edit = "<?= isset($_GET['edit'])?$_GET['edit']:"" ?>";
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/ppic/pemotonganlog/getItems']); ?>',
		type   : 'POST',
		data   : {pemotongan_log_id:pemotongan_log_id,edit:edit},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			reordertable('#table-detail');
            tabHorizontal();
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function save(){
    var $form = $('#form-transaksi');
    if(validatingDetail()){
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

    $("#table-detail tbody tr").each(function(){
        var alokasis = $(this).find('select[name*="[alokasi]"]');
        var gradingRules = $(this).find('select[name*="[grading_rule]"]');
        alokasis.each(function(index) {
            var alokasiVal = $(this).val();
            var gradingRule = gradingRules.eq(index); // pasangkan sesuai indeks

            if (alokasiVal === 'Plymill') {
                if (!gradingRule.val()) {
                    gradingRule.addClass('error-tb-detail');
                    has_error = has_error + 1;
                } else {
                    gradingRule.removeClass('error-tb-detail');
                }
            } else {
                gradingRule.removeClass('error-tb-detail');
            }
        });

        var field2s = $(this).find('input[name*="[diameter_ujung1_baru]"]');
        field2s.each(function(){
            var field2 = $(this);
            if (!field2.val() || field2.val() <= 0) {
                field2.addClass('error-tb-detail');
                has_error = has_error + 1;
            } else {
                field2.removeClass('error-tb-detail');
            }
        });

        var field3s = $(this).find('input[name*="[diameter_ujung2_baru]"]');
        field3s.each(function(){
            var field3 = $(this);
            if (!field3.val() || field3.val() <= 0) {
                field3.addClass('error-tb-detail');
                has_error = has_error + 1;
            } else {
                field3.removeClass('error-tb-detail');
            }
        });

        var field4s = $(this).find('input[name*="[diameter_pangkal1_baru]"]');
        field4s.each(function(){
            var field4 = $(this);
            if (!field4.val() || field4.val() <= 0) {
                field4.addClass('error-tb-detail');
                has_error = has_error + 1;
            } else {
                field4.removeClass('error-tb-detail');
            }
        });

        var field5s = $(this).find('input[name*="[diameter_pangkal2_baru]"]');
        field5s.each(function(){
            var field5 = $(this);
            if (!field5.val() || field5.val() <= 0) {
                field5.addClass('error-tb-detail');
                has_error = has_error + 1;
            } else {
                field5.removeClass('error-tb-detail');
            }
        });

        var field6s = $(this).find('input[name*="[panjang_baru]"]');
        field6s.each(function(){
            var field6 = $(this);
            if (!field6.val() || field6.val() <= 0) {
                field6.addClass('error-tb-detail');
                has_error = has_error + 1;
            } else {
                field6.removeClass('error-tb-detail');
            }
        });

        var field7s = $(this).find('input[name*="[volume_baru]"]');
        field7s.each(function(){
            var field7 = $(this);
            if (!field7.val() || field7.val() <= 0) {
                field7.addClass('error-tb-detail');
                has_error = has_error + 1;
            } else {
                field7.removeClass('error-tb-detail');
            }
        });
	});
	
    if(has_error === 0){
        return true;
    }
    return false;
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
        tabHorizontal();
    });
}
function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/pemotonganlog/DaftarAfterSave']) ?>','modal-aftersave','80%');
}
function edit(ele){
	$(ele).parents('tr').find('input, select').removeAttr('disabled');
	$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
	$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
}

function printLog(id){
    window.open("<?= yii\helpers\Url::toRoute('/ppic/pemotonganlog/printLog') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

// agar tab lari ke samping 
function tabHorizontal() {
	let inputs = [];

	// ambil semua input dari #table-detail
	$('#table-detail tbody input').each(function () {
		const id = $(this).attr('id');

		// TPemotonganLogDetailPotong_0_0_panjang_baru - berdasarkan baris
		const match = id.match(/TPemotonganLogDetailPotong_(\d+)_(\d+)_/);
		if (match) {
			const baris = parseInt(match[1], 10);
			const kolom = parseInt(match[2], 10);
			inputs.push({ el: this, baris, kolom });
		}
	});

	// urutkan berdasarkan baris dulu, lalu kolom
	inputs.sort((a, b) => {
		if (a.baris === b.baris) {
			return a.kolom - b.kolom;
		}
		return a.baris - b.baris;
	});

	// atur tabindex sesuai urutan yang sudah di-sort
	inputs.forEach((item, index) => {
		$(item.el).attr('tabindex', index + 1);
	});
}

</script>