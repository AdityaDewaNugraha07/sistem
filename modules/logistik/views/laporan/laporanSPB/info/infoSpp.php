<div class="modal fade" id="modal-info-spp" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi SPP'); ?></h4>
            </div>
            <div class="modal-body">
                <?php 
                    use \app\components\DeltaFormatter;
                ?>
                <table class="table">
                    <tr>
                        <td><?= Yii::t('app', 'Kode'); ?></td>
                        <th>: <?= $model->spp_kode.(!empty($model->spp_nomor)?" / ".$model->spp_nomor:""); ?></th>
                        <td><?= Yii::t('app', 'Dept. Pemesan'); ?></td>
                        <th>: <?= !empty($model->departement) ? $model->departement->departement_nama : ''; ?></th>
                        <td><?= Yii::t('app', 'Catatan'); ?></td>
                        <th>: <?= (!empty($model->spp_catatan) ? $model->spp_catatan: " - "); ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Tanggal'); ?></td>
                        <th>: <?= !empty($model->spp_tanggal) ? DeltaFormatter::formatDateTimeForUser2($model->spp_tanggal) : '' ?></th>
                        <td><?= Yii::t('app', 'Status'); ?></td>
                        <th>: <?= $model->spp_status; ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Tanggal Dibutuhkan'); ?></td>
                        <th>: <?= !empty($model->spp_tanggal_dibutuhkan) ? DeltaFormatter::formatDateTimeForUser2($model->spp_tanggal_dibutuhkan) : '' ?></th>
                        <td><?= Yii::t('app', 'Menyetujui'); ?></td>
                        <th>: <?= !empty($model->spp_disetujui) ? $model->sppDisetujui->pegawai_nama : ''; ?> </th>
                    </tr>
                </table>
				<div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr style="background-color: #F1F4F7; ">
										<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Items'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Qty'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Status'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
										<!-- <th style="text-align: center; width: 60px;"><?= Yii::t('app', 'Checked'); ?></th> -->
									</tr>
								</thead>
								<tbody>
                                    <?php foreach($model->tSppDetails as $key => $detail): ?>
									<tr>
										<td style="text-align: center;"><?= $key + 1 ?></td>
										<td><?= !empty($detail->bhp->bhp_nm) ? $detail->bhp->bhp_nm : '' ?></td>
										<td style="text-align: center;"><?= !empty($detail->sppd_qty) ? $detail->sppd_qty : ''  ?></td>
										<td style="text-align: center;"><?= !empty($detail->status_closed) ? $detail->status_closed : '' ?></td>
										<td style="font-size: 1.1rem"><?= !empty($detail->sppd_ket) ? $detail->sppd_ket : '' ?></td>
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