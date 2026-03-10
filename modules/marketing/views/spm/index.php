<?php
/* @var $this yii\web\View */

use app\models\TSpmKo;

$this->title = 'Surat Perintah Muat (SPM)';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-5 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<span class="pull-right">
<!--						<a class="btn dark btn-sm btn-outline" href="<?php // echo yii\helpers\Url::toRoute("/marketing/spm/scanSpm") ?>"><i class="icon-frame"></i> <?php // echo Yii::t('app', 'Scan Pemuatan'); ?></a>-->
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'SPM Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
								<span class="caption-subject bold"><h4>
								<?php
								if(isset($_GET['spm_ko_id'])){
									echo "Data SPM";
								}else if(isset($_GET['realisasi'])){
									echo "SPM Realisasi";
								}else{
									echo "SPM Baru";
								}
								?>
								</h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?php if(isset($_GET['spm_ko_id'])){ ?>
											<?= yii\bootstrap\Html::activeHiddenInput($model, "op_ko_id"); ?>
											<?= $form->field($model, 'kode_op')->textInput()->label("Kode OP"); ?>
											<?= yii\bootstrap\Html::activeHiddenInput($model, "terima_logalam_id"); ?>
										<?php }else{ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode OP'); ?></label>
												<div class="col-md-7">
													<span class="input-group-btn" style="width: 100%">
														<?= \yii\bootstrap\Html::activeDropDownList($model, 'op_ko_id', [],['class'=>'form-control select2','prompt'=>'','onchange'=>'setOP()','style'=>'width:100%;']); ?>
													</span>
													<span class="input-group-btn" style="width: 20%">
														<a class="btn btn-icon-only btn-default tooltips" onclick="openOP();" data-original-title="Daftar OP" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'cust_id'); ?>
										<?= $form->field($model, 'jenis_produk')->textInput(['disabled'=>'disabled'])->label("Jenis Produk"); ?>
										<?= $form->field($model, 'cust_an_nama')->textInput(['disabled'=>'disabled'])->label("Nama Customer"); ?>
										<?= $form->field($model, 'cust_pr_nama')->textInput(['disabled'=>'disabled'])->label("Nama Perusahaan") ?>
										<?= $form->field($model, 'cust_alamat')->textarea(['disabled'=>'disabled'])->label("Alamat Customer") ?>
										<?= $form->field($model, 'alamat_bongkar')->textarea(); ?>
									</div>
									<div class="col-md-5">
										<?php 
										if(!isset($_GET['spm_ko_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode SPM'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'tanggal',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'tanggal_kirim',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'kendaraan_nopol')->textInput(); ?>
										<?= $form->field($model, 'kendaraan_supir')->textInput(); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'dibuat'); ?>
										<?php // echo $form->field($model, 'dibuat_display')->textInput(["disabled"=>"disabled"])->label("Dibuat Oleh"); ?>
										<?= $form->field($model, 'disetujui')->dropDownList(\app\models\MPegawai::getOptionListByDept(\app\components\Params::DEPARTEMENT_ID_MARKETING),['class'=>'form-control select2','prompt'=>''])->label('Disetujui Oleh'); ?>
										<?= $form->field($model, 'tanggal_rencanamuat',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?php if($model->status == app\models\TSpmKo::REALISASI){ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-5 control-label">Status</label>
												<div class="col-md-7 text-align-center">
													<h4 style="background-color: #95EBA3;"><b>SUDAH REALISASI</b></h4>
												</div>
											</div>
											<?= yii\bootstrap\Html::activeHiddenInput($model, 'status'); ?>
											<?= $form->field($model, 'waktu_mulaimuat',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date form_datetime bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
											<?= $form->field($model, 'waktu_selesaimuat',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date form_datetime bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
											<?= $form->field($model, 'diperiksa')->dropDownList(\app\models\MPegawai::getOptionListWithDeptName(),['class'=>'form-control select2','prompt'=>''])->label('Diperiksa Oleh'); ?>
											<?= $form->field($model, 'diperiksa_security')->dropDownList(\app\models\MPegawai::getOptionListWithDeptName(),['class'=>'form-control select2','prompt'=>''])->label('Diperiksa Security'); ?>
											<?= $form->field($model, 'dikeluarkan')->dropDownList(\app\models\MPegawai::getOptionListWithDeptName(),['class'=>'form-control select2','prompt'=>''])->label('Dikeluarkan Oleh'); ?>
										<?php }else{ ?>
											<?php if(isset($_GET['edit'])){ ?>
												<div class="form-group field-tspmko-status" style="margin-bottom: 5px;">
													<label class="col-md-5 control-label" for="tspmko-status">Status</label>
													<div class="col-md-7">
														<select id="tspmko-status" class="form-control" name="TSpmKo[status]" style="width:200px;" onchange="setStatus();">
															<option value="" style="background-color: #FBE88C" selected="">Belum Realisasi</option>
															<option value="<?= app\models\TSpmKo::REALISASI; ?>" style="background-color: #95EBA3">Sudah Realisasi</option>
														</select>
													</div>
												</div>
												<div id="place-waktuselesaimuat" style="display: none;">
												<?= $form->field($model, 'waktu_mulaimuat',[
																		'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date form_datetime bs-datetime">{input} <span class="input-group-addon">
																		<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																		{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
												<?= $form->field($model, 'waktu_selesaimuat',[
																		'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date form_datetime bs-datetime">{input} <span class="input-group-addon">
																		<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																		{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
												<?= $form->field($model, 'diperiksa')->dropDownList(\app\models\MPegawai::getOptionListMarketing(),['class'=>'form-control select2','prompt'=>''])->label('Diperiksa Oleh'); ?>
												<?= $form->field($model, 'diperiksa_security')->dropDownList(\app\models\MPegawai::getOptionListCheckerSecurity(),['class'=>'form-control select2','prompt'=>''])->label('Diperiksa Security'); ?>
												<?= $form->field($model, 'dikeluarkan')->dropDownList(\app\models\MPegawai::getOptionListMarketing(),['class'=>'form-control select2','prompt'=>''])->label('Dikeluarkan Oleh'); ?>
												</div>
												<!-- <div id="place-tarikdata" style="display: none;">
													<label class="col-md-5 control-label" for="tspmko-tarik_data">SPM Peruntukan</label>
													<div class="col-md-7">
														<select id="tspmko-tarik_data" class="form-control" name="TSpmKo[tarik_data]" style="width:100%;" onchange="setTarikData();">
															<option value="Gudang" selected>Gudang</option>
															<option value="Pelabuhan">Pelabuhan</option>
														</select>
													</div>
												</div> -->
											<?php }else if(isset($_GET['spm_ko_id'])){ ?>
												<div class="form-group" style="margin-bottom: 5px;">
													<label class="col-md-5 control-label">Status</label>
													<div class="col-md-7 text-align-center">
														<h4 style="background-color: #FBE88C;"><b>BELUM REALISASI</b></h4>
														<h4><a class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Update SPM" onclick="edit(<?= $model->spm_ko_id; ?>)"><i class="fa fa-edit"></i> Update</a></h4>
													</div>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
                                <!-- Bagian Realisasi Produk List -->
								<div id="place-produklist" style="display: none;">
								<br><hr>
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: -15px; margin-bottom: -10px;">
                                        <h5><?= Yii::t('app', 'Realisasi Product List'); ?></h5>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover table-laporan" style="width: 90%" id="table-detail-produklist">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th rowspan="2" style="width: 90px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
														<th rowspan="2" style="width: 150px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode Produk'); ?></th>
														<th rowspan="2" style="width: 150px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Nama Produk'); ?></th>
														<th colspan="3" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty'); ?></th>
														<th rowspan="2" style="width: 50px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Cancel'); ?></th>
													</tr>
													<tr>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Palet'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 120px; padding: 5px;"><?= Yii::t('app', 'Satuan Kecil'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
												<tfoot>
													<tr>
														<td colspan="6">
															<a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="addItemProductList();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
								</div>
                                <!-- Bagian Realisasi Log List -->
								<div id="place-loglist" style="display: none;">
								<br><hr>
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: -15px; margin-bottom: -10px;">
                                        <h5>
											<?= Yii::t('app', 'Realisasi Log List'); ?>
										</h5>
                                    </div>
									<?php 
									// if(isset($_GET['spm_ko_id']) && $model->status != app\models\TSpmKo::REALISASI){ 
									// 	$cust_id = $model->cust_id;
									// 	$kode = $model->kode;	
									?>
									<!-- <div class="col-md-6" style="margin-top: -15px; margin-bottom: 10px; float:right;" id='spm-tarik-data'> -->
										<?php //echo $form->field($model, 'tarik_log')->dropDownList(\app\models\TSpmKo::getOptionListSpmPelabuhan($cust_id, $kode),['class'=>'form-control select2','prompt'=>'Tarik Data Log', 'onchange'=>'setItemLogPelabuhan()'])->label(''); ?>
											<!-- <select id="tspmko-tarik_log" name="TSpmKo[tarik_log]" style="width:70%;">
												<option value="" selected>Tarik Data Log</option>
											</select>	 -->
									<!-- </div> -->
									<?php //} ?>
                                </div>
								<div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover table-laporan" style="width: 90%" id="table-detail-loglist">
												<thead>
													<tr>
														<th rowspan="2" style="width: 20px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th rowspan="2" style="width: 130px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'No. QR Code'); ?></th>
														<th rowspan="2" style="width: 110px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
														<th rowspan="2" style="width: 70px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'No.<br>Lapangan'); ?></th>
														<th rowspan="2" style="width: 70px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'No.<br>Grade'); ?></th>
														<th rowspan="2" style="width: 70px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'No.<br>Batang'); ?></th>
														<th colspan="9" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Ukuran Log'); ?></th>
														<th colspan="9" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Ukuran Realisasi Log'); ?></th>
														<th rowspan="2" style="width: 30px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Volume'); ?></th>
														<th rowspan="2" style="width: 30px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Cancel'); ?></th>
													</tr>
													<tr>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', 'Panjang'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', '⌀<br>Ujung1'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', '⌀<br>Ujung2'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', '⌀<br>Pangkal1'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', '⌀<br>Pangkal2'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', 'Cacat<br>Panjang'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', 'Cacat Gb'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', 'Cacat Gr'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', 'Volume'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', 'Panjang'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', '⌀<br>Ujung1'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', '⌀<br>Ujung2'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', '⌀<br>Pangkal1'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', '⌀<br>Pangkal2'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', '⌀<br>Rata'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', 'Cacat<br>Panjang'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', 'Cacat Gb'); ?></th>
														<th style="font-size: 1.1rem; line-height: 0.9; width: 30px; padding: 5px;"><?= Yii::t('app', 'Cacat Gr'); ?></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
												<tfoot>
													<tr>
														<td colspan="6">
															<a class="btn btn-xs blue-hoki" id="btn-add-item-log" onclick="addItemLogList();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
								</div>
								<br><hr>
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: -15px; margin-bottom: -10px;">
                                        <h5><?= Yii::t('app', 'Detail SPM'); ?></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th class="place-diameter"rowspan="2" style="width: 400px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Produk'); ?></th>
														<th class="place-diameter-log"rowspan="2" style="width: 300px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Produk'); ?></th>
														<th class="place-diameter-log" rowspan="2" style="width: 100px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Range Diameter'); ?></th>
														<th colspan="3" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty Pesan'); ?></th>
														<th colspan="3" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty Realisasi'); ?></th>
													</tr>
													<tr>
														<th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px;"><?= Yii::t('app', 'Palet'); ?></th>
														<th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 100px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
														<th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
														<th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px;"><?= Yii::t('app', 'Palet'); ?></th>
														<th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 100px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
														<th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 70px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                                                        
														<th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px; display: none;"><?= Yii::t('app', '-'); ?></th>
														<th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 100px; padding: 5px; display: none;"><?= Yii::t('app', 'Satuan<br>Beli'); ?></th>
														<th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px; display: none;"><?= Yii::t('app', 'Satuan<br>Angkut'); ?></th>
														<th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px; display: none;"><?= Yii::t('app', '-'); ?></th>
														<th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 100px; padding: 5px; display: none;"><?= Yii::t('app', 'Satuan<br>Beli'); ?></th>
														<th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 70px; padding: 5px; display: none;"><?= Yii::t('app', 'Satuan<br>Angkut'); ?></th>

                                                        <th class="place-satuan-log" style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px; display: none;"><?= Yii::t('app', '-'); ?></th>
														<th class="place-satuan-log" style="font-size: 1.2rem; line-height: 0.9; width: 100px; padding: 5px; display: none;"><?= Yii::t('app', 'Satuan<br>Beli'); ?></th>
														<th class="place-satuan-log" style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px; display: none;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
														<th class="place-satuan-log" style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px; display: none;"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
														<th class="place-satuan-log" style="font-size: 1.2rem; line-height: 0.9; width: 100px; padding: 5px; display: none;"><?= Yii::t('app', 'Satuan<br>Beli'); ?></th>
														<th class="place-satuan-log" style="font-size: 1.2rem; line-height: 0.9; width: 70px; padding: 5px; display: none;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
													</tr>
												</thead>
												<tbody>

												</tbody>
												<tfoot>
													<tr>
														<td colspan="7" style="display: none;" id="place-addProduk">
															<a class="btn btn-xs blue-hoki" onclick="addProduk();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Produk'); ?></a>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printSPM('.(isset($_GET['spm_ko_id'])?$_GET['spm_ko_id']:'').');','disabled'=>true]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
$pagemode = "";
if(isset($_GET['spm_ko_id'])){
    $pagemode = "afterSave(".$_GET['spm_ko_id'].");";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	$('.place-diameter-log').css('display','none');
	$('.place-diameter').css('display','');
	$('select[name*=\"[op_ko_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode OP',
		ajax: {
			url: '".\yii\helpers\Url::toRoute('/marketing/orderpenjualan/findOP')."',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		}
	});
	$('select[name*=\"[diperiksa]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
		width: '100%'
	});
	$('select[name*=\"[diperiksa_security]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Security',
		width: '100%'
	});
	$('select[name*=\"[dikeluarkan]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
		width: '100%'
	});
	$('select[name*=\"[disetujui]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
		width: '100%'
	});
	$('.form_datetime').datetimepicker({
		autoclose: !0,
		isRTL: App.isRTL(),
		format: 'dd/mm/yyyy - hh:ii',
		fontAwesome: !0,
		pickerPosition: App.isRTL() ? 'bottom-right' : 'bottom-left',
		orientation: 'left',
		clearBtn:true,
		todayHighlight:true
    });  
	// $('#spm-tarik-data').css('display','none');
	// $('select[name*=\"[tarik_log]\"]').select2({
	// 	allowClear: !0,
	// 	placeholder: 'Tarik Data Log',
	// 	width: '100%'
	// });
", yii\web\View::POS_READY); ?>
<script>
function setOP(){
	var op_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/setOP']); ?>',
        type   : 'POST',
        data   : {op_ko_id:op_ko_id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_pr_nama") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_alamat") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_kirim") ?>").val('');
			$('#table-detail tbody').html("");
			if(data.op_ko_id){
				$("#modal-master").find('button.fa-close').trigger('click');
				$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val(data.jenis_produk);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val(data.cust.cust_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val(data.cust.cust_an_nama);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_pr_nama") ?>").val(data.cust.cust_pr_nama);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_alamat") ?>").val(data.cust.cust_pr_alamat ? data.cust.cust_pr_alamat : data.cust.cust_an_alamat);
				$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val(data.alamat_bongkar);
				$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_kirim") ?>").val(data.tanggal_kirim);
				getItems(op_ko_id);
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function openOP(){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/spm/openOP']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pick(op_ko_id,kode){
	$("#modal-master").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").empty().append('<option value="'+op_ko_id+'">'+kode+'</option>').val(op_ko_id).trigger('change');
}

function getItems(op_ko_id){
    var jns_produk = $("#<?= yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
    if(jns_produk == "Limbah"){
        $(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","");
        $(".place-satuan-log").css("display","none");
    } else if(jns_produk == "Log"){
		$(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","none");
		$(".place-satuan-log").css("display","");
    } else{
        $(".place-satuan-produk").css("display","");
        $(".place-satuan-limbah").css("display","none");
        $(".place-satuan-log").css("display","none");
		$(".place-diameter").css("display","none");
    }
	if(jns_produk == "Log"){
		$(".place-diameter-log").css("display","");
		$(".place-diameter").css("display","none");
		// $('.spm-tarik-data').css("display","");
	} else {
		$(".place-diameter-log").css("display","none");
		$(".place-diameter").css("display","");
		// $('.spm-tarik-data').css("display","none");
	}
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/getItems']); ?>',
		type   : 'POST',
		data   : {op_ko_id:op_ko_id},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
                if(jns_produk == "JasaKD" || jns_produk == "JasaGesek" || jns_produk == "JasaMoulding"){
                    reordertable("#table-detail");
                }
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function save(){
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_mulaimuat") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_selesaimuat") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>").parents(".form-group").removeClass("has-error");
	// $("#<?= \yii\bootstrap\Html::getInputId($model, "tarik_data") ?>").parents(".form-group").addClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_mulaimuat") ?>").parents(".form-group").addClass("has-success");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_selesaimuat") ?>").parents(".form-group").addClass("has-success");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>").parents(".form-group").addClass("has-success");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").parents(".form-group").addClass("has-success");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>").parents(".form-group").addClass("has-success");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>").parents(".form-group").addClass("has-success");
	//$("#<?= \yii\bootstrap\Html::getInputId($model, "tarik_data") ?>").parents(".form-group").addClass("has-success");
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
            return false;
        }

		
		if($("#<?= yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val() == "Log"){
			<?php if(isset($_GET['spm_ko_id'])){ 
				if($model->status !== 'REALISASI'){?>
					validatingKubikasi(function (isValid) { 
						if (isValid && validatingDetail()) {
							submitform($form);
						}
					});
			<?php } else { ?>
				if(validatingDetail()){
					submitform($form);
				}
			<?php }
			} else { ?>
				if(validatingDetail()){
					submitform($form);
				}
			<?php } ?>
		} else {
			if(validatingDetail()){
				submitform($form);
			}
		}
				
    }
    return false;
}

function validatingDetail($form){
	var has_error = 0;
	var jns_produk = $("#<?= yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
	var status = $("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val();
	var diperiksa = $("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>").val();
	var diperiksa_security = $("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").val();
	var dikeluarkan = $("#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>").val();
	var disetujui = $("#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>").val();
	if(status == "<?= app\models\TSpmKo::REALISASI ?>"){
		if(!$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_mulaimuat") ?>").val()){
			$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_mulaimuat") ?>").parents(".form-group").addClass("has-error");
			$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_mulaimuat") ?>").parents(".form-group").removeClass("has-success");
			has_error = has_error + 1;
		}
		if(!$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_selesaimuat") ?>").val()){
			$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_selesaimuat") ?>").parents(".form-group").addClass("has-error");
			$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_selesaimuat") ?>").parents(".form-group").removeClass("has-success");
			has_error = has_error + 1;
		}
		if(!diperiksa){
			$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>").parents(".form-group").addClass("has-error");
			$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>").parents(".form-group").removeClass("has-success");
			has_error = has_error + 1; 
		}
		if(!diperiksa_security){
			$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").parents(".form-group").addClass("has-error");
			$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").parents(".form-group").removeClass("has-success");
			has_error = has_error + 1;
		}
		if(!dikeluarkan){
			$("#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>").parents(".form-group").addClass("has-error");
			$("#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>").parents(".form-group").removeClass("has-success");
			has_error = has_error + 1;
		}
		if(!disetujui){
			$("#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>").parents(".form-group").addClass("has-error");
			$("#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>").parents(".form-group").removeClass("has-success");
			has_error = has_error + 1;
		}
		$('#table-detail-produklist tbody > tr').each(function(){
			var field1 = $(this).find('select[name*="[nomor_produksi]"]');
            if( jns_produk == "Limbah" || jns_produk == "JasaKD" || jns_produk == "JasaGesek" || jns_produk == "JasaMoulding"){
                
            }else{
                if(!field1.val()){
                    $(this).find('select[name*="[nomor_produksi]"]').parents('td').addClass('error-tb-detail');
                    has_error = has_error + 1;
                }else{
                    $(this).find('select[name*="[nomor_produksi]"]').parents('td').removeClass('error-tb-detail');
                }
            }
		});
	if(status !== "<?= app\models\TSpmKo::REALISASI ?>"){
        $('#table-detail-loglist tbody > tr').each(function(){
			var field1 = $(this).find('select[name*="[no_barcode]"]');
			var terima_logalam_id = $("#<?= yii\bootstrap\Html::getInputId($model, "terima_logalam_id") ?>").val();
			// console.log('validate '+ tarik_log);
			if(!terima_logalam_id){
			// 	if( jns_produk == "Log"){
					if(!field1.val()){
						$(this).find('select[name*="[no_barcode]"]').parents('td').addClass('error-tb-detail');
						has_error = has_error + 1;
					}else{
						$(this).find('select[name*="[no_barcode]"]').parents('td').removeClass('error-tb-detail');
					}
				// }else{
					
				// }
			}
            
		});
	}
		$('#table-detail tbody > tr').each(function(){
			var qty_kecil = unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
			var qty_kecil_realisasi = unformatNumber( $(this).find('input[name*="[qty_kecil_realisasi]"]').val() );
			var qty_besar = unformatNumber( $(this).find('input[name*="[qty_besar]"]').val() );
			var qty_besar_realisasi = unformatNumber( $(this).find('input[name*="[qty_besar_realisasi]"]').val() );
			var kubikasi = unformatNumber( $(this).find('input[name*="[kubikasi]"]').val() );
			var kubikasi_realisasi = unformatNumber( $(this).find('input[name*="[kubikasi_realisasi]"]').val() );
			
			if(jns_produk == "Log"){
				// jika alias true maka 0 valid
				// NOTE : validasi kubikasi realisasi dikomen karena 26/7/25 bisa save 0
				// if($(this).find('input[name*="[alias]"]').val()){
				// 	$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').removeClass('error-tb-detail');
				// } else {
				// 	if(!kubikasi_realisasi){
				// 		$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').addClass('error-tb-detail');
				// 		has_error = has_error + 1+'i';
				// 	} else {
				// 		$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').removeClass('error-tb-detail');
				// 	}
				// }


				// Validasi data, apabila kubikasi kurang dari permintaan, maka gabisa dilanjut
				// Namun, jika kubikasi lebih dari atau sama dengan permintaan maka bisa dilanjut
				// if(!kubikasi_realisasi){
				// 	$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').addClass('error-tb-detail');
				// 	has_error = has_error + 1;
				// }else{
				// 	// 18/1/25 bisa save kecuali 0
					// // if((kubikasi_realisasi == kubikasi) || (kubikasi_realisasi > kubikasi)){
					// 	$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').removeClass('error-tb-detail');
					// // }else{
					// // 	$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').addClass('error-tb-detail');
					// // 	has_error = has_error + 1;
					// // }
				// }
			} else {
				if(!qty_kecil_realisasi){
					$(this).find('input[name*="[qty_kecil_realisasi]"]').parents('td').addClass('error-tb-detail');
					has_error = has_error + 1;
				}else{
					if((qty_kecil_realisasi <= 0) || (qty_kecil != qty_kecil_realisasi)){
						$(this).find('input[name*="[qty_kecil_realisasi]"]').parents('td').addClass('error-tb-detail');
						has_error = has_error + 1;
					}else{
						$(this).find('input[name*="[qty_kecil_realisasi]"]').parents('td').removeClass('error-tb-detail');
					}
				}
				if(jns_produk == "Limbah" || jns_produk == "JasaKD" || jns_produk == "JasaGesek" || jns_produk == "JasaMoulding"){
					
				} else{
					if(!kubikasi_realisasi){
						$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').addClass('error-tb-detail');
						has_error = has_error + 1;
					}else{
						if((kubikasi_realisasi <= 0) || (kubikasi != kubikasi_realisasi)){
							//$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').addClass('error-tb-detail');
							//has_error = has_error + 1;
							//2021-10-30 selisih diabaikan jika kurang dari 0.0005
							var selisih = kubikasi - kubikasi_realisasi;
							if (selisih >= 0.0005) {
								$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').addClass('error-tb-detail');
								has_error = has_error + 1;
							} else {
								$(this).find('input[name*="[kubikasi_realisasi]"]').val(kubikasi);
								$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').removeClass('error-tb-detail');
							}
						}else{
							$(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').removeClass('error-tb-detail');
						}
					}
				}
			}
			
			if( !$(this).find("input[name*='[op_ko_random_id]']").val() ){
				if(jns_produk == "Log"){

				} else {
					if(!qty_besar){
						$(this).find('input[name*="[qty_besar]"]').parents('td').addClass('error-tb-detail');
						has_error = has_error + 1;
					}else{
						if((qty_besar <= 0) || (qty_besar != qty_besar_realisasi)){
							$(this).find('input[name*="[qty_besar]"]').parents('td').addClass('error-tb-detail');
							has_error = has_error + 1;
						}else{
							$(this).find('input[name*="[qty_besar]"]').parents('td').removeClass('error-tb-detail');
						}
					}
					if(!qty_besar_realisasi){
						$(this).find('input[name*="[qty_besar_realisasi]"]').parents('td').addClass('error-tb-detail');
						has_error = has_error + 1;
					}else{
						if((qty_besar_realisasi <= 0) || (qty_besar != qty_besar_realisasi)){
							$(this).find('input[name*="[qty_besar_realisasi]"]').parents('td').addClass('error-tb-detail');
							has_error = has_error + 1;
						}else{
							$(this).find('input[name*="[qty_besar_realisasi]"]').parents('td').removeClass('error-tb-detail');
						}
					}
				}
			}
		});
	}
	console.log(has_error);
	if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
	setStatus();
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_kirim') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_rencanamuat') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'waktu_mulaimuat') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'waktu_selesaimuat') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	// $('#<?= yii\bootstrap\Html::getInputId($model, 'tarik_data') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if($model->status == \app\models\TSpmKo::REALISASI){ ?>
	$('#btn-add-item').remove();
    $('#btn-add-item-log').remove();
	<?php } ?>
	<?php if(isset($_GET['edit'])){ ?>
		$('#<?= \yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_kirim") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal_kirim') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal_rencanamuat') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "waktu_mulaimuat") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'waktu_mulaimuat') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "waktu_selesaimuat") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'waktu_selesaimuat') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>').prop("disabled", false);
		// $('#<?= \yii\bootstrap\Html::getInputId($model, "tarik_data") ?>').prop("disabled", false);
		// $('#<?= \yii\bootstrap\Html::getInputId($model, "tarik_log") ?>').prop("disabled", false);
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
	<?php } ?>
}

