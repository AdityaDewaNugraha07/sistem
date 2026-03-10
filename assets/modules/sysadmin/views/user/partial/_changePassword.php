<div class="modal fade" id="modal-user-changepassword" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Change Password'); ?></h4>
            </div>
            <div class="modal-body">
                <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id' => 'form-change-password',
                    'fieldConfig' => [
                        'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                        'labelOptions'=>['class'=>'col-md-4 control-label'],
                    ],
                ]); ?>

                <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'Current Password')); ?>
                <?= $form->field($model, 'newpassword')->passwordInput(); ?>
                <?= $form->field($model, 'renewpassword')->passwordInput(); ?>
                
                <?php \yii\bootstrap\ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau ciptana-spin-btn btn-outline',
                    'onclick'=>'formvalidate()'
                    ])?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<script>
function formvalidate(){
    var $form = $("#form-change-password");
    if(formrequiredvalidate($form)){
		disableformelement($form);
        $.ajax({
            url    : $form.attr('action'),
            type   : 'POST',
            data   : $form.serialize(),
            success: function (data) {
                if(data.status){
					$('#modal-change-password').modal('hide');
                    cisAlert('<i class="icon-check"></i> '+data.message);
                    clearmodal();
                }else{
                    $form.yiiActiveForm('updateMessages', data.message_validate);
                }
                enableformelement($form);
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
			progress: function(e) {
                if(e.lengthComputable) {
                    var pct = (e.loaded / e.total) * 100;
                    $('#form-change-password .progress-success .bar').animate({'width':pct.toPrecision(3)+'%'});
                }else{
                    console.warn('Content Length not reported!');
                }
            }
        });
    }
	return false;
}
</script>