<?php app\assets\DatatableAsset::register($this); ?>
<style>
table{
	font-size: 1.4rem;
}
</style>
<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Kontrak Log Alam'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode PO'); ?></label>
							<div class="col-md-7"><strong><?= $model->kode ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal PO'); ?></label>
							<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_po) ?></strong></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'No. Kontrak'); ?></label>
							<div class="col-md-7"><strong><?= $model->nomor ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Kontrak'); ?></label>
							<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></strong></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="col-md-12">
							<h5> &nbsp; </h5>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Perwakilan Perusahaan'); ?></label>
							<div class="col-md-7"><strong><?= $model->pihak1_nama ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Perusahaan'); ?></label>
							<div class="col-md-7"><strong><?= $model->pihak1_perusahaan ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Alamat'); ?></label>
							<div class="col-md-7"><strong><?= $model->pihak1_alamat ?></strong></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-12">
							<h5> &nbsp; </h5>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Direktur'); ?></label>
							<div class="col-md-7"><strong><?= $model->pihak2Pegawai->pegawai_nama?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Direktur Manufatur'); ?></label>
							<div class="col-md-7"><strong><?= $model->pihak2Pegawai2->pegawai_nama?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Perusahaan'); ?></label>
							<div class="col-md-7"><strong><?= $model->pihak2_perusahaan ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Alamat'); ?></label>
							<div class="col-md-7"><strong><?= $model->pihak1_alamat ?></strong></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="col-md-12">
							<h5> &nbsp; </h5>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Log'); ?></label>
							<div class="col-md-7"><strong><?= $model->jenis_log ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Asal Kayu'); ?></label>
							<div class="col-md-7"><strong><?= $model->asal_log ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Kuantitas'); ?></label>
							<div class="col-md-7"><strong><?= $model->kuantitas ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Kualitas'); ?></label>
							<div class="col-md-7"><strong><?= $model->kualitas ?></strong></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-12">
							<h5> &nbsp; </h5>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Diameter / Komposisi'); ?></label>
							<div class="col-md-7"><strong><?= $model->komposisi?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Harga FOB / m'); ?><sup>3</sup></label>
							<div class="col-md-7"><strong><?= $model->hargafob ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Lokasi Pemuatan'); ?></label>
							<div class="col-md-7"><strong><?= $model->lokasi_muat ?></strong></div>
						</div>
						<div class="form-group col-md-12">
                            <label class="col-md-6 control-label" for="tlogkontrak-uploadfile">File Kontrak</label>
                            <div class="col-md-6">
                               <a style="font-size:1rem; line-height:1;" href="<?= \yii\helpers\Url::base().'/uploads/pur/kontraklog/'.$model->uploadfile ?>" target="BLANK"><?= $model->uploadfile ?></a>
                            </div>
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
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>