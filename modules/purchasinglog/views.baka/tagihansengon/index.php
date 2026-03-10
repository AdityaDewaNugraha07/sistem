<?php
/* @var $this yii\web\View */
$this->title = 'Tagihan Log Sengon/Jabon';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\RepeaterAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Tagihan Log Sengon/jabon'); ?></h1>
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
                            <a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Riwayat Tagihan'); ?></a> 
                        </span>
                    </div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject"><h4><?= Yii::t('app', 'Tagihan Log Sengon/Jabon'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php 
										if(!isset($_GET['tagihan_sengon_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Kode Tagihan");
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
										<?php } ?>
                                        <?= $form->field($model, 'tanggal',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime" data-date-end-date="+0d">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Tagihan"); ?>
                                        <?= $form->field($model, 'bayar_langsung',['template' => '{label}<div class="mt-checkbox-list col-md-7" style="margin-bottom:10px;"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                                                                    ->checkbox(['onchange'=>'bayarlangsung();'],false)->label(Yii::t('app', 'Bayar Langsung')); ?>
                                        <div class="form-group" id="place-kodepo">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode PO'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 5px;">
                                                <span class="input-group-btn" style="width: 95%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode_po', ['class'=>'form-control','style'=>'width:100%','disabled'=>true,'placeholder'=>'Cari PO Log Sengon']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 5%">
                                                    <button class="btn btn-icon-only btn-default tooltips" id="btn-caripo" data-original-title="Cari PO" onclick="openPOSengon();" type="button">
                                                        <i class="icon-magnifier"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group" id="place-kodeterima">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Terima'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 5px;">
                                                <span class="input-group-btn" style="width: 95%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode_terima', ['class'=>'form-control','style'=>'width:100%','disabled'=>true,'placeholder'=>'Cari Penerimaan Sengon']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 5%">
                                                    <button class="btn btn-icon-only btn-default tooltips" id="btn-cariterima" data-original-title="Cari Penerimaan" onclick="openPenerimaanSengon();" type="button">
                                                        <i class="icon-magnifier"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <?= $form->field($model, 'total_pcs')->textInput(['class'=>'form-control float','disabled'=>true])->label("Total Pcs"); ?>
                                        <?= $form->field($model, 'total_m3')->textInput(['class'=>'form-control float','disabled'=>true])->label("Total M<sup>3</sup>"); ?>
                                        
                                        <div class="form-group field-ttagihansengon-total_bayar">
                                            <label class="col-md-4 control-label" for="ttagihansengon-total_bayar">Total PPh</label>
                                            <div class="col-md-7"><input type="text" id="ttagihansengon-total_pph" class="form-control float" name="TTagihanSengon[total_pph]" value="0" disabled=""> 
                                            <span class="help-block"></span></div>
                                        </div>

                                        <?= $form->field($model, 'total_bayar')->textInput(['class'=>'form-control float','disabled'=>true])->label("Total Bayar"); ?>
                                        <div id="place-berkas-reff"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, "suplier_id") ?>
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, "posengon_id") ?>
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, "terima_sengon_id") ?>
                                        <?= $form->field($model, 'suplier_nm')->textInput(['disabled'=>true])->label("Suplier"); ?>
                                        <?= $form->field($model, 'suplier_almt')->textarea(['disabled'=>true])->label("Alamat"); ?>
                                        <div class="form-group" id="place-kodeterima">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'No Rekening'); ?></label>
                                            <div class="col-md-7" style="padding-bottom: 5px;">
                                                <span class="input-group-btn" style="width: 40%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'suplier_bank', ['class'=>'form-control','style'=>'width:100%','disabled'=>true]) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 60%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'suplier_norekening', ['class'=>'form-control','style'=>'width:100%','disabled'=>true]) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?= $form->field($model, 'suplier_an_rekening')->textInput(['disabled'=>true])->label("Rekening Atas Nama"); ?>
                                        <?= $form->field($model, 'suplier_npwp')->textInput(['disabled'=>true])->label("NPWP"); ?>
                                        <?= $form->field($model, 'cara_bayar')->dropDownList(app\models\MDefaultValue::getOptionListCustom("cara-bayar-voucher-penerimaan","'Cek','Bilyet Giro'","ASC"),['class'=>'form-control']); ?>
                                        <?= $form->field($model, 'reff_no')->textInput()->label("Reff No. (Nopol Truck)"); ?>
                                        <?= $form->field($model, 'reff_no2')->textInput()->label("No Invoice <i class='td-kecil'>(* Jika Ada)</i>"); ?>
                                    </div>
                                </div>
                                <br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Detail Perhitungan Sengon/Jabon '); ?><span id="place-judultable"></span></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                            <thead>
                                                <tr>
                                                    <th style="width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
                                                    <th style="line-height: 1"><?= Yii::t('app', 'Panjang / Wilayah'); ?></th>
                                                    <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Range Diameter<br>(Cm)'); ?></th>
                                                    <th style="width: 75px; line-height: 1"><?= Yii::t('app', 'Batang<br>(Pcs)'); ?></th>
                                                    <th style="width: 80px; line-height: 1"><?= Yii::t('app', 'Volume<br>(M<sup>3</sup>)'); ?></th>
                                                    <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Harga<br>(Rp)'); ?></th>
                                                    <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Subtotal<br>(Rp)'); ?></th>
                                                    <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'PPH <span id="place-labelpph"></span><br>(Rp)'); ?></th>
                                                    <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Jumlah Bayar<br>(Rp)'); ?></th>
                                                    <th style="width: 40px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="empty-tr"><td colspan="8" style="font-size: 1.1rem; text-align: center;"><i>Data Not Found</i></td></tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" style="text-align: right;"><a class="btn btn-xs blue pull-left" id="btn-add" onclick="addItem()"><i class="fa fa-plus"></i> Add Item</a> Total &nbsp; </td>
                                                    <td><?= yii\helpers\Html::textInput("total_pcs",0,["class"=>'form-control float col-m3-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                    <td><?= yii\helpers\Html::textInput("total_volume",0,["class"=>'form-control float col-m3-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                    <td style=""></td>
                                                    <td><?php echo yii\helpers\Html::textInput("total_harga",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                    <td><?php echo yii\helpers\Html::textInput("total_pph",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                    <td><?php echo yii\helpers\Html::textInput("total_bayar",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div><br>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save()']); ?>
                                <?php // echo \yii\helpers\Html::button( Yii::t('app', 'Print Rencana'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','disabled'=>true,'onclick'=>'printout()']); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
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
if(isset($_GET['tagihan_sengon_id'])){
    if(isset($_GET['edit'])){
        $pagemode = "afterSave(".$_GET['tagihan_sengon_id'].",1);";
    }else{
        $pagemode = "afterSave(".$_GET['tagihan_sengon_id'].");";
    }
}else{
	$pagemode = "addItem();";
}
?>
<?php $this->registerJs(" 
	$('.date-picker').datepicker({ clearBtn:false });
	formconfig();
    $pagemode;
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Tagihan Sengon'))."');
    bayarlangsung();
    setTombolReff();
", yii\web\View::POS_READY); ?>
<script>
function bayarlangsung(){
    if( $('#<?= yii\bootstrap\Html::getInputId($model, "bayar_langsung") ?>').prop('checked') ){
        $('#place-kodeterima').hide();
        $('#place-kodepo').show();
    }else{
        $('#place-kodeterima').show();
        $('#place-kodepo').hide();
    }
    <?php if(!isset($_GET['tagihan_sengon_id'])){ ?>
        setHeader();
    <?php } ?>
}
function openPOSengon(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/cariPoSengon','pick'=>'1']) ?>','modal-posengon','90%');
}
function pick(kode){
    if(kode){
        setHeader(kode,null);
    }
}
function openPenerimaanSengon(){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/terimasengon/openpenerimaan','pick'=>'1','pickingfrom'=>'purch']) ?>','modal-open-penerimaan','90%');
}
function pickPenerimaanSengon(terima_sengon_id,kode){
    if(kode){
        setHeader(null,kode);
    }
}

function setHeader(kode_po,kode_terima){
    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_id") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "posengon_id") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "kode_po") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "terima_sengon_id") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "kode_terima") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_nm") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_almt") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_bank") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_norekening") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_an_rekening") ?>').val("");
    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_npwp") ?>').val("");
    if(kode_po || kode_terima){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/tagihansengon/setHeader']); ?>',
            type   : 'POST',
            data   : { kode_terima:kode_terima,kode_po:kode_po },
            success: function (data){
                if(data){
                    if(data.terima){
                        var suplier_id = data.terima.suplier_id;
                        var posengon_id = data.terima.posengon_id;
                        var kode_po = data.po.kode+" - "+data.po.tanggal;
                        var terima_sengon_id = data.terima.terima_sengon_id;
                        var kode_terima = data.terima.kode+" - "+data.terima.tanggal;
                    }else{
                        var suplier_id = data.po.suplier_id;
                        var posengon_id = data.po.posengon_id;
                        var kode_po = data.po.kode+" - "+data.po.tanggal;
                        var terima_sengon_id = '';
                        var kode_terima = '';
                    }
                    
                    var suplier_nm = data.suplier.suplier_nm;
                    var suplier_almt = data.suplier.suplier_almt;
                    var suplier_bank = data.suplier.suplier_bank;
                    var suplier_norekening = data.suplier.suplier_norekening;
                    var suplier_an_rekening = data.suplier.suplier_an_rekening;
                    var suplier_npwp = data.suplier.suplier_npwp;
                    
                    $("#modal-posengon").find('button.fa-close').trigger('click');
                    $("#modal-open-penerimaan").find('button.fa-close').trigger('click');
                    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_id") ?>').val(suplier_id);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "posengon_id") ?>').val(posengon_id);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "kode_po") ?>').val(kode_po);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "terima_sengon_id") ?>').val(terima_sengon_id);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "kode_terima") ?>').val(kode_terima);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_nm") ?>').val(suplier_nm);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_almt") ?>').val(suplier_almt);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_bank") ?>').val(suplier_bank);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_norekening") ?>').val(suplier_norekening);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_an_rekening") ?>').val(suplier_an_rekening);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "suplier_npwp") ?>').val(suplier_npwp);
                    aktifkanBtnReferensi(posengon_id,suplier_id,terima_sengon_id);
                    setTotal();
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}

