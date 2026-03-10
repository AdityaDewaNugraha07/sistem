<?php
/* @var $this yii\web\View */
$this->title = 'Surat Perintah Muat (SPM) - EXPORT';
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
										<?= yii\bootstrap\Html::activeHiddenInput($model, "packinglist_id"); ?>
											<?= $form->field($model, 'nomor')->textInput()->label("Packinglist"); ?>
										<?php }else{ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Packinglist'); ?></label>
												<div class="col-md-7">
													<span class="input-group-btn" style="width: 100%">
														<?php
														$modAllSPM = Yii::$app->db->createCommand("SELECT packinglist_id, container_no FROM t_spm_ko WHERE jenis_penjualan = 'export' AND cancel_transaksi_id IS NULL ORDER BY spm_ko_id DESC")->queryAll(); $disabled = [];
														foreach($modAllSPM as $izxs => $illdis){
															$disabled[$illdis['packinglist_id'].'-'.$illdis['container_no']] = ['disabled'=>true];
														} 
														?>
														<?= \yii\bootstrap\Html::activeDropDownList($model, 'packinglist_id', app\models\TPackinglistContainer::getOptionListSPM(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setOP();','style'=>'width:100%;','options'=>$disabled]); ?>
													</span>
													<span class="input-group-btn" style="width: 20%">
														<a class="btn btn-icon-only btn-default tooltips" onclick="openPackinglist();" data-original-title="Daftar OP" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'cust_id'); ?>
										<?= $form->field($model, 'jenis_produk')->textInput(['disabled'=>'disabled'])->label("Jenis Produk"); ?>
										<div class="form-group" id="place-buyer">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Shipment To'); ?></label>
											<div class="col-md-7" style="padding-bottom: 5px;">
												<?= \yii\bootstrap\Html::activeTextarea($model, 'shipment_to', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
											</div>
										</div>
										<div class="form-group" id="place-applicant" style="display: none;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Applicant'); ?></label>
											<div class="col-md-7" style="padding-bottom: 5px;">
												<?= \yii\bootstrap\Html::activeTextarea($model, 'applicant', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
											</div>
										</div>
										<div class="form-group" id="place-notify_party" style="display: none;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Notify Party'); ?></label>
											<div class="col-md-7" style="padding-bottom: 5px;">
												<?= \yii\bootstrap\Html::activeTextarea($model, 'notify_party', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
											</div>
										</div>
										<?= $form->field($model, 'port_of_loading')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'final_destination')->textInput(['disabled'=>'disabled']); ?>
										<?= $form->field($model, 'container_kode')->textInput()->label("Container No."); ?>
										<?= $form->field($model, 'seal_no')->textInput()->label("Seal No."); ?>
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
                                        <h5><?= Yii::t('app', 'Realisasi Product List (Hasil SCAN)'); ?></h5>
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
														<th rowspan="2" style="width: 100px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Scanned By'); ?></th>
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
<!--															<a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="addItemProductList();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>-->
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
														<th style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px;"><?= Yii::t('app', 'Palet'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 100px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px;"><?= Yii::t('app', 'Palet'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 100px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
														<th style="font-size: 1.2rem; line-height: 0.9; width: 70px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
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
	$('select[name*=\"[packinglist_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Packinglist',
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
	
function openPackinglist(){
	var url = '<?= \yii\helpers\Url::toRoute(['/exim/spmexport/openPackinglist']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pick(packinglist_id,cont_no){
	$("#modal-master").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>").val(packinglist_id+'-'+cont_no).trigger('change');
}
	
function setOP(){
	var packinglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/exim/spmexport/setOP']); ?>',
        type   : 'POST',
        data   : {packinglist_id:packinglist_id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val('');
			$("#place-buyer").find("textarea").html('');
			$("#place-applicant").find("textarea").html('');
			$("#place-notify_party").find("textarea").html('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "port_of_loading") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "final_destination") ?>").val('');
			$('#table-detail tbody').html("");
			if(data.packinglist){
				$("#modal-master").find('button.fa-close').trigger('click');
				$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val(data.packinglist.jenis_produk);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val(data.packinglist.cust_id);
				$("#place-buyer").find("textarea").html(data.packinglist.buyer);
				$("#place-applicant").find("textarea").html(data.packinglist.applicant);
				$("#place-notify_party").find("textarea").html(data.packinglist.notify_party);
				$("#<?= yii\bootstrap\Html::getInputId($model, "port_of_loading") ?>").val(data.packinglist.port_of_loading);
				$("#<?= yii\bootstrap\Html::getInputId($model, "final_destination") ?>").val(data.packinglist.final_destination);
				if(data.packinglist.notify_party){
					$("#place-buyer").attr("style","display:none;");
					$("#place-applicant").attr("style","display:;");
					$("#place-notify_party").attr("style","display:;");
				}else{
					$("#place-buyer").attr("style","display:;");
					$("#place-applicant").attr("style","display:none;");
					$("#place-notify_party").attr("style","display:none;");
				}
				getItems(packinglist_id);
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function getItems(packinglist_id){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/spmexport/getItems']); ?>',
		type   : 'POST',
		data   : {packinglist_id:packinglist_id},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function listRandom(ele){
	var produk_id = $(ele).parents('tr').find('input[name*="[produk_id]"]').val();
	var packinglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/exim/spmexport/listRandom']); ?>?produk_id='+produk_id+'&post='+packinglist_id;
	$(".modals-place-2-min").load(url, function() {
		$("#modal-random .modal-dialog").css('width','50%');
		$("#modal-random").modal('show');
		$("#modal-random").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function save(){
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_selesaimuat") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>").parents(".form-group").removeClass("has-error");
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
	var status = $("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val();
	var diperiksa = $("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>").val();
	var diperiksa_security = $("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").val();
	var dikeluarkan = $("#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>").val();
	var disetujui = $("#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>").val();
	var final_destination = $("#<?= \yii\bootstrap\Html::getInputId($model, "final_destination") ?>").val();
	if(status == "<?= app\models\TSpmKo::REALISASI ?>"){
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
			var field1 = $(this).find('input[name*="[nomor_produksi]"]');
			if(!field1.val()){
				$(this).find('input[name*="[nomor_produksi]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[nomor_produksi]"]').parents('td').removeClass('error-tb-detail');
			}
		});
		
		$('#table-detail tbody > tr').each(function(i){
			var qty_kecil = unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
			var qty_kecil_realisasi = unformatNumber( $(this).find('input[name*="[qty_kecil_realisasi]"]').val() );
			var qty_besar = unformatNumber( $(this).find('input[name*="[qty_besar]"]').val() );
			var qty_besar_realisasi = unformatNumber( $(this).find('input[name*="[qty_besar_realisasi]"]').val() );
			var kubikasi = unformatNumber( $(this).find('input[name*="[kubikasi]"]').val() );
			var kubikasi_realisasi_display = unformatNumber( $(this).find('input[name*="[kubikasi_realisasi_display]"]').val() );
			
			if($(this).find('input[name*="[qty_besar_realisasi]"]').length > 0){
				if((qty_besar_realisasi <= 0) || (qty_besar != qty_besar_realisasi)){
					$(this).find('input[name*="[qty_besar_realisasi]"]').parents('td').addClass('error-tb-detail');
					has_error = has_error + 1;
				}else{
					$(this).find('input[name*="[qty_besar_realisasi]"]').parents('td').removeClass('error-tb-detail');
				}
			}
			if($(this).find('input[name*="[qty_kecil_realisasi]"]').length > 0){
				if((qty_kecil_realisasi <= 0) || (qty_kecil != qty_kecil_realisasi)){
					$(this).find('input[name*="[qty_kecil_realisasi]"]').parents('td').addClass('error-tb-detail');
					has_error = has_error + 1;
				}else{
					$(this).find('input[name*="[qty_kecil_realisasi]"]').parents('td').removeClass('error-tb-detail');
				}
			}
			if($(this).find('input[name*="[kubikasi_realisasi_display]"]').length > 0){
				if((kubikasi_realisasi_display <= 0) || (kubikasi != kubikasi_realisasi_display)){
					$(this).find('input[name*="[kubikasi_realisasi_display]"]').parents('td').addClass('error-tb-detail');
					has_error = has_error + 1;
				}else{
					$(this).find('input[name*="[kubikasi_realisasi_display]"]').parents('td').removeClass('error-tb-detail');
				}
			}
		});
	}
	
	if(!$("#<?= \yii\bootstrap\Html::getInputId($model, "final_destination") ?>").val()){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "final_destination") ?>").parents(".form-group").addClass("has-error");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "final_destination") ?>").parents(".form-group").removeClass("has-success");
		has_error = has_error + 1;
	}
	
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
	$('#<?= yii\bootstrap\Html::getInputId($model, 'waktu_selesaimuat') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if($model->status == \app\models\TSpmKo::REALISASI){ ?>
	$('#btn-add-item').remove();
	<?php } ?>
	<?php if(isset($_GET['edit'])){ ?>
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_kirim") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal_kirim') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal_rencanamuat') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "waktu_selesaimuat") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'waktu_selesaimuat') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "dikeluarkan") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "container_kode") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "seal_no") ?>').prop("disabled", false);
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
	<?php } ?>
}

function setStatus(){
	var status = $('#<?= \yii\bootstrap\Html::getInputId($model, 'status') ?>').val();
	var spm_ko_id = '<?= isset($_GET['spm_ko_id'])?$_GET['spm_ko_id']:"" ?>';
	var color = "";
	if(status == "<?= \app\models\TSpmKo::REALISASI; ?>"){
		color = "#95EBA3";
		$("#place-waktuselesaimuat").slideDown();
		$("#place-produklist").css('display','');
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
}
function getItemsById(id,realisasi=null,edit=null){
	var post = $('#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>').val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/exim/spmexport/getItemsById']); ?>',
		type   : 'POST',
		data   : {id:id,realisasi:realisasi,edit:edit,post:post},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				formconfig();
				if(realisasi){
//					fillSpmRealisasi();
				}
			}
			
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getCurrentProdukList(){
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var post = $('#<?= yii\bootstrap\Html::getInputId($model, "packinglist_id") ?>').val();
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
        url    : '<?= \yii\helpers\Url::toRoute(['/exim/spmexport/getCurrentProdukList']); ?>',
        type   : 'POST',
        data   : {kode_spm:kode_spm,status:status,post:post},
        success: function (data) {
            if(data.item){
				$('#table-detail-produklist > tbody').html(data.item);
				reordertable("#table-detail-produklist");
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

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/exim/spmexport/daftarAfterSave']) ?>','modal-aftersave','95%');
}

function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/exim/spmexport/index','spm_ko_id'=>'']); ?>'+id+'&edit=1');
}
function printSPM(id){
	window.open("<?= yii\helpers\Url::toRoute('/exim/spmexport/printSPM') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
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

</script>