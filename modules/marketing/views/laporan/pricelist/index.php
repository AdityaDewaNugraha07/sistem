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
                        <a href="#"> <?= Yii::t('app', 'Price List'); ?> </a>
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
                                    <?php /* <a class="btn hijau btn-outline" id="btn-new-price" href="#" ><i class="fa fa-plus"></i> <?= Yii::t('app', 'Penetapan Harga Baru'); ?></a> */?>
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

                                                            <?= \yii\bootstrap\Html::textInput('harga_tanggal_penetapan', $tanggal,['class'=>'form-control','id'=>'harga_tanggal_penetapan_picker','readonly'=>'readonly']); ?>
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
                                            <div id="xxx" class="col-md-6 pull-left"></div>
                                            <div id="yyy" class="col-md-6 pull-right">
                                                <?php /*<a class="btn btn-icon-only btn-default tooltips" onclick="printout('PRINT')" data-original-title="Print Out"><i class="fa fa-print"></i></a> ?>
                                                <a class="btn btn-icon-only btn-default tooltips" onclick="printout('PDF')" data-original-title="Export to PDF"><i class="fa fa-files-o"></i></a>
                                                <?php /*a class="btn btn-icon-only btn-default tooltips" onclick="printout('EXCEL')" data-original-title="Export to Excel"><i class="fa fa-table"></i></a>*/?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-hover" id="table-pricelist">
                                            <thead>
                                                <tr>
                                                    <th class="td-kecil" style="width: 50px;">No.</th>
                                                    <th class="td-kecil"><?= Yii::t('app', 'Produk Nama') ?></th>
                                                    <th class="td-kecil"><?= Yii::t('app', 'Produk Kode') ?></th>
                                                    <th class="td-kecil"><?= Yii::t('app', 'Produk Dimensi') ?></th>
                                                    <th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'Harga<br>Sebelumnya') ?></th>
                                                    <th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'Harga<br>End User') ?></th>
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

<?php
isset($jp) ? $produk_group = $jp : $produk_group = $model->produk_group;

$sql_tanggalx = "select distinct(to_char(a.harga_tanggal_penetapan, 'YYYY-MM-DD')) as harga_tanggal_penetapan ".
                    "   from m_harga_produk a ".
                    "   left join m_brg_produk b on b.produk_id = a.produk_id ".
                    //"   where a.status_approval = 'Not Confirmed' ".
                    //"   or a.status_approval = 'REJECTED' ".
                    "   or a.status_approval = 'APPROVED' ".
                    "   and b.produk_group = '".$produk_group."' ".
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
    var produk_group = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/laporan/setTglDropdown']); ?>',
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
        var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();

        var teks = $(".filter-option,pull-left:contains('PRP')").text();
        var split = teks.split(" ");
        if (split[7] == "Not") {
            var kode = split[10];
        } else {
            var kode = split[9];
        }
        $('#table-pricelist tbody').html("");
        $("#table-pricelist tbody").load('<?= \yii\helpers\Url::toRoute(['/marketing/laporan/getContent']); ?>?jp='+jenis_produk+'&tp='+tgl_penetapan+'&kode='+kode, function() {
        });
        //$("#xxx").load('<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/xxx']); ?>?jp='+jenis_produk+'&tp='+tgl_penetapan, function() {});
    },300);
}

function printout(caraPrint){
    var jp = $('#mbrgproduk-produk_group').val();
    var tp = $('#harga_tanggal_penetapan').val();

    if (!empty(jp) || !empty(tp)) {
        alert(jp+' '+tp)
    } else {
        window.open("<?= yii\helpers\Url::toRoute('/marketing/pricelist/PriceListPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint+'&jp='+jp+'&tp='+tp,"",'location=_new, width=1200px, scrollbars=yes');
    }
}

</script>
