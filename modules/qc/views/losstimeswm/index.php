<?php
/* @var $this yii\web\View */

$this->title = 'Losstime Sawmill';
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
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Losstime Sawmill Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Losstime Sawmill'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
                                    <div class="col-md-6">
                                        <?php 
										if(!isset($_GET['losstime_swm_id'])){ ?>
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
                                                    $kode_spk = [];
                                                    if($model->spk_sawmill_id){
                                                        $modSpk = \app\models\TSpkSawmill::findOne($model->spk_sawmill_id);
                                                        $kode_spk = [$model->spk_sawmill_id => $modSpk->kode];
                                                    }
                                                    // $kode_spk = $model->spk_sawmill_id ?  : []
                                                    echo \yii\bootstrap\Html::activeDropDownList($model, 'spk_sawmill_id', $kode_spk,['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;', 'onchange'=>'setSPK();']); 
                                                    ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%">
                                                    <a class="btn btn-icon-only btn-default tooltips" id="btn-spk" onclick="openSPK();" data-original-title="Daftar SPK" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <?php if(isset($_GET['losstime_swm_id'])){ ?>
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
                                                        <a href="javascript:void(0);" onclick="cancelLosstime(<?= $model->losstime_swm_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Losstime'); ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'tanggal', [
                                                'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(); ?>
                                        <?= $form->field($model, 'line_sawmill')->dropDownList(\app\models\MDefaultValue::getOptionList('line-sawmill'),['prompt'=>'', 'disabled'=>'']); ?>
                                    </div>
								</div>
                                <br>
                                <div class="row">
                                    <h5><b><?= Yii::t('app', 'Detail Losstime Sawmill'); ?></b></h5>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th rowspan="2"><?= Yii::t('app', 'Nomor Bandsaw'); ?></th>
                                                        <th rowspan="2"><?= Yii::t('app', 'Kategori Losstime'); ?></th>
                                                        <th colspan="2"><?= Yii::t('app', 'Losstime'); ?></th>
                                                        <th rowspan="2"><?= Yii::t('app', 'Keterangan'); ?></th>
                                                        <th rowspan="2" style="width: 50px;"></th>
                                                    </tr>
                                                    <tr>
                                                        <th><?= Yii::t('app', 'Start'); ?></th>
                                                        <th><?= Yii::t('app', 'End'); ?></th>
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
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Print'), ['id' => 'btn-print', 'class' => 'btn blue btn-outline ciptana-spin-btn', 'onclick' => 'printLosstime(' . (isset($_GET['losstime_swm_id']) ? $_GET['losstime_swm_id'] : '') . ');', 'disabled' => true]); ?>
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
if(isset($_GET['losstime_swm_id'])){
    $pagemode = "afterSaveThis(". $_GET['losstime_swm_id'] .");";
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
                    id: '". (isset($_GET['losstime_swm_id'])?$_GET['losstime_swm_id']:'') ."'
				};
			},
			cache: true
		}
	});
", yii\web\View::POS_READY); ?>
<script>
    function openSPK(){
        var edit = '<?= isset($_GET['edit'])?$_GET['edit']:'' ?>';
        var id = '<?= isset($_GET['losstime_swm_id'])?$_GET['losstime_swm_id']:''; ?>';
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

    function addItem(){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qc/losstimeswm/addItem']); ?>',
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

    function setSPK(){
        var spk_sawmill_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>').val();
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qc/losstimeswm/setSPK']); ?>',
            type   : 'POST',
            data   : {spk_sawmill_id:spk_sawmill_id},
            success: function (data) {
                if(data){
                    $('#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>').val(data.line_sawmill);
                }
            },
            error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
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

        $('#table-detail tbody > tr').each(function(){
            var nomor_bandsaw = $(this).find('select[name*="[nomor_bandsaw]"]');
            if(!nomor_bandsaw.val()){
				$(this).find('select[name*="[nomor_bandsaw]"]').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('select[name*="[nomor_bandsaw]"]').removeClass('error-tb-detail');
			}

            var kategori_losstime = $(this).find('select[name*="[kategori_losstime]"]');
            if(!kategori_losstime.val()){
				$(this).find('select[name*="[kategori_losstime]"]').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('select[name*="[kategori_losstime]"]').removeClass('error-tb-detail');
			}

            var losstime_start = $(this).find('input[name*="[losstime_start]"]');
            if(!losstime_start.val()){
				$(this).find('input[name*="[losstime_start]"]').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[losstime_start]"]').removeClass('error-tb-detail');
			}

            var losstime_end = $(this).find('input[name*="[losstime_end]"]');
            if(!losstime_end.val()){
				$(this).find('input[name*="[losstime_end]"]').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[losstime_end]"]').removeClass('error-tb-detail');
			}
        });

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
        $('#btn-spk').addClass('disabled').off('click');
        $('#btn-save').attr('disabled','');
        $('#btn-print').removeAttr('disabled');
        <?php if(isset($_GET['edit'])){ ?>
            $('#btn-save').prop('disabled',false);
            $('#btn-print').prop('disabled',true);
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'spk_sawmill_id') ?>").prop("disabled", false);
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>").prop("disabled", false);
            $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
            $('#btn-spk').removeClass('disabled');
        <?php } ?>
    }

    function getItems(losstime_swm_id, edit=null){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/qc/losstimeswm/getItems']); ?>',
            type   : 'POST',
            data   : {losstime_swm_id:losstime_swm_id,edit:edit},
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

    function daftarAfterSave(){
        openModal('<?= \yii\helpers\Url::toRoute(['/qc/losstimeswm/daftarAfterSave']) ?>','modal-aftersave','90%');
    }

    function printLosstime(id){
        var caraPrint = "PRINT";
        window.open("<?= yii\helpers\Url::toRoute(['/qc/losstimeswm/printLosstime', 'id' => '']) ?>" + id + "&caraprint=" + caraPrint, "", 'location=_new, width=1200px, scrollbars=yes');
    }

    function cancelLosstime(losstime_swm_id){
        openModal('<?php echo yii\helpers\Url::toRoute(['/qc/losstimeswm/cancelLosstime']) ?>?id='+losstime_swm_id,'modal-transaksi');
    }
</script>