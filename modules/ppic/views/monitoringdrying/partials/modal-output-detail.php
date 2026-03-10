<?php

use app\models\MDefaultValue;
use app\models\TMtrgInOutDetail;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

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
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                        aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Update Detail Drying') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php
                        echo Html::input('hidden', 'id', $id);
                        echo $form->field($modDetail, 'mtrg_in_out_detail_id')->textInput(['type' => 'hidden'])->label(false);
                        echo $form->field($modDetail, 'unit');
                        echo $form->field($modDetail, 'grade')->dropDownList(MDefaultValue::getOptionList('mtrg-grade-drying-output'));
                        echo $form->field($modDetail, 'tebal')->textInput(['type' => 'text', 'step' => '0.001', 'onchange' => 'countVolume();', 'oninput'=>'validateInput(this)']);
                        echo $form->field($modDetail, 'pcs')->textInput(['type' => 'text', 'onchange' => 'countVolume()', 'oninput'=>'validateInput(this)']);
                        echo $form->field($modDetail, 'size')->dropDownList(MDefaultValue::getOptionList('size'), ['onchange' => 'countVolume()']);
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
        $.ajax({
            url: '<?= Url::toRoute('/ppic/monitoringdrying/extractdetailoutput')?>',
            data: $(this).serialize(),
            type: 'POST',
            success: function(res) {
                mtrg.storage('output-drying-details').update(res);
                $('#modal-output-detail').modal('toggle');
                mtrg.detail('drying').renderDetail();
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
</script>
