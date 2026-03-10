<div class="modal fade" id="modal-buyer-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Informasi Buyer'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_kode'] ?></label>
                            <div class="col-md-6"><strong><?= $model->cust_kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Tipe Penjualan</label>
                            <div class="col-md-6"><strong><?= $model->cust_tipe_penjualan ?></strong></div>
                        </div>
					</div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-4 control-label"><?= $model->attributeLabels()['cust_an_alamat'] ?></label>
                            <div class="col-md-8"><strong><?= $model->cust_an_alamat ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-4 control-label"><?= $model->attributeLabels()['cust_an_email'] ?></label>
                            <div class="col-md-8"><strong><?= !empty($model->cust_an_email)?$model->cust_an_email:" - " ?></strong></div>
                        </div>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <?= yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/exim/buyer/edit','id'=>$model->cust_id])."','modal-buyer-edit')"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/exim/buyer/delete','id'=>$model->cust_id,'tableid'=>'table-buyer'])."','modal-delete-record')"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>