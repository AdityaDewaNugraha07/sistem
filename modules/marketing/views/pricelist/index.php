<?php
/* @var $this yii\web\View */
$this->title = 'Master Harga / Tarif';
app\assets\DatatableAsset::register($this);
app\assets\BootstrapSelectAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatepickerAsset::register($this);

use yii\helpers\Html;
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
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/pricelist/history"); ?>"> <?= Yii::t('app', 'Price List History'); ?> </a>
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
                                <div class="caption col-md-9 pull-left">
                                    <!-- <span class="caption-subject hijau bold col-md3 pull-left"><i class="fa fa-tags"></i> <?= Yii::t('app', 'Price List Produk'); ?></span> -->
                                    <span class="col-md-12 pull-left" id="zzz" style="margin-top: -12px;"></span>
                                </div>
                                <div class="pull-right">
                                    <?php /* <a class="btn hijau btn-outline" id="btn-new-price" onclick="setHargaBaru(); setDisabledDatepicker(false);"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Penetapan Harga Baru'); ?></a> */ ?>
                                    <a class="btn hijau btn-outline" id="btn-new-price" href="#" ><i class="fa fa-plus"></i> <?= Yii::t('app', 'Penetapan Harga Baru'); ?></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php $form = \yii\bootstrap\ActiveForm::begin([
                                    'id' => 'pricelist-update',
                                ]); ?>
                                <div id="table-grade_wrapper" class="dataTables_wrapper no-footer">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-8">
                                            <div id="table-grade_filter" class="dataTables_filter visible-lg visible-md form-horizontal" style="width: 100%; margin-top: -7px; margin-bottom: 15px;">
                                                <div class="col-md-12 form-group" id="filter-dropdown">
                                                    <div class="col-md-4" id="dropdown-filter">
                                                        <?= yii\bootstrap\Html::activeDropDownList($model, 'produk_group', \app\models\MDefaultValue::getOptionListBuatPL('jenis-produk'),['class'=>'form-control bs-select','data-style'=>'blue-hoki btn-outline',]); ?>
                                                    </div>
                                                    <div class="col-md-8" style="margin-left: -20px" id="date-filter">
                                                        <?= yii\bootstrap\Html::dropDownList('harga_tanggal_penetapan','',[],['class'=>'form-control bs-select','data-style'=>'blue-hoki btn-outline','id'=>'harga_tanggal_penetapan']); ?>
                                                    </div>
                                                    
                                                    <div class="col-md-5" style="margin-left: -20px; display: none;" id="date-filter-editmode">
                                                        <div class="input-group date date-picker" data-date-start-date="+1 d" data-date-dates-disabled="">
                                                            <?php
                                                            // setting awal mulai tanggal enable disini dul
                                                            $time_original = strtotime(date('Y-m-d', strtotime('+1 days')));
                                                            $tanggal = date("d/m/Y", ($time_original));
                                                            ?>                                                          
                                                            <?php 
                                                            // 2020-08-10 bikin lambat disable sek
                                                            //echo \yii\bootstrap\Html::textInput('harga_tanggal_penetapan', $tanggal,['class'=>'form-control','id'=>'harga_tanggal_penetapan_picker','onchange'=>'setPrice(this.value);','readonly'=>'readonly']); 
                                                            ?>
                                                            <span class="input-group-btn">
                                                                <button class="btn default" type="button" style="margin-left: -40px;">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                        </div> 
                                                    </div>
                                                    <div class="col-md-2" id="tanggalPenetapan">
                                                        <label id="label_tanggalx" style="display: none;">Tanggal Penetapan Harga : </label>
                                                        <input id="tanggalx" name="tanggalx" class="md-col-3 form-control" onkeydown="return false" style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 dataTables_moreaction visible-lg visible-md">
                                            <?php /* <a class="btn blue btn-outline" id="btn-update-price" href="#"><i class="fa fa-edit"></i> <?= Yii::t('app', 'Edit Harga'); ?></a>
                                            <a class="btn red btn-outline" id="btn-delete" onclick="hapusPricelist();" style=""><i class="icon-trash"></i> <?= Yii::t('app', 'Delete'); ?></a>

                                            <a class="btn red btn-outline ciptana-spin-btn ladda-button" id="btn-cancel" onclick="unsetUpdateForm()" data-original-title="Batalkan Perubahan" style="display: none;"><i class="fa fa-remove"></i> <?= Yii::t('app', 'Cancel'); ?></a>
                                            &nbsp;&nbsp;
                                            <a class="btn btn-icon-only btn-default tooltips" onclick="printout()" data-original-title="Print Out"><i class="fa fa-print"></i></a>
                                            <a class="btn btn-icon-only btn-default tooltips" onclick="topdf()" data-original-title="Export to PDF"><i class="fa fa-files-o"></i></a>
                                            <a class="btn btn-icon-only btn-default tooltips" onclick="toxls()" data-original-title="Export to Excel"><i class="fa fa-table"></i></a> */?>
                                            <div id="xxx" class="col-md-4 pull-left"></div>
                                            <div id="yyy" class="col-md-8 pull-right">
                                                <?php 
                                                $sql_role = "select othername from view_user where user_id = ".$_SESSION['__id']." ";
                                                $role = Yii::$app->db->createCommand($sql_role)->queryScalar();
                                                if ($role == "SUPERUSER") {
                                                ?>
                                                <a class="btn btn-icon-only btn-default tooltips" id="view" data-original-title="View"><i class="fa fa-eye"></i></a>
                                                <a class="btn btn-icon-only btn-default tooltips" onclick="printout('PRINT')" data-original-title="Print Out"><i class="fa fa-print"></i></a>
                                                <a class="btn btn-icon-only btn-default tooltips" onclick="printout('PDF')" data-original-title="Export to PDF"><i class="fa fa-files-o"></i></a>
                                                <a class="btn btn-icon-only btn-default tooltips" onclick="printout('EXCEL')" data-original-title="Export to Excel"><i class="fa fa-table"></i></a>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="page" class="col-md-12"></div>
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-hover" id="table-pricelist">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px;">No.</th>
                                                    <?php /*<th><?= Yii::t('app', 'Kode Produk') ?></th>*/?>
                                                    <th><?= Yii::t('app', 'Produk Nama') ?></th>
                                                    <th><?= Yii::t('app', 'Produk Kode') ?></th>
                                                    <?php /*<th style="width: 120px;"><?= Yii::t('app', 'HPP') ?></th>
                                                    <th style="width: 140px;"><?= Yii::t('app', 'Harga Dist') ?></th>
                                                    <th style="width: 140px;"><?= Yii::t('app', 'Harga Agent') ?></th> */?>
                                                    <th style="width: 140px;"><?= Yii::t('app', 'Harga End User') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td colspan="4" style="text-align: center;"><i><?= Yii::t('app', 'Data tidak ditemukan'); ?></i></td></tr>
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

<?php
isset($jp) ? $produk_group = $jp : $produk_group = $model->produk_group;

$sql_tanggalx = "select distinct(to_char(a.harga_tanggal_penetapan, 'YYYY-MM-DD')) as harga_tanggal_penetapan ".
                    "   from m_harga_produk a ".
                    "   left join m_brg_produk b on b.produk_id = a.produk_id ".
                    "   where a.status_approval = 'Not Confirmed' ".
                    "   or a.status_approval = 'REJECTED' ".
                    "   or a.status_approval = 'APPROVED' ".
                    "   and b.produk_group = '".$produk_group."' ".
                    "   limit 10 ".
                    "   ";
$query_tanggalx = Yii::$app->db->createCommand($sql_tanggalx)->queryAll();

$i = 0;
$len = count($query_tanggalx);
$today = date('Y-m-d');
$tanggalx = "['".$today."',";

foreach ($query_tanggalx as $key) {
    
    if ($i == 0) {
        $tanggalx .= "'".$key['harga_tanggal_penetapan']."',";
    } else if ($i == $len - 1) {
        $tanggalx .= "'".$key['harga_tanggal_penetapan']."'";
    }  else {
        $tanggalx .= "'".$key['harga_tanggal_penetapan']."',";
    }
    $i++;
}

$tanggalx .= "]";

$this->registerJs("
    setTglDropdown();

    $('#btn-new-price').click(function(){
        var jp = $('#mbrgproduk-produk_group').val();
        window.location = 'create?jp='+jp;
    });
    
    $('#". \yii\helpers\Html::getInputId($model, 'produk_group')."').change(function() {
        setTglDropdown();
        getContent();
        
    });

    $('#harga_tanggal_penetapan').change(function() {
        getContent();
    });

    var datesForDisable = $tanggalx;
    $('#tanggalx').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        datesDisabled: datesForDisable,
        startDate: '2020-03-12'
    })

    $('#table-grade_wrapper').bind('cut copy paste',function(e) {
        e.preventDefault();
    })
    
    formconfig();
    setGetLoad();
   
