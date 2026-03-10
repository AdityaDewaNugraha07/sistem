<?php
/* @var $this yii\web\View */
$this->title = 'Pengajuan DP Log';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Pengajuan DP Log'); ?></h1>
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
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar DP Log'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<?php if(isset($_GET['log_kontrak_id'])){ ?>
										<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data DP Log Terakhir'); ?></h4></span>
									<?php }else{ ?>
										<span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Downpayment Log'); ?></h4></span>
									<?php } ?>
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
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'log_kontrak_id')->dropDownList(\app\models\TLogKontrak::getOptionListPO(),['class'=>'form-control select2','prompt'=>'','onchange'=>'showDetail(this.value)'])->label("Kode PO"); ?>
										<?= $form->field($model, 'nomor_kontrak')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'total_dp')->textInput(['class'=>'form-control float']); ?>
										<?php if(isset($_GET['log_kontrak_id'])){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label">Status Approval</label>
                                                <div class="col-md-7" style="margin-top: 5px;">
                                                    <?php
                                                    $modApproval = app\models\TApproval::findOne(['reff_no'=>$model->kode,'level'=>"2"]);
                                                    if(!empty($modApproval)){
                                                        if($modApproval->status == \app\models\TApproval::STATUS_APPROVED){
                                                            echo '<span class="label label-success">'.$modApproval->status.'</span> <span style="font-size:1rem;"> at '.$modApproval->updated_at.'</span>';
                                                        }else if($modApproval->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
                                                            echo '<span class="label label-default">'.$modApproval->status.'</span>';
                                                        }else if($modApproval->status == \app\models\TApproval::STATUS_REJECTED){
                                                            echo '<span class="label label-danger">'.$modApproval->status.'</span> <span style="font-size:1rem;"> at '.$modApproval->updated_at.'</span>';
                                                        }
                                                    }
                                                    ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label">Status Payment</label>
												<div class="col-md-7" style="margin-top:8px;">
                                                    <?php
                                                    $modVoucher = app\models\TVoucherPengeluaran::findOne($model->voucher_pengeluaran_id);
                                                    if(!empty($modVoucher)){
                                                        if($modVoucher->status_bayar == "UNPAID"){
                                                            echo '<span class="label label-warning">UNPAID</span> <span style="font-size:1rem;"> at '.$modVoucher->created_at.'</span>';
                                                        }else if($modVoucher->status_bayar == "PAID"){
                                                            echo '<span class="label label-success">PAID</span> <span style="font-size:1rem;"> at '.$modVoucher->updated_at.'</span>';
                                                        }
                                                    }else{
                                                        echo '<span class="label label-default"><i>Menunggu Open Voucher</i></span>';
                                                    }
                                                    ?>
												</div>
											</div>
										<?php } ?>
                                    </div>
                                </div>
                                <div class="row dp-sebelumnya" style="display: none;">
									<br><br><hr>
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'DP yang sudah masuk'); ?></h4>
                                    </div>
                                    <div class="col-md-7">
                                    </div>
                                </div>
                                <div class="row dp-sebelumnya" style="display: none;">
                                    <div class="col-md-12">
										<span class="spb-info-place pull-right"></span>
                                        <div id="showup-pembayaran"></div>
                                    </div>
                                </div>
                                <div class="row detail-ajuan" style="display: none;">
									<br><br><hr>
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Data Kontrak'); ?></h4>
                                    </div>
                                    <div class="col-md-7">
                                    </div>
                                </div>
                                <div class="row detail-ajuan" style="display: none;">
                                    <div class="col-md-12">
										<span class="spb-info-place pull-right"></span>
                                        <div id="showup"></div>
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
if(isset($_GET['log_kontrak_id'])){
    $pagemode = "afterSave();";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode
	$(this).find('select[name*=\"[log_kontrak_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode PO',
		width: null
	});
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Pengajuan DP Log'))."');
", yii\web\View::POS_READY); ?>
<script>
function showDetail(log_kontrak_id){
	if(!log_kontrak_id){
		log_kontrak_id = "<?= isset($_GET['log_kontrak_id'])? $_GET['log_kontrak_id'] : "" ; ?>";
	}
	$('#showup').addClass('animation-loading');
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuandplog/showDetail']); ?>',
		type   : 'POST',
		data   : {log_kontrak_id:log_kontrak_id},
		success: function (data) {
			if(data.html){
				$('#showup').html(data.html);
				$('.detail-ajuan').attr('style','display:block');
			}else{
				$('.detail-ajuan').attr('style','display:none');
				$('#showup').html("");
			}
			<?php if(isset($_GET['log_kontrak_id'])){ ?>
			if(data.htmldp){
				$('#showup-pembayaran').html(data.htmldp);
				$('.dp-sebelumnya').attr('style','display:block');
			}else{
				$('.detail-ajuan').attr('style','display:none');
				$('#dp-sebelumnya').html("");
			}
			<?php } ?>
			$('#showup').removeClass('animation-loading');
			if(data.model){
				$("#<?= yii\helpers\Html::getInputId($model, "nomor_kontrak") ?>").val(data.model.nomor);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function afterSave(id){
    showDetail();
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tloglist-tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuandplog/daftarAfterSave']) ?>','modal-aftersave','75%');
}


</script>