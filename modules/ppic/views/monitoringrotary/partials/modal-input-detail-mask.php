<?php

use app\models\MDefaultValue;
use app\models\MSuplier;
use app\models\TMtrgRotaryDetail;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * @var TMtrgRotaryDetail $modDetail
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
                        echo $form->field($modDetail, 'unit')->dropDownList(MDefaultValue::getOptionList('mtrg-unit-rotary-sengon'), ['prompt' => '-- Pilih Unit --']);
                        echo $form->field($modDetail, 'suplier_id')->dropDownList(MSuplier::getOptionList('LS'), ['prompt' => '-- Pilih Suplier --']);
                        echo $form->field($modDetail, 'panjang')->dropDownList(MDefaultValue::getOptionList('log-sengon-panjang'), ['prompt' => '-- Pilih Panjang (cm) --', 'onchange' => 'genDiameterBatang(16, 40)']);
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
    $('#form-input-detail').on('beforeSubmit', function () {
        $.ajax({
            url: '<?= Url::toRoute('/ppic/monitoringrotary/extractdetailinput')?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function (result) {
                try {
                    const oldData = JSON.parse(localStorage.getItem('input-rotary-details'));
                    let data = result;
                    if(oldData !== null && oldData.length > 0) {
                        data = oldData.concat(data)
                    }
                    const newData = data.map((row, i) => ({...row, id: i + 1}));
                    localStorage.setItem('input-rotary-details', JSON.stringify(newData));
                    $('#modal-input-detail').modal('toggle');
                    generateDetail();
                }catch (e) {
                    alert(e.message);
                }
            }
        });

        return false;
    });

    function countVolume(elm) {
        const panjang = $('#<?= Html::getInputId($modDetail, 'panjang')?>').val();
        const tr = $(elm).parents('tr');
        const diameter = $(tr.find('input[name*="diameter"]')[0]).val();
        const pcs = $(tr.find('input[name*="pcs"]')[0]).val();
        const volume = $(tr.find('input[name*="volume"]')[0]);

        if (diameter !== '' && pcs !== '' && panjang !== '') {
            const vol = diameter * diameter * panjang * pcs * 0.7854 / 1000000;
            volume.val(vol.toFixed(3));
        }

        total();
    }

    function genDiameterBatang(start, end) {

        // function option(selected, start, end) {
        //     let option = '';
        //
        //     for (let i = start; i <= end; i++) {
        //         option += `<option value="${i}" ${selected === i ? 'selected' : ''}>${i}</option>`
        //     }
        //
        //     return option;
        // }

        const template = $(`
            <div class="form-group">
                <label class="col-md-4 control-label">Diameter & Batang</label>
                <div class="col-md-7">
                    <table id="tb-detail" class="table table-hover" style="font-size: 1.2rem !important;">
                        <thead>
                        <tr>
                            <th>Diameter</th>
                            <th>Batang</th>
                            <th>Volume</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        `);

        let input = '';
        const style = 'class="form-control" style="padding: 3px; font-size:1.2rem; height:30px;"';
        for (let i = start; i <= end; i++) {
            input += `
                <tr>
                    <td class="td-kecil">
                        <input type="number" ${style} name="detail[${i}][diameter]" onchange="countVolume(this)" value="${i}" readonly>
                    </td>
                    <td class="td-kecil">
                        <input type="number" ${style} name="detail[${i}][pcs]" onchange="countVolume(this)">
                    </td>
                    <td class="td-kecil">
                        <input type="text" ${style} name="detail[${i}][volume]" readonly>
                    </td>
                    <td class="td-kecil text-center">
                        <button class="btn btn-xs red" type="button" onclick="removeElm(this)"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            `;
        }
        template.find('#tb-detail > tbody').html(input);

        const footer = `
            <tr id="footer">
                <td>
                    <button class="btn btn-xs green-haze" type="button" onclick="addRow()"><i class="fa fa-plus-circle"></i> Add</button>
                </td>
                <th class="text-right">Total</th>
                <th colspan="2" id="total">0</th>
            </tr>
        `;
        template.find('#tb-detail > tbody').append(footer);
        $('.place-detail').html(template);
    }

    function removeElm(elm) {
        $(elm).parents('tr').fadeOut('slow', function () {
            $(this).remove();
            total();
        });
    }

    function total() {
        const total = $('input[name*="volume"]').toArray().reduce((prev, current) => {
            const val = $(current).val() ? parseFloat($(current).val()) : 0;
            return prev + val;
        }, 0);
        $('#total').text(total.toFixed(3));
    }

    function addRow() {
        let prev  = $('#footer').prev().prop('outerHTML');
        $(prev).find(':input').each(function (i, e) {
            if(e.name) {
                if(i === 0) {
                    prev = prev.replace('readonly', '');
                    prev = prev.replace(`value="${e.value}"`, '');
                }
                let index = parseInt(e.name.match(/\d+/)[0]);
                const newName = e.name.replace(index, index + 1);
                prev = prev.replace(e.name, newName);
            }
        });
        $(prev).insertBefore('#footer').hide().fadeIn('slow');
        total();
    }
</script>
