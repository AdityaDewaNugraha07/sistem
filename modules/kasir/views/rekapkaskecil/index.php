<?php
/* @var $this yii\web\View */
$this->title = 'Kas Kecil';
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-pengeluaran-kas',
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
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/sementara"); ?>"> <?= Yii::t('app', 'Bon Kas Kecil'); ?> </a>
                    </li>
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/index"); ?>"> <?= Yii::t('app', 'Pengeluaran Kas Kecil'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/saldokaskecil/index"); ?>"> <?= Yii::t('app', 'Laporan Kas Kecil'); ?> </a>
                    </li>
					<li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/rekapkaskecil/index"); ?>"> <?= Yii::t('app', 'Rekap Kas Kecil'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/terimaretur"); ?>"> <?= Yii::t('app', 'Terima Uang Retur'); ?> </a>
                    </li>
                </ul>
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet light bordered">
							<div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Rekap Kas Kecil'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
							<div class="portlet-body">
								<div class="row">
                                    <div class="col-md-10">
										<?= $form->field($model, 'tanggal',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'getItems()'])->label('Tanggal'); ?>
                                    </div>
                                    <div class="col-md-2">
										<h4 class="modal-title" id="place-statusclosing"></h4>
                                    </div>
                                </div>
							</div>
						</div>
						<!-- END EXAMPLE TABLE PORTLET-->
					</div>
				</div>
				<style>
				#table-summary tr td{
					padding: 3px;
				}
				</style>
                <div class="row">
					<div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
								<table style="width: 100%;" class="table-striped" id="table-summary">
									<tr>
										<td style="width: 35%;"><h4><?= Yii::t('app', 'SALDO AWAL : '); ?></h4></td>
										<td style="text-align: right; width: 65%;">
											<h4><span style="font-weight: bold;" id="place-saldoawal">-</span></h4>
										</td>
									</tr>
									<tr>
										<td>
											<h4><?= Yii::t('app', 'Total Masuk : '); ?></h4>
											<h5> &nbsp; &nbsp; Penambahan Kas</h5>
											<h5> &nbsp; &nbsp; Lain-Lain</h5>
										</td>
										<td style="text-align: right;">
											<h4><span style="font-weight: bold;" id="place-totalmasuk">0</span></h4>
											<h5> &nbsp; &nbsp; <span id="place-penambahankas">0</span></h5>
											<h5> &nbsp; &nbsp; <span id="place-masuklain">0</span></h5>
										</td>
									</tr>
									<tr>
										<td>
											<h4><?= Yii::t('app', 'Total Keluar : '); ?></h4>
											<h5>
												&nbsp; &nbsp; Operasional
												<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoLaporan()"><i class="fa fa-info-circle"></i></a>
											</h5> 
											<h5> 
												&nbsp; &nbsp; Bon Sementara
												<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoKasbon()"><i class="fa fa-info-circle"></i></a>
											</h5>
											<h5> &nbsp; &nbsp; Lain-Lain</h5>
										</td>
										<td style="text-align: right;">
											<h4>
												<span style="font-weight: bold;" id="place-totalkeluar">-</span>
												<h5> &nbsp; &nbsp; <span id="place-operasional">0</span></h5>
												<h5> &nbsp; &nbsp; <span id="place-bonsementara">0</span></h5>
												<h5> &nbsp; &nbsp; <span id="place-keluarlain">0</span></h5>
											</h4>
										</td>
									</tr>
									<tr>
										<td><h4><?= Yii::t('app', 'Bon Kas Besar : '); ?>
												<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoKasbonKasbesar()"><i class="fa fa-info-circle"></i></a>
											</h4></td>
										<td style="text-align: right;">
											<h4><span style="font-weight: bold;" id="place-bonkasbesar">0</span></h4>
										</td>
									</tr>
									<tr>
										<td><h4><?= Yii::t('app', 'Jumlah Uang Tunai : '); ?>
												<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoUangtunai()"><i class="fa fa-info-circle"></i></a>
											</h4></td>
										<td style="text-align: right;">
											<h4><span style="font-weight: bold;" id="place-uangtunai">0</span></h4>
										</td>
									</tr>
									<tr>
										<td><h4><?= Yii::t('app', 'SALDO AKHIR : '); ?></h4></td>
										<td style="text-align: right;">
											<h4><span style="font-weight: bold;" id="place-saldoakhir">0</span></h4>
										</td>
									</tr>
								</table>
                            </div>
                        </div>
                    </div>
					<div class="col-md-2"></div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-actions text-align-center">
								<div class="col-md-12">
									<br><br><br>
									<?php echo \yii\helpers\Html::button( Yii::t('app', 'Tanggal Selisih Closing'),['id'=>'btn-selisihclosing','class'=>'btn btn-sm blue btn-outline','onclick'=>'tanggalSelisihClosing();']); ?>
								</div>
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
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Kas Kecil'))."');
$(\"#".yii\bootstrap\Html::getInputId($model, 'tanggal')."\").datepicker({
	rtl: App.isRTL(),
	orientation: \"left\",
	autoclose: !0,
	format: \"dd/mm/yyyy\",
	clearBtn:false,
	todayHighlight:true
});
checkKasbonKasbesar();
", yii\web\View::POS_READY); ?>
<script>
function getItems(){
	$('#table-list > tbody').addClass('animation-loading');
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/kasir/rekapkaskecil/index']); ?>',
        type   : 'POST',
        data   : {getItems:true,tgl:tgl},
        success: function (data){
			if(typeof(data.saldoawal) != "undefined" && data.saldoawal !== null) {
				$('#place-saldoawal').html( formatNumberForUser(data.saldoawal) );
			}else{ $('#place-saldoawal').html( 0 ); }
			if(typeof(data.penambahankas) != "undefined" && data.penambahankas !== null) {
				$('#place-penambahankas').html( formatNumberForUser(data.penambahankas) );
			}else{ $('#place-penambahankas').html( 0 ); }
			if(typeof(data.masuklain) != "undefined" && data.masuklain !== null) {
				$('#place-masuklain').html( formatNumberForUser(data.masuklain) );
			}else{ $('#place-masuklain').html( 0 ); }
			if(typeof(data.totalmasuk) != "undefined" && data.totalmasuk !== null) {
				$('#place-totalmasuk').html( formatNumberForUser(data.totalmasuk) );
			}else{ $('#place-totalmasuk').html( 0 ); }
			if(typeof(data.operasional) != "undefined" && data.operasional !== null) {
				$('#place-operasional').html( formatNumberForUser(data.operasional) );
			}else{ $('#place-operasional').html( 0 ); }
			if(typeof(data.bonsementara) != "undefined" && data.bonsementara !== null) {
				$('#place-bonsementara').html( formatNumberForUser(data.bonsementara) );
			}else{ $('#place-bonsementara').html( 0 ); }
			if(typeof(data.keluarlain) != "undefined" && data.keluarlain !== null) {
				$('#place-keluarlain').html( formatNumberForUser(data.keluarlain) );
			}else{ $('#place-keluarlain').html( 0 ); }
			if(typeof(data.totalkeluar) != "undefined" && data.totalkeluar !== null) {
				$('#place-totalkeluar').html( formatNumberForUser(data.totalkeluar) );
			}else{ $('#place-totalkeluar').html( 0 ); }
			if(typeof(data.bonkasbesar) != "undefined" && data.bonkasbesar !== null) {
				$('#place-bonkasbesar').html( formatNumberForUser(data.bonkasbesar) );
			}else{ $('#place-bonkasbesar').html( 0 ); }
			if(typeof(data.uangtunai) != "undefined" && data.uangtunai !== null) {
				$('#place-uangtunai').html( formatNumberForUser(data.uangtunai) );
			}else{ $('#place-uangtunai').html( 0 ); }
			if(typeof(data.saldoakhir) != "undefined" && data.saldoakhir !== null) {
				$('#place-saldoakhir').html( formatNumberForUser(data.saldoakhir) );
			}else{ $('#place-saldoakhir').html( 0 ); }
			if(typeof(data.statusclosing) != "undefined" && data.statusclosing !== null) {
				if(data.statusclosing == 1){
					$('#place-statusclosing').html( '<strong style="background-color:#c8da8e">Sudah Closing</strong>' );
					$('.blue-hoki').removeAttr('disabled');
				}else{
					$('#place-statusclosing').html( '<strong style="background-color:#FBE88C">Belum Closing</strong>' );
					$('.blue-hoki').attr('disabled','disabled');
				}
			}else{ 
				$('#place-statusclosing').html( "" ); 
				$('.blue-hoki').attr('disabled','disabled');
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function printout(caraPrint,tgl){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/rekapkaskecil/PrintoutLaporan') ?>?tgl="+tgl+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
function checkKasbonKasbesar(){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/checkKasbonKasbesar']); ?>';
	$(".modals-place-confirm").load(url, function() {
		$("#modal-global-confirm").modal('show');
		$("#modal-global-confirm").on('hidden.bs.modal', function () {
			location.reload();
		});
		spinbtn();
		draggableModal();
	});
}
function infoLaporan(){
	var tgl = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/rekapkaskecil/getRekapByTanggal','tgl'=>'']); ?>'+tgl+'&info=laporan';
	$(".modals-place-2").load(url, function() {
		$("#modal-rekap").modal('show');
		$("#modal-rekap").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
function infoKasbon(){
	var tgl = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/rekapkaskecil/rekapKaskecil','tgl'=>'']); ?>'+tgl+'&info=kasbon';
	$(".modals-place-2").load(url, function() {
		$("#modal-kasbon").modal('show');
		$("#modal-kasbon").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
function infoKasbonKasbesar(){
	var tgl = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/rekapkaskecil/rekapKaskecil','tgl'=>'']); ?>'+tgl+'&info=kasbonkasbesar';
	$(".modals-place-2").load(url, function() {
		$("#modal-kasbonkasbesar").modal('show');
		$("#modal-kasbonkasbesar").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
function infoUangtunai(){
	var tgl = $('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/rekapPraClosing','tgl'=>'']); ?>'+tgl+'&info=uangtunai';
	$(".modals-place-2").load(url, function() {
		$("#modal-uangtunai").modal('show');
		$("#modal-uangtunai .modal-dialog").css('width','400px');
		$("#modal-uangtunai").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}

function printKasbon(caraprint,tgl){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/rekapkaskecil/Printout') ?>?tgl="+tgl+"&caraprint="+caraprint+"&tipe=kasbon","",'location=_new, width=1000px, scrollbars=yes');
}
function printKasbonKasbesar(caraprint,tgl){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/rekapkaskecil/Printout') ?>?tgl="+tgl+"&caraprint="+caraprint+"&tipe=kasbonkasbesar","",'location=_new, width=1000px, scrollbars=yes');
}

function tanggalSelisihClosing(){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/rekapkaskecil/tanggalSelisihClosing']) ?>','modal-tanggalselisihclosing');
}
</script>