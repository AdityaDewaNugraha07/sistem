<?php
/* @var $this yii\web\View */
$this->title = 'Pengajuan Pelunasan Log';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Pengajuan Pelunasan Log'); ?></h1>
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
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Pengajuan Pelunasan'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Pengajuan'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']); ?>
										<?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>				
										<?= $form->field($model, 'tongkang')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                    <div class="col-md-6">
										<?php if(isset($_GET['log_bayar_muat_id'])){ ?>
											<?= \yii\bootstrap\Html::activeHiddenInput($model, 'loglist_id') ?>
											<?= $form->field($model, 'loglist_kode')->textInput(['disabled'=>true]); ?>
										<?php }else{ ?>
											<div class="form-group field-tlogbayarmuat-loglist_id">
												<label class="col-md-4 control-label" for="tlogbayarmuat-loglist_id">Loglist</label>
												<div class="col-md-7">
													<?= yii\helpers\Html::activeDropDownList($model, 'loglist_id', \app\models\TLoglist::getOptionListPelunasanLog(),['class'=>'form-control select2','prompt'=>'','onchange'=>'loglistsetted()']); ?>
													<span class="help-block"></span>
												</div>
											</div>
										<?php } ?>
										<?= \yii\bootstrap\Html::activeHiddenInput($model, 'log_kontrak_id') ?>
										<?= \yii\bootstrap\Html::activeHiddenInput($model, 'pengajuan_pembelianlog_id') ?>
										<?= $form->field($model, 'kode_keputusan')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'kode_po')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'nomor_kontrak')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'reff_no')->textInput(); ?>
                                    </div>
                                </div>
                                <div class="row ">
									<br><br><hr>
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Detail Pengajuan Pelunasan '); ?></h4>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-6">
										<br><h5><b><?= Yii::t('app', 'Detail Downpayment Berdasarkan PO / Kontrak'); ?></b></h5>
                                        <div class="detail-dp">
											<div class="table-scrollable">
												<div class="table-scrollable">
													<table class="table table-striped table-bordered table-advance table-hover" id="table-detail" style="width: 100%">
														<thead>
															<tr>
																<th style="" ><?= Yii::t('app', 'Kode Pengajuan DP'); ?></th>
																<th style=""><?= Yii::t('app', 'Tanggal'); ?></th>
																<th style=""><?= Yii::t('app', 'Status'); ?></th>
																<th style="width: 150px;"><?= Yii::t('app', 'Jumlah DP'); ?></th>
															</tr>
														</thead>
														<tbody>
															<tr><td colspan="4" style="text-align: center;"><i><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></td></tr>
														</tbody>
														<tfoot>

														</tfoot>
													</table>
												</div>
											</div>
										</div>
										<br><h5><b><?= Yii::t('app', 'Rekap Loglist'); ?></b></h5>
                                        <div class="rekap-loglist">
											<tr><td colspan="3" style="text-align: center;"><i><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i></td></tr>
										</div>
                                    </div>
                                    <div class="col-md-6">
										<br><h5><b><?= Yii::t('app', 'Overview'); ?></b></h5>
										<?= $form->field($model, 'total_volume')->textInput(['class'=>'form-control float','disabled'=>TRUE])->label('Volume (m<sup>3</sup>)'); ?>
										<?= \yii\bootstrap\Html::activeHiddenInput($model, 'harga_m3',['value'=>'0']) ?>
										<?php // echo $form->field($model, 'harga_m3')->textInput(['class'=>'form-control float','onblur'=>'setTotal()'])->label('Harga Per m<sup>3</sup> (Rp)'); ?>
										<?= $form->field($model, 'total_harga')->textInput(['class'=>'form-control float'])->label('Total Harga (Rp)'); ?>
										<?= $form->field($model, 'total_dp')->textInput(['class'=>'form-control float','onblur'=>'setTotal()'])->label('Total Pakai DP (Rp)'); ?>
										<?= $form->field($model, 'total_bayar')->textInput(['class'=>'form-control float','disabled'=>TRUE])->label('Total Sisa Bayar (Rp)'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'submitform()']); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['log_bayar_muat_id'])){
    $pagemode = "afterSave()";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode;
	$(this).find('select[name*=\"[loglist_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nomor Loglist',
		width: null
	});
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Pengajuan Pelunasan Log'))."');
", yii\web\View::POS_READY); ?>
<script>
function loglistsetted(){
	var loglist_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'loglist_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pemuatanlog/loglistsetted']); ?>',
		type   : 'POST',
		data   : {loglist_id:loglist_id},
		success: function (data) {
			if(data.modKontrak && data.modKeputusan){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'log_kontrak_id') ?>').val(data.log_kontrak_id);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'pengajuan_pembelianlog_id') ?>').val(data.pengajuan_pembelianlog_id);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'tongkang') ?>').val(data.tongkang);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'kode_keputusan') ?>').val(data.modKeputusan.kode);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'kode_po') ?>').val(data.modKontrak.kode);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'nomor_kontrak') ?>').val(data.modKontrak.nomor);
			}else{
				$('#<?= yii\bootstrap\Html::getInputId($model, 'log_kontrak_id') ?>').val('');
				$('#<?= yii\bootstrap\Html::getInputId($model, 'pengajuan_pembelianlog_id') ?>').val('');
				$('#<?= yii\bootstrap\Html::getInputId($model, 'tongkang') ?>').val('');
				$('#<?= yii\bootstrap\Html::getInputId($model, 'kode_keputusan') ?>').val('');
				$('#<?= yii\bootstrap\Html::getInputId($model, 'kode_po') ?>').val('');
				$('#<?= yii\bootstrap\Html::getInputId($model, 'nomor_kontrak') ?>').val('');
			}
			if(data.html){
				$('#table-detail tbody').html(data.html);
			}else{
				$('#table-detail tbody').html('<tr><td colspan="3" style="text-align: center;"><i><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></td></tr>');
			}
			if(data.htmlloglist){
				$('.rekap-loglist').html(data.htmlloglist);
			}else{
				$('.rekap-loglist').html('<tr><td colspan="3" style="text-align: center;"><i><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></td></tr>');
			}
			if(data.volumem3){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'total_volume') ?>').val(formatInteger(data.volumem3.totalm3));
			}else{
				$('#<?= yii\bootstrap\Html::getInputId($model, 'total_volume') ?>').val(0);
			}
			
			var harga = unformatNumber( $('#<?= yii\bootstrap\Html::getInputId($model, 'total_harga') ?>').val() );
			var dp = unformatNumber( $('#<?= yii\bootstrap\Html::getInputId($model, 'total_dp') ?>').val() );
			$('#<?= yii\bootstrap\Html::getInputId($model, 'total_bayar') ?>').val( formatInteger(harga - dp) );
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function afterSave(id){
    loglistsetted();
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tlogbayarmuat-tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pemuatanlog/DaftarAfterSave']) ?>','modal-aftersave','80%');
}

function setTotal(){
	var total_volume = unformatNumber( $("#<?= \yii\helpers\Html::getInputId($model, 'total_volume') ?>").val() );
	var total_harga = unformatNumber( $("#<?= \yii\helpers\Html::getInputId($model, 'total_harga') ?>").val() );
	var total_dp = unformatNumber( $("#<?= \yii\helpers\Html::getInputId($model, 'total_dp') ?>").val() );
	var total_bayar = unformatNumber( $("#<?= \yii\helpers\Html::getInputId($model, 'total_bayar') ?>").val() );
	
	
	total_bayar = total_harga - total_dp; 
	
	
	$("#<?= \yii\helpers\Html::getInputId($model, 'total_bayar') ?>").val( formatNumberForUser(total_bayar) );
}

</script>