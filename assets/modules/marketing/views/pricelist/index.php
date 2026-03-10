<?php
/* @var $this yii\web\View */
$this->title = 'Master Harga / Tarif';
app\assets\DatatableAsset::register($this);
app\assets\BootstrapSelectAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Harga / Tarif'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/pricelist/index"); ?>"> <?= Yii::t('app', 'Price List'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/ongkostruk/index"); ?>"> <?= Yii::t('app', 'Ongkos Truck'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-tags"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Price List Produk'); ?></span>
                                </div>
                                <div class="pull-right">
                                    <a class="btn hijau btn-outline" id="btn-new-price" onclick="setHargaBaru(); setDisabledDatepicker(false);"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Penetapan Harga Baru'); ?></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php $form = \yii\bootstrap\ActiveForm::begin([
                                    'id' => 'pricelist-update',
                                ]); ?>
                                <div id="table-grade_wrapper" class="dataTables_wrapper no-footer">
                                    <div class="row">
                                        <div class="col-md-7 col-sm-12">
                                            <div id="table-grade_filter" class="dataTables_filter visible-lg visible-md form-horizontal" style="width: 100%; margin-top: -7px; margin-bottom: 15px;">
                                                <div class="form-group" id="filter-dropdown">
                                                    <div class="col-md-3" id="dropdown-filter">
                                                        <?= yii\bootstrap\Html::activeDropDownList($model, 'produk_group', \app\models\MDefaultValue::getOptionListBuatPL('jenis-produk'),['class'=>'form-control bs-select','data-style'=>'blue-hoki btn-outline']); ?>
                                                    </div>
                                                    <div class="col-md-6" style="margin-left: -20px" id="date-filter">
                                                        <?= yii\bootstrap\Html::dropDownList('harga_tanggal_penetapan','',[],['class'=>'form-control bs-select','data-style'=>'blue-hoki btn-outline','id'=>'harga_tanggal_penetapan']); ?>
                                                    </div>
                                                    <div class="col-md-5" style="margin-left: -20px; display: none;" id="date-filter-editmode">
                                                        <div class="input-group date date-picker" data-date-start-date="+1d">
                                                            <?php $time_original = strtotime(date('Y-m-d')); ?>
                                                            <?= \yii\bootstrap\Html::textInput('harga_tanggal_penetapan',date("d/m/Y", ($time_original + (3600*24))),['class'=>'form-control','id'=>'harga_tanggal_penetapan_picker','onchange'=>'setPrice(this.value);','readonly'=>'readonly']); ?>
                                                            <span class="input-group-btn">
                                                                <button class="btn default" type="button" style="margin-left: -40px;">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-sm-12 dataTables_moreaction visible-lg visible-md">
                                            <a class="btn blue btn-outline" id="btn-set-price" onclick="setUpdateForm(); setDisabledDatepicker(true);"><i class="fa fa-edit"></i> <?= Yii::t('app', 'Edit Harga'); ?></a>
                                            <a class="btn red btn-outline" id="btn-delete" onclick="hapusPricelist();" style="display: none;"><i class="icon-trash"></i> <?= Yii::t('app', 'Delete'); ?></a>
                                            <a class="btn hijau btn-outline ciptana-spin-btn ladda-button" id="btn-save" onclick="submitform(this)" data-original-title="Simpan Perubahan" style="display: none;"><i class="fa fa-save"></i> <?= Yii::t('app', 'Save'); ?></a>
                                            <a class="btn red btn-outline ciptana-spin-btn ladda-button" id="btn-cancel" onclick="unsetUpdateForm()" data-original-title="Batalkan Perubahan" style="display: none;"><i class="fa fa-remove"></i> <?= Yii::t('app', 'Cancel'); ?></a>
                                            &nbsp;&nbsp;
                                            <a class="btn btn-icon-only btn-default tooltips" onclick="printout()" data-original-title="Print Out"><i class="fa fa-print"></i></a>
                                            <a class="btn btn-icon-only btn-default tooltips" onclick="topdf()" data-original-title="Export to PDF"><i class="fa fa-files-o"></i></a>
                                            <a class="btn btn-icon-only btn-default tooltips" onclick="toxls()" data-original-title="Export to Excel"><i class="fa fa-table"></i></a>
                                        </div>
                                    </div>
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-hover" id="table-pricelist">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px;">No. </th>
                                                    <th><?= Yii::t('app', 'Kode Produk') ?></th>
                                                    <th><?= Yii::t('app', 'Nama Produk') ?></th>
                                                    <th><?= Yii::t('app', 'Dimensi') ?></th> 
                                                    <?php /*<th style="width: 120px;"><?= Yii::t('app', 'HPP') ?></th>
                                                    <th style="width: 140px;"><?= Yii::t('app', 'Harga Dist') ?></th>
                                                    <th style="width: 140px;"><?= Yii::t('app', 'Harga Agent') ?></th>*/?>
                                                    <th style="width: 140px;"><?= Yii::t('app', 'Harga End User') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td colspan="6" style="text-align: center;"><i><?= Yii::t('app', 'Data tidak ditemukan'); ?></i></td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs("
    setTglDropdown();
    $('#". \yii\helpers\Html::getInputId($model, 'produk_group')."').change(function() {
        setTglDropdown()
        getContent();
    });
    $('#harga_tanggal_penetapan').change(function() {
        getContent();
    });
    formconfig();
    setGetLoad();
", yii\web\View::POS_READY); ?>
<?php
$this->registerCss("
#table-pricelist thead tr th{
    text-align : center;
}
");
?>
<script>
function setTglDropdown(){
    $('#harga_tanggal_penetapan').addClass('animation-loading');
    var produk_group = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/setTglDropdown']); ?>',
		type   : 'POST',
		data   : {produk_group:produk_group},
		success: function (data) {
			$("#harga_tanggal_penetapan").html(data.html);
            $('#harga_tanggal_penetapan').removeClass('animation-loading');
            $(".bs-select").selectpicker("refresh");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
    return true;
}

function getContent(tgl_penetapan=null){
    setTimeout(function(){
        if(tgl_penetapan == null){
            var tgl_penetapan = $('#harga_tanggal_penetapan').val();
        }
        var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
        $('#table-pricelist tbody').html("");
        $("#table-pricelist tbody").load('<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/getContent']); ?>?jp='+jenis_produk, function() {
            setPrice();
            setTombolHapusEdit();
        });
    },300);
}

