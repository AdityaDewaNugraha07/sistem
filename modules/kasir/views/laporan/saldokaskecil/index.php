<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Kas Kecil';
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
		
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet light bordered">
							<div class="portlet-title">
								<div class="tools panel-cari">
									<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
									<span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
								</div>
								<!--<div class="tools font-green-dark" style="font-size: 1.8rem;"><?php // echo Yii::t('app', 'Saldo Akhir Kas Kecil : '); ?><b>Rp. <span id="saldoakhir-place"></span></b></div>-->
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
									<div class="row">
										<div class="col-md-7">
											<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode Transaksi','model' => $model,'form'=>$form]) ?>
										</div>
										<div class="col-md-2"></div>
									</div>
									<?php echo $this->render('@views/apps/form/tombolSearch') ?>
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
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Rincian Pengeluaran Kas Kecil'); ?></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover" id="table-list" style="width: 100%;">
										<thead>
											<tr>
												<th style="text-align: center; width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
												<th style="text-align: center; width: 75px;"><?= Yii::t('app', 'Tanggal'); ?></th>
												<th style="text-align: center; "><?= Yii::t('app', 'Deskripsi'); ?></th>
												<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Debit'); ?></th>
												<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Kredit'); ?></th>
												<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Saldo Akhir'); ?></th>
												<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Rincian'); ?></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th colspan="7" style="text-align: center; border: solid 1px;"><?= Yii::t('app', 'No Data Available'); ?></th>
											</tr>
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
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Laporan Kas Kecil'))."');
formconfig();
getItems(); 
$('#form-search').submit(function(){
	getItems();
	return false;
});
checkKasbonKasbesar();
", yii\web\View::POS_READY); ?>
<script>
function getItems(){
	$('#table-list > tbody').addClass('animation-loading');
	var formdata = $('#form-search').serialize();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/kasir/laporan/saldokaskecilxxx']); ?>',
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

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/pengeluarankaskecil/rekapPrint') ?>?"+$('#form-search').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
function lihatRincian(tgl){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/rekapkaskecil/getRekapByTanggal','tgl'=>'']) ?>'+tgl,'modal-rekap');
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
</script>