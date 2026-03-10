<?php
/* @var $this yii\web\View */
$this->title = 'Voucher Pengeluaran';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Voucher Pengeluaran'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.table-advance tr td:first-child {
    border-left-width: 1px !important;
}
.table-detail-terimabhp {
    border-left: 1px solid transparent !important;
}
.table-detail-terimabhp > tbody > tr > td{
    background-color: #e2f1ff; border: 1px solid #303030;
}
.table-detail-terimabhp > tbody > tr > th{
    background-color: #e2f1ff; border: 1px solid #303030;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <a class="btn blue btn-sm btn-outline pull-right" style="margin-left: 5px;" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Cari Voucher Pengeluaran'); ?></a>
                        <a class="btn dark btn-sm btn-outline pull-right" onclick="cariOpenVoucher()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Cari Open Voucher'); ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Voucher'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?php 
										if(!isset($_GET['voucher_pengeluaran_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
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
										
                                        <?php echo $form->field($model, 'tipe')->dropDownList(\app\models\MDefaultValue::getOptionList('tipe-voucher'),['prompt'=>'','class'=>'form-control select2','onchange'=>'setDropdownSupplier()']); ?>
										<?= $form->field($model, 'tanggal_bayar',[
											'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
											<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
											{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?php if( (isset($_GET['voucher_pengeluaran_id'])) && ($model->cancel_transaksi_id == NULL) ){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<a href="javascript:void(0);" onclick="cancelVoucher(<?= $model->voucher_pengeluaran_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Voucher'); ?></a>
												</div>
											</div>
										<?php } ?>
										<?php if($model->cancel_transaksi_id != null){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
													<?php
													$modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
													echo "<br><span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena ".$modCancel->cancel_reason."</span>";
													?>
												</div>
											</div>
										<?php } ?>
                                    </div>
                                    <div class="col-md-6">
										<div id="supplier-place">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'suplier_nm')->textInput()->label('Supplier');
												echo \yii\bootstrap\Html::activeHiddenInput($model, 'suplier_id');
											}else{
												echo $form->field($model, 'suplier_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff(function(){ setAutoItems(); });']);
											}
											?>
										</div>
										<div id="ppk-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'ppk_kode')->textInput()->label('Kode PPK');
												echo \yii\bootstrap\Html::activeHiddenInput($model, 'ppk_id');
											}else{
												echo $form->field($model, 'ppk_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode PPK');
											}
											?>
										</div>
										<div id="gkk-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'gkk_kode')->textInput()->label('Kode GKK');
												echo \yii\bootstrap\Html::activeHiddenInput($model, 'gkk_id');
											}else{
												echo $form->field($model, 'gkk_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode GKK');
											}
											?>
										</div>
										<div id="pdg-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'pdg_kode')->textInput(['class'=>'form-control fontsize-1-1'])->label('Kode Ajuan');
												echo \yii\bootstrap\Html::activeHiddenInput($model, 'ajuandinas_grader_id');
											}else{
												echo $form->field($model, 'ajuandinas_grader_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode Ajuan');
											}
											?>
										</div>
										<div id="pmg-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'pmg_kode')->textInput(['class'=>'form-control fontsize-1-1'])->label('Kode Ajuan');
												echo \yii\bootstrap\Html::activeHiddenInput($model, 'ajuanmakan_grader_id');
											}else{
												echo $form->field($model, 'ajuanmakan_grader_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode Ajuan');
											}
											?>
										</div>
										<div id="pdl-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'pdl_kode')->textInput(['class'=>'form-control fontsize-1-1'])->label('Kode Ajuan');
												echo \yii\bootstrap\Html::activeHiddenInput($model, 'log_bayar_dp_id');
											}else{
												echo $form->field($model, 'log_bayar_dp_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode Ajuan DP');
											}
											?>
										</div>
										<div id="mlg-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){
												echo $form->field($model, 'mlg_kode')->textInput(['class'=>'form-control fontsize-1-1'])->label('Kode Ajuan');
												echo \yii\bootstrap\Html::activeHiddenInput($model, 'log_bayar_muat_id');
											}else{
												echo $form->field($model, 'log_bayar_muat_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode Ajuan');
											}
											?>
										</div>
										<div id="ovk-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){
												echo $form->field($model, 'ovk_kode')->textInput(['class'=>'form-control fontsize-1-1'])->label('Kode Open Voucher');
												echo \yii\bootstrap\Html::activeHiddenInput($model, 'open_voucher_id');
											}else{
												echo $form->field($model, 'open_voucher_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode Open Voucher');
											}
											?>
										</div>
										<div class="form-group">
											<?= \yii\bootstrap\Html::activeLabel($model, 'akun_debit', ['class'=>'col-md-4 control-label']) ?>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 55%">
													<?= $form->field($model, 'akun_debit',['template'=>'{input}','options'=>['style'=>'margin-left: 0px; margin-right: 0px;']])->dropDownList(\app\models\MAcctRekening::getOptionListBank(),['prompt'=>'','style'=>'padding:6px;']); ?>
												</span>
												<span class="input-group-btn" style="width: 45%">
													<?= $form->field($model, 'totaldebit',['template'=>'{input}','options'=>['style'=>'margin-left: 0px; margin-right: 0px;']])->textInput(['class'=>'form-control money-format']); ?>
												</span> 
											</div>
										</div>
										<div class="form-group" style="margin-top: 5px;">
											<?= \yii\bootstrap\Html::activeLabel($model, 'cara_bayar', ['class'=>'col-md-4 control-label']) ?>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 40%">
													<?= $form->field($model, 'cara_bayar',['template'=>'{input}','options'=>['style'=>'margin-left: 0px; margin-right: 0px;']])->dropDownList(\app\models\MDefaultValue::getOptionListCustom('cara-bayar',"'Transfer Bank','Tunai'",'ASC'),['style'=>'padding:6px;','onchange'=>'setCarabayarReff()']); ?>
												</span>
												<span class="input-group-btn" style="width: 60%; visibility: hidden;">
													<?= $form->field($model, 'cara_bayar_reff',['template'=>'{input}','options'=>['style'=>'margin-left: 0px; margin-right: 0px;']])->textInput(['class'=>'form-control']); ?>
												</span> 
											</div>
										</div>
										<?php if(isset($_GET['voucher_pengeluaran_id']) && !isset($_GET['edit']) && ($model->cancel_transaksi_id == NULL) ){ ?>
										<div class="form-group" style="margin-top: 3px;">
											<?= \yii\bootstrap\Html::activeLabel($model, 'status_bayar', ['class'=>'col-md-4 control-label']) ?>
											<div class="col-md-7" style="margin-top: 8px;">
												<?php echo !empty($model->status_bayar)?$model->Status_bayar:""; ?>
											</div>
										</div>
										<?php }?>
										<?php if($model->cancel_transaksi_id != null){ ?>
										<div class="form-group" style="margin-top: 3px;">
											<?= \yii\bootstrap\Html::activeLabel($model, 'status_bayar', ['class'=>'col-md-4 control-label']) ?>
											<div class="col-md-7" style="margin-top: 8px;">
												<?php echo $model->status_bayar; ?>
											</div>
										</div>
										<?php }?>
                                    </div>
                                </div>
                                <br><hr>
                                <div class="row">
									<div class="col-md-5">
										<div class="row">
											<div class="col-md-8">
												<h4><?= Yii::t('app', 'Detail BBK'); ?></h4>
											</div>
											<div class="col-md-4">
												<?php echo \yii\bootstrap\Html::activeDropDownList($model, 'mata_uang', \app\models\MDefaultValue::getOptionListLabelValue('mata-uang'),['class'=>'form-control','style'=>'font-size: 1.3rem; padding: 3px; height: 27px;']) ?>
											</div>
											<div class="col-md-12" style="padding: 0px;">
												<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail">
													<thead>
														<tr>
															<th style="width: 30px;">No.</th>
															<th><?php echo Yii::t('app', 'Keterangan'); ?></th>
															<th style="width: 100px;"><?= Yii::t('app', 'Jumlah'); ?></th>
															<th style="width: 35px;"></th>
														</tr>
													</thead>
													<tbody>
														<?php
														if(count($modDetails) && !isset($_GET['edit'])){
															foreach($modDetails as $i => $detail){ ?>
																<tr>
																	<td style="padding-top: 10px; vertical-align:middle; text-align:center;">
																		<?= $i+1; ?>
																	</td>
																	<td style="text-align: left;">
																		<?= $detail->keterangan; ?>
																	</td>
																	<td style="text-align: right;">
																		<?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->jumlah); ?>
																	</td>
																	<td style="padding-top: 10px; text-align: center;">
																		-
																	</td>
																</tr>
														<?php	}
														}
														?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="2" style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;">
																<u>Total</u> &nbsp;
															</td>
                                                            <td style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;" colspan="2">
																<?= \yii\bootstrap\Html::activeTextInput($model, 'totalkredit', ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size:1.2rem;']); ?>
															</td>
														</tr>
														<tr>
															<td colspan="5">
																<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
															</td>
														</tr>
													</tfoot>
												</table>
											</div>
											
										</div>
									</div>
									<div class="col-md-7">
										<div class="row">
											<div class="col-md-12">
												<h4><?= Yii::t('app', 'Detail Total Pembayaran'); ?></h4>
											</div>
											<div class="col-md-12">
												<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%;" id="total-pembayaran" >
												<tbody style="font-weight: bold;">
													<tr>
														<td style="width: 60%; text-align: right;">TOTAL DPP</td>
														<td class="td-kecil" style="width: 40%; text-align: right;" >
															<?= \yii\bootstrap\Html::activeTextInput($model, 'total_dpp', ['class'=>'form-control float','disabled'=>'disabled']); ?>
														</td>
													</tr>
													<tr id="totalbayar-tr-dp">
														<td style="text-align: right;">TOTAL DP</td>
														<td class="td-kecil">
															<?= \yii\bootstrap\Html::activeTextInput($model, 'total_dp', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true)']); ?>
														</td>
													</tr>
													<tr id="totalbayar-tr-dp">
														<td style="text-align: right;" class="font-blue">SISA BAYAR</td>
														<td class="td-kecil">
															<?= \yii\bootstrap\Html::activeTextInput($model, 'total_sisa', ['class'=>'form-control float font-blue','disabled'=>'disabled']); ?>
														</td>
													</tr>
													<tr id="totalbayar-tr-kosong"></tr>
													<tr>
														<td style="text-align: right;">TOTAL PPN</td>
														<td class="td-kecil">
															<?= \yii\bootstrap\Html::activeTextInput($model, 'total_ppn', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true,true)']); ?>
														</td>
													</tr>
													<tr>
														<td style="text-align: right;">TOTAL PPh</td>
														<td class="td-kecil">
															<?= \yii\bootstrap\Html::activeTextInput($model, 'total_pph', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true)']); ?>
														</td>
													</tr>
													<tr>
														<td style="text-align: right;">TOTAL PBBKB</td>
														<td class="td-kecil">
															<?= \yii\bootstrap\Html::activeTextInput($model, 'total_pbbkb', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true)','disabled'=>'disabled']); ?>
														</td>
													</tr>
													<tr>
														<td style="text-align: right;">BIAYA TAMBAHAN</td>
														<td class="td-kecil">
															<?= \yii\bootstrap\Html::activeTextInput($model, 'biaya_tambahan', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true)']); ?>
														</td>
													</tr>
													<tr>
														<td style="text-align: right;">TOTAL POTONGAN</td>
														<td class="td-kecil">
															<?= \yii\bootstrap\Html::activeTextInput($model, 'total_potongan', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true)']); ?>
														</td>
													</tr>
													<tr>
														<td style="text-align: right;" class="font-red-mint">TOTAl PEMBAYARAN</td>
														<td class="td-kecil">
															<?= \yii\bootstrap\Html::activeTextInput($model, 'total_pembayaran', ['class'=>'form-control float font-red-mint','disabled'=>'disabled']); ?>
														</td>
													</tr>
												</tbody>
												</table>
											</div>
										</div>
										<div class="row">
											<div id="detail-gkk-place"></div>
										</div>
										<div class="row">
											<div id="detail-penerimaan-place"></div>
										</div>
										<div class="row">
											<div id="detail-pakaidp-place"></div>
										</div>
										<div class="row">
											<div id="detail-ppk-place"></div>
										</div>
										<div class="row">
											<div id="detail-reff-place"></div>
										</div>
										<div class="row">
											<div id="detail-pdg-place"></div>
										</div>
										<div class="row">
											<div id="detail-pmg-place"></div>
										</div>
										<div class="row">
											<div id="detail-pdl-place"></div>
										</div>
										<div class="row">
											<div id="detail-mlg-place"></div>
										</div>
										<div class="row">
											<div id="detail-ovk-place"></div>
										</div>
									</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printBbk('.(isset($_GET['voucher_pengeluaran_id'])?$_GET['voucher_pengeluaran_id']:'').');','disabled'=>true]); ?>
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
if( isset($_GET['voucher_pengeluaran_id']) && isset($_GET['edit'])){
	$pagemode = "editPage(".$_GET['voucher_pengeluaran_id'].")";
}else if(isset($_GET['voucher_pengeluaran_id'])){
    $pagemode = "setDropdownSupplier(); afterSaveThis(); setTimeout(function(){ setDetailReff(); },1000)";
}else if(isset($_GET['setOpenVoucher'])){
    $pagemode = "addItem(); setOpenVoucher(".$_GET['setOpenVoucher'].");";
}else {
    $pagemode = "addItem();";
}
?>
<?php $this->registerJs(" 
    $pagemode
	$(this).find('select[name*=\"[tipe]\"]').select2({
		allowClear: !0,
		placeholder: 'Tipe Voucher',
		width: null
	});
	$(this).find('select[name*=\"[suplier_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Supplier',
		width: null
	});
	$(this).find('select[name*=\"[ppk_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode PPK',
		width: null
	});
	$(this).find('select[name*=\"[gkk_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode GKK',
		width: null
	});
	$(this).find('select[name*=\"[ajuandinas_grader_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Pengajuan',
		width: null
	});
	$(this).find('select[name*=\"[ajuandinas_grader_id]\"]').on('select2:open', function (e) {
		$('#select2-tvoucherpengeluaran-ajuandinas_grader_id-results').addClass('fontsize-1-1');
	});
	$(this).find('select[name*=\"[ajuanmakan_grader_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Pengajuan',
		width: null
	});
	$(this).find('select[name*=\"[ajuanmakan_grader_id]\"]').on('select2:open', function (e) {
		$('#select2-tvoucherpengeluaran-ajuanmakan_grader_id-results').addClass('fontsize-1-1');
	});
	$(this).find('select[name*=\"[open_voucher_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Open Voucher',
		width: null
	});
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function setDropdownSupplier(){
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').siblings('.select2').addClass('animation-loading');
	var type = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tipe') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/voucher/setDropdownSupplier']); ?>',
		type   : 'POST',
		data   : {type:type},
		success: function (data) {
			$("#supplier-place").attr('style','display:none;');
			$("#ppk-place").attr('style','display:none;');
			$("#gkk-place").attr('style','display:none;');
			$("#pdg-place").attr('style','display:none;');
			$("#pmg-place").attr('style','display:none;');
			$("#pdl-place").attr('style','display:none;');
			$("#mlg-place").attr('style','display:none;');
			$("#ovk-place").attr('style','display:none;');
			if(data.type == 'Pembelian BHP' || data.type == 'Pembayaran DP BHP'){
				$("#supplier-place").attr('style','display:;');
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>").html(data.html);
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_kontrak_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
			}else if(data.type == 'Top-up Kas Kecil'){
				$("#ppk-place").attr('style','display:;');
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'ppk_id') ?>").html( data.html );
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ppk_id') ?>').siblings('.select2').removeClass('animation-loading');	
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_kontrak_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
			}else if(data.type == 'Ganti Kas Besar' || data.type == 'Ganti Kas Kecil'){
				$("#gkk-place").attr('style','display:;');
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'gkk_id') ?>").html( data.html );
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'gkk_id') ?>').siblings('.select2').removeClass('animation-loading');	
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_kontrak_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
			}else if(data.type == 'Uang Dinas Grader'){
				$("#pdg-place").attr('style','display:;');
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'ajuandinas_grader_id') ?>").html( data.html );
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuandinas_grader_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_kontrak_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
				$('#select2-tvoucherpengeluaran-ajuandinas_grader_id-container').addClass('fontsize-1-1'); // font-size
			}else if(data.type == 'Uang Makan Grader'){
				$("#pmg-place").attr('style','display:;');
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'ajuanmakan_grader_id') ?>").html( data.html );
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuanmakan_grader_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_kontrak_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
				$('#select2-tvoucherpengeluaran-ajuanmakan_grader_id-container').addClass('fontsize-1-1'); // font-size
			}else if(data.type == 'Pembayaran DP Log'){
				$("#pdl-place").attr('style','display:;');
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_dp_id') ?>").html( data.html );
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_dp_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
				$('#select2-tvoucherpengeluaran-log_kontrak_id-container').addClass('fontsize-1-3'); // font-size
			}else if(data.type == 'Pelunasan Log'){
				$("#mlg-place").attr('style','display:;');
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_muat_id') ?>").html( data.html );
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_muat_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
				$('#select2-tvoucherpengeluaran-log_kontrak_id-container').addClass('fontsize-1-3'); // font-size
			}else if(data.type == 'Open Voucher'){
				$("#ovk-place").attr('style','display:;');
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'open_voucher_id') ?>").html( data.html );
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'open_voucher_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
                $('#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
//				$('#select2-tvoucherpengeluaran-open_voucher_id-container').addClass('fontsize-1-3'); // font-size
			}
			resetTotalPembayaran();
			$('#detail-penerimaan-place').html("");
			$('#detail-reff-place').html("");
			$('#detail-pakaidp-place').html("");
			$('#detail-gkk-place').html("");
			$('#detail-pdg-place').html("");
			$('#detail-pmg-place').html("");
			$('#detail-pdl-place').html("");
			$('#detail-mlg-place').html("");
			$('#detail-ovk-place').html("");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setDetailReff(callback=null){
	$('#detail-reff-place').addClass('animation-loading');
	var supplier_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>").val();
	var ppk_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'ppk_id') ?>").val();
	var gkk_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'gkk_id') ?>").val();
	var ajuandinas_grader_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'ajuandinas_grader_id') ?>").val();
	var ajuanmakan_grader_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'ajuanmakan_grader_id') ?>").val();
	var log_bayar_dp_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_dp_id') ?>").val();
	var log_bayar_muat_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'log_bayar_muat_id') ?>").val();
	var type = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tipe') ?>').val();
	var voucher_pengeluaran_id = '<?= isset($_GET['voucher_pengeluaran_id'])?$_GET['voucher_pengeluaran_id']:null; ?>';
	var open_voucher_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'open_voucher_id') ?>").val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/voucher/setDetailReff']); ?>',
        type   : 'POST',
        data   : {type:type,supplier_id:supplier_id,voucher_pengeluaran_id:voucher_pengeluaran_id,ppk_id:ppk_id,gkk_id:gkk_id,ajuandinas_grader_id:ajuandinas_grader_id,ajuanmakan_grader_id:ajuanmakan_grader_id,
                  log_bayar_dp_id:log_bayar_dp_id,log_bayar_muat_id:log_bayar_muat_id,open_voucher_id:open_voucher_id},
        success: function (data) {
			$('#detail-penerimaan-place').html("");
			$('#detail-reff-place').html("");
			$('#detail-pakaidp-place').html("");
			$('#detail-gkk-place').html("");
			$('#detail-ppk-place').html("");
			$('#detail-pdg-place').html("");
			$('#detail-pmg-place').html("");
			$('#detail-pdl-place').html("");
			$('#detail-ovk-place').html("");
			resetTotalPembayaran();
			if(data.htmlterima){
                $('#detail-penerimaan-place').html(data.htmlterima);
				reordertable('#table-detail-terima');
                
                // set biaya tambahan bhp
                var bhpbiayatambahan = $('input[name*="bhpbiayatambahan"]').val();
                $('input[name*="[biaya_tambahan]"]').val(bhpbiayatambahan);
                // set biaya tambahan bhp
            }
            if(data.htmldp){
                $('#detail-pakaidp-place').html(data.htmldp);
				reordertable('#table-detail-dp');
				$('input[name*="[total_dp]"]').removeAttr('disabled');
            }else{
				$('input[name*="[total_dp]"]').attr('disabled','disabled');
			}
			if(data.htmlppk){
				$('#detail-ppk-place').html(data.htmlppk);
				reordertable('#table-pengeluaran-kaskecil');
				$('input[name*="totalppk"]').val(data.modPpk.nominal);
			}else{
				$('input[name*="totalppk"]').val(0);
			}
			if(data.htmlgkk){
				$('#detail-gkk-place').html(data.htmlgkk);
				reordertable('#table-gkk');
				$('input[name*="totalgkk"]').val(data.modGkk.totalnominal);
			}else{
				$('input[name*="totalgkk"]').val(0);
			}
			if(data.htmlpdg){
				$('#detail-pdg-place').html(data.htmlpdg);
				reordertable('#table-gkk');
				$('input[name*="totalpdg"]').val(data.modAjuanDinas.total_ajuan);
			}else{
				$('input[name*="totalpdg"]').val(0);
			}
			if(data.htmlpmg){
				$('#detail-pmg-place').html(data.htmlpmg);
				reordertable('#table-pmg');
				$('input[name*="totalpmg"]').val(data.modAjuanMakan.total_ajuan);
			}else{
				$('input[name*="totalpmg"]').val(0);
			}
			if(data.htmlpdl){
				$('#detail-pdl-place').html(data.htmlpdl);
				reordertable('#table-pdl');
				$('input[name*="totalpdl"]').val(data.modLogBayarDp.total_dp);
			}else{
				$('input[name*="totalpdl"]').val(0);
			}
			if(data.htmlmlg){
				$('#detail-mlg-place').html(data.htmlmlg);
				reordertable('#table-mlg');
				$('input[name*="totalmlg"]').val(data.modLogBayarMuat.total_bayar);
			}else{
				$('input[name*="totalmlg"]').val(0);
			}
			if(data.htmlovk){
				$('#detail-ovk-place').html(data.htmlovk);
				reordertable('#table-ovk');
				$('input[name*="totalovk"]').val(data.modOpenVoucher.total_pembayaran);
			}else{
				$('input[name*="totalovk"]').val(0);
			}
			
			<?php if(isset($_GET['voucher_pengeluaran_id']) && !isset($_GET['edit'])){ ?>
				setTotalPembayaran(null,false,data.voucher);
			<?php }else{ ?>
				setTotalPembayaran();
			<?php } ?>
			
			
			if($('#tvoucherpengeluaran-tipe').val() == "Pembelian BHP"){
				if(!data.htmldp){
					if( unformatNumber($('input[name="totalppnreff"]').val()) > 0 ){
						$('input[name*="[total_ppn]"]').removeAttr('disabled');
					}else{
						$('input[name*="[total_ppn]"]').attr('disabled','disabled');
					}
					if( unformatNumber($('input[name="totalpphreff"]').val()) > 0 ){
						$('input[name*="[total_pph]"]').removeAttr('disabled');
					}else{
						$('input[name*="[total_pph]"]').attr('disabled','disabled');
					}
                    // edit tambahan & potongan 
                    $('input[name*="[biaya_tambahan]"]').prop('disabled',false);
                    $('input[name*="[total_potongan]"]').prop('disabled',false);
				}
			}else if($('#tvoucherpengeluaran-tipe').val() == "Pembayaran DP BHP"){
				$('input[name*="[total_ppn]"]').removeAttr('disabled');
				$('input[name*="[total_pph]"]').removeAttr('disabled');
			}else if($('#tvoucherpengeluaran-tipe').val() == "Top-up Kas Kecil"){
				$('input[name*="[total_ppn]"]').attr('disabled','disabled');
				$('input[name*="[total_pph]"]').attr('disabled','disabled');
			}else if($('#tvoucherpengeluaran-tipe').val() == "Ganti Kas Besar" || $('#tvoucherpengeluaran-tipe').val() == "Ganti Kas Kecil"){
				$('input[name*="[total_ppn]"]').attr('disabled','disabled');
				$('input[name*="[total_pph]"]').attr('disabled','disabled');
			}else if($('#tvoucherpengeluaran-tipe').val() == "Pelunasan Log"){
				$('input[name*="[total_ppn]"]').attr('disabled','disabled');
				$('input[name*="[total_pph]"]').attr('disabled','disabled');
			}else if($('#tvoucherpengeluaran-tipe').val() == "Open Voucher"){
                $('input[name*="[total_ppn]"]').prop('disabled',true);
                $('input[name*="[total_pph]"]').prop('disabled',true);
                $('input[name*="[biaya_tambahan]"]').prop('disabled',false);
                $('input[name*="[total_potongan]"]').prop('disabled',true);
                if(data.modOpenVoucher){
                    if(data.modOpenVoucher.tipe == "DP LOG SENGON"){
                        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple').attr('onclick','detailPoByKode("'+data.modOpenVoucher.reff_no+'")');
                        $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+data.modOpenVoucher.penerima_reff_id+'")');
                    }else if(data.modOpenVoucher.tipe == "PELUNASAN LOG SENGON"){
                        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple').attr('onclick','detailPoByKode("'+data.modOpenVoucher.reff_no+'")');
                        $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+data.modOpenVoucher.penerima_reff_id+'")');
                        $("#place-berkas-reff").find('#btn-reff-3').removeClass('grey').addClass('green-seagreen').attr('onclick','');
                    }
                }
			}
			if(callback){ callback(); }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function addItem(){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/voucher/addItem']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail tbody').fadeIn(500,function(){
                    $(this).find('select[name*="[acct_id]"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik No. Rek',
                        width: null
                    });
                    reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function editItem(voucher_pengeluaran_id){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/voucher/editItem']); ?>',
        type   : 'POST',
        data   : {voucher_pengeluaran_id:voucher_pengeluaran_id},
        success: function (data) {
			$('#table-detail tbody').html("");
            if(data.item){
                $('#table-detail tbody').html(data.item);
				reordertable('#table-detail');
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setTotal(){
	var totalkredit = 0;
	$('#table-detail > tbody > tr').each(function(){
		totalkredit += unformatNumber($(this).find('input[name*="[jumlah]"]').val());
	});
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'totalkredit') ?>').val(formatInteger(totalkredit));//formatInteger diganti  unformatNumber agar bisa desimal
}

function setTotalPembayaran(editmode=null,editppn=false,loadaftersave=null){
	var total_dpp = 0; var total_dp = 0; var total_sisa = 0; var total_ppn = 0; var total_pph = 0; var total_pbbkb = 0; var total_ppk = 0; var total_gkk=0; var total_pdg=0; var total_pmg=0; var total_pdl=0; var total_mlg=0; 
    var total_pembayaran = 0; var biaya_tambahan = 0;  var total_potongan = 0; var total_ovk=0;
	
	if(editmode){
		total_dpp = unformatNumber( $('input[name*="[total_dpp]"]').val() );
		total_dp = unformatNumber( $('input[name*="[total_dp]"]').val() );
		total_pph = unformatNumber( $('input[name*="[total_pph]"]').val() );
		total_pbbkb = unformatNumber( $('input[name*="[total_pbbkb]"]').val() );
		biaya_tambahan = unformatNumber( $('input[name*="[biaya_tambahan]"]').val() );
		total_potongan = unformatNumber( $('input[name*="total_potongan"]').val() );
		total_ppk = unformatNumber( $('input[name*="totalppk"]').val() );
		total_gkk = unformatNumber( $('input[name*="totalgkk"]').val() );
		total_pdg = unformatNumber( $('input[name*="totalpdg"]').val() );
		total_pmg = unformatNumber( $('input[name*="totalpmg"]').val() );
		total_pdl = unformatNumber( $('input[name*="totalpdl"]').val() );
		total_mlg = unformatNumber( $('input[name*="totalmlg"]').val() );
		total_ovk = unformatNumber( $('input[name*="totalovk"]').val() );
		if(editppn){
			total_ppn = unformatNumber( $('input[name*="[total_ppn]"]').val() );
		}else{
			if( unformatNumber($('input[name="totalppnreff"]').val()) > 0 && total_ovk > 0 ){
				total_ppn = (total_dpp-total_dp) * 0.1;
			}else{
                total_ppn = unformatNumber( $('input[name="totalppnreff"]').val() );
            }
		}
	}else{
		total_dpp = unformatNumber( $('input[name="totaldppreff"]').val() );
		total_pph = unformatNumber( $('input[name="totalpphreff"]').val() );
		total_pbbkb = unformatNumber( $('input[name="totalpbbkb"]').val() );
		biaya_tambahan = unformatNumber( $('input[name*="biaya_tambahan"]').val() );
		total_potongan = unformatNumber( $('input[name="total_potongan"]').val() );
		total_ppk = unformatNumber( $('input[name*="totalppk"]').val() );
		total_gkk = unformatNumber( $('input[name*="totalgkk"]').val() );
		total_pdg = unformatNumber( $('input[name*="totalpdg"]').val() );
		total_pmg = unformatNumber( $('input[name*="totalpmg"]').val() );
		total_pdl = unformatNumber( $('input[name*="totalpdl"]').val() );
		total_mlg = unformatNumber( $('input[name*="totalmlg"]').val() );
		total_ovk = unformatNumber( $('input[name*="totalovk"]').val() );
		if( unformatNumber( $('input[name="totalppnreff"]').val() )  > 0 && total_ovk > 0 ){
			total_ppn = (total_dpp-total_dp) * 0.1;
			total_dp = unformatNumber( $('input[name="totaldp"]').val() ) / 1.1;
		}else{
			total_ppn = unformatNumber( $('input[name="totalppnreff"]').val() );
			total_dp = unformatNumber( $('input[name="totaldp"]').val() );
		}
	}
	
	if( ($('#tvoucherpengeluaran-tipe').val() == "Pembelian BHP") || ($('#tvoucherpengeluaran-tipe').val() == "Open Voucher") ){
		var total_sisa = total_dpp - total_dp;
		var total_pembayaran = total_sisa + total_ppn - total_pph + total_pbbkb + biaya_tambahan - total_potongan;
	}else if($('#tvoucherpengeluaran-tipe').val() == "Pembayaran DP BHP"){
		total_ppn = unformatNumber( $('input[name="totalppnreff"]').val() );
		total_pph = unformatNumber( $('input[name*="[total_pph]"]').val() );
		var total_pembayaran = total_dp + total_ppn + biaya_tambahan - total_pph - total_potongan;
	}else if($('#tvoucherpengeluaran-tipe').val() == "Top-up Kas Kecil"){
		var total_pembayaran = total_ppk + biaya_tambahan - total_potongan;
	}else if($('#tvoucherpengeluaran-tipe').val() == "Ganti Kas Besar" || $('#tvoucherpengeluaran-tipe').val() == "Ganti Kas Kecil"){
		var total_pembayaran = total_gkk + biaya_tambahan - total_potongan;
	}else if($('#tvoucherpengeluaran-tipe').val() == "Uang Dinas Grader"){
		var total_pembayaran = total_pdg + biaya_tambahan - total_potongan;
	}else if($('#tvoucherpengeluaran-tipe').val() == "Uang Makan Grader"){
		var total_pembayaran = total_pmg + biaya_tambahan - total_potongan;
	}else if($('#tvoucherpengeluaran-tipe').val() == "Pembayaran DP Log"){
		var total_pembayaran = total_pdl + biaya_tambahan - total_potongan;
	}else if($('#tvoucherpengeluaran-tipe').val() == "Pelunasan Log"){
		var total_pembayaran = total_mlg + biaya_tambahan - total_potongan;
	}
	
//	if(loadaftersave && loadaftersave.total_dpp != 0){
	if(loadaftersave){
		total_dpp = loadaftersave.total_dpp;
		total_dp = loadaftersave.total_dp;
		total_sisa = loadaftersave.total_sisa;
		total_ppn = loadaftersave.total_ppn;
		total_pph = loadaftersave.total_pph;
		total_pbbkb = loadaftersave.total_pbbkb;
		biaya_tambahan = loadaftersave.biaya_tambahan;
		total_potongan = loadaftersave.total_potongan;
		total_pembayaran = loadaftersave.total_pembayaran;
	}
//	console.log(total_ppn); 
//	console.log(total_pph); 
//	console.log(total_potongan); 
//    return false;
	$('input[name*="[total_dpp]"]').val(formatNumberForUser(total_dpp));
	$('input[name*="[total_dp]"]').val(formatNumberForUser(total_dp));
	$('input[name*="[total_sisa]"]').val(formatNumberForUser(total_sisa));
	$('input[name*="[total_ppn]"]').val(formatNumberForUser(total_ppn));
	$('input[name*="[total_pph]"]').val(formatNumberForUser(total_pph));
	$('input[name*="[total_pbbkb]"]').val(formatNumberForUser(total_pbbkb));
	$('input[name*="[biaya_tambahan]"]').val(formatNumberForUser(biaya_tambahan));
	$('input[name*="[total_potongan]"]').val(formatNumberForUser(total_potongan));
	$('input[name*="[total_pembayaran]"]').val(formatNumberForUser(total_pembayaran));
	$('#tvoucherpengeluaran-totaldebit').val(formatNumberForUser(total_pembayaran));
}
function resetTotalPembayaran(){
	$('input[name*="[total_dpp]"]').val(0);
	$('input[name*="[total_dp]"]').val(0);
	$('input[name*="[total_sisa]"]').val(0);
	$('input[name*="[total_ppn]"]').val(0);
	$('input[name*="[total_pph]"]').val(0);
	$('input[name*="[total_pbbkb]"]').val(0);
	$('input[name*="[biaya_tambahan]"]').val(0);
	$('input[name*="[total_potongan]"]').val(0);
	$('input[name*="[total_pembayaran]"]').val(0);
	
	$('input[name*="[total_dpp]"]').attr('disabled','disabled');
	$('input[name*="[total_dp]"]').attr('disabled','disabled');
	$('input[name*="[total_sisa]"]').attr('disabled','disabled');
	$('input[name*="[total_ppn]"]').attr('disabled','disabled');
	$('input[name*="[total_pph]"]').attr('disabled','disabled');
	$('input[name*="[total_pbbkb]"]').attr('disabled','disabled');
	$('input[name*="[biaya_tambahan]"]').attr('disabled','disabled');
	$('input[name*="[total_potongan]"]').attr('disabled','disabled');
	$('input[name*="[total_pembayaran]"]').attr('disabled','disabled');
	
	$('input[name*="[total_dp]"]').removeAttr('disabled');
	$('input[name*="[total_ppn]"]').removeAttr('disabled');
	$('input[name*="[total_pph]"]').removeAttr('disabled');
	<?php if(!isset($_GET['voucher_pengeluaran_id'])){ ?>
		$('input[name*="[biaya_tambahan]"]').removeAttr('disabled');
		$('input[name*="[total_potongan]"]').removeAttr('disabled');
	<?php } ?>
	
	
	$('#tvoucherpengeluaran-totaldebit').val(0);
}

function hapusItem(ele){
	$(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
		setTotal();
        reordertable('#table-detail');
    });
}

function setTipe(){
	var tipe = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tipe') ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/voucher/setTipe']); ?>',
        type   : 'POST',
        data   : {tipe:tipe},
        success: function (data) {
			if(data){
				$('select[name*="[nomor_terkait]"]').html(data.html);
				$("#<?= yii\bootstrap\Html::getInputId($model, 'totaldebit') ?>").val(0);
			}else{
				$('select[name*="[nomor_terkait]"]').html('');
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setBerkas(){
	var no_berkas = $('#<?= \yii\bootstrap\Html::getInputId($model, 'nomor_terkait') ?>').val();
	var tipe = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tipe') ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/voucher/setBerkas']); ?>',
        type   : 'POST',
        data   : {no_berkas:no_berkas,tipe:tipe},
        success: function (data) {
			if(data.totaldebit){
				$("#<?= yii\bootstrap\Html::getInputId($model, 'totaldebit') ?>").val(formatInteger(data.totaldebit));
			}else{
				$("#<?= yii\bootstrap\Html::getInputId($model, 'totaldebit') ?>").val(0);
			}
			if(data.deskripsi){
				$("#<?= yii\bootstrap\Html::getInputId($model, 'deskripsi') ?>").val(data.deskripsi);
			}else{
				$("#<?= yii\bootstrap\Html::getInputId($model, 'deskripsi') ?>").val("");
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
        }
        if(validatingDetail()){
			if(validNominal()){
	            submitform($form);
			}
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
	var cara_bayar = $("#<?= \yii\helpers\Html::getInputId($model, "cara_bayar") ?>").val();
	var cara_bayar_reff = $("#<?= \yii\helpers\Html::getInputId($model, "cara_bayar_reff") ?>").val();
    $('#table-detail > tbody > tr').each(function(){
        var field2 = $(this).find('textarea[name*="[keterangan]"]');
        var field3 = $(this).find('input[name*="[jumlah]"]');
        if(!field2.val()){
            $(this).find('textarea[name*="[keterangan]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('textarea[name*="[keterangan]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field3.val()){
            $(this).find('input[name*="[jumlah]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[jumlah]"]').parents('td').removeClass('error-tb-detail');
        }
        if(cara_bayar=="Cek" && !cara_bayar_reff ){
            $("#<?= \yii\helpers\Html::getInputId($model, "cara_bayar_reff") ?>").addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
			$("#<?= \yii\helpers\Html::getInputId($model, "cara_bayar_reff") ?>").removeClass('error-tb-detail');
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function validNominal(){
	var totaldebit = $('#<?= yii\bootstrap\Html::getInputId($model, 'totaldebit') ?>').val();
	var totalkredit = $('#<?= yii\bootstrap\Html::getInputId($model, 'totalkredit') ?>').val();
	if(totaldebit && totalkredit){
		totaldebit = unformatNumber(totaldebit);
		totalkredit = unformatNumber(totalkredit);
		if(totaldebit == totalkredit){
			return true;
		}else{
			cisAlert("Nominal Debt dan Credit harus sama!");
			return false;
		}
	}else{
		return false;
	}
}

function afterSaveThis(){
	setTimeout(function(){
		$('#btn-add-item').attr('style','display:none');
		$('form').find('input').each(function(){ $(this).prop("disabled", true); });
		$('form').find('select').each(function(){ $(this).prop("disabled", true); });
		$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
		$('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
		$('#btn-save').attr('disabled','');
		$('#btn-print').removeAttr('disabled');
	},500);
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucher/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function printBbk(id){
	window.open("<?= yii\helpers\Url::toRoute('/finance/voucher/printBbk') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function printout(caraPrint,tgl){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/rekapkaskecil/PrintoutLaporan') ?>?tgl="+tgl+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function setCarabayarReff(){
	var cara_bayar = $('#<?= \yii\bootstrap\Html::getInputId($model, 'cara_bayar') ?>').val();
	if(cara_bayar == 'Klik-BCA'){
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'cara_bayar_reff') ?>').parents('.input-group-btn').css('visibility', 'hidden');
	}else if(cara_bayar == 'Bilyet Giro'){
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'cara_bayar_reff') ?>').parents('.input-group-btn').css('visibility','visible');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'cara_bayar_reff') ?>').attr('placeholder','Input No. Giro');
	}else if(cara_bayar == 'Cek'){
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'cara_bayar_reff') ?>').parents('.input-group-btn').css('visibility','visible');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'cara_bayar_reff') ?>').attr('placeholder','Input No. Cek');
	}
}

function changeStatus(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/finance/voucher/changeStatus','id'=>'']); ?>'+id;
	$(".modals-place-confirm").load(url, function() {
		$("#modal-transaksi").modal('show');
		$("#modal-transaksi").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}

function editVoucher(voucher_pengeluaran_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/finance/voucher/index','edit'=>true,'voucher_pengeluaran_id'=>'']); ?>'+voucher_pengeluaran_id);
}
function editPage(id){
    setDetailReff();
	editItem(id);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tipe') ?>').attr('disabled','disabled');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').attr('disabled','disabled');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_nm') ?>').attr('disabled','disabled');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_bayar') ?>').attr('disabled','disabled');
	$('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
}

function cancelVoucher(voucher_pengeluaran_id){
	openModal('<?php echo \yii\helpers\Url::toRoute(['/finance/voucher/cancelVoucher']) ?>?id='+voucher_pengeluaran_id,'modal-transaksi');
}

function gkk(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/detailGkk']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-gkk").modal('show');
		$("#modal-gkk").on('hidden.bs.modal', function () { });
		$("#modal-gkk .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}
function ppk(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/ppk/detailppk']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-ppk").modal('show');
		$("#modal-ppk").on('hidden.bs.modal', function () { });
		$("#modal-ppk .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}
function ajuanDinas(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailAjuanDinas']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-ajuandinas").modal('show');
		$("#modal-ajuandinas").on('hidden.bs.modal', function () { });
		$("#modal-ajuandinas .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}
function ajuanMakan(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailAjuanMakan']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-ajuanmakan").modal('show');
		$("#modal-ajuanmakan").on('hidden.bs.modal', function () { });
		$("#modal-ajuanmakan .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}

function infoKontrak(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuandplog/infoKontrak']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info").modal('show');
		$("#modal-info").on('hidden.bs.modal', function () { });
		$("#modal-info .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}

function setAutoItems(){
    var terima_bhp_ids = [];
    $("#table-detail > tbody").html("");
    $("#table-detail-terima > tbody > tr").each(function(i){
        terima_bhp_ids[i] = $(this).find('input[name*="[terima_bhp_id]"]').val();
    });
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/voucher/setAutoItems']); ?>',
        type   : 'POST',
        data   : {terima_bhp_ids:terima_bhp_ids},
        success: function (data) {
			if(data.items){
                $("#table-detail > tbody").html(data.items);
                reordertable('#table-detail');
            }
            setTotal();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function cariOpenVoucher(){
    openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucher/cariOpenVoucher']) ?>','modal-open-voucher','90%');
}
function setOpenVoucher(open_voucher_id){
    <?php if(!isset($_GET['voucher_pengeluaran_id'])){ ?>
        $("#modal-open-voucher").modal('hide');
        $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val("Open Voucher").trigger('change');
        $("#ovk-place").addClass("animation-loading"); 
        setTimeout(function(){  
            $("#<?= yii\helpers\Html::getInputId($model, "open_voucher_id") ?>").val(open_voucher_id).trigger('change');
            $("#ovk-place").removeClass("animation-loading"); 
        },1500);
    <?php }else{ ?>
        window.location.replace("<?= \yii\helpers\Url::toRoute(['/finance/voucher/index','setOpenVoucher'=>'']) ?>"+open_voucher_id);
    <?php } ?>
}
function infoVoucher(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/finance/voucher/detailBbk']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-bbk").modal('show');
		$("#modal-bbk").on('hidden.bs.modal', function () { });
		$("#modal-bbk .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}
function detailPoByKode(reff_no){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/detailPoByKode','kode'=>'']) ?>'+reff_no,'modal-detailpo','22cm');
}
function riwayatSaldoSuplierSengon(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/saldosuplierlog/riwayatSaldo','id'=>'']) ?>'+id,'modal-riwayatsaldo','80%');
}
</script>