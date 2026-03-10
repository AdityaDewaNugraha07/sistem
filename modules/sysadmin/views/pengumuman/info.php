<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Pengumuman'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['tipe'] ?></label>
                            <div class="col-md-7"><strong><?= $model->tipe ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['judul'] ?></label>
                            <div class="col-md-7"><strong><?= $model->judul ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-4">
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['seq'] ?></label>
                            <div class="col-md-7"><strong><?= $model->seq ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['judul_pulsate'] ?></label>
                            <div class="col-md-7"><strong><?= ($model->judul_pulsate)?'<span class="font-green-jungle">Yes</span>':'<span class="font-red">No</span>' ?></strong></div>
                        </div>
                    </div>
					<div class="col-md-4">
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['active'] ?></label>
                            <div class="col-md-7"><strong><?= ($model->active)?'<span class="font-green-jungle">Active</span>':'<span class="font-red">Non-Active</span>' ?></strong></div>
                        </div>
					</div>
                </div><br>
				<div class="row">
					<div class="col-md-2"></div>
                    <div class="col-md-8">
						<div class="form-group col-md-12 text-align-center">
                            <?= preg_replace('/<img(.*?)style="(.*?)"(.*?>)/', '<img$1style="width: 100%; height: auto;"$3', $model->deskripsi); ?>
                        </div>
					</div>
					<div class="col-md-2"></div>
				</div>
            </div>
            <div class="modal-footer">
                <?= yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/sysadmin/pengumuman/edit','id'=>$model->pengumuman_id])."','modal-master-edit','80%')"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/sysadmin/pengumuman/delete','id'=>$model->pengumuman_id,'tableid'=>'table-master'])."','modal-delete-record')"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>