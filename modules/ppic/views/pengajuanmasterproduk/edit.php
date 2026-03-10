<?php
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
?>
<div class="modal fade" id="modal-produk-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Data Produk'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-produk-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($modDetail, 'produk_group')->dropDownList(\app\models\MDefaultValue::getOptionListProduk('jenis-produk'),['class'=>'form-control','onchange'=>'setJenisProduk();', 'disabled'=>'disabled']) ?>
                        <div class="place-jenis-kayu" style="display: none;">
                            <?= $form->field($modDetail, 'jenis_kayu')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
                        </div>
                                <div class="place-grade" style="display: none;">
                                        <?= $form->field($modDetail, 'grade')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
                                </div>
                                <div class="place-warna-kayu" style="display: none;">
                                        <?= $form->field($modDetail, 'warna_kayu')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
                                </div>
                                <div class="place-glue" style="display: none;">
                                        <?= $form->field($modDetail, 'glue')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
                                </div>
                                <div class="place-profil-kayu" style="display: none;">
                                        <?= $form->field($modDetail, 'profil_kayu')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
                                </div>
                                <div class="place-kondisi-kayu" style="display: none;">
                                        <?= $form->field($modDetail, 'kondisi_kayu')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
                                </div>
                        <?= $form->field($modDetail, 'produk_t',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($modDetail, 'produk_t_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','onchange'=>'setDimensi(); setMeterKubik();']).'</span> {error}</div>'])
                                ->textInput(['class'=>'form-control float','onblur'=>'setKodeNamaProduk(); setDimensi(); setMeterKubik();']); ?>
                        <?= $form->field($modDetail, 'produk_l',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($modDetail, 'produk_l_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','onchange'=>'setDimensi(); setMeterKubik();']).'</span> {error}</div>'])
                                ->textInput(['class'=>'form-control float','onblur'=>'setKodeNamaProduk(); setDimensi(); setMeterKubik();']); ?>
                        <?= $form->field($modDetail, 'produk_p',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($modDetail, 'produk_p_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','onchange'=>'setDimensi(); setMeterKubik();']).'</span> {error}</div>'])
                                ->textInput(['class'=>'form-control float','onblur'=>'setKodeNamaProduk(); setDimensi(); setMeterKubik();']); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($modDetail, 'produk_kode')->textInput(['style'=>'font-weight:800']); ?>
                        <?= $form->field($modDetail, 'produk_nama')->textInput(['style'=>'font-weight:800']); ?>
                        <?= $form->field($modDetail, 'produk_satuan_besar')->dropDownList(\app\models\MDefaultValue::getOptionList('produk-satuan-besar'),['class'=>'form-control','onchange'=>'setLabelSatuanKecil(this.value)','readonly'=>'readonly']) ?>
                        <?= $form->field($modDetail, 'produk_qty_satuan_kecil',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($modDetail, 'produk_satuan_kecil', \app\models\MDefaultValue::getOptionList('produk-satuan-kecil'),['class'=>'form-control','disabled'=>'disabled']).'</span> {error}</div>'])
                                ->textInput(['class'=>'form-control numbers-only','onblur'=>'setMeterKubik()','disabled'=>'disabled'])->label(""); ?>
                        <?= $form->field($modDetail, 'produk_dimensi')->textInput(['readonly'=>'readonly']); ?>
                        <div class="form-group">
                            <label class="col-md-4 control-label" ><?php echo Yii::t('app', 'Kapasitas Kubikasi'); ?></label>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <?php echo \yii\bootstrap\Html::activeTextInput($modDetail, 'kapasitas_kubikasi',['class'=>'form-control','readonly'=>'readonly']) ?>
                                            <span class="input-group-addon" style="padding-left: 5px; padding-right: 5px;">M<sup>3</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Update'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'updateData('.json_encode($datas).', "'.$tr_id.'")'
                    ]);
                ?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    formconfig();
	setJenisProduk();
    setLabelSatuanKecil('".$modDetail->produk_satuan_besar."');
