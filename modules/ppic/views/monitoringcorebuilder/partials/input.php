<?php

/**
 * @var TMtrgInOut $model
 */

use yii\helpers\Url;
use yii\bootstrap\Html;
use app\models\MPegawai;
use yii\web\JqueryAsset;
use app\models\TApproval;
use app\models\MDefaultValue;
use yii\bootstrap\ActiveForm;


$form = ActiveForm::begin([
    'id' => 'form-input-core-builder',
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
            <span class="caption-subject hijau bold"><?= Yii::t('app', 'Input Core Builder') ?></span>
        </div>
        <div class="tools">
            <a href="javascript:void(0);" class="fullscreen btn btn-default btn-sm" style="height: auto; top: 0;color: unset; font-size: 12px"> Full Screen</a>
            <a href="javascript:void(0);" class="btn btn-default btn-sm btn-show-history" style="font-family: FontAwesome; height: unset" onclick="openModal('<?= Url::toRoute('/ppic/monitoringcorebuilder/modalhistory') ?>?type=INPUT', 'modal-history', '80%')"><i class="fa fa-undo"></i>
                Riwayat Output
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <?php
                echo $form->field($model, 'kode')->textInput(['readonly' => 'readonly']);
                echo $form->field($model, 'tanggal_kupas', [
                    'template' => '{label}
                        <div class="col-md-8">
                            <div class="input-group date date-picker bs-datetime" data-date-end-date="-0d">{input} 
                                <span class="input-group-addon">
                                    <button class="btn btn-default" type="button">
                                    <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div> 
                            {error}
                        </div>'
                ]);
                ?>

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
                echo $form->field($model, 'shift')->dropDownList(MDefaultValue::getOptionList('plymill-shift'));
                echo $form->field($model, 'jenis_kayu')->dropDownList(MDefaultValue::getOptionList('mrtg-jenis-kayu'));
                ?>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <?php
                echo $form->field($model, 'disiapkan')->dropDownList(MPegawai::getOptionList(), ['readonly' => 'true', 'disabled' => 'disabled']);
                echo $form->field($model, 'diperiksa')->dropDownList(MPegawai::getOptionListMonitoringProduksi('diperiksa'), ['prompt' => '-- Diperiksa --']);
                // echo $form->field($model, 'disetujui')->dropDownList(MPegawai::getOptionListMonitoringProduksi('disetujui'), ['prompt' => '-- Disetujui --']);
                // echo $form->field($model, 'diketahui')->dropDownList(MPegawai::getOptionListMonitoringProduksi('diketahui'), ['prompt' => '-- Diketahui --']);
                ?>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <button type="button" class="btn btn-sm yellow-mint margin-bottom-10" id="btn-add-item" onclick="openModal('<?= Url::toRoute('/ppic/monitoringcorebuilder/modalinputdetail') ?>', 'modal-input-detail', null)">
                    <i class="fa fa-plus"></i> Tambah Item
                </button>
                <table class="table table-bordered table-hover table-desktop" id="table-input-core-builder-detail-desktop">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Unit</th>
                            <th>Grade</th>
                            <th>Tebal</th>
                            <th>Size</th>
                            <th>PCS</th>
                            <th>Volume</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="data-details">

                    </tbody>
                </table>
                <table class="table table-bordered table-hover table-mobile" id="table-input-core-builder-detail-mobile" style="width: 100%;">
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
        echo Html::resetButton('Reset', ['id' => 'btn-reset', 'class' => 'btn btn-default margin-right-10 btn-reset']);
        echo Html::submitButton('Simpan', ['id' => 'btn-save', 'class' => 'btn hijau btn-outline ciptana-spin-btn']);
        ActiveForm::end();
        ?>
    </div>
</div>

<?php
$this->registerCssFile('@web/css/mtrg.css');
$this->registerJSFile('@web/js/mtrg.js', ['depends' => [JqueryAsset::class]]);
$this->registerJs("formconfig(); init();");
?>

<script>
    function init() {
        $('#jam').timepicker({
            minuteStep: 1,
            showMeridian: false,
            showSeconds: true,
            secondStep: 1,
        });

        let fields = [
            "<?= Html::getInputId($model, 'kode') ?>",
            "<?= Html::getInputId($model, 'tanggal_kupas') ?>",
            "<?= Html::getInputId($model, 'tanggal_produksi') ?>",
            "<?= Html::getInputId($model, 'shift') ?>",
            "<?= Html::getInputId($model, 'jenis_kayu') ?>",
            "<?= Html::getInputId($model, 'disiapkan') ?>",
            "<?= Html::getInputId($model, 'diperiksa') ?>",
            "<?= Html::getInputId($model, 'disetujui') ?>",
            "<?= Html::getInputId($model, 'diketahui') ?>"
        ];

        const storage = mtrg.storage('input-core-builder-details');
        storage.clear();
        const detail = new mtrg.Detail('core-builder', 'input');
        detail.renderDetail();

        $('#form-input-core-builder').on('beforeSubmit', function() {
            if ('<?= date('Y-m-d H:i:s') <= date('Y-m-d') . ' 09:00:00' && date('Y-m-d H:i:s') >= date('Y-m-d') . ' 07:00:00' ?>' ===
                '1') {
                if (!confirm(
                        'Data akan di masukan pada setup hari sebelumnya. \nApakah anda yakin ingin melanjutkan?'
                    )) {
                    return false;
                }
            }

            if (storage.get().length > 0) {
                let data = {};
                const details = storage.get().filter(row => delete row.id);
                $(this).serializeArray().forEach(r => data[r.name] = r.value);
                data = {
                    ...data,
                    details: details
                }
                $.post("<?= Url::toRoute('/ppic/monitoringcorebuilder/input') ?>", data)
                    .done(function(result) {
                        if (result.status) {
                            storage.clear();
                            $('#btn-reset').trigger('click');
                            detail.renderDetail();
                        }
                        cisAlert(result.message);
                    })
                    .fail(function(error) {
                        cisAlert(error.message);
                    })
            } else {
                cisAlert('belum ada item yang di isi');
            }
            return false;
        });

        $(document).on('click', function(e) {
            if ($(e.target).hasClass('btn-show')) showData($(e.target).data('id'))
            else if ($(e.target).hasClass('btn-reset')) reset()
            else if ($(e.target).hasClass('btn-koreksi')) openDisable()
        });

        function reset() {
            storage.clear();
            [...fields, 'jam'].forEach(function(e) {
                $('#' + e).prop('disabled', false);
            });
            detail.renderDetail();
            $('#btn-add-item').show();
            $('#btn-save').attr('disabled', false);
            $('.badge-approver').remove();
        }

        function showData(id) {
            $('.modal').modal('toggle');
            $.ajax({
                url: '<?= Url::toRoute('/ppic/monitoringcorebuilder/show') ?>?id=' + id,
                success: function({
                    master,
                    details
                }) {
                    const approval = JSON.parse(master['reason_approval']);
                    storage.set(details.map((d, i) => ({
                        ...d,
                        id: i + 1
                    })));
                    fields.forEach(function(e) {
                        const field = e.split('-')[1];
                        if (field === 'tanggal_kupas') {
                            $(`#${e}`).val(formatDateForUser(master[field])).prop('disabled', true);
                        } else {
                            $(`#${e}`).val(master[field]).prop('disabled', true);
                        }
                    })

                    const tgl = master['tanggal_produksi'].split(" ")[0];
                    const time = master['tanggal_produksi'].split(" ")[1];
                    $('#jam').timepicker('setTime', time).attr('disabled', true);
                    $('#tanggal').val(formatDateForUser(tgl));

                    let elemen = null;
                    approval.forEach(function(approver) {
                        switch (approver.level) {
                            case 1:
                                elemen = $('#<?= Html::getInputId($model, 'diperiksa') ?>');
                                break;
                            case 2:
                                elemen = $('#<?= Html::getInputId($model, 'disetujui') ?>');
                                break;
                            case 3:
                                elemen = $('#<?= Html::getInputId($model, 'diketahui') ?>');
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
                    });
                    detail.renderDetail();
                    $('#btn-add-item').hide();
                    $('#btn-save').attr('disabled', true);
                    $('.btn-remove').hide();
                    $('.btn-edit').hide();

                    if (master['status_approval'] === '<?= TApproval::STATUS_REJECTED ?>') {
                        $('.btn-koreksi').show();
                        $('#form-input-core-builder').append(
                            `<input type="hidden" name="TMtrgInOut[mtrg_in_out_id]" value="${master['mtrg_in_out_id']}"/>`
                        );
                    }
                }
            });
        }

        function openDisable() {
            $('#jam').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'shift') ?>').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'jenis_kayu') ?>').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'diperiksa') ?>').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'disetujui') ?>').attr('disabled', false);
            $('#<?= Html::getInputId($model, 'diketahui') ?>').attr('disabled', false);
            $('#btn-add-item').show();
            $('#btn-save').attr('disabled', false);
            $('.btn-remove').show();
            $('.btn-edit').show();
            $('.badge-approver').hide();
            $('.btn-koreksi').fadeToggle();
        }
    }
</script>