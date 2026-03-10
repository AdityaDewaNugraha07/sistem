<?php
/* @var $this yii\web\View */
$this->title = 'Open Voucher';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);

use yii\bootstrap\Html;
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Open Voucher'); ?>  <small> Form Pengajuan Pembayaran Kepada Finance </small></h1>
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
.table-advance tr td:first-child {
    border-left-width: 1px !important;
}
.table-detail-terimabhp {
    border-left: 1px solid transparent !important;
}
.table-detail-terimabhp > tbody > tr > td{
    background-color: #e2f1ff; border: 1px solid #303030;
}
.table-detail-terimabhp > tbody > tr > th{
    background-color: #e2f1ff; border: 1px solid #303030;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Voucher Yang Telah Dibuat'); ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Voucher'); ?></h4></span>
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
										if(!isset($_GET['open_voucher_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, "open_voucher_id") ?>
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, "departement_id") ?>
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, "prepared_by") ?>
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, "penerima_reff_table") ?>
                                        <?php //echo $form->field($model, 'departement_nama')->textInput(['class'=>'form-control','disabled'=>true])->label("Dept"); ?>
                                        <?php echo $form->field($model, 'dept_pegawai')->textInput(['class'=>'form-control','disabled'=>true])->label("Dept"); ?>
                                        <?php echo $form->field($model, 'tanggal')->textInput(['class'=>'form-control','disabled'=>true]); ?>
                                        <?php echo $form->field($model, 'tipe')->dropDownList([],['prompt'=>'','class'=>'form-control','onchange'=>'setApprover(); setPenerima();']); ?>
                                        <div id="place-penerima-reff" class="form-group" style="display: none;">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Penerima Pembayaran'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 5px;">
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'penerima_voucher_id', [], ['prompt'=>'','class'=>'form-control','style'=>'width:100%']) ?>
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'penerima_reff_id', [], ['prompt'=>'','class'=>'form-control','style'=>'width:100%']) ?>
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'pegawai_id', [], ['prompt'=>'','class'=>'form-control','style'=>'width:100%']) ?>
                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'kepada', ['class'=>'form-control','disabled'=>true, 'style'=>'width:100%']) ?>
                                            </div>
                                        </div>
                                        <div id="place-reff-no"></div>
                                        <div id="place-berkas-reff"></div>
                                        
                                        <?php if(isset($_GET['open_voucher_id'])){ ?>
                                            <div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
                                                    <?php 
                                                    if($model->cancel_transaksi_id){?>
                                                        <span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
                                                        <?php
                                                        $modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
                                                        echo "<br><span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena ".$modCancel->cancel_reason."</span>";
                                                        ?>
                                                    <?php } else {
                                                        if($model->status_approve == 'Not Confirmed'){ ?>
                                                        <a href="javascript:void(0);" onclick="cancelVoucher(<?= $model->open_voucher_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Voucher'); ?></a>
                                                    <?php }
                                                    }?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->field($model, 'cara_bayar')->dropDownList(app\models\MDefaultValue::getOptionListCustom("cara-bayar-voucher-penerimaan","'Cek','Bilyet Giro'","ASC"),['class'=>'form-control', 'onchange'=>'setPenerima();']); ?>
                                        <?php echo $form->field($model, 'approver_1')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['prompt'=>'','class'=>'form-control']); ?>
                                        <?php echo $form->field($model, 'approver_2')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['prompt'=>'','class'=>'form-control']); ?>
                                        <?php echo $form->field($model, 'keterangan')->textarea(); ?>
                                        <?php echo $form->field($model, 'penerima_voucher_qq')->textarea(); ?>
                                        <?php 
                                        if (isset($_GET['open_voucher_id'])) {
                                            if($model->cancel_transaksi_id == null){
                                        ?>
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

                                                    // $jam = \app\components\DeltaFormatter::formatDateTimeForUser2($modApproval['updated_at']);

                                                    $approves = \yii\helpers\Json::decode($modApproval['keterangan']);
                                                    if(count($approves) > 0){
                                                        foreach($approves as $i => $approve){
                                                            $reason = '<br>reason : '.$approve['reason'];
                                                            $jam = \app\components\DeltaFormatter::formatDateTimeForUser2($approve['at']);
                                                        } 
                                                    } else {
                                                        $reason = '';
                                                        $jam = \app\components\DeltaFormatter::formatDateTimeForUser2($modApproval['updated_at']);
                                                    }
                                                ?>
                                                <a style="margin-top: 5px;" class="btn btn-outline btn-xs <?php echo $line_color;?>"><i class=""></i> <?php echo "<b>".$modApproval['status']."</b> <font style='color: #000;'>by <b>".$approver."</b> <br> at : ".$jam, $reason."</font>";?></a>
                                                <?php /* <font class="td-kecil"><?php echo $approver;?></font> : <a style="margin-top: 5px;" class="btn btn-outline btn-xs <?php echo $line_color;?>"><i class=""></i> <?php echo $modApproval['status'];?></a><br> */ ?>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        }}
                                        ?>
                                    </div>
                                </div><br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Detail Uraian Voucher'); ?></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail">
												<thead>
                                                    <tr>
                                                        <td colspan="2" style="padding: 5px;"></td>
                                                        <td style="padding: 5px;">
                                                            <?php echo Html::activeDropDownList($model, 'mata_uang', \app\models\MDefaultValue::getOptionListLabelValue('mata-uang'),['class'=>'form-control','style'=>'font-size: 1.3rem; padding: 3px; height: 27px;']) ?>
                                                        </td>
                                                        <td style="padding: 5px;"></td>
                                                        <td style="padding: 5px;"></td>
                                                        <td style="padding: 5px;"></td>
                                                        <td style="padding: 5px;"></td>
                                                    </tr>
													<tr>
														<th style="width: 30px; padding: 5px;">No.</th>
														<th style="padding: 5px;">Deskripsi</th>
                                                        <th style="width: 100px; padding: 5px;">Nominal</th>
														<th style="width: 100px; padding: 5px;">PPN</th>
														<th style="width: 100px; padding: 5px;">Pph</th>
														<th style="width: 120px; padding: 5px;">Subtotal</th>
														<th style="width: 50px;"><?= Yii::t('app', 'Cancel'); ?></th>
													</tr>
												</thead>
												<tbody>

												</tbody>
												<tfoot>
													<tr>
														<td colspan="3">
                                                            <a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
                                                            <!-- hidden input npwp -->
                                                            <input type="text" id="npwp" type="text" style="display:none;">
														</td>
														<td colspan="2" style="vertical-align: middle; text-align: right;">
															Total DPP
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_dpp',0,['id'=>'total_dpp','class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
													<tr>
														<td colspan="5" style="vertical-align: middle; text-align: right;">
															Total PPN
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_ppn',0,['id'=>'total_ppn','class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
                                                    <tr>
														<td colspan="5" style="vertical-align: middle; text-align: right;">
															Total PPh
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_pph',0,['id'=>'total_pph','class'=>'form-control float','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
                                                    <tr>
														<td colspan="5" style="vertical-align: middle; text-align: right;">
															Potongan
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?php echo yii\bootstrap\Html::textInput('total_potongan',0,['id'=>'total_potongan','class'=>'form-control float','style'=>'font-size:1.2rem; padding:5px;','onblur'=>'total();']); ?>
														</td>
													</tr>
                                                    <tr>
														<td colspan="5" style="vertical-align: middle; text-align: right;">
															Biaya Tambahan
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('biaya_tambahan',0,['id'=>'biaya_tambahan','class'=>'form-control float','style'=>'font-size:1.2rem; padding:5px;','onblur'=>'total();']); ?>
														</td>
													</tr>
                                                    <tr>
                                                        <td colspan="5" style="vertical-align: middle; text-align: right;" class="font-red-flamingo">
															TOTAL PEMBAYARAN
														</td>
														<td style="vertical-align: middle; text-align: right;">
                                                            <?= yii\bootstrap\Html::textInput('total_pembayaran',0,['id'=>'total_pembayaran','class'=>'form-control float font-red-flamingo','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
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
if(isset($_GET['open_voucher_id'])){
    $pagemode = "setTimeout(function(){ afterSaveThis(); },1000)";
}else {
    $pagemode = "addItem(); ";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
    SetDDTipeOVByDept();
    $(this).find('select[name*=\"[approver_1]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
		width: null
	});
    $(this).find('select[name*=\"[approver_2]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
		width: null
	});
", yii\web\View::POS_READY); ?>
<script>
function SetDDTipeOVByDept(){
    var dept_id = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    var open_voucher_id = $("#<?= yii\helpers\Html::getInputId($model, "open_voucher_id") ?>").val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/SetDDTipeOVByDept']); ?>',
		type   : 'POST',
		data   : {dept_id:dept_id,open_voucher_id:open_voucher_id},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'tipe') ?>").html(data.html);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setPenerima(){
    var open_voucher_id = $("#<?= yii\helpers\Html::getInputId($model, "open_voucher_id") ?>").val();
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    var cara_bayar = $("#<?= yii\helpers\Html::getInputId($model, "cara_bayar") ?>").val();
    // console.log(cara_bayar);
    $("#place-penerima-reff").hide();
    // $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").html("");
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2();
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").html("");
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").hide();
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").prop("disabled",false);
    $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("");
    $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").select2();
    $("#<?= yii\bootstrap\Html::getInputId($model, "kepada") ?>").val("");
    $("#<?= yii\bootstrap\Html::getInputId($model, "kepada") ?>").hide();
    $("#place-reff-no").html("");
    $("#place-berkas-reff").html("");
    
    if(tipe){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/SetDDPenerima']); ?>',
            type   : 'POST',
            data   : {tipe:tipe,open_voucher_id:open_voucher_id, cara_bayar: cara_bayar},
            success: function (data) {
                $("#place-penerima-reff").show();
                
                if(data.html_penerima_reff){
                    $("#place-reff-no").html(data.html_penerima_reff);
                    $("#place-reff-no").find(".tooltips").tooltip({ delay: 50 });
                }
                if(data.html_berkas_reff){
                    $("#place-berkas-reff").html(data.html_berkas_reff);
                }
                
                if(tipe == "REGULER"){
                    if(cara_bayar == "Transfer Bank"){
                        $("#<?= \yii\helpers\Html::getInputId($model, "penerima_voucher_id") ?>").html(data.html);
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").show();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2({
                            placeholder: 'Ketik Nama Penerima',
                            width: null,
                            minimumInputLength: 1,
                        });
                        $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").select2('destroy');
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_penerima_voucher");
                        <?php if(isset($_GET['open_voucher_id'])){ ?>
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_voucher_id') ?>").val(data.penerima_voucher_id).trigger('change');
                            <?php if(isset($_GET['edit'])){ ?>
                                $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(null);
                                // $("#<?= \yii\helpers\Html::getInputId($model, "penerima_voucher_id") ?>").html(data.html);
                                $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2({
                                    placeholder: 'Ketik Nama Penerima',
                                    width: null,
                                    minimumInputLength: 1,
                                });
                                $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").prop("disabled",false);
                            <?php } ?>
                        <?php } ?>
                    } else if(cara_bayar == "Tunai"){
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2('destroy');
                        <?php if(isset($_GET['open_voucher_id']) && !isset($_GET['edit'])){ ?>
                            $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").html(data.html);
                            $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").show();
                        <?php } else { ?>
                            $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").prop("disabled",false);
                            $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").html(data.html);
                            $('#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>').select2({
                                placeholder: 'Ketik Nama Pegawai',
                                width: null,
                                minimumInputLength: 1,
                            });
                        <?php } ?>
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_pegawai");
                    }
                    $("#total_pph").prop('disabled', false);
                }else if(tipe == "PEMBAYARAN LOG ALAM"){
                    if(cara_bayar == "Transfer Bank"){
                        $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").html(data.html);
                        $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").show();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").prop("disabled",true);
                        $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").select2('destroy');
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2('destroy');
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_suplier");
                        <?php if(isset($_GET['edit'])){ ?>
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.penerima_reff_id);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(null);
                        <?php } ?>
                    } else {
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2('destroy');
                        <?php if(isset($_GET['open_voucher_id']) && !isset($_GET['edit'])){ ?>
                            $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").html(data.html);
                            $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").show();
                        <?php } else { ?>
                            $("#<?= \yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").prop("disabled",false);
                            $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").html(data.html);
                            $('#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>').select2({
                                placeholder: 'Ketik Nama Pegawai',
                                width: null,
                                minimumInputLength: 1,
                            });
                        <?php } ?>
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_pegawai");
                    }
                    $("#total_pph").prop('disabled', false);
                }else if(tipe == "DEPOSIT SUPPLIER LOG"){
                    if(cara_bayar == "Transfer Bank"){
                        $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").html(data.html);
                        $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").show();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").prop("disabled",true);
                        $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").select2('destroy');
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2('destroy');
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_suplier");
                        <?php if(isset($_GET['edit'])){ ?>
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.penerima_reff_id);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(null);
                        <?php } ?>
                    } else {
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2('destroy');
                        <?php if(isset($_GET['open_voucher_id']) && !isset($_GET['edit'])){ ?>
                            $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").html(data.html);
                            $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").show();
                        <?php } else { ?>
                            $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").html(data.html);
                            $('#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>').select2({
                                placeholder: 'Ketik Nama Pegawai',
                                width: null,
                                minimumInputLength: 1,
                            });
                        <?php } ?>
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_pegawai");
                    }
                    $("#total_pph").prop('disabled', true);
                }else if(tipe == "DP LOG SENGON" || tipe == "PELUNASAN LOG SENGON"){
                    $('#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>').select2({
                        placeholder: 'Ketik Kode Tagihan',
                        width: null
                    });
                    if(cara_bayar == "Transfer Bank"){
                        $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").html(data.html);
                        $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").show();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>").prop("disabled",true);
                        $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").select2('destroy');
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2('destroy');
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_suplier");
                        <?php if(isset($_GET['edit'])){ ?>
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.penerima_reff_id);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(null);
                            if(data.reff_no2){
                                var reff_no2 = data.reff_no2.split(',');
                                setDDReff2(tipe, data.reff_no);
                                setTimeout(function(){
                                    $("#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>").val( reff_no2 ).change();
                                    <?php if( isset($_GET['open_voucher_id']) && !isset($_GET['edit']) ){ ?>
                                        setTimeout(function(){
                                            $("#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>").prop("disabled",true);
                                            $('form').find('input').each(function(){ $(this).prop("disabled", true); });
                                            $('form').find('textarea').each(function(){ $(this).prop("disabled", true); });
                                        },500);
                                    <?php } ?>
                                },500);
                            }
                        <?php } ?>
                    } else {
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2('destroy');
                        <?php if(isset($_GET['open_voucher_id']) && !isset($_GET['edit'])){ ?>
                            $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").html(data.html);
                            $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").show();
                        <?php } else { ?>
                            $("#<?= \yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").prop("disabled",false);
                            $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").html(data.html);
                            $('#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>').select2({
                                placeholder: 'Ketik Nama Pegawai',
                                width: null,
                                minimumInputLength: 1,
                            });
                            <?php if(isset($_GET['edit'])){ ?>
                                if(data.reff_no2){
                                    var reff_no2 = data.reff_no2.split(',');
                                    setDDReff2(tipe, data.reff_no);
                                    setTimeout(function(){
                                        $("#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>").val( reff_no2 ).change();
                                        <?php if( isset($_GET['open_voucher_id']) && !isset($_GET['edit']) ){ ?>
                                            setTimeout(function(){
                                                $("#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>").prop("disabled",true);
                                                $('form').find('input').each(function(){ $(this).prop("disabled", true); });
                                                $('form').find('textarea').each(function(){ $(this).prop("disabled", true); });
                                            },500);
                                        <?php } ?>
                                    },500);
                                }
                            <?php } ?>
                        <?php } ?>
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_pegawai");
                    }
                    $("#total_pph").prop('disabled', true);
                } else if(tipe == "PEMBAYARAN ASURANSI LOG SHIPPING"){
                    if(cara_bayar == "Transfer Bank"){
                        $("#<?= yii\bootstrap\Html::getInputId($model, "kepada") ?>").show();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").select2('destroy');
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2('destroy');
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("t_asuransi");
                        <?php if(isset($_GET['edit'])){ ?>
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'kepada') ?>").val(data.penerima_reff_id);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(null);
                        <?php } ?>
                    } else {
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").hide();
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_voucher_id") ?>").select2('destroy');
                        <?php if(isset($_GET['open_voucher_id']) && !isset($_GET['edit'])){ ?>
                            $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").html(data.html);
                            $("#<?= yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").show();
                        <?php } else { ?>
                            $("#<?= \yii\bootstrap\Html::getInputId($model, "pegawai_id") ?>").prop("disabled",false);
                            $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").html(data.html);
                            $('#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>').select2({
                                placeholder: 'Ketik Nama Pegawai',
                                width: null,
                                minimumInputLength: 1,
                            });
                        <?php } ?>
                        $("#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_table") ?>").val("m_pegawai");
                    }
                    $("#total_pph").prop('disabled', false);
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}
function setApprover(){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/setApprover']); ?>',
		type   : 'POST',
		data   : {tipe:tipe},
		success: function (data) {
            $("#<?= yii\bootstrap\Html::getInputId($model, "approver_1") ?>").val(data.approver_1).trigger('change');
            $("#<?= yii\bootstrap\Html::getInputId($model, "approver_2") ?>").val(data.approver_2).trigger('change');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function openKeputusan(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/daftarAfterSave','pick'=>'1']) ?>','modal-aftersave','90%');
}
function openPOSengon(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/cariPoSengon','pick'=>'1']) ?>','modal-posengon','90%');
}
function openTagihanSengon(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/tagihansengon/daftarAfterSave','pick'=>'1']) ?>','modal-aftersave','90%');
}
function pick(kode,suplier_id){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    $('#table-detail > tbody').html( "" ); 
    addItem();
    total();
	$("#modal-aftersave").find('button.fa-close').trigger('click');
	$("#modal-posengon").find('button.fa-close').trigger('click');
	$('#<?= yii\bootstrap\Html::getInputId($model, "reff_no") ?>').val(kode);
    if(tipe=="PEMBAYARAN ASURANSI LOG SHIPPING"){
        $('#<?= yii\bootstrap\Html::getInputId($model, "kepada") ?>').val(suplier_id);
    }else{
        $('#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>').val(suplier_id);
    }
    $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple');
    if(tipe == "PEMBAYARAN LOG ALAM" || tipe == "DP LOG SENGON" || tipe == "PELUSANASAN LOG SENGON"){
        $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+suplier_id+'")');
    }
