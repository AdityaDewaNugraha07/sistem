<?php
/* @var $this yii\web\View */
$this->title = 'Tracking Data Pembelian BHP';
\app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Tracking Data Pembelian BHP'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET -->
						<div class="portlet light bordered form-search">
							<div class="portlet-title">
								<div class="tools panel-cari">
									<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
									<span style=""> <?= Yii::t('app', '&nbsp;Tracking Berdasarkan :'); ?></span>
								</div>
							</div>
							<div class="portlet-body">
								<?php $form = \yii\bootstrap\ActiveForm::begin([
									'id' => 'form-search',
									'fieldConfig' => [
										'template' => '{label}<div class="col-md-8">{input} {error}</div>',
										'labelOptions'=>['class'=>'col-md-3 control-label'],
									],
									'enableClientValidation'=>false
								]); ?>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-6">
											<?= $form->field($modSPO, 'spo_id')->dropDownList([],['prompt'=>''])->label(Yii::t('app', 'Kode PO')); ?>
										</div>
										<div class="col-md-6">
											<?= $form->field($modSPL, 'spl_id')->dropDownList([],['prompt'=>''])->label(Yii::t('app', 'Kode SPL')); ?>
										</div>
									</div>
								</div>
								<?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
								<?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
								<?php \yii\bootstrap\ActiveForm::end(); ?>
							</div>
						</div>
						<!-- END EXAMPLE TABLE PORTLET-->
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-list"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar Berkas Terkait'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
									<div class="col-md-6">
										<div class="portlet light custom-bordered">
											<div class="portlet-title">
												<div class="caption"><?= Yii::t('app', 'Detail Berkas'); ?></div>
												<div class="pull-right">
													<!--<a class="btn btn-icon-only btn-default tooltips" onclick="javascript:void(0);" data-original-title="Print Out"><i class="fa fa-print"></i></a>-->
												</div>
											</div>
											<div class="portlet-body" id="showPO">
												<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>
											</div>
											<div class="portlet-body" id="showSPL" style="display: none;">
												<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>
											</div>
										</div>
										<div class="portlet light custom-bordered">
											<div class="portlet-title">
												<div class="caption"><?= Yii::t('app', 'TBP / LPB Terkait'); ?></div>
												<div class="pull-right">
													<!--<a class="btn btn-icon-only btn-default tooltips" onclick="javascript:void(0);" data-original-title="Print Out"><i class="fa fa-print"></i></a>-->
												</div>
											</div>
											<div class="portlet-body" id="showTBP">
												<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="portlet light custom-bordered">
											<div class="portlet-title">
												<div class="caption"><?= Yii::t('app', 'SPP Terkait'); ?></div>
												<div class="pull-right">
													<!--<a class="btn btn-icon-only btn-default tooltips" onclick="javascript:void(0);" data-original-title="Print Out"><i class="fa fa-print"></i></a>-->
												</div>
											</div>
											<div class="portlet-body" id="showSPP">
												<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>
											</div>
										</div>
										<div class="portlet light custom-bordered">
											<div class="portlet-title">
												<div class="caption"><?= Yii::t('app', 'SPB Terkait'); ?></div>
												<div class="pull-right">
													<!--<a class="btn btn-icon-only btn-default tooltips" onclick="javascript:void(0);" data-original-title="Print Out"><i class="fa fa-print"></i></a>-->
												</div>
											</div>
											<div class="portlet-body" id="showSPB">
												<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>
											</div>
										</div>
										<div class="portlet light custom-bordered">
											<div class="portlet-title">
												<div class="caption"><?= Yii::t('app', 'BPB Terkait'); ?></div>
												<div class="pull-right">
													<!--<a class="btn btn-icon-only btn-default tooltips" onclick="javascript:void(0);" data-original-title="Print Out"><i class="fa fa-print"></i></a>-->
												</div>
											</div>
											<div class="portlet-body" id="showBPB">
												<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>
											</div>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Tracking Pembelian BHP'))."');