", yii\web\View::POS_READY); ?>
<script>
function setLabelSatuanKecil(satuan_besar){
    $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_qty_satuan_kecil') ?>').parents("div").siblings("label").html("Qty Per "+satuan_besar);
}

function setJenisProduk(){
	setDropdownJenisKayu(function(){
            setDropdownGrade(function(){
                setDropdownWarnaKayu(function() {
                    setDropdownGlue(function(){
                        setDropdownProfilKayu(function(){
                            setDropdownKondisiKayu(function(){
                                setKodeNamaProduk(function(){
                                    setDimensi();
                                    setMeterKubik();
                                });
                            });
                        });
                    });
                });
            });
	});
}
function setDropdownJenisKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($modDetail, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'jenis_kayu') ?>").html("");

    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownJenisKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'jenis_kayu') ?>").html(data.html);
				$('.place-jenis-kayu').css('display','block');
                var selected = "<?= $modDetail->jenis_kayu ?>"; 
                if (selected !== "null" || selected !== null || selected !== "") {
                    $("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'jenis_kayu') ?>").val(selected);
                }
                // console.log(selected);
			}else{
				$('.place-jenis-kayu').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownGrade(callback=null){
    var jenis_produk = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_group') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownGrade']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'grade') ?>").html(data.html);
				$('.place-grade').css('display','block');
                var selected = "<?= $modDetail->grade ?>"; 
                if (selected!== "null" || selected !== null || selected !== "") {
                    $("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'grade') ?>").val(selected);
                }
			}else{
				$('.place-grade').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownWarnaKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($modDetail, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'warna_kayu') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownWarnaKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'warna_kayu') ?>").html(data.html);
				$('.place-warna-kayu').css('display','block');
                var selected = "<?= $modDetail->warna_kayu ?>"; 
                if (selected !== "null" || selected !== null || selected !== "") {
                    $("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'warna_kayu') ?>").val(selected);
                }
			}else{
				$('.place-warna-kayu').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownGlue(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($modDetail, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'glue') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownGlue']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'glue') ?>").html(data.html);
				$('.place-glue').css('display','block');
                var selected = "<?= $modDetail->glue ?>"; 
                if (selected !== "null" || selected !== null || selected !== "") {
                    $("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'glue') ?>").val(selected);
                }
			}else{
				$('.place-glue').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownProfilKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($modDetail, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'profil_kayu') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownProfilKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'profil_kayu') ?>").html(data.html);
				$('.place-profil-kayu').css('display','block');
                var selected = "<?= $modDetail->profil_kayu ?>"; 
                if (selected !== "null" || selected !== null || selected !== "") {
                    $("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'profil_kayu') ?>").val(selected);
                }
			}else{
				$('.place-profil-kayu').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownKondisiKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($modDetail, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'kondisi_kayu') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownKondisiKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'kondisi_kayu') ?>").html(data.html);
				$('.place-kondisi-kayu').css('display','block');
                var selected = "<?= $modDetail->kondisi_kayu ?>"; 
                if (selected !== "null" || selected !== null || selected !== "") {
                    $("#<?= \yii\bootstrap\Html::getInputId($modDetail, 'kondisi_kayu') ?>").val(selected);
                }
			}else{
				$('.place-kondisi-kayu').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setKodeNamaProduk(callback=null){
	var jenis_produk = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_group') ?>').val();
	var jenis_kayu = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'jenis_kayu') ?>').val();
    var grade = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'grade') ?>').val();
    var warna_kayu = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'warna_kayu') ?>').val();
    var glue = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'glue') ?>').val();
    var profil_kayu = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'profil_kayu') ?>').val();
    var kondisi_kayu = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'kondisi_kayu') ?>').val();
    var p = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_p') ?>').val() );
    var l = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_l') ?>').val() );
    var t = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_t') ?>').val() );
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setKodeNamaProduk']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk,jenis_kayu:jenis_kayu,grade:grade,warna_kayu:warna_kayu,glue:glue,profil_kayu:profil_kayu,kondisi_kayu:kondisi_kayu,p:p,l:l,t:t},
		success: function (data) {
            if(data){
				$('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_kode') ?>').val(data.produk_kode);
				$('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_nama') ?>').val(data.produk_nama);
            }
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}


