<?php
/* @var $this yii\web\View */
$this->title = 'Master Harga / Tarif';
app\assets\DatatableAsset::register($this);
app\assets\BootstrapSelectAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatepickerAsset::register($this);

use yii\helpers\Html;
isset($_GET['jp']) ? $jp = $_GET['jp'] : $jp = 'Platform';
isset($_GET['tp']) ? $tp = $_GET['tp'] : $tp = '';
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Harga / Tarif'); ?></h1>
<!-- END PAGE TITLE-->
<body onload="javascript: getContent(); setHargaBaru();">
<!-- END PAGE HEADER-->

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/pricelist/create"); ?>"> <?= Yii::t('app', 'Price List'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-tags"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Price List Produk'); ?> <?php echo " : ".$model->produk_group;?></span>
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
                                                    <div class="col-md-2" id="dropdown-filter">
                                                        <input id="mbrgproduk-produk_group" name="mbrgproduk-produk_group" value="<?php echo $jp;?>" class="md-col-3 form-control" readonly="readonly">
                                                    </div>
                                                    <div class="col-md-2">&nbsp;</div>
                                                    <div class="form-group">
                                                        <div class="date col-md-6" style="margin-left: -20px;" id="tanggalPenetapan">
                                                            <div class="input-group">
                                                                <input id="tanggalx" name="tanggalx" class="md-col-12 form-control" onkeydown="return false" placeholder="Tanggal Penetapan" value="<?php echo date('d/m/Y',strtotime($tp));?>" required="required">
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
                                            <?= Html::submitButton('Update', ['id'=>'btn-save', 'data-original-title'=>'Simpan Perubahan', 'class'=> 'btn hijau btn-outline ciptana-spin-btn ladda-button']) ?>
                                            <a class="btn red btn-outline ciptana-spin-btn ladda-button" id="btn-cancel" onclick="history.back();" data-original-title="Batalkan Perubahan"><i class="fa fa-remove"></i> <?= Yii::t('app', 'Cancel'); ?></a>
                                        </div>
                                    </div>
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-hover" id="table-pricelist">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px;">No. </th>
                                                    <th><?= Yii::t('app', 'Produk Nama') ?></th>
                                                    <th><?= Yii::t('app', 'Produk Kode') ?></th>
                                                    <th><?= Yii::t('app', 'Kode Dimensi') ?></th>
                                                    <th style="width: 140px;"><?= Yii::t('app', 'Harga End User') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <input type="hidden" name="kode_lama" id="kode_lama" value="<?php echo $kode;?>">
                                                <?php 
                                                $ii = 1;
                                                foreach ($modele as $i => $produk) {
                                                    $produk_id = $produk['produk_id'];
                                                    $produk_kode = $produk['produk_kode'];
                                                    $produk_dimensi = $produk['produk_dimensi'];
                                                    //$harga_enduser = $produk['harga_enduser'];
                                                    $sql_harga_enduser = "select harga_enduser ".
                                                                            "   from m_harga_produk ". 
                                                                            "   where produk_id = '".$produk_id."' ". 
                                                                            "   and harga_tanggal_penetapan = '".$tp."' ".
                                                                            "   ";
                                                    $harga_enduser = Yii::$app->db->createCommand($sql_harga_enduser)->queryScalar();
                                                    $harga_enduser > 0 ? $harga_enduser = $harga_enduser : $harga_enduser = 0;

                                                ?>
                                                <tr>
                                                    <td style="text-align:center">
                                                        <?= $ii; ?>
                                                        <?= \yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']produk_id',['value'=>$produk['produk_id']]) ?>
                                                    </td>
                                                    <td><?= $produk['produk_nama']; ?></td>
                                                    <td><?= $produk['produk_kode']; ?></td>
                                                    <td><?php echo $produk_dimensi;?></td>
                                                    <?= yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']harga_hpp', ['value'=>'0']); ?>
                                                    <?= yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']harga_distributor', ['value'=>'0']); ?>
                                                    <?= yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']harga_agent', ['value'=>'0']); ?>

                                                    <td style="text-align: right; padding-right: 15px;">
                                                        <?= yii\bootstrap\Html::activeTextInput($modHarga, '['.$i.']harga_enduser', ['class'=>'form-control money-format','value'=>$harga_enduser]); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $ii++;
                                                } 
                                                ?>
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

$sql_tanggalx = "select distinct(to_char(a.harga_tanggal_penetapan, 'DD/MM/YYYY')) as harga_tanggal_penetapan, b.produk_group ".
                    "   from m_harga_produk a  ".
                    "   left join m_brg_produk b on b.produk_id = a.produk_id ".
                    "   where b.produk_group = '".$jp."' ".
                    "   and (a.status_approval = 'Not Confirmed' or a.status_approval = 'APPROVED' ) ".
                    "   ";
$query_tanggalx = Yii::$app->db->createCommand($sql_tanggalx)->queryAll();

$i = 0;
$len = count($query_tanggalx);
$today = date('d-m-Y');
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
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Harga / Tarif'))."');
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

function setDisabledDatepicker(par){
    if(par){
        $('#harga_tanggal_penetapan_picker').prop('enabled',true);
        $('#harga_tanggal_penetapan_picker').siblings().show();
    }else{
        $('#harga_tanggal_penetapan_picker').prop('enabled',false);
        $('#harga_tanggal_penetapan_picker').siblings().show();
    }
}

function hitungTotal() {
    $('.harga_endusers').change(function () {
        //alert('xxx');
    });​​​​​​​​​     
}

</script>
