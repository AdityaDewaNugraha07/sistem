<?php
/* @var $this yii\web\View */
$this->title = 'Master Limbah';
app\assets\DatatableAsset::register($this);
app\assets\BootstrapSelectAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Limbah'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <?php echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
                <ul class="nav nav-tabs">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/limbah/index"); ?>"> <?= Yii::t('app', 'Limbah'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/marketing/pricelistlimbah/index"); ?>"> <?= Yii::t('app', 'Price List'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption col-md-9 pull-left">
<!--                                    --><?php //= Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : '' ?>
<!--                                    --><?php //= Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : '' ?>
                                    <!-- <i class="fa fa-tags"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Price List Limbah'); ?></span> -->
                                    <span class="col-md-12 pull-left" id="status-approval" style="margin-top: -12px;"></span>
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
                                                    <div class="col-md-6" id="date-filter">
                                                        <?= yii\bootstrap\Html::dropDownList(
                                                            'harga_tanggal_penetapan',
                                                            '',
                                                            [],[
                                                                'class'=>'form-control bs-select',
                                                                'data-style'=>'blue-hoki btn-outline',
                                                                'id'=>'harga_tanggal_penetapan',
                                                                'onchange'=>"getContent('view')"
                                                            ]
                                                        ); ?>
                                                    </div>
                                                    <div class="col-md-5" style="display: none;" id="date-filter-editmode">
                                                        <div class="input-group date date-picker" data-date-start-date="+0d">
                                                            <?php $time_original = strtotime(date('Y-m-d')); ?>
                                                            <?= \yii\bootstrap\Html::textInput('harga_tanggal_penetapan',date("d/m/Y", ($time_original + (3600*24))),['class'=>'form-control','id'=>'harga_tanggal_penetapan_picker','onchange'=>'getContent("input");','readonly'=>'readonly']); ?>
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
                                                    <th style="width: 120px;"><?= Yii::t('app', 'Kode Limbah') ?></th>
                                                    <th style="width: 300px;"><?= Yii::t('app', 'Nama Limbah') ?></th>
                                                    <th style="width: 160px;"><?= Yii::t('app', 'Satuan') ?></th>
                                                    <th style="width: 140px;"><?= Yii::t('app', 'Harga') ?></th>
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
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Limbah'))."');
setTglDropdown();
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
function setTglDropdown() {
    $.ajax({
        url: '<?= \yii\helpers\Url::toRoute(['/marketing/pricelistlimbah/setTglDropdown']); ?>',
        type: "POST",
        data: {selected: '<?= isset($_GET['tp'])? $_GET['tp']: null ?>'},
        success: function(data) {
            $("#harga_tanggal_penetapan").html(data.html);
            $(".bs-select").selectpicker("refresh");
            let tgl = $("#harga_tanggal_penetapan").val();
            getContent('view', tgl);
        },
        error: function(err) {
            getdefaultajaxerrorresponse(err);
        }
    });

    return true;
}

function getContent(tipe,tgl=null){
    if(tgl == null){
        var tgl = $('#harga_tanggal_penetapan').val();
    }
    setTombolHapusEdit();
    // $('#table-pricelist tbody').html("");
    $('#table-pricelist tbody').html("<tr><td colspan='5' class='text-center'>Loading...</td></tr>");
    $.ajax({
        url    : '<?php echo \yii\helpers\Url::toRoute(['/marketing/pricelistlimbah/getContent']); ?>',
        type   : 'POST',
        data   : { tgl: tgl, tipe:tipe },
        success: function (data) {
            if(data.html){
                $("#table-pricelist tbody").html(data.html);
            }
            reordertable('#table-pricelist');
            setTombolHapusEdit();
            let kode        = $("#harga_tanggal_penetapan option:selected").text().split(' ').pop();
            $("#status-approval").load(`<?= \yii\helpers\Url::toRoute(['/marketing/pricelistlimbah/statusApproval']); ?>?tp=${tgl}&kode=${kode}`);
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
    // setTimeout(function(){
    // },300);
}



function setPrice(tgl_penetapan=null){
    if(tgl_penetapan == null){
        var tgl_penetapan = $('#harga_tanggal_penetapan').val();
    }
    if(tgl_penetapan){
        $('#table-pricelist tbody tr').each(function(index){
            var limbah_id = $(this).find('input[name*="limbah_id"]').val();
            var tr = $(this);
            tr.addClass('animation-loading');
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/marketing/pricelistlimbah/setPrice']); ?>',
                type   : 'POST',
                data   : {limbah_id:limbah_id,tgl_penetapan:tgl_penetapan},
                success: function (data) {
                    if(data.harga_enduser){
                        $(tr).find('label[id*="harga_enduser"]').html(data.harga_enduser_formatted);
                        $(tr).find('input[name*="harga_enduser"]').val(data.harga_enduser);
                    }else{
                        $(tr).find('label[id*="harga_enduser"]').html(0);
                        $(tr).find('input[name*="harga_enduser"]').val(0);
                    }
                    if(data.harga_keterangan){
                        $(tr).find('label[id*="harga_keterangan"]').html(data.harga_keterangan);
                        $(tr).find('input[name*="harga_keterangan"]').val(data.harga_keterangan);
                    }else{
                        $(tr).find('label[id*="harga_keterangan"]').html(' - ');
                        $(tr).find('input[name*="harga_keterangan"]').val('');
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
    $('#status-approval').hide();
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
	getContent('edit',selected_date);
}

function unsetUpdateForm(){
    $('#btn-save').hide();
    $('#btn-cancel').hide();
    $('#btn-set-price').show();
    $('.btn-icon-only.btn-default').show();
    $('#date-filter').show();
    $('#btn-new-price').show();
    $('#date-filter-editmode').hide();
    $('#status-approval').show();
    $('#table-pricelist tbody tr').each(function(index){
        $(this).find('label').show();
        $(this).find('input[name*="[harga_enduser]"]').hide();
        $(this).find('input[name*="[harga_keterangan]"]').hide();
    });
    getContent('view');
}

function setGetLoad(){
    <?php if(isset($_GET['tp'])){ ?>
        // $('#harga_tanggal_penetapan').val('<?= $_GET['tp'] ?>');
		$(".bs-select").selectpicker("refresh");
		getContent('view', '<?= $_GET['tp'] ?>');
    <?php }else{ ?>
        let tgl = $('#harga_tanggal_penetapan').val();
        getContent('view', tgl);
    <?php } ?>
}

function setTombolHapusEdit(){
    let tgl = $("#harga_tanggal_penetapan option:selected").text();
    if(tgl.includes('APPROVED')) {
        toggle(false);
    }else if(tgl.includes('Not Confirmed')) {
        toggle(true)
    }else if(tgl.includes('REJECTED')) {
        toggle(true)
    }else {
        toggle(false)
    }

    if($('#harga_tanggal_penetapan_picker').is(':visible')) {
        toggle(false);
    }

    function toggle(setup=false) {
        if(setup) {
            $('#btn-delete').show();
            $('#btn-set-price').show();
        }else {
            $('#btn-delete').hide();
            $('#btn-set-price').hide();
        }
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
    var tgl = new Date();
//	tgl.setDate(tgl.getDate() + 1);
	tgl.setDate(tgl.getDate());
	tgl = tgl.toString('dd/MM/yyyy');
	$('#harga_tanggal_penetapan_picker').val(tgl);
	$('#btn-save').show();
	$('#btn-cancel').show();
	$('#btn-set-price').hide();
	$('#btn-delete').hide();
	$('.btn-icon-only.btn-default').hide();
	$('#date-filter').hide();
	$('#btn-new-price').hide();
	$('#date-filter-editmode').show();
    $('#status-approval').hide();
	$('#table-pricelist tbody tr').each(function(index){
		$(this).find('label').hide();
		$(this).find('input[name*="[harga_enduser]"]').show();
		$(this).find('input[name*="[harga_keterangan]"]').show();
	});
    console.log(tgl)
	getContent('input',tgl);    
}

function hapusPricelist(){
    var tp = $('#harga_tanggal_penetapan').val();
    openModal('<?= \yii\helpers\Url::toRoute(['/marketing/pricelistlimbah/delete']) ?>?id='+tp,'modal-delete-record',null,'setTimeout(function(){ location.reload(); },1000);');
}

</script>