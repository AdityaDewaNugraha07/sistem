<?php
/* @var $this yii\web\View */
$this->title = 'Scan Terima Log Alam';
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

                <?php /*<div class="row">
                    <div class="col-md-12" style="margin-top: -10px; margin-bottom: -20px;">
                        <h5>Data Detail Penerimaan Log Alam</h5>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <!--<div class="table-scrollable">-->
                            <?php
                            $form = \yii\bootstrap\ActiveForm::begin([
                                'id' => 'form-transaksi',
                                'fieldConfig' => [
                                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                                    'labelOptions'=>['class'=>'col-md-5 control-label'],
                                ],
                            ]); 
                            ?>
                <table class="table table-striped table-bordered table-advance table-hover table-laporan"
                    style="width: 100%; border: 1px solid #A0A5A9;" id="table-master">
                    <thead>
                        <tr>
                            <th rowspan="2" style="font-size: 1.1rem; 50px;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                            <th colspan="3"><?= Yii::t('app', 'Nomor'); ?></th>
                            <th colspan="2"><?= Yii::t('app', 'Panjang<sup>(m3)</sup></sup>'); ?></th>
                            <th colspan="5"><?= Yii::t('app', 'Diameter'); ?></th>
                            <th colspan="3"><?= Yii::t('app', 'Unsur Cacat'); ?></th>
                            <th rowspan="2" style="width: 50px;"><?= Yii::t('app', 'Vol'); ?></th>
                            <th rowspan="2" style="width: 50px;"><button class="btn btn-md btn-primary" name="simpan"
                                    value="Save" title="Simpan"><i class="fa fa-save "></i></button></th>
                        </tr>
                        <tr>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Lap'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Grades'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Batang'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Kode Potong'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Panjang'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Ujung1'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Ujung2'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Pangkal1'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Pangkal2'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Rata<sup>2</sup>'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Panjang'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'GB'); ?></th>
                            <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'GR'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <?php \yii\bootstrap\ActiveForm::end(); ?>
                <!--</div>-->
            </div>
        </div>
        */ ?>
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
    hapus();
	setMenuActive('" . json_encode(app\models\MMenu::getMenuByCurrentURL('Scan Penerimaan Log Alam')) . "');
", yii\web\View::POS_READY); ?>
<script>
/*function getItemsScanned(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= yii\helpers\Url::toRoute("/ppic/scanterimalogalam/getItemsScanned") ?>',data:{dt: 'table-master'} },
        "pageLength": 10,
        columnDefs: [
			{ 	targets: 0, class: 'text-align-left td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 1, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 2, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 3, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 4, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 5, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 6, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 7, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 8, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 9, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 10, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 11, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 12, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 13, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 14, class: 'text-align-right td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            
        ],
        "autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
    });
}*/

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
    $.ajax({
        url: '<?= \yii\helpers\Url::toRoute(['/ppic/scanterimalogalam/showDetail']); ?>',
        type: 'POST',
        data: {
            datas: datas
        },
        success: function(data) {
            if (data) {
                if (data['msg'] == "Data ok") {
                    var terima_logalam_detail_id = data['terima_logalam_detail_id'];
                    openModal(
                        '<?= \yii\helpers\Url::toRoute(['/ppic/scanterimalogalam/review', 'terima_logalam_detail_id' => '']) ?>' +
                        terima_logalam_detail_id, 'modal-review', '90%');
                } else if (data['msg'] == "Data sudah ada" || data['msg'] == "Data log alam untuk dijual") {
                    var terima_logalam_detail_id = data['terima_logalam_detail_id'];
                    var peruntukan = data['peruntukan'];
                    openModal(
                        '<?= \yii\helpers\Url::toRoute(['/ppic/scanterimalogalam/view', 'terima_logalam_detail_id' => '']) ?>' +
                        terima_logalam_detail_id + '&peruntukan=' + peruntukan, 'modal-review', '90%');
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

function hapus() {
    $('body').on('click', '.hapus', function() {
        $(this).parents('tr').remove();
    });
}

function infoPalet(nomor_produksi) {
    openModal('<?= \yii\helpers\Url::toRoute(['/marketing/spm/infoPalet', 'nomor_produksi' => '']) ?>' + nomor_produksi,
        'modal-info-palet', '90%');
}

function daftarScanned() {
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanterimalogalam/daftarScanned']) ?>', 'modal-scanned', '95%');
}

function inputManual() {
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanterimalogalam/inputManual']) ?>', 'modal-inputManual', '60%');
}
</script>