function setPrice(tgl_penetapan=null){
    if(tgl_penetapan == null){
        var tgl_penetapan = $('#harga_tanggal_penetapan').val();
    }
    if(tgl_penetapan){
        $('#table-pricelist tbody tr').each(function(index){
            var produk_id = $(this).find('input[name*="produk_id"]').val();
            var tr = $(this);
            tr.addClass('animation-loading');
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/setPrice']); ?>',
                type   : 'POST',
                data   : {tgl_penetapan:tgl_penetapan,produk_id:produk_id},
                success: function (data) {
                    if(data.harga_distributor){
                        $(tr).find('label[id*="harga_distributor"]').html(data.harga_distributor_formatted);
                        $(tr).find('input[name*="harga_distributor"]').val(data.harga_distributor);
                    }else{
                        $(tr).find('label[id*="harga_distributor"]').html(0);
                        $(tr).find('input[name*="harga_distributor"]').val(0);
                    }
                    if(data.harga_agent){
                        $(tr).find('label[id*="harga_agent"]').html(data.harga_agent_formatted);
                        $(tr).find('input[name*="harga_agent"]').val(data.harga_agent);
                    }else{
                        $(tr).find('label[id*="harga_agent"]').html(0);
                        $(tr).find('input[name*="harga_agent"]').val(0);
                    }
                    if(data.harga_enduser){
                        $(tr).find('label[id*="harga_enduser"]').html(data.harga_enduser_formatted);
                        $(tr).find('input[name*="harga_enduser"]').val(data.harga_enduser);
                    }else{
                        $(tr).find('label[id*="harga_enduser"]').html(0);
                        $(tr).find('input[name*="harga_enduser"]').val(0);
                    }
                    if(data.harga_hpp){
                        $(tr).find('label[id*="harga_hpp"]').html(data.harga_hpp_formatted);
                        $(tr).find('input[name*="harga_hpp"]').val(data.harga_hpp);
                    }else{
                        $(tr).find('label[id*="harga_hpp"]').html(0);
                        $(tr).find('input[name*="harga_hpp"]').val(0);
                    }
                    tr.removeClass('animation-loading');
                },
                error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
            });
        });
    }
    $(".bs-select").selectpicker("refresh");
}