", yii\web\View::POS_READY); 
?>

<?php
$this->registerCss("
#table-pricelist thead tr th{
    text-align : center;
}
");
?>
<script>

function setTglDropdown(){
    //$('#harga_tanggal_penetapan').addClass('animation-loading');
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
        //alert('split 1'+split[0]+'\n split 2'+split[2]+'\n split 3'+split[3]+'\n split 4'+split[4]+'\n split 5'+split[5]+'\n split 6'+split[6]+'\n split 7'+split[7]+'\n split 8'+split[8]+'\n split 9'+split[9]);

        if(tgl_penetapan == null){
            //var tgl_penetapan = $('#harga_tanggal_penetapan').val();
            var tgl_penetapan = $('#tanggalx').val();
        }
        var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
        
        var teks = $(".filter-option,pull-left:contains('PRP')").text();
        var split = teks.split(" ");
        if (split[7] == "Not") {
            var kode = split[10];
        } else {
            var kode = split[9];
        }
        
        $('#table-pricelist tbody').html("");
        $("#table-pricelist tbody").load('<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/getContent']); ?>?jp='+jenis_produk+'&tp='+tgl_penetapan+'&kode='+kode, function() {
            // 2020-08-10 bikin lambat disable sek
            //setPrice();
            //setTombolHapusEdit();
        });

        $("#xxx").load('<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/xxx']); ?>?jp='+jenis_produk+'&tp='+tgl_penetapan+'&kode='+kode, function() {

        });

        // 2020-07-17 tambah status approval di atas halaman
        $("#zzz").load('<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/zzz']); ?>?jp='+jenis_produk+'&tp='+tgl_penetapan+'&kode='+kode, function() {

        });
    },300);
}

