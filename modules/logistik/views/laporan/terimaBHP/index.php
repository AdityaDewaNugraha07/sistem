<?php
/* @var $this yii\web\View */
$this->title = 'Analisa Penerimaan Bahan Pembantu';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>

<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model,'tgl_awal'=>$tgl_awal,'tgl_akhir'=>$tgl_akhir,'cari_kode'=>$cari_kode]) ?>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-list"></i>
								<span class="caption-subject hijau bold"><?= Yii::t('app', 'Analisa Penerimaan Bahan Pembantu'); ?></span>
							</div>
							<div class="caption pull-right">
								<a class="btn btn-icon-only btn-default tooltips" onclick="printout('PRINT')" data-original-title="Print Out"><i class="fa fa-print"></i></a>
								<?php /* <a class="btn btn-icon-only btn-default tooltips" onclick="printout('PDF')" data-original-title="Export to PDF"><i class="fa fa-files-o"></i></a>*/?>
								<a class="btn btn-icon-only btn-default tooltips" onclick="printout('EXCEL')" data-original-title="Export to Excel"><i class="fa fa-table"></i></a>
							</div>
						</div>
						<div id="xxx" class="portlet-body loading" id="ajax" style="margin-left: -15px; margin-right: -15px;">
							<?php echo $this->render('_show', array('model'=>$model,'tgl_awal'=>$tgl_awal,'tgl_akhir'=>$tgl_akhir,'cari_kode'=>$cari_kode)); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>

<?php $this->registerJs(" 
formconfig();
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Laporan Terima Bahan Pembantu')) . "');
$('#form-search').submit( function(e){
	e.preventDefault();
	var form = $(this);
	var formData = form.serialize();
	$('.loading').addClass('animation-loading');
	$.ajax({
		url    : '".\yii\helpers\Url::toRoute(['/logistik/laporan/terimaBHPAjax'])."',
		type   : 'POST',
		data   : formData,
		success: function (data) {
			if(data){
				$(data).each(function(){
					var tgl_awal = $(this)[0].tgl_awal;
					var tgl_akhir = $(this)[0].tgl_akhir;
					var cari_kode = $(this)[0].cari_kode;
					$('#loading').show();
					$('#xxx').load('".\yii\helpers\Url::toRoute(['/logistik/laporan/terimaBHPShow'])." div#yyy', {tgl_awal:tgl_awal, tgl_akhir:tgl_akhir, cari_kode:cari_kode}, function () {
						if ($('#beres').length) {
							$('.loading').removeClass('animation-loading');
						} else {
							$('.loading').addClass('animation-loading');
						}
					});
				});
			}
		},
		complete: function(){
			//$('.loading').removeClass('animation-loading');
		},	
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
	return false;        
})
", yii\web\View::POS_READY); ?>

<script>
function infoTBP(terima_bhp_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id+'&bhp_id='+bhp_id,'modal-info-tbp','75%');
}
function infoSPO(spo_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpo']) ?>?id='+spo_id+"&bhp_id="+bhp_id,'modal-info-spo','75%','');
}
function infoSPL(spl_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpl']) ?>?id='+spl_id+"&bhp_id="+bhp_id,'modal-info-spl','75%','');
}
function infoSPP(spp_id,spo_id,spl_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpp']) ?>?id='+spp_id+'&spo_id='+spo_id+'&spl_id='+spl_id,'modal-info-spp','75%','getSPP();');
}
function infoSPB(spb_id,spo_id,spl_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpb']) ?>?id='+spb_id+'&spo_id='+spo_id+'&spl_id='+spl_id,'modal-info-spb','75%','getSPB();');
}
function printout(caraPrint){
	var tgl_awal = $('#tsppdetail-tgl_awal').val();
	var tgl_akhir = $('#tsppdetail-tgl_akhir').val();
	var cari_kode = $('#tsppdetail-cari_kode').val();
	//alert(tgl_awal+' '+tgl_akhir+' '+cari_kode)
	window.open("<?= yii\helpers\Url::toRoute('/logistik/laporan/terimaBHPPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint+"&tgl_awal="+tgl_awal+"&tgl_akhir="+tgl_akhir+"&cari_kode="+cari_kode,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>