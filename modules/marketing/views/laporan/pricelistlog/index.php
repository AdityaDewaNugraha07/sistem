<?php
/* @var $this yii\web\View */
$this->title = 'Master Harga / Tarif Log';
app\assets\DatatableAsset::register($this);
app\assets\BootstrapSelectAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Harga / Tarif Log'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-tags"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Price List Log'); ?></span>
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
                                        <div class="col-md-7 col-sm-12">
                                            <div id="table-grade_filter" class="dataTables_filter visible-lg visible-md form-horizontal" style="width: 100%; margin-top: -7px; margin-bottom: 15px;">
                                                <div class="form-group" id="filter-dropdown">
                                                    <div class="col-md-8" id="date-filter">
                                                        <?= yii\bootstrap\Html::dropDownList(
                                                            'harga_tanggal_penetapan',
                                                            '',
                                                            [],[
                                                                'class'=>'form-control bs-select',
                                                                'data-style'=>'blue-hoki btn-outline',
                                                                'id'=>'harga_tanggal_penetapan',
                                                                'onchange'=>"getContentLog()"
                                                            ]
                                                        ); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-hover" id="table-pricelist">
                                            <thead>
                                                <tr>
                                                    <th class='td-kecil' style="width: 50px;">No. </th>
                                                    <th class='td-kecil' style="width: 120px;"><?= Yii::t('app', 'Kode Log') ?></th>
                                                    <th class='td-kecil' style="width: 300px;"><?= Yii::t('app', 'Nama Log') ?></th>
                                                    <th class='td-kecil' style="width: 160px;"><?= Yii::t('app', 'Range Dimensi') ?></th>
                                                    <th class='td-kecil' style="width: 140px;"><?= Yii::t('app', 'Harga<br>Sebelumnya') ?></th>
                                                    <th class='td-kecil' style="width: 140px;"><?= Yii::t('app', 'Harga<br>End USer') ?></th>
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
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Pricelist Log'))."');
setTglDropdownLog();
formconfig();
", yii\web\View::POS_READY); ?>
<?php
$this->registerCss("
#table-pricelist thead tr th{
    text-align : center;
}
");
?>
<script>
function setTglDropdownLog() {
    $.ajax({
        url: '<?= \yii\helpers\Url::toRoute(['/marketing/laporan/setTglDropdownLog']); ?>',
        type: "POST",
        data: {selected: '<?= isset($_GET['tp'])? $_GET['tp']: null ?>'},
        success: function(data) {
            $("#harga_tanggal_penetapan").html(data.html);
            $(".bs-select").selectpicker("refresh");
            let tgl = $("#harga_tanggal_penetapan").val();
            getContentLog(tgl);
        },
        error: function(err) {
            getdefaultajaxerrorresponse(err);
        }
    });

    return true;
}

function getContentLog(tgl=null){
    setTimeout(function(){
        if(tgl == null){
            var tgl = $('#harga_tanggal_penetapan').val();
        }
        let kode    = $("#harga_tanggal_penetapan option:selected").text().split(' ').pop();
        $('#table-pricelist tbody').html("");
        $("#table-pricelist tbody").load('<?= \yii\helpers\Url::toRoute(['/marketing/laporan/getContentLog']); ?>?kode='+kode, function() {});
    },300);
}




</script>