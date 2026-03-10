<?php

use app\assets\DatatableAsset;
use app\assets\DatepickerAsset;
use app\models\MDefaultValue;
use app\models\MMtrgSetup;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/** @var MMtrgSetup $model */

DatepickerAsset::register($this);
DatatableAsset::register($this);

$this->title = 'Setup Monitoring';
$this->registerJs("main()");
?>
<h1 class="page-title"> <?php echo Yii::t('app', 'Setup Monitoring'); ?></h1>
<?php $form = ActiveForm::begin([
    'id' => 'form-setup-monitoring',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions' => ['class' => 'col-md-4 control-label'],
    ]
]); ?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>
                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Setup Monitoring') ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        //                        echo $form->field($model, 'tanggal', [
                        //                            'template' => '{label}
                        //                                <div class="col-md-8">
                        //                                    <div class="input-group date date-picker bs-datetime" data-date-end-date="-0d">{input}
                        //                                        <span class="input-group-addon">
                        //                                            <button class="btn btn-default" type="button">
                        //                                            <i class="fa fa-calendar"></i>
                        //                                            </button>
                        //                                        </span>
                        //                                    </div>
                        //                                    {error}
                        //                                </div>',
                        //                        ])->textInput(['disabled' => 'disabled']);
                        echo $form->field($model, 'tanggal')->textInput(['disabled' => 'disabled']);
                        echo $form->field($model, 'kategori_proses')->dropDownList(MDefaultValue::getOptionList('kategori-proses'), ['prompt' => '-- Pilih Kategori Proses --']);
                        echo $form->field($model, 'jenis_proses')->dropDownList(['INPUT' => 'INPUT', 'OUTPUT' => 'OUTPUT'], ['prompt' => '-- Pilih Jenis Proses --']);
                        echo $form->field($model, 'jenis_kayu')->dropDownList(MDefaultValue::getOptionList('mrtg-jenis-kayu'), ['prompt' => '-- Pilih Jenis Kayu --']);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        echo $form->field($model, 'grade')->dropDownList([], ['prompt' => '-- Pilih Grade --']);
                        echo $form->field($model, 'plan_harian')->textInput(['type' => 'text', 'step' => '0.001', 'oninput'=>'validateInput(this);']);
                        echo $form->field($model, 'satuan_harian')->dropDownList(MDefaultValue::getOptionList('mtrg-satuan'));
                        echo $form->field($model, 'sequence')->textInput(['type' => 'text', 'oninput'=>'validateInput(this);']);
                        ?>
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
            <?= $this->render('search') ?>
            <?= $this->render('datatable') ?>
        </div>
    </div>
