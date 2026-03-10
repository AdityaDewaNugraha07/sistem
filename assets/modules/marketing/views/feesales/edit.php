<?php
app\assets\DatepickerAsset::register($this);
?>
<div class="modal fade" id="modal-feesales-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Data Fee Sales'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-feesales-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'feesales_level_sales')->dropDownList(\app\models\MDefaultValue::getOptionList('sales-level'),['class'=>'form-control']) ?>
                        <?= $form->field($model, 'feesales_jenis_produk')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-produk'),['class'=>'form-control','onchange'=>'setDropdownGrade()']) ?>
                        <div class="jenis-log" style="display: none;">
                            <?= $form->field($model, 'jenis_log')->dropDownList([],['class'=>'form-control']) ?>
                        </div>
                        <?= $form->field($model, 'feesales_destinasi_penjualan')->dropDownList(\app\models\MDefaultValue::getOptionList('destinasi-penjualan'),['class'=>'form-control'])->label('Jenis Penjualan'); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'feesales_tempo_pembayaran',['template'=>'{label}<div class="col-md-5">{input} {error}</div> <div class="col-md-3" style="margin-left: -25px;"><span class="help-inline">Hari</span></div>'])
                            ->textInput(['class'=>'form-control numbers-only']); ?>
                        <?= $form->field($model, 'feesales_fee',['template'=>'{label}<div class="col-md-6">
                                <span class="input-group-btn" style="width: 45%">{input}</span> 
                                <span class="input-group-btn" style="width: 55%">'.\yii\bootstrap\Html::activeDropDownList($model, 'feesales_satuan', \app\models\MDefaultValue::getOptionList('satuan-penjualan'),['class'=>'form-control']).'</span> {error}</div>'])
                            ->textInput(['class'=>'form-control money-format'])->label("Fee (Rp) Per"); ?>
                        <?= $form->field($model, 'active',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                        ->checkbox([],false)->label(Yii::t('app', 'Active')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-feesales-edit\').modal(\'hide\'); $(\'#table-feesales\').dataTable().fnClearTable();")'])?>
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