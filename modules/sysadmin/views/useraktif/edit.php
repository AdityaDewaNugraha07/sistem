<?php
\app\assets\Select2Asset::register($this);
?>
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
<div class="modal fade" id="modal-user-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Change User Data'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-user-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'user_group_id')->dropDownList(\app\models\MUserGroup::getOptionList(),['class'=>'form-control','prompt'=>'']) ?>
                        <?= $form->field($model, 'username')->textInput(); ?>
                        <?= $form->field($model, 'authKey')->textInput(); ?>
                        <?php // echo $form->field($model, 'accessToken')->textInput(); ?>
                        <?= $form->field($model, 'active',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                            ->checkbox([],false)->label(Yii::t('app', 'Active')); ?>
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
                        <?= \yii\bootstrap\Html::activeHiddenInput($modUserProfile, 'fullname', ['readonly'=>'readonly']) ?>
                        <?= $form->field($modUserProfile, 'email')->textInput() ?>
                        <?= $form->field($model, 'pegawai_id')->dropDownList(\app\models\MPegawai::getOptionList(),['class'=>'form-control select2','prompt'=>'']); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-user-edit\').modal(\'hide\'); $(\'#table-user\').dataTable().fnClearTable();")'])?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs(" 
    formconfig();
    $('#".\yii\bootstrap\Html::getInputId($model, 'pegawai_id')."').select2({
        allowClear: !0,
        placeholder: 'Pilih Pegawai Bersangkutan',
        width: null,
    });
    $.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
    $('#".\yii\bootstrap\Html::getInputId($model, 'pegawai_id')."').change(function() {
        var nama = $('#".\yii\bootstrap\Html::getInputId($model, 'pegawai_id')." option:selected').text();
        $('#".\yii\bootstrap\Html::getInputId($modUserProfile, 'fullname')."').val(nama);
    });
", yii\web\View::POS_READY); ?>
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