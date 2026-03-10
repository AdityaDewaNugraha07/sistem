<?php app\assets\DatepickerAsset::register($this); ?>
<div class="modal fade" id="modal-master-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Data Dokumen'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-master-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Nomor Dokumen'); ?></label>
                            <div class="col-md-7" style="padding-bottom: 5px;">
                                <span class="input-group-btn" style="width: 20%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode1', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;','disabled'=>true]) ?>
                                </span>
                                <span class="input-group-btn" style="width: 20%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode2', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;','disabled'=>true]) ?>
                                </span>
                                <span class="input-group-btn" style="width: 20%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode3', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;','disabled'=>true]) ?>
                                </span>
                                <span class="input-group-btn" style="width: 20%">
                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode4', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;']) ?>
                                </span>
                            </div>
                        </div>
						<?= $form->field($model, 'jenis_dokumen')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-dokumen'),['class'=>'form-control', 'prompt'=>'','onchange'=>'setKode()']); ?>
                        <?= $form->field($model, 'kategori_dokumen')->textInput(['oninput'=>'setKode()']); ?>
                    </div>
					<div class="col-md-6">
						<?= $form->field($modDokRevisi, 'tanggal_berlaku',[
                            'template'=>'{label}<div class="col-md-4"><div class="input-group date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                        <?= $form->field($model, 'nama_dokumen')->textarea(); ?>
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
<?php //$this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    formconfig();
", yii\web\View::POS_READY); ?>
<script>
    function setKode(){
        var jenis_dokumen = $("#<?= \yii\helpers\Html::getInputId($model, 'jenis_dokumen') ?>").val();
        var kategori_dokumen = $("#<?= \yii\helpers\Html::getInputId($model, 'kategori_dokumen') ?>").val();
        
        $("#<?= \yii\helpers\Html::getInputId($model, 'kode2') ?>").val(jenis_dokumen);
        $("#<?= \yii\helpers\Html::getInputId($model, 'kode3') ?>").val(kategori_dokumen);
    }

    function save(){
        var form = $('#form-master-create');
        if(validatingDetail()){
            submitformajax(form, "$(\'#modal-master-create\').modal(\'hide\'); $(\'#table-master\').dataTable().fnClearTable();");
        }
    }

    function validatingDetail(){
        var has_error = 0;
        var kode2 = $("#<?= \yii\helpers\Html::getInputId($model, 'kode2') ?>").val();
        var kode3 = $("#<?= \yii\helpers\Html::getInputId($model, 'kode3') ?>").val();
        var kode4 = $("#<?= \yii\helpers\Html::getInputId($model, 'kode4') ?>").val();

        if(!kode2){
            $("#<?= \yii\helpers\Html::getInputId($model, 'kode2') ?>").addClass('error-tb-detail');
			has_error = has_error + 1;
        } else {
            $("#<?= \yii\helpers\Html::getInputId($model, 'kode2') ?>").removeClass('error-tb-detail');
        }

        if(!kode3){
            $("#<?= \yii\helpers\Html::getInputId($model, 'kode3') ?>").addClass('error-tb-detail');
			has_error = has_error + 1;
        } else {
            $("#<?= \yii\helpers\Html::getInputId($model, 'kode3') ?>").removeClass('error-tb-detail');
        }

        if(!kode4){
            $("#<?= \yii\helpers\Html::getInputId($model, 'kode4') ?>").addClass('error-tb-detail');
			has_error = has_error + 1;
        } else {
            if(kode4 == '00'){
                $("#<?= \yii\helpers\Html::getInputId($model, 'kode4') ?>").addClass('error-tb-detail');
				has_error = has_error + 1;
            } else {
                $("#<?= \yii\helpers\Html::getInputId($model, 'kode4') ?>").removeClass('error-tb-detail');
            }
        }

        if(has_error === 0){
            return true;
        }
        return false;
    }
</script>