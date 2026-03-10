<?php

/**
 * @var TMtrgInOut $model
 */

use app\models\MDefaultValue;
use app\models\MPegawai;
use app\models\MSuplier;
use app\models\TApproval;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\helpers\Url;


$form = ActiveForm::begin([
    'id' => 'form-input-rotary',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions' => ['class' => 'col-md-4 control-label'],
    ],
]);
?>
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-cogs"></i>
            <span class="caption-subject hijau bold"><?= Yii::t('app', 'Input Rotary') ?></span>
        </div>
        <div class="tools">
            <a href="javascript:void(0);" class="fullscreen btn btn-default btn-sm" style="height: auto; top: 0;color: unset; font-size: 12px"> Full Screen</a>
            <a href="javascript:void(0);" class="btn btn-default btn-sm" style="font-family: FontAwesome; height: unset" onclick="showHistory()"><i class="fa fa-undo"></i> Riwayat Input</a>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <?= $form->field($model, 'kode')->textInput(['readonly' => 'readonly']) ?>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="jam">Tanggal</label>
                    <div class="col-md-4">
                        <input type="text" id="tanggal" class="form-control" name="tanggal" value="<?= date('d/m/Y') ?>" readonly="readonly">
                        <span class="help-block"></span>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" id="jam" class="form-control" name="jam">
                            <span class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <?php
                echo $form->field($model, 'jam_jalan')->textInput(['type' => 'number', 'placeholder' => 'Satuan Menit']);
                echo $form->field($model, 'shift')->dropDownList(MDefaultValue::getOptionList('plymill-shift'));
                echo $form->field($model, 'jenis_kayu')->dropDownList(MDefaultValue::getOptionList('mrtg-jenis-kayu'));
                ?>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <?php
                echo $form->field($model, 'disiapkan')->dropDownList(MPegawai::getOptionList(), ['readonly' => 'readonly', 'disabled' => 'disabled']);
                echo $form->field($model, 'diperiksa')->dropDownList(MPegawai::getOptionListMonitoringProduksi('diperiksa'), ['prompt' => '-- Diperiksa --']);
                // echo $form->field($model, 'disetujui')->dropDownList(MPegawai::getOptionListMonitoringProduksi('disetujui'), ['prompt' => '-- Disetujui --']);
                // echo $form->field($model, 'diketahui')->dropDownList(MPegawai::getOptionListMonitoringProduksi('diketahui'), ['prompt' => '-- Diketahui --']);
                ?>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <button type="button" class="btn btn-sm yellow-mint margin-bottom-10" onclick="addItem()" id="btn-add-item"><i class="fa fa-plus"></i> Tambah Item
                </button>
                <table class="table table-bordered table-hover table-desktop" id="table-input-rotary-detail-desktop">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Suplier</th>
                            <th>Unit</th>
                            <th>Diameter</th>
                            <th>Panjang</th>
                            <th>PCS</th>
                            <th>Volume</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="data-details">

                    </tbody>
                </table>
                <table class="table table-bordered table-hover table-mobile" id="table-input-rotary-detail-mobile" style="width: 100%;">
                    <thead>
                        <tr class="cis-mobile">
                            <th class="text-center" style="width: 10%">No</th>
                            <th>Deskripsi</th>
                            <th class="text-center" style="width: 20%">Action</th>
                        </tr>
                    </thead>
                    <tbody class="data-details">

                    </tbody>
                </table>
                <button class="btn btn-outline green-jungle btn-koreksi" type="button" style="display: none" onclick="openDisable()"><i class="fa fa-pencil-square-o"></i> Koreksi</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col text-right" style="margin-right: 15px">
        <?php
        echo Html::resetButton('Reset', ['id' => 'btn-reset', 'class' => 'btn btn-default margin-right-10']);
        echo Html::submitButton('Simpan', ['id' => 'btn-save', 'class' => 'btn hijau btn-outline ciptana-spin-btn']);
        ActiveForm::end();
        ?>
    </div>
</div>

<?php
$isBetween = (
    date('Y-m-d H:i:s') <= date('Y-m-d') . ' 09:00:00'
    && date('Y-m-d H:i:s') >= date('Y-m-d') . ' 07:00:00'
);
?>

