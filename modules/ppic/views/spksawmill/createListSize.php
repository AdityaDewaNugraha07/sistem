<?php
app\assets\InputMaskAsset::register($this);
?>
<div class="modal fade" id="modal-add" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Size Baru Ke List'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-add',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-2 col-md-2" style="padding-top:7px;">
                        <?= yii\helpers\Html::label('Size', null, ['class'=>'control-label']); ?>
                    </div>
                    <div class="col-xs-3 col-md-3">
                        <?= $form->field($model, 'size_p')->textInput(['class'=>'form-control float'])->label(false); ?>
                    </div>
                    <div class="col-xs-1 col-md-1 text-center" style="padding-top:7px;">
                        x
                    </div>
                    <div class="col-xs-3 col-md-3">
                        <?= $form->field($model, 'size_l')->textInput(['class'=>'form-control float'])->label(false); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'saveDefaultSize()'
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
<script>
    function saveDefaultSize(){
        $.ajax({
            url: '<?= \yii\helpers\Url::toRoute(['/ppic/spksawmill/saveDefaultSize']); ?>',
            type: 'POST',
            data: $('#form-add').serialize(),
            success: function(data){
                if(data){
                    $('#modal-add').modal('hide');

                    // var dropdown = $().closest('td').find('select[name*="[size]"]');
                    // dropdown.append(new Option(data.name, data.id, true, true));
                    if (data['msg'] == "Data ok") {
                        if(currentDropdown && currentDropdown.length){
                            currentDropdown.append(new Option(data.name, data.value, true, true));
                            if(currentDropdown.hasClass('select2')){
                                currentDropdown.trigger('change');
                            }
                        }
                    } else {
                        cisAlert(data['msg']);
                    }
                } else {
                    cisAlert('Size gagal ditambahkan di daftar size');
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
</script>