<?php
/* @var $this yii\web\View */

use app\models\MDefaultValue;

$this->title = 'Pengajuan Master Produk';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
app\assets\FileUploadAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-5 control-label'],
    ],
    'options' => ['enctype' => 'multipart/form-data'],
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
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <span class="pull-right" style="margin-left: 10px; margin-right: 10px;">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Pengajuan Yang Telah Dibuat'); ?></a>
                    </span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered" style="border: solid 1px;">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>
										<?php
										if(!isset($_GET['pengajuan_masterproduk_id'])){
											echo "Pengajuan Master Produk Baru";
										}else{
											echo "Data Pengajuan Master Produk";
										}
										?>
									</h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-5">
										<?php
										if(!isset($_GET['pengajuan_masterproduk_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%; font-weight:bold', 'readonly'=>true]) ?>
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
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'keperluan')->textarea(['rows' => '3']) ?> 
                                        
                                        <?php if(isset($_GET['pengajuan_masterproduk_id'])){ ?>
                                            <?php if($model->cancel_transaksi_id != null){ ?>
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label"><?= Yii::t('app', ''); ?></label>
                                                    <div class="col-md-7" style="margin-top:7px;">
                                                        <span class="label label-sm label-danger"><?php echo \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
                                                        <?php
                                                         $modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
                                                         echo "<br><span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena ".$modCancel->cancel_reason."</span>";
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php }else if(!isset($_GET['edit'])){ ?>
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label"><?= Yii::t('app', ''); ?></label>
                                                    <div class="col-md-7" style="margin-top:7px;">
                                                        <?php
                                                        $sql_kode = "select kode from t_pengajuan_masterproduk where pengajuan_masterproduk_id = ".$_GET['pengajuan_masterproduk_id']." ";
                                                        $kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();

                                                        $sql_status_approval = "select status from t_approval where reff_no = '".$kode."' ";
                                                        $status_approval = Yii::$app->db->createCommand($sql_status_approval)->queryScalar();
                                                        
                                                        if ($status_approval == 'Not Confirmed') {
                                                        ?>
                                                            <a href="javascript:void(0);" onclick="cancelTransaksi(<?= $model->pengajuan_masterproduk_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Pengajuan'); ?></a>
                                                        <?php } else {?>
                                                            <a href="javascript:void(0);" class="btn default btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Pengajuan'); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
										<?php } ?>

                                    </div>
                                    <div class="col-md-5">
                                        <?= $form->field($model, 'status_pengajuan')->inline(true)->radioList(['Normal'=>'Normal', 'Urgent'=>'Urgent']); ?>
                                        <?= $form->field($model, 'keterangan')->textarea(['rows' => '3']) ?>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Status Approval</label>
                                            <div class="col-md-8">
                                                <?php
                                                if (isset($_GET['pengajuan_masterproduk_id'])) {
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
                                                        $approves = \yii\helpers\Json::decode($model->approve_reason);
                                                        $rejects = \yii\helpers\Json::decode($model->reject_reason);
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
                                                            // $reason = "reason : $reasons";
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
                                <hr>
                                <?php // DETAIL PENGAJUAN ;?>
                                <div class="row" id="detail-order" style="margin-top: -20px; margin-bottom: -20px;">
                                    <div class="col-md-12">
                                        <h5 style="font-weight: bold;"><?= Yii::t('app', 'Detail Produk'); ?></h5>
                                    </div>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
                                                        <th style="width: 100px;"><?= Yii::t('app', 'No.') ?></th>
                                                        <th style="width: 10%;"><?= Yii::t('app', 'Jenis Produk') ?></th>
                                                        <th><?= Yii::t('app', 'Kode Produk') ?></th>
                                                        <th><?= Yii::t('app', 'Nama Produk') ?></th>
                                                        <th><?= Yii::t('app', 'Dimensi') ?></th>
                                                        <th><?= Yii::t('app', 'Jenis<br>Kayu') ?></th>
                                                        <th><?= Yii::t('app', 'Grade') ?></th>
                                                        <th><?= Yii::t('app', 'Warna<br>Kayu') ?></th>
                                                        <th><?= Yii::t('app', 'Glue') ?></th>
                                                        <th><?= Yii::t('app', 'Profil<br>Kayu') ?></th>
                                                        <th><?= Yii::t('app', 'Kondisi<br>Kayu') ?></th>
                                                        <th><?= Yii::t('app', 'Gambar') ?></th>
                                                        <th style="width: 80px;"></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td>
                                                            <a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="create();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Produk'); ?></a>
                                                        </td>
                                                    </tr>
                                                </tfoot>
											</table>
										</div>
                                    </div>
                                    <hr>
                                    <div class="form-actions pull-right col-md-12 row">
                                        <div class="col-md-12 right">
                                            <div class="col-md-6 pull-right pull-right">
                                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'save();']); ?>
                                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'printPengajuan('.(isset($_GET['pengajuan_masterproduk_id'])?$_GET['pengajuan_masterproduk_id']:'').');','disabled'=>true]); ?>
                                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'resetForm();']); ?>
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
    </div>
