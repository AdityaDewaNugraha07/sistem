<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Data Correction';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\RepeaterAsset::register($this);
$xxx = explode("/",$model->tanggal);
$tanggal_manipulasi = new DateTime($xxx[1]."/".$xxx[0]."/".$xxx[2]);
$tanggal_berlaku    = new DateTime("10/01/2021");

?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Data Correction'); ?></h1>
<!-- END PAGE TITLE -->
<!-- END PAGE HEADER -->
<!-- BEGIN EXAMPLE TABLE PORTLET -->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <span class="pull-right">
                            <a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Riwayat Pengajuan'); ?></a> 
                        </span>
                    </div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject"><h4><?= Yii::t('app', 'Pengajuan Koreksi Data'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, "pengajuan_manipulasi_id") ?>
                                        <?php 
                                            if(!isset($_GET['pengajuan_manipulasi_id'])){
                                                    echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Kode Pengajuan");
                                            }else{ ?>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                                                        <div class="col-md-7" style="padding-bottom: 5px;">
                                                            <span class="input-group-btn" style="width: 90%">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
                                                            </span>
                                                            <span class="input-group-btn" style="width: 10%">
                                                            <a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
                                                                <i class="icon-paper-clip"></i>
                                                            </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                    <?php   } ?>
                                        <?= $form->field($model, "tanggal")->textInput(['disabled'=>true])->label("Tanggal Pengajuan") ?>
                                        <?= $form->field($model, "departement_id")->dropDownList( app\models\MDepartement::getOptionList(),['disabled'=>true,'prompt'=>''])->label("Departement Pemohon"); ?>
                                        <?php
                                        $tipe = [];
                                        if($model->departement_id == \app\components\Params::DEPARTEMENT_ID_MARKETING || Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER ){
                                            $tipe = ["KOREKSI HARGA JUAL"=>"KOREKSI HARGA JUAL",
                                                     "KOREKSI NOPOL MOBIL"=>"KOREKSI NOPOL MOBIL",
                                                     "KOREKSI ALAMAT BONGKAR"=>"KOREKSI ALAMAT BONGKAR",
                                                     "POTONGAN PIUTANG"=>"POTONGAN PIUTANG",
                                                     "KOREKSI PIUTANG LOG & JASA"=>"KOREKSI PIUTANG LOG & JASA"
                                                    ];
                                        }
                                        ?>
                                        <?= $form->field($model, "tipe")->dropDownList($tipe,["prompt"=>"",'onchange'=>'getReff()'])->label("Jenis Pengajuan "); ?>
                                        
                                        <div id="place-reff-no"></div>
                                        <div id="place-berkas-reff"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo Html::activeHiddenInput($model, "approver1") ?>
                                        <?php echo Html::activeHiddenInput($model, "approver2") ?>
                                        <?php echo Html::activeHiddenInput($model, "approver3") ?>
                                        <?php echo Html::activeHiddenInput($model, "approver4") ?>
                                        <?php echo $form->field($model, 'approver1_display')->textInput(['class'=>'form-control','disabled'=>true])->label("Approver 1"); ?>
                                        <?php echo $form->field($model, 'approver2_display')->textInput(['class'=>'form-control','disabled'=>true])->label("Approver 2"); ?>
                                        <?php echo $form->field($model, 'approver3_display')->textInput(['class'=>'form-control','disabled'=>true])->label("Approver 3"); ?>
                                        <?php
                                        if ($tanggal_manipulasi >= $tanggal_berlaku) {
                                            echo $form->field($model, 'approver4_display')->textInput(['class'=>'form-control','disabled'=>true])->label("Approver 4");
                                        }
                                        ?>
                                        <?php echo $form->field($model, 'priority')->dropDownList(["NORMAL"=>"NORMAL","SEGERA"=>"SEGERA"],['class'=>'form-control']); ?>
                                        <?php echo $form->field($model, 'reason')->textarea()->label("Alasan Pengajuan Koreksi"); ?>
                                    </div>
                                </div>
                                <br><br>
                                <div id="place-koreksi-data">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save()']); ?>
                                <?php 
                                if (isset($model->pengajuan_manipulasi_id)) {
                                    $model->approver1 != '' ? $approver1 = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$model->approver1."")->queryScalar() : $approver1 = '';
                                    $model->approver2 != '' ? $approver2 = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$model->approver2."")->queryScalar() : $approver2 = '';
                                    $model->approver3 != '' ? $approver3 = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$model->approver3."")->queryScalar() : $approver3 = '';
                                    $model->approver4 != '' ? $approver4 = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$model->approver4."")->queryScalar() : $approver4 = '';
                                    $model->approver5 != '' ? $approver5 = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$model->approver5."")->queryScalar() : $approver5 = '';
                                    
                                    $approver = "";
                                    $jml_approver = 0;
                                    for ($i=1;$i<=5;$i++) { 
                                        $approver = "approver".$i;
                                        if ($$approver != '') {
                                            $jml_approver++;
                                        }
                                    }

                                    $nc = 0;
                                    for ($i=$jml_approver;$i>=1;$i--) {
                                        $approver = "approver".$i;
                                        $sql_tanggal_jam = "select updated_at from t_approval where reff_no = '".$model->kode."' and assigned_to = ".$model->$approver."";
                                        $tanggal_jam = \app\components\DeltaFormatter::formatDateTimeForUser(Yii::$app->db->createCommand($sql_tanggal_jam)->queryScalar());
                                        $sql_status = "select status from t_approval where reff_no = '".$model->kode."' and assigned_to = ".$model->$approver;
                                        $status = Yii::$app->db->createCommand($sql_status)->queryScalar();
                                        if ($status == "Not Confirmed") {
                                            $nc = $nc + 1;
                                        }
                                    }

                                    if ($nc == 0) {
                                        echo Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'printDK('.$model->pengajuan_manipulasi_id.')']);
                                    }
                                }
                                ?>
                                <?php echo Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['pengajuan_manipulasi_id'])){
    if(isset($_GET['edit'])){
        $pagemode = "afterSave(".$_GET['pengajuan_manipulasi_id'].",1);";
    }else{
        $pagemode = "afterSave(".$_GET['pengajuan_manipulasi_id'].");";
    }
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
	$('.date-picker').datepicker({ clearBtn:false });
	formconfig();
    $pagemode;
", yii\web\View::POS_READY); ?>
<script>
function getReff(){
    var pengajuan_manipulasi_id = $("#<?= yii\helpers\Html::getInputId($model, "pengajuan_manipulasi_id") ?>").val();
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    $("#<?= yii\bootstrap\Html::getInputId($model, "pengajuan_manipulasi_id") ?>").html("");
    $("#place-reff-no").html("");
    $("#place-berkas-reff").html("");
    $("#place-approver").html("");
    $("#<?= Html::getInputId($model, "approver1") ?>").val("");
    $("#<?= Html::getInputId($model, "approver2") ?>").val("");
    $("#<?= Html::getInputId($model, "approver3") ?>").val("");
    $("#<?= Html::getInputId($model, "approver4") ?>").val("");
    $("#<?= Html::getInputId($model, "approver1_display") ?>").val("");
    $("#<?= Html::getInputId($model, "approver2_display") ?>").val("");
    $("#<?= Html::getInputId($model, "approver3_display") ?>").val("");
    $("#<?= Html::getInputId($model, "approver4_display") ?>").val("");
    $("#place-koreksi-data").html("");
    
    if(tipe){
        $.ajax({
            url    : '<?= Url::toRoute(['/sysadmin/datacorrection/getReff']); ?>',
            type   : 'POST',
            data   : {tipe:tipe,pengajuan_manipulasi_id:pengajuan_manipulasi_id},
            success: function (data) {
                
                if(data.html_reff){
                    $("#place-reff-no").html(data.html_reff);
                    $("#place-reff-no").find(".tooltips").tooltip({ delay: 50 });
                }
                
                if(tipe == "KOREKSI HARGA JUAL" || tipe == "KOREKSI NOPOL MOBIL" || tipe == "KOREKSI ALAMAT BONGKAR" || tipe == "POTONGAN PIUTANG"){
                    
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}

function openNota(){
    openModal('<?= Url::toRoute(['/sysadmin/datacorrection/opennota','pick'=>'1']) ?>','modal-open-nota','90%');
}

function openLogjasa(){
    openModal('<?= Url::toRoute(['/sysadmin/datacorrection/openlogjasa','pick'=>'1']) ?>','modal-open-logjasa','90%');
}

function pick(id,kode){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    
    $('#<?= yii\bootstrap\Html::getInputId($model, "pengajuan_manipulasi_id") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "reff_no") ?>').val("");
    $("#place-berkas-reff").html("");
    $("#place-approver").html("");
    $("#<?= Html::getInputId($model, "approver1") ?>").val("");
    $("#<?= Html::getInputId($model, "approver2") ?>").val("");
    $("#<?= Html::getInputId($model, "approver3") ?>").val("");
    $("#<?= Html::getInputId($model, "approver4") ?>").val("");
    
    $("#<?= Html::getInputId($model, "approver1_display") ?>").val("");
    $("#<?= Html::getInputId($model, "approver2_display") ?>").val("");
    $("#<?= Html::getInputId($model, "approver3_display") ?>").val("");
    $("#<?= Html::getInputId($model, "approver4_display") ?>").val("");
    
    $("#place-koreksi-data").html("");
    
    $.ajax({
        url    : '<?= Url::toRoute(['/sysadmin/datacorrection/pickNota']); ?>',
        type   : 'POST',
        data   : {tipe:tipe,id:id,kode:kode,pengajuan_manipulasi_id:'<?= $model->pengajuan_manipulasi_id ?>'},
        success: function (data) {
            $("#modal-open-nota").find('button.fa-close').trigger('click');
            $("#modal-open-logjasa").find('button.fa-close').trigger('click');
            $("#<?= yii\bootstrap\Html::getInputId($model, "reff_no") ?>").val( kode );
            
            if(data.html_berkas_reff){
                $("#place-berkas-reff").html(data.html_berkas_reff);
            }
            $("#<?= Html::getInputId($model, "approver1") ?>").val(data.approver1);
            $("#<?= Html::getInputId($model, "approver2") ?>").val(data.approver2);
            $("#<?= Html::getInputId($model, "approver3") ?>").val(data.approver3);
            $("#<?= Html::getInputId($model, "approver4") ?>").val(data.approver4);
            
            $("#<?= Html::getInputId($model, "approver1_display") ?>").val(data.approver1_display);
            $("#<?= Html::getInputId($model, "approver2_display") ?>").val(data.approver2_display);
            $("#<?= Html::getInputId($model, "approver3_display") ?>").val(data.approver3_display);
            $("#<?= Html::getInputId($model, "approver4_display") ?>").val(data.approver4_display);
            if(data.html_koreksi){
                $("#place-koreksi-data").html(data.html_koreksi);
                if(tipe == "KOREKSI HARGA JUAL" || tipe == "KOREKSI NOPOL MOBIL" || tipe == "KOREKSI ALAMAT BONGKAR" || tipe == "POTONGAN PIUTANG"){
                    if(data.cust.cust_is_pkp){
                        $("#tnotapenjualan-cust_is_pkp").prop("checked",true);
                    }else{
                        $("#tnotapenjualan-cust_is_pkp").prop("checked",false);
                    }
                    if(tipe == "KOREKSI HARGA JUAL"){
                        subTotalNota();
                    }
                    if(tipe == "POTONGAN PIUTANG"){
                        totalPotongan();
                    }
                }
                if(tipe == "KOREKSI PIUTANG LOG & JASA"){
                    totalLogjasa();
                }
            }
            formconfig();
            reordertable("#table-koreksi");
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function subTotalNota(){
	var jnsproduk = $("#tnotapenjualan-jenis_produk").val();
	$("#table-koreksi tbody tr").each(function(){
		var qty_kecil = unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
		var harga = unformatNumber( $(this).find('input[name*="[harga_jual_baru]"]').val() );
		var kubikasi = unformatNumber( $(this).find('input[name*="[kubikasi]"]').val() );
		var subtotal = 0;
		if(jnsproduk == "Plywood" || jnsproduk == "Lamineboard" || jnsproduk == "Platform" || jnsproduk == "Limbah"){
			subtotal = formatNumberForUser0Digit(qty_kecil * harga);
		}else{
			subtotal = formatNumberForUser0Digit(kubikasi * harga);
		}
        var harga_jual_baru = formatNumberForUser2Digit(harga);
		var ppn = 0;
		if( $('#tnotapenjualan-cust_is_pkp').prop('checked') ){
			ppn = subtotal * 0.1;
		}
    	$(this).find('input[name*="[ppn]"]').val( ppn );
		$(this).find('input[name*="[harga_jual_baru]"]').val( formatNumberForUser2Digit(harga_jual_baru) );
        $(this).find('input[name*="[subtotal]"]').val( formatNumberForUser0Digit(subtotal) );

	});
	totalNota();
}

function totalNota(){
	var total_harga = 0;
	var total_ppn = 0;
    var total_potongan = unformatNumber( $("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>").val() );
	$("#table-koreksi tbody tr").each(function(){
		total_harga += unformatNumber( $(this).find('input[name*="[subtotal]"]').val() );
		total_ppn += unformatNumber( $(this).find('input[name*="[ppn]"]').val() );
	});
	var total_bayar = (total_harga + total_ppn) - total_potongan;   
	$("#place-koreksi-data").find("input[name*='total_harga']").val( formatNumberForUser0Digit(total_harga) );
	$("#place-koreksi-data").find("input[name*='total_ppn']").val( formatNumberForUser0Digit(total_ppn) );
	$("#place-koreksi-data").find("input[name*='total_potongan']").val( formatNumberForUser0Digit(total_potongan) );
	$("#place-koreksi-data").find("input[name*='total_bayar']").val( formatNumberForUser0Digit(total_bayar) );
}

function totalPotongan(){
    const tagihan = unformatNumber($("#tpiutangpenjualan-tagihan").val());
    const bayar = unformatNumber($("#tpiutangpenjualan-bayar").val());
    const sisa = tagihan - bayar;
    const domSisa = $('#tpiutangpenjualan-sisa');

    if(sisa < 0) {
        domSisa.parent().siblings().text('Pengembalian');
        domSisa.val( formatNumberForUser0Digit(Math.abs(sisa)) );
    }else {
        domSisa.parent().siblings().text('Sisa Piutang');
        domSisa.val( formatNumberForUser0Digit(sisa) );
    }


}

function subtotalLogjasa(ele){
    var sisa_bayar = unformatNumber( $(ele).parents("tr").find("input[name*='[sisa_bayar]']").val() );
    var potongan = unformatNumber( $(ele).parents("tr").find("input[name*='[potongan]']").val() );    
    $(ele).parents("tr").find("input[name*='[sisa_bayar_baru]']").val( formatNumberForUser0Digit(sisa_bayar-potongan) );
    totalLogjasa();
}

function totalLogjasa(){
	var potongan = 0;
	var sisa_bayar_baru = 0;
    
    $("#table-koreksi > tbody > tr.tr-isi").each(function(){
        potongan += unformatNumber( $(this).find("input[name*='[potongan]']").val() );
        sisa_bayar_baru += unformatNumber( $(this).find("input[name*='[sisa_bayar_baru]']").val() );
    });
    $("#tpiutangalert-potongan").val( formatNumberForUser0Digit(potongan) );
    $("#tpiutangalert-sisa_bayar_baru").val( formatNumberForUser0Digit(sisa_bayar_baru) );
}

function infoNota(kode){
	openModal('<?= Url::toRoute(['/marketing/notapenjualan/infoNota']) ?>?kode='+kode,'modal-info-nota','21.5cm');
}

function save(){
    var $form = $('#form-transaksi');
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    
    if(formrequiredvalidate($form)){
        if(tipe == "KOREKSI HARGA JUAL" || tipe == "KOREKSI NOPOL MOBIL" || tipe == "KOREKSI ALAMAT BONGKAR" || tipe == "POTONGAN PIUTANG"){
            var jumlah_item = $('#table-koreksi tbody tr').length;
        }else if(tipe == "KOREKSI PIUTANG LOG & JASA"){
            var jumlah_item = $('#table-koreksi tbody tr.tr-isi').length;
        }
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

function printDK(id){
    let tipe = $("#<?= Html::getInputId($model, "tipe") ?>").val();
    tipe = encodeURIComponent(tipe);
    window.open("<?= Url::toRoute('/sysadmin/datacorrection/printDK') ?>?id=" + id + "&caraprint=PRINT&tipe=" + tipe,"",'location=_new, width=1200px, scrollbars=yes');
}

function validatingDetail($form){
	var has_error = 0;
    //    var suplier_nm = $("#<?= yii\helpers\Html::getInputId($model, 'suplier_nm') ?>");
    //    if(!suplier_nm.val()){
    //        $("#<?= yii\helpers\Html::getInputId($model, 'suplier_nm') ?>").addClass('error-tb-detail');
    //        has_error = has_error + 1;
    //    }else{
    //        $("#<?= yii\helpers\Html::getInputId($model, 'suplier_nm') ?>").removeClass('error-tb-detail');
    //    }
    
	if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id,edit){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
	$('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
    $('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
    $('#btn-save').prop('disabled',true);
    getReff();
    pick(null,'<?= $model->reff_no ?>');
    
    if(tipe == "KOREKSI HARGA JUAL"){
        setTimeout(function(){
            $("#table-koreksi > tbody > tr").each(function(){
                $(this).find("input[name*='harga_jual_baru']").prop('disabled',true);
            });
        },1000);
    }else if(tipe == "KOREKSI NOPOL MOBIL"){
        setTimeout(function(){
            $("#TPengajuanManipulasi_0_new_nopol_baru").prop('disabled',true);
            $("#TPengajuanManipulasi_0_supir_new_kendaraan_supir_baru").prop('disabled',true);
        },1000);
    }else if(tipe == "KOREKSI ALAMAT BONGKAR"){
        setTimeout(function(){
            $("#TPengajuanManipulasi_0_new_alamat_bongkar_baru").prop('disabled',true);
        },1000);
    }else if(tipe == "POTONGAN PIUTANG"){
        setTimeout(function(){
            $("#tpiutangpenjualan-bayar").prop('disabled',true);
        },1000);
    }else if(tipe == "KOREKSI PIUTANG LOG & JASA"){
        setTimeout(function(){
            $("#table-koreksi > tbody > tr").each(function(){
                $(this).find("input[name*='[potongan]']").prop('disabled',true);
            });
        },1000);
    }
    
    setTimeout(function(){
        $("#btn-open-nota").hide();
    },1000);
    
    <?php if(isset($_GET['edit'])){ ?>
        $('form').find('input').each(function(){ $(this).prop("disabled", true); });
        $('form').find('select').each(function(){ $(this).prop("disabled", true); });
        $('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
        $('#btn-save').prop('disabled',false);
    <?php } ?>
}

function daftarAfterSave(){
    openModal('<?= Url::toRoute(['/sysadmin/datacorrection/daftarAfterSave']) ?>','modal-aftersave','90%');
}
</script>