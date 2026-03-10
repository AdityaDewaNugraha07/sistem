<?php
app\assets\DatepickerAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<div class="modal fade" id="modal-importexcel" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Import File Excel'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-importexcel',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $form->field($model, 'lokasi_bongkar',['template'=>'{label}<div class="col-md-4" style="margin-bottom:5px;">{input}</div>'])->dropDownList(["PELABUHAN"=>"PELABUHAN","PABRIK"=>"PABRIK"]); ?>
                        <?php echo $form->field($model, 'file',[
                                                        'template'=>'{label}
                                                                    <div class="col-md-3">
                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                            <div class="input-group input-large">
                                                                                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                                                    <i class="fa fa-download fileinput-exists"></i>&nbsp;
                                                                                    <span class="fileinput-filename"> </span>
                                                                                </div>
                                                                                <span class="input-group-addon btn default btn-file">
                                                                                    <span class="fileinput-new"> Select file </span>
                                                                                    <span class="fileinput-exists"> Change </span>
                                                                                    {input}
                                                                                    </span>
                                                                                <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>'
                                                    ])->fileInput()->label("File excel");
                        ?>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 fontsize-1-1 text-align-center">
                        <span class="font-red-flamingo">NOTE : </span>Pastikan format excel sudah diatur sesuai dengan format import!
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-importexcel\').modal(\'hide\'); getItems()")'
                    ]);
                        ?>
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
<script type="text/javascript">
</script>