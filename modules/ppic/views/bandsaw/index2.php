<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Bandsaw Sawmill';
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
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Bandsaw Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Bandsaw'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
                                    <div class="col-md-6">
                                        <?php 
										if(!isset($_GET['bandsaw_id'])){ ?>
											<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);?>
										<?php }else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%; font-weight:bold;', 'disabled'=>'']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        <?php if(!isset($_GET['bandsaw_id']) || isset($_GET['edit'])){ ?>
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode SPK'); ?></label>
                                                <div class="col-md-8">
                                                    <span class="input-group-btn" style="width: 100%">
                                                        <?php
                                                        echo \yii\bootstrap\Html::activeDropDownList($model, 'spk_sawmill_id', $model->spk_sawmill_id ? [$model->spk_sawmill_id => $model->kode_spk] : [],['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;', 'onchange'=>'setSPK();']); 
                                                        ?>
                                                    </span>
                                                    <span class="input-group-btn" style="width: 20%">
                                                        <a class="btn btn-icon-only btn-default tooltips" onclick="openSPK();" data-original-title="Daftar SPK" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <?= $form->field($model, 'kode_spk')->textInput(['readonly'=>true])->label('Kode SPK'); ?>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'line_sawmill')->dropDownList(\app\models\MDefaultValue::getOptionList('line-sawmill'),['prompt'=>'']); ?>
                                        <?= $form->field($model, 'tanggal', [
                                                'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;" disabled><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['disabled'=>'']); ?>
                                        
                                        <?php if(isset($_GET['bandsaw_id'])){ ?>
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
                                                        // if($model->approval_status == 'Not Confirmed'){ ?>
                                                        <a href="javascript:void(0);" onclick="cancelBandsaw(<?= $model->bandsaw_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Bandsaw'); ?></a>
                                                    <?php //}
                                                    }?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
								</div>
                                <br>
                                <div class="row">
                                    <h4><?= Yii::t('app', 'Detail Bandsaw'); ?></h4>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th><?= Yii::t('app', 'No.<br>Bandsaw'); ?></th>
                                                        <th><?= Yii::t('app', 'Size'); ?></th>
                                                        <th><?= Yii::t('app', 'Panjang'); ?></th>
                                                        <th><?= Yii::t('app', 'Qty'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													
												</tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Print'), ['id' => 'btn-print', 'class' => 'btn blue btn-outline ciptana-spin-btn', 'onclick' => 'printBreakdown(' . (isset($_GET['bandsaw_id']) ? $_GET['bandsaw_id'] : '') . ');', 'disabled' => true]); ?>
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
if(isset($_GET['bandsaw_id'])){
    $pagemode = "afterSaveThis(". $_GET['bandsaw_id'] .");";
}else {
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
    $('select[name*=\"[spk_sawmill_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode SPK Sawmill',
		ajax: {
			url: '".\yii\helpers\Url::toRoute('/ppic/bandsaw/findSPK')."',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data,
                    edit: '". (isset($_GET['edit'])?$_GET['edit']:'') ."',
                    id: '". (isset($_GET['bandsaw_id'])?$_GET['bandsaw_id']:'') ."'
				};
			},
			cache: true
		}
	});
", yii\web\View::POS_READY); ?>
<script>
    function openSPK(){
        var edit = '<?= isset($_GET['edit'])?$_GET['edit']:'' ?>';
        var id = '<?= isset($_GET['bandsaw_id'])?$_GET['bandsaw_id']:''; ?>';
        $(".modals-place-3-min").load(
            '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/openSPK']) ?>', 
            { id: id, edit: edit },
            function(response) {
                $("#modal-master .modal-dialog").css('width','90%');
                $("#modal-master").modal('show');
                $("#modal-master").on('hidden.bs.modal', function () {});
                spinbtn();
                draggableModal();
            }
        );
    }

    function setSPK(){
        var spk_sawmill_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>').val();
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/setSPK']); ?>',
            type   : 'POST',
            data   : {spk_sawmill_id:spk_sawmill_id},
            success: function (data) {
                if(data){
                    $('#<?= yii\bootstrap\Html::getInputId($model, "kayu_id") ?>').val(data.kayu_id).trigger('change');
                    $('#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>').val(data.line_sawmill);
                    getItems(spk_sawmill_id);
                }
            },
            error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
        });
    }

    function pick(spk_sawmill_id,kode){
        $("#modal-master").find('button.fa-close').trigger('click');
        $("#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>").empty().append('<option value="'+spk_sawmill_id+'">'+kode+'</option>').val(spk_sawmill_id).trigger('change');
    }

    function getItems(spk_sawmill_id, bandsaw_id=null, edit=null){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/getItems']); ?>',
            type   : 'POST',
            data   : {spk_sawmill_id:spk_sawmill_id,bandsaw_id:bandsaw_id,edit:edit},
            success: function (data) {
                if(data.html){
                    $('#table-detail > tbody').html(data.html);
                }
                setTimeout(function(){
                    reordertable('#table-detail');
                },500);
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function hitung(ele, i, p){
        var now = Date.now(); // ambil waktu now (milidetik)
        var lastClick = $(ele).data('lastClick') || 0;

        // jika klik terlalu cepat (< 1000 ms/1 s), abaikan
        if (now - lastClick < 1000) {
            return;
        }
        // untuk simpan waktu klik terakhir
        $(ele).data('lastClick', now);
        
        // tambah qty
        var jml_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + i + '][' + p + '][jml]"]');
        var qty_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + i + '][' + p + '][qty]"]');
        var qty2_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + i + '][' + p + '][qty2]"]');

        var jml = jml_field.val() || '';
        var qty = parseInt(qty_field.val()) || 0;

        if (jml.length < 5) {
            jml += '1';
        } else {
            qty += 5; 
            jml = '1';
        }
        var qty2 = jml.length + qty;

        jml_field.val(jml);
        qty_field.val(qty);
        qty2_field.val(qty2);
    }

    function remove(ele, i, p){
        var now = Date.now(); // ambil waktu now (milidetik)
        var lastClick = $(ele).data('lastClick') || 0;

        // jika klik terlalu cepat (< 1000 ms/1 s), abaikan
        if (now - lastClick < 1000) {
            return;
        }
        // untuk simpan waktu klik terakhir
        $(ele).data('lastClick', now);

        // kurangi qty
        var jml_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + i + '][' + p + '][jml]"]');
        var qty_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + i + '][' + p + '][qty]"]');
        var qty2_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + i + '][' + p + '][qty2]"]');

        var jml = jml_field.val() || '';
        var qty = parseInt(qty_field.val()) || 0;
        
        if (jml.length > 1) {
            jml = jml.slice(0, -1);
        } else if (jml.length === 1) {
            if (qty <= 0 && jml.length === 1) {
                jml = '';
            } else {
                jml = '11111';
                qty = Math.max(0, qty - 5);
            }
        }
        var qty2 = jml.length + qty;

        jml_field.val(jml);
        qty_field.val(qty);
        qty2_field.val(qty2);
    }

    function addPjg (ele, i) {
        var panjangContainer = $(ele).closest('tr').find('.place-panjang-' + i).last();
        var jmlContainer = $(ele).closest('tr').find('.place-jml-' + i).last();
        var p = $(ele).closest('tr').find('.place-panjang-' + i).length;

        var newPanjang = `
            <div class="place-panjang-${i}" style="display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
                <input type="text" name="TBandsawDetail[${i}][${p}][panjang]" class="form-control float" style="width:60px; font-size:1.2rem;">
                <a class="btn btn-xs blue-hoki btn-outline" style="margin-top: 5px;" onclick="hitung(this, ${i}, ${p});">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        `;
        var newJml = `
            <div class="place-jml-${i}" style="display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
                <input type="text" name="TBandsawDetail[${i}][${p}][jml]" class="form-control" style="width:60px; font-size:1.2rem;" disabled>
                <input type="text" name="TBandsawDetail[${i}][${p}][qty]" class="form-control float" style="width:60px; font-size:1.2rem; text-align: right;" disabled>
                <center><a class="btn btn-xs red" onclick="remove(this, ${i}, ${p});"><i class="fa fa-minus"></i></a></center>
                <input type="text" name="TBandsawDetail[${i}][${p}][qty2]" class="form-control float" style="width:60px; font-size:1.2rem; text-align: right;" disabled>
            </div>
        `;

        $(panjangContainer).after(newPanjang);
        $(jmlContainer).after(newJml);
        $('.place-panjang-' + i).find('input[name="TBandsawDetail[' + i + '][' + p + '][panjang]"]').last().focus();
        reordertable('#table-detail');
    }

    function removePjg(ele, i) {
        var row = $(ele).closest('tr');

        var panjangDivs = row.find('.place-panjang-' + i);
        var jmlDivs = row.find('.place-jml-' + i);

        if (panjangDivs.length > 1) {
            panjangDivs.last().remove();
            jmlDivs.last().remove();
        }
        $('.place-panjang-' + i).find('input[name="TBandsawDetail[' + i + '][' + p + '][panjang]"]').last().focus();
        reordertable('#table-detail');
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

        var spk_sawmill_id = $("#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>");
        var line_sawmill = $("#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>");

        if(!spk_sawmill_id.val()){
            $(spk_sawmill_id).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(spk_sawmill_id).parents('.form-group').removeClass('error-tb-detail');
        }
        if(!line_sawmill.val()){
            $(line_sawmill).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(line_sawmill).parents('.form-group').removeClass('error-tb-detail');
        }

        $('#table-detail tbody > tr').each(function(){
            var nomor_bandsaw = $(this).find('select[name*="[nomor_bandsaw]"]');

            if(!nomor_bandsaw.val()){
				$(this).find('select[name*="[nomor_bandsaw]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('select[name*="[nomor_bandsaw]"]').parents('td').removeClass('error-tb-detail');
			}

            $(this).find('input[name*="[panjang]"]').each(function(){
                if(!$(this).val() || $(this).val() <= 0){
                    $(this).addClass('error-tb-detail');
                    has_error++;
                } else {
                    $(this).removeClass('error-tb-detail');
                }
            });

            $(this).find('input[name*="[jml]"]').each(function(){
                if(!$(this).val()){
                    $(this).addClass('error-tb-detail');
                    has_error++;
                } else {
                    $(this).removeClass('error-tb-detail');
                }
            });
            $(this).find('input[name*="[qty]"]').each(function(){
                if(!$(this).val()){
                    $(this).addClass('error-tb-detail');
                    has_error++;
                } else {
                    $(this).removeClass('error-tb-detail');
                }
            });
        });

        if(has_error === 0){
            return true;
        }
        return false;
    }

    function daftarAfterSave(){
        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/daftarAfterSave']) ?>','modal-aftersave','90%');
    }

    function afterSaveThis(bandsaw_id){
        <?php if(!isset($_GET['edit'])){ ?>
            getItems(spk_sawmill_id=null, bandsaw_id);
            // $('#btn-add-item').hide();
        <?php }else{ ?>
            getItems(spk_sawmill_id=null, bandsaw_id, 1);
        <?php } ?>
        
        $('#btn-save').attr('disabled','');
        $('#btn-print').removeAttr('disabled');
        $("#<?= \yii\bootstrap\Html::getInputId($model, 'line_sawmill') ?>").prop("disabled", true);
        <?php if(isset($_GET['edit'])){ ?>
            $('#btn-save').prop('disabled',false);
            $('#btn-print').prop('disabled',true);
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'line_sawmill') ?>").prop("disabled", false);
        <?php } ?>
    }

</script>