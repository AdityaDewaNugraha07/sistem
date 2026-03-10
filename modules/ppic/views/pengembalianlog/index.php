<?php
/* @var $this yii\web\View */
$this->title = 'Pengembalian Log';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\WebcodecamAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Pengembalian Log</h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions' => ['class' => 'col-md-4 control-label'],
    ],
]);
echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
    .modal-body {
        max-height: 400px;
        overflow-y: auto;
    }

    .table-laporan,
    .table-laporan>tbody>tr>td,
    .table-laporan>tbody>tr>th,
    .table-laporan>tfoot>tr>td,
    .table-laporan>tfoot>tr>th,
    .table-laporan>thead>tr>td,
    .table-laporan>thead>tr>th {
        border: 1px solid #B8BBBE;
        line-height: 1.2 !important;
        font-size: 1.2rem;
    }

    .select2-selection__rendered {
        text-align: left;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <span id="msg" class="pull-left"></span>
                        <a class="btn blue btn-sm btn-outline pull-right" onclick="daftarScanned()"><i
                                class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Scanned'); ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <div class="row" style="text-align: center;">
                                    <div class="col-md-6">
                                        <?php
										if(!isset($_GET['pengembalian_log_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%;font-weight:bold', 'readonly'=>true]) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        <?= $form->field($model, 'tanggal')->textInput(['disabled' => 'disabled']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <div class="row" style="text-align: center; padding-bottom: 10px;">
									<div class="col-md-12" id="place-inputmanual">
										<a class="btn blue btn-sm btn-outline pull-right" onclick="inputManual()"
											style="margin-right: 5px;"><i class="fa fa-edit"></i>
											<?= Yii::t('app', 'Input Manual'); ?></a>
									</div>
                                    <div class="col-md-12" id="place-inputmanual-dis">
										<a class="btn grey btn-sm btn-outline pull-right"
											style="margin-right: 5px; cursor: not-allowed;"><i class="fa fa-edit"></i>
											<?= Yii::t('app', 'Input Manual'); ?></a>
									</div>
								</div>
                                <div class="row" style="text-align: center;">
                                    <div class="col-md-12">
                                        <div class="well" style="position: relative;display: inline-block;">
                                            <canvas id="webcodecam-canvas" style="width: 250px; height: 250px;"></canvas>
                                            <div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
                                            <div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
                                            <div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
                                            <div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
                                        </div>
                                        <div class="row" style="display: none;">
                                            <p id="scanned-QR" class="text-align-center"></p>
                                            <select class="form-control" id="camera-select"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="text-align: center;">
                                    <div class="col-md-12" style="margin-top: -15px;" id="place-btnenabled">
                                        <a id="play" class="btn hijau btn-sm"><i class="fa fa-play"></i></a>
                                        <a id="pause" class="btn yellow btn-sm"><i class="fa fa-pause"></i></a>
                                        <a id="stop" class="btn red-flamingo btn-sm"><i class="fa fa-stop"></i></a>
                                    </div>
                                    <div class="col-md-12" style="margin-top: -15px; display: none;" id="place-btndisabled">
                                        <a id="" class="btn grey btn-sm" style="cursor: not-allowed;"><i class="fa fa-play"></i></a>
                                        <a id="" class="btn grey btn-sm" style="cursor: not-allowed;"><i class="fa fa-pause"></i></a>
                                        <a id="" class="btn grey btn-sm" style="cursor: not-allowed;"><i class="fa fa-stop"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="margin-top: -10px; margin-bottom: -20px;">
                                <h5>Jumlah Log : <span id="place-totalpcs"></span> <i>Pcs</i></h5>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-md-12">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-advance table-hover table-laporan" style="width: 90%; border: 1px solid #A0A5A9;" id="table-detail">
										<thead>
											<tr>
												<th rowspan="2" style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
                                                <th rowspan="2" style="width: 100px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
												<th rowspan="2" style="width: 100px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'No. QR Code<br>Lapangan<br>Grade<br>Batang'); ?></th>
                                                <th colspan="10" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Ukuran Log'); ?></th>
                                                <th rowspan="2" style="width: 120px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Alasan<br>Pengembalian'); ?></th>
												<th rowspan="2" style="width: 50px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Hapus'); ?></th>
											</tr>
											<tr>
												<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Panjang (m)'); ?></th>
												<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', '⌀<br>Ujung1'); ?></th>
												<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', '⌀<br>Ujung2'); ?></th>
                                                <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', '⌀<br>Pangkal1'); ?></th>
                                                <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', '⌀<br>Pangkal2'); ?></th>
												<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', '⌀<br>Rata'); ?></th>
                                                <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Cacat<br>Panjang'); ?></th>
                                                <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Cacat<br>Gb'); ?></th>
                                                <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Cacat<br>Gr'); ?></th>
												<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Volume'); ?></th>
											</tr>
										</thead>
										<tbody>
													
										</tbody>
										<tfoot>
											<tr style="background-color: #F1F4F7;">
												<td colspan="12" style="text-align: right;">Total &nbsp; </td>
												<td id="place-totalkubikasi" class="text-align-right"></td>
												<td colspan="8"></td>
											</tr>
										</tfoot>
									</table>
								</div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Save'), ['id' => 'btn-save', 'class' => 'btn hijau btn-outline ciptana-spin-btn', 'onclick' => 'save();']); ?>
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Reset'), ['id' => 'btn-reset', 'class' => 'btn grey-gallery btn-outline ciptana-spin-btn', 'onclick' => 'resetForm();']); ?>
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
if(isset($_GET['pengembalian_log_id'])){
    $pagemode = "afterSave(".$_GET['pengembalian_log_id'].")";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs("
    $pagemode 
	formconfig();
    setScanner();
    reading();
	setMenuActive('" . json_encode(app\models\MMenu::getMenuByCurrentURL('Pengembalian Log')) . "');
", yii\web\View::POS_READY); ?>
<script>
function setScanner(){
	<?php if(isset($_GET['pengembalian_log_id']) && !isset($_GET['edit'])){ ?>
		$("#place-btnenabled").attr("style","display:none;");
		$("#place-btndisabled").attr("style","");
		$("#place-inputmanual").attr("style","display:none;");
		$("#place-inputmanual-dis").attr("style","");
	<?php }else{ ?>
		$("#place-btnenabled").attr("style","");
		$("#place-btndisabled").attr("style","display:none;");
		$("#place-inputmanual").attr("style","");
		$("#place-inputmanual-dis").attr("style","display:none;");
	<?php } ?>
}

function reading() {
    function Q(el) {
        if (typeof el === "string") {
            var els = document.querySelectorAll(el);
            return typeof els === "undefined" ? undefined : els.length > 1 ? els : els[0];
        }
        return el;
    }
    var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
    var scannerLaser = Q(".scanner-laser"),
        play = Q("#play"),
        scannedQR = Q("#scanned-QR"),
        pause = Q("#pause"),
        stop = Q("#stop");

    var args = {
        beep: '/' + window.location.pathname.split('/')[1] + '/' + window.location.pathname.split('/')[2] +
            '/themes/metronic/global/plugins/webcodecam/audio/beep.mp3',
        decoderWorker: '/' + window.location.pathname.split('/')[1] + '/' + window.location.pathname.split('/')[2] +
            '/themes/metronic/global/plugins/webcodecam/DecoderWorker.js',
        autoBrightnessValue: 100,
        zoom: 1.5,
        width: 280,
        height: 210,
        resultFunction: function(res) {
            [].forEach.call(scannerLaser, function(el) {
                el.style.opacity = 1;
                (function fade() {
                    if ((el.style.opacity -= 0.1) < 0.5) {
                        el.style.display = "none";
                        el.classList.add("is-hidden");
                    } else {
                        requestAnimationFrame(fade);
                    }
                })();
                setTimeout(function() {
                    if (el.classList.contains("is-hidden")) {
                        el.classList.remove("is-hidden");
                    }
                    el.style.opacity = 0;
                    el.style.display = "block";
                    (function fade() {
                        var val = parseFloat(el.style.opacity);
                        if (!((val += 0.1) > 0.5)) {
                            el.style.opacity = val;
                            requestAnimationFrame(fade);
                        }
                    })();
                }, 300);
            });
            scannedQR[txt] = res.format + ": " + res.code;
            pick(res.code);
            selesai_reading();
        },
        getDevicesError: function(error) {
            var p, message = "Error detected with the following parameters:\n";
            for (p in error) {
                message += p + ": " + error[p] + "\n";
            }
            alert(message);
        },
        getUserMediaError: function(error) {
            var p, message = "Error detected with the following parameters:\n";
            for (p in error) {
                message += p + ": " + error[p] + "\n";
            }
            alert(message);
        },
        cameraError: function(error) {
            var p, message = "Error detected with the following parameters:\n";
            if (error.name == "NotSupportedError") {
                var ans = confirm(
                    "Your browser does not support getUserMedia via HTTP!\n(see: https:goo.gl/Y0ZkNV).\n You want to see github demo page in a new window?"
                    );
                if (ans) {
                    window.open("https://andrastoth.github.io/webcodecamjs/");
                }
            } else {
                for (p in error) {
                    message += p + ": " + error[p] + "\n";
                }
                alert(message);
            }
        },
        cameraSuccess: function() {}
    };

    var decoder = new WebCodeCamJS("#webcodecam-canvas").buildSelectMenu("#camera-select", "environment|back").init(
        args);
    $("#play").on("click", function() {
        if (!decoder.isInitialized()) {
            scannedQR[txt] = "Scanning ...";
        } else {
            scannedQR[txt] = "Scanning ...";
            decoder.play();
        }
    });
    $("#pause").on("click", function() {
        scannedQR[txt] = "Paused";
        decoder.pause();
    });
    $("#stop").on("click", function() {
        scannedQR[txt] = "Stopped";
        decoder.stop();
    });
}

function selesai_reading() {
    $("#pause").trigger("click");
}

function pick(datas) {
	var posisiNo = datas.indexOf("No : ");
	var no_barcode = datas.substring(posisiNo + 5); // +6 untuk melewati "No : " yang memiliki 6 karakter
    var edit = "<?= isset($_GET['edit'])?$_GET['edit']:''; ?>";
    var id = "<?= isset($_GET['pengembalian_log_id'])?$_GET['pengembalian_log_id']:''; ?>";

    if ($("#table-detail").find("input[name*='no_barcode'][value='" + no_barcode + "']").length > 0) {
        cisAlert("Barcode " + no_barcode + " sudah ada di dalam daftar.");
        return;
    }

    $.ajax({
        url: '<?= \yii\helpers\Url::toRoute(['/ppic/pengembalianlog/showDetail']); ?>',
        type: 'POST',
        data: {
            datas: datas, no_barcode:no_barcode, edit:edit, id:id
        },
        success: function(data) {
            if (data) {
                if (data['msg'] == "Data ok") {
                    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/pengembalianlog/review','no_barcode'=>'']) ?>'+no_barcode,'modal-review','95%');
                } else {
                    cisAlert(data['msg']);
                }
            }
        },
        error: function(jqXHR) {
            getdefaultajaxerrorresponse(jqXHR);
        },
    });
}

function inputManual() {
    var edit = "<?= isset($_GET['edit'])?$_GET['edit']:''; ?>";
    var id = "<?= isset($_GET['pengembalian_log_id'])?$_GET['pengembalian_log_id']:''; ?>";
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/pengembalianlog/inputManual', 'id'=>'']) ?>'+id+'&edit='+edit, 'modal-inputManual', '60%');
}

function total(){
    var totalVolume = 0;
    var totalPcs = 0;
    $('#table-detail tbody tr').each(function () {
        // var volVal = <?= $modLog?$modLog->fisik_volume:0; ?>;
        var volVal = $(this).find('.vol').text().trim();
        var volume = parseFloat(volVal) || 0;

        totalVolume += volume;
        totalPcs += 1;
    });

    $('#place-totalkubikasi').text(totalVolume.toFixed(2));
    $('#place-totalpcs').text(totalPcs);
   
}

function save(){
    var form = $('#form-transaksi');
	var jumlah_item = $('#table-detail tbody tr').length;

    if(jumlah_item <= 0){
        cisAlert('Isi detail terlebih dahulu dengan scan barcode log');
        return false;
    } else {
        submitform(form);
    }
}

function daftarScanned(){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/pengembalianlog/daftarScanned']) ?>','modal-aftersave','90%');
}

function afterSave(id){
    getItems(id);
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#btn-save').attr('disabled','');
	<?php if(isset($_GET['edit'])){ ?>
		$('#btn-save').removeAttr('disabled');
		$('#btn-print').attr('disabled','');
	<?php } ?>
}

function getItems(id){
	var edit = "<?= isset($_GET['edit'])?$_GET['edit']:"" ?>";
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/ppic/pengembalianlog/getItems']); ?>',
		type   : 'POST',
		data   : {id:id, edit:edit},
		success: function (data) {
			$('#table-detail > tbody').html("");

			if(data.html){
				$('#table-detail > tbody').html(data.html);
                total();
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function hapusItem(ele) {
    var row = $(ele).closest("tr");
    row.remove();
    reordertable('#table-detail');
    total();
}
</script>