function setDimensi(){
    var p = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_p') ?>').val());
    var l = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_l') ?>').val());
    var t = unformatNumber($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_t') ?>').val());
    var sat_p = "";
    var sat_l = "";
    var sat_t = "";
    if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_p_satuan') ?>').val() == 'mm'){
        sat_p = " mm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_p_satuan') ?>').val() == 'feet'){
        sat_p = "'";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_p_satuan') ?>').val() == 'cm'){
        sat_p = " cm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_p_satuan') ?>').val() == 'm'){
        sat_p = " m";
    }
    if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_l_satuan') ?>').val() == 'mm'){
        sat_l = " mm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_l_satuan') ?>').val() == 'feet'){
        sat_l = "'";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_l_satuan') ?>').val() == 'cm'){
        sat_l = " cm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_l_satuan') ?>').val() == 'm'){
        sat_l = " m";
    }
    if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_t_satuan') ?>').val() == 'mm'){
        sat_t = " mm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_t_satuan') ?>').val() == 'feet'){
        sat_t = "'";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_t_satuan') ?>').val() == 'cm'){
        sat_t = " cm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_t_satuan') ?>').val() == 'm'){
        sat_t = " m";
    }
    $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_dimensi') ?>').val(t+sat_t+' x '+l+sat_l+' x '+p+sat_p);
}
function setMeterKubik(){
    $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'kapasitas_kubikasi') ?>').addClass('animation-loading');
    var p = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_p') ?>').val() );
    var l = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_l') ?>').val() );
    var t = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_t') ?>').val() );
    var sat_p = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_p_satuan') ?>').val();
    var sat_l = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_l_satuan') ?>').val();
    var sat_t = $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_t_satuan') ?>').val();
    var qty = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'produk_qty_satuan_kecil') ?>').val() );
    var sat_p_m = 0;
    var sat_l_m = 0;
    var sat_t_m = 0;
    var result = 0;
    
    if(sat_p == 'mm'){
        sat_p_m = p * 0.001;
    }else if(sat_p == 'cm'){
        sat_p_m = p * 0.01;
    }else if(sat_p == 'inch'){
        sat_p_m = p * 0.0254;
    }else if(sat_p == 'm'){
        sat_p_m = p;
    }else if(sat_p == 'feet'){
        sat_p_m = p * 0.3048;
    }
    if(sat_l == 'mm'){
        sat_l_m = l * 0.001;
    }else if(sat_l == 'cm'){
        sat_l_m = l * 0.01;
    }else if(sat_l == 'inch'){
        sat_l_m = l * 0.0254;
    }else if(sat_l == 'm'){
        sat_l_m = l;
    }else if(sat_l == 'feet'){
        sat_l_m = l * 0.3048;
    }
    if(sat_t == 'mm'){
        sat_t_m = t * 0.001;
    }else if(sat_t == 'cm'){
        sat_t_m = t * 0.01;
    }else if(sat_t == 'inch'){
        sat_t_m = t * 0.0254;
    }else if(sat_t == 'm'){
        sat_t_m = t;
    }else if(sat_t == 'feet'){
        sat_t_m = t * 0.3048;
    }
    result = sat_p_m * sat_l_m * sat_t_m * qty;
    result = (Math.round( result * 10000 ) / 10000 ).toString();
    setTimeout(function() {
        $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'kapasitas_kubikasi') ?>').val( formatNumberForUser(result) );
        $('#<?= \yii\bootstrap\Html::getInputId($modDetail, 'kapasitas_kubikasi') ?>').removeClass('animation-loading');
    }, 300);
}
</script>