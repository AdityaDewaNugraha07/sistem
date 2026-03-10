<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Permintaan Barang';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Permintaan Barang <small>( Tarik Barang Dari Gudang Ke Produksi )</small></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
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
						<a href="<?= Url::toRoute("/ppic/pengajuanrepacking/index") ?>"> <?= Yii::t('app', 'Permintaan'); ?> </a>
					</li>
					<li class="">
						<a href="<?= Url::toRoute("/ppic/pengajuanrepacking/status") ?>"> <?= Yii::t('app', 'Status Permintaan'); ?> </a>
					</li>
					<li class="active">
						<a href="<?= Url::toRoute("/ppic/pengajuanrepacking/kirimgudang") ?>"> <?= Yii::t('app', 'Kirim Kembali Ke Gudang'); ?> </a>
					</li>
				</ul>
				<div class="row" style="margin-bottom: 10px; margin-top: 20px;">
					<div class="col-md-6">
						<?= $form->field($model, 'hasil_dari')->dropDownList(["Repacking"=>"Repacking","Regrade"=>"Regrade","Restamp"=>"Restamp","Penanganan Barang Retur"=>"Penanganan Barang Retur"],['class'=>'form-control','prompt'=>'','onchange'=>'setHasildari();'])->label("Hasil Dari"); ?>
                                            <!--,"Proses Produksi"=>"Proses Produksi"-->
                        <?php echo $form->field($model, 'hasil_dari_retur')->dropDownList(["Regrade"=>"Regrade","Repair"=>"Repair"],['class'=>'form-control','prompt'=>'', 'style'=>'display: none;'])->label("Proses", ['id' => 'label-hasil-dari-retur', 'style' => 'display: none;']); ?>
					</div>
					<div class="col-md-6">
						<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Riwayat Pengiriman'); ?></a>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Palet Asal'); ?></h4></span>
                                </div>
                            </div>
                            <?= yii\bootstrap\Html::activeHiddenInput($model, 'hasil_repacking_id') ?>
                            <div class="portlet-body">
                                <div class="row" style="margin-top: -20px;">
                                    <div class="col-md-12">
                                        <div class="table-scrollable">
                                            <table id="table-detail-paletasal" class="table table-striped table-bordered table-advance table-hover" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th class="td-kecil" style="width: 60px;">No.</th>
                                                        <th class="td-kecil" style="width: 160px;">Kode Permintaan<br>Tanggal</th>
                                                        <th class="td-kecil" style="">Kode Barang Jadi / Produk</th>
                                                        <th class="td-kecil" style="width: 60px;">Pcs</th>
                                                        <th class="td-kecil" style="width: 80px;">M<sup>3</sup></th>
                                                        <th class="td-kecil" style="width: 100px; line-height: 1">Tanggal<br>Mutasi Keluar</th>
                                                        <th class="td-kecil" style="width: 100px; line-height: 1">Tanggal<br>Terima Mutasi</th>
                                                        <th class="td-kecil"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2">
                                                            <a class="btn btn-xs grey" id="add-paletasal"><i class="fa fa-plus"></i> Add</a>
                                                            <a class="btn btn-xs grey" id="add-paletasal-retur" style="display: none;"><i class="fa fa-plus"></i> Add</a>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
								<div class="row">
                                    <div class="col-md-6" style="margin-bottom: 20px; ">
                                        <h4><?= Yii::t('app', 'Data Palet Baru'); ?></h4>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-md-6">
										<?php if(!isset($_GET['hasil_repacking_id'])){ ?>
                                        <?= $form->field($model, 'produk_id',['template'=>'<div class="form-group" style="margin-bottom: 5px;">{label}
                                                                                                <div class="col-md-8" style="padding-left: 20px;">
                                                                                                    <span class="input-group-btn" style="width: 90%">{input}</span>
                                                                                                    <span class="input-group-btn" style="width: 10%">
                                                                                                        <a class="btn btn-icon-only btn-default tooltips" onclick="masterProduk();" data-original-title="Master Produk" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
                                                                                                    </span>
                                                                                                    {error}
                                                                                                </div>
                                                                                            </div>'])
                                                    ->dropDownList([],['class'=>'form-control select2','prompt'=>'','onchange'=>'setProduk(); generateNomorProduksi();'])->label('Kode Produk'); ?>
										<?php }else{ ?>
											<?= yii\bootstrap\Html::activeHiddenInput($model, 'produk_id') ?>
											<?= $form->field($model, 'produk_kode')->textInput(); ?>
										<?php } ?>
										<?= $form->field($model, 'produk_nama')->textInput(['disabled'=>'disabled'])->label("Nama Produk"); ?>
										<?= $form->field($model, 'produk_jenis')->textInput(['disabled'=>'disabled'])->label("Jenis Produk"); ?>
										<?= $form->field($model, 'produk_dimensi')->textInput(['disabled'=>'disabled','style'=>'height: 40px; width: 380px; font-weight:700; font-size:2.2rem; padding:2px;  font-family: arial'])->label("Dimensi"); ?>
									</div>
									<div class="col-md-6">
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'qty_palet') ?>
										<?= $form->field($model, 'qty_besar_satuan')->textInput(['disabled'=>'disabled','class'=>'form-control input-small'])->label("Satuan Besar"); ?>
										<?= $form->field($model, 'qty_kecil')->textInput(['class'=>'form-control input-small float','onblur'=>'setMeterKubik();'])->label("Qty Kirim"); ?>
										<?= $form->field($model, 'qty_kecil_satuan')->textInput(['disabled'=>'disabled','class'=>'form-control input-small'])->label("Satuan Kecil"); ?>
										<?= $form->field($model, 'qty_m3_display')->textInput(['disabled'=>'disabled','class'=>'form-control input-small float'])->label("Kubikasi m<sup>3</sup>"); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'qty_m3') ?>
										<?= yii\bootstrap\Html::activeHiddenInput($modProduk, 'produk_p') ?>
										<?= yii\bootstrap\Html::activeHiddenInput($modProduk, 'produk_l') ?>
										<?= yii\bootstrap\Html::activeHiddenInput($modProduk, 'produk_t') ?>
										<?= yii\bootstrap\Html::activeHiddenInput($modProduk, 'produk_p_satuan') ?>
										<?= yii\bootstrap\Html::activeHiddenInput($modProduk, 'produk_l_satuan') ?>
										<?= yii\bootstrap\Html::activeHiddenInput($modProduk, 'produk_t_satuan') ?>
									</div>
								</div>
                                <div class="row">
                                    <div class="col-md-6">
										<?php 
										if(!isset($_GET['hasil_repacking_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Kode Pengiriman");
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Pengiriman'); ?></label>
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
										<?= $form->field($model, 'tanggal')->textInput(['disabled'=>'disabled','class'=>'form-control input-small'])->label("Tanggal Pengiriman"); ?>
										<?= $form->field($model, 'jenis_palet')->inline(true)->radioList(['Biasa'=>'Biasa','Khusus'=>'Random'],['style'=>'margin-left:20px','onchange'=>'showTablePenerimaanKhusus();']); ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($modProduksi, 'tanggal_produksi',[
															'template'=>'{label}<div class="col-md-7"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
															<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
															{error}</div>'])->textInput(['disabled'=>'disabled','onchange'=>'generateNomorProduksi();'])->label("Tanggal Produksi"); ?>
										<div class="place_prodsection_plymill" style="display: none;">
											<?php echo $form->field($modProduksi, 'plymill_shift')->inline(true)->checkboxList(\app\models\MDefaultValue::getOptionList('plymill-shift'), ['template' => '{label}<div class="col-md-7" style="margin-top: -10px;"><div class="mt-checkbox-inline"> {input} </div></div>','onchange'=>'generateNomorProduksi();']); ?>
										</div>
										<div class="place_prodsection_sawmill" style="display: none;">
											<?= $form->field($modProduksi, 'sawmill_line')->dropDownList(\app\models\MDefaultValue::getOptionList('sawmill-line'),['class'=>'form-control','prompt'=>'','onchange'=>'generateNomorProduksi();']) ?>
										</div>
										<?= $form->field($modProduksi, 'nomor_urut_produksi')->textInput(['class'=>'form-control numbers-only','onblur'=>'generateNomorProduksi();']); ?>
										<?= $form->field($modProduksi, 'nomor_produksi')->textInput(['style'=>'font-weight:bold','disabled'=>'disabled']); ?>
                                        <?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                </div>
                                <br><hr class="place-khusus" style="display: none;">
                                <div class="row place-khusus" style="display: none;">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Komposisi Palet Random'); ?></h4>
                                    </div>
									<div class="col-md-7">
										<label class="pull-right" style="margin-top:20px; margin-bottom: -20px; text-align: right;">
											<span id="lihatdetailSPB">
												<?php
												if(isset($_GET['mutasi_gudanglogistik_id'])){
													echo "<a onclick='infoSpb(".$model->spb_id.")'>Lihat Detail SPB : <b>".$model->spb->kode."</b></a>";
												}
												?>
											</span>
										</label>
									</div>
                                </div>
                                <div class="row place-khusus" style="display: none;">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px;">No.</th>
														<th colspan="3"><?= Yii::t('app', 'Dimensi'); ?></th>
														<th rowspan="3"><?= Yii::t('app', 'Qty'); ?></th>
														<th rowspan="2"><?= Yii::t('app', 'Volume m<sup>3</sup>'); ?></th>
														<th rowspan="2"><?= Yii::t('app', 'Keterangan'); ?></th>
														<th rowspan="2" style="width: 50px;"><?= Yii::t('app', 'Cancel'); ?></th>
													</tr>
													<tr>
														<th>Tebal</th>
														<th>Lebar</th>
														<th>Panjang</th>
													</tr>
												</thead>
												<tbody id="xxx">
												</tbody>
												<tfoot>
													<tr>
														<td colspan="4">
															<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
														</td>
														<td style="width: 150px; padding: 3px;">
															<span class="input-group-btn" style="width: 50%">
																<?= \yii\helpers\Html::activeTextInput($model, 'total_qty', ['class'=>'form-control float','style'=>'font-size:1.3rem; padding:3px; font-weight:600;','onblur'=>'setInduk()','disabled'=>'disabled']); ?>
															</span>
															<span class="input-group-btn" style="width: 50%">
																<?= \yii\helpers\Html::activeDropDownList($model, 'total_qty_satuan',\app\models\MDefaultValue::getOptionList('produk-satuan-kecil'),['class'=>'form-control','style'=>'padding: 3px; font-size:1.2rem;','onchange'=>'setInduk()']); ?>
															</span>
														</td>
														<td style="width: 100px; padding: 3px;">
															<?= \yii\bootstrap\Html::activeTextInput($model, "total_m3",['class'=>'form-control float','style'=>'font-size:1.3rem; padding:3px; font-weight:600;','disabled'=>'disabled']) ?>
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
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
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
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-barcode.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php
$pagemode = "";
if(isset($_GET['hasil_repacking_id'])){
    $pagemode = "afterSave(".$_GET['hasil_repacking_id']."); showTablePenerimaanKhusus();";
}else{
	$pagemode = "generateNomorProduksi();";
}
?>
<?php $this->registerJs(" 
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Permintaan Barang'))."');
    $pagemode
	formconfig();
    $('select[name*=\"[hasil_dari]\"]').select2({
		allowClear: true,
        placeholder: 'Pilih Keperluan',
    });
	$('select[name*=\"[produk_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Produk',
		ajax: {
			url: '". Url::toRoute('/ppic/hasilproduksi/findProduk')."',
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
	$('input:radio[name*=\"[jenis_palet]\"][value*=\"Biasa\"]').prop('disabled','disabled');
	$('input:radio[name*=\"[jenis_palet]\"][value*=\"Khusus\"]').prop('disabled','disabled');
", yii\web\View::POS_READY); ?>
<script>
function setHasildari(){
    $("#<?= yii\bootstrap\Html::getInputId($model, "hasil_dari_retur") ?>").val('');
    var hasil_dari = $("#<?= \yii\helpers\Html::getInputId($model, "hasil_dari") ?>").val();
    if(hasil_dari){
        $('#table-detail-paletasal tbody').empty();
        if(hasil_dari == 'Penanganan Barang Retur'){
            $('#add-paletasal-retur').css('display', '');
            $('#add-paletasal').css('display', 'none');
            $('#add-paletasal-retur').removeClass("grey");
            $('#add-paletasal-retur').addClass("blue-steel");
            $('#add-paletasal-retur').attr("onclick","paletRetur();");
            $("#<?= \yii\helpers\Html::getInputId($model, "hasil_dari_retur") ?>").css('display', '');
            $("#label-hasil-dari-retur").css('display', '');
        } else {
            $('#add-paletasal-retur').css('display', 'none');
            $('#add-paletasal').css('display', '');
            $('#add-paletasal').removeClass("grey");
            $('#add-paletasal').addClass("blue-steel");
            $('#add-paletasal').attr("onclick","paletlama()");
            $("#<?= \yii\helpers\Html::getInputId($model, "hasil_dari_retur") ?>").css('display', 'none');
            $("#label-hasil-dari-retur").css('display', 'none');
        }
    }else{
        $('#add-paletasal').removeClass("blue-steel");
        $('#add-paletasal').addClass("grey");
        $('#add-paletasal').removeAttr("onclick");
    }
}
function paletlama(ele){
	var url = '<?= Url::toRoute(['/ppic/pengajuanrepacking/paletditerima']); ?>';
	$(".modals-place-2-min").load(url, function() {
		$("#modal-palet .modal-dialog").css('width','85%');
		$("#modal-palet").modal('show');
		$("#modal-palet").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pickPalet(nomor_produksi, cara_keluar){
	$.ajax({
        url    : '<?= Url::toRoute(['/ppic/pengajuanrepacking/pickpalet']); ?>',
        type   : 'POST',
        data   : {nomor_produksi:nomor_produksi, cara_keluar:cara_keluar},
        success: function (data) {
			if(data){
				var already = [];
                $('#table-detail-paletasal > tbody > tr').each(function(){
                    var nomor_produksi = $(this).find('input[name*="[nomor_produksi_lama]"]');
                    if( nomor_produksi.val() ){
                        already.push(nomor_produksi.val());
                    }
                });
                if( $.inArray( data.nomor_produksi.toString(), already ) != -1 ){ // Jika ada yang sama
                    cisAlert("Palet ini sudah dipilih di list");
                    return false;
                }else{
                    $("#modal-palet").find('button.fa-close').trigger('click');
                    $("#table-detail-paletasal > tbody").append(data.html);
                    reordertable("#table-detail-paletasal");
                }
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
    
function setProduk(){
	var produk_id = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_id") ?>").val();
    $.ajax({
        url    : '<?= Url::toRoute(['/gudang/penerimaanko/getProduk']); ?>',
        type   : 'POST',
        data   : {produk_id:produk_id},
        success: function (data) {
			$('#<?= yii\bootstrap\Html::getInputId($model, "produk_kode") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "produk_nama") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "produk_jenis") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "produk_dimensi") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_besar_satuan") ?>').val("");
			<?php if(!isset($_GET['hasil_repacking_id'])){ ?>
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_palet") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_kecil_satuan") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_m3") ?>').val("");
			<?php } ?>
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_p") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_l") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_t") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_p_satuan") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_l_satuan") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_t_satuan") ?>').val("");
			<?php if(!isset($_GET['hasil_repacking_id'])){ ?>
			$("input:radio[name*='[jenis_palet]'][value*='Biasa']").prop('checked',true);
			<?php } ?>
			if(data.model){
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_kode") ?>').val(data.model.produk_kode);
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_nama") ?>').val(data.model.produk_nama);
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_jenis") ?>').val(data.model.produk_group);
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_dimensi") ?>').val(data.model.produk_dimensi);
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_besar_satuan") ?>').val(data.model.produk_satuan_besar);
				<?php if(!isset($_GET['hasil_repacking_id'])){ ?>
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_palet") ?>').val("1");
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>').val(data.model.produk_qty_satuan_kecil);
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_kecil_satuan") ?>').val(data.model.produk_satuan_kecil);
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_m3") ?>').val(data.model.kapasitas_kubikasi);
				<?php } ?>
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_p") ?>').val(data.model.produk_p);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_l") ?>').val(data.model.produk_l);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_t") ?>').val(data.model.produk_t);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_p_satuan") ?>').val(data.model.produk_p_satuan);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_l_satuan") ?>').val(data.model.produk_l_satuan);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_t_satuan") ?>').val(data.model.produk_t_satuan);
			}
			setProdSection();
            
			<?php if(!isset($_GET['hasil_repacking_id'])){ ?>
			// Tentukan Jenis Penerimaan
			var p = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_p') ?>').val() );
			var l = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_l') ?>').val() );
			var t = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_t') ?>').val() );
			if( p <= 0 || l <= 0 || t <= 0){
				// Random
				$("input:radio[name*='[jenis_palet]'][value*='Khusus']").prop('checked',true);
			}else{
				// Non-Random
				$("input:radio[name*='[jenis_palet]'][value*='Biasa']").prop('checked',true);
			}
			showTablePenerimaanKhusus();
			$("#<?= yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>").focus();
			<?php } ?>
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function showTablePenerimaanKhusus(){
    $("#xxx").empty();
    $('#thasilrepacking-total_qty').val(0);
    $('#thasilrepacking-total_m3').val(0.0000);    
    if( $("input:radio[name*='[jenis_palet]']:checked").val() == "Biasa" ){
		$('.place-khusus').css('display','none');
		$('#<?= \yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>').removeAttr("disabled");
	}else{
		$('.place-khusus').css('display','');
		<?php if(isset($_GET['hasil_repacking_id'])){ ?>
			getItemsRandomByPk("<?= $_GET['hasil_repacking_id']; ?>");
		<?php } ?>
		$('#<?= \yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>').attr("disabled","disabled");
	}
}

function setDropdownProduk(){
	setDropdown('<?= Url::toRoute(['/func/setDropdownProduk']); ?>','<?= \yii\bootstrap\Html::getInputId($model, 'produk_id') ?>',null,'setProduk();');
}

function addItem(){
	var t = $("#table-detail > tbody > tr:last").find('input[name*="[t]"]').val();
	var t_satuan = $("#table-detail > tbody > tr:last").find('select[name*="[t_satuan]"]').val();
	var p = $("#table-detail > tbody > tr:last").find('input[name*="[p]"]').val();
	var p_satuan = $("#table-detail > tbody > tr:last").find('select[name*="[p_satuan]"]').val();
	var l = $("#table-detail > tbody > tr:last").find('input[name*="[l]"]').val();
	var l_satuan = $("#table-detail > tbody > tr:last").find('select[name*="[l_satuan]"]').val();
	var qty = $("#table-detail > tbody > tr:last").find('input[name*="[qty]"]').val();
	var kapasitas_kubikasi = $("#table-detail > tbody > tr:last").find('input[name*="[kapasitas_kubikasi]"]').val();
	var keterangan = $("#table-detail > tbody > tr:last").find('input[name*="[keterangan]"]').val();
	var produk_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'produk_id') ?>").val();
    $.ajax({
        url    : '<?= Url::toRoute(['/gudang/penerimaanko/addItem']); ?>',
        type   : 'POST',
        data   : {produk_id:produk_id,t:t,t_satuan:t_satuan,p:p,p_satuan:p_satuan,l:l,l_satuan:l_satuan,qty:qty,kapasitas_kubikasi:kapasitas_kubikasi,keterangan:keterangan},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail tbody').fadeIn(500,function(){
                    reordertable('#table-detail');
					setTotal();
					setMeterKubikItem($(this).find("input[name*='[kapasitas_kubikasi]']"));
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setMeterKubikItem(ele){
    $(ele).parents('tr').find('input[name*="[kapasitas_kubikasi_display]"]').addClass('animation-loading');
    var qty = unformatNumber( $(ele).parents('tr').find('input[name*="[qty]"]').val() );
    var p = unformatNumber( $(ele).parents('tr').find('input[name*="[p]"]').val() );
    var l = unformatNumber( $(ele).parents('tr').find('input[name*="[l]"]').val() );
    var t = unformatNumber( $(ele).parents('tr').find('input[name*="[t]"]').val() );
    var sat_p = $(ele).parents('tr').find('select[name*="[p_satuan]"]').val();
    var sat_l = $(ele).parents('tr').find('select[name*="[l_satuan]"]').val();
    var sat_t = $(ele).parents('tr').find('select[name*="[t_satuan]"]').val();
    var sat_p_m = 0;
    var sat_l_m = 0;
    var sat_t_m = 0;
    var result = 0;
    var result_display = 0;
	
    if(sat_p == 'mm'){
        sat_p_m = p * 0.001;
    }else if(sat_p == 'cm'){
        sat_p_m = p * 0.01;
    }else if(sat_p == 'inch'){
        sat_p_m = p * 0.0254;
    }else if(sat_p == 'm'){
        sat_p_m = p;
    }else if(sat_p == 'feet'){
        sat_p_m = p * 0.3048;
    }
    if(sat_l == 'mm'){
        sat_l_m = l * 0.001;
    }else if(sat_l == 'cm'){
        sat_l_m = l * 0.01;
    }else if(sat_l == 'inch'){
        sat_l_m = l * 0.0254;
    }else if(sat_l == 'm'){
        sat_l_m = l;
    }else if(sat_l == 'feet'){
        sat_l_m = l * 0.3048;
    }
    if(sat_t == 'mm'){
        sat_t_m = t * 0.001;
    }else if(sat_t == 'cm'){
        sat_t_m = t * 0.01;
    }else if(sat_t == 'inch'){
        sat_t_m = t * 0.0254;
    }else if(sat_t == 'm'){
        sat_t_m = t;
    }else if(sat_t == 'feet'){
        sat_t_m = t * 0.3048;
    }
    result = sat_p_m * sat_l_m * sat_t_m * qty;
    result_display = (Math.round( result * 10000 ) / 10000 ).toString();
    setTimeout(function() {
        $(ele).parents('tr').find('input[name*="[kapasitas_kubikasi]"]').val(result);
        $(ele).parents('tr').find('input[name*="[kapasitas_kubikasi_display]"]').val(result_display);
        $(ele).parents('tr').find('input[name*="[kapasitas_kubikasi_display]"]').removeClass('animation-loading');
		setTotal();
    }, 300);
}

function setTotal(){
	var totalqty = 0;
	var totalm3 = 0;
	$('#table-detail > tbody > tr').each(function(){
		totalqty += unformatNumber( $(this).find('input[name*="[qty]"]').val() );
		totalm3 += unformatNumber( $(this).find('input[name*="[kapasitas_kubikasi]"]').val() );
	});
	$('#<?= \yii\bootstrap\Html::getInputId($model, "total_qty") ?>').val( formatNumberForUser(totalqty) );
	$('#<?= \yii\bootstrap\Html::getInputId($model, "total_m3") ?>').val( formatNumberFixed4(totalm3) );
	
	$('#<?= \yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>').val( formatNumberForUser(totalqty) );
	$('#<?= \yii\bootstrap\Html::getInputId($model, "qty_m3") ?>').val( totalm3 );
	
	$('#<?= \yii\bootstrap\Html::getInputId($model, "qty_m3_display") ?>').val( formatNumberFixed4(totalm3) );
	
	if( $('input[name*="[jenis_palet]"]:checked').val() == "Khusus" ){
		$('#<?= \yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>').attr("disabled","disabled");
	}else{
		$('#<?= \yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>').removeAttr("disabled");
	}
}

function reordertable(obj_table){
    var row = 0;
    $(obj_table).find("tbody > tr").each(function(){
        $(this).find("#no_urut").val(row+1);
        $(this).find("span.no_urut").text(row+1);
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 3){
                $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]);
                $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]");
            }
        });
        row++;
    });
    formconfig();
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
        reordertable('#table-detail');
		setTotal();
    });
}
function cancelItemThis2(ele){
    $(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
        reordertable('#table-detail-paletasal');
    });
}

