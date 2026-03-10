<div class="modal fade" id="modal-info-kasbonkk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Informasi Kasbon Kas Kecil'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                            <div class="col-md-8"><strong><?= $model->kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                            <div class="col-md-8"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Penerima'); ?></label>
                            <div class="col-md-8"><strong><?= $model->penerima ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Nominal'); ?></label>
                            <div class="col-md-8"><strong><?= (!empty($model->nominal)?app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal):""); ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Deskripsi'); ?></label>
                            <div class="col-md-8"><strong><?= $model->deskripsi ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>