<?php
/* @var $this yii\web\View */
$this->title = 'Permintaan Penambahan Kas';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-ppk',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-md-12">
						<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarDp()"><i class="fa fa-list"></i> <?= Yii::t('app', 'PPK yang telah dibuat'); ?></a>
					</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Permintaan Penambahan Kas Baru'); ?></h4></span>
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
										if(!isset($_GET['ppk_id'])){
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
										<?= $form->field($model, 'tanggal',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'tipe')->dropDownList(['Kas Kecil'=>'Kas Kecil','Kas Besar'=>'Kas Besar'],['style'=>'padding:6px;']); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'tanggal_diperlukan',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?php
										if(isset($_GET['kas_bon_id'])){
											$disabled = TRUE;
										}else{
											$disabled = FALSE;
										}
										?>
										<?= $form->field($model, 'nominal')->textInput(['class'=>'form-control float','disabled'=>$disabled]); ?>
										<?= $form->field($model, 'keperluan')->textarea(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php /*<div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
								<?php 
									if(isset($_GET['ppk_id'])){
										$disabled = FALSE;
									}else{
										$disabled = TRUE;
									}
								?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>(($disabled==FALSE)? 'printout('.(isset($_GET['ppk_id'])?$_GET['ppk_id']:'').')' :''),'disabled'=>$disabled]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div> */?>
                        
                        <?php
                        if ($model->cancel_transaksi_id > 0) {
                            $cancel_transaksi = \app\models\TCancelTransaksi::findOne(['reff_no'=>$model->kode]);
                            $pegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$cancel_transaksi->cancel_by]);
                            $pegawai_nama = $pegawai->pegawai_nama;
                        ?>
                            <div class="col-md-8 pull-left" style="font-size: 10px;"><span class="label label-sm label-danger">ABORTED</span><br>by : <?php echo $pegawai_nama;?> 
                            <br>reason : <span class="text-danger"><?php echo $cancel_transaksi->cancel_reason;?></span> 
                            <br>at : <?php echo \app\components\DeltaFormatter::formatDateTimeForUser($cancel_transaksi->cancel_at);?></div>
                        <?php
                        }
                        ?>
                        <div class="col-md-4 form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
								<?php 
                                if(isset($_GET['ppk_id'])){
                                    $disabled = FALSE;
                                }else{
                                    $disabled = TRUE;
                                }
								?>
                                <?php 
                                if ($model->cancel_transaksi_id == null && $model->voucher_pengeluaran_id == null) {
                                    echo "&nbsp;".\yii\helpers\Html::button( Yii::t('app', 'Batal'),['id'=>'btn-danger','class'=>'btn red btn-outline ciptana-spin-btn','onclick'=>(($disabled==FALSE)? 'confirmBatal('.(isset($_GET['ppk_id'])?$_GET['ppk_id']:'').')' :''),'disabled'=>$disabled]);
                                    
                                }                                
                                ?>
                                <?php echo "&nbsp;".\yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>(($disabled==FALSE)? 'printout('.(isset($_GET['ppk_id'])?$_GET['ppk_id']:'').')' :''),'disabled'=>$disabled]);?>
                                <?php echo "&nbsp;".\yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
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
if(isset($_GET['ppk_id'])){
    $pagemode = "afterSave()";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode;
", yii\web\View::POS_READY); ?>
<script>
function save(){
    var $form = $('#form-ppk');
    if(formrequiredvalidate($form)){
        submitform($form);
    }
    return false;
}
function afterSave(id){
	var total = 0;
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
    $('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tppk-tanggal').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#tppk-tanggal_diperlukan').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
}

function daftarDp(){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/ppk/daftarPpk']) ?>','modal-daftar-ppk','75%');
}

function printout(id){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/ppk/printout') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function confirmBatal(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/ppk/confirmBatal','id'=>'']) ?>'+id,'modal-confirm','500px');
}

</script>