function getItemsById(id,realisasi=null,edit=null){
    var jns_produk = $("#<?= yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
	// var tarik_data = $("#<?= yii\helpers\Html::getInputId($model, "tarik_data") ?>").val();
	// console.log('getitemsby '+tarik_data);
    if(jns_produk == "Limbah"){
        $(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","");
        $(".place-satuan-log").css("display","none");
    }else if(jns_produk == "Log"){
		$(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","none");
		$(".place-satuan-log").css("display","");
    } else{
        $(".place-satuan-produk").css("display","");
        $(".place-satuan-limbah").css("display","none");
        $(".place-satuan-log").css("display","none");
    }
	if(jns_produk == "Log"){
		$(".place-diameter-log").css("display","");
		$(".place-diameter").css("display","none");
		// $('.spm-tarik-data').css("display","");
	} else {
		$(".place-diameter-log").css("display","none");
		$(".place-diameter").css("display","");
		// $('.spm-tarik-data').css("display","none");
	}
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/getItemsById']); ?>',
		type   : 'POST',
		data   : {id:id,realisasi:realisasi,edit:edit},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				formconfig();
				if(realisasi){
					if(jns_produk == "Log"){
						fillSpmLogRealisasi();
					} else {
						fillSpmRealisasi();
					}
					
				}
                reordertable("#table-detail");
			}
			
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}


function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/marketing/spm/daftarAfterSave']) ?>','modal-aftersave','95%');
}

