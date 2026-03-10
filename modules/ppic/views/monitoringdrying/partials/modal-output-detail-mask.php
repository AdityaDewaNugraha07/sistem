<?php

use app\models\MDefaultValue;
use app\models\TMtrgInOutDetail;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * @var TMtrgInOutDetail $modDetail
 * @var integer $id
 */

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
                <h4 class="modal-title"><?= Yii::t('app', 'Output Detail Drying') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php
                        echo $form->field($modDetail, 'unit')->dropDownList(MDefaultValue::getOptionList('mtrg-unit-drying'), ['prompt' => '-- Pilih Unit --']);
                        echo $form->field($modDetail, 'size')->inline()->radioList(MDefaultValue::getOptionList('size'));
                        ?>
                        <div class="place-detail"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::resetButton('Cancel', ['data-dismiss' => 'modal', 'class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Tambah', ['id' => 'btn-add-detail', 'class' => 'btn hijau btn-outline ciptana-spin-btn']) ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<script>
    const grades = JSON.parse('<?= Json::encode(MDefaultValue::getOptionList('mtrg-grade-drying-output'))?>');
    const mask = mtrg
        .$('.place-detail')
        .setLabel('Grades')
        .setGrades(grades)
        .setFields([
            {
                name: 'grade',
                type: 'text',
                value: true,
                options: {
                    readonly: true
                }
            },
            {
                name: 'tebal',
                type: 'text',
                options: {
                    step: '0.1',
                    oninput: 'validateInput(this)',
                    placeholder: '0.0'
                }
            },
            {
                name: 'pcs',
                type: 'text',
                options: {
                    oninput: 'validateInput(this)',
                    placeholder: '0'
                }
            },
            {
                name: 'volume',
                type: 'text',
                options: {
                    step: '0.0001',
                    readonly: true
                }
            },
        ]);

    $('#<?= Html::getInputId($modDetail, 'unit')?>').on('change', function () {
        mask.render();
    });

    mtrg.$('#form-output-detail').on('beforeSubmit', function () {
        $.ajax({
            url: '<?= Url::toRoute('/ppic/monitoringrotary/extractdetailoutput')?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function (result) {
                try {
                    mtrg.storage('output-drying-details').insert(result);
                    $('#modal-output-detail').modal('toggle');
                    mtrg.detail('drying').renderDetail();
                }catch (e) {
                    alert(e.message);
                }
            }
        });

        return false;
    });

    function validateInput(ele) {
        var input = ele.value;
        var name = ele.name;  // detail[1][tebal]
        var match = name.match(/\[([^\]]+)\]$/); 
        if (match) {
            var fieldName = match[1];
        }

        var input_val = '';
        var titik = false;
        var decimalCount = 0; 
        var maxcount = 0; 
        
        for (var i = 0; i < input.length; i++) {
            var char = input.charAt(i);
            
            var nilai = /^[0-9]$/.test(char);
            if (nilai) {
                input_val += char;

                if (titik) {
                    decimalCount++;
                }
            } else if (char === '.' && !titik && fieldName == 'tebal') {
                input_val += char;
                titik = true;
                maxcount = 1; // menentukan jumlah angka di belakang koma
            }
            
            if (titik && decimalCount > maxcount) {
                input_val = input_val.slice(0, -1);  // hapus karakter yg melebihi
                break;  // berhenti proses jika desimal lebih dari maxcount
            }
        }
        ele.value = input_val;
    }
</script>
