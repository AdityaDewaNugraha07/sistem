<?php
app\assets\DatepickerAsset::register($this);
?>
<div class="modal fade" id="modal-sales-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Data Sales'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-sales-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'sales_kode')->textInput(); ?>
                        <?= $form->field($model, 'sales_nm')->textInput(); ?>
                        <?= $form->field($model, 'sales_jns')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-sales'),['class'=>'form-control']) ?>
                        <?= $form->field($model, 'sales_tgl_join',[
                            'template'=>'{label}<div class="col-md-7"><div class="input-group date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'sales_almt')->textarea(); ?>
                        <?= $form->field($model, 'sales_phone')->textInput(); ?>
                        <?= $form->field($model, 'sales_email')->textInput(); ?>
                        <?= $form->field($model, 'active',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                        ->checkbox([],false)->label(Yii::t('app', 'Active')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-sales-edit\').modal(\'hide\'); $(\'#table-sales\').dataTable().fnClearTable();")'])?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
	formconfig();
", yii\web\View::POS_READY); ?>