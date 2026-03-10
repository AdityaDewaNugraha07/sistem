<?php
/* @var $this yii\web\View */
$this->title = 'Mutasi Produk';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/mutasigudang/index") ?>"> <?= Yii::t('app', 'Mutasi Gudang'); ?> </a>
					</li>
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/mutasikeluar/index") ?>"> <?= Yii::t('app', 'Mutasi Keluar'); ?> </a>
					</li>
                    <li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/mutasiproduksi/index") ?>"> <?= Yii::t('app', 'Mutasi Ke Produksi'); ?> </a>
					</li>
                    <li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/mutasiproduksi/statuspermintaanbarangjadi") ?>"> <?= Yii::t('app', 'Permintaan Barang Jadi'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Produk'); ?></h4></span>
                                </div>
                                <span class="pull-right">
									<a class="btn dark btn-sm btn-outline" href="<?= yii\helpers\Url::toRoute("/gudang/mutasikeluar/transaksiCepat") ?>"><i class="icon-speedometer"></i> <?= Yii::t('app', 'Transaksi Cepat'); ?></a>
									<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Riwayat Mutasi'); ?></a> 
								</span>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?php 
										if(isset($_GET['mutasi_keluar_id'])){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Barang Jadi'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'nomor_produksi', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->nomor_produksi ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php }else{ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-4 control-label">Kode Barang Jadi</label>
												<div class="col-md-8">
													<span class="input-group-btn" style="width: 100%">
														<?= \yii\bootstrap\Html::activeDropDownList($model, 'persediaan_produk_id', [],['class'=>'form-control select2','onchange'=>'setProduk()']); ?>
													</span>
													<span class="input-group-btn" style="">
														<a class="btn btn-icon-only btn-default tooltips" onclick="currentstock();" data-original-title="Cari Ketersedian Produk" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
														<a class="btn btn-icon-only btn-default tooltips" onclick="scan();" data-original-title="Cari Berdasarkan Scan QR-Code" style="margin-left: 3px; border-radius: 4px;"><i class="icon-frame"></i></a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= \yii\bootstrap\Html::activeHiddenInput($model, "nomor_produksi"); ?>
										<?= $form->field($model, 'tanggal_produksi')->textInput(['readonly'=>'readonly'])->label("Tanggal Produksi"); ?>
										<?= $form->field($model, 'produk_nama')->textInput(['readonly'=>'readonly'])->label("Nama Produk"); ?>
										<?= $form->field($model, 'produk_jenis')->textInput(['readonly'=>'readonly'])->label("Jenis Produk"); ?>
									</div>
									<div class="col-md-6">
										<?= yii\bootstrap\Html::activeHiddenInput($modProduk, 'produk_id') ?>
										<?= yii\bootstrap\Html::activeHiddenInput($modProduk, 'produk_qty_satuan_besar') ?>
										<?= $form->field($model, 'produk_dimensi')->textInput(['disabled'=>'disabled','class'=>'form-control input-medium'])->label("Dimensi"); ?>
										<?= $form->field($modProduk, 'produk_satuan_besar')->textInput(['readonly'=>'readonly','class'=>'form-control input-small'])->label("Satuan Besar"); ?>
										<?= $form->field($modProduk, 'produk_qty_satuan_kecil')->textInput(['readonly'=>'readonly','class'=>'form-control input-small'])->label("Qty Mutasi"); ?>
										<?= $form->field($modProduk, 'produk_satuan_kecil')->textInput(['readonly'=>'readonly','class'=>'form-control input-small'])->label("Satuan Kecil"); ?>
										<?= $form->field($modProduk, 'kapasitas_kubikasi')->textInput(['readonly'=>'readonly','class'=>'form-control input-small'])->label("Kubikasi m<sup>3</sup>"); ?>
									</div>
								</div>
								<hr>
								<div class="row">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Data Mutasi'); ?></h4>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-md-6">
										<?php // echo $form->field($model, 'cara_keluar')->dropDownList( ['Kembali Produksi'=>'Kembali Produksi','Kebutuhan Internal'=>'Kebutuhan Internal'] ,['class'=>'form-control']); ?>
										<?php echo $form->field($model, 'cara_keluar')->dropDownList( ['Kebutuhan Internal'=>'Kebutuhan Internal'] ,['class'=>'form-control']); ?>
										<?php // echo $form->field($model, 'cara_keluar')->dropDownList( ['Kebutuhan Internal'=>'Kebutuhan Internal','Kebutuhan 35 Container'=>'Kebutuhan 35 Container'] ,['class'=>'form-control']); ?>
										<?php 
										if(!isset($_GET['mutasi_keluar_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Mutasi'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'tanggal',[
															'template'=>'{label}<div class="col-md-7"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
															<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
															{error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Mutasi"); ?>
										<?php // echo $form->field($model, 'gudang_asal')->dropDownList( \app\models\MGudang::getOptionList() ,['class'=>'form-control select2','prompt'=>'']); ?>
										<?= \yii\bootstrap\Html::activeHiddenInput($model, 'gudang_asal') ?>
                                        <?php
                                        if(isset($_GET['success']) && isset($_GET['mutasi_keluar_id'])){
                                            $gudang_asal_display = Yii::$app->db->createCommand('select gudang_nm from m_gudang where gudang_id = '.$modPersediaan->gudang_id.'')->queryScalar();
                                            $model->gudang_asal_display = $gudang_asal_display;
                                        } else {
                                            $model->gudang_asal_display = $model->gudang_asal_display;
                                        }
                                        ?>
                                        <?php echo $form->field($model, 'gudang_asal_display')->textInput(['readonly'=>'readonly'])->label("Gudang Asal"); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'pegawai_mutasi')->dropDownList(\app\models\MPegawai::getOptionListWithDeptName(  ),['class'=>'form-control select2','prompt'=>'']); ?>
                                        <?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <div id="msg_save_button" style="display: none;" class="text-success">Silahkan Menunggu, inputan Anda sedang diproses...</div>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
$pagemode = "";
if(isset($_GET['mutasi_keluar_id'])){
    $pagemode = "afterSave(".$_GET['mutasi_keluar_id'].");";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Mutasi'))."');
	$('select[name*=\"[petugas_penerima]\"]').select2({
		allowClear: !0,
		placeholder: 'Nama Petugas',
	});
	$('select[name*=\"[persediaan_produk_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Barang Jadi',
		ajax: {
			url: '".\yii\helpers\Url::toRoute('/gudang/mutasikeluar/FindProdukActive')."',
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
	$('select[name*=\"[persediaan_produk_id]\"]').siblings('.select2-container').css('width','100%');
", yii\web\View::POS_READY); ?>
<script>
function setProduk(){
	var persediaan_produk_id = $("#<?= yii\bootstrap\Html::getInputId($model, "persediaan_produk_id") ?>").val();
	if(persediaan_produk_id){
		$.ajax({
			url    : '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/getProduk']); ?>',
			type   : 'POST',
			data   : {persediaan_produk_id:persediaan_produk_id},
			success: function (data) {
				$('#<?= yii\bootstrap\Html::getInputId($model, "nomor_produksi") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($model, "tanggal_produksi") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_nama") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_jenis") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_dimensi") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($model, "gudang_asal") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($model, "gudang_asal_display") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_id") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_satuan_besar") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_qty_satuan_besar") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_qty_satuan_kecil") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_satuan_kecil") ?>').val("");
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "kapasitas_kubikasi") ?>').val("");
				if(data.model){
					$('#<?= yii\bootstrap\Html::getInputId($model, "nomor_produksi") ?>').val(data.nomor_produksi);
					$('#<?= yii\bootstrap\Html::getInputId($model, "tanggal_produksi") ?>').val(data.tanggal_produksi);
					$('#<?= yii\bootstrap\Html::getInputId($model, "produk_nama") ?>').val(data.model.produk_nama);
					$('#<?= yii\bootstrap\Html::getInputId($model, "produk_jenis") ?>').val(data.model.produk_group);
					$('#<?= yii\bootstrap\Html::getInputId($model, "produk_dimensi") ?>').val(data.model.produk_dimensi);
					$('#<?= yii\bootstrap\Html::getInputId($model, "gudang_asal") ?>').val(data.persediaan.gudang_id);
					$('#<?= yii\bootstrap\Html::getInputId($model, "gudang_asal_display") ?>').val(data.gudang_asal_display);
					$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_id") ?>').val(data.persediaan.produk_id);
					$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_satuan_besar") ?>').val(data.model.produk_satuan_besar);
					$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_qty_satuan_besar") ?>').val(data.persediaan.in_qty_palet);
					$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_qty_satuan_kecil") ?>').val(data.persediaan.in_qty_kecil);
					$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_satuan_kecil") ?>').val(data.persediaan.in_qty_kecil_satuan);
					$('#<?= yii\bootstrap\Html::getInputId($modProduk, "kapasitas_kubikasi") ?>').val(data.persediaan.in_qty_m3);
				}
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
	}
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
		$('#btn-save').hide();
		$('#msg_save_button').show();
        submitform($form);
    }
    return false;
}

function afterSave(id){
	setProduk();
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("readonly","readonly"); });
    $('#<?= yii\bootstrap\Html::getInputId($model, 'pegawai_mutasi') ?>').attr('disabled','');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_produksi') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	setTimeout(function(){
		<?php if(!empty($modDetail)){ ?>
		setTotal();
		<?php } ?>
	},1000);
}

function getItemsByPk(id){
	$('#table-detail > tbody').addClass('animation-loading');
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/getItemsByPk']); ?>',
        type   : 'POST',
        data   : {id:id},
        success: function (data) {
            if(data.html){
                $('#table-detail > tbody').html(data.html);
				reordertable('#table-detail');
				$('#table-detail > tbody').removeClass('animation-loading');
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/gudang/mutasikeluar/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function currentstock(produk_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/gudang/availablestockproduk/currentStock']); ?>','modal-currentstock','90%');
}
function pick(prod_number){ 
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/mutasigudang/pick']); ?>',
        type   : 'POST',
        data   : {prod_number:prod_number},
        success: function (data) {
			if(data.persediaan_produk_id){
				clearmodal();
				$("#<?= yii\bootstrap\Html::getInputId($model, "persediaan_produk_id") ?>").empty().append('<option value="'+data.persediaan_produk_id+'">'+prod_number+'</option>').val(data.persediaan_produk_id).trigger('change');
			}else{
				if(data.msg){
					cisAlert(data.msg);
				}else{
					cisAlert("Data tidak ditemukan");
				}
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function scan(){
	var url = '<?= \yii\helpers\Url::toRoute(['/gudang/mutasikeluar/scanMutasi']) ?>';
	var modal_id = 'modal-info';
	var width = '390px';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id+" .modal-dialog").css('width',width);
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function () {
			stop_reading();
		});
		spinbtn();
		draggableModal();
	});
}
</script>