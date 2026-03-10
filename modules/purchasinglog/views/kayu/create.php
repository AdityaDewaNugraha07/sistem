<div class="modal fade draggable-modal" id="modal-master-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Kayu Baru'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-master-create',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<?= $form->field($model, 'group_kayu')->dropDownList(\app\models\MDefaultValue::getOptionList('kelompok-kayu'))->label(Yii::t('app', 'Kelompok Kayu')); ?>
                        <?= $form->field($model, 'kayu_nama')->textInput(); ?>
                    </div>
					<div class="col-md-6">
                        <?= $form->field($model, 'kayu_othername')->textInput(); ?>
                        <?= $form->field($model, 'nama_ilmiah')->textarea(); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-master-create\').modal(\'hide\'); $(\'#table-master\').dataTable().fnClearTable();")'
                    ]);
                        ?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>