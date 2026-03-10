<?php
if(Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER){
        $disableAction = true;
}
?>
<div class="modal fade draggable-modal" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Kondisi Kayu'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['jenis_produk'] ?></label>
                            <div class="col-md-7"><strong><?= $model->jenis_produk ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['kode'] ?></label>
                            <div class="col-md-7"><strong><?= $model->kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['nama'] ?></label>
                            <div class="col-md-7"><strong><?= $model->nama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['othername'] ?></label>
                            <div class="col-md-7"><?= $model->othername ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['active'] ?></label>
                            <div class="col-md-7"><strong><?= ($model->active)?'<span class="font-green-jungle">Active</span>':'<span class="font-red">Non-Active</span>' ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
            <div class="modal-footer" style='text-align:center'>
				<?php
                if($model->active==true){
                    echo yii\helpers\Html::button(Yii::t('app', 'Non-Active kan Data ini!'),['class'=>'btn red-flamingo btn-outline','onclick'=>"updateStatus(".$model->kondisi_kayu_id.")"]);
                }else{
                    echo yii\helpers\Html::button(Yii::t('app', 'Active kan Data ini!'),['class'=>'btn green-seagreen btn-outline','onclick'=>"updateStatus(".$model->kondisi_kayu_id.")"]);
                }
				if(empty($disableAction)){
                    echo yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline pull-right','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/ppic/kondisikayu/delete','id'=>$model->kondisi_kayu_id,'tableid'=>'table-master'])."','modal-delete-record')"]);
                    echo yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline pull-right','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/ppic/kondisikayu/edit','id'=>$model->kondisi_kayu_id])."','modal-master-edit')"]);
				}
				?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>