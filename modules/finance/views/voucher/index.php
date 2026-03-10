<?php
/* @var $this yii\web\View */

use app\components\Params;
use app\models\TPengajuanDrp;
use app\models\TPengajuanDrpDetail;
use app\models\TVoucherPengeluaran;
use yii\bootstrap\Html;
use yii\helpers\Url;

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
                        <a class="btn blue btn-sm btn-outline pull-right" style="margin-left: 5px;" onclick="daftarAfterSave(<?= Yii::$app->user->identity->pegawai->departement_id ?>)"><i class="fa fa-list"></i> <?= Yii::t('app', 'Cari Voucher Pengeluaran'); ?></a>
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
														<?= Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
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
											'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime" data-date-start-date="-0d">{input} <span class="input-group-addon">
											<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
											{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?php if( (isset($_GET['voucher_pengeluaran_id']))){ ?>
												<div class="form-group">
													<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
													<div class="col-md-7" style="margin-top:7px;">
												<?php if (($model->cancel_transaksi_id == NULL)){
														if($model->status_drp == NULL){
															if($model->status_bayar == "PAID"){ ?>
																<a href="javascript:void(0);" class="btn default btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Voucher'); ?></a>
															<?php } else { ?>
																<a href="javascript:void(0);" onclick="cancelVoucher(<?= $model->voucher_pengeluaran_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Voucher'); ?></a>
															<?php }
														} else {
															$modDrpDetail = app\models\TPengajuanDrpDetail::find()->select('t_pengajuan_drp_detail.status_pengajuan')
																												->join('JOIN','t_voucher_pengeluaran', 't_pengajuan_drp_detail.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id')
																												->join('JOIN','t_pengajuan_drp', 't_pengajuan_drp.pengajuan_drp_id = t_pengajuan_drp_detail.pengajuan_drp_id')
																												->where("t_voucher_pengeluaran.voucher_pengeluaran_id=".$_GET['voucher_pengeluaran_id']." and status_approve <> 'REJECTED'")
																												->orderBy("pengajuan_drp_detail_id DESC")
																												->one();
															if(count($modDrpDetail) > 0){
																if($modDrpDetail->status_pengajuan == 'Ditunda'){ ?>
																	<a href="javascript:void(0);" onclick="cancelVoucher(<?= $model->voucher_pengeluaran_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Voucher'); ?></a>
																<?php }
															}
														}
															// $modDrpDetail = app\models\TPengajuanDrpDetail::findOne(['voucher_pengeluaran_id'=>$_GET['voucher_pengeluaran_id']]);
													} ?>
													</div>
												</div>
										<?php } else { ?>
												<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<a href="javascript:void(0);" class="btn default btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Voucher'); ?></a>
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
												echo Html::activeHiddenInput($model, 'suplier_id');
											}else{
												echo $form->field($model, 'suplier_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff(function(){ setAutoItems(); });']);
											}
											?>
										</div>
										<div id="ppk-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'ppk_kode')->textInput()->label('Kode PPK');
												echo Html::activeHiddenInput($model, 'ppk_id');
											}else{
												echo $form->field($model, 'ppk_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode PPK');
											}
											?>
										</div>
										<div id="gkk-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'gkk_kode')->textInput()->label('Kode GKK');
												echo Html::activeHiddenInput($model, 'gkk_id');
											}else{
												echo $form->field($model, 'gkk_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode GKK');
											}
											?>
										</div>
										<div id="pdg-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'pdg_kode')->textInput(['class'=>'form-control fontsize-1-1'])->label('Kode Ajuan');
												echo Html::activeHiddenInput($model, 'ajuandinas_grader_id');
											}else{
												echo $form->field($model, 'ajuandinas_grader_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode Ajuan');
											}
											?>
										</div>
										<div id="pmg-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'pmg_kode')->textInput(['class'=>'form-control fontsize-1-1'])->label('Kode Ajuan');
												echo Html::activeHiddenInput($model, 'ajuanmakan_grader_id');
											}else{
												echo $form->field($model, 'ajuanmakan_grader_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode Ajuan');
											}
											?>
										</div>
										<div id="pdl-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){ 
												echo $form->field($model, 'pdl_kode')->textInput(['class'=>'form-control fontsize-1-1'])->label('Kode Ajuan');
												echo Html::activeHiddenInput($model, 'log_bayar_dp_id');
											}else{
												echo $form->field($model, 'log_bayar_dp_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode Ajuan DP');
											}
											?>
										</div>
										<div id="mlg-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){
												echo $form->field($model, 'mlg_kode')->textInput(['class'=>'form-control fontsize-1-1'])->label('Kode Ajuan');
												echo Html::activeHiddenInput($model, 'log_bayar_muat_id');
											}else{
												echo $form->field($model, 'log_bayar_muat_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff()'])->label('Kode Ajuan');
											}
											?>
										</div>
										<div id="ovk-place" style="display: none;">
											<?php
											if(isset($_GET['voucher_pengeluaran_id'])){
												echo $form->field($model, 'ovk_kode')->textInput(['class'=>'form-control fontsize-1-1'])->label('Kode Open Voucher');
												echo Html::activeHiddenInput($model, 'open_voucher_id');
											}else{
												echo $form->field($model, 'open_voucher_id')->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setDetailReff(function(){ setAutoOv(); });'])->label('Kode Open Voucher');
											}
											?>
										</div>
										<div class="form-group">
											<?= Html::activeLabel($model, 'akun_debit', ['class'=>'col-md-4 control-label']) ?>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 55%">
													<?= $form->field($model, 'akun_debit',['template'=>'{input}','options'=>['style'=>'margin-left: 0px; margin-right: 0px;']])->dropDownList(\app\models\MAcctRekening::getOptionListBank(),['prompt'=>'','style'=>'padding:6px;']); ?>
												</span>
												<span class="input-group-btn" style="width: 45%">
													<?= $form->field($model, 'totaldebit',['template'=>'{input}','options'=>['style'=>'margin-left: 0px; margin-right: 0px;']])->textInput(['class'=>'form-control float']); ?> <!-- money-format -->
												</span> 
											</div>
										</div>
										<div class="form-group" style="margin-top: 5px;">
											<?= Html::activeLabel($model, 'cara_bayar', ['class'=>'col-md-4 control-label']) ?>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 40%">
													<?= $form->field($model, 'cara_bayar',['template'=>'{input}','options'=>['style'=>'margin-left: 0px; margin-right: 0px;']])->dropDownList(\app\models\MDefaultValue::getOptionListCustom('cara-bayar',"'Transfer Bank','Tunai'",'ASC'),['style'=>'padding:6px;','onchange'=>'setCarabayarReff()']); ?>
												</span>
												<span class="input-group-btn" style="width: 60%; visibility: hidden;">
													<?= $form->field($model, 'cara_bayar_reff',['template'=>'{input}','options'=>['style'=>'margin-left: 0px; margin-right: 0px;']])->textInput(['class'=>'form-control']); ?>
												</span> 
											</div>
											<?= Html::activeHiddenInput($model, 'nama_bank'); ?>
											<?= Html::activeHiddenInput($model, 'rekening'); ?>
											<?= Html::activeHiddenInput($model, 'an_bank'); ?>
										</div>
										<?php if(isset($_GET['voucher_pengeluaran_id']) && !isset($_GET['edit']) && ($model->cancel_transaksi_id == NULL) ){ ?>
										<div class="form-group" style="margin-top: 3px;">
											<?= Html::activeLabel($model, 'status_bayar', ['class'=>'col-md-4 control-label']) ?>
											<div class="col-md-7" style="margin-top: 8px;">
												<?php echo !empty($model->status_bayar)?$model->Status_bayar:""; ?>
											</div>
										</div>
										<?php }?>
										<?php if($model->cancel_transaksi_id != null){ ?>
										<div class="form-group" style="margin-top: 3px;">
											<?= Html::activeLabel($model, 'status_bayar', ['class'=>'col-md-4 control-label']) ?>
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
												<?php echo Html::activeDropDownList($model, 'mata_uang', \app\models\MDefaultValue::getOptionListLabelValue('mata-uang'),['class'=>'form-control','style'=>'font-size: 1.3rem; padding: 3px; height: 27px;']) ?>
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
																		<div style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">
																			<?= $detail->keterangan; ?>
																		</div>
																	</td>
																	<td style="text-align: right;">
																		<?= $model->mata_uang=="IDR"?app\components\DeltaFormatter::formatNumberForUser($detail->jumlah):app\components\DeltaFormatter::formatNumberForUserFloat($detail->jumlah, 2); ?>
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
                                                            <td style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 0px;">
																<?= Html::activeTextInput($model, 'totalkredit', ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size:1.2rem;']); ?>
															</td>
															<td></td>
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
															<?= Html::activeTextInput($model, 'total_dpp', ['class'=>'form-control float','disabled'=>'disabled']); ?>
														</td>
													</tr>
													<tr id="totalbayar-tr-dp">
														<td style="text-align: right;">TOTAL DP</td>
														<td class="td-kecil">
															<?= Html::activeTextInput($model, 'total_dp', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true)']); ?>
														</td>
													</tr>
													<tr id="totalbayar-tr-dp">
														<td style="text-align: right;" class="font-blue">SISA BAYAR</td>
														<td class="td-kecil">
															<?= Html::activeTextInput($model, 'total_sisa', ['class'=>'form-control float font-blue','disabled'=>'disabled']); ?>
														</td>
													</tr>
													<tr id="totalbayar-tr-kosong"></tr>
													<tr>
														<td style="text-align: right;">TOTAL PPN</td>
														<td class="td-kecil">
															<?= Html::activeTextInput($model, 'total_ppn', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true,true)']); ?>
														</td>
													</tr>
													<tr>
														<td style="text-align: right;">TOTAL PPh</td>
														<td class="td-kecil">
															<?= Html::activeTextInput($model, 'total_pph', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true)']); ?>
														</td>
													</tr>
													<tr>
														<td style="text-align: right;">TOTAL PBBKB</td>
														<td class="td-kecil">
															<?= Html::activeTextInput($model, 'total_pbbkb', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true)','disabled'=>'disabled']); ?>
														</td>
													</tr>
													<tr>
														<td style="text-align: right;">BIAYA TAMBAHAN</td>
														<td class="td-kecil">
															<?= Html::activeTextInput($model, 'biaya_tambahan', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true)']); ?>
														</td>
													</tr>
													<tr>
														<td style="text-align: right;">TOTAL POTONGAN</td>
														<td class="td-kecil">
															<?= Html::activeTextInput($model, 'total_potongan', ['class'=>'form-control float','onblur'=>'setTotalPembayaran(true)']); ?>
														</td>
													</tr>
													<tr>
														<td style="text-align: right;" class="font-red-mint">TOTAL PEMBAYARAN</td>
														<td class="td-kecil">
															<?= Html::activeTextInput($model, 'total_pembayaran', ['class'=>'form-control float font-red-mint','disabled'=>'disabled']); ?>
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
								<?php 
								// if(isset($_GET['edit']) ){
								// 	$modDrpDet = \app\models\TPengajuanDrpDetail::find()->where(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id])->orderBy(['pengajuan_drp_detail_id'=>SORT_DESC])->one();
								// 	$modDrp = \app\models\TPengajuanDrp::findOne(['pengajuan_drp_id'=>$modDrpDet->pengajuan_drp_id]);
								// 	if($modDrp->status_approve == "APPROVED"){
										// echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();', 'disabled'=>'disabled']);
								// 	} else {
								// 		echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']);
								// 	}
								// } else {
								// 	echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']);
								// }
								?>
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
	$pagemode = "editPage(".$_GET['voucher_pengeluaran_id'].");";
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
    $('#<?= Html::getInputId($model, 'suplier_id') ?>').siblings('.select2').addClass('animation-loading');
	var type = $('#<?= Html::getInputId($model, 'tipe') ?>').val();
	$.ajax({
		url    : '<?= Url::toRoute(['/finance/voucher/setDropdownSupplier']); ?>',
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
				$("#<?= Html::getInputId($model, 'suplier_id') ?>").html(data.html);
				$('#<?= Html::getInputId($model, 'suplier_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_kontrak_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
			}else if(data.type == 'Top-up Kas Kecil'){
				$("#ppk-place").attr('style','display:;');
				$("#<?= Html::getInputId($model, 'ppk_id') ?>").html( data.html );
				$('#<?= Html::getInputId($model, 'ppk_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_kontrak_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
			}else if(data.type == 'Ganti Kas Besar' || data.type == 'Ganti Kas Kecil'){
				$("#gkk-place").attr('style','display:;');
				$("#<?= Html::getInputId($model, 'gkk_id') ?>").html( data.html );
				$('#<?= Html::getInputId($model, 'gkk_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_kontrak_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
			}else if(data.type == 'Uang Dinas Grader'){
				$("#pdg-place").attr('style','display:;');
				$("#<?= Html::getInputId($model, 'ajuandinas_grader_id') ?>").html( data.html );
				$('#<?= Html::getInputId($model, 'ajuandinas_grader_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_kontrak_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
				$('#select2-tvoucherpengeluaran-ajuandinas_grader_id-container').addClass('fontsize-1-1'); // font-size
			}else if(data.type == 'Uang Makan Grader'){
				$("#pmg-place").attr('style','display:;');
				$("#<?= Html::getInputId($model, 'ajuanmakan_grader_id') ?>").html( data.html );
				$('#<?= Html::getInputId($model, 'ajuanmakan_grader_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_kontrak_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
				$('#select2-tvoucherpengeluaran-ajuanmakan_grader_id-container').addClass('fontsize-1-1'); // font-size
			}else if(data.type == 'Pembayaran DP Log'){
				$("#pdl-place").attr('style','display:;');
				$("#<?= Html::getInputId($model, 'log_bayar_dp_id') ?>").html( data.html );
				$('#<?= Html::getInputId($model, 'log_bayar_dp_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
				$('#select2-tvoucherpengeluaran-log_kontrak_id-container').addClass('fontsize-1-3'); // font-size
			}else if(data.type == 'Pelunasan Log'){
				$("#mlg-place").attr('style','display:;');
				$("#<?= Html::getInputId($model, 'log_bayar_muat_id') ?>").html( data.html );
				$('#<?= Html::getInputId($model, 'log_bayar_muat_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'open_voucher_id') ?>').val('').trigger('change');
				$('#select2-tvoucherpengeluaran-log_kontrak_id-container').addClass('fontsize-1-3'); // font-size
			}else if(data.type == 'Open Voucher'){
				$("#ovk-place").attr('style','display:;');
				$("#<?= Html::getInputId($model, 'open_voucher_id') ?>").html( data.html );
				$('#<?= Html::getInputId($model, 'open_voucher_id') ?>').siblings('.select2').removeClass('animation-loading');
				$('#<?= Html::getInputId($model, 'suplier_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ppk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'gkk_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuandinas_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'ajuanmakan_grader_id') ?>').val('').trigger('change');
				$('#<?= Html::getInputId($model, 'log_bayar_dp_id') ?>').val('').trigger('change');
                $('#<?= Html::getInputId($model, 'log_bayar_muat_id') ?>').val('').trigger('change');
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
	var supplier_id = $("#<?= Html::getInputId($model, 'suplier_id') ?>").val();
	var ppk_id = $("#<?= Html::getInputId($model, 'ppk_id') ?>").val();
	var gkk_id = $("#<?= Html::getInputId($model, 'gkk_id') ?>").val();
	var ajuandinas_grader_id = $("#<?= Html::getInputId($model, 'ajuandinas_grader_id') ?>").val();
	var ajuanmakan_grader_id = $("#<?= Html::getInputId($model, 'ajuanmakan_grader_id') ?>").val();
	var log_bayar_dp_id = $("#<?= Html::getInputId($model, 'log_bayar_dp_id') ?>").val();
	var log_bayar_muat_id = $("#<?= Html::getInputId($model, 'log_bayar_muat_id') ?>").val();
	var type = $('#<?= Html::getInputId($model, 'tipe') ?>').val();
	var voucher_pengeluaran_id = '<?= isset($_GET['voucher_pengeluaran_id'])?$_GET['voucher_pengeluaran_id']:null; ?>';
	var open_voucher_id = $("#<?= Html::getInputId($model, 'open_voucher_id') ?>").val();
	$.ajax({
        url    : '<?= Url::toRoute(['/finance/voucher/setDetailReff']); ?>',
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

                var total_potongan = $('input[name*="bhppotonganharga"]').val();
                $('input[name*="[total_potongan]"]').val(total_potongan);
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
                    // 2021-04-09 buka tutup biaya tambahan dan potongan buat hendri
                    $('input[name*="[biaya_tambahan]"]').prop('disabled',false);
                    $('input[name*="[total_potongan]"]').prop('disabled',false);
					// tambahan 2024-12-13
					<?php if(isset($_GET['voucher_pengeluaran_id']) && !isset($_GET['edit'])){ ?>
						$('input[name*="[biaya_tambahan]"]').prop('disabled',true);
						$('input[name*="[total_potongan]"]').prop('disabled',true);
					<?php } ?>
				}
				$('input[name*="[nama_bank]"]').val(data.nama_bank);
				$('input[name*="[rekening]"]').val(data.rekening);
				$('input[name*="[an_bank]"]').val(data.an_bank);
			}else if($('#tvoucherpengeluaran-tipe').val() == "Pembayaran DP BHP"){
				$('input[name*="[total_ppn]"]').removeAttr('disabled');
				$('input[name*="[total_pph]"]').removeAttr('disabled');
				$('input[name*="[nama_bank]"]').val(data.nama_bank);
				$('input[name*="[rekening]"]').val(data.rekening);
				$('input[name*="[an_bank]"]').val(data.an_bank);
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
                // 2021-03-24 buka tutup biaya tambahan dan potongan request rachman + iswari
                $('input[name*="[biaya_tambahan]"]').prop('disabled',false);
                $('input[name*="[total_potongan]"]').prop('disabled',false);
				// tambahan 2024-12-13
				<?php if(isset($_GET['voucher_pengeluaran_id']) && !isset($_GET['edit'])){ ?>
					$('input[name*="[biaya_tambahan]"]').prop('disabled',true);
                	$('input[name*="[total_potongan]"]').prop('disabled',true);
				<?php } ?>
                if(data.modOpenVoucher){
                    if(data.modOpenVoucher.tipe == "DP LOG SENGON"){
                        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple').attr('onclick','detailPoByKode("'+data.modOpenVoucher.reff_no+'")');
                        $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+data.modOpenVoucher.penerima_reff_id+'")');
                    }else if(data.modOpenVoucher.tipe == "PELUNASAN LOG SENGON"){
                        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple').attr('onclick','detailPoByKode("'+data.modOpenVoucher.reff_no+'")');
                        $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+data.modOpenVoucher.penerima_reff_id+'")');
                        $("#place-berkas-reff").find('#btn-reff-3').removeClass('grey').addClass('green-seagreen').attr('onclick','');
                    }else if(data.modOpenVoucher.tipe == "PEMBAYARAN LOG ALAM"){
						$("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple').attr('onclick','detailKeputusan("'+data.modOpenVoucher.reff_no+'")');
                        $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+data.modOpenVoucher.penerima_reff_id+'")');
					}else if(data.modOpenVoucher.tipe == "PEMBAYARAN ASURANSI LOG SHIPPING"){
						$("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple').attr('onclick','detailAsuransi("'+data.modOpenVoucher.reff_no+'")');
					}
                }
				$('input[name*="[nama_bank]"]').val(data.nama_bank);
				$('input[name*="[rekening]"]').val(data.rekening);
				$('input[name*="[an_bank]"]').val(data.an_bank);
			} else if(($('#tvoucherpengeluaran-tipe').val() == "Uang Dinas Grader") || ($('#tvoucherpengeluaran-tipe').val() == "Uang Makan Grader")){
				$('input[name*="[nama_bank]"]').val(data.nama_bank);
				$('input[name*="[rekening]"]').val(data.rekening);
				$('input[name*="[an_bank]"]').val(data.an_bank);
			}
			if(callback){ callback(); }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function addItem(){
    $.ajax({
        url    : '<?= Url::toRoute(['/finance/voucher/addItem']); ?>',
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
        url    : '<?= Url::toRoute(['/finance/voucher/editItem']); ?>',
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
	// $('#<?= Html::getInputId($model, 'totalkredit') ?>').val(formatInteger(totalkredit));
	if($(this).find('input[name*="[mata_uang]"]').val() == "IDR"){
		$('#<?= Html::getInputId($model, 'totalkredit') ?>').val(formatInteger(totalkredit));
	} else {
		$('#<?= Html::getInputId($model, 'totalkredit') ?>').val(formatNumberForUser2Digit(totalkredit));
	}
}

function setTotalPembayaran(editmode=null,editppn=false,loadaftersave=null){
	var total_dpp = 0; var total_dp = 0; var total_sisa = 0; var total_ppn = 0; var total_pph = 0; var total_pbbkb = 0; var total_ppk = 0; var total_gkk=0; var total_pdg=0; var total_pmg=0; var total_pdl=0; var total_mlg=0; 
    var total_pembayaran = 0; var biaya_tambahan = 0;  var total_potongan = 0; var total_ovk=0;

	var tanggal_bayar = new Date($('input[name*="tanggal_bayar"]').val());
    tanggal_bayar = tanggal_bayar.toString('yyyy-MM-dd');
	
	if(editmode){
		total_dpp = unformatNumber( $('input[name*="[total_dpp]"]').val() );
		total_dp = unformatNumber( $('input[name*="[total_dp]"]').val() );
		total_pph = unformatNumber( $('input[name*="[total_pph]"]').val() );
		total_pbbkb = unformatNumber( $('input[name*="[total_pbbkb]"]').val() );
		biaya_tambahan = unformatNumber( $('input[name*="biaya_tambahan"]').val() );
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
				if(tanggal_bayar < '2025-01-02'){ // jika sebelum 2 januari 2025, ppn 11%
					total_ppn = (total_dpp-total_dp) * 0.11;
				} else {
					total_ppn = (total_dpp-total_dp) * <?= Params::DEFAULT_PPN?>;
				}
			}else{
                total_ppn = unformatNumber( $('input[name="totalppnreff"]').val() );
            }
		}
	}else{
		total_dpp = unformatNumber( $('input[name="totaldppreff"]').val() );
		total_pph = unformatNumber( $('input[name="totalpphreff"]').val() );
		total_pbbkb = unformatNumber( $('input[name="totalpbbkb"]').val() );
		biaya_tambahan = unformatNumber( $('input[name*="biaya_tambahan"]').val() );
		total_potongan = unformatNumber( $('input[name*="total_potongan"]').val() );
		total_ppk = unformatNumber( $('input[name*="totalppk"]').val() );
		total_gkk = unformatNumber( $('input[name*="totalgkk"]').val() );
		total_pdg = unformatNumber( $('input[name*="totalpdg"]').val() );
		total_pmg = unformatNumber( $('input[name*="totalpmg"]').val() );
		total_pdl = unformatNumber( $('input[name*="totalpdl"]').val() );
		total_mlg = unformatNumber( $('input[name*="totalmlg"]').val() );
		total_ovk = unformatNumber( $('input[name*="totalovk"]').val() );
		if( unformatNumber( $('input[name="totalppnreff"]').val() )  > 0 && total_ovk > 0 ){
			if(tanggal_bayar < '2025-01-02'){ // jika sebelum 2 januari 2025, ppn 11%
				total_ppn = (total_dpp-total_dp) * 0.11;
				total_dp = unformatNumber( $('input[name="totaldp"]').val() ) / (1 + 0.11);
			} else {
				total_ppn = (total_dpp-total_dp) * <?= Params::DEFAULT_PPN?>;
				total_dp = unformatNumber( $('input[name="totaldp"]').val() ) / (1 + <?= Params::DEFAULT_PPN?>);
			}
		}else{
			total_ppn = unformatNumber( $('input[name="totalppnreff"]').val() );
			total_dp = unformatNumber( $('input[name="totaldp"]').val() );
		}
	}
	
	if (($('#tvoucherpengeluaran-tipe').val() == "Pembelian BHP")){ 
		var total_sisa = total_dpp - total_dp;
		var total_pembayaran = total_sisa + total_ppn + total_pph + total_pbbkb + biaya_tambahan - total_potongan;
        // rumusan pph dibuat +(plus) karena nilai pph adalah minus bukan absolute
	} else if ($('#tvoucherpengeluaran-tipe').val() == "Pembayaran DP BHP"){
		total_ppn = unformatNumber( $('input[name="totalppnreff"]').val() );
		total_pph = unformatNumber( $('input[name*="[total_pph]"]').val() );
		var total_pembayaran = total_dp + total_ppn + biaya_tambahan + total_pph - total_potongan;
	} else if ($('#tvoucherpengeluaran-tipe').val() == "Open Voucher"){
        var total_sisa = total_dpp - total_dp;
		var total_pembayaran = total_sisa + total_ppn - total_pph + total_pbbkb + biaya_tambahan - total_potongan;        
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
	// console.log($('input[name*="total_potongan"]'));
	// console.log(total_ppn);
	// console.log(total_pph);
	// console.log(total_potongan);
   // return false;
	$('input[name*="[total_dpp]"]').val(formatNumberForUser2Digit(total_dpp));
	$('input[name*="[total_dp]"]').val(formatNumberForUser2Digit(total_dp));
	$('input[name*="[total_sisa]"]').val(formatNumberForUser2Digit(total_sisa));
	$('input[name*="[total_ppn]"]').val(formatNumberForUser(total_ppn));
	$('input[name*="[total_pph]"]').val(formatNumberForUser(total_pph));
	$('input[name*="[total_pbbkb]"]').val(formatNumberForUser2Digit(total_pbbkb));
	// $('input[name*="[biaya_tambahan]"]').val(formatNumberForUser(biaya_tambahan));
	// $('input[name*="[total_potongan]"]').val(formatNumberForUser(total_potongan));
	// $('input[name*="[total_pembayaran]"]').val(formatNumberForUser2Digit(total_pembayaran));
	// $('#tvoucherpengeluaran-totaldebit').val(formatNumberForUser2Digit(total_pembayaran));
	
	<?php //if(isset($_GET['voucher_pengeluaran_id'])){ ?>
		var mata_uang = '<?= $model->mata_uang; ?>';
		if(mata_uang == 'IDR'){
			$('input[name*="[biaya_tambahan]"]').val(formatInteger(biaya_tambahan));
			$('input[name*="[total_potongan]"]').val(formatInteger(total_potongan));
			$('input[name*="[total_pembayaran]"]').val(formatInteger(total_pembayaran));
			$('#tvoucherpengeluaran-totaldebit').val(formatInteger(total_pembayaran));
		} else {
			$('input[name*="[biaya_tambahan]"]').val(formatNumberForUser2Digit(biaya_tambahan));
			$('input[name*="[total_potongan]"]').val(formatNumberForUser2Digit(total_potongan));
			$('input[name*="[total_pembayaran]"]').val(formatNumberForUser2Digit(total_pembayaran));
			$('#tvoucherpengeluaran-totaldebit').val(formatNumberForUser2Digit(total_pembayaran));
		}
	<?php //} ?>
}
function resetTotalPembayaran(){
	$('input[name*="[total_dpp]"]').val(0);
	$('input[name*="[total_dp]"]').val(0);
	$('input[name*="[total_sisa]"]').val(0);
	$('input[name*="[total_ppn]"]').val(0);
	$('input[name*="[total_pph]"]').val(0);
	$('input[name*="[total_pbbkb]"]').val(0);
	<?php if(!isset($_GET['edit'])){ ?>
		$('input[name*="[biaya_tambahan]"]').val(0);
		$('input[name*="[total_potongan]"]').val(0);
	<?php } ?>
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
	var tipe = $('#<?= Html::getInputId($model, 'tipe') ?>').val();
	$.ajax({
        url    : '<?= Url::toRoute(['/finance/voucher/setTipe']); ?>',
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
	var no_berkas = $('#<?= Html::getInputId($model, 'nomor_terkait') ?>').val();
	var tipe = $('#<?= Html::getInputId($model, 'tipe') ?>').val();
	$.ajax({
        url    : '<?= Url::toRoute(['/finance/voucher/setBerkas']); ?>',
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
				$('#btn-save').hide();
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
	var tanggal_bayar = $("#<?= \yii\helpers\Html::getInputId($model, "tanggal_bayar") ?>").val();

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
	if(!tanggal_bayar){
		$("#<?= \yii\helpers\Html::getInputId($model, "tanggal_bayar") ?>").addClass('error-tb-detail');
        has_error = has_error + 1;
	} else {
		$("#<?= \yii\helpers\Html::getInputId($model, "tanggal_bayar") ?>").removeClass('error-tb-detail');
	}
	
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
		// $('#btn-print').removeAttr('disabled');
		// kalo drp udah approve, status_bayar PAID maka bisa print
		<?php if(isset($_GET['voucher_pengeluaran_id'])){ 
			$modVoucher = TVoucherPengeluaran::findOne(['voucher_pengeluaran_id'=>$_GET['voucher_pengeluaran_id']]);
			if($modVoucher->status_bayar == 'PAID'){ ?>
				$('#btn-print').removeAttr('disabled');
		<?php } else {
				$modDrp = Yii::$app->db->createCommand("
							SELECT * FROM t_pengajuan_drp
							JOIN t_pengajuan_drp_detail ON t_pengajuan_drp.pengajuan_drp_id = t_pengajuan_drp_detail.pengajuan_drp_id
							JOIN t_voucher_pengeluaran ON t_voucher_pengeluaran.voucher_pengeluaran_id = t_pengajuan_drp_detail.voucher_pengeluaran_id
							WHERE t_voucher_pengeluaran.voucher_pengeluaran_id=".$_GET['voucher_pengeluaran_id']." and t_pengajuan_drp.status_approve NOT IN ('REJECTED', 'ABORTED')"
							)->queryAll();
				if(count($modDrp) > 0){
					foreach($modDrp as $m => $mod){
						if($mod['status_approve'] == 'APPROVED'){ 
							if($mod['status_pengajuan'] == 'Disetujui'){?>
								$('#btn-print').removeAttr('disabled');
						<?php }
						}
					}
				}
			}
		}?>
				
	},500);
}

function daftarAfterSave(){
    openModal('<?= Url::toRoute(['/finance/voucher/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function printBbk(id){
	window.open("<?= yii\helpers\Url::toRoute('/finance/voucher/printBbk') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function printout(caraPrint,tgl){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/rekapkaskecil/PrintoutLaporan') ?>?tgl="+tgl+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function setCarabayarReff(){
	var cara_bayar = $('#<?= Html::getInputId($model, 'cara_bayar') ?>').val();
	if(cara_bayar == 'Klik-BCA'){
		$('#<?= Html::getInputId($model, 'cara_bayar_reff') ?>').parents('.input-group-btn').css('visibility', 'hidden');
	}else if(cara_bayar == 'Bilyet Giro'){
		$('#<?= Html::getInputId($model, 'cara_bayar_reff') ?>').parents('.input-group-btn').css('visibility','visible');
		$('#<?= Html::getInputId($model, 'cara_bayar_reff') ?>').attr('placeholder','Input No. Giro');
	}else if(cara_bayar == 'Cek'){
		$('#<?= Html::getInputId($model, 'cara_bayar_reff') ?>').parents('.input-group-btn').css('visibility','visible');
		$('#<?= Html::getInputId($model, 'cara_bayar_reff') ?>').attr('placeholder','Input No. Cek');
	}
}

function changeStatus(id){
	var url = '<?= Url::toRoute(['/finance/voucher/changeStatus','id'=>'']); ?>'+id;
	$(".modals-place-confirm").load(url, function() {
		$("#modal-transaksi").modal('show');
		$("#modal-transaksi").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}

function editVoucher(voucher_pengeluaran_id){
    window.location.replace('<?= Url::toRoute(['/finance/voucher/index','edit'=>true,'voucher_pengeluaran_id'=>'']); ?>'+voucher_pengeluaran_id);
}
function editPage(id){
    setDetailReff();
	editItem(id);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'kode') ?>').attr('disabled','disabled');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tipe') ?>').attr('disabled','disabled');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').attr('disabled','disabled');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_nm') ?>').attr('disabled','disabled');
    <?php
    // 2021-08-28
    // REQUEST HENDRI, BISA EDIT TANGGAL KALAU STATUS UNPAID
    if ($model->status_bayar == "PAID") {
    ?>
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_bayar') ?>').attr('disabled','disabled');
    $('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
    <?php
    }
    // EO REQUEST HENDRI, BISA EDIT TANGGAL KALAU STATUS UNPAID
    ?>
	
}

function cancelVoucher(voucher_pengeluaran_id){
	openModal('<?php echo Url::toRoute(['/finance/voucher/cancelVoucher']) ?>?id='+voucher_pengeluaran_id,'modal-transaksi');
}

function gkk(id){
	var url = '<?= Url::toRoute(['/kasir/pengeluarankaskecil/detailGkk']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-gkk").modal('show');
		$("#modal-gkk").on('hidden.bs.modal', function () { });
		$("#modal-gkk .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}
function ppk(id){
	var url = '<?= Url::toRoute(['/kasir/ppk/detailppk']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-ppk").modal('show');
		$("#modal-ppk").on('hidden.bs.modal', function () { });
		$("#modal-ppk .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}
function ajuanDinas(id){
	var url = '<?= Url::toRoute(['/purchasinglog/biayagrader/detailAjuanDinas']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-ajuandinas").modal('show');
		$("#modal-ajuandinas").on('hidden.bs.modal', function () { });
		$("#modal-ajuandinas .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}
function ajuanMakan(id){
	var url = '<?= Url::toRoute(['/purchasinglog/biayagrader/detailAjuanMakan']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-ajuanmakan").modal('show');
		$("#modal-ajuanmakan").on('hidden.bs.modal', function () { });
		$("#modal-ajuanmakan .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}

function infoKontrak(id){
	var url = '<?= Url::toRoute(['/purchasinglog/pengajuandplog/infoKontrak']); ?>?id='+id;
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
    // console.log('setauto '+open_voucher_id);
    $.ajax({
        url    : '<?= Url::toRoute(['/finance/voucher/setAutoItems']); ?>',
        type   : 'POST',
        data   : {terima_bhp_ids:terima_bhp_ids},
        success: function (data) {
			console.log(terima_bhp_ids);
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
    openModal('<?= Url::toRoute(['/finance/voucher/cariOpenVoucher']) ?>','modal-open-voucher','90%');
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
        window.location.replace("<?= Url::toRoute(['/finance/voucher/index','setOpenVoucher'=>'']) ?>"+open_voucher_id);
    <?php } ?>
}
function infoVoucher(id){
	var url = '<?= Url::toRoute(['/finance/voucher/detailBbk']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-bbk").modal('show');
		$("#modal-bbk").on('hidden.bs.modal', function () { });
		$("#modal-bbk .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}
function detailPoByKode(reff_no){
    openModal('<?= Url::toRoute(['/purchasinglog/posengon/detailPoByKode','kode'=>'']) ?>'+reff_no,'modal-detailpo','22cm');
}
function riwayatSaldoSuplierSengon(id){
	openModal('<?= Url::toRoute(['/purchasinglog/saldosuplierlog/riwayatSaldo','id'=>'']) ?>'+id,'modal-riwayatsaldo','80%');
}
function detailKeputusan(reff_no){
    kode = reff_no.split('-')[0];
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/detailKeputusan','kode'=>'']) ?>'+kode,'modal-detailpo','22cm');
}
function detailAsuransi(kode){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/asuransi/detailAsuransi','kode'=>'']) ?>'+kode,'modal-detailpo','22cm');
}

function setAutoOv(){
	var open_voucher_id = $("#<?= yii\helpers\Html::getInputId($model, "open_voucher_id") ?>").val();
	var mata_uang = $("#<?= yii\helpers\Html::getInputId($model, "mata_uang") ?>").val();
    $.ajax({
        url    : '<?= Url::toRoute(['/finance/voucher/setAutoOv']); ?>',
        type   : 'POST',
        data   : {open_voucher_id:open_voucher_id, mata_uang:mata_uang},
        success: function (data) {
			if(data.items){
				$("#<?= yii\helpers\Html::getInputId($model, "mata_uang") ?>").prop('disabled', true);
				$("#<?= yii\helpers\Html::getInputId($model, "mata_uang") ?>").val(data.ov.mata_uang);
                $("#table-detail > tbody").html(data.items);
                reordertable('#table-detail');
            }
            setTotal();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function infoApprover(id){
	var url = '<?= Url::toRoute(['/finance/voucher/detailApprover']); ?>?id='+id;
    console.log("URL:", url); // Debug URL
    $(".modals-place-2").load(url, function(response, status, xhr) {
        if (status === "error") {
            console.error("Error loading modal:", xhr.status, xhr.statusText);
        }
        $("#modal-approver").modal('show');
        $("#modal-approver .modal-dialog").css('width', "75%");
    });
}
</script>