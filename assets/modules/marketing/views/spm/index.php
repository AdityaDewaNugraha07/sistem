<?php
/* @var $this yii\web\View */
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
										<?= $form->field($model, 'cust_an_alamat')->textarea(['disabled'=>'disabled'])->label("Alamat Customer") ?>
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
														<th rowspan="2" style="width: 400px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Produk'); ?></th>
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
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_alamat") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_kirim") ?>").val('');
			$('#table-detail tbody').html("");
			if(data.op_ko_id){
				$("#modal-master").find('button.fa-close').trigger('click');
				$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val(data.jenis_produk);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val(data.cust.cust_id);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_nama") ?>").val(data.cust.cust_an_nama);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_pr_nama") ?>").val(data.cust.cust_pr_nama);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_alamat") ?>").val(data.cust.cust_an_alamat);
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
    }else{
        $(".place-satuan-produk").css("display","");
        $(".place-satuan-limbah").css("display","none");
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
	$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_mulaimuat") ?>").parents(".form-group").addClass("has-success");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_selesaimuat") ?>").parents(".form-group").addClass("has-success");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>").parents(".form-group").addClass("has-success");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").parents(".form-group").addClass("has-success");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>").parents(".form-group").addClass("has-success");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>").parents(".form-group").addClass("has-success");
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
            return false;
        }
		if(validatingDetail()){
            submitform($form);
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
		$('#table-detail tbody > tr').each(function(){
			var qty_kecil = unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
			var qty_kecil_realisasi = unformatNumber( $(this).find('input[name*="[qty_kecil_realisasi]"]').val() );
			var qty_besar = unformatNumber( $(this).find('input[name*="[qty_besar]"]').val() );
			var qty_besar_realisasi = unformatNumber( $(this).find('input[name*="[qty_besar_realisasi]"]').val() );
			var kubikasi = unformatNumber( $(this).find('input[name*="[kubikasi]"]').val() );
			var kubikasi_realisasi = unformatNumber( $(this).find('input[name*="[kubikasi_realisasi]"]').val() );
			
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
                
            }else{
                if(!kubikasi_realisasi){
                    $(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').addClass('error-tb-detail');
                    has_error = has_error + 1;
                }else{
                    if((kubikasi_realisasi <= 0) || (kubikasi != kubikasi_realisasi)){
                        $(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').addClass('error-tb-detail');
                        has_error = has_error + 1;
                    }else{
                        $(this).find('input[name*="[kubikasi_realisasi]"]').parents('td').removeClass('error-tb-detail');
                    }
                }
            }
			
			if( !$(this).find("input[name*='[op_ko_random_id]']").val() ){
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
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if($model->status == \app\models\TSpmKo::REALISASI){ ?>
	$('#btn-add-item').remove();
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
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
	<?php } ?>
}

function getItemsById(id,realisasi=null,edit=null){
    var jns_produk = $("#<?= yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
    if(jns_produk == "Limbah"){
        $(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","");
    }else{
        $(".place-satuan-produk").css("display","");
        $(".place-satuan-limbah").css("display","none");
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
					fillSpmRealisasi();
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
                        }else{
                            $("#place-produklist").css('display','');
                        }
						getItemsById(spm_ko_id,1,'<?= isset($_GET['edit'])?$_GET['edit']:""; ?>');
						getCurrentProdukList();
					}else{
						color = "#FBE88C";
						$("#place-waktuselesaimuat").slideUp();
						$("#place-produklist").css('display','none');
						$("#table-detail-produklist tbody").html("");
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
					totalpl_kubikasi += unformatNumber( $(this).find("input[name*='[kubikasi_hasilhitung]']").val() );
				}
			}
		});
		
		totalpl_kubikasi =  (Math.round( totalpl_kubikasi * 10000 ) / 10000 ).toString(); // membuat 4 digit belakang koma
		
		if(!$(this).find("input[name*='[op_ko_random_id]']").val()){
            $(this).find("input[name*='[qty_besar_realisasi]']").val( (totalpl_palet!="-")?formatNumberForUser(totalpl_palet):totalpl_palet );
            $(this).find("input[name*='[qty_kecil_realisasi]']").val( formatNumberForUser(totalpl_qty_kecil) );
			$(this).find("input[name*='[satuan_kecil_realisasi]']").val( totalpl_satuan_kecil );
			$(this).find("input[name*='[kubikasi_realisasi]']").val( formatNumberFixed4(totalpl_kubikasi) );
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
//    result = (Math.round( result * 10000 ) / 10000 ).toString(); // karena mengambil nilai asli
	if((result==0)&&( unformatNumber($(ele).parents('tr').find('input[name*="[kubikasi]"]').val())>0 )){
		result = unformatNumber($(ele).parents('tr').find('input[name*="[kubikasi]"]').val());
		
	}
    $(ele).parents('tr').find('input[name*="[kubikasi_hasilhitung]"]').val( result );
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

</script>