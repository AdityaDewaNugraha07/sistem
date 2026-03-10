<?php 
app\assets\DatepickerAsset::register($this); 
app\assets\Select2Asset::register($this);
app\assets\FileUploadAsset::register($this);
?>
<div class="modal fade" id="modal-create-penawaran" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Penawaran Barang Baru : <b>').$modBhp->bhp_nm."</b>"; ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-create-penawaran',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<?= yii\helpers\Html::activeHiddenInput($model, "bhp_id") ?>
                        <?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled']); ?>
                        <?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-5"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
								<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
						<?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionListBHP(),['class'=>'form-control select2','prompt'=>'All']); ?>
                        <?= $form->field($model, 'bhp_nm')->textInput(['disabled'=>'disabled'])->label("Nama BHP"); ?>
                        <?= $form->field($model, 'keterangan')->textArea(); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'qty')->textInput(['disabled'=>'disabled']); ?>
                        <?= $form->field($model, 'satuan_kecil')->textInput(['disabled'=>'disabled']); ?>
                        <?= $form->field($model, 'harga_satuan')->textInput(['class'=>'form-control float text-align-right']); ?>
						<?php 
                        echo $form->field($model, 'attachment',[
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
                    'onclick'=>'submitformajax(this,"$(\'#modal-create-penawaran\').modal(\'hide\'); $(\'#table-penawaran\').dataTable().fnClearTable();")'
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
$('#".yii\bootstrap\Html::getInputId($model, 'suplier_id')."').select2({
	allowClear: !0,
	placeholder: 'Pilih Supplier',
	width: null
});
$.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>