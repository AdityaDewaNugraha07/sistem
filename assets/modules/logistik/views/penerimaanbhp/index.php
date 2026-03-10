<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan Barang';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Penerimaan Bahan Pembantu (BHP)'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-terima-bhp',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarTerimaBhp()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Penerimaan'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Penerimaan'); ?></h4></span>
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
										if(!isset($_GET['terima_bhp_id'])){
											echo $form->field($model, 'terimabhp_kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Penerimaan'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'terimabhp_kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->terimabhp_kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'tglterima',[
											'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker" data-date-start-date="-2d" data-date-end-date="+0d">{input} <span class="input-group-btn">
														 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
														 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'pegawaipenerima')->dropDownList(\app\models\MPegawai::getOptionList(),['class'=>'form-control select2','prompt'=>'']); ?>
										<?php if( (isset($_GET['terima_bhp_id'])) && ($model->cancel_transaksi_id == NULL) ){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<a href="javascript:void(0);" onclick="cancelTerima(<?= $model->terima_bhp_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Penerimaan'); ?></a>
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
                                        <?= $form->field($model, 'nofaktur',['template'=>'{label}<div class="col-md-7">
                                                                                    <span class="input-group-btn">{input}</span> 
                                                                                    <span class="input-group-btn">'.
                                                                                        \yii\bootstrap\Html::activeTextInput($model, 'no_fakturpajak', ['class'=>'form-control','placeholder'=>'No. Faktur']).'</span> {error}</div>'])
                                                                                    ->textInput(['placeholder'=>'No. Invoice',''])->label("No. Invoice / No. Faktur"); ?>
										<?php if(!isset($_GET['terima_bhp_id'])){ ?>
											<?= $form->field($model, 'suplier_id')->dropDownList([\app\models\MSuplier::getOptionListPo('BHP')],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDropdownPO(); setDropdownSPL()']); ?>
										<?php }else{ ?>
											<?= $form->field($model, 'suplier_id')->dropDownList([\app\models\MSuplier::getOptionListPo('BHP')],['class'=>'form-control select2','prompt'=>'']); ?>
										<?php } ?>
										<?= $form->field($model, 'terimabhp_keterangan')->textarea(['placeholder'=>'Keterangan penerimaan barang']); ?>
                                    </div>
                                </div>
                                <br><br><hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Penerimaan'); ?></h4>
                                    </div>
                                    <div class="col-md-7">
										<?php if(!isset($_GET['terima_bhp_id'])){ ?>
											<label class="col-md-4" style="margin-top:5px; text-align: right;"><?= Yii::t('app', 'Load Penerimaan :'); ?></label>
											<div class="col-md-8">
                                                <span class="input-group-btn" >
													<?= yii\bootstrap\Html::activeDropDownList($model, 'spo_id', [],['prompt'=>'-- Pilih PO --','class'=>'form-control','style'=>'margin-top:10px; width: 180px','onchange'=>'getItemDariSPO()']) ?>
												</span>
												<span class="input-group-btn" >
													<?= yii\bootstrap\Html::activeDropDownList($model, 'spl_id', [],['prompt'=>'-- Pilih SPL --','class'=>'form-control','style'=>'margin-top:10px; width: 180px','onchange'=>'getItemDariSPL()']) ?>
												</span>
												<span style="font-size: 1.03rem;" class="font-red-mint" id="place-tbpexist"></span>
                                            </div>
										<?php }else{
											echo \yii\bootstrap\Html::activeHiddenInput($model, 'spo_id');
											echo \yii\bootstrap\Html::activeHiddenInput($model, 'spl_id');
										} ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="pull-right" style="text-align: right;">
											<?php
											if(isset($_GET['terima_bhp_id'])){
												if(!empty($model->spo_id)){ 
													echo "Kode PO : <b><a onclick='infoSPO(".$model->spo_id.")'>".$model->spo->spo_kode."</a></b><br>";
													echo '<span class="spo-info-place"></span>';
												}
												if(!empty($model->spl_id)){
													echo "Kode SPL : <b><a onclick='infoSPL(".$model->spl_id.")'>".$model->spl->spl_kode."</a></b><br>";
													echo '<span class="spl-info-place"></span>';
												}
											}
											?>
										</div>
										<?php if(isset($_GET['terima_bhp_id'])){ 
											if(empty($model->voucher_pengeluaran_id)){
										?>
										<span class="btn-group-updateharga">
											<a class="btn yellow btn-sm btn-outline" onclick="editHarga()" id="btn-edit-hargarealisasi"><i class="fa fa-edit"></i> <?= Yii::t('app', 'Update Harga'); ?></a>
											<a class="btn hijau btn-sm btn-outline" onclick="saveEditHarga()" id="btn-save-hargarealisasi" style="display: none;"><i class="fa fa-checklist"></i> <?= Yii::t('app', 'Update'); ?></a>
											<a class="btn red btn-sm btn-outline" onclick="cancelEditHarga()" id="btn-cancel-hargarealisasi" style="display: none;"><i class="fa fa-cancel"></i> <?= Yii::t('app', 'Cancel'); ?></a>
										</span>
											<?php }else{
												$modVoucher = app\models\TVoucherPengeluaran::findOne($model->voucher_pengeluaran_id);
												echo "<b>".$modVoucher->status_bayar."</b><br><span style='font-size:1.1rem'>".\app\components\DeltaFormatter::formatDateTimeForUser2($modVoucher->tanggal_bayar)."</span>";
											} ?>
										<?php } ?>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
                                                        <th style="vertical-align: middle; text-align: center; width: 250px;" ><?= Yii::t('app', 'Nama Item'); ?></th>
														<th class="header-spo" style="width: 60px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Qty PO'); ?></th>
														<th class="header-spl" style="width: 60px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Qty SPL'); ?></th>
                                                        <th style="width: 100px; text-align: center;  vertical-align: middle;"><?= Yii::t('app', 'Qty'); ?></th>
                                                        <th class="header-spo" style="width: 60px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Satuan'); ?></th>
                                                        <th class="header-spo" style="width: 180px; vertical-align: middle;"><?= Yii::t('app', 'Harga'); ?> <span class="place-mata-uang" style="font-size: 1.2rem;"></span></th>
                                                        <th class="header-spl" style="display: none; width: 80px; vertical-align: middle; font-size: 1.2rem;"><?= Yii::t('app', 'Harga<br> Estimasi'); ?> <span class="place-mata-uang" style="font-size: 1.2rem;"></span></th>
                                                        <th class="header-spl"  style="display: none; width: 80px; vertical-align: middle; font-size: 1.2rem;"><?= Yii::t('app', 'Harga<br> Realisasi'); ?> <span class="place-mata-uang" style="font-size: 1.2rem;"></span></th>
                                                        <th style="width: 160px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Sub Total'); ?> <span class="place-mata-uang" style="font-size: 1.2rem;"></span></th>
														<th class="header-spl"  style="display: none; width: 120px; vertical-align: middle;"><?= Yii::t('app', 'Ppn'); ?> <span class="place-mata-uang" style="font-size: 1.2rem;"></span></th>
														<th class="" style="width: 120px; vertical-align: middle;"><?= Yii::t('app', 'Pph'); ?> <span class="place-mata-uang" style="font-size: 1.2rem;"></span></th>
														<th class="header-spl"  style="display: none; width: 130px; vertical-align: middle; font-size: 1.2rem;"><?= Yii::t('app', 'Suplier'); ?></th>
                                                        <th class="" style="vertical-align: middle; text-align: center;"><?php echo Yii::t('app', 'Keterangan'); ?></th>
                                                        <th style="width: 20px; vertical-align: middle; text-align: center;"><?= Yii::t('app', ''); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
												<tfoot>
													<?php $hide = ''; if(Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_LOGISTIC){ $hide = 'none'; } ?>
													<tr>
														<td colspan="7" style="text-align: right; font-weight: 500;  vertical-align: middle;">&nbsp;<span> Total </span></td>
														<td style="padding-left: 8px; padding-right: 8px; padding: 3px" colspan="2">
															<?= yii\bootstrap\Html::textInput('total',0,['class'=>'form-control float','style'=>'width:100%; font-style: bold; padding:3px;display:'.$hide,'disabled'=>'disabled','id'=>'total']); ?>
														</td>
														<td colspan="2"></td>
													</tr>
<!--													<tr>
														<td colspan="7" style="text-align: right; font-weight: 500; vertical-align: middle;">&nbsp; <?= Yii::t('app', 'Potongan Harga (Rp)'); ?></td>
														<td style="padding-left: 8px; padding-right: 8px; padding: 3px 8px;">
															<?php // echo yii\bootstrap\Html::activeTextInput($model,'potonganharga',['value'=>0,'class'=>'form-control money-format','style'=>'width:100%; font-style: bold;','onblur'=>'setTotal()']); ?>
														</td>
														<td colspan="2"></td>
													</tr>-->
													<tr>
														<td colspan="7" style="text-align: right; font-weight: 500; vertical-align: middle;"><span class="span-include-ppn"></span> <?= Yii::t('app', 'Ppn'); ?></td>
														<td style="padding: 3px;" colspan="2">
															<?php 
																$is_pkp = false;
																if(!empty($model->spo_id)){
																	$is_pkp = ($model->spo->spo_is_pkp)?true:false;
																}
															?>
															<?= yii\bootstrap\Html::hiddenInput('is_pkp',$is_pkp,['id'=>'is_pkp']); ?>
															<?= yii\bootstrap\Html::activeHiddenInput($model, 'is_ppn'); ?>
															<?= yii\bootstrap\Html::activeTextInput($model,'ppn_nominal',['value'=>0,'class'=>'form-control float','style'=>'width:100%; font-style: bold; padding:3px;display:'.$hide,'onblur'=>'setTotal(true)']); ?>
														</td>
														<td colspan="2"></td>
													</tr>
													<tr>
														<td colspan="7" style="text-align: right; font-weight: 500; vertical-align: middle;"> <?= Yii::t('app', 'Pph'); ?> <span id="pphtotalpersen"></span></td>
														<td style="padding: 3px;" colspan="2">
															<?= yii\bootstrap\Html::activeTextInput($model,'totalpph',['value'=>0,'class'=>'form-control float','style'=>'width:100%; font-style: bold; padding:3px;display:'.$hide,'disabled'=>TRUE]); ?>
														</td>
														<td colspan="2"></td>
													</tr>
													<tr>
														<td colspan="7" style="text-align: right; font-weight: 500; vertical-align: middle;"> <?= Yii::t('app', 'PBBKB'); ?> <span id="pphtotalpersen"></span></td>
														<td style="padding: 3px;" colspan="2">
															<?= yii\bootstrap\Html::activeTextInput($model,'total_pbbkb',['value'=>0,'class'=>'form-control float','style'=>'width:100%; font-style: bold; padding:3px;display:'.$hide,'disabled'=>TRUE,'onblur'=>'setTotal()']); ?>
														</td>
														<td colspan="2"></td>
													</tr>
													<tr>
														<td colspan="7" style="text-align: right; font-weight: 500; vertical-align: top; font-size: 1.1rem;"> 
                                                            <?= Yii::t('app', 'Biaya Tambahan'); ?>
                                                            <?= yii\bootstrap\Html::activeTextInput($model,'label_biayatambahan',['class'=>'form-control pull-right','placeholder'=>'Contoh: Materai, dll.','style'=>'text-align:right; height: 22px; width:50%; font-style: bold; padding:3px;display:'.$hide,'disabled'=>TRUE]); ?>
                                                        <span id="pphtotalpersen"></span></td>
														<td style="padding: 3px;" colspan="2">
															<?= yii\bootstrap\Html::activeTextInput($model,'total_biayatambahan',['value'=>0,'class'=>'form-control float','style'=>'width:100%; font-style: bold; padding:3px;display:'.$hide,'disabled'=>TRUE,'onblur'=>'setTotal()']); ?>
														</td>
														<td colspan="2"></td>
													</tr>
													<tr>
														<td colspan="7" style="text-align: right; vertical-align: middle;">&nbsp;<span> <?= Yii::t('app', 'Total Bayar'); ?> </span></td>
														<td style="padding: 3px;" colspan="2">
															<?= yii\bootstrap\Html::activeTextInput($model,'totalbayar',['value'=>0,'class'=>'form-control float','style'=>'width:100%; font-style: bold; padding:3px;display:'.$hide,'disabled'=>'disabled']); ?>
														</td>
														<td colspan="2"></td>
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
								<?php 
									if(isset($_GET['terima_bhp_id'])){
										$disabled = FALSE;
									}else{
										$disabled = TRUE;
									}
								?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>(($disabled==FALSE)? 'printout('.(isset($_GET['terima_bhp_id'])?$_GET['terima_bhp_id']:'').')' :''),'disabled'=>'$disabled']); ?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print Rincian'),['id'=>'btn-print-rincian','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>(($disabled==FALSE)? 'printRincian('.(isset($_GET['terima_bhp_id'])?$_GET['terima_bhp_id']:'').')' :''),'disabled'=>$disabled]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.popover {
    width: 2000px;
    max-width:60%
}
.popover-table th, td {
    padding: 0px 15px;
    white-space:nowrap;
}
</style>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['terima_bhp_id'])){
    $pagemode = "afterSave()";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode;
	$('select[name*=\"[pegawaipenerima]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik nama Pegawai',
	});
	$('select[name*=\"[spo_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik kode PO',
	});
	$('select[name*=\"[spl_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik kode SPL',
	});
	$('select[name*=\"[spo_id]\"]').siblings('span').click(function(){
		$('#". yii\bootstrap\Html::getInputId($model, 'spl_id') ."').val('').trigger('change'); 
	});
	$('select[name*=\"[spl_id]\"]').siblings('span').click(function(){
		$('#". yii\bootstrap\Html::getInputId($model, 'spo_id') ."').val('').trigger('change'); 
	});
	$('select[name*=\"[suplier_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Supplier',
		ajax: {
			url: '". \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/findSupplier') ."',
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
	setDropdownSPL();
    $('#". yii\bootstrap\Html::getInputId($model, 'no_fakturpajak') ."').inputmask({'mask': '999.999-99.99999999'});
", yii\web\View::POS_READY); ?>
<script>
function editHarga(){
	$('#btn-edit-hargarealisasi').attr('style','display:none');
	$('#btn-save-hargarealisasi').attr('style','display:');
	$('#btn-cancel-hargarealisasi').attr('style','display:');
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').removeAttr('disabled');
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'nofaktur') ?>').removeAttr('disabled');
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'no_fakturpajak') ?>').removeAttr('disabled');
	<?php if(!empty($model->spo_id)){ ?>
		getItemDariSPO();
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>').removeAttr('disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'total_pbbkb') ?>').removeAttr('disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'total_biayatambahan') ?>').removeAttr('disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'label_biayatambahan') ?>').removeAttr('disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'totalbayar') ?>').removeAttr('disabled').attr('readonly','readonly');
	<?php }else{ ?>
        $('#<?= \yii\bootstrap\Html::getInputId($model, 'total_biayatambahan') ?>').removeAttr('disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'label_biayatambahan') ?>').removeAttr('disabled');
	$('#table-detail > tbody > tr').each(function(){
		$(this).find('input[name*="terimabhpd_harga"]').removeAttr('disabled');
		$(this).find('input[name*="is_ppn_peritem"]').removeAttr('disabled');
		$(this).find('input[name*="is_pph_peritem"]').removeAttr('disabled');
		$(this).find('select[name*="nofaktur"]').removeAttr('disabled');
		$(this).find('select[name*="no_fakturpajak"]').removeAttr('disabled');
		$(this).find('select[name*="suplier_id"]').removeAttr('disabled');
		$(this).find('select[name*=\"[suplier_id]\"]').select2({
			allowClear: !0,
			placeholder: 'Pilih Supplier',
			ajax: {
				url: '<?= \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/findSupplier') ?>',
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
		$(this).find('.select2-selection').css('font-size','1.1rem');
		$(this).find('textarea[name*="terimabhpd_keterangan"]').removeAttr('disabled');
	});
	<?php } ?>
}
function cancelEditHarga(){
	var terima_bhp_id = '<?= (isset($_GET['terima_bhp_id'])?$_GET['terima_bhp_id']:'') ?>';
	getItemsByTerimaBhp(terima_bhp_id);
	setTimeout(function(){
		$('#btn-edit-hargarealisasi').attr('style','display:');
		$('#btn-save-hargarealisasi').attr('style','display:none');
		$('#btn-cancel-hargarealisasi').attr('style','display:none');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').attr('disabled','disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'nofaktur') ?>').attr('disabled','disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'no_fakturpajak') ?>').attr('disabled','disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>').attr('disabled','disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'total_pbbkb') ?>').attr('disabled','disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'total_biayatambahan') ?>').attr('disabled','disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'label_biayatambahan') ?>').attr('disabled','disabled');
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'totalbayar') ?>').attr('disabled','disabled').removeAttr('readonly');
		$('#table-detail > tbody > tr').each(function(){
			$(this).find('input[name*="terimabhpd_harga"]').attr('disabled','disabled');
			$(this).find('textarea[name*="terimabhpd_keterangan"]').attr('disabled');
			$(this).find('input[name*="is_ppn_peritem"]').attr('disabled','disabled');
			$(this).find('input[name*="is_pph_peritem"]').attr('disabled','disabled');
			$(this).find('input[name*="[ppn_peritem]"]').attr('disabled','disabled');
			$(this).find('input[name*="[pph_peritem]"]').attr('disabled','disabled');
			$(this).find('select[name*="suplier_id"]').attr('disabled','disabled');
			$(this).find('input[name*="nofaktur"]').attr('disabled','disabled');
			$(this).find('input[name*="no_fakturpajak"]').attr('disabled','disabled');
			$(this).find('input[name*="terimabhpd_qty"]').attr('disabled','disabled');
			$(this).find('textarea[name*="terimabhpd_keterangan"]').attr('disabled','disabled');
			$(this).find('#btn-cancel-item').remove();
		});
	},300)
	
}
function saveEditHarga(){
	$('#btn-edit-hargarealisasi').attr('style','display:');
	$('#btn-save-hargarealisasi').attr('style','display:none');
	$('#btn-cancel-hargarealisasi').attr('style','display:none');
	$('.btn-group-updateharga').addClass('animation-loading');
	$('#table-detail > tbody > tr').each(function(){
		var unformatted_harga = unformatNumber($(this).find('input[name*="terimabhpd_harga"]').val());
		var unformatted_ppn = unformatNumber($(this).find('input[name*="[ppn_peritem]"]').val());
		var unformatted_pph = unformatNumber($(this).find('input[name*="[pph_peritem]"]').val());
		$(this).find('input[name*="terimabhpd_harga"]').val(unformatted_harga);
		$(this).find('input[name*="[ppn_peritem]"]').val(unformatted_ppn);
		$(this).find('input[name*="[ppn_peritem]"]').removeAttr('disabled','disabled');
		$(this).find('input[name*="[pph_peritem]"]').val(unformatted_pph);
		$(this).find('input[name*="[pph_peritem]"]').removeAttr('disabled','disabled');
	});
	var terima_bhp_id = '<?= (isset($_GET['terima_bhp_id'])?$_GET['terima_bhp_id']:'') ?>';
	var totalbayar = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'totalbayar') ?>').val());
	var total_pbbkb = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'total_pbbkb') ?>').val());
	var total_biayatambahan = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'total_biayatambahan') ?>').val());
	var label_biayatambahan = $('#<?= \yii\bootstrap\Html::getInputId($model, 'label_biayatambahan') ?>').val();
	var ppn_nominal = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>').val());
	var pph_nominal = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($model, 'pph_nominal') ?>').val());
	var suplier_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
	var nofaktur = $('#<?= \yii\bootstrap\Html::getInputId($model, 'nofaktur') ?>').val();
	var no_fakturpajak = $('#<?= \yii\bootstrap\Html::getInputId($model, 'no_fakturpajak') ?>').val();
	<?php if($model->spl_id){ ?>
		$.ajax({
			url    : '<?php echo \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/updateHargaRealisasi']); ?>',
			type   : 'POST',
			data   : {terima_bhp_id:terima_bhp_id,formdata:$('form').serialize(),totalbayar:totalbayar,total_pbbkb:total_pbbkb,total_biayatambahan:total_biayatambahan,
                      label_biayatambahan:label_biayatambahan, suplier_id:suplier_id,ppn_nominal:ppn_nominal,pph_nominal:pph_nominal,nofaktur:nofaktur,no_fakturpajak:no_fakturpajak},
			success: function (data) {
				if(data.message){
					cisAlert(data.message);
					setTotal();
					cancelEditHarga();
					$('.btn-group-updateharga').removeClass('animation-loading');
				}
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
	<?php }else{ ?>
		$.ajax({
			url    : '<?php echo \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/updateHargaRealisasi']); ?>',
			type   : 'POST',
			data   : {terima_bhp_id:terima_bhp_id,suplier_id:suplier_id,formdata:$('form').serialize(),totalbayar:totalbayar,total_pbbkb:total_pbbkb,
                      total_biayatambahan:total_biayatambahan,label_biayatambahan:label_biayatambahan,ppn_nominal:ppn_nominal,nofaktur:nofaktur,no_fakturpajak:no_fakturpajak},
			success: function (data) {
				if(data.message){
					cisAlert(data.message);	
					cancelEditHarga();
					$('.btn-group-updateharga').removeClass('animation-loading');
				}
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
	<?php } ?>
}

function setDropdownPO(){
	$('#table-detail tbody').html("");
	checkTBP(null,null,null);
	setTotal();
	$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').parents('.input-group-btn').addClass('animation-loading');
	var suplier_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/setDropdownPO']); ?>',
		type   : 'POST',
		data   : {suplier_id:suplier_id},
		success: function (data) {
			if(data){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').html(data.html);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').parents('.input-group-btn').removeClass('animation-loading');
				
				$('#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').on('select2:open', function(e){ 
					setTimeout(function(){
						$('#select2-tterimabhp-spo_id-results').find('li').each(function(){
							var li = $(this);
							var asd = $(this).attr('id');
							var fr_el = asd.split('-')[5];
							$(data.spo).each(function(){
								var fr_db = $(this)[0].spo_id;
								if(fr_el == fr_db){
									$(li).attr('style','color:red');
								}
							});
						});
					},500);
				});
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownSPL(){
	$('#table-detail tbody').html("");
	checkTBP(null,null,null);
	setTotal();
	$('#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').parents('.input-group-btn').addClass('animation-loading');
	var suplier_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/setDropdownSPL']); ?>',
		type   : 'POST',
		data   : {suplier_id:suplier_id},
		success: function (data) {
			if(data){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').html(data.html);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').parents('.input-group-btn').removeClass('animation-loading');
				if(data.spl){
					$('#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').on('select2:open', function(e){ 
						setTimeout(function(){
							$('#select2-tterimabhp-spl_id-results').find('li').each(function(){
								var li = $(this);
								var asd = $(this).attr('id');
								var fr_el = asd.split('-')[5];
								$(data.spl).each(function(){
									var fr_db = $(this)[0].spl_id;
									if(fr_el == fr_db){
										$(li).attr('style','color:red');
									}
								});
							});
						},500);
					});
				}
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getItemDariSPO(){
	$('#table-detail').addClass('animation-loading');
	$('.header-spl').hide(); $('.header-spo').show();
	$('#table-detail tfoot tr').each(function(){
		$(this).find('td:first').attr('colspan','6');
	});
	$('.span-include-ppn').html("");
	var spo_id = $("#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>").val();
	var terima_bhp_id = '<?= isset($_GET['terima_bhp_id'])?$_GET['terima_bhp_id']:'' ?>';
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/getItemDariSPO']); ?>',
		type   : 'POST',
		data   : {spo_id:spo_id,terima_bhp_id:terima_bhp_id},
		success: function (data) {
			if(data){
				$('#table-detail tbody').html(data.html);
				$("#<?= yii\bootstrap\Html::getInputId($model, 'potonganharga') ?>").val(0);
				checkTBP(null,null,null);
				if(data.modSpo){
					if(data.modSpo.spo_is_pkp === true){
						$('#is_pkp').val(1);
					}else{
						$('#is_pkp').val(0);
					}
					if(data.modSpo.spo_is_ppn === true){
//						$('.span-include-ppn').html("Include");
						$('#<?= \yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>').val(1);
					}else{
//						$('.span-include-ppn').html("Exclude");
						$('#<?= \yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>').val(0);
					}
					$("#<?= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>").val(formatInteger(data.modSpo.spo_ppn_nominal));
					
					if(data.tbp){
						checkTBP(data.modSpo.spo_kode,data.tbp.terimabhp_kode,data.modSpo.terima_bhp_id);
					}
					$('.place-mata-uang').html("("+data.modSpo.name_en+")");
				}else{
					$("#<?= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>").val(0);
				}
				setTotal();
//				if(data.modSpo){
//					if(data.modSpo.suplier_id){
//						$("#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>").val(data.modSpo.suplier_id).trigger('change');
//					}else{
//						$("#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>").val(null).trigger('change');
//					}
//				}else{
//					$("#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>").val(null).trigger('change');
//				}
				$('#table-detail').removeClass('animation-loading');
				reordertable('#table-detail');

			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
	return false;
}

function getItemDariSPL(){
	$('#table-detail').addClass('animation-loading');
	$('.header-spl').show(); $('.header-spo').hide();
	$('#table-detail tfoot tr').each(function(){
		$(this).find('td:first').attr('colspan','6')
	});
	$('.span-include-ppn').html(""); $('#<?= \yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>').val(0);
	var spl_id = $("#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>").val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/getItemDariSPL']); ?>',
		type   : 'POST',
		data   : {spl_id:spl_id},
		success: function (data) {
			checkTBP(null,null,null);
			if(data){
				$('#table-detail tbody').html(data.html);
				$('#table-detail > tbody > tr').each(function(){
					$(this).find('select[name*=\"[suplier_id]\"]').select2({
						allowClear: !0,
						placeholder: 'Pilih Supplier',
						ajax: {
							url: '<?= \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/findSupplier') ?>',
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
					$(this).find('.select2-selection').css('font-size','1.1rem');
				});
				setTotal();
				$("#<?= yii\bootstrap\Html::getInputId($model, 'potonganharga') ?>").val(0);
				formatNumberAll();
				$('#table-detail').removeClass('animation-loading');
				reordertable('#table-detail');
//				$('.span-include-ppn').html("NON");
				if(data.tbp){
					checkTBP(data.modSpl.spl_kode,data.tbp.terimabhp_kode,data.modSpl.terima_bhp_id);
				}
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
	return false;
}

function setSubtotal(ele){
	var elestr = $(ele).attr('id');	
	$('#table-detail tbody tr').each(function(index){
		var qty = unformatNumber($(this).find('input[name*="[terimabhpd_qty]"]').val());
		var harga = unformatNumber($(this).find('input[name*="[terimabhpd_harga]"]').val());
		var subtotal = qty * harga;
		
		if(elestr.indexOf("diskon_rp") != -1){
			var diskon_rp = unformatNumber($(this).find('input[name*="[diskon_rp]"]').val())
			var diskon_persen = ((diskon_rp/qty) / harga) * 100;
		}else{
			var diskon_persen = unformatNumber($(this).find('input[name*="[terimabhpd_diskon]"]').val());
			var diskon_rp = (diskon_persen/100) * subtotal;
		}
		
		var subtotal_afterdisc = subtotal - diskon_rp;
		$(this).find('input[name*="[terimabhpd_diskon]"]').val(diskon_persen);
		$(this).find('input[name*="[diskon_rp]"]').val(formatInteger(diskon_rp));
		$(this).find('input[name*="[subtotal]"]').val(formatFloat(subtotal_afterdisc,2));
	});
	setTotal();
}

function setTotal(edit_ppn,display_mode){
	setTimeout(function(){
		var total = 0;
		var ppn = 0;
		var pph = 0;
		var pph_persen = 0.02;
		var potongan = unformatNumber($("#<?= yii\bootstrap\Html::getInputId($model, 'potonganharga') ?>").val());
		var subtotal = 0;
		var total_pbbkb = unformatNumber($("#<?= yii\bootstrap\Html::getInputId($model, 'total_pbbkb') ?>").val());
		var total_biayatambahan = unformatNumber($("#<?= yii\bootstrap\Html::getInputId($model, 'total_biayatambahan') ?>").val());
		$('#table-detail tbody tr').each(function(index){
			subtotal = unformatNumber($(this).find('input[name*="[subtotal]"]').val());
			total += subtotal;
			var terimabhpd_harga = unformatNumber($(this).find('input[name*="[terimabhpd_harga]"]').val());
			if( $('#<?= \yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').val() ){
				ppn += subtotal * 0.1;
			}else if( $('#<?= \yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').val() ){
				ppn += unformatNumber( $(this).find('input[name*="[ppn_peritem]"]').val() );
			}
				pph += unformatNumber( $(this).find('input[name*="[pph_peritem]"]').val() );
		});
//		total = Math.ceil(total);
		if(edit_ppn){
			ppn = unformatNumber( $('#<?= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>').val() );
		}
		if( $('#is_pkp').val() == '1'){
			ppn = ppn;
		}else{
			if( $('#<?= \yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').val() ){
				ppn = ppn;
			}else{
				ppn = 0;
			}
		}
		if(isNaN(ppn)){
			ppn = 0;
		}
		
		if( ('<?= isset($_GET['terima_bhp_id'])?$_GET['terima_bhp_id']:'' ?>' != '') && display_mode ){ 
			var id = '<?= (isset($_GET['terima_bhp_id'])?$_GET['terima_bhp_id']:'') ?>';
			$.ajax({
				url    : '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/getData']); ?>',
				type   : 'POST',
				data   : {id:id},
				success: function (data) {
					ppn = data.model.ppn_nominal;
					if(data.model.total_pbbkb){
						total_pbbkb = data.model.total_pbbkb;
					}
					if(data.model.total_biayatambahan){
						total_biayatambahan = data.model.total_biayatambahan;
					}
					var totalbayar = total - potongan + ppn - pph + total_pbbkb + total_biayatambahan;
					$('#<?= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>').val(formatFloat(ppn,2));
					$('#<?= yii\bootstrap\Html::getInputId($model, 'totalpph') ?>').val( formatNumberForUser(pph) );
					$('#<?= yii\bootstrap\Html::getInputId($model, 'total_pbbkb') ?>').val( formatNumberForUser(total_pbbkb) );
					$('#<?= yii\bootstrap\Html::getInputId($model, 'total_biayatambahan') ?>').val( formatNumberForUser(total_biayatambahan) );
					$('#total').val(formatNumberForUser(total));
					$('#<?= yii\bootstrap\Html::getInputId($model, 'totalbayar') ?>').val(formatNumberForUser(totalbayar));
				},
				error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
			});
		}else{
			var totalbayar = total - potongan + ppn - pph + total_pbbkb + total_biayatambahan;
			$('#<?= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>').val(formatFloat(ppn,2));
			$('#<?= yii\bootstrap\Html::getInputId($model, 'totalpph') ?>').val( formatNumberForUser(pph) );
			$('#<?= yii\bootstrap\Html::getInputId($model, 'total_pbbkb') ?>').val( formatNumberForUser(total_pbbkb) );
			$('#<?= yii\bootstrap\Html::getInputId($model, 'total_biayatambahan') ?>').val( formatNumberForUser(total_biayatambahan) );
			$('#total').val(formatNumberForUser(total));
			$('#<?= yii\bootstrap\Html::getInputId($model, 'totalbayar') ?>').val(formatNumberForUser(totalbayar));
		}
	},300);
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
		setSubtotal($(ele).parents('tr').find('input[name*="[terimabhpd_harga]"]'));
    });
}

function save(){
    var $form = $('#form-terima-bhp');
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

function validatingDetail(){
    var has_error = 0;
    $('#table-detail tbody > tr').each(function(){
        var field1 = $(this).find('input[name*="[bhp_id]"]');
        var field2 = $(this).find('input[name*="[terimabhpd_qty]"]');
		var field3 = $(this).find('input[name*="[terimabhpd_qty_old]"]');
        if(!field1.val()){
            $(this).find('input[name*="[bhp_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[bhp_id]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[terimabhpd_qty]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( $(this).find('input[name*="[terimabhpd_qty]"]').val() == 0 ){
                has_error = has_error + 1;
                $(this).find('input[name*="[terimabhpd_qty]"]').parents('td').addClass('error-tb-detail');
            }else{
				if(unformatNumber( field2.val() ) > unformatNumber( field3.val() )){
					has_error = has_error + 1;
					$(this).find('input[name*="[terimabhpd_qty]"]').parents('td').addClass('error-tb-detail');
				}else{
					$(this).find('input[name*="[terimabhpd_qty]"]').parents('td').removeClass('error-tb-detail');
				}
            }
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tterimabhp-tglterima').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
//    $('#btn-print').removeAttr('disabled');
    $('#btn-print-rincian').removeAttr('disabled');
	setTimeout(function(){
		<?php if(!empty($model->spl_id)){ ?>
			cancelEditHarga();
		<?php }else{ ?>
			getItemsByTerimaBhp(id);
		<?php } ?>
	},500);
}

function getItemsByTerimaBhp(){
    $('#table-detail').addClass('animation-loading');
    var terima_bhp_id = '<?= (isset($_GET['terima_bhp_id'])?$_GET['terima_bhp_id']:'') ?>';
    var html = "";
    if(terima_bhp_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/GetItemsByTerimaBhp']); ?>',
            type   : 'POST',
            data   : {terima_bhp_id:terima_bhp_id},
            success: function (data) {
                if(data){
                    html = data.html;
                    $('#table-detail tbody').html(html);
					if(data.terimabhp){
						if(data.terimabhp.spo_id){
//							if(data.terimabhp.is_ppn === true){
//								$('.span-include-ppn').html("Include");
//								$('#<?php // echo \yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>').val(1);
//							}else{
//								$('.span-include-ppn').html("Exclude");
//								$('#<?php // echo \yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>').val(0);
//								$("#<?php // echo yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>").val(0);
//							}
						}else{
							$("#<?= yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>").val(0);
						}
						$('.place-mata-uang').html("("+data.name_en+")");
					}else{
						$("#<?= yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>").val(0);
					}
                    $('#table-detail').removeClass('animation-loading');
					setTotal(null,true);
                    reordertable('#table-detail');
					if(data.terimabhp.spo_id){
						setPopoverInfoSpo(data.terimabhp.spo_id)
					}
					if(data.terimabhp.spl_id){
						setPopoverInfoSpl(data.terimabhp.spl_id)
					}
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        }).done(function() {
			$('#table-detail > tbody > tr').each(function(){
				$(this).find(":input:not([type=hidden])").attr('disabled','disabled');
				$(this).find('select').attr('disabled','disabled');
				$(this).find('textarea').attr('disabled','disabled');
			});
		});
    }else{
        html = "<tr><td colspan='7'><center><i>Data tidak ditemukan</i></center></td></tr>"
        $('#table-detail tbody').html(html);
        $('#table-detail').removeClass('animation-loading');
    }
}

function daftarTerimaBhp(){
    openModal('<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/daftarTerimaBhp']) ?>','modal-daftar-terimabhp','95%');
}

function setPopoverInfoSpo(spo_id){
	$('.header-spl').hide(); $('.header-spo').show();
	$('#table-detail tfoot tr').each(function(){
		$(this).find('td:first').attr('colspan','6')
	});
//    if(spo_id){
//		$('.spo-info-place').html('<i class="fa fa-info-circle popover-spo" data-ajaxload="" style="cursor: default;"> Detail PO</i> ');
//    }else{
//		$('.spo-info-place').html('');
//    }
//    $('.spo-info-place').hover(function(){
//		var e= $(this);
//		e.off('hover');
//		$.get('<?php // echo \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/infoSpo','id'=>'']); ?>'+spo_id+'',function(d){
//			e.popover({html : true,placement: 'left',content: d, title:'Detail PO'}).popover('show');
//		});
//    }, function(){
//		$('.spo-info-place').popover('hide');
//    });
}
function setPopoverInfoSpl(spl_id){
	$('.header-spl').show(); $('.header-spo').hide();
	$('#table-detail tfoot tr').each(function(){
		$(this).find('td:first').attr('colspan','6')
	});
//    if(spl_id){
//		$('.spl-info-place').html('<i class="fa fa-info-circle popover-spl" data-ajaxload="" style="cursor: default;"> Detail SPL</i> ');
//    }else{
//		$('.spl-info-place').html('');
//    }
//    $('.spl-info-place').hover(function(){
//		var e= $(this);
//		e.off('hover');
//		$.get('<?php // echo \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/infoSpl','id'=>'']); ?>'+spl_id+'',function(d){
//			e.popover({html : true,placement: 'left',content: d, title:'Detail SPL'}).popover('show');
//		});
//    }, function(){
//		$('.spl-info-place').popover('hide');
//    });
}

function setPpnPerItem(ele,value){
	ele = $(ele).parents('td').find('input[name*="is_ppn_peritem"]');
	var qty = unformatNumber( $(ele).parents('tr').find('input[name*="[terimabhpd_qty]"]').val() );
	var harga = unformatNumber( $(ele).parents('tr').find('input[name*="[terimabhpd_harga]"]').val() );
	var ppnitem = 0;
	if( $(ele).is(':checked') ){
		if(value){
			ppnitem = value;
		}else{
			ppnitem = (qty*harga)*0.1;
		}
		$(ele).parents('td').find('input[name*="[ppn_peritem]"]').removeAttr('disabled');
	}else{
		$(ele).parents('td').find('input[name*="[ppn_peritem]"]').attr('disabled','disabled');
	}
	$(ele).parents('tr').find('input[name*="[ppn_peritem]"]').val( formatNumberForUser(ppnitem) );
	setTotal();
	return false;
}
function setPphPerItem(ele,value){
	ele = $(ele).parents('td').find('input[name*="is_pph_peritem"]');
	var qty = unformatNumber( $(ele).parents('tr').find('input[name*="[terimabhpd_qty]"]').val() );
	var harga = unformatNumber( $(ele).parents('tr').find('input[name*="[terimabhpd_harga]"]').val() );
	var pphitem = 0;
	var ada_checked = false;
	var subtotal = unformatNumber( $(ele).parents('tr').find('input[name*="subtotal"]').val() );
	if( $(ele).is(':checked') ){
		// get NPWP Suplier
		<?php if( isset($_GET['terima_bhp_id']) ){ ?>
			<?php if( !empty($model->spo_id) ){ ?>
				var suplier_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
			<?php }else if( !empty($model->spl_id) ){ ?>
				var suplier_id = $(ele).parents('tr').find('select[name*="suplier_id"]').val();
			<?php } ?>
		<?php }else{ ?>
			if( $('#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').val() ){
				var suplier_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
			}else if( $('#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').val() ){
				var suplier_id = $(ele).parents('tr').find('select[name*="suplier_id"]').val();
			}
		<?php } ?>
		$.ajax({
			url    : '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/getNpwp']); ?>',
			type   : 'POST',
			data   : {suplier_id:suplier_id,subtotal:subtotal},
			success: function (data) {
				if(value){
					pphitem = value;
				}else{
					pphitem = data.total;
				}
				if(data.suplier_npwp){
					$('#npwpitemplace').html('NPWP : '+data.suplier_npwp);
				}else{
					$('#npwpitemplace').html('');
				}
				$(ele).parents('tr').find('input[name*="[pph_peritem]"]').val( formatNumberForUser(pphitem) );
				setTotal();
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
		// end get NPWP Suplier
		
		$(ele).parents('td').find('input[name*="[pph_peritem]"]').removeAttr('disabled');
	}else{
		$('#npwpitemplace').html('');
		$(ele).parents('td').find('input[name*="[pph_peritem]"]').attr('disabled','disabled');
		$(ele).parents('tr').find('input[name*="[pph_peritem]"]').val( formatNumberForUser(pphitem) );
		setTotal();
	}
	return false;
}

function cancelTerima(terima_bhp_id){
	openModal('<?php echo \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/cancelTerima']) ?>?id='+terima_bhp_id,'modal-transaksi');
}

function printout(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/tracking/printTbp') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
function printRincian(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/tracking/printTbpRincian') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function checkTBP(spo_kode,kode_tbp,tbp_id){
	if( spo_kode && kode_tbp ){
		var label = '<b>NOTE :</b> '+spo_kode+' ini sudah pernah di terima pada : <b><u><a onclick="infoTBP('+tbp_id+')">'+kode_tbp+'</a></u></b>';
	}else{
		var label = "";
	}
	$('#place-tbpexist').html(label);
}

function infoTBP(terima_bhp_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp','id'=>'']); ?>'+terima_bhp_id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-tbp").modal('show');
		$("#modal-info-tbp").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}

function infoSPO(spo_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpo']) ?>?id='+spo_id,'modal-info-spo','75%','');
}
function infoSPL(spl_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpl']) ?>?id='+spl_id,'modal-info-spl','75%','');
}

function returBHP(terima_bhpd_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/returBHP','terima_bhpd_id'=>'']); ?>'+terima_bhpd_id,'modal-transaksi');
} 

function infoReturBHP(spo_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoReturBHP']) ?>?id='+spo_id,'modal-info-spo','75%','');
}
</script>