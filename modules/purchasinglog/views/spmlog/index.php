<?php
/* @var $this yii\web\View */
$this->title = 'SPM Log';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\MagnificPopupAsset::register($this);
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
table.table thead tr th{
	font-size: 1.3rem;
	padding: 2px;
	border: 1px solid #A0A5A9;
}
table.table#table-detail-permintaan thead tr th{
	padding: 10px;
	border: 1px solid #A0A5A9;
}
.table-striped.table-bordered.table-hover.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover2.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover3.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover4.table-bordered > thead > tr > th {
    line-height: 1;
}
.add-more:hover {
    background: #58ACFA;
}

</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4> <?=$this->title;?> </h4></span>
                                </div>
                                <div class="tools">
                                    <a class="btn blue btn-sm btn-outline pull-right" style="height: 30px;" onclick="daftarSpmLog()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar SPM Log'); ?></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'spk_shipping_id'); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'keterangan'); ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 5px;">
                                                <table style="width: 100%">
                                                    <tr>
                                                        <td style="width: 60%"><?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%; font-weight:bold','disabled'=>'disabled', 'placeholder'=>'Auto Generate']) ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <?php
                                        if (empty(isset($_GET['success'])) || isset($_GET['edit']) ) {
                                        ?>
                                        <?= $form->field($model, 'tanggal',[
                                                                    'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                        <?= $form->field($model, 'etd',[
                                                                    'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                        <?= $form->field($model, 'eta_logpond',[
                                                                    'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                        <?= $form->field($model, 'eta',[
                                                                    'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                        <?php
                                        } else {
                                        ?>
                                        <?= $form->field($model, 'tanggal')->textInput(['id'=>'tspkshipping-tanggal','class'=>'form-control','readonly'=>true])->label("Tanggal"); ?>
                                        <?= $form->field($model, 'etd')->textInput(['id'=>'tspkshipping-etd','class'=>'form-control','readonly'=>true])->label("ETD To Logpond"); ?>
                                        <?= $form->field($model, 'eta_logpond')->textInput(['id'=>'tspkshipping-eta_logpond','class'=>'form-control','readonly'=>true])->label("ETA Logpond"); ?>
                                        <?= $form->field($model, 'eta')->textInput(['id'=>'tspkshipping-eta','class'=>'form-control','readonly'=>true])->label("ETA Tanjung Mas"); ?>
                                        <?php
                                        }
                                        ?>

                                        <?= $form->field($model, 'lokasi_muat')->textarea(['rows' =>'3']); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'estimasi_total_batang')->textInput(['id'=>'tspkshipping-estimasi_total_batang','class'=>'form-control text-right','readonly'=>true])->label("Est. Total Pcs"); ?>
                                        <?= $form->field($model, 'estimasi_total_m3')->textInput(['id'=>'tspkshipping-estimasi_total_m3','class'=>'form-control text-right','readonly'=>true])->label("Est. Total m<sup>3</sup>"); ?>
                                        <?= $form->field($model, 'nama_tongkang')->textInput(); ?>
                                        <?= $form->field($model, 'asuransi')->inline(true)->radioList([true=>"Ya",false=>"Tidak"]); ?>
                                        <?= $form->field($model, 'pic_shipping')->dropDownList(\app\models\MPegawai::getOptionListByDept(114),['class'=>'form-control select2','prompt'=>''])->label('PIC Shipping'); ?>
                                        <?= $form->field($model, 'keterangan')->textarea(['rows' => '3']) ?>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <h4><?= Yii::t('app', 'Pengajuan Pembelian Log Alam'); ?></h4>
                <div class="row" style="margin-left: -20px; margin-right: -20px;">
                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail-permintaan">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;"></th>
                                        <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Kode<br>Keputusan'); ?></th>
                                        <th style="width: 80px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Keputusan'); ?></th>
                                        <th style="width: 200px; line-height: 1"><?= Yii::t('app', 'Nomor<br>Kontrak'); ?></th>
                                        <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Suplier'); ?></th>
                                        <th style="width: 250px; line-height: 1"><?= Yii::t('app', 'Asal Kayu'); ?></th>
                                        <th style="width: 80px; line-height: 1"><?= Yii::t('app', 'Pcs'); ?></th>
                                        <th style="width: 80px; line-height: 1"><?= Yii::t('app', 'Volume (m<sup>3</sup>)'); ?></th>
                                        <th style="width: 80px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" style="text-align: right;">Total Permintaan &nbsp; </td>
                                        <td style="text-align: right; font-weight: 600;"><span id="place-total-batang">0</span></td>
                                        <td style="text-align: right; font-weight: 600;"><span id="place-total-volume">0</span></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <span class="font-red-flamingo pull-right" id="place-warning-overpembelian"></span>
                    </div>
                </div>
                <?php
                if (empty(isset($_GET['success'])) || isset($_GET['edit']) ) {
                ?>
                <a class="btn btn-xs blue" id="btn-add-permintaan" onclick="tambahKeputusan()"><i class="fa fa-plus"></i> Tambah Keputusan</a>
                <hr>
                <div class="row">
                    <div class="form-actions pull-right">
                        <div class="col-md-12 right">
                            <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                            <?php // echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>"printout('PRINT')"]); ?>
                            <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>

<?php
$pagemode = "";
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
    $('select[name*=\"[pic_shipping]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
	});
    getItems();
", yii\web\View::POS_READY); ?>
<script>

function tambahKeputusan(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/openKeputusanPembelianLog']) ?>','modal-keputusanPembelianLog','90%');
}

