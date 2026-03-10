<?php
/* @var $this yii\web\View */

$this->title = 'Defect Sawmill';
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
.highlightable:focus {
    background-color: #cce5ff !important; 
    border-color: #66b0ff !important;
}

</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<span class="pull-right">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Defect Sawmill Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Defect Sawmill'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
                                    <div class="col-md-6">
                                        <?php 
										if(!isset($_GET['defect_swm_id'])){ ?>
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
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode SPK'); ?></label>
                                            <div class="col-md-8">
                                                <span class="input-group-btn" style="width: 100%">
                                                    <?php
                                                    echo \yii\bootstrap\Html::activeDropDownList($model, 'spk_sawmill_id', $model->spk_sawmill_id ? [$model->spk_sawmill_id => $model->kode] : [],['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;', 'onchange'=>'setSPK();']); 
                                                    ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%">
                                                    <a class="btn btn-icon-only btn-default tooltips" id="btn-spk" onclick="openSPK();" data-original-title="Daftar SPK" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <?= $form->field($model, 'tanggal', [
                                                'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(); ?>
                                        <?php if(isset($_GET['defect_swm_id'])){ ?>
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
                                                    <?php } else { ?>
                                                        <a href="javascript:void(0);" onclick="cancelDefect(<?= $model->defect_swm_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Defect'); ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'line_sawmill')->dropDownList(\app\models\MDefaultValue::getOptionList('line-sawmill'),['prompt'=>'', 'disabled'=>'']); ?>
                                        <?= $form->field($model, 'kayu_id')->dropDownList(\app\models\MKayu::getOptionListNamaKayu(),['class'=>'form-control select2','prompt'=>'', 'disabled'=>''])->label('Jenis Kayu'); ?>
                                        <?= $form->field($model, 'nomor_bandsaw')->dropDownList(\app\models\MDefaultValue::getOptionList('nomor-bandsaw'),['class'=>'form-control','prompt'=>''])->label('Nomor Bandsaw'); ?>
                                    </div>
								</div>
                                <br>
                                <div class="row">
                                    <h5><b><?= Yii::t('app', 'Detail Defect Sawmill'); ?></b></h5>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th><?= Yii::t('app', 'No.'); ?></th>
                                                        <th><?= Yii::t('app', 'Size'); ?></th>
                                                        <th><?= Yii::t('app', 'Panjang'); ?></th>
                                                        <th><?= Yii::t('app', 'Kategori Defect'); ?></th>
                                                        <th><?= Yii::t('app', 'Qty'); ?></th>
                                                        <th><?= Yii::t('app', 'Keterangan'); ?></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													
												</tbody>
                                            </table>
                                            <a class="btn btn-xs blue-hoki btn-outline" id="btn-add-item" onclick="addItem()"><i class="fa fa-plus"></i> Add Item</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Print'), ['id' => 'btn-print', 'class' => 'btn blue btn-outline ciptana-spin-btn', 'onclick' => 'printDefect(' . (isset($_GET['defect_swm_id']) ? $_GET['defect_swm_id'] : '') . ');', 'disabled' => true]); ?>
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
if(isset($_GET['defect_swm_id'])){
    $pagemode = "afterSaveThis(". $_GET['defect_swm_id'] .");";
}else {
    $pagemode = "addItem();";
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
                    id: '". (isset($_GET['defect_swm_id'])?$_GET['defect_swm_id']:'') ."'
				};
			},
			cache: true
		}
	});
    $(this).find('select[name*=\"[kayu_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Kayu',
		width: null
	});
