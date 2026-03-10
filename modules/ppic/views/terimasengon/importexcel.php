<?php
app\assets\DatepickerAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<div class="modal fade" id="modal-importexcel" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Import File Excel'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-importexcel',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10">
                        <?= $form->field($model, 'kode')->textInput(['disabled'=>true,'style'=>'font-weight:600'])->label("Kode Input"); ?>
                        <?= $form->field($model, 'tanggal',[
                                                            'template'=>'{label}<div class="col-md-7"><div class="input-group input-small date date-picker bs-datetime" data-date-end-date="-0d">{input} <span class="input-group-addon">
                                                            <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                            {error}</div>'])->textInput(['disabled'=>true])->label("Tanggal Terima"); ?>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'PO Sengon'); ?></label>
                            <div class="col-md-6">
                                <span class="input-group-btn" style="width: 75%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, "posengon_kode", ['class'=>'form-control','disabled'=>true,'placeholder'=>'Cari Po Sengon']); ?>
                                </span>
                                <span class="input-group-btn" style="width: 25%">
                                    <a class="btn btn-icon-only btn-default tooltips" onclick="cariPosengon();" data-original-title="Cari PO" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-search"></i></a>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <div id="place-berkas-reff"></div>
                            </div>
                        </div>
                        <?= $form->field($model, 'suplier_id')->dropDownList( app\models\MSuplier::getOptionListSuplier(),['prompt'=>'','disabled'=>true] )->label("Suplier"); ?>
                        <?= $form->field($model, 'lokasi_muat')->textInput()->label("Lokasi Muat"); ?>
                        <?= $form->field($model, 'asal_kayu')->textInput()->label("Asal Kayu"); ?>
                        <?= $form->field($model, 'nopol')->textInput()->label("Nopol Kendaraan"); ?>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Total Nota Angkut'); ?></label>
                            <div class="col-md-8">
                                <span class="input-group-btn" style="width: 40%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, "total_notaangkut_pcs", ['class'=>'form-control float']); ?>
                                </span>
                                <span class="input-group-addon" style="width: 5%">Pcs</span>
                                <span class="input-group-addon" style="width: 10%; background-color:#fff; border: 1px solid transparent;"></span>
                                <span class="input-group-btn" style="width: 40%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, "total_notaangkut_m3", ['class'=>'form-control float']); ?>
                                </span>
                                <span class="input-group-addon" style="width: 5%">M<sup>3</sup></span>
                            </div>
                        </div>
                        <?= $form->field($model, 'diperiksa_tally')->dropDownList( app\models\MPegawai::getOptionListByDept( app\components\Params::DEPARTEMENT_ID_PPIC ) ,['prompt'=>'','style'=>'width:100%'] )->label("Diperiksa (Tally)"); ?>
                        <?php echo $form->field($model, 'file',[
                                                        'template'=>'{label}
                                                                    <div class="col-md-3">
                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                            <div class="input-group input-large">
                                                                                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                                                    <i class="fa fa-download fileinput-exists"></i>&nbsp;
                                                                                    <span class="fileinput-filename"> </span>
                                                                                </div>
                                                                                <span class="input-group-addon btn default btn-file">
                                                                                    <span class="fileinput-new"> Select file </span>
                                                                                    <span class="fileinput-exists"> Change </span>
                                                                                    {input}
                                                                                    </span>
                                                                                <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>'
                                                    ])->fileInput()->label("File excel");
                        ?>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 fontsize-1-1 text-align-center">
                        <span class="font-red-flamingo">NOTE : </span>Pastikan format excel sudah diatur sesuai dengan format import!
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-importexcel\').modal(\'hide\'); $(\'#table-laporan\').dataTable().fnClearTable(); ")'
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
    $('select[name*=\"[diperiksa_tally]\"]').select2({
		allowClear: !0,
		placeholder: 'Diperiksa Oleh Tally',
	});
    $.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script type="text/javascript">
</script>