<?php
\app\assets\Select2Asset::register($this);
?>
<div class="modal fade" id="modal-master-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Create New Pegawai'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-master-create',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'pegawai_nik')->textInput(); ?>
                        <?= $form->field($model, 'pegawai_nama')->textInput(); ?>
						<?= $form->field($model, 'departement_id')->dropDownList(\app\models\MDepartement::getOptionList(),['class'=>'form-control','prompt'=>'']) ?>
                    </div>
					<div class="col-md-6">
						<?= $form->field($model, 'pegawai_jk')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-kelamin'),['class'=>'form-control','prompt'=>'']) ?>
						<?= $form->field($model, 'jabatan_id')->dropDownList(\app\models\MJabatan::getOptionList(),['class'=>'form-control','prompt'=>'']) ?>
                        <?= $form->field($model, 'pegawai_alamat')->textarea(); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-master-create\').modal(\'hide\'); $(\'#table-master\').dataTable().fnClearTable();")'])?>
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
    $('#".\yii\bootstrap\Html::getInputId($model, 'departement_id')."').select2({
        allowClear: !0,
        placeholder: 'Pilih Departement',
        width: null,
    });
    $('#".\yii\bootstrap\Html::getInputId($model, 'jabatan_id')."').select2({
        allowClear: !0,
        placeholder: 'Pilih Jabatan',
        width: null,
    });
    $('#".\yii\bootstrap\Html::getInputId($model, 'pegawai_jk')."').select2({
        allowClear: !0,
        placeholder: 'Jenis Kelamin',
        width: null,
    });
    $.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script type="text/javascript">

</script>