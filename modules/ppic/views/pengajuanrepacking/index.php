<?php
/* @var $this yii\web\View */
$this->title = 'Permintaan Barang';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Permintaan Barang <small>( Tarik Barang Dari Gudang Ke Produksi )</small></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-5 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
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
                <ul class="nav nav-tabs">
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/pengajuanrepacking/index") ?>"> <?= Yii::t('app', 'Permintaan'); ?> </a>
					</li>
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/pengajuanrepacking/status") ?>"> <?= Yii::t('app', 'Status Permintaan'); ?> </a>
					</li>
                    <li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/pengajuanrepacking/kirimgudang") ?>"> <?= Yii::t('app', 'Kirim Kembali Ke Gudang'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>
										Data Permintaan
									</h4></span>
                                </div>
                                <span class="pull-right">
									<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Permintaan Yang Telah Dibuat'); ?></a> 
								</span>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-5">
										<?php 
										if(!isset($_GET['pengajuan_repacking_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label("Kode Permintaan");
										} else { ?>
											<div class="form-group">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Permintaan'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
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
										<?= $form->field($model, 'tanggal',[
																	'template'=>'
                                                                    {label}
                                                                    <div class="col-md-7">
                                                                        <div class="input-group input-medium date date-picker bs-datetime">
                                                                        {input} 
                                                                        <span class="input-group-addon">
                                                                            <button class="btn default" type="button" style="margin-left: 0px;" disabled>
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                                        </div> 
																	{error}
                                                                    </div>'])->textInput(['readonly'=>'readonly', 'disabled' => 'disabled', 'style' => 'background: #eef1f5;'])->label("Tanggal Permintaan"); ?>
 

                                        <?= $form->field($model, 'keperluan')->dropDownList(["Repacking"=>"Repacking","Regrade"=>"Regrade","Restamp"=>"Restamp","Proses Produksi"=>"Proses Produksi", "Penanganan Barang Retur"=>"Penanganan Barang Retur"],['class'=>'form-control','prompt'=>'', 'onchange'=>'setRetur();']); ?>
                                        <?= $form->field($model, 'dibuat_oleh')->textInput(['disabled'=>true])->label("Dibuat Oleh"); ?>
									</div>
									<div class="col-md-6">
                                        <?= $form->field($model, 'prepared_by')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control select2','prompt'=>''])->label('Disiapkan Oleh'); ?>
										<?= $form->field($model, 'approved_by')->dropDownList(\app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control select2','prompt'=>''])->label('Disetujui Oleh'); ?>
										<?= $form->field($model, 'approved2_by')->dropDownList(\app\models\MPegawai::getOptionListXArray([3484]),['class'=>'form-control select2','prompt'=>'','onchange'=>'pickAck()'])->label('Mengetahui'); ?>
                                                                                    <?php //$form->field($model, 'approved2_by')->dropDownList(\app\models\MPegawai::getOptionListXArray([3364,3484]),['class'=>'form-control select2','prompt'=>'','onchange'=>'pickAck()'])->label('Mengetahui'); ?>
                                        <?php /*<?= $form->field($model, 'approved2_by')->dropDownList(\app\models\MPegawai::getOptionListXArray([3364]),['class'=>'form-control select2','prompt'=>'','onchange'=>'pickAck()'])->label('Mengetahui'); ?> */?>
                                        <?= $form->field($model, 'keterangan')->textarea(); ?>
									</div>
								</div>
                                <div class="row row-centered">
                                    <?php // SHOW APPROVAL IF EDIT WAE DUL ;?>
                                    <?php
                                    $by_prepared = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->prepared_by)->queryOne();
                                    $by_approved = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->approved_by)->queryOne();
                                    $by_approved2 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND level = 3")->queryOne();
                                    $modReff = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
                                    for ($i=1;$i<=count($modReff);$i++) {
                                        $sql_pegawai_id = "select assigned_to from t_approval where reff_no = '".$model->kode."' and level = ".$i."";
                                        $pegawai_id = Yii::$app->db->createCommand($sql_pegawai_id)->queryScalar();
                                        $json = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND level = ".$i."")->queryOne();
                                        if ($json['status'] == "APPROVED") {
                                            $addClass = "green";
                                        } else if ($json['status'] == "REJECTED") {
                                            $addClass = "red";
                                        } else {
                                            $addClass = "grey";
                                        }
                                    ?>
                                    <div class="col-md-4 col-centered">
                                        <button type="button" class="btn <?php echo $addClass;?> btn-outline ciptana-spin-btn ladda-button" style="text-align: left; font-size: 10px;margin-top: 10px; margin-right: 10px;" data-style="zoom-in">
                                            <span class="ladda-label">by : <?= \app\models\MPegawai::findOne($pegawai_id)->pegawai_nama; ?>
                                            <?php
                                            if($json['status']==\app\models\TApproval::STATUS_APPROVED){
                                                echo "".\app\models\TApproval::STATUS_APPROVED."<br> at : ".
                                                        \app\components\DeltaFormatter::formatDateTimeForUser2($json['updated_at'])."</span>";
                                                $modApproveReason = \yii\helpers\Json::decode($model->approve_reason);
                                                if ($modApproveReason != "") {
                                                    foreach($modApproveReason as $iap => $aprreas){
                                                        if($aprreas['by'] == $json['assigned_to']){
                                                            echo '<span style="font-weight: 500;">';
                                                            echo "<br>&nbsp; <span>( ".$aprreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
                                                    }
                                                }
                                            }else if($json['status']==\app\models\TApproval::STATUS_REJECTED){
                                                echo "".\app\models\TApproval::STATUS_REJECTED."<br> at : ".
                                                        \app\components\DeltaFormatter::formatDateTimeForUser2($json['updated_at'])."</span>";
                                                $modRejectReason = \yii\helpers\Json::decode($model->reject_reason);
                                                if ($modRejectReason != "") {
                                                    foreach($modRejectReason as $iap => $rejreas){
                                                        if($rejreas['by'] == $json['assigned_to']){
                                                            $reject_reason = $rejreas['reason'];
                                                        } else {
                                                            $reject_reason = "Auto Reject";
                                                        }
                                                        echo '<span style="font-weight: 500;">';
                                                        echo "<br>&nbsp; <span>( ".$reject_reason." )</span>";
                                                        echo '</span>';
                                                    }
                                                }
                                            }else{
                                                echo "<br>&nbsp; <i>(Not Confirm)</i>";
                                            }
                                            ?>                                            
                                            </span>
                                        </button>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><?= Yii::t('app', 'Detail Permintaan'); ?></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.3rem;">No.</th>
														<th style="line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Produk / Dimensi'); ?></th>
														<th style="width: 60px; line-height: 0.9;  padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Qty<br>Palet'); ?></th>
														<th style="width: 120px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Available<br>Stock'); ?></th>
                                                        <th style="width: 150px;"><?= Yii::t('app', 'Keterangan') ?></th>
														<th style="width: 50px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Cancel'); ?></th>
													</tr>
												</thead>
												<tbody>

												</tbody>
												<tfoot>
													<tr>
														<td colspan="2" id="td-btn-add-item">
															<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="masterProduk();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
                                                            <a class="btn btn-sm blue-hoki" id="btn-add-item-retur" onclick="masterProdukRetur();" style="margin-top: 10px; display: none;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
														</td>
														<td style="vertical-align: middle; text-align: right;">
                                                            <?= yii\bootstrap\Html::activeTextInput($model, "total_palet",['class'=>'form-control float td-kecil','style'=>'width:100%;','disabled'=>true]); ?>
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
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printRepacking('.(isset($_GET['pengajuan_repacking_id'])?$_GET['pengajuan_repacking_id']:'').');','disabled'=>true]); ?>
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
$pagemode = "";
if(isset($_GET['pengajuan_repacking_id'])){
    $pagemode = "afterSave(".$_GET['pengajuan_repacking_id'].");";
}else{
	$pagemode = "";
}

// cek sek dul
$cek = \app\models\TPengajuanRepacking::find()->where(['kode'=>$model->kode])->one();

/*if ((empty($cek['approval_status']) || ($cek['approval_status'] == "Not Confirmed"))) {
    if (isset($_GET['edit']) && ($_GET['edit'] == 1)) {
        $disabled = "$('#btn-save').show(); 
                    $('#tpengajuanrepacking-approved2_by').prop('disabled',false);
                    ";
    } else {
        $disabled = "$('#btn-save').hide();
                    $('#tpengajuanrepacking-approved2_by').prop('disabled',false);
                    ";
    }
} else {
    $disabled = "$('#btn-save').hide();
                    $('#tpengajuanrepacking-tanggal').prop('disabled',true);
                    $('#tpengajuanrepacking-keperluan').prop('disabled',true);
                    $('#tpengajuanrepacking-prepared_by').prop('disabled',true);
                    $('#tpengajuanrepacking-approved_by').prop('disabled',true);
                    $('#tpengajuanrepacking-approved2_by').prop('disabled',true);
                    $('#tpengajuanrepacking-keterangan').prop('disabled',true);
                    ";
}
*/

if (empty($_GET['success']) && empty($_GET['edit']) && empty($_GET['pengajuan_repacking_id'])) {
    $disabled = "$('#btn-save').show(); 
                $('#tpengajuanrepacking-approved2_by').prop('disabled',false);
                ";
} else {
    if (isset($_GET['edit']) && ($_GET['edit'] == 1)) {
        if ((empty($cek['approval_status']) || ($cek['approval_status'] == "Not Confirmed"))) {
            $disabled = "$('#btn-save').show(); 
                        $('#tpengajuanrepacking-approved2_by').prop('disabled',false);
                        ";
        } else {
            $disabled = "$('#btn-save').hide();
                        $('#tpengajuanrepacking-approved2_by').prop('disabled',true);
                        ";
        }
    } else {
        $disabled = "$('#btn-save').hide();
                    $('#tpengajuanrepacking-approved2_by').prop('disabled',true);
                    ";
    }
}


?>
<?php $this->registerJs(" 
    $pagemode
    $disabled
	formconfig();
	$('select[name*=\"[prepared_by]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
	});
	$('select[name*=\"[approved_by]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
	});
    $('select[name*=\"[approved2_by]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
	});
", yii\web\View::POS_READY); ?>
<script>
function pickAck() {
    $('#td-btn-add-item').show();
    var approved2_by = $('#tpengajuanrepacking-approved2_by').val();
    var keperluan = $('#<?= \yii\bootstrap\Html::getInputId($model, "keperluan") ?>').val();
    //3364	"TATANG SECANG SIDDIT"
    //3484	"HADI HALIM"
    if (approved2_by == 3364) {
        //tipe = "Veneer";
        tipe = 1;
        label = "Veneer";
    } else if (approved2_by == 3484) {
        //tipe = "Moulding, Sawntimber";
        tipe = 2;
        label = "Moulding, Sawntimber";
    } else {
        //tipe = "Plywood, Platform, Lamineboard";
        tipe = 3;
        label = "Plywood, Platform, Lamineboard";
    }
    if(keperluan == 'Penanganan Barang Retur'){
        var btn1 = '<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="masterProduk(\''+tipe+'\');" style="margin-top: 10px; display: none;"><i class="fa fa-plus"></i> Tambah Item</a>';
        var btn2 = '<a class="btn btn-sm blue-hoki" id="btn-add-item-retur" onclick="masterProdukRetur();" style="margin-top: 10px;"><i class="fa fa-plus"></i> Tambah Item</a>';
    } else {
        var btn1 = '<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="masterProduk(\''+tipe+'\');" style="margin-top: 10px;"><i class="fa fa-plus"></i> Tambah Item</a>';
        var btn2 = '<a class="btn btn-sm blue-hoki" id="btn-add-item-retur" onclick="masterProdukRetur();" style="margin-top: 10px; display: none;"><i class="fa fa-plus"></i> Tambah Item</a>';
    }
    $('#td-btn-add-item').html(btn1 + btn2);
    <?php if(!isset($_GET['pengajuan_repacking_id'])){ ?>
        $('.rows').remove();
    <?php } ?>
}

function masterProduk(tipe){
	var tr_seq = $(tipe).parents('tr').find('#no_urut').val();
    var jenis_produk = tipe;
    var url = '<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/produkInStock','disableAction'=>'','']); ?>&jenis_produk='+jenis_produk;
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master-produk .modal-dialog").css('width','75%');
		$("#modal-master-produk").modal('show');
		$("#modal-master-produk").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function pickProduk(produk_id, kode){
    var keperluan = $('#<?= \yii\bootstrap\Html::getInputId($model, "keperluan") ?>').val();
    if(keperluan == 'Penanganan Barang Retur'){
        var kode = kode;
    } else {
        var kode = "";
    }
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/Pick']); ?>',
        type   : 'POST',
        data   : {produk_id:produk_id, keperluan:keperluan, kode:kode},
        success: function (data) {
			if(data){
				var already = [];
                $('#table-detail > tbody > tr').each(function(){
                    var produk_id = $(this).find('input[name*="[produk_id]"]');
                    if( produk_id.val() ){
                        already.push(produk_id.val());
                    }
                });
                if( $.inArray( data.produk_id.toString(), already ) != -1 ){ // Jika ada yang sama
                    cisAlert("Produk ini sudah dipilih di list");
                    return false;
                }else{
                    $("#modal-available-produk").find('button.fa-close').trigger('click');
                    $("#table-detail > tbody").append(data.html);
                    reordertable("#table-detail");
                    total();
                    $("#modal-master-produk").find('button.fa-close').trigger('click');
                }
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function total(){
	var qty_besar = 0;
	$('#table-detail > tbody > tr').each(function(){
		qty_besar += unformatNumber( $(this).find('input[name*="[qty_besar]"]').val() );
	});
	$('input[name*="[total_palet]"]').val( formatNumberForUser(qty_besar) );
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
//	var field1 = $("#asd");
//	if(!field1.val()){
//		$(field1).parents('.form-group').addClass('error-tb-detail');
//		has_error = has_error + 1;
//	}else{
//		$(field1).parents('.form-group').removeClass('error-tb-detail');
//	}
    $('#table-detail tbody > tr').each(function(){
        var qty_besar = $(this).find("input[name*='[qty_besar]']");
        var qty_stock = $(this).find("input[name*='[qty_stock]']");
        if(!qty_besar.val()){
            $(qty_besar).parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            if($(qty_besar).val() <= 0){
                $(qty_besar).parents('td').addClass('error-tb-detail');
                has_error = has_error + 1;
            }else{
                if( unformatNumber($(qty_besar).val())  > unformatNumber(qty_stock.val()) ){
                    $(qty_besar).parents('td').addClass('error-tb-detail');
                    has_error = has_error + 1;
                }else{
                    $(qty_besar).parents('td').removeClass('error-tb-detail');
                }
            }
        }
    });
	<?php if(isset($_GET['edit'])){ ?>
		//has_error = 0;
	<?php } ?>
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
	<?php if(!isset($_GET['edit'])){ ?>
		getItems(id);
		$('#btn-add-item').hide();
	<?php }else{ ?>
		getItems(id,1);
	<?php } ?>
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#<?= yii\bootstrap\Html::getInputId($model, 'pegawai_mutasi') ?>').attr('disabled','');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_kirim') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
	<?php if(isset($_GET['edit'])){ ?>
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>').prop("disabled", true);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('#<?= \yii\bootstrap\Html::getInputId($model, "keperluan") ?>').prop("disabled", false);
        $('#<?= \yii\bootstrap\Html::getInputId($model, "prepared_by") ?>').prop("disabled", false);
        $('#<?= \yii\bootstrap\Html::getInputId($model, "approved_by") ?>').prop("disabled", false);
        $('#<?= \yii\bootstrap\Html::getInputId($model, "keterangan") ?>').prop("disabled", false);
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
	<?php } ?>
}

function getItems(id,edit=null){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/getItems']); ?>',
		type   : 'POST',
		data   : {id:id,edit:edit},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				if(edit){ // edit load item process
					$('#table-detail tbody').html(data.html);
				}
			}
			setTimeout(function(){
                reordertable('#table-detail');
                setRetur();
				total();
			},500);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/index','pengajuan_repacking_id'=>'']); ?>'+id+'&edit=1');
}

function printRepacking(id){
	window.open("<?= yii\helpers\Url::toRoute('/ppic/pengajuanrepacking/printRepacking') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function setRetur(){
    var keperluan = $('#<?= \yii\bootstrap\Html::getInputId($model, "keperluan") ?>').val();
    var selected = "<?= isset($_GET['pengajuan_repacking_id'])?$model->approved2_by:''; ?>";
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/setRetur']); ?>',
        type   : 'POST',
        data   : {keperluan:keperluan, selected:selected},
        success: function (data) {
            if(data){
                $('#<?= \yii\bootstrap\Html::getInputId($model, "approved2_by") ?>').html(data.html);
            }
            <?php if(!isset($_GET['pengajuan_repacking_id'])){ ?>
                    if(keperluan == 'Penanganan Barang Retur'){
                        $('#btn-add-item-retur').css('display', '');
                        $('#btn-add-item').css('display', 'none');
                    } else {
                        $('#btn-add-item-retur').css('display', 'none');
                        $('#btn-add-item').css('display', '');
                    }
                // $('.rows').remove();
            <?php } else { 
                    if(isset($_GET['edit'])) {?>
                        if(keperluan == 'Penanganan Barang Retur'){
                            $('#btn-add-item-retur').css('display', '');
                            $('#btn-add-item').css('display', 'none');
                        } else {
                            $('#btn-add-item-retur').css('display', 'none');
                            $('#btn-add-item').css('display', '');
                        }
                <?php } 
            }?>
        }, 
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function masterProdukRetur(){
    var url = '<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/produkInRetur']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master-produk .modal-dialog").css('width','75%');
		$("#modal-master-produk").modal('show');
		$("#modal-master-produk").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
</script>