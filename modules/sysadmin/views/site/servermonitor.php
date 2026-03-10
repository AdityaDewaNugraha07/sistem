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
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/extensiontelepon/index"); ?>"> <?= Yii::t('app', 'Extension Telepon'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/servermonitor"); ?>"> <?= Yii::t('app', 'Server Monitor'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/servermonitors"); ?>"> <?= Yii::t('app', 'Backup Monitor'); ?> </a>
                    </li>
					<li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/cdb"); ?>"> <?= Yii::t('app', 'Compare Database'); ?> </a>
                    </li>
                </ul>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                <span class="caption-subject hijau bold"><?= Yii::t('app', 'Real Time Monitoring Server : 10.10.10.2'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <iframe src="http://10.10.10.2:19999/master.html" title="Netdata" style="width: 1000px; height: 950px;" frameBorder="0"></iframe>
                                    </div>
                                </div>

                                <!-- 10.10.10.2 -->
								<div class="row">
									<div class="col-lg-2">
										<div class="dashboard-stat2 ">
											<div class="display" style="margin-bottom: 0px; height: 100px;">
												<div class="number">
													<a href="http://10.10.10.2:19999/index.html" target="_blank"><img class="img-responsive text-center" src="http://10.10.10.2/cis/web/themes/metronic/cis/img/netdata2.png"></a>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="dashboard-stat2 ">
											<div class="display" style="margin-bottom: 0px; height: 100px;">
												<div class="number">
													<a href="http://10.10.10.2/ext/apache2access.html" target="_blank"><img class="img-responsive text-center img-rounded" src="http://10.10.10.2/cis/web/themes/metronic/cis/img/goaccess2.png"></a>
												</div>
											</div>
										</div>
									</div>
								</div>	
								<!-- eo 10.10.10.2 -->

								<!-- 10.10.10.3 -->
								<div class="row">
									<div class="col-lg-2">
										<div class="dashboard-stat2 ">
											<div class="display" style="margin-bottom: 0px; height: 100px;">
												<div class="number">
													<a href="http://10.10.10.3:19999/index.html" target="_blank"><img class="img-responsive text-center" src="http://10.10.10.2/cis/web/themes/metronic/cis/img/netdata3.png"></a>
												</div>
											</div>
										</div>
									</div>
									<?php /*<div class="col-lg-2">
										<div class="dashboard-stat2 ">
											<div class="display" style="margin-bottom: 0px; height: 100px;">
												<div class="number">
													<a href="http://10.10.10.3/ext/apache2access.html" target="_blank"><img class="img-responsive text-center img-rounded" src="http://10.10.10.2/cis/web/themes/metronic/cis/img/goaccess3.png"></a>
												</div>
											</div>
										</div>
									</div>
									*/?>
								</div>	
								<!-- eo 10.10.10.3 -->														
                            </div>
                        </div>
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
				//$('#place-pci_adapter').html(data.temp.pci_adapter);
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
				//var cpu4 = data.cpu.cpu4;
				//var cpu5 = data.cpu.cpu5;
				//var cpu6 = data.cpu.cpu6;
				//var cpu7 = data.cpu.cpu7;
				$('#place-cpu').html(formatNumberForUser(cpu * 100) +'%');
				$('#place-cpu0').html(formatNumberForUser(cpu0 * 100)+'%');
				$('#place-cpu1').html(formatNumberForUser(cpu1 * 100)+'%');
				$('#place-cpu2').html(formatNumberForUser(cpu2 * 100)+'%');
				$('#place-cpu3').html(formatNumberForUser(cpu3 * 100)+'%');
				//$('#place-cpu4').html(formatNumberForUser(cpu4 * 100)+'%');
				//$('#place-cpu5').html(formatNumberForUser(cpu5 * 100)+'%');
				//$('#place-cpu6').html(formatNumberForUser(cpu6 * 100)+'%');
				//$('#place-cpu7').html(formatNumberForUser(cpu7 * 100)+'%');

				$('#place-cpu').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu * 100)+'%');
				$('#place-cpu0').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu0 * 100)+'%');
				$('#place-cpu1').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu1 * 100)+'%');
				$('#place-cpu2').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu2 * 100)+'%');
				$('#place-cpu3').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu3 * 100)+'%');
				//$('#place-cpu4').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu4 * 100)+'%');
				//$('#place-cpu5').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu5 * 100)+'%');
				//$('#place-cpu6').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu6 * 100)+'%');
				//$('#place-cpu7').parents('.status').siblings('.progress').find('.progress-bar').css('width',(cpu7 * 100)+'%');
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