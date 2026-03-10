<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Pengajuan Perubahan Jenis Kayu';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
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
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); 
?>
<style>
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Pengajuan Perubahan Yang Telah Dibuat'); ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Input Pengajuan Perubahan Jenis Kayu'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
                                    <div class="col-md-6">
                                        <?php 
										if(!isset($_GET['log_rubahjenis_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%; font-weight:bold;']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        <?= $form->field($model, 'tanggal', [
                                                'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(); ?>
                                        <?= $form->field($model, 'peruntukan')->inline()->radioList(['Industri'=>"Industri",'Trading'=>"Trading"]) ?>

                                        <?php if(isset($_GET['log_rubahjenis_id'])){ ?>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
                                                <div class="col-md-8" style="margin-top:7px;">
                                                    <?php 
                                                    if($model->cancel_transaksi_id){?>
                                                        <span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
                                                        <?php
                                                        $modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
                                                        echo "<br><span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena ".$modCancel->cancel_reason."</span>";
                                                        ?>
                                                    <?php } else {
                                                        if($model->status_approve == 'Not Confirmed'){ ?>
                                                        <a href="javascript:void(0);" onclick="cancelTransaksi(<?= $model->log_rubahjenis_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Pengajuan'); ?></a>
                                                    <?php } else { ?>
                                                        <a href="javascript:void(0);" class="btn btn-outline btn-sm grey"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Pengajuan'); ?></a>
                                                    <?php }
                                                    }?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-5">
                                        <?= $form->field($model, 'keterangan')->textarea(); ?>

                                        <?php 
                                        if (isset($_GET['log_rubahjenis_id'])) {
                                            if($model->cancel_transaksi_id == null){?>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="">Status</label>
                                                    <div class="col-md-8">
                                                        <?php
                                                        foreach ($modelApproval as $modApproval) {
                                                            if ($modApproval['status'] == "Not Confirmed") {
                                                                $line_color = "blue-soft";
                                                            } else if ($modApproval['status'] == "APPROVED") {
                                                                $line_color = "green-seagreen";
                                                            } else {
                                                                $line_color = "red";
                                                            }

                                                            $sql_approver = "select pegawai_nama from m_pegawai where pegawai_id = ".$modApproval['assigned_to']."";
                                                            $approver = Yii::$app->db->createCommand($sql_approver)->queryScalar();
                                                            $jam = \app\components\DeltaFormatter::formatDateTimeForUser2($modApproval['updated_at']);
                                                            $approves = \yii\helpers\Json::decode($model->reason_approval);
                                                            $rejects = \yii\helpers\Json::decode($model->reason_rejected);
                                                            if ($modApproval['status'] == "APPROVED") {
                                                                if(count($approves) > 0){
                                                                    foreach($approves as $i => $approve){
                                                                        $by = $approve['by'];
                                                                        if($by == $modApproval['assigned_to']){
                                                                            $reasons = $approve['reason'];
                                                                        }
                                                                    } 
                                                                }
                                                                $reason = "reason : $reasons";
                                                            } else if($modApproval['status'] == "REJECTED") {
                                                                if(count($rejects) > 0){
                                                                    foreach($rejects as $i => $reject){
                                                                        $by = $reject['by'];
                                                                        if($by == $modApproval['assigned_to']){
                                                                            $reasons = $reject['reason'];
                                                                            $reason = "reason : $reasons";
                                                                        } else {
                                                                            $reason = "";
                                                                        }
                                                                    } 
                                                                }
                                                            } else {
                                                                $reason = "";
                                                            }
                                                            echo "<a style='margin-top: 5px;' class='btn btn-outline btn-xs $line_color'><i class=''></i> <b>".$modApproval['status']."</b> <font style='color: #000;'>by <b>$approver</b> <br> at : $jam <br> $reason</font></a>&nbsp";
                                                        } ?>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                        }?>

                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <h4><?= Yii::t('app', 'Detail Log Yang Diubah'); ?></h4>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th><?= Yii::t('app', 'No Barcode / No Lap'); ?></th>
                                                        <th><?= Yii::t('app', 'Jenis Kayu Lama'); ?></th>
                                                        <th><?= Yii::t('app', 'Jenis Kayu Baru'); ?></th>
                                                        <th style="width: 30px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													
												</tbody>
                                            </table>
                                        </div>
                                        <a class="btn btn-xs blue-hoki btn-outline" id="btn-add-item" onclick="addItem()"><i class="fa fa-plus"></i> Add Item</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Print'), ['id' => 'btn-print', 'class' => 'btn blue btn-outline ciptana-spin-btn', 'onclick' => 'printPengajuan(' . (isset($_GET['log_rubahjenis_id']) ? $_GET['log_rubahjenis_id'] : '') . ');', 'disabled' => true]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
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
if(isset($_GET['log_rubahjenis_id'])){
    $pagemode = "afterSaveThis(". $_GET['log_rubahjenis_id'] .");";
}else {
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function addItem() {
    var peruntukan = $("input:radio[name*='[peruntukan]']:checked").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/perubahanjenis/addItem']) ?>?peruntukan='+peruntukan, 'modal-addItem', '50%');
}

function save(){
    var form = $('#form-transaksi');

    var jumlah_item = $('#table-detail tbody tr').length;
    if (jumlah_item <= 0) {
        cisAlert('Pilih log terlebih dahulu');
    }

    if (validatingDetail()){
        submitform(form);
    }

    return false;
}

function validatingDetail(){
    var has_error = 0;

    $('#table-detail tbody > tr').each(function(){
        var kayu_id_new = $(this).find('select[name*="[kayu_id_new]"]');

        if(!kayu_id_new.val()){
			$(this).find('select[name*="[kayu_id_new]"]').parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).find('select[name*="[kayu_id_new]"]').parents('td').removeClass('error-tb-detail');
		}
    })

    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSaveThis(id){
    <?php if(!isset($_GET['edit'])){ ?>
        getItems(id);
        $('#btn-add-item').hide();
    <?php }else{ ?>
        getItems(id,1);
    <?php } ?>
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
    $('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    <?php if(isset($_GET['edit'])){ ?>
        $('#btn-save').prop('disabled',false);
        $('#btn-print').prop('disabled',true);
        $('form').find('input').each(function(){ $(this).prop("disabled", false); });
        $('form').find('select').each(function(){ $(this).prop("disabled", false); });
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'kode') ?>").prop("disabled", true);
        // $("input[name='<?= \yii\bootstrap\Html::getInputName($model, 'peruntukan') ?>']").prop("disabled", true);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'keterangan') ?>").prop("disabled", false);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
    <?php } ?>
}

function getItems(id, edit=null){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/perubahanjenis/getItems']); ?>',
        type   : 'POST',
        data   : {id:id, edit:edit},
        success: function (data) {
            if(data.html){
                $('#table-detail > tbody').html(data.html);
                $('#table-detail tbody > tr').each(function(){
                    $(this).find('select[name*="[kayu_id_new]"]').select2({
                        allowClear: !0,
						placeholder: 'Masukkan nama kayu',
                        width: '100%'
                    });
                    $(this).find('.select2-selection').css('font-size','1.2rem');
					$(this).find('.select2-selection').css('padding-left','5px');
                });
            }
            setTimeout(function(){
                reordertable('#table-detail');
            },500);
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/perubahanjenis/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function cancelTransaksi(log_rubahjenis_id){
    openModal('<?php echo yii\helpers\Url::toRoute(['/ppic/perubahanjenis/cancelTransaksi']) ?>?id='+log_rubahjenis_id,'modal-transaksi');
}

function printPengajuan(id){
    var caraPrint = "PRINT";
    window.open("<?= yii\helpers\Url::toRoute(['/ppic/perubahanjenis/printPengajuan', 'id' => '']) ?>" + id + "&caraprint=" + caraPrint, "", 'location=_new, width=1200px, scrollbars=yes');
}
</script>