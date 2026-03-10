<div class="modal fade" id="modal-catatan" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><b><?= "Catatan : ".(($model->cara_bayar=="CN")?"Credit Note": (($model->cara_bayar=="Potongan")?"Potongan":"Biaya Bank")); ?></b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" style="text-align: center;">
                        <h4><?= $model->keterangan; ?></h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
               
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>