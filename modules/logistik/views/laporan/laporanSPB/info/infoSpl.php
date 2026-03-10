<?php
/** @var array $mark_spb */
use app\components\DeltaFormatter;
use app\models\TSpl;

?>
    <div class="modal fade" id="modal-info-splspo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi SPL') ?></h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td><?= Yii::t('app', 'Kode') ?></td>
                        <th>: <?= /** @var TSpl $model */
                            $model->spl_kode.(!empty($model->spl_nomor)?" / ".$model->spl_nomor:"") ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Tanggal') ?></td>
                        <th>: <?= !empty($model->spl_tanggal) ? app\components\DeltaFormatter::formatDateTimeForUser2($model->spl_tanggal) : '' ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Menyetujui') ?></td>
                        <th>: <?= !empty($model->spl_disetujui) ? $model->splDisetujui->pegawai_nama : '' ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Status') ?></td>
                        <th>: <?= !empty($model->spl_status) ? $model->spl_status : '' ?></th>
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
										<th style="text-align: center;"><?= Yii::t('app', 'Harga Estimasi (Rp)') ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Harga Realisasi (Rp)') ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Keterangan') ?></th>
										<!-- <th style="text-align: center; width: 60px;"><?= Yii::t('app', 'Checked') ?></th> -->
									</tr>
								</thead>
								<tbody>
                                    <?php foreach($model->tSplDetails as $key => $detail): ?>
                                        <tr style="<?= in_array($detail->sppd_id, $mark_spb, true) ? 'background: #ffb62b4f;' : '' ?>">
										<td style="text-align: center;"><?= $key + 1 ?></td>
										<td><?= !empty($detail->bhp->bhp_nm) ? $detail->bhp->bhp_nm : '' ?></td>
										<td style="text-align: center;"><?= !empty($detail->spld_qty) ? $detail->spld_qty : '' ?></td>
										<td style="text-align: center;"><?= !empty($detail->spld_harga_estimasi) ? DeltaFormatter::formatUang($detail->spld_harga_estimasi) : '' ?></td>
										<td style="text-align: center;"><?= !empty($detail->spld_harga_realisasi) ? DeltaFormatter::formatUang($detail->spld_harga_realisasi) : '' ?></td>
										<td style="font-size: 1.1rem"><?= !empty($detail->spld_keterangan) ? $detail->spld_keterangan : '' ?></td>
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