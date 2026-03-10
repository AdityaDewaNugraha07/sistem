<?php

use yii\helpers\Url;
use yii\helpers\Json;
use yii\bootstrap\Html;
use app\models\MDefaultValue;
use yii\bootstrap\ActiveForm;
use app\models\TMtrgInOutDetail;

/**
 * @var TMtrgInOutDetail $modDetail
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
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Output Detail Plytech') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php
                        echo $form->field($modDetail, 'unit')->dropDownList(MDefaultValue::getOptionList('mtrg-unit-plytech'), ['prompt' => '-- Pilih Unit --']);
                        echo $form->field($modDetail, 'patching')->textInput(['type' => 'text', 'oninput'=>'validateInput(this)', 'placeholder'=>'0']);
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

<?php ActiveForm::end() ?>

<script>
    const sizes = JSON.parse('<?= Json::encode(MDefaultValue::getOptionList('size')) ?>');
    const mask = mtrg
        .$('.place-detail')
        .setGrades(sizes)
        .setContext('plytech')
        .setFields([{
                name: 'size',
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

    $('#<?= Html::getInputId($modDetail, 'unit') ?>').on('change', function() {
        mask.render();
    });

    $('#form-output-detail').on('beforeSubmit', function() {
        $.ajax({
            url: '<?= Url::toRoute('/ppic/monitoringplytech/extractdetailoutput') ?>',
            data: $(this).serialize(),
            type: 'POST',
            success: function(res) {
                if (res.length) {
                    res.forEach(function(val) {
                        const isExists = mtrg.storage('output-plytech-details').findById(val.id);
                        if (isExists) {
                            mtrg.storage('output-plytech-details').update(val);
                        } else {
                            const data = {
                                old: mtrg.storage('output-plytech-details').get(),
                                new: []
                            }
                            data.new = {
                                ...val,
                                id: data.old.length > 0 ? data.old.length + 1 : 1
                            };
                            mtrg.storage('output-plytech-details').insert(data.new);
                        }
                    });
                }

                $('#modal-output-detail').modal('toggle');
                mtrg.detail('plytech').renderDetail();
            }
        })

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
                maxcount = 1;
            }
            
            if (titik && decimalCount > maxcount) {
                input_val = input_val.slice(0, -1);  // hapus karakter yg melebihi
                break;  // berhenti proses jika desimal lebih dari maxcount
            }
        }
        ele.value = input_val;
    }
</script>