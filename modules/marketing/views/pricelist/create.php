<?php
/* @var $this yii\web\View
 * @var $model MBrgProduk
 */
$this->title = 'Master Harga / Tarif';
app\assets\DatatableAsset::register($this);
app\assets\BootstrapSelectAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatepickerAsset::register($this);

use app\components\DeltaFormatter;
use app\models\MBrgProduk;
use app\models\MMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$jp = isset($_GET['jp']) ? $_GET['jp'] : 'Platform';
$tp = isset($_GET['tp']) ? $_GET['tp'] : '';
?>

<h1 class="page-title"> <?php echo Yii::t('app', 'Master Harga / Tarif'); ?></h1>
<body onload="setHargaBaru();">

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/pricelist/create") ?>"> <?= Yii::t('app', 'Price List') ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-tags"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Price List Produk') ?> <?php echo " : ".$model->produk_group;?></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'pricelist-update',
                                ]); ?>
                                <div id="table-grade_wrapper" class="dataTables_wrapper no-footer">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-8">
                                            <div id="table-grade_filter" class="dataTables_filter visible-lg visible-md form-horizontal" style="width: 100%; margin-top: -7px; margin-bottom: 15px;">
                                                <div class="col-md-12 form-group" id="filter-dropdown">
                                                    <div class="col-md-2" id="dropdown-filter">
                                                        <input id="mbrgproduk-produk_group" name="mbrgproduk-produk_group" value="<?php echo $jp;?>" class="md-col-3 form-control" readonly="readonly">
                                                    </div>
                                                    <div class="col-md-2">&nbsp;</div>
                                                    <div class="form-group">
                                                        <div class="date col-md-6" style="margin-left: -20px;" id="tanggalPenetapan">
                                                            <div class="input-group">
                                                                <input id="tanggalx" name="tanggalx" class="md-col-12 form-control" onkeydown="return false" placeholder="Tanggal Penetapan" required="required">
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
                                        </div>
                                        <div class="col-md-4 col-sm-4 dataTables_moreaction visible-lg visible-md">
                                            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['id'=>'btn-save', 'data-original-title'=>'Simpan Perubahan', 'class'=> 'btn hijau btn-outline ciptana-spin-btn ladda-button']) ?>
                                            <a class="btn red btn-outline ciptana-spin-btn ladda-button" id="btn-cancel" onclick="history.back();" data-original-title="Batalkan Perubahan"><i class="fa fa-remove"></i> <?= Yii::t('app', 'Cancel') ?></a>
                                        </div>
                                    </div>
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-hover" id="table-pricelist">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px;">No. </th>
                                                    <th><?= Yii::t('app', 'Produk Nama') ?></th>
                                                    <th><?= Yii::t('app', 'Kode Dimensi') ?></th>
                                                    <th style="width: 140px;"><?= Yii::t('app', 'Harga End User') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $tgl_penetapan = Yii::$app->request->post('tgl_penetapan');
                                                $tgl_penetapan = DeltaFormatter::formatDateTimeForDb($tgl_penetapan);
                                                /*$sql_list_produk = " select a.produk_group, a.produk_id, a.produk_nama, a.produk_dimensi, b.harga_enduser ".
                                                                        "   from m_brg_produk a ".
                                                                        "   left join m_harga_produk b on b.produk_id = a.produk_id ".
                                                                        "   where a.produk_group = '".$jp."' ".
                                                                        "   and a.active = true ".
                                                                        "   and status_approval = 'APPROVED' ".
                                                                        "   order by a.produk_id asc ".
                                                                        "   "; */

                                                $sql_list_produk = " select a.produk_group, a.produk_id, a.produk_nama, a.produk_dimensi ".
                                                                        "   from m_brg_produk a".
                                                                        "   where a.produk_group = '".$jp."' ".
                                                                        "   and a.active = true ".
                                                                        "   order by a.produk_id asc ".
                                                                        "   ";
                                                $models = Yii::$app->db->createCommand($sql_list_produk)->queryAll();
                                                if($models){
                                                    $i = 1;
                                                    $total = 0;
                                                    
                                                    //per tanggal 07-04-2021 ada perubahan logic untuk menampilkan value pada harga enduser
                                                    //yang ditempilkan adalah harga terakhir dari tanggal penetapan yang telah di approve
                                                    foreach ($models as $key) {
                                                        $sql_pricelist_kode = " select kode,harga_tanggal_penetapan from m_harga_produk ".
                                                                             " join m_brg_produk on m_brg_produk.produk_id = m_harga_produk.produk_id ".
                                                                             " where m_harga_produk.status_approval ='APPROVED' and produk_group = '".$jp."' ".
                                                                             " order by 2 desc limit 1";
                                                        $pricelist_kode = Yii::$app->db->createCommand($sql_pricelist_kode)->queryScalar();
                                                        
                                                        $sql_harga_enduser = "select harga_enduser ".
                                                                                "   from m_harga_produk ".
                                                                                "   where produk_id = ".$key['produk_id']." ".
                                                                                "   and status_approval = 'APPROVED' and kode = '".$pricelist_kode."' ".
                                                                                "   order by harga_id desc ".
                                                                                "   limit 1 ";
                                                        
//                                                        
                                                        $harga_enduser = Yii::$app->db->createCommand($sql_harga_enduser)->queryScalar();

                                                        isset($harga_enduser) ? $harga_enduser = $harga_enduser : $harga_enduser = 0;
                                                ?>
                                                    <input type="hidden" id="mhargaproduk-<?php echo $i;?>-produk_id" name="MHargaProduk[<?php echo $i;?>][produk_id]" class="form-control money-format" value='<?php echo $key['produk_id'];?>'>
                                                    <tr>
                                                        <td class="text-center" style="padding-top: 15px;"><?php echo $i;?></td>
                                                        <td style="padding-top: 15px;"><?php echo $key['produk_nama'];?></td>
                                                        <td style="padding-top: 15px;"><?php echo $key['produk_dimensi'];?></td>
                                                        <td><input type="text" id="mhargaproduk-<?php echo $i;?>-harga_enduser" name="MHargaProduk[<?php echo $i;?>][harga_enduser]" class="form-control text-right harga_endusers" value="<?php echo $harga_enduser;?>"></td>
                                                    </tr>
                                                    <input type="hidden" id="mhargaproduk-<?php echo $i;?>-harga_hpp" name="MHargaProduk[<?php echo $i;?>][harga_hpp]" class="form-control money-format" value='0'>
                                                    <input type="hidden" id="mhargaproduk-<?php echo $i;?>-harga_distributor" name="MHargaProduk[<?php echo $i;?>][harga_distributor]" class="form-control money-format" value='0'>
                                                    <input type="hidden" id="mhargaproduk-<?php echo $i;?>-harga_agent" name="MHargaProduk[<?php echo $i;?>][harga_agent]" class="form-control money-format" value='0'>

                                                <?php
                                                        $total += $harga_enduser;
                                                        $i++;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan="3" class="text-right" style='padding-top: 15px;'>Total</td>
                                                    <td><input type="text" name="total" id="total" class="form-control text-right money-format" value="<?php echo $total;?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
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

$sql_tanggalx = "select distinct(to_char(a.harga_tanggal_penetapan, 'DD/MM/YYYY')) as harga_tanggal_penetapan, b.produk_group ".
                    "   from m_harga_produk a  ".
                    "   left join m_brg_produk b on b.produk_id = a.produk_id ".
                    "   where b.produk_group = '".$jp."' ".
                    "   and (a.status_approval = 'Not Confirmed' or a.status_approval = 'APPROVED' ) ".
                    "   and a.harga_tanggal_penetapan != now()::date " .
                    "   ";
$query_tanggalx = Yii::$app->db->createCommand($sql_tanggalx)->queryAll();

$i = 0;
$len = count($query_tanggalx);
$today = date('01/01/2020');
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
    setMenuActive('".json_encode(MMenu::getMenuByCurrentURL('Harga / Tarif Produk'))."');
    var datesForDisable = $tanggalx;

    var d = new Date();
    var month = d.getMonth()+1;
    var day = d.getDate();
    var output = ((''+day).length<2 ? '0' : '') + day + '/' + ((''+month).length<2 ? '0' : '') + month + '/' + d.getFullYear();

    $('#tanggalx').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        datesDisabled: datesForDisable,
        startDate: output
    });

    $(':input').bind('keyup change', function(e) {
        var sum = 0;
        $('.harga_endusers').each(function() {
            var jumlah = $(this).val();
            var total = jumlah.replace(/[^0-9.-]+/g,'')
            sum += Number(total);
        });
        $('#total').val(sum);
    })
    
    $( '#btn-cancel' ).click(function() {

    });    
    
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
                url    : '<?= Url::toRoute(['/marketing/pricelist/setPrice']); ?>',
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
        // getContent();
    <?php } ?>
}

