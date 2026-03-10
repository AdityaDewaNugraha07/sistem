<?php
/* @var $this yii\web\View */
$this->title = 'Mutasi Gudang Logistik';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-mutasi-gudanglogistik',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
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
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<div class="col-md-12">
						<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarMutasi()"><i class="fa fa-list"></i> <?= Yii::t('app', 'History Mutasi'); ?></a>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Mutasi'); ?></h4></span>
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
										if(!isset($_GET['mutasi_gudanglogistik_id'])){
											echo $form->field($model, 'spb_id')->dropDownList( \app\models\TSpb::getOptionListSpbMutasi(),['class'=>'form-control select2','prompt'=>'','onchange'=>'getItems(this)']);
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode SPB'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'spb_kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->spb_kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Mutasi'); ?></label>
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
															'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
															<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
															{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?php if(isset($_GET['mutasi_gudanglogistik_id'])){ ?>
<!--										<div class="form-group">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Status'); ?></label>
											<div class="col-md-7" style="margin-top:7px;">
												<?php
//												if($model->status == 'TO-DO'){
//													echo '<span class="label label-sm label-info">'.$model->status .'</span>';
//												}else if($model->status == 'INPROGRESS'){
//													echo '<span class="label label-sm label-warning">'.$model->status .'</span>';
//												}else if($model->status == 'CANCEL'){
//													echo '<span class="label label-sm label-danger">'.$model->status .'</span>';
//												}else if($model->status == 'COMPLETE'){
//													echo '<span class="label label-sm label-success">'.$model->status .'</span>';
//												}
												?>
											</div>
										</div>-->
										<?php } ?>
                                    </div>
                                    <div class="col-md-6">
										<?= $form->field($model, 'pegawai_mutasi')->dropDownList(\app\models\MPegawai::getOptionListByDept( !empty($model->pegawai_mutasi)?$model->pegawaiMutasi->departement_id:null ),['class'=>'form-control select2','prompt'=>'','disabled'=>'disabled']); ?>
										<?= $form->field($model, 'departement_id')->dropDownList(\app\models\MDepartement::getOptionList(),['prompt'=>'','disabled'=>true]); ?>
                                        <?= $form->field($model, 'keterangan')->textarea(); ?>
                                    </div>
                                </div>
                                <br><br><hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Mutasi'); ?></h4>
                                    </div>
									<div class="col-md-7">
										<label class="pull-right" style="margin-top:20px; margin-bottom: -20px; text-align: right;">
											<span id="lihatdetailSPB">
												<?php
												if(isset($_GET['mutasi_gudanglogistik_id'])){
													echo "<a onclick='infoSpb(".$model->spb_id.")'>Lihat Detail SPB : <b>".$model->spb->spb_kode."</b></a>";
												}
												?>
											</span>
										</label>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px;">No.</th>
														<th style="width: 35%;"><?= Yii::t('app', 'Nama Item'); ?></th>
														<th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Qty<br>SPB'); ?></th>
														<th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Qty<br>Termutasi'); ?></th>
														<th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Qty<br>Mutasi'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', 'Satuan'); ?></th>
														<th><?= Yii::t('app', 'Keterangan'); ?></th>
														<th style="width: 50px;"><?= Yii::t('app', 'Cancel'); ?></th>
													</tr>
												</thead>
												<tbody></tbody>
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
$pagemode = "";
if(isset($_GET['mutasi_gudanglogistik_id'])){
    $pagemode = "afterSave(".$_GET['mutasi_gudanglogistik_id'].")";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	$('select[name*=\"[spb_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode SPB',
	});
", yii\web\View::POS_READY); ?>
<script>
function getItems(ele){
	$('#table-detail > tbody').addClass('animation-loading');
	var spb_id = $(ele).val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/mutasigudanglogistik/getItems']); ?>',
        type   : 'POST',
        data   : {spb_id:spb_id},
        success: function (data) {
			$('#table-detail > tbody').html("");
			$('#<?= \yii\bootstrap\Html::getInputId($model, 'departement_id') ?>').val('');
			$('#<?= \yii\bootstrap\Html::getInputId($model, 'keterangan') ?>').val('');
			$('#lihatdetailSPB').html("");
            if(data.html){
                $('#table-detail > tbody').html(data.html);
				reordertable('#table-detail');
				$('#table-detail > tbody').removeClass('animation-loading');
				$('#lihatdetailSPB').html("<a onclick='infoSpb("+data.spb.spb_id+")'>Lihat Detail SPB : <b>"+data.spb.spb_kode+"</b></a>");
            }
			if(data.spb){
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'departement_id') ?>').val(data.spb.departement_id);
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'keterangan') ?>').val(data.spb.spb_keterangan);
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function getItemsByPk(id){
	$('#table-detail > tbody').addClass('animation-loading');
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/mutasigudanglogistik/getItemsByPk']); ?>',
        type   : 'POST',
        data   : {id:id},
        success: function (data) {
            if(data.html){
                $('#table-detail > tbody').html(data.html);
				reordertable('#table-detail');
				$('#table-detail > tbody').removeClass('animation-loading');
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function reordertable(obj_table){
    var row = 0;
    $(obj_table).find("tbody > tr").each(function(){
        $(this).find("#no_urut").val(row+1);
        $(this).find("span.no_urut").text(row+1);
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 3){
                $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]);
                $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]");
            }
        });
        row++;
    });
    formconfig();
}

function cancelItem(ele){
    $(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
        reordertable('#table-detail');
    });
}

function save(){
    var $form = $('#form-mutasi-gudanglogistik');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail permintaan terlebih dahulu');
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
    $('#table-detail tbody > tr').each(function(){
		if($(this).find('select[name*="[bhp_id]"]').length > 0){
			var field1content = 'select[name*="[bhp_id]"]';
		}else{
			var field1content = 'input[name*="[bhp_id]"]';
		}
        var field1 = $(this).find(field1content);
        var field2 = $(this).find('input[name*="[qty]"]');
        var field3 = $(this).find('textarea[name*="[keterangan]"]');
        if(!field1.val()){
            $(this).find(field1content).parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find(field1content).parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[qty]"]').parents('td').addClass('error-tb-detail');
        }else{
            $(this).find('input[name*="[qty]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field3.val()){
            has_error = has_error + 1;
            $(this).find('textarea[name*="[keterangan]"]').parents('td').addClass('error-tb-detail');
        }else{
            $(this).find('textarea[name*="[keterangan]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    getItemsByPk(id);
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("readonly","readonly"); });
    $('#<?= yii\bootstrap\Html::getInputId($model, 'pegawai_mutasi') ?>').attr('disabled','');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
}

function daftarMutasi(){
    openModal('<?= \yii\helpers\Url::toRoute(['/gudang/mutasigudanglogistik/daftarMutasi']) ?>','modal-daftar-mutasi','90%');
}

function infoSpb(spb_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpb','id'=>'']); ?>'+spb_id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-spb").modal('show');
		$("#modal-info-spb").on('hidden.bs.modal', function () {

		});
		spinbtn();
		draggableModal();
	});
}
</script>