<div class="modal fade" id="modal-info-spb" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi SPB'); ?></h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td><?= Yii::t('app', 'Kode'); ?></td>
                        <th>: <?= $model->spb_kode.(!empty($model->spb_nomor)?" / ".$model->spb_nomor:""); ?></th>
                        <td><?= Yii::t('app', 'Status'); ?></td>
                        <th>: <?= !empty($model->spb_status) ? $model->spb_status : ''; ?></th>
                        <td><?= Yii::t('app', 'Menyetujui'); ?></td>
                        <th>: <?= !empty($model->spb_disetujui) ? $model->spbDisetujui->pegawai_nama : ''; ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Tanggal'); ?></td>
                        <th>: <?= !empty(app\components\DeltaFormatter::formatDateTimeForUser2($model->spb_tanggal)) ?></th>
                        <td><?= Yii::t('app', 'Sifat Permintaan'); ?></td>
                        <th>: <?= !empty($model->spb_tipe) ? $model->spb_tipe : ''; ?></th>
                        <td><?= Yii::t('app', 'Mengetahui'); ?></td>
                        <th>: <?= !empty($model->spb_mengetahui) ? $model->spbMengetahui->pegawai_nama : ''; ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Dept. Pemesan'); ?></td>
                        <th>: <?= !empty($model->departement) ? $model->departement->departement_nama : ''; ?></th>
                        <td><?= Yii::t('app', 'Request By'); ?></td>
                        <th>: <?= !empty($model->spb_diminta) ? $model->spbDiminta->pegawai_nama : ''; ?> </th>
                        <td><?= Yii::t('app', 'Catatan Khusus'); ?></td>
                        <th>: <?= !empty($model->spb_keterangan) ? $model->spb_keterangan : ''; ?></th>
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
										<th style="text-align: center;"><?= Yii::t('app', 'Qty Order'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Qty Terpenuhi'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Satuan'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
										<!-- <th style="text-align: center; width: 60px;"><?= Yii::t('app', 'Checked'); ?></th> -->
									</tr>
								</thead>
								<tbody>
                                    <?php foreach($model->tSpbDetails as $key => $detail): ?>
									<tr>
										<td style="text-align: center;"><?= $key + 1 ?></td>
										<td><?= !empty($detail->bhp->bhp_nm) ? $detail->bhp->bhp_nm : '' ?></td>
										<td style="text-align: center;"><?= !empty($detail->spbd_jml) ? $detail->spbd_jml : '' ?></td>
										<td style="text-align: center;"><?= !empty($detail->spbd_jml_terpenuhi) ? $detail->spbd_jml_terpenuhi : '' ?></td>
										<td style="text-align: center;"><?= !empty($detail->spbd_satuan) ? $detail->spbd_satuan : '' ?></td>
										<td style="font-size: 1.1rem"><?= !empty($detail->spbd_ket) ? $detail->spbd_ket : '' ?></td>
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