function pickKeputusanPembelianLog(id){
	$("#modal-keputusanPembelianLog").find('button.fa-close').trigger('click');
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/pickKeputusanPembelianLog']); ?>',
        type   : 'POST',
        data   : {id:id},
        success: function (data){
            if(data){
                $('#modal-keputusanPembelianLog').modal('hide');
                var allowadd = true;
                //TPengajuanPembelianlog[0][pengajuan_pembelianlog_id]
                $('#table-detail-permintaan > tbody > tr').each(function(){
                    if($(this).find("input[name*='[pengajuan_pembelianlog_id]']").val() != data.pengajuan_pembelianlog_id){
                        allowadd &= true;
                    }else{
                        allowadd = false;
                    }
                });
                if(allowadd){
                    $(data.html).hide().appendTo('#table-detail-permintaan > tbody').fadeIn(100,function(){
                        reordertable("#table-detail-permintaan");
                        totalBatangVolume();
                    });
                    var lokasi_muat = $('#tspkshipping-lokasi_muat').val();
                    if (lokasi_muat == "" || lokasi_muat == null) {
                        lokasi_muat = data.lokasi_muat;
                    } else {
                        lokasi_muat = lokasi_muat+", "+data.lokasi_muat;
                    }
                    $('#tspkshipping-lokasi_muat').val(lokasi_muat);
                }else{
                    cisAlert("Permintaan ini sudah dipilih di list");
                }
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function totalBatangVolume(){
    var total_batang = 0; var total_m3 = 0; 
    $("#table-detail-permintaan > tbody > tr").each(function(i){
        total_batang += unformatNumber( $(this).find('input[name*="[qty_batang]"]').val() );
        total_m3 += unformatNumber( $(this).find('input[name*="[qty_m3]"]').val() );
        if((i+1) == $("#table-detail-permintaan > tbody > tr").length){
            $("#<?= yii\helpers\Html::getInputId($model, "estimasi_total_batang") ?>").val( total_batang );
            var totals_m3 = total_m3.toFixed(2);
            $("#<?= yii\helpers\Html::getInputId($model, "estimasi_total_m3") ?>").val( totals_m3 );
            $("#place-total-batang").text(total_batang.toLocaleString());
            $("#place-total-volume").text(formatNumberForUser(total_m3.toLocaleString()));
        }
    });
}

function hapusPengajuanPembelianLog(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail-permintaan');
		totalBatangVolume();
    });
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail-permintaan tbody tr').length;
        var estimasi_total_batang = unformatNumber($('#tspkshipping-estimasi_total_batang').val());
        var estimasi_total_m3 = unformatNumber($('#tspkshipping-estimasi_total_m3').val());        
        if(jumlah_item <= 0){
            cisAlert('Isi detail terlebih dahulu');
            return false;
        } else {
            submitform($form);
        }
    }
    return false;
}

function daftarSpmLog(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/daftarSpmLog']) ?>','modal-daftarSpmLog','90%');
}

function getItems(){
    <?php 
    if (isset($_GET['spk_shipping_id'])) {
    ?>
    var spk_shipping_id = '<?php echo $_GET['spk_shipping_id'];?>';
    <?php
    } else {
    ?>
    var spk_shipping_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'spk_shipping_id') ?>').val();
    <?php
    }
    ?>

    <?php
    if (isset($_GET['success'])) {
    ?>
        var success = '<?php echo isset($_GET['success']);?>';
    <?php
    } else {
    ?>
        var success = 0;
    <?php
    }
    ?>

    <?php
    if (isset($_GET['edit'])) {
    ?>
        var edit = 1;
    <?php
    } else {
    ?>
        var edit = 0;
    <?php
    }
    ?>

	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/getItems']); ?>',
		type   : 'POST',
		data   : {spk_shipping_id:spk_shipping_id, success:success},
		success: function (data) {
			$('#table-detail-permintaan > tbody').html("");
			if(data.html){
				$('#table-detail-permintaan > tbody').html(data.html);
			}
			totalBatangVolume();
			reordertable('#table-detail-permintaan');
            if (success == '' || edit == 1) {

            } else if ((spk_shipping_id != '' && success == 1) || (spk_shipping_id != '')) {
                $('form').find("input[name*='[tanggal]']").prop('disabled', true);
                $('form').find("input[name*='[etd]']").prop('disabled', true);
                $('form').find("input[name*='[eta_logpond]']").prop('disabled', true);
                $('form').find("input[name*='[eta]']").prop('disabled', true);
                $('form').find("input[name*='[nama_tongkang]']").prop('disabled', true);
                $('.input-group-addon').hide();
                $('form').find("textarea[name*='[lokasi_muat]']").prop('disabled', true);
                $('form').find("input[name*='[asuransi]']").prop('disabled', true);
                $('form').find("select[name*='[pic_shipping]']").prop('disabled', true);
                $('form').find("textarea[name*='[keterangan]']").prop('disabled', true);
                $('form').find('.btn').prop('disabled', true);
                $('form').find('.red').hide();
            }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function viewSpmLog(id) {
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/index','spk_shipping_id'=>'']); ?>'+id+'&success=1');
}

function editSpmLog(id) {
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/index','spk_shipping_id'=>'']); ?>'+id+'&edit=1');
}

function detailPengajuanPembelianlog(id) {
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/openDetailKeputusanPembelianlog','id'=>'']) ?>'+id,'modal-detailKeputusanPembelianlog','90%');
}
</script>