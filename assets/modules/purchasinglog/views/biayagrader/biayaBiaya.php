<?php 
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-6">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-5 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title text-align-center"><?= Yii::t('app', "Biaya - Biaya Grader Log"); ?> <b><?= $model->graderlog->graderlog_nm ?></b></h4>
                <h5 class="modal-title text-align-center"><b><?= Yii::t('app', "Kode Dinas : "); ?> <u><?= $model->kode ?></u></b></h5>
				<div class="row" style="margin-top: 15px;">
					<div class="col-md-6" style="margin-bottom: -10px;">
						<h5 style="" class="font-grey-mint">Saldo Kas Dinas : <b id="place-saldodinas"></b></h5>
					</div>
					<div class="col-md-6" style="margin-bottom: -10px;">
						<h5 style="margin-bottom: -5px;" class="font-grey-mint pull-right">Saldo Kas Makan : <b id="place-saldomakan"><?= \app\components\DeltaFormatter::formatUang(app\models\HKasDinasgrader::getSaldoKas($model->graderlog_id)); ?></b></h5>
					</div>
				</div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6" style="border-right: 1px solid #DDDDDD;">
						<center style="margin-bottom: 30px;"><h5><b><?= Yii::t('app', 'Akomodasi Dinas'); ?></b></h5></center>
						<div class="table-scrollable">
							<div class="pull-left font-grey-gallery" style="font-size: 1.2rem"><b>Pengajuan Uang Dinas Grader</b></div>
							<table id="table-ajuan" class="table table-striped table-bordered table-advance table-hover">
								<thead>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 110px;">Kode</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 110px;">Tanggal<br>Ajuan</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 110px;">Tanggal<br>Butuh</th>
									<th class="td-kecil font-grey-gallery text-align-center">Total<br>Ajuan</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 80px;">Approve<br>Status</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 70px;">Payment<br>Status</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 70px;"></th>
								</thead>
								<tbody></tbody>
								<tfoot>
									<td colspan="5"><a class="btn btn-xs grey-gallery" onclick="createAjuanDinas(<?= $model->dkg_id ?>);"><i class="fa fa-plus"></i> Ajukan Biaya Dinas</a></td>
								</tfoot>
							</table>
						</div>
						<div class="table-scrollable">
							<div class="pull-left font-blue-dark" style="font-size: 1.2rem;"><b>Realisasi Uang Dinas Grader</b></div>
							<table id="table-realisasi" class="table table-striped table-bordered table-advance table-hover">
								<thead style="background-color: ">
									<th class="td-kecil font-blue-dark text-align-center" style="width: 110px;">Kode</th>
									<th class="td-kecil font-blue-dark text-align-center" >Periode</th>
									<th class="td-kecil font-blue-dark text-align-center" style="width: 110px;">Total Realisasi</th>
									<th class="td-kecil font-blue-dark text-align-center" style="width: 70px;"></th>
								</thead>
								<tbody></tbody>
								<tfoot>
									<td colspan="5">
										<a class="btn btn-xs blue-dark" onclick="createRealisasiDinas(<?= $model->dkg_id ?>);"><i class="fa fa-plus"></i> Buat Realisasi</a>
									</td>
								</tfoot>
							</table>
						</div>
                    </div>
					<div class="col-md-6">
						<center style="margin-bottom: 30px;"><h5><b><?= Yii::t('app', 'Uang Makan'); ?></b></h5></center>
						<div class="table-scrollable">
							<div class="pull-left font-grey-gallery" style="font-size: 1.2rem"><b>Pengajuan Uang Makan Grader</b></div>
							<table id="table-ajuanmakan" class="table table-striped table-bordered table-advance table-hover">
								<thead>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 100px;">Kode</th>
									<th class="td-kecil font-grey-gallery text-align-center">Periode</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 110px;">Tanggal<br>Butuh</th>
									<th class="td-kecil font-grey-gallery text-align-center">Total<br>Ajuan</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 80px;">Approve<br>Status</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 70px;">Payment<br>Status</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 70px;"></th>
								</thead>
								<tbody></tbody>
								<tfoot>
									<td colspan="5"><a class="btn btn-xs grey-gallery" onclick="createAjuanMakan(<?= $model->dkg_id ?>);"><i class="fa fa-plus"></i> Ajukan Biaya Makan</a></td>
								</tfoot>
							</table>
						</div>
						<div class="table-scrollable">
							<div class="pull-left font-blue-dark" style="font-size: 1.2rem;"><b>Realisasi Uang Makan Grader</b></div>
							<table id="table-realisasimakan" class="table table-striped table-bordered table-advance table-hover">
								<thead style="background-color: ">
									<th class="td-kecil font-blue-dark text-align-center" style="width: 110px;">Kode</th>
									<th class="td-kecil font-blue-dark text-align-center" >Periode</th>
									<th class="td-kecil font-blue-dark text-align-center" style="width: 110px;">Total Realisasi</th>
									<th class="td-kecil font-blue-dark text-align-center" style="width: 70px;"></th>
								</thead>
								<tbody></tbody>
								<tfoot>
									<td colspan="5">
										<a class="btn btn-xs blue-dark" onclick="createRealisasiMakan(<?= $model->dkg_id ?>);"><i class="fa fa-plus"></i> Buat Realisasi</a>
									</td>
								</tfoot>
							</table>
						</div>
					</div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
				<?php // echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
