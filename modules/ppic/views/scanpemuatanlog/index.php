<?php
/* @var $this yii\web\View */
$this->title = 'Scan Pemuatan Log';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\WebcodecamAsset::register($this);
app\assets\InputMaskAsset::register($this);
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
.table-laporan, 
.table-laporan > tbody > tr > td, 
.table-laporan > tbody > tr > th, 
.table-laporan > tfoot > tr > td, 
.table-laporan > tfoot > tr > th, 
.table-laporan > thead > tr > td, 
.table-laporan > thead > tr > th {
    border: 1px solid #B8BBBE; 
	line-height: 1.2 !important;
	font-size: 1.2rem;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
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
										<?= \yii\bootstrap\Html::activeDropDownList($model, 'spm_ko_id', \app\models\TSpmKo::getOptionListScanPemuatanLog(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setScanner();','style'=>'width:100%;']); ?>
									</div>
								</div>
								<br>
								<div class="row" style="text-align: center;">
                                    <div class="col-md-12">
                                        <div class="well" style="position: relative;display: inline-block;">
                                            <canvas id="webcodecam-canvas" style="width: 300px; height: 300px;"></canvas>
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
                                <br>
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: -10px; margin-bottom: -20px;">
                                        <h5>Jumlah Log : <span id="place-totalkecil"></span> <i>Pcs</i></h5>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover table-laporan" style="width: 90%; border: 1px solid #A0A5A9;" id="table-detail-produklist">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
                                                        <th rowspan="2" style="width: 120px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
														<th rowspan="2" style="width: 120px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'No. QR Code<br>Lapangan<br>Grade<br>Batang'); ?></th>
														<!-- <th rowspan="2" style="width: 120px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'No.<br>Lapangan'); ?></th>
														<th rowspan="2" style="width: 120px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'No.<br>Grade'); ?></th>
														<th rowspan="2" style="width: 120px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'No.<br>Batang'); ?></th> -->
                                                        <th colspan="10" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Ukuran Log'); ?></th>
														<th colspan="10" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Ukuran Realisasi Log'); ?></th>
														<th rowspan="2" style="width: 50px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Hapus'); ?></th>
													</tr>
													<tr>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Panjang'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', '⌀<br>Ujung1'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', '⌀<br>Ujung2'); ?></th>
                                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', '⌀<br>Pangkal1'); ?></th>
                                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', '⌀<br>Pangkal2'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', '⌀<br>Rata'); ?></th>
                                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Cacat<br>Panjang'); ?></th>
                                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Cacat<br>Gb'); ?></th>
                                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Cacat<br>Gr'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Volume'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Panjang'); ?></th>
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
														<td colspan="22" style="text-align: right;">Total &nbsp; </td>
														<!-- <td class="place-totalbesar" class="text-align-right"></td> -->
														<!-- <td id="place-totalkecil" class="text-align-right"></td> -->
														<td id="place-totalkubikasi" class="text-align-right"></td>
														<td colspan="19"></td>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<style>
.well {
    padding: 5px;
}

;
</style>
<?php $this->registerJs(" 
	formconfig();
	setScanner();
	$('select[name*=\"[spm_ko_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode SPM',
//		ajax: {
//			url: '".\yii\helpers\Url::toRoute('/marketing/spm/FindSPMAll')."',
//			dataType: 'json',
//			delay: 250,
//			processResults: function (data) {
//				return {
//					results: data
//				};
//			},
//			cache: true
//		}
	});
	reading();
	setMenuActive('" . json_encode(app\models\MMenu::getMenuByCurrentURL('Scan Pemuatan Log')) . "');
", yii\web\View::POS_READY); ?>
<script>
// function setOP(){
// 	var op_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>').val();
//     var spm_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spm_ko_id") ?>').val();
// 	$.ajax({
//         url    : '<?= \yii\helpers\Url::toRoute(['/ppic/scanpemuatanlog/setOPScan']); ?>',
//         type   : 'POST',
//         data   : {spm_ko_id:spm_ko_id},
//         success: function (data) {
// 			$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val('');
// 			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val('');
// 			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val('');
// 			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_pr_nama") ?>").val('');
// 			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_alamat") ?>").val('');
// 			$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val('');
// 			$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_kirim") ?>").val('');
// 			$('#table-detail tbody').html("");
// 			if(data.spm_ko_id){
// 				$("#modal-master").find('button.fa-close').trigger('click');
// 				$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val(data.jenis_produk);
// 				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val(data.cust.cust_id);
// 				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val(data.cust.cust_an_nama);
// 				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_pr_nama") ?>").val(data.cust.cust_pr_nama);
// 				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_alamat") ?>").val(data.cust.cust_pr_alamat ? data.cust.cust_pr_alama : data.cust.cust_an_alamat);
// 				$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val(data.alamat_bongkar);
// 				$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_kirim") ?>").val(data.tanggal_kirim);
// 				// getItems(spm_ko_id);
// 			}
//         },
//         error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
//     });
// }

// function getItems(spm_ko_id){
//     var jns_produk = $("#<?= yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
//     $(".place-satuan-log").css("display","");
//     $.ajax({
// 		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/scanpemuatanlog/getItemsScan']); ?>',
// 		type   : 'POST',
// 		data   : {spm_ko_id:spm_ko_id},
// 		success: function (data) {
// 			if(data.html){
// 				$('#table-detail tbody').html(data.html);
// 			}
// 		},
// 		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
// 	});
// }

function setScanner(){
	var spm_ko_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "spm_ko_id") ?>").val();
	if(!spm_ko_id){
		$("#place-btnenabled").attr("style","display:none;");
		$("#place-btndisabled").attr("style","");
		$("#place-inputmanual").attr("style","display:none;");
		$("#place-inputmanual-dis").attr("style","");
	}else{
		$("#place-btnenabled").attr("style","");
		$("#place-btndisabled").attr("style","display:none;");
		$("#place-inputmanual").attr("style","");
		$("#place-inputmanual-dis").attr("style","display:none;");
	}
	console.log('setscanner '+spm_ko_id);
	getItemsLogScanned(spm_ko_id);
}

function reading(){
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
        
	// if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
	// 	var getUserMedia = navigator.mediaDevices.getUserMedia;
		var args = {
			beep: '/'+window.location.pathname.split( '/' )[1]+'/'+window.location.pathname.split( '/' )[2]+'/themes/metronic/global/plugins/webcodecam/audio/beep.mp3',
			decoderWorker: '/'+window.location.pathname.split( '/' )[1]+'/'+window.location.pathname.split( '/' )[2]+'/themes/metronic/global/plugins/webcodecam/DecoderWorker.js',
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
	//            scannedImg.src = res.imgData;
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
					var ans = confirm("Your browser does not support getUserMedia via HTTP!\n(see: https:goo.gl/Y0ZkNV).\n You want to see github demo page in a new window?");
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
			cameraSuccess: function() {
	//            grabImg.classList.remove("disabled");
			}
		};
		
		var decoder = new WebCodeCamJS("#webcodecam-canvas").buildSelectMenu("#camera-select", "environment|back").init(args);
		$("#play").on("click",function(){
			if (!decoder.isInitialized()) {
				scannedQR[txt] = "Scanning ...";
			} else {
				scannedQR[txt] = "Scanning ...";
				decoder.play();
			}
		});
		$("#pause").on("click",function(){
			scannedQR[txt] = "Paused";
			decoder.pause();
		});
		$("#stop").on("click",function(){
			scannedQR[txt] = "Stopped";
			decoder.stop();
		});
	// } else {
	// 	cisAlert('getUserMedia is not supported');
	// }
//	$("#play").trigger("click");
}

function selesai_reading(){
    $("#pause").trigger("click");
}

function getItemsLogScanned(id){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/scanpemuatanlog/getItemsLogScanned']); ?>',
		type   : 'POST',
		data   : {id:id},
		success: function (data) {
			$("#place-totalkecil").html("");
			$("#place-totalkubikasi").html("");
			if(data.html){
				$('#table-detail-produklist tbody').html(data.html);
				reordertable('#table-detail-produklist');
				setTimeout(function(){
					total();
				},500)
			}else{
				$('#table-detail-produklist tbody').html('<tr><td colspan="27" style="text-align: center;"><i>Data tidak ditemukan</i></td></tr>');
			}
			if(data.status == '<?= \app\models\TSpmKo::REALISASI ?>'){
				$("#place-btnenabled").attr("style","display:none;");
				$("#place-btndisabled").attr("style","");
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function total(){
    var total_kubikasi = 0;
	var total_kecil = 0;

	$("#table-detail-produklist tbody tr").each(function(){
        total_kubikasi += unformatNumber( $(this).find('input[name*="[volume]"]').val() );
		total_kecil += unformatNumber( $(this).find('input[name*="[fisik_pcs]"]').val() );
        // total_kubikasi += formatNumberFixed4($(this).find('input[name*="[kubikasi]"]').val(),4);
	});
    $("#place-totalkubikasi").html( formatNumberForUser(total_kubikasi) );
	$("#place-totalkecil").html( formatNumberForUser(total_kecil) );
	console.log(total_kecil);
    // $("#place-totalkubikasi").html( formatNumberFixed4(total_kubikasi) );
}

function pick(no_barcode){
	var spm_ko_id = $('#<?= \yii\bootstrap\Html::getInputId($model, "spm_ko_id") ?>').val();
    var no_barcode = no_barcode;
	var posisiNo = no_barcode.indexOf("No : ");
	var hasil_no_barcode = no_barcode.substring(posisiNo + 5); // +6 untuk melewati "No : " yang memiliki 6 karakter
	// console.log(hasil_no_barcode);
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/scanpemuatanlog/showDetailLog']); ?>',
        type   : 'POST',
        data   : {spm_ko_id:spm_ko_id, no_barcode:hasil_no_barcode, datas:no_barcode},
        success: function (data) {            
			if(data){
                if (data['msg'] == "Data ok") {
                    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanpemuatanlog/reviewLog','spm_ko_id'=>'']) ?>'+spm_ko_id+'&no_barcode='+hasil_no_barcode,'modal-review','280px');
                } else {
                    //openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanpemuatanlog/view','no_barcode'=>'']) ?>'+datas+'&gudang_id='+gudang_id,'modal-review','300px');
                    cisAlert(data['msg']);
                }
            }
			// if(data.log){
			// 	$('#table-review tbody').parents('tr').find("input[name*='[panjang]']").val( data.persediaan.fisik_panjang );
			// }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function hapusItem(ele){
	var spm_ko_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "spm_ko_id") ?>").val();
	var log_keluar_id = $(ele).parents("tr").find("input[name*='[log_keluar_id]']").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanpemuatanlog/deleteNoBarcode','id'=>'']) ?>'+log_keluar_id,'modal-delete-record');
}

function inputManual() {
	var spm_ko_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "spm_ko_id") ?>").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanpemuatanlog/inputManual', 'spm_ko_id'=>'']) ?>'+spm_ko_id, 'modal-inputManual', '60%');
}

</script>