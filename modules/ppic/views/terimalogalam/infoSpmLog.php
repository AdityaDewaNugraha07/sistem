<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Informasi Kode SPM Log <b>' . $model->kode . '</b>'); ?></h4>
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
                                <label class="col-md-5 control-label">Nama Tongkang</label>
                                <div class="col-md-6"><strong><?= $model->nama_tongkang ?></strong></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-md-5 control-label">Est. Batang</label>
                                <div class="col-md-6"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->estimasi_total_batang) ?></strong></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-md-5 control-label">Est. Volume</label>
                                <div class="col-md-6"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->estimasi_total_m3) ?></strong></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group col-md-12">
                                <label class="col-md-5 control-label">Lokasi Muat</label>
                                <div class="col-md-6"><strong><?= $model->lokasi_muat ?></strong></div>
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