</div>

<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
$pagemode = "";
if(isset($_GET['pengajuan_masterproduk_id'])){
    $pagemode = "afterSave(".$_GET['pengajuan_masterproduk_id'].");";
}else{
	$pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
", yii\web\View::POS_READY); ?>

<script>
function create(){
    openModal('<?= \yii\helpers\Url::toRoute('/ppic/pengajuanmasterproduk/create') ?>','modal-produk-create');
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanmasterproduk/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function edit(data, tr_id){
    openModal('<?= \yii\helpers\Url::toRoute('/ppic/pengajuanmasterproduk/edit') ?>?datas='+data+'&tr_id='+tr_id,'modal-produk-edit');
}

function info(id){
    openModal('<?= \yii\helpers\Url::toRoute('/ppic/pengajuanmasterproduk/info') ?>?id='+id,'modal-produk-info');
}

function printPengajuan(id){
	window.open("<?= yii\helpers\Url::toRoute('/ppic/pengajuanmasterproduk/print') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail produk terlebih dahulu');
            return false;
        }
		// if(validatingDetail()){
        submitform($form);
        // }
    }
    return false;
}

function afterSave(id){
    <?php if(!isset($_GET['edit'])) { ?>
		getItems(id);
		$('#btn-add-item').hide();
		$('form').find('input').each(function(){ $(this).prop("disabled", true); });
        $('form').find('select').each(function(){ $(this).prop("disabled", true); });
        $('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('#btn-save').attr('disabled','');
        $('#btn-print').removeAttr('disabled');
	<?php } else { ?>
		getItems(id,1);
	<?php } ?>
}

function getItems(id,edit=null){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanmasterproduk/getItems']); ?>',
		type   : 'POST',
		data   : {id:id,edit:edit},
		success: function (data) {
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(item) {
                    appendRow(item);
                });
            }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function appendRow(data){
    var ii = $('#table-detail tbody tr').length;
    var no_urut = ii + 1;
    var src_img = "<?= Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png'; ?>";
    <?php if(isset($_GET['pengajuan_masterproduk_id'])){ ?>
        if(data.produk_gbr){
            src_img = "<?= \yii\helpers\Url::base().'/uploads/gud/req_produk/'; ?>"+data.produk_gbr;
        }
    <?php } ?>

    var newRow = `
        <tr data-id="baris_${no_urut}">
            <td style="vertical-align: middle; text-align: center;" class="td-kecil">
                <span class="no_urut">${no_urut}</span>
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][pengajuan_masterproduk_detail_id]" value="${data.pengajuan_masterproduk_detail_id}" id="pengajuan_masterproduk_detail_id">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_p]" value="${unformatNumber(data.produk_p)}" id="produk_p">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_l]" value="${unformatNumber(data.produk_l)}" id="produk_l">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_t]" value="${unformatNumber(data.produk_t)}" id="produk_t">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_p_satuan]" value="${data.produk_p_satuan}" id="produk_p_satuan">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_l_satuan]" value="${data.produk_l_satuan}" id="produk_l_satuan">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_t_satuan]" value="${data.produk_t_satuan}" id="produk_t_satuan">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_satuan_besar]" value="${data.produk_satuan_besar}" id="produk_satuan_besar">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_satuan_kecil]" value="${data.produk_satuan_kecil}" id="produk_satuan_kecil">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_qty_satuan_kecil]" value="${data.produk_qty_satuan_kecil}" id="produk_qty_satuan_kecil">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][kapasitas_kubikasi]" value="${data.kapasitas_kubikasi}" id="kapasitas_kubikasi">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][diameter_range]" value="${data.diameter_range}" id="diameter_range">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_group]" value="${data.produk_group}" id="produk_group"> 
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_kode]" value="${data.produk_kode}" id="produk_kode">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_nama]" value="${data.produk_nama}" id="produk_nama">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_dimensi]" value="${data.produk_dimensi}" id="produk_dimensi">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][jenis_kayu]" value="${data.jenis_kayu}" id="jenis_kayu">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][grade]" value="${data.grade}" id="grade">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][warna_kayu]" value="${data.warna_kayu}" id="warna_kayu">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][glue]" value="${data.glue}" id="glue">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][profil_kayu]" value="${data.profil_kayu}" id="profil_kayu">
                <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][kondisi_kayu]" value="${data.kondisi_kayu}" id="kondisi_kayu">
                <?php if(isset($_GET['edit'])){ ?>
                    <input type="hidden" name="TPengajuanMasterprodukDetail[${ii}][produk_gbr_lama]" value="${data.produk_gbr}" id="produk_gbr_lama">
                <?php } ?>
            </td>
            <td style="vertical-align: middle;" class="td-kecil produk_group">
                ${data.produk_group}
            </td>
            <td style="vertical-align: middle;" class="td-kecil produk_kode">
                ${data.produk_kode}
            </td>
            <td style="vertical-align: middle;" class="td-kecil produk_nama">
                ${data.produk_nama}
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-center produk_dimensi">
                ${(data.produk_dimensi == "null" || data.produk_dimensi == null || data.produk_dimensi == "")?'-':data.produk_dimensi}
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-center jenis_kayu">
                ${(data.jenis_kayu == "null" || data.jenis_kayu == null || data.jenis_kayu == "")?'-':data.jenis_kayu}
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-center grade">
                ${(data.grade == "null" || data.grade == null || data.grade == "")?'-':data.grade}
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-center warna_kayu">
                ${(data.warna_kayu == "null" || data.warna_kayu == null || data.warna_kayu == "")?'-':data.warna_kayu}
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-center glue">
                ${(data.glue == "null" || data.glue == null || data.glue == "")?'-':data.glue}
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-center profil_kayu">
                ${(data.profil_kayu == "null" || data.profil_kayu == null ||data.profil_kayu == "")?'-':data.profil_kayu}
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-center kondisi_kayu">
                ${(data.kondisi_kayu== "null" || data.kondisi_kayu == null ||data.kondisi_kayu == "")?'-':data.kondisi_kayu}
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-center produk_gbr">
                <div class="col-md-12">
                    <div class="fileinput fileinput-new" data-provides="fileinput" >
                        <div class="fileinput-new thumbnail" style="width: 55px;">
                            <img src="${src_img}" alt="" /> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="width: 55px;"> </div>
                            <?php if(!isset($_GET['pengajuan_masterproduk_id']) || isset($_GET['edit'])){ ?>
                            <div style="text-align-center">
                                <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                    <span class="fileinput-new"> Select image </span>
                                    <span class="fileinput-exists"> Change </span>
                                    <input type="file" name="TPengajuanMasterprodukDetail[${ii}][produk_gbr]"> 
                                </span> 
                                <a href="javascript:;" id="remove_file" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php if(isset($_GET['pengajuan_masterproduk_id']) && !isset($_GET['edit'])){ ?>
                <button type="button" class="btn btn-xs blue-hoki btn-outline tooltips" onclick="editDetail(this)"><i class="fa fa-info-circle"></i></button>
                <button type="button" class="btn btn-xs grey" disabled="disabled"><i class="fa fa-remove"></i></button>
            <?php } else { ?>
                <button type="button" class="btn btn-xs blue-hoki btn-outline tooltips" onclick="editDetail(this)"><i class="fa fa-info-circle"></i></button>
                <button type="button" class="btn btn-xs red" onclick="hapusDetail(this)"><i class="fa fa-remove btn-danger"></i></button>
            <?php } ?>
            </td>
        </tr>
    `;
    $('#table-detail tbody').append(newRow); 
    $('#table-detail tbody tr').each(function(index) {
        var no_urut = index + 1;
        $(this).find('.no_urut').text(no_urut);
        $(this).attr('data-id', 'baris_' + no_urut);
    });  
}

function editDetail(ele){
    var row = $(ele).closest('tr');
    var btn_edit = row.data('id');
    var i = btn_edit.split('_').pop(); 
    var ii = i-1;

    var data = { pengajuan_masterproduk_detail_id : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][pengajuan_masterproduk_detail_id]"]').val(),
                 produk_group : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_group]"]').val(),
                 produk_kode : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_kode]"]').val(),
                 produk_nama : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_nama]"]').val(),
                 produk_dimensi : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_dimensi]"]').val(),
                 jenis_kayu : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][jenis_kayu]"]').val(),
                 grade : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][grade]"]').val(),
                 warna_kayu : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][warna_kayu]"]').val(),
                 glue : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][glue]"]').val(),
                 profil_kayu : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][profil_kayu]"]').val(),
                 kondisi_kayu : row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][kondisi_kayu]"]').val(),
                 produk_p : row.find('#produk_p').val(),
                 produk_l : row.find('#produk_l').val(),
                 produk_t : row.find('#produk_t').val(),
                 produk_p_satuan : row.find('#produk_p_satuan').val(),
                 produk_l_satuan : row.find('#produk_l_satuan').val(),
                 produk_t_satuan : row.find('#produk_t_satuan').val(),
                 produk_satuan_besar : row.find('#produk_satuan_besar').val(),
                 produk_satuan_kecil : row.find('#produk_satuan_kecil').val(),
                 produk_qty_satuan_kecil : row.find('#produk_qty_satuan_kecil').val(),
                 kapasitas_kubikasi : row.find('#kapasitas_kubikasi').val(),
                 diameter_range : row.find('#diameter_range').val()
              };

    var jsonData = encodeURIComponent(JSON.stringify(data));
    <?php if(isset($_GET['pengajuan_masterproduk_id']) && !isset($_GET['edit'])){ ?>
        info(data.pengajuan_masterproduk_detail_id);
    <?php } else { ?>
        edit(jsonData, btn_edit);
    <?php } ?>
}

