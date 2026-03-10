<?php
/* @var $this yii\web\View */
$this->title = 'Site Configuration';
app\assets\DatatableAsset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-site',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.dashboard-stat2 {
    background: #ced3d8 none repeat scroll 0 0;
}
.dashboard-stat2 .display .number small,
.dashboard-stat2 .progress-info .status{
    color: #7e8287;
	font-size: 12px;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/index"); ?>"> <?= Yii::t('app', 'General'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/systemconfig"); ?>"> <?= Yii::t('app', 'System Config'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/pengumuman/index"); ?>"> <?= Yii::t('app', 'Pengumuman'); ?> </a>
                    </li>
					<li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/servermonitor"); ?>"> <?= Yii::t('app', 'Server Monitor'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/extensiontelepon/index"); ?>"> <?= Yii::t('app', 'Extension Telepon'); ?> </a>
                    </li>
                </ul>
				<div class="row">
<!--                    <div class="col-md-7">
						<div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject hijau bold"><?php // echo Yii::t('app', 'Server Information'); ?></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row" style="font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace; font-size: 12px;line-height: 12px;">
                                    <div class="col-md-6" id="place-info1"></div>
                                    <div class="col-md-6" id="place-info2"></div>
								</div>
                            </div>
                        </div>
					</div>-->
					<div class="col-md-12">
						<div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'CIS Server Real Time Monitoring'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
									<div class="col-lg-2">
										<div class="dashboard-stat2 ">
											<div class="display" style="margin-bottom: 0px; height: 25px;">
												<div class="number">
													<h4 class="font-blue-steel" id="place-physical" style="font-weight: 500; margin: 0px;"></h4>
													<small>CPU Physical</small>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="dashboard-stat2 ">
											<div class="display" style="margin-bottom: 0px; height: 25px;">
												<div class="number">
													<h4 class="font-blue-steel" id="place-pci_adapter" style="font-weight: 500; margin: 0px;"></h4>
													<small>PCI adapter</small>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="dashboard-stat2 ">
											<div class="display" style="margin-bottom: 0px; height: 25px;">
												<div class="number">
													<h4 class="font-blue-steel" id="place-core_0" style="font-weight: 500; margin: 0px;"></h4>
													<small>CPU CORE 1</small>
												</div>
											</div>
<!--											<div class="progress-info">
												<div class="progress">
													<span style="width: 76%;" class="progress-bar progress-bar-success blue-steel">
														<span class="sr-only">76% progress</span>
													</span>
												</div>
												<div class="status">
													<div class="status-title"> progress </div>
													<div class="status-number"> 76% </div>
												</div>
											</div>-->
										</div>
									</div>
									<div class="col-lg-2">
										<div class="dashboard-stat2 ">
											<div class="display" style="margin-bottom: 0px; height: 25px;">
												<div class="number">
													<h4 class="font-blue-steel" id="place-core_1" style="font-weight: 500; margin: 0px;"></h4>
													<small>CPU CORE 2</small>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="dashboard-stat2 ">
											<div class="display" style="margin-bottom: 0px; height: 25px;">
												<div class="number">
													<h4 class="font-blue-steel" id="place-core_2" style="font-weight: 500; margin: 0px;"></h4>
													<small>CPU CORE 3</small>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="dashboard-stat2 ">
											<div class="display" style="margin-bottom: 0px; height: 25px;">
												<div class="number">
													<h4 class="font-blue-steel" id="place-core_3" style="font-weight: 500; margin: 0px;"></h4>
													<small>CPU CORE 4</small>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<!-- BEGIN PORTLET-->
										<div class="portlet light bordered" style="background: #ced3d8 none repeat scroll 0 0;">
											<div class="portlet-title">
												<div class="caption">
													<span class="caption-subject font-dark uppercase">CPU Load</span>
												</div>
											</div>
											<div class="portlet-body">
												<div class="dashboard-stat2 ">
													<div class="progress-info">
														<div class="progress">
															<span class="progress-bar progress-bar-success blue-steel"></span>
														</div>
														<div class="status" style="margin-top: 0px; margin-bottom: 30px;">
															<div class="status-title"> CPU </div><div class="status-number" id="place-cpu" style="color: #555"></div>
														</div>
													</div>
													<div class="progress-info">
														<div class="progress">
															<span class="progress-bar progress-bar-success blue-steel"></span>
														</div>
														<div class="status" style="margin-top: 0px; margin-bottom: 30px;">
															<div class="status-title"> CPU0 </div><div class="status-number" id="place-cpu0" style="color: #555"></div>
														</div>
													</div>
													<div class="progress-info">
														<div class="progress">
															<span class="progress-bar progress-bar-success blue-steel"></span>
														</div>
														<div class="status" style="margin-top: 0px; margin-bottom: 30px;">
															<div class="status-title"> CPU1 </div><div class="status-number" id="place-cpu1" style="color: #555"></div>
														</div>
													</div>
													<div class="progress-info">
														<div class="progress">
															<span class="progress-bar progress-bar-success blue-steel"></span>
														</div>
														<div class="status" style="margin-top: 0px; margin-bottom: 30px;">
															<div class="status-title"> CPU2 </div><div class="status-number" id="place-cpu2" style="color: #555"></div>
														</div>
													</div>
													<div class="progress-info">
														<div class="progress">
															<span class="progress-bar progress-bar-success blue-steel"></span>
														</div>
														<div class="status" style="margin-top: 0px; margin-bottom: 30px;">
															<div class="status-title"> CPU3 </div><div class="status-number" id="place-cpu3" style="color: #555"></div>
														</div>
													</div>
													<div class="progress-info">
														<div class="progress">
															<span class="progress-bar progress-bar-success blue-steel"></span>
														</div>
														<div class="status" style="margin-top: 0px; margin-bottom: 30px;">
															<div class="status-title"> CPU4 </div><div class="status-number" id="place-cpu4" style="color: #555"></div>
														</div>
													</div>
													<div class="progress-info">
														<div class="progress">
															<span class="progress-bar progress-bar-success blue-steel"></span>
														</div>
														<div class="status" style="margin-top: 0px; margin-bottom: 30px;">
															<div class="status-title"> CPU5 </div><div class="status-number" id="place-cpu5" style="color: #555"></div>
														</div>
													</div>
													<div class="progress-info">
														<div class="progress">
															<span class="progress-bar progress-bar-success blue-steel"></span>
														</div>
														<div class="status" style="margin-top: 0px; margin-bottom: 30px;">
															<div class="status-title"> CPU6 </div><div class="status-number" id="place-cpu6" style="color: #555"></div>
														</div>
													</div>
													<div class="progress-info">
														<div class="progress">
															<span class="progress-bar progress-bar-success blue-steel"></span>
														</div>
														<div class="status" style="margin-top: 0px; margin-bottom: 30px;">
															<div class="status-title"> CPU7 </div><div class="status-number" id="place-cpu7" style="color: #555"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- END PORTLET-->
									</div>
									<div class="col-lg-8">
										<!-- BEGIN PORTLET-->
										<div class="portlet light bordered" style="background: #ced3d8 none repeat scroll 0 0;">
											<div class="portlet-title">
												<div class="caption">
													<span class="caption-subject font-dark uppercase">Network</span>
												</div>
											</div>
											<div class="portlet-body">
												<div id="site_activities_loading">
													<img src="<?= $this->theme->baseUrl ?>/global/img/loading.gif" alt="loading" /> </div>
												<div id="site_activities_content" class="display-none">
													<div id="site_activities" style="height: 228px;"> </div>
												</div>
												<div style="margin: 20px 0 10px 30px">
													<div class="row">
														<div class="col-md-3 col-sm-3 col-xs-6 text-stat">
															<span class="label label-sm label-success"> Revenue: </span>
															<h3>$13,234</h3>
														</div>
														<div class="col-md-3 col-sm-3 col-xs-6 text-stat">
															<span class="label label-sm label-info"> Tax: </span>
															<h3>$134,900</h3>
														</div>
														<div class="col-md-3 col-sm-3 col-xs-6 text-stat">
															<span class="label label-sm label-danger"> Shipment: </span>
															<h3>$1,134</h3>
														</div>
														<div class="col-md-3 col-sm-3 col-xs-6 text-stat">
															<span class="label label-sm label-warning"> Orders: </span>
															<h3>235090</h3>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- END PORTLET-->
									</div>
								</div>
                            </div>
                        </div>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
						
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs("
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Site'))."');
	formconfig();
//	getSummary();
	getRealtime();
	setInterval(function(){ getRealtime(); }, 1500);
//	setNetwork();
", yii\web\View::POS_READY); ?>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<script>
function getSummary(){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/sysadmin/site/servermonitor']); ?>',
		type   : 'POST',
		data   : {getSummary:true},
		success: function (data) {
			if(data.info1){
				$('#place-info1').html(data.info1);
			}
			if(data.info2){
				$('#place-info2').html(data.info2);
			}
			if(data.temp){
				$('#place-temp').html(data.temp);
			}
			if(data.memory){
				$('#place-memory').html(data.memory);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function getRealtime(){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/sysadmin/site/servermonitor']); ?>',
		type   : 'POST',
		data   : {getRealtime:true},
		success: function (data) {
			if(data.temp){
				$('#place-physical').html(data.temp.physical);
				$('#place-pci_adapter').html(data.temp.pci_adapter);
				$('#place-core_0').html(data.temp.core_0);
				$('#place-core_1').html(data.temp.core_1);
				$('#place-core_2').html(data.temp.core_2);
				$('#place-core_3').html(data.temp.core_3);
			}
			if(data.cpu){
				var cpu = data.cpu.cpu;
				var cpu0 = data.cpu.cpu0;
				var cpu1 = data.cpu.cpu1;
				var cpu2 = data.cpu.cpu2;
				var cpu3 = data.cpu.cpu3;
				var cpu4 = data.cpu.cpu4;
				var cpu5 = data.cpu.cpu5;
				var cpu6 = data.cpu.cpu6;
				var cpu7 = data.cpu.cpu7;
				$('#place-cpu').html(formatNumberForUser(cpu * 100) +'%');
				$('#place-cpu0').html(formatNumberForUser(cpu0 * 100)+'%');
				$('#place-cpu1').html(formatNumberForUser(cpu1 * 100)+'%');
				$('#place-cpu2').html(formatNumberForUser(cpu2 * 100)+'%');
				$('#place-cpu3').html(formatNumberForUser(cpu3 * 100)+'%');
				$('#place-cpu4').html(formatNumberForUser(cpu4 * 100)+'%');
				$('#place-cpu5').html(formatNumberForUser(cpu5 * 100)+'%');
				$('#place-cpu6').html(formatNumberForUser(cpu6 * 100)+'%');
				$('#place-cpu7').html(formatNumberForUser(cpu7 * 100)+'%');

				$('#place-cpu').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu * 100)+'%');
				$('#place-cpu0').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu0 * 100)+'%');
				$('#place-cpu1').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu1 * 100)+'%');
				$('#place-cpu2').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu2 * 100)+'%');
				$('#place-cpu3').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu3 * 100)+'%');
				$('#place-cpu4').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu4 * 100)+'%');
				$('#place-cpu5').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu5 * 100)+'%');
				$('#place-cpu6').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu6 * 100)+'%');
				$('#place-cpu7').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu7 * 100)+'%');
			}
