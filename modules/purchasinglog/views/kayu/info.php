<div class="modal fade draggable-modal" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Kayu'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Kelompok Kayu</label>
                            <div class="col-md-7"><strong><?= $model->group_kayu ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Nama Kayu</label>
                            <div class="col-md-7"><strong><?= $model->kayu_nama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Nama Lain</label>
                            <div class="col-md-7"><strong><?= $model->kayu_othername ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Nama Ilmiah</label>
                            <div class="col-md-7"><strong><?= $model->nama_ilmiah ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Active</label>
                            <div class="col-md-7"><strong><?= ($model->active)?'<span class="font-green-jungle">Active</span>':'<span class="font-red">Non-Active</span>' ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/purchasinglog/kayu/edit','id'=>$model->kayu_id])."','modal-master-edit')"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/purchasinglog/kayu/delete','id'=>$model->kayu_id,'tableid'=>'table-master'])."','modal-delete-record')"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>