function aktifkanBtnReferensi(posengon_id,suplier_id,terima_sengon_id){
    setTombolReff(function(){
        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple').attr('onclick','detailPo("'+posengon_id+'")');
        $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+suplier_id+'")');
        if(terima_sengon_id){
            $("#place-berkas-reff").find('#btn-reff-3').removeClass('grey').addClass('green-seagreen').attr('onclick','detailTerima('+terima_sengon_id+')');
        }
    });
}

function setTombolReff(callback){
    $("#place-berkas-reff").html("");
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/SetDDPenerima']); ?>',
        type   : 'POST',
        data   : {tipe:"PELUNASAN LOG SENGON"},
        success: function (data) {
            if(data.html_berkas_reff){
                $("#place-berkas-reff").html(data.html_berkas_reff);
            }
            if( callback ){ callback(); }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function detailPo(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/detailPo','id'=>'']) ?>'+id,'modal-detailpo','22cm');
}
function riwayatSaldoSuplierSengon(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/saldosuplierlog/riwayatSaldo','id'=>'']) ?>'+id,'modal-riwayatsaldo','80%');
}
function detailTerima(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/ppic/terimasengon/detailrekap','id'=>'']) ?>'+id,'modal-detailterima','90%');
}

function addItem(){
    $("#empty-tr").remove();
    var allowadd = true;
    if(allowadd){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/tagihansengon/addItem']); ?>',
            type   : 'POST',
            data   : {},
            success: function (data){
                if(data.html){
                    $(data.html).hide().appendTo('#table-detail > tbody').fadeIn(100,function(){
                        $(this).find(".tooltips").tooltip({ delay: 50 });
                        formconfig(); reordertable("#table-detail"); 
                    });
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}

function setTotal(){
    var ada_npwp = $("#<?= yii\helpers\Html::getInputId($model, "suplier_npwp") ?>").val();
    $("input[name*='total_pcs']").val( 0 );
    $("input[name*='total_volume']").val( 0 );
    $("input[name*='total_harga']").val( 0 );
    $("input[name*='total_pph']").val( 0 );
    $("input[name*='total_bayar']").val( 0 );
    
    $("#<?= yii\helpers\Html::getInputId($model, "total_pcs") ?>").val( 0 );
    $("#<?= yii\helpers\Html::getInputId($model, "total_m3") ?>").val( 0 );
    $("#<?= yii\helpers\Html::getInputId($model, "total_bayar") ?>").val( 0 );
    
    var total_pcs = 0;
    var total_m3 = 0;
    var total_harga = 0;
    var total_pph = 0;
    var total_bayar = 0;
    $("#table-detail > tbody > tr").each(function(){
        var pcs = unformatNumber( $(this).find('input[name*="[pcs]"]').val() );
        var harga = unformatNumber( $(this).find('input[name*="[harga]"]').val() );
        var m3 = unformatNumber( $(this).find('input[name*="[m3]"]').val() );
        var subtotal = harga * m3;
        if(ada_npwp){
            var pph = 0.0025 * subtotal; // NPWP
            $("#place-labelpph").html("0.25%");
        }else{
            var pph = 0.005 * subtotal; // Tanpa NPWP
            $("#place-labelpph").html("0.5%");
        }
        //var bayar = Math.round(subtotal) - Math.round(pph); // Pembulatan awal
        var bayar = Math.round(subtotal) - pph;
        
        $(this).find("input[name*='[subtotal]']").val( formatNumberForUser( Math.round(subtotal) ) );
        //$(this).find("input[name*='[pph]']").val( formatNumberForUser( Math.round(pph) ) );
        $(this).find("input[name*='[pph]']").val( formatNumberForUser( pph ) );
        $(this).find("input[name*='[bayar]']").val( formatNumberForUser( Math.round(bayar) ) );
        
        total_pcs += pcs;
        total_m3 += m3;
        total_harga += subtotal;
        total_pph += pph;
        total_bayar += bayar;
    });
    
    var total_harga = Math.round(total_harga)
    var total_pph = Math.floor(total_pph)
    var total_bayar = total_harga - total_pph;

    $("input[name*='total_pcs']").val( formatNumberForUser(total_pcs) );
    $("input[name*='total_volume']").val( formatNumberForUser(total_m3) );
    $("input[name*='total_harga']").val( formatNumberForUser( total_harga ) );
    $("input[name*='total_pph']").val( formatNumberForUser( total_pph ) );
    //$("input[name*='total_bayar']").val( formatNumberForUser( Math.round(total_bayar) ) );
    $("input[name*='total_bayar']").val( formatNumberForUser(total_bayar) );

    $("#<?= yii\helpers\Html::getInputId($model, "total_pcs") ?>").val( formatNumberForUser(total_pcs) );
    $("#<?= yii\helpers\Html::getInputId($model, "total_m3") ?>").val( formatNumberForUser(total_m3) );
    $("#<?= yii\helpers\Html::getInputId($model, "total_pph") ?>").val( formatNumberForUser( total_pph ) );
    $("#<?= yii\helpers\Html::getInputId($model, "total_bayar") ?>").val( formatNumberForUser( total_bayar ) );
}

function save(){
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($model, "kode_po") ?>").parents("td").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "kode_terima") ?>").parents("td").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "suplier_nm") ?>").parents("td").removeClass("has-error");
    
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
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

function validatingDetail($form){
	var has_error = 0;
	
    if( $('#<?= yii\bootstrap\Html::getInputId($model, "bayar_langsung") ?>').prop('checked') ){
        var kode_po = $("#<?= yii\helpers\Html::getInputId($model, 'kode_po') ?>");
        if(!kode_po.val()){
            $("#<?= yii\helpers\Html::getInputId($model, 'kode_po') ?>").addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $("#<?= yii\helpers\Html::getInputId($model, 'kode_po') ?>").removeClass('error-tb-detail');
        }
    }else{
        var kode_terima = $("#<?= yii\helpers\Html::getInputId($model, 'kode_terima') ?>");
        if(!kode_terima.val()){
            $("#<?= yii\helpers\Html::getInputId($model, 'kode_terima') ?>").addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $("#<?= yii\helpers\Html::getInputId($model, 'kode_terima') ?>").removeClass('error-tb-detail');
        }
    }
    
    var suplier_nm = $("#<?= yii\helpers\Html::getInputId($model, 'suplier_nm') ?>");
    if(!suplier_nm.val()){
        $("#<?= yii\helpers\Html::getInputId($model, 'suplier_nm') ?>").addClass('error-tb-detail');
        has_error = has_error + 1;
    }else{
        $("#<?= yii\helpers\Html::getInputId($model, 'suplier_nm') ?>").removeClass('error-tb-detail');
    }
	
	$("#table-detail tbody tr").each(function(){
        
        var panjang = $(this).find("select[name*='[panjang]']");
        if(!panjang.val()){
            panjang.parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            panjang.parents('td').removeClass('error-tb-detail');
        }
        var diameter_awal = $(this).find("input[name*='[diameter_awal]']");
        if(!diameter_awal.val()){
            diameter_awal.parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            if(diameter_awal.val() == '0'){
                diameter_awal.parents('td').addClass('error-tb-detail');
                has_error = has_error + 1;
            }else{
                diameter_awal.parents('td').removeClass('error-tb-detail');
            }
        }
        var diameter_akhir = $(this).find("input[name*='[diameter_akhir]']");
        if(!diameter_akhir.val()){
            diameter_akhir.parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            if(diameter_akhir.val() == '0'){
                diameter_akhir.parents('td').addClass('error-tb-detail');
                has_error = has_error + 1;
            }else{
                diameter_akhir.parents('td').removeClass('error-tb-detail');
            }
        }
        var pcs = $(this).find("input[name*='[pcs]']");
        if(!pcs.val()){
            pcs.parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            if(pcs.val() == '0'){
                pcs.parents('td').addClass('error-tb-detail');
                has_error = has_error + 1;
            }else{
                pcs.parents('td').removeClass('error-tb-detail');
            }
        }
        var m3 = $(this).find("input[name*='[m3]']");
        if(!m3.val()){
            m3.parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            if(m3.val() == '0'){
                m3.parents('td').addClass('error-tb-detail');
                has_error = has_error + 1;
            }else{
                m3.parents('td').removeClass('error-tb-detail');
            }
        }
        var harga = $(this).find("input[name*='[harga]']");
        if(!harga.val()){
            harga.parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            if(harga.val() == '0'){
                harga.parents('td').addClass('error-tb-detail');
                has_error = has_error + 1;
            }else{
                harga.parents('td').removeClass('error-tb-detail');
            }
        }
        
	});
    
	if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id,edit){
	getItems(id,edit,function(){
        var posengon_id = $("#<?= yii\helpers\Html::getInputId($model, 'posengon_id') ?>").val();
        var suplier_id = $("#<?= yii\helpers\Html::getInputId($model, 'suplier_id') ?>").val();
        var terima_sengon_id = $("#<?= yii\helpers\Html::getInputId($model, 'terima_sengon_id') ?>").val();
		$('form').find('input').each(function(){ $(this).prop("disabled", true); });
		$('form').find('select').each(function(){ $(this).prop("disabled", true); });
		$('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
		$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
		$('#btn-save').attr('disabled','');
		$('#btn-print').removeAttr('disabled');
		$('#btn-print2').removeAttr('disabled');
		$('#btn-print2').removeAttr('disabled');
        $('#btn-add').hide();
        $('#btn-cariterima').hide();
        $('#btn-caripo').hide();
		<?php if(isset($_GET['edit'])){ ?>
			$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>').prop("disabled", false);
			$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
			$("#table-detail > tbody > tr").find("select[name*='[panjang]']").prop("disabled", false);
			$("#table-detail > tbody > tr").find("select[name*='[wilayah]']").prop("disabled", false);
			$("#table-detail > tbody > tr").find("input[name*='[diameter_awal]']").prop("disabled", false);
			$("#table-detail > tbody > tr").find("input[name*='[diameter_akhir]']").prop("disabled", false);
			$("#table-detail > tbody > tr").find("input[name*='[pcs]']").prop("disabled", false);
			$("#table-detail > tbody > tr").find("input[name*='[m3]']").prop("disabled", false);
			$("#table-detail > tbody > tr").find("input[name*='[harga]']").prop("disabled", false);
			$('#btn-save').prop('disabled',false);
			$('#btn-print').prop('disabled',true);
			$('#btn-add').show();
            $('#btn-cariterima').show();
            $('#btn-caripo').show();
		<?php } ?>
        
        setTimeout(function(){
            aktifkanBtnReferensi(posengon_id,suplier_id,terima_sengon_id);
            setTotal();
        },1000);
	});
}

function getItems(id,edit=null,callback=null){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/tagihansengon/GetItems']); ?>',
		type   : 'POST',
		data   : {id:id,edit:edit},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				formconfig();
				if( callback ){ callback(); }
                reordertable("#table-detail");
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/tagihansengon/daftarAfterSave']) ?>','modal-aftersave','90%');
}
</script>