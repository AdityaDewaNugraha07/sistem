<?php
/* @var $this yii\web\View */
$this->title = 'Pengajuan Tagihan';
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
    'id' => 'form-ajuan-tagihan',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-3 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.tDnD_whileDrag td {
    background-color: #93979d;
    -webkit-box-shadow: 11px 5px 12px 2px #333, 0 1px 0 #ccc inset, 0 -1px 0 #ccc inset;
}
</style>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pengajuantagihan/index"); ?>"> <?= Yii::t('app', 'Pengajuan Transfer'); ?> </a>
                    </li>
                    <li class="active">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pengajuantagihancash/index"); ?>"> <?= Yii::t('app', 'Pengajuan Cash'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Pengajuan Tagihan Nota Cash Supplier Bahan Pembantu'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    Mode : <input type="checkbox" nama="mode" id="mode-select" class="make-switch" data-on-text="Input" data-off-text="Cari" data-size="mini" checked="checked">
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row" id="mode-input" style="display: none;">
                                    <div class="col-md-6">
										<?= $form->field($model, 'tanggal',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'getItems();'])->label('Tanggal Pengajuan'); ?>
                                    </div>
									<div class="col-md-6">
										<a class="btn btn-sm blue pull-right mode-input" style="margin-bottom: 5px;" onclick="pickPanelTBP();"><i class="fa fa-plus"></i> Add TBP</a>
									</div>
                                </div>
								<div class="row" id="mode-cari" style="display: none;">
                                    <div class="col-md-6">
										<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode Pengajuan','model' => $model,'form'=>$form]) ?>
										<?= $form->field($model, 'suplier')->dropDownList(\app\models\MSuplier::getOptionList(),['enableClientValidation' => false,'class'=>"form-control",'prompt'=>'All'])->label(Yii::t('app', 'Supplier')); ?>
                                    </div>
                                    <div class="col-md-5">
										<?= $form->field($model, 'kode_tbp')->textInput(['placeholder'=>'Ketik Kode TBP'])->label("Kode TBP"); ?>
										<?= $form->field($model, 'nomor_nota')->textInput(['placeholder'=>'Ketik No. Nota'])->label("No. Nota"); ?>
                                    </div>
									<div class="col-md-1">
										<a class="btn hijau btn-outline ciptana-spin-btn pull-right" id="tombol-cari" onclick="getItems();">Search</a>
									</div>
                                </div>
								<br><br><hr>
								<div class="row" style="margin-left: -30px; margin-right: -30px;">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail" style="width: 100%;">
												<thead>
													<tr class="nodrag">
														<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
														<th style="width: 100px;"><?= Yii::t('app', 'Kode TBP'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Kode SPL'); ?></th>
														<th style=""><?= Yii::t('app', 'Suplier'); ?></th>
														<th style="width: 100px; line-height: 1; "><?= Yii::t('app', 'Tanggal<br>Nota'); ?></th>
                                                        <th style="width: 110px; line-height: 1; font-size: 1.1rem;"><?= Yii::t('app', 'Kelengkapan<br>Berkas'); ?></th>
														<th style="width: 100px; line-height: 1; font-size: 1.2rem;"><?= Yii::t('app', 'No.Nota'); ?></th>
														<th style="width: 100px; line-height: 1; font-size: 1.1rem;"><?= Yii::t('app', 'No.Faktur'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Nominal'); ?></th>
														<th style="width: 80px;"><?= Yii::t('app', 'Status'); ?></th>
														<th style="width: 60px;"></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
												<tfoot>
													<tr class="nodrag">
														<td colspan="8" class="text-align-right">Total &nbsp;</td>
														<td class="td-kecil text-align-right" id="total_nominal"></td>
														<td colspan="2"></td>
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
<?php $this->registerJs(" 
	$(\"#".yii\bootstrap\Html::getInputId($model, 'tanggal')."\").datepicker({
        rtl: App.isRTL(),
        orientation: \"left\",
        autoclose: !0,
        format: \"dd/mm/yyyy\",
        clearBtn:false,
        todayHighlight:true
    });
	setMode();
	$('#mode-select').on('switchChange.bootstrapSwitch', function (event, state) {
		setMode();
	});
	$(this).find('select[name*=\"[suplier]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Supplier',
		width: null
	});
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Pengajuan Tagihan'))."');
", yii\web\View::POS_READY); ?>
<script>
function setMode(){
	var set = $("#mode-select").bootstrapSwitch('state');
	if(set==true){
		$("#mode-input").attr("style","display:");
		$("#mode-cari").attr("style","display:none;");
	}else{
		$("#mode-input").attr("style","display:none");
		$("#mode-cari").attr("style","display:;");
	}
}
	
