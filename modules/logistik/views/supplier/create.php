<?php

use app\models\MDefaultValue;
use app\models\MSuplier;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

app\assets\InputMaskAsset::register($this);
?>
    <div class="modal fade draggable-modal"
         id="modal-supplier-create"
         tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                            aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                    <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Supplier Baru') ?></h4>
                </div>
                <?php $form = ActiveForm::begin([
                    'id' => 'form-supplier-create',
                    'fieldConfig' => [
                        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                        'labelOptions' => ['class' => 'col-md-4 control-label'],
                    ],
                ]); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= /** @var MSuplier $model */
                            $form->field($model, 'type')->dropDownList(MDefaultValue::getOptionList('tipe-suplier'), ['prompt' => 'Pilih Tipe Suplier']) ?>
                            <?= $form->field($model, 'suplier_nm')->textInput(['placeholder' => 'Nama Suplier']) ?>
                            <?= $form->field($model, 'suplier_nm_company')->textInput(['placeholder' => 'Nama Perusahaan']) ?>
                            <?= $form->field($model, 'suplier_almt')->textarea(['placeholder' => 'Alamat Suplier']) ?>
                            <?= $form->field($model, 'suplier_phone')->textInput(['class' => 'form-control numbers-only', 'placeholder' => 'Nomor Telepon']) ?>
                            <?= $form->field($model, 'suplier_email')->textInput(['placeholder' => 'Alamat Email']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'suplier_bank')->textInput(['placeholder' => 'Nama Bank']) ?>
                            <?= $form->field($model, 'suplier_norekening')->textInput(['class' => 'form-control numbers-only', 'placeholder' => 'Nomor Rekening']) ?>
                            <?= $form->field($model, 'suplier_an_rekening')->textInput(['placeholder' => 'Nama Akun Bank']) ?>
                            <?= $form->field($model, 'suplier_npwp')->textInput(['placeholder' => 'Nomor Pokok Wajib Pajak']) ?>
                            <?= $form->field($model, 'suplier_nik')->textInput(['placeholder' => 'Nomor Induk Kependudukan']) ?>
                            <?= $form->field($model, 'fax')->textInput(['placeholder' => 'Faksimile']) ?>
                            <?= $form->field($model, 'suplier_ket')->textarea(['placeholder' => 'Keterangan']) ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php echo Html::button(Yii::t('app', 'Save'), ['class' => 'btn hijau btn-outline ciptana-spin-btn',
                        'onclick' => 'submitformajax(this,"$(\'#modal-supplier-create\').modal(\'hide\'); $(\'#table-supplier\').dataTable().fnClearTable();")'
                    ]);
                    ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php $this->registerJsFile($this->theme->baseUrl . "/global/plugins/jquery-ui/jquery-ui.min.js", ['depends' => [yii\web\YiiAsset::className()]]) ?>

<?php $this->registerJs("
    formconfig();
	$('#" . \yii\bootstrap\Html::getInputId($model, 'suplier_npwp') . "').inputmask({'mask': '99.999.999.9-999.999'});
", yii\web\View::POS_READY); ?>