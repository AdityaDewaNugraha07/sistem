<?php
app\assets\InputMaskAsset::register($this);
?>
<div class="modal fade" id="modal-ongkostruk-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Ongkos Baru'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-ongkostruk-create',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-5">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'ot_tujuan')->textInput(); ?>
                        <?= $form->field($model, 'ot_tarif_slama')->textInput(['class'=>'form-control money-format']); ?>
                        <?= $form->field($model, 'ot_tarif_sbaru')->textInput(['class'=>'form-control money-format']); ?>
                        <?= $form->field($model, 'ot_satuan')->dropDownList(\app\models\MDefaultValue::getOptionList('satuan-penjualan'),['class'=>'form-control']) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-ongkostruk-create\').modal(\'hide\'); $(\'#table-ongkostruk\').dataTable().fnClearTable();")'
                    ]);
                        ?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
formconfig();
", yii\web\View::POS_READY); ?>