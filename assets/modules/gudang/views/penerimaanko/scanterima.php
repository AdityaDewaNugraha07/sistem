<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan Kayu Olahan';
app\assets\DatepickerAsset::register($this);
app\assets\WebcodecamAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
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
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); 
$hiddenTab = ( (\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_STAFF)||(\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_KADEP)||(\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_TALLY) )?"hidden":"";
?>
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
                <ul class="nav nav-tabs">
					<li class="active <?= $hiddenTab ?>">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/penerimaanko/scanterima") ?>"> <?= Yii::t('app', 'Penerimaan Reguler'); ?> </a>
					</li>
					<li class="<?= $hiddenTab ?>">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/penerimaanko/terimarepacking") ?>"> <?= Yii::t('app', 'Penerimaan Hasil Repacking'); ?> </a>
					</li>
                    <li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/penerimaanko/riwayatpenerimaan") ?>"> <?= Yii::t('app', 'Riwayat Penerimaan'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
								<div class="row" style="text-align: center;">
									<div class="col-md-6">
                                        <?= $form->field($model, 'gudang_id')->dropDownList( \app\models\MGudang::getOptionList() ,['class'=>'form-control','prompt'=>'','onchange'=>'setScanner()'])->label("Lokasi Gudang"); ?>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="portlet light bordered">
                            <div class="portlet-body">
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
                                        <h5>Palet Yang Sudah Diterima</h5>
                                    </div>
                                </div><br>
								<div class="row">
                                    <div class="col-md-12">
										<!--<div class="table-scrollable">-->
											<table class="table table-striped table-bordered table-advance table-hover table-laporan" style="width: 100%; border: 1px solid #A0A5A9;" id="table-master">
												<thead>
													<tr>
														<th style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th style="width: 130px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
														<th style="font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Produk'); ?></th>
														<th style="width: 50px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Lokasi<br>Gudang'); ?></th>
														<th style="width: 80px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Pengiriman'); ?></th>
														<th style="width: 50px; line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty Kecil'); ?></th>
														<th style="width: 75px; font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
														<th style="width: 60px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Scan At'); ?></th>
														<th style="width: 60px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Scan By'); ?></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
										<!--</div>-->
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
	reading();
    getItemsScanned();
    setScanner();
", yii\web\View::POS_READY); ?>
<script>
function getItemsScanned(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= yii\helpers\Url::toRoute("/gudang/penerimaanko/getItemsScanned") ?>',data:{dt: 'table-master'} },
//		order: [
//            [1, 'desc'],
//        ],
        "pageLength": 10,
        columnDefs: [
			{ 	targets: 0, class: 'text-align-center td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
            { 	targets: 1, class:"text-align-center td-kecil", 
                render: function ( data, type, full, meta ) {
					return "<a onclick='infoPalet(\""+data+"\")'>"+data+"</a>";
                }
            },
            { 	targets: 2, class:"text-align-left td-kecil", 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            { 	targets: 3, class:"text-align-center td-kecil", 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            { 	targets: 4, class:"text-align-center td-kecil2", 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            { 	targets: 5, class:"text-align-center td-kecil", 
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
            { 	targets: 6, class:"text-align-center td-kecil", 
                render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
                }
            },
            { 	targets: 7, class:"text-align-center td-kecil fontsize-0-9", 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy H:m:s');
					return date;
                }
            },
            { 	targets: 6, class:"text-align-center td-kecil", 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
        ],
        "autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
    });
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

function pick(prod_number){
	var pengajuan_repacking_id = $('#<?= \yii\bootstrap\Html::getInputId($model, "pengajuan_repacking_id") ?>').val();
	var gudang_id = $('#<?= \yii\bootstrap\Html::getInputId($model, "gudang_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/saveNomorProduksi']); ?>',
        type   : 'POST',
        data   : {prod_number:prod_number,gudang_id:gudang_id},
        success: function (data) {
			if(data.msg){
				cisAlert(data.msg);
			}
			$('#table-master').dataTable().fnClearTable();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function hapusItem(id){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/deleteNomorProduksi']); ?>',
        type   : 'GET',
        data   : {id:id},
        success: function (data) {
			if(data.status){
                $('#table-master').dataTable().fnClearTable();
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function infoPalet(nomor_produksi){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/spm/infoPalet','nomor_produksi'=>'']) ?>'+nomor_produksi,'modal-info-palet','90%');
}

function setScanner(){
	var gudang_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "gudang_id") ?>").val();
	if(!gudang_id){
		$("#place-btnenabled").attr("style","display:none;");
		$("#place-btndisabled").attr("style","");
	}else{
		$("#place-btnenabled").attr("style","");
		$("#place-btndisabled").attr("style","display:none;");
	}
}
</script>