$('#form-search').submit(function(){
	getItems();
	return false;
});
$('#".\yii\bootstrap\Html::getInputId($modSPO, 'spo_id')."').select2({
	allowClear: !0,
	placeholder: 'Ketik Kode PO',
	width: '250px',
	ajax: {
		url: '". \yii\helpers\Url::toRoute('/purchasing/tracking/findSPO') ."',
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
$('#".\yii\bootstrap\Html::getInputId($modSPL, 'spl_id')."').select2({
	allowClear: !0,
	placeholder: 'Ketik Kode SPL',
	width: '250px',
	ajax: {
		url: '". \yii\helpers\Url::toRoute('/purchasing/tracking/findSPL') ."',
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
$('#".yii\bootstrap\Html::getInputId($modSPO, 'spo_id')."').change(function(){
	if($(this).val()){
		$('#".\yii\bootstrap\Html::getInputId($modSPL, 'spl_id') ."').val('').trigger('change');
		getContent();
	}
});
$('#".yii\bootstrap\Html::getInputId($modSPL, 'spl_id')."').change(function(){
	if($(this).val()){
		$('#".\yii\bootstrap\Html::getInputId($modSPO, 'spo_id') ."').val('').trigger('change');
		getContent();
	}
});
", yii\web\View::POS_READY); ?>
<script>
function getContent(){
	getSPO();
	getSPL();
	getSPP();
	getSPB();
	getTBP();
	getBPB();
}

function getSPO(){
	$('#showPO').addClass('animation-loading');
	var spo_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPO, 'spo_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/getSPO']); ?>',
		type   : 'POST',
		data   : {spo_id:spo_id},
		success: function (data) {
			$('#showPO').html('<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>');
			if(data.html){
				$('#showPO').html(data.html);
				$('#showPO').removeAttr('style');
				$('#showSPL').attr('style','display:none');
			}
			$('#showPO').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function getSPL(){
	$('#showSPL').addClass('animation-loading');
	var spl_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPL, 'spl_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/getSPL']); ?>',
		type   : 'POST',
		data   : {spl_id:spl_id},
		success: function (data) {
			$('#showSPL').html('<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>');
			if(data.html){
				$('#showSPL').html(data.html);
				$('#showPO').attr('style','display:none');
				$('#showSPL').removeAttr('style');
			}
			$('#showSPL').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getSPP(){
	$('#showSPP').addClass('animation-loading');
	var spo_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPO, 'spo_id') ?>').val();
	var spl_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPL, 'spl_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/getSPP']); ?>',
		type   : 'POST',
		data   : {spo_id:spo_id,spl_id:spl_id},
		success: function (data) {
			$('#showSPP').html('<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>');
			if(data.html){
				$('#showSPP').html(data.html);
			}
			$('#showSPP').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getSPB(){
	$('#showSPB').addClass('animation-loading');
	var spo_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPO, 'spo_id') ?>').val();
	var spl_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPL, 'spl_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/getSPB']); ?>',
		type   : 'POST',
		data   : {spo_id:spo_id,spl_id:spl_id},
		success: function (data) {
			$('#showSPB').html('<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>');
			if(data.html){
				$('#showSPB').html(data.html);
			}
			$('#showSPB').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getTBP(){
	$('#showTBP').addClass('animation-loading');
	var spo_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPO, 'spo_id') ?>').val();
	var spl_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPL, 'spl_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/getTBP']); ?>',
		type   : 'POST',
		data   : {spo_id:spo_id,spl_id:spl_id},
		success: function (data) {
			$('#showTBP').html('<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>');
			if(data.html){
				$('#showTBP').html(data.html);
			}
			$('#showTBP').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getBPB(){
	$('#showBPB').addClass('animation-loading');
	var spo_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPO, 'spo_id') ?>').val();
	var spl_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPL, 'spl_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/getBPB']); ?>',
		type   : 'POST',
		data   : {spo_id:spo_id,spl_id:spl_id},
		success: function (data) {
			$('#showBPB').html('<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>');
			if(data.html){
				$('#showBPB').html(data.html);
			}
			$('#showBPB').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function infoSPP(spp_id){
	var spo_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPO, 'spo_id') ?>').val();
	var spl_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPL, 'spl_id') ?>').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpp']) ?>?id='+spp_id+'&spo_id='+spo_id+'&spl_id='+spl_id,'modal-info-spp','75%','getSPP();');
}
function infoSPB(spb_id){
	var spo_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPO, 'spo_id') ?>').val();
	var spl_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPL, 'spl_id') ?>').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpb']) ?>?id='+spb_id+'&spo_id='+spo_id+'&spl_id='+spl_id,'modal-info-spb','75%','getSPB();');
}
function infoTBP(terima_bhp_id){
	var spo_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPO, 'spo_id') ?>').val();
	var spl_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPL, 'spl_id') ?>').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id+'&spo_id='+spo_id+'&spl_id='+spl_id,'modal-info-tbp','75%','getTBP();');
}
function infoBPB(bpb_id){
	var spo_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPO, 'spo_id') ?>').val();
	var spl_id = $('#<?= \yii\bootstrap\Html::getInputId($modSPL, 'spl_id') ?>').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoBpb']) ?>?id='+bpb_id+'&spo_id='+spo_id+'&spl_id='+spl_id,'modal-info-bpb','75%','getBPB();');
}

function checklisttracking(ele,reff_no,bhp_id){
	if($(ele).is(':checked')){
		var checked = 1;
		$(ele).parents('tr').attr('style','background-color:  #A6C054;');
	}else{
		var checked = 0;
		$(ele).parents('tr').attr('style','background-color:  #fceeb1;');
	}
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/checklistTracking']); ?>',
		type   : 'POST',
		data   : {checked:checked,reff_no:reff_no,bhp_id:bhp_id},
		success: function (data) {
			
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function printSpp(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/tracking/printSpp') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
function printTbp(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/tracking/printTbp') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
</script>