<div class="modal fade draggable-modal" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Wilayah Dinas'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-7 control-label"><?= $model->attributeLabels()['wilayah_dinas_nama'] ?></label>
                            <div class="col-md-5"><strong><?= $model->wilayah_dinas_nama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-7 control-label"><?= $model->attributeLabels()['wilayah_dinas_plafon'] ?></label>
                            <div class="col-md-5"><strong><?= (!empty($model->wilayah_dinas_plafon)?\app\components\DeltaFormatter::formatUang($model->wilayah_dinas_plafon):" - "); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-7 control-label"><?= $model->attributeLabels()['wilayah_dinas_makan'] ?></label>
                            <div class="col-md-5"><strong><?= (!empty($model->wilayah_dinas_makan)?\app\components\DeltaFormatter::formatUang($model->wilayah_dinas_makan):" - "); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-7 control-label"><?= $model->attributeLabels()['wilayah_dinas_pulsa'] ?></label>
                            <div class="col-md-5"><strong><?= (!empty($model->wilayah_dinas_pulsa)?\app\components\DeltaFormatter::formatUang($model->wilayah_dinas_pulsa):" - "); ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= $model->attributeLabels()['active'] ?></label>
                            <div class="col-md-6"><strong><?= ($model->active)?'<span class="font-green-jungle">Active</span>':'<span class="font-red">Non-Active</span>' ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= $model->attributeLabels()['created_at'] ?></label>
                            <div class="col-md-6"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser($model->created_at); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= $model->attributeLabels()['created_by'] ?></label>
                            <div class="col-md-6"><strong><?php echo ( \app\models\MUser::findIdentity($model->created_by)) ? \app\models\MUser::findIdentity($model->created_by)->userProfile->fullname:"Unknown"; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= $model->attributeLabels()['updated_at'] ?></label>
                            <div class="col-md-6"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser($model->updated_at); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= $model->attributeLabels()['updated_by'] ?></label>
                            <div class="col-md-6"><strong><?php echo ( \app\models\MUser::findIdentity($model->updated_by)) ? \app\models\MUser::findIdentity($model->updated_by)->userProfile->fullname:"Unknown"; ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/purchasinglog/wilayahdinasgrader/edit','id'=>$model->wilayah_dinas_id])."','modal-master-edit')"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/purchasinglog/wilayahdinasgrader/delete','id'=>$model->wilayah_dinas_id,'tableid'=>'table-master'])."','modal-delete-record')"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>