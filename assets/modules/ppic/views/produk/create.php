<?php
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
?>
<div class="modal fade" id="modal-produk-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Produk Baru'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-produk-create',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'produk_group')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-produk'),['class'=>'form-control','onchange'=>'setJenisProduk();']) ?>
                        <div class="place-jenis-kayu" style="display: none;">
                            <?= $form->field($model, 'jenis_kayu')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
                        </div>
						<div class="place-grade" style="display: none;">
							<?= $form->field($model, 'grade')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
						</div>
						<div class="place-glue" style="display: none;">
							<?= $form->field($model, 'glue')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
						</div>
						<div class="place-profil-kayu" style="display: none;">
							<?= $form->field($model, 'profil_kayu')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
						</div>
						<div class="place-kondisi-kayu" style="display: none;">
							<?= $form->field($model, 'kondisi_kayu')->dropDownList([],['class'=>'form-control','onchange'=>'setKodeNamaProduk();']) ?>
						</div>
                        <?= $form->field($model, 'produk_t',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($model, 'produk_t_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','onchange'=>'setDimensi(); setMeterKubik();']).'</span> {error}</div>'])
                                ->textInput(['class'=>'form-control float','onblur'=>'setKodeNamaProduk(); setDimensi(); setMeterKubik();']); ?>
                        <?= $form->field($model, 'produk_l',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($model, 'produk_l_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','onchange'=>'setDimensi(); setMeterKubik();']).'</span> {error}</div>'])
                                ->textInput(['class'=>'form-control float','onblur'=>'setKodeNamaProduk(); setDimensi(); setMeterKubik();']); ?>
                        <?= $form->field($model, 'produk_p',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($model, 'produk_p_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','onchange'=>'setDimensi(); setMeterKubik();']).'</span> {error}</div>'])
                                ->textInput(['class'=>'form-control float','onblur'=>'setKodeNamaProduk(); setDimensi(); setMeterKubik();']); ?>
						<?= $form->field($model, 'produk_kode')->textInput(['style'=>'font-weight:800']); ?>
                        <?= $form->field($model, 'produk_nama')->textInput(['style'=>'font-weight:800']); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'produk_satuan_besar')->dropDownList(\app\models\MDefaultValue::getOptionList('produk-satuan-besar'),['class'=>'form-control','onchange'=>'setLabelSatuanKecil(this.value)','readonly'=>'readonly']) ?>
                        <?= $form->field($model, 'produk_qty_satuan_kecil',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($model, 'produk_satuan_kecil', \app\models\MDefaultValue::getOptionList('produk-satuan-kecil'),['class'=>'form-control','disabled'=>'disabled']).'</span> {error}</div>'])
                                ->textInput(['class'=>'form-control numbers-only','onblur'=>'setMeterKubik()','disabled'=>'disabled'])->label(""); ?>
                        <?= $form->field($model, 'produk_dimensi')->textInput(['readonly'=>'readonly']); ?>
                        <div class="form-group">
                            <label class="col-md-4 control-label" ><?php echo Yii::t('app', 'Kapasitas Kubikasi'); ?></label>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <?php echo \yii\bootstrap\Html::activeTextInput($model, 'kapasitas_kubikasi',['class'=>'form-control','readonly'=>'readonly']) ?>
                                            <span class="input-group-addon" style="padding-left: 5px; padding-right: 5px;">M<sup>3</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        echo $form->field($model, 'produk_gbr',[
                            'template'=>'{label}
                                <div class="col-md-8">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                        <div>
                                            <span class="btn blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-produk-create\').modal(\'hide\'); $(\'#table-produk\').dataTable().fnClearTable();")'
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
    setLabelSatuanKecil('".$model->produk_satuan_besar."');
", yii\web\View::POS_READY); ?>
<script>
function setLabelSatuanKecil(satuan_besar){
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_qty_satuan_kecil') ?>').parents("div").siblings("label").html("Qty Per "+satuan_besar);
}

