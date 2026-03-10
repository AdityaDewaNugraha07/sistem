<?php
/* @var $this yii\web\View */
$this->title = 'Saldo Supplier Log';
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
    'id' => 'form-grader',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-3 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Listing Saldo Supplier Log'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <?php echo yii\helpers\Html::dropDownList("tipe_suplier",$tipe_suplier,["LS"=>"Log Sengon","LA"=>"Log Alam"],['prompt'=>'All','class'=>'','disabled'=>true,'onchange'=>'getItems()']) ?>
                            <a class="btn btn-icon-only btn-default tooltips pull-right" onclick="printlist('PRINT')" data-original-title="Print Out"><i class="fa fa-print"></i></a>
                            <div class="portlet-body" style="margin-top: -10px;">
								<div class="row">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
														<th style="width: 220px; font-size: 1.3rem;"><?= Yii::t('app', 'Nama'); ?></th>
														<th style="font-size: 1.3rem;"><?= Yii::t('app', 'Alamat'); ?></th>
														<th style="width: 120px; font-size: 1.3rem;"><?= Yii::t('app', 'Sisa Saldo'); ?></th>
														<th style="width: 120px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Waktu Transaksi<br>Terakhir'); ?></th>
														<th style="width: 140px; text-align: center;"><?= Yii::t('app', ''); ?></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
												<tfoot>
													
												</tfoot>
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
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['dkg_id'])){
    $pagemode = "";
}else{
    $pagemode = "getItems();";
}
if( Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER ){
    $pagemode .= "$('select[name*=\"tipe_suplier\"]').prop('disabled',false)";
}
?>
<?php $this->registerJs(" 
    $pagemode;
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Saldo Supplier Log'))."');
", yii\web\View::POS_READY); ?>
<script>
function getItems(){
    var tipe = $('select[name*="tipe_suplier"]').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/saldosuplierlog/index']); ?>',
		type   : 'POST',
		data   : {getItems:true,tipe:tipe},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
                $('#table-detail > tbody > tr').each(function(){
                    $(this).find(".tooltips").tooltip({ delay: 50 });
                });
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function infoSuplier(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/logistik/supplier/info','id'=>'']) ?>'+id,'modal-supplier-info');
}
function riwayatSaldo(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/saldosuplierlog/riwayatSaldo','id'=>'']) ?>'+id,'modal-riwayatsaldo','80%');
}
</script>