<?php
/* @var $this yii\web\View */
$this->title = 'Kontrak Log Alam';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\MagnificPopupAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-kontraklog',
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
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Kontrak'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Kontrak'); ?></h4></span>
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
										if(!isset($_GET['log_kontrak_id'])){
											echo $form->field($model, 'kode')->label('Kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
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
										<?= $form->field($model, 'tanggal_po',[
												'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
															 <button class="btn default" type="button" style="margin-left: 0px;" disabled><i class="fa fa-calendar"></i></button></span></div> 
															 {error}</div>'])->label('Tanggal')->textInput(['readonly'=>'readonly', 'disabled' => 'disabled', 'style' => 'background-color: rgb(238, 241, 245);']); ?>
										<?= $form->field($model, 'hasil_orientasi_id')->dropDownList(\app\models\THasilOrientasi::getOptionListPO(),['class'=>'form-control select2','onchange'=>'setOrientasi()','prompt'=>'','style'=>'width:100%;'])->label("Kode Orientasi"); ?>
										<?= $form->field($model, 'nama_iuphhk')->textInput(['disabled'=>true])->label("Nama HPH"); ?>
										<?= $form->field($model, 'nama_ipk')->textInput(['disabled'=>true])->label("Nama IPK"); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'nomor')->textInput(['placeholder'=>'contoh: No.003/RML-CWM/X/2017']); ?>
                                        <?= $form->field($model, 'tanggal',[
												'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
															 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
															 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'kode_cardpad')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Pihak Pertama (Penjual)'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
										<?= $form->field($model, 'pihak1_nama',['template'=>'{label}<div class="col-md-8">
																		<span class="input-group-btn" style="width: 80%">{input}</span> 
																		<span class="input-group-btn" style="width: 5%"></span> 
																		<span class="input-group-btn" style="width: 15%">
																			<button '.(isset($_GET['log_kontrak_id'])?"disabled=''":"").' class="btn btn-icon-only btn-default tooltips" type="button" data-original-title="Tarik Master Suplier" data-url="'. \yii\helpers\Url::toRoute('/purchasinglog/kontraklog/pickPanel') .'"  onclick="openPickPanel(this)">
																				<i class="fa fa-arrow-circle-left"></i>
																			</button>
																		</span> {error}</div>'])
																	->textInput(); ?>
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, 'suplier_id'); ?>
                                        <?= $form->field($model, 'pihak1_perusahaan')->textInput(['disabled'=>true]); ?>
                                        <?= $form->field($model, 'pihak1_alamat')->textarea(['disabled'=>true]); ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Pihak Kedua (Pembeli)'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'pihak2_pegawai')->dropDownList(\app\models\MPegawai::getOptionList(),['class'=>'form-control select2','prompt'=>'']); ?>
										<?= $form->field($model, 'pihak2_pegawai2')->dropDownList(\app\models\MPegawai::getOptionList(),['class'=>'form-control select2','prompt'=>'']); ?>
                                        <?= $form->field($model, 'pihak2_perusahaan')->textInput(); ?>
                                        <?= $form->field($model, 'pihak2_alamat')->textarea(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Log'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'jenis_log')->textarea(); ?>
                                        <?= $form->field($model, 'asal_log')->textarea(); ?>
                                        <?= $form->field($model, 'kuantitas')->textarea(); ?>
                                        <?= $form->field($model, 'kualitas')->textarea(); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'komposisi')->textarea(); ?>
										<?= $form->field($model, 'hargafob',['template'=>'{label}<div class="col-md-8">
																							<span class="input-group-btn" style="width: 50%">{input}</span> 
																							<span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($model, 'term_of_price', ["CIF"=>"CIF","CNF"=>"CNF","FOB"=>"FOB","Logpond Penjual"=>"Logpond Penjual"],['class'=>'form-control']).'</span> {error}</div>'])
																	->textInput()->label("Harga / m<sup>3</sup>"); ?>
                                        <?= $form->field($model, 'is_ppn10',['template' => '{label}<div class="mt-checkbox-list col-md-7" style="margin-bottom:10px;"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                                                                        ->checkbox([],false); ?>
                                        <?= $form->field($model, 'lokasi_muat')->textarea(); ?>
										<div class="form-group">
											<?php 
											echo $form->field($model, 'uploadfile',[
													'template'=>'{label}
														<div class="col-md-8">
															<div class="fileinput fileinput-new" data-provides="fileinput">
																<div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
																	<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
																<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
																<div>
																	<span class="btn btn-xs blue-hoki btn-outline btn-file">
																		<span class="fileinput-new"> Select Image </span>
																		<span class="fileinput-exists"> Change </span>
																		{input} 
																	</span> 
																	<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																	{error}
																</div>
															</div>
														</div>'
												])->fileInput();
											?>
                                            </div>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
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
<div id="pick-panel" style="height: 92%;"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['log_kontrak_id'])){
    $pagemode = "afterSave()";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode;
	$(this).find('select[name*=\"[pihak2_pegawai]\"]').select2({
		allowClear: !0,
		placeholder: '',
		width: null
	});
	$(this).find('select[name*=\"[pihak2_pegawai2]\"]').select2({
		allowClear: !0,
		placeholder: '',
		width: null
	});
	$(this).find('select[name*=\"[hasil_orientasi_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Kode Orientasi',
		width: null
	});
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('PO Log Alam'))."');
", yii\web\View::POS_READY); ?>
<script>

function afterSave(id){
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tlogkontrak-tanggal').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#tlogkontrak-tanggal_po').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    getAttch('<?= (isset($_GET['log_kontrak_id'])?$_GET['log_kontrak_id']:'') ?>','<?= (isset($_GET['edit'])?'1':'0') ?>');
    <?php if(isset($_GET['edit'])){ ?>
        $("#<?= \yii\helpers\Html::getInputId($model, "nomor") ?>").prop("disabled", false);
        $('#<?= \yii\helpers\Html::getInputId($model, "tanggal") ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
        $('#btn-save').removeAttr('disabled','');
    <?php } ?>
}

function getAttch(log_kontrak_id,edit){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/kontraklog/getAttch']); ?>',
		type   : 'POST',
		data   : {log_kontrak_id:log_kontrak_id},
		success: function (data) {
            if(edit=='0'){
                $(".field-tlogkontrak-uploadfile").find(".btn-file").addClass("hidden");
                $(".field-tlogkontrak-uploadfile").find("img").parent().attr("style","vertical-align: middle; line-height: 0.5;");
                if(data.attch){
                    var src = "<?= Yii::$app->urlManager->baseUrl ?>/uploads/pur/kontraklog/"+data.attch; 
                    $(".field-tlogkontrak-uploadfile").find("img").parent().html("<b>File : </b><br><br><br><a style='font-size:1rem; line-height:1;' href='"+src+"' target='BLANK'>"+data.attch+"</a>");
                }else{
                    $(".field-tlogkontrak-uploadfile").find("img").parent().html("<b>File Not Found</b>");
                }   
            }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/kontraklog/daftarAfterSave']) ?>','modal-aftersave','75%');
}
function setOrientasi(){
	var hasil_orientasi_id = $("#<?= yii\helpers\Html::getInputId($model, "hasil_orientasi_id") ?>").val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/kontraklog/setOrientasi']); ?>?hasil_orientasi_id='+hasil_orientasi_id,
		type   : 'POST',
		data   : {},
		success: function (data) {
			if(data){
				$('#<?= yii\helpers\Html::getInputId($model, "nama_iuphhk") ?>').val(data.nama_iuphhk);
				$('#<?= yii\helpers\Html::getInputId($model, "nama_ipk") ?>').val(data.nama_ipk);
				$('#<?= yii\helpers\Html::getInputId($model, "lokasi_muat") ?>').val(data.lokasi_muat);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function edit(log_kontrak_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/kontraklog/index','log_kontrak_id'=>'']); ?>'+log_kontrak_id+'&edit=1');
}
function setNormalPickAttch(fileno){
	if(!fileno){
		fileno = "";
	}
	$(".field-tlogkontrak-uploadfile"+fileno).find(".btn-file").removeClass("hidden");
	$(".field-tlogkontrak-uploadfile"+fileno).find(".fileinput.fileinput-new > a.red-flamingo").remove();
	$(".field-tlogkontrak-uploadfile"+fileno).find("img").attr("src","<?= Yii::$app->view->theme->baseUrl; ?>/cis/img/no-image.png");
}
</script>