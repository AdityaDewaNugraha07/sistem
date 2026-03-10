<?php
/* @var $this yii\web\View */
$this->title = 'Stock History';
\app\assets\Select2Asset::register($this);
\app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Stock History'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
		<div class="portlet light bordered form-search custom-bordered">
			<div class="portlet-body">
				<?php $form = \yii\bootstrap\ActiveForm::begin([
					'id' => 'form-search',
					'fieldConfig' => [
						'template' => '{label}<div class="col-md-8">{input} {error}</div>',
						'labelOptions'=>['class'=>'col-md-3 control-label'],
					],
					'enableClientValidation'=>false
				]); ?>
				<div class="row">
					<div class="col-md-6">
						<?php
						$value_arr = [];
						if(!empty($model->bhp_id)){
							$modBhp = app\models\MBrgBhp::findOne($model->bhp_id);
							$value_arr[$model->bhp_id] = $modBhp->bhp_nm;
						}
						?>
						<?php echo $form->field($model, 'bhp_id')->dropDownList($value_arr,['prompt'=>''])->label(Yii::t('app', 'Nama Items')); ?>
					</div>
					<div class="col-md-6">
						<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
					</div>
				</div>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
				<?php \yii\bootstrap\ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="portlet light custom-bordered">
			<div class="portlet-title">
				<div class="caption"><?= Yii::t('app', 'Stock Activity of '); ?> <b style="font-size: 1.6rem;"><span id="namaitemplace"></span> </b></div>
				<div class="pull-right"></div>
			</div>
			<div class="portlet-body" id="showStockActivity"></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="portlet light custom-bordered">
			<div class="portlet-title">
				<div class="caption"><?= Yii::t('app', 'Adjustment Stock of '); ?> <b style="font-size: 1.6rem;"><span id="namaitemplace"></span> </b></div>
				<div class="pull-right"></div>
			</div>
			<div class="portlet-body" id="showAdjStock"></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light custom-bordered">
			<div class="portlet-title">
				<div class="caption"><?= Yii::t('app', 'Purchase Activity of '); ?> <b style="font-size: 1.6rem;"><span id="namaitemplace"></span> </b></div>
				<div class="pull-right"></div>
			</div>
			<div class="portlet-body" id="showPurchaseActivity">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div id="showSPB"></div>
							</div>
						</div><br>
						<div class="row">
							<div class="col-md-12">
								<div id="showTBP"></div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div id="showSPP"></div>
							</div>
						</div><br>
						<div class="row">
							<div class="col-md-12">
								<div id="showSPO"></div>
							</div>
						</div><br>
						<div class="row">
							<div class="col-md-12">
								<div id="showSPL"></div>
							</div>
						</div><br>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light custom-bordered">
			<div class="portlet-title" style="text-align: center;">
				<div class="caption" ><?= Yii::t('app', 'Availability Inventory Chart'); ?> <b style="font-size: 1.6rem;"><span id="namaitemplace"></span> </b></div>
				<div class="pull-right"></div>
			</div>
			<div class="portlet-body" id="showInventoryActivity"></div>
		</div>
	</div>