function save(){
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($modProduksi, "tanggal_produksi") ?>").parents(".form-group").removeClass("has-error");
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        var jumlah_item2 = $('#table-detail-paletasal tbody tr').length;
        if(jumlah_item2 <= 0){
            cisAlert('Mohon isi palet asal terlebih dahulu');
            return false;
        }
		if( $('input[name*="[jenis_palet]"]:checked').val() == "Khusus" ){
			if(jumlah_item <= 0){
					cisAlert('Isi detail komposisi terlebih dahulu');
				return false;
			}
		}
        if($("#<?= \yii\bootstrap\Html::getInputId($model, "hasil_dari") ?>").val() == 'Penanganan Barang Retur'){
            if(!$("#<?= \yii\bootstrap\Html::getInputId($model, "hasil_dari_retur") ?>").val()){
                cisAlert('Mohon isi keperluan retur barang terlebih dahulu');
                return false;
            }
        }
        if(validatingDetail()){
			submitform($form);
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
	var qty_kecil = $("#<?= \yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>").val();
	var tanggal_produksi = $("#<?= \yii\bootstrap\Html::getInputId($modProduksi, "tanggal_produksi") ?>").val();
	if(!qty_kecil || qty_kecil <= 0){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>").parents(".form-group").removeClass("has-success");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>").parents(".form-group").addClass("has-error");
		has_error = has_error + 1;
	}
	var qty_m3 = $("#<?= \yii\bootstrap\Html::getInputId($model, "qty_m3") ?>").val();
	if(!qty_m3 || qty_m3 <= 0){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "qty_m3") ?>").parents(".form-group").removeClass("has-success");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "qty_m3") ?>").parents(".form-group").addClass("has-error");
		has_error = has_error + 1;
	}
	if(!tanggal_produksi){
		$("#<?= \yii\bootstrap\Html::getInputId($modProduksi, "tanggal_produksi") ?>").parents(".form-group").removeClass("has-success");
		$("#<?= \yii\bootstrap\Html::getInputId($modProduksi, "tanggal_produksi") ?>").parents(".form-group").addClass("has-error");
		has_error = has_error + 1;
	}

    $('#table-detail tbody > tr').each(function(){
        var field2 = $(this).find('input[name*="[qty]"]');
        var field3 = $(this).find('textarea[name*="[keterangan]"]');
        if(!field2.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[qty]"]').parents('td').addClass('error-tb-detail');
        }else{
            $(this).find('input[name*="[qty]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field3.val()){
            has_error = has_error + 1;
            $(this).find('textarea[name*="[keterangan]"]').parents('td').addClass('error-tb-detail');
        }else{
            $(this).find('textarea[name*="[keterangan]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
	getPaletAsal(id);
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
		$("input[name*='[plymill_shift]'][value='<?= $modProduksi->plymill_shift ?>']").prop('checked',true);
		<?php if(!empty($modRandom)){ ?>
		setTotal();
		<?php } ?>
	},1000);
    if($('#<?= yii\bootstrap\Html::getInputId($model, 'hasil_dari') ?>').val() == 'Penanganan Barang Retur'){
        $("#<?= \yii\helpers\Html::getInputId($model, "hasil_dari_retur") ?>").css('display', '');
        $("#label-hasil-dari-retur").css('display', '');
    } else {
        $("#<?= \yii\helpers\Html::getInputId($model, "hasil_dari_retur") ?>").css('display', 'none');
        $("#label-hasil-dari-retur").css('display', 'none');
    }
}

function getItemsRandomByPk(id){
	$('#table-detail > tbody').addClass('animation-loading');
    $.ajax({
        url    : '<?= Url::toRoute(['/ppic/pengajuanrepacking/getItemsRandomByPk']); ?>',
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
    openModal('<?= Url::toRoute(['/ppic/pengajuanrepacking/DaftarAfterSavePengiriman']) ?>','modal-aftersave');
}
function createMasterProduk(){
	openModal('<?= Url::toRoute('/ppic/produk/create') ?>','modal-produk-create',null,'setDropdownProduk();');
}
function setInduk(){
	var satuan_kecil = $("#<?= yii\bootstrap\Html::getInputId($model, "total_qty_satuan") ?>").val();
	$('#<?= yii\bootstrap\Html::getInputId($model, "qty_kecil_satuan") ?>').val(satuan_kecil);
}

function generateNomorProduksi(){
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($modProduksi, "tanggal_produksi") ?>').val();
	var prod = $('#<?= yii\bootstrap\Html::getInputId($model, "produk_id") ?>').val();
	var no_urut = $('#<?= yii\bootstrap\Html::getInputId($modProduksi, "nomor_urut_produksi") ?>').val();
	var plymill_shift = $('#<?= yii\bootstrap\Html::getInputId($modProduksi, "plymill_shift") ?> input:checked').map(function () { return this.value; }).get();
	var sawmill_line = $('#<?= yii\bootstrap\Html::getInputId($modProduksi, "sawmill_line") ?>').val();
	$.ajax({
        url    : '<?= Url::toRoute(['/gudang/penerimaanko/generateNomorProduksi']); ?>',
        type   : 'POST',
        data   : {tgl:tgl,prod:prod,no_urut:no_urut,plymill_shift:plymill_shift,sawmill_line:sawmill_line},
        success: function (data) {
            if(data){
                $('#<?= yii\bootstrap\Html::getInputId($modProduksi, "nomor_produksi") ?>').val(data);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setProdSection(){
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "produk_jenis") ?>').val();
    if(jenis_produk == 'Plywood' || jenis_produk == 'Veneer' || jenis_produk == 'Platform' || jenis_produk == 'Lamineboard'){
        $('.place_prodsection_plymill').css('display','');
        $('.place_prodsection_sawmill').css('display','none');
		$('input[name*="[plymill_shift]"]').prop('checked', false);
		$("#<?= yii\bootstrap\Html::getInputId($modProduksi, 'sawmill_line'); ?> option:last").attr('selected','selected');
    }else if(jenis_produk == 'Sawntimber'){
        $('.place_prodsection_plymill').css('display','none');
        $('.place_prodsection_sawmill').css('display','');
		$("#<?= yii\bootstrap\Html::getInputId($modProduksi, 'sawmill_line'); ?>").val('');
		$('input[name*="[plymill_shift]"]').prop('checked', true);
    }else{
        $('.place_prodsection_plymill').css('display','none');
        $('.place_prodsection_sawmill').css('display','none');
		$('input[name*="[plymill_shift]"]').prop('checked', true);
		$("#<?= yii\bootstrap\Html::getInputId($modProduksi, 'sawmill_line'); ?> option:last").attr('selected','selected');
    }
}

function masterProduk(){
    openModal('<?= Url::toRoute('/ppic/hasilproduksi/modalproduk')?>', 'modal-master-produk');
}
function pickProduk(produk_id,kode){
	$("#modal-master-produk").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "produk_id") ?>").empty().append('<option value="'+produk_id+'">'+kode+'</option>').val(produk_id).trigger('change');
}
function setMeterKubik(){
    var p = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_p') ?>').val() );
    var l = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_l') ?>').val() );
    var t = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_t') ?>').val() );
    var sat_p = $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_p_satuan') ?>').val();
    var sat_l = $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_l_satuan') ?>').val();
    var sat_t = $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_t_satuan') ?>').val();
    var qty = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($model, 'qty_kecil') ?>').val() );
    var sat_p_m = 0;
    var sat_l_m = 0;
    var sat_t_m = 0;
    var result = 0;
	var result_diplay = 0;
    
    if(sat_p == 'mm'){
        sat_p_m = p * 0.001;
    }else if(sat_p == 'cm'){
        sat_p_m = p * 0.01;
    }else if(sat_p == 'inch'){
        sat_p_m = p * 0.0254;
    }else if(sat_p == 'm'){
        sat_p_m = p;
    }else if(sat_p == 'feet'){
        sat_p_m = p * 0.3048;
    }
    if(sat_l == 'mm'){
        sat_l_m = l * 0.001;
    }else if(sat_l == 'cm'){
        sat_l_m = l * 0.01;
    }else if(sat_l == 'inch'){
        sat_l_m = l * 0.0254;
    }else if(sat_l == 'm'){
        sat_l_m = l;
    }else if(sat_l == 'feet'){
        sat_l_m = l * 0.3048;
    }
    if(sat_t == 'mm'){
        sat_t_m = t * 0.001;
    }else if(sat_t == 'cm'){
        sat_t_m = t * 0.01;
    }else if(sat_t == 'inch'){
        sat_t_m = t * 0.0254;
    }else if(sat_t == 'm'){
        sat_t_m = t;
    }else if(sat_t == 'feet'){
        sat_t_m = t * 0.3048;
    }
    result = sat_p_m * sat_l_m * sat_t_m * qty;
    result_diplay = (Math.round( result * 10000 ) / 10000 ).toString();
    setTimeout(function() {
        $('#<?= \yii\bootstrap\Html::getInputId($model, 'qty_m3') ?>').val( result );
        $('#<?= \yii\bootstrap\Html::getInputId($model, 'qty_m3_display') ?>').val( formatNumberForUser(result_diplay) );
        $('#<?= \yii\bootstrap\Html::getInputId($model, 'qty_m3_display') ?>').removeClass('animation-loading');
    }, 300);
}

function getPaletAsal(id){
	$('#table-detail-paletasal > tbody').addClass('animation-loading');
    $.ajax({
        url    : '<?= Url::toRoute(['/ppic/pengajuanrepacking/getPaletAsal']); ?>',
        type   : 'POST',
        data   : {id:id},
        success: function (data) {
            if(data.html){
                $('#table-detail-paletasal > tbody').html(data.html);
				reordertable('#table-detail-paletasal');
				$('#table-detail-paletasal > tbody').removeClass('animation-loading');
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function printKartuBarang(id){
    window.open("<?= yii\helpers\Url::toRoute('/ppic/pengajuanrepacking/printKartuBarang') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function paletRetur(ele){
	var url = '<?= Url::toRoute(['/ppic/pengajuanrepacking/paletditerimaretur']); ?>';
	$(".modals-place-2-min").load(url, function() {
		$("#modal-palet .modal-dialog").css('width','85%');
		$("#modal-palet").modal('show');
		$("#modal-palet").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	}); 
}
</script>