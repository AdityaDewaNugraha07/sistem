<?php
/* @var $this yii\web\View */
$this->title = 'Pemotongan Log';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Pemotongan Log'); ?></h1>
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
									<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Pemotongan Log'); ?></h4></span>
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
                                        <?= $form->field($model, 'peruntukan')->inline(true)->radioList(['Industri'=>'Industri'],['style'=>'margin-left:20px', 'onchange'=>'setLabelNo(); setDropdownNo();']); ?> <!-- ,'Trading'=>'Trading' -->
                                        <?= $form->field($model, 'nomor')->dropDownList([],['class'=>'form-control select2', 'onchange'=>'emptyTable();'])->label('<span id="label-nomor"></span>'); ?>
                                        <?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Pemotongan"); ?>				
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'petugas')->dropDownList(\app\models\MPegawai::getOptionListPPIC(),['class'=>'form-control select2','prompt'=>'','data-placeholder'=>'Ketik Nama Pegawai', 'disabled'=>'']); ?>
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
                                                        <th style="width: 30px;"><?= Yii::t('app', 'No'); ?></th>
														<th style="width: 120px; font-size: 1.1rem;"><?= Yii::t('app', 'No. Barcode<br>No. Lapangan<br>Jenis Kayu'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'P <sup>cm</sup><br>&#8709; <sup>cm</sup><br>V <sup>m3</sup>'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Jumlah<br>Potong'); ?></th>
                                                        <th style="width: 15px;"><?= Yii::t('app', ''); ?></th>
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
    setDropdownNo();
", yii\web\View::POS_READY); ?>
<script>
function addItem(){
    var nomor = $("#<?= \yii\bootstrap\Html::getInputId($model, "nomor") ?>").val();
    var peruntukan = $("input:radio[name*='[peruntukan]']:checked").val();
    var edit = "<?= isset($_GET['edit'])?$_GET['edit']:"" ?>";
    if(nomor){
        openModal('<?= \yii\helpers\Url::toRoute('/ppic/pemotonganlog/stockLog') ?>?nomor='+nomor+'&edit='+edit,'modal-stock-log','90%');
    } else {
        if(peruntukan == 'Trading'){
            cisAlert('Isi Nomor SPM terlebih dahulu!');
        } else {
            cisAlert('Isi Nomor SPK Pemotongan Log terlebih dahulu!');
        }
    }
}

function setLabelNo(){
    var peruntukan = $("input:radio[name*='[peruntukan]']:checked").val();
    if(peruntukan == 'Trading'){
        $("#label-nomor").html('Nomor SPM');
    } else {
        $("#label-nomor").html('Nomor SPK Pemotongan Log');
    }
}

function setDropdownNo(){
    emptyTable();

    var peruntukan = $("input:radio[name*='[peruntukan]']:checked").val();
    var id = "<?= isset($_GET['pemotongan_log_id'])?$_GET['pemotongan_log_id']:"" ?>";
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/pemotonganlog/setDropdownNo']); ?>',
        type   : 'POST',
        data   : {peruntukan:peruntukan, id:id},
        success: function (data){
            if(data){
                if(peruntukan == 'Industri'){
                    $('select[name*=\"[nomor]\"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik Nomor SPK',
                    });
                } else {
                    $('select[name*=\"[nomor]\"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik Nomor SPM',
                    });
                }

                <?php if(isset($_GET['pemotongan_log_id'])){ ?>
                    $("#<?= yii\bootstrap\Html::getInputId($model, 'nomor') ?>").html(`<option value="${data.nomor}" selected>${data.nomor}</option>`);
                <?php } else { ?>
                    $("#<?= yii\bootstrap\Html::getInputId($model, 'nomor') ?>").html(data.html);
                    $('#<?= yii\bootstrap\Html::getInputId($model, 'nomor') ?>').siblings('.select2').removeClass('animation-loading');
                <?php } ?> 

                
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function pick(no_barcode){ 
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/pemotonganlog/addItem']); ?>',
        type   : 'POST',
        data   : {no_barcode:no_barcode}, 
        success: function (data){
            if(data.html){
				var allow = true;
				$('#table-detail > tbody > tr.row-detail').each(function(){
					var barcode = $(this).find('input[name*="[no_barcode]"]').val(); 
					if(barcode == data.no_barcode){
						allow = false;
					}
				});
				if(allow){
					$("#modal-stock-log").find('button.fa-close').trigger('click');
                    var tbody = $('#table-detail tbody');
                    if(tbody.find('tr.row-detail').length == 0){
                        $(data.html).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                            reordertableDetail();
                            reordertablePotong();
                            moveTab();
                        });
                    } else {
                        tbody.find('tr.row-detail-potong:last').after(data.html);
                        reordertableDetail();
                        reordertablePotong();
                        moveTab();
                    }
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
    var qr_id = 'print-qr-'+idx.i + '-' + idx.ii;
    var label_id = 'label-grade-'+idx.i + '-' + idx.ii;
    $('#' + grading_id).empty();

    if($('#' + alokasi_id).val() == "Plymill"){
        $("#" + label_id).html('Grade');
        $('#' + grading_id).append(new Option('Q1', 'Q1'));
        $('#' + grading_id).append(new Option('Q2', 'Q2'));
        $('#' + grading_id).append(new Option('Q3', 'Q3'));
        $('#' + grading_id).show().prop('disabled', false).val('Q1');
        $('#' + qr_id).hide();
    } else if ($('#' + alokasi_id).val() === "Sawmill") {
        $("#" + label_id).html('Grade');
        $('#' + grading_id).append(new Option('Standard', 'Standard'));
        $('#' + grading_id).append(new Option('Tanduk', 'Tanduk'));
        $('#' + grading_id).prop('disabled', false).val('Standard').show();
        $('#' + qr_id).hide();
    } else if($('#' + alokasi_id).val() == "Gudang"){
        $("#" + label_id).html('QRCode');
        $('#' + grading_id).hide().prop('disabled', true).val('');
        $('#' + qr_id).hide();
    } else {
        $("#" + label_id).html('Grade');
        $('#' + grading_id).show().prop('disabled', true).val('');
        $('#' + qr_id).hide();
    }
}

function getIndexFromId(id) {
    var parts = id.split('_');
    return {
        i: parts[1],
        ii: parts[2]
    };
}

function addPotongan(ele){
    var tr = $(ele).closest('tr');
    var jmlPotong = tr.find('input[name*="jumlah_potong"]');
    var table = tr.find('#table-potong tbody');

    var jumlah = parseInt(jmlPotong.val()) || 0;
    jumlah++;

    jmlPotong.val(jumlah);
    
    var i = jumlah - 1;
    addDetailPotong(i, ele);
}

function addDetailPotong(i, ele){
    var tr = $(ele).closest('tr');
    var no_barcode = tr.find('input[name*="[no_barcode]"]').val();

    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/pemotonganlog/addDetailPotong']); ?>',
        type   : 'POST',
        data   : {i: i, no_barcode:no_barcode}, 
        success: function (data){
            if(data.html){
                var table = $(ele).closest('tr').next('.row-detail-potong').find('#table-potong tbody');
                $(data.html).hide().appendTo(table).fadeIn(200,function(){
					reordertablePotong();
                    moveTab();
				});
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

/**function removePotongan(ele){
    var tr = $(ele).closest('tr');
    var jmlPotong = tr.find('input[name*="jumlah_potong"]');
    var detailRow = tr.next('.row-detail-potong');
    var table = detailRow.find('#table-potong tbody');

    var jumlah = parseInt(jmlPotong.val()) || 0;
    if (jumlah > 2) {
        jumlah--;
        jmlPotong.val(jumlah);

        var allRows = table.find('tr');
        var rowsToRemove = allRows.slice(-7); // karna tiap block potongan ada 7 row (tr)

        rowsToRemove.remove();
        reordertablePotong();
        moveTab();
    }
}*/

function removePotongan(ele) {
    var tr = $(ele).closest('tr');
    var jmlPotong = tr.find('input[name*="jumlah_potong"]');

    var jumlah = parseInt(jmlPotong.val());
    if(tr.find('input[name*="potong"]').is(":checked")){
        var minpotong = 2;
    } else {
        var minpotong = 1;
    }

    if (jumlah <= minpotong) {
        return;
    }

    var detailRow = tr.next('.row-detail-potong');
    var table = detailRow.find('#table-potong tbody');
    var allRows = table.find('tr');

    // Cari dari bawah potongan dengan data-status false untuk dihapus
    var found = false;
    for (var i = allRows.length - 7; i >= 0; i -= 7) { // 7 karena jml tr ada 7
        var headerRow = allRows.eq(i);
        var status = headerRow.data('status');

        if (status === false || status === 'false') {
            var rowsToRemove = allRows.slice(i, i + 7);
            rowsToRemove.remove();

            jumlah--;
            jmlPotong.val(jumlah);

            reordertablePotong();
            moveTab();

            found = true;
            break;
        }
    }

    if (!found) {
        cisAlert('Potongan ini sudah diterima, tidak bisa dihapus!');
    }
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
    var panjang = unformatNumber($('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_panjang_baru').val());
	var cacat_panjang = unformatNumber($('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_cacat_pjg_baru').val());
	var cacat_gb = unformatNumber($('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_cacat_gb_baru').val());
	var cacat_gr = unformatNumber($('#TPemotonganLogDetailPotong_' + id.i + '_' + id.ii + '_cacat_gr_baru').val());

    panjang == '' ? panjang = 0 : panjang = parseFloat(panjang / 100); // bagi 100, user input berupa cm - convert panjang dari cm ke m
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
		// $("#<?= \yii\helpers\Html::getInputId($model, "nomor") ?>").prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "petugas") ?>").prop('disabled', false);
		$("#<?= \yii\helpers\Html::getInputId($model, "keterangan") ?>").prop('disabled', false);
        // $('input[name*="[peruntukan]"]').prop("disabled", false);
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

            if(edit){
                $('#table-detail').find('.row-detail').each(function(){
                    if($(this).find('input[name*="potong"]').is(":checked")){
                        $(this).find('#btn-min-cut').attr('onclick', 'removePotongan(this);').css({'cursor': 'pointer'});
                        $(this).find('#btn-add-cut').attr('onclick', 'addPotongan(this);').css({'cursor': 'pointer'});
                    } else {
                        $(this).find('#btn-min-cut').attr('onclick', 'return false;').css({'cursor': 'not-allowed'});
                        $(this).find('#btn-add-cut').attr('onclick', 'return false;').css({'cursor': 'not-allowed'});
                    }
                });
            }
            
			reordertableDetail();
            reordertablePotong();
            moveTab();
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

    // cek validasi kode potong
    $('.row-detail').each(function() {
        var cut = $(this).find('input[name*="[potong]"]');
        var id = cut.attr("id"); // ex: TPemotonganLogDetail_0_potong
        var match = id.match(/_(\d+)_potong$/);

        if (!match) return; 
        var idx = match[1];
        if (!cut.is(":checked")) {
            $(`input[id^='TPemotonganLogDetailPotong_${idx}_']`).removeClass('error-tb-detail');
            return; 
        }

        $(`input[id^='TPemotonganLogDetailPotong_${idx}_'][id$='_kode_pemotongan']`).each(function(){
            if (!$(this).val()) {
                $(this).addClass('error-tb-detail');
                has_error = has_error + 1;
            } else {
                $(this).removeClass('error-tb-detail');
            }
        });
    });

    $("#table-potong tbody").each(function(){
        var alokasis = $(this).find('select[name*="[alokasi]"]');
        var gradingRules = $(this).find('select[name*="[grading_rule]"]');
        alokasis.each(function(index) {
            var alokasiVal = $(this).val();
            var gradingRule = gradingRules.eq(index); // pasangkan sesuai indeks

            if (alokasiVal === 'Plymill' || alokasiVal === 'Sawmill') {
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

        // var field8s = $(this).find('input[name*="[kode_pemotongan]"]');
        // field8s.each(function(){
        //     var field8 = $(this);
        //     if (!field8.val() || field8.val() == '') {
        //         field8.addClass('error-tb-detail');
        //         has_error = has_error + 1;
        //     } else {
        //         field8.removeClass('error-tb-detail');
        //     }
        // });
	});
	
    if(has_error === 0){
        return true;
    }
    return false;
}

function cancelItemThis(ele){
    var row = $(ele).closest('tr');

    if (row.hasClass('row-detail')) {
        var next = row.next();
        while (next.length && next.hasClass('row-detail-potong')) {
            const toRemove = next;
            next = next.next();
            toRemove.fadeOut(200, function () {
                $(this).remove();
                reordertablePotong(); 
            });
        }
        row.fadeOut(200, function () {
            $(this).remove();
            reordertableDetail(); 
            moveTab();
        });
    } else if (row.hasClass('row-detail-potong')) {
        row.fadeOut(200, function () {
            $(this).remove();
            reordertablePotong();
            moveTab();
        });
    }
}

function cancelItemThis2(ele){
    $(ele).parents('tr.row-detail').fadeOut(200,function(){
        $(this).remove();
        reordertableDetail();
    });
    $(ele).parents('tr.row-detail-potong').fadeOut(200,function(){
        $(this).remove();
        reordertablePotong();
    });
    moveTab();
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

// menyesuaikan arah tab
function moveTab() {
	let inputs = [];

	var kolomUrutan = [
		'kode_pemotongan',
		'panjang_baru',
		'diameter_ujung1_baru',
		'diameter_ujung2_baru',
		'diameter_pangkal1_baru',
		'diameter_pangkal2_baru',
		'cacat_pjg_baru',
		'cacat_gb_baru',
		'cacat_gr_baru',
		'volume_baru'
	];

	// ambil semua input/select
	$('#table-detail tbody input, #table-detail tbody select').each(function () {
		var id = $(this).attr('id');
		var match = id.match(/TPemotonganLogDetailPotong_(\d+)_(\d+)_([a-zA-Z0-9_]+)/);
		if (match) {
			var baris = parseInt(match[1], 10);
			var kolom = parseInt(match[2], 10);
			var field = match[3];
			var fieldIndex = kolomUrutan.indexOf(field);

			if (fieldIndex !== -1) {
				inputs.push({ el: this, baris, kolom, fieldIndex });
			}
		}
	});

	// urutkan berdasarkan baris, lalu kolom, lalu urutan field
	inputs.sort((a, b) => {
		if (a.baris !== b.baris) return a.baris - b.baris;
		if (a.kolom !== b.kolom) return a.kolom - b.kolom;
		return a.fieldIndex - b.fieldIndex;
	});

	// atur tabindex
	inputs.forEach((item, index) => {
		$(item.el).attr('tabindex', index + 1);
	});
}

function reordertableDetail(){
    var row = 0;
    $('#table-detail > tbody > tr.row-detail').each(function(){
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

function reordertablePotong(){
    var row = 0;
    $('#table-detail > tbody > tr.row-detail-potong').each(function(){
        $(this).find("#no_urut").val(row+1);
        $(this).find("span.no_urut").text(row+1);
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 4){
                $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]+"_"+old_name_arr[3]);
                $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]["+old_name_arr[3]+"]");
            }
        });

        $(this).find("[id^='label-grade-ii-']").each(function(){
            var labelId = $(this).attr("id");
            var labelId_arr = labelId.split("-");
            var newId = labelId.replace(/label-grade-ii-\d+/, "label-grade-" + row + '-' + labelId_arr[3]);
            $(this).attr("id", newId);
        });

        row++;
    });
    formconfig();
}

function emptyTable(){
    $('#table-detail tbody').empty();
}

function removeNonLetters(input){
    input.value = input.value.replace(/[^a-zA-Z]/g, '')
}

function setJmlPotong(ele){
    var tr = $(ele).closest('tr');
    var panjang = tr.find('input[name*="panjang"]').val();

    if(tr.find('input[name*="potong"]').is(":checked")){
        tr.find('input[name*="jumlah_potong"]').val(2);
        tr.find('#btn-min-cut').attr('onclick', 'removePotongan(this);').css({'cursor': 'pointer'});
        tr.find('#btn-add-cut').attr('onclick', 'addPotongan(this);').css({'cursor': 'pointer'});
    } else {
        tr.find('input[name*="jumlah_potong"]').val(1);
        $('#table-potong').find('#TPemotonganLogDetailPotong_0_0_panjang_baru').val(panjang);
        tr.find('#btn-min-cut').attr('onclick', 'return false;').css({'cursor': 'not-allowed'});
        tr.find('#btn-add-cut').attr('onclick', 'return false;').css({'cursor': 'not-allowed'});
    }

    var no_lap = tr.find('#no_lap').val(); 
    var jmlPotong = tr.find('input[name*="jumlah_potong"]').val();

    var jumlah = parseInt(jmlPotong) || 0;
    
    var a = jumlah - 1;
    
    // var tbody = $('#table-potong tbody');
    var tbody = tr.next('.row-detail-potong').find('tbody');
    tbody.empty();
    tampilanMinPotong(a, ele, jmlPotong,no_lap,panjang);
}

function tampilanMinPotong(i, ele, jml, no_lap, panjang){
    var tr = $(ele).closest('tr');
    var cut = tr.find('input[name*="potong"]').is(":checked")?'true':'false';

    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/pemotonganlog/tampilanMinPotong']); ?>',
        type   : 'POST',
        data   : {i: i, jml:jml, no_lap:no_lap, cut:cut,panjang:panjang}, 
        success: function (data){
            if(data.html){
                var table = $(ele).closest('tr').next('.row-detail-potong').find('#table-potong tbody');
                $(data.html).hide().appendTo(table).fadeIn(200,function(){
					reordertablePotong();
                    moveTab();
				});
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

</script>