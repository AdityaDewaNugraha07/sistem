<?php app\assets\WebcodecamAsset::register($this); ?>
<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Scan Qr-Code'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-12">
						<div class="well" style="position: relative;display: inline-block;">
							<canvas width="320" height="240" id="webcodecam-canvas"></canvas>
							<div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
							<div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
							<div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
							<div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
						</div>
						<div class="row" style="display: none;">
							<p id="scanned-QR" class="text-align-center"></p>
							<select class="form-control" id="camera-select"></select>
							<button title="Play" class="btn btn-success btn-sm" id="play" type="button" data-toggle="tooltip"><span class="fa fa-play"></span></button>
							<button title="Pause" class="btn btn-warning btn-sm" id="pause" type="button" data-toggle="tooltip"><span class="fa fa-pause"></span></button>
							<button title="Stop streams" class="btn btn-danger btn-sm" id="stop" type="button" data-toggle="tooltip"><span class="fa fa-stop"></span></button>
						</div>
					</div>
				</div>
			</div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/html5-qrcode.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jsqrcode-combined.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    reading();
", yii\web\View::POS_READY); ?>
<script>
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
			stop_reading();
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
	play.addEventListener("click", function() {
        if (!decoder.isInitialized()) {
            scannedQR[txt] = "Scanning ...";
        } else {
            scannedQR[txt] = "Scanning ...";
            decoder.play();
        }
    }, false);
	pause.addEventListener("click", function(event) {
        scannedQR[txt] = "Paused";
        decoder.pause();
    }, false);
    stop.addEventListener("click", function(event) {
        scannedQR[txt] = "Stopped";
        decoder.stop();
    }, false);
	
	$("#play").trigger("click");
}

function stop_reading(){
    $("#stop").trigger("click");
	$("#modal-info").hide();
	$("#modal-info").remove();
	$(".modals-place-2").html("");
	$(".modal-backdrop").remove();
}
</script>