function setDisabledDatepicker(par){
    if(par){
        $('#harga_tanggal_penetapan_picker').prop('enabled',true);
        $('#harga_tanggal_penetapan_picker').siblings().show();
    }else{
        $('#harga_tanggal_penetapan_picker').prop('enabled',false);
        $('#harga_tanggal_penetapan_picker').siblings().show();
    }
}

// sini cuuuy
function setHargaBaru(){
    var total_produk = 0;
    var harga_tanggal_penetapan = $('#harga_tanggal_penetapan').val();
    var jp = $('#mbrgproduk-produk_group').val();

    $('#table-pricelist tbody tr').each(function(index){
        var produk_id = $(this).find('input[name*="produk_id"]').val();
        if(produk_id){
            total_produk = total_produk+1;
        }
    });

    // setting awal mulai tanggal enable disini dul
    if(total_produk > 0){
        var besok = new Date();
        besok.setDate(besok.getDate() + 0);
        besok = besok.toString('dd/MM/yyyy');
        $('#harga_tanggal_penetapan_picker').val(besok);
        $('#btn-save').show();
        $('#btn-cancel').show();
        $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').prop('disabled',true);
        $(".bs-select").selectpicker("refresh");
        $('#date-filter-editmode').show();
        $('#table-pricelist tbody tr').each(function(index){
            $(this).find('input[name*="[harga_hpp]"]').show();
            $(this).find('input[name*="[harga_distributor]"]').show();
            $(this).find('input[name*="[harga_agent]"]').show();
            $(this).find('input[name*="[harga_enduser]"]').show();
        });
        $('.money-format').maskMoney({'symbol':'','defaultZero':true,'allowZero':true,'decimal':'.','thousands':',','precision':0});
        setPrice($('#harga_tanggal_penetapan_picker').val());
    }else { 
        var jenis_produk = $("#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>").val();
        cisAlert("Produk untuk "+jenis_produk+" belum ditambahkan, tambahkan terlebih dahulu dimaster produk");
        return false;
    }
}

function hitungTotal() {
    $('.harga_endusers').change(function () {
        //alert('xxx');
    });
}

</script>
