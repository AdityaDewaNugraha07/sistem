<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan Kayu Olahan';
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
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); 
$hiddenTab = ( (\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_STAFF)||(\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_KADEP) )?"hidden":"";
?>
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
<!--					<li class="<?= $hiddenTab ?>">
						<a href="<?php // echo \yii\helpers\Url::toRoute("/gudang/penerimaanko/index") ?>"> <?php // echo Yii::t('app', 'Penerimaan Reguler'); ?> </a>
					</li>-->
					<li class="<?= $hiddenTab ?>">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/penerimaanko/scanterima") ?>"> <?= Yii::t('app', 'Penerimaan Reguler'); ?> </a>
					</li>
					<li class="active <?= $hiddenTab ?>">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/penerimaanko/terimarepacking") ?>"> <?= Yii::t('app', 'Penerimaan Hasil Repacking'); ?> </a>
					</li>
                    <li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/penerimaanko/riwayatpenerimaan") ?>"> <?= Yii::t('app', 'Riwayat Penerimaan'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Penerimaan'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, 'hasil_repacking_id') ?>
                                        <?= $form->field($model, 'nomor_produksi',['template'=>'<div class="form-group" style="margin-left: -6px;">
                                                                                                    {label}
                                                                                                    <div class="col-md-8">
                                                                                                        <span class="input-group-btn" style="width: 100%">
                                                                                                            {input}
                                                                                                        </span>
                                                                                                        <span class="input-group-btn">
                                                                                                            '.(!isset($_GET['tbko_id'])?'<a class="btn btn-icon-only btn-default tooltips" onclick="openKirimGudang();" data-original-title="Daftar Pengiriman ke Gudang" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>':"").'
                                                                                                            
                                                                                                        </span>
                                                                                                        {error}
                                                                                                    </div>
                                                                                                </div>'])
                                                        ->textInput(['disabled'=>true,'onchange'=>'setPalet()','style'=>'font-weight:bold']); ?>
                                        <?= $form->field($model, 'kode')->textInput(['disabled'=>true,'style'=>'font-weight:bold']); ?>
										<?= $form->field($model, 'tanggal')->textInput(['disabled'=>'disabled','class'=>'form-control input-small']); ?>
										<?= $form->field($model, 'jenis_penerimaan')->inline(true)->radioList(['Biasa'=>'Biasa','Khusus'=>'Khusus'],['style'=>'margin-left:20px','onchange'=>'showTablePenerimaanKhusus();']); ?>
										<?= $form->field($model, 'gudang_id')->dropDownList( \app\models\MGudang::getOptionList() ,['class'=>'form-control select2','prompt'=>'','data-placeholder'=>'Data Penerimaan']); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'petugas_penerima')->dropDownList(\app\models\MPegawai::getOptionListWithDeptName(  ),['class'=>'form-control select2','prompt'=>'']); ?>
                                        <?= $form->field($model, 'tanggal_produksi')->textInput(['disabled'=>true]); ?>
										<div class="place_prodsection_plymill" style="display: none;">
											<?php echo $form->field($modProduksi, 'plymill_shift')->inline(true)->checkboxList(\app\models\MDefaultValue::getOptionList('plymill-shift'), ['template' => '{label}<div class="col-md-7" style="margin-top: -10px;"><div class="mt-checkbox-inline"> {input} </div></div>','onchange'=>'generateNomorProduksi();']); ?>
										</div>
										<div class="place_prodsection_sawmill" style="display: none;">
											<?= $form->field($modProduksi, 'sawmill_line')->dropDownList(\app\models\MDefaultValue::getOptionList('sawmill-line'),['class'=>'form-control','prompt'=>'','onchange'=>'generateNomorProduksi();']) ?>
										</div>
										<?= $form->field($modProduksi, 'nomor_urut_produksi')->textInput(['class'=>'form-control numbers-only','onblur'=>'generateNomorProduksi();','disabled'=>true]); ?>
                                        <?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                </div>
                                <hr>
								<div class="row">
                                    <div class="col-md-5" style="margin-bottom: 20px; ">
                                        <h4><?= Yii::t('app', 'Data Produk'); ?></h4>
                                    </div>
									<div class="col-md-7">
										
									</div>
                                </div>
								<div class="row">
                                    <div class="col-md-6">
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'produk_id') ?>
                                        <?= $form->field($model, 'produk_kode')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'produk_nama')->textInput(['disabled'=>'disabled'])->label("Nama Produk"); ?>
										<?= $form->field($model, 'produk_jenis')->textInput(['disabled'=>'disabled'])->label("Jenis Produk"); ?>
										<?= $form->field($model, 'produk_dimensi')->textInput(['disabled'=>'disabled','style'=>'height: 40px; width: 380px; font-weight:700; font-size:2.2rem; padding:2px;  font-family: arial'])->label("Dimensi"); ?>
									</div>
									<div class="col-md-6">
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'qty_palet') ?>
										<?= $form->field($model, 'qty_besar_satuan')->textInput(['disabled'=>'disabled','class'=>'form-control input-small'])->label("Satuan Besar"); ?>
										<?= $form->field($model, 'qty_kecil')->textInput(['class'=>'form-control input-small float','onblur'=>'setMeterKubik();','disabled'=>true])->label("Qty Penerimaan"); ?>
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
                                <br><hr class="place-khusus" style="display: none;">
                                <div class="row place-khusus" style="display: none;">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Komposisi Produk Penerimaan Khusus'); ?></h4>
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
												<tbody>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="4">
															
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
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printKartuBarang('.(isset($_GET['tbko_id'])?$_GET['tbko_id']:'').');','disabled'=>true]); ?>
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
if(isset($_GET['tbko_id'])){
    $pagemode = "afterSave(".$_GET['tbko_id']."); showTablePenerimaanKhusus();";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Penerimaan Kayu Olahan'))."');
    $('input:radio[name*=\"[jenis_penerimaan]\"][value*=\"Biasa\"]').prop('disabled','disabled');
	$('input:radio[name*=\"[jenis_penerimaan]\"][value*=\"Khusus\"]').prop('disabled','disabled');
