<?php
/* @var $this yii\web\View */
$this->title = 'Kas Kecil';
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
					<li class="active">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/sementara"); ?>"> <?= Yii::t('app', 'Bon Kas Kecil'); ?> </a>
                    </li>
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/index"); ?>"> <?= Yii::t('app', 'Pengeluaran Kas Kecil'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/saldokaskecil/index"); ?>"> <?= Yii::t('app', 'Laporan Kas Kecil'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/rekapkaskecil/index"); ?>"> <?= Yii::t('app', 'Rekap Kas Kecil'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/terimaretur"); ?>"> <?= Yii::t('app', 'Terima Uang Retur'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Rekap Pengeluaran Kas Kecil Sementara (Bon Sementara)'); ?></h4></span>
                                </div>
                                <div class="tools">
									<a class="btn btn-sm btn-outline blue" id="btn-closing" onclick="historyBon();" style="margin-top: 10px; height: 28px;"><i class="icon-speedometer"></i> <?= Yii::t('app', 'Bon Terealisasi'); ?></a>
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
														<th style="width: 110px; text-align: center;"><?= Yii::t('app', 'Kode'); ?></th>
														<th style="width: 150px; text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
														<th style="width: 150px; text-align: center;"><?= Yii::t('app', 'Penerima'); ?></th>
														<th><?= Yii::t('app', 'Deskripsi'); ?></th>
														<th style="width: 100px; "><?= Yii::t('app', 'Kredit'); ?></th>
														<th style="width: 70px; text-align: center;"><?= Yii::t('app', ''); ?></th>
														<th style="width: 70px; text-align: center;"><?= Yii::t('app', ''); ?></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="5" class="text-align-right">Total &nbsp;</td>
														<td class="td-kecil text-align-right"><?php echo yii\bootstrap\Html::textInput('total',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem;']) ?></td>
													</tr>
													<tr>
														<td colspan="5">
															<div class="col-md-2" id="btn-additem-place">
																<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Bon Baru'); ?></a>
															</div>
														</td>
													</tr>
												</tfoot>
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
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['kas_kecil_id'])){
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
	getItems();
    $pagemode;
	checkKasbonKasbesar();
", yii\web\View::POS_READY); ?>
<script>
function getItems(){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/getItemsSementara']); ?>',
		type   : 'POST',
		success: function (data) {
			$('#table-detail > tbody').html("");
//			if(data.html){
//				$('#table-detail > tbody').html(data.html);
//			}
			var html = "";
			if(data.kasbon){
				$.each(data.kasbon,function(index,kasbon){
					var btn_a = "";
					if(kasbon.kas_bon_id){
						if(kasbon.gkk_id){
							btn_a = '<a onclick="detailGkk('+kasbon.gkk_id+')" >'+kasbon.gkk_kode+'</a>';
						}else{
							btn_a = '<a class="btn btn-sm blue btn-outline" target="BLANK" style="font-size:1rem; padding: 3px;" onclick="createGkk('+kasbon.gkk_id+')"><i class="fa fa-share"></i> Buat GKK </a>';
						}
					}
					html += '<tr style="">\n\
								<td style="vertical-align: middle; text-align: center;">\n\
									<input type="hidden" id="no_urut" name="no_urut" style="width:30px;" disabled="disabled">\n\
									<input type="hidden" id="TKasBon_'+index+'_kas_bon_id" name="TKasBon['+index+'][kas_bon_id]" value="'+kasbon.kas_bon_id+'" style="width:50px;" disabled="disabled">\n\
									<span class="no_urut"></span>\n\
								</td>\n\
								<td style="vertical-align: middle; text-align: center;">\n\
									<input id="TKasBon_'+index+'_kode" class="form-control text-align-center font-blue" name="TKasBon['+index+'][kode]" value="'+kasbon.kode+'" disabled="disabled" style="font-weight:bold; font-size:1.1rem;" type="text">\n\
								</td>\n\
								<td style="vertical-align: middle; text-align: center;">\n\
									<div class="input-group input-sm date date-picker bs-datetime" style="padding:3px;">\n\
										<input id="TKasBon_'+index+'_tanggal" class="form-control" name="TKasBon['+index+'][tanggal]" value="'+kasbon.tanggal+'" readonly="readonly" style="width:70%; font-size:1.2rem; padding:3px;" placeholder="Tgl Kas Bon" disabled="disabled" type="text"><span class="input-group-addon">\n\
											<button class="btn default" type="button" style="margin-left: -40px;" disabled="">\n\
												<i class="fa fa-calendar"></i>\n\
											</button>\n\
										</span>\n\
									</div>\n\
								</td>\n\
								<td class="td-kecil" style="vertical-align: middle;">\n\
									<input id="TKasBon_'+index+'_penerima" class="form-control " name="TKasBon['+index+'][penerima]" value="'+kasbon.penerima+'" style="font-size:1.2rem; padding:3px;" disabled="disabled" type="text">\n\
								</td>\n\
								<td class="td-kecil" style="vertical-align: middle;">\n\
									<textarea id="TKasBon_'+index+'_deskripsi" class="form-control" name="TKasBon['+index+'][deskripsi]" style="height:50px; font-size:1.2rem; padding:3px;" disabled="disabled">'+kasbon.deskripsi+'</textarea>\n\
								</td>\n\
								<td class="td-kecil" style="vertical-align: middle; text-align: center;">\n\
									<input id="TKasBon_'+index+'_nominal" class="form-control float" name="TKasBon['+index+'][nominal]" onblur="setTotal()" value="'+kasbon.nominal+'" style="padding:3px; font-size:1.2rem;" disabled="disabled" type="text">\n\
								</td>\n\
								<td class="td-kecil" style="vertical-align: middle; text-align: center;">\n\
									'+data.content_a[index]+'\n\
								</td>\n\
								<td class="td-kecil" style="vertical-align: middle; text-align: center;">\n\
									<span id="place-editbtn" style="display: '+(data.content_btnedit[index])+'">\n\
										<a class="btn btn-xs dark btn-outline" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="edit(this);"><i class="fa fa-edit"></i></a>\n\
									</span>\n\
									<span id="place-savebtn" style="display: '+(data.content_btnsave[index])+'">\n\
										<a class="btn btn-xs hijau" id="close-btn-this" style="padding-left: 3px; padding-right: 3px; margin-right: 0px;" onclick="save(this);"><i class="fa fa-check"></i></a>\n\
									</span>\n\
									<span id="place-cancelbtn" style="display: '+(data.content_btncancel[index])+'">\n\
										<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>\n\
									</span>\n\
									<span id="place-deletebtn" style="display: '+(data.content_btndelete[index])+'">\n\
										<a class="btn btn-xs red" id="close-btn-this" onclick="deleteItem('+kasbon.kas_bon_id+');"><i class="fa fa-trash-o"></i></a>\n\
									</span>\n\
								</td>\n\
							</tr>';
				});
				$('#table-detail > tbody').html(html);
			}
			setTotal();
			setDetailLayout();
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getItemsSingle(kas_bon_id){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/getItemsSementaraSingle']); ?>',
		type   : 'POST',
		data   : {kas_bon_id:kas_bon_id},
		success: function (data) {
			if(data.html){
				$('#table-detail > tbody :input[name*="[kas_bon_id]"][value="'+kas_bon_id+'"]').parents('tr').replaceWith(data.html);
			}
			setTotal();
			setDetailLayout();
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function addItem(){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/addItemSementara']); ?>',
		type   : 'POST',
		data   : {},
		success: function (data) {
			if(data.html){
				$('#table-detail > tbody').append(data.html);
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function save(ele){
    var $form = $('#form-pengeluaran-kas');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail(ele)){
			$(ele).parents('tr').find('input[name*="[nominal]"]').val( unformatNumber($(ele).parents('tr').find('input[name*="[nominal]"]').val()) );
			$(ele).parents('tr').addClass('animation-loading');
			$.ajax({
				url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/sementara']); ?>',
				type   : 'POST',
				data   : { formData: $(ele).parents('tr').find('input, textarea').serialize() },
				success: function (data) {
					$(ele).parents('tr').find('input[name*="[nominal]"]').val( formatNumberForUser($(ele).parents('tr').find('input[name*="[nominal]"]').val()) );
					if(data.status){
						getItemsSingle( $(ele).parents('tr').find('input[name*="[kas_bon_id]"]').val() );
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
//    $('#table-detail tbody > tr').each(function(){
        var field1 = $(ele).parents('tr').find('textarea[name*="[deskripsi]"]');
        var field2 = $(ele).parents('tr').find('input[name*="[nominal]"]');
        var field3 = $(ele).parents('tr').find('input[name*="[penerima]"]');
        var field4 = $(ele).parents('tr').find('input[name*="[tanggal]"]');
        if(!field4.val()){
            $(ele).parents('tr').find('input[name*="[tanggal]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(ele).parents('tr').find('input[name*="[tanggal]"]').parents('td').removeClass('error-tb-detail');
        }
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
            has_error = has_error + 1;
            $(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( $(ele).parents('tr').find('input[name*="[nominal]"]').val() == 0 ){
                has_error = has_error + 1;
                $(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').addClass('error-tb-detail');
            }else{
                $(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').removeClass('error-tb-detail');
            }
        }
//    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function edit(ele){
	$(ele).parents('tr').find('input, textarea').removeAttr('disabled');
	$(ele).parents('tr').find('input[name*="[kode]"]').attr('disabled','disabled');
	$('.date-picker').find('.input-group-addon').find('button').prop('disabled', false);
	$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
	$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
		setTotal();
    });
}

function deleteItem(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/deleteItemSementara','id'=>''])?>'+id,'modal-delete-record')
}

function setTotal(){
	var total = 0;
	$('#table-detail > tbody > tr').each(function (){
		total += unformatNumber( $(this).find('input[name*="[nominal]"]').val() );
	});
	$('input[name="total"]').val( formatNumberForUser( total ) );
}

function setDetailLayout(){
	$('#table-detail > tbody > tr').each(function (){
		if( $(this).find('input[name*="[kas_bon_id]"]') ){
			$(this).find('input, textarea').attr('disabled','disabled');
			afterSave();
		}else{
			$(this).find('input, textarea').removeAttr('disabled');
		}
	});
}

function afterSave(){
	$('input[name*="total"]').attr('disabled','disabled');
	$('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
}

function historyBon(){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/historyPengeluaranSementara']) ?>','modal-history','85%');
}

function detailBkk(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/bkk/detailBkk']) ?>?id='+id,'modal-bkk','21cm');
}
function detailBbk(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucher/detailBbk']) ?>?id='+id,'modal-bbk','21cm');
}
function detailGkk(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/detailGkk']) ?>?id='+id,'modal-gkk','21cm');
}
function terimauangganti(id){ 
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/terimauangganti','id'=>''])?>'+id,'modal-global-confirm')
}
function printBKK(id){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/bkk/printout') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
function printGKK(id){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/pengeluarankaskecil/detailGkk') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
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

function createGkk(kas_bon_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/createGkk','kas_bon_id'=>'']); ?>'+kas_bon_id;
	$(".modals-place").load(url, function() {
		$("#modal-transaksi").modal('show');
		$("#modal-transaksi").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
</script>