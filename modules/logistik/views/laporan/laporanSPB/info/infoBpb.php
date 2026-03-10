<div class="modal fade" id="modal-info-bpb" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi BPB'); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                    use \app\components\DeltaFormatter;
                ?>
                <table class="table">
                    <tr>
                        <td><?= Yii::t('app', 'Kode'); ?></td>
                        <th>: <?= $model->bpb_kode.(!empty($model->bpb_nomor)?" / ".$model->bpb_nomor:""); ?></th>
                        <td><?= Yii::t('app', 'Tanggal Keluar'); ?></td>
                        <th>: <?= !empty($model->bpb_tgl_keluar) ? DeltaFormatter::formatDateTimeForUser($model->bpb_tgl_keluar): '' ?></th>
                        <td><?= Yii::t('app', 'tanggal Terima'); ?></td>
                        <th>: <?= !empty($model->bpb_tgl_diterima) ? DeltaFormatter::formatDateTimeForUser($model->bpb_tgl_diterima) : ''; ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Status'); ?></td>
                        <th>: <?= !empty($model->bpb_status) ? $model->bpb_status : '' ?></th>
                        <td><?= Yii::t('app', 'Dikeluarkan'); ?></td>
                        <th>: <?= !empty($model->bpbDikeluarkan) ? $model->bpbDikeluarkan->pegawai_nama : ''; ?></th>
                        <td><?= Yii::t('app', 'Diterima'); ?></td>
                        <th>: <?= $model->bpbDiterima ? $model->bpbDiterima->pegawai_nama : ''; ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Departemen'); ?></td>
                        <th>: <?= $model->departement->departement_nama; ?></th>
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
										<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
										<!-- <th style="text-align: center; width: 60px;"><?= Yii::t('app', 'Checked'); ?></th> -->
									</tr>
								</thead>
								<tbody>
                                    <?php foreach($model->tBpbDetails as $key => $detail): ?>
									<tr>
										<td style="text-align: center;"><?= $key + 1 ?></td>
										<td><?= !empty($detail->bhp->bhp_nm) ? $detail->bhp->bhp_nm : '' ?></td>
										<td style="text-align: center;"><?= !empty($detail->bpbd_jml) ? $detail->bpbd_jml: '' ?></td>
										<td style="font-size: 1.1rem"><?= !empty($detail->bpbd_ket) ? $detail->bpbd_ket: '' ?></td>
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