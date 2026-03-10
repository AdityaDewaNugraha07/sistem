<div class="modal fade draggable-modal" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Grader'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['graderlog_nm'] ?></label>
                            <div class="col-md-7"><strong><?= $model->graderlog_nm ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['graderlog_kode'] ?></label>
                            <div class="col-md-7"><strong><?= $model->graderlog_kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['graderlog_phone'] ?></label>
                            <div class="col-md-7"><strong><?= $model->graderlog_phone ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['graderlog_email'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->graderlog_email)?$model->graderlog_email:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['graderlog_almt'] ?></label>
                            <div class="col-md-7"><strong><?= $model->graderlog_almt ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['graderlog_norek_bank'] ?></label>
                            <div class="col-md-7"><strong><?= $model->graderlog_norek_bank ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['graderlog_bank'] ?></label>
                            <div class="col-md-7"><strong><?= $model->graderlog_bank ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tipe Grader'); ?></label>
                            <div class="col-md-7"><strong><?= $model->type ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['active'] ?></label>
                            <div class="col-md-7"><strong><?= ($model->active)?'<span class="font-green-jungle">Active</span>':'<span class="font-red">Non-Active</span>' ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['created_at'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser($model->created_at); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['created_by'] ?></label>
                            <div class="col-md-7"><strong><?php echo ( \app\models\MUser::findIdentity($model->created_by)) ? \app\models\MUser::findIdentity($model->created_by)->userProfile->fullname:"Unknown"; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['updated_at'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser($model->updated_at); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['updated_by'] ?></label>
                            <div class="col-md-7"><strong><?php echo ( \app\models\MUser::findIdentity($model->updated_by)) ? \app\models\MUser::findIdentity($model->updated_by)->userProfile->fullname:"Unknown"; ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/purchasinglog/graderlog/edit','id'=>$model->graderlog_id])."','modal-master-edit')"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/purchasinglog/graderlog/delete','id'=>$model->graderlog_id,'tableid'=>'table-master'])."','modal-delete-record')"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>