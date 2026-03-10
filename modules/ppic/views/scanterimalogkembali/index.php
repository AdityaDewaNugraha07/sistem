<?php
/* @var $this yii\web\View */
$this->title = 'Scan Terima Log Kembali';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\WebcodecamAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>

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
                        <a class="btn blue btn-sm btn-outline pull-right" onclick="inputManual()"
                            style="margin-right: 5px;"><i class="fa fa-edit"></i>
                            <?= Yii::t('app', 'Input Manual'); ?></a>
                    </div>
                </div>

                <div class="row" style="text-align: left;">
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
                <div class="row" style="text-align: left;">
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
            </div>
        </div>
    </div>
</div>
<style>
.well {
    padding: 5px;
}
;
</style>
<?php $this->registerJs(" 
	formconfig();
	reading();
	setMenuActive('" . json_encode(app\models\MMenu::getMenuByCurrentURL('Scan Penerimaan Log dari Pengembalian Log')) . "');
", yii\web\View::POS_READY); ?>
<script>
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

    $.ajax({
        url: '<?= \yii\helpers\Url::toRoute(['/ppic/scanterimalogkembali/showDetail']); ?>',
        type: 'POST',
        data: {
            datas: datas, no_barcode: no_barcode
        },
        success: function(data) {
            if (data) {
                if (data['msg'] == "Data ok") {
                    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanterimalogkembali/review','no_barcode'=>'']) ?>'+no_barcode+'&id='+data.pengembalian_log_detail_id,'modal-review','95%');
                }  else if (data['msg'] == "Data sudah ada") {
                    var url = "<?= yii\helpers\Url::toRoute("/ppic/scanterimalogkembali/view") ?>?no_barcode="+no_barcode+"&id="+data.pengembalian_log_detail_id;
                    $(".modals-place-3").load(url, function() {
                        $("#modal-review .modal-dialog").css('width','95%');
                        $("#modal-review").modal('show');
                        $("#modal-review").on('hidden.bs.modal', function () {});
                    });
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
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanterimalogkembali/inputManual']) ?>', 'modal-inputManual', '60%');
}

function daftarScanned() {
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanterimalogkembali/daftarScanned']) ?>', 'modal-scanned', '95%');
}
</script>