function setJenisProduk(){
	setDropdownJenisKayu(function(){
		setDropdownGrade(function(){
			setDropdownGlue(function(){
				setDropdownProfilKayu(function(){
					setDropdownKondisiKayu(function(){
						setKodeNamaProduk(function(){
							$("#<?= yii\bootstrap\Html::getInputId($model, "produk_t") ?>").val("0");
							$("#<?= yii\bootstrap\Html::getInputId($model, "produk_l") ?>").val("0");
							$("#<?= yii\bootstrap\Html::getInputId($model, "produk_p") ?>").val("0");
							setDimensi();
							setMeterKubik();
						});
					});
				});
			});
		});
	});
	
	
}
function setDropdownJenisKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'jenis_kayu') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownJenisKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'jenis_kayu') ?>").html(data.html);
				$('.place-jenis-kayu').css('display','block');
			}else{
				$('.place-jenis-kayu').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownGrade(callback=null){
    var jenis_produk = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownGrade']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'grade') ?>").html(data.html);
				$('.place-grade').css('display','block');
			}else{
				$('.place-grade').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownGlue(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'glue') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownGlue']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'glue') ?>").html(data.html);
				$('.place-glue').css('display','block');
			}else{
				$('.place-glue').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownProfilKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'profil_kayu') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownProfilKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'profil_kayu') ?>").html(data.html);
				$('.place-profil-kayu').css('display','block');
			}else{
				$('.place-profil-kayu').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownKondisiKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'kondisi_kayu') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownKondisiKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'kondisi_kayu') ?>").html(data.html);
				$('.place-kondisi-kayu').css('display','block');
			}else{
				$('.place-kondisi-kayu').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setKodeNamaProduk(callback=null){
	var jenis_produk = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
	var jenis_kayu = $('#<?= \yii\bootstrap\Html::getInputId($model, 'jenis_kayu') ?>').val();
    var grade = $('#<?= \yii\bootstrap\Html::getInputId($model, 'grade') ?>').val();
    var glue = $('#<?= \yii\bootstrap\Html::getInputId($model, 'glue') ?>').val();
    var profil_kayu = $('#<?= \yii\bootstrap\Html::getInputId($model, 'profil_kayu') ?>').val();
    var kondisi_kayu = $('#<?= \yii\bootstrap\Html::getInputId($model, 'kondisi_kayu') ?>').val();
    var p = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_p') ?>').val() );
    var l = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_l') ?>').val() );
    var t = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_t') ?>').val() );
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setKodeNamaProduk']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk,jenis_kayu:jenis_kayu,grade:grade,glue:glue,profil_kayu:profil_kayu,kondisi_kayu:kondisi_kayu,p:p,l:l,t:t},
		success: function (data) {
            if(data){
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_kode') ?>').val(data.produk_kode);
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_nama') ?>').val(data.produk_nama);
            }
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}


function setDimensi(){
    var p = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_p') ?>').val();
    var l = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_l') ?>').val();
    var t = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_t') ?>').val();
    var sat_p = "";
    var sat_l = "";
    var sat_t = "";
    if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_p_satuan') ?>').val() == 'mm'){
        sat_p = " mm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_p_satuan') ?>').val() == 'feet'){
        sat_p = "'";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_p_satuan') ?>').val() == 'cm'){
        sat_p = " cm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_p_satuan') ?>').val() == 'm'){
        sat_p = " m";
    }
    if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_l_satuan') ?>').val() == 'mm'){
        sat_l = " mm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_l_satuan') ?>').val() == 'feet'){
        sat_l = "'";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_l_satuan') ?>').val() == 'cm'){
        sat_l = " cm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_l_satuan') ?>').val() == 'm'){
        sat_l = " m";
    }
    if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_t_satuan') ?>').val() == 'mm'){
        sat_t = " mm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_t_satuan') ?>').val() == 'feet'){
        sat_t = "'";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_t_satuan') ?>').val() == 'cm'){
        sat_t = " cm";
    }else if($('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_t_satuan') ?>').val() == 'm'){
        sat_t = " m";
    }
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_dimensi') ?>').val(t+sat_t+' x '+l+sat_l+' x '+p+sat_p);
}
function setMeterKubik(){
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'kapasitas_kubikasi') ?>').addClass('animation-loading');
    var p = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_p') ?>').val() );
    var l = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_l') ?>').val() );
    var t = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_t') ?>').val() );
    var sat_p = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_p_satuan') ?>').val();
    var sat_l = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_l_satuan') ?>').val();
    var sat_t = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_t_satuan') ?>').val();
    var qty = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_qty_satuan_kecil') ?>').val() );
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
        $('#<?= \yii\bootstrap\Html::getInputId($model, 'kapasitas_kubikasi') ?>').val( formatNumberForUser(result) );
        $('#<?= \yii\bootstrap\Html::getInputId($model, 'kapasitas_kubikasi') ?>').removeClass('animation-loading');
    }, 300);
}
</script>