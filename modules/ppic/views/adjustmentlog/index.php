<?php
/* @var $this yii\web\View */
$this->title = 'Adjustment Penerimaan Log';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
(isset($_GET['success']) || isset($_GET['view'])) && isset($_GET['adjustment_log_id']) ? $disabled = "disabled" : $disabled = "";
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
table.table thead tr th{
    font-size: 1.3rem;
    padding: 2px;
    border: 1px solid #A0A5A9;
}
.table-striped.table-bordered.table-hover.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover2.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover3.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover4.table-bordered > thead > tr > th {
    line-height: 1;
}
.add-more:hover {
    background: #58ACFA;
}
</style>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <span class="pull-right">
                        <a class="btn blue btn-sm btn-outline" onclick="daftarAdjustmentLog()"><i class="fa fa-list"></i> <?php echo $this->title; ?></a> 
                    </span>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4> <?php echo $this->title;?> </h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php 
                                    // SUCCESS / VIEW & ADJUSTMENT_LOG_ID
                                    if ( (!isset($_GET['success']) || !isset($_GET['view'])) && !isset($_GET['adjustment_log_id'])){
                                        echo $form->field($model, 'kode')->textInput(['style'=>'font-weight:bold']);
                                    } 
                                    // INDEX
                                    else { 
                                    ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 5px;">
                                                <span class="input-group-btn" style="width: 90%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%','disabled'=>'disabled']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 10%">
                                                    <a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
                                                        <i class="icon-paper-clip"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    <?php 
                                    } 
                                    ?>
                                    <?= $form->field($model, 'tanggal',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                        <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                        {error}</div>'])->textInput([]); ?>

                                    <?php
                                    // EDIT
                                    if (isset($_GET['adjustment_log_id']) && isset($_GET['edit']) ) {
                                    ?>
                                        <?php // t_pengajuan_pembelian_log ;?>
                                        <div id="div_pengajuan_pembelianlog_id" class="form-group" style="margin-bottom: 5px;">
                                            <label id="label_pengajuan_pembelianlog_id" class="col-md-4 control-label"><?= Yii::t('app', 'Kode Keputusan'); ?></label>
                                            <div class="col-md-8">
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'pengajuan_pembelianlog_id', \app\models\TPengajuanPembelianlog::getOptionListLoglistAdjustment(),['class'=>'form-control select2','prompt'=>'','onchange'=>'cariCari(this.value)','style'=>'width:100%;','disabled'=>'disabled']); ?>
                                            </div>
                                        </div>
                                        <?php /* eo t_pengajuan_pembelian_log_id; */?>  

                                        <?php // t_loglist ;?>
                                        <div id="div_reff_no_loglist" class="form-group" style="margin-bottom: 5px;">
                                            <label id="label_reff_no_loglist" class="col-md-4 control-label"><?= Yii::t('app', 'Kode Loglist'); ?></label>
                                            <div class="col-md-8">
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'reff_no_loglist', \app\models\TLoglist::getOptionListAdjustment($model->pengajuan_pembelianlog_id),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;','disabled'=>'disabled']); ?>
                                            </div>
                                        </div>
                                        <?php /* eo t_loglist; */?>  

                                        <?php // t_spk_shipping ;?>
                                        <div id="div_reff_no_spk" class="form-group" style="margin-bottom: 5px;" disabled>
                                            <label id="label_reff_no_loglist" class="col-md-4 control-label"><?= Yii::t('app', 'Kode SPK Shipping'); ?></label>
                                            <div class="col-md-8">
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'reff_no_spk', \app\models\TSpkShipping::getOptionListSpkShippingAdjustment($model->reff_no_spk),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;','disabled'=>'disabled']); ?>
                                            </div>
                                        </div>
                                        <?php /* eo t_spk_shipping; */?>
                                    <?php
                                    // INDEX / VIEW
                                    } else {
                                    ?>

                                        <?php // t_pengajuan_pembelian_log ;?>
                                        <div id="div_pengajuan_pembelianlog_id" class="form-group" style="margin-bottom: 5px;">
                                            <label id="label_pengajuan_pembelianlog_id" class="col-md-4 control-label"><?= Yii::t('app', 'Kode Keputusan'); ?></label>
                                            <div class="col-md-8">
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'pengajuan_pembelianlog_id', \app\models\TPengajuanPembelianlog::getOptionListLoglistAdjustment(),['class'=>'form-control select2','prompt'=>'','onchange'=>'cariCari(this.value)','style'=>'width:100%;']); ?>
                                            </div>
                                        </div>
                                        <?php /* eo t_pengajuan_pembelian_log_id; */?>  

                                        <?php // t_loglist ;?>
                                        <div id="div_reff_no_loglist" class="form-group" style="margin-bottom: 5px; disabled: <?php echo $disabled;?>;">
                                            <label id="label_reff_no_loglist" class="col-md-4 control-label"><?= Yii::t('app', 'Kode Loglist'); ?></label>
                                            <div class="col-md-8">
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'reff_no_loglist', \app\models\TLoglist::getOptionList(".$model->reff_no_loglist."),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;']); ?>
                                            </div>
                                        </div>
                                        <?php /* eo t_loglist; */?>  

                                        <?php // t_spk_shipping ;?>
                                        <div id="div_reff_no_spk" class="form-group" style="margin-bottom: 5px; disabled: <?php echo $disabled;?>;">
                                            <label id="label_reff_no_spk" class="col-md-4 control-label"><?= Yii::t('app', 'Kode SPK Shipping'); ?></label>
                                            <div class="col-md-8">
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'reff_no_spk', \app\models\TSpkShipping::getOptionListSpkShipping(".$model->reff_no_spk."),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;']); ?>
                                            </div>
                                        </div>
                                        <?php /* eo t_spk_shipping; */?>  
                                    <?php
                                    }
                                    ?>
                                    
                                    <div class="form-group">
                                        <label id="status_approval" class="col-md-4 control-label"><?= Yii::t('app', 'Status Approval'); ?></label>
                                        <?php
                                        if ($model->status_approval == "ABORTED") {
                                            $cancel_transaksi = \app\models\TCancelTransaksi::find()->where(['reff_no' => $model->kode])->one();
                                            $cancel_by = $cancel_transaksi->cancel_by;
                                            $cancel_reason = $cancel_transaksi->cancel_reason;
                                            $updated_at = $cancel_transaksi->updated_at;
                                            $cancel_oleh = \app\models\MPegawai::findOne($cancel_by)->pegawai_nama;
                                            ?>
                                            <div class="col-md-8 text-danger text-bold" style="padding-top: 7px; font-size: 10px;">
                                                <span style="line-height: 0px;">ABORTED by <?php echo $cancel_oleh;?></span>
                                                <br>
                                                <span style="line-height: 0px;"><?php echo $cancel_reason;?></span>
                                                <br>
                                                <span style="line-height: 0px;"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($updated_at);?></span>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="form-group" style="margin-top: 10px; margin-bottom: 20px;">
                                        <label id="status_approval" class="col-md-2 control-label"></label>
                                        <div class="col-md-12 text-center">
                                            <?php
                                            if (isset($_GET['adjustment_log_id'])) {
                                                $approves = \yii\helpers\Json::decode($model->approve_reason);
                                                $rejects = \yii\helpers\Json::decode($model->reject_reason);
                                                
                                                if(count($approves)>0){
                                                    foreach($approves as $i => $approve){
                                                        $sql_approval = "select m_pegawai.pegawai_nama from t_approval left join m_pegawai on m_pegawai.pegawai_id = t_approval.assigned_to where reff_no = '".$model->kode."' AND assigned_to = ".$approve['by']."";
                                                        $approval = Yii::$app->db->createCommand($sql_approval)->queryScalar();
                                                        echo \yii\helpers\Html::button( Yii::t('app', 'by : '.$approval.'<br>reason : '.$approve['reason'].'<br>tanggal : '.\app\components\DeltaFormatter::formatDateTimeForUser2($approve['at'])),['class'=>'btn green btn-outline ciptana-spin-btn pull-left text-left', 'style' => 'text-align: left; font-size: 10px; margin-left: 45px;']);
                                                    }
                                                }

                                                if(count($rejects)>0){
                                                    foreach($rejects as $i => $reject){
                                                        $sql_approval = "select m_pegawai.pegawai_nama from t_approval left join m_pegawai on m_pegawai.pegawai_id = t_approval.assigned_to where reff_no = '".$model->kode."' AND assigned_to = ".$reject['by']."";
                                                        $approval = Yii::$app->db->createCommand($sql_approval)->queryScalar();
                                                        echo \yii\helpers\Html::button( Yii::t('app', 'by : '.$approval.'<br>reason : '.$reject['reason'].'<br>tanggal : '.\app\components\DeltaFormatter::formatDateTimeForUser2($reject['at'])),['class'=>'btn red btn-outline ciptana-spin-btn pull-right text-left', 'style' => 'text-align: left; font-size: 10px; margin-right: 45px;']);
                                                    }
                                                }
                                            }
                                            ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <?= $form->field($model, 'jml_batang_loglist')->label('Jml Pcs Loglist')->textInput(['style'=>'text-align: right;','readonly'=>'readonly']); ?>
                                <?= $form->field($model, 'jml_m3_loglist')->textInput(['style'=>'text-align: right;','readonly'=>'readonly']); ?>
                                <?= $form->field($model, 'jml_batang_terima')->label('Jml Pcs Terima')->textInput(['style'=>'text-align: right;']); ?>
                                <?= $form->field($model, 'jml_m3_terima')->textInput(['style'=>'text-align: right;']); ?>
                                <?= $form->field($model, "uraian")->textarea(['rows'=>3]); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php // echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>"printout('PRINT')"]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <h5><?= Yii::t('app', 'Attachment'); ?></h5>
                                <div id="place-attch">
                                    <div class="col-md-2">
                                        <?php
                                        echo $form->field($modAttachment, 'file',[
                                            'template'=>'
                                                <div class="col-md-12">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                            <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                        <div>
                                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                                <span class="fileinput-new"> Select Image </span>
                                                                <span class="fileinput-exists"> Change </span>
                                                                {input} 
                                                            </span> 
                                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                            {error}
                                                        </div>
                                                    </div>
                                                </div>'
                                        ])->fileInput();
                                        ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php
                                        echo $form->field($modAttachment, 'file1',[
                                            'template'=>'
                                                <div class="col-md-12">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                            <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                        <div>
                                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                                <span class="fileinput-new"> Select Image </span>
                                                                <span class="fileinput-exists"> Change </span>
                                                                {input} 
                                                            </span> 
                                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                            {error}
                                                        </div>
                                                    </div>
                                                </div>'
                                        ])->fileInput();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>

<?php
$pagemode = "";
if(isset($_GET['adjustment_log_id']) && !isset($_GET['edit'])){
    $pagemode = "afterSave(".$_GET['adjustment_log_id'].");";
}else if( isset($_GET['adjustment_log_id']) && isset($_GET['edit']) ){
    $pagemode = "afterSave(".$_GET['adjustment_log_id'].",".$_GET['edit'].");";
}else{
    $pagemode = "";
}
?>

<?php $this->registerJs(" 
    $pagemode
    formconfig();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Adjustment Penerimaan Log'))."');
", yii\web\View::POS_READY); ?>

<script>

function cariCari(pengajuan_pembelianlog_id) {
    $("#div_reff_no_loglist").show();
    $("#div_reff_no_spk").show();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/adjustmentlog/cariCari']); ?>',
        type   : 'POST',
        data   : {pengajuan_pembelianlog_id:pengajuan_pembelianlog_id},
        success: function (data){
            if(data){
                $("#div_reff_no_loglist").html(data.loglist);
                $("#div_reff_no_spk").html(data.spk_shipping);
                
                $("#form-transaksi").find('input[name*="[jml_batang_loglist]"]').val(data.jml_batang_loglist);
                $("#form-transaksi").find('input[name*="[jml_m3_loglist]"]').val(data.jml_m3_loglist);
                
                $("#form-transaksi").find('input[name*="[jml_batang_terima]"]').val(data.jml_batang_terima);
                $("#form-transaksi").find('input[name*="[jml_m3_terima]"]').val(data.jml_m3_terima);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function save(){
    var $form = $('#form-transaksi');
    var pengajuan_pembelianlog_id = $("#form-transaksi").find('select[name*="[pengajuan_pembelianlog_id]"]').val();
    var reff_no_loglist = $("#form-transaksi").find('select[name*="[reff_no_loglist]"]').val();
    var reff_no_spk = $("#form-transaksi").find('select[name*="[reff_no_spk]"]').val();
    if (pengajuan_pembelianlog_id > 0 && reff_no_loglist != '' && reff_no_spk != '') {
        if(formrequiredvalidate($form)){
            submitform($form);
        } else {
            return false;
        }
    } else {
        cisAlert('Data belum lengkap diinput');
    }
    return false;
}

function afterSave(id){
    getItemsById(id,"<?= isset($_GET['edit'])?$_GET['edit']:""; ?>");
    <?php if( (isset($_GET['adjustment_log_id'])) && !isset($_GET['edit'])){ ?>
        $('form').find('input').each(function(){ $(this).prop("disabled", true); });
        $('form').find('select').each(function(){ $(this).prop("disabled", true); });
        $('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('.btn-file').remove();
        $('#btn-save').attr('disabled','');
        setTimeout(function(){
            $('a.btn-xs.red').remove();
        },800);

    <?php } ?>
}

function getItemsById(id,edit=null){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/adjustmentlog/getItemsById']); ?>',
        type   : 'POST',
        data   : {id:id,edit:edit},
        success: function (data) {
            if(data.attch){
                $(data.attch).each(function(i,val){
                    var asd = (i==0)?"":i;
                    var src = "<?= Yii::$app->urlManager->baseUrl ?>/uploads/ppic/adjustmentlog/"+val.file_name; 
                    $(".field-tattachment-file"+asd).find("img").attr("src",src);
                    $(".field-tattachment-file"+asd).parents(".col-md-2").removeClass("hidden");
                    if(edit){
                        $(".field-tattachment-file"+asd).find(".btn-file").addClass("hidden");
                        $(".field-tattachment-file"+asd).find(".fileinput.fileinput-new").append("<a class='btn btn-xs btn-outline red-flamingo' onclick='deleteAttch("+val.attachment_id+",\""+asd+"\");'><i class='fa fa-trash-o'></i> Hapus</a>");
                    }
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
    setTimeout(function(){
        formconfig();
    },500);
}

function image(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/ppic/adjustmentlog/image','id'=>'']) ?>'+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-image").modal('show');
		$("#modal-image").on('hidden.bs.modal', function () { });
		$("#modal-image .modal-dialog").css('width',"1024px");
		spinbtn();
		draggableModal();
	});
}

function daftarAdjustmentLog(){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/adjustmentlog/daftarAdjustmentLog']) ?>','modal-daftarAdjustmentLog','90%');
}

function deleteAttch(attachment_id,fileno){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/adjustmentlog/deleteAttch']) ?>?id='+attachment_id+'&fileno='+fileno,'modal-delete-record');
}

function setNormalPickAttch(fileno){
    if(!fileno){
        fileno = "";
    }
    $(".field-tattachment-file"+fileno).find(".btn-file").removeClass("hidden");
    $(".field-tattachment-file"+fileno).find(".fileinput.fileinput-new > a.red-flamingo").remove();
    $(".field-tattachment-file"+fileno).find("img").attr("src","<?= Yii::$app->view->theme->baseUrl; ?>/cis/img/no-image.png");
}

</script>