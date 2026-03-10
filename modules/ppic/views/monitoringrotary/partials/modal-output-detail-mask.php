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
                <h4 class="modal-title"><?= Yii::t('app', 'Output Detail Rotary') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php
                        echo $form->field($modDetail, 'unit')->dropDownList(MDefaultValue::getOptionList('mtrg-unit-rotary-sengon'), ['prompt' => '-- Pilih Unit --', 'onchange' => 'generateMultipleInput()']);
                        echo $form->field($modDetail, 'size')->inline()->radioList(MDefaultValue::getOptionList('size'), ['onchange' => 'countVolume(this)']);
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
    $('#form-output-detail').on('beforeSubmit', function () {
        $.ajax({
            url: '<?= Url::toRoute('/ppic/monitoringrotary/extractdetailoutput')?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function (result) {
                try {
                    const oldData = JSON.parse(localStorage.getItem('output-rotary-details'));
                    let data = result;
                    if(oldData !== null && oldData.length > 0) {
                        data = oldData.concat(data)
                    }
                    const newData = data.map((row, i) => ({...row, id: i + 1}));
                    localStorage.setItem('output-rotary-details', JSON.stringify(newData));
                    $('#modal-output-detail').modal('toggle');
                    generateDetail();
                }catch (e) {
                    alert(e.message);
                }
            }
        });
        return false;
    });

    function countVolume(elm) {
        if(elm.id === '<?= Html::getInputId($modDetail, 'size') ?>') {
            const table = $('#tb-detail');
            if(table.length) {
                table.find('tbody > tr').each(function (i, tr) {
                    if(tr.id !== 'footer') {
                        const size = $("input:radio[name='TMtrgInOutDetail[size]']:checked").val();
                        const tebal = $(`input[name="detail[${i + 1}][tebal]"]`, tr).val();
                        const pcs = $(`input[name="detail[${i + 1}][pcs]"]`, tr).val();
                        const volume = $(`input[name="detail[${i + 1}][volume]"]`, tr);
                        if (tebal !== '' && pcs !== '' && size !== '') {
                            const vol = tebal / 1000 * size * pcs;
                            volume.val(vol.toFixed(4));
                        }
                    }
                })
            }
        }else {
            const size = $("input:radio[name='TMtrgInOutDetail[size]']:checked").val();
            const tr = $(elm).parents('tr');
            const tebal = $(tr.find('input[name*="tebal"]')[0]).val();
            const pcs = $(tr.find('input[name*="pcs"]')[0]).val();
            const volume = $(tr.find('input[name*="volume"]')[0]);
            if (tebal !== '' && pcs !== '' && size !== '') {
                const vol = tebal / 1000 * size * pcs;
                volume.val(vol.toFixed(4));
            }
        }

        total();
    }

    function generateMultipleInput() {
        const grades = JSON.parse('<?= Json::encode(MDefaultValue::getOptionList('mtrg-grade-rotary-sengon-output'))?>');
        const template = $(`
            <div class="form-group">
                <label class="col-md-4 control-label">Grades</label>
                <div class="col-md-7">
                    <table id="tb-detail" class="table table-hover" style="font-size: 1.2rem !important;">
                        <thead>
                        <tr>
                            <th>Grade</th>
                            <th>Tebal</th>
                            <th>PCS</th>
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
        let i = 1;
        for (const key in grades) {
            input += `
                <tr>
                    <td class="td-kecil">
                        <input type="text" ${style} name="detail[${i}][grade]" onchange="countVolume(this)" value="${grades[key]}" readonly>
                    </td>
                    <td class="td-kecil">
                        <input type="text" ${style} name="detail[${i}][tebal]" onchange="countVolume(this)" oninput="validateInput(this)" step="0.1" placeholder="0.0">
                    </td>
                    <td class="td-kecil">
                        <input type="number" ${style} name="detail[${i}][pcs]" onchange="countVolume(this)" oninput="validateInput(this)">
                    </td>
                    <td class="td-kecil">
                        <input type="text" ${style} name="detail[${i}][volume]" readonly step="0.0001">
                    </td>
                    <td class="td-kecil text-center">
                        <button class="btn btn-xs red" type="button" onclick="removeElm(this)"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            `;
            i++;
        }
        template.find('#tb-detail > tbody').html(input);

        const footer = `
            <tr id="footer">
                <th class="text-right" colspan="3">Total</th>
                <th colspan="2" id="total">0.0000</th>
            </tr>
        `;
        template.find('#tb-detail > tbody').append(footer);
        $('.place-detail').html(template);
    }

    function removeElm(elm) {
        $(elm).parents('tr').fadeOut(300, function () {
            $(this).remove();
            total();
        });
    }

    function total() {
        const total = $('input[name*="volume"]').toArray().reduce((prev, current) => {
            const val = $(current).val() ? parseFloat($(current).val()) : 0;
            return prev + val;
        }, 0);
        $('#total').text(total.toFixed(4));
    }

    function validateInput(input) {
        // Memastikan hanya input dengan nama 'tebal' yang divalidasi
        if (input.name.includes('tebal') || input.name.includes('pcs')) {
            // Hanya angka, satu titik, dan maksimal 1 angka desimal yang diizinkan
            input.value = input.value
                .replace(/[^0-9.]|(?<=\..*)\./g, '')  // Menghapus karakter yang tidak valid
                .replace(/(\.\d{1})\d+/g, '$1');     // Membatasi desimal hanya 1 digit
        }
    }
</script>
