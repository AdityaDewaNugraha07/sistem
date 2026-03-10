<?php
/* @var $this yii\web\View */
$this->title = 'Grader';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
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
<style>
#table-detail > tbody > tr > td{
	font-size: 1.1rem;
}
</style>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasinglog/dinasgrader/index"); ?>"> <?= Yii::t('app', 'Dinas Grader'); ?> </a>
                    </li>
                    <li class="active">
						<a href="<?= yii\helpers\Url::toRoute("/purchasinglog/dinasgrader/history"); ?>"> <?= Yii::t('app', 'History Dinas Grader'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Riwayat Grader Pergi Dinas'); ?></h4></span>
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
														<th style="width: 110px; font-size: 1.3rem;"><?= Yii::t('app', 'Kode Dinas'); ?></th>
														<th style="width: 70px; font-size: 1.3rem;"><?= Yii::t('app', 'Tipe<br>Dinas'); ?></th>
														<th style="font-size: 1.3rem;"><?= Yii::t('app', 'Nama Grader'); ?></th>
														<th style="width: 90px; font-size: 1.3rem;"><?= Yii::t('app', 'Wilayah'); ?></th>
														<th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Sisa Saldo<br>Uang Dinas'); ?></th>
														<th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Sisa Saldo<br>Uang Makan'); ?></th>
														<th style="width: 120px; font-size: 1.3rem;"><?= Yii::t('app', 'Status'); ?></th>
														<th style="width: 90px; font-size: 1.3rem;"><?= Yii::t('app', 'Tanggal<br>Selesai Dinas'); ?></th>
														<th style="width: 90px; text-align: center;"><?= Yii::t('app', ''); ?></th>
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
    $pagemode = "dtLaporan();";
}
?>
<?php $this->registerJs(" 
    $pagemode;
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Grader'))."');
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-detail').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/dinasgrader/history') ?>',data:{dt: 'table-detail'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            { 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 5, 
				class: "text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{	targets: 6, 
				class: "text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{	targets: 8, 
				class: "text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(full[8]);
					date = date.toString('dd/MM/yyyy');
					return date;
                }
            },
			{	targets: 9, 
				orderable: false,
                render: function ( data, type, full, meta ) {
					return "<center><a onclick='detailBiaya("+full[0]+")' class='btn btn-outline grey-gallery btn-xs' style='font-size: 1.1rem;'> Detail Dinas</a></center>";
                }
            }
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function getItems(){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/history']); ?>',
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

function detailBiaya(dkg_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/detailBiaya','dkg_id'=>'']) ?>'+dkg_id,'modal-transaksi','80%');
}

</script>