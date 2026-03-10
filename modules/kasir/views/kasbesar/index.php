<?php
/* @var $this yii\web\View */
$this->title = 'Kas Besar';
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
    'id' => 'form-penerimaan-kas',
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
						<a href="<?= yii\helpers\Url::toRoute("/kasir/kasbesar/index"); ?>"> <?= Yii::t('app', 'Penerimaan Kas Besar'); ?> </a>
                    </li>
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/kasbesar/kasbon"); ?>"> <?= Yii::t('app', 'Bon Kas Besar'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/saldokasbesar/index"); ?>"> <?= Yii::t('app', 'Laporan Kas Besar'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/setorbank/index"); ?>"> <?= Yii::t('app', 'Setor Bank'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/rekapkasbesar/index"); ?>"> <?= Yii::t('app', 'Rekap Kas Besar'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/terimanontunai/index"); ?>"> <?= Yii::t('app', 'Penerimaan Non-Tunai'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Transaksi Rekap Realisasi Penerimaan Kas Besar'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= $form->field($model, 'tanggal',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'getItems()'])->label('Tanggal'); ?>
                                    </div>
									<div class="col-md-6" style="margin-top: -8px; text-align: right;">
										<span id="btn-closing-place"></span><br>
										<a class="btn btn-sm blue" id="btn-closing" onclick="uangtunai();" style="margin-top: 10px;"><i class="fa fa-money"></i> &nbsp;<?= Yii::t('app', 'Total Uang Tunai : Rp. '); ?><span id="place-totaluangtunai"></span></a>
									</div>
                                </div>
								<br><br><hr>
								<div class="row">
									<div class="col-md-5">
										<h4><?= Yii::t('app', 'Realisasi Penerimaan Kas Besar'); ?></h4>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
														<th style="width: 90px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Jenis<br>Penerimaan'); ?></th>
														<th style="width: 65px; font-size: 1.1rem; line-height: 1"><?= Yii::t('app', 'Cara<br>Transaksi'); ?></th>
														<th style="width: 110px; font-size: 1.3rem;"><?= Yii::t('app', 'No. Reff'); ?></th>
														<th><?= Yii::t('app', 'Deskripsi'); ?></th>
														<th style="width: 100px; "><?= Yii::t('app', 'Debit'); ?></th>
														<!--<th style="width: 30px; "><?php // echo Yii::t('app', 'Kredit'); ?></th>-->
														<th style="width: 100px; ">Nota /<br>Kuitansi</th>
														<th style="width: 60px; text-align: center;"><?= Yii::t('app', ''); ?></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="5" class="text-align-right">Total &nbsp;</td>
														<td class="td-kecil text-align-right td-kecil"><?php echo yii\bootstrap\Html::textInput('total',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'width: 100px; padding:3px;']) ?></td>
														<td class="td-kecil text-align-right td-kecil"><?php // echo yii\bootstrap\Html::textInput('totalkredit',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'width: 100px; padding:3px;']) ?></td>
													</tr>
													<tr>
														<td colspan="5">
															<div class="col-md-2" id="btn-additem-place"></div>
														</td>
														<td colspan="5" style="text-align: right;">
															<div class="col-md-12 " id="btn-urutan-place"> </div>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
							
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
if(isset($_GET['kas_besar_id'])){
    $pagemode = "";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	$(\"#".yii\bootstrap\Html::getInputId($model, 'tanggal')."\").datepicker({
        rtl: App.isRTL(),
        orientation: \"left\",
        autoclose: !0,
        format: \"dd/mm/yyyy\",
        clearBtn:false,
        todayHighlight:true
    });
    $pagemode;
", yii\web\View::POS_READY); ?>
<script>
function getItems(){
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/getItems']); ?>',
		type   : 'POST',
		data   : {tgl:tgl},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			$('#table-detail > tbody > tr').each(function(){
				$(this).find(".tooltips").tooltip({ delay: 50 });
			});
			setClosingBtn();
			setTotal();
			setDetailLayout();
			totalUangTunai();
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setClosingBtn(){ 
	var jmltr = $('#table-detail > tbody > tr').length;
	var html = '';
	var html2 = '';
	var html3 = '';
	var html4 = '';
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/setClosingBtn']); ?>',
		type   : 'POST',
		data   : {tgl:tgl},
		success: function (data) {
			if(data.status == 1){
				html = '<a class="btn btn-sm grey" id="btn-closing" disabled="disabled" style="margin-top: 10px;"><i class="fa fa-book"></i> <?= Yii::t('app', 'Closed'); ?></a>';
				html2 = '<a id="btn-add-item" class="btn btn-sm grey" disabled="disabled" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Penerimaan Baru'); ?></a>';
				html3 = '<a class="btn btn-xs grey" disabled="disabled" style="margin-top: 10px; margin-right: 0px;"><i class="fa fa-refresh"></i> <?= Yii::t('app', 'Refresh'); ?></a>';
				$('#form-penerimaan-kas').find('input').each(function(){ $(this).attr("readonly","readonly"); });
				$('#form-penerimaan-kas').find('textarea').each(function(){ $(this).attr("readonly","readonly"); });
				$('#btn-save').attr("disabled","disabled");
				$('#table-detail > tbody > tr').each(function(){
					$(this).find('#td-action').html(' ');
				});
			}else{
				html2 = '<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Penerimaan Baru'); ?></a>';
				html3 = '<a class="btn btn-xs hijau" id="btn-refresh" style="margin-top: 10px; margin-right: 0px;" onclick="refresh();"><i class="fa fa-refresh"></i> <?= Yii::t('app', 'Refresh'); ?></a>';
//				if(jmltr > 0){
					html = '<a class="btn btn-sm red-flamingo" id="btn-closing" onclick="closing();" style="margin-top: 10px;"><i class="fa fa-book"></i> <?= Yii::t('app', 'Closing Kas Besar'); ?></a>';
//				}else{
//					html = '<a class="btn btn-sm red-flamingo" id="btn-closing" disabled="disabled" style="margin-top: 10px;"><i class="fa fa-book"></i> <?= Yii::t('app', 'Closing Kas Besar'); ?></a>';
//				}
			}
			$('#btn-closing-place').html(html);
			$('#btn-additem-place').html(html2);
			$('#btn-urutan-place').html(html3);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setTotal(){
	var total = 0;
	var totalkredit = 0;
	$('#table-detail > tbody > tr').each(function (){
		total += unformatNumber( $(this).find('input[name*="[nominal]"]').val() );
		totalkredit += unformatNumber( $(this).find('input[name*="[kredit]"]').val() );
	});
	$('input[name="totalkredit"]').val( formatNumberForUser( totalkredit ) );
	$('input[name="total"]').val( formatNumberForUser( total ) );
}

function setDetailLayout(){
	$('#table-detail > tbody > tr').each(function (){
		if( $(this).find('input[name*="[kas_besar_id]"]') ){
			$(this).find('input, textarea, select').attr('disabled','disabled');
			afterSave();
		}else{
			$(this).find('input, textarea, select').removeAttr('disabled');
		}
	});
}

function totalUangTunai(){
	$('#place-totaluangtunai').addClass('animation-loading');
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/getUangTunai']); ?>',
		type   : 'POST',
		data   : { tgl: tgl },
		success: function (data) {
			$('#place-totaluangtunai').html( formatNumberForUser(data.total) );
			$('#place-totaluangtunai').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function addItem(){
	// Check Closing
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/checkClosing']); ?>',
		type   : 'POST',
		data   : {tgl:tgl},
		success: function (data) {
			if(data == 1){
				cisAlert('Tidak bisa Tambah Item karena ada penerimaan kas yang belum di Closing di tanggal sebelumnya ;)')
			}else{
				$.ajax({
					url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/addItem']); ?>',
					type   : 'POST',
					data   : {tgl:tgl},
					success: function (data) {
						if(data.html){
							$('#table-detail > tbody').append(data.html);
							
						}
						setClosingBtn();
						reordertable('#table-detail');
					},
					error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
				});
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
	// End
}

function refresh(){
	getItems();
}

function save(ele){
    var $form = $('#form-penerimaan-kas');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail(ele)){
			$(ele).parents('tr').find('input[name*="[nominal]"]').val( unformatNumber($(ele).parents('tr').find('input[name*="[nominal]"]').val()) );
			$(ele).parents('tr').find('input[name*="[kredit]"]').val( unformatNumber($(ele).parents('tr').find('input[name*="[kredit]"]').val()) );
			$(ele).parents('tr').addClass('animation-loading');
			$.ajax({
				url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/index']); ?>',
				type   : 'POST',
				data   : { formData: $(ele).parents('tr').find('input, textarea, select').serialize() },
				success: function (data) {
					$(ele).parents('tr').find('input[name*="[nominal]"]').val( formatNumberForUser($(ele).parents('tr').find('input[name*="[nominal]"]').val()) );
					$(ele).parents('tr').find('input[name*="[kredit]"]').val( formatNumberForUser($(ele).parents('tr').find('input[name*="[kredit]"]').val()) );
					if(data.status){
						$(ele).parents('tr').find('input[name*="[kas_besar_id]"]').val(data.kas_besar_id);
						$(ele).parents('tr').find('input[name*="[kode]"]').addClass('font-blue');
						$(ele).parents('tr').find('input[name*="[kode]"]').val( data.kode );
						$(ele).parents('tr').find('input, textarea, select').attr('disabled','disabled');
						$(ele).parents('tr').find('#place-editbtn').attr('style','display:');
						$(ele).parents('tr').find('#place-cancelbtn').attr('style','display:none');
						$(ele).parents('tr').find('#place-savebtn').attr('style','display:none');
						$(ele).parents('tr').find('#place-deletebtn').attr('style','display:');
						$(ele).parents('tr').find('#place-nota').html("<a onclick='infoNota(\""+data.kodenota+"\")'>"+data.kodenota+"</a><a onclick=\"createKuitansi(this);\" class=\"tooltips btn btn-outline btn-xs blue\" data-original-title=\"Buat Kuitansi\"><i class=\"fa fa-plus\"></i> Kuitansi</a>");
						$(ele).parents('tr').find('#place-nota').attr('style','display:');
						$(ele).parents('tr').find('#place-notabtn').attr('style','display:none');
						$(ele).parents('tr').removeClass('animation-loading');
					}
					reordertable('#table-detail');
				},
				error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
			});
        }
    }
	
    return false;
}

function validatingDetail(ele){
    var has_error = 0;
	var field1 = $(ele).parents('tr').find('textarea[name*="[deskripsi]"]');
	var field2 = $(ele).parents('tr').find('input[name*="[nominal]"]');
	var field3 = $(ele).parents('tr').find('input[name*="[penerima]"]');
	var field4 = $(ele).parents('tr').find('input[name*="[kredit]"]');
	var field5 = $(ele).parents('tr').find('input[name*="[no_tandaterima]"]');
	if(!field3.val()){
		$(ele).parents('tr').find('input[name*="[penerima]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[penerima]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field1.val()){
		$(ele).parents('tr').find('textarea[name*="[deskripsi]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('textarea[name*="[deskripsi]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field2.val()){
		$(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field4.val()){
		$(ele).parents('tr').find('input[name*="[kredit]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[kredit]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field5.val()){
		$(ele).parents('tr').find('input[name*="[no_tandaterima]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[no_tandaterima]"]').parents('td').removeClass('error-tb-detail');
	}
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(){
	$('input[name*="total"]').attr('disabled','disabled');
}

function edit(ele){
	$(ele).parents('tr').find('input, textarea, select').removeAttr('disabled');
	$(ele).parents('tr').find('#place-nota').attr('style','display:none');
	$(ele).parents('tr').find('#place-editnotakui').attr('style','display:');
	$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
	$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
}

function deleteItem(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/deleteItem','id'=>''])?>'+id,'modal-delete-record');
}

function closing(){
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/CheckClosingUangTunai']); ?>',
		type   : 'POST',
		data   : { tgl: tgl },
		success: function (data) {
			if(data.exist == 1){
				openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/closingConfirm','id'=>'']); ?>'+tgl,'modal-transaksi');
			}else{
				cisAlert("Tidak bisa closing, Rincian Uang Tunai belum di input."); return false;
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
		setTotal();
		setClosingBtn();
    });
}

function uangtunai(){
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/uangtunai','id'=>''])?>'+tgl,'modal-uangtunai','400px','totalUangTunai();');
}

function caratransaksi(ele){
	var cara = $(ele).val();
	var idtarget = $(ele).parents('tr').find('input[name*="[reff_cara_transaksi]"]').attr('id');
	var lastvalue = $(ele).parents('tr').find('input[name="last_value"]').val();
	if(cara != 'Tunai'){
		$(ele).val(lastvalue);
		if(cara == 'Bilyet Giro'){ cara = 'Bilyet'; }
		openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/caratransaksi','cara'=>''])?>'+cara+'&idtarget='+idtarget+'&lastvalue='+lastvalue,'modal-caratransaksi','500px');
	}else{
		$(ele).parents('tr').find('input[name*="[reff_cara_transaksi]"]').val('-');
	}
	return false;
}

function lastvalue(ele){
	$(ele).parents('tr').find('input[name="last_value"]').val( $(ele).val() );
}
function pickPanelNota(ele){
	var eleid = $(ele).parents("td").find('input').attr('id');
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/pickPanelNota']) ?>?eleid='+eleid,'modal-nota','75%');
}
function pickingNota(nota_penjualan_id,kode){
	var eleid = $('#eleid').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/pickNota']); ?>',
		type   : 'POST',
		data   : {nota_penjualan_id:nota_penjualan_id,kode:kode},
		success: function (data) {
			if(data){
				$('#'+eleid).parents('tr').find('input[name*="[nota_penjualan_id]"]').val( nota_penjualan_id );
				$('#'+eleid).parents('tr').find('input[name*="[no_tandaterima]"]').val(data.kode);
				$('#'+eleid).parents('tr').find('textarea[name*="[deskripsi]"]').val(data.deskripsi);
				$('#'+eleid).parents('tr').find('input[name*="[nominal]"]').val( formatNumber(data.nominal) );
			}
			$("#modal-nota").find('button.fa-close').trigger('click');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
	
}
function pickingNotaOld(){
	var picked = $('#select_data').val();
	var eleid = $('#eleid').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/pickNota']); ?>',
		type   : 'POST',
		data   : {picked:picked},
		success: function (data) {
			if(data.kodeterima){
				$('#'+eleid).val(data.kodeterima);
			}
			if(data.kodelabelterima){
				$('#'+eleid).parents('tr').find('#place-tbp').html(data.kodelabelterima);
			}
			if(data.total){
				$('#'+eleid).parents('tr').find('input[name*="[nominal]"]').val( formatNumberForUser(data.total) );
			}
			if(data.deskripsi){
				$('#'+eleid).parents('tr').find('textarea[name*="[deskripsi]"]').val(data.deskripsi);
			}
			setTotal();
			$('#modal-tbp').modal('hide');
			
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function infoNota(kode){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/infoNota']); ?>?kode='+kode;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-nota .modal-dialog").css('width','21.5cm');
		$("#modal-info-nota").modal('show');
		$("#modal-info-nota").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function createKuitansi(ele){
	var kas_besar_id = $(ele).parents('tr').find('input[name*="[kas_besar_id]"]').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/createKuitansi']) ?>?reff_id='+kas_besar_id+'&cara_bayar=Tunai','modal-transaksi','75%');
}

function infoKuitansi(kuitansi_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/infoKuitansi']); ?>?kuitansi_id='+kuitansi_id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-kuitansi .modal-dialog").css('width','21.5cm');
		$("#modal-info-kuitansi").modal('show');
		$("#modal-info-kuitansi").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function editKuitansi(ele){
	var kas_besar_id = $(ele).parents('tr').find('input[name*="[kas_besar_id]"]').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/createKuitansi']) ?>?reff_id='+kas_besar_id+'&cara_bayar=Tunai&edit=1','modal-transaksi','75%');
}
</script>