function getItems(){
	$("#table-detail > tbody").addClass("animation-loading");
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	if( $("#mode-select").bootstrapSwitch('state')==true ){
		var mode = "input";
	}else{
		var mode = "cari";
	}
	var tgl_awal = $('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal') ?>').val();
	var tgl_akhir = $('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir') ?>').val();
	var suplier = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier') ?>').val();
	var kode_tbp = $('#<?= yii\bootstrap\Html::getInputId($model, 'kode_tbp') ?>').val();
	var nomor_nota = $('#<?= yii\bootstrap\Html::getInputId($model, 'nomor_nota') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasing/pengajuantagihancash/getItems']); ?>',
		type   : 'POST',
		data   : {tgl:tgl,mode:mode,tgl_awal:tgl_awal,tgl_akhir:tgl_akhir,suplier:suplier,kode_tbp:kode_tbp,nomor_nota:nomor_nota},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}else{
				$('#table-detail > tbody').html("<tr><td colspan='12' style='font-size:1.2rem;'><center>Data tidak ditemukan</center></td></tr>");
			}
			reordertable('#table-detail');
			setTotal();
            $('#table-detail > tbody > tr').each(function(){
                $(this).find("input[name*='[no_fakturpajak]']").inputmask({'mask': '999.999-99.999999999'});
            });
			$('#table-detail > tbody').find(".tooltips").tooltip({ delay: 50 });
			$('#table-detail > tbody').find('input, textarea, button').attr('disabled','disabled');
			$("#table-detail > tbody").removeClass("animation-loading");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function pickPanelTBP(){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuantagihancash/pickPanelTBP']) ?>','modal-tbp','75%');
}
function pickingTBP(){
	var picked = $('#select_data').val();
	var eleid = $('#eleid').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuantagihancash/pickTBP']); ?>',
		type   : 'POST',
		data   : {picked:picked},
		success: function (data) {
			if(data.html){
				if( $("#table-detail").find("input[name*='[pengajuan_tagihan_id]']").length == 0 ){
					$('#table-detail > tbody').html(data.html);
				}else{
					$('#table-detail > tbody').append(data.html);
				}
			}
			setTotal();
			reordertable("#table-detail");
            setHover();
			$('#modal-tbp').modal('hide');
            $("#table-detail > tbody > tr").each(function(){
                $(this).find('input[name*="[no_fakturpajak]"]').inputmask({'mask': '999.999-99.999999999'});
            });
			
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function edit(ele){
	var pengajuan_tagihan_id = $(ele).parents('tr').find('input[name*="[pengajuan_tagihan_id]"]').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuantagihan/edit']); ?>',
		type   : 'GET',
		data   : {id:pengajuan_tagihan_id},
		success: function (data) {
			if(data){
				$(ele).parents('tr').find('input, textarea, button').removeAttr('disabled');
				$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
				$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
				
				$(ele).parents('tr').find('.place-view-mode').attr('style','display:none');
				$(ele).parents('tr').find('.place-input-mode').attr('style','display:');
				$(ele).parents('tr').find('.place-input-mode').html("");
				$(ele).parents('tr').find('.place-input-mode').html(data);
				reordertable("#table-detail");
				setHover(ele);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setTotal(){
	var total = 0;
	$('#table-detail > tbody > tr').each(function (){
		total += unformatNumber( $(this).find('input[name*="[nominal]"]').val() );
	});
	$('#total_nominal').html( formatNumberForUser( total ) );
}

function save(ele){
    var $form = $('#form-ajuan-tagihan');
	var tgl = $("#<?= \yii\helpers\Html::getInputId($model, "tanggal") ?>").val();
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail(ele)){
			$(ele).parents('tr').find('input[name*="[tanggal_nota]"]').val( $(ele).parents('tr').find('input[name*="[tanggal_nota]"]').val() );
			$(ele).parents('tr').addClass('animation-loading');
			$.ajax({
				url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasing/pengajuantagihancash/index']); ?>',
				type   : 'POST',
				data   : { formData: $(ele).parents('tr').find('input, textarea').serialize(),tgl:tgl },
				success: function (data) {
					$(ele).parents('tr').find('input[name*="[pengajuan_tagihan_id]"]').val(data.model.pengajuan_tagihan_id);
					$(ele).parents('tr').find('input[name*="[tanggal_nota]"]').val( $(ele).parents('tr').find('input[name*="[tanggal_nota]"]').val() );
					if(data.status){
						$(ele).parents('tr').find('input, textarea, button').attr('disabled','disabled');
						$(ele).parents('tr').find('#place-editbtn').attr('style','display:');
						$(ele).parents('tr').find('#place-cancelbtn').attr('style','display:none');
						$(ele).parents('tr').find('#place-savebtn').attr('style','display:none');
						$(ele).parents('tr').find('#place-deletebtn').attr('style','display:');
                        if(data.html_berkas){
							$(ele).parents('tr').find('.place-view-mode').attr('style','display:');
							$(ele).parents('tr').find('.place-input-mode').attr('style','display:none');
							$(ele).parents('tr').find('.place-view-mode').html("");
							$(ele).parents('tr').find('.place-view-mode').html(data.html_berkas);
						}
						$(ele).parents('tr').removeClass('animation-loading');
						setStatus(ele,data.model.status,data.model.ket);
					}
					reordertable('#table-detail');
				},
				error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
			});
        }
    }
	
    return false;
}

function setStatus(ele,value=null,ket){
	if(value){
		if(value=="DIAJUKAN"){
			value = "<label style='font-size:1rem;' class='label label-warning tooltips' title='Menunggu Konfirmasi Kasir'>"+value+"</label>";
		}else if(value=="DITERIMA"){
			value = "<label style='font-size:1rem;' class='label label-success tooltips' title='"+ket+"'>SUDAH DITERIMA</label>";
		}else if(value=="DITOLAK"){
			value = "<label style='font-size:1rem;' class='label label-danger tooltips' title='"+ket+"'>DITOLAK</label>";
		}
	}else{
		value = "-";
	}
	$(ele).parents("tr").find("td#place-status").html(value);
	$('#table-detail > tbody').find(".tooltips").tooltip({ delay: 50 });
}

function validatingDetail(ele){
    var has_error = 0;
	var field2 = $(ele).parents('tr').find('input[name*="[tanggal_nota]"]');
	if(!field2.val()){
		$(ele).parents('tr').find('input[name*="[tanggal_nota]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[tanggal_nota]"]').parents('td').removeClass('error-tb-detail');
	}
    if(has_error === 0){
        return true;
    }
    return false;
}

function deleteItem(ele){
	var pengajuan_tagihan_id = $(ele).parents('tr').find('input[name*="[pengajuan_tagihan_id]"]').val();
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuantagihancash/delete','id'=>''])?>'+pengajuan_tagihan_id,'modal-delete-record');
}

function infoTBP(terima_bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id,'modal-info-tbp','75%');
}
function infoSPL(id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpl']) ?>?id='+id,'modal-info-spl','75%');
}
function inputKeterangan(ele){
	var value = $(ele).parents('td').find('input[name*="[keterangan_berkas]"]').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuantagihan/inputKeterangan']) ?>?value='+value;
	var modal_id = 'modal-input-keterangan';
	$(".modals-place").load(url, function() {
		$("#"+modal_id+" .modal-dialog").css('width','50%');
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			var ket = $(".modals-place").find("textarea[name*='[keterangan_berkas]']").val();
			$(ele).parents('td').find('input[name*="[keterangan_berkas]"]').val(ket);
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function infoKeterangan(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuantagihan/infoKeterangan']) ?>?id='+id,'modal-info-keterangan','50%');
}
function setHover(ele){
	if(ele){
		$(ele).parents('tr').find('.place-input-mode label').hover(function() {
			$(this).append(' <a onclick="removeBerkas(this)"><i class="fa fa-close"></i></a>')
		}, function() {
			$(this).find('a').remove();
		});
	}else{
		$('#table-detail .place-input-mode label').hover(function() {
			$(this).append(' <a onclick="removeBerkas(this)"><i class="fa fa-close"></i></a>')
		}, function() {
			$(this).find('a').remove();
		});
	}
}
function removeBerkas(ele){
	$(ele).parents('label').prev().remove();
	$(ele).parents('label').remove();
}
function updateBerkas(pengajuan_tagihan_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/tagihanbhp/updateBerkas','pengajuan_tagihan_id'=>''])?>'+pengajuan_tagihan_id+'&cash=1','modal-transaksi',null,'getItems();');
}

</script>