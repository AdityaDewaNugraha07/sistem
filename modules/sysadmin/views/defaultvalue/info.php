<div class="modal fade draggable-modal" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Data'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['type'] ?></label>
                            <div class="col-md-7"><strong><?= $model->type ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['name'] ?></label>
                            <div class="col-md-7"><strong><?= $model->name ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['name_en'] ?></label>
                            <div class="col-md-7"><?= $model->name_en ?></div>
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['value'] ?></label>
                            <div class="col-md-7"><?= $model->value ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['sequence_number'] ?></label>
                            <div class="col-md-7"><?= $model->sequence_number ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['active'] ?></label>
                            <div class="col-md-7"><strong><?= ($model->active)?'<span class="font-green-jungle">Active</span>':'<span class="font-red">Non-Active</span>' ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/sysadmin/defaultvalue/edit','id'=>$model->default_value_id])."','modal-master-edit')"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/sysadmin/defaultvalue/delete','id'=>$model->default_value_id,'tableid'=>'table-master'])."','modal-delete-record')"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>