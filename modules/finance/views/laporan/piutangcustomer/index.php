<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Piutang Penjualan';
app\assets\DatepickerAsset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Laporan Piutang Penjualan'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-5 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
		
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Laporan Piutang Customer'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-5">
										
										<?= $form->field($model, 'tanggal',[
											'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker" data-date-end-date="+0d">{input} <span class="input-group-btn">
														 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
														 {error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'setLaporan();'])->label("Per Tanggal"); ?>
                                    </div>
                                    <div class="col-md-6"></div>
                                </div><br>
								<div class="row">
                                    <div class="col-md-12" style="padding-left: 3px; padding-right: 3px;">
										<div class="portlet light custom-bordered">
											<div class="portlet-title">
												<div class="caption" style="font-size: 1.6rem;"><?= Yii::t('app', 'Table Piutang Customer Per Tanggal '); ?><b><span id="place-labelpertanggal"></span></b></div>
												<div class="pull-right" style="margin-right: -10px;">
													<a class="btn btn-icon-only btn-default tooltips" onclick="printout('PRINT')" data-original-title="Print Out"><i class="fa fa-print"></i></a>
													<a class="btn btn-icon-only btn-default tooltips" onclick="printout('PDF')" data-original-title="Export to PDF"><i class="fa fa-files-o"></i></a>
													<a class="btn btn-icon-only btn-default tooltips" onclick="printout('EXCEL')" data-original-title="Export to Excel"><i class="fa fa-table"></i></a>
												</div>
											</div>
											<div class="portlet-body" id="showLaporan" style="margin-left: -15px; margin-right: -15px; margin-top: -10px;">
												<i style="font-size: 1.2rem;"><?= Yii::t('app', 'Data Tidak Ditemukan'); ?></i>
											</div>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php $this->registerJs(" 
	formconfig();
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Laporan Piutang Penjualan'))."');
	$('#".yii\bootstrap\Html::getInputId($model, 'tanggal')."').datepicker({
        rtl: App.isRTL(),
        orientation: 'left',
        autoclose: !0,
        format: 'dd/mm/yyyy',
        clearBtn:false,
        todayHighlight:true,
		endDate:'+0d'
    })
", yii\web\View::POS_READY); ?>
<script>
function setLaporan(){
	$('#showLaporan').addClass("animation-loading");
	$('#place-labelpertanggal').html("");
	var tgl = $("#<?= yii\bootstrap\Html::getInputId($model, "tanggal") ?>").val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/piutangcustomer/index']); ?>',
		type   : 'GET',
		data   : {getLaporan:true,tgl:tgl},
		success: function (data) {
			if(data.html){
				$('#showLaporan').html(data.html);
				$('#place-labelpertanggal').html(data.tgl);
                $(".tooltips").tooltip({ delay: 50 });
			}
			$('#showLaporan').removeClass("animation-loading");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function printout(caraprint){
	var tgl = $("#<?= yii\bootstrap\Html::getInputId($model, "tanggal") ?>").val();
	window.open("<?= yii\helpers\Url::toRoute('/finance/piutangcustomer/printout') ?>?tgl="+tgl+"&caraprint="+caraprint,"",'location=_new, width=1200px, scrollbars=yes');
}

function infoNota(kode){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/infoNota']) ?>?kode='+kode,'modal-info-nota','21.5cm');
}
function infoInvoice(id){
    openModal("<?= \yii\helpers\Url::toRoute(['/exim/invoice/print']) ?>?id="+id+"&caraprint=MODAL",'modal-print','21.5cm');
}
</script>