function printSPM(id){
    window.open("<?= yii\helpers\Url::toRoute('/marketing/spm/printSPM') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');

}

function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/marketing/spm/index','spm_ko_id'=>'']); ?>'+id+'&edit=1');
}

function setStatus(){
    var jns_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
	var op_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>').val();
	if(op_ko_id){
		$.ajax({
			url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/checkApproval']); ?>',
			type   : 'POST',
			data   : {op_ko_id:op_ko_id},
			success: function (data) {
				if(data.status){
					var status = $('#<?= \yii\bootstrap\Html::getInputId($model, 'status') ?>').val();
					var spm_ko_id = '<?= isset($_GET['spm_ko_id'])?$_GET['spm_ko_id']:"" ?>';
					var color = "";
					if(status == "<?= \app\models\TSpmKo::REALISASI; ?>"){
						color = "#95EBA3";
						$("#place-waktuselesaimuat").slideDown();
                        if( jns_produk == "Limbah" || jns_produk == "JasaKD" || jns_produk == "JasaGesek" || jns_produk == "JasaMoulding" ){
                            $("#place-produklist").css('display','none');
                            $("#place-loglist").css('display','none');
                        } else if(jns_produk == "Log"){
							$("#place-produklist").css('display','none');
							$("#place-loglist").css('display','');
						} else{
                            $("#place-produklist").css('display','');
                            $("#place-loglist").css('display','none');
                        }
						if(jns_produk == "Log"){
							$("#place-tarikdata").css('display','');
						} else {
							$("#place-tarikdata").css('display','none');
						}
						getItemsById(spm_ko_id,1,'<?= isset($_GET['edit'])?$_GET['edit']:""; ?>');
						if( jns_produk == "Limbah" || jns_produk == "JasaKD" || jns_produk == "JasaGesek" || jns_produk == "JasaMoulding" ){
                        
						} else if(jns_produk == "Log"){
							<?php if(isset($_GET['edit']) ){ ?> //|| (isset($_GET['op_ko_id']))
								var id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'terima_logalam_id') ?>').val();
								if(id){
									setItemLogPelabuhan();
									$("#btn-add-item-log").css("display","none");
								}else{
									getCurrentLogList();
								}
							<?php } else { ?>
								getCurrentLogList();
							<?php } ?>
							// if(status != null){
								// getCurrentLogList();
							// } else {
								// setItemLogPelabuhan();
							// }
						} else{
                            getCurrentProdukList();
                        }
						// if( jns_produk == "Limbah" || jns_produk == "JasaKD" || jns_produk == "JasaGesek" || jns_produk == "JasaMoulding" ){
                            
						// } else if(jns_produk == "Log"){
						// 	getCurrentLogList();
						// } else{
                        //     getCurrentProdukList();
                        // }

					}else{
						color = "#FBE88C";
						$("#place-waktuselesaimuat").slideUp();
						$("#place-produklist").css('display','none');
						$("#table-detail-produklist tbody").html("");
                        $("#table-detail-loglist tbody").html("");
						getItemsById(spm_ko_id);
					}
					$('#<?= \yii\bootstrap\Html::getInputId($model, 'status') ?>').css('background-color',color);
				}else{
					cisAlert("Diperlukan Approval OP atas SPM ini!");
					$('#<?= \yii\bootstrap\Html::getInputId($model, 'status') ?>').val("");
				}

			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
	}
}

function getCurrentProdukList(){
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var notin = [];
	$('#table-detail-produklist > tbody > tr').each(function(){
		var nomor_produksi = $(this).find('select[name*="[nomor_produksi]"]');
		if( nomor_produksi.val() ){
			notin.push(nomor_produksi.val());
		}
	});
	if(notin){
		notin = JSON.stringify(notin);
	}
	var kode_spm = $("#<?= \yii\bootstrap\Html::getInputId($model, "kode") ?>").val();
	var status = "<?= (!empty($model->status))?$model->status:"" ?>";
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/getCurrentProdukList']); ?>',
        type   : 'POST',
        data   : {kode_spm:kode_spm,status:status},
        success: function (data) {
            if(data.item){
				$('#table-detail-produklist > tbody').html(data.item);
                $('#table-detail-produklist > tbody > tr').each(function(idx){
					$(this).find('select[name*="[nomor_produksi]"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik Kode Barang Jadi',
                        width: null,
						ajax: {
							url: '<?= \yii\helpers\Url::toRoute('/marketing/spm/findStockActive') ?>',
							dataType: 'json',
							delay: 250,
							data: function (params) {
								var query = {
								  term: params.term,
								  type: jns_produk,
								  notin: notin,
								}
								return query;
							},
							processResults: function (data) {
								return {
									results: data
								};
							},
							cache: true
						}
					});
					$(this).find('.select2-selection').css('font-size','1.2rem');
					$(this).find('.select2-selection').css('padding-left','5px');
					$(this).find(".tooltips").tooltip({ delay: 50 });
					$(this).find('select[name*="[nomor_produksi]"]').empty().append('<option value="'+data.model[idx].nomor_produksi+'">'+data.model[idx].nomor_produksi+'</option>').val(data.model[idx].nomor_produksi).trigger('change');
					reordertable('#table-detail-produklist');
				});
            }else{
				addItemProductList();
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function addItemProductList(){
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var notin = [];
	$('#table-detail-produklist > tbody > tr').each(function(){
		var nomor_produksi = $(this).find('select[name*="[nomor_produksi]"]');
		if( nomor_produksi.val() ){
			notin.push(nomor_produksi.val());
		}
	});
	if(notin){
		notin = JSON.stringify(notin);
	}
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/addProdukList']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail-produklist tbody').fadeIn(500,function(){
                    $(this).find('select[name*="[nomor_produksi]"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik Kode Barang Jadi',
                        width: null,
						ajax: {
							url: '<?= \yii\helpers\Url::toRoute('/marketing/spm/findStockActive') ?>',
							dataType: 'json',
							delay: 250,
							data: function (params) {
								var query = {
								  term: params.term,
								  type: jns_produk,
								  notin: notin
								}
								return query;
							},
							processResults: function (data) {
								return {
									results: data
								};
							},
							cache: true
						}
					});
					$(this).find('.select2-selection').css('font-size','1.2rem');
					$(this).find('.select2-selection').css('padding-left','5px');
					$(this).find(".tooltips").tooltip({ delay: 50 });
                    reordertable('#table-detail-produklist');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function setItemProductList(ele,nomor_produksi=null){
	if(!nomor_produksi){
		nomor_produksi = $(ele).val();
	}
	var op_ko_id = $("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").val()
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/setItemProductList']); ?>',
        type   : 'POST',
        data   : {nomor_produksi:nomor_produksi,op_ko_id:op_ko_id},
        success: function (data) {
            if(data.produksi){
                $(ele).parents('tr').find('input[name*="[produk_id]"]').val(data.produk.produk_id);
                $(ele).parents('tr').find('input[name*="[satuan_besar]"]').val("Palet");
				$(ele).parents('tr').find('input[name*="[nomor_produksi]"]').val(data.produksi.nomor_produksi);
				$(ele).parents('tr').find('input[name*="[tanggal_produksi]"]').val(data.produksi.tanggal_produksi);
				$(ele).parents('tr').find('input[name*="[produk_kode]"]').val(data.produk.produk_kode);
				$(ele).parents('tr').find('input[name*="[produk_nama]"]').val(data.produk.produk_nama);
				$(ele).parents('tr').find('input[name*="[qty_besar]"]').val(data.persediaan.qty_palet);
                $(ele).parents('tr').find('input[name*="[qty_kecil]"]').val(data.persediaan.qty_kecil);
				$(ele).parents('tr').find('input[name*="[satuan_kecil]"]').val(data.persediaan.satuan_kecil);
                $(ele).parents('tr').find('input[name*="[kubikasi]"]').val(data.persediaan.kubikasi);
                $(ele).parents('tr').find('input[name*="[gudang_id]"]').val(data.persediaan.gudang_id);
                $(ele).parents('tr').find('input[name*="[random]"]').val(data.random);
                $(ele).parents('tr').find('input[name*="[produk_p]"]').val(data.produk.produk_p);
                $(ele).parents('tr').find('input[name*="[produk_l]"]').val(data.produk.produk_l);
                $(ele).parents('tr').find('input[name*="[produk_t]"]').val(data.produk.produk_t);
                $(ele).parents('tr').find('input[name*="[produk_p_satuan]"]').val(data.produk.produk_p_satuan);
                $(ele).parents('tr').find('input[name*="[produk_l_satuan]"]').val(data.produk.produk_l_satuan);
                $(ele).parents('tr').find('input[name*="[produk_t_satuan]"]').val(data.produk.produk_t_satuan);
                $(ele).parents('tr').find('input[name*="[kubikasi_hasilhitung]"]').val(data.kubikasi_hasilhitung);
				setMeterKubik(ele);
				fillSpmRealisasi();
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function stockAvailable(ele){
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var notin = [];
	$('#table-detail-produklist > tbody > tr').each(function(){
		var nomor_produksi = $(this).find('select[name*="[nomor_produksi]"]');
		if( nomor_produksi.val() ){
			notin.push(nomor_produksi.val());
		}
	});
	if(notin){
		notin = JSON.stringify(notin);
	}
	var url = '<?= \yii\helpers\Url::toRoute(['/gudang/availablestockproduk/produkListOnModal','tr_seq'=>'']); ?>'+tr_seq+'&jns_produk='+jns_produk+'&notin='+notin;
	$(".modals-place-3-min").load(url, function() {
		$("#modal-produklist2 .modal-dialog").css('width','95%');
		$("#modal-produklist2").modal('show');
		$("#modal-produklist2").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pickProdukList(nomor_produksi,tr_seq){
	var jns_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
	var op_ko_id = $("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").val()
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/setItemProductList']); ?>',
        type   : 'POST',
        data   : {nomor_produksi:nomor_produksi,op_ko_id:op_ko_id},
        success: function (data) {
			if(data){
				var already = [];
				$('#table-detail-produklist > tbody > tr').each(function(){
					var nomor_produksi = $(this).find('select[name*="[nomor_produksi]"]');
					if( nomor_produksi.val() ){
						already.push(nomor_produksi.val());
					}
				});
				if( $.inArray(  data.produksi.nomor_produksi.toString(), already ) != -1 ){ // Jika ada yang sama
					cisAlert("Produk ini sudah dipilih di list");
					return false;
				}else{
					$("#modal-produklist2").find('button.fa-close').trigger('click');
					$("#table-detail-produklist > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[nomor_produksi]']").empty().append('<option value="'+data.produksi.nomor_produksi+'">'+data.produksi.nomor_produksi+'</option>').val(data.produksi.nomor_produksi).trigger('change');
				}
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function truncate(number, index = 2) {
  	// cutting the number
    return +number.toString().slice(0, (number.toString().indexOf(".")) + (index + 1));
}

function fillSpmRealisasi(){
	$("#table-detail > tbody > tr").each(function(){
		var op_ko_random_id_spm = $(this).find("input[name*='[op_ko_random_id]']").val();
		var produk_id_spm = $(this).find("input[name*='[produk_id]'], select[name*='[produk_id]']").val();
		var totalpl_palet = 0;
		var totalpl_qty_kecil = 0;
		var totalpl_satuan_kecil = "";
		var totalpl_kubikasi = 0;
		
		$("#table-detail-produklist > tbody > tr").each(function(){
			var produk_id_pl = $(this).find("input[name*='[produk_id]']").val();
			if(produk_id_spm == produk_id_pl){
				if(op_ko_random_id_spm){
					if($(this).find("input[name*='[random]']").val()){
						totalpl_palet = "-";
						var random = $.parseJSON( $(this).find("input[name*='[random]']").val() );
						$(random).each(function(i){
							var op_ko_random_id_pl = $.makeArray( $.makeArray( $(this) )[0] )[0].op_ko_random_id;
							var qty_kecil = $.makeArray( $.makeArray( $(this) )[0] )[0].qty_kecil;
							var kubikasi = $.makeArray( $.makeArray( $(this) )[0] )[0].kubikasi;
							if(op_ko_random_id_spm == op_ko_random_id_pl){
								totalpl_qty_kecil += qty_kecil;
								totalpl_kubikasi += kubikasi;
							}
						});
					}
				}else{
					totalpl_palet += unformatNumber( $(this).find("input[name*='[qty_besar]']").val() );
					totalpl_qty_kecil += unformatNumber( $(this).find("input[name*='[qty_kecil]']").val() );
					totalpl_satuan_kecil = $(this).find("input[name*='[satuan_kecil]']").val();
					//totalpl_kubikasi += unformatNumber( $(this).find("input[name*='[kubikasi_hasilhitung]']").val() );
                    totalpl_kubikasi += unformatNumber( $(this).find("input[name*='[kubikasi]']").val() );
				}
			}
		});
		
		//totalpl_kubikasi =  (Math.round( totalpl_kubikasi * 10000 ) / 10000 ).toString(); // membuat 4 digit belakang koma
		
		if(!$(this).find("input[name*='[op_ko_random_id]']").val()){
            $(this).find("input[name*='[qty_besar_realisasi]']").val( (totalpl_palet!="-")?formatNumberForUser(totalpl_palet):totalpl_palet );
            $(this).find("input[name*='[qty_kecil_realisasi]']").val( formatNumberForUser(totalpl_qty_kecil) );
			$(this).find("input[name*='[satuan_kecil_realisasi]']").val( totalpl_satuan_kecil );
            
            // selisih kubikasi dul

            //$(this).find("input[name*='[kubikasi_realisasi]']").val( totalpl_kubikasi );
            
            //$(this).find("input[name*='[kubikasi_realisasi]']").val( formatNumberForUser(totalpl_kubikasi) );
            
//            $(this).find("input[name*='[kubikasi_realisasi]']").val( formatNumberFixed4(totalpl_kubikasi) );  //ini adalah pembulatan 4 digit - cek pemblatan di custom.js
            
//            $(this).find("input[name*='[kubikasi_realisasi]']").val( formatNumberFixed4(formatNumberForUser(totalpl_kubikasi)) );
            
            // sawntimber, plywood
//            $(this).find("input[name*='[kubikasi_realisasi]']").val( formatNumberForUser4Digit(formatNumberForUser(totalpl_kubikasi)) ); //option
            
            // veneer
            //$(this).find("input[name*='[kubikasi_realisasi]']").val( truncate(totalpl_kubikasi,4));

            // plywood
            $(this).find("input[name*='[kubikasi_realisasi]']").val( Math.round(totalpl_kubikasi * 10000) / 10000);

            if( $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val() == "Limbah" ){
                $(this).find("input[name*='[qty_besar_realisasi]']").val("1");
                $(this).find("input[name*='[satuan_kecil_realisasi]']").val( $(this).find("input[name*='[satuan_kecil]']").val() );
            }
		}
	});
}

function cancelItemProdukList(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail-produklist');
        fillSpmRealisasi();
    });
}

function setQty(ele){
	var produk_id = $(ele).parents('tr').find('input[name*="[produk_id]"],select[name*="[produk_id]"]').val();
	var qty_besar = unformatNumber( $(ele).parents('tr').find('input[name*="[qty_besar]"]').val() );
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/setQty']); ?>',
        type   : 'POST',
        data   : {produk_id:produk_id},
        success: function (data) {
			if(data){
				$(ele).parents('tr').find('input[name*="[qty_kecil]"]').val( formatNumberForUser(qty_besar * data.qty_kecil) );
				$(ele).parents('tr').find('input[name*="[kubikasi]"]').val( formatNumberForUser(qty_besar * data.kubikasi) );
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function hapusProduk(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail-produklist');
        fillSpmRealisasi();
    });
}

function addProduk(){
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var notin = [];
	$('#table-detail > tbody > tr').each(function(){
		var produk_id = $(this).find('select[name*="[produk_id]"],input[name*="[produk_id]"]');
		if( produk_id.val() ){
			notin.push(produk_id.val());
		}
	});
	if(notin){
		notin = JSON.stringify(notin);
	}
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/addProduk']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
						$(this).find('select[name*="[produk_id]"]').select2({
							allowClear: !0,
							placeholder: 'Ketik kode produk',
							width: '100%',
							ajax: {
								url: '<?= \yii\helpers\Url::toRoute('/marketing/orderpenjualan/findProdukActive') ?>',
								dataType: 'json',
								delay: 250,
								data: function (params) {
									var query = {
									  term: params.term,
									  type: jns_produk,
									  notin: notin
									}
									return query;
								},
								processResults: function (data) {
									return {
										results: data
									};
								},
								cache: true
							}
						});
						$(this).find('.select2-selection').css('font-size','1.2rem');
						$(this).find('.select2-selection').css('padding-left','5px');
						$(this).find(".tooltips").tooltip({ delay: 50 });
						reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function setItem(ele,produk_id=null){
	var cust_id = $('#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>').val();
	if(!produk_id){
		produk_id = $(ele).val();
	}
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/orderpenjualan/setItem']); ?>',
        type   : 'POST',
        data   : {produk_id:produk_id},
        success: function (data) {
			$(ele).parents('tr').find('input[name*="[produk_nama]"]').val('');
			$(ele).parents('tr').find('input[name*="[qty_besar]"]').val("");
			$(ele).parents('tr').find('input[name*="[satuan_besar]"]').val("");
			$(ele).parents('tr').find('input[name*="[qty_kecil]"]').val('');
			$(ele).parents('tr').find('input[name*="[satuan_kecil]"]').val('');
			$(ele).parents('tr').find('input[name*="[satuan_kecil_realisasi]"]').val('');
			$(ele).parents('tr').find('input[name*="[kubikasi]"]').val('');
            if(data.produk){
                $(ele).parents('tr').find('input[name*="[produk_nama]"]').val(data.produk.produk_nama);
                $(ele).parents('tr').find('input[name*="[qty_besar]"]').val("1");
                $(ele).parents('tr').find('input[name*="[satuan_besar]"]').val("Palet");
                $(ele).parents('tr').find('input[name*="[qty_kecil]"]').val(data.produk.produk_qty_satuan_kecil);
                $(ele).parents('tr').find('input[name*="[satuan_kecil]"]').val(data.produk.produk_satuan_kecil);
				$(ele).parents('tr').find('input[name*="[satuan_kecil_realisasi]"]').val(data.produk.produk_satuan_kecil);
                $(ele).parents('tr').find('input[name*="[kubikasi]"]').val(data.produk.kapasitas_kubikasi);
            }
			fillSpmRealisasi();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function masterProduk(ele){
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'jenis_produk') ?>').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/orderpenjualan/produkInStock','disableAction'=>'']); ?>1&tr_seq='+tr_seq+"&jenis_produk="+jenis_produk;
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master-produk .modal-dialog").css('width','75%');
		$("#modal-master-produk").modal('show');
		$("#modal-master-produk").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pickProduk(produk_id,tr_seq){
	var jns_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/orderpenjualan/pickProduk']); ?>',
        type   : 'POST',
        data   : {produk_id:produk_id},
        success: function (data) {
			if(data){
				if(jns_produk == data.produk_group){
					var already = [];
					$('#table-detail > tbody > tr').each(function(){
						var produk_id = $(this).find('select[name*="[produk_id]"],input[name*="[produk_id]"]');
						if( produk_id.val() ){
							already.push(produk_id.val());
						}
					});
					if( $.inArray(  data.produk_id.toString(), already ) != -1 ){ // Jika ada yang sama
						cisAlert("Produk ini sudah dipilih di list");
						return false;
					}else{
						$("#modal-master-produk").find('button.fa-close').trigger('click');
						$("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[produk_id]'],input[name*='[produk_id]']").empty().append('<option value="'+data.produk_id+'">'+data.produk_kode+'</option>').val(data.produk_id).trigger('change');
					}
				}else{
					cisAlert("Jenis produk ini tidak sama dengan jenis produk yang terpilih");
					return false;
				}
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function listRandom(ele){
	var op_ko_id = $("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").val();
	var produk_id = $(ele).parents('tr').find('input[name*="[produk_id]"]').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/spm/listRandom']); ?>?produk_id='+produk_id+'&op_ko_id='+op_ko_id;
	$(".modals-place-2-min").load(url, function() {
		$("#modal-random .modal-dialog").css('width','50%');
		$("#modal-random").modal('show');
		$("#modal-random").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function setMeterKubik(ele){
    var p = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_p]"]').val() );
    var l = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_l]"]').val() );
    var t = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_t]"]').val() );
    var sat_p = $(ele).parents('tr').find('input[name*="[produk_p_satuan]"]').val();
    var sat_l = $(ele).parents('tr').find('input[name*="[produk_l_satuan]"]').val();
    var sat_t = $(ele).parents('tr').find('input[name*="[produk_t_satuan]"]').val();
    var qty = unformatNumber( $(ele).parents('tr').find('input[name*="[qty_kecil]"]').val() );
    var sat_p_m = 0;
    var sat_l_m = 0;
    var sat_t_m = 0;
    var result = 0;
    if(sat_p == 'mm'){
        sat_p_m = p * 0.001;
    }else if(sat_p == 'cm'){
        sat_p_m = p * 0.01;
    }else if(sat_p == 'inch'){
        sat_p_m = p * 0.0254;
    }else if(sat_p == 'm'){
        sat_p_m = p;
    }else if(sat_p == 'feet'){
        sat_p_m = p * 0.3048;
    }
    if(sat_l == 'mm'){
        sat_l_m = l * 0.001;
    }else if(sat_l == 'cm'){
        sat_l_m = l * 0.01;
    }else if(sat_l == 'inch'){
        sat_l_m = l * 0.0254;
    }else if(sat_l == 'm'){
        sat_l_m = l;
    }else if(sat_l == 'feet'){
        sat_l_m = l * 0.3048;
    }
    if(sat_t == 'mm'){
        sat_t_m = t * 0.001;
    }else if(sat_t == 'cm'){
        sat_t_m = t * 0.01;
    }else if(sat_t == 'inch'){
        sat_t_m = t * 0.0254;
    }else if(sat_t == 'm'){
        sat_t_m = t;
    }else if(sat_t == 'feet'){
        sat_t_m = t * 0.3048;
    }
    result = sat_p_m * sat_l_m * sat_t_m * qty;
    result = (Math.round( result * 10000 ) / 10000 ).toString(); // karena mengambil nilai asli
	if((result==0)&&( unformatNumber($(ele).parents('tr').find('input[name*="[kubikasi]"]').val())>0 )){
		result = unformatNumber($(ele).parents('tr').find('input[name*="[kubikasi]"]').val());
	}
    $(ele).parents('tr').find('input[name*="[kubikasi_hasilhitung]"]').val( formatNumberForUser(result) );
}

function editKecil(id){
    var url = '<?= \yii\helpers\Url::toRoute(['/marketing/spm/editKecil','id'=>'']); ?>'+id;
	$(".modals-place-2").load(url, function() {
        $("#modal-edit .modal-dialog").css('width','50%');
		$("#modal-edit").modal('show');
		$("#modal-edit").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}


function listPaletTerima(ele){
	var spm_ko_id = "<?= isset($_GET['spm_ko_id'])?$_GET['spm_ko_id']:'' ?>";
	var op_ko_id = $("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").val();
	var produk_id = $(ele).parents('tr').find('input[name*="[produk_id]"]').val();
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
    var nomor_palet_exist = $(ele).parents('tr').find('input[name*="[nomor_palet_exist]"]').val();
    var pilihmode = "0";
    <?php if( isset($_GET['spm_ko_id']) && !isset($_GET['edit']) ){ ?>
    var pilihmode = "1";
    <?php } ?>
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/spm/ListPaletTerima']); ?>?produk_id='+produk_id+'&op_ko_id='+op_ko_id+'&spm_ko_id='+spm_ko_id+'&tr_seq='+tr_seq+'&nomor_palet_exist='+nomor_palet_exist+'&lihat='+pilihmode;
	$(".modals-place-2-min").load(url, function() {
		$("#modal-palet-terima .modal-dialog").css('width','50%');
		$("#modal-palet-terima").modal('show');
		$("#modal-palet-terima").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
        
        $('#modal-palet-terima').on('hidden.bs.modal', function () {
			var row = $(this).find("input[name*='reff_ele']").val();
			var nomor_palet_all =  $(this).find("input[name*='nomor_palet_all']").val();
			var palet = (nomor_palet_all)?nomor_palet_all.split(',').length:"0";
			var pcs =  $(this).find("input[name*='tot_qty']").val();
			var kubikasi =  $(this).find("input[name*='tot_kubikasi']").val();
			$("#TSpmKoDetail_"+(row-1)+"_nomor_palet_exist").val(nomor_palet_all);
			$("#TSpmKoDetail_"+(row-1)+"_qty_besar").val(palet);
			$("#TSpmKoDetail_"+(row-1)+"_qty_kecil").val(pcs);
			$("#TSpmKoDetail_"+(row-1)+"_kubikasi").val(kubikasi);
		});
	});
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
function getCurrentLogList(){
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var notin = [];
	$('#table-detail-loglist > tbody > tr').each(function(){
		var no_barcode = $(this).find('select[name*="[no_barcode]"]');
		if( no_barcode.val() ){
			notin.push(no_barcode.val());
		}
	});
	if(notin){
		notin = JSON.stringify(notin);
	}
	var kode_spm = $("#<?= \yii\bootstrap\Html::getInputId($model, "kode") ?>").val();
	var status = "<?= (!empty($model->status))?$model->status:"" ?>";

	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/getCurrentLogList']); ?>',
        type   : 'POST',
        data   : {kode_spm:kode_spm,status:status},
        success: function (data) {
            if(data.item){
				$('#table-detail-loglist > tbody').html(data.item);
                $('#table-detail-loglist > tbody > tr').each(function(idx){
					$(this).find('select[name*="[no_barcode]"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik Kode Barcode',
                        width: null,
						ajax: {
							url: '<?= \yii\helpers\Url::toRoute('/marketing/spm/findStockLogActive') ?>',
							dataType: 'json',
							delay: 250,
							data: function (params) {
								var query = {
								  term: params.term,
								  type: jns_produk,
								  notin: notin,
								}
								return query;
							},
							processResults: function (data) {
								return {
									results: data
								};
							},
							cache: true
						}
					});
					$(this).find('.select2-selection').css('font-size','1.2rem');
					$(this).find('.select2-selection').css('padding-left','5px');
					$(this).find(".tooltips").tooltip({ delay: 50 });
					$(this).find('select[name*="[no_barcode]"]').empty().append('<option value="'+data.model[idx].no_barcode+'">'+data.model[idx].no_barcode+'</option>').val(data.model[idx].no_barcode).trigger('change');
					reordertable('#table-detail-loglist');
					if(status){
						fillSpmLogRealisasi();
					}
				});
            } else{
				addItemLogList();
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function addItemLogList(){
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var notin = [];
	$('#table-detail-loglist > tbody > tr').each(function(){
		var no_barcode = $(this).find('select[name*="[no_barcode]"]');
		if( no_barcode.val() ){
			notin.push(no_barcode.val());
		}
	});
	if(notin){
		notin = JSON.stringify(notin);
	}

	var jml_data = $('#table-detail tbody tr').length;
	var data_log_nama = [];
	for(var i = 0; i < jml_data; i++){
		var log_nama = document.querySelector('[name*="['+ i +'][log_nama]"]').value;
		data_log_nama.push(log_nama);
	}

    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/addLogList']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail-loglist tbody').fadeIn(500,function(){
                    $(this).find('select[name*="[no_barcode]"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik Kode Barcode Log',
                        width: null,
						ajax: {
							url: '<?= \yii\helpers\Url::toRoute('/marketing/spm/findStockLogActive') ?>',
							dataType: 'json',
							delay: 250,
							data: function (params) {
								var query = {
								  term: params.term,
								  type: jns_produk,
								  notin: notin,
								  data_log_nama: data_log_nama
								}
								return query;
							},
							processResults: function (data) {
								return {
									results: data
								};
							},
							cache: true
						}
					});
					$(this).find('.select2-selection').css('font-size','1.2rem');
					$(this).find('.select2-selection').css('padding-left','5px');
					$(this).find(".tooltips").tooltip({ delay: 50 });
                    reordertable('#table-detail-loglist');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function pickLogList(no_barcode,tr_seq){
	var jns_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
	var op_ko_id = $("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").val();
	
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/setItemLogList']); ?>',
        type   : 'POST',
        data   : {no_barcode:no_barcode,op_ko_id:op_ko_id},
        success: function (data) {
			if(data){
				var already = [];
				$('#table-detail-loglist > tbody > tr').each(function(){
					var no_barcode = $(this).find('select[name*="[no_barcode]"]');
					if( no_barcode.val() ){
						already.push(no_barcode.val());
					}
				});
				if( $.inArray(  data.log.no_barcode.toString(), already ) != -1 ){ // Jika ada yang sama
					cisAlert("Produk ini sudah dipilih di list");
					return false;
				}else{
					$("#modal-loglist2").find('button.fa-close').trigger('click');
					$("#table-detail-loglist > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[no_barcode]']").empty().append('<option value="'+data.log.no_barcode+'">'+data.log.no_barcode+'</option>').val(data.log.no_barcode).trigger('change');
				}
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setItemLogList(ele,no_barcode=null){
	if(!no_barcode){
		no_barcode = $(ele).val();
	}
	var op_ko_id = $("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").val();
	
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/setItemLogList']); ?>',
        type   : 'POST',
        data   : {no_barcode:no_barcode,op_ko_id:op_ko_id},
        success: function (data) {
            if(data.log){
				$(ele).parents('tr').find('input[name*="[kayu_id]"]').val(data.produk.kayu_id);
				$(ele).parents('tr').find('input[name*="[kayu_nama]"]').hide();
				$(ele).parents('tr').find('#kayu_nama_display').text(data.kayu.group_kayu + '\n' + data.kayu.kayu_nama).show();
				$(ele).parents('tr').find('input[name*="[produk_id]"]').val(data.produk.log_id);
				$(ele).parents('tr').find('input[name*="[no_produksi]"]').val(data.persediaan.no_produksi);
				$(ele).parents('tr').find('input[name*="[kode_potong]"]').val(data.persediaan.pot);
				// // $(ele).parents('tr').find('input[name*="[fisik_diameter]"]').val(data.persediaan.fisik_diameter);
				$(ele).parents('tr').find('input[name*="[fisik_pcs]"]').val(data.persediaan.fisik_pcs);
				$(ele).parents('tr').find('input[name*="[no_barcode]"]').val(data.persediaan.no_barcode);
				$(ele).parents('tr').find('input[name*="[no_lap]"]').val(data.persediaan.no_lap);
				$(ele).parents('tr').find('input[name*="[no_grade]"]').val(data.persediaan.no_grade);
				$(ele).parents('tr').find('input[name*="[no_btg]"]').val(data.persediaan.no_btg);
				$(ele).parents('tr').find('input[name*="[fisik_volume]"]').val(data.persediaan.fisik_volume);
				$(ele).parents('tr').find('input[name*="[fisik_panjang]"]').val(data.persediaan.fisik_panjang);
				$(ele).parents('tr').find('.persediaan-diameter_ujung1').val(data.persediaan.diameter_ujung1);
				$(ele).parents('tr').find('.persediaan-diameter_ujung2').val(data.persediaan.diameter_ujung2);
				$(ele).parents('tr').find('.persediaan-diameter_pangkal1').val(data.persediaan.diameter_pangkal1);
				$(ele).parents('tr').find('.persediaan-diameter_pangkal2').val(data.persediaan.diameter_pangkal2);
				$(ele).parents('tr').find('.persediaan-diameter_rata').val(data.persediaan.diameter_rata);
				$(ele).parents('tr').find('.persediaan-cacat_panjang').val(data.persediaan.cacat_panjang);
				$(ele).parents('tr').find('.persediaan-cacat_gb').val(data.persediaan.cacat_gb);
				$(ele).parents('tr').find('.persediaan-cacat_gr').val(data.persediaan.cacat_gr);
					// $(ele).parents('tr').find('.volume').val(data.spmlog.volume);
				// $(ele).parents('tr').find('input[name*="[diameter_ujung1]"]').val(data.persediaan.diameter_ujung1);
				// $(ele).parents('tr').find('input[name*="[diameter_ujung2]"]').val(data.persediaan.diameter_ujung2);
				// $(ele).parents('tr').find('input[name*="[diameter_pangkal1]"]').val(data.persediaan.diameter_pangkal1);
				// $(ele).parents('tr').find('input[name*="[diameter_pangkal2]"]').val(data.persediaan.diameter_pangkal2);
				// $(ele).parents('tr').find('input[name*="[cacat_panjang]"]').val(data.persediaan.cacat_panjang);
				// $(ele).parents('tr').find('input[name*="[cacat_gb]"]').val(data.persediaan.cacat_gb);
				// $(ele).parents('tr').find('input[name*="[cacat_gr]"]').val(data.persediaan.cacat_gr);
				if(data.spmlog){
					$(ele).parents('tr').find("input[name*='[panjang]']").val(data.spmlog.panjang);
					$(ele).parents('tr').find('.diameter_ujung1').val(data.spmlog.diameter_ujung1);
					$(ele).parents('tr').find('.diameter_ujung2').val(data.spmlog.diameter_ujung2);
					$(ele).parents('tr').find('.diameter_pangkal1').val(data.spmlog.diameter_pangkal1);
					$(ele).parents('tr').find('.diameter_pangkal2').val(data.spmlog.diameter_pangkal2);
					$(ele).parents('tr').find('.diameter_rata').val(data.spmlog.diameter_rata);
					$(ele).parents('tr').find('.cacat_panjang').val(data.spmlog.cacat_panjang);
					$(ele).parents('tr').find('.cacat_gb').val(data.spmlog.cacat_gb);
					$(ele).parents('tr').find('.cacat_gr').val(data.spmlog.cacat_gr);
				} else {
					$(ele).parents('tr').find("input[name*='[panjang]']").val( data.persediaan.fisik_panjang );
					$(ele).parents('tr').find('.diameter_ujung1').val(data.persediaan.diameter_ujung1);
					$(ele).parents('tr').find('.diameter_ujung2').val(data.persediaan.diameter_ujung2);
					$(ele).parents('tr').find('.diameter_pangkal1').val(data.persediaan.diameter_pangkal1);
					$(ele).parents('tr').find('.diameter_pangkal2').val(data.persediaan.diameter_pangkal2);
					$(ele).parents('tr').find('.diameter_rata').val(data.persediaan.diameter_rata);
					$(ele).parents('tr').find('.cacat_panjang').val(data.persediaan.cacat_panjang);
					$(ele).parents('tr').find('.cacat_gb').val(data.persediaan.cacat_gb);
					$(ele).parents('tr').find('.cacat_gr').val(data.persediaan.cacat_gr);
				}
				hitungRata(ele);
				fillSpmLogRealisasi();
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function stockLogAvailable(ele){
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();

	var jml_data = $('#table-detail tbody tr').length;
	var data_log_nama = [];
	for(var i = 0; i < jml_data; i++){
		var log_nama = document.querySelector('[name*="['+ i +'][log_nama]"]').value;
		data_log_nama.push(log_nama);
	}
	// console.log(data_log_nama);
	var notin = [];
	$('#table-detail-loglist > tbody > tr').each(function(){
		var no_barcode = $(this).find('select[name*="[no_barcode]"]');
		if( no_barcode.val() ){
			notin.push(no_barcode.val());
		}
	});
	if(notin){
		notin = JSON.stringify(notin);
	}
	var url_log = '<?= \yii\helpers\Url::toRoute(['/marketing/spm/logListOnModal','tr_seq'=>'']); ?>'+tr_seq+'&jns_produk='+jns_produk+'&data_log_nama='+data_log_nama;
	// console.log(data_log_nama);
	$(".modals-place-3-min").load(url_log, function() {
		$("#modal-loglist2 .modal-dialog").css('width','95%');
		$("#modal-loglist2").modal('show');
		$("#modal-loglist2").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function fillSpmLogRealisasi(){ 
	$("#table-detail > tbody > tr").each(function(){
		// var tarik_log = $("#<?= yii\bootstrap\Html::getInputId($model, "tarik_log") ?>").val();
		var produk_id = $(this).find("input[name*='[produk_id]'], select[name*='[produk_id]']").val();
		var terima_logalam_id = $("#<?= yii\bootstrap\Html::getInputId($model, "terima_logalam_id") ?>").val();
		var totalpl_qty_kecil = 0;
		var totalpl_kubikasi_realisasi = 0;
		
		var range_awal = unformatNumber($(this).find("input[name*='[range_awal]'], select[name*='[range_awal]']").val());
		var range_akhir = unformatNumber($(this).find("input[name*='[range_akhir]'], select[name*='[range_akhir]']").val());

		// console.log('fill terima_log : ' + terima_logalam_id);
		if(terima_logalam_id){
			var i = 0;
			$("#table-detail-loglist > tbody > tr").each(function(){
				var log_id = $(this).find(".produk_id_"+i+"").val();
				var d_rata = unformatNumber($(this).find("input[name*='[diameter_rata]']").val());
				if(log_id == produk_id){
					if(d_rata >= range_awal && d_rata <= range_akhir){
						// totalpl_kubikasi += unformatNumber( $(this).find("input[name*='[fisik_volume]']").val() );
						totalpl_qty_kecil += unformatNumber( $(this).find(".fisik_pcs_"+i+"").val() );
						totalpl_kubikasi_realisasi += unformatNumber($(this).find(".volume_"+i+"").val());
					}
				}
				i++;
			});
		} else {
			$("#table-detail-loglist > tbody > tr").each(function(){
				var detailspm =  $(this).find("input[name*='[fisik_pcs]']").val();
				var log_id = $(this).find("input[name*='[produk_id]'], select[name*='[produk_id]']").val();
				var d_rata = unformatNumber($(this).find("input[name*='[diameter_rata]']").val());
				if(log_id == produk_id){
					if(d_rata >= range_awal && d_rata <= range_akhir){
						// totalpl_kubikasi += unformatNumber( $(this).find("input[name*='[fisik_volume]']").val() );
						totalpl_qty_kecil += unformatNumber( $(this).find("input[name*='[fisik_pcs]']").val() );
						totalpl_kubikasi_realisasi += unformatNumber($(this).find("input[name*='[volume]']").val());
					}
				}
			});
		}		
		// console.log('fill terima_log : ' + range_awal);

        $(this).find("input[name*='[qty_kecil_realisasi]']").val( formatNumberForUser(totalpl_qty_kecil) );
		$(this).find("input[name*='[qty_besar_realisasi]']").val( totalpl_kubikasi_realisasi.toFixed(2) );
		$(this).find("input[name*='[kubikasi_realisasi]']").val( totalpl_kubikasi_realisasi.toFixed(2) );
            
        if( $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val() == "Log"  ){
            $(this).find("input[name*='[satuan_kecil_realisasi]']").val( $(this).find("input[name*='[satuan_kecil]']").val() );
        }
	});
}

function cancelItemLogList(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail-loglist');
		hitungRata(ele);
		// hitungVolume(ele);
        fillSpmLogRealisasi();
    });
}

function hitungRata(ele){
	var ujung1 = unformatNumber( $(ele).parents('tr').find('.diameter_ujung1').val() );
	var ujung2 = unformatNumber( $(ele).parents('tr').find('.diameter_ujung2').val() );
	var pangkal1 = unformatNumber( $(ele).parents('tr').find('.diameter_pangkal1').val() );
	var pangkal2 = unformatNumber( $(ele).parents('tr').find('.diameter_pangkal2').val() );

	var ratarata = Math.round((ujung1+ujung2+pangkal1+pangkal2)/4);
	$(ele).parents('tr').find('.diameter_rata').val( ratarata );
	hitungVolume(ele);
}

function hitungVolume(ele){
	var panjang = $(ele).parents('tr').find('.panjang').val();
	var ratarata = $(ele).parents('tr').find('.diameter_rata').val();
	var cacat_panjang = $(ele).parents('tr').find('.cacat_panjang').val();
	var cacat_gb = $(ele).parents('tr').find('.cacat_gb').val();
	var cacat_gr = $(ele).parents('tr').find('.cacat_gr').val();

	panjang == '' ? panjang = 0 : panjang = parseFloat(panjang);
	ratarata == '' ? ratarata = 0 : ratarata = parseFloat(ratarata);
    cacat_panjang == '' ? cacat_panjang = 0 : cacat_panjang = parseFloat(cacat_panjang);
    cacat_gb == '' ? cacat_gb = 0 : cacat_gb = parseFloat(cacat_gb);
    cacat_gr == '' ? cacat_gr = 0 : cacat_gr = parseFloat(cacat_gr);
	
	var pGrowong = (0.7854 * cacat_gr * cacat_gr * (panjang - (cacat_panjang / 100)) / 10000).toFixed(2);
    pGrowong == '' ? pGrowong = 0 : pGrowong = pGrowong;
    var zzz = (0.7854 * (panjang - (cacat_panjang / 100)) * ((ratarata - cacat_gb) * (ratarata - (cacat_gb)) * 1) / 10000) - (pGrowong);
    // var Vol = ((zzz * 100) / 100).toFixed(2);
    var Vol = zzz.toFixed(2);
    $(ele).parents('tr').find('.volume').val(Vol);
	fillSpmLogRealisasi();
}

// function setTarikData(){
// 	var jns_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
// 	var tarik_data = $("#<?= yii\bootstrap\Html::getInputId($model, "tarik_data") ?>").val();
// 	if(tarik_data == 'Gudang'){
// 		$('#table-detail-loglist > tbody').empty();
// 		$("#spm-tarik-data").css('display','none');
// 		$("#btn-add-item-log").css("display","");
// 		getCurrentLogList();
// 	} else if(tarik_data == 'Pelabuhan'){
// 		$("#spm-tarik-data").css('display','');
// 		$("#btn-add-item-log").css("display","none");
// 		$('#table-detail-loglist > tbody').empty();
// 		// console.log('pelabuhan');
// 	} else {
// 		$("#spm-tarik-data").css('display','none');
// 	}
// 	console.log('settarik '+tarik_data);
// }

function setItemLogPelabuhan(){
    var cust_id = $("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val();
    var spm_kode = $("#<?= yii\bootstrap\Html::getInputId($model, "kode") ?>").val();
	var terima_logalam_id = $("#<?= yii\bootstrap\Html::getInputId($model, "terima_logalam_id") ?>").val();
    
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/setItemLogPelabuhan']); ?>',
        type   : 'POST',
        data   : {cust_id:cust_id, spm_kode:spm_kode, terima_logalam_id: terima_logalam_id},
        success: function (data) {
            if(data){
                $('#table-detail-loglist > tbody').empty();

                $.each(data, function(index, data) {
					var newRow = `
						<tr>
							<td style="vertical-align: middle; text-align: center;">
								<span class="no_urut">`+data.no_urut+`</span>
								<input type="hidden" class="form-control fisik_pcs_`+index+`" style="width:100%; font-size:1.2rem; padding:5px;" value="1" disabled>
								<input type="hidden" class="form-control produk_id_`+index+`" style="width:100%; font-size:1.2rem; padding:5px;" value="`+data.produk_id+`" disabled>
							</td>
							<td class="td-kecil">
								<input type="text" class="form-control no_barcode td-kecil" name="TSpmLog[`+index+`][no_barcode] style="width:100%; font-size:1.2rem; padding:5px;" value="`+data.no_barcode+`" disabled>
								<input type="hidden" class="form-control kode_potong" name="TSpmLog[`+index+`][kode_potong] style="width:100%; font-size:1.2rem; padding:5px;" value="`+data.kode_potong+`" disabled>
								<input type="hidden" class="form-control kayu_id" name="TSpmLog[`+index+`][kayu_id] style="width:100%; font-size:1.2rem; padding:5px;" value="`+data.kayu_id+`" disabled>
								<input type="hidden" class="form-control no_produksi" name="TSpmLog[`+index+`][no_produksi] style="width:100%; font-size:1.2rem; padding:5px;" value="`+data.no_produksi+`" disabled>
								<input type="hidden" class="form-control no_barcode" name="TLogKeluar[`+index+`][no_barcode] style="width:100%; font-size:1.2rem; padding:5px;" value="`+data.no_barcode+`" disabled>
							</td>
							<td class="td-kecil"><div id="kayu_nama_display" class="form-control td-kecil" style="background-color: #eef1f5; height: auto;">`+data.kayu_nama+`</div></td>
							<td class="td-kecil"><input type="text" class="form-control no_lap td-kecil" name="TSpmLog[`+index+`][no_lap] style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.no_lap+`" disabled></td>
							<td class="td-kecil"><input type="text" class="form-control no_grade td-kecil" name="TSpmLog[`+index+`][no_grade] style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.no_grade+`" disabled></td>
							<td class="td-kecil"><input type="text" class="form-control float no_btg td-kecil" name="TSpmLog[`+index+`][no_btg] style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.no_btg+`" disabled></td>
							<td class="td-kecil" style="background-color: #FFE495;"><input type="text" class="form-control float panjang td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.panjang+`" disabled></td>
							<td class="td-kecil" style="background-color: #FFE495;"><input type="text" class="form-control float diameter_ujung1 td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.diameter_ujung1+`" disabled></td>
							<td class="td-kecil" style="background-color: #FFE495;"><input type="text" class="form-control float diameter_ujung2 td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.diameter_ujung2+`" disabled></td>
							<td class="td-kecil" style="background-color: #FFE495;"><input type="text" class="form-control float diameter_pangkal1 td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.diameter_pangkal1+`" disabled></td>
							<td class="td-kecil" style="background-color: #FFE495;"><input type="text" class="form-control float diameter_pangkal2 td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.diameter_pangkal2+`" disabled></td>
							<td class="td-kecil" style="background-color: #FFE495;"><input type="text" class="form-control float cacat_panjang td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.cacat_panjang+`" disabled></td>
							<td class="td-kecil" style="background-color: #FFE495;"><input type="text" class="form-control float cacat_gb td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.cacat_gb+`" disabled></td>
							<td class="td-kecil" style="background-color: #FFE495;"><input type="text" class="form-control float cacat_gr td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.cacat_gr+`" disabled></td>
							<td class="td-kecil" style="background-color: #FFE495;"><input type="text" class="form-control float fisik_volume td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: right;" value="`+data.volume+`" disabled></td>
							<td class="td-kecil" style="background-color: #B6D25D;"><input type="text" name="TSpmLog[`+index+`][panjang]" class="form-control float panjang td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.panjang+`" disabled></td>
							<td class="td-kecil" style="background-color: #B6D25D;"><input type="text" name="TSpmLog[`+index+`][diameter_ujung1]" class="form-control float diameter_ujung1 td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.diameter_ujung1+`" disabled></td>
							<td class="td-kecil" style="background-color: #B6D25D;"><input type="text" name="TSpmLog[`+index+`][diameter_ujung2]" class="form-control float diameter_ujung2 td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.diameter_ujung2+`" disabled></td>
							<td class="td-kecil" style="background-color: #B6D25D;"><input type="text" name="TSpmLog[`+index+`][diameter_pangkal1]" class="form-control float diameter_pangkal1 td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.diameter_pangkal1+`" disabled></td>
							<td class="td-kecil" style="background-color: #B6D25D;"><input type="text" name="TSpmLog[`+index+`][diameter_pangkal2]" class="form-control float diameter_pangkal2 td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.diameter_pangkal2+`" disabled></td>
							<td class="td-kecil" style="background-color: #B6D25D;"><input type="text" name="TSpmLog[`+index+`][diameter_rata]" class="form-control float diameter_rata td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.diameter_rata+`" disabled></td>
							<td class="td-kecil" style="background-color: #B6D25D;"><input type="text" name="TSpmLog[`+index+`][cacat_panjang]" class="form-control float cacat_panjang td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.cacat_panjang+`" disabled></td>
							<td class="td-kecil" style="background-color: #B6D25D;"><input type="text" name="TSpmLog[`+index+`][cacat_gb]" class="form-control float cacat_gb td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.cacat_gb+`" disabled></td>
							<td class="td-kecil" style="background-color: #B6D25D;"><input type="text" name="TSpmLog[`+index+`][cacat_gr]" class="form-control float cacat_gr td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: center;" value="`+data.cacat_gr+`" disabled></td>
							<td class="td-kecil" style="background-color: #B6D25D;"><input type="text" name="TSpmLog[`+index+`][volume]" class="form-control float volume_`+index+` td-kecil" style="width:100%; font-size:1.2rem; padding:5px; text-align: right;" value="`+data.volume+`" disabled></td>
							<td class="td-kecil" text-align: center;"><center>-</center></td>
						</tr>
					`;
					// console.log(index);
                    $('#table-detail-loglist > tbody').append(newRow);
                });
				fillSpmLogRealisasi();
            }
        },
        error: function (jqXHR) {getdefaultajaxerrorresponse(jqXHR);},
    });
}

function validatingKubikasi(callback){
	var produks = [];
	var edit = '<?= isset($_GET['edit'])?$_GET['edit']:''; ?>';
	var spm_id = '<?= isset($_GET['spm_ko_id'])?$_GET['spm_ko_id']:''; ?>';

	$('#table-detail tbody > tr').each(function(){
		var produk_id = $(this).find('input[name*="[produk_id]"]').val();
		if($(this).find('input[name*="[kubikasi_realisasi]"]').length > 0){
			var kubikasi = $(this).find('input[name*="[kubikasi_realisasi]"]').val();
		} else {
			var kubikasi = 0;
		}
		if (produk_id) {
			produks.push({
				produk_id: produk_id,
				kubikasi: kubikasi
			});
		}
	});

	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/validatingKubikasi']); ?>',
		type   : 'POST',
		data   : {produks:produks, edit:edit, spm_id:spm_id},
		success: function (data) {
			// console.log(data);
			var isValid = true;

			// loop semua data.post (input user)
			Object.values(data.post).forEach(function(postItem) {
				var id = postItem.po_ko_detail_id;
				var kubikasiInput = parseFloat(postItem.kubikasi);

				// kubikasi maks dan kubikasi yg ada di semua op
				var maks = data.maks[id] ? parseFloat(data.maks[id].kubikasi) : 0;
				var spm = data.spm[id] ? parseFloat(data.spm[id].kubikasi) : 0;

				// acuan kubikasi tidak boleh lebih dari 10%-nya kubikasi PO
				maks = maks + (maks * 10/100);
				var sisa = maks - spm;

				if (kubikasiInput > sisa) {
					isValid = false;
				}
			});

			if (!isValid) {
				cisAlert('Jumlah kubikasi melebihi kubikasi di PO, mohon cek kembali!!!');
			}

			callback(isValid);
		},
		error: function () {
			cisAlert("Terjadi kesalahan saat memvalidasi kubikasi.");
			callback(false);
		}
	}); 
}




</script>