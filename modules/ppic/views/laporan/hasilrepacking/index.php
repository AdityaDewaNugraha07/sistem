<?php
/* @var $this yii\web\View */

use app\models\THasilRepacking;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Hasil Repacking');
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?= Yii::t('app', 'Hasil Repacking & Penanganan Barang Retur') ?> <small>( <?= Yii::t('app', 'Pengiriman Hasil Repacking Ke Gudang')?> )</small></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <?= /** @var THasilRepacking $model */
                $this->render('_search', ['model' => $model]) ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold">
                                        <h4><?= Yii::t('app', 'Status Penerimaan Gudang')?></h4>
                                    </span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-informasi">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th style="width: 110px; line-height: 1"><?= Yii::t('app', 'Kode') ?> / <br> <?= Yii::t('app', 'Tanggal')?></th>
                                                    <th><?= Yii::t('app', 'Jenis Produk') ?></th>
                                                    <th><?= Yii::t('app', 'KBJ') ?> / <?= Yii::t('app','Nama Produk')?></th>
                                                    <th></th>
                                                    <th style="width: 50px;"><?= Yii::t('app', 'Qty') ?></th>
                                                    <th style="width: 60px;"><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                                    <th style="width: 50px; line-height: 1"><?= Yii::t('app', 'Diserahkan{br}Oleh', ['br' => '<br>']) ?></th>
                                                    <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Penerimaan{br}Gudang', ['br' => '<br>']) ?></th>
                                                    <th></th>
                                                    <th style="width: 60px; line-height: 1"><?= Yii::t('app', 'Lokasi{br}Gudang', ['br' => '<br>']) ?></th>
                                                    <th style="width: 50px; line-height: 1"><?= Yii::t('app', 'Diterima{br}Oleh', ['br' => '<br>']) ?></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs(" 
    formconfig();
    changePertanggalLabel();
    $('#form-search-laporan').submit(function(){
        afterSave();
        return false;
    });
    afterSave();
    setFilterByProdukGroup();
    
", yii\web\View::POS_READY); ?>
<script>

function afterSave() {
    $('#table-informasi').dataTable({
        ajax: {
            url: '<?= Url::toRoute('/ppic/laporan/HasilRepackingAll') ?>',
            data: {
                dt : 'table-informasi',
                laporan_params : $("#form-search-laporan").serialize()
            },
        },
        order: [
            // [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 1, class:"text-align-left td-kecil",
                render: ( data, type, full ) => `${full[12]} <br><b> ${data} </b><br> ${new Date(full[2]).toString('dd/MM/yyyy')}`
            },
            { 	targets: 2, class:"text-align-left td-kecil",
                render: ( data, type, full ) => full[12]
            },
            { 	targets: 3, class:"text-align-left td-kecil",
                render: ( data, type, full ) => `<b> ${data} </b><br> ${full[4]}`
            },
            { 	targets: 4, visible:false },
            { 	targets: 5, class:"text-align-center td-kecil" },
            { 	targets: 6, class:"text-align-right td-kecil" },
            { 	targets: 7, class:"text-align-left td-kecil2" },
            { 	targets: 8, class:"text-align-left td-kecil" },
            { 	targets: 9, visible:false },
            { 	targets: 10, class:"text-align-center td-kecil" },
            { 	targets: 11, class:"text-align-left td-kecil2" },
            { 	targets: 12, visible: false },
        ],
        "autoWidth": false,
        "drawCallback": function( oSettings ) {
            formattingDatatableReport(oSettings.sTableId);
            changePertanggalLabel()
            mergeSameValue();
        },
        "bDestroy": true
    });
}

function mergeSameValue(){
    let arr = [];
    let coll = [0];
    $("#table-informasi").find('tr').each(function () {
		$(this).find('td').each(function (d, td) {
            if ( coll.indexOf(d) !== -1) {
                let $td = $(td);
                let v_dato = $td.html();
                if(typeof arr[d] != 'undefined' && 'dato' in arr[d] && arr[d].dato === v_dato) {
                    let rs = arr[d].elem.data('rowspan');
                    if(rs === 'undefined' || isNaN(rs)) rs = 1;
					arr[d].elem.data('rowspan', parseInt(rs) + 1).addClass('rowspan-combine');
					$td.addClass('rowspan-remove');
				} else {
					arr[d] = {dato: v_dato, elem: $td};
				}
			}
		});
	});
	$('.rowspan-combine').each(function () {
        let $this = $(this);
        $this.attr('rowspan', $this.data('rowspan')).css({'vertical-align': 'middle'});
	});
	$('.rowspan-remove').remove();
}

function lihatDetail(id){
    window.location.replace('<?= Url::toRoute(['/ppic/pengajuanrepacking/index','pengajuan_repacking_id'=>'']) ?>'+id);
}