", yii\web\View::POS_READY); ?>
<script>
    function setSPK(){
        var spk_sawmill_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>').val();
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qc/defectswm/setSPK']); ?>',
            type   : 'POST',
            data   : {spk_sawmill_id:spk_sawmill_id},
            success: function (data) {
                if(data){
                    $('#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>').val(data.line_sawmill);
                    $('#<?= yii\bootstrap\Html::getInputId($model, "kayu_id") ?>').val(data.kayu_id).trigger('change');
                }
            },
            error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
        });
    }
    
    function addItem(){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qc/defectswm/addItem']); ?>',
            type   : 'POST',
            data   : {},
            success: function (data) {
                if(data.item){
                    $(data.item).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                        reordertable('#table-detail');
                    });
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function openSPK(){
        var edit = '<?= isset($_GET['edit'])?$_GET['edit']:'' ?>';
        var id = '<?= isset($_GET['defect_swm_id'])?$_GET['defect_swm_id']:''; ?>';
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

    function pick(spk_sawmill_id,kode){
        $("#modal-master").find('button.fa-close').trigger('click');
        $("#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>").empty().append('<option value="'+spk_sawmill_id+'">'+kode+'</option>').val(spk_sawmill_id).trigger('change');
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
        $('#btn-spk').addClass('disabled').off('click');
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('#btn-save').attr('disabled','');
        $('#btn-print').removeAttr('disabled');
        <?php if(isset($_GET['edit'])){ ?>
            $('#btn-save').prop('disabled',false);
            $('#btn-print').prop('disabled',true);
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'spk_sawmill_id') ?>").prop("disabled", false);
            $('#btn-spk').removeClass('disabled');
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>").prop("disabled", false);
            $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'nomor_bandsaw') ?>").prop("disabled", false);
        <?php } ?>
    }

    function getItems(defect_swm_id, edit=null){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qc/defectswm/getItems']); ?>',
            type   : 'POST',
            data   : {defect_swm_id:defect_swm_id,edit:edit},
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
        if(!spk_sawmill_id.val()){
            $(spk_sawmill_id).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(spk_sawmill_id).parents('.form-group').removeClass('error-tb-detail');
        }

        var line_sawmill = $("#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>");
        if(!line_sawmill.val()){
            $(line_sawmill).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(line_sawmill).parents('.form-group').removeClass('error-tb-detail');
        }

        var kayu_id = $("#<?= yii\bootstrap\Html::getInputId($model, "kayu_id") ?>");
        if(!kayu_id.val()){
            $(kayu_id).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(kayu_id).parents('.form-group').removeClass('error-tb-detail');
        }

        var nomor_bandsaw = $("#<?= yii\bootstrap\Html::getInputId($model, "nomor_bandsaw") ?>");
        if(!nomor_bandsaw.val()){
            $(nomor_bandsaw).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(nomor_bandsaw).parents('.form-group').removeClass('error-tb-detail');
        }

        $('#table-detail tbody > tr').each(function(){
            var produk_t = $(this).find('input[name*="[produk_t]"]');
            if(!produk_t.val() || produk_t.val() <= 0){
				$(this).find('input[name*="[produk_t]"]').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[produk_t]"]').removeClass('error-tb-detail');
			}

            var produk_l = $(this).find('input[name*="[produk_l]"]');
            if(!produk_l.val() || produk_l.val() <= 0){
				$(this).find('input[name*="[produk_l]"]').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[produk_l]"]').removeClass('error-tb-detail');
			}

            var produk_p = $(this).find('input[name*="[produk_p]"]');
            if(!produk_p.val() || produk_p.val() <= 0){
				$(this).find('input[name*="[produk_p]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[produk_p]"]').parents('td').removeClass('error-tb-detail');
			}

            var kategori_defect = $(this).find('select[name*="[kategori_defect]"]');
            if(!kategori_defect.val()){
				$(this).find('select[name*="[kategori_defect]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('select[name*="[kategori_defect]"]').parents('td').removeClass('error-tb-detail');
			}

            var qty = $(this).find('input[name*="[qty]"]');
            if(!qty.val() || qty.val() <= 0){
				$(this).find('input[name*="[qty]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[qty]"]').parents('td').removeClass('error-tb-detail');
			}
        });

        if(has_error === 0){
            return true;
        }
        return false;
    }

    function daftarAfterSave(){
        openModal('<?= \yii\helpers\Url::toRoute(['/qc/defectswm/daftarAfterSave']) ?>','modal-aftersave','90%');
    }

    function printDefect(id){
        var caraPrint = "PRINT";
        window.open("<?= yii\helpers\Url::toRoute(['/qc/defectswm/printDefect', 'id' => '']) ?>" + id + "&caraprint=" + caraPrint, "", 'location=_new, width=1200px, scrollbars=yes');
    }

    function cancelDefect(defect_swm_id){
        openModal('<?php echo yii\helpers\Url::toRoute(['/qc/defectswm/cancelDefect']) ?>?id='+defect_swm_id,'modal-transaksi');
    }
</script>