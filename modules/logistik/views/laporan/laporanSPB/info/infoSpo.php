<?php
/** @var array $mark_spb */
use app\components\DeltaFormatter;
use app\models\TSpo;

?>
    <div class="modal fade" id="modal-info-splspo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi SPO') ?></h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td><?= Yii::t('app', 'Kode') ?></td>
                        <th>: <?= /** @var TSpo $model */
                            $model->spo_kode.(!empty($model->spo_nomor)?" / ".$model->spo_nomor:"") ?></th>
                        <td><?= Yii::t('app', 'Status') ?></td>
                        <th>: <?= !empty($model->approve_status) ? $model->approve_status : '' ?></th>
                        <td><?= Yii::t('app', 'Menyetujui') ?></td>
                        <th>: <?= !empty($model->spo_disetujui) ? $model->spoDisetujui->pegawai_nama : '' ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Tanggal') ?></td>
                        <th>: <?= !empty($model->spo_tanggal) ? app\components\DeltaFormatter::formatDateTimeForUser2($model->spo_tanggal) : '' ?></th>
                        <td><?= Yii::t('app', 'Status Bayar') ?></td>
                        <th>: <?= $model->spo_status_bayar ?></th>
                        <td><?= Yii::t('app', 'Tanggal Approve') ?></td>
                        <th>: <?= !empty($model->approve_date) ? app\components\DeltaFormatter::formatDateTimeForUser2($model->approve_date) : '' ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Suplier') ?></td>
                        <th>: <?= !empty($model->suplier->suplier_nm) ? $model->suplier->suplier_nm : '' ?></th>
                        <td><?= Yii::t('app', 'Catatan Khusus') ?></td>
                        <th>: <?= !empty($model->cancel_transaksi_id) ? $model->cancelTransaksi->cancel_reason : '' ?></th>
                    </tr>
                </table>
				<div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr style="background-color: #F1F4F7; ">
										<th style="text-align: center;"><?= Yii::t('app', 'No.') ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Items') ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Qty') ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Qty Terpenuhi') ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Keterangan') ?></th>
										<!-- <th style="text-align: center; width: 60px;"><?= Yii::t('app', 'Checked') ?></th> -->
									</tr>
								</thead>
								<tbody>
                                    <?php foreach($model->tSpoDetails as $key => $detail): ?>
                                        <tr style="<?= in_array($detail->spod_id, $mark_spb, true) ? 'background: #ffb62b4f;' : '' ?>">
										<td style="text-align: center;"><?= $key + 1 ?></td>
										<td><?= !empty($detail->bhp->bhp_nm) ? $detail->bhp->bhp_nm : '' ?> - <?= $detail->spod_id ?></td>
										<td style="text-align: center;"><?= !empty($detail->spod_qty) ? $detail->spod_qty : '' ?></td>
										<td style="text-align: center;"><?= !empty($detail->spod_harga) ? DeltaFormatter::formatUang($detail->spod_harga) : '' ?></td>
										<td style="font-size: 1.1rem"><?= !empty($detail->spod_keterangan) ? $detail->spod_keterangan : '' ?></td>
									</tr>
                                    <?php endforeach ?>
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