<?php
/* @var $this yii\web\View */
$this->title = 'Transaksi SPP';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Surat Permintaan Pembelian (SPP)'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-spp',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
		<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarSpp()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar SPP'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Permintaan'); ?></h4></span>
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
										if(!isset($_GET['spp_id'])){
											echo $form->field($model, 'spp_kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode SPP'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'spp_kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->spp_kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        <?= $form->field($model, 'spp_nomor')->textInput(['placeholder'=>'No. pada faktur']); ?>
										<?= $form->field($model, 'spp_tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?php if(isset($_GET['spp_id'])){ ?>
										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Status SPP'); ?></label>
											<div class="col-md-7" style="margin-top:7px;">
												<?php
												if($model->spp_status == 'TO-DO'){
													echo '<span class="label label-info">'.$model->spp_status .'</span>';
												}else if($model->spp_status == 'INPROGRESS'){
													echo '<span class="label label-warning">'.$model->spp_status .'</span>';
												}else if($model->spp_status == 'COMPLETE'){
													echo '<span class="label label-success">'.$model->spp_status .'</span>';
												}else if($model->spp_status == 'CANCEL'){
													echo '<span class="label label-danger">'.$model->spp_status .'</span>';
												}else if($model->spp_status == 'PENDING'){
													echo '<span class="label label-default">'.$model->spp_status .'</span>';
												}
												?>
											</div>
										</div>
										<?php } ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'spp_tanggal_dibutuhkan',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                        <?= $form->field($model, 'spp_disetujui')->dropDownList(\app\models\MPegawai::getOptionList(),['class'=>'form-control select2','prompt'=>'']); ?>
										<?= $form->field($model, 'spp_catatan')->textarea(); ?>
                                    </div>
                                </div>
                                <br><br><hr>
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Permintaan'); ?></h4>
                                    </div>
                                    <div class="col-md-7 pull-right" style="margin-top:25px;">
                                            <label class="col-md-4" style="margin-top:5px; text-align: right;"><?= Yii::t('app', (isset($_GET['spb_id'])?"Load SPB :":"Departement :")); ?></label>
                                            <div class="col-md-5" >
                                                <span class="input-group-btn">
													<?php if(isset($_GET['spp_id'])){ ?>
														<input type="text" class="form-control" value="<?= $model->departement->departement_nama; ?>">
													<?php }else{ 
														echo yii\bootstrap\Html::activeDropDownList($model, 'departement_id', \app\models\TSpb::getOptionListDepartement(),['prompt'=>'-- Pilih Departement --','class'=>'form-control','disabled'=>'disabled']);
													} ?>
                                                </span>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
                                                        <th rowspan="2" style="vertical-align: middle; text-align: center; width: 300px;" ><?= Yii::t('app', 'Nama Item'); ?></th>
                                                        <th colspan="4" style="text-align: center;  vertical-align: middle;"><?= Yii::t('app', 'Kuantiti'); ?></th>
                                                        <th rowspan="2" style="width: 60px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Satuan'); ?></th>
                                                        <th rowspan="2" style="vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
														<th rowspan="2" style="width: 50px; vertical-align: middle; text-align: center;" ><?= Yii::t('app', 'Cancel'); ?></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 80px;"><?= Yii::t('app', 'Kebutuhan'); ?></th>
                                                        <th style="width: 80px;"><?= Yii::t('app', 'Terpenuhi'); ?></th>
                                                        <th style="width: 80px;"><?= Yii::t('app', 'Permintaan'); ?></th>
                                                        <th style="width: 80px;"><?= Yii::t('app', 'Stock <br>Sekarang'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
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
									if(isset($_GET['spp_id'])){
										$disabled = FALSE;
									}else{
										$disabled = TRUE;
									}
								?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>(($disabled==FALSE)? 'printout('.(isset($_GET['spp_id'])?$_GET['spp_id']:'').')' :''),'disabled'=>$disabled]); ?>
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
if(isset($_GET['spp_id'])){
    $pagemode = "afterSave()";
}else{
    $pagemode = "getItems();";
}
if(isset($_GET['loadjs'])){
	$autoloadcontent = 'autoloadcontent('.$_GET['loadjs']['dept_id'].(isset($_GET['loadjs']['spb_id'])?','.$_GET['loadjs']['spb_id']:'').')';
}else{
	$autoloadcontent = '';
}
?>
<?php $this->registerJs(" 
    $('#".yii\bootstrap\Html::getInputId($model, 'departement_id')."').change(function(){
        getItems();
    });
	formconfig();
    $pagemode;
	$autoloadcontent;
", yii\web\View::POS_READY); ?>
<script>
function autoloadcontent(dept_id,spb_id){
	getItems(dept_id,spb_id);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'departement_id') ?>').val(dept_id);
}

function getItems(departement_id=null,spb_id=null){
    $('#table-detail').addClass('animation-loading');
    if(!departement_id){
		departement_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'departement_id') ?>').val();
    }
    var html = "";
    if(departement_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/logistik/spp/getItems']); ?>',
            type   : 'POST',
            data   : {departement_id:departement_id,spb_id:spb_id},
            success: function (data) {
                if(data){
                    html = data.html;
                    $('#table-detail tbody').html(html);
                    $('#table-detail').removeClass('animation-loading');
                    reordertable('#table-detail');
                }
				if(data.spb){
					$("#<?= yii\helpers\Html::getInputId($model, "spp_catatan") ?>").val(data.spb.spb_keterangan);
				}
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }else{
        html = "<tr><td colspan='9'><center><i>Data tidak ditemukan</i></center></td></tr>"
        $('#table-detail tbody').html(html);
        $('#table-detail').removeClass('animation-loading');
    }
}

function cancelItem(ele){
    $(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
        reordertable('#table-detail');
    });
}

function save(){
    var $form = $('#form-spp');
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
        var field2 = $(this).find('input[name*="[sppd_qty]"]');
        if(!field1.val()){
            $(this).find('input[name*="[bhp_nama]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[bhp_nama]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[sppd_qty]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( $(this).find('input[name*="[sppd_qty]"]').val() == 0 ){
                has_error = has_error + 1;
                $(this).find('input[name*="[sppd_qty]"]').parents('td').addClass('error-tb-detail');
            }else{
                $(this).find('input[name*="[sppd_qty]"]').parents('td').removeClass('error-tb-detail');
            }
        }
    });
    
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    getItemsBySpp(id);
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tspp-spp_tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#tspp-spp_tanggal_dibutuhkan').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
}

function getItemsBySpp(){
    $('#table-detail').addClass('animation-loading');
    var spp_id = '<?= (isset($_GET['spp_id'])?$_GET['spp_id']:'') ?>';
    var html = "";
    if(spp_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/logistik/spp/GetItemsBySpp']); ?>',
            type   : 'POST',
            data   : {spp_id:spp_id},
            success: function (data) {
                if(data){
                    html = data.html;
                    $('#table-detail tbody').html(html);
                    $('#table-detail').removeClass('animation-loading');
                    reordertable('#table-detail');
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }else{
        html = "<tr><td colspan='7'><center><i>Data tidak ditemukan</i></center></td></tr>"
        $('#table-detail tbody').html(html);
        $('#table-detail').removeClass('animation-loading');
    }
}

function daftarSpp(){
    openModal('<?= \yii\helpers\Url::toRoute(['/logistik/spp/daftarSpp']) ?>','modal-daftar-spp','75%');
}

function printout(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/tracking/printSpp') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
</script>