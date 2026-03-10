<?php

use yii\helpers\Url;
use yii\bootstrap\Html;
use app\models\MDefaultValue;
use yii\bootstrap\ActiveForm;
use app\models\TMtrgInOutDetail;

/**
 * @var TMtrgInOutDetail $modDetail
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
                <h4 class="modal-title"><?= Yii::t('app', 'Input Detail Setting') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php
                        $grades = MDefaultValue::getOptionList('mtrg-grade-setting-input');
                        unset($grades['Output']);
                        echo Html::input('hidden', 'id', $id);
                        echo $form->field($modDetail, 'mtrg_in_out_detail_id')->textInput(['type' => 'hidden'])->label(false);
                        echo $form->field($modDetail, 'unit')->dropDownList(MDefaultValue::getOptionList('mtrg-unit-setting'), ['prompt' => '-- Pilih Unit --']);
                        echo $form->field($modDetail, 'grade')->dropDownList($grades);
                        echo $form->field($modDetail, 'tebal')->textInput(['type' => 'text', 'step' => '0.001', 'onchange' => 'countVolume()', 'oninput'=>'validateInput(this)', 'placeholder'=>'0.0']);
                        echo $form->field($modDetail, 'pcs')->textInput(['type' => 'text', 'onchange' => 'countVolume()', 'oninput'=>'validateInput(this)', 'placeholder'=>'0']);
                        echo $form->field($modDetail, 'size')->dropDownList(MDefaultValue::getOptionList('size'), ['onchange' => 'countVolume()']);
                        echo $form->field($modDetail, 'volume')->textInput(['readonly' => 'readonly', 'value' => 0]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::resetButton('Cancel', ['data-dismiss' => 'modal', 'class' => 'btn btn-default']) ?>
                <?= Html::submitButton($isUpdate ? 'Update' : 'Simpan', ['id' => 'btn-add-detail', 'class' => 'btn hijau btn-outline ciptana-spin-btn']) ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>

<script>
    countVolume();
    $('#form-input-detail').on('beforeSubmit', function () {
        $.ajax({
            url: '<?= Url::toRoute('/ppic/monitoringsetting/extractdetail')?>',
            data: $(this).serialize(),
            type: 'POST',
            success: function(res) {
                const data = mtrg.storage('input-setting-details').get();
                if(data.length > 0) {
                    mtrg.storage('input-setting-details').update(res);
                }else {
                    mtrg.storage('input-setting-details').insert(res);
                }
                $('#modal-input-detail').modal('toggle');
                mtrg.detail('setting', 'input').renderDetail();
            }
        })
        
        return false;
    });

    function countVolume() {
        const tebal = $('#<?= Html::getInputId($modDetail, 'tebal')?>').val();
        const pcs = $('#<?= Html::getInputId($modDetail, 'pcs')?>').val();
        const size = $('#<?= Html::getInputId($modDetail, 'size')?>').val();

        if (tebal !== '' && pcs !== '' && size !== '') {
            const volume = tebal / 1000 * size * pcs;
            $('#<?= Html::getInputId($modDetail, 'volume')?>').val(volume.toFixed(3));
        }
    }

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
