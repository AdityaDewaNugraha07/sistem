<div class="modal fade draggable-modal" id="modal-master-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Data Penerima'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-master-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'kode')->textInput(['disabled'=>true,'style'=>'font-weight:600']); ?>
                        <?= $form->field($model, 'nama_penerima')->textInput(); ?>
                        <?= $form->field($model, 'nama_perusahaan')->textInput(); ?>
                        <?= $form->field($model, 'penerima_alamat')->textarea(); ?>
                        <?= $form->field($model, 'contact_person')->textInput(); ?>
                        <?= $form->field($model, 'phone')->textInput(); ?>
                        <?= $form->field($model, 'phone2')->textInput(); ?>
                        <?= $form->field($model, 'fax')->textInput(); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'email')->textInput(); ?>
                        <?= $form->field($model, 'npwp')->textInput(); ?>
                        <?= $form->field($model, 'nik')->textInput(); ?>
                        <?= $form->field($model, 'rekening_bank')->textInput(); ?>
                        <?= $form->field($model, 'rekening_an')->textInput(); ?>
                        <?= $form->field($model, 'rekening_no')->textInput(); ?>
                        <?= $form->field($model, 'keterangan')->textarea(); ?>
                        <?= $form->field($model, 'active',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                        ->checkbox([],false)->label(Yii::t('app', 'Active')); ?>
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
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>