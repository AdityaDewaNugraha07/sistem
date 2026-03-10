<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\bootstrap\Html;

$this->title = 'Pengeluaran Log Alam';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\MagnificPopupAsset::register($this);
app\assets\WebcodecamAsset::register($this);

?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
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
    table.table thead tr th {
        font-size: 1.3rem;
        padding: 2px;
        border: 1px solid #A0A5A9;
    }

    table.table#table-detail-permintaan thead tr th {
        padding: 10px;
        border: 1px solid #A0A5A9;
    }

    .table-striped.table-bordered.table-hover.table-bordered>thead>tr>th,
    .table-striped.table-bordered.table-hover2.table-bordered>thead>tr>th,
    .table-striped.table-bordered.table-hover3.table-bordered>thead>tr>th,
    .table-striped.table-bordered.table-hover4.table-bordered>thead>tr>th {
        line-height: 1;
    }

    .add-more:hover {
        background: #58ACFA;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <?php /* LOG KELUAR */ ?>
                <div class="row" id="log_keluar">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold">
                                        <h4> <?= $this->title; ?> </h4>
                                    </span>
                                </div>
                                <div class="tools">
                                    <a class="btn blue btn-sm btn-outline pull-right" style="height: 30px;" onclick="daftarLogKeluar()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Log Alam Keluar'); ?></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 5px;">
                                                <table style="width: 100%">
                                                    <tr>
                                                        <td style="width: 60%"><?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class' => 'form-control', 'style' => 'width:100%; font-weight:bold', 'disabled' => 'disabled', 'placeholder' => 'Auto Generate']) ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <?php
                                        if (empty(isset($_GET['success'])) && empty(isset($_GET['view']))) {
                                        ?>
                                            <?= $form->field($model, 'tanggal', [
                                                'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;" disabled><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'
                                            ])->textInput(['readonly' => 'readonly', 'disabled' => 'disabled']); ?>

                                            <?= $form->field($model, 'cara_keluar')->inline(true)->radioList([true => "Industri", false => "Trading"]); ?>
                                            <?= $form->field($model, 'reff_no')->textInput()->label('Nomor SPK Pengambilan Log', ['id' => 'reff_no_label']); ?>
                                            <?= $form->field($model, 'pic_log_keluar')->dropDownList(\app\models\MPegawai::getOptionList(), ['disabled' => 'disabled']); ?>
                                            <?= $form->field($model, 'keterangan')->textarea(['rows' => '3']) ?>
                                            <?= $form->field($model, 'no_barcode', [
                                                'template' => '{label}<div class="col-md-7" id="no_barcode"><div class="input-group input-medium bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;" onclick="cekDoeloeSoe()"><i class="fa fa-plus"></i></button></span></div> 
                                                                    {error}</div>'
                                            ])->label('Manual QRCode')->textInput(['style' => 'width: 250px;'])->label('
                                                <select style="background-color: transparent;border: none;" id="jenis-input">
                                                    <option value="no_barcode" selected>Manual QR. Code</option>
                                                    <option value="no_lap">Nomor Lapangan</option>
                                                </select>
                                            '); ?>
                                            <br>
                                        <?php
                                        } else {
                                            $model->cara_keluar == 1 ? $cara_keluar = "Industri" : $cara_keluar = "Trading";
                                            $pegawai_nama = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id =" . $model->pic_log_keluar . "")->queryScalar();
                                        ?>
                                            <?= $form->field($model, 'tanggal')->textInput(['id' => 'tlogkeluar-tanggal', 'class' => 'form-control', 'readonly' => true])->label("Tanggal"); ?>
                                            <?= $form->field($model, 'cara_keluar')->textInput(['id' => 'tlogkeluar-cara_keluar', 'class' => 'form-control', 'readonly' => true, 'value' => $cara_keluar])->label("Jenis Peruntukan"); ?>
                                            <?= $form->field($model, 'reff_no')->textInput(['id' => 'tlogkeluar-reff_no', 'class' => 'form-control', 'readonly' => true, 'value' => $model->reff_no])->label("Nomor Nota"); ?>
                                            <?= $form->field($model, 'pic_log_keluar')->textInput(['id' => 'tlogkeluar-pic_log_keluar', 'class' => 'form-control', 'readonly' => true, 'value' => $pegawai_nama])->label("PIC Log Keluar"); ?>
                                            <?= $form->field($model, 'keterangan')->textarea(['rows' => '3', 'value' => $model->keterangan, 'readonly' => 'readonly']); ?>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <?php // SCAN QR CODE 
                                    ?>
                                    <?php
                                    if (empty(isset($_GET['success'])) && empty(isset($_GET['view']))) {
                                    ?>
                                        <div class="col-md-6">
                                            <div class="row" style="text-align: center;">

                                                <div class="col-md-12">
                                                    <div class="well" style="position: relative;display: inline-block; margin-left: -20px; padding: 0px;">
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
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php // EO SCAN QR CODE 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php /* EO LOG KELUAR */ ?>

                <hr>

                <?php /* DETAIL LOG KELUAR */ ?>
                <h4><?= Yii::t('app', 'Daftar Log Alam'); ?></h4>
                <div class="row" style="margin-left: -20px; margin-right: -20px;">
                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                        <div class="table-scrollable">
                            <table class="table-striped table-bordered table-advance table-hover" id="table-detail-logkeluar" style="width: 1600px;">
                                <thead>
                                    <tr>
                                        <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'No.'); ?></th>
                                        <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'No. QR Code'); ?></th>
                                        <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'No. Lapangan'); ?></th>
                                        <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'No. Grade'); ?></th>
                                        <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'No. Batang'); ?></th>
                                        <th style="line-height: 1; width: 300px;"><?= Yii::t('app', 'Nama Kayu'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', 'Panjang'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', 'Kode<br>Potong'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', '⌀<br>Ujung1'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', '⌀<br>Ujung2'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', '⌀<br>Pangkal1'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', '⌀<br>Pangkal2'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', 'Cacat<br>Panjang'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', 'Cacat<br>GB'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', 'Cacat<br>GR'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', 'Volume'); ?></th>
                                        <th style="line-height: 1; width: 70px;"><?= Yii::t('app', 'Status FSC'); ?></th> <!-- TAMBAH FSC -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['success'])) {
                                        echo Yii::$app->controller->renderPartial('_success', ['log_keluar_id' => $_GET['log_keluar_id']]);
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                        <span class="font-red-flamingo pull-right" id="place-warning-overpembelian"></span>
                    </div>
                </div>
                <?php // EO DETAIL LOG KELUAR ;
                ?>

                <hr>
                <div class="row">
                    <div class="form-actions pull-right">
                        <div class="col-md-12 right">
                            <?php if (empty(isset($_GET['success'])) || isset($_GET['edit'])) echo \yii\helpers\Html::button(Yii::t('app', 'Save'), ['id' => 'btn-save', 'class' => 'btn hijau btn-outline ciptana-spin-btn', 'onclick' => 'save();']); ?>
                            <?php // echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>"printout('PRINT')"]); 
                            ?>
                            <?php echo \yii\helpers\Html::button(Yii::t('app', 'Reset'), ['id' => 'btn-reset', 'class' => 'btn grey-gallery btn-outline ciptana-spin-btn', 'onclick' => 'resetForm();']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php \yii\bootstrap\ActiveForm::end(); ?>

<?php 
$script = " 
as();
formconfig();
$('select[name*=\"[pic_log_keluar]\"]').select2({
    allowClear: !0,
    placeholder: 'Ketik Nama Pegawai',
});
";

if(!isset($_GET['success']) && !isset($_GET['edit'])) {
$script .= "reading();";
}
$this->registerJs($script, yii\web\View::POS_READY); ?>

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
            beep: '/' + window.location.pathname.split('/')[1] + '/' + window.location.pathname.split('/')[2] + '/themes/metronic/global/plugins/webcodecam/audio/beep.mp3',
            decoderWorker: '/' + window.location.pathname.split('/')[1] + '/' + window.location.pathname.split('/')[2] + '/themes/metronic/global/plugins/webcodecam/DecoderWorker.js',
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
            cameraSuccess: function() {}
        };

        var decoder = new WebCodeCamJS("#webcodecam-canvas").buildSelectMenu("#camera-select", "environment|back").init(args);
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
            url: '<?= \yii\helpers\Url::toRoute(['/ppic/logkeluar/showDetail']); ?>',
            type: 'POST',
            data: {
                datas: datas
            },
            success: function(data) {
                if (data) {
                    if (data['msg'] == "Log siap dikeluarkan") {
                        var terima_logalam_detail_id = data['terima_logalam_detail_id'];
                        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/logkeluar/review', 'terima_logalam_detail_id' => '']) ?>' + terima_logalam_detail_id, 'modal-review', '80%');
                    } else if (data['msg'] == "Data sudah ada" || data['msg'] == "Data log alam untuk dijual") {
                        var terima_logalam_detail_id = data['terima_logalam_detail_id'];
                        var peruntukan = data['peruntukan'];
                        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/logkeluar/view', 'terima_logalam_detail_id' => '']) ?>' + terima_logalam_detail_id + '&peruntukan=' + peruntukan, 'modal-review', '80%');
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

    function cekDoeloeSoe() {
        var no_barcode = $("#no_barcode").find("input[name*='[no_barcode]']").val();
        if (no_barcode != '') {
            $.ajax({
                url: '<?= \yii\helpers\Url::toRoute(['/ppic/logkeluar/showDetailManual']); ?>',
                type: 'POST',
                data: {
                    keyword: no_barcode,
                    jenis_input: $('#jenis-input').val(),
                },
                success: function(data) {
                    if (data) {
                        if (data['msg'] == "Log siap dikeluarkan") {
                            var terima_logalam_detail_id = data['terima_logalam_detail_id'];
                            openModal('<?= Url::toRoute(['/ppic/logkeluar/review', 'terima_logalam_detail_id' => '']) ?>' + terima_logalam_detail_id, 'modal-review', '80%');
                        } else if (data['msg'] == "Data sudah ada" || data['msg'] == "Data log alam untuk dijual") {
                            var terima_logalam_detail_id = data['terima_logalam_detail_id'];
                            var peruntukan = data['peruntukan'];
                            openModal('<?= Url::toRoute(['/ppic/logkeluar/view', 'terima_logalam_detail_id' => '']) ?>' + terima_logalam_detail_id + '&peruntukan=' + peruntukan, 'modal-review', '80%');
                        } else {
                            cisAlert(data['msg']);
                        }
                    }
                },
                error: function(jqXHR) {
                    getdefaultajaxerrorresponse(jqXHR);
                },
            });
        } else {
            cisAlert('No. barcode kosong');
        }
    }

    function save() {
        var $form = $('#form-transaksi');
        if (formrequiredvalidate($form)) {
            var jumlah_item = $('#table-detail-logkeluar tbody tr').length;
            if (jumlah_item <= 0) {
                cisAlert('Isi detail dulu');
                return false;
            } else {
                submitform($form);
            }
        }
        return false;
    }

    function daftarLogKeluar() {
        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/logkeluar/daftarLogKeluar']) ?>', 'modal-daftarLogKeluar', '90%');
    }

    function deleteItem(log_keluar_id) {
        cisAlert(log_keluar_id);
    }

    /*function tambahKeputusan(){
        openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/openKeputusanPembelianLog']) ?>','modal-keputusanPembelianLog','90%');
    }

    function pickKeputusanPembelianLog(id){
    	$("#modal-keputusanPembelianLog").find('button.fa-close').trigger('click');
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/pickKeputusanPembelianLog']); ?>',
            type   : 'POST',
            data   : {id:id},
            success: function (data){
                if(data){
                    $('#modal-keputusanPembelianLog').modal('hide');
                    var allowadd = true;
                    //TPengajuanPembelianlog[0][pengajuan_pembelianlog_id]
                    $('#table-detail-permintaan > tbody > tr').each(function(){
                        if($(this).find("input[name*='[pengajuan_pembelianlog_id]']").val() != data.pengajuan_pembelianlog_id){
                            allowadd &= true;
                        }else{
                            allowadd = false;
                        }
                    });
                    if(allowadd){
                        $(data.html).hide().appendTo('#table-detail-permintaan > tbody').fadeIn(100,function(){
                            reordertable("#table-detail-permintaan");
                            totalBatangVolume();
                        });
                        var lokasi_muat = $('#tspkshipping-lokasi_muat').val();
                        if (lokasi_muat == "" || lokasi_muat == null) {
                            lokasi_muat = data.lokasi_muat;
                        } else {
                            lokasi_muat = lokasi_muat+", "+data.lokasi_muat;
                        }
                        $('#tspkshipping-lokasi_muat').val(lokasi_muat);
                    }else{
                        cisAlert("Permintaan ini sudah dipilih di list");
                    }
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function totalBatangVolume(){
        var total_batang = 0; var total_m3 = 0; 
        $("#table-detail-permintaan > tbody > tr").each(function(i){
            total_batang += unformatNumber( $(this).find('input[name*="[qty_batang]"]').val() );
            total_m3 += unformatNumber( $(this).find('input[name*="[qty_m3]"]').val() );
            if((i+1) == $("#table-detail-permintaan > tbody > tr").length){
                $("#<?= yii\helpers\Html::getInputId($model, "estimasi_total_batang") ?>").val( total_batang );
                var totals_m3 = total_m3.toFixed(2);
                $("#<?= yii\helpers\Html::getInputId($model, "estimasi_total_m3") ?>").val( totals_m3 );
                $("#place-total-batang").text(total_batang.toLocaleString());
                $("#place-total-volume").text(formatNumberForUser(total_m3.toLocaleString()));
            }
        });
    }

    function hapusPengajuanPembelianLog(ele){
        $(ele).parents('tr').fadeOut(200,function(){
            $(this).remove();
            reordertable('#table-detail-permintaan');
    		totalBatangVolume();
        });
    }

    function getItems(){
        <?php
        if (isset($_GET['spk_shipping_id'])) {
        ?>
        var spk_shipping_id = '<?php echo $_GET['spk_shipping_id']; ?>';
        <?php
        } else {
        ?>
        var spk_shipping_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'spk_shipping_id') ?>').val();
        <?php
        }
        ?>

        <?php
        if (isset($_GET['success'])) {
        ?>
            var success = '<?php echo isset($_GET['success']); ?>';
        <?php
        } else {
        ?>
            var success = 0;
        <?php
        }
        ?>

        <?php
        if (isset($_GET['edit'])) {
        ?>
            var edit = 1;
        <?php
        } else {
        ?>
            var edit = 0;
        <?php
        }
        ?>

    	$.ajax({
    		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/getItems']); ?>',
    		type   : 'POST',
    		data   : {spk_shipping_id:spk_shipping_id, success:success},
    		success: function (data) {
    			$('#table-detail-permintaan > tbody').html("");
    			if(data.html){
    				$('#table-detail-permintaan > tbody').html(data.html);
    			}
    			totalBatangVolume();
    			reordertable('#table-detail-permintaan');
                if (success == '' || edit == 1) {

                } else if ((spk_shipping_id != '' && success == 1) || (spk_shipping_id != '')) {
                    $('form').find("input[name*='[tanggal]']").prop('disabled', true);
                    $('form').find("input[name*='[etd]']").prop('disabled', true);
                    $('form').find("input[name*='[eta_logpond]']").prop('disabled', true);
                    $('form').find("input[name*='[eta]']").prop('disabled', true);
                    $('form').find("input[name*='[reff_no]']").prop('disabled', true);
                    $('.input-group-addon').hide();
                    $('form').find("textarea[name*='[lokasi_muat]']").prop('disabled', true);
                    $('form').find("input[name*='[asuransi]']").prop('disabled', true);
                    $('form').find("select[name*='[pic_shipping]']").prop('disabled', true);
                    $('form').find("textarea[name*='[keterangan]']").prop('disabled', true);
                    $('form').find('.btn').prop('disabled', true);
                    $('form').find('.red').hide();
                }
    		},
    		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    	});
    }

    function viewSpmLog(id) {
        window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/index', 'spk_shipping_id' => '']); ?>'+id+'&success=1');
    }

    function editSpmLog(id) {
        window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/index', 'spk_shipping_id' => '']); ?>'+id+'&edit=1');
    }

    function detailPengajuanPembelianlog(id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/openDetailKeputusanPembelianlog', 'id' => '']) ?>'+id,'modal-detailKeputusanPembelianlog','90%');
    }*/

    function as() {
        $('input:radio[name="TLogKeluar[cara_keluar]"]').change(function() {
            if ($(this).val() == 1) {
                $("#reff_no_label").text("Nomor SPK Pengambilan Log");
            } else {
                $("#reff_no_label").text("Nomor SPM");
            }
        });
    }
</script>