</div>
<?php $this->registerJs(" 
getContent();
$('#form-search').submit(function(){
	getContent();
	return false;
});
$('#".\yii\bootstrap\Html::getInputId($model, 'bhp_id')."').select2({
	allowClear: !0,
	placeholder: 'Ketik Nama Item',
	width: '250px',
	ajax: {
		url: '". \yii\helpers\Url::toRoute('/logistik/spb/FindBhpActive') ."',
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
$('#".yii\bootstrap\Html::getInputId($model, 'bhp_id')."').change(function(){
	getContent();
});
$('#".yii\bootstrap\Html::getInputId($model, 'tgl_awal')."').change(function(){
	getContent();
});
$('#".yii\bootstrap\Html::getInputId($model, 'tgl_akhir')."').change(function(){
	getContent();
});
formconfig(); 
", yii\web\View::POS_READY); ?>
<script>
<?php $this->registerCssFile($this->theme->baseUrl."/pages/css/profile.min.css",['depends'=>[yii\web\YiiAsset::className()]]); ?>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery.sparkline.min.js",['depends'=>[yii\web\YiiAsset::className(), yii\web\JqueryAsset::className()]]) ?>
function getContent(){
	getStockActivity();
	getAdjStock();
	getSPB();
	getSPP();
	getSPO();
	getSPL();
	getTBP();
	getBPB();
}

function getStockActivity(){
	$('#showStockActivity').addClass('animation-loading');
	$('#namaitemplace').html('');
	var bhp_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'bhp_id') ?>').val();
	var tgl_awal = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_awal') ?>').val();
	var tgl_akhir = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_akhir') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/stockbhp/getStockActivity']); ?>',
		type   : 'POST',
		data   : {bhp_id:bhp_id,tgl_awal:tgl_awal,tgl_akhir:tgl_akhir},
		success: function (data) {
			$('#showStockActivity').html('');
			if(data.html){
				$('#showStockActivity').html(data.html);
				$('#namaitemplace').html(data.modBhp.bhp_nm);
				$("#sparkline_bar").sparkline([8, 9, 10, 11, 10, 10, 12, 10, 10, 11, 9, 12, 11], {
					type: "bar",
					width: "100",
					barWidth: 6,
					height: "45",
					barColor: "#86a426",
					negBarColor: "#e02222"
				});
				$("#sparkline_bar2").sparkline([9, 11, 12, 13, 12, 13, 10, 14, 13, 11, 11, 12, 11], {
					type: "bar",
					width: "100",
					barWidth: 6,
					height: "45",
					barColor: "#F36A5B",
					negBarColor: "#e02222"
				});
			}
			$('#showStockActivity').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getAdjStock(){
	$('#showAdjStock').addClass('animation-loading');
	var bhp_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'bhp_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/stockbhp/getAdjStock']); ?>',
		type   : 'POST',
		data   : {bhp_id:bhp_id},
		success: function (data) {
			$('#showAdjStock').html('');
			if(data.html){
				$('#showAdjStock').html(data.html);
			}
			$('#showAdjStock').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getSPB(){
	$('#showSPB').addClass('animation-loading');
	var bhp_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'bhp_id') ?>').val();
	var tgl_awal = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_awal') ?>').val();
	var tgl_akhir = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_akhir') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/stockbhp/getSPB']); ?>',
		type   : 'POST',
		data   : {bhp_id:bhp_id,tgl_awal:tgl_awal,tgl_akhir:tgl_akhir},
		success: function (data) {
			$('#showSPB').html('');
			if(data.html){
				$('#showSPB').html(data.html);
			}
			$('#showSPB').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getSPP(){
	$('#showSPP').addClass('animation-loading');
	var bhp_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'bhp_id') ?>').val();
	var tgl_awal = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_awal') ?>').val();
	var tgl_akhir = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_akhir') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/stockbhp/getSPP']); ?>',
		type   : 'POST',
		data   : {bhp_id:bhp_id,tgl_awal:tgl_awal,tgl_akhir:tgl_akhir},
		success: function (data) {
			$('#showSPP').html('');
			if(data.html){
				$('#showSPP').html(data.html);
			}
			$('#showSPP').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getTBP(){
	$('#showTBP').addClass('animation-loading');
	var bhp_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'bhp_id') ?>').val();
	var tgl_awal = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_awal') ?>').val();
	var tgl_akhir = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_akhir') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/stockbhp/getTBP']); ?>',
		type   : 'POST',
		data   : {bhp_id:bhp_id,tgl_awal:tgl_awal,tgl_akhir:tgl_akhir},
		success: function (data) {
			$('#showTBP').html('');
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
	var bhp_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'bhp_id') ?>').val();
	var tgl_awal = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_awal') ?>').val();
	var tgl_akhir = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_akhir') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/stockbhp/getTBP']); ?>',
		type   : 'POST',
		data   : {bhp_id:bhp_id,tgl_awal:tgl_awal,tgl_akhir:tgl_akhir},
		success: function (data) {
			$('#showBPB').html('');
			if(data.html){
				$('#showBPB').html(data.html);
			}
			$('#showBPB').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getSPO(){
	$('#showSPO').addClass('animation-loading');
	var bhp_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'bhp_id') ?>').val();
	var tgl_awal = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_awal') ?>').val();
	var tgl_akhir = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_akhir') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/stockbhp/getSPO']); ?>',
		type   : 'POST',
		data   : {bhp_id:bhp_id,tgl_awal:tgl_awal,tgl_akhir:tgl_akhir},
		success: function (data) {
			$('#showSPO').html('');
			if(data.html){
				$('#showSPO').html(data.html);
				$('#showSPO').removeAttr('style');
			}
			$('#showSPO').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function getSPL(){
	$('#showSPL').addClass('animation-loading');
	var bhp_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'bhp_id') ?>').val();
	var tgl_awal = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_awal') ?>').val();
	var tgl_akhir = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tgl_akhir') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/stockbhp/getSPL']); ?>',
		type   : 'POST',
		data   : {bhp_id:bhp_id,tgl_awal:tgl_awal,tgl_akhir:tgl_akhir},
		success: function (data) {
			$('#showSPL').html('');
			if(data.html){
				$('#showSPL').html(data.html);
				$('#showSPL').removeAttr('style');
			}
			$('#showSPL').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function infoSPB(id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpb']) ?>?id='+id+'&bhp_id='+bhp_id,'modal-info-spb','75%');
}
function infoSPP(id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpp']) ?>?id='+id+'&bhp_id='+bhp_id,'modal-info-spp','75%');
}
function infoSPO(id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpo']) ?>?id='+id+'&bhp_id='+bhp_id,'modal-info-spo','75%');
}
function infoSPL(id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpl']) ?>?id='+id+'&bhp_id='+bhp_id,'modal-info-spl','75%');
}
function infoTBP(terima_bhp_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id+'&bhp_id='+bhp_id,'modal-info-tbp','75%','');
}
function infoBPB(bpb_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoBpb']) ?>?id='+bpb_id+'&bhp_id='+bhp_id,'modal-info-bpb','75%','');
}


</script>