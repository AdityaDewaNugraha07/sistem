<?php
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<div class="modal fade" id="modal-harga-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Data Harga'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-harga-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'jenis_produk')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-produk'),['class'=>'form-control','prompt'=>'','onchange'=>'setDropdownProduk()'])->label("Jenis Produk"); ?>
                        <?php echo $form->field($model, 'produk_id')->dropDownList(\app\models\MBrgProduk::getOptionList(),['class'=>'form-control select2','prompt'=>''])->label("Produk"); ?>
                        <?= $form->field($model, 'harga_hpp')->textInput(['class'=>'form-control money-format']); ?>
                        <?= $form->field($model, 'harga_distributor')->textInput(['class'=>'form-control money-format']); ?>
                        <?= $form->field($model, 'harga_agent')->textInput(['class'=>'form-control money-format']); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'harga_enduser')->textInput(['class'=>'form-control money-format']); ?>
                        <?= $form->field($model, 'harga_tanggal_penetapan',[
                            'template'=>'{label}<div class="col-md-7"><div class="input-group date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                        <?= $form->field($model, 'harga_keterangan')->textarea(); ?>
                        <?= $form->field($model, 'active',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                            ->checkbox([],false)->label(Yii::t('app', 'Active')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-harga-edit\').modal(\'hide\'); $(\'#table-harga\').dataTable().fnClearTable();")'])?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    formconfig();
    setDropdownProduk();
    $('#".\yii\bootstrap\Html::getInputId($model, 'produk_id')."').select2({
        allowClear: !0,
        placeholder: 'Pilih Produk',
        width: null
    });
    $.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script>
function setDropdownProduk(){
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_id') ?>').addClass('animation-loading');
    var produk_group = $('#<?= \yii\bootstrap\Html::getInputId($model, 'jenis_produk') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/sysadmin/hargaproduk/setDropdownProduk']); ?>',
		type   : 'POST',
		data   : {produk_group:produk_group},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'produk_id') ?>").html(data.html);
            $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_id') ?>').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>