function updateData(datas, tr_id) {
    var form = $('#form-produk-edit')[0];
    var data = new FormData(form);
    var jsonData = encodeURIComponent(JSON.stringify(datas));

    $.ajax({
        url: '<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanmasterproduk/edit']); ?>?datas='+jsonData+'&tr_id='+tr_id,
        type: 'POST',
        data: data,
        processData: false, 
        contentType: false,
        success: function(response) {
            if (response.status) {
                cekKode(response.data.produk_kode, function(exist) {
                    if (exist) {
                        cisAlert("Produk sudah ada di master!");
                    } else {
                        updateTableRow(tr_id, response.data);
                        $('#modal-produk-edit').modal('hide');
                    }
                });
            } else {
                cisAlert('Produk sudah ada!');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', status, error);
        }
    });
}

function updateTableRow(tr_id, data) {
    var i = tr_id.split('_').pop(); 
    var ii = i-1;
    var row = $('#table-detail').find(`tr[data-id="${tr_id}"]`); 

    row.find('.produk_group').text(data.produk_group); 
    row.find('.produk_kode').text(data.produk_kode);
    row.find('.produk_nama').text(data.produk_nama); 
    row.find('.produk_dimensi').text((data.produk_dimensi == "null" || data.produk_dimensi == null || data.produk_dimensi == "")?  '-': data.produk_dimensi); 
    row.find('.jenis_kayu').text((data.jenis_kayu == "null" || data.jenis_kayu == null || data.jenis_kayu == "")? '-': data.jenis_kayu); 
    row.find('.grade').text((data.grade == "null" || data.grade == null || data.grade == "")? '-' : data.grade);
    row.find('.warna_kayu').text((data.warna_kayu == "null" || data.warna_kayu == null || data.warna_kayu == "")? '-' : data.warna_kayu); 
    row.find('.glue').text((data.glue == "null" || data.glue == null || data.glue == "")? '-' : data.glue); 
    row.find('.profil_kayu').text((data.profil_kayu == "null" || data.profil_kayu == null || data.profil_kayu == "")? '-' : data.profil_kayu); 
    row.find('.kondisi_kayu').text((data.kondisi_kayu == "null" || data.kondisi_kayu == null || data.kondisi_kayu == "")? '-' : data.kondisi_kayu);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_group]"]').val(data.produk_group);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_kode]"]').val(data.produk_kode);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_nama]"]').val(data.produk_nama);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_dimensi]"]').val(data.produk_dimensi);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][jenis_kayu]"]').val(data.jenis_kayu);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][grade]"]').val(data.grade);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][warna_kayu]"]').val(data.warna_kayu);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][glue]"]').val(data.glue);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][profil_kayu]"]').val(data.profil_kayu);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][kondisi_kayu]"]').val(data.kondisi_kayu);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_p]"]').val(unformatNumber(data.produk_p));
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_l]"]').val(unformatNumber(data.produk_l));
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_t]"]').val(unformatNumber(data.produk_t));
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_p_satuan]"]').val(data.produk_p_satuan);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_l_satuan]"]').val(data.produk_l_satuan);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_t_satuan]"]').val(data.produk_t_satuan);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_satuan_besar]"]').val(data.produk_satuan_besar);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_satuan_kecil]"]').val(data.produk_satuan_kecil);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][produk_qty_satuan_kecil]"]').val(data.produk_qty_satuan_kecil);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][kapasitas_kubikasi]"]').val(data.kapasitas_kubikasi);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][diameter_range]"]').val(data.diameter_range);
    row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][pengajuan_masterproduk_detail_id]"]').val(data.pengajuan_masterproduk_detail_id);
}