</div>
<?php $this->registerJS('localConfig()') ?>
<script>
    function localConfig() {
        const gradeInput = $('#<?= Html::getInputId($model, 'grade') ?>');
        const kategoriInput = $('#<?= Html::getInputId($model, 'kategori_proses') ?>');
        const jenisInput = $('#<?= Html::getInputId($model, 'jenis_proses') ?>');

        getGrades().then(grades => {
            if (!$.isEmptyObject(grades)) {
                gradeInput.empty().append(optionBuilder(grades));
            }
        }).catch(err => console.log(err));

        kategoriInput.change(async function () {
            gradeInput.prop('disabled', true);
            const grades = await getGrades(kategoriInput.val());
            if (!$.isEmptyObject(grades)) {
                gradeInput.empty().append(optionBuilder(grades));
            }
            gradeInput.prop('disabled', false);
            optionJenisProses(kategoriInput.val(), jenisInput);
        });

        jenisInput.change(async function () {
            gradeInput.prop('disabled', true);
            const grades = await getGrades(kategoriInput.val(), jenisInput.val());
            if (!$.isEmptyObject(grades)) {
                gradeInput.empty().append(optionBuilder(grades));
            }
            gradeInput.prop('disabled', false);
        });

        $('button[type="reset"]').on('click', function () {
            const hidden = $('input[type="hidden"][name="mtrg_setup_id"]');
            if(hidden.length) {
                hidden.remove();
            }
            $('#btn-save').html('Simpan')
        })

    }

    function getGrades(kategori, proses = 'OUTPUT') {
        if (kategori === '') {
            alert('Kategori Proses tidak boleh kosong')
        }

        if (proses === '') {
            alert('Jenis Proses tidak boleh kosong')
        }

        return new Promise((resolve, reject) => {
            $.ajax({
                url: '<?= Url::toRoute('/ppic/monitoringsetup/getgrade')?>?kategori=' + kategori + '&proses=' + proses,
                success: function (res) {
                    resolve(res)
                },
                error: function (err) {
                    reject(err)
                }
            });
        });
    }

    function optionBuilder(grades) {
        let html = '<option>-- Pilih Grade --</option>';
        for (let key in grades) {
            html += `<option value="${key}">${grades[key]}</option>`
        }
        return html;
    }

    function optionJenisProses(kategori, jenisInput) {
        if(['DRYING', 'PLYTECH'].includes(kategori)) {
            jenisInput.html('<option value="OUTPUT">OUTPUT</option>');
        }else {
            jenisInput
                .html(
                    '<option value="">-- Pilih Jenis Proses --</option>'+
                    '<option value="INPUT">INPUT</option>'+
                    '<option value="OUTPUT">OUTPUT</option>'
                )
        }
    }

    function edit(id) {
        $('.btn-edit').prop('disabled', true).html('loading...');
        const hidden = $('input[type="hidden"][name="mtrg_setup_id"]');
        if(hidden.length) {
            hidden.remove();
        }
        $.post('<?= Url::toRoute('/ppic/monitoringsetup/show?mtrg_setup_id=')?>' + id, function (res) {
            const
                tanggal         = $('#<?= Html::getInputId($model, 'tanggal')?>'),
                kategori_proses = $('#<?= Html::getInputId($model, 'kategori_proses')?>'),
                jenis_proses    = $('#<?= Html::getInputId($model, 'jenis_proses')?>'),
                jenis_kayu      = $('#<?= Html::getInputId($model, 'jenis_kayu')?>'),
                grade           = $('#<?= Html::getInputId($model, 'grade')?>'),
                plan_harian     = $('#<?= Html::getInputId($model, 'plan_harian')?>'),
                satuan_harian   = $('#<?= Html::getInputId($model, 'satuan_harian')?>'),
                sequence        = $('#<?= Html::getInputId($model, 'sequence')?>');

            tanggal.val(res.tanggal);
            kategori_proses.val(res.kategori_proses);
            jenis_proses.val(res.jenis_proses);
            jenis_kayu.val(res.jenis_kayu);
            grade.val(res.grade);
            plan_harian.val(res.plan_harian);
            satuan_harian.val(res.satuan_harian);
            sequence.val(res.sequence);

            const inputId = `<input type="hidden" name="mtrg_setup_id" value="${id}"/>`;
            $('#form-setup-monitoring').append(inputId);
            setTimeout(() => {
                $('.btn-edit').prop('disabled', false).html('<i class="fa fa-pencil"></i>');
                $('html, body').animate({scrollTop: 0});
                $('#btn-save').html('Update');
            }, 300)
        });
    }

    function validateInput(ele) {
        var input = ele.value;

        var input_val = '';
        var titik = false;
        var decimalCount = 0; 
        
        for (var i = 0; i < input.length; i++) {
            var char = input.charAt(i);
            
            var nilai = /^[0-9]$/.test(char);
            if (nilai) {
                input_val += char;

                if (titik) {
                    decimalCount++;
                }
            } else if (char === '.' && !titik) {
                input_val += char;
                titik = true;
            }

            if (titik && decimalCount > 3) {
                input_val = input_val.slice(0, -1);  // hapus karakter yg melebihi
                break;  // berhenti proses jika desimal lebih dari 3
            }
        }
        ele.value = input_val;
    }


</script>