", yii\web\View::POS_READY); ?>
<script>
function showTablePenerimaanKhusus(){
	if( $("input:radio[name*='[jenis_penerimaan]']:checked").val() == "Biasa" ){
		$('.place-khusus').css('display','none');
	}else{
		$('.place-khusus').css('display','');
		<?php if(isset($_GET['tbko_id'])){ ?>
            setTimeout(function(){
                getItemsRandom();
            },500);
		<?php } ?>
	}
}

function openKirimGudang(){
	var url = '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/openHasilrepacking']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-hasilrepacking .modal-dialog").css('width','90%');
		$("#modal-hasilrepacking").modal('show');
		$("#modal-hasilrepacking").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function pick(kbj){
	$("#modal-hasilrepacking").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "nomor_produksi") ?>").val(kbj);
    setPalet();
}

function setPalet(){
	var kbj = $("#<?= yii\bootstrap\Html::getInputId($model, "nomor_produksi") ?>").val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/setPalet']); ?>',
        type   : 'POST',
        data   : {kbj:kbj},
        success: function (data) {
			$('#<?= yii\bootstrap\Html::getInputId($model, "hasil_repacking_id") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "tanggal_produksi") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduksi, "nomor_urut_produksi") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "produk_id") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "produk_kode") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "produk_nama") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "produk_jenis") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "produk_dimensi") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_besar_satuan") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_palet") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_kecil_satuan") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_m3") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($model, "qty_m3_display") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_p") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_l") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_t") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_p_satuan") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_l_satuan") ?>').val("");
			$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_t_satuan") ?>').val("");
			$("input:radio[name*='[jenis_penerimaan]'][value*='Biasa']").prop('checked',true);
			if(data.model){
                $('#<?= yii\bootstrap\Html::getInputId($model, "hasil_repacking_id") ?>').val(data.hasil_repacking.hasil_repacking_id);
                $('#<?= yii\bootstrap\Html::getInputId($model, "tanggal_produksi") ?>').val(data.hasil_repacking.tanggal_produksi);
                $('#<?= yii\bootstrap\Html::getInputId($modProduksi, "nomor_urut_produksi") ?>').val(data.produksi.nomor_urut_produksi);
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_id") ?>').val(data.model.produk_id);
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_kode") ?>').val(data.model.produk_kode);
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_nama") ?>').val(data.model.produk_nama);
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_jenis") ?>').val(data.model.produk_group);
				$('#<?= yii\bootstrap\Html::getInputId($model, "produk_dimensi") ?>').val(data.model.produk_dimensi);
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_besar_satuan") ?>').val(data.model.produk_satuan_besar);
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_palet") ?>').val("1");
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>').val(data.hasil_repacking.qty_kecil);
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_kecil_satuan") ?>').val(data.hasil_repacking.qty_kecil_satuan);
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_m3") ?>').val(data.hasil_repacking.qty_m3);
				$('#<?= yii\bootstrap\Html::getInputId($model, "qty_m3_display") ?>').val(data.qty_m3_display);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_p") ?>').val(data.model.produk_p);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_l") ?>').val(data.model.produk_l);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_t") ?>').val(data.model.produk_t);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_p_satuan") ?>').val(data.model.produk_p_satuan);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_l_satuan") ?>').val(data.model.produk_l_satuan);
				$('#<?= yii\bootstrap\Html::getInputId($modProduk, "produk_t_satuan") ?>').val(data.model.produk_t_satuan);
                if(data.produksi.plymill_shift == 'A'){
                    $("input:checkbox[name*='[plymill_shift]'][value*='A']").prop('checked',true);
                }else if(data.produksi.plymill_shift == 'B'){
                    $("input:checkbox[name*='[plymill_shift]'][value*='B']").prop('checked',true);
                }else if(data.produksi.plymill_shift == 'AB'){
                    $("input:checkbox[name*='[plymill_shift]'][value*='A']").prop('checked',true);
                    $("input:checkbox[name*='[plymill_shift]'][value*='B']").prop('checked',true);
                }
                if(data.produksi.sawmill_line != '-'){
                    $("#<?= yii\bootstrap\Html::getInputId($modProduksi, 'sawmill_line'); ?>").val(data.produksi.sawmill_line);
                }
			}
			setProdSection();
			
            var p = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_p') ?>').val() );
			var l = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_l') ?>').val() );
			var t = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modProduk, 'produk_t') ?>').val() );
			if( p <= 0 || l <= 0 || t <= 0){
				// Random
				$("input:radio[name*='[jenis_penerimaan]'][value*='Khusus']").prop('checked',true);
			}else{
				// Non-Random
				$("input:radio[name*='[jenis_penerimaan]'][value*='Biasa']").prop('checked',true);
			}
            <?php if(!isset($_GET['tbko_id'])){ ?>
                showTablePenerimaanKhusus();
            <?php } ?>
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function setProdSection(){
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "produk_jenis") ?>').val();
    if(jenis_produk == 'Plywood' || jenis_produk == 'Veneer' || jenis_produk == 'Platform' || jenis_produk == 'Lamineboard'){
        $('.place_prodsection_plymill').css('display','');
        $('.place_prodsection_sawmill').css('display','none');
		$("#<?= yii\bootstrap\Html::getInputId($modProduksi, 'sawmill_line'); ?> option:last").attr('selected','selected');
    }else if(jenis_produk == 'Sawntimber'){
        $('.place_prodsection_plymill').css('display','none');
        $('.place_prodsection_sawmill').css('display','');
		$('input[name*="[plymill_shift]"]').prop('checked', true);
    }else{
        $('.place_prodsection_plymill').css('display','none');
        $('.place_prodsection_sawmill').css('display','none');
		$('input[name*="[plymill_shift]"]').prop('checked', true);
		$("#<?= yii\bootstrap\Html::getInputId($modProduksi, 'sawmill_line'); ?> option:last").attr('selected','selected');
    }
}
function getItemsRandom(id){
    var hasil_repacking_id = $('#<?= yii\bootstrap\Html::getInputId($model, "hasil_repacking_id") ?>').val();
	$('#table-detail > tbody').addClass('animation-loading');
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/GetItemsRandom']); ?>',
        type   : 'POST',
        data   : {hasil_repacking_id:hasil_repacking_id},
        success: function (data) {
            if(data.html){
                $('#table-detail > tbody').html(data.html);
				reordertable('#table-detail');
                setTotal();
				$('#table-detail > tbody').removeClass('animation-loading');
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
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
}

function save(){
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($model, "qty_kecil") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_produksi") ?>").parents(".form-group").removeClass("has-error");
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
		if( $('input[name*="[jenis_penerimaan]"]:checked').val() == "Khusus" ){
			if(jumlah_item <= 0){
					cisAlert('Isi detail komposisi terlebih dahulu');
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
	var tanggal_produksi = $("#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_produksi") ?>").val();
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
		$("#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_produksi") ?>").parents(".form-group").removeClass("has-success");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_produksi") ?>").parents(".form-group").addClass("has-error");
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
	setPalet();
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
		<?php if(!empty($modDetail)){ ?>
		setTotal();
		<?php } ?>
	},1000);
}
function printKartuBarang(id){
	window.open("<?= yii\helpers\Url::toRoute('/gudang/penerimaanko/printKartuBarang') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
</script>