function infoKembaligudang(id){
    openModal('<?= Url::toRoute(['/ppic/pengajuanrepacking/infoKembaligudang','id'=>'']) ?>'+id,'modal-info','85%');
}
function infoPalet(nomor_produksi){
	openModal('<?= Url::toRoute(['/marketing/spm/infoPalet','nomor_produksi'=>'']) ?>'+nomor_produksi,'modal-info-palet','90%');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal')?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal')?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir')?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/ppic/laporan/HasilRepackingAllPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function setFilterByProdukGroup(callback=null){
    let jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
    $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:none");
    $("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:none");
    $("#<?= yii\bootstrap\Html::getInputId($model, "glue") ?>").parents(".form-group").attr("style","display:none");
    $("#<?= yii\bootstrap\Html::getInputId($model, "profil_kayu") ?>").parents(".form-group").attr("style","display:none");
    $("#<?= yii\bootstrap\Html::getInputId($model, "kondisi_kayu") ?>").parents(".form-group").attr("style","display:none");
    setDropdownJenisKayu(function(){
        setDropdownGrade(function(){
            setDropdownGlue(function(){
                setDropdownProfilKayu(function(){
                    setDropdownKondisiKayu(function(){
                        if(callback){ callback(); }
                    });
                });
            });
        });
    });
    if(jenis_produk === "Plywood" || jenis_produk === "Lamineboard" || jenis_produk === "Platform"){
        $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:");
        $("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
        $("#<?= yii\bootstrap\Html::getInputId($model, "glue") ?>").parents(".form-group").attr("style","display:");
    }
    if(jenis_produk === "Sawntimber"){
        $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:");
        $("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
        $("#<?= yii\bootstrap\Html::getInputId($model, "kondisi_kayu") ?>").parents(".form-group").attr("style","display:");
    }
    if(jenis_produk === "Moulding"){
        $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:");
        $("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
        $("#<?= yii\bootstrap\Html::getInputId($model, "profil_kayu") ?>").parents(".form-group").attr("style","display:");
    }
    if(jenis_produk === "Veneer"){
        $("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
    }
}

function setDropdownJenisKayu(callback = null){
    let jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
    $("#<?= yii\bootstrap\Html::getInputId($model, 'jenis_kayu') ?>").html("");
    $.ajax({
        url    : '<?= Url::toRoute(['/ppic/produk/setDropdownJenisKayu']) ?>',
        type   : 'POST',
        data   : {jenis_produk:jenis_produk},
        success: function (data) {
            if(data.html){
                $("#<?= yii\bootstrap\Html::getInputId($model, 'jenis_kayu') ?>").html(data.html);
                //$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").prepend('<option value="" selected="selected">All</option>');
            }
            if(callback){ callback(); }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setDropdownGrade(callback=null){
    let jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'jenis_produk') ?>').val();
    $.ajax({
        url    : '<?= Url::toRoute(['/ppic/produk/setDropdownGrade']) ?>',
        type   : 'POST',
        data   : {jenis_produk:jenis_produk},
        success: function (data) {
            if(data.html){
                $("#<?= yii\bootstrap\Html::getInputId($model, 'grade') ?>").html(data.html);
                //$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").prepend('<option value="" selected="selected">All</option>');
            }
            if(callback){ callback(); }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setDropdownGlue(callback=null){
    let jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
    $("#<?= yii\bootstrap\Html::getInputId($model, 'glue') ?>").html("");
    $.ajax({
        url    : '<?= Url::toRoute(['/ppic/produk/setDropdownGlue']) ?>',
        type   : 'POST',
        data   : {jenis_produk:jenis_produk},
        success: function (data) {
            if(data.html){
                $("#<?= yii\bootstrap\Html::getInputId($model, 'glue') ?>").html(data.html);
                //$("#<?= yii\bootstrap\Html::getInputId($model, "glue") ?>").prepend('<option value="" selected="selected">All</option>');
            }
            if(callback){ callback(); }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setDropdownProfilKayu(callback=null){
    let jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
    $("#<?= yii\bootstrap\Html::getInputId($model, 'profil_kayu') ?>").html("");
    $.ajax({
        url    : '<?= Url::toRoute(['/ppic/produk/setDropdownProfilKayu']) ?>',
        type   : 'POST',
        data   : {jenis_produk:jenis_produk},
        success: function (data) {
            if(data.html){
                $("#<?= yii\bootstrap\Html::getInputId($model, 'profil_kayu') ?>").html(data.html);
                //$("#<?= yii\bootstrap\Html::getInputId($model, "profil_kayu") ?>").prepend('<option value="" selected="selected">All</option>');
            }
            if(callback){ callback(); }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setDropdownKondisiKayu(callback=null){
    let jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
    $("#<?= yii\bootstrap\Html::getInputId($model, 'kondisi_kayu') ?>").html("");
    $.ajax({
        url    : '<?= Url::toRoute(['/ppic/produk/setDropdownKondisiKayu']) ?>',
        type   : 'POST',
        data   : {jenis_produk:jenis_produk},
        success: function (data) {
            if(data.html){
                $("#<?= yii\bootstrap\Html::getInputId($model, 'kondisi_kayu') ?>").html(data.html);
                //$("#<?= yii\bootstrap\Html::getInputId($model, "kondisi_kayu") ?>").prepend('<option value="" selected="selected">All</option>');
            }
            if(callback){ callback(); }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
</script>