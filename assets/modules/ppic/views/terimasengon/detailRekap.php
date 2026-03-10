<div class="modal fade" id="modal-detailterima" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= $paramprint['judul'] ?></h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Penerimaan'); ?></label>
                            <div class="col-md-7"><strong><?= $modTerima->kode; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Penerimaan'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser($modTerima->tanggal); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode PO'); ?></label>
                            <div class="col-md-7"><strong><?= $modTerima->posengon->kode; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Suplier'); ?></label>
                            <div class="col-md-7"><strong><?= "<u>".$modTerima->suplier->suplier_nm."</u><br>".$modTerima->suplier->suplier_almt; ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Lokasi Muat'); ?></label>
                            <div class="col-md-7"><strong><?= $modTerima->lokasi_muat; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Asal Kayu'); ?></label>
                            <div class="col-md-7"><strong><?= $modTerima->asal_kayu; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nopol Truck'); ?></label>
                            <div class="col-md-7"><strong><?= $modTerima->nopol; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tally Penerimaan'); ?></label>
                            <div class="col-md-7"><strong><?= $modTerima->diperiksaTally->pegawai_nama; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nota Angkut'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatNumberForUser($modTerima->total_notaangkut_pcs)." Pcs - ".\app\components\DeltaFormatter::formatNumberForUserFloat($modTerima->total_notaangkut_m3)." M<sup>3</sup>"; ?></strong></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-scrollable" id="place-tablecontent"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center"></div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
    getContent(".$modTerima->terima_sengon_id.");
", yii\web\View::POS_READY); ?>
<script>
function getContent(terima_sengon_id){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/ppic/terimasengon/detailrekap','id'=>'']); ?>'+terima_sengon_id,
		type   : 'GET',
		data   : { laporan_params:true },
		success: function (data) {
			$('#modal-detailterima').find("#place-tablecontent").html("");
			if(data.html){
                $('#modal-detailterima').find("#place-tablecontent").html(data.html);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>