<script>
    (function() {
        // $(".form_datetime").datetimepicker({
        //     autoclose: !0,
        //     isRTL: App.isRTL(),
        //     format: "yyyy-mm-dd hh:ii:ss",
        //     fontAwesome: !0,
        //     pickerPosition: App.isRTL() ? "bottom-right" : "bottom-left"
        // })
        localMain();
    })();

    function localMain() {
        localStorage.removeItem('input-rotary-details');
        $('#jam').timepicker({
            minuteStep: 1,
            showMeridian: false,
            showSeconds: true,
            secondStep: 1,
        });

        generateDetail();
        $('#form-input-rotary').on('beforeSubmit', function() {
            // if ('<?= date('Y-m-d H:i:s') <= date('Y-m-d') . ' 09:00:00' && date('Y-m-d H:i:s') >= date('Y-m-d') . ' 07:00:00' ?>' 
            // === '1') {
            //     if (!confirm(
            //             'Data akan di masukan pada setup hari sebelumnya. \nApakah anda yakin ingin melanjutkan?'
            //         )) {
            //         return false;
            //     }
            // }

            if (<?= json_encode($isBetween) ?>) {
                if (!confirm('Data akan dimasukan pada setup hari sebelumnya.\nApakah anda yakin ingin melanjutkan?')) {
                    return false;
                }
            }

            const localData = localStorage.getItem('input-rotary-details');
            if (localData !== null && JSON.parse(localData).length > 0) {
                const details = JSON.parse(localData).map(row => {
                    return {
                        suplier_id: row['suplier_id'],
                        unit: row['unit'],
                        diameter: row['diameter'],
                        panjang: row['panjang'],
                        pcs: row['pcs'],
                        volume: row['volume'],
                    }
                });
                let data = {};
                $(this).serializeArray().forEach(function(r) {
                    data[r.name] = r.value;
                });
                data = {
                    ...data,
                    details: details
                }
                $.post("<?= Url::toRoute('/ppic/monitoringrotary/input') ?>", data)
                    .done(function(result) {
                        if (result.status) {
                            localStorage.removeItem('input-rotary-details');
                            $('#btn-reset').trigger('click');
                            generateDetail();
                            $('#jam').timepicker('setTime', getCurrentTime());
                        }
                        cisAlert(result.message);
                    })
                    .fail(function(error) {
                        cisAlert(error.message);
                    })
            } else {
                cisAlert('Belum ada item yang di input');
            }
            return false;
        });

        $('button[type=reset]').on('click', function() {
            localStorage.clear();
            $('#<?= Html::getInputId($model, 'kode') ?>').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'tanggal') ?>').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'shift') ?>').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'jenis_kayu') ?>').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'diperiksa') ?>').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'disetujui') ?>').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'diketahui') ?>').attr('disabled', false);
            generateDetail();
            $('#btn-add-item').show();
            $('#btn-save').attr('disabled', false);
            $('.badge-approver').remove();
        })
    }

    function addItem() {
        openModal('<?= Url::toRoute("/ppic/monitoringrotary/modalInputDetail") ?>', 'modal-input-detail', null)
    }

    function removeRow(id) {
        const localData = localStorage.getItem('input-rotary-details');
        if (localData !== null) {
            const newData = JSON.parse(localData).filter(row => row.id !== id);
            localStorage.setItem('input-rotary-details', JSON.stringify(newData));
            generateDetail();
        }
    }

    function generateDetail() {
        $('#table-input-rotary-detail-desktop .data-details').empty();
        $('#table-input-rotary-detail-mobile .data-details').empty();
        const details = localStorage.getItem('input-rotary-details');
        if (details !== null && JSON.parse(details).length > 0) {
            JSON.parse(details).forEach(function(row) {
                $('#table-input-rotary-detail-desktop .data-details').append(`
                    <tr>
                        <td class="text-center">${row['id']}</td>
                        <td>${getSuplierName(row['suplier_id'])}</td>
                        <td>${row['unit']}</td>
                        <td>${row['diameter']}</td>
                        <td>${row['panjang']}</td>
                        <td>${row['pcs']}</td>
                        <td>${row['volume']}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-xs btn-edit" onclick="editRow(${row['id']})"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger btn-xs btn-remove" onclick="removeRow(${row['id']})"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                `);
                $('#table-input-rotary-detail-mobile .data-details').append(`
                    <tr>
                        <td class="text-center" style="vertical-align: middle">${row['id']}</td>
                        <td>
                            <table>
                                <tr>
                                    <td>Unit</td>
                                    <td>&nbsp;: ${row['unit']}</td>
                                </tr>
                                <tr>
                                    <td>Suplier</td>
                                    <td>&nbsp;: ${getSuplierName(row['suplier_id'])}</td>
                                </tr>
                                <tr>
                                    <td>Diameter</td>
                                    <td>&nbsp;: ${row['diameter']}</td>
                                </tr>
                                <tr>
                                    <td>Size</td>
                                    <td>&nbsp;: ${row['panjang']}</td>
                                </tr>
                                <tr>
                                    <td>PCS</td>
                                    <td>&nbsp;: ${row['pcs']}</td>
                                </tr>
                                <tr>
                                    <td>Volume</td>
                                    <td>&nbsp;: ${row['volume']}</td>
                                </tr>
                            </table>
                        </td>
                        <td class="text-center" style="vertical-align: middle">
                            <button type="button" class="btn btn-warning btn-sm  margin-bottom-10 btn-edit" onclick="editRow(${row['id']})"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger btn-sm btn-remove" onclick="removeRow(${row['id']})"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                `);
            });
        } else {
            $('#table-input-rotary-detail-desktop').append(
                '<tr><td colspan="9" style="text-align: center; font-style: italic">Tidak ada item</td></tr>');
            $('#table-input-rotary-detail-mobile').append(
                '<tr><td colspan="3" style="text-align: center; font-style: italic">Tidak ada item</td></tr>');
        }
    }

    function getSuplierName(value) {
        // const items = '<?= Json::encode(MSuplier::getOptionList('LS')) ?>';
        // const data = JSON.parse(items);
        const data = <?= Json::encode(MSuplier::getOptionList('LS')) ?>;

        for (const key in data) {
            if (parseInt(key) === parseInt(value)) {
                return data[value];
            }
        }
    }

    // function showHistory() {
    //     openModal('<?= Url::toRoute('/ppic/monitoringrotary/modalhistoryinput') ?>', 'modal-history-input', '95%');
    // }
    function showHistory() {
        if (typeof openModal === "function") {
            openModal('<?= Url::toRoute('/ppic/monitoringrotary/modalhistoryinput') ?>',
                        'modal-history-input', '95%');
        } else {
            alert("Fungsi openModal belum tersedia.");
        }
    }

    
    function showData(id) {
        $.ajax({
            url: '<?= Url::toRoute('/ppic/monitoringrotary/show') ?>?id=' + id + '&status=IN',
            success: fillInput
        });
    }

    function fillInput(data) {
        const {
            master: {
                kode,
                tanggal,
                shift,
                jenis_kayu,
                disiapkan,
                diperiksa,
                disetujui,
                diketahui,
                reason_approval,
                status_approval,
                mtrg_rotary_id,
                jam_jalan
            },
            details
        } = data;

        const localData = details.map((row, index) => ({
            'id': index + 1,
            'suplier_id': row.suplier_id,
            'unit': row.unit,
            'diameter': row.diameter,
            'panjang': row.panjang,
            'pcs': row.pcs,
            'volume': row.volume,
            'mtrg_rotary_detail_id': row.mtrg_rotary_detail_id
        }));
        const approval = JSON.parse(reason_approval);
        localStorage.removeItem('input-rotary-details');
        localStorage.setItem('input-rotary-details', JSON.stringify(localData));

        const lkode = $('#<?= Html::getInputId($model, 'kode') ?>');
        const ltanggal = $('#<?= Html::getInputId($model, 'tanggal') ?>');
        const lshif = $('#<?= Html::getInputId($model, 'shift') ?>');
        const lkayu = $('#<?= Html::getInputId($model, 'jenis_kayu') ?>');
        const lsiap = $('#<?= Html::getInputId($model, 'disiapkan') ?>');
        const lperiksa = $('#<?= Html::getInputId($model, 'diperiksa') ?>');
        const lsetuju = $('#<?= Html::getInputId($model, 'disetujui') ?>');
        const ltahu = $('#<?= Html::getInputId($model, 'diketahui') ?>');
        const ljam = $('#<?= Html::getInputId($model, 'jam_jalan') ?>');

        lkode.val(kode).attr('disabled', true);
        ltanggal.val(tanggal).attr('disabled', true);
        lshif.val(shift).attr('disabled', true);
        lkayu.val(jenis_kayu).attr('disabled', true);
        lsiap.val(disiapkan).attr('disabled', true);
        lperiksa.val(diperiksa).attr('disabled', true);
        lsetuju.val(disetujui).attr('disabled', true);
        ltahu.val(diketahui).attr('disabled', true);
        ljam.val(jam_jalan).attr('disabled', true);

        const tgl = tanggal.split(" ")[0];
        const time = tanggal.split(" ")[1];
        const date = new Date(tgl);
        $('#jam').timepicker('setTime', time).attr('disabled', true);
        $('#tanggal').val(
            `${date.getDate() < 10 ? '0' + date.getDate() : date.getDate()}/${date.getMonth() < 9 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1)}/${date.getFullYear()}`
        );

        let elemen = null;
        approval.forEach(function(approver) {
            switch (approver.level) {
                case 1:
                    elemen = lperiksa;
                    break;
                case 2:
                    elemen = lsetuju;
                    break;
                case 3:
                    elemen = ltahu;
                    break;
            }

            if (elemen.siblings('.badge-approver').length > 0) {
                elemen.siblings('.badge-approver').remove();
            }

            const text = approver.tanggal_approve ?
                `(${formatDateTimeForUser(approver.tanggal_approve)}${approver.reason ? ' : ' + approver.reason : ''})` :
                '';
            elemen.after(`
                <span class="badge-approver badge badge-roundless badge-${colorForApprover(approver.status)}">
                    ${approver.status} ${text}
                </span>
            `);
        })

        generateDetail();
        $('#btn-add-item').hide();
        $('#btn-save').attr('disabled', true);
        $('.btn-remove').hide();
        $('.btn-edit').hide();
        if (status_approval === '<?= TApproval::STATUS_REJECTED ?>') {
            $('.btn-koreksi').show();
            $('#form-input-rotary').append(
                `<input type="hidden" name="TMtrgRotary[mtrg_rotary_id]" value="${mtrg_rotary_id}"/>`);
        }
    }

    function editRow(id) {
        const storage = JSON.parse(localStorage.getItem('input-rotary-details'));
        const data = storage.filter(row => row.id === id)[0];
        openModal('<?= Url::toRoute("/ppic/monitoringrotary/update") ?>?' + $.param(data), 'modal-input-detail', null)
    }

    function openDisable() {
        $('#jam').attr('disabled', false);
        $('#<?= Html::getInputId($model, 'shift') ?>').attr('disabled', false);
        $('#<?= Html::getInputId($model, 'jenis_kayu') ?>').attr('disabled', false);
        $('#<?= Html::getInputId($model, 'diperiksa') ?>').attr('disabled', false);
        $('#<?= Html::getInputId($model, 'disetujui') ?>').attr('disabled', false);
        $('#<?= Html::getInputId($model, 'diketahui') ?>').attr('disabled', false);
        $('#<?= Html::getInputId($model, 'jam_jalan') ?>').attr('disabled', false);
        $('#btn-add-item').show();
        $('#btn-save').attr('disabled', false);
        $('.btn-remove').show();
        $('.btn-edit').show();
        $('.badge-approver').hide();

        let counter = 2;
        const btn = $('.btn-koreksi');
        const text = btn.html();
        btn.html('Menutup Tombol' + ' (' + 3 + ')');
        const intervalId = setInterval(() => {
            btn.html('Menutup Tombol' + ' (' + counter + ')');
            if (counter === 0) {
                btn.hide();
                btn.html(text);
                clearInterval(intervalId);
            }
            counter--;
        }, 1000)
    }
</script>