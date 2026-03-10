<?php
app\assets\FileUploadAsset::register($this);
?>
<div class="modal fade draggable-modal" id="modal-bahanpembantu-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Data Bahan Pembantu'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-bahanpembantu-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'bhp_group')->dropDownList(\app\models\MDefaultValue::getOptionList('group-bahan-pembantu'),['prompt'=>'']); ?>
                        <?= $form->field($model, 'bhp_kode')->textInput(['style'=>'font-weight:bold']); ?>
                        <?= $form->field($model, 'bhp_nm')->textInput(); ?>
                        <?php // echo $form->field($model, 'bhp_grade')->textInput(); ?>
                        <?= $form->field($model, 'bhp_satuan')->textInput(); ?>
                        <?= $form->field($model, 'active',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                        ->checkbox([],false)->label(Yii::t('app', 'Active')); ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        echo $form->field($model, 'bhp_gbr',[
                            'template'=>'{label}
                                <div class="col-md-8">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail">
                                            <img src="'.(!empty($model->bhp_gbr)?
                                            \yii\helpers\Url::base().'/uploads/log/bahanpembantu/'.$model->bhp_gbr :
                                            Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png').'" alt="" /> 
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                        <div>
                                            <span class="btn blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-bahanpembantu-edit\').modal(\'hide\'); $(\'#table-bahanpembantu\').dataTable().fnClearTable();")'])?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>