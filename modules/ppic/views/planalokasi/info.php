
<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Data Log <b>' . $model->no_barcode .'</b>'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">No. Barcode</label>
                            <div class="col-md-7"><strong><?= $model->no_barcode ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Jenis Kayu</label>
                            <div class="col-md-7"><strong><?= $modKayu->kayu_nama ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">No. Grade</label>
                            <div class="col-md-7"><strong><?= $modH->no_grade ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">No. Batang</label>
                            <div class="col-md-7"><strong><?= $modH->no_btg ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">No. Lapangan</label>
                            <div class="col-md-7"><strong><?= $modH->no_lap ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">No. Produksi</label>
                            <div class="col-md-7"><strong><?= $modH->no_produksi?$modH->no_produksi:'-' ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Panjang</label>
                            <div class="col-md-7"><strong><?= $modH->fisik_panjang ?> m</strong></div>
						</div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Diameter</label>
                            <div class="col-md-7"><strong><?= $modH->fisik_diameter ?> cm</strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Volume</label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modH->fisik_volume, 2) ?> m<sup>3</sup></strong></div>
						</div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Potongan</label>
                            <div class="col-md-7"><strong><?= $modH->pot?$modH->pot:'-' ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Diameter Ujung 1</label>
                            <div class="col-md-7"><strong><?= $modH->diameter_ujung1 ?> cm</strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Diameter Ujung 2</label>
                            <div class="col-md-7"><strong><?= $modH->diameter_ujung2 ?> cm</strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Diameter Pangkal 1</label>
                            <div class="col-md-7"><strong><?= $modH->diameter_pangkal1 ?> cm</strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Diameter Pangkal 2</label>
                            <div class="col-md-7"><strong><?= $modH->diameter_pangkal2 ?> cm</strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Cacat Panjang</label>
                            <div class="col-md-7"><strong><?= $modH->cacat_panjang ?> cm</strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Cacat Gubal</label>
                            <div class="col-md-7"><strong><?= $modH->cacat_gb ?> cm</strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Cacat Growong</label>
                            <div class="col-md-7"><strong><?= $modH->cacat_gr ?> cm</strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Status</label>
                            <div class="col-md-7"><strong><?= $modH->fsc=='true'?'FSC 100%':'Non FSC' ?> </strong></div>
						</div>
					</div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>