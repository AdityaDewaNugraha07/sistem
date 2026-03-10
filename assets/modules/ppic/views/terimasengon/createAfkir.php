<div class="modal fade" id="modal-create-afkir" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambah Afkir Sengon'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-transaksi',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-3 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'kode')->textInput(['style'=>'font-weight:bold','disabled'=>'disabled'])->label("Kode Afkir"); ?>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="col-md-3 control-label"><?= Yii::t('app', 'Kode Input Penerimaan'); ?></label>
                            <div class="col-md-8">
                                <span class="input-group-btn" style="width: 90%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, "terima_kode", ['class'=>'form-control','disabled'=>true,'placeholder'=>'Cari Penerimaan Sengon','style'=>'width:100%']); ?>
                                </span>
                                <span class="input-group-btn" style="width: 10%">
                                    <a class="btn btn-icon-only btn-default tooltips" onclick="openPenerimaan();" data-original-title="Cari Penerimaan Log Sengon" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-search"></i></a>
                                </span>
                            </div>
                        </div>
                        <?= \yii\helpers\Html::activeHiddenInput($model, "terima_sengon_id") ?>
                        <?= $form->field($model, 'suplier_nm')->textInput(['disabled'=>'disabled'])->label("Suplier"); ?>
                        <?= $form->field($model, 'lokasi_muat')->textInput(['disabled'=>'disabled'])->label("Lokasi Muat"); ?>
                        <?= $form->field($model, 'asal_kayu')->textInput(['disabled'=>'disabled'])->label("Asal Kayu"); ?>
                        <?= $form->field($model, 'nopol')->textInput(['disabled'=>'disabled'])->label("Nopol Kendaraan"); ?>
                        <?= $form->field($model, 'tanggal')->hiddenInput(['value'=>'0000-00-00'])->label(false); ?>
                        <?php /* <?= $form->field($model, 'grader')->hiddenInput(['value'=>0])->label(false); ?> */?>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="col-md-3 control-label"><?= Yii::t('app', 'Total Nota Angkut'); ?></label>
                            <div class="col-md-8">
                                <span class="input-group-btn" style="width: 40%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, "qty_pcs_nota", ['class'=>'form-control float','disabled'=>true,'style'=>'width:100%']); ?>
                                </span>
                                <span class="input-group-addon" style="width: 5%">Pcs</span>
                                <span class="input-group-addon" style="width: 10%; background-color: #fff; border: 1px solid transparent;"></span>
                                <span class="input-group-btn" style="width: 40%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, "qty_m3_nota", ['class'=>'form-control float','disabled'=>true,'style'=>'width:100%']); ?>
                                </span>
                                <span class="input-group-addon" style="width: 5%">M<sup>3</sup></span>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="col-md-3 control-label"><?= Yii::t('app', 'Total Terima'); ?></label>
                            <div class="col-md-8">
                                <span class="input-group-btn" style="width: 40%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, "qty_pcs_terima", ['class'=>'form-control float','disabled'=>true,'style'=>'width:100%']); ?>
                                </span>
                                <span class="input-group-addon" style="width: 5%">Pcs</span>
                                <span class="input-group-addon" style="width: 10%; background-color: #fff; border: 1px solid transparent;"></span>
                                <span class="input-group-btn" style="width: 40%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, "qty_m3_terima", ['class'=>'form-control float','disabled'=>true,'style'=>'width:100%']); ?>
                                </span>
                                <span class="input-group-addon" style="width: 5%">M<sup>3</sup></span>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="col-md-3 control-label"><?= Yii::t('app', 'Total Afkir'); ?></label>
                            <div class="col-md-8">
                                <table class="table table-striped table-bordered table-hover" id="table-detail-terima">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;">No.</th>
                                            <th style="width: 150px;">Panjang</th>
                                            <th style="width: 150px;">Diameter</th>
                                            <th style="width: 150px;">Pcs</th>
                                            <th style="width: 150px;">m3</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td><a class="btn btn-xs blue-hoki" onclick="addItemAfkir();"><i class="icon-plus"></i> Add</a></td>
                                            <td colspan="2" class="text-align-right">Total &nbsp; </td>
                                            <td><?= yii\helpers\Html::activeTextInput($model, "qty_pcs_total", ['style'=>"width:100%","class"=>"form-control float","disabled"=>true]) ?></td>
                                            <td><?= yii\helpers\Html::activeTextInput($model, "qty_m3_total", ['style'=>"width:100%","class"=>"form-control float","disabled"=>true]) ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="col-md-3 control-label"><?= Yii::t('app', 'Selisih'); ?></label>
                            <div class="col-md-8">
                                <span class="input-group-btn" style="width: 40%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, "selisih_pcs", ['class'=>'form-control float','disabled'=>true,'style'=>'width:100%']); ?>
                                </span>
                                <span class="input-group-addon" style="width: 5%">Pcs</span>
                                <span class="input-group-addon" style="width: 10%; background-color: #fff; border: 1px solid transparent;"></span>
                                <span class="input-group-btn" style="width: 40%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, "selisih_m3", ['class'=>'form-control float','disabled'=>true,'style'=>'width:100%']); ?>
                                </span>
                                <span class="input-group-addon" style="width: 5%">M<sup>3</sup></span>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-md-3 text-right">Sudah Dikirim</div>
                            <div class="col-md-4" style="">
                                <?= $form->field($model, 'sudah_dikirim',['template' => '{label}<div class="mt-checkbox-list col-md-8"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                                ->checkbox([],false)->label(false); ?>
                            </div>
                            <div class="col-md-4">
                                <?php /* <?= $form->field($model, 'grader',['template' => '{label}<div class="mt-checkbox-list col-md-1"><label class="mt-checkbox mt-checkbox-outline">{input} {error}  &nbsp;&nbsp;&nbsp;</label> </div><div class="mt-checkbox-list col-md-8" style="padding-top: 5px; padding-left: 10px;"> <font style="font-weight: bold; color: red; text-decoration: underline;">PASTIKAN GRADER ADA/TIDAK </font></div>',])
                                ->checkbox([],false)->label('Grader'); ?> */ ?>
                                <?php echo $form->field($model, 'grader')->radioList([ 0 => 'TIDAK ADA GRADER', 1=>'ADA GRADER']); ?> <div style="margin-left: 40px;"><font style="font-weight: bold; color: red; text-decoration: underline;">PASTIKAN GRADER ADA/TIDAK </font></div>
                            </div>
                            <div class="col-md-2">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'save(this)'
                    ]);
                ?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    formconfig();
    addItemAfkir();
