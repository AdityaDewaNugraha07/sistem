<?php
/* @var $this yii\web\View */

use app\assets\DatatableAsset;
//use app\assets\DatepickerAsset;
use app\assets\InputMaskAsset;
use app\assets\Select2Asset;
use app\assets\WebcodecamAsset;
use app\models\MMenu;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var array $spmDropdown
 * @var array $gudangDropdown
 */

$this->title = 'Scan Produk Kembali';
//DatepickerAsset::register($this);
Select2Asset::register($this);
WebcodecamAsset::register($this);
InputMaskAsset::register($this);
DatatableAsset::register($this);
?>
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<?= Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert') ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <div class="row" style="text-align: center;">
                                    <div class="col-md-6" style="margin-bottom: 15px !important;">
                                        <?= Html::dropDownList('spm_ko_id', null, $spmDropdown, [
                                            'class' => 'form-control select2',
                                            'prompt' => '',
                                            'style' => 'width:100%;'
                                        ]) ?>
                                    </div>
                                    <div class="col-md-6" style="margin-bottom: 15px !important;">
                                        <?= Html::dropDownList('gudang_id', null, $gudangDropdown, [
                                            'class' => 'form-control select2',
                                            'prompt' => '',
                                            'style' => 'width:100%;',
                                        ]) ?>
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
                                            <label for="camera-select"></label>
                                            <select class="form-control" id="camera-select"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="text-align: center;">
                                    <div class="col-md-12" style="margin-top: -15px; display: none;"
                                         id="place-btnenabled">
                                        <a id="play" class="btn hijau btn-sm"><i class="fa fa-play"></i></a>
                                        <a id="pause" class="btn yellow btn-sm"><i class="fa fa-pause"></i></a>
                                        <a id="stop" class="btn red-flamingo btn-sm"><i class="fa fa-stop"></i></a>
                                    </div>
                                    <div class="col-md-12" style="margin-top: -15px; display: none;"
                                         id="place-btndisabled">
                                        <a id="" class="btn grey btn-sm" style="cursor: not-allowed;"><i
                                                    class="fa fa-play"></i></a>
                                        <a id="" class="btn grey btn-sm" style="cursor: not-allowed;"><i
                                                    class="fa fa-pause"></i></a>
                                        <a id="" class="btn grey btn-sm" style="cursor: not-allowed;"><i
                                                    class="fa fa-stop"></i></a>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-hover"
                                               id="table-detail-produklist">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th><?= Yii::t('app', 'Kode Barang Jadi') ?></th>
                                                <th><?= Yii::t('app', 'Produk') ?></th>
                                                <th><?= Yii::t('app', 'Lokasi Gudang') ?></th>
                                                <th><?= Yii::t('app', 'Referensi') ?></th>
                                                <th><?= Yii::t('app', 'Qty Kecil') ?></th>
                                                <th><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                                <th><?= Yii::t('app', 'Scan At') ?></th>
                                                <th><?= Yii::t('app', 'Scan By') ?></th>
                                                <th><?= Yii::t('app', 'Actions') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
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
<?php $this->registerJs("main();", View::POS_READY); ?>
<script>
    function main() {
        reading();
        setMenuActive('<?= json_encode(MMenu::getMenuByCurrentURL('Scan Produk Kembali'))?>',7);
        setScanner();
        const elSpm = $('select[name=spm_ko_id]');
        const elGudang = $('select[name=gudang_id]')

        elSpm.select2({
            allowClear: !0,
            placeholder: 'Ketik Nomor Referensi',
        });

        elGudang.select2({
            allowClear: !0,
            placeholder: 'Pilih Gudang',
        });

        elSpm.on('change', function () {
            setScanner();
            if(window.tableProduk === undefined) {
                window.tableProduk = getItemScanned();
            }
            window.tableProduk.ajax.reload();
        });

        elGudang.on('change', function () {
            setScanner();
            if(window.tableProduk === undefined) {
                window.tableProduk = getItemScanned();
            }
            window.tableProduk.ajax.reload();
        });
    }

    function getItemScanned() {
        return $('#table-detail-produklist').DataTable({
            ajax: {
                url: '<?= Url::toRoute(['/gudang/scanprodukkembali/index']) ?>',
                type: 'POST',
                data: function (d) {
                    d.spm_ko_id = $('select[name=spm_ko_id]').val();
                }
            },
            columns: [
                {
                    target: 0,
                    class: 'td-kecil text-center',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    target: 1,
                    class: 'td-kecil text-center',
                },
                {
                    target: 2,
                    class: 'td-kecil',
                },
                {
                    target: 3,
                    class: 'td-kecil text-center',
                },
                {
                    target: 4,
                    class: 'td-kecil text-center',
                },
                {
                    target: 5,
                    class: 'td-kecil text-center',
                },
                {
                    target: 6,
                    class: 'td-kecil text-center',
                },
                {
                    target: 7,
                    class: 'td-kecil text-center',
                },
                {
                    target: 8,
                    class: 'td-kecil text-center',
                },
                {
                    target: 9,
                    class: 'td-kecil text-center',
                    render: function(data, type, row) {
                        let button;
                        if(data === 'PROCESS') {
                            button = `<a class="btn btn-xs red" onclick="hapusItem(${row[0]});" title="Hapus"><i class="fa fa-trash-o"></i></a>`;
                        }else{
                            button = `<a class="btn btn-xs grey" title="Hapus" disabled><i class="fa fa-trash-o"></i></a>`;
                        }
                        return button;
                    }
                },

            ],
            drawCallback: function (settings) {
                $('#'+settings.sTableId+'_wrapper')
                    .find('.dataTables_moreaction')
                    .html(`
                    <a class='btn btn-icon-only btn-default tooltips btn-refresh' onclick="reload()" data-original-title='Reload'>
                        <i class='fa fa-refresh'></i>
                    </a>
                `);
            }
        });
    }

    function reload() {
        window.tableProduk.ajax.reload();
    }

    function setScanner() {
        const spm_ko_id = $("select[name=spm_ko_id]").val();
        const gudang_id = $("select[name=gudang_id]").val();
        if (!spm_ko_id || !gudang_id) {
            $("#place-btnenabled").attr("style", "display:none;");
            $("#place-btndisabled").attr("style", "");
        } else {
            $("#place-btnenabled").attr("style", "");
            $("#place-btndisabled").attr("style", "display:none;");
        }
    }

    function reading() {
        function Q(el) {
            if (typeof el === "string") {
                const els = document.querySelectorAll(el);
                return typeof els === "undefined" ? undefined : els.length > 1 ? els : els[0];
            }
            return el;
        }

        const txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
        const scannerLaser = Q(".scanner-laser"),
            // play = Q("#play"),
            scannedQR = Q("#scanned-QR");
            // pause = Q("#pause"),
            // stop = Q("#stop");

        const args = {
            beep: '/' + window.location.pathname.split('/')[1] + '/' + window.location.pathname.split('/')[2] + '/themes/metronic/global/plugins/webcodecam/audio/beep.mp3',
            decoderWorker: '/' + window.location.pathname.split('/')[1] + '/' + window.location.pathname.split('/')[2] + '/themes/metronic/global/plugins/webcodecam/DecoderWorker.js',
            autoBrightnessValue: 100,
            zoom: 1.5,
            width: 280,
            height: 210,
            resultFunction: function (res) {
                [].forEach.call(scannerLaser, function (el) {
                    el.style.opacity = 1;
                    (function fade() {
                        if ((el.style.opacity -= 0.1) < 0.5) {
                            el.style.display = "none";
                            el.classList.add("is-hidden");
                        } else {
                            requestAnimationFrame(fade);
                        }
                    })();
                    setTimeout(function () {
                        if (el.classList.contains("is-hidden")) {
                            el.classList.remove("is-hidden");
                        }
                        el.style.opacity = 0;
                        el.style.display = "block";
                        (function fade() {
                            let val = parseFloat(el.style.opacity);
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
            getDevicesError: function (error) {
                let p, message = "Error detected with the following parameters:\n";
                for (p in error) {
                    message += p + ": " + error[p] + "\n";
                }
                alert(message);
            },
            getUserMediaError: function (error) {
                let p, message = "Error detected with the following parameters:\n";
                for (p in error) {
                    message += p + ": " + error[p] + "\n";
                }
                alert(message);
            },
            cameraError: function (error) {
                let p, message = "Error detected with the following parameters:\n";
                if (error.name === "NotSupportedError") {
                    const ans = confirm("Your browser does not support getUserMedia via HTTP!\n(see: https:goo.gl/Y0ZkNV).\n You want to see github demo page in a new window?");
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
            cameraSuccess: function () {
//            grabImg.classList.remove("disabled");
            }
        };

        const decoder = new WebCodeCamJS("#webcodecam-canvas").buildSelectMenu("#camera-select", "environment|back").init(args);
        $("#play").on("click", function () {
            if (!decoder.isInitialized()) {
                scannedQR[txt] = "Scanning ...";
            } else {
                scannedQR[txt] = "Scanning ...";
                decoder.play();
            }
        });
        $("#pause").on("click", function () {
            scannedQR[txt] = "Paused";
            decoder.pause();
        });
        $("#stop").on("click", function () {
            scannedQR[txt] = "Stopped";
            decoder.stop();
        });
//	$("#play").trigger("click");
    }

    function selesai_reading() {
        $("#pause").trigger("click");
    }

    function pick(nomor_produksi) {
        const spm_ko_id = $("select[name=spm_ko_id]").val();
        const gudang_id = $("select[name=gudang_id]").val();
        $.ajax({
            url: '<?= Url::toRoute(['/gudang/scanprodukkembali/showDetail']) ?>',
            type: 'POST',
            data: {
                spm_ko_id: spm_ko_id,
                nomor_produksi: nomor_produksi
            },
            success: function (data) {
                if (data) {
                    if (data['msg'] === "Data ok") {
                        openModal(`<?= Url::toRoute(['/gudang/scanprodukkembali/review', 'spm_ko_id' => '']) ?>${spm_ko_id}&nomor_produksi=${nomor_produksi}&gudang_id=${gudang_id}`, 'modal-review', '300px');
                    } else {
                        cisAlert(data['msg']);
                    }
                }
            },
            error: function (jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function hapusItem(id) {
        openModal('<?= Url::toRoute(['/gudang/scanprodukkembali/hapusProdukKembali', 'id' => '']) ?>' + id, 'modal-delete-record');
    }
</script>