function setUpdateForm(){
    $('#btn-save').show();
    $('#btn-cancel').show();
    $('#btn-set-price').hide();
    $('#btn-delete').hide();
    $('.btn-icon-only.btn-default').hide();
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').prop('disabled',true); $(".bs-select").selectpicker("refresh");
    $('#date-filter').hide();
    $('#btn-new-price').hide();
    var selected_date = $('#harga_tanggal_penetapan').val();
    if(selected_date){
        selected_date = Date.parse(selected_date).toString('dd/MM/yyyy');
        $('#harga_tanggal_penetapan_picker').val(selected_date);
    }
    $('#date-filter-editmode').show();
    $('#table-pricelist tbody tr').each(function(index){
        $(this).find('label').hide();
        $(this).find('input[name*="[harga_hpp]"]').show();
        $(this).find('input[name*="[harga_distributor]"]').show();
        $(this).find('input[name*="[harga_agent]"]').show();
        $(this).find('input[name*="[harga_enduser]"]').show();
    });
    $('.money-format').maskMoney(
        {'symbol':'','defaultZero':true,'allowZero':true,'decimal':'.','thousands':',','precision':0}
    );
    setPrice($('#harga_tanggal_penetapan_picker').val());
}

function unsetUpdateForm(){
    $('#btn-save').hide();
    $('#btn-cancel').hide();
    $('#btn-set-price').show();
    $('.btn-icon-only.btn-default').show();
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').prop('disabled',false); $(".bs-select").selectpicker("refresh");
    $('#date-filter').show();
    $('#btn-new-price').show();
    $('#date-filter-editmode').hide();
    $('#table-pricelist tbody tr').each(function(index){
        $(this).find('label').show();
        $(this).find('input[name*="[harga_hpp]"]').hide();
        $(this).find('input[name*="[harga_distributor]"]').hide();
        $(this).find('input[name*="[harga_agent]"]').hide();
        $(this).find('input[name*="[harga_enduser]"]').hide();
    });
    getContent();
}

function afterSave(){
    setTglDropdown();
    unsetUpdateForm();
}

function setGetLoad(){
    <?php if(isset($_GET['tp'])){ ?>
        setTimeout(function(){
            $('#harga_tanggal_penetapan').val('<?= $_GET['tp'] ?>');
            $(".bs-select").selectpicker("refresh");
            getContent();
        },800);
    <?php }else{ ?>
        getContent();
    <?php } ?>
}

function setTombolHapusEdit(){
    var tgl_penetapan = $('#harga_tanggal_penetapan').val();
    if(tgl_penetapan){
        var today = new Date();
        var inputDate = new Date(tgl_penetapan);
        if(inputDate > today){
            $('#btn-delete').show();
            $('#btn-set-price').show();            
        }else{
            $('#btn-delete').hide();
            $('#btn-set-price').hide();
        }
    }else{
        $('#btn-delete').hide();
        $('#btn-set-price').hide();
    }
}

function setDisabledDatepicker(par){
    if(par){
        $('#harga_tanggal_penetapan_picker').prop('disabled',true);
        $('#harga_tanggal_penetapan_picker').siblings().hide();
    }else{
        $('#harga_tanggal_penetapan_picker').prop('disabled',false);
        $('#harga_tanggal_penetapan_picker').siblings().show();
    }
}

function setHargaBaru(){
    var total_produk = 0;
    $('#table-pricelist tbody tr').each(function(index){
        var produk_id = $(this).find('input[name*="produk_id"]').val();
        if(produk_id){
            total_produk = total_produk+1;
        }
    });
    
    if(total_produk > 0){
        var besok = new Date();
        besok.setDate(besok.getDate() + 1);
        besok = besok.toString('dd/MM/yyyy');
        $('#harga_tanggal_penetapan_picker').val(besok);
        $('#btn-save').show();
        $('#btn-cancel').show();
        $('#btn-set-price').hide();
        $('#btn-delete').hide();
        $('.btn-icon-only.btn-default').hide();
        $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').prop('disabled',true); $(".bs-select").selectpicker("refresh");
        $('#date-filter').hide();
        $('#btn-new-price').hide();
        $('#date-filter-editmode').show();
        $('#table-pricelist tbody tr').each(function(index){
            $(this).find('label').hide();
            $(this).find('input[name*="[harga_hpp]"]').show();
            $(this).find('input[name*="[harga_distributor]"]').show();
            $(this).find('input[name*="[harga_agent]"]').show();
            $(this).find('input[name*="[harga_enduser]"]').show();
        });
        $('.money-format').maskMoney(
            {'symbol':'','defaultZero':true,'allowZero':true,'decimal':'.','thousands':',','precision':0}
        );
        setPrice($('#harga_tanggal_penetapan_picker').val());
    }else{
        var jenis_produk = $("#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>").val();
        cisAlert("Produk untuk "+jenis_produk+" belum ditambahkan, tambahkan terlebih dahulu dimaster produk");
        unsetUpdateForm();
        return false;
    }
}

function hapusPricelist(){
    var jp = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
    var tp = $('#harga_tanggal_penetapan').val();
    openModal('<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/delete']) ?>?jp='+jp+'&tp='+tp,'modal-delete-record');
}

</script>