", yii\web\View::POS_READY); ?>
<script>
function pickPenerimaanSengon(terima_sengon_id,kode,tanggal,suplier_nm,lokasi_muat,asal_kayu,nopol,pcs_nota,m3_nota,pcs,m3,grader){
    if (grader == 0) {
        xxx = 'Ada';
        yyy = "Persediaan";
        zzz = true;
    } else {
        xxx = 'Tidak ada';
        yyy = "Kembalikan lagi";
        zzz = false;
    }
    //alert('ID = '+terima_sengon_id+'\nKode = '+kode+'\nTanggal = '+tanggal+'\nSupplier = '+suplier_nm+'\nMuat = '+lokasi_muat+'\nAsal = '+asal_kayu+'\nNopol = '+nopol+'\nGrader = '+xxx+'\nAfkir = '+yyy);
    //exit();
    $('#modal-open-penerimaan').modal('hide');
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "terima_sengon_id") ?>').val(terima_sengon_id);
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "terima_kode") ?>').val(kode+" - "+tanggal);
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "suplier_nm") ?>').val(suplier_nm);
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "lokasi_muat") ?>').val(lokasi_muat);
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "asal_kayu") ?>').val(asal_kayu);
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "nopol") ?>').val(nopol);
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "qty_pcs_nota") ?>').val(pcs_nota);
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "qty_m3_nota") ?>').val(m3_nota);
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "qty_pcs_terima") ?>').val(pcs);
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "qty_m3_terima") ?>').val(m3);
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "tanggal") ?>').val(tanggal);
    //$('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "grader") ?>').val(grader).prop("checked", zzz);

    setSelisih();
}

