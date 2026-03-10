<?php
/* @var $this yii\web\View */
$this->title = 'Scan Mutasi Produksi';
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
								<div class="row" style="text-align: center;">
									<div class="col-md-12">
										<?= \yii\bootstrap\Html::activeDropDownList($model, 'pengajuan_repacking_id', \app\models\TPengajuanRepacking::getOptionListScanMutasi(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setScanner();','style'=>'width:100%;']); ?>
									</div>
								</div>
								<br>
								<div class="row" style="text-align: center;">
									<div class="col-md-12">
										<div class="well" style="position: relative;display: inline-block;">
											<canvas id="webcodecam-canvas"></canvas>
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
									<div class="col-md-12" style="margin-top: -15px; display: none;" id="place-btnenabled">
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
                                        <h5>Jumlah Palet : <span class="place-totalbesar"></span></h5>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover table-laporan" style="width: 90%; border: 1px solid #A0A5A9;" id="table-detail-produklist">
												<thead>
													<tr>
														<th style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th style="width: 150px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
														<th style="font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Produk'); ?></th>
														<th style="width: 50px; line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty Kecil'); ?></th>
														<th style="width: 75px; font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
														<th style="width: 50px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Hapus'); ?></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
												<tfoot>
													<tr style="background-color: #F1F4F7;">
														<td colspan="2" style="text-align: right;">Total &nbsp; </td>
														<td class="place-totalbesar" class="text-align-right"></td>
														<td id="place-totalkecil" class="text-align-right"></td>
														<td id="place-totalkubikasi" class="text-align-right"></td>
														<td></td>
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
<?php $this->registerJs(" 
	formconfig();
	$('select[name*=\"[pengajuan_repacking_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Permintaan',
	});
	reading();
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Scan Mutasi Produksi'))."',7);
	setScanner();
", yii\web\View::POS_READY); ?>
<script>
function setScanner(){
	var pengajuan_repacking_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "pengajuan_repacking_id") ?>").val();
	if(!pengajuan_repacking_id){
		$("#place-btnenabled").attr("style","display:none;");
		$("#place-btndisabled").attr("style","");
	}else{
		$("#place-btnenabled").attr("style","");
		$("#place-btndisabled").attr("style","display:none;");
	}
	getItemsScanned(pengajuan_repacking_id);
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
//	$("#play").trigger("click");
}

function selesai_reading(){
    $("#pause").trigger("click");
}

function getItemsScanned(id){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/gudang/scanmutasiproduksi/getItemsScanned']); ?>',
		type   : 'POST',
		data   : {id:id},
		success: function (data) {
			$(".place-totalbesar").html("");
			$("#place-totalkecil").html("");
			$("#place-totalkubikasi").html("");
			if(data.html){
				$('#table-detail-produklist tbody').html(data.html);
				reordertable('#table-detail-produklist');
				setTimeout(function(){
					total();
				},500)
			}else{
				$('#table-detail-produklist tbody').html('<tr><td colspan="6" style="text-align: center;"><i>Data tidak ditemukan</i></td></tr>');
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
	var total_besar = $("#table-detail-produklist tbody tr").length;
	var total_kecil = 0;
	var total_kubikasi = 0;
	$("#table-detail-produklist tbody tr").each(function(){
		total_kecil += unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
		total_kubikasi += unformatNumber( $(this).find('input[name*="[qty_m3]"]').val() );
	});
	$(".place-totalbesar").html( formatNumberForUser(total_besar)+" Palet" );
	$("#place-totalkecil").html( formatNumberForUser(total_kecil));
	$("#place-totalkubikasi").html( formatNumberForUser(total_kubikasi) );
}

function pick(prod_number){
	var pengajuan_repacking_id = $('#<?= \yii\bootstrap\Html::getInputId($model, "pengajuan_repacking_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/scanmutasiproduksi/saveNomorProduksi']); ?>',
        type   : 'POST',
        data   : {pengajuan_repacking_id:pengajuan_repacking_id,prod_number:prod_number},
        success: function (data) {
			if(data.msg){
				cisAlert(data.msg);
			}
			getItemsScanned(pengajuan_repacking_id);
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function hapusItem(ele){
	var pengajuan_repacking_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "pengajuan_repacking_id") ?>").val();
	var mutasi_keluar_id = $(ele).parents("tr").find("input[name*='[mutasi_keluar_id]']").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/gudang/scanmutasiproduksi/deleteNomorProduksi','id'=>'']) ?>'+mutasi_keluar_id,'modal-delete-record');
}

function infoPalet(nomor_produksi){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/spm/infoPalet','nomor_produksi'=>'']) ?>'+nomor_produksi,'modal-info-palet','90%');
}
</script>