<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Informasi Kode Keputusan <b>' . $model->kode . ' - '. $model->nomor_kontrak .'</b>'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="col-md-6">
                            <div class="form-group col-md-12">
                                <label class="col-md-5 control-label">Kode</label>
                                <div class="col-md-6"><strong><?= $model->kode ?></strong></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-md-5 control-label">Suplier</label>
                                <div class="col-md-6"><strong><?= $modSup->suplier_nm ?></strong></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-md-5 control-label">Volume Kontrak</label>
                                <div class="col-md-6"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->volume_kontrak) ?></strong></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-md-5 control-label">Total Volume</label>
                                <div class="col-md-6"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_volume) ?></strong></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group col-md-12">
                                <label class="col-md-5 control-label">Asal Kayu</label>
                                <div class="col-md-6"><strong><?= $model->asal_kayu ?></strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>