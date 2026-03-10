<div class="row">
	<div class="col-md-6">
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'No. Kontrak'); ?></label>
			<div class="col-md-6"><strong><?= $model->nomor ?></strong></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Tanggal Kontrak'); ?></label>
            <div class="col-md-6"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></strong></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="col-md-12">
			<h5> &nbsp; </h5>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Perwakilan Perusahaan'); ?></label>
			<div class="col-md-6"><strong><?= $model->pihak1_nama ?></strong></div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Nama Perusahaan'); ?></label>
			<div class="col-md-6"><strong><?= $model->pihak1_perusahaan ?></strong></div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Alamat'); ?></label>
			<div class="col-md-6"><strong><?= $model->pihak1_alamat ?></strong></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="col-md-12">
			<h5> &nbsp; </h5>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Direktur'); ?></label>
			<div class="col-md-6"><strong><?= $model->pihak2Pegawai->pegawai_nama?></strong></div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Direktur Manufatur'); ?></label>
			<div class="col-md-6"><strong><?= $model->pihak2Pegawai2->pegawai_nama?></strong></div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Perusahaan'); ?></label>
			<div class="col-md-6"><strong><?= $model->pihak2_perusahaan ?></strong></div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Alamat'); ?></label>
			<div class="col-md-6"><strong><?= $model->pihak1_alamat ?></strong></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="col-md-12">
			<h5> &nbsp; </h5>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Jenis Log'); ?></label>
			<div class="col-md-6"><strong><?= $model->jenis_log ?></strong></div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Asal Kayu'); ?></label>
			<div class="col-md-6"><strong><?= $model->asal_log ?></strong></div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Kuantitas'); ?></label>
			<div class="col-md-6"><strong><?= $model->kuantitas ?></strong></div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Kualitas'); ?></label>
			<div class="col-md-6"><strong><?= $model->kualitas ?></strong></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="col-md-12">
			<h5> &nbsp; </h5>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Diameter / Komposisi'); ?></label>
			<div class="col-md-6"><strong><?= $model->komposisi?></strong></div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Harga FOB / m'); ?><sup>3</sup></label>
            <div class="col-md-6"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->hargafob) ?></strong></div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-6 control-label"><?= Yii::t('app', 'Lokasi Pemuatan'); ?></label>
			<div class="col-md-6"><strong><?= $model->lokasi_muat ?></strong></div>
		</div>
        <div class="form-group col-md-12">
            <label class="col-md-6 control-label" for="tlogkontrak-uploadfile">File Kontrak</label>
            <div class="col-md-6">
               <a style="font-size:1rem; line-height:1;" href="<?= \yii\helpers\Url::base().'/uploads/pur/kontraklog/'.$model->uploadfile ?>" target="BLANK"><?= $model->uploadfile ?></a>
            </div>
        </div>
	</div>
</div>