<?php

use app\models\MDefaultValue;
use app\models\MSuplier;
use app\models\TMtrgRotaryDetail;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var TMtrgRotaryDetail $modDetail
 *
 * @var integer $id
 */

$form = ActiveForm::begin([
    'id' => 'form-input-detail',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions' => ['class' => 'col-md-4 control-label'],
    ],
]);
?>

<div class="modal fade" id="modal-input-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                        aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Input Detail Rotary') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php
                        echo Html::input('hidden', 'id', $id);
                        echo $form->field($modDetail, 'mtrg_rotary_detail_id')->textInput(['type' => 'hidden'])->label(false);
                        echo $form->field($modDetail, 'unit')->dropDownList(MDefaultValue::getOptionList('mtrg-unit-rotary-sengon'), ['prompt' => '-- Pilih Unit --']);
                        echo $form->field($modDetail, 'suplier_id')->dropDownList(MSuplier::getOptionList('LS'), ['prompt' => '-- Pilih Suplier --']);
                        echo $form->field($modDetail, 'panjang')->dropDownList(MDefaultValue::getOptionList('log-sengon-panjang'), ['prompt' => '-- Pilih Panjang --', 'onchange' => 'countVolume()']);
                        echo $form->field($modDetail, 'diameter')->textInput(['type' => 'number', 'step' => '0.001', 'onchange' => 'countVolume()']);
                        echo $form->field($modDetail, 'pcs')->textInput(['type' => 'number', 'onchange' => 'countVolume()']);
                        echo $form->field($modDetail, 'volume')->textInput(['readonly' => 'readonly']);
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::resetButton('Cancel', ['data-dismiss' => 'modal', 'class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Update', ['id' => 'btn-add-detail', 'class' => 'btn hijau btn-outline ciptana-spin-btn']) ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>

<script>
    $('#form-input-detail').on('beforeSubmit', function () {
        let obj = {};
        $(this).serializeArray().forEach(function (r) {
            if (r.name !== '_csrf') {
                obj[r.name] = r.value;
            }
        });
        let newObj = {}
        newObj.id = parseInt(obj['id']);
        newObj.unit = obj['TMtrgRotaryDetail[unit]'];
        newObj.suplier_id = obj['TMtrgRotaryDetail[suplier_id]'];
        newObj.diameter = obj['TMtrgRotaryDetail[diameter]'];
        newObj.pcs = obj['TMtrgRotaryDetail[pcs]'];
        newObj.panjang = obj['TMtrgRotaryDetail[panjang]'];
        newObj.volume = obj['TMtrgRotaryDetail[volume]'];
        newObj.mtrg_rotary_detail_id = obj['TMtrgRotaryDetail[mtrg_rotary-detail_id]'];

        let storage = JSON.parse(localStorage.getItem('input-rotary-details'));
        storage = storage.filter((row) => row.id !== newObj.id);
        const newData = [...storage, newObj];
        newData.sort((a, b) => a.id - b.id); // sort by id
        localStorage.setItem('input-rotary-details', JSON.stringify(newData));
        $('#modal-input-detail').modal('toggle');
        generateDetail();
        return false;
    });

    function countVolume() {
        const diameter = $('#<?= Html::getInputId($modDetail, 'diameter')?>').val();
        const pcs = $('#<?= Html::getInputId($modDetail, 'pcs')?>').val();
        const panjang = $('#<?= Html::getInputId($modDetail, 'panjang')?>').val();

        if (diameter !== '' && pcs !== '' && panjang !== '') {
            const volume = diameter * diameter * panjang * pcs * 0.7854 / 1000000;
            $('#<?= Html::getInputId($modDetail, 'volume')?>').val(volume.toFixed(3));
        }
    }
</script>
