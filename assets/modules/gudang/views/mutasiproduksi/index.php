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
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/mutasikeluar/index") ?>"> <?= Yii::t('app', 'Mutasi Keluar'); ?> </a>
					</li>
					<li class="active">
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
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Permintaan Mutasi Dari PPIC'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
                                    <div class="col-md-6">
                                        <?php 
                                        echo \yii\bootstrap\Html::activeHiddenInput($model, 'pengajuan_repacking_id');
										if(isset($_GET['kode_permintaan'])){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Permintaan'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode_permintaan', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode_permintaan ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php }else{ ?>
											<div class="form-group" style="margin-bottom: 5px;">
												<label class="col-md-4 control-label">Kode Permintaan</label>
												<div class="col-md-8">
													<span class="input-group-btn" style="width: 100%">
														<?= \yii\bootstrap\Html::activeDropDownList($model, 'kode_permintaan', app\models\TPengajuanRepacking::getOptionListMutasiKeluar(),['class'=>'form-control select2','onchange'=>'setParent()','prompt'=>'']); ?>
													</span>
													<span class="input-group-btn" style="">
														<a class="btn btn-icon-only btn-default tooltips" onclick="currentstock();" data-original-title="Cari Permintaan Barang Jadi dari PPIC" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'keperluan_permintaan')->textInput(['disabled'=>true])->label('Keperluan Permintaan'); ?>
										<?= $form->field($model, 'keterangan_permintaan')->textarea(['disabled'=>true])->label('Keterangan Permintaan'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'tanggal',[
                                                                'template'=>'{label}<div class="col-md-7"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                {error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Mutasi"); ?>
										<?= $form->field($model, 'pegawai_mutasi')->dropDownList(\app\models\MPegawai::getOptionListWithDeptName(  ),['class'=>'form-control select2','prompt'=>''])->label('Admin Mutasi'); ?>
                                        <?= $form->field($model, 'keterangan')->textarea()->label('Keterangan Mutasi'); ?>
                                    </div>
                                </div>
                                <br><hr>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
                                            <h4 style="margin-bottom: 3px;"><?= Yii::t('app', 'Permintaan Palet'); ?></h4>
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail">
												<thead>
                                                    <tr>
                                                        <th class="" style="background-color: #BAD3F5; width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th class="" style="background-color: #BAD3F5;"><?= Yii::t('app', 'Kode / Nama Produk') ?></th>
                                                        <th class="" style="background-color: #BAD3F5; width: 200px;"><?= Yii::t('app', 'Dimensi') ?></th>
                                                        <th class="" style="background-color: #BAD3F5; width: 100px;"><?= Yii::t('app', 'Qty Palet') ?></th>
                                                        <th class="" style="background-color: #BAD3F5; width: 200px;"><?= Yii::t('app', 'Keterangan') ?></th>
                                                    </tr>
												</thead>
												<tbody>
                                                    <tr><td colspan="5" style="font-size: 1.1rem;"><center><i>No Data</i></center></td></tr>
												</tbody>
												<tfoot>
                                                    <tr>
                                                        <td colspan="3" style="text-align: right; background-color: #BAD3F5; font-size: 1.2rem;"> &nbsp; &nbsp; TOTAL PERMINTAAN PALET</td>
                                                        <td style="background-color: #BAD3F5;">
                                                            <?= yii\bootstrap\Html::activeTextInput($model, "permintaan_total_palet",['class'=>'float','style'=>'width:100%; text-align: center;','disabled'=>true]); ?>
														</td>
                                                        <td style="background-color: #BAD3F5;"></td>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
                                            <h4 style="margin-bottom: 3px;"><?= Yii::t('app', 'Palet Mutasi'); ?></h4>
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail-mutasi">
												<thead>
                                                    <tr>
                                                        <th class="td-kecil" style="background-color: #BED7A4; width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th class="td-kecil" style="background-color: #BED7A4; width: 50px; line-height: 1"><?= Yii::t('app', 'Asal<br>Gudang') ?></th>
                                                        <th class="td-kecil" style="background-color: #BED7A4; width: 150px; "><?= Yii::t('app', 'KBJ') ?></th>
                                                        <th class="td-kecil" style="background-color: #BED7A4; "><?= Yii::t('app', 'Kode / Nama Produk') ?></th>
                                                        <th class="td-kecil" style="background-color: #BED7A4; width: 200px;"><?= Yii::t('app', 'Dimensi') ?></th>
                                                        <th class="td-kecil" style="background-color: #BED7A4; width: 50px;"><?= Yii::t('app', 'Pcs') ?></th>
                                                        <th class="td-kecil" style="background-color: #BED7A4; width: 80px;"><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                                        <th class="td-kecil" style="background-color: #BED7A4; width: 50px;"></th>
                                                    </tr>
												</thead>
												<tbody>
                                                    
												</tbody>
												<tfoot>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #BED7A4; text-align: center;">
															<!--<a class="btn btn-xs blue-steel" id="btn-add-item" onclick="availableProdukAtasPermintaan();" style=""><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add'); ?></a>-->
														</td>
                                                        <td colspan="6" style="vertical-align: bottom; background-color: #BED7A4;">
                                                            <table style="width: 100%;" border="0">
                                                                <tr>
                                                                    <td colspan="3">TOTAL MUTASI </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="width: 60px;">Palet</td>
                                                                    <td> : </td>
                                                                    <td><?= yii\bootstrap\Html::activeTextInput($model, "total_palet",['class'=>'float','style'=>'width:80px;','disabled'=>true]); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Pcs</td>
                                                                    <td> : </td>
                                                                    <td><?= yii\bootstrap\Html::activeTextInput($model, "total_pcs",['class'=>'float','style'=>'width:80px;','disabled'=>true]); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>m<sup>3</sup></td>
                                                                    <td> : </td>
                                                                    <td><?= yii\bootstrap\Html::activeTextInput($model, "total_m3",['class'=>'float','style'=>'width:80px;','disabled'=>true]); ?></td>
                                                                </tr>
                                                            </table>
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
                                <?php // echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php // echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
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
if(isset($_GET['kode_permintaan'])){
    $pagemode = "afterSave('".$_GET['kode_permintaan']."');";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Mutasi'))."');
	$('select[name*=\"[kode_permintaan]\"]').select2({
		allowClear: !0,
		placeholder: 'Kode Permintaan Barang Jadi',
	});
	$('select[name*=\"[pegawai_mutasi]\"]').select2({
		allowClear: !0,
		placeholder: 'Nama Petugas',
	});
", yii\web\View::POS_READY); ?>
<script>
function setParent(){
	var kode_permintaan = $("#<?= yii\bootstrap\Html::getInputId($model, "kode_permintaan") ?>").val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/mutasiproduksi/setParent']); ?>',
        type   : 'POST',
        data   : {kode_permintaan:kode_permintaan},
        success: function (data) {
            $('#<?= \yii\helpers\Html::getInputId($model, "pengajuan_repacking_id") ?>').val('');
            $('#<?= \yii\helpers\Html::getInputId($model, "keperluan_permintaan") ?>').val('');
            $('#<?= \yii\helpers\Html::getInputId($model, "keterangan_permintaan") ?>').val('');
            if(data){
                $('#<?= \yii\helpers\Html::getInputId($model, "pengajuan_repacking_id") ?>').val(data.pengajuan_repacking_id);
                $('#<?= \yii\helpers\Html::getInputId($model, "keperluan_permintaan") ?>').val(data.keperluan);
                $('#<?= \yii\helpers\Html::getInputId($model, "keterangan_permintaan") ?>').val(data.keterangan);
            }
            getItems();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function getItems(edit){
	var kode_permintaan = $("#<?= yii\bootstrap\Html::getInputId($model, "kode_permintaan") ?>").val();
    <?php if(isset($_GET['kode_permintaan'])){ ?>
        var aftersave = '1';
    <?php }else{ ?>
        var aftersave = '0';
    <?php } ?>
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/mutasiproduksi/getItems']); ?>',
        type   : 'POST',
        data   : {kode_permintaan:kode_permintaan,edit:edit,aftersave:aftersave},
        success: function (data) {
            $('#table-detail > tbody').html('');
            $('#table-detail-mutasi > tbody').html('');
            if(data.html){
                $('#table-detail > tbody').html(data.html);
                $('#<?= yii\helpers\Html::getInputId($model, "permintaan_total_palet") ?>').val(data.total_palet);
                $('#table-detail > tbody > tr').each(function(){
                    $(this).find(".tooltips").tooltip({ delay: 50 });
                });
                reordertable('#table-detail');
            }
            if(data.htmlmutasi){
                $('#table-detail-mutasi > tbody').html(data.htmlmutasi);
                $('#table-detail-mutasi > tbody > tr').each(function(){
                    $(this).find(".tooltips").tooltip({ delay: 50 });
                });
                reordertable('#table-detail-mutasi');
                total();
            }
            setButtonSave();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function availableProdukAtasPermintaan(){
    var pengajuan_repacking_id = $('#<?= yii\bootstrap\Html::getInputId($model, "pengajuan_repacking_id") ?>').val();
	openModal('<?= \yii\helpers\Url::toRoute(['/gudang/mutasiproduksi/AvailableProdukAtasPermintaan','pengajuan_repacking_id'=>'']); ?>'+pengajuan_repacking_id,'modal-currentstock','90%');
}

function pick(nomor_produksi){
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/mutasiproduksi/Pick']); ?>',
        type   : 'POST',
        data   : {nomor_produksi:nomor_produksi},
        success: function (data) {
			if(data){
                $('#table-detail-mutasi').find(".no-data").remove();
				var already = [];
                $('#table-detail-mutasi > tbody > tr').each(function(){
                    var nomor_produksi = $(this).find('input[name*="[nomor_produksi]"]');
                    if( nomor_produksi.val() ){
                        already.push(nomor_produksi.val());
                    }
                });
                if( $.inArray( data.nomor_produksi.toString(), already ) != -1 ){ // Jika ada yang sama
                    cisAlert("Palet ini sudah dipilih di list");
                    return false;
                }else{
                    $("#modal-currentstock").find('button.fa-close').trigger('click');
                    $("#table-detail-mutasi > tbody").append(data.html);
                    reordertable("#table-detail-mutasi");
                    total();
                }
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function total(){
    var total_palet = 0;
    var total_pcs = 0;
    var total_m3 = 0;
    
    $('#table-detail-mutasi > tbody > tr').each(function(){
        if( $(this).find("input[name*='[nomor_produksi]']").val() ){
            total_palet = total_palet+1;
        }
        total_pcs += unformatNumber( $(this).find("input[name*='[qty_kecil]']").val() );
        total_m3 += unformatNumber( $(this).find("input[name*='[qty_m3]']").val() );
    });
    
    $("#<?= yii\helpers\Html::getInputId($model, "total_palet") ?>").val(total_palet);
    $("#<?= yii\helpers\Html::getInputId($model, "total_pcs") ?>").val(total_pcs);
    $("#<?= yii\helpers\Html::getInputId($model, "total_m3") ?>").val( formatNumberForUser4Digit(total_m3) );
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail-mutasi > tbody > tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail()){
            submitform($form);
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
    var field0 = $('#<?= yii\helpers\Html::getInputId($model, 'kode_permintaan') ?>');
    if(!field0.val()){
        $(field0).parents('.form-group').addClass('error-tb-detail');
        has_error = has_error + 1;
    }else{
        $(field0).parents('.form-group').removeClass('error-tb-detail');
    }
    $('#table-detail-mutasi > tbody > tr').each(function(){
        var field1 = $(this).find('input[name*="[nomor_produksi]"]');
        var field2 = $(this).find('input[name*="[gudang_asal_display]"]');
        var field3 = $(this).find('input[name*="[qty_kecil]"]');
        var field4 = $(this).find('input[name*="[qty_m3]"]');
        if(!field1.val()){
            $(field1).parents('td').addClass('error-tb-detail');
            $(field2).parents('td').addClass('error-tb-detail');
            $(field3).parents('td').addClass('error-tb-detail');
            $(field4).parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(field1).parents('td').removeClass('error-tb-detail');
            $(field2).parents('td').removeClass('error-tb-detail');
            $(field3).parents('td').removeClass('error-tb-detail');
            $(field4).parents('td').removeClass('error-tb-detail');
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("readonly","readonly"); });
    $('#<?= yii\bootstrap\Html::getInputId($model, 'pegawai_mutasi') ?>').attr('disabled','');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_produksi') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
//    $('#btn-save').attr('disabled','');
    setButtonSave();
	setTimeout(function(){
        setParent();
	},1000);
}

function setButtonSave(){
    var asd = true;
    $('#table-detail-mutasi > tbody > tr').each(function(i,v){
        var mutasi_keluar_id = $(this).find('input[name*="[mutasi_keluar_id]"]').val();
        if(mutasi_keluar_id){
            asd &= true;
        }else{
            asd &= false;
        }
        if( (i+1) == $('#table-detail-mutasi > tbody > tr').length ){
            if(asd){
                $('#btn-save').attr('disabled','');
            }else{
                $('#btn-save').removeAttr('disabled');
            }
        }
    });
}

function getItemsByPk(id){
	$('#table-detail-mutasi > tbody').addClass('animation-loading');
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/getItemsByPk']); ?>',
        type   : 'POST',
        data   : {id:id},
        success: function (data) {
            if(data.html){
                $('#table-detail-mutasi > tbody').html(data.html);
				reordertable('#table-detail-mutasi');
				$('#table-detail-mutasi > tbody').removeClass('animation-loading');
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/gudang/mutasikeluar/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function cancelItem(ele,callback){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail-mutasi');
        if(callback != null){
            eval(callback);
        }
    });
}


</script>