//			if(data.memory){
//				$('#place-memory').html(data.memory);
//			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setNetwork(){
	if (0 != $("#site_activities").size()) {
		var i = null;
		$("#site_activities_loading").hide(), $("#site_activities_content").show();
		var l = [
			["DEC", 300],
			["JAN", 600],
			["FEB", 1100],
			["MAR", 1200],
			["APR", 860],
			["MAY", 1200],
			["JUN", 1450],
			["JUL", 1800],
			["AUG", 1200],
			["SEP", 600]
		];
		$.plot($("#site_activities"), [{
			data: l,
			lines: {
				fill: .2,
				lineWidth: 0
			},
			color: ["#BAD9F5"]
		}, {
			data: l,
			points: {
				show: !0,
				fill: !0,
				radius: 4,
				fillColor: "#9ACAE6",
				lineWidth: 2
			},
			color: "#9ACAE6",
			shadowSize: 1
		}, {
			data: l,
			lines: {
				show: !0,
				fill: !1,
				lineWidth: 3
			},
			color: "#9ACAE6",
			shadowSize: 0
		}], {
			xaxis: {
				tickLength: 0,
				tickDecimals: 0,
				mode: "categories",
				min: 0,
				font: {
					lineHeight: 18,
					style: "normal",
					variant: "small-caps",
					color: "#6F7B8A"
				}
			},
			yaxis: {
				ticks: 5,
				tickDecimals: 0,
				tickColor: "#eee",
				font: {
					lineHeight: 14,
					style: "normal",
					variant: "small-caps",
					color: "#6F7B8A"
				}
			},
			grid: {
				hoverable: !0,
				clickable: !0,
				tickColor: "#eee",
				borderColor: "#eee",
				borderWidth: 1
			}
		});
		$("#site_activities").bind("plothover", function(t, a, l) {
			if ($("#x").text(a.x.toFixed(2)), $("#y").text(a.y.toFixed(2)), l && i != l.dataIndex) {
				i = l.dataIndex, $("#tooltip").remove();
				l.datapoint[0].toFixed(2), l.datapoint[1].toFixed(2);
				e(l.pageX, l.pageY, l.datapoint[0], l.datapoint[1] + "M$")
			}
		}), $("#site_activities").bind("mouseleave", function() {
			$("#tooltip").remove()
		})
	}
}
</script>