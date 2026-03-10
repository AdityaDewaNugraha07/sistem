<?php
app\assets\FileUploadAsset::register($this);
?>
<div class="modal fade draggable-modal" id="modal-supplier-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Data Supplier'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-supplier-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<?= $form->field($model, 'type')->dropDownList(\app\models\MDefaultValue::getOptionList('tipe-suplier')); ?>
                        <?= $form->field($model, 'suplier_nm')->textInput(); ?>
                        <?= $form->field($model, 'suplier_nm_company')->textInput(); ?>
                        <?= $form->field($model, 'suplier_almt')->textarea(); ?>
                        <?= $form->field($model, 'suplier_phone')->textInput(['class'=>'form-control numbers-only']); ?>
                        <?= $form->field($model, 'suplier_email')->textInput(); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'suplier_bank')->textInput(); ?>
                        <?= $form->field($model, 'suplier_norekening')->textInput(['class'=>'form-control numbers-only']); ?>
                        <?= $form->field($model, 'suplier_an_rekening')->textInput(); ?>
                        <?= $form->field($model, 'suplier_npwp')->textInput(); ?>
                        <?= $form->field($model, 'suplier_ket')->textarea(['style'=>'height:50px;']); ?>
                        <?= $form->field($model, 'active',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                        ->checkbox([],false)->label(Yii::t('app', 'Active')); ?>
						<?= $form->field($model, 'fax')->textInput(); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-supplier-edit\').modal(\'hide\'); $(\'#table-supplier\').dataTable().fnClearTable();")'])?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>