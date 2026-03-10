<?php
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\BootstrapSelectAsset::register($this);
?>
<div class="modal fade" id="modal-log-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Log Baru'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-log-create',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-3 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'log_kelompok')->dropDownList(\app\models\MKayu::getOptionListGroupKayu(),['class'=>'form-control bs-select', 'onchange'=>'setLogKelompok();'])->label('Jenis Kayu') ?>
                        <div class="place-kayu" style="display: none;">
                            <?= $form->field($model, 'kayu_id')->dropDownList([],['class'=>'form-control', 'onchange'=>'setKodeLog(); setNamaLog();'])->label('Nama Kayu') ?>
                        </div>
                        <?= $form->field($model, 'log_satuan_jual',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                 {error}</div>'])
                            ->dropDownList(\app\models\MDefaultValue::getOptionList('satuan-penjualan-log'),['class'=>'form-control bs-select'])->label(Yii::t('app', 'Kuantitas')); ?>
                        <?= $form->field($model, 'range_awal',['template'=>'{label}<div class="col-md-7">
                                <span class="input-group-btn" style="width: 30%">{input}</span> 
                                <span class="input-group-btn" style="width: 70%">'.$form->field($model, 'range_akhir')->label(Yii::t('app', '')).'</span> {error}</div>'])
                            ->textInput()->label(Yii::t('app', 'Range Diameter')); ?>
                        <?= $form->field($model, 'fsc',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                                ->checkbox(['onchange' => 'setKodeLog(); setNamaLog();'],false)->label(Yii::t('app', 'FSC 100%')); ?>
                        <?= $form->field($model, 'log_kode')->textInput()->label('Kode'); ?>
                        <?= $form->field($model, 'log_nama',['template'=>'{label}<div class="col-md-8">{input} {error}</div>'])->textInput()->label('Nama Log'); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'log_keterangan')->textarea()->label('Keterangan'); ?>
                        <?php 
                        echo $form->field($model, 'log_gambar',[
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
                        ])->fileInput()->label('Gambar');
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'save()'
                    ]);
                ?>
                <?php //echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    // 'onclick'=>'submitformajax(this,"$(\'#modal-log-create\').modal(\'hide\'); $(\'#table-log\').dataTable().fnClearTable();")'
                    // ]);
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
    setLogKelompok();
    $('#mbrglog-range_awal').on('input', function() {
        setKodeLog();
        setNamaLog();
    });
    $('#mbrglog-range_akhir').on('input', function() {
        setKodeLog();
        setNamaLog();
    });
", yii\web\View::POS_READY); ?>
<script>
function setLogKelompok(){
    setDropdownKayu(function(){
        setKodeLog(function(){
            setNamaLog(function(){
                var log_kelompok = $('#<?= \yii\bootstrap\Html::getInputId($model, 'log_kelompok') ?>').val();
            });
        });
    });
}

function setDropdownKayu(callback=null){
	var log_kelompok = $("#<?= yii\bootstrap\Html::getInputId($model, "log_kelompok") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'kayu_id') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/log/setDropdownKayu']); ?>',
		type   : 'POST',
		data   : {log_kelompok:log_kelompok},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'kayu_id') ?>").html(data.html);
				$('.place-kayu').css('display','block');
			}else{
				$('.place-kayu').css('display','none');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setKodeLog(callback=null){
    var kayu_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'kayu_id') ?>').val();
    var range_awal = $('#<?= \yii\bootstrap\Html::getInputId($model, 'range_awal') ?>').val();
    var range_akhir = $('#<?= \yii\bootstrap\Html::getInputId($model, 'range_akhir') ?>').val();
    var fsc = $('#<?= \yii\bootstrap\Html::getInputId($model, 'fsc') ?>');
    if (fsc.prop('checked')) {
        var val_fsc = 1;
    } else {
        var val_fsc = 0;
    }
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/log/setKodeLog']); ?>',
		type   : 'POST',
		data   : {kayu_id:kayu_id,range_awal:range_awal,range_akhir:range_akhir,val_fsc:val_fsc},
		success: function (data) {
            if(data){
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_kode') ?>').val(data.log_kode);
            }
            
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setNamaLog(callback=null){
    var log_kelompok = $('#<?= \yii\bootstrap\Html::getInputId($model, 'log_kelompok') ?>').val();
    var kayu_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'kayu_id') ?>').val();
    var range_awal = $('#<?= \yii\bootstrap\Html::getInputId($model, 'range_awal') ?>').val();
    var range_akhir = $('#<?= \yii\bootstrap\Html::getInputId($model, 'range_akhir') ?>').val();
    var fsc = $('#<?= \yii\bootstrap\Html::getInputId($model, 'fsc') ?>');
    if (fsc.prop('checked')) {
        var val_fsc = 1;
    } else {
        var val_fsc = 0;
    }
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/log/setNamaLog']); ?>',
		type   : 'POST',
		data   : {log_kelompok:log_kelompok,kayu_id:kayu_id, range_awal:range_awal,range_akhir:range_akhir,val_fsc:val_fsc},
		success: function (data) {
            if(data){
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'log_nama') ?>').val(data.log_nama);
            }
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function save() {
	var $form = $('#form-log-create');
	if (formrequiredvalidate($form)) {
		if(validateData()){
            submitformajax($form,"$(\'#modal-log-create\').modal(\'hide\'); $(\'#table-log\').dataTable().fnClearTable();")
			// submitformajax($form , "$(\'#modal-terima-bpb\').modal(\'hide\');javascript:window.location.reload()");
			return true;
		}
    }
	return false;
}

function validateData(){
    var has_error = 0;
    var range_awal = unformatNumber($('#form-log-create').find('input[name*="[range_awal]"]').val());
    var range_akhir = unformatNumber($('#form-log-create').find('input[name*="[range_akhir]"]').val());
    var log_kode = $('#form-log-create').find('input[name*="[log_kode]"]').val();

    if(range_awal <= 0){
        has_error = has_error + 1;
        $('#form-log-create').find('input[name*="[range_awal]"]').addClass('error-tb-detail');
    } else {
        $('#form-log-create').find('input[name*="[range_awal]"]').removeClass('error-tb-detail');
    }

    if(range_akhir <= 0){
        has_error = has_error + 1;
        $('#form-log-create').find('input[name*="[range_akhir]"]').addClass('error-tb-detail');
    } else {
        $('#form-log-create').find('input[name*="[range_akhir]"]').removeClass('error-tb-detail');
    }

    if(!log_kode){
        has_error = has_error + 1;
        $('#form-log-create').find('input[name*="[log_kode]"]').addClass('error-tb-detail');
    } else {
        $('#form-log-create').find('input[name*="[log_kode]"]').removeClass('error-tb-detail');
    }

    if (has_error === 0) {
		return true;
	}
	return false;
}

</script>