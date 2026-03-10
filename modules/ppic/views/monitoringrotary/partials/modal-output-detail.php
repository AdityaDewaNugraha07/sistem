<?php

use app\models\MDefaultValue;
use app\models\MMtrgSetup;
use app\models\TMtrgInOutDetail;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/**
 * @var TMtrgInOutDetail $modDetail
 * @var integer $id
 * @var boolean $isUpdate
 */
$grades = [];
try {
    $sql    = "SELECT DISTINCT(grade), sequence FROM m_mtrg_setup WHERE jenis_proses = 'OUTPUT' AND kategori_proses = '" . MMtrgSetup::KATEGORI_ROTARY . "' ORDER BY sequence";
    $grades = Yii::$app->db->createCommand($sql)->queryAll();
    $grades = ArrayHelper::map($grades, 'grade', 'grade');
} catch (\yii\db\Exception $e) {
    echo $e->getMessage();
}

$form = ActiveForm::begin([
    'id' => 'form-output-detail',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions' => ['class' => 'col-md-4 control-label'],
    ],
]);
?>

<div class="modal fade" id="modal-output-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                        aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Output Detail Rotary') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php
                        echo Html::input('hidden', 'id', $id);
                        echo $form->field($modDetail, 'mtrg_in_out_detail_id')->textInput(['type' => 'hidden'])->label(false);
                        echo $form->field($modDetail, 'unit')->dropDownList(MDefaultValue::getOptionList('mtrg-unit-rotary-sengon'), ['prompt' => '-- Pilih Unit --']);
                        echo $form->field($modDetail, 'grade')->dropDownList($grades);
                        echo $form->field($modDetail, 'tebal')->textInput(['type' => 'number', 'step' => '0.001', 'onchange' => 'countVolume()','oninput' => "validateInput(this)",'placeholder' => '0.0']);
                        echo $form->field($modDetail, 'pcs')->textInput(['type' => 'number', 'onchange' => 'countVolume()']);
                        echo $form->field($modDetail, 'size')->dropDownList(MDefaultValue::getOptionList('size'), ['onchange' => 'countVolume()','oninput' => "validateInput(this)"]);
                        echo $form->field($modDetail, 'volume')->textInput(['readonly' => 'readonly']);
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::resetButton('Cancel', ['data-dismiss' => 'modal', 'class' => 'btn btn-default']) ?>
                <?= Html::submitButton($isUpdate ? 'Update' : 'Tambah', ['id' => 'btn-add-detail', 'class' => 'btn hijau btn-outline ciptana-spin-btn']) ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>

<script>
    $('#form-output-detail').on('beforeSubmit', function () {
        let request = {};
        $(this).serializeArray().forEach(function (r) {
            if (r.name !== '_csrf') {
                request[r.name] = r.value;
            }
        });

        let data = {
            id: parseInt(request['id']),
            unit: request['TMtrgInOutDetail[unit]'],
            grade: request['TMtrgInOutDetail[grade]'],
            tebal: request['TMtrgInOutDetail[tebal]'],
            pcs: request['TMtrgInOutDetail[pcs]'],
            size: request['TMtrgInOutDetail[size]'],
            volume: request['TMtrgInOutDetail[volume]'],
            mtrg_in_out_detail_id : request['TMtrgInOutDetail[mtrg_in_out_detail_id]']
        }

        let storage = localStorage.getItem('output-rotary-details')
            ? JSON.parse(localStorage.getItem('output-rotary-details'))
            : [];

        if(data.id) {
            if(storage.length > 0) {
                storage = storage.filter(row => row.id !== data.id);
                storage = [...storage, data]
            }
        }else {
            if (storage.length < 1) {
                data = {...data, id: 1}
                storage = [data];
            } else {
                storage = [...storage, {...data, id: storage.length + 1}]
            }
        }
        storage.sort((a, b) => a.id - b.id);
        localStorage.setItem('output-rotary-details', JSON.stringify(storage));
        $('#modal-output-detail').modal('toggle');
        generateDetail();
        return false;
    });

    function countVolume() {
        const tebal = $('#<?= Html::getInputId($modDetail, 'tebal')?>').val();
        const pcs = $('#<?= Html::getInputId($modDetail, 'pcs')?>').val();
        const size = $('#<?= Html::getInputId($modDetail, 'size')?>').val();

        if (tebal !== '' && pcs !== '' && size !== '') {
            const volume = tebal / 1000 * size * pcs;
            $('#<?= Html::getInputId($modDetail, 'volume')?>').val(volume.toFixed(4));
        }
    }

    function validateInput(input) {
        // Memastikan hanya input dengan nama 'tebal' yang divalidasi
        if (input.name.includes('tebal')  || input.name.includes('pcs')) {
            // Hanya angka, satu titik, dan maksimal 1 angka desimal yang diizinkan
            input.value = input.value
                .replace(/[^0-9.]|(?<=\..*)\./g, '')  // Menghapus karakter yang tidak valid
                .replace(/(\.\d{1})\d+/g, '$1');     // Membatasi desimal hanya 1 digit
        }
    }

</script>
