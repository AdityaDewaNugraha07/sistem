<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'SPK Sawmill';
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
					<span class="pull-right">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'SPK Sawmill Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data SPK Sawmill'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
                                    <div class="col-md-6">
                                        <?php 
										if(!isset($_GET['spk_sawmill_id'])){
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
                                        <?= $form->field($model, 'refisi_ke')->textInput(['class'=>'form-control numbers-only', 'disabled'=>'disabled'])->label("Revisi Ke"); ?>
                                        <?= $form->field($model, 'tanggal_mulai', [
                                                'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(); ?>
                                        <?= $form->field($model, 'tanggal_selesai', [
                                                'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(); ?>
                                        <?= $form->field($model, 'kayu_id')->dropDownList(\app\models\MKayu::getOptionListNamaKayu(),['class'=>'form-control select2','prompt'=>''])->label('Jenis Kayu'); ?>
                                        <?= $form->field($model, 'produk_sawmill')->dropDownList(\app\models\MDefaultValue::getOptionList('produk-sawmill'),['prompt'=>'']); ?>
                                        <?php if(isset($_GET['spk_sawmill_id'])){ ?>
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
                                                        if($model->approval_status == 'Not Confirmed'){ ?>
                                                        <a href="javascript:void(0);" onclick="cancelSPK(<?= $model->spk_sawmill_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan SPK'); ?></a>
                                                    <?php }
                                                    }?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
									<div class="col-md-6">
                                        <?= $form->field($model, 'pemenuhan_po')->textInput()->label('Kode PO'); ?>
                                        <?= $form->field($model, 'peruntukan')->inline()->radioList(['Lokal'=>"Lokal",'Export'=>"Export"]) ?>
                                        <?= $form->field($model, 'line_sawmill')->dropDownList(\app\models\MDefaultValue::getOptionList('line-sawmill'),['prompt'=>'']); ?>
                                        <?= $form->field($model, 'keterangan')->textarea(); ?>
                                        <?php 
                                        if (isset($_GET['spk_sawmill_id'])) {
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
                                                            } else {
                                                                $reason = "";
                                                            }
                                                            echo "<a style='margin-top: 5px;' class='btn btn-outline btn-xs $line_color'><i class=''></i> <b>".$modApproval['status']."</b> <font style='color: #000;'>by <b>$approver</b> <br> at : $jam <br> $reason</font></a>&nbsp";
                                                        } ?>
                                                    </div>
                                                </div>
                                            <?php
                                            }}
                                            ?>
                                        </div>
									</div>
								</div>
                                <br>
                                <div class="row">
                                    <h4><?= Yii::t('app', 'Detail SPK Sawmill'); ?></h4>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th style="width: 350px;"><?= Yii::t('app', 'Size (cm)'); ?></th>
                                                        <th><?= Yii::t('app', 'Panjang (cm)'); ?></th>
                                                        <th style="width: 100px;"><?= Yii::t('app', 'Kategori Ukuran'); ?></th>
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
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Print'), ['id' => 'btn-print', 'class' => 'btn blue btn-outline ciptana-spin-btn', 'onclick' => 'printSPK(' . (isset($_GET['spk_sawmill_id']) ? $_GET['spk_sawmill_id'] : '') . ');', 'disabled' => true]); ?>
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
if(isset($_GET['spk_sawmill_id'])){
    $pagemode = "afterSaveThis(". $_GET['spk_sawmill_id'] .");";
}else {
    $pagemode = "addItem(); ";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
    $(this).find('select[name*=\"[kayu_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Kayu',
		width: null
	});
", yii\web\View::POS_READY); ?>
<script>
    function addItem(){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/spksawmill/addItem']); ?>',
            type   : 'POST',
            data   : {},
            success: function (data) {
                if(data.item){
                    $(data.item).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                        $(this).find('select[name*="[size]"]').select2({
                            allowClear: !0,
							placeholder: 'Masukkan t x l',
                            width: '100%'
                        });
                        $(this).find('.select2-selection').css('font-size','1.2rem');
						$(this).find('.select2-selection').css('padding-left','5px');
						$(this).find(".tooltips").tooltip({ delay: 50 });
                        reordertable('#table-detail');
                    });
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function save(){
        var form = $('#form-transaksi');

        var jumlah_item = $('#table-detail tbody tr').length;
        if (jumlah_item <= 0) {
            cisAlert('Isi detail terlebih dahulu');
        }

        if (validatingDetail()){
            submitform(form);
        }

        return false;
    }

    function validatingDetail(){
        var has_error = 0;

        var revisi = $("#<?= yii\bootstrap\Html::getInputId($model, "refisi_ke") ?>");
        var pemenuhan_po = $("#<?= yii\bootstrap\Html::getInputId($model, "pemenuhan_po") ?>");
        var line_sawmill = $("#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>");
        var produk_sawmill = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_sawmill") ?>");
        var kayu_id = $("#<?= yii\bootstrap\Html::getInputId($model, "kayu_id") ?>");

        if(!revisi.val()){
            $(revisi).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(revisi).parents('.form-group').removeClass('error-tb-detail');
        }
        if(!pemenuhan_po.val()){
            $(pemenuhan_po).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(pemenuhan_po).parents('.form-group').removeClass('error-tb-detail');
        }
        if(!line_sawmill.val()){
            $(line_sawmill).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(line_sawmill).parents('.form-group').removeClass('error-tb-detail');
        }
        if(!produk_sawmill.val()){
            $(produk_sawmill).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(produk_sawmill).parents('.form-group').removeClass('error-tb-detail');
        }
        if(!kayu_id.val()){
            $(kayu_id).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(kayu_id).parents('.form-group').removeClass('error-tb-detail');
        }

        var size_arr = []; 
        $('#table-detail tbody > tr').each(function(){
            var size = $(this).find('select[name*="[size]"]');
            var kategori_ukuran = $(this).find('select[name*="[kategori_ukuran]"]');
            var panjang = $(this).find('input[name*="[panjang]"]');

            if(!size.val()){
				$(this).find('select[name*="[size]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('select[name*="[size]"]').parents('td').removeClass('error-tb-detail');
                size_arr.push(size.val());
			}
            if(!kategori_ukuran.val()){
				$(this).find('select[name*="[kategori_ukuran]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('select[name*="[kategori_ukuran]"]').parents('td').removeClass('error-tb-detail');
			}
            panjang.each(function(){
                if(!$(this).val() || $(this).val() <= 0){
                    $(this).addClass('error-tb-detail'); 
                    has_error = has_error + 1;
                } else {
                    $(this).removeClass('error-tb-detail');
                }
            });

            //validasi size tidak boleh ada yg sama
            var duplicates = size_arr.filter((item, index) => size_arr.indexOf(item) !== index);
            if (duplicates.length > 0) {
                has_error = has_error + 1;
                cisAlert('Terdapat size yang sama, mohon cek kembali!');
            }
        });

        if(has_error === 0){
            return true;
        }
        return false;
    }

    function daftarAfterSave(){
        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/spksawmill/daftarAfterSave']) ?>','modal-aftersave','90%');
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
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_mulai') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_selesai') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('#btn-save').attr('disabled','');
        $('#btn-print').removeAttr('disabled');
        <?php if(isset($_GET['edit'])){ ?>
            $('#btn-save').prop('disabled',false);
            $('#btn-print').prop('disabled',true);
            $('form').find('input').each(function(){ $(this).prop("disabled", false); });
            $('form').find('select').each(function(){ $(this).prop("disabled", false); });
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'kode') ?>").prop("disabled", true);
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'keterangan') ?>").prop("disabled", false);
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'refisi_ke') ?>").prop("disabled", true);
            $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_mulai') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
            $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_selesai') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
        <?php } ?>
    }

    function getItems(spk_sawmill_id,edit=null){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/spksawmill/getItems']); ?>',
            type   : 'POST',
            data   : {spk_sawmill_id:spk_sawmill_id,edit:edit},
            success: function (data) {
                if(data.html){
                    $('#table-detail > tbody').html(data.html);
                    $('#table-detail tbody > tr').each(function(){
                        $(this).find('select[name*="[size]"]').select2({
                            allowClear: !0,
							placeholder: 'Masukkan t x l',
                            width: '100%'
                        });
                        $(this).find('.select2-selection').css('font-size','1.2rem');
					    $(this).find('.select2-selection').css('padding-left','5px');
                    });
                }
                if(edit){
                    if(data.model.approval_status == 'APPROVED'){
                        setRevisi(spk_sawmill_id);
                    }
                }
                setTimeout(function(){
                    reordertable('#table-detail');
                },500);
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function cancelSPK(spk_sawmill_id){
        openModal('<?php echo yii\helpers\Url::toRoute(['/ppic/spksawmill/cancelSPK']) ?>?id='+spk_sawmill_id,'modal-transaksi');
    }

    function addPjg(ele){
        var place = $(ele).closest('td').find('.place-panjang');
        var newInput = $('<input type="text" class="form-control float" name="TSpkSawmillDetail[ii][panjang][]" style="display: inline-block; font-size: 1.2rem; width: 70px; margin-right: 3px;">');
        place.append(newInput);
        newInput.focus();
        reordertable('#table-detail');
    }
    
    function removePjg(ele){
        var place = $(ele).closest('td').find('.place-panjang');
        if (place.find('input').length > 1) {
            place.find('input').last().remove();
        }
        var field = place.find('input').last();
        if (field.length) {
            field.focus();
        }
        reordertable('#table-detail');
    }

    function printSPK(id){
        var caraPrint = "PRINT";
        window.open("<?= yii\helpers\Url::toRoute(['/ppic/spksawmill/printSPK', 'id' => '']) ?>" + id + "&caraprint=" + caraPrint, "", 'location=_new, width=1200px, scrollbars=yes');
    }

    function setRevisi(id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/spksawmill/setRevisi']); ?>',
            type   : 'POST',
            data   : {id:id},
            success: function (data) {
                var revisi = data + 1;
                $("#<?= \yii\helpers\Html::getInputId($model, 'refisi_ke') ?>").val(revisi);
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    } 

    let currentDropdown = null;
    function addListSize(ele){
        currentDropdown = $(ele).closest('td').find('select[name*="[size]"]');
        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/spksawmill/addListSize']) ?>','modal-add','50%');
    }
</script>