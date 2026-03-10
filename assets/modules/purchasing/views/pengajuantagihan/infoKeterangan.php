<div class="modal fade" id="modal-info-keterangan" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Keterangan Kelengkapan Berkas');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-10">
						<u><b>Keterangan Pengajuan : </b></u><br>
						<?= !empty($model->keterangan_berkas)?$model->keterangan_berkas:"<center>-</center>"; ?>
						<br><br>
						<u><b>Riwayat Aktifitas : </b></u><br>
						<div style="font-size: 1.2rem;">
							<table border="0" style="width: 100%;">
								<tr>
									<td style="padding-left: 15px;"></td>
									<td style="padding-left: 5px;">- <?= "Diajukan Pada ". app\components\DeltaFormatter::formatDateTimeForUser2($model->created_at)." Oleh ".app\models\MUser::findIdentity($model->created_by)->pegawai->pegawai_nama."<br>"; ?></td>
								</tr>
								<tr>
									<td style="padding-left: 15px;"></td>
									<td style="padding-left: 5px;"><?= !empty($model->keterangan)?"- ".$model->keterangan:""; ?></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="col-md-1"></div>
                </div>
				<br>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>