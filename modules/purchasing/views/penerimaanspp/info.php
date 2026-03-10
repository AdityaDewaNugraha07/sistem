<?php \app\assets\Select2Asset::register($this); ?>
<div class="modal fade" id="modal-info" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail SPP'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Kode SPP'); ?></strong></label>
                            <div class="col-md-7"><?= $model->spp_kode ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Nomor Berkas SPP'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->spp_nomor)?$model->spp_nomor:" - ") ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Dept. Pemesan'); ?></strong></label>
                            <div class="col-md-7"><?= $model->departement->departement_nama; ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Tanggal SPP'); ?></strong></label>
                            <div class="col-md-7"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->spp_tanggal); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Status (Terima)'); ?></strong></label>
                            <div class="col-md-7">
								<?= $model->getStatusSPP($model->spp_id); ?>
                            </div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Status'); ?></strong></label>
                            <div class="col-md-7">
								<?= $model->spp_status; ?>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Disetujui Oleh'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->spp_disetujui)?$model->sppDisetujui->pegawai_nama:" - "); ?></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Tanggal Disetujui'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->spp_tanggal_disetujui)?\app\components\DeltaFormatter::formatDateTimeForUser2($model->spp_tanggal_disetujui):"-"); ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Catatan'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->spp_catatan)?$model->spp_catatan:" - "); ?></div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
								<thead>
									<tr>
										<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
										<th><?= Yii::t('app', 'Kode Barang'); ?></th>
										<th><?= Yii::t('app', 'Nama Barang'); ?></th>
										<th><?= Yii::t('app', 'Jenis Barang'); ?></th>
										<th><?= Yii::t('app', 'Qty Permintaan'); ?></th>
										<th><?= Yii::t('app', 'Qty Terbeli'); ?></th>
										<th><?= Yii::t('app', 'Sat'); ?></th>
										<th><?= Yii::t('app', 'Keterangan'); ?></th>
										<th><?= Yii::t('app', 'Status'); ?></th>
                                    <th style="width: 250px;"><?= Yii::t('app', 'Set Supplier'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($modDetail as $i => $detail){ ?>
									<tr>
										<td><?php echo $i+1 ?></td>
										<td><?php echo $detail->bhp->bhp_kode; ?></td>
										<td><?php echo $detail->bhp->Bhp_nm; ?></td>
										<td><?php echo $detail->bhp->bhp_group; ?></td>
										<td style="text-align: center;"><?php echo $detail->sppd_qty; ?></td>
										<td style="text-align: center;"><?php echo $detail->QtyTerbeli['qty']; ?></td>
										<td style="text-align: center;"><?= $detail->bhp->bhp_satuan; ?></td>
										<td style="padding: 5px; font-size: 1.1rem;"><?= !empty($detail->sppd_ket)?$detail->sppd_ket:"<center>-</center>"; ?></td>
										<td style="text-align: center;"><?= $detail->StatusSppDetail; ?></td>
										<td>
											<?php
											if(strpos($detail->StatusSppDetail, 'COMPLETE')){
												echo yii\bootstrap\Html::activeDropDownList($detail, 'suplier_id', \app\models\MSuplier::getOptionList('BHP'),['class'=>'form-control select2','prompt'=>'','disabled'=>TRUE]);
											}else{
												echo yii\bootstrap\Html::activeDropDownList($detail, 'suplier_id', \app\models\MSuplier::getOptionList('BHP'),['class'=>'form-control select2','prompt'=>'','onchange'=>'setSupplier(this,'.$detail->sppd_id.')']);
											}
											?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
$(\".tooltips\").tooltip({ delay: 50 });
$('select[name*=\"[suplier_id]\"]').select2({
	allowClear: !0,
	placeholder: 'Pilih Supplier',
	width: null
});
$.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script>
function setSupplier(ele,sppd_id){
	var suplier_id = $(ele).val();
	$('select[name*=\"[suplier_id]\"]').parents('td').find('.select2').addClass('animation-loading');
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/setSupplier']); ?>',
		type   : 'POST',
		data   : {suplier_id:suplier_id,sppd_id:sppd_id},
		success: function (data) {
			if(data){
				$('select[name*=\"[suplier_id]\"]').parents('td').find('.select2').removeClass('animation-loading');
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>