function getContentEdit(){
    setTimeout(function(){
        var tgl_penetapan = $('#harga_tanggal_penetapan').val();
        var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
        
        var teks = $(".filter-option,pull-left:contains('PRP')").text();
        var split = teks.split(" ");
        if (split[7] == "Not") {
            var kode = split[10];
        } else {
            var kode = split[9];
        }
        $('#table-pricelist tbody').html("");
        $("#table-pricelist tbody").load('<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/edit']); ?>?jp='+jenis_produk+'&tp='+tgl_penetapan+'&kode='+kode, function() {
            // 2020-08-10 bikin lambat disable sek
            //setPrice();
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
            var teks = $(".filter-option,pull-left:contains('PRP')").text();
            var split = teks.split(" ");
            if (split[7] == "Not") {
                var kode = split[10];
            } else {
                var kode = split[9];
            }

            tr.addClass('animation-loading');
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/setPrice']); ?>',
                type   : 'POST',
                data   : {tgl_penetapan:tgl_penetapan,produk_id:produk_id,kode:kode},
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
                    // VALUE m_harga_produk DISINI
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
        $(this).find('input[name*="[harga_enduser]"]').show();
        $(this).find('input[name*="[harga_keterangan]"]').show();
    });
	getContentEdit('edit',selected_date);
}

function unsetUpdateForm(){
    $('#btn-save').hide();
    $('#btn-cancel').hide();
    $('#btn-set-price').show();
    $('.btn-icon-only.btn-default').show();
    $('#date-filter').show();
    $('#btn-new-price').show();
    $('#date-filter-editmode').hide();
    $('#table-pricelist tbody tr').each(function(index){
        $(this).find('label').show();
        $(this).find('input[name*="[harga_enduser]"]').hide();
        $(this).find('input[name*="[harga_keterangan]"]').hide();
    });
    getContent('view');
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

// sini cuuuy
function setHargaBaru(){
    $('#label_tanggalx').show();
    $('#tanggalx').show();

    var total_produk = 0;
    var harga_tanggal_penetapan = $('#harga_tanggal_penetapan').val();
    var jp = $('#mbrgproduk-produk_group').val();

    /*$.ajax ({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/index']); ?>',
        type   : 'GET',
        data   : {jp:jp},
        success: function (data) {
            $("#tanggalPenetapan").load("TglPenetapan");
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });*/

    $('#table-pricelist tbody tr').each(function(index){
        var produk_id = $(this).find('input[name*="produk_id"]').val();
        if(produk_id){
            total_produk = total_produk+1;
        }
    });
    
    // setting awal mulai tanggal enable disini dul
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
        $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').prop('disabled',true);
        $(".bs-select").selectpicker("refresh");
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
        // 2020-08-10 bikin lambat disable sek
        //setPrice($('#harga_tanggal_penetapan_picker').val());
    }else { 
        var jenis_produk = $("#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>").val();
        cisAlert("Produk untuk "+jenis_produk+" belum ditambahkan, tambahkan terlebih dahulu dimaster produk");
        unsetUpdateForm();
        return false;
    }
}

function hapusPricelist(){
    var jp = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
    var tp = $('#harga_tanggal_penetapan').val();

    var kodes = $('select option:selected').text();
    var kodes = kodes.replace('(','');
    var kodes = kodes.replace(')','');
    var kode = kodes.split(' ');
    var jp = $('#mbrgproduk-produk_group').val();
    var tp = $('#harga_tanggal_penetapan').val();
    var kode = kode[kode.length - 1];

    openModal('<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/delete']) ?>?jp='+jp+'&tp='+tp+'&kode='+kode,'modal-delete-record');
}

function printout(caraPrint){
    var jp = $('#mbrgproduk-produk_group').val();
    var tp = $('#harga_tanggal_penetapan').val();
    var teks = $(".filter-option,pull-left:contains('PRP')").text();
    var split = teks.split(" ");
    var kode = split[9];

    if (tp) {
        window.open("<?= yii\helpers\Url::toRoute('/marketing/pricelist/PriceListPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint+'&jp='+jp+'&tp='+tp+'&kode='+kode,"",'location=_new, width=1200px, scrollbars=yes');
    }
}

</script>
