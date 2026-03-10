<?php
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<div class="modal fade" id="modal-master-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Mutasi Log Sengon'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-transaksi',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-5">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'kode')->textInput(['disabled'=>true,'style'=>'font-weight:600'])->label("Kode Mutasi"); ?>
                        <?= $form->field($model, 'jenis_mutasi')->dropDownList( ["PENERIMAAN SISA KUPAS"=>"PENERIMAAN SISA KUPAS",
																				 "PEMAKAIAN LOG UTUH"=>"PEMAKAIAN LOG UTUH",
                                                                                 "PEMAKAIAN LOG AFKIR"=>"PEMAKAIAN LOG AFKIR",
                                                                                 "AFKIR PT"=>"AFKIR PT",
                                                                                 "PENJUALAN LOG AFKIR"=>"PENJUALAN LOG AFKIR",],
                                    ['prompt'=>'','onchange'=>'setTujuan()'] )->label("Jenis Mutasi"); ?>
                        <?= $form->field($model, 'tanggal',[
                                                            'template'=>'{label}<div class="col-md-7"><div class="input-group input-small date date-picker bs-datetime" data-date-end-date="-0d">{input} <span class="input-group-addon">
                                                            <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                            {error}</div>'])->textInput(['disabled'=>true])->label("Tanggal Mutasi"); ?>
                        <?= $form->field($model, 'dari')->textInput(['disabled'=>true])->label("Dari"); ?>
                        <?= $form->field($model, 'ke')->textInput(['disabled'=>true])->label("Ke"); ?>
                        <?= $form->field($model, 'panjang')->dropDownList(['100'=>'100 Cm','130'=>'130 Cm','200'=>'200 Cm','260'=>'260 Cm'],['prompt'=>'']); ?>
                        <?= $form->field($model, 'diameter')->textInput(['class'=>'form-control float'])->label("Diameter (Cm)"); ?>
                        <?= $form->field($model, 'pcs')->textInput(['class'=>'form-control float'])->label("Jumlah Batang (Pcs)"); ?>
                        <?= $form->field($model, 'm3')->textInput(['class'=>'form-control float'])->label("Jumlah Volume (m<sup>3</sup>)"); ?>
                        <?= $form->field($model, 'keterangan')->textarea()->label("Keterangan"); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'save(this);'
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
    $.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
    setTujuan();
", yii\web\View::POS_READY); ?>
<script type="text/javascript">
function setTujuan(){
    var jenis_mutasi = $("#<?= \yii\helpers\Html::getInputId($model, "jenis_mutasi") ?>").val();
    if(jenis_mutasi == "PEMAKAIAN LOG UTUH"){
        $("#<?= \yii\helpers\Html::getInputId($model, "dari") ?>").val("GUDANG LOG SENGON");
        $("#<?= \yii\helpers\Html::getInputId($model, "ke") ?>").val("PRODUKSI");
    }else if(jenis_mutasi == "BATAL PEMAKAIAN LOG UTUH"){
        $("#<?= \yii\helpers\Html::getInputId($model, "dari") ?>").val("PRODUKSI");
        $("#<?= \yii\helpers\Html::getInputId($model, "ke") ?>").val("GUDANG LOG SENGON");
    }else if(jenis_mutasi == "PEMAKAIAN LOG AFKIR"){
        $("#<?= \yii\helpers\Html::getInputId($model, "dari") ?>").val("GUDANG LOG AFKIR");
        $("#<?= \yii\helpers\Html::getInputId($model, "ke") ?>").val("PRODUKSI");
    }else if(jenis_mutasi == "BATAL PEMAKAIAN LOG AFKIR"){
        $("#<?= \yii\helpers\Html::getInputId($model, "dari") ?>").val("PRODUKSI");
        $("#<?= \yii\helpers\Html::getInputId($model, "ke") ?>").val("GUDANG LOG AFKIR");
    }else if(jenis_mutasi == "AFKIR PT"){
        $("#<?= \yii\helpers\Html::getInputId($model, "dari") ?>").val("GUDANG LOG SENGON");
        $("#<?= \yii\helpers\Html::getInputId($model, "ke") ?>").val("GUDANG LOG AFKIR");
    }else if(jenis_mutasi == "BATAL AFKIR PT"){
        $("#<?= \yii\helpers\Html::getInputId($model, "dari") ?>").val("GUDANG LOG AFKIR");
        $("#<?= \yii\helpers\Html::getInputId($model, "ke") ?>").val("GUDANG LOG SENGON");
    }else if(jenis_mutasi == "PENJUALAN LOG AFKIR"){
        $("#<?= \yii\helpers\Html::getInputId($model, "dari") ?>").val("GUDANG LOG AFKIR");
        $("#<?= \yii\helpers\Html::getInputId($model, "ke") ?>").val("PENJUALAN LOG AFKIR");
    }else if(jenis_mutasi == "PENERIMAAN SISA KUPAS"){
        $("#<?= \yii\helpers\Html::getInputId($model, "dari") ?>").val("PRODUKSI");
        $("#<?= \yii\helpers\Html::getInputId($model, "ke") ?>").val("GUDANG LOG SENGON");
    }
}

function save(ele){
//    submitformajax(ele,"$(\'#modal-master-create\').modal(\'hide\'); $(\'#table-master\').dataTable().fnClearTable();");
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($model, "panjang") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "diameter") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "pcs") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "m3") ?>").parents(".form-group").removeClass("has-error");
    if(formrequiredvalidate($form)){
        if(validatingDetail()){
			submitformajax($form);
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
	var tanggal = $("#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>").val();
	if(!tanggal || tanggal <= 0){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>").parents(".form-group").removeClass("has-success");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>").parents(".form-group").addClass("has-error");
		has_error = has_error + 1;
	}
	var panjang = $("#<?= \yii\bootstrap\Html::getInputId($model, "panjang") ?>").val();
	if(!panjang || panjang <= 0){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "panjang") ?>").parents(".form-group").removeClass("has-success");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "panjang") ?>").parents(".form-group").addClass("has-error");
		has_error = has_error + 1;
	}
	var diameter = $("#<?= \yii\bootstrap\Html::getInputId($model, "diameter") ?>").val();
	if(!diameter || diameter <= 0){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "diameter") ?>").parents(".form-group").removeClass("has-success");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "diameter") ?>").parents(".form-group").addClass("has-error");
		has_error = has_error + 1;
	}
	var pcs = $("#<?= \yii\bootstrap\Html::getInputId($model, "pcs") ?>").val();
	if(!pcs || pcs <= 0){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "pcs") ?>").parents(".form-group").removeClass("has-success");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "pcs") ?>").parents(".form-group").addClass("has-error");
		has_error = has_error + 1;
	}
	var m3 = $("#<?= \yii\bootstrap\Html::getInputId($model, "m3") ?>").val();
	if(!m3 || m3 <= 0){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "m3") ?>").parents(".form-group").removeClass("has-success");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "m3") ?>").parents(".form-group").addClass("has-error");
		has_error = has_error + 1;
	}
    if(has_error === 0){
        return true;
    }
    return false;
}
</script>