<?php
if(Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER){
        $disableAction = true;
}
?>
<div class="modal fade" id="modal-produk-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Produk'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['produk_group'] ?></label>
                            <div class="col-md-7"><strong><?= $model->produk_group ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['produk_kode'] ?></label>
                            <div class="col-md-7"><strong><?= $model->produk_kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['produk_nama'] ?></label>
                            <div class="col-md-7"><strong><?= $model->produk_nama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['jenis_kayu'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->jenis_kayu)?$model->jenis_kayu:'-'; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['grade'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->grade)?$model->grade:'-'; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['glue'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->glue)?$model->glue:'-'; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['profil_kayu'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->profil_kayu)?$model->profil_kayu:'-'; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['kondisi_kayu'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->kondisi_kayu)?$model->kondisi_kayu:'-'; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['produk_dimensi'] ?></label>
                            <div class="col-md-7"><strong><?= $model->produk_dimensi ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Qty Per ').$model->produk_satuan_besar; ?></label>
                            <div class="col-md-7"><strong><?= $model->produk_qty_satuan_kecil ?> <?= $model->produk_satuan_kecil ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kapasitas ') ?></label>
                            <div class="col-md-7"><strong><?= $model->kapasitas_kubikasi ?> M<sup>3</sup></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                        <div class="form-group col-md-12">
                            <label class="col-md-2 control-label"><?= $model->attributeLabels()['produk_gbr'] ?></label>
                            <div class="col-md-8">
                                <a href="javascript:;" class="thumbnail">
                                    <?php
                                    if(!empty($model->produk_gbr)){
                                        echo '<img src="'.\yii\helpers\Url::base().'/uploads/gud/produk/'.$model->produk_gbr .'" alt="produk-pict" style="height: 100%; width: 100%; display: block;"> ';
                                    }else{
                                        echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="produk-pict" style="height: 150px; width: 100%; display: block;"> ';
                                    }
                                    ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style='text-align:center'>
				<?php
                if($model->active==true){
                    echo yii\helpers\Html::button(Yii::t('app', 'Non-Active kan Produk ini!'),['class'=>'btn red-flamingo btn-outline','onclick'=>"updateStatus(".$model->produk_id.")"]);
                }else{
                    echo yii\helpers\Html::button(Yii::t('app', 'Active kan Produk ini!'),['class'=>'btn green-seagreen btn-outline','onclick'=>"updateStatus(".$model->produk_id.")"]);
                }
				if(empty($disableAction)){
					echo yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline pull-right','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/ppic/produk/delete','id'=>$model->produk_id,'tableid'=>'table-produk'])."','modal-delete-record')"]);
                    echo yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline pull-right','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/ppic/produk/edit','id'=>$model->produk_id])."','modal-produk-edit')"]);
				}
				?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>