<div class="portlet-body">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
        'id' => 'form-search-laporan',
        'fieldConfig' => [
            'template' => '{label}<div class="col-md-8">{input} {error}</div>',
            'labelOptions'=>['class'=>'col-md-3 control-label'],
        ],
        'enableClientValidation'=>false
    ]); ?>
    <div class="row">
        <div class="col-md-6">
            <?php echo $this->render('@views/apps/form/periodeTanggalKecil', ['label'=>'Tanggal','model' => $model,'form'=>$form]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'jenis')->dropDownList(\app\models\MDefaultValue::getOptionList('shipping_tracking'),['style'=>'width: 100px:', 'prompt'=>'All', '']); ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <?php echo $this->render('@views/apps/form/tombolSearch') ?>
        </div>
    </div>
    <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
    <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
</div>