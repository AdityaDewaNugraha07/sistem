<?php
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\BootstrapSelectAsset::register($this);
?>
<div class="modal fade draggable-modal" id="modal-limbah-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Limbah Baru'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-limbah-create',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'limbah_kelompok')->dropDownList(\app\models\MDefaultValue::getOptionList('limbah-kelompok'),['class'=>'form-control bs-select']) ?>
                        <?= $form->field($model, 'limbah_produk_jenis')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-produk'),['class'=>'form-control bs-select','onchange'=>'setDropdownGrade(); setKodeNamaLimbah();']) ?>
                        <?= $form->field($model, 'limbah_grade')->dropDownList(\app\models\MDefaultValue::getOptionList(''),['class'=>'form-control bs-select','title'=> '']) ?>
                        <?= $form->field($model, 'limbah_satuan_jual',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($model, 'limbah_satuan_muat', \app\models\MDefaultValue::getOptionList('satuan-muat-limbah'),['class'=>'form-control bs-select']).'</span> {error}</div>'])
                            ->dropDownList(\app\models\MDefaultValue::getOptionList('satuan-penjualan'),['class'=>'form-control bs-select'])->label(Yii::t('app', 'Kuantitas')); ?>
                        <?= $form->field($model, 'limbah_kode')->textInput(); ?>
                        <?= $form->field($model, 'limbah_nama')->textInput(); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'limbah_keterangan')->textarea(); ?>
                        <?php 
                        echo $form->field($model, 'limbah_gambar',[
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
                    'onclick'=>'submitformajax(this,"$(\'#modal-limbah-create\').modal(\'hide\'); $(\'#table-limbah\').dataTable().fnClearTable();")'
                    ]);
                        ?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    formconfig();
    setDropdownGrade();
    setKodeNamaLimbah();
", yii\web\View::POS_READY); ?>
<script>
function setDropdownGrade(){
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_grade') ?>').addClass('animation-loading');
    var limbah_produk_jenis = $('#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_produk_jenis') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownGrade']); ?>',
		type   : 'POST',
		data   : {produk_group:limbah_produk_jenis},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_grade') ?>").html(data.html);
            $('#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_grade') ?>').removeClass('animation-loading');
            $(".bs-select").selectpicker("refresh");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setKodeNamaLimbah(){
    var limbah_produk_jenis = $('#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_produk_jenis') ?>').val();
    var kode = "";
    if(limbah_produk_jenis == 'Plywood'){
        kode = "CPWL";
    }else if(limbah_produk_jenis == 'Sawntimber'){
        kode = "CSTL";
    }else if(limbah_produk_jenis == 'Moulding'){
        kode = "CMLL";
    }else if(limbah_produk_jenis == 'Veneer'){
        kode = "CVNL";
    }else if(limbah_produk_jenis == 'Log'){
        kode = "CLGL";
    }else if(limbah_produk_jenis == 'Platform'){
        kode = "CPFL";
    }else if(limbah_produk_jenis == 'Lamineboard'){
        kode = "CLBL";
    }
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_kode') ?>').val(kode);
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_nama') ?>').val(limbah_produk_jenis);
}
</script>