<?php app\assets\Select2Asset::register($this) ?>
<style>
.panel-body p{
	margin: 10px 0;
}
</style>
<div class="modal fade" id="modal-master-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Extension'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-master-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-3 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-12">
                        <?= $form->field($model, 'ext_kode')->textInput(['style'=>'width:100px;','placeholder'=>'Ext.']); ?>
						<?= $form->field($model, 'pegawai_id')->dropDownList(\app\models\MPegawai::getOptionList(),['prompt'=>'','onchange'=>'setValue()']); ?>
                        <?= $form->field($model, 'nama')->textInput(['placeholder'=>'Ketik Nama Pengguna Telpon']); ?>
                        <?= $form->field($model, 'bagian')->textInput(['placeholder'=>'Ketik Bagian Pengguna Telpon']); ?>
						<?= $form->field($model, 'active',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
                        ->checkbox([],false)->label(Yii::t('app', 'Active')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-master-edit\').modal(\'hide\'); $(\'#table-master\').dataTable().fnClearTable();")'])?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
$('#".\yii\bootstrap\Html::getInputId($model, 'pegawai_id')."').select2({
	allowClear: !0,
	placeholder: 'Pilih Nama Pegawai Jika Ada',
	width: null
});
$.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script>
function setValue(){
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'nama') ?>').addClass('animation-loading');
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'bagian') ?>').addClass('animation-loading');
	var pegawai_id = $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/sysadmin/extensiontelepon/SetValue']); ?>',
		type   : 'POST',
		data   : {pegawai_id:pegawai_id},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'produk_id') ?>").html(data.html);
			$('#<?= \yii\bootstrap\Html::getInputId($model, 'nama') ?>').val(data.nama);
			$('#<?= \yii\bootstrap\Html::getInputId($model, 'bagian') ?>').val(data.dept);
			$('#<?= \yii\bootstrap\Html::getInputId($model, 'nama') ?>').removeClass('animation-loading');
			$('#<?= \yii\bootstrap\Html::getInputId($model, 'bagian') ?>').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>