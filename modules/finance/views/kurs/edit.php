<div class="modal fade" id="modal-master-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Kurs'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-kurs-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="control-label col-md-4">Periode Tanggal</label>
                            <div class="col-md-6">
                                <div class="input-group input-large date-picker input-daterange" data-date-format="dd/mm/yyyy">
                                    <?= yii\helpers\Html::activeTextInput($model, 'tanggal', ['class'=>'form-control']) ?>
                                    <span class="input-group-addon"> sd </span>
                                    <?= yii\helpers\Html::activeTextInput($model, 'tanggal_akhir', ['class'=>'form-control']) ?>
                                </div>
                            </div>
                        </div>
                        <?= $form->field($model, 'usd')->textInput(['class'=>'form-control float','placeholder'=>'ex. 14,123.5'])->label("Nominal Rupiah per USD"); ?>
                        <?= $form->field($model, 'sumber')->textInput(); ?>
                        <?= $form->field($model, 'keterangan')->textarea(); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-master-edit\').modal(\'hide\'); $(\'#table-master\').dataTable().fnClearTable();")'])?>
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