//                    'onclick'=>'submitformajax(this,"$(\'#close-btn-modal\').removeAttr(\'disabled\'); $(\'#close-btn-modal\').trigger(\'click\'); getItems();")'
//                    ]);
				?>
            </div>
			<?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
formconfig(); getItemsAjuan({$model->dkg_id}); getItemsRealisasi({$model->dkg_id}); getSaldo({$model->graderlog_id}); getItemsAjuanMakan({$model->dkg_id}); getItemsRealisasiMakan({$model->dkg_id})
", yii\web\View::POS_READY); ?>
<script>
function getSaldo(){
	$('#place-saldodinas').addClass('animation-loading');
	$('#place-saldomakan').addClass('animation-loading');
	$.post("<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/getSaldo']); ?>",{
        graderlog_id: "<?= $model->graderlog_id ?>"
    },
    function(data, status){
        $('#place-saldodinas').html(data.dinas);
		$('#place-saldomakan').html(data.makan);
		$('#place-saldodinas').removeClass('animation-loading');
		$('#place-saldomakan').removeClass('animation-loading');
    });

}
	
function getItemsAjuan(id){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/biayaBiaya','id'=>'']); ?>'+id,
		type   : 'POST',
		data   : {getItemsAjuan:true},
		success: function (data) {
			$('#table-ajuan > tbody').html("");
			if(data.html){
				$('#table-ajuan > tbody').html(data.html);
			}
			reordertable('#table-ajuan');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function getItemsRealisasi(id){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/biayaBiaya','id'=>'']); ?>'+id,
		type   : 'POST',
		data   : {getItemsRealisasi:true},
		success: function (data) {
			$('#table-realisasi > tbody').html("");
			if(data.html){
				$('#table-realisasi > tbody').html(data.html);
			}
			reordertable('#table-realisasi');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function getItemsAjuanMakan(id){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/biayaBiaya','id'=>'']); ?>'+id,
		type   : 'POST',
		data   : {getItemsAjuanMakan:true},
		success: function (data) {
			$('#table-ajuanmakan > tbody').html("");
			if(data.html){
				$('#table-ajuanmakan > tbody').html(data.html);
			}
			reordertable('#table-ajuanmakan');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function getItemsRealisasiMakan(id){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/biayaBiaya','id'=>'']); ?>'+id,
		type   : 'POST',
		data   : {getItemsRealisasiMakan:true},
		success: function (data) {
			$('#table-realisasimakan > tbody').html("");
			if(data.html){
				$('#table-realisasimakan > tbody').html(data.html);
			}
			reordertable('#table-realisasimakan');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function createAjuanDinas(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/createAjuanDinas','id'=>'']) ?>'+id;
	var modal_id = 'modal-ajuan';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function createRealisasiDinas(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/createRealisasiDinas','id'=>'']) ?>'+id;
	var modal_id = 'modal-realisasi';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function createAjuanMakan(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/createAjuanMakan','id'=>'']) ?>'+id;
	var modal_id = 'modal-ajuanmakan';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function createRealisasiMakan(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/createRealisasiMakan','id'=>'']) ?>'+id;
	var modal_id = 'modal-realisasimakan';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}

function detailAjuanDinas(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailAjuanDinas','id'=>'']) ?>'+id;
	var modal_id = 'modal-ajuandinas';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function detailRealisasiDinas(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailRealisasiDinas','id'=>'']) ?>'+id;
	var modal_id = 'modal-realisasidinas';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}

function detailAjuanMakan(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailAjuanMakan','id'=>'']) ?>'+id;
	var modal_id = 'modal-ajuanmakan';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function detailRealisasiMakan(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailRealisasiMakan','id'=>'']) ?>'+id;
	var modal_id = 'modal-realisasimakan';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function printAjuanDinas(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/biayagrader/printAjuanDinas') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=900px, scrollbars=yes');
}
function printAjuanMakan(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/biayagrader/printAjuanMakan') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=900px, scrollbars=yes');
}
function deleteAjuanDinas(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/deleteAjuanDinas','id'=>''])?>'+id,'modal-delete-record');
}
function deleteRealisasiDinas(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/deleteRealisasiDinas','id'=>''])?>'+id,'modal-delete-record');
}

function deleteAjuanMakan(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/deleteAjuanMakan','id'=>''])?>'+id,'modal-delete-record');
}
function deleteRealisasiMakan(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/deleteRealisasiMakan','id'=>''])?>'+id,'modal-delete-record');
}
</script>