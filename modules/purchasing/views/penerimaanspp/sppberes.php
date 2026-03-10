<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan SPP';
\app\assets\DatatableAsset::register($this);
\app\assets\Select2Asset::register($this);
\app\assets\DatepickerAsset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Penerimaan SPP (Surat Permintaan Pembelian)'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/index"); ?>"> <?= Yii::t('app', 'PO Baru'); ?> </a>
                    </li>
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/podibuat"); ?>"> <?= Yii::t('app', 'PO Dibuat'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/spl/index"); ?>"> <?= Yii::t('app', 'SPL Baru'); ?> </a>
                    </li>
                    <li class="">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/index"); ?>"> <?= Yii::t('app', 'SPP Masuk'); ?> </a>
                    </li>
                    <li class="">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/sppmasuk"); ?>"> <?= Yii::t('app', 'SPP Masuk Detail'); ?> </a>
                    </li>
                    <li class="active">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/sppberes"); ?>"> <?= Yii::t('app', 'SPP Complete'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/dpbhp/index"); ?>"> <?= Yii::t('app', 'Downpayment'); ?> </a>
                    </li>
                </ul>
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet light bordered form-search">
							<div class="portlet-title">
								<div class="tools panel-cari">
									<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
									<span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
								</div>
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
										<div class="col-md-6">
											<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
											<?= $form->field($model, 'bhp_nm')->textInput(['placeholder'=>'Cari Berdasarkan Nama Item'])->label(Yii::t('app', 'Nama Items')); ?>
											<?php echo $form->field($model, 'sppd_ket')->textInput(['placeholder'=>'Cari Berdasarkan Keterangan'])->label(Yii::t('app', 'Keterangan')); ?>
										</div>
										<div class="col-md-5">
											<?= $form->field($model, 'spp_kode')->textInput(['placeholder'=>'Cari Berdasarkan Kode SPP'])->label(Yii::t('app', 'Kode SPP')); ?>
											<?= $form->field($model, 'suplier_id')->dropDownList([],['prompt'=>'All','class'=>'select2'])->label(Yii::t('app', 'Supplier')); ?>
										</div>
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
                    <div class="col-md-12" style="margin-left: -15px; margin-right: -15px;">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-list"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'List Semua SPP Yang Masuk'); ?></span>
                                </div>
								<div class="pull-right">
									<a class="btn btn-default btn-sm tooltips" onclick="riwayatPenerimaan()"><i class="fa fa-list"></i> Riwayat Penerimaan</a>
								</div>
                            </div>
                            <div class="portlet-body" style="margin-left: -15px; margin-right: -15px;">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover" id="table-list" style="width: 100%;">
										<thead style="background-color: #B2C4D3">
											<tr>
												<th style="text-align: center; width: 40px;"><?= Yii::t('app', 'No.'); ?></th>
												<th style="text-align: center; width: 110px; font-size: 1rem; line-height: 1;"><?= Yii::t('app', 'Kode SPP <br>Tanggal'); ?></th>
												<th style="text-align: center; width: 150px;"><?= Yii::t('app', 'Item'); ?></th>
												<th style="text-align: center; width: 40px; font-size: 0.9rem; line-height: 1;"><?= Yii::t('app', 'Qty<br> Order'); ?></th>
												<th style="text-align: center; width: 40px; font-size: 0.9rem; line-height: 1;"><?= Yii::t('app', 'Qty<br> Terbeli'); ?></th>
												<th style="text-align: center;"><?= Yii::t('app', 'Supplier'); ?></th>
												<th style="text-align: center; width: 50px;"><?= Yii::t('app', 'Status'); ?></th>
												<th style="text-align: center; width: 150px;"><?= Yii::t('app', 'Keterangan'); ?></th>
												<th style="text-align: center; width: 2%; font-size: 1rem;"><?= Yii::t('app', 'Spb'); ?></th>
												<th style="text-align: center; width: 100px; font-size: 1rem; line-height: 1;"><?= Yii::t('app', 'Penawaran<br>Terpilih'); ?></th>
												<th style="text-align: center; width: 75px; font-size: 1rem; line-height: 1;"><?= Yii::t('app', 'Reff<br>Pembelian'); ?></th>
												<th style="text-align: center; width: 75px; font-size: 1rem; line-height: 1;"><?= Yii::t('app', 'Reff<br>Penerimaan'); ?></th>
											</tr>
										</thead>
										<tbody>
											<tr><td colspan="12" style="text-align: center;"><?= Yii::t('app', 'No Data Available'); ?></td></tr>
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
formconfig();
getItems(); 
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Bahan Pembantu'))."');
$('#form-search').submit(function(){
    getItems();
	return false;
});
$('#".\yii\bootstrap\Html::getInputId($model, 'suplier_id')."').select2({
	allowClear: !0,
	placeholder: 'Cari Berdasarkan Supplier',
	width: '245px',
	ajax: {
		url: '". \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/findSupplier') ."',
		dataType: 'json',
		delay: 250,
		processResults: function (data) {
			return {
				results: data
			};
		},
		cache: true
	}
});
", yii\web\View::POS_READY); ?>
<script>
function getItems_developed(){
    var dt_table =  $('#table-list').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/sppmasuk') ?>',
			type:'POST',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function getItems(){
	$('#table-list > tbody').addClass('animation-loading');
	var formdata = $('#form-search').serialize();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/getItems']); ?>',
        type   : 'POST',
        data   : {search:true,formdata:formdata},
        success: function (data){
			if(data.items){
				$('#table-list > tbody').html("");
				$.each(data.items, function( index, value ) {
                    console.log(value.qty_terbeli);
					if (value.qty_retur > 0) {
						var qty_terbelix = ( (value.qty_terbeli - value.qty_retur) ? value.qty_terbeli-value.qty_retur : value.qty_terbeli) + '</b><br>' + ( (value.bhp_satuan)?value.bhp_satuan:"" );
					} else {
						var qty_terbelix = value.qty_terbeli + '</b><br>' + ( (value.bhp_satuan)?value.bhp_satuan:"" );
					}
                    
                    var complete = value.status_spp_detail.search("COMPLETE");
                    var closed = value.status_spp_detail.search("CLOSED");

                    if (complete > 0 || closed > 0) {
    					var html = '<tr class="row-'+(index+1)+'" style="background-color: #F5F5F5;">\n\
									<td class="td-kecil"  style="font-size:1.2rem; text-align: center;">\n\
										<span class="no_urut">'+(index+1)+'</span>\n\
										<input type="hidden" id="sppd_id-'+(value.sppd_id)+'" value="'+value.sppd_id+'">\n\
									</td>\n\
									<td class="td-kecil"  style="font-size:1.2rem; text-align:center;">\n\
										<b>'+value.spp_kode+'</b><br>'+value.spp_tanggal+'\n\
									</td>\n\
									<td class="td-kecil"  style="font-size:1.2rem;">\n\
										'+( (value.bhp_kode)?"<b>"+value.bhp_kode+"</b><br>":"" )+'\n\
										'+value.bhp_nm2+'\n\
									</td>\n\
									<td class="td-kecil" style="text-align: center; font-size: 1.1rem">\n\
										<b>'+( (value.sppd_qty)?value.sppd_qty:"0" )+'</b><br>'+( (value.bhp_satuan)?value.bhp_satuan:"" )+'\n\
									</td>\n\
									<td class="td-kecil" style="text-align: center; font-size: 1.1rem">\n\
										\n\ <?php /* 2020-06-30 qty_terbeli - qty_retur */?> \n\
										<b>'+qty_terbelix+'\n\
									</td>\n\
									<td class="td-kecil" style="padding:3px;"> \n\
										'+value.html_suplier+'\n\
									</td>\n\
									<td class="td-kecil" style="text-align: center;">\n\
										'+value.status_spp_detail+'\n\
									</td>\n\
									<td class="td-kecil" style="font-size: 1.1rem !important;">\n\
										'+( (value.sppd_ket)?value.sppd_ket:"<center>-</center>" )+'\n\
									</td>\n\
									<td class="td-kecil" style="font-size: 0.9rem; text-align: center; vertical-align: middle;">\n\
										<a href="javascript:void(0);" onclick="sppTerkait(\''+value.spp_id+'\')"><i class="fa fa-eye" aria-hidden="true"></i></a>\n\
									</td>\n\
									<td class="td-kecil" id="place-penawaran" style="font-size: 1rem !important; text-align: center; vertical-align: middle;">\n\
										'+value.html_penawaran+'\n\
									</td>\n\
									<td class="td-kecil" style="font-size: 1rem !important; text-align: center; vertical-align: middle;">\n\
										'+value.reff_beli+'\n\
									</td>\n\
									<td class="td-kecil" style="font-size: 1rem !important; text-align: center; vertical-align: middle;">\n\
										'+value.reff_terima+'\n\
									</td>\n\
								<tr>';
                    }
                    $('#table-list > tbody').append(html);
					$('#table-list > tbody > tr.row-'+(index+1)).find('select[name*="[suplier_id]"]').select2({
						allowClear: !0,
						placeholder: 'Pilih Supplier',
//						width: '200px',
						ajax: {
							url: '<?= \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/findSupplier') ?>',
							dataType: 'json',
							delay: 250,
							processResults: function (data) {
								return {
									results: data
								};
							},
							cache: true
						}
					});
					$('#table-list > tbody > tr.row-'+(index+1)).find('.select2-selection').css('font-size','1.1rem');
					$('#table-list > tbody > tr.row-'+(index+1)).find('.select2-selection').css('padding-left','3px');
				});
				
			}else{
				$('#table-list > tbody').html("<tr><td colspan='12' style='text-align: center;'>No Data Available</td></tr>");
			}
			$('#table-list > tbody').removeClass('animation-loading');
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function dtDetail(){
    var dt_table =  $('#table-list').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/sppmasuk') ?>',data:{dt: 'table-list'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, width: '12%' },
            {	targets: 2, width: '10%' },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/info','id'=>'']) ?>'+id,'modal-info',null,'$(\'#table-list\').dataTable().fnClearTable();');
}

function setSupplier(ele,sppd_id){
	var suplier_id = $(ele).val();
	$(ele).parents('tr').find('.select2').addClass('animation-loading');
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/setSupplier']); ?>',
		type   : 'POST',
		data   : {suplier_id:suplier_id,sppd_id:sppd_id},
		success: function (data) {
			if(data){
				$(ele).parents('tr').find('.select2').removeClass('animation-loading');
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function sppTerkait(sppd_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/spbTerkait','id'=>'']) ?>'+sppd_id,'modal-spb-terkait',null,'');
}

function infoSPB(spb_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpb','id'=>'']) ?>'+spb_id;
	var modal_id = 'modal-info-spb';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}


function infoSPO(spo_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpo']) ?>?id='+spo_id+"&bhp_id="+bhp_id,'modal-info-spo','75%','');
}
function infoSPL(spl_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpl']) ?>?id='+spl_id+"&bhp_id="+bhp_id,'modal-info-spl','75%','');
}
function riwayatPenerimaan(){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/riwayatPenerimaan']) ?>','modal-riwayat','85%');
}
function infoTBP(terima_bhp_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id+'&bhp_id='+bhp_id,'modal-info-tbp','75%');
}
function infoReturBHP(spo_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoReturBHP']) ?>?id='+spo_id,'modal-info-spo','75%','');
}
function closeSPP(sppd_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/closespp']) ?>?id='+sppd_id,'modal-closespp');
}