//    if(terima_sengon_id){
//        $("#place-berkas-reff").find('#btn-reff-3').removeClass('grey').addClass('green-seagreen').attr('onclick','detailTerima('+terima_sengon_id+')');
//    }
    setDDReff2(tipe,kode);

    //var suplier_id = $('#topenvoucher-penerima_reff_id').val();
    var suplier_id = suplier_id;
    if(tipe == "PEMBAYARAN ASURANSI LOG SHIPPING"){

    } else {
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/cekNPWP']); ?>',
            type   : 'POST',
            data   : { suplier_id:suplier_id },
            success: function (data){
                var npwp = data.npwp;
                $('#npwp').val(npwp); 
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}
function pickTagihanSengon(kode,kode_po,suplier_id,posengon_id,tagihan_sengon_id,terima_sengon_id){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
	$("#modal-aftersave").find('button.fa-close').trigger('click');
	$("#modal-posengon").find('button.fa-close').trigger('click');
	$('#<?= yii\bootstrap\Html::getInputId($model, "reff_no") ?>').val(kode_po);
	$('#<?= yii\bootstrap\Html::getInputId($model, "reff_no2") ?>').val(kode);
	$('#<?= yii\bootstrap\Html::getInputId($model, "penerima_reff_id") ?>').val(suplier_id);
    $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple').attr('onclick','detailPo("'+posengon_id+'")');
    $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+suplier_id+'")');
    if(terima_sengon_id){
        $("#place-berkas-reff").find('#btn-reff-3').removeClass('grey').addClass('green-seagreen').attr('onclick','detailTerima('+terima_sengon_id+')');
    }

    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/getItemsByTagihanSengon']); ?>',
        type   : 'POST',
        data   : { tagihan_sengon_id:tagihan_sengon_id },
        success: function (data){
            if(data.html){
                $('#table-detail > tbody').html( data.html );
                reordertable('#table-detail');
                total();
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setDDReff2(tipe,kode){
    var open_voucher_id = "<?= isset($_GET['open_voucher_id'])?$_GET['open_voucher_id']:'' ?>";
    if(tipe == "PELUNASAN LOG SENGON"){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/setDDReff2']); ?>',
            type   : 'POST',
            data   : { tipe:tipe,kode:kode,open_voucher_id:open_voucher_id },
            success: function (data){
                if(data.html){
                    $("#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>").html(data.html);
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}
function setReff2(){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    var reff_no2 = $("#<?= yii\helpers\Html::getInputId($model, "reff_no2") ?>").val();
    var reff_no = $("#<?= yii\helpers\Html::getInputId($model, "reff_no") ?>").val();
    $('#table-detail > tbody').html("");
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/setReff2']); ?>',
        type   : 'POST',
        data   : { tipe:tipe,reff_no2:reff_no2, reff_no:reff_no },
        success: function (data){
            if(tipe == "PELUNASAN LOG SENGON"){
                if(data.html){
                    $('#table-detail > tbody').html(data.html);
                    reordertable('#table-detail');
                    // ambil data npwp untuk persentase total pph
                    npwp = data.npwp;

                    // masukkan nilai npwp ke fungsi total
                    total(npwp);
                }
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function addItem(){
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/addItem']); ?>',
        type   : 'POST',
        data   : {  },
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail > tbody').fadeIn(100,function(){
                    reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function total(npwp){
    var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    var total_dpp = 0; var total_ppn = 0; var total_pph = 0; var total = 0; 
    var potongan = unformatNumber( $("#table-detail").find('input[name="total_potongan"]').val() );
    var biaya_tambahan = unformatNumber( $("#table-detail").find('input[name="biaya_tambahan"]').val() );

    $("#table-detail > tbody > tr").each(function(i){
        var nominal = unformatNumber( $(this).find('input[name*="[nominal]"]').val() );
        var ppn = unformatNumber( $(this).find('input[name*="[ppn]"]').val() );
        var pph = unformatNumber( $(this).find('input[name*="[pph]"]').val() );
        var subtotal = (nominal + ppn) - pph;
        total_dpp += nominal;
        total_ppn += ppn;

        // total pph lama dihitung dari pph
        //total_pph += pph;
        //total_pph = Math.floor(total_pph);

        // total pph baru dihitung langsung dari persentase total_dpp
        // jika ada npwp, total pph dihitung sebesar 0.25%
        // jika tanpa npwp, total pph dihitung sebear 0.5%

        // agar data (nominal) yang berubah saat edit bisa tersimpan 
        <?php 
        // isset($_GET['success']) ? $get_success = $_GET['success'] : $get_success = '';
        // isset($_GET['edit']) ? $get_edit = $_GET['edit'] : $get_edit = '';
        // isset($_GET['open_voucher_id']) ? $open_voucher_id = $_GET['open_voucher_id'] : $open_voucher_id = '';
        ?>

        // var get_success = "<?php //echo $get_success;?>";
        // var get_edit = "<?php //echo $get_edit;?>";
        // var open_voucher_id = "<?php //echo $open_voucher_id;?>";
        
        // if (get_success == 1 || get_edit == 1 || open_voucher_id != '') {
        //     var open_voucher_id = "<?php //echo $open_voucher_id;?>";
        //     $.ajax({
        //         url    : '<?php //echo \yii\helpers\Url::toRoute(['/finance/openvoucher/success']); ?>',
        //         type   : 'POST',
        //         data   : { open_voucher_id:open_voucher_id },
        //         success: function (data){
        //             if(data){
        //                 $('#total_dpp').val(formatNumberForUser(data.total_dpp));
        //                 $('#total_ppn').val(formatNumberForUser(data.total_ppn));
        //                 $('#total_pph').val(formatNumberForUser(data.total_pph));
        //                 $('#total_potongan').val(formatNumberForUser(data.total_potongan));
        //                 $('#biaya_tambahan').val(formatNumberForUser(data.biaya_tambahan));
        //                 $('#total_pembayaran').val(formatNumberForUser(data.total_pembayaran));
        //             }
        //         },
        //         error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        //     });
        // } else {

            if (tipe == 'DP LOG SENGON' || tipe == 'PELUNASAN LOG SENGON') {
                var npwp = $('#npwp').val();
                if (pph > 0 && (npwp == '' || npwp == null || npwp == 0)) {
                    // total_pph = Math.floor(total_dpp * (0.5/100));
                    total_pph = Math.floor(total_dpp * (0.25/100)); //perubahan sesuai memo finance per tanggal 22 juli 2024
                } else if (pph > 0 && (npwp != '' || npwp != null || npwp != 0)) {
                    total_pph = Math.floor(total_dpp * (0.25/100));
                } else {
                    total_pph = 0;
                }
            } else {
                total_pph += pph;
            }
        // }
        
        $(this).find('input[name*="[subtotal]"]').val( formatNumberForUser(subtotal) );
        if (i+1 === $("#table-detail > tbody > tr").length) { // fire after loop
            total = (total_dpp + total_ppn) - total_pph;
            total = total - potongan + biaya_tambahan;
            $("#table-detail").find('input[name="total_dpp"]').val( formatNumberForUser(total_dpp) );
            $("#table-detail").find('input[name="total_ppn"]').val( formatNumberForUser(total_ppn) );
            $("#table-detail").find('input[name="total_pph"]').val( formatNumberForUser(total_pph) );
            $("#table-detail").find('input[name="total_pembayaran"]').val( formatNumberForUser(total) );
        }
    });
    
    $("#table-detail").find('input[name="total_dpp"]').val( formatNumberForUser(total_dpp) );
    $("#table-detail").find('input[name="total_ppn"]').val( formatNumberForUser(total_ppn) );
    $("#table-detail").find('input[name="total_pph"]').val( formatNumberForUser(total_pph) );
    $("#table-detail").find('input[name="total_pembayaran"]').val( formatNumberForUser(total) );
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
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
	var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    var cara_bayar = $("#<?= yii\helpers\Html::getInputId($model, "cara_bayar") ?>").val();
    var pegawai_id = $("#<?= yii\helpers\Html::getInputId($model, "pegawai_id") ?>").val();
    var penerima_voucher_id = $("#<?= yii\helpers\Html::getInputId($model, "penerima_voucher_id") ?>").val();
    var penerima_reff_id = $("#<?= yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").val();
    var kepada = $("#<?= yii\helpers\Html::getInputId($model, "kepada") ?>").val();
    if(tipe == "PEMBAYARAN LOG ALAM"){
        if(!$("input[name*='[reff_no]']").val()){
            $("input[name*='[reff_no]']").parents(".form-group").addClass("error-tb-detail");
            has_error = has_error + 1;
        }else{
            $("input[name*='[reff_no]']").parents(".form-group").removeClass();
        }
    }else if(tipe == "DP LOG SENGON"){
        if(!$("input[name*='[reff_no]']").val()){
            $("input[name*='[reff_no]']").parents(".form-group").addClass("error-tb-detail");
            has_error = has_error + 1;
        }else{
            $("input[name*='[reff_no]']").parents(".form-group").removeClass();
        }
    }else if(tipe == "PELUNASAN LOG SENGON"){
        if(!$("input[name*='[reff_no]']").val()){
            $("input[name*='[reff_no]']").parents(".form-group").addClass("error-tb-detail");
            has_error = has_error + 1;
        }else{
            $("input[name*='[reff_no]']").parents(".form-group").removeClass();
        }
        if(!$("select[name*='[reff_no2]']").val()){
            $("select[name*='[reff_no2]']").parents(".form-group").addClass("error-tb-detail");
            has_error = has_error + 1;
        }else{
            $("select[name*='[reff_no2]']").parents(".form-group").removeClass();
        }
    }else if(tipe == "PEMBAYARAN ASURANSI LOG SHIPPING"){
        if(!$("input[name*='[reff_no]']").val()){
            $("input[name*='[reff_no]']").parents(".form-group").addClass("error-tb-detail");
            has_error = has_error + 1;
        }else{
            $("input[name*='[reff_no]']").parents(".form-group").removeClass();
        }
    }else{
        $("input[name*='[reff_no]']").parents(".form-group").removeClass();
    }

    if(cara_bayar=="Tunai" && !pegawai_id ){
        $("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").addClass('error-tb-detail');
        has_error = has_error + 1;
    }else{
		$("#<?= \yii\helpers\Html::getInputId($model, "pegawai_id") ?>").removeClass('error-tb-detail');
    }

    if(tipe == "REGULER"){
        if(cara_bayar=="Transfer Bank" && !penerima_voucher_id ){
            $("#<?= \yii\helpers\Html::getInputId($model, "penerima_voucher_id") ?>").addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $("#<?= \yii\helpers\Html::getInputId($model, "penerima_voucher_id") ?>").removeClass('error-tb-detail');
        }
    } else if(tipe == "PEMBAYARAN ASURANSI LOG SHIPPING"){
        if(cara_bayar=="Transfer Bank" && !kepada ){
            $("#<?= \yii\helpers\Html::getInputId($model, "kepada") ?>").addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $("#<?= \yii\helpers\Html::getInputId($model, "kepada") ?>").removeClass('error-tb-detail');
        }
    }else {
        if(cara_bayar=="Transfer Bank" && !penerima_reff_id ){
            $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $("#<?= \yii\helpers\Html::getInputId($model, "penerima_reff_id") ?>").removeClass('error-tb-detail');
        }
    }

    $('#table-detail > tbody > tr').each(function(){
        var field1 = $(this).find('textarea[name*="[deskripsi]"]');
        if(!field1.val()){
            $(this).find('textarea[name*="[deskripsi]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('textarea[name*="[deskripsi]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    console.log(has_error);
    
	<?php //if(isset($_GET['edit'])){ ?>
		//has_error = 0;
	<?php //} ?>
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSaveThis(id){
	<?php if(!isset($_GET['edit'])){ ?>
        setHeaderAfterSave();
		getItems(id);
		$('#btn-add-item').hide();
	<?php }else{ ?>
        setHeaderAfterSave(1);
		getItems(id,1);
	<?php } ?>
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if(isset($_GET['edit'])){ ?>
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'tipe') ?>").prop("disabled", false);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'cara_bayar') ?>").prop("disabled", false);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'approver_1') ?>").prop("disabled", false);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'approver_2') ?>").prop("disabled", false);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'keterangan') ?>").prop("disabled", false);
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_voucher_qq') ?>").prop("disabled", false);
        $("input[name*='total_potongan']").prop("disabled", false);
        $("input[name*='biaya_tambahan']").prop("disabled", false);
	<?php } ?>
}

function setHeaderAfterSave(edit){
    var open_voucher_id = $("#<?= yii\helpers\Html::getInputId($model, "open_voucher_id") ?>").val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/setHeaderAfterSave']); ?>',
		type   : 'POST',
		data   : {open_voucher_id:open_voucher_id},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'tipe') ?>").val(data.model.tipe);
			$("input[name*='total_potongan']").val( formatNumberForUser(data.model.total_potongan) );
			$("input[name*='biaya_tambahan']").val( formatNumberForUser(data.model.biaya_tambahan) );
            setPenerima();
            $("#place-reff-no").html(data.html);
            $("#place-reff-no").find(".tooltips").tooltip({ delay: 50 });
            <?php if((isset($_GET['open_voucher_id']))&&(!isset($_GET['edit']))){ ?>
                setTimeout(function(){
                    $("#<?= \yii\bootstrap\Html::getInputId($model, 'reff_no') ?>").parents(".form-group").find(".input-group-btn:last").addClass("hidden");
                },600);
            <?php } ?>
            <?php if(isset($_GET['open_voucher_id'])){ ?>
                setTimeout(function(){
                    $("#<?= \yii\bootstrap\Html::getInputId($model, 'dept_pegawai') ?>").val(data.dept_pegawai);
                    if(edit){
                        $("#<?= \yii\bootstrap\Html::getInputId($model, 'mata_uang') ?>").prop("disabled",false);
                    }
                    if(data.model.tipe == "REGULER"){
                        if(data.model.cara_bayar == "Transfer Bank"){
                            if(edit){
                                $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_voucher_id') ?>").prop("disabled",false);
                            } else {
                                $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_voucher_id') ?>").prop("disabled",true);
                            }
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_voucher_id') ?>").val(data.model.penerima_voucher_id);
                        }else{
                            if(edit){
                                $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").prop("disabled",false);
                            } else {
                                $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").prop("disabled",true);
                            }
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(data.model.pegawai_id).trigger('change');
                        }
                    }else if(data.model.tipe == "PEMBAYARAN LOG ALAM"){
                        if(data.model.cara_bayar == "Transfer Bank"){
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").prop("disabled",true);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.model.penerima_reff_id);
                            $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+data.model.penerima_reff_id+'")');
                        }else{
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").prop("disabled",true);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(data.model.pegawai_id).trigger('change');
                        }
                        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple');
                    }else if(data.model.tipe == "DEPOSIT SUPPLIER LOG"){
                        if(data.model.cara_bayar == "Transfer Bank"){
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").prop("disabled",true);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.model.penerima_reff_id);
                        }else{
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").prop("disabled",true);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(data.model.pegawai_id).trigger('change');
                        }
                    }else if(data.model.tipe == "DP LOG SENGON"){
                        if(data.model.cara_bayar == "Transfer Bank"){
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").prop("disabled",true);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.model.penerima_reff_id);
                            $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+data.model.penerima_reff_id+'")');
                        }else{
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").prop("disabled",true);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(data.model.pegawai_id).trigger('change');
                        }
                        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple');
                    }else if(data.model.tipe == "PELUNASAN LOG SENGON"){
                        if(data.model.cara_bayar == "Transfer Bank"){
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").prop("disabled",true);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'penerima_reff_id') ?>").val(data.model.penerima_reff_id);
                            $("#place-berkas-reff").find('#btn-reff-2').removeClass('grey').addClass('blue-soft').attr('onclick','riwayatSaldoSuplierSengon("'+data.model.penerima_reff_id+'")');
                        }else{
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").prop("disabled",true);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(data.model.pegawai_id).trigger('change');
                        }
                        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple');
                        if(data.modTagihan.terima_sengon_id){
                            $("#place-berkas-reff").find('#btn-reff-3').removeClass('grey').addClass('green-seagreen').attr('onclick','detailTerima('+data.modTagihan.terima_sengon_id+')');
                        }
                        if(data.model.reff_no2){
                            var reff_no2 = data.model.reff_no2.split(',');
                            setDDReff2(data.model.tipe,data.model.reff_no);
                            setTimeout(function(){
                                $("#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>").val( reff_no2 ).change();
                                <?php if( isset($_GET['open_voucher_id']) && !isset($_GET['edit']) ){ ?>
                                    setTimeout(function(){
                                        $("#<?= \yii\helpers\Html::getInputId($model, "reff_no2") ?>").prop("disabled",true);
                                        $('form').find('input').each(function(){ $(this).prop("disabled", true); });
                                        $('form').find('textarea').each(function(){ $(this).prop("disabled", true); });
                                    },500);
                                <?php } ?>
                            },500);
                        }
                    } else if(data.model.tipe == "PEMBAYARAN ASURANSI LOG SHIPPING"){
                        if(data.model.cara_bayar == "Transfer Bank"){
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'kepada') ?>").prop("disabled",true);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'kepada') ?>").val(data.kepada);
                        }else{
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").prop("disabled",true);
                            $("#<?= \yii\bootstrap\Html::getInputId($model, 'pegawai_id') ?>").val(data.model.pegawai_id).trigger('change');
                        }
                        $("#place-berkas-reff").find('#btn-reff-1').removeClass('grey').addClass('purple');
                    }
                },600);
            <?php } ?>
            
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function getItems(open_voucher_id,edit=null){
var open_voucher_id = $("#<?= yii\helpers\Html::getInputId($model, "open_voucher_id") ?>").val();
var tipe = $("#<?= yii\helpers\Html::getInputId($model, "tipe") ?>").val();
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/getItems']); ?>',
		type   : 'POST',
		data   : {open_voucher_id:open_voucher_id,edit:edit},
		success: function (data) {
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			setTimeout(function(){
                reordertable('#table-detail');
				total();
			},500);

		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/daftarAfterSave']) ?>','modal-aftersave-this','95%');
}
function infoVoucher(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/finance/voucher/detailBbk']); ?>?id='+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-bbk").modal('show');
		$("#modal-bbk").on('hidden.bs.modal', function () { });
		$("#modal-bbk .modal-dialog").css('width',"21cm");
		spinbtn();
		draggableModal();
	});
}
function detailPoByKode(id){
    var reff_no = $("#<?= \yii\helpers\Html::getInputId($model, "reff_no") ?>").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/detailPoByKode','kode'=>'']) ?>'+reff_no,'modal-detailpo','22cm');
}
function detailPo(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/detailPo','id'=>'']) ?>'+id,'modal-detailpo','22cm');
}
function detailTerima(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/ppic/terimasengon/detailrekap','id'=>'']) ?>'+id,'modal-detailterima','90%');
}
function riwayatSaldoSuplierSengon(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/saldosuplierlog/riwayatSaldo','id'=>'']) ?>'+id,'modal-riwayatsaldo','80%');
}
function detailKeputusan(id){
    var reff_no = $("#<?= \yii\helpers\Html::getInputId($model, "reff_no") ?>").val();
    kode = reff_no.split('-')[0];
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/detailKeputusan','kode'=>'']) ?>'+kode,'modal-detailpo','22cm');
}
function openAsuransi(){
    openModal('<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/openAsuransi','pick'=>'1']) ?>','modal-aftersave','90%');
}
function detailAsuransi(id){
    var kode = $("#<?= \yii\helpers\Html::getInputId($model, "reff_no") ?>").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/asuransi/detailAsuransi','kode'=>'']) ?>'+kode,'modal-detailpo','22cm');
}
function cancelVoucher(open_voucher_id){
	openModal('<?php echo yii\helpers\Url::toRoute(['/finance/openvoucher/cancelVoucher']) ?>?id='+open_voucher_id,'modal-transaksi');
}
</script>