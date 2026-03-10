<?php
app\assets\DatepickerAsset::register($this);
app\assets\BootstrapSelectAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<div class="modal fade" id="modal-harga-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Harga Baru'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-harga-create',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'limbah_kelompok')->dropDownList(\app\models\MDefaultValue::getOptionList('limbah-kelompok'),['class'=>'form-control bs-select','onchange'=>'setDropdownLimbah()'])->label("Kelompok Limbah"); ?>
                        <?php echo $form->field($model, 'limbah_id')->dropDownList(\app\models\MBrgLimbah::getOptionList(),['class'=>'form-control bs-select','prompt'=>''])->label("Nama Limbah"); ?>
                        <?= $form->field($model, 'harga_enduser')->textInput(['class'=>'form-control money-format']); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'harga_tanggal_penetapan',[
                            'template'=>'{label}<div class="col-md-7"><div class="input-group date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                        <?= $form->field($model, 'harga_keterangan')->textarea(); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-harga-create\').modal(\'hide\'); $(\'#table-harga\').dataTable().fnClearTable();")'
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
    setDropdownLimbah();
", yii\web\View::POS_READY); ?>
<script>
function setDropdownLimbah(){
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_id') ?>').addClass('animation-loading');
    var limbah_kelompok = $('#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_kelompok') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/sysadmin/hargalimbah/setDropdownLimbah']); ?>',
		type   : 'POST',
		data   : {limbah_kelompok:limbah_kelompok},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_id') ?>").html(data.html);
            $('#<?= \yii\bootstrap\Html::getInputId($model, 'limbah_id') ?>').removeClass('animation-loading');
            $(".bs-select").selectpicker("refresh");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>