function penawaran(bhp_id,sppd_id,spod_id=null,spld_id=null){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/penawaran','bhp_id'=>'']) ?>'+bhp_id+"&sppd_id="+sppd_id;
	var modal_id = 'modal-penawaran';	
	$(".modals-place").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id+" .modal-dialog").css('width',"80%");
		$("#"+modal_id).on('hidden.bs.modal', function () {
			var data_checked = $('#modal-penawaran #current_data').val();
			if(spod_id==null && spld_id==null){
				updatePenawaran(sppd_id,data_checked);
			}
			$("#"+modal_id).hide();
			$("#"+modal_id).remove();
			$('.modal-backdrop').remove();
			$(".modals-place").html("");
		});
		spinbtn();
		draggableModal();
	});
}

function updatePenawaran(sppd_id,data_checked){
	$('#table-list #sppd_id-'+sppd_id).parents('tr').addClass("animation-loading");
	$.post( '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/updatePenawaran']) ?>',{ sppd_id: sppd_id, data_checked: data_checked } , function( data ) {
		$('#table-list #sppd_id-'+sppd_id).parents('tr').find("#place-penawaran").html(data.html_penawaran);
		$('#table-list #sppd_id-'+sppd_id).parents('tr').removeClass("animation-loading");
	});
}

function createPenawaran(sppd_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/createPenawaran','id'=>'']) ?>'+sppd_id;
	var modal_id = 'modal-create-penawaran';	
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function () {
			$("#"+modal_id).hide();
			$("#"+modal_id).remove();
		});
		spinbtn();
		draggableModal();
	});
}

function infoPenawaran(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/infoPenawaran','id'=>'']) ?>'+id+"&disableDelete=1";
	var modal_id = 'modal-info-penawaran';	
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function () {
			$("#"+modal_id).hide();
			$("#"+modal_id).remove();
			$("#table-penawaran").dataTable().fnClearTable();
		});
		spinbtn();
		draggableModal();
	});
}

function penawaranTerpilih(sppd_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/penawaranTerpilih','id'=>'']) ?>'+sppd_id+'&by=SPP','modal-penawaran','80%');
}
</script>