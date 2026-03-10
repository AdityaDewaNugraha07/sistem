<style>
img.pic-bordered {
    border: 1px solid #e1e7ee;
}
a.profile-edit {
    background: #000 none repeat scroll 0 0;
    border: medium none;
    color: #fff;
    font-size: 12px;
    margin: 0;
    opacity: 0.6;
    padding: 3px 9px;
    position: absolute;
    left: 95px;
    top: 0;
}
a.profile-close {
    background: #000 none repeat scroll 0 0;
    border: medium none;
    color: #fff;
    font-size: 12px;
    margin: 0;
    opacity: 0.6;
    padding: 3px 9px;
    position: absolute;
    /*left: 95px;*/
    float: right;
    right: 15px;
    top: 0;
}
#avatar-item:hover{
    background-color: #D1E19C;
}
</style>
<div class="modal fade" id="modal-account-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit My Profile'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-account-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'username')->textInput(['readonly'=>'readonly']); ?>
                        <?= $form->field($model, 'authKey')->textInput(); ?>
                        <?= $form->field($model, 'accessToken')->textInput(); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Avatar</label>
                            <div class="col-md-7">
                                <div id="default-ava" style="display:block;">
                                    <img id="avatar-chosen" src="<?= Yii::$app->view->theme->baseUrl; ?>/cis/img/user-profile-avatar/<?= $modUserProfile->avatar ?>" class="img-responsive pic-bordered" alt="" width="120">
                                    <a class="profile-edit" href="javascript:;" onclick="openSelectionAvatar()"> edit </a>
                                </div>
                                <div id="select-ava" style="display:none; overflow: scroll; height: 120px;">
                                    <?php foreach($modAvatars as $key => $avatar){ ?>
                                    <img id="avatar-item" src="<?= Yii::$app->view->theme->baseUrl; ?>/cis/img/user-profile-avatar/<?= $avatar->value; ?>" width="40" onclick="selectAvatar('<?= $avatar->value ?>')" style="cursor: pointer;">
                                    <?php } ?>
                                    <a class="profile-close" href="javascript:;" onclick="closeSelectionAvatar()" > cancel </a>
                                </div>
                            </div>
                        </div>
                        <?= yii\bootstrap\Html::activeHiddenInput($modUserProfile, 'avatar'); ?>
                        <?= $form->field($modUserProfile, 'fullname')->textInput() ?>
                        <?= $form->field($modUserProfile, 'email')->textInput() ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau ciptana-spin-btn btn-outline',
                    'onclick'=>'submitformajax(this,"$(\'#modal-account-edit\').modal(\'hide\'); setTimeout(function (){ window.location.reload() },1500); ")'
                    ])?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<script type="text/javascript">
function openSelectionAvatar(){
    $('#default-ava').css('display','none');
    $('#select-ava').css('display','block');
}
function closeSelectionAvatar(){
    $('#default-ava').css('display','block');
    $('#select-ava').css('display','none');
}
function selectAvatar(par){
    $("#<?= \yii\bootstrap\Html::getInputId($modUserProfile, 'avatar') ?>").val(par);
    $("#avatar-chosen").attr('src','<?= Yii::$app->view->theme->baseUrl; ?>/cis/img/user-profile-avatar/'+par);
    closeSelectionAvatar();
}
</script>