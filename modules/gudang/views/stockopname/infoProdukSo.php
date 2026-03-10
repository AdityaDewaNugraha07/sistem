<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title">Tracking Produk : <b><?= $model->nomor_produksi ?></b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Data Produksi : </h4>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Di Produksi Pada</label>
                            <div class="col-md-7"><strong><?= !empty($modProduksi)?\app\components\DeltaFormatter::formatDateTimeForUser2($modProduksi->tanggal_produksi):"-"; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Di Input Oleh</label>
                            <div class="col-md-7"><strong><?= !empty($modProduksi)? \app\models\MUser::findOne($modProduksi->created_by)->username:"-"; ?></strong></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Data Terima : </h4>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Di Terima Pada</label>
                            <div class="col-md-7"><strong><?= !empty($modTerima)?\app\components\DeltaFormatter::formatDateTimeForUser2($modTerima->tgl_transaksi):"-"; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Di Terima Oleh</label>
                            <div class="col-md-7"><strong><?= !empty($modTerima)? \app\models\MUser::findOne($modTerima->created_by)->username:"-"; ?></strong></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Data Keluar : </h4>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Cara Keluar</label>
                            <div class="col-md-7"><strong><?= !empty($modKeluar)? $modKeluar->keterangan." ".$modKeluar->reff_no :"-" ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Keluar Pada</label>
                            <div class="col-md-7"><strong><?= !empty($modKeluar)?\app\components\DeltaFormatter::formatDateTimeForUser2($modKeluar->tgl_transaksi):"-"; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Dikeluarkan Oleh</label>
                            <div class="col-md-7"><strong><?= !empty($modKeluar)? \app\models\MUser::findOne($modKeluar->created_by)->username:"-"; ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>