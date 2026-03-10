<?php
/* @var $this yii\web\View */

use app\components\Params;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Daftar Rencana Pembayaran (DRP)';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Daftar Rencana Pembayaran (DRP)'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.table-advance tr td:first-child {
    border-left-width: 1px !important;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <a class="btn blue btn-sm btn-outline pull-right" style="margin-left: 5px;" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'DRP Yang Telah Dibuat'); ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data DRP'); ?></h4></span>
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
										if(!isset($_GET['pengajuan_drp_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);?>
										<?php }else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%; font-weight:bold;', 'disabled'=>'disabled']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        <?php echo $form->field($model, 'tanggal',[
                                                'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime" data-date-start-date="-0d">{input} <span class="input-group-addon">
                                                <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                {error}</div>'])->textInput(['readonly'=>'readonly', 'onchange'=>'setDetail();']);
                                        ?>
                                        <?php 
                                        if(isset($_GET['edit']) || !isset($_GET['pengajuan_drp_id'])){
                                            echo $form->field($model, 'keterangan')->textarea(['class'=>'form-control','rows' => 2]);
                                        } else {
                                            echo $form->field($model, 'keterangan')->textarea(['class'=>'form-control','rows' => 2, 'disabled'=>'disabled']);
                                        }
                                        ?>
                                        <?php if( (isset($_GET['pengajuan_drp_id'])) && ($model->cancel_transaksi_id == NULL) ){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
                                                <?php if($model->status_approve == 'Not Confirmed'){ ?>
														<a href="javascript:void(0);" onclick="cancelDrp(<?= $model->pengajuan_drp_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan DRP'); ?></a>
													<?php }else{ ?>
														<a href="javascript:void(0);" class="btn default btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan DRP'); ?></a>
													<?php } ?>
												</div>
											</div>
										<?php } ?>
										<?php if($model->cancel_transaksi_id != null){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
													<?php
													$modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
													echo "<br><span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena ".$modCancel->cancel_reason."</span>";
													?>
												</div>
											</div>
										<?php } ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->field($model, 'approver_1')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control', 'disabled'=>true]); ?>
                                        <?php echo $form->field($model, 'approver_2')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control', 'disabled'=>true]); ?>
                                        <?php echo $form->field($model, 'approver_3')->dropDownList(\app\models\MPegawai::getOptionListDirektur(),['class'=>'form-control']); ?>
                                        <input type="hidden" id="statusApprove" value="<?= $model->status_approve; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Status Approval</label>
                                            <div class="col-md-8">
                                                <?php
                                                if (isset($_GET['pengajuan_drp_id'])) {
                                                    $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
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
                                                    }
                                                }
                                                ?>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
											<div class="col-md-8">
												<h4><?= Yii::t('app', 'Detail Voucher Pengeluaran'); ?></h4>
											</div>
                                            <div class="col-md-12" style="padding: 0px;">
                                                <div class="table-scrollable">
                                                    <table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail-voucher">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30px;">No.</th>
                                                                <th style="width: 150px;"><?php echo Yii::t('app', 'Kode'); ?></th>
                                                                <th style="width: 150px;"><?php echo Yii::t('app', 'Tipe Voucher'); ?></th>
                                                                <th style="width: 200px;"><?php echo Yii::t('app', 'Penerima Pembayaran'); ?></th>
                                                                <!-- <th style="width: 250px;"><?php echo Yii::t('app', 'Reff'); ?></th> -->
                                                                <th style="width: 500px;"><?php echo Yii::t('app', 'Keterangan Voucher'); ?></th>
                                                                <th style="width: 100px;"><?= Yii::t('app', 'Jumlah'); ?></th>
                                                                <th style="width: 150px;"><?= Yii::t('app', 'Kategori DRP'); ?></th>
                                                                <th style="width: 35px;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <!-- <td></td> -->
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td style="font-weight: bold; text-align: right; vertical-align: middle;">TOTAL PENGAJUAN</td>
                                                                <td><input class="form-control" style="vertical-align: middle; font-size:1.2rem; text-align: right;" id="total" disabled></td>
                                                            </tr>
                                                            <tr id='place-total-disetujui'>
                                                                <!-- <td></td> -->
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td style="font-weight: bold; text-align: right; vertical-align: middle;">TOTAL YANG DISETUJUI</td>
                                                                <td><input class="form-control" style="vertical-align: middle; font-size:1.2rem; text-align: right;" id="totaldisetujui" disabled></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
											</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printDrp('.(isset($_GET['pengajuan_drp_id'])?$_GET['pengajuan_drp_id']:'').');','disabled'=>true]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
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
if(!isset($_GET['pengajuan_drp_id'])){
    $page = '';
} else if(isset($_GET['edit'])){
    $page = 'afterSave(1)';
} else {
    $page = 'afterSave();';
} 
?>
<?php $this->registerJs(" 
	formconfig();
    $('#table-detail-voucher').find('#total').val(0);
    $('#place-total-disetujui').hide();
    $page
", yii\web\View::POS_READY); ?>
<script>
    function setDetail(){
        var tanggal = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
        var edit = <?= isset($_GET['edit'])?$_GET['edit']:'null' ?>;
        var id = <?= isset($_GET['pengajuan_drp_id'])?$_GET['pengajuan_drp_id']:'null' ?>;

        if(tanggal){
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/finance/drp/setDetail']); ?>',
                type   : 'POST',
                data   : {tanggal:tanggal, edit:edit, id:id}, //kategori:kategori, 
                success: function (data) {
                    if(data.html){
                        $('#table-detail-voucher tbody').html(data.html);
                        total();
                        reorder('#table-detail-voucher');
                    }			
                },
                error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
            });
        }
    }

    function afterSave(edit = null){
        // var kategori = $('#<?= yii\bootstrap\Html::getInputId($model, 'kategori') ?>').val();
        var kode = $('#<?= yii\bootstrap\Html::getInputId($model, 'kode') ?>').val();

        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/finance/drp/afterSave']); ?>',
            type   : 'POST',
            data   : {kode: kode, edit: edit}, //kategori:kategori, 
            success: function (data) {
                if(data.html){
                    $('#table-detail-voucher tbody').html(data.html);
                    total();
                    reorder('#table-detail-voucher');

                    $('#<?= yii\bootstrap\Html::getInputId($model, 'approver_1') ?>').val(data.approver_1);
                    $('#<?= yii\bootstrap\Html::getInputId($model, 'approver_2') ?>').val(data.approver_2);
                    $('#<?= yii\bootstrap\Html::getInputId($model, 'approver_3') ?>').val(data.approver_3);
                    
                    if(!edit){
                        $('#btn-print').removeAttr('disabled');
                        $('#btn-save').attr('disabled','');
                        $('#<?= yii\bootstrap\Html::getInputId($model, 'approver_3') ?>').attr('disabled', true);
                        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').attr('disabled', true);
                        $('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
                    } 
                }

                var status_approve = $('#statusApprove').val();
                // var status_pengajuan = $('#statusApprove').val();
                <?php if(!isset($_GET['edit']) || !isset($_GET['pengajuan_drp_id'])){ ?>
                    if(status_approve == 'APPROVED'){
                        $('#place-total-disetujui').show();
                        finalTotal();
                    } else {
                        $('#place-total-disetujui').hide();
                    }
                <?php } ?>
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function cancelItemThis(ele){
        $(ele).parents('tr').fadeOut(300,function(){
            $(this).remove();
            total();
            reorder('#table-detail-voucher');
        });
    }

    function reorder(obj_table){
        var row = 0;
        $(obj_table).find("tbody > tr").each(function(){
            $(this).find("#no_urut").val(row+1);
            $(this).find("span.no_urut").text(row+1);
            row++;
        });
        formconfig();
    }

    function total(){
        var total = 0;
        var i = 0;
        $('#table-detail-voucher tbody tr').each(function(){
            total += unformatNumber($(this).find('#jumlah').val());
            i++;
        });
        $('#table-detail-voucher').find('#total').val(formatNumberForUser(total));
    }

    function daftarAfterSave(){
        openModal('<?= Url::toRoute(['/finance/drp/daftarAfterSave']) ?>','modal-aftersave','90%');
    }

    function save(){
        var $form = $('#form-transaksi');
        if(formrequiredvalidate($form)){
            var jumlah_item = $('#table-detail-voucher tbody tr').length;
            if(jumlah_item <= 0){
                cisAlert('Data voucher pengeluaran kosong');
                return false;
            }
            if(validatingDetail()){			
                submitform($form);
            }
        }
        return false;
    }

    function validatingDetail(){
        var has_error = 0;
        
        $('#table-detail-voucher > tbody > tr').each(function(){
            var field1 = $(this).find('input[name*="[voucher_pengeluaran_id]"]');
            // var field2 = $(this).find('textarea[name*="[reff_ket]"]'); 
            var field3 = $(this).find('textarea[name*="[keterangan]"]');
            var field4 = $(this).find('select[name*="[kategori]"]');
            if(!field1.val()){
                has_error = has_error + 1;
                cisAlert("Tidak terdapat data yang dapat disimpan");
            }
            // if(!field2.val()){
            //     $(this).find('textarea[name*="[reff_ket]"]').parents('td').addClass('error-tb-detail');
            //     has_error = has_error + 1;
            // }else{
            //     $(this).find('textarea[name*="[reff_ket]"]').parents('td').removeClass('error-tb-detail');
            // }
            if(!field3.val()){
                $(this).find('textarea[name*="[keterangan]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1;
            }else{
                $(this).find('textarea[name*="[keterangan]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field4.val()){
                $(this).find('select[name*="[kategori]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1;
            }else{
                $(this).find('select[name*="[kategori]"]').parents('td').removeClass('error-tb-detail');
            }
        })

        if(has_error === 0){
            return true;
        }
        return false;
    }

    function cancelDrp(pengajuan_drp_id){
        openModal('<?php echo Url::toRoute(['/finance/drp/cancelDrp']) ?>?id='+pengajuan_drp_id,'modal-transaksi');
    }

    function printDrp(id){
        window.open("<?= yii\helpers\Url::toRoute('/finance/drp/print') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
    }

    function gkk(id){
        var url = '<?= Url::toRoute(['/kasir/pengeluarankaskecil/detailGkk']); ?>?id='+id;
        $(".modals-place-2").load(url, function() {
            $("#modal-gkk").modal('show');
            $("#modal-gkk").on('hidden.bs.modal', function () { });
            $("#modal-gkk .modal-dialog").css('width',"21cm");
            spinbtn();
            draggableModal();
        });
    }

    function ppk(id){
        var url = '<?= Url::toRoute(['/kasir/ppk/detailppk']); ?>?id='+id;
        $(".modals-place-2").load(url, function() {
            $("#modal-ppk").modal('show');
            $("#modal-ppk").on('hidden.bs.modal', function () { });
            $("#modal-ppk .modal-dialog").css('width',"21cm");
            spinbtn();
            draggableModal();
        });
    }

    function ajuanDinas(id){
        var url = '<?= Url::toRoute(['/purchasinglog/biayagrader/detailAjuanDinas']); ?>?id='+id;
        $(".modals-place-2").load(url, function() {
            $("#modal-ajuandinas").modal('show');
            $("#modal-ajuandinas").on('hidden.bs.modal', function () { });
            $("#modal-ajuandinas .modal-dialog").css('width',"21cm");
            spinbtn();
            draggableModal();
        });
    }
    
    function ajuanMakan(id){
        var url = '<?= Url::toRoute(['/purchasinglog/biayagrader/detailAjuanMakan']); ?>?id='+id;
        $(".modals-place-2").load(url, function() {
            $("#modal-ajuanmakan").modal('show');
            $("#modal-ajuanmakan").on('hidden.bs.modal', function () { });
            $("#modal-ajuanmakan .modal-dialog").css('width',"21cm");
            spinbtn();
            draggableModal();
        });
    }

    

    function setDropdownDrp(){
        var pengajuan_drp_id = $("#<?= yii\helpers\Html::getInputId($model, "pengajuan_drp_id") ?>").val();
        // var kategori = $("#<?= yii\helpers\Html::getInputId($model, "kategori") ?>").val();
        console.log(kategori);
        if(kategori){
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/finance/drp/SetDropdownDrp']); ?>',
                type   : 'POST',
                data   : {pengajuan_drp_id:pengajuan_drp_id}, //kategori:kategori,
                success: function (data) {                    
                    if(data.html_open_voucher){
                        $("#place-open-voucher-awal").remove();
                        $("#place-open-voucher").html(data.html_open_voucher);
                    }
                },
                error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
            });
        }
    }

    function openDrpOperational(){
        openModal('<?= Url::toRoute(['/finance/drp/openDrpOperational']) ?>','modal-aftersave','90%');
    }

    function openDrpLogSengon(){
        openModal('<?= Url::toRoute(['/finance/drp/openDrpLogSengon']) ?>','modal-aftersave','90%');
    }

    function openDrpLogAlam(){
        openModal('<?= Url::toRoute(['/finance/drp/openDrpLogAlam']) ?>','modal-aftersave','90%');
    }

    function finalTotal(){
        var total_disetujui = 0;
        var i = 0;
        $('#table-detail-voucher tbody tr').each(function(){
            total_disetujui += unformatNumber($(this).find('#jumlah_disetujui').val());
            i++;
        });
        $('#table-detail-voucher').find('#totaldisetujui').val(formatNumberForUser(total_disetujui));
    }

</script>