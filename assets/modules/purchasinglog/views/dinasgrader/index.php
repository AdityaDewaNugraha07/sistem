<?php
/* @var $this yii\web\View */
$this->title = 'Grader';
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
				<ul class="nav nav-tabs">
                    <li class="active">
						<a href="<?= yii\helpers\Url::toRoute("/purchasinglog/dinasgrader/index"); ?>"> <?= Yii::t('app', 'Dinas Grader'); ?> </a>
                    </li>
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasinglog/dinasgrader/history"); ?>"> <?= Yii::t('app', 'History Dinas Grader'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'List Grader Dinas Aktif'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
														<th style="width: 100px; font-size: 1.3rem;"><?= Yii::t('app', 'Kode Dinas'); ?></th>
														<th style="width: 80px; font-size: 1.3rem;"><?= Yii::t('app', 'Tipe Dinas'); ?></th>
														<th style="width: 200px; font-size: 1.3rem;"><?= Yii::t('app', 'Nama Grader'); ?></th>
														<th style="width: 90px; font-size: 1.3rem;"><?= Yii::t('app', 'Wilayah'); ?></th>
														<th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Saldo<br>Uang Dinas'); ?></th>
														<th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Saldo<br>Uang Makan'); ?></th>
														<th style="width: 90px; font-size: 1.3rem;"><?= Yii::t('app', 'Status'); ?></th>
														<th style="text-align: center;"><?= Yii::t('app', ''); ?></th>
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
                        <div class="form-actions pull-left">
							<a class="btn btn-sm blue-hoki btn-outline" id="btn-add-item" onclick="addDKG();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Buat Dinas Kerja Grader'); ?></a>
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
?>
<?php $this->registerJs(" 
    $pagemode;
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Grader'))."');
", yii\web\View::POS_READY); ?>
<script>
function getItems(){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/index']); ?>',
		type   : 'POST',
		data   : {getItems:true},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function addDKG(){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/CreateDkg']) ?>','modal-transaksi','75%');
}
function editDKG(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/editDkg']) ?>?id='+id,'modal-transaksi','75%');
}
function biayaBiaya(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/biayaBiaya','id'=>'']) ?>'+id,'modal-transaksi','90%','getItems();');
}
function deleteItem(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/deleteItem','id'=>''])?>'+id,'modal-delete-record');
}
function refresh(){
	getItems();
}
function detailBiaya(dkg_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/detailBiaya','dkg_id'=>'']) ?>'+dkg_id,'modal-transaksi','80%');
}
function changeStatus(dkg_id,status){
	if(status == '<?= \app\models\TDkg::NON_AKTIF_DINAS ?>'){
		openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/changeStatus','dkg_id'=>'']); ?>'+dkg_id,'modal-chstatus','90%','getItems();');
	}else{
		return false;
	}
}

</script>