function setSelisih(){
    var qty_pcs_nota = unformatNumber( $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "qty_pcs_nota") ?>').val() );
    var qty_m3_nota = unformatNumber( $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "qty_m3_nota") ?>').val() );
    var qty_pcs_terima = unformatNumber( $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "qty_pcs_terima") ?>').val() );
    var qty_m3_terima = unformatNumber( $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "qty_m3_terima") ?>').val() );
    var qty_pcs_total = unformatNumber( $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "qty_pcs_total") ?>').val() );
    var qty_m3_total = unformatNumber( $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "qty_m3_total") ?>').val() );
    var selisih_pcs = Math.abs( (qty_pcs_nota+qty_pcs_terima) - qty_pcs_total );
    var selisih_m3 = Math.abs( (qty_m3_nota+qty_m3_terima) - qty_m3_total );
    
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "selisih_pcs") ?>').val( selisih_pcs );
    $('#modal-create-afkir').find('#<?= \yii\helpers\Html::getInputId($model, "selisih_m3") ?>').val( formatNumberForUser3Digit(selisih_m3) );
}

function addItemAfkir(){
	var terima_sengon_id = $("#<?= \yii\bootstrap\Html::getInputId($model, 'terima_sengon_id') ?>").val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/terimasengon/addItemAfkir']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail-terima tbody').fadeIn(500,function(){
                    reordertable('#table-detail-terima');
					setTotalAfkir();
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
        reordertable('#table-detail-terima');
		setTotalAfkir();
    });
}

function setTotalAfkir(){
    var qty_pcs = 0; var qty_m3 = 0;
    $("#table-detail-terima > tbody > tr").each(function(){
        qty_pcs += unformatNumber( $(this).find("input[name*='[qty_pcs]']").val() );
        qty_m3 += unformatNumber( $(this).find("input[name*='[qty_m3]']").val() );
    });
    var qty_m3 = (Math.round( qty_m3 * 10000 ) / 10000 ).toString();
    $("#<?= yii\helpers\Html::getInputId($model, 'qty_pcs_total') ?>").val(qty_pcs);
    $("#<?= yii\helpers\Html::getInputId($model, 'qty_m3_total') ?>").val(qty_m3);
    setSelisih();
}

function save(ele){
    var $form = $('#form-transaksi');
    var jmltr = $("#table-detail-terima > tbody > tr").length;
    if(jmltr > 0){
        if(validatingDetail()){
            submitformajax(ele);
        }
    }else{
        cisAlert("Isi Detail Afkir terlebih dahulu");
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
    
	$("#table-detail-terima > tbody > tr").each(function(){
        var panjang = $(this).find("input[name*='[panjang]']");
        var diameter = $(this).find("input[name*='[diameter]']");
        var qty_pcs = $(this).find("input[name*='[qty_pcs]']");
        var qty_m3 = $(this).find("input[name*='[qty_m3]']");
        
        if( !$(panjang).val() ){
            $(panjang).parents("td").addClass("has-error");
            has_error = has_error + 1;
        }else{
            if( unformatNumber($(panjang).val()) <= 0 ){
                $(panjang).parents("td").addClass("has-error");
                has_error = has_error + 1;
            }else{
                $(panjang).parents("td").removeClass("has-error");
            }
        }
        if( !$(diameter).val() ){
            $(diameter).parents("td").addClass("has-error");
            has_error = has_error + 1;
        }else{
            if( unformatNumber($(diameter).val()) <= 0 ){
                $(diameter).parents("td").addClass("has-error");
                has_error = has_error + 1;
            }else{
                $(diameter).parents("td").removeClass("has-error");
            }
        }
        if( !$(qty_pcs).val() ){
            $(qty_pcs).parents("td").addClass("has-error");
            has_error = has_error + 1;
        }else{
            if( unformatNumber($(qty_pcs).val()) <= 0 ){
                $(qty_pcs).parents("td").addClass("has-error");
                has_error = has_error + 1;
            }else{
                $(qty_pcs).parents("td").removeClass("has-error");
            }
        }
        if( !$(qty_m3).val() ){
            $(qty_m3).parents("td").addClass("has-error");
            has_error = has_error + 1;
        }else{
            if( unformatNumber($(qty_m3).val()) <= 0 ){
                $(qty_m3).parents("td").addClass("has-error");
                has_error = has_error + 1;
            }else{
                $(qty_m3).parents("td").removeClass("has-error");
            }
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}
</script>
