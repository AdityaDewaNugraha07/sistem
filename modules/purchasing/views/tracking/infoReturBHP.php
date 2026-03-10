<div class="modal fade" id="modal-info-spo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><center><?= Yii::t('app', 'Detail Retur Pembelian BHP');?></center></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                            <div class="col-md-7"> : <strong><?= $model->kode; ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                            <div class="col-md-7"> : <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Penerimaan'); ?></label>
                            <div class="col-md-7"> : <strong><a onclick="infoTBP(<?= $model->terimaBhpd->terima_bhp_id ?>)"><?= $model->terimaBhpd->terimaBhp->terimabhp_kode ?></a></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Item'); ?></label>
                            <div class="col-md-7"> : <?= $model->terimaBhpd->bhp->bhp_nm ?></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan'); ?></label>
                            <div class="col-md-7" style="font-size: 1.2rem;"> : <?= $model->deskripsi ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Harga Terima'); ?></label>
                            <div class="col-md-7"> : <?= app\components\DeltaFormatter::formatNumberForUserFloat($model->terimaBhpd->terimabhpd_harga) ?></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Potongan'); ?></label>
                            <div class="col-md-7"> : <?= app\components\DeltaFormatter::formatNumberForUserFloat($model->potongan) ?></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Harga Retur'); ?></label>
                            <div class="col-md-7"> : <?= app\components\DeltaFormatter::formatNumberForUserFloat($model->harga) ?></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Qty Retur'); ?></label>
                            <div class="col-md-7"> : <?= app\components\DeltaFormatter::formatNumberForUserFloat($model->qty) ?></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'PPN'); ?></label>
                            <div class="col-md-7"> : <?= app\components\DeltaFormatter::formatNumberForUserFloat($model->ppn_nominal) ?></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Total Kembali'); ?></label>
                            <div class="col-md-7"> : <b><?= app\components\DeltaFormatter::formatNumberForUserFloat($model->total_kembali) ?></b></div>
                        </div>
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
<script>
function infoTBP(terima_bhp_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp','id'=>'']); ?>'+terima_bhp_id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-tbp").modal('show');
		$("#modal-info-tbp").on('hidden.bs.modal', function () {

		});
		spinbtn();
		draggableModal();
	});
}
</script>