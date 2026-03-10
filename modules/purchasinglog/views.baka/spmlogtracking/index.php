<?php
/* @var $this yii\web\View */
$this->title = 'SPM Log Tracking';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\MagnificPopupAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<style>
table.table thead tr th{
	font-size: 1.3rem;
	padding: 2px;
	border: 1px solid #A0A5A9;
}
table.table#table-detail-permintaan thead tr th{
	padding: 10px;
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4> <?=$this->title;?> </h4></span>
                                </div>
                                <div class="tools">
                                    <a class="btn blue btn-sm btn-outline pull-right" style="height: 30px;" onclick="daftarSpmLogTrackingX()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar SPM Log'); ?></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php /* FORM START */?>
                                <?php $form = \yii\bootstrap\ActiveForm::begin([
                                    'id' => 'form-transaksi',
                                    'fieldConfig' => [
                                        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                                        'labelOptions'=>['class'=>'col-md-4 control-label'],
                                    ],
                                ]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
                                <?php
                                if (isset($_GET['success']) && isset($_GET['spk_shipping_tracking_id'])) {
                                    $disabled = true;
                                    $openDaftarSpmLog = "";
                                } else {
                                    $disabled = false;
                                    $openDaftarSpmLog = "openDaftarSpmLog();";
                                }
                                ?>

                                <div class="row">
                                    <div class="col-md-6">
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'spk_shipping_tracking_id'); ?>
										<?= $form->field($model, 'tanggal',[
											'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date time-picker">{input} <span class="input-group-btn">
														 <button class="btn default" type="button" style="margin-left: 0px;" style="'.$tanggal.'","><i class="fa fa-clock-o"></i></button></span></div> 
														 {error}</div>'])->textInput(['disabled'=>$disabled]); ?>
                                        
                                        <?= $form->field($model, 'jenis')->dropDownList(\app\models\MDefaultValue::getOptionList('shipping_tracking'),['class'=>'form-control', 'disabled'=>$disabled, 'value'=>$model->jenis]) ?>
                                        <label id="label_kode_spm_log" class="col-md-4 control-label text-left"><?= Yii::t('app', 'Kode SPM Log'); ?></label>
                                        <div class="col-md-8" style="margin-left: -5px;">
                                            <span class="input-group-btn" style="width: 270px;">
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'spk_shipping_id', \app\models\TSpkShipping::getOptionList(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setSpmLog()', 'disabled'=>$disabled]); ?>
                                            </span>
                                            <span class="input-group-btn" style="width: 30px;">
                                                <a id="span_button_kode_spm_log" class="btn btn-icon-only btn-default tooltips" onclick="<?php echo $openDaftarSpmLog;?>" data-original-title="Daftar SPM Log" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'lokasi')->textInput(['value'=>$model->lokasi, 'disabled'=>$disabled]); ?>
                                        <?= $form->field($model, 'keterangan')->textarea(['rows'=>3,'value'=>$model->keterangan, 'disabled'=>$disabled]); ?>
									</div>
                                </div>
                                <?php /* EOF START */?>
                                <br>
                                <div class="row">
                                    <div class="form-actions pull-right">
                                        <div class="col-md-12 right">
                                            <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();','disabled'=>$disabled]); ?>
                                            <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
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
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	$('select[name*=\"[spk_shipping_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode SPM Log',
		ajax: {
			url: '".\yii\helpers\Url::toRoute('/purchasinglog/spmlogtracking/findSpmLog')."',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		}
	});
	$('#tspkshippingtracking-tanggal').datetimepicker({
		autoclose: !0,
		isRTL: App.isRTL(),
		format: 'dd/mm/yyyy hh:ii',
		fontAwesome: !0,
		pickerPosition: App.isRTL() ? 'bottom-right' : 'bottom-left',
		orientation: 'left',
		clearBtn:true,
		todayHighlight:true,
		minuteStep: 1,
		todayBtn: true,
		//startDate: new Date(new Date().setDate(new Date().getDate() - 30)),
		endDate: new Date(new Date().setDate(new Date().getDate() + 0))
	});    
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('SPM Log Tracking'))."');
", yii\web\View::POS_READY); ?>
<script>
function setSpmLog(){
	var spk_shipping_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spk_shipping_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlogtracking/findSpmLog']); ?>',
        type   : 'POST',
        data   : {spk_shipping_id:spk_shipping_id},
        success: function (data) {
			if(data.spk_shipping_id){
				$("#modal-master").find('button.fa-close').trigger('click');
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function openDaftarSpmLog(){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlogtracking/daftarSpmLog']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-daftarSpmLog .modal-dialog").css('width','90%');
		$("#modal-daftarSpmLog").modal('show');
		$("#modal-daftarSpmLog").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function pickDaftarSpmLog(spk_shipping_id, kode){
	$("#modal-daftarSpmLog").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "spk_shipping_id") ?>").empty().append('<option value="'+spk_shipping_id+'">'+kode+'</option>').val(spk_shipping_id).trigger('change');
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var tanggal = $('#tspkshippingtracking-tanggal').val();
        var jenis = $('#tspkshippingtracking-jenis').val();
        var kode = $('#select2-tspkshippingtracking-spk_shipping_id-container').text();
        var lokasi = $('#tspkshippingtracking-lokasi').val();
        if(tanggal == '' || tanggal == null || jenis == '' || jenis == null || kode == 'Ketik Kode SPM Log' || kode == null || lokasi == '' || lokasi == null){
            if (tanggal == '' || tanggal == null) {
                cisAlert('Isi tanggal dulu');
            }
            if (jenis == '' || jenis == null) {
                cisAlert('Isi jenis dulu');
            }
            if (kode == 'Ketik Kode SPM Log' || kode == null) {
                $("#label_kode_spm_log").removeAttr("style");
                $("#tspkshippingtracking-spk_shipping_id").removeAttr("style");
                $("#span_button_kode_spm_log").removeAttr("style");

                $('#label_kode_spm_log'). attr("style","color: #E73D4A");
                $('#tspkshippingtracking-spk_shipping_id'). attr("style","border: solid 1px #E73D4A; border-radius: 5px; color: #E73D4A");
                $('#span_button_kode_spm_log'). attr("style","border: solid 1px #E73D4A; border-radius: 4px; color: #E73D4A, margin-left: 3px; ");
                cisAlert('Isi kode dulu');
            }
            if (lokasi == '' || lokasi == null) {
                cisAlert('Isi lokasi dulu');
            }
            return false;
        } else {
            console.log('disini');
            submitform($form);
        }
    }
    return false;
}

function daftarSpmLogTrackingX() {
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlogtracking/daftarSpmLogTracking']) ?>','modal-daftarSpmLogTracking','90%');
}

function editSpmLogTracking(id) {
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlogtracking/index','spk_shipping_tracking_id'=>'']); ?>'+id+'&edit=1');
}
</script>