function hapusDetail(ele){
    var confirmation = confirm("Apakah Anda yakin ingin menghapus detail ini?");
    if(confirmation){
        var rows = $(ele).closest('tr');
        var row_id = rows.data('id');
        var row = $('#table-detail').find(`tr[data-id="${row_id}"]`);
        var i = row_id.split('_').pop(); 
        var ii = i-1;

        // var pengajuan_detail_id = row.find('input[name="TPengajuanMasterprodukDetail['+ii+'][pengajuan_masterproduk_detail_id]"]').val();
        // if(pengajuan_detail_id){
        //     $.ajax({
        //         url: '<?php //echo \yii\helpers\Url::toRoute(['/ppic/pengajuanmasterproduk/hapusDetail']); ?>',
        //         type: 'POST',
        //         data: { id: pengajuan_detail_id},
        //         success: function(response) {
        //             console.log('Gambar berhasil dihapus.');
        //         },
        //         error: function(xhr, status, error) {
        //             console.log('Terjadi kesalahan saat menghapus gambar.');
        //         }
        //     });
        // } 

        row.remove();

        $('#table-detail tbody tr').each(function(index) {
            var no_urut = index + 1;
            $(this).find('.no_urut').text(no_urut);
            $(this).attr('data-id', 'baris_' + no_urut);
        });
    }
}

function kodeExist(kode){
    var produkKodeExists = false;
    $('#table-detail tbody tr').each(function() {
        var existingProdukKode = $(this).find('.produk_kode').text().trim();
        if (existingProdukKode === kode) {
            produkKodeExists = true;
            return false;
        }
    });
    return produkKodeExists;
}

function cekKode(kode, callback){
    $.ajax({
        url: '<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanmasterproduk/cekKode']); ?>',
        method: 'POST',
        data: { kode: kode },
        success: function(response) {
            if (response.exists) {
                callback(true);
            } else {
                callback(false);
            }
        },
        error: function() {
            cisAlert('Terjadi kesalahan dalam memeriksa kode produk.');
            callback(false);
        }
    });
}

function cancelTransaksi(id){
	openModal('<?php echo \yii\helpers\Url::toRoute(['/ppic/pengajuanmasterproduk/cancelTransaksi']) ?>?id='+id,'modal-transaksi');
}

</script>