<?php
/* @var $this yii\web\View */
$this->title = 'PO Yang Telah Dibuat';
\app\assets\DatatableAsset::register($this);
\app\assets\Select2Asset::register($this);
\app\assets\DatepickerAsset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'PO Yang Telah Dibuat'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/index"); ?>"> <?= Yii::t('app', 'PO Baru'); ?> </a>
                    </li>
					<li class="active">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/podibuat"); ?>"> <?= Yii::t('app', 'PO Dibuat'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/spl/index"); ?>"> <?= Yii::t('app', 'SPL Baru'); ?> </a>
                    </li>
                    <li class="">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/index"); ?>"> <?= Yii::t('app', 'SPP Masuk'); ?> </a>
                    </li>
                    <li class="">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/sppmasuk"); ?>"> <?= Yii::t('app', 'SPP Masuk Detail'); ?> </a>
                    </li>
                    <li class="">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/sppberes"); ?>"> <?= Yii::t('app', 'SPP Complete'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/dpbhp/index"); ?>"> <?= Yii::t('app', 'Downpayment'); ?> </a>
                    </li>
                </ul>
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet light bordered form-search">
							<div class="portlet-title">
								<div class="tools panel-cari">
									<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
									<span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
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
									<div class="row" style="margin-bottom: 10px;">
										<div class="col-md-6">
											<div class="form-group field-tspodetail-status">
												<label class="col-md-3 control-label" for="tspodetail-status">Status</label>
												<div class="col-md-8">
													<select id="tspodetail-status" class="form-control" name="TSpoDetail[status]" style="width:200px;" onchange="dropdownStatusColor()">
														<option value="">All</option>
														<option value="UNCOMPLETED" style="background-color: #FBE88C" selected="">UNCOMPLETED</option>
														<option value="COMPLETE" style="background-color: #95EBA3">COMPLETE</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div id="place-advancesearch">
										<div class="row">
											<div class="col-md-6">
												<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
												<?= $form->field($model, 'bhp_nm')->textInput(['placeholder'=>'Cari Berdasarkan Nama Item'])->label(Yii::t('app', 'Nama Items')); ?>
											</div>
											<div class="col-md-5">
												<?= $form->field($model, 'spo_kode')->textInput(['placeholder'=>'Cari Berdasarkan Kode SPP'])->label(Yii::t('app', 'Kode SPO')); ?>
												<?= $form->field($model, 'suplier_id')->dropDownList([],['prompt'=>'All','class'=>'select2'])->label(Yii::t('app', 'Supplier')); ?>
											</div>
										</div>
										<div class="row" style="margin-top: -45px; margin-right: -30px;">
											<div class="col-md-1 pull-right" style="position: relative;">
												<?php echo \yii\helpers\Html::button( Yii::t('app', 'Search'),[
													'class'=>'btn hijau btn-outline ciptana-spin-btn pull-right',
													'type'=>'button',
													'name'=>'search-laporan',
													'onclick'=>'getItems()',
													]);
												?>
											</div>
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
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'List SPO Yang Pernah Dibuat'); ?></span>
                                </div>
								<div class="pull-right">
									<a class="btn btn-default btn-sm tooltips" onclick="riwayatPenerimaan()"><i class="fa fa-list"></i> Riwayat Penerimaan</a>
								</div>
                            </div>
                            <div class="portlet-body">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover" id="table-list" style="width: 100%;">
										<thead>
											<tr>
												<th style="text-align: center; width: 2%;"><?= Yii::t('app', 'No.'); ?></th>
												<th style="text-align: center; width: 10%;"><?= Yii::t('app', 'Kode SPO'); ?></th>
												<th style="text-align: center; width: 8%;"><?php echo Yii::t('app', 'Tanggal'); ?></th>
												<th style="text-align: center; width: 12%;"><?= Yii::t('app', 'Item'); ?></th>
												<th style="text-align: center; width: 2%; font-size: 0.9rem;"><?= Yii::t('app', 'Qty<br> PO'); ?></th>
												<th style="text-align: center; width: 2%; font-size: 0.9rem;"><?= Yii::t('app', 'Qty<br> Terbeli'); ?></th>
												<th style="text-align: center; width: 2%; font-size: 0.9rem;"><?= Yii::t('app', 'Status<br>Garansi'); ?></th>
												<th style="text-align: center; width: 15%;"><?= Yii::t('app', 'Supplier'); ?></th>
												<th style="text-align: center; width: 5%;"><?= Yii::t('app', 'Status'); ?></th>
												<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
												<th style="text-align: center; width: 2%; font-size: 1rem;"><?= Yii::t('app', 'Spb'); ?></th>
												<th style="text-align: center; width: 2%; font-size: 1rem;"><?= Yii::t('app', 'Reff<br>Penerimaan'); ?></th>
											</tr>
										</thead>
										<tbody>
											<tr><td colspan="12" style="text-align: center;"><?= Yii::t('app', 'No Data Available'); ?></td></tr>
										</tbody>
									</table>
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
formconfig();
getItems(); 
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Bahan Pembantu'))."');
dropdownStatusColor();
$('#".\yii\bootstrap\Html::getInputId($model, 'suplier_id')."').select2({
	allowClear: !0,
	placeholder: 'Cari Berdasarkan Supplier',
	width: '245px',
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
", yii\web\View::POS_READY); ?>
<script>
function getItems(){
	$('#table-list > tbody').addClass('animation-loading');
	var formdata = $('#form-search').serialize();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/PodibuatGetItems']); ?>',
        type   : 'POST',
        data   : {search:true,formdata:formdata},
        success: function (data){
			if(data.html){
				$('#table-list > tbody').html("");
				$('#table-list > tbody').html(data.html);
				$('#table-list > tbody').removeClass('animation-loading');
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function spbTerkait(sppd_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/spbTerkait','id'=>'']) ?>'+sppd_id,'modal-spb-terkait',null,'');
}

function infoSPB(spb_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpb','id'=>'']) ?>'+spb_id;
	var modal_id = 'modal-info-spb';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function riwayatPenerimaan(){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/riwayatPenerimaan']) ?>','modal-riwayat','85%');
}
function infoTBP(terima_bhp_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id+'&bhp_id='+bhp_id,'modal-info-tbp','75%');
}
function infoReturBHP(spo_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoReturBHP']) ?>?id='+spo_id,'modal-info-spo','75%','');
}
function dropdownStatusColor(){
	var status = $('#<?= \yii\bootstrap\Html::getInputId($model, 'status') ?>').val();
	var color = "";
	if(status == 'UNCOMPLETED'){
		color = "#FBE88C";
	}else if(status == 'COMPLETE'){
		color = "#95EBA3";
	}
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'status') ?>').css('background-color',color);
}
</script>