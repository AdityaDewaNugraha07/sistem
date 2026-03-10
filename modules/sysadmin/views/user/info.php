<div class="modal fade" id="modal-user-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'User Details'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['username'] ?></label>
                            <div class="col-md-7"><strong><?= $model->username ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['user_group_id'] ?></label>
                            <div class="col-md-7"><strong><?= $model->userGroup->name ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['authKey'] ?></label>
                            <div class="col-md-7"><strong><?= ($model->authKey)?$model->authKey:' - ' ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['accessToken'] ?></label>
                            <div class="col-md-7"><strong><?= ($model->accessToken)?$model->accessToken:' - ' ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['active'] ?></label>
                            <div class="col-md-7"><strong><?= ($model->active)?'<span class="font-green-jungle">Active</span>':'<span class="font-red">Non-Active</span>' ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['created_at'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser($model->created_at); ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <img src="<?= Yii::$app->view->theme->baseUrl; ?>/cis/img/user-profile-avatar/<?= $modUserProfile->avatar ?>" class="img-responsive pic-bordered" alt="" width="120">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modUserProfile->attributeLabels()['fullname'] ?></label>
                            <div class="col-md-7"><strong><?= $modUserProfile->fullname ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modUserProfile->attributeLabels()['email'] ?></label>
                            <div class="col-md-7"><strong><?= ($modUserProfile->email)?$modUserProfile->email:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modUserProfile->attributeLabels()['language'] ?></label>
                            <div class="col-md-7"><strong><?= ($modUserProfile->language)?$modUserProfile->language:" - " ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <?= yii\helpers\Html::button(Yii::t('app', 'Reset Password'),['class'=>'btn blue btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/sysadmin/user/reset','id'=>$model->user_id])."','modal-user-reset')"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/sysadmin/user/edit','id'=>$model->user_id])."','modal-user-edit')"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/sysadmin/user/delete','id'=>$model->user_id,'tableid'=>'table-user'])."','modal-delete-record')"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>